<?php

namespace App\Services\InstitucionEducativa;

use App\Models\Areas;
use App\Models\Cargos;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\CondicionesLaborales;
use App\Models\InstitucionesEduc;
use App\Models\Param\ParamRolTrabajador;
use App\Models\Param\ParamSituacionLaboral;
use App\Models\Param\ParamTipoContrato;
use App\Models\Trabajador;
use App\Services\Trabajador\AltaTrabajadorService;

class AltaMasivaIEService
{
    public function __construct(
        private AltaTrabajadorService $altaService,
    ) {}

    /**
     * Procesa la carga masiva de altas para una IE.
     * Valida existencia de FKs en batch (sin N+1) y delega la inserción al AltaTrabajadorService.
     *
     * @param  array<int, array<string, mixed>>  $filas  Ya validadas por StoreAltaMasivaIERequest
     * @return array{
     *     insertados: int,
     *     errores_validacion: array<int, string>,
     *     errores_db: array<int, string>
     * }
     */
    public function procesarMasivo(InstitucionesEduc $ie, array $filas): array
    {
        $createdBy = auth()->id() ?? 1;
        $ahora     = now()->toDateString();

        // Pre-cargar IDs válidos por tipo — evita N+1 queries
        $validTrabajadores  = $this->idsValidos(Trabajador::class,          $filas, 'trabajador_id');
        $validCondiciones   = $this->idsValidos(CondicionesLaborales::class, $filas, 'condicionLaboral_id');
        $validTipoContratos = $this->idsValidos(ParamTipoContrato::class,    $filas, 'tipoContrato_id');
        $validRoles         = $this->idsValidos(ParamRolTrabajador::class,   $filas, 'rolTrabajador_id');
        $validSitLab        = $this->idsValidos(ParamSituacionLaboral::class, $filas, 'situacionLaboral_id');
        $validAreas         = $this->idsValidos(Areas::class,                $filas, 'area_id');
        $validCargos        = $this->idsValidos(Cargos::class,               $filas, 'cargo_id');

        // Locales válidos: solo los que pertenecen a esta IE.
        $validLocales = ConasisLocalesInstEduc::where('institucionEduc_id', $ie->id)
            ->pluck('id')
            ->flip();

        $validas = [];
        $errores = [];

        foreach ($filas as $idx => $fila) {
            $rowNum    = $idx + 1;
            $fila      = array_map(fn ($v) => $v === '' ? null : $v, $fila);
            $rowErrors = [];

            if (! isset($validTrabajadores[$fila['trabajador_id']])) {
                $rowErrors[] = "trabajador_id {$fila['trabajador_id']} no existe";
            }
            if (! isset($validCondiciones[$fila['condicionLaboral_id']])) {
                $rowErrors[] = "condicionLaboral_id {$fila['condicionLaboral_id']} no existe";
            }
            if (! isset($validTipoContratos[$fila['tipoContrato_id']])) {
                $rowErrors[] = "tipoContrato_id {$fila['tipoContrato_id']} no existe";
            }
            if (! isset($validRoles[$fila['rolTrabajador_id']])) {
                $rowErrors[] = "rolTrabajador_id {$fila['rolTrabajador_id']} no existe";
            }
            if (! isset($validSitLab[$fila['situacionLaboral_id']])) {
                $rowErrors[] = "situacionLaboral_id {$fila['situacionLaboral_id']} no existe";
            }
            if (! isset($validAreas[$fila['area_id']])) {
                $rowErrors[] = "area_id {$fila['area_id']} no existe";
            }
            if (! isset($validCargos[$fila['cargo_id']])) {
                $rowErrors[] = "cargo_id {$fila['cargo_id']} no existe";
            }
            if (! empty($fila['localInstEduc_id']) && ! isset($validLocales[$fila['localInstEduc_id']])) {
                $rowErrors[] = "localInstEduc_id {$fila['localInstEduc_id']} no pertenece a esta IE";
            }

            if ($rowErrors) {
                $errores[$rowNum] = implode('; ', $rowErrors);
            } else {
                $fila['institucionEducativa_id'] = $ie->id;
                $fila['created_by']              = $createdBy;
                $fila['fechaAlta']               = $fila['fechaAlta'] ?? $ahora;
                $validas[]                       = $fila;
            }
        }

        if (empty($validas)) {
            return [
                'insertados'         => 0,
                'errores_validacion' => $errores,
                'errores_db'         => [],
            ];
        }

        $resultado = $this->altaService->insertarMasivo($ie, $validas, $createdBy);

        return [
            'insertados'         => $resultado['insertados'],
            'errores_validacion' => $errores,
            'errores_db'         => $resultado['errores'],
        ];
    }

    /**
     * Carga todos los IDs válidos de un modelo para un campo dado, en una sola query.
     *
     * @param  class-string  $model
     * @param  array<int, array<string, mixed>>  $filas
     * @return \Illuminate\Support\Collection<int, int>
     */
    private function idsValidos(string $model, array $filas, string $campo): \Illuminate\Support\Collection
    {
        $ids = array_unique(array_column($filas, $campo));

        return $model::whereIn('id', $ids)->pluck('id')->flip();
    }
}
