<?php

namespace App\Http\Requests\Zona;

use App\DTOs\Zona\CreateZonaDTO;
use App\Models\Param\ParamDistritos;
use App\Models\Param\ParamTiposZona;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreZonaRequest extends FormRequest
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
            'tipoZona_id' => ['required', 'integer', Rule::exists(ParamTiposZona::class, 'id')],
            'distrito_id' => ['nullable', 'integer', Rule::exists(ParamDistritos::class, 'id')],
            'nombre' => ['required', 'string', 'max:100'],
            'abreviatura' => ['nullable', 'string', 'max:50'],
            'activo' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'tipoZona_id' => 'tipo de zona',
            'distrito_id' => 'distrito',
            'nombre' => 'nombre',
            'abreviatura' => 'abreviatura',
            'activo' => 'estado activo',
        ];
    }

    public function toDTO(): CreateZonaDTO
    {
        return CreateZonaDTO::from($this->validated());
    }
}
