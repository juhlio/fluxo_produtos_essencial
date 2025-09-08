<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductRequestController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Área Admin (autenticado, e-mail verificado e perfil admin)
Route::middleware(['auth', 'acl:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });;

// Raiz: redireciona para a lista de solicitações
Route::redirect('/', '/solicitacoes');

// Rota padrão do Breeze após login -> envia para solicitações
Route::redirect('/dashboard', '/solicitacoes')
    ->middleware(['auth'])
    ->name('dashboard');

// 🔒 Módulo de solicitações (tudo autenticado)
Route::middleware(['auth'])
    ->prefix('solicitacoes')
    ->name('requests.')
    ->group(function () {
        Route::get('/', [ProductRequestController::class, 'index'])->name('index');
        Route::get('/nova', [ProductRequestController::class, 'create'])->name('create');
        Route::post('/', [ProductRequestController::class, 'store'])->name('store');

        Route::get('/{id}', [ProductRequestController::class, 'show'])
            ->whereNumber('id')->name('show');
        Route::get('/{id}/editar', [ProductRequestController::class, 'edit'])
            ->whereNumber('id')->name('edit');

        // Atualizações por setor
        Route::put('/{id}/estoque', [ProductRequestController::class, 'updateEstoque'])
            ->whereNumber('id')->name('update.estoque');
        Route::put('/{id}/fiscal', [ProductRequestController::class, 'updateFiscal'])
            ->whereNumber('id')->name('update.fiscal');

        // Fluxo
        Route::post('/{id}/enviar/{proximo}', [ProductRequestController::class, 'enviar'])
            ->whereNumber('id')->name('enviar');
        Route::post('/{id}/devolver', [ProductRequestController::class, 'devolver'])
            ->whereNumber('id')->name('devolver');
        Route::post('/{id}/finalizar', [ProductRequestController::class, 'finalizar'])
            ->whereNumber('id')->name('finalizar');

        // Anexos
        Route::post('/{id}/anexos', [ProductRequestController::class, 'upload'])
            ->whereNumber('id')->name('attach');
    });

// Rotas de autenticação do Breeze
require __DIR__ . '/auth.php';
