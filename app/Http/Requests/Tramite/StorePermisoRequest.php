<?php

namespace App\Http\Requests\Tramite;

use App\DTOs\Tramite\CreatePermisoDTO;
use App\Enums\TipoPermiso;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePermisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'trabajador_id'     => ['required', 'integer', 'exists:t_trabajador,id'],
            'altaTrabajador_id' => ['required', 'integer', 'exists:t_altasTrabajadores,id'],
            'tipo'              => ['required', Rule::enum(TipoPermiso::class)],
            'asunto'            => ['required', 'string', 'max:255'],
            'fechaInicio'       => ['required', 'date'],
            'fechaFin'          => ['required', 'date', 'after_or_equal:fechaInicio'],
            'marcaApli'         => ['nullable', 'required_if:tipo,'.TipoPermiso::Exoneracion->value, 'string', 'in:E,S,ES'],
            'turno'             => ['nullable', 'integer', 'exists:param.t00_turnos,id'],
            'documento_id'      => ['required', 'integer', 'exists:param.t00_documentos,id'],
            'nroDoc'            => ['nullable', 'string', 'max:150'],
            'observacion'       => ['nullable', 'string', 'max:255'],
            'sustento'          => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'trabajador_id.required'     => 'El trabajador es obligatorio.',
            'altaTrabajador_id.required' => 'Debe seleccionar el alta (vínculo laboral) del trabajador.',
            'tipo.required'              => 'Debe seleccionar el tipo de permiso.',
            'asunto.required'            => 'El motivo del permiso es obligatorio.',
            'fechaInicio.required'       => 'La fecha de inicio es obligatoria.',
            'fechaFin.required'          => 'La fecha de fin es obligatoria.',
            'fechaFin.after_or_equal'    => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'marcaApli.required_if'      => 'Debe indicar la marca a exonerar (entrada, salida o ambas).',
            'documento_id.required'      => 'Debe seleccionar el tipo de documento de sustento.',
            'sustento.required'          => 'Debe adjuntar el documento de sustento.',
            'sustento.mimes'             => 'El sustento debe ser un archivo PDF, JPG o PNG.',
            'sustento.max'               => 'El sustento no debe superar los 5 MB.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'trabajador_id'     => 'trabajador',
            'altaTrabajador_id' => 'alta del trabajador',
            'tipo'              => 'tipo de permiso',
            'asunto'            => 'motivo',
            'fechaInicio'       => 'fecha de inicio',
            'fechaFin'          => 'fecha de fin',
            'marcaApli'         => 'marca aplicable',
            'turno'             => 'turno',
            'documento_id'      => 'tipo de documento',
            'nroDoc'            => 'número de documento',
            'observacion'       => 'observación',
            'sustento'          => 'documento de sustento',
        ];
    }

    public function toDTO(): CreatePermisoDTO
    {
        return CreatePermisoDTO::from(collect($this->validated())->except('sustento')->all());
    }
}
