<?php

namespace App\Models\Conasis;

use App\Models\AltasTrabajadores;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisAmpliaDeduceHoras extends Model
{
    protected $table = 'conasis.t_ampliaDeduceHoras';

    public $timestamps = false;

    protected $fillable = [
        'trabajador_id',
        'altaTrabajador_id',
        'marcaApli',
        'fechaInicio',
        'fechaFin',
        'turno',
        'horaIngreso',
        'horaSalida',
        'toleranciaPre',
        'toleranciaPost',
        'expediente_id',
        'observacion',
        'estado_id',
        'created_by',
    ];

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
