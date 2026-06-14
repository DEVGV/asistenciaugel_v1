<?php

namespace App\Services\Tramite;

use App\DTOs\Tramite\CreatePermisoDTO;
use App\DTOs\Tramite\ValidarPermisoDTO;
use App\Enums\EstadoTramite;
use App\Enums\TipoPermiso;
use App\Models\AltasTrabajadores;
use App\Models\Conasis\ConasisDocumentosTram;
use App\Models\Conasis\ConasisExoneracionesMarcacion;
use App\Models\Conasis\ConasisExpediente;
use App\Models\Conasis\ConasisJustificaciones;
use App\Models\InstitucionesEduc;
use App\Models\Param\ParamEstadosTram;
use App\Models\Trabajador;
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

class PermisoService
{
    /**
     * Relaciones estándar de un expediente de permiso.
     *
     * @var array<int, string>
     */
    private const RELACIONES = [
        'estado',
        'trabajador.persona:id,paterno,materno,nombre,docIdentidad',
        'documentos.documento',
        'justificaciones.altaTrabajador.institucionEducativa:id,nombreLegal,codigoInstitucion',
        'justificaciones.altaTrabajador.cargo:id,nombre',
        'exoneraciones.altaTrabajador.institucionEducativa:id,nombreLegal,codigoInstitucion',
        'exoneraciones.altaTrabajador.cargo:id,nombre',
    ];

