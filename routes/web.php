<?php

use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('produtos.index');
});

Route::resource('cupons', \App\Http\Controllers\CupomController::class);
Route::resource('produtos', ProdutoController::class);

Route::get('/produtos/{produto}/comprar', [ProdutoController::class, 'comprar'])->name('produtos.comprar');
Route::post('/finalizar-pedido', [PedidoController::class, 'finalizar'])->name('pedido.finalizar');
Route::post('/carrinho/adicionar', [PedidoController::class, 'adicionarAoCarrinho'])->name('carrinho.adicionar');

Route::get('/comprar/{produto}', [CarrinhoController::class, 'adicionar'])->name('produtos.finalizar');
Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
Route::post('/carrinho/finalizar', [CarrinhoController::class, 'finalizar'])->name('carrinho.finalizar');
Route::post('/carrinho/limpar', [CarrinhoController::class, 'limpar'])->name('carrinho.limpar');

Route::post('/cupom/validar', [CupomController::class, 'validarCupom'])->name('cupom.validar');

// Rota de back-end para uso de API
Route::prefix('api')->group(function () {
    Route::get('/produtos', [ProdutoController::class, 'listJson'])->name('api.produtos');
    Route::post('/produto/new', [ProdutoController::class, 'storeJson'])->name('api.produto.store');
    Route::put('/produto/{produto}', [ProdutoController::class, 'updateJson'])->name('api.produto.update');
    Route::delete('/produto/{produto}', [ProdutoController::class, 'destroyJson'])->name('api.produto.destroy');
});
