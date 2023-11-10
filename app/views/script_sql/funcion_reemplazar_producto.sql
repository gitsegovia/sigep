

-- Usar: variable_1 = al año, variable_2 = al codigo actual, variable_3 = al codigo a reemplazar

--select funcion_reemplazar_producto(variable_1 integer, variable_2 integer, variable_3 integer);




CREATE OR REPLACE FUNCTION funcion_reemplazar_producto(variable_1 integer, variable_2 integer, variable_3 integer)
  RETURNS text AS



$BODY$
	DECLARE
          t text;

		BEGIN

                  UPDATE cscd02_solicitud_cuerpo                SET codigo_prod_serv=$3    WHERE ano_solicitud=$1      and codigo_prod_serv=$2;
		          UPDATE cscd02_solicitud_cuerpo_anulado        SET codigo_prod_serv=$3    WHERE ano_solicitud=$1      and codigo_prod_serv=$2;
		          UPDATE cscd03_cotizacion_cuerpo               SET codigo_prod_serv=$3    WHERE ano_cotizacion=$1     and codigo_prod_serv=$2;
		          UPDATE cscd03_cotizacion_cuerpo_anulado       SET codigo_prod_serv=$3    WHERE ano_cotizacion=$1     and codigo_prod_serv=$2;
		          UPDATE cscd05_ordencompra_nota_entrega_cuerpo SET codigo_prod_serv=$3    WHERE ano_nota_entrega=$1   and codigo_prod_serv=$2;

		          DELETE FROM cscd01_catalogo  WHERE codigo_prod_serv=$2;

                        t = 'si';
                        RETURN t;
                        COMMIT;
                EXCEPTION

                      WHEN OTHERS THEN

                      t = 'no';
		              RETURN t;
		              ROLLBACK;
	        END;
$BODY$

LANGUAGE 'plpgsql' VOLATILE
COST 100;




ALTER FUNCTION funcion_reemplazar_producto(variable_1 integer, variable_2 integer, variable_3 integer) OWNER TO sisap;





