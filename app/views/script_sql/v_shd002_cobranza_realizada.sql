
--DROP VIEW v_shd002_cobranza_realizada;

CREATE OR REPLACE VIEW v_shd002_cobranza_realizada AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  b.personalidad,
  b.nombre_razon,
  b.fecha_ingreso,
  b.recurso_cobro,
  b.condicion_actividad,
  a.ano,
  a.cobranza_acumulada,
  a.enero,
  a.febrero,
  a.marzo,
  a.abril,
  a.mayo,
  a.junio,
  a.julio,
  a.agosto,
  a.septiembre,
  a.octubre,
  a.noviembre,
  a.diciembre

from
    shd002_cobranza_realizada a, shd002_cobradores b

where b.cod_presi       =  a.cod_presi       and
      b.cod_entidad     =  a.cod_entidad    and
      b.cod_tipo_inst   =  a.cod_tipo_inst  and
      b.cod_inst        =  a.cod_inst       and
      b.cod_dep         =  a.cod_dep        and
      b.rif_ci          =  a.rif_ci;

ALTER TABLE v_shd002_cobranza_realizada OWNER TO sisap;