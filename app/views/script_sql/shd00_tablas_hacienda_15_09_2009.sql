
-- DROP VIEW  v_shd910_detalles_cobradores_2 CASCADE;
-- DROP VIEW  v_shd910_detalles_cobradores CASCADE;
-- DROP TABLE shd910_detalles CASCADE;
-- DROP TABLE shd910_cuerpo CASCADE;
-- DROP TABLE shd999_cobranza_acumulada CASCADE;
-- DROP TABLE shd100_ordenanza CASCADE;
-- DROP TABLE shd900_ingresos_numero CASCADE;
-- DROP TABLE shd901_ingresos_cobro CASCADE;

DROP TABLE shd950_solvencia_numero CASCADE;
DROP TABLE shd950_solvencia_monto CASCADE;
DROP TABLE shd950_solvencia CASCADE;
DROP TABLE shd900_planillas_deuda_cobro_detalles CASCADE;
DROP TABLE shd900_planillas_deuda_cobro_cuerpo CASCADE;
DROP TABLE shd900_cobranza_numero CASCADE;
DROP TABLE shd900_cobranza_diaria CASCADE;
DROP TABLE shd900_cobranza_acumulada CASCADE;
DROP TABLE shd700_credito_vivienda_parentesco CASCADE;
DROP TABLE shd700_credito_vivienda CASCADE;
DROP TABLE shd600_solicitud_arrendamiento CASCADE;
DROP TABLE shd600_compra_terreno CASCADE;
DROP TABLE shd600_aprobacion_arrendamiento CASCADE;
DROP TABLE shd500_aseo_domiciliario CASCADE;
DROP TABLE shd500_aseo_clasificacion CASCADE;
DROP TABLE shd400_propiedad CASCADE;
DROP TABLE shd300_tipo_propaganda CASCADE;
DROP TABLE shd300_recargos CASCADE;
DROP TABLE shd300_propaganda CASCADE;
DROP TABLE shd300_detalles_propaganda CASCADE;
DROP TABLE shd300_detalles_adicional CASCADE;
DROP TABLE shd200_vehiculos_usos CASCADE;
DROP TABLE shd200_vehiculos_tipos CASCADE;
DROP TABLE shd200_vehiculos_modelos CASCADE;
DROP TABLE shd200_vehiculos_marcas CASCADE;
DROP TABLE shd200_vehiculos_colores CASCADE;
DROP TABLE shd200_vehiculos_clasificacion CASCADE;
DROP TABLE shd200_vehiculos_clases CASCADE;
DROP TABLE shd200_vehiculos CASCADE;
DROP TABLE shd100_solicitud_actividades CASCADE;
DROP TABLE shd100_solicitud CASCADE;
DROP TABLE shd100_patente_actividades CASCADE;
DROP TABLE shd100_patente CASCADE;
DROP TABLE shd100_declaracion_ingresos CASCADE;
DROP TABLE shd100_declaracion_actividades CASCADE;
DROP TABLE shd100_articulos CASCADE;
DROP TABLE shd100_actividades CASCADE;
DROP TABLE shd003_codigo_ingresos CASCADE;
DROP TABLE shd002_cobranza_realizada CASCADE;
DROP TABLE shd002_cobranza_pendiente CASCADE;
DROP TABLE shd002_cobradores CASCADE;
DROP TABLE shd001_registro_contribuyentes CASCADE;
DROP TABLE shd000_ordenanzas CASCADE;
DROP TABLE shd000_control_numero CASCADE;
DROP TABLE shd000_control_actualizacion CASCADE;
DROP TABLE shd000_arranque CASCADE;








CREATE TABLE shd000_arranque
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código dependencia
  ano_arranque integer NOT NULL, -- Año de arranque
  mes_arranque integer NOT NULL, -- Mes de arranque
  CONSTRAINT shd000_arranque_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_arranque)
)
WITH (OIDS=FALSE);
ALTER TABLE shd000_arranque OWNER TO sisap;
COMMENT ON TABLE shd000_arranque IS 'Control de arranque del módulo de hacienda';
COMMENT ON COLUMN shd000_arranque.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd000_arranque.cod_entidad IS 'Código de la entidad federal
';
COMMENT ON COLUMN shd000_arranque.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd000_arranque.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN shd000_arranque.cod_dep IS 'Código dependencia';
COMMENT ON COLUMN shd000_arranque.ano_arranque IS 'Año de arranque';
COMMENT ON COLUMN shd000_arranque.mes_arranque IS 'Mes de arranque
';






















CREATE TABLE shd000_control_actualizacion
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_ingreso integer NOT NULL, -- Código de ingreso:...
  ano_actualizado integer NOT NULL, -- Año actualizado
  mes_actualizado integer NOT NULL, -- Mes actualizado
  condicion integer NOT NULL DEFAULT 0, -- Condición...
  CONSTRAINT shd000_control_actualizacion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ingreso, ano_actualizado, mes_actualizado)
)
WITH (OIDS=FALSE);
ALTER TABLE shd000_control_actualizacion OWNER TO sisap;
COMMENT ON TABLE shd000_control_actualizacion IS 'Controla la actualización de las planillas y la emisón';
COMMENT ON COLUMN shd000_control_actualizacion.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd000_control_actualizacion.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd000_control_actualizacion.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd000_control_actualizacion.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd000_control_actualizacion.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd000_control_actualizacion.cod_ingreso IS 'Código de ingreso:
01 = Industria y comercio
02 = Vehículos
03 = Propaganda comercial
04 = Inmuebles urbanos
05 = Aseo domiciliario
06 = Arrendamiento de tierras
07 = Crédito de vivienda

';
COMMENT ON COLUMN shd000_control_actualizacion.ano_actualizado IS 'Año actualizado';
COMMENT ON COLUMN shd000_control_actualizacion.mes_actualizado IS 'Mes actualizado';
COMMENT ON COLUMN shd000_control_actualizacion.condicion IS 'Condición
0 = Sin actualizar
1 = Actualizado
2 = Emitido
';




CREATE TABLE shd000_control_numero
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano integer NOT NULL, -- Año
  cod_ingreso integer NOT NULL, -- Código de ingreso:...
  numero_planilla integer NOT NULL, -- Número de planilla de liquidación previa
  CONSTRAINT shd000_control_numero_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_ingreso)
)
WITH (OIDS=FALSE);
ALTER TABLE shd000_control_numero OWNER TO sisap;
COMMENT ON TABLE shd000_control_numero IS 'Control de número de las planillas';
COMMENT ON COLUMN shd000_control_numero.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd000_control_numero.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd000_control_numero.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd000_control_numero.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd000_control_numero.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd000_control_numero.ano IS 'Año';
COMMENT ON COLUMN shd000_control_numero.cod_ingreso IS 'Código de ingreso:
01 = Industria y comercio
02 = Vehículos
03 = Propaganda comercial
04 = Inmuebles urbanos
05 = Aseo domiciliario
06 = Arrendamiento de tierras
07 = Crédito de vivienda

';
COMMENT ON COLUMN shd000_control_numero.numero_planilla IS 'Número de planilla de liquidación previa';










