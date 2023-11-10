-- Table: shd001_registro_contribuyentes

-- DROP TABLE shd001_registro_contribuyentes;

CREATE TABLE shd001_registro_contribuyentes
(
  rif_cedula character varying(20) NOT NULL, -- Rif o cédela de identidad
  personalidad_juridica integer NOT NULL, -- Personalidad Juridica...
  razon_social_nombres character varying(100) NOT NULL, -- Razón social o Nombres y Apellidos
  fecha_inscripcion date NOT NULL, -- Fecha de Inscripción
  nacionalidad integer, -- Nacionalidad...
  estado_civil integer, -- Estado civil...
  profesion integer, -- Codigo de la profesion (Enlace con personal)
  cod_pais integer NOT NULL, -- Código del pais
  cod_estado integer NOT NULL, -- Código del estado
  cod_municipio integer NOT NULL, -- Código del municipio
  cod_parroquia integer NOT NULL, -- Código de la parroquia
  cod_centro_poblado integer NOT NULL, -- Código del centro poblado...
  cod_calle_avenida integer NOT NULL, -- Código de la calle o avenida
  cod_vereda_edificio integer NOT NULL, -- Código de la vereda o edificio
  numero_vivienda_local character varying(30) NOT NULL, -- Número de la vivienda, local, piso y apartamento
  telefonos_fijos character varying(30), -- Telefonos fijos
  telefonos_celulares character varying(30), -- Telefonos celulares
  correo_electronico character varying(30), -- Correo electrónico
  CONSTRAINT shd001_registro_contribuyentes_pkey PRIMARY KEY (rif_cedula)
)
WITH (OIDS=FALSE);
ALTER TABLE shd001_registro_contribuyentes OWNER TO sisap;
COMMENT ON TABLE shd001_registro_contribuyentes IS 'Registro general de contribuyentes';
COMMENT ON COLUMN shd001_registro_contribuyentes.rif_cedula IS 'Rif o cédela de identidad';
COMMENT ON COLUMN shd001_registro_contribuyentes.personalidad_juridica IS 'Personalidad Juridica
1.- Natural
2.- Juridica';
COMMENT ON COLUMN shd001_registro_contribuyentes.razon_social_nombres IS 'Razón social o Nombres y Apellidos';
COMMENT ON COLUMN shd001_registro_contribuyentes.fecha_inscripcion IS 'Fecha de Inscripción';
COMMENT ON COLUMN shd001_registro_contribuyentes.nacionalidad IS 'Nacionalidad
1.- Extranjera
2.- Venezolana';
COMMENT ON COLUMN shd001_registro_contribuyentes.estado_civil IS 'Estado civil
1.- Soltero
2.- Casado
3.- Divorciado
4.- Viudo
5.- Otro
';
COMMENT ON COLUMN shd001_registro_contribuyentes.profesion IS 'Codigo de la profesion (Enlace con personal)';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_pais IS 'Código del pais';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_estado IS 'Código del estado';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_parroquia IS 'Código de la parroquia';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_centro_poblado IS 'Código del centro poblado
Urbanizaciones
Barrios
Caserios
Otros
';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_calle_avenida IS 'Código de la calle o avenida';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_vereda_edificio IS 'Código de la vereda o edificio';
COMMENT ON COLUMN shd001_registro_contribuyentes.numero_vivienda_local IS 'Número de la vivienda, local, piso y apartamento';
COMMENT ON COLUMN shd001_registro_contribuyentes.telefonos_fijos IS 'Telefonos fijos';
COMMENT ON COLUMN shd001_registro_contribuyentes.telefonos_celulares IS 'Telefonos celulares';
COMMENT ON COLUMN shd001_registro_contribuyentes.correo_electronico IS 'Correo electrónico';

-- Table: shd002_cobradores

-- DROP TABLE shd002_cobradores;

CREATE TABLE shd002_cobradores
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_ci character varying(20) NOT NULL, -- Rif o Cédula de Identidad
  personalidad integer NOT NULL, -- Personalidad Juridica...
  nombre_razon character varying(100) NOT NULL, -- Nombre y apellidos / Razón social
  fecha_ingreso date NOT NULL, -- Fecha de ingreso
  recurso_cobro integer NOT NULL, -- Recurso para el cobro...
  condicion_actividad integer NOT NULL, -- Condición de actividad...
  CONSTRAINT shd002_cobradores_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci)
)
WITH (OIDS=FALSE);
ALTER TABLE shd002_cobradores OWNER TO sisap;
COMMENT ON TABLE shd002_cobradores IS 'Registro de cobradores';
COMMENT ON COLUMN shd002_cobradores.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd002_cobradores.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd002_cobradores.cod_tipo_inst IS 'Código tipo de la institución';
COMMENT ON COLUMN shd002_cobradores.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd002_cobradores.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd002_cobradores.rif_ci IS 'Rif o Cédula de Identidad';
COMMENT ON COLUMN shd002_cobradores.personalidad IS 'Personalidad Juridica
1.- Natural
2.- Juridica';
COMMENT ON COLUMN shd002_cobradores.nombre_razon IS 'Nombre y apellidos / Razón social';
COMMENT ON COLUMN shd002_cobradores.fecha_ingreso IS 'Fecha de ingreso';
COMMENT ON COLUMN shd002_cobradores.recurso_cobro IS 'Recurso para el cobro
1.- Ninguno
2.- Bicicleta
3.- Moto
4.- Vehiculo';
COMMENT ON COLUMN shd002_cobradores.condicion_actividad IS 'Condición de actividad
1.- Activo
2.- Retirado
';

-- Table: shd003_codigo_ingresos

-- DROP TABLE shd003_codigo_ingresos;

CREATE TABLE shd003_codigo_ingresos
(
  cod_ingreso integer NOT NULL, -- Código de ingreso
  denominacion character varying(100) NOT NULL, -- Denominación de ingreso
  cod_partida integer NOT NULL, -- Partida
  cod_generica integer NOT NULL, -- Código de la generica
  cod_especifica integer NOT NULL, -- Código de la especifica
  cod_subespec integer NOT NULL, -- Código de la Subespecifica
  cod_auxiliar integer NOT NULL, -- Código del auxiliar
  CONSTRAINT shd003_codigo_ingresos_pkey PRIMARY KEY (cod_ingreso)
)
WITH (OIDS=FALSE);
ALTER TABLE shd003_codigo_ingresos OWNER TO sisap;
COMMENT ON TABLE shd003_codigo_ingresos IS 'Registro de código de ingresos';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_ingreso IS 'Código de ingreso';
COMMENT ON COLUMN shd003_codigo_ingresos.denominacion IS 'Denominación de ingreso';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_partida IS 'Partida';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_generica IS 'Código de la generica';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_especifica IS 'Código de la especifica';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_subespec IS 'Código de la Subespecifica';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_auxiliar IS 'Código del auxiliar';

-- Table: shd100_actividades

-- DROP TABLE shd100_actividades;

CREATE TABLE shd100_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_actividad character varying(20) NOT NULL, -- Código de la actividad economica
  denominacion_actividad text NOT NULL, -- Denominación de la actividad economica
  alicuota numeric(5,2) DEFAULT 0, -- Porcentaje alicuota
  unidades_tributarias numeric(5,2), -- Unidades tributarias
  minimo_tributable numeric(26,2), -- Monto minimo tributable
  CONSTRAINT shd100_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_actividad)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_actividades OWNER TO sisap;
COMMENT ON TABLE shd100_actividades IS 'Registra de la ordenanza de patente de industria y comercio las actividades economicas';
COMMENT ON COLUMN shd100_actividades.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_actividades.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_actividades.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_actividades.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_actividades.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_actividades.cod_actividad IS 'Código de la actividad economica';
COMMENT ON COLUMN shd100_actividades.denominacion_actividad IS 'Denominación de la actividad economica';
COMMENT ON COLUMN shd100_actividades.alicuota IS 'Porcentaje alicuota';
COMMENT ON COLUMN shd100_actividades.unidades_tributarias IS 'Unidades tributarias';
COMMENT ON COLUMN shd100_actividades.minimo_tributable IS 'Monto minimo tributable';


-- Table: shd100_declaracion_ingresos

-- DROP TABLE shd100_declaracion_ingresos;

