<?php

class Cfpp40DependenciasPresupuestoController extends AppController {

	var $name = 'cfpp40_dependencias_presupuesto';
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

	function index(){

        $this->layout = "ajax";

        $this->set('cod_dep',$this->Session->read('SScoddep'));

        $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
        if($lista !=null){
            $this->concatena($lista, 'listadependencia');
        }else{
            $this->set('listadependencia','');
        }

        $sqlDepen = 'SELECT a.cod_dep, b.denominacion FROM cfpd40_dependencias_presupuesto AS a INNER JOIN arrd05 AS b ON a.cod_dep=b.cod_dep ORDER BY cod_dep ASC';
        $listDep = $this->arrd05->execute($sqlDepen);
        $this->set('datos',$listDep);  
             
    }

    function guardar(){

        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        
        $cod_dep = $this->data["cfpp40_dependencias_presupuesto"]["select_dependencia"];

        if($cod_dep!==""){

            $sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
            $checkDep = $this->arrd05->execute($sqlCheck);
            if(count($checkDep)>0){
                $this->set('errorMessage','Dependencia ya esta afectando presupuesto');            
            }else{
                $sqlInsert = "INSERT INTO cfpd40_dependencias_presupuesto(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep) VALUES (".$cod_presi.", ".$cod_entidad.", ".$cod_tipo_inst.", ".$cod_inst.", ".$cod_dep.")";
                
                if ($this->arrd05->execute($sqlInsert)>0) {
                    $this->set('Message_existe','Dependencia registrada correctamente');
                }else{
                    $this->set('errorMessage','La información no pudo ser registrada');
                }
            }
        }

        $sqlDepen = 'SELECT a.cod_dep, b.denominacion FROM cfpd40_dependencias_presupuesto AS a INNER JOIN arrd05 AS b ON a.cod_dep=b.cod_dep ORDER BY cod_dep ASC';
        $listDep = $this->arrd05->execute($sqlDepen);
        $this->set('datos',$listDep);   
             
    }

    function eliminar($cod_dep){

        $this->layout = "ajax";
        
        if($cod_dep!==""){

            $sqlDelet = 'DELETE FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
            if ($this->arrd05->execute($sqlDelet)>0) {
                $this->set('Message_existe','Dependencia eliminada correctamente');
            }else{
                $this->set('errorMessage','La información no pudo ser eliminada');
            }
        }

        $sqlDepen = 'SELECT a.cod_dep, b.denominacion FROM cfpd40_dependencias_presupuesto AS a INNER JOIN arrd05 AS b ON a.cod_dep=b.cod_dep ORDER BY cod_dep ASC';
        $listDep = $this->arrd05->execute($sqlDepen);
        $this->set('datos',$listDep); 

        $this->render('guardar');
             
    }

}
?>