<?php
class Capp01ProcesoRealizarPasoController extends AppController {
   var $name = 'capp01_proceso_realizar_paso';
   var $uses = array('capd01_tipo_documento','capd02_procesos','capd03_documentos');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession

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




 function beforeFilter(){
 	$this->checkSession();
 	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
 }


 function index(){
 	$this->layout ="ajax";

 	$lista=$this->capd01_tipo_documento->generateList($this->SQLCA(),'cod_tipo_documento ASC', null, '{n}.capd01_tipo_documento.cod_tipo_documento', '{n}.capd01_tipo_documento.denominacion');
 	$this->set('documentos',$lista);

 }// fin index


function mostrar($opcion=null,$var=null){
	$this->layout ="ajax";
if($var!=''){
	if($opcion=='cod'){
		$this->set('codigo',$var);
		$this->set('opcion',$opcion);
		/*echo "<script>
		 	document.getElementById('save').disabled=false;
		 	document.getElementById('pasos').value='';
			document.getElementById('dias').value='';
		 </script>";*/
	}else{

		$deno=$this->capd01_tipo_documento->execute("select denominacion from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$var);
		$this->set('denominacion',$deno[0][0]['denominacion']);
		$this->set('opcion',$opcion);

	}
}else{
	$this->set('codigo','');
	$this->set('denominacion','');
	$this->set('opcion',$opcion);
}

}//fin mostrar



function proceso($var=null){
	$this->layout = "ajax";
if($var!=''){
	$pasos=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$var);
	$ver=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$var." order by  paso desc limit 1");
	if($ver!=null){
		$numero=$ver[0][0]['paso']+1;
		$veri=$ver[0][0]['paso'];
	}else{
		$numero=1;
		$actual=1;
		$veri='';
	}

	$cantidad_pasos=$pasos[0][0]['pasos_cumplir'];
	if($veri!=$cantidad_pasos){
		$this->set('paso',$numero);
		$this->set('pasos_actuales',$numero);
		$this->set('cantidad_pasos',$cantidad_pasos);
		$this->set('disabled','');
	}else{
		$this->set('paso',$ver[0][0]['paso']);
		$this->set('pasos_actuales',$ver[0][0]['paso']);
		$this->set('cantidad_pasos',$cantidad_pasos);
		$this->set('disabled','disabled');
	}
	$this->set('pasitos','pasitos');
	$datos=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$var." order by paso asc");
	$this->set('datos',$datos);

}else{
	$this->set('paso',' ');
	$this->set('datos',null);
	$this->set('pasitos',null);
	$this->set('pasos_actuales','');
	$this->set('cantidad_pasos','');
	$this->set('disabled','disabled');
}

}// fin proceso



