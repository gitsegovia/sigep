

DROP VIEW v_relacion_obras_infogobierno;
DROP VIEW v_relacion_servicio_infogobierno;
DROP VIEW v_relacion_orden_compra_infogobierno;

CREATE OR REPLACE VIEW v_relacion_orden_compra_infogobierno AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.condicion_actividad,
  (SELECT aa.denominacion FROM arrd01 aa WHERE
                                              aa.cod_presi    =  a.cod_presi
                                             ) as deno_cod_presi,
  (SELECT aa.denominacion FROM arrd02 aa WHERE
                                              aa.cod_presi    =  a.cod_presi   and
                                              aa.cod_entidad  =  a.cod_entidad
                                             ) as deno_cod_entidad,
  (SELECT bb.denominacion FROM arrd03 bb WHERE
                                              bb.cod_presi      =  a.cod_presi   and
                                              bb.cod_tipo_inst  =  a.cod_tipo_inst
                                             ) as deno_cod_tipo_inst,
  (SELECT cc.denominacion FROM arrd04 cc WHERE
                                              cc.cod_presi      =  a.cod_presi     and
                                              cc.cod_entidad    =  a.cod_entidad   and
                                              cc.cod_tipo_inst  =  a.cod_tipo_inst and
                                              cc.cod_inst       =  a.cod_inst
                                              ) as deno_cod_inst,
  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
  a.ano_orden_compra,
  a.numero_orden_compra,
  a.fecha_orden_compra,
  a.tipo_orden,
  a.rif,
  a.ano_cotizacion,
  a.numero_cotizacion,
  a.lugar_entrega,
  a.plazo_entrega,
  a.monto_orden,
  a.modificacion_aumento,
  a.modificacion_disminucion,
  a.monto_anticipo,
  a.monto_amortizacion,
  a.monto_cancelado,
  ((a.monto_orden + a.modificacion_aumento) - a.modificacion_disminucion) as monto_ajustado,
  ((a.monto_orden + a.modificacion_aumento) - (a.modificacion_disminucion + a.monto_anticipo)) as monto_ajustado_con_anticipo,
  (((a.monto_orden + a.modificacion_aumento) - a.modificacion_disminucion))  - (a.monto_cancelado + a.monto_amortizacion) as saldo_documento,


  (0::TEXT) as fecha_autorizacion,
  (0) as numero_pago,
  (0) as monto_iva,
  (0) as monto_cancelar,
  (0) as monto_cancelar_siniva,


  (0) as ano_orden_pago,
  (0) as numero_orden_pago,
  (0::TEXT) as fecha_orden_pago,
  (0) as monto_neto_cobrar,
  (0) as ano_movimiento,
  (0) as cod_entidad_bancaria,
  (0) as cod_sucursal,
  (0::TEXT) as cuenta_bancaria,
  (0) as numero_cheque,
  (0::TEXT) as fecha_cheque


FROM cscd04_ordencompra_encabezado a WHERE a.monto_cancelado          =  0 and a.condicion_actividad  =  1




UNION




SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.condicion_actividad,
  (SELECT aa.denominacion FROM arrd01 aa WHERE
                                              aa.cod_presi    =  a.cod_presi
                                             ) as deno_cod_presi,
  (SELECT aa.denominacion FROM arrd02 aa WHERE
                                              aa.cod_presi    =  a.cod_presi   and
                                              aa.cod_entidad  =  a.cod_entidad
                                             ) as deno_cod_entidad,
  (SELECT bb.denominacion FROM arrd03 bb WHERE
                                              bb.cod_presi      =  a.cod_presi   and
                                              bb.cod_tipo_inst  =  a.cod_tipo_inst
                                             ) as deno_cod_tipo_inst,
  (SELECT cc.denominacion FROM arrd04 cc WHERE
                                              cc.cod_presi      =  a.cod_presi     and
                                              cc.cod_entidad    =  a.cod_entidad   and
                                              cc.cod_tipo_inst  =  a.cod_tipo_inst and
                                              cc.cod_inst       =  a.cod_inst
                                              ) as deno_cod_inst,
  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
  a.ano_orden_compra,
  a.numero_orden_compra,
  a.fecha_orden_compra,
  a.tipo_orden,
  a.rif,
  a.ano_cotizacion,
  a.numero_cotizacion,
  a.lugar_entrega,
  a.plazo_entrega,
  a.monto_orden,
  a.modificacion_aumento,
  a.modificacion_disminucion,
  a.monto_anticipo,
  a.monto_amortizacion,
  a.monto_cancelado,
  ((a.monto_orden + a.modificacion_aumento) - a.modificacion_disminucion) as monto_ajustado,
  ((a.monto_orden + a.modificacion_aumento) - (a.modificacion_disminucion + a.monto_anticipo)) as monto_ajustado_con_anticipo,
  (((a.monto_orden + a.modificacion_aumento) - a.modificacion_disminucion))  - (a.monto_cancelado + a.monto_amortizacion) as saldo_documento,

  b.fecha_autorizacion::TEXT,
  b.numero_pago,
  b.monto_iva,
  b.monto_cancelar,
  b.monto_cancelar_siniva,
  b.ano_orden_pago,
  b.numero_orden_pago,


  (0::TEXT) as fecha_orden_pago,
  (0) as monto_neto_cobrar,

  (0) as ano_movimiento,
  (0) as cod_entidad_bancaria,
  (0) as cod_sucursal,
  (0::TEXT) as cuenta_bancaria,
  (0) as numero_cheque,
  (0::TEXT) as fecha_cheque


  FROM cscd04_ordencompra_encabezado a, cscd04_ordencompra_autorizacion_pago_cuerpo b


  WHERE

      a.monto_cancelado     !=  0                      and
      a.condicion_actividad  =  1                      and
	  b.cod_presi            =  a.cod_presi            and
	  b.cod_entidad          =  a.cod_entidad          and
	  b.cod_tipo_inst        =  a.cod_tipo_inst        and
	  b.cod_inst             =  a.cod_inst             and
	  b.cod_dep              =  a.cod_dep              and
	  b.ano_orden_compra     =  a.ano_orden_compra     and
	  b.numero_orden_compra  =  a.numero_orden_compra  and
	  b.ano_orden_pago       =  0                      and
	  b.numero_orden_pago    =  0



UNION




SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.condicion_actividad,
  (SELECT aa.denominacion FROM arrd01 aa WHERE
                                              aa.cod_presi    =  a.cod_presi
                                             ) as deno_cod_presi,
  (SELECT aa.denominacion FROM arrd02 aa WHERE
                                              aa.cod_presi    =  a.cod_presi   and
                                              aa.cod_entidad  =  a.cod_entidad
                                             ) as deno_cod_entidad,
  (SELECT bb.denominacion FROM arrd03 bb WHERE
                                              bb.cod_presi      =  a.cod_presi   and
                                              bb.cod_tipo_inst  =  a.cod_tipo_inst
                                             ) as deno_cod_tipo_inst,
  (SELECT cc.denominacion FROM arrd04 cc WHERE
                                              cc.cod_presi      =  a.cod_presi     and
                                              cc.cod_entidad    =  a.cod_entidad   and
                                              cc.cod_tipo_inst  =  a.cod_tipo_inst and
                                              cc.cod_inst       =  a.cod_inst
                                              ) as deno_cod_inst,
  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
  a.ano_orden_compra,
  a.numero_orden_compra,
  a.fecha_orden_compra,
  a.tipo_orden,
  a.rif,
  a.ano_cotizacion,
  a.numero_cotizacion,
  a.lugar_entrega,
  a.plazo_entrega,
  a.monto_orden,
  a.modificacion_aumento,
  a.modificacion_disminucion,
  a.monto_anticipo,
  a.monto_amortizacion,
  a.monto_cancelado,
  ((a.monto_orden + a.modificacion_aumento) - a.modificacion_disminucion) as monto_ajustado,
  ((a.monto_orden + a.modificacion_aumento) - (a.modificacion_disminucion + a.monto_anticipo)) as monto_ajustado_con_anticipo,
  (((a.monto_orden + a.modificacion_aumento) - a.modificacion_disminucion))  - (a.monto_cancelado + a.monto_amortizacion) as saldo_documento,

  b.fecha_autorizacion::TEXT,
  b.numero_pago,
  b.monto_iva,
  b.monto_cancelar,
  b.monto_cancelar_siniva,
  b.ano_orden_pago,
  b.numero_orden_pago,


  c.fecha_orden_pago::TEXT,
  c.monto_neto_cobrar,
  c.ano_movimiento,
  c.cod_entidad_bancaria,
  c.cod_sucursal,
  c.cuenta_bancaria::TEXT,
  c.numero_cheque,
  c.fecha_cheque::TEXT



  FROM cscd04_ordencompra_encabezado a, cscd04_ordencompra_autorizacion_pago_cuerpo b, cepd03_ordenpago_cuerpo c




  WHERE

      a.monto_cancelado     !=  0                      and
      a.condicion_actividad  =  1                      and
	  b.cod_presi            =  a.cod_presi            and
	  b.cod_entidad          =  a.cod_entidad          and
	  b.cod_tipo_inst        =  a.cod_tipo_inst        and
	  b.cod_inst             =  a.cod_inst             and
	  b.cod_dep              =  a.cod_dep              and
	  b.ano_orden_compra     =  a.ano_orden_compra     and
	  b.numero_orden_compra  =  a.numero_orden_compra  and
	  b.ano_orden_pago      !=  0                      and
	  b.numero_orden_pago   !=  0                      and

	  c.cod_presi            =  b.cod_presi            and
	  c.cod_entidad          =  b.cod_entidad          and
	  c.cod_tipo_inst        =  b.cod_tipo_inst        and
	  c.cod_inst             =  b.cod_inst             and
	  c.cod_dep              =  b.cod_dep              and
	  c.ano_orden_pago       =  b.ano_orden_pago       and
	  c.numero_orden_pago    =  b.numero_orden_pago;


