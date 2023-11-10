<?php

 class Cugp02direccionController extends AppController{

	var $name = 'Cugp02direccion';
	var $uses = array('cfpd01_formulacion', 'cfpd02_sector', 'cfpd02_programa', 'cfpd05_auxiliar', 'cfpd02_sub_prog',
                      'cfpd02_proyecto', 'cugd02_institucion', 'cugd02_dependencia', 'cugd02_direccionsuperior', 'cugd02_coordinacion',
                      'cugd02_secretaria', 'cugd02_direccion', 'ccfd04_cierre_mes', 'v_cugd02_verificacion');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector!=null){
   	  	  if($extra==null){
   	  	foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]="0".$x;
        	}else{
	           $Var[$x]=$x;
        	}
	    }//fin each
   	  }else{
          foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]=$extra.".0".$x;
        	}else{
	           $Var[$x]=$extra.".".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }
}//fin AddCero


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
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

/*
	if($modulo=='0'){
		return;
	}else{
 		echo "<h3>LO SIENTO - SOLO LA ADMINISTRACION CENTRAL TIENE ACCESO A ESTE PROGRAMA!!</h3>";

		exit;
	}
*/

return;
}




 function selec_sector($var=null){
   $this->layout = "ajax";
    $ano_ejecucion=$this->ano_ejecucion();
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year2            = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    = $this->ano_ejecucion();
    $ano              = $ano_formulacion;

   if($this->data['cugp02direccion']['codigo']){$var = $this->data['cugp02direccion']['codigo'];   $this->set('opcion', $var);
   }else{ $this->set('opcion', $var);  }
   $lista =  $this->cfpd02_sector->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.programa.denominacion');
   $this->concatena($lista, 'sector');
   //$this->set('sector', $lista);
}//fin s sector


function selec_programa($var=null, $aux=null){
	$this->layout = "ajax";
	$ano_ejecucion=$this->ano_ejecucion();
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year2            = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    = $this->ano_ejecucion();
    $ano              = $ano_formulacion;

	if($this->data['cugp02direccion']['codigo'] &&  $var!=null){ $this->set('selecion', $this->data['cugp02direccion']['codigo']); }
	if($var==null){ $var = $this->data['cugp02direccion']['codigo']; }
	if($aux!=null){  $this->set('selecion', $aux);}
	$this->set('opcion1', $var);
	if($var!=null && $var!='otros'){
		$listaPro=$this->cfpd02_programa->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$var.' ', ' cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
		$this->concatena($listaPro, 'programa');
		}else{   $this->set('programa', '');}
}//fin s programa


function selec_sub_prog($var1=null, $var2=null , $aux=null){
    $this->layout = "ajax";
    $ano_ejecucion=$this->ano_ejecucion();
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year2            = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    = $this->ano_ejecucion();
    $ano              = $ano_formulacion;

	if($this->data['cugp02direccion']['codigo']  &&  $var2!=null){ $this->set('selecion', $this->data['cugp02direccion']['codigo']); }
	if($var2==null){ $var2 = $this->data['cugp02direccion']['codigo'];}
	if($aux!=null){  $this->set('selecion', $aux);}
		$this->set('opcion1', $var1);
		$this->set('opcion2', $var2);
		if($var2!=null && $var2!='otros'){
		$listaSP=$this->cfpd02_sub_prog->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.'', ' cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
		$this->concatena($listaSP, 'sub_prog');
		}else{   $this->set('sub_prog', ''); }
}//fin s sub prog

function selec_proyecto($var1=null, $var2=null, $var3=null , $aux=null){
	$this->layout = "ajax";
	$ano_ejecucion=$this->ano_ejecucion();
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year2            = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    = $this->ano_ejecucion();
    $ano              = $ano_formulacion;

	if($this->data['cugp02direccion']['codigo']  &&  $var3!=null){ $this->set('selecion', $this->data['cugp02direccion']['codigo']); }
	if($var3==null){ $var3 = $this->data['cugp02direccion']['codigo'];}
	if($aux!=null){  $this->set('selecion', $aux);}
		$this->set('opcion1', $var1);
		$this->set('opcion2', $var2);
		$this->set('opcion3', $var3);
		if($var3!=null && $var3!='otros'){
		$listaProyecto=$this->cfpd02_proyecto->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.' and cod_sub_prog = '.$var3.'', ' cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
        $this->concatena($listaProyecto, 'proyecto');
		}else{   $this->set('proyecto', ''); }
}//fin s proyecto


