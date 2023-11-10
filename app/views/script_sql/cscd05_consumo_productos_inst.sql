-- View: cscd05_consumo_productos_inst

-- DROP VIEW cscd05_consumo_productos_inst;

CREATE OR REPLACE VIEW cscd05_consumo_productos_inst AS

SELECT
		ne.cod_presi,
		ne.cod_entidad,
		ne.cod_tipo_inst,
		ne.cod_inst,
		ne.ano_nota_entrega,
		ne.codigo_prod_serv,

 substr(upper(( SELECT c.cod_snc::text AS denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv)), 1, 3) as cod_grupo_3,
 substr(upper(( SELECT c.cod_snc::text AS denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv)), 1, 5) as cod_grupo_5,

		upper(( SELECT c.denominacion::text AS denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv)) AS denominacion,
		upper((( SELECT c.cod_snc FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv))::text) AS cod_snc,
		sum(ne.cantidad)::numeric(22,6) AS cantidad_promedio,
		(sum(ne.cantidad * ne.precio_unitario) / sum(ne.cantidad))::numeric(26,3) AS precio_promedio,
		sum(ne.cantidad * ne.precio_unitario)::numeric(22,2) AS total_consumo,
		( SELECT um.expresion FROM cscd01_unidad_medida um WHERE um.cod_medida = (( SELECT c.cod_medida FROM cscd01_catalogo c WHERE c.codigo_prod_serv = ne.codigo_prod_serv))) AS expresion

FROM cscd05_ordencompra_nota_entrega_cuerpo ne
GROUP BY ne.cod_presi, ne.cod_entidad, ne.cod_tipo_inst, ne.cod_inst, ne.ano_nota_entrega, ne.codigo_prod_serv
ORDER BY ne.cod_presi, ne.cod_entidad, ne.cod_tipo_inst, ne.cod_inst, ne.ano_nota_entrega, ne.codigo_prod_serv;

ALTER TABLE cscd05_consumo_productos_inst OWNER TO sisap;


