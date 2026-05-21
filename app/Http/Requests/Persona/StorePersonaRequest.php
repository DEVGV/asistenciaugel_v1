<?php

namespace App\Http\Requests\Persona;

use App\DTOs\Persona\CreatePersonaDTO;
use App\Models\Param\ParamSexos;
use App\Models\Param\ParamTipoDocIdentidad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonaRequest extends FormRequest
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
            'tipoDocIdentidad_id' => ['required', 'integer', Rule::exists(ParamTipoDocIdentidad::class, 'id')],
            'docIdentidad' => ['required', 'string', 'max:20', 'unique:t_personas,docIdentidad'],
            'paterno' => ['required', 'string', 'max:100'],
            'materno' => ['required', 'string', 'max:100'],
            'nombre' => ['required', 'string', 'max:100'],
            'sexo_id' => ['required', 'integer', Rule::exists(ParamSexos::class, 'id')],
            'fechaNac' => ['nullable', 'date'],
            'pais_id' => ['required', 'integer'],
            'ubigeo' => ['nullable', 'string', 'max:6'],
            'foto' => ['nullable', 'string'],
            'activo' => ['boolean'],
        ];
    }

    public function toDTO(): CreatePersonaDTO
    {
        return CreatePersonaDTO::from($this->validated());
    }
}
