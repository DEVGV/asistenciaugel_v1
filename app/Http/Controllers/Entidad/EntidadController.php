<?php

namespace App\Http\Controllers\Entidad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Entidad\StoreEntidadRequest;
use App\Http\Requests\Entidad\UpdateEntidadRequest;
use App\Models\Entidades;
use App\Services\Entidad\EntidadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EntidadController extends Controller
{
    public function __construct(
        private EntidadService $entidadService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('entidad/Index', [
            'entidades' => $this->entidadService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(StoreEntidadRequest $request): RedirectResponse
    {
        $this->entidadService->crear($request->toDTO());

        return redirect()->route('entidades.index')
            ->with('success', 'Entidad registrada exitosamente.');
    }

    public function update(UpdateEntidadRequest $request, Entidades $entidade): RedirectResponse
    {
        $this->entidadService->actualizar($entidade, $request->toDTO());

        return redirect()->route('entidades.index')
            ->with('success', 'Entidad actualizada exitosamente.');
    }

    public function destroy(Entidades $entidade): RedirectResponse
    {
        $this->entidadService->eliminar($entidade);

        return redirect()->route('entidades.index')
            ->with('success', 'Entidad eliminada exitosamente.');
    }

    public function search(Request $request): JsonResponse
    {
        $tipoId = $request->integer('tipo_entidad_id');
        $selectedId = $request->integer('selected_id');
        $search = $request->string('q')->trim();

        $query = Entidades::query()->select('id', 'razonSocial as nombre', 'ruc as codigo');

        if ($tipoId) {
            $query->where('tipoEntidad_id', $tipoId);
        }

        $query->where(function ($q) use ($selectedId) {
            $q->where('activo', true);
            if ($selectedId) {
                $q->orWhere('id', $selectedId);
            }
        });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('razonSocial', 'ilike', "%{$search}%")
                    ->orWhere('ruc', 'like', "%{$search}%");
            });
        } elseif ($selectedId) {
            // Sort to ensure the selected item is at the top when no search query is active
            $query->orderByRaw('CASE WHEN id = ? THEN 1 ELSE 0 END DESC', [$selectedId]);
        }

        return response()->json([
            'data' => $query->limit(50)->get(),
        ]);
    }
}
