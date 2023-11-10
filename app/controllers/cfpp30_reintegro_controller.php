<?php
/*
 * Created on 27/02/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class Cfpp30ReintegroController extends AppController{


 	var $uses = array('ccfd03_instalacion','ccfd04_cierre_mes', 'v_cfpd02_sector', 'cfpd30_reintegro_cuerpo',
                      'cfpd30_reintegro_partidas', 'cfpd23_numero_asiento_pagado', 'cfpd05', 'cugd04', 'cfpd23',
                      'cfpd22_numero_asiento_causado', 'cfpd21_numero_asiento_compromiso', 'cfpd21', 'cfpd22',
                      'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cstd01_sucursales_bancarias',
                      'cstd02_cuentas_bancarias', 'v_cfpd05_disponibilidad',

                       'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                        'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo', 'cstd30_debito_cuerpo',
                        'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'cscd04_ordcom_modificacion_cuerpo',
					    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo', 'cstd09_notadebito_ordenes',
					    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
					    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'cscd02_solicitud_encabezado',
					    'v_cstd03_cheque_orden_pago', 'v_cstd09_notadebito_orden_pago', 'v_inventario_muebles_todo', 'v_inventario_inmuebles_todo',
					    'cimd01_clasificacion_seccion');

 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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


function beforeFilter(){
					$this->checkSession();
					if($this->ano_ejecucion()!=""){
						return;
					}else{
						echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
						exit();
					}
}

function actualizame_op () {
    $this->layout="ajax";
    $this->Session->write('up_op',"");//date("H:i:s")

}

function index(){
	$this->layout="ajax";
	$this->limpiar_lista();
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);
	$lista2=  $this->v_cfpd02_sector->generateList($this->condicion()." and ano='$ano'", 'cod_sector ASC', null, '{n}.v_cfpd02_sector.cod_sector', '{n}.v_cfpd02_sector.denominacion');
  	$this->concatena($lista2, 'sector');
  	$ano = $this->ano_ejecucion();
	$numero_reintegro = $this->cfpd30_reintegro_cuerpo->execute("SELECT MAX(numero_reintegro) as numero_reintegro FROM cfpd30_reintegro_cuerpo WHERE ".$this->condicion()." and ano_reintegro=$ano ;");
	//pr($numero_reintegro);
	$numero = $numero_reintegro[0][0]['numero_reintegro'] + 1;
	$this->set('numero', $numero);

	    $this->set('cod_entidad_lista',$this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));


}//fin if


function pre_compromiso($monto=null){
	$this->layout="ajax";
	if($monto != null){
		//echo $this->formato1($monto);
		if($this->formato1($monto) == "0.00"){
			$this->set('nulo', '');
		}
	}else{
		$this->set('nulo', '');
	}
}

function compromiso($monto=null){
	$this->layout="ajax";
	if($monto != null){
		//echo $this->formato1($monto);
		if($this->formato1($monto) == "0.00"){
			$this->set('nulo', '');
		}
	}else{
		$this->set('nulo', '');
	}

}

function causado($monto=null){
	$this->layout="ajax";
	if($monto != null){
		//echo $this->formato1($monto);
		if($this->formato1($monto) == "0.00"){
			$this->set('nulo', '');
		}
	}else{
		$this->set('nulo', '');
	}

}

function pagado($monto=null){
	$this->layout="ajax";
	if($monto != null){
		//echo $this->formato1($monto);
		if($this->formato1($monto) == "0.00"){
			$this->set('nulo', '');
		}
	}else{
		$this->set('nulo', '');
	}

}

function agregar_partidas($var=null) {
	$this->layout="ajax";

	if(isset($var) && !empty($var)){
            $cod[0]=$this->data["cscp04_ordencompra"]["ano_partidas"];
			$cod[1]=$this->data["cscp04_ordencompra"]["cod_sector"];
			$cod[2]=$this->data["cscp04_ordencompra"]["cod_programa"];
			$cod[3]=$this->data["cscp04_ordencompra"]["cod_subprograma"];
			$cod[4]=$this->data["cscp04_ordencompra"]["cod_proyecto"];
			$cod[5]=$this->data["cscp04_ordencompra"]["cod_actividad"];
			$cod[6]=$this->data["cscp04_ordencompra"]["cod_partida"];
			if($cod[6]<9){
				$cod[6]="40".$cod[6];
			}else if($cod[6]<100){
				$cod[6]="4".$cod[6];
			}else{
				$cod[6]=$cod[6];
			}

			$cod[7]=$this->data["cscp04_ordencompra"]["cod_generica"];
			$cod[8]=$this->data["cscp04_ordencompra"]["cod_especifica"];
			$cod[9]=$this->data["cscp04_ordencompra"]["cod_subespecifica"];
			$cod[10]=$this->data["cscp04_ordencompra"]["cod_auxiliar"];//
			$cod[10]=$cod[10]<9?str_replace("0","",$cod[10]):$cod[10];
			$cod[10]=$cod[10]<9?"0".$cod[10]:$cod[10];
			if(isset($this->data["cfpp30_reintegro"]["monto_pre"]) && !empty($this->data["cfpp30_reintegro"]["monto_pre"])){
				$cod[11]=$this->data["cfpp30_reintegro"]["monto_pre"];
			}else{
				$cod[11]= "0,00";
			}
			if(isset($this->data["cfpp30_reintegro"]["monto_comp"]) && !empty($this->data["cfpp30_reintegro"]["monto_comp"])){
				$cod[12]=$this->data["cfpp30_reintegro"]["monto_comp"];
			}else{
				$cod[12]= "0,00";
			}
			if(isset($this->data["cfpp30_reintegro"]["monto_cau"]) && !empty($this->data["cfpp30_reintegro"]["monto_cau"])){
				$cod[13]=$this->data["cfpp30_reintegro"]["monto_cau"];
			}else{
				$cod[13]= "0,00";
			}
			if(isset($this->data["cfpp30_reintegro"]["monto_pag"]) && !empty($this->data["cfpp30_reintegro"]["monto_pag"])){
				$cod[14]=$this->data["cfpp30_reintegro"]["monto_pag"];
			}else{
				$cod[14]= "0,00";
			}
			//echo $cod[11]."-".$cod[12]."-".$cod[13]."-".$cod[14];
		    if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
	    }else{
		   $this->Session->write("i",0);
			$i=0;
		}
        switch($var){
        	case 'normal':
					 $vec[$i][0]=$this->data["cscp04_ordencompra"]["ano_partidas"];
					 $vec[$i][1]=$this->data["cscp04_ordencompra"]["cod_sector"];
					 $vec[$i][2]=$this->data["cscp04_ordencompra"]["cod_programa"];
					 $vec[$i][3]=$this->data["cscp04_ordencompra"]["cod_subprograma"];
					 $vec[$i][4]=$this->data["cscp04_ordencompra"]["cod_proyecto"];
					 $vec[$i][5]=$this->data["cscp04_ordencompra"]["cod_actividad"];
					 $vec[$i][6]=$this->data["cscp04_ordencompra"]["cod_partida"];//<9 ? "4.0".$this->data["cepp01_compromiso_partidas"]["cod_partida"] : "4.".$this->data["cepp01_compromiso_partidas"]["cod_partida"];
					 $vec[$i][7]=$this->data["cscp04_ordencompra"]["cod_generica"];
					 $vec[$i][8]=$this->data["cscp04_ordencompra"]["cod_especifica"];
					 $vec[$i][9]=$this->data["cscp04_ordencompra"]["cod_subespecifica"];
					 $vec[$i][10]=$this->mascara_cuatro($this->data["cscp04_ordencompra"]["cod_auxiliar"]);
					 $vec[$i][11]=$this->data["cscp04_ordencompra"]["monto_partidas"];
					 $vec[$i]["id"]=$i;
					 if(isset($_SESSION["codigos"])){
						foreach($_SESSION["codigos"] as $codi){
							//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        }else{
                        	$_SESSION["codigos"]=$_SESSION["codigos"]+$vec;
                          //  echo "si";
                        }
					 }else{
						$_SESSION["codigos"]=$vec;
					 }
        	break;
        	case 'nuevos':
                     $vec[$i][0]=$cod[0];
					 $vec[$i][1]=$this->AddCeroR($cod[1]);
					 $vec[$i][2]=$this->AddCeroR($cod[2]);
					 $vec[$i][3]=$this->AddCeroR($cod[3]);
					 $vec[$i][4]=$this->AddCeroR($cod[4]);
					 $vec[$i][5]=$this->AddCeroR($cod[5]);
					 $vec[$i][6]=$cod[6];
					 $vec[$i][7]=$this->AddCeroR($cod[7]);
					 $vec[$i][8]=$this->AddCeroR($cod[8]);
					 $vec[$i][9]=$this->AddCeroR($cod[9]);
					 $vec[$i][10]=$this->mascara_cuatro($cod[10]);
					 $vec[$i][11]=$cod[11];
					 $vec[$i][12]=$cod[12];
					 $vec[$i][13]=$cod[13];
					 $vec[$i][14]=$cod[14];
					 $vec[$i]["id"]=$i;
					 if(isset($_SESSION["codigos"])){
						foreach($_SESSION["codigos"] as $codi){
							//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        }else{
                        	$_SESSION["codigos"]=$_SESSION["codigos"]+$vec;
                        	echo "<script>";
				     			echo "document.getElementById('cant_items').value=".$i.";";
				     	//echo "alert(eval(document.getElementById('cant_items').value));";
				     		echo "</script>";
                          //  echo "si";
                        }
					 }else{
						$_SESSION["codigos"]=$vec;
						echo "<script>";
				     		echo "document.getElementById('cant_items').value=".$i.";";
				     	//echo "alert(eval(document.getElementById('cant_items').value));";
				     	echo "</script>";
					 }

        	break;

        }//fin switch


		}//
}//fin funcu¡ions

function eliminar_items ($id) {
	$this->layout = "ajax";
	$this->set('i', $id);
	$_SESSION["codigos"][$id]=null;
	$this->render("agregar_partidas");

}

function limpiar_lista () {
	$this->layout = "ajax";
	$this->Session->delete("codigos");
	$this->Session->delete("i");
}

function eliminar_item($i=null, $ano=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null, $cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $cod_auxiliar=null){
	$this->layout="ajax";
	$username = strtoupper($this->Session->read('nom_usuario'));
	//$cond_partidas = "ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' and ";
	$sql_delete_cugd04="DELETE FROM cugd04 WHERE username='$username' and ".$this->condicion();

	$sw = $this->borrar_cugd04();
	$this->Session->delete("codigos");
	$this->Session->delete("i");


}






function select_para_cuenta_bancaria($var1=null, $var2=null, $var3=null, $var4=null){


$this->layout= "ajax";

			     if($var1==1){
                    $this->set('var1', $var2);
                    $lista = "";
		            if($var2!=""){
					  $lista = $this->cstd01_sucursales_bancarias->generateList("cod_entidad_bancaria=".$var2, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
					}//fin if
					if(!is_array($lista)){
						$this->set('cod_sucursal_lista', array());
					}else{
						$this->set('cod_sucursal_lista', $lista);
					}//fin else
					    $c=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$var2);
		                $this->set("deno", $c[0]["cstd01_entidades_bancarias"]["denominacion"]);
		                $this->set("cod",  $c[0]["cstd01_entidades_bancarias"]["cod_entidad_bancaria"]);
			}else if($var1==2){
                    $this->set('var1', $var2);
                    $this->set('var2', $var3);
                    $lista = "";
                    if($var2!=""){
					    $lista =  $this->cstd02_cuentas_bancarias->generateList($this->condicion()." and cod_entidad_bancaria=".$var2." and cod_sucursal=".$var3, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
					}//fin if
					if(!is_array($lista)){
						$this->set('cod_cuenta_lista', array());
					}else{
						$this->set('cod_cuenta_lista',$lista);
					}//fin else
					    $c=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$var2." and cod_sucursal=".$var3);
		                $this->set("deno", $c[0]["cstd01_sucursales_bancarias"]["denominacion"]);
		                $this->set("cod",  $c[0]["cstd01_sucursales_bancarias"]["cod_sucursal"]);

		    }else if($var1==3){
                    $this->set('var1', $var2);
                    $this->set('var2', $var3);
                    $this->set('var3', $var4);

                        $c=$this->cstd02_cuentas_bancarias->findAll($this->condicion()." and cod_entidad_bancaria=".$var2." and cod_sucursal=".$var3." and cuenta_bancaria='".$var4."' ");
		                $this->set("concep_m",  $c[0]["cstd02_cuentas_bancarias"]["concepto_manejo"]);

			}//fin else


$this->set('opcion', $var1);

}//fin function










function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
	if(!empty($this->data['cfpp30_reintegro'])){


		$ano_reintegro = $ann = $ano = $this->data['cfpp30_reintegro']['ano_reintegro'];

		$numero_reintegro        = $this->data['cfpp30_reintegro']['numero_reintegro'];
		$fecha_reintegro = $fd   = $this->data['cfpp30_reintegro']['fecha_reintegro'];
		$funcionario_responsable = $this->data['cfpp30_reintegro']['funcionario'];

        if(!empty($this->data['cfpp30_reintegro']['cod_entidad_bancaria'])){
	        $cod_ent_pago_aux        = $this->data['cfpp30_reintegro']['cod_entidad_bancaria'];
	        $cod_suc_pago_aux        = $this->data['cfpp30_reintegro']['cod_sucursal_bancaria'];
	 	    $cod_cta_pago_aux        = $this->data['cfpp30_reintegro']['cod_cuenta_bancaria'];
	 	    $tipo_doc                = $this->data['cfpp30_reintegro']['tipo_doc'];
	 	    $num_cheque              = $this->data['cfpp30_reintegro']['num_cheque'];
	 	    $concepto_m              = $this->data['cfpp30_reintegro']['concepto_m'];
        }else{
	        $cod_ent_pago_aux        = 0;
	        $cod_suc_pago_aux        = 0;
	 	    $cod_cta_pago_aux        = 0;
	 	    $tipo_doc                = 0;
	 	    $num_cheque              = 0;
	 	    $concepto_m              = 0;
        }//fin else




		$concepto = $this->data['cfpp30_reintegro']['concepto'];
		$fecha_proceso_registro = date('d/m/Y');
		$dia_asiento_registro = 0;
		$mes_asiento_registro = 0;
		$ano_asiento_registro = 0;
		$numero_asiento_registro = 0;
		$username_registro = $this->Session->read('nom_usuario');
		$condicion_actividad = 1;
		$dia_asiento_anulacion = 0;
		$mes_asiento_anulacion = 0;
		$ano_asiento_anulacion = 0;
		$numero_asiento_contable= 0;
		$ano_acta_anulacion = 0;
		$numero_acta_anulacion = 0;
		$username_anulacion = "0";
		$fecha_proceso_anulacion = '1900/01/01';

		$sql_insert_cfpp30_reintegro_cuerpo = "BEGIN; INSERT INTO cfpd30_reintegro_cuerpo VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_reintegro', '$numero_reintegro', '$fecha_reintegro', '$funcionario_responsable', '$concepto', '$fecha_proceso_registro', '$dia_asiento_registro', '$mes_asiento_registro', '$ano_asiento_registro', '$numero_asiento_registro', '$username_registro', '$condicion_actividad', '$dia_asiento_anulacion', '$mes_asiento_anulacion', '$ano_asiento_anulacion', '$numero_asiento_contable', '$ano_acta_anulacion', '$numero_acta_anulacion', '$username_anulacion', '$fecha_proceso_anulacion', '".$cod_ent_pago_aux."', '".$cod_suc_pago_aux."', '".$cod_cta_pago_aux."', '".$tipo_doc."', '".$num_cheque."')";

		$sw1 = $this->cfpd30_reintegro_cuerpo->execute($sql_insert_cfpp30_reintegro_cuerpo);

		if($sw1 > 1){
			$cont_comp=0;
			$cont_cau=0;
			$cont_pag=0;
			$montos["monto_pre_compromiso"] = 0;
		    $montos["monto_compromiso"]     = 0;
		    $montos["monto_causado"]        = 0;
		    $montos["monto_pagado"]         = 0;
			$i=0;
			$new_array = array();
			foreach($_SESSION["codigos"] as $nss){
				if($nss!=null){
				     $montos["monto_pre_compromiso"] += $this->Formato1($nss[11]);
				     $montos["monto_compromiso"]     += $this->Formato1($nss[12]);
				     $montos["monto_causado"]        += $this->Formato1($nss[13]);
				     $montos["monto_pagado"]         += $this->Formato1($nss[14]);
				}//fin if
				if($nss!=null && $nss[11] == '0,00'){
		           $new_array[$i]['ano']=$nss[0];
		             $new_array[$i]['cod_sector']=$nss[1];
		             $new_array[$i]['cod_programa']=$nss[2];
		             $new_array[$i]['cod_sub_prog']=$nss[3];
		             $new_array[$i]['cod_proyecto']=$nss[4];
		             $new_array[$i]['cod_activ_obra']=$nss[5];
		             $new_array[$i]['cod_partida']=$nss[6];
		             $new_array[$i]['cod_generica']=$nss[7];
		             $new_array[$i]['cod_especifica']=$nss[8];
		             $new_array[$i]['cod_sub_espec']=$nss[9];
		             $new_array[$i]['cod_auxiliar']=$nss[10];
		             $new_array[$i]['monto_pre']=$this->Formato1($nss[11]);
		             $new_array[$i]['monto_comp']=$this->Formato1($nss[12]);
		             $new_array[$i]['monto_cau']=$this->Formato1($nss[13]);
		             $new_array[$i]['monto_pag']=$this->Formato1($nss[14]);
		             $i++;

		             if($nss[12] != '0,00' && $nss[13] == '0,00' && $nss[14] == '0,00'){// VA AL MOTOR SOLO EL COMPROMISO
			        	$cont_comp++;
						$cont_cau;
						$cont_pag;

			        }elseif($nss[12] != '0,00' && $nss[13] == '0,00' && $nss[14] != '0,00'){// VA AL MOTOR EL COMPROMISO, CAUSADO Y PAGADO
			        	$cont_comp++;
						$cont_cau++;
						$cont_pag++;

			        }elseif($nss[12] != '0,00' && $nss[13] != '0,00' && $nss[14] != '0,00'){// VA AL MOTOR EL COMPROMISO, CAUSADO Y PAGADO
			        	$cont_comp++;
						$cont_cau++;
						$cont_pag++;

			        }elseif($nss[12] != '0,00' && $nss[13] != '0,00' && $nss[14] == '0,00'){// VA AL MOTOR EL COMPROMISO Y EL CAUSADO
			        	$cont_comp++;
						$cont_cau++;
						$cont_pag;

			        }elseif($nss[12] == '0,00' && $nss[13] == '0,00' && $nss[14] != '0,00'){// VA AL MOTOR COMPROMISO, CAUSADO Y PAGADO
			        	$cont_comp++;
						$cont_cau++;
						$cont_pag++;

			        }elseif($nss[12] == '0,00' && $nss[13] != '0,00' && $nss[14] == '0,00'){// VA AL MOTOR COMPROMISO Y EL CAUSADO
			        	$cont_comp++;
						$cont_cau++;
						$cont_pag;

			        }elseif($nss[12] == '0,00' && $nss[13] != '0,00' && $nss[14] != '0,00'){// VA AL MOTOR COMPROMISO, CAUSADO Y PAGADO
			        	$cont_comp++;
						$cont_cau++;
						$cont_pag++;

			        }elseif($nss[12] == '0,00' && $nss[13] == '0,00' && $nss[14] == '0,00'){// NO VA AL MOTOR NINGUNO, ES PORQUE INGRESARON SOLO EN EL PRECOMPROMISO
			        	$cont_comp;
						$cont_cau;
						$cont_pag;
			        }
		        }//null
		      }//fin foreach

		    $i=0;
		    $new_array2 = array();
			foreach($_SESSION["codigos"] as $nss2){
		        if($nss!=null && $nss2[11] != '0,00'){
		           $new_array2[$i]['ano']=$nss2[0];
		             $new_array2[$i]['cod_sector']=$nss2[1];
		             $new_array2[$i]['cod_programa']=$nss2[2];
		             $new_array2[$i]['cod_sub_prog']=$nss2[3];
		             $new_array2[$i]['cod_proyecto']=$nss2[4];
		             $new_array2[$i]['cod_activ_obra']=$nss2[5];
		             $new_array2[$i]['cod_partida']=$nss2[6];
		             $new_array2[$i]['cod_generica']=$nss2[7];
		             $new_array2[$i]['cod_especifica']=$nss2[8];
		             $new_array2[$i]['cod_sub_espec']=$nss2[9];
		             $new_array2[$i]['cod_auxiliar']=$nss2[10];
		             $new_array2[$i]['monto_pre']=$this->Formato1($nss2[11]);
		             $i++;
		        }//null
		      }//fin foreach

		      $update_pre_compromiso = "";
		      $activa = 1;
		      $sw_pre = 2;
			//DESDE AQUI INSERTO LOS PRECOMPROMISO
               if(count($new_array2) != 0){
					    foreach($new_array2 as $cod2){
						    	$ano = $cod2['ano'];
							    $cod_sector= $cod2['cod_sector'];
							    $cod_programa = $cod2['cod_programa'];
							    $cod_sub_prog = $cod2['cod_sub_prog'];
							    $cod_proyecto = $cod2['cod_proyecto'];
							    $cod_activ_obra = $cod2['cod_activ_obra'];
							    $cod_partida = $cod2['cod_partida'];
							    $cod_generica = $cod2['cod_generica'];
							    $cod_especifica = $cod2['cod_especifica'];
							    $cod_sub_espec = $cod2['cod_sub_espec'];
							    $cod_auxiliar = $cod2['cod_auxiliar'];
							    $monto_pre = $cod2['monto_pre'];

							    $update_pre_compromiso          = "UPDATE cfpd05 SET precompromiso_congelado=precompromiso_congelado - $monto_pre WHERE ".$this->condicion()." and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' ;";
							    $sql_insert_notadebito_partidas = "INSERT INTO cfpd30_reintegro_partidas VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_reintegro', '$numero_reintegro', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$monto_pre', '0', '0', '0');";
							    $sw_pre = $this->cfpd05->execute($sql_insert_notadebito_partidas.$update_pre_compromiso);
								if($sw_pre > 1){
								}else{
									$activa = 0;
								}
					      }
                }
                if($activa==0){$sw_pre=0;}
			    if($sw_pre > 1){
												$j =0;
												$indice_comp=0;
												$indice_cau =0;
												$indice_pag =0;
												if(count($new_array) != 0){
													foreach($new_array as $cod){

												        $ano = $cod['ano'];
												        $cod_sector= $cod['cod_sector'];
												        $cod_programa = $cod['cod_programa'];
												        $cod_sub_prog = $cod['cod_sub_prog'];
												        $cod_proyecto = $cod['cod_proyecto'];
												        $cod_activ_obra = $cod['cod_activ_obra'];
												        $cod_partida = $cod['cod_partida'];
												        $cod_generica = $cod['cod_generica'];
												        $cod_especifica = $cod['cod_especifica'];
												        $cod_sub_espec = $cod['cod_sub_espec'];
												        $cod_auxiliar = $cod['cod_auxiliar'];
												        $monto_pre = $cod['monto_pre'];
												        $monto_comp = $cod['monto_comp'];
												        $monto_cau = $cod['monto_cau'];
												        $monto_pag = $cod['monto_pag'];

												         $pasa_comp = 0;
												         $pasa_cau = 0;
												         $pasa_pag = 0;

												        if($monto_comp != '0.00' && $monto_cau == '0.00' && $monto_pag == '0.00'){// VA AL MOTOR SOLO EL COMPROMISO
												        	//echo "<br>VA AL MOTOR SOLO EL COMPROMISO";
												        	//echo "<br>1: &nbsp; pasa_comp ".$pasa_comp=1; echo "<br>&nbsp; pasa_cau ".$pasa_cau=0; echo "<br>&nbsp; pasa_pag ".$pasa_pag=0;
												        	$pasa_comp=1; $pasa_cau=0; $pasa_pag=0;

												        }elseif($monto_comp != '0.00' && $monto_cau == '0.00' && $monto_pag != '0.00'){// VA AL MOTOR EL COMPROMISO, CAUSADO Y PAGADO
													        //echo "<br>VA AL MOTOR EL COMPROMISO, CAUSADO Y PAGADO";
												        	//echo "<br>2: &nbsp; pasa_comp ".$pasa_comp=1; echo "<br>&nbsp; pasa_cau ".$pasa_cau=1; echo "<br>&nbsp; pasa_pag ".$pasa_pag=1;
												        	$pasa_comp=1; $pasa_cau=1; $pasa_pag=1;

												        }elseif($monto_comp != '0.00' && $monto_cau != '0.00' && $monto_pag != '0.00'){// VA AL MOTOR EL COMPROMISO, CAUSADO Y PAGADO
												        	//echo "<br>VA AL MOTOR EL COMPROMISO, CAUSADO Y PAGADO";
												        	//echo "<br>3: &nbsp; pasa_comp ".$pasa_comp=1; echo "<br>&nbsp; pasa_cau ".$pasa_cau=1; echo "<br>&nbsp; pasa_pag ".$pasa_pag=1;
												        	$pasa_comp=1; $pasa_cau=1; $pasa_pag=1;

												        }elseif($monto_comp != '0.00' && $monto_cau != '0.00' && $monto_pag == '0.00'){// VA AL MOTOR EL COMPROMISO Y EL CAUSADO
												        	//echo "<br>VA AL MOTOR EL COMPROMISO Y EL CAUSADO";
												        	//echo "<br>4: &nbsp; pasa_comp ".$pasa_comp=1; echo "<br>&nbsp; pasa_cau ".$pasa_cau=1; echo "<br>&nbsp; pasa_pag ".$pasa_pag=0;
												        	$pasa_comp=1; $pasa_cau=1; $pasa_pag=0;

												        }elseif($monto_comp == '0.00' && $monto_cau == '0.00' && $monto_pag != '0.00'){// VA AL MOTOR COMPROMISO, CAUSADO Y PAGADO
												        	//echo "<br>VA AL MOTOR COMPROMISO, CAUSADO Y PAGADO";
												        	//echo "<br>5: &nbsp; pasa_comp ".$pasa_comp=1; echo "<br>&nbsp; pasa_cau ".$pasa_cau=1; echo "<br>&nbsp; pasa_pag ".$pasa_pag=1;
												        	$pasa_comp=1; $pasa_cau=1; $pasa_pag=1;

												        }elseif($monto_comp == '0.00' && $monto_cau != '0.00' && $monto_pag == '0.00'){// VA AL MOTOR COMPROMISO Y EL CAUSADO
												        	//echo "<br>VA AL MOTOR COMPROMISO Y EL CAUSADO";
												        	//echo "<br>6: &nbsp; pasa_comp ".$pasa_comp=1; echo "<br>&nbsp; pasa_cau ".$pasa_cau=1; echo "<br>&nbsp; pasa_pag ".$pasa_pag=0;
												        	$pasa_comp=1; $pasa_cau=1; $pasa_pag=0;

												        }elseif($monto_comp == '0.00' && $monto_cau != '0.00' && $monto_pag != '0.00'){// VA AL MOTOR COMPROMISO, CAUSADO Y PAGADO
												        	//echo "<br>VA AL MOTOR COMPROMISO, CAUSADO Y PAGADO";
												        	//echo "<br>7: &nbsp; pasa_comp ".$pasa_comp=1; echo "<br>&nbsp; pasa_cau ".$pasa_cau=1; echo "<br>&nbsp; pasa_pag ".$pasa_pag=1;
												        	$pasa_comp=1; $pasa_cau=1; $pasa_pag=1;

												        }elseif($monto_comp == '0.00' && $monto_cau == '0.00' && $monto_pag == '0.00'){// NO VA AL MOTOR NINGUNO, ES PORQUE INGRESARON SOLO EN EL PRECOMPROMISO
												        	//echo "<br>NO VA AL MOTOR NINGUNO";
												        	//echo "<br>7: &nbsp; pasa_comp ".$pasa_comp=0; echo "<br>&nbsp; pasa_cau ".$pasa_cau=0; echo "<br>&nbsp; pasa_pag ".$pasa_pag=0;
												        	$pasa_comp=0; $pasa_cau=0; $pasa_pag=0;
												        }


														if($pasa_comp==1 && $pasa_cau==1 && $pasa_pag==1){

														    $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP()." and ano_compromiso='$ann'", $order =null);
														    if(!empty($numero_compromiso)){
														      $numero_compromiso ++;
														      $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' and ".$this->condicionNDEP().";";
														    }else{
														      $numero_compromiso = 1;
														      $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ano', '$numero_compromiso');";
														    }
														    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);

																if($sw_numero_compromiso > 1){
															        $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
															        $to   = 1;
															        $td   = 3;
															        $ta   = 7;
															        $ccp  = $concepto;
															        $mt   = $monto_comp;
															        $rnco = $numero_compromiso;

															  $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $numero_reintegro, null, null, null, null, null, null, null, $rnco, null, null, null, $indice_comp);

															        if($dnco){
															          $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);

															          if(!empty($numero_causado)){
															            $numero_causado ++;
															            $sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
															          }else{
															            $numero_causado = 1;
															            $sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado');";
															          }//fin if
															          $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);

															          if($sw_numero_causado > 1){
															            $to   = 1;
															            $td   = 4;
															            $ta   = 10;
															            $mt   = $monto_cau;
															            $rnca = $numero_causado;

															    $dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp='', $ann, $numero_reintegro, null, null, null, null, null, null, null, $rnco, $rnca, null, null, $indice_cau);

															            if($dnca){
																          $numero_pagado= $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', $conditions = $this->condicionNDEP()." and ano_pagado='$ann'", $order =null);

																          if(!empty($numero_pagado)){
																            $numero_pagado ++;
																            $sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='$ann' and ".$this->condicionNDEP().";";
																          }else{
																            $numero_pagado = 1;
																            $sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_pagado');";
																          }//fin if
																          $sw_numero_pagado = $this->cfpd23_numero_asiento_pagado->query($sql_numero_pagado);

																          if($sw_numero_pagado > 1){
																			  $to   = 1;
																              $td   = 5;
																              $ta   = 4;
																              $mt   = $monto_pag;
																              $rnpa = $numero_pagado;

																  $dnpa = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo=null, $nda=null, $opago=null, $opfecha=$fd, $cbanco=null, $ccuenta=null, $ccheque=$numero_reintegro, $fechache=$fd, $rnco, $rnca, $rnpa, null, $indice_pag);

																              if($dnpa != false){

																                  $sql_insert_notadebito_partidas = "INSERT INTO cfpd30_reintegro_partidas VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_reintegro', '$numero_reintegro', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$monto_pre', '$monto_comp', '$monto_cau', '$monto_pag', '$rnco', '$rnca', '$rnpa');";
																                  $sw4 = $this->cfpd30_reintegro_partidas->execute($sql_insert_notadebito_partidas);
																                  if($sw4 > 1){

																                  		$motor = true;
																                  }else{
																                      //echo "rollback sw2";
																                    $motor = false;
																                    $this->cfpd30_reintegro_partidas->execute("ROLLBACK;");
																                    $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
																                    $this->data=null;
																                    $this->index();
																                    $this->render('index');
																                    return;
																                  }
															                }else{
															                  $this->cfpd05->execute("ROLLBACK;");
															                  $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															                }
																          }else{//ROLLBACK SI NO EJECUTA EL NUMERO DE CONTROL DE pagado
																              $this->cfpd22_numero_asiento_causado->execute("ROLLBACK;");
																              $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															            	}

															              }else{
															                $this->cfpd05->execute("ROLLBACK;");
															                $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															                $this->data=null;
															                $this->index();
															                $this->render('index');
															                return;
															              }

															            }else{//ROLLBACK SI NO EJECUTA EL NUMERO DE CONTROL DE CAUSADO
															              $this->cfpd22_numero_asiento_causado->execute("ROLLBACK;");
															              $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															            }

															          //FIN COMPROMISOS
															          }else{
															            $this->cfpd05->execute("ROLLBACK;");
															            $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															            $this->data=null;
															            $this->index();
															            $this->render('index');
															            return;
															          }
															   }else{
													            $this->cfpd05->execute("ROLLBACK;");
													            $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
													            $this->data=null;
													            $this->index();
													            $this->render('index');
													            return;
													          }
															  $indice_comp++;
															  $indice_cau++;
															  $indice_pag++;

														//FIN PASA COMPROMISO, CAUSADO, PAGADO @pasa_comp @pasa_cau @pasa_pag
														}elseif($pasa_comp==1 && $pasa_cau==1 && $pasa_pag==0){

															//Pasando nada mas el compromiso y el causado";

														    $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP()." and ano_compromiso='$ann'", $order =null);
														    if(!empty($numero_compromiso)){
														      $numero_compromiso ++;
														      $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' and ".$this->condicionNDEP().";";
														    }else{
														      $numero_compromiso = 1;
														      $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ano', '$numero_compromiso');";
														    }
														    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);

																if($sw_numero_compromiso > 1){
															        $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
															        $to   = 1;
															        $td   = 3;
															        $ta   = 7;
															        $ccp  = $concepto;
															        $mt   = $monto_comp;
															        $rnco = $numero_compromiso;

														  $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $numero_reintegro, null, null, null, null, null, null, null, $rnco, null, null, null, $indice_comp);

															        if($dnco){
															          $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);
															          if(!empty($numero_causado)){
															            $numero_causado ++;
															            $sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
															          }else{

															            $numero_causado = 1;
															            $sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado');";
															          }//fin if
															          $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);

															          if($sw_numero_causado > 1){
															            $to   = 1;
															            $td   = 4;
															            $ta   = 10;
															            $mt   = $monto_cau;
															            $rnca = $numero_causado;

															 $dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp='', $ann, $numero_reintegro, null, null, null, null, null, null, null, $rnco, $rnca, null, null, $indice_cau);

															            if($dnca){
															                  $sql_insert_notadebito_partidas = "INSERT INTO cfpd30_reintegro_partidas VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_reintegro', '$numero_reintegro', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$monto_pre', '$monto_comp', '$monto_cau', '$monto_pag', '$rnco', '$rnca', '0');";
															                  $sw4 = $this->cfpd30_reintegro_partidas->execute($sql_insert_notadebito_partidas);
															                  if($sw4 > 1){
															                  		$motor = true;
															                  }else{
															                      //echo "rollback sw2";
															                    $motor = false;
															                    $this->cfpd30_reintegro_partidas->execute("ROLLBACK;");
															                    $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															                    $this->data=null;
															                    $this->index();
															                    $this->render('index');
															                    return;
															                  }

															              }else{
															                $this->cfpd05->execute("ROLLBACK;");
															                $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															                $this->data=null;
															                $this->index();
															                $this->render('index');
															                return;
															              }

															            }else{//ROLLBACK SI NO EJECUTA EL NUMERO DE CONTROL DE CAUSADO
															              $this->cfpd22_numero_asiento_causado->execute("ROLLBACK;");
															              $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															            }

															          //FIN COMPROMISOS
															          }else{
															            $this->cfpd05->execute("ROLLBACK;");
															            $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															            $this->data=null;
															            $this->index();
															            $this->render('index');
															            return;
															          }
															   }else{
													            $this->cfpd05->execute("ROLLBACK;");
													            $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
													            $this->data=null;
													            $this->index();
													            $this->render('index');
													            return;
													          }
													          $indice_comp++;
															  $indice_cau++;
															  $indice_pag;


														}elseif($pasa_comp==1 && $pasa_cau==0 && $pasa_pag==0){

															//Pasando nada mas el compromiso";

														    $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP()." and ano_compromiso='$ann'", $order =null);
														    if(!empty($numero_compromiso)){
														      $numero_compromiso ++;
														      $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' and ".$this->condicionNDEP().";";
														    }else{
														      $numero_compromiso = 1;
														      $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ano', '$numero_compromiso');";
														    }
														    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);

																if($sw_numero_compromiso > 1){
															        $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
															        $to   = 1;
															        $td   = 3;
															        $ta   = 7;
															        $ccp  = $concepto;
															        $mt   = $monto_comp;
															        $rnco = $numero_compromiso;

															 $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $numero_reintegro, null, null, null, null, null, null, null, $rnco, null, null, null, $indice_comp);


															        if($dnco){
														                  $sql_insert_notadebito_partidas = "INSERT INTO cfpd30_reintegro_partidas VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_reintegro', '$numero_reintegro', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$monto_pre', '$monto_comp', '$monto_cau', '$monto_pag', '$rnco', '0', '0');";
														                  $sw4 = $this->cfpd30_reintegro_partidas->execute($sql_insert_notadebito_partidas);

														                  if($sw4 > 1){
														                  	$motor = true;
														                  }else{
														                    $motor = false;
														                    $this->cfpd30_reintegro_partidas->execute("ROLLBACK;");
														                    $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
														                    $this->data=null;
														                    $this->index();
														                    $this->render('index');
														                    return;
														                  }
															          //FIN COMPROMISOS
															          }else{
															            $this->cfpd05->execute("ROLLBACK;");
															            $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															            $this->data=null;
															            $this->index();
															            $this->render('index');
															            return;
															          }
															   }else{
													            $this->cfpd05->execute("ROLLBACK;");
													            $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
													            $this->data=null;
													            $this->index();
													            $this->render('index');
													            return;
													          }
													          $indice_comp++;
															  $indice_cau;
															  $indice_pag;

														}elseif($pasa_comp==0 && $pasa_cau==0 && $pasa_pag==0){
															//Pasando nada mas el Pre-compromiso";
															$motor = true;
														}
										                //$j++;

													}//foreach


													if($motor){


									                          if($montos["monto_pre_compromiso"]==0){unset($montos["monto_pre_compromiso"]);}
														      if($montos["monto_compromiso"]==0){    unset($montos["monto_compromiso"]);}
														      if($montos["monto_causado"]==0){       unset($montos["monto_causado"]);}
														      if($montos["monto_pagado"]==0){        unset($montos["monto_pagado"]);}

																if(isset($montos["monto_compromiso"]) || isset($montos["monto_causado"]) || isset($montos["monto_pagado"])){

																   $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																                                                                 $to              = 1,
																                                                                 $td              = 18,
																                                                                 $rif_doc         = null,
																                                                                 $ano_dc          = $ano_reintegro,
																                                                                 $n_dc            = $numero_reintegro,
																                                                                 $f_dc            = $this->data['cfpp30_reintegro']['fecha_reintegro'],
																                                                                 $cpt_dc          = $concepto,
																                                                                 $ben_dc          = $funcionario_responsable,
																                                                                 $mon_dc          = $montos,

																                                                                 $ano_op          = null,
																                                                                 $n_op            = null,
																                                                                 $f_op            = null,

																                                                                 $a_adj_op        = null,
																                                                                 $n_adj_op        = null,
																                                                                 $f_adj_op        = null,
																                                                                 $tp_op           = null,

																                                                                 $deno_ban_pago   = null,
																                                                                 $ano_movimiento  = null,
																                                                                 $cod_ent_pago    = $cod_ent_pago_aux,
																                                                                 $cod_suc_pago    = $cod_suc_pago_aux,
																                                                                 $cod_cta_pago    = $cod_cta_pago_aux,

																                                                                 $num_che_o_debi  = null,
																                                                                 $fec_che_o_debi  = null,
																                                                                 $clas_che_o_debi = null
																                                                             );
																}else{
																	$valor_motor_contabilidad = true;
																}

															if($valor_motor_contabilidad==true){
																$this->cfpd30_reintegro_partidas->execute("COMMIT;");
																$this->set('msg', 'EL REINTEGRO PRESUPUESTARIO FUE REALIZADO CON EXITO');
															}else{
																$this->cfpd21_numero_asiento_compromiso->query("ROLLBACK;");
															    $this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
															}


													}else{
														$this->cfpd21_numero_asiento_compromiso->query("ROLLBACK;");
														$this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
													}


												}
			    }else{
						$this->cfpd05->execute("ROLLBACK;");
						$this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
				        $this->data=null;
				        $this->index();
				        $this->render('index');
				}

		}else{
			$this->cfpd30_reintegro_cuerpo->execute("ROLLBACK;");
			$this->set('msg_error', "NO SE LOGRO REALIZAR EL REINTEGRO PRESUPUESTARIO - POR FAVOR INTENTE DE NUEVO");
		}
	}


	$this->data= array();
	$this->index();
	$this->render('index');

}



function ano_consultar($year){
    $this->layout="ajax";
	$_SESSION["ano_consulta_reintegro"] = $year;

}



function consulta_index(){
	$this->layout="ajax";
	$_SESSION["ano_consulta_reintegro"] = $this->ano_ejecucion();
    $this->set('ANO',$this->ano_ejecucion());
}





function consulta($pagina=null){
	$this->layout="ajax";

	if(isset($pagina)){
        $pagina=$pagina;
    }else{
         $pagina=1;
    }//fin else

    if(isset($this->data['cfpp30_reintegro']['ano_consulta'])){
         $_SESSION["ano_consulta_reintegro"] = $this->data['cfpp30_reintegro']['ano_consulta'];
    }


    if(!empty($_SESSION["ano_consulta_reintegro"])){
        $ano_consulta=$_SESSION["ano_consulta_reintegro"];
    }else{
         $ano_consulta=$this->ano_ejecucion();
    }//fin else

    $this->set('ano_ejecucion',$this->ano_ejecucion());

    $Tfilas = $this->cfpd30_reintegro_cuerpo->findCount($this->condicion()." and ano_reintegro='".$ano_consulta."'   ");
    if($Tfilas!=0){
	    $this->set('pag_cant',$pagina.'/'.$Tfilas);
	    $this->set('ultimo',$Tfilas);
	    $data =$this->cfpd30_reintegro_cuerpo->findAll($this->condicion()." and ano_reintegro='".$ano_consulta."'   ",null,'numero_reintegro ASC',1,$pagina,null);
	    foreach ($data as $row){
	       $ano_movimiento        = $row['cfpd30_reintegro_cuerpo']['ano_reintegro'];
	       $concepto_manejo       = $row['cfpd30_reintegro_cuerpo']['concepto'];
	       $numero_documento      = $row['cfpd30_reintegro_cuerpo']['numero_reintegro'];
	       $condicion_actividad   = $row['cfpd30_reintegro_cuerpo']['condicion_actividad'];
	       $ano_acta_anulacion    = $row['cfpd30_reintegro_cuerpo']['ano_acta_anulacion'];
	       $numero_acta_anulacion = $row['cfpd30_reintegro_cuerpo']['numero_acta_anulacion'];

	       $cod_entidad_bancaria  = $row['cfpd30_reintegro_cuerpo']['cod_entidad_bancaria'];
	       $cod_sucursal          = $row['cfpd30_reintegro_cuerpo']['cod_sucursal'];
	       $cuenta_bancaria       = $row['cfpd30_reintegro_cuerpo']['cuenta_bancaria'];
	       $tipo_documento        = $row['cfpd30_reintegro_cuerpo']['tipo_documento'];
	       $numero_cheque         = $row['cfpd30_reintegro_cuerpo']['numero_documento'];
	    }





	    if($cuenta_bancaria!="" && $cuenta_bancaria!=0){

	                    $c=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria);
		                $this->set("deno_enti", $c[0]["cstd01_entidades_bancarias"]["denominacion"]);
		                $this->set("cod_enti",  $c[0]["cstd01_entidades_bancarias"]["cod_entidad_bancaria"]);

					    $c=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal);
		                $this->set("deno_sucu", $c[0]["cstd01_sucursales_bancarias"]["denominacion"]);
		                $this->set("cod_sucu",  $c[0]["cstd01_sucursales_bancarias"]["cod_sucursal"]);

		                $c=$this->cstd02_cuentas_bancarias->findAll($this->condicion()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' ");
		                $this->set("concep_m",  $c[0]["cstd02_cuentas_bancarias"]["concepto_manejo"]);

		                $this->set("cuenta",    $cuenta_bancaria);
		                $this->set("tipo_doc",  $tipo_documento);
		                $this->set("num_doc",   $numero_cheque);
                  }else{

                  	    $this->set("deno_enti", "");
		                $this->set("cod_enti",  "");
                        $this->set("deno_sucu", "");
		                $this->set("cod_sucu",  "");
		                $this->set("cuenta",    "");
		                $this->set("tipo_doc",  "");
		                $this->set("num_doc",   "");
		                $this->set("concep_m",   "");
                  }//fin else



	    $tipo_operacion = 254;
	    if($condicion_actividad==2){
	    	$motivo_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.motivo_anulacion', $conditions = $this->condicion()." and ano_acta_anulacion='$ano_acta_anulacion' and numero_acta_anulacion='$numero_acta_anulacion' and tipo_operacion='$tipo_operacion' and ano_documento='$ano_movimiento' and numero_documento='$numero_documento'", $order =null);
			//$motivo_anulacion="";
			$disabled = 'disabled';
	    }else{
	    	$motivo_anulacion="";
	    	$disabled = "";
	    }
	    //echo $motivo_anulacion;
	    $this->set('disabled', $disabled);
	    $this->set('motivo_anulacion', $motivo_anulacion);
	    $this->set('data', $data);
	    $datos = $this->cfpd30_reintegro_partidas->findAll($conditions = $this->condicion()." and ano_reintegro='$ano_movimiento' and numero_reintegro='$numero_documento'", $fields = null, $order = 'ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar', $limit = null, $page = null, $recursive = null);
	    $this->set('datos', $datos);

	    $this->set('siguiente',$pagina+1);
	    $this->set('anterior',$pagina-1);
	    $this->bt_nav($Tfilas,$pagina);

	}else{
		$this->set('NOTA_DEBITO','');
	    $this->set('msg_error', 'No se encontrar&oacute;n datos en la consulta');
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

function preanular(){
  $this->layout="ajax";

}

function anular($ano_reintegro=null, $numero_reintegro=null, $page=null){
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
    $cod_entidad   = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst      = $this->Session->read('SScodinst');
    $cod_dep       = $this->Session->read('SScoddep');
    $username      = $this->Session->read('nom_usuario');

	if($ano_reintegro != null && $numero_reintegro != null){

								$ccp = $concepto_anulacion = $this->data['cfpp30_reintegro']['concepto_anulacion'];


								$ano                     = $ano_reintegro;
								$fecha_proceso_anulacion = date('d/m/Y');
								$partidas                = $this->cfpd30_reintegro_partidas->findAll($conditions = $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $fields = null, $order = null, $limit = null, $page2 = null, $recursive = null);
								$fecha_rendicion         = $this->cfpd30_reintegro_cuerpo->field('cfpd30_reintegro_cuerpo.fecha_reintegro', $conditions, $order =null);
								$fd                      = $fecha_rendicion[8].$fecha_rendicion[9].'/'.$fecha_rendicion[5].$fecha_rendicion[6].'/'.$fecha_rendicion[0].$fecha_rendicion[1].$fecha_rendicion[2].$fecha_rendicion[3];
								$nro_acta_anulacion      = $this->cugd03_acta_anulacion_numero->field('cugd03_acta_anulacion_numero.numero_acta_anulacion', $conditions = $this->condicion()." and ano_acta_anulacion='$ano'", $order=null);

								if(empty($nro_acta_anulacion)){
									$nro_acta_anulacion = 1;
									$sql_update_numero_anulacion = "INSERT INTO cugd03_acta_anulacion_numero VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$nro_acta_anulacion');";
								}else{
									$nro_acta_anulacion += 1;
									$sql_update_numero_anulacion = "UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion='$nro_acta_anulacion' WHERE ".$this->condicion()." and ano_acta_anulacion='$ano';";
								}
								$sql_insert_anulacion = "INSERT INTO cugd03_acta_anulacion_cuerpo VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$nro_acta_anulacion', '254', '$ano', '$numero_reintegro', '$fd', '$concepto_anulacion');";
								$sw1 = $this->cugd03_acta_anulacion_numero->execute("BEGIN; ".$sql_update_numero_anulacion.$sql_insert_anulacion);

								if($sw1 > 1){

									  $montos["monto_pre_compromiso"] = 0;
									  $montos["monto_compromiso"]     = 0;
									  $montos["monto_causado"]        = 0;
									  $montos["monto_pagado"]         = 0;

									  $cod_entidad_bancaria_reintegro         = $this->cfpd30_reintegro_cuerpo->field('cfpd30_reintegro_cuerpo.cod_entidad_bancaria', $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $order =null);
									  $cuenta_bancaria_reintegro              = $this->cfpd30_reintegro_cuerpo->field('cfpd30_reintegro_cuerpo.cuenta_bancaria', $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $order =null);
									  $numero_cheque_reintegro                = $this->cfpd30_reintegro_cuerpo->field('cfpd30_reintegro_cuerpo.numero_documento', $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $order =null);

									foreach($partidas as $row){
										$continua                  = false;
										$ano_reintegro             = $row['cfpd30_reintegro_partidas']['ano_reintegro'];
									    $numero_rendicion          = $row['cfpd30_reintegro_partidas']['numero_reintegro'];
									    $ano                       = $row['cfpd30_reintegro_partidas']['ano'];
									    $cod_sector                = $row['cfpd30_reintegro_partidas']['cod_sector'];
									    $cod_programa              = $row['cfpd30_reintegro_partidas']['cod_programa'];
									    $cod_sub_prog              = $row['cfpd30_reintegro_partidas']['cod_sub_prog'];
									    $cod_proyecto              = $row['cfpd30_reintegro_partidas']['cod_proyecto'];
									    $cod_activ_obra            = $row['cfpd30_reintegro_partidas']['cod_activ_obra'];
									    $cod_partida               = $row['cfpd30_reintegro_partidas']['cod_partida'];
									    $cod_generica              = $row['cfpd30_reintegro_partidas']['cod_generica'];
									    $cod_especifica            = $row['cfpd30_reintegro_partidas']['cod_especifica'];
									    $cod_sub_espec             = $row['cfpd30_reintegro_partidas']['cod_sub_espec'];
									    $cod_auxiliar              = $row['cfpd30_reintegro_partidas']['cod_auxiliar'];
									    $monto_pre                 = $row['cfpd30_reintegro_partidas']['monto_pre_compromiso'];
									    $monto_comp                = $row['cfpd30_reintegro_partidas']['monto_compromiso'];
									    $monto_cau                 = $row['cfpd30_reintegro_partidas']['monto_causado'];
									    $monto_pag                 = $row['cfpd30_reintegro_partidas']['monto_pagado'];
									    $numero_control_compromiso = $row['cfpd30_reintegro_partidas']['numero_asiento_compromiso'];
								        $numero_control_causado    = $row['cfpd30_reintegro_partidas']['numero_asiento_causado'];
								        $numero_control_pagado     = $row['cfpd30_reintegro_partidas']['numero_asiento_pagado'];

								          $montos["monto_pre_compromiso"] += $monto_pre;
										  $montos["monto_compromiso"]     += $monto_comp;
										  $montos["monto_causado"]        += $monto_cau;
										  $montos["monto_pagado"]         += $monto_pag;

								        $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

									    if($monto_pre != '0.00'){
											$update_pre_compromiso = "UPDATE cfpd05 SET precompromiso_congelado=precompromiso_congelado + $monto_pre WHERE ".$this->condicion()." and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' ;";
											$sw2 = $this->cfpd05->execute($update_pre_compromiso);
											if($sw2 > 1){
												$continua = true;
											}else{
												$this->cfpd05->execute('ROLLBACK;');
												$continua = false;
												$this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO1');
											}
									    }else{//REALIZO LA ANULACION DEL COMPROMISO, CAUSADO Y PAGADO

									    if($monto_comp != '0.00'){
											$numero_compromiso_control = $this->motor_presupuestario($cp, 2, 3, 7, $fd, $monto_comp, $ccp, $ano, $numero_rendicion, null, null, null, null, null, null, null, $numero_control_compromiso, null, null, null, null);
									    }
									    if($numero_compromiso_control){
									    	if($monto_cau != '0.00'){
									            $numero_causado_control = $this->motor_presupuestario($cp, 2, 4, 10, $fd, $monto_cau, $ccp, $ano, $numero_rendicion, null, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null, null);
									        }
									        if($numero_causado_control){
									    		if($monto_pag != '0.00'){
									              $numero_pagado_control = $this->motor_presupuestario($cp, 2, 5, 4, $fd, $monto_pag, $ccp, $ano, $numero_rendicion, null, null, null, $cod_entidad_bancaria_reintegro, $cuenta_bancaria_reintegro, $numero_cheque_reintegro, $fechache=$fd, $numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null,null);
									         	}
									            if($numero_pagado_control){
									              	$continua = true;

									            }else{
									                $this->cfpd05->execute("ROLLBACK;");
									                $this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO2');
									            }
									        }else{
									            $this->cfpd05->execute("ROLLBACK;");
									            $this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO3');
									        }
									    }else{
									            $this->cfpd05->execute("ROLLBACK;");
									            $this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO4');
									    }

									    }


								}//FIN DEL FOREACH


									if($continua == true){
										$sw_cond_activ = $this->cfpd30_reintegro_cuerpo->execute("UPDATE cfpd30_reintegro_cuerpo SET condicion_actividad=2, username_anulacion='$username', fecha_proceso_anulacion='$fecha_proceso_anulacion', ano_acta_anulacion='$ano', numero_acta_anulacion='$nro_acta_anulacion' WHERE ".$this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro';");
									}else{
										$sw_cond_activ = -1;
										$this->cfpd30_reintegro_cuerpo->execute('ROLLBACK;');
										$this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO5');
									}


													if($sw_cond_activ > 1){



														$ano_reintegro           = $this->data['cfpp30_reintegro']['ano_reintegro'];
														$numero_reintegro        = $this->data['cfpp30_reintegro']['numero_reintegro'];
														$fecha_reintegro         = $this->data['cfpp30_reintegro']['fecha_reintegro'];
														$funcionario_responsable = $this->data['cfpp30_reintegro']['funcionario'];
												        $cod_ent_pago_aux        = $this->data['cfpp30_reintegro']['cod_entidad'];
												        $cod_suc_pago_aux        = $this->data['cfpp30_reintegro']['cod_sucu'];
												 	    $cod_cta_pago_aux        = $this->data['cfpp30_reintegro']['cod_cuenta_bancaria'];
												 	    $num_cheque              = $this->data['cfpp30_reintegro']['num_cheque'];
												 	    $concepto_m              = $this->data['cfpp30_reintegro']['concepto_m'];

												 	      if($montos["monto_pre_compromiso"]==0){unset($montos["monto_pre_compromiso"]);}
													      if($montos["monto_compromiso"]==0){    unset($montos["monto_compromiso"]);}
													      if($montos["monto_causado"]==0){       unset($montos["monto_causado"]);}
													      if($montos["monto_pagado"]==0){        unset($montos["monto_pagado"]);}

															if(isset($montos["monto_compromiso"]) || isset($montos["monto_causado"]) || isset($montos["monto_pagado"])){

															  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
															                                                                 $to              = 2,
															                                                                 $td              = 18,
															                                                                 $rif_doc         = null,
															                                                                 $ano_dc          = $ano_reintegro,
															                                                                 $n_dc            = $numero_reintegro,
															                                                                 $f_dc            = date("d/m/Y"),
															                                                                 $cpt_dc          = null,
															                                                                 $ben_dc          = $funcionario_responsable,
															                                                                 $mon_dc          = $montos,

															                                                                 $ano_op          = null,
															                                                                 $n_op            = null,
															                                                                 $f_op            = null,

															                                                                 $a_adj_op        = null,
															                                                                 $n_adj_op        = null,
															                                                                 $f_adj_op        = null,
															                                                                 $tp_op           = null,

															                                                                 $deno_ban_pago   = null,
															                                                                 $ano_movimiento  = null,
															                                                                 $cod_ent_pago    = $cod_ent_pago_aux,
															                                                                 $cod_suc_pago    = $cod_suc_pago_aux,
															                                                                 $cod_cta_pago    = $cod_cta_pago_aux,

															                                                                 $num_che_o_debi  = null,
															                                                                 $fec_che_o_debi  = null,
															                                                                 $clas_che_o_debi = null
															                                                             );
															}else{
																$valor_motor_contabilidad=true;
															}//fin else

														if($valor_motor_contabilidad==true){
															$this->cfpd30_reintegro_cuerpo->execute('COMMIT;');
														    $this->set('msg', 'LA ANULACIÓN FUE REALIZADA CON EXITO');
														}else{
															$this->cfpd30_reintegro_cuerpo->execute('ROLLBACK;');
														    $this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO (6)');
														}


													}else{
														$this->cfpd30_reintegro_cuerpo->execute('ROLLBACK;');
														$this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO (6)');
//														$this->consulta($page);
//										    			$this->render('consulta');
													}

								}else{
									$this->cugd03_acta_anulacion_numero->execute('ROLLBACK;');
									$this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO (7)');
								}
								//print_r($partidas);
                   echo "<script type='text/javascript'>";
					echo "ver_documento('/cfpp30_reintegro/consulta/".$page."' ,'principal_cfpp30_reintegro');";
				   echo "</script>";
 	}//fin if



} // fin de funcion







function anular_confirm($ano_reintegro=null, $numero_reintegro=null, $page=null){
	$this->layout="ajax";


	$cod_presi     = $this->Session->read('SScodpresi');
    $cod_entidad   = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst      = $this->Session->read('SScodinst');
    $cod_dep       = $this->Session->read('SScoddep');
    $username      = $this->Session->read('nom_usuario');


    if($ano_reintegro != null && $numero_reintegro != null){
		$ccp = $concepto_anulacion = $_SESSION["CONCEPTO_ANULACION_REINTEGRO"];
		//echo $ccp;
		$ano                     = $ano_reintegro;
		$fecha_proceso_anulacion = date('d/m/Y');
		$partidas                = $this->cfpd30_reintegro_partidas->findAll($conditions = $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $fields = null, $order = null, $limit = null, $page2 = null, $recursive = null);
		$fecha_rendicion         = $this->cfpd30_reintegro_cuerpo->field('cfpd30_reintegro_cuerpo.fecha_reintegro', $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $order =null);
		$fd                      = $fecha_rendicion[8].$fecha_rendicion[9].'/'.$fecha_rendicion[5].$fecha_rendicion[6].'/'.$fecha_rendicion[0].$fecha_rendicion[1].$fecha_rendicion[2].$fecha_rendicion[3];
		$nro_acta_anulacion      = $this->cugd03_acta_anulacion_numero->field('cugd03_acta_anulacion_numero.numero_acta_anulacion', $conditions = $this->condicion()." and ano_acta_anulacion='$ano'", $order=null);

		if(empty($nro_acta_anulacion)){
			//echo "no encontre el acta de anulacion es 1<br>";
			$nro_acta_anulacion = 1;
			$sql_update_numero_anulacion = "INSERT INTO cugd03_acta_anulacion_numero VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$nro_acta_anulacion');";
		}else{
			//echo "encontre el acta de anulacion<br>";
			$nro_acta_anulacion += 1;
			$sql_update_numero_anulacion = "UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion='$nro_acta_anulacion' WHERE ".$this->condicion()." and ano_acta_anulacion='$ano';";
		}
		$sql_insert_anulacion = "INSERT INTO cugd03_acta_anulacion_cuerpo VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$nro_acta_anulacion', '254', '$ano', '$numero_reintegro', '$fd', '$concepto_anulacion');";
		$sw1 = $this->cugd03_acta_anulacion_numero->execute("BEGIN; ".$sql_update_numero_anulacion.$sql_insert_anulacion);

		if($sw1 > 1){

			  $montos["monto_pre_compromiso"] = 0;
			  $montos["monto_compromiso"]     = 0;
			  $montos["monto_causado"]        = 0;
			  $montos["monto_pagado"]         = 0;

			  $cod_entidad_bancaria_reintegro         = $this->cfpd30_reintegro_cuerpo->field('cfpd30_reintegro_cuerpo.cod_entidad_bancaria', $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $order =null);
			  $cuenta_bancaria_reintegro              = $this->cfpd30_reintegro_cuerpo->field('cfpd30_reintegro_cuerpo.cuenta_bancaria', $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $order =null);
			  $numero_cheque_reintegro                = $this->cfpd30_reintegro_cuerpo->field('cfpd30_reintegro_cuerpo.numero_documento', $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $order =null);

			foreach($partidas as $row){
				$continua                  = false;
				$ano_reintegro             = $row['cfpd30_reintegro_partidas']['ano_reintegro'];
			    $numero_rendicion          = $row['cfpd30_reintegro_partidas']['numero_reintegro'];
			    $ano                       = $row['cfpd30_reintegro_partidas']['ano'];
			    $cod_sector                = $row['cfpd30_reintegro_partidas']['cod_sector'];
			    $cod_programa              = $row['cfpd30_reintegro_partidas']['cod_programa'];
			    $cod_sub_prog              = $row['cfpd30_reintegro_partidas']['cod_sub_prog'];
			    $cod_proyecto              = $row['cfpd30_reintegro_partidas']['cod_proyecto'];
			    $cod_activ_obra            = $row['cfpd30_reintegro_partidas']['cod_activ_obra'];
			    $cod_partida               = $row['cfpd30_reintegro_partidas']['cod_partida'];
			    $cod_generica              = $row['cfpd30_reintegro_partidas']['cod_generica'];
			    $cod_especifica            = $row['cfpd30_reintegro_partidas']['cod_especifica'];
			    $cod_sub_espec             = $row['cfpd30_reintegro_partidas']['cod_sub_espec'];
			    $cod_auxiliar              = $row['cfpd30_reintegro_partidas']['cod_auxiliar'];
			    $monto_pre                 = $row['cfpd30_reintegro_partidas']['monto_pre_compromiso'];
			    $monto_comp                = $row['cfpd30_reintegro_partidas']['monto_compromiso'];
			    $monto_cau                 = $row['cfpd30_reintegro_partidas']['monto_causado'];
			    $monto_pag                 = $row['cfpd30_reintegro_partidas']['monto_pagado'];
			    $numero_control_compromiso = $row['cfpd30_reintegro_partidas']['numero_asiento_compromiso'];
		        $numero_control_causado    = $row['cfpd30_reintegro_partidas']['numero_asiento_causado'];
		        $numero_control_pagado     = $row['cfpd30_reintegro_partidas']['numero_asiento_pagado'];

		          $montos["monto_pre_compromiso"] += $monto_pre;
				  $montos["monto_compromiso"]     += $monto_comp;
				  $montos["monto_causado"]        += $monto_cau;
				  $montos["monto_pagado"]         += $monto_pag;

		        $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
			    if($monto_pre != '0.00'){
					$update_pre_compromiso = "UPDATE cfpd05 SET precompromiso_congelado=precompromiso_congelado + $monto_pre WHERE ".$this->condicion()." and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' ;";
					$sw2 = $this->cfpd05->execute($update_pre_compromiso);
					if($sw2 > 1){
						$continua = true;
					}else{
						$this->cfpd05->execute('ROLLBACK;');
						$continua = false;
						$this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO1');
					}
			    }else{//REALIZO LA ANULACION DEL COMPROMISO, CAUSADO Y PAGADO

					$numero_compromiso_control = $this->motor_presupuestario($cp, 2, 3, 7, $fd, $monto_comp, $ccp, $ano, $numero_rendicion, null, null, null, null, null, null, null, $numero_control_compromiso, null, null, null, null);
			          if($numero_compromiso_control){
			            //echo "anulo compromiso<br/>";
			            $numero_causado_control = $this->motor_presupuestario($cp, 2, 4, 10, $fd, $monto_cau, $ccp, $ano, $numero_rendicion, null, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null, null);
			            if($numero_causado_control){
			            //  echo "anulo causado<br/>";
			              $numero_pagado_control = $this->motor_presupuestario($cp, 2, 5, 4, $fd, $monto_pag, $ccp, $ano, $numero_rendicion, null, null, null, $cod_entidad_bancaria_reintegro, $cuenta_bancaria_reintegro, $numero_cheque_reintegro, $fechache=$fd, $numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null,null);
			              if($numero_pagado_control){
			              	$continua = true;
			             //   echo "anulo pagado<br/>";
			              }else{
			                $this->cfpd05->execute("ROLLBACK;");
			                $this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO2');
			              }
			            }else{
			              $this->cfpd05->execute("ROLLBACK;");
			              $this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO3');
			            }
			          }else{
			            $this->cfpd05->execute("ROLLBACK;");
			            $this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO4');
			          }

			    }

			}//FIN DEL FOREACH
			if($continua == true){
				$sw_cond_activ = $this->cfpd30_reintegro_cuerpo->execute("UPDATE cfpd30_reintegro_cuerpo SET condicion_actividad=2, username_anulacion='$username', fecha_proceso_anulacion='$fecha_proceso_anulacion', ano_acta_anulacion='$ano', numero_acta_anulacion='$nro_acta_anulacion' WHERE ".$this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro';");
			}else{
				$sw_cond_activ = -1;
				$this->cfpd30_reintegro_cuerpo->execute('ROLLBACK;');
				$this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO5');
			}


							if($sw_cond_activ > 1){



								$ano_reintegro           = $_SESSION['ano_reintegro'];
								$numero_reintegro        = $_SESSION['numero_reintegro'];
								$fecha_reintegro         = $_SESSION['fecha_reintegro'];
								$funcionario_responsable = $_SESSION['funcionario'];
						        $cod_ent_pago_aux        = $_SESSION['cod_entidad'];
						        $cod_suc_pago_aux        = $_SESSION['cod_sucu'];
						 	    $cod_cta_pago_aux        = $_SESSION['cod_cuenta_bancaria'];
						 	    $num_cheque              = $_SESSION['num_cheque'];
						 	    $concepto_m              = $_SESSION['concepto_m'];

						 	      if($montos["monto_pre_compromiso"]==0){unset($montos["monto_pre_compromiso"]);}
							      if($montos["monto_compromiso"]==0){    unset($montos["monto_compromiso"]);}
							      if($montos["monto_causado"]==0){       unset($montos["monto_causado"]);}
							      if($montos["monto_pagado"]==0){        unset($montos["monto_pagado"]);}

									if(isset($montos["monto_compromiso"]) || isset($montos["monto_causado"]) || isset($montos["monto_pagado"])){

									  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
									                                                                 $to              = 2,
									                                                                 $td              = 18,
									                                                                 $rif_doc         = null,
									                                                                 $ano_dc          = $ano_reintegro,
									                                                                 $n_dc            = $numero_reintegro,
									                                                                 $f_dc            = date("d/m/Y"),
									                                                                 $cpt_dc          = null,
									                                                                 $ben_dc          = $funcionario_responsable,
									                                                                 $mon_dc          = $montos,

									                                                                 $ano_op          = null,
									                                                                 $n_op            = null,
									                                                                 $f_op            = null,

									                                                                 $a_adj_op        = null,
									                                                                 $n_adj_op        = null,
									                                                                 $f_adj_op        = null,
									                                                                 $tp_op           = null,

									                                                                 $deno_ban_pago   = null,
									                                                                 $ano_movimiento  = null,
									                                                                 $cod_ent_pago    = $cod_ent_pago_aux,
									                                                                 $cod_suc_pago    = $cod_suc_pago_aux,
									                                                                 $cod_cta_pago    = $cod_cta_pago_aux,

									                                                                 $num_che_o_debi  = null,
									                                                                 $fec_che_o_debi  = null,
									                                                                 $clas_che_o_debi = null
									                                                             );
									}else{
										$valor_motor_contabilidad=true;
									}//fin else

								if($valor_motor_contabilidad==true){
									$this->cfpd30_reintegro_cuerpo->execute('COMMIT;');
								    $this->set('msg', 'LA ANULACIÓN FUE REALIZADA CON EXITO');
								}else{
									$this->cfpd30_reintegro_cuerpo->execute('ROLLBACK;');
								    $this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO6');
								}


							}else{
								$this->cfpd30_reintegro_cuerpo->execute('ROLLBACK;');
								$this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO6');
								$this->consulta($page);
				    			$this->render('consulta');
							}

		}else{
			$this->cugd03_acta_anulacion_numero->execute('ROLLBACK;');
			$this->set('msg_error', 'NO SE LOGRO REALIZAR LA ANULACIÓN POR FAVOR INTENTE DE NUEVO7');
		}

		//print_r($partidas);
    }
    $this->consulta($page);
    $this->render('consulta');
}






function comprobar_numero($numero_reintegro=null){
	$this->layout = "ajax";
	if($numero_reintegro != null){
		$cant = $this->cfpd30_reintegro_cuerpo->findCount("numero_reintegro='$numero_reintegro'");
		if($cant != 0){
			$this->set('msj_error', "YA EXISTE UN REINTEGRO CON ESE NUMERO");
		}
	}else{
		$numero_reintegro = "";
	}

	$this->set('numero_reintegro', $numero_reintegro);
}

function automatico($opc=null){
	$this->layout = "ajax";
	//echo $opc;
	if($opc != null){
		if($opc == 0){
			$readonly = "";
			$numero = "";
		}else{
			$readonly = "readonly";
			$ano = $this->ano_ejecucion();
			$numero_reintegro = $this->cfpd30_reintegro_cuerpo->execute("SELECT MAX(numero_reintegro) as numero_reintegro FROM cfpd30_reintegro_cuerpo WHERE ".$this->condicion()." and ano_reintegro=$ano ;");
			//pr($numero_reintegro);
			$numero = $numero_reintegro[0][0]['numero_reintegro'] + 1;
		}
	}else{
		$readonly = "";
		$numero = "";
	}
	$this->set('numero', $this->zero($numero));
	$this->set('readonly', $readonly);

}

}//fin de la clase
?>
