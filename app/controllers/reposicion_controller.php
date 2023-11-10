<?php

class ReposicionController extends AppController{


    var $name    = "reposicion";
    var $uses    = array('v_cstd06_comprobante_egreso', 'v_cstd06_comprobante_egreso_ordenes', 'cepd03_tipo_documento', 'ccfd04_cierre_mes', 'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_facturas', 'cugd02_dependencia', 'cugd02_institucion', 'cstd03_cheque_cuerpo',
                         'cstd02_cuentas_bancarias', 'cstd01_sucursales_bancarias', 'cstd01_entidades_bancarias', 'cstd06_comprobante_cuerpo_egreso',
                         'cstd09_notadebito_cuerpo_pago','cstd30_debito_cuerpo','cstd03_movimientos_manuales','cstd03_beneficiario_retencion_obra');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');



function SQLCA($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "  and  ";
        if ($ano != null) {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . "  and  ";
            $sql_re .= "ano=" . $ano . "  ";
        } else {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . " ";
        }
        return $sql_re;
}


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession





function beforeFilter(){$this->checkSession();}






function distribuir($var1=null){
	$this->layout = "ajax";
}//fin function

function select_reposcion($var1=null, $var2=null){
$this->layout = "ajax";

    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $lista = "";



		  if($var1==1){

			            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_iva!=0 and monto_retencion_iva!=0'." and ano_movimiento=".$var2,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_iva";


		}else if($var1==2){


                        $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_islr!=0 and monto_islr!=0'." and ano_movimiento=".$var2, 'DISTINCT autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_islr";




		}else if($var1==3){

                        $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_municipal!=0 and monto_impuesto_municipal!=0'." and ano_movimiento=".$var2,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_municipal";


        }else if($var1==4){



        	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_timbre!=0 and monto_timbre_fiscal!=0'." and ano_movimiento=".$var2,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_timbre";


	     }else if($var1==5){


        	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_egreso!=0 and condicion_actividad=1'." and ano_movimiento=".$var2,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						//var_dump($array);exit();  
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_egreso_libre";


		 }else if($var1==6){



        	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_egreso!=0 and condicion_actividad=1'." and ano_movimiento=".$var2,  'DISTINCT  autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_egreso_preimpreso";


	    }else if($var1==7){



        	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_multa!=0 and retencion_multa!=0'." and ano_movimiento=".$var2,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_multa";

	    }else if($var1==8){



        	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_responsabilidad!=0 and retencion_responsabilidad!=0'." and ano_movimiento=".$var2,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_responsabilidad";



		}else if($var1==9){



        	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and cod_tipo_documento = 6 and numero_orden_pago_secuencia = 'ret-lab' and ano_movimiento=".$var2,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_lab";



		}else if($var1==10){



        	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and cod_tipo_documento = 6 and numero_orden_pago_secuencia = 'ret-fc' and ano_movimiento=".$var2,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
						foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
						$this->set('lista', $lista);
						$this->set('opcion', null);
						$direccion = "comprobante_fc";



		}//fin funtion


$this->set('opcion_dirrecion', $var1);
$this->set('year', $var2);

}//fin funtion


function radio_clasificacion($ano=null, $opc_clase=null){
	$this->layout = "ajax";
	$this->Session->write('clase_beneficiario_egreso', $opc_clase);
    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$cod_clasificacion = $this->Session->read('clase_beneficiario_egreso'); // CODIGO CLASE BENEFICIARIO
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
		$array = $this->v_cstd06_comprobante_egreso->findAll($condicion." and ano_movimiento=".$ano." and clase_beneficiario='$cod_clasificacion'",  'DISTINCT  autorizado, rif', 'ORDER BY autorizado', null);

                $co = array();
                $li = array();
			foreach($array as $b_aux){

				$rif=$b_aux['v_cstd06_comprobante_egreso']['rif'];
			  	$co[] = $rif;
			  	$li[] = $b_aux['v_cstd06_comprobante_egreso']['autorizado'].' - '.$rif;

			}

				if(is_array($co) && !empty($co)){
					$lista = array_combine($co, $li);
				}else{
					$lista = array();
				}

				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 5);
				$this->set('year', $ano);
}// FIN FUNCION


