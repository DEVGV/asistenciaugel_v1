<?php

namespace App\Http\Requests\Persona;

use Illuminate\Foundation\Http\FormRequest;

class StoreTelefonoRequest extends FormRequest
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
            'operador_id' => ['required', 'integer'],
            'movilFijo' => ['required', 'string', 'in:M,F'],
            'codigoPais' => ['nullable', 'string', 'max:5'],
            'numero' => ['required', 'string', 'max:20'],
            'imei' => ['nullable', 'string', 'max:20'],
            'fechaInicio' => ['nullable', 'date'],
            'fechaFin' => ['nullable', 'date', 'after_or_equal:fechaInicio'],
        ];
    }
}
