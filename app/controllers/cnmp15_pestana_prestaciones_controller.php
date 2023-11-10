<?php
 class cnmp15PestanaPrestacionesController extends AppController {
   var $name = 'cnmp15_pestana_prestaciones';
	var $uses = array('cugd05_restriccion_clave','cnmd06_datos_personales');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession


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


 function index($var=false,$var1=null,V$var2=null){

$this->verifica_entrada('108');

	  $this->layout="ajax";
      $this->Session->delete('cedula_pestana_prestaciones');
      $this->Session->delete('cod_dep_prestaciones');
      $this->Session->delete('cod_tipo_nomina_prestaciones');
      $this->Session->delete('cod_cargo_prestaciones');
      $this->Session->delete('cod_ficha_prestaciones');
      $this->Session->delete('pag_num_prestaciones');
      $this->Session->delete("autor_valido");
	  $this->set('autor_valido',$var);
	  if($var1!=null)$this->set($var1,$var2);

 }//index

function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cmp15_index']['login']) && isset($this->data['cmp15_index']['password'])){
		$l="PROYECTO";
		$c="JODAVA";
		$user=addslashes($this->data['cmp15_index']['login']);
		$paswd=addslashes($this->data['cmp15_index']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=108 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index(true);
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index(true);
			$this->render("index");
		}else{
			$this->set('autor_valido',false);
			$this->index(false,'errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->render("index");
		}
	}
}



}//fin class
?>