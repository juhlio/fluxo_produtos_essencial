@extends('adminlte::page')
@section('title','Editar usuário')
@section('content_header') <h1>Editar usuário #{{ $user->id }}</h1> @stop
@section('content')
<form action="{{ route('admin.users.update',$user) }}" method="post" class="card">
  @method('PUT')
  <div class="card-body">@include('admin.users._form')</div>
  <div class="card-footer d-flex justify-content-between">
    <a href="{{ route('admin.users.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Voltar</a>
    <button class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
  </div>
</form>
@stop
