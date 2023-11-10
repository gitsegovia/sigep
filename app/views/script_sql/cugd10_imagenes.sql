-- Table: cugd10_imagenes

-- DROP TABLE cugd10_imagenes;

CREATE TABLE cugd10_imagenes
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de institución
  cod_inst integer NOT NULL, -- Código de la institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_campo integer NOT NULL, -- 01.- Foto de la Institucion...
  identificacion character varying(30) NOT NULL, -- Identificación (No. de cédula, Placas, código, etc)
  imagen bytea NOT NULL, -- archivo binario de la imagen
  tipo character varying(100) NOT NULL, -- Tipo de imagen o formato
  size integer NOT NULL, -- Tamaño que pesa la imagen
  fecha date NOT NULL, -- Fecha de la carga de la imagen
  CONSTRAINT cugd10_imagenes_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_campo, identificacion)
)
WITH (OIDS=FALSE);
ALTER TABLE cugd10_imagenes OWNER TO sisap;
COMMENT ON TABLE cugd10_imagenes IS 'Guarda las imagenes por cada módulo';
COMMENT ON COLUMN cugd10_imagenes.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cugd10_imagenes.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN cugd10_imagenes.cod_tipo_inst IS 'Código tipo de institución';
COMMENT ON COLUMN cugd10_imagenes.cod_inst IS 'Código de la institución';
COMMENT ON COLUMN cugd10_imagenes.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cugd10_imagenes.cod_campo IS '01.- Foto de la Institucion
02.- Organigrama de la institución
03.- Foto de la dependencia
04.- Organigrama de la dependencia
05.- Constancia RNC
06.- Solvencia de Laboral
07.- Solvencia de S.S.O.
08.- Solvencia de I.N.C.E.
09.- Solvencia de Municipal
10.- Solvencia C.I.V.
11.- Cédula de identidad
12.- Titulo
13.- Foto de la obra (Antes)
14.- Foto de la obra (Después)
15.- Foto de beneficiario (Retiro Cheq)
16.- Foto solicitante de empleo
17.- Foto de Bienes mueble
18.- Foto de Bienes Inmuebles
19.- Foto del solicitante de la ayuda
';
COMMENT ON COLUMN cugd10_imagenes.identificacion IS 'Identificación (No. de cédula, Placas, código, etc)';
COMMENT ON COLUMN cugd10_imagenes.imagen IS 'archivo binario de la imagen';
COMMENT ON COLUMN cugd10_imagenes.tipo IS 'Tipo de imagen o formato';
COMMENT ON COLUMN cugd10_imagenes.size IS 'Tamaño que pesa la imagen';
COMMENT ON COLUMN cugd10_imagenes.fecha IS 'Fecha de la carga de la imagen';

