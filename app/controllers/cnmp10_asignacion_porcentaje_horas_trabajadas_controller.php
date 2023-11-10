<?php
/*
 * Fecha: 14/02/2008
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp10AsignacionPorcentajeHorasTrabajadasController extends AppController {
   var $name = 'cnmp10_asignacion_porcentaje_horas_trabajadas';
   var $uses = array('Cnmd01',  'cnmd03_transacciones', 'cnmd10_control_escenarios','cnmd10_individual_porcentaje_horas','cnmd10_individual_porcentaje_horas_cantidad','cnmd06_fichas','cnmd06_datos_personales','v_cnmd05','v_cnmd06_fichas_datos_personales');
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



function porcentaje_5_2($monto1=null){
	/*
	 * Esta funcion da formato
	 * a los porcentajes con
	 * precision numeric(5,2)
	 * llamando en el programa
	 * a la funcion moneda
	 * */
	$monto=str_replace(".",",",$monto1);
	  $paso = explode(',', $monto);
      $monto_aux[] = $paso[0];
      if(strlen($paso[1])>2){
      	$a=$paso[1];
      	$b=substr($a,0,2);
      	if($a[2]>5){
      		if($b<99)
      		  $b=$b+1;
      	}
      	$paso[1]=$b;
      }
      $monto_aux[] = $paso[1];
      $monto=implode('.', $monto_aux);
	  $monto=sprintf("%01.2f",$this->Formato1($monto));
	 return $monto;
}//fin porcentaje_5_2



function index($cod=null){
	$this->layout="ajax";
	if(isset($cod)){
		echo $cod."   ";
	}

	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');

	/*
	$lista = $this->v_cnmd05->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
	$this->concatenaN($lista, 'nomina');
	*/

	$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');

	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled='disabled';";
	echo "</script>";
	echo "<script>";
		echo "document.getElementById('agregar').disabled='disabled';";
	echo "</script>";

	$this->data[][]=null;
}//fin index



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
		$this->Session->delete('codi_tipo1');
		$this->Session->write('codi_tipo1',$cod_nomina);
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
		$this->Session->write('nomina',$cod_nomina);
	}
	echo "<script>";
		echo "document.getElementById('cedula').value='';";
		echo "document.getElementById('primer_ape').value='';";
		echo "document.getElementById('segudo_ape').value='';";
		echo "document.getElementById('primer_nombre').value='';";
		echo "document.getElementById('segundo_nombre').value='';";
		echo "document.getElementById('cantidad').value='';";
		echo "document.getElementById('transaccion').value='';";
		echo "document.getElementById('denominacion').value='';";
		echo "document.getElementById('porcentaje').value='';";
		echo"document.getElementById('modi').disabled ='disabled'; ";
	echo "</script>";
}//fin cod_nomina



function deno_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
	}
}



function cod_trans($cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$this->set('cod_trans', $cod_trans);
		$this->Session->delete('trans');
		$this->Session->write('trans',$cod_trans);
	}
	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled=false;";
	echo "</script>";

	echo "<script>";
		echo "document.getElementById('st_select_2').innerHTML='<select></select>';";
		echo "document.getElementById('cedula').value='';";
		echo "document.getElementById('primer_ape').value='';";
		echo "document.getElementById('segudo_ape').value='';";
		echo "document.getElementById('primer_nombre').value='';";
		echo "document.getElementById('segundo_nombre').value='';";
		echo "document.getElementById('cantidad').value='';";
		echo "document.getElementById('buscar').disabled=false;";
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
		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$cod_trans' and cod_tipo_transaccion=".$this->Session->read('codi_tipo1'), $order ="cod_transaccion ASC");
		$this->set('deno_trans', $deno_trans);
	}
}



