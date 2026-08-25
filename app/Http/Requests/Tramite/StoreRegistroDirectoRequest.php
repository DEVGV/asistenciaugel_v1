<?php

namespace App\Http\Requests\Tramite;

use App\Enums\TipoExpediente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistroDirectoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $tipo = $this->input('tipoExpediente');

        $rules = [
            'tipoExpediente' => ['required', Rule::enum(TipoExpediente::class)],
            'trabajador_id' => ['required', 'integer', 'exists:t_trabajador,id'],
            'altaTrabajador_id' => ['nullable', 'integer', 'exists:t_altasTrabajadores,id'],
        ];

        // Reglas condicionales según el tipo
        return match ($tipo) {
            'S' => array_merge($rules, [
                'motivoSuspLab_id' => ['required', 'integer', 'exists:t00_motivosSuspLab,id'],
                'fechaHoraInicio' => ['required', 'date'],
                'fechaHoraFin' => ['required', 'date', 'after_or_equal:fechaHoraInicio'],
                'totalDias' => ['nullable', 'numeric', 'min:0'],
                'totalHoras' => ['nullable', 'numeric', 'min:0'],
                'observacion' => ['nullable', 'string', 'max:255'],
            ]),
            'J' => array_merge($rules, [
                'motivoSuspLab_id' => ['nullable', 'integer', 'exists:param.t00_motivosSuspLab,id'],
                'turno' => ['nullable', 'integer'],
                'fechaInicio' => ['required', 'date'],
                'fechaFin' => ['required', 'date', 'after_or_equal:fechaInicio'],
                'observacion' => ['nullable', 'string', 'max:255'],
            ]),
            'I' => array_merge($rules, [
                'motivoSuspLab_id' => ['required', 'integer', 'exists:t00_motivosSuspLab,id'],
                'condicionSubsidio' => ['required', 'string', 'max:5'],
                'fechaInicio' => ['required', 'date'],
                'fechaFin' => ['required', 'date', 'after_or_equal:fechaInicio'],
                'nroDias' => ['nullable', 'numeric', 'min:0'],
                'nroCertificado' => ['required', 'string', 'max:50'],
                'observacion' => ['nullable', 'string', 'max:255'],
            ]),
            'E' => array_merge($rules, [
                'motivoSuspLab_id' => ['nullable', 'integer', 'exists:param.t00_motivosSuspLab,id'],
                'fechaInicio' => ['required', 'date'],
                'fechaFin' => ['required', 'date', 'after_or_equal:fechaInicio'],
                'observacion' => ['nullable', 'string', 'max:255'],
            ]),
            default => $rules,
        };
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'tipoExpediente.required' => 'El tipo es obligatorio.',
            'trabajador_id.required' => 'El trabajador es obligatorio.',
            'trabajador_id.exists' => 'El trabajador seleccionado no existe.',
            'altaTrabajador_id.exists' => 'El alta seleccionada no existe.',
            'motivoSuspLab_id.required' => 'El motivo es obligatorio.',
            'motivoSuspLab_id.exists' => 'El motivo seleccionado no existe.',
            'fechaHoraInicio.required' => 'La fecha de inicio es obligatoria.',
            'fechaHoraFin.required' => 'La fecha de fin es obligatoria.',
            'fechaHoraFin.after_or_equal' => 'La fecha fin debe ser igual o posterior al inicio.',
            'fechaInicio.required' => 'La fecha de inicio es obligatoria.',
            'fechaFin.required' => 'La fecha de fin es obligatoria.',
            'fechaFin.after_or_equal' => 'La fecha fin debe ser igual o posterior al inicio.',
            'condicionSubsidio.required' => 'La condición de subsidio es obligatoria.',
            'nroCertificado.required' => 'El número de certificado médico es obligatorio.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'tipoExpediente' => 'tipo',
            'trabajador_id' => 'trabajador',
            'altaTrabajador_id' => 'alta del trabajador',
            'motivoSuspLab_id' => 'motivo',
            'fechaHoraInicio' => 'fecha de inicio',
            'fechaHoraFin' => 'fecha de fin',
            'fechaInicio' => 'fecha de inicio',
            'fechaFin' => 'fecha de fin',
            'totalDias' => 'total de días',
            'totalHoras' => 'total de horas',
            'condicionSubsidio' => 'condición de subsidio',
            'nroDias' => 'número de días',
            'nroCertificado' => 'número de certificado',
            'observacion' => 'observación',
        ];
    }

    /**
     * Retorna solo los campos validados del detalle CUD (sin los campos comunes).
     *
     * @return array<string, mixed>
     */
    public function cudData(): array
    {
        $comunes = ['tipoExpediente', 'trabajador_id', 'altaTrabajador_id'];

        return collect($this->validated())->except($comunes)->all();
    }
}
