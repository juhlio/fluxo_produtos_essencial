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
        ];
    }
}