function selec_activ_obra($var1=null, $var2=null, $var3=null, $var4=null , $aux=null){
	$this->layout = "ajax";
}



function index($institucion=null, $dependencia=null, $dir_superior=null, $coordinacion=null, $secretaria=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$ano_ejecucion=$this->ano_ejecucion();
	$opcion = 'no';
	$data = '';
	$denominacion='';

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	if($institucion == null){
		$institucion = $cod_inst;
	}
	if($dependencia == null){
		$dependencia2 = $cod_dep;
	}
	if($dir_superior == null && $dependencia == null){
		$ds = $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia2,null,'cod_dir_superior ASC');
		$dir_superior2 = $ds[0]['cugd02_direccionsuperior']['cod_dir_superior'];
	}
	if($coordinacion == null && $dependencia==null && $dir_superior == null){
		$ds = $this->cugd02_coordinacion->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia2." and cod_dir_superior=".$dir_superior2,null,'cod_coordinacion ASC');
		$coordinacion2 = $ds[0]['cugd02_coordinacion']['cod_coordinacion'];
	}
	if($coordinacion == null && $dependencia==null && $dir_superior == null && $coordinacion == null){
		$ds = $this->cugd02_secretaria->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia2." and cod_dir_superior=".$dir_superior2." and cod_coordinacion=".$coordinacion2,null,'cod_secretaria ASC');
		$secretaria2 = $ds[0]['cugd02_secretaria']['cod_secretaria'];
	}

	if($dependencia == null){
		$dependencia = $dependencia2;
	}
	if($dir_superior == null){
		$dir_superior = $dir_superior2;
	}
	if($coordinacion == null){
		$coordinacion = $coordinacion2;
	}
	if($secretaria == null){
		$secretaria = $secretaria2;
	}

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year2            = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    = $this->ano_ejecucion();
    $ano              = $ano_formulacion;

