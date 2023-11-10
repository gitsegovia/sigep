<?php
/*
 * Creado el 09/10/2008 a las 12:41:23 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Shp950SolvenciaController extends AppController{
	var $uses = array('cugd01_vereda','cugd01_vialidad','v_shd001_registro_contribuyentes','ccfd04_cierre_mes','cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados',
					  'shd001_registro_contribuyentes','cnmd06_profesiones','shd950_solvencia','shd950_solvencia_numero','shd000_arranque','v_shd950_solvencias','shd000_arranque');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp950_solvencia";


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


function index($var=null){///////////////<<--INDEX
	 $this->layout = "ajax";

	 $ano=$this->ano_ejecucion();
	 $ano = $this->shd000_arranque->ano($this->SQLCA());
	 //$this->set('year', $ano);
	 //$this->Session->write('year_pago',$ano);

      $maxi=$this->shd950_solvencia_numero->findCount($this->SQLCA()." and ano=".$ano." and situacion=1");
      //$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()."  ORDER BY numero_compromiso ASC LIMIT 1");
      if($maxi==0){
         $this->set("errorMessage","Verifique el n&uacute;mero de control de solvencias");
      	 $this->set("numero_solvencia","");
      	 $this->redirect("/shp950_solvencia_numero/index/xy");
      }
      if(isset($_SESSION["MSJ"])){
					$a=$_SESSION["MSJ"];
					if($a["tipo_msj"]=="exito")$this->set('Message_existe', $a["msj"]);
					else if($a["tipo_msj"]=="error")$this->set('errorMessage', $a["msj"]);
					$this->Session->delete("MSJ");
	  }

//	  $this->planilla(2009,1,18444246);

}//fin index

function index2(){
	$this->layout = "ajax";
//echo $_SESSION['utiliza_planillas_liquidacion_previa'];
	$select=array('1'=>'SOLICITUD DE PATENTE DE INDUSTRIA Y COMERCIO','2'=>'RENOVACIÓN DE CARTA DE PATENTE','3'=>'DECLARACIÓN JURADA DE INGRESOS BRUTOS',
				  '4'=>'PERMISOS DE CONSTRUCCIÓN','5'=>'VARIABLES URBANAS','6'=>'REPARACIÓN O MEJORAS DE INMUBLES','7'=>'PROTOCOLIZACIÓN DE DOCUMENTOS',
				  '8'=>'REGULACIÓN O DESOCUPACIÓN DE INMUEBLE','9'=>'SOLICITUD DE CRÉDITO A ENTIDAD FINANCIERA','10'=>'ESPECTACULOS PÚBLICOS','11'=>'CONSTITUCIÓN DE EMPRESAS',
				  '12'=>'LICITACIONES','14'=>'TRÁMITES ADMINISTRATIVOS','13'=>'OTROS');
	$this->set('select_solvencia',$select);


	/////////////////////////////////////AQUI LA PRUEBA DEL NUMERO////////////////////////////
 //$dato=$this->ano_ejecucion();
 $dato = $this->shd000_arranque->ano($this->SQLCA());
  $maxi=$this->shd950_solvencia_numero->findCount($this->SQLCA());
      $max=$this->shd950_solvencia_numero->execute("SELECT numero_solvencia FROM shd950_solvencia_numero WHERE ".$this->SQLCA()." and ano=".$dato." and situacion=1 ORDER BY numero_solvencia ASC LIMIT 1");
      //echo "numero".$maxi;
      //print_r($max);
      if($max!=null){
      	    $codigo=$max[0][0]["numero_solvencia"];
            $resultado=$this->shd950_solvencia_numero->execute("UPDATE  shd950_solvencia_numero SET situacion=2 WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$dato);
	         if($resultado>1){
                //$this->set("Message_existe","Situacion de solicitud actualizada con exito");
               $this->set("numero_solvencia",$codigo);
	         }else{
		        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de solvencias");
		        $this->set("numero_solvencia","");
		        $MSJ1=array("msj"=>"debe registrar nuevos numeros para las solvencias","tipo_msj"=>"exito");
				$this->Session->write("MSJ1",$MSJ1);
		        $this->redirect("/shp950_solvencia_numero/index/numero/otro");
	      }
      }else{
      	 $this->set("errorMessage","Verifique el n&uacute;mero de control de solvencias");
      	 $this->set("numero_solicitud","");
      	 $MSJ1=array("msj"=>"debe registrar nuevos numeros para las solvencias","tipo_msj"=>"exito");
		 $this->Session->write("MSJ1",$MSJ1);
      	 $this->redirect("/shp950_solvencia_numero/index/numero/otro");
      }


      $a=$this->shd950_solvencia_numero->execute("select * from shd950_solvencia_monto where ".$this->SQLCA());
      if($a!=null){
		$this->set('solvencia_monto',$a[0][0]['monto_solvencia']);
      }else{
		$this->set('solvencia_monto',null);
 	  }

 ///////////////////////////////////FIN PRUEBA NUMERO//////////////////////////////////


}//fin index


function verifica_solvencia($rif_cedula){
		$this->layout="ajax";
if($rif_cedula!='no'){
	if($_SESSION['utiliza_planillas_liquidacion_previa']==1){
					$sql="select
					cod_presi,
					cod_entidad,
					cod_tipo_inst,
					cod_inst,
					cod_dep,
					cod_partida,
					cod_generica,
					cod_especifica,
					cod_sub_espec,
					cod_auxiliar,
					codigo_ingreso,
					denominacion_ingreso
					from v_shd950_solvencia_detalles
					where ".$this->SQLCA()."and rif_cedula='".$rif_cedula."' and cancelado=2
					group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,codigo_ingreso,denominacion_ingreso
					order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,codigo_ingreso";
				$datos=$this->shd001_registro_contribuyentes->execute($sql);

				if($datos!=null){
					$this->set('datos',$datos);
					$this->set('errorMessage', 'el contribuyente no se encuentra solvente');
				}else{
					$this->set('datos',null);

				}

	}else{

		$n=0;
		$sql="select
					cod_presi,
					cod_entidad,
					cod_tipo_inst,
					cod_inst,
					cod_dep,
					cod_partida,
					cod_generica,
					cod_especifica,
					cod_sub_espec,
					cod_auxiliar,
					codigo_ingreso,
					denominacion_ingreso
					from v_shd950_solvencia_detalles
					where ".$this->SQLCA()."and rif_cedula='".$rif_cedula."' and cancelado=2 and codigo_ingreso!=1
					group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,codigo_ingreso,denominacion_ingreso
					order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,codigo_ingreso";
				$datos=$this->shd001_registro_contribuyentes->execute($sql);

				if($datos!=null){
					$datos=$datos;
					$this->set('errorMessage', 'el contribuyente no se encuentra solvente');
					$n=1;
				}else{
					$datos=null;

				}

				$patente1=$this->shd001_registro_contribuyentes->execute("select * from shd100_patente where ".$this->SQLCA()." and rif_cedula='$rif_cedula'");
				if($patente1!=null){//aqui verifico que exista en la tabla patente , luego verifico si en las condiciones si ya ha declarado o no
					if($patente1[0][0]['ultimo_numero_declaracion']==0 || $patente1[0][0]['ultimo_numero_declaracion']==null){
						//aqui verifico si el numero de declaracion es 0 o null quiere decir que nop ha declarado y voy directamente a verificar la fecha de patente para ver si esta o no solvente
							$fecha=$patente1[0][0]['fecha_patente'];
							$valor=$this->antiguedad($fecha,date('Y-m-d'));
							if($valor==true){
								if($n!=1){
									$this->set('errorMessage', 'el contribuyente no se encuentra solvente');

								}
								$datos=true;
							}
							$this->set('patente',$valor);
					}else{
						//aqui quiere decir que ya ha declarado, debo verificar si cancelo o no,si cancelado =2 no esta solvente sino es 1 y pago pero debo verificar las fecha para ver hace cuanto fue y asi ver si esta o no solvente
//						$solvente=$this->shd001_registro_contribuyentes->execute("select * from shd100_declaracion_ingresos where ".$this->SQLCA()." and rif_cedula='$rif_cedula' and fecha_declaracion='".$patente1[0][0]['fecha_ultima_decla']."' and numero_declaracion='".$patente1[0][0]['ultimo_numero_declaracion']."' and cancelado=2");
//						$solvente=$this->shd001_registro_contribuyentes->execute("select * from shd100_declaracion_ingresos where ".$this->SQLCA()." and rif_cedula='$rif_cedula' and numero_declaracion='".$patente1[0][0]['ultimo_numero_declaracion']."' and cancelado=2");
						$solvente=$this->shd001_registro_contribuyentes->execute("select * from shd100_declaracion_ingresos where ".$this->SQLCA()." and rif_cedula='$rif_cedula' and condicion_actividad=1 and cancelado=2");
						if($solvente!=null){
							$this->set('patente',true);
							if($n!=1){
								$this->set('errorMessage', 'el contribuyente no se encuentra solvente');
								$datos=true;
							}

						}else{
							$fecha=$patente1[0][0]['periodo_hasta'];
							$valor=$this->antiguedad($fecha,date('Y-m-d'));
							if($valor==true){
								if($n!=1){
									$this->set('errorMessage', 'el contribuyente no se encuentra solvente');
									$datos=true;
								}

							}
							$this->set('patente',$valor);
						}

					}


				}else{
					$this->set('patente',false);
				}

					$this->set('datos',$datos);
	}
$this->set('no','si');
}else{
	$this->set('no','no');
}


}


function antiguedad ($desde,$hasta) {
   $this->layout="ajax";
   $ano = $this->shd001_registro_contribuyentes->execute("SELECT devolver_edad('$hasta', '$desde', 'ANO')");
   $mes = $this->shd001_registro_contribuyentes->execute("SELECT devolver_edad('$hasta', '$desde', 'MES')");
   $dia = $this->shd001_registro_contribuyentes->execute("SELECT devolver_edad('$hasta', '$desde', 'DIA')");
   if(count($ano)>0){
   	 $ano = $ano[0][0]['devolver_edad'];
   }else{
   	 $ano = 0;
   }

   if($ano>=1){
	   	$mes=12;
	   	$var=true;
   }else if($ano==0){
	   	if($mes[0][0]['devolver_edad']>=3){
			$mes=12;
			$var=true;
	   	}else if($mes[0][0]['devolver_edad']==2 && $dia[0][0]['devolver_edad']>20 ){
	   		$mes=12;
	   		$var=true;
	   	}else{
	   		$mes=$mes[0][0]['devolver_edad'];
	   		$var=false;
	   	}
   }else{
   		$var=false;
   }

   /*if($mes>=3){
		return true;
   }else{
		return false;
   }*/
   return $var;

}//fin funcion antiguedad


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


