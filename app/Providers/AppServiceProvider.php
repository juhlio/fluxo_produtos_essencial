<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // (Opcional) Log de SQL quando precisar
        // DB::listen(function ($q) {
        //     Log::info('SQL', ['sql' => $q->sql, 'bindings' => $q->bindings]);
        // });

        /**
         * Gate "admin" (usada no menu do AdminLTE e onde mais precisar).
         * Funciona tanto com Spatie\Permission (hasRole) quanto com coluna "role".
         */
        Gate::define('admin', function (User $u) {
            // Spatie\Permission
            if (method_exists($u, 'hasRole')) {
                try {
                    if ($u->hasRole('admin')) {
                        return true;
                    }
                } catch (\Throwable $e) {
                    // ignora
                }
            }

            // Fallback: coluna "role"
            return strtolower($u->role ?? '') === 'admin';
        });

        /**
         * Publica $canBasic / $canFiscal em TODAS as views.
         * Assim o Blade nunca dá "Undefined variable".
         */
        View::composer('*', function ($view) {
            $u = auth()->user();

            $hasAny = function (?User $user, array $roles): bool {
                if (!$user) return false;

                // Spatie: hasAnyRole()
                if (method_exists($user, 'hasAnyRole')) {
                    try {
                        return $user->hasAnyRole($roles);
                    } catch (\Throwable $e) {
                        // ignora e cai no fallback
                    }
                }

                // Fallback coluna "role"
                $r = strtolower($user->role ?? '');
                foreach ($roles as $want) {
                    if ($r === strtolower($want)) return true;
                }
                return false;
            };

            $canBasic  = $hasAny($u, ['admin', 'estoque']); // Estoque/Cadastrais
            $canFiscal = $hasAny($u, ['admin', 'fiscal']);  // Fiscal/Impostos

            $view->with(compact('canBasic', 'canFiscal'));
        });
    }
}
