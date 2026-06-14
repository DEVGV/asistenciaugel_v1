<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Permisos directos por usuario y por IE (sin pasar por perfil)
        Schema::create('auth.usuario_permisos_ie', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('permiso_id');
            $table->bigInteger('institucionEducativa_id')->nullable(); // NULL = permiso global (UGEL)
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('user_id')->references('id')->on('auth.users')->cascadeOnDelete();
            $table->foreign('permiso_id')->references('id')->on('auth.permisos')->cascadeOnDelete();
            $table->foreign('institucionEducativa_id')->references('id')->on('t_institucionesEduc')->cascadeOnDelete();

            // Un usuario no puede tener el mismo permiso dos veces en la misma IE
            $table->unique(['user_id', 'permiso_id', 'institucionEducativa_id'], 'uq_usuario_permiso_ie');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth.usuario_permisos_ie');
    }
};
