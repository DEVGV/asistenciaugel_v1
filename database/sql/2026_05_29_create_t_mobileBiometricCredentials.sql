CREATE TABLE IF NOT EXISTS conasis."t_mobileBiometricCredentials" (
    id BIGSERIAL PRIMARY KEY,
    trabajador_id BIGINT NOT NULL,
    face_status VARCHAR(20) NOT NULL DEFAULT 'pending',
    face_template VARCHAR(64) NULL,
    face_threshold SMALLINT NOT NULL DEFAULT 12,
    face_embedding JSONB NULL,
    face_similarity_threshold NUMERIC(6,4) NOT NULL DEFAULT 0.6000,
    face_enrolled_at TIMESTAMP NULL,
    face_approved_at TIMESTAMP NULL,
    local_biometric_enabled BOOLEAN NOT NULL DEFAULT FALSE,
    local_biometric_enabled_at TIMESTAMP NULL,
    failed_attempts SMALLINT NOT NULL DEFAULT 0,
    last_face_distance SMALLINT NULL,
    last_face_similarity NUMERIC(6,4) NULL,
    blocked_until TIMESTAMP NULL,
    last_verified_at TIMESTAMP NULL,
    created_by BIGINT NULL,
    updated_by BIGINT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT t_mobilebiometriccredentials_trabajador_unique UNIQUE (trabajador_id),
    CONSTRAINT t_mobilebiometriccredentials_trabajador_fk
        FOREIGN KEY (trabajador_id) REFERENCES public.t_trabajador(id),
    CONSTRAINT t_mobilebiometriccredentials_face_status_check
        CHECK (face_status IN ('pending', 'approved', 'blocked'))
);