ALTER TABLE v_relacion_orden_compra_infogobierno OWNER TO sisap;






































CREATE OR REPLACE VIEW v_relacion_obras_infogobierno AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.condicion_actividad,
  (SELECT aa.denominacion FROM arrd01 aa WHERE
                                              aa.cod_presi    =  a.cod_presi
                                             ) as deno_cod_presi,
  (SELECT aa.denominacion FROM arrd02 aa WHERE
                                              aa.cod_presi    =  a.cod_presi   and
                                              aa.cod_entidad  =  a.cod_entidad
                                             ) as deno_cod_entidad,
  (SELECT bb.denominacion FROM arrd03 bb WHERE
                                              bb.cod_presi      =  a.cod_presi   and
                                              bb.cod_tipo_inst  =  a.cod_tipo_inst
                                             ) as deno_cod_tipo_inst,
  (SELECT cc.denominacion FROM arrd04 cc WHERE
                                              cc.cod_presi      =  a.cod_presi     and
                                              cc.cod_entidad    =  a.cod_entidad   and
                                              cc.cod_tipo_inst  =  a.cod_tipo_inst and
                                              cc.cod_inst       =  a.cod_inst
                                              ) as deno_cod_inst,
  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.fecha_contrato_obra,
  a.rif,
  a.monto_original_contrato,
  a.aumento,
  a.disminucion,
  a.monto_anticipo,
  a.monto_amortizacion,
  a.monto_retencion_laboral,
  a.monto_retencion_fielcumplimiento,
  a.monto_cancelado,
  ((a.monto_original_contrato  + a.aumento) - a.disminucion) as monto_ajustado,
  ((a.monto_original_contrato  + a.aumento) - (a.disminucion + a.monto_anticipo)) as monto_ajustado_con_anticipo,
  (((a.monto_original_contrato + a.aumento) - a.disminucion))  - (a.monto_cancelado + a.monto_amortizacion + a.monto_retencion_laboral + a.monto_retencion_fielcumplimiento) as saldo_documento,


  (0::TEXT) as fecha_valuacion,
  (0) as numero_valuacion,
  (0) as monto_iva,
  (0) as monto_coniva,
  (0) as monto_siniva,


  (0) as ano_orden_pago,
  (0) as numero_orden_pago,
  (0::TEXT) as fecha_orden_pago,
  (0) as monto_neto_cobrar,
  (0) as ano_movimiento,
  (0) as cod_entidad_bancaria,
  (0) as cod_sucursal,
  (0::TEXT) as cuenta_bancaria,
  (0) as numero_cheque,
  (0::TEXT) as fecha_cheque