function comprobante_egreso_libre($opcion=null, $var1=null, $var2=null){

    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$cod_clasificacion = $this->Session->read('clase_beneficiario_egreso'); // CODIGO CLASE BENEFICIARIO

	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);
	$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
	$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
	$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));
	$lista = "";

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

	if($opcion=='a'){
		        $this->layout = "ajax";

				$array = $this->v_cstd06_comprobante_egreso->findAll($condicion." and ano_movimiento=".$ano." and clase_beneficiario='$cod_clasificacion'",  'DISTINCT  autorizado, rif', 'ORDER BY autorizado', null);

                $co = array();
                $li = array();
			foreach($array as $b_aux){
				$rif=$b_aux['v_cstd06_comprobante_egreso']['rif'];
			  	$co[] = $rif;
			  	$li[] = $b_aux['v_cstd06_comprobante_egreso']['autorizado'].' - '.$rif;
			}

				if(is_array($co) && !empty($co)){
					$lista = array_combine($co, $li);
				}else{
					$lista = array();
				}

				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 5);
				$this->set('year', $ano);


	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');

	}else if($opcion!='si'){		        $this->layout = "ajax";

			    $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
				$campos = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, numero_orden_pago, ano_movimiento, cuenta_bancaria, numero_cheque, fecha_cheque, numero_comprobante_egreso, sum(monto_neto_cobrar) as monto_neto_cobrar";
				$agrupar = "GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, numero_orden_pago, ano_movimiento, cuenta_bancaria, numero_cheque, fecha_cheque, numero_comprobante_egreso";
				$array = $this->v_cstd06_comprobante_egreso_ordenes->findAll($sql." and clase_beneficiario=".$cod_clasificacion." and numero_comprobante_egreso!=0 and upper(trim(rif))='".strtoupper($opcion)."' and ano_movimiento=".$ano." ".$agrupar, $campos, 'numero_comprobante_egreso ASC');

				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else


	if($var1!=null){

	            $ano_movimiento    = $this->data['reposicion']['ano_movimiento_'.$var1];
	            $numero_comprobante_egreso = $this->data['reposicion']['numero_comprobante_egreso_'.$var1];
				$this->layout = "pdf";
				$this->set('opcion', '2');

				// ORDENES DE PAGO
			    $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
				$campos = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, numero_orden_pago, fecha_orden_pago, cod_tipo_documento, autorizado, ano_movimiento, numero_comprobante_egreso, monto_neto_cobrar";
				$agrupar = "GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, numero_orden_pago, fecha_orden_pago, cod_tipo_documento, autorizado, ano_movimiento, numero_comprobante_egreso, monto_neto_cobrar";
				$datos_cepd03_ordenpago_cuerpo = $this->v_cstd06_comprobante_egreso_ordenes->findAll($sql." and clase_beneficiario=".$cod_clasificacion." and ano_movimiento=".$ano_movimiento." and numero_comprobante_egreso=".$numero_comprobante_egreso." ".$agrupar, $campos, 'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, numero_orden_pago ASC');


                $ano_comprobante_egreso      =  $ano_movimiento;

                $datos_cstd06_comprobante_cuerpo_egreso = $this->cstd06_comprobante_cuerpo_egreso->findAll($condicion.' and ano_comprobante_egreso='.$ano_comprobante_egreso.' and numero_comprobante_egreso='.$numero_comprobante_egreso);
		        $ano_comprobante_egreso      =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['ano_comprobante_egreso'];
			    $numero_comprobante_egreso   =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['numero_comprobante_egreso'];
			    $ano_movimiento              =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['ano_movimiento'];
			    $cod_entidad_bancaria        =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['cod_entidad_bancaria'];
			    $cod_sucursal                =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['cod_sucursal'];
			    $cuenta_bancaria             =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['cuenta_bancaria'];
			    $numero_cheque               =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['numero_cheque'];
                $datos_cstd03_cheque_cuerpo      = $this->cstd03_cheque_cuerpo->findAll($condicion."            and ano_movimiento=".$ano_movimiento."  and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque=".$numero_cheque);
                $datos_cstd09_notadebito_cuerpo  = $this->cstd09_notadebito_cuerpo_pago->findAll($condicion."   and ano_movimiento=".$ano_movimiento."  and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_debito=".$numero_cheque);


                $tipodocu=$this->cepd03_tipo_documento->findAll();

				$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
				$this->set('titulo_a', $this->Session->read('dependencia'));
				$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);
				$this->set('datos_cstd06_comprobante_cuerpo_egreso', $datos_cstd06_comprobante_cuerpo_egreso);
				$this->set('datos_cstd03_cheque_cuerpo',      $datos_cstd03_cheque_cuerpo);
				$this->set('datos_cstd09_notadebito_cuerpo',      $datos_cstd09_notadebito_cuerpo);
				$this->set('tipodocu', $tipodocu);
				$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
				$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
				$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));				
				$this->render("comprobante_egreso_libre");
				
	      }//fin if
}//fin function


