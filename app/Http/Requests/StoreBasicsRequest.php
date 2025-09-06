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
            'unidade'   => ['nullable','string','max:10'],
            'marca'     => ['nullable','string','max:120'],
            'modelo'    => ['nullable','string','max:120'],
            'sku'       => ['nullable','string','max:120'],
            'familia'   => ['nullable','string','max:120'],
            'peso'      => ['nullable','numeric','min:0'],
            'dimensoes' => ['nullable','string','max:120'],
        ];
    }
}
