<?php

use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('produtos.index');
});

Route::resource('cupons', \App\Http\Controllers\CupomController::class);
Route::resource('produtos', ProdutoController::class);

// Rota de back-end para uso de API
Route::prefix('api')->group(function () {
    Route::get('/produtos', [ProdutoController::class, 'listJson'])->name('api.produtos');
    Route::post('/produto/new', [ProdutoController::class, 'storeJson'])->name('api.produto.store');
    Route::put('/produto/{produto}', [ProdutoController::class, 'updateJson'])->name('api.produto.update');
    Route::delete('/produto/{produto}', [ProdutoController::class, 'destroyJson'])->name('api.produto.destroy');
});