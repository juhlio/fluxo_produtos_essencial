@extends('adminlte::page')

@section('title', 'Novo Usuário')

@section('content_header')
  <h1>Novo Usuário</h1>
@stop

@section('content')
  <form method="POST" action="{{ route('admin.users.store') }}" class="card">
    <div class="card-body">
      @include('admin.users._form', ['user' => $user, 'roles' => $roles])
    </div>
    <div class="card-footer d-flex justify-content-between">
      <a href="{{ route('admin.users.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Voltar</a>
      <button class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
    </div>
  </form>
@stop
