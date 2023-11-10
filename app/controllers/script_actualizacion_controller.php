<?php

 class ScriptActualizacionController extends AppController {
   var $name='script_actualizacion';
   var $uses=array('ccfd01_tipo','ccfd01_cuenta','ccfd01_subcuenta','usuario','v_ccfd01');
   var $helpers=array('Html','Ajax','Javascript', 'Sisap');

   function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }

 function beforeFilter(){
 	$this->checkSession();
 }




function actualizar_cheque_cantaura(){

  $this->layout="ajax";

/*

				ALTER TABLE cstd03_cheque_cuerpo DROP CONSTRAINT cstd03_cheque_cuerpo_1;
				ALTER TABLE cstd03_cheque_ordenes DROP CONSTRAINT cstd03_cheque_ordenes_1;
				ALTER TABLE cstd03_cheque_partidas DROP CONSTRAINT cstd03_cheque_partidas_1;
				ALTER TABLE cstd03_cheque_poremitir DROP CONSTRAINT cstd03_cheque_poremitir_1;


				      UPDATE cstd03_cheque_cuerpo                SET  numero_cheque='57000011'      WHERE ano_movimiento=2008 and numero_cheque='51';
					  UPDATE cstd03_cheque_numero                SET  numero_cheque='57000011'      WHERE                         numero_cheque='51';
					  UPDATE cstd03_cheque_ordenes               SET  numero_cheque='57000011'      WHERE ano_movimiento=2008 and numero_cheque='51';
					  UPDATE cstd03_cheque_partidas              SET  numero_cheque='57000011'      WHERE ano_movimiento=2008 and numero_cheque='51';
					  UPDATE cstd03_cheque_poremitir             SET  numero_cheque='57000011'      WHERE ano_movimiento=2008 and numero_cheque='51';
					  UPDATE cepd03_ordenpago_cuerpo             SET  numero_cheque='57000011'      WHERE ano_movimiento=2008 and numero_cheque='51';
					  UPDATE cstd04_movimientos_generales        SET  numero_documento='57000011'   WHERE ano_movimiento=2008 and numero_documento='51' and tipo_documento='4';
					  UPDATE ccfd10_descripcion                  SET  numero_documento='57000011'   WHERE ano_asiento=2008    and numero_documento='51' and tipo_documento='1';



				ALTER TABLE cstd03_cheque_cuerpo
				  ADD CONSTRAINT cstd03_cheque_cuerpo_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria)
				      REFERENCES cstd02_cuentas_bancarias (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria) MATCH SIMPLE
				      ON UPDATE NO ACTION ON DELETE CASCADE;

				ALTER TABLE cstd03_cheque_ordenes
				  ADD CONSTRAINT cstd03_cheque_ordenes_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)
				      REFERENCES cstd03_cheque_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque) MATCH SIMPLE
				      ON UPDATE NO ACTION ON DELETE CASCADE;

				ALTER TABLE cstd03_cheque_partidas
				  ADD CONSTRAINT cstd03_cheque_partidas_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)
				      REFERENCES cstd03_cheque_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque) MATCH SIMPLE
				      ON UPDATE NO ACTION ON DELETE CASCADE;

				ALTER TABLE cstd03_cheque_poremitir
				  ADD CONSTRAINT cstd03_cheque_poremitir_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)
				      REFERENCES cstd03_cheque_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque) MATCH SIMPLE
				      ON UPDATE NO ACTION ON DELETE CASCADE;

*/


}





}
?>
