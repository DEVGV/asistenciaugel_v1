<?php

namespace App\Models\Conasis;

use App\Models\AltasTrabajadores;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisJustificaciones extends Model
{
    protected $table = 'conasis.t_justificaciones';

    public $timestamps = false;

    protected $fillable = [
        'trabajador_id',
        'altaTrabajador_id',
        'turno',
        'fechaInicio',
        'fechaFin',
        'marcaApli',
        'expediente_id',
        'observacion',
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
