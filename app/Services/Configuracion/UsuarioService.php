<?php

namespace App\Services\Configuracion;

use App\Models\AltasTrabajadores;
use App\Models\Auth\UsuarioPerfilIe;
use App\Models\Auth\UsuarioPermisoIe;
use App\Models\Entidades;
use App\Models\InstitucionesEduc;
use App\Models\Trabajador;
use App\Models\User;
use App\Services\Auth\ContextoService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function __construct(
        private ContextoService $contextoService,
    ) {}

    /**
     * @return LengthAwarePaginator<User>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return User::query()
            ->with(['trabajador.persona', 'permisosIe', 'perfilesIe.perfil'])
            // Solo usuarios visibles en el contexto actual (o sin asignaciones, para poder asignarles)
            ->when($this->contextoService->ugelId(), function ($query) {
                $query->where(function ($q) {
                    $q->whereDoesntHave('perfilesIe')
                        ->orWhereHas('perfilesIe', fn ($p) => $this->contextoService->filtrarAsignaciones($p));
                });
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('login', 'ilike', "%{$search}%")
                        ->orWhereHas('trabajador.persona', function ($qp) use ($search) {
                            $qp->where('nombre', 'ilike', "%{$search}%")
                                ->orWhere('paterno', 'ilike', "%{$search}%")
                                ->orWhere('materno', 'ilike', "%{$search}%");
                        });
                });
            })
            ->orderBy('login')
            ->paginate(15);
    }

    /**
     * Encuentra el usuario asociado a un trabajador, o null si no existe.
     */
    public function porTrabajador(Trabajador $trabajador): ?User
    {
        return User::where('trabajador_id', $trabajador->id)->first();
    }

    /**
     * Verifica si el trabajador del usuario tiene alta activa en la IE indicada.
     */
    public function tieneAltaActivaEnIe(User $user, int $ieId): bool
    {
        if (! $user->trabajador_id) {
            return false;
        }

        return AltasTrabajadores::where('trabajador_id', $user->trabajador_id)
            ->where('institucionEducativa_id', $ieId)
            ->whereNull('fechaBaja')
            ->where(function ($q) {
                $q->whereNull('fechaFin')
                  ->orWhere('fechaFin', '>=', now()->toDateString());
            })
            ->exists();
    }

    public function cambiarPassword(User $user, string $newPassword): void
    {
        $user->update(['password' => Hash::make($newPassword)]);
    }

    public function toggleActivo(User $user): void
    {
        $user->update(['activo' => ! $user->activo]);
    }

    /**
     * Devuelve las IEs donde el trabajador tiene alta activa (sin baja y vigente por fechas).
     * Solo estas IEs estarán disponibles para asignar permisos directos.
     */
    public function ieDisponiblesParaUsuario(User $user): array
    {
        if (! $user->trabajador_id) {
            return [];
        }

        return AltasTrabajadores::where('trabajador_id', $user->trabajador_id)
            ->whereNull('fechaBaja')
            ->where(function ($q) {
                $q->whereNull('fechaFin')
                    ->orWhere('fechaFin', '>=', now()->toDateString());
            })
            ->where('fechaInicio', '<=', now()->toDateString())
            ->with('institucionEducativa')
            ->get()
            ->map(fn ($a) => [
                'id' => $a->institucionEducativa_id,
                'label' => $a->institucionEducativa?->nombreLegal ?? "IE #{$a->institucionEducativa_id}",
                'codModular' => $a->institucionEducativa?->codigoModular,
            ])
            ->unique('id')
            ->values()
            ->toArray();
    }

    /**
     * Devuelve los IDs de permisos que el usuario tiene para una IE concreta.
     * ieId = null → permisos globales.
     */
    public function permisosDeUsuarioPorIe(User $user, ?int $ieId): array
    {
        return UsuarioPermisoIe::where('user_id', $user->id)
            ->where(function ($q) use ($ieId) {
                if ($ieId === null) {
                    $q->whereNull('institucionEducativa_id');
                } else {
                    $q->where('institucionEducativa_id', $ieId);
                }
            })
            ->pluck('permiso_id')
            ->toArray();
    }

    /**
     * Sincroniza los permisos directos del usuario para una IE concreta.
     * Reemplaza completamente los permisos de esa IE.
     */
    public function syncPermisosIe(User $user, ?int $ieId, array $permisoIds): void
    {
        // Eliminar los permisos actuales para esta IE
        UsuarioPermisoIe::where('user_id', $user->id)
            ->where(function ($q) use ($ieId) {
                if ($ieId === null) {
                    $q->whereNull('institucionEducativa_id');
                } else {
                    $q->where('institucionEducativa_id', $ieId);
                }
            })
            ->delete();

        // Insertar los nuevos
        if (! empty($permisoIds)) {
            $rows = array_map(fn ($pid) => [
                'user_id' => $user->id,
                'permiso_id' => $pid,
                'institucionEducativa_id' => $ieId,
                'created_by' => auth()->id(),
                'created_at' => now(),
            ], $permisoIds);

            UsuarioPermisoIe::insert($rows);
        }
    }

    /**
     * Obtiene los perfiles asignados al usuario.
     *
     * @return Collection<int, UsuarioPerfilIe>
     */
    public function perfilesDelUsuario(User $user): Collection
    {
        return UsuarioPerfilIe::where('user_id', $user->id)
            ->with(['perfil', 'institucionEducativa', 'entidadUgel'])
            ->get();
    }

    /**
     * Asigna un perfil al usuario.
     * - IE indicada → asignación a esa IE (la UGEL se deriva de la IE).
     * - Solo UGEL → administrador de esa UGEL (todas sus IEs).
     * - Ambos null → administrador global del sistema.
     */
    public function asignarPerfil(User $user, int $perfilId, ?int $ieId, ?int $ugelId = null): void
    {
        if ($ieId !== null) {
            $ugelId ??= InstitucionesEduc::whereKey($ieId)->value('entidadUgel_id');

            UsuarioPerfilIe::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'institucionEducativa_id' => $ieId,
                ],
                [
                    'perfil_id' => $perfilId,
                    'entidadUgel_id' => $ugelId,
                    'activo' => true,
                    'created_by' => auth()->id() ?? 1,
                ]
            );

            return;
        }

        UsuarioPerfilIe::updateOrCreate(
            [
                'user_id' => $user->id,
                'institucionEducativa_id' => null,
                'entidadUgel_id' => $ugelId,
            ],
            [
                'perfil_id' => $perfilId,
                'activo' => true,
                'created_by' => auth()->id() ?? 1,
            ]
        );
    }

    /**
     * UGELs activas disponibles para asignar perfiles (limitadas al contexto actual).
     *
     * @return Collection<int, Entidades>
     */
    public function ugelesDisponibles(): Collection
    {
        return Entidades::query()
            ->ugeles()
            ->where('activo', true)
            ->when($this->contextoService->ugelId(), fn ($q, int $ugelId) => $q->where('id', $ugelId))
            ->orderBy('razonSocial')
            ->get(['id', 'razonSocial']);
    }

    /**
     * IEs vigentes disponibles para asignar perfiles (limitadas al contexto actual).
     *
     * @return Collection<int, InstitucionesEduc>
     */
    public function institucionesParaAsignacion(): Collection
    {
        return $this->contextoService->filtrarInstituciones(InstitucionesEduc::query())
            ->select(['id', 'nombreLegal', 'codigoModular', 'entidadUgel_id'])
            ->where(function ($q) {
                $q->whereNull('fechaFin')
                  ->orWhere('fechaFin', '>=', now()->toDateString());
            })
            ->orderBy('nombreLegal')
            ->get();
    }

    /**
     * Revoca una asignación de perfil.
     */
    public function revocarPerfil(UsuarioPerfilIe $perfilIe): void
    {
        $perfilIe->delete();
    }
}
