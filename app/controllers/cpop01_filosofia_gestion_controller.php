<?php
 class Cpop01FilosofiaGestionController extends AppController {
 	var $name = 'cpop01_filosofia_gestion';
 	var $uses = array('cpod01_filosofia_gestion','cfpd01_formulacion');
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
			$sql_re .= "and ano=" . $ano ." ";
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

	/*****
	 **  ACCIONES
	 **/

	function index($editar=null) {

 		$this->layout = "ajax";

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		if($this->cpod01_filosofia_gestion->findCount($this->SQLCA()) != 0){

			$depx = $this->cpod01_filosofia_gestion->findAll($this->SQLCA());
			$mision = $depx[0]['cpod01_filosofia_gestion']['mision'];
			$vision = $depx[0]['cpod01_filosofia_gestion']['vision'];

			$this->set('entidad_federal', $this->Session->read('entidad_federal'));
			$this->set('mision', $mision);
			$this->set('vision', $vision);
			if($editar!=null){
				$this->set('nuevo', "editar");
			}else{
				$this->set('nuevo', "no");
			}

		}else{

			$this->set('entidad_federal', $this->Session->read('entidad_federal'));
			$this->set('mision', '');
			$this->set('vision', '');
			$this->set('nuevo', "si");

		}

  	}



 	function guardar() {

 		$this->layout = "ajax";

 		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

 		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
	 		$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$mision = $this->data['cpop01_filosofia_gestion']['mision'];
			$vision = $this->data['cpop01_filosofia_gestion']['vision'];

			if($this->cpod01_filosofia_gestion->findCount($this->SQLCA()) == 0){
				$sql_insert = "INSERT INTO cpod01_filosofia_gestion VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$mision', '$vision')";

				if ($this->cpod01_filosofia_gestion->execute($sql_insert)>0) {

					$depx = $this->cpod01_filosofia_gestion->findAll($this->SQLCA());
					$mision = $depx[0]['cpod01_filosofia_gestion']['mision'];
					$vision = $depx[0]['cpod01_filosofia_gestion']['vision'];

					$this->set('entidad_federal', $this->Session->read('entidad_federal'));
					$this->set('mision', $mision);
					$this->set('vision', $vision);
					$this->set('nuevo', "no");

					$this->set('Message_existe','Información registrada correctamente');

				}else{
			       	$this->set('errorMessage','La información no pudo ser registrada');
				}

			}else{

				$sql_update = "UPDATE cpod01_filosofia_gestion SET mision = '$mision', vision = '$vision' WHERE " . $this->SQLCA();

				if ($this->cpod01_filosofia_gestion->execute($sql_update)>0) {


					$depx = $this->cpod01_filosofia_gestion->findAll($this->SQLCA());
					$mision = $depx[0]['cpod01_filosofia_gestion']['mision'];
					$vision = $depx[0]['cpod01_filosofia_gestion']['vision'];

					$this->set('entidad_federal', $this->Session->read('entidad_federal'));
					$this->set('mision', $mision);
					$this->set('vision', $vision);
					$this->set('nuevo', "no");

					$this->set('Message_existe','Información actualizada correctamente');
				}else{
			    $this->set('errorMessage','La información no pudo ser actualizada');
				}

			}
		}else{

			$depx = $this->cpod01_filosofia_gestion->findAll($this->SQLCA());
			$mision = $depx[0]['cpod01_filosofia_gestion']['mision'];
			$vision = $depx[0]['cpod01_filosofia_gestion']['vision'];

			$this->set('entidad_federal', $this->Session->read('entidad_federal'));
			$this->set('mision', $mision);
			$this->set('vision', $vision);
			$this->set('nuevo', "no");

			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}
 	}


	function eliminar() {
		$this->layout='ajax';
		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

 		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

			$borrar=true;

	        if($this->cpod01_filosofia_gestion->findCount($this->SQLCA()) != 0){

	            if ($this->cpod01_filosofia_gestion->execute("DELETE FROM cpod01_filosofia_gestion WHERE ".$this->SQLCA())>0) {

					$this->set('Message_existe','La información fu&eacute; eliminada correctamente');
					$borrar=true;

				}else{

					$this->set('errorMessage','La información no pudo ser eliminada');
					$borrar=false;
				}
			}

			if($borrar){

				$this->set('entidad_federal', $this->Session->read('entidad_federal'));
				$this->set('mision', '');
				$this->set('vision', '');
				$this->set('nuevo', "si");

			}else{

				$depx = $this->cpod01_filosofia_gestion->findAll($this->SQLCA());
				$mision = $depx[0]['cpod01_filosofia_gestion']['mision'];
				$vision = $depx[0]['cpod01_filosofia_gestion']['vision'];

				$this->set('entidad_federal', $this->Session->read('entidad_federal'));
				$this->set('mision', $mision);
				$this->set('vision', $vision);
				$this->set('nuevo', "no");

			}
		}else{

			$depx = $this->cpod01_filosofia_gestion->findAll($this->SQLCA());
			$mision = $depx[0]['cpod01_filosofia_gestion']['mision'];
			$vision = $depx[0]['cpod01_filosofia_gestion']['vision'];

			$this->set('entidad_federal', $this->Session->read('entidad_federal'));
			$this->set('mision', $mision);
			$this->set('vision', $vision);
			$this->set('nuevo', "no");

			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}
	}


 }
?>
