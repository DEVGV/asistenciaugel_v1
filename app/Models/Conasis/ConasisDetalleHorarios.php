<?php

namespace App\Models\Conasis;

use App\Models\Param\ParamTurnos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisDetalleHorarios extends Model
{
    protected $table = 'conasis.t_detalleHorarios';

    public $timestamps = false;

    protected $fillable = [
        'horarioTrabajador_id',
        'turno_id',
        'nombreTurno',
        'nroTurno',
        'diaSemana',
        'nroDia',
        'horarioCursoIni_id',
        'entDiaInicio',
        'entDiaFin',
        'entHoraInicio',
        'entHoraFin',
        'entTolerancia',
        'horarioCursoFin_id',
        'salDiaInicio',
        'salDiaFin',
        'salHoraInicio',
        'salHoraFin',
        'salTolerancia',
        'horaAcumula',
        'aplicar',
        'created_by',
    ];

    public function turno(): BelongsTo
    {
        return $this->belongsTo(ParamTurnos::class, 'turno_id');
    }

    public function horarioCursoIni(): BelongsTo
    {
        return $this->belongsTo(ConasisHorariosCursos::class, 'horarioCursoIni_id');
    }

    public function horarioTrabajador(): BelongsTo
    {
        return $this->belongsTo(ConasisHorariosTrabajador::class, 'horarioTrabajador_id');
    }
}