CREATE TABLE shd100_declaracion_ingresos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de la solicitud de patente de industria y comercio
  numero_patente character varying(20) NOT NULL, -- Número de patente otorgado al contribuyente
  numero_declaracion character varying(20) NOT NULL, -- Número de declaración
  periodo_desde date NOT NULL, -- Periodo de declaración desde
  periodo_hasta date NOT NULL, -- Periodo de declaración hasta
  capital numeric(26,2), -- Capital
  numero_empleados integer, -- Número de empleados
  numero_obreros integer, -- Número de obreros
  fecha_declaracion date NOT NULL, -- Fecha de declaración de ingresos
  CONSTRAINT shd100_declaracion_ingresos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, numero_declaracion),
  CONSTRAINT shd100_declaracion_ingresos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente)
      REFERENCES shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_declaracion_ingresos OWNER TO sisap;
COMMENT ON TABLE shd100_declaracion_ingresos IS 'Registro de la declaración de ingresos brutos de los contribuyentes';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_solicitud IS 'Número de la solicitud de patente de industria y comercio';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_patente IS 'Número de patente otorgado al contribuyente';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_declaracion IS 'Número de declaración ';
COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_desde IS 'Periodo de declaración desde';
COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_hasta IS 'Periodo de declaración hasta';
COMMENT ON COLUMN shd100_declaracion_ingresos.capital IS 'Capital';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_empleados IS 'Número de empleados';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_obreros IS 'Número de obreros';
COMMENT ON COLUMN shd100_declaracion_ingresos.fecha_declaracion IS 'Fecha de declaración de ingresos';


-- Table: shd100_declaracion_actividades

-- DROP TABLE shd100_declaracion_actividades;

CREATE TABLE shd100_declaracion_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la depedencia
  numero_solicitud character varying(20) NOT NULL, -- Número de la solicitud de patente de industria y comercio
  numero_patente character varying(20) NOT NULL, -- Número de patente otorgada al contribuyente
  numero_declaracion character varying(20) NOT NULL, -- Número de declaración
  cod_actividad character varying(20) NOT NULL, -- Código de actividad economica declarada por el contribuyente
  monto_ingresos numeric(26,2) NOT NULL, -- Monto de ingreso declarado por el contribuyente
  monto_impuesto numeric(26,2) NOT NULL, -- Monto del impuesto calculado según los ingresos declarados por el contribuyente
  CONSTRAINT shd100_declaracion_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, numero_declaracion, cod_actividad),
  CONSTRAINT shd100_declaracion_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, numero_declaracion)
      REFERENCES shd100_declaracion_ingresos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, numero_declaracion) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_declaracion_actividades OWNER TO sisap;
COMMENT ON TABLE shd100_declaracion_actividades IS 'Registra las actividades economicas declaradas por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_dep IS 'Código de la depedencia';
COMMENT ON COLUMN shd100_declaracion_actividades.numero_solicitud IS 'Número de la solicitud de patente de industria y comercio';
COMMENT ON COLUMN shd100_declaracion_actividades.numero_patente IS 'Número de patente otorgada al contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.numero_declaracion IS 'Número de declaración';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_actividad IS 'Código de actividad economica declarada por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.monto_ingresos IS 'Monto de ingreso declarado por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.monto_impuesto IS 'Monto del impuesto calculado según los ingresos declarados por el contribuyente';


-- Table: shd100_ordenanza

-- DROP TABLE shd100_ordenanza;

CREATE TABLE shd100_ordenanza
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  porcentaje_descuento numeric(5,2), -- Porcentaje de descuento
  porcentaje_multa numeric(5,2), -- Porcentaje por multa
  porcentaje_recargo numeric(5,2), -- Porcentaje de recargos
  porcentaje_interes numeric(5,2), -- Porcentaje de interes
  CONSTRAINT shd100_ordenanza_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_ordenanza OWNER TO sisap;
COMMENT ON TABLE shd100_ordenanza IS 'Porcentajes de descuento o sanciones según la ordenanza';
COMMENT ON COLUMN shd100_ordenanza.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_ordenanza.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_ordenanza.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN shd100_ordenanza.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_ordenanza.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_ordenanza.porcentaje_descuento IS 'Porcentaje de descuento';
COMMENT ON COLUMN shd100_ordenanza.porcentaje_multa IS 'Porcentaje por multa';
COMMENT ON COLUMN shd100_ordenanza.porcentaje_recargo IS 'Porcentaje de recargos';
COMMENT ON COLUMN shd100_ordenanza.porcentaje_interes IS 'Porcentaje de interes';

-- Table: shd100_patente

-- DROP TABLE shd100_patente;

CREATE TABLE shd100_patente
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- número de solicitud de patente de industria y comercio
  numero_patente character varying(20) NOT NULL, -- Número de patente de industria y comercio
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer NOT NULL, -- Pago todo el año ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  fecha_ultima_decla date, -- Fecha ultima declaración
  ingresos_declarados numeric(26,2), -- Monto ingresos declarados
  ultimo_ejercicio_decla integer, -- Ultimo ejercicio declarado
  periodo_desde date, -- Periodo declarado desde
  periodo_hasta date, -- Periodo declarado hasta
  fecha_patente date NOT NULL, -- Fecha de otorgamiento de la patente
  CONSTRAINT shd100_patente_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_patente OWNER TO sisap;
COMMENT ON TABLE shd100_patente IS 'Registro de otorgamiento de licencia de industria y comercio';
COMMENT ON COLUMN shd100_patente.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_patente.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_patente.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_patente.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_patente.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_patente.numero_solicitud IS 'número de solicitud de patente de industria y comercio';
COMMENT ON COLUMN shd100_patente.numero_patente IS 'Número de patente de industria y comercio';
COMMENT ON COLUMN shd100_patente.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd100_patente.monto_mensual IS 'Monto mensual';
COMMENT ON COLUMN shd100_patente.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No
Por defecto "2"';
COMMENT ON COLUMN shd100_patente.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No
Por defecto "2"';
COMMENT ON COLUMN shd100_patente.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd100_patente.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd100_patente.ultimo_mes_facturado IS 'Ultimo mes cancelado';
COMMENT ON COLUMN shd100_patente.fecha_ultima_decla IS 'Fecha ultima declaración';
COMMENT ON COLUMN shd100_patente.ingresos_declarados IS 'Monto ingresos declarados';
COMMENT ON COLUMN shd100_patente.ultimo_ejercicio_decla IS 'Ultimo ejercicio declarado';
COMMENT ON COLUMN shd100_patente.periodo_desde IS 'Periodo declarado desde';
COMMENT ON COLUMN shd100_patente.periodo_hasta IS 'Periodo declarado hasta';
COMMENT ON COLUMN shd100_patente.fecha_patente IS 'Fecha de otorgamiento de la patente';

-- Table: shd100_patente_actividades

-- DROP TABLE shd100_patente_actividades;

CREATE TABLE shd100_patente_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud de patente
  numero_patente character varying(20) NOT NULL, -- Número de la patente de industria y comercioi
  cod_actividad character varying(20) NOT NULL, -- Código de la actividad economica
  numero_aforos integer NOT NULL, -- Número de aforos
  monto_aforo_anual numeric(26,2) NOT NULL, -- Monto aforo anual
  total_aforo_anual numeric(26,2) NOT NULL, -- Total aforo anual
  CONSTRAINT shd100_patente_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, cod_actividad),
  CONSTRAINT shd100_patente_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente)
      REFERENCES shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_patente_actividades OWNER TO sisap;
COMMENT ON TABLE shd100_patente_actividades IS 'Registro de monto de aforos de las actividades economicas del contribuyentes';
COMMENT ON COLUMN shd100_patente_actividades.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_patente_actividades.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_patente_actividades.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_patente_actividades.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_patente_actividades.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_patente_actividades.numero_solicitud IS 'Número de solicitud de patente';
COMMENT ON COLUMN shd100_patente_actividades.numero_patente IS 'Número de la patente de industria y comercioi';
COMMENT ON COLUMN shd100_patente_actividades.cod_actividad IS 'Código de la actividad economica';
COMMENT ON COLUMN shd100_patente_actividades.numero_aforos IS 'Número de aforos';
COMMENT ON COLUMN shd100_patente_actividades.monto_aforo_anual IS 'Monto aforo anual';
COMMENT ON COLUMN shd100_patente_actividades.total_aforo_anual IS 'Total aforo anual';

-- Table: shd100_solicitud

-- DROP TABLE shd100_solicitud;

