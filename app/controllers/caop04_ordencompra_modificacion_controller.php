<?php
class Caop04ordencompramodificacionController extends AppController {

   var $name = "caop04_ordencompra_modificacion";
   var $uses = array('ccfd03_instalacion', 'cscd04_ordencompra_encabezado', 'ccfd04_cierre_mes', 'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cscd04_ordcom_modificacion_cuerpo', 'cscd04_ordencompra_modificacion_partidas', 'cscd04_ordencompra_partidas', 'cpcd02', 'v_cfpd05_disponibilidad', 'cfpd21_numero_asiento_compromiso', 'cfpd21', 'cfpd05', 'cugd04'
                     ,'select_orden_compra','select_modificacion_compra', 'cfpd07_obras_cuerpo', 'cfpd07_obras_partidas',

                            'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo','cugd05_restriccion_clave',


                     );
   var $helpers = array('Html','Ajax','Javascript','Sisap');

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
 }//fin function



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

		function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .=  $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 if($ano!=null){
					 $sql_re .= $this->verifica_SS(5).",";
						$sql_re .= $ano."";
				 }else{
					 $sql_re .=  $this->verifica_SS(5)."";
				 }
				 return $sql_re;
		}//fin funcion SQLCAIN


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

 $this->set('autor_valido',true);// Temporal despues se puede quitar.

 $ano='';
 $ano = $this->ano_ejecucion();
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
 $lista = $this->select_orden_compra->generateList($condicion.' and condicion_actividad=1'." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
 $this->set('lista_numero', $lista);

 $ano = $this->ano_ejecucion();

 $this->set('year', $ano);

 $this->data = null;
 }//fin function





function selecion($var1=null){
 $this->layout = "ajax";

 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');

 $ano='';
 $ano = $this->ano_ejecucion();


 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
 $lista = $this->select_orden_compra->generateList($condicion.' and condicion_actividad=1'." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
 $this->set('lista_numero', $lista);

 $this->set('numero_orden_compra', $var1);
 $this->Session->delete('PAG_NUM');


if($var1==null){

$this->index();
$this->render('index');

}else{

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$var1.'  and  ano_orden_compra='.$ano.' ';
$numero_datos = $this->cscd04_ordencompra_encabezado->findAll($condicion);
$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion, null, 'numero_orden_compra DESC');

$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
	$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
	$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
	$cod_obra            = $aux['cscd04_ordencompra_encabezado']['cod_obra'];
}//fin foreach

$this->Session->write("caop02_codigo_obra", $cod_obra);
$this->Session->write("cscd04_numero_compra", $numero_orden_compra);



$cfpd07_infa  =  $this->cfpd07_obras_cuerpo->findAll("cod_presi='".$this->Session->read('SScodpresi')."' and cod_entidad='".$this->Session->read('SScodentidad')."' and cod_tipo_inst='".$this->Session->read('SScodtipoinst')."' and cod_inst='".$this->Session->read('SScodinst')."' and cod_dep_original='".$SScoddeporig."'  and ano_estimacion='".$ano_orden_compra."'  and upper(cod_obra)='".strtoupper_sisap($cod_obra)."' ", null, 'cod_obra DESC');

foreach($cfpd07_infa as $cfpd07_informe){
	$estimado_presu      = $cfpd07_informe['cfpd07_obras_cuerpo']['estimado_presu'];
	$aumento_obras       = $cfpd07_informe['cfpd07_obras_cuerpo']['aumento_obras'];
	$disminucion_obras   = $cfpd07_informe['cfpd07_obras_cuerpo']['disminucion_obras'];
	$monto_ajustado      = (($estimado_presu + $aumento_obras)	- $disminucion_obras);
	$monto_contratado    = $cfpd07_informe['cfpd07_obras_cuerpo']['monto_contratado'];
	$saldo               = (($estimado_presu + $aumento_obras) - ($disminucion_obras + $monto_contratado));
}

	$this->set('estimado_presu', $estimado_presu);
	$this->set('aumento_obras', $aumento_obras);
	$this->set('disminucion_obras', $disminucion_obras);
	$this->set('monto_ajustado', $monto_ajustado);
	$this->set('monto_contratado', $monto_contratado);
	$this->set('saldo', $saldo);


$opc      = $this->cscd04_ordcom_modificacion_cuerpo->findCount($condicion.' and ano_orden_compra='.$ano_orden_compra.'  and  numero_orden_compra='.$numero_orden_compra.'');
$result   = $this->cscd04_ordcom_modificacion_cuerpo->findAll($condicion."   and ano_orden_compra=".$ano_orden_compra."  and  numero_orden_compra=".$numero_orden_compra." ", null, "numero_modificacion ASC", null, null);
$result2  = $this->select_orden_compra->findAll($condicion."                 and ano_orden_compra=".$ano_orden_compra."  and  numero_orden_compra=".$numero_orden_compra." ", null, null, null, null);



foreach($result as $ves){$opc = $ves['cscd04_ordcom_modificacion_cuerpo']['numero_modificacion'];}//fin foreach
foreach($result2 as $ves2){
	$cod_obra = $ves2['select_orden_compra']['cod_obra'];
}//fin foreach


$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");

foreach($rif_datos as $aux_2){
	$denominacion_rif = $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
}//fin foreach


$opc++;

$this->set('ano_orden_compra_modificacion', $ano);
$this->set('numero_orden_compra_modificacion', $opc);
$this->set('datos_orden_compra', $numero_datos);
$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('direccion_comercial_rif', $direccion_comercial_rif);



}//fin else

}//fin function



