<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamModalidadesForm extends Model
{
    protected $table = 'param.t34_modalidadesForm';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
