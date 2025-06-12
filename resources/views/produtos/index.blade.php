@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-4">🛍️ Lista de Produtos</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('produtos.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-plus-circle"></i> Novo Produto
            </a>
            <a href="{{ route('cupom.index') }}" class="btn btn-outline-info">
                <i class="bi bi-plus-circle"></i> Cupons
            </a>
            <a href="{{ route('carrinho.index') }}" class="btn btn-outline-secondary">
                🛒 Carrinho
            </a>
        </div>
    </div>


    <div class="row">
        @foreach ($produtos as $produto)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $produto->nome }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">R$ {{ $produto->preco }}</h6>

                        <ul class="list-group list-group-flush mb-3">
                            @foreach ($produto->variacoes as $v)
                                <li class="list-group-item">
                                    {{ $v->nome }} — Estoque: {{ $v->estoque->quantidade ?? 0 }}
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-auto d-flex justify-content-between gap-1">
                            <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('produtos.destroy', $produto) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-excluir">Excluir</button>
                            </form>
                            <a href="{{ route('produtos.comprar', $produto) }}" class="btn btn-sm btn-success">Comprar</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-excluir', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Essa ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
