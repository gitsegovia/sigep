<?php

 class Shp100DeclaracionIngresosConvenimientosController extends AppController{
	var $name = "shp100_declaracion_ingresos_convenimientos";
 	var $uses = array('shd100_patente','shd100_declaracion_ingresos', 'v_shd100_patente', 'v_shd100_solicitud', 'shd100_declaracion_ingresos',
 	                  'v_shd100_declaracion_actividades', 'v_shd100_patente_actividades', 'shd100_declaracion_ingresos_convenimientos');
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


					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$datos[0]['v_shd100_solicitud']['razon_social_nombres']."';   ";
					    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['v_shd100_solicitud']['rif_cedula']."';   ";
					echo "</script>";
}else{
	$vacio='';
					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$vacio."';   ";
						echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
					echo "</script>";

  $this->set('datos', null);

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







function cuerpo($var1=null){

$this->layout = "ajax";

	 $cod_presi     = $this->Session->read('SScodpresi');
	 $cod_entidad   = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst      = $this->Session->read('SScodinst');
	 $cod_dep       = $this->Session->read('SScoddep');

$data3 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$var1."' ",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
$data2 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$var1."' and cancelado=2",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
$data  = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$var1."' and condicion_actividad = 1 and cancelado=2",null," ano_declaracion, numero_declaracion ASC");
$this->set('data',       $data);
$this->set('data2',      $data2);
$this->set('data3',      $data3);
$this->set('rif_cedula', $var1);


}//fin functio







