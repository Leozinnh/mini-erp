@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">🛒 Comprar Produto</h5>
                        <span class="fw-medium">{{ $produto->nome }}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('carrinho.adicionar') }}">
                            @csrf
                            <input type="hidden" name="produto_id" value="{{ $produto->id }}">

                            <div class="mb-3">
                                <label for="variacao" class="form-label">Variação</label>
                                <select name="variacao_id" class="form-select" required>
                                    <option selected disabled>Escolha uma variação</option>
                                    @foreach ($produto->variacoes as $v)
                                        <option value="{{ $v->id }}">
                                            {{ $v->nome }} — Estoque: {{ $v->estoque->quantidade ?? 0 }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="number" name="quantidade" class="form-control" min="1" required
                                    placeholder="Ex: 1">
                                @error('message')
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                                </button>
                                <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            const variacoes = @json($produto->variacoes->mapWithKeys(fn($v) => [$v->id => $v->estoque->quantidade ?? 0]));

            document.querySelector('form').addEventListener('submit', function(e) {
                const variacaoId = document.querySelector('[name="variacao_id"]').value;
                const quantidade = parseInt(document.querySelector('[name="quantidade"]').value);
                const estoque = variacoes[variacaoId];

                if (quantidade > estoque) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Estoque insuficiente',
                        text: `Só temos ${estoque} unidades em estoque.`,
                    });
                }
            });
        });
    </script>
@endsection
