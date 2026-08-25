<?php

namespace App\Http\Requests\Asistencia;

use App\Models\Trabajador;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReprocesarTrabajadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trabajador_id' => ['required', 'integer', Rule::exists(Trabajador::class, 'id')],
            'anio'          => ['required', 'integer', 'min:2020', 'max:2099'],
            'mes'           => ['required', 'integer', 'min:1', 'max:12'],
        ];
    }

    /**
     * @return array{trabajador_id: int, anio: int, mes: int, fechaDesde: string, fechaHasta: string}
     */
    public function toProcesarParams(): array
    {
        $anio = (int) $this->validated('anio');
        $mes  = (int) $this->validated('mes');

        $fechaDesde = sprintf('%04d-%02d-01', $anio, $mes);
        $fechaHasta = date('Y-m-t', strtotime($fechaDesde));

        return [
            'trabajador_id' => (int) $this->validated('trabajador_id'),
            'anio'          => $anio,
            'mes'           => $mes,
            'fechaDesde'    => $fechaDesde,
            'fechaHasta'    => $fechaHasta,
        ];
    }
}
