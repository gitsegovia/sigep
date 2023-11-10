<?php

 class Cugp01parroquiasController extends AppController{

	var $name = 'Cugp01parroquias';
	var $uses = array('cugd01_republica', 'cugd01_estados', 'cugd01_municipios', 'cugd01_parroquias', 'cugd90_municipio_defecto', 'v_cugd01_verificacion');
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


function index($estado=null, $municipio=null, $selet=null, $boton=null){
	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';
	$denominacion='';

	if($selet=="republica"){$_SESSION["cod_presi_geografico"] = $boton; $selet =null; $boton = null;}
	if(empty($_SESSION["cod_presi_geografico"])){$_SESSION["cod_presi_geografico"] = $this->Session->read('SScodpresi'); }

	$lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
	$this->set('lista', $lista_republicas);

	/* if($selet!=null && $selet!='otros'){$this->set('selecion', $selet);
	}else if($selet=='otros'){$this->set('selecion',$selet); $selet='0';
	}else{ $selet=$this->Session->read('SScodentidad'); $this->set('selecion',$selet);}*/

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
					$this->set('selecion_estado','');
					$this->set('selecion_municipio','');
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
				   	$estado =null;
					//$opcion = 'si';
			}//fin else


			if($municipio!=null && $municipio!="no"){
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


			if($selet!=null && $selet!='otros' && $selet!="republica"){
				$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
				$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio."  and cod_parroquia=".$selet."";
				if($this->cugd01_parroquias->findCount($sql_selet) != 0){
			 		$data = $this->cugd01_parroquias->findAll($sql_selet, null, null, null);
			 		$denominacion =  $this->cugd01_parroquias->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
			 		$data_aux = $data;
			 		foreach($data_aux as $data_aux_pre){}
			 		if($boton=='modificar'){$this->set('selecion_parroquia_2', $selet); $selet=$data_aux_pre['cugd01_parroquias']['denominacion']; }
				}else{ $opcion = 'si'; }
					$this->set('selecion_parroquia', $selet);
				}else if($selet=='otros'){
					$opcion = 'si';
					$this->set('selecion_parroquia','otros');
					//*************************************
					$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
					$zona_postal = $this->cugd01_municipios->findAll($sql_re, null, null, null);
					foreach($zona_postal as $data_aux_pre){
						$selet=$data_aux_pre['cugd01_municipios']['zona_postal'];
					}
					$datos_aux = $this->cugd01_parroquias->findAll($sql_re, null, "cod_parroquia desc", null);
			        $this->set('cod_parroquia', isset($datos_aux[0]["cugd01_parroquias"]["cod_parroquia"])?$datos_aux[0]["cugd01_parroquias"]["cod_parroquia"] + 1:1);

					$this->set('zona_postal',$selet);
					//*************************************
				}else{
					if(($municipio!=null && $estado!=null) && ($municipio!="no" && $estado!="no")){
						$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." ";
						if($this->cugd01_parroquias->findCount($sql_re) != 0){
							$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
							$denominacion =  $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
							$this->set('parroquia', $consulta_parroquia);
						}
					}
					$this->set('selecion_parroquia','');
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

}// fin index




















function grabar($estado=null, $municipio=null, $selet=null, $boton=null){

	$this->layout = "ajax";


	$opcion = 'no';
	$data = '';
	$denominacion='';



	 if($estado==null){$estado=$this->data['cugp01parroquias']['cod_estado'];}
	 if($municipio==null){$municipio=$this->data['cugp01parroquias']['cod_municipio'];}
	 if($selet==null){$selet=$this->data['cugp01parroquias']['cod_parroquia'];}


$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')."and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$selet."";
if($this->cugd01_parroquias->findCount($sql_re) == 0){
$sql = "INSERT INTO cugd01_parroquias (cod_republica, cod_estado, cod_municipio, cod_parroquia, denominacion, conocido, caracteristicas, economia, poblacion, orientacion, limites, dimension, zona_postal, clasificacion)   VALUES  ( '".$this->data['cugp01parroquias']['cod_republica']."',  '".$this->data['cugp01parroquias']['cod_estado']."',  '".$this->data['cugp01parroquias']['cod_municipio']."',  '".$this->data['cugp01parroquias']['cod_parroquia']."',  '".$this->data['cugp01parroquias']['denominacion']."',  '".$this->data['cugp01parroquias']['conocido']."',  '".$this->data['cugp01parroquias']['caracteristicas']."',  '".$this->data['cugp01parroquias']['economia']."',  '".$this->data['cugp01parroquias']['poblacion']."',  '".$this->data['cugp01parroquias']['orientacion']."',  '".$this->data['cugp01parroquias']['limites']."',  '".$this->data['cugp01parroquias']['dimension']."',  '".$this->data['cugp01parroquias']['zona_postal']."',  '".$this->data['cugp01parroquias']['clasificacion']."')";
$this->cugd01_parroquias->execute($sql);
}else{

$sql ="UPDATE cugd01_parroquias  SET  denominacion = '".$this->data['cugp01parroquias']['denominacion']."', conocido = '".$this->data['cugp01parroquias']['conocido']."', caracteristicas = '".$this->data['cugp01parroquias']['caracteristicas']."', economia = '".$this->data['cugp01parroquias']['economia']."', poblacion= '".$this->data['cugp01parroquias']['poblacion']."', orientacion = '".$this->data['cugp01parroquias']['orientacion']."',limites = '".$this->data['cugp01parroquias']['limites']."',dimension = '".$this->data['cugp01parroquias']['dimension']."',zona_postal = '".$this->data['cugp01parroquias']['zona_postal']."', clasificacion = '".$this->data['cugp01parroquias']['clasificacion']."' where cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$selet."";
$this->cugd01_parroquias->execute($sql);

}

	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);

if($estado!=null){
		$sql_estado = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."";
        $sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')."  ";
        $this->set('selecion_estado', $estado);

        $estado_var = $this->cugd01_estados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	    $this->set('estado', $estado_var);
}else{     	$sql_estado="cod_republica=".$this->Session->read('cod_presi_geografico')."";
        	$this->set('selecion_estado', '');
           	if($this->cugd01_estados->findCount($sql_estado) != 0){
        	$consulta_estado = $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico'), 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
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


if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
		  	$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio."  and cod_parroquia=".$selet."";
		if($this->cugd01_parroquias->findCount($sql_selet) != 0){
 			$data = $this->cugd01_parroquias->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd01_parroquias->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_parroquia_2', $selet); $selet=$data_aux_pre['cugd01_parroquias']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_parroquia', $selet);

}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_parroquia','otros');

			$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
			$datos_aux = $this->cugd01_parroquias->findAll($sql_re, null, "cod_parroquia desc", null);
            $this->set('cod_parroquia', isset($datos_aux[0]["cugd01_parroquias"]["cod_parroquia"])?$datos_aux[0]["cugd01_parroquias"]["cod_parroquia"] + 1:1);

}else{
		if($municipio!=null){
			$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." ";
			if($this->cugd01_parroquias->findCount($sql_re) != 0){
			$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	   		$denominacion =  $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	   		$this->set('parroquia', $consulta_parroquia);
			}
		}
	$this->set('selecion_parroquia','');
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

































function eliminar($estado=null, $municipio=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$opcion = 'no';
	$data = '';

	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);

	$sql_re = "cod_presi=".$cod_presi."  and cod_entidad=".$cod_entidad."  and cod_tipo_inst=".$cod_tipo_inst."  and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$selet."";
    if($this->v_cugd01_verificacion->findCount($sql_re)==0){
		$sql= "DELETE  FROM  cugd01_parroquias  WHERE cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$selet." ";
		$this->cugd01_parroquias->execute($sql);
		$denominacion='';
		$this->set('Message_existe', "REGISTRO ELIMINADO");
    }else{
    	$this->set('errorMessage', "NO PUEDE ELIMINAR ESTE REGISTRO.....ESTA EN USO");
    }

if($estado!=null){
		$sql_estado = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."";
        $sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')."  ";
        $this->set('selecion_estado', $estado);

        $estado_var = $this->cugd01_estados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	    $this->set('estado', $estado_var);
}else{     	$sql_estado="cod_republica=".$this->Session->read('cod_presi_geografico')."";
        	$this->set('selecion_estado', '');
           	if($this->cugd01_estados->findCount($sql_estado) != 0){
        	$consulta_estado = $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico'), 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
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

$selet = null;

if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
		  	$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio."  and cod_parroquia=".$selet."";
		if($this->cugd01_parroquias->findCount($sql_selet) != 0){
 			$data = $this->cugd01_parroquias->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd01_parroquias->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_parroquia_2', $selet); $selet=$data_aux_pre['cugd01_parroquias']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_parroquia', $selet);

}else if($selet=='otros'){
			$opcion = 'si';
			$this->set('selecion_parroquia','otros');

			$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." ";
			$datos_aux = $this->cugd01_parroquias->findAll($sql_re, null, "cod_parroquia desc", null);
            $this->set('cod_parroquia', isset($datos_aux[0]["cugd01_parroquias"]["cod_parroquia"])?$datos_aux[0]["cugd01_parroquias"]["cod_parroquia"] + 1:1);

}else{
		if($municipio!=null){
			$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." ";
			if($this->cugd01_parroquias->findCount($sql_re) != 0){
			$consulta_parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	   		$denominacion =  $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	   		$this->set('parroquia', $consulta_parroquia);
			}
		}
	$this->set('selecion_parroquia','');
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
                    $Tfilas=$this->cugd01_parroquias->findCount(null);
			        if($Tfilas!=0){
			        	$Tfilas=(int)ceil($Tfilas/1);
			        	$this->set('pag_cant',$pag_num.'/'.$Tfilas);
						$this->set('total_paginas',$Tfilas);
						$this->set('pagina_actual',$pag_num);
						$this->set('ultimo',$Tfilas);
			     	    $data = $this->cugd01_parroquias->findAll(null, null, 'cod_republica, cod_estado, cod_municipio, cod_parroquia ASC', 1, $pag_num, null);
				        $this->set('siguiente',$pag_num+1);
						$this->set('anterior',$pag_num-1);
						$this->bt_nav($Tfilas,$pag_num);
			          }

	     if(isset($data[0]["cugd01_parroquias"]["cod_republica"])){

        	$sql_1 = "cod_republica=".$data[0]["cugd01_parroquias"]["cod_republica"];
        	$sql_2 = "cod_republica=".$data[0]["cugd01_parroquias"]["cod_republica"]." and cod_estado=".$data[0]["cugd01_parroquias"]["cod_estado"];
        	$sql_3 = "cod_republica=".$data[0]["cugd01_parroquias"]["cod_republica"]." and cod_estado=".$data[0]["cugd01_parroquias"]["cod_estado"]." and cod_municipio=".$data[0]["cugd01_parroquias"]["cod_municipio"];

        }else{
        	$sql_1="";
        	$sql_2="";
        	$sql_3="";

        }



         $republica = $this->cugd01_republica->findAll($sql_1, null, null, null);
	 	 $this->set('var_republica', $republica);

	 	 $estados = $this->cugd01_estados->findAll($sql_2, null, 'cod_republica, cod_estado ASC', null);
	 	 $this->set('var_estados', $estados);

	 	 $municipios = $this->cugd01_municipios->findAll($sql_3, null, 'cod_republica, cod_estado, cod_municipio ASC', null);
	 	 $this->set('var_municipios', $municipios);



         $this->set('data',$data);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));



if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}




}//fin class



?>
