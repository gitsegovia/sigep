<?php
 class Cscp03registrocotizacionController extends AppController{
	 var $name='cscp03_registro_cotizacion';
     var $uses = array('ccfd04_cierre_mes','v_cscd03_cotizacion','cscd01_catalogo','cugd02_direccionsuperior','cpcd02','ccfd03_instalacion',
                       'cugd02_direccion','cugd02_coordinacion','cugd02_secretaria','cugd02_division','cugd02_departamento','cugd02_oficina',
                       'cscd02_solicitud_numero','cscd02_solicitud_encabezado','cscd02_solicitud_cuerpo','cscd03_cotizacion_encabezado',
                       'cscd03_cotizacion_cuerpo', 'cscd01_unidad_medida', 'cscd02_solicitud_encabezado_anulado', 'v_cscd04_rif', 'v_cscd02_solicitud_cuerpo_catalgo');
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











function cargar_script(){

$this->layout = "ajax";



}//fin else








function beforeFilter(){
					$this->checkSession();
}//fin function




function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector!=null){
   	  	  if($extra==null){
   	  	foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]="0".$x;
        	}else{
	           $Var[$x]=$x;
        	}
	    }//fin each
   	  }else{
          foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]=$extra.".0".$x;
        	}else{
	           $Var[$x]=$extra.".".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }



   }//fin AddCero







function eliminar_producto($var1=null, $var2=null, $var3=null, $var4=null){

   $this->layout = "ajax";

   $sql  = "delete from cscd02_solicitud_cuerpo  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano_solicitud='".$var1."' and numero_solicitud='".$var2."' and codigo_prod_serv='".$var3."'  ";

   if($this->cscd02_solicitud_cuerpo->execute($sql)>1){
      $this->set('errorMessage', 'Los datos fueron eliminados correctamente');
   }else{
   	  $this->set('errorMessage', 'No pudo ser eliminado correctamente');
   }//fin



        $this->selecion_numero($var2);
        $this->render("selecion_numero");







}//produncto














function eliminar_producto_consulta($var1=null, $var2=null, $var3=null, $var4=null){

   $this->layout = "ajax";
   $sql = "";

   $sql  = "delete from cscd03_cotizacion_cuerpo  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano_cotizacion='".$var1."' and numero_cotizacion='".$var2."' and codigo_prod_serv='".$var3."' and rif='".$var4."'  ";

   if($this->cscd03_cotizacion_cuerpo->execute($sql)>1){
   		$existe_cuerpo = $this->cscd03_cotizacion_cuerpo->findCount($this->condicion()." and ano_cotizacion='$var1' and numero_cotizacion='$var2' and rif='$var4'");
   		//echo "el numero de cuerpo es: $existe_cuerpo";

   		if($existe_cuerpo == 0){
			$delete_encabezado = "DELETE FROM cscd03_cotizacion_encabezado WHERE ".$this->condicion()." and ano_cotizacion='$var1' and numero_cotizacion='$var2' and rif='$var4';";
			$sql_update_solicitud = "UPDATE cscd02_solicitud_encabezado set rif='0', ano_cotizacion='0', numero_cotizacion='0' WHERE ".$this->condicion()." and ano_cotizacion='$var1' and numero_cotizacion='$var2' and rif='$var4';";
			$this->cscd03_cotizacion_encabezado->execute($sql_update_solicitud);

			$this->set('Message_existe', 'LA COTIZACION HA SIDO ELIMINADA');
			//$this->buscar_index();
			//$this->render('buscar_index');
   		}else{
   			$this->set('Message_existe', 'Los datos fueron eliminados correctamente');
   		}


   }else{
   	  $this->set('errorMessage', 'No pudo ser eliminado correctamente');
   }//fin

       // $this->selecion_numero($var2);
        //$this->render("selecion_numero");

}//produncto


























function concatenaRif($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'.0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.'.'.$x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = $x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function

   function Formato1($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-3,1)=='.') {
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
        $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,2,'.','');
    }

function Formato2($monto){
    	return number_format($monto,2,",",".");
    }




function index(){
 $this->layout = "ajax";
}

function index2(){
 $this->layout = "ajax";
 $ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 //$year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = $this->ano_ejecucion();
 //echo "el ano ejecucion es: ".$ano;
 //foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
 $this->set('ano',$ano);
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep')." and  ano_solicitud= '$ano' and (numero_cotizacion='0' or numero_cotizacion is null)";
 $lista = $this->cscd02_solicitud_encabezado->generateList($condicion." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", ' numero_solicitud ASC', null, '{n}.cscd02_solicitud_encabezado.numero_solicitud', '{n}.cscd02_solicitud_encabezado.numero_solicitud');
 $this->AddCero('lista_numero', $lista);
 $lista = $this->cpcd02->generateList(null, ' rif ASC', null, '{n}.cpcd02.rif', '{n}.cpcd02.rif');
 $this->set('lista_rif', $lista);


}//fin function


function selecion_numero($var=null){
 $this->layout = "ajax";
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 //$year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = $this->ano_ejecucion();
 //foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}

if($var!=null){
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_solicitud='.$ano.' and numero_solicitud='.$var." and numero_cotizacion='0'";
$numero = $this->cscd02_solicitud_encabezado->findAll($condicion);





foreach($numero as $num){

$fecha = $num["cscd02_solicitud_encabezado"]["fecha_solicitud"];
$mes = '';
$year = '';
if($fecha!=''){

$year = $fecha[0].$fecha[1].$fecha[2].$fecha[3];
$mes = $fecha[5].$fecha[6];
$dia = $fecha[8].$fecha[9];


}
	echo'<script>';
		    echo'document.getElementById("solicitud_cotizacion_ano").value ="'.$num["cscd02_solicitud_encabezado"]["ano_solicitud"].'"; ';
		    echo'document.getElementById("solicitud_cotizacion_fecha").value ="'.$dia.'/'.$mes.'/'.$year.'"; ';
		    echo'document.getElementById("cotizacion_ano").value ="'.$ano.'"; ';
		    echo'document.getElementById("cotizacion_fecha").value ="'.date('d/m/Y').'"; ';
	echo'</script>';
}//fin foreach


 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_solicitud='.$ano.' and numero_solicitud='.$var.' ';

 $lista_sin_iva = $this->v_cscd02_solicitud_cuerpo_catalgo->findAll($condicion." and not(cod_partida=403 and cod_generica=18 and cod_especifica=1 and cod_sub_espec=0) ",null, 'codigo_prod_serv DESC', null);
 $this->set('lista_sin_iva', $lista_sin_iva);

 $lista_iva = $this->v_cscd02_solicitud_cuerpo_catalgo->findAll($condicion." and (cod_partida=403 and cod_generica=18 and cod_especifica=1 and cod_sub_espec=0) ",null, 'codigo_prod_serv DESC', null);
 $this->set('lista_iva', $lista_iva);





 $lista_unidades = $this->cscd01_unidad_medida->findAll(null,null, 'cod_medida ASC', null);
 $this->set('unidades', $lista_unidades);


}else{
    echo'<script>';
    echo'document.getElementById("solicitud_cotizacion_ano").value =""; ';
    echo'document.getElementById("solicitud_cotizacion_fecha").value =""; ';

    echo'document.getElementById("cotizacion_ano").value =""; ';
    echo'document.getElementById("cotizacion_fecha").value =""; ';
	echo'</script>';
}//fin else


}//fin function




