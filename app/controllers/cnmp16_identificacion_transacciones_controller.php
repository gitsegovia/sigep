<?php

 class Cnmp16IdentificacionTransaccionesController extends AppController{

	var $name = 'cnmp16_identificacion_transacciones';
	var $uses = array('cnmd16_identif_transa', 'v_cnmp16_identificacion_transacciones', 'cnmd03_transacciones', 'ccfd03_instalacion', 'ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');


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


function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

  return $condicion;

}



function index($activa=null){
	$this->layout="ajax";
	if($activa!=null && $activa=="si"){ $this->set('activa_si', true); }

	$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 1', $order = "cod_transaccion ASC", $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	if(!empty($cnmd03)){
		$this->concatenaN($cnmd03, 'transaccion');
	}else{
		$this->set('transaccion', array());
	}

	$cnmd03_deduccion = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 2', $order = "cod_transaccion ASC", $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	if(!empty($cnmd03_deduccion)){
		$this->concatenaN($cnmd03_deduccion, 'transaccion_deduccion');
	}else{
		$this->set('transaccion_deduccion', array());
	}

	$dato_transacciones = array();
	// $datos_trans = $this->cnmd16_identif_transa->findAll($this->condicion(), $fields = null, $order = null, $limit = 1, $page = null, $recursive = null);
	$datos_trans = $this->v_cnmp16_identificacion_transacciones->findAll($this->condicion(), $fields = null, $order = null, $limit = 1);
	if(!empty($datos_trans)){
		$this->set('verifica_existe', true);
		$this->set('dato_transacciones', $datos_trans);
	}
}


	// PARA LA F. INDEX:
	// CREANDO TRANSACCIONES DE ASIGNACIONES: cod_tipo_transaccion=1

	/*

		$dato_transacciones[1]['codi_transaccion_1'] = $datos_trans[0]['cnmd16_identif_transa']['cod_asig_vaca']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_asig_vaca'] : '';
		$dato_transacciones[1]['deno_transaccion_1'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_asig_vaca']."' and cod_tipo_transaccion='1'", $order ="cod_transaccion ASC");

		$dato_transacciones[2]['codi_transaccion_2'] = $datos_trans[0]['cnmd16_identif_transa']['cod_asig_bonificacion']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_asig_bonificacion'] : '';
		$dato_transacciones[2]['deno_transaccion_2'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_asig_bonificacion']."' and cod_tipo_transaccion='1'", $order ="cod_transaccion ASC");

		$dato_transacciones[3]['codi_transaccion_3'] = $datos_trans[0]['cnmd16_identif_transa']['cod_asig_bono_vaca']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_asig_bono_vaca'] : '';
		$dato_transacciones[3]['deno_transaccion_3'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_asig_bono_vaca']."' and cod_tipo_transaccion='1'", $order ="cod_transaccion ASC");

		// $dato_transacciones[4]['codi_transaccion_4'] = $datos_trans[0]['cnmd16_identif_transa']['cod_asig_sab_dom_fer']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_asig_sab_dom_fer'] : '';
		// $dato_transacciones[4]['deno_transaccion_4'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_asig_sab_dom_fer']."' and cod_tipo_transaccion='1'", $order ="cod_transaccion ASC");
	*/

	/** TRANSACCIONES DE ASIGNACIONES RESERVADAS A FUTURO:

		$dato_transacciones[5]['codi_transaccion_5'] = $datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado1']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado1'] : '';
		$dato_transacciones[5]['deno_transaccion_5'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado1']."' and cod_tipo_transaccion='1'", $order ="cod_transaccion ASC");

		$dato_transacciones[6]['codi_transaccion_6'] = $datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado2']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado2'] : '';
		$dato_transacciones[6]['deno_transaccion_6'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado2']."' and cod_tipo_transaccion='1'", $order ="cod_transaccion ASC");

		$dato_transacciones[7]['codi_transaccion_7'] = $datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado3']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado3'] : '';
		$dato_transacciones[7]['deno_transaccion_7'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado3']."' and cod_tipo_transaccion='1'", $order ="cod_transaccion ASC");

		$dato_transacciones[8]['codi_transaccion_8'] = $datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado4']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado4'] : '';
		$dato_transacciones[8]['deno_transaccion_8'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_asig_reservado4']."' and cod_tipo_transaccion='1'", $order ="cod_transaccion ASC");

	*/

	// CREANDO TRANSACCIONES DE DEDUCCIONES: cod_tipo_transaccion=2

	/*
		$dato_transacciones[9]['codi_transaccion_9'] = $datos_trans[0]['cnmd16_identif_transa']['cod_ded_seguro_social']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_ded_seguro_social'] : '';
		$dato_transacciones[9]['deno_transaccion_9'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_ded_seguro_social']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[10]['codi_transaccion_10'] = $datos_trans[0]['cnmd16_identif_transa']['cod_ded_paro_forzoso']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_ded_paro_forzoso'] : '';
		$dato_transacciones[10]['deno_transaccion_10'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_ded_paro_forzoso']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[11]['codi_transaccion_11'] = $datos_trans[0]['cnmd16_identif_transa']['cod_ded_fondo_vivienda']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_ded_fondo_vivienda']: '';
		$dato_transacciones[11]['deno_transaccion_11'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_ded_fondo_vivienda']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[12]['codi_transaccion_12'] = $datos_trans[0]['cnmd16_identif_transa']['cod_ded_fondo_jub']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_ded_fondo_jub'] : '';
		$dato_transacciones[12]['deno_transaccion_12'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_ded_fondo_jub']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[13]['codi_transaccion_13'] = $datos_trans[0]['cnmd16_identif_transa']['cod_ded_ahorro']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_ded_ahorro'] : '';
		$dato_transacciones[13]['deno_transaccion_13'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_ded_ahorro']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[14]['codi_transaccion_14'] = $datos_trans[0]['cnmd16_identif_transa']['cod_ded_prestamo']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_ded_prestamo'] : '';
		$dato_transacciones[14]['deno_transaccion_14'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_ded_prestamo']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[15]['codi_transaccion_15'] = $datos_trans[0]['cnmd16_identif_transa']['cod_ded_sindicato']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_ded_sindicato'] : '';
		$dato_transacciones[15]['deno_transaccion_15'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_ded_sindicato']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[16]['codi_transaccion_16'] = $datos_trans[0]['cnmd16_identif_transa']['cod_ded_cred_vivienda']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['cod_ded_cred_vivienda'] : '';
		$dato_transacciones[16]['deno_transaccion_16'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['cod_ded_cred_vivienda']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");
	*/

		/** BLOQUE RESERVADOS DEDUCCIONES 17 - 21 */

	/*
		$dato_transacciones[22]['codi_transaccion_22'] = $datos_trans[0]['cnmd16_identif_transa']['aporte_seguro_social']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['aporte_seguro_social'] : '';
		$dato_transacciones[22]['deno_transaccion_22'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['aporte_seguro_social']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[23]['codi_transaccion_23'] = $datos_trans[0]['cnmd16_identif_transa']['aporte_paro_forzoso']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['aporte_paro_forzoso'] : '';
		$dato_transacciones[23]['deno_transaccion_23'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['aporte_paro_forzoso']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[24]['codi_transaccion_24'] = $datos_trans[0]['cnmd16_identif_transa']['aporte_fondo_vivienda']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['aporte_fondo_vivienda'] : '';
		$dato_transacciones[24]['deno_transaccion_24'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['aporte_fondo_vivienda']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[25]['codi_transaccion_25'] = $datos_trans[0]['cnmd16_identif_transa']['aporte_fondo_jub']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['aporte_fondo_jub'] : '';
		$dato_transacciones[25]['deno_transaccion_25'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['aporte_fondo_jub']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$dato_transacciones[26]['codi_transaccion_26'] = $datos_trans[0]['cnmd16_identif_transa']['aporte_ahorro']!=0 ? $datos_trans[0]['cnmd16_identif_transa']['aporte_ahorro'] : '';
		$dato_transacciones[26]['deno_transaccion_26'] = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='".$datos_trans[0]['cnmd16_identif_transa']['aporte_ahorro']."' and cod_tipo_transaccion='2'", $order ="cod_transaccion ASC");

		$this->set('dato_transacciones', $dato_transacciones);
	*/



