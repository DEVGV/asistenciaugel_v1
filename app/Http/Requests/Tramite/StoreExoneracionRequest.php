<?php

namespace App\Http\Requests\Tramite;

use App\DTOs\Tramite\CreateExoneracionDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreExoneracionRequest extends FormRequest
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
            'fechaInicio'      => 'fecha de inicio',
            'fechaFin'         => 'fecha de fin',
            'observacion'      => 'observación',
        ];
    }

    public function toDTO(): CreateExoneracionDTO
    {
        return CreateExoneracionDTO::from($this->validated());
    }
}
