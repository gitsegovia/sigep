CREATE OR REPLACE VIEW v_cnmd09_frecuencia AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, a.cod_frecuencia, ( SELECT x.denominacion
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS denominacion
   FROM cnmd09_frecuencia a;

ALTER TABLE v_cnmd09_frecuencia OWNER TO sisap;

