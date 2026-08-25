<?php

namespace App\Http\Requests\Horario;

use App\Models\Conasis\ConasisDetalleHorarios;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateToleranciasHorarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Payload esperado:
     *   {
     *     "detalles": [
     *        { "id": 12, "entTolerancia": 15, "salTolerancia": 15,
     *          "entHoraInicio": "07:45", "entHoraFin": "08:00",
     *          "salHoraInicio": "09:30", "salHoraFin": "09:45" }, ...
     *     ]
     *   }
     */
    public function rules(): array
    {
        return [
            'detalles'                     => ['required', 'array', 'min:1'],
            'detalles.*.id'                => ['required', 'integer', Rule::exists(ConasisDetalleHorarios::class, 'id')],
            'detalles.*.entTolerancia'     => ['required', 'integer', 'min:0', 'max:120'],
            'detalles.*.salTolerancia'     => ['required', 'integer', 'min:0', 'max:120'],
            'detalles.*.entHoraInicio'     => ['nullable', 'date_format:H:i'],
            'detalles.*.entHoraFin'        => ['nullable', 'date_format:H:i'],
            'detalles.*.salHoraInicio'     => ['nullable', 'date_format:H:i'],
            'detalles.*.salHoraFin'        => ['nullable', 'date_format:H:i'],
            'detalles.*.aplicar'           => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'detalles.*.entTolerancia.max' => 'La tolerancia de entrada no puede superar 120 minutos.',
            'detalles.*.salTolerancia.max' => 'La tolerancia de salida no puede superar 120 minutos.',
        ];
    }

    /**
     * Validar que los rangos horarios sean coherentes (fin >= inicio).
     */
    public function withValidator(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Contracts\Validation\Validator $v) {
            $detalles = $this->input('detalles', []);
            foreach ($detalles as $i => $det) {
                $entDesde = $det['entHoraInicio'] ?? null;
                $entHasta = $det['entHoraFin'] ?? null;
                $salDesde = $det['salHoraInicio'] ?? null;
                $salHasta = $det['salHoraFin'] ?? null;

                if ($entDesde && $entHasta && $entHasta < $entDesde) {
                    $v->errors()->add("detalles.{$i}.entHoraFin", 'Entrada Hasta debe ser igual o posterior a Entrada Desde.');
                }
                if ($salDesde && $salHasta && $salHasta < $salDesde) {
                    $v->errors()->add("detalles.{$i}.salHoraFin", 'Salida Hasta debe ser igual o posterior a Salida Desde.');
                }
            }
        });
    }
}