function seleccion_busqueda($var1=null){
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

echo" <script> ver_documento('/shp950_solvencia/verifica_solvencia/$var1','botones'); </script>";

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

	echo" <script> ver_documento('/shp950_solvencia/verifica_solvencia/no','botones'); </script>";
}




}//fin function


function guardar(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	if(empty($this->data['shp950']['rif_constribuyente'])){
		$this->set('errorMessage', 'debe ingresar el rif/cédula');
	}else if(empty($this->data['shp950']['fecha_expedicion'])){
		$this->set('errorMessage', 'debe ingresar la fecha de expedicion');
	}else if(empty($this->data['shp950']['valida_hasta'])){
		$this->set('errorMessage', 'debe ingresar la fecha valida hasta');
	}else if(empty($this->data['shp950']['objeto_solvencia'])){
		$this->set('errorMessage', 'seleccione el objeto de la solvencia');
	}else if(empty($this->data['shp950']['monto_solvencia']) || $this->data['shp950']['monto_solvencia']==0){
		$this->set('errorMessage', 'ingrese el monto de la solvencia');
	}else if(empty($this->data['shp950']['radio_formato'])){
		$this->set('errorMessage', 'seleccione en que formato desea emitir la solvencia');
		echo "<script>document.getElementById('ver1').focus();</script>";
	}else{
		$this->set('radio_formato',$this->data['shp950']['radio_formato']);


		$fecha_expedicion     		= $this->data['shp950']['fecha_expedicion'];
	    $paso= explode('/',$fecha_expedicion);
	    $ano=$paso[2];
		$rif_cedula    				= $this->data['shp950']['rif_constribuyente'];
		$numero_solvencia     		= $this->data['shp950']['numero_solvencia'];
		$valida_hasta    			= $this->data['shp950']['valida_hasta'];
		$objeto_solvencia     		= $this->data['shp950']['objeto_solvencia'];
		$monto_solvencia    		= $this->Formato1($this->data['shp950']['monto_solvencia']);
		$fecha_registro=date('Y-m-d');
		$username=$this->Session->read('nom_usuario');

		$impuestos_solventes   		= $this->data['shp950']['impuestos_solventes'];


		if(empty($this->data['shp950']['observaciones'])){
			$campos='(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,numero_solvencia, rif_cedula, fecha_expedicion, valida_hasta,objeto_solvencia, monto_solvencia,fecha_registro, username_registro, fecha_anulacion,solvencia_impuestos)';
			$SQL_INSERT ="BEGIN;INSERT INTO shd950_solvencia ".$campos." VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$ano','$numero_solvencia','$rif_cedula', '$fecha_expedicion','$valida_hasta' ,'$objeto_solvencia' ,'$monto_solvencia','$fecha_registro','$username','1900-01-01','$impuestos_solventes' )";
		}else{
			$campos='(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano,numero_solvencia, rif_cedula, fecha_expedicion, valida_hasta,objeto_solvencia, monto_solvencia, observaciones,fecha_registro, username_registro, fecha_anulacion,solvencia_impuestos)';
			$observaciones     			= $this->data['shp950']['observaciones'];
			$SQL_INSERT ="BEGIN;INSERT INTO shd950_solvencia ".$campos." VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$ano','$numero_solvencia','$rif_cedula', '$fecha_expedicion','$valida_hasta' ,'$objeto_solvencia' ,'$monto_solvencia' ,'$observaciones','$fecha_registro','$username','1900-01-01','$impuestos_solventes')";
		}
//		$a=$this->shd950_solvencia->execute("select * from shd900_planillas_deuda_cobro_detalles where ".$this->SQLCA()." and rif_cedula='".$rif_cedula."' and cancelado=2");
//		if($a==null){
//			$SQL_INSERT ="BEGIN;INSERT INTO shd950_solvencia VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$ano','$numero_solvencia','$rif_cedula', '$fecha_expedicion','$valida_hasta' ,'$objeto_solvencia' ,'$monto_solvencia' ,'$observaciones')";
			$sw = $this->shd950_solvencia->execute($SQL_INSERT);
			$this->shd950_solvencia_numero->execute("UPDATE  shd950_solvencia_numero SET situacion=3 WHERE ".$this->SQLCA()."  and ano=".$ano." and numero_solvencia=".$numero_solvencia." and situacion=2");


			if($sw>1){
				$this->shd950_solvencia_numero->execute("COMMIT");
				$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
				$this->set('guardado', 'si');
				$this->set('ano',$ano);
				$this->set('numero',$numero_solvencia);
				$this->set('Rif',$rif_cedula);

	//			$this->index();
	//			$this->render('index');
			}else{
				$this->shd950_solvencia_numero->execute("ROLLBACK");
				$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
			}

	/*}else{
		$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - el contribuyente no se encuentra solvente');
	}*/

	}



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


function numero($var=null){
	$this->layout = "ajax";
if($var!=''){
	$num=$this->v_shd950_solvencias->generateList($this->SQLCA()." and ano=".$var,'numero_solvencia ASC', null, '{n}.v_shd950_solvencias.numero_solvencia', '{n}.v_shd950_solvencias.razon_social_nombres');
	if($num!=null){
		$this->concatena_seis_digitos($num,'numero');
//		$this->set('numero',$num);
	}else{
		$this->set('numero',null);
	}

}else{
	$this->set('numero',null);

}

}

function pre_busqueda(){
	$this->layout = "ajax";
	$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
	if($ver!=null){
		$this->set('ano1',$ver[0][0]['ano_arranque']);
		$this->set('mes',$ver[0][0]['mes_arranque']);
	}else{
		$this->set('ano1','');
		$this->set('mes','');
	}

 	$datos  = $this->shd950_solvencia->execute(" SELECT DISTINCT ano FROM shd950_solvencia WHERE ".$this->condicion()." ORDER BY ano ASC");
	if(count($datos)!=0){
		foreach($datos as $n){
			$lista[$n[0]['ano']]=$n[0]['ano'];
	    }
	}else{
		$lista=array('0'=>'No existen datos');
	}
	$this->set("lista_ano", $lista);

}



function anulacion($n=null,$ano=null,$pagina=null,$numero=null){
	$this->layout = "ajax";

	$fecha_anulacion=date('Y-m-d');
	$username=$this->Session->read('nom_usuario');
	$ver=$this->shd950_solvencia->execute("BEGIN;update shd950_solvencia_numero set situacion=4 where ".$this->SQLCA()." and ano=".$ano." and numero_solvencia=".$numero);
	$ver2=$this->shd950_solvencia->execute("update shd950_solvencia set condicion_actividad=2,fecha_anulacion='".$fecha_anulacion."',username_anulacion='$username' where ".$this->SQLCA()." and ano=".$ano." and numero_solvencia=".$numero);

	if($ver>0 && $ver2>0){
		$this->shd950_solvencia->execute('COMMIT');
		$this->set('Message_existe', 'SOLVENCIA ANULADA');
		if($n==1){
			echo" <script> ver_documento('/shp950_solvencia/consultar/$ano/$pagina','principal'); </script>";
		}else if($n==2){
			echo" <script> ver_documento('/shp950_solvencia/busqueda/$ano/1/$numero','principal'); </script>";
		}else{
			echo" <script> ver_documento('/shp950_solvencia/consultar3/$ano/1/$numero','principal'); </script>";
		}
	}else{
		$this->shd950_solvencia->execute('ROLLBACK');
		$this->set('errorMessage', 'FALLO LA ANULACIÓN');
	}



}



function busqueda($ano=null,$pagina=null,$numero=null){//echo 'si llego';
 		$this->layout = "ajax";
//pr($this->data);
 	if(!empty($this->data['shp500']['ano'])){
 		$sql=$this->SQLCA()." and ano=".$this->data['shp500']['ano'];
		$ano=$this->data['shp500']['ano'];
 		if(!empty($this->data['shp500']['numero'])){
	 		$sql.=" and numero_solvencia=".$this->data['shp500']['numero'];
	 	}
 	}else{
 		$sql=$this->SQLCA()." and ano=".$ano;
 		if(isset($numero)){
			$sql.=" and numero_solvencia=".$numero;
 		}
 	}




	$this->set('ano',$ano);
 		if(isset($pagina)){
		$Tfilas=$this->shd950_solvencia->findCount($sql);
        if($Tfilas!=0){
        	$x=$this->shd950_solvencia->findAll($sql,null,'numero_solvencia ASC',1,$pagina,null);
            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->shd950_solvencia->findCount($sql);

        if($Tfilas!=0){
        	$x=$this->shd950_solvencia->findAll($sql,null,'numero_solvencia ASC',1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$this->set('x',$x);
	$rif= $x[0]["shd950_solvencia"]["rif_cedula"];

	$datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$rif."'");
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

}




function pre_busqueda2(){
	$this->layout = "ajax";
	$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
	if($ver!=null){
		$this->set('ano1',$ver[0][0]['ano_arranque']);
		$this->set('mes',$ver[0][0]['mes_arranque']);
	}else{
		$this->set('ano1','');
		$this->set('mes','');
	}

 	$datos  = $this->shd950_solvencia->execute(" SELECT DISTINCT ano FROM shd950_solvencia WHERE ".$this->condicion()." ORDER BY ano ASC");
	if(count($datos)!=0){
		foreach($datos as $n){
			$lista[$n[0]['ano']]=$n[0]['ano'];
	    }
	}else{
		$lista=array('0'=>'No existen datos');
	}
	$this->set("lista_ano", $lista);

}



function consultar3($ano=null,$pagina=null,$numero=null){//echo 'si llego';
 		$this->layout = "ajax";
//pr($this->data);
 	if(!empty($this->data['shp500']['ano'])){
 		$sql=$this->SQLCA()." and ano=".$this->data['shp500']['ano'];
		$ano=$this->data['shp500']['ano'];
 		if(!empty($this->data['shp500']['numero'])){
	 		$sql.=" and numero_solvencia=".$this->data['shp500']['numero'];
	 	}
 	}else{
 		$sql=$this->SQLCA()." and ano=".$ano;
 		if(isset($numero)){
			$sql.=" and numero_solvencia=".$numero;
 		}
 	}




	$this->set('ano',$ano);
 		if(isset($pagina)){
		$Tfilas=$this->shd950_solvencia->findCount($sql);
        if($Tfilas!=0){
        	$x=$this->shd950_solvencia->findAll($sql,null,'numero_solvencia ASC',1,$pagina,null);
            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->shd950_solvencia->findCount($sql);

        if($Tfilas!=0){
        	$x=$this->shd950_solvencia->findAll($sql,null,'numero_solvencia ASC',1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$this->set('x',$x);
	$rif= $x[0]["shd950_solvencia"]["rif_cedula"];

	$datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$rif."'");
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

}



function pre_consulta(){
	$this->layout = "ajax";
	$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
	if($ver!=null){
		$this->set('ano1',$ver[0][0]['ano_arranque']);
		$this->set('mes',$ver[0][0]['mes_arranque']);
	}else{
		$this->set('ano1','');
		$this->set('mes','');
	}

 	$datos  = $this->shd950_solvencia->execute(" SELECT DISTINCT ano FROM shd950_solvencia WHERE ".$this->condicion()." ORDER BY ano ASC");
	if(count($datos)!=0){
		foreach($datos as $n){
			$lista[$n[0]['ano']]=$n[0]['ano'];
	    }
	}else{
		$lista=array('0'=>'No existen datos');
	}
	$this->set("lista_ano", $lista);


}

function consultar($ano=null,$pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
 	if(isset($this->data['shp500']['ano'])){
 		$ano=$this->data['shp500']['ano'];
 	}

	$this->set('ano',$ano);
 		if(isset($pagina)){
		$Tfilas=$this->shd950_solvencia->findCount($this->SQLCA()." and ano=".$ano);
        if($Tfilas!=0){
        	$x=$this->shd950_solvencia->findAll($this->SQLCA()." and ano=".$ano,null,'numero_solvencia ASC',1,$pagina,null);
            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->shd950_solvencia->findCount($this->SQLCA()." and ano=".$ano);

        if($Tfilas!=0){
        	$x=$this->shd950_solvencia->findAll($this->SQLCA()." and ano=".$ano,null,'numero_solvencia ASC',1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$this->set('x',$x);
	$rif= $x[0]["shd950_solvencia"]["rif_cedula"];

	$datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$rif."'");
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

}


function planilla($ano=null,$solvencia=null,$rif=null){
	$this->layout="pdf";
	$ano=$this->data['planilla']['ano'];
	$solvencia=$this->data['planilla']['numero_solvencia'];
	$rif=$this->data['planilla']['rif'];
	$radio_formato=$this->data['planilla']['radio_formato'];
	$this->Session->write('radio_formato1',$radio_formato);
//	pr($this->data);
	 $sql=$this->SQLCA()." and h.ano=".$ano." and h.numero_solvencia=".$solvencia." and h.rif_cedula=a.rif_cedula";
	 $rs=$this->shd001_registro_contribuyentes->execute("
                      SELECT
                          a.rif_cedula,
						  a.personalidad_juridica,
						  a.razon_social_nombres,
						  a.fecha_inscripcion,
						  a.nacionalidad,
						  a.estado_civil,
						  a.profesion,
						  a.cod_pais ,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro_poblado,
						  a.cod_calle_avenida,
						  a.cod_vereda_edificio,
						  a.numero_vivienda_local,
						  a.telefonos_fijos,
						  a.telefonos_celulares,
						  a.correo_electronico,
						  h.ano,
						  h.numero_solvencia,
						  h.fecha_expedicion,
						  h.valida_hasta,
						  h.objeto_solvencia,
						  h.monto_solvencia,
						  h.observaciones,
						 (SELECT xye.denominacion FROM cugd01_republica  xye where xye.cod_republica=a.cod_pais GROUP BY xye.denominacion) as  deno_cod_republica,
                         (SELECT xya.denominacion FROM cugd01_estados  xya where xya.cod_republica=a.cod_pais and xya.cod_estado=a.cod_estado GROUP BY xya.denominacion) as  deno_cod_estado,
                         (SELECT xyb.denominacion FROM cugd01_municipios  xyb where xyb.cod_republica=a.cod_pais and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio GROUP BY xyb.denominacion) as  deno_cod_municipio,
                         (SELECT xyb.conocido FROM cugd01_municipios  xyb where xyb.cod_republica=a.cod_pais and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio GROUP BY xyb.conocido) as  deno_conocido,
						 (SELECT xyc.denominacion FROM cugd01_parroquias   xyc where xyc.cod_republica=a.cod_pais and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						 (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_pais and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro_poblado GROUP BY xyd.denominacion) as  deno_cod_centro,
						 (SELECT xyf.denominacion FROM cugd01_vialidad xyf where xyf.cod_republica=a.cod_pais and xyf.cod_estado=a.cod_estado  and xyf.cod_municipio=a.cod_municipio and xyf.cod_parroquia = a.cod_parroquia and xyf.cod_centro = a.cod_centro_poblado and xyf.cod_vialidad=a.cod_calle_avenida GROUP BY xyf.denominacion) as  deno_cod_vialidad,
						 (SELECT xyg.denominacion FROM cugd01_vereda xyg where xyg.cod_republica=a.cod_pais and xyg.cod_estado=a.cod_estado  and xyg.cod_municipio=a.cod_municipio and xyg.cod_parroquia = a.cod_parroquia and xyg.cod_centro = a.cod_centro_poblado and xyg.cod_vialidad=a.cod_calle_avenida and xyg.cod_vereda=a.cod_vereda_edificio GROUP BY xyg.denominacion) as  deno_cod_vereda

                       FROM
                          shd001_registro_contribuyentes a,shd950_solvencia h where

                       ".$sql."



");


	$this->set('datos',$rs);


}





function modificar($n=null,$ano=null,$numero=null,$pagina=null){
	$this->layout = "ajax";
	$x=$this->shd950_solvencia->findAll($this->SQLCA()." and ano=".$ano." and numero_solvencia=".$numero,null,'numero_solvencia ASC',1,null,null);

	$this->set('n',$n);
	$rif= $x[0]["shd950_solvencia"]["rif_cedula"];
	$this->set('x',$x);
	$this->set('pagina',$pagina);
	$datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$rif."'");
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

	$select=array('1'=>'SOLICITUD DE PATENTE DE INDUSTRIA Y COMERCIO','2'=>'RENOVACIÓN DE CARTA DE PATENTE','3'=>'DECLARACIÓN JURADA DE INGRESOS BRUTOS',
				  '4'=>'PERMISOS DE CONSTRUCCIÓN','5'=>'VARIABLES URBANAS','6'=>'REPARACIÓN O MEJORAS DE INMUBLES','7'=>'PROTOCOLIZACIÓN DE DOCUMENTOS',
				  '8'=>'REGULACIÓN O DESOCUPACIÓN DE INMUEBLE','9'=>'SOLICITUD DE CRÉDITO A ENTIDAD FINANCIERA','10'=>'ESPECTACULOS PÚBLICOS','11'=>'CONSTITUCIÓN DE EMPRESAS',
				  '12'=>'LICITACIONES','14'=>'TRÁMITES ADMINISTRATIVOS','13'=>'OTROS');
	$this->set('select_solvencia',$select);

}

function guardar_modificar($n=null,$ano=null,$numero=null,$rif_cedula=null,$pagina=null){
	$this->layout = "ajax";

	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	if(empty($this->data['shp950']['fecha_expedicion'])){
		$this->set('errorMessage', 'debe ingresar la fecha de expedicion');
	}else if(empty($this->data['shp950']['valida_hasta'])){
		$this->set('errorMessage', 'debe ingresar la fecha valida hasta');
	}else if(empty($this->data['shp950']['objeto_solvencia'])){
		$this->set('errorMessage', 'seleccione el objeto de la solvencia');
	}else if(empty($this->data['shp950']['monto_solvencia']) || $this->data['shp950']['monto_solvencia']==0){
		$this->set('errorMessage', 'ingrese el monto de la solvencia');
	}else{


		$fecha_expedicion     		= $this->data['shp950']['fecha_expedicion'];
	    $paso= explode('/',$fecha_expedicion);
	    $ano1=$paso[2];
		$objeto_solvencia     		= $this->data['shp950']['objeto_solvencia'];
		$monto_solvencia    		= $this->Formato1($this->data['shp950']['monto_solvencia']);
		$fecha_hasta     			= $this->data['shp950']['valida_hasta'];

		if(empty($this->data['shp950']['observaciones'])){
			$SQL_INSERT ="UPDATE shd950_solvencia SET ano='$ano1',fecha_expedicion='".$fecha_expedicion."',valida_hasta='".$fecha_hasta."',objeto_solvencia='$objeto_solvencia',monto_solvencia='$monto_solvencia',observaciones=null WHERE ".$this->SQLCA()." and ano=".$ano." and numero_solvencia=".$numero;
		}else{
			$observaciones     			= $this->data['shp950']['observaciones'];
			$SQL_INSERT ="UPDATE shd950_solvencia SET ano='$ano1',fecha_expedicion='".$fecha_expedicion."',valida_hasta='".$fecha_hasta."',objeto_solvencia='$objeto_solvencia',monto_solvencia='$monto_solvencia',observaciones='$observaciones' WHERE ".$this->SQLCA()." and ano=".$ano." and numero_solvencia=".$numero;
		}



//		$SQL_INSERT ="UPDATE shd950_solvencia SET fecha_expedicion='".$fecha_expedicion."',valida_hasta='".$fecha_hasta."',objeto_solvencia='$objeto_solvencia',monto_solvencia='$monto_solvencia',observaciones='$observaciones' WHERE ".$this->SQLCA()." and ano=".$ano." and numero_solvencia=".$numero;
		$sw = $this->shd950_solvencia->execute($SQL_INSERT);
		if($sw>1){
			$this->shd950_solvencia_numero->execute("COMMIT");
			$this->set('Message_existe', 'LOS DATOS FUERON MODIFICADOS');
			if($n==1){
				$render="consultar";
				echo" <script> ver_documento('/shp950_solvencia/consultar/$ano/$pagina','principal'); </script>";
			}else{
				$render="busqueda";
				echo" <script> ver_documento('/shp950_solvencia/busqueda/$ano1/1/$numero','principal'); </script>";
			}
//			echo" <script> ver_documento('/shp950_solvencia/$render/$ano/$pagina','principal'); </script>";
		}else{
			$this->shd950_solvencia_numero->execute("ROLLBACK");
			$this->set('errorMessage', 'LOS DATOS NO FUERON MODIFICADOS - POR FAVOR INTENTE DE NUEVO');
		}

	}


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


function salir_solvencia($num_rc=null){
	$this->layout="ajax";
	//$ano=$this->ano_ejecucion();
	$ano = $this->shd000_arranque->ano($this->SQLCA());
	 $this->set('year', $ano);
	 $this->Session->write('year_pago',$ano);
	$resultado=$this->shd950_solvencia_numero->execute("UPDATE  shd950_solvencia_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_solvencia=".$num_rc." and ano=".$ano." and situacion=2");
	//$this->('index');
}


}