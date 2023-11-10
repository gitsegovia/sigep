-- Table: cnmd08_historia_nomina
DROP TABLE cnmd08_historia_transacciones;
DROP TABLE cnmd08_historia_trabajador;
DROP TABLE cnmd08_historia_nomina;
 
 
 
CREATE TABLE cnmd08_historia_nomina
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo_nomina integer NOT NULL, -- Código tipo de nómina
  ano integer NOT NULL, -- Año
  numero_nomina integer NOT NULL, -- Número de nómina
  periodo_desde date NOT NULL, -- Fecha de comienzo de pago en nómina
  periodo_hasta date NOT NULL, -- Fecha de terminación de pago en nómina
  concepto text NOT NULL, -- Concepto de pago
  numero_recibo integer NOT NULL, -- Número de recibo
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago ...
  mensaje_colectivo text,
  CONSTRAINT cnmd08_historia_nomina_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina),
  CONSTRAINT cnmd08_historia_nomina_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina)
      REFERENCES cnmd01 (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE cnmd08_historia_nomina OWNER TO sisap;
COMMENT ON TABLE cnmd08_historia_nomina IS 'Registra la historia de pago por tipo de nomina
';
COMMENT ON COLUMN cnmd08_historia_nomina.cod_presi IS 'Código de la presidencia
';
COMMENT ON COLUMN cnmd08_historia_nomina.cod_entidad IS 'Código de la entidad
';
COMMENT ON COLUMN cnmd08_historia_nomina.cod_tipo_inst IS 'Código tipo de Institución
';
COMMENT ON COLUMN cnmd08_historia_nomina.cod_inst IS 'Código de la Institución
';
COMMENT ON COLUMN cnmd08_historia_nomina.cod_dep IS 'Código de la dependencia
';
COMMENT ON COLUMN cnmd08_historia_nomina.cod_tipo_nomina IS 'Código tipo de nómina
';
COMMENT ON COLUMN cnmd08_historia_nomina.ano IS 'Año
';
COMMENT ON COLUMN cnmd08_historia_nomina.numero_nomina IS 'Número de nómina
';
COMMENT ON COLUMN cnmd08_historia_nomina.periodo_desde IS 'Fecha de comienzo de pago en nómina
';
COMMENT ON COLUMN cnmd08_historia_nomina.periodo_hasta IS 'Fecha de terminación de pago en nómina';
COMMENT ON COLUMN cnmd08_historia_nomina.concepto IS 'Concepto de pago
';
COMMENT ON COLUMN cnmd08_historia_nomina.numero_recibo IS 'Número de recibo
';
COMMENT ON COLUMN cnmd08_historia_nomina.frecuencia_pago IS 'Frecuencia de pago 
Semanal
01.- 1era. semana
02.- 2da.  semana
03.- 3era. semana
04.- 4ta.   semana
05.- 5ta.   semana
Quincenal
06.- 1era. quincena
07.- 2da.  quincena
Otras
08.- Pago único
';


-- Table: cnmd08_historia_transacciones

CREATE TABLE cnmd08_historia_trabajador
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo_nomina integer NOT NULL, -- Código tipo de nómina
  ano integer NOT NULL, -- Año
  numero_nomina integer NOT NULL, -- Número de nómina
  cod_cargo integer NOT NULL, -- Código de cargo
  cod_ficha integer NOT NULL, -- Código de la ficha
  cedula_identidad integer NOT NULL, -- Cédula de identidad
  sueldo_salario numeric(26,2) NOT NULL, -- Sueldo o salario
  dias_cancelar numeric(5,2) NOT NULL, -- Dias cancelados
  acumulado_prestaciones numeric(26,2) NOT NULL, -- Acumulado de prestaciones sociales
  mensaje_personal text, -- Mensaje personal
  CONSTRAINT cnmd08_historia_trabajador_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha),
  CONSTRAINT cnmd08_historia_trabajador_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina)
      REFERENCES cnmd08_historia_nomina (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE cnmd08_historia_trabajador OWNER TO sisap;
COMMENT ON TABLE cnmd08_historia_trabajador IS 'Registra la historia de pago por trabajador
';
COMMENT ON COLUMN cnmd08_historia_trabajador.cod_presi IS 'Código de la presidencia
';
COMMENT ON COLUMN cnmd08_historia_trabajador.cod_entidad IS 'Código de la entidad
';
COMMENT ON COLUMN cnmd08_historia_trabajador.cod_tipo_inst IS 'Código tipo de Institución
';
COMMENT ON COLUMN cnmd08_historia_trabajador.cod_inst IS 'Código de la Institución
';
COMMENT ON COLUMN cnmd08_historia_trabajador.cod_dep IS 'Código de la dependencia
';
COMMENT ON COLUMN cnmd08_historia_trabajador.cod_tipo_nomina IS 'Código tipo de nómina
';
COMMENT ON COLUMN cnmd08_historia_trabajador.ano IS 'Año
';
COMMENT ON COLUMN cnmd08_historia_trabajador.numero_nomina IS 'Número de nómina
';
COMMENT ON COLUMN cnmd08_historia_trabajador.cod_cargo IS 'Código de cargo
';
COMMENT ON COLUMN cnmd08_historia_trabajador.cod_ficha IS 'Código de la ficha
';
COMMENT ON COLUMN cnmd08_historia_trabajador.cedula_identidad IS 'Cédula de identidad';
COMMENT ON COLUMN cnmd08_historia_trabajador.sueldo_salario IS 'Sueldo o salario
';
COMMENT ON COLUMN cnmd08_historia_trabajador.dias_cancelar IS 'Dias cancelados
';
COMMENT ON COLUMN cnmd08_historia_trabajador.acumulado_prestaciones IS 'Acumulado de prestaciones sociales
';
COMMENT ON COLUMN cnmd08_historia_trabajador.mensaje_personal IS 'Mensaje personal';



CREATE TABLE cnmd08_historia_transacciones
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo_nomina integer NOT NULL, -- Código tipo de nómina
  ano integer NOT NULL, -- Año
  numero_nomina integer NOT NULL, -- Número de nómina
  cod_cargo integer NOT NULL, -- Código del cargo
  cod_ficha integer NOT NULL, -- Código de la ficha
  cod_tipo_transaccion integer NOT NULL, -- Código tipo de transacción
  cod_transaccion integer NOT NULL, -- Código de transacción
  fecha_transaccion date NOT NULL, -- Fecha de la transacción
  monto_original numeric(26,2) NOT NULL, -- Monto original
  numero_cuotas_descontar integer NOT NULL, -- Número de cuotas a descontar
  numero_cuotas_canceladas integer NOT NULL, -- Número de cuotas canceladas
  monto_cuota numeric(26,2) NOT NULL, -- Monto de la cuota
  saldo numeric(26,2) NOT NULL, -- Saldo
  dias_horas integer, -- Dias o Horas
  CONSTRAINT cnmd08_historia_transacciones_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion),
  CONSTRAINT cnmd08_historia_transacciones_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha)
      REFERENCES cnmd08_historia_trabajador (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE cnmd08_historia_transacciones OWNER TO sisap;
COMMENT ON TABLE cnmd08_historia_transacciones IS 'Guarda la historia de las transacciones canceladas según la ficha del trabajador 
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_presi IS 'Código de la presidencia
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_entidad IS 'Código de la entidad
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_tipo_inst IS 'Código tipo de Institución
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_inst IS 'Código de la Institución
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_dep IS 'Código de la dependencia
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_tipo_nomina IS 'Código tipo de nómina
';
COMMENT ON COLUMN cnmd08_historia_transacciones.ano IS 'Año

';
COMMENT ON COLUMN cnmd08_historia_transacciones.numero_nomina IS 'Número de nómina
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_cargo IS 'Código del cargo
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_ficha IS 'Código de la ficha
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_tipo_transaccion IS 'Código tipo de transacción
';
COMMENT ON COLUMN cnmd08_historia_transacciones.cod_transaccion IS 'Código de transacción
';
COMMENT ON COLUMN cnmd08_historia_transacciones.fecha_transaccion IS 'Fecha de la transacción
';
COMMENT ON COLUMN cnmd08_historia_transacciones.monto_original IS 'Monto original
';
COMMENT ON COLUMN cnmd08_historia_transacciones.numero_cuotas_descontar IS 'Número de cuotas a descontar
';
COMMENT ON COLUMN cnmd08_historia_transacciones.numero_cuotas_canceladas IS 'Número de cuotas canceladas
';
COMMENT ON COLUMN cnmd08_historia_transacciones.monto_cuota IS 'Monto de la cuota';
COMMENT ON COLUMN cnmd08_historia_transacciones.saldo IS 'Saldo
';
COMMENT ON COLUMN cnmd08_historia_transacciones.dias_horas IS 'Dias o Horas';


-- Table: cnmd08_historia_trabajador