function direccion_reposcion($var1=null, $var2=null, $var3=null){

$this->layout = "ajax";

    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$cod_clasificacion = $this->Session->read('clase_beneficiario_egreso');

	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

    $this->set("year", $var2);


		      if($var1==1){


				      	$sql = "";
			            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_iva!=0 and   upper(trim(rif))='".strtoupper($var3)."'  and ano_movimiento=".$var2,  null, 'numero_orden_pago ASC');
		                foreach($array as $aux){
		                	$ano_orden_pago     =   $aux['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
		                    $numero_orden_pago  =   $aux['cepd03_ordenpago_cuerpo']['numero_orden_pago'];
		                            if($sql==""){ $sql  = "     ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  ";
		                      }else if($sql!=""){ $sql .= " or (ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."')"; }//fin else
		                }//fin for
		                $datos_cepd03_ordenpago_facturas = $this->cepd03_ordenpago_facturas->findAll($condicion." and (".$sql.")",  null, 'numero_orden_pago ASC');
		                $this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
						$this->set('lista', $array);
						$this->set('opcion', '1');


						$this->render("comprobante_iva");




		}else if($var1==2){

			            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_islr!=0 and upper(trim(rif))='".strtoupper($var3)."'  "." and ano_movimiento=".$var2, null, 'numero_orden_pago ASC');
						$this->set('lista', $array);
						$this->set('opcion', '1');

						$this->render("comprobante_islr");



		}else if($var1==3){

                        $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_municipal!=0 and upper(trim(rif))='".strtoupper($var3)."'  "." and ano_movimiento=".$var2, null , 'numero_orden_pago ASC');
						$this->set('lista', $array);
						$this->set('opcion', '1');

						$this->render("comprobante_municipal");



        }else if($var1==4){

                         $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_timbre!=0 and upper(trim(rif))='".strtoupper($var3)."'  "." and ano_movimiento=".$var2,  null , 'numero_orden_pago ASC');
						 $this->set('lista', $array);
						 $this->set('opcion', '1');

						 $this->render("comprobante_timbre");


         }else if($var1==5){
                            $resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
							$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
							$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
							$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);
							$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
							$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
							$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));

						    $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
							$campos = " cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cuenta_bancaria, numero_cheque, fecha_cheque, numero_comprobante_egreso, monto_neto_cobrar";
							$agrupar = "GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cuenta_bancaria, numero_cheque, fecha_cheque, numero_comprobante_egreso, monto_neto_cobrar";
				            $array = $this->v_cstd06_comprobante_egreso->findAll($sql." and clase_beneficiario=".$cod_clasificacion." and numero_comprobante_egreso!=0 and upper(trim(rif))='".strtoupper($var3)."' and ano_movimiento=".$var2." ".$agrupar, $campos, 'numero_comprobante_egreso ASC');

							$this->set('lista', $array);
							$this->set('opcion', '1');
							$this->render("comprobante_egreso_libre");


		 }else if($var1==6){

		 	                $resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
							$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
							$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
							$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);
							$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
							$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
							$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));



                            $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
							$campos = 'cod_presi ,
							cod_entidad,
							cod_tipo_inst,
							cod_inst,
							cod_dep,
							ano_movimiento,
							cuenta_bancaria,
							numero_cheque,
							fecha_cheque,
							numero_comprobante_egreso,
							SUM(monto_neto_cobrar)  as "monto_neto_cobrar" ';


							$agrupar = 'GROUP BY cod_presi,
							cod_entidad,
							cod_tipo_inst,
							cod_inst,
							cod_dep,
							ano_movimiento ,
							cuenta_bancaria ,
							numero_cheque ,
							fecha_cheque,
							numero_comprobante_egreso';

				            $array = $this->cepd03_ordenpago_cuerpo->findAll($sql." and numero_comprobante_egreso!=0 and upper(trim(rif))='".strtoupper($var3)."'  and condicion_actividad=1 "." and ano_movimiento=".$var2." ".$agrupar,   $campos, 'numero_cheque,  numero_comprobante_egreso DESC');
							$this->set('lista', $array);
							$this->set('opcion', '1');

							 $this->render("comprobante_egreso_preimpreso");


	   }else if($var1==7){



                         $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_multa!=0 and upper(trim(rif))='".strtoupper($var3)."'  "." and ano_movimiento=".$var2,  null , 'numero_orden_pago ASC');
						 $this->set('lista', $array);
						 $this->set('opcion', '1');

						 $this->render("comprobante_multa");


		}else if($var1==8){



                         $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_responsabilidad!=0 and upper(trim(rif))='".strtoupper($var3)."'  "." and ano_movimiento=".$var2,  null , 'numero_orden_pago ASC');
						 $this->set('lista', $array);
						 $this->set('opcion', '1');

						 $this->render("comprobante_responsabilidad");



		}else if($var1==9){



                          $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and cod_tipo_documento = 6 and numero_orden_pago_secuencia = 'ret-lab' and upper(trim(rif))='".strtoupper($var3)."'  "." and ano_movimiento=".$var2,  null , 'numero_orden_pago ASC');
						 $this->set('lista', $array);
						 $this->set('opcion', '1');

						 $this->render("comprobante_lab");



		}else if($var1==10){



                         $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and cod_tipo_documento = 6 and numero_orden_pago_secuencia = 'ret-fc' and upper(trim(rif))='".strtoupper($var3)."'  "." and ano_movimiento=".$var2,  null , 'numero_orden_pago ASC');
						 $this->set('lista', $array);
						 $this->set('opcion', '1');

						 $this->render("comprobante_fc");



		}//fin funtion


}//fin funtion



/***** ARREGLO DEL COMPROBANTE FIEL CUMPLIMEINTO Y LABORAL *******/

