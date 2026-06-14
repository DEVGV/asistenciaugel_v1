<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetMobileBiometricCommand extends Command
{
    protected $signature = 'mobile:reset-biometric {trabajador_id? : ID del trabajador a resetear (opcional)}';

    protected $description = 'Resetea estado biometrico movil para pruebas de enrolamiento';

    public function handle(): int
    {
        $trabajadorId = $this->argument('trabajador_id');

        $query = DB::table('conasis.t_mobileBiometricCredentials');
        if ($trabajadorId) {
            $query->where('trabajador_id', (int) $trabajadorId);
        }

        $affected = $query->update([
            'face_status' => 'pending',
            'face_template' => null,
            'face_embedding' => null,
            'face_enrolled_at' => null,
            'face_approved_at' => null,
            'local_biometric_enabled' => false,
            'local_biometric_enabled_at' => null,
            'failed_attempts' => 0,
            'last_face_distance' => null,
            'last_face_similarity' => null,
            'blocked_until' => null,
            'last_verified_at' => null,
            'updated_at' => now(),
        ]);

        $scope = $trabajadorId ? "trabajador_id={$trabajadorId}" : 'todos los trabajadores';
        $this->info("Reset biometrico aplicado para {$scope}. Registros afectados: {$affected}");

        return self::SUCCESS;
    }
}
