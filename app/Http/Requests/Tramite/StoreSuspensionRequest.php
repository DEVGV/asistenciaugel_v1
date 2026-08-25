<?php

namespace App\Http\Requests\Tramite;

use App\DTOs\Tramite\CreateSuspensionDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreSuspensionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'motivoSuspLab_id' => ['required', 'integer', 'exists:t00_motivosSuspLab,id'],
            'fechaHoraInicio'  => ['required', 'date'],
            'fechaHoraFin'     => ['required', 'date', 'after_or_equal:fechaHoraInicio'],
            'totalDias'        => ['nullable', 'numeric', 'min:0'],
            'totalHoras'       => ['nullable', 'numeric', 'min:0'],
            'observacion'      => ['nullable', 'string', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'motivoSuspLab_id.required'    => 'El motivo de suspensión es obligatorio.',
            'motivoSuspLab_id.exists'      => 'El motivo seleccionado no existe.',
            'fechaHoraInicio.required'     => 'La fecha de inicio es obligatoria.',
            'fechaHoraFin.required'        => 'La fecha de fin es obligatoria.',
            'fechaHoraFin.after_or_equal'  => 'La fecha fin debe ser igual o posterior al inicio.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'motivoSuspLab_id' => 'motivo de suspensión',
            'fechaHoraInicio'  => 'fecha de inicio',
            'fechaHoraFin'     => 'fecha de fin',
            'totalDias'        => 'total de días',
            'totalHoras'       => 'total de horas',
            'observacion'      => 'observación',
        ];
    }

    public function toDTO(): CreateSuspensionDTO
    {
        return CreateSuspensionDTO::from($this->validated());
    }
}
