<!doctype html>
<html>
<head><meta charset="utf-8"><title>Solicitações</title></head>
<body>
    <h1>Solicitações</h1>
    <p><a href="{{ route('requests.create') }}">+ Nova solicitação</a></p>
    <table border="1" cellpadding="6">
        <tr><th>ID</th><th>Status</th><th>Setor</th><th>ERP</th><th>Ações</th></tr>
        @foreach($items as $it)
            <tr>
                <td>{{ $it->id }}</td>
                <td>{{ $it->status }}</td>
                <td>{{ $it->current_sector }}</td>
                <td>{{ $it->erp_product_code ?? '-' }}</td>
                <td><a href="{{ route('requests.show', $it->id) }}">abrir</a></td>
            </tr>
        @endforeach
    </table>
    {{ $items->links() }}
</body>
</html>
