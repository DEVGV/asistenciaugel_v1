<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ConsultarDniRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>|string> */
    public function rules(): array
    {
        return [
            'dni' => ['required', 'string', 'size:8', 'regex:/^\d{8}$/'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.size'     => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.regex'    => 'El DNI solo debe contener números.',
        ];
    }

    public function dni(): string
    {
        return $this->validated()['dni'];
    }
}
