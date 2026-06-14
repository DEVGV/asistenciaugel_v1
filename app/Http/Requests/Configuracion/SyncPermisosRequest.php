<?php

namespace App\Http\Requests\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

class SyncPermisosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permiso_ids'   => ['required', 'array'],
            'permiso_ids.*' => ['integer', 'exists:auth.permisos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'permiso_ids.required' => 'Debe enviar la lista de permisos (puede estar vacía).',
        ];
    }
}
