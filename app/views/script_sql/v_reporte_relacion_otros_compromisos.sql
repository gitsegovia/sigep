

CREATE OR REPLACE VIEW v_reporte_relacion_otros_compromisos AS 


select
	a.cod_presi,
	a.cod_entidad,
	a.cod_tipo_inst,
	a.cod_inst,
	a.cod_dep,
	a.ano_documento,
	a.numero_documento,
	a.condicion_actividad,
	a.cod_tipo_compromiso,
	(select d.denominacion from cepd01_tipo_compromiso d where d.cod_tipo_compromiso=a.cod_tipo_compromiso)::varchar(50) as deno_compromiso,
	a.fecha_documento,
	a.rif,
	a.cedula_identidad,
	a.monto,
	a.beneficiario,
	(select c.numero_orden_pago from cepd03_ordenpago_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_orden_pago=a.ano_orden_pago and c.numero_orden_pago=a.numero_orden_pago)::int4 as numero_orden_pago,
	(select c.fecha_orden_pago  from cepd03_ordenpago_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_orden_pago=a.ano_orden_pago and c.numero_orden_pago=a.numero_orden_pago)::date as fecha_orden_pago,
	(select c.numero_cheque     from cepd03_ordenpago_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_orden_pago=a.ano_orden_pago and c.numero_orden_pago=a.numero_orden_pago)::int4 as numero_cheque,
	(select c.fecha_cheque      from cepd03_ordenpago_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_orden_pago=a.ano_orden_pago and c.numero_orden_pago=a.numero_orden_pago)::date as fecha_cheque

from cepd01_compromiso_cuerpo a;



ALTER TABLE v_reporte_relacion_otros_compromisos OWNER TO sisap;
