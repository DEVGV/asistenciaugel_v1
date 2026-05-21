<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamTipoInstEduc extends Model
{
    protected $table = 'param.t34_tipoInstEduc';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