function comprobante_lab($opcion=null, $var1=null, $var2=null){
     $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

    $lista = "";



	if($opcion=='a'){
		        $this->layout = "ajax"; //str_replace(" ", '',$b_aux['cepd03_ordenpago_cuerpo']['autorizado'])
				$array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and cod_tipo_documento = 6 and numero_orden_pago_secuencia = 'ret-lab'  and ano_movimiento=".$ano,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
				foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
				
				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 9);
				$this->set('year', $ano);

	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');
	}/*else if($opcion!='si'){
		        $this->layout = "ajax";
		        $sql = "";
	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and cod_tipo_documento = 6 and numero_orden_pago_secuencia = 'ret-fc' and   upper(trim(rif))='".strtoupper($opcion)."'  and ano_movimiento=".$ano,  null, 'numero_orden_pago ASC');
                foreach($array as $aux){
                	$ano_orden_pago     =   $aux['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
                    $numero_orden_pago  =   $aux['cepd03_ordenpago_cuerpo']['numero_orden_pago'];
                            if($sql==""){ $sql  = "     ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  ";
                      }else if($sql!=""){ $sql .= " or (ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."')"; }//fin else
                }//fin for
                $datos_cepd03_ordenpago_facturas = $this->cepd03_ordenpago_facturas->findAll($condicion." and (".$sql.")",  null, 'numero_orden_pago ASC');
                $this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else */

	if($var1!=null){
			/**
			 * El beneficiario de cheque es la Gobernación ya que deben de hacer un movimiento financiero 
			 * de una cuenta a otra.
			 * Es por eso que se consulta a la tabla de beneficiario retención de Obras y porder obtener el nombre
			 * del beneficiario relacionado al tipo de documento.
			 */
			$beneficiario_gob = $this->cstd03_beneficiario_retencion_obra->findAll($this->SQLCA()." and tipo_doc = 1",'beneficiario');

			 $ano_orden_pago    = $this->data['reposicion']['ano_orden_pago_'.$var1];
            $numero_orden_pago = $this->data['reposicion']['numero_orden_pago_'.$var1];
            //$numero_factura    = $this->data['reposicion']['numero_factura_'.$var1];
			$this->layout = "pdf";
			$this->set('opcion', '2');
			
			$datos_cepd03_ordenpago_cuerpo   = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' '." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  " );

			$datos_cheque_egreso = $this->v_cstd06_comprobante_egreso->findAll($condicion ." and cod_entidad_bancaria = " . $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria'] . " and numero_cheque = " . $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['numero_cheque'], "fecha_cheque, numero_comprobante_egreso");

			$cuerpo_cheque = $this->cstd03_cheque_cuerpo->findAll($condicion." and numero_cheque = ".$datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['numero_cheque'], "concepto");
			
			$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
			$this->set('fecha_cheque', $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
			$this->set('titulo_a', $this->Session->read('dependencia'));
			$this->set('datos_cugd02_dependencias', $this->cugd02_dependencia->findAll());
			$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
			$this->set('tipodocu', $this->cepd03_tipo_documento->findAll());
			$this->set('datos_cheque_egreso', $datos_cheque_egreso);
			$this->set('beneficiario', $beneficiario_gob [0]["cstd03_beneficiario_retencion_obra"]["beneficiario"]);
			$this->set('concepto', $cuerpo_cheque[0]["cstd03_cheque_cuerpo"]["concepto"]);
		
	           
	 }//fin if
}//fin function

function comprobante_fc($opcion=null, $var1=null, $var2=null){
    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

    $lista = "";



	if($opcion=='a'){
		        $this->layout = "ajax"; //str_replace(" ", '',$b_aux['cepd03_ordenpago_cuerpo']['autorizado'])
				$array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and cod_tipo_documento = 6 and numero_orden_pago_secuencia = 'ret-fc'  and ano_movimiento=".$ano,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
				foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
				
				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 10);
				$this->set('year', $ano);

	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');
	}/*else if($opcion!='si'){
		        $this->layout = "ajax";
		        $sql = "";
	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and cod_tipo_documento = 6 and numero_orden_pago_secuencia = 'ret-fc' and   upper(trim(rif))='".strtoupper($opcion)."'  and ano_movimiento=".$ano,  null, 'numero_orden_pago ASC');
                foreach($array as $aux){
                	$ano_orden_pago     =   $aux['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
                    $numero_orden_pago  =   $aux['cepd03_ordenpago_cuerpo']['numero_orden_pago'];
                            if($sql==""){ $sql  = "     ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  ";
                      }else if($sql!=""){ $sql .= " or (ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."')"; }//fin else
                }//fin for
                $datos_cepd03_ordenpago_facturas = $this->cepd03_ordenpago_facturas->findAll($condicion." and (".$sql.")",  null, 'numero_orden_pago ASC');
                $this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else */

	if($var1!=null){
			/**
			 * El beneficiario de cheque es la Gobernación ya que deben de hacer un movimiento financiero 
			 * de una cuenta a otra.
			 * Es por eso que se consulta a la tabla de beneficiario retención de Obras y porder obtener el nombre
			 * del beneficiario relacionado al tipo de documento.
			 */
			$beneficiario_gob = $this->cstd03_beneficiario_retencion_obra->findAll($this->SQLCA()." and tipo_doc = 1",'beneficiario');

			 $ano_orden_pago    = $this->data['reposicion']['ano_orden_pago_'.$var1];
            $numero_orden_pago = $this->data['reposicion']['numero_orden_pago_'.$var1];
            //$numero_factura    = $this->data['reposicion']['numero_factura_'.$var1];
			$this->layout = "pdf";
			$this->set('opcion', '2');
			
			$datos_cepd03_ordenpago_cuerpo   = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' '." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  " );

			$datos_cheque_egreso = $this->v_cstd06_comprobante_egreso->findAll($condicion ." and cod_entidad_bancaria = " . $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria'] . " and numero_cheque = " . $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['numero_cheque'], "fecha_cheque, numero_comprobante_egreso");

			$cuerpo_cheque = $this->cstd03_cheque_cuerpo->findAll($condicion." and numero_cheque = ".$datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['numero_cheque'], "concepto");

			$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
			$this->set('fecha_cheque', $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
			$this->set('titulo_a', $this->Session->read('dependencia'));
			$this->set('datos_cugd02_dependencias', $this->cugd02_dependencia->findAll());
			$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
			$this->set('tipodocu', $this->cepd03_tipo_documento->findAll());
			$this->set('datos_cheque_egreso', $datos_cheque_egreso);
			$this->set('beneficiario', $beneficiario_gob [0]["cstd03_beneficiario_retencion_obra"]["beneficiario"]);
			$this->set('concepto', $cuerpo_cheque[0]["cstd03_cheque_cuerpo"]["concepto"]);
		
	 }//fin if
}//fin function


