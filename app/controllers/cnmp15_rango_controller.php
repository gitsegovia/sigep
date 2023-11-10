<?php
class Cnmp15RangoController extends AppController{


	//var $uses = array('cnmd03_transacciones','cnmd09_asignacion_calcula_asignacion','Cnmd01','ccfd03_instalacion','v_cnmd09_asignacion_calcula_asignacion_2','cnmd09_asignacion_calcula_asignacion_2');
 	var $uses = array('cnmd15_rango','Cnmd01');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
	//var $layout =  "administradors";
function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir/');
						exit();
        }
    }//checkSession



	function beforeFilter(){

		$this->checkSession();

}//beforeFilter






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

		$this->set($nomVar, $cod);

	}
}//fin concatena





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



function index(){
	$this->layout = "ajax";

	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');
	$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
	$lista1 = $this->Cnmd01->FindAll($this->SQLCA());
	$this->set('datos',$datos);
	$this->set('lista1',$lista1);
}//FIN INDEX


function cod_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_nomina', $cod_nomina);
	}
	echo "<script>";
		echo "document.getElementById('transferencia').innerHTML='';";
	echo "</script>";
}

function deno_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		//echo "el tipo de nomina es: ".$deno_nomina;
		$this->set('deno_nomina', $deno_nomina);
	}
}


function guardar(){
	$this->layout="ajax";
	 $cond= $this->SQLCA();

	//$this->data["cnmp15"]["cod_nomina"];
	//echo "<br>".$this->data["cnmp15"]["fecha_desde"];
	//echo "<br>".$this->data["cnmp15"]["fecha_hasta"];
	if(!empty($this->data["cnmp15"]["cod_nomina"]) && !empty($this->data["cnmp15"]["fecha_desde"]) and !empty($this->data["cnmp15"]["fecha_hasta"])){
		if(!$this->cnmd15_rango->FindAll($this->SQLCA()." and cod_tipo_nomina=".$this->data["cnmp15"]["cod_nomina"])){
			$sql_insert = "INSERT INTO cnmd15_rango VALUES(".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$this->verifica_SS(5).",".$this->data['cnmp15']['cod_nomina'].",'".$this->data['cnmp15']['fecha_desde']."','".$this->data['cnmp15']['fecha_hasta']."')";
			$sw1 = $this->cnmd15_rango->execute($sql_insert);
			if($sw1>1){
				$this->set('Message_existe', 'Registro exitoso');
				$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
				$lista = $this->Cnmd01->FindAll($this->SQLCA());
				$this->set('datos',$datos);
				$this->set('lista',$lista);
			}else{
				$this->set('errorMessage','No se pudo registrar,verifique e intente nuevamente');
				$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
				$lista = $this->Cnmd01->FindAll($this->SQLCA());
				$this->set('datos',$datos);
				$this->set('lista',$lista);
			}
		}else{
			$this->set('errorMessage','este codigo de nomina ya existe registrado');
			$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
			$lista = $this->Cnmd01->FindAll($this->SQLCA());
			$this->set('datos',$datos);
			$this->set('lista',$lista);
		}
	}else{
		$this->set('errorMessage','Debe completar todos los campos para procesar esta operacion');
		$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
		$lista = $this->Cnmd01->FindAll($this->SQLCA());
		$this->set('datos',$datos);
		$this->set('lista',$lista);
	}


	echo "<script>";
		 echo "document.getElementById('save').disabled=false;";
		 echo "document.getElementById('cod_nomina').value='';";
		 echo "document.getElementById('deno_nomina').value='';";
		 echo "document.getElementById('fecha1').value='';";
		 echo "document.getElementById('fecha2').value='';";
	echo "</script>";

}//fin guardar



function eliminar($var=null){
	$this->layout="ajax";
	$sw=$this->cnmd15_rango->execute("delete from cnmd15_rango where ".$this->SQLCA()." and cod_tipo_nomina=".$var);
	if($sw>1){
		$this->set('Message_existe', 'Se elimino exitosamente');
	}else{
		$this->set('errorMessage','No se pudo eliminar');
	}
}//fin eliminar



function modificar($nomi=null,$i=null){
	$this->layout="ajax";
	$this->set('nomina',$nomi);
	//$this->set('desde',$desde);
	//$this->set('hasta',$hasta);
	//$this->set('deno',$deno);
	$this->set('k',$i);
	$deno2=$this->cnmd15_rango->execute("select denominacion from cnmd01 where ".$this->SQLCA()." and cod_tipo_nomina=".$nomi);
	$this->set('deno2',$deno2);
	$deno3=$this->cnmd15_rango->execute("select fecha_desde,fecha_hasta from cnmd15_rango where ".$this->SQLCA()." and cod_tipo_nomina=".$nomi);
	$this->set('deno3',$deno3);
	//print_r($deno2);
}// fin mostrar


function guardar_modificar($var=null,$i=null){
	$this->layout="ajax";
	$sw=$this->cnmd15_rango->execute("update cnmd15_rango set fecha_desde='".$this->data["cnmp15_rango"]["fecha_desde".$i]."',fecha_hasta='".$this->data["cnmp15_rango"]["fecha_hasta".$i]."' where ".$this->SQLCA()." and cod_tipo_nomina=".$var);
	if($sw>1){
		$this->set('mensaje','se ha modificado exitosamente');
		$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
		$lista = $this->Cnmd01->FindAll($this->SQLCA());
		$this->set('datos',$datos);
		$this->set('lista1',$lista);
	}else{
		$this->set('mensajeError','No se pudo modificar,intente nuevamente');
		$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
		$lista = $this->Cnmd01->FindAll($this->SQLCA());
		$this->set('datos',$datos);
		$this->set('lista1',$lista);
	}


}//fin guardar_modificar



function cancelar(){
	$this->layout="ajax";

	$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
	$lista = $this->Cnmd01->FindAll($this->SQLCA());
	$this->set('datos',$datos);
	$this->set('lista1',$lista);


}//fin cancelar



}//fin controller
?>