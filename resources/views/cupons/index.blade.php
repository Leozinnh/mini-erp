@extends('layouts.app')

@section('title', 'Cupons')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Cupons</h2>
        <a href="{{ route('cupons.create') }}" class="btn btn-primary">Novo Cupom</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Desconto (R$)</th>
                <th>Valor Mínimo</th>
                <th>Validade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cupons as $cupom)
                <tr>
                    <td>{{ $cupom->codigo }}</td>
                    <td>{{ $cupom->desconto }}</td>
                    <td>{{ $cupom->valor_minimo }}</td>
                    <td>{{ \Carbon\Carbon::parse($cupom->validade)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('cupons.edit', $cupom) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('cupons.destroy', $cupom) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir cupom?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
