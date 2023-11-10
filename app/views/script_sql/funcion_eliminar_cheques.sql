-- Function: borrar_cheques(integer, integer, integer, integer, character varying, integer, integer)

-- DROP FUNCTION borrar_cheques(integer, integer, integer, integer, character varying, integer, integer);

CREATE OR REPLACE FUNCTION borrar_cheques(integer, integer, integer, integer, character varying, integer, integer)
  RETURNS void AS
$BODY$
        DELETE FROM cstd03_cheque_cuerpo WHERE cod_dep=$1 AND ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5 and numero_cheque=$6;
        DELETE FROM cstd03_cheque_partidas WHERE cod_dep=$1 AND ano_movimiento=$2  and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5 and numero_cheque=$6;
        DELETE FROM cstd03_cheque_poremitir WHERE cod_presi=$1 and ano_movimiento=$2  and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5 and numero_cheque=$6;
        DELETE FROM cstd03_cheque_ordenes WHERE  cod_dep=$1 and ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5 and numero_cheque=$6;
        DELETE FROM cstd06_comprobante_cuerpo_egreso WHERE cod_dep=$1 and ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5 and numero_cheque=$6;
        DELETE FROM cstd06_comprobante_poremitir_egreso WHERE cod_dep=$1 AND ano_comprobante_egreso=$2 and numero_comprobante_egreso=$7;
        UPDATE cstd03_cheque_numero SET situacion='2' WHERE cod_dep=$1 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5 and numero_cheque=$6;
        DELETE FROM cugd03_acta_anulacion_cuerpo where cod_dep=$1 AND tipo_operacion=251 AND ano_acta_anulacion=$2 and numero_documento=$6::character varying(30);
        DELETE FROM cstd04_movimientos_generales WHERE cod_dep=$1 AND ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5 and tipo_documento=4 and numero_documento=$6;
$BODY$
  LANGUAGE 'sql' VOLATILE
  COST 100;
ALTER FUNCTION borrar_cheques(integer, integer, integer, integer, character varying, integer, integer) OWNER TO sisap;





-- Function: borrar_cheques_cuenta(integer, integer, integer, integer, character varying)

-- DROP FUNCTION borrar_cheques_cuenta(integer, integer, integer, integer, character varying);

CREATE OR REPLACE FUNCTION borrar_cheques_cuenta(integer, integer, integer, integer, character varying)
  RETURNS void AS
$BODY$
        DELETE FROM cstd03_cheque_cuerpo                 WHERE cod_dep=$1 AND ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;
        DELETE FROM cstd03_cheque_partidas               WHERE cod_dep=$1 AND ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;
        DELETE FROM cstd03_cheque_poremitir              WHERE cod_dep=$1 and ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;
        DELETE FROM cstd03_cheque_ordenes                WHERE cod_dep=$1 and ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;
        DELETE FROM cstd06_comprobante_cuerpo_egreso     WHERE cod_dep=$1 and ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;

        DELETE FROM cstd06_comprobante_cuerpo_islr       WHERE cod_dep=$1 and ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;
        DELETE FROM cstd06_comprobante_cuerpo_iva        WHERE cod_dep=$1 and ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;
        DELETE FROM cstd06_comprobante_cuerpo_municipal  WHERE cod_dep=$1 and ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;
        DELETE FROM cstd06_comprobante_cuerpo_timbre     WHERE cod_dep=$1 and ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;

        UPDATE cstd03_cheque_numero SET situacion='2'    WHERE cod_dep=$1 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5;
        DELETE FROM cstd04_movimientos_generales         WHERE cod_dep=$1 AND ano_movimiento=$2 and cod_entidad_bancaria=$3 and cod_sucursal=$4 and cuenta_bancaria=$5 and tipo_documento=4;
$BODY$
  LANGUAGE 'sql' VOLATILE
  COST 100;
ALTER FUNCTION borrar_cheques_cuenta(integer, integer, integer, integer, character varying) OWNER TO sisap;




-- Function: borrar_cheques_dep(integer, integer)

-- DROP FUNCTION borrar_cheques_dep(integer, integer);

CREATE OR REPLACE FUNCTION borrar_cheques_dep(integer, integer)
  RETURNS void AS
