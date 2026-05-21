<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamPais extends Model
{
    protected $table = 'param.t26_pais';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