function verifica($cod_nomina=null, $cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null && $cod_trans!=""){
	 $ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->SQLCA()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans'", $order =null);
		$this->set('ubicacion', $ubicacion);
		$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
		$this->concatenaN($cnmd03, 'transaccion');

		$veri=$this->cnmd10_individual_porcentaje_horas->findAll($this->condicion()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans);
		$this->set('opciones',$veri);
		if($veri){
			$v=$this->cnmd10_individual_porcentaje_horas->execute("SELECT * FROM cnmd10_individual_porcentaje_horas WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans);
			$cod=$v[0][0]["codi_transaccion"];
			$cod1=$v[0][0]["codi_tipo_transaccion"];
			$porcentaje=$v[0][0]["porcentaje"];
			$deno = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions ="cod_tipo_transaccion=".$cod1." and cod_transaccion=".$cod, $order =null);
			$this->set('denominacion',$deno);
			$this->set('porcentaje',$porcentaje);
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



function mostrar($var=null,$cod=null){
	$this->layout="ajax";
	if($cod!=""){
		switch($var){
			case 'cargo':
				$this->Session->write('cod1',$cod);
				$cond=$this->SQLCA()." and cod_tipo_nomina=".$cod;
				$lista = $this->v_cnmd05->generateList($conditions = $cond, $order = 'cod_cargo', $limit = null, '{n}.v_cnmd05.cod_cargo', '{n}.v_cnmd05.denominacion_clase');
				if($lista){
					$this->concatenaN($lista, 'cargo');
				}else{
					$this->set('vacio','');
				}
			break;
			case 'ficha';
				$nom=$this->Session->read('cod1');
				$this->Session->write('cod2',$cod);
				$cond=$this->SQLCA()." and cod_tipo_nomina=".$nom." and cod_cargo=".$cod;
				$lista = $this->cnmd06_fichas->generateList($conditions = $cond, $order = 'cod_ficha', $limit = null, '{n}.cnmd06_fichas.cod_ficha', '{n}.cnmd06_fichas.cedula_identidad');
				if($lista){
					$this->concatena($lista, 'ficha');
				}else{
					$this->set('vacio','');
				}
				echo "<script>";
					echo "document.getElementById('cedula').value='';";
					echo "document.getElementById('primer_ape').value='';";
					echo "document.getElementById('segudo_ape').value='';";
					echo "document.getElementById('primer_nombre').value='';";
					echo "document.getElementById('segundo_nombre').value='';";
					echo "document.getElementById('cantidad').value='';";
				echo "</script>";
			break;
			case 'cedula':
				$nom=$this->Session->read('cod1');
				$cargo=$this->Session->read('cod2');
				$cond=$this->SQLCA()." and cod_tipo_nomina=".$nom." and cod_cargo=".$cargo." and cod_ficha=".$cod;
				$cedula = $this->cnmd06_fichas->field('cnmd06_fichas.cedula_identidad',$conditions=$cond, $order =null);
				$this->set('cedula',$cedula);
				$this->Session->write('ced',$cedula);
				$trans=$this->Session->read('trans');
				$ver1=$this->cnmd10_individual_porcentaje_horas->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nom." and cod_transaccion=".$trans);
				if(!$ver1){
					echo "<script>";
						echo "document.getElementById('agregar').disabled='disabled';";
					echo "</script>";
				}else{
					$ver=$this->cnmd10_individual_porcentaje_horas_cantidad->FindAll($cond." and cod_transaccion=".$trans);
					if($ver){
						echo "<script>";
							echo "document.getElementById('agregar').disabled='disabled';";
						echo "</script>";
					}else{
						echo "<script>";
							echo "document.getElementById('agregar').disabled=false;";
						echo "</script>";
					}//fin ver
				}
			break;
			case 'apellido1':
				$nom=$this->Session->read('cod1');
				$cargo=$this->Session->read('cod2');
				$cond=$this->SQLCA()." and cod_tipo_nomina=".$nom." and cod_cargo=".$cargo." and cod_ficha=".$cod;
				$ced = $this->cnmd06_fichas->field('cnmd06_fichas.cedula_identidad',$conditions=$cond, $order =null);
				$apellido1 = $this->cnmd06_datos_personales->field('cnmd06_datos_personales.primer_apellido',$conditions="cedula_identidad=".$ced, $order =null);
				$this->set('apellido1',$apellido1);
			break;
			case 'apellido2':
				$nom=$this->Session->read('cod1');
				$cargo=$this->Session->read('cod2');
				$cond=$this->SQLCA()." and cod_tipo_nomina=".$nom." and cod_cargo=".$cargo." and cod_ficha=".$cod;
				$ced = $this->cnmd06_fichas->field('cnmd06_fichas.cedula_identidad',$conditions=$cond, $order =null);
			    $apellido2 = $this->cnmd06_datos_personales->field('cnmd06_datos_personales.segundo_apellido',$conditions="cedula_identidad=".$ced, $order =null);
			    $this->set('apellido2',$apellido2);
			break;
			case 'nombre1'://echo "hola";
				 $nom=$this->Session->read('cod1');
				 $cargo=$this->Session->read('cod2');
				 $cond=$this->SQLCA()." and cod_tipo_nomina=".$nom." and cod_cargo=".$cargo." and cod_ficha=".$cod;
				 $ced = $this->cnmd06_fichas->field('cnmd06_fichas.cedula_identidad',$conditions=$cond, $order =null);
				 $nombre1 = $this->cnmd06_datos_personales->field('cnmd06_datos_personales.primer_nombre',$conditions="cedula_identidad=".$ced, $order =null);
				 $this->set('nombre1',$nombre1);
			break;
			case 'nombre2':
				 $nom=$this->Session->read('cod1');
				 $cargo=$this->Session->read('cod2');
				 $cond=$this->SQLCA()." and cod_tipo_nomina=".$nom." and cod_cargo=".$cargo." and cod_ficha=".$cod;
				 $ced = $this->cnmd06_fichas->field('cnmd06_fichas.cedula_identidad',$conditions=$cond, $order =null);
				 $nombre2 = $this->cnmd06_datos_personales->field('cnmd06_datos_personales.segundo_nombre',$conditions="cedula_identidad=".$ced, $order =null);
				 $this->set('nombre2',$nombre2);
			break;
		}//fin switch
    }else{
    	$this->set('vacio','');
    }
}//fin mostrar


function grilla($var=null,$var2=null){
	$this->layout="ajax";
	if($var2!=""){
			$datos=$this->cnmd10_individual_porcentaje_horas_cantidad->FindAll($this->SQLCA()." and cod_tipo_nomina=".$var." and cod_transaccion=".$var2." ORDER BY cod_ficha ASC");
			if($datos){
				$this->set('datos',$datos);
				$grilla=$this->v_cnmd06_fichas_datos_personales->FindAll($this->SQLCA()." and cod_tipo_nomina=".$var);
				$this->set('grilla',$grilla);
				echo'<script>';
					  echo"document.getElementById('modi').disabled =false; ";
				echo'</script>';
			}else{
				$this->set('datos',null);
				echo'<script>';
					  echo"document.getElementById('modi').disabled ='disabled'; ";
				echo'</script>';
			}//fin datos
	}else{
		$this->set('datos',null);
	}
}//fin grilla



function guardar_items(){
	$this->layout="ajax";
    $cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_nomina'];
    $cod_tipo_transaccion = 1;
    $cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_trans'];
	$cod_cargo = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['select_cargo'];
	$cod_ficha =$this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['select_ficha'];
//	$cantidad = $this->porcentaje_5_2($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cantidad1']);
	$cantidad = $this->Formato1($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cantidad1']);
	    if($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cantidad1']!=""){
	    	$prueba=$this->cnmd10_individual_porcentaje_horas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,null);
	    		if($prueba){
	    			$prueba1=$this->cnmd10_individual_porcentaje_horas_cantidad->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion." and cod_cargo=".$cod_cargo." and cod_ficha=".$cod_ficha,null,null);
	    			if(!$prueba1){
	    				$sql_insert2 = "INSERT INTO cnmd10_individual_porcentaje_horas_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_cargo', '$cod_ficha','$cod_tipo_transaccion', '$cod_transaccion', '$cantidad')";
						$sw = $this->cnmd10_individual_porcentaje_horas_cantidad->execute($sql_insert2);
						if($sw>1){
							$datos = $this->cnmd10_individual_porcentaje_horas_cantidad->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,'cod_ficha ASC');
							$this->set("datos",$datos);
							$grilla=$this->v_cnmd06_fichas_datos_personales->FindAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
							$this->set('grilla',$grilla);
							echo "<script>";
								echo "document.getElementById('agregar').disabled='disabled';";
								echo "document.getElementById('cantidad').value='';";
							echo "</script>";
						}else{
							echo "no se pudo guardar";
						}//sw
	    			}//prueba1

	    		}//fin prueba
	    }else{
	    	echo "ingrese cantidad";
	    }
}//fin guardar_items



