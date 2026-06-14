<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conasis.t_mobileBiometricCredentials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trabajador_id');
            $table->string('face_status', 20)->default('pending');
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
            $table->unique('trabajador_id');
            $table->foreign('trabajador_id')->references('id')->on('t_trabajador');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement(
                "ALTER TABLE conasis.\"t_mobileBiometricCredentials\" ADD CONSTRAINT t_mobilebiometriccredentials_face_status_check CHECK (face_status IN ('pending', 'approved', 'blocked'))"
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_mobileBiometricCredentials');
    }
};
