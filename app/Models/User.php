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
     *
     * Fuentes de permisos (unión):
     * 1. Permisos directos (usuario_permisos_ie) para la IE o globales (IE null).
     * 2. Permisos heredados del perfil asignado en la IE o globalmente.
     */
    public function permisosParaIe(?int $ieId): array
    {
        // 1. Permisos directos
        $directos = $this->permisosIe()
            ->where(function ($q) use ($ieId) {
                $q->where('institucionEducativa_id', $ieId)
                    ->orWhereNull('institucionEducativa_id');
            })
            ->with('permiso')
            ->get()
            ->pluck('permiso.codigo')
            ->filter();

        // 2. Permisos heredados del perfil
        $dePerfil = $this->perfilesIe()
            ->where('activo', true)
            ->where(function ($q) use ($ieId) {
                // Perfil asignado a esta IE, o admin de UGEL (IE null), o admin global (ambos null)
                $q->where('institucionEducativa_id', $ieId)
                    ->orWhereNull('institucionEducativa_id');
            })
            ->with('perfil.permisos')
            ->get()
            ->flatMap(fn ($asignacion) => $asignacion->perfil?->permisos?->pluck('codigo') ?? collect())
            ->filter();

        return $directos->merge($dePerfil)
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
     * Verifica si el usuario tiene al menos uno de los permisos indicados.
     */
    public function tieneAlgunPermiso(array $codigos, ?int $ieId = null): bool
    {
        $permisos = $this->permisosParaIe($ieId);

        foreach ($codigos as $codigo) {
            if (in_array($codigo, $permisos)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene el nombre del perfil activo del usuario para la IE del contexto.
     * Si es admin de UGEL o global, devuelve ese perfil.
     */
    public function perfilActivoParaIe(?int $ieId): ?string
    {
        $asignacion = $this->perfilesIe()
            ->where('activo', true)
            ->with('perfil')
            ->orderByRaw("CASE
                WHEN \"institucionEducativa_id\" = ? THEN 1
                WHEN \"institucionEducativa_id\" IS NULL AND \"entidadUgel_id\" IS NOT NULL THEN 2
                WHEN \"institucionEducativa_id\" IS NULL AND \"entidadUgel_id\" IS NULL THEN 3
                ELSE 4
            END", [$ieId ?? 0])
            ->first();

        return $asignacion?->perfil?->nombre;
    }

    /**
     * Verifica si el perfil activo del usuario para la IE dada es "Docente".
     */
    public function esDocente(?int $ieId): bool
    {
        return $this->perfilActivoParaIe($ieId) === 'Docente';
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
