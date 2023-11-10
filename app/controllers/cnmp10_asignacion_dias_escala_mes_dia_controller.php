<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp10AsignacionDiasEscalaMesDiaController extends AppController {
   var $name = 'cnmp10_asignacion_dias_escala_mes_dia';
   var $uses = array('Cnmd01','cnmd03_transacciones','cnmd10_control_escenarios','cnmd10_comunes_escala_mes_dia_asig_2','cnmd10_comunes_escala_mes_dia_asig');
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

	$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');

}



function select_trans($opc=null,$cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina==null && $opc!=null){
		$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
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
		$this->Session->delete('nomina');
		$this->Session->write('nomina',$cod_nomina);
	}
	echo "<script>";
		echo"document.getElementById('modi').disabled ='disabled'; ";
	echo "</script>";
}



function deno_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
	}
	echo "<script>";
		echo "document.getElementById('transferencia').innerHTML='';";
	echo "</script>";
}



function cod_trans($cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$this->set('cod_trans', $cod_trans);
		$this->Session->delete('transaccion');
		$this->Session->write('transaccion',$cod_trans);
	}
	$cod_tipo_nomina=$this->Session->read('nomina');
	$prueba=$this->cnmd10_comunes_escala_mes_dia_asig->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_trans,null,null);
	if($prueba){
		echo "<script>";
			echo "document.getElementById('input_tag').readOnly=true;";
		echo "</script>";
	}else{
		echo "<script>";
			echo "document.getElementById('input_tag').readOnly=false;";
		echo "</script>";
	}
	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled=false;";
		echo "document.getElementById('input_tag').value='';";
	echo "</script>";
}




function deno_trans($cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$cod_trans' and cod_tipo_transaccion=1", $order ="cod_transaccion ASC");
		$this->set('deno_trans', $deno_trans);
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
		$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans'", $order =null);
		$this->set('ubicacion', $ubicacion);
		$veri=$this->cnmd10_comunes_escala_mes_dia_asig->findAll($this->condicion()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans);
		$this->set('opciones',$veri);
		if($veri){
			$v=$this->cnmd10_comunes_escala_mes_dia_asig->execute("SELECT * FROM cnmd10_comunes_escala_mes_dia_asig WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans);
			$cod=$v[0][0]["codi_transaccion"];
			$cod1=$v[0][0]["codi_tipo_transaccion"];
			$deno = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions ="cod_tipo_transaccion=".$cod1." and cod_transaccion=".$cod, $order =null);
			$this->set('denominacion',$deno);
			$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$cod1, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
			$this->concatenaN($cnmd03, 'transaccion');
		}else{

			$radios=$this->cnmd03_transacciones->execute("select * from cnmd03_transacciones where cod_tipo_transaccion=1 and cod_transaccion='$cod_trans'");
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
		$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
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
			$v=$this->cnmd10_comunes_escala_mes_dia_asig_2->execute("SELECT * FROM cnmd10_comunes_escala_mes_dia_asig_2 WHERE ".$cond." and cod_tipo_nomina=".$var." and cod_transaccion=".$var2." ORDER BY escala DESC");
			if($v!=null){
				$escala=$v[0][0]["escala"];
				$desde_mes=$v[0][0]["desde_mes"];
				$desde_dia=$v[0][0]["desde_dia"];
				$monto=$v[0][0]["dias"];
				$escala = $escala =="" ? 1 : $escala+1;
				$desde_mes = $desde_mes =="" ? 0 : $desde_mes+1;
				$desde_dia = $desde_dia =="" ? 2 : $desde_dia+2;
				$monto = $monto =="" ? 1000 : $monto+1000;
				echo'<script>';
					  echo"document.getElementById('modi').disabled =false; ";
				echo'</script>';
			}else{
				$escala=1;
				$desde_mes=1;
				$desde_dia=1;
				$desde_ano=0;
				$monto=1000;
				echo'<script>';
					  echo"document.getElementById('modi').disabled ='disabled'; ";
				echo'</script>';
			}
		$this->set("escala",$escala);
		$this->set("desde_mes",$desde_mes);
		$this->set("desde_dia",$desde_dia);
		$this->set("monto",$monto);
		$datos = $this->cnmd10_comunes_escala_mes_dia_asig_2->findAll($cond." and cod_tipo_nomina=".$var." and cod_transaccion=".$var2,null,'escala ASC');
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
		$prueba=$this->cnmd10_comunes_escala_mes_dia_asig->findAll($this->SQLCA()." and cod_tipo_nomina=".$var." and cod_transaccion=".$var2,null,null);
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
		$v=$this->cnmd10_comunes_escala_mes_dia_asig_2->execute("SELECT * FROM cnmd10_comunes_escala_mes_dia_asig_2 WHERE ".$cond." and cod_tipo_nomina=".$var." and cod_transaccion=".$var2." ORDER BY escala DESC");
			if($v!=null){
				$escala=$v[0][0]["escala"];
				$desde_mes=$v[0][0]["desde_mes"];
				$desde_dia=$v[0][0]["desde_dia"];
				$escala = $escala =="" ? 1 : $escala+1;
				$desde_mes = $desde_mes =="" ? 0 : $desde_mes+1;
				$desde_dia = $desde_dia =="" ? 2 : $desde_dia+2;
			}else{
				$escala=1;
				$desde_mes=1;
				$desde_dia=1;
				$monto=1000;
			}

			$this->set("escala",$escala);
			$this->set("desde_mes",$desde_mes);
			$this->set("desde_dia",$desde_dia);

		}else{
			$this->vacio1();
		}
}//fin agregar_input



