<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trabajador\StoreTrabajadorRequest;
use App\Http\Requests\Trabajador\UpdatePersonaTrabajadorRequest;
use App\Models\Trabajador;
use App\Services\Auth\ContextoService;
use App\Services\Persona\PersonaService;
use App\Services\Trabajador\TrabajadorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TrabajadorController extends Controller
{
    public function __construct(
        private TrabajadorService $trabajadorService,
        private PersonaService $personaService,
        private ContextoService $contextoService,
    ) {}

    public function index(Request $request): Response|RedirectResponse
    {
        // Docente no accede al listado; va directo a su propia ficha.
        if ($request->user()->esDocente($this->contextoService->ieId())) {
            $trabajadorId = $request->user()->trabajador_id;

            if ($trabajadorId) {
                return redirect()->route('trabajadores.show', $trabajadorId);
            }

            abort(403, 'No tiene un trabajador asociado.');
        }

        return Inertia::render('trabajador/Index', [
            'trabajadores' => $this->trabajadorService->listarPaginado($request),
            'filters' => $request->only(['search']),
            'perfiles' => $this->trabajadorService->listarPerfilesActivos(),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json(
            $this->trabajadorService->buscarParaAsignacion($request)
        );
    }

    public function store(StoreTrabajadorRequest $request): RedirectResponse
    {
        $this->trabajadorService->crearMasivo($request->toDTOs());

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajadores registrados exitosamente.');
    }

    public function show(Request $request, Trabajador $trabajador): Response
    {
        $this->autorizarAccesoDocente($request, $trabajador);

        return Inertia::render('trabajador/Show', [
            'trabajador' => $this->trabajadorService->obtenerConRelaciones($trabajador),
        ]);
    }

    public function showTab(Request $request, Trabajador $trabajador, string $tab): Response
    {
        $this->autorizarAccesoDocente($request, $trabajador);

        // 'horario' en URL → 'horarios' como clave de tab en el frontend
        $tabMap = ['horario' => 'horarios'];

        return Inertia::render('trabajador/Show', [
            'trabajador' => $this->trabajadorService->obtenerConRelaciones($trabajador),
            'activeTab'  => $tabMap[$tab] ?? $tab,
        ]);
    }

    /**
     * Si el perfil activo es Docente, solo puede ver su propio trabajador.
     */
    private function autorizarAccesoDocente(Request $request, Trabajador $trabajador): void
    {
        $user = $request->user();

        if ($user->esDocente($this->contextoService->ieId()) && $user->trabajador_id !== $trabajador->id) {
            throw new AccessDeniedHttpException('No tiene permiso para ver este trabajador.');
        }
    }

    public function update(UpdatePersonaTrabajadorRequest $request, Trabajador $trabajador): RedirectResponse
    {
        $this->personaService->actualizar($trabajador->persona, $request->toDTO());

        return redirect()->route('trabajadores.show', $trabajador)
            ->with('success', 'Datos personales actualizados exitosamente.');
    }

    public function destroy(Trabajador $trabajador): RedirectResponse
    {
        $this->trabajadorService->eliminar($trabajador);

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador desactivado exitosamente.');
    }
}
