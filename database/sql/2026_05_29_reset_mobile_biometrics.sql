-- Reset global para pruebas repetidas de enrolamiento/validacion facial.
UPDATE conasis."t_mobileBiometricCredentials"
SET face_status = 'pending',
    face_template = NULL,
    face_enrolled_at = NULL,
    face_approved_at = NULL,
    local_biometric_enabled = FALSE,
    local_biometric_enabled_at = NULL,
    failed_attempts = 0,
    last_face_distance = NULL,
    blocked_until = NULL,
    last_verified_at = NULL,
    updated_at = NOW();

-- Opcional: limpiar marcas moviles no procesadas del dia actual.
-- DELETE FROM conasis."t_marcaciones"
-- WHERE "medioMarcacion" = 'M' AND "procesado" = FALSE AND DATE("fechaMarcacion") = CURRENT_DATE;
