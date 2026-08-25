<?php

namespace App\Http\Requests\Trabajador;

use App\DTOs\Persona\UpdatePersonaDTO;
use App\Models\Param\ParamSexos;
use App\Models\Param\ParamTipoDocIdentidad;
use App\Models\Trabajador;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonaTrabajadorRequest extends FormRequest
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
        /** @var Trabajador $trabajador */
        $trabajador = $this->route('trabajador');
        $personaId = $trabajador?->persona_id;

        return [
            'tipoDocIdentidad_id' => ['required', 'integer', Rule::exists(ParamTipoDocIdentidad::class, 'id')],
            'docIdentidad' => [
                'required',
                'string',
                'max:20',
                Rule::unique('t_personas', 'docIdentidad')->ignore($personaId),
            ],
            'paterno' => ['required', 'string', 'max:100'],
            'materno' => ['required', 'string', 'max:100'],
            'nombre'  => ['required', 'string', 'max:100'],
            'sexo_id' => ['required', 'integer', Rule::exists(ParamSexos::class, 'id')],
            'fechaNac' => ['nullable', 'date'],
            'pais_id'  => ['required', 'integer'],
            'ubigeo'   => ['nullable', 'string', 'max:6'],
            'foto'     => ['nullable', 'string'],
            'activo'   => ['boolean'],
        ];
    }

    public function toDTO(): UpdatePersonaDTO
    {
        return UpdatePersonaDTO::from($this->validated());
    }
}
