<?php

class Cpop00ReportesController extends AppController {

	var $name = 'cpop00_reportes';
	var $uses = array('arrd05','cpod01_filosofia_gestion', 'cpod01_proyectos', 'cpod02_recurso_humano', 'cpod03_organigrama', 'cpod04_objetivos', 'cpod04_problemas_areas_gestion', 'cpod05_control_metas', 'cpod05_situacion_actual', 'cpod06_vinculacion_presupuesto', 'cpod06_distribucion_ingresos_propios','ccfd04_cierre_mes', 'cfpd01_formulacion', 'cfpd02_activ_obra', 'cfpd01_sub_espec');
	var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap', 'Fpdf');

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

		$cod_presi = $this->Session->read('SScodpresi0');
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

	function reporte_formato_1($pdf=false){

        if(!$pdf){

            $this->layout = "ajax";

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
            $this->set('cod_dep',$this->Session->read('SScoddep'));
             $this->set('select_dependencia',$this->data['reporte_formato_poai_1']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }
             
        }else{

            $this->layout = "pdf";

            $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }
          

            $datos_filosofia = $this->cpod01_filosofia_gestion->find($this->SQLCA_Institucion(). " and cod_dep=". $cod_dep_set);
            $datos_proyectos_estrategicos = $this->cpod01_proyectos->findAll("tipo_proyecto = 'ESTRATEGICO' and ".$this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano']). " and cod_dep=".$cod_dep_set, 'proyectos', 'numero_proyecto ASC', null);
            $datos_proyectos_gestion = $this->cpod01_proyectos->findAll("tipo_proyecto = 'GESTION' and ".$this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano']). " and cod_dep=". $cod_dep_set, 'proyectos', 'numero_proyecto ASC', null);

            $this->set('pdf',$pdf);
            $this->set('datos_filosofia',$datos_filosofia);
            $this->set('datos_proyectos_estrategicos',$datos_proyectos_estrategicos);
            $this->set('datos_proyectos_gestion',$datos_proyectos_gestion);
            $this->set('ano',$this->data['reporte_formato_poai_1']['ano']);
            $this->set('select_dependencia',$this->data['reporte_formato_poai_1']['select_dependencia']);
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
           // var_dump($name_dep[0][0]['denominacion']);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
             $this->set('cod_dep',$this->Session->read('SScoddep'));
            //exit();

        }
    }

    function reporte_formato_2($pdf = null) {
        if(!$pdf){

            $this->layout = 'ajax';

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);

              $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_2']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_2']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_2']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }


            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
            $this->set('cod_dep',$this->Session->read('SScoddep'));

        }else{
            $this->layout = "pdf";

            $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_2']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_2']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_2']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }
          

            $cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$cod_dep_set;
            $datos_organigrama = $this->cpod03_organigrama->find($this->SQLCA_Institucion($this->data['reporte_formato_poai_2']['ano']) . " and cod_dep=". $cod_dep_set);
           // $datos_organigrama = $this->cpod03_organigrama->find($this->SQLCA($this->data['reporte_formato_poai_2']['ano']));

