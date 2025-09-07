<?php

namespace App\Services;

use App\Models\ProductRequest;
use App\Models\HistoryLog;
use App\Jobs\CreateProductInErp;
use App\Jobs\UpdateFiscalInErp;
use Illuminate\Support\Facades\Auth;

class RequestWorkflowService
{
    public function log(ProductRequest $pr, string $action, ?string $from, ?string $to, ?string $msg=null): void {
        HistoryLog::create([
            'product_request_id' => $pr->id,
            'actor_id' => Auth::id() ?? 1,
            'action' => $action,
            'from_status' => $from,
            'to_status' => $to,
            'message' => $msg,
        ]);
    }

    public function enviar(ProductRequest $pr, string $proximo, ?string $mensagem=null): void {
        $proximo = strtoupper($proximo);
        $from = $pr->status;

        if ($proximo === 'ESTOQUE') {
            $pr->update(['status' => 'EM_ANALISE_ESTOQUE', 'current_sector' => 'ESTOQUE']);
        } elseif (in_array($proximo, ['FISCAL','CONTABIL'])) {
            // Estoque concluiu -> criar produto no ERP
            dispatch(new CreateProductInErp($pr->id));
            $pr->update(['status' => 'EM_ANALISE_FISCAL', 'current_sector' => 'FISCAL']);
        } elseif ($proximo === 'FINALIZAR') {
            // Fiscal/Contábil concluiu -> atualizar fiscais no ERP
            dispatch(new UpdateFiscalInErp($pr->id));
            $pr->update(['status' => 'EM_FINALIZACAO_ESTOQUE', 'current_sector' => 'ESTOQUE']);
        }

        $this->log($pr, "ENVIAR_PARA_{$proximo}", $from, $pr->status, $mensagem);
    }

    public function devolver(ProductRequest $pr, ?string $mensagem=null): void {
        $from = $pr->status;
        $to = ($pr->current_sector === 'FISCAL' || $pr->current_sector === 'CONTABIL')
            ? 'EM_ANALISE_ESTOQUE'
            : 'RASCUNHO';
        $sector = $to === 'RASCUNHO' ? 'SOLICITANTE' : 'ESTOQUE';

        $pr->update(['status' => $to, 'current_sector' => $sector]);
        $this->log($pr, "DEVOLVER", $from, $to, $mensagem);
    }

    public function finalizar(ProductRequest $pr): void {
        $from = $pr->status;
        $pr->update(['status' => 'FINALIZADA', 'current_sector' => 'ESTOQUE']);
        $this->log($pr, "FINALIZAR", $from, 'FINALIZADA', 'Conciliação básica OK');
    }
}
