<?php

namespace App\Http\Requests\Entidad;

use App\DTOs\Entidad\CreateEntidadDTO;
use App\Models\Param\ParamTipoEntidad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEntidadRequest extends FormRequest
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
            'tipoEntidad_id' => ['required', 'integer', Rule::exists(ParamTipoEntidad::class, 'id')],
            'ruc' => ['required', 'string', 'size:11', 'unique:t_entidades,ruc'],
            'razonSocial' => ['required', 'string', 'max:255'],
            'razonComercial' => ['nullable', 'string', 'max:255'],
            'personaRepLegal_id' => ['nullable', 'integer', 'exists:t_personas,id'],
            'activo' => ['boolean'],
        ];
    }

    public function toDTO(): CreateEntidadDTO
    {
        return CreateEntidadDTO::from($this->validated());
    }
}