            $this->set('pdf',$pdf);
            $this->set('ano',$this->data['reporte_formato_poai_2']['ano']);
            $this->set('cod_dep',$cod_dep_set);
            $this->set('cod_imagen', $cod_imagen);
            $this->set('datos_organigrama', $datos_organigrama);
           $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$this->data['reporte_formato_poai_2']['select_dependencia']);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
        }
    }

    function reporte_formato_3($pdf=false){

        if(!$pdf){

            $this->layout = "ajax";

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
            

            $this->set('cod_dep',$this->Session->read('SScoddep'));
             $this->set('select_dependencia',$this->data['reporte_formato_poai_3']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

        }else{

            $this->layout = "pdf";
             $cod_dep_set=0;
             
            if (isset($this->data['reporte_formato_poai_3']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_3']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_3']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }
            //$datos_proyectos_gestion = $this->cpod01_proyectos->findAll("tipo_proyecto = 'GESTION' and ".$this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano']). " and cod_dep=". $cod_dep_set, 'proyectos', 'numero_proyecto ASC', null);

            //$datos_recurso_humano = $this->cpod02_recurso_humano->findAll($this->SQLCA($this->data['reporte_formato_poai_3']['ano']), 'poner datos a mostrar con CASE para situacion laboral', 'numero_proyecto ASC', null);
            $datos_recurso_humano = $this->cpod02_recurso_humano->findAll($this->SQLCA_Institucion($this->data['reporte_formato_poai_3']['ano'])." and cod_dep=". $cod_dep_set." and situacion_laboral in (2,3,5)", null, 'cod_cargo ASC', null);
            

            $this->set('pdf',$pdf);
            $this->set('datos',$datos_recurso_humano);
            $this->set('ano',$this->data['reporte_formato_poai_3']['ano']);
            //$this->set('nombre_dependencia',$this->verifica_SS(6));
            $this->set('select_dependencia',$this->data['reporte_formato_poai_3']['select_dependencia']);
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            /*var_dump($name_dep[0][0]['denominacion']);
            exit();*/
           // var_dump($name_dep[0][0]['denominacion']);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
             $this->set('cod_dep',$this->Session->read('SScoddep'));

            //exit();
        }
    }

    function reporte_formato_3_contratados($pdf=false){

        if(!$pdf){

            $this->layout = "ajax";

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
            

            $this->set('cod_dep',$this->Session->read('SScoddep'));
             $this->set('select_dependencia',$this->data['reporte_formato_poai_3']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

        }else{

            $this->layout = "pdf";
             $cod_dep_set=0;
             
            if (isset($this->data['reporte_formato_poai_3']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_3']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_3']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }
            //$datos_proyectos_gestion = $this->cpod01_proyectos->findAll("tipo_proyecto = 'GESTION' and ".$this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano']). " and cod_dep=". $cod_dep_set, 'proyectos', 'numero_proyecto ASC', null);

            //$datos_recurso_humano = $this->cpod02_recurso_humano->findAll($this->SQLCA($this->data['reporte_formato_poai_3']['ano']), 'poner datos a mostrar con CASE para situacion laboral', 'numero_proyecto ASC', null);
            $datos_recurso_humano_contratados = $this->cpod02_recurso_humano->findAll($this->SQLCA_Institucion($this->data['reporte_formato_poai_3']['ano'])." and cod_dep=". $cod_dep_set." and situacion_laboral in (14,15,16)", null, 'cod_cargo ASC', null);
           
            $this->set('pdf',$pdf);
            $this->set('datos_contratados',$datos_recurso_humano_contratados);
            $this->set('ano',$this->data['reporte_formato_poai_3']['ano']);
            //$this->set('nombre_dependencia',$this->verifica_SS(6));
            $this->set('select_dependencia',$this->data['reporte_formato_poai_3']['select_dependencia']);
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            /*var_dump($name_dep[0][0]['denominacion']);
            exit();*/
           // var_dump($name_dep[0][0]['denominacion']);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
             $this->set('cod_dep',$this->Session->read('SScoddep'));

            //exit();
        }
    }

    function reporte_formato_3_pensionados($pdf=false){

        if(!$pdf){

            $this->layout = "ajax";

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
            

            $this->set('cod_dep',$this->Session->read('SScoddep'));
             $this->set('select_dependencia',$this->data['reporte_formato_poai_3']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

        }else{

            $this->layout = "pdf";
             $cod_dep_set=0;
             
            if (isset($this->data['reporte_formato_poai_3']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_3']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_3']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }
            //$datos_proyectos_gestion = $this->cpod01_proyectos->findAll("tipo_proyecto = 'GESTION' and ".$this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano']). " and cod_dep=". $cod_dep_set, 'proyectos', 'numero_proyecto ASC', null);

            //$datos_recurso_humano = $this->cpod02_recurso_humano->findAll($this->SQLCA($this->data['reporte_formato_poai_3']['ano']), 'poner datos a mostrar con CASE para situacion laboral', 'numero_proyecto ASC', null);
            $datos_recurso_humano_pensionados = $this->cpod02_recurso_humano->findAll($this->SQLCA_Institucion($this->data['reporte_formato_poai_3']['ano'])." and cod_dep=". $cod_dep_set." and situacion_laboral in (6,7,8,9,10,11,12,13)", null, 'cod_cargo ASC', null);


            $this->set('pdf',$pdf);
            $this->set('datos_pensionados',$datos_recurso_humano_pensionados);
            $this->set('ano',$this->data['reporte_formato_poai_3']['ano']);
            //$this->set('nombre_dependencia',$this->verifica_SS(6));
            $this->set('select_dependencia',$this->data['reporte_formato_poai_3']['select_dependencia']);
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            /*var_dump($name_dep[0][0]['denominacion']);
            exit();*/
           // var_dump($name_dep[0][0]['denominacion']);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
             $this->set('cod_dep',$this->Session->read('SScoddep'));

            //exit();
        }
    }

    function reporte_formato_4($pdf = null) {
    	if(!$pdf){

            $this->layout = 'ajax';

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            
            $this->set('cod_dep',$this->Session->read('SScoddep'));
            $this->set('select_dependencia',$this->data['reporte_formato_poai_4']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }
             

        }else{
            $this->layout = "pdf";
             $cod_dep_set=0;
             
            if (isset($this->data['reporte_formato_poai_4']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_4']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_4']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }

            //$year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

/*
ano integer NOT NULL,
  tipo_proyecto text, -- Tipos de Proyectos: -Estrategicos, -Gestion
  numero_proyecto integer,
  numero_objetivo integer NOT NULL,
  objetivo text,
 */

/*
ano integer NOT NULL,
  numero_objetivo integer,
  tipo_problema_area_gestion character varying(100) NOT NULL, -- Tipos: -Problemas. -Gestion (Areas de Gestion).
  numero_problema_area_gestion integer NOT NULL,
  problema_area_gestion text,
 */

            $datos_objetivos = $this->cpod04_objetivos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'numero_objetivo ASC', null);
            $datos_problemas = $this->cpod04_problemas_areas_gestion->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'numero_objetivo ASC, numero_problema_area_gestion ASC', null);
            
  /*var_dump($datos_problemas);
  exit();*/

            $this->set('datos_objetivos',$datos_objetivos);
            $this->set('datos_problemas',$datos_problemas);
            $this->set('pdf',$pdf);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            

            $this->set('select_dependencia',$this->data['reporte_formato_poai_3']['select_dependencia']);
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
            $this->set('cod_dep',$this->Session->read('SScoddep'));
        }
    }

    function reporte_formato_5($pdf = null) {
    	if(!$pdf){

            $this->layout = 'ajax';

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
            $this->set('cod_dep',$this->Session->read('SScoddep'));
            $this->set('select_dependencia',$this->data['reporte_formato_poai_5']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

        }else{
            $this->layout = "pdf";
             $cod_dep_set=0;
             
            if (isset($this->data['reporte_formato_poai_5']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_5']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_5']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

            $datos_proyectos = $this->cpod01_proyectos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);

            $datos_objetivos = $this->cpod04_objetivos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);

            $datos_metas = $this->cpod05_control_metas->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set."",null, 'tipo_proyecto ASC, numero_proyecto ASC, cod_meta ASC', null);

            $datos_situacion_actual = $this->cpod05_situacion_actual->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set."",null, 'tipo_proyecto ASC, numero_proyecto ASC', null);

            $totales_gobernacion = $this->cpod05_situacion_actual->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,
                    tipo_proyecto, numero_proyecto, SUM(monto) as total
                  FROM cpod06_vinculacion_presupuesto
                  where grupo=4 and ".$this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set."
                  group by cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,
                       tipo_proyecto, numero_proyecto;");

            $totales_propios = $this->cpod05_situacion_actual->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,
                       tipo_proyecto, numero_proyecto, SUM(monto) as total
                  FROM cpod06_distribucion_ingresos_propios
                  WHERE ".$this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set."
                  group by cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,
                       tipo_proyecto, numero_proyecto;");


            $this->set('select_dependencia',$this->data['reporte_formato_poai_5']['select_dependencia']);
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            $this->set('cod_dep',$this->Session->read('SScoddep'));


            if(!empty($datos_situacion_actual)){
                $this->set('datos_proyectos',$datos_proyectos);
                $this->set('datos_objetivos',$datos_objetivos);
                $this->set('datos_metas',$datos_metas);
                $this->set('datos_situacion_actual',$datos_situacion_actual);
                $this->set('totales_gobernacion', $totales_gobernacion);
                $this->set('totales_propios', $totales_propios);
                $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
                $this->set('pdf',$pdf);
                $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
            }else{

                $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
                $this->set('pdf',$pdf);
                $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
                $this->set('errorMessage','Debe Registrar la situacion actual y el supuesto');
            }
            
        }
    }

    function reporte_formato_6($pdf = null) {
    	if(!$pdf){

            $this->layout = 'ajax';

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);

             $this->set('cod_dep',$this->Session->read('SScoddep'));
            $this->set('select_dependencia',$this->data['reporte_formato_poai_6']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

        }else{
            $this->layout = "pdf";
            
            $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_6']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_6']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_6']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

            $cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']-1) ." and cod_dep=". $cod_dep_set);
           // $cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA($year[0]['cfpd01_formulacion']['ano_formular']));
/*var_dump($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) ." and cod_dep=". $cod_dep_set);
exit();*/
            $cod_sector = $cfpp02['cfpd02_activ_obra']['cod_sector'];
            $cod_programa = $cfpp02['cfpd02_activ_obra']['cod_programa'];
            $cod_sub_prog = $cfpp02['cfpd02_activ_obra']['cod_sub_prog'];
            $cod_proyecto = $cfpp02['cfpd02_activ_obra']['cod_proyecto'];
            $cod_activ_obra = $cfpp02['cfpd02_activ_obra']['cod_activ_obra'];


            
//$this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set
            $condicion_categoria_sql = "cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and grupo=4";
            $datos_proyectos = $this->cpod01_proyectos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);
            $datos_presupuesto = $this->cpod06_vinculacion_presupuesto->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) ." and cod_dep=". $cod_dep_set ." and ". $condicion_categoria_sql);
