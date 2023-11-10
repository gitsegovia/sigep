<?php
/*
 * Creado el 29/07/2008 a las 12:11:16 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Shp100DeclaracionIngresosExoneracionesController extends AppController{
	var $name = "shp100_declaracion_ingresos_exoneraciones";
 	var $uses = array('shd100_patente','shd100_declaracion_ingresos', 'v_shd100_patente', 'v_shd100_solicitud', 'shd100_declaracion_ingresos',
 	                  'v_shd100_declaracion_actividades', 'v_shd100_patente_actividades');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
	if (!$this->Session->check('Usuario')){
		$this->redirect('/salir/');
		exit();
	}else{
		$this->requestAction('/usuarios/actualizar_user');
	}
}//fin checksession

function beforeFilter(){
	$this->checkSession();
}



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


function grilla($var1=null){
	$this->layout='ajax';
	$acti  = $this->v_shd100_patente_actividades->findAll("rif_cedula='".$var1."'",null,'cod_actividad ASC');
	$num_acti = $this->v_shd100_patente_actividades->findCount("rif_cedula='".$var1."'");
	$this->Session->write('conta_acti', $num_acti);
	$this->Session->write('rif_cedula', $var1);
	$this->set('acti',$acti);
}

function ultima_declaraciones_declaradas ($rif_cedula=null) {
   $this->layout="ajax";
   $this->set('data',$this->v_shd100_declaracion_actividades->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'"));

}//fin funcion ultima_declaraciones_declaradas

function historia_declaraciones_anteriores ($rif_cedula=null) {
   $this->layout="ajax";
   $this->set('data',$this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and condicion_actividad = 1 and rif_cedula='$rif_cedula'"));


}//fin funcion historia_declaraciones_anteriores

function buscar_constribuyente($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function


function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

$cod_presi     = $this->Session->read('SScodpresi');
$cod_entidad   = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst      = $this->Session->read('SScodinst');
$cod_dep       = $this->Session->read('SScoddep');

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$sql =  $this->condicion()." and ".$this->busca_separado(array("rif_cedula", "razon_social_nombres"), $var2);
					$sql .= "and rif_cedula  IN (select c.rif_cedula FROM shd100_declaracion_ingresos c  where cod_presi      =  '".$cod_presi."'     and
																								               cod_entidad    =  '".$cod_entidad."'   and
																								               cod_tipo_inst  =  '".$cod_tipo_inst."' and
																								               cod_inst       =  '".$cod_inst."'      and
																								               cod_dep        =  '".$cod_dep."')  ";

					$Tfilas=$this->v_shd100_solicitud->findCount($sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd100_solicitud->findAll($sql,null,"rif_cedula ASC",50,1,null);
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
						$sql =  $this->condicion()." and ".$this->busca_separado(array("rif_cedula", "razon_social_nombres"), $var22);
						$sql .= "and rif_cedula  IN (select c.rif_cedula FROM shd100_declaracion_ingresos c  where cod_presi      =  '".$cod_presi."'     and
																									               cod_entidad    =  '".$cod_entidad."'   and
																									               cod_tipo_inst  =  '".$cod_tipo_inst."' and
																									               cod_inst       =  '".$cod_inst."'      and
																									               cod_dep        =  '".$cod_dep."')  ";
						$Tfilas=$this->v_shd100_solicitud->findCount($sql);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd100_solicitud->findAll($sql,null,"rif_cedula ASC",50,$pagina,null);
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

             $data = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and condicion_actividad = 1 and cancelado=2 and rif_cedula='".$var1."'  ",null," ano_declaracion, numero_declaracion ASC");
	         $this->set('data',$data);
	         extract($data[0]['shd100_declaracion_ingresos']);
	         $_SESSION["contador"]=isset($_SESSION["contador"])?$_SESSION["contador"]+1:1;
		     $i= $_SESSION["contador"];
		     $vec[$i]['declaracion_ingresos'] = "si";
		     $vec[$i]['ano_declaracion']      = $ano_declaracion;
			 $vec[$i]['numero_declaracion']   = $numero_declaracion;
			 $vec[$i]['periodo_desde']        = $periodo_desde;
			 $vec[$i]['periodo_hasta']        = $periodo_hasta;
			 $vec[$i]['fecha_declaracion']    = $fecha_declaracion;
			 $vec[$i]['ingresos_declarados']  = $ingresos_declarados;
			 $vec[$i]['monto_impuesto']       = $monto_impuesto;
			 $vec[$i]['monto_exonerado']      = $monto_exonerado;
			 $vec[$i]['id']=$i;
			 $_SESSION["items_deuda"]=isset($_SESSION["items_deuda"])?$_SESSION["items_deuda"]+$vec:$vec;
			 $this->set('vector',$_SESSION["items_deuda"]);
			 $this->set('y',$i);



}//fin function




function deuda($var1=null){
$this->layout="ajax";

             $data = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and condicion_actividad = 1 and cancelado=2 and rif_cedula='".$var1."'",null," ano_declaracion, numero_declaracion ASC");
	         $this->set('data',$data);
	         extract($data[0]['shd100_declaracion_ingresos']);
	         $_SESSION["contador"]=isset($_SESSION["contador"])?$_SESSION["contador"]+1:1;
		     $i= $_SESSION["contador"];
		     $vec[$i]['declaracion_ingresos'] = "si";
		     $vec[$i]['ano_declaracion']      = $ano_declaracion;
			 $vec[$i]['numero_declaracion']   = $numero_declaracion;
			 $vec[$i]['periodo_desde']        = $periodo_desde;
			 $vec[$i]['periodo_hasta']        = $periodo_hasta;
			 $vec[$i]['fecha_declaracion']    = $fecha_declaracion;
			 $vec[$i]['ingresos_declarados']  = $ingresos_declarados;
			 $vec[$i]['monto_impuesto']       = $monto_impuesto;
			 $vec[$i]['monto_exonerado']      = $monto_exonerado;
			 $vec[$i]['observacion_exoneracion'] = $observacion_exoneracion;
			 $vec[$i]['id']=$i;
			 $_SESSION["items_deuda"]=isset($_SESSION["items_deuda"])?$_SESSION["items_deuda"]+$vec:$vec;
			 $this->set('vector',$_SESSION["items_deuda"]);
			 $this->set('y',$i);
			 $this->set('rif_cedula',$var1);



}//fin function





function editar($var1=null, $var2=null, $var3=null){
	$this->layout = "ajax";

	echo "<script>";
          echo "$('monto_exonerado_".$var1."_".$var2."').readOnly=false;";
          echo "$('monto_exonerado_".$var1."_".$var2."').disabled=false;";
          echo "$('iconos_1_".$var1."_".$var2."').style.display='none';";
          echo "$('iconos_2_".$var1."_".$var2."').style.display='block';";
	echo "</script>";

	$this->render("funcion");
}


function cancelar($var1=null, $var2=null, $var3=null){
	$this->layout = "ajax";
	$data          = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$var3."' and ano_declaracion='".$var1."' and numero_declaracion='".$var2."' ",null," ano_declaracion, numero_declaracion ASC");
	echo "<script>";
	      echo "$('monto_exonerado_".$var1."_".$var2."').value          = '".$this->Formato2($data[0]["shd100_declaracion_ingresos"]["monto_exonerado"])."';";
	      echo "$('monto_impuesto_pagar_".$var1."_".$var2."').innerHTML = '".$this->Formato2($data[0]["shd100_declaracion_ingresos"]["monto_impuesto"] - $data[0]["shd100_declaracion_ingresos"]["monto_exonerado"])."';";
          echo "$('monto_exonerado_".$var1."_".$var2."').readOnly=true;";
          echo "$('monto_exonerado_".$var1."_".$var2."').disabled=true;";
          echo "$('iconos_1_".$var1."_".$var2."').style.display='block';";
          echo "$('iconos_2_".$var1."_".$var2."').style.display='none';";
	echo "</script>";
	$this->render("funcion");
}



function recalcular($ano_declaracion=null, $numero_declaracion=null, $monto_impuesto=null, $monto_exonera=null){
    $this->layout = "ajax";
    $monto = $monto_impuesto-$this->Formato1($monto_exonera);
	echo "<script>";
	    echo "$('monto_impuesto_pagar_".$ano_declaracion."_".$numero_declaracion."').innerHTML='".$this->Formato2($monto)."';";
	echo "</script>";
	$this->render("funcion");
}

function montar_observacion($var1=null, $var2=null, $var3=null){
	$this->layout = "ajax";
    echo "<script>";
     echo "Control.Modal.close(true);";
	echo "</script>";

	$this->set("rif_cedula",         $var3);
	$this->set("ano_declaracion",    $var1);
	$this->set("numero_declaracion", $var2);
	$this->set("value",              $this->data['shp100_declaracion_ingresos_exoneraciones']['observacion_value']);
}


function observaciones_1($var1=null, $var2=null, $var3=null){
	$this->layout = "ajax";
	$this->set("rif_cedula",         $var3);
	$this->set("ano_declaracion",    $var1);
	$this->set("numero_declaracion", $var2);
}




function observaciones_2($var1=null, $var2=null, $var3=null){
	$this->layout = "ajax";
	$data          = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$var3."' and ano_declaracion='".$var1."' and numero_declaracion='".$var2."' ","observacion_exoneracion"," ano_declaracion, numero_declaracion ASC");
    $this->set("rif_cedula",         $var3);
	$this->set("ano_declaracion",    $var1);
	$this->set("numero_declaracion", $var2);
	$this->set("observacion_exoneracion", $data[0]["shd100_declaracion_ingresos"]["observacion_exoneracion"]);
}



function guardar($var1=null, $var2=null, $var3=null){
	 $this->layout    = "ajax";
	 $monto_exonerado = $this->Formato1($this->data['shp100_declaracion_ingresos_exoneraciones']['monto_exonerado_'.$var1.'_'.$var2]);
	 $observacion     = $this->data['shp100_declaracion_ingresos_exoneraciones']['observacion_'.$var1.'_'.$var2];

	  $data          = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$var3."' and ano_declaracion='".$var1."' and numero_declaracion='".$var2."' ",null," ano_declaracion, numero_declaracion ASC");
      $monto_a_pagar = $data[0]["shd100_declaracion_ingresos"]["monto_impuesto"] - $monto_exonerado;

    if($monto_exonerado>$data[0]["shd100_declaracion_ingresos"]["monto_impuesto"]){
           	  $this->set("errorMessage", "El monto exonerado es mayor al monto impuesto");
    }else{
		      $update_documento_detalles = "UPDATE shd100_declaracion_ingresos SET monto_exonerado='".$monto_exonerado."', observacion_exoneracion='".$observacion."' WHERE ".$this->SQLCA()." and rif_cedula='".$var3."' and ano_declaracion='".$var1."' and numero_declaracion='".$var2."'; ";
			  $this->shd100_declaracion_ingresos->execute($update_documento_detalles);
		      if($monto_a_pagar==0){
		         $update_documento_detalles = "UPDATE shd100_declaracion_ingresos SET cancelado='1' WHERE ".$this->SQLCA()." and rif_cedula='".$var3."' and ano_declaracion='".$var1."' and numero_declaracion='".$var2."'; ";
			     $this->shd100_declaracion_ingresos->execute($update_documento_detalles);
		      }

			echo "<script>";
		          echo "$('monto_exonerado_".$var1."_".$var2."').readOnly=true;";
		          echo "$('monto_exonerado_".$var1."_".$var2."').disabled=true;";
		          echo "$('iconos_1_".$var1."_".$var2."').style.display='block';";
		          echo "$('iconos_2_".$var1."_".$var2."').style.display='none';";

		          if($monto_a_pagar==0){
		          	 echo "new Effect.DropOut('fila_".$var1."_".$var2."');";
		          }else{
		              echo " $('monto_impuesto_pagar_".$var1."_".$var2."').innerHTML = '".$this->Formato2($monto_a_pagar)."';  ";

		          }
			echo "</script>";
		    $this->set("Message_existe", "Los datos fueron guardados");
    }//fin else


	$this->render("funcion");
}






function funcion(){
	$this->layout = "ajax";
}



 function index($var=null, $var2=null){
	 $this->layout = "ajax";

	 $cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');
	 $cod_dep = $this->Session->read('SScoddep');

}//fin index

 }//Fin class
 ?>
