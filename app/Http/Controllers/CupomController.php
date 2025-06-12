<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $validated = $request->validate([
            'codigo' => 'required|unique:cupons,codigo',
            'tipo' => 'required|in:valor,porcentagem',
            'desconto' => 'required|numeric|min:0.01',
            'valor_minimo' => $request->tipo === 'valor' ? 'required|numeric|min:0' : 'nullable',
            'validade' => 'required|date|after_or_equal:today',
        ]);

        Cupom::create($validated);
        return redirect()->route('cupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    public function edit($id)
    {
        $cupom = Cupom::findOrFail($id);
        return view('cupons.edit', compact('cupom'));
    }

    public function update(Request $request, Cupom $cupon)
    {
        $validated = $request->validate([
            'codigo' => [
                'required',
                Rule::unique('cupons', 'codigo')->ignore($cupon->id),
            ],
            'tipo' => 'required|in:valor,porcentagem',
            'desconto' => 'required|numeric|min:0.01',
            'valor_minimo' => $request->tipo === 'valor' ? 'required|numeric|min:0' : 'nullable',
            'validade' => 'required|date|after_or_equal:today',
        ]);

        $cupon->update($validated);

        return redirect()->route('cupons.index')->with('success', 'Cupom atualizado com sucesso!');
    }

    public function validar(Request $request)
    {
        $request->validate([
            'cupom' => 'required|string|max:50',
        ]);

        $codigo = $request->input('cupom');

        $cupom = \App\Models\Cupom::where('codigo', $codigo)
            ->whereDate('validade', '>=', now())
            ->first();

        if (!$cupom) {
            return response()->json([
                'valido' => false,
                'mensagem' => 'Cupom inválido ou expirado.'
            ]);
        }

        $validadeFormatada = Carbon::parse($cupom->validade)->format('Y-m-d');

        return response()->json([
            'valido' => true,
            'codigo' => $cupom->codigo,
            'desconto' => (float) $cupom->desconto,
            'tipo' => $cupom->tipo,
            'valor_minimo' => (float) $cupom->valor_minimo,
            'validade' => $validadeFormatada,
            'mensagem' => 'Cupom válido!'
        ]);
    }


    public function destroy(Cupom $cupon)
    {
        $cupon->delete();
        return response()->json(['success' => true]);
    }
}
