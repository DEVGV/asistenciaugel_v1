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
        Schema::create('conasis.t_locales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 200)->nullable();
            $table->string('domicilio', 200)->nullable();
            $table->bigInteger('zona_id')->nullable();
            $table->string('ubigeo', 20)->nullable();
            $table->decimal('utm_huso', 15, 4)->nullable();
            $table->string('utm_banda', 10)->nullable();
            $table->decimal('utm_x_este', 15, 4)->nullable();
            $table->decimal('utm_y_norte', 15, 4)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('zona_id')->references('id')->on('t_zonas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_locales');
    }
};
