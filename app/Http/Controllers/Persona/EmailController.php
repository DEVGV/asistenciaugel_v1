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

    public function store(StoreEmailRequest $request, Personas $persona): RedirectResponse
    {
        $this->emailService->crear($persona, $request->validated());

        return redirect()->route('personas.show', $persona)
            ->with('success', 'Email agregado exitosamente.');
    }

    public function update(StoreEmailRequest $request, Emails $email): RedirectResponse
    {
        $this->emailService->actualizar($email, $request->validated());

        return redirect()->route('personas.show', $email->persona_id)
            ->with('success', 'Email actualizado exitosamente.');
    }

    public function destroy(Emails $email): RedirectResponse
    {
        $personaId = $email->persona_id;
        $this->emailService->eliminar($email);

        return redirect()->route('personas.show', $personaId)
            ->with('success', 'Email eliminado exitosamente.');
    }
}
