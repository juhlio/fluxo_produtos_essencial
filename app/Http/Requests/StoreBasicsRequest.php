<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBasicsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'descricao' => ['required','string','max:255'],

            // básicos livres (você pode apertar depois)
            'unidade' => ['nullable','string','max:10'],
            'marca'   => ['nullable','string','max:100'],
            'modelo'  => ['nullable','string','max:100'],
            'sku'     => ['nullable','string','max:100'],
            'familia' => ['nullable','string','max:100'],
            'peso'    => ['nullable','string'],
            'dimensoes' => ['nullable','string','max:100'],

            // fiscais/contábeis (deixe flexível por enquanto)
            'codigo' => ['nullable','string','max:50'],
            'tipo'   => ['nullable','string','max:10'],
            'armazem_padrao' => ['nullable','string','max:50'],
            'grupo'  => ['nullable','string','max:50'],
            'seg_un_medi' => ['nullable','string','max:10'],
            'te_padrao' => ['nullable','string','max:10'],
            'ts_padrao' => ['nullable','string','max:10'],
            'fator_conv' => ['nullable','string'],
            'tipo_conv'  => ['nullable','in:M,D'],
            'alternativo'=> ['nullable','string','max:100'],
            'preco_venda'=> ['nullable','string'],
            'custo_stand'=> ['nullable','string'],
            'moeda_cstd' => ['nullable','string','max:10'],
            'ult_calculo'=> ['nullable','string'],
            'ult_preco'  => ['nullable','string'],
            'ult_compra' => ['nullable','string'],
            'peso_liquido'=> ['nullable','string'],
            'cta_contabil' => ['nullable','string','max:50'],
            'centro_custo' => ['nullable','string','max:50'],
            'item_conta'   => ['nullable','string','max:50'],
            'base_estrut'  => ['nullable','integer'],
            'fornecedor_padrao' => ['nullable','string','max:100'],
            'loja_padrao'  => ['nullable','string','max:100'],
            'rastro'       => ['nullable','in:S,N'],
            'ult_revisao'  => ['nullable','string'],
            'dt_referenc'  => ['nullable','string'],
            'apropriacao'  => ['nullable','string','max:100'],
            'fora_estado'  => ['nullable','in:S,N'],
            'fantasma'     => ['nullable','in:S,N'],
            'perc_comissao'=> ['nullable','string'],
            'cod_barras'   => ['nullable','string','max:100'],

            'aliq_icms' => ['nullable','string'],
            'aliq_ipi'  => ['nullable','string'],
            'aliq_iss'  => ['nullable','string'],
            'pos_ipi_ncm' => ['nullable','string','max:50'],
            'ex_nbm'    => ['nullable','string','max:50'],
            'ex_ncm'    => ['nullable','string','max:50'],
            'especie_tipi' => ['nullable','string','max:50'],
            'cod_serv_iss' => ['nullable','string','max:50'],
            'origem'    => ['nullable','string','max:20'],
            'class_fiscal' => ['nullable','string','max:50'],
            'grupo_trib' => ['nullable','string','max:50'],
            'cont_seg_soc' => ['nullable','string','max:50'],
            'imposto_renda' => ['nullable','string','max:50'],
            'calcula_inss'  => ['nullable','in:S,N'],
            'red_inss'   => ['nullable','string'],
            'red_irrf'   => ['nullable','string'],
            'red_pis'    => ['nullable','string'],
            'red_cofins' => ['nullable','string'],
            'perc_pis'   => ['nullable','string'],
            'perc_cofins'=> ['nullable','string'],
            'perc_csll'  => ['nullable','string'],
            'proprio_icms'=> ['nullable','string'],
            'icms_pauta' => ['nullable','string'],
            'ipi_pauta'  => ['nullable','string'],
            'aliq_famad' => ['nullable','string'],
            'aliq_fecp'  => ['nullable','string'],
            'solid_saida'=> ['nullable','string'],
            'solid_entrada'=> ['nullable','string'],
            'imp_zfranca'=> ['nullable','string'],
        ];
    }
}
