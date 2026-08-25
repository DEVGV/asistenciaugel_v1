<?php

namespace App\Http\Requests\Tramite;

use App\DTOs\Tramite\CreateJustificacionDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreJustificacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'motivoSuspLab_id' => ['nullable', 'integer', 'exists:param.t00_motivosSuspLab,id'],
            'turno'            => ['nullable', 'integer'],
            'fechaInicio'      => ['required', 'date'],
            'fechaFin'         => ['required', 'date', 'after_or_equal:fechaInicio'],
            'observacion'      => ['nullable', 'string', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'fechaInicio.required'    => 'La fecha de inicio es obligatoria.',
            'fechaFin.required'       => 'La fecha de fin es obligatoria.',
            'fechaFin.after_or_equal' => 'La fecha fin debe ser igual o posterior al inicio.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'motivoSuspLab_id' => 'motivo',
            'turno'            => 'turno',
            'fechaInicio'      => 'fecha de inicio',
            'fechaFin'         => 'fecha de fin',
            'observacion'      => 'observación',
        ];
    }

    public function toDTO(): CreateJustificacionDTO
    {
        return CreateJustificacionDTO::from($this->validated());
    }
}
