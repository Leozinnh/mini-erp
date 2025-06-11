@extends('layouts.app')

@section('title', 'Novo Cupom')

@section('content')
    <h2>Novo Cupom</h2>

    <form action="{{ route('cupons.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="desconto" class="form-label">Desconto (R$)</label>
            <input type="number" step="0.01" name="desconto" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="valor_minimo" class="form-label">Valor Mínimo</label>
            <input type="number" step="0.01" name="valor_minimo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="validade" class="form-label">Validade</label>
            <input type="date" name="validade" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('cupons.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
@endsection
