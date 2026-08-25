<?php

namespace App\Http\Requests\Horario;

use App\DTOs\Horario\CreateCargaHorariaDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreCargaHorariaRequest extends FormRequest
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
            'horarioCurso_id' => ['required', 'integer', 'exists:t_horariosCursos,id'],
            'trabajador_id' => ['required', 'integer', 'exists:t_trabajador,id'],
            'altaTrabajador_id' => ['nullable', 'integer', 'exists:t_altasTrabajadores,id'],
            'fechaInicio' => ['nullable', 'date'],
            'fechaFin' => ['nullable', 'date', 'after_or_equal:fechaInicio'],
            'titularSuplencia' => ['nullable', 'string', 'max:1'],
            'turno_id' => ['nullable', 'integer', 'in:1,2,3'],
        ];
    }

    public function toDTO(): CreateCargaHorariaDTO
    {
        return CreateCargaHorariaDTO::from($this->validated());
    }
}
