<?php

namespace App\Http\Controllers\Tramite;

use App\Enums\TipoExpediente;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tramite\StoreExoneracionRequest;
use App\Http\Requests\Tramite\StoreExpedienteRequest;
use App\Http\Requests\Tramite\StoreIncapacidadRequest;
use App\Http\Requests\Tramite\StoreJustificacionRequest;
use App\Http\Requests\Tramite\StoreRegistroDirectoRequest;
use App\Http\Requests\Tramite\StoreSuspensionRequest;
use App\Models\Conasis\ConasisDocumentosTram;
use App\Models\Conasis\ConasisExpediente;
use App\Models\InstitucionesEduc;
use App\Models\Trabajador;
use App\Services\Auth\ContextoService;
use App\Services\Tramite\ExpedienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpedienteController extends Controller
{
    public function __construct(
        private ExpedienteService $expedienteService,
        private ContextoService $contextoService,
    ) {}

    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'tipo', 'estado', 'fecha_desde', 'fecha_hasta', 'ie_id']);

        // Defaults para que el frontend muestre los valores activos
        $filters['estado'] ??= 'pendientes';
        $filters['fecha_desde'] ??= now()->startOfMonth()->toDateString();
        $filters['fecha_hasta'] ??= now()->endOfMonth()->toDateString();

        // IEs disponibles para el filtro (según contexto UGEL)
        $ies = InstitucionesEduc::query()
            ->select(['id', 'nombreLegal', 'codigoModular'])
            ->tap(fn ($q) => $this->contextoService->filtrarInstituciones($q))
            ->orderBy('nombreLegal')
            ->get();

        return Inertia::render('tramite/expedientes/Index', [
            'expedientes' => $this->expedienteService->listarPaginado($request),
            'filters' => $filters,
            'ies' => $ies,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('tramite/expedientes/Create');
    }

    public function store(StoreExpedienteRequest $request): RedirectResponse
    {
        $this->expedienteService->crear(
            $request->toDTO(),
            $request->getArchivos(),
        );

        return redirect()->route('expedientes.index')
            ->with('success', 'Expediente registrado correctamente.');
    }

    public function anular(ConasisExpediente $expediente): RedirectResponse
    {
        $this->expedienteService->anular($expediente);

        return redirect()->back()
            ->with('success', 'Expediente anulado.');
    }

    // ── Registro directo (sin expediente previo) ──────────────────────────

    public function registroDirecto(StoreRegistroDirectoRequest $request): JsonResponse
    {
        abort_unless(
            $this->expedienteService->puedeRegistrarCud(auth()->user()),
            403,
            'No tiene competencia para registrar permisos.',
        );

        $validated = $request->validated();

        $expediente = $this->expedienteService->registroDirecto(
            tipoExpediente: $validated['tipoExpediente'],
            trabajadorId: (int) $validated['trabajador_id'],
            altaTrabajadorId: isset($validated['altaTrabajador_id']) ? (int) $validated['altaTrabajador_id'] : null,
            cudData: $request->cudData(),
        );

        return response()->json([
            'message' => 'Registro creado correctamente.',
            'data' => $expediente,
        ], 201);
    }

    // ── Autorizar / Rechazar (post-CUD) ────────────────────────────────────

    public function autorizar(ConasisExpediente $expediente): JsonResponse
    {
        abort_unless(
            $this->expedienteService->puedeResolver($expediente, auth()->user()),
            403,
            'No tiene competencia para autorizar este expediente.',
        );

        $this->expedienteService->autorizar($expediente);

        return response()->json(['message' => 'Expediente autorizado.']);
    }

    public function rechazar(ConasisExpediente $expediente, Request $request): JsonResponse
    {
        abort_unless(
            $this->expedienteService->puedeResolver($expediente, auth()->user()),
            403,
            'No tiene competencia para rechazar este expediente.',
        );

        $this->expedienteService->rechazar(
            $expediente,
            $request->string('motivoRechazo')->toString() ?: null,
        );

        return response()->json(['message' => 'Expediente rechazado.']);
    }

    // ── CUD post-aprobación ─────────────────────────────────────────────────

    public function registrarSuspension(StoreSuspensionRequest $request, ConasisExpediente $expediente): JsonResponse
    {
        abort_unless(
            $this->expedienteService->puedeRegistrarCud(auth()->user()),
            403,
            'No tiene competencia para registrar detalle de expedientes.',
        );

        $registro = $this->expedienteService->registrarSuspension(
            $expediente,
            $request->toDTO(),
        );

        return response()->json([
            'message' => 'Suspensión laboral registrada.',
            'data' => $registro,
        ], 201);
    }

    public function registrarJustificacion(StoreJustificacionRequest $request, ConasisExpediente $expediente): JsonResponse
    {
        abort_unless(
            $this->expedienteService->puedeRegistrarCud(auth()->user()),
            403,
            'No tiene competencia para registrar detalle de expedientes.',
        );

        $registro = $this->expedienteService->registrarJustificacion(
            $expediente,
            $request->toDTO(),
        );

        return response()->json([
            'message' => 'Justificación registrada.',
            'data' => $registro,
        ], 201);
    }

    public function registrarIncapacidad(StoreIncapacidadRequest $request, ConasisExpediente $expediente): JsonResponse
    {
        abort_unless(
            $this->expedienteService->puedeRegistrarCud(auth()->user()),
            403,
            'No tiene competencia para registrar detalle de expedientes.',
        );

        $registro = $this->expedienteService->registrarIncapacidad(
            $expediente,
            $request->toDTO(),
        );

        return response()->json([
            'message' => 'Incapacidad temporal registrada.',
            'data' => $registro,
        ], 201);
    }

    public function registrarExoneracion(StoreExoneracionRequest $request, ConasisExpediente $expediente): JsonResponse
    {
        abort_unless(
            $this->expedienteService->puedeRegistrarCud(auth()->user()),
            403,
            'No tiene competencia para registrar detalle de expedientes.',
        );

        $registro = $this->expedienteService->registrarExoneracion(
            $expediente,
            $request->toDTO(),
        );

        return response()->json([
            'message' => 'Exoneración de marcación registrada.',
            'data' => $registro,
        ], 201);
    }

    // ── API: motivos filtrados ───────────────────────────────────────────────

    public function motivosSuspension(Request $request): JsonResponse
    {
        $competencia = $this->expedienteService->competenciaResolucion(auth()->user());

        abort_unless($competencia !== null, 403, 'No tiene competencia para consultar motivos.');

        // ?todos=1 omite filtro autorizadoPor (usado en registro directo)
        $comp = $request->boolean('todos') ? null : $competencia;

        return response()->json([
            'data' => $this->expedienteService->motivosSuspensionFiltrados($comp),
        ]);
    }

    public function motivosIncapacidad(Request $request): JsonResponse
    {
        $competencia = $this->expedienteService->competenciaResolucion(auth()->user());

        abort_unless($competencia !== null, 403, 'No tiene competencia para consultar motivos.');

        $comp = $request->boolean('todos') ? null : $competencia;

        return response()->json([
            'data' => $this->expedienteService->motivosIncapacidad($comp),
        ]);
    }

    public function motivosJustificacion(Request $request): JsonResponse
    {
        $competencia = $this->expedienteService->competenciaResolucion(auth()->user());

        abort_unless($competencia !== null, 403, 'No tiene competencia para consultar motivos.');

        $comp = $request->boolean('todos') ? null : $competencia;

        return response()->json([
            'data' => $this->expedienteService->motivosJustificacion($comp),
        ]);
    }

    public function motivosExoneracion(Request $request): JsonResponse
    {
        $competencia = $this->expedienteService->competenciaResolucion(auth()->user());

        abort_unless($competencia !== null, 403, 'No tiene competencia para consultar motivos.');

        $comp = $request->boolean('todos') ? null : $competencia;

        return response()->json([
            'data' => $this->expedienteService->motivosExoneracion($comp),
        ]);
    }

    public function descargarDocumento(ConasisDocumentosTram $documentoTram): StreamedResponse
    {
        return $this->expedienteService->descargarDocumento($documentoTram);
    }

    // ── API JSON (para tabs embebidos) ───────────────────────────────────────

    public function detalleJson(ConasisExpediente $expediente): JsonResponse
    {
        $exp = $this->expedienteService->obtener($expediente);

        // Agregar info de competencia de resolución del usuario actual
        $user = auth()->user();
        $puedeResolver = $this->expedienteService->puedeResolver($expediente, $user);

        // Determinar si ya tiene detalle CUD registrado
        $tieneDetalle = match ($expediente->tipoExpediente) {
            TipoExpediente::Suspension => $expediente->suspension !== null,
            TipoExpediente::Justificacion => $expediente->justificacion !== null,
            TipoExpediente::Incapacidad => $expediente->incapacidad !== null,
            TipoExpediente::Exoneracion => $expediente->exoneracion !== null,
            default => false,
        };

        $puedeRegistrarCud = $this->expedienteService->puedeRegistrarCud($user);

        return response()->json([
            ...$exp->toArray(),
            'puedeResolver' => $puedeResolver,
            'puedeRegistrarCud' => $puedeRegistrarCud,
            'tieneDetalle' => $tieneDetalle,
        ]);
    }

    public function porTrabajador(Trabajador $trabajador): JsonResponse
    {
        return response()->json([
            'data' => $this->expedienteService->listarPorTrabajador($trabajador),
        ]);
    }

    public function porInstitucion(Request $request, InstitucionesEduc $institucione): JsonResponse
    {
        return response()->json(
            $this->expedienteService->listarPorInstitucion($institucione, $request),
        );
    }

    public function personalActivo(InstitucionesEduc $institucione): JsonResponse
    {
        return response()->json([
            'data' => $this->expedienteService->personalActivoPorInstitucion($institucione),
        ]);
    }

    public function altasActivas(Trabajador $trabajador): JsonResponse
    {
        return response()->json([
            'data' => $this->expedienteService->altasActivasPorTrabajador($trabajador),
        ]);
    }
}
