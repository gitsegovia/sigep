-- View: v_buscar_inmuebles

DROP VIEW v_buscar_inmuebles;

CREATE OR REPLACE VIEW v_buscar_inmuebles AS
 SELECT
	a.cod_tipo,
	b.denominacion AS deno_tipo,
	a.cod_grupo,
	c.denominacion AS deno_grupo,
	a.cod_subgrupo,
	a.denominacion AS deno_subgrupo

   FROM
	cimd01_clasificacion_tipo b,
	cimd01_clasificacion_grupo c,
	cimd01_clasificacion_subgrupo a
  WHERE a.cod_tipo = 1 AND a.cod_tipo = b.cod_tipo AND a.cod_tipo = c.cod_tipo AND a.cod_grupo = c.cod_grupo;

ALTER TABLE v_buscar_inmuebles OWNER TO sisap;

