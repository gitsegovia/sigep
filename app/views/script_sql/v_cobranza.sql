
CREATE OR REPLACE VIEW v_cobranza AS
 SELECT
 a.cod_presi,
 a.cod_entidad,
 a.cod_tipo_inst,
 a.cod_inst,
 a.cod_dep,
 a.ano_comprobante,
 a.numero_comprobante,
 a.cod_partida,
 a.cod_generica,
 a.cod_especifica,
 a.cod_sub_espec,
 a.cod_auxiliar,
 a.fecha_comprobante,
 a.rif_cedula,
 a.concepto_comprobante,
 a.deuda_vigente,
 a.deuda_anterior,
 a.monto_recargo,
 a.monto_multa,
 a.monto_intereses,
 a.monto_descuento,
 a.cod_entidad_deposito,
 a.cod_sucursal_deposito,
 a.cuenta_bancaria_deposito,
 a.numero_deposito,
 a.monto_deposito,
 a.fecha_deposito,
 a.cod_entidad_credito,
 a.cod_sucursal_credito,
 a.cuenta_bancaria_credito,
 a.numero_nota_credito,
 a.monto_nota_credito,
 a.fecha_nota_credito,
 a.cod_entidad_cheque,
 a.cod_sucursal_cheque,
 a.cuenta_bancaria_cheque,
 a.numero_cheque,
 a.monto_cheque,
 a.fecha_cheque,
 a.monto_efectivo,
 a.condicion_documento,
 a.fecha_registro,
 a.username_registro,
 a.ano_anulacion,
 a.numero_anulacion,
 a.fecha_anulacion,
 a.username_anulacion,
 a.rif_ci_cobrador,
 a.banco_deposito,
 a.banco_nota_credito,
 a.banco_cheque,
 a.sucursal_deposito,
 a.sucursal_nota_credito,
 a.sucursal_cheque,
 a.denominacion_ingreso,
 b.personalidad_juridica,
 b.razon_social_nombres,
 b.fecha_inscripcion,
 b.nacionalidad,
 b.estado_civil,
 b.profesion,
 b.deno_profesion,
 b.cod_pais,
 b.deno_pais,
 b.cod_estado,
 b.deno_estado,
 b.cod_municipio,
 b.deno_municipio,
 b.cod_parroquia,
 b.deno_parroquia,
 b.cod_centro_poblado,
 b.deno_centro,
 b.cod_calle_avenida,
 b.deno_vialidad,
 b.cod_vereda_edificio,
 b.deno_vereda,
 b.numero_vivienda_local,
 b.telefonos_fijos,
 b.telefonos_celulares,
 b.correo_electronico,
 (((((a.numero_comprobante::text || ' '::text) || a.rif_cedula::text) || ' '::text) || quitar_acentos(b.razon_social_nombres::text)) || ' '::text) || quitar_acentos(a.denominacion_ingreso) AS denominacion_busqueda
   FROM v_shd900_cobranza_diaria a, v_shd001_registro_contribuyentes b
  WHERE b.rif_cedula::text = a.rif_cedula::text and (SELECT count(*) FROM shd900_cobranza_diaria_planillas c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_comprobante=a.ano_comprobante and c.numero_comprobante=a.numero_comprobante) != 0;

ALTER TABLE v_cobranza OWNER TO sisap;


