<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    $users = \App\Models\User::with('roles')->latest()->paginate(20);
    $roles = Role::orderBy('name')->get(); // <— AQUI

    return view('admin.users.index', compact('users', 'roles')); // <— AQUI
}

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $user  = new User();
        return view('admin.users.create', compact('user','roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6','confirmed'],
            'roles'    => ['array'],          // roles[]
            'roles.*'  => ['integer','exists:roles,id'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // vincula papéis na pivô
        $user->roles()->sync($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado!');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $user->load('roles');
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email,'.$user->id],
            'password' => ['nullable','string','min:6','confirmed'],
            'roles'    => ['array'],
            'roles.*'  => ['integer','exists:roles,id'],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        // atualiza a pivô (remove os que saíram e adiciona novos)
        $user->roles()->sync($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Usuário removido!');
    }
}
