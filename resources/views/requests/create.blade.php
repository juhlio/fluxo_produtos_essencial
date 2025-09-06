@extends('adminlte::page')

@section('title', 'Nova Solicitação')

@section('content_header')
    <h1>Nova Solicitação</h1>
@stop

@section('content')
    <form method="post" action="{{ route('requests.store') }}" class="card">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Descrição*</label>
                <input name="descricao" class="form-control" required value="{{ old('descricao') }}">
                @error('descricao') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Unidade</label>
                    <input name="unidade" class="form-control" value="{{ old('unidade') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>Marca</label>
                    <input name="marca" class="form-control" value="{{ old('marca') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>Modelo</label>
                    <input name="modelo" class="form-control" value="{{ old('modelo') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>SKU</label>
                    <input name="sku" class="form-control" value="{{ old('sku') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Família</label>
                    <input name="familia" class="form-control" value="{{ old('familia') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Peso</label>
                    <input name="peso" type="number" step="0.001" class="form-control" value="{{ old('peso') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Dimensões</label>
                    <input name="dimensoes" class="form-control" value="{{ old('dimensoes') }}">
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('requests.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Voltar</a>
            <button class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        </div>
    </form>
@stop
