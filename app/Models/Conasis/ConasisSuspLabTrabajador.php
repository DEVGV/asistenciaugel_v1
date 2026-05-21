<?php

namespace App\Models\Conasis;

use App\Models\AltasTrabajadores;
use App\Models\Param\ParamMotivosSuspLab;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisSuspLabTrabajador extends Model
{
    protected $table = 'conasis.t_suspLabTrabajador';

    public $timestamps = false;

    protected $fillable = [
        'trabajador_id',
        'altaTrabajador_id',
        'motivoSuspLab_id',
        'fechaHoraInicio',
        'fechaHoraFin',
        'fechaRegistro',
        'totalDias',
        'totalHoras',
        'marcaApli',
        'observacion',
        'expediente_id',
        'created_by',
    ];

    public function motivoSuspLab(): BelongsTo
    {
        return $this->belongsTo(ParamMotivosSuspLab::class, 'motivoSuspLab_id');
    }

    public function altaTrabajador(): BelongsTo
    {
        return $this->belongsTo(AltasTrabajadores::class, 'altaTrabajador_id');
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(ConasisExpediente::class, 'expediente_id');
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
}
