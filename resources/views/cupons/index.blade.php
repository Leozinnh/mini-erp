@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cupons</h2>

    <a href="{{ route('cupons.create') }}" class="btn btn-success mb-3">Novo Cupom</a>
    <a href="{{ route('produtos.index') }}" class="btn btn-secondary mb-3">Voltar</a>

    <table class="table table-bordered" id="cupons-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Desconto</th>
                <th>Valor Mínimo</th>
                <th>Validade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cupons as $cupom)
            <tr data-id="{{ $cupom->id }}">
                <td>{{ $cupom->codigo }}</td>
                <td>
                    @if ($cupom->tipo === 'valor')
                        R$ {{ number_format($cupom->desconto, 2, ',', '.') }}
                    @else
                        {{ number_format($cupom->desconto, 0, ',', '.') }}%
                    @endif
                </td>
                <td>
                    @if ($cupom->tipo === 'valor')
                        R$ {{ number_format($cupom->valor_minimo, 2, ',', '.') }}
                    @else
                        —
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($cupom->validade)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('cupons.edit', $cupom) }}" class="btn btn-primary btn-sm">Editar</a>

                    <button class="btn btn-danger btn-sm btn-delete-cupom" data-id="{{ $cupom->id }}">
                        Excluir
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<!-- Importa SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const token = '{{ csrf_token() }}';

        document.querySelectorAll('.btn-delete-cupom').forEach(button => {
            button.addEventListener('click', function () {
                const cupomId = this.getAttribute('data-id');
                const row = this.closest('tr');

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Essa ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/cupons/${cupomId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                // Remove a linha da tabela
                                row.remove();

                                Swal.fire(
                                    'Excluído!',
                                    'O cupom foi excluído.',
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Erro!',
                                    'Não foi possível excluir o cupom.',
                                    'error'
                                );
                            }
                        })
                        .catch(() => {
                            Swal.fire(
                                'Erro!',
                                'Não foi possível excluir o cupom.',
                                'error'
                            );
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
