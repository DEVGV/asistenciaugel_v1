<?php

namespace App\Http\Requests\Trabajador;

use App\DTOs\Trabajador\CreateTrabajadorDTO;
use App\Models\Personas;
use App\Models\Trabajador;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTrabajadorRequest extends FormRequest
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
            'trabajadores' => ['required', 'array', 'min:1'],
            'trabajadores.*.persona_id' => [
                'required',
                'integer',
                Rule::exists(Personas::class, 'id'),
                Rule::unique(Trabajador::class, 'persona_id')->where('activo', true),
            ],
            'trabajadores.*.activo' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'trabajadores.required' => 'Debe enviar al menos un trabajador.',
            'trabajadores.array' => 'El formato de trabajadores no es válido.',
            'trabajadores.min' => 'Debe registrar al menos un trabajador.',
            'trabajadores.*.persona_id.required' => 'Debe seleccionar una persona.',
            'trabajadores.*.persona_id.exists' => 'La persona seleccionada no existe en el sistema.',
            'trabajadores.*.persona_id.unique' => 'Esta persona ya se encuentra registrada como un trabajador activo.',
        ];
    }

    /**
     * @return CreateTrabajadorDTO[]
     */
    public function toDTOs(): array
    {
        $dtos = [];
        foreach ($this->validated('trabajadores') as $data) {
            $dtos[] = CreateTrabajadorDTO::from($data);
        }

        return $dtos;
    }
}
