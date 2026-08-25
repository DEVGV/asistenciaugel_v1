<?php

namespace App\Http\Requests\Horario;

use Illuminate\Foundation\Http\FormRequest;

class StoreHorarioMasivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>|string> */
    public function rules(): array
    {
        return [
            'filas' => ['required', 'array', 'min:1', 'max:1000'],
            'filas.*.id' => ['nullable', 'integer'],
            'filas.*.seccion_id' => ['required', 'integer', 'exists:t_seccionesIE,id'],
            'filas.*.anio' => ['required', 'integer', 'min:2000', 'max:2100'],
            'filas.*.curso_id' => ['required', 'integer', 'exists:t_cursosIE,id'],
            'filas.*.nroDia' => ['required', 'integer', 'min:1', 'max:7'],
            'filas.*.turno_id' => ['nullable', 'integer', 'in:1,2,3'],
            'filas.*.diaSemana' => ['nullable', 'string', 'max:1'],
            'filas.*.horaInicio' => ['required', 'string'],
            'filas.*.horaFin' => ['required', 'string'],
            'filas.*.trabajador_id' => ['nullable', 'integer'],
            'filas.*.altaTrabajador_id' => ['nullable', 'integer'],
            'filas.*.titularSuplencia' => ['nullable', 'string', 'max:1', 'in:T,S'],
            'filas.*.fechaInicioDocente' => ['nullable', 'date'],
            'filas.*.fechaFinDocente' => ['nullable', 'date'],
            'filas.*.carga_id' => ['nullable', 'integer'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'filas.required' => 'Debe enviar al menos una fila.',
            'filas.*.seccion_id.required' => 'La sección es obligatoria.',
            'filas.*.seccion_id.exists' => 'La sección seleccionada no existe.',
            'filas.*.anio.required' => 'El año lectivo es obligatorio.',
            'filas.*.curso_id.required' => 'El curso es obligatorio.',
            'filas.*.curso_id.exists' => 'El curso seleccionado no existe.',
            'filas.*.nroDia.required' => 'El día de la semana es obligatorio.',
            'filas.*.nroDia.min' => 'El día debe ser entre 1 (Lunes) y 7 (Domingo).',
            'filas.*.nroDia.max' => 'El día debe ser entre 1 (Lunes) y 7 (Domingo).',
            'filas.*.horaInicio.required' => 'La hora de inicio es obligatoria.',
            'filas.*.horaFin.required' => 'La hora de fin es obligatoria.',
            'filas.*.titularSuplencia.in' => 'La condición debe ser T (Titular) o S (Suplente).',
        ];
    }

    /**
     * Validar que horaFin sea posterior a horaInicio en cada fila.
     */
    public function withValidator(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Contracts\Validation\Validator $v) {
            $filas = $this->input('filas', []);
            foreach ($filas as $i => $fila) {
                $inicio = $fila['horaInicio'] ?? null;
                $fin = $fila['horaFin'] ?? null;
                if ($inicio && $fin && $fin <= $inicio) {
                    $v->errors()->add("filas.{$i}.horaFin", 'La hora de fin debe ser posterior a la hora de inicio.');
                }
            }
        });
    }

    /** @return array<int, array<string, mixed>> Filas validadas listas para el Service */
    public function filas(): array
    {
        return $this->validated()['filas'];
    }
}
