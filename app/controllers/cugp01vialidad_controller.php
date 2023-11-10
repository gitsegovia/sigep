<?php

 class Cugp01vialidadController extends AppController{

	var $name = 'Cugp01vialidad';
	var $uses = array('cugd01_republica', 'cugd01_estados', 'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados',  'cugd01_vialidad', 'cugd90_municipio_defecto');
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
}//fin index2

function index($estado=null, $municipio=null, $parroquia=null, $centro_poblado=null, $selet=null, $boton=null){
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
					$this->set('selecion_centro_poblado', '');
					$this->set('selecion_vialidad', '');
					$this->set('centro_poblado', '');
								 		
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
					$this->set("selecion_estado", '');
					$this->set('selecion_municipio','');
					$this->set('agregar', 'si');
				}
				
	}else{

				if($estado!=null && $estado!="no"){
						$sql_estado = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."";
				        $sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico');
				        $this->set('selecion_estado', $estado);
				        $estado_var = $this->cugd01_estados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
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
				
				
				if($centro_poblado!=null && $centro_poblado!="no"){
						$sql_centro_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
						$consulta_centro_poblado = $this->cugd01_centropoblados->generateList($sql_centro_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
					   	$this->set('centro_poblado', $consulta_centro_poblado);
				        $this->set('selecion_centro_poblado', $centro_poblado);
				        //$opcion = 'si';
				}else{
						if($parroquia==null || $parroquia=="no"){
							$this->set('selecion_centro_poblado', '');
							$this->set('centro_poblado', '');
							//$opcion = 'si';
						}else{
							$sql_centro_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
				        	$this->set('selecion_centro_poblado', '');
				           	if($this->cugd01_centropoblados->findCount($sql_parroquia) != 0){
				        		$consulta_centro_poblado = $this->cugd01_centropoblados->generateList($sql_centro_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
					   			$this->set('centro_poblado', $consulta_centro_poblado);
					   		}else{$this->set('centro_poblado', 'vacio');}
							//$opcion = 'si';
						}
				}//fin else
				
				
				if($selet!=null && $selet!='otros' && $selet!="republica"){
						$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."  and cod_centro=".$centro_poblado."";
						$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro_poblado." and cod_vialidad=".$selet."";
						if($this->cugd01_vialidad->findCount($sql_selet) != 0){
				 			$data = $this->cugd01_vialidad->findAll($sql_selet, null, null, null);
				 			$denominacion =  $this->cugd01_vialidad->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
				 			$data_aux = $data;
				 			foreach($data_aux as $data_aux_pre){}
				 			if($boton=='modificar'){$this->set('selecion_vialidad_2', $selet); $selet=$data_aux_pre['cugd01_vialidad']['denominacion']; }
						}else{ $opcion = 'si'; }
							$this->set('selecion_vialidad', $selet);
							
				}else if($selet=='otros'){ ;
							$opcion = 'si';
							$this->set('selecion_vialidad','otros');
							$sql_aux_cod="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro_poblado."";
							$datos_1 = $this->cugd01_vialidad->findAll($sql_aux_cod, null, " cod_vialidad DESC");
							if(!isset($datos_1[0]["cugd01_vialidad"]["cod_vialidad"])){$datos_1[0]["cugd01_vialidad"]["cod_vialidad"]=0;}
							$this->set('cod_vialidad_aux',$datos_1[0]["cugd01_vialidad"]["cod_vialidad"]+1);
				}else{
						if(($parroquia!=null && $centro_poblado!=null) && ($parroquia!="no" && $centro_poblado!="no")){
							$sql_vialidad="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."  and cod_centro=".$centro_poblado."";
							if($this->cugd01_vialidad->findCount($sql_vialidad) != 0){
								$consulta_vialidad = $this->cugd01_vialidad->generateList($sql_vialidad, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
						   		$denominacion =  $this->cugd01_vialidad->generateList($sql_vialidad, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
						   		$this->set('vialidad', $consulta_vialidad);
							}
						}
						$this->set('selecion_vialidad','');
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




















function grabar($estado=null, $municipio=null, $parroquia=null, $centro_poblado=null, $selet=null, $boton=null){

	$this->layout = "ajax";


	$opcion = 'no';
	$data = '';
	$denominacion='';



	 if($estado==null){$estado=$this->data['cugp01vialidad']['cod_estado'];}
	 if($municipio==null){$municipio=$this->data['cugp01vialidad']['cod_municipio'];}
	 if($parroquia==null){$parroquia=$this->data['cugp01vialidad']['cod_parroquia'];}
 	 if($centro_poblado==null){$centro_poblado=$this->data['cugp01vialidad']['cod_centro'];}
	 if($selet==null){$selet=$this->data['cugp01vialidad']['cod_vialidad'];}


$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')."and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro_poblado."  and cod_vialidad=".$selet."";
if($this->cugd01_vialidad->findCount($sql_re) == 0){
$sql = "INSERT INTO cugd01_vialidad (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_vialidad, denominacion)   VALUES  ( '".$this->data['cugp01vialidad']['cod_republica']."',  '".$this->data['cugp01vialidad']['cod_estado']."',  '".$this->data['cugp01vialidad']['cod_municipio']."',  '".$this->data['cugp01vialidad']['cod_parroquia']."', '".$this->data['cugp01vialidad']['cod_centro']."',  '".$this->data['cugp01vialidad']['cod_vialidad']."', '".$this->data['cugp01vialidad']['denominacion']."')";
$this->cugd01_vialidad->execute($sql);
}else{
$sql ="UPDATE cugd01_vialidad SET  denominacion = '".$this->data['cugp01vialidad']['denominacion']."'   where cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."  and cod_centro=".$centro_poblado."  and cod_vialidad=".$selet."";
$this->cugd01_vialidad->execute($sql);

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



if($centro_poblado!=null){
			$sql_centro_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
			$consulta_centro_poblado = $this->cugd01_centropoblados->generateList($sql_centro_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	   		$this->set('centro_poblado', $consulta_centro_poblado);
        	$this->set('selecion_centro_poblado', $centro_poblado);
        	//$opcion = 'si';
}else{
	if($parroquia==null){
			$this->set('selecion_centro_poblado', '');
			$this->set('centro_poblado', '');
			//$opcion = 'si';
	}else{
	        $sql_centro_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
        	$this->set('selecion_centro_poblado', '');
           	if($this->cugd01_centropoblados->findCount($sql_parroquia) != 0){
        	$consulta_centro_poblado = $this->cugd01_centropoblados->generateList($sql_centro_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	   		$this->set('centro_poblado', $consulta_centro_poblado);
	   		}else{$this->set('centro_poblado', 'vacio');}
			//$opcion = 'si';
		}
}//fin else




if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."  and cod_centro=".$centro_poblado."";
		  	$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro_poblado." and cod_vialidad=".$selet."";
		if($this->cugd01_vialidad->findCount($sql_selet) != 0){
 			$data = $this->cugd01_vialidad->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd01_vialidad->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_vialidad_2', $selet); $selet=$data_aux_pre['cugd01_vialidad']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_vialidad', $selet);

}else if($selet=='otros'){ ;
			$opcion = 'si';
			$this->set('selecion_vialidad','otros');
			$sql_aux_cod="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro_poblado."";
			$datos_1 = $this->cugd01_vialidad->findAll($sql_aux_cod, null, " cod_vialidad DESC");
			if(!isset($datos_1[0]["cugd01_vialidad"]["cod_vialidad"])){$datos_1[0]["cugd01_vialidad"]["cod_vialidad"]=0;}
			$this->set('cod_vialidad_aux',$datos_1[0]["cugd01_vialidad"]["cod_vialidad"]+1);
}else{
		if($centro_poblado!=null){
			$sql_vialidad="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."  and cod_centro=".$centro_poblado."";
			if($this->cugd01_vialidad->findCount($sql_vialidad) != 0){
			$consulta_vialidad = $this->cugd01_vialidad->generateList($sql_vialidad, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	   		$denominacion =  $this->cugd01_vialidad->generateList($sql_vialidad, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	   		$this->set('vialidad', $consulta_vialidad);
			}
		}
	$this->set('selecion_vialidad','');
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

































function eliminar($estado=null, $municipio=null, $parroquia=null, $centro_poblado=null, $selet=null, $boton=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';

	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);

	$sql= "DELETE  FROM  cugd01_vialidad  WHERE cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro_poblado."  and cod_vialidad=".$selet." ";
	$this->cugd01_vialidad->execute($sql);
	$denominacion='';




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



if($centro_poblado!=null){
			$sql_centro_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
			$consulta_centro_poblado = $this->cugd01_centropoblados->generateList($sql_centro_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	   		$this->set('centro_poblado', $consulta_centro_poblado);
        	$this->set('selecion_centro_poblado', $centro_poblado);
        	//$opcion = 'si';
}else{
	if($parroquia==null){
			$this->set('selecion_centro_poblado', '');
			$this->set('centro_poblado', '');
			//$opcion = 'si';
	}else{
	        $sql_centro_poblado="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."";
        	$this->set('selecion_centro_poblado', '');
           	if($this->cugd01_centropoblados->findCount($sql_parroquia) != 0){
        	$consulta_centro_poblado = $this->cugd01_centropoblados->generateList($sql_centro_poblado, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	   		$this->set('centro_poblado', $consulta_centro_poblado);
	   		}else{$this->set('centro_poblado', 'vacio');}
			//$opcion = 'si';
		}
}//fin else


$selet=null;


if($selet!=null && $selet!='otros'){
		  	$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."  and cod_centro=".$centro_poblado."";
		  	$sql_selet = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro_poblado." and cod_vialidad=".$selet."";
		if($this->cugd01_vialidad->findCount($sql_selet) != 0){
 			$data = $this->cugd01_vialidad->findAll($sql_selet, null, null, null);
 			$denominacion =  $this->cugd01_vialidad->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
 			$data_aux = $data;
 			foreach($data_aux as $data_aux_pre){}
 			if($boton=='modificar'){$this->set('selecion_vialidad_2', $selet); $selet=$data_aux_pre['cugd01_vialidad']['denominacion']; }
		}else{ $opcion = 'si'; }
			$this->set('selecion_vialidad', $selet);

}else if($selet=='otros'){ ;
			$opcion = 'si';
			$this->set('selecion_vialidad','otros');
			$sql_aux_cod="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro_poblado."";
			$datos_1 = $this->cugd01_vialidad->findAll($sql_aux_cod, null, " cod_vialidad DESC");
			if(!isset($datos_1[0]["cugd01_vialidad"]["cod_vialidad"])){$datos_1[0]["cugd01_vialidad"]["cod_vialidad"]=0;}
			$this->set('cod_vialidad_aux',$datos_1[0]["cugd01_vialidad"]["cod_vialidad"]+1);
}else{
		if($centro_poblado!=null){
			$sql_vialidad="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$estado."  and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."  and cod_centro=".$centro_poblado."";
			if($this->cugd01_vialidad->findCount($sql_vialidad) != 0){
			$consulta_vialidad = $this->cugd01_vialidad->generateList($sql_vialidad, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	   		$denominacion =  $this->cugd01_vialidad->generateList($sql_vialidad, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	   		$this->set('vialidad', $consulta_vialidad);
			}
		}
	$this->set('selecion_vialidad','');
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
                    $Tfilas=$this->cugd01_vialidad->findCount(null);
			        if($Tfilas!=0){
//			        	$pagina=1;
			        	$Tfilas=(int)ceil($Tfilas/1);
			        	$this->set('pag_cant',$pag_num.'/'.$Tfilas);
						$this->set('total_paginas',$Tfilas);
						$this->set('pagina_actual',$pag_num);
						$this->set('ultimo',$Tfilas);
			     	    $data = $this->cugd01_vialidad->findAll(null, null, 'cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_vialidad  ASC', 1, $pag_num, null);
				        $this->set('siguiente',$pag_num+1);
						$this->set('anterior',$pag_num-1);
						$this->bt_nav($Tfilas,$pag_num);
			          }

         $this->set('data',$data);

        if(isset($data[0]["cugd01_vialidad"]["cod_republica"])){

        	$sql_1 = "cod_republica=".$data[0]["cugd01_vialidad"]["cod_republica"];
        	$sql_2 = "cod_republica=".$data[0]["cugd01_vialidad"]["cod_republica"]." and cod_estado=".$data[0]["cugd01_vialidad"]["cod_estado"];
        	$sql_3 = "cod_republica=".$data[0]["cugd01_vialidad"]["cod_republica"]." and cod_estado=".$data[0]["cugd01_vialidad"]["cod_estado"]." and cod_municipio=".$data[0]["cugd01_vialidad"]["cod_municipio"];
        	$sql_4 = "cod_republica=".$data[0]["cugd01_vialidad"]["cod_republica"]." and cod_estado=".$data[0]["cugd01_vialidad"]["cod_estado"]." and cod_municipio=".$data[0]["cugd01_vialidad"]["cod_municipio"]." and cod_parroquia=".$data[0]["cugd01_vialidad"]["cod_parroquia"];
        	$sql_5 = "cod_republica=".$data[0]["cugd01_vialidad"]["cod_republica"]." and cod_estado=".$data[0]["cugd01_vialidad"]["cod_estado"]." and cod_municipio=".$data[0]["cugd01_vialidad"]["cod_municipio"]." and cod_parroquia=".$data[0]["cugd01_vialidad"]["cod_parroquia"]." and cod_centro=".$data[0]["cugd01_vialidad"]["cod_centro"];

        }else{
        	$sql_1="";
        	$sql_2="";
        	$sql_3="";
        	$sql_4="";
        	$sql_5="";

        }



         $republica = $this->cugd01_republica->findAll($sql_1, null, null, null);
	 	 $this->set('var_republica', $republica);

	 	 $estados = $this->cugd01_estados->findAll($sql_2, null, 'cod_republica, cod_estado ASC', null);
	 	 $this->set('var_estados', $estados);

	 	 $municipios = $this->cugd01_municipios->findAll($sql_3, null, 'cod_republica, cod_estado, cod_municipio ASC', null);
	 	 $this->set('var_municipios', $municipios);

	 	 $parroquias = $this->cugd01_parroquias->findAll($sql_4, null, 'cod_republica, cod_estado, cod_municipio, cod_parroquia ASC', null);
	 	 $this->set('var_parroquias', $parroquias);

		 $centropoblados = $this->cugd01_centropoblados->findAll($sql_5, null, 'cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro ASC', null);
	 	 $this->set('var_centropoblados', $centropoblados);




		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));



$lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
$this->set('lista', $lista_republicas);


if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}




function agregar_ventana(){
	$this->layout = "ajax";

	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');

	$this->set('cod_republica',$cod_republica);
	$this->set('cod_estado',$cod_estado);
	$this->set('cod_municipio',$cod_municipio);
	$this->set('cod_parroquia',$cod_parroquia);
	$this->set('cod_centro',$cod_centro);

	$this->set('deno_republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('deno_estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('deno_municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('deno_parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));
	$this->set('deno_centro',$this->cugd01_centropoblados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro'), $order ="cod_centro ASC"));

	$sql="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro'";
    $vialidad=$this->cugd01_vialidad->generateList($sql,'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	$vialidad = $vialidad != null ? $vialidad : array();
	$this->concatena($vialidad, 'vialidad');


}

function agregar_vialidad($opcion=null){
	$this->layout = "ajax";
	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');

    $conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro;
	$ver=$this->cugd01_vialidad->execute("select * from cugd01_vialidad where ".$conditions." order by cod_vialidad desc limit 1");
	if($ver!=null)
		$cod_vialidad=$ver[0][0]['cod_vialidad']+1;
	else
		$cod_vialidad=1;

	$this->set('cod_vialidad',$cod_vialidad);

	if($opcion!='agregar'){
		$deno=$this->cugd01_vialidad->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_vialidad=".$opcion, $order ="cod_vialidad ASC");
		echo "<script>document.getElementById('deno_vialidad').value='".$deno."';</script>";

		$sql="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro'";
	    $vialidad=$this->cugd01_vialidad->generateList($sql,'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
		$vialidad = $vialidad != null ? $vialidad : array();
		$this->concatena($vialidad, 'vialidad');
		$this->set('codigo',$opcion);
	}else{
		echo "<script>document.getElementById('deno_vialidad').value='';</script>";
		$this->set('agregar',$cod_vialidad);
	}

}



function guardar(){
	$this->layout = "ajax";

	$cod_republica = $this->Session->read('CC_republica');
 	$estado    = $this->Session->read('CC_estado');
 	$municipio = $this->Session->read('CC_municipio');
 	$parroquia = $this->Session->read('CC_parroquia');
    $centro_poblado    = $this->Session->read('CC_centro');

	if(!empty($this->data['cugp01vialidad']['cod_vialidad']) && !empty($this->data['cugp01vialidad']['denominacion'])){

		$selet=$this->data['cugp01vialidad']['cod_vialidad'];


		$sql_re = "cod_republica=".$cod_republica."and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro_poblado."  and cod_vialidad=".$selet."";
		if($this->cugd01_vialidad->findCount($sql_re) == 0){
			$sql = "INSERT INTO cugd01_vialidad (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_vialidad, denominacion)   VALUES  ( '".$cod_republica."',  '".$estado."',  '".$municipio."',  '".$parroquia."', '".$centro_poblado."',  '".$this->data['cugp01vialidad']['cod_vialidad']."', '".$this->data['cugp01vialidad']['denominacion']."')";
			$this->cugd01_vialidad->execute($sql);
			$this->set('Message_existe','registro guardado');
		}else{
			$sql ="UPDATE cugd01_vialidad SET  denominacion = '".$this->data['cugp01vialidad']['denominacion']."'   where cod_republica=".$cod_republica." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia."  and cod_centro=".$centro_poblado."  and cod_vialidad=".$selet."";
			$this->cugd01_vialidad->execute($sql);
			$this->set('Message_existe','registro modificado con exito');
		}
	}else{
		$this->set('errorMessage','debe completar los campos');
	}

	$this->agregar_ventana();
	$this->render('agregar_ventana');

}




}//fin class



?>