CREATE TABLE shd100_solicitud
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud de licencia de patente de industria y comercio
  fecha_solicitud date NOT NULL, -- Fecha de la solicitud
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de Identidad
  numero_ficha_catastral integer NOT NULL, -- Número de ficha catastral
  capital numeric(26,2) NOT NULL, -- Capital de la empresa
  horario_trab_desde numeric(4,2), -- Horario de trabajo desde
  horario_trab_hasta numeric(4,2), -- Horario de trabajo hasta
  tipo_establecimiento integer NOT NULL, -- Tipo de establecimiento...
  tipo_local integer NOT NULL, -- Tipo de local...
  nacionalidad integer NOT NULL, -- Nacionalidad...
  cedula_identidad integer NOT NULL, -- Cédula de Identidad
  nombres_apellidos character varying(100) NOT NULL, -- Nombres y Apellidos
  cod_pais integer NOT NULL, -- Código del pais
  cod_estado integer NOT NULL, -- Código del estado
  cod_municipio integer NOT NULL, -- Código del municipio
  cod_parroquia integer NOT NULL, -- Código de la parroquia
  cod_centro integer NOT NULL, -- Código del centro poblado
  cod_vialidad integer NOT NULL, -- Código de calle o avenida
  cod_vereda integer, -- Código de la vereda o edificio
  numero_casa_local character varying(30) NOT NULL, -- Número de la casa o local
  telefonos_fijos character varying(50), -- Teléfonos fijos
  telefonos_celulares character varying(50), -- Teléfonos celulares
  correo_electronico character varying(50), -- Correo electrónico
  fecha_inicio_const date NOT NULL, -- Fecha de Inicio de la constitución
  fecha_cierre_const date NOT NULL, -- Fecha de cierre de la constitución
  fecha_inicio_econo date NOT NULL, -- Fecha de inicio economico
  fecha_cierre_economico date NOT NULL, -- fecha de cierre economico
  registro_mercantil text, -- Registro mercantil de la empresa
  tiene_sucursal integer NOT NULL, -- Tiene sucursal ?...
  es_fabricante integer NOT NULL, -- Es fabricante de algún producto...
  numero_empleado integer, -- Número de empleados
  numero_obreros integer, -- Número de obreros
  distancia_bar numeric(8,2), -- Distancia de un bar
  distancia_hospital numeric(8,2), -- Distancia de un hospital, clinica o dispensario (Centro de salud)
  distancia_educativo numeric(8,2), -- Distancia de una establecimiento educativo
  distancia_funeraria numeric(8,2), -- Distancia de una feneraria
  distancia_estacion numeric(8,2), -- Distancia de una estación de servicio
  distancia_gubernam numeric(8,2), -- Distancia de una entidad gubernamental
  tilde_reg_mercantil integer, -- Registro mercantil
  tilde_fotoco_ci integer, -- Fotocopia de la cédula de identidad
  tilde_acta_const integer, -- Acta constitutiva
  tilde_uso_conforme integer, -- Uso conforme
  tilde_croquis integer, -- Croquis elaborado por el contribuyente
  tilde_bomberos integer, -- Autorización de los bomberos
  tilde_rif integer, -- Certificado del R.I.F.
  tilde_solvencia integer, -- Solvencia de propiedad inmobiliaria
  tilde_concejo integer, -- Constancia del concejo comunal
  tilde_recibo integer, -- Recibo de cancelación de la solvencia
  tilde_planilla integer, -- Planilla de solicitud
  tilde_permiso integer, -- Permiso otorgado por organismo nacionales
  numero_patente character varying(20), -- Número de patente
  categoria_comercial integer,
  mercado_cubre integer,
  CONSTRAINT shd100_solicitud_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_solicitud OWNER TO sisap;
COMMENT ON TABLE shd100_solicitud IS 'Registro de las solicitudes de licencia de patente de industria y comercio';
COMMENT ON COLUMN shd100_solicitud.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_solicitud.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_solicitud.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_solicitud.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_solicitud.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_solicitud.numero_solicitud IS 'Número de solicitud de licencia de patente de industria y comercio';
COMMENT ON COLUMN shd100_solicitud.fecha_solicitud IS 'Fecha de la solicitud';
COMMENT ON COLUMN shd100_solicitud.rif_cedula IS 'Rif o Cédula de Identidad';
COMMENT ON COLUMN shd100_solicitud.numero_ficha_catastral IS 'Número de ficha catastral';
COMMENT ON COLUMN shd100_solicitud.capital IS 'Capital de la empresa';
COMMENT ON COLUMN shd100_solicitud.horario_trab_desde IS 'Horario de trabajo desde';
COMMENT ON COLUMN shd100_solicitud.horario_trab_hasta IS 'Horario de trabajo hasta';
COMMENT ON COLUMN shd100_solicitud.tipo_establecimiento IS 'Tipo de establecimiento
1.- Industrial
2.- Comercial
3.- Similar indole';
COMMENT ON COLUMN shd100_solicitud.tipo_local IS 'Tipo de local
1.- Un inmueble
2.- Mas de un Inmueble
3.- Parte de un inmueble';
COMMENT ON COLUMN shd100_solicitud.nacionalidad IS 'Nacionalidad
1.- Venezolana
2.- Extranjera';
COMMENT ON COLUMN shd100_solicitud.cedula_identidad IS 'Cédula de Identidad';
COMMENT ON COLUMN shd100_solicitud.nombres_apellidos IS 'Nombres y Apellidos';
COMMENT ON COLUMN shd100_solicitud.cod_pais IS 'Código del pais';
COMMENT ON COLUMN shd100_solicitud.cod_estado IS 'Código del estado';
COMMENT ON COLUMN shd100_solicitud.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN shd100_solicitud.cod_parroquia IS 'Código de la parroquia';
COMMENT ON COLUMN shd100_solicitud.cod_centro IS 'Código del centro poblado';
COMMENT ON COLUMN shd100_solicitud.cod_vialidad IS 'Código de calle o avenida';
COMMENT ON COLUMN shd100_solicitud.cod_vereda IS 'Código de la vereda o edificio';
COMMENT ON COLUMN shd100_solicitud.numero_casa_local IS 'Número de la casa o local';
COMMENT ON COLUMN shd100_solicitud.telefonos_fijos IS 'Teléfonos fijos';
COMMENT ON COLUMN shd100_solicitud.telefonos_celulares IS 'Teléfonos celulares';
COMMENT ON COLUMN shd100_solicitud.correo_electronico IS 'Correo electrónico';
COMMENT ON COLUMN shd100_solicitud.fecha_inicio_const IS 'Fecha de Inicio de la constitución';
COMMENT ON COLUMN shd100_solicitud.fecha_cierre_const IS 'Fecha de cierre de la constitución';
COMMENT ON COLUMN shd100_solicitud.fecha_inicio_econo IS 'Fecha de inicio economico';
COMMENT ON COLUMN shd100_solicitud.fecha_cierre_economico IS 'fecha de cierre economico';
COMMENT ON COLUMN shd100_solicitud.registro_mercantil IS 'Registro mercantil de la empresa';
COMMENT ON COLUMN shd100_solicitud.tiene_sucursal IS 'Tiene sucursal ?
1.- Si
2.- No';
COMMENT ON COLUMN shd100_solicitud.es_fabricante IS 'Es fabricante de algún producto
1.- Si
2.- No';
COMMENT ON COLUMN shd100_solicitud.numero_empleado IS 'Número de empleados
';
COMMENT ON COLUMN shd100_solicitud.numero_obreros IS 'Número de obreros';
COMMENT ON COLUMN shd100_solicitud.distancia_bar IS 'Distancia de un bar';
COMMENT ON COLUMN shd100_solicitud.distancia_hospital IS 'Distancia de un hospital, clinica o dispensario (Centro de salud)';
COMMENT ON COLUMN shd100_solicitud.distancia_educativo IS 'Distancia de una establecimiento educativo';
COMMENT ON COLUMN shd100_solicitud.distancia_funeraria IS 'Distancia de una feneraria';
COMMENT ON COLUMN shd100_solicitud.distancia_estacion IS 'Distancia de una estación de servicio';
COMMENT ON COLUMN shd100_solicitud.distancia_gubernam IS 'Distancia de una entidad gubernamental';
COMMENT ON COLUMN shd100_solicitud.tilde_reg_mercantil IS 'Registro mercantil';
COMMENT ON COLUMN shd100_solicitud.tilde_fotoco_ci IS 'Fotocopia de la cédula de identidad';
COMMENT ON COLUMN shd100_solicitud.tilde_acta_const IS 'Acta constitutiva';
COMMENT ON COLUMN shd100_solicitud.tilde_uso_conforme IS 'Uso conforme';
COMMENT ON COLUMN shd100_solicitud.tilde_croquis IS 'Croquis elaborado por el contribuyente';
COMMENT ON COLUMN shd100_solicitud.tilde_bomberos IS 'Autorización de los bomberos';
COMMENT ON COLUMN shd100_solicitud.tilde_rif IS 'Certificado del R.I.F.';
COMMENT ON COLUMN shd100_solicitud.tilde_solvencia IS 'Solvencia de propiedad inmobiliaria';
COMMENT ON COLUMN shd100_solicitud.tilde_concejo IS 'Constancia del concejo comunal';
COMMENT ON COLUMN shd100_solicitud.tilde_recibo IS 'Recibo de cancelación de la solvencia';
COMMENT ON COLUMN shd100_solicitud.tilde_planilla IS 'Planilla de solicitud';
COMMENT ON COLUMN shd100_solicitud.tilde_permiso IS 'Permiso otorgado por organismo nacionales';
COMMENT ON COLUMN shd100_solicitud.numero_patente IS 'Número de patente';

