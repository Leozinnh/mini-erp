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
Route::post('/produtos/new', [ProdutoController::class, 'storeJson'])->name('produtos.storeJson');
Route::put('/produtos/{produto}', [ProdutoController::class, 'updateJson'])->name('produtos.updateJson');
Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroyJson'])->name('produtos.destroyJson');
