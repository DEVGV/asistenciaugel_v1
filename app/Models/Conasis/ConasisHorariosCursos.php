<?php

namespace App\Models\Conasis;

use App\Models\CursosIE;
use App\Models\SeccionesIE;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConasisHorariosCursos extends Model
{
    protected $table = 'conasis.t_horariosCursos';

    public $timestamps = false;

    protected $fillable = [
        'anio',
        'seccion_id',
        'curso_id',
        'diaSemana',
        'nroDia',
        'horaInicio',
        'horaFin',
        'minAcum',
        'created_by',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(CursosIE::class, 'curso_id');
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(SeccionesIE::class, 'seccion_id');
    }

    public function cargas(): HasMany
    {
        return $this->hasMany(ConasisCargaHoraria::class, 'horarioCurso_id');
    }

    /** Detalles de horario donde este curso es el de inicio del día */
    public function detallesIni(): HasMany
    {
        return $this->hasMany(ConasisDetalleHorarios::class, 'horarioCursoIni_id');
    }
}
