-- View: cscd05_consumo_productos

-- DROP VIEW cscd05_consumo_productos;

CREATE OR REPLACE VIEW cscd05_consumo_productos AS

SELECT
		ne.cod_presi,
		ne.cod_entidad,
		ne.cod_tipo_inst,
		ne.cod_inst,
		ne.cod_dep,
		ne.ano_nota_entrega,
		sc.cod_dir_superior,
		sc.cod_coordinacion,
		sc.cod_secretaria,
		sc.cod_direccion,
		ne.codigo_prod_serv,

 substr(upper(( SELECT c.cod_snc::text AS denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv)), 1, 3) as cod_grupo_3,
 substr(upper(( SELECT c.cod_snc::text AS denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv)), 1, 5) as cod_grupo_5,

		upper(( SELECT c.denominacion::text AS denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv)) AS denominacion,
		upper((( SELECT c.cod_snc FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv))::text) AS cod_snc,
		sum(ne.cantidad)::numeric(22,6) AS cantidad_promedio,
		(sum(ne.cantidad * ne.precio_unitario) / sum(ne.cantidad))::numeric(26,3) AS precio_promedio,
		sum(ne.cantidad * ne.precio_unitario)::numeric(26,2) AS total_consumo,
		(SELECT um.expresion  FROM cscd01_unidad_medida um WHERE um.cod_medida = (( SELECT c.cod_medida FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv))) AS expresion


FROM cscd05_ordencompra_nota_entrega_cuerpo ne, cscd05_ordencompra_nota_entrega_encabezado nee, cscd01_catalogo c, cscd02_solicitud_encabezado sc, cscd04_ordencompra_encabezado oc, cscd03_cotizacion_encabezado co, cscd01_unidad_medida u
WHERE c.codigo_prod_serv = ne.codigo_prod_serv AND ne.cod_presi = sc.cod_presi AND ne.cod_entidad = sc.cod_entidad AND ne.cod_tipo_inst = sc.cod_tipo_inst AND ne.cod_inst = sc.cod_inst AND ne.cod_dep = sc.cod_dep AND ne.cod_presi = oc.cod_presi AND ne.cod_entidad = oc.cod_entidad AND ne.cod_tipo_inst = oc.cod_tipo_inst AND ne.cod_inst = oc.cod_inst AND ne.cod_dep = oc.cod_dep AND ne.cod_presi = co.cod_presi AND ne.cod_entidad = co.cod_entidad AND ne.cod_tipo_inst = co.cod_tipo_inst AND ne.cod_inst = co.cod_inst AND ne.cod_dep = co.cod_dep AND ne.cod_presi = nee.cod_presi AND ne.cod_entidad = nee.cod_entidad AND ne.cod_tipo_inst = nee.cod_tipo_inst AND ne.cod_inst = nee.cod_inst AND ne.cod_dep = nee.cod_dep AND nee.cod_presi = sc.cod_presi AND nee.cod_entidad = sc.cod_entidad AND nee.cod_tipo_inst = sc.cod_tipo_inst AND nee.cod_inst = sc.cod_inst AND nee.cod_dep = sc.cod_dep AND nee.cod_presi = oc.cod_presi AND nee.cod_entidad = oc.cod_entidad AND nee.cod_tipo_inst = oc.cod_tipo_inst AND nee.cod_inst = oc.cod_inst AND nee.cod_dep = oc.cod_dep AND nee.cod_presi = co.cod_presi AND nee.cod_entidad = co.cod_entidad AND nee.cod_tipo_inst = co.cod_tipo_inst AND nee.cod_inst = co.cod_inst AND nee.cod_dep = co.cod_dep AND co.cod_presi = oc.cod_presi AND co.cod_entidad = oc.cod_entidad AND co.cod_tipo_inst = oc.cod_tipo_inst AND co.cod_inst = oc.cod_inst AND co.cod_dep = oc.cod_dep AND sc.cod_presi = oc.cod_presi AND sc.cod_entidad = oc.cod_entidad AND sc.cod_tipo_inst = oc.cod_tipo_inst AND sc.cod_inst = oc.cod_inst AND sc.cod_dep = oc.cod_dep AND sc.cod_presi = co.cod_presi AND sc.cod_entidad = co.cod_entidad AND sc.cod_tipo_inst = co.cod_tipo_inst AND sc.cod_inst = co.cod_inst AND sc.cod_dep = co.cod_dep AND nee.numero_orden_compra = oc.numero_orden_compra AND oc.numero_cotizacion::text = co.numero_cotizacion::text AND oc.rif::text = co.rif::text AND co.numero_solicitud = sc.numero_solicitud AND oc.condicion_actividad = 1 AND nee.rif::text = oc.rif::text AND nee.ano_nota_entrega = ne.ano_nota_entrega AND sc.ano_solicitud = ne.ano_nota_entrega AND co.ano_cotizacion = ne.ano_nota_entrega AND oc.ano_orden_compra = ne.ano_nota_entrega AND nee.rif::text = ne.rif::text AND nee.numero_nota_entrega::text = ne.numero_nota_entrega::text AND u.cod_medida = c.cod_medida
GROUP BY ne.cod_presi, ne.cod_entidad, ne.cod_tipo_inst, ne.cod_inst, ne.cod_dep, ne.codigo_prod_serv, ne.ano_nota_entrega, c.denominacion, sc.cod_dir_superior, sc.cod_coordinacion, sc.cod_secretaria, sc.cod_direccion, c.cod_snc, u.expresion
ORDER BY ne.cod_presi, ne.cod_entidad, ne.cod_tipo_inst, ne.cod_inst, ne.cod_dep, ne.codigo_prod_serv;

ALTER TABLE cscd05_consumo_productos OWNER TO sisap;


