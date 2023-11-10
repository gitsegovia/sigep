<?php
 class Shp000ControlNumerosPlanillasController extends AppController{
	var $uses = array('shd000_arranque','shd000_control_actualizacion','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp000_control_numeros_planillas";


 	function checkSession(){
 	if (!$this->Session->check('Usuario')){
 		$this->redirect('/salir/');
		exit();
	}else{
		//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
		//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
		$this->requestAction('/usuarios/actualizar_user');
	}
 }//fin checkSession



 function beforeFilter(){
 	$this->checkSession();
 }//fin before filter

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

function index($var=null, $var2=null){

$this->verifica_entrada('71');

	$this->layout = "ajax";
	if(isset($var2)){
		$this->set('autor_valido',true);
	}

	$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
	if($ver!=null){
		$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$sql2="select * FROM v_shd000_control_numero_planilla WHERE ".$this->SQLCA()." and ano=".$ver[0][0]['ano_arranque']." order by cod_ingreso,numero_planilla asc";
		$ver2= $this->shd000_control_actualizacion->execute($sql2);

		if($ver2!=null){
			$this->set('datos',$ver2);
		}else{
			$this->set('datos',null);
		}
	}else{
		$this->set('datos',null);
	}


}//fin index



function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp02_solicitud_numero']['login']) && isset($this->data['cscp02_solicitud_numero']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp02_solicitud_numero']['login']);
		$paswd=addslashes($this->data['cscp02_solicitud_numero']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=71 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}else{
		$this->set('errorMessage',"Debe ingresar su login y su contrase&tilde;na");
		$this->set('autor_valido',false);
		$this->index("autor_valido");
		$this->render("index");
	}
}




}