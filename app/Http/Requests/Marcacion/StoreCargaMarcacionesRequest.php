<?php

namespace App\Http\Requests\Marcacion;

use Illuminate\Foundation\Http\FormRequest;

class StoreCargaMarcacionesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>|string> */
    public function rules(): array
    {
        return [
            'archivo'    => [
                'required',
                'file',
                'max:10240',
                function ($attribute, $value, $fail) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (! in_array($ext, ['xlsx', 'xls', 'csv'])) {
                        $fail('El archivo debe ser formato Excel (.xlsx, .xls) o CSV.');
                    }
                },
            ],
            'reloj_id'   => ['required', 'integer', 'exists:t_relojes,id'],
            'overwrite'  => ['sometimes', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'archivo.required' => 'Debe seleccionar un archivo Excel.',
            'archivo.mimes'    => 'El archivo debe ser formato Excel (.xlsx o .xls).',
            'archivo.max'      => 'El archivo no debe superar los 10 MB.',
            'reloj_id.required' => 'Debe seleccionar un reloj.',
            'reloj_id.exists'   => 'El reloj seleccionado no existe.',
        ];
    }
}
