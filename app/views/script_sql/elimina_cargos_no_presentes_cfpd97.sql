CREATE OR REPLACE FUNCTION elimina_cargos_no_presentes_cfpd97(integer, integer, integer, integer, integer, integer, integer)
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
	
	     recCFPD97SELCON record;
             curCFPD97SELCON cursor (Pcod_presi int4, Pcod_entidad int4, Pcod_tipo_inst int4, Pcod_inst int4, Pcod_dep int4, Pcod_tipo_nomina int4, Pcod_cargo int4) for SELECT count(*) as cantidad_cargos FROM cfpd97 WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo;
             recCNMD05_2 record;
             curCNMD05_2 cursor (Pcod_presi int4, Pcod_entidad int4, Pcod_tipo_inst int4, Pcod_inst int4, Pcod_dep int4, Pcod_tipo_nomina int4, Pcod_cargo int4) for SELECT * FROM cnmd05 WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo;



begin
            open curCFPD97SELCON (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina, Pcod_cargo);
		loop
			fetch curCFPD97SELCON into recCFPD97SELCON;
			exit when not found;
			if recCFPD97SELCON.cantidad_cargos=0 then
				open curCNMD05_2 (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina, Pcod_cargo);
				loop
					fetch curCNMD05_2 into recCNMD05_2;
					exit when not found;
					INSERT INTO cnmd05_cargos_eliminados values (recCNMD05_2.cod_presi,
					  recCNMD05_2.cod_entidad,
					  recCNMD05_2.cod_tipo_inst,
					  recCNMD05_2.cod_inst,
					  recCNMD05_2.cod_dep,
					  recCNMD05_2.cod_tipo_nomina,
					  recCNMD05_2.cod_cargo,
					  recCNMD05_2.cod_puesto,
					  recCNMD05_2.sueldo_basico,
					  recCNMD05_2.compensaciones,
					  recCNMD05_2.primas,
					  recCNMD05_2.bonos,
					  recCNMD05_2.cod_dir_superior,
					  recCNMD05_2.cod_coordinacion,
					  recCNMD05_2.cod_secretaria,
					  recCNMD05_2.cod_direccion,
					  recCNMD05_2.cod_division,
					  recCNMD05_2.cod_departamento,
					  recCNMD05_2.cod_oficina,
					  recCNMD05_2.cod_estado,
					  recCNMD05_2.cod_municipio,
					  recCNMD05_2.cod_parroquia,
					  recCNMD05_2.cod_centro,
					  recCNMD05_2.condicion_actividad,
					  recCNMD05_2.ano,
					  recCNMD05_2.cod_sector,
					  recCNMD05_2.cod_programa,
					  recCNMD05_2.cod_sub_prog,
					  recCNMD05_2.cod_proyecto,
					  recCNMD05_2.cod_activ_obra,
					  recCNMD05_2.cod_partida,
					  recCNMD05_2.cod_generica,
					  recCNMD05_2.cod_especifica,
					  recCNMD05_2.cod_sub_espec,
					  recCNMD05_2.cod_auxiliar,
					  recCNMD05_2.cod_nivel_i,
					  recCNMD05_2.cod_nivel_ii,
					  recCNMD05_2.cod_ficha);
					
				end loop;
				DELETE FROM cnmd05 WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo;
				retornar=recCFPD97SELCON.cantidad_cargos;
				close curCNMD05_2;
                    end if;
                    end loop;
                close curCFPD97SELCON;
         
	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION elimina_cargos_no_presentes_cfpd97(integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

