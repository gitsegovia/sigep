<?php
class Cscp01CatalogoMantenimientoAlicotaController extends AppController{

   var $name = 'cscp01_catalogo_mantenimiento_alicota';
   var $uses = array("cscd01_catalogo", "ccfd04_cierre_mes", "cugd05_restriccion_clave");
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

	function checkSession(){
					if (!$this->Session->check('Usuario')){
							$this->redirect('/salir/');
							exit();
					}else{
						$this->requestAction('/usuarios/actualizar_user');
					}
	}//fin checksession


	function beforeFilter(){
						$this->checkSession();
	}


	function verifica_SS($i){
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


	function SQLCA($ano=null){
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


	function index($var1=null, $var2=null, $var3=null){

$this->verifica_entrada('99');

		$this->layout= "ajax";
	}//fin function


	function guardar($var1=null, $var2=null){
		  $this->layout = "ajax";
		  $desde = $this->Formato1($this->data['cscp01_catalogo_mantenimiento_alicota']['desde']);
		  $hasta = $this->Formato1($this->data['cscp01_catalogo_mantenimiento_alicota']['hasta']);

		 if(!empty($this->data['cscp01_catalogo_mantenimiento_alicota']['desde']) &&  !empty($this->data['cscp01_catalogo_mantenimiento_alicota']['hasta'])){
		      $sw   =  $this->cscd01_catalogo->execute("UPDATE cscd01_catalogo SET alicuota_iva='".$hasta."' WHERE alicuota_iva='".$desde."' ");
		      $this->set("Message_existe", "La alícuota fue actualizada");
		 }else{

		 	  $this->set("errorMessage", "La alícuota no fue actualizada");
		 }

	$this->index();
	$this->render("index");

	}//fin function

	function funcion($var1=null, $var2=null){
		  $this->layout = "ajax";
	}//fin function


function salir_clave(){
	$this->layout="ajax";
	$this->Session->delete('autor_valido');
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp01_catalogo_mantenimiento_alicota']['login']) && isset($this->data['cscp01_catalogo_mantenimiento_alicota']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp01_catalogo_mantenimiento_alicota']['login']);
		$paswd=addslashes($this->data['cscp01_catalogo_mantenimiento_alicota']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=99 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}// Entrar


}//fin clases
?>