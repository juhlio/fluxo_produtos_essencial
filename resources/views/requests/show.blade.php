@extends('adminlte::page')

@section('plugins.Toastr', true)
@section('plugins.Sweetalert2', true)

@section('title', "Solicitação #{$pr->id}")

@section('content_header')
  <h1>Solicitação #{{ $pr->id }}</h1>
@stop

@section('content')
<div class="row">
  <div class="col-md-8">
    <!-- CABEÇALHO -->
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          Status:
          <span class="badge badge-info">{{ $pr->status ?? 'RASCUNHO' }}</span>
          &nbsp;|&nbsp; Setor: <b>{{ $pr->current_sector ?? 'SOLICITANTE' }}</b>
          &nbsp;|&nbsp; ERP: <b>{{ $pr->erp_product_code ?? '-' }}</b>
        </p>

        {{-- ESTOQUE / BÁSICO --}}
        @php($basic = $pr->preProduct?->basic)
        <h5 class="mb-2">Estoque</h5>
        <dl class="row mb-3">
          <dt class="col-sm-3">Descrição</dt><dd class="col-sm-9">{{ $basic?->descricao ?? '-' }}</dd>
          <dt class="col-sm-3">Unidade</dt><dd class="col-sm-9">{{ $basic?->unidade ?? '-' }}</dd>
          <dt class="col-sm-3">SKU</dt><dd class="col-sm-9">{{ $basic?->sku ?? '-' }}</dd>
          <dt class="col-sm-3">Marca</dt><dd class="col-sm-9">{{ $basic?->marca ?? '-' }}</dd>
          <dt class="col-sm-3">Modelo</dt><dd class="col-sm-9">{{ $basic?->modelo ?? '-' }}</dd>
          <dt class="col-sm-3">Família</dt><dd class="col-sm-9">{{ $basic?->familia ?? '-' }}</dd>
          <dt class="col-sm-3">Peso</dt><dd class="col-sm-9">{{ $basic?->peso ?? '-' }}</dd>
          <dt class="col-sm-3">Dimensões</dt><dd class="col-sm-9">{{ $basic?->dimensoes ?? '-' }}</dd>
          <dt class="col-sm-3">Código</dt><dd class="col-sm-9">{{ $basic?->codigo ?? '-' }}</dd>
          <dt class="col-sm-3">Tipo</dt><dd class="col-sm-9">{{ $basic?->tipo ?? '-' }}</dd>
        </dl>

        {{-- FISCAL (resumo) --}}
        @php($fiscal = $pr->preProduct?->fiscal)
        <h5 class="mb-2">Fiscal/Contábil (resumo)</h5>
        <dl class="row mb-0">
          <dt class="col-sm-3">NCM</dt><dd class="col-sm-9">{{ $fiscal?->ncm ?? '-' }}</dd>
          <dt class="col-sm-3">Origem</dt><dd class="col-sm-9">{{ $fiscal?->origem ?? '-' }}</dd>
          <dt class="col-sm-3">CFOP Entrada</dt><dd class="col-sm-9">{{ $fiscal?->cfop_entrada ?? '-' }}</dd>
          <dt class="col-sm-3">CFOP Saída</dt><dd class="col-sm-9">{{ $fiscal?->cfop_saida ?? '-' }}</dd>
        </dl>

        <a class="btn btn-outline-primary mt-3" href="{{ route('requests.edit', $pr->id) }}">
          <i class="fas fa-edit"></i> Editar
        </a>
      </div>
    </div>

    {{-- HISTÓRICO --}}
    <div class="card">
      <div class="card-header"><b>Histórico</b></div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          @forelse((array)($pr->history ?? []) as $h)
            <li class="list-group-item">
              <small class="text-muted">[{{ $h->created_at ?? '-' }}]</small>
              <b>{{ $h->action ?? '-' }}</b>
              <span class="text-muted">{{ $h->from_status ?? '-' }} → {{ $h->to_status ?? '-' }}</span>
              @if(!empty($h->message)) — {{ $h->message }} @endif
            </li>
          @empty
            <li class="list-group-item text-center text-muted">Sem histórico</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  {{-- AÇÕES --}}
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><b>Ações</b></div>
      <div class="card-body">
        <form method="post" action="{{ route('requests.enviar', [$pr->id,'ESTOQUE']) }}" class="mb-2">@csrf
          <button class="btn btn-block btn-secondary"><i class="fas fa-share"></i> Enviar p/ Estoque</button>
        </form>
        <form method="post" action="{{ route('requests.enviar', [$pr->id,'FISCAL']) }}" class="mb-2">@csrf
          <button class="btn btn-block btn-info"><i class="fas fa-file-invoice-dollar"></i> Enviar p/ Fiscal</button>
        </form>
        <form id="form-finalizar" method="post" action="{{ route('requests.enviar', [$pr->id,'FINALIZAR']) }}" class="mb-2">@csrf
          <button class="btn btn-block btn-success"><i class="fas fa-check"></i> Finalizar</button>
        </form>
        <form method="post" action="{{ route('requests.devolver', $pr->id) }}">@csrf
          <button class="btn btn-block btn-warning"><i class="fas fa-undo"></i> Devolver</button>
        </form>
      </div>
    </div>
  </div>
</div>
@stop

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const formFinalizar = document.getElementById('form-finalizar');
  if (formFinalizar) {
    formFinalizar.addEventListener('submit', function (e) {
      e.preventDefault();
      Swal.fire({title:'Finalizar solicitação?',text:'Essa ação registrará a conclusão do fluxo.',icon:'question',showCancelButton:true,confirmButtonText:'Sim, finalizar',cancelButtonText:'Cancelar'})
        .then((res)=>{ if(res.isConfirmed) formFinalizar.submit(); });
    });
  }
  @if(session('ok'))
  <script>
    toastr.success(@json(session('ok')), "OK");
  </script>
  @endif
});
</script>
@endpush
