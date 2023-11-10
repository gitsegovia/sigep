<?php

 class Cugp04UsuariosPartidasAtrapadasController extends AppController {
   var $name = 'cugp04_usuarios_partidas_atrapadas';
   var $uses = array('cugd04_entrada_modulo','arrd05','cugd04');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession()
    {
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }

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

  function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}//fin zero



function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}//fin concatena

 function index(){
 	$this->layout ="ajax";
 	$dep=$this->verifica_SS(5);
 	if($dep!=1){
 		$datos=$this->cugd04->findAll($this->SQLCA(),null,'cod_dep ASC');
 	}else{
 		$datos=$this->cugd04->findAll($this->condicionNDEP(),null,'cod_dep ASC');
 	}
 	if(!$datos){
 		echo "";
 	}else{
 		$this->set('datos',$datos);
 	}
 	$deno_arrd05=$this->arrd05->findAll($this->condicionNDEP(),null,null);
 	$this->set('deno_arrd05',$deno_arrd05);

 }///FIN INDEX



 function eliminar_items($cod1=null,$cod2){
	$this->layout="ajax";
	$cond="cod_dep=".$cod1." and username='".$cod2."'";
	$this->cugd04->execute("delete from cugd04 where ".$cond);
	$this->set('mensaje', 'Registro eliminado');
}// eliminar_items


function actualizar(){
$this->layout ="ajax";
	$dep=$this->verifica_SS(5);
 	if($dep!=1){
 		$datos=$this->cugd04->findAll($this->SQLCA(),null,'cod_dep ASC');
 	}else{
 		$datos=$this->cugd04->findAll($this->condicionNDEP(),null,'cod_dep ASC');
 	}
 	if(!$datos){
 		echo "";
 	}else{
 		//echo "si consulto";
 		$this->set('datos',$datos);
 	}
 	$deno_arrd05=$this->arrd05->findAll($this->condicionNDEP(),null,null);
 	$this->set('deno_arrd05',$deno_arrd05);
}//fin actualizar

}//fin de la clase controller
?>