function datos($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina != null){
		$deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=1', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
		$this->set('deno_trans', $deno_trans);
		$datos = $this->cnmd10_bolivares_asig->findAll($conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina'", $fields ='cod_transaccion, monto,codi_transaccion', $order = 'cod_tipo_nomina, cod_transaccion', $limit = null, $page = null, $recursive = null);
		$this->set('datos', $datos);
		$this->set('cod_nomina', $cod_nomina);

	}
}




function guardar_items(){
	$this->layout="ajax";
	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');

	  $cod_tipo_nomina = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_nomina'];
	  $cod_tipo_transaccion = 1;
	  $cod_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_trans'];
		$monto = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['monto'];
		$escala = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['escala'];
		$desde_mes = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['desde_mes'];
		$hasta_mes = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['hasta_mes'];
		$desde_dia = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['desde_dia'];
		$hasta_dia = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['hasta_dia'];


		$this->set('desde_mes',$desde_mes);
if($desde_mes!="" && $hasta_mes!="" && $desde_dia!="" && $hasta_dia!="" && $monto!=""){
	    if($this->data['cnmp10_asignacion_dias_escala_mes_dia']['hasta_mes']!=""){
	    	$sql_insert2 = "INSERT INTO cnmd10_comunes_escala_mes_dia_asig_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$escala', '$desde_mes', '$hasta_mes','$desde_dia','$hasta_dia','$monto')";
			$sw = $this->cnmd10_comunes_escala_mes_dia_asig_2->execute($sql_insert2);
			$this->set('escala',$escala);
	    }
}else{
	$this->set('errorMessage','Debe completar todos los campos');
}
$datos = $this->cnmd10_comunes_escala_mes_dia_asig_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,'escala ASC');
$this->set("datos",$datos);
}//fin guardar items



