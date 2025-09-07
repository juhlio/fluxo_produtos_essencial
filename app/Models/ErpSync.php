<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ErpSync extends Model
{
    protected $fillable = ['product_request_id','type','payload','response','status','retries','last_error'];
    public function request(){ return $this->belongsTo(ProductRequest::class,'product_request_id'); }
}
