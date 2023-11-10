<?php
/*
 * Creado el 03/11/2007 a las 04:59:38 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 */
 class Cnmp06ParentescoController extends AppController {
 	var $name = 'cnmp06_parentesco';
 	var $uses = array ('cnmd06_parentesco');
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

	$parentesco = $this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion');
	$parentesco = $parentesco != null ? $parentesco : array();
	$this->concatena($parentesco, 'tipo');
	$this->data["cnmp06_parentesco"]["denominacion"]=null;

	//verifico la dependencia para activar los botones
 	if($this->verifica_SS(5) == 1){
 		$this->set('enable', 'disabled');
 		$this->set('enable_guardar', 'enable');
 	}else{
 		$this->set('enable', 'disabled');
 		$this->set('enable_guardar', 'disabled');
 	}
 }



 function selec_tipo($var = null){
 	$this->layout ="ajax";
  	$this->set('action', $var);

 	$parentesco = $this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion');
	$parentesco = $parentesco != null ? $parentesco : array();
	$this->concatena($parentesco, 'tipo');
 	if($var != 'otros'){
		$this->set('datos', $this->cnmd06_parentesco->findAll('cod_parentesco = '.$var));
 	}else{
 		$this->data['cnmp06_parentesco'] = array();
 	}

	//verifico la dependencia para activar los botones
	if($this->verifica_SS(5) == 1){
 		$this->set('enable', 'enable');
 		$this->set('enable_guardar', 'enable');
 	}else{
 		$this->set('enable', 'disabled');
 		$this->set('enable_guardar', 'disabled');
 	}
 }



 function guardar(){
 	$this->layout ="ajax";

 	if(!empty($this->data['cnmp06_parentesco']['denominacion'])){
 		if($this->cnmd06_parentesco->findBydenominacion($this->data['cnmp06_parentesco']['denominacion'])){
 			$this->set('datos', array());
			$this->set('mensajeError', 'EL PARENTESCO YA EXISTE');
			$this->index();
			$this->render("index");
		}else{
		$denominacion = $this->data['cnmp06_parentesco']['denominacion'];
		$sql ="INSERT INTO cnmd06_parentesco (denominacion)";
		$sql .= " values ('".$denominacion."')";
		$this->cnmd06_parentesco->execute($sql);
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



 function eliminar($cod_parentesco){
	$this->layout ="ajax";

	if($cod_parentesco != null){
		$sql = "DELETE FROM cnmd06_parentesco WHERE cod_parentesco = ".$cod_parentesco;
		$this->cnmd06_parentesco->execute($sql);
		$this->set('tipo', $this->cnmd06_parentesco->generateList(null, 'cod_parentesco'));
		$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		$this->set('enable', 'disabled');
		$this->consulta();
		$this->render("consulta");
	}
 }



 function consulta($pag_num=null){
 	$this->layout ="ajax";

    $data = $this->cnmd06_parentesco->findAll(null, null, 'cod_parentesco ASC', null, null, null);
    $this->set('datos',$data);

    if($pag_num!=null){
    	$this->set('pagina_actual', $pag_num);
    }

	//verifico la dependencia para activar los botones
    if($this->verifica_SS(5) == 1){
 		$this->set('enable', 'enable');
 	}else{
 		$this->set('enable', 'disabled');
 	}
 }



 function modificar($cod_parentesco = null){
 	$this->layout ="ajax";
 	$this->set('tipo', $this->cnmd06_parentesco->generateList(null, 'cod_parentesco'));
 	$this->set('datos', $this->cnmd06_parentesco->findAll('cod_parentesco = '.$cod_parentesco));
 	$this->set('Message_existe', 'INGRESE LOS DATOS A MODIFICAR');
 }



function guardar_modificar(){
	$this->layout = "ajax";
	//valido la denominacion
	if(!empty($this->data['cnmp06_parentesco']['denominacion'])){

	 $a=$this->data['cnmp06_parentesco']['denominacion'];
	 $b=$this->data['cnmp06_parentesco']['cod_parentesco'];

	    $sql3="update cnmd06_parentesco set denominacion='$a' where cod_parentesco=$b";
		$this->set('Message_existe', 'EL PARENTESCO FUE MODIFICADO CORRECTAMENTE');

	   	$this->cnmd06_parentesco->execute($sql3);
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