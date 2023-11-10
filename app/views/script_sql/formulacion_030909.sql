-- Function: denominacion_clasificacion(integer, integer)

-- DROP FUNCTION denominacion_clasificacion(integer, integer);

CREATE OR REPLACE FUNCTION denominacion_clasificacion(nivel_i integer, nivel_ii integer)
  RETURNS text AS
$BODY$
DECLARE
t text;
x text;

BEGIN
	if nivel_i = 1 then
           x = 'I';
       elsif nivel_i = 2 then
           x = 'II';
       elsif nivel_i = 3 then
           x = 'III';
       elsif nivel_i = 4 then
           x = 'IV';
       elsif nivel_i = 5 then
           x = 'V';
       elsif nivel_i = 6 then
           x = 'VI';
       end if;

   if nivel_ii = 0 then
       t = (SELECT x ||'.' || denominacion FROM cnmd04_tipo WHERE cod_nivel_i=nivel_i);
   else
       t = (SELECT x ||'.' ||cod_nivel_ii::text ||'.' || denominacion FROM cnmd04_ocupacion WHERE cod_nivel_i=nivel_i and cod_nivel_ii=nivel_ii);
   end if;
RETURN upper(t);
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION denominacion_clasificacion(integer, integer) OWNER TO sisap;
-- View: v_cnmd06_fichas_sexo

-- DROP VIEW v_cnmd06_fichas_sexo;

CREATE OR REPLACE VIEW v_cnmd06_fichas_sexo AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha, a.cedula_identidad, b.sexo
   FROM cnmd06_fichas a, cnmd06_datos_personales b
  WHERE b.cedula_identidad = a.cedula_identidad;

ALTER TABLE v_cnmd06_fichas_sexo OWNER TO sisap;




-- DROP VIEW v_cfpd97_sexo;

CREATE OR REPLACE VIEW v_cfpd97_sexo AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_puesto, a.sueldo_basico, a.compensaciones, a.primas, a.bonos, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.condicion_actividad, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_nivel_i, a.cod_nivel_ii, a.cod_ficha, ( SELECT x.sexo
           FROM v_cnmd06_fichas_sexo x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_ficha = a.cod_ficha) AS sexo
   FROM cfpd97 a
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_puesto, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_nivel_i, a.cod_nivel_ii, a.cod_ficha;

ALTER TABLE v_cfpd97_sexo OWNER TO sisap;

-- DROP VIEW v_cnmd05_sexo;

CREATE OR REPLACE VIEW v_cnmd05_sexo AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_puesto, a.sueldo_basico, a.compensaciones, a.primas, a.bonos, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.condicion_actividad, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_nivel_i, a.cod_nivel_ii, a.cod_ficha, ( SELECT x.sexo
           FROM v_cnmd06_fichas_sexo x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina AND x.cod_cargo = a.cod_cargo AND x.cod_ficha = a.cod_ficha) AS sexo
   FROM cnmd05 a
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_puesto, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_nivel_i, a.cod_nivel_ii, a.cod_ficha;

ALTER TABLE v_cnmd05_sexo OWNER TO sisap;

-- View: v_cfpd97_reporte1

-- DROP VIEW v_cfpd97_reporte1;

CREATE OR REPLACE VIEW v_cfpd97_reporte1 AS
((( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, 0 AS cod_nivel_ii, a.sexo, 0 AS tipo_columna, count(a.cod_nivel_i) AS cantidad
   FROM v_cfpd97_sexo a
  WHERE a.cod_nivel_i <= 4 AND a.cod_partida <> 407
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.sexo
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i)
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, 0 AS cod_nivel_ii, a.sexo, 1 AS tipo_columna, count(a.cod_nivel_i) AS cantidad
   FROM v_cfpd97_sexo a
  WHERE a.cod_nivel_i <= 4 AND a.cod_partida = 407
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.sexo
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i))
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, a.cod_nivel_ii, a.sexo, 0 AS tipo_columna, count(a.cod_nivel_i) AS cantidad
   FROM v_cfpd97_sexo a
  WHERE a.cod_nivel_i <= 4 AND a.cod_partida <> 407
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii, a.sexo
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii))
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, a.cod_nivel_ii, a.sexo, 1 AS tipo_columna, count(a.cod_nivel_i) AS cantidad
   FROM v_cfpd97_sexo a
  WHERE a.cod_nivel_i <= 4 AND a.cod_partida = 407
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii, a.sexo
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii);

ALTER TABLE v_cfpd97_reporte1 OWNER TO sisap;

-- View: v_cnmd05_reporte1

-- DROP VIEW v_cnmd05_reporte1;

CREATE OR REPLACE VIEW v_cnmd05_reporte1 AS
((( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, 0 AS cod_nivel_ii, a.sexo, 0 AS tipo_columna, count(a.cod_nivel_i) AS cantidad
   FROM v_cnmd05_sexo a
  WHERE a.cod_nivel_i <= 4 AND a.cod_partida <> 407
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.sexo
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i)
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, 0 AS cod_nivel_ii, a.sexo, 1 AS tipo_columna, count(a.cod_nivel_i) AS cantidad
   FROM v_cnmd05_sexo a
  WHERE a.cod_nivel_i <= 4 AND a.cod_partida = 407
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.sexo
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i))
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, a.cod_nivel_ii, a.sexo, 0 AS tipo_columna, count(a.cod_nivel_i) AS cantidad
   FROM v_cnmd05_sexo a
  WHERE a.cod_nivel_i <= 4 AND a.cod_partida <> 407
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii, a.sexo
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii))
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, a.cod_nivel_ii, a.sexo, 1 AS tipo_columna, count(a.cod_nivel_i) AS cantidad
   FROM v_cnmd05_sexo a
  WHERE a.cod_nivel_i <= 4 AND a.cod_partida = 407
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii, a.sexo
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii);

