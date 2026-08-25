<?php

namespace App\Models\Conasis;

use App\Models\AltasTrabajadores;
use App\Models\InstitucionesEduc;
use App\Models\Param\ParamEstadosAsis;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisAsistencia extends Model
{
    protected $table = 'conasis.t_asistencia';

    public $timestamps = false;

    protected $fillable = [
        'institucionEduc_id',
        'trabajador_id',
        'altaTrabajador_id',
        'anio',
        'mes',
        'ndias_asis',
        'nhoras_crono',
        'nhoras_acad',
        'ndias_perm',
        'nhoras_perm',
        'nminu_perm',
        'ndias_falt',
        'ndias_tarde',
        'nhoras_tarde',
        'nminu_tarde',
        'ndias_extra',
        'nhoras_extra',
        'nnimu_extra',
        'fechaDesde',
        'fechaHasta',
        'estadoUltim_id',
        'created_by',
    ];

    public function estadoUltim(): BelongsTo
    {
        return $this->belongsTo(ParamEstadosAsis::class, 'estadoUltim_id');
    }

    public function altaTrabajador(): BelongsTo
    {
        return $this->belongsTo(AltasTrabajadores::class, 'altaTrabajador_id');
    }

    public function institucionEduc(): BelongsTo
    {
        return $this->belongsTo(InstitucionesEduc::class, 'institucionEduc_id');
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
}
