-- Table: ccnd01_tipo_directivo

-- DROP TABLE ccnd01_tipo_directivo;

CREATE TABLE ccnd01_tipo_directivo
(
  cod_tipo int4 NOT NULL, -- Código tipo de directivo
  denominacion varchar(200) NOT NULL, -- Denominación tipo de directivo
  CONSTRAINT ccnd01_tipo_directivo_pkey PRIMARY KEY (cod_tipo)
)
WITHOUT OIDS;
ALTER TABLE ccnd01_tipo_directivo OWNER TO sisap;
COMMENT ON TABLE ccnd01_tipo_directivo IS 'Registro de tipo de directivo
';
COMMENT ON COLUMN ccnd01_tipo_directivo.cod_tipo IS 'Código tipo de directivo
01.- Banco comunal
02.- Comité de salud
03.- Comité de educación
04.- Comité de tierra urbana o rural
05.- Comité de vivienda y hábitat
06.- Comité de protección e igual social
07.- Comité de economía popular
08.- Comité de cultura
09.- Comité de seguridad integral
10.- Comité de medios de comunicación e información
11.- Comité de recreación y deportes
12.- Comité de Alimentación
13.- Mesa técnica de agua
14.- Mesa técnica de energía y gas
15.- Comité de servicios
';
COMMENT ON COLUMN ccnd01_tipo_directivo.denominacion IS 'Denominación tipo de directivo';



-- Table: ccnd01_cargos_directivos

-- DROP TABLE ccnd01_cargos_directivos;

CREATE TABLE ccnd01_cargos_directivos
(
  cod_tipo int4 NOT NULL, -- Código tipo de directivo
  cod_cargo int4 NOT NULL, -- Código cargo directivo
  denominacion varchar(200) NOT NULL, -- Denominación del cargo directivo
  CONSTRAINT ccnd01_cargos_directivos_pkey PRIMARY KEY (cod_tipo, cod_cargo),
  CONSTRAINT ccnd01_cargos_directivos_1 FOREIGN KEY (cod_tipo)
      REFERENCES ccnd01_tipo_directivo (cod_tipo) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd01_cargos_directivos OWNER TO sisap;
COMMENT ON TABLE ccnd01_cargos_directivos IS 'Registro de los cargos directivos de los concejos comunales
';
COMMENT ON COLUMN ccnd01_cargos_directivos.cod_tipo IS 'Código tipo de directivo
';
COMMENT ON COLUMN ccnd01_cargos_directivos.cod_cargo IS 'Código cargo directivo
';
COMMENT ON COLUMN ccnd01_cargos_directivos.denominacion IS 'Denominación del cargo directivo';




















-- Table: ccnd01_concejo_comunal

-- DROP TABLE ccnd01_concejo_comunal;

CREATE TABLE ccnd01_concejo_comunal
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  denominacion varchar(200) NOT NULL, -- Denominación del concejo comunal
  tipo_zona int4 NOT NULL, -- Tipo de zona
  fecha_inicio date NOT NULL, -- Fecha de inicio
  fecha_terminacion date NOT NULL, -- Fecha de terminación
  numero_electores int4 NOT NULL, -- Número de electores
  numero_votantes int4 NOT NULL, -- Número de votantes
  resultado int4 NOT NULL, -- Resultado
  CONSTRAINT ccnd01_concejo_comunal_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo)
)
WITHOUT OIDS;
ALTER TABLE ccnd01_concejo_comunal OWNER TO sisap;
COMMENT ON TABLE ccnd01_concejo_comunal IS 'Registro de los concejos comunales';
COMMENT ON COLUMN ccnd01_concejo_comunal.cod_republica IS 'Código de la república';
COMMENT ON COLUMN ccnd01_concejo_comunal.cod_estado IS 'Código del estado';
COMMENT ON COLUMN ccnd01_concejo_comunal.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN ccnd01_concejo_comunal.cod_parroquia IS 'Código de la parroquia';
COMMENT ON COLUMN ccnd01_concejo_comunal.cod_centro IS 'Código del centro poblado';
COMMENT ON COLUMN ccnd01_concejo_comunal.cod_concejo IS 'Código del concejo comunal';
COMMENT ON COLUMN ccnd01_concejo_comunal.denominacion IS 'Denominación del concejo comunal';
COMMENT ON COLUMN ccnd01_concejo_comunal.tipo_zona IS 'Tipo de zona
1.- Urbanización
2.- Barrio
3.- Caserio
4.- Comunca
5.- Vialidad

';
COMMENT ON COLUMN ccnd01_concejo_comunal.fecha_inicio IS 'Fecha de inicio';
COMMENT ON COLUMN ccnd01_concejo_comunal.fecha_terminacion IS 'Fecha de terminación
';
COMMENT ON COLUMN ccnd01_concejo_comunal.numero_electores IS 'Número de electores
';
COMMENT ON COLUMN ccnd01_concejo_comunal.numero_votantes IS 'Número de votantes
';
COMMENT ON COLUMN ccnd01_concejo_comunal.resultado IS 'Resultado';



































-- Table: ccnd01_directiva

-- DROP TABLE ccnd01_directiva;

