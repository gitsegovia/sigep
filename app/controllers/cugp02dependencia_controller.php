<?php

 class Cugp02dependenciaController extends AppController{

	var $name = 'Cugp02dependencia';
	var $uses = array('cugd02_institucion', 'cugd02_dependencia', 'arrd05', 'cugd10_imagenes', 'v_cugd02_verificacion');
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

if ($cod_dep==1){
$cugd02 = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";
$arrd05 = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')."";
}else{
	$cugd02 = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')." and cod_dependencia=".$this->Session->read('SScoddep')."";
	$arrd05 = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."";
}
$this->Session->write('cugd02_a',$cugd02);
$this->Session->write('arrd05_a',$arrd05);

	if($modulo=='0'){
		return;
	}else{
 		echo "<h3>LO SIENTO - SOLO LA ADMINISTRACION CENTRAL TIENE ACCESO A ESTE PROGRAMA!!</h3>";

		exit;
	}

}



function index($institucion=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';
	$denominacion='';
	$dat ="";
	$cugd02 =  $this->Session->read('cugd02_a');
	$arrd05 =  $this->Session->read('arrd05_a');

//echo "<script>alert('Index ".$cugd02."')</script>";

if($institucion!=null){
			$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";
			$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
        	$this->set('selecion_institucion', $institucion);


}else{
		    $sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";
        	$this->set('selecion_institucion', '');
           	if($this->cugd02_institucion->findCount($sql_re) != 0){
        	$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
	   		}else{$this->set('institucion', 'vacio');}

}//fin else



if($selet!=null && $selet!='otros'){
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."  and cod_dependencia=".$selet."";
		if($this->cugd02_dependencia->findCount($sql_selet) != 0){
 			$data = $this->cugd02_dependencia->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_dependencia->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_dependencia_2', $selet);}
		}else{ $opcion = 'si'; }
		    $sql_re = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$selet."";
			$sql_re_2 = $arrd05;

			if($this->arrd05->findCount($sql_re) != 0){
			$dat = $this->arrd05->findAll($sql_re, null, null, null);
			$denominacion =  $this->arrd05->generateList($sql_re_2, 'denominacion ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
	   		}
			$this->set('selecion_dependencia', $selet);


}else if($selet=='otros'){ ;
			$opcion = 'si';
			$this->set('selecion_dependencia','otros');
}else{

			if($institucion!=null){
			$sql_institucion = $cugd02;
			if($this->cugd02_dependencia->findCount($sql_institucion) != 0){
			$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_institucion, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$denominacion =  $this->cugd02_dependencia->generateList($sql_institucion, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
			}

		    $sql_re = $arrd05;
			if($this->arrd05->findCount($sql_re) != 0){
			$denominacion =  $this->arrd05->generateList($sql_re, 'denominacion ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
	   		}

		}

			$this->set('selecion_dependencia','');
			$opcion = 'si';
}






if($selet!=null){

 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep');
 $var_img = $selet;

	        $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=3 and identificacion='".$var_img."'  ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe1',true);
		    }else{
		    	$this->set('aqui_imagen_existe1',false);
		    }

		    $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=4 and identificacion='".$var_img."' ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe2',true);
		    }else{
		    	$this->set('aqui_imagen_existe2',false);
		    }


			$this->set('aqui_imagen',  $var_img);
			$this->set('aqui_imagen2', $var_img);

}else{
	        $opcion = 'no';
	        $this->set('aqui_imagen_existe1',false);
	        $this->set('aqui_imagen_existe2',false);

}//fin else








	$this->set('denominacion', $denominacion);
	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', $boton);
	$this->set('deno_dep', $dat);
}




















function grabar($institucion=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';
	$denominacion='';
	$dat ="";
	$cugd02 =  $this->Session->read('cugd02_a');
	$arrd05 =  $this->Session->read('arrd05_a');

	 if($institucion==null){$institucion=$this->Session->read('SScodinst');}
	 if($selet==null){$selet=$this->data['cugp02dependencia']['cod_dependencia'];}


	$condicion = $arrd05;
    $var_img = $selet;

	        $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=3 and identificacion='".$var_img."'  ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe1',true);
		    }else{
		    	$this->set('aqui_imagen_existe1',false);
		    }

		    $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=4 and identificacion='".$var_img."' ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe2',true);
		    }else{
		    	$this->set('aqui_imagen_existe2',false);
		    }


			$this->set('aqui_imagen',  $var_img);
			$this->set('aqui_imagen2', $var_img);