function comprobante_iva($opcion=null, $var1=null, $var2=null){
    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

    $lista = "";


	if($opcion=='a'){
		        $this->layout = "ajax"; //str_replace(" ", '',$b_aux['cepd03_ordenpago_cuerpo']['autorizado'])
				$array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_iva!=0 and monto_retencion_iva!=0'." and ano_movimiento=".$ano,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
				foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 1);
				$this->set('year', $ano);

	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');
	}else if($opcion!='si'){
		        $this->layout = "ajax";
		        $sql = "";
	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_iva!=0 and   upper(trim(rif))='".strtoupper($opcion)."'  and ano_movimiento=".$ano,  null, 'numero_orden_pago ASC');
                foreach($array as $aux){
                	$ano_orden_pago     =   $aux['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
                    $numero_orden_pago  =   $aux['cepd03_ordenpago_cuerpo']['numero_orden_pago'];
                            if($sql==""){ $sql  = "     ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  ";
                      }else if($sql!=""){ $sql .= " or (ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."')"; }//fin else
                }//fin for
                $datos_cepd03_ordenpago_facturas = $this->cepd03_ordenpago_facturas->findAll($condicion." and (".$sql.")",  null, 'numero_orden_pago ASC');
                $this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else


	if($var1!=null){
	            $ano_orden_pago    = $this->data['reposicion']['ano_orden_pago_'.$var1];
	            $numero_orden_pago = $this->data['reposicion']['numero_orden_pago_'.$var1];
	            $numero_factura    = $this->data['reposicion']['numero_factura_'.$var1];
				$this->layout = "pdf";
				$this->set('opcion', '2');
				$datos_cugd02_dependencias       = $this->cugd02_dependencia->findAll();
				$datos_cepd03_ordenpago_cuerpo   = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' '." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  " );
				$datos_cepd03_ordenpago_facturas = $this->cepd03_ordenpago_facturas->findAll($condicion.' '." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' and numero_factura='".$numero_factura."'  ");
				$this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
				$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
				$this->set('fecha_cheque', $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
				$this->set('titulo_a', $this->Session->read('dependencia'));
				$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);
	 }//fin if
}//fin function




function comprobante_islr($opcion=null, $var1=null, $var2=null){
    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);
	$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
	$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
	$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));
	$lista = "";

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

	if($opcion=='a'){
		        $this->layout = "ajax";
				$array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_islr!=0 and monto_islr!=0'." and ano_movimiento=".$ano, 'DISTINCT autorizado, rif ', 'autorizado ASC', null);
				foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 2);
				$this->set('year', $ano);

	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');
	}else if($opcion!='si'){
		        $this->layout = "ajax";
	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_islr!=0 and upper(trim(rif))='".strtoupper($opcion)."'  "." and ano_movimiento=".$ano,  null , 'numero_orden_pago ASC');
				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else



	if($var1!=null){
	            $ano_orden_pago    = $this->data['reposicion']['ano_orden_pago_'.$var1];
	            $numero_orden_pago = $this->data['reposicion']['numero_orden_pago_'.$var1];
				$this->layout = "pdf";
				$this->set('opcion', '2');
				$datos_cugd02_dependencias=$this->cugd02_dependencia->findAll();
				$datos_cepd03_ordenpago_cuerpo=$this->cepd03_ordenpago_cuerpo->findAll($condicion.' '." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  ");
				$datos_cepd03_ordenpago_facturas=$this->cepd03_ordenpago_facturas->findAll($condicion);
				$this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
				$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
				$this->set('fecha_cheque', $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
				$this->set('titulo_a', $this->Session->read('dependencia'));
				$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);
	}//fin if
}//fin function










function comprobante_municipal($opcion=null, $var1=null, $var2=null){
    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);
	$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
	$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
	$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));
	$lista = "";

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

	if($opcion=='a'){
		        $this->layout = "ajax";
				$array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_municipal!=0 and monto_impuesto_municipal!=0'." and ano_movimiento=".$ano,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
				foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 3);
				$this->set('year', $ano);

	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');
	}else if($opcion!='si'){
		        $this->layout = "ajax";
	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_municipal!=0 and upper(trim(rif))='".strtoupper($opcion)."'  "." and ano_movimiento=".$ano,  null , 'numero_orden_pago ASC');
				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else


	if($var1!=null){
	            $ano_orden_pago    = $this->data['reposicion']['ano_orden_pago_'.$var1];
	            $numero_orden_pago = $this->data['reposicion']['numero_orden_pago_'.$var1];
				$this->layout = "pdf";
				$this->set('opcion', '2');
				$datos_cugd02_dependencias=$this->cugd02_dependencia->findAll();
				$datos_cepd03_ordenpago_cuerpo=$this->cepd03_ordenpago_cuerpo->findAll($condicion.' '." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  ");
				$datos_cepd03_ordenpago_facturas=$this->cepd03_ordenpago_facturas->findAll($condicion);
				$this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
				$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
				$this->set('fecha_cheque', $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
				$this->set('titulo_a', $this->Session->read('dependencia'));
				$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);
	}//fin if
}//fin function











