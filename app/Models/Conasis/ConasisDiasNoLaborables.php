<?php

namespace App\Models\Conasis;

use App\Models\InstitucionesEduc;
use App\Models\Param\ParamFeriados;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisDiasNoLaborables extends Model
{
    protected $table = 'conasis.t_diasNoLaborables';

    public $timestamps = false;

    protected $fillable = [
        'feriado_id',
        'institucionEduc_id',
        'fecha',
        'observacion',
        'nacionalLocal',
        'recuperable',
        'created_by',
        'activo',
    ];

    public function feriado(): BelongsTo
    {
        return $this->belongsTo(ParamFeriados::class, 'feriado_id');
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(InstitucionesEduc::class, 'institucionEduc_id');
    }
}
