<?php
//cpod05_control_metas
 class Cpop05ControlMetasController extends AppController {
 	var $name = 'cpop05_control_metas';
 	var $uses = array('cpod05_control_metas', 'cpod05_situacion_actual', 'cpod01_proyectos', 'ccfd04_cierre_mes', 'cfpd01_formulacion');
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
 *  Informacion de la tabla cpod05_control_metas
 *
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano integer NOT NULL,
  tipo_proyecto text NOT NULL,
  numero_proyecto integer NOT NULL,
  cod_meta integer NOT NULL,
  descripcion_meta text NOT NULL,
  costo_total_meta numeric(26,2),
  actividad text NOT NULL,
  indicador_resultados text,
  metas_fisicas_1er_trim integer NOT NULL,
  metas_fisicas_2do_trim integer NOT NULL,
  metas_fisicas_3er_trim integer NOT NULL,
  metas_fisicas_4to_trim integer NOT NULL,
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


    $sql="SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.tipo_proyecto, a.numero_proyecto, b.proyectos, a.cod_meta, a.descripcion_meta, a.costo_total_meta, a.actividad, a.indicador_resultados, a.metas_fisicas_1er_trim, a.metas_fisicas_2do_trim, a.metas_fisicas_3er_trim, a.metas_fisicas_4to_trim
          FROM cpod05_control_metas as a
          inner join cpod01_proyectos as b on a.numero_proyecto=b.numero_proyecto and a.tipo_proyecto=b.tipo_proyecto
          where
          a.cod_presi=b.cod_presi and 
          a.cod_entidad=b.cod_entidad and 
          a.cod_tipo_inst=b.cod_tipo_inst and 
          a.cod_inst=b.cod_inst and 
          a.cod_dep=b.cod_dep and 
          a.ano=b.ano";
    $order="order by a.tipo_proyecto ASC, a.numero_proyecto ASC, a.cod_meta ASC";
    $condicion_sql =  "a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$this->verifica_SS(5)." and a.ano=".$ano_formular;
		$datos = $this->cpod05_control_metas->execute($sql." and ".$condicion_sql." ".$order);

  	$this->set('datos', $datos);
		$this->set('transferir', false);
		$this->set('ano', $ano_formular);

  }

  	function proyectos($ano, $tipo_proyecto){

            $vector=array();
            $lista = $this->cpod01_proyectos->findAll($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."' and numero_proyecto IN (SELECT numero_proyecto FROM cpod04_objetivos WHERE ".$this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."')", " numero_proyecto, proyectos","numero_proyecto ASC");
            foreach($lista as $lista1){
                $vector[$lista1['cpod01_proyectos']['numero_proyecto']]=$lista1['cpod01_proyectos']['proyectos'];
            }
            $this->set('vector',$vector);
            $this->set('tipo_proyecto',$tipo_proyecto);
            $this->set('ano', $ano);
    }

 	function guardar() {

 		$this->layout = "ajax";
 		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

/*
 ano integer NOT NULL,
  tipo_proyecto text NOT NULL,
  numero_proyecto integer NOT NULL,
  cod_meta integer NOT NULL,
  descripcion_meta text NOT NULL,
  costo_total_meta numeric(26,2),
  actividad text NOT NULL,
  indicador_resultados text,
  metas_fisicas_1er_trim integer NOT NULL,
  metas_fisicas_2do_trim integer NOT NULL,
  metas_fisicas_3er_trim integer NOT NULL,
  metas_fisicas_4to_trim integer NOT NULL,
 */
		$ano = $this->data['cpod05_control_metas']['ano'];
		//$tipo_proyecto = $this->data['cpod05_control_metas']['tipo_proyecto'];
		$tipo_proyecto = 'GESTION';
		$numero_proyecto = $this->data['cpod05_control_metas']['numero_proyecto'];
		$cod_meta = $this->data['cpod05_control_metas']['cod_meta'];
		$descripcion_meta = $this->data['cpod05_control_metas']['descripcion_meta'];
		$costo_total_meta = $this->Formato1($this->data['cpod05_control_metas']['costo_total_meta']);
		$actividad = $this->data['cpod05_control_metas']['actividad'];
		$indicador_resultados = $this->data['cpod05_control_metas']['indicador_resultados'];
		$metas_fisicas_1er_trim = $this->data['cpod05_control_metas']['metas_fisicas_1er_trim'];
		$metas_fisicas_2do_trim = $this->data['cpod05_control_metas']['metas_fisicas_2do_trim'];
		$metas_fisicas_3er_trim = $this->data['cpod05_control_metas']['metas_fisicas_3er_trim'];
		$metas_fisicas_4to_trim = $this->data['cpod05_control_metas']['metas_fisicas_4to_trim'];
    $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
    if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
    
  		if($cod_meta == 0){
  			/* INSERT */
  			$condicion_sql = $this->SQLCA($ano);
  			if($this->cpod05_control_metas->findCount($condicion_sql) == 0){ // si no existen registros para ese año - primer cargo registrado
  				$sql_insert = "INSERT INTO cpod05_control_metas VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$tipo_proyecto', '$numero_proyecto', 1, '$descripcion_meta', $costo_total_meta, '$actividad', '$indicador_resultados', $metas_fisicas_1er_trim, $metas_fisicas_2do_trim, $metas_fisicas_3er_trim, $metas_fisicas_4to_trim)";

  				if ($this->cpod05_control_metas->execute($sql_insert)>0) {
  					$this->set('Message_existe','Información registrada correctamente');
  				}else{
  			       	$this->set('errorMessage','La información no pudo ser registrada1');
  				}

  			}else{ // si ya existen cargos registrado se consige el numero del cod_meta para su insercion

  				/*$sql_cod_meta = "SELECT (MAX(cod_meta)+1) as numero FROM cpod02_recurso_humano WHERE " . $this->SQLCA($ano);
  				$datos_cod_meta = $this->cpod02_recurso_humano->execute($sql_cod_meta);
  				$cod_meta = $datos_cod_meta['0']['0']['numero'];*/

  				$datos_cod_meta = $this->cpod05_control_metas->find($this->SQLCA($ano),'(MAX(cod_meta)+1) as numero');
  				$cod_meta = $datos_cod_meta[0]['numero'];

  				$sql_insert = "INSERT INTO cpod05_control_metas VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$tipo_proyecto', '$numero_proyecto', $cod_meta, '$descripcion_meta', $costo_total_meta, '$actividad', '$indicador_resultados', $metas_fisicas_1er_trim, $metas_fisicas_2do_trim, $metas_fisicas_3er_trim, $metas_fisicas_4to_trim)";
  				if ($this->cpod05_control_metas->execute($sql_insert)>0) {
  					$this->set('Message_existe','Información registrada correctamente');
  				}else{
  			       	$this->set('errorMessage','La información no pudo ser registrada2');
  				}
  			}
  		}else{
  			/* UPDATE */
    		/*	$sql_update = "UPDATE cpod02_recurso_humano SET numero_cargos = $numero_cargos, denominacion_cargo = '$denominacion_cargo', responsabilidades = '$responsabilidades', remuneracion_mensual = $remuneracion_mensual, situacion_laboral = $situacion_laboral WHERE " . $this->SQLCA($ano) . " and cod_cargo = " . $cod_cargo;
    			if ($this->cpod02_recurso_humano->execute($sql_update)>0) {
    				$this->set('Message_existe','Información actualizada correctamente');
    			}else{
    			}*/
  		    $this->set('errorMessage','La información no pudo ser registrada3');
  		}

    }else{
      $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
    }

		$this->index();
		$this->render('index');
 	}


	function eliminar($cod_meta, $ano){

		$this->layout="ajax";
    $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
    if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
    
  		$condicion_sql= $this->SQLCA($ano)." and cod_meta = $cod_meta";

  		if($this->cpod05_control_metas->execute("DELETE FROM cpod05_control_metas WHERE " . $condicion_sql)>1){
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
/*
	function editar($cod_cargo, $ano) {

 		$this->layout = "ajax";

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

  	}
*/

  	/**
  	 * SITUACION ACTUAL Y SUPUESTOS
  	 */

  	function situacion_actual($ano=null) {

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

		$sql="SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.tipo_proyecto, a.numero_proyecto, b.proyectos, a.situacion_actual, a.supuestos
          FROM cpod05_situacion_actual as a
          inner join cpod01_proyectos as b on a.numero_proyecto=b.numero_proyecto and a.tipo_proyecto=b.tipo_proyecto
          where
          a.cod_presi=b.cod_presi and 
          a.cod_entidad=b.cod_entidad and 
          a.cod_tipo_inst=b.cod_tipo_inst and 
          a.cod_inst=b.cod_inst and 
          a.cod_dep=b.cod_dep and 
          a.ano=b.ano";
    $order="order by a.tipo_proyecto ASC, a.numero_proyecto ASC";
    $condicion_sql =  "a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$this->verifica_SS(5)." and a.ano=".$ano_formular;

    $datos = $this->cpod05_situacion_actual->execute($sql." and ".$condicion_sql." ".$order);

		$this->set('datos', $datos);
		$this->set('transferir', false);
		$this->set('ano', $ano_formular);

 	}

  	function proyectos_situacion_actual($ano, $tipo_proyecto){

            $vector=array();
            $lista = $this->cpod01_proyectos->findAll($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."' and numero_proyecto IN (SELECT numero_proyecto FROM cpod04_objetivos WHERE ".$this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."') AND numero_proyecto NOT IN (SELECT numero_proyecto FROM cpod05_situacion_actual WHERE ".$this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."')", " numero_proyecto, proyectos","numero_proyecto ASC");
            foreach($lista as $lista1){
                $vector[$lista1['cpod01_proyectos']['numero_proyecto']]=$lista1['cpod01_proyectos']['proyectos'];
            }
            $this->set('vector',$vector);
            $this->set('tipo_proyecto',$tipo_proyecto);
            $this->set('ano', $ano);
            $this->render('proyectos');
    }

  	function guardar_situacion_actual() {

 		$this->layout = "ajax";
 		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

/*
 ano integer NOT NULL,
  tipo_proyecto text NOT NULL,
  numero_proyecto integer NOT NULL,
  situacion_actual text NOT NULL,
  supuestos text NOT NULL,
 */
		$ano = $this->data['cpod05_control_metas']['ano'];
		$tipo_proyecto = $this->data['cpod05_control_metas']['tipo_proyecto'];
		$numero_proyecto = $this->data['cpod05_control_metas']['numero_proyecto'];
		$situacion_actual = $this->data['cpod05_control_metas']['situacion_actual'];
		$supuestos = $this->data['cpod05_control_metas']['supuestos'];
    $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
    if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
    
			/* INSERT */
			$condicion_sql = $this->SQLCA($ano);
			$sql_insert = "INSERT INTO cpod05_situacion_actual VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$tipo_proyecto', '$numero_proyecto', '$situacion_actual', '$supuestos')";

			if ($this->cpod05_control_metas->execute($sql_insert)>0) {
				$this->set('Message_existe','Información registrada correctamente');
			}else{
		       	$this->set('errorMessage','La información no pudo ser registrada1');
			}

    }else{
      $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
    }

		$this->situacion_actual();
		$this->render('situacion_actual');
 	}

 	function eliminar_situacion_actual($tipo_proyecto, $numero_proyecto, $ano){

		$this->layout="ajax";
    $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
    if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

  		$condicion_sql= $this->SQLCA($ano)." and tipo_proyecto ='$tipo_proyecto' and numero_proyecto = $numero_proyecto";

  		if($this->cpod05_situacion_actual->execute("DELETE FROM cpod05_situacion_actual WHERE " . $condicion_sql)>1){
  			$this->set('Message_existe','Registro Eliminado con Exito');
  		}else{
  			$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
  		}

    }else{
      $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
    }

		$this->situacion_actual();
		$this->render('situacion_actual');
	}
}
?>
