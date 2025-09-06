<!doctype html>
<html>
<head><meta charset="utf-8"><title>Solicitação #{{ $pr->id }}</title></head>
<body>
    <h1>Solicitação #{{ $pr->id }}</h1>
    <p>Status: <b>{{ $pr->status }}</b> | Setor: <b>{{ $pr->current_sector }}</b> | ERP: {{ $pr->erp_product_code ?? '-' }}</p>

    <h3>Dados principais</h3>
    @if($pr->preProduct)
        <ul>
            <li>Descrição: {{ $pr->preProduct->descricao }}</li>
            <li>Unidade: {{ $pr->preProduct->unidade }}</li>
            <li>SKU: {{ $pr->preProduct->sku }}</li>
        </ul>
    @endif

    <p>
        <a href="{{ route('requests.edit', $pr->id) }}">Editar</a>
    </p>

    <h3>Ações</h3>
    <form method="post" action="{{ route('requests.enviar', [$pr->id,'ESTOQUE']) }}" style="display:inline">@csrf<button>Enviar p/ Estoque</button></form>
    <form method="post" action="{{ route('requests.enviar', [$pr->id,'FISCAL']) }}" style="display:inline">@csrf<button>Enviar p/ Fiscal</button></form>
    <form method="post" action="{{ route('requests.enviar', [$pr->id,'FINALIZAR']) }}" style="display:inline">@csrf<button>Finalizar (via Estoque)</button></form>
    <form method="post" action="{{ route('requests.devolver', $pr->id) }}" style="display:inline">@csrf<button>Devolver</button></form>

    <h3>Histórico</h3>
    <ul>
        @foreach($pr->history as $h)
            <li>[{{ $h->created_at }}] {{ $h->action }} ({{ $h->from_status }} → {{ $h->to_status }}) {{ $h->message }}</li>
        @endforeach
    </ul>

    <p><a href="{{ route('requests.index') }}">← voltar</a></p>
</body>
</html>
