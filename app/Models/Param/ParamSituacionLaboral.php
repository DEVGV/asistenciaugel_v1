<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamSituacionLaboral extends Model
{
    protected $table = 'param.t15_situacionLaboral';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