CREATE TABLE shd000_ordenanzas
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_ingreso integer NOT NULL, -- Código de ingreso:...
  porcentaje_descuento numeric(5,2), -- Porcentaje de descuento
  porcentaje_multa numeric(5,2), -- Porcentaje por multa
  porcentaje_recargo numeric(5,2), -- Porcentaje de recargos
  porcentaje_interes numeric(5,2), -- Porcentaje de interes
  CONSTRAINT shd000_ordenanzas_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ingreso)
)
WITH (OIDS=FALSE);
ALTER TABLE shd000_ordenanzas OWNER TO sisap;
COMMENT ON TABLE shd000_ordenanzas IS 'Porcentajes de descuento o sanciones según la ordenanza';
COMMENT ON COLUMN shd000_ordenanzas.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd000_ordenanzas.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd000_ordenanzas.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN shd000_ordenanzas.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd000_ordenanzas.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd000_ordenanzas.cod_ingreso IS 'Código de ingreso:
01 = Industria y comercio
02 = Vehículos
03 = Propaganda comercial
04 = Inmuebles urbanos
05 = Aseo domiciliario
06 = Arrendamiento de tierras
07 = Crédito de vivienda
';
COMMENT ON COLUMN shd000_ordenanzas.porcentaje_descuento IS 'Porcentaje de descuento';
COMMENT ON COLUMN shd000_ordenanzas.porcentaje_multa IS 'Porcentaje por multa';
COMMENT ON COLUMN shd000_ordenanzas.porcentaje_recargo IS 'Porcentaje de recargos';
COMMENT ON COLUMN shd000_ordenanzas.porcentaje_interes IS 'Porcentaje de interes';







CREATE TABLE shd001_registro_contribuyentes
(
  rif_cedula character varying(20) NOT NULL, -- Rif o cédula de identidad
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
COMMENT ON COLUMN shd001_registro_contribuyentes.rif_cedula IS 'Rif o cédula de identidad';
COMMENT ON COLUMN shd001_registro_contribuyentes.personalidad_juridica IS 'Personalidad Juridica
1.- Natural
2.- Juridica';
COMMENT ON COLUMN shd001_registro_contribuyentes.razon_social_nombres IS 'Razón social o Nombres y Apellidos';
COMMENT ON COLUMN shd001_registro_contribuyentes.fecha_inscripcion IS 'Fecha de Inscripción';
COMMENT ON COLUMN shd001_registro_contribuyentes.nacionalidad IS 'Nacionalidad
1.- Venezolana
2.- Extranjera';
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








CREATE TABLE shd002_cobranza_pendiente
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Códito tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_ci character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ano integer NOT NULL, -- Año actual
  cobranza_pendiente_acumulada numeric(26,2) NOT NULL, -- Cobranza pendiente acumulada en años anteriores
  enero numeric(26,2) NOT NULL, -- Mes de enero
  febrero numeric(26,2) NOT NULL, -- Mes de febrero
  marzo numeric(26,2) NOT NULL, -- Mes de marzo
  abril numeric(26,2) NOT NULL, -- Mes de abril
  mayo numeric(26,2) NOT NULL, -- Mes de mayo
  junio numeric(26,2) NOT NULL, -- Mes de junio
  julio numeric(26,2) NOT NULL, -- Mes de julio
  agosto numeric(26,2) NOT NULL, -- Mes de agosto
  septiembre numeric(26,2) NOT NULL, -- Mes de septiembre
  octubre numeric(26,2) NOT NULL, -- Mes de octubre
  noviembre numeric(26,2) NOT NULL, -- Mes de noviembre
  diciembre numeric(26,2) NOT NULL, -- Mes de diciembre
  CONSTRAINT shd002_cobranza_pendiente_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, ano)
)
WITH (OIDS=FALSE);
ALTER TABLE shd002_cobranza_pendiente OWNER TO sisap;
COMMENT ON TABLE shd002_cobranza_pendiente IS 'Registra la cobranza realizada por el cobrador';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_tipo_inst IS 'Códito tipo de Institución';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd002_cobranza_pendiente.rif_ci IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd002_cobranza_pendiente.ano IS 'Año actual';
COMMENT ON COLUMN shd002_cobranza_pendiente.cobranza_pendiente_acumulada IS 'Cobranza pendiente acumulada en años anteriores';
COMMENT ON COLUMN shd002_cobranza_pendiente.enero IS 'Mes de enero';
COMMENT ON COLUMN shd002_cobranza_pendiente.febrero IS 'Mes de febrero';
COMMENT ON COLUMN shd002_cobranza_pendiente.marzo IS 'Mes de marzo';
COMMENT ON COLUMN shd002_cobranza_pendiente.abril IS 'Mes de abril';
COMMENT ON COLUMN shd002_cobranza_pendiente.mayo IS 'Mes de mayo';
COMMENT ON COLUMN shd002_cobranza_pendiente.junio IS 'Mes de junio';
COMMENT ON COLUMN shd002_cobranza_pendiente.julio IS 'Mes de julio';
COMMENT ON COLUMN shd002_cobranza_pendiente.agosto IS 'Mes de agosto';
COMMENT ON COLUMN shd002_cobranza_pendiente.septiembre IS 'Mes de septiembre';
COMMENT ON COLUMN shd002_cobranza_pendiente.octubre IS 'Mes de octubre';
COMMENT ON COLUMN shd002_cobranza_pendiente.noviembre IS 'Mes de noviembre';
COMMENT ON COLUMN shd002_cobranza_pendiente.diciembre IS 'Mes de diciembre';












































CREATE TABLE shd002_cobranza_realizada
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Códito tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_ci character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ano integer NOT NULL, -- Año actual
  cobranza_acumulada numeric(26,2) NOT NULL, -- Cobranza acumulada en años anteriores
  enero numeric(26,2) NOT NULL, -- Mes de enero
  febrero numeric(26,2) NOT NULL, -- Mes de febrero
  marzo numeric(26,2) NOT NULL, -- Mes de marzo
  abril numeric(26,2) NOT NULL, -- Mes de abril
  mayo numeric(26,2) NOT NULL, -- Mes de mayo
  junio numeric(26,2) NOT NULL, -- Mes de junio
  julio numeric(26,2) NOT NULL, -- Mes de julio
  agosto numeric(26,2) NOT NULL, -- Mes de agosto
  septiembre numeric(26,2) NOT NULL, -- Mes de septiembre
  octubre numeric(26,2) NOT NULL, -- Mes de octubre
  noviembre numeric(26,2) NOT NULL, -- Mes de noviembre
  diciembre numeric(26,2) NOT NULL, -- Mes de diciembre
  CONSTRAINT shd002_cobranza_realizada_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, ano)
)
WITH (OIDS=FALSE);
ALTER TABLE shd002_cobranza_realizada OWNER TO sisap;
COMMENT ON TABLE shd002_cobranza_realizada IS 'Registra la cobranza realizada por el cobrador';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_tipo_inst IS 'Códito tipo de Institución';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd002_cobranza_realizada.rif_ci IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd002_cobranza_realizada.ano IS 'Año actual';
COMMENT ON COLUMN shd002_cobranza_realizada.cobranza_acumulada IS 'Cobranza acumulada en años anteriores';
COMMENT ON COLUMN shd002_cobranza_realizada.enero IS 'Mes de enero';
COMMENT ON COLUMN shd002_cobranza_realizada.febrero IS 'Mes de febrero';
COMMENT ON COLUMN shd002_cobranza_realizada.marzo IS 'Mes de marzo';
COMMENT ON COLUMN shd002_cobranza_realizada.abril IS 'Mes de abril';
COMMENT ON COLUMN shd002_cobranza_realizada.mayo IS 'Mes de mayo';
COMMENT ON COLUMN shd002_cobranza_realizada.junio IS 'Mes de junio';
COMMENT ON COLUMN shd002_cobranza_realizada.julio IS 'Mes de julio';
COMMENT ON COLUMN shd002_cobranza_realizada.agosto IS 'Mes de agosto';
COMMENT ON COLUMN shd002_cobranza_realizada.septiembre IS 'Mes de septiembre';
COMMENT ON COLUMN shd002_cobranza_realizada.octubre IS 'Mes de octubre';
COMMENT ON COLUMN shd002_cobranza_realizada.noviembre IS 'Mes de noviembre';
COMMENT ON COLUMN shd002_cobranza_realizada.diciembre IS 'Mes de diciembre';














































