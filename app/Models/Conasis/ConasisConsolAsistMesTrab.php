<?php

namespace App\Models\Conasis;

use App\Models\Param\ParamMotivosSuspLab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisConsolAsistMesTrab extends Model
{
    protected $table = 'conasis.t_consolAsistMesTrab';

    public $timestamps = false;

    protected $fillable = [
        'asistencia_id',
        'motivoSuspLab_id',
        'sigla',
        'siglaPers',
        'ndias',
        'remunerado',
        'localInstEduc_id',
        'turno_id',
        'nroTurno',
        'asusfal',
        'aniopla',
        'mespla',
        'ndiaspla',
        'created_by',
        'createdenv_at',
        'createdenv_by',
        'estadoUltim_id',
    ];

    public function motivoSuspLab(): BelongsTo
    {
        return $this->belongsTo(ParamMotivosSuspLab::class, 'motivoSuspLab_id');
    }

    public function asistencia(): BelongsTo
    {
        return $this->belongsTo(ConasisAsistencia::class, 'asistencia_id');
    }
}
