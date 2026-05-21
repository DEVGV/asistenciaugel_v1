<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamRegimenEduc extends Model
{
    protected $table = 'param.t34_regimenEduc';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
