<?php

namespace Database\Seeders;

use App\Models\CondicionesLaborales;
use App\Models\Param\ParamRegimenLaboral;
use App\Models\Param\ParamTipoTrabajador;
use Illuminate\Database\Seeder;

class CondicionesLaboralesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener Regímenes Laborales existentes (param.t33_regimenLaboral)
        $reg276Id = ParamRegimenLaboral::where('codigo', '02')->orWhere('nombre', 'like', '%276%')->value('id');
        $regCASId = ParamRegimenLaboral::where('codigo', '15')->orWhere('nombre', 'like', '%1057%')->value('id');
        $regReformaId = ParamRegimenLaboral::where('codigo', '23')->orWhere('nombre', 'like', '%29944%')->value('id');

        // 2. Obtener Tipos de Trabajador existentes (param.t8_tipoTrabajador)
        $tipoDocenteId = ParamTipoTrabajador::where('codigo', '96')->orWhere('nombre', 'like', '%MAGISTERIO%')->value('id');
        $tipoAdministrativoId = ParamTipoTrabajador::where('codigo', '21')->orWhere('nombre', 'like', '%EMPLEADO%')->value('id');
        $tipoAuxiliarId = ParamTipoTrabajador::where('codigo', '21')->orWhere('nombre', 'like', '%EMPLEADO%')->value('id');
        $tipoDirectivoId = ParamTipoTrabajador::where('codigo', '84')->orWhere('nombre', 'like', '%DIRECTIVO SUPERIOR%')->value('id');

        // 3. Crear 7 condiciones laborales utilizando los IDs anteriores
        $condiciones = [
            [
                'regimenLaboral_id' => $reg276Id,
                'tipoTrabajador_id' => $tipoAdministrativoId,
                'nombre' => 'Administrativo Nombrado D.L. 276',
                'abreviatura' => 'ADM_NOM_276',
                'descripcion' => 'Personal administrativo nombrado bajo la carrera administrativa estatal (D.L. 276).',
                'created_by' => 1,
            ],
            [
                'regimenLaboral_id' => $reg276Id,
                'tipoTrabajador_id' => $tipoAdministrativoId,
                'nombre' => 'Administrativo Contratado D.L. 276',
                'abreviatura' => 'ADM_CON_276',
                'descripcion' => 'Personal administrativo contratado para labores temporales (D.L. 276).',
                'created_by' => 1,
            ],
            [
                'regimenLaboral_id' => $regCASId,
                'tipoTrabajador_id' => $tipoAdministrativoId,
                'nombre' => 'Contratado CAS Administrativo',
                'abreviatura' => 'CAS_ADM',
                'descripcion' => 'Personal administrativo contratado bajo la modalidad de Contrato Administrativo de Servicios (D.L. 1057).',
                'created_by' => 1,
            ],
            [
                'regimenLaboral_id' => $regCASId,
                'tipoTrabajador_id' => $tipoDirectivoId,
                'nombre' => 'Contratado CAS de Confianza / Directivo',
                'abreviatura' => 'CAS_DIR_CONF',
                'descripcion' => 'Personal directivo o de confianza contratado bajo modalidad CAS.',
                'created_by' => 1,
            ],
            [
                'regimenLaboral_id' => $regReformaId,
                'tipoTrabajador_id' => $tipoDocenteId,
                'nombre' => 'Docente Nombrado Magisterial',
                'abreviatura' => 'DOC_NOM_LRM',
                'descripcion' => 'Docente nombrado en la carrera pública magisterial (Ley 29944).',
                'created_by' => 1,
            ],
            [
                'regimenLaboral_id' => $regReformaId,
                'tipoTrabajador_id' => $tipoDocenteId,
                'nombre' => 'Docente Contratado Magisterial',
                'abreviatura' => 'DOC_CON_LRM',
                'descripcion' => 'Docente contratado temporalmente para cubrir plaza vacante (Ley 29944).',
                'created_by' => 1,
            ],
            [
                'regimenLaboral_id' => $regReformaId,
                'tipoTrabajador_id' => $tipoAuxiliarId,
                'nombre' => 'Auxiliar de Educación Nombrado',
                'abreviatura' => 'AUX_NOM_LRM',
                'descripcion' => 'Auxiliar de educación nombrado bajo la Ley de Reforma Magisterial.',
                'created_by' => 1,
            ],
        ];

        foreach ($condiciones as $condData) {
            CondicionesLaborales::updateOrCreate(
                ['abreviatura' => $condData['abreviatura']],
                $condData
            );
        }
    }
}
