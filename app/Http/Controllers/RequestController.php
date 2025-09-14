<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\ProductRequest; // ajuste conforme seu modelo

class RequestController extends Controller
{
    // ...

    public function update(Request $request, $id)
    {
        // $requestItem = ProductRequest::findOrFail($id); // ajuste ao seu modelo

        // Valide como já faz hoje. Exemplo:
        $data = $request->all(); // ou $request->validate([...])

        $u = $request->user();

        $hasAny = function ($user, array $roles): bool {
            if (!$user) return false;
            if (method_exists($user, 'hasAnyRole')) {
                try { return $user->hasAnyRole($roles); } catch (\Throwable $e) {}
            }
            $role = strtolower($user->role ?? '');
            foreach ($roles as $r) if ($role === strtolower($r)) return true;
            return false;
        };

        $canBasic  = $hasAny($u, ['admin','estoque']);
        $canFiscal = $hasAny($u, ['admin','fiscal']);

        // Blindagem: remove blocos que o usuário não pode alterar.
        if (!$canBasic)  unset($data['basic']);
        if (!$canFiscal) unset($data['fiscal']);

        // >>>> A partir daqui, prossiga seu fluxo normal de update <<<<
        // $requestItem->fillCom($data);  // ou como você persiste
        // $requestItem->save();

        // return redirect()->route('requests.show', $requestItem->id)
        //                  ->with('success', 'Solicitação atualizada!');
    }
}
