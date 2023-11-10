-- View: v_restaurar_compromisos

-- DROP VIEW v_restaurar_compromisos;

CREATE OR REPLACE VIEW v_restaurar_compromisos AS

 SELECT
			 a.cod_presi,
			 a.cod_entidad,
			 a.cod_tipo_inst,
			 a.cod_inst,
			 a.cod_dep,
			 a.ano_documento,
			 a.numero_documento,
			 a.ano,
			 a.cod_sector,
			 a.cod_programa,
			 a.cod_sub_prog,
			 a.cod_proyecto,
			 a.cod_activ_obra,
			 a.cod_partida,
			 a.cod_generica,
			 a.cod_especifica,
			 a.cod_sub_espec,
			 a.cod_auxiliar,
			 a.monto,
			 a.numero_control_compromiso,
			 b.fecha_documento,
			 b.ano_orden_pago,
			 b.numero_orden_pago,
			 b.fecha_proceso_registro,
			 b.fecha_proceso_anulacion,
			 b.condicion_actividad,
			 b.concepto,
			 b.numero_anulacion,
			 ( SELECT x.motivo_anulacion FROM cugd03_acta_anulacion_cuerpo x
			          WHERE x.cod_presi = a.cod_presi AND
			                x.cod_entidad = a.cod_entidad AND
			                x.cod_tipo_inst = a.cod_tipo_inst AND
			                x.cod_inst = a.cod_inst AND
			                x.cod_dep = a.cod_dep AND
			                x.ano_documento = a.numero_documento AND
			                x.numero_acta_anulacion = b.numero_anulacion AND
			                (x.tipo_operacion = 231 OR x.tipo_operacion = 5) AND
			                x.numero_documento::text = a.numero_documento::character varying(30)::text) AS concepto_anulacion

FROM cepd01_compromiso_partidas a, cepd01_compromiso_cuerpo b


WHERE

a.cod_presi = b.cod_presi AND
a.cod_entidad = b.cod_entidad AND
a.cod_tipo_inst = b.cod_tipo_inst AND
a.cod_inst = b.cod_inst AND
a.cod_dep = b.cod_dep AND
a.ano_documento = b.ano_documento AND
a.numero_documento = b.numero_documento;

ALTER TABLE v_restaurar_compromisos OWNER TO sisap;

