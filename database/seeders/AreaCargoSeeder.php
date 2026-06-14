<?php

namespace Database\Seeders;

use App\Models\Areas;
use App\Models\Cargos;
use App\Models\Param\ParamRolTrabajador;
use Illuminate\Database\Seeder;

class AreaCargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener roles de trabajador existentes en param.t00_rolTrabajador
        $rolDirectivoId = ParamRolTrabajador::where('codigo', '3')->orWhere('nombre', 'DIRECTOR')->value('id');
        $rolAdministrativoId = ParamRolTrabajador::where('codigo', '1')->orWhere('nombre', 'ADMINISTRATIVO')->value('id');
        $rolDocenteId = ParamRolTrabajador::where('codigo', '2')->orWhere('nombre', 'DOCENTE')->value('id');

        // 2. Crear 3 áreas realistas de UGEL
        $areas = [
            [
                'nombre' => 'Área de Gestión Administrativa',
                'sigla' => 'AGA',
                'descripcion' => 'Responsable de la administración de los recursos humanos, financieros y materiales de la UGEL.',
                'rolTrabajador_id' => $rolAdministrativoId,
                'activo' => true,
            ],
            [
                'nombre' => 'Área de Gestión Institucional',
                'sigla' => 'AGI',
                'descripcion' => 'Planificación, presupuesto y modernización de la gestión educativa institucional.',
                'rolTrabajador_id' => $rolDirectivoId,
                'activo' => true,
            ],
            [
                'nombre' => 'Área de Gestión de la Educación Básica Regular',
                'sigla' => 'AGEBRE',
                'descripcion' => 'Supervisión pedagógica, acompañamiento docente y mejora del aprendizaje.',
                'rolTrabajador_id' => $rolDocenteId,
                'activo' => true,
            ],
        ];

        foreach ($areas as $areaData) {
            Areas::updateOrCreate(
                ['sigla' => $areaData['sigla']],
                $areaData
            );
        }

        // 3. Crear 3 cargos realistas de UGEL
        $cargos = [
            [
                'nombre' => 'Director del Programa Sectorial III / Jefe de UGEL',
                'abreviatura' => 'DIR_UGEL',
                'rolTrabajador_id' => $rolDirectivoId,
                'created_by' => 1,
                'activo' => true,
            ],
            [
                'nombre' => 'Especialista en Educación',
                'abreviatura' => 'ESP_EDUC',
                'rolTrabajador_id' => $rolDocenteId,
                'created_by' => 1,
                'activo' => true,
            ],
            [
                'nombre' => 'Especialista Administrativo - Recursos Humanos',
                'abreviatura' => 'ESP_ADM_RRHH',
                'rolTrabajador_id' => $rolAdministrativoId,
                'created_by' => 1,
                'activo' => true,
            ],
        ];

        foreach ($cargos as $cargoData) {
            Cargos::updateOrCreate(
                ['abreviatura' => $cargoData['abreviatura']],
                $cargoData
            );
        }
    }
}
