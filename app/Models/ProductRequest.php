<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    protected $table = 'requests';
    public $timestamps = true;

    protected $fillable = [
        // Cabeçalho da solicitação
        'requested_by_id',
        'status',
        'current_sector',

        // Campos do formulário (básico + fiscais/contábeis)
        'descricao','unidade','marca','modelo','sku','familia','peso','dimensoes',
        'codigo','tipo','armazem_padrao','grupo','seg_un_medi','te_padrao','ts_padrao',
        'fator_conv','tipo_conv','alternativo','preco_venda','custo_stand','moeda_cstd',
        'ult_calculo','ult_preco','ult_compra','peso_liquido','cta_contabil','centro_custo',
        'item_conta','base_estrut','fornecedor_padrao','loja_padrao','rastro','ult_revisao',
        'dt_referenc','apropriacao','fora_estado','fantasma','perc_comissao','cod_barras',
        'aliq_icms','aliq_ipi','aliq_iss','pos_ipi_ncm','ex_nbm','ex_ncm','especie_tipi',
        'cod_serv_iss','origem','class_fiscal','grupo_trib','cont_seg_soc','imposto_renda',
        'calcula_inss','red_inss','red_irrf','red_pis','red_cofins','perc_pis','perc_cofins',
        'perc_csll','proprio_icms','icms_pauta','ipi_pauta','aliq_famad','aliq_fecp',
        'solid_saida','solid_entrada','imp_zfranca',
    ];

    protected $casts = [
        'peso'            => 'decimal:4',
        'peso_liquido'    => 'decimal:4',
        'fator_conv'      => 'decimal:4',
        'preco_venda'     => 'decimal:2',
        'custo_stand'     => 'decimal:4',
        'ult_preco'       => 'decimal:2',
        'perc_comissao'   => 'decimal:2',

        'aliq_icms' => 'decimal:2', 'aliq_ipi' => 'decimal:2', 'aliq_iss' => 'decimal:2',
        'red_inss'  => 'decimal:2', 'red_irrf' => 'decimal:2', 'red_pis'  => 'decimal:2',
        'red_cofins'=> 'decimal:2', 'perc_pis' => 'decimal:2', 'perc_cofins' => 'decimal:2',
        'perc_csll' => 'decimal:2', 'proprio_icms' => 'decimal:2',
        'icms_pauta'=> 'decimal:2', 'ipi_pauta' => 'decimal:2',
        'aliq_famad'=> 'decimal:2', 'aliq_fecp'  => 'decimal:2',
        'solid_saida'=> 'decimal:2','solid_entrada'=>'decimal:2','imp_zfranca'=>'decimal:2',

        'ult_calculo' => 'date',
        'ult_compra'  => 'date',
        'ult_revisao' => 'date',
        'dt_referenc' => 'date',
    ];

    public function preProduct()
    {
        return $this->hasOne(PreProduct::class, 'product_request_id', 'id');
    }
}
