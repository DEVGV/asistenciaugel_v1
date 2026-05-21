<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParamDepartamento extends Model
{
    protected $table = 'param.t28_departamento';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'pais_id',
        'activo',
    ];

    public function pais(): BelongsTo
    {
        return $this->belongsTo(ParamPais::class, 'pais_id');
    }
}
