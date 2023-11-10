<?php

class Shp100SolicitudLicenciaController extends AppController{

    var $name    = "shp100_solicitud_licencia";
    var $uses    = array('shd001_registro_contrby', 'shd100_solicitud', 'shd100_solicitud_activ', 'ccfd04_cierre_mes');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');







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
 }


 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));




}//fin function










}//fin class
?>