$listaSector=$this->cfpd02_sector->generateList($this->condicion().' and ano='.$ano, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
$this->concatena($listaSector, 'sector');

if($institucion!=null){
			$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst').""." and cod_institucion=".$this->Session->read('SScodinst')."";
			$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
        	$this->set('selecion_institucion', $institucion);
        	//$opcion = 'si';

}else{
	        $sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst').""." and cod_institucion=".$this->Session->read('SScodinst')."";
        	$this->set('selecion_institucion', '');
           	if($this->cugd02_institucion->findCount($sql_re) != 0){
        	$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
	   		}else{$this->set('institucion', 'vacio');}
			//$opcion = 'si';

}//fin else


if($dependencia!=null){
			$sql_dependencia="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." ";
			if($cod_dep != 1){$sql_dependencia=  "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}
			$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_dependencia, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
        	$this->set('selecion_dependencia', $dependencia);
        	//$opcion = 'si';
}else{
	if($institucion==null){
			$this->set('selecion_dependencia', '');
			$this->set('dependencia', '');
			//$opcion = 'si';
	}else{
			$sql_dependencia="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." ";
        	if($cod_dep != 1){$sql_dependencia=  "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}
        	$this->set('selecion_dependencia', '');
           	if($this->cugd02_dependencia->findCount($sql_dependencia) != 0){
        	$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_dependencia, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
	   		}else{$this->set('dependencia', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($dir_superior!=null){
			$sql_direccionsuperior="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."";
			$consulta_direccionsuperior = $this->cugd02_direccionsuperior->generateList($sql_direccionsuperior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$this->set('dir_superior', $consulta_direccionsuperior);
        	$this->set('selecion_dir_superior', $dir_superior);
        	//$opcion = 'si';
}else{
	if($dependencia==null){
			$this->set('selecion_dir_superior', '');
			$this->set('dir_superior', '');
			//$opcion = 'si';
	}else{
			$sql_direccionsuperior="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." ";
        	$this->set('selecion_dir_superior', '');
           	if($this->cugd02_direccionsuperior->findCount($sql_direccionsuperior) != 0){
        	$consulta_direccionsuperior = $this->cugd02_direccionsuperior->generateList($sql_direccionsuperior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$this->set('dir_superior', $consulta_direccionsuperior);
	   		}else{$this->set('dir_superior', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($coordinacion!=null){
			$sql_coordinacion="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."";
			$consulta_coordinacion = $this->cugd02_coordinacion->generateList($sql_coordinacion, 'denominacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	   		$this->set('coordinacion', $consulta_coordinacion);
        	$this->set('selecion_coordinacion', $coordinacion);
        	//$opcion = 'si';
}else{
	if($dir_superior==null){
			$this->set('selecion_coordinacion', '');
			$this->set('coordinacion', '');
			//$opcion = 'si';
	}else{
			$sql_coordinacion="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." ";
        	$this->set('selecion_coordinacion', '');
           	if($this->cugd02_coordinacion->findCount($sql_coordinacion) != 0){
        	$consulta_coordinacion = $this->cugd02_coordinacion->generateList($sql_coordinacion, 'denominacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	   		$this->set('coordinacion', $consulta_coordinacion);
	   		}else{$this->set('coordinacion', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($secretaria!=null){
			$sql_secretaria="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." ";
			$consulta_secretaria = $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$this->set('secretaria', $consulta_secretaria);
        	$this->set('selecion_secretaria', $secretaria);
        	//$opcion = 'si';
}else{
	if($coordinacion==null){
			$this->set('selecion_secretaria', '');
			$this->set('secretaria', '');
			//$opcion = 'si';
	}else{
			$sql_secretaria="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." ";
        	$this->set('selecion_secretaria', '');
           	if($this->cugd02_secretaria->findCount($sql_secretaria) != 0){
        	$consulta_secretaria = $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$this->set('secretaria', $consulta_secretaria);
	   		}else{$this->set('secretaria', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."  and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion."  and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$selet."";
		if($this->cugd02_direccion->findCount($sql_selet) != 0){
 			$data = $this->cugd02_direccion->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_direccion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){
 				$this->set('selecion_direccion_2', $selet);
 				$selet=$data_aux_pre['cugd02_direccion']['denominacion'];

               $vari1 = $data_aux_pre['cugd02_direccion']['cod_sector'];
               $vari2 = $data_aux_pre['cugd02_direccion']['cod_programa'];
               $vari3 = $data_aux_pre['cugd02_direccion']['cod_sub_prog'];

 	    $listaPro=$this->cfpd02_programa->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$vari1.' ', ' cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
		$this->concatena($listaPro, 'programa');

		$listaSP=$this->cfpd02_sub_prog->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$vari1.'  and cod_programa = '.$vari2.'', ' cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
		$this->concatena($listaSP, 'sub_prog');

		$listaProyecto=$this->cfpd02_proyecto->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$vari1.'  and cod_programa = '.$vari2.' and cod_sub_prog = '.$vari3.'', ' cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
        $this->concatena($listaProyecto, 'proyecto');



 			}//fin if
		}else{ $opcion = 'si'; }
			$this->set('selecion_direccion', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_direccion','otros');
			$nume = $this->cugd02_direccion->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria."",null,'cod_direccion DESC');
			//pr($nume);
			if($nume == null){//echo 'hello';
				$numero = 1;
			}else{
				$numero = $nume[0]['cugd02_direccion']['cod_direccion'] + 1;
			}
			$this->set('numero',$numero);
			$depx = $this->cugd02_secretaria->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria."");
			$fr = $depx[0]['cugd02_secretaria']['funcionario_responsable'];
			$ug = $depx[0]['cugd02_secretaria']['direccion'];
			$ca = $depx[0]['cugd02_secretaria']['cod_area'];
			$tf = $depx[0]['cugd02_secretaria']['telefonos'];
			$fa = $depx[0]['cugd02_secretaria']['fax'];
			$em = $depx[0]['cugd02_secretaria']['email'];
			$this->set('fr',$fr);
			$this->set('ug',$ug);
			$this->set('ca',$ca);
			$this->set('tf',$tf);
			$this->set('fa',$fa);
			$this->set('em',$em);
			$this->set('nuevo','si');
}else{

			if($secretaria!=null){
			$sql_direccion = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria."";
			if($this->cugd02_direccion->findCount($sql_direccion) != 0){
			$consulta_direccion = $this->cugd02_direccion->generateList($sql_direccion, 'denominacion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	   		$denominacion =  $this->cugd02_direccion->generateList($sql_direccion, 'denominacion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	   		$this->set('direccion', $consulta_direccion);
			}
		}

			$this->set('selecion_direccion','');
			$opcion = 'si';
}



	$this->set('denominacion', $denominacion);
	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', $boton);
}




















function grabar($institucion=null, $dependencia=null, $dir_superior=null, $coordinacion=null, $secretaria=null, $selet=null, $boton=null){


	$this->layout = "ajax";
    $ano_ejecucion=$this->ano_ejecucion();
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year2            = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    = $this->ano_ejecucion();
    $ano              = $ano_formulacion;



	$opcion = 'no';
	$data = '';
	$denominacion='';

	 if($institucion==null){$institucion=$this->data['cugp02direccion']['cod_institucion'];}
	 if($dependencia==null){$dependencia=$this->data['cugp02direccion']['cod_dependencia'];}
	 if($dir_superior==null){$dir_superior=$this->data['cugp02direccion']['cod_dir_superior'];}
	 if($coordinacion==null){$coordinacion=$this->data['cugp02direccion']['cod_coordinacion'];}
	 if($secretaria==null){$secretaria=$this->data['cugp02direccion']['cod_secretaria'];}
	 if($selet==null){$selet=$this->data['cugp02direccion']['cod_direccion'];}


$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')."and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$selet."";
if($this->cugd02_direccion->findCount($sql_re) == 0){
$sql = "INSERT INTO cugd02_direccion (cod_tipo_institucion, cod_institucion, cod_dependencia, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, denominacion, funcionario_responsable, direccion, cod_area, telefonos, fax, email, cod_sector, cod_programa, cod_sub_prog, cod_proyecto)   VALUES  ( '".$this->Session->read('SScodtipoinst')."',  '".$this->data['cugp02direccion']['cod_institucion']."',  '".$this->data['cugp02direccion']['cod_dependencia']."',  '".$this->data['cugp02direccion']['cod_dir_superior']."',  '".$this->data['cugp02direccion']['cod_coordinacion']."',  '".$this->data['cugp02direccion']['cod_secretaria']."',  '".$this->data['cugp02direccion']['cod_direccion']."', '".$this->data['cugp02direccion']['denominacion']."', '".$this->data['cugp02direccion']['funcionario_responsable']."', '".$this->data['cugp02direccion']['direccion']."', '".$this->data['cugp02direccion']['cod_area']."', '".$this->data['cugp02direccion']['telefonos']."', '".$this->data['cugp02direccion']['fax']."', '".$this->data['cugp02direccion']['email']."', '".$this->data['cugp02direccion']['cod_sector']."', '".$this->data['cugp02direccion']['cod_programa']."', '".$this->data['cugp02direccion']['cod_sub_prog']."', '".$this->data['cugp02direccion']['cod_proyecto']."')";

$this->cugd02_direccion->execute($sql);
}else{
$sql ="UPDATE cugd02_direccion SET  denominacion = '".$this->data['cugp02direccion']['denominacion']."', funcionario_responsable = '".$this->data['cugp02direccion']['funcionario_responsable']."', direccion = '".$this->data['cugp02direccion']['direccion']."', cod_area = '".$this->data['cugp02direccion']['cod_area']."', telefonos = '".$this->data['cugp02direccion']['telefonos']."', fax = '".$this->data['cugp02direccion']['fax']."', email = '".$this->data['cugp02direccion']['email']."', cod_sector = '".$this->data['cugp02direccion']['cod_sector']."', cod_programa = '".$this->data['cugp02direccion']['cod_programa']."', cod_sub_prog = '".$this->data['cugp02direccion']['cod_sub_prog']."', cod_proyecto = '".$this->data['cugp02direccion']['cod_proyecto']."'   where cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->data['cugp02direccion']['cod_institucion']." and cod_dependencia=".$this->data['cugp02direccion']['cod_dependencia']."  and cod_dir_superior=".$this->data['cugp02direccion']['cod_dir_superior']." and cod_coordinacion=".$this->data['cugp02direccion']['cod_coordinacion']." and cod_secretaria=".$this->data['cugp02direccion']['cod_secretaria']." and cod_direccion=".$this->data['cugp02direccion']['cod_direccion']."";
$this->cugd02_direccion->execute($sql);

}




if($institucion!=null){
			$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst').""." and cod_institucion=".$this->Session->read('SScodinst')."";
			$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
        	$this->set('selecion_institucion', $institucion);
        	//$opcion = 'si';

}else{
	        $sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst').""." and cod_institucion=".$this->Session->read('SScodinst')."";
        	$this->set('selecion_institucion', '');
           	if($this->cugd02_institucion->findCount($sql_re) != 0){
        	$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
	   		}else{$this->set('institucion', 'vacio');}
			//$opcion = 'si';

}//fin else


if($dependencia!=null){
			$sql_dependencia="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." ";
if($cod_dep != 1){$sql_dependencia=  "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}
			$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_dependencia, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
        	$this->set('selecion_dependencia', $dependencia);
        	//$opcion = 'si';
}else{
	if($institucion==null){
			$this->set('selecion_dependencia', '');
			$this->set('dependencia', '');
			//$opcion = 'si';
	}else{
			$sql_dependencia="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." ";
        	if($cod_dep != 1){$sql_dependencia=  "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}
        	$this->set('selecion_dependencia', '');
           	if($this->cugd02_dependencia->findCount($sql_dependencia) != 0){
        	$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_dependencia, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
	   		}else{$this->set('dependencia', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($dir_superior!=null){
			$sql_direccionsuperior="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."";
			$consulta_direccionsuperior = $this->cugd02_direccionsuperior->generateList($sql_direccionsuperior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$this->set('dir_superior', $consulta_direccionsuperior);
        	$this->set('selecion_dir_superior', $dir_superior);
        	//$opcion = 'si';
}else{
	if($dependencia==null){
			$this->set('selecion_dir_superior', '');
			$this->set('dir_superior', '');
			//$opcion = 'si';
	}else{
			$sql_direccionsuperior="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." ";
        	$this->set('selecion_dir_superior', '');
           	if($this->cugd02_direccionsuperior->findCount($sql_direccionsuperior) != 0){
        	$consulta_direccionsuperior = $this->cugd02_direccionsuperior->generateList($sql_direccionsuperior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$this->set('dir_superior', $consulta_direccionsuperior);
	   		}else{$this->set('dir_superior', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($coordinacion!=null){
			$sql_coordinacion="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."";
			$consulta_coordinacion = $this->cugd02_coordinacion->generateList($sql_coordinacion, 'denominacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	   		$this->set('coordinacion', $consulta_coordinacion);
        	$this->set('selecion_coordinacion', $coordinacion);
        	//$opcion = 'si';
}else{
	if($dir_superior==null){
			$this->set('selecion_coordinacion', '');
			$this->set('coordinacion', '');
			//$opcion = 'si';
	}else{
			$sql_coordinacion="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." ";
        	$this->set('selecion_coordinacion', '');
           	if($this->cugd02_coordinacion->findCount($sql_coordinacion) != 0){
        	$consulta_coordinacion = $this->cugd02_coordinacion->generateList($sql_coordinacion, 'denominacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	   		$this->set('coordinacion', $consulta_coordinacion);
	   		}else{$this->set('coordinacion', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($secretaria!=null){
			$sql_secretaria="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." ";
			$consulta_secretaria = $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$this->set('secretaria', $consulta_secretaria);
        	$this->set('selecion_secretaria', $secretaria);
        	//$opcion = 'si';
}else{
	if($coordinacion==null){
			$this->set('selecion_secretaria', '');
			$this->set('secretaria', '');
			//$opcion = 'si';
	}else{
			$sql_secretaria="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." ";
        	$this->set('selecion_secretaria', '');
           	if($this->cugd02_secretaria->findCount($sql_secretaria) != 0){
        	$consulta_secretaria = $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$this->set('secretaria', $consulta_secretaria);
	   		}else{$this->set('secretaria', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."  and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion."  and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$selet."";
		if($this->cugd02_direccion->findCount($sql_selet) != 0){
 			$data = $this->cugd02_direccion->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_direccion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_direccion_2', $selet); $selet=$data_aux_pre['cugd02_direccion']['denominacion'];

 			   $vari1 = $data_aux_pre['cugd02_direccion']['cod_sector'];
               $vari2 = $data_aux_pre['cugd02_direccion']['cod_programa'];
               $vari3 = $data_aux_pre['cugd02_direccion']['cod_sub_prog'];

 	    $listaPro=$this->cfpd02_programa->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$vari1.' ', ' cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
		$this->concatena($listaPro, 'programa');

		$listaSP=$this->cfpd02_sub_prog->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$vari1.'  and cod_programa = '.$vari2.'', ' cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
		$this->concatena($listaSP, 'sub_prog');

		$listaProyecto=$this->cfpd02_proyecto->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$vari1.'  and cod_programa = '.$vari2.' and cod_sub_prog = '.$vari3.'', ' cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
        $this->concatena($listaProyecto, 'proyecto');

        }//fin if

		}else{ $opcion = 'si'; }
			$this->set('selecion_direccion', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_direccion','otros');
}else{

			if($secretaria!=null){
			$sql_direccion = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria."";
			if($this->cugd02_direccion->findCount($sql_direccion) != 0){
			$consulta_direccion = $this->cugd02_direccion->generateList($sql_direccion, 'denominacion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	   		$denominacion =  $this->cugd02_direccion->generateList($sql_direccion, 'denominacion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	   		$this->set('direccion', $consulta_direccion);
			}
		}

			$this->set('selecion_direccion','');
			$opcion = 'si';
}







	$this->set('data', $data);
	$this->set('denominacion', $denominacion);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', $boton);




}

































function eliminar($institucion=null, $dependencia=null, $dir_superior=null, $coordinacion=null, $secretaria=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';
    $ano_ejecucion=$this->ano_ejecucion();
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$sql_re = "cod_presi=".$cod_presi."  and cod_entidad=".$cod_entidad."  and cod_tipo_inst=".$cod_tipo_inst."  and cod_inst=".$cod_inst."  and cod_dep=".$dependencia."  and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$selet."";
    if($this->v_cugd02_verificacion->findCount($sql_re)==0){
		$sql= "DELETE  FROM  cugd02_direccion  WHERE cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$selet." ";
		$this->cugd02_direccion->execute($sql);
		$denominacion='';
    }else{
		$this->set('errorMessage', "NO PUEDE ELIMINAR ESTE REGISTRO.....ESTA EN USO");
    }

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year2            = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    = $this->ano_ejecucion();
    $ano              = $ano_formulacion;




if($institucion!=null){
			$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst').""." and cod_institucion=".$this->Session->read('SScodinst')."";
			$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
        	$this->set('selecion_institucion', $institucion);
        	//$opcion = 'si';

}else{
	        $sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst').""." and cod_institucion=".$this->Session->read('SScodinst')."";
        	$this->set('selecion_institucion', '');
           	if($this->cugd02_institucion->findCount($sql_re) != 0){
        	$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
	   		}else{$this->set('institucion', 'vacio');}
			//$opcion = 'si';

}//fin else


if($dependencia!=null){
			$sql_dependencia="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." ";
			if($cod_dep != 1){$sql_dependencia=  "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}
			$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_dependencia, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
        	$this->set('selecion_dependencia', $dependencia);
        	//$opcion = 'si';
}else{
	if($institucion==null){
			$this->set('selecion_dependencia', '');
			$this->set('dependencia', '');
			//$opcion = 'si';
	}else{
			$sql_dependencia="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." ";
        	if($cod_dep != 1){$sql_dependencia=  "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}
        	$this->set('selecion_dependencia', '');
           	if($this->cugd02_dependencia->findCount($sql_dependencia) != 0){
        	$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_dependencia, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
	   		}else{$this->set('dependencia', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($dir_superior!=null){
			$sql_direccionsuperior="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."";
			$consulta_direccionsuperior = $this->cugd02_direccionsuperior->generateList($sql_direccionsuperior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$this->set('dir_superior', $consulta_direccionsuperior);
        	$this->set('selecion_dir_superior', $dir_superior);
        	//$opcion = 'si';
}else{
	if($dependencia==null){
			$this->set('selecion_dir_superior', '');
			$this->set('dir_superior', '');
			//$opcion = 'si';
	}else{
			$sql_direccionsuperior="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." ";
        	$this->set('selecion_dir_superior', '');
           	if($this->cugd02_direccionsuperior->findCount($sql_direccionsuperior) != 0){
        	$consulta_direccionsuperior = $this->cugd02_direccionsuperior->generateList($sql_direccionsuperior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$this->set('dir_superior', $consulta_direccionsuperior);
	   		}else{$this->set('dir_superior', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($coordinacion!=null){
			$sql_coordinacion="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."";
			$consulta_coordinacion = $this->cugd02_coordinacion->generateList($sql_coordinacion, 'denominacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	   		$this->set('coordinacion', $consulta_coordinacion);
        	$this->set('selecion_coordinacion', $coordinacion);
        	//$opcion = 'si';
}else{
	if($dir_superior==null){
			$this->set('selecion_coordinacion', '');
			$this->set('coordinacion', '');
			//$opcion = 'si';
	}else{
			$sql_coordinacion="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." ";
        	$this->set('selecion_coordinacion', '');
           	if($this->cugd02_coordinacion->findCount($sql_coordinacion) != 0){
        	$consulta_coordinacion = $this->cugd02_coordinacion->generateList($sql_coordinacion, 'denominacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	   		$this->set('coordinacion', $consulta_coordinacion);
	   		}else{$this->set('coordinacion', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($secretaria!=null){
			$sql_secretaria="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." ";
			$consulta_secretaria = $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$this->set('secretaria', $consulta_secretaria);
        	$this->set('selecion_secretaria', $secretaria);
        	//$opcion = 'si';
}else{
	if($coordinacion==null){
			$this->set('selecion_secretaria', '');
			$this->set('secretaria', '');
			//$opcion = 'si';
	}else{
			$sql_secretaria="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." ";
        	$this->set('selecion_secretaria', '');
           	if($this->cugd02_secretaria->findCount($sql_secretaria) != 0){
        	$consulta_secretaria = $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$this->set('secretaria', $consulta_secretaria);
	   		}else{$this->set('secretaria', 'vacio');}
			//$opcion = 'si';
		}
}//fin else


$selet=null;


if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."  and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion."  and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$selet."";
		if($this->cugd02_direccion->findCount($sql_selet) != 0){
 			$data = $this->cugd02_direccion->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_direccion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){
 				$this->set('selecion_direccion_2', $selet);
 				$selet=$data_aux_pre['cugd02_direccion']['denominacion'];

 			   $vari1 = $data_aux_pre['cugd02_direccion']['cod_sector'];
               $vari2 = $data_aux_pre['cugd02_direccion']['cod_programa'];
               $vari3 = $data_aux_pre['cugd02_direccion']['cod_sub_prog'];

 	    $listaPro=$this->cfpd02_programa->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$vari1.' ', ' cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
		$this->concatena($listaPro, 'programa');

		$listaSP=$this->cfpd02_sub_prog->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$vari1.'  and cod_programa = '.$vari2.'', ' cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
		$this->concatena($listaSP, 'sub_prog');

		$listaProyecto=$this->cfpd02_proyecto->generateList('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ano.' and cod_sector =  '.$vari1.'  and cod_programa = '.$vari2.' and cod_sub_prog = '.$vari3.'', ' cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
        $this->concatena($listaProyecto, 'proyecto');

 				}//fin if
		}else{ $opcion = 'si'; }
			$this->set('selecion_direccion', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_direccion','otros');
}else{

			if($secretaria!=null){
			$sql_direccion = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria."";
			if($this->cugd02_direccion->findCount($sql_direccion) != 0){
			$consulta_direccion = $this->cugd02_direccion->generateList($sql_direccion, 'denominacion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	   		$denominacion =  $this->cugd02_direccion->generateList($sql_direccion, 'denominacion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	   		$this->set('direccion', $consulta_direccion);
			}
		}

			$this->set('selecion_direccion','');
			$opcion = 'si';
}












	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('denominacion', $denominacion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', null);
}



















function consulta($pag_num=null) {

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$year2            = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    = $this->ano_ejecucion();
    $ano              = $ano_formulacion;

 		 $this->layout = "ajax";
		 $sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst').""." and cod_institucion=".$this->Session->read('SScodinst')."";

	 	 $institucion = $this->cugd02_institucion->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('var_institucion',$institucion);

	 	 if($cod_dep != 1){$sql_re .=  " and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}

	 	 $dependencia = $this->cugd02_dependencia->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('var_dependencia',$dependencia);

	 	 $dir_superior = $this->cugd02_direccionsuperior->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('var_dir_superior',$dir_superior);

	 	 $coordinacion = $this->cugd02_coordinacion->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('var_coordinacion',$coordinacion);

	 	 $secretaria = $this->cugd02_secretaria->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('var_secretaria',$secretaria);

	 	 $data = $this->cugd02_direccion->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('data', $data);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));



if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}




}//fin class



?>
