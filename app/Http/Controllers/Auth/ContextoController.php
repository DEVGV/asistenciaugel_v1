<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SeleccionarContextoRequest;
use App\Services\Auth\ContextoService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContextoController extends Controller
{
    public function __construct(
        private ContextoService $contextoService,
    ) {}

    /** Pantalla de selección de contexto (UGEL → IE). */
    public function create(): Response
    {
        return Inertia::render('auth/SeleccionarContexto', [
            'opciones' => $this->contextoService->opcionesParaUsuario(auth()->user()),
            'actual' => [
                'ugel_id' => $this->contextoService->ugelId(),
                'ie_id' => $this->contextoService->ieId(),
            ],
        ]);
    }

    /** Establece (o cambia) el contexto de trabajo. */
    public function store(SeleccionarContextoRequest $request): RedirectResponse
    {
        $this->contextoService->establecer($request->user(), $request->toDTO());

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Contexto de trabajo actualizado.');
    }
}
