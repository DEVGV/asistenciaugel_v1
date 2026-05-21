<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamTipoTrabajador extends Model
{
    protected $table = 'param.t8_tipoTrabajador';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'sectorPub',
        'sectorPriv',
        'sectorOtro',
        'activo',
    ];
}
