<?php

namespace App\Http\Requests\Entidad;

use App\DTOs\Entidad\UpdateEntidadDTO;
use App\Models\Param\ParamTipoEntidad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEntidadRequest extends FormRequest
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
            'ruc' => [
                'required',
                'string',
                'size:11',
                Rule::unique('t_entidades', 'ruc')->ignore($this->route('entidade')),
            ],
            'razonSocial' => ['required', 'string', 'max:255'],
            'razonComercial' => ['nullable', 'string', 'max:255'],
            'personaRepLegal_id' => ['nullable', 'integer', 'exists:t_personas,id'],
            'activo' => ['boolean'],
        ];
    }

    public function toDTO(): UpdateEntidadDTO
    {
        return UpdateEntidadDTO::from($this->validated());
    }
}
