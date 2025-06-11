@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Comprar: {{ $produto->nome }}</h2>

        <form method="POST" action="{{ route('carrinho.adicionar') }}">
            @csrf
            <input type="hidden" name="produto_id" value="{{ $produto->id }}">

            <div class="mb-3">
                <label for="variacao" class="form-label">Variação:</label>
                <select name="variacao_id" class="form-control" required>
                    @foreach ($produto->variacoes as $v)
                        <option value="{{ $v->id }}">{{ $v->nome }} — Estoque:
                            {{ $v->estoque->quantidade ?? 0 }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade:</label>
                <input type="number" name="quantidade" class="form-control" required min="1">
            </div>

            <button class="btn btn-primary">Adicionar ao Carrinho</button>
        </form>

    </div>
@endsection