function cod_trans($id_trans=null, $cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$this->set('id_trans', $id_trans);
		$this->set('cod_trans', $cod_trans);
	}else{
		$this->set('id_trans', '');
	}
}

function deno_trans($idn_trans=null, $cod_tipo_trans=null, $cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$cod_trans' and cod_tipo_transaccion='$cod_tipo_trans'", $order ="cod_transaccion ASC");
		$this->set('deno_trans', $deno_trans);
		$this->set('idn_trans', $idn_trans);
	}else{
		$this->set('idn_trans', '');
	}
}



function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

			// ASIGNACIONES CASE: 1 - 8
    $cod_vacaciones = (int) $this->data['cnmd16_identif_transa']['cod_trans_1'];
    $cod_bonificacion = (int) $this->data['cnmd16_identif_transa']['cod_trans_2'];
    $cod_bono_vacacional = (int) $this->data['cnmd16_identif_transa']['cod_trans_3'];
    // $cod_sab_dom_feriados = (int) $this->data['cnmd16_identif_transa']['cod_trans_4'];
    $cod_asig_reservados = 0; // Representa Asig.: 5, 6, 7, 8

			// DEDUCCIONES CASE: 9 - 21
    $cod_seg_soc_oblig = (int) $this->data['cnmd16_identif_transa']['cod_trans_9'];
    $cod_paro_forzoso = (int) $this->data['cnmd16_identif_transa']['cod_trans_10'];
    $cod_fondo_aho_oblig_vivienda = (int) $this->data['cnmd16_identif_transa']['cod_trans_11'];
    $cod_fondo_jubilacion = (int) $this->data['cnmd16_identif_transa']['cod_trans_12'];
    $cod_caja_ahorros = (int) $this->data['cnmd16_identif_transa']['cod_trans_13'];
    $cod_prestamo_caja_ahorro = (int) $this->data['cnmd16_identif_transa']['cod_trans_14'];
    $cod_cuota_sindical = (int) $this->data['cnmd16_identif_transa']['cod_trans_15'];
    $cod_cuota_credito_vivienda = (int) $this->data['cnmd16_identif_transa']['cod_trans_16'];
    $cod_ded_reservados = 0; // Representa Deduc.: 17, 18, 19, 20, 21

			// APORTES CASE: 22 - 26
    $aporte_seguro_social = (int) $this->data['cnmd16_identif_transa']['cod_trans_22'];
    $aporte_paro_forzoso = (int) $this->data['cnmd16_identif_transa']['cod_trans_23'];
    $aporte_fondo_vivienda = (int) $this->data['cnmd16_identif_transa']['cod_trans_24'];
    $aporte_fondo_jub = (int) $this->data['cnmd16_identif_transa']['cod_trans_25'];
    $aporte_ahorro = (int) $this->data['cnmd16_identif_transa']['cod_trans_26'];

	if($cod_vacaciones=='' && $cod_bonificacion=='' && $cod_bono_vacacional=='' && $cod_seg_soc_oblig=='' && $cod_paro_forzoso=='' && $cod_fondo_aho_oblig_vivienda=='' && $cod_fondo_jubilacion=='' && $cod_caja_ahorros=='' && $cod_prestamo_caja_ahorro=='' && $cod_cuota_sindical=='' && $cod_cuota_credito_vivienda=='' && $aporte_seguro_social='' && $aporte_paro_forzoso=='' && $aporte_fondo_vivienda=='' && $aporte_fondo_jub=='' && $aporte_ahorro==''){
		$this->set('errorMessage', 'POR FAVOR SELECCIONE LAS TRANSACCIONES DE ASIGNACI&Oacute;N Y DEDUCCI&Oacute;N');
	}else{

	if (!empty($this->data['cnmd16_identif_transa'])){

		$verifica_trans = $this->cnmd16_identif_transa->findCount($this->condicion());
		if($verifica_trans!=0){
			$sql_insert = "UPDATE cnmd16_identif_transa SET cod_asig_vaca = '$cod_vacaciones', cod_asig_bonificacion = '$cod_bonificacion', cod_asig_bono_vaca = '$cod_bono_vacacional', cod_asig_reservado1 = '$cod_asig_reservados', cod_asig_reservado2 = '$cod_asig_reservados', cod_asig_reservado3 = '$cod_asig_reservados', cod_asig_reservado4 = '$cod_asig_reservados', cod_ded_seguro_social = '$cod_seg_soc_oblig', cod_ded_paro_forzoso = '$cod_paro_forzoso', cod_ded_fondo_vivienda = '$cod_fondo_aho_oblig_vivienda', cod_ded_fondo_jub = '$cod_fondo_jubilacion', cod_ded_ahorro = '$cod_caja_ahorros', cod_ded_prestamo = '$cod_prestamo_caja_ahorro', cod_ded_sindicato = '$cod_cuota_sindical', cod_ded_cred_vivienda = '$cod_cuota_credito_vivienda', cod_ded_reservado1='$cod_ded_reservados', cod_ded_reservado2='$cod_ded_reservados', cod_ded_reservado3='$cod_ded_reservados', cod_ded_reservado4='$cod_ded_reservados', cod_ded_reservado5='$cod_ded_reservados', aporte_seguro_social='$aporte_seguro_social', aporte_paro_forzoso='$aporte_paro_forzoso', aporte_fondo_vivienda='$aporte_fondo_vivienda', aporte_fondo_jub='$aporte_fondo_jub', aporte_ahorro='$aporte_ahorro' WHERE ".$this->condicion().";";
			$accion_a = "MODIFICADOS";
			$accion_b = "MODIFICAR";
		}else{
			$sql_insert = "INSERT INTO cnmd16_identif_transa VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_vacaciones', '$cod_bonificacion', '$cod_bono_vacacional', '$cod_asig_reservados', '$cod_asig_reservados', '$cod_asig_reservados', '$cod_asig_reservados', '$cod_seg_soc_oblig', '$cod_paro_forzoso', '$cod_fondo_aho_oblig_vivienda', '$cod_fondo_jubilacion', '$cod_caja_ahorros', '$cod_prestamo_caja_ahorro', '$cod_cuota_sindical', '$cod_cuota_credito_vivienda', '$cod_ded_reservados', '$cod_ded_reservados', '$cod_ded_reservados', '$cod_ded_reservados', '$cod_ded_reservados', '$aporte_seguro_social', '$aporte_paro_forzoso', '$aporte_fondo_vivienda', '$aporte_fondo_jub', '$aporte_ahorro');";
			$accion_a = "REGISTRADOS";
			$accion_b = "REGISTRAR";
		}

		$sw = $this->cnmd16_identif_transa->execute($sql_insert);
		if($sw > 1){
			$this->set('Message_existe', 'LOS DATOS FUERON '.$accion_a.' EXITOSAMENTE');
		}else{
			$this->set('errorMessage', 'POR FAVOR SELECCIONE LAS TRANSACCIONES E INTENTE '.$accion_b.' NUEVAMENTE');
		}
	}else{
		$this->set('errorMessage', 'NO LLEGO INFORMACION COMPLETA PARA REGISTRAR');
	}

	}

 	$this->index();
	$this->render("index");

} //FIN FUNCION GUARDAR



