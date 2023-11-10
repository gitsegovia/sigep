<?php


class ReporteJuan2Controller extends AppController{

    var $name = "reporte_juan2";
    var $uses = array("cscd01_unidad_medida", "cscd01_catalogo", "v_catalgo_reporte_tipo_grupo", "v_cscd01_catalogo_con_snc_denominacion",
                      "ccfd04_cierre_mes", "cfpd05", "v_credito_presupuestario_dependencia", "cstd03_cheque_cuerpo", "cepd03_ordenpago_tipopago", "v_casd01_datos_existe_cuerpo", "cstd01_entidades_bancarias", "v_relacion_cheques", "cstd01_sucursales_bancarias", "cstd02_cuentas_bancarias", "v_vistas_cheques_union","cepd03_ordenpago_cuerpo");

	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


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



function verifica_SS($i){
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


function SQLCA_report($pre=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA




    function SQLCA_report_a($pre=null){
         $sql_re = "a.cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "a.cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "a.cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "a.cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "a.cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "a.cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
    function SQLCA_report_in($pre=null){
         $sql_re = $this->verifica_SS(1).",";
         $sql_re .= $this->verifica_SS(2).",";
         $sql_re .= $this->verifica_SS(3).",";
         if($pre!=null && $pre==1){
         $sql_re .= $this->verifica_SS(4).",";
         $sql_re .= 0;
         }else{
         	$sql_re .= $this->verifica_SS(4).",";
            $sql_re .= $this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA




function beforeFilter(){
					$this->checkSession();

}//fin function











function carta_atencion_social($var1=null){


	if($var1==1){  $this->layout="ajax";


	}else{  $this->layout="pdf";


		$tipo= $this->data['reporte_juan2']['tipo'];

				if($tipo==1){

					$cond = $this->condicionNDEP()." and cod_estado=11";

				}else{

		            $cond = $this->condicionNDEP()." and aparece_en_casd01_ayudas_cuerpo=1 and cod_estado=11";

				}//fin else



		$datos = $this->v_casd01_datos_existe_cuerpo->findAll($conditions = $cond, null, "cod_estado, cod_municipio, cod_parroquia, cod_centro_poblado ASC");
		$this->set('datos', $datos);





	}//fin else


$this->set("var1", $var1);

}//fin function










function cscd01_catalogo_1($var1=null){

	$this->set("var1", $var1);


		if($var1==1){
					    $this->layout="pdf";
						if(!empty($this->data['reporte_juan2'])){
							$tipo= $this->data['reporte_juan2']['tipo'];
							$orden= $this->data['reporte_juan2']['orden'];
							switch ($tipo) {
								case 1:
									$cond = null;
									break;
								case 2:
									$cond = "cod_tipo='1'";
									break;
								case 3:
									$cond = "cod_tipo='2'";
									break;
								case 4:
									$cond = "cod_tipo='3'";
									break;

								default:
									break;
							}

							switch ($orden) {
								case 1:
									$order = "cod_grupo_3, cod_grupo_5, cod_snc, denominacion  ASC";
									break;
								case 2:
									$order = "denominacion ASC";
									break;
								case 3:
									$order = "cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC";
									break;
								default:
									$order = "cod_snc ASC";
									break;
							}

							$catalogo = $this->v_catalgo_reporte_tipo_grupo->findAll($conditions = $cond, $fields = null, $order, $limit = null, $page = null, $recursive = null);
							$this->set('catalogo', $catalogo);
							$this->set('orden', $orden);
						}

		}else{
		                $this->layout="ajax";
		}//fin else






}//fin function










function ordenado($tipo=null){
	$this->layout="ajax";
	if($tipo!=null){
		$this->set('tipo', $tipo);
	}
}












function distribucion_ejecucion_de_recursos($var1=null){
	$this->layout="ajax";
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');


    if($var1==null){
       $this->layout="ajax";
       $var1 = 1;

       $this->set('year_inicio', $this->ano_ejecucion());
    }else{
       $this->layout="pdf";
       $var1 = 2;
       $ano_recurso    =   $this->data["reporte_juan2"]["ano_recurso"];
       $tipo_recurso   =   $this->data["reporte_juan2"]["tipo_recurso"];


       if($tipo_recurso!=7){ $sql_recurso = " and a.tipo_presupuesto    =   ".$tipo_recurso;}else{$sql_recurso = " ";}


       $rs1  =  $this->cscd01_catalogo->execute(

				"  SELECT
					          a.cod_presi,
							  a.cod_entidad,
							  a.cod_tipo_inst,
							  a.cod_inst,
							  a.ano,
							  SUM((a.asignacion_anual+a.aumento_traslado_anual+a.credito_adicional_anual)-(a.disminucion_traslado_anual+a.rebaja_anual))   as presupuesto_ajustado_institucion,
					 		  SUM(a.compromiso_anual) as monto_ejecutado_institucion,
					 		  SUM(((a.asignacion_anual+a.aumento_traslado_anual+a.credito_adicional_anual)-(a.disminucion_traslado_anual+a.rebaja_anual)) - a.compromiso_anual) as disponibilidad_institucion

					FROM
					            cfpd05 a

					WHERE

						        a.cod_presi            =  ".$cod_presi."             and
							    a.cod_entidad          =  ".$cod_entidad."           and
							    a.cod_tipo_inst        =  ".$cod_tipo_inst."         and
							    a.cod_inst             =  ".$cod_inst."              and
                                a.ano                  =  ".$ano_recurso."  ".$sql_recurso."
					GROUP BY
								a.cod_presi,
								a.cod_entidad,
								a.cod_tipo_inst,
								a.cod_inst,
								a.ano


					ORDER BY
						        a.cod_presi,
						        a.cod_entidad,
						        a.cod_tipo_inst,
						        a.cod_inst,
						        a.ano

					     ASC; " );


       $rs2  =  $this->cscd01_catalogo->execute(

				"  SELECT
					          a.cod_presi,
							  a.cod_entidad,
							  a.cod_tipo_inst,
							  a.cod_inst,
							  a.cod_dep,
							  (SELECT denominacion FROM cugd02_dependencias b WHERE
							     b.cod_tipo_institucion = a.cod_tipo_inst and
							     b.cod_institucion      = a.cod_inst      and
							     b.cod_dependencia      = a.cod_dep) as denominacion_dep,
							  a.ano,
							  SUM((a.asignacion_anual+a.aumento_traslado_anual+a.credito_adicional_anual)-(a.disminucion_traslado_anual+a.rebaja_anual))   as presupuesto_ajustado,
					 		  SUM(a.compromiso_anual) as monto_ejecutado,
					 		  SUM(((a.asignacion_anual+a.aumento_traslado_anual+a.credito_adicional_anual)-(a.disminucion_traslado_anual+a.rebaja_anual)) - a.compromiso_anual) as disponibilidad


					FROM
					            cfpd05 a

					WHERE

						        a.cod_presi            =  ".$cod_presi."             and
							    a.cod_entidad          =  ".$cod_entidad."           and
							    a.cod_tipo_inst        =  ".$cod_tipo_inst."         and
							    a.cod_inst             =  ".$cod_inst."              and
                                a.ano                  =  ".$ano_recurso."  ".$sql_recurso."

					GROUP BY
								a.cod_presi,
								a.cod_entidad,
								a.cod_tipo_inst,
								a.cod_inst,
								a.cod_dep,
								denominacion_dep,
								a.ano


					ORDER BY
						        a.cod_presi,
						        a.cod_entidad,
						        a.cod_tipo_inst,
						        a.cod_inst,
						        a.cod_dep,
						        a.ano

					     ASC; " );


             $this->set('datos1', $rs1);
             $this->set('datos2', $rs2);
             $this->set('ano_recurso', $ano_recurso);


                 if($tipo_recurso==1){  $opcion1_aux = "Ordinario";
		   }else if($tipo_recurso==2){  $opcion1_aux = "Coordinado";
		   }else if($tipo_recurso==3){  $opcion1_aux = "F.C.I.";
		   }else if($tipo_recurso==4){  $opcion1_aux = "MPPS";
		   }else if($tipo_recurso==5){  $opcion1_aux = "Ingreso Extraordinario";
		   }else if($tipo_recurso==6){  $opcion1_aux = "Ingresos Propios";
		   }else{  $opcion1_aux = ""; }//fin else

		   $this->set('tipo_recurso', $opcion1_aux);


    }//fin function


$this->set('opcion', $var1);

}//fin function









function ventana_cobradores_1($var1=null){

 $this->layout="ajax";


$url                  =  "/reporte_juan2/ventana_cobradores_2/1";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

    if($var1==2){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

	}//fin else


}//fin function








function ventana_cobradores_2($var1=null, $pagina=null, $pista=null){

$this->layout="ajax";
$cod_presi                =       $this->Session->read('SScodpresi');
$cod_entidad              =       $this->Session->read('SScodentidad');
$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
$cod_inst                 =       $this->Session->read('SScodinst');
$cod_dep                  =       $this->Session->read('SScoddep');




       if($var1==1){

        $this->set("datos",'');


 }else if($var1==2){


    	            if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }

					if($pista!=null){
						  $this->Session->write('pista_buscar_beneficiario', $pista);
					}else{
					      $pista = $this->Session->read('pista_buscar_beneficiario');
					}//fin else


					    $condicion = $this->condicion()." and ano_movimiento='".$_SESSION["ano_busqueda"]."' and (".$this->busca_separado(array("rif_cedula","beneficiario"), $pista).") and condicion_actividad=1 ";

			            $Tfilas=count($this->cstd03_cheque_cuerpo->findAll($condicion, "DISTINCT rif_cedula, beneficiario"));
				        if($Tfilas!=0){
				        	$Tfilas=(int)ceil($Tfilas/50);
				        	$this->set('total_paginas',$Tfilas);
							$this->set('pagina_actual',$pagina);
							$this->set('pag_cant',$pagina.'/'.$Tfilas);
							$this->set('ultimo',$Tfilas);
				     	    $datos_filas=$this->cstd03_cheque_cuerpo->findAll($condicion,"DISTINCT rif_cedula, beneficiario", "rif_cedula, beneficiario ASC",50,$pagina,null);
					        $this->set("datos",$datos_filas);
					        $this->set('siguiente',$pagina+1);
							$this->set('anterior',$pagina-1);
							$this->bt_nav($Tfilas,$pagina);
				        }else{
				        	$this->set("datos",'');
				        }

					$this->set("pista",$pista);

 }else  if($var1==3){

         $this->set("valor_seleccionado",$pagina);


 }//fin else

$this->set("opcion",$var1);

}//fin function



function cambiar_year($year=null){
	$this->layout="ajax";
	$_SESSION["ano_busqueda"] = $year!=null?$year:$this->ano_ejecucion();
	$this->render("ventana_cobradores_1");
}





function relacion_de_cheque($ir=null, $formato=null){
	$this->layout="ajax";

	if($ir!=null){
			if($ir=='si'){//IR AL FOMULARIO
				$this->layout="ajax";
				$this->set('ir',$ir);
				$this->set('ano',$this->ano_ejecucion());
				$_SESSION["ano_busqueda"] = $this->ano_ejecucion();

				if($formato != null){
					$this->render('relacion_de_cheque_horizontal');
				}

			}elseif($ir=='no'){//IR AL REPORTE
				$this->layout="pdf";

				//pr($this->data);
				$ano_mov     = $this->data['cheques_caja']['ano'];
				$status      = $this->data['reporte']['status'];
				$por_fecha 	 = false;

				$opcion_reporte		= $this->data['reporte3']['opcion_reporte'];

				if($status == 1 || $status == 5) {
					$sql_status = "";
				} else {
					$sql_status = "and (status_cheque='".$status."')";
				}

				if(!empty($this->data['reporte']['fecha_desde']) && !empty($this->data['reporte']['fecha_hasta'])){
					$fecha_desde = $this->data['reporte']['fecha_desde'];
				    $fecha_hasta = $this->data['reporte']['fecha_hasta'];
				    if($status == 4){
						$fecha_sql   = "and (fecha_cancelado BETWEEN '".$fecha_desde."' AND '$fecha_hasta')";
				    }else{
				    	$fecha_sql   = "and (fecha_cheque BETWEEN '".$fecha_desde."' AND '$fecha_hasta')";
				    }
				    $por_fecha = true;
				}else{
                    $fecha_sql   = "";
                    $fecha_desde = "";
                    $fecha_hasta = "";
				}


				if($this->data['reporte']['tipo_busqueda']==2){
					$bene = split('@', $this->data['reporte']['rif_ce']);// Ahora este campo viene unido el RIF y el BENEFICIARIO divididos por el simbolo @ por eso se utiliza split()
					$rif  = $bene[0];
					$deno = $bene[1];
                    $sql_beneficiario = " and (rif_cedula= '".$rif."' OR UPPER(beneficiario) like UPPER('%$deno%'))";
                    //$sql_beneficiario = " and rif_cedula= '".$rif."'";
                    //$sql_beneficiario = " and rif_cedula= '".$this->data['reporte']['rif_ce']."' ";
				}else{
                    $sql_beneficiario = "";
				}


				if($opcion_reporte == 2){// Indica que es una cuenta bancaria especifica.
					if(!empty($this->data['reporte3']['codigo_entidad_bancaria']) && !empty($this->data['reporte3']['cod_sucursal_bancaria']) && !empty($this->data['reporte3']['cuenta_bancaria'])){
						$sql_cuenta_banc = " and cod_entidad_bancaria='".$this->data['reporte3']['codigo_entidad_bancaria']."' and cod_sucursal='".$this->data['reporte3']['cod_sucursal_bancaria']."' and cuenta_bancaria='".$this->data['reporte3']['cuenta_bancaria']."'";
					}else{
						$sql_cuenta_banc = "";
					}

				}elseif($opcion_reporte == 3){// Indica que es una cuenta y un cheque especifico.
					/*
					if(!empty($this->data['reporte_juan2']['cod_entidad_bancaria']) && !empty($this->data['reporte_juan2']['cod_sucursal']) && !empty($this->data['reporte_juan2']['cuenta_bancaria']) && !empty($this->data['reporte_juan2']['numero_documento'])){
						$sql_cuenta_banc = " and cod_entidad_bancaria='".$this->data['reporte_juan2']['cod_entidad_bancaria']."' and cod_sucursal='".$this->data['reporte_juan2']['cod_sucursal']."' and cuenta_bancaria='".$this->data['reporte_juan2']['cuenta_bancaria']."'"." and numero_cheque='".$this->data['reporte_juan2']['numero_documento']."'";
					}else{
						$sql_cuenta_banc = "";
					}*/
					if(!empty($this->data['reporte_juan2']['cod_entidad_bancaria']) && !empty($this->data['reporte_juan2']['cod_sucursal']) && !empty($this->data['reporte_juan2']['cuenta_bancaria']) && !empty($this->data['reporte_juan2']['numero_documento'])){
						//Recorremos el array con los numeros de cheques que estan en session.
						$x = 0;
						$in= '';
						foreach($_SESSION['numeros_cheque'] as $cheque){$x++;
							if($x == 1){
								$in .= $cheque['numero_documento'];
							}else{
								$in .= ', '.$cheque['numero_documento'];
							}
						}
						//$sql_cuenta_banc = " and cod_entidad_bancaria='".$this->data['reporte_juan2']['cod_entidad_bancaria']."' and cod_sucursal='".$this->data['reporte_juan2']['cod_sucursal']."' and cuenta_bancaria='".$this->data['reporte_juan2']['cuenta_bancaria']."'"." and numero_cheque='".$this->data['reporte_juan2']['numero_documento']."'";
						$sql_cuenta_banc = " and cod_entidad_bancaria='".$this->data['reporte_juan2']['cod_entidad_bancaria']."' and cod_sucursal='".$this->data['reporte_juan2']['cod_sucursal']."' and cuenta_bancaria='".$this->data['reporte_juan2']['cuenta_bancaria']."'"." and numero_cheque IN ($in)";
					}else{
						$sql_cuenta_banc = "";
					}

				}elseif($opcion_reporte == 4){// Indica que es una cuenta y un rango de cheque para imprimir.
					if(!empty($this->data['reporte3']['codigo_entidad_bancaria']) && !empty($this->data['reporte3']['cod_sucursal_bancaria']) && !empty($this->data['reporte3']['cuenta_bancaria']) && !empty($this->data['reporte3']['cheque_desde']) && !empty($this->data['reporte3']['cheque_hasta'])){
						$sql_cuenta_banc = " and cod_entidad_bancaria='".$this->data['reporte3']['codigo_entidad_bancaria']."' and cod_sucursal='".$this->data['reporte3']['cod_sucursal_bancaria']."' and cuenta_bancaria='".$this->data['reporte3']['cuenta_bancaria']."'"." and (numero_cheque BETWEEN '".$this->data['reporte3']['cheque_desde']."' AND '".$this->data['reporte3']['cheque_hasta']."')";
					}else{
						$sql_cuenta_banc = "";
					}

				}else{
					$sql_cuenta_banc = "";
				}

				if ($status == 5) {
					$cond = $this->SQLCA()." and condicion_actividad=2 ";
				} else if($status == 1){
					$cond = $this->SQLCA()." and (condicion_actividad=1 or condicion_actividad=2) ";
				} else {
					$cond = $this->SQLCA()." and condicion_actividad=1 ";
				}

				if($por_fecha){
					$cond .= $sql_status." ".$fecha_sql." ".$sql_beneficiario." ".$sql_cuenta_banc;
				}else{
					$cond .= " and ano_movimiento='$ano_mov' ".$sql_status." ".$fecha_sql." ".$sql_beneficiario." ".$sql_cuenta_banc;
				}


				//$datos_cheque = $this->cstd03_cheque_cuerpo->findAll($cond,array('ano_movimiento','cuenta_bancaria','numero_cheque','fecha_cheque','beneficiario','monto','cod_tipo_pago', 'rif_cedula'),'cuenta_bancaria, fecha_cheque, numero_cheque ASC');
				
				$datos_cheque = $this->v_relacion_cheques->findAll($cond,array('ano_movimiento','cod_entidad_bancaria','cod_sucursal','cuenta_bancaria','numero_cheque','fecha_cheque','fecha_cancelado','beneficiario','monto','cod_tipo_pago', 'rif_cedula', 'status_cheque', 'fecha_transito', 'condicion_actividad'),'cuenta_bancaria, fecha_cheque, numero_cheque ASC');
				$tipo_pago = $this->cepd03_ordenpago_tipopago->findAll();
				$this->set('ir',$ir);
				$this->set('datos_cheque',$datos_cheque);
				$this->set('tipo_pago',$tipo_pago);
				$this->set('fecha_desde',$fecha_desde);
				$this->set('fecha_hasta',$fecha_hasta);

                      if($status==1){
				      	$this->set('status',"TODOS");
				}else if($status==2){
				      	$this->set('status',"CUSTODIA");
				}else if($status==3){
					    $this->set('status',"TRANSITO");
			    }else if($status==4){
			    	    $this->set('status',"CANCELADO");
				}else if($status==5){
			    	    $this->set('status',"ANULADOS");
				}//fin else


				//Para concatenar las ordenes de pago a cada numero de cheque.
				foreach($datos_cheque as $x){
					//$x['v_relacion_cheques']['numero_cheque'];
					$sql_ordenes_pago = $this->SQLCA()." AND ano_movimiento=".$x['v_relacion_cheques']['ano_movimiento']." AND cod_entidad_bancaria=".$x['v_relacion_cheques']['cod_entidad_bancaria']." AND cod_sucursal=".$x['v_relacion_cheques']['cod_sucursal']." AND cuenta_bancaria='".$x['v_relacion_cheques']['cuenta_bancaria']."' AND numero_cheque=".$x['v_relacion_cheques']['numero_cheque']." ";
					$datos_ordenes 	  = $this->cepd03_ordenpago_cuerpo->findAll($sql_ordenes_pago);
					$coletilla = '';
					$k = 0;
					foreach($datos_ordenes as $ordenes){
						$k++;
						if($k == 1){
							$coletilla .= ' '.$ordenes['cepd03_ordenpago_cuerpo']['numero_orden_pago'].' ';
						}else{
							$coletilla .= ' '.$ordenes['cepd03_ordenpago_cuerpo']['numero_orden_pago'].' -';
						}
					}
					$cheque[$x['v_relacion_cheques']['cuenta_bancaria']][$x['v_relacion_cheques']['numero_cheque']] = $coletilla;
				}
				$this->set('cheque', $cheque);

				if($formato != null){
					$this->render('relacion_de_cheque_horizontal');
				}

			}
	}//null
}//cheques_en_caja






















function ejecucion_presupuestaria_mensual($var=null) {
     if(isset($var) && $var=="FORM"){
     	$this->layout="ajax";
	    $Ano=$this->ano_ejecucion();
	    $this->set('ANO',$Ano);
	    $this->set('ELFORM',true);
     }else if(isset($var) && $var=="GENERAR"){
			     	$this->layout="pdf";
			     	$this->set('ELFORM',false);
			        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
				    if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
			              $Ano=$this->data["reporte"]["ano"];
				     }else{
				     	$Ano=$this->ano_ejecucion();
				     }
			    	$this->set('ANO',$Ano);

					    	if(isset($this->data['cfpp05']['consolidacion'])){
					    	    $con=$this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
					    	}else{
					    		$con=$this->SQLCA_consolidado();
					    	}

			        $titulo_a = $this->Session->read('dependencia');
			  	    $this->set('titulo_a',$titulo_a);

			  	    $result=$this->v_credito_presupuestario_dependencia->execute("SELECT * FROM v_credito_presupuestario_dependencia WHERE ano=".$Ano." and ".$con);
			        $this->set("DATA",$result);
     }//fin generar


}//fin reporte credito

function ejecucion_presupuestaria_personal($var=null) {
     if(isset($var) && $var=="FORM"){
      $this->layout="ajax";
      $Ano=$this->ano_ejecucion();
      $this->set('ANO',$Ano);
      $this->set('ELFORM',true);
     }else if(isset($var) && $var=="GENERAR"){
        $this->layout="pdf";
        $this->set('ELFORM',false);
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));

        if(isset($this->data['cfpp05']['consolidacion'])){
                    $con=$this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
                }else{
                  $con=$this->SQLCA_consolidado();
                }

        if($this->data["reporte"]["modo"]=="1"){
           $condicion=$con." and a.ano=".$this->data["reporte"]["ano"];
           $title="POR AÑO (".$this->data["reporte"]["ano"].")";
        }else{
          $condicion=$con." and a.fecha BETWEEN '".$this->data["reporte"]["fecha_desde"]."' AND '".$this->data["reporte"]["fecha_hasta"]."'";
          $title="POR FECHA DESDE: ".$this->data["reporte"]["fecha_desde"]." HASTA ".$this->data["reporte"]["fecha_hasta"];
        }

        $result=$this->v_credito_presupuestario_dependencia->execute("SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, upper((SELECT a1.denominacion
           FROM arrd05  a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep)) AS deno_dependencia, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, SUM(monto) as monto
   FROM cfpd22 a WHERE a.cod_partida=401 and ".$condicion."
   GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, deno_dependencia, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida ORDER BY a.cod_dep ASC, a.cod_sector ASC, a.cod_programa ASC, a.cod_sub_prog ASC, a.cod_activ_obra ASC");
        $this->set("DATA",$result);
        $this->set("SUBTITLE", $title);
     }//fin generar


}//fin reporte credito



function radio_cuenta_bancaria($var=null){
	$this->layout="ajax";

	if(isset($var) && $var==1){

	}elseif(isset($var) && ($var==2 || $var==4)){
		$entidades_banc=$this->cstd01_entidades_bancarias->findAll(null, null, 'cod_entidad_bancaria ASC');
	    $entidades=array();
	    $codEntidad  = array();
	    $denoEntidad = array();
	    $total_entidades = count($entidades_banc);
		if($total_entidades!=0){
			for($i=0; $i<$total_entidades; $i++){
				$codEntidad[]  = mascara($entidades_banc[$i]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4);
				$denoEntidad[] = mascara($entidades_banc[$i]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4)." - ".$entidades_banc[$i]['cstd01_entidades_bancarias']['denominacion'];
			}
			$entidades = array_combine($codEntidad, $denoEntidad);
		}
		$this->set('entidades',$entidades);
		$this->set('vector_cuenta','');

		$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	    $this->concatena($meses, 'mes');
	}elseif(isset($var) && $var==3){
		$this->layout="ajax";
		$this->set('tipo', $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));
		$ano = $this->ano_ejecucion();
		$this->set('ano',$ano);
		$this->Session->write('cod4', $ano);
	}

	$this->set('var',$var);
}


function select($select=null,$var=null, $var2=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	if($select!=null && $var!=null){
		switch($select){
			case 'sucursal':
				$this->set('SELECT','sucursal');
				$this->set('codigo','cod_sucursal');
				$this->set('seleccion','');
				$this->set('n',2);
				if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
				$this->Session->write('cod1',$var2);
				$cond =" cod_entidad_bancaria=".$var2;
				$lista = "";
				if($var2!=""){
				  $lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
				}
				if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector',$lista);}
			break;
			case 'cuenta':
				$this->set('SELECT','cuenta');
				$this->set('codigo','cuenta_bancaria');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->set('no','no');
				$this->set('otro','otro');
				if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
				if($var!=null && $var=='consulta'){$this->set('cuenta','cuenta');}
				$this->Session->write('cod2',$var2);
				$cod_1 =  $this->Session->read('cod1');
				$cond  =  $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$var2;
				$lista = "";
				if($var2!=""){
				    $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
				}
	            if($lista==""){$lista = array(); $this->set('vector',$lista);}else{$this->set('vector',$lista);}
			break;
		}//fin switch

	}else{
			echo "";
	}
}//fin select codigos bancarios


function mostrar($opcion,$var,$codigo=null) {
	$this->layout="ajax";
	if(isset($codigo) && $codigo!=''){
	switch($opcion){
		case 'entidades':
			if(isset($var) && $var=="codigo"){
				$this->set("codigo",$codigo);
			}else if(isset($var) && $var=="deno"){
				$c=$this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($codigo);
				$this->set("deno",$c["cstd01_entidades_bancarias"]["denominacion"]);
			}
			$this->Session->write('entidad_banc', $codigo);
	break;
		case 'sucursales':
			if(isset($var) && $var=="codigo"){
				$this->set("codigo",$codigo);
			}else if(isset($var) && $var=="deno"){
				$entidad_banc = $this->Session->read('entidad_banc');
				$c=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria = '$entidad_banc' AND cod_sucursal='$codigo'");
				$this->set("deno",$c[0]["cstd01_sucursales_bancarias"]["denominacion"]);
			}
		break;
	}
	}else{

		echo "";
	}

}//fin mostrar


function generate_select_numero($var=null){
	$this->layout="ajax";
	$i = 0;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$year2 = $this->Session->read('cod4');
	if(isset($var) && $var!=""){
		$radio_1 =  $this->Session->read('radio');
		$cod_1   =  $this->Session->read('cod1');
		$cod_2   =  $this->Session->read('cod2');
		$this->Session->write('cod3',$var);
		$ano     =  $this->Session->read('cod4');
		$cond    =  $this->SQLCA();
		$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";
		$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";
		$lista=  $this->v_vistas_cheques_union->generateList($cond2.' and ano_movimiento='.$year2, 'numero_documento ASC', null, '{n}.v_vistas_cheques_union.numero_documento', '{n}.v_vistas_cheques_union.numero_documento');
	}else{$lista="";}//fin else
		$this->set('lista', $lista);

	$this->Session->delete('i_cheque');
	$this->Session->delete('numeros_cheque');
}//fin function



function mostar_cheque($pag_num=null){
	$this->layout = "ajax";
	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
	$ano = $this->Session->read('cod4');
	$cond2 = "";

	if(isset($pag_num)){
		$radio_1 =  $this->Session->read('radio');
		$cod_1   =  $this->Session->read('cod1');
		$cod_2   =  $this->Session->read('cod2');
		$cod_3   =  $this->Session->read('cod3');
		$ano     =  $this->Session->read('cod4');
		$cond    =  $this->SQLCA();
		$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria=".$cod_3." and situacion=1 ";
		$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_documento=".$pag_num." and ano_movimiento='".$ano."' ";
	}//fin

	if($cond2!=""){
		$array = $this->v_vistas_cheques_union->findAll($cond2);
	    foreach($array as $aux){
		 	$numero['ano_movimiento']              =   $aux['v_vistas_cheques_union']['ano_movimiento'];
		 	$numero['numero_cheque']               =   $aux['v_vistas_cheques_union']['numero_documento'];
		 	$numero['cod_entidad_bancaria']        =   $aux['v_vistas_cheques_union']['cod_entidad_bancaria'];
		 	$numero['cod_sucursal']                =   $aux['v_vistas_cheques_union']['cod_sucursal'];
		 	$numero['cuenta_bancaria']             =   $aux['v_vistas_cheques_union']['cuenta_bancaria'];
		 	$numero['fecha_cheque']                =   $aux['v_vistas_cheques_union']['fecha_documento'];
		 	$numero['beneficiario']                =   $aux['v_vistas_cheques_union']['beneficiario'];
		 	$numero['monto']                       =   $aux['v_vistas_cheques_union']['monto'];
		 	$numero['tipo_cheque']                 =   $aux['v_vistas_cheques_union']['tipo_cheque'];
		}

		$fecha_nueva = $numero['fecha_cheque'][8].$numero['fecha_cheque'][9].'/'.$numero['fecha_cheque'][5].$numero['fecha_cheque'][6].'/'.$numero['fecha_cheque'][0].$numero['fecha_cheque'][1].$numero['fecha_cheque'][2].$numero['fecha_cheque'][3];

			echo'<script>';
				echo"document.getElementById('beneficiar_cheque').value = '".$numero['beneficiario']."'; ";
				echo"document.getElementById('fecha_cheque').value      = '".$fecha_nueva."'; ";
				echo"document.getElementById('monto_cheque').value      = '".$this->Formato2($numero['monto'])."'; ";
				//echo"document.getElementById('tipo_cheque').value      = '".$this->Formato2($numero['tipo_cheque'])."'; ";
				echo"document.getElementById('agregar_cheque').disabled = false;";
			echo'</script>';

		}else{

            echo'<script>';
				echo"document.getElementById('beneficiar_cheque').value = ''; ";
				echo"document.getElementById('fecha_cheque').value      = ''; ";
				echo"document.getElementById('monto_cheque').value      = ''; ";
				echo"document.getElementById('persona_receptor').readOnly = true; ";
		        echo"document.getElementById('cedula_identidad').readOnly = true; ";
		        //echo"document.getElementById('tipo_cheque').value      = ''; ";
		        echo"document.getElementById('agregar_cheque').disabled = false;";
			echo'</script>';

		}//fin else

}//fin function

function agregar_cheque_grilla(){
	$this->layout='ajax';
	if(isset($_SESSION['i_cheque'])){
		$i = $this->Session->read('i_cheque');
		$indice = $i + 1;
		$this->Session->write('i_cheque', $indice);
	}else{
		$indice = 0;
		$this->Session->write('i_cheque', $indice);
	}
	$vec[$indice]['numero_documento']  = $this->data['reporte_juan2']['numero_documento'];
	$vec[$indice]['beneficiar_cheque'] = $this->data['reporte_juan2']['beneficiar_cheque'];
	$vec[$indice]['fecha_cheque']      = $this->data['reporte_juan2']['fecha_cheque'];
	$vec[$indice]['monto_cheque']      = $this->data['reporte_juan2']['monto_cheque'];
	if(isset($_SESSION['numeros_cheque'])){
		$flag = false;
		$numero_documento = $this->data['reporte_juan2']['numero_documento'];
		foreach($_SESSION['numeros_cheque'] as $cheque){
			if($cheque['numero_documento'] == $numero_documento){
				$flag = true;
			}
		}
		if($flag == false){
			$_SESSION['numeros_cheque'] = $_SESSION['numeros_cheque'] + $vec;
		}else{
			$this->set('mensajeError', 'Ya el número de cheque '.mascara($numero_documento, 8).' se encuentra agregado');
		}
	}else{
		$_SESSION['numeros_cheque'] = $vec;
	}
}


function vacio(){
	$this->layout='ajax';
	echo'<script>';
		echo"document.getElementById('beneficiar_cheque').value = ''; ";
		echo"document.getElementById('fecha_cheque').value      = ''; ";
		echo"document.getElementById('monto_cheque').value      = ''; ";
	echo'</script>';
}



}//fin class
?>