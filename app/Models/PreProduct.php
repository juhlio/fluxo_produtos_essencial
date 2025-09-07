<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreProduct extends Model
{
    protected $fillable = [
        'product_request_id','descricao','unidade','marca','modelo','sku','familia','peso','dimensoes',
        'conta_contabil','natureza','centro_custo_padrao',
        'ncm','cest','origem','cfop_entrada','cfop_saida','cst_icms','csosn','aliq_icms','aliq_ipi',
        'cst_pis','aliq_pis','cst_cofins','aliq_cofins','tem_st','mva_st',
        'cod_servico_municipal','aliq_iss','retencao_iss','fiscal_rules'
    ];

    protected $casts = [
        'tem_st'        => 'bool',
        'retencao_iss'  => 'bool',
        'fiscal_rules'  => 'array',
        'peso'          => 'decimal:3',
        'aliq_icms'     => 'decimal:2',
        'aliq_ipi'      => 'decimal:2',
        'aliq_pis'      => 'decimal:2',
        'aliq_cofins'   => 'decimal:2',
        'aliq_iss'      => 'decimal:2',
        'mva_st'        => 'decimal:2',
    ];

    public function request()
    {
        return $this->belongsTo(ProductRequest::class, 'product_request_id');
    }
}
