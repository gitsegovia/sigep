<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk Germán Aragol Hernández
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

class Cnmp10DeduccionPorcentajeSueldoController extends AppController {
   var $name = 'cnmp10_deduccion_porcentaje_sueldo';
   var $uses = array('Cnmd01',  'cnmd03_transacciones', 'cnmd10_control_escenarios','cnmd10_comunes_porcentaje_deduccion');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');
//'cnmd10_bolivares_asig',
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



function index(){
	$this->layout="ajax";
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');

}

function select_trans($opc=null,$cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina==null && $opc!=null){
		//BLOQUEO FUSAMIEBG
		$cod_dep = $this->Session->read('SScoddep');
		if($cod_dep!=1){
		  $conditions ="cod_tipo_transaccion=2 and cod_transaccion!=103";
		}else{
			$conditions ="cod_tipo_transaccion=2";
		}
		$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
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

	echo'<script>';
		  echo"document.getElementById('otra_nomina').innerHTML=''; ";
	echo'</script>';
}


function otras_nominas($var1=null,$var2=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$sql='';
	$datos=$this->cnmd10_comunes_porcentaje_deduccion->findAll($this->condicion()." and cod_tipo_transaccion=2 and cod_tipo_nomina=".$var1,null,null);
			 if($datos){
			 	foreach($datos as $x){
			 		$transaccion=$x['cnmd10_comunes_porcentaje_deduccion']['cod_transaccion'];
			 		if($sql==''){
			 			$sql.=$transaccion;
			 		}else{
			 			$sql.=",".$transaccion;
			 		}
			 	}
				$query="SELECT distinct a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.cod_tipo_nomina FROM cnmd10_comunes_porcentaje_deduccion a where a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.cod_tipo_transaccion=2 and cod_tipo_nomina!=".$var1." and a.cod_transaccion IN(".$sql.")";
				$opciones=$this->cnmd10_comunes_porcentaje_deduccion->execute($query);
				if($opciones){
					$this->set('opciones',$opciones);
				}else{
					 $this->set('opciones','');
				}
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->Cnmd01->findAll($this->condicion(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
 	$this->render('otras_nominas');
}//fin otras nominas

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

}

function deno_trans($cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$cod_trans' and cod_tipo_transaccion=2", $order ="cod_transaccion ASC");
		$this->set('deno_trans', $deno_trans);

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
	if($cod_trans!=null && $cod_trans!=null){
		$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=2 and cod_transaccion='$cod_trans'", $order =null);
		$this->set('ubicacion', $ubicacion);
	}

}

function datos($cod_nomina=null){
	$this->layout="ajax";

	if($cod_nomina != null){
		$deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=2', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
		$this->set('deno_trans', $deno_trans);
		$datos = $this->cnmd10_comunes_porcentaje_deduccion->findAll($conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina'", null, $order = 'cod_tipo_nomina, cod_transaccion', $limit = null, $page = null, $recursive = null);
		$this->set('datos', $datos);
		$this->set('cod_nomina', $cod_nomina);

	}
}


function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $cod_tipo_nomina = $this->data['cnmp10_comunes_bolivares_asig']['cod_nomina'];
    $cod_tipo_transaccion = 2;
    $cod_transaccion = $this->data['cnmp10_comunes_bolivares_asig']['cod_trans'];
    $cod_frecuencia =  $this->Session->read('frecuencia');
	$cod_condicion = $this->data['cnmp10_comunes_bolivares_asig']['condicion'];
	$monto=$this->porcentaje_5_2($this->data['cnmp10_comunes_bolivares_asig']['monto']);
	$tope = $this->Formato1($this->data['cnmp10_comunes_bolivares_asig']['monto_tope']);
    $ubicacion_escenario = strtoupper('DEDUCCIONES COMUNES - EN PORCENTAJE CALCULADO SEGÚN EL SUELDO');
	$prueba=$this->cnmd10_comunes_porcentaje_deduccion->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion,null,null);

	if(!$prueba){
		if($monto==0 || $tope==0){
			$this->set('errorMessage', 'Debe ingresar un monto');
			return;
		}else{
			if  (!empty($this->data['cnmp10_comunes_bolivares_asig'])){

				if(empty($this->data['cnmp10_comunes_bolivares_asig']['tipo_trans'])){
					$codi_tipo_transaccion = 0;
				}else{
					$codi_tipo_transaccion = $this->data['cnmp10_comunes_bolivares_asig']['tipo_trans'];
				}

			if($cod_condicion==1){
				$codi_transaccion = 0;
			}else{
				if(empty($this->data['cnmp10_comunes_bolivares_asig']['select4'])){
					 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
					 return;
				}else{
					 $codi_transaccion = $this->data['cnmp10_comunes_bolivares_asig']['select4'];
				}

			}

				if(empty($this->data['cnmp10_comunes_bolivares_asig']['escenario'])){
					$activar_frecuencia_eventual = 2;
				}else{
					$activar_frecuencia_eventual = $this->data['cnmp10_comunes_bolivares_asig']['escenario'];
				}
				$sql_insert = "INSERT INTO cnmd10_comunes_porcentaje_deduccion VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion', '$monto','$tope', '$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
				$sw = $this->cnmd10_comunes_porcentaje_deduccion->execute($sql_insert);
				if($sw > 1){
					$this->set('Message_existe', 'EL ESCENARIO FUE GUARDADO CON EXITO');
					$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion', '$ubicacion_escenario')";
					$sw = $this->cnmd10_comunes_porcentaje_deduccion->execute($sql_control);
					echo'<script>';
				 	     echo"document.getElementById('monto').value = ''; ";
				 	     echo"document.getElementById('tope').value = ''; ";
				 	     echo"document.getElementById('save').disabled=false; ";
					echo'</script>';
				}else{
					$this->set('errorMessage', 'POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
				}
			}else{

			}
		$this->data['cnmp10_comunes_bolivares_asig'] = array();
		}//FIN ELSE VALIDA MONTO CERO
	}else{
		$this->set('errorMessage', 'estos datos ya existen registrados');
	}

	$datos=$this->cnmd10_comunes_porcentaje_deduccion->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2",null,'order by  cod_transaccion asc');
	 if($datos){
	 	 $this->set('datos',$datos);
	 }else{
	 	 $this->set('datos','');
	 }
	 $deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=2', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);

	 $this->Session->delete('frecuencia');

}//FIN GUARDAR


function modificar(){
	$this->layout="ajax";

	$a=0;
	$cod_tipo_nomina = $this->data['cnmp10_comunes_bolivares_asig']['cod_nomina'];
	$cod_transaccion = $this->data['cnmp10_comunes_bolivares_asig']['cod_trans_grilla'];
 	$monto=$this->porcentaje_5_2($this->data['cnmp10_comunes_bolivares_asig']['monto']);
	$tope = $this->Formato1($this->data['cnmp10_comunes_bolivares_asig']['monto_tope']);
//	$cod_frecuencia = $this->data['cnmp10_comunes_bolivares_asig']['frecuencia'];
    $cod_condicion = $this->data['cnmp10_comunes_bolivares_asig']['condicion'];


	if(empty($this->data['cnmp10_comunes_bolivares_asig']['tipo_trans'])){
	 	$codi_tipo_transaccion = 0;
		}else{
	 	$codi_tipo_transaccion = $this->data['cnmp10_comunes_bolivares_asig']['tipo_trans'];
		}

	if($cod_condicion==1){
		$codi_transaccion = 0;
	}else{
		if(empty($this->data['cnmp10_comunes_bolivares_asig']['select4'])){
			 $this->set('errorMessage', 'Debe seleccionar el codigo de transaccion');
			 echo'<script>';
				 echo"document.getElementById('transaccion').value = ''; ";
				 echo"document.getElementById('denominacion').value = ''; ";
				 echo"document.getElementById('monto').value = ''; ";
				 echo"document.getElementById('tope').value = ''; ";
				 echo"document.getElementById('save').disabled='disabled'; ";
			 echo'</script>';
			$a=1;
		}else{
			 $codi_transaccion = $this->data['cnmp10_comunes_bolivares_asig']['select4'];
			 $cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$codi_tipo_transaccion, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
			 $this->concatenaN($cnmd03, 'transaccion');
		}
	}
		if(empty($this->data['cnmp10_comunes_bolivares_asig']['escenario'])){
			$activar_frecuencia_eventual = 2;
		}else{
			$activar_frecuencia_eventual = $this->data['cnmp10_comunes_bolivares_asig']['escenario'];
		}

	if($monto==0 || $tope==0){
		$this->set('errorMessage', 'POR FAVOR INGRESE datos validos');
		echo'<script>';
			  echo"document.getElementById('transaccion').value = ''; ";
			  echo"document.getElementById('denominacion').value = ''; ";
			  echo"document.getElementById('monto').value = ''; ";
			  echo"document.getElementById('tope').value = ''; ";
			  echo"document.getElementById('save').disabled='disabled'; ";
		echo'</script>';
	}else{
		if($a!=1){
			$this->cnmd10_comunes_porcentaje_deduccion->execute("update cnmd10_comunes_porcentaje_deduccion set porcentaje=".$monto.",tope=".$tope.",cod_condicion=".$cod_condicion.",codi_tipo_transaccion=".$codi_tipo_transaccion.",codi_transaccion=".$codi_transaccion.",activar_frecuencia_eventual=".$activar_frecuencia_eventual." where ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_transaccion=".$cod_transaccion);///modifica el monto
			$this->set('Message_existe', 'El registro se ha modificado');
			echo'<script>';
			   echo"document.getElementById('transaccion').value = ''; ";
			   echo"document.getElementById('denominacion').value = ''; ";
			   echo"document.getElementById('monto').value = ''; ";
			   echo"document.getElementById('tope').value = ''; ";
			   echo"document.getElementById('save').disabled='disabled'; ";
			echo'</script>';
		}
	}
	$datos=$this->cnmd10_comunes_porcentaje_deduccion->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2",null,null);
	 if($datos){
	 	 $this->set('datos',$datos);
	 }else{
	 	 $this->set('datos','');
	 }
	 $deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=2', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
}//FIN MODIFICAR



function eliminar($cod_nomina=null, $cod_trans=null){
	$this->layout="ajax";
	if($cod_nomina!=null && $cod_trans!=null){
		$sql_eliminar="DELETE FROM cnmd10_comunes_porcentaje_deduccion WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=2 and cod_transaccion='$cod_trans'";
		$sw = $this->cnmd10_comunes_porcentaje_deduccion->execute($sql_eliminar);
		if($sw>1){
			$sql_del_control = "DELETE FROM cnmd10_control_de_escenarios WHERE ".$this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=2 and cod_transaccion='$cod_trans'";
			$sw = $this->cnmd10_comunes_porcentaje_deduccion->execute($sql_del_control);
			$this->set('Message_existe', 'EL ESCENARIO FUE ELIMINADO CON EXITO');
		}
		$deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=2', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
		$this->set('deno_trans', $deno_trans);
		$datos = $this->cnmd10_comunes_porcentaje_deduccion->findAll($conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina'", null, $order = 'cod_tipo_nomina, cod_transaccion', $limit = null, $page = null, $recursive = null);
		$this->set('datos', $datos);
		$this->set('cod_nomina', $cod_nomina);
	}

}//FIN ELIMINAR

function monto($cod_nomina=null, $cod_trans=null){
	if($cod_nomina!=null && $cod_trans!=null){

		$monto = $this->cnmd10_bolivares_asig->field('cnmd10_bolivares_asig.monto', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_transaccion='$cod_trans'", $order ="cod_tipo_nomina, cod_transaccion ASC");
		$this->set('monto', $monto);

	}
}

function ver($cod_nomina=null, $cod_trans=null, $codi=null,$condicion=null){
	$this->layout="ajax";
	if($cod_nomina!=null && $cod_trans!=null){
		$denomi_transaccion=$this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$codi' and cod_tipo_transaccion=".$condicion, $order ="cod_transaccion ASC");
		if(!$denomi_transaccion){
			$this->set('denomi_transaccion', '');
		}else{
			$this->set('denomi_transaccion', $denomi_transaccion);
		}
		$this->set('cod_nomina', $cod_nomina);
		$this->index();
		$this->set('cod_trans', $cod_trans);
		$this->deno_nomina($cod_nomina);
		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$cod_trans' and cod_tipo_transaccion=2", $order ="cod_transaccion ASC");
		$this->set('deno_trans', $deno_trans);
		$opciones= $this->cnmd10_comunes_porcentaje_deduccion->findAll($conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_transaccion='$cod_trans'", null, $order = null, $limit = null, $page = null, $recursive = null);
		$this->set('opciones', $opciones);
		$opciones1= $this->cnmd10_comunes_porcentaje_deduccion->findAll($conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina'", null,'order by  cod_transaccion asc', $limit = null, $page = null, $recursive = null);
		$this->set('opciones1', $opciones1);
		$deno_trans= $this->cnmd03_transacciones->findAll('cod_tipo_transaccion=2', null, $order = null, $limit = null, $page = null, $recursive = null);
		$this->set('deno_trans', $deno_trans);
		$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$condicion, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
		$this->concatenaN($cnmd03, 'transaccion');
	}
}//FIN VER




function transferir($var1=null){
	$this->layout="ajax";
		$carga=$this->cnmd10_comunes_porcentaje_deduccion->findAll($this->condicion()." and cod_tipo_nomina=".$var1." and cod_tipo_transaccion=2");
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

}//fin transferir




function cod_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_trans', $cod_nomina);
	}

}//fin cod_transferencia



function deno_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
		echo "<script>";
			echo "document.getElementById('save_transferir').disabled=false;";
		echo "</script>";
	}
}//fin deno_transferencia



function guardar_transferir(){
	$this->layout="ajax";

	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $cod_tipo_nomina = $this->data['cnmp10_comunes_bolivares_asig']['cod_nomina'];
	  $cod_transferir = $this->data['cnmp10_comunes_bolivares_asig']['cod_transferir'];
	  $cod_tipo_transaccion=2;
	  $s=0;
	  $bandera=0;
	  $bandera=0;
	 $data=$this->cnmd10_comunes_porcentaje_deduccion->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2");
	 $ubicacion_escenario = strtoupper('DEDUCCIONES COMUNES - EN PORCENTAJE CALCULADO SEGÚN EL SUELDO');
	  foreach($data as $row){
			$cod_transaccion1 = $row['cnmd10_comunes_porcentaje_deduccion']['cod_transaccion'];
			$monto = $row['cnmd10_comunes_porcentaje_deduccion']['porcentaje'];
			$tope = $row['cnmd10_comunes_porcentaje_deduccion']['tope'];
			$cod_frecuencia = $row['cnmd10_comunes_porcentaje_deduccion']['cod_frecuencia'];
			$cod_condicion = $row['cnmd10_comunes_porcentaje_deduccion']['cod_condicion'];
			$codi_tipo_transaccion = $row['cnmd10_comunes_porcentaje_deduccion']['codi_tipo_transaccion'];
			$codi_transaccion = $row['cnmd10_comunes_porcentaje_deduccion']['codi_transaccion'];
			$activar_frecuencia_eventual = $row['cnmd10_comunes_porcentaje_deduccion']['activar_frecuencia_eventua'];
			 $datos=$this->cnmd10_control_escenarios->findAll($this->condicion()." and cod_tipo_nomina=".$cod_transferir." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transaccion1);
			if(!$datos){
				$sql_insert = "INSERT INTO cnmd10_comunes_porcentaje_deduccion VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion1','$monto','$tope','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
				$sw = $this->cnmd10_comunes_porcentaje_deduccion->execute($sql_insert);
				if($sw>1){
				echo "<script>";
					echo "document.getElementById('save_transferir').disabled='disabled';";
					echo "document.getElementById('select_transferir').options[0].text='';";
					echo "document.getElementById('cod_transferencia').value='';";
					echo "document.getElementById('deno_transferencia').value='';";
				echo "</script>";
				$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion1', '$ubicacion_escenario')";
				$sw1 = $this->cnmd10_control_escenarios->execute($sql_control);
				$s=2;
			}//fin $sw
		}else{
			$bandera=2;
		}//fin $datos
			$sw=0;
	  }//fin foreach data

	 /* if($s>1){
	  	$this->set('Message_existe', 'Transferencia realizada con exito');
	}//fin $s*/
	if($bandera>1 && $s>1){
	  	$this->set('Message_existe', 'Transferencia realizada con exito');
	}else if($bandera==0 && $s>1){
		$this->set('Message_existe', 'Transferencia realizada con exito');
	}else if($bandera>1 && $s==0){
		$this->set('Message_error', 'Transferencia sin exito');
	}

$sql='';
			 if($data){
			 	foreach($data as $x){
			 		$transaccion=$x['cnmd10_comunes_porcentaje_deduccion']['cod_transaccion'];
			 		if($sql==''){
			 			$sql.=$transaccion;
			 		}else{
			 			$sql.=",".$transaccion;
			 		}
			 	}
				$query="SELECT distinct a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.cod_tipo_nomina FROM cnmd10_comunes_porcentaje_deduccion a where a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.cod_tipo_transaccion=2 and cod_tipo_nomina!=".$cod_tipo_nomina." and a.cod_transaccion IN(".$sql.")";
				$opciones=$this->cnmd10_comunes_porcentaje_deduccion->execute($query);
				if($opciones){
					$this->set('opciones',$opciones);
				}else{
					 $this->set('opciones','');
				}
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->Cnmd01->findAll($this->condicion(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);

}// fin guardar_transferir


}//FIN CONTROLADOR
?>