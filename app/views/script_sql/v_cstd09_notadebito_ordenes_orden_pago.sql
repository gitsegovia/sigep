
DROP VIEW v_cstd09_notadebito_ordenes_orden_pago;

CREATE OR REPLACE VIEW v_cstd09_notadebito_ordenes_orden_pago AS


 SELECT


      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.clase_orden,
	  a.ano_orden_pago,
	  a.numero_orden_pago,
	  a.ano_movimiento,
	  a.cod_entidad_bancaria,
	  a.cod_sucursal,
	  a.cuenta_bancaria,
	  a.numero_debito,
	  b.tipo_orden,
	  b.fecha_orden_pago,
	  b.ano_documento_origen,
	  b.numero_documento_origen,
	  b.numero_documento_adjunto,
	  b.fecha_documento,
	  b.cod_tipo_documento,
	  b.rif,
	  b.beneficiario,
	  b.autorizado,
	  b.cedula_identidad,
	  b.concepto,
	  b.monto_total,
	  b.numero_pago,
	  b.monto_parcial,
	  b.cod_frecuencia_pago,
	  b.fecha_desde,
	  b.fecha_hasta,
	  b.cod_tipo_pago,
	  b.monto_coniva,
	  b.monto_iva,
	  b.porcentaje_iva,
	  b.monto_siniva,
	  b.monto_retencion_laboral,
	  b.porcentaje_laboral,
	  b.monto_retencion_fielcumplimiento,
	  b.porcentaje_fielcumplimiento,
	  b.monto_descontar_impuesto,
	  b.amortizacion_anticipo,
	  b.porcentaje_amortizacion,
	  b.monto_orden_pago,
	  b.monto_retencion_iva,
	  b.porcentaje_retencion_iva,
	  b.monto_islr,
	  b.porcentaje_islr,
	  b.monto_sustraendo,
	  b.monto_timbre_fiscal,
	  b.porcentaje_timbre_fiscal,
	  b.monto_impuesto_municipal,
	  b.porcentaje_impuesto_municipal,
	  b.monto_neto_cobrar,
	  b.dia_asiento_registro,
	  b.mes_asiento_registro,
	  b.ano_asiento_registro,
	  b.numero_asiento_registro,
	  b.username_registro,
	  b.condicion_actividad,
	  b.ano_anulacion,
	  b.numero_anulacion,
	  b.dia_asiento_anulacion,
	  b.mes_asiento_anulacion,
	  b.ano_asiento_anulacion,
	  b.numero_asiento_anulacion,
	  b.username_anulacion,
	  b.fecha_proceso_registro,
	  b.fecha_proceso_anulacion,
	  b.numero_comprobante_islr,
	  b.numero_comprobante_timbre,
	  b.numero_comprobante_municipal,
	  b.numero_comprobante_iva,
	  b.numero_comprobante_librocompras,
	  b.numero_comprobante_egreso,
	  b.documento_pago,
	  b.retencion_multa,
	  b.retencion_responsabilidad,
	  b.numero_comprobante_multa,
	  b.numero_comprobante_responsabilidad,
	  (SELECT aa.fecha_documento from cepd01_compromiso_cuerpo aa where     aa.cod_presi            =  a.cod_presi                and
																		   aa.cod_entidad          =  a.cod_entidad              and
																		   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																		   aa.cod_inst             =  a.cod_inst                 and
																		   aa.cod_dep              =  a.cod_dep                  and
																		   aa.ano_documento        =  b.ano_documento_origen     and
																		   aa.numero_documento::text     =  b.numero_documento_origen::text and
																		   b.cod_tipo_documento          = 1


	   ) as fecha_documento_compromiso,

	   (SELECT aa.fecha_anticipo from cscd04_ordencompra_anticipo_cuerpo aa where   aa.cod_presi            =  a.cod_presi                and
																				   aa.cod_entidad          =  a.cod_entidad              and
																				   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																				   aa.cod_inst             =  a.cod_inst                 and
																				   aa.cod_dep              =  a.cod_dep                  and
																				   aa.ano_orden_compra     =  b.ano_documento_origen     and
																				   aa.numero_orden_compra::text  =  b.numero_documento_origen::text  and
																				   aa.numero_anticipo::text      =  b.numero_documento_adjunto::text and
																				   b.cod_tipo_documento          = 2

	   ) as fecha_anticipo_compra,

	   (SELECT aa.fecha_autorizacion from cscd04_ordencompra_autorizacion_pago_cuerpo aa where     aa.cod_presi            =  a.cod_presi                and
																								   aa.cod_entidad          =  a.cod_entidad              and
																								   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																								   aa.cod_inst             =  a.cod_inst                 and
																								   aa.cod_dep              =  a.cod_dep                  and
																								   aa.ano_orden_compra     =  b.ano_documento_origen     and
																								   aa.numero_orden_compra::text  =  b.numero_documento_origen::text  and
																								   aa.numero_pago::text          =  b.numero_documento_adjunto::text and
																								    b.cod_tipo_documento          = 3

	   ) as fecha_autorizacion_compra,

	   (SELECT aa.fecha_orden_compra from cscd04_ordencompra_encabezado aa where    aa.cod_presi            =  a.cod_presi                and
																				   aa.cod_entidad          =  a.cod_entidad              and
																				   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																				   aa.cod_inst             =  a.cod_inst                 and
																				   aa.cod_dep              =  a.cod_dep                  and
																				   aa.ano_orden_compra::text     =  b.ano_documento_origen::text     and
																				   aa.numero_orden_compra::text  =  b.numero_documento_origen::text  and
																				   (b.cod_tipo_documento = 2 or b.cod_tipo_documento = 3)

	   ) as fecha_orden_compra_compra,



	   (SELECT aa.fecha_anticipo from cobd01_contratoobras_anticipo_cuerpo aa where     aa.cod_presi            =  a.cod_presi                and
																					   aa.cod_entidad          =  a.cod_entidad              and
																					   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																					   aa.cod_inst             =  a.cod_inst                 and
																					   aa.cod_dep              =  a.cod_dep                  and
																					   aa.ano_contrato_obra    =  b.ano_documento_origen     and
																					   aa.numero_contrato_obra::text =  b.numero_documento_origen::text  and
																					   aa.numero_anticipo::text      =  b.numero_documento_adjunto::text and
																					   b.cod_tipo_documento          = 4

	   ) as fecha_anticipo_obra,

	   (SELECT aa.fecha_valuacion from cobd01_contratoobras_valuacion_cuerpo aa where       aa.cod_presi            =  a.cod_presi                and
																						   aa.cod_entidad          =  a.cod_entidad              and
																						   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																						   aa.cod_inst             =  a.cod_inst                 and
																						   aa.cod_dep              =  a.cod_dep                  and
																						   aa.ano_contrato_obra    =  b.ano_documento_origen     and
																						   aa.numero_contrato_obra::text =  b.numero_documento_origen::text  and
																						   aa.numero_valuacion::text     =  b.numero_documento_adjunto::text and
																						    b.cod_tipo_documento          = 5

	   ) as fecha_valuacion_obra,

	   (SELECT aa.fecha_retencion from cobd01_contratoobras_retencion_cuerpo aa where       aa.cod_presi            =  a.cod_presi                and
																						   aa.cod_entidad          =  a.cod_entidad              and
																						   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																						   aa.cod_inst             =  a.cod_inst                 and
																						   aa.cod_dep              =  a.cod_dep                  and
																						   aa.ano_contrato_obra    =  b.ano_documento_origen     and
																						   aa.numero_contrato_obra::text =  b.numero_documento_origen::text  and
																						   aa.numero_retencion::text     =  b.numero_documento_adjunto::text and
																						    b.cod_tipo_documento          = 6

	   ) as fecha_retencion_obra,

	   (SELECT aa.fecha_contrato_obra from cobd01_contratoobras_cuerpo aa where     aa.cod_presi            =  a.cod_presi                and
																				   aa.cod_entidad          =  a.cod_entidad              and
																				   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																				   aa.cod_inst             =  a.cod_inst                 and
																				   aa.cod_dep              =  a.cod_dep                  and
																				   aa.ano_contrato_obra     =  b.ano_documento_origen     and
																				   aa.numero_contrato_obra::text  =  b.numero_documento_origen::text and
																				   (b.cod_tipo_documento = 4 or b.cod_tipo_documento = 5  or b.cod_tipo_documento = 6)

	   ) as fecha_contrato_obra_obra,





	   (SELECT aa.fecha_anticipo from cepd02_contratoservicio_anticipo_cuerpo aa where  aa.cod_presi            =  a.cod_presi                and
																					   aa.cod_entidad          =  a.cod_entidad              and
																					   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																					   aa.cod_inst             =  a.cod_inst                 and
																					   aa.cod_dep              =  a.cod_dep                  and
																					   aa.ano_contrato_servicio    =  b.ano_documento_origen     and
																					   aa.numero_contrato_servicio::text =  b.numero_documento_origen::text  and
																					   aa.numero_anticipo::text          =  b.numero_documento_adjunto::text and
																					    b.cod_tipo_documento          = 7

	   ) as fecha_anticipo_servicio,

	   (SELECT aa.fecha_valuacion from cepd02_contratoservicio_valuacion_cuerpo aa  where   aa.cod_presi            =  a.cod_presi                and
																						   aa.cod_entidad          =  a.cod_entidad              and
																						   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																						   aa.cod_inst             =  a.cod_inst                 and
																						   aa.cod_dep              =  a.cod_dep                  and
																						   aa.ano_contrato_servicio    =  b.ano_documento_origen     and
																						   aa.numero_contrato_servicio::text =  b.numero_documento_origen::text  and
																						   aa.numero_valuacion::text         =  b.numero_documento_adjunto::text and
																						    b.cod_tipo_documento          = 8

	   ) as fecha_valuacion_servicio,

	   (SELECT aa.fecha_retencion from cepd02_contratoservicio_retencion_cuerpo aa where    aa.cod_presi            =  a.cod_presi                and
																						   aa.cod_entidad          =  a.cod_entidad              and
																						   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																						   aa.cod_inst             =  a.cod_inst                 and
																						   aa.cod_dep              =  a.cod_dep                  and
																						   aa.ano_contrato_servicio    =  b.ano_documento_origen     and
																						   aa.numero_contrato_servicio::text =  b.numero_documento_origen::text  and
																						   aa.numero_retencion::text         =  b.numero_documento_adjunto::text and
																						    b.cod_tipo_documento          = 9

	   ) as fecha_retencion_servicio,

	   (SELECT aa.fecha_contrato_servicio from cepd02_contratoservicio_cuerpo aa where  aa.cod_presi            =  a.cod_presi                and
																					   aa.cod_entidad          =  a.cod_entidad              and
																					   aa.cod_tipo_inst        =  a.cod_tipo_inst            and
																					   aa.cod_inst             =  a.cod_inst                 and
																					   aa.cod_dep              =  a.cod_dep                  and
																					   aa.ano_contrato_servicio           =  b.ano_documento_origen     and
																					   aa.numero_contrato_servicio::text  =  b.numero_documento_origen::text and
																					   (b.cod_tipo_documento = 7 or b.cod_tipo_documento = 8  or b.cod_tipo_documento = 9)

	   ) as fecha_contrato_servicio_servicio





FROM   cstd09_notadebito_ordenes a, cepd03_ordenpago_cuerpo b


where  b.cod_presi            =  a.cod_presi                and
	   b.cod_entidad          =  a.cod_entidad              and
	   b.cod_tipo_inst        =  a.cod_tipo_inst            and
	   b.cod_inst             =  a.cod_inst                 and
	   b.cod_dep              =  a.cod_dep                  and
	   b.ano_orden_pago       =  a.ano_orden_pago           and
	   b.numero_orden_pago    =  a.numero_orden_pago


ORDER  BY

       a.cod_presi,
	   a.cod_entidad,
	   a.cod_tipo_inst,
	   a.cod_inst,
	   a.cod_dep,
	   a.ano_orden_pago,
	   a.numero_orden_pago;



ALTER TABLE v_cstd09_notadebito_ordenes_orden_pago OWNER TO sisap;












