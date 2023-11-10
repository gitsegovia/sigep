<?php
/*
 * Creado el 27/06/2008 a las 12:25:28 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion: Existen js de este programa que se encuentran en las js del programa cscp01_unidad_medida
 */
  class Csrp01TipoSolicitudController extends AppController {
 	var $name = 'csrp01_tipo_solicitud';
 	var $uses = array ('csrd01_tipo_solicitud');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');


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


function beforeFilter(){
    $this->checkSession();
}



function index() {
 	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$list = $this->csrd01_tipo_solicitud->generateList(null,'cod_tipo_solicitud ASC', null, '{n}.csrd01_tipo_solicitud.cod_tipo_solicitud', '{n}.csrd01_tipo_solicitud.denominacion');
	$list = $list != null ? $list : array();
	$datos=$this->csrd01_tipo_solicitud->findAll(null,null,'cod_tipo_solicitud ASC');
	$this->concatena($list, 'list');
	$this->set('datos',$datos);
 }



function mostrar1($select=null){
	$this->layout="ajax";
	if($select!=null){
		if($select=='otros'){
			$this->set('ir','no');
			$this->set('cod_tipo_solicitud','');
			$this->set('denominacion','');
			$this->set('mensaje','Puede agregar el nuevo tipo de solicitud');
		}else{
			$dato=$this->csrd01_tipo_solicitud->findAll('cod_tipo_solicitud='.$select);
			$this->set('ir','si');
			$this->set('cod_tipo_solicitud',$dato[0]['csrd01_tipo_solicitud']['cod_tipo_solicitud']);
			$this->set('denominacion',$dato[0]['csrd01_tipo_solicitud']['denominacion']);
		}
	}else{
		$this->set('ir','no');
		$this->set('cod_tipo_solicitud','');
		$this->set('denominacion','');
		$this->set('mensajeError','No ha seleccionado nigun tipo de solicitud');
	}
}//mostrar1



function mostrar_datos(){
	$datos=$this->csrd01_tipo_solicitud->findAll(null,null,'cod_tipo_solicitud ASC');
	$this->set('datos',$datos);
}


function guardar(){
 	$this->layout ="ajax";

 	if(!empty($this->data["csrp01_tipo_solicitud"]['denominacion']) || !empty($this->data["csrp01_tipo_solicitud"]['cod_tipo_solicitud'])){
 			if($this->csrd01_tipo_solicitud->findBycod_tipo_solicitud($this->data["csrp01_tipo_solicitud"]['cod_tipo_solicitud'])!=0){
			$this->set('mensajeError', 'LO SIENTO YA ESE C&Oacute;DIGO DE SOLICITUD SE ENCUENTRA REGISTRADO');
 			}else{
			$cod_tipo_solicitud = $this->data["csrp01_tipo_solicitud"]['cod_tipo_solicitud'];
			$denominacion = $this->data["csrp01_tipo_solicitud"]['denominacion'];
			$sql ="INSERT INTO csrd01_tipo_solicitud (cod_tipo_solicitud, denominacion)";
			$sql .= " values ($cod_tipo_solicitud,'".$denominacion."')";
				if($this->csrd01_tipo_solicitud->execute($sql)>1){
				$this->set('mensaje', 'EL TIPO DE SOLICITUD  FUE REGISTRADA CORRECTAMENTE');
				}else{
				$this->set('mensajeError', 'EL TIPO DE SOLICITUD NO PUDO SER REGISTRADA');
				}
			}
 	}else{
 		$this->set('datos', array());
 		$this->set('mensajeError', 'INGRESE EL C&Oacute;DIGO Y LA DENOMINACI&Oacute;N DE LA SOLICITUD, POR FAVOR');
 	}
 	$this->mostrar_datos();
	$this->render("mostrar_datos");
}



function guardar_modificar($cod_tipo_pago=null){
	$this->layout = "ajax";
	if(!empty($this->data["csrp01_tipo_solicitud"]['denominacion'])){
		$a=$this->data["csrp01_tipo_solicitud"]['denominacion'];
		$sql3="update csrd01_tipo_solicitud set denominacion='$a' where cod_tipo_solicitud=".$cod_tipo_pago;
		$this->csrd01_tipo_solicitud->execute($sql3);
		$this->set('mensaje', 'EL TIPO DE SOLICITUD FUE MODIFICADO CORRECTAMENTE');
	}else{
    	$this->set('datos', array());
 		$this->set('mensajeError', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
	}
	$this->mostrar_datos();
	$this->render("mostrar_datos");
}


function eliminar($cod_tipo_solicitud=null){
	$this->layout ="ajax";
	if($cod_tipo_solicitud != null){
		$sql = "DELETE FROM csrd01_tipo_solicitud WHERE cod_tipo_solicitud = ".$cod_tipo_solicitud;
		$this->csrd01_tipo_solicitud->execute($sql);
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
	}
	$this->mostrar_datos();
	$this->render("mostrar_datos");
}


}//fin class
?>