<?php
/*
 * Creado el 09/10/2008 a las 12:41:23 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Shp500AseoDomiciliarioController extends AppController{
	var $uses = array('v_shd500_aseo_domiciliario','shd500_aseo_domiciliario','shd500_aseo_clasificacion','shd002_cobradores','shd200_vehiculos_clasificacion','shd200_vehiculos_marcas','shd200_vehiculos_modelos','shd200_vehiculos_colores','shd200_vehiculos_tipos','shd200_vehiculos_usos','shd200_vehiculos_clases','cnmd06_profesiones','cugd01_vereda','cugd01_vialidad','v_shd001_registro_contribuyentes','shd200_vehiculos_usos', 'ccfd04_cierre_mes','cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados',
					  'shd001_registro_contribuyentes','shd200_vehiculos','v_shd200_vehiculos');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp500_aseo_domiciliario";


 function checkSession(){
 	if (!$this->Session->check('Usuario')){
 		$this->redirect('/salir/');
		exit();
	}else{
		//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
		//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
		$this->requestAction('/usuarios/actualizar_user');
	}
 }//fin checkSession



 function beforeFilter(){
 	$this->checkSession();
 }//fin before filter

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

function index($var=null, $var_cont=null){
	$this->layout = "ajax";
	$this->set("rif_cedula", $this->shd002_cobradores->generateList($this->SQLCA(), "nombre_razon ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));
	$cla 	= $this->shd500_aseo_clasificacion->generateList($this->SQLCA(), 'cod_clasificacion ASC', null, '{n}.shd500_aseo_clasificacion.cod_clasificacion', '{n}.shd500_aseo_clasificacion.denominacion');
	$this->concatena($cla, 'cla');
}//fin index

function codigo_rif($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_rif($codigo){
	$this->layout = "ajax";
	$b = $this->shd002_cobradores->findAll($this->SQLCA()." and rif_ci='".$codigo."'",array('rif_ci','nombre_razon'));
	$this->set("b",$b[0]['shd002_cobradores']['nombre_razon']);
}//fin cpcp02_denominacion

function a1($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function a2($codigo){
	$this->layout = "ajax";
	$b = $this->shd500_aseo_clasificacion->findAll($this->SQLCA()." and cod_clasificacion=$codigo",array('cod_clasificacion','denominacion','monto_mensual'));
	$this->set("b",$b[0]['shd500_aseo_clasificacion']['denominacion']);
}//fin cpcp02_denominacion

function a3($codigo){
	$this->layout = "ajax";
	$b = $this->shd500_aseo_clasificacion->findAll($this->SQLCA()." and cod_clasificacion=$codigo",array('cod_clasificacion','denominacion','monto_mensual'));
	$this->set("b",$b[0]['shd500_aseo_clasificacion']['monto_mensual']);
}//fin cpcp02_denominacion

function a4($codigo){
	$this->layout = "ajax";
	$b = $this->shd500_aseo_clasificacion->findAll($this->SQLCA()." and cod_clasificacion=$codigo",array('cod_clasificacion','denominacion','monto_mensual'));
	$this->set("b",($b[0]['shd500_aseo_clasificacion']['monto_mensual'] * 12));
}//fin cpcp02_denominacion

function buscar_constribuyente($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						//if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function seleccion_busqueda_venta($var1=null){
$this->layout="ajax";
$datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$var1."'");
	if($datos != null){
	$cod_profesion=$datos[0]["shd001_registro_contribuyentes"]["profesion"];
	$cod_pais=$datos[0]["shd001_registro_contribuyentes"]["cod_pais"];
	$cod_estado=$datos[0]["shd001_registro_contribuyentes"]["cod_estado"];
	$cod_municipio=$datos[0]["shd001_registro_contribuyentes"]["cod_municipio"];
	$cod_parroquia=$datos[0]["shd001_registro_contribuyentes"]["cod_parroquia"];
	$cod_centro_poblado=$datos[0]["shd001_registro_contribuyentes"]["cod_centro_poblado"];
	$cod_calle_avenida=$datos[0]["shd001_registro_contribuyentes"]["cod_calle_avenida"];
	$cod_vereda_edificio=$datos[0]["shd001_registro_contribuyentes"]["cod_vereda_edificio"];
	$pais=$this->cugd01_republica->findAll('cod_republica='.$cod_pais);
	$estados=$this->cugd01_estados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado);
	$municipios=$this->cugd01_municipios->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio);
	$parroquias=$this->cugd01_parroquias->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia);
	$centros=$this->cugd01_centropoblados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado);
	$vialidad=$this->cugd01_vialidad->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida);
	$vereda=$this->cugd01_vereda->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida.' and cod_vereda='.$cod_vereda_edificio);
	$profesiones=$this->cnmd06_profesiones->findAll('cod_profesion='.$cod_profesion);
	$this->set('profesion',$profesiones);
	$this->set('pais',$pais);
	$this->set('estados',$estados);
	$this->set('municipios',$municipios);
	$this->set('parroquias',$parroquias);
	$this->set('centros',$centros);
	$this->set('vialidad',$vialidad);
	$this->set('vereda',$vereda);
	$this->set('datos',$datos);



$this->set('datos',$datos);
$resul = javascript_encode($datos[0]['shd001_registro_contribuyentes']['razon_social_nombres'], 1);
   echo'<script>';
		  echo"document.getElementById('deno_rif').value = \"$resul\"; ";
		  echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
   echo'</script>';
					/*echo "<script>";
					    echo "document.getElementById('deno_rif').value='".$datos[0]['shd001_registro_contribuyentes']['razon_social_nombres']."';   ";
					    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
					echo "</script>";*/
}else{
	$vacio='';
					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$vacio."';   ";
						echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
					echo "</script>";
}

}//fin function


