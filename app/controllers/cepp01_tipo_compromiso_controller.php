<?php
/*
 * Nota: Una de las funciones de JavaScript de este programa (para habilitar el boton de modificacion) se encuentra en el archivo js --> "cscp01_unidad_medida";
 */
 class Cepp01TipoCompromisoController extends AppController {
 	//var $name = 'cepd01_tipo_documento';
 	var $uses = array ('cepd01_tipo_compromiso','cepd01_compromiso_cuerpo');
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

	$tipo_compromiso = $this->cepd01_tipo_compromiso->generateList(null,'cod_tipo_compromiso ASC', null, '{n}.cepd01_tipo_compromiso.cod_tipo_compromiso', '{n}.cepd01_tipo_compromiso.denominacion');
	$tipo_compromiso = $tipo_compromiso != null ? $tipo_compromiso : array();
	$this->concatena($tipo_compromiso, 'tipo');
	$this->data["cepp01_tipo_compromiso"]["denominacion"]=null;
 	$this->set('enable', 'disabled');
 }



 function selec_tipo($var = null){
 	$this->layout ="ajax";
  	$this->set('action', $var);

 	$tipo_compromiso = $this->cepd01_tipo_compromiso->generateList(null,'cod_tipo_compromiso ASC', null, '{n}.cepd01_tipo_compromiso.cod_tipo_compromiso', '{n}.cepd01_tipo_compromiso.denominacion');
	$tipo_compromiso = $tipo_compromiso != null ? $tipo_compromiso : array();
	$this->concatena($tipo_compromiso, 'tipo');
 	if($var != 'otros'){
		$this->set('datos', $this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso = '.$var));
 	}else{
 		$this->data["cepp01_tipo_compromiso"] = array();
 	}
	$this->set('enable', 'disabled');
 }



 function guardar(){
 	$this->layout ="ajax";

 	if(!empty($this->data["cepp01_tipo_compromiso"]['denominacion'])){
		$denominacion = $this->data["cepp01_tipo_compromiso"]['denominacion'];
		$retencion = $this->data["cepp01_tipo_compromiso"]['sujeto_retencion'];
		$sql ="INSERT INTO cepd01_tipo_compromiso (denominacion,sujeto_retencion)";
		$sql .= " values ('".$denominacion."',".$retencion.")";
		$x=$this->cepd01_tipo_compromiso->execute($sql);
		if($x>1){
			$this->set('mensaje', 'EL REGISTRO DE COMPROMISO FU&Eacute; GUARDADO CORRECTAMENTE');
		}else{
			$this->set('mensajeError', 'LO SIENTO, EL REGISTRO DE COMPROMISO NO FU&Eacute; GUARDADO');
		}
 	}else{
 		$this->set('datos', array());
 		$this->set('mensajeError', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
 	}
 	echo'<script>';
	echo"document.getElementById('agregar').disabled=false;";
	echo"document.getElementById('denominacion').value='';";
	echo"document.getElementById('sujeto_retencion_1').checked=false;";
	echo'</script>';
 	$this->mostrar_datos();
	$this->render("mostrar_datos");
 }



 function eliminar($cod_tipo_compromiso=null){
	$this->layout ="ajax";

	if($cod_tipo_compromiso != null){
		if($this->cepd01_compromiso_cuerpo->findBycod_tipo_compromiso($cod_tipo_compromiso)){
			$this->set('mensajeError', 'Lo siento, el compromiso ya se encuentra en uso, no puede ser eliminado');
			$this->set('tipo', $this->cepd01_tipo_compromiso->generateList(null, 'cod_tipo_compromiso'));
		}else{
		$this->cepd01_tipo_compromiso->execute("DELETE FROM cepd01_tipo_compromiso WHERE cod_tipo_compromiso = ".$cod_tipo_compromiso);
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		}
	}else{
		$this->set('mensajeError', 'Lo siento, no llego el codigo del compromiso');
	}
	$this->mostrar_datos();
	$this->render("mostrar_datos");
 }



 function consulta($pag_num=null){
 	$this->layout ="ajax";

    $data = $this->cepd01_tipo_compromiso->findAll(null, null, 'cod_tipo_compromiso ASC', null, null, null);
    $this->set('datos',$data);
    if($pag_num!=null){
    	$this->set('pagina_actual', $pag_num);
    }
 }



 function modificar($cod_tipo_compromiso = null){
 	$this->layout ="ajax";
 	$this->set('tipo', $this->cepd01_tipo_compromiso->generateList(null, 'cod_tipo_compromiso'));
 	$this->set('datos', $this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso = '.$cod_tipo_compromiso));
 	$this->set('mensaje', 'INGRESE LOS DATOS A MODIFICAR');
 }



function guardar_modificar($cod_tipo_compromiso=null){
	$this->layout = "ajax";

	if(!empty($this->data["cepp01_tipo_compromiso"]['denominacion'])){
		$a=$this->data["cepp01_tipo_compromiso"]['denominacion'];
		//$b=$this->data["cepp01_tipo_compromiso"]['cod_tipo_compromiso'];
	    $retencion = $this->data["cepp01_tipo_compromiso"]['sujeto_retencion'];
	    $sql3="update cepd01_tipo_compromiso set denominacion='$a',sujeto_retencion=".$retencion." where cod_tipo_compromiso=".$cod_tipo_compromiso;
		$this->cepd01_tipo_compromiso->execute($sql3);
		$this->set('mensaje', 'EL REGISTRO DE COMPROMISO FUE MODIFICADO CORRECTAMENTE');
	}else{
    	$this->set('datos', array());
 		$this->set('errorMessage', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
	}
	$this->mostrar_datos();
	$this->render("mostrar_datos");
}



//------------Nuevas Funciones (index, mostrar1, mostrar_datos)-----------//



function index3 () {
 	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	$list = $this->cepd01_tipo_compromiso->generateList(null,'cod_tipo_compromiso ASC', null, '{n}.cepd01_tipo_compromiso.cod_tipo_compromiso', '{n}.cepd01_tipo_compromiso.denominacion');
	$list = $list != null ? $list : array();
	$datos=$this->cepd01_tipo_compromiso->findAll(null,null,'cod_tipo_compromiso ASC');
	$this->concatena($list, 'list');
	$this->set('datos',$datos);
 }



function mostrar1($select=null){
	$this->layout="ajax";
	if($select!=null){
		if($select=='otros'){
			$this->set('ir','no');
			$this->set('expresion','');
			$this->set('denominacion','');
			$this->set('mensaje','Puede agregar el nuevo Registro de Compromiso');
		}else{
			$dato=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$select);
			$this->set('ir','si');
			$this->set('cod_tipo_compromiso',$dato[0]['cepd01_tipo_compromiso']['cod_tipo_compromiso']);
			$this->set('denominacion',$dato[0]['cepd01_tipo_compromiso']['denominacion']);
			$this->set('sujeto_retencion',$dato[0]['cepd01_tipo_compromiso']['sujeto_retencion']);
		}
	}else{
		$this->set('ir','no');
		$this->set('cod_tipo_compromiso','');
		$this->set('denominacion','');
		$this->set('sujeto_retencion','');
		$this->set('mensajeError','No ha seleccionado ningun registro de compromiso');
	}
}//mostrar1



function mostrar_datos(){
	$datos=$this->cepd01_tipo_compromiso->findAll(null,null,'cod_tipo_compromiso ASC');
	$this->set('datos',$datos);
}



 }//fin de la clase
?>