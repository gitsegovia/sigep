



 --DROP TABLE ccfd10_detalles;

 --DROP TABLE ccfd10_descripcion;

 --DROP TABLE ccfd02;







-- Table: ccfd02

CREATE TABLE ccfd02
(
  cod_presi integer NOT NULL, -- Código de presidencia
  cod_entidad integer NOT NULL, -- Código de entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_fiscal integer NOT NULL, -- Año fiscal
  cod_tipo_cuenta integer NOT NULL, -- Código tipo de la cuenta contable
  cod_cuenta integer NOT NULL, -- Código de la cuenta contable
  cod_subcuenta integer NOT NULL, -- Código de subcuenta contable
  cod_division integer NOT NULL, -- Código de la división estadistica
  cod_subdivision integer NOT NULL, -- Código de la subdivision estadistica
  debito_acumulado numeric(26,2) NOT NULL, -- Debito acumulado
  credito_acumulado numeric(26,2) NOT NULL, -- Credito acumulado
  debito_ene numeric(26,2) NOT NULL, -- Debito mes de enero
  credito_ene numeric(26,2) NOT NULL, -- Crédito mes de enero
  debito_feb numeric(26,2) NOT NULL, -- Debito mes de febrero
  credito_feb numeric(26,2) NOT NULL, -- Crédito mes de febrero
  debito_mar numeric(26,2) NOT NULL, -- Debito mes de marzo
  credito_mar numeric(26,2) NOT NULL, -- Crédito mes de marzo
  debito_abr numeric(26,2) NOT NULL, -- Debito mes de abril
  credito_abr numeric(26,2) NOT NULL, -- Crédito mes de abril
  debito_may numeric(26,2) NOT NULL, -- Debito mes de mayo
  credito_may numeric(26,2) NOT NULL, -- Crédito mes de mayo
  debito_jun numeric(26,2) NOT NULL, -- Debito mes de junio
  credito_jun numeric(26,2) NOT NULL, -- Crédito mes de junio
  debito_jul numeric(26,2) NOT NULL, -- Debito mes de julio
  credito_jul numeric(26,2) NOT NULL, -- Crédito mes de julio
  debito_ago numeric(26,2) NOT NULL, -- Debito mes de agosto
  credito_ago numeric(26,2) NOT NULL, -- Crédito mes de agosto
  debito_sep numeric(26,2) NOT NULL, -- Debito mes de septiembre
  credito_sep numeric(26,2) NOT NULL, -- Crédito mes de septiembre
  debito_oct numeric(26,2) NOT NULL, -- Debito mes de octubre
  credito_oct numeric(26,2) NOT NULL, -- Crédito mes de octubre
  debito_nov numeric(26,2) NOT NULL, -- Debito mes de noviembre
  credito_nov numeric(26,2) NOT NULL, -- Crédito mes de noviembre
  debito_dic numeric(26,2) NOT NULL, -- Debito mes de diciembre
  credito_dic numeric(26,2) NOT NULL, -- Crédito mes de diciembre
  CONSTRAINT ccfd02_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_fiscal, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision),
  CONSTRAINT ccfd02_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision)
      REFERENCES ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE ccfd02 OWNER TO sisap;
