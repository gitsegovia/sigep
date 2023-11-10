<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Shp100DeclaracionIngresosController extends AppController {
   var $name = 'shp100_declaracion_ingresos';
   var $uses = array('shd100_patente_actividades','v_shd100_patente_actividades','shd100_patente','shd100_solicitud','v_shd100_declaracion_actividades','cscd04_ordencompra_parametros','v_shd100_patente','v_shd100_solicitud','shd001_registro_contribuyentes', 'shd100_patente','shd100_declaracion_actividades','v_shd100_declaracion_ingreso',
					'shd100_declaracion_ingresos','shd100_actividades','v_shd100_declaracion_ingresos');//v_shd100_declaracion_ingreso_2
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');
//'cnmd10_bolivares_asig',
function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }

 function beforeFilter(){
 	$this->checkSession();

 }





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










 function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  return $condicion;

}

function index(){
	$this->layout="ajax";
	$this->data=null;
	$this->concatena_sin_cero($this->shd100_actividades->generateList($this->SQLCA().' and minimo_tributable !=0', "cod_actividad ASC", null, '{n}.shd100_actividades.cod_actividad', '{n}.shd100_actividades.denominacion_actividad'), "actividad");
	$this->Session->delete("DATOS");
}
function buscar_constribuyente($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function grilla($var1=null){
	$this->layout='ajax';
	$acti  = $this->v_shd100_patente_actividades->findAll("rif_cedula='".$var1."'",null,'cod_actividad ASC');
	$num_acti = $this->v_shd100_patente_actividades->findCount("rif_cedula='".$var1."'");
	$this->Session->write('conta_acti', $num_acti);
	$this->Session->write('rif_cedula', $var1);
	$this->set('acti',$acti);
}


function seleccion_busqueda_venta($var1=null){
$this->layout="ajax";
$datos = $this->v_shd100_solicitud->findAll("rif_cedula='".$var1."' and numero_patente!='0'");
$decla = $this->shd100_declaracion_ingresos->findAll("rif_cedula='".$var1."'",array('numero_declaracion'),'numero_declaracion DESC',1,1,null);
if($decla==null){
     		$new_numero=1;
     	}else{
     		$new_numero=$decla[0]["shd100_declaracion_ingresos"]["numero_declaracion"]+1;
     	}
if($datos != null){
	$this->set('datos',$datos);
	$this->set('datos',$datos);

					echo "<script>";
						echo "document.getElementById('numero_solicitud').value='".$datos[0]['v_shd100_solicitud']['numero_solicitud']."';   ";
						echo "document.getElementById('fecha_solicitud').value='".cambiar_formato_fecha($datos[0]['v_shd100_solicitud']['fecha_solicitud'])."';   ";
					    echo "document.getElementById('deno_rif').value='".$datos[0]['v_shd100_solicitud']['razon_social_nombres']."';   ";
					    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['v_shd100_solicitud']['rif_cedula']."';   ";
					    echo "document.getElementById('numero_declaracion').value='".mascara_seis($new_numero)."';   ";
					echo "</script>";
}else{
	$vacio='';
					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$vacio."';   ";
						echo "document.getElementById('numero_solicitud').value='".$vacio."';   ";
						echo "document.getElementById('fecha_solicitud').value='".$vacio."';   ";
						echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
						echo "document.getElementById('numero_declaracion').value='';   ";
					echo "</script>";
}

}//fin function

function patente($var1=null){
$this->layout="ajax";
$datos=$this->v_shd100_patente->findAll("rif_cedula='".$var1."'");
	if($datos != null){//echo 'si';
	$this->set('datos',$datos);//pr($datos);
}
}

function empresa($var1=null){//echo 'hola';
$this->layout="ajax";
$datos=$this->v_shd100_solicitud->findAll("rif_cedula='".$var1."' and numero_patente!='0'");
	if($datos != null){
	$this->set('datos',$datos);
}

}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_shd100_solicitud->findCount("(((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))) and numero_patente!= '0')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd100_solicitud->findAll("(((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))) and numero_patente!= '0') ",null,"rif_cedula ASC",50,1,null);
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
						$Tfilas=$this->v_shd100_solicitud->findCount("(((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%'))) and numero_patente!= '0')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd100_solicitud->findAll("(((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%'))) and numero_patente!= '0')",null,"rif_cedula ASC",50,$pagina,null);
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

function actividad($var=null){
	$this->layout="ajax";
	$datos=$this->shd100_actividades->findAll("cod_actividad='".$var."' and ".$this->SQLCA());
	$this->set('datos',$datos);
	$datos2=$this->cscd04_ordencompra_parametros->findAll($this->SQLCA());
	$this->set('datos2',$datos2);
	$numero_unidades = $datos[0]['shd100_actividades']['unidades_tributarias'];
	$alicuota 		 = $datos[0]['shd100_actividades']['alicuota'];
	$valor_unidad = $datos2[0]['cscd04_ordencompra_parametros']['unidad_tributaria'];
	$mut = $numero_unidades * $valor_unidad;
	$this->set('mut',$mut);
					echo "<script>";
						echo "document.getElementById('alicuota2').value='".$alicuota."';   ";
						echo "document.getElementById('ingresos').disabled=false;   ";
					echo "</script>";

}

function agregar_grilla($i){

$this->layout = "ajax";
   	$cod_actividad		= $this->data['shp100_declaracion_ingresos']['cod_actividad_'.$i];
   	$alicuota		    = $this->data['shp100_declaracion_ingresos']['alicuota_'.$i];
   	$ingresos       	= $this->data['shp100_declaracion_ingresos']['ingresos_'.$i];
   	$impuestos    		= $this->data['shp100_declaracion_ingresos']['impuestos_'.$i];
	$deno				= $this->shd100_actividades->findAll("cod_actividad='".$cod_actividad."' and ".$this->SQLCA());

if($ingresos ==null){

	$this->set('xxx',false);
	$num_acti = $this->Session->read('conta_acti');
	$this->set('conta_acti',$num_acti);
	$this->set("accion", $_SESSION["DATOS"]);
	$this->set("errorMessage", "INSERTE EL MONTO DE INGRESOS");
}else{


			if(!isset($_SESSION["DATOS"])){
	              $_SESSION["CUENTA"] = 1;
	              $cont               = $_SESSION["CUENTA"];
	              $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              $_SESSION["DATOS"][$cont]["deno_actividad"]    = $deno[0]['shd100_actividades']['denominacion_actividad'];
	              $_SESSION["DATOS"][$cont]["ingresos"]       = $ingresos;
	              $_SESSION["DATOS"][$cont]["impuestos"]    = $impuestos;
	              $_SESSION["DATOS"][$cont]["alicuota"]    = $alicuota;
	              $_SESSION["DATOS"][$cont]["activa"]           = 1;
	              $_SESSION["DATOS"][$cont]["id"]               = $cont;
	              $num_acti = $this->Session->read('conta_acti');
				  $num_acti = $num_acti - 1;
				  $this->Session->write('conta_acti', $num_acti);
				  $this->set('conta_acti',$num_acti);
	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{

                  $cont  = $_SESSION["CUENTA"];
                  $marca = 0;

		              for($i=1; $i<=$cont; $i++){
		                   if($cod_actividad==$_SESSION["DATOS"][$i]["cod_actividad"]  &&  $_SESSION["DATOS"][$i]["activa"]==1){
                               $marca=1;
		                   }//fin if
		              }//fin for
	                 if($marca==1){
	                 	$num_acti = $this->Session->read('conta_acti');
						$this->set('conta_acti',$num_acti);
                           $this->set("errorMessage", "EL REGISTRO YA EXISTE");
	                 }else{
                            $cont = $_SESSION["CUENTA"];  $cont++; $_SESSION["CUENTA"] = $cont;
                            $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              			$_SESSION["DATOS"][$cont]["deno_actividad"]    = $deno[0]['shd100_actividades']['denominacion_actividad'];
	              			$_SESSION["DATOS"][$cont]["ingresos"]       = $ingresos;
	              			$_SESSION["DATOS"][$cont]["impuestos"]    = $impuestos;
	              			$_SESSION["DATOS"][$cont]["alicuota"]    = $alicuota;
				            $_SESSION["DATOS"][$cont]["activa"]           = 1;
				            $_SESSION["DATOS"][$cont]["id"]               = $cont;
				            $num_acti = $this->Session->read('conta_acti');
				  			$num_acti = $num_acti - 1;
				  			$this->Session->write('conta_acti', $num_acti);
				  			$this->set('conta_acti',$num_acti);
                           $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	                 }//fin else
			}//fin else

echo'<script>';
       echo" document.getElementById('cod_actividad').value     = ''; ";
       echo" document.getElementById('alicuota2').value     = ''; ";
       echo" document.getElementById('activ_deno').value           = ''; ";
       echo" document.getElementById('ingresos').value           = ''; ";
       echo" document.getElementById('impuestos').value           = ''; ";
       echo" document.getElementById('ingresos').disabled           = true; ";
echo'</script>';

$this->set('i',$i);
$this->set("accion", $_SESSION["DATOS"]);
	$this->set('xxx',true);
}

}//fin function




function agregar_grilla2(){

$this->layout = "ajax";
   	$cod_actividad		= $this->data['shp100_declaracion_ingresos']['cod_actividad'];
   	$alicuota		    = $this->data['shp100_declaracion_ingresos']['alicuota'];
   	$ingresos       	= $this->data['shp100_declaracion_ingresos']['ingresos'];
   	$impuestos    		= $this->data['shp100_declaracion_ingresos']['impuestos'];
	$deno				= $this->shd100_actividades->findAll("cod_actividad='".$cod_actividad."' and ".$this->SQLCA());

			if(!isset($_SESSION["DATOS"])){
	              $_SESSION["CUENTA"] = 1;
	              $cont               = $_SESSION["CUENTA"];
	              $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              $_SESSION["DATOS"][$cont]["deno_actividad"]    = $deno[0]['shd100_actividades']['denominacion_actividad'];
	              $_SESSION["DATOS"][$cont]["ingresos"]       = $ingresos;
	              $_SESSION["DATOS"][$cont]["impuestos"]    = $impuestos;
	              $_SESSION["DATOS"][$cont]["alicuota"]    = $alicuota;
	              $_SESSION["DATOS"][$cont]["activa"]           = 1;
	              $_SESSION["DATOS"][$cont]["id"]               = $cont;
	              $num_acti = $this->Session->read('conta_acti');
				  $num_acti = $num_acti - 1;
				  $this->Session->write('conta_acti', $num_acti);
				  $this->set('conta_acti',$num_acti);
	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{

                  $cont  = $_SESSION["CUENTA"];
                  $marca = 0;

		              for($i=1; $i<=$cont; $i++){
		                   if($cod_actividad==$_SESSION["DATOS"][$i]["cod_actividad"]  &&  $_SESSION["DATOS"][$i]["activa"]==1){
                               $marca=1;
		                   }//fin if
		              }//fin for
	                 if($marca==1){
	                 	$num_acti = $this->Session->read('conta_acti');
						$this->set('conta_acti',$num_acti);
                           $this->set("errorMessage", "EL REGISTRO YA EXISTE");
	                 }else{
                            $cont = $_SESSION["CUENTA"];  $cont++; $_SESSION["CUENTA"] = $cont;
                            $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              			$_SESSION["DATOS"][$cont]["deno_actividad"]    = $deno[0]['shd100_actividades']['denominacion_actividad'];
	              			$_SESSION["DATOS"][$cont]["ingresos"]       = $ingresos;
	              			$_SESSION["DATOS"][$cont]["impuestos"]    = $impuestos;
	              			$_SESSION["DATOS"][$cont]["alicuota"]    = $alicuota;
				            $_SESSION["DATOS"][$cont]["activa"]           = 1;
				            $_SESSION["DATOS"][$cont]["id"]               = $cont;
				            $num_acti = $this->Session->read('conta_acti');
				  			$num_acti = $num_acti - 1;
				  			$this->Session->write('conta_acti', $num_acti);
				  			$this->set('conta_acti',$num_acti);
                           $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	                 }//fin else
			}//fin else

echo'<script>';
       echo" document.getElementById('cod_actividad').value     = ''; ";
       echo" document.getElementById('alicuota2').value     = ''; ";
       echo" document.getElementById('activ_deno').value           = ''; ";
       echo" document.getElementById('ingresos').value           = ''; ";
       echo" document.getElementById('impuestos').value           = ''; ";
       echo" document.getElementById('ingresos').disabled           = true; ";
echo'</script>';



$this->set('i',$i);
$this->set("accion", $_SESSION["DATOS"]);


}//fin function








function eliminar_grilla($var1=null,$cod_actividad=null){
$this->layout = "ajax";
$rif_cedula = $this->Session->read('rif_cedula');
$cond=$this->SQLCA()." and rif_cedula='".$rif_cedula."' and cod_actividad='".$cod_actividad."'";
$this->shd100_patente_actividades->execute("DELETE FROM shd100_patente_actividades  WHERE ".$cond);
$_SESSION["DATOS"][$var1]["activa"] = 0;
$this->set("errorMessage", "EL REGISTRO FUE ELIMINADO");

$cont  = $_SESSION["CUENTA"];
$marca = 0;

 for($i=1; $i<=$cont; $i++){
    if($_SESSION["DATOS"][$i]["activa"]==1){
       $marca++;
   }//fin if
 }//fin for

$this->set("accion", $_SESSION["DATOS"]);
}//fin function

function funcion($var=null){$this->layout = "ajax";}//fin index

function guardar(){
	$this->layout = "ajax";//pr($this->data);

	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$numero_solicitud			= $this->data['shp100_declaracion_ingresos']['numero_solicitud'];
	$rif_cedula					= $this->data['shp100_declaracion_ingresos']['rif_constribuyente'];
  	$numero_patente				= $this->data['shp100_declaracion_ingresos']['numero_patente'];
  	$numero_declaracion			= $this->data['shp100_declaracion_ingresos']['numero_declaracion'];
  	$periodo_desde				= $this->data['shp100_declaracion_ingresos']['periodo_desde'];
  	$periodo_hasta				= $this->data['shp100_declaracion_ingresos']['periodo_hasta'];
  	$capital					= $this->Formato1($this->data['shp100_declaracion_ingresos']['capital']);
  	$numero_empleados			= $this->data['shp100_declaracion_ingresos']['numero_empleados'];
  	$numero_obreros				= $this->data['shp100_declaracion_ingresos']['numero_obreros'];
  	$fecha_declaracion			= $this->data['shp100_declaracion_ingresos']['fecha_declaracion'];
  	$f							= $fecha_declaracion;
  	$ejercicio_declarado=$f[6].$f[7].$f[8].$f[9];

$reg = $this->v_shd100_declaracion_ingresos->findCount($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano_declaracion=".$ejercicio_declarado);
if($reg==0){

$SQL_INSERT ="INSERT INTO shd100_declaracion_ingresos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula,
  			numero_declaracion, periodo_desde, periodo_hasta, capital, numero_empleados, numero_obreros, fecha_declaracion)";
$SQL_INSERT .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '".$rif_cedula."',
  			'".$numero_declaracion."', '".$periodo_desde."', '".$periodo_hasta."', $capital, $numero_empleados, $numero_obreros, '".$fecha_declaracion."');";
$this->shd100_declaracion_ingresos->execute('BEGIN;');
$sw1 = $this->shd100_declaracion_ingresos->execute($SQL_INSERT);

if($sw1>1){
	$cont  = $_SESSION["CUENTA"];
$mto=0;
$imp=0;
			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["DATOS"][$i]["activa"]==1){
                       $cod_actividad     = $_SESSION["DATOS"][$i]["cod_actividad"];
                       $monto_ingresos     = $this->Formato1($_SESSION["DATOS"][$i]["ingresos"]);
                       $monto_impuesto     = $this->Formato1($_SESSION["DATOS"][$i]["impuestos"]);
                       $alicuota     = $_SESSION["DATOS"][$i]["alicuota"];
                       $mto=$mto + $monto_ingresos;
                       $imp=$imp + $monto_impuesto;
					   $sql ="INSERT INTO shd100_declaracion_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
  						rif_cedula,numero_declaracion, cod_actividad, monto_ingresos, monto_impuesto,alicuota_aplicada)";
					   $sql.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,
  						'".$rif_cedula."', '".$numero_declaracion."', '".$cod_actividad."', $monto_ingresos, $monto_impuesto,$alicuota);";
					   $sw = $this->shd100_declaracion_actividades->execute($sql);
					   $ver=$this->shd100_patente_actividades->findCount("cod_actividad='".$cod_actividad."' and rif_cedula='".$rif_cedula."' and ".$this->SQLCA());
					   if($ver == 0){
					   		$ins ="INSERT INTO shd100_patente_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,rif_cedula, cod_actividad, numero_aforos, monto_aforo_anual, total_aforo_anual)";
					   		$ins.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$rif_cedula."', '".$cod_actividad."',1, $monto_impuesto,$monto_impuesto);";
					   		$this->shd100_declaracion_actividades->execute($ins);
					   }else{
					   	$ins="update shd100_patente_actividades set monto_aforo_anual=$monto_impuesto,total_aforo_anual=$monto_impuesto,numero_aforos=1 where rif_cedula='".$rif_cedula."' and cod_actividad='".$cod_actividad."' and ".$this->SQLCA();
					    $this->shd100_declaracion_actividades->execute($ins);
					   }
					   if($sw>1){}else{break;}
			    }//fin if
			 }//fin for
//echo $imp;
$imp2= $this->Formato2($imp / 12);
$imp2 = $this->Formato1($imp2);
$u1="update shd100_solicitud set capital=$capital,numero_empleado=$numero_empleados, numero_obreros=$numero_obreros where rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."' and ".$this->SQLCA();
$u2="update shd100_patente set fecha_ultima_decla='".$fecha_declaracion."', monto_mensual=$imp2,ingresos_declarados=$mto, periodo_desde='".$periodo_desde."',periodo_hasta='".$periodo_hasta."',ultimo_ejercicio_decla=$ejercicio_declarado where rif_cedula='".$rif_cedula."' and numero_patente='".$numero_patente."' and ".$this->SQLCA();
//echo $u2;
if($sw>1){
$swxx = $this->shd100_solicitud->execute($u1);
if($swxx>1){
	$swyy = $this->shd100_patente->execute($u2);
}
}


}else{
	$swyy=0;
}

		if($swyy>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
			$this->shd100_patente->execute('COMMIT;');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
			$this->shd100_patente->execute('ROLLBACK;');
		}
}else{
	$this->set('errorMessage', 'ESTE CONTRIBUYENTE YA TIENE UNA DECLARACI&Oacute;N REGISTRADA PARA ESTE AÑO');
}
		$this->index();
		$this->render("index");
}

function consultar($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd100_declaracion_ingresos->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd100_declaracion_ingresos->findAll($this->SQLCA(),null,'rif_cedula,numero_declaracion ASC',1,$pagina,null);//pr($datos);
          	 foreach($datos as $row){
          	 	$rif  		= $row['v_shd100_declaracion_ingresos']['rif_cedula'];
          	 	$num_dec  	= $row['v_shd100_declaracion_ingresos']['numero_declaracion'];
          	 }
          	 $Tfilas2=$this->v_shd100_declaracion_actividades->findCount($this->SQLCA()." and rif_cedula='".$rif."' and numero_declaracion='".$num_dec."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd100_declaracion_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif."' and numero_declaracion='".$num_dec."'",null,'cod_actividad ASC');
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
          	 $Tfilas=$this->v_shd100_declaracion_ingresos->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd100_declaracion_ingresos->findAll($this->SQLCA(),null,'rif_cedula,numero_declaracion ASC',1,$pagina,null);
          	 foreach($datos as $row){
          	 	$rif  		= $row['v_shd100_declaracion_ingresos']['rif_cedula'];
          	 	$num_dec  	= $row['v_shd100_declaracion_ingresos']['numero_declaracion'];
          	 }
          	 $Tfilas2=$this->v_shd100_declaracion_actividades->findCount($this->SQLCA()." and rif_cedula='".$rif."' and numero_declaracion='".$num_dec."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd100_declaracion_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif."' and numero_declaracion='".$num_dec."'",null,'cod_actividad ASC');
          	 	$this->set('accion',$accion);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
}//fin function consultar2
}//fin class

function modificar($rif_cedula=null,$num_dec=null,$pagina=null){
	$this->layout = "ajax";
	$datos=$this->v_shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and numero_declaracion='".$num_dec."'",null,'rif_cedula ASC',null,null,null);
	//pr($datos);
	$this->set('datos',$datos);
	foreach($datos as $row){
          	 	$rif  		= $row['v_shd100_declaracion_ingresos']['rif_cedula'];
          	 	$num_dec  	= $row['v_shd100_declaracion_ingresos']['numero_declaracion'];
          	 }
          	  $Tfilas2=$this->v_shd100_declaracion_actividades->findCount($this->SQLCA()." and rif_cedula='".$rif."' and numero_declaracion='".$num_dec."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd100_declaracion_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif."' and numero_declaracion='".$num_dec."'",null,'cod_actividad ASC');
          	 	$this->set('accion',$accion);
          	 }
    $this->set('pagina',$pagina);
	$this->concatena_sin_cero($this->shd100_actividades->generateList($this->SQLCA(), "cod_actividad ASC", null, '{n}.shd100_actividades.cod_actividad', '{n}.shd100_actividades.denominacion_actividad'), "actividad");
}

function agregar_grilla_m($num_sol=null,$num_pat=null,$num_dec=null){//pr($this->data);
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$cod_actividad    = $this->data['shp100_declaracion_ingresos']['cod_actividad'];
   	$ingresos       = $this->Formato1($this->data['shp100_declaracion_ingresos']['ingresos']);
   	$impuestos    = $this->Formato1($this->data['shp100_declaracion_ingresos']['impuestos']);
	$sql ="INSERT INTO shd100_declaracion_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
  			numero_solicitud, numero_patente, numero_declaracion, cod_actividad, monto_ingresos, monto_impuesto)";
	$sql.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,
  		'".$num_sol."', '".$num_pat."', '".$num_dec."', '".$cod_actividad."', $ingresos, $impuestos);";
	$sw = $this->shd100_declaracion_actividades->execute($sql);
 	 $Tfilas2=$this->v_shd100_declaracion_actividades->findCount($this->SQLCA()." and numero_solicitud='".$num_sol."' and numero_patente='".$num_pat."' and numero_declaracion='".$num_dec."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd100_declaracion_actividades->findAll($this->SQLCA()." and numero_solicitud='".$num_sol."' and numero_patente='".$num_pat."' and numero_declaracion='".$num_dec."'",null,'cod_actividad ASC');
          	 	$this->set('accion',$accion);
          	 }
          	 echo'<script>';
       echo" document.getElementById('cod_actividad').value     = ''; ";
       echo" document.getElementById('activ_deno').value           = ''; ";
       echo" document.getElementById('ingresos').value           = ''; ";
       echo" document.getElementById('impuestos').value           = ''; ";
       echo" document.getElementById('ingresos').disabled           = true; ";
			echo'</script>';
}

function eliminar_grilla_m($num_sol=null,$num_pat=null,$num_dec=null,$cod_act=null){
	$this->layout = "ajax";
	$ca=$this->SQLCA();
 	$cond=$this->SQLCA()." and numero_solicitud='".$num_sol."' and numero_patente='".$num_pat."' and cod_actividad='".$cod_act."' and numero_declaracion='".$num_dec."'";
 	$this->shd100_declaracion_actividades->execute("DELETE FROM shd100_declaracion_actividades  WHERE ".$cond);
 	$Tfilas2=$this->v_shd100_declaracion_actividades->findCount($this->SQLCA()." and numero_solicitud='".$num_sol."' and numero_patente='".$num_pat."' and numero_declaracion='".$num_dec."'");
          	 if($Tfilas2 !=0){
          	 	$accion=$this->v_shd100_declaracion_actividades->findAll($this->SQLCA()." and numero_solicitud='".$num_sol."' and numero_patente='".$num_pat."' and numero_declaracion='".$num_dec."'",null,'cod_actividad ASC');
          	 	$this->set('accion',$accion);
          	 }
}
function guardar_modificar($num_sol=null,$num_pat=null,$num_dec=null,$pagina=null){
	$this->layout = "ajax";//pr($this->data);
	$periodo_desde				= $this->data['shp100_declaracion_ingresos']['periodo_desde'];
  	$periodo_hasta				= $this->data['shp100_declaracion_ingresos']['periodo_hasta'];
  	$capital					= $this->Formato1($this->data['shp100_declaracion_ingresos']['capital']);
  	$numero_empleados			= $this->data['shp100_declaracion_ingresos']['numero_empleados'];
  	$numero_obreros				= $this->data['shp100_declaracion_ingresos']['numero_obreros'];
  	$fecha_declaracion			= $this->data['shp100_declaracion_ingresos']['fecha_declaracion'];
	$cond=$this->SQLCA();
	$guardar="update shd100_declaracion_ingresos set periodo_desde='".$periodo_desde."', periodo_hasta='".$periodo_hasta."', capital=$capital, numero_empleados=$numero_empleados, numero_obreros=$numero_obreros, fecha_declaracion='".$fecha_declaracion."' where numero_solicitud='".$num_sol."'
  			and numero_patente='".$num_pat."' and numero_declaracion='".$num_dec."' and $cond";
	$sw = $this->shd100_declaracion_ingresos->execute($guardar);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->consultar($pagina);
		$this->render("consultar");
}
function eliminar($rif=null,$num_dec=null,$pagina=null){
 	$this->layout = "ajax";
 	$cond=$this->SQLCA()." and rif_cedula='".$rif."' and numero_declaracion='".$num_dec."'";
 	$this->shd100_declaracion_ingresos->execute("DELETE FROM shd100_declaracion_ingresos  WHERE ".$cond);
 	$y=$this->shd100_declaracion_ingresos->findCount($this->SQLCA());
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
$this->layout="ajax";//echo 'si2';
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->v_shd100_declaracion_ingresos->findCount($this->SQLCA()." and (((rif_cedula LIKE '%$var2%') or (numero_declaracion LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd100_declaracion_ingresos->findAll($this->SQLCA()." and (((rif_cedula LIKE '%$var2%') or (numero_declaracion LIKE '%$var2%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var2%'))))",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{//echo 'aa';
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->v_shd100_declaracion_ingresos->findCount($this->SQLCA()." and (((rif_cedula LIKE '%$var22%') or (numero_declaracion LIKE '%$var22%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var22%'))))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
							        $datos_filas=$this->v_shd100_declaracion_ingresos->findAll($this->SQLCA()." and (((rif_cedula LIKE '%$var22%') or (numero_declaracion LIKE '%$var22%') or (quitar_acentos(nombre_razon) LIKE quitar_acentos('%$var22%'))))",null,"rif_cedula ASC",50,$pagina,null);
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


function consulta2($rif_cedula=null,$numero_declaracion=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$rif_cedula."' and numero_declaracion='".$numero_declaracion."'";
    $veri=$this->v_shd100_declaracion_ingresos->findCount($this->SQLCA().' and '.$c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd100_declaracion_ingresos->findAll($this->SQLCA().' and '.$c);
      	$this->set('datos',$datacpcp01);
      	$datos2=$this->v_shd100_declaracion_actividades->findAll($this->SQLCA().' and '.$c,null,'numero_declaracion ASC',null,null,null);
    //  	pr($datos2);
        $this->set('accion',$datos2);
      }else{
	  			$this->index();
				$this->render("index");
          	 }
}//fin function consultar2


}
?>