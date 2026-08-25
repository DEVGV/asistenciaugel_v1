<?php

namespace App\Http\Requests\Tramite;

use App\DTOs\Tramite\CreateIncapacidadDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreIncapacidadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'motivoSuspLab_id'  => ['required', 'integer', 'exists:t00_motivosSuspLab,id'],
            'condicionSubsidio' => ['required', 'string', 'max:5'],
            'fechaInicio'       => ['required', 'date'],
            'fechaFin'          => ['required', 'date', 'after_or_equal:fechaInicio'],
            'nroDias'           => ['nullable', 'numeric', 'min:0'],
            'nroCertificado'    => ['required', 'string', 'max:50'],
            'observacion'       => ['nullable', 'string', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'motivoSuspLab_id.required'  => 'El motivo de incapacidad es obligatorio.',
            'motivoSuspLab_id.exists'    => 'El motivo seleccionado no existe.',
            'condicionSubsidio.required' => 'La condición de subsidio es obligatoria.',
            'fechaInicio.required'       => 'La fecha de inicio es obligatoria.',
            'fechaFin.required'          => 'La fecha de fin es obligatoria.',
            'fechaFin.after_or_equal'    => 'La fecha fin debe ser igual o posterior al inicio.',
            'nroCertificado.required'    => 'El número de certificado médico es obligatorio.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'motivoSuspLab_id'  => 'motivo de incapacidad',
            'condicionSubsidio' => 'condición de subsidio',
            'fechaInicio'       => 'fecha de inicio',
            'fechaFin'          => 'fecha de fin',
            'nroDias'           => 'número de días',
            'nroCertificado'    => 'número de certificado',
            'observacion'       => 'observación',
        ];
    }

    public function toDTO(): CreateIncapacidadDTO
    {
        return CreateIncapacidadDTO::from($this->validated());
    }
}
