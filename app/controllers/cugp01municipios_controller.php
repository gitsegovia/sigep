<?php

 class Cugp01municipiosController extends AppController{

	var $name = 'Cugp01municipios';
	var $uses = array('cugd01_republica', 'cugd01_estados', 'cugd01_municipios', 'v_cugd01_verificacion');
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

}



function index2(){
    $this->layout = "ajax";
	$this->Session->delete('cod_presi_geografico');
    $this->index();
    $this->render("index");
}//fin function




function index($estado=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';
	$denominacion='';
	/* if($selet!=null && $selet!='otros'){$this->set('selecion', $selet);
	 }else if($selet=='otros'){$this->set('selecion',$selet); $selet='0';
	 }else{ $selet=$this->Session->read('SScodentidad'); $this->set('selecion',$selet);}*/



if($selet=="republica"){$_SESSION["cod_presi_geografico"] = $boton; $selet =null; $boton = null;}
if(empty($_SESSION["cod_presi_geografico"])){$_SESSION["cod_presi_geografico"] = $this->Session->read('SScodpresi'); }



	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);

if($estado!=null && $estado!="no"){
        $sql_estado = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."";
        $sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
        $this->set('selecion_estado', $estado);

        $estado_var = $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico')."", 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	    $this->set('estado', $estado_var);

 		if($this->cugd01_municipios->findCount($sql_re) != 0){
 			$data = $this->cugd01_municipios->findAll($sql_re, null, null, null);

 			if($boton=='modificar'){
 			$denominacion =  $this->cugd01_municipios->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_municipios.denominacion', '{n}.cugd01_municipios.denominacion');
 			}else{
 			$denominacion =  $this->cugd01_municipios->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
 				}

		}
		if($selet!=null && $selet!='otros'){
		  $sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$selet." ";
			if($this->cugd01_municipios->findCount($sql_re) != 0){
 			$data = $this->cugd01_municipios->findAll($sql_re, null, null, null);
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_municipio_2', $selet); $selet=$data_aux_pre['cugd01_municipios']['denominacion']; }
			}else{ $opcion = 'si'; }
		}else{ $opcion = 'si'; }

		$datos_aux = $this->cugd01_municipios->findAll($sql_estado, null, "cod_municipio desc", null);
        $this->set('cod_municipio', isset($datos_aux[0]["cugd01_municipios"]["cod_municipio"])?$datos_aux[0]["cugd01_municipios"]["cod_municipio"] + 1:1);

		$this->set('selecion_municipio', $selet);


}else{
        	$sql_estado="cod_republica=".$this->Session->read('cod_presi_geografico')."";
        	$this->set('selecion_estado', '');
        	$this->set('selecion_municipio', '');

        	if($this->cugd01_estados->findCount($sql_estado) != 0){
        	$estado = $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico')."", 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	   		$this->set('estado', $estado);}else{$this->set('estado', 'vacio');}
			$opcion = 'si';

}//fin else



    $lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	$this->set('lista', $lista_republicas);

	$this->set('denominacion', $denominacion);
	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', $boton);
}





function grabar($estado=null, $selet=null, $boton=null){

	$this->layout = "ajax";


	$opcion = 'no';
	$data = '';
	$denominacion='';



	 if($estado==null){$estado=$this->data['cugp01municipios']['cod_estado'];}
	 if($selet==null){$selet=$this->data['cugp01municipios']['cod_municipio'];}


$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')."and cod_estado=".$estado." and cod_municipio=".$selet."";
if($this->cugd01_municipios->findCount($sql_re) == 0){
$sql = "INSERT INTO cugd01_municipios (cod_republica, cod_estado, cod_municipio,denominacion, conocido, caracteristicas, economia, poblacion, orientacion, limites, dimension, zona_postal)   VALUES  ( '".$this->data['cugp01municipios']['cod_republica']."',  '".$this->data['cugp01municipios']['cod_estado']."',  '".$this->data['cugp01municipios']['cod_municipio']."',  '".$this->data['cugp01municipios']['denominacion']."',  '".$this->data['cugp01municipios']['conocido']."',  '".$this->data['cugp01municipios']['caracteristicas']."',  '".$this->data['cugp01municipios']['economia']."',  '".$this->data['cugp01municipios']['poblacion']."',  '".$this->data['cugp01municipios']['orientacion']."',  '".$this->data['cugp01municipios']['limites']."',  '".$this->data['cugp01municipios']['dimension']."',  '".$this->data['cugp01municipios']['zona_postal']."')";
$this->cugd01_municipios->execute($sql);
}else{

$sql ="UPDATE cugd01_municipios  SET  denominacion = '".$this->data['cugp01municipios']['denominacion']."', conocido = '".$this->data['cugp01municipios']['conocido']."', caracteristicas = '".$this->data['cugp01municipios']['caracteristicas']."', economia = '".$this->data['cugp01municipios']['economia']."', poblacion= '".$this->data['cugp01municipios']['poblacion']."', orientacion = '".$this->data['cugp01municipios']['orientacion']."',limites = '".$this->data['cugp01municipios']['limites']."',dimension = '".$this->data['cugp01municipios']['dimension']."',zona_postal = '".$this->data['cugp01municipios']['zona_postal']."' where cod_republica=".$this->Session->read('cod_presi_geografico')."and cod_estado=".$estado." and cod_municipio=".$selet."";
$this->cugd01_municipios->execute($sql);

	//$this->cugd01_municipios->save($this->data['cugp01municipios']);

	}
$_SESSION["cod_presi_geografico"]=$this->data['cugp01municipios']['cod_republica'];

	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);



