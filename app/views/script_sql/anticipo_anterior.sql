-- View: anticipos_anterior1

-- DROP VIEW anticipos_anterior1;

CREATE OR REPLACE VIEW anticipos_anterior1 AS
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_contrato_servicio AS ano_documento, a.ano, a.cod_sector, a.cod_partida, substr(b.fecha_anticipo::text, 6, 2)::integer AS mes, sum(a.monto) AS monto
   FROM cepd02_contratoservicio_anticipo_partidas a, cepd02_contratoservicio_anticipo_cuerpo b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.ano_contrato_servicio = b.ano_contrato_servicio AND a.numero_contrato_servicio::text = b.numero_contrato_servicio::text AND a.numero_anticipo = b.numero_anticipo AND b.saldo_ano_anterior = 2 AND b.condicion_actividad = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_contrato_servicio, a.ano, a.cod_sector, a.cod_partida, substr(b.fecha_anticipo::text, 6, 2)::integer
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_contrato_obra AS ano_documento, a.ano, a.cod_sector, a.cod_partida, substr(b.fecha_anticipo::text, 6, 2)::integer AS mes, sum(a.monto) AS monto
   FROM cobd01_contratoobras_anticipo_partidas a, cobd01_contratoobras_anticipo_cuerpo b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.ano_contrato_obra = b.ano_contrato_obra AND a.numero_contrato_obra::text = b.numero_contrato_obra::text AND a.numero_anticipo = b.numero_anticipo AND b.saldo_ano_anterior = 2 AND b.condicion_actividad = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_contrato_obra, a.ano, a.cod_sector, a.cod_partida, substr(b.fecha_anticipo::text, 6, 2)::integer)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_orden_compra AS ano_documento, a.ano, a.cod_sector, a.cod_partida, substr(b.fecha_anticipo::text, 6, 2)::integer AS mes, sum(a.monto) AS monto
   FROM cscd04_ordencompra_anticipo_partidas a, cscd04_ordencompra_anticipo_cuerpo b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.ano_orden_compra = b.ano_orden_compra AND a.numero_orden_compra = b.numero_orden_compra AND a.numero_anticipo = b.numero_anticipo AND b.saldo_ano_anterior = 2 AND b.condicion_actividad = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_orden_compra, a.ano, a.cod_sector, a.cod_partida, substr(b.fecha_anticipo::text, 6, 2)::integer;

ALTER TABLE anticipos_anterior1 OWNER TO sisap;

-- View: v_casd01_relacion_solicitantes



 DROP VIEW v_casd01_relacion_solicitantes cascade;

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


CREATE OR REPLACE VIEW v_casd01_comunicacion AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, c.apellidos_nombres
   FROM casd01_evaluacion_ayuda a, v_casd01_relacion_solicitantes c
  WHERE a.cedula_identidad = c.cedula_identidad AND a.aprobado <> 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cedula_identidad, c.apellidos_nombres;

ALTER TABLE v_casd01_comunicacion OWNER TO sisap;



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
