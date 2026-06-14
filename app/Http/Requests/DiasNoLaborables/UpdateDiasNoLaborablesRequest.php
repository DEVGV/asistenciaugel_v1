<?php

namespace App\Http\Requests\DiasNoLaborables;

use App\DTOs\DiasNoLaborables\UpdateDiasNoLaborablesDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiasNoLaborablesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'fecha'         => ['required', 'date'],
            'feriado_id'    => ['nullable', 'integer', 'exists:param.t00_feriados,id'],
            'observacion'   => ['nullable', 'string', 'max:200'],
            'nacionalLocal' => ['nullable', 'string', 'in:N,L'],
            'recuperable'   => ['nullable', 'string', 'in:S,N'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date'     => 'La fecha no tiene un formato válido.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'fecha'         => 'fecha',
            'feriado_id'    => 'feriado',
            'observacion'   => 'observación',
            'nacionalLocal' => 'ámbito',
            'recuperable'   => 'recuperable',
        ];
    }

    public function toDTO(): UpdateDiasNoLaborablesDTO
    {
        return UpdateDiasNoLaborablesDTO::from($this->validated());
    }
}
