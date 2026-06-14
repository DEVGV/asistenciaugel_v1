<?php

namespace App\Services\Auth;

use App\DTOs\Auth\SeleccionarContextoDTO;
use App\Models\AltasTrabajadores;
use App\Models\Entidades;
use App\Models\InstitucionesEduc;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

/**
 * Maneja el contexto de trabajo (UGEL + IE) del usuario autenticado.
 *
 * Semántica de auth.usuario_perfil_ie:
 * - institucionEducativa_id set             → asignación a una IE específica
 * - institucionEducativa_id null + UGEL set → administrador de esa UGEL (todas sus IEs)
 * - ambos null                              → administrador global (todas las UGELs)
 *
 * El contexto activo se guarda en sesión:
 * - contexto.ugel_id → UGEL con la que trabaja
 * - contexto.ie_id   → IE seleccionada, o null = "Todas las IEs" de la UGEL (solo admin)
 */
class ContextoService
{
    private const SESSION_UGEL = 'contexto.ugel_id';

    private const SESSION_IE = 'contexto.ie_id';

    /**
     * Opciones de contexto disponibles para un usuario, agrupadas por UGEL.
     *
     * @return array<int, array{id: int, nombre: string, esAdmin: bool, ies: array<int, array{id: int, nombre: string, codigoModular: string|null, perfil: string|null}>}>
     */
    public function opcionesParaUsuario(User $user): array
    {
        $asignaciones = $user->perfilesIe()
            ->where('activo', true)
            ->with(['perfil', 'entidadUgel', 'institucionEducativa.entidadUgel'])
            ->get();

        $ugeles = [];

        // Admin global → administra todas las UGELs activas
        if ($asignaciones->contains(fn ($a) => $a->esAdminGlobal())) {
            Entidades::query()
                ->ugeles()
                ->where('activo', true)
                ->orderBy('razonSocial')
                ->get()
                ->each(function (Entidades $ugel) use (&$ugeles) {
                    $ugeles[$ugel->id] = [
                        'id' => $ugel->id,
                        'nombre' => $ugel->razonSocial ?? "UGEL #{$ugel->id}",
                        'esAdmin' => true,
                        'ies' => [],
                    ];
                });
        }

        foreach ($asignaciones as $asignacion) {
            // Admin de una UGEL específica
            if ($asignacion->esAdminUgel() && $asignacion->entidadUgel) {
                $ugel = $asignacion->entidadUgel;
                $ugeles[$ugel->id] ??= [
                    'id' => $ugel->id,
                    'nombre' => $ugel->razonSocial ?? "UGEL #{$ugel->id}",
                    'esAdmin' => false,
                    'ies' => [],
                ];
                $ugeles[$ugel->id]['esAdmin'] = true;

                continue;
            }

            // Asignación a una IE específica (desde perfil explícito)
            $ie = $asignacion->institucionEducativa;
            if (! $ie) {
                continue;
            }

            $ugel = $ie->entidadUgel;
            $ugelId = $asignacion->entidadUgel_id ?? $ie->entidadUgel_id;
            if (! $ugelId) {
                continue;
            }

            $ugeles[$ugelId] ??= [
                'id' => $ugelId,
                'nombre' => $ugel?->razonSocial ?? "UGEL #{$ugelId}",
                'esAdmin' => false,
                'ies' => [],
            ];

            $ugeles[$ugelId]['ies'][$ie->id] = [
                'id' => $ie->id,
                'nombre' => $ie->nombreLegal ?? "IE #{$ie->id}",
                'codigoModular' => $ie->codigoModular,
                'perfil' => $asignacion->perfil?->nombre,
            ];
        }

        // ── Altas activas del trabajador como acceso implícito a IEs ──────
        // Si el trabajador tiene altas activas vigentes, esas IEs también
        // aparecen como opciones de contexto (acceso por alta).
        if ($user->trabajador_id) {
            $altas = AltasTrabajadores::where('trabajador_id', $user->trabajador_id)
                ->whereNull('fechaBaja')
                ->where(function ($q) {
                    $q->whereNull('fechaFin')
                        ->orWhere('fechaFin', '>=', now()->toDateString());
                })
                ->where('fechaInicio', '<=', now()->toDateString())
                ->with('institucionEducativa.entidadUgel')
                ->get();

            foreach ($altas as $alta) {
                $ie = $alta->institucionEducativa;
                if (! $ie) {
                    continue;
                }

                $ugelId = $ie->entidadUgel_id;
                if (! $ugelId) {
                    continue;
                }

                // No duplicar si ya existe por perfil explícito
                if (isset($ugeles[$ugelId]['ies'][$ie->id])) {
                    continue;
                }

                $ugeles[$ugelId] ??= [
                    'id' => $ugelId,
                    'nombre' => $ie->entidadUgel?->razonSocial ?? "UGEL #{$ugelId}",
                    'esAdmin' => false,
                    'ies' => [],
                ];

                $ugeles[$ugelId]['ies'][$ie->id] = [
                    'id' => $ie->id,
                    'nombre' => $ie->nombreLegal ?? "IE #{$ie->id}",
                    'codigoModular' => $ie->codigoModular,
                    'perfil' => null, // acceso por alta, sin perfil explícito
                ];
            }
        }

        return array_values(array_map(function (array $ugel) {
            $ugel['ies'] = array_values($ugel['ies']);

            return $ugel;
        }, $ugeles));
    }