function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_nomina'];
    $cod_tipo_transaccion = 1;
    $cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_trans'];
//    $porcentaje=$this->porcentaje_5_2($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['porcentaje']);
    $porcentaje=$this->Formato1($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['porcentaje']);
	$cod_frecuencia = $this->Session->read('frecuencia');
	$cod_condicion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['condicion'];
	$cod_cargo = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['select_cargo'];
	$cod_ficha =$this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['select_ficha'];
//	$cantidad = $this->porcentaje_5_2($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cantidad1']);
	$cantidad = $this->Formato1($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cantidad1']);
    $prueba=$this->cnmd10_individual_porcentaje_horas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,null);

		if(!$prueba){
			if($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cantidad1']==0){
				$this->set('errorMessage', 'INGRESE UNA CANTIDAD VALIDA');
			}else{
			  $cod_presi = $this->Session->read('SScodpresi');
			  $cod_entidad = $this->Session->read('SScodentidad');
			  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			  $cod_inst = $this->Session->read('SScodinst');
			  $cod_dep = $this->Session->read('SScoddep');
			  $ubicacion_escenario = strtoupper('ASIGNACION INDIVIDUAL EN PORCENTAJE SEGUN CANTIDAD  HORAS TRABAJADAS');
			  if(!empty($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas'])){
					if(empty($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['tipo_trans'])){
						$codi_tipo_transaccion = 0;
					}else{
						$codi_tipo_transaccion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['tipo_trans'];
					}

					if($cod_condicion==1){
						$codi_transaccion = 0;
					}else{
						if(empty($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['select4'])){
							 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
						}else{
							 $codi_transaccion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['select4'];
						}//empty
					}//fin cod_condicion


					if(empty($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['escenario'])){
						$activar_frecuencia_eventual = 2;
					}else{
						$activar_frecuencia_eventual = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['escenario'];
					}//fin empty

					$sql_insert = "INSERT INTO cnmd10_individual_porcentaje_horas VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$porcentaje','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
					$sw = $this->cnmd10_individual_porcentaje_horas->execute($sql_insert);
					if($sw>1){
						$sql_insert2 = "INSERT INTO cnmd10_individual_porcentaje_horas_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_cargo', '$cod_ficha','$cod_tipo_transaccion', '$cod_transaccion', '$cantidad')";
						$sw1 = $this->cnmd10_individual_porcentaje_horas_cantidad->execute($sql_insert2);
						if($sw1 > 1){
							$this->set('Message_existe', 'EL ESCENARIO FUE GUARDADO CON EXITO');
							$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion', '$ubicacion_escenario')";
							$sw = $this->cnmd10_individual_porcentaje_horas_cantidad->execute($sql_control);
								echo "<script>";
									echo "document.getElementById('cedula').value='';";
									echo "document.getElementById('primer_ape').value='';";
									echo "document.getElementById('segudo_ape').value='';";
									echo "document.getElementById('primer_nombre').value='';";
									echo "document.getElementById('segundo_nombre').value='';";
									echo "document.getElementById('cantidad').value='';";
									echo "document.getElementById('save').disabled='disabled';";
									echo"document.getElementById('modi').disabled =false; ";
								echo "</script>";
						}else{
							$this->set('errorMessage',' POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
						}//fin sw1
					}//fin sw

			}else{
				$this->set('errorMessage',' POR FAVOR COMPLETE TODOS LOS CAMPOS');
			}//FIN ELSE VALIDA MONTO CERO
		}//FIN PRUEBA
	}else{
		$this->set('errorMessage',' ESTA TRANSACCION YA EXISTE REGISTRADO');
	}
	 $datos=$this->cnmd10_individual_porcentaje_horas_cantidad->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,null);
	 if($datos){
	 	 $this->set('datos',$datos);
	 	 $grilla=$this->v_cnmd06_fichas_datos_personales->FindAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		 $this->set('grilla',$grilla);
	 }else{
	 	 $this->set('datos','');
	 }

	 $this->Session->delete('frecuencia');

}//FIN GUARDAR


