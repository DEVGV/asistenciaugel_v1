<?php

namespace App\Http\Requests\InstitucionEducativa;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubRecursoIERequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:150'],
            'sigla' => ['nullable', 'string', 'max:20'],
            'activo' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
        ];
    }

    /** @return array<string, mixed> */
    public function toDTO(): array
    {
        return $this->validated();
    }
}
