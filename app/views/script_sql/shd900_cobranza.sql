-- Function: mascara_2(integer)

-- DROP FUNCTION mascara_2(integer);

CREATE OR REPLACE FUNCTION mascara_2(integer)
  RETURNS text AS
$BODY$
DECLARE
t text;
c integer;
BEGIN
c = (SELECT length($1::text));
if  c=2 then
t = '' || $1;
elsif  c=1 then
t = '0' || $1;
else
t = $1;
end if;

RETURN t;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION mascara_2(integer) OWNER TO sisap;


DROP TABLE shd900_ingresos_numero;
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

DROP TABLE shd901_ingresos_cobro cascade;

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


CREATE TABLE shd999_cobranza_acumulada
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
ALTER TABLE shd999_cobranza_acumulada OWNER TO sisap;
COMMENT ON TABLE shd999_cobranza_acumulada IS 'Registra cobranza acumulada';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd999_cobranza_acumulada.ano IS 'Año';
COMMENT ON COLUMN shd999_cobranza_acumulada.mes IS 'Mes';
COMMENT ON COLUMN shd999_cobranza_acumulada.dia IS 'Dia';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_partida IS 'Código de partida';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_generica IS 'Código de generica';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_especifica IS 'Código de especifica';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_sub_espec IS 'Código de subespecifica';
COMMENT ON COLUMN shd999_cobranza_acumulada.cod_auxiliar IS 'Código de auxiliar';
COMMENT ON COLUMN shd999_cobranza_acumulada.deuda_vigente IS 'Deuda vigente';
COMMENT ON COLUMN shd999_cobranza_acumulada.deuda_anterior IS 'Deuda anterior';
COMMENT ON COLUMN shd999_cobranza_acumulada.monto_recargo IS 'Monto recargo';
COMMENT ON COLUMN shd999_cobranza_acumulada.monto_multa IS 'Monto multa';
COMMENT ON COLUMN shd999_cobranza_acumulada.monto_intereses IS 'Monto intereses';
COMMENT ON COLUMN shd999_cobranza_acumulada.monto_descuento IS 'Monto descuento';
COMMENT ON COLUMN shd999_cobranza_acumulada.cantidad_depositos IS 'Cantidad de depositos';
COMMENT ON COLUMN shd999_cobranza_acumulada.monto_depositos IS 'Monto de depositos';
COMMENT ON COLUMN shd999_cobranza_acumulada.cantidad_notas_credito IS 'Cantidad notas de crédito';
COMMENT ON COLUMN shd999_cobranza_acumulada.monto_notas_credito IS 'Monto notas de crédito';
COMMENT ON COLUMN shd999_cobranza_acumulada.cantidad_cheques IS 'Cantidad de cheques';
COMMENT ON COLUMN shd999_cobranza_acumulada.monto_cheques IS 'Monto de cheques';
COMMENT ON COLUMN shd999_cobranza_acumulada.cantidad_descuento IS 'Cantidad de descuentos';
COMMENT ON COLUMN shd999_cobranza_acumulada.cantidad_pagos_efectivo IS 'Cantidad de pagos en efectivo';
COMMENT ON COLUMN shd999_cobranza_acumulada.monto_pagos_efectivo IS 'Monto de pagos en efectivo';



--DROP VIEW v_consulta_ingreso;
-- View: v_consulta_ingreso
DROP VIEW v_consulta_ingreso CASCADE;