    /**
     * Lista las solicitudes de permiso (expedientes con detalle de
     * justificación o exoneración) de un trabajador.
     *
     * @return Collection<int, ConasisExpediente>
     */
    public function listarPorTrabajador(Trabajador $trabajador): Collection
    {
        return $this->basePermisos()
            ->where('trabajador_id', $trabajador->id)
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Lista paginada de solicitudes de permiso del personal de una IE,
     * con filtros por estado y búsqueda por trabajador.
     *
     * @return LengthAwarePaginator<int, ConasisExpediente>
     */
    public function listarPorInstitucion(InstitucionesEduc $institucion, Request $request): LengthAwarePaginator
    {
        return $this->basePermisos()
            ->where(function (Builder $q) use ($institucion) {
                $q->whereHas('justificaciones.altaTrabajador', fn (Builder $qq) => $qq->where('institucionEducativa_id', $institucion->id))
                    ->orWhereHas('exoneraciones.altaTrabajador', fn (Builder $qq) => $qq->where('institucionEducativa_id', $institucion->id));
            })
            ->when($request->string('estado')->toString(), fn (Builder $q, string $codigo) => $q->where('estado_id', $this->estadoId(EstadoTramite::from($codigo))))
            ->when($request->string('search')->toString(), fn (Builder $q, string $search) => $q->whereHas('trabajador.persona', fn (Builder $qq) => $qq
                ->where('paterno', 'ilike', "%{$search}%")
                ->orWhere('materno', 'ilike', "%{$search}%")
                ->orWhere('nombre', 'ilike', "%{$search}%")
                ->orWhere('docIdentidad', 'ilike', "%{$search}%")))
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Opciones de altas activas de una IE para el select del formulario.
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function opcionesAltasPorInstitucion(InstitucionesEduc $institucion): \Illuminate\Support\Collection
    {
        return AltasTrabajadores::query()
            ->with(['trabajador.persona:id,paterno,materno,nombre,docIdentidad', 'cargo:id,nombre'])
            ->where('institucionEducativa_id', $institucion->id)
            ->whereNull('fechaBaja')
            ->get()
            ->map(function (AltasTrabajadores $alta) {
                $persona = $alta->trabajador?->persona;
                $apellidos = collect([$persona?->paterno, $persona?->materno])->filter()->implode(' ');

                return [
                    'id'            => $alta->id,
                    'trabajador_id' => $alta->trabajador_id,
                    'label'         => trim(collect([$apellidos.',', $persona?->nombre])->filter()->implode(' '), ', '),
                    'docIdentidad'  => $persona?->docIdentidad,
                    'cargo'         => $alta->cargo?->nombre,
                ];
            })
            ->sortBy('label')
            ->values();
    }

    /**
     * Crea la solicitud de permiso: expediente + documento de sustento + detalle
     * (justificación de días o exoneración de marcación).
     */
    public function crear(CreatePermisoDTO $dto, UploadedFile $sustento): ConasisExpediente
    {
        return DB::transaction(function () use ($dto, $sustento) {
            $hoy = now()->toDateString();

            $expediente = ConasisExpediente::create([
                'anio'          => now()->year,
                'trabajador_id' => $dto->trabajador_id,
                'asunto'        => $dto->asunto,
                'fecha'         => $hoy,
                'observacion'   => $dto->observacion,
                'estado_id'     => $this->estadoId(EstadoTramite::Pendiente),
                'created_by'    => auth()->id(),
            ]);

            $rutaDoc = $sustento->storeAs(
                'permisos/'.now()->year,
                $expediente->codigo.'_'.Str::random(8).'.'.$sustento->getClientOriginalExtension(),
            );

            ConasisDocumentosTram::create([
                'expediente_id'   => $expediente->id,
                'documento_id'    => $dto->documento_id,
                'nroDoc'          => $dto->nroDoc,
                'fechaDoc'        => $hoy,
                'trabajadorDoc_id' => $dto->trabajador_id,
                'rutaDoc'         => $rutaDoc,
                'observacion'     => 'Sustento de solicitud de permiso',
                'created_by'      => auth()->id(),
            ]);

            $detalle = [
                'trabajador_id'     => $dto->trabajador_id,
                'altaTrabajador_id' => $dto->altaTrabajador_id,
                'turno'             => $dto->turno,
                'fechaInicio'       => $dto->fechaInicio,
                'fechaFin'          => $dto->fechaFin,
                'expediente_id'     => $expediente->id,
                'observacion'       => $dto->observacion,
                'created_by'        => auth()->id(),
            ];

            if (TipoPermiso::from($dto->tipo) === TipoPermiso::Exoneracion) {
                ConasisExoneracionesMarcacion::create($detalle + ['marcaApli' => $dto->marcaApli]);
            } else {
                ConasisJustificaciones::create($detalle + ['marcaApli' => $dto->marcaApli ?? 'ES']);
            }

            return $expediente->load(self::RELACIONES);
        });
    }

    /**
     * Valida (aprueba o rechaza) una solicitud pendiente desde la IE.
     */
    public function validar(ConasisExpediente $expediente, ValidarPermisoDTO $dto): ConasisExpediente
    {
        abort_unless(
            (int) $expediente->estado_id === $this->estadoId(EstadoTramite::Pendiente),
            422,
            'Solo se pueden validar solicitudes pendientes.',
        );

        $expediente->update([
            'estado_id'   => $this->estadoId(EstadoTramite::from($dto->estado)),
            'observacion' => $dto->observacion ?: $expediente->observacion,
        ]);

        return $expediente->fresh(self::RELACIONES);
    }

    /**
     * Anula una solicitud pendiente (equivalente a eliminar, sin borrar registros).
     */
    public function anular(ConasisExpediente $expediente): void
    {
        abort_unless(
            (int) $expediente->estado_id === $this->estadoId(EstadoTramite::Pendiente),
            422,
            'Solo se pueden anular solicitudes pendientes.',
        );

        $expediente->update(['estado_id' => $this->estadoId(EstadoTramite::Anulado)]);
    }

    /**
     * Descarga el documento de sustento de un trámite.
     */
    public function descargarSustento(ConasisDocumentosTram $documento): StreamedResponse
    {
        abort_unless(
            $documento->rutaDoc && Storage::exists($documento->rutaDoc),
            404,
            'El documento de sustento no se encuentra disponible.',
        );

        return Storage::download($documento->rutaDoc, basename($documento->rutaDoc));
    }

    /**
     * Query base: expedientes que son solicitudes de permiso
     * (tienen justificación o exoneración asociada) y no están anulados.
     *
     * @return Builder<ConasisExpediente>
     */
    private function basePermisos(): Builder
    {
        return ConasisExpediente::query()
            ->with(self::RELACIONES)
            ->where(function (Builder $q) {
                $q->whereHas('justificaciones')->orWhereHas('exoneraciones');
            })
            ->where('estado_id', '!=', $this->estadoId(EstadoTramite::Anulado));
    }

    /**
     * Resuelve (con caché) el id del estado de trámite por su código.
     */
    private function estadoId(EstadoTramite $estado): int
    {
        return Cache::rememberForever(
            "param.estados-tramite.{$estado->value}",
            function () use ($estado) {
                $id = ParamEstadosTram::query()->where('codigo', $estado->value)->value('id');

                abort_unless($id !== null, 500, "Estado de trámite '{$estado->value}' no configurado. Ejecute el seeder ParamTramiteSeeder.");

                return (int) $id;
            },
        );
    }
}
