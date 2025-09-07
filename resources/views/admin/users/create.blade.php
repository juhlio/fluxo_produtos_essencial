@extends('adminlte::page')
@section('title','Novo usuário')
@section('content_header') <h1>Novo usuário</h1> @stop
@section('content')
<form action="{{ route('admin.users.store') }}" method="post" class="card">
  <div class="card-body">@include('admin.users._form')</div>
  <div class="card-footer d-flex justify-content-between">
    <a href="{{ route('admin.users.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Voltar</a>
    <button class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
  </div>
</form>
@stop
