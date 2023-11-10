
-- Table: crcd01_actas_defuncion

-- DROP TABLE crcd01_actas_defuncion;

CREATE TABLE crcd01_actas_defuncion
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_acta character varying(100) NOT NULL,
  contenido_acta text,
  usuario character varying(100),
  fecha_proceso date,
  cedula_difunto integer,
  nombres_apellidos_difunto character varying(200),
  cedula_exponente integer,
  nombres_apellidos_exponente character varying(200),
  cedula_testigo integer,
  nombres_apellidos_testigo character varying(200),
  ano_acta integer,
  tomo character varying(20),
  folio character varying(20),
  CONSTRAINT crcd01_actas_defuncion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_acta)
)
WITH (OIDS=FALSE);
ALTER TABLE crcd01_actas_defuncion OWNER TO sisap;


-- Table: crcd01_actas_matrimonio

-- DROP TABLE crcd01_actas_matrimonio;

CREATE TABLE crcd01_actas_matrimonio
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_acta character varying(100) NOT NULL,
  contenido_acta text,
  usuario character varying(100),
  fecha_proceso date,
  cedula_novia integer,
  nombres_apellidos_novia character varying(200),
  cedula_novio integer,
  nombres_apellidos_novio character varying(200),
  cedula_testigo integer,
  nombres_apellidos_testigo character varying(200),
  ano_acta integer,
  tomo character varying(20),
  folio character varying(20),
  CONSTRAINT crcd01_actas_matrimonio_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_acta)
)
WITH (OIDS=FALSE);
ALTER TABLE crcd01_actas_matrimonio OWNER TO sisap;


-- Table: crcd01_actas_nacimiento

-- DROP TABLE crcd01_actas_nacimiento;

CREATE TABLE crcd01_actas_nacimiento
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_acta character varying(100) NOT NULL,
  contenido_acta text,
  usuario character varying(100),
  fecha_proceso date,
  cedula_madre integer,
  nombres_apellidos_madre character varying(200),
  cedula_padre integer,
  nombres_apellidos_padre character varying(200),
  cedula_testigo integer,
  nombres_apellidos_testigo character varying(200),
  ano_acta integer,
  tomo character varying(20),
  folio character varying(20),
  nombre_nacido character varying(100),
  sexo integer,
  CONSTRAINT crcd01_actas_nacimiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_acta)
)
WITH (OIDS=FALSE);
ALTER TABLE crcd01_actas_nacimiento OWNER TO sisap;

-- Table: crcd01_actas_plantillas

-- DROP TABLE crcd01_actas_plantillas;

CREATE TABLE crcd01_actas_plantillas
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_plantilla serial NOT NULL,
  contenido_acta text,
  titulo_tipo_acta character varying(150) NOT NULL,
  tipo_plantilla integer, -- 1. ACTA DE ADOPCIÓN...
  CONSTRAINT crcd01_actas_plantillas_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_plantilla)
)
WITH (OIDS=FALSE);
ALTER TABLE crcd01_actas_plantillas OWNER TO sisap;
COMMENT ON COLUMN crcd01_actas_plantillas.tipo_plantilla IS '1. ACTA DE ADOPCIÓN
2. ACTA DE CONCUBINATO
3. ACTA DE DEFUNCIÓN
4. ACTA DE DIVORCIO
5. ACTA DE MATRIMONIO
6. ACTA DE NACIMIENTO';


