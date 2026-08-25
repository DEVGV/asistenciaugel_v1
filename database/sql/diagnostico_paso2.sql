-- ══════════════════════════════════════════════════════════════════════════
-- PASO 2: entender qué es lm.id=11 y por qué no hay horarios
-- Trabajador=11, IE=1, mayo 2026
-- ══════════════════════════════════════════════════════════════════════════

-- (a) ¿Qué es realmente lm.id=11? ¿A quién pertenece y en qué IE?
SELECT lm.id AS lm_id, lm.trabajador_id, lm."localInstEduc_id",
       lie."institucionEduc_id" AS ie_del_local,
       lm."fechaInicio", lm."fechaFin"
FROM conasis."t_localesMarcacion" lm
JOIN conasis."t_localesInstEduc" lie ON lie.id = lm."localInstEduc_id"
WHERE lm.id = 11;
-- Si sale trabajador_id <> 11 → la carga puso las marcas apuntando al local
-- de OTRO trabajador (bug confirmado en CargaMarcacionesService).

-- (b) ¿El trabajador 11 tiene alta en IE=1?
SELECT id, trabajador_id, "institucionEducativa_id", "fechaInicio", "fechaFin", "fechaBaja"
FROM public."t_altasTrabajadores"
WHERE trabajador_id = 11
ORDER BY "fechaInicio";

-- (c) Horarios del trabajador (sin filtros): existencia, IE y vigencias
SELECT id, "institucionEduc_id", trabajador_id, "fechaInicio", "fechaFin",
       archivado, activo
FROM conasis."t_horariosTrabajador"
WHERE trabajador_id = 11
ORDER BY "fechaInicio";

-- (d) ¿Los horarios tienen detalle?
SELECT dh."horarioTrabajador_id", COUNT(*) AS filas_detalle,
       BOOL_OR(dh.aplicar) AS alguna_aplicar_true
FROM conasis."t_detalleHorarios" dh
JOIN conasis."t_horariosTrabajador" ht ON ht.id = dh."horarioTrabajador_id"
WHERE ht.trabajador_id = 11
GROUP BY dh."horarioTrabajador_id";

-- (e) ¿Cuál es la IE que se está procesando en el consolidado que ves en pantalla?
-- (para confirmar que es IE=1 y no otra)
SELECT id, "institucionEduc_id", trabajador_id, anio, mes, "fechaDesde", "fechaHasta"
FROM conasis.t_asistencia
WHERE trabajador_id = 11 AND anio = 2026 AND mes = 5;
