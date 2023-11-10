COMMENT ON COLUMN cstd03_cheque_cuerpo.clase_beneficiario IS 'Clase de beneficiario
1.- Beneficiario común
2.- I.S.L.R.
3.- Timbre fiscal
4.- Impuesto Municipal
5.- I.V.A.
6.- MULTA
7.- RESPONSABILIDAD';



--TABLA: cimd01_clasificacion_seccion


ALTER TABLE cimd01_clasificacion_seccion ADD COLUMN bienes_tipo integer;
ALTER TABLE cimd01_clasificacion_seccion ADD COLUMN bienes_cuenta integer;
ALTER TABLE cimd01_clasificacion_seccion ADD COLUMN bienes_grupo_subcuenta integer;
ALTER TABLE cimd01_clasificacion_seccion ADD COLUMN bienes_subgrupo_division integer;
ALTER TABLE cimd01_clasificacion_seccion ADD COLUMN bienes_seccion_subdivision integer;


COMMENT ON COLUMN cimd01_clasificacion_seccion.bienes_tipo IS 'bienes_tipo = 1';
COMMENT ON COLUMN cimd01_clasificacion_seccion.bienes_cuenta IS 'bienes_cuenta=212 Cuando cod_tipo=1bienes_cuenta=214 Cuando cod_tipo=2';
COMMENT ON COLUMN cimd01_clasificacion_seccion.bienes_grupo_subcuenta IS 'bienes_grupo_subcuenta=cod_grupo';
COMMENT ON COLUMN cimd01_clasificacion_seccion.bienes_subgrupo_division IS 'bienes_subgrupo_division=cod_subgrupo';
COMMENT ON COLUMN cimd01_clasificacion_seccion.bienes_seccion_subdivision IS 'bienes_seccion_subdivision=cod_seccion';




--AGREGAR ESTOS CAMPOS A LAS SIGUENTES TABLAS:

-----TABLA: cepd02_contratoservicio_valuacion_cuerpo

ALTER TABLE cepd02_contratoservicio_valuacion_cuerpo ADD COLUMN retencion_multa numeric(26,2);
ALTER TABLE cepd02_contratoservicio_valuacion_cuerpo ADD COLUMN retencion_responsabilidad numeric(26,2);


COMMENT ON COLUMN cepd02_contratoservicio_valuacion_cuerpo.retencion_multa IS 'Retención por multa';
COMMENT ON COLUMN cepd02_contratoservicio_valuacion_cuerpo.retencion_responsabilidad IS 'Retención por responsabilidad social';

--TABLA: cepd03_ordenpago_cuerpo

ALTER TABLE cepd03_ordenpago_cuerpo ADD COLUMN retencion_multa numeric(26,2);
ALTER TABLE cepd03_ordenpago_cuerpo ADD COLUMN retencion_responsabilidad numeric(26,2);


COMMENT ON COLUMN cepd03_ordenpago_cuerpo.retencion_multa IS 'Retención por multas';
COMMENT ON COLUMN cepd03_ordenpago_cuerpo.retencion_responsabilidad IS 'Retención por responsabilidad social';

--TABLA: cobd01_contratoobras_valuacion_cuerpo

ALTER TABLE cobd01_contratoobras_valuacion_cuerpo ADD COLUMN retencion_multa numeric(26,2);
ALTER TABLE cobd01_contratoobras_valuacion_cuerpo ADD COLUMN retencion_responsabilidad numeric(26,2);


COMMENT ON COLUMN cobd01_contratoobras_valuacion_cuerpo.retencion_multa IS 'Retención por multas';
COMMENT ON COLUMN cobd01_contratoobras_valuacion_cuerpo.retencion_responsabilidad IS 'Retención por responsabilidad social';

--TABLA: cscd04_ordencompra_autorizacion_pago_cuerpo

ALTER TABLE cscd04_ordencompra_autorizacion_pago_cuerpo ADD COLUMN retencion_multa numeric(26,2);
ALTER TABLE cscd04_ordencompra_autorizacion_pago_cuerpo ADD COLUMN retencion_responsabilidad numeric(26,2);


COMMENT ON COLUMN cscd04_ordencompra_autorizacion_pago_cuerpo.retencion_multa IS 'Retención por multas';
COMMENT ON COLUMN cscd04_ordencompra_autorizacion_pago_cuerpo.retencion_responsabilidad IS 'Retención por responsabilidad social';









