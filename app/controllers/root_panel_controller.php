<?php

class RootPanelController extends AppController{


    var $name    = "root_panel";
    var $uses    = array();
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    var $layout  = "root";




function checkSession(){

				if (!$this->Session->check('Root_session')){
						$this->redirect('/root/salir/');
						exit();
				}else{
					if($this->Session->read('Root_session')!="VISION_INTEGRAL"){
						$this->redirect('/root/salir/');
						 exit();
					}
				}
}//fin checksession





function beforeFilter(){$this->checkSession();}





function index(){}







function vacio(){
	$this->layout="ajax";
		 echo"<script>menu_activo();</script>";
}//fin vacio







}//fin class

?>