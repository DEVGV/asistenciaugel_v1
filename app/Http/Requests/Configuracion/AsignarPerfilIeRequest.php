<?php

namespace App\Http\Requests\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

class AsignarPerfilIeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'perfil_id' => ['required', 'integer', 'exists:perfiles,id'],
            'institucionEducativa_id' => ['nullable', 'integer', 'exists:t_institucionesEduc,id'],
            'entidadUgel_id' => ['nullable', 'integer', 'exists:t_entidades,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'perfil_id.required' => 'El perfil es obligatorio.',
            'perfil_id.exists' => 'El perfil seleccionado no existe.',
            'institucionEducativa_id.exists' => 'La institución educativa no existe.',
            'entidadUgel_id.exists' => 'La UGEL seleccionada no existe.',
        ];
    }
}