CREATE OR REPLACE VIEW v_cobranza_diaria AS
 SELECT
 a.cod_presi, a.cod_entidad,
 a.cod_tipo_inst,
 a.cod_inst,
 a.cod_dep,
 a.ano_comprobante,
 a.numero_comprobante,
 a.cod_partida,
 a.cod_generica,
 a.cod_especifica,
 a.cod_sub_espec,
 a.cod_auxiliar,
 a.fecha_comprobante,
 a.rif_cedula,
 a.concepto_comprobante,
 a.deuda_vigente,
 a.deuda_anterior,
 a.monto_recargo,
 a.monto_multa,
 a.monto_intereses,
 a.monto_descuento,
 a.cod_entidad_deposito,
 a.cod_sucursal_deposito,
 a.cuenta_bancaria_deposito,
 a.numero_deposito,
 a.monto_deposito,
 a.fecha_deposito,
 a.cod_entidad_credito,
 a.cod_sucursal_credito,
 a.cuenta_bancaria_credito,
 a.numero_nota_credito,
 a.monto_nota_credito,
 a.fecha_nota_credito,
 a.cod_entidad_cheque,
 a.cod_sucursal_cheque,
 a.cuenta_bancaria_cheque,
 a.numero_cheque,
 a.monto_cheque,
 a.fecha_cheque,
 a.monto_efectivo,
 a.condicion_documento,
 a.fecha_registro,
 a.username_registro,
 a.ano_anulacion,
 a.numero_anulacion,
 a.fecha_anulacion,
 a.username_anulacion,
 a.rif_ci_cobrador,
 a.banco_deposito,
 a.banco_nota_credito,
 a.banco_cheque,
 a.sucursal_deposito,
 a.sucursal_nota_credito,
 a.sucursal_cheque,
 a.denominacion_ingreso,
 b.personalidad_juridica,
b.razon_social_nombres,
b.fecha_inscripcion,
b.nacionalidad,
b.estado_civil,
b.profesion,
b.deno_profesion,
b.cod_pais,
b.deno_pais,
b.cod_estado,
b.deno_estado,
b.cod_municipio,
b.deno_municipio,
b.cod_parroquia,
b.deno_parroquia,
b.cod_centro_poblado,
b.deno_centro,
b.cod_calle_avenida,
b.deno_vialidad,
b.cod_vereda_edificio,
b.deno_vereda,
b.numero_vivienda_local,
b.telefonos_fijos,
b.telefonos_celulares,
b.correo_electronico,
(((((a.numero_comprobante::text || ' '::text) || a.rif_cedula::text) || ' '::text) || quitar_acentos(b.razon_social_nombres::text)) || ' '::text) || quitar_acentos(a.denominacion_ingreso) AS denominacion_busqueda
   FROM v_shd900_cobranza_diaria a, v_shd001_registro_contribuyentes b
  WHERE b.rif_cedula::text = a.rif_cedula::text
   AND (( SELECT count(*) AS count
           FROM shd900_cobranza_diaria_planillas c
          WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.ano_comprobante = a.ano_comprobante AND c.numero_comprobante = a.numero_comprobante)) = 0;

ALTER TABLE v_cobranza_diaria OWNER TO sisap;


-- View: v_shd000_control_actua_partida

-- DROP VIEW v_shd000_control_actua_partida;



CREATE OR REPLACE VIEW v_shd000_control_actua_partida AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_ingreso, a.ano_actualizado, a.mes_actualizado, a.condicion, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_subespec AS cod_sub_espec, b.cod_auxiliar
   FROM shd000_control_actualizacion a, shd003_codigo_ingresos b
  WHERE a.cod_ingreso = b.cod_ingreso;

ALTER TABLE v_shd000_control_actua_partida OWNER TO sisap;
COMMENT ON VIEW v_shd000_control_actua_partida IS 'vista de la union de control actualizacion con los codigos de ingresos para traerse los codigos de las partidas';


-- View: v_shd900_pdpcdc

-- DROP VIEW v_shd900_pdpcdc;

CREATE OR REPLACE VIEW v_shd900_pdpcdc AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.rif_cedula, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.fecha_emision, b.condicion
   FROM shd900_planillas_deuda_cobro_detalles a, v_shd000_control_actua_partida b
  WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_actualizado = a.ano AND b.mes_actualizado = a.mes AND b.cod_partida = a.cod_partida AND b.cod_generica = a.cod_generica AND b.cod_especifica = a.cod_especifica AND b.cod_sub_espec = a.cod_sub_espec AND b.cod_auxiliar = a.cod_auxiliar;

ALTER TABLE v_shd900_pdpcdc OWNER TO sisap;
COMMENT ON VIEW v_shd900_pdpcdc IS 'vista de la tabla shd900_planilla_deuda_cobro_detalles
la cual se trae la condicion de la vista v_shd000_control_actua_partida';