function modificar(){
	$this->layout="ajax";
	$cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_nomina'];
	$cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_trans'];
//	$cod_frecuencia = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['frecuencia'];
	$cod_condicion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['condicion'];
	$porcentaje = $this->porcentaje_5_2($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['porcentaje']);
	$a=0;
	if($porcentaje==0){
		$this->set('errorMessage', 'Debe ingresar un porcentaje valido');
		$a=1;
	}

	if(empty($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['tipo_trans'])){
		$codi_tipo_transaccion = 0;
	}else{
		$codi_tipo_transaccion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['tipo_trans'];
	}

		if($cod_condicion==1){
			$codi_transaccion = 0;
		}else{
			if(empty($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['select4'])){
				 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
				 $a=1;
			}else{
				 $codi_transaccion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['select4'];
				 $cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$codi_tipo_transaccion, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
				$this->concatenaN($cnmd03, 'transaccion');
				$deno = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions ="cod_tipo_transaccion=".$codi_tipo_transaccion." and cod_transaccion=".$codi_transaccion, $order =null);
				$this->set('denominacion',$deno);
			}

		}


			if(empty($this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['escenario'])){
				$activar_frecuencia_eventual = 2;
			}else{
				$activar_frecuencia_eventual = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['escenario'];
			}

			if($a!=1){
				$this->cnmd10_individual_porcentaje_horas->execute("update cnmd10_individual_porcentaje_horas set cod_condicion=".$cod_condicion.",codi_tipo_transaccion=".$codi_tipo_transaccion.",codi_transaccion=".$codi_transaccion.",activar_frecuencia_eventual=".$activar_frecuencia_eventual.",porcentaje=".$porcentaje." where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion);///modifica el monto
		   		 $this->set('Message_existe', 'El registro se ha modificado');
			}

			$datos=$this->cnmd10_individual_porcentaje_horas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
}//fin modificar