ALTER TABLE cepd03_ordenpago_cuerpo ADD COLUMN numero_comprobante_multa integer;
ALTER TABLE cepd03_ordenpago_cuerpo ADD COLUMN numero_comprobante_responsabilidad integer;


COMMENT ON COLUMN cepd03_ordenpago_cuerpo.numero_comprobante_multa IS 'Número de comprobante por retención de multa';
COMMENT ON COLUMN cepd03_ordenpago_cuerpo.numero_comprobante_responsabilidad IS 'Número de comprobante de la retención de responsabilidad';




-- Table: cstd03_beneficiario_retencion_multa

-- DROP TABLE cstd03_beneficiario_retencion_multa;

CREATE TABLE cstd03_beneficiario_retencion_multa
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  beneficiario character varying(100) NOT NULL, -- Registra el Beneficiario del cheque producto de la retención por multa
  CONSTRAINT cstd03_beneficiario_retencion_multa_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd03_beneficiario_retencion_multa OWNER TO sisap;
COMMENT ON TABLE cstd03_beneficiario_retencion_multa IS 'Registra el Beneficiario del cheque producto de la retención por multa';
COMMENT ON COLUMN cstd03_beneficiario_retencion_multa.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd03_beneficiario_retencion_multa.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd03_beneficiario_retencion_multa.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd03_beneficiario_retencion_multa.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd03_beneficiario_retencion_multa.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd03_beneficiario_retencion_multa.beneficiario IS 'Registra el Beneficiario del cheque producto de la retención por multa';



















-- Table: cstd03_beneficiario_retencion_responsabilidad

-- DROP TABLE cstd03_beneficiario_retencion_responsabilidad;

CREATE TABLE cstd03_beneficiario_retencion_responsabilidad
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  beneficiario character varying(100) NOT NULL, -- Registra el Beneficiario del cheque producto de la retención por responsabilidad
  CONSTRAINT cstd03_beneficiario_retencion_responsabilidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd03_beneficiario_retencion_responsabilidad OWNER TO sisap;
COMMENT ON TABLE cstd03_beneficiario_retencion_responsabilidad IS 'Registra el Beneficiario del cheque producto de la retención por responsabilidad';
COMMENT ON COLUMN cstd03_beneficiario_retencion_responsabilidad.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd03_beneficiario_retencion_responsabilidad.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd03_beneficiario_retencion_responsabilidad.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd03_beneficiario_retencion_responsabilidad.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd03_beneficiario_retencion_responsabilidad.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd03_beneficiario_retencion_responsabilidad.beneficiario IS 'Registra el Beneficiario del cheque producto de la retención por responsabilidad';



































--CREAR LAS SIGUIENTES TABLAS:

-- Table: cstd06_comprobante_cuerpo_multa

-- DROP TABLE cstd06_comprobante_cuerpo_multa;

CREATE TABLE cstd06_comprobante_cuerpo_multa
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_comprobante_multa integer NOT NULL, -- Año del comprobante de multa
  numero_comprobante_multa integer NOT NULL, -- Número del comprobante de multa
  ano_movimiento integer NOT NULL, -- Año del moviemiento bancario
  cod_entidad_bancaria integer NOT NULL, -- Código de la entidad bancaria
  cod_sucursal integer NOT NULL, -- Código de la sucursal
  cuenta_bancaria character varying(20) NOT NULL, -- Cuenta bancaria
  numero_cheque integer NOT NULL, -- Número del cheque
  CONSTRAINT cstd06_comprobante_cuerpo_multa_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_multa, numero_comprobante_multa)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd06_comprobante_cuerpo_multa OWNER TO sisap;
COMMENT ON TABLE cstd06_comprobante_cuerpo_multa IS 'Registra comprobante de multa';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.ano_comprobante_multa IS 'Año del comprobante de multa';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.numero_comprobante_multa IS 'Número del comprobante de multa';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.ano_movimiento IS 'Año del moviemiento bancario';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.cod_entidad_bancaria IS 'Código de la entidad bancaria';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.cod_sucursal IS 'Código de la sucursal';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.cuenta_bancaria IS 'Cuenta bancaria';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_multa.numero_cheque IS 'Número del cheque';

































