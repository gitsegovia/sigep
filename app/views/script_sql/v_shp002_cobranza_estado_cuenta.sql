

--DROP VIEW v_shp002_cobranza_estado_cuenta_1;

CREATE OR REPLACE VIEW v_shp002_cobranza_estado_cuenta_1 AS

SELECT


  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (b.ano) as ano_cobranza,
   b.cobranza_pendiente_acumulada,
  (0) as cobranza_realizada_acumulada


from shd002_cobradores a, shd002_cobranza_pendiente b

where b.cod_presi       =  a.cod_presi      and
      b.cod_entidad     =  a.cod_entidad    and
      b.cod_tipo_inst   =  a.cod_tipo_inst  and
      b.cod_inst        =  a.cod_inst       and
      b.cod_dep         =  a.cod_dep        and
      b.rif_ci          =  a.rif_ci


UNION



SELECT


  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (c.ano) as ano_cobranza,
  (0) as cobranza_pendiente_acumulada,
  (c.cobranza_acumulada) as cobranza_realizada_acumulada


from shd002_cobradores a, shd002_cobranza_realizada c

where c.cod_presi       =  a.cod_presi       and
      c.cod_entidad     =  a.cod_entidad    and
      c.cod_tipo_inst   =  a.cod_tipo_inst  and
      c.cod_inst        =  a.cod_inst       and
      c.cod_dep         =  a.cod_dep        and
      c.rif_ci          =  a.rif_ci;


ALTER TABLE v_shp002_cobranza_estado_cuenta_1 OWNER TO sisap;







--DROP VIEW v_shp002_cobranza_estado_cuenta_2;

CREATE OR REPLACE VIEW v_shp002_cobranza_estado_cuenta_2 AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  a.ano_cobranza

from v_shp002_cobranza_estado_cuenta_1 a

GROUP BY  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.rif_ci,
		  a.personalidad,
		  a.nombre_razon,
		  a.fecha_ingreso,
		  a.recurso_cobro,
		  a.condicion_actividad,
		  a.ano_cobranza

ORDER BY  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.rif_ci,
		  a.ano_cobranza ASC;

ALTER TABLE v_shp002_cobranza_estado_cuenta_2 OWNER TO sisap;