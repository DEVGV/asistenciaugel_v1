<?php

use App\Models\Areas;
use App\Models\Cargos;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('auto-generates codigo with correct prefix for Areas', function () {
    $area = Areas::factory()->create();

    expect($area->codigo)->toStartWith('ARE');
    expect(strlen($area->codigo))->toBe(8); // ARE + 5 digits
});

it('auto-generates codigo with correct prefix for Cargos', function () {
    $cargo = Cargos::factory()->create();

    expect($cargo->codigo)->toStartWith('CAR');
    expect(strlen($cargo->codigo))->toBe(8);
});

it('generates sequential codes', function () {
    $area1 = Areas::factory()->create();
    $area2 = Areas::factory()->create();
    $area3 = Areas::factory()->create();

    $num1 = (int) substr($area1->codigo, 3);
    $num2 = (int) substr($area2->codigo, 3);
    $num3 = (int) substr($area3->codigo, 3);

    expect($num2)->toBe($num1 + 1);
    expect($num3)->toBe($num2 + 1);
});

it('does not overwrite an explicitly set codigo', function () {
    $area = new Areas;
    $area->codigo = 'CUSTOM001';
    $area->nombre = 'Test Custom';
    $area->activo = true;
    $area->save();

    expect($area->fresh()->codigo)->toBe('CUSTOM001');
});

it('generates unique codes across multiple models', function () {
    $area = Areas::factory()->create();
    $cargo = Cargos::factory()->create();

    expect($area->codigo)->toStartWith('ARE');
    expect($cargo->codigo)->toStartWith('CAR');
    expect($area->codigo)->not->toBe($cargo->codigo);
});

it('pads numeric part with zeros', function () {
    $area = Areas::factory()->create();

    $numericPart = substr($area->codigo, 3);
    expect(strlen($numericPart))->toBe(5);
    expect($numericPart)->toMatch('/^\d{5}$/');
});
