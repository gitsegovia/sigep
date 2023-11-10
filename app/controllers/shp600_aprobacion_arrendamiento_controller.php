<?php

 class shp600AprobacionArrendamientoController extends AppController{
 	var $name = "shp600_aprobacion_arrendamiento";
	var $uses = array('v_shd600_aprobacion_arrendamiento','shd600_aprobacion_arrendamiento','v_shd600_solicitud_arrendamiento','shd600_solicitud_arrendamiento','v_shd600_compra_terreno','v_shd400_propiedad','catd02_ficha_tipologia','catd02_ficha_datos','v_catd02_ficha_datos','shd400_propiedad','shd300_propaganda','v_shd100_patente_actividades','v_shd100_patente','v_shd100_solicitud','shd100_patente','shd001_registro_contribuyentes','shd100_patente_actividades','shd100_solicitud',
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
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
	$cod_presi 		= $this->Session->read('SScodpresi');
	$cod_entidad 	= $this->Session->read('SScodentidad');
	$cod_tipo_inst 	= $this->Session->read('SScodtipoinst');
	$cod_inst 		= $this->Session->read('SScodinst');
	$cod_dep 		= $this->Session->read('SScoddep');

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					// $Tfilas=$this->v_shd600_solicitud_arrendamiento->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%')  or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))");
					$cond_p = "SELECT a.numero_solicitud, a.rif_cedula, a.nombre_razon, a.cod_ficha FROM v_shd600_solicitud_arrendamiento a WHERE NOT EXISTS (SELECT b.numero_solicitud FROM v_shd600_aprobacion_arrendamiento b WHERE b.cod_presi = a.cod_presi and b.cod_entidad = a.cod_entidad and b.cod_tipo_inst = a.cod_tipo_inst and b.cod_inst = a.cod_inst and b.cod_dep = a.cod_dep and b.rif_cedula = a.rif_cedula and b.numero_solicitud = a.numero_solicitud) and
		  							  a.cod_presi=$cod_presi and a.cod_entidad=$cod_entidad and a.cod_tipo_inst=$cod_tipo_inst and a.cod_inst=$cod_inst and a.cod_dep=$cod_dep";
					$datos_filas=$this->v_shd600_solicitud_arrendamiento->execute($cond_p." and ((a.rif_cedula LIKE '%$var2%') or (a.numero_solicitud LIKE '%$var2%')  or (quitar_acentos(a.nombre_razon) LIKE quitar_acentos('%$var2%'))) ORDER BY a.numero_solicitud ASC LIMIT 50 OFFSET 0;");
					        if($datos_filas!=0){
					        	$pagina=1;
					        	$Tfilas=count($datos_filas);
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    // $datos_filas=$this->v_shd600_solicitud_arrendamiento->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%')  or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,1,null);
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
						$pagina=$var3;
						// $Tfilas=$this->v_shd600_solicitud_arrendamiento->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%')  or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))");
					$cond_p = "SELECT a.numero_solicitud, a.rif_cedula, a.nombre_razon, a.cod_ficha FROM v_shd600_solicitud_arrendamiento a WHERE NOT EXISTS (SELECT b.numero_solicitud FROM v_shd600_aprobacion_arrendamiento b WHERE b.cod_presi = a.cod_presi and b.cod_entidad = a.cod_entidad and b.cod_tipo_inst = a.cod_tipo_inst and b.cod_inst = a.cod_inst and b.cod_dep = a.cod_dep and b.rif_cedula = a.rif_cedula and b.numero_solicitud = a.numero_solicitud) and
		  							  a.cod_presi=$cod_presi and a.cod_entidad=$cod_entidad and a.cod_tipo_inst=$cod_tipo_inst and a.cod_inst=$cod_inst and a.cod_dep=$cod_dep";
					$datos_filas=$this->v_shd600_solicitud_arrendamiento->execute($cond_p." and ((a.rif_cedula LIKE '%$var2%') or (a.numero_solicitud LIKE '%$var2%')  or (quitar_acentos(a.nombre_razon) LIKE quitar_acentos('%$var2%'))) ORDER BY a.numero_solicitud ASC LIMIT 50 OFFSET ".($pagina-1).";");
					        if($datos_filas!=0){
						        	$Tfilas=count($datos_filas);
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
							        $datos_filas=$this->v_shd600_solicitud_arrendamiento->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%')  or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,$pagina,null);
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


function buscar_constribuyente_v($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('campo_pista_v').focus();</script>";
}//fin function

function buscar_por_pista_v($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

	$modelo="v_catd02_ficha_datos";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					// $Tfilas=$this->$modelo->findCount("((cedula_rif LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))");
				 	$Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    // $datos_filas=$this->$modelo->findAll("((cedula_rif LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))",null,"cedula_rif, cod_ficha ASC",50,1,null);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))","cod_ficha, cedula_rif_repre, nombre_repre, terreno_sector","cod_ficha, cedula_rif_repre, nombre_repre ASC",50,1,null);
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
						// $Tfilas=$this->$modelo->findCount("((cedula_rif LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))");
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    // $datos_filas=$this->$modelo->findAll("((cedula_rif LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))",null,"cedula_rif,cod_ficha ASC",50,$pagina,null);
						     	    $datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))","cod_ficha, cedula_rif_repre, nombre_repre, terreno_sector","cod_ficha, cedula_rif_repre, nombre_repre ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("modelo",$modelo);
$this->set("opcion",$var1);
}//fin function

function seleccion_busqueda_venta($var1=null,$numero_solicitud=null){
$this->layout="ajax";
$datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$var1."'");
	if($datos != null){
	$sol = $this->v_shd600_solicitud_arrendamiento->findAll($this->SQLCA()." and rif_cedula='".$var1."' and numero_solicitud='".$numero_solicitud."'");
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

$opcion = $sol[0]['v_shd600_solicitud_arrendamiento']['opcion'];
if($opcion==1){
	$op1=true;
	$op2=false;
}else if($opcion==2){
	$op2=true;
	$op1=false;
}


$this->set('datos',$datos);
$resul = javascript_encode($datos[0]['shd001_registro_contribuyentes']['razon_social_nombres'], 1);
   echo'<script>';
		  echo"document.getElementById('deno_rif').value = \"$resul\"; ";
		  echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
		  echo "document.getElementById('numero_solicitud').value='".$sol[0]['v_shd600_solicitud_arrendamiento']['numero_solicitud']."';   ";
		   echo "document.getElementById('expectativa').value='".$sol[0]['v_shd600_solicitud_arrendamiento']['expectativa_construccion']."';   ";
		    echo "document.getElementById('opcion_1').checked='".$op1."';   ";
		    echo "document.getElementById('opcion_2').checked='".$op2."';   ";
   echo'</script>';

					/*echo "<script>";
					    echo "document.getElementById('deno_rif').value='".$datos[0]['shd001_registro_contribuyentes']['razon_social_nombres']."';   ";
					    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
					    echo "document.getElementById('numero_solicitud').value='".$sol[0]['v_shd600_solicitud_arrendamiento']['numero_solicitud']."';   ";
					    echo "document.getElementById('expectativa').value='".$sol[0]['v_shd600_solicitud_arrendamiento']['expectativa_construccion']."';   ";
					    echo "document.getElementById('opcion_1').checked='".$op1."';   ";
					    echo "document.getElementById('opcion_2').checked='".$op2."';   ";
					echo "</script>";*/
}else{
	$vacio='';
					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$vacio."';   ";
						echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
						echo "document.getElementById('numero_solicitud').value='".$vacio."';   ";
					    echo "document.getElementById('expectativa').value='".$vacio."';   ";
					echo "</script>";
}

}//fin function

function num_ficha1($rif_cedula=null,$cod_ficha=null){
	$this->layout="ajax";
	$datos=$this->catd02_ficha_datos->findAll($this->SQLCA()." and cedula_rif='".$rif_cedula."' and cod_ficha=$cod_ficha");
	$this->set('datos',$datos);
}

function num_fichax($numero=null){
	$this->layout="ajax";
	$numero=strtoupper($numero);
	$datos1=$this->shd600_solicitud_arrendamiento->findAll($this->SQLCA()." and numero_solicitud='".$numero."'");
	if($datos1 != null){
	$cod_ficha=$datos1[0]['shd600_solicitud_arrendamiento']['cod_ficha'];
	$datos=$this->catd02_ficha_datos->findAll($this->SQLCA()." and cod_ficha='".$cod_ficha."'");
	$this->set('datos',$datos);
	}else{
		$this->set('datos','');
	}
}

function num_soli($numero=null){
	$this->layout="ajax";
	$numero1=strtoupper($numero);
	$datos=$this->v_shd600_solicitud_arrendamiento->findAll($this->SQLCA()." and numero_solicitud='".$numero1."'");
	$this->set('datos',$datos);
	if($datos != null){
		$expectativa=$datos[0]['v_shd600_solicitud_arrendamiento']['expectativa_construccion'];
		$opcion=$datos[0]['v_shd600_solicitud_arrendamiento']['opcion'];
		if($opcion==1){
			echo "<script>";
	    		echo "document.getElementById('opcion_1').checked=true;   ";
	    		echo "document.getElementById('opcion_2').checked=false;   ";
			echo "</script>";
		}else if($opcion==2){
			echo "<script>";
	    		echo "document.getElementById('opcion_2').checked=true;   ";
	    		echo "document.getElementById('opcion_1').checked=false;   ";
			echo "</script>";
		}


		echo "<script>";
	    	echo "document.getElementById('expectativa').value='".$expectativa."';   ";
		echo "</script>";

	}else{
		echo "<script>";
			echo "document.getElementById('num_solicitud').value='';   ";
			echo "document.getElementById('opcion_2').checked=false;   ";
	    	echo "document.getElementById('opcion_1').checked=false;   ";
	    	echo "document.getElementById('expectativa').value='';   ";
		echo "</script>";
	}

}

function guardar(){
	$this->layout = "ajax";//pr($this->data);
	$cod_presi 						= $this->Session->read('SScodpresi');
	$cod_entidad 					= $this->Session->read('SScodentidad');
	$cod_tipo_inst 					= $this->Session->read('SScodtipoinst');
	$cod_inst 						= $this->Session->read('SScodinst');
	$cod_dep 						= $this->Session->read('SScoddep');
  	$numero_solicitud 				= $this->data['shp600_aprobacion_arrendamiento']['numero_solicitud'];
  	$fecha_aprobacion 				= $this->data['shp600_aprobacion_arrendamiento']['fecha_aprobacion'];
  	$frecuencia_pago 				= $this->data['shp600_aprobacion_arrendamiento']['frecuencia_pago'];
  	$datos_registro_arrendamiento 	= $this->data['shp600_aprobacion_arrendamiento']['datos_legales'];
  	$monto_mensual 					= $this->Formato1($this->data['shp600_aprobacion_arrendamiento']['monto_pagar']);
  	$pago_todo 						= $this->data['shp600_aprobacion_arrendamiento']['pago_todo'];
  	$suspendido 					= $this->data['shp600_aprobacion_arrendamiento']['suspendido'];
  	$rif_ci_cobrador 				= $this->data['shp600_aprobacion_arrendamiento']['rif_cedula'];
  	$rif_cedula   				= $this->data['shp600_aprobacion_arrendamiento']['rif_constribuyente'];
  	$ultimo_ano_facturado 			= '0';
  	$ultimo_mes_facturado 			= '0';
  	$terreno_vendido 				= '2';


	$SQL_INSERT ="INSERT INTO shd600_aprobacion_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,rif_cedula,numero_solicitud,
  	fecha_aprobacion, frecuencia_pago, datos_registro_arrendamiento, monto_mensual, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado,
  	ultimo_mes_facturado, terreno_vendido)";

	$SQL_INSERT .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,'".$rif_cedula."', '".$numero_solicitud."',
  	'".$fecha_aprobacion."', $frecuencia_pago, '".$datos_registro_arrendamiento."', $monto_mensual, $pago_todo, $suspendido, '".$rif_ci_cobrador."', $ultimo_ano_facturado,
  	$ultimo_mes_facturado, $terreno_vendido)";

	$sw = $this->shd600_aprobacion_arrendamiento->execute($SQL_INSERT);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS EXITOSAMENTE');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->index();
		$this->render("index");
}

