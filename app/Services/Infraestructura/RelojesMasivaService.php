<?php

namespace App\Services\Infraestructura;

use App\DTOs\Infraestructura\CreateRelojDTO;
use App\Models\Conasis\ConasisLocalesInstEduc;
use Illuminate\Support\Facades\DB;

class RelojesMasivaService
{
    public function __construct(
        private RelojService $relojService,
    ) {}

    /**
     * Crea relojes de forma masiva para un local de IE.
     *
     * @param  array<int, array<string, mixed>>  $filas
     * @return array{ creados: int, errores: array<int, string> }
     */
    public function procesarMasivo(ConasisLocalesInstEduc $localIe, array $filas): array
    {
        $creados = 0;
        $errores = [];

        DB::transaction(function () use ($localIe, $filas, &$creados, &$errores) {
            foreach ($filas as $idx => $fila) {
                $rowNum = $idx + 1;
                $nombre = trim($fila['nombre'] ?? '');
                $ip     = trim($fila['dreccionIP'] ?? '');

                if ($nombre === '' && $ip === '') {
                    $errores[$rowNum] = 'Debe indicar al menos nombre o dirección IP.';
                    continue;
                }

                try {
                    $dto = CreateRelojDTO::from(array_merge($fila, [
                        'localInstEduc_id' => $localIe->id,
                        'activo'           => true,
                    ]));
                    $this->relojService->crear($dto);
                    $creados++;
                } catch (\Throwable $e) {
                    $etiqueta         = $nombre ?: $ip;
                    $errores[$rowNum] = "\"{$etiqueta}\": " . $e->getMessage();
                }
            }
        });

        return [
            'creados' => $creados,
            'errores' => $errores,
        ];
    }
}
