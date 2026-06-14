<?php

namespace App\Http\Requests\Auth;

use App\DTOs\Auth\SeleccionarContextoDTO;
use App\Services\Auth\ContextoService;
use Illuminate\Foundation\Http\FormRequest;

class SeleccionarContextoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(ContextoService::class)->puedeAcceder(
            $this->user(),
            (int) $this->input('ugel_id'),
            $this->filled('ie_id') ? (int) $this->input('ie_id') : null,
        );
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'ugel_id' => ['required', 'integer', 'exists:t_entidades,id'],
            'ie_id' => ['nullable', 'integer', 'exists:t_institucionesEduc,id'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'ugel_id.required' => 'Debe seleccionar una UGEL.',
            'ugel_id.exists' => 'La UGEL seleccionada no existe.',
            'ie_id.exists' => 'La institución educativa seleccionada no existe.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'ugel_id' => 'UGEL',
            'ie_id' => 'institución educativa',
        ];
    }

    public function toDTO(): SeleccionarContextoDTO
    {
        return SeleccionarContextoDTO::from($this->validated());
    }
}
