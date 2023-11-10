-- Function: pasar_personal_presupuesto(integer)

-- DROP FUNCTION pasar_personal_presupuesto(integer);

CREATE OR REPLACE FUNCTION pasar_modulos_nueva_dependencia(integer,integer)
  RETURNS text AS
$BODY$
DECLARE
        c integer=1;
	recArrd05 record;
	curArrd05 cursor for SELECT *FROM arrd05 where cod_dep!=1;
	recModulos record;
	curModulos cursor for SELECT *FROM modulos where cod_tipo_inst=$1 and cod_inst=$2 and cod_dep=1;
begin

      	open curArrd05;			
		loop
			fetch curArrd05 into recArrd05;			
			exit when not found;
			open curModulos;
				loop
				fetch curModulos into recModulos;			
			        exit when not found;
			        c=(select count(*) from modulos where cod_tipo_inst=$1 and cod_inst=$2 and cod_dep=recArrd05.cod_dep and cod_modulo=recModulos.cod_modulo);
                                if c=0 then
			        INSERT INTO modulos(cod_tipo_inst,cod_inst,cod_dep, cod_modulo, denominacion, status, orden_ubicacion)
                                       VALUES ($1, $2, recArrd05.cod_dep, recModulos.cod_modulo, recModulos.denominacion, recModulos.status, recModulos.orden_ubicacion);
                                end if;
                                end loop;
                        close curModulos;

		end loop;
		close curArrd05;
	return 'LISTO';

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION pasar_modulos_nueva_dependencia(integer,integer) OWNER TO sisap;

select pasar_modulos_nueva_dependencia(30,11);