<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreProductBasics extends Model
{
    protected $table = 'pre_product_basics';
    protected $fillable = [
        'pre_product_id','descricao','unidade','marca','modelo','sku','familia','peso',
        'dimensoes','codigo','tipo'
    ];
    public $timestamps = true;

    public function preProduct(){ return $this->belongsTo(PreProduct::class); }
}
