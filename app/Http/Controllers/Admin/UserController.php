<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Events\UserRolesChanged;


class UserController extends Controller
{
   public function index()
    {
        // traz usuários e papéis para a view (para filtros)
        $users = User::with('roles')->orderByDesc('id')->get();
        $roles = Role::orderBy('name')->pluck('name');
        return view('admin.users.index', compact('users','roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->pluck('name');
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $roleIds = Role::whereIn('name', $request->roles ?? [])->pluck('id')->all();
        if ($roleIds) {
            $rows = array_map(fn ($rid) => [
                'role_id'    => $rid,
                'model_type' => User::class,
                'model_id'   => $user->id,
            ], $roleIds);
            DB::table('model_has_roles')->insert($rows);
        }

        // log (criação com papéis)
        event(new UserRolesChanged(auth()->user(), $user, [], $request->roles ?? []));

        return redirect()->route('admin.users.index')->with('ok', 'Usuário criado.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->pluck('name');
        $userRoles = $user->roles->pluck('name')->all();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

     public function update(UpdateUserRequest $request, User $user)
    {
        $user->fill($request->only('name','email'));
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // papéis atuais (antes)
        $before = DB::table('roles as r')
            ->join('model_has_roles as mr', 'mr.role_id', '=', 'r.id')
            ->where('mr.model_type', User::class)
            ->where('mr.model_id', $user->id)
            ->pluck('r.name')->sort()->values()->all();

        $after = $request->roles ? array_values(array_unique($request->roles)) : [];

        // Bloqueia remover o próprio admin
        if (auth()->id() === $user->id) {
            $hadAdmin = in_array('admin', $before, true);
            $willHaveAdmin = in_array('admin', $after, true);
            if ($hadAdmin && !$willHaveAdmin) {
                return back()
                    ->with('error', 'Você não pode remover o próprio papel admin.')
                    ->withInput();
            }
        }

        // limpa vínculos e recria conforme $after
        DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->where('model_id', $user->id)
            ->delete();

        if ($after) {
            $roleIds = Role::whereIn('name', $after)->pluck('id')->all();
            if ($roleIds) {
                $rows = array_map(fn ($rid) => [
                    'role_id'    => $rid,
                    'model_type' => User::class,
                    'model_id'   => $user->id,
                ], $roleIds);
                DB::table('model_has_roles')->insert($rows);
            }
        }

        // log (mudança de papéis)
        event(new UserRolesChanged(auth()->user(), $user, $before, $after));

        return redirect()->route('admin.users.index')->with('ok', 'Usuário atualizado.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Você não pode excluir a si mesmo.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('ok', 'Usuário excluído.');
    }
}
