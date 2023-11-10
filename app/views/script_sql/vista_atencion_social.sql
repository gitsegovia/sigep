-- View: v_casd01_relacion_solicitantes

 DROP VIEW v_casd01_relacion_solicitantes;

CREATE OR REPLACE VIEW v_casd01_relacion_solicitantes AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, a.apellidos_nombres, a.fecha_nacimiento, a.cod_ambito, a.cod_zona, a.cod_vivienda, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.direccion_habitacion, a.telefonos_fijos, a.telefonos_movil, a.fecha_inscripcion, ( SELECT b.denominacion
           FROM cugd01_estados b
          WHERE b.cod_estado = a.cod_estado) AS denominacion_estado, ( SELECT c.denominacion
           FROM cugd01_municipios c
          WHERE c.cod_estado = a.cod_estado AND c.cod_municipio = a.cod_municipio) AS denominacion_municipio, ( SELECT d.denominacion
           FROM cugd01_parroquias d
          WHERE d.cod_estado = a.cod_estado AND d.cod_municipio = a.cod_municipio AND d.cod_parroquia = a.cod_parroquia) AS denominacion_parroquia, ( SELECT e.denominacion
           FROM cugd01_centros_poblados e
          WHERE e.cod_estado = a.cod_estado AND e.cod_municipio = a.cod_municipio AND e.cod_parroquia = a.cod_parroquia AND e.cod_centro = a.cod_centro_poblado) AS denominacion_centro, ( SELECT count(f.cedula_identidad) AS count
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





--/////////////////////////////////////////////////////////////////////////////////////


-- View: v_casd01_ubicacion_geografica

 DROP VIEW v_casd01_ubicacion_geografica;

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

--/////////////////////////////////////////////////////////////////////////////////////////

-- View: v_casd01_ubicacion_geografica_tipo_1

 DROP VIEW v_casd01_ubicacion_geografica_tipo_1;

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




--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



-- View: v_casd01_ubicacion_geografica_tipo_2

 DROP VIEW v_casd01_ubicacion_geografica_tipo_2;

CREATE OR REPLACE VIEW v_casd01_ubicacion_geografica_tipo_2 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_estado, a.denominacion_municipio, a.denominacion_parroquia, a.denominacion_centro, a.cod_tipo_ayuda, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas, a.denominacion_ayuda
   FROM v_casd01_ubicacion_geografica_tipo_1 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_estado, a.denominacion_municipio, a.denominacion_parroquia, a.denominacion_centro, a.cod_tipo_ayuda, a.denominacion_ayuda
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_estado, a.denominacion_municipio, a.denominacion_parroquia, a.denominacion_centro;

ALTER TABLE v_casd01_ubicacion_geografica_tipo_2 OWNER TO sisap;

--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



-- View: v_casp01_relacion_solicitudes

 DROP VIEW v_casp01_relacion_solicitudes;

CREATE OR REPLACE VIEW v_casp01_relacion_solicitudes AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, ( SELECT b.apellidos_nombres
           FROM casd01_datos_personales b
          WHERE b.cedula_identidad = a.cedula_identidad) AS apellidos_nombres, a.cod_tipo_ayuda, a.numero_ocacion, a.ayuda_solicitada, a.fecha_solicitud, ( SELECT c.denominacion
           FROM casd01_tipo_ayuda c
          WHERE a.cod_tipo_ayuda = c.cod_tipo_ayuda) AS tipo_ayuda, a.numero_documento_evaluacion, a.numero_documento_ayuda, ( SELECT aaa.evaluacion
           FROM casd01_evaluacion_ayuda aaa
          WHERE a.cod_presi = aaa.cod_presi AND a.cod_entidad = aaa.cod_entidad AND a.cod_tipo_inst = aaa.cod_tipo_inst AND a.cod_inst = aaa.cod_inst AND a.cod_dep = aaa.cod_dep AND a.cedula_identidad = aaa.cedula_identidad AND a.cod_tipo_ayuda = aaa.cod_tipo_ayuda AND a.numero_ocacion = aaa.numero_ocacion AND a.numero_documento_evaluacion = aaa.numero_documento_evaluacion) AS evaluacion, ( SELECT aa.aprobado
           FROM casd01_evaluacion_ayuda aa
          WHERE a.cod_presi = aa.cod_presi AND a.cod_entidad = aa.cod_entidad AND a.cod_tipo_inst = aa.cod_tipo_inst AND a.cod_inst = aa.cod_inst AND a.cod_dep = aa.cod_dep AND a.cedula_identidad = aa.cedula_identidad AND a.cod_tipo_ayuda = aa.cod_tipo_ayuda AND a.numero_ocacion = aa.numero_ocacion AND a.numero_documento_evaluacion = aa.numero_documento_evaluacion) AS aprobacion, ( SELECT d.fecha_ayuda
           FROM casd01_ayudas_cuerpo d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.cedula_identidad = d.cedula_identidad AND a.cod_tipo_ayuda = d.cod_tipo_ayuda AND a.numero_ocacion = d.numero_ocacion AND a.numero_documento_evaluacion = d.numero_documento_evaluacion AND a.numero_documento_ayuda = d.numero_documento_ayuda) AS fecha_ayuda, ( SELECT e.monto_total
           FROM casd01_ayudas_cuerpo e
          WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND a.cedula_identidad = e.cedula_identidad AND a.cod_tipo_ayuda = e.cod_tipo_ayuda AND a.numero_ocacion = e.numero_ocacion AND a.numero_documento_evaluacion = e.numero_documento_evaluacion AND a.numero_documento_ayuda = e.numero_documento_ayuda) AS monto_total
   FROM casd01_solicitud_ayuda a;

ALTER TABLE v_casp01_relacion_solicitudes OWNER TO postgres;


--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////7






