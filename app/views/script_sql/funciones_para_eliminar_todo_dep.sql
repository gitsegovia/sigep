-- Function: borrar_cobp01_dep(integer, integer)

-- DROP FUNCTION borrar_cobp01_dep(integer, integer);

CREATE OR REPLACE FUNCTION borrar_cobp01_dep(integer, integer)
  RETURNS void AS
$BODY$
	

        delete from cobd01_contratoobras_partidas                 WHERE cod_dep=$1 and ano_contrato_obra=$2;
	delete from cobd01_contratoobras_anticipo_partidas        WHERE cod_dep=$1 and ano_contrato_obra=$2;
	delete from cobd01_contratoobras_modificacion_partidas    WHERE cod_dep=$1 and ano_contrato_obra=$2;
	delete from cobd01_contratoobras_retencion_partidas       WHERE cod_dep=$1 and ano_contrato_obra=$2;
	delete from cobd01_contratoobras_valuacion_partidas       WHERE cod_dep=$1 and ano_contrato_obra=$2;

        delete from cobd01_contratoobras_valuacion_cuerpo         WHERE cod_dep=$1 and ano_contrato_obra=$2;
        delete from cobd01_contratoobras_retencion_cuerpo         WHERE cod_dep=$1 and ano_contrato_obra=$2;
        delete from cobd01_contratoobras_modificacion_cuerpo      WHERE cod_dep=$1 and ano_contrato_obra=$2;
        delete from cobd01_contratoobras_anticipo_cuerpo          WHERE cod_dep=$1 and ano_contrato_obra=$2;
        delete from cobd01_contratoobras_cuerpo                   WHERE cod_dep=$1 and ano_contrato_obra=$2;	

	
$BODY$
  LANGUAGE 'sql' VOLATILE
  COST 100;
ALTER FUNCTION borrar_cobp01_dep(integer, integer) OWNER TO sisap;















-- Function: borrar_cepd02_dep(integer, integer)

-- DROP FUNCTION borrar_cepd02_dep(integer, integer);

CREATE OR REPLACE FUNCTION borrar_cepd02_dep(integer, integer)
  RETURNS void AS
$BODY$
	

        delete from cepd02_contratoservicio_partidas                 WHERE cod_dep=$1 and ano_contrato_servicio=$2;
	delete from cepd02_contratoservicio_anticipo_partidas        WHERE cod_dep=$1 and ano_contrato_servicio=$2;
	delete from cepd02_contratoservicio_modificacion_partidas    WHERE cod_dep=$1 and ano_contrato_servicio=$2;
	delete from cepd02_contratoservicio_retencion_partidas       WHERE cod_dep=$1 and ano_contrato_servicio=$2;
	delete from cepd02_contratoservicio_valuacion_partidas       WHERE cod_dep=$1 and ano_contrato_servicio=$2;

        delete from cepd02_contratoservicio_valuacion_cuerpo         WHERE cod_dep=$1 and ano_contrato_servicio=$2;
        delete from cepd02_contratoservicio_retencion_cuerpo         WHERE cod_dep=$1 and ano_contrato_servicio=$2;
        delete from cepd02_contratoservicio_modificacion_cuerpo      WHERE cod_dep=$1 and ano_contrato_servicio=$2;
        delete from cepd02_contratoservicio_anticipo_cuerpo          WHERE cod_dep=$1 and ano_contrato_servicio=$2;
        delete from cepd02_contratoservicio_cuerpo                   WHERE cod_dep=$1 and ano_contrato_servicio=$2;	

	
$BODY$
  LANGUAGE 'sql' VOLATILE
  COST 100;
ALTER FUNCTION borrar_cepd02_dep(integer, integer) OWNER TO sisap;












-- Function: borrar_cstd09_notadebito_dep(integer, integer)

-- DROP FUNCTION borrar_cstd09_notadebito_dep(integer, integer);

CREATE OR REPLACE FUNCTION borrar_cstd09_notadebito_dep(integer, integer)
  RETURNS void AS
$BODY$
	

         DELETE FROM cstd09_notadebito_cuerpo    WHERE cod_dep=$1 AND ano_movimiento=$2;
         DELETE FROM cstd09_notadebito_ordenes   WHERE cod_dep=$1 AND ano_movimiento=$2;
         DELETE FROM cstd09_notadebito_partidas  WHERE cod_dep=$1 AND ano_movimiento=$2;
         DELETE FROM cstd09_notadebito_poremitir WHERE cod_dep=$1 AND ano_movimiento=$2;


	
$BODY$
  LANGUAGE 'sql' VOLATILE
  COST 100;
ALTER FUNCTION borrar_cstd09_notadebito_dep(integer, integer) OWNER TO sisap;












-- Function: borrar_todo_ejecucion_dep(integer, integer)

-- DROP FUNCTION borrar_todo_ejecucion_dep(integer, integer);

CREATE OR REPLACE FUNCTION borrar_todo_ejecucion_dep(integer, integer)
  RETURNS void AS
$BODY$
	

   select borrar_cheques_dep($1, $2);
   select borrar_orden_pago_dep($1, $2);
   select borrar_oc_dep($1, $2);
   select borrar_rc_dep($1, $2);
   select borrar_cobp01_dep($1, $2);
   select borrar_cepd02_dep($1, $2);
   select borrar_cstd09_notadebito_dep($1, $2);
	
$BODY$
  LANGUAGE 'sql' VOLATILE
  COST 100;
ALTER FUNCTION borrar_todo_ejecucion_dep(integer, integer) OWNER TO sisap;




















