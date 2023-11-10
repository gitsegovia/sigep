<?php
 class Shp950MontoSolvenciaController extends AppController{
	var $uses = array('shd950_solvencia_monto');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp950_monto_solvencia";


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

function index($var=null, $var_cont=null){
	$this->layout = "ajax";

	$ver=$this->shd950_solvencia_monto->execute("select * from shd950_solvencia_monto where ".$this->SQLCA());
	if($ver!=null){
		$this->set('monto',$this->Formato2($ver[0][0]['monto_solvencia']));
		$this->set('readonly','readonly');
	}else{
		$this->set('monto','');
		$this->set('readonly','');
	}


}//fin index



function guardar(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	if(!empty($this->data['shp950']['monto']) && $this->data['shp950']['monto']!=0){
		$monto= $this->Formato1($this->data['shp950']['monto']);
		if($this->shd950_solvencia_monto->FindCount($this->SQLCA())==0){
			$insert="INSERT INTO shd950_solvencia_monto (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,monto_solvencia) VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$monto')";
			$sw = $this->shd950_solvencia_monto->execute($insert);
			$mensaje='EL DATO NO PUDO SER REGISTRADO';
			$MENSAJE1='EL DATO FUE REGISTRADO';
		}else{
			$insert="UPDATE shd950_solvencia_monto SET monto_solvencia='$monto' WHERE ".$this->SQLCA();
			$sw = $this->shd950_solvencia_monto->execute($insert);
			$MENSAJE1='EL DATO FUE ACTUALIZADO';
			$mensaje='EL DATO NO PUDO SER MODIFICADO';
		}
		if($sw>0){
			$this->set('Message_existe', $MENSAJE1);
		}else{
			$this->set('errorMessage',$mensaje);
		}
	}else{
		$this->set('errorMessage', 'DEBE INGRESAR EL MONTO SOLVENCIA');
	}


	$this->index();
	$this->render("index");
}


function modificar(){
	$this->layout = "ajax";

	$this->set('Message_existe', 'PROCEDA A MODIFICAR EL DATO');

}




}