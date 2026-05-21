<?php

namespace App\Http\Requests\Infraestructura;

use App\DTOs\Infraestructura\CreateDispositivoMarcaDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreDispositivoMarcaRequest extends FormRequest
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
            'telefonoMovil_id' => ['required', 'integer', 'exists:t_telefonos,id'],
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
            'telefonoMovil_id.required' => 'Debe seleccionar un teléfono móvil.',
            'telefonoMovil_id.exists' => 'El teléfono móvil seleccionado no existe en el sistema.',
            'fechaInicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fechaFin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fechaFin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }

    public function toDTO(): CreateDispositivoMarcaDTO
    {
        return CreateDispositivoMarcaDTO::from($this->validated());
    }
}
