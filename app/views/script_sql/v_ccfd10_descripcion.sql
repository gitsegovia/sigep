
--DROP VIEW v_ccfd10_descripcion;

CREATE OR REPLACE VIEW v_ccfd10_descripcion AS


 SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_asiento,
  a.mes_asiento,
  a.dia_asiento,
  a.numero_asiento,
  a.instancia_asiento,
  a.concepto,
  a.tipo_documento,
  a.numero_documento,
  a.fecha_documento,
  b.numero_linea,
  b.debito_credito,
  b.cod_tipo_cuenta,
  b.cod_cuenta,
  b.cod_subcuenta,
  b.cod_division,
  b.cod_subdivision,
  b.monto

FROM   ccfd10_descripcion a, ccfd10_detalles b


where  b.cod_presi            =  a.cod_presi                and
	   b.cod_entidad          =  a.cod_entidad              and
	   b.cod_tipo_inst        =  a.cod_tipo_inst            and
	   b.cod_inst             =  a.cod_inst                 and
	   b.cod_dep              =  a.cod_dep                  and
	   b.ano_asiento          =  a.ano_asiento              and
	   b.mes_asiento          =  a.mes_asiento              and
	   b.dia_asiento          =  a.cod_dep                  and
	   b.numero_asiento       =  a.dia_asiento


ORDER  BY

       a.cod_presi,
	   a.cod_entidad,
	   a.cod_tipo_inst,
	   a.cod_inst,
	   a.cod_dep,
	   a.ano_asiento,
	   a.mes_asiento,
	   a.dia_asiento,
	   a.numero_asiento;



ALTER TABLE v_ccfd10_descripcion OWNER TO sisap;
