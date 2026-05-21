<?php

namespace App\Http\Requests\Configuracion;

use App\DTOs\Configuracion\UpdateAreaDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAreaRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:255'],
            'sigla' => ['nullable', 'string', 'max:50'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'rolTrabajador_id' => ['nullable', 'integer'],
            'activo' => ['boolean'],
        ];
    }

    public function toDTO(): UpdateAreaDTO
    {
        return UpdateAreaDTO::from($this->validated());
    }
}
