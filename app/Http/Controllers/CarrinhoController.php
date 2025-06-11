<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\Cupom;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CarrinhoController extends Controller
{
    public function adicionar(Request $request, Produto $produto)
    {
        $carrinho = session()->get('carrinho', []);

        $carrinho[] = [
            'produto_id' => $produto->id,
            'nome' => $produto->nome,
            'preco' => $produto->preco,
        ];

        session()->put('carrinho', $carrinho);

        return redirect()->route('carrinho.index')->with('success', 'Produto adicionado!');
    }

    public function index()
    {
        $carrinho = session('carrinho', []);
        $subtotal = array_sum(array_column($carrinho, 'preco'));

        $frete = 20;
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif ($subtotal > 200) {
            $frete = 0;
        }

        return view('carrinho.index', compact('carrinho', 'subtotal', 'frete'));
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
}
