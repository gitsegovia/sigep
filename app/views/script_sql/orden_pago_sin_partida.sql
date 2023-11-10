

--DROP VIEW  orden_pago_sin_partida;

CREATE OR REPLACE VIEW  orden_pago_sin_partida  AS


SELECT


		  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.ano_orden_pago,
		  a.numero_orden_pago,
		  a.tipo_orden,
		  a.fecha_orden_pago,
		  a.ano_documento_origen,
		  a.numero_documento_origen,
		  a.numero_documento_adjunto,
		  a.fecha_documento,
		  a.cod_tipo_documento,
		  a.rif,
		  a.beneficiario,
		  a.autorizado,
		  a.cedula_identidad,
		  a.concepto,
		  a.monto_total,
		  a.numero_pago,
		  a.monto_parcial,
		  a.cod_frecuencia_pago,
		  a.fecha_desde,
		  a.fecha_hasta,
		  a.cod_tipo_pago,
		  a.monto_coniva,
		  a.monto_iva,
		  a.porcentaje_iva,
		  a.monto_siniva,
		  a.monto_retencion_laboral,
		  a.porcentaje_laboral,
		  a.monto_retencion_fielcumplimiento,
		  a.porcentaje_fielcumplimiento,
		  a.monto_descontar_impuesto,
		  a.amortizacion_anticipo,
		  a.porcentaje_amortizacion,
		  a.monto_orden_pago,
		  a.monto_retencion_iva,
		  a.porcentaje_retencion_iva,
		  a.monto_islr,
		  a.porcentaje_islr,
		  a.monto_sustraendo,
		  a.monto_timbre_fiscal,
		  a.porcentaje_timbre_fiscal,
		  a.monto_impuesto_municipal,
		  a.porcentaje_impuesto_municipal,
		  a.monto_neto_cobrar,
		  a.dia_asiento_registro,
		  a.mes_asiento_registro,
		  a.ano_asiento_registro,
		  a.numero_asiento_registro,
		  a.username_registro,
		  a.condicion_actividad,
		  a.ano_anulacion,
		  a.numero_anulacion,
		  a.dia_asiento_anulacion,
		  a.mes_asiento_anulacion,
		  a.ano_asiento_anulacion,
		  a.numero_asiento_anulacion,
		  a.username_anulacion,
		  a.ano_movimiento,
		  a.cod_entidad_bancaria,
		  a.cod_sucursal,
		  a.cuenta_bancaria,
		  a.numero_cheque,
		  a.fecha_cheque,
		  a.fecha_proceso_registro,
		  a.fecha_proceso_anulacion,
		  a.numero_comprobante_islr,
		  a.numero_comprobante_timbre,
		  a.numero_comprobante_municipal,
		  a.numero_comprobante_iva,
		  a.numero_comprobante_librocompras,
		  a.numero_comprobante_egreso,
		  a.documento_pago,
		  a.retencion_multa,
		  a.retencion_responsabilidad,
		  a.numero_comprobante_multa,
		  a.numero_comprobante_responsabilidad,

		  (SELECT
			          COUNT( ba.cod_dep ) as partidas

			    from cepd03_ordenpago_partidas ba

			    WHERE

			          ba.cod_presi           =  a.cod_presi  and
					  ba.cod_entidad         =  a.cod_entidad  and
					  ba.cod_tipo_inst       =  a.cod_tipo_inst  and
					  ba.cod_inst            =  a.cod_inst  and
					  ba.cod_dep             =  a.cod_dep  and
					  ba.ano_orden_pago      =  a.ano_orden_pago  and
					  ba.numero_orden_pago   =  a.numero_orden_pago

			  ) as  aparece_tabla_partidas


FROM

  cepd03_ordenpago_cuerpo a;



ALTER TABLE orden_pago_sin_partida OWNER TO sisap;