CREATE TABLE ccnd01_directiva
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código concejo comunal
  cod_tipo int4 NOT NULL, -- Código tipo de directivo
  cod_cargo int4 NOT NULL, -- Código de cargo del directivo
  cedula_identidad int4 NOT NULL, -- Cédula de identidad
  nacionalidad varchar(1) NOT NULL, -- V=Venezolana
  apellidos_nombres varchar(100) NOT NULL, -- Apellidos y Nombres
  fecha_nacimiento date NOT NULL, -- Fecha de nacimiento
  sexo varchar(1) NOT NULL, -- M=Masculino
  estado_civil varchar(1) NOT NULL, -- Estado civil
  peso int4, -- Peso en kilogramos
  estatura numeric(5,2), -- Estatura en metros
  grupo_sanguineo varchar(20), -- Grupo sanguineo
  cod_profesion int4 NOT NULL, -- Código de la profesión
  cod_ocupacion int4 NOT NULL, -- Código de la ocupación
  cod_vivienda int4 NOT NULL, -- Código tipo de vivienda
  cod_tenencia_vivienda int4 NOT NULL, -- Código tenencia de la vivienda
  anos_residencia int4 NOT NULL, -- Años de residencia
  monto_alquiler_hipoteca numeric(26,2) NOT NULL, -- Monto del alquiler o de la hipoteca
  cod_mision int4 NOT NULL, -- Código de misión
  direccion_habitacion text NOT NULL, -- Direccion de habitación
  telefonos_fijos varchar(50), -- Teléfonos fijos
  telefonos_moviles varchar(50), -- Teléfonos moviles
  estado_conservacion_vivienda varchar(1), -- Estado de conservación de la vivienda
  CONSTRAINT ccnd01_directiva_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_tipo, cod_cargo, cedula_identidad)
)
WITHOUT OIDS;
ALTER TABLE ccnd01_directiva OWNER TO sisap;
COMMENT ON TABLE ccnd01_directiva IS 'Registros de datos personales de la directiva';
COMMENT ON COLUMN ccnd01_directiva.cod_republica IS 'Código de la república
';
COMMENT ON COLUMN ccnd01_directiva.cod_estado IS 'Código del estado
';
COMMENT ON COLUMN ccnd01_directiva.cod_municipio IS 'Código del municipio
';
COMMENT ON COLUMN ccnd01_directiva.cod_parroquia IS 'Código de parroquia
';
COMMENT ON COLUMN ccnd01_directiva.cod_centro IS 'Código del centro poblado
';
COMMENT ON COLUMN ccnd01_directiva.cod_concejo IS 'Código concejo comunal
';
COMMENT ON COLUMN ccnd01_directiva.cod_tipo IS 'Código tipo de directivo
';
COMMENT ON COLUMN ccnd01_directiva.cod_cargo IS 'Código de cargo del directivo';
COMMENT ON COLUMN ccnd01_directiva.cedula_identidad IS 'Cédula de identidad
';
COMMENT ON COLUMN ccnd01_directiva.nacionalidad IS 'V=Venezolana
E=Extranjera
';
COMMENT ON COLUMN ccnd01_directiva.apellidos_nombres IS 'Apellidos y Nombres';
COMMENT ON COLUMN ccnd01_directiva.fecha_nacimiento IS 'Fecha de nacimiento
';
COMMENT ON COLUMN ccnd01_directiva.sexo IS 'M=Masculino
F=Femenino
';
COMMENT ON COLUMN ccnd01_directiva.estado_civil IS 'Estado civil
S=Soltero
C=Casado
D=Divorciado
V=Viudo
O=Otro';
COMMENT ON COLUMN ccnd01_directiva.peso IS 'Peso en kilogramos
';
COMMENT ON COLUMN ccnd01_directiva.estatura IS 'Estatura en metros';
COMMENT ON COLUMN ccnd01_directiva.grupo_sanguineo IS 'Grupo sanguineo
';
COMMENT ON COLUMN ccnd01_directiva.cod_profesion IS 'Código de la profesión
';
COMMENT ON COLUMN ccnd01_directiva.cod_ocupacion IS 'Código de la ocupación
';
COMMENT ON COLUMN ccnd01_directiva.cod_vivienda IS 'Código tipo de vivienda
1.- Quinta
2.- Casa/Quinta
3.- Casa manposteria
4.- Apartamento
5.- Vivienda popular
6.- Rancho
';
COMMENT ON COLUMN ccnd01_directiva.cod_tenencia_vivienda IS 'Código tenencia de la vivienda
1.- Propia
2- Alquilada
3.- De un familiar
4.- Al cuidado
5.- Hipotecada
6.- Invadida
';
COMMENT ON COLUMN ccnd01_directiva.anos_residencia IS 'Años de residencia
';
COMMENT ON COLUMN ccnd01_directiva.monto_alquiler_hipoteca IS 'Monto del alquiler o de la hipoteca
';
COMMENT ON COLUMN ccnd01_directiva.cod_mision IS 'Código de misión
1.- Ninguna
2.- Robinsón I
3.- Robinsón II
4.- Ribas
5.- Sucre
6.- Negra hipolita
7.- José Gregorio Hernandez
8.- Barrio adentro
9.- Mercal
10.- Arbol
11.- Ciencia
12.- Miranda
13.- Guacaipuro
14.- Piar
15.- Vuelvan caras
16.- Identidad
17.- Che Guevara
18.- Cultura
19.- Esperanza
20.- Habitat
21.- Madre del barrio
22.- Milagro
23.- Niños y niñas del barrio
24.- Zamora
';
COMMENT ON COLUMN ccnd01_directiva.direccion_habitacion IS 'Direccion de habitación';
COMMENT ON COLUMN ccnd01_directiva.telefonos_fijos IS 'Teléfonos fijos
';
COMMENT ON COLUMN ccnd01_directiva.telefonos_moviles IS 'Teléfonos moviles
';
COMMENT ON COLUMN ccnd01_directiva.estado_conservacion_vivienda IS 'Estado de conservación de la vivienda
E=Excelente
B=Buena
R=Regular
M=Mala
';




























-- Table: ccnd01_directiva_familiar

