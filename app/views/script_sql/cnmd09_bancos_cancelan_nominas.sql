-- Table: cnmd09_bancos_cancelan_nominas

DROP TABLE cnmd09_bancos_cancelan_nominas cascade;

CREATE TABLE cnmd09_bancos_cancelan_nominas
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo_nomina integer NOT NULL, -- Código tipo de nómina
  cod_entidad_bancaria integer NOT NULL, -- Código de la entidad bancaria
  cod_sucursal integer NOT NULL, -- Código de la sucursal
  cuenta_bancaria character varying(20) NOT NULL, -- Cuenta bancaria
  beneficiario character varying(70) NOT NULL, -- Beneficiario del cheque
  CONSTRAINT cnmd09_bancos_cancelan_nominas_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria),
  CONSTRAINT cnmd09_bancos_cancelan_nominas_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina)
      REFERENCES cnmd01 (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE cnmd09_bancos_cancelan_nominas OWNER TO sisap;
COMMENT ON TABLE cnmd09_bancos_cancelan_nominas IS 'Registra los bancos que cancelan nóminas';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.cod_tipo_nomina IS 'Código tipo de nómina';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.cod_entidad_bancaria IS 'Código de la entidad bancaria';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.cod_sucursal IS 'Código de la sucursal';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.cuenta_bancaria IS 'Cuenta bancaria';
COMMENT ON COLUMN cnmd09_bancos_cancelan_nominas.beneficiario IS 'Beneficiario del cheque';






ALTER TABLE cnmd09_bancos_cancelan_nominas ADD COLUMN rif character varying(20);




ALTER TABLE cnmd09_bancos_cancela_fondos_terceros ADD COLUMN personalidad integer;
ALTER TABLE cnmd09_bancos_cancela_fondos_terceros ADD COLUMN rif_cedula character varying(20);


COMMENT ON COLUMN cnmd09_bancos_cancela_fondos_terceros.personalidad IS 'Personalidad
1.- Natural
2.- Juridica';
COMMENT ON COLUMN cnmd09_bancos_cancela_fondos_terceros.rif_cedula IS 'Rif o cédula de identidad del beneficiario

Si es 1.- Natural se utiliza Cédula
Si es 2.- Juridico se utiliza Rif

';