function selecion_rif($var=null){
 $this->layout = "ajax";
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 //$year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = $this->ano_ejecucion();
 //foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}

if($var!=null){
$condicion = "rif='".$var."' ";
$rif = $this->cpcd02->findAll($condicion, null, 'rif ASC', null);
foreach($rif as $r){
	echo'<script>';
    echo'document.getElementById("rif_nombre").value ="'.$r["cpcd02"]["denominacion"].'"; ';
    echo'document.getElementById("rif_direccion").value ="'.$r["cpcd02"]["direccion_comercial"].'"; ';
    echo'</script>';
}//fin foreach

}else{
    echo'<script>';
    echo'document.getElementById("rif_nombre").value =""; ';
    echo'document.getElementById("rif_direccion").value =""; ';
    echo'</script>';
}//fin else

}//fin function





function guardar(){
   $this->layout = "ajax";

   $i_lenght = $this->data['cscp03_registro_cotizacion']['cuenta_i'];

   $solicitud_cotizacion_ano     =  "";
   $solicitud_cotizacion_numero  =  "";
   $solicitud_cotizacion_fecha   =  "";
   $cotizacion_ano               =  "";
   $cotizacion_numero            =  "";
   $cotizacion_fecha             =  "";
   $fecha_proceso                =  "";
   $rif_numero                   =  "";
   $rif_nombre                   =  "";
   $rif_direccion                =  "";

   $solicitud_cotizacion_ano      =   $this->data['cscp03_registro_cotizacion']['solicitud_cotizacion_ano'];
   $solicitud_cotizacion_numero   =   $this->data['cscp03_registro_cotizacion']['solicitud_cotizacion_numero'];
   $solicitud_cotizacion_fecha    =   $this->data['cscp03_registro_cotizacion']['solicitud_cotizacion_fecha'];
   $cotizacion_ano                =   $this->data['cscp03_registro_cotizacion']['cotizacion_ano'];
   $cotizacion_numero             =   $this->data['cscp03_registro_cotizacion']['cotizacion_numero'];
   $cotizacion_fecha              =   $this->data['cscp03_registro_cotizacion']['cotizacion_fecha'];
   $fecha_proceso                 =   date('Y-m-d');
   $rif_numero                    =   $this->data['cscp03_registro_cotizacion']['rif_numero'];
   $rif_nombre                    =   $this->data['cscp03_registro_cotizacion']['rif_nombre'];
   $rif_direccion                 =   $this->data['cscp03_registro_cotizacion']['rif_direccion'];



    if($solicitud_cotizacion_ano==""){    $campo_aux="AÑO DE LA SOLICITUD";  }
	if($solicitud_cotizacion_numero==""){ $campo_aux="NÚMERO DE LA SOLICITUD";  }
	if($solicitud_cotizacion_fecha==""){  $campo_aux="FECHA DE LA SOLICITUD";  }
	if($cotizacion_ano==""){              $campo_aux="AÑO DE LA COTIZACIÓN";  }
	if($cotizacion_numero==""){           $campo_aux="NÚMERO DE LA COTIZACIÓN";  }
	if($cotizacion_fecha==""){            $campo_aux="FECHA DE LA COTIZACIÓN";  }
	if($rif_numero==""){                  $campo_aux="RIF";  }


if(!empty($solicitud_cotizacion_ano) && !empty($solicitud_cotizacion_numero) && !empty($solicitud_cotizacion_fecha) && !empty($cotizacion_ano) && !empty($cotizacion_numero) && !empty($cotizacion_fecha) && !empty($rif_numero)){

			if($solicitud_cotizacion_ano==""){    $campo_aux="AÑO DE LA SOLICITUD";  }
			if($solicitud_cotizacion_numero==""){ $campo_aux="NÚMERO DE LA SOLICITUD";  }
			if($solicitud_cotizacion_fecha==""){  $campo_aux="FECHA DE LA SOLICITUD";  }
			if($cotizacion_ano==""){              $campo_aux="AÑO DE LA COTIZACIÓN";  }
			if($cotizacion_numero==""){           $campo_aux="NÚMERO DE LA COTIZACIÓN";  }
			if($cotizacion_fecha==""){            $campo_aux="FECHA DE LA COTIZACIÓN";  }
			if($rif_numero==""){                  $campo_aux="RIF";  }

  if($solicitud_cotizacion_ano!="" && $solicitud_cotizacion_numero!="" && $solicitud_cotizacion_fecha!="" && $cotizacion_ano!="" && $cotizacion_numero!="" && $cotizacion_fecha!="" && $rif_numero!=""){



       if($this->cscd02_solicitud_encabezado->findCount($this->condicion()." and  ano_solicitud = '".$solicitud_cotizacion_ano."' and  numero_solicitud='$solicitud_cotizacion_numero' and numero_cotizacion='0' ")!=0){

			$sqlcp2e = "SELECT denominacion, cod_estado, cod_municipio FROM cpcd02 WHERE rif = '$rif_numero' LIMIT 1;";
			$sqlcp2 = $this->cscd03_cotizacion_encabezado->execute($sqlcp2e);
			$deno_cp2 = $sqlcp2[0][0]['denominacion'];
			$cod_est_cp2 = $sqlcp2[0][0]['cod_estado'];
			$cod_munc_cp2 = $sqlcp2[0][0]['cod_municipio'];

			$sqlcugd1e = "SELECT denominacion, conocido FROM cugd01_municipios WHERE cod_republica = 1 AND cod_estado = $cod_est_cp2 AND cod_municipio = $cod_munc_cp2 LIMIT 1;";
			$sqlcugd1 = $this->cscd03_cotizacion_encabezado->execute($sqlcugd1e);
			$deno_municipio = $sqlcugd1[0][0]['denominacion'];
			$conocido = $sqlcugd1[0][0]['conocido'];
			if($conocido == null || $conocido == ''){
				$conocido = $deno_municipio;
			}

							       $sql1  = "BEGIN; INSERT INTO cscd03_cotizacion_encabezado (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_cotizacion, numero_cotizacion, fecha_cotizacion, ano_solicitud, numero_solicitud, fecha_proceso, ano_ordencompra, numero_ordencompra) ";
							       $sql1 .= "VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."', '".$rif_numero."', '".$cotizacion_ano."', '".$cotizacion_numero."', '".$cotizacion_fecha."', '".$solicitud_cotizacion_ano."', '".$solicitud_cotizacion_numero."', '".$fecha_proceso."', '0', '0');";
							       $sw = $this->cscd03_cotizacion_encabezado->execute($sql1);
							 	if($sw >1 ){

										$sql2="INSERT INTO cscd03_cotizacion_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_cotizacion, numero_cotizacion, codigo_prod_serv, descripcion, cod_medida, cantidad, precio_unitario, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_partida, cod_generica, cod_especifica, cod_sub_espec) VALUES ";

									  	 for($i=0; $i<$i_lenght; $i++){

									  	 	 if ($i < 50) {$nom_model = "cscp03_registro_cotizacion";}
                                             if ($i > 50 && $i <= 100 ) {$nom_model = "cscp03_registro_cotizacion_a";}
                                             if ($i > 100 && $i <= 150 ) {$nom_model = "cscp03_registro_cotizacion_b";}
                                             if ($i > 150 && $i <= 200 ) {$nom_model = "cscp03_registro_cotizacion_c";}
                                             if ($i > 200 && $i <= 250 ) {$nom_model = "cscp03_registro_cotizacion_d";}
                                             if ($i > 250 && $i <= 300 ) {$nom_model = "cscp03_registro_cotizacion_e";}

									      	 $var[$i]['codigo_prod_serv']= $this->data[$nom_model]['codigo_prod_serv_'.$i];
									      	 $var[$i]['cod_medida']      = $this->data[$nom_model]['cod_medida_'.$i];
									      	 $var[$i]['cantidad']        = str_replace(',', '.', $this->data[$nom_model]['cantidad_'.$i]);
									       	 $var[$i]['precio']          = $this->FormatoBd($this->data[$nom_model]['precio_'.$i]);
									       	 $var[$i]['descripcion']     = $this->data[$nom_model]['descripcion_'.$i];
									       	 $var[$i]['cod_sector']     = $this->data[$nom_model]['cod_sector_'.$i];
									       	 $var[$i]['cod_programa']     = $this->data[$nom_model]['cod_programa_'.$i];
									       	 $var[$i]['cod_sub_prog']     = $this->data[$nom_model]['cod_sub_prog_'.$i];
									       	 $var[$i]['cod_proyecto']     = $this->data[$nom_model]['cod_proyecto_'.$i];
									       	 $var[$i]['cod_partida']     = $this->data[$nom_model]['cod_partida_'.$i];
									       	 $var[$i]['cod_generica']     = $this->data[$nom_model]['cod_generica_'.$i];
									       	 $var[$i]['cod_especifica']     = $this->data[$nom_model]['cod_especifica_'.$i];
									       	 $var[$i]['cod_sub_espec']     = $this->data[$nom_model]['cod_sub_espec_'.$i];

											if($i==$i_lenght-1){
												$sql2 .= "('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."','".$rif_numero."', '".$cotizacion_ano."', '$cotizacion_numero', '".$var[$i]['codigo_prod_serv']."', '".$var[$i]['descripcion']."', '".$var[$i]['cod_medida']."', '".$var[$i]['cantidad']."', '".$var[$i]['precio']."', '".$var[$i]['cod_sector']."', '".$var[$i]['cod_programa']."', '".$var[$i]['cod_sub_prog']."', '".$var[$i]['cod_proyecto']."', '".$var[$i]['cod_partida']."', '".$var[$i]['cod_generica']."', '".$var[$i]['cod_especifica']."', '".$var[$i]['cod_sub_espec']."');";
											}else{
												$sql2 .= "('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."','".$rif_numero."', '".$cotizacion_ano."', '$cotizacion_numero', '".$var[$i]['codigo_prod_serv']."', '".$var[$i]['descripcion']."', '".$var[$i]['cod_medida']."', '".$var[$i]['cantidad']."', '".$var[$i]['precio']."', '".$var[$i]['cod_sector']."', '".$var[$i]['cod_programa']."', '".$var[$i]['cod_sub_prog']."', '".$var[$i]['cod_proyecto']."', '".$var[$i]['cod_partida']."', '".$var[$i]['cod_generica']."', '".$var[$i]['cod_especifica']."', '".$var[$i]['cod_sub_espec']."'),";
											}

											$sql4 = "UPDATE cscd01_catalogo SET precio_referencia='".$var[$i]['precio']."', fecha_precio='$cotizacion_fecha', denominacion_fuente='$deno_cp2', distancia_ciudad='$conocido' WHERE codigo_prod_serv = '".$var[$i]['codigo_prod_serv']."';";
											$sw4 =  $this->cscd03_cotizacion_cuerpo->execute($sql4);
											if($sw4 > 1){
												$sw4e = 2;
											}else{
												$sw4e = 1;
											}
									     }//fin for

										$sw2 =  $this->cscd03_cotizacion_cuerpo->execute($sql2);
							            if($sw2>1){
							            	if($sw4e > 1){
										    	$sql3  = "UPDATE cscd02_solicitud_encabezado SET fecha_proceso='$cotizacion_fecha', rif='$rif_numero', ano_cotizacion='$cotizacion_ano', numero_cotizacion='$cotizacion_numero' WHERE ".$this->condicion()." and  ano_solicitud = '".$solicitud_cotizacion_ano."' and  numero_solicitud='$solicitud_cotizacion_numero';";
												$sw3 = $this->cscd02_solicitud_encabezado->execute($sql3);

												if($sw3>1){
										        	$this->cscd02_solicitud_encabezado->execute("COMMIT;");
													$this->set('msg', 'La COTIZACIÓN fue Generada exitosamente');
												}else{
													$this->cscd02_solicitud_encabezado->execute("ROLLBACK;");
													$this->set('errorMessage', 'NO SE LOGRO CREAR LA COTIZACIÓN - POR FAVOR INTENTELO DE NUEVO');
												}//fin else
							            	}else{
												$this->cscd02_solicitud_encabezado->execute("ROLLBACK;");
												$this->set('errorMessage', 'NO SE LOGRO CREAR LA COTIZACIÓN - POR FAVOR INTENTELO DE NUEVO');
							            	}
							             }else{
										    $this->cscd02_solicitud_encabezado->execute("ROLLBACK;");
										    $this->set('errorMessage', 'NO SE LOGRO CREAR LA COTIZACIÓN - POR FAVOR INTENTELO DE NUEVO');
										}//fin else
									}else{
										$this->cscd03_cotizacion_encabezado->execute("ROLLBACK;");
										$this->set('errorMessage', 'NO SE LOGRO CREAR LA COTIZACIÓN - POR FAVOR INTENTELO DE NUEVO');
									//return;
									}//fin else




             }else{
        	    $this->set('errorMessage', 'POR FAVOR INTENTELO DE NUEVO - LA SOLICITUD YA FUE USADA POR OTRA COTIZACIÓN ');
             }//fin else

		}else{
        	$this->set('errorMessage', 'NO SE LOGRO CREAR LA COTIZACIÓN FALTA '.$campo_aux.' - POR FAVOR INTENTELO DE NUEVO');
        }//fin else

   }else{
     	$this->set('errorMessage', 'NO SE LOGRO CREAR LA COTIZACIÓN '.$campo_aux.'  - POR FAVOR INTENTELO DE NUEVO');
   }//fin else




 $ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 //$year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = $this->ano_ejecucion();
 //foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_solicitud='.$ano." and numero_cotizacion='0'";
 $lista = $this->cscd02_solicitud_encabezado->generateList($condicion, ' numero_solicitud ASC', null, '{n}.cscd02_solicitud_encabezado.numero_solicitud', '{n}.cscd02_solicitud_encabezado.numero_solicitud');
 $this->AddCero('lista_numero', $lista);

 $lista = $this->cpcd02->generateList(null, ' rif ASC', null, '{n}.cpcd02.rif', '{n}.cpcd02.rif');
 $this->set('lista_rif', $lista);

$this->index();
$this->render("index");

}//fin guardar

