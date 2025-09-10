<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBasicsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'descricao'  => ['required','string','max:255'],
            'unidade'    => ['nullable','string','max:50'],
            'marca'      => ['nullable','string','max:100'],
            'modelo'     => ['nullable','string','max:100'],
            'sku'        => ['nullable','string','max:100'],
            'familia'    => ['nullable','string','max:100'],
            'peso'       => ['nullable','numeric'],
            'dimensoes'  => ['nullable','string','max:255'],
            // Extras
            'codigo'         => ['nullable','string','max:100'],
            'tipo'           => ['nullable','string','max:50'],
            'armazem_padrao' => ['nullable','string','max:100'],
            'grupo'          => ['nullable','string','max:100'],
            'grupo_trib'     => ['nullable','string','max:100'],
            'cont_seg_soc'   => ['nullable','string','max:100'],
            'imposto_renda'  => ['nullable','string','max:100'],
            'calcula_inss'   => ['nullable','string','max:10'],
            'red_inss'       => ['nullable','numeric'],
            'red_irrf'       => ['nullable','numeric'],
            'red_pis'        => ['nullable','numeric'],
            'red_cofins'     => ['nullable','numeric'],
            'perc_pis'       => ['nullable','numeric'],
            'perc_cofins'    => ['nullable','numeric'],
            'perc_csll'      => ['nullable','numeric'],
            'proprio_icms'   => ['nullable','numeric'],
            'icms_pauta'     => ['nullable','numeric'],
            'ipi_pauta'      => ['nullable','numeric'],
            'aliq_famad'     => ['nullable','numeric'],
            'aliq_fecp'      => ['nullable','numeric'],
            'solid_saida'    => ['nullable','numeric'],
            'solid_entrada'  => ['nullable','numeric'],
            'imp_zfranca'    => ['nullable','numeric'],
        ];
    }
}