-- Table: shd100_solicitud_actividades

-- DROP TABLE shd100_solicitud_actividades;

CREATE TABLE shd100_solicitud_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  cod_actividad character varying(20) NOT NULL, -- Código de actividad
  CONSTRAINT shd100_solicitud_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, cod_actividad)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_solicitud_actividades OWNER TO sisap;
COMMENT ON TABLE shd100_solicitud_actividades IS 'Registra las actiividades economicas a la cual se dedica';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_solicitud_actividades.numero_solicitud IS 'Número de solicitud';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_actividad IS 'Código de actividad';

-- Table: shd200_vehiculos

-- DROP TABLE shd200_vehiculos;

CREATE TABLE shd200_vehiculos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad del contribuyente
  placa_vehiculo character varying(10) NOT NULL, -- Placa del Vehículo
  fecha_registro date NOT NULL, -- Fecha de registro
  cod_marca integer NOT NULL, -- Código de la marca del vehículo
  cod_modelo integer NOT NULL, -- Código del modelo del vehículo
  cod_color integer NOT NULL, -- Código del color del vehículo
  cod_clase integer NOT NULL, -- Código de la clase de vehículo
  cod_tipo integer NOT NULL, -- Código tipo del vehículo
  cod_uso integer NOT NULL, -- Código de uso de vehículo
  serial_carroceria character varying(25), -- Serial de carroceria
  serial_motor character varying(25), -- Serial del motor
  ano_adquisicion integer NOT NULL, -- Año de adquisición
  valor_vehiculo numeric(26,2) NOT NULL, -- Valor del vehículo
  fecha_adquisicion date NOT NULL, -- Fecha de adquisición del vehículo
  cod_clasificacion character varying(10) NOT NULL, -- Código de la clasificación automotríz
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  monto_mensual numeric(26,2) NOT NULL, -- Monto pago mensual
  pago_todo integer NOT NULL, -- Pago todo el año ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer -- Ultimo mes cancelado
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos IS 'Registro vehiculos de los contribuyentes';
COMMENT ON COLUMN shd200_vehiculos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd200_vehiculos.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd200_vehiculos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd200_vehiculos.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd200_vehiculos.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd200_vehiculos.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';
COMMENT ON COLUMN shd200_vehiculos.placa_vehiculo IS 'Placa del Vehículo';
COMMENT ON COLUMN shd200_vehiculos.fecha_registro IS 'Fecha de registro';
COMMENT ON COLUMN shd200_vehiculos.cod_marca IS 'Código de la marca del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_modelo IS 'Código del modelo del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_color IS 'Código del color del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_clase IS 'Código de la clase de vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_tipo IS 'Código tipo del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_uso IS 'Código de uso de vehículo';
COMMENT ON COLUMN shd200_vehiculos.serial_carroceria IS 'Serial de carroceria';
COMMENT ON COLUMN shd200_vehiculos.serial_motor IS 'Serial del motor';
COMMENT ON COLUMN shd200_vehiculos.ano_adquisicion IS 'Año de adquisición';
COMMENT ON COLUMN shd200_vehiculos.valor_vehiculo IS 'Valor del vehículo';
COMMENT ON COLUMN shd200_vehiculos.fecha_adquisicion IS 'Fecha de adquisición del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_clasificacion IS 'Código de la clasificación automotríz';
COMMENT ON COLUMN shd200_vehiculos.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd200_vehiculos.monto_mensual IS 'Monto pago mensual';
COMMENT ON COLUMN shd200_vehiculos.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No
Por defecto "2"';
COMMENT ON COLUMN shd200_vehiculos.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No
Por defecto "2"';
COMMENT ON COLUMN shd200_vehiculos.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd200_vehiculos.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd200_vehiculos.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd200_vehiculos_clases

-- DROP TABLE shd200_vehiculos_clases;

CREATE TABLE shd200_vehiculos_clases
(
  codigo_clase serial NOT NULL, -- Código de la clase
  denominacion character varying(100) NOT NULL, -- Denominación de la clase
  CONSTRAINT shd200_vehiculos_clases_pkey PRIMARY KEY (codigo_clase)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_clases OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_clases IS 'Registro de la clase de Vehículos';
COMMENT ON COLUMN shd200_vehiculos_clases.codigo_clase IS 'Código de la clase';
COMMENT ON COLUMN shd200_vehiculos_clases.denominacion IS 'Denominación de la clase';

-- Table: shd200_vehiculos_clasificacion

-- DROP TABLE shd200_vehiculos_clasificacion;

CREATE TABLE shd200_vehiculos_clasificacion
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_clasificacion character varying(10) NOT NULL, -- Código de la clasificación automotríz
  porcentaje numeric(5,2) NOT NULL, -- Porcentaje a aplicar
  monto_anual numeric(26,2) NOT NULL, -- Monto minimo anual
  CONSTRAINT shd200_vehiculos_clasificacion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_clasificacion)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_clasificacion OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_clasificacion IS 'Registra la clasificación automotriz de acuerdo a la ordenanza';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_clasificacion IS 'Código de la clasificación automotríz';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.porcentaje IS 'Porcentaje a aplicar';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.monto_anual IS 'Monto minimo anual';

-- Table: shd200_vehiculos_colores

-- DROP TABLE shd200_vehiculos_colores;

