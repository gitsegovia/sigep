<?php

 class shp700CreditoViviendaController extends AppController{
 	var $name = "shp700_credito_vivienda";
	var $uses = array('v_shd700_credito_vivienda_parentesco','cnmd06_parentesco','v_shd700_credito_vivienda','shd700_credito_vivienda','shd700_credito_vivienda_parentesco','v_shd001_registro_contribuyentes','v_shd600_solicitud_arrendamiento','shd600_solicitud_arrendamiento','v_shd400_propiedad','catd02_ficha_tipologia','catd02_ficha_datos','shd400_propiedad','shd300_propaganda','v_shd100_patente_actividades','v_shd100_patente','v_shd100_solicitud','shd100_patente','shd001_registro_contribuyentes','shd100_patente_actividades','shd100_solicitud',
                      'shd100_solicitud_actividades', 'shd002_cobradores', 'shd100_actividades', 'cnmd06_profesiones','cugd01_republica', 'cugd01_estados',
                      'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados', 'cugd01_vialidad', 'cugd01_vereda');
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



function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;
}//fin funcion SQLX


function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
         return $sql_re;
}//fin funcion SQLCA


function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;
}//fin zero



function concatena($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			$cod[$x] = $this->zero($x).' - '.$y;
		}
		$this->set($nomVar, $cod);
	}
}//fin concatena

