-- Table: cscd01_snc_grupo

-- DROP TABLE cscd01_snc_grupo;

CREATE TABLE cscd01_snc_grupo
(
  cod_grupo character varying(20) NOT NULL, -- Código de grupo
  denominacion character varying(200) NOT NULL, -- Denominación del grupo
  descripcion text NOT NULL, -- Descripción del grupo
  CONSTRAINT cscd01_snc_grupo_pkey PRIMARY KEY (cod_grupo)
)
WITH (OIDS=FALSE);
ALTER TABLE cscd01_snc_grupo OWNER TO sisap;
COMMENT ON TABLE cscd01_snc_grupo IS 'Registra el Grupo del Clasificador del Servicio Nacional de Contratistas';
COMMENT ON COLUMN cscd01_snc_grupo.cod_grupo IS 'Código de grupo';
COMMENT ON COLUMN cscd01_snc_grupo.denominacion IS 'Denominación del grupo
';
COMMENT ON COLUMN cscd01_snc_grupo.descripcion IS 'Descripción del grupo';

