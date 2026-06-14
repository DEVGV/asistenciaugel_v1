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
        Schema::create('conasis.t_relojes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 200)->nullable();
            $table->string('dreccionIP', 30)->nullable();
            $table->string('direccionMac', 50)->nullable();
            $table->smallInteger('puerto')->nullable();
            $table->string('serie', 100)->nullable();
            $table->bigInteger('localInstEduc_id')->nullable();
            $table->bigInteger('idBiometrico')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('localInstEduc_id')->references('id')->on('conasis.t_localesInstEduc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_relojes');
    }
};
