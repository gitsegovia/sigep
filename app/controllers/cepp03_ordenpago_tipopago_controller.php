<?php

 class Cepp03OrdenpagoTipopagoController extends AppController {
 	//var $name = 'cepd01_tipo_documento';
 	var $uses = array ('cepd03_ordenpago_tipopago','cepd03_ordenpago_cuerpo');
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


 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	$tipo_compromiso = $this->cepd03_ordenpago_tipopago->generateList(null,'cod_tipo_pago ASC', null, '{n}.cepd03_ordenpago_tipopago.cod_tipo_pago', '{n}.cepd03_ordenpago_tipopago.denominacion');
	$tipo_compromiso = $tipo_compromiso != null ? $tipo_compromiso : array();
	$this->concatena($tipo_compromiso, 'tipo');
	$this->data["cepp03_ordenpago_tipopago"]["denominacion"]=null;
 	$this->set('enable', 'disabled');
 }



 function selec_tipo($var = null){
 	$this->layout ="ajax";
  	$this->set('action', $var);

 	$tipo_compromiso = $this->cepd03_ordenpago_tipopago->generateList(null,'cod_tipo_pago ASC', null, '{n}.cepd03_ordenpago_tipopago.cod_tipo_pago', '{n}.cepd03_ordenpago_tipopago.denominacion');
	$tipo_compromiso = $tipo_compromiso != null ? $tipo_compromiso : array();
	$this->concatena($tipo_compromiso, 'tipo');
 	if($var != 'otros'){
		$this->set('datos', $this->cepd03_ordenpago_tipopago->findAll('cod_tipo_pago = '.$var));
 	}else{
 		$this->data["cepp03_ordenpago_tipopago"] = array();
 	}
	$this->set('enable', 'disabled');
 }



 function guardar(){
 	$this->layout ="ajax";

 	if(!empty($this->data["cepp03_ordenpago_tipopago"]['denominacion'])){
 		if($this->cepd03_ordenpago_tipopago->findBydenominacion($this->data["cepp03_ordenpago_tipopago"]['denominacion'])){
 			$this->set('datos', array());
			$this->set('mensajeError', 'EL TIPO DE PAGO YA EXISTE');
		}else{
		$denominacion = $this->data["cepp03_ordenpago_tipopago"]['denominacion'];
		$sql ="INSERT INTO cepd03_ordenpago_tipopago (denominacion)";
		$sql .= " values ('".$denominacion."')";
		if($this->cepd03_ordenpago_tipopago->execute($sql)>1){
			$this->set('mensaje', 'EL TIPO DE PAGO  FUE GUARDADO CORRECTAMENTE');
		}else{
			$this->set('mensajeError', 'EL TIPO DE PAGO NO PUDO SER GUARDADO');
		}
		}
 	}else{
 		$this->set('datos', array());
 		$this->set('mensajeError', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
 	}
 	$this->mostrar_datos();
	$this->render("mostrar_datos");
 }



 function eliminar($cod_tipo_pago=null){
	$this->layout ="ajax";

	if($cod_tipo_pago != null){
		if($this->cepd03_ordenpago_cuerpo->findBycod_tipo_pago($cod_tipo_pago)){
			$this->set('mensajeError', 'Lo siento, el tipo de pago ya se encuentra en uso, no puede ser eliminado');
		}else{
		$sql = "DELETE FROM cepd03_ordenpago_tipopago WHERE cod_tipo_pago = ".$cod_tipo_pago;
		$this->cepd03_ordenpago_tipopago->execute($sql);
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		}
	}
	$this->mostrar_datos();
	$this->render("mostrar_datos");
 }



 function consulta($pag_num=null){
 	$this->layout ="ajax";

    $data = $this->cepd03_ordenpago_tipopago->findAll(null, null, 'cod_tipo_pago ASC', null, null, null);
    $this->set('datos',$data);
    if($pag_num!=null){
    $this->set('pagina_actual', $pag_num);
    }
 }



 function modificar($cod_tipo_pago = null){
 	$this->layout ="ajax";
 	$this->set('tipo', $this->cepd03_ordenpago_tipopago->generateList(null, 'cod_tipo_pago'));
 	$this->set('datos', $this->cepd03_ordenpago_tipopago->findAll('cod_tipo_pago = '.$cod_tipo_pago));
 	$this->set('Message_existe', 'INGRESE LOS DATOS A MODIFICAR');
 }



function guardar_modificar($cod_tipo_pago=null){
	$this->layout = "ajax";

	if(!empty($this->data["cepp03_ordenpago_tipopago"]['denominacion'])){
		$a=$this->data["cepp03_ordenpago_tipopago"]['denominacion'];
		//$b=$this->data["cepp03_ordenpago_tipopago"]['cod_tipo_pago'];
		$sql3="update cepd03_ordenpago_tipopago set denominacion='$a' where cod_tipo_pago=".$cod_tipo_pago;
		$this->set('mensaje', 'EL TIPO DE PAGO FUE MODIFICADO CORRECTAMENTE');
		$this->cepd03_ordenpago_tipopago->execute($sql3);
	}else{
    	$this->set('datos', array());
 		$this->set('mensajeError', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
	}
	$this->mostrar_datos();
	$this->render("mostrar_datos");
}



//------------Nuevas Funciones (index, mostrar1, mostrar_datos)-----------//



function index3 () {
 	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	$list = $this->cepd03_ordenpago_tipopago->generateList(null,'cod_tipo_pago ASC', null, '{n}.cepd03_ordenpago_tipopago.cod_tipo_pago', '{n}.cepd03_ordenpago_tipopago.denominacion');
	$list = $list != null ? $list : array();
	$datos=$this->cepd03_ordenpago_tipopago->findAll(null,null,'cod_tipo_pago ASC');
	$this->concatena($list, 'list');
	$this->set('datos',$datos);
 }



function mostrar1($select=null){
	$this->layout="ajax";
	if($select!=null){
		if($select=='otros'){
			$this->set('ir','no');
			$this->set('cod_tipo_pago','');
			$this->set('denominacion','');
			$this->set('mensaje','Puede agregar el nuevo tipo de pago');
		}else{
			$dato=$this->cepd03_ordenpago_tipopago->findAll('cod_tipo_pago='.$select);
			$this->set('ir','si');
			$this->set('cod_tipo_pago',$dato[0]['cepd03_ordenpago_tipopago']['cod_tipo_pago']);
			$this->set('denominacion',$dato[0]['cepd03_ordenpago_tipopago']['denominacion']);
		}
	}else{
		$this->set('ir','no');
		$this->set('cod_tipo_pago','');
		$this->set('denominacion','');
		$this->set('mensajeError','No ha seleccionado nigun tipo de pago');
	}
}//mostrar1



function mostrar_datos(){
	$datos=$this->cepd03_ordenpago_tipopago->findAll(null,null,'cod_tipo_pago ASC');
	$this->set('datos',$datos);
}


 }//fin de la clase
?>