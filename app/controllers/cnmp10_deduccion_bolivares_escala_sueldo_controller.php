<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp10deduccionBolivaresEscalaSueldoController extends AppController {
   var $name = 'cnmp10_deduccion_bolivares_escala_sueldo';
   var $uses = array('Cnmd01',  'cnmd03_transacciones', 'cnmd10_control_escenarios','cnmd10_comunes_dia_deduccion','v_cnmd10_comunes_dia_deduccion_trans','cnmd10_comunes_escala_sueldo_bolivares_ded','cnmd10_comunes_escala_sueldo_bolivares_ded_2');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');


function checkSession()
    {
        // If the session info hasn't been set...
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


 function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  return $condicion;

}

function index($cod=null){
	$this->layout="ajax";
	if(isset($cod)){
		echo $cod."   ";
	}
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');

	$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 2', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');

	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled='disabled';";
	echo "</script>";
}



function select_trans($opc=null,$cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina==null && $opc!=null){
		$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion = 2', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
		if($cnmd03){
		$this->concatenaN($cnmd03, 'transaccion');
		$this->set('cod_nomina', $opc);
		}else{
			$this->set('vacio', "");
		}
	}
	if($opc=='radio'){
		$this->Session->delete('codi_tipo');
		$this->Session->write('codi_tipo',$cod_nomina);
		$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$cod_nomina, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');

		if($cnmd03){
			$this->concatenaN($cnmd03, 'radio');
		}else{
			$this->set('radio',array());
		}
$this->set('tipo',$cod_nomina);
	}
}



function cod_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_nomina', $cod_nomina);
	}
	echo "<script>";
		echo "document.getElementById('transferencia').innerHTML='';";
		echo"document.getElementById('modi').disabled ='disabled'; ";
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



function cod_trans($cod_trans=null){
	$this->layout="ajax";
	//echo "holaaaaaaaaa";
	if($cod_trans!=null){
		$this->set('cod_trans', $cod_trans);
		$this->Session->delete('trans');
		$this->Session->write('trans',$cod_trans);
	}
	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled=false;";
	echo "</script>";

}



function deno_trans($cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null && $cod_trans!=""){
		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$cod_trans' and cod_tipo_transaccion=2", $order ="cod_transaccion ASC");
		if($deno_trans){
		$this->set('deno_trans', $deno_trans);
		}else{
			$this->set('deno_trans', "");
		}
	}
}



function codi_trans($cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$this->set('cod_trans', $cod_trans);
	}

}



function denomi_trans($cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$cod_trans' and cod_tipo_transaccion=".$this->Session->read('codi_tipo'), $order ="cod_transaccion ASC");
		$this->set('deno_trans', $deno_trans);
	}
}



function verifica($cod_nomina=null, $cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null && $cod_trans!=null && $cod_trans!=""){
		$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=2 and cod_transaccion='$cod_trans'", $order =null);
		$this->set('ubicacion', $ubicacion);
		$veri=$this->cnmd10_comunes_escala_sueldo_bolivares_ded->findAll($this->condicion()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans);
		$this->set('opciones',$veri);
		if($veri!=null){
			$v=$this->cnmd10_comunes_escala_sueldo_bolivares_ded->execute("SELECT * FROM cnmd10_comunes_escala_sueldo_bolivares_ded WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans);
			$cod=$v[0][0]["codi_transaccion"];
			$cod1=$v[0][0]["codi_tipo_transaccion"];
			$deno = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions ="cod_tipo_transaccion=".$cod1." and cod_transaccion=".$cod, $order =null);
			$this->set('denominacion',$deno);
			$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$cod1, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');

			$this->concatenaN($cnmd03, 'transaccion');
		}else{

			$radios=$this->cnmd03_transacciones->execute("select * from cnmd03_transacciones where cod_tipo_transaccion=2 and cod_transaccion='$cod_trans'");
			$uso_transaccion=$radios[0][0]['uso_transaccion'];
			if($uso_transaccion==7){
				$this->Session->write('frecuencia',2);
				echo "<script>document.getElementById('frecuencia_2').checked=true;</script>";
				echo "<script>escenario_show();</script>";
			}else{
				$this->Session->write('frecuencia',1);
				echo "<script>document.getElementById('frecuencia_1').checked=true;</script>";
				echo "<script>escenario_show();</script>";
			}
			echo "<script>document.getElementById('frecuencia_1').disabled='disabled';</script>";
			echo "<script>document.getElementById('frecuencia_2').disabled='disabled';</script>";

		}


	}else{
		$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion =2', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
		$this->concatenaN($cnmd03, 'transaccion');

	}
}//fin select_trans



