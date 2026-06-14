<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permiso extends Model
{
    protected $table = 'auth.permisos';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'modulo',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function perfiles(): BelongsToMany
    {
        return $this->belongsToMany(
            Perfil::class,
            'auth.perfil_permisos',
            'permiso_id',
            'perfil_id'
        );
    }
}
