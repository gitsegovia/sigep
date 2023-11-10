<?php


class Cpyp01ProyAccionController extends AppController{

    var $uses = array('cfpd01_formulacion','cpyd01_proy_accion','ccfd04_cierre_mes','cpyd01_accion_espec','cpyd01_accion_espec_activ');
    var $name = 'cpyp01_proy_accion';
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

function index($var=null,$ano=null){
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

	$datos0 = $this->cpyd01_proy_accion->findCount($condicion." and proyecto_accion=".$var, null, null, null);

	if($datos0!=0){

		$condicion.=" and ano=".$ano_formular." and proyecto_accion=".$var;

		$datos = $this->cpyd01_proy_accion->findCount($condicion, null, null, null);

		if($datos!=0) {

			$this->set('transferir',false);
			$datos = $this->cpyd01_proy_accion->findAll($condicion, null, "fecha_creacion,cod_proy_accion ASC", null);
			$this->set('datos',$datos);


		}else $this->set('transferir',true);


	}else $this->set('transferir',false);

	$this->set('titulo',$var==1 ? "PROYECTOS" : "ACCIONES CENTRALIZADAS");
	$this->set('opcion',$var);
	$this->set('modelo','cpyd01_proy_accion');

	$this->set('formular',$ano_formular);

}//fin function


function guardar_proyecto($var=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	//opcion 1 de proyecto


		$rs_count=$this->cpyd01_proy_accion->findCount($this->SQLCA()." and cod_proy_accion='".$this->data['cpyp01_proy_accion']['cod_proyecto']."' and proyecto_accion=$var");

		if($rs_count==0){
			$onapre=$var==1 ? $this->data['cpyp01_proy_accion']['cod_onapre'] : "N/A";
			$sql = "INSERT INTO cpyd01_proy_accion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst,cod_dep, ano, cod_proy_accion, denominacion, descripcion, unidad_ejecutora, responsable, cod_onapre, proyecto_accion, fecha_creacion) VALUES (".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$this->data['cpyp01_proy_accion']['ej_formular'].",'".$this->data['cpyp01_proy_accion']['cod_proyecto']."','"
				.$this->data['cpyp01_proy_accion']['denominacion']."','".$this->data['cpyp01_proy_accion']['descripcion']."','".$this->data['cpyp01_proy_accion']['unidad_ej']."','".$this->data['cpyp01_proy_accion']['funcionario']."','".$onapre."',".$var.",'".$this->Cfecha($this->data['cpyp01_proy_accion']['fecha'], 'A-M-D')."');";
			$rs=$this->cpyd01_proy_accion->execute($sql);

			if($rs>1){
   	       	 	$this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
       	 	}else{
   	      	  $this->set("errorMessage","Los Datos No Fuerón Guardados");
        	}

		}else $this->set("errorMessage","Los Datos No Fuerón Guardados, el Codigo ya esta registrado");

		$this->index($var);
		$this->set('titulo',$var==1 ? "PROYECTOS" : "ACCIONES CENTRALIZADAS");
		$this->render('index');
		$this->set('modelo','cpyd01_proy_accion');



}//fin function

function eliminar ($codigo_pro=null,$var=null,$ano=null){
	$this->layout="ajax";

	$rs=$this->cpyd01_accion_espec->findCount($this->SQLCA()." and cod_proy_accion='$codigo_pro' and proyecto_accion='$var' and ano='$ano'");

	if($rs==0){
	$rs=$this->cpyd01_proy_accion->execute("delete from cpyd01_proy_accion where ". $this->SQLCA()." and cod_proy_accion='$codigo_pro' and proyecto_accion='$var' and ano='$ano'");

		if($rs > 1){
			$this->set('Message_existe','registro eliminado con exito');
		}else{
			$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		}
	}else{
			$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO, TIENE REGISTRADAS ACCIONES ESPECIFICAS');

	}
		$this->index($var);
		$this->render('index');

}//fin eliminar

function editar ($codigo_pro=null,$var=null,$ano=null){
	$this->layout="ajax";

		$rs=$this->cpyd01_proy_accion->findAll($this->SQLCA()." and cod_proy_accion='$codigo_pro' and proyecto_accion='$var' and ano='$ano'");

		$this->set('datos',$rs);
		$this->set('opcion',$var);
		$this->set('titulo',$var==1 ? "PROYECTOS" : "ACCIONES CENTRALIZADAS");
		$this->set('modelo','cpyd01_proy_accion');

}//fin editar


function guardar_editar ($codigo_pro=null,$var=null,$ano=null){
	$this->layout="ajax";

  	$sql = "UPDATE cpyd01_proy_accion SET  denominacion='".$this->data['cpyp01_proy_accion']['denominacion']."', descripcion='".$this->data['cpyp01_proy_accion']['descripcion']."', unidad_ejecutora='".$this->data['cpyp01_proy_accion']['unidad_ej']."', responsable='".$this->data['cpyp01_proy_accion']['funcionario']."', cod_onapre='".$this->data['cpyp01_proy_accion']['cod_onapre']."'  where ". $this->SQLCA()." and cod_proy_accion='".$codigo_pro."' and proyecto_accion='".$var."' and ano='".$ano."'";

		$rs=$this->cpyd01_proy_accion->execute($sql);

		$this->set('opcion',$var);
		$this->set('titulo',$var==1 ? "PROYECTOS" : "ACCIONES CENTRALIZADAS");
		$this->set('modelo','cpyd01_proy_accion');


		if($rs > 1){
			$this->set('Message_existe','registro actualizado con exito');
		}else{
			$this->set('errorMessage', 'EL DATO NO PUDO SER actualizado');
		}

		$this->index($var);
		$this->render('index');


}//fin editar

function revisar($var=null){
	$this->layout="ajax";

	$datos0 = $this->cpyd01_proy_accion->findCount($this->SQLCA_INST()." and cod_proy_accion='".strtoupper($var)."'");

	if($datos0!=0){
			$this->set('existe',true);
			$this->set('errorMessage', 'EL Código ya se encuentra registrado, por favor ingrese otro código');
		}else{
			$this->set('existe',false);
	}

}

function transferir($var=null){
$ano=$this->data['transferir']['ano_ejecucion'];

$rs=$this->cpyd01_proy_accion->findAll($this->SQLCA($ano)." and proyecto_accion=".$var);

	foreach($rs as $rs){

			$sql = "INSERT INTO cpyd01_proy_accion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst,cod_dep, ano, cod_proy_accion, denominacion, descripcion, unidad_ejecutora, responsable, cod_onapre, proyecto_accion, fecha_creacion) VALUES (".$rs['cpyd01_proy_accion']['cod_presi'].",".$rs['cpyd01_proy_accion']['cod_entidad'].",".$rs['cpyd01_proy_accion']['cod_tipo_inst'].",".$rs['cpyd01_proy_accion']['cod_inst'].",".$rs['cpyd01_proy_accion']['cod_dep'].",".$this->data['transferir']['ano_formular'].",'".$rs['cpyd01_proy_accion']['cod_proy_accion']."','"
				.$rs['cpyd01_proy_accion']['denominacion']."','".$rs['cpyd01_proy_accion']['descripcion']."','".$rs['cpyd01_proy_accion']['unidad_ejecutora']."','".$rs['cpyd01_proy_accion']['responsable']."','".$rs['cpyd01_proy_accion']['cod_onapre']."',".$var.",'".$this->Cfecha($rs['cpyd01_proy_accion']['fecha_creacion'], 'A-M-D')."');";
			$rs=$this->cpyd01_proy_accion->execute($sql);

	}


$rs=$this->cpyd01_accion_espec->findAll($this->SQLCA($ano)." and proyecto_accion=".$var);

	foreach($rs as $rs){

			$sql = "INSERT INTO cpyd01_accion_espec (cod_presi, cod_entidad, cod_tipo_inst, cod_inst,cod_dep, ano, cod_proy_accion, denominacion, descripcion, unidad_ejecutora, responsable, cod_onapre, proyecto_accion,fecha_creacion, cod_accion_espec) VALUES (".$rs['cpyd01_accion_espec']['cod_presi'].",".$rs['cpyd01_accion_espec']['cod_entidad'].",".$rs['cpyd01_accion_espec']['cod_tipo_inst'].",".$rs['cpyd01_accion_espec']['cod_inst'].",".$rs['cpyd01_accion_espec']['cod_dep'].",".$this->data['transferir']['ano_formular'].",'".$rs['cpyd01_accion_espec']['cod_proy_accion']."','"
				.$rs['cpyd01_accion_espec']['denominacion']."','".$rs['cpyd01_accion_espec']['descripcion']."','".$rs['cpyd01_accion_espec']['unidad_ejecutora']."','".$rs['cpyd01_accion_espec']['responsable']."','".$rs['cpyd01_accion_espec']['cod_onapre']."',".$var.",'".$this->Cfecha($rs['cpyd01_accion_espec']['fecha_creacion'], 'A-M-D')."','".$rs['cpyd01_accion_espec']['cod_accion_espec']."');";
			$rs=$this->cpyd01_proy_accion->execute($sql);

	}


$rs=$this->cpyd01_accion_espec_activ->findAll($this->SQLCA($ano)." and proyecto_accion=".$var);

	foreach($rs as $rs){

			$sql = "INSERT INTO cpyd01_accion_espec_activ (cod_presi, cod_entidad, cod_tipo_inst, cod_inst,cod_dep, ano, cod_proy_accion, denominacion, descripcion, unidad_ejecutora, responsable, cod_onapre, proyecto_accion,fecha_creacion, cod_accion_espec,cod_accion_espec_activ,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra)VALUES (".$rs['cpyd01_accion_espec_activ']['cod_presi'].",".$rs['cpyd01_accion_espec_activ']['cod_entidad'].",".$rs['cpyd01_accion_espec_activ']['cod_tipo_inst'].",".$rs['cpyd01_accion_espec_activ']['cod_inst'].",".$rs['cpyd01_accion_espec_activ']['cod_dep'].",".$this->data['transferir']['ano_formular'].",'".$rs['cpyd01_accion_espec_activ']['cod_proy_accion']."','"
				.$rs['cpyd01_accion_espec_activ']['denominacion']."','".$rs['cpyd01_accion_espec_activ']['descripcion']."','".$rs['cpyd01_accion_espec_activ']['unidad_ejecutora']."','".$rs['cpyd01_accion_espec_activ']['responsable']."','".$rs['cpyd01_accion_espec_activ']['cod_onapre']."',".$var.",'".$this->Cfecha($rs['cpyd01_accion_espec_activ']['fecha_creacion'], 'A-M-D')."','".$rs['cpyd01_accion_espec_activ']['cod_accion_espec']."',".$rs['cpyd01_accion_espec_activ']['cod_accion_espec_activ'].",".$rs['cpyd01_accion_espec_activ']['cod_sector']
				.",".$rs['cpyd01_accion_espec_activ']['cod_programa'].",".$rs['cpyd01_accion_espec_activ']['cod_sub_prog'].",".$rs['cpyd01_accion_espec_activ']['cod_proyecto'].",".$rs['cpyd01_accion_espec_activ']['cod_activ_obra'].");";
			$rs=$this->cpyd01_proy_accion->execute($sql);

	}

		$this->set('Message_existe','TRANSFERENCIA REALIZADA CON EXITO');

		$this->index($var);
		$this->render('index');
}

}?>
