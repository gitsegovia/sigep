<?php

 class Cugp02institucionController extends AppController{

	var $name = 'Cugp02institucion';
	var $uses = array('cugd02_institucion', 'cugd10_imagenes', 'v_cugd02_verificacion');
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






function index($selet=null, $boton=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';
	$denominacion='';



if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$selet."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$selet."";
		if($this->cugd02_institucion->findCount($sql_selet) != 0){
 			$data = $this->cugd02_institucion->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_institucion_2', $selet); $selet=$data_aux_pre['cugd02_institucion']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_institucion', $selet);

}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_institucion','otros');
}else{

			$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";

           	if($this->cugd02_institucion->findCount($sql_re) != 0){
        	$denominacion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$data = $this->cugd02_institucion->findAll($sql_re, null, null, null);
	   		$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
	   		$this->set('selecion_institucion_2', $data_aux_pre['cugd02_institucion']['cod_institucion']);
	   		$boton='ya';
	   		$opcion = 'no';
	   		$this->set('selecion_institucion','');
	   		}else{$opcion = 'si';
	   			$this->set('selecion_institucion','otros');
	              }
}









 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep');
 $var_img = $this->Session->read('SScodinst');

	        $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=1 and identificacion='".$var_img."'  ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe1',true);
		    }else{
		    	$this->set('aqui_imagen_existe1',false);
		    }

		    $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=2 and identificacion='".$var_img."' ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe2',true);
		    }else{
		    	$this->set('aqui_imagen_existe2',false);
		    }


			$this->set('aqui_imagen',  $var_img);
			$this->set('aqui_imagen2', $var_img);










	$this->set('denominacion', $denominacion);
	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('cod_inst',$this->Session->read('SScodinst'));
	$this->set('boton', $boton);
}




















function grabar($selet=null, $boton=null){

	$this->layout = "ajax";

	$opcion = 'no';
	$data = '';
	$denominacion='';

	 if($selet==null){$selet=$this->Session->read('SScodinst');}






	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep');
    $var_img = $selet;

	        $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=1 and identificacion='".$var_img."'  ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe1',true);
		    }else{
		    	$this->set('aqui_imagen_existe1',false);
		    }

		    $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=2 and identificacion='".$var_img."' ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe2',true);
		    }else{
		    	$this->set('aqui_imagen_existe2',false);
		    }


			$this->set('aqui_imagen',  $var_img);
			$this->set('aqui_imagen2', $var_img);


//	 $this->data['cugp02institucion']['fecha'] = $this->data['cugp02institucion']['year'].'-'.$this->data['cugp02institucion']['mes'].'-'.$this->data['cugp02institucion']['dia'];


$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')."and cod_institucion=".$this->Session->read('SScodinst')."";
if($this->cugd02_institucion->findCount($sql_re) == 0){
$sql = "INSERT INTO cugd02_institucion (cod_tipo_institucion, cod_institucion, denominacion, funcionario_responsable, direccion, cod_area, telefonos, fax, email, rif, nit, agente_retencion, fiscal_rentas, decreto_gaceta, fecha )   VALUES  ( '".$this->Session->read('SScodtipoinst')."',  '".$this->Session->read('SScodinst')."', '".$this->data['cugp02institucion']['denominacion']."', '".$this->data['cugp02institucion']['funcionario_responsable']."', '".$this->data['cugp02institucion']['direccion']."', '".$this->data['cugp02institucion']['cod_area']."', '".$this->data['cugp02institucion']['telefonos']."', '".$this->data['cugp02institucion']['fax']."', '".$this->data['cugp02institucion']['email']."', '".$this->data['cugp02institucion']['rif']."', '".$this->data['cugp02institucion']['nit']."', '".$this->data['cugp02institucion']['agente_retencion']."', '".$this->data['cugp02institucion']['fiscal_rentas']."', '".$this->data['cugp02institucion']['decreto_gaceta']."', '".$this->data['cugp02institucion']['fecha']."')";
$this->cugd02_institucion->execute($sql);
}else{
$sql ="UPDATE cugd02_institucion SET  denominacion = '".$this->data['cugp02institucion']['denominacion']."', funcionario_responsable = '".$this->data['cugp02institucion']['funcionario_responsable']."', direccion = '".$this->data['cugp02institucion']['direccion']."', cod_area = '".$this->data['cugp02institucion']['cod_area']."', telefonos = '".$this->data['cugp02institucion']['telefonos']."', fax = '".$this->data['cugp02institucion']['fax']."', email = '".$this->data['cugp02institucion']['email']."', rif = '".$this->data['cugp02institucion']['rif']."', nit = '".$this->data['cugp02institucion']['nit']."', agente_retencion = '".$this->data['cugp02institucion']['agente_retencion']."', fiscal_rentas = '".$this->data['cugp02institucion']['fiscal_rentas']."', decreto_gaceta = '".$this->data['cugp02institucion']['decreto_gaceta']."', fecha = '".$this->data['cugp02institucion']['fecha']."'   where cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";
$this->cugd02_institucion->execute($sql);

}

$this->Session->write('entidad_federal', $this->data['cugp02institucion']['denominacion']);





if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$selet."";
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$selet."";
		if($this->cugd02_institucion->findCount($sql_selet) != 0){
 			$data = $this->cugd02_institucion->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_institucion_2', $selet); $selet=$data_aux_pre['cugd02_institucion']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_institucion', $selet);

}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_institucion','otros');
}else{
	$this->set('selecion_institucion','');
	$opcion = 'si';
}






	$this->set('data', $data);
	$this->set('denominacion', $denominacion);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', $boton);
	$this->set('cod_inst',$this->Session->read('SScodinst'));




}

































function eliminar($selet=null, $boton=null){

	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$opcion = 'no';
	$data = '';

	$sql_re = "cod_presi=".$cod_presi."  and cod_entidad=".$cod_entidad."  and cod_tipo_inst=".$cod_tipo_inst."  and cod_inst=".$this->Session->read('SScodinst')."";
    if($this->v_cugd02_verificacion->findCount($sql_re)==0){
		$sql= "DELETE  FROM  cugd02_institucion  WHERE cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')." ";
		$this->cugd02_institucion->execute($sql);
		$denominacion='';
    }else{
    	echo "<script>alert('NO PUEDE ELIMINAR ESTE REGISTRO.....ESTA EN USO ')</script>";
    }


    $this->requestAction('/cugp10_imagenes/eliminar/'.$selet.'/1');
    $this->requestAction('/cugp10_imagenes/eliminar/'.$selet.'/2');

    $this->index();
    $this->render("index");



}//fin function



















function consulta($pag_num=null) {

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

 		$this->layout = "ajax";
		$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$cod_inst."";

		  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep');
          $var_img = $this->Session->read('SScodinst');
		  $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=1 and identificacion='".$var_img."'  ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe1',true);
		    }else{
		    	$this->set('aqui_imagen_existe1',false);
		    }

		    $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=2 and identificacion='".$var_img."' ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe2',true);
		    }else{
		    	$this->set('aqui_imagen_existe2',false);
		    }


			$this->set('aqui_imagen',  $var_img);
			$this->set('aqui_imagen2', $var_img);



	 	 $data = $this->cugd02_institucion->findAll($sql_re, null, 'denominacion ASC', null);
	 	$this->set('data',$data);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));
		 $this->set('cod_inst',$this->Session->read('SScodinst'));



if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}




}//fin class



?>
