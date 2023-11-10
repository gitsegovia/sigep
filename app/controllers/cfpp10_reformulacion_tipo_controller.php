<?php
/*
 * Creado el 31/01/2008 a las 07:47:02 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class Cfpp10ReformulacionTipoController extends AppController {
 	var $name = 'cfpp10_reformulacion_tipo';
 	var $uses = array ('cfpd10_reformulacion_tipo');
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
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	$num=$this->cfpd10_reformulacion_tipo->findCount();
	$tipo_compromiso = $this->cfpd10_reformulacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cfpd10_reformulacion_tipo.cod_tipo', '{n}.cfpd10_reformulacion_tipo.denominacion');
	$tipo_compromiso = $tipo_compromiso != null ? $tipo_compromiso : array();
	$this->concatena($tipo_compromiso, 'tipo');
	$this->data["cfpp10_reformulacion_tipo"]=null;
 	$this->set('enable', 'disabled');
 	$this->set('num',$num);
 }


function selec_tipo($var = null){
 	$this->layout ="ajax";
  	$this->set('action', $var);

	$num=$this->cfpd10_reformulacion_tipo->findCount();
	$this->set('num',$num);

 	$tipo_compromiso = $this->cfpd10_reformulacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cfpd10_reformulacion_tipo.cod_tipo', '{n}.cfpd10_reformulacion_tipo.denominacion');
	$tipo_compromiso = $tipo_compromiso != null ? $tipo_compromiso : array();
	$this->concatena($tipo_compromiso, 'tipo');
 	if($var != 'otros'){
		$this->set('datos', $this->cfpd10_reformulacion_tipo->findAll('cod_tipo = '.$var));
 	}else{
 		$this->data["cfpp10_reformulacion_tipo"] = array();
 	}
	$this->set('enable', 'disabled');
}




 function guardar(){
 	$this->layout ="ajax";

	//echo $this->data["cfpp10_reformulacion_tipo"]['cod_tipo_reformulacion'];
	//echo $this->data["cfpp10_reformulacion_tipo"]['denominacion'];
 	if(!empty($this->data["cfpp10_reformulacion_tipo"]['denominacion'])){
 		if($this->cfpd10_reformulacion_tipo->findBycod_tipo($this->data["cfpp10_reformulacion_tipo"]['cod_tipo_reformulacion'])){
 			$this->set('datos', array());
			$this->set('mensajeError', 'LO SIENTO EL C&Oacute;DIGO YA ESTA EN USO');
			$this->index();
			$this->render("index");
		}else{
			//print_r($this->data);
		$codigo = $this->data["cfpp10_reformulacion_tipo"]['cod_tipo_reformulacion'];
		$denominacion = $this->data["cfpp10_reformulacion_tipo"]['denominacion'];

		$sql ="INSERT INTO cfpd10_reformulacion_tipo (cod_tipo,denominacion) values ('$codigo','".$denominacion."')";
		$x=$this->cfpd10_reformulacion_tipo->execute($sql);
		if($x>1){
			$this->set('mensaje', 'LA INFORMACION FUE REGISTRADA CORRECTAMENTE');
		$this->index();
		$this->render("index");
		}else{
		$this->set('mensajeError','LA INFORMACION NO PUDO SER REGISTRADA');
		$this->index();
		$this->render("index");
		}
		}
 	}else{
 		$this->set('datos', array());
 		$this->set('mensajeError', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
 		$this->index();
		$this->render("index");
 	}
 }


function consulta($pag_num=null){
 	$this->layout ="ajax";
    $data = $this->cfpd10_reformulacion_tipo->findAll(null, null, 'cod_tipo ASC', null, null, null);
    $this->set('datos',$data);
    if($pag_num!=null){
    	$this->set('pagina_actual', $pag_num);
    }
}


function eliminar($cod_tipo_reformulacion=null){
	$this->layout ="ajax";

	if($cod_tipo_reformulacion != null){
		$sql = "DELETE FROM cfpd10_reformulacion_tipo WHERE cod_tipo = ".$cod_tipo_reformulacion;
		if($this->cfpd10_reformulacion_tipo->execute($sql)>0){
			$this->set('tipo', $this->cfpd10_reformulacion_tipo->generateList(null, 'cod_tipo'));
			$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
			$this->set('enable', 'disabled');
			$this->consulta();
			$this->render("consulta");
		}
	}
}


function modificar($cod_tipo_reformulacion=null){
 	$this->layout ="ajax";
 	$this->set('tipo', $this->cfpd10_reformulacion_tipo->generateList(null, 'cod_tipo'));
 	$this->set('datos', $this->cfpd10_reformulacion_tipo->findAll('cod_tipo = '.$cod_tipo_reformulacion));
 	$this->set('Message_existe', 'INGRESE LOS DATOS A MODIFICAR');
}


function guardar_modificar(){
	$this->layout = "ajax";
	//valido la denominacion
	if(!empty($this->data["cfpp10_reformulacion_tipo"]['denominacion'])){

	$a=$this->data["cfpp10_reformulacion_tipo"]['cod_tipo_reformulacion'];
	$b=$this->data["cfpp10_reformulacion_tipo"]['denominacion'];
	$sql3="update cfpd10_reformulacion_tipo set denominacion='$b' where cod_tipo='$a'";
		if($this->cfpd10_reformulacion_tipo->execute($sql3)>0){
		$this->set('Message_existe', 'LOS DATOS FUER&Oacute;N MODIFICADOS CORRECTAMENTE');
		$this->consulta();
	    $this->render("consulta");
		}
	}else{
    	$this->set('datos', array());
 		$this->set('errorMessage', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
		$this->consulta();
        $this->render("consulta");
	}
}


}//fin clase
?>
