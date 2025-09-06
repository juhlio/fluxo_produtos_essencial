@extends('adminlte::page')

@section('title', 'Solicitações')

@section('content_header')
    <h1>Solicitações</h1>
@stop

@section('content')
    @if(session('ok')) <x-adminlte-alert theme="success" title="OK" dismissable>{{ session('ok') }}</x-adminlte-alert> @endif

    <a href="{{ route('requests.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Nova solicitação
    </a>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th><th>Status</th><th>Setor</th><th>ERP</th><th style="width:120px">Ações</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($items as $it)
                    <tr>
                        <td>#{{ $it->id }}</td>
                        <td><span class="badge badge-info">{{ $it->status }}</span></td>
                        <td>{{ $it->current_sector }}</td>
                        <td>{{ $it->erp_product_code ?? '-' }}</td>
                        <td>
                            <a class="btn btn-xs btn-outline-primary" href="{{ route('requests.show', $it->id) }}">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center p-4">Nenhuma solicitação ainda.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $items->links() }}</div>
    </div>
@stop
