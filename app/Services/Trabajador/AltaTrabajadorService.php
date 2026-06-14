<?php

namespace App\Services\Trabajador;

use App\DTOs\Trabajador\CreateAltaTrabajadorDTO;
use App\Models\AltasTrabajadores;
use App\Models\Conasis\ConasisLocalesMarcacion;
use App\Models\InstitucionesEduc;
use App\Models\Trabajador;
use App\Models\User;
use App\Models\Auth\UsuarioPerfilIe;
use App\Services\Auth\ContextoService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AltaTrabajadorService
{
    public function __construct(
        private ContextoService $contextoService,
    ) {}

    /**
     * Lista todas las altas de un trabajador específico con relaciones.
     *
     * @return Collection<int, AltasTrabajadores>
     */
    public function listarPorTrabajador(Trabajador $trabajador): Collection
    {
        $altas = AltasTrabajadores::query()
            ->with([
                'institucionEducativa',
                'condicionLaboral',
                'tipoContrato',
                'rolTrabajador',
                'situacionLaboral',
                'area',
                'cargo',
                'motivoBaja',
            ])
            ->where('trabajador_id', $trabajador->id)
            ->orderByDesc('fechaInicio')
            ->get();

        $user = User::where('trabajador_id', $trabajador->id)->first();
        if ($user) {
            $perfilesIe = UsuarioPerfilIe::where('user_id', $user->id)
                ->where('activo', true)
                ->with('perfil')
                ->get()
                ->keyBy('institucionEducativa_id');

            foreach ($altas as $alta) {
                $alta->perfil_ie = $perfilesIe->get($alta->institucionEducativa_id);
            }
        }

        return $altas;
    }

    /**
     * Lista todas las altas del sistema con paginación y filtros.
     * Optimizado para manejar más de 1 000 IEs.
     *
     * @return LengthAwarePaginator<AltasTrabajadores>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        $paginator = $this->contextoService->filtrarPorIe(AltasTrabajadores::query())
            ->with([
                'trabajador.persona',
                'institucionEducativa',
                'condicionLaboral',
                'rolTrabajador',
                'situacionLaboral',
                'motivoBaja',
            ])
            ->when($request->search, function ($query, string $search) {
                $query->where(function ($outer) use ($search) {
                    $outer->whereHas('trabajador.persona', function ($q) use ($search) {
                        $q->where('paterno', 'ilike', "%{$search}%")
                            ->orWhere('materno', 'ilike', "%{$search}%")
                            ->orWhere('nombre', 'ilike', "%{$search}%")
                            ->orWhere('docIdentidad', 'like', "%{$search}%");
                    })->orWhereHas('trabajador', function ($q) use ($search) {
                        $q->where('codigo', 'like', "%{$search}%");
                    });
                });
            })
            ->when($request->integer('institucion_id'), function ($query, int $ieId) {
                $query->where('institucionEducativa_id', $ieId);
            })
            ->when($request->integer('trabajador_id'), function ($query, int $tId) {
                $query->where('trabajador_id', $tId);
            })
            ->when($request->anio, function ($query, string $anio) {
                $query->where(function ($q) use ($anio) {
                    $q->whereYear('fechaInicio', $anio)
                        ->orWhereYear('fechaFin', $anio)
                        ->orWhere(function ($q2) use ($anio) {
                            $q2->where('fechaInicio', '<=', "{$anio}-12-31")
                                ->where(function ($q3) use ($anio) {
                                    $q3->whereNull('fechaFin')
                                        ->orWhere('fechaFin', '>=', "{$anio}-01-01");
                                });
                        });
                });
            })
            ->when($request->boolean('solo_activas'), function ($query) {
                $query->whereNull('fechaBaja');
            })
            ->orderByDesc('fechaInicio')
            ->paginate(20)
            ->withQueryString();

        $trabajadorIds = $paginator->pluck('trabajador_id')->unique()->toArray();
        if (!empty($trabajadorIds)) {
            $users = User::whereIn('trabajador_id', $trabajadorIds)->get()->keyBy('trabajador_id');
            $userIds = $users->pluck('id')->toArray();
            if (!empty($userIds)) {
                $perfilesIe = UsuarioPerfilIe::whereIn('user_id', $userIds)
                    ->where('activo', true)
                    ->with('perfil')
                    ->get()
                    ->groupBy('user_id');

                foreach ($paginator->items() as $alta) {
                    $user = $users->get($alta->trabajador_id);
                    if ($user) {
                        $userPerfiles = $perfilesIe->get($user->id) ?? collect();
                        $alta->perfil_ie = $userPerfiles->firstWhere('institucionEducativa_id', $alta->institucionEducativa_id);
                    } else {
                        $alta->perfil_ie = null;
                    }
                }
            }
        }

        return $paginator;
    }

    /**
     * Lista altas paginadas de una IE específica con filtros de búsqueda.
     * Optimizado con índices selectivos para manejar gran volumen.
     *
     * @return LengthAwarePaginator<AltasTrabajadores>
     */
    public function listarPorInstitucion(InstitucionesEduc $ie, Request $request): LengthAwarePaginator
    {
        return AltasTrabajadores::query()
            ->select([
                't_altasTrabajadores.id',
                't_altasTrabajadores.trabajador_id',
                't_altasTrabajadores.codigoAirsp',
                't_altasTrabajadores.fechaInicio',
                't_altasTrabajadores.fechaFin',
                't_altasTrabajadores.fechaBaja',
                't_altasTrabajadores.condicionLaboral_id',
                't_altasTrabajadores.tipoContrato_id',
                't_altasTrabajadores.rolTrabajador_id',
                't_altasTrabajadores.situacionLaboral_id',
                't_altasTrabajadores.area_id',
                't_altasTrabajadores.cargo_id',
                't_altasTrabajadores.motivoBaja_id',
                't_altasTrabajadores.observacion',
            ])
            ->with([
                'trabajador:id,persona_id,codigo',
                'trabajador.persona:id,paterno,materno,nombre,docIdentidad',
                'condicionLaboral:id,nombre,abreviatura',
                'rolTrabajador:id,nombre',
                'situacionLaboral:id,nombre',
                'area:id,nombre',
                'cargo:id,nombre',
                'motivoBaja:id,nombre',
            ])
            ->where('institucionEducativa_id', $ie->id)
            ->when($request->search, function ($query, string $search) {
                $term = "%{$search}%";
                $query->where(function ($q) use ($term) {
                    $q->whereHas('trabajador.persona', function ($q2) use ($term) {
                        $q2->where('paterno', 'ilike', $term)
                            ->orWhere('materno', 'ilike', $term)
                            ->orWhere('nombre', 'ilike', $term)
                            ->orWhere('docIdentidad', 'like', $term);
                    })->orWhereHas('trabajador', function ($q2) use ($term) {
                        $q2->where('codigo', 'like', $term);
                    })->orWhere('codigoAirsp', 'ilike', $term);
                });
            })
            ->when($request->boolean('solo_activas'), fn ($q) => $q->whereNull('fechaBaja'))
            ->when($request->filled('condicion_id'), fn ($q) => $q->where('condicionLaboral_id', $request->integer('condicion_id')))
            ->orderByDesc('fechaInicio')
            ->paginate(25)
            ->withQueryString();

        $trabajadorIds = $paginator->pluck('trabajador_id')->unique()->toArray();
        if (!empty($trabajadorIds)) {
            $users = User::whereIn('trabajador_id', $trabajadorIds)->get()->keyBy('trabajador_id');
            $userIds = $users->pluck('id')->toArray();
            if (!empty($userIds)) {
                $perfilesIe = UsuarioPerfilIe::whereIn('user_id', $userIds)
                    ->where('institucionEducativa_id', $ie->id)
                    ->where('activo', true)
                    ->with('perfil')
                    ->get()
                    ->keyBy('user_id');

                foreach ($paginator->items() as $alta) {
                    $user = $users->get($alta->trabajador_id);
                    $alta->perfil_ie = $user ? $perfilesIe->get($user->id) : null;
                }
            }
        }

        return $paginator;
    }

    /**
     * Inserta altas masivas para una IE desde datos ya validados (array de DTOs).
     * Crea cada alta y, si la fila trae localInstEduc_id, registra también el
     * local de marcación correspondiente (siempre como registro nuevo).
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array{insertados: int, errores: array<int, string>}
     */
    public function insertarMasivo(InstitucionesEduc $ie, array $rows, int $createdBy): array
    {
        $insertados = 0;
        $errores = [];
        $chunks = array_chunk($rows, 100);

        foreach ($chunks as $chunk) {
            try {
                DB::transaction(function () use ($chunk, $ie, $createdBy, &$insertados) {
                    foreach ($chunk as $row) {
                        // localInstEduc_id no es columna de t_altasTrabajadores: se separa.
                        $localInstEducId = $row['localInstEduc_id'] ?? null;
                        unset($row['localInstEduc_id']);

                        $alta = AltasTrabajadores::create(array_merge($row, [
                            'institucionEducativa_id' => $ie->id,
                            'created_by'              => $createdBy,
                        ]));

                        if ($localInstEducId) {
                            $this->asignarLocalMarcacion(
                                trabajadorId: (int) $row['trabajador_id'],
                                altaTrabajadorId: $alta->id,
                                localInstEducId: (int) $localInstEducId,
                                fechaInicio: $row['fechaInicio'] ?? null,
                                fechaFin: $row['fechaFin'] ?? null,
                                createdBy: $createdBy,
                            );
                        }

                        $insertados++;
                    }
                });
            } catch (\Throwable $e) {
                $errores[] = $e->getMessage();
            }
        }

        return ['insertados' => $insertados, 'errores' => $errores];
    }

    public function crear(CreateAltaTrabajadorDTO $dto): AltasTrabajadores
    {
        return DB::transaction(function () use ($dto) {
            $datos = $dto->toArray();
            unset($datos['perfil_id'], $datos['localInstEduc_id']);

            $alta = AltasTrabajadores::create($datos);

            // Asignar local de marcación (siempre se crea un registro nuevo, nunca se edita).
            if ($dto->localInstEduc_id) {
                $this->asignarLocalMarcacion(
                    trabajadorId: $dto->trabajador_id,
                    altaTrabajadorId: $alta->id,
                    localInstEducId: $dto->localInstEduc_id,
                    fechaInicio: $dto->fechaInicio,
                    fechaFin: $dto->fechaFin,
                    createdBy: $dto->created_by,
                );
            }

            $user = User::where('trabajador_id', $dto->trabajador_id)->first();
            if ($user && $dto->perfil_id) {
                UsuarioPerfilIe::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'institucionEducativa_id' => $dto->institucionEducativa_id,
                    ],
                    [
                        'perfil_id' => $dto->perfil_id,
                        'activo' => true,
                        'created_by' => auth()->id() ?? 1,
                    ]
                );
            }

            return $alta;
        });
    }

    /**
     * @param  array<string, mixed>  $datos
     */
    public function actualizar(AltasTrabajadores $alta, array $datos): bool
    {
        return DB::transaction(function () use ($alta, $datos) {
            $perfilId = $datos['perfil_id'] ?? null;
            // trabajador_id no se modifica en edición; se descarta aquí, no en el controller
            unset($datos['perfil_id'], $datos['trabajador_id']);

            $oldIeId = $alta->getOriginal('institucionEducativa_id');

            $res = $alta->update($datos);

            $user = User::where('trabajador_id', $alta->trabajador_id)->first();
            if ($user) {
                if ($oldIeId && $oldIeId != $alta->institucionEducativa_id) {
                    UsuarioPerfilIe::where('user_id', $user->id)
                        ->where('institucionEducativa_id', $oldIeId)
                        ->delete();
                }

                if ($perfilId) {
                    UsuarioPerfilIe::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'institucionEducativa_id' => $alta->institucionEducativa_id,
                        ],
                        [
                            'perfil_id' => $perfilId,
                            'activo' => true,
                            'created_by' => auth()->id() ?? 1,
                        ]
                    );
                } else {
                    UsuarioPerfilIe::where('user_id', $user->id)
                        ->where('institucionEducativa_id', $alta->institucionEducativa_id)
                        ->delete();
                }
            }

            return $res;
        });
    }

    /**
     * Registra la baja de un alta, cerrando el período laboral.
     */
    public function darBaja(AltasTrabajadores $alta, string $fechaBaja, int $motivoBaja_id): bool
    {
        return DB::transaction(function () use ($alta, $fechaBaja, $motivoBaja_id) {
            $res = $alta->update([
                'fechaBaja' => $fechaBaja,
                'motivoBaja_id' => $motivoBaja_id,
            ]);

            $user = User::where('trabajador_id', $alta->trabajador_id)->first();
            if ($user) {
                UsuarioPerfilIe::where('user_id', $user->id)
                    ->where('institucionEducativa_id', $alta->institucionEducativa_id)
                    ->update(['activo' => false]);
            }

            return $res;
        });
    }

    /**
     * Elimina un alta si aún no tiene baja registrada.
     */
    public function eliminar(AltasTrabajadores $alta): bool
    {
        return DB::transaction(function () use ($alta) {
            $res = $alta->delete();

            $user = User::where('trabajador_id', $alta->trabajador_id)->first();
            if ($user) {
                UsuarioPerfilIe::where('user_id', $user->id)
                    ->where('institucionEducativa_id', $alta->institucionEducativa_id)
                    ->delete();
            }

            return $res;
        });
    }

    /**
     * Crea un registro en conasis.t_localesMarcacion asociando un trabajador a un local de la IE.
     *
     * Regla de negocio: cambiar de local NO edita el registro previo; siempre se crea
     * un registro nuevo. Un trabajador puede tener más de un local de marcación.
     */
    public function asignarLocalMarcacion(
        int $trabajadorId,
        ?int $altaTrabajadorId,
        int $localInstEducId,
        ?string $fechaInicio,
        ?string $fechaFin,
        int $createdBy,
    ): ConasisLocalesMarcacion {
        return ConasisLocalesMarcacion::create([
            'trabajador_id'     => $trabajadorId,
            'altaTrabajador_id' => $altaTrabajadorId,
            'localInstEduc_id'  => $localInstEducId,
            'fechaInicio'       => $fechaInicio,
            'fechaFin'          => $fechaFin,
            'created_by'        => $createdBy,
        ]);
    }
}
