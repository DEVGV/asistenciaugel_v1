<?php

namespace App\Services\Tramite;

use App\Enums\AutorizadoPor;
use App\Models\Param\ParamMotivosSuspLab;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MotivoSuspLabService
{
    /**
     * @return LengthAwarePaginator<ParamMotivosSuspLab>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return ParamMotivosSuspLab::query()
            ->with('tipoSuspensionLaboral')
            ->where('codigoProg', 'SUSP')
            ->when($request->search, function ($query, $search) {
                $query->where('descripcion', 'ilike', "%{$search}%")
                    ->orWhere('codigo', 'ilike', "%{$search}%");
            })
            ->orderBy('descripcion')
            ->paginate(20);
    }

    public function actualizarAutorizadoPor(ParamMotivosSuspLab $motivo, AutorizadoPor $autorizadoPor): bool
    {
        return $motivo->update(['autorizadoPor' => $autorizadoPor->value]);
    }
}
