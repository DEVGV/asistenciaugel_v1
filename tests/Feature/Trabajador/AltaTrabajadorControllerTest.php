<?php

use App\Models\AltasTrabajadores;
use App\Models\Personas;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);

    // Obtener IDs reales existentes dinámicamente para evitar violaciones de FK
    $this->altaIeId = DB::table('t_institucionesEduc')->value('id') ?? 1;
    $this->altaCondId = DB::table('t_condicionesLaborales')->value('id') ?? 1;
    $this->altaTipoContratoId = DB::table('param.t12_tipoContrato')->value('id') ?? 1;
    $this->altaRolId = DB::table('param.t00_rolTrabajador')->value('id') ?? 1;
    $this->altaSituacionId = DB::table('param.t15_situacionLaboral')->value('id') ?? 1;
    $this->altaMotivoBajaId = DB::table('param.t17_motivoDeBajas')->value('id') ?? 1;
    $this->altaAreaId = DB::table('t_areas')->value('id') ?? 1;
    $this->altaCargoId = DB::table('t_cargos')->value('id') ?? 1;

    // Crear persona y trabajador de apoyo
    $this->persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->unique()->numerify('########'),
        'paterno' => 'ALTA',
        'materno' => 'TEST',
        'nombre' => 'WORKER',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => 1,
        'activo' => true,
    ]);

    $this->trabajador = Trabajador::create([
        'persona_id' => $this->persona->id,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);
});

/** Helper para crear una alta con todos los campos NOT NULL. */
function makeAlta($test, Trabajador $trabajador, User $user, string $fechaInicio = '2025-01-01'): AltasTrabajadores
{
    return AltasTrabajadores::create([
        'trabajador_id' => $trabajador->id,
        'institucionEducativa_id' => $test->altaIeId,
        'condicionLaboral_id' => $test->altaCondId,
        'tipoContrato_id' => $test->altaTipoContratoId,
        'rolTrabajador_id' => $test->altaRolId,
        'situacionLaboral_id' => $test->altaSituacionId,
        'area_id' => $test->altaAreaId,
        'cargo_id' => $test->altaCargoId,
        'fechaInicio' => $fechaInicio,
        'fechaAlta' => $fechaInicio,
        'created_by' => $user->id,
    ]);
}

it('can list all altas globally', function () {
    get(route('altas.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('trabajador/altas/Index'));
});

it('can store a new alta for a trabajador', function () {
    post(route('trabajadores.altas.store', $this->trabajador), [
        'trabajador_id' => $this->trabajador->id,
        'institucionEducativa_id' => $this->altaIeId,
        'condicionLaboral_id' => $this->altaCondId,
        'tipoContrato_id' => $this->altaTipoContratoId,
        'rolTrabajador_id' => $this->altaRolId,
        'situacionLaboral_id' => $this->altaSituacionId,
        'area_id' => $this->altaAreaId,
        'cargo_id' => $this->altaCargoId,
        'fechaInicio' => '2025-01-01',
    ])->assertRedirect();

    expect(
        AltasTrabajadores::where('trabajador_id', $this->trabajador->id)->exists()
    )->toBeTrue();
});

it('validates required fields on alta store', function () {
    post(route('trabajadores.altas.store', $this->trabajador), [])
        ->assertSessionHasErrors([
            'trabajador_id',
            'institucionEducativa_id',
            'condicionLaboral_id',
            'tipoContrato_id',
            'rolTrabajador_id',
            'situacionLaboral_id',
            'area_id',
            'cargo_id',
            'fechaInicio',
        ]);
});

it('validates fecha_fin must be after or equal fecha_inicio', function () {
    post(route('trabajadores.altas.store', $this->trabajador), [
        'trabajador_id' => $this->trabajador->id,
        'institucionEducativa_id' => $this->altaIeId,
        'condicionLaboral_id' => $this->altaCondId,
        'tipoContrato_id' => $this->altaTipoContratoId,
        'rolTrabajador_id' => $this->altaRolId,
        'situacionLaboral_id' => $this->altaSituacionId,
        'fechaInicio' => '2025-06-01',
        'fechaFin' => '2025-01-01', // before fechaInicio
    ])->assertSessionHasErrors(['fechaFin']);
});

it('can update an existing alta', function () {
    $alta = makeAlta($this, $this->trabajador, $this->user);

    put(route('altas.update', $alta), [
        'trabajador_id' => $this->trabajador->id,
        'institucionEducativa_id' => $this->altaIeId,
        'condicionLaboral_id' => $this->altaCondId,
        'tipoContrato_id' => $this->altaTipoContratoId,
        'rolTrabajador_id' => $this->altaRolId,
        'situacionLaboral_id' => $this->altaSituacionId,
        'area_id' => $this->altaAreaId,
        'cargo_id' => $this->altaCargoId,
        'fechaInicio' => '2025-03-01',
        'observacion' => 'Actualizado correctamente',
    ])->assertRedirect();

    expect($alta->fresh()->fechaInicio)->toBe('2025-03-01');
    expect($alta->fresh()->observacion)->toBe('Actualizado correctamente');
});

it('can dar baja to an active alta', function () {
    $alta = makeAlta($this, $this->trabajador, $this->user);

    post(route('altas.baja', $alta), [
        'fechaBaja' => '2025-12-31',
        'motivoBaja_id' => $this->altaMotivoBajaId,
    ])->assertRedirect();

    expect($alta->fresh()->fechaBaja)->toBe('2025-12-31');
    expect($alta->fresh()->motivoBaja_id)->toBe($this->altaMotivoBajaId);
});

it('validates fechaBaja must be after fechaInicio', function () {
    $alta = makeAlta($this, $this->trabajador, $this->user, '2025-06-01');

    post(route('altas.baja', $alta), [
        'fechaBaja' => '2025-01-01', // before fechaInicio
        'motivoBaja_id' => $this->altaMotivoBajaId,
    ])->assertSessionHasErrors(['fechaBaja']);
});

it('can destroy an alta', function () {
    $alta = makeAlta($this, $this->trabajador, $this->user);
    $altaId = $alta->id;

    delete(route('altas.destroy', $alta))->assertRedirect();

    expect(AltasTrabajadores::find($altaId))->toBeNull();
});
