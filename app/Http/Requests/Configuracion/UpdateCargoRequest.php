<?php

namespace App\Http\Requests\Configuracion;

use App\DTOs\Configuracion\UpdateCargoDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCargoRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:255'],
            'abreviatura' => ['nullable', 'string', 'max:50'],
            'rolTrabajador_id' => ['nullable', 'integer'],
            'activo' => ['boolean'],
        ];
    }

    public function toDTO(): UpdateCargoDTO
    {
        return UpdateCargoDTO::from($this->validated());
    }
}
