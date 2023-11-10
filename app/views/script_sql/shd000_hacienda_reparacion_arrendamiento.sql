 DROP VIEW v_shd600_compra_terreno;
 DROP VIEW v_shd600_aprobacion_arrendamiento;
 DROP VIEW v_shd600_solicitud_arrendamiento;
 DROP VIEW v_shd001_contribuyentes_e_impuestos;
 DROP VIEW v_relacion_coradores;
DROP VIEW v_grilla_constribuyentes;
DROP VIEW v_shd600_contribuyentes_arrendamiento;
DROP VIEW v_shd900_planilla_liquidacion_previa_arrendamiento;
DROP VIEW v_shd600_solicitud_apobacion_arrendamiento;
DROP VIEW v_shd600_aprobacion_arrendamiento_deuda_cobro_detalles;




DROP TABLE shd600_compra_terreno;
DROP TABLE shd600_aprobacion_arrendamiento;
 DROP TABLE shd600_solicitud_arrendamiento;
-- Table: shd600_compra_terreno




-- Table: shd600_solicitud_arrendamiento



CREATE TABLE shd600_solicitud_arrendamiento
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  fecha_solicitud date NOT NULL, -- Fecha de la solicitud de arrendamiento
  opcion integer NOT NULL, -- Opción...
  cod_ficha character varying(20) NOT NULL, -- Código de la ficha catastral
  expectativa_construccion text, -- Expectativa de construcción que tiene el solicitante
  CONSTRAINT shd600_solicitud_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud)
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_solicitud_arrendamiento OWNER TO sisap;
COMMENT ON TABLE shd600_solicitud_arrendamiento IS 'Registra la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.rif_cedula IS 'Rif o Cédula de identidad';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.numero_solicitud IS 'Número de solicitud';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.fecha_solicitud IS 'Fecha de la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.opcion IS 'Opción
1.- Simple
2.- Compra';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_ficha IS 'Código de la ficha catastral';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.expectativa_construccion IS 'Expectativa de construcción que tiene el solicitante';





CREATE TABLE shd600_aprobacion_arrendamiento
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL,
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud de arrendamiento
  fecha_aprobacion date NOT NULL, -- Fecha de aprobacion
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  datos_registro_arrendamiento text NOT NULL, -- Datos registro de arrendamiento
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer, -- Contribuyente pago todo el año ?...
  suspendido integer NOT NULL, -- Pago del contribuyente esta suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año facturado
  ultimo_mes_facturado integer, -- Ultimo mes facturado
  terreno_vendido integer, -- Terreno vendido ?...
  CONSTRAINT shd600_aprobacion_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud),
  CONSTRAINT shd600_aprobacion_arrendamiento_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud)
      REFERENCES shd600_solicitud_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_aprobacion_arrendamiento OWNER TO sisap;
COMMENT ON TABLE shd600_aprobacion_arrendamiento IS 'Registro de la aprobación a la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.numero_solicitud IS 'Número de solicitud de arrendamiento';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.fecha_aprobacion IS 'Fecha de aprobacion';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.datos_registro_arrendamiento IS 'Datos registro de arrendamiento';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.monto_mensual IS 'Monto mensual';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.pago_todo IS 'Contribuyente pago todo el año ?
1.- Si
2.- No';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.suspendido IS 'Pago del contribuyente esta suspendido ?
1.- Si
2.- No';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_ano_facturado IS 'Ultimo año facturado';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_mes_facturado IS 'Ultimo mes facturado';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.terreno_vendido IS 'Terreno vendido ?
1.- Si
2.- No';





CREATE TABLE shd600_compra_terreno
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL,
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  fecha_compra date NOT NULL, -- Fecha de compra
  datos_compra text NOT NULL, -- Datos de la compra
  monto numeric(26,2) NOT NULL, -- Monto de compra
  CONSTRAINT shd600_compra_terreno_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud),
  CONSTRAINT shd600_compra_terreno_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud)
      REFERENCES shd600_aprobacion_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_compra_terreno OWNER TO sisap;