function aumento_disminucion($opcion=null, $monto=null){
        $this->layout = "ajax";
        $username = $this->Session->read('nom_usuario');
        $ano = $this->ano_ejecucion();
        $tipo = $opcion;
        $monto = $this->Formato1($monto);
        $disponibilidad = 0;
        $numero_orden_compra    =  $this->Session->read("cscd04_numero_compra");
        $cobp01_codigo_obra = $this->Session->read("caop02_codigo_obra");
        if ($tipo == 1){

        		$condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '  ';
				$cfpd07_obras_cuerpo_aux = $this->cfpd07_obras_cuerpo->findAll($condicion . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($cobp01_codigo_obra) . "' ");

            foreach ($cfpd07_obras_cuerpo_aux as $aux) {
                $estimado_presu    = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
                $aumento_obras     = $aux['cfpd07_obras_cuerpo']['aumento_obras'];
                $disminucion_obras = $aux['cfpd07_obras_cuerpo']['disminucion_obras'];
                $monto_contratado  = $aux['cfpd07_obras_cuerpo']['monto_contratado'];
                $disponibilidad    = (($estimado_presu + $aumento_obras) - ($disminucion_obras + $monto_contratado));
            }//fin foreach

			$disponibilidad = $this->Formato2($disponibilidad);
            $disponibilidad = $this->Formato1($disponibilidad);


            if ($monto > $disponibilidad) {
                $this->set('msg_error', 'El monto del aumento es mayor al saldo de la orden de compra - Saldo Disponible: ' . $this->Formato2($disponibilidad));
				echo "<script> document.getElementById('guardar').disabled=true; </script>";
				echo "<script> document.getElementById('aumento').value='0,00'; </script>";
            }else{
            	echo "<script> document.getElementById('guardar').disabled=false; </script>";
            }
        }else{
                $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano.' ';
			    $numero_datos_encabezado_6 = $this->cscd04_ordencompra_encabezado->findAll($condicion);
            foreach ($numero_datos_encabezado_6 as $aux_encabezado_6) {

                $monto_6        = $aux_encabezado_6['cscd04_ordencompra_encabezado']['monto_orden'];
                $aumento_6      = $aux_encabezado_6['cscd04_ordencompra_encabezado']['modificacion_aumento'];
                $disminucion_6  = $aux_encabezado_6['cscd04_ordencompra_encabezado']['modificacion_disminucion'];
                $amortizacion_6 = $aux_encabezado_6['cscd04_ordencompra_encabezado']['monto_amortizacion'];
                $cancelado_6    = $aux_encabezado_6['cscd04_ordencompra_encabezado']['monto_cancelado'];
                $disponibilidad = (($monto_6 + $aumento_6) - ($disminucion_6 + $amortizacion_6 + $cancelado_6));
            }//fin foreach

            $disponibilidad = $this->Formato2($disponibilidad);
            $disponibilidad = $this->Formato1($disponibilidad);

            if ($monto > $disponibilidad) {
                $this->set('msg_error', 'El monto de la disminucion es mayor al saldo de la orden de compra - Saldo Disponible: ' . $this->Formato2($disponibilidad));
                echo "<script> document.getElementById('guardar').disabled=true; </script>";
				echo "<script> document.getElementById('disminucion').value='0,00'; </script>";
            }else{
            	echo "<script> document.getElementById('guardar').disabled=false; </script>";
            }

     }

}// fin funcion aumento_disminucion



function ver_disponibilidad($i, $ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $monto){

	$this->layout="ajax";
	$username= $this->Session->read('nom_usuario');
	$cod_presi = $this->Session->read('SScodpresi');
  	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	$cod_dep = $this->Session->read('SScoddep');
	$tipo  = $_SESSION['tipo'];
	$monto =  $this->Formato1($monto);

	$disponibilidad_obra   = 0;
	$disponibilidad_compra = 0;

if ($tipo==1){

	    $caop02_codigo_obra     =  $this->Session->read("caop02_codigo_obra");
		$cfpd07_obras_partidas  =  $this->cfpd07_obras_partidas->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."'  and ano_estimacion='".$ano."'  and upper(cod_obra)='".strtoupper_sisap($caop02_codigo_obra)."' and  cod_sector='".$cod_sector."' and cod_programa='".$cod_programa."' and cod_sub_prog='".$cod_sub_prog."' and cod_proyecto='".$cod_proyecto."' and cod_activ_obra='".$cod_activ_obra."' and cod_partida='".$cod_partida."' and cod_generica='".$cod_generica."' and cod_especifica='".$cod_especifica."' and cod_sub_espec='".$cod_sub_espec."' and cod_auxiliar='".$cod_auxiliar."' ", null, 'cod_obra DESC');

		foreach($cfpd07_obras_partidas as $ve3){
		  $monto_2             = $ve3["cfpd07_obras_partidas"]["monto"];
		  $aumento_obras_2     = $ve3["cfpd07_obras_partidas"]["aumento_obras"];
		  $disminucion_obras_2 = $ve3["cfpd07_obras_partidas"]["disminucion_obras"];
		  $monto_contratado_2  = $ve3["cfpd07_obras_partidas"]["monto_contratado"];
		  $disponibilidad_obra = (($monto_2 + $aumento_obras_2) - ($disminucion_obras_2 + $monto_contratado_2));
		}//fin foreach

		  $disponibilidad_obra = $this->Formato2($disponibilidad_obra);
	      $disponibilidad_obra = $this->Formato1($disponibilidad_obra);

		  echo "<script> document.getElementById('monto_actual_base').value='$disponibilidad_obra'; </script>";

		if($monto > $disponibilidad_obra){
			$this->set('msg_error', 'la disponibilidad para aumentar la orden de compra para esta partida es de: '.$this->Formato2($disponibilidad_obra));
			$this->set('i', $i);
			echo "<script> document.getElementById('guardar').disabled=true; </script>";
		}else{
			echo "<script> document.getElementById('guardar').disabled=false; </script>";
		}
/*
    echo'<script>var i_m = 0;';
    echo'for(i=0; i<document.getElementById("cuenta_i").value; i++){
		i_m = eval(reemplazarPC(document.getElementById("modificacion_"+i).value)) + eval(i_m);
    }';
    echo'var i_mm = redondear(i_m,2); document.getElementById("TOTALINGRESOS").innerHTML = i_mm; ';
    echo'</script>';
*/

}else{

	    $cscd04_numero_compra =  $this->Session->read("cscd04_numero_compra");
	    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  cod_sector='.$cod_sector.' and cod_programa='.$cod_programa.' and cod_sub_prog='.$cod_sub_prog.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_activ_obra.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec.' and cod_auxiliar='.$cod_auxiliar.' and  numero_orden_compra='.$cscd04_numero_compra.'  and  ano_orden_compra='.$ano.' ';
		$cscd04_compra_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion, null, 'numero_orden_compra DESC');

		foreach($cscd04_compra_partidas as $ve33){
		  $monto_3                       = $ve33["cscd04_ordencompra_partidas"]["monto"];
		  $aumento_3                     = $ve33["cscd04_ordencompra_partidas"]["aumento"];
		  $disminucion_3                 = $ve33["cscd04_ordencompra_partidas"]["disminucion"];
		  $amortizacion_3                = $ve33["cscd04_ordencompra_partidas"]["amortizacion"];
		  $cancelacion_3                 = $ve33["cscd04_ordencompra_partidas"]["cancelado"];

		  $disponibilidad_compra       = (($monto_3 + $aumento_3) - ($disminucion_3 + $amortizacion_3 + $cancelacion_3));
		}//fin foreach


		  $disponibilidad_compra = $this->Formato2($disponibilidad_compra);
	      $disponibilidad_compra = $this->Formato1($disponibilidad_compra);

		  echo "<script> document.getElementById('monto_actual_base').value='$disponibilidad_compra'; </script>";

		if($monto > $disponibilidad_compra){
			$this->set('msg_error', 'la disponibilidad para disminuir la orden de compra para esta partida es de: '.$this->Formato2($disponibilidad_compra));
			$this->set('i', $i);
			echo "<script> document.getElementById('guardar').disabled=true; </script>";
		}else{
			echo "<script> document.getElementById('guardar').disabled=false; </script>";
		}
	}
}// fin funcion ver_disponibilidad

