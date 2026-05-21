<?php

namespace App\Models\Conasis;

use App\Models\AltasTrabajadores;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisCargaHoraria extends Model
{
    protected $table = 'conasis.t_cargaHoraria';

    public $timestamps = false;

    protected $fillable = [
        'horarioCurso_id',
        'trabajador_id',
        'altaTrabajador_id',
        'fechaInicio',
        'fechaFin',
        'titularSuplencia',
        'created_by',
    ];

    public function altaTrabajador(): BelongsTo
    {
        return $this->belongsTo(AltasTrabajadores::class, 'altaTrabajador_id');
    }

    public function horarioCurso(): BelongsTo
    {
        return $this->belongsTo(ConasisHorariosCursos::class, 'horarioCurso_id');
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
}
