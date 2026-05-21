<?php

namespace App\Models;

use App\Models\Param\ParamMotivoDeBajas;
use App\Models\Param\ParamRolTrabajador;
use App\Models\Param\ParamSituacionLaboral;
use App\Models\Param\ParamTipoContrato;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AltasTrabajadores extends Model
{
    protected $table = 't_altasTrabajadores';

    public $timestamps = false;

    protected $fillable = [
        'codigoAirsp',
        'trabajador_id',
        'fechaInicio',
        'fechaFin',
        'fechaAlta',
        'condicionLaboral_id',
        'tipoContrato_id',
        'institucionEducativa_id',
        'rolTrabajador_id',
        'area_id',
        'cargo_id',
        'situacionLaboral_id',
        'observacion',
        'fechaBaja',
        'motivoBaja_id',
        'created_by',
    ];

    public function rolTrabajador(): BelongsTo
    {
        return $this->belongsTo(ParamRolTrabajador::class, 'rolTrabajador_id');
    }

    public function tipoContrato(): BelongsTo
    {
        return $this->belongsTo(ParamTipoContrato::class, 'tipoContrato_id');
    }

    public function situacionLaboral(): BelongsTo
    {
        return $this->belongsTo(ParamSituacionLaboral::class, 'situacionLaboral_id');
    }

    public function motivoBaja(): BelongsTo
    {
        return $this->belongsTo(ParamMotivoDeBajas::class, 'motivoBaja_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Areas::class, 'area_id');
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargos::class, 'cargo_id');
    }

    public function condicionLaboral(): BelongsTo
    {
        return $this->belongsTo(CondicionesLaborales::class, 'condicionLaboral_id');
    }

    public function institucionEducativa(): BelongsTo
    {
        return $this->belongsTo(InstitucionesEduc::class, 'institucionEducativa_id');
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
}
