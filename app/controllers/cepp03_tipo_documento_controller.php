<?php

 class Cepp03TipoDocumentoController extends AppController {
 	//var $name = 'cepd01_tipo_documento';
 	var $uses = array ('cepd03_tipo_documento');
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

	$tipo_documento = $this->cepd03_tipo_documento->generateList(null,'cod_tipo_documento ASC', null, '{n}.cepd03_tipo_documento.cod_tipo_documento', '{n}.cepd03_tipo_documento.denominacion');
	$tipo_documento = $tipo_documento != null ? $tipo_documento : array();
	$this->concatena($tipo_documento, 'tipo');
	$this->data["cepp03_tipo_documento"]["denominacion"]=null;
 	$this->set('enable', 'disabled');
 }



 function selec_tipo($var = null){
 	$this->layout ="ajax";
  	$this->set('action', $var);

 	$tipo_documento = $this->cepd03_tipo_documento->generateList(null,'cod_tipo_documento ASC', null, '{n}.cepd03_tipo_documento.cod_tipo_documento', '{n}.cepd03_tipo_documento.denominacion');
	$tipo_documento = $tipo_documento != null ? $tipo_documento : array();
	$this->concatena($tipo_documento, 'tipo');
 	if($var != 'otros'){
		$this->set('datos', $this->cepd03_tipo_documento->findAll('cod_tipo_documento = '.$var));
 	}else{
 		$this->data["cepp03_tipo_documento"] = array();
 	}
	$this->set('enable', 'disabled');
 }



 function guardar(){
 	$this->layout ="ajax";

 	if(!empty($this->data["cepp03_tipo_documento"]['denominacion'])){
 		if($this->cepd03_tipo_documento->findBydenominacion($this->data["cepp03_tipo_documento"]['denominacion'])){
 			$this->set('datos', array());
			$this->set('mensajeError', 'EL PARENTESCO YA EXISTE');
			$this->index();
			$this->render("index");
		}else{
		$denominacion = $this->data["cepp03_tipo_documento"]['denominacion'];
		$sql ="INSERT INTO cepd03_tipo_documento (denominacion)";
		$sql .= " values ('".$denominacion."')";
		$this->cepd03_tipo_documento->execute($sql);
		$this->set('mensaje', 'EL DATO FUE GUARDADO CORRECTAMENTE');
		$this->index();
		$this->render("index");
		}
 	}else{
 		$this->set('datos', array());
 		$this->set('mensajeError', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
 		$this->index();
		$this->render("index");
 	}
 }



 function eliminar($cod_tipo_documento){
	$this->layout ="ajax";

	if($cod_tipo_documento != null){
		$sql = "DELETE FROM cepd03_tipo_documento WHERE cod_tipo_documento = ".$cod_tipo_documento;
		$this->cepd03_tipo_documento->execute($sql);
		$this->set('tipo', $this->cepd03_tipo_documento->generateList(null, 'cod_tipo_documento'));
		$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		$this->set('enable', 'disabled');
		$this->consulta();
		$this->render("consulta");
	}
 }



 function consulta($pag_num=null){
 	$this->layout ="ajax";

    $data = $this->cepd03_tipo_documento->findAll(null, null, 'cod_tipo_documento ASC', null, null, null);
    $this->set('datos',$data);

    if($pag_num!=null){
    	$this->set('pagina_actual', $pag_num);
    }
 }



 function modificar($cod_tipo_documento = null){
 	$this->layout ="ajax";
 	$this->set('tipo', $this->cepd03_tipo_documento->generateList(null, 'cod_tipo_documento'));
 	$this->set('datos', $this->cepd03_tipo_documento->findAll('cod_tipo_documento = '.$cod_tipo_documento));
 	$this->set('Message_existe', 'INGRESE LOS DATOS A MODIFICAR');
 }



function guardar_modificar(){
	$this->layout = "ajax";
	//valido la denominacion
	if(!empty($this->data["cepp03_tipo_documento"]['denominacion'])){

	 $a=$this->data["cepp03_tipo_documento"]['denominacion'];
	 $b=$this->data["cepp03_tipo_documento"]['cod_tipo_documento'];

	    $sql3="update cepd03_tipo_documento set denominacion='$a' where cod_tipo_documento=$b";
		$this->set('Message_existe', 'EL PARENTESCO FUE MODIFICADO CORRECTAMENTE');

	   	$this->cepd03_tipo_documento->execute($sql3);
		$this->consulta();
        $this->render("consulta");
	}else{
    	$this->set('datos', array());
 		$this->set('errorMessage', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
		$this->consulta();
        $this->render("consulta");
	}
}



 }//fin de la clase
?>