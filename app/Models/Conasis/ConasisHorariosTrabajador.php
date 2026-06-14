<?php

namespace App\Models\Conasis;

use App\Models\AltasTrabajadores;
use App\Models\InstitucionesEduc;
use App\Models\Trabajador;
use App\Traits\HasCodigo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConasisHorariosTrabajador extends Model
{
    use HasCodigo;

    protected $table = 'conasis.t_horariosTrabajador';

    public $timestamps = false;

    protected string $codigoPrefix = 'HOR';

    protected $fillable = [
        'anio',
        'institucionEduc_id',
        'trabajador_id',
        'altaTrabajador_id',
        'tipoHorario',
        'nombre',
        'fechaInicio',
        'fechaFin',
        'created_by',
        'archivado',
        'activo',
    ];

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

    public function detalles(): HasMany
    {
        return $this->hasMany(ConasisDetalleHorarios::class, 'horarioTrabajador_id');
    }
}
