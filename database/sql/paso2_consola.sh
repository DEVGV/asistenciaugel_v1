#!/bin/bash
# Ejecuta el diagnóstico paso 2 directamente contra el contenedor Postgres
# Uso:  bash database/sql/paso2_consola.sh

docker exec -i asistencia_db psql -U sail -d asistenciaugel <<'SQL'
\pset border 2
\pset format aligned

\echo '════════════════════════════════════════════════════════════'
\echo '(a) ¿Qué es lm.id=11?'
\echo '════════════════════════════════════════════════════════════'
SELECT lm.id AS lm_id, lm.trabajador_id, lm."localInstEduc_id",
       lie."institucionEduc_id" AS ie_del_local,
       lm."fechaInicio", lm."fechaFin"
FROM conasis."t_localesMarcacion" lm
JOIN conasis."t_localesInstEduc" lie ON lie.id = lm."localInstEduc_id"
WHERE lm.id = 11;

\echo ''
\echo '════════════════════════════════════════════════════════════'
\echo '(b) Altas del trabajador 11'
\echo '════════════════════════════════════════════════════════════'
SELECT id, trabajador_id, "institucionEducativa_id",
       "fechaInicio", "fechaFin", "fechaBaja"
FROM public."t_altasTrabajadores"
WHERE trabajador_id = 11
ORDER BY "fechaInicio";

\echo ''
\echo '════════════════════════════════════════════════════════════'
\echo '(c) Horarios del trabajador (sin filtros)'
\echo '════════════════════════════════════════════════════════════'
SELECT id, "institucionEduc_id", trabajador_id,
       "fechaInicio", "fechaFin", archivado, activo
FROM conasis."t_horariosTrabajador"
WHERE trabajador_id = 11
ORDER BY "fechaInicio";

\echo ''
\echo '════════════════════════════════════════════════════════════'
\echo '(d) ¿Los horarios tienen detalle?'
\echo '════════════════════════════════════════════════════════════'
SELECT dh."horarioTrabajador_id",
       COUNT(*) AS filas_detalle,
       BOOL_OR(dh.aplicar) AS alguna_aplicar_true
FROM conasis."t_detalleHorarios" dh
JOIN conasis."t_horariosTrabajador" ht ON ht.id = dh."horarioTrabajador_id"
WHERE ht.trabajador_id = 11
GROUP BY dh."horarioTrabajador_id";

\echo ''
\echo '════════════════════════════════════════════════════════════'
\echo '(e) ¿Qué IE tiene la asistencia generada?'
\echo '════════════════════════════════════════════════════════════'
SELECT id, "institucionEduc_id", trabajador_id, anio, mes,
       "fechaDesde", "fechaHasta"
FROM conasis.t_asistencia
WHERE trabajador_id = 11 AND anio = 2026 AND mes = 5;
SQL
