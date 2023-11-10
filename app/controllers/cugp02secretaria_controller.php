<?php

 class Cugp02secretariaController extends AppController{

	var $name = 'Cugp02secretaria';
	var $uses = array('cugd02_institucion', 'cugd02_dependencia', 'cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria', 'v_cugd02_verificacion');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');




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






function index($institucion=null, $dependencia=null, $dir_superior=null, $coordinacion=null, $selet=null, $boton=null){

	$this->layout = "ajax";
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

	if($dependencia == null){
		$dependencia = $dependencia2;
	}
	if($dir_superior == null){
		$dir_superior = $dir_superior2;
	}
	if($coordinacion == null){
		$coordinacion = $coordinacion2;
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




if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."  and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion."  and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$selet."";
		if($this->cugd02_secretaria->findCount($sql_selet) != 0){
 			$data = $this->cugd02_secretaria->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_secretaria->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_secretaria_2', $selet); $selet=$data_aux_pre['cugd02_secretaria']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_secretaria', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_secretaria','otros');
			$nume = $this->cugd02_secretaria->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion."",null,'cod_secretaria DESC');
			//pr($nume);
			if($nume == null){//echo 'hello';
				$numero = 1;
			}else{
				$numero = $nume[0]['cugd02_secretaria']['cod_secretaria'] + 1;
			}
			$this->set('numero',$numero);
			$depx = $this->cugd02_coordinacion->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion."");
			$fr = $depx[0]['cugd02_coordinacion']['funcionario_responsable'];
			$ug = $depx[0]['cugd02_coordinacion']['direccion'];
			$ca = $depx[0]['cugd02_coordinacion']['cod_area'];
			$tf = $depx[0]['cugd02_coordinacion']['telefonos'];
			$fa = $depx[0]['cugd02_coordinacion']['fax'];
			$em = $depx[0]['cugd02_coordinacion']['email'];
			$this->set('fr',$fr);
			$this->set('ug',$ug);
			$this->set('ca',$ca);
			$this->set('tf',$tf);
			$this->set('fa',$fa);
			$this->set('em',$em);
			$this->set('nuevo','si');
}else{

			if($coordinacion!=null){
			$sql_secretaria = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion."";
			if($this->cugd02_secretaria->findCount($sql_secretaria) != 0){
			$consulta_secretaria = $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$denominacion =  $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$this->set('secretaria', $consulta_secretaria);
			}
		}

			$this->set('selecion_secretaria','');
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




















function grabar($institucion=null, $dependencia=null, $dir_superior=null, $coordinacion=null,  $selet=null, $boton=null){


	$this->layout = "ajax";

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$opcion = 'no';
	$data = '';
	$denominacion='';

	 if($institucion==null){$institucion=$this->data['cugp02secretaria']['cod_institucion'];}
	 if($dependencia==null){$dependencia=$this->data['cugp02secretaria']['cod_dependencia'];}
	 if($dir_superior==null){$dir_superior=$this->data['cugp02secretaria']['cod_dir_superior'];}
	 if($coordinacion==null){$coordinacion=$this->data['cugp02secretaria']['cod_coordinacion'];}
	 if($selet==null){$selet=$this->data['cugp02secretaria']['cod_secretaria'];}


$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')."and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$selet."";
if($this->cugd02_secretaria->findCount($sql_re) == 0){
$sql = "INSERT INTO cugd02_secretaria (cod_tipo_institucion, cod_institucion, cod_dependencia, cod_dir_superior, cod_coordinacion, cod_secretaria, denominacion, funcionario_responsable, direccion, cod_area, telefonos, fax, email)   VALUES  ( '".$this->Session->read('SScodtipoinst')."',  '".$this->data['cugp02secretaria']['cod_institucion']."',  '".$this->data['cugp02secretaria']['cod_dependencia']."',  '".$this->data['cugp02secretaria']['cod_dir_superior']."',  '".$this->data['cugp02secretaria']['cod_coordinacion']."',  '".$this->data['cugp02secretaria']['cod_secretaria']."', '".$this->data['cugp02secretaria']['denominacion']."', '".$this->data['cugp02secretaria']['funcionario_responsable']."', '".$this->data['cugp02secretaria']['direccion']."', '".$this->data['cugp02secretaria']['cod_area']."', '".$this->data['cugp02secretaria']['telefonos']."', '".$this->data['cugp02secretaria']['fax']."', '".$this->data['cugp02secretaria']['email']."')";

$this->cugd02_secretaria->execute($sql);
}else{
$sql ="UPDATE cugd02_secretaria SET  denominacion = '".$this->data['cugp02secretaria']['denominacion']."', funcionario_responsable = '".$this->data['cugp02secretaria']['funcionario_responsable']."', direccion = '".$this->data['cugp02secretaria']['direccion']."', cod_area = '".$this->data['cugp02secretaria']['cod_area']."', telefonos = '".$this->data['cugp02secretaria']['telefonos']."', fax = '".$this->data['cugp02secretaria']['fax']."', email = '".$this->data['cugp02secretaria']['email']."'   where cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->data['cugp02secretaria']['cod_institucion']." and cod_dependencia=".$this->data['cugp02secretaria']['cod_dependencia']."  and cod_dir_superior=".$this->data['cugp02secretaria']['cod_dir_superior']." and cod_coordinacion=".$this->data['cugp02secretaria']['cod_coordinacion']." and cod_secretaria=".$this->data['cugp02secretaria']['cod_secretaria']."";
$this->cugd02_secretaria->execute($sql);

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




if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."  and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion."  and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$selet."";
		if($this->cugd02_secretaria->findCount($sql_selet) != 0){
 			$data = $this->cugd02_secretaria->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_secretaria->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_secretaria_2', $selet); $selet=$data_aux_pre['cugd02_secretaria']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_secretaria', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_secretaria','otros');
}else{

			if($coordinacion!=null){
			$sql_secretaria = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion."";
			if($this->cugd02_secretaria->findCount($sql_secretaria) != 0){
			$consulta_secretaria = $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$denominacion =  $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$this->set('secretaria', $consulta_secretaria);
			}
		}

			$this->set('selecion_secretaria','');
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

































function eliminar($institucion=null, $dependencia=null, $dir_superior=null, $coordinacion=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');


	$sql_re = "cod_presi=".$cod_presi."  and cod_entidad=".$cod_entidad."  and cod_tipo_inst=".$cod_tipo_inst."  and cod_inst=".$cod_inst."  and cod_dep=".$dependencia."  and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." and cod_secretaria=".$selet."";
    if($this->v_cugd02_verificacion->findCount($sql_re)==0){
		$sql= "DELETE  FROM  cugd02_secretaria  WHERE cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior."  and cod_coordinacion=".$coordinacion." and cod_secretaria=".$selet."";
		$this->cugd02_secretaria->execute($sql);
		$denominacion='';
    }else{
		$this->set('errorMessage', "NO PUEDE ELIMINAR ESTE REGISTRO.....ESTA EN USO");
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


$selet=null;

if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."  and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion."  and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$selet."";
		if($this->cugd02_secretaria->findCount($sql_selet) != 0){
 			$data = $this->cugd02_secretaria->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_secretaria->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_secretaria_2', $selet); $selet=$data_aux_pre['cugd02_secretaria']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_secretaria', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_secretaria','otros');
}else{

			if($coordinacion!=null){
			$sql_secretaria = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$dir_superior." and cod_coordinacion=".$coordinacion."";
			if($this->cugd02_secretaria->findCount($sql_secretaria) != 0){
			$consulta_secretaria = $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$denominacion =  $this->cugd02_secretaria->generateList($sql_secretaria, 'denominacion ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	   		$this->set('secretaria', $consulta_secretaria);
			}
		}

			$this->set('selecion_secretaria','');
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

	 	 $data = $this->cugd02_secretaria->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('data', $data);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));



if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}




}//fin class



?>
