<?php

namespace App\Http\Requests\InstitucionEducativa;

use App\Models\Telefonos;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTelefonoIERequest extends FormRequest
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
        $telefono = $this->route('telefono');
        $institucione = $this->route('institucione');

        $ieId = $telefono instanceof Telefonos
            ? $telefono->institucionEduc_id
            : (is_object($institucione) ? $institucione->id : $institucione);

        $ignoreId = $telefono instanceof Telefonos ? $telefono->id : null;

        return [
            'operador_id'  => ['nullable', 'integer'],
            'movilFijo'    => ['required', 'string', 'in:M,F'],
            'codigoPais'   => ['nullable', 'string', 'max:5'],
            'numero'       => [
                'required',
                'string',
                'max:20',
                Rule::unique('t_telefonos', 'numero')
                    ->where('institucionEduc_id', $ieId)
                    ->ignore($ignoreId),
            ],
            'imei'         => ['nullable', 'string', 'max:20'],
            'fechaInicio'  => ['sometimes', 'nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'numero.unique' => 'Este número ya está registrado para esta institución.',
        ];
    }

    /** @return array<string, mixed> */
    public function toDTO(): array
    {
        return $this->validated();
    }
}
