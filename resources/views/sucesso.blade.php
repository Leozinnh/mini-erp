@extends('layouts.app')
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm border-success">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="green"
                                class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.08.022l3.992-4.99a.75.75 0 1 0-1.14-.976L7.477 9.417 5.384 7.323a.75.75 0 1 0-1.06 1.06l2.646 2.647z" />
                            </svg>
                        </div>
                        <h1 class="mb-3 text-success">Parabéns pela sua compra!</h1>
                        <p class="lead mb-4">Seu pedido foi recebido com sucesso e está sendo processado.</p>

                        <p class="mb-2">Número do pedido: <strong>{{ $pedido->id }}</strong></p>
                        <p class="mb-2">Valor total: <strong>R$
                                {{ number_format($pedido->subtotal, 2, ',', '.') }}</strong>
                        </p>
                        <p class="mb-4">Acompanhe o status do seu pedido pelo e-mail cadastrado.</p>

                        <a href="{{ route('produtos.index') }}" class="btn btn-success btn-lg">Continuar Comprando</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
