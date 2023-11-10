<?php

 class Cugp01centropobladosController extends AppController{

	var $name = 'Cugp01centropoblados';
	var $uses = array('cugd01_republica', 'cugd01_estados', 'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados','cugd90_municipio_defecto', 'v_cugd01_verificacion');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
	if (!$this->Session->check('Usuario')){
		if (!$this->Session->check('concejo_comunal')){
			$this->redirect('/salir');
			exit();
		}
	}else{
		//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
		//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
		$this->requestAction('/usuarios/actualizar_user');
	}
}//fin checksession

function beforeFilter(){
	$this->checkSession();
	$cod_presi = $this->Session->read('cod_presi_geografico');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
}

function verifica_SS($i){
	switch ($i){
		case 1:return $this->Session->read('SScodpresi');break;
		case 2:return $this->Session->read('SScodentidad');break;
		case 3:return $this->Session->read('SScodtipoinst');break;
		case 4:return $this->Session->read('SScodinst');break;
		case 5:return $this->Session->read('SScoddep');break;
		case 6:return $this->Session->read('entidad_federal');break;
		default:
		return "NULO";
	}//fin switch
}//fin verifica_SS

function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
		 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
		 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
		 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
		 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
		 return $sql_re;
}//fin funcion SQLCA_s

function index2(){
    $this->layout = "ajax";
	$this->Session->delete('cod_presi_geografico');
    $this->index();
    $this->render("index");
}//fin function