CREATE TABLE shd200_vehiculos_colores
(
  codigo_color serial NOT NULL, -- Código del color
  denominacion character varying(100) NOT NULL, -- Denominación del color
  CONSTRAINT shd200_vehiculos_colores_pkey PRIMARY KEY (codigo_color)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_colores OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_colores IS 'Registro de color de los Vehículos';
COMMENT ON COLUMN shd200_vehiculos_colores.codigo_color IS 'Código del color';
COMMENT ON COLUMN shd200_vehiculos_colores.denominacion IS 'Denominación del color';

-- Table: shd200_vehiculos_marcas

-- DROP TABLE shd200_vehiculos_marcas;

CREATE TABLE shd200_vehiculos_marcas
(
  codigo_marca serial NOT NULL, -- Código de marcas
  denominacion character varying(100) NOT NULL, -- Denominación de la marca
  CONSTRAINT shd200_vehiculos_marcas_pkey PRIMARY KEY (codigo_marca)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_marcas OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_marcas IS 'Registro de marcas de Vehículos';
COMMENT ON COLUMN shd200_vehiculos_marcas.codigo_marca IS 'Código de marcas';
COMMENT ON COLUMN shd200_vehiculos_marcas.denominacion IS 'Denominación de la marca';

-- Table: shd200_vehiculos_modelos

-- DROP TABLE shd200_vehiculos_modelos;

CREATE TABLE shd200_vehiculos_modelos
(
  codigo_modelo serial NOT NULL, -- Código del modelo
  denominacion character varying(100) NOT NULL, -- Denominación del modelo
  CONSTRAINT shd200_vehiculos_modelos_pkey PRIMARY KEY (codigo_modelo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_modelos OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_modelos IS 'Registro de modelos de Vehículos';
COMMENT ON COLUMN shd200_vehiculos_modelos.codigo_modelo IS 'Código del modelo';
COMMENT ON COLUMN shd200_vehiculos_modelos.denominacion IS 'Denominación del modelo';

-- Table: shd200_vehiculos_tipos

-- DROP TABLE shd200_vehiculos_tipos;

CREATE TABLE shd200_vehiculos_tipos
(
  codigo_tipo serial NOT NULL, -- Código del tipo de Vehículo
  denominacion character varying(100) NOT NULL, -- Denominación del tipo de Vehículo
  CONSTRAINT shd200_vehiculos_tipos_pkey PRIMARY KEY (codigo_tipo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_tipos OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_tipos IS 'Registro del de Vehículos';
COMMENT ON COLUMN shd200_vehiculos_tipos.codigo_tipo IS 'Código del tipo de Vehículo';
COMMENT ON COLUMN shd200_vehiculos_tipos.denominacion IS 'Denominación del tipo de Vehículo';

-- Table: shd200_vehiculos_usos

-- DROP TABLE shd200_vehiculos_usos;

CREATE TABLE shd200_vehiculos_usos
(
  codigo_uso serial NOT NULL, -- Código del uso del Vehículo
  denominacion character varying(100) NOT NULL, -- Denominación del uso del Vehículo
  CONSTRAINT shd200_vehiculos_usos_pkey PRIMARY KEY (codigo_uso)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_usos OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_usos IS 'Registro del uso Vehículos';
COMMENT ON COLUMN shd200_vehiculos_usos.codigo_uso IS 'Código del uso del Vehículo';
COMMENT ON COLUMN shd200_vehiculos_usos.denominacion IS 'Denominación del uso del Vehículo';

-- Table: shd300_detalles_adicional

-- DROP TABLE shd300_detalles_adicional;

CREATE TABLE shd300_detalles_adicional
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad del contribuyente
  cod_tipo integer NOT NULL, -- Código tipo de propaganda
  numero integer NOT NULL, -- Número consecutivo clave de la tabla shd300-detalles-propaganda
  cod_recargo integer NOT NULL, -- Código del recargo
  monto numeric(26,2) NOT NULL, -- Monto
  CONSTRAINT shd300_detalles_adicional_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero, cod_recargo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_detalles_adicional OWNER TO sisap;
COMMENT ON TABLE shd300_detalles_adicional IS 'Registra los montos adicionales por tipo de propaganda';
COMMENT ON COLUMN shd300_detalles_adicional.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_detalles_adicional.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_detalles_adicional.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_detalles_adicional.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd300_detalles_adicional.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_detalles_adicional.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';
COMMENT ON COLUMN shd300_detalles_adicional.cod_tipo IS 'Código tipo de propaganda';
COMMENT ON COLUMN shd300_detalles_adicional.numero IS 'Número consecutivo clave de la tabla shd300-detalles-propaganda';
COMMENT ON COLUMN shd300_detalles_adicional.cod_recargo IS 'Código del recargo';
COMMENT ON COLUMN shd300_detalles_adicional.monto IS 'Monto';

-- Table: shd300_detalles_propaganda

-- DROP TABLE shd300_detalles_propaganda;

CREATE TABLE shd300_detalles_propaganda
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad del contribuyente
  cod_tipo integer NOT NULL, -- Código tipo de propaganda comercial
  numero integer NOT NULL, -- Número consecutivo para registrar varias propagandas de un solo tipo
  largo numeric(10,3), -- Largo en metros lineales
  alto numeric(10,3), -- Alto en metros lineales
  area numeric(10,3), -- Area en metros cuadrados
  espesor numeric(10,3), -- Espesor
  cantidad numeric(10,3) NOT NULL, -- Cantidad en metros m2 o unidades
  monto numeric(26,2) NOT NULL, -- Monto resultante de calcular la Cantidad de metros o unidades por el valor del metro cuadrado o valor en unidad
  monto_adicional numeric(26,2), -- Monto adicional resultado de el calculo en porcentaje del monto bruto
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual a cobrar por este tipo de propaganda
  ubicacion text, -- Ubicación de la valla o cualquier otro tipo de propaganda
  fecha_registro date NOT NULL, -- Fecha de registro
  CONSTRAINT shd300_detalles_propaganda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_detalles_propaganda OWNER TO sisap;
COMMENT ON TABLE shd300_detalles_propaganda IS 'Registro de los diferentes tipos de propaganda comercial que cancela el contribuyente';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_detalles_propaganda.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_tipo IS 'Código tipo de propaganda comercial';
COMMENT ON COLUMN shd300_detalles_propaganda.numero IS 'Número consecutivo para registrar varias propagandas de un solo tipo';
COMMENT ON COLUMN shd300_detalles_propaganda.largo IS 'Largo en metros lineales';
COMMENT ON COLUMN shd300_detalles_propaganda.alto IS 'Alto en metros lineales';
COMMENT ON COLUMN shd300_detalles_propaganda.area IS 'Area en metros cuadrados';
COMMENT ON COLUMN shd300_detalles_propaganda.espesor IS 'Espesor';
COMMENT ON COLUMN shd300_detalles_propaganda.cantidad IS 'Cantidad en metros m2 o unidades';
COMMENT ON COLUMN shd300_detalles_propaganda.monto IS 'Monto resultante de calcular la Cantidad de metros o unidades por el valor del metro cuadrado o valor en unidad';
COMMENT ON COLUMN shd300_detalles_propaganda.monto_adicional IS 'Monto adicional resultado de el calculo en porcentaje del monto bruto';
COMMENT ON COLUMN shd300_detalles_propaganda.monto_mensual IS 'Monto mensual a cobrar por este tipo de propaganda';
COMMENT ON COLUMN shd300_detalles_propaganda.ubicacion IS 'Ubicación de la valla o cualquier otro tipo de propaganda';
COMMENT ON COLUMN shd300_detalles_propaganda.fecha_registro IS 'Fecha de registro';

-- Table: shd300_propaganda

-- DROP TABLE shd300_propaganda;

CREATE TABLE shd300_propaganda
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de Identidad
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  monto_mensual_general numeric(26,2) NOT NULL, -- Monto mensual general a cancelar
  pago_todo integer NOT NULL, -- Pago todo ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  CONSTRAINT shd300_propaganda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_propaganda OWNER TO sisap;
COMMENT ON TABLE shd300_propaganda IS 'Registra los contribuyentes de propaganda comercial, totalizando todos los tipos de propaganda';
COMMENT ON COLUMN shd300_propaganda.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_propaganda.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_propaganda.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_propaganda.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd300_propaganda.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_propaganda.rif_cedula IS 'Rif o Cédula de Identidad';
COMMENT ON COLUMN shd300_propaganda.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd300_propaganda.monto_mensual_general IS 'Monto mensual general a cancelar';
COMMENT ON COLUMN shd300_propaganda.pago_todo IS 'Pago todo ?
1.- Si
2.- No';
COMMENT ON COLUMN shd300_propaganda.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No';
COMMENT ON COLUMN shd300_propaganda.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd300_propaganda.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd300_propaganda.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd300_recargos

-- DROP TABLE shd300_recargos;

CREATE TABLE shd300_recargos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_recargo integer NOT NULL, -- Código de recargo
  denominacion character varying(100) NOT NULL, -- Denominación del recargo
  porcentaje numeric(5,2) NOT NULL, -- Porcentaje del recargo
  CONSTRAINT shd300_recargos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_recargo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_recargos OWNER TO sisap;
COMMENT ON TABLE shd300_recargos IS 'Registra los recargos adicionales según la ordenanza y el tipo de propaganda';
COMMENT ON COLUMN shd300_recargos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_recargos.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_recargos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_recargos.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN shd300_recargos.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_recargos.cod_recargo IS 'Código de recargo';
COMMENT ON COLUMN shd300_recargos.denominacion IS 'Denominación del recargo';
COMMENT ON COLUMN shd300_recargos.porcentaje IS 'Porcentaje del recargo';

-- Table: shd300_tipo_propaganda

-- DROP TABLE shd300_tipo_propaganda;

CREATE TABLE shd300_tipo_propaganda
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo integer NOT NULL, -- Código tipo de públicidad
  denominacion character varying(100) NOT NULL, -- Denominación tipo de publicidad
  articulo character varying(10) NOT NULL, -- Articulo de la ordenanza
  monto numeric(26,2) NOT NULL, -- Monto en bolivares por m2 o unidad
  tipo_unidad integer NOT NULL, -- Tipo de unidad...
  CONSTRAINT shd300_tipo_publicidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_tipo_propaganda OWNER TO sisap;
COMMENT ON TABLE shd300_tipo_propaganda IS 'Registrael monto por m2 o unidad según los tipos de propaganda';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_tipo IS 'Código tipo de públicidad';
COMMENT ON COLUMN shd300_tipo_propaganda.denominacion IS 'Denominación tipo de publicidad';
COMMENT ON COLUMN shd300_tipo_propaganda.articulo IS 'Articulo de la ordenanza';
COMMENT ON COLUMN shd300_tipo_propaganda.monto IS 'Monto en bolivares por m2 o unidad';
COMMENT ON COLUMN shd300_tipo_propaganda.tipo_unidad IS 'Tipo de unidad
1.- Unidad
2.- Metros';

-- Table: shd400_propiedad

-- DROP TABLE shd400_propiedad;

CREATE TABLE shd400_propiedad
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad del contribuyente
  cod_ficha character varying(20) NOT NULL, -- Código ficha catastral
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer NOT NULL, -- Pago todo el año ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  CONSTRAINT shd400_propiedad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_ficha)
)
WITH (OIDS=FALSE);
ALTER TABLE shd400_propiedad OWNER TO sisap;
COMMENT ON TABLE shd400_propiedad IS 'Registra la propiedad inmobiliaria de los contribuyentes';
COMMENT ON COLUMN shd400_propiedad.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd400_propiedad.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd400_propiedad.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd400_propiedad.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd400_propiedad.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd400_propiedad.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';
COMMENT ON COLUMN shd400_propiedad.cod_ficha IS 'Código ficha catastral';
COMMENT ON COLUMN shd400_propiedad.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd400_propiedad.monto_mensual IS 'Monto mensual';
COMMENT ON COLUMN shd400_propiedad.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No';
COMMENT ON COLUMN shd400_propiedad.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No';
COMMENT ON COLUMN shd400_propiedad.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd400_propiedad.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd400_propiedad.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd500_aseo_clasificacion

-- DROP TABLE shd500_aseo_clasificacion;

CREATE TABLE shd500_aseo_clasificacion
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_clasificacion integer NOT NULL, -- Código clasificacion del servicio
  denominacion character varying(100) NOT NULL, -- Denominación de la clasificación del servicio de aseo domiciliario
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  CONSTRAINT shd500_aseo_clasificacion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_clasificacion)
)
WITH (OIDS=FALSE);
ALTER TABLE shd500_aseo_clasificacion OWNER TO sisap;
COMMENT ON TABLE shd500_aseo_clasificacion IS 'Registra la clasificación del servicio de aseo domiciliario';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_clasificacion IS 'Código clasificacion del servicio';
COMMENT ON COLUMN shd500_aseo_clasificacion.denominacion IS 'Denominación de la clasificación del servicio de aseo domiciliario';
COMMENT ON COLUMN shd500_aseo_clasificacion.monto_mensual IS 'Monto mensual';

-- Table: shd500_aseo_domiciliario

-- DROP TABLE shd500_aseo_domiciliario;

CREATE TABLE shd500_aseo_domiciliario
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  cod_clasificacion integer NOT NULL, -- Código de la clasificación del servicio
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  fecha_registro date NOT NULL, -- Fecha del registro
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer NOT NULL, -- Pago todo el año ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  CONSTRAINT shd500_aseo_domiciliario_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
)
WITH (OIDS=FALSE);
ALTER TABLE shd500_aseo_domiciliario OWNER TO sisap;
COMMENT ON TABLE shd500_aseo_domiciliario IS 'Registra los contribuyentes de aseo domiciliario';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd500_aseo_domiciliario.rif_cedula IS 'Rif o Cédula de identidad';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_clasificacion IS 'Código de la clasificación del servicio';
COMMENT ON COLUMN shd500_aseo_domiciliario.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd500_aseo_domiciliario.fecha_registro IS 'Fecha del registro';
COMMENT ON COLUMN shd500_aseo_domiciliario.monto_mensual IS 'Monto mensual';
COMMENT ON COLUMN shd500_aseo_domiciliario.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No';
COMMENT ON COLUMN shd500_aseo_domiciliario.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No';
COMMENT ON COLUMN shd500_aseo_domiciliario.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd500_aseo_domiciliario.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd500_aseo_domiciliario.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd600_aprobacion_arrendamiento

-- DROP TABLE shd600_aprobacion_arrendamiento;

CREATE TABLE shd600_aprobacion_arrendamiento
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud de arrendamiento
  fecha_aprobacion date NOT NULL, -- Fecha de aprobacion
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  datos_registro_arrendamiento text NOT NULL, -- Datos registro de arrendamiento
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer, -- Contribuyente pago todo el año ?...
  suspendido integer NOT NULL, -- Pago del contribuyente esta suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  terreno_vendido integer, -- Terreno vendido ?...
  CONSTRAINT shd600_aprobacion_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud),
  CONSTRAINT shd600_aprobacion_arrendamiento_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud)
      REFERENCES shd600_solicitud_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_aprobacion_arrendamiento OWNER TO sisap;
