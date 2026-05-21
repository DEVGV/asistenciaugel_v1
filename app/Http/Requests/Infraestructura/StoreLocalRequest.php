<?php

namespace App\Http\Requests\Infraestructura;

use App\DTOs\Infraestructura\CreateLocalDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocalRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:200'],
            'domicilio' => ['nullable', 'string', 'max:200'],
            'zona_id' => ['nullable', 'integer', 'exists:t_zonas,id'],
            'ubigeo' => ['nullable', 'string', 'max:20'],
            'utm_huso' => ['nullable', 'numeric'],
            'utm_banda' => ['nullable', 'string', 'max:10'],
            'utm_x_este' => ['nullable', 'numeric'],
            'utm_y_norte' => ['nullable', 'numeric'],
            'activo' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del local es obligatorio.',
            'nombre.max' => 'El nombre no debe exceder los 200 caracteres.',
            'domicilio.max' => 'El domicilio no debe exceder los 200 caracteres.',
            'zona_id.exists' => 'La zona seleccionada no existe en el sistema.',
            'ubigeo.max' => 'El ubigeo no debe exceder los 20 caracteres.',
            'utm_huso.numeric' => 'El huso UTM debe ser un valor numérico.',
            'utm_x_este.numeric' => 'La coordenada UTM X (Este) debe ser un valor numérico.',
            'utm_y_norte.numeric' => 'La coordenada UTM Y (Norte) debe ser un valor numérico.',
        ];
    }

    public function toDTO(): CreateLocalDTO
    {
        return CreateLocalDTO::from($this->validated());
    }
}