CREATE TABLE shd003_codigo_ingresos
(
  cod_ingreso integer NOT NULL, -- Código de ingreso...
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
COMMENT ON COLUMN shd003_codigo_ingresos.cod_ingreso IS 'Código de ingreso
01 = Industria y comercio
02 = Vehículos
03 = Propaganda comercial
04 = Inmuebles urbanos
05 = Aseo domiciliario
06 = Arrendamiento de tierras
07 = Crédito de vivienda

';
COMMENT ON COLUMN shd003_codigo_ingresos.denominacion IS 'Denominación de ingreso';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_partida IS 'Partida';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_generica IS 'Código de la generica';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_especifica IS 'Código de la especifica';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_subespec IS 'Código de la Subespecifica';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_auxiliar IS 'Código del auxiliar';
















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

















CREATE TABLE shd100_articulos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  articulos_patente text NOT NULL, -- Artículos de la ordenanza de patente de industria y comercio
  CONSTRAINT shd100_articulos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_articulos OWNER TO sisap;
COMMENT ON TABLE shd100_articulos IS 'Registra los artículos para ser impresos en el boletín de notificación';
COMMENT ON COLUMN shd100_articulos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_articulos.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd100_articulos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_articulos.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN shd100_articulos.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_articulos.articulos_patente IS 'Artículos de la ordenanza de patente de industria y comercio';








CREATE TABLE shd100_patente
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- rif cedula
  numero_solicitud character varying(20) NOT NULL, -- número de solicitud de patente de industria y comercio
  numero_patente character varying(20) NOT NULL, -- Número de patente de industria y comercio
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer NOT NULL, -- Pago todo el año ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año facturado
  ultimo_mes_facturado integer, -- Ultimo mes facturado
  fecha_ultima_decla date, -- Fecha ultima declaración
  ingresos_declarados numeric(26,2), -- Monto ingresos declarados
  ultimo_ejercicio_decla integer, -- Ultimo ejercicio declarado
  periodo_desde date, -- Periodo declarado desde
  periodo_hasta date, -- Periodo declarado hasta
  fecha_patente date NOT NULL, -- Fecha de otorgamiento de la patente
  numero_expediente integer, -- Número de expediente
  CONSTRAINT shd100_patente_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
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
COMMENT ON COLUMN shd100_patente.ultimo_ano_facturado IS 'Ultimo año facturado';
COMMENT ON COLUMN shd100_patente.ultimo_mes_facturado IS 'Ultimo mes facturado';
COMMENT ON COLUMN shd100_patente.fecha_ultima_decla IS 'Fecha ultima declaración';
COMMENT ON COLUMN shd100_patente.ingresos_declarados IS 'Monto ingresos declarados';
COMMENT ON COLUMN shd100_patente.ultimo_ejercicio_decla IS 'Ultimo ejercicio declarado';
COMMENT ON COLUMN shd100_patente.periodo_desde IS 'Periodo declarado desde';
COMMENT ON COLUMN shd100_patente.periodo_hasta IS 'Periodo declarado hasta';
COMMENT ON COLUMN shd100_patente.fecha_patente IS 'Fecha de otorgamiento de la patente';
COMMENT ON COLUMN shd100_patente.numero_expediente IS 'Número de expediente';














CREATE TABLE shd100_declaracion_ingresos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de Idendad del Contribuyente
  ano_declaracion integer NOT NULL, -- Año de Declaración
  numero_declaracion integer NOT NULL, -- Número de Declaración
  periodo_desde date NOT NULL, -- Periodo de Declaración desde
  periodo_hasta date NOT NULL, -- Periodo de Declaración hasta
  capital numeric(26,2), -- Capital
  numero_empleados integer NOT NULL, -- Número de Empleados
  numero_obreros integer NOT NULL, -- Número de Obreros
  fecha_declaracion date NOT NULL, -- Fecha de Declaración de ingresos
  ingresos_declarados numeric(26,2) NOT NULL, -- Monto de los Ingresos declarados
  monto_impuesto numeric(26,2) NOT NULL, -- Monto del impuesto que le corresponde de acuerdo a esta declaración
  capital_anterior numeric(26,2) NOT NULL, -- Capital declarado anterior
  numero_empleados_anterior integer NOT NULL, -- Número de empleados declarado anteriormente
  numero_obreros_anterior integer NOT NULL, -- Número de obreros declarados anteriormente
  monto_impuesto_anterior numeric(26,2) NOT NULL, -- Monto del impuesto que le correspondio cancelar por la declaración anterior
  monto_cancelado_anterior numeric(26,2) NOT NULL, -- Monto de los impuestos cancelados año anterior
  monto_por_cancelar_anterior numeric(26,2) NOT NULL, -- Monto por cancelar de los impuestos que le correspondia cancelar anteriormente
  aumento_monto_impuesto numeric(26,2) NOT NULL, -- Aumento Monto del impuesto, representa la diferencia positiva comparando el Monto del impuesto de esta declaración y el Monto de Impuesto anterior
  disminucion_monto_impuesto numeric(26,2) NOT NULL, -- Disminución Monto del impuesto, representa la diferencia negativa comparando el Monto del impuesto de esta declaración y el Monto de Impuesto anterior
  CONSTRAINT shd100_declaracion_ingresos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion),
  CONSTRAINT shd100_declaracion_ingresos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
      REFERENCES shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) MATCH SIMPLE
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
COMMENT ON COLUMN shd100_declaracion_ingresos.rif_cedula IS 'Rif o Cédula de Idendad del Contribuyente
';
COMMENT ON COLUMN shd100_declaracion_ingresos.ano_declaracion IS 'Año de Declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_declaracion IS 'Número de Declaración ';
COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_desde IS 'Periodo de Declaración desde';
COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_hasta IS 'Periodo de Declaración hasta';
COMMENT ON COLUMN shd100_declaracion_ingresos.capital IS 'Capital';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_empleados IS 'Número de Empleados';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_obreros IS 'Número de Obreros';
COMMENT ON COLUMN shd100_declaracion_ingresos.fecha_declaracion IS 'Fecha de Declaración de ingresos';
COMMENT ON COLUMN shd100_declaracion_ingresos.ingresos_declarados IS 'Monto de los Ingresos declarados';
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_impuesto IS 'Monto del impuesto que le corresponde de acuerdo a esta declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos.capital_anterior IS 'Capital declarado anterior';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_empleados_anterior IS 'Número de empleados declarado anteriormente';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_obreros_anterior IS 'Número de obreros declarados anteriormente';
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_impuesto_anterior IS 'Monto del impuesto que le correspondio cancelar por la declaración anterior';
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_cancelado_anterior IS 'Monto de los impuestos cancelados año anterior';
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_por_cancelar_anterior IS 'Monto por cancelar de los impuestos que le correspondia cancelar anteriormente';
COMMENT ON COLUMN shd100_declaracion_ingresos.aumento_monto_impuesto IS 'Aumento Monto del impuesto, representa la diferencia positiva comparando el Monto del impuesto de esta declaración y el Monto de Impuesto anterior
';
COMMENT ON COLUMN shd100_declaracion_ingresos.disminucion_monto_impuesto IS 'Disminución Monto del impuesto, representa la diferencia negativa comparando el Monto del impuesto de esta declaración y el Monto de Impuesto anterior
';

