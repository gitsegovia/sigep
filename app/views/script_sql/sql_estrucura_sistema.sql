-- Table: arrd01

-- DROP TABLE arrd01;

CREATE TABLE arrd01
(
  cod_presi integer NOT NULL, -- Código de la República
  denominacion character varying(200), -- Denominación de la República
  CONSTRAINT arrd01_pkey PRIMARY KEY (cod_presi)
)
WITH (OIDS=FALSE);
ALTER TABLE arrd01 OWNER TO sisap;
COMMENT ON TABLE arrd01 IS 'Registro de las Repúblicas';
COMMENT ON COLUMN arrd01.cod_presi IS 'Código de la República';
COMMENT ON COLUMN arrd01.denominacion IS 'Denominación de la República';




































-- Table: arrd02

-- DROP TABLE arrd02;

CREATE TABLE arrd02
(
  cod_presi integer NOT NULL, -- Código de la República
  cod_entidad integer NOT NULL, -- Código del Estado
  denominacion character varying(200), -- Denominación del Estado
  CONSTRAINT arrd02_pkey PRIMARY KEY (cod_presi, cod_entidad),
  CONSTRAINT arrd01_republica_1 FOREIGN KEY (cod_presi)
      REFERENCES arrd01 (cod_presi) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE arrd02 OWNER TO sisap;
COMMENT ON TABLE arrd02 IS 'Registro de los Estados de la República';
COMMENT ON COLUMN arrd02.cod_presi IS 'Código de la República';
COMMENT ON COLUMN arrd02.cod_entidad IS 'Código del Estado';
COMMENT ON COLUMN arrd02.denominacion IS 'Denominación del Estado';































-- Table: arrd03

-- DROP TABLE arrd03;

CREATE TABLE arrd03
(
  cod_presi integer NOT NULL, -- Código de la República
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  denominacion character varying(200), -- Denominación del Estado
  CONSTRAINT arrd03_pkey PRIMARY KEY (cod_presi, cod_tipo_inst),
  CONSTRAINT arrd01_republica_2 FOREIGN KEY (cod_presi)
      REFERENCES arrd01 (cod_presi) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE arrd03 OWNER TO sisap;
COMMENT ON TABLE arrd03 IS 'Registro de los tipos de Institución';
COMMENT ON COLUMN arrd03.cod_presi IS 'Código de la República';
COMMENT ON COLUMN arrd03.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN arrd03.denominacion IS 'Denominación del Estado';































-- Table: arrd04

-- DROP TABLE arrd04;

CREATE TABLE arrd04
(
  cod_presi integer NOT NULL, -- Código de la República
  cod_entidad integer NOT NULL, -- Código del Estado
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  denominacion character varying(200), -- Denominación de la Institución
  CONSTRAINT arrd04_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst)
)
WITH (OIDS=FALSE);
ALTER TABLE arrd04 OWNER TO sisap;
COMMENT ON TABLE arrd04 IS 'Registro de las Instituciones';
COMMENT ON COLUMN arrd04.cod_presi IS 'Código de la República';
COMMENT ON COLUMN arrd04.cod_entidad IS 'Código del Estado';
COMMENT ON COLUMN arrd04.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN arrd04.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN arrd04.denominacion IS 'Denominación de la Institución';
