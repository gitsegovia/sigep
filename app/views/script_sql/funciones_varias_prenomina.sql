--- de aqui en adelante funcione  para la prenomina

-- Function: verifica_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION verifica_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION verifica_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   Pcod_presi            alias for $1;
   Pcod_entidad          alias for $2;
   Pcod_tipo_inst        alias for $3;
   Pcod_inst             alias for $4;
   Pcod_dep              alias for $5;
   Pcod_tipo_nomina      alias for $6;
   Pcod_cargo            alias for $7;
   Pcod_ficha            alias for $8;
   Pcod_tipo_transaccion alias for $9;
   Pcod_transaccion      alias for $10;

   retornar integer = 0;

	     Rtrans_actuales record;
             Ctrans_actuales cursor (Pcod_presi int4, Pcod_entidad int4, Pcod_tipo_inst int4, Pcod_inst int4, Pcod_dep int4, Pcod_tipo_nomina int4, Pcod_cargo int4, Pcod_ficha int4, Pcod_tipo_transaccion int4, Pcod_transaccion int4) for SELECT count(*) as cantidad FROM cnmd07_transacciones_actuales WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha AND cod_tipo_transaccion=Pcod_tipo_transaccion AND cod_transaccion=Pcod_transaccion;
begin
            open Ctrans_actuales (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina, Pcod_cargo,Pcod_ficha,Pcod_tipo_transaccion,Pcod_transaccion);
		loop
			fetch Ctrans_actuales into Rtrans_actuales;
			exit when not found;
			   retornar=Rtrans_actuales.cantidad;
                    end loop;
                close Ctrans_actuales;

	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION verifica_cnmd07_transacciones_actuales(integer, integer, integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: insetar_en_cnmd03_transacciones_direcciones(integer[], integer[])

-- DROP FUNCTION insetar_en_cnmd03_transacciones_direcciones(integer[], integer[]);

CREATE OR REPLACE FUNCTION insetar_en_cnmd03_transacciones_direcciones(lista integer[], lista2 integer[])
  RETURNS text AS
$BODY$
	DECLARE
          t text;
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
							  cod_tipo_nomina   =   lis_cod_tipo_nomina;




		BEGIN
	             		               open curCNMD05;
					       loop
							fetch curCNMD05 into recCNMD05;
							exit when not found;
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
ALTER FUNCTION insetar_en_cnmd03_transacciones_direcciones(integer[], integer[]) OWNER TO sisap;


-- Function: insetar_en_cnmd03_transacciones(integer[], integer[])

-- DROP FUNCTION insetar_en_cnmd03_transacciones(integer[], integer[]);

CREATE OR REPLACE FUNCTION insetar_en_cnmd03_transacciones(lista integer[], lista2 integer[])
  RETURNS text AS
$BODY$
	DECLARE
          t text;
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
ALTER FUNCTION insetar_en_cnmd03_transacciones(integer[], integer[]) OWNER TO sisap;

-- Function: devolver_sueldo_basico_cnmd05(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_sueldo_basico_cnmd05(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_sueldo_basico_cnmd05(integer, integer, integer, integer, integer, integer, integer, integer)
  RETURNS numeric AS
$BODY$
DECLARE
   Pcod_presi            alias for $1;
   Pcod_entidad          alias for $2;
   Pcod_tipo_inst        alias for $3;
   Pcod_inst             alias for $4;
   Pcod_dep              alias for $5;
   Pcod_tipo_nomina      alias for $6;
   Pcod_cargo            alias for $7;
   Pcod_ficha            alias for $8;
   r_sueldo_basico  numeric(26,2);

BEGIN
 r_sueldo_basico=(SELECT sueldo_basico FROM cnmd05 WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha);

RETURN r_sueldo_basico;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_sueldo_basico_cnmd05(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: devolver_grado_puesto(integer, integer)

-- DROP FUNCTION devolver_grado_puesto(integer, integer);

CREATE OR REPLACE FUNCTION devolver_grado_puesto(integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
t integer;

BEGIN
	    IF $1 = 1 THEN

	      t = (select grado from cnmd02_empleados_puestos where cod_puesto = $2);

	elsif $1 = 2 THEN

	      t = (select grado from cnmd02_obreros_puestos where cod_puesto = $2);

        else
              t = (select grado from cnmd02_varios_puestos where cod_puesto = $2);

	end if;

RETURN t;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_grado_puesto(integer, integer) OWNER TO sisap;


-- Function: devolver_dias_t_i_e(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_dias_t_i_e(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_dias_t_i_e(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer)
  RETURNS integer AS
$BODY$
DECLARE
   r_dias_i_e  integer = 0;
BEGIN
 r_dias_i_e=(SELECT dias FROM cnmd09_dias_trabajados_ingreso_egreso WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha);
 if r_dias_i_e is null then
   r_dias_i_e = 0;
 end if;
RETURN r_dias_i_e;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_dias_t_i_e(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: devolver_dias_faltas(integer, integer, integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION devolver_dias_faltas(integer, integer, integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION devolver_dias_faltas(pcod_presi integer, pcod_entidad integer, pcod_tipo_inst integer, pcod_inst integer, pcod_dep integer, pcod_tipo_nomina integer, pcod_cargo integer, pcod_ficha integer)
  RETURNS integer AS
$BODY$
DECLARE
   r_dias_faltas  integer = 0;
BEGIN
 r_dias_faltas=(SELECT dias FROM cnmd09_dias_trabajados_falta WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina AND cod_cargo=Pcod_cargo AND cod_ficha=Pcod_ficha);
 if r_dias_faltas is null then
   r_dias_faltas = 0;
 end if;
RETURN r_dias_faltas;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION devolver_dias_faltas(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;

-- Function: d_transaccion(integer)

-- DROP FUNCTION d_transaccion(integer);

CREATE OR REPLACE FUNCTION d_transaccion(integer)
  RETURNS text AS
$BODY$
DECLARE
t text;
BEGIN
IF $1 is null THEN
t = '';
elsif $1 = 1 THEN
t = 'ASIGNACIÓN';
else
t = 'DEDUCCIÓN';
end if;

RETURN t;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION d_transaccion(integer) OWNER TO sisap;

-- Function: bajar_trans_viadiskette_trans_actuales(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION bajar_trans_viadiskette_trans_actuales(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION bajar_trans_viadiskette_trans_actuales(integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;


   retornar integer = 0;

	     Rviadiskette record;
             Cviadiskette cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT * FROM cnmd07_transacciones_viadiskette WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
             Rviadiskette_c record;
             Cviadiskette_c cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT count(*) as cantidad FROM cnmd07_transacciones_viadiskette WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
begin
            open Cviadiskette_c (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
		loop
			fetch Cviadiskette_c into Rviadiskette_c;
			exit when not found;
			if Rviadiskette_c.cantidad!=0 then
				open Cviadiskette (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
				loop
					fetch Cviadiskette into Rviadiskette;
					exit when not found;
					v = verifica_cnmd07_transacciones_actuales(Rviadiskette.cod_presi, Rviadiskette.cod_entidad, Rviadiskette.cod_tipo_inst, Rviadiskette.cod_inst, Rviadiskette.cod_dep, Rviadiskette.cod_tipo_nomina, Rviadiskette.cod_cargo, Rviadiskette.cod_ficha, Rviadiskette.cod_tipo_transaccion, Rviadiskette.cod_transaccion);
					if v=0 then
					   INSERT INTO cnmd05_cargos_eliminados values (Rviadiskette.cod_presi, Rviadiskette.cod_entidad, Rviadiskette.cod_tipo_inst, Rviadiskette.cod_inst, Rviadiskette.cod_dep, Rviadiskette.cod_tipo_nomina, Rviadiskette.cod_cargo, Rviadiskette.cod_ficha, Rviadiskette.cod_tipo_transaccion, Rviadiskette.cod_transaccion,Rviadiskette.fecha_transaccion, Rviadiskette.monto_original, Rviadiskette.numero_cuotas_descontar, Rviadiskette.numero_cuotas_cancelar,Rviadiskette.numero_cuotas_canceladas, Rviadiskette.monto_cuota, Rviadiskette.saldo, Rviadiskette.marca_fin_descuento, Rviadiskette.fecha_proceso, Rviadiskette.username);
					else
					   UPDATE cnmd07_transacciones_actuales
					   SET fecha_transaccion=Rviadiskette.fecha_transaccion, monto_original=Rviadiskette.monto_original, numero_cuotas_descontar=Rviadiskette.numero_cuotas_descontar,
					       numero_cuotas_cancelar=Rviadiskette.numero_cuotas_cancelar, numero_cuotas_canceladas=Rviadiskette.numero_cuotas_canceladas, monto_cuota=Rviadiskette.monto_cuota,
					       saldo=Rviadiskette.saldo, marca_fin_descuento=Rviadiskette.marca_fin_descuento, fecha_proceso=Rviadiskette.fecha_proceso, username=Rviadiskette.username
					 WHERE cod_presi=Rviadiskette.cod_presi AND cod_entidad=Rviadiskette.cod_entidad AND cod_tipo_inst=Rviadiskette.cod_tipo_inst AND cod_inst=Rviadiskette.cod_inst AND cod_dep=Rviadiskette.cod_dep AND cod_tipo_nomina=Rviadiskette.cod_tipo_nomina AND cod_cargo=Rviadiskette.cod_cargo AND cod_ficha=Rviadiskette.cod_ficha AND cod_tipo_transaccion=Rviadiskette.cod_tipo_transaccion AND cod_transaccion=Rviadiskette.cod_transaccion;
					end if;
				end loop;
				close Cviadiskette;
                    end if;
                    end loop;
                close Cviadiskette_c;

	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION bajar_trans_viadiskette_trans_actuales(integer, integer, integer, integer, integer, integer) OWNER TO sisap;


-- Function: bajar_trans_suspen_trans_actuales(integer, integer, integer, integer, integer, integer)

-- DROP FUNCTION bajar_trans_suspen_trans_actuales(integer, integer, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION bajar_trans_suspen_trans_actuales(integer, integer, integer, integer, integer, integer)
  RETURNS integer AS
$BODY$
DECLARE
   v integer = 0;
   vcod_presi       alias for $1;
   vcod_entidad     alias for $2;
   vcod_tipo_inst   alias for $3;
   vcod_inst        alias for $4;
   vcod_dep         alias for $5;
   vcod_tipo_nomina alias for $6;


   retornar integer = 0;

	     Rsuspendida record;
             Csuspendida cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT * FROM cnmd07_transacciones_suspendidas WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
             Rsuspendida_c record;
             Csuspendida_c cursor (vcod_presi int4, vcod_entidad int4, vcod_tipo_inst int4, vcod_inst int4, vcod_dep int4, vcod_tipo_nomina int4) for SELECT count(*) as cantidad FROM cnmd07_transacciones_suspendidas WHERE cod_presi=vcod_presi AND cod_entidad=vcod_entidad AND cod_tipo_inst=vcod_tipo_inst AND cod_inst=vcod_inst AND cod_dep=vcod_dep AND cod_tipo_nomina=vcod_tipo_nomina;
begin
            open Csuspendida_c (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
		loop
			fetch Csuspendida_c into Rsuspendida_c;
			exit when not found;
			if Rsuspendida_c.cantidad!=0 then
				open Csuspendida (vcod_presi, vcod_entidad, vcod_tipo_inst, vcod_inst, vcod_dep, vcod_tipo_nomina);
				loop
					fetch Csuspendida into Rsuspendida;
					exit when not found;
					v=verifica_cnmd07_transacciones_actuales(Rsuspendida.cod_presi, Rsuspendida.cod_entidad, Rsuspendida.cod_tipo_inst, Rsuspendida.cod_inst, Rsuspendida.cod_dep, Rsuspendida.cod_tipo_nomina, Rsuspendida.cod_cargo, Rsuspendida.cod_ficha, Rsuspendida.cod_tipo_transaccion, Rsuspendida.cod_transaccion);
					if v=0 then
					   INSERT INTO cnmd05_cargos_eliminados values (Rsuspendida.cod_presi, Rsuspendida.cod_entidad, Rsuspendida.cod_tipo_inst, Rsuspendida.cod_inst, Rsuspendida.cod_dep, Rsuspendida.cod_tipo_nomina, Rsuspendida.cod_cargo, Rsuspendida.cod_ficha, Rsuspendida.cod_tipo_transaccion, Rsuspendida.cod_transaccion,Rsuspendida.fecha_transaccion, Rsuspendida.monto_original, Rsuspendida.numero_cuotas_descontar, Rsuspendida.numero_cuotas_cancelar,Rsuspendida.numero_cuotas_canceladas, Rsuspendida.monto_cuota, Rsuspendida.saldo, Rsuspendida.marca_fin_descuento, Rsuspendida.fecha_proceso, Rsuspendida.username);
					else
					   UPDATE cnmd07_transacciones_actuales
					   SET fecha_transaccion=Rsuspendida.fecha_transaccion, monto_original=Rsuspendida.monto_original, numero_cuotas_descontar=Rsuspendida.numero_cuotas_descontar,
					       numero_cuotas_cancelar=Rsuspendida.numero_cuotas_cancelar, numero_cuotas_canceladas=Rsuspendida.numero_cuotas_canceladas, monto_cuota=Rsuspendida.monto_cuota,
					       saldo=Rsuspendida.saldo, marca_fin_descuento=Rsuspendida.marca_fin_descuento, fecha_proceso=Rsuspendida.fecha_proceso, username=Rsuspendida.username
					 WHERE cod_presi=Rsuspendida.cod_presi AND cod_entidad=Rsuspendida.cod_entidad AND cod_tipo_inst=Rsuspendida.cod_tipo_inst AND cod_inst=Rsuspendida.cod_inst AND cod_dep=Rsuspendida.cod_dep AND cod_tipo_nomina=Rsuspendida.cod_tipo_nomina AND cod_cargo=Rsuspendida.cod_cargo AND cod_ficha=Rsuspendida.cod_ficha AND cod_tipo_transaccion=Rsuspendida.cod_tipo_transaccion AND cod_transaccion=Rsuspendida.cod_transaccion;
					end if;
					DELETE FROM cnmd07_transacciones_suspendidas WHERE cod_presi=Rsuspendida.cod_presi AND cod_entidad=Rsuspendida.cod_entidad AND cod_tipo_inst=Rsuspendida.cod_tipo_inst AND cod_inst=Rsuspendida.cod_inst AND cod_dep=Rsuspendida.cod_dep AND cod_tipo_nomina=Rsuspendida.cod_tipo_nomina AND cod_cargo=Rsuspendida.cod_cargo AND cod_ficha=Rsuspendida.cod_ficha AND cod_tipo_transaccion=Rsuspendida.cod_tipo_transaccion AND cod_transaccion=Rsuspendida.cod_transaccion;
				end loop;
				close Csuspendida;
                    end if;
                    end loop;
                close Csuspendida_c;

	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION bajar_trans_suspen_trans_actuales(integer, integer, integer, integer, integer, integer) OWNER TO sisap;

----
-------
-------AQUI LA PRENOMINA COMO TAL bueno creo que la primera parte

CREATE OR REPLACE FUNCTION proceso_prenomina_1(Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer, Pfecha_periodo_hasta date)
  RETURNS integer AS
$BODY$
DECLARE
   SUELDO numeric (26.2) = 0;
   DIAS_F integer;
   DIAS_IE integer;
   dias_cobro integer;
   dias_cobro1 integer;
   dias_cobro2 integer;
   registrada_ctqcct integer;
   prorrateo integer;
   calc_monto_cuota numeric (26.2);
   retornar integer = 0;

	     --SELECT * FROM  cnmd06_fichas_clasi_personal WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."  OR (clasificacion_personal=5 AND fecha_terminacion_contrato > '".$periodo_hasta."')

	     Rcnmd06_fichasv record;
             Ccnmd06_fichasv cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT * FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina  OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta);
             Rcnmd06_fichasvc record;
             Ccnmd06_fichasvc cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer,Pfecha_periodo_hasta date) for SELECT count(*) as cantidad FROM  cnmd06_fichas_clasi_personal  WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina  OR (clasificacion_personal=5 AND fecha_terminacion_contrato > Pfecha_periodo_hasta);
             Rcnmd07_trans_actuales record;
             Ccnmd07_trans_actuales cursor (Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer) for SELECT * FROM v_cnmd07_transacciones_actuales_deno WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina;


begin
            open Ccnmd06_fichasvc (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
		loop
			fetch Ccnmd06_fichasvc into Rcnmd06_fichasvc;
			exit when not found;
			if Rcnmd06_fichasvc.cantidad!=0 then
				open Ccnmd06_fichasv (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina,Pfecha_periodo_hasta);
				loop
					fetch Ccnmd06_fichasv into Rcnmd06_fichasv;
					exit when not found;
					SUELDO = devolver_sueldo_basico_cnmd05(Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_cargo, Rcnmd06_fichasv.cod_ficha);
                                        DIAS_F = devolver_dias_faltas(Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_cargo, Rcnmd06_fichasv.cod_ficha);
                                        DIAS_IE = devolver_dias_t_i_e(Rcnmd06_fichasv.cod_presi, Rcnmd06_fichasv.cod_entidad, Rcnmd06_fichasv.cod_tipo_inst, Rcnmd06_fichasv.cod_inst, Rcnmd06_fichasv.cod_dep, Rcnmd06_fichasv.cod_tipo_nomina, Rcnmd06_fichasv.cod_cargo, Rcnmd06_fichasv.cod_ficha);
                                        dias_cobro =(SELECT dias_cobro FROM cnmd01 WHERE cod_presi=Pcod_presi AND cod_entidad=Pcod_entidad AND cod_tipo_inst=Pcod_tipo_inst AND cod_inst=Pcod_inst AND cod_dep=Pcod_dep AND cod_tipo_nomina=Pcod_tipo_nomina);
                                        dias_cobro1=DIAS_F;
                                        dias_cobro2=DIAS_IE;
                                        if DIAS_F = 0 then
                                           SUELDO = (SUELDO/dias_cobro);
                                           SUELDO = (SUELDO*DIAS_IE);
                                        else
                                           SUELDO = (SUELDO/dias_cobro);
                                           SUELDO = (SUELDO*DIAS_F);
                                        end if;
					open Ccnmd07_trans_actuales (Pcod_presi, Pcod_entidad, Pcod_tipo_inst, Pcod_inst, Pcod_dep, Pcod_tipo_nomina);
					loop
					    fetch Ccnmd07_trans_actuales into Rcnmd07_trans_actuales;
					    exit when not found;
					    registrada_ctqcct = (SELECT count(*) FROM cnmd09_traba_queno_cobran_cancela_transa WHERE cod_presi=Rcnmd07_trans_actuales.cod_presi AND cod_entidad=Rcnmd07_trans_actuales.cod_entidad AND cod_tipo_inst=Rcnmd07_trans_actuales.cod_tipo_inst AND cod_inst=Rcnmd07_trans_actuales.cod_inst AND cod_dep=Rcnmd07_trans_actuales.cod_dep AND cod_tipo_nomina=Rcnmd07_trans_actuales.cod_tipo_nomina AND cod_cargo=Rcnmd07_trans_actuales.cod_cargo AND cod_ficha=Rcnmd07_trans_actuales.cod_ficha AND cod_tipo_transaccion=Rcnmd07_trans_actuales.cod_tipo_transaccion AND cod_transaccion=Rcnmd07_trans_actuales.cod_transaccion);
					    if registrada_ctqcct!=0 then
					       DELETE FROM cnmd07_transacciones_actuales WHERE cod_presi=Rcnmd07_trans_actuales.cod_presi AND cod_entidad=Rcnmd07_trans_actuales.cod_entidad AND cod_tipo_inst=Rcnmd07_trans_actuales.cod_tipo_inst AND cod_inst=Rcnmd07_trans_actuales.cod_inst AND cod_dep=Rcnmd07_trans_actuales.cod_dep AND cod_tipo_nomina=Rcnmd07_trans_actuales.cod_tipo_nomina AND cod_cargo=Rcnmd07_trans_actuales.cod_cargo AND cod_ficha=Rcnmd07_trans_actuales.cod_ficha AND cod_tipo_transaccion=Rcnmd07_trans_actuales.cod_tipo_transaccion AND cod_transaccion=Rcnmd07_trans_actuales.cod_transaccion;
					    end if;
					    if Rcnmd07_trans_actuales.uso_transaccion = 9 then
						/*INSERT INTO cnmd07_abono_cuenta(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion_padre, cod_transaccion_padre,
						    monto)
						    VALUES (Rcnmd07_trans_actuales.cod_presi, Rcnmd07_trans_actuales.cod_entidad,Rcnmd07_trans_actuales.cod_tipo_inst ,Rcnmd07_trans_actuales.cod_inst, Rcnmd07_trans_actuales.cod_dep, Rcnmd07_trans_actuales.cod_tipo_nomina,Rcnmd07_trans_actuales.cod_cargo, Rcnmd07_trans_actuales.cod_ficha,Rcnmd07_trans_actuales.cod_tipo_transaccion_padre,Rcnmd07_trans_actuales.cod_transaccion_padre,
						     Rcnmd07_trans_actuales.AQUI_FALTA_MONTOA_GUARDAR);*/
						    if dias_cobro1 != 0 then
							   prorrateo = (SELECT count(*) FROM cnmd09_transa_nosujetas_prorrateo WHERE cod_presi=Rcnmd07_trans_actuales.cod_presi AND cod_entidad=Rcnmd07_trans_actuales.cod_entidad AND cod_tipo_inst=Rcnmd07_trans_actuales.cod_tipo_inst AND cod_inst=Rcnmd07_trans_actuales.cod_inst AND cod_dep=Rcnmd07_trans_actuales.cod_dep AND cod_tipo_nomina=Rcnmd07_trans_actuales.cod_tipo_nomina AND cod_tipo_transaccion=Rcnmd07_trans_actuales.cod_tipo_transaccion AND cod_transaccion=Rcnmd07_trans_actuales.cod_transaccion);
							if prorrateo !=0 then
							    INSERT INTO cnmd07_transacciones_suspendidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_cancelar, numero_cuotas_canceladas, monto_cuota, saldo, marca_fin_descuento, fecha_proceso, username) VALUES (Rcnmd07_trans_actuales.cod_presi, Rcnmd07_trans_actuales.cod_entidad, Rcnmd07_trans_actuales.cod_tipo_inst, Rcnmd07_trans_actuales.cod_inst, Rcnmd07_trans_actuales.cod_dep, Rcnmd07_trans_actuales.cod_tipo_nomina, Rcnmd07_trans_actuales.cod_cargo, Rcnmd07_trans_actuales.cod_ficha, Rcnmd07_trans_actuales.cod_tipo_transaccion, Rcnmd07_trans_actuales.cod_transaccion, Rcnmd07_trans_actuales.fecha_transaccion, Rcnmd07_trans_actuales.monto_original, Rcnmd07_trans_actuales.numero_cuotas_descontar, Rcnmd07_trans_actuales.numero_cuotas_cancelar, Rcnmd07_trans_actuales.numero_cuotas_canceladas, Rcnmd07_trans_actuales.monto_cuota, Rcnmd07_trans_actuales.saldo, Rcnmd07_trans_actuales.marca_fin_descuento, Rcnmd07_trans_actuales.fecha_proceso, Rcnmd07_trans_actuales.username);
							    INSERT INTO cnmd07_transacciones_quecobran_incompleto(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_cancelar, numero_cuotas_canceladas, monto_cuota, saldo, marca_fin_descuento, fecha_proceso, username) VALUES (Rcnmd07_trans_actuales.cod_presi, Rcnmd07_trans_actuales.cod_entidad, Rcnmd07_trans_actuales.cod_tipo_inst, Rcnmd07_trans_actuales.cod_inst, Rcnmd07_trans_actuales.cod_dep, Rcnmd07_trans_actuales.cod_tipo_nomina, Rcnmd07_trans_actuales.cod_cargo, Rcnmd07_trans_actuales.cod_ficha, Rcnmd07_trans_actuales.cod_tipo_transaccion, Rcnmd07_trans_actuales.cod_transaccion, Rcnmd07_trans_actuales.fecha_transaccion, Rcnmd07_trans_actuales.monto_original, Rcnmd07_trans_actuales.numero_cuotas_descontar, Rcnmd07_trans_actuales.numero_cuotas_cancelar, Rcnmd07_trans_actuales.numero_cuotas_canceladas, Rcnmd07_trans_actuales.monto_cuota, Rcnmd07_trans_actuales.saldo, Rcnmd07_trans_actuales.marca_fin_descuento, Rcnmd07_trans_actuales.fecha_proceso, Rcnmd07_trans_actuales.username);
							else
							    calc_monto_cuota=((Rcnmd07_trans_actuales.monto_cuota/dias_cobro)*dias_cobro1);
							    UPDATE cnmd07_transacciones_actuales SET monto_cuota=calc_monto_cuota  WHERE cod_presi=Rcnmd07_trans_actuales.cod_presi AND cod_entidad=Rcnmd07_trans_actuales.cod_entidad AND cod_tipo_inst=Rcnmd07_trans_actuales.cod_tipo_inst AND cod_inst=Rcnmd07_trans_actuales.cod_inst AND cod_dep=Rcnmd07_trans_actuales.cod_dep AND cod_tipo_nomina=Rcnmd07_trans_actuales.cod_tipo_nomina AND cod_cargo=Rcnmd07_trans_actuales.cod_cargo AND cod_ficha=Rcnmd07_trans_actuales.cod_ficha AND cod_tipo_transaccion=Rcnmd07_trans_actuales.cod_tipo_transaccion AND cod_transaccion=Rcnmd07_trans_actuales.cod_transaccion;
							end if;/*fin prorrateo*/
						     end if;/*fin dias_cobro1 !=0*/
			                     end if;/*fin uso_transaccion = 9*/
					end loop;/*fin loop Ccnmd07_trans_actuales*/
					close Ccnmd07_trans_actuales;
				end loop;
				close Ccnmd06_fichasv;
                    end if;/*fin cantidad!=0*/
                    end loop;
                close Ccnmd06_fichasvc;

	return retornar;

end;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION proceso_prenomina_1(Pcod_presi integer, Pcod_entidad integer, Pcod_tipo_inst integer, Pcod_inst integer, Pcod_dep integer, Pcod_tipo_nomina integer, Pfecha_periodo_hasta date) OWNER TO sisap;