function index($var=null){
	$this->layout = "ajax";
 	$this->data = null;
 	$this->set("rif_cedula", $this->shd002_cobradores->generateList($this->SQLCA(), "nombre_razon ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));
	$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
	$this->concatena($vivienda, 'vivienda');
	$parentesco 	= $this->cnmd06_parentesco->generateList(null, 'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion');
	$this->concatena($parentesco, 'parentesco');
	$this->Session->delete("DATOS");
}//fin index

function codigo_rif($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_rif($codigo){
	$this->layout = "ajax";
	$b = $this->shd002_cobradores->findAll("rif_ci='".$codigo."'",array('rif_ci','nombre_razon'));
	$this->set("b",$b[0]['shd002_cobradores']['nombre_razon']);


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
						$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,$pagina,null);
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

function num_ficha1($numero=null){
	$this->layout="ajax";
	$datos=$this->catd02_ficha_datos->findAll($this->SQLCA().' and cod_ficha='.$numero);
	$this->set('datos',$datos);
}
function num_ficha2($numero=null){
	$this->layout="ajax";
	$datos=$this->catd02_ficha_datos->findAll($this->SQLCA().' and cod_ficha='.$numero);
	$this->set('datos',$datos);
}
function num_ficha3($numero=null){
	$this->layout="ajax";
	$datos=$this->catd02_ficha_datos->findAll($this->SQLCA().' and cod_ficha='.$numero);
	$this->set('datos',$datos);

}
function num_ficha4($numero=null){
	$this->layout="ajax";
	$datos=$this->catd02_ficha_datos->findAll($this->SQLCA().' and cod_ficha='.$numero);
	$this->set('datos',$datos);

}
function num_ficha5($numero=null){
	$this->layout="ajax";
	$datos=$this->catd02_ficha_datos->findAll($this->SQLCA().' and cod_ficha='.$numero);
	$this->set('datos',$datos);

}

function num_fichax($numero=null){
	$this->layout="ajax";
	$datos=$this->catd02_ficha_datos->findAll($this->SQLCA().' and cod_ficha='.$numero);
	$this->set('datos',$datos);

}

function guardar(){
	$this->layout = "ajax";//pr($this->data);

	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
  	$rif_cedula 				= $this->data['shp700_credito_vivienda']['rif_constribuyente'];
  	$numero_solicitud			= $this->data['shp700_credito_vivienda']['numero_solicitud'];
  	$fecha_solicitud			= $this->data['shp700_credito_vivienda']['fecha_solicitud'];
  	$nombre_conyugue			= $this->data['shp700_credito_vivienda']['nombre_conyugue'];
  	if($nombre_conyugue==null){
  		$nombre_conyugue='0';
  	}
  	$cedula_conyugue			= $this->data['shp700_credito_vivienda']['cedula_conyugue'];
  	if($cedula_conyugue==null){
  		$cedula_conyugue='0';
  	}
  	$nombre_empresa				= $this->data['shp700_credito_vivienda']['nombre_empresa'];
  	if($nombre_empresa==null){
  		$nombre_empresa='0';
  	}
  	$tiempo_empresa				= $this->data['shp700_credito_vivienda']['tiempo_empresa'];
  	if($tiempo_empresa==null){
  		$tiempo_empresa='0';
  	}
  	$telefonos_empresas			= $this->data['shp700_credito_vivienda']['telefonos_empresas'];
  	if($telefonos_empresas==null){
  		$telefonos_empresas='0';
  	}
  	$direccion_empresa			= $this->data['shp700_credito_vivienda']['direccion_empresa'];
  	if($direccion_empresa==null){
  		$direccion_empresa='0';
  	}
  	$grupo_familiar				= $this->data['shp700_credito_vivienda']['grupo_familiar'];
  	$ingreso_mensual			= $this->Formato1($this->data['shp700_credito_vivienda']['ingreso_mensual']);
  	$vivienda_actual			= $this->data['shp700_credito_vivienda']['vivienda_actual'];
  	$tipo_vivienda				= $this->data['shp700_credito_vivienda']['tipo_vivienda'];
  	$direccion_vivienda_credito	= $this->data['shp700_credito_vivienda']['direccion_vivienda_credito'];
  	$costo_vivienda				= $this->Formato1($this->data['shp700_credito_vivienda']['costo_vivienda']);
  	$monto_cuota_inicial		= $this->Formato1($this->data['shp700_credito_vivienda']['monto_cuota_inicial']);
  	$monto_restante				= $this->Formato1($this->data['shp700_credito_vivienda']['monto_restante']);
  	$factor						= $this->Formato1($this->data['shp700_credito_vivienda']['factor']);
  	$plazo_anos					= $this->data['shp700_credito_vivienda']['plazo_anos'];
  	$numero_cuotas				= $this->data['shp700_credito_vivienda']['numero_cuotas'];
  	$monto_mensual				= $this->Formato1($this->data['shp700_credito_vivienda']['monto_mensual']);
  	$numero_contrato			= $this->data['shp700_credito_vivienda']['numero_contrato'];
  	$fecha_contrato				= $this->data['shp700_credito_vivienda']['fecha_contrato'];
  	$frecuencia_pago			= $this->data['shp700_credito_vivienda']['frecuencia_pago'];
  	$pago_todo					= $this->data['shp700_credito_vivienda']['pago_todo'];
  	$suspendido					= $this->data['shp700_credito_vivienda']['suspendido'];
  	$rif_ci_cobrador			= $this->data['shp700_credito_vivienda']['rif_ci_cobrador'];
  	$ultimo_ano_facturado		= '0';
  	$ultimo_mes_facturado		= '0';
  	$area_construccion			= $this->Formato1($this->data['shp700_credito_vivienda']['area_construccion']);
  	$area_terreno				= $this->Formato1($this->data['shp700_credito_vivienda']['area_terreno']);
  	$norte						= $this->data['shp700_credito_vivienda']['norte'];
  	$sur						= $this->data['shp700_credito_vivienda']['sur'];
  	$este						= $this->data['shp700_credito_vivienda']['este'];
  	$oeste						= $this->data['shp700_credito_vivienda']['oeste'];
  	$tasa_interes				= $this->Formato1($this->data['shp700_credito_vivienda']['tasa_interes']);
  	$fecha_entrega_contrato		= $this->data['shp700_credito_vivienda']['fecha_entrega_contrato'];


$SQL_INSERT ="INSERT INTO shd700_credito_vivienda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula,numero_solicitud,fecha_solicitud, nombre_conyugue,
  cedula_conyugue, nombre_empresa, tiempo_empresa, telefonos_empresas, direccion_empresa, grupo_familiar,
  ingreso_mensual, vivienda_actual, tipo_vivienda, direccion_vivienda_credito, costo_vivienda, monto_cuota_inicial,
  monto_restante, factor, plazo_anos, numero_cuotas, monto_mensual, numero_contrato, fecha_contrato, frecuencia_pago,
  pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado, area_construccion, area_terreno,
  norte, sur, este, oeste, tasa_interes, fecha_entrega_contrato)";

$SQL_INSERT .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '".$rif_cedula."','".$numero_solicitud."','".$fecha_solicitud."', '".$nombre_conyugue."',
  $cedula_conyugue, '".$nombre_empresa."', '".$tiempo_empresa."', '".$telefonos_empresas."', '".$direccion_empresa."', $grupo_familiar,
  $ingreso_mensual, $vivienda_actual, '".$tipo_vivienda."', '".$direccion_vivienda_credito."', $costo_vivienda, $monto_cuota_inicial,
  $monto_restante, $factor, $plazo_anos, $numero_cuotas, $monto_mensual, '".$numero_contrato."', '".$fecha_contrato."', $frecuencia_pago,
  $pago_todo, $suspendido, '".$rif_ci_cobrador."', $ultimo_ano_facturado, $ultimo_mes_facturado, $area_construccion, $area_terreno,
  '".$norte."', '".$sur."', '".$este."', '".$oeste."', $tasa_interes, '".$fecha_entrega_contrato."')";

$sw1 = $this->shd700_credito_vivienda->execute($SQL_INSERT);

if($sw1>1){
	$cont  = $_SESSION["CUENTA"];

			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["DATOS"][$i]["activa"]==1){
                       $cod_parentesco     = $_SESSION["DATOS"][$i]["cod_parentesco"];
                       $nombre_parentesco     = $_SESSION["DATOS"][$i]["nombre_parentesco"];
                       $sexo_parentesco     = $_SESSION["DATOS"][$i]["sexo_parentesco"];
                       $fecha_nacimiento_parentesco     = $_SESSION["DATOS"][$i]["fecha_nacimiento_parentesco"];
					   $sql ="INSERT INTO shd700_credito_vivienda_parentesco (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,numero_solicitud, rif_cedula, cod_parentesco,nombre_apellido,sexo,fecha_nacimiento)";
					   $sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."','".$numero_solicitud."','".$rif_cedula."', '".$cod_parentesco."','".$nombre_parentesco."','".$sexo_parentesco."','".$fecha_nacimiento_parentesco."');";
					   $sw = $this->shd100_solicitud_actividades->execute($sql);
					   if($sw>1){}else{break;}
			    }//fin if
			 }//fin for
}else{
	$sw=0;
}

		if($sw1>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->index();
		$this->render("index");
}

