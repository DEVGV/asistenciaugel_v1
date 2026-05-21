<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamNivelesCiclo extends Model
{
    protected $table = 'param.t34_nivelesCiclo';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
