CREATE OR REPLACE FUNCTION tiene_movimiento_cfpd05(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   result  integer = 0;
   aleatorio numeric = random();
BEGIN
 result=(SELECT count(*) FROM cfpd05 WHERE cod_presi=$1 and
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
cod_auxiliar=$16 and (aumento_traslado_anual!=0 or disminucion_traslado_anual!=0 or credito_adicional_anual!=0 or rebaja_anual!=0 or compromiso_anual!=0 or causado_anual!=0 or pagado_anual!=0 or precompromiso_congelado!=0) and aleatorio=aleatorio);
 if result is null then
   result = 0;
 end if;
RETURN result;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION tiene_movimiento_cfpd05(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

CREATE OR REPLACE FUNCTION cantidad_fila_cfpd05(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   result_2  integer = 0;
   aleatorio2 numeric = random();
BEGIN
 result_2=(SELECT count(*) FROM cfpd05 WHERE cod_presi=$1 and
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
cod_auxiliar=$16 and aleatorio2=aleatorio2);
 if result_2 is null then
   result_2 = 0;
 end if;
RETURN result_2;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION cantidad_fila_cfpd05(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;
