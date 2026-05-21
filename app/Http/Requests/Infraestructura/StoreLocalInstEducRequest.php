<?php

namespace App\Http\Requests\Infraestructura;

use App\DTOs\Infraestructura\CreateLocalDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocalInstEducRequest extends FormRequest
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
            'fechaInicio' => ['nullable', 'date'],
            'fechaFin' => ['nullable', 'date', 'after_or_equal:fechaInicio'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del local es obligatorio.',
            'nombre.max' => 'El nombre no debe superar los 200 caracteres.',
            'domicilio.max' => 'El domicilio no debe superar los 200 caracteres.',
            'zona_id.exists' => 'La zona seleccionada no existe.',
            'fechaFin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }

    public function toLocalDTO(): CreateLocalDTO
    {
        return CreateLocalDTO::from($this->validated());
    }
}