ALTER TABLE v_cnmd05_reporte1 OWNER TO sisap;

CREATE OR REPLACE VIEW v_cfpd97_reporte2_dep AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, a.cod_nivel_ii, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cfpd97_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo IS NULL)) AS vacante, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cfpd97_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo::text = 'F'::text)) AS femenino, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cfpd97_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo::text = 'M'::text)) AS masculino, null_cero(( SELECT sum(x.sueldo_basico) AS sum
           FROM v_cfpd97_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.tipo_columna = 1)) AS transferencia
   FROM v_cfpd97_reporte1 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii;

ALTER TABLE v_cfpd97_reporte2_dep OWNER TO sisap;

CREATE OR REPLACE VIEW v_cfpd97_reporte2_inst AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, a.cod_nivel_ii, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cfpd97_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo IS NULL)) AS vacante, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cfpd97_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo::text = 'F'::text)) AS femenino, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cfpd97_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo::text = 'M'::text)) AS masculino, null_cero(( SELECT sum(x.sueldo_basico) AS sum
           FROM v_cfpd97_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.tipo_columna = 1)) AS transferencia
   FROM v_cfpd97_reporte1 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_nivel_i, a.cod_nivel_ii
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_nivel_i, a.cod_nivel_ii;

ALTER TABLE v_cfpd97_reporte2_inst OWNER TO sisap;

-- View: v_cnmd05_reporte2_dep

-- DROP VIEW v_cnmd05_reporte2_dep;

CREATE OR REPLACE VIEW v_cnmd05_reporte2_dep AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, a.cod_nivel_ii, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cnmd05_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo IS NULL)) AS vacante, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cnmd05_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo::text = 'F'::text)) AS femenino, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cnmd05_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo::text = 'M'::text)) AS masculino, null_cero(( SELECT sum(x.sueldo_basico) AS sum
           FROM v_cnmd05_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.tipo_columna = 1)) AS transferencia
   FROM v_cnmd05_reporte1 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_nivel_i, a.cod_nivel_ii;

ALTER TABLE v_cnmd05_reporte2_dep OWNER TO sisap;

-- View: v_cnmd05_reporte2_inst

-- DROP VIEW v_cnmd05_reporte2_inst;

CREATE OR REPLACE VIEW v_cnmd05_reporte2_inst AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, sum(a.sueldo_basico) AS sueldo_basico, sum(a.compensaciones) AS compensaciones, sum(a.primas) AS primas, sum(a.bonos) AS bonos, a.cod_nivel_i, a.cod_nivel_ii, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cnmd05_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo IS NULL)) AS vacante, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cnmd05_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo::text = 'F'::text)) AS femenino, null_cero(( SELECT sum(x.cantidad) AS sum
           FROM v_cnmd05_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.sexo::text = 'M'::text)) AS masculino, null_cero(( SELECT sum(x.sueldo_basico) AS sum
           FROM v_cnmd05_reporte1 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii AND x.tipo_columna = 1)) AS transferencia
   FROM v_cnmd05_reporte1 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_nivel_i, a.cod_nivel_ii
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_nivel_i, a.cod_nivel_ii;

ALTER TABLE v_cnmd05_reporte2_inst OWNER TO sisap;

-- View: v_cfpd97_reporte2_dep_final

--DROP VIEW v_cfpd97_reporte2_dep_final;

