<?php

namespace Database\Seeders;

use App\Models\InstitucionesEduc;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitucionesEducSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener IDs de entidades existentes, o crear una si está vacía para no violar la FK
        $entidadUgelId = DB::table('t_entidades')->value('id');

        if (! $entidadUgelId) {
            $tipoEntidadId = DB::table('param.t00_tipoEntidad')->value('id') ?? 1;
            $entidadUgelId = DB::table('t_entidades')->insertGetId([
                'tipoEntidad_id' => $tipoEntidadId,
                'ruc' => '20123456789',
                'razonSocial' => 'UGEL Principal Autogenerada',
                'razonComercial' => 'UGEL Principal',
                'activo' => true,
                'created_at' => now(),
            ]);
        }

        $entidadAdminId = DB::table('t_entidades')->skip(1)->value('id') ?? $entidadUgelId;

        // 2. Obtener IDs de tablas parametrizadas existentes
        $regimenes = DB::table('param.t34_regimenEduc')->pluck('id')->toArray();
        $tipos = DB::table('param.t34_tipoInstEduc')->pluck('id')->toArray();
        $modalidades = DB::table('param.t34_modalidadesForm')->pluck('id')->toArray();
        $niveles = DB::table('param.t34_nivelesCiclo')->pluck('id')->toArray();

        // Validar que existan los parámetros requeridos
        if (empty($modalidades) || empty($niveles)) {
            return;
        }

        // 3. Crear registros realistas de Instituciones Educativas (IE)
        // Con fechaInicio y sin fechaFin (null)
        $instituciones = [
            [
                'codigoInstitucion' => 'IE0001',
                'codigoModular' => '0234567',
                'nombreLegal' => 'I.E. Mercedes Cabello de Carbonera',
                'entidadUgel_id' => $entidadUgelId,
                'entidadAdmin_id' => $entidadAdminId,
                'regimenEduc_id' => $regimenes[0] ?? null,
                'tipoInstEduc_id' => $tipos[0] ?? null,
                'modalidadFormativa_id' => $modalidades[0],
                'nivelCiclo_id' => $niveles[2] ?? $niveles[0], // SECUNDARIA o primer nivel
                'fechaInicio' => '2010-03-01',
                'fechaFin' => null,
                'created_by' => 1,
            ],
            [
                'codigoInstitucion' => 'IE0002',
                'codigoModular' => '0543210',
                'nombreLegal' => 'I.E. Melitón Carvajal',
                'entidadUgel_id' => $entidadUgelId,
                'entidadAdmin_id' => $entidadAdminId,
                'regimenEduc_id' => $regimenes[0] ?? null,
                'tipoInstEduc_id' => $tipos[1] ?? $tipos[0] ?? null,
                'modalidadFormativa_id' => $modalidades[0],
                'nivelCiclo_id' => $niveles[1] ?? $niveles[0], // PRIMARIA o primer nivel
                'fechaInicio' => '2015-05-15',
                'fechaFin' => null,
                'created_by' => 1,
            ],
            [
                'codigoInstitucion' => 'IE0003',
                'codigoModular' => '0987654',
                'nombreLegal' => 'I.E. Alfonso Ugarte',
                'entidadUgel_id' => $entidadUgelId,
                'entidadAdmin_id' => $entidadAdminId,
                'regimenEduc_id' => $regimenes[1] ?? $regimenes[0] ?? null,
                'tipoInstEduc_id' => $tipos[0] ?? null,
                'modalidadFormativa_id' => $modalidades[0],
                'nivelCiclo_id' => $niveles[2] ?? $niveles[0], // SECUNDARIA o primer nivel
                'fechaInicio' => '2018-08-20',
                'fechaFin' => null,
                'created_by' => 1,
            ],
        ];

        foreach ($instituciones as $ieData) {
            InstitucionesEduc::updateOrCreate(
                ['codigoModular' => $ieData['codigoModular']],
                $ieData
            );
        }
    }
}
