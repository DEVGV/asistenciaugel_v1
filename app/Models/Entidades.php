<?php

namespace App\Models;

use App\Models\Param\ParamTipoEntidad;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
