<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;

class ParamDocumentos extends Model
{
    protected $table = 'param.t00_documentos';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'activo',
    ];
}