function guardar(){
	$this->layout = "ajax";

//	pr($this->data);
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
	$tipo_documento=$this->data['capp01']['tipo_documento'];
	if(empty($this->data['capp01']['tipo_documento']) || empty($this->data['capp01']['pasos']) || empty($this->data['capp01']['entrada']) || empty($this->data['capp01']['salida'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');

	}else{
		$tipo_documento=$this->data['capp01']['tipo_documento'];
		$pasos=$this->data['capp01']['pasos'];
		$entrada=$this->data['capp01']['entrada'];
		$salida=$this->data['capp01']['salida'];
		$colar='';
		if(empty($this->data['capp01']['dias']) && empty($this->data['capp01']['horas']) && empty($this->data['capp01']['minutos'])){
			$this->set('errorMessage', 'debe ingresar los dias, minutos u horas');
		}else{
			$meter='';
			if(!empty($this->data['capp01']['dias'])){
				$dias=$this->data['capp01']['dias'];
				$colar.=',estimacion_dias';
				$meter.=",'$dias'";
			}

			if(!empty($this->data['capp01']['horas'])){
				$horas=$this->data['capp01']['horas'];
				$colar.=',estimacion_horas';
				$meter.=",'$horas'";
			}

			if(!empty($this->data['capp01']['minutos'])){
				$minutos=$this->data['capp01']['minutos'];
				$colar.=',estimacion_minutos';
				$meter.=",'$minutos'";
			}

			$campos="(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_tipo_documento,paso,proceso_realizar_entrada,proceso_realizar_salida".$colar.")";
			$insert="('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$tipo_documento','$pasos','$entrada','$salida'".$meter.")";






		$dato=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." and paso=".$pasos);
		if($dato!=null){
			$this->set('errorMessage', 'ESTE PASO YA EXISTE REGISTRADO');
			/*echo "<script>
			 	document.getElementById('pasos').value='';
			 	document.getElementById('dias').value='';
			 </script>";*/
		}else{
			$sql_insert = "BEGIN;INSERT INTO capd02_procesos ".$campos." VALUES ".$insert;
			$sw2 = $this->capd02_procesos->execute($sql_insert);
			if($sw2>1){
					$this->capd02_procesos->execute("COMMIT");
			 		$this->set('Message_existe', 'EL PASO HA SIDO REGISTRADO CON EXITO');
			 }else{
			 	$this->capd02_procesos->execute("ROOLBACK");
			 	$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA');
			 }
		}

$this->data=null;

	}
}
	$pasos1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
	$ver=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." order by  paso desc limit 1");
	if($ver!=null){
		$numero=$ver[0][0]['paso']+1;
	}else{
		$numero=1;
		$actual=1;
	}

	$cantidad_pasos=$pasos1[0][0]['pasos_cumplir'];
if($ver[0][0]['paso']!=$cantidad_pasos){
	$this->set('paso',$numero);
	$this->set('pasos_actuales',$numero);
	$this->set('cantidad_pasos',$cantidad_pasos);
	$this->set('disabled','');
}else{
	$this->set('paso',$ver[0][0]['paso']);
	$this->set('pasos_actuales',$ver[0][0]['paso']);
	$this->set('cantidad_pasos',$cantidad_pasos);
	$this->set('disabled','disabled');

///////////////////////////////para actualizar los dias probables de pago///////////////////////////

	$ver2=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
	$dias=0;$horas=0;$minutos=0;
	for($i=0;$i<count($ver2);$i++){
		$dias+=$ver2[$i][0]['estimacion_dias'];
		$horas+=$ver2[$i][0]['estimacion_horas'];
		$minutos+=$ver2[$i][0]['estimacion_minutos'];
	}
	$minutos=$minutos/60;
	$div_minutos= explode('.',$minutos);
	$minutos=$div_minutos[0]+1;

	$horas=($horas+$minutos)/8;
	$div_horas= explode('.',$horas);
	$horas=$div_horas[0]+1;

	$dias=$dias+$horas;


	 $this->capd01_tipo_documento->execute("UPDATE  capd01_tipo_documento SET dias_probable_pago='$dias' WHERE ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
}


	$datos=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." order by paso asc");
	$this->set('datos',$datos);

}// fin guardar





