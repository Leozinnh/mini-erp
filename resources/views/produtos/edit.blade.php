@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
    <h2 class="mb-4">✏️ Editar Produto</h2>

    <form action="{{ route('produtos.update', $produto->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $produto->nome) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="tel" name="preco" id="preco" class="form-control"
                value="{{ old('preco', number_format($produto->preco, 2, ',', '.')) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Variações</label>
            <div id="variacoes">
                @foreach ($produto->variacoes as $index => $variacao)
                    <div class="row mb-2 p-2 border rounded variacao-item bg-light">
                        <input type="hidden" name="variacoes[{{ $index }}][id]" value="{{ $variacao->id }}">
                        <input type="hidden" name="variacoes[{{ $index }}][remover]" value="0"
                            class="remover-flag">

                        <div class="col-md-5">
                            <input type="text" name="variacoes[{{ $index }}][nome]" class="form-control"
                                placeholder="Nome" value="{{ old("variacoes.$index.nome", $variacao->nome) }}">
                        </div>
                        <div class="col-md-5">
                            <input type="number" name="variacoes[{{ $index }}][quantidade]" class="form-control"
                                placeholder="Estoque"
                                value="{{ old("variacoes.$index.quantidade", $variacao->estoque->quantidade ?? 0) }}">
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm mt-1"
                                onclick="removerVariacao(this)">REMOVER</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="adicionarVariacao()">+ Adicionar
                Variação</button>
        </div>

        <hr>
        <button type="submit" class="btn btn-success">💾 Atualizar</button>
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection

@section('scripts')
    <script>
        let count = {{ $produto->variacoes->count() }};

        function adicionarVariacao() {
            const html = `
        <div class="row mb-2 p-2 border rounded bg-light">
            <div class="col-md-5">
                <input type="text" name="variacoes[${count}][nome]" class="form-control" placeholder="Nome da variação">
            </div>
            <div class="col-md-5">
                <input type="number" name="variacoes[${count}][quantidade]" class="form-control" placeholder="Estoque">
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-outline-danger btn-sm mt-1" onclick="$(this).closest('.row').remove()">🗑</button>
            </div>
        </div>`;
            $('#variacoes').append(html);
            count++;
        }

        function removerVariacao(button) {
            const row = $(button).closest('.variacao-item');
            row.find('.remover-flag').val("1");
            row.hide();
        }

        // Formatação de valor no campo #preco sem usar .mask
        $('#preco').on('keyup', function() {
            let input = $(this).val().replace(/\D/g, '');
            if (input.length < 3) input = input.padStart(3, '0');
            let cents = input.slice(-2);
            let whole = input.slice(0, -2);
            whole = whole.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(`${whole},${cents}`);
        });
    </script>
@endsection