function consultar($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
 		$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
		$this->concatena($vivienda, 'vivienda');
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd700_credito_vivienda->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd700_credito_vivienda->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);//pr($datos);
          	 foreach($datos as $row){
          	 	$rif  = $row['v_shd700_credito_vivienda']['rif_cedula'];
          	 	$num  = $row['v_shd700_credito_vivienda']['numero_solicitud'];
          	 }
          	 $Tfilas2=$this->v_shd700_credito_vivienda_parentesco->findCount($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$num."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd700_credito_vivienda_parentesco->findAll($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$num."'",null,'deno_parentesco ASC');
          	 	$this->set('accion',$accion);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
            // echo"hola";
 }else{
 	$pagina=1;
 			$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd700_credito_vivienda->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd700_credito_vivienda->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 foreach($datos as $row){
          	 	$rif  = $row['v_shd700_credito_vivienda']['rif_cedula'];
          	 	$num  = $row['v_shd700_credito_vivienda']['numero_solicitud'];
          	 }
          	 $Tfilas2=$this->v_shd700_credito_vivienda_parentesco->findCount($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$num."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd700_credito_vivienda_parentesco->findAll($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$num."'",null,'deno_parentesco ASC');
          	 	$this->set('accion',$accion);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
}//fin function consultar2
}//fin class


