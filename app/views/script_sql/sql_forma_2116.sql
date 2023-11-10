-- Function: deno_partida_forma2126(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION deno_partida_forma2126(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION deno_partida_forma2126(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pano integer, pcod_sector integer, pcod_programa integer, pcod_sub_prog integer, pcod_proyecto integer, pcod_activ_obra integer, pcod_partida integer, pcod_generica integer, pcod_especifica integer, pcod_sub_espec integer, pcod_auxiliar integer)
  RETURNS text AS
$BODY$
DECLARE
t text;

BEGIN
if pcod_generica = 0 AND pcod_especifica = 0 AND pcod_sub_espec = 0 AND pcod_auxiliar = 0 then
--partida
	t = (SELECT denominacion FROM cfpd01_ano_2_partida WHERE ejercicio=pano and cod_grupo=substr(pcod_partida::text,0,2)::integer and cod_partida=substr(pcod_partida::text,2,2)::integer);
elsif pcod_generica != 0 AND pcod_especifica = 0 AND pcod_sub_espec = 0 AND pcod_auxiliar = 0 then
--generica
	t = (SELECT denominacion FROM cfpd01_ano_3_generica WHERE ejercicio=pano and cod_grupo=substr(pcod_partida::text,0,2)::integer and cod_partida=substr(pcod_partida::text,2,2)::integer and cod_generica=pcod_generica);
elsif pcod_generica != 0 AND pcod_especifica != 0 AND pcod_sub_espec = 0 AND pcod_auxiliar = 0 then
--especifica
	t = (SELECT denominacion FROM cfpd01_ano_4_especifica WHERE ejercicio=pano and cod_grupo=substr(pcod_partida::text,0,2)::integer and cod_partida=substr(pcod_partida::text,2,2)::integer and cod_generica=pcod_generica and cod_especifica=pcod_especifica);
else
	if pcod_dep = 0 then
	   if pcod_auxiliar = 0 then
	       t = (SELECT denominacion FROM cfpd01_ano_5_sub_espec WHERE ejercicio=pano and cod_grupo=substr(pcod_partida::text,0,2)::integer and cod_partida=substr(pcod_partida::text,2,2)::integer and cod_generica=pcod_generica and cod_especifica=pcod_especifica  and cod_sub_espec=pcod_sub_espec);
	   else
	       t = (SELECT denominacion FROM cfpd05_auxiliar WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog and cod_proyecto=pcod_proyecto and cod_partida=pcod_partida and cod_generica=pcod_generica and cod_especifica=pcod_especifica  and cod_sub_espec=pcod_sub_espec and cod_auxiliar=pcod_auxiliar limit 1);
	   end if;
	else
	   if pcod_auxiliar = 0 then
	       t = (SELECT denominacion FROM cfpd01_ano_5_sub_espec WHERE ejercicio=pano and cod_grupo=substr(pcod_partida::text,0,2)::integer and cod_partida=substr(pcod_partida::text,2,2)::integer and cod_generica=pcod_generica and cod_especifica=pcod_especifica  and cod_sub_espec=pcod_sub_espec);
	   else
	       t = (SELECT denominacion FROM cfpd05_auxiliar WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog and cod_proyecto=pcod_proyecto and cod_partida=pcod_partida and cod_generica=pcod_generica and cod_especifica=pcod_especifica  and cod_sub_espec=pcod_sub_espec and cod_auxiliar=pcod_auxiliar limit 1);
	   end if;
	end if;
end if;



RETURN upper(t);
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION deno_partida_forma2126(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: deno_sectores_forma2126(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION deno_sectores_forma2126(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION deno_sectores_forma2126(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pano integer, pcod_sector integer, pcod_programa integer, pcod_sub_prog integer, pcod_proyecto integer, pcod_activ_obra integer)
  RETURNS text AS
$BODY$
DECLARE
t text;

BEGIN
if pcod_dep != 0 then
	if pcod_sector != 0 AND pcod_programa = 0 AND pcod_sub_prog = 0  AND pcod_proyecto = 0 AND pcod_activ_obra = 0 then
	--sector
		t = (SELECT denominacion FROM cfpd02_sector WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and ano=pano and cod_sector=pcod_sector);
	elsif  pcod_sector != 0 AND pcod_programa != 0 AND pcod_sub_prog = 0  AND pcod_proyecto = 0 AND pcod_activ_obra = 0  then
	--progrma
		t = (SELECT denominacion FROM cfpd02_programa WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa);
	elsif  pcod_sector != 0 AND pcod_programa != 0 AND pcod_sub_prog != 0  AND pcod_proyecto = 0 AND pcod_activ_obra = 0 then
	--sub_programa
		t = (SELECT denominacion FROM cfpd02_sub_prog WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog);
        elsif  pcod_sector != 0 AND pcod_programa != 0 AND pcod_sub_prog != 0 AND pcod_proyecto!= 0  AND pcod_activ_obra = 0 then
        --proyecto
                t = (SELECT denominacion FROM cfpd02_proyecto WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog and cod_proyecto=pcod_proyecto);
	elsif  pcod_sector != 0 AND pcod_programa != 0 AND pcod_sub_prog != 0 AND pcod_activ_obra!= 0 then
        --actividad
                t = (SELECT denominacion FROM cfpd02_activ_obra WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and cod_dep=pcod_dep and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog and cod_proyecto=pcod_proyecto and cod_activ_obra=pcod_activ_obra);
	end if;
else
	if pcod_sector != 0 AND pcod_programa = 0 AND pcod_sub_prog = 0  AND pcod_proyecto = 0 AND pcod_activ_obra = 0 then
	--sector
		t = (SELECT denominacion FROM cfpd02_sector WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  ano=pano and cod_sector=pcod_sector limit 1);
	elsif  pcod_sector != 0 AND pcod_programa != 0 AND pcod_sub_prog = 0  AND pcod_proyecto = 0 AND pcod_activ_obra = 0  then
	--progrma
		t = (SELECT denominacion FROM cfpd02_programa WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa limit 1);
	elsif  pcod_sector != 0 AND pcod_programa != 0 AND pcod_sub_prog != 0  AND pcod_proyecto = 0 AND pcod_activ_obra = 0 then
	--sub_programa
		t = (SELECT denominacion FROM cfpd02_sub_prog WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and  ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog limit 1);
        elsif  pcod_sector != 0 AND pcod_programa != 0 AND pcod_sub_prog != 0 AND pcod_proyecto!= 0  AND pcod_activ_obra = 0 then
        --proyecto
                t = (SELECT denominacion FROM cfpd02_proyecto WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog and cod_proyecto=pcod_proyecto limit 1);
	elsif  pcod_sector != 0 AND pcod_programa != 0 AND pcod_sub_prog != 0 AND pcod_activ_obra!= 0 then
        --actividad
                t = (SELECT denominacion FROM cfpd02_activ_obra WHERE cod_presi=pcod_presi and cod_entidad=pcod_entidad and cod_tipo_inst=pcod_tipo_inst and cod_inst=pcod_inst and ano=pano and cod_sector=pcod_sector and cod_programa=pcod_programa and cod_sub_prog=pcod_sub_prog and cod_proyecto=pcod_proyecto and cod_activ_obra=pcod_activ_obra limit 1);
	end if;
end if;



RETURN upper(t);
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION deno_sectores_forma2126(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- View: v_forma_2126_dep

-- DROP VIEW v_forma_2126_dep;

CREATE OR REPLACE VIEW v_forma_2126_dep AS 
((( SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, 0, 0, 0, 0) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida
UNION 
 SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, l.cod_generica, 0, 0, 0) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica)
UNION 
 SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, l.cod_generica, l.cod_especifica, 0, 0) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica)
UNION 
 SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, 0 AS cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, 0) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec)
UNION 
 SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, l.cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.cod_dep = l.cod_dep AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, l.cod_auxiliar) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.cod_dep = l.cod_dep AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.cod_dep, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, l.cod_auxiliar;

ALTER TABLE v_forma_2126_dep OWNER TO sisap;

-- View: v_forma_2126_inst

-- DROP VIEW v_forma_2126_inst;

CREATE OR REPLACE VIEW v_forma_2126_inst AS 
((( SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, 0, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, 0, 0, 0, 0) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida
UNION 
 SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, 0, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, l.cod_generica, 0, 0, 0) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica)
UNION 
 SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, 0, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, l.cod_generica, l.cod_especifica, 0, 0) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica)
