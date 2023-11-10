<?php
class Ccnp01TipoDirectivaController extends AppController {
   var $name = 'ccnp01_tipo_directiva';
   var $uses = array('ccnd01_tipo_directivo','ccnd01_cargos_directivos');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
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
	$documentos= array('1'=>'UNIDAD ADMINISTRATIVA Y FINANCIERA COMUNITARIO','2'=>'COMITÉ DE SALUD','3'=>'COMITÉ DE EDUCACIÓN Y FORMACION CIUDADANA','4'=>'COMITÉ DE TIERRA URBANA O RURAL',
						'5'=>'COMITÉ DE VIVIENDA Y HABITÁT','6'=>'COMITÉ DE PROTECCIÓN SOCIAL DE NIÑOS, NIÑAS Y ADOLESCENTES','7'=>'COMITÉ DE ECONOMIA COMUNAL',
						'8'=>'COMITÉ DE FAMILIA E IGUALDAD DE GENERO','9'=>'COMITÉ DE SEGURIDAD Y DEFENSA INTEGRAL','10'=>'COMITÉ DE MEDIOS ALTERNATIVOS COMUNITARIOS',
						'11'=>'COMITÉ DE RECREACIÓN Y DEPORTES','12'=>'COMITÉ DE ALIMENTACIÓN Y DEFENSA DEL CONSUMIDOR','13'=>'COMITÉ DE MESA TÉCNICA DE AGUA','14'=>'COMITÉ DE MESA TÉCNICA DE ENERGÍA Y GAS',
						'15'=>'COMITÉ COMUNITARIO DE PERSONAS CON DISCAPACIDAD','16'=>'UNIDAD DE CONTRALORIA SOCIAL',);
	$this->set('documentos',$documentos);

	$sql="select * from ccnd01_tipo_directivo order by cod_tipo asc";
	$datos=$this->ccnd01_tipo_directivo->execute($sql);
	$this->set('datos',$datos);

 }// fin index


function mostrar($opcion=null,$var=null){
	$this->layout ="ajax";
	if($opcion=='cod'){
		$this->set('codigo',$var);
		$this->set('opcion',$opcion);
		echo "<script>
		 	document.getElementById('save').disabled=false;
		 	document.getElementById('pasos').value='';
			document.getElementById('dias').value='';
		 </script>";
	}else{
		$documentos= array('1'=>'UNIDAD ADMINISTRATIVA Y FINANCIERA COMUNITARIO','2'=>'COMITÉ DE SALUD','3'=>'COMITÉ DE EDUCACIÓN Y FORMACION CIUDADANA','4'=>'COMITÉ DE TIERRA URBANA O RURAL',
						'5'=>'COMITÉ DE VIVIENDA Y HABITÁT','6'=>'COMITÉ DE PROTECCIÓN SOCIAL DE NIÑOS, NIÑAS Y ADOLESCENTES','7'=>'COMITÉ DE ECONOMIA COMUNAL',
						'8'=>'COMITÉ DE FAMILIA E IGUALDAD DE GENERO','9'=>'COMITÉ DE SEGURIDAD Y DEFENSA INTEGRAL','10'=>'COMITÉ DE MEDIOS ALTERNATIVOS COMUNITARIOS',
						'11'=>'COMITÉ DE RECREACIÓN Y DEPORTES','12'=>'COMITÉ DE ALIMENTACIÓN Y DEFENSA DEL CONSUMIDOR','13'=>'COMITÉ DE MESA TÉCNICA DE AGUA','14'=>'COMITÉ DE MESA TÉCNICA DE ENERGÍA Y GAS',
						'15'=>'COMITÉ COMUNITARIO DE PERSONAS CON DISCAPACIDAD','16'=>'UNIDAD DE CONTRALORIA SOCIAL',);

		$this->set('denominacion',$documentos[$var]);
		$this->set('opcion',$opcion);

	}



}//fin mostrar