-- Table: cstd06_comprobante_cuerpo_responsabilidad

-- DROP TABLE cstd06_comprobante_cuerpo_responsabilidad;

CREATE TABLE cstd06_comprobante_cuerpo_responsabilidad
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_comprobante_responsabilidad integer NOT NULL, -- Año del comprobante de multa
  numero_comprobante_responsabilidad integer NOT NULL, -- Número del comprobante de multa
  ano_movimiento integer NOT NULL, -- Año del moviemiento bancario
  cod_entidad_bancaria integer NOT NULL, -- Código de la entidad bancaria
  cod_sucursal integer NOT NULL, -- Código de la sucursal
  cuenta_bancaria character varying(20) NOT NULL, -- Cuenta bancaria
  numero_cheque integer NOT NULL, -- Número del cheque
  CONSTRAINT cstd06_comprobante_cuerpo_responsabilidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_responsabilidad, numero_comprobante_responsabilidad)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd06_comprobante_cuerpo_responsabilidad OWNER TO sisap;
COMMENT ON TABLE cstd06_comprobante_cuerpo_responsabilidad IS 'Registra comprobante de responsabilidad';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.ano_comprobante_responsabilidad IS 'Año del comprobante de multa';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.numero_comprobante_responsabilidad IS 'Número del comprobante de multa';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.ano_movimiento IS 'Año del moviemiento bancario';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.cod_entidad_bancaria IS 'Código de la entidad bancaria';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.cod_sucursal IS 'Código de la sucursal';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.cuenta_bancaria IS 'Cuenta bancaria';
COMMENT ON COLUMN cstd06_comprobante_cuerpo_responsabilidad.numero_cheque IS 'Número del cheque';






























-- Table: cstd06_comprobante_numero_multa

-- DROP TABLE cstd06_comprobante_numero_multa;

CREATE TABLE cstd06_comprobante_numero_multa
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_comprobante_multa integer NOT NULL, -- Año del comprobante del timbre fiscal
  numero_comprobante_multa integer, -- Número de comprobante de timbre fiscal
  CONSTRAINT cstd06_comprobante_numero_multa_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_multa)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd06_comprobante_numero_multa OWNER TO sisap;
COMMENT ON TABLE cstd06_comprobante_numero_multa IS 'Controla del número de comprobante de multa';
COMMENT ON COLUMN cstd06_comprobante_numero_multa.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd06_comprobante_numero_multa.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd06_comprobante_numero_multa.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd06_comprobante_numero_multa.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd06_comprobante_numero_multa.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd06_comprobante_numero_multa.ano_comprobante_multa IS 'Año del comprobante del timbre fiscal';
COMMENT ON COLUMN cstd06_comprobante_numero_multa.numero_comprobante_multa IS 'Número de comprobante de timbre fiscal';








-- Table: cstd06_comprobante_numero_responsabilidad

-- DROP TABLE cstd06_comprobante_numero_responsabilidad;

CREATE TABLE cstd06_comprobante_numero_responsabilidad
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_comprobante_responsabilidad integer NOT NULL, -- Año del comprobante del timbre fiscal
  numero_comprobante_responsabilidad integer, -- Número de comprobante de timbre fiscal
  CONSTRAINT cstd06_comprobante_numero_responsabilidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_responsabilidad)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd06_comprobante_numero_responsabilidad OWNER TO sisap;
COMMENT ON TABLE cstd06_comprobante_numero_responsabilidad IS 'Controla del número de comprobante de responsabilidad';
COMMENT ON COLUMN cstd06_comprobante_numero_responsabilidad.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd06_comprobante_numero_responsabilidad.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd06_comprobante_numero_responsabilidad.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd06_comprobante_numero_responsabilidad.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd06_comprobante_numero_responsabilidad.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd06_comprobante_numero_responsabilidad.ano_comprobante_responsabilidad IS 'Año del comprobante del timbre fiscal';
COMMENT ON COLUMN cstd06_comprobante_numero_responsabilidad.numero_comprobante_responsabilidad IS 'Número de comprobante de timbre fiscal';





-- Table: cstd06_comprobante_poremitir_multa

-- DROP TABLE cstd06_comprobante_poremitir_multa;

