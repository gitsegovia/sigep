<?php
 class Cpop02RecursoHumanoController extends AppController {
 	var $name = 'cpop02_recurso_humano';
 	var $uses = array('cpod02_recurso_humano','Cnmd02_empleados_puestos','Cnmd02_obreros_puestos', 'cpod01_proyectos', 'cnmd02_varios_puestos', 'ccfd04_cierre_mes', 'cnmd02_empleados_puestos', 'cfpd01_formulacion');
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


/**
 *  Informacion de la tabla cpod02_recurso_humano
 *
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  // agregar cod_cargo para la identificacion unica de cada cargo
  ano integer NOT NULL,
  numero_cargos integer NOT NULL,
  denominacion_cargo text NOT NULL,
  responsabilidades text NOT NULL,
  remuneracion_mensual numeric(26,2) NOT NULL,
  situacion_laboral integer NOT NULL,
 */

	/*****
	 **  ACCIONES
	 **/

	function index($ano=null) {

 		$this->layout = "ajax";

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

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

		$condicion_sql = $this->SQLCA($ano_formular)." and situacion_laboral in (2,3,5)";

		$datos = $this->cpod02_recurso_humano->findAll($condicion_sql, null, 'cod_cargo ASC', null);

		// $lista = $this->cnmd02_tablas_tipo->generateList(null, $order = 'cod_tabla', $limit = null, '{n}.cnmd02_tablas_tipo.cod_tabla', '{n}.cnmd02_tablas_tipo.denominacion');
		// $this->concatena($lista, 'nomina');

		// $grados = $this->cnmd02_deno_grado->generateList(null, $order = 'grado', $limit = null, '{n}.cnmd02_deno_grado.grado', '{n}.cnmd02_deno_grado.denominacion');
		// $this->concatena($grados, 'grados');
		// $grados_obreros = $this->cnmd02_deno_grado_obrero->generateList(null, $order = 'grado', $limit = null, '{n}.cnmd02_deno_grado_obrero.grado', '{n}.cnmd02_deno_grado_obrero.denominacion');
		// $this->concatena($grados_obreros, 'grados_obreros');
		// $this->concatena($grados, 'grados');
		
		$datos_proyectos = $this->cpod01_proyectos->findCount($this->SQLCA($ano_formular));
		
		$this->set('datos_proyectos', $datos_proyectos);
		$this->set('datos', $datos);
		$this->set('ano', $ano_formular);
  }


 	function guardar() {

 		$this->layout = "ajax";
 		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 				
 		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$ano = $this->data['cpop02_recurso_humano']['ano'];
		$cod_cargo = $this->data['cpop02_recurso_humano']['cod_cargo'];
		$situacion_laboral = $this->data['cpop02_recurso_humano']['situacion_laboral'];
		//$denominacion_cargo = $this->data['cpop02_recurso_humano']['denominacion_cargo'];
		$denominacion_cargo = $this->data['ccfp01_division']['cod_div_contable1'];
		$grado = $this->data['cpop02_recurso_humano']['grado'];
		$paso = $this->data['cpop02_recurso_humano']['paso'];
		$numero_cargos = $this->data['cpop02_recurso_humano']['numero_cargos'];
		$remuneracion_mensual = $this->Formato1($this->data['cpop02_recurso_humano']['remuneracion_mensual']);
		$responsabilidades = "";

		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

			if($cod_cargo == 0){
				/* INSERT */
				$condicion_sql = $this->SQLCA($ano);
				if($this->cpod02_recurso_humano->findCount($condicion_sql) == 0){ // si no existen registros para ese año - primer cargo registrado
					$sql_insert = "INSERT INTO cpod02_recurso_humano VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', 1, $numero_cargos, '$denominacion_cargo', '$responsabilidades', $remuneracion_mensual, '$situacion_laboral',$grado,$paso)";

					if($this->cpod02_recurso_humano->execute($sql_insert)>0) {
						$this->set('Message_existe','Información registrada correctamente');
					}else{
				    	$this->set('errorMessage','La información no pudo ser registrada1');
					}

				}else{ // si ya existen cargos registrado se consige el numero del cod_cargo para su insercion

					/*$sql_cod_cargo = "SELECT (MAX(cod_cargo)+1) as numero FROM cpod02_recurso_humano WHERE " . $this->SQLCA($ano);
					$datos_cod_cargo = $this->cpod02_recurso_humano->execute($sql_cod_cargo);
					$cod_cargo = $datos_cod_cargo['0']['0']['numero'];*/

					$datos_cod_cargo = $this->cpod02_recurso_humano->find($this->SQLCA($ano),'(MAX(cod_cargo)+1) as numero');
					$cod_cargo = $datos_cod_cargo[0]['numero'];

					$sql_insert = "INSERT INTO cpod02_recurso_humano VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', $cod_cargo, $numero_cargos, '$denominacion_cargo', '$responsabilidades', $remuneracion_mensual, '$situacion_laboral',$grado,$paso)";
					
					//var_dump($sql_insert);exit();

					if ($this->cpod02_recurso_humano->execute($sql_insert)>0) {
						$this->set('Message_existe','Información registrada correctamente');
					}else{
				    	$this->set('errorMessage','La información no pudo ser registrada2');
					}
				}
			}else{
				/* UPDATE */
				$sql_update = "UPDATE cpod02_recurso_humano SET numero_cargos = $numero_cargos, denominacion_cargo = '$denominacion_cargo', responsabilidades = '$responsabilidades', remuneracion_mensual = $remuneracion_mensual, situacion_laboral = $situacion_laboral, grado=$grado, paso=$paso WHERE " . $this->SQLCA($ano) . " and cod_cargo = " . $cod_cargo;
				if ($this->cpod02_recurso_humano->execute($sql_update)>0) {
					$this->set('Message_existe','Información actualizada correctamente');
				}else{
			    	$this->set('errorMessage','La información no pudo ser registrada3');
				}
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->index($ano);
		$this->render('index');
 	}


	function eliminar($cod_cargo, $ano){

		$this->layout="ajax";
		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		
		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
		
			$condicion_sql= $this->SQLCA($ano)." and cod_cargo = $cod_cargo";

			if($this->cpod02_recurso_humano->execute("DELETE FROM cpod02_recurso_humano WHERE " . $condicion_sql)>1){
				$this->set('Message_existe','Registro Eliminado con Exito');
			}else{
				$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
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

  function cargos($tipo_cargo){
		$this->layout = "ajax";
		if($tipo_cargo ==2){
			$Lista = $this->Cnmd02_empleados_puestos->generateList(null, 'cod_puesto ASC', null, '{n}.Cnmd02_empleados_puestos.cod_puesto', '{n}.Cnmd02_empleados_puestos.denominacion_clase');
			$this->set('var2', 1);
			$this->concatena($Lista, 'cod_puesto');
		}else if($tipo_cargo == 5){
			$Lista = $this->Cnmd02_obreros_puestos->generateList(null, 'cod_puesto ASC', null, '{n}.Cnmd02_obreros_puestos.cod_puesto', '{n}.Cnmd02_obreros_puestos.titulo_puesto');
			$this->concatena($Lista, 'cod_puesto');
			$this->set('var2', 2);
		}else{
			$Lista = $this->cnmd02_varios_puestos->generateList(null, 'cod_puesto ASC', null, '{n}.cnmd02_varios_puestos.cod_puesto', '{n}.cnmd02_varios_puestos.denominacion_clase');
			$this->concatena($Lista, 'cod_puesto');
			$this->set('var2', 3);
		}

  }

  function cargos_paso($tipo_cargo){
		$this->layout = "ajax";
		if($tipo_cargo ==2){
			$this->set('situacion_laboral', 2);
		}else if($tipo_cargo == 5){
			$this->set('situacion_laboral', 5);
		}else{
			$this->set('situacion_laboral', 3);
		}
  }

  //CONTRATADOS
	function contratados($ano=null) {

		$this->layout = "ajax";

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

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

		$condicion_sql = $this->SQLCA($ano_formular). " and situacion_laboral in (14,15,16)";

		$datos = $this->cpod02_recurso_humano->findAll($condicion_sql, null, 'cod_cargo ASC', null);

		$datos_proyectos = $this->cpod01_proyectos->findCount($this->SQLCA($ano_formular));
		
		$this->set('datos_proyectos', $datos_proyectos);
		$this->set('datos', $datos);
		$this->set('ano', $ano_formular);
	}

	function guardar_contratados() {

 		$this->layout = "ajax";
 		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 				
 		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$ano = $this->data['cpop02_recurso_humano']['ano'];
		$cod_cargo = $this->data['cpop02_recurso_humano']['cod_cargo'];
		$numero_cargos = $this->data['cpop02_recurso_humano']['numero_cargos'];
		$denominacion_cargo = '';
		switch ($this->data['cpop02_recurso_humano']['situacion_laboral']) {
			case '1':
				$denominacion_cargo = '';
				break;
			case '14':
				$denominacion_cargo = 'CONTRATADO ADMINISTRATIVO';
				break;
			case '15':
				$denominacion_cargo = 'CONTRATADO OBRERO';
				break;
			case '16':
				$denominacion_cargo = 'HONORARIOS PROFESIONALES';
				break;
		}

		$responsabilidades = $this->data['cpop02_recurso_humano']['responsabilidades'];
		$remuneracion_mensual = $this->Formato1($this->data['cpop02_recurso_humano']['remuneracion_mensual']);
		$situacion_laboral = $this->data['cpop02_recurso_humano']['situacion_laboral'];

		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

			if($cod_cargo == 0){
				/* INSERT */
				$condicion_sql = $this->SQLCA($ano);
				if($this->cpod02_recurso_humano->findCount($condicion_sql) == 0){ // si no existen registros para ese año - primer cargo registrado
					$sql_insert = "INSERT INTO cpod02_recurso_humano VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', 1, $numero_cargos, '$denominacion_cargo', '$responsabilidades', $remuneracion_mensual, '$situacion_laboral',1,1)";

					if($this->cpod02_recurso_humano->execute($sql_insert)>0) {
						$this->set('Message_existe','Información registrada correctamente');
					}else{
				    $this->set('errorMessage','La información no pudo ser registrada1');
					}

				}else{ // si ya existen cargos registrado se consige el numero del cod_cargo para su insercion

					/*$sql_cod_cargo = "SELECT (MAX(cod_cargo)+1) as numero FROM cpod02_recurso_humano WHERE " . $this->SQLCA($ano);
					$datos_cod_cargo = $this->cpod02_recurso_humano->execute($sql_cod_cargo);
					$cod_cargo = $datos_cod_cargo['0']['0']['numero'];*/

					$datos_cod_cargo = $this->cpod02_recurso_humano->find($this->SQLCA($ano),'(MAX(cod_cargo)+1) as numero');
					$cod_cargo = $datos_cod_cargo[0]['numero'];

					$sql_insert = "INSERT INTO cpod02_recurso_humano VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', $cod_cargo, $numero_cargos, '$denominacion_cargo', '$responsabilidades', $remuneracion_mensual, '$situacion_laboral',1,1)";
					
					if ($this->cpod02_recurso_humano->execute($sql_insert)>0) {
						$this->set('Message_existe','Información registrada correctamente');
					}else{
				    $this->set('errorMessage','La información no pudo ser registrada2');
					}
				}
			}else{
				/* UPDATE */
				$sql_update = "UPDATE cpod02_recurso_humano SET numero_cargos = $numero_cargos, denominacion_cargo = '$denominacion_cargo', responsabilidades = '$responsabilidades', remuneracion_mensual = $remuneracion_mensual, situacion_laboral = $situacion_laboral WHERE " . $this->SQLCA($ano) . " and cod_cargo = " . $cod_cargo;
				if ($this->cpod02_recurso_humano->execute($sql_update)>0) {
					$this->set('Message_existe','Información actualizada correctamente');
				}else{
			    $this->set('errorMessage','La información no pudo ser registrada3');
				}
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->contratados($ano);
		$this->render('contratados');
 	}

	function eliminar_contratados($cod_cargo, $ano){

		$this->layout="ajax";
		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		
		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
		
			$condicion_sql= $this->SQLCA($ano)." and cod_cargo = $cod_cargo";

			if($this->cpod02_recurso_humano->execute("DELETE FROM cpod02_recurso_humano WHERE " . $condicion_sql)>1){
				$this->set('Message_existe','Registro Eliminado con Exito');
			}else{
				$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->contratados();
		$this->render('contratados');
	}

	function editar_contratados($cod_cargo, $ano) {

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

				$condicion_sql_all= $this->SQLCA($ano_formular). " and situacion_laboral in (14,15,16)";

				if($this->cpod02_recurso_humano->findCount($condicion_sql_cargos) != 0){
					$datos_cargo = $this->cpod02_recurso_humano->find($condicion_sql_cargos);
					$datos = $this->cpod02_recurso_humano->findAll($condicion_sql_all, null, 'cod_cargo ASC', null);
					$this->set('consulta',false);
					$this->set('datos', $datos);
					$this->set('transferir', false);
					$this->set('ano', $ano_formular);
					$this->set('datos_cargo', $datos_cargo);
				}else{ // no existen proyectos registrados para ese año
					$this->set('errorMessage','Registro no Encontrado');
					$this->set('transferir', true);
					$this->set('ano', $ano_formular);
					$this->contratados();
					$this->render("contratados");
				}

			}else{
				$this->set('errorMessage','Registro no Pertenece a este año de Formulación');
				$this->contratados();
				$this->render("contratados");
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
			$this->contratados();
			$this->render('contratados');
		}
	}
  //PENSIONADOS Y JUBILADOS
	function pensionados_jubilados($ano=null) {

		$this->layout = "ajax";

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

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

		$condicion_sql = $this->SQLCA($ano_formular)." and  situacion_laboral in (6,7,8,9,10,11,12,13)";

		$datos = $this->cpod02_recurso_humano->findAll($condicion_sql, null, 'cod_cargo ASC', null);

		$datos_proyectos = $this->cpod01_proyectos->findCount($this->SQLCA($ano_formular));
		
		$this->set('datos_proyectos', $datos_proyectos);

		$this->set('datos', $datos);
		$this->set('ano', $ano_formular);
	}

	function guardar_pensionados() {

 		$this->layout = "ajax";
 		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 				
 		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$ano = $this->data['cpop02_recurso_humano']['ano'];
		$cod_cargo = $this->data['cpop02_recurso_humano']['cod_cargo'];
		$numero_cargos = $this->data['cpop02_recurso_humano']['numero_cargos'];
		$denominacion_cargo = '';

		switch ($this->data['cpop02_recurso_humano']['situacion_laboral']) {
			case '1':
				$denominacion_cargo = '';
				break;
			case '6':
				$denominacion_cargo = 'JUBILADO ADMINISTRATIVO';
				break;
			case '7':
				$denominacion_cargo = 'JUBILADO OBRERO';
				break;
			case '8':
				$denominacion_cargo = 'JUBILADO DOCENTE';
				break;
			case '9':
				$denominacion_cargo = 'PENSIONADO OBRERO';
				break;
			case '10':
				$denominacion_cargo = 'PENSIONADO ADMINISTRATIVO';
				break;
			case '11':
				$denominacion_cargo = 'PENSIONADO SOBREVIVIENTE ADMINISTRATIVO';
				break;
			case '12':
				$denominacion_cargo = 'PENSIONADO SOBREVIVIENTE DOCENTE';
				break;
			case '13':
				$denominacion_cargo = 'PENSIONADO SOBREVIVIENTE OBRERO';
				break;
		}

		$remuneracion_mensual = $this->Formato1($this->data['cpop02_recurso_humano']['remuneracion_mensual']);
		$situacion_laboral = $this->data['cpop02_recurso_humano']['situacion_laboral'];
		$responsabilidades="";

		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

			if($cod_cargo == 0){
				/* INSERT */
				$condicion_sql = $this->SQLCA($ano);
				if($this->cpod02_recurso_humano->findCount($condicion_sql) == 0){ // si no existen registros para ese año - primer cargo registrado
					$sql_insert = "INSERT INTO cpod02_recurso_humano VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', 1, $numero_cargos, '$denominacion_cargo', '$responsabilidades', $remuneracion_mensual, '$situacion_laboral',1,1)";

					if($this->cpod02_recurso_humano->execute($sql_insert)>0) {
						$this->set('Message_existe','Información registrada correctamente');
					}else{
				    $this->set('errorMessage','La información no pudo ser registrada1');
					}

				}else{ // si ya existen cargos registrado se consige el numero del cod_cargo para su insercion

					/*$sql_cod_cargo = "SELECT (MAX(cod_cargo)+1) as numero FROM cpod02_recurso_humano WHERE " . $this->SQLCA($ano);
					$datos_cod_cargo = $this->cpod02_recurso_humano->execute($sql_cod_cargo);
					$cod_cargo = $datos_cod_cargo['0']['0']['numero'];*/

					$datos_cod_cargo = $this->cpod02_recurso_humano->find($this->SQLCA($ano),'(MAX(cod_cargo)+1) as numero');
					$cod_cargo = $datos_cod_cargo[0]['numero'];

					$sql_insert = "INSERT INTO cpod02_recurso_humano VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', $cod_cargo, $numero_cargos, '$denominacion_cargo', '$responsabilidades', $remuneracion_mensual, '$situacion_laboral',1,1)";
					
					if ($this->cpod02_recurso_humano->execute($sql_insert)>0) {
						$this->set('Message_existe','Información registrada correctamente');
					}else{
				    $this->set('errorMessage','La información no pudo ser registrada2');
					}
				}
			}else{
				/* UPDATE */
				$sql_update = "UPDATE cpod02_recurso_humano SET numero_cargos = $numero_cargos, denominacion_cargo = '$denominacion_cargo', responsabilidades = '$responsabilidades', remuneracion_mensual = $remuneracion_mensual, situacion_laboral = $situacion_laboral WHERE " . $this->SQLCA($ano) . " and cod_cargo = " . $cod_cargo;
				if ($this->cpod02_recurso_humano->execute($sql_update)>0) {
					$this->set('Message_existe','Información actualizada correctamente');
				}else{
			    $this->set('errorMessage','La información no pudo ser registrada3');
				}
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->pensionados_jubilados($ano);
		$this->render('pensionados_jubilados');
 	}

	function eliminar_pensionados($cod_cargo, $ano){

		$this->layout="ajax";
		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		
		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
		
			$condicion_sql= $this->SQLCA($ano)." and cod_cargo = $cod_cargo";

			if($this->cpod02_recurso_humano->execute("DELETE FROM cpod02_recurso_humano WHERE " . $condicion_sql)>1){
				$this->set('Message_existe','Registro Eliminado con Exito');
			}else{
				$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->pensionados_jubilados();
		$this->render('pensionados_jubilados');
	}

	function editar_pensionados($cod_cargo, $ano) {

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

				$condicion_sql_all= $this->SQLCA($ano_formular). " and situacion_laboral in (6,7,8,9,10,11,12,13)";

				if($this->cpod02_recurso_humano->findCount($condicion_sql_cargos) != 0){
					$datos_cargo = $this->cpod02_recurso_humano->find($condicion_sql_cargos);
					$datos = $this->cpod02_recurso_humano->findAll($condicion_sql_all, null, 'cod_cargo ASC', null);
					$this->set('consulta',false);
					$this->set('datos', $datos);
					$this->set('transferir', false);
					$this->set('ano', $ano_formular);
					$this->set('datos_cargo', $datos_cargo);
				}else{ // no existen proyectos registrados para ese año
					$this->set('errorMessage','Registro no Encontrado');
					$this->set('transferir', true);
					$this->set('ano', $ano_formular);
					$this->pensionados_jubilados();
					$this->render("pensionados_jubilados");
				}

			}else{
				$this->set('errorMessage','Registro no Pertenece a este año de Formulación');
				$this->pensionados_jubilados();
				$this->render("pensionados_jubilados");
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
			$this->pensionados_jubilados();
			$this->render('pensionados_jubilados');
		}
	}

}
?>
