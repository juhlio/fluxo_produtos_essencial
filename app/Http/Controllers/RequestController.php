<?php

namespace App\Http\Controllers;

use App\Models\ProductRequest; // Model que aponta para a tabela 'requests'
use App\Models\PreProduct;     // Detalhamento opcional em 'pre_products'
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    /**
     * Lista de solicitações
     */
    public function index()
    {
        // Carrega a relação com pre_products (se houver)
        $items = ProductRequest::with('preProduct')->latest()->paginate(15);
        return view('requests.index', compact('items'));
    }

    /**
     * Form de criação
     */
    public function create()
    {
        return view('requests.create');
    }

    /**
     * Salva a solicitação
     */
    public function store(Request $request): RedirectResponse
    {
        // 1) Validação mínima (pode trocar para um FormRequest depois)
        $request->validate([
            'descricao'  => ['required', 'string', 'max:255'],
            'unidade'    => ['nullable', 'string', 'max:50'],
            'marca'      => ['nullable', 'string', 'max:100'],
            'modelo'     => ['nullable', 'string', 'max:100'],
            'sku'        => ['nullable', 'string', 'max:100'],
            'familia'    => ['nullable', 'string', 'max:100'],
            'peso'       => ['nullable', 'numeric'],
            'dimensoes'  => ['nullable', 'string', 'max:255'],

            // fiscais/contábeis (opcionais agora)
            'ncm'                   => ['nullable', 'string', 'max:10'],
            'cest'                  => ['nullable', 'string', 'max:10'],
            'origem'                => ['nullable', 'string', 'max:5'], // aumentei p/ 5 pra comportar códigos
            'cfop_entrada'          => ['nullable', 'string', 'max:5'],
            'cfop_saida'            => ['nullable', 'string', 'max:5'],
            'cst_icms'              => ['nullable', 'string', 'max:3'],
            'csosn'                 => ['nullable', 'string', 'max:3'],
            'aliq_icms'             => ['nullable', 'numeric'],
            'aliq_ipi'              => ['nullable', 'numeric'],
            'cst_pis'               => ['nullable', 'string', 'max:2'],
            'aliq_pis'              => ['nullable', 'numeric'],
            'cst_cofins'            => ['nullable', 'string', 'max:2'],
            'aliq_cofins'           => ['nullable', 'numeric'],
            'tem_st'                => ['nullable', 'boolean'],
            'mva_st'                => ['nullable', 'numeric'],
            'cod_servico_municipal' => ['nullable', 'string', 'max:20'],
            'aliq_iss'              => ['nullable', 'numeric'],
            'retencao_iss'          => ['nullable', 'boolean'],
            'conta_contabil'        => ['nullable', 'string', 'max:100'],
            'natureza'              => ['nullable', 'string', 'max:100'],
            'centro_custo_padrao'   => ['nullable', 'string', 'max:100'],
            'fiscal_rules'          => ['nullable', 'array'],
        ]);

        // 2) Aliases para compatibilizar names do form com nomes internos
        //    (mantém compatibilidade com telas antigas/prints)
        $aliases = [
            'cta_contabil' => 'conta_contabil',
            'centro_custo' => 'centro_custo_padrao',
            'cod_serv_iss' => 'cod_servico_municipal',
            'class_fiscal' => 'ncm',
            'pos_ipi_ncm'  => 'ncm',
        ];
        foreach ($aliases as $from => $to) {
            if ($request->filled($from) && !$request->filled($to)) {
                $request->merge([$to => $request->input($from)]);
            }
        }

        DB::transaction(function () use ($request) {
            /**
             * 3) Salva NA TABELA 'requests'
             *    Pega apenas colunas permitidas pelo Model ProductRequest (fillable)
             */
            $productRequestPrototype = new ProductRequest();
            $requestPayload = $request->only($productRequestPrototype->getFillable());

            // (opcional) se seu Model tiver essas colunas, preenche. Se não tiver, ignora.
            // Ex.: $requestPayload['requested_by_id'] = Auth::id();

            $productRequest = ProductRequest::create($requestPayload);

            /**
             * 4) (Opcional) Salva também um “espelho”/detalhamento em 'pre_products'
             *    Caso você queira manter o fluxo com a tabela auxiliar.
             *    Se não usar pre_products, pode remover este bloco.
             */
            if (class_exists(PreProduct::class)) {
                $prePrototype = new PreProduct();
                $preData = $request->only($prePrototype->getFillable());
                $preData['product_request_id'] = $productRequest->id;

                // Normaliza booleans vindos de checkbox
                $preData['tem_st']       = (bool) ($preData['tem_st'] ?? false);
                $preData['retencao_iss'] = (bool) ($preData['retencao_iss'] ?? false);

                // Se fiscal_rules vier como JSON string, tenta decodificar
                if (isset($preData['fiscal_rules']) && is_string($preData['fiscal_rules'])) {
                    $decoded = json_decode($preData['fiscal_rules'], true);
                    $preData['fiscal_rules'] = $decoded ?: null;
                }

                PreProduct::create($preData);
            }
        });

        return redirect()->route('requests.index')->with('success', 'Solicitação criada com sucesso!');
    }

    /**
     * Detalhe
     */
    public function show(ProductRequest $requestItem)
    {
        $requestItem->load('preProduct');
        $unidades = config('unidades');
        return view('requests.show', ['item' => $requestItem, 'unidades' => $unidades]);
    }

    /**
     * Edição
     */
    public function edit(ProductRequest $requestItem)
    {
        $requestItem->load('preProduct.basic', 'preProduct.fiscal');
        return view('requests.edit', ['pr' => $requestItem]);
    }
    /**
     * Update (implemente conforme seu fluxo)
     */
    public function update(\Illuminate\Http\Request $request, ProductRequest $requestItem)
    {
        $data = $request->validate([
            // BASIC
            'basic.descricao'  => ['nullable', 'string', 'max:255'],
            'basic.unidade'    => ['nullable', 'string', 'max:50'],
            'basic.sku'        => ['nullable', 'string', 'max:120'],
            'basic.marca'      => ['nullable', 'string', 'max:120'],
            'basic.modelo'     => ['nullable', 'string', 'max:120'],
            'basic.familia'    => ['nullable', 'string', 'max:120'],
            'basic.peso'       => ['nullable', 'numeric'],
            'basic.dimensoes'  => ['nullable', 'string', 'max:60'],
            'basic.codigo'     => ['nullable', 'string', 'max:60'],
            'basic.tipo'       => ['nullable', 'string', 'max:10'],

            // FISCAL
            'fiscal.ncm'                   => ['nullable', 'string', 'max:10'],
            'fiscal.origem'                => ['nullable', 'string', 'max:30'],
            'fiscal.cfop_entrada'          => ['nullable', 'string', 'max:5'],
            'fiscal.cfop_saida'            => ['nullable', 'string', 'max:5'],
            'fiscal.cst_icms'              => ['nullable', 'string', 'max:3'],
            'fiscal.csosn'                 => ['nullable', 'string', 'max:3'],
            'fiscal.aliq_icms'             => ['nullable', 'numeric'],
            'fiscal.aliq_ipi'              => ['nullable', 'numeric'],
            'fiscal.cst_pis'               => ['nullable', 'string', 'max:2'],
            'fiscal.aliq_pis'              => ['nullable', 'numeric'],
            'fiscal.cst_cofins'            => ['nullable', 'string', 'max:2'],
            'fiscal.aliq_cofins'           => ['nullable', 'numeric'],
            'fiscal.tem_st'                => ['nullable', 'boolean'],
            'fiscal.mva_st'                => ['nullable', 'numeric'],
            'fiscal.cod_servico_municipal' => ['nullable', 'string', 'max:20'],
            'fiscal.aliq_iss'              => ['nullable', 'numeric'],
            'fiscal.retencao_iss'          => ['nullable', 'boolean'],
        ]);

        // garante o PreProduct "pai"
        $pre = $requestItem->preProduct ?? \App\Models\PreProduct::create([
            'product_request_id' => $requestItem->id,
        ]);

        // salva BASIC
        if (isset($data['basic'])) {
            \App\Models\PreProductBasic::updateOrCreate(
                ['pre_product_id' => $pre->id],
                $data['basic']
            );
        }

        // normaliza checkboxes fiscais
        $fiscal = $data['fiscal'] ?? [];
        $fiscal['tem_st']       = isset($fiscal['tem_st']) ? (bool)$fiscal['tem_st'] : false;
        $fiscal['retencao_iss'] = isset($fiscal['retencao_iss']) ? (bool)$fiscal['retencao_iss'] : false;

        // salva FISCAL
        \App\Models\PreProductFiscal::updateOrCreate(
            ['pre_product_id' => $pre->id],
            $fiscal
        );

        return redirect()->route('requests.show', $requestItem->id)
            ->with('ok', 'Solicitação atualizada!');
    }

    /**
     * Remoção
     */
    public function destroy(ProductRequest $requestItem): RedirectResponse
    {
        $requestItem->delete();
        return redirect()->route('requests.index')->with('success', 'Solicitação removida!');
    }
}
