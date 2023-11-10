<?php

 class Cugp10VideosController extends AppController{


 	var $uses = array('ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
	if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
	}else{
		//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
		//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
		//$this->requestAction('/usuarios/actualizar_user');
	}
}//fin checksession


function beforeFilter(){
	$this->checkSession();
}

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
function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA


function index ($url=null,$ancho=425,$alto=344,$iframe=null) {
    $this->layout="ajax";
    if(isset($url)){
       $this->set("URL",$url);
       $this->set("ANCHO",$ancho);
       $this->set("ALTO",$alto);
    }
    if(isset($iframe)){
    	$this->set("IFRAME",$iframe);
    }
}//fin index


function ver_video_ayuda ($url=null) {
    $this->layout="ajax";
    $this->redirect('/videos/'.$url);
    //if(isset($url)){
      // $this->set("URL",$url);
    //}
}//fin index

}//fin class
?>