function guardar(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$rif_cedula    				= $this->data['shp500_aseo_domiciliario']['rif_constribuyente'];
	$fecha_registro     		= $this->data['shp500_aseo_domiciliario']['fecha_registro'];
	$cod_clasificacion     		= $this->data['shp500_aseo_domiciliario']['cod_clasificacion'];
	$frecuencia_pago    		= $this->data['shp500_aseo_domiciliario']['frecuencia_pago'];
	$monto_mensual     			= $this->data['shp500_aseo_domiciliario']['monto_mensual'];
	$pago_todo    				= $this->data['shp500_aseo_domiciliario']['pago_todo'];
	$suspendido     			= $this->data['shp500_aseo_domiciliario']['suspendido'];
	$rif_ci_cobrador    		= $this->data['shp500_aseo_domiciliario']['rif_cedula'];
	$ultimo_ano_facturado     		= 0;
	$ultimo_mes_facturado    		= 0;
	$monto_mensual=$this->Formato1($monto_mensual);


$SQL_INSERT ="INSERT INTO shd500_aseo_domiciliario (cod_presi, cod_entidad ,cod_tipo_inst ,cod_inst ,cod_dep ,rif_cedula, cod_clasificacion,
  			frecuencia_pago ,fecha_registro ,monto_mensual ,pago_todo ,suspendido ,rif_ci_cobrador ,ultimo_ano_facturado, ultimo_mes_facturado)";

$SQL_INSERT .=" VALUES ($cod_presi, $cod_entidad ,$cod_tipo_inst ,$cod_inst ,$cod_dep ,'".$rif_cedula."', $cod_clasificacion,
  			$frecuencia_pago ,'".$fecha_registro."' ,$monto_mensual ,$pago_todo ,$suspendido ,'".$rif_ci_cobrador."' ,$ultimo_ano_facturado,$ultimo_mes_facturado)";

$sw = $this->shd500_aseo_domiciliario->execute($SQL_INSERT);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->index();
		$this->render("index");
}

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

function consultar($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd500_aseo_domiciliario->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 //$this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
            // echo"hola";
 }else{
 	$pagina=1;
 			$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd500_aseo_domiciliario->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
}//fin function consultar2

}