-- DROP TABLE ccnd01_directiva_familiar;

CREATE TABLE ccnd01_directiva_familiar
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  cod_tipo int4 NOT NULL, -- Código tipo de directivo
  cod_cargo int4 NOT NULL, -- Código del cargo
  cedula_directivo int4 NOT NULL, -- Cédula del directivo
  cod_parentesco int4 NOT NULL, -- Código de parentesco
  cedula_pariente int4 NOT NULL, -- Cédula del pariente
  apellidos_nombres varchar(100) NOT NULL, -- Apellidos y nombres del pariente
  fecha_nacimiento date, -- Fecha de nacimiento del pariente
  sexo varchar(1) NOT NULL, -- Sexo
  trabaja varchar(1), -- Trabaja ?
  estudia varchar(1), -- Estudia ?
  CONSTRAINT ccnd01_directiva_familiar_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_tipo, cod_cargo, cedula_directivo, cod_parentesco, cedula_pariente),
  CONSTRAINT ccnd01_directiva_familiar_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_tipo, cod_cargo, cedula_directivo)
      REFERENCES ccnd01_directiva (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_tipo, cod_cargo, cedula_identidad) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd01_directiva_familiar OWNER TO sisap;
COMMENT ON TABLE ccnd01_directiva_familiar IS 'Registro de familiares de los directivos del concejo comunal';
COMMENT ON COLUMN ccnd01_directiva_familiar.cod_republica IS 'Código de la república
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cod_estado IS 'Código del estado
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cod_municipio IS 'Código del municipio
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cod_parroquia IS 'Código de la parroquia
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cod_centro IS 'Código del centro poblado
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cod_concejo IS 'Código del concejo comunal
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cod_tipo IS 'Código tipo de directivo
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cod_cargo IS 'Código del cargo
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cedula_directivo IS 'Cédula del directivo
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cod_parentesco IS 'Código de parentesco
';
COMMENT ON COLUMN ccnd01_directiva_familiar.cedula_pariente IS 'Cédula del pariente
';
COMMENT ON COLUMN ccnd01_directiva_familiar.apellidos_nombres IS 'Apellidos y nombres del pariente
';
COMMENT ON COLUMN ccnd01_directiva_familiar.fecha_nacimiento IS 'Fecha de nacimiento del pariente
';
COMMENT ON COLUMN ccnd01_directiva_familiar.sexo IS 'Sexo
M=Masculino
F=Femenino
';
COMMENT ON COLUMN ccnd01_directiva_familiar.trabaja IS 'Trabaja ?
S=Si
N=No
';
COMMENT ON COLUMN ccnd01_directiva_familiar.estudia IS 'Estudia ?
S=Si
N=No
';




















-- Table: ccnd02_proyectos

-- DROP TABLE ccnd02_proyectos;

