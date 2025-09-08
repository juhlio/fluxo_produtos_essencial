@extends('adminlte::page')

@section('title', "Editar Usuário #{$user->id}")

@section('content_header')
  <h1>Editar Usuário #{{ $user->id }}</h1>
@stop

@section('content')
  <form method="POST" action="{{ route('admin.users.update', $user) }}" class="card">
    @method('PUT')
    <div class="card-body">
      @include('admin.users._form', ['user' => $user, 'roles' => $roles])
    </div>
    <div class="card-footer d-flex justify-content-between">
      <a href="{{ route('admin.users.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Voltar</a>
      <button class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
    </div>
  </form>
@stop
