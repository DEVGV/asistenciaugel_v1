<?php

namespace App\Http\Requests\Persona;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomicilioRequest extends FormRequest
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
            'domicilio' => ['required', 'string', 'max:500'],
            'zona_id' => ['nullable', 'integer'],
            'ubigeo' => ['nullable', 'string', 'max:6'],
            'fechaInicio' => ['sometimes', 'nullable', 'date'],
        ];
    }

    /** @return array<string, mixed> */
    public function toDTO(): array
    {
        return $this->validated();
    }
}
