-- View: cfpd30_reintegro_union_1

-- DROP VIEW cfpd30_reintegro_union_1;

CREATE OR REPLACE VIEW cfpd30_reintegro_union_1 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, sum(a.monto_causado) AS monto, substr(b.fecha_reintegro::text, 6, 2)::integer AS mes
   FROM cfpd30_reintegro_partidas a, cfpd30_reintegro_cuerpo b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND b.condicion_actividad = 1 AND a.ano_reintegro = b.ano_reintegro AND a.numero_reintegro = b.numero_reintegro
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, substr(b.fecha_reintegro::text, 6, 2)::integer
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, substr(b.fecha_reintegro::text, 6, 2)::integer;

ALTER TABLE cfpd30_reintegro_union_1 OWNER TO sisap;

