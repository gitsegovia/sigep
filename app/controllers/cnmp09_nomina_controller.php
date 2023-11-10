<?php
 class Cnmp09NominaController extends AppController {
   var $name = 'cnmp09_nomina';
	var $uses = array('cnmd06_datos_personales');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession

 function index(){
	$this->layout="ajax";
 }//index
}
?>