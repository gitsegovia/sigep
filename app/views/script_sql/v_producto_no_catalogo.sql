




 --DROP VIEW v_producto_no_catalogo;

CREATE OR REPLACE VIEW v_producto_no_catalogo AS

select

	a.cod_dep,
	a.ano_solicitud,
	a.numero_solicitud,
	a.codigo_prod_serv,
	a.descripcion,
	(select COUNT(*) from cscd01_catalogo b where b.codigo_prod_serv=a.codigo_prod_serv) as contar


from

    cscd02_solicitud_cuerpo a;


ALTER TABLE v_producto_no_catalogo OWNER TO sisap;










 --DROP VIEW v_producto_no_catalogo_cotizacion;


CREATE OR REPLACE VIEW v_producto_no_catalogo_cotizacion AS

select

	a.cod_dep,
	a.ano_cotizacion,
	a.numero_cotizacion,
	a.codigo_prod_serv,
	a.descripcion,
	(select COUNT(*) from cscd01_catalogo b where b.codigo_prod_serv=a.codigo_prod_serv) as contar


from

    cscd03_cotizacion_cuerpo a ;


ALTER TABLE v_producto_no_catalogo_cotizacion OWNER TO sisap;

















