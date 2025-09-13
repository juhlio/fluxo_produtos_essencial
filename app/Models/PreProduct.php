<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreProduct extends Model
{
    protected $table = 'pre_products';
    public $timestamps = true;

    protected $fillable = [
        'product_request_id',
        'descricao',
        'unidade',
        'marca',
        'modelo',
        'sku',
        'familia',
        'peso',
        'dimensoes',
        'ncm',
        'cest',
        'origem',
        'cfop_entrada',
        'cfop_saida',
        'cst_icms',
        'csosn',
        'aliq_icms',
        'aliq_ipi',
        'cst_pis',
        'aliq_pis',
        'cst_cofins',
        'aliq_cofins',
        'tem_st',
        'mva_st',
        'cod_servico_municipal',
        'aliq_iss',
        'retencao_iss',
        'conta_contabil',
        'natureza',
        'centro_custo_padrao',
        'fiscal_rules',
    ];

    protected $casts = [
        'peso' => 'decimal:4',
        'tem_st' => 'boolean',
        'retencao_iss' => 'boolean',
        'aliq_icms' => 'decimal:2',
        'aliq_ipi' => 'decimal:2',
        'aliq_pis' => 'decimal:2',
        'aliq_cofins' => 'decimal:2',
        'mva_st' => 'decimal:2',
        'aliq_iss' => 'decimal:2',
        'fiscal_rules' => 'array',
    ];

    public function productRequest()
    {
        return $this->belongsTo(ProductRequest::class, 'product_request_id', 'id');
    }

    public function basic()
    {
        return $this->hasOne(\App\Models\PreProductBasic::class, 'pre_product_id');
    }

    // alias para compatibilidade se alguém usar 'basics'
    public function basics()
    {
        return $this->hasOne(\App\Models\PreProductBasic::class, 'pre_product_id');
    }

    public function operational()
    {
        return $this->hasOne(\App\Models\PreProductOperational::class, 'pre_product_id');
    }

    // alias caso em algum lugar use 'operationals'
    public function operationals()
    {
        return $this->hasOne(\App\Models\PreProductOperational::class, 'pre_product_id');
    }

    public function fiscal()
    {
        return $this->hasOne(\App\Models\PreProductFiscal::class, 'pre_product_id');
    }

    public function pricing()
    {
        return $this->hasOne(\App\Models\PreProductPricing::class, 'pre_product_id');
    }
}