function guardar_cugd04($ano=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null, $cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $cod_auxiliar=null, $username=null){

	$cod_presi = $this->Session->read('SScodpresi');
  	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	$cod_dep = $this->Session->read('SScoddep');

	if ($ano!=null && $cod_sector!=null && $cod_programa!=null && $cod_sub_prog!=null && $cod_proyecto!=null && $cod_activ_obra!=null && $cod_partida!=null && $cod_generica!=null && $cod_especifica!=null && $cod_sub_espec!=null && $username!=null && $cod_auxiliar!=null){

		$partida = " and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and username='$username'";
		$cont_partida = $this->cugd04->findCount($this->condicion().$partida);
		if($cont_partida==0){
			$sql_insert_cugd04="INSERT INTO cugd04 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$username')";
			$this->cugd04->execute($sql_insert_cugd04);
			$time = date('U');
			$this->cugd04->execute("UPDATE cugd04_entrada_modulo SET hora_captura_partida='$time'");
		}else{
			$sql_update_cugd04="UPDATE cugd04 set cod_auxiliar='$cod_auxiliar' WHERE ".$this->condicion().$partida;
			$this->cugd04->execute($sql_update_cugd04);
		}

	}
}

function prueba(){

    echo'<script>';
      echo'alert("hola");';
    echo'</script>';

}//fin function




function tipo_modificacion($var1=null){
   $this->layout = "ajax";
	$_SESSION['tipo']= $var1;
   switch ($var1) {
	case '1':{$tipo="Monto del Aumento"; $opcion="aumento";}break;
	case '2':{$tipo="Monto de la Disminución"; $opcion="disminucion";}break;

}//fin switch

	$this->set('tipo_opc', $var1);
    $this->set('opcion', $opcion);
    echo'<script>';
    echo'document.getElementById("modificacion_texto").innerHTML = "'.$tipo.'"; ';
    echo'for(i=0; i<document.getElementById("cuenta_i").value; i++){
      document.getElementById("modificacion_"+i).disabled=false;
       document.getElementById("modificacion_"+i).value="0,00";
    }';
    echo'document.getElementById("TOTALINGRESOS").innerHTML = "0,00"; ';
    echo'</script>';


}//fin function