function eliminar($cod_nomina=null, $cod_trans=null,$cargo=null,$ficha=null){
	$this->layout="ajax";

		if(isset($cod_nomina) && isset($cod_trans) && isset($cargo) && isset($ficha)){
			$sql_eliminar2="DELETE FROM cnmd10_individual_porcentaje_horas_cantidad WHERE ".$this->SQLCA()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans' and cod_cargo='$cargo' and cod_ficha='$ficha'";
			$sw2 = $this->cnmd10_individual_porcentaje_horas_cantidad->execute($sql_eliminar2);
			if($sw2 > 1){
				$this->set('Message_existe', 'LA TRANSACCION SE ELIMINO CON EXITO');
			}else{
				$this->set('errorMessage', 'NO SE PUDO ELIMINAR');
			}
		}else{
			$cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_nomina'];
		    $cod_tipo_transaccion = 1;
		    $cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_trans'];

			 $verificar=$this->cnmd10_individual_porcentaje_horas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
			if($verificar!=null){
				if(($cod_tipo_nomina!="") && ($cod_transaccion!="")){
					$sql_eliminar="DELETE FROM cnmd10_individual_porcentaje_horas WHERE ".$this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'";
					$sw = $this->cnmd10_individual_porcentaje_horas->execute($sql_eliminar);
					if($sw>1){
						$sql_del_control = "DELETE FROM cnmd10_control_de_escenarios WHERE ".$this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'";
						$sw = $this->cnmd10_individual_porcentaje_horas->execute($sql_del_control);
						$this->set('Message_existe', 'EL ESCENARIO FUE ELIMINADO CON EXITO');
						$this->index();
						$this->render('index');
						echo "<script>";
							echo "document.getElementById('porcentaje').value='';";
							echo "document.getElementById('cantidad').value='';";
							echo "document.getElementById('deno_nomina').value='';";
							echo "document.getElementById('cod_nomina').value='';";
							echo "document.getElementById('cedula').value='';";
							echo "document.getElementById('primer_ape').value='';";
							echo "document.getElementById('segudo_ape').value='';";
							echo "document.getElementById('primer_nombre').value='';";
							echo "document.getElementById('segundo_nombre').value='';";
						echo "</script>";
					}else{
						$this->set('errorMessage', 'NO SE PUDO ELIMINAR');
					}
				}else{
					$this->set('errorMessage', 'debe seleccionar lo datos requeridos para realizar esta operacion');
				}
			}else{
				$this->set('errorMessage','el registro que desea eliminar no existe');
				$this->index();
				$this->render('index');
		}// verifica

		$this->data=null;
		}//fin isset



}//FIN ELIMINAR




