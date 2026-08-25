<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Crear tabla de perfiles (roles del sistema)
        Schema::create('auth.perfiles', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nombre', 100);           // Ej: "Docente", "Director", "Admin UGEL"
            $table->string('descripcion', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('created_at')->nullable();
        });

        // 2. Crear tabla de permisos (acciones del sistema)
        Schema::create('auth.permisos', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo', 80)->unique();   // Ej: "trabajadores.crear", "asistencia.ver"
            $table->string('modulo', 60);              // Ej: "trabajadores", "asistencia", "reportes"
            $table->string('descripcion', 255)->nullable();
            $table->boolean('activo')->default(true);
        });

        // 3. Relación perfil → permisos (plantilla de permisos por perfil)
        Schema::create('auth.perfil_permisos', function (Blueprint $table) {
            $table->unsignedSmallInteger('perfil_id');
            $table->unsignedSmallInteger('permiso_id');
            $table->primary(['perfil_id', 'permiso_id']);
            $table->foreign('perfil_id')->references('id')->on('auth.perfiles')->cascadeOnDelete();
            $table->foreign('permiso_id')->references('id')->on('auth.permisos')->cascadeOnDelete();
        });

        // 4. Reestructurar auth.users
        Schema::table('auth.users', function (Blueprint $table) {
            // Quitar columnas que ya no se usan
            $table->dropColumn(['name', 'email', 'email_verified_at']);

            // Agregar nuevas columnas (solo si no existen)
            if (!Schema::hasColumn('auth.users', 'trabajador_id')) {
                $table->bigInteger('trabajador_id')->nullable()->after('id');
                $table->foreign('trabajador_id')->references('id')->on('t_trabajador');
            }
            $table->string('login', 20)->unique()->after('trabajador_id');  // nro documento
            $table->boolean('activo')->default(true)->after('remember_token');
        });

        // Quitar password_reset_tokens que dependía de email
        Schema::dropIfExists('auth.password_reset_tokens');

        // Recrear password_reset_tokens con email
        Schema::create('auth.password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 255)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 5. Asignación de perfil por usuario y por IE (permisos contextuales)
        Schema::create('auth.usuario_perfil_ie', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('perfil_id');
            $table->bigInteger('institucionEducativa_id')->nullable(); // NULL = permiso global (UGEL)
            $table->boolean('activo')->default(true);
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('user_id')->references('id')->on('auth.users')->cascadeOnDelete();
            $table->foreign('perfil_id')->references('id')->on('auth.perfiles');
            $table->foreign('institucionEducativa_id')->references('id')->on('t_institucionesEduc');

            // Un usuario solo puede tener un perfil por IE
            $table->unique(['user_id', 'institucionEducativa_id'], 'uq_usuario_ie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth.usuario_perfil_ie');
        Schema::dropIfExists('auth.password_reset_tokens');

        Schema::table('auth.users', function (Blueprint $table) {
            $table->dropForeign(['trabajador_id']);
            $table->dropColumn(['trabajador_id', 'login', 'activo']);
            $table->string('name')->after('id');
            $table->string('email')->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });

        Schema::create('auth.password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::dropIfExists('auth.perfil_permisos');
        Schema::dropIfExists('auth.permisos');
        Schema::dropIfExists('auth.perfiles');
    }
};
