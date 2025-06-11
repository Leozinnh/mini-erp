@extends('layouts.app')

@section('title', 'Criar Produto')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="mb-4"><i class="bi bi-box-seam"></i> Novo Produto</h3>

                <form action="{{ route('produtos.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome do Produto</label>
                        <input type="text" name="nome" class="form-control" placeholder="Digite o nome do produto"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Preço</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="tel" step="0.01" id="preco" name="preco" class="form-control"
                                placeholder="0,00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Variações</label>
                        <div id="variacoes">
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <input type="text" name="variacoes[0][nome]" class="form-control"
                                        placeholder="Nome da variação" required>
                                </div>
                                <div class="col">
                                    <input type="number" name="variacoes[0][quantidade]" class="form-control"
                                        placeholder="Estoque" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="adicionarVariacao()">
                            <i class="bi bi-plus-circle"></i> Adicionar Variação
                        </button>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Criar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('#preco').on('keyup', function() {
                let input = $(this).val();

                // Remove tudo que não for dígito
                input = input.replace(/\D/g, '');

                // Adiciona os centavos
                let cents = input.slice(-2);
                let whole = input.slice(0, -2);

                // Adiciona pontos nos milhares
                whole = whole.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                let formatted = whole.length ? `${whole},${cents.padStart(2, '0')}` :
                    `${cents.padStart(1, '0')}`;

                $(this).val(formatted);
            });
        });

        let count = 1;

        function adicionarVariacao() {
            const div = document.createElement('div');
            const id = `variacao-${count}`;
            div.id = id;
            div.classList.add('row', 'g-2', 'mb-2');
            div.innerHTML = `
            <div class="col">
                <input type="text" name="variacoes[${count}][nome]" class="form-control" placeholder="Nome da variação" required>
            </div>
            <div class="col">
                <input type="number" name="variacoes[${count}][quantidade]" class="form-control" placeholder="Estoque" required>
            </div>
            <div class="col-auto d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removerVariacao('${id}')">
                    REMOVER
                </button>
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
