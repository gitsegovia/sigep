
ALTER TABLE cfpd09 ADD COLUMN cantidad_estimada numeric(22,3) NOT NULL;
COMMENT ON COLUMN cfpd09.cantidad_estimada IS 'Cantidad estimada';




-- Table: cfpd08_identificacion_alcaldia_concejales

-- DROP TABLE cfpd08_identificacion_alcaldia_concejales;

CREATE TABLE cfpd08_identificacion_alcaldia_concejales
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la Dependencia
  ejercicio_fiscal integer NOT NULL, -- Ejercicio fiscal
  cod_concejal integer NOT NULL, -- Código concejal
  nombres_apellidos character varying(100) NOT NULL, -- Nombres y Apellidos del Concejal
  CONSTRAINT cfpd08_identificacion_alcaldia_concejales_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal, cod_concejal),
  CONSTRAINT cfpd08_identificacion_alcaldia_concejales_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal)
      REFERENCES cfpd08_identificacion_alcaldia (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd08_identificacion_alcaldia_concejales OWNER TO sisap;
COMMENT ON TABLE cfpd08_identificacion_alcaldia_concejales IS 'Registra la Identificación de la Alcaldía';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_concejales.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_concejales.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_concejales.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_concejales.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_concejales.cod_dep IS 'Código de la Dependencia';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_concejales.ejercicio_fiscal IS 'Ejercicio fiscal';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_concejales.cod_concejal IS 'Código concejal';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_concejales.nombres_apellidos IS 'Nombres y Apellidos del Concejal';



-- Table: cfpd08_identificacion_alcaldia_directivos

-- DROP TABLE cfpd08_identificacion_alcaldia_directivos;

CREATE TABLE cfpd08_identificacion_alcaldia_directivos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la Dependencia
  ejercicio_fiscal integer NOT NULL, -- Ejercicio fiscal
  cod_directivo integer NOT NULL, -- Código directivo
  nombres_apellidos character varying(100) NOT NULL, -- Nombres y Apellidos
  telefonos character varying(50) NOT NULL, -- Teléfonos
  direccion_electronica character varying(50) NOT NULL, -- Correo electrónico
  CONSTRAINT cfpd08_identificacion_alcaldia_directivos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal, cod_directivo),
  CONSTRAINT cfpd08_identificacion_alcaldia_directivos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal)
      REFERENCES cfpd08_identificacion_alcaldia (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd08_identificacion_alcaldia_directivos OWNER TO sisap;
COMMENT ON TABLE cfpd08_identificacion_alcaldia_directivos IS 'Registra la Identificación de la Alcaldía';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.cod_dep IS 'Código de la Dependencia';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.ejercicio_fiscal IS 'Ejercicio fiscal';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.cod_directivo IS 'Código directivo';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.nombres_apellidos IS 'Nombres y Apellidos';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.telefonos IS 'Teléfonos';
COMMENT ON COLUMN cfpd08_identificacion_alcaldia_directivos.direccion_electronica IS 'Correo electrónico';





















































BORRAR LA SIGUIENTE TABLA Y VOLVER A CREAR

-- Table: cfpd06

-- DROP TABLE cfpd06;

CREATE TABLE cfpd06
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano integer NOT NULL, -- Ejercicio fiscal
  cod_sector integer NOT NULL, -- Sector de inversiÃ³n
  cod_programa integer NOT NULL, -- Programa responsable
  cod_sub_prog integer NOT NULL, -- Sub-programa responsable
  cod_proyecto integer NOT NULL, -- Proyecto de adquisiciÃ³n
  cod_activ_obra integer NOT NULL, -- Actividad u obra responsable
  cod_partida integer NOT NULL, -- CÃ³digo de la partida
  cod_generica integer NOT NULL, -- CÃ³digo de la genÃ©rica
  cod_especifica integer NOT NULL, -- CÃ³digo de la especifica
  cod_sub_espec integer NOT NULL, -- CÃ³digo de la Sub-especifica
  cod_auxiliar integer NOT NULL, -- CÃ³digo del auxiliar
  numero_linea serial NOT NULL,
  cantidad_reemplazo integer NOT NULL, -- Cantidad de equÃ­pos a reeplazar
  cantidad_deficiencia integer NOT NULL, -- Cantidad de equÃ­pos por deficiencia a adquirir por la instituciÃ³n
  costo_unitario numeric(26,2) NOT NULL, -- Costo unitario estimado de los equÃ­pos.
  denominacion text NOT NULL, -- DenominaciÃ³n de los equÃ­pos
  CONSTRAINT cfpd06_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, numero_linea)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd06 OWNER TO sisap;
