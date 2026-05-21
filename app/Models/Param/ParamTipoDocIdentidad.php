<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamTipoDocIdentidad extends Model
{
    protected $table = 'param.t3_tipoDocIdentidad';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
