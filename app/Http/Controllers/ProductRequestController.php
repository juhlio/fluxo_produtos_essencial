<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBasicsRequest;
use App\Http\Requests\UpdateEstoqueRequest;
use App\Http\Requests\UpdateFiscalRequest;
use App\Models\ProductRequest;
use App\Models\PreProduct;
use App\Models\User; // fallback de checagem de papel, caso não use Spatie
use App\Services\RequestWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class ProductRequestController extends Controller
{
    /** Lista com paginação e eager loading */
    public function index()
    {
        $items = ProductRequest::with('preProduct')->latest()->paginate(50);
        return view('requests.index', compact('items'));
    }

    public function create()
    {
        return view('requests.create'); // form abas Cadastrais (solicitante)
    }

    /** Cria ProductRequest + PreProduct (básicos do solicitante) */
    public function store(StoreBasicsRequest $req, RequestWorkflowService $workflow)
    {
        $pr = ProductRequest::create([
            'requested_by_id' => Auth::id(),   // precisa estar logado
            'status'          => 'RASCUNHO',
            'current_sector'  => 'SOLICITANTE',
        ]);

        $validated = $req->validated();
        $extrasFields = [
            'codigo','tipo','armazem_padrao','grupo','grupo_trib','cont_seg_soc','imposto_renda','calcula_inss',
            'red_inss','red_irrf','red_pis','red_cofins','perc_pis','perc_cofins','perc_csll','proprio_icms',
            'icms_pauta','ipi_pauta','aliq_famad','aliq_fecp','solid_saida','solid_entrada','imp_zfranca',
        ];
        $extras = Arr::only($validated, $extrasFields);
        $data   = Arr::except($validated, $extrasFields);

        PreProduct::create(array_merge(
            ['product_request_id' => $pr->id, 'extras' => $extras],
            $data
        ));

        // log de criação
        $workflow->log($pr, 'CRIAR', null, 'RASCUNHO', 'Solicitação criada');

        return redirect()->route('requests.show', $pr->id);
    }

    public function show($id)
    {
        $pr = ProductRequest::with('preProduct')->findOrFail($id);
        return view('requests.show', compact('pr'));
    }

    public function edit($id)
    {
        $req  = \App\Models\ProductRequest::with('preProduct')->findOrFail($id);
        $user = auth()->user();

        $canEstoque = $user->hasAnyRole(['admin', 'estoque']);
        $canFiscal  = $user->hasAnyRole(['admin', 'fiscal', 'contabil']);

        if (!$req->preProduct) {
            $req->setRelation('preProduct', \App\Models\PreProduct::create([
                'product_request_id' => $req->id,
                'descricao'          => $req->descricao ?? '',
            ]));
        }

        return view('requests.edit', compact('req', 'canEstoque', 'canFiscal'));
    }

    /** Atualiza somente campos de estoque */
    public function updateEstoque(UpdateEstoqueRequest $request, $id, RequestWorkflowService $workflow)
    {
        $pr = ProductRequest::with('preProduct')->findOrFail($id);

        // Autorização por papel (se tiver Policy, troque por $this->authorize(...))
        abort_unless($this->userHasAnyRole($request->user(), ['admin', 'estoque']), 403, 'Sem permissão para editar Estoque.');

        $data = $request->validated();

        // garante que exista o pre_products
        PreProduct::updateOrCreate(
            ['product_request_id' => $pr->id],
            $data
        );

        // log opcional
        $workflow->log($pr, 'EDITAR_ESTOQUE', $pr->status, $pr->status, 'Dados de Estoque atualizados');

        return back()->with('ok', 'Dados de Estoque atualizados.');
    }

    /** Atualiza somente campos fiscais/contábeis */
    public function updateFiscal(UpdateFiscalRequest $request, $id, RequestWorkflowService $workflow)
    {
        $pr = ProductRequest::with('preProduct')->findOrFail($id);

        abort_unless($this->userHasAnyRole($request->user(), ['admin', 'fiscal', 'contabil']), 403, 'Sem permissão para editar Fiscal/Contábil.');

        $data = $request->validated();

        // normaliza checkboxes ausentes (evita "ficar preso" em true)
        $data['tem_st']       = $request->boolean('tem_st');
        $data['retencao_iss'] = $request->boolean('retencao_iss');

        PreProduct::updateOrCreate(
            ['product_request_id' => $pr->id],
            $data
        );

        $workflow->log($pr, 'EDITAR_FISCAL', $pr->status, $pr->status, 'Dados Fiscais/Contábeis atualizados');

        return back()->with('ok', 'Dados Fiscais/Contábeis atualizados.');
    }

    /** Envia para próximo setor no workflow */
    public function enviar(Request $request, $id, $proximo, RequestWorkflowService $workflow)
    {
        $pr = ProductRequest::with('preProduct')->findOrFail($id);
        $workflow->enviar($pr, strtoupper($proximo), $request->input('mensagem'));
        return redirect()->route('requests.show', $pr->id);
    }

    /** Devolve para setor anterior */
    public function devolver(Request $request, $id, RequestWorkflowService $workflow)
    {
        $pr = ProductRequest::findOrFail($id);
        $workflow->devolver($pr, $request->input('mensagem'));
        return back();
    }

    /** Finaliza a solicitação */
    public function finalizar(RequestWorkflowService $workflow, $id)
    {
        $pr = ProductRequest::findOrFail($id);
        $workflow->finalizar($pr);
        return redirect()->route('requests.show', $pr->id)->with('ok', 'Finalizada!');
    }

    /** Upload de anexos (implementar regras de arquivo depois) */
    public function upload(Request $request, $id)
    {
        // TODO: validar mimetype/size e salvar em storage/app/products
    }

    /* ======================== Helpers ======================== */

    /**
     * Checa papéis do usuário; funciona com Spatie (hasAnyRole)
     * e também com relação roles() simples (tabela pivot role_user).
     */
    private function userHasAnyRole(User $user, array $roles): bool
    {
        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole($roles);
        }

        // fallback: tenta relation roles()->whereIn('name', ...)
        if (method_exists($user, 'roles')) {
            return $user->roles()->whereIn('name', $roles)->exists();
        }

        // último recurso: se o model tiver um atributo "role" simples
        if (isset($user->role)) {
            return in_array($user->role, $roles, true);
        }

        return false;
    }
}