COMMENT ON TABLE cfpd06 IS 'Registra el Costo estimado de los equipos requeridos para el prÃ³ximo ejercicio fiscal';
COMMENT ON COLUMN cfpd06.ano IS 'Ejercicio fiscal';
COMMENT ON COLUMN cfpd06.cod_sector IS 'Sector de inversiÃ³n';
COMMENT ON COLUMN cfpd06.cod_programa IS 'Programa responsable';
COMMENT ON COLUMN cfpd06.cod_sub_prog IS 'Sub-programa responsable';
COMMENT ON COLUMN cfpd06.cod_proyecto IS 'Proyecto de adquisiciÃ³n';
COMMENT ON COLUMN cfpd06.cod_activ_obra IS 'Actividad u obra responsable';
COMMENT ON COLUMN cfpd06.cod_partida IS 'CÃ³digo de la partida';
COMMENT ON COLUMN cfpd06.cod_generica IS 'CÃ³digo de la genÃ©rica';
COMMENT ON COLUMN cfpd06.cod_especifica IS 'CÃ³digo de la especifica';
COMMENT ON COLUMN cfpd06.cod_sub_espec IS 'CÃ³digo de la Sub-especifica';
COMMENT ON COLUMN cfpd06.cod_auxiliar IS 'CÃ³digo del auxiliar';
COMMENT ON COLUMN cfpd06.cantidad_reemplazo IS 'Cantidad de equÃ­pos a reeplazar';
COMMENT ON COLUMN cfpd06.cantidad_deficiencia IS 'Cantidad de equÃ­pos por deficiencia a adquirir por la instituciÃ³n';
COMMENT ON COLUMN cfpd06.costo_unitario IS 'Costo unitario estimado de los equÃ­pos.';
COMMENT ON COLUMN cfpd06.denominacion IS 'DenominaciÃ³n de los equÃ­pos';


-- Index: cfpd06_index

-- DROP INDEX cfpd06_index;

CREATE INDEX cfpd06_index
  ON cfpd06
  USING btree
  (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, numero_linea);































-- Table: cfpd15

-- DROP TABLE cfpd15;

CREATE TABLE cfpd15
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano integer NOT NULL, -- Ejercicio fiscal
  cod_sector integer NOT NULL, -- Sector de inversiÃ³n
  cod_programa integer NOT NULL, -- Programa responsable
  cod_sub_prog integer NOT NULL, -- Sub-programa responsable
  cod_proyecto integer NOT NULL, -- Proyecto de adquisiciÃ³n
  cod_activ_obra integer NOT NULL, -- Actividad u obra responsable
  cod_partida integer NOT NULL, -- CÃ³digo de la partida
  cod_generica integer NOT NULL, -- CÃ³digo de la genÃ©rica
  cod_especifica integer NOT NULL, -- CÃ³digo de la especifica
  cod_sub_espec integer NOT NULL, -- CÃ³digo de la Sub-especifica
  cod_auxiliar integer NOT NULL, -- CÃ³digo del auxiliar
  numero_linea serial NOT NULL,
  programa_social text NOT NULL, -- Programa social
  organismo character varying(100) NOT NULL, -- Organismo
  asignacion_anual numeric(26,2) NOT NULL, -- Asignación social
  CONSTRAINT cfpd15_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, numero_linea)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd15 OWNER TO sisap;
