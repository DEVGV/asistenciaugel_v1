<?php

namespace App\Http\Requests\Infraestructura;

use Illuminate\Foundation\Http\FormRequest;

class StoreRelojesMasivaRequest extends FormRequest
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
            'filas.*.nombre'       => ['nullable', 'string', 'max:200'],
            'filas.*.dreccionIP'   => ['nullable', 'string', 'max:30'],
            'filas.*.direccionMac' => ['nullable', 'string', 'max:50'],
            'filas.*.puerto'       => ['nullable', 'integer', 'min:1', 'max:65535'],
            'filas.*.serie'        => ['nullable', 'string', 'max:100'],
            'filas.*.idBiometrico' => ['nullable', 'integer'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'filas.required'          => 'Debe enviar al menos una fila.',
            'filas.*.nombre.max'      => 'El nombre no debe exceder 200 caracteres.',
            'filas.*.dreccionIP.max'  => 'La dirección IP no debe exceder 30 caracteres.',
            'filas.*.direccionMac.max' => 'La dirección MAC no debe exceder 50 caracteres.',
            'filas.*.puerto.min'      => 'El puerto debe ser al menos 1.',
            'filas.*.puerto.max'      => 'El puerto no debe exceder 65535.',
            'filas.*.serie.max'       => 'La serie no debe exceder 100 caracteres.',
        ];
    }

    /** @return array<int, array<string, mixed>> Filas validadas listas para el Service */
    public function filas(): array
    {
        return $this->validated()['filas'];
    }
}
