<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamFeriados extends Model
{
    protected $table = 'param.t00_feriados';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'descripcion',
        'diaMesDefault',
        'programaDefault',
        'activo',
    ];
}
