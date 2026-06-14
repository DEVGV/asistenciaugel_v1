<?php

namespace App\Http\Requests\Horario;

use App\DTOs\Horario\UpdateHorarioCursoDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHorarioCursoRequest extends FormRequest
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
            'anio' => ['required', 'integer', 'min:2000', 'max:2100'],
            'seccion_id' => ['required', 'integer', 'exists:t_seccionesIE,id'],
            'curso_id' => ['required', 'integer', 'exists:t_cursosIE,id'],
            'diaSemana' => ['required', 'string', 'max:1'],
            'nroDia' => ['required', 'integer', 'min:1', 'max:7'],
            'horaInicio' => ['required', 'string'],
            'horaFin' => ['required', 'string'],
            'minAcum' => ['nullable', 'numeric'],
        ];
    }

    public function toDTO(): UpdateHorarioCursoDTO
    {
        return UpdateHorarioCursoDTO::from($this->validated());
    }
}
