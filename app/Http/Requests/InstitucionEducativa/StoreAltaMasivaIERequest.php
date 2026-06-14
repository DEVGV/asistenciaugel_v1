<?php

namespace App\Http\Requests\InstitucionEducativa;

use Illuminate\Foundation\Http\FormRequest;

class StoreAltaMasivaIERequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>|string> */
    public function rules(): array
    {
        return [
            'filas'                        => ['required', 'array', 'min:1', 'max:2000'],
            'filas.*.trabajador_id'        => ['required', 'integer'],
            'filas.*.condicionLaboral_id'  => ['required', 'integer'],
            'filas.*.tipoContrato_id'      => ['required', 'integer'],
            'filas.*.rolTrabajador_id'     => ['required', 'integer'],
            'filas.*.situacionLaboral_id'  => ['required', 'integer'],
            'filas.*.area_id'              => ['required', 'integer'],
            'filas.*.cargo_id'             => ['required', 'integer'],
            'filas.*.fechaInicio'          => ['required', 'date'],
            'filas.*.fechaFin'             => ['nullable', 'date'],
            'filas.*.codigoAirsp'          => ['nullable', 'string', 'max:50'],
            'filas.*.observacion'          => ['nullable', 'string', 'max:500'],
            'filas.*.localInstEduc_id'     => ['nullable', 'integer'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'filas.required'                       => 'Debe enviar al menos una fila.',
            'filas.*.trabajador_id.required'       => 'El trabajador es obligatorio.',
            'filas.*.condicionLaboral_id.required' => 'La condición laboral es obligatoria.',
            'filas.*.tipoContrato_id.required'     => 'El tipo de contrato es obligatorio.',
            'filas.*.rolTrabajador_id.required'    => 'El rol del trabajador es obligatorio.',
            'filas.*.situacionLaboral_id.required' => 'La situación laboral es obligatoria.',
            'filas.*.area_id.required'             => 'El área es obligatoria.',
            'filas.*.cargo_id.required'            => 'El cargo es obligatorio.',
            'filas.*.fechaInicio.required'         => 'La fecha de inicio es obligatoria.',
            'filas.*.fechaInicio.date'             => 'La fecha de inicio debe ser una fecha válida.',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function filas(): array
    {
        return $this->validated()['filas'];
    }
}
