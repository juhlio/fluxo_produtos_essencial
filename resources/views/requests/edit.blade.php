@extends('adminlte::page')

@php($pr = $pr ?? $item ?? $requestItem ?? null)

@section('plugins.Toastr', true)
@section('title', $pr ? "Editar Solicitação #{$pr->id}" : "Editar Solicitação")


@section('content_header')
  <h1>Editar Solicitação #{{ $pr->id }}</h1>
@stop

@section('content')
<form method="post" action="{{ route('requests.update', $pr->id) }}">
  @csrf
  @method('PUT')

  @php($basic  = $pr->preProduct?->basic)
  @php($fiscal = $pr->preProduct?->fiscal)

  <div class="row">
    {{-- ESTOQUE --}}
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><b>Estoque</b></div>
        <div class="card-body">

          <div class="form-group">
            <label>Descrição</label>
            <input name="basic[descricao]" class="form-control"
              value="{{ old('basic.descricao', $basic?->descricao) }}">
            @error('basic.descricao')<small class="text-danger">{{ $message }}</small>@enderror
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Unidade</label>
              <input name="basic[unidade]" class="form-control"
                value="{{ old('basic.unidade', $basic?->unidade) }}">
            </div>
            <div class="form-group col-md-6">
              <label>SKU</label>
              <input name="basic[sku]" class="form-control"
                value="{{ old('basic.sku', $basic?->sku) }}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Marca</label>
              <input name="basic[marca]" class="form-control"
                value="{{ old('basic.marca', $basic?->marca) }}">
            </div>
            <div class="form-group col-md-6">
              <label>Modelo</label>
              <input name="basic[modelo]" class="form-control"
                value="{{ old('basic.modelo', $basic?->modelo) }}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Família</label>
              <input name="basic[familia]" class="form-control"
                value="{{ old('basic.familia', $basic?->familia) }}">
            </div>
            <div class="form-group col-md-6">
              <label>Peso</label>
              <input name="basic[peso]" type="number" step="0.0001" inputmode="decimal" class="form-control"
                value="{{ old('basic.peso', $basic?->peso) }}">
            </div>
          </div>

          <div class="form-group">
            <label>Dimensões</label>
            <input name="basic[dimensoes]" class="form-control" placeholder="CxLxA"
              value="{{ old('basic.dimensoes', $basic?->dimensoes) }}">
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Código</label>
              <input name="basic[codigo]" class="form-control"
                value="{{ old('basic.codigo', $basic?->codigo) }}">
            </div>
            <div class="form-group col-md-6">
              <label>Tipo</label>
              <input name="basic[tipo]" class="form-control"
                value="{{ old('basic.tipo', $basic?->tipo) }}">
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- FISCAL / CONTÁBIL --}}
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><b>Fiscal/Contábil</b></div>
        <div class="card-body">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>NCM*</label>
              <input name="fiscal[ncm]" class="form-control"
                value="{{ old('fiscal.ncm', $fiscal?->ncm) }}">
            </div>
            <div class="form-group col-md-6">
              <label>Origem*</label>
              <select name="fiscal[origem]" class="form-control">
                @php($origem = old('fiscal.origem', $fiscal?->origem))
                @foreach(['0 - Nacional','1 - Estrangeira (imp. direta)','2 - Estrangeira (mercado interno)','3','4','5','6','7','8'] as $opt)
                  <option value="{{ $opt }}" {{ $origem==$opt ? 'selected':'' }}>{{ $opt }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>CFOP Entrada</label>
              <input name="fiscal[cfop_entrada]" class="form-control"
                value="{{ old('fiscal.cfop_entrada', $fiscal?->cfop_entrada) }}">
            </div>
            <div class="form-group col-md-6">
              <label>CFOP Saída</label>
              <input name="fiscal[cfop_saida]" class="form-control"
                value="{{ old('fiscal.cfop_saida', $fiscal?->cfop_saida) }}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>CST ICMS</label>
              <input name="fiscal[cst_icms]" class="form-control"
                value="{{ old('fiscal.cst_icms', $fiscal?->cst_icms) }}">
            </div>
            <div class="form-group col-md-6">
              <label>CSOSN</label>
              <input name="fiscal[csosn]" class="form-control"
                value="{{ old('fiscal.csosn', $fiscal?->csosn) }}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Alíq. ICMS (%)</label>
              <input name="fiscal[aliq_icms]" type="number" step="0.01" inputmode="decimal" class="form-control"
                value="{{ old('fiscal.aliq_icms', $fiscal?->aliq_icms) }}">
            </div>
            <div class="form-group col-md-6">
              <label>Alíq. IPI (%)</label>
              <input name="fiscal[aliq_ipi]" type="number" step="0.01" inputmode="decimal" class="form-control"
                value="{{ old('fiscal.aliq_ipi', $fiscal?->aliq_ipi) }}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>CST PIS</label>
              <input name="fiscal[cst_pis]" class="form-control"
                value="{{ old('fiscal.cst_pis', $fiscal?->cst_pis) }}">
            </div>
            <div class="form-group col-md-6">
              <label>Alíq. PIS (%)</label>
              <input name="fiscal[aliq_pis]" type="number" step="0.01" inputmode="decimal" class="form-control"
                value="{{ old('fiscal.aliq_pis', $fiscal?->aliq_pis) }}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>CST COFINS</label>
              <input name="fiscal[cst_cofins]" class="form-control"
                value="{{ old('fiscal.cst_cofins', $fiscal?->cst_cofins) }}">
            </div>
            <div class="form-group col-md-6">
              <label>Alíq. COFINS (%)</label>
              <input name="fiscal[aliq_cofins]" type="number" step="0.01" inputmode="decimal" class="form-control"
                value="{{ old('fiscal.aliq_cofins', $fiscal?->aliq_cofins) }}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <div class="custom-control custom-switch mt-4">
                <input type="checkbox" class="custom-control-input" id="tem_st" name="fiscal[tem_st]"
                  {{ old('fiscal.tem_st', $fiscal?->tem_st) ? 'checked':'' }}>
                <label class="custom-control-label" for="tem_st">Possui ST</label>
              </div>
            </div>
            <div class="form-group col-md-6">
              <label>MVA ST (%)</label>
              <input name="fiscal[mva_st]" type="number" step="0.01" inputmode="decimal" class="form-control"
                value="{{ old('fiscal.mva_st', $fiscal?->mva_st) }}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Cód. Serviço Municipal</label>
              <input name="fiscal[cod_servico_municipal]" class="form-control"
                value="{{ old('fiscal.cod_servico_municipal', $fiscal?->cod_servico_municipal) }}">
            </div>
            <div class="form-group col-md-6">
              <label>Alíq. ISS (%)</label>
              <input name="fiscal[aliq_iss]" type="number" step="0.01" inputmode="decimal" class="form-control"
                value="{{ old('fiscal.aliq_iss', $fiscal?->aliq_iss) }}">
            </div>
          </div>

          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="retencao_iss" name="fiscal[retencao_iss]"
              {{ old('fiscal.retencao_iss', $fiscal?->retencao_iss) ? 'checked':'' }}>
            <label class="custom-control-label" for="retencao_iss">Retenção de ISS</label>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <button class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
    <a class="btn btn-default" href="{{ route('requests.show', $pr->id) }}">Cancelar</a>
  </div>
</form>
@stop
