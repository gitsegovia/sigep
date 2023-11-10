-- View: v_casd01_relacion_solicitantes

-- DROP VIEW v_casd01_relacion_solicitantes;

CREATE OR REPLACE VIEW v_casd01_relacion_solicitantes AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, a.apellidos_nombres, a.fecha_nacimiento, a.sexo, a.peso, a.estado_civil, a.estatura, a.grupo_sanguineo, a.cod_profesion, a.cod_oficio, a.cod_ambito, a.cod_zona, a.cod_vivienda, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.direccion_habitacion, a.telefonos_fijos, a.telefonos_movil, a.fecha_inscripcion, a.cod_tenencia_vivienda, a.anos_residencia, a.monto_alquiler_hipoteca, a.cod_mision, ( SELECT b.denominacion
           FROM cugd01_estados b
          WHERE b.cod_estado = a.cod_estado) AS denominacion_estado, ( SELECT c.denominacion
           FROM cugd01_municipios c
          WHERE c.cod_estado = a.cod_estado AND c.cod_municipio = a.cod_municipio) AS denominacion_municipio, ( SELECT d.denominacion
           FROM cugd01_parroquias d
          WHERE d.cod_estado = a.cod_estado AND d.cod_municipio = a.cod_municipio AND d.cod_parroquia = a.cod_parroquia) AS denominacion_parroquia, ( SELECT e.denominacion
           FROM cugd01_centros_poblados e
          WHERE e.cod_estado = a.cod_estado AND e.cod_municipio = a.cod_municipio AND e.cod_parroquia = a.cod_parroquia AND e.cod_centro = a.cod_centro_poblado) AS denominacion_centro, ( SELECT ccc.conocido
           FROM cugd01_municipios ccc
          WHERE ccc.cod_estado = a.cod_estado AND ccc.cod_municipio = a.cod_municipio) AS conocido, ( SELECT count(f.cedula_identidad) AS count
           FROM casd01_solicitud_ayuda f
          WHERE f.cedula_identidad = a.cedula_identidad) AS numero_solicitudes, ( SELECT count(g.cedula_identidad) AS count
           FROM casd01_ayudas_cuerpo g
          WHERE g.cedula_identidad = a.cedula_identidad) AS numero_ayudas, ( SELECT sum(l.monto_total) AS sum
           FROM casd01_ayudas_cuerpo l
          WHERE l.cedula_identidad = a.cedula_identidad) AS monto_ayudas, ( SELECT count(h.cedula_identidad) AS count
           FROM casd01_datos_familiares h
          WHERE h.cedula_identidad = a.cedula_identidad) AS numero_familiares, ( SELECT count(p.cedula_identidad) AS count
           FROM casd01_solicitud_ayuda p
          WHERE (p.cedula_identidad::character varying::text IN ( SELECT y.cedula
                   FROM casd01_datos_familiares y
                  WHERE y.cedula_identidad = a.cedula_identidad))) AS numero_solicitudes_familiares, ( SELECT count(pp.cedula_identidad) AS count
           FROM casd01_ayudas_cuerpo pp
          WHERE (pp.cedula_identidad::character varying::text IN ( SELECT yy.cedula
                   FROM casd01_datos_familiares yy
                  WHERE yy.cedula_identidad = a.cedula_identidad))) AS numero_ayudas_familiares, ( SELECT sum(zz.monto_total) AS sum
           FROM casd01_ayudas_cuerpo zz
          WHERE (zz.cedula_identidad::character varying::text IN ( SELECT yyy.cedula
                   FROM casd01_datos_familiares yyy
                  WHERE yyy.cedula_identidad = a.cedula_identidad))) AS monto_ayudas_familiares
   FROM casd01_datos_personales a;

ALTER TABLE v_casd01_relacion_solicitantes OWNER TO sisap;











-- View: v_casd01_sintesis_social

-- DROP VIEW v_casd01_sintesis_social;