COMMENT ON TABLE shd600_aprobacion_arrendamiento IS 'Registro de la aprobación a la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.numero_solicitud IS 'Número de solicitud de arrendamiento';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.fecha_aprobacion IS 'Fecha de aprobacion';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.datos_registro_arrendamiento IS 'Datos registro de arrendamiento';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.monto_mensual IS 'Monto mensual';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.pago_todo IS 'Contribuyente pago todo el año ?
1.- Si
2.- No';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.suspendido IS 'Pago del contribuyente esta suspendido ?
1.- Si
2.- No';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_mes_facturado IS 'Ultimo mes cancelado';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.terreno_vendido IS 'Terreno vendido ?
1.- Si
2.- No';

-- Table: shd600_compra_terreno

-- DROP TABLE shd600_compra_terreno;

CREATE TABLE shd600_compra_terreno
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  fecha_compra date NOT NULL, -- Fecha de compra
  datos_compra text NOT NULL, -- Datos de la compra
  monto numeric(26,2) NOT NULL, -- Monto de compra
  CONSTRAINT shd600_compra_terreno_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud),
  CONSTRAINT shd600_compra_terreno_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud)
      REFERENCES shd600_aprobacion_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_compra_terreno OWNER TO sisap;
COMMENT ON TABLE shd600_compra_terreno IS 'Registra la compra del terreno, previo arrendamiento';
COMMENT ON COLUMN shd600_compra_terreno.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_compra_terreno.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_compra_terreno.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd600_compra_terreno.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_compra_terreno.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_compra_terreno.numero_solicitud IS 'Número de solicitud';
COMMENT ON COLUMN shd600_compra_terreno.fecha_compra IS 'Fecha de compra';
COMMENT ON COLUMN shd600_compra_terreno.datos_compra IS 'Datos de la compra';
COMMENT ON COLUMN shd600_compra_terreno.monto IS 'Monto de compra';

-- Table: shd600_solicitud_arrendamiento

-- DROP TABLE shd600_solicitud_arrendamiento;

CREATE TABLE shd600_solicitud_arrendamiento
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  fecha_solicitud date NOT NULL, -- Fecha de la solicitud de arrendamiento
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  opcion integer NOT NULL, -- Opción...
  cod_ficha character varying(20) NOT NULL, -- Código de la ficha catastral
  expectativa_construccion text, -- Expectativa de construcción que tiene el solicitante
  CONSTRAINT shd600_solicitud_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud)
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_solicitud_arrendamiento OWNER TO sisap;
COMMENT ON TABLE shd600_solicitud_arrendamiento IS 'Registra la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.numero_solicitud IS 'Número de solicitud';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.fecha_solicitud IS 'Fecha de la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.rif_cedula IS 'Rif o Cédula de identidad';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.opcion IS 'Opción
1.- Simple
2.- Compra';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_ficha IS 'Código de la ficha catastral';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.expectativa_construccion IS 'Expectativa de construcción que tiene el solicitante';

-- Table: shd700_credito_vivienda

-- DROP TABLE shd700_credito_vivienda;

