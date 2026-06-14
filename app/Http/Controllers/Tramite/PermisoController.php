<?php

namespace App\Http\Controllers\Tramite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tramite\StorePermisoRequest;
use App\Http\Requests\Tramite\ValidarPermisoRequest;
use App\Models\Conasis\ConasisDocumentosTram;
use App\Models\Conasis\ConasisExpediente;
use App\Models\InstitucionesEduc;
use App\Models\Trabajador;
use App\Services\Tramite\PermisoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PermisoController extends Controller
{
    public function __construct(
        private PermisoService $permisoService,
    ) {}

    public function porTrabajador(Trabajador $trabajador): JsonResponse
    {
        return response()->json([
            'data' => $this->permisoService->listarPorTrabajador($trabajador),
        ]);
    }

    public function porInstitucion(Request $request, InstitucionesEduc $institucione): JsonResponse
    {
        return response()->json(
            $this->permisoService->listarPorInstitucion($institucione, $request),
        );
    }

    public function altasPorInstitucion(InstitucionesEduc $institucione): JsonResponse
    {
        return response()->json([
            'data' => $this->permisoService->opcionesAltasPorInstitucion($institucione),
        ]);
    }

    public function store(StorePermisoRequest $request): RedirectResponse
    {
        $this->permisoService->crear($request->toDTO(), $request->file('sustento'));

        return redirect()->back()
            ->with('success', 'Solicitud de permiso registrada.');
    }

    public function validar(ValidarPermisoRequest $request, ConasisExpediente $expediente): RedirectResponse
    {
        $this->permisoService->validar($expediente, $request->toDTO());

        return redirect()->back()
            ->with('success', 'Solicitud de permiso procesada.');
    }

    public function anular(ConasisExpediente $expediente): RedirectResponse
    {
        $this->permisoService->anular($expediente);

        return redirect()->back()
            ->with('success', 'Solicitud de permiso anulada.');
    }

    public function descargarSustento(ConasisDocumentosTram $documentoTram): StreamedResponse
    {
        return $this->permisoService->descargarSustento($documentoTram);
    }
}
