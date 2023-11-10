<?php
class Cnmp09AsignacionController extends AppController{


 	var $uses = array('cnmd03_transacciones','cnmd09_asignacion_calcula_asignacion','cnmd09_asignacion_calcula_asignacion_2','ccfd04_cierre_mes','Cnmd01','ccfd03_instalacion');
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
$this->Session->delete('nomina');
//$this->Session->delete('radio');
	$cond= $this->SQLCA();

 $ano='';
 $ano=$this->ano_ejecucion();
 $this->set('ano',$ano);
$cond2="cod_tipo_transaccion=1";
$lista= $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');

//print_r($lista);
$this->concatenaN($lista, 'lista');

//$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	//$this->concatena($lista, 'nomina');



    }//fin index

function select_trans($var=null){
	$this->layout = "ajax";
//	$tipo=$this->cnmd03_transacciones->generateList2("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$tipo=$this->cnmd03_transacciones->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($tipo, 'tipo');
	$this->set('nomina',$var);
//	print_r($tipo);
}



    function mostrardatos($opcion,$var=null) {
	$this->layout="ajax";
	if(isset($var) && $var!=''){
		$this->Session->write('nomina',$var);
	switch($opcion){
		case 'codigo':
				$this->set("codigo",$var);
				$this->Session->delete('nomi');
				$this->Session->write('nomi',$var);
				echo "<script>";
					echo "document.getElementById('transferencia').innerHTML='';";
					echo"document.getElementById('transaccion').value = ''; ";
			  		echo"document.getElementById('denominacion').value = ''; ";
				echo "</script>";
	break;
		case 'denominacion':
				$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$var'", $order ="cod_tipo_nomina ASC");
				$this->set('deno', $deno_nomina);
//				$c=$this->Cnmd01->findByCod_tipo_nomina($var);
//				$this->set("deno",$c["Cnmd01"]["denominacion"]);
	break;
	}
	}else{
		echo'<script>';
			  echo"document.getElementById('cod_nomina').value = ''; ";
			  echo"document.getElementById('deno_nomina').value = ''; ";
			  echo"document.getElementById('transaccion').value = ''; ";
			  echo"document.getElementById('denominacion').value = ''; ";
		echo'</script>';
			$this->set('vacio',"");
		//echo "";
	}

}//fin mostrardatos




