<?php

use App\Models\AltasTrabajadores;
use App\Models\Areas;
use App\Models\Cargos;
use App\Models\Conasis\ConasisCargaHoraria;
use App\Models\Conasis\ConasisDetalleHorarios;
use App\Models\Conasis\ConasisHorariosCursos;
use App\Models\Conasis\ConasisHorariosTrabajador;
use App\Models\CondicionesLaborales;
use App\Models\CursosIE;
use App\Models\GradosIE;
use App\Models\InstitucionesEduc;
use App\Models\Personas;
use App\Models\SeccionesIE;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);

    // Setup master data
    $this->ie = InstitucionesEduc::create([
        'nombreLegal' => 'IE Test Horarios '.uniqid(),
        'codigoInstitucion' => 'IE'.rand(1000, 9999),
        'modalidadFormativa_id' => 1,
        'nivelCiclo_id' => 1,
        'created_by' => $this->user->id,
    ]);

    $this->grado = GradosIE::create([
        'institucionEduc_id' => $this->ie->id,
        'nombre' => '1º Primaria',
        'sigla' => '1P',
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $this->seccion = SeccionesIE::create([
        'grado_id' => $this->grado->id,
        'nombre' => 'Sección A',
        'sigla' => 'A',
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $this->curso = CursosIE::create([
        'institucionEduc_id' => $this->ie->id,
        'nombre' => 'Matemática',
        'sigla' => 'MAT',
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $this->persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->unique()->numerify('########'),
        'paterno' => 'Gomez',
        'materno' => 'Perez',
        'nombre' => 'Juan',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $this->trabajador = Trabajador::create([
        'persona_id' => $this->persona->id,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $area = Areas::factory()->create();
    $cargo = Cargos::factory()->create();
    $condicionLaboral = CondicionesLaborales::factory()->create();

    $this->alta = AltasTrabajadores::create([
        'trabajador_id' => $this->trabajador->id,
        'institucionEducativa_id' => $this->ie->id,
        'condicionLaboral_id' => $condicionLaboral->id,
        'tipoContrato_id' => 1,
        'rolTrabajador_id' => 1,
        'situacionLaboral_id' => 1,
        'area_id' => $area->id,
        'cargo_id' => $cargo->id,
        'fechaInicio' => '2026-03-01',
        'fechaAlta' => '2026-03-01',
        'created_by' => $this->user->id,
    ]);
});

it('can list course schedules for a section', function () {
    $horario = ConasisHorariosCursos::create([
        'anio' => 2026,
        'seccion_id' => $this->seccion->id,
        'curso_id' => $this->curso->id,
        'diaSemana' => 'L',
        'nroDia' => 1,
        'horaInicio' => '08:00:00',
        'horaFin' => '09:30:00',
        'minAcum' => 90,
        'created_by' => $this->user->id,
    ]);

    get(route('horarios-cursos.index', [
        'seccion_id' => $this->seccion->id,
        'anio' => 2026,
    ]))->assertOk()
        ->assertJsonFragment([
            'id' => $horario->id,
            'seccion_id' => $this->seccion->id,
        ]);
});

it('can create a course schedule', function () {
    post(route('horarios-cursos.store'), [
        'anio' => 2026,
        'seccion_id' => $this->seccion->id,
        'curso_id' => $this->curso->id,
        'diaSemana' => 'M',
        'nroDia' => 2,
        'horaInicio' => '09:30',
        'horaFin' => '11:00',
    ])->assertStatus(201);

    expect(ConasisHorariosCursos::where('seccion_id', $this->seccion->id)->count())->toBe(1);
});

it('can update a course schedule', function () {
    $horario = ConasisHorariosCursos::create([
        'anio' => 2026,
        'seccion_id' => $this->seccion->id,
        'curso_id' => $this->curso->id,
        'diaSemana' => 'L',
        'nroDia' => 1,
        'horaInicio' => '08:00:00',
        'horaFin' => '09:30:00',
        'minAcum' => 90,
        'created_by' => $this->user->id,
    ]);

    put(route('horarios-cursos.update', $horario), [
        'anio' => 2026,
        'seccion_id' => $this->seccion->id,
        'curso_id' => $this->curso->id,
        'diaSemana' => 'L',
        'nroDia' => 1,
        'horaInicio' => '08:30',
        'horaFin' => '10:00',
    ])->assertOk();

    expect($horario->fresh()->horaInicio)->toBe('08:30:00');
});

it('can delete a course schedule', function () {
    $horario = ConasisHorariosCursos::create([
        'anio' => 2026,
        'seccion_id' => $this->seccion->id,
        'curso_id' => $this->curso->id,
        'diaSemana' => 'L',
        'nroDia' => 1,
        'horaInicio' => '08:00:00',
        'horaFin' => '09:30:00',
        'minAcum' => 90,
        'created_by' => $this->user->id,
    ]);

    delete(route('horarios-cursos.destroy', $horario))->assertOk();

    expect(ConasisHorariosCursos::find($horario->id))->toBeNull();
});

it('can assign a teacher to a course schedule and auto-generates consolidated worker schedule', function () {
    $horario = ConasisHorariosCursos::create([
        'anio' => 2026,
        'seccion_id' => $this->seccion->id,
        'curso_id' => $this->curso->id,
        'diaSemana' => 'L',
        'nroDia' => 1,
        'horaInicio' => '08:00:00',
        'horaFin' => '09:30:00',
        'minAcum' => 90,
        'created_by' => $this->user->id,
    ]);

    post(route('horarios-cursos.cargas.store', $horario), [
        'horarioCurso_id' => $horario->id,
        'trabajador_id' => $this->trabajador->id,
        'altaTrabajador_id' => $this->alta->id,
        'fechaInicio' => '2026-03-01',
        'titularSuplencia' => 'T',
    ])->assertStatus(201);

    // Verify CargaHoraria exists
    $carga = ConasisCargaHoraria::where('horarioCurso_id', $horario->id)->first();
    expect($carga)->not->toBeNull();
    expect($carga->trabajador_id)->toBe($this->trabajador->id);

    // Verify HorarioTrabajador was auto-generated
    $horarioTrabajador = ConasisHorariosTrabajador::where('trabajador_id', $this->trabajador->id)
        ->where('institucionEduc_id', $this->ie->id)
        ->where('anio', 2026)
        ->first();
    expect($horarioTrabajador)->not->toBeNull();
    expect($horarioTrabajador->activo)->toBeTrue();

    // Verify DetalleHorario details exist and consolidated min/max hours
    $detalle = ConasisDetalleHorarios::where('horarioTrabajador_id', $horarioTrabajador->id)
        ->where('nroDia', 1)
        ->first();
    expect($detalle)->not->toBeNull();
    expect($detalle->entHoraInicio)->toBe('08:00:00');
    expect($detalle->salHoraFin)->toBe('09:30:00');
    expect((float) $detalle->horaAcumula)->toBe(1.5);
});

it('can show worker schedule consolidated page', function () {
    $horarioTrabajador = ConasisHorariosTrabajador::create([
        'anio' => 2026,
        'institucionEduc_id' => $this->ie->id,
        'trabajador_id' => $this->trabajador->id,
        'altaTrabajador_id' => $this->alta->id,
        'nombre' => 'Horario Consolidad',
        'activo' => true,
        'created_by' => $this->user->id,
    ]);

    get(route('horarios-trabajador.show', $horarioTrabajador))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('horario/Show'));
});

it('can list worker schedules and active institutions', function () {
    get(route('horarios-trabajador.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('horario/Index')
            ->has('instituciones')
            ->where('instituciones', function ($instituciones) {
                return collect($instituciones)->contains('id', $this->ie->id);
            })
        );
});

it('can delete a course schedule even if a teacher is assigned', function () {
    $horario = ConasisHorariosCursos::create([
        'anio' => 2026,
        'seccion_id' => $this->seccion->id,
        'curso_id' => $this->curso->id,
        'diaSemana' => 'L',
        'nroDia' => 1,
        'horaInicio' => '08:00:00',
        'horaFin' => '09:30:00',
        'minAcum' => 90,
        'created_by' => $this->user->id,
    ]);

    post(route('horarios-cursos.cargas.store', $horario), [
        'horarioCurso_id' => $horario->id,
        'trabajador_id' => $this->trabajador->id,
        'altaTrabajador_id' => $this->alta->id,
        'fechaInicio' => '2026-03-01',
        'titularSuplencia' => 'T',
    ])->assertStatus(201);

    // Verify DetalleHorario exists before delete
    expect(ConasisDetalleHorarios::where('horarioCursoIni_id', $horario->id)->count())->toBeGreaterThan(0);

    // Now delete the course schedule
    delete(route('horarios-cursos.destroy', $horario))->assertOk();

    expect(ConasisHorariosCursos::find($horario->id))->toBeNull();
    expect(ConasisCargaHoraria::where('horarioCurso_id', $horario->id)->count())->toBe(0);
    expect(ConasisDetalleHorarios::where('horarioCursoIni_id', $horario->id)->count())->toBe(0);
});
