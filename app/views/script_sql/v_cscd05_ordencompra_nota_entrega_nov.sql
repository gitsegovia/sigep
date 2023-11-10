--DROP VIEW v_cscd05_ordencompra_nota_entrega;
CREATE VIEW v_cscd05_ordencompra_nota_entrega AS
SELECT
	a.cod_presi ,
	a.cod_entidad ,
	a.cod_tipo_inst ,
	a.cod_inst ,
	a.cod_dep ,
	a.rif ,
	(SELECT b.denominacion FROM cpcd02 b WHERE a.rif = b.rif) AS deno_rif,
	(SELECT b.direccion_comercial FROM cpcd02 b WHERE a.rif = b.rif) AS direccion_rif,
	a.ano_nota_entrega ,
	a.numero_nota_entrega ,
	a.ano_orden_compra ,
	(SELECT b.fecha_orden_compra FROM cscd04_ordencompra_encabezado b WHERE a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.numero_orden_compra = b.numero_orden_compra and a.ano_orden_compra = b.ano_orden_compra) AS fecha_orden_compra,
	a.numero_orden_compra ,
	(SELECT b.tipo_orden FROM cscd04_ordencompra_encabezado b WHERE a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.numero_orden_compra = b.numero_orden_compra and a.ano_orden_compra = b.ano_orden_compra) AS tipo_orden,
	(SELECT b.ano_cotizacion FROM cscd03_cotizacion_encabezado b WHERE a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.numero_orden_compra = b.numero_ordencompra and a.ano_orden_compra = b.ano_ordencompra and a.rif = b.rif) AS ano_cotizacion,
	(SELECT b.numero_cotizacion FROM cscd03_cotizacion_encabezado b WHERE a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.numero_orden_compra = b.numero_ordencompra and a.ano_orden_compra = b.ano_ordencompra and a.rif = b.rif) AS numero_cotizacion,
	a.observaciones ,
	a.entrega_completa ,
	a.fecha_nota_entrega
FROM
	cscd05_ordencompra_nota_entrega_encabezado a;

ALTER TABLE v_cscd05_ordencompra_nota_entrega OWNER TO sisap;