<?php

namespace App\Models;

use App\Models\Param\ParamTipoEntidad;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entidades extends Model
{
    protected $table = 't_entidades';

    public $timestamps = false;

    protected $fillable = [
        'tipoEntidad_id',
        'ruc',
        'razonSocial',
        'razonComercial',
        'personaRepLegal_id',
        'created_by',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function tipoEntidad(): BelongsTo
    {
        return $this->belongsTo(ParamTipoEntidad::class, 'tipoEntidad_id');
    }

    public function institucionesEducativas(): HasMany
    {
        return $this->hasMany(InstitucionesEduc::class, 'entidadUgel_id');
    }

    /** Scope: solo entidades de tipo UGEL. */
    public function scopeUgeles(Builder $query): Builder
    {
        return $query->whereHas('tipoEntidad', fn ($q) => $q->where('abreviatura', 'UGEL'));
    }
}
