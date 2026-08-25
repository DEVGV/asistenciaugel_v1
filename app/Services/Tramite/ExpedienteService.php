<?php

namespace App\Services\Tramite;

use App\DTOs\Tramite\CreateExoneracionDTO;
use App\DTOs\Tramite\CreateExpedienteDTO;
use App\DTOs\Tramite\CreateIncapacidadDTO;
use App\DTOs\Tramite\CreateJustificacionDTO;
use App\DTOs\Tramite\CreateSuspensionDTO;
use App\DTOs\Tramite\UpdateExpedienteDTO;
use App\Enums\AutorizadoPor;
use App\Enums\EstadoTramite;
use App\Enums\TipoExpediente;
use App\Models\AltasTrabajadores;
use App\Models\Conasis\ConasisDocumentosTram;
use App\Models\Conasis\ConasisExoneracionesMarcacion;
use App\Models\Conasis\ConasisExpediente;
use App\Models\Conasis\ConasisIncapsTempTrab;
use App\Models\Conasis\ConasisJustificaciones;
use App\Models\Conasis\ConasisSuspLabTrabajador;
use App\Models\InstitucionesEduc;
use App\Models\Param\ParamEstadosTram;
use App\Models\Param\ParamMotivosSuspLab;
use App\Models\Trabajador;
use App\Models\User;
use App\Services\Auth\ContextoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpedienteService
{
    /** @var array<int, string> */
    private const RELACIONES = [
        'estado',
        'trabajador.persona:id,paterno,materno,nombre,docIdentidad',
        'alta.institucionEducativa:id,nombreLegal,codigoModular',
        'documentos.documento',
        'suspension.motivoSuspLab',
        'justificacion.motivoSuspLab',
        'incapacidad.motivoSuspLab',
        'exoneracion.motivoSuspLab',
    ];

    /**
     * Lista paginada de expedientes con filtros opcionales.
     *
     * Defaults: estado "pendientes" (Registrado + Aprobado), mes actual.
     *
     * @return LengthAwarePaginator<ConasisExpediente>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        // Defaults: mes actual
        $fechaDesde = $request->string('fecha_desde')->toString() ?: now()->startOfMonth()->toDateString();
        $fechaHasta = $request->string('fecha_hasta')->toString() ?: now()->endOfMonth()->toDateString();

        // Default estado: "pendientes" (Registrado + Aprobado = sin atender)
        $estado = $request->string('estado')->toString() ?: 'pendientes';

        return ConasisExpediente::query()
            ->with(self::RELACIONES)
            ->when($request->string('tipo')->toString(), fn ($q, string $tipo) => $q->where('tipoExpediente', $tipo))
            ->when($request->string('search')->toString(), fn ($q, string $search) => $q->whereHas(
                'trabajador.persona',
                fn ($qq) => $qq
                    ->where('paterno', 'ilike', "%{$search}%")
                    ->orWhere('materno', 'ilike', "%{$search}%")
                    ->orWhere('nombre', 'ilike', "%{$search}%")
                    ->orWhere('docIdentidad', 'ilike', "%{$search}%"),
            ))
            // Filtro de estado
            ->when($estado === 'pendientes', function ($q) {
                $q->whereIn('estado_id', [
                    $this->estadoId(EstadoTramite::Registrado),
                    $this->estadoId(EstadoTramite::Aprobado),
                ]);
            })
            ->when($estado !== 'pendientes' && $estado !== 'todos', function ($q) use ($estado) {
                $q->where('estado_id', $this->estadoId(EstadoTramite::from($estado)));
            })
            // Filtro de fechas
            ->when($fechaDesde, fn ($q) => $q->where('fecha', '>=', $fechaDesde))
            ->when($fechaHasta, fn ($q) => $q->where('fecha', '<=', $fechaHasta))
            // Filtro por IE (a través de la alta)
            ->when($request->filled('ie_id'), fn ($q) => $q->whereHas(
                'alta',
                fn ($qq) => $qq->where('institucionEducativa_id', (int) $request->input('ie_id')),
            ))
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * Lista expedientes de un trabajador (para tab JSON).
     *
     * @return Collection<int, ConasisExpediente>
     */
    public function listarPorTrabajador(Trabajador $trabajador): Collection
    {
        return ConasisExpediente::query()
            ->with(self::RELACIONES)
            ->where('trabajador_id', $trabajador->id)
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Lista paginada de expedientes de una IE (para tab JSON).
     *
     * @return LengthAwarePaginator<ConasisExpediente>
     */
    public function listarPorInstitucion(InstitucionesEduc $institucion, Request $request): LengthAwarePaginator
    {
        return ConasisExpediente::query()
            ->with(self::RELACIONES)
            ->whereHas(
                'trabajador.altas',
                fn (Builder $q) => $q
                    ->where('institucionEducativa_id', $institucion->id)
                    ->whereNull('fechaBaja'),
            )
            ->when($request->string('search')->toString(), fn ($q, string $search) => $q->whereHas(
                'trabajador.persona',
                fn ($qq) => $qq
                    ->where('paterno', 'ilike', "%{$search}%")
                    ->orWhere('materno', 'ilike', "%{$search}%")
                    ->orWhere('nombre', 'ilike', "%{$search}%")
                    ->orWhere('docIdentidad', 'ilike', "%{$search}%"),
            ))
            ->when($request->string('tipo')->toString(), fn ($q, string $tipo) => $q->where('tipoExpediente', $tipo))
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Personal activo (altas vigentes) de una IE para el select del formulario.
     * Devuelve una entrada por alta (no por trabajador) incluyendo alta_id.
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function personalActivoPorInstitucion(InstitucionesEduc $institucion): \Illuminate\Support\Collection
    {
        return AltasTrabajadores::query()
            ->with([
                'trabajador.persona:id,paterno,materno,nombre,docIdentidad',
                'cargo:id,nombre',
            ])
            ->where('institucionEducativa_id', $institucion->id)
            ->whereNull('fechaBaja')
            ->get()
            ->map(function (AltasTrabajadores $alta) {
                $persona = $alta->trabajador?->persona;
                $apellidos = collect([$persona?->paterno, $persona?->materno])
                    ->filter()
                    ->implode(' ');
                $nombre = trim(collect([$apellidos, $persona?->nombre])->filter()->implode(', '));

                return [
                    'alta_id' => $alta->id,
                    'trabajador_id' => $alta->trabajador_id,
                    'label' => $nombre ?: "Trabajador #{$alta->trabajador_id}",
                    'docIdentidad' => $persona?->docIdentidad,
                    'cargo' => $alta->cargo?->nombre,
                ];
            })
            ->sortBy('label')
            ->values();
    }

    /**
     * Altas vigentes de un trabajador para el select de IE en el formulario.
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function altasActivasPorTrabajador(Trabajador $trabajador): \Illuminate\Support\Collection
    {
        return AltasTrabajadores::query()
            ->with([
                'institucionEducativa:id,nombreLegal,codigoInstitucion',
                'cargo:id,nombre',
            ])
            ->where('trabajador_id', $trabajador->id)
            ->whereNull('fechaBaja')
            ->get()
            ->map(fn (AltasTrabajadores $alta) => [
                'alta_id' => $alta->id,
                'label' => $alta->institucionEducativa?->nombreLegal ?? "IE #{$alta->institucionEducativa_id}",
                'sublabel' => $alta->cargo?->nombre,
            ])
            ->values();
    }

    /**
     * Retorna un expediente con sus relaciones cargadas.
     */
    public function obtener(ConasisExpediente $expediente): ConasisExpediente
    {
        return $expediente->load(self::RELACIONES);
    }

    /**
     * Crea un expediente con sus documentos asociados.
     * El array $archivos es indexado igual que $dto->documentos;
     * cada entrada puede ser un UploadedFile o null.
     *
     * @param  array<int, UploadedFile|null>  $archivos
     */
    public function crear(CreateExpedienteDTO $dto, array $archivos = []): ConasisExpediente
    {
        return DB::transaction(function () use ($dto, $archivos) {
            $expediente = ConasisExpediente::create([
                'tipoExpediente' => $dto->tipoExpediente,
                'anio' => now()->year,
                'trabajador_id' => $dto->trabajador_id,
                'altaTrabajador_id' => $dto->altaTrabajador_id,
                'asunto' => $dto->asunto,
                'fecha' => $dto->fecha,
                'observacion' => $dto->observacion,
                'estado_id' => $this->estadoId(EstadoTramite::Registrado),
                'created_by' => auth()->id(),
            ]);

            foreach ($dto->documentos as $index => $docDTO) {
                $rutaDoc = null;

                $archivo = $archivos[$index] ?? null;

                if ($archivo instanceof UploadedFile) {
                    $rutaDoc = $archivo->storeAs(
                        'expedientes/'.now()->year,
                        $expediente->codigo
                            .'_doc'.($index + 1)
                            .'_'.Str::random(6)
                            .'.'.$archivo->getClientOriginalExtension(),
                    );
                }

                ConasisDocumentosTram::create($docDTO->toArray() + [
                    'expediente_id' => $expediente->id,
                    'trabajadorDoc_id' => $dto->trabajador_id,
                    'rutaDoc' => $rutaDoc,
                    'created_by' => auth()->id(),
                ]);
            }

            return $expediente->load(self::RELACIONES);
        });
    }

    /**
     * Actualiza los campos principales del expediente.
     * Solo se permite modificar mientras esté en estado Registrado.
     */
    public function actualizar(ConasisExpediente $expediente, UpdateExpedienteDTO $dto): ConasisExpediente
    {
        abort_unless(
            (int) $expediente->estado_id === $this->estadoId(EstadoTramite::Registrado),
            422,
            'Solo se pueden editar expedientes en estado Registrado.',
        );

        $expediente->update($dto->toArray());

        return $expediente->fresh(self::RELACIONES);
    }

    /**
     * Anula un expediente (cambia estado a Anulado).
     */
    public function anular(ConasisExpediente $expediente): void
    {
        abort_unless(
            (int) $expediente->estado_id === $this->estadoId(EstadoTramite::Registrado),
            422,
            'Solo se pueden anular expedientes en estado Registrado.',
        );

        $expediente->update(['estado_id' => $this->estadoId(EstadoTramite::Anulado)]);
    }

    /**
     * Aprueba un expediente (cambia estado a Aprobado).
     * Solo permitido si el expediente está en estado Registrado.
     */
    /**
     * Autoriza un expediente aprobado (Aprobado → Autorizado).
     * Solo la autoridad competente (según autorizadoPor) puede autorizar.
     */
    public function autorizar(ConasisExpediente $expediente): void
    {
        abort_unless(
            (int) $expediente->estado_id === $this->estadoId(EstadoTramite::Aprobado),
            422,
            'Solo se pueden autorizar expedientes en estado Aprobado.',
        );

        $expediente->update(['estado_id' => $this->estadoId(EstadoTramite::Autorizado)]);
    }

    /**
     * Rechaza un expediente aprobado (Aprobado → Rechazado).
     * Solo la autoridad competente (según autorizadoPor) puede rechazar.
     */
    public function rechazar(ConasisExpediente $expediente, ?string $motivoRechazo = null): void
    {
        abort_unless(
            (int) $expediente->estado_id === $this->estadoId(EstadoTramite::Aprobado),
            422,
            'Solo se pueden rechazar expedientes en estado Aprobado.',
        );

        $datos = ['estado_id' => $this->estadoId(EstadoTramite::Rechazado)];

        if ($motivoRechazo) {
            $datos['observacion'] = $motivoRechazo;
        }

        $expediente->update($datos);
    }

    /**
     * Verifica si el usuario puede autorizar/rechazar un expediente
     * (transición Aprobado → Autorizado / Rechazado).
     *
     * Solo Admin UGEL puede autorizar.
     */
    public function puedeResolver(ConasisExpediente $expediente, User $user): bool
    {
        return $this->esAdminUgel($user);
    }

    /**
     * Verifica si el usuario puede registrar el detalle CUD de un expediente
     * (transición Registrado → Aprobado).
     *
     * Director y Admin UGEL pueden registrar CUD.
     */
    public function puedeRegistrarCud(User $user): bool
    {
        if ($this->esAdminUgel($user)) {
            return true;
        }

        $ieId = app(ContextoService::class)->ieId();

        return $this->esDirector($user, $ieId);
    }

    /**
     * Determina la competencia de resolución que tiene el usuario:
     * 'U' si es admin UGEL, 'D' si es director, null si ninguno.
     */
    public function competenciaResolucion(User $user): ?string
    {
        if ($this->esAdminUgel($user)) {
            return AutorizadoPor::Ugel->value;
        }

        $ieId = app(ContextoService::class)->ieId();

        if ($this->esDirector($user, $ieId)) {
            return AutorizadoPor::Director->value;
        }

        return null;
    }

    /**
     * Verifica si el usuario es Director de la IE indicada.
     */
    private function esDirector(User $user, ?int $ieId): bool
    {
        $perfil = $user->perfilActivoParaIe($ieId);

        return $perfil === 'Director';
    }

    /**
     * Verifica si el usuario es administrador de UGEL o global.
     */
    private function esAdminUgel(User $user): bool
    {
        return $user->perfilesIe()
            ->where('activo', true)
            ->where(function ($q) {
                $q->where(fn ($w) => $w->whereNull('institucionEducativa_id')->whereNull('entidadUgel_id'))
                    ->orWhere(fn ($w) => $w->whereNull('institucionEducativa_id')->whereNotNull('entidadUgel_id'));
            })
            ->exists();
    }

    // ── Registro directo (sin expediente previo) ──────────────────────────

    /**
     * Crea un expediente + detalle CUD en un solo paso (registro directo).
     * El expediente se crea en estado Registrado, y al registrar el CUD
     * se transiciona a Aprobado (pendiente de autorización UGEL).
     *
     * @param  array<string, mixed>  $cudData  Datos validados del detalle CUD
     */
    public function registroDirecto(
        string $tipoExpediente,
        int $trabajadorId,
        ?int $altaTrabajadorId,
        array $cudData,
    ): ConasisExpediente {
        return DB::transaction(function () use ($tipoExpediente, $trabajadorId, $altaTrabajadorId, $cudData) {
            $tipo = TipoExpediente::from($tipoExpediente);

            $expediente = ConasisExpediente::create([
                'tipoExpediente' => $tipoExpediente,
                'anio' => now()->year,
                'trabajador_id' => $trabajadorId,
                'altaTrabajador_id' => $altaTrabajadorId,
                'asunto' => 'Registro directo — '.$tipo->nombre(),
                'fecha' => now()->toDateString(),
                'estado_id' => $this->estadoId(EstadoTramite::Registrado),
                'created_by' => auth()->id(),
            ]);

            match ($tipo) {
                TipoExpediente::Suspension => $this->registrarSuspension($expediente, CreateSuspensionDTO::from($cudData)),
                TipoExpediente::Justificacion => $this->registrarJustificacion($expediente, CreateJustificacionDTO::from($cudData)),
                TipoExpediente::Incapacidad => $this->registrarIncapacidad($expediente, CreateIncapacidadDTO::from($cudData)),
                TipoExpediente::Exoneracion => $this->registrarExoneracion($expediente, CreateExoneracionDTO::from($cudData)),
            };

            return $expediente->load(self::RELACIONES);
        });
    }

    // ── CUD post-aprobación ─────────────────────────────────────────────────

    /**
     * Registra la suspensión laboral vinculada al expediente.
     * Cambia estado: Registrado → Aprobado (pendiente de autorización).
     */
    public function registrarSuspension(ConasisExpediente $expediente, CreateSuspensionDTO $dto): ConasisSuspLabTrabajador
    {
        $this->validarExpedienteRegistrado($expediente, TipoExpediente::Suspension);

        return DB::transaction(function () use ($expediente, $dto) {
            $registro = ConasisSuspLabTrabajador::create([
                'trabajador_id' => $expediente->trabajador_id,
                'altaTrabajador_id' => $expediente->altaTrabajador_id,
                'motivoSuspLab_id' => $dto->motivoSuspLab_id,
                'fechaHoraInicio' => $dto->fechaHoraInicio,
                'fechaHoraFin' => $dto->fechaHoraFin,
                'totalDias' => $dto->totalDias,
                'totalHoras' => $dto->totalHoras,
                'observacion' => $dto->observacion,
                'expediente_id' => $expediente->id,
                'created_by' => auth()->id(),
            ]);

            $expediente->update(['estado_id' => $this->estadoId(EstadoTramite::Aprobado)]);

            return $registro;
        });
    }

    /**
     * Registra la justificación vinculada al expediente.
     * Cambia estado: Registrado → Aprobado.
     */
    public function registrarJustificacion(ConasisExpediente $expediente, CreateJustificacionDTO $dto): ConasisJustificaciones
    {
        $this->validarExpedienteRegistrado($expediente, TipoExpediente::Justificacion);

        return DB::transaction(function () use ($expediente, $dto) {
            $registro = ConasisJustificaciones::create([
                'trabajador_id' => $expediente->trabajador_id,
                'altaTrabajador_id' => $expediente->altaTrabajador_id,
                'motivoSuspLab_id' => $dto->motivoSuspLab_id,
                'turno' => $dto->turno,
                'fechaInicio' => $dto->fechaInicio,
                'fechaFin' => $dto->fechaFin,
                'observacion' => $dto->observacion,
                'expediente_id' => $expediente->id,
                'created_by' => auth()->id(),
            ]);

            $expediente->update(['estado_id' => $this->estadoId(EstadoTramite::Aprobado)]);

            return $registro;
        });
    }

    /**
     * Registra la incapacidad temporal vinculada al expediente.
     * Cambia estado: Registrado → Aprobado.
     */
    public function registrarIncapacidad(ConasisExpediente $expediente, CreateIncapacidadDTO $dto): ConasisIncapsTempTrab
    {
        $this->validarExpedienteRegistrado($expediente, TipoExpediente::Incapacidad);

        return DB::transaction(function () use ($expediente, $dto) {
            $registro = ConasisIncapsTempTrab::create([
                'trabajador_id' => $expediente->trabajador_id,
                'altaTrabajador_id' => $expediente->altaTrabajador_id,
                'motivoSuspLab_id' => $dto->motivoSuspLab_id,
                'condicionSubsidio' => $dto->condicionSubsidio,
                'fechaInicio' => $dto->fechaInicio,
                'fechaFin' => $dto->fechaFin,
                'nroDias' => $dto->nroDias,
                'nroCertificado' => $dto->nroCertificado,
                'observacion' => $dto->observacion,
                'expediente_id' => $expediente->id,
                'created_by' => auth()->id(),
            ]);

            $expediente->update(['estado_id' => $this->estadoId(EstadoTramite::Aprobado)]);

            return $registro;
        });
    }

    /**
     * Registra la exoneración de marcación vinculada al expediente.
     * Cambia estado: Registrado → Aprobado.
     */
    public function registrarExoneracion(ConasisExpediente $expediente, CreateExoneracionDTO $dto): ConasisExoneracionesMarcacion
    {
        $this->validarExpedienteRegistrado($expediente, TipoExpediente::Exoneracion);

        return DB::transaction(function () use ($expediente, $dto) {
            $registro = ConasisExoneracionesMarcacion::create([
                'trabajador_id' => $expediente->trabajador_id,
                'altaTrabajador_id' => $expediente->altaTrabajador_id,
                'motivoSuspLab_id' => $dto->motivoSuspLab_id,
                'fechaInicio' => $dto->fechaInicio,
                'fechaFin' => $dto->fechaFin,
                'observacion' => $dto->observacion,
                'expediente_id' => $expediente->id,
                'created_by' => auth()->id(),
            ]);

            $expediente->update(['estado_id' => $this->estadoId(EstadoTramite::Aprobado)]);

            return $registro;
        });
    }

    /**
     * Obtiene los motivos de suspensión filtrados por codigoProg para el formulario.
     *
     * @return \Illuminate\Support\Collection<int, ParamMotivosSuspLab>
     */
    /**
     * Motivos de suspensión (excluye incapacidad "7 sit").
     * Director: solo ve motivos con autorizadoPor='D'.
     * Admin UGEL: ve todos.
     */
    public function motivosSuspensionFiltrados(?string $competencia = null): \Illuminate\Support\Collection
    {
        return ParamMotivosSuspLab::query()
            ->where(function ($q) {
                $q->where('activo', true)->orWhereNull('activo');
            })
            ->where('codigoProg', 'ilike', 'SUSP')
            ->when(
                $competencia === AutorizadoPor::Director->value,
                fn ($q) => $q->where('autorizadoPor', AutorizadoPor::Director->value)
            )
            ->orderBy('descripcion')
            ->get();
    }

    /**
     * Motivos de incapacidad temporal (codigoProg = '7 sit').
     * Director: solo ve motivos con autorizadoPor='D'.
     * Admin UGEL: ve todos.
     */
    public function motivosIncapacidad(?string $competencia = null): \Illuminate\Support\Collection
    {
        return ParamMotivosSuspLab::query()
            ->where(function ($q) {
                $q->where('activo', true)->orWhereNull('activo');
            })
            ->where('codigoProg', 'ilike', '7_CITT')
            ->when(
                $competencia === AutorizadoPor::Director->value,
                fn ($q) => $q->where('autorizadoPor', AutorizadoPor::Director->value)
            )
            ->orderBy('descripcion')
            ->get();
    }

    /**
     * Motivos de justificación (todos los motivos activos).
     */
    public function motivosJustificacion(?string $competencia = null): \Illuminate\Support\Collection
    {
        return ParamMotivosSuspLab::query()
            ->where(function ($q) {
                $q->where('activo', true)->orWhereNull('activo');
            })
            ->when(
                $competencia === AutorizadoPor::Director->value,
                fn ($q) => $q->where('autorizadoPor', AutorizadoPor::Director->value)
            )
            ->orderBy('descripcion')
            ->get();
    }

    /**
     * Motivos de exoneración (todos los motivos activos).
     */
    public function motivosExoneracion(?string $competencia = null): \Illuminate\Support\Collection
    {
        return ParamMotivosSuspLab::query()
            ->where(function ($q) {
                $q->where('activo', true)->orWhereNull('activo');
            })
            ->when(
                $competencia === AutorizadoPor::Director->value,
                fn ($q) => $q->where('autorizadoPor', AutorizadoPor::Director->value)
            )
            ->orderBy('descripcion')
            ->get();
    }

    /**
     * Valida que el expediente esté en estado Registrado (sin detalle aún) y sea del tipo correcto.
     */
    private function validarExpedienteRegistrado(ConasisExpediente $expediente, TipoExpediente $tipoEsperado): void
    {
        abort_unless(
            (int) $expediente->estado_id === $this->estadoId(EstadoTramite::Registrado),
            422,
            'El expediente debe estar en estado Registrado para registrar el detalle.',
        );

        abort_unless(
            $expediente->tipoExpediente === $tipoEsperado,
            422,
            "El expediente no es de tipo {$tipoEsperado->nombre()}.",
        );
    }

    /**
     * Descarga un documento de trámite desde el storage.
     */
    public function descargarDocumento(ConasisDocumentosTram $documento): StreamedResponse
    {
        abort_unless(
            $documento->rutaDoc && Storage::exists($documento->rutaDoc),
            404,
            'El documento no se encuentra disponible.',
        );

        return Storage::download($documento->rutaDoc, basename($documento->rutaDoc));
    }

    /**
     * Resuelve (con caché) el id del estado de trámite por su código.
     */
    private function estadoId(EstadoTramite $estado): int
    {
        $key = "param.estados-tramite.{$estado->value}";

        $cached = Cache::get($key);

        if ($cached !== null) {
            return (int) $cached;
        }

        $id = ParamEstadosTram::query()->where('codigo', $estado->value)->value('id');

        abort_unless($id !== null, 500, "Estado '{$estado->value}' no configurado. Ejecute ParamTramiteSeeder.");

        Cache::forever($key, (int) $id);

        return (int) $id;
    }
}