if($estado!=null){
        $sql_estado = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."";
        $sql_es = "cod_republica=".$this->Session->read('cod_presi_geografico');
        $sql_mu = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";

        $this->set('selecion_estado', $estado);

        $estado_var = $this->cugd01_estados->generateList($sql_es, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	    $this->set('estado', $estado_var);

 		if($this->cugd01_municipios->findCount($sql_mu) != 0){
 			//$data = $this->cugd01_municipios->findAll($sql_re, null, null, null);
 			$denominacion =  $this->cugd01_municipios->generateList($sql_mu, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
		}
		if($selet!=null && $selet!='otros'){
		  $sql_mu = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$selet." ";
			if($this->cugd01_municipios->findCount($sql_mu) != 0){
 			$data = $this->cugd01_municipios->findAll($sql_mu, null, null, null);
			}else{ $opcion = 'si'; }
		}else{ $opcion = 'si'; }

		$datos_aux = $this->cugd01_municipios->findAll($sql_estado, null, "cod_municipio desc", null);
        $this->set('cod_municipio', isset($datos_aux[0]["cugd01_municipios"]["cod_municipio"])?$datos_aux[0]["cugd01_municipios"]["cod_municipio"] + 1:1);


		$this->set('selecion_municipio', $selet);



}else{
        	$sql_estado="cod_republica=".$this->Session->read('cod_presi_geografico')."";
        	$this->set('selecion_estado', '');
        	$this->set('selecion_municipio', '');

        	if($this->cugd01_estados->findCount($sql_estado) != 0){
        	$estado = $this->cugd01_estados->generateList(null, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	   		$this->set('estado', $estado);}else{$this->set('estado', 'vacio');}
			$opcion = 'si';

}//fin else




    $lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	$this->set('lista', $lista_republicas);
	$this->set('data', $data);
	$this->set('denominacion', $denominacion);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', $boton);




}



function eliminar($estado=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$opcion = 'no';
	$data = '';

	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);


	$sql_re = "cod_presi=".$cod_presi."  and cod_entidad=".$cod_entidad."  and cod_tipo_inst=".$cod_tipo_inst."  and cod_estado=".$estado." and cod_municipio=".$selet."";
    if($this->v_cugd01_verificacion->findCount($sql_re)==0){
		$sql= "DELETE  FROM  cugd01_municipios  WHERE cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$selet."";
		$this->cugd01_municipios->execute($sql);
		$denominacion='';
		$this->set('Message_existe', "REGISTRO ELIMINADO");
    }else{
    	$this->set('errorMessage', "NO PUEDE ELIMINAR ESTE REGISTRO.....ESTA EN USO");
    }


if($estado!=null){
        $sql_estado = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."";
        $sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
        $this->set('selecion_estado', $estado);

        $estado_var = $this->cugd01_estados->generateList(null, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	    $this->set('estado', $estado_var);

 		if($this->cugd01_municipios->findCount($sql_re) != 0){
 			$data = $this->cugd01_municipios->findAll($sql_re, null, null, null);
 			$denominacion =  $this->cugd01_municipios->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
		}
		if($selet!=null && $selet!='otros'){
		  $sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$selet." ";
			if($this->cugd01_municipios->findCount($sql_re) != 0){
 			$data = $this->cugd01_municipios->findAll($sql_re, null, null, null);
			}else{ $opcion = 'si'; }
		}else{ $opcion = 'si'; }

		$datos_aux = $this->cugd01_municipios->findAll($sql_estado, null, "cod_municipio desc", null);
        $this->set('cod_municipio', isset($datos_aux[0]["cugd01_municipios"]["cod_municipio"])?$datos_aux[0]["cugd01_municipios"]["cod_municipio"] + 1:1);


		$this->set('selecion_municipio', '');



}else{
        	$sql_estado="cod_republica=".$this->Session->read('cod_presi_geografico')."";
        	$this->set('selecion_estado', '');
        	$this->set('selecion_municipio', '');

        	if($this->cugd01_estados->findCount($sql_estado) != 0){
        	$estado = $this->cugd01_estados->generateList(null, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	   		$this->set('estado', $estado);}else{$this->set('estado', 'vacio');}
			$opcion = 'si';

}//fin else


    $lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	$this->set('lista', $lista_republicas);
	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('denominacion', $denominacion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', null);
}



function consulta($pag_num=null) {

 		$this->layout = "ajax";




 		 $republica = $this->cugd01_republica->findAll(null, null, 'cod_republica ASC', null);
	 	 $this->set('var_republica', $republica);

	 	 $estados = $this->cugd01_estados->findAll(null, null, 'denominacion ASC', null);
	 	 $this->set('var_estados', $estados);

		 $data = $this->cugd01_municipios->findAll(null, null, 'cod_republica, cod_estado, cod_municipio ASC', null, null, null);
         $this->set('data',$data);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));


		 $lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	     $this->set('lista', $lista_republicas);

         if($pag_num!=null){
	 	 	     $this->set('pagina_actual', $pag_num);
	 	 }//fin else



}//fin function




}//fin class



?>