CREATE TABLE cstd06_comprobante_poremitir_multa
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  username character varying(60) NOT NULL, -- Operador que registro el cheque
  ano_comprobante_multa integer NOT NULL, -- Año del comprobante del timbre fiscal
  numero_comprobante_multa integer NOT NULL, -- Número del comprobante del timbre fiscal
  ano_orden_pago integer NOT NULL, -- Año de la orden de pago
  clase_orden integer NOT NULL, -- Clase de orden...
  numero_orden_pago integer NOT NULL, -- Número orden de pago
  CONSTRAINT cstd06_comprobante_poremitir_multa_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_multa, numero_comprobante_multa, ano_orden_pago, clase_orden, numero_orden_pago)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd06_comprobante_poremitir_multa OWNER TO sisap;
COMMENT ON TABLE cstd06_comprobante_poremitir_multa IS 'Registra el comprobante de multa por emitir';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.username IS 'Operador que registro el cheque';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.ano_comprobante_multa IS 'Año del comprobante del timbre fiscal';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.numero_comprobante_multa IS 'Número del comprobante del timbre fiscal';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.ano_orden_pago IS 'Año de la orden de pago';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.clase_orden IS 'Clase de orden
1.- Interna
2.- Especial';
COMMENT ON COLUMN cstd06_comprobante_poremitir_multa.numero_orden_pago IS 'Número orden de pago';



































-- Table: cstd06_comprobante_poremitir_responsabilidad

-- DROP TABLE cstd06_comprobante_poremitir_responsabilidad;

CREATE TABLE cstd06_comprobante_poremitir_responsabilidad
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  username character varying(60) NOT NULL, -- Operador que registro el cheque
  ano_comprobante_responsabilidad integer NOT NULL, -- Año del comprobante del timbre fiscal
  numero_comprobante_responsabilidad integer NOT NULL, -- Número del comprobante del timbre fiscal
  ano_orden_pago integer NOT NULL, -- Año de la orden de pago
  clase_orden integer NOT NULL, -- Clase de orden...
  numero_orden_pago integer NOT NULL, -- Número orden de pago
  CONSTRAINT cstd06_comprobante_poremitir_responsabilidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_responsabilidad, numero_comprobante_responsabilidad, ano_orden_pago, clase_orden, numero_orden_pago)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd06_comprobante_poremitir_responsabilidad OWNER TO sisap;
COMMENT ON TABLE cstd06_comprobante_poremitir_responsabilidad IS 'Registra el comprobante de multa por emitir';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.username IS 'Operador que registro el cheque';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.ano_comprobante_responsabilidad IS 'Año del comprobante del timbre fiscal';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.numero_comprobante_responsabilidad IS 'Número del comprobante del timbre fiscal';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.ano_orden_pago IS 'Año de la orden de pago';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.clase_orden IS 'Clase de orden
1.- Interna
2.- Especial';
COMMENT ON COLUMN cstd06_comprobante_poremitir_responsabilidad.numero_orden_pago IS 'Número orden de pago';


































-- Table: cstd07_retenciones_cuerpo_multa

-- DROP TABLE cstd07_retenciones_cuerpo_multa;

CREATE TABLE cstd07_retenciones_cuerpo_multa
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_orden_pago integer NOT NULL, -- Año de la orden de pago
  clase_orden integer NOT NULL, -- Clase de orden...
  numero_orden_pago integer NOT NULL, -- Número de la orden de pago
  monto numeric(26,2) NOT NULL, -- Monto
  fecha_proceso_registro date NOT NULL, -- Fecha del proceso al momento de actualizar las ordenes acumuladas pendientes
  status integer NOT NULL, -- Status...
  ano_movimiento integer, -- Año del movimiento
  cod_entidad_bancaria integer, -- Código de la entidad bancaria
  cod_sucursal integer, -- Código de la sucursal
  cuenta_bancaria character varying(20), -- Cuenta bancaria
  numero_cheque integer, -- Número de cheque
  fecha_proceso_anulacion date, -- Fecha del proceso al momento de cancelar las ordenes de pagos acumuladas pendientes
  ano_anterior integer DEFAULT 2, -- Año anterior...
  CONSTRAINT cstd07_retenciones_cuerpo_multa_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd07_retenciones_cuerpo_multa OWNER TO sisap;
