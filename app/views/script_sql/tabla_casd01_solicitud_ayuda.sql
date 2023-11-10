

/////////////////////////TABLA VIEJA///////////////////



-- Table: casd01_solicitud_ayuda

-- DROP TABLE casd01_solicitud_ayuda;

CREATE TABLE casd01_solicitud_ayuda
(
  cedula_identidad integer NOT NULL, -- Cédula de identidad
  cod_tipo_ayuda integer NOT NULL, -- Código tipo de ayuda...
  numero_ocacion serial NOT NULL, -- Contador de solitudes realizadas
  ayuda_solicitada text NOT NULL, -- Concepto de la ayuda solicitada
  fecha_solicitud date NOT NULL, -- Fecha de solicitud de la ayuda
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de institución
  cod_inst integer NOT NULL, -- Código de institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_documento_evaluacion integer, -- Número del documento de evaluación
  numero_documento_ayuda integer, -- Número del documento de ayuda
  CONSTRAINT casd01_solicitud_ayuda_pkey PRIMARY KEY (cedula_identidad, cod_tipo_ayuda, numero_ocacion),
  CONSTRAINT casd01_solicitud_ayuda_1 FOREIGN KEY (cedula_identidad)
      REFERENCES casd01_datos_personales (cedula_identidad) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (OIDS=FALSE);
ALTER TABLE casd01_solicitud_ayuda OWNER TO sisap;
COMMENT ON TABLE casd01_solicitud_ayuda IS 'Registra las solicitudes de ayudas que se realizan en cada dependencia
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cedula_identidad IS 'Cédula de identidad';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_tipo_ayuda IS 'Código tipo de ayuda
 1.- EFECTIVO
 2.- MEDICAMENTOS
 3.- ALIMENTOS Y BEBIDAS
 4.- TRASLADOS MÉDICOS
 5.- PASAJES
 6.- HOSPITALIZACIÓN Y CIRUGIA
 7.-GASTOS FUNERARIOS
 8.- SILLAS DE RUEDAS
 9.- COMPETENCIAS DEPORTIVAS
10.- ÚTILES ESCOLARES
11.- ÚTILES DEPORTIVOS
12.- CRÉDITOS
13.- EMPLEOS
14.- SERVICIOS PÚBLICOS';
COMMENT ON COLUMN casd01_solicitud_ayuda.numero_ocacion IS 'Contador de solitudes realizadas
';
COMMENT ON COLUMN casd01_solicitud_ayuda.ayuda_solicitada IS 'Concepto de la ayuda solicitada
';
COMMENT ON COLUMN casd01_solicitud_ayuda.fecha_solicitud IS 'Fecha de solicitud de la ayuda';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_presi IS 'Código de la presidencia
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_entidad IS 'Código de la entidad federal
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_tipo_inst IS 'Código tipo de institución';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_inst IS 'Código de institución
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_dep IS 'Código de la dependencia
';
COMMENT ON COLUMN casd01_solicitud_ayuda.numero_documento_evaluacion IS 'Número del documento de evaluación
';
COMMENT ON COLUMN casd01_solicitud_ayuda.numero_documento_ayuda IS 'Número del documento de ayuda
';





/////////////////////////TABLA NUEVA///////////////////


-- Table: casd01_solicitud_ayuda

-- DROP TABLE casd01_solicitud_ayuda;

CREATE TABLE casd01_solicitud_ayuda
(
  cod_presi int4 NOT NULL, -- Código de la presidencia
  cod_entidad int4 NOT NULL, -- Código de la entidad federal
  cod_tipo_inst int4 NOT NULL, -- Código tipo de Institución
  cod_inst int4 NOT NULL, -- Código de la Institución
  cod_dep int4 NOT NULL, -- Código de dependencia
  cedula_identidad int4 NOT NULL, -- Cédula de identidad
  cod_tipo_ayuda int4 NOT NULL, -- Código tipo de ayuda
  numero_ocacion int4 NOT NULL, -- Número de la ocación de solicitud
  ayuda_solicitada text NOT NULL, -- Ayuda solicitada
  fecha_solicitud date NOT NULL, -- Fecha de solicitud
  numero_documento_evaluacion int4, -- Número de documento de evaluación
  numero_documento_ayuda int4, -- Número de documento de ayuda
  CONSTRAINT casd01_solicitud_ayuda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cedula_identidad, cod_tipo_ayuda, numero_ocacion),
  CONSTRAINT casd01_solicitud_ayuda_1 FOREIGN KEY (cedula_identidad)
      REFERENCES casd01_datos_personales (cedula_identidad) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE casd01_solicitud_ayuda OWNER TO sisap;
COMMENT ON TABLE casd01_solicitud_ayuda IS 'Registra las solicitudes de ayudas efectuadas por los Ciudadanos';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_presi IS 'Código de la presidencia
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_entidad IS 'Código de la entidad federal
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_tipo_inst IS 'Código tipo de Institución
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_inst IS 'Código de la Institución
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_dep IS 'Código de dependencia
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cedula_identidad IS 'Cédula de identidad
';
COMMENT ON COLUMN casd01_solicitud_ayuda.cod_tipo_ayuda IS 'Código tipo de ayuda
';
COMMENT ON COLUMN casd01_solicitud_ayuda.numero_ocacion IS 'Número de la ocación de solicitud
';
COMMENT ON COLUMN casd01_solicitud_ayuda.ayuda_solicitada IS 'Ayuda solicitada
';
COMMENT ON COLUMN casd01_solicitud_ayuda.fecha_solicitud IS 'Fecha de solicitud
';
COMMENT ON COLUMN casd01_solicitud_ayuda.numero_documento_evaluacion IS 'Número de documento de evaluación
';
COMMENT ON COLUMN casd01_solicitud_ayuda.numero_documento_ayuda IS 'Número de documento de ayuda
';




