<?php

namespace App\Http\Requests\Infraestructura;

use App\DTOs\Infraestructura\CreateRelojDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreRelojRequest extends FormRequest
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
            'idBiometrico' => ['nullable', 'integer'],
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
            'localInstEduc_id.required' => 'Debe seleccionar un local de institución educativa.',
            'localInstEduc_id.exists' => 'El local de institución educativa seleccionado no existe en el sistema.',
        ];
    }

    public function toDTO(): CreateRelojDTO
    {
        return CreateRelojDTO::from($this->validated());
    }
}
