<?php

namespace App\Models;

use App\Models\Conasis\ConasisLocalesMarcacion;
use App\Models\Param\ParamMotivoDeBajas;
use App\Models\Param\ParamRolTrabajador;
use App\Models\Param\ParamSituacionLaboral;
use App\Models\Param\ParamTipoContrato;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * Local de marcación vigente asociado a esta alta (el más reciente).
     */
    public function localMarcacion(): HasOne
    {
        return $this->hasOne(ConasisLocalesMarcacion::class, 'altaTrabajador_id')->latestOfMany('id');
    }

    /**
     * Todos los locales de marcación asociados a esta alta.
     */
    public function localesMarcacion(): HasMany
    {
        return $this->hasMany(ConasisLocalesMarcacion::class, 'altaTrabajador_id');
    }

    /**
     * Verifica si ya existe un alta activa (sin baja) que se solape
     * en fechas para el mismo trabajador en la misma IE.
     *
     * Dos intervalos [s1,e1] y [s2,e2] se solapan si s1 <= e2 AND s2 <= e1.
     * Un extremo null se trata como infinito (período abierto).
     */
    public static function tieneAltaSolapada(
        int $trabajadorId,
        int $ieId,
        string $fechaInicio,
        ?string $fechaFin,
        ?int $excluirAltaId = null,
    ): bool {
        return self::where('trabajador_id', $trabajadorId)
            ->where('institucionEducativa_id', $ieId)
            ->whereNull('fechaBaja')
            ->when($excluirAltaId, fn ($q) => $q->where('id', '!=', $excluirAltaId))
            ->where(function ($q) use ($fechaInicio, $fechaFin) {
                // existing.start <= new.end (siempre true si new.end es null / abierto)
                if ($fechaFin !== null) {
                    $q->where('fechaInicio', '<=', $fechaFin);
                }
                // existing.end >= new.start (siempre true si existing.end es null / abierto)
                $q->where(function ($q2) use ($fechaInicio) {
                    $q2->whereNull('fechaFin')
                        ->orWhere('fechaFin', '>=', $fechaInicio);
                });
            })
            ->exists();
    }
}
