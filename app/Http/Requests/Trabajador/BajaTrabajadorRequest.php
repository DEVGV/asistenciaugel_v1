<?php

namespace App\Http\Requests\Trabajador;

use App\Models\AltasTrabajadores;
use App\Models\Param\ParamMotivoDeBajas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BajaTrabajadorRequest extends FormRequest
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
        /** @var AltasTrabajadores $alta */
        $alta = $this->route('alta');

        return [
            'fechaBaja' => [
                'required',
                'date',
                "after_or_equal:{$alta->fechaInicio}",
            ],
            'motivoBaja_id' => [
                'required',
                'integer',
                Rule::exists(ParamMotivoDeBajas::class, 'id'),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fechaBaja.required' => 'La fecha de baja es obligatoria.',
            'fechaBaja.date' => 'La fecha de baja debe ser una fecha válida.',
            'fechaBaja.after_or_equal' => 'La fecha de baja no puede ser anterior a la fecha de inicio del alta.',
            'motivoBaja_id.required' => 'El motivo de baja es obligatorio.',
            'motivoBaja_id.exists' => 'El motivo de baja seleccionado no existe.',
        ];
    }
}