CREATE TABLE ccnd02_proyectos
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL,
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  ano int4 NOT NULL, -- Año del proyecto
  cod_proyecto varchar(30) NOT NULL, -- Código de proyecto
  fecha_proyecto date NOT NULL, -- Fecha del proyecto
  responsable_proyecto varchar(100) NOT NULL, -- Responsable del proyecto
  cedula_identidad int4 NOT NULL, -- Cédula de identidad
  cargo varchar(100) NOT NULL, -- Cargo que ocupa
  nombre_proyecto text NOT NULL,
  lugar_ejecucion text NOT NULL, -- Lugar de ejecución
  duracion_proyecto varchar(200) NOT NULL, -- Duración del proyecto
  costo_proyecto numeric(26,2) NOT NULL, -- Costo del proyecto
  identificacion_problema text NOT NULL, -- Identificación del problema
  diagnostico_situacion text NOT NULL, -- Diagnostico situación actual
  formulacion_alternativa text NOT NULL, -- Formulación de alternativas
  sintesis_propuesta text NOT NULL, -- Sintesis de la propuesta
  objetivo_general text NOT NULL, -- Objectivo general
  "objectivos especificos" text NOT NULL, -- Objectivos especificos
  metas_fisicas text NOT NULL, -- Metas fisícas
  obra text NOT NULL, -- Denominación de la obra
  responsable varchar(100) NOT NULL, -- Responsable
  iva_aplicado numeric(5,2) NOT NULL, -- Iva aplicado en el proyecto
  plan_ejecucion text NOT NULL, -- Plan ejecución
  plan_desembolso text NOT NULL, -- Plan de desembolso
  rendimiento_proyecto numeric(26,2) NOT NULL, -- Rendimiento del proyecto
  resultados_inmediatos text NOT NULL, -- Resultados inmediatos
  resultados_mediano_plazo text NOT NULL, -- Resultados a mediano plazo
  impacto_economico text NOT NULL, -- Impacto economico
  impacto_social text NOT NULL, -- Impacto social
  impacto_ambiental text NOT NULL, -- Impacto ambiental
  beneficiarios text NOT NULL, -- Beneficiarios
  empleos_generados text NOT NULL, -- Empleados generados directos o indirectos
  CONSTRAINT ccnd02_proyectos_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
)
WITHOUT OIDS;
ALTER TABLE ccnd02_proyectos OWNER TO sisap;
COMMENT ON TABLE ccnd02_proyectos IS 'Registro de proyectos de los concejos comunales
';
COMMENT ON COLUMN ccnd02_proyectos.cod_republica IS 'Código de la república';
COMMENT ON COLUMN ccnd02_proyectos.cod_estado IS 'Código del estado';
COMMENT ON COLUMN ccnd02_proyectos.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN ccnd02_proyectos.cod_centro IS 'Código del centro poblado';
COMMENT ON COLUMN ccnd02_proyectos.cod_concejo IS 'Código del concejo comunal';
COMMENT ON COLUMN ccnd02_proyectos.ano IS 'Año del proyecto';
COMMENT ON COLUMN ccnd02_proyectos.cod_proyecto IS 'Código de proyecto';
COMMENT ON COLUMN ccnd02_proyectos.fecha_proyecto IS 'Fecha del proyecto';
COMMENT ON COLUMN ccnd02_proyectos.responsable_proyecto IS 'Responsable del proyecto';
COMMENT ON COLUMN ccnd02_proyectos.cedula_identidad IS 'Cédula de identidad';
COMMENT ON COLUMN ccnd02_proyectos.cargo IS 'Cargo que ocupa';
COMMENT ON COLUMN ccnd02_proyectos.lugar_ejecucion IS 'Lugar de ejecución';
COMMENT ON COLUMN ccnd02_proyectos.duracion_proyecto IS 'Duración del proyecto';
COMMENT ON COLUMN ccnd02_proyectos.costo_proyecto IS 'Costo del proyecto';
COMMENT ON COLUMN ccnd02_proyectos.identificacion_problema IS 'Identificación del problema';
COMMENT ON COLUMN ccnd02_proyectos.diagnostico_situacion IS 'Diagnostico situación actual';
COMMENT ON COLUMN ccnd02_proyectos.formulacion_alternativa IS 'Formulación de alternativas
';
COMMENT ON COLUMN ccnd02_proyectos.sintesis_propuesta IS 'Sintesis de la propuesta';
COMMENT ON COLUMN ccnd02_proyectos.objetivo_general IS 'Objectivo general';
COMMENT ON COLUMN ccnd02_proyectos."objectivos especificos" IS 'Objectivos especificos';
COMMENT ON COLUMN ccnd02_proyectos.metas_fisicas IS 'Metas fisícas';
COMMENT ON COLUMN ccnd02_proyectos.obra IS 'Denominación de la obra';
COMMENT ON COLUMN ccnd02_proyectos.responsable IS 'Responsable';
COMMENT ON COLUMN ccnd02_proyectos.iva_aplicado IS 'Iva aplicado en el proyecto';
COMMENT ON COLUMN ccnd02_proyectos.plan_ejecucion IS 'Plan ejecución';
COMMENT ON COLUMN ccnd02_proyectos.plan_desembolso IS 'Plan de desembolso
';
COMMENT ON COLUMN ccnd02_proyectos.rendimiento_proyecto IS 'Rendimiento del proyecto';
COMMENT ON COLUMN ccnd02_proyectos.resultados_inmediatos IS 'Resultados inmediatos
';
COMMENT ON COLUMN ccnd02_proyectos.resultados_mediano_plazo IS 'Resultados a mediano plazo
';
COMMENT ON COLUMN ccnd02_proyectos.impacto_economico IS 'Impacto economico';
COMMENT ON COLUMN ccnd02_proyectos.impacto_social IS 'Impacto social';
COMMENT ON COLUMN ccnd02_proyectos.impacto_ambiental IS 'Impacto ambiental
';
COMMENT ON COLUMN ccnd02_proyectos.beneficiarios IS 'Beneficiarios
';
COMMENT ON COLUMN ccnd02_proyectos.empleos_generados IS 'Empleados generados directos o indirectos
';














-- Table: ccnd02_proyectos_actividad_desembolso

-- DROP TABLE ccnd02_proyectos_actividad_desembolso;

