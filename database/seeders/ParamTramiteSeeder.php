<?php

namespace Database\Seeders;

use App\Models\Param\ParamDocumentos;
use App\Models\Param\ParamEstadosTram;
use Illuminate\Database\Seeder;

class ParamTramiteSeeder extends Seeder
{
    /**
     * Estados de trámite y tipos de documento de sustento.
     * Idempotente: actualiza por código sin duplicar.
     */
    public function run(): void
    {
        $estados = [
            ['codigo' => 'PEN', 'nombre' => 'Pendiente', 'abreviatura' => 'PEND'],
            ['codigo' => 'VAL', 'nombre' => 'Validado', 'abreviatura' => 'VALI'],
            ['codigo' => 'REC', 'nombre' => 'Rechazado', 'abreviatura' => 'RECH'],
            ['codigo' => 'ANU', 'nombre' => 'Anulado', 'abreviatura' => 'ANUL'],
        ];

        foreach ($estados as $estado) {
            ParamEstadosTram::updateOrCreate(
                ['codigo' => $estado['codigo']],
                $estado + ['activo' => true],
            );
        }

        $documentos = [
            ['codigo' => 'FUT', 'nombre' => 'Formato Único de Trámite (FUT)', 'abreviatura' => 'FUT'],
            ['codigo' => 'PAP', 'nombre' => 'Papeleta de Permiso', 'abreviatura' => 'PAPELETA'],
            ['codigo' => 'CMED', 'nombre' => 'Certificado Médico', 'abreviatura' => 'CERT.MED'],
            ['codigo' => 'CITT', 'nombre' => 'CITT - EsSalud', 'abreviatura' => 'CITT'],
            ['codigo' => 'RD', 'nombre' => 'Resolución Directoral', 'abreviatura' => 'RES.DIR'],
            ['codigo' => 'OFI', 'nombre' => 'Oficio', 'abreviatura' => 'OFICIO'],
            ['codigo' => 'MEM', 'nombre' => 'Memorándum', 'abreviatura' => 'MEMO'],
            ['codigo' => 'INF', 'nombre' => 'Informe', 'abreviatura' => 'INFORME'],
            ['codigo' => 'CONS', 'nombre' => 'Constancia', 'abreviatura' => 'CONST'],
            ['codigo' => 'OTRO', 'nombre' => 'Otro Documento', 'abreviatura' => 'OTRO'],
        ];

        foreach ($documentos as $documento) {
            ParamDocumentos::updateOrCreate(
                ['codigo' => $documento['codigo']],
                $documento + ['activo' => true],
            );
        }
    }
}
