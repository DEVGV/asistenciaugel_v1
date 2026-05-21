<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamTipoEntidad extends Model
{
    protected $table = 'param.t00_tipoEntidad';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
