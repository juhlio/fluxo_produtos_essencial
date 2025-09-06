@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)  {{-- Buttons/Responsive --}}
@section('plugins.Toastr', true)
@section('plugins.Sweetalert2', true)

@section('title', "Editar #{$pr->id}")

@section('content_header')
    <h1>Editar Solicitação #{{ $pr->id }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <form method="post" action="{{ route('requests.update.estoque', $pr->id) }}" class="card">
            @csrf @method('PUT')
            <div class="card-header"><b>Estoque</b></div>
            <div class="card-body">
                <div class="form-group"><label>SKU</label>
                    <input name="sku" class="form-control" value="{{ old('sku', $pr->preProduct->sku) }}">
                </div>
                <div class="form-group"><label>Peso</label>
                    <input name="peso" type="number" step="0.001" class="form-control" value="{{ old('peso', $pr->preProduct->peso) }}">
                </div>
                <div class="form-group"><label>Dimensões</label>
                    <input name="dimensoes" class="form-control" value="{{ old('dimensoes', $pr->preProduct->dimensoes) }}">
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
            </div>
        </form>
    </div>

    <div class="col-md-6">
        <form method="post" action="{{ route('requests.update.fiscal', $pr->id) }}" class="card">
            @csrf @method('PUT')
            <div class="card-header"><b>Fiscal/Contábil</b></div>
            <div class="card-body">
                <div class="form-group"><label>NCM*</label>
                    <input name="ncm" class="form-control" value="{{ old('ncm', $pr->preProduct->ncm) }}">
                </div>
                <div class="form-group"><label>Origem*</label>
                    <select name="origem" class="form-control">
                        @foreach(range(0,8) as $o)
                            <option value="{{ $o }}" @selected(old('origem', $pr->preProduct->origem)==(string)$o)>{{ $o }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label>CFOP Saída</label>
                    <input name="cfop_saida" class="form-control" value="{{ old('cfop_saida', $pr->preProduct->cfop_saida) }}">
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@stop
