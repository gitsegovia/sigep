<?php
/*
 * Fecha: 06/07/2007
 *
 * Por Erisk Aragol
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

 class Ccnp01TipoRequerimientosController extends AppController{

	var $name = 'ccnp01_tipo_requerimientos';
	var $uses = array('ccnd02_tipo_requerimiento','Usuario');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');




function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
				}
}//fin checksession





 function beforeFilter(){
 	$this->checkSession();

 }




function index() {

   	$this->layout = "ajax";
   	$ver=$this->ccnd02_tipo_requerimiento->execute("select * from ccnd02_tipo_requerimiento order by cod_tipo_requerimiento desc limit 1");
	if($ver!=null)
		$cod_tipo_requerimiento=$ver[0][0]['cod_tipo_requerimiento']+1;
	else
		$cod_tipo_requerimiento=1;
	$this->set('cod_tipo_requerimiento',$cod_tipo_requerimiento);

	$datos=$this->ccnd02_tipo_requerimiento->execute("select * from ccnd02_tipo_requerimiento order by cod_tipo_requerimiento asc");
	$this->set('datos',$datos);

	echo "<script>document.getElementById('denominacion').focus();</script>";

}



function guardar(){
	$this->layout="ajax";

	if(empty($this->data['arrp00']['codigo'])){
		$this->set('errorMessage', 'Debe ingresar el código');

	}else if(empty($this->data['arrp00']['denominacion'])){
		$this->set('errorMessage', 'Debe ingresar la denominación del tipo de requerimiento');

	}else{
		$codigo=$this->data['arrp00']['codigo'];
		$denominacion=$this->data['arrp00']['denominacion'];
		$datos=$this->ccnd02_tipo_requerimiento->execute("select * from ccnd02_tipo_requerimiento where cod_tipo_requerimiento='$codigo' order by cod_tipo_requerimiento asc");
		if($datos==null){
			 $sql = "INSERT INTO ccnd02_tipo_requerimiento VALUES ('$codigo', '$denominacion')";
		   	 $sw=$this->ccnd02_tipo_requerimiento->execute($sql);
		   	 if($sw>1){
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				echo" <script> ver_documento('/ccnp01_tipo_requerimientos','principal'); </script>";
	   		}else{
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	   		}
		}else{
			$this->set('errorMessage', 'este dato ya existe registrado');
		}

	}
}




function modificar($cod_tipo_requerimiento=null,$i=null){
	$this->layout="ajax";

	$datos=$this->ccnd02_tipo_requerimiento->execute("select * from ccnd02_tipo_requerimiento where cod_tipo_requerimiento='$cod_tipo_requerimiento' order by cod_tipo_requerimiento asc");
	$this->set('datos',$datos);

	$this->set('k',$i);


}



function guardar_modificar($cod_tipo_requerimiento=null,$i=null){
	$this->layout="ajax";

	if(empty($this->data['arrp00']['denominacion'.$i])){
		$this->set('errorMessage', 'Debe ingresar la denominación del tipo de requerimiento');

	}else{
		$codigo=$this->data['arrp00']['codigo'.$i];
		$denominacion=$this->data['arrp00']['denominacion'.$i];

		 $sql = "update ccnd02_tipo_requerimiento set denominacion='".$denominacion."' where cod_tipo_requerimiento='$cod_tipo_requerimiento'";
	   	 $sw=$this->ccnd02_tipo_requerimiento->execute($sql);
	   	 if($sw>1){
			$this->set('Message_existe', 'EL REGISTRO SE MODIFICO CON EXITO');
			echo" <script> ver_documento('/ccnp01_tipo_requerimientos','principal'); </script>";
   		}else{
   			$this->set('errorMessage', 'EL DATO NO PUDO SER MODIFICADO');
   		}

	}

}



function eliminar($cod_tipo_requerimiento=null){
	$this->layout="ajax";

	 $sql = "delete from  ccnd02_tipo_requerimiento where cod_tipo_requerimiento='$cod_tipo_requerimiento'";
   	 $sw=$this->ccnd02_tipo_requerimiento->execute($sql);
   	 if($sw>1){
		$this->set('Message_existe', 'EL REGISTRO SE ELIMINO CON EXITO');
		echo" <script> ver_documento('/ccnp01_tipo_requerimientos','principal'); </script>";
	}else{
		$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
	}

}



function cancelar($cod_tipo_requerimiento=null){
	$this->layout="ajax";
	$this->index();
	$this->render('index');
}



}//FIN DEL CONTROLADOR
?>
