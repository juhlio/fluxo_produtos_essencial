<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    protected $fillable = ['product_request_id','sector','key','checked_by_id','checked_at'];
    protected $casts = ['checked_at'=>'datetime'];
    public function request(){ return $this->belongsTo(ProductRequest::class,'product_request_id'); }
    public function checker(){ return $this->belongsTo(User::class,'checked_by_id'); }
}
