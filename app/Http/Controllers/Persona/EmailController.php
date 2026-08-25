<?php

namespace App\Http\Controllers\Persona;

use App\Http\Controllers\Controller;
use App\Http\Requests\Persona\StoreEmailRequest;
use App\Models\Emails;
use App\Models\Personas;
use App\Services\Persona\EmailService;
use Illuminate\Http\RedirectResponse;

class EmailController extends Controller
{
    public function __construct(
        private EmailService $emailService,
    ) {}

    private function redirectToTrabajador(Personas $persona, string $message): RedirectResponse
    {
        $trabajador = $persona->trabajador;

        return $trabajador
            ? redirect()->route('trabajadores.show', $trabajador)->with('success', $message)
            : redirect()->route('trabajadores.index')->with('success', $message);
    }

    public function store(StoreEmailRequest $request, Personas $persona): RedirectResponse
    {
        $this->emailService->crear($persona, $request->toDTO());

        return $this->redirectToTrabajador($persona, 'Email agregado exitosamente.');
    }

    public function update(StoreEmailRequest $request, Emails $email): RedirectResponse
    {
        $this->emailService->actualizar($email, $request->toDTO());

        return $this->redirectToTrabajador($email->persona, 'Email actualizado exitosamente.');
    }

    public function darDeBaja(Emails $email): RedirectResponse
    {
        $this->emailService->darDeBaja($email);

        return $this->redirectToTrabajador($email->persona, 'Email dado de baja exitosamente.');
    }

    public function destroy(Emails $email): RedirectResponse
    {
        $persona = $email->persona;
        $this->emailService->eliminar($email);

        return $this->redirectToTrabajador($persona, 'Email eliminado exitosamente.');
    }
}