CREATE TABLE ccnd02_proyectos_actividad_desembolso
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código de municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  ano int4 NOT NULL, -- Año del proyecto
  cod_proyecto varchar(30) NOT NULL, -- Código del proyecto
  cod_actividad int4 NOT NULL, -- Código de la actividad
  numero_semana int4 NOT NULL, -- Número de semana
  porcentaje numeric(5,2) NOT NULL, -- Porcentaje
  monto numeric(26,2) NOT NULL, -- Monto del desembolso
  CONSTRAINT ccnd02_proyectos_actividad_desembolso_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto, cod_actividad, numero_semana),
  CONSTRAINT ccnd02_proyectos_actividad_desembolso_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
      REFERENCES ccnd02_proyectos (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
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
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.numero_semana IS 'Número de semana';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.porcentaje IS 'Porcentaje';
COMMENT ON COLUMN ccnd02_proyectos_actividad_desembolso.monto IS 'Monto del desembolso';





























-- Table: ccnd02_proyectos_actividad_ejecucion

-- DROP TABLE ccnd02_proyectos_actividad_ejecucion;

CREATE TABLE ccnd02_proyectos_actividad_ejecucion
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de parroquia
  cod_centro int4 NOT NULL, -- Código centro poblado
  cod_concejo int4 NOT NULL, -- Código de concejo comunal
  ano int4 NOT NULL, -- Año del proyecto
  cod_proyecto varchar(30) NOT NULL, -- Código del proyecto
  cod_actividad int4 NOT NULL, -- Código de actividad
  semanas varchar(52),
  CONSTRAINT ccnd02_proyectos_actividad_ejecucion_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto, cod_actividad),
  CONSTRAINT ccnd02_proyectos_actividad_ejecucion_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
      REFERENCES ccnd02_proyectos (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd02_proyectos_actividad_ejecucion OWNER TO sisap;
COMMENT ON TABLE ccnd02_proyectos_actividad_ejecucion IS 'Registra la actividad de ejecucion del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_ejecucion.cod_republica IS 'Código de la república';
COMMENT ON COLUMN ccnd02_proyectos_actividad_ejecucion.cod_estado IS 'Código del estado';
COMMENT ON COLUMN ccnd02_proyectos_actividad_ejecucion.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN ccnd02_proyectos_actividad_ejecucion.cod_parroquia IS 'Código de parroquia';
COMMENT ON COLUMN ccnd02_proyectos_actividad_ejecucion.cod_centro IS 'Código centro poblado';
COMMENT ON COLUMN ccnd02_proyectos_actividad_ejecucion.cod_concejo IS 'Código de concejo comunal';
COMMENT ON COLUMN ccnd02_proyectos_actividad_ejecucion.ano IS 'Año del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_ejecucion.cod_proyecto IS 'Código del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_ejecucion.cod_actividad IS 'Código de actividad';








































-- Table: ccnd02_proyectos_actividad_equipos

-- DROP TABLE ccnd02_proyectos_actividad_equipos;

CREATE TABLE ccnd02_proyectos_actividad_equipos
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  ano int4 NOT NULL, -- Año del proyecto
  cod_proyecto varchar(30) NOT NULL, -- Código del proyecto
  cod_actividad int4 NOT NULL, -- Código de la actividad
  numero_renglon int4 NOT NULL, -- Número de renglón
  denominacion_equipo text NOT NULL, -- Denominación de los equipos
  costo numeric NOT NULL, -- Costo unitario de los equipos
  CONSTRAINT ccnd02_proyectos_actividad_equipos_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto, cod_actividad, numero_renglon),
  CONSTRAINT ccnd02_proyectos_actividad_equipos FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
      REFERENCES ccnd02_proyectos (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd02_proyectos_actividad_equipos OWNER TO sisap;
COMMENT ON TABLE ccnd02_proyectos_actividad_equipos IS 'Registro de los equipos de las actividades del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.cod_republica IS 'Código de la república
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.cod_estado IS 'Código del estado
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.cod_municipio IS 'Código del municipio
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.cod_parroquia IS 'Código de la parroquia
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.cod_centro IS 'Código del centro poblado
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.cod_concejo IS 'Código del concejo comunal
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.ano IS 'Año del proyecto
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.cod_proyecto IS 'Código del proyecto
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.cod_actividad IS 'Código de la actividad
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.numero_renglon IS 'Número de renglón
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.denominacion_equipo IS 'Denominación de los equipos
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_equipos.costo IS 'Costo unitario de los equipos
';

















-- Table: ccnd02_proyectos_actividad_manoobra

-- DROP TABLE ccnd02_proyectos_actividad_manoobra;

CREATE TABLE ccnd02_proyectos_actividad_manoobra
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  ano int4 NOT NULL, -- Año del proyecto
  cod_proyecto varchar(30) NOT NULL, -- Código del proyecto
  cod_actividad int4 NOT NULL, -- Código de la actividad
  numero_renglon int4 NOT NULL, -- Número de renglón
  denominacion_manoobra text NOT NULL, -- Denominación de la mano de obra
  costo_unitario numeric(26,2) NOT NULL, -- Costo unitario
  CONSTRAINT ccnd02_proyectos_actividad_manoobra_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto, cod_actividad, numero_renglon),
  CONSTRAINT ccnd02_proyectos_actividad_manoobra_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
      REFERENCES ccnd02_proyectos (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd02_proyectos_actividad_manoobra OWNER TO sisap;
COMMENT ON TABLE ccnd02_proyectos_actividad_manoobra IS 'Registro de la mano de obra de acuerdo a la actividad, dentro del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.cod_republica IS 'Código de la república';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.cod_estado IS 'Código del estado';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.cod_parroquia IS 'Código de la parroquia';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.cod_centro IS 'Código del centro poblado';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.cod_concejo IS 'Código del concejo comunal';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.ano IS 'Año del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.cod_proyecto IS 'Código del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.cod_actividad IS 'Código de la actividad';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.numero_renglon IS 'Número de renglón';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.denominacion_manoobra IS 'Denominación de la mano de obra';
COMMENT ON COLUMN ccnd02_proyectos_actividad_manoobra.costo_unitario IS 'Costo unitario';




























-- Table: ccnd02_proyectos_actividad_materiales

-- DROP TABLE ccnd02_proyectos_actividad_materiales;

CREATE TABLE ccnd02_proyectos_actividad_materiales
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  ano int4 NOT NULL, -- Año del proyecto
  cod_proyecto varchar(30) NOT NULL, -- Código del proyecto
  cod_actividad int4 NOT NULL, -- Código de actividad
  numero_renglon int4 NOT NULL, -- Número de renglón
  denominacion_materiales text NOT NULL, -- Denominación de materiales
  costo numeric(26,2) NOT NULL, -- Costo unitario de materiales
  CONSTRAINT ccnd02_proyectos_actividad_materiales_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto, cod_actividad, numero_renglon),
  CONSTRAINT ccnd02_proyectos_actividad_materiales_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
      REFERENCES ccnd02_proyectos (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd02_proyectos_actividad_materiales OWNER TO sisap;
COMMENT ON TABLE ccnd02_proyectos_actividad_materiales IS 'Registro de materiales dentro de la actividades, dentro del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.cod_republica IS 'Código de la república
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.cod_estado IS 'Código del estado
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.cod_municipio IS 'Código del municipio
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.cod_parroquia IS 'Código de la parroquia
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.cod_centro IS 'Código del centro poblado
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.cod_concejo IS 'Código del concejo comunal
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.ano IS 'Año del proyecto
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.cod_proyecto IS 'Código del proyecto
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.cod_actividad IS 'Código de actividad
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.numero_renglon IS 'Número de renglón
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.denominacion_materiales IS 'Denominación de materiales
';
COMMENT ON COLUMN ccnd02_proyectos_actividad_materiales.costo IS 'Costo unitario de materiales
';
















-- Table: ccnd02_proyectos_actividades

-- DROP TABLE ccnd02_proyectos_actividades;

CREATE TABLE ccnd02_proyectos_actividades
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código de estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  ano int4 NOT NULL, -- Año del proyecto
  cod_proyecto varchar(30) NOT NULL, -- Código del proyecto
  cod_actividad int4 NOT NULL, -- Código de la actividad
  denominacion text NOT NULL, -- Denominación de la actividad
  cantidad int4 NOT NULL, -- Cantidad de acciones a realizar de una misma actividad
  CONSTRAINT ccnd02_proyectos_actividades_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto, cod_actividad),
  CONSTRAINT ccnd02_proyectos_actividades_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
      REFERENCES ccnd02_proyectos (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd02_proyectos_actividades OWNER TO sisap;
COMMENT ON TABLE ccnd02_proyectos_actividades IS 'Registra las actividades de los proyectos
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.cod_republica IS 'Código de la república
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.cod_estado IS 'Código de estado
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.cod_municipio IS 'Código del municipio
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.cod_parroquia IS 'Código de la parroquia
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.cod_centro IS 'Código del centro poblado
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.cod_concejo IS 'Código del concejo comunal
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.ano IS 'Año del proyecto
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.cod_proyecto IS 'Código del proyecto
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.cod_actividad IS 'Código de la actividad
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.denominacion IS 'Denominación de la actividad
';
COMMENT ON COLUMN ccnd02_proyectos_actividades.cantidad IS 'Cantidad de acciones a realizar de una misma actividad

';
















-- Table: ccnd02_proyectos_alternativas

-- DROP TABLE ccnd02_proyectos_alternativas;

CREATE TABLE ccnd02_proyectos_alternativas
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  ano int4 NOT NULL, -- Año del proyecto
  cod_proyecto varchar(30) NOT NULL, -- Código del proyecto
  numero_renglon int4 NOT NULL, -- Número de renglón
  formulacion_solucion text NOT NULL, -- Formulación de solución
  descripcion text NOT NULL, -- Descripción de solución
  costo numeric(26,2) NOT NULL, -- Costo de la solución
  ventajas text NOT NULL, -- Ventajas de la solución
  desventajas text NOT NULL, -- Desventajas de la solución
  CONSTRAINT ccnd02_proyectos_alternativas_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto, numero_renglon),
  CONSTRAINT ccnd02_proyectos_alternativas_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
      REFERENCES ccnd02_proyectos (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd02_proyectos_alternativas OWNER TO sisap;
COMMENT ON TABLE ccnd02_proyectos_alternativas IS 'Registro de las diferentes alternativas del proyecto
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.cod_republica IS 'Código de la república
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.cod_estado IS 'Código del estado
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.cod_municipio IS 'Código del municipio
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.cod_parroquia IS 'Código de la parroquia
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.cod_centro IS 'Código del centro poblado
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.cod_concejo IS 'Código del concejo comunal
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.ano IS 'Año del proyecto
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.cod_proyecto IS 'Código del proyecto
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.numero_renglon IS 'Número de renglón
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.formulacion_solucion IS 'Formulación de solución
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.descripcion IS 'Descripción de solución
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.costo IS 'Costo de la solución
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.ventajas IS 'Ventajas de la solución
';
COMMENT ON COLUMN ccnd02_proyectos_alternativas.desventajas IS 'Desventajas de la solución
';














-- Table: ccnd03_censo_familias

-- DROP TABLE ccnd03_censo_familias;

CREATE TABLE ccnd03_censo_familias
(
  cod_republica int4 NOT NULL, -- código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  numero_familia int4 NOT NULL, -- Número de familia
  cod_calle int4 NOT NULL, -- Código de la calle o avenida
  numero_casa varchar(20) NOT NULL, -- Número de casa
  telefonos varchar(50) NOT NULL, -- Teléfonos
  cod_vivienda int4 NOT NULL, -- Código tipo de vivienda
  cod_tenencia int4 NOT NULL, -- Código de tenencia
  condicion_vivienda varchar(1) NOT NULL, -- Condición de vivienda
  numero_ambientes int4 NOT NULL, -- Número de ambientes
  tiempo_residencia varchar(20) NOT NULL, -- Tiempo de residencia
  cod_mision int4 NOT NULL, -- Código de misión
  ingresos_familiar numeric(26,2) NOT NULL, -- Ingresos familiar
  numero_familias int4 NOT NULL DEFAULT 0, -- Número de familias
  monto_alquiler_hipoteca numeric(26,2) DEFAULT 0, -- Monto alquiler o hipoteca
  numero_habitantes int4 NOT NULL, -- Número de habitantes
  adultos_mayores int4 DEFAULT 0, -- Número de personas Adultas mayores
  discapacitados int4 DEFAULT 0, -- Número de personas discapacitadas
  enfermos_controlados int4 DEFAULT 0, -- Número de personas con enfermedades que requieren control permanente
  enfermos_terminales int4 DEFAULT 0, -- Número de personas con enfermedades mortales en estado terminal
  cedula_promotor int4 NOT NULL, -- Número de cédula de identidad del promotor
  nombres_apelllidos varchar(100) NOT NULL, -- Nombres y apellidos del promotor
  CONSTRAINT ccnd03_censo_familias_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, numero_familia),
  CONSTRAINT ccnd03_censo_familias_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo)
      REFERENCES ccnd01_concejo_comunal (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd03_censo_familias OWNER TO sisap;
COMMENT ON TABLE ccnd03_censo_familias IS 'Registro de censo de familiar por concejo comunal
';
COMMENT ON COLUMN ccnd03_censo_familias.cod_republica IS 'código de la república';
COMMENT ON COLUMN ccnd03_censo_familias.cod_estado IS 'Código del estado';
COMMENT ON COLUMN ccnd03_censo_familias.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN ccnd03_censo_familias.cod_parroquia IS 'Código de la parroquia
';
COMMENT ON COLUMN ccnd03_censo_familias.cod_centro IS 'Código del centro poblado
';
COMMENT ON COLUMN ccnd03_censo_familias.cod_concejo IS 'Código del concejo comunal';
COMMENT ON COLUMN ccnd03_censo_familias.numero_familia IS 'Número de familia';
COMMENT ON COLUMN ccnd03_censo_familias.cod_calle IS 'Código de la calle o avenida';
COMMENT ON COLUMN ccnd03_censo_familias.numero_casa IS 'Número de casa';
COMMENT ON COLUMN ccnd03_censo_familias.telefonos IS 'Teléfonos
';
COMMENT ON COLUMN ccnd03_censo_familias.cod_vivienda IS 'Código tipo de vivienda
1.- Quinta
2.- Casa / Quinta
3.- Casa popular
4.- Apartamento
5.- Vivienda popular
6.- Rancho';
COMMENT ON COLUMN ccnd03_censo_familias.cod_tenencia IS 'Código de tenencia
1.- Propia
2.- Alquilada
3.- De un familiar
4.- Al cuidado
5.- Hipotecada
6.- Invadida';
COMMENT ON COLUMN ccnd03_censo_familias.condicion_vivienda IS 'Condición de vivienda
E=Excelente
B=Buena
R=Regular
M=Mala';
COMMENT ON COLUMN ccnd03_censo_familias.numero_ambientes IS 'Número de ambientes
';
COMMENT ON COLUMN ccnd03_censo_familias.tiempo_residencia IS 'Tiempo de residencia
';
COMMENT ON COLUMN ccnd03_censo_familias.cod_mision IS 'Código de misión
1.- Ninguna
2.- Robinsón I
3.- Robinsón II
4.- Ribas
5.- Sucre
6.- Negra hipolita
7.- José Gregorio Hernandez
8.- Barrio adentro
9.- Mercal
10.- Arbol
11.- Ciencia
12.- Miranda
13.- Guacaipuro
14.- Piar
15.- Vuelvan caras
16.- Identidad
17.- Che Guevara
18.- Cultura
19.- Esperanza
20.- Habitat
21.- Madre del barrio
22.- Milagro
23.- Niños y niñas del barrio
24.- Zamora';
COMMENT ON COLUMN ccnd03_censo_familias.ingresos_familiar IS 'Ingresos familiar';
COMMENT ON COLUMN ccnd03_censo_familias.numero_familias IS 'Número de familias
';
COMMENT ON COLUMN ccnd03_censo_familias.monto_alquiler_hipoteca IS 'Monto alquiler o hipoteca
';
COMMENT ON COLUMN ccnd03_censo_familias.numero_habitantes IS 'Número de habitantes
';
COMMENT ON COLUMN ccnd03_censo_familias.adultos_mayores IS 'Número de personas Adultas mayores
';
COMMENT ON COLUMN ccnd03_censo_familias.discapacitados IS 'Número de personas discapacitadas
';
COMMENT ON COLUMN ccnd03_censo_familias.enfermos_controlados IS 'Número de personas con enfermedades que requieren control permanente
';
COMMENT ON COLUMN ccnd03_censo_familias.enfermos_terminales IS 'Número de personas con enfermedades mortales en estado terminal';
COMMENT ON COLUMN ccnd03_censo_familias.cedula_promotor IS 'Número de cédula de identidad del promotor';
COMMENT ON COLUMN ccnd03_censo_familias.nombres_apelllidos IS 'Nombres y apellidos del promotor';







































-- Table: ccnd03_censo_miembros

-- DROP TABLE ccnd03_censo_miembros;

CREATE TABLE ccnd03_censo_miembros
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  numero_familia int4 NOT NULL, -- Número de familia
  miembro_numero int4 NOT NULL, -- Miembro número
  cod_miembro int4 NOT NULL, -- Código de miembro
  nacionalidad varchar(1), -- V=Venezolana
  cedula_identidad int4, -- Cédula de identidad
  apellidos_nombres varchar(100) NOT NULL, -- Apellidos y Nombres
  fecha_nacimiento date, -- Fecha de nacimiento
  sexo varchar(1) NOT NULL, -- M=Masculino
  trabaja int4 NOT NULL DEFAULT 2, -- Trabaja
  estudia int4 NOT NULL DEFAULT 2, -- Estudia
  CONSTRAINT ccnd03_censo_miembros_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, numero_familia, miembro_numero),
  CONSTRAINT ccnd03_censo_miembros_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, numero_familia)
      REFERENCES ccnd03_censo_familias (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, numero_familia) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;