$BODY$	DELETE FROM cstd03_cheque_cuerpo WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd03_cheque_partidas WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd03_cheque_poremitir WHERE cod_presi=$1;
        DELETE FROM cstd03_cheque_ordenes WHERE  cod_dep=$1;
        DELETE FROM cstd06_comprobante_cuerpo_egreso WHERE cod_dep=$1 AND ano_comprobante_egreso=$2;
        DELETE FROM cstd06_comprobante_cuerpo_islr WHERE cod_dep=$1 AND ano_comprobante_islr=$2;
        DELETE FROM cstd06_comprobante_cuerpo_iva WHERE cod_dep=$1 AND ano_comprobante_iva=$2;
        DELETE FROM cstd06_comprobante_cuerpo_municipal WHERE cod_dep=$1 AND ano_comprobante_municipal=$2;
        DELETE FROM cstd06_comprobante_cuerpo_timbre WHERE cod_dep=$1 AND ano_comprobante_timbre=$2;
        DELETE FROM cstd06_comprobante_poremitir_egreso WHERE cod_dep=$1 AND ano_comprobante_egreso=$2;
        DELETE FROM cstd06_comprobante_poremitir_islr WHERE cod_dep=$1 AND ano_comprobante_islr=$2;
        DELETE FROM cstd06_comprobante_poremitir_iva WHERE cod_dep=$1 AND ano_comprobante_iva=$2;
        DELETE FROM cstd06_comprobante_poremitir_municipal WHERE cod_dep=$1 AND ano_comprobante_municipal=$2;
        DELETE FROM cstd06_comprobante_poremitir_timbre WHERE cod_dep=$1 AND ano_comprobante_timbre=$2;

        DELETE FROM cstd07_retenciones_partidas_islr WHERE cod_dep=$1 AND ano=$2;
        DELETE FROM cstd07_retenciones_partidas_iva WHERE cod_dep=$1 AND ano=$2;
        DELETE FROM cstd07_retenciones_partidas_municipal WHERE cod_dep=$1 AND ano=$2;
        DELETE FROM cstd07_retenciones_partidas_timbre WHERE cod_dep=$1 AND ano=$2;

        DELETE FROM cstd07_retenciones_cuerpo_islr WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd07_retenciones_cuerpo_iva WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE cod_dep=$1 AND ano_movimiento=$2;

        DELETE FROM cstd09_notadebito_especial_cuerpo WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd09_notadebito_especial_partidas WHERE cod_dep=$1 AND ano_movimiento=$2;

        DELETE FROM cstd30_debito_cuerpo WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd30_debito_partidas WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd30_debito_ordenes WHERE cod_dep=$1 AND ano_movimiento=$2;

        DELETE FROM cstd04_movimientos_generales WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd03_movimientos_manuales WHERE cod_dep=$1 AND ano_movimiento=$2;
        DELETE FROM cstd04_cheque_poremitir WHERE cod_dep=$1 AND ano_movimiento=$2;
        UPDATE cstd02_cuentas_bancarias SET deposito_dia=0, deposito_mes=0, deposito_ano=0, nota_credito_dia=0, nota_credito_mes=0, nota_credito_ano=0, nota_debito_dia=0, nota_debito_mes=0, nota_debito_ano=0, cheque_dia=0, cheque_mes=0,  cheque_ano=0,  monto_cheque_por_emitir=0,  monto_cheque_custodia=0,  monto_cheque_transito=0, monto_cheque_pagado=0, saldo_dia_anterior=0, saldo_mes_anterior=0, disponibilidad_libro=0, disponibilidad_real=0 WHERE cod_dep=$1;
        UPDATE cstd03_cheque_numero SET situacion='1' WHERE cod_dep=$1;
        delete from cugd03_acta_anulacion_cuerpo where cod_dep=$1 AND tipo_operacion=251 AND ano_acta_anulacion=$2;
$BODY$
  LANGUAGE 'sql' VOLATILE
  COST 100;
ALTER FUNCTION borrar_cheques_dep(integer, integer) OWNER TO sisap;
COMMENT ON FUNCTION borrar_cheques_dep(integer, integer) IS 'Borra todos los datos de las tablas de cheques para la dependencia indicada y el año indicado (dep,ano)';
