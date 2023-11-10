<?php
class Cnmp16BonoVacacionalController extends AppController{

	var $name = 'cnmp16_bono_vacacional';
 	var $uses = array('cnmd15_bono_vaca_vacaciones','Cnmd01');
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
	//$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
	$lista1 = $this->Cnmd01->FindAll($this->SQLCA());
	//$this->set('datos',$datos);
	$this->set('lista1',$lista1);
}//FIN INDEX


function cod_nomina($cod_nomina=null){
	$this->layout="ajax";
	$this->Session->delete('nomina');
	$this->Session->write('nomina',$cod_nomina);
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


function escala_inputs($var=null){
	$this->layout="ajax";
	$v=$this->cnmd15_bono_vaca_vacaciones->execute("SELECT * FROM cnmd15_bono_vaca_vacaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$var." ORDER BY escala DESC");
			if($v!=null){
				$escala=$v[0][0]["escala"];
				$escala = $escala =="" ? 1 : $escala+1;
				//$x=$this->cnmd15_bono_vaca_vacaciones->execute("SELECT * FROM cnmd15_bono_vaca_vacaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$var." ORDER BY desde_antiguedad DESC");
				$desde=$v[0][0]["hasta_antiguedad"];
				$desde = $desde =="" ? 1 : $desde+1;
			}else{
				$escala=1;
				$desde=1;
			}
			$this->set('escala',$escala);
			$this->set('desde',$desde);

}//fin escenarios_inputs



function grilla($var=null){
	$this->layout="ajax";
	$datos=$this->cnmd15_bono_vaca_vacaciones->FindAll($this->SQLCA()." and cod_tipo_nomina=".$var,null,' ORDER BY escala ASC');

	if($datos){
		$this->set('datos',$datos);
	}else{
		$this->set('datos',"");
	}

}//fin grilla




function guardar(){
	$this->layout="ajax";

	$escala=$this->data["cnmp15_bono"]["escala"];
	$dia1=$this->data["cnmp15_bono"]["dia1"];
	$mes1=$this->data["cnmp15_bono"]["mes1"];
	$ano1=$this->data["cnmp15_bono"]["ano1"];
	$dia2=$this->data["cnmp15_bono"]["dia2"];
	$mes2=$this->data["cnmp15_bono"]["mes2"];
	$ano2=$this->data["cnmp15_bono"]["ano2"];
	$desde=$this->data["cnmp15_bono"]["desde"];
	$hasta=$this->data["cnmp15_bono"]["hasta"];
	$dias = $this->Formato1($this->data["cnmp15_bono"]["dias"]);
	$basico=$this->data["cnmp15_bono"]["basico"];
	$descuento=$this->data["cnmp15_bono"]["descuento"];

	if($escala!="" && $dia1!="" && $mes1!="" && $ano1!="" && $dia2!="" && $mes2!="" && $ano2!="" && $desde!="" && $hasta!="" && $dias!=""){
			$sql_insert = "INSERT INTO cnmd15_bono_vaca_vacaciones VALUES(".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$this->verifica_SS(5).",".$this->Session->read('nomina').",".$escala.",".$dia1.",".$mes1.",".$ano1.",".$dia2.",".$mes2.",".$ano2.",".$desde.",".$hasta.",".$dias.",".$basico.",".$descuento.")";
			$sw1 = $this->cnmd15_bono_vaca_vacaciones->execute($sql_insert);
			if($sw1>1){
				$this->set('Message_existe','registro exitoso');
				$escala=$escala+1;
				$desde=$hasta+1;
				echo "<script>";
					echo "document.getElementById('escala').value=".$escala.";";
					//echo "document.getElementById('dia1').value='';";
					//echo "document.getElementById('mes1').value='';";
					//echo "document.getElementById('ano1').value='';";
					//echo "document.getElementById('dia2').value='';";
					//echo "document.getElementById('mes2').value='';";
					//echo "document.getElementById('ano2').value='';";
					echo "document.getElementById('desde').value=".$desde.";";
					echo "document.getElementById('hasta').value='';";
					echo "document.getElementById('dias').value='';";
				echo "</script>";
			}else{
				$this->set('errorMessage','no');
			}
	}else{
		$this->set('errorMessage','Debe ingresar datos en  todos los campos');
	}

	$datos=$this->cnmd15_bono_vaca_vacaciones->FindAll($this->SQLCA()." and cod_tipo_nomina=".$this->Session->read('nomina'),null,' ORDER BY escala ASC');

	if($datos){
		$this->set('datos',$datos);
	}else{
		$this->set('datos',"");
	}


}//fin guardar



function eliminar(){
	$this->layout="ajax";
	//echo $nomina=$this->data["cnmp15_bono"]["cod_nomina"];
	$nomina=$this->Session->read('nomina');
	if($nomina!=""){
		if($this->cnmd15_bono_vaca_vacaciones->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina)){
			$sw1=$this->cnmd15_bono_vaca_vacaciones->execute("delete from cnmd15_bono_vaca_vacaciones where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina);
			if($sw1>1){
				$this->set('Message_existe','se elimino exitosamente');

			}else{
				$this->set('errorMessage','no se pudo eliminar');
			}
		}else{
			$this->set('errorMessage','El codigo que intenta eliminar no existe registrado!');
		}
	}else{
		$this->set('errorMessage','debe seleccionar el codigo a eliminar');
	}
	echo "<script>";
		echo "document.getElementById('cod_nomina').value='';";
		echo "document.getElementById('deno_nomina').value='';";
		echo "document.getElementById('muestra_grilla').innerHTML='';";
		echo "document.getElementById('cargar_grilla').innerHTML='';";
	echo "</script>";


}//fin eliminar



function modificar($nomina=null,$escala=null,$desde_dia=null,$desde_mes=null,$desde_ano=null,$hasta_dia=null,$hasta_mes=null,$hasta_ano=null,$desde_antiguedad=null,$hasta_antiguedad=null,$dias=null,$i=null,$basicos=null,$descuentos=null){
	$this->layout="ajax";

	// $sq_datos_campos = $this->cnmd15_bono_vaca_vacaciones->execute("SELECT escala, hasta_dia, hasta_mes, hasta_ano FROM cnmd15_bono_vaca_vacaciones WHERE ".$this->SQLCA()." AND cod_tipo_nomina=".$nomina." ORDER BY escala DESC LIMIT 1;");

	$sq_datos_campos = $this->cnmd15_bono_vaca_vacaciones->execute("SELECT escala, hasta_dia, hasta_mes, hasta_ano FROM cnmd15_bono_vaca_vacaciones WHERE ".$this->SQLCA()." AND cod_tipo_nomina=".$nomina." AND escala = (SELECT (MAX(escala)-1) FROM cnmd15_bono_vaca_vacaciones) LIMIT 1;");

	//if($sq_datos_campos != null){
		$da_esca = $sq_datos_campos[0][0]['escala'];
		$fecha_esc_anterior = (int) $sq_datos_campos[0][0]['hasta_ano'].$sq_datos_campos[0][0]['hasta_mes'].$sq_datos_campos[0][0]['hasta_dia'];

		$this->set('num_escala_ant',$da_esca);
	// }

	/*else{
		$da_esca = 0;
		$fecha_esc_anterior = 0; // (int) $hasta_ano.$hasta_mes.$hasta_dia;
	}*/

	$this->set('nomina',$nomina);
	$this->set('escala',$escala);
	$this->set('desde_dia',$desde_dia);
	$this->set('desde_mes',$desde_mes);
	$this->set('desde_ano',$desde_ano);
	$this->set('hasta_dia',$hasta_dia);
	$this->set('hasta_mes',$hasta_mes);
	$this->set('hasta_ano',$hasta_ano);
	$this->set('desde_antiguedad',$desde_antiguedad);
	$this->set('hasta_antiguedad',$hasta_antiguedad);
	$this->set('dias',$dias);
	$this->set('k',$i);
	$this->set('basicos',$basicos);
	$this->set('descuentos',$descuentos);
	$this->set('fecha_esc_anteri',$fecha_esc_anterior);

}//fin modificar




function guardar_modificar($nomina=null,$escala=null,$desde_dia=null,$desde_mes=null,$desde_ano=null,$hasta_dia=null,$hasta_mes=null,$hasta_ano=null,$desde_antiguedad=null,$hasta_antiguedad=null,$diass=null,$i=null,$basicomo=null,$descuentomo=null){
	$this->layout="ajax";

	$escala=$this->data["cnmp15_bono"]["escalas"];
	$dia1=$this->data["cnmp15_bono"]["dias1"];
	$mes1=$this->data["cnmp15_bono"]["mess1"];
	$ano1=$this->data["cnmp15_bono"]["anos1"];
	$dia2=$this->data["cnmp15_bono"]["dias2"];
	$mes2=$this->data["cnmp15_bono"]["mess2"];
	$ano2=$this->data["cnmp15_bono"]["anos2"];
	$desde=$this->data["cnmp15_bono"]["desdes"];
	$hasta=$this->data["cnmp15_bono"]["hastas"];
	$dias = $this->Formato1($this->data["cnmp15_bono"]["diass"]);
	$basicoa=$this->data["cnmp15_bono"]["basicos"];
	$descuentoa=$this->data["cnmp15_bono"]["descuentos"];

	if($escala!="" && $dia1!="" && $mes1!="" && $ano1!="" && $dia2!="" && $mes2!="" && $ano2!="" && $desde!="" && $hasta!="" && $dias!=""){

		$sw=$this->cnmd15_bono_vaca_vacaciones->execute("update cnmd15_bono_vaca_vacaciones set hasta_dia=".$dia2." , hasta_mes=".$mes2." , hasta_ano=".$ano2." , dias=".$dias." , basico=".$basicoa." , descuento=".$descuentoa." where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and escala=".$escala);
		if($sw>1){
			$this->set('Message_existe','se ha modificado exitosamente');
		}else{
			$this->set('errorMessage','No se pudo modificar, intente nuevamente');
		}

		$datos=$this->cnmd15_bono_vaca_vacaciones->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina,null,' ORDER BY escala ASC');
		$this->set('datos',$datos);
	}else{
		$this->set('errorMessage','Debe ingresar datos en todos los campos');
		$datos=$this->cnmd15_bono_vaca_vacaciones->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina,null,' ORDER BY escala ASC');
		$this->set('datos',$datos);
	}


}//fin guardar_modificar



function cancelar($nomina=null){
	$this->layout="ajax";
	$datos=$this->cnmd15_bono_vaca_vacaciones->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina,null,' ORDER BY escala ASC');
	$this->set('datos',$datos);
}


}//fin controller
?>