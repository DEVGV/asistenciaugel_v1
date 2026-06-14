<?php

namespace App\Http\Requests\Trabajador;

use App\Models\Auth\Perfil;
use App\Models\Param\ParamSexos;
use App\Models\Param\ParamTipoDocIdentidad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistroTrabajadorRequest extends FormRequest
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
        $rules = [
            // ── Datos de Persona ──
            'tipoDocIdentidad_id' => ['required', 'integer', Rule::exists(ParamTipoDocIdentidad::class, 'id')],
            'docIdentidad' => ['required', 'string', 'max:20', 'unique:t_personas,docIdentidad'],
            'paterno' => ['required', 'string', 'max:100'],
            'materno' => ['required', 'string', 'max:100'],
            'nombre' => ['required', 'string', 'max:150'],
            'sexo_id' => ['required', 'integer', Rule::exists(ParamSexos::class, 'id')],
            'fechaNac' => ['nullable', 'date'],
            'pais_id' => ['required', 'integer'],
            'ubigeo' => ['nullable', 'string', 'max:20'],

            // ── Flag para incluir alta ──
            'incluir_alta' => ['boolean'],

            // ── Datos de Alta (condicionales) ──
            'institucionEducativa_id' => ['required_if:incluir_alta,true', 'nullable', 'integer', 'exists:t_institucionesEduc,id'],
            'condicionLaboral_id' => ['required_if:incluir_alta,true', 'nullable', 'integer', 'exists:t_condicionesLaborales,id'],
            'tipoContrato_id' => ['required_if:incluir_alta,true', 'nullable', 'integer', 'exists:param.t12_tipoContrato,id'],
            'rolTrabajador_id' => ['nullable', 'integer', 'exists:param.t00_rolTrabajador,id'],
            'situacionLaboral_id' => ['required_if:incluir_alta,true', 'nullable', 'integer', 'exists:param.t15_situacionLaboral,id'],
            'area_id' => ['required_if:incluir_alta,true', 'nullable', 'integer', 'exists:t_areas,id'],
            'cargo_id' => ['required_if:incluir_alta,true', 'nullable', 'integer', 'exists:t_cargos,id'],
            'codigoAirsp' => ['nullable', 'string', 'max:20'],
            'fechaInicio' => ['required_if:incluir_alta,true', 'nullable', 'date'],
            'fechaFin' => ['nullable', 'date', 'after_or_equal:fechaInicio'],
            'fechaAlta' => ['nullable', 'date'],
            'observacion' => ['nullable', 'string', 'max:200'],

            // ── Perfil (solo si hay alta) ──
            'perfil_id' => ['nullable', 'integer', Rule::exists(Perfil::class, 'id')],

            // ── Local de marcación (solo si hay alta) ──
            'localInstEduc_id' => [
                'nullable',
                'integer',
                Rule::exists('conasis.t_localesInstEduc', 'id')
                    ->where('institucionEduc_id', $this->input('institucionEducativa_id')),
            ],
        ];

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'docIdentidad.unique' => 'Ya existe una persona con este documento de identidad.',
            'institucionEducativa_id.required_if' => 'La institución educativa es requerida para registrar el alta.',
            'condicionLaboral_id.required_if' => 'La condición laboral es requerida para registrar el alta.',
            'tipoContrato_id.required_if' => 'El tipo de contrato es requerido para registrar el alta.',
            'situacionLaboral_id.required_if' => 'La situación laboral es requerida para registrar el alta.',
            'area_id.required_if' => 'El área es requerida para registrar el alta.',
            'cargo_id.required_if' => 'El cargo es requerido para registrar el alta.',
            'fechaInicio.required_if' => 'La fecha de inicio es requerida para registrar el alta.',
            'localInstEduc_id.exists' => 'El local de marcación seleccionado no pertenece a la institución educativa.',
        ];
    }
}
