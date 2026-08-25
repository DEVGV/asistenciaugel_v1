<?php

namespace App\Http\Requests\InstitucionEducativa;

use App\Models\Emails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmailIERequest extends FormRequest
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
        $institucione = $this->route('institucione');

        $ieId = $email instanceof Emails
            ? $email->institucionEduc_id
            : (is_object($institucione) ? $institucione->id : $institucione);

        $ignoreId = $email instanceof Emails ? $email->id : null;

        return [
            'email'       => [
                'required',
                'email',
                'max:255',
                Rule::unique('t_emails', 'email')
                    ->where('institucionEduc_id', $ieId)
                    ->ignore($ignoreId),
            ],
            'personalInst' => ['required', 'string', 'in:P,I'],
            'fechaInicio'  => ['sometimes', 'nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo ya está registrado para esta institución.',
        ];
    }

    /** @return array<string, mixed> */
    public function toDTO(): array
    {
        return $this->validated();
    }
}
