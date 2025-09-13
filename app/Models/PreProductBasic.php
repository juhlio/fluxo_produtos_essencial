<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreProductBasic extends Model
{
    protected $table = 'pre_product_basics';
    public $timestamps = true;

    protected $fillable = [
        'pre_product_id',
        'descricao',
        'unidade',
        'sku',
        'marca',
        'modelo',
        'familia',
        'peso',
        'dimensoes',
        'codigo',
        'tipo',
    ];

    protected $casts = [
        'peso' => 'decimal:4',
    ];

    public function preProduct()
    {
        return $this->belongsTo(PreProduct::class, 'pre_product_id');
    }
}
