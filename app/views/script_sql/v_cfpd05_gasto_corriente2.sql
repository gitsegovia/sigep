-- View: v_cfpd05_gasto_corriente2

-- DROP VIEW v_cfpd05_gasto_corriente2;

CREATE OR REPLACE VIEW v_cfpd05_gasto_corriente2 AS 
 SELECT 
 a.cod_presi, 
 a.cod_entidad, 
 a.cod_tipo_inst, 
 a.cod_inst, 
 a.cod_dep, 
 a.ano, 
 (SELECT sum(x.asignacion_anual + (x.aumento_traslado_anual + x.credito_adicional_anual - (x.disminucion_traslado_anual + x.rebaja_anual))) AS sum
  FROM cfpd05 x
  WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_gasto <> 2 AND x.ano = a.ano) AS gasto_corriente


  FROM cfpd05 a


  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano;

ALTER TABLE v_cfpd05_gasto_corriente2 OWNER TO sisap;