function cod_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_trans', $cod_nomina);
		$cod_trans=$this->Session->read('trans');
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
		$carga=$this->cnmd10_individual_porcentaje_horas->findAll($this->condicion()." and cod_tipo_nomina=".$var1." and cod_tipo_transaccion=1 and cod_transaccion=".$var2);
		if($carga){
			$lista2 = $this->v_cnmd05->generateList($conditions = $this->condicion()." and cod_tipo_nomina!=".$var1, $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
			if($lista2){
			$this->concatenaN($lista2, 'transferir');
			$this->set('cod_nomina', $var1);
			}else{
				$this->set('transferir',array());
			}
		}else{
			$this->set('nada',"");
		}

}//fin transferir




function guardar_transferir(){
	$this->layout="ajax";

	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');

	  $cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_nomina'];
	  $cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_trans'];
	  $cod_transferir = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_transferir'];
	  $cod_tipo_transaccion=1;

	  $ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_transferir' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'", $order =null);
	  if(!$ubicacion){
	  $data=$this->cnmd10_individual_porcentaje_horas->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
	  $data2=$this->cnmd10_individual_porcentaje_horas_cantidad->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
	  $ubicacion_escenario = strtoupper('ASIGNACION INDIVIDUAL EN PORCENTAJE SEGUN CANTIDAD  HORAS TRABAJADAS');
	  foreach($data as $row){
			$cod_frecuencia = $row['cnmd10_individual_porcentaje_horas']['cod_frecuencia'];
			$cod_condicion = $row['cnmd10_individual_porcentaje_horas']['cod_condicion'];
			$codi_tipo_transaccion = $row['cnmd10_individual_porcentaje_horas']['codi_tipo_transaccion'];
			$codi_transaccion = $row['cnmd10_individual_porcentaje_horas']['codi_transaccion'];
			$activar_frecuencia_eventual = $row['cnmd10_individual_porcentaje_horas']['activar_frecuencia_eventual'];
			$porcentaje = $row['cnmd10_individual_porcentaje_horas']['porcentaje'];
			$sql_insert = "INSERT INTO cnmd10_individual_porcentaje_horas VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion','$porcentaje','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
			$sw = $this->cnmd10_individual_porcentaje_horas->execute($sql_insert);
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
						$cod_cargo = $row['cnmd10_individual_porcentaje_horas_cantidad']['cod_cargo'];
						$cod_ficha = $row['cnmd10_individual_porcentaje_horas_cantidad']['cod_ficha'];
						$cantidad = $row['cnmd10_individual_porcentaje_horas_cantidad']['cantidad'];
						$sql_insert2 = "INSERT INTO cnmd10_individual_porcentaje_horas_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_cargo', '$cod_ficha','$cod_tipo_transaccion', '$cod_transaccion', '$cantidad')";
						$sw2 = $this->cnmd10_individual_porcentaje_horas_cantidad->execute($sql_insert2);
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

}// fin guardar_transferir



function grilla_vacia(){
	$this->layout="ajax";
}// fin grilla vacia



function buscar_ficha($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin buscar_ficha



function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
 $nomina=$this->Session->read('nomina');
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($this->SQLCA()." and cod_tipo_nomina='$nomina' and quitar_acentos(super_busqueda) LIKE quitar_acentos('%".$var2."%')");//
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($this->SQLCA()." and cod_tipo_nomina='$nomina' and quitar_acentos(super_busqueda) LIKE quitar_acentos('%".$var2."%')",null,"cod_ficha ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($this->SQLCA()." and cod_tipo_nomina='$nomina' and quitar_acentos(super_busqueda) LIKE quitar_acentos('%".$var22."%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($this->SQLCA()." and cod_tipo_nomina='$nomina' and quitar_acentos(super_busqueda) LIKE quitar_acentos('%".$var22."%')",null,"cod_ficha ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function bt_nav($Tfilas,$pagina){
    if($Tfilas==1){
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
          	}else if($Tfilas==2){
          		if($pagina==2){
                   $this->set('mostrarS',false);
                   $this->set('mostrarA',true);
          		}else{
          		   $this->set('mostrarS',true);
                   $this->set('mostrarA',false);
          		}
          	}else if($Tfilas>=3){
          		if($pagina==$Tfilas){
                     $this->set('mostrarS',false);
                     $this->set('mostrarA',true);
          		}else if($pagina==1){
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',false);
          		}else{
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',true);
          		}
          	}
 }//fin navegacion



function seleccion_busqueda($opcion=null,$nomina=null,$cargo=null,$ficha=null){
	$this->layout="ajax";

	$cond=$this->SQLCA()." and cod_tipo_nomina=".$nomina;
	$lista = $this->v_cnmd05->generateList($conditions = $cond, $order = 'cod_cargo', $limit = null, '{n}.v_cnmd05.cod_cargo', '{n}.v_cnmd05.denominacion_clase');
	if($lista){
		$this->concatenaN($lista, 'cargo');
	}else{
		$this->set('vacio','');
	}

	$cond=$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_cargo=".$cargo;
	$lista1 = $this->cnmd06_fichas->generateList($conditions = $cond, $order = 'cod_ficha', $limit = null, '{n}.cnmd06_fichas.cod_ficha', '{n}.cnmd06_fichas.cedula_identidad');
	if($lista){
		$this->concatena($lista1, 'ficha');
	}else{
		$this->set('vacio','');
	}

	$dato=$this->v_cnmd06_fichas_datos_personales->execute("select * from v_cnmd06_fichas_datos_personales where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_cargo=".$cargo." and cod_ficha=".$ficha);
	$this->set('dato',$dato);

	$cod_trans=$this->Session->read('trans');

	$veri=$this->cnmd10_individual_porcentaje_horas->FindCount($this->condicion()." and cod_tipo_nomina=".$nomina." and cod_transaccion=".$cod_trans);
	if($veri==0){
		$disabled='disabled';
	}else{
		$datos=$this->cnmd10_individual_porcentaje_horas_cantidad->FindCount($this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_transaccion=".$cod_trans." and cod_cargo=".$cargo." and cod_ficha=".$ficha,null,null);
		if($datos==0){
			$disabled='';
		}else{
			$disabled='disabled';
		}
	}
	$this->set('disabled',$disabled);
}// fin seleccion_busqueda


function transaccion($var=null){
	$this->layout="ajax";
	if($var!='guarda'){
		$nomina=$var;
	}else{
		 $nomina = $this->data['cnmp10_asignacion_porcentaje_horas_trabajadas']['cod_nomina'];
	}
	$datos=$this->cnmd10_individual_porcentaje_horas->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_tipo_nomina=".$nomina,null,null);
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