//$this->data['cugp02dependencia']['fecha'] = $this->data['cugp02dependencia']['year'].'-'.$this->data['cugp02dependencia']['mes'].'-'.$this->data['cugp02dependencia']['dia'];


$sql_re = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$selet."";
if($this->arrd05->findCount($sql_re) != 0){$dataa = $this->arrd05->findAll($sql_re, null, null, null);}
foreach($dataa as $aux){}
$this->data['cugp02dependencia']['denominacion']=$aux['arrd05']['denominacion'];


$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')."and cod_institucion=".$this->Session->read('SScodinst')." and cod_dependencia=".$selet."";
if($this->cugd02_dependencia->findCount($sql_re) == 0){
$sql = "INSERT INTO cugd02_dependencias (cod_tipo_institucion, cod_institucion, cod_dependencia, denominacion, funcionario_responsable, direccion, cod_area, telefonos, fax, email, rif, nit, agente_retencion, fiscal_rentas, decreto_gaceta, fecha, actividad, tipo_dependencia,  cargo)   VALUES  ( '".$this->Session->read('SScodtipoinst')."',  '".$this->Session->read('SScodinst')."',  '".$this->data['cugp02dependencia']['cod_dependencia']."', '".$this->data['cugp02dependencia']['denominacion']."', '".$this->data['cugp02dependencia']['funcionario_responsable']."', '".$this->data['cugp02dependencia']['direccion']."', '".$this->data['cugp02dependencia']['cod_area']."', '".$this->data['cugp02dependencia']['telefonos']."', '".$this->data['cugp02dependencia']['fax']."', '".$this->data['cugp02dependencia']['email']."', '".$this->data['cugp02dependencia']['rif']."', '".$this->data['cugp02dependencia']['nit']."', '".$this->data['cugp02dependencia']['agente_retencion']."', '".$this->data['cugp02dependencia']['fiscal_rentas']."', '".$this->data['cugp02dependencia']['decreto_gaceta']."', '".$this->data['cugp02dependencia']['fecha']."', '".$this->data['cugp02dependencia']['actividad']."', '".$this->data['cugp02dependencia']['tipo_dependencia']."', '".$this->data['cugp02dependencia']['cargo_funcionario_responsable']."')";
$this->cugd02_dependencia->execute($sql);
}else{
$sql ="UPDATE cugd02_dependencias SET  denominacion = '".$this->data['cugp02dependencia']['denominacion']."', funcionario_responsable = '".$this->data['cugp02dependencia']['funcionario_responsable']."', direccion = '".$this->data['cugp02dependencia']['direccion']."', cod_area = '".$this->data['cugp02dependencia']['cod_area']."', actividad = '".$this->data['cugp02dependencia']['actividad']."', telefonos = '".$this->data['cugp02dependencia']['telefonos']."', fax = '".$this->data['cugp02dependencia']['fax']."', email = '".$this->data['cugp02dependencia']['email']."', rif = '".$this->data['cugp02dependencia']['rif']."', nit = '".$this->data['cugp02dependencia']['nit']."', agente_retencion = '".$this->data['cugp02dependencia']['agente_retencion']."', fiscal_rentas = '".$this->data['cugp02dependencia']['fiscal_rentas']."', decreto_gaceta = '".$this->data['cugp02dependencia']['decreto_gaceta']."', fecha = '".$this->data['cugp02dependencia']['fecha']."', cargo='".$this->data['cugp02dependencia']['cargo_funcionario_responsable']."'   where cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')." and cod_dependencia=".$this->data['cugp02dependencia']['cod_dependencia']." ";
$this->cugd02_dependencia->execute($sql);

}