function vacio1(){
	$this->layout="ajax";
}//fin vacio



function grilla_vacia(){
	$this->layout="ajax";
}//fin grilla_vacia



function grilla($var=null,$var2=null){
	$this->layout="ajax";
	if($var2!=""){
	$cond= $this->SQLCA();
			$v=$this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->execute("SELECT * FROM cnmd10_comunes_escala_sueldo_bolivares_ded_2 WHERE ".$cond." and cod_tipo_nomina=".$var." and cod_transaccion=".$var2." ORDER BY escala DESC");
			if($v!=null){
				$escala=$v[0][0]["escala"];
				$desde_sueldo=$v[0][0]["desde_sueldo"];
				$hasta_sueldo=$v[0][0]["hasta_sueldo"];
				$monto=$v[0][0]["monto"];
				$escala = $escala =="" ? 1 : $escala+1;
				$desde_sueldo = $desde_sueldo =="" ? 0 : $hasta_sueldo+1;
				$hasta_sueldo = $hasta_sueldo =="" ? 2 : $hasta_sueldo+2;
				$monto = $monto =="" ? 1000 : $monto+1000;
				echo'<script>';
					  echo"document.getElementById('modi').disabled =false; ";
				echo'</script>';
			}else{
				$escala=1;
				$desde_sueldo=0;
				$monto=1000;
				echo'<script>';
					  echo"document.getElementById('modi').disabled ='disabled'; ";
				echo'</script>';
			}
		$this->set("escala",$escala);
		$this->set("desde_sueldo",$desde_sueldo);
		$this->set("monto",$monto);
		$datos = $this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->findAll($cond." and cod_tipo_nomina=".$var." and cod_transaccion=".$var2,null,'escala ASC');
		$this->set("datos",$datos);
	}else{
		$this->grilla_vacia();
	}
}//fin grilla



function agrega_input($var=null,$var2=null){
	$this->layout="ajax";

		if($var2!=""){
		$this->set('nomina',$var);
		$this->set('trans',$var2);
		$prueba=$this->cnmd10_comunes_escala_sueldo_bolivares_ded->findAll($this->SQLCA()." and cod_tipo_nomina=".$var." and cod_transaccion=".$var2,null,null);
		$cond= $this->SQLCA();
		if(!$prueba){
			echo "<script>";
				echo "document.getElementById('agregar').disabled='disabled';";
			echo "</script>";
		}else{
			echo "<script>";
				echo "document.getElementById('agregar').disabled=false;";
			echo "</script>";
		}
		$v=$this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->execute("SELECT * FROM cnmd10_comunes_escala_sueldo_bolivares_ded_2 WHERE ".$cond." and cod_tipo_nomina=".$var." and cod_transaccion=".$var2." ORDER BY escala DESC");
			if($v!=null){
				$escala=$v[0][0]["escala"];
				$desde_sueldo=$v[0][0]["desde_sueldo"];
				$hasta_sueldo=$v[0][0]["hasta_sueldo"];
				$escala = $escala =="" ? 1 : $escala+1;
				$desde_sueldo = $desde_sueldo =="" ? 0 : $hasta_sueldo+0.01;
			}else{
				$escala=1;
				$desde_sueldo=0;
				$monto=1000;
			}

			$this->set("escala",$escala);
			$this->set("desde_sueldo",$desde_sueldo);

		}else{
			$this->vacio1();
		}
}//fin agregar_input



