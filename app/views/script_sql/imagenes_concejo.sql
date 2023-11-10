-- Table: ccnd00_imagenes

-- DROP TABLE ccnd00_imagenes;

CREATE TABLE ccnd00_imagenes
(
  cod_republica integer NOT NULL, -- Código de la presidencia
  cod_estado integer NOT NULL, -- Código de la entidad federal
  cod_municipio integer NOT NULL, -- Código tipo de institución
  cod_parroquia integer NOT NULL, -- Código de la institución
  cod_centro integer NOT NULL, -- Código de la dependencia
  cod_concejo integer NOT NULL, -- Código de la dependencia
  cod_campo integer NOT NULL, -- 
  identificacion character varying(30) NOT NULL, -- Identificación (No. de cédula, Placas, código, etc)
  imagen bytea NOT NULL, -- archivo binario de la imagen
  tipo character varying(100) NOT NULL, -- Tipo de imagen o formato
  size integer NOT NULL, -- Tamaño que pesa la imagen
  fecha date NOT NULL, -- Fecha de la carga de la imagen
  imagen_miniatura bytea, -- Imagen miniatura
  ano integer NOT NULL,
  CONSTRAINT ccnd00_imagenes_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro,cod_concejo, cod_campo, identificacion)
)
WITH (OIDS=FALSE);
ALTER TABLE ccnd00_imagenes OWNER TO sisap;
COMMENT ON TABLE ccnd00_imagenes IS 'Guarda las imagenes por cada módulo';
COMMENT ON COLUMN ccnd00_imagenes.cod_republica IS 'Código de la presidencia';
COMMENT ON COLUMN ccnd00_imagenes.cod_estado IS 'Código de la entidad federal';
COMMENT ON COLUMN ccnd00_imagenes.cod_municipio IS 'Código tipo de institución';
COMMENT ON COLUMN ccnd00_imagenes.cod_parroquia IS 'Código de la institución';
COMMENT ON COLUMN ccnd00_imagenes.cod_centro IS 'Código de la dependencia';
COMMENT ON COLUMN ccnd00_imagenes.cod_concejo IS 'Código de la dependencia';
COMMENT ON COLUMN ccnd00_imagenes.cod_campo IS '
01.- Foto1
02.- Foto2
03.- Foto3
04.- Foto4
05.- Foto5
06.- Foto6
07.- Planos1
08.- Planos2
09.- Cotizaciones1
10.- Cotizaciones2
11.- Cotizaciones3
12.- Catalogo1
13.- Catalogo2
14.- Catalogo3
';
COMMENT ON COLUMN ccnd00_imagenes.identificacion IS 'Identificación (No. de cédula, Placas, código, etc)';
COMMENT ON COLUMN ccnd00_imagenes.imagen IS 'archivo binario de la imagen';
COMMENT ON COLUMN ccnd00_imagenes.tipo IS 'Tipo de imagen o formato';
COMMENT ON COLUMN ccnd00_imagenes.size IS 'Tamaño que pesa la imagen';
COMMENT ON COLUMN ccnd00_imagenes.fecha IS 'Fecha de la carga de la imagen';
COMMENT ON COLUMN ccnd00_imagenes.imagen_miniatura IS 'Imagen miniatura
';