function show_rif($pista=null){
	$this->layout = "ajax";
	if($pista != null){
		$pista = strtoupper($pista);
		if($this->cpcd02->findCount("mayus_acentos(denominacion) LIKE '%$pista%' OR mayus_acentos(rif) LIKE '%$pista%'") > 0){
			$proveedor= $this->cpcd02->generateList($conditions = "condicion_actividad=1 and mayus_acentos(denominacion) LIKE '%$pista%' OR condicion_actividad=1 and mayus_acentos(rif) LIKE '%$pista%'", $order = null, $limit = null, '{n}.cpcd02.rif', '{n}.cpcd02.denominacion');
			$this->concatenaRif($proveedor, 'proveedor');
		}else{
			$this->set('msgError', 'NO SE ENCONTRO NINGUN PROVEEDOR REGISTRADO');
		}
	}
}









function buscar_year($var1=null){

    $this->layout = "ajax";
    $_SESSION['ano_compra'] = $var1;
	$ano = $var1;
	$this->set('ano', $ano);
	$listaRif = $this->v_cscd04_rif->generateList($conditions = $this->condicion()."  and ano_cotizacion='".$ano."' ", $order = null, $limit = null, '{n}.v_cscd04_rif.rif', '{n}.v_cscd04_rif.denominacion');
	$this->concatena_sin_cero($listaRif, 'listaRif');


}//fin function