CREATE OR REPLACE VIEW v_cfpd97_reporte2_dep_final AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.sueldo_basico AS sueldo_basico_actual, a.compensaciones AS compesanciones_actual, a.primas AS primas_actual, a.bonos AS bonos_actual, a.cod_nivel_i, a.cod_nivel_ii, a.vacante AS vacante_actual, a.femenino AS femenino_actual, a.masculino AS masculino_actual, a.transferencia AS transferencia_actual, null_cero(( SELECT x.sueldo_basico
           FROM v_cnmd05_reporte2_dep x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS sueldo_basico_anterior, null_cero(( SELECT x.compensaciones
           FROM v_cnmd05_reporte2_dep x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS compensaciones_anterior, null_cero(( SELECT x.primas
           FROM v_cnmd05_reporte2_dep x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS primas_anterior, null_cero(( SELECT x.bonos
           FROM v_cnmd05_reporte2_dep x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS bonos_anterior, null_cero(( SELECT x.vacante
           FROM v_cnmd05_reporte2_dep x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS vacante_anterior, null_cero(( SELECT x.femenino
           FROM v_cnmd05_reporte2_dep x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS femenino_anterior, null_cero(( SELECT x.masculino
           FROM v_cnmd05_reporte2_dep x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS masculino_anterior, null_cero(( SELECT x.transferencia
           FROM v_cnmd05_reporte2_dep x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS transferencia_anterior, denominacion_clasificacion(a.cod_nivel_i, a.cod_nivel_ii) AS denominacion
   FROM v_cfpd97_reporte2_dep a;

ALTER TABLE v_cfpd97_reporte2_dep_final OWNER TO sisap;

--DROP VIEW v_cfpd97_reporte2_inst_final;

CREATE OR REPLACE VIEW v_cfpd97_reporte2_inst_final AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.sueldo_basico AS sueldo_basico_actual, a.compensaciones AS compesanciones_actual, a.primas AS primas_actual, a.bonos AS bonos_actual, a.cod_nivel_i, a.cod_nivel_ii, a.vacante AS vacante_actual, a.femenino AS femenino_actual, a.masculino AS masculino_actual, a.transferencia AS transferencia_actual, null_cero(( SELECT x.sueldo_basico
           FROM v_cnmd05_reporte2_inst x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS sueldo_basico_anterior, null_cero(( SELECT x.compensaciones
           FROM v_cnmd05_reporte2_inst x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS compensaciones_anterior, null_cero(( SELECT x.primas
           FROM v_cnmd05_reporte2_inst x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS primas_anterior, null_cero(( SELECT x.bonos
           FROM v_cnmd05_reporte2_inst x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS bonos_anterior, null_cero(( SELECT x.vacante
           FROM v_cnmd05_reporte2_inst x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS vacante_anterior, null_cero(( SELECT x.femenino
           FROM v_cnmd05_reporte2_inst x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS femenino_anterior, null_cero(( SELECT x.masculino
           FROM v_cnmd05_reporte2_inst x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS masculino_anterior, null_cero(( SELECT x.transferencia
           FROM v_cnmd05_reporte2_inst x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii)) AS transferencia_anterior, denominacion_clasificacion(a.cod_nivel_i, a.cod_nivel_ii) AS denominacion
   FROM v_cfpd97_reporte2_inst a;

ALTER TABLE v_cfpd97_reporte2_inst_final OWNER TO sisap;




CREATE VIEW v_cnmd04_ocupacion AS
    SELECT cnmd04_tipo.cod_nivel_i, 0 AS cod_nivel_ii, denominacion_clasificacion(cnmd04_tipo.cod_nivel_i, 0) AS denominacion FROM cnmd04_tipo UNION SELECT cnmd04_ocupacion.cod_nivel_i, cnmd04_ocupacion.cod_nivel_ii, denominacion_clasificacion(cnmd04_ocupacion.cod_nivel_i, cnmd04_ocupacion.cod_nivel_ii) AS denominacion FROM cnmd04_ocupacion;

ALTER TABLE public.v_cnmd04_ocupacion OWNER TO sisap;

















--DROP VIEW v_nivel_partida_sector;

CREATE VIEW v_nivel_partida_sector AS

SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida,
	  (SELECT x.denominacion FROM cfpd01_ano_2_partida x  WHERE x.ejercicio     = a.ano and
	                                                            x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int ) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_1,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_2,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_3,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_4,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_5,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_6,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_7,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_8,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_9,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_10,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_11,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_12,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_13,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_14,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_15,

     (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE   x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as total


FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida

ORDER BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida;



ALTER TABLE v_nivel_partida_sector OWNER TO sisap;












--DROP VIEW v_metas_agrupadas;

CREATE VIEW v_metas_agrupadas AS


SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano,
  a.cod_sector,
  (0) as cod_programa,
  (0) as cod_sub_prog,
  (0) as cod_proyecto,
  (0) as cod_activ_obra,
  (1) as tipo_agrupamiento,
  SUM(costo_financiero)  as costo_financiero,
  SUM(cantidad_estimada) as cantidad_estimada,
  (SELECT x.denominacion FROM cfpd02_sector        x  WHERE x.cod_presi     = a.cod_presi     and
													        x.cod_entidad   = a.cod_entidad   and
													        x.cod_tipo_inst = a.cod_tipo_inst and
													        x.cod_inst      = a.cod_inst      and
													        x.cod_dep       = a.cod_dep       and
													        x.ano           = a.ano           and
													        x.cod_sector   = a.cod_sector) as denominacion,

  (select x.metas FROM cfpd09_metas_sector x WHERE x.cod_presi     = a.cod_presi     and
											       x.cod_entidad   = a.cod_entidad   and
											       x.cod_tipo_inst = a.cod_tipo_inst and
											       x.cod_inst      = a.cod_inst      and
											       x.cod_dep       = a.cod_dep       and
											       x.ano           = a.ano           and
											       x.cod_sector   = a.cod_sector) as metas,

  (select x.unidad_medida FROM cfpd09_metas_sector x WHERE x.cod_presi     = a.cod_presi     and
											               x.cod_entidad   = a.cod_entidad   and
											               x.cod_tipo_inst = a.cod_tipo_inst and
											               x.cod_inst      = a.cod_inst      and
											               x.cod_dep       = a.cod_dep       and
											               x.ano           = a.ano           and
											               x.cod_sector   = a.cod_sector) as unidad_medida

FROM cfpd09 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector




UNION






SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  (0) as cod_sub_prog,
  (0) as cod_proyecto,
  (0) as cod_activ_obra,
  (2) as tipo_agrupamiento,
  SUM(costo_financiero)  as costo_financiero,
  SUM(cantidad_estimada) as cantidad_estimada,
  (SELECT x.denominacion FROM cfpd02_programa      x  WHERE x.cod_presi     = a.cod_presi     and
													        x.cod_entidad   = a.cod_entidad   and
													        x.cod_tipo_inst = a.cod_tipo_inst and
													        x.cod_inst      = a.cod_inst      and
													        x.cod_dep       = a.cod_dep       and
													        x.ano           = a.ano           and
													        x.cod_sector    = a.cod_sector    and
													        x.cod_programa  = a.cod_programa) as denominacion,

  (select x.metas FROM cfpd09_metas_programa x WHERE x.cod_presi     = a.cod_presi     and
											         x.cod_entidad   = a.cod_entidad   and
											         x.cod_tipo_inst = a.cod_tipo_inst and
											         x.cod_inst      = a.cod_inst      and
											         x.cod_dep       = a.cod_dep       and
											         x.ano           = a.ano           and
											         x.cod_sector    = a.cod_sector    and
											         x.cod_programa  = a.cod_programa) as metas,

  (select x.unidad_medida FROM cfpd09_metas_programa x WHERE x.cod_presi     = a.cod_presi     and
											                 x.cod_entidad   = a.cod_entidad   and
											                 x.cod_tipo_inst = a.cod_tipo_inst and
											                 x.cod_inst      = a.cod_inst      and
											                 x.cod_dep       = a.cod_dep       and
											                 x.ano           = a.ano           and
											                 x.cod_sector    = a.cod_sector    and
											                 x.cod_programa  = a.cod_programa) as unidad_medida

FROM cfpd09 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa






UNION






SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  (0) as cod_proyecto,
  (0) as cod_activ_obra,
  (3) as tipo_agrupamiento,
  SUM(costo_financiero)  as costo_financiero,
  SUM(cantidad_estimada) as cantidad_estimada,
  (SELECT x.denominacion FROM cfpd02_sub_prog      x  WHERE x.cod_presi     = a.cod_presi     and
													        x.cod_entidad   = a.cod_entidad   and
													        x.cod_tipo_inst = a.cod_tipo_inst and
													        x.cod_inst      = a.cod_inst      and
													        x.cod_dep       = a.cod_dep       and
													        x.ano           = a.ano           and
													        x.cod_sector    = a.cod_sector    and
													        x.cod_programa  = a.cod_programa  and
													        x.cod_sub_prog  = a.cod_sub_prog) as denominacion,

  (select x.metas FROM cfpd09_metas_subprog  x WHERE x.cod_presi     = a.cod_presi     and
											         x.cod_entidad   = a.cod_entidad   and
											         x.cod_tipo_inst = a.cod_tipo_inst and
											         x.cod_inst      = a.cod_inst      and
											         x.cod_dep       = a.cod_dep       and
											         x.ano           = a.ano           and
											         x.cod_sector    = a.cod_sector    and
											         x.cod_programa  = a.cod_programa  and
											         x.cod_sub_prog  = a.cod_sub_prog) as metas,

  (select x.unidad_medida FROM cfpd09_metas_subprog  x WHERE x.cod_presi     = a.cod_presi     and
											                 x.cod_entidad   = a.cod_entidad   and
											                 x.cod_tipo_inst = a.cod_tipo_inst and
											                 x.cod_inst      = a.cod_inst      and
											                 x.cod_dep       = a.cod_dep       and
											                 x.ano           = a.ano           and
											                 x.cod_sector    = a.cod_sector    and
											                 x.cod_programa  = a.cod_programa  and
											                 x.cod_sub_prog  = a.cod_sub_prog) as unidad_medida

FROM cfpd09 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_sub_prog



UNION






SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  (0) as cod_activ_obra,
  (4) as tipo_agrupamiento,
  SUM(costo_financiero)  as costo_financiero,
  SUM(cantidad_estimada) as cantidad_estimada,
  (SELECT x.denominacion FROM cfpd02_proyecto      x  WHERE x.cod_presi     = a.cod_presi     and
													        x.cod_entidad   = a.cod_entidad   and
													        x.cod_tipo_inst = a.cod_tipo_inst and
													        x.cod_inst      = a.cod_inst      and
													        x.cod_dep       = a.cod_dep       and
													        x.ano           = a.ano           and
													        x.cod_sector    = a.cod_sector    and
													        x.cod_programa  = a.cod_programa  and
													        x.cod_sub_prog  = a.cod_sub_prog  and
													        x.cod_proyecto  = a.cod_proyecto) as denominacion,

  (select x.metas FROM cfpd09_metas_proyecto x WHERE x.cod_presi     = a.cod_presi     and
											         x.cod_entidad   = a.cod_entidad   and
											         x.cod_tipo_inst = a.cod_tipo_inst and
											         x.cod_inst      = a.cod_inst      and
											         x.cod_dep       = a.cod_dep       and
											         x.ano           = a.ano           and
											         x.cod_sector    = a.cod_sector    and
											         x.cod_programa  = a.cod_programa  and
											         x.cod_sub_prog  = a.cod_sub_prog  and
											         x.cod_proyecto  = a.cod_proyecto) as metas,

  (select x.unidad_medida FROM cfpd09_metas_proyecto x WHERE x.cod_presi     = a.cod_presi     and
											                 x.cod_entidad   = a.cod_entidad   and
											                 x.cod_tipo_inst = a.cod_tipo_inst and
											                 x.cod_inst      = a.cod_inst      and
											                 x.cod_dep       = a.cod_dep       and
											                 x.ano           = a.ano           and
											                 x.cod_sector    = a.cod_sector    and
											                 x.cod_programa  = a.cod_programa  and
											                 x.cod_sub_prog  = a.cod_sub_prog  and
											                 x.cod_proyecto  = a.cod_proyecto) as unidad_medida

FROM cfpd09 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_sub_prog,
	  a.cod_proyecto




UNION






SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  (5) as tipo_agrupamiento,
  SUM(costo_financiero)  as costo_financiero,
  SUM(cantidad_estimada) as cantidad_estimada,
  (SELECT x.denominacion FROM cfpd02_activ_obra    x  WHERE x.cod_presi     = a.cod_presi     and
													        x.cod_entidad   = a.cod_entidad   and
													        x.cod_tipo_inst = a.cod_tipo_inst and
													        x.cod_inst      = a.cod_inst      and
													        x.cod_dep       = a.cod_dep       and
													        x.ano           = a.ano           and
													        x.cod_sector    = a.cod_sector    and
													        x.cod_programa  = a.cod_programa  and
													        x.cod_sub_prog  = a.cod_sub_prog  and
													        x.cod_proyecto  = a.cod_proyecto  and
													        x.cod_activ_obra = a.cod_activ_obra) as denominacion,

  (select x.metas FROM cfpd09_metas_actividad x WHERE x.cod_presi     = a.cod_presi     and
											          x.cod_entidad   = a.cod_entidad   and
											          x.cod_tipo_inst = a.cod_tipo_inst and
											          x.cod_inst      = a.cod_inst      and
											          x.cod_dep       = a.cod_dep       and
											          x.ano           = a.ano           and
											          x.cod_sector    = a.cod_sector    and
											          x.cod_programa  = a.cod_programa  and
											          x.cod_sub_prog  = a.cod_sub_prog  and
											          x.cod_proyecto  = a.cod_proyecto  and
											          x.cod_activ_obra = a.cod_activ_obra) as metas,

  (select x.unidad_medida FROM cfpd09_metas_actividad x WHERE x.cod_presi     = a.cod_presi     and
											                  x.cod_entidad   = a.cod_entidad   and
											                  x.cod_tipo_inst = a.cod_tipo_inst and
											                  x.cod_inst      = a.cod_inst      and
											                  x.cod_dep       = a.cod_dep       and
											                  x.ano           = a.ano           and
											                  x.cod_sector    = a.cod_sector    and
											                  x.cod_programa  = a.cod_programa  and
											                  x.cod_sub_prog  = a.cod_sub_prog  and
											                  x.cod_proyecto  = a.cod_proyecto  and
											                  x.cod_activ_obra = a.cod_activ_obra) as unidad_medida

FROM cfpd09 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_sub_prog,
	  a.cod_proyecto,
	  a.cod_activ_obra;



ALTER TABLE v_metas_agrupadas OWNER TO sisap;























--DROP VIEW v_nivel_subpartida_sector;

CREATE VIEW v_nivel_subpartida_sector AS

SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida,
	  (0) as cod_generica,
      (0) as cod_especifica,
      (0) as cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_2_partida x  WHERE x.ejercicio     = a.ano and
	                                                            x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int ) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_1,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_2,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_3,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_4,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_5,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_6,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_7,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_8,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_9,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_10,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_11,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_12,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_13,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_14,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as sector_15,

     (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE   x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as total


FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida






UNION



SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida,
	  a.cod_generica,
      (0) as cod_especifica,
      (0) as cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_3_generica x  WHERE x.ejercicio     = a.ano and
	                                                             x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																 x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int    and
																 x.cod_generica  = a.cod_generica) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_1,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_2,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_3,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_4,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_5,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_6,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_7,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_8,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_9,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_10,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_11,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_12,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_13,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_14,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as sector_15,

     (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE   x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as total


FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida,
	  a.cod_generica





UNION



SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida,
	  a.cod_generica,
      a.cod_especifica,
      (0) as cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_4_especifica x  WHERE x.ejercicio      = a.ano and
	                                                               x.cod_grupo      = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																   x.cod_partida    = (SUBSTR(a.cod_partida::text, 2))::int    and
																   x.cod_generica   = a.cod_generica                           and
																   x.cod_especifica = a.cod_especifica) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_1,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_2,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_3,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_4,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_5,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_6,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_7,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_8,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_9,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_10,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_11,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_12,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_13,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_14,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as sector_15,

     (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE   x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as total


FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica





UNION



SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida,
	  a.cod_generica,
      a.cod_especifica,
      a.cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_5_sub_espec x   WHERE x.ejercicio      = a.ano and
	                                                               x.cod_grupo      = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																   x.cod_partida    = (SUBSTR(a.cod_partida::text, 2))::int    and
																   x.cod_generica   = a.cod_generica                           and
																   x.cod_especifica = a.cod_especifica                         and
																   x.cod_sub_espec  = a.cod_sub_espec) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_1,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_2,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_3,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_4,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_5,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_6,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_7,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_8,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_9,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_10,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_11,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_12,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_13,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_14,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as sector_15,

     (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE   x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as total


FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica,
	  a.cod_sub_espec;



ALTER TABLE v_nivel_subpartida_sector OWNER TO sisap;















































--DROP VIEW v_nivel_subpartida_programa;

CREATE VIEW v_nivel_subpartida_programa AS

SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_partida,
	  (0) as cod_generica,
      (0) as cod_especifica,
      (0) as cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_2_partida x  WHERE x.ejercicio     = a.ano and
	                                                            x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int ) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_1,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_2,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_3,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_4,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_5,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_6,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_7,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_8,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_9,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_10,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_11,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_12,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_13,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_14,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as programa_15,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as total



FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_partida







UNION





SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_partida,
	  a.cod_generica,
      (0) as cod_especifica,
      (0) as cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_3_generica x  WHERE    x.ejercicio     = a.ano and
		                                                            x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																	x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int    and
																	x.cod_generica  = a.cod_generica) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_1,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_2,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_3,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_4,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_5,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_6,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_7,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_8,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_9,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_10,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_11,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_12,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_13,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_14,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as programa_15,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as total



FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_partida,
	  a.cod_generica







UNION





SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_partida,
	  a.cod_generica,
      a.cod_especifica,
      (0) as cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_4_especifica x  WHERE    x.ejercicio     = a.ano and
		                                                              x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																	  x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int    and
																	  x.cod_generica   = a.cod_generica                          and
																	  x.cod_especifica = a.cod_especifica) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_1,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_2,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_3,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_4,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_5,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_6,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_7,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_8,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_9,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_10,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_11,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_12,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_13,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_14,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as programa_15,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as total



FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica





UNION





SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_partida,
	  a.cod_generica,
      a.cod_especifica,
      a.cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_5_sub_espec x   WHERE    x.ejercicio     = a.ano and
		                                                              x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																	  x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int    and
																	  x.cod_generica   = a.cod_generica                          and
																	  x.cod_especifica = a.cod_especifica                        and
																	  x.cod_sub_espec  = a.cod_sub_espec) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica    and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_1,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica   and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_2,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_3,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_4,

       (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_5,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_6,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_7,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_8,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_9,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_10,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_11,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_12,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_13,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_14,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as programa_15,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as total



FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica,
	  a.cod_sub_espec;



ALTER TABLE v_nivel_subpartida_programa OWNER TO sisap;
















--DROP VIEW v_nivel_subpartida_subprograma;

CREATE VIEW v_nivel_subpartida_subprograma AS

SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_partida,
	  (0) as cod_generica,
      (0) as cod_especifica,
      (0) as cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_2_partida x  WHERE x.ejercicio     = a.ano and
	                                                            x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int ) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_1,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_2,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_3,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_4,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_5,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_6,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_7,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_8,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_9,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_10,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 11               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_11,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_12,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_13,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_14,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as subprograma_15,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida) as total



FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_partida





UNION


SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_partida,
	  a.cod_generica,
      (0) as cod_especifica,
      (0) as cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_3_generica x  WHERE x.ejercicio     = a.ano and
	                                                             x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
															  	 x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int    and
																 x.cod_generica  = a.cod_generica) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_1,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida  and
													       x.cod_generica  = a.cod_generica) as subprograma_2,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_3,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_4,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_5,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_6,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_7,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_8,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_9,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_10,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_11,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_12,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_13,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_14,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as subprograma_15,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica) as total



FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_partida,
	  a.cod_generica



UNION




SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_partida,
	  a.cod_generica,
      a.cod_especifica,
      (0) as cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_4_especifica x  WHERE x.ejercicio     = a.ano and
	                                                               x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
															  	   x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int    and
																   x.cod_generica  = a.cod_generica                           and
																   x.cod_especifica = a.cod_especifica) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_1,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida  and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_2,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_3,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_4,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_5,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_6,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_7,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_8,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_9,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_10,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_11,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_12,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_13,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_14,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as subprograma_15,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica) as total



FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica












UNION




SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_partida,
	  a.cod_generica,
      a.cod_especifica,
      a.cod_sub_espec,
	  (SELECT x.denominacion FROM cfpd01_ano_5_sub_espec x  WHERE x.ejercicio     = a.ano and
	                                                              x.cod_grupo     = (SUBSTR(a.cod_partida::text, 0, 2))::int and
															  	  x.cod_partida   = (SUBSTR(a.cod_partida::text, 2))::int    and
																  x.cod_generica  = a.cod_generica                           and
																  x.cod_especifica = a.cod_especifica                        and
																  x.cod_sub_espec  = a.cod_sub_espec) as denominacion_partida,

	  (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 1               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_1,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 2               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica    and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_2,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 3               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_3,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 4               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_4,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 5               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_5,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 6               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_6,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 7               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_7,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 8               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_8,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 9               and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_9,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 10              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_10,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 11              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_11,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 12              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_12,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 13              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_13,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 14              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_14,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.cod_sub_prog  = 15              and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as subprograma_15,

      (SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE  x.cod_presi     = a.cod_presi     and
													       x.cod_entidad   = a.cod_entidad   and
													       x.cod_tipo_inst = a.cod_tipo_inst and
													       x.cod_inst      = a.cod_inst      and
													       x.cod_dep       = a.cod_dep       and
													       x.cod_sector    = a.cod_sector    and
													       x.cod_programa  = a.cod_programa  and
													       x.ano           = a.ano           and
													       x.cod_partida   = a.cod_partida   and
													       x.cod_generica  = a.cod_generica  and
													       x.cod_especifica = a.cod_especifica and
													       x.cod_sub_espec  = a.cod_sub_espec) as total



FROM cfpd05 a


GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica,
	  a.cod_sub_espec;


ALTER TABLE v_nivel_subpartida_subprograma OWNER TO sisap;





-- View: v_cfpd03_ano_pasado_actual

-- DROP VIEW v_cfpd03_ano_pasado_actual;

CREATE OR REPLACE VIEW v_cfpd03_ano_pasado_actual AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, sum(a.estimacion_inicial) AS estimacion_inicial_actual, ( SELECT sum(b.estimacion_inicial) AS sum
           FROM cfpd03 b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = (a.ano - 1) AND b.cod_partida = a.cod_partida
          GROUP BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.ano, b.cod_partida) AS estimacion_inicial_pasada, sum(a.ingresos_adicionales) AS ingresos_adicionales_actual, ( SELECT sum(b.ingresos_adicionales) AS sum
           FROM cfpd03 b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = (a.ano - 1) AND b.cod_partida = a.cod_partida
          GROUP BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.ano, b.cod_partida) AS ingresos_adicionales_pasada, sum(a.rebajas) AS rebajas_actual, ( SELECT sum(b.rebajas) AS sum
           FROM cfpd03 b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = (a.ano - 1) AND b.cod_partida = a.cod_partida
          GROUP BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.ano, b.cod_partida) AS rebajas_pasada, sum(a.monto_facturado) AS monto_facturado_actual, ( SELECT sum(b.monto_facturado) AS sum
           FROM cfpd03 b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = (a.ano - 1) AND b.cod_partida = a.cod_partida
          GROUP BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.ano, b.cod_partida) AS monto_facturado_pasada, sum(a.monto_cobrado) AS monto_cobrado_actual, ( SELECT sum(b.monto_cobrado) AS sum
           FROM cfpd03 b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = (a.ano - 1) AND b.cod_partida = a.cod_partida
          GROUP BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.ano, b.cod_partida) AS monto_cobrado_pasada
   FROM cfpd03 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida;

ALTER TABLE v_cfpd03_ano_pasado_actual OWNER TO sisap;
















-- View: v_cfpd05_asignacion_corriente_capital

-- DROP VIEW v_cfpd05_asignacion_corriente_capital;

CREATE OR REPLACE VIEW v_cfpd05_asignacion_corriente_capital AS

SELECT

 a.cod_presi,
 a.cod_entidad,
 a.cod_tipo_inst,
 a.cod_inst,
 a.cod_dep,
 a.ano,
 (SELECT sum(x.asignacion_anual) AS sum FROM cfpd05 x WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_gasto <> 2 AND x.ano = a.ano) AS gasto_corriente,
 (SELECT sum(x.asignacion_anual) AS sum FROM cfpd05 x WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_gasto = 2  AND x.ano = a.ano) AS gasto_inversion,
 (SELECT sum(x.asignacion_anual) AS sum FROM cfpd05 x WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_dep = a.cod_dep AND                           x.ano = a.ano) AS total


  FROM cfpd05 a


  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano;

ALTER TABLE v_cfpd05_asignacion_corriente_capital OWNER TO sisap;








-- View: v_cfpd17_inversion_coordinada1

-- DROP VIEW v_cfpd17_inversion_coordinada1;

CREATE OR REPLACE VIEW v_cfpd17_inversion_coordinada1 AS
((((( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, 0 AS cod_programa, 0 AS cod_sub_prog, 0 AS cod_proyecto, 0 AS cod_activ_obra, 0 AS cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, ( SELECT b.denominacion
           FROM cfpd02_sector b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector) AS deno_sector, ''::character varying::text AS deno_programa, ''::character varying::text AS deno_sub_pro, ''::character varying::text AS deno_proyecto, ''::character varying::text AS deno_activ_obra, ''::character varying::text AS deno_partida, ''::character varying::text AS deno_generica, ''::character varying::text AS deno_especifica, ''::character varying::text AS deno_sub_espec, sum(a.aporte_municipio) AS aporte_municipio, sum(a.aporte_organismo) AS aporte_organismo, sum(a.aporte_gobernacion) AS aporte_gobernacion, 1 AS identificador
   FROM cfpd17_inversion_coordinada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector)
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, 0 AS cod_sub_prog, 0 AS cod_proyecto, 0 AS cod_activ_obra, 0 AS cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, ( SELECT b.denominacion
           FROM cfpd02_sector b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector) AS deno_sector, ( SELECT b.denominacion
           FROM cfpd02_programa b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa) AS deno_programa, ''::character varying::text AS deno_sub_pro, ''::character varying::text AS deno_proyecto, ''::character varying::text AS deno_activ_obra, ''::character varying::text AS deno_partida, ''::character varying::text AS deno_generica, ''::character varying::text AS deno_especifica, ''::character varying::text AS deno_sub_espec, sum(a.aporte_municipio) AS aporte_municipio, sum(a.aporte_organismo) AS aporte_organismo, sum(a.aporte_gobernacion) AS aporte_gobernacion, 2 AS identificador
   FROM cfpd17_inversion_coordinada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa))
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, 0 AS cod_proyecto, 0 AS cod_activ_obra, 0 AS cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, ( SELECT b.denominacion
           FROM cfpd02_sector b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector) AS deno_sector, ( SELECT b.denominacion
           FROM cfpd02_programa b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa) AS deno_programa, ( SELECT b.denominacion
           FROM cfpd02_sub_prog b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog) AS deno_sub_pro, ''::character varying::text AS deno_proyecto, ''::character varying::text AS deno_activ_obra, ''::character varying::text AS deno_partida, ''::character varying::text AS deno_generica, ''::character varying::text AS deno_especifica, ''::character varying::text AS deno_sub_espec, sum(a.aporte_municipio) AS aporte_municipio, sum(a.aporte_organismo) AS aporte_organismo, sum(a.aporte_gobernacion) AS aporte_gobernacion, 3 AS identificador
   FROM cfpd17_inversion_coordinada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog))
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, 0 AS cod_activ_obra, 0 AS cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, ( SELECT b.denominacion
           FROM cfpd02_sector b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector) AS deno_sector, ( SELECT b.denominacion
           FROM cfpd02_programa b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa) AS deno_programa, ( SELECT b.denominacion
           FROM cfpd02_sub_prog b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog) AS deno_sub_pro, ( SELECT b.denominacion
           FROM cfpd02_proyecto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog AND b.cod_proyecto = a.cod_proyecto) AS deno_proyecto, ''::character varying::text AS deno_activ_obra, ''::character varying::text AS deno_partida, ''::character varying::text AS deno_generica, ''::character varying::text AS deno_especifica, ''::character varying::text AS deno_sub_espec, sum(a.aporte_municipio) AS aporte_municipio, sum(a.aporte_organismo) AS aporte_organismo, sum(a.aporte_gobernacion) AS aporte_gobernacion, 4 AS identificador
   FROM cfpd17_inversion_coordinada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto))
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, 0 AS cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, ( SELECT b.denominacion
           FROM cfpd02_sector b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector) AS deno_sector, ( SELECT b.denominacion
           FROM cfpd02_programa b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa) AS deno_programa, ( SELECT b.denominacion
           FROM cfpd02_sub_prog b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog) AS deno_sub_pro, ( SELECT b.denominacion
           FROM cfpd02_proyecto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog AND b.cod_proyecto = a.cod_proyecto) AS deno_proyecto, ( SELECT b.denominacion
           FROM cfpd02_activ_obra b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog AND b.cod_proyecto = a.cod_proyecto AND b.cod_activ_obra = a.cod_activ_obra) AS deno_activ_obra, ''::character varying::text AS deno_partida, ''::character varying::text AS deno_generica, ''::character varying::text AS deno_especifica, ''::character varying::text AS deno_sub_espec, sum(a.aporte_municipio) AS aporte_municipio, sum(a.aporte_organismo) AS aporte_organismo, sum(a.aporte_gobernacion) AS aporte_gobernacion, 5 AS identificador
   FROM cfpd17_inversion_coordinada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra))
UNION
( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, ( SELECT b.denominacion
           FROM cfpd02_sector b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector) AS deno_sector, ( SELECT b.denominacion
           FROM cfpd02_programa b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa) AS deno_programa, ( SELECT b.denominacion
           FROM cfpd02_sub_prog b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog) AS deno_sub_pro, ( SELECT b.denominacion
           FROM cfpd02_proyecto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog AND b.cod_proyecto = a.cod_proyecto) AS deno_proyecto, ( SELECT b.denominacion
           FROM cfpd02_activ_obra b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog AND b.cod_proyecto = a.cod_proyecto AND b.cod_activ_obra = a.cod_activ_obra) AS deno_activ_obra, ( SELECT x.denominacion
           FROM cfpd01_ano_2_partida x
          WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer) AS deno_partida, ( SELECT x.denominacion
           FROM cfpd01_ano_3_generica x
          WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer AND x.cod_generica = a.cod_generica) AS deno_generica, ( SELECT x.denominacion
           FROM cfpd01_ano_4_especifica x
          WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer AND x.cod_generica = a.cod_generica AND x.cod_especifica = a.cod_especifica) AS deno_especifica, ( SELECT x.denominacion
           FROM cfpd01_ano_5_sub_espec x
          WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer AND x.cod_generica = a.cod_generica AND x.cod_especifica = a.cod_especifica AND x.cod_sub_espec = a.cod_sub_espec) AS deno_sub_espec, sum(a.aporte_municipio) AS aporte_municipio, sum(a.aporte_organismo) AS aporte_organismo, sum(a.aporte_gobernacion) AS aporte_gobernacion, 6 AS identificador
   FROM cfpd17_inversion_coordinada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_organismo, a.cod_municipio, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec);

ALTER TABLE v_cfpd17_inversion_coordinada1 OWNER TO sisap;









-- View: v_cfpd05_sector_publico

-- DROP VIEW v_cfpd05_sector_publico;

CREATE OR REPLACE VIEW v_cfpd05_sector_publico AS






ALTER TABLE v_cfpd05_sector_publico OWNER TO sisap;

