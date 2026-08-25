<?php

namespace App\Http\Requests\Configuracion;

use App\DTOs\Configuracion\CreateCargoDTO;
use App\Models\Param\ParamRolTrabajador;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCargoRequest extends FormRequest
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
            'rolTrabajador_id' => ['nullable', 'integer', Rule::exists(ParamRolTrabajador::class, 'id')],
            'activo' => ['boolean'],
        ];
    }

    public function toDTO(): CreateCargoDTO
    {
        return CreateCargoDTO::from($this->validated());
    }
}
