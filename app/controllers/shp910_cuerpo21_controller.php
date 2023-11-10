<?php
 class Shp910Cuerpo21Controller extends AppController{
	var $uses = array('shd003_codigo_ingresos','shd900_planillas_deuda_cobro_detalles','v_shd500_aseo_domiciliario','shd500_aseo_domiciliario','shd500_aseo_clasificacion','shd002_cobradores','shd200_vehiculos_clasificacion','shd200_vehiculos_marcas','shd200_vehiculos_modelos','shd200_vehiculos_colores','shd200_vehiculos_tipos','shd200_vehiculos_usos','shd200_vehiculos_clases','cnmd06_profesiones','cugd01_vereda','cugd01_vialidad','v_shd001_registro_contribuyentes','shd200_vehiculos_usos', 'ccfd04_cierre_mes','cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados',
					  'shd001_registro_contribuyentes','shd200_vehiculos','v_shd200_vehiculos');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp910_cuerpo21";


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
	$mes= array('1'=>'ENERO','2'=>'FEBRERO','3'=>'MARZO','4'=>'ABRIL','5'=>'MAYO','6'=>'JUNIO','7'=>'JULIO','8'=>'AGOSTO','9'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
	$this->set('mes',$mes);
	$this->Session->delete("DATOS");
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
	$this->Session->write('rif_det', $var1);



$this->set('datos',$datos);
$resul = javascript_encode($datos[0]['shd001_registro_contribuyentes']['razon_social_nombres'], 1);
   echo'<script>';
			 echo"document.getElementById('deno_rif').value = \"$resul\"; ";
			  echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
			  echo "document.getElementById('ano_deuda').readOnly='';	";
   echo'</script>';
					/*echo "<script>";
					    echo "document.getElementById('deno_rif').value='".$datos[0]['shd001_registro_contribuyentes']['razon_social_nombres']."';   ";
					    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
					    echo "document.getElementById('ano_deuda').readOnly='';	";
					echo "</script>";*/
}else{
	$this->Session->write('rif_det', $var1);
	$vacio='';
					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$vacio."';   ";
						echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
						echo "document.getElementById('ano_deuda').readOnly='true';	";
					echo "</script>";
}

}//fin function


