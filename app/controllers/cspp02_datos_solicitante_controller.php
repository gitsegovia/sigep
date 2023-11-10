<?php

 class Cspp02DatosSolicitanteController extends AppController {

   var $uses = array('arrd04','arrd05','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados','cugd01_vialidad','cspd02_datos_solicitante','v_cspd02_datos_solicitante','v_usuarios','cspd03_planteamientos','v_cspd03_planteamientos');
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
 }

 function verifica_SS($i){
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
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



function index($var=null){///////////////<<--INDEX
	 $this->layout = "ajax";
	 $this->Session->delete('cspp02_direccion');

}//fin index

function index2($var=null){///////////////<<--INDEX2
	 $this->layout = "ajax";


	$cond =" cod_republica=".$this->Session->read('SScodpresi');
	$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($lista, 'estado_select');

	$this->Session->delete('cspp02_direccion');


}//fin index


function cargar_select($procede,$var=null){///////////////<<--INDEX2
	 $this->layout = "ajax";

if($procede=='estado'){

	$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$var;
	$lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	$this->concatena($lista, 'select_datos');
	$this->set('siguiente','municipio');
	$this->set('recarga_td','parroquia_td');
	$_SESSION['cspp02_direccion'][0]=$var;


}elseif($procede=='municipio'){

	$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$_SESSION['cspp02_direccion'][0]." and cod_municipio=".$var;
	$lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	$this->concatena($lista, 'select_datos');

	$_SESSION['cspp02_direccion'][1]=$var;
	$this->set('siguiente','parroquia');
	$this->set('recarga_td','centropoblado_td');


}elseif($procede=='parroquia'){
	$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$_SESSION['cspp02_direccion'][0]." and cod_municipio=".$_SESSION['cspp02_direccion'][1]." and cod_parroquia=".$var;
	$lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	$this->concatena($lista, 'select_datos');

	$_SESSION['cspp02_direccion'][2]=$var;
	$this->set('siguiente','centropoblado');
	$this->set('recarga_td','calle_td');

}elseif($procede=='centropoblado'){
	$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$_SESSION['cspp02_direccion'][0]." and cod_municipio=".$_SESSION['cspp02_direccion'][1]." and cod_parroquia=".$_SESSION['cspp02_direccion'][2]." and cod_centro=".$var;
	$lista=  $this->cugd01_vialidad->generateList($cond, 'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	$this->concatena($lista, 'select_datos');


	$this->set('siguiente','ultimo');

}


}//fin cargar_select

function guardar(){///////////////<<--GUARDAR
	 $this->layout = "ajax";



    if($this->v_cspd02_datos_solicitante->findCount($this->SQLCA()." and rif_cedula='".$this->data['cspp02_datos_solicitante']['cedula']."'")==0){

	$fecha=date("Y/m/d");

    $usuario = $this->v_usuarios->findAll($this->SQLCA()." and username='".$this->Session->read('nom_usuario')."'");
	//$sql="BEGIN; INSERT INTO cspd02_datos_solicitante (rif_cedula,nombre_solicitante,cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,cod_vialidad,complemento_direccion,fecha_registro,cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,usuario,cedula,nombre_funcionario) ";
    $sql="INSERT INTO cspd02_datos_solicitante";
    $sql.=" VALUES ('".$this->data['cspp02_datos_solicitante']['cedula']."','".$this->data['cspp02_datos_solicitante']['nombre']."',".$this->data['cspp02_datos_solicitante']['estado'].",".$this->data['cspp02_datos_solicitante']['municipio'].",".$this->data['cspp02_datos_solicitante']['parroquia'].",";
	$sql.= $this->data['cspp02_datos_solicitante']['centropoblado'].",".$this->data['cspp02_datos_solicitante']['calle'].",'".$this->data['cspp02_datos_solicitante']['direccion']."','".$this->data['cspp02_datos_solicitante']['telefono']."','".$this->Cfecha($fecha, 'A-M-D')."',".$this->verifica_SS(1).",".$this->verifica_SS(2).",".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$this->verifica_SS(5);
	$sql.= ",'".$this->Session->read('nom_usuario')."','".$usuario[0]['v_usuarios']['cedula_identidad']."','".$usuario[0]['v_usuarios']['funcionario']."')";
	$rs=$this->cspd02_datos_solicitante->execute($sql);

	if($rs>1){
           	        $this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
    				$datos=$this->v_cspd02_datos_solicitante->findAll($this->SQLCA()." and rif_cedula='".$this->data['cspp02_datos_solicitante']['cedula']."'", null, null, null, null, null);
					$this->set('datos',$datos);
					$this->set('entidad_federal',$this->Session->read('entidad_federal'));
					$this->set('dependencia',$this->Session->read('dependencia'));
					$this->set('bien',true);
					$this->set('sololectura',"readonly='readonly'");



               }else{
           	        $this->set("errorMessage","Los Datos No Fuerón Guardados");
                }
     }else{
	    	$this->set("errorMessage","La CÉdula ya se encuentra registrada");

     		$datos[0]['v_cspd02_datos_solicitante']['nombre_solicitante']=$this->data['cspp02_datos_solicitante']['nombre'];
     		$datos[0]['v_cspd02_datos_solicitante']['rif_cedula']=$this->data['cspp02_datos_solicitante']['cedula'];
     		$datos[0]['v_cspd02_datos_solicitante']['cod_estado']=$this->data['cspp02_datos_solicitante']['estado'];
     		$datos[0]['v_cspd02_datos_solicitante']['cod_municipio']=$this->data['cspp02_datos_solicitante']['municipio'];
     		$datos[0]['v_cspd02_datos_solicitante']['cod_parroquia']=$this->data['cspp02_datos_solicitante']['parroquia'];
     		$datos[0]['v_cspd02_datos_solicitante']['cod_centro_poblado']=$this->data['cspp02_datos_solicitante']['centropoblado'];
     		$datos[0]['v_cspd02_datos_solicitante']['cod_vialidad']=$this->data['cspp02_datos_solicitante']['calle'];
			$datos[0]['v_cspd02_datos_solicitante']['complemento_direccion']=$this->data['cspp02_datos_solicitante']['direccion'];
			$datos[0]['v_cspd02_datos_solicitante']['telefono']=$this->data['cspp02_datos_solicitante']['telefono'];
     		$this->set('datos',$datos);
			$this->set('bien',false);

	        }//coun

					//datos de los select de direccion
					$cond =" cod_republica=".$this->Session->read('SScodpresi');
					$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
					$this->concatena($lista, 'estado_select');

					$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$datos[0]['v_cspd02_datos_solicitante']['cod_estado'];
					$lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
					$this->concatena($lista, 'municipio_select');

					$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$datos[0]['v_cspd02_datos_solicitante']['cod_estado']." and cod_municipio=".$datos[0]['v_cspd02_datos_solicitante']['cod_municipio'];
					$lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
					$this->concatena($lista, 'parroquia_select');

					$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$datos[0]['v_cspd02_datos_solicitante']['cod_estado']." and cod_municipio=".$datos[0]['v_cspd02_datos_solicitante']['cod_municipio']." and cod_parroquia=".$datos[0]['v_cspd02_datos_solicitante']['cod_parroquia'];
					$lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
					$this->concatena($lista, 'centropoblado_select');

					$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$datos[0]['v_cspd02_datos_solicitante']['cod_estado']." and cod_municipio=".$datos[0]['v_cspd02_datos_solicitante']['cod_municipio']." and cod_parroquia=".$datos[0]['v_cspd02_datos_solicitante']['cod_parroquia']." and cod_centro=".$datos[0]['v_cspd02_datos_solicitante']['cod_centro_poblado'];
					$lista=  $this->cugd01_vialidad->generateList($cond, 'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
					$this->concatena($lista, 'calle_select');

}//FIN GUARDAR



function consultar($pagina=null,$cedula=null) {
	$this->layout="ajax";

		$Tfilas=$this->v_cspd02_datos_solicitante->findCount($this->SQLCA()." and rif_cedula='".$cedula."'");

        if($Tfilas!=0){
        	$datos=$this->v_cspd02_datos_solicitante->findAll($this->SQLCA()." and rif_cedula='".$cedula."'",null,"rif_cedula ASC",1,$pagina,null);

            $this->set('datos',$datos);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('pagina',$pagina);
			$this->set('Tfilas',$Tfilas);


			$conditions = "cod_presi=".$datos[0]['v_cspd02_datos_solicitante']['cod_presi']."  and    ";
            $conditions .= "cod_entidad=".$datos[0]['v_cspd02_datos_solicitante']['cod_entidad']."  and  ";
            $conditions .= "cod_tipo_inst=".$datos[0]['v_cspd02_datos_solicitante']['cod_tipo_inst']."  and ";
            $conditions .= "cod_inst=".$datos[0]['v_cspd02_datos_solicitante']['cod_inst']."  ";


			$entidad_federal = $this->arrd04->field('denominacion', $conditions);

			$conditions .= "and cod_dep=".$datos[0]['v_cspd02_datos_solicitante']['cod_dep']." ";

			$dependencia = $this->arrd05->field('denominacion', $conditions);

			$this->set('entidad_federal',$entidad_federal);
			$this->set('dependencia',$dependencia);



					//datos de los select de direccion
					$cond =" cod_republica=".$this->Session->read('SScodpresi');
					$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
					$this->concatena($lista, 'estado_select');

					$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$datos[0]['v_cspd02_datos_solicitante']['cod_estado'];
					$lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
					$this->concatena($lista, 'municipio_select');
					$_SESSION['cspp02_direccion'][0]=$datos[0]['v_cspd02_datos_solicitante']['cod_estado'];

					$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$datos[0]['v_cspd02_datos_solicitante']['cod_estado']." and cod_municipio=".$datos[0]['v_cspd02_datos_solicitante']['cod_municipio'];
					$lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
					$this->concatena($lista, 'parroquia_select');
					$_SESSION['cspp02_direccion'][1]=$datos[0]['v_cspd02_datos_solicitante']['cod_municipio'];

					$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$datos[0]['v_cspd02_datos_solicitante']['cod_estado']." and cod_municipio=".$datos[0]['v_cspd02_datos_solicitante']['cod_municipio']." and cod_parroquia=".$datos[0]['v_cspd02_datos_solicitante']['cod_parroquia'];
					$lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
					$this->concatena($lista, 'centropoblado_select');
					$_SESSION['cspp02_direccion'][2]=$datos[0]['v_cspd02_datos_solicitante']['cod_parroquia'];

					$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$datos[0]['v_cspd02_datos_solicitante']['cod_estado']." and cod_municipio=".$datos[0]['v_cspd02_datos_solicitante']['cod_municipio']." and cod_parroquia=".$datos[0]['v_cspd02_datos_solicitante']['cod_parroquia']." and cod_centro=".$datos[0]['v_cspd02_datos_solicitante']['cod_centro_poblado'];
					$lista=  $this->cugd01_vialidad->generateList($cond, 'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
					$this->concatena($lista, 'calle_select');
					$_SESSION['cspp02_direccion'][3]=$datos[0]['v_cspd02_datos_solicitante']['cod_centro_poblado'];

					$this->set('historial',$this->v_cspd03_planteamientos->findAll($this->SQLCA()." and rif_cedula='".$datos[0]['v_cspd02_datos_solicitante']['rif_cedula']."'",null,'numero_solicitud ASC'));

        }else{
	 	    $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	    $this->set('noExiste',true);
	 	    $this->index();
			$this->render("index");
		    return;
        }

 }//consultar


 function bt_nav($Tfilas,$pagina){
    if($Tfilas==1){
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
          	}else if($Tfilas==2){
          		if($pagina==2){
                   $this->set('mostrarS',false);
                   $this->set('mostrarA',true);
          		}else{
          		   $this->set('mostrarS',true);
                   $this->set('mostrarA',false);
          		}
          	}else if($Tfilas>=3){
          		if($pagina==$Tfilas){
                     $this->set('mostrarS',false);
                     $this->set('mostrarA',true);
          		}else if($pagina==1){
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',false);
          		}else{
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',true);
          		}
          	}
 }//fin navegacion


function editar($cedula){
	$this->layout="ajax";

}

function guardar_editar($cedula,$pagina,$Tfilas){
	$this->layout="ajax";


			$this->set('pagina',$pagina);
			$this->set('Tfilas',$Tfilas);


	if( $this->v_cspd02_datos_solicitante->findCount($this->SQLCA()." and rif_cedula='".$cedula."'")!=0){

		$rs=$this->v_cspd02_datos_solicitante->execute("UPDATE cspd02_datos_solicitante SET nombre_solicitante='".$this->data['cspp02_datos_solicitante']['nombre']."',cod_estado=".$this->data['cspp02_datos_solicitante']['estado'].",cod_municipio=". $this->data['cspp02_datos_solicitante']['municipio']
														.",cod_parroquia=".$this->data['cspp02_datos_solicitante']['parroquia'].",cod_centro_poblado=".$this->data['cspp02_datos_solicitante']['centropoblado'].",cod_vialidad=".$this->data['cspp02_datos_solicitante']['calle']
														.",complemento_direccion='".$this->data['cspp02_datos_solicitante']['direccion']."',telefonos='".$this->data['cspp02_datos_solicitante']['telefono']."'"
														." WHERE ".$this->SQLCA()." and rif_cedula='".$cedula."'");

                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron actualizado Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron actualizado");

                }


	}else{
		  $this->set("errorMessage","No existe este solicitante, debe ingresarlo");
		  $this->set('no_exite',true);

	 	  return;
	}


}//fin guardar editar


function eliminar($cedula,$anterior){
	$this->layout="ajax";

		if($this->v_cspd03_planteamientos->findCount($this->SQLCA()." and rif_cedula='$cedula'")==0){

	    	$rs=$this->cspd02_datos_solicitante->execute("DELETE FROM cspd02_datos_solicitante WHERE ".$this->SQLCA()." and rif_cedula='$cedula'");
    		if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
            	    $this->set('bien',true);

    		}else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");
	           	    $this->set('bien',false);

			}
		}else{
			 $this->set("errorMessage","El Dato No Fu&eacute; Eliminado, Se encuentra registrado en una solicitud");
	         $this->set('bien',false);
	}
}//eliminar


//BUSCAR DATOS

function buscar_datos(){
	$this->layout="ajax";
	$this->Session->delete('pista');

	echo "<script>$('campo_pista').focus();</script>";
}//fin function


function buscar_datos_porpista($var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='cspd02_datos_solicitante';
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(nombre_solicitante) LIKE quitar_acentos('%$var2%')) )");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(nombre_solicitante) LIKE quitar_acentos('%$var2%')) )","rif_cedula, nombre_solicitante, telefonos","rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(nombre_solicitante) LIKE quitar_acentos('%$var2%')) )");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(nombre_solicitante) LIKE quitar_acentos('%$var2%')) )","rif_cedula, nombre_solicitante, telefonos","rif_cedula ASC",50,1,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else

} //fin funcion


}//fin clase
?>
