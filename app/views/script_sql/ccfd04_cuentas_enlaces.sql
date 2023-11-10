-- Table: ccfd04_cuentas_enlace

-- DROP TABLE ccfd04_cuentas_enlace;

CREATE TABLE ccfd04_cuentas_enlace
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  ano_fiscal integer NOT NULL, -- Año fiscal
  cod_tipo_enlace integer NOT NULL, -- Código tipo de enlace...
  cod_tipo_cuenta integer NOT NULL, -- Código tipo de cuenta
  cod_cuenta integer NOT NULL, -- Código de cuenta
  cod_subcuenta integer NOT NULL, -- Código de subcuenta
  cod_division integer NOT NULL, -- Código de la división
  cod_subdivision integer NOT NULL, -- Código de subdivisión
  CONSTRAINT ccfd04_cuentas_enlace_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_fiscal, cod_tipo_enlace)
)
WITH (OIDS=FALSE);
ALTER TABLE ccfd04_cuentas_enlace OWNER TO sisap;
COMMENT ON TABLE ccfd04_cuentas_enlace IS 'Se utiliza para enlazar cuentas contables fiscales, según el tipo de operación';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_inst IS 'Código de la Institución
';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_dep IS 'Código de la dependencia
';
COMMENT ON COLUMN ccfd04_cuentas_enlace.ano_fiscal IS 'Año fiscal
';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_tipo_enlace IS 'Código tipo de enlace
01.- Fondos de terceros
';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_tipo_cuenta IS 'Código tipo de cuenta
';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_cuenta IS 'Código de cuenta
';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_subcuenta IS 'Código de subcuenta
';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_division IS 'Código de la división

';
COMMENT ON COLUMN ccfd04_cuentas_enlace.cod_subdivision IS 'Código de subdivisión
';

