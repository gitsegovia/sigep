
CREATE OR REPLACE FUNCTION insetar_en_cnmd03_transacciones(lista integer[], lista2 integer[])
  RETURNS text AS



$BODY$
	DECLARE
          t text;
          contar integer;
      lis_cod_presi         integer    =   lista[1];
	  lis_cod_entidad       integer    =   lista[2];
	  lis_cod_tipo_inst     integer    =   lista[3];
	  lis_cod_inst          integer    =   lista[4];
	  lis_cod_dep           integer    =   lista[5];
	  lis_cod_tipo_nomina   integer    =   lista[6];
	  lis_cod_dir_superior  integer    =   lista[7];
	  lis_cod_coordinacion  integer    =   lista[8];
	  lis_cod_secretaria    integer    =   lista[9];
	  lis_cod_direccion     integer    =   lista[10];
	  lis_cod_division      integer    =   lista[11];
	  lis_cod_departamento  integer    =   lista[12];
	  lis_cod_oficina       integer    =   lista[13];
	  lis_ano               integer    =   lista[14];
	  lis_cod_sector        integer    =   lista[15];
	  lis_cod_programa      integer    =   lista[16];
	  lis_cod_sub_prog      integer    =   lista[17];
	  lis_cod_proyecto      integer    =   lista[18];
	  lis_cod_activ_obra    integer    =   lista[19];




	  lis2_cod_tipo_transaccion  integer   =     lista2[1];
	  lis2_cod_transaccion       integer   =     lista2[2];
	  lis2_ano                   integer   =     lista2[3];
	  lis2_cod_sector            integer   =     lista2[4];
	  lis2_cod_programa          integer   =     lista2[5];
	  lis2_cod_sub_prog          integer   =     lista2[6];
	  lis2_cod_proyecto          integer   =     lista2[7];
	  lis2_cod_activ_obra        integer   =     lista2[8];
	  lis2_cod_partida           integer   =     lista2[9];
	  lis2_cod_generica          integer   =     lista2[10];
	  lis2_cod_especifica        integer   =     lista2[11];
	  lis2_cod_sub_espec         integer   =     lista2[12];
	  lis2_cod_auxiliar          integer   =     lista2[13];





               recCNMD05 record;
               curCNMD05 cursor for SELECT * FROM cnmd05 WHERE
							  cod_presi         =   lis_cod_presi         and
							  cod_entidad       =   lis_cod_entidad       and
							  cod_tipo_inst     =   lis_cod_tipo_inst     and
							  cod_inst          =   lis_cod_inst          and
							  cod_dep           =   lis_cod_dep           and
							  cod_tipo_nomina   =   lis_cod_tipo_nomina   and
							  cod_dir_superior  =   lis_cod_dir_superior  and
							  cod_coordinacion  =   lis_cod_coordinacion  and
							  cod_secretaria    =   lis_cod_secretaria    and
							  cod_direccion     =   lis_cod_direccion     and
							  cod_division      =   lis_cod_division      and
							  cod_departamento  =   lis_cod_departamento  and
							  cod_oficina       =   lis_cod_oficina       and
							  ano               =   lis_ano               and
							  cod_sector        =   lis_cod_sector        and
							  cod_programa      =   lis_cod_programa      and
							  cod_sub_prog      =   lis_cod_sub_prog      and
							  cod_proyecto      =   lis_cod_proyecto      and
							  cod_activ_obra    =   lis_cod_activ_obra;




		BEGIN
	             		               open curCNMD05;
					       loop
							fetch curCNMD05 into recCNMD05;
							exit when not found;

							     contar = ( SELECT COUNT(x.*)  FROM cnmd03_conexion_transacciones x WHERE
											                       x.cod_presi             = recCNMD05.cod_presi     and
							                                       x.cod_entidad           = recCNMD05.cod_entidad   and
							                                       x.cod_tipo_inst         = recCNMD05.cod_tipo_inst and
							                                       x.cod_inst              = recCNMD05.cod_inst      and
							                                       x.cod_dep               = recCNMD05.cod_dep       and
							                                       x.cod_tipo_nomina       = recCNMD05.cod_tipo_nomina and
							                                       x.cod_cargo             = recCNMD05.cod_cargo       and
							                                       x.cod_tipo_transaccion  = lis2_cod_tipo_transaccion and
							                                       x.cod_transaccion       = lis2_cod_transaccion      and
							                                       x.ano                   = lis2_ano);


                                  if contar = 0 then


				                        INSERT INTO  cnmd03_conexion_transacciones VALUES (
				                                    recCNMD05.cod_presi,
													recCNMD05.cod_entidad,
													recCNMD05.cod_tipo_inst,
													recCNMD05.cod_inst,
													recCNMD05.cod_dep,
													recCNMD05.cod_tipo_nomina,
													recCNMD05.cod_cargo,
													lis2_cod_tipo_transaccion,
													lis2_cod_transaccion,
													lis2_ano,
													lis2_cod_sector,
													lis2_cod_programa,
													lis2_cod_sub_prog,
													lis2_cod_proyecto,
													lis2_cod_activ_obra,
													lis2_cod_partida,
													lis2_cod_generica,
													lis2_cod_especifica,
													lis2_cod_sub_espec,
													lis2_cod_auxiliar);


								  else

                                        UPDATE cnmd03_conexion_transacciones set
												ano            = lis2_ano,
												cod_sector     = lis2_cod_sector,
												cod_programa   = lis2_cod_programa,
												cod_sub_prog   = lis2_cod_sub_prog,
												cod_proyecto   = lis2_cod_proyecto,
												cod_activ_obra = lis2_cod_activ_obra,
												cod_partida    = lis2_cod_partida,
												cod_generica   = lis2_cod_generica,
												cod_especifica = lis2_cod_especifica,
												cod_sub_espec  = lis2_cod_sub_espec,
												cod_auxiliar   = lis2_cod_auxiliar
										WHERE
						                        cod_presi             = recCNMD05.cod_presi       and
		                                        cod_entidad           = recCNMD05.cod_entidad     and
		                                        cod_tipo_inst         = recCNMD05.cod_tipo_inst   and
		                                        cod_inst              = recCNMD05.cod_inst        and
		                                        cod_dep               = recCNMD05.cod_dep         and
		                                        cod_tipo_nomina       = recCNMD05.cod_tipo_nomina and
		                                        cod_cargo             = recCNMD05.cod_cargo       and
		                                        cod_tipo_transaccion  = lis2_cod_tipo_transaccion and
		                                        cod_transaccion       = lis2_cod_transaccion      and
		                                        ano                   = lis2_ano;


                                   end if;


				               end loop;
				               close curCNMD05;



                        t = 'si';
                        RETURN t;
                        --COMMIT;
                EXCEPTION

                      WHEN OTHERS THEN

                      t = 'no';
		              RETURN t;
		              --ROLLBACK;
	        END;
$BODY$

LANGUAGE 'plpgsql' VOLATILE
COST 100;




ALTER FUNCTION insetar_en_cnmd03_transacciones(lista integer[], lista2 integer[]) OWNER TO sisap;
