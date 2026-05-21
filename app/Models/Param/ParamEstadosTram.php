<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamEstadosTram extends Model
{
    protected $table = 'param.t00_estadosTram';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
