<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBasicsRequest;
use App\Http\Requests\UpdateEstoqueRequest;
use App\Http\Requests\UpdateFiscalRequest;
use App\Models\ProductRequest;
use App\Models\PreProduct;
use App\Models\PreProductBasic;
use App\Models\PreProductOperational;
use App\Models\PreProductPricing;
use App\Models\PreProductFiscal;
use App\Models\User;
use App\Services\RequestWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductRequestController extends Controller
{
    /** Lista com paginação */
    public function index()
    {
        $items = ProductRequest::with([
            'preProduct.basic',
            'preProduct.operational',
            'preProduct.pricing',
            'preProduct.fiscal',
        ])->latest()->paginate(50);

        return view('requests.index', compact('items'));
    }

    /** Form de criação (solicitante) */
    public function create()
    {
        return view('requests.create');
    }

    /** Criação */
    public function store(StoreBasicsRequest $request, RequestWorkflowService $workflow)
    {
        $validated = $request->validated();
        $payload   = $this->normalizeNumbersAndDates($validated);

        DB::transaction(function () use ($payload, $workflow, &$pr) {
            $pr = ProductRequest::create([
                'requested_by_id' => Auth::id(),
                'status'          => 'RASCUNHO',
                'current_sector'  => 'SOLICITANTE',
            ]);

            $pp = PreProduct::create(['product_request_id' => $pr->id]);

            $basics = Arr::only($payload, $this->keysBasics());
            $oper   = Arr::only($payload, $this->keysOperational());
            $price  = Arr::only($payload, $this->keysPricing());

            // Fiscal: pega do payload, mapeia alias, normaliza e salva
            $fiscal = Arr::only($payload, array_merge($this->keysFiscal(), ['tem_st','retencao_iss','calcula_inss']));
            $fiscal = $this->mapFiscalAliases($fiscal);
            $fiscal = $this->normalizeFiscalBooleans($fiscal);

            if ($basics) {
                PreProductBasic::updateOrCreate(
                    ['pre_product_id' => $pp->id],
                    $basics + ['pre_product_id' => $pp->id]
                );
            }

            if ($oper) {
                PreProductOperational::updateOrCreate(
                    ['pre_product_id' => $pp->id],
                    $oper + ['pre_product_id' => $pp->id]
                );
            }

            if ($price) {
                PreProductPricing::updateOrCreate(
                    ['pre_product_id' => $pp->id],
                    $price + ['pre_product_id' => $pp->id]
                );
            }

            if ($fiscal) {
                PreProductFiscal::updateOrCreate(
                    ['pre_product_id' => $pp->id],
                    $fiscal + ['pre_product_id' => $pp->id]
                );
            }

            $workflow->log($pr, 'CRIAR', null, 'RASCUNHO', 'Solicitação criada');
        });

        return redirect()->route('requests.show', $pr->id)->with('ok', 'Solicitação criada.');
    }

    /** Visualização */
    public function show(ProductRequest $pr)
    {
        $pr->load('preProduct.basic','preProduct.operational','preProduct.pricing','preProduct.fiscal');
        return view('requests.show', compact('pr'));
    }

    /** Form de edição/confirmação */
    public function edit(ProductRequest $pr)
    {
        $pr->load('preProduct.basic','preProduct.operational','preProduct.pricing','preProduct.fiscal');

        $user       = auth()->user();
        $canEstoque = $this->userHasAnyRole($user, ['admin', 'estoque']);
        $canFiscal  = $this->userHasAnyRole($user, ['admin', 'fiscal', 'contabil']);

        if (!$pr->preProduct) {
            $pp = PreProduct::create(['product_request_id' => $pr->id]);
            $pr->setRelation('preProduct', $pp);
        }

        return view('requests.edit', compact('pr', 'canEstoque', 'canFiscal'));
    }

    /**
     * Update geral (aceita basic[...] e fiscal[...]; ou campos planos como fallback)
     */
    public function update(Request $request, ProductRequest $pr, RequestWorkflowService $workflow)
    {
        $in     = $request->all();
        $basic  = $in['basic']  ?? Arr::only($in, $this->keysBasics());
        $fiscal = $in['fiscal'] ?? Arr::only($in, array_merge($this->keysFiscal(), ['tem_st','retencao_iss','calcula_inss']));

        $basic  = $this->normalizeNumbersAndDates($basic);
        $fiscal = $this->normalizeNumbersAndDates($fiscal);
        $fiscal = $this->mapFiscalAliases($fiscal);
        $fiscal = $this->normalizeFiscalBooleans($fiscal);

        DB::transaction(function () use ($pr, $basic, $fiscal, $workflow) {
            $pp = $pr->preProduct ?: PreProduct::create(['product_request_id' => $pr->id]);

            if ($basic) {
                PreProductBasic::updateOrCreate(
                    ['pre_product_id' => $pp->id],
                    $basic + ['pre_product_id' => $pp->id]
                );
            }

            if ($fiscal) {
                PreProductFiscal::updateOrCreate(
                    ['pre_product_id' => $pp->id],
                    $fiscal + ['pre_product_id' => $pp->id]
                );
            }

            $workflow->log($pr, 'EDITAR', $pr->status, $pr->status, 'Solicitação atualizada');
        });

        return redirect()->route('requests.show', $pr->id)->with('ok', 'Solicitação atualizada!');
    }

    /** Atualiza somente ESTOQUE/OPERACIONAL (e básicos) */
    public function updateEstoque(UpdateEstoqueRequest $request, ProductRequest $pr, RequestWorkflowService $workflow)
    {
        abort_unless($this->userHasAnyRole($request->user(), ['admin', 'estoque']), 403, 'Sem permissão.');

        $validated = $request->validated();
        $payload   = $this->normalizeNumbersAndDates($validated);

        DB::transaction(function () use ($pr, $payload, $workflow) {
            $pp = $pr->preProduct ?: PreProduct::create(['product_request_id' => $pr->id]);

            $basics = Arr::only($payload, $this->keysBasics());
            if ($basics) {
                PreProductBasic::updateOrCreate(
                    ['pre_product_id' => $pp->id],
                    $basics + ['pre_product_id' => $pp->id]
                );
            }

            $oper = Arr::only($payload, $this->keysOperational());
            if ($oper) {
                PreProductOperational::updateOrCreate(
                    ['pre_product_id' => $pp->id],
                    $oper + ['pre_product_id' => $pp->id]
                );
            }

            $workflow->log($pr, 'EDITAR_ESTOQUE', $pr->status, $pr->status, 'Dados de Estoque atualizados');
        });

        return back()->with('ok', 'Dados de Estoque atualizados.');
    }

    /** Atualiza somente FISCAL/CONTÁBIL */
    public function updateFiscal(UpdateFiscalRequest $request, ProductRequest $pr, RequestWorkflowService $workflow)
    {
        abort_unless($this->userHasAnyRole($request->user(), ['admin', 'fiscal', 'contabil']), 403, 'Sem permissão.');

        $incoming = $request->all();
        $incoming['tem_st']       = $request->boolean('tem_st');
        $incoming['retencao_iss'] = $request->boolean('retencao_iss');

        $validated = validator($incoming, (new UpdateFiscalRequest)->rules())->validate();
        $payload   = $this->normalizeNumbersAndDates($validated);

        DB::transaction(function () use ($pr, $payload, $workflow) {
            $pp = $pr->preProduct ?: PreProduct::create(['product_request_id' => $pr->id]);

            $fiscal = Arr::only($payload, array_merge($this->keysFiscal(), ['tem_st','retencao_iss','calcula_inss']));
            $fiscal = $this->mapFiscalAliases($fiscal);
            $fiscal = $this->normalizeFiscalBooleans($fiscal);

            if ($fiscal) {
                PreProductFiscal::updateOrCreate(
                    ['pre_product_id' => $pp->id],
                    $fiscal + ['pre_product_id' => $pp->id]
                );
            }

            $workflow->log($pr, 'EDITAR_FISCAL', $pr->status, $pr->status, 'Dados Fiscais/Contábeis atualizados');
        });

        return back()->with('ok', 'Dados Fiscais/Contábeis atualizados.');
    }

    /** Fluxo */
    public function enviar(Request $request, ProductRequest $pr, string $proximo, RequestWorkflowService $workflow)
    {
        $workflow->enviar($pr, strtoupper($proximo), $request->input('mensagem'));
        return redirect()->route('requests.show', $pr->id);
    }

    public function devolver(Request $request, ProductRequest $pr, RequestWorkflowService $workflow)
    {
        $workflow->devolver($pr, $request->input('mensagem'));
        return back();
    }

    public function finalizar(RequestWorkflowService $workflow, ProductRequest $pr)
    {
        $workflow->finalizar($pr);
        return redirect()->route('requests.show', $pr->id)->with('ok', 'Finalizada!');
    }

    /** Remoção */
    public function destroy(ProductRequest $pr)
    {
        $pr->delete();
        return redirect()->route('requests.index')->with('ok', 'Solicitação removida.');
    }

    /** Upload (stub) */
    public function upload(Request $request, ProductRequest $pr)
    {
        return back()->with('ok', 'Upload pendente de implementação.');
    }

    /* ================= Helpers ================= */

    private function userHasAnyRole(User $user, array $roles): bool
    {
        if (method_exists($user, 'hasAnyRole')) return $user->hasAnyRole($roles);
        if (method_exists($user, 'roles')) return $user->roles()->whereIn('name', $roles)->exists();
        if (isset($user->role)) return in_array($user->role, $roles, true);
        return false;
    }

    private function keysBasics(): array
    {
        return ['descricao','unidade','marca','modelo','sku','familia','peso','dimensoes','codigo','tipo'];
    }

    private function keysOperational(): array
    {
        return [
            'armazem_padrao','grupo','seg_un_medi','te_padrao','ts_padrao','fator_conv','tipo_conv',
            'alternativo','base_estrut','fornecedor_padrao','loja_padrao','rastro'
        ];
    }

    private function keysPricing(): array
    {
        return [
            'preco_venda','custo_stand','moeda_cstd','ult_calculo','ult_preco','ult_compra','peso_liquido',
            'ult_revisao','dt_referenc','apropriacao','cta_contabil','centro_custo','item_conta',
            'perc_comissao','cod_barras'
        ];
    }

    /**
     * Campos FISCAIS de acordo com o que existe na sua tabela (confirmado no Tinker).
     */
    private function keysFiscal(): array
    {
        return [
            'ncm','cest','pos_ipi_ncm','ex_nbm','ex_ncm','especie_tipi','cod_servico_municipal','origem',
            'cfop_entrada','cfop_saida','cst_icms','csosn','cst_pis','aliq_pis','cst_cofins','aliq_cofins','mva_st',
            'class_fiscal','grupo_trib','cont_seg_soc','imposto_renda','calcula_inss',
            'aliq_icms','aliq_ipi','aliq_iss','red_inss','red_irrf','red_pis','red_cofins',
            'perc_pis','perc_cofins','perc_csll','proprio_icms','icms_pauta','ipi_pauta',
            'aliq_famad','aliq_fecp','solid_saida','solid_entrada','imp_zfranca',
            'tem_st','retencao_iss',
        ];
    }

    /** Normaliza números e datas comuns do formulário */
    private function normalizeNumbersAndDates(array $data): array
    {
        $decimalFields = [
            'peso','fator_conv','preco_venda','custo_stand','ult_preco','peso_liquido','perc_comissao',
            'aliq_icms','aliq_ipi','aliq_iss','red_inss','red_irrf','red_pis','red_cofins',
            'perc_pis','perc_cofins','perc_csll','proprio_icms','icms_pauta','ipi_pauta','aliq_famad',
            'aliq_fecp','solid_saida','solid_entrada','imp_zfranca',
            'aliq_pis','aliq_cofins','mva_st',
        ];
        foreach ($decimalFields as $f) {
            if (array_key_exists($f, $data) && $data[$f] !== null && $data[$f] !== '') {
                $data[$f] = str_replace(['.', ','], ['', '.'], (string)$data[$f]);
            }
        }

        $dateFields = ['ult_calculo','ult_compra','ult_revisao','dt_referenc'];
        foreach ($dateFields as $d) {
            if (!empty($data[$d]) && preg_match('#^\d{2}/\d{2}/\d{4}$#', $data[$d])) {
                [$dd,$mm,$yy] = explode('/', $data[$d]);
                $data[$d] = sprintf('%04d-%02d-%02d', $yy, $mm, $dd);
            }
        }

        return $data;
    }

    /** Converte alias do form -> nomes do banco (garante compatibilidade) */
    private function mapFiscalAliases(array $f): array
    {
        // se a view ainda envia 'cod_serv_iss', converte para 'cod_servico_municipal'
        if (isset($f['cod_serv_iss']) && !isset($f['cod_servico_municipal'])) {
            $f['cod_servico_municipal'] = $f['cod_serv_iss'];
        }
        unset($f['cod_serv_iss']);

        return $f;
    }

    /** Booleans e enum fiscal */
    private function normalizeFiscalBooleans(array $f): array
    {
        $f['tem_st']       = isset($f['tem_st']) ? (bool)$f['tem_st'] : false;
        $f['retencao_iss'] = isset($f['retencao_iss']) ? (bool)$f['retencao_iss'] : false;

        if (array_key_exists('calcula_inss', $f)) {
            $f['calcula_inss'] =
                ($f['calcula_inss'] === 'S' || $f['calcula_inss'] === 1 || $f['calcula_inss'] === '1' || $f['calcula_inss'] === true)
                ? 'S' : 'N';
        }

        return $f;
    }
}
