

-- View: v_credito_agrupado_dep

-- DROP VIEW v_credito_agrupado_dep;

CREATE OR REPLACE VIEW v_credito_agrupado_dep2 AS
((((((((((( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, 0 AS cod_programa, 0 AS cod_sub_prog, 0 AS cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd02_sector a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep AND a1.ano = a.ano AND a1.cod_sector = a.cod_sector)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 1 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector,a.tipo_presupuesto
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, 0 AS cod_sub_prog, 0 AS cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd02_programa a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep AND a1.ano = a.ano AND a1.cod_sector = a.cod_sector AND a1.cod_programa = a.cod_programa)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 2 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, 0 AS cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd02_sub_prog a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep AND a1.ano = a.ano AND a1.cod_sector = a.cod_sector AND a1.cod_programa = a.cod_programa AND a1.cod_sub_prog = a.cod_sub_prog)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 3 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_2_partida a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 4 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_partida,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_partida, a.cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_3_generica a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 5 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_partida, a.cod_generica,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_partida, a.cod_generica, a.cod_especifica, 0 AS cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_4_especifica a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 6 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_partida, a.cod_generica, a.cod_especifica,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_5_sub_espec a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica AND a1.cod_sub_espec = a.cod_sub_espec)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 7 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, 0 AS cod_programa, 0 AS cod_sub_prog, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_5_sub_espec a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica AND a1.cod_sub_espec = a.cod_sub_espec)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 8 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, 0 AS cod_sector, 0 AS cod_programa, 0 AS cod_sub_prog, a.cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_2_partida a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 9 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, 0 AS cod_sub_prog, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_5_sub_espec a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica AND a1.cod_sub_espec = a.cod_sub_espec)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 10 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, 0 AS cod_programa, 0 AS cod_sub_prog, a.cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_2_partida a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 11 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_partida,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, 0 AS cod_sub_prog, a.cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_2_partida a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 12 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_partida,a.tipo_presupuesto)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, 0 AS cod_sector, 0 AS cod_programa, 0 AS cod_sub_prog, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.tipo_presupuesto, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_5_sub_espec a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica AND a1.cod_sub_espec = a.cod_sub_espec)) AS denominacion, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento) AS aumento, sum(a.disminucion) AS disminucion, sum(a.total_asignacion) AS total_asignacion, sum(a.compromiso_anual) AS compromiso, sum(a.causado_anual) AS causado, sum(a.pagado_anual) AS pagado, sum(a.deuda) AS deuda, sum(a.disponibilidad) AS disponibilidad, 13 AS modelo_reporte
   FROM v_balance_ejecucion a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.tipo_presupuesto;

ALTER TABLE v_credito_agrupado_dep2 OWNER TO sisap;
