<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamRegimenLaboral extends Model
{
    protected $table = 'param.t33_regimenLaboral';

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
