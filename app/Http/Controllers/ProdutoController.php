<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Produto;
use App\Models\Variacao;
use App\Models\Estoque;

class ProdutoController extends Controller
{
    private function consultarCep($cep)
    {
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
        return $response->json();
    }

    public function index()
    {
        $produtos = Produto::with('variacoes.estoque')->get();
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'preco' => 'required|numeric',
            'variacoes.*.nome' => 'required',
            'variacoes.*.quantidade' => 'required|integer',
        ]);

        $produto = Produto::create($request->only('nome', 'preco'));

        foreach ($request->variacoes as $var) {
            $v = $produto->variacoes()->create(['nome' => $var['nome']]);
            $v->estoque()->create(['quantidade' => $var['quantidade']]);
        }

        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso!');
    }
    public function storeJson(Request $request)
    {
        // Validação do JSON recebido
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'variacoes' => 'array',
            'variacoes.*.nome' => 'required_with:variacoes|string',
            'variacoes.*.quantidade' => 'required_with:variacoes|integer',
        ]);

        // Cria o produto
        $produto = Produto::create($request->only('nome', 'preco'));

        // Cria variações e estoques, se existir
        if ($request->has('variacoes')) {
            foreach ($request->variacoes as $var) {
                $variacao = $produto->variacoes()->create(['nome' => $var['nome']]);
                $variacao->estoque()->create(['quantidade' => $var['quantidade']]);
            }
        }

        return response()->json([
            'message' => 'Produto criado com sucesso',
            'produto' => $produto->load('variacoes.estoque')
        ], 201);
    }


    public function edit(Produto $produto)
    {
        $produto->load('variacoes.estoque');
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $produto->update($request->only('nome', 'preco'));

        foreach ($request->variacoes as $var) {
            if (isset($var['id'])) {
                $variacao = \App\Models\Variacao::find($var['id']);
                if ($variacao) {
                    if (isset($var['remover']) && $var['remover'] == "1") {
                        $variacao->estoque()->delete();
                        $variacao->delete();
                    } else {
                        $variacao->update(['nome' => $var['nome']]);
                        if ($variacao->estoque) {
                            $variacao->estoque->update(['quantidade' => $var['quantidade']]);
                        }
                    }
                }
            } else {
                if (!(isset($var['remover']) && $var['remover'] == "1")) {
                    $novaVariacao = $produto->variacoes()->create(['nome' => $var['nome']]);
                    $novaVariacao->estoque()->create(['quantidade' => $var['quantidade']]);
                }
            }
        }

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado!');
    }
    public function updateJson(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'variacoes' => 'array',
            'variacoes.*.id' => 'sometimes|exists:variacoes,id',
            'variacoes.*.nome' => 'required_with:variacoes|string',
            'variacoes.*.quantidade' => 'required_with:variacoes|integer',
        ]);

        // Atualiza os dados básicos do produto
        $produto->update($request->only('nome', 'preco'));

        // Atualiza ou cria variações e seus estoques
        if ($request->has('variacoes')) {
            foreach ($request->variacoes as $var) {
                if (isset($var['id'])) {
                    // Atualiza variação existente
                    $variacao = $produto->variacoes()->find($var['id']);
                    if ($variacao) {
                        $variacao->update(['nome' => $var['nome']]);
                        $variacao->estoque()->updateOrCreate(
                            ['variacao_id' => $variacao->id],
                            ['quantidade' => $var['quantidade']]
                        );
                    }
                } else {
                    // Cria variação nova
                    $variacao = $produto->variacoes()->create(['nome' => $var['nome']]);
                    $variacao->estoque()->create(['quantidade' => $var['quantidade']]);
                }
            }
        }

        return response()->json([
            'message' => 'Produto atualizado com sucesso',
            'produto' => $produto->load('variacoes.estoque'),
        ]);
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Produto removido!');
    }
    public function destroyJson(Produto $produto)
    {
        $produto->delete();

        return response()->json([
            'message' => 'Produto deletado com sucesso',
        ]);
    }
}
