<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamOperadores extends Model
{
    protected $table = 'param.t00_operadores';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
