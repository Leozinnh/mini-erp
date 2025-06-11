@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
    <h2>Editar Produto</h2>

    <form action="{{ route('produtos.update', $produto->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $produto->nome) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="number" step="0.01" name="preco" class="form-control"
                value="{{ old('preco', $produto->preco) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Variações</label>
            <div id="variacoes">
                @foreach ($produto->variacoes as $index => $variacao)
                    <div class="row mb-2 variacao-item">
                        <input type="hidden" name="variacoes[{{ $index }}][id]" value="{{ $variacao->id }}">
                        <input type="hidden" name="variacoes[{{ $index }}][remover]" value="0"
                            class="remover-flag">
                        <div class="col">
                            <input type="text" name="variacoes[{{ $index }}][nome]" class="form-control"
                                placeholder="Nome da variação" value="{{ old("variacoes.$index.nome", $variacao->nome) }}">
                        </div>
                        <div class="col">
                            <input type="number" name="variacoes[{{ $index }}][quantidade]" class="form-control"
                                placeholder="Estoque"
                                value="{{ old("variacoes.$index.quantidade", $variacao->estoque->quantidade ?? 0) }}">
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="removerVariacao(this)">Remover</button>
                        </div>
                    </div>
                @endforeach

            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="adicionarVariacao()">+ Variação</button>
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <script>
        let count = {{ $produto->variacoes->count() }};

        function adicionarVariacao() {
            const div = document.createElement('div');
            div.classList.add('row', 'mb-2');
            div.innerHTML = `
                <div class="col">
                    <input type="text" name="variacoes[${count}][nome]" class="form-control" placeholder="Nome da variação">
                </div>
                <div class="col">
                    <input type="number" name="variacoes[${count}][quantidade]" class="form-control" placeholder="Estoque">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerVariacao(this)">Remover</button>
                </div>
            `;
            document.getElementById('variacoes').appendChild(div);
            count++;
        }

        function removerVariacao(botao) {
            const row = botao.closest('.variacao-item');
            // Marca o campo hidden 'remover' como 1
            row.querySelector('.remover-flag').value = "1";
            // Esconde visualmente a linha
            row.style.display = 'none';
        }
    </script>
@endsection