function modificar($rif_cedula=null,$pagina=null){
	$this->layout = "ajax";
	$datos=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'",null,'rif_cedula ASC',null,null,null);
	$this->set("rif_cedula", $this->shd002_cobradores->generateList($this->SQLCA(), "nombre_razon ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));
	$cla 	= $this->shd500_aseo_clasificacion->generateList($this->SQLCA(), 'cod_clasificacion ASC', null, '{n}.shd500_aseo_clasificacion.cod_clasificacion', '{n}.shd500_aseo_clasificacion.denominacion');
	$this->concatena($cla, 'cla');
	$this->set('datos',$datos);
	$this->set('pagina',$pagina);
}

function guardar_modificar($rif_cedula=null,$pagina=null){
	$this->layout = "ajax";
	//pr($this->data);
	$fecha_registro     		= $this->data['shp500_aseo_domiciliario']['fecha_registro'];
	$cod_clasificacion     		= $this->data['shp500_aseo_domiciliario']['cod_clasificacion'];
	$frecuencia_pago    		= $this->data['shp500_aseo_domiciliario']['frecuencia_pago'];
	$monto_mensual     			= $this->data['shp500_aseo_domiciliario']['monto_mensual'];
	$pago_todo    				= $this->data['shp500_aseo_domiciliario']['pago_todo'];
	$suspendido     			= $this->data['shp500_aseo_domiciliario']['suspendido'];
	$rif_ci_cobrador    		= $this->data['shp500_aseo_domiciliario']['rif_cedula'];
	$ultimo_ano_facturado     	= 0;
	$ultimo_mes_facturado    	= 0;
	$monto_mensual=$this->Formato1($monto_mensual);
	$cond=$this->SQLCA();
	$guardar="update shd500_aseo_domiciliario set fecha_registro='".$fecha_registro."', cod_clasificacion='".$cod_clasificacion."',
  			frecuencia_pago=$frecuencia_pago, monto_mensual=$monto_mensual, pago_todo=$pago_todo, suspendido=$suspendido, rif_ci_cobrador='".$rif_ci_cobrador."' where rif_cedula='".$rif_cedula."' and $cond";
	$sw = $this->shd500_aseo_domiciliario->execute($guardar);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->consultar($pagina);
		$this->render("consultar");
}

function eliminar($rif=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();
 	$cond="rif_cedula='".$rif."' and $ca";
 	//echo $cond;
 	$this->shd500_aseo_domiciliario->execute("DELETE FROM shd500_aseo_domiciliario  WHERE ".$cond);
 	$y=$this->shd500_aseo_domiciliario->findCount($this->SQLCA());
 	if($pagina>$y){
 		$pagina=$pagina-1;
 	}
 	if($y!=0){
	  	 $this->set('Message_existe', 'Registro Eliminado con exito.');
      	 $this->consultar($pagina);//si es el primero solamente
      $this->render("consultar");

		}else if($y==0){
			$this->set('Message_existe', 'Registro Eliminado con exito.');
			$this->index();
      		$this->render("index");
		}//fin if
}

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);//echo "(rif_cedula LIKE '%$var2%' or razon_social_nombres LIKE '%$var2%')";
					$Tfilas=$this->v_shd500_aseo_domiciliario->findCount($this->SQLCA()." and (rif_cedula LIKE '%$var2%' or quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA()." and (rif_cedula LIKE '%$var2%' or quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "(rif_cedula LIKE '%$var22%' or razon_social_nombres LIKE '%$var22%')";
						$Tfilas=$this->v_shd500_aseo_domiciliario->findCount($this->SQLCA()." and (rif_cedula LIKE '%$var22%' or quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA()." and (rif_cedula LIKE '%$var22%' or quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function

function consulta2($numero=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$numero."' and ".$this->SQLCA();
    $veri=$this->v_shd500_aseo_domiciliario->findCount($c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd500_aseo_domiciliario->findAll($c);
      	$this->set('datos',$datacpcp01);
      	$this->set('rif_cedula',$numero);
      }else{
	  			$this->index();
				$this->render("index");
          	 }
}//fin function consultar2

function consulta3($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd500_aseo_domiciliario->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 //$this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
            // echo"hola";
 }else{
 	$pagina=1;
 			$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd500_aseo_domiciliario->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
}//fin function consultar2

}
function vacio ($msj,$tipo) {
   $this->layout="ajax";
   $this->set($tipo,$msj);

}//fin funcion vacio

function buscar3($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista3($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);//echo "(rif_cedula LIKE '%$var2%' or razon_social_nombres LIKE '%$var2%')";
					$Tfilas=$this->v_shd500_aseo_domiciliario->findCount($this->SQLCA()." and (rif_cedula LIKE '%$var2%' or quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA()." and (rif_cedula LIKE '%$var2%' or quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "(rif_cedula LIKE '%$var22%' or razon_social_nombres LIKE '%$var22%')";
						$Tfilas=$this->v_shd500_aseo_domiciliario->findCount($this->SQLCA()." and (rif_cedula LIKE '%$var22%' or quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA()." and (rif_cedula LIKE '%$var22%' or quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function

function consulta4($numero=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$numero."' and ".$this->SQLCA();
    $veri=$this->v_shd500_aseo_domiciliario->findCount($c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd500_aseo_domiciliario->findAll($c);
      	$this->set('datos',$datacpcp01);
      	$this->set('rif_cedula',$numero);
      }else{
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
}//fin function consultar2



}