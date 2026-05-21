<?php

namespace App\Http\Requests\Configuracion;

use App\DTOs\Configuracion\CreateCondicionLaboralDTO;
use App\Models\Param\ParamRegimenLaboral;
use App\Models\Param\ParamTipoTrabajador;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCondicionLaboralRequest extends FormRequest
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
            'regimenLaboral_id' => ['required', 'integer', Rule::exists(ParamRegimenLaboral::class, 'id')],
            'tipoTrabajador_id' => ['required', 'integer', Rule::exists(ParamTipoTrabajador::class, 'id')],
            'abreviatura' => ['nullable', 'string', 'max:50'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function toDTO(): CreateCondicionLaboralDTO
    {
        return CreateCondicionLaboralDTO::from($this->validated());
    }
}
