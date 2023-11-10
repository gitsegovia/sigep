



CREATE OR REPLACE VIEW pago_vs_orden_pago_partidas AS


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
			          COUNT( ba.cod_dep ) as partidas

			    from cepd03_ordenpago_partidas ba

			    WHERE

			          ba.cod_presi          =  a.cod_presi  and
					  ba.cod_entidad        =  a.cod_entidad  and
					  ba.cod_tipo_inst      =  a.cod_tipo_inst  and
					  ba.cod_inst           =  a.cod_inst  and
					  ba.cod_dep            =  a.cod_dep  and
					  ba.ano_orden_pago     =  b.ano_orden_pago  and
					  ba.numero_orden_pago  =  b.numero_orden_pago and
                      ba.ano                  = b.ano_movimiento and
					  ba.cod_sector           = b.cod_sector and
					  ba.cod_programa         = b.cod_programa and
					  ba.cod_sub_prog         = b.cod_sub_prog and
					  ba.cod_proyecto         = b.cod_proyecto and
					  ba.cod_activ_obra       = b.cod_activ_obra and
					  ba.cod_partida          = b.cod_partida and
					  ba.cod_generica         = b.cod_generica and
					  ba.cod_especifica       = b.cod_especifica and
					  ba.cod_sub_espec        = b.cod_sub_espec and
					  ba.cod_auxiliar         = b.cod_auxiliar

			  ) as  aparece_en_cheques_cancelaciones


FROM

  cstd03_cheque_cuerpo a, cstd03_cheque_partidas b


WHERE

  a.condicion_actividad=1 and
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

			    from cepd03_ordenpago_partidas ba

			    WHERE

			          ba.cod_presi          =  a.cod_presi  and
					  ba.cod_entidad        =  a.cod_entidad  and
					  ba.cod_tipo_inst      =  a.cod_tipo_inst  and
					  ba.cod_inst           =  a.cod_inst  and
					  ba.cod_dep            =  a.cod_dep  and
					  ba.ano_orden_pago     =  b.ano_orden_pago  and
					  ba.numero_orden_pago  =  b.numero_orden_pago and
                      ba.ano                  = b.ano_movimiento and
					  ba.cod_sector           = b.cod_sector and
					  ba.cod_programa         = b.cod_programa and
					  ba.cod_sub_prog         = b.cod_sub_prog and
					  ba.cod_proyecto         = b.cod_proyecto and
					  ba.cod_activ_obra       = b.cod_activ_obra and
					  ba.cod_partida          = b.cod_partida and
					  ba.cod_generica         = b.cod_generica and
					  ba.cod_especifica       = b.cod_especifica and
					  ba.cod_sub_espec        = b.cod_sub_espec and
					  ba.cod_auxiliar         = b.cod_auxiliar

			  ) as  aparece_en_cheques_cancelaciones


FROM

  cstd09_notadebito_cuerpo a, cstd09_notadebito_partidas b


WHERE

  a.condicion_actividad=1 and
  b.cod_presi  = a.cod_presi  and
  b.cod_entidad = a.cod_entidad  and
  b.cod_tipo_inst = a.cod_tipo_inst  and
  b.cod_inst = a.cod_inst  and
  b.cod_dep = a.cod_dep  and
  b.ano_movimiento = a.ano_movimiento  and
  b.cod_entidad_bancaria = a.cod_entidad_bancaria  and
  b.cod_sucursal = a.cod_sucursal  and
  b.cuenta_bancaria = a.cuenta_bancaria  and
  b.numero_debito = a.numero_debito





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

			    from cepd03_ordenpago_partidas ba

			    WHERE

			                  ba.cod_presi          =  a.cod_presi  and
					  ba.cod_entidad        =  a.cod_entidad  and
					  ba.cod_tipo_inst      =  a.cod_tipo_inst  and
					  ba.cod_inst           =  a.cod_inst  and
					  ba.cod_dep            =  a.cod_dep  and
					  ba.ano_orden_pago     =  b.ano_orden_pago  and
					  ba.numero_orden_pago  =  b.numero_orden_pago and
                                          ba.ano                  = b.ano_movimiento and
					  ba.cod_sector           = b.cod_sector and
					  ba.cod_programa         = b.cod_programa and
					  ba.cod_sub_prog         = b.cod_sub_prog and
					  ba.cod_proyecto         = b.cod_proyecto and
					  ba.cod_activ_obra       = b.cod_activ_obra and
					  ba.cod_partida          = b.cod_partida and
					  ba.cod_generica         = b.cod_generica and
					  ba.cod_especifica       = b.cod_especifica and
					  ba.cod_sub_espec        = b.cod_sub_espec and
					  ba.cod_auxiliar         = b.cod_auxiliar

			  ) as  aparece_en_cheques_cancelaciones


FROM

  cstd30_debito_cuerpo a, cstd30_debito_partidas b


