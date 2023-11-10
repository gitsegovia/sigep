<?php

 class Cugp02direccionsuperiorController extends AppController{

	var $name = 'Cugp02direccionsuperior';
	var $uses = array('cugd02_institucion', 'cugd02_dependencia', 'cugd02_direccionsuperior', 'v_cugd02_verificacion');
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

	if($modulo=='0'){
		return;
	}else{
 		echo "<h3>LO SIENTO - SOLO LA ADMINISTRACION CENTRAL TIENE ACCESO A ESTE PROGRAMA!!</h3>";

		exit;
	}

}






function index($institucion=null, $dependencia=null, $selet=null, $boton=null){//echo 'si';

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
		$dependencia = $cod_dep;
	}

if($institucion!=null){
			//echo '1';
			$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst').""." and cod_institucion=".$this->Session->read('SScodinst')."";
			$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
        	$this->set('selecion_institucion', $institucion);
        	//$opcion = 'si';

}else{
			//echo '2';
			$this->set('a',$cod_inst);
	        $sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst').""." and cod_institucion=".$this->Session->read('SScodinst')."";
        	$this->set('selecion_institucion', '');
           	if($this->cugd02_institucion->findCount($sql_re) != 0){
        	$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
	   		}else{$this->set('institucion', 'vacio');}
			//$opcion = 'si';

}//fin else


