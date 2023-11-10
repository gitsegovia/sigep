-- Function: denominacion_partida(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION denominacion_partida(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);
-- Table: cnmd10_individual_dias_cantidad

CREATE TABLE cnmd07_transacciones_prenomina
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo_nomina integer NOT NULL, -- Código tipo de nómina
  cod_cargo integer NOT NULL, -- Código del cargo
  cod_ficha integer NOT NULL, -- Código de ficha del trabajador
  cod_tipo_transaccion integer NOT NULL, -- Código tipo de transaccion
  cod_transaccion integer NOT NULL, -- Código de transaccion
  fecha_transaccion date NOT NULL, -- Fecha de la transacción
  monto_original numeric(26,2), -- Monto original de la transacción, cuando esta sea una deducción cuya actualización sea deductiva
  numero_cuotas_descontar integer NOT NULL, -- Número original de cuotas a descontar o a cancelar
  numero_cuotas_cancelar integer NOT NULL, -- Número de cuotas a cancelar
  numero_cuotas_canceladas integer NOT NULL, -- Número de cuotas canceladas
  monto_cuota numeric(26,2) NOT NULL, -- Monto de la cuota
  saldo numeric(26,2) NOT NULL, -- Saldo de la transaccion. Este saldo dependende de la actualización, si es acumulativo o es deductivo
  marca_fin_descuento character varying(1), -- Esta marca debe ser un asteristico. Al momento del cierre este busca las transacciones que tengan esta marca y las elimina
  fecha_proceso date NOT NULL, -- Fecha de proceso de la transaccion
  username character varying(60) NOT NULL, -- Operador que realizo la operacion
  dias_horas integer, -- Dias o Horas
  CONSTRAINT cnmd07_transacciones_prenomina_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion)
)
WITH (OIDS=FALSE);
ALTER TABLE cnmd07_transacciones_prenomina OWNER TO sisap;
COMMENT ON TABLE cnmd07_transacciones_prenomina IS 'Registra las transacciones cuando se realizan mas de un pago';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_tipo_inst IS 'Código tipo de institución';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_tipo_nomina IS 'Código tipo de nómina';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_cargo IS 'Código del cargo';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_ficha IS 'Código de ficha del trabajador';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_tipo_transaccion IS 'Código tipo de transaccion';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.cod_transaccion IS 'Código de transaccion';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.fecha_transaccion IS 'Fecha de la transacción';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.monto_original IS 'Monto original de la transacción, cuando esta sea una deducción cuya actualización sea deductiva';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.numero_cuotas_descontar IS 'Número original de cuotas a descontar o a cancelar';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.numero_cuotas_cancelar IS 'Número de cuotas a cancelar';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.numero_cuotas_canceladas IS 'Número de cuotas canceladas';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.monto_cuota IS 'Monto de la cuota';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.saldo IS 'Saldo de la transaccion. Este saldo dependende de la actualización, si es acumulativo o es deductivo';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.marca_fin_descuento IS 'Esta marca debe ser un asteristico. Al momento del cierre este busca las transacciones que tengan esta marca y las elimina';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.fecha_proceso IS 'Fecha de proceso de la transaccion';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.username IS 'Operador que realizo la operacion';
COMMENT ON COLUMN cnmd07_transacciones_prenomina.dias_horas IS 'Dias o Horas';