function show_rif_buscar($pista=null){
	$this->layout = "ajax";


 if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}
  $this->set('ano_compra', $ano);


	if($pista != null){
		$pista = strtoupper($pista);
		if($this->v_cscd04_rif->findCount($this->condicion()." and ano_cotizacion='".$ano."' and (mayus_acentos(denominacion) LIKE '%$pista%' OR mayus_acentos(rif) LIKE '%$pista%')") > 0){
			$proveedor= $this->v_cscd04_rif->generateList($conditions = $this->condicion()." and ano_cotizacion='".$ano."'  and (mayus_acentos(denominacion) LIKE '%$pista%' OR mayus_acentos(rif) LIKE '%$pista%')", $order = null, $limit = null, '{n}.v_cscd04_rif.rif', '{n}.v_cscd04_rif.denominacion');
			$this->concatenaRif($proveedor, 'proveedor');
		}else{
			$this->set('msgError', 'NO SE ENCONTRO NINGUN PROVEEDOR REGISTRADO PARA ESTA DEPENDENCIA');
		$this->set('ano', $ano);
		$listaRif = $this->v_cscd04_rif->generateList($conditions = $this->condicion()." and ano_cotizacion='".$ano."' ", $order = null, $limit = null, '{n}.v_cscd04_rif.rif', '{n}.v_cscd04_rif.denominacion');
		$this->concatena_sin_cero($listaRif, 'listaRif');
		}
	}else{
		$this->set('ano', $ano);
		$listaRif = $this->v_cscd04_rif->generateList($conditions = $this->condicion()." and ano_cotizacion='".$ano."' ", $order = null, $limit = null, '{n}.v_cscd04_rif.rif', '{n}.v_cscd04_rif.denominacion');
		$this->concatena_sin_cero($listaRif, 'listaRif');
	}
}//fin function




function show_cotizacion($rif=null){
	$this->layout = "ajax";
	if($rif != null){
		$this->set('rif', $rif);

		$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
				$objeto_rif = "";
				$denominacion_rif = "";
				$porcentaje_iva  = "";
				$direccion_comercial_rif = "";
				$datos_contrato_obra_anteriores = "";
				$datos_orden_pagos_anteriores_partidas = "";

				foreach($rif_datos as $aux_2){
					$denominacion_rif          = $aux_2['cpcd02']['denominacion'];
					$direccion_comercial_rif   = $aux_2['cpcd02']['direccion_comercial'];
					$objeto_rif                = $aux_2['cpcd02']['objeto'];
					$exento_islr_cooperativa   = $aux_2['cpcd02']['exento_islr_cooperativa'];
					$fecha_actualizacion   	   = $aux_2['cpcd02']['fecha_actualizacion'];
				}//fin foreach


				// Para indicar si el proveedor esta o no esta autorizado para la fecha actual del documento de autorizacion de pago
				// Condicion: CUANDO FECHA_ACTUALIZACION < A FECHA DE DOCUMENTO DE AUTORIZACIÓN DE PAGO DE ORDEN DE COMPRA.
				$fecha_1 = strtotime (date('Y-m-d'));
				$fecha_2 = strtotime ("$fecha_actualizacion");
				if($fecha_2 < $fecha_1){
					$this->set('errorMessage', 'PROVEEDOR NO ESTA ACTUALIZADO EN EL REGISTRO DE PROVEEDORES Y CONTRATISTAS');
					echo "<script>if (confirm('PROVEEDOR NO ESTA ACTUALIZADO EN EL REGISTRO DE PROVEEDORES Y CONTRATISTAS')){}else{ document.getElementById('cotizacion_fecha').focus(); return false;}</script>";
				}

				$this->set('fecha_actualizacion', $fecha_actualizacion);
	}

}

