<!doctype html>
<html>
<head><meta charset="utf-8"><title>Editar Solicitação #{{ $pr->id }}</title></head>
<body>
    <h1>Editar Solicitação #{{ $pr->id }}</h1>

    <h3>Estoque</h3>
    <form method="post" action="{{ route('requests.update.estoque', $pr->id) }}">
        @csrf @method('PUT')
        <label>SKU: <input name="sku" value="{{ old('sku', $pr->preProduct->sku) }}"></label><br>
        <label>Peso: <input name="peso" type="number" step="0.001" value="{{ old('peso', $pr->preProduct->peso) }}"></label><br>
        <label>Dimensões: <input name="dimensoes" value="{{ old('dimensoes', $pr->preProduct->dimensoes) }}"></label><br>
        <button type="submit">Salvar Estoque</button>
    </form>

    <h3>Fiscal/Contábil</h3>
    <form method="post" action="{{ route('requests.update.fiscal', $pr->id) }}">
        @csrf @method('PUT')
        <label>NCM*: <input name="ncm" value="{{ old('ncm', $pr->preProduct->ncm) }}"></label><br>
        <label>Origem*:
            <select name="origem">
                @foreach(range(0,8) as $o)
                    <option value="{{ $o }}" @selected(old('origem', $pr->preProduct->origem)==(string)$o)>{{ $o }}</option>
                @endforeach
            </select>
        </label><br>
        <label>CFOP saída: <input name="cfop_saida" value="{{ old('cfop_saida', $pr->preProduct->cfop_saida) }}"></label><br>
        <button type="submit">Salvar Fiscal</button>
    </form>

    <p><a href="{{ route('requests.show', $pr->id) }}">← voltar</a></p>
</body>
</html>
