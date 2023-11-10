DROP VIEW cscd05_cpcd02_suministro;

CREATE OR REPLACE VIEW cscd05_cpcd02_suministro AS


SELECT
				oc.cod_presi,
				oc.cod_entidad,
				oc.cod_tipo_inst,
				oc.cod_inst,
				oc.cod_dep,
				(SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = oc.cod_tipo_inst and b.cod_institucion  =  oc.cod_inst and b.cod_dependencia   =  oc.cod_dep) as denominacion_dep,
				oc.rif,
				upper(p.denominacion::text) AS denominacion_comercial,
				p.codigo_area_empresa,
				p.telefonos,
				p.telefonos_fijos_representante,
				p.telefonos_moviles_representante,
				oc.ano_orden_compra,
				oc.numero_orden_compra,
				oc.fecha_orden_compra,
				ne.ano_nota_entrega,
				ne.numero_nota_entrega,
				ne.fecha_nota_entrega,
				nec.codigo_prod_serv,
				upper(ca.denominacion::text) AS producto,
				nec.precio_unitario,
				nec.cantidad,
				ca.cod_snc,

				( SELECT aa.expresion::text AS expresion
				  FROM cscd01_unidad_medida aa
				  WHERE aa.cod_medida = ca.cod_medida) AS expresion_medida_producto,

				( SELECT bb.denominacion::text AS denominacion
				  FROM cscd01_unidad_medida bb
				  WHERE bb.cod_medida = ca.cod_medida) AS denominacion_medida_producto



FROM
      cscd04_ordencompra_encabezado oc,
      cpcd02 p,
      cscd05_ordencompra_nota_entrega_encabezado ne,
      cscd05_ordencompra_nota_entrega_cuerpo nec,
      cscd01_catalogo ca



WHERE
      oc.rif::text = p.rif::text AND
      ca.codigo_prod_serv = nec.codigo_prod_serv AND
      oc.cod_presi = ne.cod_presi AND
      oc.cod_entidad = ne.cod_entidad AND
      oc.cod_tipo_inst = ne.cod_tipo_inst AND
      oc.cod_inst = ne.cod_inst AND
      oc.cod_dep = ne.cod_dep AND
      oc.rif::text = ne.rif::text AND
      oc.ano_orden_compra = ne.ano_orden_compra AND
      oc.numero_orden_compra = ne.numero_orden_compra AND
      nec.cod_presi = ne.cod_presi AND
      nec.cod_entidad = ne.cod_entidad AND
      nec.cod_tipo_inst = ne.cod_tipo_inst AND
      nec.cod_inst = ne.cod_inst AND
      nec.cod_dep = ne.cod_dep AND
      nec.rif::text = ne.rif::text AND
      ne.ano_nota_entrega = nec.ano_nota_entrega AND
      ne.numero_nota_entrega::text = nec.numero_nota_entrega::text





ORDER BY oc.cod_presi, oc.cod_entidad, oc.cod_tipo_inst, oc.cod_inst, oc.cod_dep, oc.rif, oc.ano_orden_compra, oc.numero_orden_compra;

ALTER TABLE cscd05_cpcd02_suministro OWNER TO sisap;
