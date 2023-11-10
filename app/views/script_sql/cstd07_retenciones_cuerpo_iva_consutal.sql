
DROP VIEW cstd07_retenciones_cuerpo_iva_consutal;



CREATE OR REPLACE VIEW cstd07_retenciones_cuerpo_iva_consutal AS

SELECT
          a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano_orden_pago,
	  a.clase_orden,
	  a.numero_orden_pago,
	  a.monto,
	  a.fecha_proceso_registro,
	  a.status,
	  a.ano_movimiento,
	  a.cod_entidad_bancaria,
	  a.cod_sucursal,
	  a.cuenta_bancaria,
	  a.numero_cheque,
	  a.fecha_proceso_anulacion,

          (select b.beneficiario from cepd03_ordenpago_cuerpo  b where
                  b.cod_presi         =   a.cod_presi         and
		  b.cod_entidad       =   a.cod_entidad       and
		  b.cod_tipo_inst     =   a.cod_tipo_inst     and
		  b.cod_inst          =   a.cod_inst          and
		  b.cod_dep           =   a.cod_dep           and
		  b.ano_orden_pago    =   a.ano_orden_pago    and
		  b.numero_orden_pago =   a.numero_orden_pago
           ) as beneficiario,


       (select b.ano_movimiento from cepd03_ordenpago_cuerpo  b where
              b.cod_presi         =   a.cod_presi         and
			  b.cod_entidad       =   a.cod_entidad       and
			  b.cod_tipo_inst     =   a.cod_tipo_inst     and
			  b.cod_inst          =   a.cod_inst          and
			  b.cod_dep           =   a.cod_dep           and
			  b.ano_orden_pago    =   a.ano_orden_pago    and
			  b.numero_orden_pago =   a.numero_orden_pago
       ) as ano_movimiento_op,


       (select b.cod_entidad_bancaria from cepd03_ordenpago_cuerpo  b where
              b.cod_presi         =   a.cod_presi         and
			  b.cod_entidad       =   a.cod_entidad       and
			  b.cod_tipo_inst     =   a.cod_tipo_inst     and
			  b.cod_inst          =   a.cod_inst          and
			  b.cod_dep           =   a.cod_dep           and
			  b.ano_orden_pago    =   a.ano_orden_pago    and
			  b.numero_orden_pago =   a.numero_orden_pago
       ) as cod_entidad_bancaria_op,


       (select b.cod_sucursal from cepd03_ordenpago_cuerpo  b where
              b.cod_presi         =   a.cod_presi         and
			  b.cod_entidad       =   a.cod_entidad       and
			  b.cod_tipo_inst     =   a.cod_tipo_inst     and
			  b.cod_inst          =   a.cod_inst          and
			  b.cod_dep           =   a.cod_dep           and
			  b.ano_orden_pago    =   a.ano_orden_pago    and
			  b.numero_orden_pago =   a.numero_orden_pago
       ) as cod_sucursal_op,


       (select b.cuenta_bancaria from cepd03_ordenpago_cuerpo  b where
              b.cod_presi         =   a.cod_presi         and
			  b.cod_entidad       =   a.cod_entidad       and
			  b.cod_tipo_inst     =   a.cod_tipo_inst     and
			  b.cod_inst          =   a.cod_inst          and
			  b.cod_dep           =   a.cod_dep           and
			  b.ano_orden_pago    =   a.ano_orden_pago    and
			  b.numero_orden_pago =   a.numero_orden_pago
       ) as cuenta_bancaria_op,


       (select b.numero_cheque from cepd03_ordenpago_cuerpo  b where
              b.cod_presi         =   a.cod_presi         and
			  b.cod_entidad       =   a.cod_entidad       and
			  b.cod_tipo_inst     =   a.cod_tipo_inst     and
			  b.cod_inst          =   a.cod_inst          and
			  b.cod_dep           =   a.cod_dep           and
			  b.ano_orden_pago    =   a.ano_orden_pago    and
			  b.numero_orden_pago =   a.numero_orden_pago
       ) as numero_cheque_op


FROM

       cstd07_retenciones_cuerpo_iva a;


ALTER TABLE cstd07_retenciones_cuerpo_iva_consutal OWNER TO sisap;

