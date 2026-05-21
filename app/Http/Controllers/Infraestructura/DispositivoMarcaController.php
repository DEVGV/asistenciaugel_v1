<?php

namespace App\Http\Controllers\Infraestructura;

use App\Http\Controllers\Controller;
use App\Http\Requests\Infraestructura\StoreDispositivoMarcaRequest;
use App\Models\Conasis\ConasisDispositivosMarca;
use App\Services\Infraestructura\DispositivoMarcaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DispositivoMarcaController extends Controller
{
    public function __construct(
        private DispositivoMarcaService $dispositivoService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('infraestructura/dispositivos/Index', [
            'dispositivos' => $this->dispositivoService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(StoreDispositivoMarcaRequest $request): RedirectResponse
    {
        $this->dispositivoService->crear($request->toDTO());

        return redirect()->route('dispositivos-marca.index')
            ->with('success', 'Dispositivo de marca registrado exitosamente.');
    }

    public function destroy(ConasisDispositivosMarca $dispositivosMarca): RedirectResponse
    {
        $this->dispositivoService->eliminar($dispositivosMarca);

        return redirect()->route('dispositivos-marca.index')
            ->with('success', 'Dispositivo de marca eliminado exitosamente.');
    }
}