UNION 
 SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, 0 AS cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, 0, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, 0) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec)
UNION 
 SELECT l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, l.cod_auxiliar, ( SELECT s.denominacion
           FROM cfpd02_sector s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector
         LIMIT 1) AS deno_sector, ( SELECT s.denominacion
           FROM cfpd02_programa s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa
         LIMIT 1) AS deno_programa, ( SELECT s.denominacion
           FROM cfpd02_sub_prog s
          WHERE s.cod_presi = l.cod_presi AND s.cod_entidad = l.cod_entidad AND s.cod_tipo_inst = l.cod_tipo_inst AND s.cod_inst = l.cod_inst AND s.ano = l.ano AND s.cod_sector = l.cod_sector AND s.cod_programa = l.cod_programa AND s.cod_sub_prog = l.cod_sub_prog
         LIMIT 1) AS deno_sub_prog, deno_partida_forma2126(l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, 0, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, 0, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, l.cod_auxiliar) AS denominacion, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 51 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ51, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 52 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ52, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 53 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ53, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 54 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ54, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 55 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ55, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 56 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ56, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 57 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ57, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 58 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ58, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 59 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ59, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 60 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ60, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 61 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ61, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 62 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ62, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 63 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ63, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 64 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ64, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 65 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ65, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 66 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ66, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 67 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ67, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 68 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ68, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 69 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ69, ( SELECT sum(x.asignacion_anual) AS sum
           FROM cfpd05 x
          WHERE x.cod_activ_obra = 70 AND x.cod_presi = l.cod_presi AND x.cod_entidad = l.cod_entidad AND x.cod_tipo_inst = l.cod_tipo_inst AND x.cod_inst = l.cod_inst AND x.ano = l.ano AND x.cod_sector = l.cod_sector AND x.cod_programa = l.cod_programa AND x.cod_sub_prog = l.cod_sub_prog AND x.cod_proyecto = l.cod_proyecto AND x.cod_partida = l.cod_partida AND x.cod_generica = l.cod_generica AND x.cod_especifica = l.cod_especifica AND x.cod_sub_espec = l.cod_sub_espec AND x.cod_auxiliar = l.cod_auxiliar) AS activ70
   FROM cfpd05 l
  WHERE l.ano = (( SELECT z.ano_formular
           FROM cfpd01_formulacion z
          WHERE z.cod_presi = l.cod_presi AND z.cod_entidad = l.cod_entidad AND z.cod_tipo_inst = l.cod_tipo_inst))
  GROUP BY l.cod_presi, l.cod_entidad, l.cod_tipo_inst, l.cod_inst, l.ano, l.cod_sector, l.cod_programa, l.cod_sub_prog, l.cod_proyecto, l.cod_partida, l.cod_generica, l.cod_especifica, l.cod_sub_espec, l.cod_auxiliar;

ALTER TABLE v_forma_2126_inst OWNER TO sisap;