function modificar($items_itr=null){
	$this->layout="ajax";

	/* if($items_itr!=null){
		for($itre=1;$itre<$items_itr;$itre++){
			if($itre==5 || $itre==6 || $itre==7 || $itre==8){ // RESERVADOS
			}else{
				echo "<script>document.getElementById('id_select_$itre').disabled='';</script>";
			}
		}
	}

	echo "<script>document.getElementById('id_select_1').focus();</script>";

	*/

	echo "<script>document.getElementById('guardar').disabled='';</script>";
	echo "<script>document.getElementById('regresar').disabled='';</script>";
	echo "<script>document.getElementById('modificar').disabled='disabled';</script>";
	echo "<script>document.getElementById('eliminar').disabled='disabled';</script>";

	$this->set('Message_existe', 'PUEDE MODIFICAR LOS DATOS');

 	$this->index("si");
	$this->render("index");

}



function eliminar($items_itre=null){
	$this->layout="ajax";
	$sql_eliminar="DELETE FROM cnmd16_identif_transa WHERE ".$this->condicion().";";
	$swe = $this->cnmd16_identif_transa->execute($sql_eliminar);
	if($swe>1){
		$this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS CON EXITO');
		if($items_itre!=null){
			for($itre=1;$itre<$items_itre;$itre++){
				if($itre==4 || $itre==5 || $itre==6 || $itre==7 || $itre==8 || $itre==17 || $itre==18 || $itre==19 || $itre==20 || $itre==21){ // RESERVADOS: 5 - 8 --> Asignaciones , 17 - 21 --> Deducciones
				}else{
					echo "<script>document.getElementById('transaccion_$itre').value='';
							document.getElementById('denominacion_$itre').value='';
						</script>";
				}
			}
		}
	}else{
		$this->set('errorMessage', 'NO SE PUDO ELIMINAR INTENTE NUEVAMENTE');
	}

 	$this->index();
	$this->render("index");
}

 } // fin class
?>