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
            return back()
                ->withErrors(['message' => 'Variação do produto não encontrada.'])
                ->withInput();
        }

        $estoque = $variacao->estoque->quantidade ?? 0;
        if ($request->quantidade > $estoque) {
            return back()
                ->withErrors(['message' => "Só temos {$estoque} unidade(s) em estoque."])
                ->withInput();
        }

        // Pega o carrinho da sessão (array)
        $carrinho = session('carrinho', []);

        // Identificador único para evitar duplicidade (exemplo: variacao_id)
        $key = $variacao->id;

        if (isset($carrinho[$key])) {
            // Atualiza a quantidade somando a existente
            $novoTotal = $carrinho[$key]['quantidade'] + $request->quantidade;

            if ($novoTotal > $estoque) {
                return back()
                    ->withErrors(['message' => "Só temos {$estoque} unidade(s) em estoque."])
                    ->withInput();
            }

            $carrinho[$key]['quantidade'] = $novoTotal;
            $carrinho[$key]['subtotal'] = $carrinho[$key]['preco'] * $novoTotal; // << AQUI

        } else {
            // Adiciona novo item no carrinho
            $carrinho[$key] = [
                'produto_id' => $produto->id,
                'variacao_id' => $variacao->id,
                'nome' => $produto->nome . ' — ' . $variacao->nome,
                'preco' => $produto->preco, // ou preço da variação se for diferente
                'quantidade' => $request->quantidade,
                'subtotal' => $produto->preco * $request->quantidade, // << AQUI
            ];
        }


        session(['carrinho' => $carrinho]);

        return redirect()->route('carrinho.index')->with('success', 'Produto adicionado ao carrinho!');
    }
}
