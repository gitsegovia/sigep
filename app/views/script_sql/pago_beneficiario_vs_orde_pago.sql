



CREATE OR REPLACE VIEW pago_beneficiario_vs_orde_pago AS


SELECT


		  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.ano_movimiento,
		  a.cod_entidad_bancaria,
		  a.cod_sucursal,
		  a.cuenta_bancaria,
		  a.numero_cheque  as numero_documento,
		  b.ano,
		  b.cod_sector,
		  b.cod_programa,
		  b.cod_sub_prog,
		  b.cod_proyecto,
		  b.cod_activ_obra,
		  b.cod_partida,
		  b.cod_generica,
		  b.cod_especifica,
		  b.cod_sub_espec,
		  b.cod_auxiliar,
		  b.monto,
		  (SELECT
			          COUNT( ba.cod_dep ) as cuenta

			    from cepd03_ordenpago_cuerpo ba

			    WHERE

			          ba.cod_presi          =  a.cod_presi  and
					  ba.cod_entidad        =  a.cod_entidad  and
					  ba.cod_tipo_inst      =  a.cod_tipo_inst  and
					  ba.cod_inst           =  a.cod_inst  and
					  ba.cod_dep            =  a.cod_dep  and
					  ba.ano_orden_pago     =  b.ano_orden_pago  and
					  ba.numero_orden_pago  =  b.numero_orden_pago and
					  ba.ano_movimiento       = a.ano_movimiento  and
					  ba.cod_entidad_bancaria = a.cod_entidad_bancaria  and
					  ba.cod_sucursal         = a.cod_sucursal  and
					  ba.cuenta_bancaria      = a.cuenta_bancaria  and
					  ba.numero_cheque        = a.numero_cheque

			  ) as  aparece_en_cheques_cancelaciones


FROM

  cstd03_cheque_cuerpo a, cstd03_cheque_partidas b


WHERE

  a.condicion_actividad=1 and
  a.clase_beneficiario =1 and
  b.cod_presi  = a.cod_presi  and
  b.cod_entidad = a.cod_entidad  and
  b.cod_tipo_inst = a.cod_tipo_inst  and
  b.cod_inst = a.cod_inst  and
  b.cod_dep = a.cod_dep  and
  b.ano_movimiento = a.ano_movimiento  and
  b.cod_entidad_bancaria = a.cod_entidad_bancaria  and
  b.cod_sucursal = a.cod_sucursal  and
  b.cuenta_bancaria = a.cuenta_bancaria  and
  b.numero_cheque = a.numero_cheque




UNION






SELECT


		  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.ano_movimiento,
		  a.cod_entidad_bancaria,
		  a.cod_sucursal,
		  a.cuenta_bancaria,
		  a.numero_debito as numero_documento,
		  b.ano,
		  b.cod_sector,
		  b.cod_programa,
		  b.cod_sub_prog,
		  b.cod_proyecto,
		  b.cod_activ_obra,
		  b.cod_partida,
		  b.cod_generica,
		  b.cod_especifica,
		  b.cod_sub_espec,
		  b.cod_auxiliar,
		  b.monto,
		  (SELECT
			          COUNT( ba.cod_dep ) as cuenta

			    from cepd03_ordenpago_cuerpo ba

			    WHERE

			          ba.cod_presi          =  a.cod_presi  and
					  ba.cod_entidad        =  a.cod_entidad  and
					  ba.cod_tipo_inst      =  a.cod_tipo_inst  and
					  ba.cod_inst           =  a.cod_inst  and
					  ba.cod_dep            =  a.cod_dep  and
					  ba.ano_orden_pago     =  b.ano_orden_pago  and
					  ba.numero_orden_pago  =  b.numero_orden_pago and
					  ba.ano_movimiento       = a.ano_movimiento  and
					  ba.cod_entidad_bancaria = a.cod_entidad_bancaria  and
					  ba.cod_sucursal         = a.cod_sucursal  and
					  ba.cuenta_bancaria      = a.cuenta_bancaria  and
					  ba.numero_cheque        = a.numero_debito

			  ) as  aparece_en_cheques_cancelaciones


FROM

  cstd09_notadebito_cuerpo a, cstd09_notadebito_partidas b


WHERE

  a.condicion_actividad=1 and
  a.clase_beneficiario =1 and
  b.cod_presi  = a.cod_presi  and
  b.cod_entidad = a.cod_entidad  and
  b.cod_tipo_inst = a.cod_tipo_inst  and
  b.cod_inst = a.cod_inst  and
  b.cod_dep = a.cod_dep  and
  b.ano_movimiento = a.ano_movimiento  and
  b.cod_entidad_bancaria = a.cod_entidad_bancaria  and
  b.cod_sucursal = a.cod_sucursal  and
  b.cuenta_bancaria = a.cuenta_bancaria  and
  b.numero_debito = a.numero_debito;



ALTER TABLE pago_beneficiario_vs_orde_pago OWNER TO sisap;
















SELECT


		  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.ano_movimiento,
		  a.cod_entidad_bancaria,
		  a.cod_sucursal,
		  a.cuenta_bancaria,
		  a.numero_cheque  as numero_documento,
		  b.ano,
		  b.cod_sector,
		  b.cod_programa,
		  b.cod_sub_prog,
		  b.cod_proyecto,
		  b.cod_activ_obra,
		  b.cod_partida,
		  b.cod_generica,
		  b.cod_especifica,
		  b.cod_sub_espec,
		  b.cod_auxiliar,
		  b.monto,
		  (SELECT
			          COUNT( ba.cod_dep ) as cuenta

			    from cepd03_ordenpago_cuerpo ba

			    WHERE

			          ba.cod_presi          =  a.cod_presi  and
					  ba.cod_entidad        =  a.cod_entidad  and
					  ba.cod_tipo_inst      =  a.cod_tipo_inst  and
					  ba.cod_inst           =  a.cod_inst  and
					  ba.cod_dep            =  a.cod_dep  and
					  ba.ano_orden_pago     =  b.ano_orden_pago  and
					  ba.numero_orden_pago  =  b.numero_orden_pago and
					  ba.condicion_actividad = 1

			  ) as  aparece_en_cheques_cancelaciones


FROM

  cstd03_cheque_cuerpo a, cstd03_cheque_partidas b


WHERE

  a.condicion_actividad=1 and
  a.clase_beneficiario =1 and
  b.cod_presi  = a.cod_presi  and
  b.cod_entidad = a.cod_entidad  and
  b.cod_tipo_inst = a.cod_tipo_inst  and
  b.cod_inst = a.cod_inst  and
  b.cod_dep = 11  and
  b.ano_movimiento = 2009  and
  b.cod_entidad_bancaria = a.cod_entidad_bancaria  and
  b.cod_sucursal = a.cod_sucursal  and
  b.cuenta_bancaria = a.cuenta_bancaria  and
  b.numero_cheque = a.numero_cheque



