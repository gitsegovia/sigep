-- Table: ccnd02_proyectos_actividad_desembolso

 DROP TABLE ccnd02_proyectos_actividad_desembolso;

CREATE TABLE ccnd02_proyectos_actividad_desembolso
(
  cod_republica integer NOT NULL, -- Código de la república
  cod_estado integer NOT NULL, -- Código del estado
  cod_municipio integer NOT NULL, -- Código de municipio
  cod_parroquia integer NOT NULL, -- Código de la parroquia
  cod_centro integer NOT NULL, -- Código del centro poblado
  cod_concejo integer NOT NULL, -- Código del concejo comunal
  ano integer NOT NULL, -- Año del proyecto
  cod_proyecto character varying(30) NOT NULL, -- Código del proyecto
  cod_actividad integer NOT NULL, -- Código de la actividad
  semanas character varying(52), -- Semanas
  CONSTRAINT ccnd02_proyectos_actividad_desembolso_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto, cod_actividad),
  CONSTRAINT ccnd02_proyectos_actividad_desembolso_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
      REFERENCES ccnd02_proyectos (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE ccnd02_proyectos_actividad_desembolso OWNER TO sisap;
COMMENT ON TABLE ccnd02_proyectos_actividad_desembolso IS 'Registra el desembolso por actividad del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.cod_republica IS 'Código de la república';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.cod_estado IS 'Código del estado';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.cod_municipio IS 'Código de municipio';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.cod_parroquia IS 'Código de la parroquia';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.cod_centro IS 'Código del centro poblado';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.cod_concejo IS 'Código del concejo comunal';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.ano IS 'Año del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.cod_proyecto IS 'Código del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.cod_actividad IS 'Código de la actividad';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.semanas IS 'Semanas';