function mostrar($opcion,$nomina=null,$var=null) {

	$this->layout="ajax";
	if(isset($var) && $var!=''){
	switch($opcion){
		case 'codigo':
				$this->set("codigo",$var);
				$this->Session->delete('tran');
				$this->Session->write('tran',$var);
				$datos=$this->cnmd09_asignacion_calcula_asignacion->findCount($this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_transaccion=".$var,null,'cod_transaccion ASC');
				  if($datos==0){
				  	echo'<script>';
					  echo"document.getElementById('guardar_asignacion').disabled=false; ";
					echo'</script>';
				  }else{
				  	echo'<script>';
					  echo"document.getElementById('guardar_asignacion').disabled='disabled'; ";
					echo'</script>';
				  }

	break;
		case 'denominacion':
				$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$var' and cod_tipo_transaccion=1", $order ="cod_transaccion ASC");
				if(!$deno_trans){
					$this->set('vacio',"");
				}else{
					$this->set("deno",$deno_trans);
				}

	break;
	}
	}else{
		echo'<script>';
			  echo"document.getElementById('transaccion').value = ''; ";
			  echo"document.getElementById('denominacion').value = ''; ";
			echo'</script>';
			echo'<script>';
			  echo"document.getElementById('trans').value = ''; ";
			  echo"document.getElementById('deno').value = ''; ";
			echo'</script>';
			$this->set('vacio',"");
	}

}//fin mostrar



function muestracod($opcion,$var=null) {
	$this->layout="ajax";
	if(isset($var) && $var!=''){
	switch($opcion){
		case 'codigo':
				$this->set("codigo",$var);
				$a=$this->Session->read('nomi');
				$b=$this->Session->read('tran');
				if($this->cnmd09_asignacion_calcula_asignacion->FindCount($this->SQLCA()." and cod_tipo_nomina=".$a." and cod_transaccion=".$b)==0){
					echo'<script>';
						  echo"document.getElementById('agregar').disabled ='disabled'; ";
						echo'</script>';
				}else{
					if($this->cnmd09_asignacion_calcula_asignacion_2->FindCount($this->SQLCA()." and cod_tipo_nomina=".$a." and cod_transaccion=".$b." and codi_transaccion=".$var)==0){
						echo'<script>';
						  echo"document.getElementById('agregar').disabled =false; ";
						echo'</script>';
					}else{
						echo'<script>';
						  echo"document.getElementById('agregar').disabled ='disabled'; ";
						echo'</script>';
					}
				}
	break;
		case 'denominacion':
				$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$var' and cod_tipo_transaccion=1", $order ="cod_transaccion ASC");
				if(!$deno_trans){
					$this->set('vacio',"");
				}else{
					$this->set('deno', $deno_trans);
				}

	break;
	}
	}else{
		echo'<script>';
			  echo"document.getElementById('trans').value = ''; ";
			  echo"document.getElementById('deno').value = ''; ";
			echo'</script>';
			$this->set('vacio',"");
	}

}//fin muestracod


function muestra_radio($a=null,$b=null){
	$this->layout="ajax";
	$sql="select * from cnmd09_asignacion_calcula_asignacion where ".$this->SQLCA()." and cod_tipo_nomina=".$a." and cod_transaccion=".$b;
	$v=$this->cnmd09_asignacion_calcula_asignacion->execute($sql);
	if($v){
		$radio=$v[0][0]["incluye_sueldo_basico"];
		$this->set('radio',$radio);
	}else{
		$this->set('radio',"");
	}
}


function guardar_items(){
	$this->layout="ajax";

	  $cod1=$this->verifica_SS(1);
	  $cod2=$this->verifica_SS(2);
	  $cod3=$this->verifica_SS(3);
	  $cod4=$this->verifica_SS(4);
	  $cod5=$this->verifica_SS(5);
	  $cod_nomina=$this->data["cnmp09_asignacion"]["nomina"];
	  $cod_transaccion=$this->data["cnmp09_asignacion"]["transaccion"];
	  $cod_trans=$this->data["cnmp09_asignacion"]["codigo"];
	  $tipo_transaccion = 1;

if($cod_transaccion==410){
	echo "Código no se puede modificar";
					$this->set('mensajeError', 'Código no se puede modificar');
}else{
	    if($this->data['cnmp09_asignacion']['codigo']!=null && $this->data['cnmp09_asignacion']['codigo']!=""){
	    	//mensaje
	    	$sw1="INSERT INTO cnmd09_asignacion_calcula_asignacion_2 VALUES (".$cod1.",".$cod2.",".$cod3.",".$cod4.",".$cod5.",".$cod_nomina.",".$tipo_transaccion.",".$cod_transaccion.",".$tipo_transaccion.",".$cod_trans.")";
			$sw = $this->cnmd09_asignacion_calcula_asignacion_2->execute($sw1);
			if($sw>1){
				$datos = $this->cnmd09_asignacion_calcula_asignacion_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_transaccion,null,'cod_transaccion ASC');
				$this->set("datos",$datos);
				echo'<script>';
							  echo"document.getElementById('agregar').disabled ='disabled'; ";
				echo'</script>';

			}else{
				echo "no se pudo guardar";
			}
	    }else{

	    	echo "ingrese hasta que ano";
	    }
	    $datos2=$this->cnmd03_transacciones->findAll("cod_tipo_transaccion=1",null,null);
		$this->set('deno_trans',$datos2);
	}
}