ALTER TABLE ccnd03_censo_miembros OWNER TO sisap;
COMMENT ON TABLE ccnd03_censo_miembros IS 'Registro de los miembros de la familia censada
';
COMMENT ON COLUMN ccnd03_censo_miembros.cod_republica IS 'Código de la república
';
COMMENT ON COLUMN ccnd03_censo_miembros.cod_estado IS 'Código del estado
';
COMMENT ON COLUMN ccnd03_censo_miembros.cod_municipio IS 'Código del municipio
';
COMMENT ON COLUMN ccnd03_censo_miembros.cod_parroquia IS 'Código de la parroquia
';
COMMENT ON COLUMN ccnd03_censo_miembros.cod_centro IS 'Código del centro poblado
';
COMMENT ON COLUMN ccnd03_censo_miembros.cod_concejo IS 'Código del concejo comunal
';
COMMENT ON COLUMN ccnd03_censo_miembros.numero_familia IS 'Número de familia
';
COMMENT ON COLUMN ccnd03_censo_miembros.miembro_numero IS 'Miembro número

';
COMMENT ON COLUMN ccnd03_censo_miembros.cod_miembro IS 'Código de miembro
';
COMMENT ON COLUMN ccnd03_censo_miembros.nacionalidad IS 'V=Venezolana
E=Extrajera
';
COMMENT ON COLUMN ccnd03_censo_miembros.cedula_identidad IS 'Cédula de identidad
';
COMMENT ON COLUMN ccnd03_censo_miembros.apellidos_nombres IS 'Apellidos y Nombres';
COMMENT ON COLUMN ccnd03_censo_miembros.fecha_nacimiento IS 'Fecha de nacimiento
';
COMMENT ON COLUMN ccnd03_censo_miembros.sexo IS 'M=Masculino
F=Femenino
';
COMMENT ON COLUMN ccnd03_censo_miembros.trabaja IS 'Trabaja
1=Si
2=No
';
COMMENT ON COLUMN ccnd03_censo_miembros.estudia IS 'Estudia
1=Si
2=No
';














