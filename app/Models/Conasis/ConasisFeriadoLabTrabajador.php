<?php

namespace App\Models\Conasis;

use App\Models\AltasTrabajadores;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisFeriadoLabTrabajador extends Model
{
    protected $table = 'conasis.t_feriadoLabTrabajador';

    public $timestamps = false;

    protected $fillable = [
        'diaNoLaborable_id',
        'trabajador_id',
        'altaTrabajador_id',
        'fechaInicio',
        'fechaFin',
        'observacion',
        'expediente_id',
        'created_by',
    ];

    public function altaTrabajador(): BelongsTo
    {
        return $this->belongsTo(AltasTrabajadores::class, 'altaTrabajador_id');
    }

    public function diaNoLaborable(): BelongsTo
    {
        return $this->belongsTo(ConasisDiasNoLaborables::class, 'diaNoLaborable_id');
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
