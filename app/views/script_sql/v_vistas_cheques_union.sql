--DROP VIEW v_vistas_cheques_union;

CREATE OR REPLACE VIEW v_vistas_cheques_union AS


  select

				  a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.ano_movimiento,
				  a.cod_entidad_bancaria,
				  a.cod_sucursal,
				  a.cuenta_bancaria,
				  a.numero_documento,
				  a.fecha_documento,
				  a.beneficiario,
				  a.monto,
				  a.concepto,
				  a.status,
				  a.fecha_proceso_registro,
				  a.dia_asiento_registro,
				  a.mes_asiento_registro,
				  a.ano_asiento_registro,
				  a.numero_asiento_registro,
				  a.username_registro,
				  a.condicion_actividad,
				  a.ano_anulacion,
				  a.numero_anulacion,
				  a.fecha_proceso_anulacion,
				  a.dia_asiento_anulacion,
				  a.mes_asiento_anulacion,
				  a.ano_asiento_anulacion,
				  a.numero_asiento_anulacion,
				  a.username_anulacion,
				  1 as tipo_cheque


from cstd03_movimientos_manuales a  where  tipo_documento = 4




UNION



  select


				  b.cod_presi,
				  b.cod_entidad,
				  b.cod_tipo_inst,
				  b.cod_inst,
				  b.cod_dep,
				  b.ano_movimiento,
				  b.cod_entidad_bancaria,
				  b.cod_sucursal,
				  b.cuenta_bancaria,
				  (b.numero_cheque) as numero_documento,
				  (b.fecha_cheque)  as fecha_documento,
				  b.beneficiario,
				  b.monto,
				  b.concepto,
				  (b.status_cheque) as status,
				  b.fecha_proceso_registro,
				  b.dia_asiento_registro,
				  b.mes_asiento_registro,
				  b.ano_asiento_registro,
				  b.numero_asiento_registro,
				  b.username_registro,
				  b.condicion_actividad,
				  b.ano_anulacion,
				  b.numero_anulacion,
				  b.fecha_proceso_anulacion,
				  b.dia_asiento_anulacion,
				  b.mes_asiento_anulacion,
				  b.ano_asiento_anulacion,
				  b.numero_asiento_anulacion,
				  b.username_anulacion,
				  2 as tipo_cheque




from cstd03_cheque_cuerpo b;






ALTER TABLE v_vistas_cheques_union OWNER TO sisap;



