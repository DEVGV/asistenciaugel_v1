<?php

namespace App\Http\Requests\Persona;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomicilioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'domicilio' => ['required', 'string', 'max:500'],
            'zona_id' => ['required', 'integer'],
            'ubigeo' => ['nullable', 'string', 'max:6'],
            'fechaInicio' => ['nullable', 'date'],
            'fechaFin' => ['nullable', 'date', 'after_or_equal:fechaInicio'],
        ];
    }
}
