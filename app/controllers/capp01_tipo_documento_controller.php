<?php
class Capp01TipoDocumentoController extends AppController {
   var $name = 'capp01_tipo_documento';
   var $uses = array('capd01_tipo_documento','capd02_procesos');
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
	$documentos= array('1'=>'OTROS COMPROMISOS','2'=>'ORDENES DE COMPRA','3'=>'CONTRATOS DE OBRAS','4'=>'CONTRATOS DE SERVICIOS');
	$this->set('documentos',$documentos);

	$sql="select * from capd01_tipo_documento where ".$this->SQLCA()." order by cod_tipo_documento asc";
	$datos=$this->capd01_tipo_documento->execute($sql);
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
		switch($var){
			case 1:
				$deno='OTROS COMPROMISOS';
			break;
			case 2:
				$deno='ORDENES DE COMPRA';
			break;
			case 3:
				$deno='CONTRATOS DE OBRAS';
			break;
			case 4:
				$deno='CONTRATOS DE SERVICIOS';
			break;
		}
		$this->set('denominacion',$deno);
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

	if(empty($this->data['capp01']['tipo_documento']) || empty($this->data['capp01']['pasos']) || empty($this->data['capp01']['dias'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
	}else {
		$tipo_documento=$this->data['capp01']['tipo_documento'];
		$denominacion=$this->data['capp01']['denominacion'];
		$pasos=$this->data['capp01']['pasos'];
		$dias=$this->data['capp01']['dias'];

		$dato=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
		if($dato!=null){
			$this->set('errorMessage', 'ESTE TIPO DE DOCUMENTO YA EXISTE REGISTRADO');
			echo "<script>
			 	document.getElementById('pasos').value='';
			 	document.getElementById('dias').value='';
			 </script>";
		}else{
			$sql_insert = "BEGIN;INSERT INTO capd01_tipo_documento VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$tipo_documento','$denominacion','$dias','$pasos')";
			$sw2 = $this->capd01_tipo_documento->execute($sql_insert);
			if($sw2>1){
					$this->capd01_tipo_documento->execute("COMMIT");
			 		$this->set('Message_existe', 'REGISTRO EXITOSO');
			 }else{
			 	$this->capd01_tipo_documento->execute("ROOLBACK");
			 	$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA');
			 }
		}



	}

$sql="select * from capd01_tipo_documento where ".$this->SQLCA()." order by cod_tipo_documento asc";
$datos=$this->capd01_tipo_documento->execute($sql);
$this->set('datos',$datos);

}// fin guardar




function eliminar($tipo_documento=null){
	  $this->layout = "ajax";

	  $verifica = $this->capd02_procesos->findcount($this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
	  if($verifica==0){
	  	 $x = $this->capd01_tipo_documento->execute("BEGIN;DELETE FROM capd01_tipo_documento  WHERE ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
		  if($x>1){
				$this->capd01_tipo_documento->execute("COMMIT");
		 		$this->set('Message_existe', 'REGISTRO ELIMINADO CON EXITO');
		  }else{
		  	$this->capd01_tipo_documento->execute("ROLLBACK");
		  	$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		  }

	  }else{
	  	$this->set('errorMessage', 'EL DATO NO PODRA SER ELIMINADO YA QUE POSEE PASOS REGISTRADOS');
	  }


	$sql="select * from capd01_tipo_documento where ".$this->SQLCA()." order by cod_tipo_documento asc";
	$datos=$this->capd01_tipo_documento->execute($sql);
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