function modificar($rif_cedula=null,$numero_solicitud=null,$pagina=null){
	$this->layout = "ajax";
	$datos=$this->v_shd700_credito_vivienda->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'",null,'rif_cedula ASC',null,null,null);
	$this->set('datos',$datos);
	foreach($datos as $row){
          	 	$rif  = $row['v_shd700_credito_vivienda']['rif_cedula'];
          	 }
          	 $Tfilas2=$this->v_shd700_credito_vivienda_parentesco->findCount($this->SQLCA()." and rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd700_credito_vivienda_parentesco->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'",null,'deno_parentesco ASC');
          	 	$this->set('accion',$accion);
          	 }
	$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
	$this->concatena($vivienda, 'vivienda');
    $this->set('pagina',$pagina);
    $this->set("rif_cedula", $this->shd002_cobradores->generateList($this->SQLCA(), "nombre_razon ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));
	$parentesco 	= $this->cnmd06_parentesco->generateList(null, 'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion');
	$this->concatena($parentesco, 'parentesco');
}

function guardar_modificar($rif_cedula=null,$numero_solicitud=null,$pagina=null){
	$this->layout = "ajax";//pr($this->data);
	$fecha_solicitud			= $this->data['shp700_credito_vivienda']['fecha_solicitud'];
  	$nombre_conyugue			= $this->data['shp700_credito_vivienda']['nombre_conyugue'];
  	if($nombre_conyugue==null){
  		$nombre_conyugue='0';
  	}
  	$cedula_conyugue			= $this->data['shp700_credito_vivienda']['cedula_conyugue'];
  	if($cedula_conyugue==null){
  		$cedula_conyugue='0';
  	}
  	$nombre_empresa				= $this->data['shp700_credito_vivienda']['nombre_empresa'];
  	if($nombre_empresa==null){
  		$nombre_empresa='0';
  	}
  	$tiempo_empresa				= $this->data['shp700_credito_vivienda']['tiempo_empresa'];
  	if($tiempo_empresa==null){
  		$tiempo_empresa='0';
  	}
  	$telefonos_empresas			= $this->data['shp700_credito_vivienda']['telefonos_empresas'];
  	if($telefonos_empresas==null){
  		$telefonos_empresas='0';
  	}
  	$direccion_empresa			= $this->data['shp700_credito_vivienda']['direccion_empresa'];
  	if($direccion_empresa==null){
  		$direccion_empresa='0';
  	}
  	$grupo_familiar				= $this->data['shp700_credito_vivienda']['grupo_familiar'];
  	$ingreso_mensual			= $this->Formato1($this->data['shp700_credito_vivienda']['ingreso_mensual']);
  	$vivienda_actual			= $this->data['shp700_credito_vivienda']['vivienda_actual'];
  	$tipo_vivienda				= $this->data['shp700_credito_vivienda']['tipo_vivienda'];
  	$direccion_vivienda_credito	= $this->data['shp700_credito_vivienda']['direccion_vivienda_credito'];
  	$costo_vivienda				= $this->Formato1($this->data['shp700_credito_vivienda']['costo_vivienda']);
  	$monto_cuota_inicial		= $this->Formato1($this->data['shp700_credito_vivienda']['monto_cuota_inicial']);
  	$monto_restante				= $this->Formato1($this->data['shp700_credito_vivienda']['monto_restante']);
//  	$factor						= $this->Formato1($this->data['shp700_credito_vivienda']['factor']);
 	$factor						= $this->data['shp700_credito_vivienda']['factor'];
  	$plazo_anos					= $this->data['shp700_credito_vivienda']['plazo_anos'];
  	$numero_cuotas				= $this->data['shp700_credito_vivienda']['numero_cuotas'];
  	$monto_mensual				= $this->Formato1($this->data['shp700_credito_vivienda']['monto_mensual']);
  	$numero_contrato			= $this->data['shp700_credito_vivienda']['numero_contrato'];
  	$fecha_contrato				= $this->data['shp700_credito_vivienda']['fecha_contrato'];
  	$frecuencia_pago			= $this->data['shp700_credito_vivienda']['frecuencia_pago'];
  	$pago_todo					= $this->data['shp700_credito_vivienda']['pago_todo'];
  	$suspendido					= $this->data['shp700_credito_vivienda']['suspendido'];
  	$rif_ci_cobrador			= $this->data['shp700_credito_vivienda']['rif_ci_cobrador'];
  	$area_construccion			= $this->Formato1($this->data['shp700_credito_vivienda']['area_construccion']);
  	$area_terreno				= $this->Formato1($this->data['shp700_credito_vivienda']['area_terreno']);
  	$norte						= $this->data['shp700_credito_vivienda']['norte'];
  	$sur						= $this->data['shp700_credito_vivienda']['sur'];
  	$este						= $this->data['shp700_credito_vivienda']['este'];
  	$oeste						= $this->data['shp700_credito_vivienda']['oeste'];
  	$tasa_interes				= $this->Formato1($this->data['shp700_credito_vivienda']['tasa_interes']);
  	$fecha_entrega_contrato		= $this->data['shp700_credito_vivienda']['fecha_entrega_contrato'];
	$cond=$this->SQLCA();
	$guardar="update shd700_credito_vivienda set fecha_solicitud='".$fecha_solicitud."', nombre_conyugue='".$nombre_conyugue."',
  	cedula_conyugue=$cedula_conyugue, nombre_empresa='".$nombre_empresa."', tiempo_empresa='".$tiempo_empresa."', telefonos_empresas='".$telefonos_empresas."', direccion_empresa='".$direccion_empresa."', grupo_familiar=$grupo_familiar,
  	ingreso_mensual=$ingreso_mensual, vivienda_actual=$vivienda_actual, tipo_vivienda=$tipo_vivienda, direccion_vivienda_credito='".$direccion_vivienda_credito."', costo_vivienda=$costo_vivienda, monto_cuota_inicial=$monto_cuota_inicial,
  	monto_restante=$monto_restante, factor=$factor, plazo_anos=$plazo_anos, numero_cuotas=$numero_cuotas, monto_mensual=$monto_mensual, numero_contrato='".$numero_contrato."', fecha_contrato='".$fecha_contrato."', frecuencia_pago=$frecuencia_pago,
  	pago_todo=$pago_todo, rif_ci_cobrador='".$rif_ci_cobrador."', area_construccion=$area_construccion, area_terreno=$area_terreno,
  	norte='".$norte."', sur='".$sur."', este='".$este."', oeste='".$oeste."', tasa_interes=$tasa_interes, fecha_entrega_contrato='".$fecha_entrega_contrato."' where rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."' and $cond";
	//echo $guardar;
	$sw = $this->shd700_credito_vivienda->execute($guardar);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->consultar($pagina);
		$this->render("consultar");
}
function eliminar($rif_cedula=null,$numero_solicitud=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();
 	$cond="rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."' and $ca";
 	//echo $cond;
 	$this->shd700_credito_vivienda->execute("DELETE FROM shd700_credito_vivienda  WHERE ".$cond);
 	$y=$this->shd700_credito_vivienda->findCount($this->SQLCA());
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

function agregar_grilla(){

$this->layout = "ajax";
   	$cod_parentesco    = $this->data['shp700_credito_vivienda']['cod_parentesco'];
   	$nombre_parentesco       = $this->data['shp700_credito_vivienda']['nombre_parentesco'];
   	$sexo_parentesco    = $this->data['shp700_credito_vivienda']['sexo_parentesco'];
   	$fecha_nacimiento_parentesco = $this->data['shp700_credito_vivienda']['fecha_nacimiento_parentesco'];
	$deno=$this->cnmd06_parentesco->findAll('cod_parentesco='.$cod_parentesco);

			if(!isset($_SESSION["DATOS"])){
	              $_SESSION["CUENTA"] = 1;
	              $cont               = $_SESSION["CUENTA"];
	              $_SESSION["DATOS"][$cont]["cod_parentesco"]    = $cod_parentesco;
	              $_SESSION["DATOS"][$cont]["deno_parentesco"]    = $deno[0]['cnmd06_parentesco']['denominacion'];
	              $_SESSION["DATOS"][$cont]["nombre_parentesco"]       = $nombre_parentesco;
	              $_SESSION["DATOS"][$cont]["sexo_parentesco"]    = $sexo_parentesco;
	              $_SESSION["DATOS"][$cont]["fecha_nacimiento_parentesco"] = $fecha_nacimiento_parentesco;
	              $_SESSION["DATOS"][$cont]["activa"]           = 1;
	              $_SESSION["DATOS"][$cont]["id"]               = $cont;

	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{

                  $cont  = $_SESSION["CUENTA"];
                  $marca = 0;

		              for($i=1; $i<=$cont; $i++){
		                   if($cod_parentesco==$_SESSION["DATOS"][$i]["cod_parentesco"]  &&  $_SESSION["DATOS"][$i]["activa"]==1){
                               $marca=1;
		                   }//fin if
		              }//fin for
	                 if($marca==1){
                           $this->set("errorMessage", "EL REGISTRO YA EXISTE");
	                 }else{
                              $cont = $_SESSION["CUENTA"];  $cont++; $_SESSION["CUENTA"] = $cont;
                              $_SESSION["DATOS"][$cont]["cod_parentesco"]    = $cod_parentesco;
                              $_SESSION["DATOS"][$cont]["deno_parentesco"]    = $deno[0]['cnmd06_parentesco']['denominacion'];
				              $_SESSION["DATOS"][$cont]["nombre_parentesco"]       = $nombre_parentesco;
				              $_SESSION["DATOS"][$cont]["sexo_parentesco"]    = $sexo_parentesco;
				              $_SESSION["DATOS"][$cont]["fecha_nacimiento_parentesco"] = $fecha_nacimiento_parentesco;
				              $_SESSION["DATOS"][$cont]["activa"]           = 1;
				              $_SESSION["DATOS"][$cont]["id"]               = $cont;
                           $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	                 }//fin else
			}//fin else






echo'<script>';
       echo" document.getElementById('cod_parentesco').value     = ''; ";
       echo" document.getElementById('nombre_parentesco').value           = ''; ";
       echo" document.getElementById('fecha_nacimiento_parentesco').value           = ''; ";
echo'</script>';




$this->set("accion", $_SESSION["DATOS"]);


}//fin function

function eliminar_grilla($var1=null){

$this->layout = "ajax";
$_SESSION["DATOS"][$var1]["activa"] = 0;
$this->set("errorMessage", "EL REGISTRO FUE ELIMINADO");

$cont  = $_SESSION["CUENTA"];
$marca = 0;

 for($i=1; $i<=$cont; $i++){
    if($_SESSION["DATOS"][$i]["activa"]==1){
       $marca++;
   }//fin if
 }//fin for

$this->render("funcion");


}//fin function


function funcion($var=null){$this->layout = "ajax";}//fin index

function eliminar_grilla_m($rif=null,$numero_solicitud=null,$cod_parentesco=null){
	$this->layout = "ajax";
	$ca=$this->SQLCA();
 	$cond="rif_cedula='".$rif."' and cod_parentesco=".$cod_parentesco." and numero_solicitud='".$numero_solicitud."' and $ca";
 	$this->shd700_credito_vivienda_parentesco->execute("DELETE FROM shd700_credito_vivienda_parentesco  WHERE ".$cond);
 	$Tfilas2=$this->v_shd700_credito_vivienda_parentesco->findCount($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$numero_solicitud."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd700_credito_vivienda_parentesco->findAll($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$numero_solicitud."'",null,'deno_parentesco ASC');
          	 	$this->set('accion',$accion);
          	 }
}

function agregar_grilla_m($rif_cedula=null,$numero_solicitud=null){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$cod_parentesco    			= $this->data['shp700_credito_vivienda']['cod_parentesco'];
   	$nombre_parentesco       	= $this->data['shp700_credito_vivienda']['nombre_parentesco'];
   	$sexo_parentesco   	 		= $this->data['shp700_credito_vivienda']['sexo_parentesco'];
   	$fecha_nacimiento_parentesco= $this->data['shp700_credito_vivienda']['fecha_nacimiento_parentesco'];
	$sql ="INSERT INTO shd700_credito_vivienda_parentesco (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula,numero_solicitud,cod_parentesco,nombre_apellido,sexo,fecha_nacimiento)";
	$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$rif_cedula."','".$numero_solicitud."','".$cod_parentesco."','".$nombre_parentesco."','".$sexo_parentesco."','".$fecha_nacimiento_parentesco."');";
	$sw = $this->shd100_solicitud_actividades->execute($sql);
 	$Tfilas2=$this->v_shd700_credito_vivienda_parentesco->findCount($this->SQLCA()." and rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd700_credito_vivienda_parentesco->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'",null,'deno_parentesco ASC');
          	 	$this->set('accion',$accion);
          	 }
          	 echo'<script>';
       echo" document.getElementById('cod_parentesco').value     = ''; ";
       echo" document.getElementById('nombre_parentesco').value           = ''; ";
       echo" document.getElementById('fecha_nacimiento_parentesco').value           = ''; ";
			echo'</script>';
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
					$Tfilas=$this->v_shd700_credito_vivienda->findCount("(rif_cedula LIKE '%$var2%' or nombre_razon LIKE '%$var2%') and ".$this->SQLCA());
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd700_credito_vivienda->findAll("(rif_cedula LIKE '%$var2%' or nombre_razon LIKE '%$var2%') and ".$this->SQLCA(),null,"rif_cedula ASC",50,1,null);
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
						$Tfilas=$this->v_shd700_credito_vivienda->findCount("(rif_cedula LIKE '%$var22%' or nombre_razon LIKE '%$var22%') and ".$this->SQLCA());
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd700_credito_vivienda->findAll("(rif_cedula LIKE '%$var22%' or nombre_razon LIKE '%$var22%') and ".$this->SQLCA(),null,"rif_cedula ASC",50,$pagina,null);
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

function consulta2($numero=null,$numero_solicitud=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$numero."' and numero_solicitud='".$numero_solicitud."' and ".$this->SQLCA();
    $veri=$this->v_shd700_credito_vivienda->findCount($c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd700_credito_vivienda->findAll($c);
      	$data2=$this->v_shd700_credito_vivienda_parentesco->findAll($c);
      	$this->set('datos',$datacpcp01);
      	$this->set('accion',$data2);
      	$this->set('rif_cedula',$numero);
      	$this->set('numero_solicitud',$numero_solicitud);
      	$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
		$this->concatena($vivienda, 'vivienda');
      }else{
	  			$this->index();
				$this->render("index");
          	 }
}//fin function consultar2

function consulta3($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
 		$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
		$this->concatena($vivienda, 'vivienda');
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd700_credito_vivienda->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd700_credito_vivienda->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);//pr($datos);
          	 foreach($datos as $row){
          	 	$rif  = $row['v_shd700_credito_vivienda']['rif_cedula'];
          	 	$num  = $row['v_shd700_credito_vivienda']['numero_solicitud'];
          	 }
          	 $Tfilas2=$this->v_shd700_credito_vivienda_parentesco->findCount($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$num."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd700_credito_vivienda_parentesco->findAll($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$num."'",null,'deno_parentesco ASC');
          	 	$this->set('accion',$accion);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
            // echo"hola";
 }else{
 	$pagina=1;
 			$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd700_credito_vivienda->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd700_credito_vivienda->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 foreach($datos as $row){
          	 	$rif  = $row['v_shd700_credito_vivienda']['rif_cedula'];
          	 	$num  = $row['v_shd700_credito_vivienda']['numero_solicitud'];
          	 }
          	 $Tfilas2=$this->v_shd700_credito_vivienda_parentesco->findCount($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$num."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd700_credito_vivienda_parentesco->findAll($this->SQLCA()." and rif_cedula='".$rif."' and numero_solicitud='".$num."'",null,'deno_parentesco ASC');
          	 	$this->set('accion',$accion);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
}//fin function consultar2
}//fin class
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
					$Tfilas=$this->v_shd700_credito_vivienda->findCount("(rif_cedula LIKE '%$var2%' or nombre_razon LIKE '%$var2%') and ".$this->SQLCA());
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd700_credito_vivienda->findAll("(rif_cedula LIKE '%$var2%' or nombre_razon LIKE '%$var2%') and ".$this->SQLCA(),null,"rif_cedula ASC",50,1,null);
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
						$Tfilas=$this->v_shd700_credito_vivienda->findCount("(rif_cedula LIKE '%$var22%' or nombre_razon LIKE '%$var22%') and ".$this->SQLCA());
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd700_credito_vivienda->findAll("(rif_cedula LIKE '%$var22%' or nombre_razon LIKE '%$var22%') and ".$this->SQLCA(),null,"rif_cedula ASC",50,$pagina,null);
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

function consulta4($numero=null,$numero_solicitud=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$numero."' and numero_solicitud='".$numero_solicitud."' and ".$this->SQLCA();
    $veri=$this->v_shd700_credito_vivienda->findCount($c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd700_credito_vivienda->findAll($c);
      	$data2=$this->v_shd700_credito_vivienda_parentesco->findAll($c);
      	$this->set('datos',$datacpcp01);
      	$this->set('accion',$data2);
      	$this->set('rif_cedula',$numero);
      	$this->set('numero_solicitud',$numero_solicitud);
      	$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
		$this->concatena($vivienda, 'vivienda');
      }else{
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
}//fin function consultar2

 }
?>