ALTER TABLE shd100_declaracion_ingresos ADD COLUMN cancelado integer DEFAULT 2;

COMMENT ON COLUMN shd100_declaracion_ingresos.cancelado IS 'Cancelado?
1.- Si
2.- No
';












CREATE TABLE shd100_declaracion_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la depedencia
  rif_cedula character varying(20) NOT NULL, -- rif cedula
  numero_declaracion character varying(20) NOT NULL, -- Número de declaración
  cod_actividad character varying(20) NOT NULL, -- Código de actividad economica declarada por el contribuyente
  monto_ingresos numeric(26,2) NOT NULL, -- Monto de ingreso declarado por el contribuyente
  monto_impuesto numeric(26,2) NOT NULL, -- Monto del impuesto calculado según los ingresos declarados por el contribuyente
  alicuota_aplicada numeric(3,2), -- Alicuota aplicada
  CONSTRAINT shd100_declaracion_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,rif_cedula, numero_declaracion, cod_actividad),
  CONSTRAINT shd100_declaracion_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,rif_cedula, numero_declaracion)
      REFERENCES shd100_declaracion_ingresos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_declaracion) MATCH SIMPLE
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
COMMENT ON COLUMN shd100_declaracion_actividades.numero_declaracion IS 'Número de declaración';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_actividad IS 'Código de actividad economica declarada por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.monto_ingresos IS 'Monto de ingreso declarado por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.monto_impuesto IS 'Monto del impuesto calculado según los ingresos declarados por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.alicuota_aplicada IS 'Alicuota aplicada';










CREATE TABLE shd100_patente_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- rif cedula
  cod_actividad character varying(20) NOT NULL, -- Código de la actividad economica
  numero_aforos integer NOT NULL, -- Número de aforos
  monto_aforo_anual numeric(26,2) NOT NULL, -- Monto aforo anual
  total_aforo_anual numeric(26,2) NOT NULL, -- Total aforo anual
  CONSTRAINT shd100_patente_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,rif_cedula, cod_actividad),
  CONSTRAINT shd100_patente_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,rif_cedula)
      REFERENCES shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) MATCH SIMPLE
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
COMMENT ON COLUMN shd100_patente_actividades.cod_actividad IS 'Código de la actividad economica';
COMMENT ON COLUMN shd100_patente_actividades.numero_aforos IS 'Número de aforos';
COMMENT ON COLUMN shd100_patente_actividades.monto_aforo_anual IS 'Monto aforo anual';
COMMENT ON COLUMN shd100_patente_actividades.total_aforo_anual IS 'Total aforo anual';










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
  horario_trab_desde character varying(7),
  horario_trab_hasta character varying(7),
  distancia_bar numeric(8,3),
  distancia_hospital numeric(8,3),
  distancia_educativo numeric(8,3),
  distancia_funeraria numeric(8,3),
  distancia_estacion numeric(8,3),
  distancia_gubernam numeric(8,3),
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
  ultimo_ano_facturado integer, -- Ultimo año facturado
  ultimo_mes_facturado integer, -- Ultimo mes facturado
  CONSTRAINT shd200_vehiculos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, placa_vehiculo)
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
COMMENT ON COLUMN shd200_vehiculos.ultimo_ano_facturado IS 'Ultimo año facturado';
COMMENT ON COLUMN shd200_vehiculos.ultimo_mes_facturado IS 'Ultimo mes facturado';















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
  denominacion character varying(200),
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
  ultimo_ano_facturado integer, -- Ultimo año facturado
  ultimo_mes_facturado integer, -- Ultimo mes facturado
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
COMMENT ON COLUMN shd300_propaganda.ultimo_ano_facturado IS 'Ultimo año facturado';
COMMENT ON COLUMN shd300_propaganda.ultimo_mes_facturado IS 'Ultimo mes facturado';
















































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
  ultimo_ano_facturado integer, -- Ultimo año facturado
  ultimo_mes_facturado integer, -- Ultimo mes facturado
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
COMMENT ON COLUMN shd400_propiedad.ultimo_ano_facturado IS 'Ultimo año facturado';
COMMENT ON COLUMN shd400_propiedad.ultimo_mes_facturado IS 'Ultimo mes facturado';



















































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
  ultimo_ano_facturado integer, -- Ultimo año facturado
  ultimo_mes_facturado integer, -- Ultimo mes facturado
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
COMMENT ON COLUMN shd500_aseo_domiciliario.ultimo_ano_facturado IS 'Ultimo año facturado';
COMMENT ON COLUMN shd500_aseo_domiciliario.ultimo_mes_facturado IS 'Ultimo mes facturado';




CREATE TABLE shd600_solicitud_arrendamiento
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  fecha_solicitud date NOT NULL, -- Fecha de la solicitud de arrendamiento
  opcion integer NOT NULL, -- Opción...
  cod_ficha character varying(20) NOT NULL, -- Código de la ficha catastral
  expectativa_construccion text, -- Expectativa de construcción que tiene el solicitante
  CONSTRAINT shd600_solicitud_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud)
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_solicitud_arrendamiento OWNER TO sisap;
COMMENT ON TABLE shd600_solicitud_arrendamiento IS 'Registra la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.rif_cedula IS 'Rif o Cédula de identidad';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.numero_solicitud IS 'Número de solicitud';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.fecha_solicitud IS 'Fecha de la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.opcion IS 'Opción
1.- Simple
2.- Compra';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_ficha IS 'Código de la ficha catastral';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.expectativa_construccion IS 'Expectativa de construcción que tiene el solicitante';





CREATE TABLE shd600_aprobacion_arrendamiento
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL,
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud de arrendamiento
  fecha_aprobacion date NOT NULL, -- Fecha de aprobacion
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  datos_registro_arrendamiento text NOT NULL, -- Datos registro de arrendamiento
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer, -- Contribuyente pago todo el año ?...
  suspendido integer NOT NULL, -- Pago del contribuyente esta suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año facturado
  ultimo_mes_facturado integer, -- Ultimo mes facturado
  terreno_vendido integer, -- Terreno vendido ?...
  CONSTRAINT shd600_aprobacion_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud),
  CONSTRAINT shd600_aprobacion_arrendamiento_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud)
      REFERENCES shd600_solicitud_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) MATCH SIMPLE
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
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_ano_facturado IS 'Ultimo año facturado';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_mes_facturado IS 'Ultimo mes facturado';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.terreno_vendido IS 'Terreno vendido ?
1.- Si
2.- No';





CREATE TABLE shd600_compra_terreno
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL,
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  fecha_compra date NOT NULL, -- Fecha de compra
  datos_compra text NOT NULL, -- Datos de la compra
  monto numeric(26,2) NOT NULL, -- Monto de compra
  CONSTRAINT shd600_compra_terreno_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud),
  CONSTRAINT shd600_compra_terreno_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud)
      REFERENCES shd600_aprobacion_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) MATCH SIMPLE
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













