


CREATE OR REPLACE VIEW v_cepd02_contratoservicio_cuerpo AS


SELECT

				  a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.ano_contrato_servicio,
				  a.numero_contrato_servicio,
				   ((a.numero_contrato_servicio::text) || ' - '  || ((select b.denominacion   from cpcd02 b where b.rif = a.rif)::text)) as deno_numero_contrato_con_rif,
				  a.codigo_prod_serv,
				  a.cod_dir_superior,
				  a.cod_coordinacion,
				  a.cod_secretaria,
				  a.cod_direccion,
				  a.rif,
				  a.concepto,
				  a.fecha_contrato_servicio,
				  a.fecha_inicio_contrato,
				  a.fecha_terminacion_contrato,
				  a.monto_original_contrato,
				  a.aumento,
				  a.disminucion,
				  a.monto_anticipo,
				  a.monto_amortizacion,
				  a.monto_retencion_laboral,
				  a.monto_retencion_fielcumplimiento,
				  a.monto_cancelado,
				  a.porcentaje_iva,
				  a.porcentaje_anticipo,
				  a.anticipo_con_iva,
				  a.fecha_proceso_registro,
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
				  a.fecha_proceso_anulacion,
				  a.username_anulacion,
				  a.laboral_cancelado,
				  a.fielcumplimiento_cancelado,
				  a.saldo_ano_anterior



FROM cepd02_contratoservicio_cuerpo a;

ALTER TABLE v_cepd02_contratoservicio_cuerpo OWNER TO sisap;




