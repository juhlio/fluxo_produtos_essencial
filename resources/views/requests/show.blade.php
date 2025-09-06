@extends('adminlte::page')

@section('title', "Solicitação #{$pr->id}")

@section('content_header')
    <h1>Solicitação #{{ $pr->id }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <p>Status:
                    <span class="badge badge-info">{{ $pr->status }}</span>
                    &nbsp;|&nbsp; Setor: <b>{{ $pr->current_sector }}</b>
                    &nbsp;|&nbsp; ERP: <b>{{ $pr->erp_product_code ?? '-' }}</b>
                </p>
                @if($pr->preProduct)
                <dl class="row">
                    <dt class="col-sm-3">Descrição</dt><dd class="col-sm-9">{{ $pr->preProduct->descricao }}</dd>
                    <dt class="col-sm-3">Unidade</dt><dd class="col-sm-9">{{ $pr->preProduct->unidade }}</dd>
                    <dt class="col-sm-3">SKU</dt><dd class="col-sm-9">{{ $pr->preProduct->sku }}</dd>
                </dl>
                @endif
                <a class="btn btn-outline-primary" href="{{ route('requests.edit', $pr->id) }}">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><b>Histórico</b></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($pr->history as $h)
                        <li class="list-group-item">
                            <small class="text-muted">[{{ $h->created_at }}]</small>
                            <b>{{ $h->action }}</b>
                            <span class="text-muted">{{ $h->from_status }} → {{ $h->to_status }}</span>
                            @if($h->message) — {{ $h->message }} @endif
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">Sem histórico</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

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
                <form method="post" action="{{ route('requests.enviar', [$pr->id,'FINALIZAR']) }}" class="mb-2">@csrf
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