function consultar($pagina=null){
 		$this->layout = "ajax";
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd600_aprobacion_arrendamiento->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos2=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA(),null,'rif_cedula,numero_solicitud ASC',1,$pagina,null);
          	 $num_solic = $datos2[0]['v_shd600_aprobacion_arrendamiento']['numero_solicitud'];
          	 $rif_cedula = $datos2[0]['v_shd600_aprobacion_arrendamiento']['rif_cedula'];
          	 $cod_ficha = $datos2[0]['v_shd600_aprobacion_arrendamiento']['cod_ficha'];
          	 $datos=$this->catd02_ficha_datos->findAll("cedula_rif='".$rif_cedula."' and cod_ficha=$cod_ficha and ".$this->SQLCA());
			 $busca_ct=$this->v_shd600_compra_terreno->findCount($this->SQLCA()." and numero_solicitud='$num_solic' and rif_cedula='$rif_cedula' and cod_ficha='$cod_ficha'");
          	 $this->set('datos2',$datos2);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $this->set('busca_ct',$busca_ct);
             }
 }else{
 	$pagina=1;
 			$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd600_aprobacion_arrendamiento->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos2=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA(),null,'rif_cedula,numero_solicitud ASC',1,$pagina,null);
          	 $num_solic = $datos2[0]['v_shd600_aprobacion_arrendamiento']['numero_solicitud'];
          	 $rif_cedula = $datos2[0]['v_shd600_aprobacion_arrendamiento']['rif_cedula'];
          	 $cod_ficha = $datos2[0]['v_shd600_aprobacion_arrendamiento']['cod_ficha'];
          	 $datos=$this->catd02_ficha_datos->findAll("cedula_rif='".$rif_cedula."' and cod_ficha=$cod_ficha and ".$this->SQLCA());
          	 $busca_ct=$this->v_shd600_compra_terreno->findCount($this->SQLCA()." and numero_solicitud='$num_solic' and rif_cedula='$rif_cedula' and cod_ficha='$cod_ficha'");
          	 $this->set('datos2',$datos2);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $this->set('busca_ct',$busca_ct);
			 }
}
}//fin function consultar


