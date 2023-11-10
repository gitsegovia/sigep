CREATE OR REPLACE VIEW v_cnmd03_transacciones AS
 SELECT a.cod_tipo_transaccion, a.cod_transaccion, a.denominacion, a.denominacion_pago, a.tipo_asignacion, a.uso_transaccion, a.tipo_actualizacion, a.cod_tipo_transaccion_padre, a.cod_transaccion_padre, ( SELECT x.denominacion
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion_padre AND x.cod_transaccion = a.cod_transaccion_padre) AS denominacion_padre
   FROM cnmd03_transacciones a;

ALTER TABLE v_cnmd03_transacciones OWNER TO sisap;


CREATE OR REPLACE VIEW v_cnmd03_partidas AS
 SELECT a.cod_tipo_transaccion, a.cod_transaccion, ( SELECT x.denominacion
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS deno_transaccion, a.clasificacion_personal, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, ( SELECT b.denominacion
           FROM cfpd01_ano_5_sub_espec b
          WHERE b.cod_grupo = substr(a.cod_partida::text, 1, 1)::integer AND b.cod_partida = substr(a.cod_partida::text, 2, 3)::integer AND b.cod_generica = a.cod_generica AND b.cod_especifica = a.cod_especifica AND b.cod_sub_espec = a.cod_sub_espec
          ORDER BY b.ejercicio DESC
         LIMIT 1) AS deno_sub_espec, ( SELECT b.denominacion
           FROM cfpd01_ano_6_auxiliar b
          WHERE b.cod_grupo = substr(a.cod_partida::text, 1, 1)::integer AND b.cod_partida = substr(a.cod_partida::text, 2, 3)::integer AND b.cod_generica = a.cod_generica AND b.cod_especifica = a.cod_especifica AND b.cod_sub_espec = a.cod_sub_espec AND b.cod_auxiliar = a.cod_auxiliar
          ORDER BY b.ejercicio DESC
         LIMIT 1) AS deno_auxiliar
   FROM cnmd03_partidas a;

ALTER TABLE v_cnmd03_partidas OWNER TO sisap;

CREATE OR REPLACE VIEW depositos_bancarios_1 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha, a.cedula_identidad, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, ( SELECT sum(x.monto_cuota) AS sum
           FROM cnmd07_transacciones_actuales x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_ficha = a.cod_ficha AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = 1) AS asignaciones, ( SELECT sum(x.monto_cuota) AS sum
           FROM cnmd07_transacciones_actuales x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_ficha = a.cod_ficha AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = 2) AS deducciones
   FROM cnmd06_fichas a
  WHERE a.condicion_actividad = 1
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina;

ALTER TABLE depositos_bancarios_1 OWNER TO sisap;

CREATE OR REPLACE VIEW depositos_bancarios_final AS
 SELECT depositos_bancarios_1.cod_presi, depositos_bancarios_1.cod_entidad, depositos_bancarios_1.cod_tipo_inst, depositos_bancarios_1.cod_inst, depositos_bancarios_1.cod_dep, depositos_bancarios_1.cod_tipo_nomina, depositos_bancarios_1.cedula_identidad, (((((a.primer_apellido::text || ' '::text) || a.segundo_apellido::text) || ' '::text) || a.primer_nombre::text) || ' '::text) || a.segundo_nombre::text AS apellidos_nombres, depositos_bancarios_1.cod_entidad_bancaria, depositos_bancarios_1.cod_sucursal, depositos_bancarios_1.cuenta_bancaria, sum(null_cero(depositos_bancarios_1.asignaciones)) AS asignaciones, sum(null_cero(depositos_bancarios_1.deducciones)) AS deducciones
   FROM depositos_bancarios_1, cnmd06_datos_personales a
  WHERE a.cedula_identidad = depositos_bancarios_1.cedula_identidad
  GROUP BY depositos_bancarios_1.cod_presi, depositos_bancarios_1.cod_entidad, depositos_bancarios_1.cod_tipo_inst, depositos_bancarios_1.cod_inst, depositos_bancarios_1.cod_dep, depositos_bancarios_1.cod_tipo_nomina, depositos_bancarios_1.cedula_identidad, depositos_bancarios_1.cod_entidad_bancaria, depositos_bancarios_1.cod_sucursal, depositos_bancarios_1.cuenta_bancaria, (((((a.primer_apellido::text || ' '::text) || a.segundo_apellido::text) || ' '::text) || a.primer_nombre::text) || ' '::text) || a.segundo_nombre::text
  ORDER BY depositos_bancarios_1.cod_presi, depositos_bancarios_1.cod_entidad, depositos_bancarios_1.cod_tipo_inst, depositos_bancarios_1.cod_inst, depositos_bancarios_1.cod_dep, depositos_bancarios_1.cod_tipo_nomina, depositos_bancarios_1.cedula_identidad, depositos_bancarios_1.cod_entidad_bancaria, depositos_bancarios_1.cod_sucursal, depositos_bancarios_1.cuenta_bancaria;

ALTER TABLE depositos_bancarios_final OWNER TO sisap;
COMMENT ON VIEW depositos_bancarios_final IS 'vista para generar reportes de depostios bancarios de nomina y generar archivo de texto';

