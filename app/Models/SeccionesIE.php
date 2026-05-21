<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeccionesIE extends Model
{
    use \App\Traits\HasCodigo;

    protected $table = 't_seccionesIE';

    public $timestamps = false;

    protected $fillable = [
        'grado_id',
        'codigo',
        'nombre',
        'sigla',
        'created_by',
        'activo',
    ];

    public function grado(): BelongsTo
    {
        return $this->belongsTo(GradosIE::class, 'grado_id');
    }
}
