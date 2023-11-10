 DROP VIEW v_casp01_relacion_solicitudes;

CREATE OR REPLACE VIEW v_casp01_relacion_solicitudes AS
 SELECT
 a.cod_presi,
 a.cod_entidad,
 a.cod_tipo_inst,
 a.cod_inst,
 a.cod_dep,
 a.cedula_identidad,
 ( SELECT b.apellidos_nombres FROM casd01_datos_personales b WHERE b.cedula_identidad = a.cedula_identidad) AS apellidos_nombres,
 ( SELECT bb.telefonos_fijos FROM casd01_datos_personales bb WHERE bb.cedula_identidad = a.cedula_identidad) AS telefonos_fijos,
 ( SELECT bbb.telefonos_movil FROM casd01_datos_personales bbb WHERE bbb.cedula_identidad = a.cedula_identidad) AS telefonos_movil,
 a.cod_tipo_ayuda,
 a.numero_ocacion,
 a.ayuda_solicitada,
 a.fecha_solicitud,
 ( SELECT c.denominacion FROM casd01_tipo_ayuda c WHERE a.cod_tipo_ayuda = c.cod_tipo_ayuda) AS tipo_ayuda, a.numero_documento_evaluacion, a.numero_documento_ayuda,
 ( SELECT aaa.evaluacion FROM casd01_evaluacion_ayuda aaa WHERE a.cod_presi = aaa.cod_presi AND a.cod_entidad = aaa.cod_entidad AND a.cod_tipo_inst = aaa.cod_tipo_inst AND a.cod_inst = aaa.cod_inst AND a.cod_dep = aaa.cod_dep AND a.cedula_identidad = aaa.cedula_identidad AND a.cod_tipo_ayuda = aaa.cod_tipo_ayuda AND a.numero_ocacion = aaa.numero_ocacion AND a.numero_documento_evaluacion = aaa.numero_documento_evaluacion) AS evaluacion,
 ( SELECT aa.aprobado FROM casd01_evaluacion_ayuda aa WHERE a.cod_presi = aa.cod_presi AND a.cod_entidad = aa.cod_entidad AND a.cod_tipo_inst = aa.cod_tipo_inst AND a.cod_inst = aa.cod_inst AND a.cod_dep = aa.cod_dep AND a.cedula_identidad = aa.cedula_identidad AND a.cod_tipo_ayuda = aa.cod_tipo_ayuda AND a.numero_ocacion = aa.numero_ocacion AND a.numero_documento_evaluacion = aa.numero_documento_evaluacion) AS aprobacion,
 ( SELECT d.fecha_ayuda FROM casd01_ayudas_cuerpo d WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.cedula_identidad = d.cedula_identidad AND a.cod_tipo_ayuda = d.cod_tipo_ayuda AND a.numero_ocacion = d.numero_ocacion AND a.numero_documento_evaluacion = d.numero_documento_evaluacion AND a.numero_documento_ayuda = d.numero_documento_ayuda) AS fecha_ayuda,
 ( SELECT e.monto_total FROM casd01_ayudas_cuerpo e WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND a.cedula_identidad = e.cedula_identidad AND a.cod_tipo_ayuda = e.cod_tipo_ayuda AND a.numero_ocacion = e.numero_ocacion AND a.numero_documento_evaluacion = e.numero_documento_evaluacion AND a.numero_documento_ayuda = e.numero_documento_ayuda) AS monto_total,
 ( SELECT z.cod_estado FROM casd01_datos_personales z WHERE z.cedula_identidad = a.cedula_identidad) AS cod_estado,
 ( SELECT zz.denominacion_estado FROM v_casd01_relacion_solicitantes zz WHERE zz.cedula_identidad = a.cedula_identidad) AS denominacion_estado,
 ( SELECT y.cod_municipio FROM casd01_datos_personales y WHERE y.cedula_identidad = a.cedula_identidad) AS cod_municipio,
 ( SELECT yy.denominacion_municipio FROM v_casd01_relacion_solicitantes yy WHERE yy.cedula_identidad = a.cedula_identidad) AS denominacion_municipio,
 ( SELECT x.cod_parroquia FROM casd01_datos_personales x WHERE x.cedula_identidad = a.cedula_identidad) AS cod_parroquia,
 ( SELECT xx.denominacion_parroquia FROM v_casd01_relacion_solicitantes xx WHERE xx.cedula_identidad = a.cedula_identidad) AS denominacion_parroquia,
 ( SELECT w.cod_centro_poblado FROM casd01_datos_personales w WHERE w.cedula_identidad = a.cedula_identidad) AS cod_centro_poblado,
 ( SELECT ww.denominacion_centro FROM v_casd01_relacion_solicitantes ww WHERE ww.cedula_identidad = a.cedula_identidad) AS denominacion_centro,
  a.username as usewrname_promotor_solicitud,
  a.cedula_usuario as cedula_promotor_solicitud,
  a.nombre_usuario as nombre_promotor_solicitud,
  ( SELECT uu.username FROM casd01_evaluacion_ayuda uu WHERE a.cod_presi = uu.cod_presi AND a.cod_entidad = uu.cod_entidad AND a.cod_tipo_inst = uu.cod_tipo_inst AND a.cod_inst = uu.cod_inst AND a.cod_dep = uu.cod_dep AND a.cedula_identidad = uu.cedula_identidad AND a.cod_tipo_ayuda = uu.cod_tipo_ayuda AND a.numero_ocacion = uu.numero_ocacion AND a.numero_documento_evaluacion = uu.numero_documento_evaluacion) AS username_promotor_evaluacion,
  ( SELECT uuu.cedula_usuario FROM casd01_evaluacion_ayuda uuu WHERE a.cod_presi = uuu.cod_presi AND a.cod_entidad = uuu.cod_entidad AND a.cod_tipo_inst = uuu.cod_tipo_inst AND a.cod_inst = uuu.cod_inst AND a.cod_dep = uuu.cod_dep AND a.cedula_identidad = uuu.cedula_identidad AND a.cod_tipo_ayuda = uuu.cod_tipo_ayuda AND a.numero_ocacion = uuu.numero_ocacion AND a.numero_documento_evaluacion = uuu.numero_documento_evaluacion) AS cedula_promotor_evaluacion,
  ( SELECT uuuu.nombre_usuario FROM casd01_evaluacion_ayuda uuuu WHERE a.cod_presi = uuuu.cod_presi AND a.cod_entidad = uuuu.cod_entidad AND a.cod_tipo_inst = uuuu.cod_tipo_inst AND a.cod_inst = uuuu.cod_inst AND a.cod_dep = uuuu.cod_dep AND a.cedula_identidad = uuuu.cedula_identidad AND a.cod_tipo_ayuda = uuuu.cod_tipo_ayuda AND a.numero_ocacion = uuuu.numero_ocacion AND a.numero_documento_evaluacion = uuuu.numero_documento_evaluacion) AS nombre_promotor_evaluacion,
  ( SELECT eeee.username FROM casd01_ayudas_cuerpo eeee WHERE a.cod_presi = eeee.cod_presi AND a.cod_entidad = eeee.cod_entidad AND a.cod_tipo_inst = eeee.cod_tipo_inst AND a.cod_inst = eeee.cod_inst AND a.cod_dep = eeee.cod_dep AND a.cedula_identidad = eeee.cedula_identidad AND a.cod_tipo_ayuda = eeee.cod_tipo_ayuda AND a.numero_ocacion = eeee.numero_ocacion AND a.numero_documento_evaluacion = eeee.numero_documento_evaluacion AND a.numero_documento_ayuda = eeee.numero_documento_ayuda) AS username_promotor_ayuda,
  ( SELECT ee.cedula_usuario FROM casd01_ayudas_cuerpo ee WHERE a.cod_presi = ee.cod_presi AND a.cod_entidad = ee.cod_entidad AND a.cod_tipo_inst = ee.cod_tipo_inst AND a.cod_inst = ee.cod_inst AND a.cod_dep = ee.cod_dep AND a.cedula_identidad = ee.cedula_identidad AND a.cod_tipo_ayuda = ee.cod_tipo_ayuda AND a.numero_ocacion = ee.numero_ocacion AND a.numero_documento_evaluacion = ee.numero_documento_evaluacion AND a.numero_documento_ayuda = ee.numero_documento_ayuda) AS cedula_promotor_ayuda,
  ( SELECT eee.nombre_usuario FROM casd01_ayudas_cuerpo eee WHERE a.cod_presi = eee.cod_presi AND a.cod_entidad = eee.cod_entidad AND a.cod_tipo_inst = eee.cod_tipo_inst AND a.cod_inst = eee.cod_inst AND a.cod_dep = eee.cod_dep AND a.cedula_identidad = eee.cedula_identidad AND a.cod_tipo_ayuda = eee.cod_tipo_ayuda AND a.numero_ocacion = eee.numero_ocacion AND a.numero_documento_evaluacion = eee.numero_documento_evaluacion AND a.numero_documento_ayuda = eee.numero_documento_ayuda) AS nombre_promotor_ayuda
 FROM casd01_solicitud_ayuda a;

ALTER TABLE v_casp01_relacion_solicitudes OWNER TO postgres;