<?php

namespace Database\Seeders;

use App\Models\CursosIE;
use App\Models\GradosIE;
use App\Models\InstitucionesEduc;
use App\Models\SeccionesIE;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitucionCursosGradosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Asegurar que existan IEs
        if (DB::table('t_institucionesEduc')->count() === 0) {
            $this->call(InstitucionesEducSeeder::class);
        }

        // Seleccionar la primera IE
        $ie = InstitucionesEduc::where('codigoInstitucion', 'IE0001')->first() ?? InstitucionesEduc::first();

        if (! $ie) {
            return;
        }

        // 2. Crear 15 Cursos para esta IE
        $cursos = [
            ['nombre' => 'Matemática', 'sigla' => 'MAT'],
            ['nombre' => 'Comunicación', 'sigla' => 'COM'],
            ['nombre' => 'Ciencia y Tecnología', 'sigla' => 'CYT'],
            ['nombre' => 'Ciencias Sociales', 'sigla' => 'CCSS'],
            ['nombre' => 'Desarrollo Personal, Ciudadanía y Cívica', 'sigla' => 'DPCC'],
            ['nombre' => 'Educación para el Trabajo', 'sigla' => 'EPT'],
            ['nombre' => 'Educación Física', 'sigla' => 'EDFI'],
            ['nombre' => 'Arte y Cultura', 'sigla' => 'ARTE'],
            ['nombre' => 'Inglés', 'sigla' => 'ING'],
            ['nombre' => 'Educación Religiosa', 'sigla' => 'REL'],
            ['nombre' => 'Tutoría y Orientación Educativa', 'sigla' => 'TUT'],
            ['nombre' => 'Competencias Digitales', 'sigla' => 'COMP'],
            ['nombre' => 'Psicología', 'sigla' => 'PSIC'],
            ['nombre' => 'Literatura', 'sigla' => 'LIT'],
            ['nombre' => 'Filosofía', 'sigla' => 'FILO'],
        ];

        foreach ($cursos as $cursoData) {
            CursosIE::updateOrCreate(
                [
                    'institucionEduc_id' => $ie->id,
                    'nombre' => $cursoData['nombre'],
                ],
                [
                    'sigla' => $cursoData['sigla'],
                    'created_by' => 1,
                    'activo' => true,
                ]
            );
        }

        // 3. Crear Grados y Secciones para esta IE
        $grados = [
            ['nombre' => 'Primero de Secundaria', 'sigla' => '1° SEC'],
            ['nombre' => 'Segundo de Secundaria', 'sigla' => '2° SEC'],
            ['nombre' => 'Tercero de Secundaria', 'sigla' => '3° SEC'],
            ['nombre' => 'Cuarto de Secundaria', 'sigla' => '4° SEC'],
            ['nombre' => 'Quinto de Secundaria', 'sigla' => '5° SEC'],
        ];

        $seccionesLetras = ['A', 'B', 'C'];

        foreach ($grados as $gradoData) {
            $grado = GradosIE::updateOrCreate(
                [
                    'institucionEduc_id' => $ie->id,
                    'nombre' => $gradoData['nombre'],
                ],
                [
                    'sigla' => $gradoData['sigla'],
                    'created_by' => 1,
                    'activo' => true,
                ]
            );

            // Crear las secciones para este grado
            foreach ($seccionesLetras as $letra) {
                SeccionesIE::updateOrCreate(
                    [
                        'grado_id' => $grado->id,
                        'nombre' => "Sección {$letra}",
                    ],
                    [
                        'sigla' => $letra,
                        'created_by' => 1,
                        'activo' => true,
                    ]
                );
            }
        }
    }
}
