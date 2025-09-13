<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreProductPricing extends Model
{
    protected $table = 'pre_product_pricing';
    protected $fillable = [
        'pre_product_id','preco_venda','custo_stand','moeda_cstd','ult_calculo','ult_preco','ult_compra',
        'peso_liquido','ult_revisao','dt_referenc','apropriacao','cta_contabil','centro_custo','item_conta',
        'perc_comissao','cod_barras'
    ];
    public $timestamps = true;

    public function preProduct(){ return $this->belongsTo(PreProduct::class); }
}