FROM cobd01_contratoobras_cuerpo a WHERE a.monto_cancelado =  0  and  a.condicion_actividad  =  1




UNION




SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.condicion_actividad,
  (SELECT aa.denominacion FROM arrd01 aa WHERE
                                              aa.cod_presi    =  a.cod_presi
                                             ) as deno_cod_presi,
  (SELECT aa.denominacion FROM arrd02 aa WHERE
                                              aa.cod_presi    =  a.cod_presi   and
                                              aa.cod_entidad  =  a.cod_entidad
                                             ) as deno_cod_entidad,
  (SELECT bb.denominacion FROM arrd03 bb WHERE
                                              bb.cod_presi      =  a.cod_presi   and
                                              bb.cod_tipo_inst  =  a.cod_tipo_inst
                                             ) as deno_cod_tipo_inst,
  (SELECT cc.denominacion FROM arrd04 cc WHERE
                                              cc.cod_presi      =  a.cod_presi     and
                                              cc.cod_entidad    =  a.cod_entidad   and
                                              cc.cod_tipo_inst  =  a.cod_tipo_inst and
                                              cc.cod_inst       =  a.cod_inst
                                              ) as deno_cod_inst,
  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.fecha_contrato_obra,
  a.rif,
  a.monto_original_contrato,
  a.aumento,
  a.disminucion,
  a.monto_anticipo,
  a.monto_amortizacion,
  a.monto_retencion_laboral,
  a.monto_retencion_fielcumplimiento,
  a.monto_cancelado,
  ((a.monto_original_contrato  + a.aumento) - a.disminucion) as monto_ajustado,
  ((a.monto_original_contrato  + a.aumento) - (a.disminucion + a.monto_anticipo)) as monto_ajustado_con_anticipo,
  (((a.monto_original_contrato + a.aumento) - a.disminucion))  - (a.monto_cancelado + a.monto_amortizacion + a.monto_retencion_laboral + a.monto_retencion_fielcumplimiento) as saldo_documento,


  b.fecha_valuacion::TEXT,
  b.numero_valuacion,
  b.monto_iva,
  b.monto_coniva,
  b.monto_siniva,
  b.ano_orden_pago,
  b.numero_orden_pago,


  (0::TEXT) as fecha_orden_pago,
  (0) as monto_neto_cobrar,

  (0) as ano_movimiento,
  (0) as cod_entidad_bancaria,
  (0) as cod_sucursal,
  (0::TEXT) as cuenta_bancaria,
  (0) as numero_cheque,
  (0::TEXT) as fecha_cheque


  FROM cobd01_contratoobras_cuerpo a, cobd01_contratoobras_valuacion_cuerpo b


  WHERE

      a.monto_cancelado      !=  0                      and
      a.condicion_actividad   =  1                      and
	  b.cod_presi             =  a.cod_presi            and
	  b.cod_entidad           =  a.cod_entidad          and
	  b.cod_tipo_inst         =  a.cod_tipo_inst        and
	  b.cod_inst              =  a.cod_inst             and
	  b.cod_dep               =  a.cod_dep              and
	  b.ano_contrato_obra     =  a.ano_contrato_obra    and
	  b.numero_contrato_obra  =  a.numero_contrato_obra and
	  b.ano_orden_pago        =  0                      and
	  b.numero_orden_pago     =  0



UNION




SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.condicion_actividad,
  (SELECT aa.denominacion FROM arrd01 aa WHERE
                                              aa.cod_presi    =  a.cod_presi
                                             ) as deno_cod_presi,
  (SELECT aa.denominacion FROM arrd02 aa WHERE
                                              aa.cod_presi    =  a.cod_presi   and
                                              aa.cod_entidad  =  a.cod_entidad
                                             ) as deno_cod_entidad,
  (SELECT bb.denominacion FROM arrd03 bb WHERE
                                              bb.cod_presi      =  a.cod_presi   and
                                              bb.cod_tipo_inst  =  a.cod_tipo_inst
                                             ) as deno_cod_tipo_inst,
  (SELECT cc.denominacion FROM arrd04 cc WHERE
                                              cc.cod_presi      =  a.cod_presi     and
                                              cc.cod_entidad    =  a.cod_entidad   and
                                              cc.cod_tipo_inst  =  a.cod_tipo_inst and
                                              cc.cod_inst       =  a.cod_inst
                                              ) as deno_cod_inst,
  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.fecha_contrato_obra,
  a.rif,
  a.monto_original_contrato,
  a.aumento,
  a.disminucion,
  a.monto_anticipo,
  a.monto_amortizacion,
  a.monto_retencion_laboral,
  a.monto_retencion_fielcumplimiento,
  a.monto_cancelado,
  ((a.monto_original_contrato  + a.aumento) - a.disminucion) as monto_ajustado,
  ((a.monto_original_contrato  + a.aumento) - (a.disminucion + a.monto_anticipo)) as monto_ajustado_con_anticipo,
  (((a.monto_original_contrato + a.aumento) - a.disminucion))  - (a.monto_cancelado + a.monto_amortizacion + a.monto_retencion_laboral + a.monto_retencion_fielcumplimiento) as saldo_documento,


  b.fecha_valuacion::TEXT,
  b.numero_valuacion,
  b.monto_iva,
  b.monto_coniva,
  b.monto_siniva,
  b.ano_orden_pago,
  b.numero_orden_pago,


  c.fecha_orden_pago::TEXT,
  c.monto_neto_cobrar,
  c.ano_movimiento,
  c.cod_entidad_bancaria,
  c.cod_sucursal,
  c.cuenta_bancaria::TEXT,
  c.numero_cheque,
  c.fecha_cheque::TEXT



  FROM cobd01_contratoobras_cuerpo a, cobd01_contratoobras_valuacion_cuerpo b, cepd03_ordenpago_cuerpo c




  WHERE

      a.monto_cancelado     !=  0                      and
      a.condicion_actividad  =  1                      and
	  b.cod_presi            =  a.cod_presi            and
	  b.cod_entidad          =  a.cod_entidad          and
	  b.cod_tipo_inst        =  a.cod_tipo_inst        and
	  b.cod_inst             =  a.cod_inst             and
	  b.cod_dep              =  a.cod_dep              and
	  b.ano_contrato_obra     =  a.ano_contrato_obra     and
	  b.numero_contrato_obra  =  a.numero_contrato_obra  and
	  b.ano_orden_pago      !=  0                      and
	  b.numero_orden_pago   !=  0                      and

	  c.cod_presi            =  b.cod_presi            and
	  c.cod_entidad          =  b.cod_entidad          and
	  c.cod_tipo_inst        =  b.cod_tipo_inst        and
	  c.cod_inst             =  b.cod_inst             and
	  c.cod_dep              =  b.cod_dep              and
	  c.ano_orden_pago       =  b.ano_orden_pago       and
	  c.numero_orden_pago    =  b.numero_orden_pago;


ALTER TABLE v_relacion_obras_infogobierno OWNER TO sisap;



















