<?php

namespace App\Http\Requests\Trabajador;

use App\DTOs\Trabajador\UpdateTrabajadorDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrabajadorRequest extends FormRequest
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
            'activo' => ['boolean'],
        ];
    }

    public function toDTO(): UpdateTrabajadorDTO
    {
        return UpdateTrabajadorDTO::from($this->validated());
    }
}
