@extends('adminlte::page')
@section('title','Usuários')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Toastr', true)

@section('content_header')
  <h1>Usuários</h1>
@stop

@section('content')

  <div class="d-flex gap-2 mb-3">
    <input id="globalSearch" class="form-control" placeholder="Buscar nome ou e-mail">
    <select id="roleFilter" class="form-select" style="max-width: 220px">
      <option value="">Todos os papéis</option>
      @foreach($roles as $r)
        <option value="{{ $r }}">{{ ucfirst($r) }}</option>
      @endforeach
    </select>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary ms-auto">
      <i class="fas fa-user-plus"></i> Novo usuário
    </a>
  </div>

  <div class="card">
    <div class="card-body table-responsive p-0">
      <table id="tblUsers" class="table table-hover mb-0">
        <thead>
          <tr>
            <th>ID</th><th>Nome</th><th>Email</th><th>Papéis</th><th style="width:130px">Ações</th>
          </tr>
        </thead>
        <tbody>
        @foreach($users as $u)
          <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->roles->pluck('name')->implode(', ') ?: '-' }}</td>
            <td>
              <a class="btn btn-xs btn-outline-primary" href="{{ route('admin.users.edit',$u) }}"><i class="fas fa-edit"></i></a>
              <form action="{{ route('admin.users.destroy',$u) }}" method="post" style="display:inline">
                @csrf @method('DELETE')
                <button class="btn btn-xs btn-outline-danger btn-del"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@stop

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const dt = $('#tblUsers').DataTable({
    pageLength: 10,
    order: [[0,'desc']],
    responsive: true,
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json' }
  });

  // Busca global
  $('#globalSearch').on('keyup change', function(){ dt.search(this.value).draw(); });

  // Filtro por papel (coluna 3 = Papéis)
  $('#roleFilter').on('change', function(){
    dt.column(3).search(this.value).draw();
  });

  // Confirmação de exclusão
  document.querySelectorAll('.btn-del').forEach(btn => {
    btn.addEventListener('click', function(e){
      if(!confirm('Excluir este usuário?')) e.preventDefault();
    });
  });

  @if(session('ok')) toastr.success(@json(session('ok'))); @endif
  @if(session('error')) toastr.error(@json(session('error'))); @endif
});
</script>
@endpush
