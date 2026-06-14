<?php

namespace App\Http\Requests\Trabajador;

use App\DTOs\Trabajador\CreateAltaTrabajadorDTO;
use App\Models\Areas;
use App\Models\Cargos;
use App\Models\CondicionesLaborales;
use App\Models\InstitucionesEduc;
use App\Models\Param\ParamRolTrabajador;
use App\Models\Param\ParamSituacionLaboral;
use App\Models\Param\ParamTipoContrato;
use App\Models\Auth\Perfil;
use App\Models\Trabajador;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAltaTrabajadorRequest extends FormRequest
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
        $altaId = $this->route('alta')?->id;

        return [
            'trabajador_id' => [
                'required',
                'integer',
                Rule::exists(Trabajador::class, 'id'),
            ],
            'institucionEducativa_id' => [
                'required',
                'integer',
                Rule::exists(InstitucionesEduc::class, 'id'),
            ],
            'condicionLaboral_id' => [
                'required',
                'integer',
                Rule::exists(CondicionesLaborales::class, 'id'),
            ],
            'tipoContrato_id' => [
                'required',
                'integer',
                Rule::exists(ParamTipoContrato::class, 'id'),
            ],
            'rolTrabajador_id' => [
                'required',
                'integer',
                Rule::exists(ParamRolTrabajador::class, 'id'),
            ],
            'situacionLaboral_id' => [
                'required',
                'integer',
                Rule::exists(ParamSituacionLaboral::class, 'id'),
            ],
            'area_id' => [
                'required',
                'integer',
                Rule::exists(Areas::class, 'id'),
            ],
            'cargo_id' => [
                'required',
                'integer',
                Rule::exists(Cargos::class, 'id'),
            ],
            'codigoAirsp' => ['nullable', 'string', 'max:50'],
            'fechaInicio' => ['required', 'date'],
            'fechaFin' => ['nullable', 'date', 'after_or_equal:fechaInicio'],
            'fechaAlta' => ['nullable', 'date'],
            'observacion' => ['nullable', 'string', 'max:500'],
            'perfil_id' => ['nullable', 'integer', Rule::exists(Perfil::class, 'id')],
            'localInstEduc_id' => [
                'nullable',
                'integer',
                Rule::exists('conasis.t_localesInstEduc', 'id')
                    ->where('institucionEduc_id', $this->input('institucionEducativa_id')),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'trabajador_id.required' => 'El trabajador es obligatorio.',
            'trabajador_id.exists' => 'El trabajador seleccionado no existe.',
            'institucionEducativa_id.required' => 'La institución educativa es obligatoria.',
            'institucionEducativa_id.exists' => 'La institución educativa seleccionada no existe.',
            'condicionLaboral_id.required' => 'La condición laboral es obligatoria.',
            'tipoContrato_id.required' => 'El tipo de contrato es obligatorio.',
            'rolTrabajador_id.required' => 'El rol del trabajador es obligatorio.',
            'situacionLaboral_id.required' => 'La situación laboral es obligatoria.',
            'fechaInicio.required' => 'La fecha de inicio es obligatoria.',
            'fechaInicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fechaFin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'localInstEduc_id.exists' => 'El local de marcación seleccionado no pertenece a la institución educativa.',
        ];
    }

    public function toDTO(): CreateAltaTrabajadorDTO
    {
        return CreateAltaTrabajadorDTO::from($this->validated());
    }
}