if($institucion!=null){
			$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";
			$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
        	$this->set('selecion_institucion', $this->Session->read('SScodinst'));
        	//$opcion = 'si';

}else{
	        $sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";
        	$this->set('selecion_institucion', '');
           	if($this->cugd02_institucion->findCount($sql_re) != 0){
        	$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
	   		}else{$this->set('institucion', 'vacio');}
			//$opcion = 'si';

}//fin else



if($selet!=null && $selet!='otros'){
		  	$sql_re = $cugd02;
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."  and cod_dependencia=".$selet."";
		if($this->cugd02_dependencia->findCount($sql_selet) != 0){
 			$data = $this->cugd02_dependencia->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_dependencia->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_dependencia_2', $selet);}
		}else{ $opcion = 'si'; }
		    $sql_re = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$selet."";
			$sql_re_2 = $arrd05;

			if($this->arrd05->findCount($sql_re) != 0){
			$dat = $this->arrd05->findAll($sql_re, null, null, null);
			$denominacion =  $this->arrd05->generateList($sql_re_2, 'denominacion ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
	   		}
			$this->set('selecion_dependencia', $selet);


}else if($selet=='otros'){ ;
			$opcion = 'si';
			$this->set('selecion_dependencia','otros');
}else{

			if($institucion!=null){
			$sql_institucion = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";
			if($this->cugd02_dependencia->findCount($sql_institucion) != 0){
			$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_institucion, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$denominacion =  $this->cugd02_dependencia->generateList($sql_institucion, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
			}

		    $sql_re = $arrd05;
			if($this->arrd05->findCount($sql_re) != 0){
			$denominacion =  $this->arrd05->generateList($sql_re, 'denominacion ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
	   		}

		}

			$this->set('selecion_dependencia','');
			$opcion = 'si';
}






	$this->set('data', $data);
	$this->set('denominacion', $denominacion);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', $boton);
	$this->set('deno_dep', $dat);




}

































function eliminar($institucion=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$opcion = 'no';
	$data = '';
	$dat ="";
    $cugd02 =  $this->Session->read('cugd02_a');
	$arrd05 =  $this->Session->read('arrd05_a');


	$sql_re = "cod_presi=".$cod_presi."  and cod_entidad=".$cod_entidad."  and cod_tipo_inst=".$cod_tipo_inst."  and cod_inst=".$cod_inst."  and cod_dep=".$selet."";
    if($this->v_cugd02_verificacion->findCount($sql_re)==0){
		$sql= "DELETE  FROM  cugd02_dependencias  WHERE cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')." and cod_dependencia=".$selet." ";
		$this->cugd02_dependencia->execute($sql);
		$denominacion='';
    }else{
		$this->set('errorMessage', "NO PUEDE ELIMINAR ESTE REGISTRO.....ESTA EN USO");
    }



  $this->requestAction('/cugp10_imagenes/eliminar/'.$selet.'/3');
  $this->requestAction('/cugp10_imagenes/eliminar/'.$selet.'/4');



if($institucion!=null){
			$sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";
			$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
        	$this->set('selecion_institucion', $this->Session->read('SScodinst'));
        	//$opcion = 'si';

}else{
	        $sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";
        	$this->set('selecion_institucion', '');
           	if($this->cugd02_institucion->findCount($sql_re) != 0){
        	$consulta_institucion = $this->cugd02_institucion->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	   		$this->set('institucion', $consulta_institucion);
	   		}else{$this->set('institucion', 'vacio');}
			//$opcion = 'si';

}//fin else

$selet=null;

if($selet!=null && $selet!='otros'){
		  	$sql_re = $cugd02;
		  	$sql_selet = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."  and cod_dependencia=".$selet."";
		if($this->cugd02_dependencia->findCount($sql_selet) != 0){
 			$data = $this->cugd02_dependencia->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd02_dependencia->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_dependencia_2', $selet);}
		}else{ $opcion = 'si'; }
		    $sql_re = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$selet."";
			$sql_re_2 = $arrd05;

			if($this->arrd05->findCount($sql_re) != 0){
			$dat = $this->arrd05->findAll($sql_re, null, null, null);
			$denominacion =  $this->arrd05->generateList($sql_re_2, 'denominacion ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
	   		}
			$this->set('selecion_dependencia', $selet);


}else if($selet=='otros'){ ;
			$opcion = 'si';
			$this->set('selecion_dependencia','otros');
}else{

			if($institucion!=null){
			$sql_institucion = $cugd02;
			if($this->cugd02_dependencia->findCount($sql_institucion) != 0){
			$consulta_dependencia = $this->cugd02_dependencia->generateList($sql_institucion, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$denominacion =  $this->cugd02_dependencia->generateList($sql_institucion, 'denominacion ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	   		$this->set('dependencia', $consulta_dependencia);
			}

		    $sql_re = $arrd05;
			if($this->arrd05->findCount($sql_re) != 0){
			$denominacion =  $this->arrd05->generateList($sql_re, 'denominacion ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
	   		}

		}

			$this->set('selecion_dependencia','');
			$opcion = 'si';
}





                $this->set('aqui_imagen_existe1',false);
		        $this->set('aqui_imagen_existe2',false);
		        $opcion = 'no';



	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('denominacion', $denominacion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', null);
	$this->set('deno_dep', $dat);
}


function consulta($pag_num=null) {


	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cugd02 =  $this->Session->read('cugd02_a');
	$arrd05 =  $this->Session->read('arrd05_a');

 		$this->layout = "ajax";
 		$dat='';

$sql_re = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')."";
if($this->arrd05->findCount($sql_re) != 0){$dat = $this->arrd05->findAll($sql_re, null, null, null);}


		 $sql_re = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')."";

	 	 $institucion = $this->cugd02_institucion->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('var_institucion',$institucion);

	 	if($cod_dep != 1){$sql_re .=  " and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;}

	 	 $data = $this->cugd02_dependencia->findAll($sql_re, null, 'denominacion ASC', null);
	 	 $this->set('data', $data);

	 	 $i = 0;

		foreach($data as $datos){
		    $var[$i]['cod_institucion']  = $datos['cugd02_dependencia']['cod_institucion'];
		    $var[$i]['cod_dependencia']  = $datos['cugd02_dependencia']['cod_dependencia'];
		    $var[$i]['denominacion']  = $datos['cugd02_dependencia']['denominacion'];
			$var[$i]['funcionario_responsable']  = $datos['cugd02_dependencia']['funcionario_responsable'];
			$var[$i]['direccion']  = $datos['cugd02_dependencia']['direccion'];
			$var[$i]['cod_area']  = $datos['cugd02_dependencia']['cod_area'];
			$var[$i]['telefonos']  = $datos['cugd02_dependencia']['telefonos'];
			$var[$i]['fax']  = $datos['cugd02_dependencia']['fax'];
			$var[$i]['email']  = $datos['cugd02_dependencia']['email'];
			$var[$i]['rif']  = $datos['cugd02_dependencia']['rif'];
			$var[$i]['nit']  = $datos['cugd02_dependencia']['nit'];
			$var[$i]['agente_retencion']  = $datos['cugd02_dependencia']['agente_retencion'];
			$var[$i]['fiscal_rentas']  = $datos['cugd02_dependencia']['fiscal_rentas'];
			$var[$i]['decreto_gaceta']  = $datos['cugd02_dependencia']['decreto_gaceta'];
			$var[$i]['fecha']  = $datos['cugd02_dependencia']['fecha'];
			$var[$i]['actividad']  = $datos['cugd02_dependencia']['actividad'];
			$var[$i]['tipo_dependencia']  = $datos['cugd02_dependencia']['tipo_dependencia'];

			$i++;
			}

    if(isset($pag_num)){$pageNum_Recordset1 = $pag_num; }else{$pageNum_Recordset1 = 0;}
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep');
    $var_img = $var[$pageNum_Recordset1]['cod_dependencia'];

	        $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=3 and identificacion='".$var_img."'  ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe1',true);
		    }else{
		    	$this->set('aqui_imagen_existe1',false);
		    }

		    $vec=$this->cugd10_imagenes->findCount($condicion." and cod_campo=4 and identificacion='".$var_img."' ");
		    if($vec!=0){
		    	$this->set('aqui_imagen_existe2',true);
		    }else{
		    	$this->set('aqui_imagen_existe2',false);
		    }


			$this->set('aqui_imagen',  $var_img);
			$this->set('aqui_imagen2', $var_img);





		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_tipo_institucion', $this->Session->read('SScodtipoinst'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));
		 $this->set('deno_dep', $dat);



if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}






function subir_imagen ($var=null){
	//$this->layout="";
	if(isset($var)){

	 		if($this->data['cugp02dependencia']['foto']['size']<2004654){
		 		if($this->data['cugp02dependencia']['foto']['type']=="image/jpeg"){
				    $fileDataMini = fread(fopen($this->redimensionar($this->data['cugp02dependencia']['foto']['tmp_name'],110,0),"r"), $this->data['cugp02dependencia']['foto']['size']);
			       // $this->data['cugp02_dependencia']['foto']['cedula_identidad']=$this->data['cugp02_dependencia']['cedula'];
				    $this->data['cugp02dependencia']['foto']['foto'] = base64_encode($fileDataMini);
				    $this->cugd02_dependencia->save($this->data['cugp02dependencia']['foto']);
			        echo "La imagen se a cargado exitosamente...";
				    echo '<script type="text/javascript">' .
				    		'window.close();' .
				    		'</script>';
		 		}else{
		 			echo "Por Favor Cargue una imagen que sea de tipo JPG";
		 			echo "<br>Tipo Imagen:".$this->data['cugp02dependencias']['foto']['type'];
		 			echo "<br>Tamaño: ".$this->data['cugp02dependencia']['foto']['size'];
		 		}
	 		}else{
	 			echo "Disculpe, El archivo excede del tamaño permitido.";
	 		}

 }
}//finn subir_imagen

function busca_foto ($id) {
    $this->layout="ajax";
    $vec=$this->cugd02_dependencia->findCount("cod_dependencia=".$id);
    if($vec!=0){
    	$this->set('id',$id);
    }

}
function ver_foto ($id) {
    $this->layout="img";
    $vec=$this->cugd02_dependencia->findByCod_dependencia($id);
    $dataimg=$vec["cugd02_dependencia"]["logo"];
    $this->set('img',$dataimg);
    //$this->render();
}
 function viewid ($id) {
    $this->layout="img";
    $vec=$this->cugd02_dependencia->findByCod_dependencia($id);
    $dataimg=$vec["cugd02_dependencia"]["logo"];
    $this->set('img',$dataimg);
}
/**
 * Funcion para redimensionar las imagenes.
 *
 */
function redimensionar($imagen,$largo,$mostrar = 1)
{
 	// $imagen	Ruta de la Imagen a Redimensioanr
 	// $largo	Largo de la Redimension
	// $mostrar	1 Muestra la Imagen en el Nevegador
	// $mostrar	0 Guarda la Imagen

	// Si $mostrar es 0
	// Funcion devuelve ruta de la Imagen


	$anchura=$largo;
	// Altura Maxima de la Imagen
	$hmax=400;
	$nombre=$imagen;
	$datos = getimagesize($nombre);



	if($datos[2]==1){
	 	$img = @imagecreatefromgif($nombre);
	}

	if($datos[2]==2){
	 	$img = @imagecreatefromjpeg($nombre);
	}

	if($datos[2]==3){
	 	$img = @imagecreatefrompng($nombre);
	}

	$ratio = ($datos[0] / $anchura);

	$altura = ($datos[1] / $ratio);

	if($altura>$hmax){
	 	$anchura2=$hmax*$anchura/$altura;
		 $altura=$hmax;
		 $anchura=$anchura2;
	}

	$thumb = imagecreatetruecolor($anchura,$altura);

	imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);


	// Creamos la Imagen (un JPG)
	if ( $mostrar == 0 ){
	 	// Guardamos Imagen en Directorio
	  	imagejpeg($thumb,$imagen.'_copy',100);
	  	imagedestroy($thumb);
	  	return ($imagen.'_copy');//devuelve ruta de la imagen creada

	}else{
	 	// Mostramos Imagen en el Navegador
		//header("Content-type: image/jpeg");
		return imagejpeg($thumb,'',100);

	}
	imagedestroy($thumb);
}//fin funcion redimensionar





}//fin class



?>
