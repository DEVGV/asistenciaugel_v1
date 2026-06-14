<?php

namespace App\Http\Requests\Persona;

use App\Models\Emails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmailRequest extends FormRequest
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
        $email = $this->route('email');
        $persona = $this->route('persona');

        $personaId = $email instanceof Emails
            ? $email->persona_id
            : (is_object($persona) ? $persona->id : $persona);

        $ignoreId = $email instanceof Emails ? $email->id : null;

        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('t_emails', 'email')
                    ->where('persona_id', $personaId)
                    ->ignore($ignoreId),
            ],
            'personalInst' => ['required', 'string', 'in:P,I'],
            'fechaInicio' => ['sometimes', 'nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo ya está registrado para esta persona.',
        ];
    }

    /** @return array<string, mixed> */
    public function toDTO(): array
    {
        return $this->validated();
    }
}