function comprobante_timbre($opcion=null, $var1=null, $var2=null){
    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);
	$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
	$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
	$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));
	$lista = "";

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

	if($opcion=='a'){
		        $this->layout = "ajax";
				$array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_timbre!=0 and monto_timbre_fiscal!=0'." and ano_movimiento=".$ano,  'DISTINCT autorizado, rif ', 'autorizado ASC',null);
				foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 4);
				$this->set('year', $ano);

	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');
	}else if($opcion!='si'){
		        $this->layout = "ajax";
	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_timbre!=0 and upper(trim(rif))='".strtoupper($opcion)."'  "." and ano_movimiento=".$ano,  null , 'numero_orden_pago ASC');
				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else



	if($var1!=null){
	            $ano_orden_pago    = $this->data['reposicion']['ano_orden_pago_'.$var1];
	            $numero_orden_pago = $this->data['reposicion']['numero_orden_pago_'.$var1];
				$this->layout = "pdf";
				$this->set('opcion', '2');
				$datos_cugd02_dependencias=$this->cugd02_dependencia->findAll();
				$datos_cepd03_ordenpago_cuerpo=$this->cepd03_ordenpago_cuerpo->findAll($condicion.' '." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  ");
				$datos_cepd03_ordenpago_facturas=$this->cepd03_ordenpago_facturas->findAll($condicion);
				$this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
				$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
				$this->set('fecha_cheque', $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
				$this->set('titulo_a', $this->Session->read('dependencia'));
				$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);
	}//fin if
}//fin function















function comprobante_multa($opcion=null, $var1=null, $var2=null){
    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);
	$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
	$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
	$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));
	$lista = "";

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

	if($opcion=='a'){
		        $this->layout = "ajax";
				$array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_multa!=0 and retencion_multa!=0'." and ano_movimiento=".$ano,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
				foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 7);
				$this->set('year', $ano);

	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');
	}else if($opcion!='si'){
		        $this->layout = "ajax";
	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_multa!=0 and upper(trim(rif))='".strtoupper($opcion)."'  "." and ano_movimiento=".$ano,  null , 'numero_orden_pago ASC');
				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else



	if($var1!=null){
	            $ano_orden_pago    = $this->data['reposicion']['ano_orden_pago_'.$var1];
	            $numero_orden_pago = $this->data['reposicion']['numero_orden_pago_'.$var1];
				$this->layout = "pdf";
				$this->set('opcion', '2');
				$datos_cugd02_dependencias=$this->cugd02_dependencia->findAll();
				$datos_cepd03_ordenpago_cuerpo=$this->cepd03_ordenpago_cuerpo->findAll($condicion.' '." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  ");
				$datos_cepd03_ordenpago_facturas=$this->cepd03_ordenpago_facturas->findAll($condicion);
				$this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
				$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
				$this->set('fecha_cheque', $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
				$this->set('titulo_a', $this->Session->read('dependencia'));
				$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);
	}//fin if
}//fin function













function comprobante_responsabilidad($opcion=null, $var1=null, $var2=null){
    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);
	$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
	$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
	$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));
	$lista = "";

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

	if($opcion=='a'){
		        $this->layout = "ajax";
				$array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_responsabilidad!=0 and retencion_responsabilidad!=0'." and ano_movimiento=".$ano,  'DISTINCT autorizado, rif ', 'autorizado ASC', null);
				foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 8);
				$this->set('year', $ano);

	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');
	}else if($opcion!='si'){
		        $this->layout = "ajax";
	            $array = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and numero_comprobante_responsabilidad!=0 and upper(trim(rif))='".strtoupper($opcion)."'  "." and ano_movimiento=".$ano,  null , 'numero_orden_pago ASC');
				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else



	if($var1!=null){
	            $ano_orden_pago    = $this->data['reposicion']['ano_orden_pago_'.$var1];
	            $numero_orden_pago = $this->data['reposicion']['numero_orden_pago_'.$var1];
				$this->layout = "pdf";
				$this->set('opcion', '2');
				$datos_cugd02_dependencias=$this->cugd02_dependencia->findAll();
				$datos_cepd03_ordenpago_cuerpo=$this->cepd03_ordenpago_cuerpo->findAll($condicion.' '." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'  ");
				$datos_cepd03_ordenpago_facturas=$this->cepd03_ordenpago_facturas->findAll($condicion);
				$this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
				$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
				$this->set('fecha_cheque', $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
				$this->set('titulo_a', $this->Session->read('dependencia'));
				$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);
	}//fin if
}//fin function





