<?php

namespace App\Http\Requests\Tramite;

use App\DTOs\Tramite\CreateExpedienteDTO;
use App\Enums\TipoExpediente;
use App\Models\Param\ParamDocumentos;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class StoreExpedienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'tipoExpediente'             => ['required', Rule::enum(TipoExpediente::class)],
            'trabajador_id'              => ['required', 'integer', 'exists:t_trabajador,id'],
            'altaTrabajador_id'          => ['nullable', 'integer', 'exists:t_altasTrabajadores,id'],
            'asunto'                     => ['required', 'string', 'max:255'],
            'fecha'                      => ['required', 'date'],
            'observacion'                => ['nullable', 'string', 'max:255'],
            'documentos'                 => ['required', 'array', 'min:1'],
            'documentos.*.documento_id'  => ['required', 'integer', Rule::exists(ParamDocumentos::class, 'id')],
            'documentos.*.nroDoc'        => ['nullable', 'string', 'max:150'],
            'documentos.*.fechaDoc'      => ['nullable', 'date'],
            'documentos.*.observacion'   => ['nullable', 'string', 'max:255'],
            'documentos.*.archivo'       => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'tipoExpediente.required'            => 'El tipo de expediente es obligatorio.',
            'trabajador_id.required'             => 'El trabajador es obligatorio.',
            'trabajador_id.exists'               => 'El trabajador seleccionado no existe.',
            'altaTrabajador_id.exists'           => 'El alta seleccionada no existe.',
            'asunto.required'                    => 'El asunto del expediente es obligatorio.',
            'fecha.required'                     => 'La fecha del expediente es obligatoria.',
            'documentos.required'                => 'Debe registrar al menos un documento.',
            'documentos.min'                     => 'Debe registrar al menos un documento.',
            'documentos.*.documento_id.required' => 'El tipo de documento es obligatorio.',
            'documentos.*.documento_id.exists'   => 'El tipo de documento seleccionado no existe.',
            'documentos.*.archivo.mimes'         => 'El archivo debe ser PDF, JPG, PNG, DOC o DOCX.',
            'documentos.*.archivo.max'           => 'El archivo no debe superar los 10 MB.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'tipoExpediente'    => 'tipo de expediente',
            'trabajador_id'     => 'trabajador',
            'altaTrabajador_id' => 'alta del trabajador',
            'asunto'            => 'asunto',
            'fecha'             => 'fecha',
            'observacion'       => 'observación',
            'documentos'        => 'documentos',
        ];
    }

    public function toDTO(): CreateExpedienteDTO
    {
        // Excluir 'archivo' del array validado antes de pasarlo al DTO
        $data = $this->validated();
        $data['documentos'] = array_map(
            fn (array $d) => collect($d)->except('archivo')->all(),
            $data['documentos'] ?? [],
        );

        return CreateExpedienteDTO::from($data);
    }

    /**
     * Retorna los archivos por índice de documento (null si no se adjuntó).
     *
     * @return array<int, UploadedFile|null>
     */
    public function getArchivos(): array
    {
        $docs = $this->file('documentos') ?? [];

        return array_map(
            fn (array $d) => $d['archivo'] instanceof UploadedFile ? $d['archivo'] : null,
            $docs,
        );
    }
}
