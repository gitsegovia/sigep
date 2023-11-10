-- View: v_cnmd06_datos_familiares

-- DROP VIEW v_cnmd06_datos_familiares;

CREATE OR REPLACE VIEW v_cnmd06_datos_familiares AS
 SELECT a.cedula, b.primer_apellido, b.segundo_apellido, b.primer_nombre, b.segundo_nombre, a.consecutivo, a.cod_parentesco, c.denominacion AS deno_parentesco, a.nombres_apellidos, a.numero_cedula, a.fecha_nacimiento, a.sexo, a.afiliado, a.cod_guarderia, a.costo_guarderia
   FROM cnmd06_datos_familiares a, cnmd06_datos_personales b, cnmd06_parentesco c
  WHERE a.cedula = b.cedula_identidad AND a.cod_parentesco = c.cod_parentesco
  ORDER BY a.cedula;

ALTER TABLE v_cnmd06_datos_familiares OWNER TO sisap;