COMMENT ON TABLE cfpd15 IS 'Registra los programas sociales';
COMMENT ON COLUMN cfpd15.ano IS 'Ejercicio fiscal';
COMMENT ON COLUMN cfpd15.cod_sector IS 'Sector de inversiÃ³n';
COMMENT ON COLUMN cfpd15.cod_programa IS 'Programa responsable';
COMMENT ON COLUMN cfpd15.cod_sub_prog IS 'Sub-programa responsable';
COMMENT ON COLUMN cfpd15.cod_proyecto IS 'Proyecto de adquisiciÃ³n';
COMMENT ON COLUMN cfpd15.cod_activ_obra IS 'Actividad u obra responsable';
COMMENT ON COLUMN cfpd15.cod_partida IS 'CÃ³digo de la partida';
COMMENT ON COLUMN cfpd15.cod_generica IS 'CÃ³digo de la genÃ©rica';
COMMENT ON COLUMN cfpd15.cod_especifica IS 'CÃ³digo de la especifica';
COMMENT ON COLUMN cfpd15.cod_sub_espec IS 'CÃ³digo de la Sub-especifica';
COMMENT ON COLUMN cfpd15.cod_auxiliar IS 'CÃ³digo del auxiliar';
COMMENT ON COLUMN cfpd15.programa_social IS 'Programa social';
COMMENT ON COLUMN cfpd15.organismo IS 'Organismo';
COMMENT ON COLUMN cfpd15.asignacion_anual IS 'Asignación social';


-- Index: cfpd15_index

-- DROP INDEX cfpd15_index;

CREATE INDEX cfpd15_index
  ON cfpd15
  USING btree
  (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, numero_linea);









































-- Table: cfpd16

-- DROP TABLE cfpd16;

CREATE TABLE cfpd16
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano integer NOT NULL, -- Ejercicio fiscal
  cod_sector integer NOT NULL, -- Sector de inversiÃ³n
  cod_programa integer NOT NULL, -- Programa responsable
  cod_sub_prog integer NOT NULL, -- Sub-programa responsable
  cod_proyecto integer NOT NULL, -- Proyecto de adquisiciÃ³n
  cod_activ_obra integer NOT NULL, -- Actividad u obra responsable
  cod_partida integer NOT NULL, -- CÃ³digo de la partida
  cod_generica integer NOT NULL, -- CÃ³digo de la genÃ©rica
  cod_especifica integer NOT NULL, -- CÃ³digo de la especifica
  cod_sub_espec integer NOT NULL, -- CÃ³digo de la Sub-especifica
  cod_auxiliar integer NOT NULL, -- CÃ³digo del auxiliar
  numero_linea serial NOT NULL,
  nombre_concejo_comunal character varying(100) NOT NULL, -- Nombre del consejo comunal o mancomunidad
  nombre_banco_comunal character varying(100) NOT NULL, -- Nombre del Banco comunal o Mancomunidad
  nombre_proyecto character varying(200) NOT NULL, -- Nombre del proyecto
  ente_financiante character varying(100) NOT NULL, -- Ente financiante
  denominacion_obra text NOT NULL, -- Denominación de la obra
  monto_obra numeric(26,2) NOT NULL, -- Monto de la obra
  CONSTRAINT cfpd16_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, numero_linea)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd16 OWNER TO sisap;
