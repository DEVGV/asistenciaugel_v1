<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamMotivoDeBajas extends Model
{
    protected $table = 'param.t17_motivoDeBajas';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
