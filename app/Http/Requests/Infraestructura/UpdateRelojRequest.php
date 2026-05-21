<?php

namespace App\Http\Requests\Infraestructura;

use App\DTOs\Infraestructura\UpdateRelojDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRelojRequest extends FormRequest
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
            'nombre' => ['nullable', 'string', 'max:200'],
            'dreccionIP' => ['nullable', 'string', 'max:30'],
            'direccionMac' => ['nullable', 'string', 'max:50'],
            'puerto' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'serie' => ['nullable', 'string', 'max:100'],
            'activo' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.max' => 'El nombre no debe exceder los 200 caracteres.',
            'dreccionIP.max' => 'La dirección IP no debe exceder los 30 caracteres.',
            'direccionMac.max' => 'La dirección MAC no debe exceder los 50 caracteres.',
            'puerto.min' => 'El puerto debe ser al menos 1.',
            'puerto.max' => 'El puerto no debe exceder 65535.',
            'serie.max' => 'La serie no debe exceder los 100 caracteres.',
        ];
    }

    public function toDTO(): UpdateRelojDTO
    {
        return UpdateRelojDTO::from($this->validated());
    }
}
