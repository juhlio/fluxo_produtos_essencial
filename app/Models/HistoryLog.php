<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
    protected $fillable = ['product_request_id','actor_id','action','from_status','to_status','message'];
    public function request(){ return $this->belongsTo(ProductRequest::class,'product_request_id'); }
    public function actor(){ return $this->belongsTo(User::class,'actor_id'); }
}
