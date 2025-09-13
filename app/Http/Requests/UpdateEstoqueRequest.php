<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEstoqueRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'descricao' => ['required','string','max:255'],
            'unidade'   => ['nullable','string','max:10'],
            'marca'     => ['nullable','string','max:100'],
            'modelo'    => ['nullable','string','max:100'],
            'sku'       => ['nullable','string','max:100'],
            'familia'   => ['nullable','string','max:100'],
            'peso'      => ['nullable','string'],
            'dimensoes' => ['nullable','string','max:100'],

            // campos operacionais de estoque
            'armazem_padrao' => ['nullable','string','max:50'],
            'grupo'          => ['nullable','string','max:50'],
            'rastro'         => ['nullable','in:S,N'],
            'base_estrut'    => ['nullable','integer'],
            'fornecedor_padrao' => ['nullable','string','max:100'],
            'loja_padrao'    => ['nullable','string','max:100'],
        ];
    }
}
