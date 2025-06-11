@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Produtos</h2>
        <div>
            <a href="{{ route('produtos.create') }}" class="btn btn-primary">Novo Produto</a>
            <a href="{{ route('carrinho.index') }}" class="btn btn-secondary">🛒 Carrinho</a>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Variações</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produtos as $produto)
                <tr>
                    <td>{{ $produto->nome }}</td>
                    <td>R$ {{ $produto->preco }}</td>
                    <td>
                        <ul>
                            @foreach ($produto->variacoes as $v)
                                <li>{{ $v->nome }} — Estoque: {{ $v->estoque->quantidade ?? 0 }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir?')">Excluir</button>
                        </form>
                        <a href="{{ route('produtos.comprar', $produto) }}" class="btn btn-sm btn-success">Comprar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
