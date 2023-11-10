<?php



 class ccnp01PlanInversionController extends AppController {
    var $name    = 'ccnp01_plan_inversion';
	var $uses    = array('ccnd01_tipo_directivo');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');





function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
				}
}//fin checksession





 function beforeFilter(){
 	$this->checkSession();

 }



function index(){
	$this->layout="ajax";


}//index




}//fin function

?>