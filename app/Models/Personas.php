<?php

namespace App\Models;

use App\Models\Param\ParamPais;
use App\Models\Param\ParamSexos;
use App\Models\Param\ParamTipoDocIdentidad;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Personas extends Model
{
    public static $snakeAttributes = false;

    protected $table = 't_personas';

    public $timestamps = false;

    protected $fillable = [
        'pais_id',
        'tipoDocIdentidad_id',
        'docIdentidad',
        'paterno',
        'materno',
        'nombre',
        'sexo_id',
        'fechaNac',
        'ubigeo',
        'foto',
        'created_by',
        'activo',
    ];

    public function sexo(): BelongsTo
    {
        return $this->belongsTo(ParamSexos::class, 'sexo_id');
    }

    public function tipoDocIdentidad(): BelongsTo
    {
        return $this->belongsTo(ParamTipoDocIdentidad::class, 'tipoDocIdentidad_id');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(ParamPais::class, 'pais_id');
    }

    public function telefonos(): HasMany
    {
        return $this->hasMany(Telefonos::class, 'persona_id');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Emails::class, 'persona_id');
    }

    public function domicilios(): HasMany
    {
        return $this->hasMany(Domicilios::class, 'persona_id');
    }

    public function trabajador(): HasOne
    {
        return $this->hasOne(Trabajador::class, 'persona_id');
    }

    /**
     * Accessor para nombre completo.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->paterno} {$this->materno}, {$this->nombre}");
    }
}
