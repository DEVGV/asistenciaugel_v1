-- ═══════════════════════════════════════════════════════════════════════════════
-- DIAGNÓSTICO: ¿Por qué el procedimiento f_procesa_asismes_v1 marca FALTA
-- aunque existan marcaciones?
--
-- Modifica los parámetros al inicio y ejecuta bloque por bloque.
-- ═══════════════════════════════════════════════════════════════════════════════

-- ▼▼▼ CAMBIA ESTOS VALORES ▼▼▼
-- Ejemplo: MONTEZA VÁSQUEZ, EDDY ROBENSOM (DNI 76955021), IE 1, mayo 2026
\set dni         '''76955021'''
\set ie_id       1
\set anio        2026
\set mes         5
\set fecha_desde '''2026-05-01'''
\set fecha_hasta '''2026-05-31'''

-- Resolver trabajador_id a partir del DNI (guardar en una temporal)
DROP TABLE IF EXISTS tmp_diag;
CREATE TEMP TABLE tmp_diag AS
SELECT t.id AS trabajador_id, p."docIdentidad" AS dni,
       trim(p.paterno||' '||p.materno||', '||p.nombre) AS nombre
FROM public.t_personas p
JOIN public.t_trabajador t ON t.persona_id = p.id
WHERE p."docIdentidad" = :dni;
SELECT * FROM tmp_diag;
-- ⚠ Si esto no devuelve nada: el DNI no existe. Verifica el DNI.


-- ─── 1. ¿Existen MARCACIONES en el periodo? ────────────────────────────────────
SELECT ma.id, ma."fechaMarcacion", ma.tipo, ma."localMarcacion_id",
       ma.reloj_id, ma."dispositivoMarca_id", ma.procesado, ma."medioMarcacion"
FROM conasis.t_marcaciones ma
JOIN tmp_diag d ON d.trabajador_id = ma.trabajador_id
WHERE date(ma."fechaMarcacion") BETWEEN :fecha_desde::date AND :fecha_hasta::date
ORDER BY ma."fechaMarcacion";
-- ⚠ Si NO hay filas → el problema es que las marcaciones no se cargaron.
-- ⚠ Si tipo <> 'A' → la función filtra por tipo='A'; tu carga debe grabar 'A'.


-- ─── 2. ¿Coincide localMarcacion_id de la marca con el local del trabajador? ──
-- La función exige: ma.localMarcacion_id = lm.id  AND  lm.trabajador_id = trabajador
SELECT
  ma.id AS marc_id, ma."fechaMarcacion", ma."localMarcacion_id" AS lm_marca,
  lm_trab.id AS lm_asignado_trabajador,
  CASE WHEN ma."localMarcacion_id" = lm_trab.id
       THEN '✔ coincide'
       ELSE '✗ DIFERENTE: la marca apunta a otro local' END AS estado
FROM conasis.t_marcaciones ma
JOIN tmp_diag d ON d.trabajador_id = ma.trabajador_id
LEFT JOIN conasis."t_localesMarcacion" lm_trab
       ON lm_trab.trabajador_id = d.trabajador_id
      AND lm_trab."fechaInicio" <= :fecha_hasta::date
      AND (lm_trab."fechaFin" IS NULL OR lm_trab."fechaFin" >= :fecha_desde::date)
WHERE date(ma."fechaMarcacion") BETWEEN :fecha_desde::date AND :fecha_hasta::date
ORDER BY ma."fechaMarcacion";
-- ⚠ Causa MÁS FRECUENTE de "todos FALTA":
-- La carga masiva del Excel guarda ma.localMarcacion_id apuntando al local
-- del reloj, pero al trabajador no le asignaron ese local en t_localesMarcacion.


-- ─── 3. ¿El local pertenece a la IE que se está procesando? ────────────────────
SELECT lm.id AS localMarc_id, lm.trabajador_id, lm."localInstEduc_id",
       lie."institucionEduc_id", lm."fechaInicio", lm."fechaFin",
       CASE WHEN lie."institucionEduc_id" = :ie_id
            THEN '✔ IE correcta' ELSE '✗ IE distinta' END AS estado_ie