CREATE OR REPLACE VIEW v_casd01_sintesis_social AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, a.apellidos_nombres, a.fecha_nacimiento, a.sexo, a.peso, a.estado_civil, a.estatura, a.grupo_sanguineo, a.cod_profesion, ( SELECT x.denominacion
           FROM cnmd06_profesiones x
          WHERE x.cod_profesion = a.cod_profesion) AS denominacion_profesion, a.cod_oficio, ( SELECT xx.denominacion
           FROM cnmd06_oficio xx
          WHERE xx.cod_oficio = a.cod_oficio) AS denominacion_oficio, a.cod_ambito, a.cod_zona, a.cod_vivienda, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.direccion_habitacion, a.telefonos_fijos, a.telefonos_movil, a.fecha_inscripcion, a.denominacion_estado, a.denominacion_municipio, a.denominacion_parroquia, a.denominacion_centro, a.conocido, a.numero_solicitudes, a.numero_ayudas, a.monto_ayudas, a.numero_familiares, a.numero_solicitudes_familiares, a.numero_ayudas_familiares, a.monto_ayudas_familiares, a.cod_tenencia_vivienda, a.anos_residencia, a.monto_alquiler_hipoteca, a.cod_mision, b.cedula AS cedula_familiar, b.cod_parentesco, ( SELECT c.denominacion
           FROM cnmd06_parentesco c
          WHERE c.cod_parentesco = b.cod_parentesco) AS denominacion_parentesco, b.apellidos_nombres AS apellidos_nombres_familiares, b.fecha_nacimiento AS fecha_nacimiento_familiar, b.sexo AS sexo_familiar, b.trabaja AS trabaja_familiar, b.estudia AS estudia_familiar
   FROM v_casd01_relacion_solicitantes a, casd01_datos_familiares b
  WHERE a.cedula_identidad = b.cedula_identidad;

ALTER TABLE v_casd01_sintesis_social OWNER TO sisap;













-- View: v_casd01_ubicacion_geografica

-- DROP VIEW v_casd01_ubicacion_geografica;

CREATE OR REPLACE VIEW v_casd01_ubicacion_geografica AS
(( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, 0 AS cod_municipio, 0 AS cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_estado AS denominacion, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas
   FROM v_casd01_relacion_solicitantes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.denominacion_estado
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, 0 AS cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_municipio AS denominacion, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas
   FROM v_casd01_relacion_solicitantes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.denominacion_municipio)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_parroquia AS denominacion, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas
   FROM v_casd01_relacion_solicitantes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.denominacion_parroquia)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_centro AS denominacion, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas
   FROM v_casd01_relacion_solicitantes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_centro;

ALTER TABLE v_casd01_ubicacion_geografica OWNER TO sisap;









-- View: v_casd01_ubicacion_geografica_rango

-- DROP VIEW v_casd01_ubicacion_geografica_rango;

CREATE OR REPLACE VIEW v_casd01_ubicacion_geografica_rango AS
(( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, 0 AS cod_municipio, 0 AS cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_estado AS denominacion, a.fecha_solicitud, count(a.numero_ocacion) AS numero_solicitudes, count(a.numero_documento_ayuda) AS numero_ayudas, sum(a.monto_total) AS monto_ayudas
   FROM v_casp01_relacion_solicitudes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.denominacion_estado, a.fecha_solicitud
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, 0 AS cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_municipio AS denominacion, a.fecha_solicitud, count(a.numero_ocacion) AS numero_solicitudes, count(a.numero_documento_ayuda) AS numero_ayudas, sum(a.monto_total) AS monto_ayudas
   FROM v_casp01_relacion_solicitudes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.denominacion_municipio, a.fecha_solicitud)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_parroquia AS denominacion, a.fecha_solicitud, count(a.numero_ocacion) AS numero_solicitudes, count(a.numero_documento_ayuda) AS numero_ayudas, sum(a.monto_total) AS monto_ayudas
   FROM v_casp01_relacion_solicitudes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.denominacion_parroquia, a.fecha_solicitud)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_centro AS denominacion, a.fecha_solicitud, count(a.numero_ocacion) AS numero_solicitudes, count(a.numero_documento_ayuda) AS numero_ayudas, sum(a.monto_total) AS monto_ayudas
   FROM v_casp01_relacion_solicitudes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_centro, a.fecha_solicitud;

