-- View: v_cnmd07_transacciones_actuales_deno

-- DROP VIEW v_cnmd07_transacciones_actuales_deno;

CREATE OR REPLACE VIEW v_cnmd07_transacciones_actuales_deno AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha, a.cod_tipo_transaccion, a.cod_transaccion, a.fecha_transaccion, a.monto_original, a.numero_cuotas_descontar, a.numero_cuotas_cancelar, a.numero_cuotas_canceladas, a.monto_cuota, a.saldo, a.marca_fin_descuento, a.fecha_proceso, a.username, upper((( SELECT c.denominacion
           FROM cnmd01 c
          WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina))::text) AS denominacion_tipo_nomina, ( SELECT x.denominacion
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS denominacion_transaccion, ( SELECT x.tipo_actualizacion
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS tipo_actualizacion, ( SELECT x.uso_transaccion
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS uso_transaccion, ( SELECT x.cod_tipo_transaccion_padre
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS cod_tipo_transaccion_padre, ( SELECT x.cod_transaccion_padre
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS cod_transaccion_padre
   FROM cnmd07_transacciones_actuales a;

ALTER TABLE v_cnmd07_transacciones_actuales_deno OWNER TO sisap;

-- View: v_cnmd07_transacciones_suspendidas

-- DROP VIEW v_cnmd07_transacciones_suspendidas;

CREATE OR REPLACE VIEW v_cnmd07_transacciones_suspendidas AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, c.cod_puesto, c.sueldo_basico, ( SELECT devolver_denominacion_puesto(( SELECT xy.clasificacion_personal
                   FROM cnmd01 xy
                  WHERE xy.cod_presi = a.cod_presi AND xy.cod_entidad = a.cod_entidad AND xy.cod_tipo_inst = a.cod_tipo_inst AND xy.cod_inst = a.cod_inst AND xy.cod_dep = a.cod_dep AND xy.cod_tipo_nomina = a.cod_tipo_nomina), c.cod_puesto) AS devolver_denominacion_puesto) AS denominacion_puesto, a.cod_ficha, a.cod_tipo_transaccion, a.cod_transaccion, ( SELECT b.denominacion
           FROM cnmd03_transacciones b
          WHERE a.cod_tipo_transaccion = b.cod_tipo_transaccion AND a.cod_transaccion = b.cod_transaccion) AS deno_transaccion, a.fecha_transaccion, a.monto_original, a.numero_cuotas_descontar, a.numero_cuotas_cancelar, a.numero_cuotas_canceladas, a.monto_cuota, a.saldo, a.marca_fin_descuento, a.fecha_proceso, a.username
   FROM cnmd07_transacciones_suspendidas a, cnmd05 c
  WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_tipo_nomina = c.cod_tipo_nomina AND a.cod_cargo = c.cod_cargo;

ALTER TABLE v_cnmd07_transacciones_suspendidas OWNER TO sisap;

-- View: v_escenarios_frecuencia

-- DROP VIEW v_escenarios_frecuencia;

CREATE OR REPLACE VIEW v_escenarios_frecuencia AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, upper((( SELECT c.denominacion
           FROM cnmd01 c
          WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina))::text) AS denominacion_tipo_nomina, upper((( SELECT d.denominacion
           FROM cnmd03_transacciones d
          WHERE d.cod_tipo_transaccion = a.cod_tipo_transaccion AND d.cod_transaccion = a.cod_transaccion))::text) AS denominacion_transaccion, (( SELECT count(*) AS count
           FROM cnmd09_frecuencia b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_tipo_transaccion = a.cod_tipo_transaccion AND b.cod_transaccion = a.cod_transaccion))::integer AS frecuencia
   FROM v_todos_escenarios a;

ALTER TABLE v_escenarios_frecuencia OWNER TO sisap;
COMMENT ON VIEW v_escenarios_frecuencia IS 'vista que muestra si los codigos de los  escenarios que existen poseen la frecuencia de pago';

-- View: v_todos_escenarios

-- DROP VIEW v_todos_escenarios;

CREATE OR REPLACE VIEW v_todos_escenarios AS
((((((((((((((((((((((((((((( SELECT cnmd10_aportes_patronales.cod_presi, cnmd10_aportes_patronales.cod_entidad, cnmd10_aportes_patronales.cod_tipo_inst, cnmd10_aportes_patronales.cod_inst, cnmd10_aportes_patronales.cod_dep, cnmd10_aportes_patronales.cod_tipo_nomina, cnmd10_aportes_patronales.cod_tipo_transaccion, cnmd10_aportes_patronales.cod_transaccion
   FROM cnmd10_aportes_patronales
UNION
 SELECT cnmd10_comunes_52semanas_porcentaje_ded.cod_presi, cnmd10_comunes_52semanas_porcentaje_ded.cod_entidad, cnmd10_comunes_52semanas_porcentaje_ded.cod_tipo_inst, cnmd10_comunes_52semanas_porcentaje_ded.cod_inst, cnmd10_comunes_52semanas_porcentaje_ded.cod_dep, cnmd10_comunes_52semanas_porcentaje_ded.cod_tipo_nomina, cnmd10_comunes_52semanas_porcentaje_ded.cod_tipo_transaccion, cnmd10_comunes_52semanas_porcentaje_ded.cod_transaccion
   FROM cnmd10_comunes_52semanas_porcentaje_ded)
UNION
 SELECT cnmd10_comunes_asignacion_bolivares_sexo.cod_presi, cnmd10_comunes_asignacion_bolivares_sexo.cod_entidad, cnmd10_comunes_asignacion_bolivares_sexo.cod_tipo_inst, cnmd10_comunes_asignacion_bolivares_sexo.cod_inst, cnmd10_comunes_asignacion_bolivares_sexo.cod_dep, cnmd10_comunes_asignacion_bolivares_sexo.cod_tipo_nomina, cnmd10_comunes_asignacion_bolivares_sexo.cod_tipo_transaccion, cnmd10_comunes_asignacion_bolivares_sexo.cod_transaccion
   FROM cnmd10_comunes_asignacion_bolivares_sexo)
UNION
 SELECT cnmd10_comunes_asignacion_porcentaje_sexo.cod_presi, cnmd10_comunes_asignacion_porcentaje_sexo.cod_entidad, cnmd10_comunes_asignacion_porcentaje_sexo.cod_tipo_inst, cnmd10_comunes_asignacion_porcentaje_sexo.cod_inst, cnmd10_comunes_asignacion_porcentaje_sexo.cod_dep, cnmd10_comunes_asignacion_porcentaje_sexo.cod_tipo_nomina, cnmd10_comunes_asignacion_porcentaje_sexo.cod_tipo_transaccion, cnmd10_comunes_asignacion_porcentaje_sexo.cod_transaccion
   FROM cnmd10_comunes_asignacion_porcentaje_sexo)
UNION
 SELECT cnmd10_comunes_bolivares_asignacion.cod_presi, cnmd10_comunes_bolivares_asignacion.cod_entidad, cnmd10_comunes_bolivares_asignacion.cod_tipo_inst, cnmd10_comunes_bolivares_asignacion.cod_inst, cnmd10_comunes_bolivares_asignacion.cod_dep, cnmd10_comunes_bolivares_asignacion.cod_tipo_nomina, cnmd10_comunes_bolivares_asignacion.cod_tipo_transaccion, cnmd10_comunes_bolivares_asignacion.cod_transaccion
   FROM cnmd10_comunes_bolivares_asignacion)
UNION
 SELECT cnmd10_comunes_bolivares_deduccion.cod_presi, cnmd10_comunes_bolivares_deduccion.cod_entidad, cnmd10_comunes_bolivares_deduccion.cod_tipo_inst, cnmd10_comunes_bolivares_deduccion.cod_inst, cnmd10_comunes_bolivares_deduccion.cod_dep, cnmd10_comunes_bolivares_deduccion.cod_tipo_nomina, cnmd10_comunes_bolivares_deduccion.cod_tipo_transaccion, cnmd10_comunes_bolivares_deduccion.cod_transaccion
   FROM cnmd10_comunes_bolivares_deduccion)
UNION
 SELECT cnmd10_comunes_dia_asignacion.cod_presi, cnmd10_comunes_dia_asignacion.cod_entidad, cnmd10_comunes_dia_asignacion.cod_tipo_inst, cnmd10_comunes_dia_asignacion.cod_inst, cnmd10_comunes_dia_asignacion.cod_dep, cnmd10_comunes_dia_asignacion.cod_tipo_nomina, cnmd10_comunes_dia_asignacion.cod_tipo_transaccion, cnmd10_comunes_dia_asignacion.cod_transaccion
   FROM cnmd10_comunes_dia_asignacion)
UNION
 SELECT cnmd10_comunes_dia_deduccion.cod_presi, cnmd10_comunes_dia_deduccion.cod_entidad, cnmd10_comunes_dia_deduccion.cod_tipo_inst, cnmd10_comunes_dia_deduccion.cod_inst, cnmd10_comunes_dia_deduccion.cod_dep, cnmd10_comunes_dia_deduccion.cod_tipo_nomina, cnmd10_comunes_dia_deduccion.cod_tipo_transaccion, cnmd10_comunes_dia_deduccion.cod_transaccion
   FROM cnmd10_comunes_dia_deduccion)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_presi, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_entidad, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_inst, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_dep, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_tipo_nomina, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_tipo_transaccion, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_transaccion
   FROM cnmd10_comunes_escala_antiguedad_bolivares_asig)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_presi, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_entidad, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_inst, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_dep, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_tipo_nomina, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_tipo_transaccion, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_transaccion
   FROM cnmd10_comunes_escala_antiguedad_bolivares_ded)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_dias_asig.cod_presi, cnmd10_comunes_escala_antiguedad_dias_asig.cod_entidad, cnmd10_comunes_escala_antiguedad_dias_asig.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_dias_asig.cod_inst, cnmd10_comunes_escala_antiguedad_dias_asig.cod_dep, cnmd10_comunes_escala_antiguedad_dias_asig.cod_tipo_nomina, cnmd10_comunes_escala_antiguedad_dias_asig.cod_tipo_transaccion, cnmd10_comunes_escala_antiguedad_dias_asig.cod_transaccion
   FROM cnmd10_comunes_escala_antiguedad_dias_asig)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_dias_ded.cod_presi, cnmd10_comunes_escala_antiguedad_dias_ded.cod_entidad, cnmd10_comunes_escala_antiguedad_dias_ded.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_dias_ded.cod_inst, cnmd10_comunes_escala_antiguedad_dias_ded.cod_dep, cnmd10_comunes_escala_antiguedad_dias_ded.cod_tipo_nomina, cnmd10_comunes_escala_antiguedad_dias_ded.cod_tipo_transaccion, cnmd10_comunes_escala_antiguedad_dias_ded.cod_transaccion
   FROM cnmd10_comunes_escala_antiguedad_dias_ded)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_presi, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_entidad, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_inst, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_dep, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_tipo_nomina, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_tipo_transaccion, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_transaccion
   FROM cnmd10_comunes_escala_antiguedad_porcentaje_asig)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_presi, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_entidad, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_inst, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_dep, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_tipo_nomina, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_tipo_transaccion, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_transaccion
   FROM cnmd10_comunes_escala_antiguedad_porcentaje_ded)
UNION
 SELECT cnmd10_comunes_escala_mes_dia_asig.cod_presi, cnmd10_comunes_escala_mes_dia_asig.cod_entidad, cnmd10_comunes_escala_mes_dia_asig.cod_tipo_inst, cnmd10_comunes_escala_mes_dia_asig.cod_inst, cnmd10_comunes_escala_mes_dia_asig.cod_dep, cnmd10_comunes_escala_mes_dia_asig.cod_tipo_nomina, cnmd10_comunes_escala_mes_dia_asig.cod_tipo_transaccion, cnmd10_comunes_escala_mes_dia_asig.cod_transaccion
   FROM cnmd10_comunes_escala_mes_dia_asig)
UNION
 SELECT cnmd10_comunes_escala_sueldo_bolivares_asig.cod_presi, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_entidad, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_tipo_inst, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_inst, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_dep, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_tipo_nomina, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_tipo_transaccion, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_transaccion
   FROM cnmd10_comunes_escala_sueldo_bolivares_asig)
UNION
 SELECT cnmd10_comunes_escala_sueldo_bolivares_ded.cod_presi, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_entidad, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_tipo_inst, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_inst, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_dep, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_tipo_nomina, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_tipo_transaccion, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_transaccion
   FROM cnmd10_comunes_escala_sueldo_bolivares_ded)
UNION
 SELECT cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_presi, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_entidad, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_tipo_inst, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_inst, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_dep, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_tipo_nomina, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_tipo_transaccion, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_transaccion
   FROM cnmd10_comunes_escala_sueldo_porcentaje_asig)
UNION
 SELECT cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_presi, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_entidad, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_tipo_inst, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_inst, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_dep, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_tipo_nomina, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_tipo_transaccion, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_transaccion
   FROM cnmd10_comunes_escala_sueldo_porcentaje_ded)
UNION
 SELECT cnmd10_comunes_porcentaje_asignacion.cod_presi, cnmd10_comunes_porcentaje_asignacion.cod_entidad, cnmd10_comunes_porcentaje_asignacion.cod_tipo_inst, cnmd10_comunes_porcentaje_asignacion.cod_inst, cnmd10_comunes_porcentaje_asignacion.cod_dep, cnmd10_comunes_porcentaje_asignacion.cod_tipo_nomina, cnmd10_comunes_porcentaje_asignacion.cod_tipo_transaccion, cnmd10_comunes_porcentaje_asignacion.cod_transaccion
   FROM cnmd10_comunes_porcentaje_asignacion)
UNION
 SELECT cnmd10_comunes_porcentaje_deduccion.cod_presi, cnmd10_comunes_porcentaje_deduccion.cod_entidad, cnmd10_comunes_porcentaje_deduccion.cod_tipo_inst, cnmd10_comunes_porcentaje_deduccion.cod_inst, cnmd10_comunes_porcentaje_deduccion.cod_dep, cnmd10_comunes_porcentaje_deduccion.cod_tipo_nomina, cnmd10_comunes_porcentaje_deduccion.cod_tipo_transaccion, cnmd10_comunes_porcentaje_deduccion.cod_transaccion
   FROM cnmd10_comunes_porcentaje_deduccion)
UNION
 SELECT cnmd10_comunes_puestos_bolivares_asig.cod_presi, cnmd10_comunes_puestos_bolivares_asig.cod_entidad, cnmd10_comunes_puestos_bolivares_asig.cod_tipo_inst, cnmd10_comunes_puestos_bolivares_asig.cod_inst, cnmd10_comunes_puestos_bolivares_asig.cod_dep, cnmd10_comunes_puestos_bolivares_asig.cod_tipo_nomina, cnmd10_comunes_puestos_bolivares_asig.cod_tipo_transaccion, cnmd10_comunes_puestos_bolivares_asig.cod_transaccion
   FROM cnmd10_comunes_puestos_bolivares_asig)
UNION
 SELECT cnmd10_comunes_puestos_bolivares_ded.cod_presi, cnmd10_comunes_puestos_bolivares_ded.cod_entidad, cnmd10_comunes_puestos_bolivares_ded.cod_tipo_inst, cnmd10_comunes_puestos_bolivares_ded.cod_inst, cnmd10_comunes_puestos_bolivares_ded.cod_dep, cnmd10_comunes_puestos_bolivares_ded.cod_tipo_nomina, cnmd10_comunes_puestos_bolivares_ded.cod_tipo_transaccion, cnmd10_comunes_puestos_bolivares_ded.cod_transaccion
   FROM cnmd10_comunes_puestos_bolivares_ded)
UNION
 SELECT cnmd10_comunes_puestos_porcentaje_asig.cod_presi, cnmd10_comunes_puestos_porcentaje_asig.cod_entidad, cnmd10_comunes_puestos_porcentaje_asig.cod_tipo_inst, cnmd10_comunes_puestos_porcentaje_asig.cod_inst, cnmd10_comunes_puestos_porcentaje_asig.cod_dep, cnmd10_comunes_puestos_porcentaje_asig.cod_tipo_nomina, cnmd10_comunes_puestos_porcentaje_asig.cod_tipo_transaccion, cnmd10_comunes_puestos_porcentaje_asig.cod_transaccion
   FROM cnmd10_comunes_puestos_porcentaje_asig)
UNION
 SELECT cnmd10_comunes_puestos_porcentaje_ded.cod_presi, cnmd10_comunes_puestos_porcentaje_ded.cod_entidad, cnmd10_comunes_puestos_porcentaje_ded.cod_tipo_inst, cnmd10_comunes_puestos_porcentaje_ded.cod_inst, cnmd10_comunes_puestos_porcentaje_ded.cod_dep, cnmd10_comunes_puestos_porcentaje_ded.cod_tipo_nomina, cnmd10_comunes_puestos_porcentaje_ded.cod_tipo_transaccion, cnmd10_comunes_puestos_porcentaje_ded.cod_transaccion
   FROM cnmd10_comunes_puestos_porcentaje_ded)
UNION
 SELECT cnmd10_comunes_sueldo_sugerido.cod_presi, cnmd10_comunes_sueldo_sugerido.cod_entidad, cnmd10_comunes_sueldo_sugerido.cod_tipo_inst, cnmd10_comunes_sueldo_sugerido.cod_inst, cnmd10_comunes_sueldo_sugerido.cod_dep, cnmd10_comunes_sueldo_sugerido.cod_tipo_nomina, cnmd10_comunes_sueldo_sugerido.cod_tipo_transaccion, cnmd10_comunes_sueldo_sugerido.cod_transaccion
   FROM cnmd10_comunes_sueldo_sugerido)
UNION
 SELECT cnmd10_control_de_escenarios.cod_presi, cnmd10_control_de_escenarios.cod_entidad, cnmd10_control_de_escenarios.cod_tipo_inst, cnmd10_control_de_escenarios.cod_inst, cnmd10_control_de_escenarios.cod_dep, cnmd10_control_de_escenarios.cod_tipo_nomina, cnmd10_control_de_escenarios.cod_tipo_transaccion, cnmd10_control_de_escenarios.cod_transaccion
   FROM cnmd10_control_de_escenarios)
UNION
 SELECT cnmd10_individual_bolivares.cod_presi, cnmd10_individual_bolivares.cod_entidad, cnmd10_individual_bolivares.cod_tipo_inst, cnmd10_individual_bolivares.cod_inst, cnmd10_individual_bolivares.cod_dep, cnmd10_individual_bolivares.cod_tipo_nomina, cnmd10_individual_bolivares.cod_tipo_transaccion, cnmd10_individual_bolivares.cod_transaccion
   FROM cnmd10_individual_bolivares)
UNION
 SELECT cnmd10_individual_bolivares_cantidad.cod_presi, cnmd10_individual_bolivares_cantidad.cod_entidad, cnmd10_individual_bolivares_cantidad.cod_tipo_inst, cnmd10_individual_bolivares_cantidad.cod_inst, cnmd10_individual_bolivares_cantidad.cod_dep, cnmd10_individual_bolivares_cantidad.cod_tipo_nomina, cnmd10_individual_bolivares_cantidad.cod_tipo_transaccion, cnmd10_individual_bolivares_cantidad.cod_transaccion
   FROM cnmd10_individual_bolivares_cantidad)
UNION
 SELECT cnmd10_individual_porcentaje_horas.cod_presi, cnmd10_individual_porcentaje_horas.cod_entidad, cnmd10_individual_porcentaje_horas.cod_tipo_inst, cnmd10_individual_porcentaje_horas.cod_inst, cnmd10_individual_porcentaje_horas.cod_dep, cnmd10_individual_porcentaje_horas.cod_tipo_nomina, cnmd10_individual_porcentaje_horas.cod_tipo_transaccion, cnmd10_individual_porcentaje_horas.cod_transaccion
   FROM cnmd10_individual_porcentaje_horas)
UNION
 SELECT cnmd10_individual_porcentaje_horas_cantidad.cod_presi, cnmd10_individual_porcentaje_horas_cantidad.cod_entidad, cnmd10_individual_porcentaje_horas_cantidad.cod_tipo_inst, cnmd10_individual_porcentaje_horas_cantidad.cod_inst, cnmd10_individual_porcentaje_horas_cantidad.cod_dep, cnmd10_individual_porcentaje_horas_cantidad.cod_tipo_nomina, cnmd10_individual_porcentaje_horas_cantidad.cod_tipo_transaccion, cnmd10_individual_porcentaje_horas_cantidad.cod_transaccion
   FROM cnmd10_individual_porcentaje_horas_cantidad;

ALTER TABLE v_todos_escenarios OWNER TO sisap;