FROM conasis."t_localesMarcacion" lm
JOIN tmp_diag d ON d.trabajador_id = lm.trabajador_id
JOIN conasis."t_localesInstEduc" lie ON lie.id = lm."localInstEduc_id";


-- ─── 4. Detalle del HORARIO del trabajador en ese periodo ──────────────────────
-- Estos son los filtros que aplica el procedimiento sobre t_detalleHorarios
SELECT ht.id AS horario_id, ht."fechaInicio", ht."fechaFin",
       dh.turno_id, dh."nroTurno", dh."diaSemana", dh."nroDia",
       dh."entHoraInicio", dh."entHoraFin", dh."entTolerancia",
       dh."salHoraInicio", dh."salHoraFin",
       dh."entDiaInicio", dh."salDiaFin",
       dh.aplicar,
       CASE WHEN dh.aplicar IS NOT TRUE
            THEN '✗ aplicar=FALSE/NULL → nunca matchea marcas' END AS alerta_aplicar,
       CASE WHEN dh."entDiaInicio" <> dh."salDiaFin"
            THEN '✗ entDiaInicio<>salDiaFin → la función NO procesa turnos partidos' END AS alerta_dia
FROM conasis."t_horariosTrabajador" ht
JOIN conasis."t_detalleHorarios" dh ON dh."horarioTrabajador_id" = ht.id
JOIN tmp_diag d ON d.trabajador_id = ht.trabajador_id
WHERE ht."institucionEduc_id" = :ie_id
  AND ht."fechaInicio" <= :fecha_hasta::date
  AND (ht."fechaFin" IS NULL OR ht."fechaFin" >= :fecha_desde::date)
ORDER BY dh."nroTurno", dh."nroDia";
-- ⚠ Revisa 3 cosas:
-- (a) aplicar='t'  (booleano true).  Si es NULL o false, la marca se ignora.
-- (b) entDiaInicio = salDiaFin  (turno NO nocturno).  Si son distintos, la
--     función tampoco lo procesa (línea del WHERE del tb_temp_group2).
-- (c) Que los horarios "entHoraInicio..salHoraFin" cubran la hora en que
--     realmente se marca (ver bloque 5).


-- ─── 5. ¿Las marcaciones caen dentro del rango horario y del día correcto? ────
-- Simula EXACTAMENTE el filtro interno de tb_temp_group2 de la función
SELECT
  ma.id AS marc_id, ma."fechaMarcacion",
  date_part('dow', ma."fechaMarcacion")::int  AS dow_marca,
  extract(day  from ma."fechaMarcacion")::int AS dia_marca,
  dh."nroTurno", dh."diaSemana", dh."nroDia",
  dh."entHoraInicio", dh."salHoraFin",
  dh.aplicar,
  dh."entDiaInicio" = dh."salDiaFin" AS mismo_dia,
  -- reglas exactas del procedimiento:
  (ma."localMarcacion_id" = lm.id)                                          AS ok_local,
  (ma.tipo = 'A')                                                           AS ok_tipo,
  (dh.aplicar = TRUE)                                                       AS ok_aplicar,
  (dh."entDiaInicio" = dh."salDiaFin")                                      AS ok_no_nocturno,
  (
    (dh."diaSemana"='S' AND date_part('dow', ma."fechaMarcacion") = dh."nroDia")
    OR
    (dh."diaSemana"='D' AND extract(day from ma."fechaMarcacion") = dh."nroDia")
  )                                                                         AS ok_dia,
  ("time"(ma."fechaMarcacion") >= dh."entHoraInicio"
    AND "time"(ma."fechaMarcacion") <= dh."salHoraFin")                     AS ok_hora
FROM conasis.t_marcaciones ma
JOIN tmp_diag d ON d.trabajador_id = ma.trabajador_id
JOIN conasis."t_localesMarcacion" lm
      ON lm.trabajador_id = d.trabajador_id
     AND lm."fechaInicio" <= :fecha_hasta::date
     AND (lm."fechaFin" IS NULL OR lm."fechaFin" >= :fecha_desde::date)