ALTER TABLE v_casd01_ubicacion_geografica_rango OWNER TO sisap;








-- View: v_casd01_ubicacion_geografica_tipo_1

-- DROP VIEW v_casd01_ubicacion_geografica_tipo_1;

CREATE OR REPLACE VIEW v_casd01_ubicacion_geografica_tipo_1 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, a.apellidos_nombres, a.fecha_nacimiento, a.cod_ambito, a.cod_zona, a.cod_vivienda, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.direccion_habitacion, a.telefonos_fijos, a.telefonos_movil, a.fecha_inscripcion, ( SELECT b.denominacion
           FROM cugd01_estados b
          WHERE b.cod_estado = a.cod_estado) AS denominacion_estado, ( SELECT c.denominacion
           FROM cugd01_municipios c
          WHERE c.cod_estado = a.cod_estado AND c.cod_municipio = a.cod_municipio) AS denominacion_municipio, ( SELECT d.denominacion
           FROM cugd01_parroquias d
          WHERE d.cod_estado = a.cod_estado AND d.cod_municipio = a.cod_municipio AND d.cod_parroquia = a.cod_parroquia) AS denominacion_parroquia, ( SELECT e.denominacion
           FROM cugd01_centros_poblados e
          WHERE e.cod_estado = a.cod_estado AND e.cod_municipio = a.cod_municipio AND e.cod_parroquia = a.cod_parroquia AND e.cod_centro = a.cod_centro_poblado) AS denominacion_centro, bbb.cod_tipo_ayuda, ( SELECT count(f.cedula_identidad) AS count
           FROM casd01_solicitud_ayuda f
          WHERE f.cedula_identidad = a.cedula_identidad AND f.cod_tipo_ayuda = bbb.cod_tipo_ayuda) AS numero_solicitudes, ( SELECT count(g.cedula_identidad) AS count
           FROM casd01_ayudas_cuerpo g
          WHERE g.cedula_identidad = a.cedula_identidad AND g.cod_tipo_ayuda = bbb.cod_tipo_ayuda) AS numero_ayudas, ( SELECT sum(l.monto_total) AS sum
           FROM casd01_ayudas_cuerpo l
          WHERE l.cedula_identidad = a.cedula_identidad AND l.cod_tipo_ayuda = bbb.cod_tipo_ayuda) AS monto_ayudas, ( SELECT k.denominacion
           FROM casd01_tipo_ayuda k
          WHERE k.cod_tipo_ayuda = bbb.cod_tipo_ayuda) AS denominacion_ayuda
   FROM casd01_datos_personales a, casd01_solicitud_ayuda bbb
  WHERE a.cedula_identidad = bbb.cedula_identidad
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, a.apellidos_nombres, a.fecha_nacimiento, a.cod_ambito, a.cod_zona, a.cod_vivienda, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.direccion_habitacion, a.telefonos_fijos, a.telefonos_movil, a.fecha_inscripcion, bbb.cod_tipo_ayuda;

ALTER TABLE v_casd01_ubicacion_geografica_tipo_1 OWNER TO sisap;







-- View: v_casd01_ubicacion_geografica_tipo_2

-- DROP VIEW v_casd01_ubicacion_geografica_tipo_2;

