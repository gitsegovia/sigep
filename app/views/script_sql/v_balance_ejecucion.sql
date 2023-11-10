-- View: v_balance_ejecucion

-- DROP VIEW v_balance_ejecucion;

CREATE OR REPLACE VIEW v_balance_ejecucion AS

SELECT  a.cod_presi,
        a.cod_entidad,
        a.cod_tipo_inst,
        a.cod_inst,
        a.cod_dep,
        a.ano,
        a.cod_sector,
 upper(( SELECT a1.denominacion
           FROM cfpd02_sector a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep AND a1.ano = a.ano AND a1.cod_sector = a.cod_sector)) AS deno_sector,
          a.cod_programa,
upper(( SELECT a1.denominacion
           FROM cfpd02_programa a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep AND a1.ano = a.ano AND a1.cod_sector = a.cod_sector AND a1.cod_programa = a.cod_programa)) AS deno_programa,
         a.cod_sub_prog,
upper(( SELECT a1.denominacion
           FROM cfpd02_sub_prog a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep AND a1.ano = a.ano AND a1.cod_sector = a.cod_sector AND a1.cod_programa = a.cod_programa AND a1.cod_sub_prog = a.cod_sub_prog)) AS deno_sub_prog,
         a.cod_proyecto,
upper(( SELECT a1.denominacion
           FROM cfpd02_proyecto a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep AND a1.ano = a.ano AND a1.cod_sector = a.cod_sector AND a1.cod_programa = a.cod_programa AND a1.cod_sub_prog = a.cod_sub_prog AND a1.cod_proyecto = a.cod_proyecto)) AS deno_proyecto,
          a.cod_activ_obra,
upper(( SELECT a1.denominacion
           FROM cfpd02_activ_obra a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep AND a1.ano = a.ano AND a1.cod_sector = a.cod_sector AND a1.cod_programa = a.cod_programa AND a1.cod_sub_prog = a.cod_sub_prog AND a1.cod_proyecto = a.cod_proyecto AND a1.cod_activ_obra = a.cod_activ_obra)) AS deno_activ_obra,
          a.cod_partida,
          a.cod_generica,
          a.cod_especifica,
          a.cod_sub_espec,
upper(( SELECT a1.denominacion
           FROM cfpd01_ano_5_sub_espec a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica AND a1.cod_sub_espec = a.cod_sub_espec)) AS deno_sub_espec,
         a.cod_auxiliar,
upper(( SELECT a1.denominacion
           FROM cfpd05_auxiliar a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep AND a1.ano = a.ano AND a1.cod_sector = a.cod_sector AND a1.cod_programa = a.cod_programa AND a1.cod_sub_prog = a.cod_sub_prog AND a1.cod_proyecto = a.cod_proyecto AND a1.cod_activ_obra = a.cod_activ_obra AND a1.cod_partida = a.cod_partida AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica AND a1.cod_sub_espec = a.cod_sub_espec AND a1.cod_auxiliar = a.cod_auxiliar)) AS deno_auxiliar,
         a.asignacion_anual,
         (a.aumento_traslado_anual + a.credito_adicional_anual)::numeric(22,2) AS aumento,
         (a.disminucion_traslado_anual + a.rebaja_anual)::numeric(22,2) AS disminucion,
         (a.asignacion_anual + (a.aumento_traslado_anual + a.credito_adicional_anual) - (a.disminucion_traslado_anual + a.rebaja_anual))::numeric(22,2) AS total_asignacion,
         (a.precompromiso_congelado + a.precompromiso_requisicion + a.precompromiso_obras + a.precompromiso_fondo_avance)::numeric(22,2) AS pre_compromiso,
         a.compromiso_anual,
         a.causado_anual,
         a.pagado_anual,
         (a.causado_anual - a.pagado_anual)::numeric(22,2) AS deuda,
         (a.asignacion_anual + a.aumento_traslado_anual + a.credito_adicional_anual - (a.disminucion_traslado_anual + a.rebaja_anual + a.compromiso_anual + a.precompromiso_congelado + a.precompromiso_requisicion + a.precompromiso_obras + a.precompromiso_fondo_avance))::numeric(22,2) AS disponibilidad,
         a.cod_tipo_gasto,
         a.tipo_presupuesto

FROM cfpd05 a;

ALTER TABLE v_balance_ejecucion OWNER TO sisap;