COMMENT ON TABLE cfpd16 IS 'Registra transferencias o Aportes a los Consejos comunales';
COMMENT ON COLUMN cfpd16.ano IS 'Ejercicio fiscal';
COMMENT ON COLUMN cfpd16.cod_sector IS 'Sector de inversiÃ³n';
COMMENT ON COLUMN cfpd16.cod_programa IS 'Programa responsable';
COMMENT ON COLUMN cfpd16.cod_sub_prog IS 'Sub-programa responsable';
COMMENT ON COLUMN cfpd16.cod_proyecto IS 'Proyecto de adquisiciÃ³n';
COMMENT ON COLUMN cfpd16.cod_activ_obra IS 'Actividad u obra responsable';
COMMENT ON COLUMN cfpd16.cod_partida IS 'CÃ³digo de la partida';
COMMENT ON COLUMN cfpd16.cod_generica IS 'CÃ³digo de la genÃ©rica';
COMMENT ON COLUMN cfpd16.cod_especifica IS 'CÃ³digo de la especifica';
COMMENT ON COLUMN cfpd16.cod_sub_espec IS 'CÃ³digo de la Sub-especifica';
COMMENT ON COLUMN cfpd16.cod_auxiliar IS 'CÃ³digo del auxiliar';
COMMENT ON COLUMN cfpd16.nombre_concejo_comunal IS 'Nombre del consejo comunal o mancomunidad';
COMMENT ON COLUMN cfpd16.nombre_banco_comunal IS 'Nombre del Banco comunal o Mancomunidad';
COMMENT ON COLUMN cfpd16.nombre_proyecto IS 'Nombre del proyecto';
COMMENT ON COLUMN cfpd16.ente_financiante IS 'Ente financiante';
COMMENT ON COLUMN cfpd16.denominacion_obra IS 'Denominación de la obra';
COMMENT ON COLUMN cfpd16.monto_obra IS 'Monto de la obra';


-- Index: cfpd16_index

-- DROP INDEX cfpd16_index;

CREATE INDEX cfpd16_index
  ON cfpd16
  USING btree
  (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, numero_linea);





























-- Table: cfpd17_inversion_coordinada

-- DROP TABLE cfpd17_inversion_coordinada;

CREATE TABLE cfpd17_inversion_coordinada
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_estado integer NOT NULL, -- Código del Estado
  cod_organismo integer NOT NULL, -- Código del Organismo
  cod_municipio integer NOT NULL, -- Código del Municpio
  ano integer NOT NULL, -- Ejercicio fiscal
  cod_sector integer NOT NULL, -- Sector de inversiÃ³n
  cod_programa integer NOT NULL, -- Programa responsable
  cod_sub_prog integer NOT NULL, -- Sub-programa responsable
  cod_proyecto integer NOT NULL, -- Proyecto de adquisiciÃ³n
  cod_activ_obra integer NOT NULL, -- Actividad u obra responsable
  cod_partida integer NOT NULL, -- CÃ³digo de la partida
  cod_generica integer NOT NULL, -- CÃ³digo de la genÃ©rica
  cod_especifica integer NOT NULL, -- CÃ³digo de la especifica
  cod_sub_espec integer NOT NULL, -- CÃ³digo de la Sub-especifica
  aporte_municipio numeric(26,2) NOT NULL, -- Aporte del Municipio
  aporte_organismo numeric(26,2) NOT NULL, -- Aporte del Organismo
  aporte_gobernacion numeric(26,2) NOT NULL, -- Aporte de la Gobernación
  CONSTRAINT cfpd17_inversion_coordinada_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_estado, cod_organismo, cod_municipio, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd17_inversion_coordinada OWNER TO sisap;
