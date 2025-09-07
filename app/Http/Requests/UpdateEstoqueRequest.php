<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEstoqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && (
            method_exists($this->user(), 'hasAnyRole')
                ? $this->user()->hasAnyRole(['admin','estoque'])
                : true
        );
    }

    public function rules(): array
    {
        return [
            'unidade'   => ['nullable','string','max:50'],
            'sku'       => ['nullable','string','max:100'],
            'marca'     => ['nullable','string','max:100'],
            'modelo'    => ['nullable','string','max:100'],
            'familia'   => ['nullable','string','max:100'],
            'peso'      => ['nullable','numeric'],
            'dimensoes' => ['nullable','string','max:255'],
        ];
    }
}
