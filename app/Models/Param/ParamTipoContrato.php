<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamTipoContrato extends Model
{
    protected $table = 'param.t12_tipoContrato';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