JOIN conasis."t_localesInstEduc" lie
      ON lie.id = lm."localInstEduc_id" AND lie."institucionEduc_id" = :ie_id
JOIN conasis."t_horariosTrabajador" ht
      ON ht.trabajador_id = d.trabajador_id
     AND ht."institucionEduc_id" = :ie_id
     AND ht."fechaInicio" <= :fecha_hasta::date
     AND (ht."fechaFin" IS NULL OR ht."fechaFin" >= :fecha_desde::date)
JOIN conasis."t_detalleHorarios" dh ON dh."horarioTrabajador_id" = ht.id
WHERE date(ma."fechaMarcacion") BETWEEN :fecha_desde::date AND :fecha_hasta::date
ORDER BY ma."fechaMarcacion", dh."nroTurno";
-- Interpretación: cualquier columna "ok_*" en FALSE explica por qué esa marca
-- no fue considerada. La causa suele ser una sola columna en FALSE en TODAS
-- las filas (ej. ok_local=false ⇒ localMarcacion mal enlazado).


-- ─── 6. ¿Cuántas marcas quedan DESPUÉS de aplicar el filtro real? ──────────────
-- Este es literalmente el WHERE que ejecuta la función (sin las ventanas):
SELECT COUNT(*) AS marcas_que_pasan_el_filtro
FROM conasis.t_marcaciones ma
JOIN conasis."t_localesMarcacion" lm ON lm.id = ma."localMarcacion_id"
JOIN conasis."t_localesInstEduc"  lie ON lie.id = lm."localInstEduc_id"
JOIN conasis."t_horariosTrabajador" ht
      ON ht.trabajador_id = ma.trabajador_id
     AND ht."institucionEduc_id" = lie."institucionEduc_id"
JOIN conasis."t_detalleHorarios" dh ON dh."horarioTrabajador_id" = ht.id
JOIN tmp_diag d ON d.trabajador_id = ma.trabajador_id
WHERE ht."institucionEduc_id" = :ie_id
  AND ma.tipo = 'A'
  AND dh.aplicar = TRUE
  AND date(ma."fechaMarcacion") BETWEEN :fecha_desde::date AND :fecha_hasta::date
  AND ht."fechaInicio" <= :fecha_hasta::date
  AND (ht."fechaFin" IS NULL OR ht."fechaFin" >= :fecha_desde::date)
  AND lm."fechaInicio" <= :fecha_hasta::date
  AND (lm."fechaFin" IS NULL OR lm."fechaFin" >= :fecha_desde::date)
  AND (
       (dh."diaSemana"='S' AND date_part('dow', ma."fechaMarcacion") = dh."nroDia")
    OR (dh."diaSemana"='D' AND extract(day from ma."fechaMarcacion") = dh."nroDia")
  )
  AND "time"(ma."fechaMarcacion") >= dh."entHoraInicio"
  AND "time"(ma."fechaMarcacion") <= dh."salHoraFin"
  AND dh."entDiaInicio" = dh."salDiaFin";
-- ⚠ Si esto da 0 → confirma que el problema NO es el trigger sino el filtro
-- (local, tipo, aplicar, día u hora).  Si da >0 → el procedimiento debería
-- estar generando "A"/"T"/"E"; revisa el resultado en t_asistenciaMesTrabajador.


-- ─── 7. Ver lo que quedó guardado en la asistencia procesada ───────────────────
SELECT amt.id, amt.turno_id, amt."nroTurno",
       amt.e1,amt.s1,amt.c1, amt.e2,amt.s2,amt.c2, amt.e10,amt.s10,amt.c10,
       amt.e15,amt.s15,amt.c15
FROM conasis."t_asistenciaMesTrabajador" amt
JOIN conasis.t_asistencia a ON a.id = amt.asistencia_id
JOIN tmp_diag d ON d.trabajador_id = a.trabajador_id
WHERE a."institucionEduc_id" = :ie_id AND a.anio = :anio AND a.mes = :mes;
