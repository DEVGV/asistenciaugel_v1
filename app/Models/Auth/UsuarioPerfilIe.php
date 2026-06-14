<?php

namespace App\Models\Auth;

use App\Models\Entidades;
use App\Models\InstitucionesEduc;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioPerfilIe extends Model
{
    /** Serializar relaciones en camelCase (institucionEducativa, entidadUgel). */
    public static $snakeAttributes = false;

    protected $table = 'auth.usuario_perfil_ie';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'perfil_id',
        'entidadUgel_id',
        'institucionEducativa_id',
        'activo',
        'created_by',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }

    public function entidadUgel(): BelongsTo
    {
        return $this->belongsTo(Entidades::class, 'entidadUgel_id');
    }

    public function institucionEducativa(): BelongsTo
    {
        return $this->belongsTo(InstitucionesEduc::class, 'institucionEducativa_id');
    }

    /** ¿Es una asignación de administrador de UGEL (acceso a todas sus IEs)? */
    public function esAdminUgel(): bool
    {
        return $this->institucionEducativa_id === null && $this->entidadUgel_id !== null;
    }

    /** ¿Es una asignación de administrador global del sistema? */
    public function esAdminGlobal(): bool
    {
        return $this->institucionEducativa_id === null && $this->entidadUgel_id === null;
    }
}
