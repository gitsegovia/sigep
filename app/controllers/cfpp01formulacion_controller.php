<?php
/*
 * Created on 25/09/2007
 *
 * Miguelangel Cabrera
 */

 class Cfpp01formulacionController extends AppController {

   var $uses = array('cfpd01_formulacion','Usuario', 'cfpd01_formulacion');
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

 function beforeFilter(){
    $this->checkSession();

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$opc = $this->Usuario->findCount($condicion2);

	if($cod_dep == '01'){
		return;
	}else{
 		echo "LO SIENTO - UD. NO TIENE PERMISOS PARA ESTE PROCESO!!";
		exit;
	}
 }

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
         $sql_re .= "cod_inst=".$this->verifica_SS(4);
         return $sql_re;
    }//fin funcion SQLCA

function index($id=null){
    $this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('formulacion', $this->cfpd01_formulacion->findAll());

	//Este ciclo hace una consula antes de agregar para validar si ya el dato fue agregado con anterioridad
	$consulta="select *from cfpd01_formulacion where ".$this->SQLCA();
	if($this->cfpd01_formulacion->execute($consulta)){

		//setea la variable para luego examinarla si existe o no
		$this->set('existe',true);

    if (empty($this->data)){
    	$dato=$this->cfpd01_formulacion->findAll($this->SQLCA());
    	foreach($dato as $dato){
    		$ano_formular=$dato['cfpd01_formulacion']['ano_formular'];
        $activar_formulacion=$dato['cfpd01_formulacion']['activar_formulacion'];
    	}
    	 $this->set('cfpd01_ano_formular',$ano_formular);
       $this->set('cfpd01_activar_formulacion',$activar_formulacion);

    }

	}else{
		$this->set('existe',false);
	}

 }

 function guardar($valor=null){
    $this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano_formular=$this->data['cfpp01_formulacion']['ano_formular'];
  $activar_formulacion=$this->data['cfpp01_formulacion']['activar_formulacion'];
	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);

	$consulta="select *from cfpd01_formulacion";
	$sql="insert into cfpd01_formulacion values ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano_formular','$activar_formulacion')";

		//Si el dato no fue agregado con anterioridad entonces procede a insertarlo
		if($this->cfpd01_formulacion->execute($sql)>1){

			$this->set('errorMessage', 'El Ejercicio Presupuestario Formulado fue Almacenado');
			$this->set('cfpd01_ano_formular',$ano_formular);
      $this->set('cfpd01_activar_formulacion',$activar_formulacion);
    }else{
	   	$this->set('Message_existe', 'El Ejercicio Presupuestario Formulado no fue Almacenado');
	  }

 }

 function modificar () {
 	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano_formular=$this->data['cfpp01_formulacion']['ano_formular'];
  $activar_formulacion=$this->data['cfpp01_formulacion']['activar_formulacion'];

  $this->set('cfpd01_ano_formular',$ano_formular);
  $this->set('cfpd01_activar_formulacion',strtolower($activar_formulacion));

}

function guardar_modificar () {
	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano_formular=$this->data['cfpp01_formulacion']['ano_formular'];
  $activar_formulacion=$this->data['cfpp01_formulacion']['activar_formulacion'];
	$sql="update cfpd01_formulacion set ano_formular='$ano_formular', activar_formulacion='$activar_formulacion' where ".$this->SQLCA();

	//Si el dato no fue agregado con anterioridad entonces procede a insertarlo
		if($this->cfpd01_formulacion->execute($sql)>1){

			$this->set('errorMessage', 'El Ejercicio Presupuestario Formulado fue Actualizado');
			$this->set('cfpd01_formulacion',$ano_formular);

	   }else{
	   	$this->set('Message_existe', 'El Ejercicio Presupuestario Formulado no fue Actualizado');
	   	}
  $this->set('cfpd01_ano_formular',$ano_formular);
  $this->set('cfpd01_activar_formulacion',strtolower($activar_formulacion));
}



 }
?>
