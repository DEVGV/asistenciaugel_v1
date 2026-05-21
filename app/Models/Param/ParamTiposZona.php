<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamTiposZona extends Model
{
    protected $table = 'param.t6_tiposZona';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