function guardar_items(){
	$this->layout="ajax";

	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');

	  $cod_tipo_nomina = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_nomina'];
	  $cod_tipo_transaccion = 2;
	  $cod_transaccion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_trans'];
	  $monto = $this->Formato1($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['monto']);
	  $escala=$this->data['cnmp10_deduccion_bolivares_escala_sueldo']['escala'];
	  $desde_sueldo=$this->Formato1($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['desde_sueldo']);
	  $hasta_sueldo=$this->Formato1($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['hasta_sueldo']);

		$this->set('escala',$escala);
		$this->set('hasta_sueldo',$hasta_sueldo);

	    if($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['hasta_sueldo']!=""){
	    	$sql_insert2 = "INSERT INTO cnmd10_comunes_escala_sueldo_bolivares_ded_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$escala', '$desde_sueldo', '$hasta_sueldo', '$monto')";
			$sw = $this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->execute($sql_insert2);
			if($sw>1){
				$datos = $this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,'escala ASC');
				$this->set("datos",$datos);
				echo'<script>';
					  echo"document.getElementById('hasta_sueldo').value = ''; ";
					  echo"document.getElementById('desde_sueldo').readOnly = true; ";
					  echo"document.getElementById('monto').value = ''; ";
				echo'</script>';

			}else{
				$this->set('errorMessage',' POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
			}
	    }else{
	    	$this->set('errorMessage',' POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
	    }
}//fin guardar_items



function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $cod_tipo_nomina = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_nomina'];
    $cod_tipo_transaccion = 2;
    $cod_transaccion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_trans'];
	$monto = $this->Formato1($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['monto']);
	$cod_frecuencia = $this->Session->read('frecuencia');
	$cod_condicion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['condicion'];
	$escala = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['escala'];
	$desde_sueldo = $this->Formato1($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['desde_sueldo']);
	$hasta_sueldo = $this->Formato1($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['hasta_sueldo']);
    $ubicacion_escenario = strtoupper('Deducciones Comunes -  en Bolivares utilizando una escala de sueldo');
	$prueba=$this->cnmd10_comunes_escala_sueldo_bolivares_ded->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,null);

	if(!$prueba){
		 $monto = $this->Formato1($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['monto']);

		if($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['monto']==0 && $desde_sueldo==0 && $hasta_sueldo==""){
			$this->set('errorMessage', 'Debe completar todos los campos');
			return;
		}else{
			if  (!empty($this->data['cnmp10_deduccion_bolivares_escala_sueldo'])){

				if(empty($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['tipo_trans'])){
					$codi_tipo_transaccion = 0;
				}else{
					$codi_tipo_transaccion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['tipo_trans'];
				}

			if($cod_condicion==1){
				$codi_transaccion = 0;
			}else{
				if(empty($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['select4'])){
					 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
					 return;
				}else{
					 $codi_transaccion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['select4'];
				}
			}
				if(empty($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['escenario'])){
					$activar_frecuencia_eventual = 2;
				}else{
					$activar_frecuencia_eventual = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['escenario'];
				}
				$sql_insert = "INSERT INTO cnmd10_comunes_escala_sueldo_bolivares_ded VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
				$sw = $this->cnmd10_comunes_escala_sueldo_bolivares_ded->execute($sql_insert);
				$sql_insert2 = "INSERT INTO cnmd10_comunes_escala_sueldo_bolivares_ded_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$escala', '$desde_sueldo', '$hasta_sueldo', '$monto')";
				$sw = $this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->execute($sql_insert2);
				if($sw > 1){
					$this->set('Message_existe', 'EL ESCENARIO FUE GUARDADO CON EXITO');
					$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion', '$ubicacion_escenario')";
					$sw = $this->cnmd10_comunes_dia_deduccion->execute($sql_control);
					$this->set('escala',$escala);
					$this->set('hasta_sueldo',$hasta_sueldo);
					echo'<script>';
				 		  echo"document.getElementById('save').disabled='disabled'; ";
				 		  echo"document.getElementById('agregar').disabled=false; ";
						  echo"document.getElementById('hasta_sueldo').value = ''; ";
						  echo"document.getElementById('desde_sueldo').readOnly = true; ";
						  echo"document.getElementById('monto').value = ''; ";
						  echo"document.getElementById('modi').disabled =false; ";
					echo'</script>';
				}else{
					$this->set('errorMessage',' POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
				}
			}else{

			}
			$this->data['cnmp10_deduccion_bolivares_escala_sueldo'] = array();
			}//FIN ELSE VALIDA MONTO CERO
	}else{

	}
	$datos=$this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transaccion,null,null);
	 if($datos){
	 	 $this->set('datos',$datos);
	 }else{
	 	 $this->set('datos','');
	 }

	  $this->Session->delete('frecuencia');

}//FIN GUARDAR