function mes_frecuencia($var=null){
	$this->layout = "ajax";
	if($var==1){
		$mes= array('1'=>'ENERO','2'=>'FEBRERO','3'=>'MARZO','4'=>'ABRIL','5'=>'MAYO','6'=>'JUNIO','7'=>'JULIO','8'=>'AGOSTO','9'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
		$this->set('mes',$mes);
	}else
	if($var==2){
		$mes= array('1'=>'ENERO','3'=>'MARZO','5'=>'MAYO','7'=>'JULIO','9'=>'SEPTIEMBRE','11'=>'NOVIEMBRE');
		$this->set('mes',$mes);
	}else
	if($var==3){
		$mes= array('1'=>'ENERO','4'=>'ABRIL','7'=>'JULIO','10'=>'OCTUBRE');
		$this->set('mes',$mes);
	}else
	if($var==4){
		$mes= array('1'=>'ENERO','7'=>'JULIO');
		$this->set('mes',$mes);
	}else
	if($var==5){
		$mes= array('1'=>'ENERO');
		$this->set('mes',$mes);
	}

}

function agregar_grilla(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$rif_cedula    		       	= $this->data['shp910_cuerpo21']['rif_constribuyente'];
	$ano 						= $this->data['shp910_cuerpo21']['ano_deuda'];
	$cod_numero_catastral_placas= $this->data['shp910_cuerpo21']['placa'];
	$datos = $this->shd003_codigo_ingresos->findAll('cod_ingreso=2');
	$cod_partida 	= $datos[0]['shd003_codigo_ingresos']['cod_partida'];
  	$cod_generica 	= $datos[0]['shd003_codigo_ingresos']['cod_generica'];
  	$cod_especifica = $datos[0]['shd003_codigo_ingresos']['cod_especifica'];
  	$cod_subespec 	= $datos[0]['shd003_codigo_ingresos']['cod_subespec'];
  	$cod_auxiliar 	= $datos[0]['shd003_codigo_ingresos']['cod_auxiliar'];
  	$part='cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_subespec.' and cod_auxiliar='.$cod_auxiliar;
/*
  	$cont = $this->shd900_planillas_deuda_cobro_cuerpo->findCount($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ".$part);
	if($cont==0){
	 	$SQL_INSERT ="INSERT INTO shd900_planillas_deuda_cobro_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica,
  			cod_especifica, cod_sub_espec, cod_auxiliar, rif_cedula, cod_numero_catastral_placas, deuda_ano_anterior)";
	 	$SQL_INSERT .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_partida, $cod_generica,
  			$cod_especifica, $cod_subespec, $cod_auxiliar, '".$rif_cedula."', '".$cod_numero_catastral_placas."', $deuda_anterior)";

	 	$sw = $this->shd900_planillas_deuda_cobro_cuerpo->execute($SQL_INSERT);
	}else{
		$upd="update shd900_planillas_deuda_cobro_cuerpo set deuda_ano_anterior=$deuda_anterior where rif_cedula='".$rif_cedula."' and ".$this->SQLCA()." and ".$part;
		$sw = $this->shd900_planillas_deuda_cobro_cuerpo->execute($upd);
	}*/
	 	   	$mes    			= $this->data['shp910_cuerpo21']['mes'];
   			$numero_recibo     	= $this->data['shp910_cuerpo21']['numero_recibo'];
   			$deuda_vigente	    = $this->Formato1($this->data['shp910_cuerpo21']['deuda_vigente']);
   			if($this->data['shp910_cuerpo21']['recargo']!=null){
   				$recargo			= $this->Formato1($this->data['shp910_cuerpo21']['recargo']);
   			}else{
				$recargo=0;
   			}

   			if($this->data['shp910_cuerpo21']['multa']!=null){
   				$multa			= $this->Formato1($this->data['shp910_cuerpo21']['multa']);
   			}else{
				$multa=0;
   			}

   			if($this->data['shp910_cuerpo21']['intereses']!=null){
   				$intereses			= $this->Formato1($this->data['shp910_cuerpo21']['intereses']);
   			}else{
				$intereses=0;
   			}
			if($this->data['shp910_cuerpo21']['descuentos']!=null){
   				$descuentos			= $this->Formato1($this->data['shp910_cuerpo21']['descuentos']);
   			}else{
				$descuentos=0;
   			}
   			$total 				= $this->Formato1($this->data['shp910_cuerpo21']['total']);
   			$cancelado 			= $this->data['shp910_cuerpo21']['cancelado'];
   			$veri = "cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec and cod_auxiliar=$cod_auxiliar and rif_cedula='".$rif_cedula."' and ano=$ano and mes=$mes and ".$this->SQLCA();
			$veri1 = $this->shd900_planillas_deuda_cobro_detalles->findCount($veri);
			if($veri1 == 0){
			$sql ="INSERT INTO shd900_planillas_deuda_cobro_detalles (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica, cod_especifica, cod_sub_espec,
  					cod_auxiliar, rif_cedula, cod_numero_catastral_placas, ano, mes, numero_planilla, deuda_vigente, monto_recargo,
 	 				monto_multa, monto_intereses, monto_descuento, cancelado,fecha_emision)";
			$sql.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_partida, $cod_generica, $cod_especifica, $cod_subespec,
  					$cod_auxiliar, '".$rif_cedula."', '".$cod_numero_catastral_placas."', $ano, $mes, $numero_recibo, $deuda_vigente, $recargo,
  					$multa, $intereses, $descuentos, $cancelado,'1900-01-01');";
			$sw1 = $this->shd900_planillas_deuda_cobro_detalles->execute($sql);
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');


echo'<script>';
       echo" document.getElementById('mes').options[0].text     = ''; ";
       echo" document.getElementById('numero_recibo').value           = ''; ";
       echo" document.getElementById('deuda_vigente').value           = ''; ";
       echo" document.getElementById('recargo').value           = ''; ";
       echo" document.getElementById('multa').value        = ''; ";
       echo" document.getElementById('intereses').value     = ''; ";
       echo" document.getElementById('descuentos').value           = ''; ";
       echo" document.getElementById('total').value           = ''; ";
       echo" document.getElementById('cancelado_1').checked        = 'false'; ";
       echo" document.getElementById('cancelado_2').checked        = 'true'; ";
echo'</script>';
			}else{
				$this->set('errorMessage', 'MES YA SE ENCUENTRA REGISTRADO');
			}
$datos2 = $this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano=".$ano." and ".$part,null,'mes ASC');
$this->set('accion',$datos2);
}

