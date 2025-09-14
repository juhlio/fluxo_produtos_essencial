@extends('adminlte::page')

@php($pr    = $pr ?? $item ?? $requestItem ?? null)
@php($basic = $pr->preProduct?->basic)
@php($fiscal= $pr->preProduct?->fiscal)

@section('plugins.Toastr', true)
@section('title', "Editar Solicitação #{$pr->id}")

@section('content_header')
  <h1 class="mb-0">Editar Solicitação #{{ $pr->id }}</h1>
@stop

@section('content')
<form method="post" action="{{ route('requests.update', $pr->id) }}" class="card" autocomplete="off">
  @csrf
  @method('PUT')

  @if ($errors->any())
    <div class="alert alert-danger m-3">
      <b>Corrija os campos:</b>
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card-body pt-2">
    <div class="row">

      {{-- ===================== ESTOQUE / CADASTRAIS ===================== --}}
      <div class="col-lg-7">
        <div class="card shadow-sm">
          <div class="card-header d-flex align-items-center">
            <i class="fas fa-box-open mr-2 text-primary"></i>
            <b>Estoque</b>
          </div>

          <div class="card-body">

            <div class="form-group">
              <label>Descrição* <small class="text-muted">(nome do produto)</small></label>
              <input name="basic[descricao]" class="form-control" required
                     value="{{ old('basic.descricao', $basic?->descricao) }}">
              @error('basic.descricao') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                @php($valorUn = old('basic.unidade', $basic->unidade ?? null))
                <label>Unidade</label>
                <select name="basic[unidade]" class="form-control">
                  <option value="">Selecione</option>
                  @foreach (config('unidades', []) as $sigla => $descricao)
                    <option value="{{ $sigla }}" @selected($valorUn === $sigla)>{{ $descricao }}</option>
                  @endforeach
                </select>
                @error('basic.unidade') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="form-group col-md-6">
                <label>SKU</label>
                <input name="basic[sku]" class="form-control" value="{{ old('basic.sku', $basic?->sku) }}">
                @error('basic.sku') <small class="text-danger">{{ $message }}</small> @enderror
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Marca</label>
                <input name="basic[marca]" class="form-control" value="{{ old('basic.marca', $basic?->marca) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Modelo</label>
                <input name="basic[modelo]" class="form-control" value="{{ old('basic.modelo', $basic?->modelo) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Família</label>
                <input name="basic[familia]" class="form-control" value="{{ old('basic.familia', $basic?->familia) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Peso</label>
                <input name="basic[peso]" type="number" step="0.0001" inputmode="decimal" class="form-control"
                       value="{{ old('basic.peso', $basic?->peso) }}">
              </div>
            </div>

            <div class="form-group">
              <label>Dimensões</label>
              <input name="basic[dimensoes]" class="form-control" placeholder="CxLxA ou livre"
                     value="{{ old('basic.dimensoes', $basic?->dimensoes) }}">
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Código</label>
                <input name="basic[codigo]" class="form-control" value="{{ old('basic.codigo', $basic?->codigo) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Tipo</label>
                <input name="basic[tipo]" class="form-control" value="{{ old('basic.tipo', $basic?->tipo) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Armazém Padrão</label>
                <input name="basic[armazem_padrao]" class="form-control"
                       value="{{ old('basic.armazem_padrao', $basic?->armazem_padrao) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Grupo</label>
                <input name="basic[grupo]" class="form-control" value="{{ old('basic.grupo', $basic?->grupo) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Seg. Un. Med.</label>
                <input name="basic[seg_un_medi]" class="form-control"
                       value="{{ old('basic.seg_un_medi', $basic?->seg_un_medi) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Fator Conv.</label>
                <input name="basic[fator_conv]" type="number" step="0.0001" inputmode="decimal" class="form-control"
                       value="{{ old('basic.fator_conv', $basic?->fator_conv) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Tipo de Conv.</label>
                @php($tipoConv = old('basic.tipo_conv', $basic?->tipo_conv))
                <select name="basic[tipo_conv]" class="form-control">
                  <option value="">Selecione</option>
                  <option value="M" @selected($tipoConv==='M')>M - Multiplicador</option>
                  <option value="D" @selected($tipoConv==='D')>D - Divisor</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label>Alternativo</label>
                <input name="basic[alternativo]" class="form-control"
                       value="{{ old('basic.alternativo', $basic?->alternativo) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Preço Venda</label>
                <input name="basic[preco_venda]" type="number" step="0.01" inputmode="decimal" class="form-control"
                       value="{{ old('basic.preco_venda', $basic?->preco_venda) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Custo Stand.</label>
                <input name="basic[custo_stand]" type="number" step="0.0001" inputmode="decimal" class="form-control"
                       value="{{ old('basic.custo_stand', $basic?->custo_stand) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Moeda C.Std</label>
                <input name="basic[moeda_cstd]" class="form-control"
                       value="{{ old('basic.moeda_cstd', $basic?->moeda_cstd) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Últ. Cálculo</label>
                <input name="basic[ult_calculo]" class="form-control" placeholder="dd/mm/aaaa"
                       value="{{ old('basic.ult_calculo', $basic?->ult_calculo) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Últ. Preço</label>
                <input name="basic[ult_preco]" type="number" step="0.01" inputmode="decimal" class="form-control"
                       value="{{ old('basic.ult_preco', $basic?->ult_preco) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Últ. Compra</label>
                <input name="basic[ult_compra]" class="form-control" placeholder="dd/mm/aaaa"
                       value="{{ old('basic.ult_compra', $basic?->ult_compra) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Peso Líquido</label>
                <input name="basic[peso_liquido]" type="number" step="0.0001" inputmode="decimal" class="form-control"
                       value="{{ old('basic.peso_liquido', $basic?->peso_liquido) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-danger">Cta Contábil</label>
                <input name="basic[cta_contabil]" class="form-control"
                       value="{{ old('basic.cta_contabil', $basic?->cta_contabil) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-danger">Centro de Custo</label>
                <input name="basic[centro_custo]" class="form-control"
                       value="{{ old('basic.centro_custo', $basic?->centro_custo) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-danger">Item Conta</label>
                <input name="basic[item_conta]" class="form-control"
                       value="{{ old('basic.item_conta', $basic?->item_conta) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Base Estrut.</label>
                <input name="basic[base_estrut]" type="number" step="1" class="form-control"
                       value="{{ old('basic.base_estrut', $basic?->base_estrut) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Forn. Padrão</label>
                <input name="basic[fornecedor_padrao]" class="form-control"
                       value="{{ old('basic.fornecedor_padrao', $basic?->fornecedor_padrao) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Loja Padrão</label>
                <input name="basic[loja_padrao]" class="form-control"
                       value="{{ old('basic.loja_padrao', $basic?->loja_padrao) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Rastro</label>
                @php($rastro = old('basic.rastro', $basic?->rastro))
                <select name="basic[rastro]" class="form-control">
                  <option value="">Selecione</option>
                  <option value="N" @selected($rastro==='N')>N - Não utiliza</option>
                  <option value="S" @selected($rastro==='S')>S - Utiliza</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Últ. Revisão</label>
                <input name="basic[ult_revisao]" class="form-control" placeholder="dd/mm/aaaa"
                       value="{{ old('basic.ult_revisao', $basic?->ult_revisao) }}">
              </div>
              <div class="form-group col-md-6">
                <label>DT Referenc.</label>
                <input name="basic[dt_referenc]" class="form-control" placeholder="dd/mm/aaaa"
                       value="{{ old('basic.dt_referenc', $basic?->dt_referenc) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Apropriação</label>
                <input name="basic[apropriacao]" class="form-control"
                       value="{{ old('basic.apropriacao', $basic?->apropriacao) }}">
              </div>
              <div class="form-group col-md-6">
                <label>Fora estado</label>
                @php($fora = old('basic.fora_estado', $basic?->fora_estado))
                <select name="basic[fora_estado]" class="form-control">
                  <option value="">Selecione</option>
                  <option value="S" @selected($fora==='S')>Sim</option>
                  <option value="N" @selected($fora==='N')>Não</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Fantasma</label>
                @php($fantasma = old('basic.fantasma', $basic?->fantasma))
                <select name="basic[fantasma]" class="form-control">
                  <option value="">Selecione</option>
                  <option value="S" @selected($fantasma==='S')>Sim</option>
                  <option value="N" @selected($fantasma==='N')>Não</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label>% Comissão</label>
                <div class="input-group">
                  <input name="basic[perc_comissao]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('basic.perc_comissao', $basic?->perc_comissao) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Cód. Barras / GTIN</label>
              <input name="basic[cod_barras]" class="form-control"
                     value="{{ old('basic.cod_barras', $basic?->cod_barras) }}">
            </div>

          </div>
        </div>
      </div>

      {{-- ===================== FISCAL / IMPOSTOS ===================== --}}
      <div class="col-lg-5">
        <div class="card shadow-sm">
          <div class="card-header d-flex align-items-center">
            <i class="fas fa-file-invoice-dollar mr-2 text-success"></i>
            <b>Fiscal / Impostos</b>
          </div>

          <div class="card-body">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Aliq. ICMS</label>
                <div class="input-group">
                  <input name="fiscal[aliq_icms]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.aliq_icms', $fiscal?->aliq_icms) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">Aliq. IPI</label>
                <div class="input-group">
                  <input name="fiscal[aliq_ipi]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.aliq_ipi', $fiscal?->aliq_ipi) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Aliq. ISS</label>
                <div class="input-group">
                  <input name="fiscal[aliq_iss]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.aliq_iss', $fiscal?->aliq_iss) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">Pos. IPI/NCM</label>
                <input name="fiscal[pos_ipi_ncm]" class="form-control"
                       value="{{ old('fiscal.pos_ipi_ncm', $fiscal?->pos_ipi_ncm) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Ex-NBM</label>
                <input name="fiscal[ex_nbm]" class="form-control" value="{{ old('fiscal.ex_nbm', $fiscal?->ex_nbm) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">Ex-NCM</label>
                <input name="fiscal[ex_ncm]" class="form-control" value="{{ old('fiscal.ex_ncm', $fiscal?->ex_ncm) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Espécie TIPI</label>
                <input name="fiscal[especie_tipi]" class="form-control"
                       value="{{ old('fiscal.especie_tipi', $fiscal?->especie_tipi) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">Cód. Serv. ISS</label>
                <input name="fiscal[cod_serv_iss]" class="form-control"
                       value="{{ old('fiscal.cod_serv_iss', $fiscal?->cod_serv_iss) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Origem</label>
                <input name="fiscal[origem]" class="form-control"
                       value="{{ old('fiscal.origem', $fiscal?->origem) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">Class. Fiscal (NCM)</label>
                <input name="fiscal[class_fiscal]" class="form-control"
                       value="{{ old('fiscal.class_fiscal', $fiscal?->class_fiscal) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Grupo Trib.</label>
                <input name="fiscal[grupo_trib]" class="form-control"
                       value="{{ old('fiscal.grupo_trib', $fiscal?->grupo_trib) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">Cont. Seg. Soc.</label>
                <input name="fiscal[cont_seg_soc]" class="form-control"
                       value="{{ old('fiscal.cont_seg_soc', $fiscal?->cont_seg_soc) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Impos. Renda</label>
                <input name="fiscal[imposto_renda]" class="form-control"
                       value="{{ old('fiscal.imposto_renda', $fiscal?->imposto_renda) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">Calcula INSS</label>
                @php($calcInss = old('fiscal.calcula_inss', $fiscal?->calcula_inss))
                <select name="fiscal[calcula_inss]" class="form-control">
                  <option value="">Selecione</option>
                  <option value="S" @selected($calcInss==='S')>S - Sim</option>
                  <option value="N" @selected($calcInss==='N')>N - Não</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">% Red. INSS</label>
                <div class="input-group">
                  <input name="fiscal[red_inss]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.red_inss', $fiscal?->red_inss) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">% Red. IRRF</label>
                <div class="input-group">
                  <input name="fiscal[red_irrf]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.red_irrf', $fiscal?->red_irrf) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">% Red. PIS</label>
                <div class="input-group">
                  <input name="fiscal[red_pis]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.red_pis', $fiscal?->red_pis) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">% Red. COFINS</label>
                <div class="input-group">
                  <input name="fiscal[red_cofins]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.red_cofins', $fiscal?->red_cofins) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">% PIS</label>
                <div class="input-group">
                  <input name="fiscal[perc_pis]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.perc_pis', $fiscal?->perc_pis) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">% COFINS</label>
                <div class="input-group">
                  <input name="fiscal[perc_cofins]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.perc_cofins', $fiscal?->perc_cofins) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Perc. CSLL</label>
                <div class="input-group">
                  <input name="fiscal[perc_csll]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.perc_csll', $fiscal?->perc_csll) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">P. ICMS Prop.</label>
                <div class="input-group">
                  <input name="fiscal[proprio_icms]" type="number" step="0.01" inputmode="decimal" class="form-control"
                         value="{{ old('fiscal.proprio_icms', $fiscal?->proprio_icms) }}">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">ICMS Pauta</label>
                <input name="fiscal[icms_pauta]" type="number" step="0.01" inputmode="decimal" class="form-control"
                       value="{{ old('fiscal.icms_pauta', $fiscal?->icms_pauta) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">IPI de Pauta</label>
                <input name="fiscal[ipi_pauta]" type="number" step="0.01" inputmode="decimal" class="form-control"
                       value="{{ old('fiscal.ipi_pauta', $fiscal?->ipi_pauta) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Aliq. FAMAD</label>
                <input name="fiscal[aliq_famad]" type="number" step="0.01" inputmode="decimal" class="form-control"
                       value="{{ old('fiscal.aliq_famad', $fiscal?->aliq_famad) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">Aliq. FECP</label>
                <input name="fiscal[aliq_fecp]" type="number" step="0.01" inputmode="decimal" class="form-control"
                       value="{{ old('fiscal.aliq_fecp', $fiscal?->aliq_fecp) }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="text-success">Solid. Saída</label>
                <input name="fiscal[solid_saida]" type="number" step="0.01" inputmode="decimal" class="form-control"
                       value="{{ old('fiscal.solid_saida', $fiscal?->solid_saida) }}">
              </div>
              <div class="form-group col-md-6">
                <label class="text-success">Solid. Entrada</label>
                <input name="fiscal[solid_entrada]" type="number" step="0.01" inputmode="decimal" class="form-control"
                       value="{{ old('fiscal.solid_entrada', $fiscal?->solid_entrada) }}">
              </div>
            </div>

            <div class="form-group">
              <label class="text-success">Imp. Z. Franca</label>
              <input name="fiscal[imp_zfranca]" type="number" step="0.01" inputmode="decimal" class="form-control"
                     value="{{ old('fiscal.imp_zfranca', $fiscal?->imp_zfranca) }}">
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="card-footer d-flex justify-content-between">
    <a href="{{ route('requests.show', $pr->id) }}" class="btn btn-default">
      <i class="fas fa-times"></i> Cancelar
    </a>
    <button type="submit" class="btn btn-primary">
      <i class="fas fa-save"></i> Salvar
    </button>
  </div>
</form>
@stop
