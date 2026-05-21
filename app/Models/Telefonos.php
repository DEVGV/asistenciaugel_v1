<?php

namespace App\Models;

use App\Models\Param\ParamOperadores;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Telefonos extends Model
{
    protected $table = 't_telefonos';

    public $timestamps = false;

    protected $fillable = [
        'persona_id',
        'entidad_id',
        'institucionEduc_id',
        'operador_id',
        'movilFijo',
        'codigoPais',
        'numero',
        'imei',
        'fechaInicio',
        'fechaFin',
        'created_by',
    ];

    public function operador(): BelongsTo
    {
        return $this->belongsTo(ParamOperadores::class, 'operador_id');
    }

    public function entidad(): BelongsTo
    {
        return $this->belongsTo(Entidades::class, 'entidad_id');
    }

    public function institucionEduc(): BelongsTo
    {
        return $this->belongsTo(InstitucionesEduc::class, 'institucionEduc_id');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Personas::class, 'persona_id');
    }
}
