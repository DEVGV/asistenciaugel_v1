<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamEstadosAsis extends Model
{
    protected $table = 'param.t00_estadosAsis';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