if($dependencia!=null){//echo 'a';
			$sql_dependencia="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." ";
			if($cod_dep != 1){$sql_dependencia=  "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}
			$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_dependencia, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
        	$this->set('selecion_dependencia', $dependencia);
        	//$opcion = 'si';
}else{
	if($institucion==null){//echo 'b';
			$this->set('selecion_dependencia', '');
			//$this->set('dependencia', '');

			$sql_dependencia="cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." ";

        	if($cod_dep != 1){$sql_dependencia=  "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}
  //      	$this->set('selecion_dependencia', '');
  //echo $sql_dependencia;

    //       	if($this->cugd02_dependencia->findCount($sql_dependencia) != 0){
        	$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_dependencia, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);

			$this->set('b',$cod_dep);
			$sql_dir_superior = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep."";
			$denominacion =  $this->cugd02_direccionsuperior->generateList($sql_dir_superior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
			$this->set('denominacion',$denominacion);
			$this->set('selecion_institucion', $cod_inst);
			$this->set('selecion_dependencia', $cod_dep);

			//$opcion = 'si';
	}else{//echo 'c';
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


if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion."  and cod_dependencia=".$dependencia." and cod_dir_superior=".$selet."";
		if($this->cugd02_direccionsuperior->findCount($sql_selet) != 0){
 			$data = $this->cugd02_direccionsuperior->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_direccionsuperior->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_dir_superior_2', $selet); $selet=$data_aux_pre['cugd02_direccionsuperior']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_dir_superior', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_dir_superior','otros');
			$nume = $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."",null,'cod_dir_superior DESC');
			if($nume == null){//echo 'hello';
				$numero = 1;
			}else{
				$numero = $nume[0]['cugd02_direccionsuperior']['cod_dir_superior'] + 1;
			}
			$this->set('numero',$numero);
			$depx = $this->cugd02_dependencia->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."");
			$fr = $depx[0]['cugd02_dependencia']['funcionario_responsable'];
			$ug = $depx[0]['cugd02_dependencia']['direccion'];
			$ca = $depx[0]['cugd02_dependencia']['cod_area'];
			$tf = $depx[0]['cugd02_dependencia']['telefonos'];
			$fa = $depx[0]['cugd02_dependencia']['fax'];
			$em = $depx[0]['cugd02_dependencia']['email'];
			$this->set('fr',$fr);
			$this->set('ug',$ug);
			$this->set('ca',$ca);
			$this->set('tf',$tf);
			$this->set('fa',$fa);
			$this->set('em',$em);
			$this->set('nuevo','si');

}else{

			if($dependencia!=null){
			$sql_dir_superior = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."";
			if($this->cugd02_direccionsuperior->findCount($sql_dir_superior) != 0){
			$consulta_dir_superior = $this->cugd02_direccionsuperior->generateList($sql_dir_superior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$denominacion =  $this->cugd02_direccionsuperior->generateList($sql_dir_superior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$this->set('dir_superior', $consulta_dir_superior);

			}
		}

			$this->set('selecion_dir_superior','');
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




















function grabar($institucion=null, $dependencia=null, $selet=null, $boton=null){


	$this->layout = "ajax";

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');


	$opcion = 'no';
	$data = '';
	$denominacion='';

	 if($institucion==null){$institucion=$this->data['cugp02direccionsuperior']['cod_institucion'];}
	 if($dependencia==null){$dependencia=$this->data['cugp02direccionsuperior']['cod_dependencia'];}
	 if($selet==null){$selet=$this->data['cugp02direccionsuperior']['cod_dir_superior'];}


$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')."and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$selet."";
if($this->cugd02_direccionsuperior->findCount($sql_re) == 0){
$sql = "INSERT INTO cugd02_direccionsuperior (cod_tipo_institucion, cod_institucion, cod_dependencia, cod_dir_superior, denominacion, funcionario_responsable, direccion, cod_area, telefonos, fax, email)   VALUES  ( '".$this->Session->read('SScodtipoinst')."',  '".$this->data['cugp02direccionsuperior']['cod_institucion']."',  '".$this->data['cugp02direccionsuperior']['cod_dependencia']."',  '".$this->data['cugp02direccionsuperior']['cod_dir_superior']."', '".$this->data['cugp02direccionsuperior']['denominacion']."', '".$this->data['cugp02direccionsuperior']['funcionario_responsable']."', '".$this->data['cugp02direccionsuperior']['direccion']."', '".$this->data['cugp02direccionsuperior']['cod_area']."', '".$this->data['cugp02direccionsuperior']['telefonos']."', '".$this->data['cugp02direccionsuperior']['fax']."', '".$this->data['cugp02direccionsuperior']['email']."')";

$this->cugd02_direccionsuperior->execute($sql);
}else{
$sql ="UPDATE cugd02_direccionsuperior SET  denominacion = '".$this->data['cugp02direccionsuperior']['denominacion']."', funcionario_responsable = '".$this->data['cugp02direccionsuperior']['funcionario_responsable']."', direccion = '".$this->data['cugp02direccionsuperior']['direccion']."', cod_area = '".$this->data['cugp02direccionsuperior']['cod_area']."', telefonos = '".$this->data['cugp02direccionsuperior']['telefonos']."', fax = '".$this->data['cugp02direccionsuperior']['fax']."', email = '".$this->data['cugp02direccionsuperior']['email']."'   where cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->data['cugp02direccionsuperior']['cod_institucion']." and cod_dependencia=".$this->data['cugp02direccionsuperior']['cod_dependencia']."  and cod_dir_superior=".$this->data['cugp02direccionsuperior']['cod_dir_superior']." ";
$this->cugd02_direccionsuperior->execute($sql);

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


if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion."  and cod_dependencia=".$dependencia." and cod_dir_superior=".$selet."";
		if($this->cugd02_direccionsuperior->findCount($sql_selet) != 0){
 			$data = $this->cugd02_direccionsuperior->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_direccionsuperior->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_dir_superior_2', $selet); $selet=$data_aux_pre['cugd02_direccionsuperior']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_dir_superior', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_dir_superior','otros');
}else{

			if($dependencia!=null){
			$sql_dir_superior = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."";
			if($this->cugd02_direccionsuperior->findCount($sql_dir_superior) != 0){
			$consulta_dir_superior = $this->cugd02_direccionsuperior->generateList($sql_dir_superior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$denominacion =  $this->cugd02_direccionsuperior->generateList($sql_dir_superior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$this->set('dir_superior', $consulta_dir_superior);
			}
		}

			$this->set('selecion_dir_superior','');
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

































function eliminar($institucion=null, $dependencia=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');


	$sql_re = "cod_presi=".$cod_presi."  and cod_entidad=".$cod_entidad."  and cod_tipo_inst=".$cod_tipo_inst."  and cod_inst=".$cod_inst."  and cod_dep=".$dependencia."  and cod_dir_superior=".$selet."";
    if($this->v_cugd02_verificacion->findCount($sql_re)==0){
		$sql= "DELETE  FROM  cugd02_direccionsuperior  WHERE cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$selet."  ";
		$this->cugd02_direccionsuperior->execute($sql);
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


$selet=null;


if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion."  and cod_dependencia=".$dependencia." and cod_dir_superior=".$selet."";
		if($this->cugd02_direccionsuperior->findCount($sql_selet) != 0){
 			$data = $this->cugd02_direccionsuperior->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_direccionsuperior->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_dir_superior_2', $selet); $selet=$data_aux_pre['cugd02_direccionsuperior']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_dir_superior', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_dir_superior','otros');
}else{

			if($dependencia!=null){
			$sql_dir_superior = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$institucion." and cod_dependencia=".$dependencia."";
			if($this->cugd02_direccionsuperior->findCount($sql_dir_superior) != 0){
			$consulta_dir_superior = $this->cugd02_direccionsuperior->generateList($sql_dir_superior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$denominacion =  $this->cugd02_direccionsuperior->generateList($sql_dir_superior, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	   		$this->set('dir_superior', $consulta_dir_superior);
			}
		}

			$this->set('selecion_dir_superior','');
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

	 	 $data = $this->cugd02_direccionsuperior->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('data', $data);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));



if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}




}//fin class



?>