function show_denominacion($rif=null){
	$this->layout = "ajax";
	if($rif!= null){
		$rif = strtoupper($rif);
		$deno_rif = $this->cpcd02->field('cpcd02.denominacion', $conditions = "mayus_acentos(cpcd02.rif)='$rif'", $order =null);
		$this->set('deno_rif', strtoupper($deno_rif));

				$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
				$objeto_rif = "";
				$denominacion_rif = "";
				$porcentaje_iva  = "";
				$direccion_comercial_rif = "";
				$datos_contrato_obra_anteriores = "";
				$datos_orden_pagos_anteriores_partidas = "";

				foreach($rif_datos as $aux_2){
					$denominacion_rif          = $aux_2['cpcd02']['denominacion'];
					$direccion_comercial_rif   = $aux_2['cpcd02']['direccion_comercial'];
					$objeto_rif                = $aux_2['cpcd02']['objeto'];
					$exento_islr_cooperativa   = $aux_2['cpcd02']['exento_islr_cooperativa'];
					$fecha_actualizacion   	   = $aux_2['cpcd02']['fecha_actualizacion'];
				}//fin foreach


				// Para indicar si el proveedor esta o no esta autorizado para la fecha actual del documento de autorizacion de pago
				// Condicion: CUANDO FECHA_ACTUALIZACION < A FECHA DE DOCUMENTO DE AUTORIZACIÓN DE PAGO DE ORDEN DE COMPRA.
				$fecha_1 = strtotime (date('Y-m-d'));
				$fecha_2 = strtotime ("$fecha_actualizacion");
				if($fecha_2 < $fecha_1){
					$this->set('errorMessage', 'PROVEEDOR NO ESTA ACTUALIZADO EN EL REGISTRO DE PROVEEDORES Y CONTRATISTAS');
				}

				$this->set('fecha_actualizacion', $fecha_actualizacion);
	}
}

function show_direccion($rif=null){
	$this->layout = "ajax";
	if($rif!= null){
		$rif = strtoupper($rif);
		$dir_rif = $this->cpcd02->field('cpcd02.direccion_comercial', $conditions = "mayus_acentos(cpcd02.rif)='$rif'", $order =null);
		$this->set('dir_rif', strtoupper($dir_rif));
	}
}

function valida($rif=null, $num_cotizacion=null){
	$this->layout = "ajax";
	$ano = $this->ano_ejecucion();
	if($num_cotizacion!=null){
		$num = $this->cscd03_cotizacion_encabezado->findCount($this->condicion()."and ano_cotizacion='$ano' and rif='$rif' and numero_cotizacion='$num_cotizacion'");
		if($num > 0){
			$this->set('msg', 'YA EXISTE UNA SOLICITUD REGISTRADA CON ESE NUMERO');
		}else{
			$this->set('existe', true);
		}
	}
}


function consulta($pag_num=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $opcion=null){
  $this->layout = "ajax";

  if($opcion!=null){

/*
  	 $sql  = "delete from cscd03_cotizacion_cuerpo   where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano_cotizacion=".$var1." and numero_cotizacion='$var2' and codigo_prod_serv=".$var3." ";
       $this->cscd03_cotizacion_cuerpo->execute($sql);

       if($this->cscd03_cotizacion_cuerpo->findCount("cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano_cotizacion=".$var1." and numero_cotizacion='$var2'") == '0'){
       	$sql  = "delete from cscd03_cotizacion_encabezado  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano_cotizacion=".$var1." and numero_cotizacion='$var2'";
        $this->cscd03_cotizacion_encabezado->execute($sql);

        $sql  = "UPDATE cscd02_solicitud_encabezado SET rif='0', ano_cotizacion='0', numero_cotizacion='0' where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and numero_solicitud=".$var5."";
        $this->cscd02_solicitud_encabezado->execute($sql);

       }//fin
*/
		$this->consulta($pag_num);
        $this->render("consulta");


  }else{


 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
 if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}

   $array = $this->v_cscd03_cotizacion->findAll($condicion.'and ano_solicitud = '.$ano, 'DISTINCT numero_solicitud, rif, numero_cotizacion');

    $i = 0;
 if($pag_num==null){
 	$pag_num=0;
 }

 foreach($array as $aux){
 	$numero[$i]['numero_solicitud'] = $aux['v_cscd03_cotizacion']['numero_solicitud'];
 	$i++;
 }
 $i--;

if(isset($numero[$pag_num]['numero_solicitud'])){
$numero_solicitud = $numero[$pag_num]['numero_solicitud'];
	$fecha_solicitud = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.fecha_solicitud', $conditions = $this->condicion()." and cscd02_solicitud_encabezado.numero_solicitud='$numero_solicitud' and cscd02_solicitud_encabezado.ano_solicitud=".$ano, $order =null);
	$this->set('fecha_solicitud', $fecha_solicitud);
 $lista = $this->v_cscd03_cotizacion->findAll($condicion. ' and ano_solicitud = '.$ano.' and numero_solicitud = '.$numero[$pag_num]['numero_solicitud'], null, 'codigo_prod_serv DESC', null);
 $this->set('lista_cscd03_cotizacion_encabezado', $array);
 $this->set('lista_cscd03_cotizacion_cuerpo', $lista);
 $this->set('pag_num', $pag_num);
 $this->set('ano_ejecucion', $this->ano_ejecucion());
 $this->set('totalPages_Recordset1', $i);

}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

}//fin else

  }//fin else

}//fin consulta

function buscar_index(){
	$this->layout = "ajax";
	$ano = $this->ano_ejecucion();
	$_SESSION['ano_compra'] = $ano;
	$this->set('ano', $ano);
	$listaRif = $this->v_cscd04_rif->generateList($conditions = $this->condicion()."  and ano_cotizacion='".$ano."' ", $order = null, $limit = null, '{n}.v_cscd04_rif.rif', '{n}.v_cscd04_rif.denominacion');
	$this->concatena_sin_cero($listaRif, 'listaRif');

}

function buscar(){
	$this->layout = "ajax";
	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
    if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}
	$rif= strtoupper($this->data['cscp03_registro_cotizacion']['rif']);
	$numero_cotizacion = $this->data['cscp03_registro_cotizacion']['num_cotizacion'];
	$array = $this->v_cscd03_cotizacion->findAll($condicion.'and ano_solicitud = '.$ano, 'DISTINCT numero_solicitud, rif, numero_cotizacion');
	$cont = count($array);
    $i = 0;
 	$pag_num=0;
 	//echo "el contador es: $cont";


 foreach($array as $aux){
 	$numero[$i]['numero_solicitud'] = $aux['v_cscd03_cotizacion']['numero_solicitud'];
 	$rif_q = strtoupper($aux['v_cscd03_cotizacion']['rif']);
 	$num_cotizacion_q = $aux['v_cscd03_cotizacion']['numero_cotizacion'];
	//echo $rif_q." = ".$rif." || ".$num_cotizacion_q." = ".$numero_cotizacion." || el pag_num es: ".$i."<br/>";
 	if((strtoupper($rif_q) == strtoupper($rif)) && ($num_cotizacion_q == $numero_cotizacion)){
 		echo "<script>";
 		echo "document.getElementById('ant_up').style.display='none';";
 		echo "document.getElementById('sig_up').style.display='none';";
 		echo "document.getElementById('ant_down').style.display='none';";
 		echo "document.getElementById('sig_down').style.display='none';";
 		echo "</script>";
 		$this->consulta($i);
 		$this->render("consulta");
 		return;
 	}else if($i==($cont-1)){
		$this->set('pag_num', 0);
 		$this->set('totalPages_Recordset1', '');
 		$this->set('errorMessage', 'EL NÚMERO DE COTIZACIÓN NO FUE ENCONTRADO');
 		$this->data=array();
 		$this->buscar_index();
 		$this->render('buscar_index');
 		return;
 	}

 	$i++;
 }
 $i--;
 echo "el pag_num es: $i";
