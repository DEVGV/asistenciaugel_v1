<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamSexos extends Model
{
    protected $table = 'param.t00_sexos';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
