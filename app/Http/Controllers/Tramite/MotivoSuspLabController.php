<?php

namespace App\Http\Controllers\Tramite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tramite\UpdateMotivoSuspLabRequest;
use App\Models\Param\ParamMotivosSuspLab;
use App\Services\Tramite\MotivoSuspLabService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MotivoSuspLabController extends Controller
{
    public function __construct(
        private MotivoSuspLabService $motivoSuspLabService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('tramite/motivos-susp-lab/Index', [
            'motivos' => $this->motivoSuspLabService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function update(UpdateMotivoSuspLabRequest $request, ParamMotivosSuspLab $motivoSuspLab): RedirectResponse
    {
        $this->motivoSuspLabService->actualizarAutorizadoPor(
            $motivoSuspLab,
            \App\Enums\AutorizadoPor::from($request->validated('autorizadoPor')),
        );

        return redirect()->route('motivos-susp-lab.index')
            ->with('success', 'Competencia de resolución actualizada.');
    }
}