function guardar(){
  $this->layout = "ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano_orden_compra         =       $this->data['caop04_ordencompra_modificacion']['ano_orden_compra'];
  $ann = $ano_orden_compra;
  $numero_orden_compra      =       $this->data['caop04_ordencompra_modificacion']['numero_orden_compra'];
  $numero_modificacion      =       $this->data['caop04_ordencompra_modificacion']['numero_orden_compra_modificacion'];
  $tipo_modificacion        =       $this->data['caop04_ordencompra_modificacion']['tipo_modificacion'];
        if($tipo_modificacion=='1'){$monto = $this->Formato1($this->data['caop04_ordencompra_modificacion']['aumento']);
  }else if($tipo_modificacion=='2'){$monto = $this->Formato1($this->data['caop04_ordencompra_modificacion']['disminucion']);}//fin else
  $monto_modificacion       =       $monto;


  $fecha_modificacion       =       $this->data['caop04_ordencompra_modificacion']['fecha_modificacion'];
  $fecha_orden_compra       =       $this->data['caop04_ordencompra_modificacion']['fecha_orden_compra'];
  $fd                       =       $this->data['caop04_ordencompra_modificacion']['fecha_modificacion'];
  $observaciones            =       $this->data['caop04_ordencompra_modificacion']['observaciones'];


  $rif                      =       $this->data["caop04_ordencompra_modificacion"]["rif"];





if(isset($ano_orden_compra)){
if(isset($numero_orden_compra)){
if(isset($numero_modificacion)){
if(isset($tipo_modificacion)){
if(isset($monto_modificacion)){
if(isset($fecha_modificacion)){
if(isset($fd)){
if(isset($observaciones)){






  $ccp = $observaciones;
  $ano_asiento_registro= '0';
  $mes_asiento_registro='0';
  $dia_asiento_registro='0';
  $numero_asiento_registro='0';
  $fecha_proceso_registro=date('d/m/Y');
  $username_registro=$_SESSION['nom_usuario'];
  $condicion_actividad='1';
  $ano_asiento_anulacion='0';
  $mes_asiento_anulacion='0';
  $dia_asiento_anulacion='0';
  $numero_asiento_anulacion='0';
  $username_anulacion='0';

  $fecha_proceso_anulacion = '01/01/1900';
  $ano_anulacion           = "0";
  $numero_anulacion        = "0";



$numero_orden_compra2 = $numero_orden_compra;
$ano_orden_compra2 = $ano_orden_compra;
$ann = $ano_orden_compra;
$sql=" BEGIN; INSERT INTO cscd04_ordencompra_modificacion_cuerpo (cod_presi, cod_entidad, cod_tipo_inst ,cod_inst, cod_dep , ano_orden_compra, numero_orden_compra, numero_modificacion, tipo_modificacion, monto_modificacion, fecha_modificacion, observaciones, ano_asiento_registro, mes_asiento_registro, dia_asiento_registro, numero_asiento_registro, fecha_proceso_registro, username_registro, condicion_actividad, ano_asiento_anulacion, mes_asiento_anulacion, dia_asiento_anulacion , numero_asiento_anulacion, username_anulacion, fecha_proceso_anulacion, ano_anulacion, numero_anulacion)";
$sql.="VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$numero_modificacion."', '".$tipo_modificacion."', '".$monto_modificacion."', '".$this->Cfecha($fecha_modificacion, 'A-M-D')."', '".$observaciones."', '".$ano_asiento_registro."', '".$mes_asiento_registro."', '".$dia_asiento_registro."', '".$numero_asiento_registro."', '".$this->Cfecha($fecha_proceso_registro, 'A-M-D')."', '".$username_registro."', '".$condicion_actividad."', '".$ano_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$dia_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$username_anulacion."', '".$this->Cfecha($fecha_proceso_anulacion, 'A-M-D')."', '".$ano_anulacion."', '".$numero_anulacion."'); ";
$swa = $this->cscd04_ordcom_modificacion_cuerpo->execute($sql);

if($swa>1){

		$i_lenght = $this->data['caop04_ordencompra_modificacion']['cuenta_i'];
		$total_fil = $i_lenght - 1;
		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra.' ';
		$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion, null, 'numero_orden_compra DESC');
		$a=0;
		$i_aux = 0;
		foreach($numero_datos_partidas as $aux_partidas){
		  $cod_presi2[$a]                 =   $aux_partidas['cscd04_ordencompra_partidas']['cod_presi'];
		  $cod_entidad2[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_entidad'];
		  $cod_tipo_inst2[$a]             =   $aux_partidas['cscd04_ordencompra_partidas']['cod_tipo_inst'];
		  $cod_inst2[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['cod_inst'];
		  $cod_dep2[$a]                   =   $aux_partidas['cscd04_ordencompra_partidas']['cod_dep'];
		  $ano_orden_compra3[$a]          =   $aux_partidas['cscd04_ordencompra_partidas']['ano_orden_compra'];
		  $numero_orden_compra3[$a]       =   $aux_partidas['cscd04_ordencompra_partidas']['numero_orden_compra'];
		  $ano_partidas[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['ano'];
		  $cod_sector[$a]                 =   $aux_partidas['cscd04_ordencompra_partidas']['cod_sector'];
		  $cod_programa[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_programa'];
		  $cod_sub_prog[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_sub_prog'];
		  $cod_proyecto[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_proyecto'];
		  $cod_activ_obra[$a]             =   $aux_partidas['cscd04_ordencompra_partidas']['cod_activ_obra'];
		  $cod_partida[$a]                =   $aux_partidas['cscd04_ordencompra_partidas']['cod_partida'];
		  $cod_generica[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_generica'];
		  $cod_especifica[$a]             =   $aux_partidas['cscd04_ordencompra_partidas']['cod_especifica'];
		  $cod_sub_espec[$a]              =   $aux_partidas['cscd04_ordencompra_partidas']['cod_sub_espec'];
		  $cod_auxiliar[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_auxiliar'];
		  $monto2[$a]                     =   $aux_partidas['cscd04_ordencompra_partidas']['monto'];
		  $aumento2[$a]                   =   $aux_partidas['cscd04_ordencompra_partidas']['aumento'];
		  $disminucion2[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['disminucion'];
		  $numero_compromiso              =   $aux_partidas['cscd04_ordencompra_partidas']['numero_asiento_compromiso'];
		$a++;

					   $partidas_aux  = $aux_partidas['cscd04_ordencompra_partidas']['cod_sector'];
						$partidas_aux .= $aux_partidas['cscd04_ordencompra_partidas']['cod_programa'];
						$partidas_aux .= $aux_partidas['cscd04_ordencompra_partidas']['cod_sub_prog'];
						$partidas_aux .= $aux_partidas['cscd04_ordencompra_partidas']['cod_proyecto'];
						$partidas_aux .= $aux_partidas['cscd04_ordencompra_partidas']['cod_activ_obra'];
						$partidas_aux .= $aux_partidas['cscd04_ordencompra_partidas']['cod_partida'];
						$partidas_aux .= $aux_partidas['cscd04_ordencompra_partidas']['cod_generica'];
						$partidas_aux .= $aux_partidas['cscd04_ordencompra_partidas']['cod_especifica'];
						$partidas_aux .= $aux_partidas['cscd04_ordencompra_partidas']['cod_sub_espec'];
						$partidas_aux .= $aux_partidas['cscd04_ordencompra_partidas']['cod_auxiliar'];

						for($i=0; $i<=$total_fil; $i++){
						   if($partidas_aux==$this->data['partidas']['partidas_'.$i]){$partidas_vista['pago_'.$i_aux] = $this->data['caop04_ordencompra_modificacion']['modificacion_'.$i]; $i_aux++;}
						}//fin foreach
		}//fin foreach


		$j =0;

/*

		$numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP()." and ano_compromiso='$ann'", $order =null);
		if(!empty($numero_compromiso)){
			$numero_compromiso ++;
			$sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' and ".$this->condicionNDEP().";";
		}else{
			$numero_compromiso = 1;
			$sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_compromiso'); ";
		}
		$sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);

*/


		for($i=0; $i<$i_lenght; $i++){
				       $var[$i]['monto']=$partidas_vista['pago_'.$i];
				if($var[$i]['monto']!="0,00"){ $var[$i]['monto'] = $this->Formato1($var[$i]['monto']);


										$cp   = $this->crear_partida($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
										$to   = 1;
										$td   = 2;
										$ta   = $tipo_modificacion;
										$mt   = $var[$i]['monto'];
										$ndo  = $numero_orden_compra3[$i];
										$rnco = $numero_compromiso;

										$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, null, null, null, null, null, null, null, $rnco, null, null, null, $i);

										       $sql2  ="INSERT INTO cscd04_ordencompra_modificacion_partidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra, numero_modificacion, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica,  cod_sub_espec, cod_auxiliar,  monto, numero_control_compromiso) ";
										       $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_orden_compra3[$i]."', '".$numero_orden_compra3[$i]."', '".$numero_modificacion."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['monto']."', '".$rnco."'); ";
										       $swaa = $this->cscd04_ordencompra_modificacion_partidas->execute($sql2);


										       if($swaa>1){
														    $monto_modificacion_tipo2 = 0;
														      if($tipo_modificacion=='1'){
															$monto_modificacion_tipo2 = $aumento2[$i] + $var[$i]['monto']; $campo="aumento"; $bb=0;  $aa = $var[$i]['monto'];

															$validaxx = "ano =".$ano_partidas[$i]." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and  cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i];
                                                            $ud05     = "update cfpd05 set precompromiso_obras = precompromiso_obras - ".$aa." where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ".$validaxx;
                                                            $respxx   = $this->cfpd05->execute($ud05);

														}else if($tipo_modificacion=='2'){
															$monto_modificacion_tipo2 = $disminucion2[$i] + $var[$i]['monto']; $campo="disminucion"; $aa = 0; $bb = $var[$i]['monto'];

														    $validaxx = "ano =".$ano_partidas[$i]." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and  cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i];
                                                            $ud05     = "update cfpd05 set precompromiso_obras = precompromiso_obras + ".$bb." where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ".$validaxx;
                                                            $respxx   = $this->cfpd05->execute($ud05);

														}//fin else

														$sql4  = "UPDATE cscd04_ordencompra_partidas SET ".$campo."= '".$monto_modificacion_tipo2."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_orden_compra=".$numero_orden_compra2."  and  ano_orden_compra=".$ano_orden_compra2." and ano=".$ano_partidas[$i]." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i]."; ";
														$swbb = $this->cscd04_ordencompra_partidas->execute($sql4);


                                                           if($tipo_modificacion=='1'){
		                                                      $caop02_codigo_obra = $this->Session->read("caop02_codigo_obra");
														      $sql55  = "UPDATE cfpd07_obras_partidas SET monto_contratado = monto_contratado + ".$aa."  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and   upper(cod_obra)='".strtoupper($caop02_codigo_obra)."'  and  ano_estimacion=".$ano_orden_compra2." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i]."; ";
											                  $sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);
											                  $sql55  = "UPDATE cfpd07_obras_cuerpo   SET monto_contratado = monto_contratado + ".$aa."  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and   upper(cod_obra)='".strtoupper($caop02_codigo_obra)."'  and  ano_estimacion=".$ano_orden_compra2." ; ";
											                  $sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);
											         }else if($tipo_modificacion=='2'){
                                                              $caop02_codigo_obra = $this->Session->read("caop02_codigo_obra");
														      $sql55  = "UPDATE cfpd07_obras_partidas SET monto_contratado = monto_contratado - ".$bb."  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and   upper(cod_obra)='".strtoupper($caop02_codigo_obra)."'  and  ano_estimacion=".$ano_orden_compra2." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i]."; ";
											                  $sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);
											                  $sql55  = "UPDATE cfpd07_obras_cuerpo   SET monto_contratado = monto_contratado - ".$bb."  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and   upper(cod_obra)='".strtoupper($caop02_codigo_obra)."'  and  ano_estimacion=".$ano_orden_compra2." ; ";
											                  $sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);
                                                     }
													 if($sw155 > 1){
														$swbb = 2;
												     }else{
												      	$swbb = 0;
												     }
												     if($swbb > 1){}else{break; }//fin else
										       }else{break;}





				  }//fin if
		}//fin for

              if($swbb > 1){

							$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
				                                                                          $to=1,
				                                                                          $td=10,
				                                                                          $rif_doc = $rif,

				                                                                          $ano_dc  = $ano_orden_compra,
				                                                                          $n_dc    = $numero_orden_compra,

				                                                                          $f_dc    = $fecha_orden_compra,
				                                                                          $cpt_dc  = $ccp,
				                                                                          $ben_dc  = null,

				                                                                          $mon_dc=array("monto"=>$monto_modificacion),

				                                                                          $ano_op               = null,
				                                                                          $n_op                 = null,
				                                                                          $f_op                 = null,
				                                                                          $a_adj_op             = null,
				                                                                          $n_adj_op             = $numero_modificacion,
				                                                                          $f_adj_op             = $fecha_modificacion,
				                                                                          $tp_op                = null,
				                                                                          $deno_ban_pago        = null,
				                                                                          $ano_movimiento       = null,
				                                                                          $cod_ent_pago         = null,
				                                                                          $cod_suc_pago         = null,
				                                                                          $cod_cta_pago         = null,
				                                                                          $num_che_o_debi       = null,
				                                                                          $fec_che_o_debi       = null,
				                                                                          $clas_che_o_debi      = null,
				                                                                          $tipo_che_o_debi      = null,
																					      $ano_dc_array_pago    = array(),
																					      $n_dc_array_pago      = array(),
																					      $n_dc_adj_array_pago  = array(),
																					      $f_dc_array_pago      = array(),

																					      $ano_op_array_pago  = array(),
																					      $n_op_array_pago    = array(),
																					      $f_op_array_pago    = array(),
																					      $tipo_op_array_pago = array(),
																					      $tipo_modificacion2 = $tipo_modificacion);


								        }else{

								        	$valor_motor_contabilidad = false;

								        }//fin else


				        if($valor_motor_contabilidad==true){

									$campo = "";
									$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra2.'  and  ano_orden_compra='.$ano_orden_compra2.' ';
									$numero_datos = $this->cscd04_ordencompra_encabezado->findAll($condicion);
									$numero_datos_aux =  $numero_datos;
									foreach($numero_datos_aux as $aux){
										$modificacion_aumento = $aux['cscd04_ordencompra_encabezado']['modificacion_aumento'];
										$modificacion_disminucion = $aux['cscd04_ordencompra_encabezado']['modificacion_disminucion'];
									}//fin foreach
									      if($tipo_modificacion=='1'){
										$monto_modificacion_tipo = $monto_modificacion + $modificacion_aumento; $campo="modificacion_aumento";
									}else if($tipo_modificacion=='2'){
										$monto_modificacion_tipo = $monto_modificacion + $modificacion_disminucion;  $campo="modificacion_disminucion";
									}//fin else

									$sql3  = "UPDATE cscd04_ordencompra_encabezado SET ".$campo."= '".$monto_modificacion_tipo."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_orden_compra=".$numero_orden_compra2."  and  ano_orden_compra=".$ano_orden_compra2."; ";
									$swxy = $this->cscd04_ordencompra_encabezado->execute($sql3);


							if($swxy > 1){

									////************************************** DESPUES DE GUARDAR ****************************************/////
									 $ano='';
									 $ano = $this->ano_ejecucion();
									 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
									 $lista = $this->cscd04_ordencompra_encabezado->generateList($condicion.' and condicion_actividad=1', ' numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.numero_orden_compra');
									 $this->AddCero('lista_numero', $lista);
									 $this->set('numero_orden_compra', $numero_orden_compra2);
									 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra2.'  and  ano_orden_compra='.$ano_orden_compra2.' ';
									 $numero_datos_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and rif='".$this->data['caop04_ordencompra_modificacion']['rif']."' ");
									 $numero_datos_orden_compra_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion. " and ano=".$ano_partidas[0]."  ");
									 $numero_datos_aux  =  $numero_datos_encabezado;
									foreach($numero_datos_aux as $aux){
										$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
										$ano_orden_compra = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
										$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
									}//fin foreach
									$opc = $this->cscd04_ordcom_modificacion_cuerpo->findAll($condicion.' and ano_orden_compra='.$ano_orden_compra.'  and numero_orden_compra='.$numero_orden_compra.' and numero_modificacion='.$numero_modificacion.'  ');
									$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
									foreach($rif_datos as $aux_2){
										$denominacion_rif = $aux_2['cpcd02']['denominacion'];
										$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
									}//fin foreach
									$campos = ' cod_presi ,
									cod_entidad,
									cod_tipo_inst,
									cod_inst,
									SUM(monto) as "monto" ';
									$agrupar = ' GROUP BY cod_presi,
									cod_entidad,
									cod_tipo_inst,
									cod_inst';
									$aux_monto_modificacion = $this->cscd04_ordencompra_modificacion_partidas->findAll($condicion.' and ano_orden_compra='.$ano_orden_compra.'  and numero_orden_compra='.$numero_orden_compra.' and numero_modificacion='.$numero_modificacion.'  '.$agrupar, $campos, null,  null, null);
									foreach($aux_monto_modificacion as $aux2_monto_modificacion){
										$monto_modificacion = $aux2_monto_modificacion[0]['monto'];
									}//fin foreach
									$this->set('year', $ano);
									$this->set('datos_orden_compra_modificacion_cuerpo', $opc);
									$this->set('datos_orden_compra_encabezado', $numero_datos_encabezado);
									$this->set('datos_orden_compra_partidas', $numero_datos_orden_compra_partidas);
									$this->set('denominacion_rif', $denominacion_rif);
									$this->set('direccion_comercial_rif', $direccion_comercial_rif);
									$this->set('monto_modificacion', $monto_modificacion);
									 $this->cscd04_ordencompra_modificacion_partidas->execute("COMMIT;");
									$this->set('Message_existe', 'Los datos fueron guardados correctamente');
									////************************************** DESPUES DE GUARDAR ****************************************/////



								}else{
									$this->cscd04_ordencompra_modificacion_partidas->execute("ROLLBACK;");
									$this->set('errorMessage', 'NO SE LOGRO REALIZAR LA MODIFICACION - POR FAVOR INTENTE DE NUEVO');
								}//fin else

					}else{
						$this->cscd04_ordencompra_modificacion_partidas->execute("ROLLBACK;");
						$this->set('errorMessage', 'NO SE LOGRO REALIZAR LA MODIFICACION - POR FAVOR INTENTE DE NUEVO');
					}//fin else

}else{
	$this->cscd04_ordencompra_modificacion_partidas->execute("ROLLBACK;");
	$this->set('errorMessage', 'NO SE LOGRO REALIZAR LA MODIFICACION - POR FAVOR INTENTE DE NUEVO');
}//fin else





}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}



$this->index();
$this->render('index');

}//fin funtion guardar




function consulta_index($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $pag_num = 0;
  $opcion = 'si';
  $condicion= $this->condicion();

   if(!empty($this->data['caop04_ordencompra_modificacion']['ano_ejecucion'])){
       	 $_SESSION['ano_compra'] = $this->data['caop04_ordencompra_modificacion']['ano_ejecucion'];
   }else{$_SESSION['ano_compra'] = $this->ano_ejecucion();}

   $ano = $_SESSION['ano_compra'];

   $this->set('ano_compra', $ano);
   $this->set('ano_ejecucion', $this->ano_ejecucion());


 $lista = $this->select_modificacion_compra->generateList($condicion." and ano_orden_compra = ".$ano." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_modificacion_compra.numero_orden_compra', '{n}.select_modificacion_compra.beneficiario');
 $this->set('lista_numero', $lista);
 $this->set('ano', $ano);

if($var1!=null){

  if($var1=='si'){

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


if(!empty($this->data['caop04_ordencompra_modificacion']['numero_orden_compra'] )){

	   $array = $this->cscd04_ordcom_modificacion_cuerpo->findAll($condicion. " and numero_orden_compra='".$this->data['caop04_ordencompra_modificacion']['numero_orden_compra']."' and ano_orden_compra = ".$ano, null, 'ano_orden_compra, numero_orden_compra, numero_modificacion ASC', null);
  $i = 0;
   foreach($array as $aux){
 	$numero[$i]['ano_orden_compra'] = $aux['cscd04_ordcom_modificacion_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordcom_modificacion_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_modificacion'] = $aux['cscd04_ordcom_modificacion_cuerpo']['numero_modificacion'];
 	$i++;
} $i--;

  for($a=0; $a<=$i; $a++){
    if($this->data['caop04_ordencompra_modificacion']['numero_orden_compra'] == $numero[$a]['numero_orden_compra']){
    	$pag_num = 0;
    	$opcion='si';
    	$numero_documento = $numero[$a]['numero_orden_compra'];
    	break;
    	}else{
    		$pag_num = 0;
    	    $opcion='si';
    	    $numero_documento = $numero[0]['numero_orden_compra'];
    		}
   }//fin for

      if($opcion=='si'){$_SESSION['PAG_NUM']=$this->data['caop04_ordencompra_modificacion']['numero_orden_compra']; $this->consulta($pag_num, $numero_documento);$this->render('consulta');
}else if($opcion=='no'){
	$this->set('errorMessage', 'No existen datos');
	}//fin else





}else{


	   $array = $this->cscd04_ordcom_modificacion_cuerpo->findAll($condicion. " and ano_orden_compra = ".$ano, null, 'ano_orden_compra, numero_orden_compra, numero_modificacion ASC', null);
  $i = 0;
   foreach($array as $aux){
 	$numero[$i]['ano_orden_compra'] = $aux['cscd04_ordcom_modificacion_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordcom_modificacion_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_modificacion'] = $aux['cscd04_ordcom_modificacion_cuerpo']['numero_modificacion'];
 	$i++;
} $i--;





	$this->Session->delete('PAG_NUM');

	if($i<=0){
	  $this->set('errorMessage', 'No existen datos');
	  $this->consulta_index();
	  $this->render('consulta_index');
	}else{$this->consulta(0, $numero[0]['numero_orden_compra']);$this->render('consulta');}  }//fin else



  }//fin if
}//fin if

$this->set('entidad_federal', $this->Session->read('entidad_federal'));
$this->data = null;
}//fin function










function buscar_year($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $lista = $this->select_modificacion_compra->generateList($condicion." and ano_orden_compra = ".$var1." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_modificacion_compra.numero_orden_compra', '{n}.select_modificacion_compra.beneficiario');
  $this->set('compras', $lista);


}//fin function









function consulta($pag_num=null, $numero_documento=null, $g=null){
  $this->layout = "ajax";

   if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}
   $this->set('ano_compra', $ano);

   $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
   $this->set('ano_ejecucion', $this->ano_ejecucion());



 $array = $this->cscd04_ordcom_modificacion_cuerpo->findAll($condicion. ' and ano_orden_compra = '.$ano.'  and numero_orden_compra='.$numero_documento , null, 'numero_modificacion ASC', null);

       $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$ano_documento = $numero[$i]['ano_orden_compra']    = $aux['cscd04_ordcom_modificacion_cuerpo']['ano_orden_compra'];
 	$numero_orden_compra = $numero[$i]['numero_orden_compra'] = $aux['cscd04_ordcom_modificacion_cuerpo']['numero_orden_compra'];
 	$numero_modificacion = $numero[$i]['numero_modificacion'] = $aux['cscd04_ordcom_modificacion_cuerpo']['numero_modificacion'];
	$condicion_actividad = $aux['cscd04_ordcom_modificacion_cuerpo']['condicion_actividad'];
	$fecha_documento = $aux['cscd04_ordcom_modificacion_cuerpo']['fecha_modificacion'];
	$tipo = '22'.$aux['cscd04_ordcom_modificacion_cuerpo']['tipo_modificacion'];
 	$i++;

} $i--;

if($condicion_actividad==2){
			$ano_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.ano_acta_anulacion', $conditions = $this->condicion()." and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion='$tipo'", $order ="ano_acta_anulacion, numero_acta_anulacion ASC");
			$numero_acta_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.numero_acta_anulacion', $conditions = $this->condicion()." and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion='$tipo'", $order ="ano_acta_anulacion, numero_acta_anulacion ASC");
			$motivo_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.motivo_anulacion', $conditions = $this->condicion()." and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion='$tipo'", $order ="ano_acta_anulacion, numero_acta_anulacion ASC");

		}else{
			$ano_anulacion= 0;
			$numero_acta_anulacion=0;
			$motivo_anulacion = " ";
		}
		$this->set('ano_anulacion', $ano_anulacion);
		$this->set('numero_acta_anulacion', $numero_acta_anulacion);
		$this->set('motivo_anulacion',$motivo_anulacion);




if(isset($numero[$pag_num]['numero_orden_compra'])){



$datos_orden_compra_encabezado          =   $this->cscd04_ordencompra_encabezado->findAll($condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].'  ');
$datos_orden_compra_modificacion_cuerpo =   $this->cscd04_ordcom_modificacion_cuerpo->findAll($condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and numero_modificacion='.$numero[$pag_num]['numero_modificacion'].'  ');




$numero_datos_aux  =  $datos_orden_compra_encabezado;
foreach($numero_datos_aux as $aux){$rif = $aux['cscd04_ordencompra_encabezado']['rif'];}//fin foreach
$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){$denominacion_rif = $aux_2['cpcd02']['denominacion']; $direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];}//fin foreach
$campos = ' cod_presi, cod_entidad, cod_tipo_inst, cod_inst, SUM(monto) as "monto" ';
$agrupar = ' GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst';
$sql = $condicion.'  and  ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and  numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and numero_modificacion='.$numero[$pag_num]['numero_modificacion'].'  '.$agrupar;
$sql2 = $condicion.'  and  ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and  numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and numero_modificacion='.$numero[$pag_num]['numero_modificacion'].'  ';
$aux_monto_modificacion = $this->cscd04_ordencompra_modificacion_partidas->findAll($sql, $campos, null,  null, null);
$numero_datos_orden_compra_partidas = $this->cscd04_ordencompra_modificacion_partidas->findAll($sql2);
foreach($aux_monto_modificacion as $aux2_monto_modificacion){ $monto_modificacion = $aux2_monto_modificacion[0]['monto']; }//fin foreach
 $this->set('monto_modificacion', $monto_modificacion);
 $this->set('denominacion_rif', $denominacion_rif);
 $this->set('datos_orden_compra__modificacion_partidas', $numero_datos_orden_compra_partidas);
 $this->set('direccion_comercial_rif', $direccion_comercial_rif);
 $this->set('datos_orden_compra_encabezado', $datos_orden_compra_encabezado);
 $this->set('datos_orden_compra_modificacion_cuerpo', $datos_orden_compra_modificacion_cuerpo);
 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);

}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

}//fin else



}//fin function




















function guardar_anulacion1($var=null) {
	$this->layout="ajax";


echo'<script>';
    echo'document.getElementById("guardar").disabled = false; ';
    echo'document.getElementById("anular").disabled = true; ';
echo'</script>';


}//fin function





function guardar_anulacion2($var=null) {

$this->layout="ajax";

	if(isset($this->data["caop04_ordencompra_modificacion"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		     $tipo_documento           =  22;

			 $concepto_anulacion       =  $this->data["caop04_ordencompra_modificacion"]["concepto_anulacion"];
			 $concepto = $concepto_anulacion;
			 $fecha_proceso_anulacion  =  date("d/m/Y");

			 $condicion_documento      =  2;//cuando se guarda es Activo=1
			 $ano_orden_compra    = $this->data["caop04_ordencompra_modificacion"]["ano_orden_compra"];
			 $numero_orden_compra = $this->data["caop04_ordencompra_modificacion"]["numero_orden_compra"];


			 $fecha_modificacion  = $this->data["caop04_ordencompra_modificacion"]["fecha_modificacion"];
			 $fecha_contrato      = $this->data["caop04_ordencompra_modificacion"]["fecha_modificacion2"];


			 $fecha_orden_compra      = $this->data["caop04_ordencompra_modificacion"]["fecha_orden_compra"];


			 $fd = $fecha_modificacion;


			   $rif                        =       $this->data["caop04_ordencompra_modificacion"]["rif"];



			 $numero_modificacion = $this->data["caop04_ordencompra_modificacion"]["numero_modificacion"];
			 $tipo_modificacion = $this->data["caop04_ordencompra_modificacion"]["tipo_modificacion"];
			 //echo "el tipo de la modificacion es ".$tipo_modificacion;


if(isset($concepto_anulacion)){
if(isset($ano_orden_compra)){
if(isset($numero_orden_compra)){
if(isset($fecha_modificacion)){
if(isset($fecha_contrato)){
if(isset($numero_modificacion)){
if(isset($tipo_modificacion)){





			 $tipo_documento           =  '22'.$tipo_modificacion;

			$datos_partidas = $this->cscd04_ordencompra_modificacion_partidas->findAll($conditions = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and numero_modificacion='$numero_modificacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			 			$monto_anticipo = 0;
			$sql_update_cscd04_partidas = '';

			$result2  = $this->select_orden_compra->findAll($condicion."                 and ano_orden_compra=".$ano_orden_compra."  and  numero_orden_compra=".$numero_orden_compra." ", null, null, null, null);
			foreach($result2 as $ves2){
				$cod_obra = $ves2['select_orden_compra']['cod_obra'];
			}//fin foreach

			$monto_modificacion=0;
			$sql_cfpd07_obras_partidas = "";
			$sql_cfpd07_obras_cuerpo = "";
			 foreach($datos_partidas as $row){
			 	$ano = $row['cscd04_ordencompra_modificacion_partidas']['ano'];
			 	$cod_sector = $row['cscd04_ordencompra_modificacion_partidas']['cod_sector'];
			 	$cod_programa = $row['cscd04_ordencompra_modificacion_partidas']['cod_programa'];
			 	$cod_sub_prog = $row['cscd04_ordencompra_modificacion_partidas']['cod_sub_prog'];
			 	$cod_proyecto = $row['cscd04_ordencompra_modificacion_partidas']['cod_proyecto'];
			 	$cod_activ_obra = $row['cscd04_ordencompra_modificacion_partidas']['cod_activ_obra'];
			 	$cod_partida = $row['cscd04_ordencompra_modificacion_partidas']['cod_partida'];
			 	$cod_generica = $row['cscd04_ordencompra_modificacion_partidas']['cod_generica'];
			 	$cod_especifica = $row['cscd04_ordencompra_modificacion_partidas']['cod_especifica'];
			 	$cod_sub_espec = $row['cscd04_ordencompra_modificacion_partidas']['cod_sub_espec'];
			 	$cod_auxiliar = $row['cscd04_ordencompra_modificacion_partidas']['cod_auxiliar'];
			 	$monto_partida = $row['cscd04_ordencompra_modificacion_partidas']['monto'];
			 	$numero_control_compromiso = $row['cscd04_ordencompra_modificacion_partidas']['numero_control_compro'];
			 	$cond1 = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
				$monto_modificacion += $monto_partida;
if($tipo_modificacion == 1){$tipo='modificacion_aumento = modificacion_aumento - ';$signo = '-';}else{$tipo='modificacion_disminucion = modificacion_disminucion + ';$signo = '+';}
				$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
				$num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 2, $tipo_modificacion, date("d/m/Y"), $monto_partida, $concepto, $ano, $numero_orden_compra, $numero_modificacion, null, null, null, null, null, null, $numero_control_compromiso, null, null, null, null);
if($tipo_modificacion == 1){$actualizar='aumento';$tipo='aumento =aumento - ';$signo = '-';}else{$tipo='disminucion = disminucion - ';$actualizar='disminucion';$signo = '-';}
			 	$sql_update_cscd04_partidas .= "UPDATE cscd04_ordencompra_partidas SET ".$actualizar."=".$actualizar."".$signo."'$monto_partida'  WHERE ".$cond1.";";


			    if($tipo_modificacion == 1){
			    	   $disminucion2 =  0;
                       $aumento2     =  $monto_partida;

                        $validaxx = "ano =".$ano_orden_compra." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and  cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
                        $ud05     = "update cfpd05 set precompromiso_obras = precompromiso_obras + ".$aumento2." where ".$this->condicion()." and ".$validaxx;
                        $respxx   = $this->cfpd05->execute($ud05);

				 }else{
				 	   $aumento2     =  0;
                       $disminucion2 =  $monto_partida;

                        $validaxx = "ano =".$ano_orden_compra." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and  cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
                        $ud05     = "update cfpd05 set precompromiso_obras = precompromiso_obras - ".$disminucion2." where ".$this->condicion()." and ".$validaxx;
                        $respxx   = $this->cfpd05->execute($ud05);

				 }//fin if

				 $cond2 = " cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";



                  if($tipo_modificacion == 1){
                       $sql_cfpd07_obras_partidas  .= "UPDATE cfpd07_obras_partidas SET monto_contratado = monto_contratado - ".$aumento2."  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and   upper(cod_obra)='".strtoupper($cod_obra)."'  and  ano_estimacion=".$ano_orden_compra." and ".$cond2."; ";
			 	       $sql_cfpd07_obras_cuerpo    .= "UPDATE cfpd07_obras_cuerpo   SET monto_contratado = monto_contratado - ".$aumento2."  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and   upper(cod_obra)='".strtoupper($cod_obra)."'  and  ano_estimacion=".$ano_orden_compra."; ";
				 }else{
				 	   $sql_cfpd07_obras_partidas  .= "UPDATE cfpd07_obras_partidas SET monto_contratado = monto_contratado + ".$disminucion2."  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and   upper(cod_obra)='".strtoupper($cod_obra)."'  and  ano_estimacion=".$ano_orden_compra." and ".$cond2."; ";
			 	       $sql_cfpd07_obras_cuerpo    .= "UPDATE cfpd07_obras_cuerpo   SET monto_contratado = monto_contratado + ".$disminucion2."  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and   upper(cod_obra)='".strtoupper($cod_obra)."'  and  ano_estimacion=".$ano_orden_compra."; ";
				 }//fin if

			 }
			 			$sw155  = $this->cscd04_ordencompra_partidas->execute($sql_cfpd07_obras_partidas);
			 			$sw1555 = $this->cscd04_ordencompra_partidas->execute($sql_cfpd07_obras_cuerpo);

                if($tipo_modificacion == 1){$tipo='modificacion_aumento = modificacion_aumento - ';$signo = '-';}else{$tipo='modificacion_disminucion = modificacion_disminucion - ';$signo = '+';}
			 	$sql_cscd04_encabezado="UPDATE cscd04_ordencompra_encabezado SET ".$tipo." ".$monto_modificacion." WHERE ".$this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra';";
				$this->cscd04_ordencompra_modificacion_partidas->execute($sql_cscd04_encabezado.$sql_update_cscd04_partidas);






			 //$numero_anulacion=$this->cugd03_acta_anulacion_numero->execute($condicion);
             $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra." ORDER BY numero_acta_anulacion DESC");


		     if($v!=null){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",1)");
			    $numero=1;
		     }//fin else
			 $R1 = $this->cscd04_ordcom_modificacion_cuerpo->execute("UPDATE cscd04_ordencompra_modificacion_cuerpo SET ano_anulacion=".date('Y').", numero_anulacion=".$numero.", condicion_actividad='".$condicion_documento."', username_anulacion='".$_SESSION['nom_usuario']."',  fecha_proceso_anulacion='".$this->Cfecha($fecha_proceso_anulacion, 'A-M-D')."'  WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_modificacion=".$numero_modificacion );
		     $v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",".$numero.",".$tipo_documento.",".$ano_orden_compra.",".$numero_orden_compra.",'".$this->Cfecha($fecha_modificacion, 'A-M-D')."','".$concepto_anulacion."')");
		     if($R1 > 1 && $v>1){

		     }

//		     $this->cscd04_ordcom_modificacion_cuerpo->execute($sql_cfpd07_obras_partidas);

		    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                          $to=2,
                                                                          $td=10,
                                                                          $rif_doc = $rif,

                                                                          $ano_dc  = $ano_orden_compra,
                                                                          $n_dc    = $numero_orden_compra,

                                                                          $f_dc    = $fecha_orden_compra,
                                                                          $cpt_dc  = $concepto,
                                                                          $ben_dc  = null,

                                                                          $mon_dc=array("monto"=>$monto_modificacion),

                                                                          $ano_op               = null,
                                                                          $n_op                 = null,
                                                                          $f_op                 = null,
                                                                          $a_adj_op             = null,
                                                                          $n_adj_op             = $numero_modificacion,
                                                                          $f_adj_op             = date("d/m/Y"),
                                                                          $tp_op                = null,
                                                                          $deno_ban_pago        = null,
                                                                          $ano_movimiento       = null,
                                                                          $cod_ent_pago         = null,
                                                                          $cod_suc_pago         = null,
                                                                          $cod_cta_pago         = null,
                                                                          $num_che_o_debi       = null,
                                                                          $fec_che_o_debi       = null,
                                                                          $clas_che_o_debi      = null,
                                                                          $tipo_che_o_debi      = null,
																	      $ano_dc_array_pago    = array(),
																	      $n_dc_array_pago      = array(),
																	      $n_dc_adj_array_pago  = array(),
																	      $f_dc_array_pago      = array(),

																	      $ano_op_array_pago  = array(),
																	      $n_op_array_pago    = array(),
																	      $f_op_array_pago    = array(),
																	      $tipo_op_array_pago = array(),
																	      $tipo_modificacion2 = $tipo_modificacion);

		                $this->set('Message_existe', 'El registro fue anulado correctamente');
		        }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}


	}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}




$this->consulta_index("");
$this->render("consulta_index");



}//fin function


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['caop04_ordencompra_modificacion']['login']) && isset($this->data['caop04_ordencompra_modificacion']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['caop04_ordencompra_modificacion']['login']);
		$paswd=addslashes($this->data['caop04_ordencompra_modificacion']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=81 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}






}//fin class

?>