-- Table: ccnd00

-- DROP TABLE ccnd00;

CREATE TABLE ccnd00
(
  username varchar(60) NOT NULL, -- Login de los usuarios
  "password" varchar(60) NOT NULL, -- Clave de usuario
  cedula_identidad int4 NOT NULL, -- Cédula de Identidad
  apellidos_nombres varchar(100) NOT NULL, -- Apellidos y Nombres
  cod_republica int4 NOT NULL, -- Código de la República
  cod_estado int4 NOT NULL, -- Código del Estado
  cod_municipio int4 NOT NULL, -- Código del Municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del Centro Poblado
  cod_concejo int4 NOT NULL, -- Código del Concejo Comunal
  CONSTRAINT ccnd00_pkey PRIMARY KEY (username)
)
WITHOUT OIDS;
ALTER TABLE ccnd00 OWNER TO sisap;
COMMENT ON TABLE ccnd00 IS 'Registro de login y claves de usuarios para el módulo de Concejos Comunales';
COMMENT ON COLUMN ccnd00.username IS 'Login de los usuarios
';
COMMENT ON COLUMN ccnd00."password" IS 'Clave de usuario';
COMMENT ON COLUMN ccnd00.cedula_identidad IS 'Cédula de Identidad';
COMMENT ON COLUMN ccnd00.apellidos_nombres IS 'Apellidos y Nombres';
COMMENT ON COLUMN ccnd00.cod_republica IS 'Código de la República';
COMMENT ON COLUMN ccnd00.cod_estado IS 'Código del Estado
';
COMMENT ON COLUMN ccnd00.cod_municipio IS 'Código del Municipio
';
COMMENT ON COLUMN ccnd00.cod_parroquia IS 'Código de la parroquia
';
COMMENT ON COLUMN ccnd00.cod_centro IS 'Código del Centro Poblado

