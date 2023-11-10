-- View: v_cnmd06_datos_formacion_profesional

-- DROP VIEW v_cnmd06_datos_formacion_profesional;

CREATE OR REPLACE VIEW v_cnmd06_datos_formacion_profesional AS
 SELECT a.cedula, b.primer_apellido, b.segundo_apellido, b.primer_nombre, b.segundo_nombre, a.consecutivo, a.cod_curso, c.denominacion AS deno_curso, a.cod_institucion, d.denominacion AS deno_institucion, a.duracion, a.desde, a.hasta, a.observaciones
   FROM cnmd06_datos_formacion_profesional a, cnmd06_datos_personales b, cnmd06_cursos c, cnmd06_instituto_educativo d
  WHERE a.cedula = b.cedula_identidad AND a.cod_curso = c.cod_curso AND a.cod_institucion = d.cod_institucion
  ORDER BY a.cedula;

ALTER TABLE v_cnmd06_datos_formacion_profesional OWNER TO sisap;