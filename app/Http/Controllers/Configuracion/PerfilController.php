<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracion\StorePerfilRequest;
use App\Http\Requests\Configuracion\SyncPermisosRequest;
use App\Http\Requests\Configuracion\UpdatePerfilRequest;
use App\Models\Auth\Perfil;
use App\Services\Configuracion\PerfilService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PerfilController extends Controller
{
    public function __construct(
        private PerfilService $perfilService,
    ) {}

    public function index(Request $request): Response
    {
        $perfiles = $this->perfilService->listarPaginado($request);
        // Cargar permisos en cada perfil para el panel de edición inline
        $perfiles->getCollection()->load('permisos');

        return Inertia::render('configuracion/perfiles/Index', [
            'perfiles'          => $perfiles,
            'permisosPorModulo' => $this->perfilService->permisosPorModulo(),
            'filters'           => $request->only(['search']),
        ]);
    }

    public function store(StorePerfilRequest $request): RedirectResponse
    {
        $this->perfilService->crear(
            $request->input('nombre'),
            $request->input('descripcion'),
        );

        return redirect()->route('perfiles.index')
            ->with('success', 'Perfil creado exitosamente.');
    }

    public function update(UpdatePerfilRequest $request, Perfil $perfil): RedirectResponse
    {
        $this->perfilService->actualizar(
            $perfil,
            $request->input('nombre'),
            $request->input('descripcion'),
            $request->boolean('activo'),
        );

        return redirect()->route('perfiles.index')
            ->with('success', 'Perfil actualizado exitosamente.');
    }

    public function destroy(Perfil $perfil): RedirectResponse
    {
        $this->perfilService->eliminar($perfil);

        return redirect()->route('perfiles.index')
            ->with('success', 'Perfil desactivado exitosamente.');
    }

    public function syncPermisos(SyncPermisosRequest $request, Perfil $perfil): RedirectResponse
    {
        $this->perfilService->sincronizarPermisos($perfil, $request->input('permiso_ids', []));

        return back()->with('success', 'Permisos actualizados exitosamente.');
    }

}
