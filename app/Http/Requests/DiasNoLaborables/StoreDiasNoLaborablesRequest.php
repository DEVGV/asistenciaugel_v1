<?php

namespace App\Http\Requests\DiasNoLaborables;

use App\DTOs\DiasNoLaborables\CreateDiasNoLaborablesDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreDiasNoLaborablesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'institucionEduc_id' => ['required', 'integer', 'exists:t_institucionesEduc,id'],
            'fecha' => ['required', 'date'],
            'feriado_id' => ['nullable', 'integer', 'exists:t00_feriados,id'],
            'observacion' => ['nullable', 'string', 'max:200'],
            'nacionalLocal' => ['nullable', 'string', 'in:N,L'],
            'recuperable' => ['nullable', 'string', 'in:S,N'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no tiene un formato válido.',
            'institucionEduc_id.required' => 'La institución educativa es obligatoria.',
            'institucionEduc_id.exists' => 'La institución educativa no existe.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'fecha' => 'fecha',
            'feriado_id' => 'feriado',
            'observacion' => 'observación',
            'nacionalLocal' => 'ámbito',
            'recuperable' => 'recuperable',
        ];
    }

    public function toDTO(): CreateDiasNoLaborablesDTO
    {
        return CreateDiasNoLaborablesDTO::from($this->validated());
    }
}