COMMENT ON TABLE cfpd17_inversion_coordinada IS 'Registra los créditos presupuestarios asignados a los programas de inversión en coordinación con organismos del sector público';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_estado IS 'Código del Estado';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_organismo IS 'Código del Organismo';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_municipio IS 'Código del Municpio';
COMMENT ON COLUMN cfpd17_inversion_coordinada.ano IS 'Ejercicio fiscal';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_sector IS 'Sector de inversiÃ³n';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_programa IS 'Programa responsable';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_sub_prog IS 'Sub-programa responsable';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_proyecto IS 'Proyecto de adquisiciÃ³n';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_activ_obra IS 'Actividad u obra responsable';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_partida IS 'CÃ³digo de la partida';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_generica IS 'CÃ³digo de la genÃ©rica';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_especifica IS 'CÃ³digo de la especifica';
COMMENT ON COLUMN cfpd17_inversion_coordinada.cod_sub_espec IS 'CÃ³digo de la Sub-especifica';
COMMENT ON COLUMN cfpd17_inversion_coordinada.aporte_municipio IS 'Aporte del Municipio';
COMMENT ON COLUMN cfpd17_inversion_coordinada.aporte_organismo IS 'Aporte del Organismo';
COMMENT ON COLUMN cfpd17_inversion_coordinada.aporte_gobernacion IS 'Aporte de la Gobernación';































-- Table: cfpd17_inversion_coordinada_estado

-- DROP TABLE cfpd17_inversion_coordinada_estado;

CREATE TABLE cfpd17_inversion_coordinada_estado
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_estado integer NOT NULL, -- Código del Estado
  denominacion character varying(100) NOT NULL,
  CONSTRAINT cfpd17_inversion_coordinada_estado_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_estado)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd17_inversion_coordinada_estado OWNER TO sisap;
COMMENT ON TABLE cfpd17_inversion_coordinada_estado IS 'Registra los créditos presupuestarios asignados a los programas de inversión en coordinación con organismos del sector público';
COMMENT ON COLUMN cfpd17_inversion_coordinada_estado.cod_estado IS 'Código del Estado';



























-- Table: cfpd17_inversion_coordinada_municipio

-- DROP TABLE cfpd17_inversion_coordinada_municipio;

CREATE TABLE cfpd17_inversion_coordinada_municipio
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_municipio integer NOT NULL, -- Código del Municipio
  denominacion character varying(100) NOT NULL,
  CONSTRAINT cfpd17_inversion_coordinada_municipio_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_municipio)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd17_inversion_coordinada_municipio OWNER TO sisap;
COMMENT ON TABLE cfpd17_inversion_coordinada_municipio IS 'Registra los créditos presupuestarios asignados a los programas de inversión en coordinación con organismos del sector público';
COMMENT ON COLUMN cfpd17_inversion_coordinada_municipio.cod_municipio IS 'Código del Municipio';



























-- Table: cfpd17_inversion_coordinada_organismo

-- DROP TABLE cfpd17_inversion_coordinada_organismo;

CREATE TABLE cfpd17_inversion_coordinada_organismo
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_organismo integer NOT NULL, -- Código del organismo
  denominacion character varying(100) NOT NULL,
  CONSTRAINT cfpd17_inversion_coordinada_organismo_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_organismo)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd17_inversion_coordinada_organismo OWNER TO sisap;
COMMENT ON TABLE cfpd17_inversion_coordinada_organismo IS 'Registra los créditos presupuestarios asignados a los programas de inversión en coordinación con organismos del sector público';
COMMENT ON COLUMN cfpd17_inversion_coordinada_organismo.cod_organismo IS 'Código del organismo';



























-- Table: cfpd18_contrato_colectivo_clausulas

-- DROP TABLE cfpd18_contrato_colectivo_clausulas;

CREATE TABLE cfpd18_contrato_colectivo_clausulas
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_sindicato integer NOT NULL, -- Código del Sindicato
  ano_formulacion integer NOT NULL, -- Año de formulación
  cod_clausula integer NOT NULL, -- Código de la clausula
  denominacion_clausula character varying(100) NOT NULL, -- Denominación de la clausula
  CONSTRAINT cfpd18_contrato_colectivo_clausulas_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_sindicato, ano_formulacion, cod_clausula)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd18_contrato_colectivo_clausulas OWNER TO sisap;
