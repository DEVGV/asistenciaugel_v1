<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ConsultarRucRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>|string> */
    public function rules(): array
    {
        return [
            'ruc' => ['required', 'string', 'size:11', 'regex:/^\d{11}$/'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.size'     => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.regex'    => 'El RUC solo debe contener números.',
        ];
    }

    public function ruc(): string
    {
        return $this->validated()['ruc'];
    }
}
