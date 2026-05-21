<?php

namespace App\Models\Conasis;

use App\Models\Param\ParamEstadosTram;
use App\Models\Trabajador;
use App\Traits\HasCodigo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisExpediente extends Model
{
    use HasCodigo;

    protected $table = 'conasis.t_expediente';

    public $timestamps = false;

    protected string $codigoPrefix = 'EXP';

    protected $fillable = [
        'anio',
        'trabajador_id',
        'asunto',
        'fecha',
        'observacion',
        'estado_id',
        'created_by',
    ];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(ParamEstadosTram::class, 'estado_id');
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
}