COMMENT ON TABLE cstd07_retenciones_cuerpo_multa IS 'Registra las ordenes de pago acumuladas pendientes y las canceladas del timbre fiscal';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.ano_orden_pago IS 'Año de la orden de pago';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.clase_orden IS 'Clase de orden
1.- Orden Interna
2.- Orden pago';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.numero_orden_pago IS 'Número de la orden de pago';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.monto IS 'Monto';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.fecha_proceso_registro IS 'Fecha del proceso al momento de actualizar las ordenes acumuladas pendientes';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.status IS 'Status
1.- Por emitir
2.- Emitido
3.- Por re-emit';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.ano_movimiento IS 'Año del movimiento';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.cod_entidad_bancaria IS 'Código de la entidad bancaria';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.cod_sucursal IS 'Código de la sucursal';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.cuenta_bancaria IS 'Cuenta bancaria';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.numero_cheque IS 'Número de cheque';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.fecha_proceso_anulacion IS 'Fecha del proceso al momento de cancelar las ordenes de pagos acumuladas pendientes';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_multa.ano_anterior IS 'Año anterior
1.- Si
2.- No
';






-- Table: cstd07_retenciones_cuerpo_responsabilidad

-- DROP TABLE cstd07_retenciones_cuerpo_responsabilidad;

CREATE TABLE cstd07_retenciones_cuerpo_responsabilidad
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_orden_pago integer NOT NULL, -- Año de la orden de pago
  clase_orden integer NOT NULL, -- Clase de orden...
  numero_orden_pago integer NOT NULL, -- Número de la orden de pago
  monto numeric(26,2) NOT NULL, -- Monto
  fecha_proceso_registro date NOT NULL, -- Fecha del proceso al momento de actualizar las ordenes acumuladas pendientes
  status integer NOT NULL, -- Status...
  ano_movimiento integer, -- Año del movimiento
  cod_entidad_bancaria integer, -- Código de la entidad bancaria
  cod_sucursal integer, -- Código de la sucursal
  cuenta_bancaria character varying(20), -- Cuenta bancaria
  numero_cheque integer, -- Número de cheque
  fecha_proceso_anulacion date, -- Fecha del proceso al momento de cancelar las ordenes de pagos acumuladas pendientes
  ano_anterior integer DEFAULT 2, -- Año anterior...
  CONSTRAINT cstd07_retenciones_cuerpo_responsabilidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago)
)
WITH (OIDS=FALSE);
ALTER TABLE cstd07_retenciones_cuerpo_responsabilidad OWNER TO sisap;
COMMENT ON TABLE cstd07_retenciones_cuerpo_responsabilidad IS 'Registra las ordenes de pago acumuladas pendientes y las canceladas del timbre fiscal';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.ano_orden_pago IS 'Año de la orden de pago';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.clase_orden IS 'Clase de orden
1.- Orden Interna
2.- Orden pago';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.numero_orden_pago IS 'Número de la orden de pago';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.monto IS 'Monto';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.fecha_proceso_registro IS 'Fecha del proceso al momento de actualizar las ordenes acumuladas pendientes';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.status IS 'Status
1.- Por emitir
2.- Emitido
3.- Por re-emit';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.ano_movimiento IS 'Año del movimiento';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.cod_entidad_bancaria IS 'Código de la entidad bancaria';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.cod_sucursal IS 'Código de la sucursal';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.cuenta_bancaria IS 'Cuenta bancaria';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.numero_cheque IS 'Número de cheque';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.fecha_proceso_anulacion IS 'Fecha del proceso al momento de cancelar las ordenes de pagos acumuladas pendientes';
COMMENT ON COLUMN cstd07_retenciones_cuerpo_responsabilidad.ano_anterior IS 'Año anterior
1.- Si
2.- No
';

-- Table: cstd07_retenciones_partidas_multa

-- DROP TABLE cstd07_retenciones_partidas_multa;

