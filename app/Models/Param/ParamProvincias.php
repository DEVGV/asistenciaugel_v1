<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParamProvincias extends Model
{
    protected $table = 'param.t28_provincias';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'departamento_id',
        'activo',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(ParamDepartamento::class, 'departamento_id');
    }
}
