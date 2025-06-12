@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4">🛒 Seu Carrinho</h3>

        @if (count($carrinho) === 0)
            <div class="alert alert-info">Seu carrinho está vazio.</div>
            <a href="{{ route('produtos.index') }}" class="btn btn-primary">Voltar às compras</a>
        @else
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th class="text-center" style="width:100px;">Quantidade</th>
                                <th class="text-end">Preço Unitário</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($carrinho as $item)
                                <tr>
                                    <td>{{ $item['nome'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary fs-6">{{ $item['quantidade'] }}</span>
                                    </td>
                                    <td class="text-end">R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
                                    <td class="text-end">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <form method="POST" action="{{ route('carrinho.limpar') }}"
                        onsubmit="return confirm('Tem certeza que quer limpar o carrinho?');">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 mb-3">Limpar Carrinho</button>
                    </form>
                </div>


                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Resumo do Pedido</h5>

                            <p><strong>Total:</strong> <span id="total">R$
                                    {{ number_format($subtotal, 2, ',', '.') }}</span></p>
                            {{-- <p><strong>Frete:</strong> R$ {{ number_format($frete, 2, ',', '.') }}</p> --}}
                            <p><strong>Frete:</strong> <span id="frete"></span></p>

                            @php
                                $totalComDesconto = $subtotal + $frete;
                                if (isset($desconto) && $desconto > 0) {
                                    $totalComDesconto -= $desconto;
                                }
                            @endphp

                            @if (isset($desconto) && $desconto > 0)
                                <p><strong>Desconto:</strong> -R$ {{ number_format($desconto, 2, ',', '.') }}</p>
                            @endif

                            <hr>
                            <h5 class="mb-3">Total: R$ {{ number_format($totalComDesconto, 2, ',', '.') }}</h5>

                            <form method="POST" action="{{ route('carrinho.finalizar') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="cep" class="form-label">CEP para entrega</label>
                                    <input type="text" name="cep" id="cep"
                                        class="form-control @error('cep') is-invalid @enderror" required>
                                    @error('cep')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="endereco" class="mb-3"></div>

                                <div class="mb-3">
                                    <label for="cupom" class="form-label">Cupom de Desconto</label>
                                    <div class="input-group">
                                        <input type="text" name="cupom" id="cupom"
                                            class="form-control @error('cupom') is-invalid @enderror"
                                            placeholder="Digite seu cupom aqui" value="{{ old('cupom') }}">
                                        <button type="button" id="validar-cupom"
                                            class="btn btn-outline-primary">Validar</button>
                                    </div>
                                    @error('cupom')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div id="feedback-cupom" class="form-text mt-1"></div>
                                </div>

                                <button type="submit" class="btn btn-success w-100">Finalizar Pedido</button>
                            </form>
                        </div>
                    </div>
                    <a href="{{ route('produtos.index') }}" class="btn btn-link mt-3 w-100">← Continuar comprando</a>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            let subtotal = parseFloat("{{ $subtotal }}");
            let frete = parseFloat("{{ $frete }}");
            let desconto = 0;
            $('#cep').mask('00000-000');

            function calcularFrete(subtotal) {
                if (subtotal >= 52 && subtotal <= 166.59) {
                    return 15;
                } else if (subtotal > 200) {
                    return 0;
                }
                return 20;
            }

            $('#cep').on('blur', function() {
                let cep = $(this).val().replace(/\D/g, '');
                if (cep.length === 8) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(res => res.json())
                        .then(data => {
                            if (!data.erro) {
                                $('#endereco').html(`
                            <div class="alert alert-success py-2">
                                <strong>Endereço:</strong> ${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}
                            </div>
                        `);

                                frete = calcularFrete(subtotal);
                                let totalComDesconto = subtotal + frete - desconto;
                                if (totalComDesconto < 0) totalComDesconto = 0;

                                $('#frete').text('R$ ' + frete.toFixed(2).replace('.', ','));
                                $('h5.mb-3').text('Total: R$ ' + totalComDesconto.toFixed(2).replace(
                                    '.', ','));
                            } else {
                                $('#endereco').html('');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'CEP não encontrado',
                                    text: 'Por favor, verifique o CEP e tente novamente.'
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

            function atualizarResumo() {
                let totalComDesconto = subtotal + frete - desconto;
                if (totalComDesconto < 0) totalComDesconto = 0;

                $('p strong:contains("Desconto:")').parent().remove();
                if (desconto > 0) {
                    $("#total").css("text-decoration", "line-through");
                }

                $('h5.mb-3').text('Total: R$ ' + totalComDesconto.toFixed(2).replace('.', ',')).css('font-weight',
                    '700');
            }
            $('#validar-cupom').click(function() {
                let cupom = $('#cupom').val().trim();
                if (cupom.length === 0) {
                    $('#feedback-cupom').text('Digite um cupom para validar.').removeClass('text-success')
                        .addClass('text-danger');
                    return;
                }

                $.ajax({
                    url: "{{ route('cupom.validar') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cupom: cupom
                    },
                    success: function(response) {
                        if (response.valido) {
                            // Calcula desconto conforme tipo (porcentagem ou fixo)
                            if (response.tipo === 'porcentagem') {
                                desconto = subtotal * (response.desconto / 100);
                            } else {
                                desconto = response.desconto;
                            }
                            $('#feedback-cupom').text(
                                `Cupom válido! Desconto: R$ ${desconto.toFixed(2).replace('.', ',')}`
                            ).removeClass('text-danger').addClass('text-success');

                            atualizarResumo();

                            // Guardar o cupom na sessão para envio no form (hidden input)
                            if ($('#cupom-input').length === 0) {
                                $('<input>').attr({
                                    type: 'hidden',
                                    id: 'cupom-input',
                                    name: 'cupom',
                                    value: cupom
                                }).appendTo('form');
                            } else {
                                $('#cupom-input').val(cupom);
                            }
                        } else {
                            desconto = 0;
                            $('#feedback-cupom').text('Cupom inválido ou expirado.')
                                .removeClass('text-success').addClass('text-danger');
                            atualizarResumo();
                        }
                    },
                    error: function() {
                        desconto = 0;
                        $('#feedback-cupom').text(
                                'Erro ao validar cupom. Tente novamente mais tarde.')
                            .removeClass('text-success').addClass('text-danger');
                        atualizarResumo();
                    }
                });
            });
        });
    </script>
@endsection
