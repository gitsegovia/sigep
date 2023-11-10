-- View: v_restaurar_pagados

-- DROP VIEW v_restaurar_pagados;

CREATE OR REPLACE VIEW v_restaurar_pagados AS

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
		 a.clase_orden,
		 a.ano_orden_pago,
		 a.numero_orden_pago,
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
		 a.numero_control_causado,
		 a.numero_control_pagado,
		 b.fecha_proceso_registro,
		 b.fecha_proceso_anulacion,
		 b.numero_anulacion,
		 b.condicion_actividad,
		 b.concepto,
		 b.fecha_cheque,
		 b.ano_anterior,
		 b.clase_beneficiario,
		 (SELECT x.motivo_anulacion FROM cugd03_acta_anulacion_cuerpo x
		          WHERE x.cod_presi = a.cod_presi AND
		                x.cod_entidad = a.cod_entidad AND
		                x.cod_tipo_inst = a.cod_tipo_inst AND
		                x.cod_inst = a.cod_inst AND
		                x.cod_dep = a.cod_dep AND
		                x.numero_acta_anulacion = b.numero_anulacion AND
		                x.tipo_operacion = 251 AND
		                x.ano_documento = a.ano_movimiento AND
		                x.numero_documento::text = a.numero_cheque::text
		  ) AS concepto_anulacion


FROM cstd03_cheque_partidas a, cstd03_cheque_cuerpo b


WHERE a.cod_presi = b.cod_presi AND
      a.cod_entidad = b.cod_entidad AND
      a.cod_tipo_inst = b.cod_tipo_inst AND
      a.cod_inst = b.cod_inst AND
      a.cod_dep = b.cod_dep AND
      a.ano_movimiento = b.ano_movimiento AND
      a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
      a.cod_sucursal = b.cod_sucursal AND
      a.cuenta_bancaria::text = b.cuenta_bancaria::text AND
      a.numero_cheque = b.numero_cheque;

ALTER TABLE v_restaurar_pagados OWNER TO sisap;

