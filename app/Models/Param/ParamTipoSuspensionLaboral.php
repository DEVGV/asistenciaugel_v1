<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamTipoSuspensionLaboral extends Model
{
    protected $table = 'param.t21_tipoSuspensionLaboral';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'perfeImperfe',
        'activo',
    ];
}
