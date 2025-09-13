<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductRequestController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Área Admin (autenticado e perfil admin)
Route::middleware(['auth', 'acl:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class);
    });

// Raiz → lista de solicitações
Route::redirect('/', '/solicitacoes');

// Dashboard padrão → solicitações
Route::redirect('/dashboard', '/solicitacoes')
    ->middleware(['auth'])
    ->name('dashboard');

// 🔒 Módulo de solicitações (autenticado)
Route::middleware(['auth'])
    ->prefix('solicitacoes')
    ->name('requests.')
    ->group(function () {
        // Lista / criação
        Route::get('/', [ProductRequestController::class, 'index'])->name('index');
        Route::get('/nova', [ProductRequestController::class, 'create'])->name('create');
        Route::post('/', [ProductRequestController::class, 'store'])->name('store');

        // Exibição / edição (aliases em PT-BR)
        Route::get('/{pr}', [ProductRequestController::class, 'show'])
            ->whereNumber('pr')->name('show');
        Route::get('/{pr}/editar', [ProductRequestController::class, 'edit'])
            ->whereNumber('pr')->name('edit');

        // Update/Destroy REST (geral)
        Route::put('/{pr}', [ProductRequestController::class, 'update'])
            ->whereNumber('pr')->name('update');
        Route::delete('/{pr}', [ProductRequestController::class, 'destroy'])
            ->whereNumber('pr')->name('destroy');

        // Atualizações por setor (separadas)
        Route::put('/{pr}/estoque', [ProductRequestController::class, 'updateEstoque'])
            ->whereNumber('pr')->name('update.estoque');
        Route::put('/{pr}/fiscal', [ProductRequestController::class, 'updateFiscal'])
            ->whereNumber('pr')->name('update.fiscal');

        // Fluxo
        Route::post('/{pr}/enviar/{proximo}', [ProductRequestController::class, 'enviar'])
            ->whereNumber('pr')->name('enviar');
        Route::post('/{pr}/devolver', [ProductRequestController::class, 'devolver'])
            ->whereNumber('pr')->name('devolver');
        Route::post('/{pr}/finalizar', [ProductRequestController::class, 'finalizar'])
            ->whereNumber('pr')->name('finalizar');

        // Anexos
        Route::post('/{pr}/anexos', [ProductRequestController::class, 'upload'])
            ->whereNumber('pr')->name('attach');
    });

require __DIR__ . '/auth.php';
