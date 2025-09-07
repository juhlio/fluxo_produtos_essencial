<?php

namespace App\Jobs;

use App\Models\ErpSync;
use App\Models\ProductRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateFiscalInErp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $productRequestId) {}

    public function handle(): void
    {
        $pr = ProductRequest::find($this->productRequestId);
        if (!$pr) return;

        ErpSync::create([
            'product_request_id' => $pr->id,
            'type' => 'update_fiscal',
            'payload' => json_encode(['pre_product_id'=>optional($pr->preProduct)->id]),
            'response' => json_encode(['ok'=>true]),
            'status' => 'OK',
        ]);
    }
}
