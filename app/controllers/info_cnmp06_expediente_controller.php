<?php
 class InfoCnmp06ExpedienteController extends AppController {
   var $name = 'info_cnmp06_expediente';
	var $uses = array('cnmd06_datos_personales');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'infogob');



function checkSession(){
				if (!$this->Session->check('infogobierno')){
						$this->redirect('/infogobierno/salir_todo');
						exit();
				}else{
					$c1=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'J');
					$c2=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'G');
					if($c1!=0 || $c2!=0){
						echo "<script type=\"text/javascript\" language=\"javascript\">error('Por favor registrese cómo persona natural para acceder a la información');</script>";
                        exit();
					}
				}
}//fin checksession



 function beforeFilter(){
 	$this->checkSession();
 }



 function index(){
	$this->layout="ajax";

      $this->Session->delete('cedula_pestana_expediente');
      $this->Session->delete('cod_dep_expediente');
      $this->Session->delete('cod_tipo_nomina_expediente');
      $this->Session->delete('cod_cargo_expediente');
      $this->Session->delete('cod_ficha_expediente');
      $this->Session->delete('pag_num_expediente');


 }//index
}
?>