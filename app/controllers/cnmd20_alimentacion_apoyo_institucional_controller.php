<?php
 class Cnmd20AlimentacionApoyoInstitucionalController extends AppController {
 	var $name = 'cnmd20_alimentacion_apoyo_institucional';
 	var $uses = array('cpod02_recurso_humano', 'ccfd04_cierre_mes', 'cfpd01_formulacion');
 	var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf','Form','Fck');

	function checkSession(){
		if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
		}else{
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

		return;
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
			default: return "NULO";
		}
	}

	function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
		$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
		$sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
		$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
		$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
		$sql_re .= "cod_dep=".$this->verifica_SS(5). " ";

		if($ano!=null){
			$sql_re .= "and ano=" . $ano;
		}

		return $sql_re;
	}

	function SQLCA_Institucion($ano=null){//sql para busqueda de codigos de arranque con y sin año
		$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
		$sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
		$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
		$sql_re .= "cod_inst=".$this->verifica_SS(4)." ";

		if($ano!=null){
			$sql_re .= " and ano=".$ano."  ";
		}
		return $sql_re;
	}


	function index($ano=null) {

 		$this->layout = "ajax";

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$condicion = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst. " and cod_dep=".$cod_dep;
		
		$sql = "SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, primer_nombre || ' ' || segundo_nombre || ' ' || primer_apellido || ' ' || segundo_apellido as nombre_completo, cedula_identidad, funcion FROM cnmd20_alimentacion_apoyo_institucional WHERE ".$condicion." ORDER BY cedula_identidad ASC;";
			
  	$datos = $this->cpod02_recurso_humano->execute($sql);

  	$condicion_activo = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst;
	
		$sql_activo = "SELECT * FROM cnmd20_activar_registro WHERE ".$condicion_activo;
			
		$datos_activo = $this->cpod02_recurso_humano->execute($sql_activo);
		
		if(strtolower($datos_activo[0][0]['activar_registro'])=='t'){
	 	 $this->set('activo_guardar',true);
		}

		$this->set('datos', $datos);
  }


 	function guardar() {

 		$this->layout = "ajax";
 				
 		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$cedula_identidad = $this->data['cnmd20_alimentacion_apoyo_institucional']['cedula_identidad'];
		$primer_nombre = $this->data['cnmd20_alimentacion_apoyo_institucional']['primer_nombre'];
		$segundo_nombre = $this->data['cnmd20_alimentacion_apoyo_institucional']['segundo_nombre'];
		$primer_apellido = $this->data['cnmd20_alimentacion_apoyo_institucional']['primer_apellido'];
		$segundo_apellido = $this->data['cnmd20_alimentacion_apoyo_institucional']['segundo_apellido'];
		$funcion = $this->data['cnmd20_alimentacion_apoyo_institucional']['funcion'];

		$condicion_find = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst. " and cod_dep=".$cod_dep." and cedula_identidad=".$cedula_identidad;
		
		$sql_find = "SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, primer_nombre || ' ' || segundo_nombre || ' ' || primer_apellido || ' ' || segundo_apellido as nombre_completo, cedula_identidad, funcion FROM cnmd20_alimentacion_apoyo_institucional WHERE ".$condicion_find." ORDER BY cedula_identidad ASC;";
			
  	$consulta_usuario = $this->cpod02_recurso_humano->execute($sql_find);		

		if(count($consulta_usuario) == 0){
			/* INSERT */
			$sql_insert = "INSERT INTO cnmd20_alimentacion_apoyo_institucional VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cedula_identidad', 'V', '$primer_nombre', '$segundo_nombre', '$primer_apellido', '$segundo_apellido', '$funcion')";

			if($this->cpod02_recurso_humano->execute($sql_insert)>0) {
				$this->set('Message_existe','Información registrada correctamente');
			}else{
		    $this->set('errorMessage','La información no pudo ser registrada');
			}

		}else{ 
			$this->set('errorMessage','Esta persona ya se encuentra registra en otra institución.');
		}			
  	
  	$this->index();
		$this->render('index');
 	}


	function eliminar($cedula_identidad){

		$this->layout="ajax";
		
		$condicion_sql= $this->SQLCA($ano)." and cod_cargo = $cod_cargo";

		if($this->cpod02_recurso_humano->execute("DELETE FROM cnmd20_alimentacion_apoyo_institucional WHERE cedula_identidad=".$cedula_identidad)>1){
			$this->set('Message_existe','Registro Eliminado con Exito');
		}else{
			$this->set('errorMessage', 'El Registro no se pudo eliminar');
		}

		$this->index();
		$this->render('index');
	}

	function editar($cod_cargo, $ano) {

 		$this->layout = "ajax";
 		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		
		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
			$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);

	  	if($ano==$year[0]['cfpd01_formulacion']['ano_formular']){

				$ano_formular=$ano;

				$condicion_sql_cargos= $this->SQLCA($ano_formular). " and cod_cargo =" . $cod_cargo;

				$condicion_sql_all= $this->SQLCA($ano_formular);

				if($this->cpod02_recurso_humano->findCount($condicion_sql_cargos) != 0){
					$datos_cargo = $this->cpod02_recurso_humano->find($condicion_sql_cargos);
					$datos = $this->cpod02_recurso_humano->findAll($condicion_sql_all, null, 'cod_cargo ASC', null);
					$this->set('consulta',false);
					$this->set('datos', $datos);
					$this->set('transferir', false);
					$this->set('ano', $ano_formular);
					$this->set('datos_cargo', $datos_cargo);
				}else{ // no existen proyectos registrados para ese año
					$this->set('Message_existe','Registro no Encontrado');
					$this->set('transferir', true);
					$this->set('ano', $ano_formular);
				}

			}else{
				$this->set('Message_existe','Registro no Pertenece a este año de Formulación');
				$this->index();
				$this->render("index");
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
			$this->index();
			$this->render('index');
		}

  }

  function activar () {
 	$this->layout = "ajax";

 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$condicion = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst;
	
	$sql = "SELECT * FROM cnmd20_activar_registro WHERE ".$condicion;
		
	$datos = $this->cpod02_recurso_humano->execute($sql);

  $this->set('cnmd20_activar_registro',strtolower($datos[0][0]['activar_registro']));

}

function guardar_activar () {
	$this->layout = "ajax";
	
  $activar_registro=$this->data['cnmd20_alimentacion_apoyo_institucional']['activar_registro'];
	$sql="UPDATE cnmd20_activar_registro SET activar_registro='$activar_registro' WHERE ".$this->SQLCA_Institucion();

	if($this->cpod02_recurso_humano->execute($sql)>1){
		$this->set('errorMessage', 'El registro fue Actualizado');
  }else{
   	$this->set('Message_existe', 'El registro no fue Actualizado');
 	}

 	$this->activar();
 	$this->render('activar');

}

}
?>