CREATE TABLE shd700_credito_vivienda
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  fecha_solicitud date NOT NULL, -- Fecha de solicitud
  nombre_conyugue character varying(100), -- Nombre y apellidos del conyugue
  cedula_conyugue integer, -- Cédula del conyugue
  nombre_empresa character varying(100), -- Nombre de la empresa donde trabaja el solicitante
  tiempo_empresa character varying(20), -- Tiempo de trabajo en la empresa
  telefonos_empresas character varying(50), -- Teléfonos de la empresa
  direccion_empresa text, -- Dirección de la empresa
  grupo_familiar integer, -- Grupo familiar
  ingreso_mensual numeric(26,2), -- Ingreso mensual familiar
  vivienda_actual integer, -- Vivienda actual...
  tipo_vivienda character varying(10), -- Tipo de vivienda
  direccion_vivienda_credito text, -- Dirección de la vivienda sujeta al crédito
  costo_vivienda numeric(26,2) NOT NULL, -- Costo de la vivienda sujeta al crédito
  monto_cuota_inicial numeric(26,2) NOT NULL, -- Monto de la cuota inicial
  monto_restante numeric(26,2) NOT NULL, -- Monto restante del crédito
  factor numeric(14,12), -- Factor de cálculo
  plazo_anos integer NOT NULL, -- Plazo años
  numero_cuotas integer NOT NULL, -- Número de cuotas
  monto_mensual numeric(26,2) NOT NULL, -- Monto a cancelar mensual
  numero_contrato character varying(20), -- Número de contrato
  fecha_contrato date, -- Fecha de contrato
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  pago_todo integer, -- Contribuyente pago todo el año?...
  suspendido integer, -- Suspendido el cobro a este contribuyente
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o cédula de identidad del cobrador
  ultimo_ano_facturado integer NOT NULL, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  CONSTRAINT shd700_credito_vivienda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
)
WITH (OIDS=FALSE);
ALTER TABLE shd700_credito_vivienda OWNER TO sisap;
COMMENT ON TABLE shd700_credito_vivienda IS 'Registro de solicitantes de créditos de vivienda';
COMMENT ON COLUMN shd700_credito_vivienda.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd700_credito_vivienda.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd700_credito_vivienda.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd700_credito_vivienda.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd700_credito_vivienda.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd700_credito_vivienda.rif_cedula IS 'Rif o Cédula de identidad';
COMMENT ON COLUMN shd700_credito_vivienda.fecha_solicitud IS 'Fecha de solicitud';
COMMENT ON COLUMN shd700_credito_vivienda.nombre_conyugue IS 'Nombre y apellidos del conyugue';
COMMENT ON COLUMN shd700_credito_vivienda.cedula_conyugue IS 'Cédula del conyugue';
COMMENT ON COLUMN shd700_credito_vivienda.nombre_empresa IS 'Nombre de la empresa donde trabaja el solicitante';
COMMENT ON COLUMN shd700_credito_vivienda.tiempo_empresa IS 'Tiempo de trabajo en la empresa';
COMMENT ON COLUMN shd700_credito_vivienda.telefonos_empresas IS 'Teléfonos de la empresa';
COMMENT ON COLUMN shd700_credito_vivienda.direccion_empresa IS 'Dirección de la empresa';
COMMENT ON COLUMN shd700_credito_vivienda.grupo_familiar IS 'Grupo familiar';
COMMENT ON COLUMN shd700_credito_vivienda.ingreso_mensual IS 'Ingreso mensual familiar';
COMMENT ON COLUMN shd700_credito_vivienda.vivienda_actual IS 'Vivienda actual
1.- Propia
2.- Alquilada
3.- Familiar';
COMMENT ON COLUMN shd700_credito_vivienda.tipo_vivienda IS 'Tipo de vivienda
';
COMMENT ON COLUMN shd700_credito_vivienda.direccion_vivienda_credito IS 'Dirección de la vivienda sujeta al crédito';
COMMENT ON COLUMN shd700_credito_vivienda.costo_vivienda IS 'Costo de la vivienda sujeta al crédito';
COMMENT ON COLUMN shd700_credito_vivienda.monto_cuota_inicial IS 'Monto de la cuota inicial';
COMMENT ON COLUMN shd700_credito_vivienda.monto_restante IS 'Monto restante del crédito';
COMMENT ON COLUMN shd700_credito_vivienda.factor IS 'Factor de cálculo';
COMMENT ON COLUMN shd700_credito_vivienda.plazo_anos IS 'Plazo años';
COMMENT ON COLUMN shd700_credito_vivienda.numero_cuotas IS 'Número de cuotas';
COMMENT ON COLUMN shd700_credito_vivienda.monto_mensual IS 'Monto a cancelar mensual';
COMMENT ON COLUMN shd700_credito_vivienda.numero_contrato IS 'Número de contrato';
COMMENT ON COLUMN shd700_credito_vivienda.fecha_contrato IS 'Fecha de contrato';
COMMENT ON COLUMN shd700_credito_vivienda.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd700_credito_vivienda.pago_todo IS 'Contribuyente pago todo el año?
1.- Si
2.- No
';
COMMENT ON COLUMN shd700_credito_vivienda.suspendido IS 'Suspendido el cobro a este contribuyente';
COMMENT ON COLUMN shd700_credito_vivienda.rif_ci_cobrador IS 'Rif o cédula de identidad del cobrador';
COMMENT ON COLUMN shd700_credito_vivienda.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd700_credito_vivienda.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd700_credito_vivienda_parentesco

-- DROP TABLE shd700_credito_vivienda_parentesco;

CREATE TABLE shd700_credito_vivienda_parentesco
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o cédula del solicitante
  cod_parentesco integer NOT NULL, -- Código de parentesco
  nombre_apellido character varying(100) NOT NULL, -- Nombre del apellido de pariente
  sexo integer NOT NULL, -- Sexo...
  fecha_nacimiento date NOT NULL, -- Fecha de nacimiento
  CONSTRAINT shd700_credito_vivienda_parentesco_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_parentesco),
  CONSTRAINT shd700_credito_vivienda_parentesco_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
      REFERENCES shd700_credito_vivienda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd700_credito_vivienda_parentesco OWNER TO sisap;
COMMENT ON TABLE shd700_credito_vivienda_parentesco IS 'Registro de parentesco de las solicitudes de crédito de vivienda';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.rif_cedula IS 'Rif o cédula del solicitante';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_parentesco IS 'Código de parentesco';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.nombre_apellido IS 'Nombre del apellido de pariente';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.sexo IS 'Sexo
1.- Masculino
2.- Femenino';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.fecha_nacimiento IS 'Fecha de nacimiento';





















-- View: v_shd001_registro_contribuyentes

-- DROP VIEW v_shd001_registro_contribuyentes;

CREATE OR REPLACE VIEW v_shd001_registro_contribuyentes AS
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, a.nacionalidad, a.estado_civil, a.profesion, ( SELECT b.denominacion
           FROM cnmd06_profesiones b
          WHERE a.profesion = b.cod_profesion) AS deno_profesion, a.cod_pais, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE a.cod_pais = c.cod_republica) AS deno_pais, a.cod_estado, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE a.cod_pais = d.cod_republica AND a.cod_estado = d.cod_estado) AS deno_estado, a.cod_municipio, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE a.cod_pais = e.cod_republica AND a.cod_estado = e.cod_estado AND a.cod_municipio = e.cod_municipio) AS deno_municipio, a.cod_parroquia, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE a.cod_pais = f.cod_republica AND a.cod_estado = f.cod_estado AND a.cod_municipio = f.cod_municipio AND a.cod_parroquia = f.cod_parroquia) AS deno_parroquia, a.cod_centro_poblado, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE a.cod_pais = g.cod_republica AND a.cod_estado = g.cod_estado AND a.cod_municipio = g.cod_municipio AND a.cod_parroquia = g.cod_parroquia AND a.cod_centro_poblado = g.cod_centro) AS deno_centro, a.cod_calle_avenida, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE a.cod_pais = h.cod_republica AND a.cod_estado = h.cod_estado AND a.cod_municipio = h.cod_municipio AND a.cod_parroquia = h.cod_parroquia AND a.cod_centro_poblado = h.cod_centro AND a.cod_calle_avenida = h.cod_vialidad) AS deno_vialidad, a.cod_vereda_edificio, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE a.cod_pais = i.cod_republica AND a.cod_estado = i.cod_estado AND a.cod_municipio = i.cod_municipio AND a.cod_parroquia = i.cod_parroquia AND a.cod_centro_poblado = i.cod_centro AND a.cod_calle_avenida = i.cod_vialidad AND a.cod_vereda_edificio = i.cod_vereda) AS deno_vereda, a.numero_vivienda_local, a.telefonos_fijos, a.telefonos_celulares, a.correo_electronico
   FROM shd001_registro_contribuyentes a;

ALTER TABLE v_shd001_registro_contribuyentes OWNER TO sisap;


-- View: v_shd100_solicitud

-- DROP VIEW v_shd100_solicitud;