    /**
     * Cuenta las opciones seleccionables (UGEL admin = 1 opción "Todas" + sus IEs explícitas).
     *
     * @param  array<int, array<string, mixed>>  $opciones
     */
    public function totalOpciones(array $opciones): int
    {
        return collect($opciones)->sum(
            fn (array $ugel) => ($ugel['esAdmin'] ? 1 : 0) + count($ugel['ies'])
        );
    }

    /**
     * Si el usuario tiene una sola opción posible, la establece automáticamente.
     * Devuelve true si el contexto quedó establecido.
     */
    public function establecerAutomatico(User $user): bool
    {
        $opciones = $this->opcionesParaUsuario($user);

        if ($this->totalOpciones($opciones) !== 1) {
            return false;
        }

        $ugel = $opciones[0];
        $ieId = $ugel['esAdmin'] ? null : ($ugel['ies'][0]['id'] ?? null);

        Session::put(self::SESSION_UGEL, $ugel['id']);
        Session::put(self::SESSION_IE, $ieId);

        return true;
    }

    /** Valida que el usuario tenga acceso al contexto solicitado. */
    public function puedeAcceder(User $user, int $ugelId, ?int $ieId): bool
    {
        $opciones = collect($this->opcionesParaUsuario($user));
        $ugel = $opciones->firstWhere('id', $ugelId);

        if (! $ugel) {
            return false;
        }

        // "Todas las IEs" solo para administradores de la UGEL
        if ($ieId === null) {
            return (bool) $ugel['esAdmin'];
        }

        // IE asignada explícitamente
        if (collect($ugel['ies'])->contains('id', $ieId)) {
            return true;
        }

        // Admin de la UGEL puede elegir cualquier IE de esa UGEL
        return $ugel['esAdmin'] && InstitucionesEduc::query()
            ->where('id', $ieId)
            ->where('entidadUgel_id', $ugelId)
            ->exists();
    }

    public function establecer(User $user, SeleccionarContextoDTO $dto): void
    {
        Session::put(self::SESSION_UGEL, $dto->ugel_id);
        Session::put(self::SESSION_IE, $dto->ie_id);
    }

    public function limpiar(): void
    {
        Session::forget([self::SESSION_UGEL, self::SESSION_IE]);
    }

    public function establecido(): bool
    {
        return Session::has(self::SESSION_UGEL);
    }

