<?php
class Cepd03RetencionesCuerpoIvaController extends AppController{
	var $name="Cepd03RetencionesCuerpoIva";
	var $uses = array('cstd01_sucursales_bancarias','cstd01_entidades_bancarias','usuario');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');
 	var $components = array('Autocomplete');

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
    $this->checkSession();

}//fin before filter
 function verifica_SS($i){
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
    	switch ($i){
    		case 1:return $this->Session->read('SScodpresi');break;
    		case 2:return $this->Session->read('SScodentidad');break;
    		case 3:return $this->Session->read('SScodtipoinst');break;
    		case 4:return $this->Session->read('SScodinst');break;
    		case 5:return $this->Session->read('SScoddep');break;
    		case 6:return $this->Session->read('entidad_federal');break;
    		default:
    		   return "NULO";


    	}//fin switch
    }//fin verifica_SS

 function index(){
 	$this->layout ="ajax";
 	$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
	$this->set('enable', 'disabled');
 	}

 	}
?>
