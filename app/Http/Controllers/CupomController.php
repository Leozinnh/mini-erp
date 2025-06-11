<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    public function index()
    {
        $cupons = Cupom::all();
        return view('cupons.index', compact('cupons'));
    }

    public function create()
    {
        return view('cupons.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:cupons',
            'desconto' => 'required|numeric',
            'valor_minimo' => 'required|numeric',
            'validade' => 'required|date',
        ]);

        Cupom::create($request->all());

        return redirect()->route('cupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    public function edit(Cupom $cupom)
    {
        return view('cupons.edit', compact('cupom'));
    }

    public function update(Request $request, Cupom $cupom)
    {
        $cupom->update($request->all());

        return redirect()->route('cupons.index')->with('success', 'Cupom atualizado!');
    }

    public function destroy(Cupom $cupom)
    {
        $cupom->delete();
        return redirect()->route('cupons.index')->with('success', 'Cupom excluído!');
    }
}
