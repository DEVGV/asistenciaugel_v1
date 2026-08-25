<?php

namespace App\Http\Requests\Infraestructura;

use App\DTOs\Infraestructura\CreateLocalMarcacionDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocalMarcacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trabajador_id' => ['required', 'integer', 'exists:t_trabajador,id'],
            'altaTrabajador_id' => ['nullable', 'integer', 'exists:t_altasTrabajadores,id'],
            'localInstEduc_id' => ['required', 'integer', 'exists:t_localesInstEduc,id'],
            'fechaInicio' => ['nullable', 'date'],
            'fechaFin' => ['nullable', 'date', 'after_or_equal:fechaInicio'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'trabajador_id.required' => 'Debe seleccionar un trabajador.',
            'trabajador_id.exists' => 'El trabajador seleccionado no existe en el sistema.',
            'altaTrabajador_id.exists' => 'El alta de trabajador seleccionada no existe en el sistema.',
            'localInstEduc_id.required' => 'Debe seleccionar un local de institución educativa.',
            'localInstEduc_id.exists' => 'El local de institución educativa seleccionado no existe en el sistema.',
            'fechaInicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fechaFin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fechaFin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }

    public function toDTO(): CreateLocalMarcacionDTO
    {
        return CreateLocalMarcacionDTO::from($this->validated());
    }
}