CREATE TABLE cstd07_retenciones_partidas_multa
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_orden_pago integer NOT NULL, -- Año de la orden de pago
  clase_orden integer NOT NULL, -- Clase de orden...
  numero_orden_pago integer NOT NULL, -- Numero de la orden de pago
  ano integer NOT NULL, -- Ejercicio presupuestario
  cod_sector integer NOT NULL, -- Código del sector
  cod_programa integer NOT NULL, -- Código del programa
  cod_sub_prog integer NOT NULL, -- Código del Subprograma
  cod_proyecto integer NOT NULL, -- Código del proyecto
  cod_activ_obra integer NOT NULL, -- Código de la actividad o de la obra
  cod_partida integer NOT NULL, -- Código de la partida
  cod_generica integer NOT NULL, -- Código de la generica
  cod_especifica integer NOT NULL, -- Código de la especifica
  cod_sub_espec integer NOT NULL, -- Código de la Subespecifica
  cod_auxiliar integer NOT NULL, -- Código del auxiliar
  monto numeric(26,2) NOT NULL, -- Monto
  numero_control_compromiso integer, -- Número de control del compromiso
  numero_control_causado integer, -- Número de control del causado
  numero_control_pagado integer, -- Número de control del pagado
  CONSTRAINT cstd07_retenciones_partidas_multa_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar),
  CONSTRAINT cstd07_retenciones_partidas_multa_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago)
      REFERENCES cstd07_retenciones_cuerpo_multa (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (OIDS=FALSE);
ALTER TABLE cstd07_retenciones_partidas_multa OWNER TO sisap;
COMMENT ON TABLE cstd07_retenciones_partidas_multa IS 'Registra las partidas de las ordenes de pago acumuladas pendientes y canceladas por timbre fiscal';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.ano_orden_pago IS 'Año de la orden de pago';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.clase_orden IS 'Clase de orden
1.- Orden Interna
2.- Orden especial';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.numero_orden_pago IS 'Numero de la orden de pago';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.ano IS 'Ejercicio presupuestario';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_sector IS 'Código del sector';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_programa IS 'Código del programa';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_sub_prog IS 'Código del Subprograma';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_proyecto IS 'Código del proyecto';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_activ_obra IS 'Código de la actividad o de la obra';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_partida IS 'Código de la partida';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_generica IS 'Código de la generica';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_especifica IS 'Código de la especifica';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_sub_espec IS 'Código de la Subespecifica';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.cod_auxiliar IS 'Código del auxiliar';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.monto IS 'Monto';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.numero_control_compromiso IS 'Número de control del compromiso';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.numero_control_causado IS 'Número de control del causado';
COMMENT ON COLUMN cstd07_retenciones_partidas_multa.numero_control_pagado IS 'Número de control del pagado';



































-- Table: cstd07_retenciones_partidas_responsabilidad

-- DROP TABLE cstd07_retenciones_partidas_responsabilidad;

CREATE TABLE cstd07_retenciones_partidas_responsabilidad
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_orden_pago integer NOT NULL, -- Año de la orden de pago
  clase_orden integer NOT NULL, -- Clase de orden...
  numero_orden_pago integer NOT NULL, -- Numero de la orden de pago
  ano integer NOT NULL, -- Ejercicio presupuestario
  cod_sector integer NOT NULL, -- Código del sector
  cod_programa integer NOT NULL, -- Código del programa
  cod_sub_prog integer NOT NULL, -- Código del Subprograma
  cod_proyecto integer NOT NULL, -- Código del proyecto
  cod_activ_obra integer NOT NULL, -- Código de la actividad o de la obra
  cod_partida integer NOT NULL, -- Código de la partida
  cod_generica integer NOT NULL, -- Código de la generica
  cod_especifica integer NOT NULL, -- Código de la especifica
  cod_sub_espec integer NOT NULL, -- Código de la Subespecifica
  cod_auxiliar integer NOT NULL, -- Código del auxiliar
  monto numeric(26,2) NOT NULL, -- Monto
  numero_control_compromiso integer, -- Número de control del compromiso
  numero_control_causado integer, -- Número de control del causado
  numero_control_pagado integer, -- Número de control del pagado
  CONSTRAINT cstd07_retenciones_partidas_responsabilidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar),
  CONSTRAINT cstd07_retenciones_partidas_responsabilidad_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago)
      REFERENCES cstd07_retenciones_cuerpo_responsabilidad (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (OIDS=FALSE);
ALTER TABLE cstd07_retenciones_partidas_responsabilidad OWNER TO sisap;
COMMENT ON TABLE cstd07_retenciones_partidas_responsabilidad IS 'Registra las partidas de las ordenes de pago acumuladas pendientes y canceladas por timbre fiscal';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.ano_orden_pago IS 'Año de la orden de pago';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.clase_orden IS 'Clase de orden
1.- Orden Interna
2.- Orden especial';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.numero_orden_pago IS 'Numero de la orden de pago';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.ano IS 'Ejercicio presupuestario';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_sector IS 'Código del sector';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_programa IS 'Código del programa';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_sub_prog IS 'Código del Subprograma';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_proyecto IS 'Código del proyecto';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_activ_obra IS 'Código de la actividad o de la obra';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_partida IS 'Código de la partida';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_generica IS 'Código de la generica';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_especifica IS 'Código de la especifica';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_sub_espec IS 'Código de la Subespecifica';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.cod_auxiliar IS 'Código del auxiliar';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.monto IS 'Monto';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.numero_control_compromiso IS 'Número de control del compromiso';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.numero_control_causado IS 'Número de control del causado';
COMMENT ON COLUMN cstd07_retenciones_partidas_responsabilidad.numero_control_pagado IS 'Número de control del pagado';














DROP TABLE cnmd07_transacciones_quecobran_incompleto;



-- Table: cnmd07_transacciones_prenomina

-- DROP TABLE cnmd07_transacciones_prenomina;

CREATE TABLE cnmd07_transacciones_prenomina
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo_nomina integer NOT NULL, -- Código tipo de nómina
  cod_cargo integer NOT NULL, -- Código del cargo
  cod_ficha integer NOT NULL, -- Código de ficha del trabajador
  cod_tipo_transaccion integer NOT NULL, -- Código tipo de transaccion
  cod_transaccion integer NOT NULL, -- Código de transaccion
  fecha_transaccion date NOT NULL, -- Fecha de la transacción
  monto_original numeric(26,2), -- Monto original de la transacción, cuando esta sea una deducción cuya actualización sea deductiva
  numero_cuotas_descontar integer NOT NULL, -- Número original de cuotas a descontar o a cancelar
  numero_cuotas_cancelar integer NOT NULL, -- Número de cuotas a cancelar
  numero_cuotas_canceladas integer NOT NULL, -- Número de cuotas canceladas
  monto_cuota numeric(26,2) NOT NULL, -- Monto de la cuota
  saldo numeric(26,2) NOT NULL, -- Saldo de la transaccion. Este saldo dependende de la actualización, si es acumulativo o es deductivo
  marca_fin_descuento character varying(1), -- Esta marca debe ser un asteristico. Al momento del cierre este busca las transacciones que tengan esta marca y las elimina
  fecha_proceso date NOT NULL, -- Fecha de proceso de la transaccion
  username character varying(60) NOT NULL, -- Operador que realizo la operacion
  dias_horas integer, -- Dias o Horas
  CONSTRAINT cnmd07_transacciones_quecobran_incompleto_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion)
)
WITH (OIDS=FALSE);
ALTER TABLE cnmd07_transacciones_prenomina OWNER TO sisap;
COMMENT ON TABLE cnmd07_transacciones_prenomina IS 'Registra las transacciones cuando se realizan mas de un pago';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_tipo_inst IS 'Código tipo de institución';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_tipo_nomina IS 'Código tipo de nómina';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_cargo IS 'Código del cargo';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_ficha IS 'Código de ficha del trabajador';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_tipo_transaccion IS 'Código tipo de transaccion';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_transaccion IS 'Código de transaccion';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.fecha_transaccion IS 'Fecha de la transacción';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.monto_original IS 'Monto original de la transacción, cuando esta sea una deducción cuya actualización sea deductiva';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.numero_cuotas_descontar IS 'Número original de cuotas a descontar o a cancelar';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.numero_cuotas_cancelar IS 'Número de cuotas a cancelar';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.numero_cuotas_canceladas IS 'Número de cuotas canceladas';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.monto_cuota IS 'Monto de la cuota';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.saldo IS 'Saldo de la transaccion. Este saldo dependende de la actualización, si es acumulativo o es deductivo';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.marca_fin_descuento IS 'Esta marca debe ser un asteristico. Al momento del cierre este busca las transacciones que tengan esta marca y las elimina';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.fecha_proceso IS 'Fecha de proceso de la transaccion';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.username IS 'Operador que realizo la operacion';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.dias_horas IS 'Dias o Horas';










