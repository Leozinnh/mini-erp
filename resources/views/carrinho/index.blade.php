@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>🛒 Carrinho</h3>

        <form method="POST" action="{{ route('carrinho.finalizar') }}">
            @csrf
            <ul>
                @foreach ($carrinho as $item)
                    <li>{{ $item['nome'] }} — R$ {{ number_format($item['preco'], 2, ',', '.') }}</li>
                @endforeach
            </ul>

            <p>Subtotal: R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
            <p>Frete: R$ {{ number_format($frete, 2, ',', '.') }}</p>

            <div class="mb-3">
                <label for="cep" class="form-label">CEP para entrega:</label>
                <input type="text" name="cep" id="cep" class="form-control" required>
            </div>
            <div id="endereco" class="mt-3"></div>

            <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
        </form>
        <hr>
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary mb-3">Voltar</a>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#cep').mask('00000-000');

            $('#cep').on('blur', function() {
                let cep = $(this).val().replace(/\D/g, '');
                if (cep.length == 8) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(res => res.json())
                        .then(data => {
                            if (!data.erro) {
                                $('#endereco').html(`
                            <p><strong>Endereço:</strong> ${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}</p>
                        `);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'CEP não encontrado.'
                                });
                            }
                        });
                } else {
                    $('#endereco').html('');
                    Swal.fire({
                        icon: 'warning',
                        title: 'CEP inválido',
                        text: 'O CEP deve conter exatamente 8 dígitos.'
                    });
                }
            });
        });
    </script>
@endsection
