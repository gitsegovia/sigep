-- View: v_cnmd06_datos_registro_titulo

-- DROP VIEW v_cnmd06_datos_registro_titulo;

CREATE OR REPLACE VIEW v_cnmd06_datos_registro_titulo AS
 SELECT a.cedula, b.primer_apellido, b.segundo_apellido, b.primer_nombre, b.segundo_nombre, a.consecutivo, a.cod_profesion, c.denominacion AS deno_profesion, a.numero_registro, a.tomo, a.folios, a.fecha_registro, a.cod_colegio, e.denominacion AS deno_colegio, a.numero_colegio, a.cod_especialidad, d.denominacion AS deno_especialidad
   FROM cnmd06_datos_registro_titulo a, cnmd06_datos_personales b, cnmd06_profesiones c, cnmd06_especialidades d, cnmd06_colegio_profesional e
  WHERE a.cedula = b.cedula_identidad AND a.cod_profesion = c.cod_profesion AND a.cod_profesion = d.cod_profesion AND a.cod_especialidad = d.cod_especialidad AND a.cod_colegio = e.cod_colegio
  ORDER BY a.cedula;

ALTER TABLE v_cnmd06_datos_registro_titulo OWNER TO sisap;