/*
if(isset($numero[$pag_num]['numero_solicitud'])){
$numero_solicitud = $numero[$pag_num]['numero_solicitud'];
	$fecha_solicitud = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.fecha_solicitud', $conditions = $this->condicion()." and cscd02_solicitud_encabezado.numero_solicitud='$numero_solicitud'", $order =null);
	$this->set('fecha_solicitud', $fecha_solicitud);
 $lista = $this->v_cscd03_cotizacion->findAll($condicion. ' and ano_solicitud = '.$ano.' and numero_solicitud = '.$numero[$pag_num]['numero_solicitud'], null, 'codigo_prod_serv DESC', null);
 $this->set('lista_cscd03_cotizacion_encabezado', $array);
 $this->set('lista_cscd03_cotizacion_cuerpo', $lista);
 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);
 $this->consulta($pag_num);
 $this->render("consulta");

}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

}//fin else
*/

}

function proveedor($rif=null){
  $this->layout="ajax";
	//echo $rif;
  if($rif!=null){
  	//$num_cotizacion = substr($num_cotizacion,0,1);
  	//echo $num_cotizacion;
    //$rif = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.rif', $conditions = $this->condicion()." and cscd03_cotizacion_encabezado.numero_cotizacion='$num_cotizacion'", $order ="numero_cotizacion ASC");
    //echo $rif;

    $razon_social = $this->cpcd02->field('cpcd02.denominacion', $conditions = "cpcd02.rif='$rif'", $order ="rif ASC");
    $direccion = $this->cpcd02->field('cpcd02.direccion_comercial', $conditions = "cpcd02.rif='$rif'", $order ="rif ASC");

    $this->set('rif', $rif);
    $this->Session->write('rif', $rif);
    $this->set('razon_social', $razon_social);
    $this->set('direccion', $direccion);
  }else{

  }
}

function cotizar($rif=null){
	$this->layout="ajax";
	if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}
	if($rif!=null){
		$lista = $this->cscd03_cotizacion_encabezado->generateList($conditions = $this->condicion()."  and ano_cotizacion='".$ano."'  and mayus_acentos(rif)=mayus_acentos('$rif')"." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $order = 'numero_cotizacion ASC', $limit = null, '{n}.cscd03_cotizacion_encabezado.numero_cotizacion', '{n}.cscd03_cotizacion_encabezado.numero_cotizacion');
		//echo $conditions;
		$this->set('lista', $lista);
		$this->set('rif', $rif);
	}
}

function show_buscar($var=null){
	$this->layout="ajax";
	//echo "el var es: ".$var;
	if($var!=null){
		echo "<script>".
			"if(document.getElementById('ano_ejecucion').value==''){
				fun_msj('EL A&Ntilde;O NO PUEDE ESTAR VACIO');
			}else{ show_save();}" .
		"</script>";
	}else{
		echo "<script>".
			"hide_save();" .
		"</script>";
	}

}