CREATE OR REPLACE VIEW v_casd01_ubicacion_geografica_tipo_2 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_estado, a.denominacion_municipio, a.denominacion_parroquia, a.denominacion_centro, a.cod_tipo_ayuda, quitar_acentos(a.denominacion_ayuda::text) AS denominacion_ayuda, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas
   FROM v_casd01_ubicacion_geografica_tipo_1 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_estado, a.denominacion_municipio, a.denominacion_parroquia, a.denominacion_centro, a.cod_tipo_ayuda, quitar_acentos(a.denominacion_ayuda::text)
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_estado, a.denominacion_municipio, a.denominacion_parroquia, a.denominacion_centro;

ALTER TABLE v_casd01_ubicacion_geografica_tipo_2 OWNER TO sisap;









-- View: v_casd01_ubicacion_geografica_tipo_rango

-- DROP VIEW v_casd01_ubicacion_geografica_tipo_rango;

CREATE OR REPLACE VIEW v_casd01_ubicacion_geografica_tipo_rango AS
(( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, 0 AS cod_municipio, 0 AS cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_estado AS denominacion, a.tipo_ayuda, a.fecha_solicitud, count(a.numero_ocacion) AS numero_solicitudes, count(a.numero_documento_ayuda) AS numero_ayudas, sum(a.monto_total) AS monto_ayudas
   FROM v_casp01_relacion_solicitudes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.denominacion_estado, a.tipo_ayuda, a.fecha_solicitud
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, 0 AS cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_municipio AS denominacion, a.tipo_ayuda, a.fecha_solicitud, count(a.numero_ocacion) AS numero_solicitudes, count(a.numero_documento_ayuda) AS numero_ayudas, sum(a.monto_total) AS monto_ayudas
   FROM v_casp01_relacion_solicitudes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.denominacion_municipio, a.tipo_ayuda, a.fecha_solicitud)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_parroquia AS denominacion, a.tipo_ayuda, a.fecha_solicitud, count(a.numero_ocacion) AS numero_solicitudes, count(a.numero_documento_ayuda) AS numero_ayudas, sum(a.monto_total) AS monto_ayudas
   FROM v_casp01_relacion_solicitudes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.denominacion_parroquia, a.tipo_ayuda, a.fecha_solicitud)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_centro AS denominacion, a.tipo_ayuda, a.fecha_solicitud, count(a.numero_ocacion) AS numero_solicitudes, count(a.numero_documento_ayuda) AS numero_ayudas, sum(a.monto_total) AS monto_ayudas
   FROM v_casp01_relacion_solicitudes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_centro, a.tipo_ayuda, a.fecha_solicitud;

ALTER TABLE v_casd01_ubicacion_geografica_tipo_rango OWNER TO sisap;












-- View: v_casp01_relacion_solicitudes

-- DROP VIEW v_casp01_relacion_solicitudes;

