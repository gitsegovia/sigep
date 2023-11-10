<?php


 class ReemplazarRifController extends AppController{
	  var $name = 'reemplazar_rif';
     var $uses = array('cepd03_ordenpago_cuerpo');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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






function index($var1=null){

	$this->layout = "ajax";


	if($var1==null){ $var1 = 1;

     }else{
                if(!empty($this->data['campo']['rif_a']) && !empty($this->data['campo']['rif_b'])  && !empty($this->data['campo']['rif_c'])){
                        $campo_a  =  strtoupper($this->data['campo']['rif_b']);
                        $campo_b  =  strtoupper($this->data['campo']['rif_a']);
                        $campo_c  =  strtoupper($this->data['campo']['rif_c']);
                             if($campo_c==1){
                        $a=$this->cepd03_ordenpago_cuerpo->execute(" select reemplazar_rif ('".$campo_a."', '".$campo_b."', 1);  ");
                        if($a>1){$this->set('Message_existe', "El rif fue cambiado con exito");}else{$this->set('errorMessage', "No pudo ser cambiado");}
                       }else if($campo_c==2){
                       	$a=$this->cepd03_ordenpago_cuerpo->execute(" select reemplazar_rif ('".$campo_a."', '".$campo_b."', 2);  ");
                         if($a>1){$this->set('Message_existe', "El cedula fue cambiado con exito");}else{$this->set('errorMessage', "No pudo ser cambiado");}
                       }//fin if
                }else{
                         $this->set('mensaje', "Faltan datos");
                }



     }//fin function



	$this->set('var1', $var1);





}//fin fucntion


















}//fin clase cfpp09Controller