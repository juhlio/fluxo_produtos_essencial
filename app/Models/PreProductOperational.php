<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreProductOperational extends Model
{
    protected $table = 'pre_product_operational';
    protected $fillable = [
        'pre_product_id','armazem_padrao','grupo','seg_un_medi','te_padrao','ts_padrao',
        'fator_conv','tipo_conv','alternativo','base_estrut','fornecedor_padrao','loja_padrao','rastro'
    ];
    public $timestamps = true;

    public function preProduct(){ return $this->belongsTo(PreProduct::class); }
}
