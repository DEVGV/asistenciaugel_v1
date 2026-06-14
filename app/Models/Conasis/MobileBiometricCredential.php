<?php

namespace App\Models\Conasis;

use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileBiometricCredential extends Model
{
    protected $table = 'conasis.t_mobileBiometricCredentials';

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_BLOCKED = 'blocked';

    protected $fillable = [
        'trabajador_id',
        'face_status',
        'face_template',
        'face_threshold',
        'face_embedding',
        'face_similarity_threshold',
        'face_enrolled_at',
        'face_approved_at',
        'local_biometric_enabled',
        'local_biometric_enabled_at',
        'failed_attempts',
        'last_face_distance',
        'last_face_similarity',
        'blocked_until',
        'last_verified_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'face_enrolled_at' => 'datetime',
            'face_approved_at' => 'datetime',
            'face_threshold' => 'integer',
            'face_embedding' => 'array',
            'face_similarity_threshold' => 'float',
            'local_biometric_enabled' => 'boolean',
            'local_biometric_enabled_at' => 'datetime',
            'blocked_until' => 'datetime',
            'last_verified_at' => 'datetime',
            'failed_attempts' => 'integer',
            'last_face_distance' => 'integer',
            'last_face_similarity' => 'float',
        ];
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
}
