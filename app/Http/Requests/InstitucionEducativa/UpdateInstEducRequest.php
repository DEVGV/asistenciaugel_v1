<?php

namespace App\Http\Requests\InstitucionEducativa;

use App\DTOs\InstitucionEducativa\UpdateInstEducDTO;
use App\Models\Entidades;
use App\Models\Param\ParamModalidadesForm;
use App\Models\Param\ParamNivelesCiclo;
use App\Models\Param\ParamRegimenEduc;
use App\Models\Param\ParamTipoInstEduc;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstEducRequest extends FormRequest
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
            'entidadUgel_id' => ['nullable', 'integer', Rule::exists(Entidades::class, 'id')],
            'entidadAdmin_id' => ['nullable', 'integer', Rule::exists(Entidades::class, 'id')],
            'codigoInstitucion' => [
                'required', 'string', 'max:20',
                Rule::unique('t_institucionesEduc', 'codigoInstitucion')
                    ->ignore($this->route('institucione')),
            ],
            'codigoModular' => ['nullable', 'string', 'max:20'],
            'nombreLegal' => ['required', 'string', 'max:255'],
            'regimenEduc_id' => ['nullable', 'integer', Rule::exists(ParamRegimenEduc::class, 'id')],
            'tipoInstEduc_id' => ['nullable', 'integer', Rule::exists(ParamTipoInstEduc::class, 'id')],
            'modalidadFormativa_id' => ['required', 'integer', Rule::exists(ParamModalidadesForm::class, 'id')],
            'nivelCiclo_id' => ['required', 'integer', Rule::exists(ParamNivelesCiclo::class, 'id')],
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
            'codigoInstitucion.required' => 'El código de institución es obligatorio.',
            'codigoInstitucion.unique' => 'Ya existe una institución con este código.',
            'nombreLegal.required' => 'El nombre legal es obligatorio.',
            'fechaFin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ];
    }

    public function toDTO(): UpdateInstEducDTO
    {
        return UpdateInstEducDTO::from($this->validated());
    }
}
