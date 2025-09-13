<?php

namespace App\Services;

use App\Models\ProductRequest;
use Illuminate\Support\Facades\Log;

class RequestWorkflowService
{
    public function log(ProductRequest $pr, string $acao, ?string $statusDe, ?string $statusPara, string $mensagem = null): void
    {
        Log::info('WORKFLOW', [
            'request_id' => $pr->id,
            'acao'       => $acao,
            'de'         => $statusDe,
            'para'       => $statusPara,
            'msg'        => $mensagem,
        ]);
    }

    public function enviar(ProductRequest $pr, string $proximo, ?string $mensagem = null): void
    {
        $statusAnterior = $pr->status;
        $pr->current_sector = $proximo;
        $pr->status = 'EM_ANDAMENTO';
        $pr->save();

        $this->log($pr, 'ENVIAR', $statusAnterior, $pr->status, $mensagem ?? 'Enviado para ' . $proximo);
    }

    public function devolver(ProductRequest $pr, ?string $mensagem = null): void
    {
        $statusAnterior = $pr->status;
        $pr->status = 'DEVOLVIDO';
        $pr->save();

        $this->log($pr, 'DEVOLVER', $statusAnterior, $pr->status, $mensagem ?? 'Devolvido');
    }

    public function finalizar(ProductRequest $pr): void
    {
        $statusAnterior = $pr->status;
        $pr->status = 'FINALIZADO';
        $pr->save();

        $this->log($pr, 'FINALIZAR', $statusAnterior, $pr->status, 'Finalizado');
    }
}
