<?php

class RootController extends AppController{


    var $name    = "root";
    var $uses    = array('cugd04');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    var $layout  = "root";







function index(){

 $this->layout="script_correciones";
 $this->Session->delete('sesion_cerrada');

}//fin functio







function entrar($var=null) {

   if(isset($_SESSION["sesion_cerrada"])){$sesion_cerrada = $_SESSION["sesion_cerrada"]; }else{ $sesion_cerrada = true;}

    if(isset($this->params['form']['login']) && isset($this->params['form']['password']) && $sesion_cerrada==true){
    	$u=strtoupper($this->params['form']['login']);
    	$p=strtoupper($this->params['form']['password']);
        if(defined('CLAVE_SISAP3')){
              if(CLAVE_SISAP3==md5($p) && $u=='PROYECTO'){

              	    $_SESSION["sesion_cerrada"] = false;
              	    $this->Session->write('Root_session', "VISION_INTEGRAL");

              	    $this->layout="root";

	                $this->set('ESTOY_LOGUEADO', true);

	                $this->redirect('/root_panel/');


              }else{

              	  $this->layout="script_correciones";

              	  $this->set('ESTOY_LOGUEADO', false);

              }
        }
    }//fin


    $this->render("entrar");

}//entrar









function salir(){

$this->layout="ajax";

 $this->Session->delete('Root_session');
 $this->Session->delete('sesion_cerrada');

}//fin function





function salida_panel(){

$this->layout="script_correciones";


}//fin function










}//fin class

?>