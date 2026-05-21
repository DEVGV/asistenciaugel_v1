<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CursosIE extends Model
{
    use \App\Traits\HasCodigo;

    protected $table = 't_cursosIE';

    public $timestamps = false;

    protected $fillable = [
        'institucionEduc_id',
        'codigo',
        'nombre',
        'sigla',
        'created_by',
        'activo',
    ];

    public function institucionEduc(): BelongsTo
    {
        return $this->belongsTo(InstitucionesEduc::class, 'institucionEduc_id');
    }
}
