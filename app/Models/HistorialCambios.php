<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialCambios extends Model
{
    protected $table = 't_historialCambios';

    public $timestamps = false;

    protected $fillable = [
        'esquema',
        'tabla',
        'registro_id',
        'accion',
        'dato_old',
        'dato_new',
        'accion_at',
        'accion_by',
    ];
}
