
CREATE OR REPLACE FUNCTION funcion_actualizar_clasificador_original(variable integer)
  RETURNS text AS



$BODY$
	DECLARE
          t text;
          contar integer;


               rec_cfpd01_ano_1_grupo record;
               cur_cfpd01_ano_1_grupo      cursor for SELECT * FROM cfpd01_ano_1_grupo      where ejercicio=$1;

               rec_cfpd01_ano_2_partida record;
               cur_cfpd01_ano_2_partida    cursor for SELECT * FROM cfpd01_ano_2_partida    where ejercicio=$1;

               rec_cfpd01_ano_3_generica record;
               cur_cfpd01_ano_3_generica   cursor for SELECT * FROM cfpd01_ano_3_generica   where ejercicio=$1;

               rec_cfpd01_ano_4_especifica record;
               cur_cfpd01_ano_4_especifica cursor for SELECT * FROM cfpd01_ano_4_especifica where ejercicio=$1;

               rec_cfpd01_ano_5_sub_espec record;
               cur_cfpd01_ano_5_sub_espec  cursor for SELECT * FROM cfpd01_ano_5_sub_espec  where ejercicio=$1;

               rec_cfpd01_ano_6_auxiliar record;
               cur_cfpd01_ano_6_auxiliar   cursor for SELECT * FROM cfpd01_ano_6_auxiliar   where ejercicio=$1;




		BEGIN

-- ********************   GRUPO  **********************
	             		   open cur_cfpd01_ano_1_grupo;
					       loop
							fetch cur_cfpd01_ano_1_grupo into rec_cfpd01_ano_1_grupo;
							exit when not found;

							     contar = ( SELECT COUNT(x.*)  FROM cfpd01_grupo x WHERE
											                       x.cod_grupo      = rec_cfpd01_ano_1_grupo.cod_grupo);


                                  if contar = 0 then

				                        INSERT INTO  cfpd01_grupo VALUES (
				                                    rec_cfpd01_ano_1_grupo.cod_grupo,
													rec_cfpd01_ano_1_grupo.denominacion,
													rec_cfpd01_ano_1_grupo.concepto);
                                   end if;

				               end loop;
				               close cur_cfpd01_ano_1_grupo;


-- ********************   PARTIDA  **********************



				            open cur_cfpd01_ano_2_partida;
					        loop
							fetch cur_cfpd01_ano_2_partida into rec_cfpd01_ano_2_partida;
							exit when not found;

							     contar = ( SELECT COUNT(x.*)  FROM cfpd01_partida x WHERE
											                       x.cod_grupo      = rec_cfpd01_ano_2_partida.cod_grupo   and
											                       x.cod_partida    = rec_cfpd01_ano_2_partida.cod_partida);


                                  if contar = 0 then

				                        INSERT INTO  cfpd01_partida VALUES (
				                                    rec_cfpd01_ano_2_partida.cod_grupo,
				                                    rec_cfpd01_ano_2_partida.cod_partida,
													rec_cfpd01_ano_2_partida.denominacion,
													rec_cfpd01_ano_2_partida.concepto);
                                   end if;

				               end loop;
				               close cur_cfpd01_ano_2_partida;



-- ********************   GENERICA  **********************



				            open cur_cfpd01_ano_3_generica;
					        loop
							fetch cur_cfpd01_ano_3_generica into rec_cfpd01_ano_3_generica;
							exit when not found;

							     contar = ( SELECT COUNT(x.*)  FROM cfpd01_generica x WHERE
											                       x.cod_grupo      = rec_cfpd01_ano_3_generica.cod_grupo     and
											                       x.cod_partida    = rec_cfpd01_ano_3_generica.cod_partida   and
											                       x.cod_generica   = rec_cfpd01_ano_3_generica.cod_generica);


                                  if contar = 0 then

				                        INSERT INTO  cfpd01_generica VALUES (
				                                    rec_cfpd01_ano_3_generica.cod_grupo,
				                                    rec_cfpd01_ano_3_generica.cod_partida,
				                                    rec_cfpd01_ano_3_generica.cod_generica,
													rec_cfpd01_ano_3_generica.denominacion,
													rec_cfpd01_ano_3_generica.concepto);
                                   end if;

				               end loop;
				               close cur_cfpd01_ano_3_generica;