CREATE TABLE shd700_credito_vivienda
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  numero_solicitud character varying(20) NOT NULL, -- numero de solicitud
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
  ultimo_ano_facturado integer NOT NULL, -- Ultimo año facturado
  ultimo_mes_facturado integer, -- Ultimo mes facturado
  area_construccion numeric(10,3),
  area_terreno numeric(10,3),
  norte text,
  sur text,
  este text,
  oeste text,
  tasa_interes numeric(5,2),
  fecha_entrega_contrato date,
  CONSTRAINT shd700_credito_vivienda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula,numero_solicitud)
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
COMMENT ON COLUMN shd700_credito_vivienda.ultimo_ano_facturado IS 'Ultimo año facturado';
COMMENT ON COLUMN shd700_credito_vivienda.ultimo_mes_facturado IS 'Ultimo mes facturado';
































CREATE TABLE shd700_credito_vivienda_parentesco
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- numero solicitud
  rif_cedula character varying(20) NOT NULL, -- Rif o cédula del solicitante
  cod_parentesco integer NOT NULL, -- Código de parentesco
  nombre_apellido character varying(100) NOT NULL, -- Nombre del apellido de pariente
  sexo integer NOT NULL, -- Sexo...
  fecha_nacimiento date NOT NULL, -- Fecha de nacimiento
  CONSTRAINT shd700_credito_vivienda_parentesco_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula,numero_solicitud, cod_parentesco),
  CONSTRAINT shd700_credito_vivienda_parentesco_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula,numero_solicitud)
      REFERENCES shd700_credito_vivienda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula,numero_solicitud) MATCH SIMPLE
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





CREATE TABLE shd900_cobranza_acumulada
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano integer NOT NULL, -- Año
  mes integer NOT NULL, -- Mes
  dia integer NOT NULL, -- Dia
  cod_partida integer NOT NULL, -- Código de partida
  cod_generica integer NOT NULL, -- Código de generica
  cod_especifica integer NOT NULL, -- Código de especifica
  cod_sub_espec integer NOT NULL, -- Código de subespecifica
  cod_auxiliar integer NOT NULL, -- Código de auxiliar
  deuda_vigente numeric(26,2), -- Deuda vigente
  deuda_anterior numeric(26,2), -- Deuda anterior
  monto_recargo numeric(26,2), -- Monto recargo
  monto_multa numeric(26,2), -- Monto multa
  monto_intereses numeric(26,2), -- Monto intereses
  monto_descuento numeric(26,2), -- Monto descuento
  cantidad_depositos integer, -- Cantidad de depositos
  monto_depositos numeric(26,2), -- Monto de depositos
  cantidad_notas_credito integer, -- Cantidad notas de crédito
  monto_notas_credito numeric(26,2), -- Monto notas de crédito
  cantidad_cheques integer, -- Cantidad de cheques
  monto_cheques numeric(26,2), -- Monto de cheques
  cantidad_descuento integer, -- Cantidad de descuentos
  cantidad_pagos_efectivo integer, -- Cantidad de pagos en efectivo
  monto_pagos_efectivo numeric(26,2), -- Monto de pagos en efectivo
  CONSTRAINT shd999_cobranza_acumulada_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, mes, dia, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar)
)
WITH (OIDS=FALSE);
ALTER TABLE shd900_cobranza_acumulada OWNER TO sisap;
COMMENT ON TABLE shd900_cobranza_acumulada IS 'Registra cobranza acumulada';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd900_cobranza_acumulada.ano IS 'Año';
COMMENT ON COLUMN shd900_cobranza_acumulada.mes IS 'Mes';
COMMENT ON COLUMN shd900_cobranza_acumulada.dia IS 'Dia';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_partida IS 'Código de partida';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_generica IS 'Código de generica';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_especifica IS 'Código de especifica';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_sub_espec IS 'Código de subespecifica';
COMMENT ON COLUMN shd900_cobranza_acumulada.cod_auxiliar IS 'Código de auxiliar';
COMMENT ON COLUMN shd900_cobranza_acumulada.deuda_vigente IS 'Deuda vigente';
COMMENT ON COLUMN shd900_cobranza_acumulada.deuda_anterior IS 'Deuda anterior';
COMMENT ON COLUMN shd900_cobranza_acumulada.monto_recargo IS 'Monto recargo';
COMMENT ON COLUMN shd900_cobranza_acumulada.monto_multa IS 'Monto multa';
COMMENT ON COLUMN shd900_cobranza_acumulada.monto_intereses IS 'Monto intereses';
COMMENT ON COLUMN shd900_cobranza_acumulada.monto_descuento IS 'Monto descuento';
COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_depositos IS 'Cantidad de depositos';
COMMENT ON COLUMN shd900_cobranza_acumulada.monto_depositos IS 'Monto de depositos';
COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_notas_credito IS 'Cantidad notas de crédito';
COMMENT ON COLUMN shd900_cobranza_acumulada.monto_notas_credito IS 'Monto notas de crédito';
COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_cheques IS 'Cantidad de cheques';
COMMENT ON COLUMN shd900_cobranza_acumulada.monto_cheques IS 'Monto de cheques';
COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_descuento IS 'Cantidad de descuentos';
COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_pagos_efectivo IS 'Cantidad de pagos en efectivo';
COMMENT ON COLUMN shd900_cobranza_acumulada.monto_pagos_efectivo IS 'Monto de pagos en efectivo';




-- Table: shd900_planillas_deuda_cobro_cuerpo

-- DROP TABLE shd900_planillas_deuda_cobro_cuerpo;

CREATE TABLE shd900_planillas_deuda_cobro_cuerpo
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_partida integer NOT NULL, -- Código de la partida
  cod_generica integer NOT NULL, -- Código de genérica
  cod_especifica integer NOT NULL, -- Código de especifica
  cod_sub_espec integer NOT NULL, -- Código Subespecifica
  cod_auxiliar integer NOT NULL, -- Código de auxiliar
  rif_cedula character varying(20) NOT NULL, -- RIF o Cédula de Identidad
  cod_numero_catastral_placas character varying(20) NOT NULL, -- Código número de ficha catastra o Placas del Vehículo...
  deuda_ano_anterior numeric(26,2),
  CONSTRAINT shd900_planillas_deuda_cobro_cuerpo_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, rif_cedula, cod_numero_catastral_placas)
)
WITH (OIDS=FALSE);
ALTER TABLE shd900_planillas_deuda_cobro_cuerpo OWNER TO sisap;
COMMENT ON TABLE shd900_planillas_deuda_cobro_cuerpo IS 'Registro estadistico de Deudas y Cobros';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_partida IS 'Código de la partida';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_generica IS 'Código de genérica';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_especifica IS 'Código de especifica';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_sub_espec IS 'Código Subespecifica';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_auxiliar IS 'Código de auxiliar';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.rif_cedula IS 'RIF o Cédula de Identidad';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_numero_catastral_placas IS 'Código número de ficha catastra o Placas del Vehículo

Se utiliza solamente en estos casos los demás tipos de ingresos no lo requieren';






 --Table: shd900_planillas_deuda_cobro_detalles

-- DROP TABLE shd900_planillas_deuda_cobro_detalles;

