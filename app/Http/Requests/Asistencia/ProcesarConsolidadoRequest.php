<?php

namespace App\Http\Requests\Asistencia;

use Illuminate\Foundation\Http\FormRequest;

class ProcesarConsolidadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'anio' => ['required', 'integer', 'min:2020', 'max:2099'],
            'mes'  => ['required', 'integer', 'min:1', 'max:12'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'anio.required' => 'El año es obligatorio.',
            'anio.integer'  => 'El año debe ser un número entero.',
            'anio.min'      => 'El año mínimo es 2020.',
            'anio.max'      => 'El año máximo es 2099.',
            'mes.required'  => 'El mes es obligatorio.',
            'mes.integer'   => 'El mes debe ser un número entero.',
            'mes.min'       => 'El mes mínimo es 1.',
            'mes.max'       => 'El mes máximo es 12.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'anio' => 'año',
            'mes'  => 'mes',
        ];
    }

    /**
     * Calcula fechaDesde y fechaHasta a partir de año y mes.
     *
     * @return array{anio: int, mes: int, fechaDesde: string, fechaHasta: string}
     */
    public function toProcesarParams(): array
    {
        $anio = (int) $this->validated('anio');
        $mes  = (int) $this->validated('mes');

        $fechaDesde = sprintf('%04d-%02d-01', $anio, $mes);
        $fechaHasta = date('Y-m-t', strtotime($fechaDesde));

        return [
            'anio'       => $anio,
            'mes'        => $mes,
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
        ];
    }
}
