<?php
/*
 * Created on 09/12/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */






 /**
  * #ESTAS FUNCIONES ESTABAN EN EL CONTROLLER USUARIOS
  *

function actualizar_cuentas_bancarias($cod_dep=null){


set_time_limit(0);
ini_set ("memory_limit", "2048M");

$this->layout="ajax";

$datos2_aux = $this->arrd05->findAll("cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11");


foreach($datos2_aux as $aux_arrd05){

$cod_dep_arrd05 =   $aux_arrd05['arrd05']['cod_dep'];


		$datos  = $this->cstd03_cheque_cuerpo->findAll("cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and cod_dep='".$cod_dep_arrd05."' and condicion_actividad=1");

						foreach($datos as $datos_aux){
							  $cod_presi                = $datos_aux["cstd03_cheque_cuerpo"]["cod_presi"];
							  $cod_entidad              = $datos_aux["cstd03_cheque_cuerpo"]["cod_entidad"];
							  $cod_tipo_inst            = $datos_aux["cstd03_cheque_cuerpo"]["cod_tipo_inst"];
							  $cod_inst                 = $datos_aux["cstd03_cheque_cuerpo"]["cod_inst"];
							  $cod_dep                  = $datos_aux["cstd03_cheque_cuerpo"]["cod_dep"];
							  $ano_movimiento           = $datos_aux["cstd03_cheque_cuerpo"]["ano_movimiento"];
							  $cod_entidad_bancaria     = $datos_aux["cstd03_cheque_cuerpo"]["cod_entidad_bancaria"];
							  $cod_sucursal             = $datos_aux["cstd03_cheque_cuerpo"]["cod_sucursal"];
							  $cuenta_bancaria          = $datos_aux["cstd03_cheque_cuerpo"]["cuenta_bancaria"];
							  $numero_cheque            = $datos_aux["cstd03_cheque_cuerpo"]["numero_cheque"];
							  $monto                    = $datos_aux["cstd03_cheque_cuerpo"]["monto"];


							  $cond   = " cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";

							  $vec[$cuenta_bancaria]['cheque_dia'] = !isset($vec[$cuenta_bancaria]['cheque_dia'])?$monto:($vec[$cuenta_bancaria]['cheque_dia']+$monto);
							  $vec[$cuenta_bancaria]['cheque_mes'] = !isset($vec[$cuenta_bancaria]['cheque_mes'])?$monto:($vec[$cuenta_bancaria]['cheque_mes']+$monto);
							  $vec[$cuenta_bancaria]['cheque_ano'] = !isset($vec[$cuenta_bancaria]['cheque_ano'])?$monto:($vec[$cuenta_bancaria]['cheque_ano']+$monto);
							  $vec[$cuenta_bancaria]['cuenta_bancaria'] = $cuenta_bancaria;
						}





		$array = $this->cstd03_movimientos_manuales->findAll("cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and cod_dep='".$cod_dep_arrd05."' and condicion_actividad=1");

						foreach($array as $aux){
						   	$cod_inst                   =   $aux['cstd03_movimientos_manuales']['cod_inst'];
							$cod_dep                    =   $aux['cstd03_movimientos_manuales']['cod_dep'];
							$ano_movimiento             =   $aux['cstd03_movimientos_manuales']['ano_movimiento'];
							$cod_entidad_bancaria       =   $aux['cstd03_movimientos_manuales']['cod_entidad_bancaria'];
							$cod_sucursal               =   $aux['cstd03_movimientos_manuales']['cod_sucursal'];
							$cuenta_bancaria            =   $aux['cstd03_movimientos_manuales']['cuenta_bancaria'];
							$numero_documento           =   $aux['cstd03_movimientos_manuales']['numero_documento'];
							$condicion_actividad        =   $aux['cstd03_movimientos_manuales']['condicion_actividad'];
							$tipo_documento             =   $aux['cstd03_movimientos_manuales']['tipo_documento'];
							$monto                      =   $aux["cstd03_movimientos_manuales"]["monto"];

							$cond   = " cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";

						           $vec[$cuenta_bancaria]['cuenta_bancaria'] = $cuenta_bancaria;

						          if($tipo_documento==1){


									$vec[$cuenta_bancaria]['deposito_dia'] = !isset($vec[$cuenta_bancaria]['deposito_dia'])?$monto:($vec[$cuenta_bancaria]['deposito_dia']+$monto);
									$vec[$cuenta_bancaria]['deposito_mes'] = !isset($vec[$cuenta_bancaria]['deposito_mes'])?$monto:($vec[$cuenta_bancaria]['deposito_mes']+$monto);
									$vec[$cuenta_bancaria]['deposito_ano'] = !isset($vec[$cuenta_bancaria]['deposito_ano'])?$monto:($vec[$cuenta_bancaria]['deposito_ano']+$monto);

						    }else if($tipo_documento==2){

									$vec[$cuenta_bancaria]['nota_credito_dia'] = !isset($vec[$cuenta_bancaria]['nota_credito_dia'])?$monto:($vec[$cuenta_bancaria]['nota_credito_dia']+$monto);
									$vec[$cuenta_bancaria]['nota_credito_mes'] = !isset($vec[$cuenta_bancaria]['nota_credito_mes'])?$monto:($vec[$cuenta_bancaria]['nota_credito_mes']+$monto);
									$vec[$cuenta_bancaria]['nota_credito_ano'] = !isset($vec[$cuenta_bancaria]['nota_credito_ano'])?$monto:($vec[$cuenta_bancaria]['nota_credito_ano']+$monto);


						    }else if($tipo_documento==3){

									$vec[$cuenta_bancaria]['nota_debito_dia'] = !isset($vec[$cuenta_bancaria]['nota_debito_dia'])?$monto:($vec[$cuenta_bancaria]['nota_debito_dia']+$monto);
									$vec[$cuenta_bancaria]['nota_debito_mes'] = !isset($vec[$cuenta_bancaria]['nota_debito_mes'])?$monto:($vec[$cuenta_bancaria]['nota_debito_mes']+$monto);
									$vec[$cuenta_bancaria]['nota_debito_ano'] = !isset($vec[$cuenta_bancaria]['nota_debito_ano'])?$monto:($vec[$cuenta_bancaria]['nota_debito_ano']+$monto);


						    }else if($tipo_documento==4){

									$vec[$cuenta_bancaria]['cheque_dia'] = !isset($vec[$cuenta_bancaria]['cheque_dia'])?$monto:($vec[$cuenta_bancaria]['cheque_dia']+$monto);
									$vec[$cuenta_bancaria]['cheque_mes'] = !isset($vec[$cuenta_bancaria]['cheque_mes'])?$monto:($vec[$cuenta_bancaria]['cheque_mes']+$monto);
									$vec[$cuenta_bancaria]['cheque_ano'] = !isset($vec[$cuenta_bancaria]['cheque_ano'])?$monto:($vec[$cuenta_bancaria]['cheque_ano']+$monto);
						    }



						}//fin for

                if(isset($vec)){

						foreach($vec as $ve){


							if(!isset($ve["deposito_dia"])){$ve["deposito_dia"]=0;}
							if(!isset($ve["deposito_mes"])){$ve["deposito_mes"]=0;}
							if(!isset($ve["deposito_ano"])){$ve["deposito_ano"]=0;}

							if(!isset($ve["nota_credito_dia"])){$ve["nota_credito_dia"]=0;}
							if(!isset($ve["nota_credito_mes"])){$ve["nota_credito_mes"]=0;}
							if(!isset($ve["nota_credito_ano"])){$ve["nota_credito_ano"]=0;}

							if(!isset($ve["nota_debito_dia"])){$ve["nota_debito_dia"]=0;}
							if(!isset($ve["nota_debito_mes"])){$ve["nota_debito_mes"]=0;}
							if(!isset($ve["nota_debito_ano"])){$ve["nota_debito_ano"]=0;}

							if(!isset($ve["cheque_dia"])){$ve["cheque_dia"]=0;}
							if(!isset($ve["cheque_mes"])){$ve["cheque_mes"]=0;}
							if(!isset($ve["cheque_ano"])){$ve["cheque_ano"]=0;}

							echo $ve["deposito_dia"]." + ".$ve["nota_credito_dia"]." - ".$ve["nota_debito_dia"]." + ".$ve["cheque_dia"]." <br>";

						$disponibilidad_libro = ($ve["deposito_dia"]+$ve["nota_credito_dia"]) - ($ve["nota_debito_dia"]+$ve["cheque_dia"]);

						$actualiza = "     disponibilidad_libro = '".$disponibilidad_libro."',
												  deposito_dia  = '".$ve["deposito_dia"]."',
												  deposito_mes  = '".$ve["deposito_mes"]."',
												  deposito_ano  = '".$ve["deposito_ano"]."',

												  nota_credito_dia  = '".$ve["nota_credito_dia"]."',
												  nota_credito_mes  = '".$ve["nota_credito_mes"]."',
												  nota_credito_ano  = '".$ve["nota_credito_ano"]."',

												  nota_debito_dia  = '".$ve["nota_debito_dia"]."',
												  nota_debito_mes  = '".$ve["nota_debito_mes"]."',
												  nota_debito_ano  = '".$ve["nota_debito_ano"]."',

												  cheque_dia  = '".$ve["cheque_dia"]."',
												  cheque_mes  = '".$ve["cheque_mes"]."',
												  cheque_ano  = '".$ve["cheque_ano"]."' ";



						$sql  = "cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and cod_dep='".$cod_dep_arrd05."' and cuenta_bancaria = '".$ve["cuenta_bancaria"]."' " ;
						$sql1 = " UPDATE cstd02_cuentas_bancarias SET ".$actualiza." WHERE ".$sql;
//                        echo $sql1."<br>";
						$this->cstd03_cheque_cuerpo->execute($sql1);

						}
                }

unset($vec);
unset($ve);

}//fin foreach



$this->render("funcion");

}//fin function










function actualizar_numeros(){

$sql1 = " UPDATE cstd03_cheque_numero SET situacion=3 WHERE ";
$sql2 = " UPDATE cstd03_cheque_numero SET situacion=4 WHERE ";

$sql1_aux = "";
$sql2_aux = "";

$array = $this->cstd03_cheque_cuerpo->findAll("cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and (cod_dep=2008 or cod_dep=2009)");

		   foreach($array as $aux){

		   	    $cod_inst                   =   $aux['cstd03_cheque_cuerpo']['cod_inst'];
		     	$cod_dep                    =   $aux['cstd03_cheque_cuerpo']['cod_dep'];
			 	$ano_movimiento             =   $aux['cstd03_cheque_cuerpo']['ano_movimiento'];
			 	$cod_entidad_bancaria       =   $aux['cstd03_cheque_cuerpo']['cod_entidad_bancaria'];
			 	$cod_sucursal               =   $aux['cstd03_cheque_cuerpo']['cod_sucursal'];
			 	$cuenta_bancaria            =   $aux['cstd03_cheque_cuerpo']['cuenta_bancaria'];
			 	$numero_cheque              =   $aux['cstd03_cheque_cuerpo']['numero_cheque'];
			 	$condicion_actividad        =   $aux['cstd03_cheque_cuerpo']['condicion_actividad'];
			    $cond   = " cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque='".$numero_cheque."'";
                if($condicion_actividad==1){
                	if($sql1_aux==""){
                	  $sql1_aux .= " (".$cond.") ";
                	}else{
                	  $sql1_aux .= " or (".$cond.") ";
                	}
                }else{
                	 if($sql2_aux==""){
                	  $sql2_aux .= " (".$cond.") ";
                	}else{
                	  $sql2_aux .= " or (".$cond.") ";
                	}
                }

			}//fin for


$array = $this->cstd03_movimientos_manuales->findAll("cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and (cod_dep=2008 or cod_dep=2009) and tipo_documento=4");

		   foreach($array as $aux){
		   	    $cod_inst                   =   $aux['cstd03_movimientos_manuales']['cod_inst'];
		   	    $cod_dep                    =   $aux['cstd03_movimientos_manuales']['cod_dep'];
			 	$ano_movimiento             =   $aux['cstd03_movimientos_manuales']['ano_movimiento'];
			 	$cod_entidad_bancaria       =   $aux['cstd03_movimientos_manuales']['cod_entidad_bancaria'];
			 	$cod_sucursal               =   $aux['cstd03_movimientos_manuales']['cod_sucursal'];
			 	$cuenta_bancaria            =   $aux['cstd03_movimientos_manuales']['cuenta_bancaria'];
			 	$numero_documento           =   $aux['cstd03_movimientos_manuales']['numero_documento'];
			 	$condicion_actividad        =   $aux['cstd03_movimientos_manuales']['condicion_actividad'];
			    $cond   = " cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque='".$numero_documento."'";
                if($condicion_actividad==1){
                	if($sql1_aux==""){
                	  $sql1_aux .= " (".$cond.") ";
                	}else{
                	  $sql1_aux .= " or (".$cond.") ";
                	}
                }else{
                	 if($sql2_aux==""){
                	  $sql2_aux .= " (".$cond.") ";
                	}else{
                	  $sql2_aux .= " or (".$cond.") ";
                	}
                }

			}//fin for

if($sql1_aux!=""){
$sql1 .= $sql1_aux;
$this->cstd03_cheque_cuerpo->execute($sql1);
}



if($sql2_aux!=""){
$sql2 .= $sql2_aux;
$this->cstd03_cheque_cuerpo->execute($sql2);
}

$this->render("funcion");
}//fin function



function script_actualizar_registro_titulo(){

set_time_limit(0);
ini_set ("memory_limit", "2048M");

$this->layout="ajax";


$datos    = $this->cnmd06_datos_registro_titulo->findAll();
$contador = 0;

foreach($datos as $ve){
	$cedula            = $ve["cnmd06_datos_registro_titulo"]["cedula"];
	$cod_profesion     = $ve["cnmd06_datos_registro_titulo"]["cod_profesion"];
	$cod_especialidad  = $ve["cnmd06_datos_registro_titulo"]["cod_especialidad"];

	$radom  = rand();
	$cont   = $this->cnmd06_especialidades->findCount(" cod_profesion='".$cod_profesion."' and cod_especialidad='".$cod_especialidad."'  and ".$radom."=".$radom."    ");
	$cont2  = $this->cnmd06_profesiones->findCount("    cod_profesion='".$cod_profesion."' and ".$radom."=".$radom."    ");

    if($cont==0 || $cont2==0){
    	$contador++;
		$this->cnmd06_datos_registro_titulo->execute(" DELETE FROM cnmd06_datos_registro_titulo WHERE cedula='".$cedula."' and cod_profesion='".$cod_profesion."' and cod_especialidad='".$cod_especialidad."'  ");
    }

}//fin foreach

echo $contador;

$this->render("funcion");

}//fin foreach



  **/


















?>