CREATE TABLE shd900_planillas_deuda_cobro_detalles
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_partida integer NOT NULL, -- Código de la partida
  cod_generica integer NOT NULL, -- Código de genérica
  cod_especifica integer NOT NULL, -- Código de especifica
  cod_sub_espec integer NOT NULL, -- Código Subespecifica
  cod_auxiliar integer NOT NULL, -- Código de auxiliar
  rif_cedula character varying(20) NOT NULL, -- RIF o Cédula de Identidad
  cod_numero_catastral_placas character varying(20) NOT NULL, -- Código número de ficha catastra o Placas del Vehículo...
  ano integer NOT NULL, -- Año
  mes integer NOT NULL, -- Mes
  numero_planilla integer NOT NULL, -- Número de planilla de liquidación previa
  deuda_vigente numeric(26,2), -- Deuda vigente
  monto_recargo numeric(26,2), -- Monto por recargo
  monto_multa numeric(26,2), -- Monto por multa
  monto_intereses numeric(26,2), -- Moonto por intereses
  monto_descuento numeric(26,2), -- Descuento
  cancelado integer NOT NULL DEFAULT 2, -- Cancelado ?...
  CONSTRAINT shd900_planillas_deuda_cobro_detalles_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, rif_cedula, cod_numero_catastral_placas, ano, mes, numero_planilla),
  CONSTRAINT shd900_planillas_deuda_cobro_detalles_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, rif_cedula, cod_numero_catastral_placas)
      REFERENCES shd900_planillas_deuda_cobro_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, rif_cedula, cod_numero_catastral_placas) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (OIDS=FALSE);
ALTER TABLE shd900_planillas_deuda_cobro_detalles OWNER TO sisap;
COMMENT ON TABLE shd900_planillas_deuda_cobro_detalles IS 'Registro estadistico de Deudas y Cobros';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_partida IS 'Código de la partida';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_generica IS 'Código de genérica';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_especifica IS 'Código de especifica';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_sub_espec IS 'Código Subespecifica';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_auxiliar IS 'Código de auxiliar';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.rif_cedula IS 'RIF o Cédula de Identidad';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_numero_catastral_placas IS 'Código número de ficha catastra o Placas del Vehículo

Se utiliza solamente en estos casos los demás tipos de ingresos no lo requieren';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.ano IS 'Año';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.mes IS 'Mes';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.numero_planilla IS 'Número de planilla de liquidación previa';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.deuda_vigente IS 'Deuda vigente';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.monto_recargo IS 'Monto por recargo';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.monto_multa IS 'Monto por multa';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.monto_intereses IS 'Moonto por intereses';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.monto_descuento IS 'Descuento';
COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cancelado IS 'Cancelado ?
1.- Si
2.- No
Pregunta si el recibo esta cancelado o no esta cancelado';



CREATE TABLE shd900_cobranza_numero
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de dependencia
  ano_comprobante integer NOT NULL, -- Año del comprobante
  numero_comprobante integer NOT NULL, -- Número de comprobante
  situacion integer NOT NULL, -- Situación:...
  CONSTRAINT shd900_cobranza_numero_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante, numero_comprobante)
)
WITH (OIDS=FALSE);
ALTER TABLE shd900_cobranza_numero OWNER TO sisap;
COMMENT ON TABLE shd900_cobranza_numero IS 'Control de número de comprobantes de otros ingresos';
COMMENT ON COLUMN shd900_cobranza_numero.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd900_cobranza_numero.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd900_cobranza_numero.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd900_cobranza_numero.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN shd900_cobranza_numero.cod_dep IS 'Código de dependencia';
COMMENT ON COLUMN shd900_cobranza_numero.ano_comprobante IS 'Año del comprobante';
COMMENT ON COLUMN shd900_cobranza_numero.numero_comprobante IS 'Número de comprobante';
COMMENT ON COLUMN shd900_cobranza_numero.situacion IS 'Situación:
1.- Sin utilizar
2.- Seleccionado
3.- Emitida
4.- Anulada
5.- Congelada';



