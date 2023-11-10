<?php
 class cnmp15PestanaVacacionesController extends AppController {

	var $name = 'cnmp15_pestana_vacaciones';
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




}//fin class
?>