/*
var_dump($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) ." and cod_dep=". $cod_dep_set ." and ". $condicion_categoria_sql);
exit();*/
            $concepto_partidas = $this->cfpd01_sub_espec->findAll("cod_grupo=4");

            $this->set('datos_proyectos', $datos_proyectos);
            $this->set('datos_presupuesto', $datos_presupuesto);
            $this->set('concepto_partidas',$concepto_partidas);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
            
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
            $this->set('cod_dep',$this->Session->read('SScoddep'));
        }
    }

    function reporte_formato_7($pdf = null) {
    	if(!$pdf){

            $this->layout = 'ajax';
            $this->verifica_entrada('124');
            $this->set('cod_dep',$this->Session->read('SScoddep'));
            $this->set('select_dependencia',$this->data['reporte_formato_poai_7']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
            $this->set('cod_dep',$this->Session->read('SScoddep'));

        }else{
            $this->layout = "pdf";

            $this->set('cod_dep',$this->Session->read('SScoddep'));
            $this->set('select_dependencia',$this->data['reporte_formato_poai_7']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

            $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_7']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_7']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_7']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

            $cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']-1) ." and cod_dep=". $cod_dep_set);
            //$cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA($year[0]['cfpd01_formulacion']['ano_formular']));
            $cod_sector = $cfpp02['cfpd02_activ_obra']['cod_sector'];
            $cod_programa = $cfpp02['cfpd02_activ_obra']['cod_programa'];
            $cod_sub_prog = $cfpp02['cfpd02_activ_obra']['cod_sub_prog'];
            $cod_proyecto = $cfpp02['cfpd02_activ_obra']['cod_proyecto'];
            $cod_activ_obra = $cfpp02['cfpd02_activ_obra']['cod_activ_obra'];


            $condicion_categoria_sql = "cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and grupo=3";



            $datos_proyectos = $this->cpod01_proyectos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) ." and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);

          
            $datos_presupuesto = $this->cpod06_vinculacion_presupuesto->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) . " and cod_dep=". $cod_dep_set ." and ". $condicion_categoria_sql);
