-- View: "v_consulta_cauxiliar"

-- DROP VIEW v_consulta_cauxiliar;

CREATE OR REPLACE VIEW v_consulta_cauxiliar AS
(( SELECT a.ano, substr(a.cod_partida::text, 1, 1)::integer AS cod_grupo, substr(a.cod_partida::text, 2, 2)::integer AS cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, upper(a.denominacion) AS denominacion, ' '::text AS concepto, (((((((((substr(a.cod_partida::text, 1, 1) || ''::text) || substr(a.cod_partida::text, 2, 2)) || ''::text) || mascara_cero(a.cod_generica)) || mascara_cero(a.cod_especifica)) || mascara_cero(a.cod_sub_espec)) || mascara_cero(a.cod_auxiliar)) || ', '::text) || upper(a.denominacion)) || ''::text AS denominacion_busqueda
   FROM cfpd05_auxiliar a
  GROUP BY a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion
  ORDER BY a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar)
UNION
( SELECT b.ejercicio AS ano, b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, 0 AS cod_auxiliar, upper(b.denominacion) AS denominacion, upper(b.concepto) AS concepto, (((((((((b.cod_grupo::text || ''::text) || mascara_cero(b.cod_partida)) || mascara_cero(b.cod_generica)) || mascara_cero(b.cod_especifica)) || '00'::text) || ', '::text) || upper(b.denominacion)) || ''::text) || ', '::text) || upper(b.concepto) AS denominacion_busqueda
   FROM cfpd01_ano_5_sub_espec b
  WHERE b.cod_grupo = 4
  GROUP BY b.ejercicio, b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.denominacion, b.concepto
  ORDER BY b.ejercicio, b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec))
UNION
( SELECT c.ejercicio AS ano, c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, upper(c.denominacion) AS denominacion, upper(c.concepto) AS concepto, (((((((((((c.cod_grupo::text || ''::text) || mascara_cero(c.cod_partida)) || mascara_cero(c.cod_generica)) || mascara_cero(c.cod_especifica)) || '00'::text) || ''::text) || '00'::text) || ', '::text) || upper(c.denominacion)) || ''::text) || ', '::text) || upper(c.concepto) AS denominacion_busqueda
   FROM cfpd01_ano_4_especifica c
  WHERE c.cod_grupo = 4
  GROUP BY c.ejercicio, c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica, c.denominacion, c.concepto
  ORDER BY c.ejercicio, c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica);

ALTER TABLE v_consulta_cauxiliar OWNER TO sisap;
