<?
class Cpyp01AccionEspecController extends AppController{

    var $uses = array('cfpd01_formulacion','cpyd01_proy_accion','ccfd04_cierre_mes','cpyd01_accion_espec','cpyd01_accion_espec_activ');
    var $name = 'cpyp01_accion_espec';
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

function index($var=null,$select=null,$ano=null){
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
	$this->set('modelo','cpyd01_accion_espec');
	$this->set('readonly','readonly');

	$this->set('formular',$ano_formular);


	if($select!=null){//entrando con el select


		$rs1=$this->cpyd01_proy_accion->findAll($this->SQLCA($ano_formular)." and cod_proy_accion='$select' and proyecto_accion='$var'");
		$rs=$this->cpyd01_accion_espec->findAll($this->SQLCA($ano_formular)." and cod_proy_accion='$select' and proyecto_accion='$var'");

		$this->set('datos1',$rs1);
		$this->set('datos',$rs);
		$this->set('opcion',$var);
		$this->set('select',$select);
		$this->set('titulo',$var==1 ? "DE LOS PROYECTOS" : "DE LAS ACCIONES CENTRALIZADAS");
		$this->set('modelo_proy','cpyd01_proy_accion');
		$this->set('readonly',false);




	}
}//fin function


function guardar_proyecto($var=null,$cod_proy=null,$cod_onapre=null){
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
			$sql = "INSERT INTO cpyd01_accion_espec (cod_presi, cod_entidad, cod_tipo_inst, cod_inst,cod_dep, ano, cod_proy_accion, denominacion, descripcion, unidad_ejecutora, responsable, cod_onapre, proyecto_accion, cod_accion_espec,fecha_creacion) VALUES (".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$this->data['cpyp01_accion_espec']['ej_formular'].",'".$cod_proy."','"
				.$this->data['cpyp01_accion_espec']['denominacion_especifica']."','".$this->data['cpyp01_accion_espec']['descripcion_especifica']."','".$this->data['cpyp01_accion_espec']['unidad_ej_especifica']."','".$this->data['cpyp01_accion_espec']['funcionario_especifica']."','".$onapre."',".$var.",'".$this->data['cpyp01_accion_espec']['cod_especifica']."','".$this->Cfecha($this->data['cpyp01_accion_espec']['fecha_especifica'], 'A-M-D')."');";
			$rs=$this->cpyd01_proy_accion->execute($sql);

			if($rs>1){
   	       	 	$this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
       	 	}else{
   	      	  $this->set("errorMessage","Los Datos No Fuerón Guardados");
        	}

		}else $this->set("errorMessage","Los Datos No Fuerón Guardados, el Codigo ya esta registrado");

		$this->index($var,$cod_proy);
		$this->set('titulo',$var==1 ? "DE LOS PROYECTOS" : "DE LAS ACCIONES CENTRALIZADAS");
		$this->render('index');
		$this->set('modelo','cpyd01_proy_accion');



}//fin function

function eliminar ($cod_espec=null,$codigo_pro=null,$var=null,$ano=null){
	$this->layout="ajax";

	$rs=$this->cpyd01_accion_espec_activ->findCount($this->SQLCA()." and cod_accion_espec='$cod_espec' and cod_proy_accion='$codigo_pro' and proyecto_accion='$var' and ano='$ano'");

	if($rs==0){
	$rs=$this->cpyd01_accion_espec->execute("delete from cpyd01_accion_espec where ". $this->SQLCA()." and cod_accion_espec='$cod_espec' and cod_proy_accion='$codigo_pro' and proyecto_accion='$var' and ano='$ano'");

		if($rs > 1){
			$this->set('Message_existe','registro eliminado con exito');
		}else{
			$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		}
	}else{
			$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO, TIENE REGISTRADAS ACTIVIDADES');

	}

		$this->index($var,$codigo_pro);
		$this->render('index');

}//fin eliminar

function editar ($cod_espec=null,$codigo_pro=null,$var=null,$ano=null){
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

		$this->set('datos1',$rs1);
		$this->set('datos',$rs);
		$this->set('opcion',$var);
		$this->set('select',$codigo_pro);
		$this->set('titulo',$var==1 ? "DE LOS PROYECTOS" : "DE LAS ACCIONES CENTRALIZADAS");
		$this->set('modelo_proy','cpyd01_proy_accion');
		$this->set('modelo','cpyd01_accion_espec');
		$this->set('readonly',false);
		$this->set('formular',$ano);

}//fin editar


function guardar_editar ($cod_espec=null,$codigo_pro=null,$var=null,$ano=null){
	$this->layout="ajax";

  	$sql = "UPDATE cpyd01_accion_espec SET  denominacion='".$this->data['cpyp01_accion_espec']['denominacion_especifica']."', descripcion='".$this->data['cpyp01_accion_espec']['descripcion_especifica']."', unidad_ejecutora='".$this->data['cpyp01_accion_espec']['unidad_ej_especifica']."', responsable='".$this->data['cpyp01_accion_espec']['funcionario_especifica']."'  where ". $this->SQLCA()." and cod_accion_espec='".$cod_espec."' and cod_proy_accion='".$codigo_pro."' and proyecto_accion='".$var."' and ano='".$ano."'";

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

		$this->index($var,$codigo_pro);
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

		$this->index($opcion,$var,$ano);
		$this->render('index');

}



}
?>