function guardar(){
	$this->layout = "ajax";

//	pr($this->data);
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

	if(empty($this->data['capp01']['tipo_documento'])){
		$this->set('errorMessage', 'Debe seleccionar el tipo de directivo');
	}else {
		$tipo_documento=$this->data['capp01']['tipo_documento'];
		$denominacion=$this->data['capp01']['denominacion'];

		$dato=$this->ccnd01_tipo_directivo->execute("select * from ccnd01_tipo_directivo where cod_tipo=".$tipo_documento);
		if($dato!=null){
			$this->set('errorMessage', 'ESTE TIPO DE DIRECTIVO YA EXISTE REGISTRADO');
		}else{
			$sql_insert = "BEGIN;INSERT INTO ccnd01_tipo_directivo VALUES ('$tipo_documento','$denominacion')";
			$sw2 = $this->ccnd01_tipo_directivo->execute($sql_insert);
			if($sw2>1){
					$this->ccnd01_tipo_directivo->execute("COMMIT");
			 		$this->set('Message_existe', 'REGISTRO EXITOSO');
			 }else{
			 	$this->ccnd01_tipo_directivo->execute("ROOLBACK");
			 	$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA');
			 }
		}



	}

$sql="select * from ccnd01_tipo_directivo order by cod_tipo asc";
$datos=$this->ccnd01_tipo_directivo->execute($sql);
$this->set('datos',$datos);

}// fin guardar




function eliminar($tipo_documento=null){
	  $this->layout = "ajax";

	  $verifica = $this->ccnd01_cargos_directivos->findcount("cod_tipo=".$tipo_documento);
	  if($verifica==0){
	  	 $x = $this->ccnd01_tipo_directivo->execute("BEGIN;DELETE FROM ccnd01_tipo_directivo  WHERE cod_tipo=".$tipo_documento);
		  if($x>1){
				$this->ccnd01_tipo_directivo->execute("COMMIT");
		 		$this->set('Message_existe', 'REGISTRO ELIMINADO CON EXITO');
		  }else{
		  	$this->ccnd01_tipo_directivo->execute("ROLLBACK");
		  	$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		  }

	  }else{
	  	$this->set('errorMessage', 'EL DATO NO PODRA SER ELIMINADO YA QUE EL TIPO DE DIRECTIVO EXISTE EN OTRO PROGRAMA');
	  }


	$sql="select * from ccnd01_tipo_directivo order by cod_tipo asc";
	$datos=$this->ccnd01_tipo_directivo->execute($sql);
	$this->set('datos',$datos);
}//fin function




 function modificar($tipo_documento=null,$i=null){
 	 $this->layout = "ajax";


	 	$sql2="select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento;
		$dato1=$this->capd01_tipo_documento->execute($sql2);
		$this->set('dato1',$dato1);
		$this->set('k',$i);

$verifica = $this->capd02_procesos->findcount($this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
if($verifica==0){
		$this->set('Message_existe', 'PROCEDA A MODIFICAR LOS DATOS');
}else{
	$this->set('errorMessage', 'EL DATO NO PODRA SER MODIFICADO YA QUE POSEE PASOS REGISTRADOS');
}

 }// fin modificar_items





function guardar_modificar($tipo_documento=null,$i=null){
	$this->layout = "ajax";
$verifica = $this->capd02_procesos->findcount($this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
if($verifica==0){
	if(empty($this->data['capp01']['pasos'.$i]) || empty($this->data['capp01']['dias'.$i])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
	}else{
		$pasos=$this->data['capp01']['pasos'.$i];
		$dias=$this->data['capp01']['dias'.$i];

		$sql = "BEGIN;UPDATE capd01_tipo_documento SET dias_probable_pago='$dias',pasos_cumplir='$pasos' where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento;
		$sw=$this->capd01_tipo_documento->execute($sql);
		if($sw>1){
				$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
				$this->capd01_tipo_documento->execute("COMMIT");
			}else{
				$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER MODIFICADOS');
				$this->capd01_tipo_documento->execute("ROLLBACK");
			}
	}
 }else{
	$this->set('errorMessage', 'EL DATO NO PODRA SER MODIFICADO YA QUE POSEE PASOS REGISTRADOS');
 }

	$sql="select * from capd01_tipo_documento where ".$this->SQLCA()." order by cod_tipo_documento asc";
	$datos=$this->capd01_tipo_documento->execute($sql);
	$this->set('datos',$datos);

}//fin guardar_items_modificar



function cancelar(){
    $this->layout = "ajax";

	$sql="select * from capd01_tipo_documento where ".$this->SQLCA()." order by cod_tipo_documento asc";
	$datos=$this->capd01_tipo_documento->execute($sql);
	$this->set('datos',$datos);

}//fin cancelar


 }//Fin de la clase controller
 ?>