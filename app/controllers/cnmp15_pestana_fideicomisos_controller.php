<?php
 class cnmp15PestanaFideicomisosController extends AppController {

	var $name = 'cnmp15_pestana_fideicomisos';
	var $uses = array('cnmd15_bono_vaca', 'cnmd15_semana_salarial', 'cnmd15_aguinaldo', 'cnmd06_datos_personales');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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