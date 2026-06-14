<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracion\AsignarPerfilIeRequest;
use App\Http\Requests\Configuracion\CambiarPasswordRequest;
use App\Models\Auth\UsuarioPerfilIe;
use App\Models\User;
use App\Services\Configuracion\PerfilService;
use App\Services\Configuracion\UsuarioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsuarioController extends Controller
{
    public function __construct(
        private UsuarioService $usuarioService,
        private PerfilService $perfilService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('configuracion/usuarios/Index', [
            'usuarios'   => $this->usuarioService->listarPaginado($request),
            'filters'    => $request->only(['search']),
        ]);
    }

    public function show(User $usuario): Response
    {
        return Inertia::render('configuracion/usuarios/Show', [
            'usuario'    => $usuario->load('trabajador.persona'),
            'perfilesIe' => $this->usuarioService->perfilesDelUsuario($usuario),
            'perfiles'   => $this->perfilService->listarTodos(),
            'instituciones' => $this->usuarioService->institucionesParaAsignacion(),
            'ugeles'     => $this->usuarioService->ugelesDisponibles(),
        ]);
    }

    public function cambiarPassword(CambiarPasswordRequest $request, User $usuario): RedirectResponse
    {
        $this->usuarioService->cambiarPassword($usuario, $request->input('password'));

        return back()->with('success', 'Contraseña actualizada exitosamente.');
    }

    public function toggleActivo(User $usuario): RedirectResponse
    {
        $this->usuarioService->toggleActivo($usuario);

        $estado = $usuario->fresh()->activo ? 'activado' : 'desactivado';

        return back()->with('success', "Usuario {$estado} exitosamente.");
    }

    public function asignarPerfil(AsignarPerfilIeRequest $request, User $usuario): RedirectResponse
    {
        $this->usuarioService->asignarPerfil(
            $usuario,
            $request->integer('perfil_id'),
            $request->input('institucionEducativa_id') ? $request->integer('institucionEducativa_id') : null,
            $request->input('entidadUgel_id') ? $request->integer('entidadUgel_id') : null,
        );

        return back()->with('success', 'Perfil asignado exitosamente.');
    }

    public function revocarPerfil(User $usuario, UsuarioPerfilIe $perfilIe): RedirectResponse
    {
        $this->usuarioService->revocarPerfil($perfilIe);

        return back()->with('success', 'Perfil revocado exitosamente.');
    }
}
