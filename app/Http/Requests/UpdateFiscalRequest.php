<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFiscalRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'ncm'           => ['nullable','string','max:20'],
            'cest'          => ['nullable','string','max:20'],
            'grupo_trib'    => ['nullable','string','max:50'],
            'cont_seg_soc'  => ['nullable','string','max:50'],
            'imposto_renda' => ['nullable','string','max:50'],
            'calcula_inss'  => ['nullable','in:S,N'],

            'aliq_icms' => ['nullable','string'],
            'aliq_ipi'  => ['nullable','string'],
            'aliq_iss'  => ['nullable','string'],
            'perc_pis'  => ['nullable','string'],
            'perc_cofins'=> ['nullable','string'],
            'perc_csll' => ['nullable','string'],
            'red_inss'  => ['nullable','string'],
            'red_irrf'  => ['nullable','string'],
            'red_pis'   => ['nullable','string'],
            'red_cofins'=> ['nullable','string'],
            'proprio_icms'=> ['nullable','string'],
            'icms_pauta' => ['nullable','string'],
            'ipi_pauta'  => ['nullable','string'],
            'aliq_famad' => ['nullable','string'],
            'aliq_fecp'  => ['nullable','string'],
            'solid_saida'=> ['nullable','string'],
            'solid_entrada'=> ['nullable','string'],
            'imp_zfranca'=> ['nullable','string'],

            // flags
            'tem_st'       => ['nullable','boolean'],
            'retencao_iss' => ['nullable','boolean'],
        ];
    }
}