function buscar_detalles($ano){
	$this->layout = "ajax";
	$rif_cedula = $this->Session->read('rif_det');
	$datos = $this->shd003_codigo_ingresos->findAll('cod_ingreso=2');
	$cod_partida 	= $datos[0]['shd003_codigo_ingresos']['cod_partida'];
  	$cod_generica 	= $datos[0]['shd003_codigo_ingresos']['cod_generica'];
  	$cod_especifica = $datos[0]['shd003_codigo_ingresos']['cod_especifica'];
  	$cod_subespec 	= $datos[0]['shd003_codigo_ingresos']['cod_subespec'];
  	$cod_auxiliar 	= $datos[0]['shd003_codigo_ingresos']['cod_auxiliar'];
  	$part='cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_subespec.' and cod_auxiliar='.$cod_auxiliar;
	$datos2 = $this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano=".$ano." and ".$part,null,'mes ASC');
	/*$datos3 = $this->shd900_planillas_deuda_cobro_cuerpo->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ".$part);
	if($datos3 !=null){
		$a=$datos3[0]['shd900_planillas_deuda_cobro_cuerpo']['cod_numero_catastral_placa'];
		$b=$this->Formato2($datos3[0]['shd900_planillas_deuda_cobro_cuerpo']['deuda_ano_anterior']);
*/
$datos3 = $this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ".$part,null,'mes ASC');
if($datos3 !=null){
$a=$datos3[0]['shd900_planillas_deuda_cobro_detalles']['cod_numero_catastral_pla'];
		echo'<script>';
       		echo" document.getElementById('placa').value           = '".$a."'; ";
		echo'</script>';
	}else{
		echo'<script>';
       		echo" document.getElementById('placa').value           = ''; ";
		echo'</script>';
	}
	$this->set('accion',$datos2);
}

function guardar(){
	$this->layout = "ajax";
	$rif_cedula    		       	= $this->data['shp910_cuerpo21']['rif_constribuyente'];
	$deuda_anterior				= $this->Formato1($this->data['shp910_cuerpo21']['deuda_anterior']);
	$datos = $this->shd003_codigo_ingresos->findAll('cod_ingreso=2');
	$cod_partida 	= $datos[0]['shd003_codigo_ingresos']['cod_partida'];
  	$cod_generica 	= $datos[0]['shd003_codigo_ingresos']['cod_generica'];
  	$cod_especifica = $datos[0]['shd003_codigo_ingresos']['cod_especifica'];
  	$cod_subespec 	= $datos[0]['shd003_codigo_ingresos']['cod_subespec'];
  	$cod_auxiliar 	= $datos[0]['shd003_codigo_ingresos']['cod_auxiliar'];
  	$part='cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_subespec.' and cod_auxiliar='.$cod_auxiliar;
	$upd="update shd900_planillas_deuda_cobro_cuerpo set deuda_ano_anterior=$deuda_anterior where rif_cedula='".$rif_cedula."' and ".$this->SQLCA()." and ".$part;
	$sw = $this->shd900_planillas_deuda_cobro_cuerpo->execute($upd);
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
	$this->index();
	$this->render('index');
}

function eliminar_grilla($ano=null,$mes=null,$rif_cedula=null){
	$this->layout='ajax';
	$datos = $this->shd003_codigo_ingresos->findAll('cod_ingreso=2');
	$cod_partida 	= $datos[0]['shd003_codigo_ingresos']['cod_partida'];
  	$cod_generica 	= $datos[0]['shd003_codigo_ingresos']['cod_generica'];
  	$cod_especifica = $datos[0]['shd003_codigo_ingresos']['cod_especifica'];
  	$cod_subespec 	= $datos[0]['shd003_codigo_ingresos']['cod_subespec'];
  	$cod_auxiliar 	= $datos[0]['shd003_codigo_ingresos']['cod_auxiliar'];
  	$part='cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_subespec.' and cod_auxiliar='.$cod_auxiliar;
	$this->shd900_planillas_deuda_cobro_detalles->execute("DELETE FROM shd900_planillas_deuda_cobro_detalles  WHERE ".$this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano=".$ano." and mes=".$mes." and ".$part);
 	$datos2 = $this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano=".$ano." and ".$part,null,'mes ASC');
 	$this->set('accion',$datos2);
 	$this->set('Message_existe', 'el registro fue eliminado');
}
function grilla_nuevax(){
	$this->layout='ajax';
	   	echo'<script>';
	   		echo" document.getElementById('ano_deuda').value           = ''; ";
       		echo" document.getElementById('placa').value           = ''; ";
       	echo'</script>';
}

}