CREATE TABLE cnmd10_individual_dias
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la entidad
  cod_tipo_nomina integer NOT NULL, -- Código tipo de nómina
  cod_tipo_transaccion integer NOT NULL, -- Código tipo de transacción...
  cod_transaccion integer NOT NULL, -- Código de transacción
  cod_frecuencia integer NOT NULL, -- Código de frecuencia...
  cod_condicion integer NOT NULL,
  codi_tipo_transaccion integer, -- Código tipo de transacción
  codi_transaccion integer, -- Código de transacción
  activar_frecuencia_eventual integer NOT NULL, -- 1.- Si...
  CONSTRAINT cnmd10_individual_dias_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion),
  CONSTRAINT cnmd10_individual_dias_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina)
      REFERENCES cnmd01 (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE cnmd10_individual_dias OWNER TO sisap;
COMMENT ON TABLE cnmd10_individual_dias IS 'Escenarios de control - Asignaciones - Cancelacion de dias especiales a trabajadores';
COMMENT ON COLUMN cnmd10_individual_dias.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cnmd10_individual_dias.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cnmd10_individual_dias.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cnmd10_individual_dias.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cnmd10_individual_dias.cod_dep IS 'Código de la entidad';
COMMENT ON COLUMN cnmd10_individual_dias.cod_tipo_nomina IS 'Código tipo de nómina';
COMMENT ON COLUMN cnmd10_individual_dias.cod_tipo_transaccion IS 'Código tipo de transacción
Siempre debe ser "1"';
COMMENT ON COLUMN cnmd10_individual_dias.cod_transaccion IS 'Código de transacción';
COMMENT ON COLUMN cnmd10_individual_dias.cod_frecuencia IS 'Código de frecuencia
1.- Fijo
2.- Eventual
Por defecto "1"';
COMMENT ON COLUMN cnmd10_individual_dias.codi_tipo_transaccion IS 'Código tipo de transacción';
COMMENT ON COLUMN cnmd10_individual_dias.codi_transaccion IS 'Código de transacción';
COMMENT ON COLUMN cnmd10_individual_dias.activar_frecuencia_eventual IS '1.- Si
2.- No
Por defecto "2"';

-- DROP TABLE cnmd10_individual_dias_cantidad;

CREATE TABLE cnmd10_individual_dias_cantidad
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo_nomina integer NOT NULL, -- Código tipo de nómina
  cod_cargo integer NOT NULL, -- Código del cargo
  cod_ficha integer NOT NULL, -- Código de la ficha
  cod_tipo_transaccion integer NOT NULL, -- Código tipo de transacción...
  cod_transaccion integer NOT NULL, -- Código de transacción
  cantidad numeric(7,2) NOT NULL, -- Cantidad
  CONSTRAINT cnmd10_individual_dias_cantidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion),
  CONSTRAINT cnmd10_individual_dias_cantidad_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion)
      REFERENCES cnmd10_individual_dias (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE cnmd10_individual_dias_cantidad OWNER TO sisap;
COMMENT ON TABLE cnmd10_individual_dias_cantidad IS 'Registra la cantidad de horas trabajadas para los escenarios individuales';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_tipo_nomina IS 'Código tipo de nómina';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_cargo IS 'Código del cargo';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_ficha IS 'Código de la ficha';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_tipo_transaccion IS 'Código tipo de transacción
1.- Asignación
2.- Deducción
Por defecto "1"';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cod_transaccion IS 'Código de transacción';
COMMENT ON COLUMN cnmd10_individual_dias_cantidad.cantidad IS 'Cantidad';


CREATE OR REPLACE FUNCTION denominacion_partida(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pano integer, pcod_sector integer, pcod_programa integer, pcod_sub_prog integer, pcod_proyecto integer, pcod_activ_obra integer, pcod_partida integer, pcod_generica integer, pcod_especifica integer, pcod_sub_espec integer, pcod_auxiliar integer)
  RETURNS text AS
$BODY$
DECLARE
t text;

BEGIN
   if pcod_auxiliar = 0 then
       t = (SELECT deno_sub_espec FROM v_cfpd05_denominaciones WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog and cod_proyecto=pcod_proyecto and cod_activ_obra=pcod_activ_obra and cod_partida=pcod_partida and cod_generica=pcod_generica and cod_especifica=pcod_especifica  and cod_sub_espec=pcod_sub_espec and cod_auxiliar=pcod_auxiliar);
   else
       t = (SELECT deno_auxiliar FROM v_cfpd05_denominaciones WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog and cod_proyecto=pcod_proyecto and cod_activ_obra=pcod_activ_obra and cod_partida=pcod_partida and cod_generica=pcod_generica and cod_especifica=pcod_especifica  and cod_sub_espec=pcod_sub_espec and cod_auxiliar=pcod_auxiliar);
   end if;
RETURN upper(t);
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION denominacion_partida(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- View: v_todos_escenarios
-- Function: mascara_3(integer)

-- DROP FUNCTION mascara_3(integer);

CREATE OR REPLACE FUNCTION mascara_3(integer)
  RETURNS text AS
$BODY$
DECLARE
t text;
c integer;
BEGIN
c = (SELECT length($1::text));
if  c=3 then
t = '' || $1;
elsif  c=2 then
t = '0' || $1;
elsif  c=1 then
t = '00' || $1;
end if;

RETURN t;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION mascara_3(integer) OWNER TO sisap;

CREATE OR REPLACE VIEW cnmd06_fichas_clasi_personal AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha, a.cedula_identidad, a.fecha_ingreso, a.forma_pago, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.condicion_actividad, a.funciones_realizar, a.responsabilidad_administrativa, a.horas_laborar, a.porcentaje_jub_pension, a.fecha_terminacion_contrato, a.fecha_retiro, a.motivo_retiro, a.paso, a.tipo_contrato, a.situacion, a.nivel, a.categoria, ( SELECT b.clasificacion_personal
           FROM cnmd01 b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS clasificacion_personal
   FROM cnmd06_fichas a
  WHERE a.condicion_actividad = 1;

ALTER TABLE cnmd06_fichas_clasi_personal OWNER TO sisap;


-- DROP VIEW v_todos_escenarios;

CREATE OR REPLACE VIEW v_todos_escenarios AS
(((((((((((((((((((((((((((((((( SELECT cnmd10_aportes_patronales.cod_presi, cnmd10_aportes_patronales.cod_entidad, cnmd10_aportes_patronales.cod_tipo_inst, cnmd10_aportes_patronales.cod_inst, cnmd10_aportes_patronales.cod_dep, cnmd10_aportes_patronales.cod_tipo_nomina, cnmd10_aportes_patronales.cod_tipo_transaccion, cnmd10_aportes_patronales.cod_transaccion
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
   FROM cnmd10_individual_porcentaje_horas_cantidad)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion
   FROM cnmd10_aportes_patronales a)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transa_patrono AS cod_tipo_transaccion, a.cod_transa_patrono AS cod_transaccion
   FROM cnmd10_aportes_patronales a)
UNION
 SELECT cnmd10_individual_dias_cantidad.cod_presi, cnmd10_individual_dias_cantidad.cod_entidad, cnmd10_individual_dias_cantidad.cod_tipo_inst, cnmd10_individual_dias_cantidad.cod_inst, cnmd10_individual_dias_cantidad.cod_dep, cnmd10_individual_dias_cantidad.cod_tipo_nomina, cnmd10_individual_dias_cantidad.cod_tipo_transaccion, cnmd10_individual_dias_cantidad.cod_transaccion
   FROM cnmd10_individual_dias_cantidad;

ALTER TABLE v_todos_escenarios OWNER TO sisap;

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

CREATE OR REPLACE VIEW v_cnmd09_frecuencia AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, a.cod_frecuencia, ( SELECT x.denominacion
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS denominacion
   FROM cnmd09_frecuencia a;

ALTER TABLE v_cnmd09_frecuencia OWNER TO sisap;

CREATE OR REPLACE VIEW v_cnmd07_transacciones_actuales_frecuencias AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha, a.cod_tipo_transaccion, a.cod_transaccion, a.fecha_transaccion, a.monto_original, a.numero_cuotas_descontar, a.numero_cuotas_cancelar, a.numero_cuotas_canceladas, a.monto_cuota, a.saldo, a.marca_fin_descuento, a.fecha_proceso, a.username, a.dias_horas, ( SELECT devolver_denominacion_transaccion(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion) AS devolver_denominacion_transaccion) AS denominacion
   FROM cnmd07_transacciones_actuales a
  WHERE (( SELECT verificar_frecuencias(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion) AS verificar_frecuencias)) = 1;

ALTER TABLE v_cnmd07_transacciones_actuales_frecuencias OWNER TO sisap;
COMMENT ON VIEW v_cnmd07_transacciones_actuales_frecuencias IS 'vista de la tabla de transacciones actuales filtrada para no mostras las transacciones que no cumplen las condiciones';


CREATE OR REPLACE VIEW v_cnmd07_transacciones_actuales_frecuencias2 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha, a.cod_tipo_transaccion, a.cod_transaccion, a.fecha_transaccion, a.monto_original, a.numero_cuotas_descontar, a.numero_cuotas_cancelar, a.numero_cuotas_canceladas, a.monto_cuota, a.saldo, a.marca_fin_descuento, a.fecha_proceso, a.username, a.dias_horas, ( SELECT x.frecuencia_pago
           FROM cnmd01 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina) AS frecuencia_pago, ( SELECT x.denominacion
           FROM cnmd01 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina) AS denominacion_nomina, ( SELECT x.cod_frecuencia
           FROM cnmd09_frecuencia x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS cod_frecuencia, ( SELECT devolver_denominacion_transaccion(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion) AS devolver_denominacion_transaccion) AS denominacion, ( SELECT x.tipo_actualizacion
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS tipo_actualizacion, ( SELECT x.uso_transaccion
           FROM cnmd03_transacciones x
          WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS uso_transaccion, ( SELECT count(*) AS count
           FROM cnmd07_transacciones_suspendidas x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_ficha = a.cod_ficha AND x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS verificacion_cantidad_pago
   FROM cnmd07_transacciones_actuales a;

ALTER TABLE v_cnmd07_transacciones_actuales_frecuencias2 OWNER TO sisap;
COMMENT ON VIEW v_cnmd07_transacciones_actuales_frecuencias2 IS 'vista de la tabla de transacciones actuales con los campos de la frecuencia de la tabla cnm01 y la tabla frecuencias';


CREATE OR REPLACE VIEW v_cnmd07_transacciones_suspendidas AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, c.cod_puesto, c.sueldo_basico, ( SELECT devolver_denominacion_puesto(( SELECT xy.clasificacion_personal
                   FROM cnmd01 xy
                  WHERE xy.cod_presi = a.cod_presi AND xy.cod_entidad = a.cod_entidad AND xy.cod_tipo_inst = a.cod_tipo_inst AND xy.cod_inst = a.cod_inst AND xy.cod_dep = a.cod_dep AND xy.cod_tipo_nomina = a.cod_tipo_nomina), c.cod_puesto) AS devolver_denominacion_puesto) AS denominacion_puesto, a.cod_ficha, a.cod_tipo_transaccion, a.cod_transaccion, ( SELECT b.denominacion
           FROM cnmd03_transacciones b
          WHERE a.cod_tipo_transaccion = b.cod_tipo_transaccion AND a.cod_transaccion = b.cod_transaccion) AS deno_transaccion, a.fecha_transaccion, a.monto_original, a.numero_cuotas_descontar, a.numero_cuotas_cancelar, a.numero_cuotas_canceladas, a.monto_cuota, a.saldo, a.marca_fin_descuento, a.fecha_proceso, a.username
   FROM cnmd07_transacciones_suspendidas a, cnmd05 c
  WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_tipo_nomina = c.cod_tipo_nomina AND a.cod_cargo = c.cod_cargo;

ALTER TABLE v_cnmd07_transacciones_suspendidas OWNER TO sisap;


CREATE OR REPLACE VIEW trasacciones_no_conectadas AS
 (SELECT a.cod_presi,
         a.cod_entidad,
         a.cod_tipo_inst,
         a.cod_inst,
         a.cod_dep,
         a.cod_tipo_nomina,
         a.cod_tipo_transaccion,
         a.cod_transaccion,
         ( SELECT devolver_denominacion_transaccion(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion) AS devolver_denominacion_transaccion) AS denominacion
 FROM v_cnmd07_transacciones_actuales_frecuencias2 a
  WHERE (a.cod_tipo_transaccion = 1 OR (a.cod_tipo_transaccion = 2 AND (a.uso_transaccion = 6 or a.uso_transaccion = 9))) AND
       ( SELECT count(*) AS count FROM cnmd03_conexion_transacciones b
															          WHERE b.cod_presi = a.cod_presi AND
															                b.cod_entidad = a.cod_entidad AND
															                b.cod_tipo_inst = a.cod_tipo_inst AND
															                b.cod_inst = a.cod_inst AND
															                b.cod_dep = a.cod_dep AND
															                b.cod_tipo_nomina = a.cod_tipo_nomina AND
															                b.cod_cargo = a.cod_cargo AND
															                b.cod_tipo_transaccion = a.cod_tipo_transaccion AND
															                b.cod_transaccion = a.cod_transaccion AND
															                b.ano = substr(a.fecha_transaccion::text, 0, 5)::integer) = 0
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.codi_tipo_transaccion_asig AS cod_tipo_transaccion, a.codi_transaccion_asig AS cod_transaccion, ( SELECT devolver_denominacion_transaccion(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.codi_tipo_transaccion_asig, a.codi_transaccion_asig) AS devolver_denominacion_transaccion) AS denominacion
   FROM deducciones_conectadas_asignacion a
  WHERE (( SELECT count(*) AS count
           FROM cnmd03_conexion_transacciones b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_tipo_transaccion = a.codi_tipo_transaccion_asig AND b.cod_transaccion = a.codi_transaccion_asig)) = 0
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.codi_tipo_transaccion_asig, a.codi_transaccion_asig;

ALTER TABLE trasacciones_no_conectadas OWNER TO sisap;

CREATE OR REPLACE VIEW deducciones_conectadas_asignacion2 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, c.codi_tipo_transaccion_asig, c.codi_transaccion_asig, a.monto_cuota, (((mascara_3(c.cod_transaccion_ded) || ' - '::text) || a.denominacion) || ' > '::text) || (( SELECT (mascara_3(c.codi_transaccion_asig) || ' - '::text) || x.denominacion
           FROM v_cnmd07_transacciones_actuales_frecuencias2 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_tipo_transaccion = c.codi_tipo_transaccion_asig AND x.cod_transaccion = c.codi_transaccion_asig
         LIMIT 1)) AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd09_deducciones_conectada_asignaciones c
  WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_tipo_nomina = c.cod_tipo_nomina AND a.cod_tipo_transaccion = c.cod_tipo_transaccion_ded AND a.cod_transaccion = c.cod_transaccion_ded AND a.cod_tipo_transaccion = 2 AND c.activar = 1;

ALTER TABLE deducciones_conectadas_asignacion2 OWNER TO sisap;

CREATE OR REPLACE VIEW fichas_generar_recibo AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha;

ALTER TABLE fichas_generar_recibo OWNER TO sisap;

-- View: esc_transacciones_eventuales

-- DROP VIEW esc_transacciones_eventuales;

CREATE OR REPLACE VIEW esc_transacciones_eventuales AS
((((((((((((((((((((((((( SELECT cnmd10_comunes_asignacion_bolivares_sexo.cod_presi, cnmd10_comunes_asignacion_bolivares_sexo.cod_entidad, cnmd10_comunes_asignacion_bolivares_sexo.cod_tipo_inst, cnmd10_comunes_asignacion_bolivares_sexo.cod_inst, cnmd10_comunes_asignacion_bolivares_sexo.cod_dep, cnmd10_comunes_asignacion_bolivares_sexo.cod_tipo_nomina
   FROM cnmd10_comunes_asignacion_bolivares_sexo
  WHERE cnmd10_comunes_asignacion_bolivares_sexo.cod_frecuencia = 2 AND cnmd10_comunes_asignacion_bolivares_sexo.activar_frecuencia_eventual = 1
UNION
 SELECT cnmd10_comunes_asignacion_porcentaje_sexo.cod_presi, cnmd10_comunes_asignacion_porcentaje_sexo.cod_entidad, cnmd10_comunes_asignacion_porcentaje_sexo.cod_tipo_inst, cnmd10_comunes_asignacion_porcentaje_sexo.cod_inst, cnmd10_comunes_asignacion_porcentaje_sexo.cod_dep, cnmd10_comunes_asignacion_porcentaje_sexo.cod_tipo_nomina
   FROM cnmd10_comunes_asignacion_porcentaje_sexo
  WHERE cnmd10_comunes_asignacion_porcentaje_sexo.cod_frecuencia = 2 AND cnmd10_comunes_asignacion_porcentaje_sexo.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_bolivares_asignacion.cod_presi, cnmd10_comunes_bolivares_asignacion.cod_entidad, cnmd10_comunes_bolivares_asignacion.cod_tipo_inst, cnmd10_comunes_bolivares_asignacion.cod_inst, cnmd10_comunes_bolivares_asignacion.cod_dep, cnmd10_comunes_bolivares_asignacion.cod_tipo_nomina
   FROM cnmd10_comunes_bolivares_asignacion
  WHERE cnmd10_comunes_bolivares_asignacion.cod_frecuencia = 2 AND cnmd10_comunes_bolivares_asignacion.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_bolivares_deduccion.cod_presi, cnmd10_comunes_bolivares_deduccion.cod_entidad, cnmd10_comunes_bolivares_deduccion.cod_tipo_inst, cnmd10_comunes_bolivares_deduccion.cod_inst, cnmd10_comunes_bolivares_deduccion.cod_dep, cnmd10_comunes_bolivares_deduccion.cod_tipo_nomina
   FROM cnmd10_comunes_bolivares_deduccion
  WHERE cnmd10_comunes_bolivares_deduccion.cod_frecuencia = 2 AND cnmd10_comunes_bolivares_deduccion.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_dia_asignacion.cod_presi, cnmd10_comunes_dia_asignacion.cod_entidad, cnmd10_comunes_dia_asignacion.cod_tipo_inst, cnmd10_comunes_dia_asignacion.cod_inst, cnmd10_comunes_dia_asignacion.cod_dep, cnmd10_comunes_dia_asignacion.cod_tipo_nomina
   FROM cnmd10_comunes_dia_asignacion
  WHERE cnmd10_comunes_dia_asignacion.cod_frecuencia = 2 AND cnmd10_comunes_dia_asignacion.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_dia_deduccion.cod_presi, cnmd10_comunes_dia_deduccion.cod_entidad, cnmd10_comunes_dia_deduccion.cod_tipo_inst, cnmd10_comunes_dia_deduccion.cod_inst, cnmd10_comunes_dia_deduccion.cod_dep, cnmd10_comunes_dia_deduccion.cod_tipo_nomina
   FROM cnmd10_comunes_dia_deduccion
  WHERE cnmd10_comunes_dia_deduccion.cod_frecuencia = 2 AND cnmd10_comunes_dia_deduccion.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_presi, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_entidad, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_inst, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_dep, cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_tipo_nomina
   FROM cnmd10_comunes_escala_antiguedad_bolivares_asig
  WHERE cnmd10_comunes_escala_antiguedad_bolivares_asig.cod_frecuencia = 2 AND cnmd10_comunes_escala_antiguedad_bolivares_asig.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_presi, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_entidad, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_inst, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_dep, cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_tipo_nomina
   FROM cnmd10_comunes_escala_antiguedad_bolivares_ded
  WHERE cnmd10_comunes_escala_antiguedad_bolivares_ded.cod_frecuencia = 2 AND cnmd10_comunes_escala_antiguedad_bolivares_ded.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_dias_asig.cod_presi, cnmd10_comunes_escala_antiguedad_dias_asig.cod_entidad, cnmd10_comunes_escala_antiguedad_dias_asig.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_dias_asig.cod_inst, cnmd10_comunes_escala_antiguedad_dias_asig.cod_dep, cnmd10_comunes_escala_antiguedad_dias_asig.cod_tipo_nomina
   FROM cnmd10_comunes_escala_antiguedad_dias_asig
  WHERE cnmd10_comunes_escala_antiguedad_dias_asig.cod_frecuencia = 2 AND cnmd10_comunes_escala_antiguedad_dias_asig.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_dias_ded.cod_presi, cnmd10_comunes_escala_antiguedad_dias_ded.cod_entidad, cnmd10_comunes_escala_antiguedad_dias_ded.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_dias_ded.cod_inst, cnmd10_comunes_escala_antiguedad_dias_ded.cod_dep, cnmd10_comunes_escala_antiguedad_dias_ded.cod_tipo_nomina
   FROM cnmd10_comunes_escala_antiguedad_dias_ded
  WHERE cnmd10_comunes_escala_antiguedad_dias_ded.cod_frecuencia = 2 AND cnmd10_comunes_escala_antiguedad_dias_ded.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_presi, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_entidad, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_inst, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_dep, cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_tipo_nomina
   FROM cnmd10_comunes_escala_antiguedad_porcentaje_asig
  WHERE cnmd10_comunes_escala_antiguedad_porcentaje_asig.cod_frecuencia = 2 AND cnmd10_comunes_escala_antiguedad_porcentaje_asig.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_presi, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_entidad, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_tipo_inst, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_inst, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_dep, cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_tipo_nomina
   FROM cnmd10_comunes_escala_antiguedad_porcentaje_ded
  WHERE cnmd10_comunes_escala_antiguedad_porcentaje_ded.cod_frecuencia = 2 AND cnmd10_comunes_escala_antiguedad_porcentaje_ded.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_mes_dia_asig.cod_presi, cnmd10_comunes_escala_mes_dia_asig.cod_entidad, cnmd10_comunes_escala_mes_dia_asig.cod_tipo_inst, cnmd10_comunes_escala_mes_dia_asig.cod_inst, cnmd10_comunes_escala_mes_dia_asig.cod_dep, cnmd10_comunes_escala_mes_dia_asig.cod_tipo_nomina
   FROM cnmd10_comunes_escala_mes_dia_asig
  WHERE cnmd10_comunes_escala_mes_dia_asig.cod_frecuencia = 2 AND cnmd10_comunes_escala_mes_dia_asig.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_sueldo_bolivares_asig.cod_presi, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_entidad, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_tipo_inst, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_inst, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_dep, cnmd10_comunes_escala_sueldo_bolivares_asig.cod_tipo_nomina
   FROM cnmd10_comunes_escala_sueldo_bolivares_asig
  WHERE cnmd10_comunes_escala_sueldo_bolivares_asig.cod_frecuencia = 2 AND cnmd10_comunes_escala_sueldo_bolivares_asig.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_sueldo_bolivares_ded.cod_presi, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_entidad, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_tipo_inst, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_inst, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_dep, cnmd10_comunes_escala_sueldo_bolivares_ded.cod_tipo_nomina
   FROM cnmd10_comunes_escala_sueldo_bolivares_ded
  WHERE cnmd10_comunes_escala_sueldo_bolivares_ded.cod_frecuencia = 2 AND cnmd10_comunes_escala_sueldo_bolivares_ded.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_presi, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_entidad, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_tipo_inst, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_inst, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_dep, cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_tipo_nomina
   FROM cnmd10_comunes_escala_sueldo_porcentaje_asig
  WHERE cnmd10_comunes_escala_sueldo_porcentaje_asig.cod_frecuencia = 2 AND cnmd10_comunes_escala_sueldo_porcentaje_asig.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_presi, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_entidad, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_tipo_inst, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_inst, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_dep, cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_tipo_nomina
   FROM cnmd10_comunes_escala_sueldo_porcentaje_ded
  WHERE cnmd10_comunes_escala_sueldo_porcentaje_ded.cod_frecuencia = 2 AND cnmd10_comunes_escala_sueldo_porcentaje_ded.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_porcentaje_asignacion.cod_presi, cnmd10_comunes_porcentaje_asignacion.cod_entidad, cnmd10_comunes_porcentaje_asignacion.cod_tipo_inst, cnmd10_comunes_porcentaje_asignacion.cod_inst, cnmd10_comunes_porcentaje_asignacion.cod_dep, cnmd10_comunes_porcentaje_asignacion.cod_tipo_nomina
   FROM cnmd10_comunes_porcentaje_asignacion
  WHERE cnmd10_comunes_porcentaje_asignacion.cod_frecuencia = 2 AND cnmd10_comunes_porcentaje_asignacion.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_porcentaje_deduccion.cod_presi, cnmd10_comunes_porcentaje_deduccion.cod_entidad, cnmd10_comunes_porcentaje_deduccion.cod_tipo_inst, cnmd10_comunes_porcentaje_deduccion.cod_inst, cnmd10_comunes_porcentaje_deduccion.cod_dep, cnmd10_comunes_porcentaje_deduccion.cod_tipo_nomina
   FROM cnmd10_comunes_porcentaje_deduccion
  WHERE cnmd10_comunes_porcentaje_deduccion.cod_frecuencia = 2 AND cnmd10_comunes_porcentaje_deduccion.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_puestos_bolivares_asig.cod_presi, cnmd10_comunes_puestos_bolivares_asig.cod_entidad, cnmd10_comunes_puestos_bolivares_asig.cod_tipo_inst, cnmd10_comunes_puestos_bolivares_asig.cod_inst, cnmd10_comunes_puestos_bolivares_asig.cod_dep, cnmd10_comunes_puestos_bolivares_asig.cod_tipo_nomina
   FROM cnmd10_comunes_puestos_bolivares_asig
  WHERE cnmd10_comunes_puestos_bolivares_asig.cod_frecuencia = 2 AND cnmd10_comunes_puestos_bolivares_asig.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_puestos_bolivares_ded.cod_presi, cnmd10_comunes_puestos_bolivares_ded.cod_entidad, cnmd10_comunes_puestos_bolivares_ded.cod_tipo_inst, cnmd10_comunes_puestos_bolivares_ded.cod_inst, cnmd10_comunes_puestos_bolivares_ded.cod_dep, cnmd10_comunes_puestos_bolivares_ded.cod_tipo_nomina
   FROM cnmd10_comunes_puestos_bolivares_ded
  WHERE cnmd10_comunes_puestos_bolivares_ded.cod_frecuencia = 2 AND cnmd10_comunes_puestos_bolivares_ded.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_puestos_porcentaje_asig.cod_presi, cnmd10_comunes_puestos_porcentaje_asig.cod_entidad, cnmd10_comunes_puestos_porcentaje_asig.cod_tipo_inst, cnmd10_comunes_puestos_porcentaje_asig.cod_inst, cnmd10_comunes_puestos_porcentaje_asig.cod_dep, cnmd10_comunes_puestos_porcentaje_asig.cod_tipo_nomina
   FROM cnmd10_comunes_puestos_porcentaje_asig
  WHERE cnmd10_comunes_puestos_porcentaje_asig.cod_frecuencia = 2 AND cnmd10_comunes_puestos_porcentaje_asig.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_puestos_porcentaje_ded.cod_presi, cnmd10_comunes_puestos_porcentaje_ded.cod_entidad, cnmd10_comunes_puestos_porcentaje_ded.cod_tipo_inst, cnmd10_comunes_puestos_porcentaje_ded.cod_inst, cnmd10_comunes_puestos_porcentaje_ded.cod_dep, cnmd10_comunes_puestos_porcentaje_ded.cod_tipo_nomina
   FROM cnmd10_comunes_puestos_porcentaje_ded
  WHERE cnmd10_comunes_puestos_porcentaje_ded.cod_frecuencia = 2 AND cnmd10_comunes_puestos_porcentaje_ded.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_comunes_sueldo_sugerido.cod_presi, cnmd10_comunes_sueldo_sugerido.cod_entidad, cnmd10_comunes_sueldo_sugerido.cod_tipo_inst, cnmd10_comunes_sueldo_sugerido.cod_inst, cnmd10_comunes_sueldo_sugerido.cod_dep, cnmd10_comunes_sueldo_sugerido.cod_tipo_nomina
   FROM cnmd10_comunes_sueldo_sugerido
  WHERE cnmd10_comunes_sueldo_sugerido.cod_frecuencia = 2 AND cnmd10_comunes_sueldo_sugerido.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_individual_bolivares.cod_presi, cnmd10_individual_bolivares.cod_entidad, cnmd10_individual_bolivares.cod_tipo_inst, cnmd10_individual_bolivares.cod_inst, cnmd10_individual_bolivares.cod_dep, cnmd10_individual_bolivares.cod_tipo_nomina
   FROM cnmd10_individual_bolivares
  WHERE cnmd10_individual_bolivares.cod_frecuencia = 2 AND cnmd10_individual_bolivares.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_individual_dias.cod_presi, cnmd10_individual_dias.cod_entidad, cnmd10_individual_dias.cod_tipo_inst, cnmd10_individual_dias.cod_inst, cnmd10_individual_dias.cod_dep, cnmd10_individual_dias.cod_tipo_nomina
   FROM cnmd10_individual_dias
  WHERE cnmd10_individual_dias.cod_frecuencia = 2 AND cnmd10_individual_dias.activar_frecuencia_eventual = 1)
UNION
 SELECT cnmd10_individual_porcentaje_horas.cod_presi, cnmd10_individual_porcentaje_horas.cod_entidad, cnmd10_individual_porcentaje_horas.cod_tipo_inst, cnmd10_individual_porcentaje_horas.cod_inst, cnmd10_individual_porcentaje_horas.cod_dep, cnmd10_individual_porcentaje_horas.cod_tipo_nomina
   FROM cnmd10_individual_porcentaje_horas
  WHERE cnmd10_individual_porcentaje_horas.cod_frecuencia = 2 AND cnmd10_individual_porcentaje_horas.activar_frecuencia_eventual = 1;

ALTER TABLE esc_transacciones_eventuales OWNER TO sisap;

CREATE OR REPLACE VIEW deducciones_conectadas_asignacion AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, c.codi_tipo_transaccion_asig, c.codi_transaccion_asig, a.monto_cuota
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd09_deducciones_conectada_asignaciones c
  WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_tipo_nomina = c.cod_tipo_nomina AND a.cod_tipo_transaccion = c.cod_tipo_transaccion_ded AND a.cod_transaccion = c.cod_transaccion_ded AND a.cod_tipo_transaccion = 2 AND c.activar = 1;

ALTER TABLE deducciones_conectadas_asignacion OWNER TO sisap;

CREATE OR REPLACE VIEW deducciones_conectadas_asignacion2 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, c.codi_tipo_transaccion_asig, c.codi_transaccion_asig, a.monto_cuota, (((mascara_3(c.cod_transaccion_ded) || ' - '::text) || a.denominacion) || ' > '::text) || (( SELECT (mascara_3(c.codi_transaccion_asig) || ' - '::text) || x.denominacion
           FROM v_cnmd07_transacciones_actuales_frecuencias2 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_tipo_transaccion = c.codi_tipo_transaccion_asig AND x.cod_transaccion = c.codi_transaccion_asig
         LIMIT 1)) AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd09_deducciones_conectada_asignaciones c
  WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_tipo_nomina = c.cod_tipo_nomina AND a.cod_tipo_transaccion = c.cod_tipo_transaccion_ded AND a.cod_transaccion = c.cod_transaccion_ded AND a.cod_tipo_transaccion = 2 AND c.activar = 1;

ALTER TABLE deducciones_conectadas_asignacion2 OWNER TO sisap;

CREATE OR REPLACE VIEW cargos_anos_diferentes AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, ( SELECT b.ano AS count
           FROM cnmd05 b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo) AS ano_cnmd05, ( SELECT substr(a.fecha_transaccion::text, 0, 5)::integer AS substr) AS ano_transaccion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a
  WHERE (( SELECT b.ano AS count
           FROM cnmd05 b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo)) <> (( SELECT substr(a.fecha_transaccion::text, 0, 5)::integer AS substr));

ALTER TABLE cargos_anos_diferentes OWNER TO sisap;


-- -- -- --
-- -- -- --
-- desde aqui las funciones en pgplsql
-- -- -- --
-- -- -- --

-- Function: bajar_trans_prenomina(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION bajar_trans_prenomina(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION bajar_trans_prenomina(integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;


   retornar integer = 0;

	     Rpre record;
             Cpre cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT * FROM cnmd07_transacciones_prenomina WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
             Rpre_c record;
             Cpre_c cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT count(*) as cantidad FROM cnmd07_transacciones_prenomina WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
begin
            open Cpre_c (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
		loop
			fetch Cpre_c into Rpre_c;
			exit when not found;
			if Rpre_c.cantidad!=0 then
				open Cpre (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
				loop
					fetch Cpre into Rpre;
					exit when not found;

					   UPDATE cnmd07_transacciones_actuales
					   SET fecha_transaccion=Rpre.fecha_transaccion, monto_original=Rpre.monto_original, numero_cuotas_descontar=Rpre.numero_cuotas_descontar,
					       numero_cuotas_cancelar=Rpre.numero_cuotas_cancelar, numero_cuotas_canceladas=Rpre.numero_cuotas_canceladas, monto_cuota=Rpre.monto_cuota,
					       saldo=Rpre.saldo, marca_fin_descuento=Rpre.marca_fin_descuento, fecha_proceso=Rpre.fecha_proceso, username=Rpre.username
					 WHERE cod_presi=Rpre.cod_presi AND cod_entidad=Rpre.cod_entidad AND cod_tipo_inst=Rpre.cod_tipo_inst AND cod_inst=Rpre.cod_inst AND cod_dep=Rpre.cod_dep AND cod_tipo_nomina=Rpre.cod_tipo_nomina AND cod_cargo=Rpre.cod_cargo AND cod_ficha=Rpre.cod_ficha AND cod_tipo_transaccion=Rpre.cod_tipo_transaccion AND cod_transaccion=Rpre.cod_transaccion;

					DELETE FROM cnmd07_transacciones_prenomina WHERE cod_presi=Rpre.cod_presi AND cod_entidad=Rpre.cod_entidad AND cod_tipo_inst=Rpre.cod_tipo_inst AND cod_inst=Rpre.cod_inst AND cod_dep=Rpre.cod_dep AND cod_tipo_nomina=Rpre.cod_tipo_nomina AND cod_cargo=Rpre.cod_cargo AND cod_ficha=Rpre.cod_ficha AND cod_tipo_transaccion=Rpre.cod_tipo_transaccion AND cod_transaccion=Rpre.cod_transaccion;
				end loop;
				close Cpre;
                    end if;
                    end loop;
                close Cpre_c;

	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION bajar_trans_prenomina(integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: bajar_trans_suspen_trans_actuales(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION bajar_trans_suspen_trans_actuales(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION bajar_trans_suspen_trans_actuales(integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;


   retornar integer = 0;

	     Rsuspendida record;
             Csuspendida cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT * FROM cnmd07_transacciones_suspendidas WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
             Rsuspendida_c record;
             Csuspendida_c cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT count(*) as cantidad FROM cnmd07_transacciones_suspendidas WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
begin
            open Csuspendida_c (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
		loop
			fetch Csuspendida_c into Rsuspendida_c;
			exit when not found;
			if Rsuspendida_c.cantidad!=0 then
				open Csuspendida (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
				loop
					fetch Csuspendida into Rsuspendida;
					exit when not found;

					   UPDATE cnmd07_transacciones_actuales
					   SET fecha_transaccion=Rsuspendida.fecha_transaccion, monto_original=Rsuspendida.monto_original, numero_cuotas_descontar=Rsuspendida.numero_cuotas_descontar,
					       numero_cuotas_cancelar=Rsuspendida.numero_cuotas_cancelar, numero_cuotas_canceladas=Rsuspendida.numero_cuotas_canceladas, monto_cuota=Rsuspendida.monto_cuota,
					       saldo=Rsuspendida.saldo, marca_fin_descuento=Rsuspendida.marca_fin_descuento, fecha_proceso=Rsuspendida.fecha_proceso, username=Rsuspendida.username
					 WHERE cod_presi=Rsuspendida.cod_presi AND cod_entidad=Rsuspendida.cod_entidad AND cod_tipo_inst=Rsuspendida.cod_tipo_inst AND cod_inst=Rsuspendida.cod_inst AND cod_dep=Rsuspendida.cod_dep AND cod_tipo_nomina=Rsuspendida.cod_tipo_nomina AND cod_cargo=Rsuspendida.cod_cargo AND cod_ficha=Rsuspendida.cod_ficha AND cod_tipo_transaccion=Rsuspendida.cod_tipo_transaccion AND cod_transaccion=Rsuspendida.cod_transaccion;

					DELETE FROM cnmd07_transacciones_suspendidas WHERE cod_presi=Rsuspendida.cod_presi AND cod_entidad=Rsuspendida.cod_entidad AND cod_tipo_inst=Rsuspendida.cod_tipo_inst AND cod_inst=Rsuspendida.cod_inst AND cod_dep=Rsuspendida.cod_dep AND cod_tipo_nomina=Rsuspendida.cod_tipo_nomina AND cod_cargo=Rsuspendida.cod_cargo AND cod_ficha=Rsuspendida.cod_ficha AND cod_tipo_transaccion=Rsuspendida.cod_tipo_transaccion AND cod_transaccion=Rsuspendida.cod_transaccion;
				end loop;
				close Csuspendida;
                    end if;
                    end loop;
                close Csuspendida_c;

	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION bajar_trans_suspen_trans_actuales(integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: bajar_trans_viadiskette_trans_actuales(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION bajar_trans_viadiskette_trans_actuales(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION bajar_trans_viadiskette_trans_actuales(integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;


   retornar integer = 0;

	     Rviadiskette record;
             Cviadiskette cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT * FROM cnmd07_transacciones_viadiskette WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
             Rviadiskette_c record;
             Cviadiskette_c cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT count(*) as cantidad FROM cnmd07_transacciones_viadiskette WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
begin
            open Cviadiskette_c (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
		loop
			fetch Cviadiskette_c into Rviadiskette_c;
			exit when not found;
			if Rviadiskette_c.cantidad!=0 then
				open Cviadiskette (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
				loop
					fetch Cviadiskette into Rviadiskette;
					exit when not found;
					   UPDATE cnmd07_transacciones_actuales
					   SET fecha_transaccion=Rviadiskette.fecha_transaccion, monto_original=Rviadiskette.monto_original, numero_cuotas_descontar=Rviadiskette.numero_cuotas_descontar,
					       numero_cuotas_cancelar=Rviadiskette.numero_cuotas_cancelar, numero_cuotas_canceladas=Rviadiskette.numero_cuotas_canceladas, monto_cuota=Rviadiskette.monto_cuota,
					       saldo=Rviadiskette.saldo, marca_fin_descuento=Rviadiskette.marca_fin_descuento, fecha_proceso=Rviadiskette.fecha_proceso, username=Rviadiskette.username
					   WHERE cod_presi=Rviadiskette.cod_presi AND cod_entidad=Rviadiskette.cod_entidad AND cod_tipo_inst=Rviadiskette.cod_tipo_inst AND cod_inst=Rviadiskette.cod_inst AND cod_dep=Rviadiskette.cod_dep AND cod_tipo_nomina=Rviadiskette.cod_tipo_nomina AND cod_cargo=Rviadiskette.cod_cargo AND cod_ficha=Rviadiskette.cod_ficha AND cod_tipo_transaccion=Rviadiskette.cod_tipo_transaccion AND cod_transaccion=Rviadiskette.cod_transaccion;

                                           DELETE FROM cnmd07_transacciones_viadiskette WHERE cod_presi=Rviadiskette.cod_presi AND cod_entidad=Rviadiskette.cod_entidad AND cod_tipo_inst=Rviadiskette.cod_tipo_inst AND cod_inst=Rviadiskette.cod_inst AND cod_dep=Rviadiskette.cod_dep AND cod_tipo_nomina=Rviadiskette.cod_tipo_nomina AND cod_cargo=Rviadiskette.cod_cargo AND cod_ficha=Rviadiskette.cod_ficha AND cod_tipo_transaccion=Rviadiskette.cod_tipo_transaccion AND cod_transaccion=Rviadiskette.cod_transaccion;
				end loop;
				close Cviadiskette;
                    end if;
                    end loop;
                close Cviadiskette_c;

	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION bajar_trans_viadiskette_trans_actuales(integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: cant_prorrateo(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION cant_prorrateo(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION cant_prorrateo(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS integer AS
$BODY$
DECLARE
   prorrateo integer = 0;
BEGIN
 prorrateo=(SELECT count(*) FROM cnmd09_transa_nosujetas_prorrateo WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_tipo_transaccion=pcod_tipo_transaccion AND cod_transaccion=pcod_transaccion);
 if prorrateo is null then
   prorrateo = 0;
 end if;
RETURN prorrateo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION cant_prorrateo(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;



CREATE OR REPLACE FUNCTION cant_trans_actuales(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS integer AS
$BODY$
DECLARE
   cant_trans integer = 0;
BEGIN
 cant_trans=(SELECT count(*) FROM cnmd07_transacciones_actuales WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=pcod_cargo AND cod_ficha=pcod_ficha AND cod_tipo_transaccion=pcod_tipo_transaccion AND cod_transaccion=pcod_transaccion);
 if cant_trans is null then
   cant_trans = 0;
 end if;
RETURN cant_trans;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION cant_trans_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: condicion_varias_escenario(integer, integer, integer)

-- DROP FUNCTION condicion_varias_escenario(integer, integer, integer);

CREATE OR REPLACE FUNCTION condicion_varias_escenario(pcod_frecuencia integer, pcod_condicion integer, pactiva integer)
  RETURNS integer AS
$BODY$
DECLARE
t integer = 0;
BEGIN
if pcod_frecuencia=1 and pcod_condicion=1 then
  t =1;
end if;

if pcod_frecuencia=2 and pcod_condicion=1 and pactiva=1 then
  t =1;
end if;

if pcod_frecuencia=1 and pcod_condicion=2 then
  t =2;
end if;

if pcod_frecuencia=2 and pcod_condicion=2 and pactiva=1 then
  t =2;
end if;

RETURN t;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION condicion_varias_escenario(integer, integer, integer) OWNER TO sisap;


-- Function: crear_transaccion_cero(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION crear_transaccion_cero(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION crear_transaccion_cero(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer)
  RETURNS integer AS
$BODY$
DECLARE

retornar integer = 0;
existe1 integer = 0;
existe2 integer = 0;
xsueldo numeric(26,2) = 0;
             /* Rcnmd06 es  para la vista v_cnmd06_fichas_sueldo_basico */
	     Rcnmd06 record;
             Ccnmd06 cursor (pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer) for SELECT * FROM  v_cnmd06_fichas_sueldo_basico  WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND condicion_actividad=1;

begin
	open Ccnmd06 (pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina);
		loop
			fetch Ccnmd06 into Rcnmd06;
			exit when not found;
			existe2 = cant_trans_actuales(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina, Rcnmd06.cod_cargo,Rcnmd06.cod_ficha,1,1);
                        xsueldo = devolver_calculo_monto_escenario(pcod_presi, pcod_entidad, pcod_tipo_inst,pcod_inst, pcod_dep, pcod_tipo_nomina, Rcnmd06.cod_cargo,Rcnmd06.cod_ficha,1,1, Rcnmd06.sueldo_basico);
			if existe2 = 0 then
			   INSERT INTO cnmd07_transacciones_actuales VALUES (Rcnmd06.cod_presi, Rcnmd06.cod_entidad, Rcnmd06.cod_tipo_inst, Rcnmd06.cod_inst, Rcnmd06.cod_dep, Rcnmd06.cod_tipo_nomina,Rcnmd06.cod_cargo, Rcnmd06.cod_ficha, 1, 1,CURRENT_DATE,0,0,1,0,xsueldo,0, 0,CURRENT_DATE, 'AUTO',0);
			else
			     UPDATE cnmd07_transacciones_actuales SET monto_cuota=xsueldo  WHERE cod_presi=Rcnmd06.cod_presi AND cod_entidad=Rcnmd06.cod_entidad AND cod_tipo_inst=Rcnmd06.cod_tipo_inst AND cod_inst=Rcnmd06.cod_inst AND cod_dep=Rcnmd06.cod_dep AND cod_tipo_nomina=Rcnmd06.cod_tipo_nomina AND cod_cargo=Rcnmd06.cod_cargo AND cod_ficha=Rcnmd06.cod_ficha AND cod_tipo_transaccion=1 AND cod_transaccion=1;
			end if;
		end loop;
	close Ccnmd06;
   return retornar;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION crear_transaccion_cero(integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_montocuota_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_montocuota_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_montocuota_cnmd07_transacciones_actuales(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   monto  numeric(26,2) = 0;
BEGIN
/*
 monto=(SELECT monto_cuota FROM cnmd07_transacciones_actuales WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=pcod_cargo AND cod_ficha=pcod_ficha AND cod_tipo_transaccion=pcod_tipo_transaccion AND cod_transaccion=pcod_transaccion);
 if monto is null then
   monto = 0.00;
 end if;*/
  monto=(SELECT SUM(monto_cuota) FROM cnmd07_transacciones_actuales WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=pcod_cargo AND cod_ficha=pcod_ficha AND
 cod_tipo_transaccion=1
 and cod_transaccion IN (SELECT codi_transaccion FROM cnmd09_asignacion_calcula_asignacion_2 WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion));
 if monto is null then
   monto = 0.00;
 end if;
RETURN monto;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_montocuota_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_montocuota_porc_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_montocuota_porc_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_montocuota_porc_cnmd07_transacciones_actuales(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   monto  numeric(26,2) = 0;
BEGIN
 monto=(SELECT SUM(monto_cuota) FROM cnmd07_transacciones_actuales WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=pcod_cargo AND cod_ficha=pcod_ficha AND
 cod_tipo_transaccion=1
 and cod_transaccion IN (SELECT codi_transaccion FROM cnmd09_asignacion_calcula_asignacion_2 WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion));
 if monto is null then
   monto = 0.00;
 end if;
RETURN monto;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_montocuota_porc_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_montocuota_porc_ded_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_montocuota_porc_ded_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_montocuota_porc_ded_cnmd07_transacciones_actuales(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   monto  numeric(26,2) = 0;
BEGIN
 monto=(SELECT SUM(monto_cuota) FROM cnmd07_transacciones_actuales WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=pcod_cargo AND cod_ficha=pcod_ficha AND
 cod_tipo_transaccion=1
 and cod_transaccion IN (SELECT codi_transaccion FROM cnmd09_asignacion_calcula_deduccion_2 WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion));
 if monto is null then
   monto = 0.00;
 end if;
RETURN monto;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_montocuota_porc_ded_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_porcentaje_puesto(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_porcentaje_puesto(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_porcentaje_puesto(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   porcentaje_para_calculo  numeric(5,2) = 0;
BEGIN

   porcentaje_para_calculo = (select porcentaje FROM cnmd10_comunes_puestos_porcentaje_asig_2 WHERE
        cod_presi=pcod_presi and
        cod_entidad=pcod_entidad and
        cod_tipo_inst=pcod_tipo_inst and
        cod_inst=pcod_inst and
        cod_dep=pcod_dep and
        cod_tipo_nomina=pcod_tipo_nomina and
        cod_tipo_transaccion=pcod_tipo_transaccion and
        cod_transaccion=pcod_transaccion and cod_puesto=(select cod_puesto from cnmd05 where cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_cargo=pcod_cargo));

RETURN porcentaje_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_porcentaje_puesto(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_porcentaje_puesto_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_porcentaje_puesto_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_porcentaje_puesto_ded(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   porcentaje_para_calculo  numeric(5,2) = 0;
BEGIN

   porcentaje_para_calculo = (select porcentaje FROM cnmd10_comunes_puestos_porcentaje_ded_2 WHERE
        cod_presi=pcod_presi and
        cod_entidad=pcod_entidad and
        cod_tipo_inst=pcod_tipo_inst and
        cod_inst=pcod_inst and
        cod_dep=pcod_dep and
        cod_tipo_nomina=pcod_tipo_nomina and
        cod_tipo_transaccion=pcod_tipo_transaccion and
        cod_transaccion=pcod_transaccion and cod_puesto=(select cod_puesto from cnmd05 where cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_cargo=pcod_cargo));

RETURN porcentaje_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_porcentaje_puesto_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_sexo_cnmd06_fichas(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_sexo_cnmd06_fichas(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_sexo_cnmd06_fichas(integer, integer, integer, integer, integer, integer, integer, integer)
  RETURNS character varying AS
$BODY$
DECLARE
   Pcod_presi            alias for $1;
   Pcod_entidad          alias for $2;
   Pcod_tipo_inst        alias for $3;
   Pcod_inst             alias for $4;
   Pcod_dep              alias for $5;
   Pcod_tipo_nomina      alias for $6;
   Pcod_cargo            alias for $7;
   Pcod_ficha            alias for $8;
   rsexo  character varying(1);

BEGIN

 rsexo=(SELECT sexo FROM cnmd06_datos_personales WHERE cedula_identidad=(SELECT cedula_identidad FROM cnmd06_fichas WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha));

RETURN rsexo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_sexo_cnmd06_fichas(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_sueldo_basico_cnmd05(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_sueldo_basico_cnmd05(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_sueldo_basico_cnmd05(integer, integer, integer, integer, integer, integer, integer, integer)
  RETURNS numeric AS
$BODY$
DECLARE
   Pcod_presi            alias for $1;
   Pcod_entidad          alias for $2;
   Pcod_tipo_inst        alias for $3;
   Pcod_inst             alias for $4;
   Pcod_dep              alias for $5;
   Pcod_tipo_nomina      alias for $6;
   Pcod_cargo            alias for $7;
   Pcod_ficha            alias for $8;
   r_sueldo_basico  numeric(26,2);
   vsueldo_sugerido numeric(26,2);
   c_sueldo_sugerido integer = 0;

BEGIN

 c_sueldo_sugerido = (SELECT count(*) FROM cnmd09_incidencia_sueldo_sugerido WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina);

 if c_sueldo_sugerido != 0 then
    vsueldo_sugerido = devolver_sueldo_sugerido(Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst,Pcod_dep, Pcod_tipo_nomina);
    r_sueldo_basico = (SELECT sueldo_basico FROM cnmd05 WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha);
    if r_sueldo_basico < vsueldo_sugerido then
      r_sueldo_basico = vsueldo_sugerido;
    end if;
 else
    r_sueldo_basico = (SELECT sueldo_basico FROM cnmd05 WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha);
 end if;


RETURN r_sueldo_basico;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_sueldo_basico_cnmd05(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_sueldo_sugerido(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_sueldo_sugerido(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_sueldo_sugerido(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer)
  RETURNS numeric AS
$BODY$
DECLARE
   re_sueldo_sugerido  numeric(26,2) = 0;
BEGIN
   re_sueldo_sugerido = (select sueldo_sugerido FROM cnmd09_incidencia_sueldo_sugerido WHERE
        cod_presi=pcod_presi and
        cod_entidad=pcod_entidad and
        cod_tipo_inst=pcod_tipo_inst and
        cod_inst=pcod_inst and
        cod_dep=pcod_dep and
        cod_tipo_nomina=pcod_tipo_nomina);
RETURN re_sueldo_sugerido;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_sueldo_sugerido(integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_monto_puesto_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_monto_puesto_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_monto_puesto_ded(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   monto_para_calculo  numeric(26,2) = 0;
BEGIN

   monto_para_calculo = (select monto FROM cnmd10_comunes_puestos_bolivares_ded_2 WHERE
        cod_presi=pcod_presi and
        cod_entidad=pcod_entidad and
        cod_tipo_inst=pcod_tipo_inst and
        cod_inst=pcod_inst and
        cod_dep=pcod_dep and
        cod_tipo_nomina=pcod_tipo_nomina and
        cod_tipo_transaccion=pcod_tipo_transaccion and
        cod_transaccion=pcod_transaccion and cod_puesto=(select cod_puesto from cnmd05 where cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_cargo=pcod_cargo));

RETURN monto_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_monto_puesto_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: devolver_monto_puesto(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_monto_puesto(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_monto_puesto(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   monto_para_calculo  numeric(26,2) = 0;
BEGIN

   monto_para_calculo = (select monto FROM cnmd10_comunes_puestos_bolivares_asig_2 WHERE
        cod_presi=pcod_presi and
        cod_entidad=pcod_entidad and
        cod_tipo_inst=pcod_tipo_inst and
        cod_inst=pcod_inst and
        cod_dep=pcod_dep and
        cod_tipo_nomina=pcod_tipo_nomina and
        cod_tipo_transaccion=pcod_tipo_transaccion and
        cod_transaccion=pcod_transaccion and cod_puesto=(select cod_puesto from cnmd05 where cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_cargo=pcod_cargo));

RETURN monto_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_monto_puesto(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_monto_para_calculo_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_monto_para_calculo_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_monto_para_calculo_escenario(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   vmonto_cuota        numeric(26,2) = 0;
   monto_para_calculo  numeric(26,2) = 0;
   vsueldo_basico      numeric(26,2) = 0;
   cant_1              integer = 0;
   cant_2              integer = 0;
BEGIN
	cant_1=(SELECT count(*) FROM cnmd09_asignacion_calcula_asignacion_2 WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion);
        if cant_1 !=0 then
	      vmonto_cuota = devolver_montocuota_porc_cnmd07_transacciones_actuales(pcod_presi,pcod_entidad, pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha,pcod_tipo_transaccion,pcod_transaccion);
              monto_para_calculo = vmonto_cuota;
	end if;
/*
	cant_2=(SELECT incluye_sueldo_basico FROM cnmd09_asignacion_calcula_asignacion WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and  cod_transaccion=pcod_transaccion);
        if cant_2 is null then
              vsueldo_basico = devolver_sueldo_basico_cnmd05(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha);
              monto_para_calculo = monto_para_calculo + vsueldo_basico;
        else
              if cant_2 !=2 then
	          vsueldo_basico = devolver_sueldo_basico_cnmd05(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha);
                  monto_para_calculo = monto_para_calculo + vsueldo_basico;
	      end if;
        end if;*/

RETURN monto_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_monto_para_calculo_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: devolver_fecha_ingreso_cnmd06_fichas(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_fecha_ingreso_cnmd06_fichas(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_fecha_ingreso_cnmd06_fichas(integer, integer, integer, integer, integer, integer, integer, integer)
  RETURNS date AS
$BODY$
DECLARE
   Pcod_presi            alias for $1;
   Pcod_entidad          alias for $2;
   Pcod_tipo_inst        alias for $3;
   Pcod_inst             alias for $4;
   Pcod_dep              alias for $5;
   Pcod_tipo_nomina      alias for $6;
   Pcod_cargo            alias for $7;
   Pcod_ficha            alias for $8;
   rfecha_ingreso  date;

BEGIN

 rfecha_ingreso=(SELECT fecha_ingreso FROM cnmd06_fichas WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha);

RETURN rfecha_ingreso;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_fecha_ingreso_cnmd06_fichas(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: devolver_edad(date, date, text)

-- DROP FUNCTION devolver_edad(date, date, text);

CREATE OR REPLACE FUNCTION devolver_edad(date, date, text)
  RETURNS integer AS
$BODY$
DECLARE
opcion text = upper($3);
FECHA_ACTUAL date = $1;
FECHA_NAC date =$2;
devolver integer = 0;
BEGIN
if opcion ='ANO' then
   devolver = date_part('years',age(FECHA_ACTUAL,FECHA_NAC))::integer;
end if;

if opcion ='MES' then
   devolver = date_part('month',age(FECHA_ACTUAL,FECHA_NAC))::integer;
end if;

if opcion ='DIA' then
   devolver = date_part('day',age(FECHA_ACTUAL,FECHA_NAC))::integer;
end if;

RETURN devolver;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_edad(date, date, text) OWNER TO sisap;

-- Function: devolver_dias_t_i_e(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_dias_t_i_e(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_dias_t_i_e(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer)
  RETURNS integer AS
$BODY$
DECLARE
   r_dias_i_e  integer = 0;
BEGIN
 r_dias_i_e=(SELECT dias FROM cnmd09_dias_trabajados_ingreso_egreso WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha);
 if r_dias_i_e is null then
   r_dias_i_e = 0;
 end if;
RETURN r_dias_i_e;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_dias_t_i_e(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_dias_faltas(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_dias_faltas(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_dias_faltas(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer)
  RETURNS integer AS
$BODY$
DECLARE
   r_dias_faltas  integer = 0;
BEGIN
 r_dias_faltas=(SELECT dias FROM cnmd09_dias_trabajados_falta WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha);
 if r_dias_faltas is null then
   r_dias_faltas = 0;
 end if;
RETURN r_dias_faltas;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_dias_faltas(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: devolver_dias_cobro_cnmd01(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_dias_cobro_cnmd01(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_dias_cobro_cnmd01(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer)
  RETURNS integer AS
$BODY$
DECLARE
   vdias_cobro integer =0;
BEGIN
 vdias_cobro=(SELECT dias_cobro FROM cnmd01 WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina);
 if vdias_cobro is null then
   vdias_cobro = 0;
 end if;
RETURN vdias_cobro;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_dias_cobro_cnmd01(integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_denominacion_transaccion(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_denominacion_transaccion(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_denominacion_transaccion(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS text AS
$BODY$
DECLARE
t text;

BEGIN
   if pcod_tipo_transaccion = 1 AND pcod_transaccion = 1 then
       t = (SELECT denominacion_devengado FROM cnmd01 WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina);
   else
       t = (SELECT x.denominacion FROM cnmd03_transacciones x WHERE x.cod_tipo_transaccion = pcod_tipo_transaccion AND x.cod_transaccion = pcod_transaccion);
   end if;
RETURN upper(t);
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_denominacion_transaccion(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_calculo_monto_porce_ded_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric)

-- DROP FUNCTION devolver_calculo_monto_porce_ded_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric);

CREATE OR REPLACE FUNCTION devolver_calculo_monto_porce_ded_escenario(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer, porcentaje_recibido numeric)
  RETURNS numeric AS
$BODY$
DECLARE
   porcentaje          numeric(5,2) = porcentaje_recibido;
   vmonto_cuota        numeric(26,2) = 0;
   monto_para_calculo  numeric(26,2) = 0;
   vsueldo_basico      numeric(26,2) = 0;
   cant_1              integer = 0;
   cant_2              integer = 0;
BEGIN
	cant_1=(SELECT count(*) FROM cnmd09_asignacion_calcula_deduccion_2 WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion);
        if cant_1 !=0 then
	      vmonto_cuota = devolver_montocuota_porc_ded_cnmd07_transacciones_actuales(pcod_presi,pcod_entidad, pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha,pcod_tipo_transaccion,pcod_transaccion);
              monto_para_calculo = vmonto_cuota;
	end if;
        /*
	cant_2=(SELECT incluye_sueldo_basico FROM cnmd09_asignacion_calcula_deduccion WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and  cod_transaccion=pcod_transaccion);
        if cant_2 is null then
              vsueldo_basico = devolver_sueldo_basico_cnmd05(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha);
              monto_para_calculo = monto_para_calculo + vsueldo_basico;
        else
              if cant_2 !=2 then
	          vsueldo_basico = devolver_sueldo_basico_cnmd05(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha);
                  monto_para_calculo = monto_para_calculo + vsueldo_basico;
	      end if;
        end if;*/


        monto_para_calculo=((monto_para_calculo*porcentaje)/100);


RETURN monto_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_calculo_monto_porce_ded_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric) OWNER TO sisap;

-- Function: devolver_calculo_monto_porce_asig_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric)

-- DROP FUNCTION devolver_calculo_monto_porce_asig_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric);

CREATE OR REPLACE FUNCTION devolver_calculo_monto_porce_asig_escenario(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer, porcentaje_recibido numeric)
  RETURNS numeric AS
$BODY$
DECLARE
   porcentaje          numeric(5,2) = porcentaje_recibido;
   vmonto_cuota        numeric(26,2) = 0;
   monto_para_calculo  numeric(26,2) = 0;
   vsueldo_basico      numeric(26,2) = 0;
   cant_1              integer = 0;
   cant_2              integer = 0;
BEGIN
	cant_1=(SELECT count(*) FROM cnmd09_asignacion_calcula_asignacion_2 WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion);
        if cant_1 !=0 then
	      vmonto_cuota = devolver_montocuota_porc_cnmd07_transacciones_actuales(pcod_presi,pcod_entidad, pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha,pcod_tipo_transaccion,pcod_transaccion);
              monto_para_calculo = vmonto_cuota;
	end if;

        /*
	cant_2=(SELECT incluye_sueldo_basico FROM cnmd09_asignacion_calcula_asignacion WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and  cod_transaccion=pcod_transaccion);
        if cant_2 is null then
              vsueldo_basico = devolver_sueldo_basico_cnmd05(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha);
              monto_para_calculo = monto_para_calculo + vsueldo_basico;
        else
              if cant_2 !=2 then
	          vsueldo_basico = devolver_sueldo_basico_cnmd05(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha);
                  monto_para_calculo = monto_para_calculo + vsueldo_basico;
	      end if;
        end if;*/


        monto_para_calculo=((monto_para_calculo*porcentaje)/100);


RETURN monto_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_calculo_monto_porce_asig_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric) OWNER TO sisap;

-- Function: devolver_calculo_monto_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric)

-- DROP FUNCTION devolver_calculo_monto_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric);

CREATE OR REPLACE FUNCTION devolver_calculo_monto_escenario(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer, monto_recibido numeric)
  RETURNS numeric AS
$BODY$
DECLARE
   monto_para_calculo  numeric(26,2) = monto_recibido;
   dias_cobro integer = 0;
   dias_tie integer = 0;
   dias_falta integer = 0;
   prorrateo integer = 0;
BEGIN
	dias_cobro = devolver_dias_cobro_cnmd01(pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina);
	dias_tie = devolver_dias_t_i_e(pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina, pcod_cargo, pcod_ficha);
	if dias_tie !=0 then
		monto_para_calculo = ((monto_para_calculo/dias_cobro)*dias_tie);
	else
		dias_falta = devolver_dias_faltas(pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina, pcod_cargo, pcod_ficha);
		if dias_falta !=0 then
		    prorrateo = cant_prorrateo(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_tipo_transaccion,pcod_transaccion);
		    if prorrateo = 0 then
		        INSERT INTO cnmd07_transacciones_suspendidas VALUES (pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina,pcod_cargo, pcod_ficha, pcod_tipo_transaccion, pcod_transaccion,CURRENT_DATE,0,0,1,0,monto_para_calculo,0, 0,CURRENT_DATE, 'AUTO',0);
			monto_para_calculo = ((monto_para_calculo/dias_cobro)*dias_falta);

		    end if;
		end if;
	end if;
RETURN monto_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_calculo_monto_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric) OWNER TO sisap;

-- Function: devolver_calculo_monto2_ded_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_calculo_monto2_ded_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_calculo_monto2_ded_escenario(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   vmonto_cuota        numeric(26,2) = 0;
   monto_para_calculo  numeric(26,2) = 0;
   vsueldo_basico      numeric(26,2) = 0;
   cant_1              integer = 0;
   cant_2              integer = 0;
BEGIN
	cant_1=(SELECT count(*) FROM cnmd09_asignacion_calcula_deduccion_2 WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and  cod_transaccion=pcod_transaccion);
        if cant_1 !=0 then
	      vmonto_cuota = devolver_montocuota_cnmd07_transacciones_actuales(pcod_presi, cod_entidad=pcod_entidad, pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha,pcod_tipo_transaccion,pcod_transaccion);
              monto_para_calculo = vmonto_cuota;
	end if;
        /*
	cant_2=(SELECT incluye_sueldo_basico FROM cnmd09_asignacion_calcula_deduccion WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and  cod_transaccion=pcod_transaccion);
        if cant_2 =1 then
	      vsueldo_basico = devolver_sueldo_basico_cnmd05(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha);
              monto_para_calculo = monto_para_calculo + vsueldo_basico;
	end if;*/



RETURN monto_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_calculo_monto2_ded_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: devolver_calculo_monto2_asig_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_calculo_monto2_asig_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_calculo_monto2_asig_escenario(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS numeric AS
$BODY$
DECLARE
   vmonto_cuota        numeric(26,2) = 0;
   monto_para_calculo  numeric(26,2) = 0;
   vsueldo_basico      numeric(26,2) = 0;
   cant_1              integer = 0;
   cant_2              integer = 0;
BEGIN

	cant_1=(SELECT count(*) FROM cnmd09_asignacion_calcula_asignacion_2 WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion);
        if cant_1 !=0 then
	      vmonto_cuota = devolver_montocuota_cnmd07_transacciones_actuales(pcod_presi,pcod_entidad, pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha,pcod_tipo_transaccion,pcod_transaccion);
              monto_para_calculo = vmonto_cuota;
	end if;

        /*
	cant_2=(SELECT incluye_sueldo_basico FROM cnmd09_asignacion_calcula_asignacion WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and  cod_transaccion=pcod_transaccion);
        if cant_2 is null then
              vsueldo_basico = devolver_sueldo_basico_cnmd05(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha);
              monto_para_calculo = monto_para_calculo + vsueldo_basico;
        else
              if cant_2 !=2 then
	          vsueldo_basico = devolver_sueldo_basico_cnmd05(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_cargo,pcod_ficha);
                  monto_para_calculo = monto_para_calculo + vsueldo_basico;
	      end if;
        end if;*/



RETURN monto_para_calculo;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_calculo_monto2_asig_escenario(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: elimina_transacciones_ap_nocumplen(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION elimina_transacciones_ap_nocumplen(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION elimina_transacciones_ap_nocumplen(integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   Pcod_presi       alias for $1;
   Pcod_entidad     alias for $2;
   Pcod_tipo_inst   alias for $3;
   Pcod_inst        alias for $4;
   Pcod_dep         alias for $5;
   Pcod_tipo_nomina alias for $6;
   c1 integer = 0;
   c2 integer = 0;
   retornar integer = 0;

	     record1 record;
             cursor1 cursor (Pcod_presi int4, Pcod_entidad int4, Pcod_tipo_inst int4, Pcod_inst int4, Pcod_dep int4, Pcod_tipo_nomina int4) for SELECT count(*) as cantidad FROM cnmd10_aportes_patronales WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina;
             record2 record;
             cursor2 cursor (Pcod_presi int4, Pcod_entidad int4, Pcod_tipo_inst int4, Pcod_inst int4, Pcod_dep int4, Pcod_tipo_nomina int4) for SELECT * FROM cnmd10_aportes_patronales WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina;
begin
            open cursor1 (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina);
		loop
			fetch cursor1 into record1;
			exit when not found;
			if record1.cantidad != 0 then
				open cursor2 (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina);
				loop
					fetch cursor2 into record2;
					exit when not found;
					c1=(select count(*) from cnmd07_transacciones_actuales where cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_tipo_transaccion=2 AND cod_transaccion=record2.cod_transaccion);
					c2=(select count(*) from cnmd07_transacciones_actuales where cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_tipo_transaccion=2 AND cod_transaccion=record2.cod_transa_patrono);
					if c1=0 and c2!=0 then
					   DELETE FROM cnmd07_transacciones_actuales WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_tipo_transaccion=2 AND cod_transaccion=record2.cod_transa_patrono;
					elsif c1!=0 and c2=0 then
                                           DELETE FROM cnmd07_transacciones_actuales WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_tipo_transaccion=2 AND cod_transaccion=record2.cod_transaccion;
					end if;
				end loop;
				close cursor2;
                    end if;
                    end loop;
                close cursor1;
 	return retornar;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION elimina_transacciones_ap_nocumplen(integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: escala_antiguedad_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION escala_antiguedad_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION escala_antiguedad_bolivares_asig(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, ano_calculado integer)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(26,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_antiguedad_bolivares_asig_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if ano_calculado >= recESCALA.desde_ano AND ano_calculado <= recESCALA.hasta_ano then
					retornar = recESCALA.monto;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_antiguedad_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: escala_antiguedad_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION escala_antiguedad_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION escala_antiguedad_bolivares_ded(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, ano_calculado integer)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(26,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_antiguedad_bolivares_ded_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if ano_calculado >= recESCALA.desde_ano AND ano_calculado <= recESCALA.hasta_ano then
					retornar = recESCALA.monto;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_antiguedad_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: escala_antiguedad_dias_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION escala_antiguedad_dias_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION escala_antiguedad_dias_asig(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, ano_calculado integer)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(7,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_antiguedad_dias_asig_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if ano_calculado >= recESCALA.desde_ano AND ano_calculado <= recESCALA.hasta_ano then
					retornar = recESCALA.dias;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_antiguedad_dias_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: escala_antiguedad_dias_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION escala_antiguedad_dias_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION escala_antiguedad_dias_ded(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, ano_calculado integer)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(7,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_antiguedad_dias_ded_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if ano_calculado >= recESCALA.desde_ano AND ano_calculado <= recESCALA.hasta_ano then
					retornar = recESCALA.dias;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_antiguedad_dias_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: escala_antiguedad_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION escala_antiguedad_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION escala_antiguedad_porcentaje_asig(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, ano_calculado integer)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(5,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_antiguedad_porcentaje_asig_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if ano_calculado >= recESCALA.desde_ano AND ano_calculado <= recESCALA.hasta_ano then
					retornar = recESCALA.porcentaje;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_antiguedad_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: escala_antiguedad_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION escala_antiguedad_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION escala_antiguedad_porcentaje_ded(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, ano_calculado integer)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(5,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_antiguedad_porcentaje_ded_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if ano_calculado >= recESCALA.desde_ano AND ano_calculado <= recESCALA.hasta_ano then
					retornar = recESCALA.porcentaje;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_antiguedad_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: escala_mes_dia_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION escala_mes_dia_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION escala_mes_dia_asig(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, ano_calculado integer, mes_calculado integer, dia_calculado integer)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(7,2) = 0;
 mes_c integer = 0;
 dias_c integer = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_mes_dia_asig_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if ano_calculado >= 1 then
				   mes_c = 12;
				   dias_c = 0;
				else
				   mes_c = mes_calculado;
				   dias_c = dia_calculado;
				end if;

				if mes_c >= recESCALA.desde_mes AND mes_c <= recESCALA.hasta_mes AND dias_c >= recESCALA.desde_dia AND dias_c <= recESCALA.hasta_dia then
				    retornar = recESCALA.dias;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_mes_dia_asig(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: escala_mes_dia_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION escala_mes_dia_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION escala_mes_dia_ded(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, ano_calculado integer, mes_calculado integer, dia_calculado integer)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(7,2) = 0;
 mes_c integer = 0;
 dias_c integer = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_mes_dia_ded_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if ano_calculado >= 1 then
				   mes_c = 12;
				   dias_c = 0;
				else
				   mes_c = mes_calculado;
				   dias_c = dia_calculado;
				end if;

				if mes_c >= recESCALA.desde_mes AND mes_c <= recESCALA.hasta_mes AND dias_c >= recESCALA.desde_dia AND dias_c <= recESCALA.hasta_dia then
				    retornar = recESCALA.dias;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_mes_dia_ded(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: escala_sueldo_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, numeric)

-- DROP FUNCTION escala_sueldo_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, numeric);

CREATE OR REPLACE FUNCTION escala_sueldo_bolivares_asig(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, sueldo_basico numeric)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(26,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_sueldo_bolivares_asig_2
                             WHERE cod_presi=pcod_presi and
                             cod_entidad=pcod_entidad and
                             cod_tipo_inst=pcod_tipo_inst and
                             cod_inst=pcod_inst and
                             cod_dep=pcod_dep and
                             cod_tipo_nomina=pcod_tipo_nomina and
                             cod_tipo_transaccion=pcod_tipo_transaccion and
                             cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if sueldo_basico >= recESCALA.desde_sueldo and sueldo_basico <= recESCALA.hasta_sueldo then
					retornar = recESCALA.monto;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_sueldo_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, numeric) OWNER TO sisap;

-- Function: escala_sueldo_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, numeric)

-- DROP FUNCTION escala_sueldo_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, numeric);

CREATE OR REPLACE FUNCTION escala_sueldo_bolivares_ded(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, sueldo_basico numeric)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(26,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_sueldo_bolivares_ded_2
                             WHERE cod_presi=pcod_presi and
                             cod_entidad=pcod_entidad and
                             cod_tipo_inst=pcod_tipo_inst and
                             cod_inst=pcod_inst and
                             cod_dep=pcod_dep and
                             cod_tipo_nomina=pcod_tipo_nomina and
                             cod_tipo_transaccion=pcod_tipo_transaccion and
                             cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if sueldo_basico >= recESCALA.desde_sueldo and sueldo_basico <= recESCALA.hasta_sueldo then
					retornar = recESCALA.monto;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_sueldo_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, numeric) OWNER TO sisap;

-- Function: escala_sueldo_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, numeric)

-- DROP FUNCTION escala_sueldo_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, numeric);

CREATE OR REPLACE FUNCTION escala_sueldo_porcentaje_asig(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, sueldo_basico numeric)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(26,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_sueldo_porcentaje_asig_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if sueldo_basico >= recESCALA.desde_sueldo and sueldo_basico <= recESCALA.hasta_sueldo then
					retornar = recESCALA.porcentaje;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_sueldo_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, numeric) OWNER TO sisap;

-- Function: escala_sueldo_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, numeric)

-- DROP FUNCTION escala_sueldo_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, numeric);

CREATE OR REPLACE FUNCTION escala_sueldo_porcentaje_ded(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer, sueldo_basico numeric)
  RETURNS numeric AS
$BODY$
DECLARE
 retornar numeric(26,2) = 0;

	recESCALA record;
	curESCALA cursor for SELECT *
                             FROM cnmd10_comunes_escala_sueldo_porcentaje_ded_2
                             WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_tipo_transaccion=pcod_tipo_transaccion and cod_transaccion=pcod_transaccion;

begin

		open curESCALA;
		loop
			fetch curESCALA into recESCALA;
			exit when not found;
				if sueldo_basico >= recESCALA.desde_sueldo and sueldo_basico <= recESCALA.hasta_sueldo then
					retornar = recESCALA.porcentaje;
				end if;
		end loop;
		close curESCALA;
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION escala_sueldo_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, numeric) OWNER TO sisap;

-- Function: verificar_frecuencias(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION verificar_frecuencias(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION verificar_frecuencias(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS integer AS
$BODY$
DECLARE
   retornar integer = 0;
   c1 integer = 0;
   c2 integer = 0;
BEGIN
	c1 = (SELECT x.frecuencia_pago FROM cnmd01 x WHERE x.cod_presi=pcod_presi and x.cod_entidad=pcod_entidad and x.cod_tipo_inst=pcod_tipo_inst and x.cod_inst=pcod_inst and x.cod_dep=pcod_dep and x.cod_tipo_nomina=pcod_tipo_nomina);
	c2 = (SELECT x.cod_frecuencia FROM cnmd09_frecuencia x WHERE x.cod_presi=pcod_presi and x.cod_entidad=pcod_entidad and x.cod_tipo_inst=pcod_tipo_inst and x.cod_inst=pcod_inst and x.cod_dep=pcod_dep and x.cod_tipo_nomina=pcod_tipo_nomina and x.cod_tipo_transaccion=pcod_tipo_transaccion and x.cod_transaccion=pcod_transaccion);

     if c1 = 1 and (c2 = 1 or c2 = 6) then
	retornar = 1;
     elsif c1 = 2 and (c2 = 2 or c2 = 6) then
	retornar = 1;
     elsif c1 = 3 and (c2 = 3 or c2 = 6) then
	retornar = 1;
     elsif c1 = 4 and (c2 = 4 or c2 = 6) then
	retornar = 1;
     elsif c1 = 5 and (c2 = 5 or c2 = 6) then
	retornar = 1;
     elsif c1 = 7 and (c2 = 7 or c2 = 9) then
	retornar = 1;
     elsif c1 = 8 and (c2 = 8 or c2 = 9) then
	retornar = 1;
     elsif c1 = 10 and c2 = 10 then
	retornar = 1;
     elsif c2 = 11 then
	retornar = 0;
     end if;




RETURN retornar;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION verificar_frecuencias(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: verifica_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION verifica_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION verifica_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   Pcod_presi            alias for $1;
   Pcod_entidad          alias for $2;
   Pcod_tipo_inst        alias for $3;
   Pcod_inst             alias for $4;
   Pcod_dep              alias for $5;
   Pcod_tipo_nomina      alias for $6;
   Pcod_cargo            alias for $7;
   Pcod_ficha            alias for $8;
   Pcod_tipo_transaccion alias for $9;
   Pcod_transaccion      alias for $10;

   retornar integer = 0;

	     Rtrans_actuales record;
             Ctrans_actuales cursor (Pcod_presi int4, Pcod_entidad int4, Pcod_tipo_inst int4, Pcod_inst int4, Pcod_dep int4, Pcod_tipo_nomina int4, Pcod_cargo int4, Pcod_ficha int4, Pcod_tipo_transaccion int4, Pcod_transaccion int4) for SELECT count(*) as cantidad FROM cnmd07_transacciones_actuales WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha AND cod_tipo_transaccion=Pcod_tipo_transaccion AND cod_transaccion=Pcod_transaccion;
begin
            open Ctrans_actuales (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina, Pcod_cargo,Pcod_ficha,Pcod_tipo_transaccion,Pcod_transaccion);
		loop
			fetch Ctrans_actuales into Rtrans_actuales;
			exit when not found;
			   retornar=Rtrans_actuales.cantidad;
                    end loop;
                close Ctrans_actuales;

	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION verifica_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: verifica_cargo_cnmd05(integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION verifica_cargo_cnmd05(integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION verifica_cargo_cnmd05(integer, integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   Pcod_presi       alias for $1;
   Pcod_entidad     alias for $2;
   Pcod_tipo_inst   alias for $3;
   Pcod_inst        alias for $4;
   Pcod_dep         alias for $5;
   Pcod_tipo_nomina alias for $6;
   Pcod_cargo       alias for $7;

   retornar integer = 0;

	     recCNMD05CON record;
             curCNMD05CON cursor (Pcod_presi int4, Pcod_entidad int4, Pcod_tipo_inst int4, Pcod_inst int4, Pcod_dep int4, Pcod_tipo_nomina int4, Pcod_cargo int4) for SELECT count(*) as cantidad_cargos FROM cnmd05 WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo;
begin
            open curCNMD05CON (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina, Pcod_cargo);
		loop
			fetch curCNMD05CON into recCNMD05CON;
			exit when not found;
			   retornar=recCNMD05CON.cantidad_cargos;
                    end loop;
                close curCNMD05CON;

	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION verifica_cargo_cnmd05(integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: update_monto_cuota_transanciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric)

-- DROP FUNCTION update_monto_cuota_transanciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric);

CREATE OR REPLACE FUNCTION update_monto_cuota_transanciones_actuales(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer, monto_actualizar numeric)
  RETURNS integer AS
$BODY$
DECLARE
existe integer = 0;
procesar integer = 0;
retornar integer =0;
monto_cuota_up numeric (26,2) = 0;
begin
        procesar = verificar_frecuencias(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,pcod_tipo_transaccion,pcod_transaccion);
        if procesar != 0 then
		existe = cant_trans_actuales(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina, pcod_cargo,pcod_ficha,pcod_tipo_transaccion,pcod_transaccion);
		if existe = 0 then
		   if monto_actualizar!=0 then
			INSERT INTO cnmd07_transacciones_actuales VALUES (pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina,pcod_cargo, pcod_ficha, pcod_tipo_transaccion, pcod_transaccion,CURRENT_DATE,0,1,1,0,monto_actualizar,0, 0,CURRENT_DATE, 'AUTO',0);
			retornar = 1;
		   else
		       retornar = 999;
		   end if;
		else
                      monto_cuota_up = monto_actualizar;
			  if monto_cuota_up = 0 then
			     DELETE FROM cnmd07_transacciones_actuales WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=pcod_cargo AND cod_ficha=pcod_ficha AND cod_tipo_transaccion=pcod_tipo_transaccion AND cod_transaccion=pcod_transaccion;
			   else
			     UPDATE cnmd07_transacciones_actuales SET monto_cuota=monto_cuota_up  WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=pcod_cargo AND cod_ficha=pcod_ficha AND cod_tipo_transaccion=pcod_tipo_transaccion AND cod_transaccion=pcod_transaccion;
			   end if;
                   retornar = 2;
		end if;
	else
         retornar = 717;
	end if;
   return retornar;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION update_monto_cuota_transanciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, numeric) OWNER TO sisap;

-- Function: revision_saldo_transacciones(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION revision_saldo_transacciones(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION revision_saldo_transacciones(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer)
  RETURNS integer AS
$BODY$
DECLARE
retornar integer =0;
monto_cuota_up numeric (26,2) = 0;
        /*recorrido para la tabla cnmd07_transacciones_actuales*/
	R1 record;
	C1 cursor (pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer) for SELECT * FROM v_cnmd07_transacciones_actuales_frecuencias2 WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND tipo_actualizacion=2;

begin

open C1 (pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina);
loop
    fetch C1 into R1;
    exit when not found;
    if R1.saldo < R1.monto_cuota then
	 INSERT INTO cnmd07_transacciones_suspendidas VALUES (pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina,R1.cod_cargo, R1.cod_ficha, R1.cod_tipo_transaccion, R1.cod_transaccion,CURRENT_DATE,0,0,1,0,R1.monto_cuota,R1.saldo, 0,CURRENT_DATE, 'AUTO',0);
	 monto_cuota_up = R1.saldo;
	   if monto_cuota_up = 0 then
	     DELETE FROM cnmd07_transacciones_actuales WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=R1.cod_cargo AND cod_ficha=R1.cod_ficha AND cod_tipo_transaccion=R1.cod_tipo_transaccion AND cod_transaccion=R1.cod_transaccion;
             retornar = 1;
	   else
	     UPDATE cnmd07_transacciones_actuales SET monto_cuota=monto_cuota_up , saldo=0  WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=R1.cod_cargo AND cod_ficha=R1.cod_ficha AND cod_tipo_transaccion=R1.cod_tipo_transaccion AND cod_transaccion=R1.cod_transaccion;
             retornar= 2;
	   end if;
   end if;

end loop;/*fin loop C1*/
close C1;
   return retornar;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION revision_saldo_transacciones(integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: revision_cantidad_pagos(integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION revision_cantidad_pagos(integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION revision_cantidad_pagos(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, cantidad_pagos integer)
  RETURNS integer AS
$BODY$
DECLARE
retornar integer =0;
monto_cuota_up numeric (26,2) = 0;
        /*recorrido para la tabla cnmd07_transacciones_actuales*/
	R1 record;
	C1 cursor (pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer) for SELECT * FROM v_cnmd07_transacciones_actuales_frecuencias2 WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND verificacion_cantidad_pago=0 AND  uso_transaccion!=7;

begin

open C1 (pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina);
loop
    fetch C1 into R1;
    exit when not found;
       monto_cuota_up = cantidad_pagos  *  R1.monto_cuota;
       UPDATE cnmd07_transacciones_actuales SET monto_cuota=monto_cuota_up  WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=R1.cod_cargo AND cod_ficha=R1.cod_ficha AND cod_tipo_transaccion=R1.cod_tipo_transaccion AND cod_transaccion=R1.cod_transaccion;
       INSERT INTO cnmd07_transacciones_prenomina VALUES (pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina,R1.cod_cargo,R1.cod_ficha, R1.cod_tipo_transaccion, R1.cod_transaccion,CURRENT_DATE,0,1,1,0,R1.monto_cuota,0, 0,CURRENT_DATE, 'AUTO',0);
    retornar= 2;
end loop;/*fin loop C1*/
close C1;
   return retornar;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION revision_cantidad_pagos(integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- -- -- --FUCNIONES
-- Function: f_cnmd10_comunes_asignacion_bolivares_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_asignacion_bolivares_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_asignacion_bolivares_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   sexo character varying(1);
   cf_cd_ac integer = 0;
   x_result integer = 0;
   retornarf integer = 4;
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_asignacion_bolivares_sexo  WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
        sexo = devolver_sexo_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        if sexo = 'M' then
           monto_para_calculo = Rescenario1.monto_masculino;

        elsif sexo = 'F' then
           monto_para_calculo = Rescenario1.monto_femenino;
        end if;
	cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/
        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */
end loop;
close Cescenario1;
return retornarf;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_asignacion_bolivares_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_asignacion_porcentaje_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_asignacion_porcentaje_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_asignacion_porcentaje_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   sexo character varying(1);
   porcentaje_escenario numeric (5,2);
   cf_cd_ac integer = 0;
   retornarf integer = 4;

   /*Escenario:cnmd10_comunes_asignacion_porcentaje_sexo  */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_asignacion_porcentaje_sexo  WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
        sexo = devolver_sexo_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        if sexo = 'M' then
           porcentaje_escenario = Rescenario1.porcentaje_masculino;

        elsif sexo = 'F' then
           porcentaje_escenario = Rescenario1.porcentaje_femenino;
        end if;


	cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_asignacion_porcentaje_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_bolivares_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_bolivares_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_bolivares_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   sexo character varying(1);
   cf_cd_ac integer = 0;
   retornarf integer = 4;

        /*Escenario:cnmd10_comunes_bolivares_asignacion  */
	Rescenario1 record;
        Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_bolivares_asignacion  WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
        monto_para_calculo = Rescenario1.monto;

	cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */
end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_bolivares_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_bolivares_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_bolivares_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_bolivares_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   sexo character varying(1);
   cf_cd_ac integer = 0;
   retornarf integer = 4;

        /*Escenario:cnmd10_comunes_bolivares_asignacion  */
	Rescenario1 record;
        Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_bolivares_deduccion  WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
        monto_para_calculo = Rescenario1.monto;

	cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */
end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_bolivares_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_deduccion_bolivares_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_deduccion_bolivares_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_deduccion_bolivares_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   sexo character varying(1);
   cf_cd_ac integer = 0;
   x_result integer = 0;
   retornarf integer = 4;
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_deduccion_bolivares_sexo  WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
        sexo = devolver_sexo_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        if sexo = 'M' then
           monto_para_calculo = Rescenario1.monto_masculino;

        elsif sexo = 'F' then
           monto_para_calculo = Rescenario1.monto_femenino;
        end if;
	cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/
        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */
end loop;
close Cescenario1;
return retornarf;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_deduccion_bolivares_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_deduccion_porcentaje_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_deduccion_porcentaje_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_deduccion_porcentaje_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   sexo character varying(1);
   porcentaje_escenario numeric (5,2);
   cf_cd_ac integer = 0;
   retornarf integer = 4;

   /*Escenario:cnmd10_comunes_asignacion_porcentaje_sexo  */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_deduccion_porcentaje_sexo  WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
        sexo = devolver_sexo_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        if sexo = 'M' then
           porcentaje_escenario = Rescenario1.porcentaje_masculino;

        elsif sexo = 'F' then
           porcentaje_escenario = Rescenario1.porcentaje_femenino;
        end if;


	cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_deduccion_porcentaje_sexo(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_dia_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_dia_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_dia_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   dias_cobro integer = 0;
   dias_escenario numeric(6,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;


   /*Escenario:cnmd10_comunes_dia_asignacion */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_dia_asignacion WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
            dias_cobro = devolver_dias_cobro_cnmd01(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
            dias_escenario = Rescenario1.dias;
            monto_para_calculo = devolver_calculo_monto2_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
            monto_para_calculo = ((monto_para_calculo/dias_cobro)*dias_escenario);

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */

end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_dia_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_dia_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_dia_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_dia_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   dias_cobro integer = 0;
   dias_escenario integer = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;


   /*Escenario:cnmd10_comunes_dia_deduccion */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_dia_deduccion WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
            dias_cobro = devolver_dias_cobro_cnmd01(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
            dias_escenario = Rescenario1.dias;
            monto_para_calculo = devolver_calculo_monto2_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
            monto_para_calculo = ((monto_para_calculo/dias_cobro)*dias_escenario);

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */

end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_dia_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_antiguedad_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_antiguedad_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_antiguedad_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;



   /*Escenario:cnmd10_comunes_escala_antiguedad_bolivares_asig */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_antiguedad_bolivares_asig WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        /*llamando de escenario de escala: cnmd10_comunes_escala_antiguedad_bolivares_asig_2*/
        monto_para_calculo = escala_antiguedad_bolivares_asig(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,ano_calculado);


        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_antiguedad_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_antiguedad_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_antiguedad_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_antiguedad_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;



   /*Escenario:cnmd10_comunes_escala_antiguedad_bolivares_ded */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_antiguedad_bolivares_ded WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        /*llamando de escenario de escala: cnmd10_comunes_escala_antiguedad_bolivares_asig_2*/
        monto_para_calculo = escala_antiguedad_bolivares_ded(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,ano_calculado);


        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_antiguedad_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_antiguedad_dias_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_antiguedad_dias_asig(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_antiguedad_dias_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   dias_cobro numeric(7,2) = 0;
   dias_escenario numeric(7,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;


   /*Escenario:cnmd10_comunes_escala_antiguedad_dias_asig */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_antiguedad_dias_asig WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        /*llamando de escenario de escala: cnmd10_comunes_escala_antiguedad_dias_asig_2*/
        dias_escenario = escala_antiguedad_dias_asig(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,ano_calculado);


            dias_cobro = devolver_dias_cobro_cnmd01(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
            monto_para_calculo = devolver_calculo_monto2_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
            monto_para_calculo = ((monto_para_calculo/dias_cobro)*dias_escenario);


        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf  = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_antiguedad_dias_asig(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_antiguedad_dias_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_antiguedad_dias_ded(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_antiguedad_dias_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   dias_cobro numeric(7,2) = 0;
   dias_escenario numeric(7,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;


   /*Escenario:cnmd10_comunes_escala_antiguedad_dias_ded */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_antiguedad_dias_ded WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        /*llamando de escenario de escala: cnmd10_comunes_escala_antiguedad_dias_ded_2*/
        dias_escenario = escala_antiguedad_dias_ded(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,ano_calculado);


            dias_cobro = devolver_dias_cobro_cnmd01(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
            monto_para_calculo = devolver_calculo_monto2_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
            monto_para_calculo = ((monto_para_calculo/dias_cobro)*dias_escenario);


        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf  = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_antiguedad_dias_ded(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_antiguedad_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_antiguedad_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_antiguedad_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   porcentaje numeric(5,2) = 0;
   porcentaje_escenario numeric(5,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;


   /*Escenario:cnmd10_comunes_escala_antiguedad_porcentaje_asig */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_antiguedad_porcentaje_asig WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        /*llamando de escenario de escala: cnmd10_comunes_escala_antiguedad_dias_asig_2*/
        porcentaje_escenario = escala_antiguedad_porcentaje_asig(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,ano_calculado);

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf  = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_antiguedad_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_antiguedad_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_antiguedad_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_antiguedad_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   porcentaje numeric(5,2) = 0;
   porcentaje_escenario numeric(5,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;


   /*Escenario:cnmd10_comunes_escala_antiguedad_porcentaje_ded */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_antiguedad_porcentaje_ded WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        /*llamando de escenario de escala: cnmd10_comunes_escala_antiguedad_dias_ded_2*/
        porcentaje_escenario = escala_antiguedad_porcentaje_ded(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,ano_calculado);

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf  = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_antiguedad_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_mes_dia_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_mes_dia_asig(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_mes_dia_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   mes_calculado integer = 0;
   dia_calculado integer = 0;
   dias_cobro numeric(7,2) = 0;
   dias_escenario numeric(7,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;
   xfhasta  date;


   /*Escenario:cnmd10_comunes_escala_mes_dia_asig */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_mes_dia_asig WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        xfhasta = (select (substr(vfecha_hasta::text,0,5)||'-' || '12-31')::date);
        ano_calculado=devolver_edad(xfhasta,fecha_ingreso,'ano');
        mes_calculado=devolver_edad(xfhasta,fecha_ingreso,'mes');
        dia_calculado=devolver_edad(xfhasta,fecha_ingreso,'dia');
        dias_escenario = escala_mes_dia_asig(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,ano_calculado,mes_calculado,dia_calculado);

            dias_cobro = devolver_dias_cobro_cnmd01(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
            monto_para_calculo = devolver_calculo_monto2_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
            monto_para_calculo = ((monto_para_calculo/dias_cobro)*dias_escenario);


        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_mes_dia_asig(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_mes_dia_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_mes_dia_ded(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_mes_dia_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   mes_calculado integer = 0;
   dia_calculado integer = 0;
   dias_cobro numeric(7,2) = 0;
   dias_escenario numeric(7,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;
   xfhasta date;

   /*Escenario:cnmd10_comunes_escala_mes_dia_ded */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_mes_dia_ded WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        xfhasta = (select (substr(vfecha_hasta::text,0,5)||'-' || '12-31')::date);
        ano_calculado=devolver_edad(xfhasta,fecha_ingreso,'ano');
        mes_calculado=devolver_edad(xfhasta,fecha_ingreso,'mes');
        dia_calculado=devolver_edad(xfhasta,fecha_ingreso,'dia');
        dias_escenario = escala_mes_dia_ded(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,ano_calculado,mes_calculado,dia_calculado);

            dias_cobro = devolver_dias_cobro_cnmd01(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
            monto_para_calculo = devolver_calculo_monto2_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
            monto_para_calculo = ((monto_para_calculo/dias_cobro)*dias_escenario);


        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_mes_dia_ded(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_sueldo_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_sueldo_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_sueldo_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   porcentaje_escenario numeric(2,2) = 0;
   cf_cd_ac integer = 0;
   sueldo_basico numeric(26,2);
   retornarf integer = 4;



   /*Escenario:cnmd10_comunes_escala_sueldo_bolivares_asig */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_sueldo_bolivares_asig WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        --fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        --ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        sueldo_basico=devolver_sueldo_basico_cnmd05(vcod_presi,vcod_entidad,vcod_tipo_inst, vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        monto_para_calculo = escala_sueldo_bolivares_asig(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,sueldo_basico);



        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                --monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                --monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_sueldo_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_sueldo_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_sueldo_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_sueldo_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   porcentaje_escenario numeric(2,2) = 0;
   cf_cd_ac integer = 0;
   sueldo_basico numeric(26,2);
   retornarf integer = 4;



   /*Escenario:cnmd10_comunes_escala_sueldo_bolivares_ded */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_sueldo_bolivares_ded WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        --fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        --ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        sueldo_basico=devolver_sueldo_basico_cnmd05(vcod_presi,vcod_entidad,vcod_tipo_inst, vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        monto_para_calculo = escala_sueldo_bolivares_ded(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,sueldo_basico);



        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                --monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                --monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_sueldo_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_sueldo_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_sueldo_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_sueldo_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   porcentaje_escenario numeric(5,2) = 0;
   cf_cd_ac integer = 0;
   sueldo_basico numeric(26,2);
   retornarf integer = 4;



   /*Escenario:cnmd10_comunes_escala_sueldo_porcentaje_asig */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_sueldo_porcentaje_asig WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        sueldo_basico=devolver_sueldo_basico_cnmd05(vcod_presi,vcod_entidad,vcod_tipo_inst, vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        porcentaje_escenario = escala_sueldo_porcentaje_asig(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,sueldo_basico);



        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_sueldo_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_escala_sueldo_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_escala_sueldo_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_escala_sueldo_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   porcentaje_escenario numeric(5,2) = 0;
   cf_cd_ac integer = 0;
   sueldo_basico numeric(26,2);
   retornarf integer = 4;



   /*Escenario:cnmd10_comunes_escala_sueldo_porcentaje_ded */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_escala_sueldo_porcentaje_ded WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        fecha_ingreso = devolver_fecha_ingreso_cnmd06_fichas(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        ano_calculado=devolver_edad(vfecha_hasta,fecha_ingreso,'ano');
        sueldo_basico=devolver_sueldo_basico_cnmd05(vcod_presi,vcod_entidad,vcod_tipo_inst, vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
        porcentaje_escenario = escala_sueldo_porcentaje_ded(Rescenario1.cod_dep,Rescenario1.cod_entidad,Rescenario1.cod_tipo_inst,Rescenario1.cod_inst,Rescenario1.cod_dep,Rescenario1.cod_tipo_nomina,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,sueldo_basico);



        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_escala_sueldo_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_porcentaje_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_porcentaje_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_porcentaje_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   tope numeric(26,2) = 0;
   porcentaje numeric(5,2) = 0;
   porcentaje_escenario numeric(5,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;



   /*Escenario:cnmd10_comunes_porcentaje_asignacion */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_porcentaje_asignacion WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        tope = Rescenario1.tope;
        porcentaje = Rescenario1.porcentaje;
        porcentaje_escenario = porcentaje;

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                if monto_para_calculo>tope then
                   monto_para_calculo=tope;
                end if;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                if monto_para_calculo>tope then
                   monto_para_calculo=tope;
                end if;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;

return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_porcentaje_asignacion(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;


-- Function: f_cnmd10_comunes_porcentaje_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_porcentaje_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_porcentaje_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   tope numeric(26,2) = 0;
   porcentaje numeric(5,2) = 0;
   porcentaje_escenario numeric(5,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;
   c_ap integer = 0;
   cod_transaccion_ap integer = 0;
   porcentaje_ap numeric(5,2) = 0;
   tope_ap numeric(26,2) = 0;

   /*Escenario:cnmd10_comunes_porcentaje_deduccion */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_porcentaje_deduccion WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        tope = Rescenario1.tope;
        porcentaje = Rescenario1.porcentaje;
        porcentaje_escenario = porcentaje;
        c_ap = (select count(*)           from cnmd10_aportes_patronales where cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=Rescenario1.cod_transaccion);
        cod_transaccion_ap = (select cod_transa_patrono from cnmd10_aportes_patronales where cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=Rescenario1.cod_transaccion);
        porcentaje_ap = (select porcentaje_patrono from cnmd10_aportes_patronales where cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=Rescenario1.cod_transaccion);
        tope_ap = (select tope_cuarta_semana from cnmd10_aportes_patronales where cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=Rescenario1.cod_transaccion);


        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                if monto_para_calculo>tope then
                   monto_para_calculo=tope;
                end if;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);

		if c_ap!=0 then
			monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_ap);
			if monto_para_calculo>tope_ap then
			   monto_para_calculo=tope_ap;
			end if;
			monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,cod_transaccion_ap,monto_para_calculo);
			retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, cod_transaccion_ap, monto_para_calculo);
		     end if;
           end if;/*fin if contar_codi*/

        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                if monto_para_calculo>tope then
                   monto_para_calculo=tope;
                end if;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);

             if c_ap!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_ap);
                if monto_para_calculo>tope_ap then
                   monto_para_calculo=tope_ap;
                end if;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,cod_transaccion_ap,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, cod_transaccion_ap, monto_para_calculo);
             end if;

        end if;/*fin cf_cd_ca!=0 */




end loop;
close Cescenario1;

return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_porcentaje_deduccion(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_puestos_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_puestos_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_puestos_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;



   /*Escenario:cnmd10_comunes_puestos_bolivares_asig */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_puestos_bolivares_asig WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
        monto_para_calculo=devolver_monto_puesto(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina, vcod_cargo,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_puestos_bolivares_asig(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_puestos_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_puestos_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_puestos_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;



   /*Escenario:cnmd10_comunes_puestos_bolivares_ded */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_puestos_bolivares_ded WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
        monto_para_calculo=devolver_monto_puesto_ded(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina, vcod_cargo,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_puestos_bolivares_ded(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_puestos_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_puestos_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_puestos_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   porcentaje_escenario numeric(5,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;

   /*Escenario:cnmd10_comunes_escala_sueldo_porcentaje_asig */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_puestos_porcentaje_asig WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        porcentaje_escenario = devolver_porcentaje_puesto(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina, vcod_cargo,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_asig_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_puestos_porcentaje_asig(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_puestos_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_puestos_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_puestos_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   fecha_ingreso date;
   ano_calculado integer = 0;
   porcentaje_escenario numeric(5,2) = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;

   /*Escenario:cnmd10_comunes_escala_sueldo_porcentaje_ded */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_puestos_porcentaje_ded WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        porcentaje_escenario = devolver_porcentaje_puesto_ded(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina, vcod_cargo,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_calculo_monto_porce_ded_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,porcentaje_escenario);
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_puestos_porcentaje_ded(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_comunes_sueldo_sugerido(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_comunes_sueldo_sugerido(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_comunes_sueldo_sugerido(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   nsueldo_basico numeric(26,2) = 0;
   contar_codi integer = 0;
   sexo character varying(1);
   cf_cd_ac integer = 0;
   retornarf integer = 4;

        /*Escenario:cnmd10_comunes_sueldo_sugerido  */
	Rescenario1 record;
        Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_sueldo_sugerido  WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=cod_tipo_inst and cod_inst=cod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;
        monto_para_calculo = Rescenario1.sueldo_sugerido;
        nsueldo_basico = devolver_sueldo_basico_cnmd05(vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina, vcod_cargo, vcod_ficha);

        if nsueldo_basico < monto_para_calculo then
           monto_para_calculo = monto_para_calculo - nsueldo_basico;
        else
           monto_para_calculo = 0;
        end if;

	cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
  	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
            monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	    retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */
end loop;
close Cescenario1;
return retornarf;
end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_comunes_sueldo_sugerido(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_individual_bolivares(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_individual_bolivares(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_individual_bolivares(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;
   cantidad_horas numeric(7,2) = 0;
   horas_laborablesc numeric(3,2) = 0;
   dias_cobroc integer = 0;

   /*Escenario:cnmd10_individual_bolivares */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_individual_bolivares WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        cantidad_horas = (SELECT cantidad FROM cnmd10_individual_bolivares_cantidad WHERE  cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_cargo=vcod_cargo and cod_ficha=vcod_ficha and cod_tipo_transaccion=Rescenario1.cod_tipo_transaccion and cod_transaccion=Rescenario1.cod_transaccion);

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo =  Rescenario1.monto * cantidad_horas ;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo =  Rescenario1.monto * cantidad_horas ;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_individual_bolivares(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_individual_dias(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_individual_dias(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_individual_dias(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;
   cantidad_dias numeric(7,2) = 0;
   dias_cobroc integer = 0;

   /*Escenario:cnmd10_individual_dias */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_individual_dias WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        cantidad_dias = (SELECT cantidad FROM cnmd10_individual_dias_cantidad WHERE  cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_cargo=vcod_cargo and cod_ficha=vcod_ficha and cod_tipo_transaccion=Rescenario1.cod_tipo_transaccion and cod_transaccion=Rescenario1.cod_transaccion);
        dias_cobroc =(SELECT dias_cobro FROM cnmd01 WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina);

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_monto_para_calculo_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
                monto_para_calculo = monto_para_calculo / dias_cobroc;
                monto_para_calculo = monto_para_calculo * cantidad_dias;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_monto_para_calculo_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
                monto_para_calculo = monto_para_calculo / dias_cobroc;
                monto_para_calculo = monto_para_calculo * cantidad_dias;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_individual_dias(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: f_cnmd10_individual_porcentaje_horas(integer, integer, integer, integer, integer, integer, integer, integer, date)

-- DROP FUNCTION f_cnmd10_individual_porcentaje_horas(integer, integer, integer, integer, integer, integer, integer, integer, date);

CREATE OR REPLACE FUNCTION f_cnmd10_individual_porcentaje_horas(integer, integer, integer, integer, integer, integer, integer, integer, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_hasta     alias for $9;
   retornar integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   contar_codi integer = 0;
   cf_cd_ac integer = 0;
   retornarf integer = 4;
   cantidad_horas numeric(7,2) = 0;
   horas_laborablesc numeric(3,2) = 0;
   dias_cobroc integer = 0;

   /*Escenario:cnmd10_comunes_escala_sueldo_porcentaje_asig */
	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_individual_porcentaje_horas WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

        cantidad_horas = (SELECT cantidad FROM cnmd10_individual_porcentaje_horas_cantidad WHERE  cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_cargo=vcod_cargo and cod_ficha=vcod_ficha and cod_tipo_transaccion=Rescenario1.cod_tipo_transaccion and cod_transaccion=Rescenario1.cod_transaccion);
        dias_cobroc =(SELECT dias_cobro FROM cnmd01 WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina);
        horas_laborablesc =(SELECT horas_laborables FROM cnmd01 WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina);

        cf_cd_ac=condicion_varias_escenario(Rescenario1.cod_frecuencia, Rescenario1.cod_condicion, Rescenario1.activar_frecuencia_eventual);
        if cf_cd_ac = 2 then
           contar_codi=cant_trans_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.codi_tipo_transaccion,Rescenario1.codi_transaccion);
           if contar_codi!=0 then
                monto_para_calculo = devolver_monto_para_calculo_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
                monto_para_calculo = monto_para_calculo / dias_cobroc;
                monto_para_calculo = monto_para_calculo / horas_laborablesc;
                monto_para_calculo = monto_para_calculo * (Rescenario1.porcentaje/100);
                monto_para_calculo = monto_para_calculo * cantidad_horas;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
           end if;/*fin if contar_codi*/
        end if;/*fin cf_cd_ac*/

        if cf_cd_ac = 1 then
                monto_para_calculo = devolver_monto_para_calculo_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
                monto_para_calculo = monto_para_calculo / dias_cobroc;
                monto_para_calculo = monto_para_calculo / horas_laborablesc;
                monto_para_calculo = monto_para_calculo * (Rescenario1.porcentaje/100);
                monto_para_calculo = monto_para_calculo * cantidad_horas;
                monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	        retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, Rescenario1.cod_transaccion, monto_para_calculo);
        end if;/*fin cf_cd_ca!=0 */



end loop;
close Cescenario1;
return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION f_cnmd10_individual_porcentaje_horas(integer, integer, integer, integer, integer, integer, integer, integer, date) OWNER TO sisap;

-- Function: marcar_transanciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION marcar_transanciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION marcar_transanciones_actuales(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer, pcod_tipo_transaccion integer, pcod_transaccion integer)
  RETURNS integer AS
$BODY$
DECLARE
retornar integer =0;

begin

	UPDATE cnmd07_transacciones_actuales SET marca_fin_descuento='*'  WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND cod_cargo=pcod_cargo AND cod_ficha=pcod_ficha AND cod_tipo_transaccion=pcod_tipo_transaccion AND cod_transaccion=pcod_transaccion and saldo=0;

return retornar;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION marcar_transanciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: cnmd10com52semporceded(integer, integer, integer, integer, integer, integer, integer, integer, date, date)

-- DROP FUNCTION cnmd10com52semporceded(integer, integer, integer, integer, integer, integer, integer, integer, date, date);

CREATE OR REPLACE FUNCTION cnmd10com52semporceded(integer, integer, integer, integer, integer, integer, integer, integer, date, date)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;
   vcod_ficha       alias for $7;
   vcod_cargo       alias for $8;
   vfecha_desde     alias for $9;
   vfecha_hasta     alias for $10;
   retornar integer = 0;
   cantidad1 integer = 0;
   cant_1 integer = 0;
   cant_2 integer = 0;
   monto_para_calculo numeric(26,2) = 0;
   vmonto_cuota numeric (26,2) = 0;
   vsueldo_basico numeric (26,2) = 0;
   dias_cobro integer = 0;
   dias_tie integer = 0;
   dias_falta integer = 0;
   prorrateo integer = 0;
   cant_lunes integer = 0;
   CODFRECUENCIA integer = 0;
   monto_calculado numeric (26,2);
   retornarf integer = 4;
   c_ap integer = 0;
   cod_transaccion_ap integer = 0;
   porcentaje_ap numeric(5,2) = 0;
   tope_cuarta_ap numeric(26,2) = 0;
   tope_quinta_ap numeric(26,2) = 0;
   porcentaje  numeric(5,2) = 0;

	     Rescenario1 record;
             Cescenario1 cursor (vcod_presi integer, vcod_entidad integer, vcod_tipo_inst integer, vcod_inst integer, vcod_dep integer, vcod_tipo_nomina integer) for SELECT * FROM cnmd10_comunes_52semanas_porcentaje_ded WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina;
begin
open Cescenario1 (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
loop
	fetch Cescenario1 into Rescenario1;
	exit when not found;

                cant_1=(SELECT count(*) FROM cnmd09_asignacion_calcula_deduccion_2 WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and  cod_dep=vcod_dep and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=Rescenario1.cod_tipo_transaccion and cod_transaccion=Rescenario1.cod_transaccion);
		if cant_1 !=0 then
		      vmonto_cuota = devolver_montocuota_porc_ded_cnmd07_transacciones_actuales(vcod_presi,vcod_entidad, vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
		      monto_para_calculo = vmonto_cuota;
		end if;

		/*cant_2=(SELECT incluye_sueldo_basico FROM cnmd09_asignacion_calcula_deduccion WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and  cod_dep=vcod_dep and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=Rescenario1.cod_tipo_transaccion and  cod_transaccion=Rescenario1.cod_transaccion);
		if cant_2 is null then
		      vsueldo_basico = devolver_sueldo_basico_cnmd05(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
		      monto_para_calculo = monto_para_calculo + vsueldo_basico;
		else
		      if cant_2 !=2 then
			  vsueldo_basico = devolver_sueldo_basico_cnmd05(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
			  monto_para_calculo = monto_para_calculo + vsueldo_basico;
			  --monto_para_calculo=77;
		      end if;
		end if;*/
           --monto_para_calculo=1;
           cant_lunes = (SELECT numero_lunes FROM cnmd09_lunes_ejercicio WHERE ano=substr(vfecha_desde::text,0,5)::integer and mes=substr(vfecha_desde::text,6,2)::integer);
           CODFRECUENCIA = (SELECT frecuencia_cobro FROM cnmd01 WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina);
           porcentaje = (Rescenario1.porcentaje/100);
           if CODFRECUENCIA=1 then
		monto_para_calculo = (monto_para_calculo * 7) * porcentaje;
           elsif CODFRECUENCIA=2 then
		monto_para_calculo = monto_para_calculo  * porcentaje;
           elsif CODFRECUENCIA=3 then
		monto_para_calculo = (((monto_para_calculo * 24)/52) * porcentaje) * (cant_lunes / 2);
           elsif CODFRECUENCIA=4 then
		monto_para_calculo = (((monto_para_calculo * 12)/52) * porcentaje) * cant_lunes;
           elsif CODFRECUENCIA=5 then
                cant_lunes = (SELECT sum(numero_lunes) FROM cnmd09_lunes_ejercicio WHERE ano=substr(vfecha_desde::text,0,5)::integer and mes>=substr(vfecha_desde::text,6,2)::integer and mes<=substr(vfecha_hasta::text,6,2)::integer);
		monto_para_calculo = (((monto_para_calculo * 6)/52) * porcentaje) * cant_lunes;
           elsif CODFRECUENCIA=6 then
                cant_lunes = (SELECT sum(numero_lunes) FROM cnmd09_lunes_ejercicio WHERE ano=substr(vfecha_desde::text,0,5)::integer and mes>=substr(vfecha_desde::text,6,2)::integer and mes<=substr(vfecha_hasta::text,6,2)::integer);
		monto_para_calculo = (((monto_para_calculo * 4)/52) * porcentaje) * cant_lunes;
           end if;

           if cant_lunes = 4 and monto_para_calculo > Rescenario1.tope_cuarta_semana then
		monto_para_calculo = Rescenario1.tope_cuarta_semana;
	   elsif cant_lunes = 5 and monto_para_calculo > Rescenario1.tope_quinta_semana then
		monto_para_calculo = Rescenario1.tope_quinta_semana;
	   end if;

	   monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
	   retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion, monto_para_calculo);
           /*hasta aqui la primera parte*/

           /*desde aqui comienza la segunda parte para los aportes patronales*/
           c_ap = (select count(*)           from cnmd10_aportes_patronales where cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=Rescenario1.cod_transaccion);
           cod_transaccion_ap = (select cod_transa_patrono from cnmd10_aportes_patronales where cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=Rescenario1.cod_transaccion);
           porcentaje_ap = (select porcentaje_patrono from cnmd10_aportes_patronales where cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=Rescenario1.cod_transaccion);
           tope_cuarta_ap = (select tope_cuarta_semana from cnmd10_aportes_patronales where cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=Rescenario1.cod_transaccion);
           tope_quinta_ap = (select tope_quinta_semana from cnmd10_aportes_patronales where cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and cod_dep=vcod_dep  and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=Rescenario1.cod_transaccion);


           if c_ap != 0 then
                cant_1=(SELECT count(*) FROM cnmd09_asignacion_calcula_deduccion_2 WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and  cod_dep=vcod_dep and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=Rescenario1.cod_tipo_transaccion and cod_transaccion=Rescenario1.cod_transaccion);
		if cant_1 !=0 then
		      vmonto_cuota = devolver_montocuota_porc_ded_cnmd07_transacciones_actuales(vcod_presi,vcod_entidad, vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion);
		      monto_para_calculo = vmonto_cuota;
		end if;

		/*cant_2=(SELECT incluye_sueldo_basico FROM cnmd09_asignacion_calcula_deduccion WHERE cod_presi=vcod_presi and cod_entidad=vcod_entidad and cod_tipo_inst=vcod_tipo_inst and cod_inst=vcod_inst and  cod_dep=vcod_dep and cod_tipo_nomina=vcod_tipo_nomina and cod_tipo_transaccion=Rescenario1.cod_tipo_transaccion and  cod_transaccion=Rescenario1.cod_transaccion);
		if cant_2 is null then
		      vsueldo_basico = devolver_sueldo_basico_cnmd05(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
		      monto_para_calculo = monto_para_calculo + vsueldo_basico;
		else
		      if cant_2 !=2 then
			  vsueldo_basico = devolver_sueldo_basico_cnmd05(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha);
			  monto_para_calculo = monto_para_calculo + vsueldo_basico;
		      end if;
		end if;*/
           --monto_para_calculo=1;
		   cant_lunes = (SELECT numero_lunes FROM cnmd09_lunes_ejercicio WHERE ano=substr(vfecha_desde::text,0,5)::integer and mes=substr(vfecha_desde::text,6,2)::integer);
		   CODFRECUENCIA = (SELECT frecuencia_cobro FROM cnmd01 WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina);
                   porcentaje = (porcentaje_ap/100);
		   if CODFRECUENCIA=1 then
			monto_para_calculo = (monto_para_calculo * 7) * porcentaje;
		   elsif CODFRECUENCIA=2 then
			monto_para_calculo = monto_para_calculo  * porcentaje;
		   elsif CODFRECUENCIA=3 then
			monto_para_calculo = (((monto_para_calculo * 24)/52) * porcentaje)*(cant_lunes / 2);
		   elsif CODFRECUENCIA=4 then
			monto_para_calculo = (((monto_para_calculo * 12)/52) * porcentaje)*cant_lunes;
		   elsif CODFRECUENCIA=5 then
		        cant_lunes = (SELECT sum(numero_lunes) FROM cnmd09_lunes_ejercicio WHERE ano=substr(vfecha_desde::text,0,5)::integer and mes>=substr(vfecha_desde::text,6,2)::integer and mes<=substr(vfecha_hasta::text,6,2)::integer);
			monto_para_calculo = (((monto_para_calculo * 6)/52) * porcentaje) * cant_lunes;
		   elsif CODFRECUENCIA=6 then
		        cant_lunes = (SELECT sum(numero_lunes) FROM cnmd09_lunes_ejercicio WHERE ano=substr(vfecha_desde::text,0,5)::integer and mes>=substr(vfecha_desde::text,6,2)::integer and mes<=substr(vfecha_hasta::text,6,2)::integer);
			monto_para_calculo = (((monto_para_calculo * 4)/52) * porcentaje) * cant_lunes;
		   end if;

		   if cant_lunes = 4 and monto_para_calculo > tope_cuarta_ap then
			monto_para_calculo = tope_cuarta_ap;
		   elsif cant_lunes = 5 and monto_para_calculo > tope_quinta_ap then
			monto_para_calculo = tope_quinta_ap;
		   end if;

		   monto_para_calculo = devolver_calculo_monto_escenario(vcod_presi, vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina,vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion,Rescenario1.cod_transaccion,monto_para_calculo);
		   retornarf = update_monto_cuota_transanciones_actuales(vcod_presi,vcod_entidad,vcod_tipo_inst,vcod_inst,vcod_dep,vcod_tipo_nomina, vcod_cargo,vcod_ficha,Rescenario1.cod_tipo_transaccion, cod_transaccion_ap, monto_para_calculo);

           end if;/* fin c_ap*/



end loop;
close Cescenario1;

return retornarf;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION cnmd10com52semporceded(integer, integer, integer, integer, integer, integer, integer, integer, date, date) OWNER TO sisap;




-- Function: proceso_parte_individual(integer, integer, integer, integer, integer, integer, date, date)

-- DROP FUNCTION proceso_parte_individual(integer, integer, integer, integer, integer, integer, date, date);

CREATE OR REPLACE FUNCTION proceso_parte_individual(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pfecha_periodo_desde date, pfecha_periodo_hasta date)
  RETURNS integer[] AS
$BODY$
DECLARE
   SUELDO numeric (26,2) = 0;
   DIAS_F integer;
   DIAS_IE integer;
   dias_cobro integer;
   dias_cobro1 integer;
   dias_cobro2 integer;
   registrada_ctqcct integer;
   prorrateo integer;
   calc_monto_cuota numeric (26,2);
   monto_retornar  numeric(26,2);
   existe integer = 0;
   llamadof integer[];

	     Rcnmd06_fichasv record;
             Ccnmd06_fichasv cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT * FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_ficha IN (SELECT x.cod_ficha FROM cnmd10_individual_porcentaje_horas_cantidad x  WHERE x.cod_presi=Pcod_presi AND x.cod_entidad=Pcod_entidad AND x.cod_tipo_inst=Pcod_tipo_inst AND x.cod_inst=Pcod_inst AND x.cod_dep=Pcod_dep AND x.cod_tipo_nomina=Pcod_tipo_nomina) AND cod_tipo_nomina=Pcod_tipo_nomina  OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta AND cod_tipo_nomina=Pcod_tipo_nomina);
             Rcnmd06_fichasvc record;
             Ccnmd06_fichasvc cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT count(*) as cantidad FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_ficha IN (SELECT x.cod_ficha FROM cnmd10_individual_porcentaje_horas_cantidad x  WHERE x.cod_presi=Pcod_presi AND x.cod_entidad=Pcod_entidad AND x.cod_tipo_inst=Pcod_tipo_inst AND x.cod_inst=Pcod_inst AND x.cod_dep=Pcod_dep AND x.cod_tipo_nomina=Pcod_tipo_nomina) AND cod_tipo_nomina=Pcod_tipo_nomina OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta AND cod_tipo_nomina=Pcod_tipo_nomina);
begin
            open Ccnmd06_fichasvc (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
		loop
			fetch Ccnmd06_fichasvc into Rcnmd06_fichasvc;
			exit when not found;
			if Rcnmd06_fichasvc.cantidad!=0 then
				open Ccnmd06_fichasv (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
				loop
					fetch Ccnmd06_fichasv into Rcnmd06_fichasv;
					exit when not found;
		                           /*ASIGNACIONES BOLIVARES*/
			                   llamadof[0]  = f_cnmd10_individual_porcentaje_horas                              (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);

				end loop;
				close Ccnmd06_fichasv;
                    end if;/*fin cantidad!=0*/
                    end loop;
                close Ccnmd06_fichasvc;

	--return monto_retornar;
	return llamadof;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION proceso_parte_individual(integer, integer, integer, integer, integer, integer, date, date) OWNER TO sisap;

-- Function: proceso_parte_individual2(integer, integer, integer, integer, integer, integer, date, date)

-- DROP FUNCTION proceso_parte_individual2(integer, integer, integer, integer, integer, integer, date, date);

CREATE OR REPLACE FUNCTION proceso_parte_individual2(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pfecha_periodo_desde date, pfecha_periodo_hasta date)
  RETURNS integer[] AS
$BODY$
DECLARE
   SUELDO numeric (26,2) = 0;
   DIAS_F integer;
   DIAS_IE integer;
   dias_cobro integer;
   dias_cobro1 integer;
   dias_cobro2 integer;
   registrada_ctqcct integer;
   prorrateo integer;
   calc_monto_cuota numeric (26,2);
   monto_retornar  numeric(26,2);
   existe integer = 0;
   llamadof integer[];

	     Rcnmd06_fichasv record;
             Ccnmd06_fichasv cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT * FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_ficha IN (SELECT x.cod_ficha FROM cnmd10_individual_bolivares_cantidad x  WHERE x.cod_presi=Pcod_presi AND x.cod_entidad=Pcod_entidad AND x.cod_tipo_inst=Pcod_tipo_inst AND x.cod_inst=Pcod_inst AND x.cod_dep=Pcod_dep AND x.cod_tipo_nomina=Pcod_tipo_nomina) AND cod_tipo_nomina=Pcod_tipo_nomina  OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta AND cod_tipo_nomina=Pcod_tipo_nomina);
             Rcnmd06_fichasvc record;
             Ccnmd06_fichasvc cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT count(*) as cantidad FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_ficha IN (SELECT x.cod_ficha FROM cnmd10_individual_bolivares_cantidad x  WHERE x.cod_presi=Pcod_presi AND x.cod_entidad=Pcod_entidad AND x.cod_tipo_inst=Pcod_tipo_inst AND x.cod_inst=Pcod_inst AND x.cod_dep=Pcod_dep AND x.cod_tipo_nomina=Pcod_tipo_nomina) AND cod_tipo_nomina=Pcod_tipo_nomina OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta AND cod_tipo_nomina=Pcod_tipo_nomina);
begin
            open Ccnmd06_fichasvc (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
		loop
			fetch Ccnmd06_fichasvc into Rcnmd06_fichasvc;
			exit when not found;
			if Rcnmd06_fichasvc.cantidad!=0 then
				open Ccnmd06_fichasv (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
				loop
					fetch Ccnmd06_fichasv into Rcnmd06_fichasv;
					exit when not found;
		                           /*ASIGNACIONES BOLIVARES*/
			                   llamadof[0]  = f_cnmd10_individual_bolivares                              (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);

				end loop;
				close Ccnmd06_fichasv;
                    end if;/*fin cantidad!=0*/
                    end loop;
                close Ccnmd06_fichasvc;

	--return monto_retornar;
	return llamadof;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION proceso_parte_individual2(integer, integer, integer, integer, integer, integer, date, date) OWNER TO sisap;

-- Function: proceso_parte_individual3(integer, integer, integer, integer, integer, integer, date, date)

-- DROP FUNCTION proceso_parte_individual3(integer, integer, integer, integer, integer, integer, date, date);

CREATE OR REPLACE FUNCTION proceso_parte_individual3(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pfecha_periodo_desde date, pfecha_periodo_hasta date)
  RETURNS integer[] AS
$BODY$
DECLARE
   SUELDO numeric (26,2) = 0;
   DIAS_F integer;
   DIAS_IE integer;
   dias_cobro integer;
   dias_cobro1 integer;
   dias_cobro2 integer;
   registrada_ctqcct integer;
   prorrateo integer;
   calc_monto_cuota numeric (26,2);
   monto_retornar  numeric(26,2);
   existe integer = 0;
   llamadof integer[];

	     Rcnmd06_fichasv record;
             Ccnmd06_fichasv cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT * FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_ficha IN (SELECT x.cod_ficha FROM cnmd10_individual_dias_cantidad x  WHERE x.cod_presi=Pcod_presi AND x.cod_entidad=Pcod_entidad AND x.cod_tipo_inst=Pcod_tipo_inst AND x.cod_inst=Pcod_inst AND x.cod_dep=Pcod_dep AND x.cod_tipo_nomina=Pcod_tipo_nomina) AND cod_tipo_nomina=Pcod_tipo_nomina  OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta AND cod_tipo_nomina=Pcod_tipo_nomina);
             Rcnmd06_fichasvc record;
             Ccnmd06_fichasvc cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT count(*) as cantidad FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_ficha IN (SELECT x.cod_ficha FROM cnmd10_individual_dias_cantidad x  WHERE x.cod_presi=Pcod_presi AND x.cod_entidad=Pcod_entidad AND x.cod_tipo_inst=Pcod_tipo_inst AND x.cod_inst=Pcod_inst AND x.cod_dep=Pcod_dep AND x.cod_tipo_nomina=Pcod_tipo_nomina) AND cod_tipo_nomina=Pcod_tipo_nomina OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta AND cod_tipo_nomina=Pcod_tipo_nomina);
begin
            open Ccnmd06_fichasvc (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
		loop
			fetch Ccnmd06_fichasvc into Rcnmd06_fichasvc;
			exit when not found;
			if Rcnmd06_fichasvc.cantidad!=0 then
				open Ccnmd06_fichasv (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
				loop
					fetch Ccnmd06_fichasv into Rcnmd06_fichasv;
					exit when not found;
		                           /*ASIGNACIONES BOLIVARES*/
			                   llamadof[0]  = f_cnmd10_individual_dias                              (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);

				end loop;
				close Ccnmd06_fichasv;
                    end if;/*fin cantidad!=0*/
                    end loop;
                close Ccnmd06_fichasvc;

	--return monto_retornar;
	return llamadof;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION proceso_parte_individual3(integer, integer, integer, integer, integer, integer, date, date) OWNER TO sisap;

-- Function: proceso_prenomina_1(integer, integer, integer, integer, integer, integer, date, date)

-- DROP FUNCTION proceso_prenomina_1(integer, integer, integer, integer, integer, integer, date, date);

CREATE OR REPLACE FUNCTION proceso_prenomina_1(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pfecha_periodo_desde date, pfecha_periodo_hasta date)
  RETURNS integer[] AS
$BODY$
DECLARE
   SUELDO numeric (26,2) = 0;
   DIAS_F integer;
   DIAS_IE integer;
   dias_cobro integer;
   dias_cobro1 integer;
   dias_cobro2 integer;
   registrada_ctqcct integer;
   prorrateo integer;
   calc_monto_cuota numeric (26,2);
   monto_retornar  numeric(26,2);
   existe integer = 0;
   llamadof integer[];
   return_crear0 integer = 999;

	     Rcnmd06_fichasv record;
             Ccnmd06_fichasv cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT * FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina  OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta AND cod_tipo_nomina=Pcod_tipo_nomina);
             Rcnmd06_fichasvc record;
             Ccnmd06_fichasvc cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT count(*) as cantidad FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta AND cod_tipo_nomina=Pcod_tipo_nomina);
             Rcnmd07_trans_actuales record;
             Ccnmd07_trans_actuales cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer) for SELECT * FROM v_cnmd07_transacciones_actuales_deno WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina;
             Rcnmd09_tqan record;
             Ccnmd09_tqan cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer) for SELECT * FROM cnmd09_transa_queno_actuan_nomina where cod_presi=Pcod_presi and cod_entidad=Pcod_entidad and cod_tipo_inst=Pcod_tipo_inst and cod_inst=Pcod_inst and cod_dep=Pcod_dep and cod_tipo_nomina=Pcod_tipo_nomina;
begin
            return_crear0 = crear_transaccion_cero(Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina);

            open Ccnmd09_tqan (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina);
		loop
			fetch Ccnmd09_tqan into Rcnmd09_tqan;
			exit when not found;
			    DELETE FROM cnmd07_transacciones_actuales WHERE cod_presi=Rcnmd09_tqan.cod_presi AND cod_entidad=Rcnmd09_tqan.cod_entidad AND cod_tipo_inst=Rcnmd09_tqan.cod_tipo_inst AND cod_inst=Rcnmd09_tqan.cod_inst AND cod_dep=Rcnmd09_tqan.cod_dep AND cod_tipo_nomina=Rcnmd09_tqan.cod_tipo_nomina AND cod_tipo_transaccion=Rcnmd09_tqan.cod_tipo_transaccion AND cod_transaccion=Rcnmd09_tqan.cod_transaccion;
		end loop;
	   close Ccnmd09_tqan;
            open Ccnmd06_fichasvc (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
		loop
			fetch Ccnmd06_fichasvc into Rcnmd06_fichasvc;
			exit when not found;
			if Rcnmd06_fichasvc.cantidad!=0 then
				open Ccnmd06_fichasv (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
				loop
					fetch Ccnmd06_fichasv into Rcnmd06_fichasv;
					exit when not found;
					/*SUELDO = devolver_sueldo_basico_cnmd05(Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_cargo, Rcnmd06_fichasv.cod_ficha);
                                        DIAS_F = devolver_dias_faltas(Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_cargo, Rcnmd06_fichasv.cod_ficha);
                                        DIAS_IE = devolver_dias_t_i_e(Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_cargo, Rcnmd06_fichasv.cod_ficha);
                                        dias_cobro =(SELECT dias_cobro FROM cnmd01 WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina);
                                        dias_cobro1=DIAS_F;
                                        dias_cobro2=DIAS_IE;
                                        if DIAS_F = 0 then
                                           SUELDO = (SUELDO/dias_cobro);
                                           SUELDO = (SUELDO*DIAS_IE);
                                        else
                                           SUELDO = (SUELDO/dias_cobro);
                                           SUELDO = (SUELDO*DIAS_F);
                                        end if;*/
					open Ccnmd07_trans_actuales (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina);
					loop
					    fetch Ccnmd07_trans_actuales into Rcnmd07_trans_actuales;
					    exit when not found;
					    registrada_ctqcct = (SELECT count(*) FROM cnmd09_traba_queno_cobran_cancela_transa WHERE cod_presi=Rcnmd07_trans_actuales.cod_presi AND cod_entidad=Rcnmd07_trans_actuales.cod_entidad AND cod_tipo_inst=Rcnmd07_trans_actuales.cod_tipo_inst AND cod_inst=Rcnmd07_trans_actuales.cod_inst AND cod_dep=Rcnmd07_trans_actuales.cod_dep AND cod_tipo_nomina=Rcnmd07_trans_actuales.cod_tipo_nomina AND cod_cargo=Rcnmd07_trans_actuales.cod_cargo AND cod_ficha=Rcnmd07_trans_actuales.cod_ficha AND cod_tipo_transaccion=Rcnmd07_trans_actuales.cod_tipo_transaccion AND cod_transaccion=Rcnmd07_trans_actuales.cod_transaccion);
					    if registrada_ctqcct!=0 then
					       DELETE FROM cnmd07_transacciones_actuales WHERE cod_presi=Rcnmd07_trans_actuales.cod_presi AND cod_entidad=Rcnmd07_trans_actuales.cod_entidad AND cod_tipo_inst=Rcnmd07_trans_actuales.cod_tipo_inst AND cod_inst=Rcnmd07_trans_actuales.cod_inst AND cod_dep=Rcnmd07_trans_actuales.cod_dep AND cod_tipo_nomina=Rcnmd07_trans_actuales.cod_tipo_nomina AND cod_cargo=Rcnmd07_trans_actuales.cod_cargo AND cod_ficha=Rcnmd07_trans_actuales.cod_ficha AND cod_tipo_transaccion=Rcnmd07_trans_actuales.cod_tipo_transaccion AND cod_transaccion=Rcnmd07_trans_actuales.cod_transaccion;
					    end if;
					    if Rcnmd07_trans_actuales.uso_transaccion = 9 then
						/*INSERT INTO cnmd07_abono_cuenta(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion_padre, cod_transaccion_padre,monto)
						    VALUES (Rcnmd07_trans_actuales.cod_presi, Rcnmd07_trans_actuales.cod_entidad,Rcnmd07_trans_actuales.cod_tipo_inst ,Rcnmd07_trans_actuales.cod_inst, Rcnmd07_trans_actuales.cod_dep, Rcnmd07_trans_actuales.cod_tipo_nomina,Rcnmd07_trans_actuales.cod_cargo, Rcnmd07_trans_actuales.cod_ficha,Rcnmd07_trans_actuales.cod_tipo_transaccion_padre,Rcnmd07_trans_actuales.cod_transaccion_padre,
						     Rcnmd07_trans_actuales.AQUI_FALTA_MONTOA_GUARDAR);*/
						    if dias_cobro1 != 0 then
							   prorrateo = cant_prorrateo(Rcnmd07_trans_actuales.cod_presi,Rcnmd07_trans_actuales.cod_entidad,Rcnmd07_trans_actuales.cod_tipo_inst,Rcnmd07_trans_actuales.cod_inst,Rcnmd07_trans_actuales.cod_dep,Rcnmd07_trans_actuales.cod_tipo_nomina,Rcnmd07_trans_actuales.cod_tipo_transaccion,Rcnmd07_trans_actuales.cod_transaccion);
							if prorrateo !=0 then
							    --INSERT INTO cnmd07_transacciones_suspendidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_cancelar, numero_cuotas_canceladas, monto_cuota, saldo, marca_fin_descuento, fecha_proceso, username) VALUES (Rcnmd07_trans_actuales.cod_presi, Rcnmd07_trans_actuales.cod_entidad, Rcnmd07_trans_actuales.cod_tipo_inst, Rcnmd07_trans_actuales.cod_inst, Rcnmd07_trans_actuales.cod_dep, Rcnmd07_trans_actuales.cod_tipo_nomina, Rcnmd07_trans_actuales.cod_cargo, Rcnmd07_trans_actuales.cod_ficha, Rcnmd07_trans_actuales.cod_tipo_transaccion, Rcnmd07_trans_actuales.cod_transaccion, Rcnmd07_trans_actuales.fecha_transaccion, Rcnmd07_trans_actuales.monto_original, Rcnmd07_trans_actuales.numero_cuotas_descontar, Rcnmd07_trans_actuales.numero_cuotas_cancelar, Rcnmd07_trans_actuales.numero_cuotas_canceladas, Rcnmd07_trans_actuales.monto_cuota, Rcnmd07_trans_actuales.saldo, Rcnmd07_trans_actuales.marca_fin_descuento, Rcnmd07_trans_actuales.fecha_proceso, Rcnmd07_trans_actuales.username);
							    --INSERT INTO cnmd07_transacciones_quecobran_incompleto(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_cancelar, numero_cuotas_canceladas, monto_cuota, saldo, marca_fin_descuento, fecha_proceso, username) VALUES (Rcnmd07_trans_actuales.cod_presi, Rcnmd07_trans_actuales.cod_entidad, Rcnmd07_trans_actuales.cod_tipo_inst, Rcnmd07_trans_actuales.cod_inst, Rcnmd07_trans_actuales.cod_dep, Rcnmd07_trans_actuales.cod_tipo_nomina, Rcnmd07_trans_actuales.cod_cargo, Rcnmd07_trans_actuales.cod_ficha, Rcnmd07_trans_actuales.cod_tipo_transaccion, Rcnmd07_trans_actuales.cod_transaccion, Rcnmd07_trans_actuales.fecha_transaccion, Rcnmd07_trans_actuales.monto_original, Rcnmd07_trans_actuales.numero_cuotas_descontar, Rcnmd07_trans_actuales.numero_cuotas_cancelar, Rcnmd07_trans_actuales.numero_cuotas_canceladas, Rcnmd07_trans_actuales.monto_cuota, Rcnmd07_trans_actuales.saldo, Rcnmd07_trans_actuales.marca_fin_descuento, Rcnmd07_trans_actuales.fecha_proceso, Rcnmd07_trans_actuales.username);
							else
							    --calc_monto_cuota=((Rcnmd07_trans_actuales.monto_cuota/dias_cobro)*dias_cobro1);
							    --UPDATE cnmd07_transacciones_actuales SET monto_cuota=calc_monto_cuota  WHERE cod_presi=Rcnmd07_trans_actuales.cod_presi AND cod_entidad=Rcnmd07_trans_actuales.cod_entidad AND cod_tipo_inst=Rcnmd07_trans_actuales.cod_tipo_inst AND cod_inst=Rcnmd07_trans_actuales.cod_inst AND cod_dep=Rcnmd07_trans_actuales.cod_dep AND cod_tipo_nomina=Rcnmd07_trans_actuales.cod_tipo_nomina AND cod_cargo=Rcnmd07_trans_actuales.cod_cargo AND cod_ficha=Rcnmd07_trans_actuales.cod_ficha AND cod_tipo_transaccion=Rcnmd07_trans_actuales.cod_tipo_transaccion AND cod_transaccion=Rcnmd07_trans_actuales.cod_transaccion;
							end if;/*fin prorrateo*/
						    end if;/*fin dias_cobro1 !=0*/
			                    end if;/*fin uso_transaccion = 9*/

					end loop;/*fin loop Ccnmd07_trans_actuales*/
					close Ccnmd07_trans_actuales;

                                           /*ASIGNACIONES BOLIVARES*/
			                   llamadof[0]  = cnmd10com52semporceded                              (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde, Pfecha_periodo_hasta);
					   llamadof[1]  = f_cnmd10_comunes_asignacion_bolivares_sexo          (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);
				           llamadof[2]  = f_cnmd10_comunes_bolivares_asignacion               (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);
				           llamadof[3]  = f_cnmd10_comunes_dia_asignacion                     (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);
				           llamadof[4]  = f_cnmd10_comunes_escala_antiguedad_bolivares_asig   (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
				           llamadof[5]  = f_cnmd10_comunes_escala_antiguedad_dias_asig        (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
				           llamadof[6]  = f_cnmd10_comunes_escala_mes_dia_asig                (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
				           llamadof[7]  = f_cnmd10_comunes_escala_sueldo_bolivares_asig       (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
				           llamadof[8]  = f_cnmd10_comunes_puestos_bolivares_asig             (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
				           llamadof[9]  = f_cnmd10_comunes_sueldo_sugerido                    (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);

                                           /*ASIGNACIONES PORCENTAJE*/
                                           llamadof[10]  = f_cnmd10_comunes_asignacion_porcentaje_sexo         (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);
				           llamadof[11]  = f_cnmd10_comunes_escala_antiguedad_porcentaje_asig  (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
                                           llamadof[12]  = f_cnmd10_comunes_escala_sueldo_porcentaje_asig      (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
				           llamadof[13]  = f_cnmd10_comunes_puestos_porcentaje_asig            (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
                                           llamadof[14]  = f_cnmd10_comunes_porcentaje_asignacion              (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);


                                           /*DEDUCCIONES*/
                                           llamadof[15]  = f_cnmd10_comunes_bolivares_deduccion               (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);
                                           llamadof[16]  = f_cnmd10_comunes_escala_antiguedad_bolivares_ded   (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
				           llamadof[17]  = f_cnmd10_comunes_escala_antiguedad_dias_ded        (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
				           llamadof[18]  = f_cnmd10_comunes_escala_sueldo_bolivares_ded       (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
				           llamadof[19]  = f_cnmd10_comunes_puestos_bolivares_ded             (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);


                                           /*DEDUCCIONES PORCENTAJE*/
                                           llamadof[20] = f_cnmd10_comunes_porcentaje_deduccion              (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_desde);
                                           llamadof[21] = f_cnmd10_comunes_puestos_porcentaje_ded            (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
                                           llamadof[22] = f_cnmd10_comunes_escala_antiguedad_porcentaje_ded  (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);
                                           llamadof[23] = f_cnmd10_comunes_escala_sueldo_porcentaje_ded      (Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_ficha, Rcnmd06_fichasv.cod_cargo, Pfecha_periodo_hasta);



				end loop;
				close Ccnmd06_fichasv;
                    end if;/*fin cantidad!=0*/
                    end loop;
                close Ccnmd06_fichasvc;

	--return monto_retornar;
	return llamadof;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION proceso_prenomina_1(integer, integer, integer, integer, integer, integer, date, date) OWNER TO sisap;

-- Function: cierre_nomina(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION cierre_nomina(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION cierre_nomina(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer)
  RETURNS integer AS
$BODY$
DECLARE

retornar integer = 0;

             /* R1 es  para la vista v_cnmd07_transacciones_actuales_frecuencias2
              * filtra todas las transacciones que son eventuales (uso_transaccion = 7) y las elimina de la
              * tabla cnmd07_transacciones_actuales
             */
	     R1 record;
             C1 cursor (pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer) for SELECT cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion FROM  v_cnmd07_transacciones_actuales_frecuencias2  WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND uso_transaccion=7;

begin
	open C1 (pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina);
		loop
			fetch C1 into R1;
			exit when not found;
                           DELETE FROM cnmd07_transacciones_actuales WHERE  cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_cargo=R1.cod_cargo and cod_ficha=R1.cod_ficha  and cod_tipo_transaccion=R1.cod_tipo_transaccion and cod_transaccion=R1.cod_transaccion;
      		end loop;
	close C1;

        DELETE FROM cnmd07_transacciones_actuales         WHERE  cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and marca_fin_descuento = '*';
        DELETE FROM cnmd09_dias_trabajados_falta          WHERE  cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina;
        DELETE FROM cnmd09_dias_trabajados_ingreso_egreso WHERE  cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina;
        DELETE FROM cnmd09_incidencia_sueldo_sugerido     WHERE  cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina;
	UPDATE  cnmd10_comunes_asignacion_bolivares_sexo          SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_asignacion_porcentaje_sexo         SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_bolivares_asignacion               SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_bolivares_deduccion                SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_dia_asignacion                     SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_dia_deduccion                      SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_antiguedad_bolivares_asig   SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_antiguedad_bolivares_ded    SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_antiguedad_dias_asig        SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_antiguedad_dias_ded         SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_antiguedad_porcentaje_asig  SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_antiguedad_porcentaje_ded   SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_mes_dia_asig                SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_sueldo_bolivares_asig       SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_sueldo_bolivares_ded        SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_sueldo_porcentaje_asig      SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_escala_sueldo_porcentaje_ded       SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_porcentaje_asignacion              SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_porcentaje_deduccion               SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_puestos_bolivares_asig             SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_puestos_bolivares_ded              SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_puestos_porcentaje_asig            SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_puestos_porcentaje_ded             SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_comunes_sueldo_sugerido                    SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_individual_bolivares                       SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_individual_dias                            SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	UPDATE  cnmd10_individual_porcentaje_horas                SET activar_frecuencia_eventual=2 WHERE cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina and cod_frecuencia=2 and activar_frecuencia_eventual=1;
	DELETE FROM cnmd10_individual_bolivares_cantidad        WHERE  cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina;
	DELETE FROM cnmd10_individual_dias_cantidad             WHERE  cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina;
	DELETE FROM cnmd10_individual_porcentaje_horas_cantidad WHERE  cod_dep=pcod_dep and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and cod_tipo_nomina=pcod_tipo_nomina;



   return retornar;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION cierre_nomina(integer, integer, integer, integer, integer, integer) OWNER TO sisap;




CREATE OR REPLACE VIEW v_distribucion_asignacion_deduccion AS
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, sum(a.monto_cuota) AS monto_asignacion, 0 AS monto_deduccion, a.denominacion, a.uso_transaccion, count(*) AS cantidad_de_transacciones
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a
  WHERE a.cod_tipo_transaccion = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, a.denominacion, a.uso_transaccion
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion)
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, 0 AS monto_asignacion, sum(a.monto_cuota) AS monto_deduccion, a.denominacion, a.uso_transaccion, count(*) AS cantidad_de_transacciones
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a
  WHERE a.cod_tipo_transaccion = 2
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, a.denominacion, a.uso_transaccion
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion);

ALTER TABLE v_distribucion_asignacion_deduccion OWNER TO sisap;
COMMENT ON VIEW v_distribucion_asignacion_deduccion IS 'vista para el reporte de distribucion de asignaciones y deducciones';

-- View: costo_presupuestario_p1

-- DROP VIEW costo_presupuestario_p1;

CREATE OR REPLACE VIEW costo_presupuestario_p1 AS
(( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, b.ano, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, sum(a.monto_cuota) AS sueldo
   FROM cnmd07_transacciones_actuales a, cnmd05 b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_tipo_transaccion = 1 AND a.cod_transaccion = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, b.ano, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, b.ano, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, sum(a.monto_cuota) AS sueldo
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd03_conexion_transacciones b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_tipo_transaccion = b.cod_tipo_transaccion AND a.cod_transaccion = b.cod_transaccion AND (a.cod_tipo_transaccion = 1 OR (a.cod_tipo_transaccion = 2 AND a.uso_transaccion = 6))
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, b.ano, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, b.ano, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, sum(a.monto_cuota) AS sueldo
   FROM deducciones_conectadas_asignacion a, cnmd03_conexion_transacciones b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.codi_tipo_transaccion_asig = b.cod_tipo_transaccion AND a.codi_transaccion_asig = b.cod_transaccion
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, b.ano, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, b.ano, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, sum(a.monto_cuota * (-1)::numeric) AS sueldo
   FROM deducciones_conectadas_asignacion a, cnmd05 b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, b.ano, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar;

ALTER TABLE costo_presupuestario_p1 OWNER TO sisap;

-- View: costo_presupuestario_p2

-- DROP VIEW costo_presupuestario_p2;

CREATE OR REPLACE VIEW costo_presupuestario_p2 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.sueldo) AS monto_nomina,
  ( SELECT disponibilidad(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar) AS disponibilidad) AS disponibilidad,
 (( SELECT disponibilidad(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar) AS disponibilidad)) - sum(a.sueldo) AS diferencia,
 ( SELECT denominacion_partida(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar) AS denominacion_partida) AS denominacion
   FROM costo_presupuestario_p1 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar;

ALTER TABLE costo_presupuestario_p2 OWNER TO sisap;


CREATE OR REPLACE VIEW cuenta_banco_ficha AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_entidad_bancaria, a.cod_sucursal, ( SELECT count(*) AS count
           FROM cnmd09_bancos_cancelan_nominas b
          WHERE b.cod_presi = a.cod_dep AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_entidad_bancaria = a.cod_entidad_bancaria AND b.cod_sucursal = a.cod_sucursal) AS cantidad, ( SELECT cs.denominacion
           FROM cstd01_entidades_bancarias cs
          WHERE cs.cod_entidad_bancaria = a.cod_entidad_bancaria) AS deno_banco, ( SELECT cse.denominacion
           FROM cstd01_sucursales_bancarias cse
          WHERE cse.cod_entidad_bancaria = a.cod_entidad_bancaria AND cse.cod_sucursal = a.cod_sucursal) AS deno_sucursal
   FROM cnmd06_fichas a
  WHERE a.forma_pago = 3
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_entidad_bancaria, a.cod_sucursal
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina;

ALTER TABLE cuenta_banco_ficha OWNER TO sisap;


-- DROP VIEW cuenta_banco_transacciones;

CREATE OR REPLACE VIEW cuenta_banco_transacciones AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, ( SELECT count(*) AS count
           FROM cnmd09_bancos_cancela_fondos_terceros b
          WHERE b.cod_presi = a.cod_dep AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_tipo_transaccion = a.cod_tipo_transaccion AND b.cod_transaccion = a.cod_transaccion) AS cantidad, devolver_denominacion_transaccion(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion) AS denominacion
   FROM cnmd07_transacciones_actuales a
  WHERE a.cod_tipo_transaccion = 2
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion;

ALTER TABLE cuenta_banco_transacciones OWNER TO sisap;

-- View: nomina_beneficiario_partidas_p1

-- DROP VIEW nomina_beneficiario_partidas_p1;

CREATE OR REPLACE VIEW nomina_beneficiario_partidas_p1 AS
(((((((((( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 1 AND a.cod_transaccion = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 1 AND a.cod_transaccion = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 1 AND a.cod_transaccion <> 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 1 AND a.cod_transaccion <> 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM deducciones_conectadas_asignacion a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = a.codi_tipo_transaccion_asig AND x.cod_transaccion = a.codi_transaccion_asig
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM deducciones_conectadas_asignacion a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = a.codi_tipo_transaccion_asig AND x.cod_transaccion = a.codi_transaccion_asig
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota * (-1)::numeric) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM deducciones_conectadas_asignacion a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota * (-1)::numeric) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM deducciones_conectadas_asignacion a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota * (-1)::numeric) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 2 AND a.uso_transaccion <> 6
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota * (-1)::numeric) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 2 AND a.uso_transaccion <> 6
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif_cedula, c.personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 2 AS tipo_denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancela_fondos_terceros c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_tipo_transaccion = a.cod_tipo_transaccion AND c.cod_transaccion = a.cod_transaccion AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 2 AND a.uso_transaccion <> 6
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif_cedula, c.personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif_cedula, c.personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 2 AS tipo_denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancela_fondos_terceros c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_tipo_transaccion = a.cod_tipo_transaccion AND c.cod_transaccion = a.cod_transaccion AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 2 AND a.uso_transaccion = 6
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif_cedula, c.personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar;

ALTER TABLE nomina_beneficiario_partidas_p1 OWNER TO sisap;

-- View: nomina_beneficiario_partidas_p2

-- DROP VIEW nomina_beneficiario_partidas_p2;

CREATE OR REPLACE VIEW nomina_beneficiario_partidas_p2 AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.monto_cuota) AS monto, a.beneficiario, a.rif_cedula, a.personalidad, a.tipo_denominacion
   FROM nomina_beneficiario_partidas_p1 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.beneficiario, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.tipo_denominacion, a.rif_cedula, a.personalidad
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.beneficiario, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar;

ALTER TABLE nomina_beneficiario_partidas_p2 OWNER TO sisap;

-- View: nomina_partidas_denot_p1

-- DROP VIEW nomina_partidas_denot_p1;

CREATE OR REPLACE VIEW nomina_partidas_denot_p1 AS
(((((((((( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 1 AND a.cod_transaccion = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 1 AND a.cod_transaccion = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 1 AND a.cod_transaccion <> 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 1 AND a.cod_transaccion <> 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, a.denominacion
   FROM deducciones_conectadas_asignacion2 a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = a.codi_tipo_transaccion_asig AND x.cod_transaccion = a.codi_transaccion_asig
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, a.denominacion
   FROM deducciones_conectadas_asignacion2 a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_tipo_transaccion = a.codi_tipo_transaccion_asig AND x.cod_transaccion = a.codi_transaccion_asig
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota * (-1)::numeric) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, a.denominacion
   FROM deducciones_conectadas_asignacion2 a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota * (-1)::numeric) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, a.denominacion
   FROM deducciones_conectadas_asignacion2 a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota * (-1)::numeric) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif AS rif_cedula, 2 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancelan_nominas c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago = 3 AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_entidad_bancaria = b.cod_entidad_bancaria AND c.cod_sucursal = b.cod_sucursal AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 2 AND a.uso_transaccion <> 6
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota * (-1)::numeric) AS monto_cuota, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text) AS beneficiario, c.cedula_identidad::character varying(20) AS rif_cedula, 1 AS personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 1 AS tipo_denominacion, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd06_datos_personales c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND b.forma_pago <> 3 AND c.cedula_identidad = b.cedula_identidad AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 2 AND a.uso_transaccion <> 6
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, (((((btrim(c.primer_nombre::text) || ' '::text) || btrim(c.segundo_nombre::text)) || ' '::text) || btrim(c.primer_apellido::text)) || ' '::text) || btrim(c.segundo_apellido::text), c.cedula_identidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif_cedula, c.personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 2 AS tipo_denominacion, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancela_fondos_terceros c, cnmd05 x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_tipo_transaccion = a.cod_tipo_transaccion AND c.cod_transaccion = a.cod_transaccion AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 2 AND a.uso_transaccion <> 6
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif_cedula, c.personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, sum(a.monto_cuota) AS monto_cuota, c.beneficiario::text AS beneficiario, c.rif_cedula, c.personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, 2 AS tipo_denominacion, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion AS denominacion
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a, cnmd06_fichas b, cnmd09_bancos_cancela_fondos_terceros c, cnmd03_conexion_transacciones x
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_tipo_nomina = b.cod_tipo_nomina AND a.cod_cargo = b.cod_cargo AND a.cod_ficha = b.cod_ficha AND c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad AND c.cod_tipo_inst = b.cod_tipo_inst AND c.cod_inst = b.cod_inst AND c.cod_dep = b.cod_dep AND c.cod_tipo_nomina = b.cod_tipo_nomina AND c.cod_tipo_transaccion = a.cod_tipo_transaccion AND c.cod_transaccion = a.cod_transaccion AND x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion AND x.cod_cargo = a.cod_cargo AND x.ano = substr(a.fecha_transaccion::text, 0, 5)::integer AND a.cod_tipo_transaccion = 2 AND a.uso_transaccion = 6
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, c.beneficiario::text, c.rif_cedula, c.personalidad, x.ano, x.cod_sector, x.cod_programa, x.cod_sub_prog, x.cod_proyecto, x.cod_activ_obra, x.cod_partida, x.cod_generica, x.cod_especifica, x.cod_sub_espec, x.cod_auxiliar, (mascara_3(a.cod_transaccion) || ' - '::text) || a.denominacion;

ALTER TABLE nomina_partidas_denot_p1 OWNER TO sisap;

CREATE OR REPLACE VIEW v_cnmd09_asignacion_calcula_deduccion_2 AS
 SELECT a.cod_tipo_transaccion, a.cod_tipo_nomina, a.cod_transaccion, a.codi_transaccion, b.denominacion
   FROM cnmd09_asignacion_calcula_deduccion_2 a, cnmd03_transacciones b
  WHERE b.cod_tipo_transaccion = 1 AND a.codi_transaccion = b.cod_transaccion;

ALTER TABLE v_cnmd09_asignacion_calcula_deduccion_2 OWNER TO sisap;


CREATE OR REPLACE VIEW v_distribucion_asignacion_deduccion AS
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, sum(a.monto_cuota) AS monto_asignacion, 0 AS monto_deduccion, a.denominacion, a.uso_transaccion, count(*) AS cantidad_de_transacciones
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a
  WHERE a.cod_tipo_transaccion = 1
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, a.denominacion, a.uso_transaccion
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion)
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, 0 AS monto_asignacion, sum(a.monto_cuota) AS monto_deduccion, a.denominacion, a.uso_transaccion, count(*) AS cantidad_de_transacciones
   FROM v_cnmd07_transacciones_actuales_frecuencias2 a
  WHERE a.cod_tipo_transaccion = 2
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion, a.denominacion, a.uso_transaccion
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_tipo_transaccion, a.cod_transaccion);

ALTER TABLE v_distribucion_asignacion_deduccion OWNER TO sisap;
COMMENT ON VIEW v_distribucion_asignacion_deduccion IS 'vista para el reporte de distribucion de asignaciones y deducciones';

CREATE OR REPLACE VIEW v_cnmd06_fichas_sueldo_basico AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha, a.cedula_identidad, a.condicion_actividad, ( SELECT x.sueldo_basico
           FROM cnmd05 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_ficha = a.cod_ficha) AS sueldo_basico
   FROM cnmd06_fichas a
  WHERE (( SELECT x.sueldo_basico
           FROM cnmd05 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_ficha = a.cod_ficha)) IS NOT NULL;

ALTER TABLE v_cnmd06_fichas_sueldo_basico OWNER TO sisap;
COMMENT ON VIEW v_cnmd06_fichas_sueldo_basico IS 'vista para cnmd06_fichas solo con sueldo basico';


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

-- Function: corrida_definitiva_nimina(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION corrida_definitiva_nimina(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION corrida_definitiva_nimina(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer)
  RETURNS integer AS
$BODY$
DECLARE

retornar integer = 0;

             /* R1 es  para la vista v_cnmd07_transacciones_actuales_frecuencias2 */
	     R1 record;
             C1 cursor (pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer) for SELECT * FROM  v_cnmd07_transacciones_actuales_frecuencias2  WHERE cod_presi=pcod_presi AND cod_entidad=pcod_entidad AND cod_tipo_inst=pcod_tipo_inst AND cod_inst=pcod_inst AND cod_dep=pcod_dep AND cod_tipo_nomina=pcod_tipo_nomina AND uso_transaccion!=7 and ((cod_tipo_transaccion=1 and cod_transaccion!=1) or cod_tipo_transaccion!=1);

begin
	open C1 (pcod_presi, pcod_entidad, pcod_tipo_inst, pcod_inst, pcod_dep, pcod_tipo_nomina);
		loop
			fetch C1 into R1;
			exit when not found;
                         if R1.tipo_actualizacion = 1 then
			     UPDATE cnmd07_transacciones_actuales SET saldo=(saldo+monto_cuota)  WHERE cod_presi=R1.cod_presi AND cod_entidad=R1.cod_entidad AND cod_tipo_inst=R1.cod_tipo_inst AND cod_inst=R1.cod_inst AND cod_dep=R1.cod_dep AND cod_tipo_nomina=R1.cod_tipo_nomina AND cod_cargo=R1.cod_cargo AND cod_ficha=R1.cod_ficha AND cod_tipo_transaccion=R1.cod_tipo_transaccion AND cod_transaccion=R1.cod_transaccion;
			 elsif R1.tipo_actualizacion = 2 AND R1.saldo>=R1.monto_cuota then
                             UPDATE cnmd07_transacciones_actuales SET saldo=(saldo-monto_cuota)  WHERE cod_presi=R1.cod_presi AND cod_entidad=R1.cod_entidad AND cod_tipo_inst=R1.cod_tipo_inst AND cod_inst=R1.cod_inst AND cod_dep=R1.cod_dep AND cod_tipo_nomina=R1.cod_tipo_nomina AND cod_cargo=R1.cod_cargo AND cod_ficha=R1.cod_ficha AND cod_tipo_transaccion=R1.cod_tipo_transaccion AND cod_transaccion=R1.cod_transaccion;
			 end if;
			retornar = marcar_transanciones_actuales(pcod_presi,pcod_entidad,pcod_tipo_inst,pcod_inst,pcod_dep,pcod_tipo_nomina,R1.cod_cargo,R1.cod_ficha,R1.cod_tipo_transaccion,R1.cod_transaccion);
		end loop;
	close C1;
   return retornar;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION corrida_definitiva_nimina(integer, integer, integer, integer, integer, integer) OWNER TO sisap;



CREATE TABLE cnmd09_ubicacion_direccion_personal
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo_nomina integer NOT NULL, -- Código tipo de nómina
  cod_dir_superior integer NOT NULL, -- Código de la dirección superior
  cod_coordinacion integer NOT NULL, -- Código de la coordinación
  cod_secretaria integer NOT NULL, -- Código de la secretaria
  cod_direccion integer NOT NULL, -- Código de la dirección
  CONSTRAINT cnmd09_ubicacion_direccion_personal_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina)
)
WITH (OIDS=FALSE);
ALTER TABLE cnmd09_ubicacion_direccion_personal OWNER TO sisap;
COMMENT ON TABLE cnmd09_ubicacion_direccion_personal IS 'Se utiliza registrar la ubicación administrativa de la Dirección de personal, para generar los documentos de otros compromisos de manera automática al momento de hacer las nóminas';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_tipo_nomina IS 'Código tipo de nómina';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_dir_superior IS 'Código de la dirección superior';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_coordinacion IS 'Código de la coordinación';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_secretaria IS 'Código de la secretaria';
COMMENT ON COLUMN cnmd09_ubicacion_direccion_personal.cod_direccion IS 'Código de la dirección';

-- Function: disponibilidad(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION disponibilidad(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION disponibilidad(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)
  RETURNS numeric AS
$BODY$
DECLARE
 disponibilidad cfpd05.asignacion_anual%TYPE;

	recCFPD05 record;
	curCFPD05 cursor
		for SELECT (cfpd05.asignacion_anual + cfpd05.aumento_traslado_anual + cfpd05.credito_adicional_anual - (cfpd05.disminucion_traslado_anual + cfpd05.rebaja_anual + cfpd05.compromiso_anual + cfpd05.precompromiso_congelado + cfpd05.precompromiso_requisicion + cfpd05.precompromiso_obras + cfpd05.precompromiso_fondo_avance)) as dispon
FROM cfpd05 WHERE cod_presi=$1 and
cod_entidad=$2 and
cod_tipo_inst=$3 and
cod_inst=$4 and
cod_dep=$5 and
ano=$6 and
cod_sector=$7 and
cod_programa=$8 and
cod_sub_prog=$9 and
cod_proyecto=$10 and
cod_activ_obra=$11 and
cod_partida=$12 and
cod_generica=$13 and
cod_especifica=$14 and
cod_sub_espec=$15 and
cod_auxiliar=$16;

begin

		open curCFPD05;
		loop
			fetch curCFPD05 into recCFPD05;
			exit when not found;
			disponibilidad=recCFPD05.dispon;
		end loop;
		close curCFPD05;
	return disponibilidad;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION disponibilidad(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;
