<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBasicsRequest;
use App\Http\Requests\UpdateEstoqueRequest;
use App\Http\Requests\UpdateFiscalRequest;
use App\Models\ProductRequest;
use App\Models\PreProduct;
use App\Models\User; // <-- para fallback de usuário
use App\Services\RequestWorkflowService;
use Illuminate\Http\Request;

class ProductRequestController extends Controller
{
    public function index() {
        $items = ProductRequest::latest()->paginate(20);
        return view('requests.index', compact('items'));
    }

    public function create() {
        return view('requests.create'); // form abas Cadastrais (solicitante)
    }

    public function store(StoreBasicsRequest $req, RequestWorkflowService $workflow) {
        // Fallback caso não tenha auth configurado ainda
        $requestedBy = auth()->id() ?? optional(User::first())->id ?? 1;

        $pr = ProductRequest::create([
            'requested_by_id' => $requestedBy,
            'status' => 'RASCUNHO',
            'current_sector' => 'SOLICITANTE',
        ]);

        PreProduct::create(array_merge(
            ['product_request_id' => $pr->id],
            $req->validated()
        ));

        $workflow->log($pr, 'CRIAR', null, 'RASCUNHO', 'Solicitação criada');
        return redirect()->route('requests.show', $pr->id);
    }

    public function show($id) {
        $pr = ProductRequest::with('preProduct')->findOrFail($id);
        return view('requests.show', compact('pr'));
    }

    public function edit($id) {
        $pr = ProductRequest::with('preProduct')->findOrFail($id);
        return view('requests.edit', compact('pr')); // renderiza abas conforme setor
    }

    public function updateEstoque(UpdateEstoqueRequest $req, $id) {
        $pr = ProductRequest::with('preProduct')->findOrFail($id);
        // $this->authorize('editarEstoque', $pr); // habilitar quando criar a Policy
        $pr->preProduct->update($req->validated());
        return back()->with('ok', 'Dados de Estoque atualizados.');
    }

    public function updateFiscal(UpdateFiscalRequest $req, $id) {
        $pr = ProductRequest::with('preProduct')->findOrFail($id);
        // $this->authorize('editarFiscal', $pr); // habilitar quando criar a Policy
        $pr->preProduct->update($req->validated());
        return back()->with('ok', 'Dados Fiscais/Contábeis atualizados.');
    }

    public function enviar(Request $request, $id, $proximo, RequestWorkflowService $workflow) {
        $pr = ProductRequest::with('preProduct')->findOrFail($id);
        $workflow->enviar($pr, strtoupper($proximo), $request->input('mensagem'));
        return redirect()->route('requests.show', $pr->id);
    }

    public function devolver(Request $request, $id, RequestWorkflowService $workflow) {
        $pr = ProductRequest::findOrFail($id);
        $workflow->devolver($pr, $request->input('mensagem'));
        return back();
    }

    public function finalizar(RequestWorkflowService $workflow, $id) {
        $pr = ProductRequest::findOrFail($id);
        $workflow->finalizar($pr);
        return redirect()->route('requests.show', $pr->id)->with('ok', 'Finalizada!');
    }

    public function upload(Request $request, $id) {
        // armazenar em storage/app/products
        // validar mimetypes e size conforme sua política
    }
}
