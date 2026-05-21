<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamTurnos extends Model
{
    protected $table = 'param.t00_turnos';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
