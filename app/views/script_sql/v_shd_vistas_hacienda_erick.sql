-- View: v_shd001_contribuyentes_e_impuestos

-- DROP VIEW v_shd001_contribuyentes_e_impuestos;

CREATE OR REPLACE VIEW v_shd001_contribuyentes_e_impuestos AS
((((( SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 1::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 1) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd100_patente b, shd100_solicitud c
  WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.numero_solicitud::text = c.numero_solicitud::text AND b.numero_patente::text = c.numero_patente::text AND a.rif_cedula::text = c.rif_cedula::text
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 2::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 2) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd200_vehiculos b
  WHERE a.rif_cedula::text = b.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual_general AS monto_mensual, 3::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 3) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd300_propaganda b
  WHERE a.rif_cedula::text = b.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 4::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 4) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd400_propiedad b
  WHERE a.rif_cedula::text = b.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 5::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 5) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd500_aseo_domiciliario b
  WHERE a.rif_cedula::text = b.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 6::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 6) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd600_aprobacion_arrendamiento b, shd600_solicitud_arrendamiento c
  WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.numero_solicitud::text = c.numero_solicitud::text AND a.rif_cedula::text = c.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 7::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 7) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd700_credito_vivienda b
  WHERE a.rif_cedula::text = b.rif_cedula::text;

ALTER TABLE v_shd001_contribuyentes_e_impuestos OWNER TO sisap;