function eliminar($tipo_documento=null,$paso=null){
	  $this->layout = "ajax";

		 $verifica = $this->capd03_documentos->findcount($this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
		 if($verifica==0){
		 	 $x = $this->capd02_procesos->execute("BEGIN;DELETE FROM capd02_procesos  WHERE ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." and paso=".$paso);
			  if($x>1){
			  	$this->capd02_procesos->execute("COMMIT");
			  	$this->set('Message_existe','registro eliminado con exito');
			  }else{
			  	$this->capd02_procesos->execute("ROLLBACK");
			  	$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
			  }
		 }else{
		 		$this->set('errorMessage', 'EL DATO NO Podra ser eliminado ya que contiene registrado, documentos de origen');
		 }



	$pasos1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
	$ver=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." order by  paso desc limit 1");
	if($ver!=null){
		$numero=$ver[0][0]['paso']+1;
	}else{
		$numero=1;
		$actual=1;
	}

	$cantidad_pasos=$pasos1[0][0]['pasos_cumplir'];
if($ver[0][0]['paso']!=$cantidad_pasos){
	$this->set('paso',$numero);
	$this->set('pasos_actuales',$numero);
	$this->set('cantidad_pasos',$cantidad_pasos);
	$this->set('disabled','');
}else{
	$this->set('paso',$ver[0][0]['paso']);
	$this->set('pasos_actuales',$numero);
	$this->set('cantidad_pasos',$cantidad_pasos);
	$this->set('disabled','disabled');
}


	$datos=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." order by paso asc");
	$this->set('datos',$datos);

}//fin function




 function modificar($tipo_documento=null,$paso=null,$i=null){
 	 $this->layout = "ajax";

 	$sql2="select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." and paso=".$paso;
	$dato1=$this->capd02_procesos->execute($sql2);
	$this->set('datos',$dato1);
	$this->set('k',$i);

	$verifica = $this->capd03_documentos->findcount($this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
	if($verifica==0){
		$this->set('Message_existe', 'PROCEDA A MODIFICAR LOS DATOS');
		$this->set('disabled','');
	}else{
		$this->set('errorMessage', 'EL DATO NO Podra ser modificado ya que contiene registrado, documentos de origen');
		$this->set('disabled','disabled');
	}

 }// fin modificar_items





function guardar_modificar($tipo_documento=null,$paso=null,$i=null){
	$this->layout = "ajax";
//pr($this->data);
	if(empty($this->data['capp01']['entrada'.$i]) || empty($this->data['capp01']['salida'.$i])){
		$this->set('errorMessage', 'Debe ingresar los datos de entrada y salida');
	}else{
		$entrada=$this->data['capp01']['entrada'.$i];
		$salida=$this->data['capp01']['salida'.$i];
//		$dias=$this->data['capp01']['dias'.$i];
//		$horas=$this->data['capp01']['horas'.$i];
//		$minutos=$this->data['capp01']['minutos'.$i];

		if(empty($this->data['capp01']['dias'.$i]) && empty($this->data['capp01']['horas'.$i]) && empty($this->data['capp01']['minutos'.$i])){
			$this->set('errorMessage', 'debe ingresar los dias, minutos u horas');
		}else{
			$colar='';
			if(!empty($this->data['capp01']['dias'.$i])){
				$dias=$this->data['capp01']['dias'.$i];
				$colar.=',estimacion_dias='.$dias;
			}else{
				$colar.=',estimacion_dias=null';
			}

			if(!empty($this->data['capp01']['horas'.$i])){
				$horas=$this->data['capp01']['horas'.$i];
				$colar.=',estimacion_horas='.$horas;
			}else{
				$colar.=',estimacion_horas=null';
			}

			if(!empty($this->data['capp01']['minutos'.$i])){
				$minutos=$this->data['capp01']['minutos'.$i];
				$colar.=',estimacion_minutos='.$minutos;
			}else{
				$colar.=',estimacion_minutos=null';
			}

		$verifica = $this->capd03_documentos->findcount($this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
		if($verifica==0){
			$sql = "BEGIN;UPDATE capd02_procesos SET proceso_realizar_entrada='$entrada',proceso_realizar_salida='$salida'".$colar." where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." and paso=".$paso;
			$sw=$this->capd02_procesos->execute($sql);
			if($sw>1){
					$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
					$this->capd02_procesos->execute("COMMIT");
				}else{
					$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER MODIFICADOS');
					$this->capd02_procesos->execute("ROLLBACK");
				}
		}else{
			$this->set('errorMessage', 'EL DATO NO Podra ser modificado ya que contiene registrado, documentos de origen');
		}
	}
}

	$pasos1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
	$ver=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." order by  paso desc limit 1");
	if($ver!=null){
		$numero=$ver[0][0]['paso']+1;
	}else{
		$numero=1;
		$actual=1;
	}

	$cantidad_pasos=$pasos1[0][0]['pasos_cumplir'];
	if($ver[0][0]['paso']!=$cantidad_pasos){
		$this->set('paso',$numero);
		$this->set('pasos_actuales',$numero);
		$this->set('cantidad_pasos',$cantidad_pasos);
		$this->set('disabled','');
	}else{
		$this->set('paso',$ver[0][0]['paso']);
		$this->set('pasos_actuales',$ver[0][0]['paso']);
		$this->set('cantidad_pasos',$cantidad_pasos);
		$this->set('disabled','disabled');

		///////////////////////////////para actualizar los dias probables de pago///////////////////////////

	$ver2=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
	$dias=0;$horas=0;$minutos=0;
	for($i=0;$i<count($ver2);$i++){
		$dias+=$ver2[$i][0]['estimacion_dias'];
		$horas+=$ver2[$i][0]['estimacion_horas'];
		$minutos+=$ver2[$i][0]['estimacion_minutos'];
	}

	$minutos=$minutos/60;
	$div_minutos= explode('.',$minutos);
	$minutos=$div_minutos[0]+1;

	$horas=($horas+$minutos)/8;
	$div_horas= explode('.',$horas);
	$horas=$div_horas[0]+1;

	$dias=$dias+$horas;

	 $this->capd01_tipo_documento->execute("UPDATE  capd01_tipo_documento SET dias_probable_pago='$dias' WHERE ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);

	}


	$datos=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." order by paso asc");
	$this->set('datos',$datos);


}//fin guardar_items_modificar



function cancelar($tipo_documento=null){
    $this->layout = "ajax";

	$pasos1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
	$ver=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." order by  paso desc limit 1");
	if($ver!=null){
		$numero=$ver[0][0]['paso']+1;
	}else{
		$numero=1;
		$actual=1;
	}

	$cantidad_pasos=$pasos1[0][0]['pasos_cumplir'];
	if($ver[0][0]['paso']!=$cantidad_pasos){
		$this->set('paso',$numero);
		$this->set('pasos_actuales',$numero);
		$this->set('cantidad_pasos',$cantidad_pasos);
		$this->set('disabled','');
	}else{
		$this->set('paso',$ver[0][0]['paso']);
		$this->set('pasos_actuales',$ver[0][0]['paso']);
		$this->set('cantidad_pasos',$cantidad_pasos);
		$this->set('disabled','disabled');
	}


	$datos=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." order by paso asc");
	$this->set('datos',$datos);

}//fin cancelar


 }//Fin de la clase controller
 ?>