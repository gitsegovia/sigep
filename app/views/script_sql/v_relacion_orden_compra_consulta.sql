


CREATE OR REPLACE VIEW v_relacion_orden_compra_consulta AS 

   select
	a.cod_presi,
	a.cod_entidad,
	a.cod_tipo_inst,
	a.cod_inst,
	a.cod_dep,
	a.ano_orden_compra,
	a.numero_orden_compra,
	a.condicion_actividad,
	a.fecha_orden_compra,
	(select b.rif from cpcd02 b where b.rif=a.rif)::varchar(20) as rif,
	(select b.denominacion from cpcd02 b where b.rif=a.rif)::varchar(100) as beneficiario,
	a.monto_orden

	from cscd04_ordencompra_encabezado a;


ALTER TABLE v_relacion_orden_compra_consulta OWNER TO sisap;
