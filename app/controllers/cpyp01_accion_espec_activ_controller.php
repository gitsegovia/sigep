<?
class Cpyp01AccionEspecActivController extends AppController{

    var $uses = array('ccfd04_cierre_mes','cfpd01_formulacion','cpyd01_proy_accion','ccfd04_cierre_mes','cpyd01_accion_espec','cpyd01_accion_espec_activ','cfpd02_sector','cfpd02_programa','cfpd02_sub_prog','cfpd02_proyecto','cfpd02_activ_obra');
    var $name = 'cpyp01_accion_espec_activ';
    var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf');


function checkSession(){
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir/');
            exit();
        }
    }


function beforeFilter(){
     $this->checkSession();

    }

 function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
}//fin funcion SQLCA

 function SQLCA_INST(){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";

         return $sql_re;
}//fin funcion SQLCA

function index($var=null,$select=null,$select1=null,$ano=null){
	$this->layout="ajax";
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

	$vector=array();
 	$lista = $this->cpyd01_proy_accion->findAll($this->SQLCA($ano_formular)." and proyecto_accion=".$var, null,"fecha_creacion,cod_proy_accion ASC");
	foreach($lista as $lista1){
		$vector[$lista1['cpyd01_proy_accion']['cod_proy_accion']]=$lista1['cpyd01_proy_accion']['cod_proy_accion'];

	}

	$this->set('vector',$vector);

	$this->set('titulo',$var==1 ? "DE LOS PROYECTOS" : "DE LAS ACCIONES CENTRALIZADAS");
	$this->set('opcion',$var);
	$this->set('tipo_inst',$cod_tipo_inst);
	$this->set('modelo','cpyd01_accion_espec_activ');
		$this->set('readonly','readonly');

	$this->set('formular',$ano_formular);


	if($select!=null){//entrando con el select


		$rs=$this->cpyd01_proy_accion->findAll($this->SQLCA($ano_formular)." and cod_proy_accion='$select' and proyecto_accion='$var'");
		$vector1=array();

 		$lista = $this->cpyd01_accion_espec->findAll($this->SQLCA($ano_formular)." and proyecto_accion=".$var." and cod_proy_accion='$select' ", null,"fecha_creacion,cod_proy_accion ASC");
		foreach($lista as $lista1){
			$vector1[$lista1['cpyd01_accion_espec']['cod_accion_espec']]=$lista1['cpyd01_accion_espec']['cod_accion_espec'];
		}

		$this->set('vector1',$vector1);
	    $this->set('datos1',$rs);
	    $this->set('opcion',$var);
		$this->set('select',$select);
		$this->set('titulo',$var==1 ? "DE LOS PROYECTOS" : "DE LAS ACCIONES CENTRALIZADAS");
		$this->set('modelo_proy','cpyd01_proy_accion');

	}

	if($select1!=null){//entrando con el select


		$rs=$this->cpyd01_accion_espec->findAll($this->SQLCA($ano_formular)." and cod_proy_accion='$select' and cod_accion_espec='$select1' and proyecto_accion='$var'");

		$this->set('datos2',$rs);
		$this->set('select1',$select1);
		$this->set('modelo_espc','cpyd01_accion_espec');

		$rs=$this->cpyd01_accion_espec_activ->findAll($this->SQLCA($ano_formular)." and cod_proy_accion='$select' and cod_accion_espec='$select1' and proyecto_accion='$var'",null,"cod_accion_espec_activ DESC",1);
		$this->set('siguiente',$rs[0]['cpyd01_accion_espec_activ']['cod_accion_espec_activ']+1);

		$rs=$this->cpyd01_accion_espec_activ->findAll($this->SQLCA($ano_formular)." and cod_proy_accion='$select' and cod_accion_espec='$select1' and proyecto_accion='$var'",null,"cod_proy_accion,cod_accion_espec,cod_accion_espec_activ ASC");
		$this->set('datos',$rs);

		$sector=$this->cfpd02_sector->generateList($this->SQLCA($this->ano_ejecucion()),'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
		$sector = $sector != null ? $sector : array();
		$this->concatena($sector, 'sector');
		$this->set('readonly',false);

	}

}//fin function


function guardar_proyecto($var=null,$cod_proy=null,$cod_onapre=null,$cod_espec=null,$ano=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	//opcion 1 de proyecto

		$rs_count=$this->cpyd01_proy_accion->findCount($this->SQLCA()." and cod_proy_accion='".$this->data['cpyp01_proy_accion']['cod_proyecto']."' and proyecto_accion=$var");

		if($rs_count==0){
			$onapre=$var==1 ? $cod_onapre : "N/A";
			$sql = "INSERT INTO cpyd01_accion_espec_activ (cod_presi, cod_entidad, cod_tipo_inst, cod_inst,cod_dep, ano, cod_proy_accion, denominacion, descripcion, unidad_ejecutora, responsable, cod_onapre, proyecto_accion, cod_accion_espec,fecha_creacion,cod_accion_espec_activ,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra) VALUES (".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$ano.",'".$cod_proy."','"
				.$this->data['cpyp01_accion_espec_activ']['denominacion_actividad']."','".$this->data['cpyp01_accion_espec_activ']['descripcion_actividad']."','".$this->data['cpyp01_accion_espec_activ']['unidad_ej_actividad']."','".$this->data['cpyp01_accion_espec_activ']['funcionario_actividad']."','".$onapre."',".$var.",'".$cod_espec."','".$this->Cfecha($this->data['cpyp01_accion_espec_activ']['fecha_actividad'], 'A-M-D')."',".$this->data['cpyp01_accion_espec_activ']['cod_actividad'].",".$this->data['cpyp01_accion_espec_activ']['cod_sector']
				.",".$this->data['cpyp01_accion_espec_activ']['cod_programa'].",".$this->data['cpyp01_accion_espec_activ']['cod_sub'].",".$this->data['cpyp01_accion_espec_activ']['cod_proyecto'].",".$this->data['cpyp01_accion_espec_activ']['cod_act_obra'].");";
			$rs=$this->cpyd01_proy_accion->execute($sql);

			if($rs>1){
   	       	 	$this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
       	 	}else{
   	      	  $this->set("errorMessage","Los Datos No Fuerón Guardados");
        	}

		}else $this->set("errorMessage","Los Datos No Fuerón Guardados, el Codigo ya esta registrado");

		$this->index($var,$cod_proy,$cod_espec);
		$this->set('titulo',$var==1 ? "DE LOS PROYECTOS" : "DE LAS ACCIONES CENTRALIZADAS");
		$this->render('index');
		$this->set('modelo','cpyd01_proy_accion');



}//fin function

function eliminar ($cod_actv=null,$cod_espec=null,$codigo_pro=null,$var=null,$ano=null){
	$this->layout="ajax";


		$rs=$this->cpyd01_accion_espec->execute("delete from cpyd01_accion_espec_activ where ". $this->SQLCA()." and cod_accion_espec='$cod_espec' and cod_proy_accion='$codigo_pro' and cod_accion_espec_activ='$cod_actv' and proyecto_accion='$var' and ano='$ano'");

		if($rs > 1){
			$this->set('Message_existe','registro eliminado con exito');
		}else{
			$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		}


		$this->index($var,$codigo_pro,$cod_espec);
		$this->render('index');

}//fin eliminar

function editar ($cod_actv=null,$cod_espec=null,$codigo_pro=null,$var=null,$ano=null){
	$this->layout="ajax";

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;


	$vector=array();
 	$lista = $this->cpyd01_proy_accion->findAll($this->SQLCA($ano)." and proyecto_accion=".$var, null,"fecha_creacion,cod_proy_accion ASC");
	foreach($lista as $lista1){
		$vector[$lista1['cpyd01_proy_accion']['cod_proy_accion']]=$lista1['cpyd01_proy_accion']['cod_proy_accion'];

	}
	$this->set('vector',$vector);



		$rs1=$this->cpyd01_proy_accion->findAll($this->SQLCA($ano)." and cod_proy_accion='$codigo_pro' and proyecto_accion='$var'");
		$rs=$this->cpyd01_accion_espec->findAll($this->SQLCA($ano)." and cod_accion_espec='$cod_espec' and cod_proy_accion='$codigo_pro' and proyecto_accion='$var'");
		$rs2=$this->cpyd01_accion_espec_activ->findAll($this->SQLCA()." and cod_accion_espec='$cod_espec' and cod_proy_accion='$codigo_pro' and cod_accion_espec_activ='$cod_actv' and proyecto_accion='$var' and ano='$ano'");

		$this->set('datos2',$rs2);
		$this->set('datos1',$rs1);
		$this->set('datos',$rs);
		$this->set('opcion',$var);
		$this->set('select',$codigo_pro);
		$this->set('select1',$cod_espec);
		$this->set('titulo',$var==1 ? "DE LOS PROYECTOS" : "DE LAS ACCIONES CENTRALIZADAS");
		$this->set('modelo_proy','cpyd01_proy_accion');
		$this->set('modelo','cpyd01_accion_espec');
		$this->set('modelo_act','cpyd01_accion_espec_activ');
		$this->set('readonly',false);
		$this->set('formular',$ano);
		$this->set('tipo_inst',$cod_tipo_inst);


//sector

		$sector=$this->cfpd02_sector->generateList($this->SQLCA($this->ano_ejecucion()),'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
		$sector = $sector != null ? $sector : array();
		$this->concatena($sector, 'sector');

//programa
			$cond =$this->SQLCA()." and ano=".$this->ano_ejecucion()." and cod_sector=".$rs2[0]['cpyd01_accion_espec_activ']['cod_sector'];
			$codigos=  $this->cfpd02_programa->generateList($cond, 'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
			$codigos = $codigos != null ? $codigos : array();
			$this->concatena($codigos, 'vector1');

//sub programa
			$cond =$this->SQLCA()." and ano=".$this->ano_ejecucion()." and cod_sector=".$rs2[0]['cpyd01_accion_espec_activ']['cod_sector']." and cod_programa=".$rs2[0]['cpyd01_accion_espec_activ']['cod_programa'];
			$codigos=  $this->cfpd02_sub_prog->generateList($cond, 'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
			$codigos = $codigos != null ? $codigos : array();
			$this->concatena($codigos, 'vector2');

//proyecto
			$cond =$this->SQLCA()." and ano=".$this->ano_ejecucion()." and cod_sector=".$rs2[0]['cpyd01_accion_espec_activ']['cod_sector']." and cod_programa=".$rs2[0]['cpyd01_accion_espec_activ']['cod_programa']." and cod_sub_prog=".$rs2[0]['cpyd01_accion_espec_activ']['cod_sub_prog'];
			$codigos=  $this->cfpd02_proyecto->generateList($cond, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
			$codigos = $codigos != null ? $codigos : array();
			$this->concatena($codigos, 'vector3');

//activida obra

			$cond =$this->SQLCA()." and ano=".$this->ano_ejecucion()." and cod_sector=".$rs2[0]['cpyd01_accion_espec_activ']['cod_sector']." and cod_programa=".$rs2[0]['cpyd01_accion_espec_activ']['cod_programa']." and cod_sub_prog=".$rs2[0]['cpyd01_accion_espec_activ']['cod_sub_prog']." and cod_proyecto=".$rs2[0]['cpyd01_accion_espec_activ']['cod_proyecto'];
			$codigos=  $this->cfpd02_activ_obra->generateList($cond, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
			$codigos = $codigos != null ? $codigos : array('00'=>'N/A');
			$this->concatena($codigos, 'vector4');


}//fin editar


function guardar_editar ($var=null,$cod_espec=null,$codigo_pro=null,$ano=null){
	$this->layout="ajax";

if(isset($this->data['cpyp01_accion_espec_activ']['cod_sector']))
  	$sql = "UPDATE cpyd01_accion_espec_activ SET  denominacion='".$this->data['cpyp01_accion_espec_activ']['denominacion_actividad']."', descripcion='".$this->data['cpyp01_accion_espec_activ']['descripcion_actividad']."', unidad_ejecutora='".$this->data['cpyp01_accion_espec_activ']['unidad_ej_actividad']."', responsable='".$this->data['cpyp01_accion_espec_activ']['funcionario_actividad']."', cod_sector=".$this->data['cpyp01_accion_espec_activ']['cod_sector'].", cod_programa=".$this->data['cpyp01_accion_espec_activ']['cod_programa'].", cod_sub_prog=".$this->data['cpyp01_accion_espec_activ']['cod_sub'].",  cod_proyecto=".$this->data['cpyp01_accion_espec_activ']['cod_proyecto'].", cod_activ_obra=".$this->data['cpyp01_accion_espec_activ']['cod_act_obra']." where ". $this->SQLCA()." and cod_accion_espec='".$cod_espec."' and cod_proy_accion='".$codigo_pro."' and cod_accion_espec_activ=".$this->data['cpyp01_accion_espec_activ']['cod_actividad']." and proyecto_accion=".$var." and ano=".$ano;
else
  	$sql = "UPDATE cpyd01_accion_espec_activ SET  denominacion='".$this->data['cpyp01_accion_espec_activ']['denominacion_actividad']."', descripcion='".$this->data['cpyp01_accion_espec_activ']['descripcion_actividad']."', unidad_ejecutora='".$this->data['cpyp01_accion_espec_activ']['unidad_ej_actividad']."', responsable='".$this->data['cpyp01_accion_espec_activ']['funcionario_actividad']." where ". $this->SQLCA()." and cod_accion_espec='".$cod_espec."' and cod_proy_accion='".$codigo_pro."' and cod_accion_espec_activ=".$this->data['cpyp01_accion_espec_activ']['cod_actividad']." and proyecto_accion=".$var." and ano=".$ano;

		$rs=$this->cpyd01_proy_accion->execute($sql);


		if($rs > 1){
			$this->set('Message_existe','registro actualizado con exito');
		}else{
			$this->set('errorMessage', 'EL DATO NO PUDO SER actualizado');
		}

		$this->set('opcion',$var);
		$this->set('titulo',$var==1 ? "DE LOS PROYECTOS" : "DE LAS ACCIONES CENTRALIZADAS");
		$this->set('modelo','cpyd01_proy_accion');
		$this->set('modelo_proy','cpyd01_proy_accion');
		$this->set('modelo','cpyd01_accion_espec');
		$this->set('readonly',false);
		$this->set('formular',$ano);

		$this->index($var,$codigo_pro,$cod_espec);
		$this->render('index');


}//fin editar

function revisar($cod_proy=null,$var=null){
	$this->layout="ajax";

	$datos0 = $this->cpyd01_accion_espec->findCount($this->SQLCA_INST()." and cod_proy_accion='".strtoupper($cod_proy)."' and cod_accion_espec='".strtoupper($var)."'");

	if($datos0!=0){
			$this->set('existe',true);
			$this->set('errorMessage', 'EL Código ya se encuentra registrado, por favor ingrese otro código');
		}else{
			$this->set('existe',false);
	}

}


function seleccion($opcion=null,$ano=null,$var=null){
	$this->layout="ajax";
		$this->index($opcion,$var,null,$ano);
		$this->render('index');

}


function seleccion1($opcion=null,$select=null,$ano=null,$var=null){
	$this->layout="ajax";
		$this->index($opcion,$select,$var,$ano);
		$this->render('index');

}

function select_codigos($opcion=null,$var=null){
	$this->layout="ajax";

	if($opcion=="programa"){

			$cond =$this->SQLCA()." and ano=".$this->ano_ejecucion()." and cod_sector=".$var;
			$codigos=  $this->cfpd02_programa->generateList($cond, 'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
			$codigos = $codigos != null ? $codigos : array();
			$this->concatena($codigos, 'codigos');
			$this->set('td_up','td_sub');
			$this->set('id_select','cod_programa');
			$this->Session->write('sec',$var);

	}else if($opcion=="cod_programa"){

			$sec =  $this->Session->read('sec');
			$cond =$this->SQLCA()." and ano=".$this->ano_ejecucion()." and cod_sector=".$sec." and cod_programa=".$var;
			$codigos=  $this->cfpd02_sub_prog->generateList($cond, 'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
			$codigos = $codigos != null ? $codigos : array();
			$this->concatena($codigos, 'codigos');
			$this->set('td_up','td_proyecto');
			$this->set('id_select','cod_sub');
			$this->Session->write('prog',$var);

	}else if($opcion=="cod_sub"){

			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$this->Session->write('subp',$var);
			$cond =$this->SQLCA()." and ano=".$this->ano_ejecucion()." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
			$codigos=  $this->cfpd02_proyecto->generateList($cond, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
			$codigos = $codigos != null ? $codigos : array();
			$this->concatena($codigos, 'codigos');
			$this->set('td_up','td_actividad');
			$this->set('id_select','cod_proyecto');

	}else if($opcion=="cod_proyecto"){

			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$this->Session->write('proy',$var);
			$cond =$this->SQLCA()." and ano=".$this->ano_ejecucion()." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
			$codigos=  $this->cfpd02_activ_obra->generateList($cond, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
			$codigos = $codigos != null ? $codigos : array('00'=>'N/A');
			$this->concatena($codigos, 'codigos');
			$this->set('td_up','listo');
			$this->set('id_select','cod_act_obra');

	}


}//fin select_codigos

}
?>