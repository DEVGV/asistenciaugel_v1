<?php

namespace App\Http\Requests\Tramite;

use App\DTOs\Tramite\ValidarPermisoDTO;
use App\Enums\EstadoTramite;
use Illuminate\Foundation\Http\FormRequest;

class ValidarPermisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'estado'      => ['required', 'string', 'in:'.EstadoTramite::Validado->value.','.EstadoTramite::Rechazado->value],
            'observacion' => ['nullable', 'required_if:estado,'.EstadoTramite::Rechazado->value, 'string', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'estado.required'         => 'Debe indicar el resultado de la validación.',
            'estado.in'               => 'El resultado de la validación no es válido.',
            'observacion.required_if' => 'Debe indicar el motivo del rechazo.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'estado'      => 'resultado',
            'observacion' => 'observación',
        ];
    }

    public function toDTO(): ValidarPermisoDTO
    {
        return ValidarPermisoDTO::from($this->validated());
    }
}