CREATE OR REPLACE VIEW v_consulta_ingreso AS
(( SELECT (a.cod_grupo::text || mascara_2(a.cod_partida))::integer AS cod_partida_completo, a.cod_grupo, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, quitar_acentos(a.descripcion) AS denominacion, quitar_acentos(a.concepto) AS concepto, ((((((((a.cod_grupo::text || mascara_2(a.cod_partida)) || mascara_2(a.cod_generica)) || mascara_2(a.cod_especifica)) || mascara_2(a.cod_sub_espec)) || mascara_2(a.cod_auxiliar)) || ', '::text) || quitar_acentos(a.descripcion)) || ' '::text) || quitar_acentos(a.concepto) AS denominacion_busqueda
   FROM cfpd01_auxiliar a
  WHERE a.cod_grupo = 3
  GROUP BY a.cod_grupo, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.descripcion, a.concepto
  ORDER BY a.cod_grupo, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar)
UNION
( SELECT (b.cod_grupo::text || mascara_2(b.cod_partida))::integer AS cod_partida_completo, b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, 0 AS cod_auxiliar, quitar_acentos(b.descripcion) AS denominacion, quitar_acentos(b.concepto) AS concepto, ((((((((b.cod_grupo || mascara_2(b.cod_partida)) || mascara_2(b.cod_generica)) || mascara_2(b.cod_especifica)) || mascara_2(b.cod_sub_espec)) || mascara_2(0)) || ', '::text) || quitar_acentos(b.descripcion)) || ', '::text) || quitar_acentos(b.concepto) AS denominacion_busqueda
   FROM cfpd01_sub_espec b
  WHERE b.cod_grupo = 3
  GROUP BY b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.descripcion, b.concepto
  ORDER BY b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec))
UNION
( SELECT (c.cod_grupo::text || mascara_2(c.cod_partida))::integer AS cod_partida_completo, c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, quitar_acentos(c.descripcion) AS denominacion, quitar_acentos(c.concepto) AS concepto, (((((((c.cod_grupo || mascara_2(c.cod_partida)) || mascara_2(c.cod_generica)) || mascara_2(c.cod_especifica)) || '0000'::text) || ', '::text) || quitar_acentos(c.descripcion)) || ', '::text) || quitar_acentos(c.concepto) AS denominacion_busqueda
   FROM cfpd01_especifica c
  WHERE c.cod_grupo = 3
  GROUP BY c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica, c.descripcion, c.concepto
  ORDER BY c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica);

ALTER TABLE v_consulta_ingreso OWNER TO sisap;

-- View: v_shd901_ingresos_cobro

-- DROP VIEW v_shd901_ingresos_cobro;

-- View: v_shd901_ingresos_cobro

CREATE OR REPLACE VIEW v_shd900_cobranza_diaria AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_comprobante, a.numero_comprobante, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.fecha_comprobante, a.rif_cedula, a.concepto_comprobante, a.deuda_vigente, a.deuda_anterior, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cod_entidad_deposito, a.cod_sucursal_deposito, a.cuenta_bancaria_deposito, a.numero_deposito, a.monto_deposito, a.fecha_deposito, a.cod_entidad_credito, a.cod_sucursal_credito, a.cuenta_bancaria_credito, a.numero_nota_credito, a.monto_nota_credito, a.fecha_nota_credito, a.cod_entidad_cheque, a.cod_sucursal_cheque, a.cuenta_bancaria_cheque, a.numero_cheque, a.monto_cheque, a.fecha_cheque, a.monto_efectivo, a.condicion_documento, a.fecha_registro, a.username_registro, a.ano_anulacion, a.numero_anulacion, a.fecha_anulacion, a.username_anulacion,a.rif_ci_cobrador, ( SELECT b.denominacion
           FROM cstd01_entidades_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_deposito) AS banco_deposito, ( SELECT b.denominacion
           FROM cstd01_entidades_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_credito) AS banco_nota_credito, ( SELECT b.denominacion
           FROM cstd01_entidades_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_cheque) AS banco_cheque, ( SELECT b.denominacion
           FROM cstd01_sucursales_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_deposito AND b.cod_sucursal = a.cod_sucursal_deposito) AS sucursal_deposito, ( SELECT b.denominacion
           FROM cstd01_sucursales_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_credito AND b.cod_sucursal = a.cod_sucursal_credito) AS sucursal_nota_credito, ( SELECT b.denominacion
           FROM cstd01_sucursales_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_cheque AND b.cod_sucursal = a.cod_sucursal_cheque) AS sucursal_cheque, ( SELECT b.denominacion
           FROM v_consulta_ingreso b
          WHERE b.cod_partida_completo = a.cod_partida AND b.cod_generica = a.cod_generica AND b.cod_especifica = a.cod_especifica AND b.cod_sub_espec = a.cod_sub_espec AND b.cod_auxiliar = a.cod_auxiliar limit 1) AS denominacion_ingreso
   FROM shd900_cobranza_diaria a;

