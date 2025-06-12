@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Novo Cupom</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cupons.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Código:</label>
                <input type="text" name="codigo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Tipo de Desconto:</label>
                <select name="tipo" id="tipo_desconto" class="form-control" required>
                    <option value="valor">R$ (Valor fixo)</option>
                    <option value="porcentagem">% (Porcentagem)</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Desconto:</label>
                <input type="tel" name="desconto" id="campo_desconto" class="form-control" required>
            </div>

            <div class="mb-3" id="valor_minimo_box">
                <label>Valor mínimo (R$):</label>
                <input type="tel" name="valor_minimo" class="form-control" step="0.01" value="0">
            </div>

            <div class="mb-3">
                <label>Validade:</label>
                <input type="date" name="validade" class="form-control" required>
            </div>

            <button class="btn btn-success">Criar</button>
            <a href="{{ route('cupons.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {
            function toggleValorMinimo() {
                if ($('#tipo_desconto').val() === 'valor') {
                    $('#valor_minimo_box').show();
                } else {
                    $('#valor_minimo_box').hide();
                }
            }

            function aplicarValidacaoDesconto() {
                const tipo = $('#tipo_desconto').val();
                const $desconto = $('#campo_desconto');

                $desconto.off('input').on('input', function() {
                    let valor = $(this).val();

                    if (tipo === 'porcentagem') {
                        valor = valor.replace(/\D/g, ''); // Apenas dígitos
                        let numero = parseInt(valor, 10);
                        if (numero > 99) numero = 99;
                        $(this).val(numero || '');
                    } else {
                        // Permite número com até 2 casas decimais
                        valor = valor.replace(/[^0-9.,]/g, '').replace(',', '.');
                        const match = valor.match(/^(\d+)(\.\d{0,2})?/);
                        valor = match ? match[0] : '';
                        $(this).val(valor);
                    }
                });
            }


            $('#tipo_desconto').on('change', function() {
                toggleValorMinimo();
                aplicarValidacaoDesconto();
                $('#campo_desconto').val('');
            });

            toggleValorMinimo();
            aplicarValidacaoDesconto();
        });
    </script>
@endsection