CREATE OR REPLACE VIEW v_casp01_relacion_solicitudes AS
 SELECT
 a.cod_presi,
 a.cod_entidad,
 a.cod_tipo_inst,
 a.cod_inst,
 a.cod_dep,
 a.cedula_identidad,
 ( SELECT b.apellidos_nombres FROM casd01_datos_personales b WHERE b.cedula_identidad = a.cedula_identidad) AS apellidos_nombres,
  ( SELECT b.sexo FROM casd01_datos_personales b WHERE b.cedula_identidad = a.cedula_identidad) AS sexo,
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
 ( SELECT ww.denominacion_centro FROM v_casd01_relacion_solicitantes ww WHERE ww.cedula_identidad = a.cedula_identidad) AS denominacion_centro, a.username AS username_promotor_solicitud, a.cedula_usuario AS cedula_promotor_solicitud, a.nombre_usuario AS nombre_promotor_solicitud,
 ( SELECT uu.username FROM casd01_evaluacion_ayuda uu WHERE a.cod_presi = uu.cod_presi AND a.cod_entidad = uu.cod_entidad AND a.cod_tipo_inst = uu.cod_tipo_inst AND a.cod_inst = uu.cod_inst AND a.cod_dep = uu.cod_dep AND a.cedula_identidad = uu.cedula_identidad AND a.cod_tipo_ayuda = uu.cod_tipo_ayuda AND a.numero_ocacion = uu.numero_ocacion AND a.numero_documento_evaluacion = uu.numero_documento_evaluacion) AS username_promotor_evaluacion,
 ( SELECT uuu.cedula_usuario FROM casd01_evaluacion_ayuda uuu WHERE a.cod_presi = uuu.cod_presi AND a.cod_entidad = uuu.cod_entidad AND a.cod_tipo_inst = uuu.cod_tipo_inst AND a.cod_inst = uuu.cod_inst AND a.cod_dep = uuu.cod_dep AND a.cedula_identidad = uuu.cedula_identidad AND a.cod_tipo_ayuda = uuu.cod_tipo_ayuda AND a.numero_ocacion = uuu.numero_ocacion AND a.numero_documento_evaluacion = uuu.numero_documento_evaluacion) AS cedula_promotor_evaluacion,
 ( SELECT uuuu.nombre_usuario FROM casd01_evaluacion_ayuda uuuu WHERE a.cod_presi = uuuu.cod_presi AND a.cod_entidad = uuuu.cod_entidad AND a.cod_tipo_inst = uuuu.cod_tipo_inst AND a.cod_inst = uuuu.cod_inst AND a.cod_dep = uuuu.cod_dep AND a.cedula_identidad = uuuu.cedula_identidad AND a.cod_tipo_ayuda = uuuu.cod_tipo_ayuda AND a.numero_ocacion = uuuu.numero_ocacion AND a.numero_documento_evaluacion = uuuu.numero_documento_evaluacion) AS nombre_promotor_evaluacion,
 ( SELECT eeee.username FROM casd01_ayudas_cuerpo eeee WHERE a.cod_presi = eeee.cod_presi AND a.cod_entidad = eeee.cod_entidad AND a.cod_tipo_inst = eeee.cod_tipo_inst AND a.cod_inst = eeee.cod_inst AND a.cod_dep = eeee.cod_dep AND a.cedula_identidad = eeee.cedula_identidad AND a.cod_tipo_ayuda = eeee.cod_tipo_ayuda AND a.numero_ocacion = eeee.numero_ocacion AND a.numero_documento_evaluacion = eeee.numero_documento_evaluacion AND a.numero_documento_ayuda = eeee.numero_documento_ayuda) AS username_promotor_ayuda,
 ( SELECT ee.cedula_usuario FROM casd01_ayudas_cuerpo ee WHERE a.cod_presi = ee.cod_presi AND a.cod_entidad = ee.cod_entidad AND a.cod_tipo_inst = ee.cod_tipo_inst AND a.cod_inst = ee.cod_inst AND a.cod_dep = ee.cod_dep AND a.cedula_identidad = ee.cedula_identidad AND a.cod_tipo_ayuda = ee.cod_tipo_ayuda AND a.numero_ocacion = ee.numero_ocacion AND a.numero_documento_evaluacion = ee.numero_documento_evaluacion AND a.numero_documento_ayuda = ee.numero_documento_ayuda) AS cedula_promotor_ayuda,
 ( SELECT eee.nombre_usuario FROM casd01_ayudas_cuerpo eee WHERE a.cod_presi = eee.cod_presi AND a.cod_entidad = eee.cod_entidad AND a.cod_tipo_inst = eee.cod_tipo_inst AND a.cod_inst = eee.cod_inst AND a.cod_dep = eee.cod_dep AND a.cedula_identidad = eee.cedula_identidad AND a.cod_tipo_ayuda = eee.cod_tipo_ayuda AND a.numero_ocacion = eee.numero_ocacion AND a.numero_documento_evaluacion = eee.numero_documento_evaluacion AND a.numero_documento_ayuda = eee.numero_documento_ayuda) AS nombre_promotor_ayuda
   FROM casd01_solicitud_ayuda a;

