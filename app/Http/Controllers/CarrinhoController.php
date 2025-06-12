<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\Cupom;
use App\Models\Estoque;
use App\Models\Variacao;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CarrinhoController extends Controller
{
    public function index()
    {
        $carrinho = session('carrinho', []);
        $total = array_sum(array_column($carrinho, 'preco'));
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        $desconto = 0;
        $frete = 0;

        return view('carrinho.index', compact('carrinho', 'total', 'subtotal', 'frete', 'desconto'));
    }

    public function finalizar(Request $request)
    {
        $carrinho = session('carrinho', []);
        $subtotal = array_sum(array_column($carrinho, 'subtotal'));
        $cep = $request->cep;

        $frete = 20;
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif ($subtotal > 200) {
            $frete = 0;
        }

        $cupom = Cupom::where('codigo', $request->cupom)
            ->first();
        $desconto = 0;

        if ($cupom) {
            if ($cupom->tipo === 'porcentagem') {
                $desconto = ($subtotal * $cupom->desconto) / 100;
            } else {
                $desconto = $cupom->desconto;
            }
        }

        $totalFinal = $subtotal + $frete - $desconto;

        $dadosEndereco = Http::get("https://viacep.com.br/ws/{$cep}/json/")->json();

        $pedido = Pedido::create([
            'subtotal' => $totalFinal,
            'frete' => $frete,
            'desconto' => $desconto,
            'cupom_id' => $cupom?->id,
            'cep' => $cep,
            'endereco' => $dadosEndereco['logradouro'] ?? 'Desconhecido',
        ]);

        foreach ($carrinho as $item) {
            $pedido->itens()->create([
                'produto_id' => $item['produto_id'],
                'variacao_id' => $item['variacao_id'],
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco'],
                'subtotal' => $item['subtotal'],
            ]);

            $estoque = Estoque::where('variacao_id', $item['variacao_id'])->first();
            if ($estoque) {
                $estoque->quantidade -= $item['quantidade'];
                $estoque->save();
            }
        }

        session()->forget(['carrinho', 'cupom']);

        try {
            Mail::raw("Seu pedido foi recebido!\nEndereço: " . ($dadosEndereco['logradouro'] ?? 'Desconhecido'), function ($message) {
                $message->to('cliente@exemplo.com')->subject('Pedido confirmado');
            });
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail do pedido: ' . $e->getMessage());
        }

        // return redirect()->route('produtos.index')->with('success', 'Pedido finalizado!');
        return redirect()->route('sucesso', ['pedido' => $pedido->id]);
    }

    public function limpar()
    {
        session()->forget('carrinho');
        return redirect()->route('carrinho.index')->with('success', 'Carrinho limpo com sucesso!');
    }
}