COMMENT ON TABLE cfpd18_contrato_colectivo_clausulas IS 'Registra las Clausuals de acuerdo al Sindicato que las discute';
COMMENT ON COLUMN cfpd18_contrato_colectivo_clausulas.cod_sindicato IS 'Código del Sindicato';
COMMENT ON COLUMN cfpd18_contrato_colectivo_clausulas.ano_formulacion IS 'Año de formulación';
COMMENT ON COLUMN cfpd18_contrato_colectivo_clausulas.cod_clausula IS 'Código de la clausula';
COMMENT ON COLUMN cfpd18_contrato_colectivo_clausulas.denominacion_clausula IS 'Denominación de la clausula';

















-- Table: cfpd18_contrato_colectivo_cuerpo

-- DROP TABLE cfpd18_contrato_colectivo_cuerpo;

CREATE TABLE cfpd18_contrato_colectivo_cuerpo
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano_formulacion integer NOT NULL, -- Año de formulación
  cod_sindicato integer NOT NULL, -- Código del Sindicato
  fecha_contrato_inicio date NOT NULL, -- Fecha de inicio del contrato
  fecha_contrato_conclusion date NOT NULL, -- Fecha de conclusión del contrato
  CONSTRAINT cfpd18_contrato_colectivo_cuerpo_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_formulacion, cod_sindicato)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd18_contrato_colectivo_cuerpo OWNER TO sisap;
COMMENT ON TABLE cfpd18_contrato_colectivo_cuerpo IS 'Registra cuerpo del contrato por sindicato';
COMMENT ON COLUMN cfpd18_contrato_colectivo_cuerpo.ano_formulacion IS 'Año de formulación';
COMMENT ON COLUMN cfpd18_contrato_colectivo_cuerpo.cod_sindicato IS 'Código del Sindicato';
COMMENT ON COLUMN cfpd18_contrato_colectivo_cuerpo.fecha_contrato_inicio IS 'Fecha de inicio del contrato';
COMMENT ON COLUMN cfpd18_contrato_colectivo_cuerpo.fecha_contrato_conclusion IS 'Fecha de conclusión del contrato';


















-- Table: cfpd18_contrato_colectivo_detalles

-- DROP TABLE cfpd18_contrato_colectivo_detalles;

CREATE TABLE cfpd18_contrato_colectivo_detalles
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano_formulacion integer NOT NULL, -- Año de formulación
  cod_sindicato integer NOT NULL, -- Código del Sindicato
  cod_clausula integer NOT NULL, -- Código de la clausula
  cod_partida integer NOT NULL, -- Código de la partida
  cod_generica integer NOT NULL, -- Código de la generica
  cod_especifica integer NOT NULL, -- Código de la especifica
  cod_sub_espec integer NOT NULL, -- Código de la Sub especifica
  revisado_anterior numeric(26,2) NOT NULL, -- Monto revisado anterior
  presupuestado_actual numeric(26,2) NOT NULL, -- Monto presupuestado actual
  base_calculo character varying(50) NOT NULL, -- Base de Cálculo
  CONSTRAINT cfpd18_contrato_colectivo_detalles_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_formulacion, cod_sindicato, cod_clausula)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd18_contrato_colectivo_detalles OWNER TO sisap;
COMMENT ON TABLE cfpd18_contrato_colectivo_detalles IS 'Registra cuerpo del contrato por sindicato';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.ano_formulacion IS 'Año de formulación';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.cod_sindicato IS 'Código del Sindicato';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.cod_clausula IS 'Código de la clausula';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.cod_partida IS 'Código de la partida';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.cod_generica IS 'Código de la generica';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.cod_especifica IS 'Código de la especifica';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.cod_sub_espec IS 'Código de la Sub especifica';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.revisado_anterior IS 'Monto revisado anterior';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.presupuestado_actual IS 'Monto presupuestado actual';
COMMENT ON COLUMN cfpd18_contrato_colectivo_detalles.base_calculo IS 'Base de Cálculo';

-- Table: cfpd18_contrato_colectivo_sindicato

-- DROP TABLE cfpd18_contrato_colectivo_sindicato;