/*var_dump($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) . " and cod_dep=". $cod_dep_set ." and ". $condicion_categoria_sql);
exit();*/
            $concepto_partidas = $this->cfpd01_sub_espec->findAll("cod_grupo=3");

            $this->set('datos_proyectos', $datos_proyectos);
            $this->set('datos_presupuesto', $datos_presupuesto);

            $this->set('concepto_partidas',$concepto_partidas);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
            
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
            $this->set('cod_dep',$this->Session->read('SScoddep'));
        }
    }

    function reporte_formato_8($pdf = null) {
    	if(!$pdf){

            $this->layout = 'ajax';
            $this->verifica_entrada('124');

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);


            $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_8']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_8']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_8']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }


            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
            $this->set('cod_dep',$this->Session->read('SScoddep'));


        }else{
        	set_time_limit(0);
            $this->layout = "pdf";
            $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_8']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_8']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_8']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }


            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

            $cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']-1) . " and cod_dep=". $cod_dep_set);


            $cod_sector = $cfpp02['cfpd02_activ_obra']['cod_sector'];
            $cod_programa = $cfpp02['cfpd02_activ_obra']['cod_programa'];
            $cod_sub_prog = $cfpp02['cfpd02_activ_obra']['cod_sub_prog'];
            $cod_proyecto = $cfpp02['cfpd02_activ_obra']['cod_proyecto'];
            $cod_activ_obra = $cfpp02['cfpd02_activ_obra']['cod_activ_obra'];


            $condicion_categoria_sql = "cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra";




            $datos_proyectos = $this->cpod01_proyectos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) . " and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);
            
            //$datos_proyectos = $this->cpod01_proyectos->findAll($this->SQLCA($year[0]['cfpd01_formulacion']['ano_formular']), null, 'tipo_proyecto ASC, numero_proyecto ASC', null);
            $datos_presupuesto = $this->cpod06_distribucion_ingresos_propios->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) . " and cod_dep=". $cod_dep_set." and ".$condicion_categoria_sql);
            //$datos_presupuesto = $this->cpod06_distribucion_ingresos_propios->findAll($this->SQLCA($year[0]['cfpd01_formulacion']['ano_formular'])." and ".$condicion_categoria_sql);

            $concepto_partidas = $this->cfpd01_sub_espec->findAll("cod_grupo=4");

            $this->set('datos_proyectos', $datos_proyectos);
            $this->set('datos_presupuesto', $datos_presupuesto);
            $this->set('concepto_partidas',$concepto_partidas);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);


            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$this->data['reporte_formato_poai_8']['select_dependencia']);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);

            
        }
    }

    function reporte_formato_9($pdf = null) {
        /**
         *SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.tipo_proyecto, a.numero_proyecto, a.proyectos,
 SUM(b.monto) as gobernacion, SUM(c.monto) as propios, (SELECT SUM(d.costo_total_meta) from cpod05_control_metas as d where a.cod_presi=d.cod_presi and a.cod_entidad=d.cod_entidad and a.cod_tipo_inst=d.cod_tipo_inst
                            and a.cod_inst=d.cod_inst and a.cod_dep=d.cod_dep and a.ano=d.ano and
                            a.tipo_proyecto=d.tipo_proyecto and a.numero_proyecto=d.numero_proyecto) as costo_total_proyecto
  FROM cpod01_proyectos as a
  inner join cpod06_vinculacion_presupuesto as b on a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst
                            and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.ano=b.ano and
                            a.tipo_proyecto=b.tipo_proyecto and a.numero_proyecto=b.numero_proyecto and b.grupo=4
  inner join cpod06_distribucion_ingresos_propios as c on  a.cod_presi=c.cod_presi and a.cod_entidad=c.cod_entidad and a.cod_tipo_inst=c.cod_tipo_inst
                            and a.cod_inst=c.cod_inst and a.cod_dep=c.cod_dep and a.ano=c.ano and
                            a.tipo_proyecto=c.tipo_proyecto and a.numero_proyecto=c.numero_proyecto

group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.tipo_proyecto, a.numero_proyecto, a.proyectos;
         */
    	if(!$pdf){

            $this->layout = 'ajax';

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
             $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_9']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_9']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_9']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }


            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }

            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
            
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
            $this->set('cod_dep',$this->Session->read('SScoddep'));

        }else{
            $this->layout = "pdf";
            $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_9']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_9']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_9']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }


            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

            $condicion_sql = "a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$cod_dep_set." and a.ano=".$year[0]['cfpd01_formulacion']['ano_formular'];
         /*   
var_dump($cod_dep_set);
exit();*/

            $totales_proyectos = $this->cpod01_proyectos->execute("SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.tipo_proyecto, a.numero_proyecto, a.proyectos, 
                   (SELECT SUM(b.monto) 
                   FROM cpod06_vinculacion_presupuesto as b 
                   WHERE b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.tipo_proyecto=a.tipo_proyecto and b.numero_proyecto=a.numero_proyecto and b.grupo=4) as gobernacion,
                   (SELECT SUM(c.monto)
                       FROM cpod06_distribucion_ingresos_propios as c 
                       WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano=a.ano and c.tipo_proyecto=a.tipo_proyecto and c.numero_proyecto=a.numero_proyecto) as propios,
                   (SELECT SUM(d.costo_total_meta) 
                       FROM cpod05_control_metas as d 
                       WHERE a.cod_presi=d.cod_presi and a.cod_entidad=d.cod_entidad and a.cod_tipo_inst=d.cod_tipo_inst and a.cod_inst=d.cod_inst and a.cod_dep=d.cod_dep and a.ano=d.ano and a.tipo_proyecto=d.tipo_proyecto and a.numero_proyecto=d.numero_proyecto) as costo_total_proyecto 
                FROM cpod01_proyectos as a 
                WHERE ".$condicion_sql);

            $this->set('totales_proyectos',$totales_proyectos);
            $this->set('pdf',$pdf);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            //$this->set('nombre_dependencia',$this->verifica_SS(6));
              $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$this->data['reporte_formato_poai_9']['select_dependencia']);
            $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);

        }
    }

    function reporte_formato_10() {

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);

    }

     function reporte_formato_unico($pdf=false){

        if(!$pdf){

            $this->layout = "ajax";

            $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
            $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
            $this->set('pdf',$pdf);
            $this->set('cod_dep',$this->Session->read('SScoddep'));
            $this->set('select_dependencia',$this->data['reporte_formato_poai_1']['select_dependencia']);

            $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            if($lista !=null){
                $this->concatena($lista, 'listadependencia');
            }else{
                $this->set('listadependencia','');
            }
             
        }else{

          $this->layout = "pdf";

          //REPORTE 1
          $cod_dep_set=0;
          if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
            if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
              $cod_dep_set=1;
            }else{
              $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
            }
          }else{
            $cod_dep_set=$this->Session->read('SScoddep');
          }


          $datos_filosofia = $this->cpod01_filosofia_gestion->find($this->SQLCA_Institucion(). " and cod_dep=". $cod_dep_set);
          $datos_proyectos_estrategicos = $this->cpod01_proyectos->findAll("tipo_proyecto = 'ESTRATEGICO' and ".$this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano']). " and cod_dep=".$cod_dep_set, 'proyectos', 'numero_proyecto ASC', null);
          $datos_proyectos_gestion = $this->cpod01_proyectos->findAll("tipo_proyecto = 'GESTION' and ".$this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano']). " and cod_dep=". $cod_dep_set, 'proyectos', 'numero_proyecto ASC', null);

          $this->set('pdf',$pdf);
          $this->set('datos_filosofia',$datos_filosofia);
          $this->set('datos_proyectos_estrategicos',$datos_proyectos_estrategicos);
          $this->set('datos_proyectos_gestion',$datos_proyectos_gestion);
          $this->set('ano',$this->data['reporte_formato_poai_1']['ano']);
          $this->set('select_dependencia',$this->data['reporte_formato_poai_1']['select_dependencia']);
          $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
          // var_dump($name_dep[0][0]['denominacion']);
          $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
          $this->set('cod_dep',$this->Session->read('SScoddep'));

          $handle = fopen('http://data-recargas.guarico.gob.ve/api/download-tree-image/'.$this->Session->read('SScoddep'), 'rb');
          // $img = new Imagick();
          // $img->readImageFile($handle);
          // $img->resizeImage(128, 128, 0, 0);
          //$img->writeImage('images/'.$this->Session->read('SScoddep').'.png');
            

                   
          //REPORTE 2
          $cod_dep_set=0;
            if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
              if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
                $cod_dep_set=1;
              }
              else{
                $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
              }
            }
            else{
                $cod_dep_set=$this->Session->read('SScoddep');
            }
          

            $cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$cod_dep_set;
            $datos_organigrama = $this->cpod03_organigrama->find($this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano']) . " and cod_dep=". $cod_dep_set);
           // $datos_organigrama = $this->cpod03_organigrama->find($this->SQLCA($this->data['reporte_formato_poai_2']['ano']));

            $this->set('cod_imagen', $cod_imagen);
            $this->set('datos_organigrama', $datos_organigrama);
            $this->set('cod_dep_img',$cod_dep_set);
          

          //REPORTE 3
          $cod_dep_set=0;
           
          if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
            if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
              $cod_dep_set=1;
            }else{
              $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
            }
          }else{
              $cod_dep_set=$this->Session->read('SScoddep');
          }
          //$datos_proyectos_gestion = $this->cpod01_proyectos->findAll("tipo_proyecto = 'GESTION' and ".$this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano']). " and cod_dep=". $cod_dep_set, 'proyectos', 'numero_proyecto ASC', null);

          //$datos_recurso_humano = $this->cpod02_recurso_humano->findAll($this->SQLCA($this->data['reporte_formato_poai_1']['ano']), 'poner datos a mostrar con CASE para situacion laboral', 'numero_proyecto ASC', null);
          $datos_recurso_humano = $this->cpod02_recurso_humano->findAll($this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano'])." and cod_dep=". $cod_dep_set." and situacion_laboral in (2,3,5)", null, 'cod_cargo ASC', null);
          $datos_recurso_humano_contratados = $this->cpod02_recurso_humano->findAll($this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano'])." and cod_dep=". $cod_dep_set." and situacion_laboral in (14,15,16)", null, 'cod_cargo ASC', null);
          $datos_recurso_humano_pensionados = $this->cpod02_recurso_humano->findAll($this->SQLCA_Institucion($this->data['reporte_formato_poai_1']['ano'])." and cod_dep=". $cod_dep_set." and situacion_laboral in (6,7,8,9,10,11,12,13)", null, 'cod_cargo ASC', null);

          $this->set('pdf',$pdf);
          $this->set('datos',$datos_recurso_humano);
          $this->set('datos_contratados',$datos_recurso_humano_contratados);
          $this->set('datos_pensionados',$datos_recurso_humano_pensionados);
          $this->set('ano',$this->data['reporte_formato_poai_1']['ano']);
          //$this->set('nombre_dependencia',$this->verifica_SS(6));

          /*var_dump($name_dep[0][0]['denominacion']);
          exit();*/
          // var_dump($name_dep[0][0]['denominacion']);
          $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
           $this->set('cod_dep',$this->Session->read('SScoddep'));

          //REPORTE 4
          $cod_dep_set=0;
           
          if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
            if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
              $cod_dep_set=1;
            }
            else{
              $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
            }
          }
          else{
              $cod_dep_set=$this->Session->read('SScoddep');
          }

          //$year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
          $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

          $datos_objetivos_r4 = $this->cpod04_objetivos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'numero_objetivo ASC', null);
          $datos_problemas_r4 = $this->cpod04_problemas_areas_gestion->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'numero_objetivo ASC, numero_problema_area_gestion ASC', null);
          

          $this->set('datos_objetivos_r4',$datos_objetivos_r4);
          $this->set('datos_problemas_r4',$datos_problemas_r4);
          $this->set('pdf',$pdf);
          $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
          

          
          $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
          $this->set('cod_dep',$this->Session->read('SScoddep'));

          //REPORTE 5
           $cod_dep_set=0;
           
          if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
            if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
              $cod_dep_set=1;
            }
            else{
              $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
            }
          }
          else{
              $cod_dep_set=$this->Session->read('SScoddep');
          }

          $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

          $datos_proyectos_r5 = $this->cpod01_proyectos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);

          $datos_objetivos_r5 = $this->cpod04_objetivos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);

          $datos_metas_r5 = $this->cpod05_control_metas->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set."",null, 'tipo_proyecto ASC, numero_proyecto ASC, cod_meta ASC', null);

          $datos_situacion_actual_r5 = $this->cpod05_situacion_actual->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set."",null, 'tipo_proyecto ASC, numero_proyecto ASC', null);

          $totales_gobernacion_r5 = $this->cpod05_situacion_actual->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,
                  tipo_proyecto, numero_proyecto, SUM(monto) as total
                FROM cpod06_vinculacion_presupuesto
                where grupo=4 and ".$this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set."
                group by cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,
                     tipo_proyecto, numero_proyecto;");

          $totales_propios_r5 = $this->cpod05_situacion_actual->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,
                     tipo_proyecto, numero_proyecto, SUM(monto) as total
                FROM cpod06_distribucion_ingresos_propios
                WHERE ".$this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set."
                group by cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,
                     tipo_proyecto, numero_proyecto;");


          $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
          $this->set('cod_dep',$this->Session->read('SScoddep'));
          


          if(!empty($datos_situacion_actual_r5)){
              $this->set('datos_proyectos_r5',$datos_proyectos_r5);
              $this->set('datos_objetivos_r5',$datos_objetivos_r5);
              $this->set('datos_metas_r5',$datos_metas_r5);
              $this->set('datos_situacion_actual_r5',$datos_situacion_actual_r5);
              $this->set('totales_gobernacion_r5', $totales_gobernacion_r5);
              $this->set('totales_propios_r5', $totales_propios_r5);
              $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
              $this->set('pdf',$pdf);
              $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
          }else{

              $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
              $this->set('pdf',$pdf);
              $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
              $this->set('errorMessage','Debe Registrar la situacion actual y el supuesto');
          }

          //REPORTE 6           
          $cod_dep_set=0;
          if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
            if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
              $cod_dep_set=1;
            }
            else{
              $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
            }
          }
          else{
              $cod_dep_set=$this->Session->read('SScoddep');
          }

          $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

          $cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']-1) ." and cod_dep=". $cod_dep_set);

          $cod_sector = $cfpp02['cfpd02_activ_obra']['cod_sector'];
          $cod_programa = $cfpp02['cfpd02_activ_obra']['cod_programa'];
          $cod_sub_prog = $cfpp02['cfpd02_activ_obra']['cod_sub_prog'];
          $cod_proyecto = $cfpp02['cfpd02_activ_obra']['cod_proyecto'];
          $cod_activ_obra = $cfpp02['cfpd02_activ_obra']['cod_activ_obra'];



          $condicion_categoria_sql = "cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and grupo=4";
          $datos_proyectos_r6 = $this->cpod01_proyectos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular'])." and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);
          $datos_presupuesto_r6 = $this->cpod06_vinculacion_presupuesto->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) ." and cod_dep=". $cod_dep_set ." and ". $condicion_categoria_sql);

          $concepto_partidas_r6 = $this->cfpd01_sub_espec->findAll("cod_grupo=4");

          $this->set('datos_proyectos_r6', $datos_proyectos_r6);
          $this->set('datos_presupuesto_r6', $datos_presupuesto_r6);
          $this->set('concepto_partidas_r6',$concepto_partidas_r6);
          $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
          $this->set('pdf',$pdf);

          $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
          $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);

          $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
          if($lista !=null){
              $this->concatena($lista, 'listadependencia');
          }else{
              $this->set('listadependencia','');
          }

          //REPORTE 7
          $cod_dep_set=0;
          if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
            if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
              $cod_dep_set=1;
            }
            else{
              $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
            }
          }
          else{
              $cod_dep_set=$this->Session->read('SScoddep');
          }

          $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

          $cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']-1) ." and cod_dep=". $cod_dep_set);
          //$cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA($year[0]['cfpd01_formulacion']['ano_formular']));
          $cod_sector = $cfpp02['cfpd02_activ_obra']['cod_sector'];
          $cod_programa = $cfpp02['cfpd02_activ_obra']['cod_programa'];
          $cod_sub_prog = $cfpp02['cfpd02_activ_obra']['cod_sub_prog'];
          $cod_proyecto = $cfpp02['cfpd02_activ_obra']['cod_proyecto'];
          $cod_activ_obra = $cfpp02['cfpd02_activ_obra']['cod_activ_obra'];


          $condicion_categoria_sql = "cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and grupo=3";



          $datos_proyectos_r7 = $this->cpod01_proyectos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) ." and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);


          $datos_presupuesto_r7 = $this->cpod06_vinculacion_presupuesto->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) . " and cod_dep=". $cod_dep_set ." and ". $condicion_categoria_sql);

          $concepto_partidas_r7 = $this->cfpd01_sub_espec->findAll("cod_grupo=3");

          $this->set('datos_proyectos_r7', $datos_proyectos_r7);
          $this->set('datos_presupuesto_r7', $datos_presupuesto_r7);

          $this->set('concepto_partidas_r7',$concepto_partidas_r7);
          $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
          $this->set('pdf',$pdf);

          $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$cod_dep_set);
          $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);
          $this->set('cod_dep',$this->Session->read('SScoddep'));

          
          //REPORTE 8
          set_time_limit(0);
          $cod_dep_set=0;
          if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
            if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
              $cod_dep_set=1;
            }
            else{
              $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
            }
          }
          else{
              $cod_dep_set=$this->Session->read('SScoddep');
          }


          $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

          $cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']-1) . " and cod_dep=". $cod_dep_set);


          $cod_sector = $cfpp02['cfpd02_activ_obra']['cod_sector'];
          $cod_programa = $cfpp02['cfpd02_activ_obra']['cod_programa'];
          $cod_sub_prog = $cfpp02['cfpd02_activ_obra']['cod_sub_prog'];
          $cod_proyecto = $cfpp02['cfpd02_activ_obra']['cod_proyecto'];
          $cod_activ_obra = $cfpp02['cfpd02_activ_obra']['cod_activ_obra'];


          $condicion_categoria_sql = "cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra";




          $datos_proyectos_r8 = $this->cpod01_proyectos->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) . " and cod_dep=". $cod_dep_set, null, 'tipo_proyecto ASC, numero_proyecto ASC', null);

          //$datos_proyectos = $this->cpod01_proyectos->findAll($this->SQLCA($year[0]['cfpd01_formulacion']['ano_formular']), null, 'tipo_proyecto ASC, numero_proyecto ASC', null);
          $datos_presupuesto_r8 = $this->cpod06_distribucion_ingresos_propios->findAll($this->SQLCA_Institucion($year[0]['cfpd01_formulacion']['ano_formular']) . " and cod_dep=". $cod_dep_set." and ".$condicion_categoria_sql);
          //$datos_presupuesto = $this->cpod06_distribucion_ingresos_propios->findAll($this->SQLCA($year[0]['cfpd01_formulacion']['ano_formular'])." and ".$condicion_categoria_sql);

          $concepto_partidas_r8 = $this->cfpd01_sub_espec->findAll("cod_grupo=4");

          $this->set('datos_proyectos_r8', $datos_proyectos_r8);
          $this->set('datos_presupuesto_r8', $datos_presupuesto_r8);
          $this->set('concepto_partidas_r8',$concepto_partidas_r8);
          $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
          $this->set('pdf',$pdf);


          $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$this->data['reporte_formato_poai_1']['select_dependencia']);
          $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);

          //REPORTE 9
          $cod_dep_set=0;
          if (isset($this->data['reporte_formato_poai_1']['select_dependencia'])){
            if ($this->data['reporte_formato_poai_1']['select_dependencia']=="") {
              $cod_dep_set=1;
            }
            else{
              $cod_dep_set=$this->data['reporte_formato_poai_1']['select_dependencia'];
            }
          }
          else{
              $cod_dep_set=$this->Session->read('SScoddep');
          }


          $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

          $condicion_sql = "a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$cod_dep_set." and a.ano=".$year[0]['cfpd01_formulacion']['ano_formular'];



          $totales_proyectos_r9 = $this->cpod01_proyectos->execute("SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.tipo_proyecto, a.numero_proyecto, a.proyectos, 
                 (SELECT SUM(b.monto) 
                 FROM cpod06_vinculacion_presupuesto as b 
                 WHERE b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.tipo_proyecto=a.tipo_proyecto and b.numero_proyecto=a.numero_proyecto and b.grupo=4) as gobernacion,
                 (SELECT SUM(c.monto)
                     FROM cpod06_distribucion_ingresos_propios as c 
                     WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano=a.ano and c.tipo_proyecto=a.tipo_proyecto and c.numero_proyecto=a.numero_proyecto) as propios,
                 (SELECT SUM(d.costo_total_meta) 
                     FROM cpod05_control_metas as d 
                     WHERE a.cod_presi=d.cod_presi and a.cod_entidad=d.cod_entidad and a.cod_tipo_inst=d.cod_tipo_inst and a.cod_inst=d.cod_inst and a.cod_dep=d.cod_dep and a.ano=d.ano and a.tipo_proyecto=d.tipo_proyecto and a.numero_proyecto=d.numero_proyecto) as costo_total_proyecto 
              FROM cpod01_proyectos as a 
              WHERE ".$condicion_sql);
          

          $this->set('totales_proyectos_r9',$totales_proyectos_r9);
          $this->set('pdf',$pdf);
          $this->set('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
          //$this->set('nombre_dependencia',$this->verifica_SS(6));
            $name_dep=$this->arrd05->execute("select denominacion from arrd05 where cod_dep=".$this->data['reporte_formato_poai_1']['select_dependencia']);
          $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);

              }
    }
}
?>