function guardar($bandera=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $cod_tipo_nomina = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_nomina'];
    $cod_tipo_transaccion = 1;
    $cod_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_trans'];
    $cod_frecuencia = $this->Session->read('frecuencia');
	$cod_condicion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['condicion'];
	$prueba=$this->cnmd10_comunes_escala_mes_dia_asig->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,null);

	if(!$prueba){
	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $ubicacion_escenario = strtoupper('ASIGNACI0NES COMUNES - EN DIAS CALCULADO UTILIZANDO UNA ESCALA POR MES Y DIA (EJEMPLO: AGUINALDOS)');
	if($bandera=="manual"){
		$monto = $this->Formato1($this->data['cnmp10_asignacion_dias_escala_mes_dia']['monto']);

		$escala = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['escala'];
		$desde_mes = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['desde_mes'];
		$hasta_mes = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['hasta_mes'];
		$desde_dia = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['desde_dia'];
		$hasta_dia = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['hasta_dia'];


		if($this->data['cnmp10_asignacion_dias_escala_mes_dia']['monto']==0 && $desde_mes==0 && $hasta_mes=="" && $desde_dia=="" && $hasta_dia==""){
			$this->set('errorMessage', 'Debe completar todos los campos');
			return;
		}else{
			if  (!empty($this->data['cnmp10_asignacion_dias_escala_mes_dia'])){

				if(empty($this->data['cnmp10_asignacion_dias_escala_mes_dia']['tipo_trans'])){
					$codi_tipo_transaccion = 0;
				}else{
					$codi_tipo_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['tipo_trans'];
				}

			if($cod_condicion==1){
				$codi_transaccion = 0;
			}else{
				if(empty($this->data['cnmp10_asignacion_dias_escala_mes_dia']['select4'])){
					 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
					 return;
				}else{
					 $codi_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['select4'];
				}

			}

				if(empty($this->data['cnmp10_asignacion_dias_escala_mes_dia']['escenario'])){
					$activar_frecuencia_eventual = 2;
				}else{
					$activar_frecuencia_eventual = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['escenario'];
				}

				$sql_insert = "INSERT INTO cnmd10_comunes_escala_mes_dia_asig VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
				$sw = $this->cnmd10_comunes_escala_mes_dia_asig->execute($sql_insert);
				$sql_insert2 = "INSERT INTO cnmd10_comunes_escala_mes_dia_asig_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$escala', '$desde_mes', '$hasta_mes','$desde_dia','$hasta_dia', '$monto')";
				$sw = $this->cnmd10_comunes_escala_mes_dia_asig_2->execute($sql_insert2);
				if($sw > 1){
					$this->set('Message_existe', 'EL ESCENARIO FUE GUARDADO CON EXITO');
					$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion', '$ubicacion_escenario')";
					$sw = $this->cnmd10_comunes_escala_mes_dia_asig_2->execute($sql_control);
					$escala=$escala+1;
					echo'<script>';
				 		  echo"document.getElementById('save').disabled='disabled'; ";
				 		  echo"document.getElementById('agregar').disabled=false; ";
				 		  echo"document.getElementById('escala').value =".$escala."; ";
						  echo"document.getElementById('desde_mes').value = ''; ";
						  echo"document.getElementById('hasta_mes').value = ''; ";
						  echo"document.getElementById('desde_dia').value = ''; ";
						  echo"document.getElementById('hasta_dia').value = ''; ";
						  echo"document.getElementById('hasta_dia').value = ''; ";
						  echo"document.getElementById('monto').value = ''; ";
						  echo"document.getElementById('modi').disabled =false; ";
					echo'</script>';
				}else{
					$this->set('errorMessage',' POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
				}

			}else{
			}
			$this->data['cnmp10_asignacion_dias_escala_mes_dia'] = array();
			}//FIN ELSE VALIDA MONTO CERO
	}else if($bandera=="auto"){
		if  (!empty($this->data['cnmp10_asignacion_dias_escala_mes_dia'])){
			if(empty($this->data['cnmp10_asignacion_dias_escala_mes_dia']['tipo_trans'])){
				$codi_tipo_transaccion = 0;
			}else{
				$codi_tipo_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['tipo_trans'];
			}

		if($cod_condicion==1){
			$codi_transaccion = 0;
		}else{
			if(empty($this->data['cnmp10_asignacion_dias_escala_mes_dia']['select4'])){
				 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
				 return;
			}else{
				 $codi_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['select4'];
			}

		}

			if(empty($this->data['cnmp10_asignacion_dias_escala_mes_dia']['escenario'])){
				$activar_frecuencia_eventual = 2;
			}else{
				$activar_frecuencia_eventual = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['escenario'];
			}
			$sql_insert = "INSERT INTO cnmd10_comunes_escala_mes_dia_asig VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
			$sw = $this->cnmd10_comunes_escala_mes_dia_asig->execute($sql_insert);
			if($sw>1){
				$vec=$_SESSION["MESES"];
					for($i=1;$i<13;$i++){
						for($j=0;$j<31;$j++){
							$escala=$vec[$i][$j]['escala'];
							$desde_mes=$vec[$i][$j]['desde_mes'];
							$hasta_mes=$vec[$i][$j]['hasta_mes'];
							$desde_dia=$vec[$i][$j]['desde_dia'];
							$hasta_dia=$vec[$i][$j]['hasta_dia'];
							$cantidad=$vec[$i][$j]['dias_asignar'];
							$sql_insert2 = "INSERT INTO cnmd10_comunes_escala_mes_dia_asig_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$escala', '$desde_mes', '$hasta_mes','$desde_dia','$hasta_dia', '$cantidad')";
							$sw1 = $this->cnmd10_comunes_escala_mes_dia_asig_2->execute($sql_insert2);
							if($i==12){
								break;
							}
						}//fin for 2
					}//fin for 1
				if($sw1 > 1){
					$this->set('Message_existe', 'EL ESCENARIO FUE GUARDADO CON EXITO');
					$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion', '$ubicacion_escenario')";
					$sw = $this->cnmd10_comunes_escala_mes_dia_asig_2->execute($sql_control);
					$this->Session->delete('tipo');
					$escala=$escala+1;
					$this->Session->delete('tipo');
					echo'<script>';
						  echo"document.getElementById('modi').disabled =false; ";
					echo'</script>';
				}else{
					$this->set('errorMessage',' POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
				}
			}//fin sw>1

		}else{
			$this->set('errorMessage',' debe completar todos los campos');
		}

	}//fin tipo
}else{
	$this->set('errorMessage',' ESTA TRANSACCION YA EXISTE REGISTRADO');
}
$datos=$this->cnmd10_comunes_escala_mes_dia_asig_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion,null,null);
	 if($datos){
	 	 $this->set('datos',$datos);
	 }else{
	 	 $this->set('datos','');
	 }

	 $this->Session->delete('frecuencia');

}//FIN GUARDAR



