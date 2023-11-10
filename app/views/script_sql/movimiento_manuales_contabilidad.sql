-- Table: cstd03_movimientos_manuales_ingresos

-- DROP TABLE cstd03_movimientos_manuales_ingresos;

CREATE TABLE cstd03_movimientos_manuales_ingresos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_movimiento integer NOT NULL, -- Año del movimiento
  cod_entidad_bancaria integer NOT NULL, -- Entidad bancaria
  cod_sucursal integer NOT NULL, -- Sucursal bancaria
  cuenta_bancaria character varying(20) NOT NULL, -- Cuenta bancaria
  tipo_documento integer NOT NULL, -- Tipo de documento...
  numero_documento integer NOT NULL, -- Número de documento
  cod_tipo_cuenta integer NOT NULL, -- Tipo de cuenta...
  cod_cuenta integer NOT NULL, -- Código de cuenta...
  cod_subcuenta integer NOT NULL, -- Código de subcuenta...
  cod_division integer NOT NULL, -- Código de division estadistica...
  cod_subdivision integer NOT NULL, -- Subdivisión estadistica...
  monto numeric(26,2) NOT NULL, -- Monto
  CONSTRAINT cstd03_movimientos_manuales_ingresos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, tipo_documento, numero_documento, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision),
  CONSTRAINT cstd03_movimientos_manuales_ingresos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, tipo_documento, numero_documento)
      REFERENCES cstd03_movimientos_manuales (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, tipo_documento, numero_documento) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE cstd03_movimientos_manuales_ingresos OWNER TO sisap;
COMMENT ON TABLE cstd03_movimientos_manuales_ingresos IS 'Mediante esta tabla se registrará la distribución de los ingresos';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.ano_movimiento IS 'Año del movimiento';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_entidad_bancaria IS 'Entidad bancaria';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_sucursal IS 'Sucursal bancaria';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cuenta_bancaria IS 'Cuenta bancaria';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.tipo_documento IS 'Tipo de documento
1.- Depositos
2.- Nota de crédito
';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.numero_documento IS 'Número de documento
';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_tipo_cuenta IS 'Tipo de cuenta
2.- Pasivo (301)
';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_cuenta IS 'Código de cuenta
301 - Ingresos
';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_subcuenta IS 'Código de subcuenta
Según plan de cuentas
';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_division IS 'Código de division estadistica
Según el plan de cuentas
';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.cod_subdivision IS 'Subdivisión estadistica
Según plan de cuentas
';
COMMENT ON COLUMN cstd03_movimientos_manuales_ingresos.monto IS 'Monto
';