-- ********************   ESPECIFICA  **********************



				            open cur_cfpd01_ano_4_especifica;
					        loop
							fetch cur_cfpd01_ano_4_especifica into rec_cfpd01_ano_4_especifica;
							exit when not found;

							     contar = ( SELECT COUNT(x.*)  FROM cfpd01_especifica x WHERE
											                       x.cod_grupo      = rec_cfpd01_ano_4_especifica.cod_grupo     and
											                       x.cod_partida    = rec_cfpd01_ano_4_especifica.cod_partida   and
											                       x.cod_generica   = rec_cfpd01_ano_4_especifica.cod_generica   and
											                       x.cod_especifica = rec_cfpd01_ano_4_especifica.cod_especifica);


                                  if contar = 0 then

				                        INSERT INTO  cfpd01_especifica VALUES (
				                                    rec_cfpd01_ano_4_especifica.cod_grupo,
				                                    rec_cfpd01_ano_4_especifica.cod_partida,
				                                    rec_cfpd01_ano_4_especifica.cod_generica,
				                                    rec_cfpd01_ano_4_especifica.cod_especifica,
													rec_cfpd01_ano_4_especifica.denominacion,
													rec_cfpd01_ano_4_especifica.concepto);
                                   end if;

				               end loop;
				               close cur_cfpd01_ano_4_especifica;




-- ********************   SUB - ESPECIFICA  **********************




                            open cur_cfpd01_ano_5_sub_espec;
					        loop
							fetch cur_cfpd01_ano_5_sub_espec into rec_cfpd01_ano_5_sub_espec;
							exit when not found;

							     contar = ( SELECT COUNT(x.*)  FROM cfpd01_sub_espec x WHERE
											                       x.cod_grupo      = rec_cfpd01_ano_5_sub_espec.cod_grupo     and
											                       x.cod_partida    = rec_cfpd01_ano_5_sub_espec.cod_partida   and
											                       x.cod_generica   = rec_cfpd01_ano_5_sub_espec.cod_generica   and
											                       x.cod_especifica = rec_cfpd01_ano_5_sub_espec.cod_especifica   and
											                       x.cod_sub_espec  = rec_cfpd01_ano_5_sub_espec.cod_sub_espec);


                                  if contar = 0 then

				                        INSERT INTO  cfpd01_sub_espec VALUES (
				                                    rec_cfpd01_ano_5_sub_espec.cod_grupo,
				                                    rec_cfpd01_ano_5_sub_espec.cod_partida,
				                                    rec_cfpd01_ano_5_sub_espec.cod_generica,
				                                    rec_cfpd01_ano_5_sub_espec.cod_especifica,
				                                    rec_cfpd01_ano_5_sub_espec.cod_sub_espec,
													rec_cfpd01_ano_5_sub_espec.denominacion,
													rec_cfpd01_ano_5_sub_espec.concepto);
                                   end if;

				               end loop;
				               close cur_cfpd01_ano_5_sub_espec;




-- ********************   AUXILIAR  **********************




                            open cur_cfpd01_ano_6_auxiliar;
					        loop
							fetch cur_cfpd01_ano_6_auxiliar into rec_cfpd01_ano_6_auxiliar;
							exit when not found;

							     contar = ( SELECT COUNT(x.*)  FROM cfpd01_auxiliar x WHERE
											                       x.cod_grupo      = rec_cfpd01_ano_6_auxiliar.cod_grupo     and
											                       x.cod_partida    = rec_cfpd01_ano_6_auxiliar.cod_partida   and
											                       x.cod_generica   = rec_cfpd01_ano_6_auxiliar.cod_generica   and
											                       x.cod_especifica = rec_cfpd01_ano_6_auxiliar.cod_especifica   and
											                       x.cod_sub_espec  = rec_cfpd01_ano_6_auxiliar.cod_sub_espec   and
											                       x.cod_auxiliar   = rec_cfpd01_ano_6_auxiliar.cod_auxiliar);


                                  if contar = 0 then

				                        INSERT INTO  cfpd01_auxiliar VALUES (
				                                    rec_cfpd01_ano_6_auxiliar.cod_grupo,
				                                    rec_cfpd01_ano_6_auxiliar.cod_partida,
				                                    rec_cfpd01_ano_6_auxiliar.cod_generica,
				                                    rec_cfpd01_ano_6_auxiliar.cod_especifica,
				                                    rec_cfpd01_ano_6_auxiliar.cod_sub_espec,
				                                    rec_cfpd01_ano_6_auxiliar.cod_auxiliar,
													rec_cfpd01_ano_6_auxiliar.denominacion,
													rec_cfpd01_ano_6_auxiliar.concepto);
                                   end if;

				               end loop;
				               close cur_cfpd01_ano_6_auxiliar;





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




ALTER FUNCTION funcion_actualizar_clasificador_original(variable integer) OWNER TO sisap;






select funcion_actualizar_clasificador_original(2009);