function modificar(){
	$this->layout="ajax";
	$cod_tipo_nomina = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_nomina'];
	$cod_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_trans'];
//	$cod_frecuencia = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['frecuencia'];
	$cod_condicion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['condicion'];
	$a=0;

	if(empty($this->data['cnmp10_asignacion_dias_escala_mes_dia']['tipo_trans'])){
		$codi_tipo_transaccion = 0;
	}else{
		$codi_tipo_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['tipo_trans'];
	}

		if($cod_condicion==1){
			$codi_transaccion = 0;
		}else{
			if(empty($this->data['cnmp10_asignacion_dias_escala_mes_dia']['select4'])){
				 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
				 $a=1;
			}else{
				 $codi_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['select4'];
				 $cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$codi_tipo_transaccion, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
				$this->concatenaN($cnmd03, 'transaccion');
				$deno = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions ="cod_tipo_transaccion=".$codi_tipo_transaccion." and cod_transaccion=".$codi_transaccion, $order =null);
				$this->set('denominacion',$deno);
			}

		}

			if(empty($this->data['cnmp10_asignacion_dias_escala_mes_dia']['escenario'])){
				$activar_frecuencia_eventual = 2;
			}else{
				$activar_frecuencia_eventual = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['escenario'];
			}

			if($a!=1){
				$this->cnmd10_comunes_escala_mes_dia_asig->execute("update cnmd10_comunes_escala_mes_dia_asig set cod_condicion=".$cod_condicion.",codi_tipo_transaccion=".$codi_tipo_transaccion.",codi_transaccion=".$codi_transaccion.",activar_frecuencia_eventual=".$activar_frecuencia_eventual." where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion);///modifica el monto
		   		 $this->set('Message_existe', 'El registro se ha modificado');
			}

			$datos=$this->cnmd10_comunes_escala_mes_dia_asig->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
}//fin modificar




