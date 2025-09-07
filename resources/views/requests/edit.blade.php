@extends('adminlte::page')

@section('title', "Editar Solicitação #{$req->id}")

@section('content_header')
    <h1>Editar Solicitação #{{ $req->id }}</h1>
@stop

@section('content')
@php
    /** @var \App\Models\ProductRequest $req */
    $p = $req->preProduct;
    $lockEstoque = empty($canEstoque); // true = desabilita campos de estoque
    $lockFiscal  = empty($canFiscal);  // true = desabilita campos fiscais/contábeis
@endphp

<div class="row">
    {{-- ==================== ESTOQUE ==================== --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><strong>Estoque</strong></div>
            <form method="POST" action="{{ route('requests.update.estoque', $req->id) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Unidade</label>
                            <input name="unidade" class="form-control" value="{{ old('unidade', $p->unidade) }}" {{ $lockEstoque?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>SKU</label>
                            <input name="sku" class="form-control" value="{{ old('sku', $p->sku) }}" {{ $lockEstoque?'disabled':'' }}>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Marca</label>
                            <input name="marca" class="form-control" value="{{ old('marca', $p->marca) }}" {{ $lockEstoque?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Modelo</label>
                            <input name="modelo" class="form-control" value="{{ old('modelo', $p->modelo) }}" {{ $lockEstoque?'disabled':'' }}>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Família</label>
                            <input name="familia" class="form-control" value="{{ old('familia', $p->familia) }}" {{ $lockEstoque?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Peso</label>
                            <input name="peso" type="number" step="0.0001" inputmode="decimal" class="form-control" value="{{ old('peso', $p->peso) }}" {{ $lockEstoque?'disabled':'' }}>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Dimensões</label>
                            <input name="dimensoes" class="form-control" value="{{ old('dimensoes', $p->dimensoes) }}" {{ $lockEstoque?'disabled':'' }}>
                        </div>

                        {{-- Se quiser, acrescente aqui mais campos “de estoque” que você usa na criação --}}
                        {{-- ex.: unidade secundária, fator conv., preço venda, custo, etc. --}}
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" {{ $lockEstoque?'disabled':'' }}>
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== FISCAL / CONTÁBIL ==================== --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><strong>Fiscal/Contábil</strong></div>
            <form method="POST" action="{{ route('requests.update.fiscal', $req->id) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        {{-- Fiscais principais --}}
                        <div class="col-md-6 form-group">
                            <label>NCM*</label>
                            <input name="ncm" class="form-control" value="{{ old('ncm', $p->ncm) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Origem*</label>
                            <select name="origem" class="form-control" {{ $lockFiscal?'disabled':'' }}>
                                @foreach (['0'=>'0 - Nacional','1'=>'1 - Estrangeira - Importação direta','2'=>'2 - Estrangeira - Adquirida no mercado interno','3'=>'3 - Nacional, conteúdo de importação > 40%','4'=>'4 - Nacional, produção c/ PPB','5'=>'5 - Nacional, conteúdo de importação ≤ 40%','6'=>'6 - Estrangeira - Importação direta sem similar','7'=>'7 - Estrangeira - Adquirida no mercado interno sem similar','8'=>'8 - Nacional, mercadoria importada'] as $k => $txt)
                                    <option value="{{ $k }}" @selected(old('origem',$p->origem)==$k)>{{ $txt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>CFOP Entrada</label>
                            <input name="cfop_entrada" class="form-control" value="{{ old('cfop_entrada', $p->cfop_entrada) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>CFOP Saída</label>
                            <input name="cfop_saida" class="form-control" value="{{ old('cfop_saida', $p->cfop_saida) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>CST ICMS</label>
                            <input name="cst_icms" class="form-control" value="{{ old('cst_icms', $p->cst_icms) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>CSOSN</label>
                            <input name="csosn" class="form-control" value="{{ old('csosn', $p->csosn) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Alíq. ICMS (%)</label>
                            <input name="aliq_icms" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_icms', $p->aliq_icms) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Alíq. IPI (%)</label>
                            <input name="aliq_ipi" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_ipi', $p->aliq_ipi) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>CST PIS</label>
                            <input name="cst_pis" class="form-control" value="{{ old('cst_pis', $p->cst_pis) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Alíq. PIS (%)</label>
                            <input name="aliq_pis" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_pis', $p->aliq_pis) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>CST COFINS</label>
                            <input name="cst_cofins" class="form-control" value="{{ old('cst_cofins', $p->cst_cofins) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Alíq. COFINS (%)</label>
                            <input name="aliq_cofins" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_cofins', $p->aliq_cofins) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="d-block">Tem ST?</label>
                            <input type="checkbox" name="tem_st" value="1" {{ old('tem_st',$p->tem_st) ? 'checked' : '' }} {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>MVA ST (%)</label>
                            <input name="mva_st" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('mva_st', $p->mva_st) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Cód. Serviço Municipal (ISS)</label>
                            <input name="cod_servico_municipal" class="form-control" value="{{ old('cod_servico_municipal',$p->cod_servico_municipal) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Alíq. ISS (%)</label>
                            <input name="aliq_iss" type="number" step="0.01" inputmode="decimal" class="form-control" value="{{ old('aliq_iss', $p->aliq_iss) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="d-block">Retenção ISS?</label>
                            <input type="checkbox" name="retencao_iss" value="1" {{ old('retencao_iss',$p->retencao_iss) ? 'checked' : '' }} {{ $lockFiscal?'disabled':'' }}>
                        </div>

                        {{-- Contábil --}}
                        <div class="col-md-6 form-group">
                            <label>Conta Contábil</label>
                            <input name="conta_contabil" class="form-control" value="{{ old('conta_contabil',$p->conta_contabil) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Natureza</label>
                            <input name="natureza" class="form-control" value="{{ old('natureza',$p->natureza) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Centro Custo Padrão</label>
                            <input name="centro_custo_padrao" class="form-control" value="{{ old('centro_custo_padrao',$p->centro_custo_padrao) }}" {{ $lockFiscal?'disabled':'' }}>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" {{ $lockFiscal?'disabled':'' }}>
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
