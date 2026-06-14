<?php

namespace App\Models\Auth;

use App\Models\InstitucionesEduc;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioPermisoIe extends Model
{
    protected $table = 'auth.usuario_permisos_ie';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'permiso_id',
        'institucionEducativa_id',
        'created_by',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function permiso(): BelongsTo
    {
        return $this->belongsTo(Permiso::class, 'permiso_id');
    }

    public function institucionEducativa(): BelongsTo
    {
        return $this->belongsTo(InstitucionesEduc::class, 'institucionEducativa_id');
    }
}