ALTER TABLE v_shd900_cobranza_diaria OWNER TO sisap;

CREATE OR REPLACE VIEW v_cobranza_diaria AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_comprobante, a.numero_comprobante, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.fecha_comprobante, a.rif_cedula, a.concepto_comprobante, a.deuda_vigente, a.deuda_anterior, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cod_entidad_deposito, a.cod_sucursal_deposito, a.cuenta_bancaria_deposito, a.numero_deposito, a.monto_deposito, a.fecha_deposito, a.cod_entidad_credito, a.cod_sucursal_credito, a.cuenta_bancaria_credito, a.numero_nota_credito, a.monto_nota_credito, a.fecha_nota_credito, a.cod_entidad_cheque, a.cod_sucursal_cheque, a.cuenta_bancaria_cheque, a.numero_cheque, a.monto_cheque, a.fecha_cheque, a.monto_efectivo, a.condicion_documento, a.fecha_registro, a.username_registro, a.ano_anulacion, a.numero_anulacion, a.fecha_anulacion, a.username_anulacion,a.rif_ci_cobrador, a.banco_deposito, a.banco_nota_credito, a.banco_cheque, a.sucursal_deposito, a.sucursal_nota_credito, a.sucursal_cheque, a.denominacion_ingreso, b.personalidad_juridica, b.razon_social_nombres, b.fecha_inscripcion, b.nacionalidad, b.estado_civil, b.profesion, b.deno_profesion, b.cod_pais, b.deno_pais, b.cod_estado, b.deno_estado, b.cod_municipio, b.deno_municipio, b.cod_parroquia, b.deno_parroquia, b.cod_centro_poblado, b.deno_centro, b.cod_calle_avenida, b.deno_vialidad, b.cod_vereda_edificio, b.deno_vereda, b.numero_vivienda_local, b.telefonos_fijos, b.telefonos_celulares, b.correo_electronico, (((((a.numero_comprobante::text || ' '::text) || a.rif_cedula::text) || ' '::text) || quitar_acentos(b.razon_social_nombres::text)) || ' '::text) || quitar_acentos(a.denominacion_ingreso) AS denominacion_busqueda
   FROM v_shd900_cobranza_diaria a, v_shd001_registro_contribuyentes b
  WHERE b.rif_cedula::text = a.rif_cedula::text;

ALTER TABLE v_cobranza_diaria OWNER TO sisap;

CREATE OR REPLACE VIEW v_shd999_cobranza_acumulada AS
 SELECT b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.ano, b.mes, b.dia, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, b.deuda_vigente, b.deuda_anterior, b.monto_recargo, b.monto_multa, b.monto_intereses, b.monto_descuento, b.cantidad_depositos, b.monto_depositos, b.cantidad_notas_credito, b.monto_notas_credito, b.cantidad_cheques, b.monto_cheques, b.cantidad_descuento, b.cantidad_pagos_efectivo, b.monto_pagos_efectivo, ( SELECT a.denominacion
           FROM v_consulta_ingreso a
          WHERE a.cod_partida_completo = b.cod_partida AND a.cod_generica = b.cod_generica AND a.cod_especifica = b.cod_especifica AND a.cod_sub_espec = b.cod_sub_espec AND a.cod_auxiliar = b.cod_auxiliar
          ORDER BY a.denominacion DESC
         LIMIT 1) AS denominacion_ingreso
   FROM shd999_cobranza_acumulada b;

