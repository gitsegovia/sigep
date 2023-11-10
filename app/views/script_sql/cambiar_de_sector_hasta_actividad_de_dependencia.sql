


--DROP FUNCTION cambiar_de_sector_hasta_actividad_de_dependencia(cod_sector_p2 integer, cod_programa_p2 integer, cod_sub_prog_p2 integer, cod_proyecto_p2 integer, cod_activ_obra_p2 integer, ano_p2 integer, cod_dep_desde_p2 integer, cod_dep_hasta_p2 integer);

--select cambiar_de_sector_hasta_actividad_de_dependencia(12, 06, 04, 00, 51, 2009, 1, 23);

CREATE OR REPLACE FUNCTION cambiar_de_sector_hasta_actividad_de_dependencia(cod_sector_p2 integer, cod_programa_p2 integer, cod_sub_prog_p2 integer, cod_proyecto_p2 integer, cod_activ_obra_p2 integer, ano_p2 integer, cod_dep_desde_p2 integer, cod_dep_hasta_p2 integer)
  RETURNS void AS
$BODY$

  DECLARE


	cod_sector_p     integer  = cod_sector_p2;
	cod_programa_p   integer  = cod_programa_p2;
	cod_sub_prog_p   integer  = cod_sub_prog_p2;
	cod_proyecto_p   integer  = cod_proyecto_p2;
	cod_activ_obra_p integer  = cod_activ_obra_p2;
	ano_p            integer  = ano_p2;
	cod_dep_desde_p  integer  = cod_dep_desde_p2;
	cod_dep_hasta_p  integer  = cod_dep_hasta_p2;


        BEGIN


		        --UPDATE cfpd02_sector           set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p;
				--UPDATE cfpd02_programa         set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p;
				--UPDATE cfpd02_sub_prog         set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p and cod_sub_prog = cod_sub_prog_p;
				--UPDATE cfpd02_proyecto         set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p and cod_sub_prog = cod_sub_prog_p and cod_proyecto = cod_proyecto_p;
		    	--UPDATE cfpd02_activ_obra       set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p and cod_sub_prog = cod_sub_prog_p and cod_proyecto = cod_proyecto_p and cod_activ_obra = cod_activ_obra_p;
				UPDATE cfpd05                  set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p and cod_sub_prog = cod_sub_prog_p and cod_proyecto = cod_proyecto_p and cod_activ_obra = cod_activ_obra_p;
				UPDATE cfpd05_auxiliar         set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p and cod_sub_prog = cod_sub_prog_p and cod_proyecto = cod_proyecto_p and cod_activ_obra = cod_activ_obra_p;
				UPDATE cfpd05_requerimiento    set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p and cod_sub_prog = cod_sub_prog_p and cod_proyecto = cod_proyecto_p and cod_activ_obra = cod_activ_obra_p;
				UPDATE cfpd09_metas_sector     set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p;
				UPDATE cfpd09_metas_programa   set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p;
				UPDATE cfpd09_metas_subprog    set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p and cod_sub_prog = cod_sub_prog_p;
				UPDATE cfpd09_metas_proyecto   set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p and cod_sub_prog = cod_sub_prog_p and cod_proyecto = cod_proyecto_p;
				UPDATE cfpd09_metas_actividad  set cod_dep = cod_dep_hasta_p  WHERE  cod_dep = cod_dep_desde_p and ano = ano_p and   cod_sector = cod_sector_p and cod_programa = cod_programa_p and cod_sub_prog = cod_sub_prog_p and cod_proyecto = cod_proyecto_p and cod_activ_obra = cod_activ_obra_p;

       END;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION cambiar_de_sector_hasta_actividad_de_dependencia(cod_sector_p2 integer, cod_programa_p2 integer, cod_sub_prog_p2 integer, cod_proyecto_p2 integer, cod_activ_obra_p2 integer, ano_p2 integer, cod_dep_desde_p2 integer, cod_dep_hasta_p2 integer) OWNER TO sisap;
