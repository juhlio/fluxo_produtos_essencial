<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        $id = $this->route('user'); // resource param
        return [
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:190', Rule::unique('users','email')->ignore($id)],
            'password' => ['nullable','string','min:8','confirmed'],
            'roles' => ['array'],
            'roles.*' => ['string'],
        ];
    }
}
