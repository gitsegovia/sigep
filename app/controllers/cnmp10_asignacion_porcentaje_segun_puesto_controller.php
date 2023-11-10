<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp10AsignacionPorcentajeSegunPuestoController extends AppController {
   var $name = 'cnmp10_asignacion_porcentaje_segun_puesto';
   var $uses = array('Cnmd01',  'cnmd03_transacciones', 'cnmd10_control_escenarios','cnmd10_comunes_puestos_porcentaje_asig','cnmd10_comunes_puestos_porcentaje_asig_2','Cnmd02_empleados_puestos','Cnmd02_obreros_puestos','cnmd02_varios_puestos','v_cnmd05');
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
	$this->Session->delete('nomina');
 	$this->Session->delete('clasi_personal');
 	$this->Session->delete('transaccion');
	if(isset($cod)){
		echo $cod."   ";
	}
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');

	$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');

	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled='disabled';";
	echo "</script>";
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
	    $clasificacion = $this->Cnmd01->field('clasificacion_personal', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
	 	$this->Session->delete('nomina');
	 	$this->Session->write('nomina',$cod_nomina);
	 	$this->Session->delete('clasi_personal');
	 	$this->Session->write('clasi_personal',$clasificacion);
	}
	echo "<script>";
		echo "document.getElementById('input_tag').value='';";
		echo "document.getElementById('input_tag').disabled=false;";
		echo "document.getElementById('cod_puesto').value='';";
		echo "document.getElementById('deno_puesto').value='';";
		echo "document.getElementById('transferencia').innerHTML='';";
		echo"document.getElementById('modi').disabled ='disabled'; ";
		echo"document.getElementById('select_5').innerHTML ='<select></select>'; ";
		echo"document.getElementById('codi_trans1').value =''; ";
		echo"document.getElementById('denomi_trans1').value =''; ";
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
		$this->Session->delete('transaccion');
	 	$this->Session->write('transaccion',$cod_trans);
	}
	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled=false;";
		echo "document.getElementById('input_tag').disabled=false;";
		echo "document.getElementById('input_tag').value='';";
		echo "document.getElementById('cod_puesto').value='';";
		echo "document.getElementById('deno_puesto').value='';";
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
}//fin codi_trans




function denomi_trans($cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$cod_trans' and cod_tipo_transaccion=".$this->Session->read('codi_tipo'), $order ="cod_transaccion ASC");
		$this->set('deno_trans', $deno_trans);
	}
}



function cod_puesto($cod=null){
	$this->layout="ajax";
	$x1=$this->Session->read('nomina');
	$x2=$this->Session->read('transaccion');
	echo "<script>";
		echo "document.getElementById('monto').value='';";
	echo "</script>";
	if($cod!=null){
		$this->set('cod_puesto', $cod);
		if(!isset($_SESSION['transaccion'])){
			echo "<script>";
				echo "document.getElementById('save_grilla').disabled='disabled';";
				echo "document.getElementById('monto').disabled='disabled'";
			echo "</script>";
		}else{
			$datos1 = $this->cnmd10_comunes_puestos_porcentaje_asig->findAll($this->SQLCA()." and cod_tipo_nomina=".$x1." and cod_tipo_transaccion=1 and cod_transaccion=".$x2,null,' cod_tipo_nomina ASC');
			$datos = $this->cnmd10_comunes_puestos_porcentaje_asig_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$x1." and cod_tipo_transaccion=1 and cod_transaccion=".$x2." and cod_puesto=".$cod,null,' cod_puesto ASC');

			if($datos1 && $datos){
				echo "<script>";
					echo "document.getElementById('save_grilla').disabled='disabled';";
					echo "document.getElementById('monto').disabled='disabled'";
				echo "</script>";
			}else if($datos1 && !$datos){
				echo "<script>";
					echo "document.getElementById('save_grilla').disabled=false;";
					echo "document.getElementById('monto').disabled=false;";
				echo "</script>";
			}else if(!$datos){
				echo "<script>";
					echo "document.getElementById('save_grilla').disabled='disabled';";
				echo "</script>";
			}
		}
	}

}//fin cod_puesto