function modificar($rif_cedula=null,$numero_solicitud=null,$pagina=null,$individual=null){
	$this->layout = "ajax";
			 $datos2=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'");
          	 $rif_cedula = $datos2[0]['v_shd600_aprobacion_arrendamiento']['rif_cedula'];
          	 $cod_ficha  = $datos2[0]['v_shd600_aprobacion_arrendamiento']['cod_ficha'];
          	 $datos=$this->catd02_ficha_datos->findAll("cedula_rif='".$rif_cedula."' and cod_ficha=$cod_ficha and ".$this->SQLCA());
          	 $this->set('datos2',$datos2);
          	 $this->set('datos',$datos);
    $this->set('pagina',$pagina);
    $this->set("rif_cedula", $this->shd002_cobradores->generateList($this->SQLCA(), "nombre_razon ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));
	$this->set('individual',$individual);
}

function guardar_modificar($rif_cedula=null,$numero_solicitud=null,$pagina=null,$individual=null){
	$this->layout = "ajax";
	$fecha_aprobacion 				= $this->data['shp600_aprobacion_arrendamiento']['fecha_aprobacion'];
  	$frecuencia_pago 				= $this->data['shp600_aprobacion_arrendamiento']['frecuencia_pago'];
  	$datos_registro_arrendamiento 	= $this->data['shp600_aprobacion_arrendamiento']['datos_legales'];
  	$monto_mensual 					= $this->Formato1($this->data['shp600_aprobacion_arrendamiento']['monto_pagar']);
  	$pago_todo 						= $this->data['shp600_aprobacion_arrendamiento']['pago_todo'];
  	$suspendido 					= $this->data['shp600_aprobacion_arrendamiento']['suspendido'];
  	$rif_ci_cobrador 				= $this->data['shp600_aprobacion_arrendamiento']['rif_cedula'];
	$cond=$this->SQLCA();
	$guardar="update shd600_aprobacion_arrendamiento set fecha_aprobacion='".$fecha_aprobacion."', frecuencia_pago=$frecuencia_pago, datos_registro_arrendamiento='".$datos_registro_arrendamiento."', monto_mensual=$monto_mensual, pago_todo=$pago_todo, suspendido=$suspendido, rif_ci_cobrador='".$rif_ci_cobrador."'
  	 where numero_solicitud='".$numero_solicitud."' and $cond and rif_cedula='".$rif_cedula."'";
	$sw = $this->shd600_aprobacion_arrendamiento->execute($guardar);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON MODIFICADOS EXITOSAMENTE');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON MODIFICADOS - POR FAVOR INTENTE DE NUEVO');
		}
echo "reeeeeeeeeeennn: ".$individual.$pagina;
		if($individual!=null){
			$this->consulta2($rif_cedula,$numero_solicitud);
			$this->render("consulta2");
		}else{
			$this->consultar($pagina);
			$this->render("consultar");
		}
}