function eliminar($cod_nomina=null, $cod_trans=null){
	$this->layout="ajax";
		$cod_tipo_nomina = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_nomina'];
	    $cod_tipo_transaccion = 1;
	    $cod_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_trans'];

	    $verificar=$this->cnmd10_comunes_escala_mes_dia_asig_2->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
		if($verificar!=null){
			if(($cod_tipo_nomina!="") && ($cod_transaccion!="")){
				$sql_eliminar="DELETE FROM cnmd10_comunes_escala_mes_dia_asig WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'";
				$sw = $this->cnmd10_comunes_escala_mes_dia_asig->execute($sql_eliminar);
				$sql_eliminar2="DELETE FROM cnmd10_comunes_escala_mes_dia_asig_2 WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'";
				$sw2 = $this->cnmd10_comunes_escala_mes_dia_asig_2->execute($sql_eliminar2);
				if($sw>1){

					$sql_del_control = "DELETE FROM cnmd10_control_de_escenarios WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'";
					$sw = $this->cnmd10_comunes_escala_mes_dia_asig_2->execute($sql_del_control);
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
		$cod_trans=$this->Session->read('transaccion');
		$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans'", $order =null);
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
		$carga=$this->cnmd10_comunes_escala_mes_dia_asig_2->findAll($this->condicion()." and cod_tipo_nomina=".$var1." and cod_tipo_transaccion=1 and cod_transaccion=".$var2);
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

		//////////////////// AGREGAR A PARTIR DE AQUI////////////////////////////
	$datos=$this->cnmd10_comunes_escala_mes_dia_asig->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_transaccion=".$var2." and cod_tipo_nomina!=".$var1,null,null);
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

	  $cod_tipo_nomina = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_nomina'];
	  $cod_transaccion = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_trans'];
	  $cod_transferir = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_transferir'];
	  $cod_tipo_transaccion=1;

	  $ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_transferir' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'", $order =null);
	  if(!$ubicacion){
	  $data=$this->cnmd10_comunes_escala_mes_dia_asig->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
	  $data2=$this->cnmd10_comunes_escala_mes_dia_asig_2->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
	  $ubicacion_escenario = strtoupper('ASIGNACI0NES COMUNES - EN DIAS CALCULADO UTILIZANDO UNA ESCALA POR MES Y DIA (EJEMPLO: AGUINALDOS)');
	  foreach($data as $row){
			$cod_frecuencia = $row['cnmd10_comunes_escala_mes_dia_asig']['cod_frecuencia'];
			$cod_condicion = $row['cnmd10_comunes_escala_mes_dia_asig']['cod_condicion'];
			$codi_tipo_transaccion = $row['cnmd10_comunes_escala_mes_dia_asig']['codi_tipo_transaccion'];
			$codi_transaccion = $row['cnmd10_comunes_escala_mes_dia_asig']['codi_transaccion'];
			$activar_frecuencia_eventual = $row['cnmd10_comunes_escala_mes_dia_asig']['activar_frecuencia_eventual'];
			$sql_insert = "INSERT INTO cnmd10_comunes_escala_mes_dia_asig VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
			$sw = $this->cnmd10_comunes_escala_mes_dia_asig->execute($sql_insert);
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
						$escala = $row['cnmd10_comunes_escala_mes_dia_asig_2']['escala'];
						$desde_mes = $row['cnmd10_comunes_escala_mes_dia_asig_2']['desde_mes'];
						$hasta_mes = $row['cnmd10_comunes_escala_mes_dia_asig_2']['hasta_mes'];
						$desde_dia = $row['cnmd10_comunes_escala_mes_dia_asig_2']['desde_dia'];
						$hasta_dia = $row['cnmd10_comunes_escala_mes_dia_asig_2']['hasta_dia'];
						$monto= $row['cnmd10_comunes_escala_mes_dia_asig_2']['dias'];
						$sql_insert2 = "INSERT INTO cnmd10_comunes_escala_mes_dia_asig_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion','$escala','$desde_mes','$hasta_mes','$desde_dia',$hasta_dia,'$monto')";
						$sw2 = $this->cnmd10_comunes_escala_mes_dia_asig_2->execute($sql_insert2);
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

	$datos=$this->cnmd10_comunes_escala_mes_dia_asig->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion." and cod_tipo_nomina!=".$cod_tipo_nomina,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->Cnmd01->findAll($this->SQLCA(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
}// fin guardar_transferir


function distribucion($var=null){
	$this->layout="ajax";
	if(!empty($var) && $var!=0){
		$_SESSION["MESES"]=array();
		$this->Session->delete('tipo');
		$this->Session->write('tipo','si');
		$mes=$var/12;
		$dia=$mes/30;

		$escala=1;

		for($i=1;$i<13;$i++){
			$cantidad=$i*$mes;
			$k=$i;
				for($j=0;$j<31;$j++){
					$vec[$i][$j]['escala']=$escala;
					$vec[$i][$j]['desde_mes']=$i;
					$vec[$i][$j]['hasta_mes']=$i;
					$vec[$i][$j]['desde_dia']=$j;
					$vec[$i][$j]['hasta_dia']=$j;
					$vec[$i][$j]['dias_asignar']=0;
					if($k==$i){
						$vec[$i][$j]['dias_asignar']=$cantidad;
						$k++;
						$monto=$cantidad;
					}else{
						$monto=$monto+$dia;
						$vec[$i][$j]['dias_asignar']=$monto;
					}
					$escala++;
					if($i==12){
						break;
					}
				}//fin J
				$_SESSION["MESES"]=$_SESSION["MESES"]+$vec;
		}//fin i
	}else{
		$this->set('errorMessage', 'Debe ingresar una cantidad valida');
	}
}//fin distribucion



function botones($var=null,$var2=null,$var3=null){
	$this->layout="ajax";
if($var=='manual'){
	$this->set('bandera',$var);
	$ubicacion = $this->cnmd10_control_escenarios->FindAll($this->condicion()." and cod_tipo_nomina='$var2' and cod_tipo_transaccion=1 and cod_transaccion='$var3'");
}else if($var=='auto'){
	$nomi=$this->Session->read('nomina');
	$trans=$this->Session->read('transaccion');
	$this->set('bandera',$var);
	$ubicacion = $this->cnmd10_control_escenarios->FindAll($this->condicion()." and cod_tipo_nomina='$nomi' and cod_tipo_transaccion=1 and cod_transaccion='$trans'");
}
	if($ubicacion!=null){
		$this->set('disabled','disabled');
	}else{
		$this->set('disabled','');
	}
}//fin botones



function transaccion($var=null){
	$this->layout="ajax";
	if($var!='guarda'){
		$nomina=$var;
	}else{
		 $nomina = $this->data['cnmp10_asignacion_dias_escala_mes_dia']['cod_nomina'];
	}
	$datos=$this->cnmd10_comunes_escala_mes_dia_asig->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_tipo_nomina=".$nomina,null,null);
	 if($datos){
	 	 $this->set('opciones',$datos);
	 }else{
	 	 $this->set('opciones','');
	 }
	 $deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=1', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
	 $this->render('transaccion');
}//fin transaccion


}//FIN CONTROLADOR
?>