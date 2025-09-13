<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreProductFiscal extends Model
{
    protected $table = 'pre_product_fiscal';
    public $timestamps = true;

    // Evita problemas de mass-assignment quando a lista de campos mudar
    protected $guarded = [];

    // Casts úteis
    protected $casts = [
        'tem_st'       => 'boolean',
        'retencao_iss' => 'boolean',
    ];

    /**
     * No banco é enum('S','N'); aqui tratamos como boolean de forma transparente.
     */
    public function setCalculaInssAttribute($value): void
    {
        $this->attributes['calcula_inss'] =
            ($value === 'S' || $value === 1 || $value === '1' || $value === true) ? 'S' : 'N';
    }

    public function getCalculaInssAttribute($value): bool
    {
        return $value === 'S';
    }

    public function preProduct()
    {
        return $this->belongsTo(PreProduct::class, 'pre_product_id');
    }
}
