<?php

namespace App\Http\Controllers\Infraestructura;

use App\Http\Controllers\Controller;
use App\Http\Requests\Infraestructura\StoreLocalRequest;
use App\Http\Requests\Infraestructura\UpdateLocalRequest;
use App\Models\Conasis\ConasisLocales;
use App\Services\Infraestructura\LocalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LocalController extends Controller
{
    public function __construct(
        private LocalService $localService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('infraestructura/locales/Index', [
            'locales' => $this->localService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(StoreLocalRequest $request): RedirectResponse
    {
        $this->localService->crear($request->toDTO());

        return back()->with('success', 'Local registrado exitosamente.');
    }

    public function update(UpdateLocalRequest $request, ConasisLocales $local): RedirectResponse
    {
        $this->localService->actualizar($local, $request->toDTO());

        return back()->with('success', 'Local actualizado exitosamente.');
    }

    public function destroy(ConasisLocales $local): RedirectResponse
    {
        $this->localService->eliminar($local);

        return back()->with('success', 'Local desactivado exitosamente.');
    }
}
