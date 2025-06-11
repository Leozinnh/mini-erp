<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function adicionarAoCarrinho(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|integer|exists:produtos,id',
            'variacao_id' => 'required|integer|exists:variacoes,id',
            'quantidade' => 'required|integer|min:1',
        ]);

        $produto = Produto::with('variacoes.estoque')->findOrFail($request->produto_id);
        $variacao = $produto->variacoes->where('id', $request->variacao_id)->first();

        if (!$variacao) {
            return back()->withErrors(['variacao_id' => 'Variação inválida']);
        }

        $estoque = $variacao->estoque->quantidade ?? 0;
        if ($request->quantidade > $estoque) {
            return back()->withErrors(['quantidade' => 'Quantidade solicitada maior que estoque disponível']);
        }

        // Pega o carrinho da sessão (array)
        $carrinho = session('carrinho', []);

        // Identificador único para evitar duplicidade (exemplo: variacao_id)
        $key = $variacao->id;

        if (isset($carrinho[$key])) {
            // Atualiza a quantidade somando a existente
            $novoTotal = $carrinho[$key]['quantidade'] + $request->quantidade;

            if ($novoTotal > $estoque) {
                return back()->withErrors(['quantidade' => 'Quantidade total no carrinho maior que estoque disponível']);
            }

            $carrinho[$key]['quantidade'] = $novoTotal;
        } else {
            // Adiciona novo item no carrinho
            $carrinho[$key] = [
                'produto_id' => $produto->id,
                'variacao_id' => $variacao->id,
                'nome' => $produto->nome . ' — ' . $variacao->nome,
                'preco' => $produto->preco, // ou variação de preço, se houver
                'quantidade' => $request->quantidade,
            ];
        }

        session(['carrinho' => $carrinho]);

        return redirect()->route('carrinho.index')->with('success', 'Produto adicionado ao carrinho!');
    }
}
