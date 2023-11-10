<?php

 class ExcelController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','v_f6_gr_bcv','v_f3_gr_bcv');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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
	if($this->ano_ejecucion()!=""){
		return;
	}else{
		echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
		exit();
	}
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

    function SQLCA_no_dep($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  ";

         return $sql_re;
    }//fin funcion SQLCA


function index () {
    $this->layout="ajax";
    $this->set('ano',$this->ano_ejecucion());

}//fin index

function f3_gr ($ano,$parte) {
	$this->layout="ajax";
	/**/$vector=$this->v_f3_gr_bcv->findAll($this->SQLCA_no_dep()." and ano=".$ano."");
	$this->set('vector',$vector);
	$this->set('parte',$parte);/**/
}

function f6_gr ($ano) {
	$this->layout="ajax";

	$vector401=$this->v_f6_gr_bcv->findAll($this->SQLCA_no_dep()." and ano=".$ano." and cod_partida=401");
	$this->set('vector401',$vector401);
	$vector402=$this->v_f6_gr_bcv->findAll($this->SQLCA_no_dep()." and ano=".$ano." and cod_partida=402");
	$this->set('vector402',$vector402);
	$vector403=$this->v_f6_gr_bcv->findAll($this->SQLCA_no_dep()." and ano=".$ano." and cod_partida=403");
	$this->set('vector403',$vector403);
	$vector404=$this->v_f6_gr_bcv->findAll($this->SQLCA_no_dep()." and ano=".$ano." and cod_partida=404");
	$this->set('vector404',$vector404);
}

}//fin class
?>
