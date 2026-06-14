<?php

namespace App\Http\Requests\Persona;

use App\Models\Telefonos;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTelefonoRequest extends FormRequest
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
        // En store: persona viene como route param {persona}
        // En update: telefono viene como route param {telefono}
        $telefono = $this->route('telefono');
        $persona = $this->route('persona');

        // Determinar persona_id para la validación unique
        $personaId = $telefono instanceof Telefonos
            ? $telefono->persona_id
            : (is_object($persona) ? $persona->id : $persona);

        $ignoreId = $telefono instanceof Telefonos ? $telefono->id : null;

        return [
            'operador_id' => ['nullable', 'integer'],
            'movilFijo' => ['required', 'string', 'in:M,F'],
            'codigoPais' => ['nullable', 'string', 'max:5'],
            'numero' => [
                'required',
                'string',
                'max:20',
                Rule::unique('t_telefonos', 'numero')
                    ->where('persona_id', $personaId)
                    ->ignore($ignoreId),
            ],
            'imei' => ['nullable', 'string', 'max:20'],
            'fechaInicio' => ['sometimes', 'nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'numero.unique' => 'Este número ya está registrado para esta persona.',
        ];
    }

    /** @return array<string, mixed> */
    public function toDTO(): array
    {
        return $this->validated();
    }
}
