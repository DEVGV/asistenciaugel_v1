<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradosIE extends Model
{
    use \App\Traits\HasCodigo;

    protected $table = 't_gradosIE';

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

    public function secciones(): HasMany
    {
        return $this->hasMany(SeccionesIE::class, 'grado_id');
    }
}