function guardar(){
		$this->layout="ajax";
		$ver=$this->Session->read('radio');
		$cod1=$this->verifica_SS(1);
		$cod2=$this->verifica_SS(2);
		$cod3=$this->verifica_SS(3);
		$cod4=$this->verifica_SS(4);
		$cod5=$this->verifica_SS(5);
		$cod_nomina=$this->data["cnmp09_asignacion"]["nomina"];
	    $cod_transaccion=$this->data["cnmp09_asignacion"]["transaccion"];


	    $tipo_transaccion=1;
	     $cond=$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_transaccion;
		$n=1;
		if($ver==2){
			$sueldo=2;
		}else{
			$sueldo=1;
		}

		$query=$this->cnmd09_asignacion_calcula_asignacion->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_tipo_transaccion=".$tipo_transaccion." and cod_transaccion=".$cod_transaccion,null,null);
		if(!$query){
				$sw=$this->cnmd09_asignacion_calcula_asignacion->execute("BEGIN;INSERT INTO cnmd09_asignacion_calcula_asignacion VALUES (".$cod1.",".$cod2.",".$cod3.",".$cod4.",".$cod5.",".$cod_nomina.",".$tipo_transaccion.",".$cod_transaccion.")");
				if(!empty($this->data["cnmp09_asignacion"]["codigo"])){
			    	$cod_trans=$this->data["cnmp09_asignacion"]["codigo"];
			    	$sw1=$this->cnmd09_asignacion_calcula_asignacion_2->execute("INSERT INTO cnmd09_asignacion_calcula_asignacion_2 VALUES (".$cod1.",".$cod2.",".$cod3.",".$cod4.",".$cod5.",".$cod_nomina.",".$tipo_transaccion.",".$cod_transaccion.",".$tipo_transaccion.",".$cod_trans.")");
				}
				if($sw>1){
					$this->cnmd09_asignacion_calcula_asignacion->execute('COMMIT');
			        $this->set('mensaje', 'Registro Exitoso');
				}else{
					$this->cnmd09_asignacion_calcula_asignacion->execute('ROLLBACK');
					$this->set('mensajeError', 'inserci&oacute;n fallida');
				}
		}else{
		        $this->cnmd09_asignacion_calcula_asignacion->execute("update cnmd09_asignacion_calcula_asignacion where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_transaccion);///modifica el sueldo
		        $this->set('mensaje', 'el registro se ha modificado');
		}// fin if
			 $datos=$this->cnmd09_asignacion_calcula_asignacion_2->findAll($cond,null,'cod_transaccion ASC');
			  if(!$datos){
			       $this->set('datos',"");
			  }else{
			       $this->set('datos',$datos);
			  }// fin if(!$datos)


				$datos2=$this->cnmd03_transacciones->findAll("cod_tipo_transaccion=1",null,null);
				$this->set('deno_trans',$datos2);



}///fin guardar







function select($var=null){
	$this->layout="ajax";
if(isset($var) && $var!=""){
	$cond="cod_tipo_transaccion=1 and cod_transaccion!=".$var;
//	$cod=$this->cnmd03_transacciones->generateList2($cond, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$cod=$this->cnmd03_transacciones->generateList($cond, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cod,'cod');

}else{
	echo'<script>';
			  echo"document.getElementById('trans').value = ''; ";
			  echo"document.getElementById('deno').value = ''; ";
			echo'</script>';
}


}//fin select



function grilla($var=null){

	$this->layout="ajax";
	$cod=$this->Session->read('nomina');
	if((isset($var) && $var!="") && $cod!=""){
	    $cond=$this->SQLCA()." and cod_tipo_nomina=".$cod." and cod_transaccion=".$var;
		$datos=$this->cnmd09_asignacion_calcula_asignacion_2->findAll($cond,null,'codi_transaccion ASC');
		if(!$datos){
			$this->set('datos',"");
		}else{
			$this->set('datos',$datos);
		}

	}
	//$this->Session->delete('nomina');
	$datos2=$this->cnmd03_transacciones->findAll("cod_tipo_transaccion=1",null,null);
//	pr($datos2);
	$this->set('deno_trans',$datos2);

}//fin grilla




function sueldo($var=null) {
		$this->layout="ajax";
//$this->Session->delete('radio');
$this->Session->write('radio',$var);
/*	echo '<script>' .
			'habilita_compromiso();' .
			'</script>';
	if(isset($var) && $var==1){
		//buscar para que el codigo sea automatico
		$v=$this->cstd03_cheque_numero->execute("SELECT numero_control_cheque FROM cstd03_cheque_numero WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." ORDER BY numero_compromiso DESC");
		//print_r($v);
		if($v!=null){
			$numero=$v[0][0]["numero_compromiso"];
			$numero = $numero =="" ? 1 : $numero+1;
		}else{
			$numero=1;
		}
	}else{
		$numero="";
	}
		$this->set("numero",$numero);*/
}//sueldo







function eliminar_items($cod=null,$cod1=null,$cod2){
	$this->layout="ajax";
	if($cod1!=410){
	$cond=$this->SQLCA()." and cod_tipo_nomina=".$cod." and cod_tipo_transaccion=1 and cod_transaccion=".$cod1." and codi_transaccion=".$cod2;
	$this->cnmd09_asignacion_calcula_asignacion_2->execute("delete from cnmd09_asignacion_calcula_asignacion_2 where ".$cond);
	$this->set('mensaje', 'Registro eliminado');
}else{
	$this->set('mensajeError', 'Este registro no se puede eliminar');
}
}// eliminar_items





function eliminar(){

	$this->layout="ajax";
	$cod_nomina=$this->data["cnmp09_asignacion"]["nomina"];
    $cod_transaccion=$this->data["cnmp09_asignacion"]["transaccion"];
    $cond=$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_transaccion;
	$datos=$this->cnmd09_asignacion_calcula_asignacion->findAll($cond,null,null);
	if(!$datos){
		$this->set('mensajeError', 'no existen datos que eliminar');
	}else{
		$cond1=$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion;
		$this->cnmd09_asignacion_calcula_asignacion->execute("delete from cnmd09_asignacion_calcula_asignacion where ".$cond1);
		$this->set('mensaje', 'Registro eliminado');
	}
	$this->index();
	$this->render('index');

	echo'<script>';
	  echo"document.getElementById('eliminar_asignacion').disabled=false; ";
	echo'</script>';

}



function cod_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_trans', $cod_nomina);
		$cod_trans=$this->Session->read('tran');
		if($this->cnmd09_asignacion_calcula_asignacion->FindCount($this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans)==0){
			echo "<script>show_save_transferir();</script>";
		}else{
			echo "<script>hide_save_transferir();</script>";
		}
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
		$carga=$this->cnmd09_asignacion_calcula_asignacion->findAll($this->condicion()." and cod_tipo_nomina=".$var1." and cod_tipo_transaccion=1 and cod_transaccion=".$var2);
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
	$datos=$this->cnmd09_asignacion_calcula_asignacion->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_transaccion=".$var2." and cod_tipo_nomina!=".$var1,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
//print_r($datos);
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

	  $cod_tipo_nomina = $this->data['cnmp09_asignacion']['nomina'];
	  $cod_transaccion = $this->data["cnmp09_asignacion"]["transaccion"];
	  $cod_transferir = $this->data["cnmp09_asignacion"]["select_transferir"];
	  $cod_tipo_transaccion=1;
	  if($this->cnmd09_asignacion_calcula_asignacion->FindCount($this->SQLCA()." and cod_tipo_nomina=".$cod_transferir." and cod_transaccion=".$cod_transaccion)==0){
	  $data=$this->cnmd09_asignacion_calcula_asignacion->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
	  $data2=$this->cnmd09_asignacion_calcula_asignacion_2->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
		//print_r($data);
	  foreach($data as $row){
			$cod_transaccion1 = $row['cnmd09_asignacion_calcula_asignacion']['cod_transaccion'];
			$sql_insert = "INSERT INTO cnmd09_asignacion_calcula_asignacion VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion1')";
			$sw = $this->cnmd09_asignacion_calcula_asignacion->execute($sql_insert);
			if($sw>1){
				echo "<script>";
					echo "document.getElementById('save_transferir').disabled='disabled';";
					echo "document.getElementById('select_transferir').options[0].text='';";
					echo "document.getElementById('cod_transferencia').value='';";
					echo "document.getElementById('deno_transferencia').value='';";
				echo "</script>";
				if($data2){
					foreach($data2 as $row){
						$cod_transaccion2 = $row['cnmd09_asignacion_calcula_asignacion_2']['cod_transaccion'];
						$codi_transaccion = $row['cnmd09_asignacion_calcula_asignacion_2']['codi_transaccion'];
						//$monto_tope = $row['cnmd10_comunes_puestos_porcentaje_ded_2']['monto_tope'];
						$sql_insert2 = "INSERT INTO cnmd09_asignacion_calcula_asignacion_2 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion2','$cod_tipo_transaccion','$codi_transaccion')";
						$sw2 = $this->cnmd09_asignacion_calcula_asignacion_2->execute($sql_insert2);
					}// fin foreach data2
				}else{
					$sw2=2;
				}
			}//fin $sw
	  }//fin foreach data
	  if($sw>1 && $sw2>1){
	  		$this->set('Message_existe', 'Transferencia realizada con exito');
	  }else{
	  		$this->set('Message_error', 'Transferencia sin exito intente nuevamente');
	  }
}else{//fin ubicacion
	$this->set('Message_error', 'Transferencia sin exito intente nuevamente');
}


//////////////////////////////AGREGAR DESDE AQUI////////////////////

	$datos=$this->cnmd09_asignacion_calcula_asignacion->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion." and cod_tipo_nomina!=".$cod_tipo_nomina,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
//print_r($datos);
	 $deno_trans= $this->Cnmd01->findAll($this->SQLCA(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);

}// fin guardar_transferir



function transaccion($var=null){
	$this->layout="ajax";
	if($var!='guarda'){
		$nomina=$var;
	}else{
		 $nomina = $this->data['cnmp09_asignacion']['nomina'];
	}
	$datos=$this->cnmd09_asignacion_calcula_asignacion->findAll($this->SQLCA()." and cod_tipo_transaccion=1 and cod_tipo_nomina=".$nomina,null,'order by asc cod_transaccion');
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=1', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
	 $this->render('transaccion');
}//fin transaccion


function vacio(){
	$this->layout="ajax";
}


}//fin controlador

?>