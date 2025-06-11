<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\Cupom;
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

        $frete = 20;
        if ($total >= 52 && $total <= 166.59) {
            $frete = 15;
        } elseif ($total > 200) {
            $frete = 0;
        }

        return view('carrinho.index', compact('carrinho', 'total', 'subtotal', 'frete', 'desconto'));
    }

    public function finalizar(Request $request)
    {
        $carrinho = session('carrinho', []);
        $subtotal = array_sum(array_column($carrinho, 'preco'));
        $cep = $request->cep;

        $frete = 20;
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif ($subtotal > 200) {
            $frete = 0;
        }

        $dadosEndereco = Http::get("https://viacep.com.br/ws/{$cep}/json/")->json();

        $pedido = Pedido::create([
            'subtotal' => $subtotal,
            'frete' => $frete,
            'cep' => $cep,
            'endereco' => $dadosEndereco['logradouro'] ?? 'Desconhecido',
        ]);

        session()->forget('carrinho');

        try {
            Mail::raw("Seu pedido foi recebido!\nEndereço: " . ($dadosEndereco['logradouro'] ?? 'Desconhecido'), function ($message) {
                $message->to('cliente@exemplo.com')->subject('Pedido confirmado');
            });
        } catch (\Exception $e) {
            // Loga o erro para análise, mas não interrompe o fluxo
            Log::error('Erro ao enviar e-mail do pedido: ' . $e->getMessage());
        }

        return redirect()->route('produtos.index')->with('success', 'Pedido finalizado!');
    }

    public function limpar()
    {
        session()->forget('carrinho');
        return redirect()->route('carrinho.index')->with('success', 'Carrinho limpo com sucesso!');
    }
}