function comprobante_egreso_preimpreso($opcion=null, $var1=null, $var2=null){
    $cod_presi       =  $this->Session->read('SScodpresi');
	$cod_entidad     =  $this->Session->read('SScodentidad');
	$cod_tipo_inst   =  $this->Session->read('SScodtipoinst');
	$cod_inst        =  $this->Session->read('SScodinst');
	$cod_dep         =  $this->Session->read('SScoddep');
	$condicion       =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano             =  $this->ano_ejecucion();
	$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");
	$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
	$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
	$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);
	$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
	$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
	$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));
	$lista = "";




	if($opcion=='a'){
		        $this->layout = "ajax";
				$array = $this->cepd03_ordenpago_cuerpo->findAll($condicion.' and numero_comprobante_egreso!=0 and condicion_actividad=1'." and ano_movimiento=".$ano,  'DISTINCT  autorizado, rif ', 'ORDER BY autorizado', null);
				foreach($array as $b_aux){$lista[$b_aux['cepd03_ordenpago_cuerpo']['rif']]=$b_aux['cepd03_ordenpago_cuerpo']['autorizado'];}
				$this->set('lista', $lista);
				$this->set('opcion', null);

				$this->set('opcion_dirrecion', 6);
				$this->set('year', $ano);

	}else if($opcion==null){
				$this->layout = "ajax";
				$this->set('opcion', '1');
	}else if($opcion!='si'){
		        $this->layout = "ajax";
	            $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
				$campos = 'cod_presi ,
							cod_entidad,
							cod_tipo_inst,
							cod_inst,
							cod_dep,
							ano_movimiento,
							cuenta_bancaria,
							numero_cheque,
							fecha_cheque,
							numero_comprobante_egreso,
							SUM(monto_neto_cobrar)  as "monto_neto_cobrar" ';


							$agrupar = 'GROUP BY cod_presi,
							cod_entidad,
							cod_tipo_inst,
							cod_inst,
							cod_dep,
							ano_movimiento ,
							cuenta_bancaria ,
							numero_cheque ,
							fecha_cheque,
							numero_comprobante_egreso';

	            $array = $this->cepd03_ordenpago_cuerpo->findAll($sql." and numero_comprobante_egreso!=0 and upper(trim(rif))='".strtoupper($opcion)."'  and condicion_actividad=1 "." and ano_movimiento=".$ano." ".$agrupar,   $campos, 'numero_cheque, numero_comprobante_egreso DESC');


				$this->set('lista', $array);
				$this->set('opcion', '1');
	}//fin else






	if($var1!=null){
	            $ano_movimiento    = $this->data['reposicion']['ano_movimiento_'.$var1];
	            $numero_comprobante_egreso = $this->data['reposicion']['numero_comprobante_egreso_'.$var1];
				$this->layout = "pdf";
				$this->set('opcion', '2');
				$datos_cugd02_dependencias=$this->cugd02_dependencia->findAll();
				$datos_cepd03_ordenpago_cuerpo=$this->cepd03_ordenpago_cuerpo->findAll($condicion.' '."  and ano_movimiento='".$ano_movimiento."' and numero_comprobante_egreso='".$numero_comprobante_egreso."'  and condicion_actividad=1 ");


				$ano_comprobante_egreso      =  $datos_cepd03_ordenpago_cuerpo[0]['cepd03_ordenpago_cuerpo']['ano_movimiento'];
                $datos_cstd06_comprobante_cuerpo_egreso = $this->cstd06_comprobante_cuerpo_egreso->findAll($condicion.' and ano_comprobante_egreso='.$ano_comprobante_egreso.' and numero_comprobante_egreso='.$numero_comprobante_egreso);

		        $ano_comprobante_egreso      =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['ano_comprobante_egreso'];
			    $numero_comprobante_egreso   =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['numero_comprobante_egreso'];
			    $ano_movimiento              =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['ano_movimiento'];
			    $cod_entidad_bancaria        =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['cod_entidad_bancaria'];
			    $cod_sucursal                =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['cod_sucursal'];
			    $cuenta_bancaria             =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['cuenta_bancaria'];
			    $numero_cheque               =  $datos_cstd06_comprobante_cuerpo_egreso[0]['cstd06_comprobante_cuerpo_egreso']['numero_cheque'];
                $datos_cstd03_cheque_cuerpo  = $this->cstd03_cheque_cuerpo->findAll($condicion."                and ano_movimiento=".$ano_movimiento."  and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque=".$numero_cheque);
                $datos_cstd09_notadebito_cuerpo  = $this->cstd09_notadebito_cuerpo_pago->findAll($condicion."   and ano_movimiento=".$ano_movimiento." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_debito=".$numero_cheque);
                $tipodocu=$this->cepd03_tipo_documento->findAll();


				$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);
				$this->set('titulo_a', $this->Session->read('dependencia'));
				$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);
				$this->set('datos_cstd06_comprobante_cuerpo_egreso',      $datos_cstd06_comprobante_cuerpo_egreso);
				$this->set('datos_cstd03_cheque_cuerpo',      $datos_cstd03_cheque_cuerpo);
				$this->set('datos_cstd09_notadebito_cuerpo',      $datos_cstd09_notadebito_cuerpo);
				$this->set('tipodocu', $tipodocu);
				$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
				$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
				$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));

	}//fin if





}//fin function





