<?php

namespace App\Http\Controllers;

use App\Services\Auth\ContextoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private ContextoService $contextoService,
    ) {}

    public function __invoke(Request $request): Response|RedirectResponse
    {
        // Docente → redirigir a su propia ficha de trabajador
        if ($request->user()->esDocente($this->contextoService->ieId())) {
            $trabajadorId = $request->user()->trabajador_id;

            if ($trabajadorId) {
                return redirect()->route('trabajadores.show', $trabajadorId);
            }
        }

        return Inertia::render('Dashboard');
    }
}
