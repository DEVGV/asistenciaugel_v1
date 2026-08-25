<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreInstEducRequest;
use App\Http\Requests\InstitucionEducativa\UpdateInstEducRequest;
use App\Models\InstitucionesEduc;
use App\Services\Infraestructura\LocalInstEducService;
use App\Services\InstitucionEducativa\InstitucionEducativaService;
use App\Services\Trabajador\AltaTrabajadorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InstitucionEducativaController extends Controller
{
    public function __construct(
        private InstitucionEducativaService $ieService,
        private AltaTrabajadorService $altaService,
        private LocalInstEducService $localInstEducService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('institucion-educativa/Index', [
            'instituciones' => $this->ieService->listarPaginado($request),
            'filters'       => $request->only(['search']),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->ieService->buscarParaSelect($request));
    }

    public function store(StoreInstEducRequest $request): RedirectResponse
    {
        $this->ieService->crear($request->toDTO());

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución educativa registrada exitosamente.');
    }

    public function show(InstitucionesEduc $institucione): Response
    {
        return Inertia::render('institucion-educativa/Show', [
            'institucion' => $this->ieService->obtenerConRelaciones($institucione),
        ]);
    }

    public function showTab(InstitucionesEduc $institucione, string $tab): Response
    {
        // 'no-laborables' en URL → 'diasNoLaborables' como clave de tab en el frontend
        $tabMap = [
            'no-laborables' => 'diasNoLaborables',
            'consolidado-asistencia' => 'consolidadoAsistencia',
        ];

        return Inertia::render('institucion-educativa/Show', [
            'institucion' => $this->ieService->obtenerConRelaciones($institucione),
            'activeTab'   => $tabMap[$tab] ?? $tab,
        ]);
    }

    public function docentes(InstitucionesEduc $institucione, Request $request): Response
    {
        return Inertia::render('institucion-educativa/Show', [
            'institucion'     => $this->ieService->obtenerConRelaciones($institucione),
            'docentes'        => $this->altaService->listarPorInstitucion($institucione, $request),
            'docentesFiltros' => $request->only(['search', 'solo_activas', 'condicion_id']),
            'activeTab'       => 'docentes',
        ]);
    }

    public function locales(InstitucionesEduc $institucione): JsonResponse
    {
        return response()->json([
            'data' => $this->localInstEducService->opcionesParaSelect($institucione),
        ]);
    }

    public function detallesJson(InstitucionesEduc $institucione): JsonResponse
    {
        return response()->json([
            'data' => $this->ieService->obtenerConRelaciones($institucione),
        ]);
    }

    public function update(UpdateInstEducRequest $request, InstitucionesEduc $institucione): RedirectResponse
    {
        $this->ieService->actualizar($institucione, $request->toDTO());

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución educativa actualizada exitosamente.');
    }

    public function destroy(InstitucionesEduc $institucione): RedirectResponse
    {
        $this->ieService->eliminar($institucione);

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución educativa eliminada exitosamente.');
    }
}
