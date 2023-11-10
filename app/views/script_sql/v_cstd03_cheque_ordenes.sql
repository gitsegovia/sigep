


CREATE OR REPLACE VIEW v_cstd03_cheque_ordenes AS



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
		  a.numero_cheque,
		  ( select x.fecha_orden_pago from cepd03_ordenpago_cuerpo x  where    x.cod_presi            =  a.cod_presi                and
																			   x.cod_entidad          =  a.cod_entidad              and
																			   x.cod_tipo_inst        =  a.cod_tipo_inst            and
																			   x.cod_inst             =  a.cod_inst                 and
																			   x.cod_dep              =  a.cod_dep                  and
																			   x.ano_orden_pago       =  a.ano_orden_pago           and
																			   x.numero_orden_pago    =  a.numero_orden_pago
		  ) as fecha_orden_pago


FROM

          cstd03_cheque_ordenes  a;


ALTER TABLE v_cstd03_cheque_ordenes OWNER TO sisap;