CREATE OR REPLACE VIEW v_shd100_solicitud AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, b.razon_social_nombres, a.numero_ficha_catastral, a.capital, a.horario_trab_desde, a.horario_trab_hasta, a.tipo_establecimiento, a.tipo_local, a.nacionalidad AS nacionalidad_repre, a.cedula_identidad, a.nombres_apellidos, a.cod_pais AS pais_repre, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE a.cod_pais = c.cod_republica) AS deno_pais_repre, a.cod_estado AS estado_repre, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE a.cod_pais = d.cod_republica AND a.cod_estado = d.cod_estado) AS deno_estado_repre, a.cod_municipio AS municipio_repre, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE a.cod_pais = e.cod_republica AND a.cod_estado = e.cod_estado AND a.cod_municipio = e.cod_municipio) AS deno_municipio_repre, a.cod_parroquia AS parroquia_repre, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE a.cod_pais = f.cod_republica AND a.cod_estado = f.cod_estado AND a.cod_municipio = f.cod_municipio AND a.cod_parroquia = f.cod_parroquia) AS deno_parroquia_repre, a.cod_centro AS centro_repre, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE a.cod_pais = g.cod_republica AND a.cod_estado = g.cod_estado AND a.cod_municipio = g.cod_municipio AND a.cod_parroquia = g.cod_parroquia AND a.cod_centro = g.cod_centro) AS deno_centro_repre, a.cod_vialidad AS vialidad_repre, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE a.cod_pais = h.cod_republica AND a.cod_estado = h.cod_estado AND a.cod_municipio = h.cod_municipio AND a.cod_parroquia = h.cod_parroquia AND a.cod_centro = h.cod_centro AND a.cod_vialidad = h.cod_vialidad) AS deno_vialidad_repre, a.cod_vereda AS vereda_repre, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE a.cod_pais = i.cod_republica AND a.cod_estado = i.cod_estado AND a.cod_municipio = i.cod_municipio AND a.cod_parroquia = i.cod_parroquia AND a.cod_centro = i.cod_centro AND a.cod_vialidad = i.cod_vialidad AND a.cod_vereda = i.cod_vereda) AS deno_vereda_repre, a.numero_casa_local AS numero_local_repre, a.telefonos_fijos AS telefonos_fijos_repre, a.telefonos_celulares AS telefonos_celulares_repre, a.correo_electronico AS correo_electronico_repre, a.fecha_inicio_const, a.fecha_cierre_const, a.fecha_inicio_econo, a.fecha_cierre_economico, a.registro_mercantil, a.tiene_sucursal, a.es_fabricante, a.numero_empleado, a.numero_obreros, a.distancia_bar, a.distancia_hospital, a.distancia_educativo, a.distancia_funeraria, a.distancia_estacion, a.distancia_gubernam, a.tilde_reg_mercantil, a.tilde_fotoco_ci, a.tilde_acta_const, a.tilde_uso_conforme, a.tilde_croquis, a.tilde_bomberos, a.tilde_rif, a.tilde_solvencia, a.tilde_concejo, a.tilde_recibo, a.tilde_planilla, a.tilde_permiso, a.numero_patente, b.cod_pais AS pais_razon, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE b.cod_pais = c.cod_republica) AS deno_pais_razon, b.cod_estado AS estado_razon, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE b.cod_pais = d.cod_republica AND b.cod_estado = d.cod_estado) AS deno_estado_razon, b.cod_municipio AS municipio_razon, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE b.cod_pais = e.cod_republica AND b.cod_estado = e.cod_estado AND b.cod_municipio = e.cod_municipio) AS deno_municipio_razon, b.cod_parroquia AS parroquia_razon, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE b.cod_pais = f.cod_republica AND b.cod_estado = f.cod_estado AND b.cod_municipio = f.cod_municipio AND b.cod_parroquia = f.cod_parroquia) AS deno_parroquia_razon, b.cod_centro_poblado AS centro_razon, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE b.cod_pais = g.cod_republica AND b.cod_estado = g.cod_estado AND b.cod_municipio = g.cod_municipio AND b.cod_parroquia = g.cod_parroquia AND b.cod_centro_poblado = g.cod_centro) AS deno_centro_razon, b.cod_calle_avenida AS calle_razon, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE b.cod_pais = h.cod_republica AND b.cod_estado = h.cod_estado AND b.cod_municipio = h.cod_municipio AND b.cod_parroquia = h.cod_parroquia AND b.cod_centro_poblado = h.cod_centro AND b.cod_calle_avenida = h.cod_vialidad) AS deno_vialidad_razon, b.cod_vereda_edificio AS vereda_razon, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE b.cod_pais = i.cod_republica AND b.cod_estado = i.cod_estado AND b.cod_municipio = i.cod_municipio AND b.cod_parroquia = i.cod_parroquia AND b.cod_centro_poblado = i.cod_centro AND b.cod_calle_avenida = i.cod_vialidad AND b.cod_vereda_edificio = i.cod_vereda) AS deno_vereda_razon, b.fecha_inscripcion, b.telefonos_fijos AS telefonos_fijos_razon, b.telefonos_celulares AS telefonos_celulares_razon, b.correo_electronico AS correo_electronico_razon, b.nacionalidad AS nacionalidad_razon, b.estado_civil, b.numero_vivienda_local AS numero_local_razon, b.profesion, ( SELECT j.denominacion
           FROM cnmd06_profesiones j
          WHERE b.profesion = j.cod_profesion) AS deno_profesion, a.categoria_comercial, a.mercado_cubre, ( SELECT x.fecha_patente
           FROM shd100_patente x
          WHERE a.numero_patente::text = x.numero_patente::text AND a.cod_presi = x.cod_presi AND a.cod_entidad = x.cod_entidad AND a.cod_tipo_inst = x.cod_tipo_inst AND a.cod_dep = x.cod_dep) AS fecha_patente, ( SELECT x.frecuencia_pago
           FROM shd100_patente x
          WHERE a.numero_patente::text = x.numero_patente::text AND a.cod_presi = x.cod_presi AND a.cod_entidad = x.cod_entidad AND a.cod_tipo_inst = x.cod_tipo_inst AND a.cod_dep = x.cod_dep) AS frecuencia_pago
   FROM shd100_solicitud a, shd001_registro_contribuyentes b
  WHERE a.rif_cedula::text = b.rif_cedula::text;

ALTER TABLE v_shd100_solicitud OWNER TO sisap;


-- View: v_shd100_solicitud_actividades

-- DROP VIEW v_shd100_solicitud_actividades;

CREATE OR REPLACE VIEW v_shd100_solicitud_actividades AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.cod_actividad, b.denominacion_actividad, b.alicuota, b.unidades_tributarias, b.minimo_tributable
   FROM shd100_solicitud_actividades a, shd100_actividades b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text;

ALTER TABLE v_shd100_solicitud_actividades OWNER TO sisap;


-- View: v_shd100_declaracion_ingreso

-- DROP VIEW v_shd100_declaracion_ingreso;

CREATE OR REPLACE VIEW v_shd100_declaracion_ingreso AS
 SELECT x.cod_presi, x.cod_entidad, x.cod_tipo_inst, x.cod_inst, x.cod_dep, x.numero_solicitud, x.numero_patente, x.fecha_solicitud, a.rif_cedula, a.razon_social_nombres, a.cod_pais, ( SELECT b.denominacion
           FROM cugd01_republica b
          WHERE b.cod_republica = a.cod_pais) AS denominacion_pais, a.cod_estado, ( SELECT c.denominacion
           FROM cugd01_estados c
          WHERE c.cod_republica = a.cod_pais AND c.cod_estado = a.cod_estado) AS denominacion_estado, a.cod_municipio, ( SELECT d.denominacion
           FROM cugd01_municipios d
          WHERE d.cod_republica = a.cod_pais AND d.cod_estado = a.cod_estado AND d.cod_municipio = a.cod_municipio) AS denominacion_municipio, a.cod_parroquia, ( SELECT e.denominacion
           FROM cugd01_parroquias e
          WHERE e.cod_republica = a.cod_pais AND e.cod_estado = a.cod_estado AND e.cod_municipio = a.cod_municipio AND e.cod_parroquia = a.cod_parroquia) AS denominacion_parroquia, a.cod_centro_poblado, ( SELECT f.denominacion
           FROM cugd01_centros_poblados f
          WHERE f.cod_republica = a.cod_pais AND f.cod_estado = a.cod_estado AND f.cod_municipio = a.cod_municipio AND f.cod_parroquia = a.cod_parroquia AND f.cod_centro = a.cod_centro_poblado) AS denominacion_centro, a.cod_calle_avenida, ( SELECT g.denominacion
           FROM cugd01_vialidad g
          WHERE g.cod_republica = a.cod_pais AND g.cod_estado = a.cod_estado AND g.cod_municipio = a.cod_municipio AND g.cod_parroquia = a.cod_parroquia AND g.cod_centro = a.cod_centro_poblado AND g.cod_vialidad = a.cod_calle_avenida) AS denominacion_vialidad, a.cod_vereda_edificio, ( SELECT h.denominacion
           FROM cugd01_vereda h
          WHERE h.cod_republica = a.cod_pais AND h.cod_estado = a.cod_estado AND h.cod_municipio = a.cod_municipio AND h.cod_parroquia = a.cod_parroquia AND h.cod_centro = a.cod_centro_poblado AND h.cod_vialidad = a.cod_calle_avenida AND h.cod_vereda = a.cod_vereda_edificio) AS denominacion_vereda, a.numero_vivienda_local, a.telefonos_fijos, a.telefonos_celulares, a.correo_electronico
   FROM shd001_registro_contribuyentes a, shd100_solicitud x
  WHERE x.rif_cedula::text = a.rif_cedula::text;

ALTER TABLE v_shd100_declaracion_ingreso OWNER TO sisap;






