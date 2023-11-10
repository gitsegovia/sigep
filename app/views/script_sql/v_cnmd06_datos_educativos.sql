-- View: v_cnmd06_datos_educativos

-- DROP VIEW v_cnmd06_datos_educativos;

CREATE OR REPLACE VIEW v_cnmd06_datos_educativos AS

 SELECT
			 a.cedula,
			 b.primer_apellido,
			 b.segundo_apellido,
			 b.primer_nombre,
			 b.segundo_nombre,
			 a.consecutivo,
			 a.cod_nivel_educacion,
			 c.denominacion AS deno_nivel,
			 a.cod_institucion,
			 i.denominacion AS deno_institucion,
			 a.cod_republica,
			 d.denominacion AS deno_pais,
			 a.cod_estado, e.denominacion AS deno_estado,
			 a.cod_municipio,
			 f.denominacion AS deno_municipio,
			 a.cod_parroquia,
			 g.denominacion AS deno_parroquia,
			 a.cod_centro,
			 h.denominacion AS deno_centro,
			 a.fecha_inicio,
			 a.fecha_culminacion,
			 a.observaciones

   FROM

		   cnmd06_datos_educativos a,
		   cnmd06_datos_personales b,
		   cnmd06_nivel_educacion c,
		   cugd01_republica d,
		   cugd01_estados e,
		   cugd01_municipios f,
		   cugd01_parroquias g,
		   cugd01_centros_poblados h,
		   cnmd06_instituto_educativo i

  WHERE

  a.cedula = b.cedula_identidad AND
  a.cod_nivel_educacion = c.cod_nivel_educativo AND
  a.cod_institucion = i.cod_institucion AND
  a.cod_republica = d.cod_republica AND
  a.cod_republica = e.cod_republica AND
  a.cod_estado = e.cod_estado AND
  a.cod_republica = f.cod_republica AND
  a.cod_estado = f.cod_estado AND
  a.cod_municipio = f.cod_municipio AND
  a.cod_republica = g.cod_republica AND
  a.cod_estado = g.cod_estado AND
  a.cod_municipio = g.cod_municipio AND
  a.cod_parroquia = g.cod_parroquia AND
  a.cod_republica = h.cod_republica AND
  a.cod_estado = h.cod_estado AND
  a.cod_municipio = h.cod_municipio AND
  a.cod_parroquia = h.cod_parroquia AND
  a.cod_centro = h.cod_centro


  ORDER BY a.cedula;

ALTER TABLE v_cnmd06_datos_educativos OWNER TO sisap;