function comprobante_egreso_cheq_manuales_1(){
	$this->layout="ajax";
    $ano             =  $this->ano_ejecucion();
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

	$sql_select = "SELECT DISTINCT beneficiario FROM cstd03_movimientos_manuales WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND tipo_documento=4 AND condicion_actividad=1  and ano_movimiento='".$ano."' ORDER BY beneficiario ";
	$select = $this->cstd03_movimientos_manuales->execute($sql_select);
	for($i=0; $i<count($select); $i++){
		$benef[$select[$i][0]['beneficiario']] = $select[$i][0]['beneficiario'];
	}
	//pr($select);
	$this->set('beneficiarios',$benef);
}//comprobante_egreso_cheq_manuales_1


function comprobante_egreso_cheq_manuales_2(){
	$this->layout="ajax";
    $ano             =  $this->ano_ejecucion();
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

   	$sql_estado = "SELECT denominacion FROM cugd01_estados WHERE cod_republica=$cod_presi AND cod_estado=$cod_entidad";
    $data_estado = $this->cepd03_ordenpago_cuerpo->execute($sql_estado);
    $estado="ESTADO ".$data_estado[0][0]['denominacion'];
    $this->set('estado',$estado);

	$sql_select = "SELECT DISTINCT beneficiario FROM cstd03_movimientos_manuales WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND tipo_documento=4 AND condicion_actividad=1 and ano_movimiento='".$ano."' ORDER BY beneficiario";
	$select = $this->cstd03_movimientos_manuales->execute($sql_select);
	for($i=0; $i<count($select); $i++){
		$benef[$select[$i][0]['beneficiario']] = ($i+1).' - '.$select[$i][0]['beneficiario'];
	}
	//pr($benef);
	$this->set('beneficiarios',$benef);
}//comprobante_egreso_cheq_manuales_1


function listar_comprobantes_mov_manuales($formato=null,$var=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$beneficiario = strtoupper($var);
	$ano             =  $this->ano_ejecucion();
	$lista = $this->cstd03_movimientos_manuales->execute("
			SELECT
			        a.ano_movimiento,
			        a.cod_entidad_bancaria,
			        a.cod_sucursal,
			        a.cuenta_bancaria,
			        a.numero_documento,
			        a.fecha_documento,
			        a.monto,
                   (SELECT b.numero_comprobante_egreso FROM cstd06_comprobante_cuerpo_egreso b WHERE a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.cod_dep=b.cod_dep AND a.ano_movimiento=b.ano_movimiento AND a.cod_entidad_bancaria=b.cod_entidad_bancaria AND a.cod_sucursal=b.cod_sucursal AND a.cuenta_bancaria=b.cuenta_bancaria AND a.numero_documento=b.numero_cheque) AS numero_comprobante_egreso

         FROM cstd03_movimientos_manuales a

         WHERE

         a.cod_presi='$cod_presi' AND
         a.cod_entidad='$cod_entidad' AND
         a.cod_tipo_inst='$cod_tipo_inst' AND
         a.cod_inst='$cod_inst' AND
         a.cod_dep='$cod_dep' AND
         a.tipo_documento=4 AND
         a.ano_movimiento='".$ano."' AND
         a.condicion_actividad=1 AND
         UPPER(a.beneficiario)  LIKE upper('$beneficiario')

");


	$this->set('lista',$lista);
	$this->set('formato',$formato);
}


function generar_comprobante_egreso_cheq_manuales($ano_movimiento=null, $cod_entidad_bancaria=null, $cod_sucursal=null, $cuenta_bancaria=null, $numero_documento=null,$opcion=null){
	$this->layout = "pdf";

	$ano_movimiento;
	$cod_entidad_bancaria;
	$cod_sucursal;
	$cuenta_bancaria;
	$numero_documento;
	$opcion;

	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$sql_comprobante = "SELECT a.cod_entidad_bancaria, a.cuenta_bancaria, a.numero_documento, a.fecha_documento, a.monto, a.beneficiario, a.concepto,
						(SELECT c.denominacion FROM cstd01_entidades_bancarias c WHERE a.cod_entidad_bancaria=c.cod_entidad_bancaria) AS entidad_bancaria,
						(SELECT b.numero_comprobante_egreso FROM cstd06_comprobante_cuerpo_egreso b WHERE a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.cod_dep=b.cod_dep AND a.ano_movimiento=b.ano_movimiento AND a.cod_entidad_bancaria=b.cod_entidad_bancaria AND a.cod_sucursal=b.cod_sucursal AND a.cuenta_bancaria=b.cuenta_bancaria AND a.numero_documento=b.numero_cheque) AS numero_comprobante_egreso
						FROM cstd03_movimientos_manuales a
						WHERE
						a.cod_presi = '$cod_presi' AND
						a.cod_entidad = '$cod_entidad' AND
						a.cod_tipo_inst = '$cod_tipo_inst' AND
						a.cod_inst = '$cod_inst' AND
						a.cod_dep = '$cod_dep' AND
						a.ano_movimiento = '$ano_movimiento' AND
						a.cod_entidad_bancaria = '$cod_entidad_bancaria' AND
						a.cod_sucursal = '$cod_sucursal' AND
						a.cuenta_bancaria = '$cuenta_bancaria' AND
						a.tipo_documento = 4 AND
						a.condicion_actividad=1 AND
						a.numero_documento = '$numero_documento'";
	$datos = $this->cstd03_movimientos_manuales->execute($sql_comprobante);
	$this->set('datos',$datos);
	$this->set('opcion',$opcion);
}




}//fin class
