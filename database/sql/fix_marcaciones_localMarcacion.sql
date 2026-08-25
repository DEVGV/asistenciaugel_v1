-- ══════════════════════════════════════════════════════════════════════════
-- ARREGLO: reasignar ma.localMarcacion_id al t_localesMarcacion CORRECTO
-- (el del propio trabajador en el localInstEduc donde marcó).
--
-- ⚠ EJECUTA PRIMERO EL SELECT DE PREVIEW, revisa los conteos, y RECIÉN
--   ejecuta el UPDATE dentro de un BEGIN/ROLLBACK/COMMIT.
-- ══════════════════════════════════════════════════════════════════════════

-- (0) Preview: cuántas marcas quedarían reasignadas, por trabajador
WITH marca_local AS (
  SELECT ma.id AS marc_id, ma.trabajador_id,
         ma."localMarcacion_id" AS lm_actual,
         lm_actual."localInstEduc_id" AS localInstEduc_actual,
         lm_correcto.id AS lm_correcto
  FROM conasis.t_marcaciones ma
  LEFT JOIN conasis."t_localesMarcacion" lm_actual
         ON lm_actual.id = ma."localMarcacion_id"
  LEFT JOIN conasis."t_localesMarcacion" lm_correcto
         ON lm_correcto.trabajador_id = ma.trabajador_id
        AND lm_correcto."localInstEduc_id" = lm_actual."localInstEduc_id"
        AND lm_correcto."fechaInicio" <= date(ma."fechaMarcacion")
        AND (lm_correcto."fechaFin" IS NULL OR lm_correcto."fechaFin" >= date(ma."fechaMarcacion"))
)
SELECT trabajador_id, lm_actual, lm_correcto, COUNT(*) AS n_marcas
FROM marca_local
WHERE lm_actual <> lm_correcto OR lm_correcto IS NULL
GROUP BY trabajador_id, lm_actual, lm_correcto
ORDER BY trabajador_id;
-- Si lm_correcto = NULL → ese trabajador NO tiene t_localesMarcacion en ese
-- localInstEduc; crea uno primero (INSERT abajo) o corre la nueva carga.


-- (1) Si aparecen filas con lm_correcto IS NULL, créalas primero.
-- Ajusta trabajador_id, altaTrabajador_id y localInstEduc_id según lo visto.
-- INSERT INTO conasis."t_localesMarcacion"
--   (trabajador_id, "altaTrabajador_id", "localInstEduc_id", "fechaInicio", created_by, created_at)
-- VALUES (11, <alta_id>, <localInstEduc_id>, '2026-05-01', 1, now());


-- (2) UPDATE con transacción para reasignar las marcas
BEGIN;

UPDATE conasis.t_marcaciones ma
SET "localMarcacion_id" = lm_correcto.id
FROM conasis."t_localesMarcacion" lm_actual
JOIN conasis."t_localesMarcacion" lm_correcto
      ON lm_correcto.trabajador_id = -- se calcula abajo
         (SELECT ma2.trabajador_id FROM conasis.t_marcaciones ma2 WHERE ma2.id = ma.id)
     AND lm_correcto."localInstEduc_id" = lm_actual."localInstEduc_id"
     AND lm_correcto."fechaInicio" <= date(ma."fechaMarcacion")
     AND (lm_correcto."fechaFin" IS NULL OR lm_correcto."fechaFin" >= date(ma."fechaMarcacion"))
WHERE lm_actual.id = ma."localMarcacion_id"
  AND lm_correcto.id <> ma."localMarcacion_id";

-- Revisa cuántas filas se actualizaron y decide:
-- ROLLBACK;
-- COMMIT;


-- (3) Re-procesa el mes tras arreglar la data
-- SELECT conasis.f_procesa_asismes_v1(
--   1::bigint, 11::bigint, 2026::smallint, 5::smallint,
--   '2026-05-01'::date, '2026-05-31'::date, 1::bigint
-- );
