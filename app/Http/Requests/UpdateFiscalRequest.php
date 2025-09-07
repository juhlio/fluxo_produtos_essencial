<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFiscalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && (
            method_exists($this->user(), 'hasAnyRole')
                ? $this->user()->hasAnyRole(['admin','fiscal','contabil'])
                : true
        );
    }

    public function rules(): array
    {
        return [
            'ncm'                  => ['required','string','max:10'],
            'origem'               => ['required','string','max:2'],
            'cfop_entrada'         => ['nullable','string','max:5'],
            'cfop_saida'           => ['nullable','string','max:5'],
            'cst_icms'             => ['nullable','string','max:3'],
            'csosn'                => ['nullable','string','max:3'],
            'aliq_icms'            => ['nullable','numeric'],
            'aliq_ipi'             => ['nullable','numeric'],
            'cst_pis'              => ['nullable','string','max:2'],
            'aliq_pis'             => ['nullable','numeric'],
            'cst_cofins'           => ['nullable','string','max:2'],
            'aliq_cofins'          => ['nullable','numeric'],
            'tem_st'               => ['nullable','boolean'],
            'mva_st'               => ['nullable','numeric'],
            'cod_servico_municipal'=> ['nullable','string','max:20'],
            'aliq_iss'             => ['nullable','numeric'],
            'retencao_iss'         => ['nullable','boolean'],
            'conta_contabil'       => ['nullable','string','max:100'],
            'natureza'             => ['nullable','string','max:100'],
            'centro_custo_padrao'  => ['nullable','string','max:100'],
        ];
    }
}
