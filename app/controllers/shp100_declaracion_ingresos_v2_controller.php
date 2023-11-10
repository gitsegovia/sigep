<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Shp100DeclaracionIngresosV2Controller extends AppController {
   var $name = 'shp100_declaracion_ingresos_v2';
   var $uses = array('shd100_patente_actividades','shd000_arranque','shd100_declaracion_numero','v_shd100_patente_actividades','shd100_patente','shd100_solicitud','v_shd100_declaracion_actividades','cscd04_ordencompra_parametros','v_shd100_patente','v_shd100_solicitud','shd001_registro_contribuyentes', 'shd100_patente','shd100_declaracion_actividades','v_shd100_declaracion_ingreso',
					'shd100_declaracion_ingresos','v_shd100_ingresos_rif','shd100_actividades','v_shd100_declaracion_ingresos');//v_shd100_declaracion_ingreso_2
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
function index($guardado=array()){///////////////<<--INDEX
	 $this->layout = "ajax";
	 //$ano=$this->ano_ejecucion();
	 $ano = $this->shd000_arranque->ano($this->SQLCA());
     $maxi=$this->shd100_declaracion_numero->findCount($this->SQLCA()." and ano_declaracion=".$ano." and situacion=1");
      if($maxi==0){
         $this->set("errorMessage","Verifique el n&uacute;mero de control de declaraciones de ingresos");
      	 $this->set("numero_compromiso","");
      	 $this->redirect("/shp100_declaracion_numero/");
      }
      if(count($guardado)>0){
           echo "<script>window.location='/shp100_declaracion_ingresos_v2/recibo/".$guardado[0]."/".$guardado[1]."/".$guardado[2]."'</script>";
	 }
}//fin index

function index2(){
	$this->layout="ajax";
	$this->data=null;
	$this->concatena_sin_cero($this->shd100_actividades->generateList($this->SQLCA().' and minimo_tributable !=0', "cod_actividad ASC", null, '{n}.shd100_actividades.cod_actividad', '{n}.shd100_actividades.denominacion_actividad'), "actividad");
	$this->Session->delete("DATOS");
	$this->Session->delete('rif_cedula');
	$ano = $this->shd000_arranque->ano($this->SQLCA());
	$numero_declaracion=$this->shd100_declaracion_numero->field('shd100_declaracion_numero.numero_declaracion', $this->SQLCA()." and ano_declaracion='$ano' and situacion=1", $order ="numero_declaracion ASC");
    if(!empty($numero_declaracion)){
  		$this->set('numero_declaracion',$numero_declaracion);
  		$this->set('ano_declaracion',          $ano);
  		$this->shd100_declaracion_numero->execute('UPDATE shd100_declaracion_numero set situacion=2 WHERE '.$this->SQLCA()." and ano_declaracion='$ano' and numero_declaracion = '$numero_declaracion'");
    }else{
  		$this->set('msg_error1', $msg_error1 = 'NECESITA CREAR LOS NÚMEROS DE DECLARACIÓN DE INGRESOS PARA CONTINUAR');
  		$this->redirect('/shp100_declaracion_numero');
	    return;
    }
}
function buscar_constribuyente($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_actividades($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista_actividad');
}//fin function

function buscar_pista_actividad ($pista,$pagina=null) {
   $this->layout="ajax";
   //$d=$this->shd100_actividades->findAll($this->SQLCA()." and minimo_tributable !=0 and denominacion_actividad ILIKE '%$pista%'",null,  "cod_actividad ASC");
   //$this->set('data_actividad',$d);
   $this->set('pista',$pista);
   if(!isset($pagina)){
   	  $pagina = 1;
   }else{
   	 if($pagina==null){
   	 	$pagina=1;
   	 }else{
   	 	$pagina=$pagina;
   	 }

   }
					//$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
	$pista = strtoupper($pista);
	$Tfilas=$this->shd100_actividades->findCount($this->SQLCA()." and minimo_tributable !=0 and  quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$pista%')");
    if($Tfilas!=0){
    	$ca_reg = 50;
    	$Tfilas=(int)ceil($Tfilas/$ca_reg);
    	$this->set('pag_cant',$pagina.'/'.$Tfilas);
		$this->set('total_paginas',$Tfilas);
		$this->set('pagina_actual',$pagina);
		$this->set('ultimo',$Tfilas);
 	    $datos_filas=$this->shd100_actividades->findAll($this->SQLCA()." and minimo_tributable !=0 and  quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$pista%')",null,"cod_actividad ASC",$ca_reg,$pagina,null);
        $this->set("data_actividad",$datos_filas);
        $this->set('siguiente',$pagina+1);
		$this->set('anterior',$pagina-1);
		$this->bt_nav($Tfilas,$pagina);
      }else{
    	$this->set("data_actividad",'');
      }

}//fin funcion buscar_pista_actividad

function grilla($var1=null){
	$this->layout='ajax';
	$acti  = $this->v_shd100_patente_actividades->findAll("rif_cedula='".$var1."'",null,'cod_actividad ASC');
	$num_acti = $this->v_shd100_patente_actividades->findCount("rif_cedula='".$var1."'");
	$this->Session->write('conta_acti', $num_acti);
	$this->Session->write('rif_cedula', $var1);
	$this->set('acti',$acti);
	$this->set('rif_cedula',$var1);
}

function eliminar_grilla_principal ($fila=null,$rif_cedula=null,$cod_actividad=null) {
   $this->layout="ajax";
   $cond=$this->SQLCA()." and rif_cedula='".$rif_cedula."' and cod_actividad='".$cod_actividad."'";
   $this->shd100_patente_actividades->execute("DELETE FROM shd100_patente_actividades  WHERE ".$cond);
   $c=$this->Session->read('conta_acti');
   $this->Session->write('conta_acti', $c-1);
   $this->set('ifila',$fila);

}//fin funcion eliminar_grilla_principal

function seleccion_busqueda_venta($var1=null){
$this->layout="ajax";
$datos = $this->v_shd100_solicitud->findAll($this->SQLCA()." and rif_cedula='".$var1."' and numero_patente!='0'");

if($datos != null){
	$this->set('datos',$datos);
	$this->set('datos',$datos);

					echo "<script>";
						echo "document.getElementById('numero_solicitud').value='".$datos[0]['v_shd100_solicitud']['numero_solicitud']."';   ";
						echo "document.getElementById('fecha_solicitud').value='".cambiar_formato_fecha($datos[0]['v_shd100_solicitud']['fecha_solicitud'])."';   ";
					    echo "document.getElementById('deno_rif').value='".$datos[0]['v_shd100_solicitud']['razon_social_nombres']."';   ";
					    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['v_shd100_solicitud']['rif_cedula']."';   ";
					echo "</script>";
}else{
	$vacio='';
					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$vacio."';   ";
						echo "document.getElementById('numero_solicitud').value='".$vacio."';   ";
						echo "document.getElementById('fecha_solicitud').value='".$vacio."';   ";
						echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
					echo "</script>";
}


    $datos_empresa=$this->v_shd100_solicitud->findAll($this->SQLCA()." and rif_cedula='".$var1."' and numero_patente!='0'");
	if($datos_empresa != null){
	     $this->set('datos_empresa',$datos_empresa);
    }

    $datos_patente=$this->v_shd100_patente->findAll($this->SQLCA()." and rif_cedula='".$var1."'");
	if($datos_patente != null){//echo 'si';
	     $this->set('datos_patente',$datos_patente);//pr($datos);
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
    $datos=$this->v_shd100_solicitud->findAll($this->SQLCA()." and rif_cedula='".$var1."' and numero_patente!='0'");
	if($datos != null){
	$this->set('datos',$datos);
    }

}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_shd100_solicitud->findCount($this->SQLCA()." and (((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))) and numero_patente!= '0')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd100_solicitud->findAll($this->SQLCA()." and (((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))) and numero_patente!= '0') ",null,"rif_cedula ASC",50,1,null);
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
						$Tfilas=$this->v_shd100_solicitud->findCount($this->SQLCA()." and (((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%'))) and numero_patente!= '0')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd100_solicitud->findAll($this->SQLCA()." and (((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%'))) and numero_patente!= '0')",null,"rif_cedula ASC",50,$pagina,null);
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
   	$minimo_tributable  = $this->data['shp100_declaracion_ingresos']['minimo_tributable_'.$i];
   	$alicuota		    = $this->data['shp100_declaracion_ingresos']['alicuota_'.$i];
   	$ingresos       	= $this->data['shp100_declaracion_ingresos']['ingresos_'.$i];
   	$impuestos    		= $this->data['shp100_declaracion_ingresos']['impuestos_'.$i];
	$deno				= $this->shd100_actividades->findAll("cod_actividad='".$cod_actividad."' and ".$this->SQLCA());
$this->set('ifila',$i);
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
	              $_SESSION["DATOS"][$cont]["minimo_tributable"]       = $minimo_tributable;
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
	              			$_SESSION["DATOS"][$cont]["minimo_tributable"]       = $minimo_tributable;
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
/*
echo'<script>';
       echo" document.getElementById('cod_actividad').value     = ''; ";
       echo" document.getElementById('alicuotax').value     = ''; ";
       echo" document.getElementById('activ_deno').value           = ''; ";
       echo" document.getElementById('ingresos').value           = ''; ";
       echo" document.getElementById('impuestos').value           = ''; ";
       echo" document.getElementById('ingresos').disabled           = true; ";
echo'</script>';
*/
$this->set('i',$i);
$this->set("accion", $_SESSION["DATOS"]);
	$this->set('xxx',true);
}

}//fin function




function agregar_grilla2(){

$this->layout = "ajax";
   	$cod_actividad		= $this->data['shp100_declaracion_ingresos']['cod_actividad'];
   	$alicuota		    = $this->data['shp100_declaracion_ingresos']['alicuotax'];
   	$minimo_tributable  = $this->data['shp100_declaracion_ingresos']['minimo_tributablex'];
   	$ingresos       	= $this->data['shp100_declaracion_ingresos']['ingresosx'];
   	$impuestos    		= $this->data['shp100_declaracion_ingresos']['impuestosx'];
	$deno				= $this->shd100_actividades->findAll("cod_actividad='".$cod_actividad."' and ".$this->SQLCA());

			if(!isset($_SESSION["DATOS"])){
	              $_SESSION["CUENTA"] = 1;
	              $cont               = $_SESSION["CUENTA"];
	              $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              $_SESSION["DATOS"][$cont]["deno_actividad"]    = $deno[0]['shd100_actividades']['denominacion_actividad'];
	              $_SESSION["DATOS"][$cont]["ingresos"]       = $ingresos;
	              $_SESSION["DATOS"][$cont]["minimo_tributable"]= $minimo_tributable;
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
	              			$_SESSION["DATOS"][$cont]["minimo_tributable"]= $minimo_tributable;
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
       echo" document.getElementById('minimo_tributablex').value     = ''; ";
       echo" document.getElementById('alicuotax').value     = ''; ";
       echo" document.getElementById('activ_deno').value = ''; ";
       echo" document.getElementById('ingresosx').value           = ''; ";
       echo" document.getElementById('impuestosx').value           = ''; ";
echo'</script>';



$this->set('i',$i);
$this->set("accion", $_SESSION["DATOS"]);


}//fin function








function eliminar_grilla($var1=null,$cod_actividad=null){
$this->layout = "ajax";
$rif_cedula = $this->Session->read('rif_cedula');
$cond=$this->SQLCA()." and rif_cedula='".$rif_cedula."' and cod_actividad='".$cod_actividad."'";
//$this->shd100_patente_actividades->execute("DELETE FROM shd100_patente_actividades  WHERE ".$cond);
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
  	$ano_declaracion=$ejercicio_declarado;
  	$resultado_solicitud=$this->shd100_solicitud->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'",'capital,numero_empleado,numero_obreros');
    $capital_anterior = $resultado_solicitud[0]['shd100_solicitud']['capital'];
    $numero_empleados_anterior = $resultado_solicitud[0]['shd100_solicitud']['numero_empleado'];
    $numero_obreros_anterior = $resultado_solicitud[0]['shd100_solicitud']['numero_obreros'];
    $reg = $this->v_shd100_declaracion_ingresos->findCount($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano_declaracion=".$ejercicio_declarado);
    $guadado = array();
    if($reg<6){
    $this->shd100_declaracion_ingresos->execute('BEGIN;');
	$cont  = $_SESSION["CUENTA"];
	$mto=0;
	$imp=0;
             $sql ="INSERT INTO shd100_declaracion_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,rif_cedula,ano_declaracion,numero_declaracion, cod_actividad, monto_ingresos, monto_impuesto,alicuota_aplicada) VALUES ";
			 $ins ="INSERT INTO shd100_patente_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,rif_cedula, cod_actividad, numero_aforos, monto_aforo_anual, total_aforo_anual) VALUES ";
			 $ver=$this->shd100_patente_actividades->execute("DELETE FROM  shd100_patente_actividades WHERE rif_cedula='".$rif_cedula."' and ".$this->SQLCA());
             $sql1=array();
             $ins1=array();
			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["DATOS"][$i]["activa"]==1){
                       $cod_actividad  = $_SESSION["DATOS"][$i]["cod_actividad"];
                       $monto_ingresos = $this->Formato1($_SESSION["DATOS"][$i]["ingresos"]);
                       $monto_impuesto = $this->Formato1($_SESSION["DATOS"][$i]["impuestos"]);
                       $alicuota       = $this->Formato1($_SESSION["DATOS"][$i]["alicuota"]);
                       $mto=$mto + $monto_ingresos;
                       $imp=$imp + $monto_impuesto;
					   $sql1[]=" ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,'$rif_cedula',$ano_declaracion,$numero_declaracion, '$cod_actividad', $monto_ingresos, $monto_impuesto,$alicuota)";
					   $ins1[]=" ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$rif_cedula."', '".$cod_actividad."',1, $monto_impuesto,$monto_impuesto)";
			    }//fin if activa
			 }//fin for
			 /*$resultado_patente = $this->shd100_patente->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'","(monto_mensual*12) as monto_impuesto_anterior");
			 extract($resultado_patente[0]['shd100_patente']);
			 $monto_verificacion_impuesto = $imp - $monto_impuesto_anterior;
			 if($monto_verificacion_impuesto<0){
			 	$aumento_monto_impuesto = 0;
			 	$disminucion_monto_impuesto = $monto_verificacion_impuesto;
			 }else{
			 	$aumento_monto_impuesto = $monto_verificacion_impuesto;
			 	$disminucion_monto_impuesto = 0;
			 }*/
			 $usuario_registro = $_SESSION['nom_usuario'];
			 $fecha_registro = date('Y-m-d');
			 $sql_insert_declaraciones = "INSERT INTO shd100_declaracion_ingresos VALUES(
            $cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '$rif_cedula',
            $ano_declaracion, $numero_declaracion, '$periodo_desde', '$periodo_hasta',
            $capital, $numero_empleados, $numero_obreros, '$fecha_declaracion',
            $mto, $imp, $capital_anterior, $numero_empleados_anterior,
            $numero_obreros_anterior, 0, 0,0, 0, 0,2,0,'0',1,'".cambiar_formato_fecha($fecha_registro)."','$usuario_registro','1900-01-01','',0,0);";
            $sw3 = $this->shd100_declaracion_ingresos->execute($sql_insert_declaraciones);
            if($sw3>1){
            $sw1 = $this->shd100_declaracion_actividades->execute($sql.implode(',',$sql1));
	            if($sw1>1){
	            	$sw2 = $this->shd100_declaracion_actividades->execute($ins.implode(',',$ins1));
	            	if($sw2>1){
	                    	$imp2= $this->Formato2($imp / 12);
	                        $imp2 = $this->Formato1($imp2);
	                        $u1="update shd100_solicitud set capital=$capital,numero_empleado=$numero_empleados, numero_obreros=$numero_obreros where rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."' and ".$this->SQLCA();
	                        $u2="update shd100_patente set fecha_ultima_decla='".$fecha_declaracion."', monto_mensual=$imp2,ingresos_declarados=$mto, periodo_desde='".$periodo_desde."',periodo_hasta='".$periodo_hasta."',ultimo_ejercicio_decla=$ejercicio_declarado, ultimo_numero_declaracion=$numero_declaracion where rif_cedula='".$rif_cedula."' and numero_patente='".$numero_patente."' and ".$this->SQLCA();
	                        $sw4 = $this->shd100_solicitud->execute($u1);
							if($sw4>1){
								$sw5 = $this->shd100_patente->execute($u2);
								if($sw5>1){
									$this->set('exito', 'LOS DATOS FUERON GUARDADOS');
				                	$this->shd100_patente->execute('COMMIT;');
				                	$guadado = array($ano_declaracion,$numero_declaracion,$rif_cedula);
								}else{
									$this->set('error', 'Actualización de la patente no realizado');
				                    $this->shd100_patente->execute('ROLLBACK;');
								}
							}else{
								$this->set('error', 'Actualización de la solitud no realizada');
				                $this->shd100_patente->execute('ROLLBACK;');
							}
	            	}else{
	                    	$this->set('error', 'Registro de actividades de patende no realizado');
				            $this->shd100_patente->execute('ROLLBACK;');
	                }
	            }else{
	            	$this->set('error', 'Registro de la declaración de actividades no realizado');
		            $this->shd100_patente->execute('ROLLBACK;');
	            }
            }else{//llver
                    	$this->set('error', 'Registro de la declaración de ingreso no realizado');
			            $this->shd100_patente->execute('ROLLBACK;');
            }
}else{
	$this->set('error', 'ESTE CONTRIBUYENTE YA EXCEDIÓ EL NÚMERO MAXIMO DECLARACIONES PARA ESTE AÑO');
}
    //echo $sql_insert_declaraciones;
    //echo $sql.implode(',',$sql1);
    //echo $ins.implode(',',$ins1);

	$this->index($guadado);
	$this->render("index");

}

function anulacion($rif_cedula=null,$numero_declaracion=null,$ano_declaracion=null,$pagina=null){
	$this->layout = "ajax";//pr($this->data);

	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
  	$resultados_anteriores= $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion and numero_declaracion=$numero_declaracion","capital_anterior,numero_empleados_anterior,numero_obreros_anterior");
  	extract($resultados_anteriores[0]['shd100_declaracion_ingresos']);
  	$sql_update_solicitud = "UPDATE shd100_solicitud SET capital=$capital_anterior, numero_empleado=$numero_empleados_anterior, numero_obreros=$numero_obreros_anterior WHERE ".$this->SQLCA()." and rif_cedula='$rif_cedula'";
  	$this->shd100_declaracion_ingresos->execute('BEGIN;');
  	$y=$this->shd100_solicitud->execute($sql_update_solicitud);
  	if($y>1){
        $sql_update_declaraciones = "UPDATE shd100_declaracion_ingresos SET fecha_anulacion='".date('Y-m-d')."', username_anulacion='".$_SESSION['nom_usuario']."', condicion_actividad=2 WHERE ".$this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion and numero_declaracion=$numero_declaracion";
        $x=$this->shd100_declaracion_ingresos->execute($sql_update_declaraciones);
        if($x>1){
            $this->set('exito', 'Registro anulado');
			$this->shd100_patente->execute('COMMIT;');
        }else{
        	$this->set('error', 'Registro no pudo ser anulado');
			$this->shd100_patente->execute('ROLLBACK;');
        }
  	}else{
  		    $this->set('error', 'Registro no pudo ser anulado');
			$this->shd100_patente->execute('ROLLBACK;');
  	}
	$this->consulta2($rif_cedula);
	$this->render("consulta2");

}

/*
function consultar($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
 		$ano = $this->shd000_arranque->ano($this->SQLCA());
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
         }else{
 	         $pagina=1;
         }
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd100_ingresos_rif->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd100_declaracion_ingresos->findAll($this->SQLCA()."",null,'rif_cedula,ano_declaracion,numero_declaracion ASC',1,$pagina,null);//pr($datos);
          	 $datos3=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()."",null,'rif_cedula,ano_declaracion,numero_declaracion ASC',1,$pagina,null);//pr($datos);
          	 foreach($datos as $row){
          	 	$rif  		= $row['v_shd100_declaracion_ingresos']['rif_cedula'];
          	 	$num_dec  	= $row['v_shd100_declaracion_ingresos']['numero_declaracion'];
          	 	$ano_declaracion  		= $row['v_shd100_declaracion_ingresos']['ano_declaracion'];
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('datos3',$datos3);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $this->set('data2',$this->v_shd100_declaracion_actividades->findAll($this->SQLCA()." and rif_cedula='$rif'  and ano_declaracion=$ano_declaracion and numero_declaracion='".$num_dec."'",null,"ano_declaracion,numero_declaracion,cod_actividad ASC"));
             $datos3_numero=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and ano_declaracion=$ano_declaracion and rif_cedula='$rif' and condicion_actividad=1","numero_declaracion",'numero_declaracion DESC',1,null,null);//pr($datos);
             $this->set('numero_declaracion_anular',$datos3_numero[0]['shd100_declaracion_ingresos']['numero_declaracion']);
             $datos4=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif' and condicion_actividad=1",null,'ano_declaracion,numero_declaracion ASC',null,null,null);//pr($datos);
             $this->set('datos4',$datos4);
             }
            // echo"hola";

}//fin function consultar2*/

function consultar($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
 		$ano = $this->shd000_arranque->ano($this->SQLCA());
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
         }else{
 	         $pagina=1;
         }
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd100_ingresos_rif->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datosx=$this->v_shd100_ingresos_rif->findAll($this->SQLCA()."",null,'rif_cedula ASC',1,$pagina,null);//pr($datos);
          	 $rif_cedula=$datosx[0]['v_shd100_ingresos_rif']['rif_cedula'];
          	 $datos=$this->v_shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'",null,'rif_cedula,ano_declaracion,numero_declaracion ASC',1,1,null);//pr($datos);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $datos4=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'",null,'ano_declaracion,numero_declaracion ASC',null,null,null);//pr($datos);
             $this->set('datos4',$datos4);
             }
            // echo"hola";

}//fin function consultar2

function consulta_detalle_ingreso ($rif_cedula=null,$numero_declaracion=null,$ano_declaracion=null) {
   $this->layout="ajax";
   $datos4=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion and numero_declaracion=$numero_declaracion",'rif_cedula,numero_declaracion,ano_declaracion,fecha_declaracion,periodo_desde,periodo_hasta,capital,numero_empleados,numero_obreros,condicion_actividad,username_registro,fecha_registro,username_anulacion,fecha_anulacion,cancelado','ano_declaracion,numero_declaracion ASC',null,null,null);//pr($datos);
   $this->set('datos4',$datos4);
   $this->set('data_actividades',$this->v_shd100_declaracion_actividades->findAll($this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion  and numero_declaracion=$numero_declaracion"));
   $datos3_numero=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and ano_declaracion=$ano_declaracion and rif_cedula='$rif_cedula' and condicion_actividad=1","numero_declaracion",'numero_declaracion DESC',1,null,null);//pr($datos);
   $this->set('numero_declaracion_anular',$datos3_numero[0]['shd100_declaracion_ingresos']['numero_declaracion']);
}//fin funcion consulta_detalle_ingreso

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
function eliminar($rif=null,$num_dec=null,$ano_declaracion=null,$pagina=null){
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
					$SQL1="(".$this->busca_separado(array("rif_cedula", "razon_social_nombres"), $var2).")";
					$Tfilas=$this->v_shd100_ingresos_rif->findCount($this->SQLCA()." and ".$SQL1);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd100_ingresos_rif->findAll($this->SQLCA()." and ".$SQL1,null,"rif_cedula ASC",50,1,null);
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
						$SQL1="(".$this->busca_separado(array("rif_cedula", "razon_social_nombres"), $var22).")";
						$Tfilas=$this->v_shd100_ingresos_rif->findCount($this->SQLCA()." and ".$SQL1);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);

							        $datos_filas=$this->v_shd100_ingresos_rif->findAll($this->SQLCA()." and ".$SQL1,null,"rif_cedula ASC",50,$pagina,null);
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


function consulta2($rif_cedula=null){
	$this->layout = "ajax";

	$veri=$datos=$this->v_shd100_declaracion_ingresos->findCount($this->SQLCA()." and rif_cedula='$rif_cedula'");
      if($veri > 0){
      	     $datos=$this->v_shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'",null,'rif_cedula,ano_declaracion,numero_declaracion ASC',1,1,null);//pr($datos);
          	 $this->set('datos',$datos);
             $datos4=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'",null,'ano_declaracion,numero_declaracion ASC',null,null,null);//pr($datos);
             $this->set('datos4',$datos4);
      }else{
	  			$this->set('error',"Datos no encontrados");
	  			$this->index();
				$this->render("index");
      }
}//fin function consultar2

function historia_declaraciones_anteriores ($rif_cedula=null) {
   $this->layout="ajax";
   $this->set('data',$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'",null,'ano_declaracion,numero_declaracion ASC'));


}//fin funcion historia_declaraciones_anteriores


function ultima_declaraciones_declaradas ($rif_cedula=null) {
   $this->layout="ajax";
   $x=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula' and condicion_actividad=1",'ano_declaracion,numero_declaracion','ano_declaracion,numero_declaracion DESC',1);
   if(count($x)>0){
      extract($x[0]['shd100_declaracion_ingresos']);
      $this->set('data',$this->v_shd100_declaracion_actividades->findAll($this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion and numero_declaracion=$numero_declaracion"));
   }

}//fin funcion ultima_declaraciones_declaradas

function antiguedad ($desde,$hasta,$x) {
   $this->layout="ajax";
   $ano = $this->shd100_actividades->execute("SELECT devolver_edad('$hasta', '$desde', 'ANO')");
   $mes = $this->shd100_actividades->execute("SELECT devolver_edad('$hasta', '$desde', 'MES')");
   if(count($ano)>0){
   	 $ano = $ano[0][0]['devolver_edad'];
   }else{
   	 $ano = 0;
   }
   $this->set('ano_antiguedad',$ano);
   $this->set('mes_antiguedad',$mes[0][0]['devolver_edad']+1);

   $this->set('x',$x);

}//fin funcion antiguedad

function verifica_desde_fecha_declaracion_anterior ($dia,$mes,$ano) {
   $this->layout="ajax";
   $rif_cedula=$this->Session->read('rif_cedula');
   $periodo_desde = $dia."/".$mes."/".$ano;
   if($rif_cedula!=null){
   	   $ano = $this->shd000_arranque->ano($this->SQLCA());
       $c=$this->shd100_declaracion_ingresos->findCount($this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano and condicion_actividad=1");
        if($c!=0){
           $r=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano and condicion_actividad=1","periodo_hasta","numero_declaracion DESC",1);
	       extract($r[0]['shd100_declaracion_ingresos']);
	       $r2=compara_fechas_basic(cambiar_formato_fecha($periodo_hasta),$periodo_desde);
	       if($r2>0){
	          $this->set('error','El periodo desde no puede ser menor a '.cambiar_formato_fecha($periodo_hasta)." de la declaración anterior");
	       }else{
	   	        $this->set('exito','');
	       }
        }else{
            $rif_cedula=$this->Session->read('rif_cedula');
			   if($rif_cedula!=null){
			   	   $ano = $this->shd000_arranque->ano($this->SQLCA());
			   	   $re=$this->shd100_patente->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'","fecha_patente");
			       extract($re[0]['shd100_patente']);
			       $c=$this->shd100_declaracion_ingresos->findCount($this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano and condicion_actividad=1");
			       if($c==0){
				       $r2=compara_fechas_basic($periodo_desde,cambiar_formato_fecha($fecha_patente));
				       if($r2<0){
				          $this->set('error','El periodo desde no puede ser menor a la fecha de la patente '.cambiar_formato_fecha($fecha_patente)."");
				       }else{
				       	  $this->set('exito','');
				       }
			        }
			   }
        }
   }

}//fin funcion verfica_desde_fecha_declaracion_anterior

function verifica_fecha_declaracion_patente ($dia,$mes,$ano) {
   $this->layout="ajax";
   $rif_cedula=$this->Session->read('rif_cedula');
   $fecha_declaracion = $dia."/".$mes."/".$ano;
   if($rif_cedula!=null){
   	   $ano = $this->shd000_arranque->ano($this->SQLCA());
   	   $re=$this->shd100_patente->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'","fecha_patente");
       extract($re[0]['shd100_patente']);
       $c=$this->shd100_declaracion_ingresos->findCount($this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano and condicion_actividad=1");
       if($c==0){
	       $r2=compara_fechas_basic($fecha_declaracion,cambiar_formato_fecha($fecha_patente));
	       if($r2<0){
	          $this->set('error','La fecha de declaración no puede ser menor a la fecha de la patente '.cambiar_formato_fecha($fecha_patente)."");
	       }else{
	   	        $this->set('exito','');
	       }
        }else{

           $r=$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano and condicion_actividad=1","periodo_hasta","numero_declaracion DESC",1);
	       extract($r[0]['shd100_declaracion_ingresos']);
	       $r2=compara_fechas_basic($fecha_declaracion,cambiar_formato_fecha($periodo_hasta));
	       if($r2<0){
	          $this->set('error','La fecha de declaración no puede ser menor al periodo hasta '.cambiar_formato_fecha($periodo_hasta)." de la declaración anterior");
	       }else{
	   	        $this->set('exito','');
	       }
        }
   }

}//fin funcion verfica_desde_fecha_declaracion_anterior

//PERIODO_DESDE NO PUEDE SER MAYOR A LA FECHA DE DECLARACION AND MAYOR AL PERIODO_HASTA
//PERIODO_HASTA NO PUEDE SER MENOR A PERIODO_DESDE

function desde_mayor_declaracion ($dia,$mes,$ano) {
   $this->layout="ajax";
   $periodo_desde = $ano."-".$mes."-".$dia;
   $this->set('periodo_desde',$periodo_desde);
}//fin funcion desde_mayor_declaracion

function desde_mayor_declaracion2 ($dia,$mes,$ano,$dia2,$mes2,$ano2) {
   $this->layout="ajax";
   $fecha_declaracion = $dia."/".$mes."/".$ano;
   $periodo_desde = $dia2."/".$mes2."/".$ano2;
   $r2=compara_fechas_basic($periodo_desde,$fecha_declaracion);
   if($r2>0){
          $this->set('error','El periodo desde no puede ser mayor a fecha declaración '.($fecha_declaracion));
   }



}//fin funcion desde_mayor_declaracion

function verificar_hasta_desde ($dia,$mes,$ano) {
   $this->layout="ajax";
   $periodo_hasta = "".$dia."/".$mes."/".$ano."";
   $this->set('periodo_hasta',$periodo_hasta);

}//fin funcion verfica_hasta_desde


function verificar_hasta_desde2 ($dia,$mes,$ano,$dia2,$mes2,$ano2,$dia3,$mes3,$ano3) {
   $this->layout="ajax";
   $fecha_declaracion = $dia."/".$mes."/".$ano;
   $periodo_desde = $dia2."/".$mes2."/".$ano2;
   $periodo_hasta = $dia3."/".$mes3."/".$ano3;
   $r2=compara_fechas_basic($periodo_hasta,$fecha_declaracion);
   $r3=compara_fechas_basic($periodo_desde,$periodo_hasta);
   /*if($r2>0){
          $this->set('error','El periodo hasta no puede ser mayor a fecha declaración '.($fecha_declaracion));
          $this->set('tipo',1);
   }*/
   if($r3>0){
          $this->set('error','El periodo hasta no puede ser menor a periodo desde '.($periodo_desde));
          $this->set('tipo',2);
   }
   //echo $r3;

}//fin funcion desde_mayor_declaracion

function salir($numero_declaracion=null){
	$this->layout="ajax";
	//$ano=$this->ano_ejecucion();
	$ano = $this->shd000_arranque->ano($this->SQLCA());
    $this->shd100_declaracion_numero->execute('UPDATE shd100_declaracion_numero set situacion=1 WHERE '.$this->SQLCA()." and ano_declaracion='$ano' and numero_declaracion = '$numero_declaracion'");
    $this->index();
    $this->render('index');
}


function recibo ($ano_declaracion,$numero_declaracion,$rif_cedula) {
   $this->layout="ajax";
        $condicion=$this->SQLCA();
        //$rif_cedula = $_SESSION['infogobierno']['cedula_identidad'];
        $datos = $this->v_shd100_solicitud->findAll($condicion." and rif_cedula='$rif_cedula' and numero_patente!='0'");
		//pr($datos);
		$this->set('datos',$datos);
	    $datos_patente=$this->v_shd100_patente->findAll($condicion." and rif_cedula='$rif_cedula'");
		if($datos_patente != null){//echo 'si';
		     $this->set('datos_patente',$datos_patente);
	    }
	   $this->set('data_hi',$data_hi=$this->shd100_declaracion_ingresos->findAll($condicion." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion and numero_declaracion=$numero_declaracion",null,'ano_declaracion,numero_declaracion ASC'));
	   $this->set('data_da',$data_da=$this->v_shd100_declaracion_actividades->findAll($condicion." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion and numero_declaracion=$numero_declaracion"));
}//fin funcion recibo



}
?>