function modificar(){
	$this->layout="ajax";
	$cod_tipo_nomina = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_nomina'];
	$cod_transaccion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_trans'];
//	$cod_frecuencia = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['frecuencia'];
	$cod_condicion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['condicion'];
	$a=0;

	if(empty($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['tipo_trans'])){
		$codi_tipo_transaccion = 0;
	}else{
		$codi_tipo_transaccion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['tipo_trans'];
	}
		if($cod_condicion==1){
			$codi_transaccion = 0;
		}else{
			if(empty($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['select4'])){
				 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
				 $a=1;
			}else{
				 $codi_transaccion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['select4'];
				 $cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$codi_transaccion, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');

				$this->concatenaN($cnmd03, 'transaccion');
				$deno = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions ="cod_tipo_transaccion=".$codi_tipo_transaccion." and cod_transaccion=".$codi_transaccion, $order =null);
				$this->set('denominacion',$deno);
			}

		}

			if(empty($this->data['cnmp10_deduccion_bolivares_escala_sueldo']['escenario'])){
				$activar_frecuencia_eventual = 2;
			}else{
				$activar_frecuencia_eventual = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['escenario'];
			}

			if($a!=1){
				$this->cnmd10_comunes_escala_sueldo_bolivares_ded->execute("update cnmd10_comunes_escala_sueldo_bolivares_ded set cod_condicion=".$cod_condicion.",codi_tipo_transaccion=".$codi_tipo_transaccion.",codi_transaccion=".$codi_transaccion.",activar_frecuencia_eventual=".$activar_frecuencia_eventual." where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion);///modifica el monto
		   		 $this->set('Message_existe', 'El registro se ha modificado');
			}

			$datos=$this->cnmd10_comunes_escala_sueldo_bolivares_ded->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transaccion,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }

}//fin modificar



function eliminar($cod_nomina=null, $cod_trans=null){
	$this->layout="ajax";
		$cod_tipo_nomina = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_nomina'];
	    $cod_tipo_transaccion = 1;
	    $cod_transaccion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_trans'];

	    $verificar=$this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transaccion);
		if($verificar!=null){
			if(($cod_tipo_nomina!="") && ($cod_transaccion!="")){
				$sql_eliminar="DELETE FROM cnmd10_comunes_escala_sueldo_bolivares_ded WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=2 and cod_transaccion='$cod_transaccion'";
				$sw = $this->cnmd10_comunes_escala_sueldo_bolivares_ded->execute($sql_eliminar);
				$sql_eliminar2="DELETE FROM cnmd10_comunes_escala_sueldo_bolivares_ded_2 WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=2 and cod_transaccion='$cod_transaccion'";
				$sw2 = $this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->execute($sql_eliminar2);
				if($sw>1){

					$sql_del_control = "DELETE FROM cnmd10_control_de_escenarios WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=2 and cod_transaccion='$cod_transaccion'";
					$sw = $this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->execute($sql_del_control);
					$this->set('msg', 'EL ESCENARIO FUE ELIMINADO CON EXITO');
					echo "<script>cnmp10_cancelacion_limpiar_eliminar1();</script>";
					$this->index();
					$this->render('index');
				}
			}else{
				echo "";
			}
		}else{
			echo'<script>';
			   echo"document.getElementById('cod_nomina').value = ''; ";
			echo'</script>';
			$this->set('errorMessage','el registro que desea eliminar no existe');
			$this->index();
			$this->render('index');
		}
		echo'<script>';
		   echo"document.getElementById('deno_nomina').value = ''; ";
		echo'</script>';
}//FIN ELIMINAR



function cod_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_trans', $cod_nomina);
		$cod_trans=$this->Session->read('trans');
		$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=2 and cod_transaccion='$cod_trans'", $order =null);
		$this->set('ubicacion', $ubicacion);

	}
}//fin cod_transferencia



function deno_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
	}
}//fin deno_transferencia



