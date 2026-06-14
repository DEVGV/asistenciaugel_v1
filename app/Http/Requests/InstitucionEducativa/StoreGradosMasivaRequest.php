<?php

namespace App\Http\Requests\InstitucionEducativa;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradosMasivaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>|string> */
    public function rules(): array
    {
        return [
            'filas'                => ['required', 'array', 'min:1', 'max:500'],
            'filas.*.grado_nombre' => ['required', 'string', 'max:100'],
            'filas.*.grado_sigla'  => ['nullable', 'string', 'max:20'],
            'filas.*.secciones'    => ['nullable', 'array', 'max:50'],
            'filas.*.secciones.*'  => ['string', 'max:100'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'filas.required'              => 'Debe enviar al menos una fila.',
            'filas.*.grado_nombre.required' => 'El nombre del grado es obligatorio.',
            'filas.*.grado_nombre.max'    => 'El nombre del grado no debe exceder 100 caracteres.',
            'filas.*.grado_sigla.max'     => 'La sigla no debe exceder 20 caracteres.',
            'filas.*.secciones.max'       => 'No se pueden registrar más de 50 secciones por grado.',
            'filas.*.secciones.*.max'     => 'El nombre de sección no debe exceder 100 caracteres.',
        ];
    }

    /** @return array<int, array<string, mixed>> Filas validadas listas para el Service */
    public function filas(): array
    {
        return $this->validated()['filas'];
    }
}
