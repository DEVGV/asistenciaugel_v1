<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trabajador\StoreRegistroMasivoRequest;
use App\Http\Requests\Trabajador\StoreRegistroTrabajadorRequest;
use App\Services\Trabajador\RegistroTrabajadorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class RegistroTrabajadorController extends Controller
{
    public function __construct(
        private RegistroTrabajadorService $registroService,
    ) {}

    public function create(): RedirectResponse
    {
        return redirect()->route('trabajadores.index');
    }

    public function store(StoreRegistroTrabajadorRequest $request): RedirectResponse
    {
        $this->registroService->registrar($request->validated());

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador registrado exitosamente. Se creó usuario con documento como login y contraseña por defecto.');
    }

    public function storeMasivo(StoreRegistroMasivoRequest $request): JsonResponse
    {
        $resultado = $this->registroService->registrarMasivo($request->filas());

        return response()->json($resultado);
    }
}