CREATE TABLE shd900_cobranza_diaria
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_comprobante integer NOT NULL, -- Año del comprobante
  numero_comprobante integer NOT NULL, -- Número del comprobante
  cod_partida integer NOT NULL, -- Código de partida
  cod_generica integer NOT NULL, -- Código de generica
  cod_especifica integer NOT NULL, -- Código de especifica
  cod_sub_espec integer NOT NULL, -- Código de subespecifica
  cod_auxiliar integer NOT NULL, -- Código de auxiliar
  fecha_comprobante date NOT NULL, -- Fecha de comprobante
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  concepto_comprobante text NOT NULL, -- Concepto del comprobante
  deuda_anterior numeric(26,2), -- Deuda años anteriores
  deuda_vigente numeric(26,2), -- Deuda vigente
  monto_recargo numeric(26,2), -- Monto de recargo
  monto_multa numeric(26,2), -- Monto multa
  monto_intereses numeric(26,2), -- Monto intereses
  monto_descuento numeric(26,2), -- Monto descuento
  cod_entidad_deposito integer, -- Código de la entidad bancaria por deposito
  cod_sucursal_deposito integer, -- Código sucursal bancaria por deposito
  cuenta_bancaria_deposito character varying(20), -- Cuenta bancaria por deposito
  numero_deposito character varying(20), -- Número de deposito
  monto_deposito numeric(26,2), -- Monto de deposito
  fecha_deposito date, -- Fecha de deposito
  cod_entidad_credito integer, -- Código entidad bancaria por nota de crédito
  cod_sucursal_credito integer, -- Código sucursal bancaria por nota de crédito
  cuenta_bancaria_credito character varying(20), -- Cuenta bancaria por nota de crédito
  numero_nota_credito character varying(20), -- Número de nota de crédito
  monto_nota_credito numeric(26,2), -- Monto nota de crédito
  fecha_nota_credito date, -- Fecha nota de crédito
  cod_entidad_cheque integer, -- Código entidad bancaria por cheque
  cod_sucursal_cheque integer, -- Código sucursal bancaria por cheque
  cuenta_bancaria_cheque character varying(20), -- Cuenta bancaria de cheque
  numero_cheque integer, -- Número de cheque
  monto_cheque numeric(26,2), -- Monto del cheque
  fecha_cheque date, -- Fecha de cheque
  monto_efectivo numeric(26,2), -- Monto efectivo
  condicion_documento integer NOT NULL, -- Condición del documento...
  fecha_registro date NOT NULL, -- Fecha de registro
  username_registro character varying(60), -- Operador que registro el cobro
  ano_anulacion integer, -- año de anulación
  numero_anulacion integer, -- Número de anulación
  fecha_anulacion date, -- Fecha de anulación
  username_anulacion character varying(60), -- Operador que anulo el cobro
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula del cobrador
  CONSTRAINT shd900_cobranza_diaria_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante, numero_comprobante)
)
WITH (OIDS=FALSE);
ALTER TABLE shd900_cobranza_diaria OWNER TO sisap;
COMMENT ON TABLE shd900_cobranza_diaria IS 'Registro de cobros efectuados por razón a otros ingresos';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_presi IS 'Código de la presidencia
';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd900_cobranza_diaria.ano_comprobante IS 'Año del comprobante';
COMMENT ON COLUMN shd900_cobranza_diaria.numero_comprobante IS 'Número del comprobante';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_partida IS 'Código de partida';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_generica IS 'Código de generica';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_especifica IS 'Código de especifica';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_sub_espec IS 'Código de subespecifica';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_auxiliar IS 'Código de auxiliar';
COMMENT ON COLUMN shd900_cobranza_diaria.fecha_comprobante IS 'Fecha de comprobante';
COMMENT ON COLUMN shd900_cobranza_diaria.rif_cedula IS 'Rif o Cédula de identidad';
COMMENT ON COLUMN shd900_cobranza_diaria.concepto_comprobante IS 'Concepto del comprobante
';
COMMENT ON COLUMN shd900_cobranza_diaria.deuda_vigente IS 'Deuda vigente';
COMMENT ON COLUMN shd900_cobranza_diaria.deuda_anterior IS 'Deuda años anteriores';
COMMENT ON COLUMN shd900_cobranza_diaria.monto_recargo IS 'Monto de recargo';
COMMENT ON COLUMN shd900_cobranza_diaria.monto_multa IS 'Monto multa';
COMMENT ON COLUMN shd900_cobranza_diaria.monto_intereses IS 'Monto intereses';
COMMENT ON COLUMN shd900_cobranza_diaria.monto_descuento IS 'Monto descuento';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_entidad_deposito IS 'Código de la entidad bancaria por deposito';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_sucursal_deposito IS 'Código sucursal bancaria por deposito';
COMMENT ON COLUMN shd900_cobranza_diaria.cuenta_bancaria_deposito IS 'Cuenta bancaria por deposito';
COMMENT ON COLUMN shd900_cobranza_diaria.numero_deposito IS 'Número de deposito';
COMMENT ON COLUMN shd900_cobranza_diaria.monto_deposito IS 'Monto de deposito';
COMMENT ON COLUMN shd900_cobranza_diaria.fecha_deposito IS 'Fecha de deposito';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_entidad_credito IS 'Código entidad bancaria por nota de crédito';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_sucursal_credito IS 'Código sucursal bancaria por nota de crédito';
COMMENT ON COLUMN shd900_cobranza_diaria.cuenta_bancaria_credito IS 'Cuenta bancaria por nota de crédito';
COMMENT ON COLUMN shd900_cobranza_diaria.numero_nota_credito IS 'Número de nota de crédito';
COMMENT ON COLUMN shd900_cobranza_diaria.monto_nota_credito IS 'Monto nota de crédito';
COMMENT ON COLUMN shd900_cobranza_diaria.fecha_nota_credito IS 'Fecha nota de crédito';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_entidad_cheque IS 'Código entidad bancaria por cheque';
COMMENT ON COLUMN shd900_cobranza_diaria.cod_sucursal_cheque IS 'Código sucursal bancaria por cheque';
COMMENT ON COLUMN shd900_cobranza_diaria.cuenta_bancaria_cheque IS 'Cuenta bancaria de cheque';
COMMENT ON COLUMN shd900_cobranza_diaria.numero_cheque IS 'Número de cheque';
COMMENT ON COLUMN shd900_cobranza_diaria.monto_cheque IS 'Monto del cheque';
COMMENT ON COLUMN shd900_cobranza_diaria.fecha_cheque IS 'Fecha de cheque';
COMMENT ON COLUMN shd900_cobranza_diaria.monto_efectivo IS 'Monto efectivo';
COMMENT ON COLUMN shd900_cobranza_diaria.condicion_documento IS 'Condición del documento
1.- Activo
2.- Anulado
';
COMMENT ON COLUMN shd900_cobranza_diaria.fecha_registro IS 'Fecha de registro
';
COMMENT ON COLUMN shd900_cobranza_diaria.username_registro IS 'Operador que registro el cobro';
COMMENT ON COLUMN shd900_cobranza_diaria.ano_anulacion IS 'año de anulación';
COMMENT ON COLUMN shd900_cobranza_diaria.numero_anulacion IS 'Número de anulación
';
COMMENT ON COLUMN shd900_cobranza_diaria.fecha_anulacion IS 'Fecha de anulación
';
COMMENT ON COLUMN shd900_cobranza_diaria.username_anulacion IS 'Operador que anulo el cobro';
COMMENT ON COLUMN shd900_cobranza_diaria.rif_ci_cobrador IS 'Rif o Cédula de identidad';



CREATE TABLE shd950_solvencia
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano integer NOT NULL, -- Año de solvencia
  numero_solvencia integer NOT NULL, -- Número de solvencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad del contribuyente
  fecha_expedicion date NOT NULL, -- Fecha de expedición
  valida_hasta date NOT NULL, -- Fecha de expiración de la solvencia
  objeto_solvencia integer NOT NULL, -- Objeto de la solvencia
  monto_solvencia numeric(26,2) NOT NULL, -- Monto de la solvencia
  observaciones text, -- Observaciones
  CONSTRAINT shd950_solvencia_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, numero_solvencia)
)
WITH (OIDS=FALSE);
ALTER TABLE shd950_solvencia OWNER TO sisap;
COMMENT ON TABLE shd950_solvencia IS 'Registro de solvencias';
COMMENT ON COLUMN shd950_solvencia.cod_presi IS 'Código de la presidencia
';
COMMENT ON COLUMN shd950_solvencia.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd950_solvencia.cod_tipo_inst IS 'Código tipo institución';
COMMENT ON COLUMN shd950_solvencia.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd950_solvencia.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd950_solvencia.ano IS 'Año de solvencia';
COMMENT ON COLUMN shd950_solvencia.numero_solvencia IS 'Número de solvencia';
COMMENT ON COLUMN shd950_solvencia.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';
COMMENT ON COLUMN shd950_solvencia.fecha_expedicion IS 'Fecha de expedición';
COMMENT ON COLUMN shd950_solvencia.valida_hasta IS 'Fecha de expiración de la solvencia';
COMMENT ON COLUMN shd950_solvencia.objeto_solvencia IS 'Objeto de la solvencia
';
COMMENT ON COLUMN shd950_solvencia.monto_solvencia IS 'Monto de la solvencia';
COMMENT ON COLUMN shd950_solvencia.observaciones IS 'Observaciones
';







CREATE TABLE shd950_solvencia_monto
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  monto_solvencia numeric(26,2) NOT NULL, -- Monto fijo de la solvencia
  CONSTRAINT shd950_solvencia_monto_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep)
)
WITH (OIDS=FALSE);
ALTER TABLE shd950_solvencia_monto OWNER TO sisap;
COMMENT ON TABLE shd950_solvencia_monto IS 'Registra el monto de la solvencia';
COMMENT ON COLUMN shd950_solvencia_monto.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd950_solvencia_monto.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd950_solvencia_monto.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd950_solvencia_monto.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd950_solvencia_monto.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd950_solvencia_monto.monto_solvencia IS 'Monto fijo de la solvencia';


























CREATE TABLE shd950_solvencia_numero
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano integer NOT NULL, -- Año
  numero_solvencia integer NOT NULL, -- Número de solvencia
  situacion integer NOT NULL, -- Situación...
  CONSTRAINT shd950_solvencias_numero_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, numero_solvencia)
)
WITH (OIDS=FALSE);
ALTER TABLE shd950_solvencia_numero OWNER TO sisap;
COMMENT ON TABLE shd950_solvencia_numero IS 'Controla el número de las solvencias
';
COMMENT ON COLUMN shd950_solvencia_numero.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd950_solvencia_numero.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd950_solvencia_numero.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd950_solvencia_numero.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd950_solvencia_numero.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd950_solvencia_numero.ano IS 'Año';
COMMENT ON COLUMN shd950_solvencia_numero.numero_solvencia IS 'Número de solvencia';
COMMENT ON COLUMN shd950_solvencia_numero.situacion IS 'Situación 1.- Sin utilizar
2.- Seleccionado
3.- Emitida
4.- Anulada
5.- Congelada';