function deno_puesto($tabla=null,$var=null){
	$this->layout="ajax";
	$nomina=$this->Session->read('nomina');
	$deno_puesto = $this->v_cnmd05->field('denominacion_clase',$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_puesto=".$var);
	$this->set('deno_puesto', $deno_puesto);

}// deno_puesto



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



function transferir($var2=null){
	$this->layout="ajax";
		 $var1=$this->Session->read('nomina');
		 $var2=$this->Session->read('transaccion');
		 $cod=$this->Session->read('clasi_personal');
		$carga=$this->cnmd10_comunes_puestos_porcentaje_asig_2->findAll($this->condicion()." and cod_tipo_nomina=".$var1." and cod_tipo_transaccion=1 and cod_transaccion=".$var2);
		if($carga){
			$lista2 = $this->Cnmd01->generateList($conditions = $this->condicion()." and cod_tipo_nomina!=".$var1." and clasificacion_personal=".$cod, $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
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
	$datos=$this->cnmd10_comunes_puestos_porcentaje_asig->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_transaccion=".$var2." and cod_tipo_nomina!=".$var1,null,null);
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

	  $cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_nomina'];
	  $cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_trans'];
	  $cod_transferir = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_transferir'];
	  $cod_tipo_transaccion=1;

	  $ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_transferir' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'", $order =null);
	  if(!$ubicacion){
	  $data=$this->cnmd10_comunes_puestos_porcentaje_asig->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
	  $data2=$this->cnmd10_comunes_puestos_porcentaje_asig_2->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
	  $ubicacion_escenario = strtoupper('Asignaciones Comunes - en Porcentaje según el puesto que ocupa');
	  foreach($data as $row){
			$cod_frecuencia = $row['cnmd10_comunes_puestos_porcentaje_asig']['cod_frecuencia'];
			$cod_condicion = $row['cnmd10_comunes_puestos_porcentaje_asig']['cod_condicion'];
			$codi_tipo_transaccion = $row['cnmd10_comunes_puestos_porcentaje_asig']['codi_tipo_transaccion'];
			$codi_transaccion = $row['cnmd10_comunes_puestos_porcentaje_asig']['codi_transaccion'];
			$activar_frecuencia_eventual = $row['cnmd10_comunes_puestos_porcentaje_asig']['activar_frecuencia_even'];
			$sql_insert = "INSERT INTO cnmd10_comunes_puestos_porcentaje_asig VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
			$sw = $this->cnmd10_comunes_puestos_porcentaje_asig->execute($sql_insert);
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
						$cod_puesto = $row['cnmd10_comunes_puestos_porcentaje_asig_2']['cod_puesto'];
						$monto = $row['cnmd10_comunes_puestos_porcentaje_asig_2']['porcentaje'];
						$sql_insert2 = "INSERT INTO cnmd10_comunes_puestos_porcentaje_asig_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion','$cod_puesto', '$monto')";
						$sw = $this->cnmd10_comunes_puestos_porcentaje_asig_2->execute($sql_insert2);
					}// fin foreach data2
				}//fin $sw1
			}//fin $sw
	  }//fin foreach data
}else{//fin ubicacion
	$this->set('Message_error', 'Transferencia sin exito intente nuevamente');
}

//////////////////////////////AGREGAR DESDE AQUI////////////////////

	$datos=$this->cnmd10_comunes_puestos_porcentaje_asig->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion." and cod_tipo_nomina!=".$cod_tipo_nomina,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->Cnmd01->findAll($this->SQLCA(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);

}// fin guardar_transferir



function verifica($cod_nomina=null, $cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null && $cod_trans!=null && $cod_trans!=""){
		$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans'", $order =null);
		$this->set('ubicacion', $ubicacion);
		$veri=$this->cnmd10_comunes_puestos_porcentaje_asig->findAll($this->condicion()." and cod_tipo_nomina=".$cod_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_trans);
		$this->set('opciones',$veri);
		if($veri){
			$v=$this->cnmd10_comunes_puestos_porcentaje_asig->execute("SELECT * FROM cnmd10_comunes_puestos_porcentaje_asig WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_trans);
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


function grilla_vacia(){
	$this->layout="ajax";
}//fin grilla vacia


function grilla($var=null,$var2=null){
	$this->layout="ajax";
	  $cod1=$this->Session->read('clasi_personal');
	if($var2!=""){
		$cond= $this->SQLCA();
		$datos = $this->cnmd10_comunes_puestos_porcentaje_asig_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$var." and cod_tipo_transaccion=1 and cod_transaccion=".$var2,null,' cod_puesto ASC');
		if($datos){
			$this->set("datos",$datos);
			echo "<script>";
				echo "document.getElementById('save_grilla').disabled=false;";
				echo"document.getElementById('modi').disabled =false; ";
			echo "</script>";
		}else{
			echo "<script>";
				echo "document.getElementById('save_grilla').disabled='disabled';";
				echo"document.getElementById('modi').disabled ='disabled'; ";
			echo "</script>";
		}

		if($cod1==1){
			 $table="Cnmd02_empleados_puestos";
			$this->set("campo","denominacion_clase");
		}else if($cod1==2){
			 $table="Cnmd02_obreros_puestos";
			$this->set("campo","titulo_puesto");
		}else{
			 $table="cnmd02_varios_puestos";
			$this->set("campo","denominacion_clase");
		}
		$this->set("tabla",$table);
		$datos1 = $this->$table->findAll();
		$this->set("deno_puesto",$datos1);
	}else{
		$this->grilla_vacia();
	}

}//fin grilla



function select_puesto($var=null){
	$this->layout="ajax";
     $cod=$this->Session->read('clasi_personal');
      $nomina=$this->Session->read('nomina');
    echo "<script>";
		echo "document.getElementById('cod_puesto').value='';";
		echo "document.getElementById('deno_puesto').value='';";
	echo "</script>";

	$cod2 = strtoupper($var);
    $catalogo = $this->v_cnmd05->generateList($this->SQLCA()." and cod_tipo_nomina=".$nomina." and upper(denominacion_clase) LIKE '%$cod2%'", $order = null, $limit = null, '{n}.v_cnmd05.cod_puesto', '{n}.v_cnmd05.denominacion_clase');
	if(!empty($catalogo)){
 		$this->concatena($catalogo,'puesto');
 	}else{
 		$this->concatena(array(),'puesto');
 		 $this->set('errorMessage', 'Sin exito! intente una nueva busqueda');
 	}

 	$this->set('tabla',"tabla");//cambie esta linea, dee ir dentro

}//fin agrega puesto



function guardar_items(){
	$this->layout="ajax";

	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');

	  $cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_nomina'];
	  $cod_tipo_transaccion = 1;
	  $cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_trans'];
      $monto=$this->porcentaje_5_2($this->data['cnmp10_asignacion_porcentaje_segun_puesto']['monto']);
	  $cod_puesto=$this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_puesto'];
	    	$sql_insert2 = "INSERT INTO cnmd10_comunes_puestos_porcentaje_asig_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$cod_puesto', '$monto')";
			$sw = $this->cnmd10_comunes_puestos_porcentaje_asig_2->execute($sql_insert2);
			if($sw>1){
				$datos = $this->cnmd10_comunes_puestos_porcentaje_asig_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion,null,'cod_puesto ASC');
				$this->set("datos",$datos);
				$cod1=$this->Session->read('clasi_personal');
				if($cod1==1){
					$table="Cnmd02_empleados_puestos";
					$this->set("campo","denominacion_clase");
				}else if($cod1==2){
					$table="Cnmd02_obreros_puestos";
					$this->set("campo","titulo_puesto");
				}else{
					$table="cnmd02_varios_puestos";
					$this->set("campo","denominacion_clase");
				}
				$this->set("tabla",$table);
				$datos1 = $this->$table->findAll();
				$this->set("deno_puesto",$datos1);
				echo "<script>";
					echo "document.getElementById('monto').value='';";
					echo "document.getElementById('cod_puesto').value='';";
					echo "document.getElementById('deno_puesto').value='';";
				echo "</script>";
			}else{
				echo "no se pudo guardar";
			}
}//fin guardar_items



function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_nomina'];
    $cod_tipo_transaccion = 1;
    $cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_trans'];
	$monto=$this->porcentaje_5_2($this->data['cnmp10_asignacion_porcentaje_segun_puesto']['monto']);
	$cod_frecuencia =$this->Session->read('frecuencia');
	$cod_condicion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['condicion'];
	$cod_puesto = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_puesto'];
    $ubicacion_escenario = strtoupper('Asignaciones Comunes - en Porcentaje según el puesto que ocupa');
	$prueba=$this->cnmd10_comunes_puestos_porcentaje_asig->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,null);

	if(!$prueba){
		if($this->data['cnmp10_asignacion_porcentaje_segun_puesto']['monto']==0){
		$this->set('errorMessage', 'Debe ingresar un porcentaje valido');
				echo'<script>';
				  	echo"document.getElementById('select_1').options[0].selected=true; ";
				    echo"document.getElementById('cod_nomina').value = ''; ";
				  	echo"document.getElementById('deno_nomina').value = ''; ";
				    echo"document.getElementById('cod_trans').value = ''; ";
				    echo"document.getElementById('monto').value = ''; ";
				    echo "document.getElementById('save').disabled=false;";
				echo'</script>';
				return;
	}else{
		if  (!empty($this->data['cnmp10_asignacion_porcentaje_segun_puesto'])){

			if(empty($this->data['cnmp10_asignacion_porcentaje_segun_puesto']['tipo_trans'])){
				$codi_tipo_transaccion = 0;
			}else{
				$codi_tipo_transaccion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['tipo_trans'];
			}

		if($cod_condicion==1){
			$codi_transaccion = 0;
		}else{
			if(empty($this->data['cnmp10_asignacion_porcentaje_segun_puesto']['select4'])){
				 $this->set('errorMessage', 'Debe seleccionar el código de transacción');
				  echo "<script>";
					echo "document.getElementById('save').disabled=false;";
				 echo "</script>";
				 return;
			}else{
				 $codi_transaccion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['select4'];
			}

		}

			if(empty($this->data['cnmp10_asignacion_porcentaje_segun_puesto']['escenario'])){
				$activar_frecuencia_eventual = 2;
			}else{
				$activar_frecuencia_eventual = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['escenario'];
			}

			$sql_insert = "BEGIN;INSERT INTO cnmd10_comunes_puestos_porcentaje_asig VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
			$sw = $this->cnmd10_comunes_puestos_porcentaje_asig->execute($sql_insert);
			if($sw > 1){
				$sql_insert2 = "INSERT INTO cnmd10_comunes_puestos_porcentaje_asig_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$cod_puesto','$monto')";
				$sw1 = $this->cnmd10_comunes_puestos_porcentaje_asig_2->execute($sql_insert2);
				if($sw1 > 1){
					$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion', '$ubicacion_escenario')";
					$sw2 = $this->cnmd10_control_escenarios->execute($sql_control);
					if($sw2 > 1){
						$this->set('Message_existe', 'EL ESCENARIO FUE GUARDADO CON EXITO');
						echo'<script>';
							  echo"document.getElementById('modi').disabled =false; ";
						echo'</script>';
					}else{
						$this->cnmd10_control_escenarios->execute("ROLLBACK");
						$this->set('errorMessage',' POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
					}
				}else{
					$this->cnmd10_comunes_puestos_porcentaje_asig_2->execute("ROLLBACK");
					$this->set('errorMessage',' POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
				}
			}else{
				$this->cnmd10_comunes_puestos_porcentaje_asig->execute("ROLLBACK");
				$this->set('errorMessage',' POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
			}
			$this->cnmd10_comunes_puestos_porcentaje_asig->execute("COMMIT");
		}else{
		}
		$this->data['cnmp10_asignacion_porcentaje_segun_puesto'] = array();
		}//FIN ELSE VALIDA MONTO CERO
	}else{
			$this->set('errorMessage','Este registro ya existe');
	}
$datos=$this->cnmd10_comunes_puestos_porcentaje_asig_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion,null,null);
	 if($datos){
	 	 $this->set('datos',$datos);
	 }else{
	 	 $this->set('datos','');
	 }

	 $cod1=$this->Session->read('clasi_personal');
	 if($cod1==1){
		$table="Cnmd02_empleados_puestos";
		$this->set("campo","denominacion_clase");
	 }else if($cod1==2){
		$table="Cnmd02_obreros_puestos";
		$this->set("campo","titulo_puesto");
	 }else{
		$table="cnmd02_varios_puestos";
		$this->set("campo","denominacion_clase");
	 }
	$this->set("tabla",$table);
	$datos1 = $this->$table->findAll();
	$this->set("deno_puesto",$datos1);

	$this->Session->delete('frecuencia');

}//FIN GUARDAR



function modificar(){
	$this->layout="ajax";
	$cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_nomina'];
	$cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_trans'];
//	$cod_frecuencia = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['frecuencia'];
	$cod_condicion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['condicion'];
	$a=0;

	if(empty($this->data['cnmp10_asignacion_porcentaje_segun_puesto']['tipo_trans'])){
		$codi_tipo_transaccion = 0;
	}else{
		$codi_tipo_transaccion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['tipo_trans'];
	}

		if($cod_condicion==1){
			$codi_transaccion = 0;
		}else{
			if(empty($this->data['cnmp10_asignacion_porcentaje_segun_puesto']['select4'])){
				 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
				 $a=1;
			}else{
				 $codi_transaccion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['select4'];
				 $cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$codi_tipo_transaccion, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
				$this->concatenaN($cnmd03, 'transaccion');
				$deno = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions ="cod_tipo_transaccion=".$codi_tipo_transaccion." and cod_transaccion=".$codi_transaccion, $order =null);
				$this->set('denominacion',$deno);
			}

		}

			if(empty($this->data['cnmp10_asignacion_porcentaje_segun_puesto']['escenario'])){
				$activar_frecuencia_eventual = 2;
			}else{
				$activar_frecuencia_eventual = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['escenario'];
			}

			if($a!=1){
				$this->cnmd10_comunes_puestos_porcentaje_asig->execute("update cnmd10_comunes_puestos_porcentaje_asig set cod_condicion=".$cod_condicion.",codi_tipo_transaccion=".$codi_tipo_transaccion.",codi_transaccion=".$codi_transaccion.",activar_frecuencia_eventual=".$activar_frecuencia_eventual." where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion);///modifica el monto
		   		 $this->set('Message_existe', 'El registro se ha modificado');
			}

			$datos=$this->cnmd10_comunes_puestos_porcentaje_asig->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
}//fin modificar


function eliminar($cod_nomina=null, $cod_trans=null){
	$this->layout="ajax";
		$cod_tipo_nomina = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_nomina'];
	    $cod_tipo_transaccion = 1;
	    $cod_transaccion = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_trans'];

	    $verificar=$this->cnmd10_comunes_puestos_porcentaje_asig_2->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
		if($verificar!=null){
			if(($cod_tipo_nomina!="") && ($cod_transaccion!="")){
				$sql_eliminar="DELETE FROM cnmd10_comunes_puestos_porcentaje_asig WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'";
				$sw = $this->cnmd10_comunes_puestos_porcentaje_asig->execute($sql_eliminar);
				$sql_eliminar2="DELETE FROM cnmd10_comunes_puestos_porcentaje_asig_2 WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'";
				$sw2 = $this->cnmd10_comunes_puestos_porcentaje_asig_2->execute($sql_eliminar2);
				if($sw>1){

					$sql_del_control = "DELETE FROM cnmd10_control_de_escenarios WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'";
					$sw = $this->cnmd10_comunes_puestos_porcentaje_asig_2->execute($sql_del_control);
					$this->set('Message_existe', 'EL ESCENARIO FUE ELIMINADO CON EXITO');
					echo "<script>cnmp10_cancelacion_limpiar_eliminar2();</script>";
					$this->index();
					$this->render('index');
				}
			}else{
				echo "";
			}
		}else{
			echo'<script>';
			   echo"document.getElementById('cod_nomina').value = ''; ";
			   echo"document.getElementById('deno_nomina').value = ''; ";
			echo'</script>';
			$this->set('errorMessage','el registro que desea eliminar no existe');
			$this->index();
			$this->render('index');
		}

}//FIN ELIMINAR



function FormatoJ1($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-3,1)=='.') {
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
    	   $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,2,'.','');
    }

function FormatoDEC2($monto){
	$var=$this->FormatoJ1($this->Formato2($monto));
	return $var;
}



function transaccion($var=null){
	$this->layout="ajax";
	if($var!='guarda'){
		$nomina=$var;
	}else{
		 $nomina = $this->data['cnmp10_asignacion_porcentaje_segun_puesto']['cod_nomina'];
	}
	$datos=$this->cnmd10_comunes_puestos_porcentaje_asig->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_tipo_nomina=".$nomina,null,null);
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