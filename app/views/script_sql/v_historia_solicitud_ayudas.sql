
/////////////////////////ULTIMA VISTA/////////////////////////////////////

 DROP VIEW v_historia_solicitud_ayudas;

CREATE OR REPLACE VIEW v_historia_solicitud_ayudas AS
 SELECT b.cedula_identidad, b.cod_tipo_ayuda, b.numero_ocacion, b.fecha_solicitud,b.numero_documento_evaluacion,b.numero_documento_ayuda, ( SELECT t.fecha_ayuda
           FROM casd01_ayudas_cuerpo t
          WHERE t.cedula_identidad = b.cedula_identidad AND t.cod_tipo_ayuda = b.cod_tipo_ayuda AND t.numero_ocacion = b.numero_ocacion) AS fecha_ayuda, ( SELECT tt.monto_total
           FROM casd01_ayudas_cuerpo tt
          WHERE tt.cedula_identidad = b.cedula_identidad AND tt.cod_tipo_ayuda = b.cod_tipo_ayuda AND tt.numero_ocacion = b.numero_ocacion) AS monto_total, b.cod_presi AS cod_presi_solicitud, b.cod_entidad AS cod_entidad_solicitud, b.cod_tipo_inst AS cod_tipo_inst_solicitud, b.cod_inst AS cod_inst_solicitud, ( SELECT z.denominacion
           FROM cugd02_institucion z
          WHERE z.cod_tipo_institucion = b.cod_tipo_inst AND z.cod_institucion = b.cod_inst) AS denominacion_institucion, b.cod_dep AS cod_dep_solicitud, ( SELECT zz.denominacion
           FROM cugd02_dependencias zz
          WHERE zz.cod_tipo_institucion = b.cod_tipo_inst AND zz.cod_institucion = b.cod_inst AND zz.cod_dependencia = b.cod_dep) AS denominacion_dependencia, (((b.cod_presi::text || b.cod_entidad::text) || b.cod_tipo_inst::text) || b.cod_inst::text) || b.cod_dep::text AS combinacion_codigos, ( SELECT l.cod_presi
           FROM casd01_ayudas_cuerpo l
          WHERE l.cedula_identidad = b.cedula_identidad AND l.cod_tipo_ayuda = b.cod_tipo_ayuda AND l.numero_ocacion = b.numero_ocacion) AS cod_presi_ayuda, ( SELECT ll.cod_entidad
           FROM casd01_ayudas_cuerpo ll
          WHERE ll.cedula_identidad = b.cedula_identidad AND ll.cod_tipo_ayuda = b.cod_tipo_ayuda AND ll.numero_ocacion = b.numero_ocacion) AS cod_pentidad_ayuda, ( SELECT x.cod_tipo_inst
           FROM casd01_ayudas_cuerpo x
          WHERE x.cedula_identidad = b.cedula_identidad AND x.cod_tipo_ayuda = b.cod_tipo_ayuda AND x.numero_ocacion = b.numero_ocacion) AS cod_tipo_inst_ayuda, ( SELECT xx.cod_inst
           FROM casd01_ayudas_cuerpo xx
          WHERE xx.cedula_identidad = b.cedula_identidad AND xx.cod_tipo_ayuda = b.cod_tipo_ayuda AND xx.numero_ocacion = b.numero_ocacion) AS cod_inst_ayuda, ( SELECT p.cod_dep
           FROM casd01_ayudas_cuerpo p
          WHERE p.cedula_identidad = b.cedula_identidad AND p.cod_tipo_ayuda = b.cod_tipo_ayuda AND p.numero_ocacion = b.numero_ocacion) AS cod_dep_ayuda,
          (SELECT j.denominacion from casd01_tipo_ayuda j where j.cod_tipo_ayuda=b.cod_tipo_ayuda) as denominacion_ayuda
   FROM casd01_solicitud_ayuda b;

ALTER TABLE v_historia_solicitud_ayudas OWNER TO sisap;
