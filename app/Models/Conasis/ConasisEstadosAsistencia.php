<?php

namespace App\Models\Conasis;

use App\Models\Param\ParamEstadosAsis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisEstadosAsistencia extends Model
{
    protected $table = 'conasis.t_estadosAsistencia';

    public $timestamps = false;

    protected $fillable = [
        'asistencia_id',
        'estadoAsis_id',
        'observacion',
        'created_by',
    ];

    public function estadoAsis(): BelongsTo
    {
        return $this->belongsTo(ParamEstadosAsis::class, 'estadoAsis_id');
    }

    public function asistencia(): BelongsTo
    {
        return $this->belongsTo(ConasisAsistencia::class, 'asistencia_id');
    }
}
