<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductRequestController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::middleware(['auth', 'verified', 'role:admin']) // <- exige e-mail verificado
    ->prefix('admin')->name('admin.')
    ->group(fn() => Route::resource('users', \App\Http\Controllers\Admin\UserController::class));


// Raiz do site: manda para /solicitacoes (se não estiver logado, Breeze redireciona para /login)
Route::redirect('/', '/solicitacoes');

// Rota que o Breeze usa após login (nome: dashboard)
// Em vez de uma view, apenas redireciona para a lista de solicitações.
Route::redirect('/dashboard', '/solicitacoes')
    ->middleware(['auth'])   // deixe só 'auth' para não travar em verificação de e-mail
    ->name('dashboard');

// 🔒 Todas as rotas do seu módulo protegidas por autenticação
Route::middleware(['auth'])->prefix('solicitacoes')->group(function () {
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

// Rotas de autenticação geradas pelo Breeze
require __DIR__ . '/auth.php';
