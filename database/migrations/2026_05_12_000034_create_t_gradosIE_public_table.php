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
        Schema::create('t_gradosIE', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('institucionEduc_id');
            $table->string('codigo', 20)->nullable();
            $table->string('nombre', 100)->nullable();
            $table->string('sigla', 50)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('institucionEduc_id')->references('id')->on('t_institucionesEduc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_gradosIE');
    }
};
