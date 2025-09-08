<?php

namespace App\Jobs;

use App\Models\ErpSync;
use App\Models\ProductRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateProductInErp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $productRequestId) {}

    public function handle(): void
    {
        $pr = ProductRequest::find($this->productRequestId);
        if (!$pr) return;

        // Simulação: define um código ERP e registra sync OK
        $pr->update(['erp_product_code' => 'ERP-' . str_pad((string)$pr->id, 6, '0', STR_PAD_LEFT)]);

        ErpSync::create([
            'product_request_id' => $pr->id,
            'type' => 'create_product',
            'payload' => json_encode(['id'=>$pr->id]),
            'response' => json_encode(['code'=>$pr->erp_product_code]),
            'status' => 'OK',
        ]);
    }
}
