<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-05-29 08:00:00'));
    prepareMobileSqliteSchema();
});

afterEach(function () {
    Carbon::setTestNow();
});

test('guests cannot access mobile profile', function () {
    $this->getJson(route('api.mobile.me'))
        ->assertUnauthorized();
});

test('authenticated teacher can enroll face enable local biometric and mark attendance', function () {
    mobileTeacherFixture();

    $this->postJson(route('api.mobile.login'), [
        'dni' => '45678912',
        'password' => 'password',
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.teacher.documento', '45678912')
        ->assertJsonPath('data.mobile_biometric.face_status', 'pending')
        ->assertJsonPath('data.assignments.0.local_marcacion.id', 1);

    $this->postJson(route('api.mobile.biometria.enroll-face'), [
        'face_image_base64' => fakeFaceBase64(20, 120, 200),
        'face_embedding' => fakeFaceEmbedding(),
    ])
        ->assertCreated()
        ->assertJsonPath('data.biometric.face_status', 'approved')
        ->assertJsonPath('data.biometric.face_similarity_threshold', 0.6);

    $this->postJson(route('api.mobile.biometria.enable-local'))
        ->assertSuccessful()
        ->assertJsonPath('data.local_biometric_enabled', true);

    $this->postJson(route('api.mobile.marcaciones.prevalidate'), [
        'alta_trabajador_id' => 1,
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.allowed', true)
        ->assertJsonPath('data.checks.asignado_marcacion_movil', true);

    $this->postJson(route('api.mobile.marcaciones.store'), [
        'alta_trabajador_id' => 1,
        'biometric_method' => 'local_device',
        'biometric_passed' => true,
        'utm_huso' => 18,
        'utm_base' => 'L',
        'utm_x_este' => 500000,
        'utm_y_norte' => 8660000,
    ])
        ->assertCreated()
        ->assertJsonPath('data.mark.medio_marcacion', 'M')
        ->assertJsonPath('data.mark.procesado', false);

    $this->assertDatabaseHas('conasis.t_marcaciones', [
        'trabajador_id' => 1,
        'altaTrabajador_id' => 1,
        'medioMarcacion' => 'M',
        'tipo' => 'A',
        'procesado' => false,
    ]);
    $markCode = DB::table('conasis.t_marcaciones')->value('codigo');

    expect($markCode)->toStartWith('MOB')
        ->and(strlen($markCode))->toBeLessThanOrEqual(20);
});

test('three failed biometric attempts block mobile marking temporarily', function () {
    $user = mobileTeacherFixture(faceApproved: true);

    $this->actingAs($user);

    foreach (range(1, 3) as $attempt) {
        $this->postJson(route('api.mobile.marcaciones.store'), [
            'alta_trabajador_id' => 1,
            'biometric_method' => 'face',
            'face_image_base64' => fakeFaceBase64(240, 40, 40),
            'face_embedding' => fakeFaceEmbedding(-1.0),
        ])->assertUnprocessable();
    }

    $this->assertDatabaseHas('conasis.t_mobileBiometricCredentials', [
        'trabajador_id' => 1,
        'face_status' => 'blocked',
        'failed_attempts' => 3,
    ]);
});

test('teacher can enable local biometric before face enrollment', function () {
    $user = mobileTeacherFixture();

    $this->actingAs($user);

    $this->postJson(route('api.mobile.biometria.enable-local'))
        ->assertSuccessful()
        ->assertJsonPath('data.local_biometric_enabled', true)
        ->assertJsonPath('data.face_status', 'pending');

    $this->postJson(route('api.mobile.marcaciones.prevalidate'), [
        'alta_trabajador_id' => 1,
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.allowed', true)
        ->assertJsonPath('data.checks.metodo_biometrico_habilitado', true);
});

function mobileTeacherFixture(bool $faceApproved = false): User
{
    Schema::disableForeignKeyConstraints();

    DB::table('t_personas')->insert([
        'id' => 1,
        'pais_id' => 1,
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => '45678912',
        'paterno' => 'Prueba',
        'materno' => 'Docente',
        'nombre' => 'Mario',
        'sexo_id' => 1,
        'activo' => true,
    ]);

    DB::table('t_trabajador')->insert([
        'id' => 1,
        'codigo' => 'TRA0001',
        'persona_id' => 1,
        'activo' => true,
    ]);

    DB::table('t_institucionesEduc')->insert([
        'id' => 1,
        'codigoInstitucion' => 'IE001',
        'nombreLegal' => 'IE Demo UGEL',
        'modalidadFormativa_id' => 1,
        'nivelCiclo_id' => 1,
    ]);

    DB::table('t_altasTrabajadores')->insert([
        'id' => 1,
        'trabajador_id' => 1,
        'fechaInicio' => '2026-01-01',
        'fechaAlta' => '2026-01-01',
        'condicionLaboral_id' => 1,
        'tipoContrato_id' => 1,
        'institucionEducativa_id' => 1,
        'area_id' => 1,
        'cargo_id' => 1,
        'situacionLaboral_id' => 1,
    ]);

    DB::table('conasis.t_locales')->insert([
        'id' => 1,
        'nombre' => 'Local Demo',
        'utm_huso' => 18,
        'utm_banda' => 'L',
        'utm_x_este' => 500000,
        'utm_y_norte' => 8660000,
        'activo' => true,
    ]);

    DB::table('conasis.t_localesInstEduc')->insert([
        'id' => 1,
        'local_id' => 1,
        'institucionEduc_id' => 1,
        'fechaInicio' => '2026-01-01',
    ]);

    DB::table('conasis.t_localesMarcacion')->insert([
        'id' => 1,
        'trabajador_id' => 1,
        'altaTrabajador_id' => 1,
        'localInstEduc_id' => 1,
        'fechaInicio' => '2026-01-01',
    ]);

    DB::table('conasis.t_horariosTrabajador')->insert([
        'id' => 1,
        'codigo' => 'HOR0001',
        'anio' => 2026,
        'institucionEduc_id' => 1,
        'trabajador_id' => 1,
        'altaTrabajador_id' => 1,
        'tipoHorario' => 'D',
        'nombre' => 'Horario demo',
        'fechaInicio' => '2026-01-01',
        'fechaFin' => '2026-12-31',
        'activo' => true,
        'archivado' => false,
    ]);

    DB::table('conasis.t_detalleHorarios')->insert([
        'id' => 1,
        'horarioTrabajador_id' => 1,
        'diaSemana' => 'V',
        'nroDia' => 5,
        'entHoraInicio' => '07:30:00',
        'entHoraFin' => '08:15:00',
        'salHoraInicio' => '13:00:00',
        'salHoraFin' => '13:30:00',
        'aplicar' => true,
    ]);

    if ($faceApproved) {
        DB::table('conasis.t_mobileBiometricCredentials')->insert([
            'trabajador_id' => 1,
            'face_status' => 'approved',
            'face_template' => str_repeat('1', 64),
            'face_threshold' => 0,
            'face_embedding' => json_encode(fakeFaceEmbedding()),
            'face_similarity_threshold' => 0.6,
            'face_enrolled_at' => now(),
            'face_approved_at' => now(),
            'local_biometric_enabled' => false,
            'failed_attempts' => 0,
            'last_face_distance' => null,
            'last_face_similarity' => null,
        ]);
    }

    Schema::enableForeignKeyConstraints();

    return User::factory()->create([
        'trabajador_id' => '1',
    ]);
}

function prepareMobileSqliteSchema(): void
{
    if (DB::connection()->getDriverName() !== 'sqlite') {
        return;
    }

    foreach (['auth', 'conasis'] as $schema) {
        try {
            DB::statement("ATTACH DATABASE ':memory:' AS {$schema}");
        } catch (Throwable) {
            //
        }
    }

    Schema::disableForeignKeyConstraints();

    foreach ([
        'conasis.t_mobileBiometricCredentials',
        'conasis.t_detalleHorarios',
        'conasis.t_horariosTrabajador',
        'conasis.t_marcaciones',
        'conasis.t_localesMarcacion',
        'conasis.t_localesInstEduc',
        'conasis.t_locales',
        't_altasTrabajadores',
        't_institucionesEduc',
        't_trabajador',
        't_personas',
        'auth.users',
    ] as $table) {
        Schema::dropIfExists($table);
    }

    Schema::create('auth.users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('trabajador_id')->nullable();
        $table->rememberToken();
        $table->text('two_factor_secret')->nullable();
        $table->text('two_factor_recovery_codes')->nullable();
        $table->timestamp('two_factor_confirmed_at')->nullable();
        $table->timestamps();
    });

    Schema::create('t_personas', function (Blueprint $table) {
        $table->id();
        $table->smallInteger('pais_id');
        $table->smallInteger('tipoDocIdentidad_id');
        $table->string('docIdentidad')->nullable();
        $table->string('paterno');
        $table->string('materno');
        $table->string('nombre')->nullable();
        $table->smallInteger('sexo_id');
        $table->boolean('activo')->nullable();
    });

    Schema::create('t_trabajador', function (Blueprint $table) {
        $table->id();
        $table->string('codigo')->nullable();
        $table->bigInteger('persona_id')->nullable();
        $table->boolean('activo')->nullable();
    });

    Schema::create('t_institucionesEduc', function (Blueprint $table) {
        $table->id();
        $table->string('codigoInstitucion')->nullable();
        $table->string('nombreLegal')->nullable();
        $table->smallInteger('modalidadFormativa_id');
        $table->smallInteger('nivelCiclo_id');
    });

    Schema::create('t_altasTrabajadores', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('trabajador_id');
        $table->date('fechaInicio');
        $table->date('fechaFin')->nullable();
        $table->date('fechaAlta');
        $table->smallInteger('condicionLaboral_id');
        $table->smallInteger('tipoContrato_id');
        $table->bigInteger('institucionEducativa_id');
        $table->smallInteger('area_id');
        $table->integer('cargo_id');
        $table->smallInteger('situacionLaboral_id');
        $table->date('fechaBaja')->nullable();
    });

    Schema::create('conasis.t_locales', function (Blueprint $table) {
        $table->id();
        $table->string('nombre')->nullable();
        $table->decimal('utm_huso', 15, 4)->nullable();
        $table->string('utm_banda')->nullable();
        $table->decimal('utm_x_este', 15, 4)->nullable();
        $table->decimal('utm_y_norte', 15, 4)->nullable();
        $table->boolean('activo')->nullable();
    });

    Schema::create('conasis.t_localesInstEduc', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('local_id')->nullable();
        $table->bigInteger('institucionEduc_id')->nullable();
        $table->date('fechaInicio')->nullable();
    });

    Schema::create('conasis.t_localesMarcacion', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('trabajador_id');
        $table->bigInteger('altaTrabajador_id')->nullable();
        $table->bigInteger('localInstEduc_id')->nullable();
        $table->date('fechaInicio')->nullable();
        $table->date('fechaFin')->nullable();
    });

    Schema::create('conasis.t_marcaciones', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('trabajador_id');
        $table->bigInteger('altaTrabajador_id')->nullable();
        $table->bigInteger('localMarcacion_id')->nullable();
        $table->string('codigo')->nullable();
        $table->timestamp('fechaMarcacion')->nullable();
        $table->timestamp('fechaRegistro')->nullable();
        $table->string('tipo', 1)->nullable();
        $table->string('medioMarcacion', 1)->nullable();
        $table->boolean('procesado')->nullable();
        $table->decimal('utm_huso', 15, 4)->nullable();
        $table->string('utm_base')->nullable();
        $table->decimal('utm_x_este', 15, 4)->nullable();
        $table->decimal('utm_y_norte', 15, 4)->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('conasis.t_horariosTrabajador', function (Blueprint $table) {
        $table->id();
        $table->string('codigo')->nullable();
        $table->smallInteger('anio')->nullable();
        $table->bigInteger('institucionEduc_id')->nullable();
        $table->bigInteger('trabajador_id');
        $table->bigInteger('altaTrabajador_id')->nullable();
        $table->string('tipoHorario', 1)->nullable();
        $table->string('nombre')->nullable();
        $table->date('fechaInicio')->nullable();
        $table->date('fechaFin')->nullable();
        $table->boolean('archivado')->nullable();
        $table->boolean('activo')->nullable();
    });

    Schema::create('conasis.t_detalleHorarios', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('horarioTrabajador_id');
        $table->string('diaSemana', 1)->nullable();
        $table->smallInteger('nroDia')->nullable();
        $table->time('entHoraInicio')->nullable();
        $table->time('entHoraFin')->nullable();
        $table->time('salHoraInicio')->nullable();
        $table->time('salHoraFin')->nullable();
        $table->boolean('aplicar')->nullable();
    });

    Schema::create('conasis.t_mobileBiometricCredentials', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('trabajador_id')->unique();
        $table->string('face_status')->default('pending');
        $table->string('face_template', 64)->nullable();
        $table->smallInteger('face_threshold')->default(12);
        $table->json('face_embedding')->nullable();
        $table->decimal('face_similarity_threshold', 6, 4)->default(0.6000);
        $table->timestamp('face_enrolled_at')->nullable();
        $table->timestamp('face_approved_at')->nullable();
        $table->boolean('local_biometric_enabled')->default(false);
        $table->timestamp('local_biometric_enabled_at')->nullable();
        $table->smallInteger('failed_attempts')->default(0);
        $table->smallInteger('last_face_distance')->nullable();
        $table->decimal('last_face_similarity', 6, 4)->nullable();
        $table->timestamp('blocked_until')->nullable();
        $table->timestamp('last_verified_at')->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->timestamps();
    });

    Schema::enableForeignKeyConstraints();
}

function fakeFaceEmbedding(float $firstValue = 1.0): array
{
    $embedding = array_fill(0, 192, 0.0);
    $embedding[0] = $firstValue;

    return $embedding;
}

function fakeFaceBase64(int $red, int $green, int $blue): string
{
    $img = imagecreatetruecolor(64, 64);
    for ($y = 0; $y < 64; $y++) {
        for ($x = 0; $x < 64; $x++) {
            $r = ($red + $x * 3 + $y) % 255;
            $g = ($green + $y * 2 + $x) % 255;
            $b = ($blue + $x + $y * 3) % 255;
            $color = imagecolorallocate($img, $r, $g, $b);
            imagesetpixel($img, $x, $y, $color);
        }
    }
    ob_start();
    imagepng($img);
    $binary = ob_get_clean();
    imagedestroy($img);

    return 'data:image/png;base64,'.base64_encode($binary ?: '');
}