COMMENT ON TABLE ccfd02 IS 'Registro contable de las cuentas';
COMMENT ON COLUMN ccfd02.cod_presi IS 'Código de presidencia';
COMMENT ON COLUMN ccfd02.cod_entidad IS 'Código de entidad';
COMMENT ON COLUMN ccfd02.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN ccfd02.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN ccfd02.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN ccfd02.ano_fiscal IS 'Año fiscal';
COMMENT ON COLUMN ccfd02.cod_tipo_cuenta IS 'Código tipo de la cuenta contable';
COMMENT ON COLUMN ccfd02.cod_cuenta IS 'Código de la cuenta contable';
COMMENT ON COLUMN ccfd02.cod_subcuenta IS 'Código de subcuenta contable';
COMMENT ON COLUMN ccfd02.cod_division IS 'Código de la división estadistica';
COMMENT ON COLUMN ccfd02.cod_subdivision IS 'Código de la subdivision estadistica';
COMMENT ON COLUMN ccfd02.debito_acumulado IS 'Debito acumulado';
COMMENT ON COLUMN ccfd02.credito_acumulado IS 'Credito acumulado';
COMMENT ON COLUMN ccfd02.debito_ene IS 'Debito mes de enero';
COMMENT ON COLUMN ccfd02.credito_ene IS 'Crédito mes de enero';
COMMENT ON COLUMN ccfd02.debito_feb IS 'Debito mes de febrero';
COMMENT ON COLUMN ccfd02.credito_feb IS 'Crédito mes de febrero';
COMMENT ON COLUMN ccfd02.debito_mar IS 'Debito mes de marzo';
COMMENT ON COLUMN ccfd02.credito_mar IS 'Crédito mes de marzo';
COMMENT ON COLUMN ccfd02.debito_abr IS 'Debito mes de abril';
COMMENT ON COLUMN ccfd02.credito_abr IS 'Crédito mes de abril';
COMMENT ON COLUMN ccfd02.debito_may IS 'Debito mes de mayo';
COMMENT ON COLUMN ccfd02.credito_may IS 'Crédito mes de mayo';
COMMENT ON COLUMN ccfd02.debito_jun IS 'Debito mes de junio';
COMMENT ON COLUMN ccfd02.credito_jun IS 'Crédito mes de junio';
COMMENT ON COLUMN ccfd02.debito_jul IS 'Debito mes de julio';
COMMENT ON COLUMN ccfd02.credito_jul IS 'Crédito mes de julio';
COMMENT ON COLUMN ccfd02.debito_ago IS 'Debito mes de agosto';
COMMENT ON COLUMN ccfd02.credito_ago IS 'Crédito mes de agosto';
COMMENT ON COLUMN ccfd02.debito_sep IS 'Debito mes de septiembre';
COMMENT ON COLUMN ccfd02.credito_sep IS 'Crédito mes de septiembre';
COMMENT ON COLUMN ccfd02.debito_oct IS 'Debito mes de octubre';
COMMENT ON COLUMN ccfd02.credito_oct IS 'Crédito mes de octubre';
COMMENT ON COLUMN ccfd02.debito_nov IS 'Debito mes de noviembre';
COMMENT ON COLUMN ccfd02.credito_nov IS 'Crédito mes de noviembre';
COMMENT ON COLUMN ccfd02.debito_dic IS 'Debito mes de diciembre';
COMMENT ON COLUMN ccfd02.credito_dic IS 'Crédito mes de diciembre';
























-- Table: ccfd10_descripcion


CREATE TABLE ccfd10_descripcion
(
  cod_presi integer NOT NULL, -- Código de presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código del tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_asiento integer NOT NULL, -- Año de asiento
  mes_asiento integer NOT NULL, -- Mes de asiento
  dia_asiento integer NOT NULL, -- Dia de asiento
  numero_asiento integer NOT NULL, -- Numero de asiento
  instancia_asiento integer NOT NULL, -- Instancia del asiento...
  concepto text NOT NULL, -- Concepto
  tipo_documento integer NOT NULL, -- Tipo de documento...
  numero_documento character varying(20) NOT NULL, -- Número de documento
  fecha_documento date NOT NULL, -- Fecha de documento
  CONSTRAINT ccfd10_descripcion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento)
)
WITH (OIDS=FALSE);
ALTER TABLE ccfd10_descripcion OWNER TO sisap;
COMMENT ON TABLE ccfd10_descripcion IS 'Registra la descripción de los asientos contables';
COMMENT ON COLUMN ccfd10_descripcion.cod_presi IS 'Código de presidencia';
COMMENT ON COLUMN ccfd10_descripcion.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN ccfd10_descripcion.cod_tipo_inst IS 'Código del tipo de Institución';
COMMENT ON COLUMN ccfd10_descripcion.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN ccfd10_descripcion.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN ccfd10_descripcion.ano_asiento IS 'Año de asiento';
COMMENT ON COLUMN ccfd10_descripcion.mes_asiento IS 'Mes de asiento';
COMMENT ON COLUMN ccfd10_descripcion.dia_asiento IS 'Dia de asiento';
COMMENT ON COLUMN ccfd10_descripcion.numero_asiento IS 'Numero de asiento';
COMMENT ON COLUMN ccfd10_descripcion.instancia_asiento IS 'Instancia del asiento
1.- Apertura
2.- Tesoreria
3.- Contabilidad
4.- Manual';
COMMENT ON COLUMN ccfd10_descripcion.concepto IS 'Concepto';
COMMENT ON COLUMN ccfd10_descripcion.tipo_documento IS 'Tipo de documento
1.- Cheque
2.- Deposito
3.- Nota de crédito
4.- Nota de debito
5.- Orden de compra
6.- Otros compromisos
7.- Contratos de obras
8.- Contratos de servicios
9.- Ordenes de pago';
COMMENT ON COLUMN ccfd10_descripcion.numero_documento IS 'Número de documento
';
COMMENT ON COLUMN ccfd10_descripcion.fecha_documento IS 'Fecha de documento';