function eliminar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

	   $this->layout = "ajax";


   $solicitud_cotizacion_ano      =   $this->data['cscp03_registro_cotizacion']['solicitud_cotizacion_ano_222'];
   $solicitud_cotizacion_numero   =   $this->data['cscp03_registro_cotizacion']['solicitud_cotizacion_numero_222'];
   $solicitud_cotizacion_fecha    =   $this->data['cscp03_registro_cotizacion']['solicitud_cotizacion_fecha_222'];
   $cotizacion_ano                =   $this->data['cscp03_registro_cotizacion']['cotizacion_ano_222'];
   $cotizacion_numero             =   $this->data['cscp03_registro_cotizacion']['cotizacion_numero_222'];
   $cotizacion_fecha              =   $this->data['cscp03_registro_cotizacion']['cotizacion_fecha_222'];
   $fecha_proceso                 =   date('Y-m-d');
   $rif_numero                    =   $this->data['cscp03_registro_cotizacion']['rif_numero_222'];
   $rif_nombre                    =   $this->data['cscp03_registro_cotizacion']['rif_nombre_222'];
   $rif_direccion                 =   $this->data['cscp03_registro_cotizacion']['rif_direccion_222'];


	   $solicitud_encabezado = $this->cscd02_solicitud_encabezado->findAll($conditions = $this->condicion()." and ano_solicitud='$cotizacion_ano' and numero_solicitud='$solicitud_cotizacion_numero'", $fields = null, $order = null, $limit = 1, $page = null, $recursive = null);
	   $solicitud_cuerpo = $this->cscd02_solicitud_cuerpo->findAll($conditions = $this->condicion()." and ano_solicitud='$cotizacion_ano' and numero_solicitud='$solicitud_cotizacion_numero'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
	   foreach($solicitud_encabezado as $row1){
			$cod_presi = $row1['cscd02_solicitud_encabezado']['cod_presi'];
			$cod_entidad = $row1['cscd02_solicitud_encabezado']['cod_entidad'];
			$cod_tipo_inst = $row1['cscd02_solicitud_encabezado']['cod_tipo_inst'];
			$cod_inst = $row1['cscd02_solicitud_encabezado']['cod_inst'];
			$cod_dep = $row1['cscd02_solicitud_encabezado']['cod_dep'];
			$ano_solicitud = $row1['cscd02_solicitud_encabezado']['ano_solicitud'];
			$fecha_solicitud = $row1['cscd02_solicitud_encabezado']['fecha_solicitud'];
			$cod_dir_superior = $row1['cscd02_solicitud_encabezado']['cod_dir_superior'];
			$cod_coordinacion = $row1['cscd02_solicitud_encabezado']['cod_coordinacion'];
			$cod_secretaria = $row1['cscd02_solicitud_encabezado']['cod_secretaria'];
			$cod_direccion = $row1['cscd02_solicitud_encabezado']['cod_direccion'];
			$cod_division = $row1['cscd02_solicitud_encabezado']['cod_division'];
			$cod_departamento = $row1['cscd02_solicitud_encabezado']['cod_departamento'];
			$cod_oficina = $row1['cscd02_solicitud_encabezado']['cod_oficina'];
			$uso_destino = $row1['cscd02_solicitud_encabezado']['uso_destino'];
			$fecha_proceso = $row1['cscd02_solicitud_encabezado']['fecha_proceso'];
			$rif = $row1['cscd02_solicitud_encabezado']['rif'];
			$sql_insert_solicitud_encabezado = "BEGIN; INSERT INTO cscd02_solicitud_encabezado_anulado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_solicitud', '$solicitud_cotizacion_numero', '$fecha_solicitud', '$cod_dir_superior', '$cod_coordinacion', '$cod_secretaria', '$cod_direccion', '$cod_division', '$cod_departamento', '$cod_oficina', '$uso_destino', '$fecha_proceso', '$rif', '$cotizacion_ano', '$cotizacion_numero');";
	   }
	   $centinela = count($solicitud_cuerpo);
	   //echo "el var2 es: ".$cotizacion_numero;
	   $i=0;
		$sql_insert_solicitud_cuerpo = " INSERT INTO cscd02_solicitud_cuerpo_anulado(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_solicitud, numero_solicitud, codigo_prod_serv, descripcion, cod_medida, cantidad, ano_cotizacion, numero_cotizacion, rif) VALUES ";
	   foreach($solicitud_cuerpo as $row2){
	   		$cod_presi = $row2['cscd02_solicitud_cuerpo']['cod_presi'];
			$cod_entidad = $row2['cscd02_solicitud_cuerpo']['cod_entidad'];
			$cod_tipo_inst = $row2['cscd02_solicitud_cuerpo']['cod_tipo_inst'];
			$cod_inst = $row2['cscd02_solicitud_cuerpo']['cod_inst'];
			$cod_dep = $row2['cscd02_solicitud_cuerpo']['cod_dep'];
			$ano_solicitud = $row2['cscd02_solicitud_cuerpo']['ano_solicitud'];
			$codigo_prod_serv = $row2['cscd02_solicitud_cuerpo']['codigo_prod_serv'];
			$descripcion = $row2['cscd02_solicitud_cuerpo']['descripcion'];
			$cod_medida = $row2['cscd02_solicitud_cuerpo']['cod_medida'];
			$cantidad = $row2['cscd02_solicitud_cuerpo']['cantidad'];
			if($i == ($centinela -1)){
				$sql_insert_solicitud_cuerpo .= "('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_solicitud', '$solicitud_cotizacion_numero', '$codigo_prod_serv', '$descripcion', '$cod_medida', '$cantidad', '$cotizacion_ano', '$cotizacion_numero', '$rif');";
			}else{
				$sql_insert_solicitud_cuerpo .= "('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_solicitud', '$solicitud_cotizacion_numero', '$codigo_prod_serv', '$descripcion', '$cod_medida', '$cantidad', '$cotizacion_ano', '$cotizacion_numero', '$rif'),";
			}

			$i++;
	   }
	   $cont_solicitud = $this->cscd02_solicitud_encabezado_anulado->findCount($cond3 =$this->condicion()." and ano_solicitud='$ano_solicitud' and numero_solicitud='$solicitud_cotizacion_numero' and ano_cotizacion='$cotizacion_ano' and numero_cotizacion='$cotizacion_numero' and mayus_acentos(rif)=mayus_acentos('$rif')");
//echo "<script>alert('Conteo solicitud ".$cont_solicitud."')</script>";
		if( $cont_solicitud==0){
			$swj = $this->cscd02_solicitud_encabezado->execute($sql_insert_solicitud_cuerpo);
			$sw  = $this->cscd02_solicitud_encabezado->execute($sql_insert_solicitud_encabezado);
		}else{
			$sw=2;
		}


	   if($sw > 1){
	   		$sql1  = "delete from cscd03_cotizacion_cuerpo   where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano_cotizacion=".$cotizacion_ano." and numero_cotizacion='$cotizacion_numero' and rif='$rif';";

	    	$sql2  = "delete from cscd03_cotizacion_encabezado  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano_cotizacion=".$cotizacion_ano." and numero_cotizacion='$cotizacion_numero' and rif='$rif';";

	        $sql3  = "UPDATE cscd02_solicitud_encabezado SET rif='0', ano_cotizacion='0', numero_cotizacion='0' where ".$this->condicion()." and ano_solicitud = '".$solicitud_cotizacion_ano."'  and numero_solicitud=".$solicitud_cotizacion_numero.";";

	        $sw2 = $this->cscd02_solicitud_encabezado->execute($sql1.$sql2.$sql3);

	        if($sw2 > 1){
	        	$this->cscd02_solicitud_encabezado->execute("COMMIT;");
	        	$this->set('Message_existe', 'Los datos fueron eliminados correctamente');
				$user = $this->Session->read('nom_usuario');
				$codigo = $cod_presi.$cod_entidad.$cod_tipo_inst.$cod_inst.$cod_dep;
				$this->log('El usuario: '.$user.' con el codigo: '.$codigo.' elimino de la tablas cscd03_cotizacion la cotizacion: '.$cotizacion_numero.' del ano: '.$cotizacion_ano.' del rif: '.$rif, LOG_ELIMINAR);
	        }else{
				$this->cscd02_solicitud_encabezado->execute("ROLLBACK;");
	        }

	        $this->index();
	        $this->render("index");

	   }else{
	   		$this->set('errorMessage', 'NO SE LOGRO ELIMINAR LA COTIZACION - POR FAVOR INTENTE DE NUEVO');
	   		$this->cscd02_solicitud_encabezado->execute("ROLLBACK;");
	   		$this->index();
	        $this->render("index");
	   }


}//fin eliminar





function editar($var1=null, $pag_num=null, $var2=null, $var3=null, $var4=null, $var5=null){

	$this->layout = "ajax";

	 $this->set('i', $var1);

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
 //$array = $this->v_cscd03_cotizacion->execute('SELECT DISTINCT * FROM  v_cscd03_cotizacion where '.$condicion);

 //$year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = $this->ano_ejecucion();
 //foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}


   $array = $this->v_cscd03_cotizacion->findAll($condicion. 'and ano_solicitud = '.$ano , 'DISTINCT numero_solicitud, rif, numero_cotizacion');

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){ $numero[$i]['numero_solicitud'] = $aux['v_cscd03_cotizacion']['numero_solicitud']; $i++; } $i--;

if(isset($numero[$pag_num]['numero_solicitud'])){
 $lista = $this->v_cscd03_cotizacion->findAll($condicion. ' and ano_solicitud = '.$ano.' and numero_solicitud = '.$numero[$pag_num]['numero_solicitud'].' and codigo_prod_serv = '. $var4,  null, 'numero_cotizacion ASC', null);
 $this->set('lista_cscd03_cotizacion_cuerpo', $lista);
 $this->set('pag_num', $pag_num);
}//fin



}//fin function



