@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Editar Cupom: {{ $cupom->codigo }}</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cupons.update', $cupom) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Código:</label>
                <input type="text" name="codigo" class="form-control" value="{{ $cupom->codigo }}" required>
            </div>

            <div class="mb-3">
                <label>Tipo de Desconto:</label>
                <select name="tipo" id="tipo_desconto" class="form-control" required>
                    <option value="valor" {{ $cupom->tipo === 'valor' ? 'selected' : '' }}>R$ (Valor fixo)</option>
                    <option value="porcentagem" {{ $cupom->tipo === 'porcentagem' ? 'selected' : '' }}>% (Porcentagem)
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label>Desconto:</label>
                <input type="tel" name="desconto" id="desconto" class="form-control"
                    value="{{ $cupom->tipo === 'porcentagem' ? intval($cupom->desconto) : number_format($cupom->desconto, 2, '.', '') }}"
                    required>
            </div>

            <div class="mb-3" id="valor_minimo_box">
                <label>Valor mínimo (R$):</label>
                <input type="tel" name="valor_minimo" class="form-control" step="0.01"
                    value="{{ number_format($cupom->valor_minimo, 2, '.', '') }}">
            </div>

            <div class="mb-3">
                <label>Validade:</label>
                <input type="date" name="validade" class="form-control"
                    value="{{ \Carbon\Carbon::parse($cupom->validade)->format('Y-m-d') }}" required>
            </div>

            <button class="btn btn-primary">Atualizar</button>
            <a href="{{ route('cupons.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            function toggleValorMinimo() {
                if ($('#tipo_desconto').val() === 'valor') {
                    $('#valor_minimo_box').show();
                    $('input[name="valor_minimo"]').prop('disabled', false);
                } else {
                    $('#valor_minimo_box').hide();
                    $('input[name="valor_minimo"]').prop('disabled', true);
                }
            }

            function validarDesconto() {
                var tipo = $('#tipo_desconto').val();
                var desconto = $('#desconto').val();

                if (tipo === 'porcentagem') {
                    desconto = desconto.replace(/[^0-9]/g, '');
                    if (desconto > 99) desconto = '99';
                    $('#desconto').val(desconto);
                } else {
                    // permite números com decimais
                    var valor = $('#desconto').val();
                    var regex = /^\d+(\.\d{0,2})?$/;
                    if (!regex.test(valor)) {
                        // Corrigir para formato válido
                        valor = valor.replace(/[^0-9\.]/g, '');
                        $('#desconto').val(valor);
                    }
                }
            }

            $('#tipo_desconto').change(function() {
                toggleValorMinimo();
                validarDesconto();
            });

            $('#desconto').on('input', function() {
                validarDesconto();
            });

            // Inicializa ao carregar
            toggleValorMinimo();
            validarDesconto();
        });
    </script>
@endsection
