<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreEmailIERequest;
use App\Models\Emails;
use App\Models\InstitucionesEduc;
use App\Services\InstitucionEducativa\EmailIEService;
use Illuminate\Http\RedirectResponse;

class EmailIEController extends Controller
{
    public function __construct(
        private EmailIEService $emailService,
    ) {}

    public function store(StoreEmailIERequest $request, InstitucionesEduc $institucione): RedirectResponse
    {
        $this->emailService->crear($institucione, $request->toDTO());

        return redirect()->route('instituciones.show', $institucione)
            ->with('success', 'Email agregado exitosamente.');
    }

    public function update(StoreEmailIERequest $request, Emails $email): RedirectResponse
    {
        $this->emailService->actualizar($email, $request->toDTO());

        return redirect()->route('instituciones.show', $email->institucionEduc_id)
            ->with('success', 'Email actualizado exitosamente.');
    }

    public function darDeBaja(Emails $email): RedirectResponse
    {
        $this->emailService->darDeBaja($email);

        return redirect()->route('instituciones.show', $email->institucionEduc_id)
            ->with('success', 'Email dado de baja exitosamente.');
    }

    public function destroy(Emails $email): RedirectResponse
    {
        $ieId = $email->institucionEduc_id;
        $this->emailService->eliminar($email);

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Email eliminado exitosamente.');
    }
}
