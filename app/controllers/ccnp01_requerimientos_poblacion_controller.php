<?php
/*
 * Fecha: 06/07/2007
 *
 * Por Erisk Aragol
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

 class Ccnp01RequerimientosPoblacionController extends AppController{

	var $name = 'ccnp01_requerimientos_poblacion';
	var $uses = array('ccnd02_tipo_requerimiento','ccnd02_requerimientos','Usuario');
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



 function filtro(){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');


	return $conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo;

 }


function index() {

   	$this->layout = "ajax";
   	$ver=$this->ccnd02_requerimientos->execute("select * from ccnd02_requerimientos where ".$this->filtro()." order by cod_requerimiento desc limit 1");
	if($ver!=null)
		$cod_tipo_requerimiento=$ver[0][0]['cod_requerimiento']+1;
	else
		$cod_tipo_requerimiento=1;
	$this->set('cod_requerimiento',$cod_tipo_requerimiento);

	$tipo_requerimiento=$this->ccnd02_tipo_requerimiento->generateList(null,'cod_tipo_requerimiento ASC', null, '{n}.ccnd02_tipo_requerimiento.cod_tipo_requerimiento', '{n}.ccnd02_tipo_requerimiento.denominacion');
	if($tipo_requerimiento!=null){
		$this->concatena($tipo_requerimiento,'tipo_requerimiento');
	}else{
		$this->set('tipo_requerimiento',array());
	}

	$datos=$this->ccnd02_requerimientos->execute("select * from ccnd02_requerimientos where ".$this->filtro()." order by cod_requerimiento asc");
	$this->set('datos',$datos);

	$paren=$this->ccnd02_tipo_requerimiento->findAll();
	$this->set('paren',$paren);

	echo "<script>document.getElementById('denominacion').focus();</script>";


}



function guardar(){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');

	if(empty($this->data['arrp00']['codigo'])){
		$this->set('errorMessage', 'Debe ingresar el código');

	}else if(empty($this->data['arrp00']['denominacion'])){
		$this->set('errorMessage', 'Debe ingresar la denominación del requerimiento');

	}else if(empty($this->data['arrp00']['tipo_requerimiento'])){
		$this->set('errorMessage', 'Debe seleccionar un tipo de requerimiento');

	}else{
		$codigo=$this->data['arrp00']['codigo'];
		$denominacion=$this->data['arrp00']['denominacion'];
		$tipo_requerimiento=$this->data['arrp00']['tipo_requerimiento'];
		$proyecto=null;
		$status=1;
		$campos="(cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_centro,cod_concejo,cod_requerimiento,denominacion,status,cod_tipo_requerimiento)";
		$datos=$this->ccnd02_requerimientos->execute("select * from ccnd02_requerimientos where ".$this->filtro()." and cod_requerimiento='$codigo' order by cod_tipo_requerimiento asc");
		if($datos==null){
			 $sql = "INSERT INTO ccnd02_requerimientos ".$campos." VALUES ('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro','$cod_concejo','$codigo', '$denominacion','$status','$tipo_requerimiento')";
		   	 $sw=$this->ccnd02_requerimientos->execute($sql);
		   	 if($sw>1){
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				echo" <script> ver_documento('/ccnp01_requerimientos_poblacion','principal'); </script>";
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

	$datos=$this->ccnd02_requerimientos->execute("select * from ccnd02_requerimientos where ".$this->filtro()." and cod_requerimiento='$cod_tipo_requerimiento' order by cod_requerimiento asc");
	$this->set('datos',$datos);

	$this->set('k',$i);

	$tipo_requerimiento=$this->ccnd02_tipo_requerimiento->generateList(null,'cod_tipo_requerimiento ASC', null, '{n}.ccnd02_tipo_requerimiento.cod_tipo_requerimiento', '{n}.ccnd02_tipo_requerimiento.denominacion');
	if($tipo_requerimiento!=null){
		$this->concatena($tipo_requerimiento,'tipo_requerimiento');
	}else{
		$this->set('tipo_requerimiento',array());
	}


}



function guardar_modificar($cod_tipo_requerimiento=null,$i=null){
	$this->layout="ajax";

	if(empty($this->data['arrp00']['denominacion'.$i])){
		$this->set('errorMessage', 'Debe ingresar la denominación del requerimiento');

	}else if(empty($this->data['arrp00']['tipo_requerimiento'.$i])){
		$this->set('errorMessage', 'Debe seleccionar un tipo de requerimiento');

	}else{
		$codigo=$this->data['arrp00']['codigo'.$i];
		$denominacion=$this->data['arrp00']['denominacion'.$i];
		$tipo_requerimiento=$this->data['arrp00']['tipo_requerimiento'.$i];

		 $sql = "update ccnd02_requerimientos set denominacion='".$denominacion."',cod_tipo_requerimiento='$tipo_requerimiento' where ".$this->filtro()." and cod_requerimiento='$cod_tipo_requerimiento'";
	   	 $sw=$this->ccnd02_tipo_requerimiento->execute($sql);
	   	 if($sw>1){
			$this->set('Message_existe', 'EL REGISTRO SE MODIFICO CON EXITO');
			echo" <script> ver_documento('/ccnp01_requerimientos_poblacion','principal'); </script>";
   		}else{
   			$this->set('errorMessage', 'EL DATO NO PUDO SER MODIFICADO');
   		}

	}

}



function eliminar($cod_tipo_requerimiento=null){
	$this->layout="ajax";

	 $sql = "delete from  ccnd02_requerimientos where ".$this->filtro()." and cod_requerimiento='$cod_tipo_requerimiento'";
   	 $sw=$this->ccnd02_requerimientos->execute($sql);
   	 if($sw>1){
		$this->set('Message_existe', 'EL REGISTRO SE ELIMINO CON EXITO');
		echo" <script> ver_documento('/ccnp01_requerimientos_poblacion','principal'); </script>";
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