function transferir($var1=null,$var2=null){
	$this->layout="ajax";
	$this->Session->delete('nomi1');
	$this->Session->delete('trans1');
	$this->Session->write('nomi1',$var1);
	$this->Session->write('trans1',$var2);
	if($var2!=""){
		$carga=$this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->findAll($this->condicion()." and cod_tipo_nomina=".$var1." and cod_tipo_transaccion=2 and cod_transaccion=".$var2);
		if($carga){
			$lista2 = $this->Cnmd01->generateList($conditions = $this->condicion()." and cod_tipo_nomina!=".$var1, $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
			if($lista2){
			$this->concatenaN($lista2, 'transferir');
			$this->set('cod_nomina', $var1);
			}else{
				$this->set('transferir',array());
			}
		}else{
			$this->set('nada',"");
		}
	}else{
		$this->set('nada',"");
	}
//////////////////// AGREGAR A PARTIR DE AQUI////////////////////////////
	$datos=$this->cnmd10_comunes_escala_sueldo_bolivares_ded->findAll($this->SQLCA()." and cod_tipo_transaccion=2 and cod_transaccion=".$var2." and cod_tipo_nomina!=".$var1,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->Cnmd01->findAll($this->SQLCA(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
}//fin transferir



function guardar_transferir(){
	$this->layout="ajax";
	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');

	  $cod_tipo_nomina = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_nomina'];
	  $cod_transaccion = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_trans'];
	  $cod_transferir = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_transferir'];
	  $cod_tipo_transaccion=2;

	  $ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_transferir' and cod_tipo_transaccion=2 and cod_transaccion='$cod_transaccion'", $order =null);
	  if(!$ubicacion){
	  $data=$this->cnmd10_comunes_escala_sueldo_bolivares_ded->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transaccion);
	  $data2=$this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transaccion);
	  $ubicacion_escenario = strtoupper('Deducciones Comunes -  en Bolivares utilizando una escala de sueldo');
	  foreach($data as $row){
			$cod_frecuencia = $row['cnmd10_comunes_escala_sueldo_bolivares_ded']['cod_frecuencia'];
			$cod_condicion = $row['cnmd10_comunes_escala_sueldo_bolivares_ded']['cod_condicion'];
			$codi_tipo_transaccion = $row['cnmd10_comunes_escala_sueldo_bolivares_ded']['codi_tipo_transacci'];
			$codi_transaccion = $row['cnmd10_comunes_escala_sueldo_bolivares_ded']['codi_transaccion'];
			$activar_frecuencia_eventual = $row['cnmd10_comunes_escala_sueldo_bolivares_ded']['activar_frecuencia_'];
			$sql_insert = "INSERT INTO cnmd10_comunes_escala_sueldo_bolivares_ded VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
			$sw = $this->cnmd10_comunes_escala_sueldo_bolivares_ded->execute($sql_insert);
			$this->set('Message_existe', 'Transferencia realizada con exito');
			if($sw>1){
				echo "<script>";
					echo "document.getElementById('save_transferir').disabled='disabled';";
					echo "document.getElementById('select_transferir').options[0].text='';";
					echo "document.getElementById('cod_transferencia').value='';";
					echo "document.getElementById('deno_transferencia').value='';";
				echo "</script>";
				$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion', '$ubicacion_escenario')";
				$sw1 = $this->cnmd10_control_escenarios->execute($sql_control);
				if($sw1 > 1){
					foreach($data2 as $row){
						$escala = $row['cnmd10_comunes_escala_sueldo_bolivares_ded_2']['escala'];
						$desde = $row['cnmd10_comunes_escala_sueldo_bolivares_ded_2']['desde_sueldo'];
						$hasta = $row['cnmd10_comunes_escala_sueldo_bolivares_ded_2']['hasta_sueldo'];
						$monto = $row['cnmd10_comunes_escala_sueldo_bolivares_ded_2']['monto'];
						$sql_insert2 = "INSERT INTO cnmd10_comunes_escala_sueldo_bolivares_ded_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion','$escala','$desde','$hasta', '$monto')";
						$sw2 = $this->cnmd10_comunes_escala_sueldo_bolivares_ded_2->execute($sql_insert2);
					}// fin foreach data2
				}//fin $sw1
			}//fin $sw
	  }//fin foreach data
	  if($sw>1 && $sw1>1 && $sw2>1){
	  		$this->set('Message_existe', 'Transferencia realizada con exito');
	  }else{
	  		$this->set('Message_error', 'Transferencia sin exito intente nuevamente');
	  }
}else{//fin ubicacion
	$this->set('Message_error', 'Transferencia sin exito intente nuevamente');
}

//////////////////////////////AGREGAR DESDE AQUI////////////////////

	$datos=$this->cnmd10_comunes_escala_sueldo_bolivares_ded->findAll($this->SQLCA()." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transaccion." and cod_tipo_nomina!=".$cod_tipo_nomina,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->Cnmd01->findAll($this->SQLCA(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);

}// fin guardar_transferir



function transaccion($var=null){
	$this->layout="ajax";
	if($var!='guarda'){
		$nomina=$var;
	}else{
		 $nomina = $this->data['cnmp10_deduccion_bolivares_escala_sueldo']['cod_nomina'];
	}
	$datos=$this->cnmd10_comunes_escala_sueldo_bolivares_ded->findAll($this->SQLCA()." and cod_tipo_transaccion=2 and cod_tipo_nomina=".$nomina,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=2', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
	 $this->render('transaccion');
}//fin transaccion




}//FIN CONTROLADOR
?>