<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersAndRolesSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Garante papéis na tabela roles
        $roleIds = [];
        foreach (['admin','estoque','fiscal'] as $name) {
            $id = DB::table('roles')->where('name', $name)->value('id');
            if (!$id) {
                $payload = ['name' => $name, 'created_at' => now(), 'updated_at' => now()];
                if (Schema::hasColumn('roles', 'guard_name')) {
                    $payload['guard_name'] = 'web';
                }
                $id = DB::table('roles')->insertGetId($payload);
            }
            $roleIds[$name] = $id;
        }

        // 2) Cria/atualiza usuários
        $seed = [
            ['name'=>'Admin',         'email'=>'teste@teste.com',   'pass'=>'12345678', 'roles'=>['admin']],
            ['name'=>'Teste Estoque', 'email'=>'estoque@teste.com', 'pass'=>'12345678', 'roles'=>['estoque']],
            ['name'=>'Teste Fiscal',  'email'=>'fiscal@teste.com',  'pass'=>'12345678', 'roles'=>['fiscal']],
        ];

        $pivotHasCreated = Schema::hasColumn('role_user', 'created_at');
        $pivotHasUpdated = Schema::hasColumn('role_user', 'updated_at');

        foreach ($seed as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'email_verified_at'=>now(), 'password'=>Hash::make($u['pass'])]
            );

            // limpa vínculos antigos para esse usuário
            DB::table('role_user')->where('user_id', $user->id)->delete();

            // (re)insere vínculos
            foreach ($u['roles'] as $r) {
                $payload = [
                    'user_id' => $user->id,
                    'role_id' => $roleIds[$r],
                ];
                if ($pivotHasCreated) $payload['created_at'] = now();
                if ($pivotHasUpdated) $payload['updated_at'] = now();

                DB::table('role_user')->insert($payload);
                // ou: DB::table('role_user')->insertOrIgnore($payload);
            }
        }
    }
}