COMMENT ON TABLE shd600_compra_terreno IS 'Registra la compra del terreno, previo arrendamiento';
COMMENT ON COLUMN shd600_compra_terreno.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_compra_terreno.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_compra_terreno.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd600_compra_terreno.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_compra_terreno.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_compra_terreno.numero_solicitud IS 'Número de solicitud';
COMMENT ON COLUMN shd600_compra_terreno.fecha_compra IS 'Fecha de compra';
COMMENT ON COLUMN shd600_compra_terreno.datos_compra IS 'Datos de la compra';
COMMENT ON COLUMN shd600_compra_terreno.monto IS 'Monto de compra';




-- View: v_shd600_compra_terreno

-- DROP VIEW v_shd600_compra_terreno;

CREATE OR REPLACE VIEW v_shd600_compra_terreno AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.terreno_vendido
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS terreno_vendido, a.fecha_compra, a.datos_compra, a.monto, ( SELECT b.opcion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS opcion, ( SELECT b.rif_cedula
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_profesion, ( SELECT b.cod_ficha
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_total, ( SELECT b.expectativa_construccion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS expectativa_construccion, ( SELECT b.datos_registro_arrendamiento
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS datos_registro_arrendamiento, ( SELECT b.rif_ci_cobrador
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_ci_cobrador, ( SELECT e.nombre_razon
           FROM shd002_cobradores e
          WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND ((( SELECT b.rif_ci_cobrador
                   FROM shd600_aprobacion_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = e.rif_ci::text) AS deno_cobrador, ( SELECT b.frecuencia_pago
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS frecuencia_pago, ( SELECT b.monto_mensual
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS monto_mensual, ( SELECT b.pago_todo
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS pago_todo, ( SELECT b.suspendido
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS suspendido, ( SELECT b.ultimo_ano_facturado
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS ultimo_ano_facturado, ( SELECT b.ultimo_mes_facturado
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS ultimo_mes_facturado
   FROM shd600_compra_terreno a;

ALTER TABLE v_shd600_compra_terreno OWNER TO sisap;














-- View: v_shd600_aprobacion_arrendamiento

-- DROP VIEW v_shd600_aprobacion_arrendamiento;

CREATE OR REPLACE VIEW v_shd600_aprobacion_arrendamiento AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.opcion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS opcion, ( SELECT b.rif_cedula
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_profesion, ( SELECT b.cod_ficha
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_total, ( SELECT b.expectativa_construccion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS expectativa_construccion, a.fecha_aprobacion, a.frecuencia_pago, a.datos_registro_arrendamiento, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT e.nombre_razon
           FROM shd002_cobradores e
          WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND a.rif_ci_cobrador::text = e.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.terreno_vendido, ( SELECT b.monto
           FROM shd600_compra_terreno b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS monto, ( SELECT b.fecha_compra
           FROM shd600_compra_terreno b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_venta
   FROM shd600_aprobacion_arrendamiento a;

ALTER TABLE v_shd600_aprobacion_arrendamiento OWNER TO sisap;






-- View: v_shd600_solicitud_arrendamiento

-- DROP VIEW v_shd600_solicitud_arrendamiento;

CREATE OR REPLACE VIEW v_shd600_solicitud_arrendamiento AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.opcion, a.cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_total, a.expectativa_construccion
   FROM shd600_solicitud_arrendamiento a;

ALTER TABLE v_shd600_solicitud_arrendamiento OWNER TO sisap;

-- View: v_shd600_solicitud_arrendamiento

-- DROP VIEW v_shd600_solicitud_arrendamiento;

CREATE OR REPLACE VIEW v_shd600_solicitud_arrendamiento AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.opcion, a.cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_total, a.expectativa_construccion
   FROM shd600_solicitud_arrendamiento a;

ALTER TABLE v_shd600_solicitud_arrendamiento OWNER TO sisap;






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
 SELECT a.rif_cedula,
        a.personalidad_juridica,
        a.razon_social_nombres,
        a.fecha_inscripcion,
        b.cod_presi,
        b.cod_entidad,
        b.cod_tipo_inst,
        b.cod_inst,
        b.cod_dep,
        b.monto_mensual, 7::character varying AS pertenece_tabla,
        ( SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 7) AS concepto_impuesto
FROM shd001_registro_contribuyentes a, shd700_credito_vivienda b WHERE a.rif_cedula::text = b.rif_cedula::text;

ALTER TABLE v_shd001_contribuyentes_e_impuestos OWNER TO sisap;


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
 SELECT a.rif_cedula,
        a.personalidad_juridica,
        a.razon_social_nombres,
        a.fecha_inscripcion,
        b.cod_presi,
        b.cod_entidad,
        b.cod_tipo_inst,
        b.cod_inst,
        b.cod_dep,
        b.monto_mensual, 7::character varying AS pertenece_tabla,
        ( SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 7) AS concepto_impuesto
FROM shd001_registro_contribuyentes a, shd700_credito_vivienda b WHERE a.rif_cedula::text = b.rif_cedula::text;

ALTER TABLE v_shd001_contribuyentes_e_impuestos OWNER TO sisap;







-- DROP VIEW v_relacion_coradores;


CREATE OR REPLACE VIEW v_relacion_coradores AS

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  (SELECT s.rif_cedula FROM shd100_solicitud s  WHERE  s.cod_presi        =  a.cod_presi      and
												       s.cod_entidad      =  a.cod_entidad    and
												       s.cod_tipo_inst    =  a.cod_tipo_inst  and
												       s.cod_inst         =  a.cod_inst       and
												       s.cod_dep          =  a.cod_dep        and
												       s.numero_solicitud =  aa.numero_solicitud) AS rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  aa.frecuencia_pago,
  (aa.monto_mensual) as monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 1) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = (SELECT s.rif_cedula FROM shd100_solicitud s WHERE  s.cod_presi        =  a.cod_presi      and
																																				       s.cod_entidad      =  a.cod_entidad    and
																																				       s.cod_tipo_inst    =  a.cod_tipo_inst  and
																																				       s.cod_inst         =  a.cod_inst       and
																																				       s.cod_dep          =  a.cod_dep        and
																																				       s.numero_solicitud =  aa.numero_solicitud)) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = (SELECT s.rif_cedula FROM shd100_solicitud s WHERE      s.cod_presi        =  a.cod_presi      and
																																					       s.cod_entidad      =  a.cod_entidad    and
																																					       s.cod_tipo_inst    =  a.cod_tipo_inst  and
																																					       s.cod_inst         =  a.cod_inst       and
																																					       s.cod_dep          =  a.cod_dep        and
																																					       s.numero_solicitud =  aa.numero_solicitud)) as razon_social_nombres,
  (1) as tipo_ingreso
from
    shd002_cobradores a, shd100_patente aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  aa.frecuencia_pago,
  (aa.monto_mensual) as monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 2) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (2) as tipo_ingreso
from
    shd002_cobradores a, shd200_vehiculos aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  aa.frecuencia_pago,
  (aa.monto_mensual_general) as    monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 3) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (3) as tipo_ingreso
from
    shd002_cobradores a, shd300_propaganda aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  aa.frecuencia_pago,
  (aa.monto_mensual) as    monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 4) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (4) as tipo_ingreso
from
    shd002_cobradores a, shd400_propiedad aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  aa.frecuencia_pago,
  (aa.monto_mensual) as    monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 5) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (5) as tipo_ingreso
from
    shd002_cobradores a, shd500_aseo_domiciliario aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  (SELECT s.rif_cedula FROM shd600_solicitud_arrendamiento s  WHERE  s.cod_presi        =  a.cod_presi      and
															         s.cod_entidad      =  a.cod_entidad    and
															         s.cod_tipo_inst    =  a.cod_tipo_inst  and
															         s.cod_inst         =  a.cod_inst       and
															         s.cod_dep          =  a.cod_dep        and
															         s.numero_solicitud =  aa.numero_solicitud) AS rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  aa.frecuencia_pago,
  (aa.monto_mensual) as    monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 6) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = (SELECT s.rif_cedula FROM shd600_solicitud_arrendamiento s WHERE  s.cod_presi        =  a.cod_presi      and
																																							         s.cod_entidad      =  a.cod_entidad    and
																																							         s.cod_tipo_inst    =  a.cod_tipo_inst  and
																																							         s.cod_inst         =  a.cod_inst       and
																																							         s.cod_dep          =  a.cod_dep        and
																																							         s.numero_solicitud =  aa.numero_solicitud)) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = (SELECT s.rif_cedula FROM shd600_solicitud_arrendamiento s WHERE  s.cod_presi        =  a.cod_presi      and
																																							         s.cod_entidad      =  a.cod_entidad    and
																																							         s.cod_tipo_inst    =  a.cod_tipo_inst  and
																																							         s.cod_inst         =  a.cod_inst       and
																																							         s.cod_dep          =  a.cod_dep        and
																																							         s.numero_solicitud =  aa.numero_solicitud)) as razon_social_nombres,
  (6) as tipo_ingreso
from
    shd002_cobradores a, shd600_aprobacion_arrendamiento aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci

UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  aa.frecuencia_pago,
  (aa.monto_mensual) as   monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 7) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (7) as tipo_ingreso
from
    shd002_cobradores a, shd700_credito_vivienda aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci;




ALTER TABLE v_relacion_coradores OWNER TO sisap;





-- View: v_grilla_constribuyentes

-- DROP VIEW v_grilla_constribuyentes;

CREATE OR REPLACE VIEW v_grilla_constribuyentes AS
(((( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.rif_cedula
           FROM shd100_solicitud b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.numero_solicitud::text = a.numero_solicitud::text) AS rif_cedula, a.frecuencia_pago, a.monto_mensual * 12::numeric AS monto, 1 AS tipo
   FROM shd100_patente a
UNION
 SELECT v_shd200_vehiculos.cod_presi, v_shd200_vehiculos.cod_entidad, v_shd200_vehiculos.cod_tipo_inst, v_shd200_vehiculos.cod_inst, v_shd200_vehiculos.cod_dep, 0::character varying(20) AS numero_solicitud, v_shd200_vehiculos.rif_cedula, v_shd200_vehiculos.frecuencia_pago, v_shd200_vehiculos.monto_anual AS monto, 2 AS tipo
   FROM v_shd200_vehiculos)
UNION
 SELECT shd300_propaganda.cod_presi, shd300_propaganda.cod_entidad, shd300_propaganda.cod_tipo_inst, shd300_propaganda.cod_inst, shd300_propaganda.cod_dep, 0::character varying(20) AS numero_solicitud, shd300_propaganda.rif_cedula, shd300_propaganda.frecuencia_pago, shd300_propaganda.monto_mensual_general * 12::numeric AS monto, 3 AS tipo
   FROM shd300_propaganda)
UNION
 SELECT shd400_propiedad.cod_presi, shd400_propiedad.cod_entidad, shd400_propiedad.cod_tipo_inst, shd400_propiedad.cod_inst, shd400_propiedad.cod_dep, 0::character varying(20) AS numero_solicitud, shd400_propiedad.rif_cedula, shd400_propiedad.frecuencia_pago, shd400_propiedad.monto_mensual * 12::numeric AS monto, 4 AS tipo
   FROM shd400_propiedad)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.rif_cedula
           FROM shd600_solicitud_arrendamiento b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND upper(b.numero_solicitud::text) = upper(a.numero_solicitud::text)) AS rif_cedula, a.frecuencia_pago, a.monto_mensual * 12::numeric AS monto, 5 AS tipo
   FROM shd600_aprobacion_arrendamiento a)
UNION
 SELECT shd700_credito_vivienda.cod_presi, shd700_credito_vivienda.cod_entidad, shd700_credito_vivienda.cod_tipo_inst, shd700_credito_vivienda.cod_inst, shd700_credito_vivienda.cod_dep, 0::character varying(20) AS numero_solicitud, shd700_credito_vivienda.rif_cedula, shd700_credito_vivienda.frecuencia_pago, shd700_credito_vivienda.monto_mensual * 12::numeric AS monto, 6 AS tipo
   FROM shd700_credito_vivienda;

ALTER TABLE v_grilla_constribuyentes OWNER TO sisap;




-- View: v_shd600_contribuyentes_arrendamiento

 --DROP VIEW v_shd600_contribuyentes_arrendamiento;

CREATE OR REPLACE VIEW v_shd600_contribuyentes_arrendamiento AS
 SELECT
 a.cod_presi,
 a.cod_entidad,
 a.cod_tipo_inst,
 a.cod_inst,
 a.cod_dep,
 a.rif_cedula,
 ( SELECT b.razon_social_nombres
           FROM shd001_registro_contribuyentes b
          WHERE b.rif_cedula::text = a.rif_cedula::text) AS nombre_razon,
 a.numero_solicitud,
 a.fecha_aprobacion,
 a.frecuencia_pago,
 a.datos_registro_arrendamiento,
 a.monto_mensual, a.pago_todo,
 a.suspendido,
 a.rif_ci_cobrador,
 a.ultimo_ano_facturado,
 a.ultimo_mes_facturado,
 a.terreno_vendido,
 ( SELECT b.deuda_ano_anterior_por_impuesto_6 FROM v_shd900_planillas_deuda_cobro_detalles b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.rif_cedula::text = a.rif_cedula::text  LIMIT 1) AS deuda_ano_anterior,
 ( SELECT sum((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) FROM v_shd900_planillas_deuda_cobro_detalles b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.rif_cedula::text = a.rif_cedula::text and b.cod_numero_catastral_placas::text = a.numero_solicitud::text and b.cancelado=2) AS deuda_vigente
 FROM shd600_aprobacion_arrendamiento a;

ALTER TABLE v_shd600_contribuyentes_arrendamiento OWNER TO sisap;





-- View: v_shd900_planilla_liquidacion_previa_arrendamiento

-- DROP VIEW v_shd900_planilla_liquidacion_previa_arrendamiento;

CREATE OR REPLACE VIEW v_shd900_planilla_liquidacion_previa_arrendamiento AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_numero_catastral_placas, a.ano, a.mes, a.rif_cedula, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, ( SELECT d.razon_social_nombres
           FROM shd001_registro_contribuyentes d
          WHERE a.rif_cedula::text = d.rif_cedula::text) AS razon_social_nombres, b.frecuencia_pago
   FROM shd900_planillas_deuda_cobro_detalles a, shd600_aprobacion_arrendamiento b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text AND a.cod_numero_catastral_placas::text = b.numero_solicitud::text
  GROUP BY a.ano, a.mes, a.rif_cedula, a.numero_planilla, a.cod_numero_catastral_placas, a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, b.frecuencia_pago
  ORDER BY a.ano, a.mes, a.rif_cedula, a.numero_planilla, a.cod_numero_catastral_placas;

ALTER TABLE v_shd900_planilla_liquidacion_previa_arrendamiento OWNER TO sisap;



-- View: v_shd600_solicitud_apobacion_arrendamiento

-- DROP VIEW v_shd600_solicitud_apobacion_arrendamiento;

CREATE OR REPLACE VIEW v_shd600_solicitud_apobacion_arrendamiento AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM shd001_registro_contribuyentes b
          WHERE b.rif_cedula::text = a.rif_cedula::text) AS nombre_razon, a.numero_solicitud, a.fecha_solicitud, a.opcion, a.cod_ficha, a.expectativa_construccion, ( SELECT b.terreno_vendido
           FROM shd600_aprobacion_arrendamiento b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.rif_cedula::text = a.rif_cedula::text AND b.numero_solicitud::text = a.numero_solicitud::text) AS terreno_vendido
   FROM shd600_solicitud_arrendamiento a;

ALTER TABLE v_shd600_solicitud_apobacion_arrendamiento OWNER TO sisap;






-- View: v_shd600_aprobacion_arrendamiento_deuda_cobro_detalles

-- DROP VIEW v_shd600_aprobacion_arrendamiento_deuda_cobro_detalles;

CREATE OR REPLACE VIEW v_shd600_aprobacion_arrendamiento_deuda_cobro_detalles AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, a.numero_solicitud, a.fecha_aprobacion, a.frecuencia_pago, a.datos_registro_arrendamiento, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.terreno_vendido, b.fecha_solicitud, b.opcion, b.cod_ficha, b.expectativa_construccion, c.personalidad_juridica, c.razon_social_nombres, c.fecha_inscripcion, c.nacionalidad, c.estado_civil, c.profesion, c.cod_pais, c.cod_estado, c.cod_municipio, c.cod_parroquia, c.cod_centro_poblado, c.cod_calle_avenida, c.cod_vereda_edificio, c.numero_vivienda_local, c.telefonos_fijos, c.telefonos_celulares, c.correo_electronico, ( SELECT xya.denominacion
           FROM cugd01_estados xya
          WHERE xya.cod_republica = c.cod_pais AND xya.cod_estado = c.cod_estado
          GROUP BY xya.denominacion) AS deno_cod_estado, ( SELECT xyb.denominacion
           FROM cugd01_municipios xyb
          WHERE xyb.cod_republica = c.cod_pais AND xyb.cod_estado = c.cod_estado AND xyb.cod_municipio = c.cod_municipio
          GROUP BY xyb.denominacion) AS deno_cod_municipio, ( SELECT xyb.conocido
           FROM cugd01_municipios xyb
          WHERE xyb.cod_republica = c.cod_pais AND xyb.cod_estado = c.cod_estado AND xyb.cod_municipio = c.cod_municipio
          GROUP BY xyb.conocido) AS conocido, ( SELECT xyc.denominacion
           FROM cugd01_parroquias xyc
          WHERE xyc.cod_republica = c.cod_pais AND xyc.cod_estado = c.cod_estado AND xyc.cod_municipio = c.cod_municipio AND xyc.cod_parroquia = c.cod_parroquia
          GROUP BY xyc.denominacion) AS deno_cod_parroquia, ( SELECT xyd.denominacion
           FROM cugd01_centros_poblados xyd
          WHERE xyd.cod_republica = c.cod_pais AND xyd.cod_estado = c.cod_estado AND xyd.cod_municipio = c.cod_municipio AND xyd.cod_parroquia = c.cod_parroquia AND xyd.cod_centro = c.cod_centro_poblado
          GROUP BY xyd.denominacion) AS deno_cod_centro, ( SELECT xyd.denominacion
           FROM cugd01_vialidad xyd
          WHERE xyd.cod_republica = c.cod_pais AND xyd.cod_estado = c.cod_estado AND xyd.cod_municipio = c.cod_municipio AND xyd.cod_parroquia = c.cod_parroquia AND xyd.cod_centro = c.cod_centro_poblado AND xyd.cod_vialidad = c.cod_calle_avenida
          GROUP BY xyd.denominacion) AS deno_cod_calle_avd, ( SELECT xyd.denominacion
           FROM cugd01_vereda xyd
          WHERE xyd.cod_republica = c.cod_pais AND xyd.cod_estado = c.cod_estado AND xyd.cod_municipio = c.cod_municipio AND xyd.cod_parroquia = c.cod_parroquia AND xyd.cod_centro = c.cod_centro_poblado AND xyd.cod_vialidad = c.cod_calle_avenida AND xyd.cod_vereda = c.cod_vereda_edificio
          GROUP BY xyd.denominacion) AS deno_cod_verenda, ( SELECT sum(xyd.deuda_vigente) AS sum
           FROM shd900_planillas_deuda_cobro_detalles xyd
          WHERE xyd.cod_presi = a.cod_presi AND xyd.cod_entidad = a.cod_entidad AND xyd.cod_tipo_inst = a.cod_tipo_inst AND xyd.cod_inst = a.cod_inst AND xyd.cod_dep = a.cod_dep AND xyd.rif_cedula::text = a.rif_cedula::text AND xyd.cancelado = 2 AND 6 = (( SELECT aa.cod_ingreso
                   FROM shd003_codigo_ingresos aa
                  WHERE aa.cod_partida = xyd.cod_partida AND aa.cod_generica = xyd.cod_generica AND aa.cod_especifica = xyd.cod_especifica AND aa.cod_subespec = xyd.cod_sub_espec AND aa.cod_auxiliar = xyd.cod_auxiliar
                 LIMIT 1))) AS deuda_vigente, ( SELECT sum(xyd.monto_recargo) AS sum
           FROM shd900_planillas_deuda_cobro_detalles xyd
          WHERE xyd.cod_presi = a.cod_presi AND xyd.cod_entidad = a.cod_entidad AND xyd.cod_tipo_inst = a.cod_tipo_inst AND xyd.cod_inst = a.cod_inst AND xyd.cod_dep = a.cod_dep AND xyd.rif_cedula::text = a.rif_cedula::text AND xyd.cancelado = 2 AND 6 = (( SELECT aa.cod_ingreso
                   FROM shd003_codigo_ingresos aa
                  WHERE aa.cod_partida = xyd.cod_partida AND aa.cod_generica = xyd.cod_generica AND aa.cod_especifica = xyd.cod_especifica AND aa.cod_subespec = xyd.cod_sub_espec AND aa.cod_auxiliar = xyd.cod_auxiliar
                 LIMIT 1))) AS monto_recargo, ( SELECT sum(xyd.monto_multa) AS sum
           FROM shd900_planillas_deuda_cobro_detalles xyd
          WHERE xyd.cod_presi = a.cod_presi AND xyd.cod_entidad = a.cod_entidad AND xyd.cod_tipo_inst = a.cod_tipo_inst AND xyd.cod_inst = a.cod_inst AND xyd.cod_dep = a.cod_dep AND xyd.rif_cedula::text = a.rif_cedula::text AND xyd.cancelado = 2 AND 6 = (( SELECT aa.cod_ingreso
                   FROM shd003_codigo_ingresos aa
                  WHERE aa.cod_partida = xyd.cod_partida AND aa.cod_generica = xyd.cod_generica AND aa.cod_especifica = xyd.cod_especifica AND aa.cod_subespec = xyd.cod_sub_espec AND aa.cod_auxiliar = xyd.cod_auxiliar
                 LIMIT 1))) AS monto_multa, ( SELECT sum(xyd.monto_intereses) AS sum
           FROM shd900_planillas_deuda_cobro_detalles xyd
          WHERE xyd.cod_presi = a.cod_presi AND xyd.cod_entidad = a.cod_entidad AND xyd.cod_tipo_inst = a.cod_tipo_inst AND xyd.cod_inst = a.cod_inst AND xyd.cod_dep = a.cod_dep AND xyd.rif_cedula::text = a.rif_cedula::text AND xyd.cancelado = 2 AND 6 = (( SELECT aa.cod_ingreso
                   FROM shd003_codigo_ingresos aa
                  WHERE aa.cod_partida = xyd.cod_partida AND aa.cod_generica = xyd.cod_generica AND aa.cod_especifica = xyd.cod_especifica AND aa.cod_subespec = xyd.cod_sub_espec AND aa.cod_auxiliar = xyd.cod_auxiliar
                 LIMIT 1))) AS monto_intereses, ( SELECT sum(xyd.monto_descuento) AS sum
           FROM shd900_planillas_deuda_cobro_detalles xyd
          WHERE xyd.cod_presi = a.cod_presi AND xyd.cod_entidad = a.cod_entidad AND xyd.cod_tipo_inst = a.cod_tipo_inst AND xyd.cod_inst = a.cod_inst AND xyd.cod_dep = a.cod_dep AND xyd.rif_cedula::text = a.rif_cedula::text AND xyd.cancelado = 2 AND 6 = (( SELECT aa.cod_ingreso
                   FROM shd003_codigo_ingresos aa
                  WHERE aa.cod_partida = xyd.cod_partida AND aa.cod_generica = xyd.cod_generica AND aa.cod_especifica = xyd.cod_especifica AND aa.cod_subespec = xyd.cod_sub_espec AND aa.cod_auxiliar = xyd.cod_auxiliar
                 LIMIT 1))) AS monto_descuento
   FROM shd600_aprobacion_arrendamiento a, shd600_solicitud_arrendamiento b, shd001_registro_contribuyentes c
  WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.numero_solicitud::text = a.numero_solicitud::text AND b.rif_cedula::text = a.rif_cedula::text AND c.rif_cedula::text = a.rif_cedula::text;

ALTER TABLE v_shd600_aprobacion_arrendamiento_deuda_cobro_detalles OWNER TO sisap;