ALTER TABLE v_shd999_cobranza_acumulada OWNER TO sisap;

CREATE OR REPLACE VIEW v_shd999_cobranza_acumulada_ano AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.deuda_vigente) AS deuda_vigente, sum(a.deuda_anterior) AS deuda_anterior, sum(a.monto_recargo) AS monto_recargo, sum(a.monto_multa) AS monto_multa, sum(a.monto_intereses) AS monto_intereses, sum(a.monto_descuento) AS monto_descuento, sum(a.cantidad_depositos) AS cantidad_depositos, sum(a.monto_depositos) AS monto_depositos, sum(a.cantidad_notas_credito) AS cantidad_notas_credito, sum(a.monto_notas_credito) AS monto_notas_credito, sum(a.cantidad_cheques) AS cantidad_cheques, sum(a.monto_cheques) AS monto_cheques, sum(a.cantidad_descuento) AS cantidad_descuento, sum(a.cantidad_pagos_efectivo) AS cantidad_pagos_efectivo, sum(a.monto_pagos_efectivo) AS monto_pagos_efectivo, a.denominacion_ingreso
   FROM v_shd999_cobranza_acumulada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion_ingreso;

ALTER TABLE v_shd999_cobranza_acumulada_ano OWNER TO sisap;

CREATE OR REPLACE VIEW v_shd999_cobranza_acumulada_ano_mes AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.deuda_vigente) AS deuda_vigente, sum(a.deuda_anterior) AS deuda_anterior, sum(a.monto_recargo) AS monto_recargo, sum(a.monto_multa) AS monto_multa, sum(a.monto_intereses) AS monto_intereses, sum(a.monto_descuento) AS monto_descuento, sum(a.cantidad_depositos) AS cantidad_depositos, sum(a.monto_depositos) AS monto_depositos, sum(a.cantidad_notas_credito) AS cantidad_notas_credito, sum(a.monto_notas_credito) AS monto_notas_credito, sum(a.cantidad_cheques) AS cantidad_cheques, sum(a.monto_cheques) AS monto_cheques, sum(a.cantidad_descuento) AS cantidad_descuento, sum(a.cantidad_pagos_efectivo) AS cantidad_pagos_efectivo, sum(a.monto_pagos_efectivo) AS monto_pagos_efectivo, a.denominacion_ingreso
   FROM v_shd999_cobranza_acumulada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion_ingreso;

ALTER TABLE v_shd999_cobranza_acumulada_ano_mes OWNER TO sisap;

CREATE OR REPLACE VIEW v_shd999_cobranza_acumulada_ano_mes_dia AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.dia, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.deuda_vigente) AS deuda_vigente, sum(a.deuda_anterior) AS deuda_anterior, sum(a.monto_recargo) AS monto_recargo, sum(a.monto_multa) AS monto_multa, sum(a.monto_intereses) AS monto_intereses, sum(a.monto_descuento) AS monto_descuento, sum(a.cantidad_depositos) AS cantidad_depositos, sum(a.monto_depositos) AS monto_depositos, sum(a.cantidad_notas_credito) AS cantidad_notas_credito, sum(a.monto_notas_credito) AS monto_notas_credito, sum(a.cantidad_cheques) AS cantidad_cheques, sum(a.monto_cheques) AS monto_cheques, sum(a.cantidad_descuento) AS cantidad_descuento, sum(a.cantidad_pagos_efectivo) AS cantidad_pagos_efectivo, sum(a.monto_pagos_efectivo) AS monto_pagos_efectivo, a.denominacion_ingreso
   FROM v_shd999_cobranza_acumulada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.dia, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion_ingreso;

ALTER TABLE v_shd999_cobranza_acumulada_ano_mes_dia OWNER TO sisap;