WHERE

  a.condicion_actividad=1 and
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









ALTER TABLE pago_vs_orden_pago_partidas OWNER TO sisap;



















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
		  a.numero_cheque,
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

			    from cfpd23 ba

			    WHERE

			          ba.cod_presi          =  a.cod_presi  and
					  ba.cod_entidad        =  a.cod_entidad  and
					  ba.cod_tipo_inst      =  a.cod_tipo_inst  and
					  ba.cod_inst           =  a.cod_inst  and
					  ba.cod_dep            =  a.cod_dep  and
                      ba.ano                  = b.ano_movimiento and
					  ba.cod_sector           = b.cod_sector and
					  ba.cod_programa         = b.cod_programa and
					  ba.cod_sub_prog         = b.cod_sub_prog and
					  ba.cod_proyecto         = b.cod_proyecto and
					  ba.cod_activ_obra       = b.cod_activ_obra and
					  ba.cod_partida          = b.cod_partida and
					  ba.cod_generica         = b.cod_generica and
					  ba.cod_especifica       = b.cod_especifica and
					  ba.cod_sub_espec        = b.cod_sub_espec and
					  ba.cod_auxiliar         = b.cod_auxiliar  and

					  ba.numero_asiento_compromiso      = b.numero_control_compromiso  and
					  ba.numero_asiento_causado         = b.numero_control_causado  and
					  ba.numero_asiento_pagado          = b.numero_control_pagado


			  ) as  aparece_en_actas


FROM

  cstd03_cheque_cuerpo a, cstd03_cheque_partidas b


WHERE

  a.condicion_actividad=1 and
  b.cod_presi  = a.cod_presi  and
  b.cod_entidad = a.cod_entidad  and
  b.cod_tipo_inst = a.cod_tipo_inst  and
  b.cod_inst = a.cod_inst  and
  b.cod_dep = a.cod_dep  and
  b.ano_movimiento = a.ano_movimiento  and
  b.cod_entidad_bancaria = a.cod_entidad_bancaria  and
  b.cod_sucursal = a.cod_sucursal  and
  b.cuenta_bancaria = a.cuenta_bancaria  and
  b.numero_cheque = a.numero_cheque  and
  b.cod_sector           = 1 and
  b.cod_programa         = 10 and
  b.cod_sub_prog         = 1 and
  b.cod_proyecto         = 0 and
  b.cod_activ_obra       = 51 and
  b.cod_partida          = 403 and
  b.cod_generica         = 18 and
  b.cod_especifica       = 1 and
  b.cod_sub_espec        = 0 and
  b.cod_auxiliar         = 1
















  SELECT


		  b.cod_presi,
		  b.cod_entidad,
		  b.cod_tipo_inst,
		  b.cod_inst,
		  b.cod_dep,
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
		  b.concepto,
		  b.numero_asiento_compromiso,
		  b.numero_asiento_causado,
	      b.numero_asiento_pagado,
		  (SELECT
			          COUNT( ba.cod_dep ) as cuenta

			    from cstd03_cheque_partidas ba

			    WHERE

			          ba.cod_presi          =  b.cod_presi  and
					  ba.cod_entidad        =  b.cod_entidad  and
					  ba.cod_tipo_inst      =  b.cod_tipo_inst  and
					  ba.cod_inst           =  b.cod_inst  and
					  ba.cod_dep            =  b.cod_dep  and
                      ba.ano_movimiento                  = b.ano and
					  ba.cod_sector           = b.cod_sector and
					  ba.cod_programa         = b.cod_programa and
					  ba.cod_sub_prog         = b.cod_sub_prog and
					  ba.cod_proyecto         = b.cod_proyecto and
					  ba.cod_activ_obra       = b.cod_activ_obra and
					  ba.cod_partida          = b.cod_partida and
					  ba.cod_generica         = b.cod_generica and
					  ba.cod_especifica       = b.cod_especifica and
					  ba.cod_sub_espec        = b.cod_sub_espec and
					  ba.cod_auxiliar         = b.cod_auxiliar  and

					  ba.numero_control_compromiso      = b.numero_asiento_compromiso  and
					  ba.numero_control_causado         = b.numero_asiento_causado  and
					  ba.numero_control_pagado          = b.numero_asiento_pagado


			  ) as  aparece_en_actas


FROM

  cfpd23 b


WHERE


  b.cod_dep = 11  and
  b.ano = 2009  and
  b.cod_sector           = 1 and
  b.cod_programa         = 10 and
  b.cod_sub_prog         = 1 and
  b.cod_proyecto         = 0 and
  b.cod_activ_obra       = 51 and
  b.cod_partida          = 403 and
  b.cod_generica         = 18 and
  b.cod_especifica       = 1 and
  b.cod_sub_espec        = 0 and
  b.cod_auxiliar         = 1 and
  b.concepto    NOT LIKE '%ANULACI%'












