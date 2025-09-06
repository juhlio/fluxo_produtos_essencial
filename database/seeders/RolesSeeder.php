<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin','solicitante','estoque','fiscal','contabil'] as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // deixa o primeiro usuário como admin (ajuste se quiser)
        if ($u = User::first()) $u->assignRole('admin');
    }
}
