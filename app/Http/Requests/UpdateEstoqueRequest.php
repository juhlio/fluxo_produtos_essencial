<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEstoqueRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'sku'       => ['nullable','string','max:120'],
            'peso'      => ['nullable','numeric','min:0'],
            'dimensoes' => ['nullable','string','max:120'],
        ];
    }
}
