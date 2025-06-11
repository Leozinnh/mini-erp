@extends('layouts.app')

@section('title', 'Editar Cupom')

@section('content')
    <h2>Editar Cupom</h2>

    <form action="{{ route('cupons.update', $cupom) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control" value="{{ $cupom->codigo }}" required>
        </div>

        <div class="mb-3">
            <label for="desconto" class="form-label">Desconto (R$)</label>
            <input type="number" step="0.01" name="desconto" class="form-control" value="{{ $cupom->desconto }}" required>
        </div>

        <div class="mb-3">
            <label for="valor_minimo" class="form-label">Valor Mínimo</label>
            <input type="number" step="0.01" name="valor_minimo" class="form-control" value="{{ $cupom->valor_minimo }}" required>
        </div>

        <div class="mb-3">
            <label for="validade" class="form-label">Validade</label>
            <input type="date" name="validade" class="form-control" value="{{ $cupom->validade }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('cupons.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
