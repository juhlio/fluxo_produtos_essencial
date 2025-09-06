<!doctype html>
<html>
<head><meta charset="utf-8"><title>Nova Solicitação</title></head>
<body>
    <h1>Nova Solicitação</h1>
    <form method="post" action="{{ route('requests.store') }}">
        @csrf
        <label>Descrição*: <input name="descricao" required></label><br>
        <label>Unidade: <input name="unidade"></label><br>
        <label>Marca: <input name="marca"></label><br>
        <label>Modelo: <input name="modelo"></label><br>
        <label>SKU: <input name="sku"></label><br>
        <label>Família: <input name="familia"></label><br>
        <label>Peso: <input name="peso" type="number" step="0.001"></label><br>
        <label>Dimensões: <input name="dimensoes"></label><br>
        <button type="submit">Salvar</button>
    </form>
    <p><a href="{{ route('requests.index') }}">← voltar</a></p>
</body>
</html>
