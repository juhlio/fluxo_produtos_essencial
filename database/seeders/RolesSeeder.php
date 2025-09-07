<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin','solicitante','estoque','fiscal','contabil'];

        DB::table('roles')->upsert(
            collect($roles)->map(fn($r)=>['name'=>$r,'guard_name'=>'web'])->all(),
            ['name','guard_name']
        );

        // dá papel admin ao primeiro usuário
        $userId = DB::table('users')->min('id');
        $roleId = DB::table('roles')->where('name','admin')->where('guard_name','web')->value('id');

        if ($userId && $roleId) {
            DB::table('model_has_roles')->updateOrInsert([
                'role_id' => $roleId,
                'model_type' => User::class,
                'model_id' => $userId,
            ], []);
        }
    }
}
