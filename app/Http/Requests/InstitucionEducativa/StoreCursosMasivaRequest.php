<?php

namespace App\Http\Requests\InstitucionEducativa;

use Illuminate\Foundation\Http\FormRequest;

class StoreCursosMasivaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>|string> */
    public function rules(): array
    {
        return [
            'filas'          => ['required', 'array', 'min:1', 'max:500'],
            'filas.*.nombre' => ['required', 'string', 'max:200'],
            'filas.*.sigla'  => ['nullable', 'string', 'max:20'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'filas.required'          => 'Debe enviar al menos una fila.',
            'filas.*.nombre.required' => 'El nombre del curso es obligatorio.',
            'filas.*.nombre.max'      => 'El nombre del curso no debe exceder 200 caracteres.',
            'filas.*.sigla.max'       => 'La sigla no debe exceder 20 caracteres.',
        ];
    }

    /** @return array<int, array<string, mixed>> Filas validadas listas para el Service */
    public function filas(): array
    {
        return $this->validated()['filas'];
    }
}