function eliminar($rif_cedula=null,$numero_solicitud=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();
 	$cond="numero_solicitud='".$numero_solicitud."' and $ca and rif_cedula='".$rif_cedula."'";
 	//echo $cond;
 	$this->shd600_aprobacion_arrendamiento->execute("DELETE FROM shd600_aprobacion_arrendamiento  WHERE ".$cond);
 	$y=$this->shd600_aprobacion_arrendamiento->findCount($this->SQLCA());
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
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";//echo 'si2';
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->v_shd600_aprobacion_arrendamiento->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);//pr($datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{//echo 'aa';
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))";
						$Tfilas=$this->v_shd600_aprobacion_arrendamiento->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    //$datos_filas=$this->v_shd600_solicitud_arrendamiento->findAll("((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))",1,1,null);
							        $datos_filas=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);//pr($datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function consulta2($rif_cedula=null,$numero_solicitud=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'";
    $veri=$this->v_shd600_aprobacion_arrendamiento->findCount($c." and ".$this->SQLCA());
      if($veri > 0){
			 $datos2=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'");
			 $num_solic = $datos2[0]['v_shd600_aprobacion_arrendamiento']['numero_solicitud'];
          	 $rif_cedula = $datos2[0]['v_shd600_aprobacion_arrendamiento']['rif_cedula'];
          	 $cod_ficha  = $datos2[0]['v_shd600_aprobacion_arrendamiento']['cod_ficha'];
          	 $datos=$this->catd02_ficha_datos->findAll("cedula_rif='".$rif_cedula."' and cod_ficha=$cod_ficha and ".$this->SQLCA());
          	 $busca_ct=$this->v_shd600_compra_terreno->findCount($this->SQLCA()." and numero_solicitud='$num_solic' and rif_cedula='$rif_cedula' and cod_ficha='$cod_ficha'");
          	 $this->set('datos2',$datos2);
          	 $this->set('datos',$datos);
          	 $this->set('rif_cedula',$rif_cedula);
          	 $this->set('numero_solicitud',$numero_solicitud);
          	 $this->set('busca_ct',$busca_ct);
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
          	  $Tfilas=$this->v_shd600_aprobacion_arrendamiento->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos2=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA(),null,'rif_cedula,numero_solicitud ASC',1,$pagina,null);
          	 $rif_cedula = $datos2[0]['v_shd600_aprobacion_arrendamiento']['rif_cedula'];
          	 $cod_ficha = $datos2[0]['v_shd600_aprobacion_arrendamiento']['cod_ficha'];
          	 $datos=$this->catd02_ficha_datos->findAll("cedula_rif='".$rif_cedula."' and cod_ficha=$cod_ficha and ".$this->SQLCA());
          	 $this->set('datos2',$datos2);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
            // echo"hola";
 }else{
 	$pagina=1;
 			$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd600_aprobacion_arrendamiento->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos2=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA(),null,'rif_cedula,numero_solicitud ASC',1,$pagina,null);
          	 $rif_cedula = $datos2[0]['v_shd600_aprobacion_arrendamiento']['rif_cedula'];
          	 $cod_ficha = $datos2[0]['v_shd600_aprobacion_arrendamiento']['cod_ficha'];
          	 $datos=$this->catd02_ficha_datos->findAll("cedula_rif='".$rif_cedula."' and cod_ficha=$cod_ficha and ".$this->SQLCA());
          	 $this->set('datos2',$datos2);
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
$this->layout="ajax";//echo 'si2';
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->v_shd600_aprobacion_arrendamiento->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);//pr($datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{//echo 'aa';
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))";
						$Tfilas=$this->v_shd600_aprobacion_arrendamiento->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    //$datos_filas=$this->v_shd600_solicitud_arrendamiento->findAll("((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))",1,1,null);
							        $datos_filas=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);//pr($datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function consulta4($rif_cedula=null,$numero_solicitud=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'";
    $veri=$this->v_shd600_aprobacion_arrendamiento->findCount($c." and ".$this->SQLCA());
      if($veri > 0){
			 $datos2=$this->v_shd600_aprobacion_arrendamiento->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."'");
          	 $rif_cedula = $datos2[0]['v_shd600_aprobacion_arrendamiento']['rif_cedula'];
          	 $cod_ficha  = $datos2[0]['v_shd600_aprobacion_arrendamiento']['cod_ficha'];
          	 $datos=$this->catd02_ficha_datos->findAll("cedula_rif='".$rif_cedula."' and cod_ficha=$cod_ficha and ".$this->SQLCA());
          	 $this->set('datos2',$datos2);
          	 $this->set('datos',$datos);
          	 $this->set('rif_cedula',$rif_cedula);
          	 $this->set('numero_solicitud',$numero_solicitud);
      }else{
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
}//fin function consultar2




 }
?>