<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductRequestController;

Route::get('/', fn() => redirect()->route('requests.index'));

Route::prefix('solicitacoes')->group(function () {
    Route::get('/', [ProductRequestController::class, 'index'])->name('requests.index');
    Route::get('/nova', [ProductRequestController::class, 'create'])->name('requests.create');
    Route::post('/', [ProductRequestController::class, 'store'])->name('requests.store');

    Route::get('/{id}', [ProductRequestController::class, 'show'])->name('requests.show');
    Route::get('/{id}/editar', [ProductRequestController::class, 'edit'])->name('requests.edit');

    Route::put('/{id}/estoque', [ProductRequestController::class, 'updateEstoque'])->name('requests.update.estoque');
    Route::put('/{id}/fiscal', [ProductRequestController::class, 'updateFiscal'])->name('requests.update.fiscal');

    Route::post('/{id}/enviar/{proximo}', [ProductRequestController::class, 'enviar'])->name('requests.enviar');
    Route::post('/{id}/devolver', [ProductRequestController::class, 'devolver'])->name('requests.devolver');
    Route::post('/{id}/finalizar', [ProductRequestController::class, 'finalizar'])->name('requests.finalizar');

    Route::post('/{id}/anexos', [ProductRequestController::class, 'upload'])->name('requests.attach');
});