function guardar_editar($pag_num=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $opcion=null){

  $this->layout = "ajax";

$i = 1 ;

         $var[$i]['codigo_prod_serv']  = $this->data['cscp03_registro_cotizacion']['codigo_prod_serv'];
      	 $var[$i]['cod_medida']        = $this->data['cscp03_registro_cotizacion']['cod_medida'];
      	 $var[$i]['cantidad']          = str_replace(',', '.', $this->data['cscp03_registro_cotizacion']['cantidad']);
       	 $var[$i]['precio']            = $this->FormatoBd($this->data['cscp03_registro_cotizacion']['precio']);
       	 $var[$i]['descripcion']       = $this->data['cscp03_registro_cotizacion']['descripcion'];
       	 $var[$i]['ano_cotizacion']    = $this->data['cscp03_registro_cotizacion']['ano_cotizacion'];
       	 $var[$i]['numero_cotizacion'] = $this->data['cscp03_registro_cotizacion']['numero_cotizacion'];
       	 $numero_cotizacion            = $this->data['cscp03_registro_cotizacion']['numero_cotizacion'];

 $ano_solicitud         =  $this->data['caop03_registro_cotizacion']['solicitud_cotizacion_ano_222'];
 $numero_solicitud      =  $this->data['caop03_registro_cotizacion']['solicitud_cotizacion_numero_222'];
 $v_cscd03_cotizacion   =  $this->v_cscd03_cotizacion->findAll($this->condicion(). " and ano_cotizacion = ".$var[$i]['ano_cotizacion']." and numero_cotizacion = '".$var[$i]['numero_cotizacion']."'", null, 'codigo_prod_serv DESC', null);

 $condicion22           =  'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_solicitud='.$ano_solicitud.' and numero_solicitud='.$numero_solicitud.' ';
 $lista_sin_iva         =  $this->v_cscd02_solicitud_cuerpo_catalgo->findAll($condicion22." and not(cod_partida=403 and cod_generica=18 and cod_especifica=1 and cod_sub_espec=0) ",null, 'codigo_prod_serv DESC', null);
 $lista_iva             =  $this->v_cscd02_solicitud_cuerpo_catalgo->findAll($condicion22." and    (cod_partida=403 and cod_generica=18 and cod_especifica=1 and cod_sub_espec=0) ",null, 'codigo_prod_serv DESC', null);

$sql  = "UPDATE cscd03_cotizacion_cuerpo SET  descripcion='".$var[$i]['descripcion']."', cantidad='".$var[$i]['cantidad']."', precio_unitario='".$var[$i]['precio']."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano_cotizacion=".$var[$i]['ano_cotizacion']."  and  numero_cotizacion='$numero_cotizacion' and  codigo_prod_serv=".$var[$i]['codigo_prod_serv']." ";
$this->cscd03_cotizacion_cuerpo->execute($sql);
$iva = 0;
foreach($v_cscd03_cotizacion as $ve_aux1){
	$codigo_prod_serv = $ve_aux1['v_cscd03_cotizacion']['codigo_prod_serv'];
	if($codigo_prod_serv==$var[$i]['codigo_prod_serv']){
		 $ve_aux1['v_cscd03_cotizacion']['precio_unitario'] = $var[$i]['precio'];
		 $ve_aux1['v_cscd03_cotizacion']['cantidad']        = $var[$i]['cantidad'];
	}
	$ccc = $ve_aux1['v_cscd03_cotizacion']['cantidad'] * $ve_aux1['v_cscd03_cotizacion']['precio_unitario'];

    foreach($lista_sin_iva as $ve){
    	if($codigo_prod_serv==$ve['v_cscd02_solicitud_cuerpo_catalgo']['codigo_prod_serv']){
			        $exento_iva   = $ve['v_cscd02_solicitud_cuerpo_catalgo']['exento_iva'];
			        $alicuota_iva = $ve['v_cscd02_solicitud_cuerpo_catalgo']['alicuota_iva'];
			        $alicuota_iva = $alicuota_iva /100;
			        if($exento_iva=="2"){
                      $iva += ($ccc * $alicuota_iva);
			        }
    	}
    }
}
if($var[$i]['codigo_prod_serv']!="3228"){
	foreach($lista_iva as $ve){
	    $codigo_prod_serv  =  $ve['v_cscd02_solicitud_cuerpo_catalgo']['codigo_prod_serv'];
	    $sql  = "UPDATE cscd03_cotizacion_cuerpo SET  precio_unitario='".$iva."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano_cotizacion=".$var[$i]['ano_cotizacion']."  and  numero_cotizacion='$numero_cotizacion' and  codigo_prod_serv=".$codigo_prod_serv." ";
	    $this->cscd03_cotizacion_cuerpo->execute($sql);
	}
}





 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
 //$array = $this->v_cscd03_cotizacion->execute('SELECT DISTINCT * FROM  v_cscd03_cotizacion where '.$condicion);

 //$year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = $this->ano_ejecucion();
 //foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}


   $array = $this->v_cscd03_cotizacion->findAll($condicion. 'and ano_solicitud = '.$ano , 'DISTINCT numero_solicitud, rif, numero_cotizacion');

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){ $numero[$i]['numero_solicitud'] = $aux['v_cscd03_cotizacion']['numero_solicitud']; $i++; } $i--;

if(isset($numero[$pag_num]['numero_solicitud'])){

 $lista = $this->v_cscd03_cotizacion->findAll($condicion. ' and ano_solicitud = '.$ano.' and numero_solicitud = '.$numero[$pag_num]['numero_solicitud'], null, 'codigo_prod_serv DESC', null);
 $this->set('lista_cscd03_cotizacion_encabezado', $array);
 $this->set('lista_cscd03_cotizacion_cuerpo', $lista);
 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);
  $this->set('i', $i);

}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

     }//fin else


}//fin function





function vacio(){

	  $this->layout = "ajax";


}//fin



function cancelar_editar($pag_num=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $opcion=null){

  $this->layout = "ajax";



 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
 //$array = $this->v_cscd03_cotizacion->execute('SELECT DISTINCT * FROM  v_cscd03_cotizacion where '.$condicion);

 //$year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
$ano = $this->ano_ejecucion();
 //foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}


   $array = $this->v_cscd03_cotizacion->findAll($condicion. 'and ano_solicitud = '.$ano , 'DISTINCT numero_solicitud');

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){ $numero[$i]['numero_solicitud'] = $aux['v_cscd03_cotizacion']['numero_solicitud']; $i++; } $i--;

if(isset($numero[$pag_num]['numero_solicitud'])){

 $lista = $this->v_cscd03_cotizacion->findAll($condicion. ' and ano_solicitud = '.$ano.' and numero_solicitud = '.$numero[$pag_num]['numero_solicitud'], null, 'numero_cotizacion ASC', null);
 $this->set('lista_cscd03_cotizacion_encabezado', $array);
 $this->set('lista_cscd03_cotizacion_cuerpo', $lista);
 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);
  $this->set('i', $i);

}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

     }//fin else


}//fin function



}//fin class

?>