    public function ugelId(): ?int
    {
        $id = Session::get(self::SESSION_UGEL);

        return $id !== null ? (int) $id : null;
    }

    public function ieId(): ?int
    {
        $id = Session::get(self::SESSION_IE);

        return $id !== null ? (int) $id : null;
    }

    /**
     * Datos del contexto activo para compartir vía Inertia.
     *
     * @return array{ugel: array{id: int, nombre: string}|null, ie: array{id: int, nombre: string, codigoModular: string|null}|null, multiple: bool}
     */
    public function compartir(User $user): array
    {
        $ugel = $this->ugelId()
            ? Entidades::query()->select(['id', 'razonSocial'])->find($this->ugelId())
            : null;

        $ie = $this->ieId()
            ? InstitucionesEduc::query()->select(['id', 'nombreLegal', 'codigoModular'])->find($this->ieId())
            : null;

        $opciones = $this->opcionesParaUsuario($user);

        return [
            'ugel' => $ugel ? ['id' => $ugel->id, 'nombre' => $ugel->razonSocial] : null,
            'ie' => $ie ? ['id' => $ie->id, 'nombre' => $ie->nombreLegal, 'codigoModular' => $ie->codigoModular] : null,
            'multiple' => $this->totalOpciones($opciones) > 1,
        ];
    }

    /* ──────────────────────────────────────────────────────────────────────
     |  Helpers de filtrado para los Services de cada módulo
     ──────────────────────────────────────────────────────────────────────*/

    /**
     * Aplica el filtro del contexto sobre una columna IE directa.
     * IE seleccionada → esa IE; solo UGEL → todas las IEs de la UGEL.
     * Sin contexto → no retorna nada (seguridad).
     */
    public function filtrarPorIe(Builder $query, string $columna = 'institucionEducativa_id'): Builder
    {
        if ($this->ieId()) {
            return $query->where($columna, $this->ieId());
        }

        if ($this->ugelId()) {
            return $query->whereIn(
                $columna,
                InstitucionesEduc::query()
                    ->select('id')
                    ->where('entidadUgel_id', $this->ugelId())
            );
        }

        // Sin contexto → no retornar nada para evitar acceso irrestricto
        return $query->whereRaw('1 = 0');
    }

    /** Aplica el filtro del contexto sobre el propio modelo InstitucionesEduc. */
    public function filtrarInstituciones(Builder $query): Builder
    {
        if ($this->ieId()) {
            return $query->where('id', $this->ieId());
        }

        if ($this->ugelId()) {
            return $query->where('entidadUgel_id', $this->ugelId());
        }

        // Sin contexto → no retornar nada para evitar acceso irrestricto
        return $query->whereRaw('1 = 0');
    }

    /**
     * Aplica el filtro del contexto a una consulta de auth.usuario_perfil_ie.
     * Incluye asignaciones de admin global, admin de la UGEL del contexto,
     * y asignaciones a IEs dentro del contexto.
     */
    public function filtrarAsignaciones(Builder $query): Builder
    {
        if (! $this->ugelId()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function ($q) {
            $q->where(fn ($w) => $w->whereNull('institucionEducativa_id')->whereNull('entidadUgel_id'))
                ->orWhere(fn ($w) => $w->whereNull('institucionEducativa_id')->where('entidadUgel_id', $this->ugelId()))
                ->orWhere(fn ($w) => $this->filtrarPorIe($w, 'institucionEducativa_id'));
        });
    }

    /**
     * Aplica el filtro del contexto vía una relación que termina en IE.
     * Ejemplo: filtrarPorRelacionIe($query, 'altas', 'institucionEducativa_id').
     * Sin contexto → no retorna nada (seguridad).
     */
    public function filtrarPorRelacionIe(Builder $query, string $relacion, string $columna = 'institucionEducativa_id'): Builder
    {
        if (! $this->ieId() && ! $this->ugelId()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas($relacion, fn ($q) => $this->filtrarPorIe($q, $columna));
    }
}
