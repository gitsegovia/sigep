-- Table: cscd01_snc_tipo

-- DROP TABLE cscd01_snc_tipo;

CREATE TABLE cscd01_snc_tipo
(
  cod_tipo character varying(30) NOT NULL, -- Código del tipo de productos...
  denominacion character varying(200) NOT NULL, -- Denominación del tipo de productos o servicios
  CONSTRAINT cscd01_snc_tipo_pkey PRIMARY KEY (cod_tipo)
)
WITH (OIDS=FALSE);
ALTER TABLE cscd01_snc_tipo OWNER TO sisap;
COMMENT ON TABLE cscd01_snc_tipo IS 'Registro del Tipo de productos o servicios del Clasificador del Servicio Nacional de Contratistas';
COMMENT ON COLUMN cscd01_snc_tipo.cod_tipo IS 'Código del tipo de productos

Nota: Los primeros 5 Dígitos pertenecen al Código del grupo

';
COMMENT ON COLUMN cscd01_snc_tipo.denominacion IS 'Denominación del tipo de productos o servicios

';

