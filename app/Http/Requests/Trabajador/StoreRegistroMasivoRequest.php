<?php

namespace App\Http\Requests\Trabajador;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistroMasivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>|string> */
    public function rules(): array
    {
        return [
            'filas' => ['required', 'array', 'min:1'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'filas.required' => 'Debe enviar al menos una fila.',
            'filas.min'      => 'Debe enviar al menos una fila.',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function filas(): array
    {
        return $this->validated()['filas'];
    }
}