-- Table: ccfd10_detalles


CREATE TABLE ccfd10_detalles
(
  cod_presi integer NOT NULL, -- Código de presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código del tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de dependencia
  ano_asiento integer NOT NULL, -- Año de asiento
  mes_asiento integer NOT NULL, -- Mes de asiento
  dia_asiento integer NOT NULL, -- Dia de asiento
  numero_asiento integer NOT NULL, -- Número de asiento
  numero_linea integer NOT NULL, -- Número de linea
  debito_credito integer NOT NULL, -- Debito o crédito...
  cod_tipo_cuenta integer NOT NULL, -- Código de tipo de cuenta
  cod_cuenta integer NOT NULL, -- Código de cuenta
  cod_subcuenta integer NOT NULL, -- Código de subcuenta
  cod_division integer NOT NULL, -- Código de división estadistica
  cod_subdivision integer NOT NULL, -- Código de subdivision estadistica
  monto numeric(26,2) NOT NULL, -- Monto
  CONSTRAINT ccfd10_detalles_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento, numero_linea),
  CONSTRAINT ccfd10_detalles_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento)
      REFERENCES ccfd10_descripcion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE ccfd10_detalles OWNER TO sisap;
COMMENT ON TABLE ccfd10_detalles IS 'Registro de asientos contables';
COMMENT ON COLUMN ccfd10_detalles.cod_presi IS 'Código de presidencia';
COMMENT ON COLUMN ccfd10_detalles.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN ccfd10_detalles.cod_tipo_inst IS 'Código del tipo de Institución';
COMMENT ON COLUMN ccfd10_detalles.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN ccfd10_detalles.cod_dep IS 'Código de dependencia';
COMMENT ON COLUMN ccfd10_detalles.ano_asiento IS 'Año de asiento';
COMMENT ON COLUMN ccfd10_detalles.mes_asiento IS 'Mes de asiento';
COMMENT ON COLUMN ccfd10_detalles.dia_asiento IS 'Dia de asiento';
COMMENT ON COLUMN ccfd10_detalles.numero_asiento IS 'Número de asiento';
COMMENT ON COLUMN ccfd10_detalles.numero_linea IS 'Número de linea';
COMMENT ON COLUMN ccfd10_detalles.debito_credito IS 'Debito o crédito
1.- Debito
2.-Crédito';
COMMENT ON COLUMN ccfd10_detalles.cod_tipo_cuenta IS 'Código de tipo de cuenta';
COMMENT ON COLUMN ccfd10_detalles.cod_cuenta IS 'Código de cuenta';
COMMENT ON COLUMN ccfd10_detalles.cod_subcuenta IS 'Código de subcuenta';
COMMENT ON COLUMN ccfd10_detalles.cod_division IS 'Código de división estadistica';
COMMENT ON COLUMN ccfd10_detalles.cod_subdivision IS 'Código de subdivision estadistica';
COMMENT ON COLUMN ccfd10_detalles.monto IS 'Monto';


