ALTER TABLE shd900_planillas_deuda_cobro_detalles ADD COLUMN fecha_emision date;

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.fecha_emision IS 'Fecha de emisión es igual a la fecha de actualización de las planillas';







ALTER TABLE shd950_solvencia ADD COLUMN condicion_actividad integer;
ALTER TABLE shd950_solvencia ADD COLUMN fecha_registro date;
ALTER TABLE shd950_solvencia ADD COLUMN username_registro character varying(60);
ALTER TABLE shd950_solvencia ADD COLUMN fecha_anulacion date;
ALTER TABLE shd950_solvencia ADD COLUMN username_anulacion character varying(60);


ALTER TABLE shd950_solvencia
   ALTER COLUMN condicion_actividad SET DEFAULT 1;
COMMENT ON COLUMN shd950_solvencia.condicion_actividad IS 'Condición de actividad
1.- Activa
2.- Anulada

';
COMMENT ON COLUMN shd950_solvencia.fecha_registro IS 'Fecha de registro';
COMMENT ON COLUMN shd950_solvencia.username_registro IS 'Operdor que registro la solvencia';
COMMENT ON COLUMN shd950_solvencia.fecha_anulacion IS 'Fecha de anulación
';
COMMENT ON COLUMN shd950_solvencia.username_anulacion IS 'Operador que anulo la solvencia';



-- Table: shd100_control_industria_comercio

-- DROP TABLE shd100_control_industria_comercio;

CREATE TABLE shd100_control_industria_comercio
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la Dependencia
  utiliza_planillas_liquidacion_previa integer, -- Utiliza planillas de liquidación previa?  o el cobro se realiza mediante la declaración de los ingresos brutos...
  frecuencia_pago_segun_ordenanza integer NOT NULL, -- Frecuencia de pago según la ordenanza...
  CONSTRAINT shd100_control_industria_comercio_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_control_industria_comercio OWNER TO sisap;
COMMENT ON TABLE shd100_control_industria_comercio IS 'Indica la forma como debe presentarse y comportarse el la Sección de Impuesto Sobre Patente de Industria y Comercio';
COMMENT ON COLUMN shd100_control_industria_comercio.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_control_industria_comercio.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd100_control_industria_comercio.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_control_industria_comercio.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_control_industria_comercio.cod_dep IS 'Código de la Dependencia
';
COMMENT ON COLUMN shd100_control_industria_comercio.utiliza_planillas_liquidacion_previa IS 'Utiliza planillas de liquidación previa?  o el cobro se realiza mediante la declaración de los ingresos brutos
1.- Si
2.- No';
COMMENT ON COLUMN shd100_control_industria_comercio.frecuencia_pago_segun_ordenanza IS 'Frecuencia de pago según la ordenanza
1.- Mensual
2.- Bimensual
3.- Trimestral
4.- Semestral
5.- Anual
';


ALTER TABLE shd300_detalles_adicional ADD COLUMN porcentaje_recargo numeric(3,2);
COMMENT ON COLUMN shd300_detalles_adicional.porcentaje_recargo IS 'Porcentaje de recargo';



ALTER TABLE shd100_declaracion_ingresos ADD COLUMN monto_intereses numeric(26,2);
ALTER TABLE shd100_declaracion_ingresos ADD COLUMN acumulado_pagos_parciales numeric(26,2);
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_intereses IS 'Monto de intereses generados en esta declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos.acumulado_pagos_parciales IS 'Monto acumulado de pagos parciales por convenimiento de pago';

-- Table: shd100_declaracion_ingresos_convenimientos

-- DROP TABLE shd100_declaracion_ingresos_convenimientos;

CREATE TABLE shd100_declaracion_ingresos_convenimientos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la Dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula del contribuyente
  ano_declaracion integer NOT NULL, -- Año de la declaración de ingresos brutos
  numero_declaracion integer NOT NULL, -- Número de declaración de ingresos brutos
  ano_convenimiento integer NOT NULL, -- Año de convenimiento
  numero_convenimiento integer NOT NULL, -- Número de convenimiento
  monto_deuda numeric(26,2) NOT NULL, -- Monto de la deuda de este convenimiento
  fecha_acordada_pago date NOT NULL, -- Fecha acordada para el pago
  monto_convenido numeric(26,2) NOT NULL, -- Monto convenido a pagar
  deuda_pendiente numeric(26,2) NOT NULL, -- Deuda pendiente después de cancelar este convenimiento
  fecha_cancelacion date, -- Fecha cuando se cancelo el convenimiento
  cancelado integer DEFAULT 2, -- Cancelado?...
  CONSTRAINT shd100_declaracion_ingresos_convenimientos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento),
  CONSTRAINT shd100_declaracion_ingresos_convenimiento_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion)
      REFERENCES shd100_declaracion_ingresos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_declaracion_ingresos_convenimientos OWNER TO sisap;
COMMENT ON TABLE shd100_declaracion_ingresos_convenimientos IS 'Registra los convenimientos de pago';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_dep IS 'Código de la Dependencia';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.rif_cedula IS 'Rif o Cédula del contribuyente';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.ano_declaracion IS 'Año de la declaración de ingresos brutos';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.numero_declaracion IS 'Número de declaración de ingresos brutos';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.ano_convenimiento IS 'Año de convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.numero_convenimiento IS 'Número de convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.monto_deuda IS 'Monto de la deuda de este convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.fecha_acordada_pago IS 'Fecha acordada para el pago';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.monto_convenido IS 'Monto convenido a pagar
';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.deuda_pendiente IS 'Deuda pendiente después de cancelar este convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.fecha_cancelacion IS 'Fecha cuando se cancelo el convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cancelado IS 'Cancelado?
1.- SI
2.- NO';


-- Table: shd100_declaracion_ingresos_facturado_convenimientos
-- DROP TABLE shd100_declaracion_ingresos_facturado_convenimientos;
CREATE TABLE shd100_declaracion_ingresos_facturado_convenimientos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de Idendad del Contribuyente
  ano_comprobante integer NOT NULL, -- Año de Comprobante
  numero_comprobante integer NOT NULL, -- Número de Comprobante
  ano_declaracion integer NOT NULL, -- Año de Declaración
  numero_declaracion integer NOT NULL, -- Número de Declaración
  ano_convenimiento integer NOT NULL, -- Año de convenimiento
  numero_convenimiento integer NOT NULL, -- Número de convenimiento
  CONSTRAINT shd100_declaracion_ingresos_facturado_convenimientos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_comprobante, numero_comprobante, ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_declaracion_ingresos_facturado_convenimientos OWNER TO sisap;
COMMENT ON TABLE shd100_declaracion_ingresos_facturado_convenimientos IS 'Registro de la declaración de ingresos brutos de los contribuyentes';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.rif_cedula IS 'Rif o Cédula de Idendad del Contribuyente';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.ano_comprobante IS 'Año de Comprobante';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.numero_comprobante IS 'Número de Comprobante';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.ano_declaracion IS 'Año de Declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.numero_declaracion IS 'Número de Declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.ano_convenimiento IS 'Año de convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado_convenimientos.numero_convenimiento IS 'Número de convenimiento';