CREATE TABLE cfpd18_contrato_colectivo_sindicato
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  cod_sindicato integer NOT NULL, -- Código del Sindicato
  denominacion character varying(100) NOT NULL,
  CONSTRAINT cfpd18_contrato_colectivo_sindicato_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_sindicato)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd18_contrato_colectivo_sindicato OWNER TO sisap;
COMMENT ON TABLE cfpd18_contrato_colectivo_sindicato IS 'Registra los Sindicatos';
COMMENT ON COLUMN cfpd18_contrato_colectivo_sindicato.cod_sindicato IS 'Código del Sindicato';





























-- Table: cfpd19_participacion_financiera

-- DROP TABLE cfpd19_participacion_financiera;

CREATE TABLE cfpd19_participacion_financiera
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano_formulacion integer NOT NULL, -- Año de formulación
  numero integer NOT NULL
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd19_participacion_financiera OWNER TO sisap;
COMMENT ON TABLE cfpd19_participacion_financiera IS 'Registra las participaciones financieras';
COMMENT ON COLUMN cfpd19_participacion_financiera.ano_formulacion IS 'Año de formulación';






























-- Table: cfpd19_participacion_financiera

-- DROP TABLE cfpd19_participacion_financiera;

CREATE TABLE cfpd19_participacion_financiera
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano_formulacion integer NOT NULL, -- Año de formulación
  numero integer NOT NULL, -- Número consecutivo
  nombre character varying(100) NOT NULL, -- Nombre
  ubicacion_geografica character varying(100) NOT NULL, -- Ubicación geográfica
  tipo character varying(100) NOT NULL, -- Tipo
  capital_social numeric(26,2) NOT NULL, -- Capital social
  cuota_participacion numeric(26,2) NOT NULL, -- Cuota de participación
  porcentaje numeric(3,2) NOT NULL, -- Porcentaje
  cod_sector integer NOT NULL, -- Código del sector
  cod_programa integer NOT NULL, -- Código del programa
  cod_sub_prog integer NOT NULL, -- Código del sub programa
  cod_partida integer NOT NULL, -- Código de partida
  observaciones text NOT NULL, -- observaciones
  CONSTRAINT cfpd19_participacion_financiera_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_formulacion, numero)
)
WITH (OIDS=FALSE);
ALTER TABLE cfpd19_participacion_financiera OWNER TO sisap;
COMMENT ON TABLE cfpd19_participacion_financiera IS 'Registra las participaciones financieras';
COMMENT ON COLUMN cfpd19_participacion_financiera.ano_formulacion IS 'Año de formulación';
COMMENT ON COLUMN cfpd19_participacion_financiera.numero IS 'Número consecutivo';
COMMENT ON COLUMN cfpd19_participacion_financiera.nombre IS 'Nombre';
COMMENT ON COLUMN cfpd19_participacion_financiera.ubicacion_geografica IS 'Ubicación geográfica';
COMMENT ON COLUMN cfpd19_participacion_financiera.tipo IS 'Tipo';
COMMENT ON COLUMN cfpd19_participacion_financiera.capital_social IS 'Capital social';
COMMENT ON COLUMN cfpd19_participacion_financiera.cuota_participacion IS 'Cuota de participación';
COMMENT ON COLUMN cfpd19_participacion_financiera.porcentaje IS 'Porcentaje';
COMMENT ON COLUMN cfpd19_participacion_financiera.cod_sector IS 'Código del sector';
COMMENT ON COLUMN cfpd19_participacion_financiera.cod_programa IS 'Código del programa';
COMMENT ON COLUMN cfpd19_participacion_financiera.cod_sub_prog IS 'Código del sub programa';
COMMENT ON COLUMN cfpd19_participacion_financiera.cod_partida IS 'Código de partida';
COMMENT ON COLUMN cfpd19_participacion_financiera.observaciones IS 'observaciones';