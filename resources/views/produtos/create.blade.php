@extends('layouts.app')

@section('title', 'Criar Produto')

@section('content')
    <h2>Novo Produto</h2>

    <form action="{{ route('produtos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="number" step="0.01" name="preco" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Variações</label>
            <div id="variacoes">
                <div class="row mb-2">
                    <div class="col">
                        <input type="text" name="variacoes[0][nome]" class="form-control" placeholder="Nome da variação">
                    </div>
                    <div class="col">
                        <input type="number" name="variacoes[0][quantidade]" class="form-control" placeholder="Estoque">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="adicionarVariacao()">+ Variação</button>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <script>
        let count = 1;
        function adicionarVariacao() {
            const div = document.createElement('div');
            var id = `variacao-${count}`;
            div.id = id;
            div.classList.add('row', 'mb-2');
            div.innerHTML = `
                <div class="col">
                    <input type="text" name="variacoes[${count}][nome]" class="form-control" placeholder="Nome da variação">
                </div>
                <div class="col">
                    <input type="number" name="variacoes[${count}][quantidade]" class="form-control" placeholder="Estoque">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerVariacao('${id}')">Remover</button>
                </div>
            `;
            document.getElementById('variacoes').appendChild(div);
            count++;
        }

        function removerVariacao(id) {
            const variacao = document.getElementById(id);
            if (variacao) {
                variacao.remove();
            }
        }
    </script>
@endsection
