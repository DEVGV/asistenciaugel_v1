<?php

namespace App\Http\Requests\Tramite;

use App\DTOs\Tramite\UpdateExpedienteDTO;
use App\Enums\TipoExpediente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpedienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'tipoExpediente' => ['required', Rule::enum(TipoExpediente::class)],
            'asunto'         => ['required', 'string', 'max:255'],
            'fecha'          => ['required', 'date'],
            'observacion'    => ['nullable', 'string', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'tipoExpediente.required' => 'El tipo de expediente es obligatorio.',
            'asunto.required'         => 'El asunto del expediente es obligatorio.',
            'fecha.required'          => 'La fecha del expediente es obligatoria.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'tipoExpediente' => 'tipo de expediente',
            'asunto'         => 'asunto',
            'fecha'          => 'fecha',
            'observacion'    => 'observación',
        ];
    }

    public function toDTO(): UpdateExpedienteDTO
    {
        return UpdateExpedienteDTO::from($this->validated());
    }
}
