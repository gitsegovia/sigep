<?php

 class Cfpp05TipoIngresoExtraController extends AppController {
 	//var $name = 'cepd01_tipo_documento';
 	var $uses = array ('cfpd05_tipo_ingreso_extra','cepd03_ordenpago_cuerpo');
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
    $Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);

	$tipo_compromiso = $this->cfpd05_tipo_ingreso_extra->generateList("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst",'cod_tipo_ingreso ASC', null, '{n}.cfpd05_tipo_ingreso_extra.cod_tipo_ingreso', '{n}.cfpd05_tipo_ingreso_extra.denominacion');
	$tipo_compromiso = $tipo_compromiso != null ? $tipo_compromiso : array();
	$this->concatena($tipo_compromiso, 'tipo');
	$this->data["cfpd05_tipo_ingreso_extra"]["denominacion"]=null;
 	$this->set('enable', 'disabled');
 }



 function selec_tipo($var = null){
 	$this->layout ="ajax";
  	$this->set('action', $var);
  	$Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);

 	$tipo_compromiso = $this->cfpd05_tipo_ingreso_extra->generateList("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst",'cod_tipo_ingreso ASC', null, '{n}.cfpd05_tipo_ingreso_extra.cod_tipo_ingreso', '{n}.cfpd05_tipo_ingreso_extra.denominacion');
	$tipo_compromiso = $tipo_compromiso != null ? $tipo_compromiso : array();
	$this->concatena($tipo_compromiso, 'tipo');
 	if($var != 'otros'){
		$this->set('datos', $this->cfpd05_tipo_ingreso_extra->findAll("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst".' and cod_tipo_ingreso = '.$var));
 	}else{
 		$this->data["cfpd05_tipo_ingreso_extra"] = array();
 	}
	$this->set('enable', 'disabled');
 }



 function guardar(){
 	$this->layout ="ajax";
    $Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);
 	if(!empty($this->data["cfpd05_tipo_ingreso_extra"]['denominacion'])){
 		if($this->cfpd05_tipo_ingreso_extra->findCount("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst and denominacion='".$this->data["cfpd05_tipo_ingreso_extra"]['denominacion']."'")>0){
 			$this->set('datos', array());
			$this->set('mensajeError', 'EL REGISTRO YA EXISTE');
		}else{
		$dd = $this->cfpd05_tipo_ingreso_extra->findAll("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst",null,'cod_tipo_ingreso DESC');
		$var_num = isset($dd[0]['cfpd05_tipo_ingreso_extra']['cod_tipo_ingreso'])?($dd[0]['cfpd05_tipo_ingreso_extra']['cod_tipo_ingreso']+1):1;
		$denominacion = $this->data["cfpd05_tipo_ingreso_extra"]['denominacion'];
		$sql ="INSERT INTO cfpd05_tipo_ingreso_extra ";
		$sql .= " values ($Cpresi,$Centidad,$Ctipo_inst,$Cinst,$var_num,'".$denominacion."')";
		if($this->cfpd05_tipo_ingreso_extra->execute($sql)>1){
			$this->set('mensaje', 'EL REGISTRO  FUE GUARDADO CORRECTAMENTE');
		}else{
			$this->set('mensajeError', 'EL REGISTRO NO PUDO SER GUARDADO');
		}
		}
 	}else{
 		$this->set('datos', array());
 		$this->set('mensajeError', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
 	}
 	$this->mostrar_datos();
	$this->render("mostrar_datos");
 }



 function eliminar($cod_tipo_ingreso=null){
	$this->layout ="ajax";
    $Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);
	if($cod_tipo_ingreso != null){
		$sql = "DELETE FROM cfpd05_tipo_ingreso_extra WHERE cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst and cod_tipo_ingreso = ".$cod_tipo_ingreso;
		$this->cfpd05_tipo_ingreso_extra->execute($sql);
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
	}
	$this->mostrar_datos();
	$this->render("mostrar_datos");
 }



 function consulta($pag_num=null){
 	$this->layout ="ajax";
    $Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);
    $data = $this->cfpd05_tipo_ingreso_extra->findAll("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst", null, 'cod_tipo_ingreso ASC', null, null, null);
    $this->set('datos',$data);
    if($pag_num!=null){
    $this->set('pagina_actual', $pag_num);
    }
 }



 function modificar($cod_tipo_ingreso = null){
 	$this->layout ="ajax";
 	$Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);
 	$this->set('tipo', $this->cfpd05_tipo_ingreso_extra->generateList("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst", 'cod_tipo_ingreso'));
 	$this->set('datos', $this->cfpd05_tipo_ingreso_extra->findAll("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst and ".' cod_tipo_ingreso = '.$cod_tipo_ingreso));
 	$this->set('Message_existe', 'INGRESE LOS DATOS A MODIFICAR');
 }



function guardar_modificar($cod_tipo_ingreso=null){
	$this->layout = "ajax";
    $Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);
	if(!empty($this->data["cfpd05_tipo_ingreso_extra"]['denominacion'])){
		$a=$this->data["cfpd05_tipo_ingreso_extra"]['denominacion'];
		//$b=$this->data["cfpd05_tipo_ingreso_extra"]['cod_tipo_ingreso'];
		$sql3="update cfpd05_tipo_ingreso_extra set denominacion='$a' where cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst and cod_tipo_ingreso=".$cod_tipo_ingreso;
		$this->set('mensaje', 'EL REGISTRO FUE MODIFICADO CORRECTAMENTE');
		$this->cfpd05_tipo_ingreso_extra->execute($sql3);
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
	$Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);

	$list = $this->cfpd05_tipo_ingreso_extra->generateList("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst",'cod_tipo_ingreso ASC', null, '{n}.cfpd05_tipo_ingreso_extra.cod_tipo_ingreso', '{n}.cfpd05_tipo_ingreso_extra.denominacion');
	$list = $list != null ? $list : array();
	$datos=$this->cfpd05_tipo_ingreso_extra->findAll("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst",null,'cod_tipo_ingreso ASC');
	$this->concatena($list, 'list');
	$this->set('datos',$datos);
 }



function mostrar1($select=null){
	$this->layout="ajax";
	$Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);
	if($select!=null){
		if($select=='otros'){
			$this->set('ir','no');
			$this->set('cod_tipo_ingreso','');
			$this->set('denominacion','');
			$this->set('mensaje','Puede agregar el nuevo registro');
		}else{
			$dato=$this->cfpd05_tipo_ingreso_extra->findAll("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst and ".'cod_tipo_ingreso='.$select);
			$this->set('ir','si');
			$this->set('cod_tipo_ingreso',$dato[0]['cfpd05_tipo_ingreso_extra']['cod_tipo_ingreso']);
			$this->set('denominacion',$dato[0]['cfpd05_tipo_ingreso_extra']['denominacion']);
		}
	}else{
		$this->set('ir','no');
		$this->set('cod_tipo_ingreso','');
		$this->set('denominacion','');
		$this->set('mensajeError','No ha seleccionado ningun registro');
	}
}//mostrar1



function mostrar_datos(){
	$Cpresi=$this->verifica_SS(1);
	$Centidad=$this->verifica_SS(2);
	$Ctipo_inst=$this->verifica_SS(3);
	$Cinst=$this->verifica_SS(4);
	$datos=$this->cfpd05_tipo_ingreso_extra->findAll("cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst",null,'cod_tipo_ingreso ASC');
	$this->set('datos',$datos);
}


 }//fin de la clase
?>