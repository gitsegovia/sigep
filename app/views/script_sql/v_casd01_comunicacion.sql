-- View: v_casd01_comunicacion

-- DROP VIEW v_casd01_comunicacion;

CREATE OR REPLACE VIEW v_casd01_comunicacion AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, c.apellidos_nombres
   FROM casd01_evaluacion_ayuda a, v_casd01_relacion_solicitantes c
  WHERE a.cedula_identidad = c.cedula_identidad AND a.aprobado <> 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, c.apellidos_nombres;

ALTER TABLE v_casd01_comunicacion OWNER TO sisap;