ALTER TABLE v_casp01_relacion_solicitudes OWNER TO sisap;






-- View: v_casp01_solicitudes_ayudas_sexo

-- DROP VIEW v_casp01_solicitudes_ayudas_sexo;

CREATE OR REPLACE VIEW v_casp01_solicitudes_ayudas_sexo AS
select
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.fecha_solicitud,
a.fecha_ayuda,
(select count(b.numero_ocacion) as solicitudes_f from v_casp01_relacion_solicitudes b where
	b.cod_presi=a.cod_presi and
	b.cod_entidad=a.cod_entidad and
	b.cod_tipo_inst=a.cod_tipo_inst and
	b.cod_inst=a.cod_inst and
	b.cod_dep=a.cod_dep and b.sexo=1
	group by
	b.cod_presi,
	b.cod_entidad,
	b.cod_tipo_inst,
	b.cod_inst,
	b.cod_dep) as solicitudes_f,
(select count(b.numero_ocacion) as solicitudes_m from v_casp01_relacion_solicitudes b where
	b.cod_presi=a.cod_presi and
	b.cod_entidad=a.cod_entidad and
	b.cod_tipo_inst=a.cod_tipo_inst and
	b.cod_inst=a.cod_inst and
	b.cod_dep=a.cod_dep and b.sexo=2
	group by
	b.cod_presi,
	b.cod_entidad,
	b.cod_tipo_inst,
	b.cod_inst,
	b.cod_dep) as solicitudes_m,
(select count(b.numero_documento_ayuda) as ayudas_f from v_casp01_relacion_solicitudes b where
	b.cod_presi=a.cod_presi and
	b.cod_entidad=a.cod_entidad and
	b.cod_tipo_inst=a.cod_tipo_inst and
	b.cod_inst=a.cod_inst and
	b.cod_dep=a.cod_dep and b.sexo=1
	group by
	b.cod_presi,
	b.cod_entidad,
	b.cod_tipo_inst,
	b.cod_inst,
	b.cod_dep) as ayudas_f,
(select count(b.numero_documento_ayuda) as ayudas_m from v_casp01_relacion_solicitudes b where
	b.cod_presi=a.cod_presi and
	b.cod_entidad=a.cod_entidad and
	b.cod_tipo_inst=a.cod_tipo_inst and
	b.cod_inst=a.cod_inst and
	b.cod_dep=a.cod_dep and b.sexo=2
	group by
	b.cod_presi,
	b.cod_entidad,
	b.cod_tipo_inst,
	b.cod_inst,
	b.cod_dep) as ayudas_m,
(select sum(b.monto_total) as monto_ayudas_m from v_casp01_relacion_solicitudes b where
	b.cod_presi=a.cod_presi and
	b.cod_entidad=a.cod_entidad and
	b.cod_tipo_inst=a.cod_tipo_inst and
	b.cod_inst=a.cod_inst and
	b.cod_dep=a.cod_dep and b.sexo=2
	group by
	b.cod_presi,
	b.cod_entidad,
	b.cod_tipo_inst,
	b.cod_inst,
	b.cod_dep) as monto_ayudas_m,
(select sum(b.monto_total) as monto_ayudas_f from v_casp01_relacion_solicitudes b where
	b.cod_presi=a.cod_presi and
	b.cod_entidad=a.cod_entidad and
	b.cod_tipo_inst=a.cod_tipo_inst and
	b.cod_inst=a.cod_inst and
	b.cod_dep=a.cod_dep and b.sexo=1
	group by
	b.cod_presi,
	b.cod_entidad,
	b.cod_tipo_inst,
	b.cod_inst,
	b.cod_dep) as monto_ayudas_f
from v_casp01_relacion_solicitudes a
group by
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep;
ALTER TABLE v_casp01_solicitudes_ayudas_sexo OWNER TO sisap;