CREATE OR REPLACE VIEW v_relacion_servicio_infogobierno AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.condicion_actividad,
  (SELECT aa.denominacion FROM arrd01 aa WHERE
                                              aa.cod_presi    =  a.cod_presi
                                             ) as deno_cod_presi,
  (SELECT aa.denominacion FROM arrd02 aa WHERE
                                              aa.cod_presi    =  a.cod_presi   and
                                              aa.cod_entidad  =  a.cod_entidad
                                             ) as deno_cod_entidad,
  (SELECT bb.denominacion FROM arrd03 bb WHERE
                                              bb.cod_presi      =  a.cod_presi   and
                                              bb.cod_tipo_inst  =  a.cod_tipo_inst
                                             ) as deno_cod_tipo_inst,
  (SELECT cc.denominacion FROM arrd04 cc WHERE
                                              cc.cod_presi      =  a.cod_presi     and
                                              cc.cod_entidad    =  a.cod_entidad   and
                                              cc.cod_tipo_inst  =  a.cod_tipo_inst and
                                              cc.cod_inst       =  a.cod_inst
                                              ) as deno_cod_inst,
  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.fecha_contrato_servicio,
  a.rif,
  a.monto_original_contrato,
  a.aumento,
  a.disminucion,
  a.monto_anticipo,
  a.monto_amortizacion,
  a.monto_retencion_laboral,
  a.monto_retencion_fielcumplimiento,
  a.monto_cancelado,
  ((a.monto_original_contrato  + a.aumento) - a.disminucion) as monto_ajustado,
  ((a.monto_original_contrato  + a.aumento) - (a.disminucion + a.monto_anticipo)) as monto_ajustado_con_anticipo,
  (((a.monto_original_contrato + a.aumento) - a.disminucion))  - (a.monto_cancelado + a.monto_amortizacion + a.monto_retencion_laboral + a.monto_retencion_fielcumplimiento) as saldo_documento,


  (0::TEXT) as fecha_valuacion,
  (0) as numero_valuacion,
  (0) as monto_iva,
  (0) as monto_coniva,
  (0) as monto_siniva,


  (0) as ano_orden_pago,
  (0) as numero_orden_pago,
  (0::TEXT) as fecha_orden_pago,
  (0) as monto_neto_cobrar,
  (0) as ano_movimiento,
  (0) as cod_entidad_bancaria,
  (0) as cod_sucursal,
  (0::TEXT) as cuenta_bancaria,
  (0) as numero_cheque,
  (0::TEXT) as fecha_cheque


FROM cepd02_contratoservicio_cuerpo a WHERE a.monto_cancelado =  0  and  a.condicion_actividad  =  1




UNION




SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.condicion_actividad,
  (SELECT aa.denominacion FROM arrd01 aa WHERE
                                              aa.cod_presi    =  a.cod_presi
                                             ) as deno_cod_presi,
  (SELECT aa.denominacion FROM arrd02 aa WHERE
                                              aa.cod_presi    =  a.cod_presi   and
                                              aa.cod_entidad  =  a.cod_entidad
                                             ) as deno_cod_entidad,
  (SELECT bb.denominacion FROM arrd03 bb WHERE
                                              bb.cod_presi      =  a.cod_presi   and
                                              bb.cod_tipo_inst  =  a.cod_tipo_inst
                                             ) as deno_cod_tipo_inst,
  (SELECT cc.denominacion FROM arrd04 cc WHERE
                                              cc.cod_presi      =  a.cod_presi     and
                                              cc.cod_entidad    =  a.cod_entidad   and
                                              cc.cod_tipo_inst  =  a.cod_tipo_inst and
                                              cc.cod_inst       =  a.cod_inst
                                              ) as deno_cod_inst,
  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.fecha_contrato_servicio,
  a.rif,
  a.monto_original_contrato,
  a.aumento,
  a.disminucion,
  a.monto_anticipo,
  a.monto_amortizacion,
  a.monto_retencion_laboral,
  a.monto_retencion_fielcumplimiento,
  a.monto_cancelado,
  ((a.monto_original_contrato  + a.aumento) - a.disminucion) as monto_ajustado,
  ((a.monto_original_contrato  + a.aumento) - (a.disminucion + a.monto_anticipo)) as monto_ajustado_con_anticipo,
  (((a.monto_original_contrato + a.aumento) - a.disminucion))  - (a.monto_cancelado + a.monto_amortizacion + a.monto_retencion_laboral + a.monto_retencion_fielcumplimiento) as saldo_documento,


  b.fecha_valuacion::TEXT,
  b.numero_valuacion,
  b.monto_iva,
  b.monto_coniva,
  b.monto_siniva,
  b.ano_orden_pago,
  b.numero_orden_pago,


  (0::TEXT) as fecha_orden_pago,
  (0) as monto_neto_cobrar,

  (0) as ano_movimiento,
  (0) as cod_entidad_bancaria,
  (0) as cod_sucursal,
  (0::TEXT) as cuenta_bancaria,
  (0) as numero_cheque,
  (0::TEXT) as fecha_cheque


  FROM cepd02_contratoservicio_cuerpo a, cepd02_contratoservicio_valuacion_cuerpo b


  WHERE

      a.monto_cancelado      !=  0                      and
      a.condicion_actividad   =  1                      and
	  b.cod_presi             =  a.cod_presi            and
	  b.cod_entidad           =  a.cod_entidad          and
	  b.cod_tipo_inst         =  a.cod_tipo_inst        and
	  b.cod_inst              =  a.cod_inst             and
	  b.cod_dep               =  a.cod_dep              and
	  b.ano_contrato_servicio     =  a.ano_contrato_servicio    and
	  b.numero_contrato_servicio  =  a.numero_contrato_servicio and
	  b.ano_orden_pago        =  0                      and
	  b.numero_orden_pago     =  0



