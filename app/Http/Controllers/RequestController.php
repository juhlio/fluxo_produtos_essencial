<?php

namespace App\Http\Controllers;

use App\Models\ProductRequest;
use App\Models\PreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $items = ProductRequest::with('preProduct')->latest()->paginate(15);
        return view('requests.index', compact('items'));
    }

    public function create()
    {
        return view('requests.create');
    }

    public function store(Request $request)
    {
        // 1) Validação mínima (mantém o fluxo leve)
        $request->validate([
            'descricao'  => ['required','string','max:255'],
            'unidade'    => ['nullable','string','max:50'],
            'marca'      => ['nullable','string','max:100'],
            'modelo'     => ['nullable','string','max:100'],
            'sku'        => ['nullable','string','max:100'],
            'familia'    => ['nullable','string','max:100'],
            'peso'       => ['nullable','numeric'],
            'dimensoes'  => ['nullable','string','max:255'],

            // fiscais/contábeis (opcionais agora)
            'ncm'                 => ['nullable','string','max:10'],
            'cest'                => ['nullable','string','max:10'],
            'origem'              => ['nullable','string','max:2'],
            'cfop_entrada'        => ['nullable','string','max:5'],
            'cfop_saida'          => ['nullable','string','max:5'],
            'cst_icms'            => ['nullable','string','max:3'],
            'csosn'               => ['nullable','string','max:3'],
            'aliq_icms'           => ['nullable','numeric'],
            'aliq_ipi'            => ['nullable','numeric'],
            'cst_pis'             => ['nullable','string','max:2'],
            'aliq_pis'            => ['nullable','numeric'],
            'cst_cofins'          => ['nullable','string','max:2'],
            'aliq_cofins'         => ['nullable','numeric'],
            'tem_st'              => ['nullable','boolean'],
            'mva_st'              => ['nullable','numeric'],
            'cod_servico_municipal'=> ['nullable','string','max:20'],
            'aliq_iss'            => ['nullable','numeric'],
            'retencao_iss'        => ['nullable','boolean'],
            'conta_contabil'      => ['nullable','string','max:100'],
            'natureza'            => ['nullable','string','max:100'],
            'centro_custo_padrao' => ['nullable','string','max:100'],
            'fiscal_rules'        => ['nullable','array'],
        ]);

        // 2) Aliases para compatibilizar names da view (se você manteve nomes diferentes)
        $aliases = [
            'cta_contabil'    => 'conta_contabil',
            'centro_custo'    => 'centro_custo_padrao',
            'cod_serv_iss'    => 'cod_servico_municipal',
            'class_fiscal'    => 'ncm',
            'pos_ipi_ncm'     => 'ncm',
        ];
        foreach ($aliases as $from => $to) {
            if ($request->filled($from) && !$request->filled($to)) {
                $request->merge([$to => $request->input($from)]);
            }
        }

        DB::transaction(function () use ($request) {
            // 3) Cria a solicitação (fluxo)
            $productRequest = ProductRequest::create([
                'requested_by_id' => Auth::id(),
                'status'          => 'aberta',
                'current_sector'  => 'estoque',
                'erp_product_code'=> null,
            ]);

            // 4) Monta os dados do PreProduct usando os fillables do model
            $prePrototype = new PreProduct();
            $data = $request->only($prePrototype->getFillable());
            $data['product_request_id'] = $productRequest->id;

            // Normaliza booleans vindos de checkbox
            $data['tem_st']       = (bool) ($data['tem_st'] ?? false);
            $data['retencao_iss'] = (bool) ($data['retencao_iss'] ?? false);

            // Se fiscal_rules vier como JSON string, tenta decodificar
            if (isset($data['fiscal_rules']) && is_string($data['fiscal_rules'])) {
                $decoded = json_decode($data['fiscal_rules'], true);
                $data['fiscal_rules'] = $decoded ?: null;
            }

            PreProduct::create($data);
        });

        return redirect()->route('requests.index')->with('success', 'Solicitação criada com sucesso!');
    }

    public function show(ProductRequest $requestItem)
    {
        $requestItem->load('preProduct');
        return view('requests.show', ['item' => $requestItem]);
    }

    public function edit(ProductRequest $requestItem)
    {
        $requestItem->load('preProduct');
        return view('requests.edit', ['item' => $requestItem]);
    }

    public function update(Request $request, ProductRequest $requestItem)
    {
        // (podemos implementar depois conforme seu fluxo)
        return back()->with('info', 'Update pendente de implementação.');
    }

    public function destroy(ProductRequest $requestItem)
    {
        $requestItem->delete();
        return redirect()->route('requests.index')->with('success', 'Solicitação removida!');
    }
}
