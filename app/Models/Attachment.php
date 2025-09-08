<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['product_request_id','original_name','path','type','uploaded_by_id'];
    public function request(){ return $this->belongsTo(ProductRequest::class,'product_request_id'); }
    public function uploader(){ return $this->belongsTo(User::class,'uploaded_by_id'); }
}
