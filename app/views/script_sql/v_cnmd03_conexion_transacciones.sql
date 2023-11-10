

DROP VIEW v_cnmd03_conexion_transacciones;

CREATE OR REPLACE VIEW v_cnmd03_conexion_transacciones  as


  SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.cod_tipo_nomina,
          ( SELECT b.denominacion FROM cugd02_dependencias b  WHERE b.cod_tipo_institucion = a.cod_tipo_inst AND b.cod_institucion = a.cod_inst AND b.cod_dependencia = a.cod_dep) AS denominacion_dep,
          ( SELECT d.denominacion FROM cnmd01 d  WHERE d.cod_presi = a.cod_presi AND d.cod_entidad = a.cod_entidad AND d.cod_tipo_inst = a.cod_tipo_inst AND d.cod_inst = a.cod_inst AND d.cod_dep = a.cod_dep AND d.cod_tipo_nomina = a.cod_tipo_nomina) AS denominacion_nomina,
          ( SELECT c.denominacion FROM cnmd03_transacciones c WHERE c.cod_tipo_transaccion=a.cod_tipo_transaccion and c.cod_transaccion=a.cod_transaccion ) as denominacion_transaccion,
	  a.cod_tipo_transaccion,
	  a.cod_transaccion,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_sub_prog,
	  a.cod_proyecto,
	  a.cod_activ_obra,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica,
	  a.cod_sub_espec,
	  a.cod_auxiliar


FROM

     cnmd03_conexion_transacciones a

GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.cod_tipo_nomina,
      denominacion_dep,
      denominacion_nomina,
      denominacion_transaccion,
      a.cod_tipo_transaccion,
	  a.cod_transaccion,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_sub_prog,
	  a.cod_proyecto,
	  a.cod_activ_obra,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica,
	  a.cod_sub_espec,
	  a.cod_auxiliar

  ORDER BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.cod_tipo_nomina,
      denominacion_dep,
      denominacion_nomina,
      denominacion_transaccion,
      a.cod_tipo_transaccion,
	  a.cod_transaccion,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_sub_prog,
	  a.cod_proyecto,
	  a.cod_activ_obra,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica,
	  a.cod_sub_espec,
	  a.cod_auxiliar ASC;


ALTER TABLE v_cnmd03_conexion_transacciones OWNER TO sisap;

