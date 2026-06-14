<?php

namespace App\Models;

use App\Models\Auth\UsuarioPerfilIe;
use App\Models\Auth\UsuarioPermisoIe;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $table = 'auth.users';

    protected $fillable = [
        'trabajador_id',
        'login',
        'password',
        'activo',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'activo' => 'boolean',
        ];
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }

    public function perfilesIe(): HasMany
    {
        return $this->hasMany(UsuarioPerfilIe::class, 'user_id');
    }

    public function permisosIe(): HasMany
    {
        return $this->hasMany(UsuarioPermisoIe::class, 'user_id');
    }

    /**
     * Obtiene los códigos de permiso del usuario para una IE específica.
     * Los permisos globales (IE null) siempre aplican.
     */
    public function permisosParaIe(?int $ieId): array
    {
        return $this->permisosIe()
            ->where(function ($q) use ($ieId) {
                $q->where('institucionEducativa_id', $ieId)
                    ->orWhereNull('institucionEducativa_id');
            })
            ->with('permiso')
            ->get()
            ->pluck('permiso.codigo')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Verifica si el usuario tiene un permiso específico en una IE.
     */
    public function tienePermiso(string $codigoPermiso, ?int $ieId = null): bool
    {
        return in_array($codigoPermiso, $this->permisosParaIe($ieId));
    }

    /**
     * Obtiene el email del usuario a través de su trabajador → persona → emails.
     */
    public function getEmailAttribute(): ?string
    {
        return $this->trabajador?->persona?->emails()->whereNull('fechaFin')->value('email')
            ?? $this->trabajador?->persona?->emails()->value('email');
    }
}
