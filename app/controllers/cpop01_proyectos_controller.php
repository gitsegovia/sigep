<?php
 class Cpop01ProyectosController extends AppController {
 	var $name = 'cpop01_proyectos';
 	var $uses = array('cpod01_proyectos', 'ccfd04_cierre_mes', 'cpod01_filosofia_gestion', 'cfpd01_formulacion');
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


/**
 *  Informacion de la tabla cpod01_proyectos
 *
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano integer NOT NULL,
  tipo_proyecto text NOT NULL, -- Tipos de Proyectos: -Estrategicos, -Gestion
  numero_proyecto integer NOT NULL,
  proyectos text NOT NULL,
 */

	/*****
	 **  ACCIONES
	 **/

	function index($tipo_proyecto, $ano=null) {

 		$this->layout = "ajax";

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$tipo_proyecto = strtoupper($tipo_proyecto);

		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);

  	if($ano==null){
			$ano_formular=$year[0]['cfpd01_formulacion']['ano_formular'];
			$this->set('consulta',false);
		}elseif($ano==$year[0]['cfpd01_formulacion']['ano_formular']){
			$ano_formular=$ano;
			$this->set('consulta',false);
		}else{
			$ano_formular=$ano;
			$this->set('consulta',true);
		}

		$condicion_sql= "tipo_proyecto = '$tipo_proyecto' and ".$this->SQLCA($ano_formular);
		$datos_filosofia = $this->cpod01_filosofia_gestion->find($this->SQLCA_Institucion(). " and cod_dep=". $cod_dep);

		if($this->cpod01_proyectos->findCount($condicion_sql) != 0){
			$datos = $this->cpod01_proyectos->findAll($condicion_sql, null, 'numero_proyecto ASC', null);
			$this->set('datos', $datos);
			$this->set('transferir', false);
			$this->set('tipo_proyecto',$tipo_proyecto);
			$this->set('proyecto','');
			$this->set('ano', $ano_formular);
			$this->set('datos_filosofia', $datos_filosofia);
		}else{ // no existen proyectos registrados para ese año
			$this->set('transferir', true);
			$this->set('tipo_proyecto',$tipo_proyecto);
			$this->set('proyecto','');
			$this->set('ano', $ano_formular);
			$this->set('datos_filosofia', $datos_filosofia);
		}
  }


 	function guardar() {

 		$this->layout = "ajax";
 		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);


	 		$this->set('proyecto', '');
	 		$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$ano = $this->data['cpop01_proyectos']['ano'];
			$tipo_proyecto = $this->data['cpop01_proyectos']['tipo_proyecto'];
			$proyecto = $this->data['cpop01_proyectos']['proyecto'];
			$responsable = $this->data['cpop01_proyectos']['responsable'];
			$numero_proyecto = $this->data['cpop01_proyectos']['numero_proyecto'];

 		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

			if($numero_proyecto == 0){
				/* INSERT */
				$condicion_sql = $this->SQLCA($ano) . " and tipo_proyecto = '" . $tipo_proyecto ."'";
				if($this->cpod01_proyectos->findCount($condicion_sql) == 0){
					$sql_insert = "INSERT INTO cpod01_proyectos VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$tipo_proyecto', 1, '$proyecto', '$responsable')";
					if ($this->cpod01_proyectos->execute($sql_insert)>0) {
						$this->set('Message_existe','Información registrada correctamente');
					}else{
				       	$this->set('errorMessage','La información no pudo ser registrada');
					}

				}else{

					$sql_numero_proyecto = "SELECT (MAX(numero_proyecto)+1) as numero FROM cpod01_proyectos WHERE " . $this->SQLCA($ano) . " and tipo_proyecto = '" . $tipo_proyecto . "'";
					$numero_proyecto = $this->cpod01_proyectos->execute($sql_numero_proyecto);

					$sql_insert = "INSERT INTO cpod01_proyectos VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$tipo_proyecto', " . $numero_proyecto['0']['0']['numero'] . ", '$proyecto', '$responsable')";
					if ($this->cpod01_proyectos->execute($sql_insert)>0) {
						$this->set('Message_existe','Información registrada correctamente');
					}else{
				     $this->set('errorMessage','La información no pudo ser registrada');
					}
				}
			}
		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->index($tipo_proyecto);
		$this->render('index');
 	}

 	function actualizar() {

 		$this->layout = "ajax";
 		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);


			$ano = $this->data['cpop01_proyectos']['ano'];
			$tipo_proyecto = $this->data['cpop01_proyectos']['tipo_proyecto'];
			$proyecto = $this->data['cpop01_proyectos']['proyecto'];
			$responsable = $this->data['cpop01_proyectos']['responsable'];
			$numero_proyecto = $this->data['cpop01_proyectos']['numero_proyecto'];

 		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
				/* UPDATE */
				$sql_update = "UPDATE cpod01_proyectos SET proyectos = '$proyecto', responsable = '$responsable' WHERE " . $this->SQLCA($ano) . " and tipo_proyecto = '" . $tipo_proyecto . "' and numero_proyecto = " . $numero_proyecto;

				if ($this->cpod01_proyectos->execute($sql_update)>0) {
					$this->set('Message_existe','Información registrada correctamente');
				}else{
			       	$this->set('errorMessage','La información no pudo ser registrada');
				}
		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->index($tipo_proyecto);
		$this->render('index');
 	}


	function eliminar($tipo_proyecto, $numero_proyecto, $ano){

		$this->layout="ajax";
		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

			$condicion_sql= $this->SQLCA($ano)." and tipo_proyecto = '$tipo_proyecto' and numero_proyecto = '$numero_proyecto'";

			if($this->cpod01_proyectos->execute("DELETE FROM cpod01_proyectos WHERE " . $condicion_sql)>1){
				$this->set('Message_existe','Registro Eliminado con Exito');
			}else{
				$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->index($tipo_proyecto);
		$this->render('index');
	}

	function editar($tipo_proyecto, $numero_proyecto, $ano) {

 		$this->layout = "ajax";
 		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		
		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
		
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$tipo_proyecto = strtoupper($tipo_proyecto);

			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
			$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);

	  	if($ano==$year[0]['cfpd01_formulacion']['ano_formular']){

				$ano_formular=$ano;

				$condicion_sql_proyecto= $this->SQLCA($ano_formular). " and tipo_proyecto='$tipo_proyecto' and numero_proyecto =" . $numero_proyecto;

				$condicion_sql_all= "tipo_proyecto = '$tipo_proyecto' and ".$this->SQLCA($ano_formular);

				if($this->cpod01_proyectos->findCount($condicion_sql_proyecto) != 0){
					$datos_proyecto = $this->cpod01_proyectos->find($condicion_sql_proyecto);
					$datos = $this->cpod01_proyectos->findAll($condicion_sql_all, null, 'numero_proyecto ASC', null);

					$this->set('datos', $datos);
					$this->set('transferir', false);
					$this->set('tipo_proyecto',$tipo_proyecto);
					$this->set('proyecto', $datos_proyecto);
					$this->set('consulta', false);

				}else{ // no existen proyectos registrados para ese año
					$this->set('Message_existe','Registro no Encontrado');
					$this->index($tipo_proyecto);
					$this->render('index');
				}

			}else{
				$this->index($tipo_proyecto);
				$this->render("index");
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
			$this->index($tipo_proyecto);
			$this->render('index');
		}

  }

}
?>