function eliminar_convenio($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

$this->layout = "ajax";

	 $cod_presi     = $this->Session->read('SScodpresi');
	 $cod_entidad   = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst      = $this->Session->read('SScodinst');
	 $cod_dep       = $this->Session->read('SScoddep');

$this->shd100_declaracion_ingresos_convenimientos->execute("BEGIN;");

    $sql = $this->condicion()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and ano_convenimiento='".$var4."' and numero_convenimiento='".$var5."' ";
    $SQL_INSERT  = "DELETE FROM  shd100_declaracion_ingresos_convenimientos  WHERE ".$sql;
    $sw = $this->shd100_declaracion_ingresos_convenimientos->execute($SQL_INSERT);

if($sw>1){
 	  $this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO');
 	  $this->shd100_declaracion_ingresos_convenimientos->execute("COMMIT;");
 }else{
 	  $this->set('errorMessage', 'EL REGISTRO NO PUDO SER ELIMINADO');
 	  $this->shd100_declaracion_ingresos_convenimientos->execute("ROLLBACK;");
 }

    $data2_aux = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and cancelado=2",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
	$data_aux  = $this->shd100_declaracion_ingresos->findAll(               $this->SQLCA()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and condicion_actividad = 1 and cancelado=2",null," ano_declaracion, numero_declaracion ASC");
	foreach($data_aux as $r){
		extract($r['shd100_declaracion_ingresos']);
	    $acumulado_pagos_parciales_aux  = $acumulado_pagos_parciales;
	    foreach($data2_aux as $ve2){
	      if($ve2["shd100_declaracion_ingresos_convenimientos"]["ano_declaracion"]==$var2 && $ve2["shd100_declaracion_ingresos_convenimientos"]["numero_declaracion"]==$var3){
	         //$op=0;
	         $acumulado_pagos_parciales_aux += $ve2["shd100_declaracion_ingresos_convenimientos"]["monto_convenido"];
	      }
	    }
	    $_SESSION["deuda_vigente_".$var2."_".$var3] = (($monto_impuesto+$monto_intereses)-($monto_exonerado+$acumulado_pagos_parciales_aux));

         if($_SESSION["deuda_vigente_".$var2."_".$var3]==0){
	     	echo "<script> $('deuda_vigente3_".$var2."_".$var3."').style.display='none'; </script>";
	     	echo "<script> $('deuda_vigente4_".$var2."_".$var3."').style.display='block'; </script>";
	      }else{
	      	 echo "<script> $('deuda_vigente3_".$var2."_".$var3."').style.display='block'; </script>";
	      	 echo "<script> $('deuda_vigente4_".$var2."_".$var3."').style.display='none'; </script>";
	      }

	    echo "<script> $('deuda_vigente2_".$var2."_".$var3."').innerHTML='".$this->Formato2($_SESSION["deuda_vigente_".$var2."_".$var3])."';</script>";
	    echo "<script> $('deuda_vigente1_".$var2."_".$var3."').innerHTML='".$this->Formato2($acumulado_pagos_parciales_aux)."';</script>";
	    echo "<script>verifica_radio(); eliminar_verifica_radio();</script>";
	}

$data3 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$var1."' ",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
$this->set('data3',      $data3);
}//fin function






function editar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

$this->layout = "ajax";

	 $cod_presi     = $this->Session->read('SScodpresi');
	 $cod_entidad   = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst      = $this->Session->read('SScodinst');
	 $cod_dep       = $this->Session->read('SScoddep');

$sql   = $this->condicion()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and ano_convenimiento='".$var4."' and numero_convenimiento='".$var5."' ";
$data3 = $this->shd100_declaracion_ingresos_convenimientos->findAll($sql,null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");

         $var2_aux = $data3[0]['shd100_declaracion_ingresos_convenimientos']['fecha_acordada_pago'];
         $var3_aux = $data3[0]['shd100_declaracion_ingresos_convenimientos']['monto_convenido'];

         $var1 = $var2."_".$var3."_".$var4."_".$var5;

$this->set('var1',        $var1);
$this->set('var2_aux',    $var2_aux);
$this->set('var3_aux',    $var3_aux);
$this->set('data3',       $data3);
$this->set('fila',       $var6);
}




function cancelar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

$this->layout = "ajax";

	 $cod_presi     = $this->Session->read('SScodpresi');
	 $cod_entidad   = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst      = $this->Session->read('SScodinst');
	 $cod_dep       = $this->Session->read('SScoddep');

$sql   = $this->condicion()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and ano_convenimiento='".$var4."' and numero_convenimiento='".$var5."' ";
$data3 = $this->shd100_declaracion_ingresos_convenimientos->findAll($sql,null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");

         $var2_aux = $data3[0]['shd100_declaracion_ingresos_convenimientos']['fecha_acordada_pago'];
         $var3_aux = $data3[0]['shd100_declaracion_ingresos_convenimientos']['monto_convenido'];

         $var1 = $var2."_".$var3."_".$var4."_".$var5;

$this->set('var1',        $var1);
$this->set('var2_aux',    $var2_aux);
$this->set('var3_aux',    $var3_aux);
$this->set('data3',       $data3);
$this->set('fila',       $var6);
}








function guardar2($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

$this->layout = "ajax";

	 $cod_presi     = $this->Session->read('SScodpresi');
	 $cod_entidad   = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst      = $this->Session->read('SScodinst');
	 $cod_dep       = $this->Session->read('SScoddep');

$var1_aux = $var2."_".$var3."_".$var4."_".$var5;
$this->shd100_declaracion_ingresos_convenimientos->execute("BEGIN;");
$sw  = 0;
$sql = $this->condicion()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and ano_convenimiento='".$var4."' and numero_convenimiento='".$var5."' ";


if(!empty($this->data["shp100_declaracion_ingresos_convenimientos"]["fecha_convenimiento_".$var1_aux])  &&
   !empty($this->data["shp100_declaracion_ingresos_convenimientos"]["monto_convenimiento_".$var1_aux])  &&
   !empty($this->data["shp100_declaracion_ingresos_convenimientos"]["deuda_pendiente_".$var1_aux])){


				  $fecha_acordada_pago    = $this->data["shp100_declaracion_ingresos_convenimientos"]["fecha_convenimiento_".$var1_aux];
				  $monto_convenido        = $this->Formato1($this->data["shp100_declaracion_ingresos_convenimientos"]["monto_convenimiento_".$var1_aux]);
				  $deuda_pendiente        = $this->Formato1($this->data["shp100_declaracion_ingresos_convenimientos"]["deuda_pendiente_".$var1_aux]);


				if($deuda_pendiente<0){
				  $this->set('errorMessage', 'El monto de convenimiento no puede ser mayor al monto de la deuda');
				}else{
				  $SQL_INSERT  = "UPDATE  shd100_declaracion_ingresos_convenimientos set  fecha_acordada_pago='".$fecha_acordada_pago."', monto_convenido='".$monto_convenido."', deuda_pendiente='".$deuda_pendiente."' WHERE ".$sql;
				  $sw = $this->shd100_declaracion_ingresos_convenimientos->execute($SQL_INSERT);

				}//fin else

				 $data3 = $this->shd100_declaracion_ingresos_convenimientos->findAll($sql,null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
				 $var2_aux = $data3[0]['shd100_declaracion_ingresos_convenimientos']['fecha_acordada_pago'];
				 $var3_aux = $data3[0]['shd100_declaracion_ingresos_convenimientos']['monto_convenido'];
				 $var1 = $var2."_".$var3."_".$var4."_".$var5;
				$this->set('var1',        $var1);
				$this->set('var2_aux',    $var2_aux);
				$this->set('var3_aux',    $var3_aux);
				$this->set('data3',       $data3);
				$this->set('fila',       $var6);

					if($sw>1){
					 	  $this->set('Message_existe', 'EL REGISTRO FUE ACUALIZADO');
					 	  $this->shd100_declaracion_ingresos_convenimientos->execute("COMMIT;");
					 }else{
					 	  $this->shd100_declaracion_ingresos_convenimientos->execute("ROLLBACK;");
					 	  $this->render("editar");
					 }

					$data2_aux = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and cancelado=2",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
					$data_aux  = $this->shd100_declaracion_ingresos->findAll(               $this->SQLCA()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and condicion_actividad = 1 and cancelado=2",null," ano_declaracion, numero_declaracion ASC");
					foreach($data_aux as $r){
						extract($r['shd100_declaracion_ingresos']);
					    $acumulado_pagos_parciales_aux  = $acumulado_pagos_parciales;
					    foreach($data2_aux as $ve2){
					      if($ve2["shd100_declaracion_ingresos_convenimientos"]["ano_declaracion"]==$var2 && $ve2["shd100_declaracion_ingresos_convenimientos"]["numero_declaracion"]==$var3){
					         //$op=0;
					         $acumulado_pagos_parciales_aux += $ve2["shd100_declaracion_ingresos_convenimientos"]["monto_convenido"];
					      }
					    }
					    $_SESSION["deuda_vigente_".$var2."_".$var3] = (($monto_impuesto+$monto_intereses)-($monto_exonerado+$acumulado_pagos_parciales_aux));
					      if($_SESSION["deuda_vigente_".$var2."_".$var3]==0){
					     	echo "<script> $('deuda_vigente3_".$var2."_".$var3."').style.display='none'; </script>";
					     	echo "<script> $('deuda_vigente4_".$var2."_".$var3."').style.display='block'; </script>";
					      }else{
					      	 echo "<script> $('deuda_vigente3_".$var2."_".$var3."').style.display='block'; </script>";
					      	 echo "<script> $('deuda_vigente4_".$var2."_".$var3."').style.display='none'; </script>";
					      }

	                     echo "<script> $('deuda_vigente2_".$var2."_".$var3."').innerHTML='".$this->Formato2($_SESSION["deuda_vigente_".$var2."_".$var3])."';</script>";
	                     echo "<script> $('deuda_vigente1_".$var2."_".$var3."').innerHTML='".$this->Formato2($acumulado_pagos_parciales_aux)."';</script>";
	                     echo "<script>verifica_radio();</script>";

					}

}else{


						$data3 = $this->shd100_declaracion_ingresos_convenimientos->findAll($sql,null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");

					         $var2_aux = $data3[0]['shd100_declaracion_ingresos_convenimientos']['fecha_acordada_pago'];
					         $var3_aux = $data3[0]['shd100_declaracion_ingresos_convenimientos']['monto_convenido'];

					         $var1 = $var2."_".$var3."_".$var4."_".$var5;

					$this->set('var1',        $var1);
					$this->set('var2_aux',    $var2_aux);
					$this->set('var3_aux',    $var3_aux);
					$this->set('data3',       $data3);
					$this->set('fila',       $var6);

			 	    $this->set('errorMessage', 'EL REGISTRO NO PUDO SER ACUALIZADO');
			 	    $this->shd100_declaracion_ingresos_convenimientos->execute("ROLLBACK;");
			 	    $this->render("editar");


}//fin else principal






}//fin function






function agregar_convenio($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){


$this->layout = "ajax";

	 $cod_presi     = $this->Session->read('SScodpresi');
	 $cod_entidad   = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst      = $this->Session->read('SScodinst');
	 $cod_dep       = $this->Session->read('SScoddep');


  $rif_cedula             = $var1;
  $ano_declaracion        = $var2;
  $numero_declaracion     = $var3;
  $ano_convenimiento      = $this->data["shp100_declaracion_ingresos_convenimientos"]["ano_convenimiento"];
  $numero_convenimiento   = $this->data["shp100_declaracion_ingresos_convenimientos"]["numero_convenimiento"];

  $monto_deuda            = $this->Formato1($this->data["shp100_declaracion_ingresos_convenimientos"]["monto_deuda"]);
  $fecha_acordada_pago    = $this->data["shp100_declaracion_ingresos_convenimientos"]["fecha_convenimiento"];
  $monto_convenido        = $this->Formato1($this->data["shp100_declaracion_ingresos_convenimientos"]["monto_convenimiento"]);
  $deuda_pendiente        = $this->Formato1($this->data["shp100_declaracion_ingresos_convenimientos"]["deuda_pendiente"]);
  $fecha_cancelacion      = "1900/01/01";
  $cancelado              = 2;

$this->shd100_declaracion_ingresos_convenimientos->execute("BEGIN;");

    $sql = $this->condicion()." and rif_cedula='".$rif_cedula."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."' and ano_convenimiento='".$ano_convenimiento."' and numero_convenimiento='".$numero_convenimiento."' ";

$contar = $this->shd100_declaracion_ingresos_convenimientos->findCount($sql);

if($contar==0){
    $SQL_INSERT  ="INSERT INTO shd100_declaracion_ingresos_convenimientos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula,  ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento, monto_deuda, fecha_acordada_pago, monto_convenido, deuda_pendiente, fecha_cancelacion, cancelado) ";
    $SQL_INSERT .="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$rif_cedula."', '".$ano_declaracion."', '".$numero_declaracion."', '".$ano_convenimiento."', '".$numero_convenimiento."', '".$monto_deuda."', '".$fecha_acordada_pago."', '".$monto_convenido."', '".$deuda_pendiente."', '".$fecha_cancelacion."', '".$cancelado."') ";
}else{
    $SQL_INSERT  = "UPDATE  shd100_declaracion_ingresos_convenimientos set monto_deuda='".$monto_deuda."', fecha_acordada_pago='".$fecha_acordada_pago."', monto_convenido='".$monto_convenido."', deuda_pendiente='".$deuda_pendiente."', fecha_cancelacion='".$fecha_cancelacion."', cancelado='".$cancelado."' WHERE ".$sql;
}


$sw = $this->shd100_declaracion_ingresos_convenimientos->execute($SQL_INSERT);



if($sw>1){
 	  $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
 	  $this->shd100_declaracion_ingresos_convenimientos->execute("COMMIT;");
 }else{
 	  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
 	  $this->shd100_declaracion_ingresos_convenimientos->execute("ROLLBACK;");
 }


$data2_aux = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."' and cancelado=2",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
$data_aux  = $this->shd100_declaracion_ingresos->findAll(               $this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."' and condicion_actividad = 1 and cancelado=2",null," ano_declaracion, numero_declaracion ASC");
foreach($data_aux as $r){
	extract($r['shd100_declaracion_ingresos']);
    $acumulado_pagos_parciales_aux  = $acumulado_pagos_parciales;
    foreach($data2_aux as $ve2){
      if($ve2["shd100_declaracion_ingresos_convenimientos"]["ano_declaracion"]==$ano_declaracion && $ve2["shd100_declaracion_ingresos_convenimientos"]["numero_declaracion"]==$numero_declaracion){
         //$op=0;
         $acumulado_pagos_parciales_aux += $ve2["shd100_declaracion_ingresos_convenimientos"]["monto_convenido"];
      }
    }
    $_SESSION["deuda_vigente_".$ano_declaracion."_".$numero_declaracion] = (($monto_impuesto+$monto_intereses)-($monto_exonerado+$acumulado_pagos_parciales_aux));

          if($_SESSION["deuda_vigente_".$ano_declaracion."_".$numero_declaracion]==0){
	     	echo "<script> $('deuda_vigente3_".$ano_declaracion."_".$numero_declaracion."').style.display='none'; </script>";
	     	echo "<script> $('deuda_vigente4_".$ano_declaracion."_".$numero_declaracion."').style.display='block'; </script>";
	      }else{
	      	 echo "<script> $('deuda_vigente3_".$ano_declaracion."_".$numero_declaracion."').style.display='block'; </script>";
	      	 echo "<script> $('deuda_vigente4_".$ano_declaracion."_".$numero_declaracion."').style.display='none'; </script>";
	      }

	 echo "<script> $('deuda_vigente2_".$ano_declaracion."_".$numero_declaracion."').innerHTML='".$this->Formato2($_SESSION["deuda_vigente_".$ano_declaracion."_".$numero_declaracion])."';</script>";
	 echo "<script> $('deuda_vigente1_".$ano_declaracion."_".$numero_declaracion."').innerHTML='".$this->Formato2($acumulado_pagos_parciales_aux)."';</script>";
	 echo "<script>verifica_radio();</script>";

}



$data3 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$var1."' ",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
$this->set('data3',      $data3);




}//fin function






function pasar_a_convenio($var1=null, $var2=null, $var3=null){

$this->layout = "ajax";
$data = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and condicion_actividad = 1 and cancelado=2",null," ano_declaracion, numero_declaracion ASC");
$this->set('data',       $data);
$this->set('rif_cedula', $var1);

$data2 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$var1."' and ano_declaracion='".$var2."' and numero_declaracion='".$var3."' and ano_convenimiento='".$var2."' ",null," ano_convenimiento, numero_convenimiento  DESC");
$ano_convenimiento    = $var2;
$numero_convenimiento = !isset($data2[0]["shd100_declaracion_ingresos_convenimientos"]["numero_convenimient"])?1:$data2[0]["shd100_declaracion_ingresos_convenimientos"]["numero_convenimient"]+1;

$this->set('ano_convenimiento',     $ano_convenimiento);
$this->set('numero_convenimiento',  $numero_convenimiento);


}//fin function









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