UNION




SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.condicion_actividad,
  (SELECT aa.denominacion FROM arrd01 aa WHERE
                                              aa.cod_presi    =  a.cod_presi
                                             ) as deno_cod_presi,
  (SELECT aa.denominacion FROM arrd02 aa WHERE
                                              aa.cod_presi    =  a.cod_presi   and
                                              aa.cod_entidad  =  a.cod_entidad
                                             ) as deno_cod_entidad,
  (SELECT bb.denominacion FROM arrd03 bb WHERE
                                              bb.cod_presi      =  a.cod_presi   and
                                              bb.cod_tipo_inst  =  a.cod_tipo_inst
                                             ) as deno_cod_tipo_inst,
  (SELECT cc.denominacion FROM arrd04 cc WHERE
                                              cc.cod_presi      =  a.cod_presi     and
                                              cc.cod_entidad    =  a.cod_entidad   and
                                              cc.cod_tipo_inst  =  a.cod_tipo_inst and
                                              cc.cod_inst       =  a.cod_inst
                                              ) as deno_cod_inst,
  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.fecha_contrato_servicio,
  a.rif,
  a.monto_original_contrato,
  a.aumento,
  a.disminucion,
  a.monto_anticipo,
  a.monto_amortizacion,
  a.monto_retencion_laboral,
  a.monto_retencion_fielcumplimiento,
  a.monto_cancelado,
  ((a.monto_original_contrato  + a.aumento) - a.disminucion) as monto_ajustado,
  ((a.monto_original_contrato  + a.aumento) - (a.disminucion + a.monto_anticipo)) as monto_ajustado_con_anticipo,
  (((a.monto_original_contrato + a.aumento) - a.disminucion))  - (a.monto_cancelado + a.monto_amortizacion + a.monto_retencion_laboral + a.monto_retencion_fielcumplimiento) as saldo_documento,


  b.fecha_valuacion::TEXT,
  b.numero_valuacion,
  b.monto_iva,
  b.monto_coniva,
  b.monto_siniva,
  b.ano_orden_pago,
  b.numero_orden_pago,


  c.fecha_orden_pago::TEXT,
  c.monto_neto_cobrar,
  c.ano_movimiento,
  c.cod_entidad_bancaria,
  c.cod_sucursal,
  c.cuenta_bancaria::TEXT,
  c.numero_cheque,
  c.fecha_cheque::TEXT



  FROM cepd02_contratoservicio_cuerpo a, cepd02_contratoservicio_valuacion_cuerpo b, cepd03_ordenpago_cuerpo c




  WHERE

      a.monto_cancelado     !=  0                      and
      a.condicion_actividad  =  1                      and
	  b.cod_presi            =  a.cod_presi            and
	  b.cod_entidad          =  a.cod_entidad          and
	  b.cod_tipo_inst        =  a.cod_tipo_inst        and
	  b.cod_inst             =  a.cod_inst             and
	  b.cod_dep              =  a.cod_dep              and
	  b.ano_contrato_servicio     =  a.ano_contrato_servicio     and
	  b.numero_contrato_servicio  =  a.numero_contrato_servicio  and
	  b.ano_orden_pago      !=  0                      and
	  b.numero_orden_pago   !=  0                      and

	  c.cod_presi            =  b.cod_presi            and
	  c.cod_entidad          =  b.cod_entidad          and
	  c.cod_tipo_inst        =  b.cod_tipo_inst        and
	  c.cod_inst             =  b.cod_inst             and
	  c.cod_dep              =  b.cod_dep              and
	  c.ano_orden_pago       =  b.ano_orden_pago       and
	  c.numero_orden_pago    =  b.numero_orden_pago;


ALTER TABLE v_relacion_servicio_infogobierno OWNER TO sisap;






