

DROP VIEW  compromisos_sin_partidas;

CREATE OR REPLACE VIEW  compromisos_sin_partidas  AS


SELECT


		  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.ano_documento,
		  a.numero_documento,
		  a.cod_tipo_compromiso,
		  a.fecha_documento,
		  a.tipo_recurso,
		  a.rif,
		  a.cedula_identidad,
		  a.cod_dir_superior,
		  a.cod_coordinacion,
		  a.cod_secretaria,
		  a.cod_direccion,
		  a.concepto,
		  a.monto,
		  a.condicion_actividad,
		  a.dia_asiento_registro,
		  a.mes_asiento_registro,
		  a.ano_asiento_registro,
		  a.numero_asiento_registro,
		  a.username_registro,
		  a.ano_anulacion,
		  a.numero_anulacion,
		  a.dia_asiento_anulacion,
		  a.mes_asiento_anulacion,
		  a.ano_asiento_anulacion,
		  a.numero_asiento_anulacion,
		  a.username_anulacion,
		  a.ano_orden_pago,
		  a.numero_orden_pago,
		  a.beneficiario,
		  a.condicion_juridica,
		  a.fecha_proceso_registro,
		  a.fecha_proceso_anulacion,

		  (SELECT
			          COUNT( ba.cod_dep ) as partidas

			    from cepd01_compromiso_partidas ba

			    WHERE

			          ba.cod_presi          =  a.cod_presi  and
					  ba.cod_entidad        =  a.cod_entidad  and
					  ba.cod_tipo_inst      =  a.cod_tipo_inst  and
					  ba.cod_inst           =  a.cod_inst  and
					  ba.cod_dep            =  a.cod_dep  and
					  ba.ano_documento      =  a.ano_documento  and
					  ba.numero_documento   =  a.numero_documento

			  ) as  aparece_tabla_partidas


FROM

  cepd01_compromiso_cuerpo a;



ALTER TABLE compromisos_sin_partidas OWNER TO sisap;