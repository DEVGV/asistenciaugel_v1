<?php

use App\Models\CursosIE;
use App\Models\GradosIE;
use App\Models\SeccionesIE;
use Database\Seeders\InstitucionCursosGradosSeeder;
use Database\Seeders\InstitucionesEducSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('it seeds instituciones educativas successfully', function () {
    // Run the seeder
    $this->seed(InstitucionesEducSeeder::class);

    // Assert that the seeded data exists in the database
    $this->assertDatabaseHas('t_institucionesEduc', [
        'codigoModular' => '0234567',
        'nombreLegal' => 'I.E. Mercedes Cabello de Carbonera',
        'fechaInicio' => '2010-03-01',
        'fechaFin' => null,
    ]);

    $this->assertDatabaseHas('t_institucionesEduc', [
        'codigoModular' => '0543210',
        'nombreLegal' => 'I.E. Melitón Carvajal',
        'fechaInicio' => '2015-05-15',
        'fechaFin' => null,
    ]);

    $this->assertDatabaseHas('t_institucionesEduc', [
        'codigoModular' => '0987654',
        'nombreLegal' => 'I.E. Alfonso Ugarte',
        'fechaInicio' => '2018-08-20',
        'fechaFin' => null,
    ]);
});

test('it seeds courses, grades, and sections successfully for an IE', function () {
    // Run the seeders
    $this->seed(InstitucionesEducSeeder::class);
    $this->seed(InstitucionCursosGradosSeeder::class);

    // Assert that we have 15 courses, 5 grades, and 15 sections in the database
    expect(CursosIE::count())->toBe(15);
    expect(GradosIE::count())->toBe(5);
    expect(SeccionesIE::count())->toBe(15);
});