';
COMMENT ON COLUMN ccnd00.cod_concejo IS 'Código del Concejo Comunal
';











-- Table: ccnd02_proyectos_profesionales

-- DROP TABLE ccnd02_proyectos_profesionales;

CREATE TABLE ccnd02_proyectos_profesionales
(
  cod_republica int4 NOT NULL, -- Código de la república
  cod_estado int4 NOT NULL, -- Código del estado
  cod_municipio int4 NOT NULL, -- Código del municipio
  cod_parroquia int4 NOT NULL, -- Código de la parroquia
  cod_centro int4 NOT NULL, -- Código del centro poblado
  cod_concejo int4 NOT NULL, -- Código del concejo comunal
  ano int4 NOT NULL, -- Año del proyecto
  cod_proyecto varchar(30) NOT NULL, -- Código del proyecto
  cedula_identidad int4 NOT NULL, -- Cédula de identidad
  apellidos_nombres varchar(100) NOT NULL, -- Apellidos y nombres
  profesion varchar(100) NOT NULL, -- Profesión
  numero_colegio varchar(50), -- Número de colegio
  CONSTRAINT ccnd02_proyectos_profesionales_pkey PRIMARY KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto, cedula_identidad),
  CONSTRAINT ccnd02_proyectos_profesionales_1 FOREIGN KEY (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto)
      REFERENCES ccnd02_proyectos (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, ano, cod_proyecto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITHOUT OIDS;

ALTER TABLE ccnd02_proyectos_profesionales OWNER TO sisap;

COMMENT ON TABLE ccnd02_proyectos_profesionales IS 'Registro de los profesionales responsables del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.cod_republica IS 'Código de la república';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.cod_estado IS 'Código del estado';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.cod_parroquia IS 'Código de la parroquia';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.cod_centro IS 'Código del centro poblado';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.cod_concejo IS 'Código del concejo comunal';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.ano IS 'Año del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.cod_proyecto IS 'Código del proyecto';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.cedula_identidad IS 'Cédula de identidad';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.apellidos_nombres IS 'Apellidos y nombres';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.profesion IS 'Profesión';
COMMENT ON COLUMN ccnd02_proyectos_profesionales.numero_colegio IS 'Número de colegio';












ALTER TABLE ccnd02_proyectos RENAME "objectivos especificos"  TO objectivos_especificos;
ALTER TABLE ccnd02_proyectos ALTER COLUMN objectivos_especificos SET STATISTICS -1;
COMMENT ON COLUMN ccnd02_proyectos.objectivos_especificos IS 'Objectivos especificos';




