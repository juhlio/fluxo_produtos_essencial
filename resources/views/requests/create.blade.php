@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true) {{-- Buttons/Responsive --}}
@section('plugins.Toastr', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Nova Solicitação')

@section('content_header')
<h1>Nova Solicitação</h1>
@stop

@section('content')
<form method="post" action="{{ route('requests.store') }}" class="card" autocomplete="off">
    @csrf

    @if ($errors->any())
    <div class="alert alert-danger">
        <b>Corrija os campos:</b>
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card-body">

        {{-- LEGENDA DE CORES (apenas visual) --}}
        <div class="mb-3">
            <span class="badge" style="background:#ffe08a;color:#000;">Básico</span>
            <span class="badge" style="background:#ff6b6b;">Contabilidade</span>
            <span class="badge" style="background:#51cf66;">Fiscal</span>
            <span class="badge" style="background:#74c0fc;">Fiscal/Prefeitura</span>
        </div>

        {{-- ABAS --}}
        <ul class="nav nav-tabs" id="tabsProduto" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-cadastrais" data-toggle="tab" href="#pane-cadastrais" role="tab">Cadastrais</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-impostos" data-toggle="tab" href="#pane-impostos" role="tab">Impostos</a>
            </li>

        </ul>

        <div class="tab-content border p-3" id="tabsProdutoContent">

            {{-- ===================== CADASTRAIS ===================== --}}
            <div class="tab-pane fade show active" id="pane-cadastrais" role="tabpanel" aria-labelledby="tab-cadastrais">
                <div class="row">
                    {{-- Seus campos originais --}}
                    <div class="form-group col-md-12">
                        <label>Descrição* <small class="text-muted">(nome do produto)</small></label>
                        <input name="descricao" class="form-control" required value="{{ old('descricao') }}">
                        @error('descricao') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 form-group">
                        @php
                        $unidades = config('unidades', []); // carrega do config
                        $valorUn = old('unidade');
                        @endphp

                        <label>Unidade de medida*</label>
                        <select name="unidade" class="form-control">
                            <option value="">Selecione</option>
                            @foreach ($unidades as $sigla => $descricao)
                            <option value="{{ $sigla }}" {{ $valorUn === $sigla ? 'selected' : '' }}>
                                {{ $descricao }}
                            </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-3 form-group">
                        <label>Marca*</label>
                        <input name="marca" class="form-control" value="{{ old('marca') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Modelo*</label>
                        <input name="modelo" class="form-control" value="{{ old('modelo') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>SKU*</label>
                        <input name="sku" class="form-control" value="{{ old('sku') }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Família*</label>
                        <input name="familia" class="form-control" value="{{ old('familia') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Peso*</label>
                        <input name="peso" type="number" step="0.0001" inputmode="decimal" class="form-control" value="{{ old('peso') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Dimensões*</label>
                        <input name="dimensoes" class="form-control" value="{{ old('dimensoes') }}" placeholder="CxLxA ou livre">
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Código*</label>
                        <input name="codigo" class="form-control" value="{{ old('codigo') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Tipo*</label>
                        <input name="tipo" class="form-control" value="{{ old('tipo') }}" placeholder="ex.: ME">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Armazém Padrão</label>
                        <input name="armazem_padrao" class="form-control" value="{{ old('armazem_padrao') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Grupo</label>
                        <input name="grupo" class="form-control" value="{{ old('grupo') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Seg. Un. Med.</label>
                        <input name="seg_un_medi" class="form-control" value="{{ old('seg_un_medi') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>TE Padrão</label>
                        <input name="te_padrao" class="form-control" value="{{ old('te_padrao') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>TS Padrão</label>
                        <input name="ts_padrao" class="form-control" value="{{ old('ts_padrao') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Fator Conv.</label>
                        <input name="fator_conv" type="number" step="0.0001" inputmode="decimal" class="form-control" value="{{ old('fator_conv') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Tipo de Conv.</label>
                        <select name="tipo_conv" class="form-control">
                            <option value="">Selecione</option>
                            <option value="M" @selected(old('tipo_conv')==='M' )>M - Multiplicador</option>
                            <option value="D" @selected(old('tipo_conv')==='D' )>D - Divisor</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Alternativo</label>
                        <input name="alternativo" class="form-control" value="{{ old('alternativo') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Preço Venda</label>
                        <input name="preco_venda" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('preco_venda') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Custo Stand.</label>
                        <input name="custo_stand" type="number" step="0.0001" inputmode="decimal" class="form-control" value="{{ old('custo_stand') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Moeda C.Std</label>
                        <input name="moeda_cstd" class="form-control" value="{{ old('moeda_cstd') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Últ. Cálculo</label>
                        <input name="ult_calculo" class="form-control" value="{{ old('ult_calculo') }}" placeholder="dd/mm/aaaa">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Últ. Preço</label>
                        <input name="ult_preco" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('ult_preco') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Últ. Compra</label>
                        <input name="ult_compra" class="form-control" value="{{ old('ult_compra') }}" placeholder="dd/mm/aaaa">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Peso Líquido</label>
                        <input name="peso_liquido" type="number" step="0.0001" inputmode="decimal" class="form-control" value="{{ old('peso_liquido') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-danger">Cta Contábil</label>
                        <input name="cta_contabil" class="form-control" value="{{ old('cta_contabil') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-danger">Centro de Custo</label>
                        <input name="centro_custo" class="form-control" value="{{ old('centro_custo') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="text-danger">Item Conta</label>
                        <input name="item_conta" class="form-control" value="{{ old('item_conta') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Base Estrut.</label>
                        <input name="base_estrut" type="number" step="1" class="form-control" value="{{ old('base_estrut') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Forn. Padrão</label>
                        <input name="fornecedor_padrao" class="form-control" value="{{ old('fornecedor_padrao') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Loja Padrão</label>
                        <input name="loja_padrao" class="form-control" value="{{ old('loja_padrao') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Rastro</label>
                        <select name="rastro" class="form-control">
                            <option value="">Selecione</option>
                            <option value="N" @selected(old('rastro')==='N' )>N - Não utiliza</option>
                            <option value="S" @selected(old('rastro')==='S' )>S - Utiliza</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Últ. Revisão</label>
                        <input name="ult_revisao" class="form-control" value="{{ old('ult_revisao') }}" placeholder="dd/mm/aaaa">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>DT Referenc.</label>
                        <input name="dt_referenc" class="form-control" value="{{ old('dt_referenc') }}" placeholder="dd/mm/aaaa">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Apropriação</label>
                        <input name="apropriacao" class="form-control" value="{{ old('apropriacao') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Fora estado</label>
                        <select name="fora_estado" class="form-control">
                            <option value="">Selecione</option>
                            <option value="S" @selected(old('fora_estado')==='S' )>Sim</option>
                            <option value="N" @selected(old('fora_estado')==='N' )>Não</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Fantasma</label>
                        <select name="fantasma" class="form-control">
                            <option value="">Selecione</option>
                            <option value="S" @selected(old('fantasma')==='S' )>Sim</option>
                            <option value="N" @selected(old('fantasma')==='N' )>Não</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>% Comissão</label>
                        <input name="perc_comissao" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('perc_comissao') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Cód. Barras / GTIN</label>
                        <input name="cod_barras" class="form-control" value="{{ old('cod_barras') }}">
                    </div>
                </div>
            </div>

            {{-- ===================== IMPOSTOS ===================== --}}
            <div class="tab-pane fade" id="pane-impostos" role="tabpanel" aria-labelledby="tab-impostos">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="text-success">Aliq. ICMS</label>
                        <input name="aliq_icms" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_icms') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Aliq. IPI</label>
                        <input name="aliq_ipi" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_ipi') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Aliq. ISS</label>
                        <input name="aliq_iss" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_iss') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Pos. IPI/NCM</label>
                        <input name="pos_ipi_ncm" class="form-control" value="{{ old('pos_ipi_ncm') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="text-success">Ex-NBM</label>
                        <input name="ex_nbm" class="form-control" value="{{ old('ex_nbm') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Ex-NCM</label>
                        <input name="ex_ncm" class="form-control" value="{{ old('ex_ncm') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Espécie TIPI</label>
                        <input name="especie_tipi" class="form-control" value="{{ old('especie_tipi') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Cód. Serv. ISS</label>
                        <input name="cod_serv_iss" class="form-control" value="{{ old('cod_serv_iss') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="text-success">Origem</label>
                        <input name="origem" class="form-control" value="{{ old('origem') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Class. Fiscal (NCM)</label>
                        <input name="class_fiscal" class="form-control" value="{{ old('class_fiscal') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Grupo Trib.</label>
                        <input name="grupo_trib" class="form-control" value="{{ old('grupo_trib') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Cont. Seg. Soc.</label>
                        <input name="cont_seg_soc" class="form-control" value="{{ old('cont_seg_soc') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="text-success">Impos. Renda</label>
                        <input name="imposto_renda" class="form-control" value="{{ old('imposto_renda') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Calcula INSS</label>
                        <select name="calcula_inss" class="form-control">
                            <option value="">Selecione</option>
                            <option value="S" @selected(old('calcula_inss')==='S' )>S - Sim</option>
                            <option value="N" @selected(old('calcula_inss')==='N' )>N - Não</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">% Red. INSS</label>
                        <input name="red_inss" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('red_inss') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">% Red. IRRF</label>
                        <input name="red_irrf" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('red_irrf') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="text-success">% Red. PIS</label>
                        <input name="red_pis" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('red_pis') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">% Red. COFINS</label>
                        <input name="red_cofins" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('red_cofins') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">% PIS</label>
                        <input name="perc_pis" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('perc_pis') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">% COFINS</label>
                        <input name="perc_cofins" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('perc_cofins') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="text-success">Perc. CSLL</label>
                        <input name="perc_csll" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('perc_csll') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">P. ICMS Prop.</label>
                        <input name="proprio_icms" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('proprio_icms') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">ICMS Pauta</label>
                        <input name="icms_pauta" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('icms_pauta') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">IPI de Pauta</label>
                        <input name="ipi_pauta" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('ipi_pauta') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="text-success">Aliq. FAMAD</label>
                        <input name="aliq_famad" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_famad') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Aliq. FECP</label>
                        <input name="aliq_fecp" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_fecp') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="text-success">Solid. Saída</label>
                        <input name="solid_saida" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('solid_saida') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="text-success">Solid. Entrada</label>
                        <input name="solid_entrada" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('solid_entrada') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="text-success">Imp. Z. Franca</label>
                        <input name="imp_zfranca" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('imp_zfranca') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('requests.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Voltar</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
    </div>
</form>

{{-- (Opcional) manter a aba ativa ao voltar com erros/old() --}}
<script>
    (function() {
        const hash = window.location.hash;
        if (hash && document.querySelector(`a[href="${hash}"]`)) {
            document.querySelector(`a[href="${hash}"]`).click();
        }
        document.querySelectorAll('#tabsProduto a[data-toggle="tab"]').forEach(a => {
            a.addEventListener('shown.bs.tab', e => history.replaceState(null, '', e.target.getAttribute('href')));
        });
    })();
</script>

@if ($errors->any())
<div class="alert alert-danger">
    <b>Corrija os campos:</b>
    <ul class="mb-0">
        @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

@stop
