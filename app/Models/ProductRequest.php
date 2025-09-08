<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    protected $fillable = [
        'requested_by_id', 'status', 'current_sector', 'erp_product_code',
    ];

    public function requester(){ return $this->belongsTo(User::class, 'requested_by_id'); }
    public function preProduct(){ return $this->hasOne(PreProduct::class, 'product_request_id'); }
    public function history(){ return $this->hasMany(HistoryLog::class, 'product_request_id'); }
    public function attachments(){ return $this->hasMany(Attachment::class, 'product_request_id'); }
    public function erpSyncs(){ return $this->hasMany(ErpSync::class, 'product_request_id'); }
}
