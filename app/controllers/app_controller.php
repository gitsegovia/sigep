<?php
class AppController extends Controller
{
	function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
	}//fin checksession

	function beforeFilter(){

	}


	function DebugSystem($varDebug, $exit=false, $cod_dep=null, $usuario=null){
var_dump($varDebug);/*
		if(($this->Session->read('SScoddep')==$cod_dep || $cod_dep==null) && ($this->Session->read('nom_usuario')==$usuario || $usuario==null)){
			var_dump($varDebug);
			if($exit){
				exit();
			}
		}*/

	}


}
?>
