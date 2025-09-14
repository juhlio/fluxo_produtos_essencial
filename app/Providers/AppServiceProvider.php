<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
        DB::listen(function ($q) {
            Log::info('SQL', ['sql' => $q->sql, 'bindings' => $q->bindings]);
        });

        Gate::define('admin', function (User $u) {
            // 1) Coluna 'role' em users
            if (isset($u->role) && $u->role === 'admin') {
                return true;
            }

            // 2) Relação via Spatie (se o trait estiver no model)
            if (method_exists($u, 'hasRole')) {
                try {
                    if ($u->hasRole('admin')) return true;
                } catch (\Throwable $e) { /* segue o jogo */
                }
            }

            // 3) Consulta direta nas tabelas do Spatie (sem trait)
            try {
                $exists = DB::table('model_has_roles as mr')
                    ->join('roles as r', 'r.id', '=', 'mr.role_id')
                    ->where('mr.model_type', User::class)
                    ->where('mr.model_id', $u->id)
                    ->where('r.name', 'admin')
                    ->exists();

                if ($exists) return true;
            } catch (\Throwable $e) { /* tabela pode não existir */
            }

            return false;
        });

        /*  Gate::define('admin', function (User $u) {
            return DB::table('model_has_roles as mr')
                ->join('roles as r', 'r.id', '=', 'mr.role_id')
                ->where('mr.model_type', User::class)
                ->where('mr.model_id', $u->id)
                ->where('r.name', 'admin')
                ->exists();
        }); */
    }
}