function index($estado=null, $municipio=null, $parroquia=null, $selet=null, $boton=null){
	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';
	$denominacion='';
	/* if($selet!=null && $selet!='otros'){$this->set('selecion', $selet);
	 }else if($selet=='otros'){$this->set('selecion',$selet); $selet='0';
	 }else{ $selet=$this->Session->read('SScodentidad'); $this->set('selecion',$selet);}*/

	if($selet=="republica"){$_SESSION["cod_presi_geografico"] = $boton; $selet =null; $boton = null;}
	if(empty($_SESSION["cod_presi_geografico"])){$_SESSION["cod_presi_geografico"] = $this->Session->read('SScodpresi'); }


	$lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
	$this->set('lista', $lista_republicas);

	$republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	$this->set('var', $republica);


	if($estado==null && $municipio==null && $selet==null && $boton==null){
			$opcion = 'si';

				$can_mun_def=$this->cugd90_municipio_defecto->findCount($this->SQLCA_S());
				if($can_mun_def!=0){
					$mun_defecto=$this->cugd90_municipio_defecto->findAll($this->SQLCA_S());
				    $cod_republica=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
				    $cod_estado=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_estado"];
				    $cod_municipio=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_municipio"];

				    $lista_r=  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
					$lista_e=  $this->cugd01_estados->generateList("cod_republica=".$cod_republica, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
					$lista_m=  $this->cugd01_municipios->generateList("cod_republica=".$cod_republica." and cod_estado=".$cod_estado, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
					//$this->concatena($lista_r, 'republica');
					$this->set('estado', $lista_e);
					$this->set('municipio', $lista_m);

					//$this->set('cod_presi',$cod_republica);
					$this->set('selecion_estado',$cod_estado);
					$this->set('selecion_municipio',$cod_municipio);

					$deno_r=  $this->cugd01_republica->findAll("cod_republica=".$cod_republica);
					$this->set('republica',$deno_r);

					$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." ";
					$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio."  and cod_parroquia=".$selet."";
					//if($this->cugd01_parroquias->findCount($sql_selet) != 0){
					$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$cod_estado."  and cod_municipio=".$cod_municipio." ";
					if($this->cugd01_parroquias->findCount($sql_re) != 0){
						$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
						$denominacion =  $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
			   			$this->set('parroquia', $consulta_parroquia);
					}
					$this->set('selecion_parroquia','');
					//$opcion = 'si';

			 		$this->set('denominacion', $denominacion);
					$this->set('data', $data);
					$this->set('agregar', $opcion);
					$this->set('entidad_federal', $this->Session->read('entidad_federal'));
					$this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
					$this->set('cod_entidad', $this->Session->read('SScodentidad'));
					$this->set('boton', $boton);

				}else{
					$lista_r=  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
					$this->concatena($lista_r, 'republica');
					$this->concatena(array(), 'estado');
					$this->concatena(array(), 'municipio');
					$this->set('entidad_federal', $this->Session->read('entidad_federal'));
					$this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
					$this->set('cod_entidad', $this->Session->read('SScodentidad'));
					$this->set('estado', '');
					$this->set('municipio', '');
					$this->set('selecion_estado','');
					$this->set('selecion_municipio', '');
					$this->set('agregar', 'si');
				}
	}else{


			if($estado!=null && $estado!="no"){
					$sql_estado = "cod_republica=".$this->Session->read('cod_presi_geografico');
			        $sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
			        $this->set('selecion_estado', $estado);
			        $estado_var = $this->cugd01_estados->generateList($sql_estado, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
				    $this->set('estado', $estado_var);
			}else{
					$sql_estado="cod_republica=".$this->Session->read('cod_presi_geografico')."";
		        	$this->set('selecion_estado', '');
		           	if($this->cugd01_estados->findCount($sql_estado) != 0){
		        	$consulta_estado = $this->cugd01_estados->generateList($sql_estado, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			   		$this->set('estado', $consulta_estado);
			   		}else{$this->set('estado', 'vacio');}
					//$opcion = 'si';
			}//fin else


			if($municipio!=null && $municipio!="no"){
					$sql_municipio="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
					$consulta_municipio = $this->cugd01_municipios->generateList($sql_municipio, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
			   		$this->set('municipio', $consulta_municipio);
		        	$this->set('selecion_municipio', $municipio);
		        	//$opcion = 'si';
			}else{
				if($estado==null || $estado=="no"){
					$this->set('selecion_municipio', '');
					$this->set('municipio', '');
					//$opcion = 'si';
				}else{
					$sql_municipio="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
			        $this->set('selecion_municipio', '');
			        if($this->cugd01_municipios->findCount($sql_municipio) != 0){
			        	$consulta_municipio = $this->cugd01_municipios->generateList($sql_municipio, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
				   		$this->set('municipio', $consulta_municipio);
				   	}else{$this->set('municipio', 'vacio');}
					//$opcion = 'si';
				}
			}//fin else


			if($parroquia!=null && $parroquia!="no"){
					$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
					$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
			   		$this->set('parroquia', $consulta_parroquia);
			       	$this->set('selecion_parroquia', $parroquia);
			       	//$opcion = 'si';
			}else{
					if($municipio==null || $municipio=="no"){
						$this->set('selecion_parroquia', '');
						$this->set('parroquia', '');
						//$opcion = 'si';
					}else{
						$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio."";
			        	$this->set('selecion_parroquia', '');
			           	if($this->cugd01_parroquias->findCount($sql_parroquia) != 0){
			        		$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
				   			$this->set('parroquia', $consulta_parroquia);
				   		}else{$this->set('parroquia', 'vacio');}
						//$opcion = 'si';
					}
			}//fin else


			if($selet!=null && $selet!='otros' && $selet!="republica"){
					  	$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
					  	$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$selet."";
					if($this->cugd01_centropoblados->findCount($sql_selet) != 0){
			 			$data = $this->cugd01_centropoblados->findAll($sql_selet, null, null, null);
			 			$denominacion =  $this->cugd01_centropoblados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
			 			$data_aux = $data;
			 			foreach($data_aux as $data_aux_pre){}
			 			if($boton=='modificar'){$this->set('selecion_centro_poblado_2', $selet); $selet=$data_aux_pre['cugd01_centropoblados']['denominacion']; }
					}else{ $opcion = 'si'; }
						$this->set('selecion_centro_poblado', $selet);

			}else if($selet=='otros'){
						$opcion = 'si';
						$this->set('selecion_centro_poblado','otros');
						//*************************************
					$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
					$zona_postal = $this->cugd01_municipios->findAll($sql_re, null, null, null);
					foreach($zona_postal as $data_aux_pre){
						$selet=$data_aux_pre['cugd01_municipios']['zona_postal'];
					}
					$this->set('zona_postal',$selet);
					    $sql_centreo_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
						$datos_1 = $this->cugd01_centropoblados->findAll($sql_centreo_poblado, null, " cod_centro DESC");
						if(!isset($datos_1[0]["cugd01_centropoblados"]["cod_centro"])){$datos_1[0]["cugd01_centropoblados"]["cod_centro"]=0;}
						$this->set('cod_centro_aux',$datos_1[0]["cugd01_centropoblados"]["cod_centro"]+1);

					//*************************************
			}else{
					if(($parroquia!=null && $municipio!=null) && ($parroquia!="no" && $municipio!="no")){
						$sql_centreo_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
						if($this->cugd01_centropoblados->findCount($sql_centreo_poblado) != 0){
						$consulta_centreo_poblado = $this->cugd01_centropoblados->generateList($sql_centreo_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
				   		$denominacion =  $this->cugd01_centropoblados->generateList($sql_centreo_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
				   		$this->set('centro_poblado', $consulta_centreo_poblado);
						}
					}
				$this->set('selecion_centro_poblado','');
				$opcion = 'si';
			}


			$this->set('denominacion', $denominacion);
			$this->set('data', $data);
			$this->set('agregar', $opcion);
			$this->set('entidad_federal', $this->Session->read('entidad_federal'));
			$this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
			$this->set('cod_entidad', $this->Session->read('SScodentidad'));
			$this->set('boton', $boton);
	}
}




















function grabar($estado=null, $municipio=null, $parroquia=null, $selet=null, $boton=null){

	$this->layout = "ajax";


	$opcion = 'no';
	$data = '';
	$denominacion='';



	 if($estado==null){$estado=$this->data['cugp01centropoblados']['cod_estado'];}
	 if($municipio==null){$municipio=$this->data['cugp01centropoblados']['cod_municipio'];}
	 if($parroquia==null){$parroquia=$this->data['cugp01centropoblados']['cod_parroquia'];}
	 if($selet==null){$selet=$this->data['cugp01centropoblados']['cod_centro'];}


$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')."and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$selet."";
if($this->cugd01_centropoblados->findCount($sql_re) == 0){
$sql = "INSERT INTO cugd01_centros_poblados (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, denominacion, conocido, caracteristicas, economia, poblacion, orientacion, limites, dimension, zona_postal, clasificacion)   VALUES  ( '".$this->data['cugp01centropoblados']['cod_republica']."',  '".$this->data['cugp01centropoblados']['cod_estado']."',  '".$this->data['cugp01centropoblados']['cod_municipio']."',  '".$this->data['cugp01centropoblados']['cod_parroquia']."', '".$this->data['cugp01centropoblados']['cod_centro']."', '".$this->data['cugp01centropoblados']['denominacion']."',  '".$this->data['cugp01centropoblados']['conocido']."',  '".$this->data['cugp01centropoblados']['caracteristicas']."',  '".$this->data['cugp01centropoblados']['economia']."',  '".$this->data['cugp01centropoblados']['poblacion']."',  '".$this->data['cugp01centropoblados']['orientacion']."',  '".$this->data['cugp01centropoblados']['limites']."',  '".$this->data['cugp01centropoblados']['dimension']."',  '".$this->data['cugp01centropoblados']['zona_postal']."',  '".$this->data['cugp01centropoblados']['clasificacion']."')";
$this->cugd01_centropoblados->execute($sql);
}else{
$sql ="UPDATE cugd01_centros_poblados  SET  denominacion = '".$this->data['cugp01centropoblados']['denominacion']."', conocido = '".$this->data['cugp01centropoblados']['conocido']."', caracteristicas = '".$this->data['cugp01centropoblados']['caracteristicas']."', economia = '".$this->data['cugp01centropoblados']['economia']."', poblacion= '".$this->data['cugp01centropoblados']['poblacion']."', orientacion = '".$this->data['cugp01centropoblados']['orientacion']."',limites = '".$this->data['cugp01centropoblados']['limites']."',dimension = '".$this->data['cugp01centropoblados']['dimension']."',zona_postal = '".$this->data['cugp01centropoblados']['zona_postal']."', clasificacion = '".$this->data['cugp01centropoblados']['clasificacion']."' where cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$selet."";
$this->cugd01_centropoblados->execute($sql);

}

	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);


if($estado!=null){
		$sql_estado = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."";
        $sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
        $this->set('selecion_estado', $estado);

        $estado_var = $this->cugd01_estados->generateList(null, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	    $this->set('estado', $estado_var);
}else{     	$sql_estado="cod_republica=".$this->Session->read('cod_presi_geografico')."";
        	$this->set('selecion_estado', '');
           	if($this->cugd01_estados->findCount($sql_estado) != 0){
        	$consulta_estado = $this->cugd01_estados->generateList(null, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	   		$this->set('estado', $consulta_estado);
	   		}else{$this->set('estado', 'vacio');}
			//$opcion = 'si';
}//fin else


if($municipio!=null){
			$sql_municipio="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
			$consulta_municipio = $this->cugd01_municipios->generateList($sql_municipio, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	   		$this->set('municipio', $consulta_municipio);
        	$this->set('selecion_municipio', $municipio);
        	//$opcion = 'si';
}else{
	if($estado==null){
			$this->set('selecion_municipio', '');
			$this->set('municipio', '');
			//$opcion = 'si';
	}else{
			$sql_municipio="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
        	$this->set('selecion_municipio', '');
           	if($this->cugd01_municipios->findCount($sql_municipio) != 0){
        	$consulta_municipio = $this->cugd01_municipios->generateList($sql_municipio, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	   		$this->set('municipio', $consulta_municipio);
	   		}else{$this->set('municipio', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($parroquia!=null){
			$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
			$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	   		$this->set('parroquia', $consulta_parroquia);
        	$this->set('selecion_parroquia', $parroquia);
        	//$opcion = 'si';
}else{
	if($municipio==null){
			$this->set('selecion_parroquia', '');
			$this->set('parroquia', '');
			//$opcion = 'si';
	}else{
			$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio."";
        	$this->set('selecion_parroquia', '');
           	if($this->cugd01_parroquias->findCount($sql_parroquia) != 0){
        	$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	   		$this->set('parroquia', $consulta_parroquia);
	   		}else{$this->set('parroquia', 'vacio');}
			//$opcion = 'si';
		}
}//fin else



if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
		  	$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$selet."";
		if($this->cugd01_centropoblados->findCount($sql_selet) != 0){
 			$data = $this->cugd01_centropoblados->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd01_centropoblados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_centro_poblado_2', $selet); $selet=$data_aux_pre['cugd01_centropoblados']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_centro_poblado', $selet);


}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_centro_poblado','otros');
			$sql_centreo_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
			$datos_1 = $this->cugd01_centropoblados->findAll($sql_centreo_poblado, null, " cod_centro DESC");
			if(!isset($datos_1[0]["cugd01_centropoblados"]["cod_centro"])){$datos_1[0]["cugd01_centropoblados"]["cod_centro"]=0;}
			$this->set('cod_centro_aux',$datos_1[0]["cugd01_centropoblados"]["cod_centro"]+1);
}else{
		if($parroquia!=null){
			$sql_centreo_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
			if($this->cugd01_centropoblados->findCount($sql_centreo_poblado) != 0){
			$consulta_centreo_poblado = $this->cugd01_centropoblados->generateList($sql_centreo_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	   		$denominacion =  $this->cugd01_centropoblados->generateList($sql_centreo_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	   		$this->set('centro_poblado', $consulta_centreo_poblado);
			}
		}
	$this->set('selecion_centro_poblado','');
	$opcion = 'si';
}


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

































function eliminar($estado=null, $municipio=null, $parroquia=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$opcion = 'no';
	$data = '';

	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);


	$sql_re = "cod_presi=".$cod_presi."  and cod_entidad=".$cod_entidad."  and cod_tipo_inst=".$cod_tipo_inst."  and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$selet."";
    if($this->v_cugd01_verificacion->findCount($sql_re)==0){
		$sql= "DELETE  FROM  cugd01_centros_poblados  WHERE cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$selet." ";
		$this->cugd01_centropoblados->execute($sql);
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
}else{     	$sql_estado="cod_republica=".$this->Session->read('cod_presi_geografico')."";
        	$this->set('selecion_estado', '');
           	if($this->cugd01_estados->findCount($sql_estado) != 0){
        	$consulta_estado = $this->cugd01_estados->generateList(null, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	   		$this->set('estado', $consulta_estado);
	   		}else{$this->set('estado', 'vacio');}
			//$opcion = 'si';
}//fin else


if($municipio!=null){
			$sql_municipio="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
			$consulta_municipio = $this->cugd01_municipios->generateList($sql_municipio, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	   		$this->set('municipio', $consulta_municipio);
        	$this->set('selecion_municipio', $municipio);
        	//$opcion = 'si';
}else{
	if($estado==null){
			$this->set('selecion_municipio', '');
			$this->set('municipio', '');
			//$opcion = 'si';
	}else{
			$sql_municipio="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." ";
        	$this->set('selecion_municipio', '');
           	if($this->cugd01_municipios->findCount($sql_municipio) != 0){
        	$consulta_municipio = $this->cugd01_municipios->generateList($sql_municipio, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	   		$this->set('municipio', $consulta_municipio);
	   		}else{$this->set('municipio', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($parroquia!=null){
			$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
			$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	   		$this->set('parroquia', $consulta_parroquia);
        	$this->set('selecion_parroquia', $parroquia);
        	//$opcion = 'si';
}else{
	if($municipio==null){
			$this->set('selecion_parroquia', '');
			$this->set('parroquia', '');
			//$opcion = 'si';
	}else{
			$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio."";
        	$this->set('selecion_parroquia', '');
           	if($this->cugd01_parroquias->findCount($sql_parroquia) != 0){
        	$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	   		$this->set('parroquia', $consulta_parroquia);
	   		}else{$this->set('parroquia', 'vacio');}
			//$opcion = 'si';
		}
}//fin else


$selet = null;


if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
		  	$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$selet."";
		if($this->cugd01_centropoblados->findCount($sql_selet) != 0){
 			$data = $this->cugd01_centropoblados->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd01_centropoblados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_centro_poblado_2', $selet); $selet=$data_aux_pre['cugd01_centropoblados']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_centro_poblado', $selet);

}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_centro_poblado','otros');
			$sql_centreo_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
			$datos_1 = $this->cugd01_centropoblados->findAll($sql_centreo_poblado, null, " cod_centro DESC");
			if(!isset($datos_1[0]["cugd01_centropoblados"]["cod_centro"])){$datos_1[0]["cugd01_centropoblados"]["cod_centro"]=0;}
			$this->set('cod_centro_aux',$datos_1[0]["cugd01_centropoblados"]["cod_centro"]+1);
}else{
		if($parroquia!=null){
			$sql_centreo_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
			if($this->cugd01_centropoblados->findCount($sql_centreo_poblado) != 0){
			$consulta_centreo_poblado = $this->cugd01_centropoblados->generateList($sql_centreo_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	   		$denominacion =  $this->cugd01_centropoblados->generateList($sql_centreo_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	   		$this->set('centro_poblado', $consulta_centreo_poblado);
			}
		}
	$this->set('selecion_centro_poblado','');
	$opcion = 'si';
}




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

	 	 if($pag_num==null){$pag_num=1;}

                    $data = array();
                    $Tfilas=$this->cugd01_centropoblados->findCount(null);
			        if($Tfilas!=0){
//			        	$pagina=1;
			        	$Tfilas=(int)ceil($Tfilas/1);
			        	$this->set('pag_cant',$pag_num.'/'.$Tfilas);
						$this->set('total_paginas',$Tfilas);
						$this->set('pagina_actual',$pag_num);
						$this->set('ultimo',$Tfilas);
			     	    $data = $this->cugd01_centropoblados->findAll(null, null, 'cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro  ASC', 1, $pag_num, null);
				        $this->set('siguiente',$pag_num+1);
						$this->set('anterior',$pag_num-1);
						$this->bt_nav($Tfilas,$pag_num);
			          }


	     if(isset($data[0]["cugd01_centropoblados"]["cod_republica"])){

        	$sql_1 = "cod_republica=".$data[0]["cugd01_centropoblados"]["cod_republica"];
        	$sql_2 = "cod_republica=".$data[0]["cugd01_centropoblados"]["cod_republica"]." and cod_estado=".$data[0]["cugd01_centropoblados"]["cod_estado"];
        	$sql_3 = "cod_republica=".$data[0]["cugd01_centropoblados"]["cod_republica"]." and cod_estado=".$data[0]["cugd01_centropoblados"]["cod_estado"]." and cod_municipio=".$data[0]["cugd01_centropoblados"]["cod_municipio"];
        	$sql_4 = "cod_republica=".$data[0]["cugd01_centropoblados"]["cod_republica"]." and cod_estado=".$data[0]["cugd01_centropoblados"]["cod_estado"]." and cod_municipio=".$data[0]["cugd01_centropoblados"]["cod_municipio"]." and cod_parroquia=".$data[0]["cugd01_centropoblados"]["cod_parroquia"];

        }else{
        	$sql_1="";
        	$sql_2="";
        	$sql_3="";
        	$sql_4="";

        }



         $republica = $this->cugd01_republica->findAll($sql_1, null, null, null);
	 	 $this->set('var_republica', $republica);

	 	 $estados = $this->cugd01_estados->findAll($sql_2, null, 'cod_republica, cod_estado ASC', null);
	 	 $this->set('var_estados', $estados);

	 	 $municipios = $this->cugd01_municipios->findAll($sql_3, null, 'cod_republica, cod_estado, cod_municipio ASC', null);
	 	 $this->set('var_municipios', $municipios);

	 	 $parroquias = $this->cugd01_parroquias->findAll($sql_4, null, 'cod_republica, cod_estado, cod_municipio, cod_parroquia ASC', null);
	 	 $this->set('var_parroquias', $parroquias);


         $this->set('data',$data);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));



$lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
$this->set('lista', $lista_republicas);


if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}




}//fin class



?>
