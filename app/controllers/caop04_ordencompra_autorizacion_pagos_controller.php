<?php
class Caop04ordencompraautorizacionpagosController extends AppController {

   var $name = "caop04_ordencompra_autorizacion_pagos";
   var $uses = array('cscd04_ordencompra_parametros', 'v_cscd04_ordencompra_pago', 'ccfd04_cierre_mes', 'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'cscd04_ordencompra_a_pago_partidas', 'cscd04_ordencompra_anticipo_partidas','ccfd03_instalacion', 'cscd04_ordencompra_partidas', 'cpcd02', 'cfpd22_numero_asiento_causado', 'cfpd22', 'cfpd05', 'cugd04', 'cscd04_ordcom_modificacion_cuerpo', 'cscd05_ordencompra_nota_entrega_encabezado', 'cscd03_cotizacion_cuerpo', 'cscd03_cotizacion_encabezado','select_autorizacion_pago','select_orden_compra','cugd05_restriccion_clave');
   var $helpers = array('Html','Ajax','Javascript','Sisap');

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

function Formato2($monto){return number_format($monto,2,",",".");}


function index(){

$this->layout = "ajax";

$this->set('autor_valido',true);// Temporal despues se puede quitar.

 $ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $ano = $this->ano_ejecucion();
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
 $lista = $this->select_orden_compra->generateList($condicion.' and condicion_actividad=1 and monto_verificar!=0'." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
 $this->set('lista_numero', $lista);
$this->set('ano',$ano);
$this->Session->delete('PAG_NUM');

}//fin function





function selecion($var1=null){
 $this->layout = "ajax";
 $ano='';
 $datos_orden_pagos_anteriores = "";
 $ano = $this->ano_ejecucion();
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
 $lista = $this->select_orden_compra->generateList($condicion.' and condicion_actividad=1 and monto_verificar!=0'." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
 $this->set('lista_numero', $lista);

 $this->set('numero_orden_compra', $var1);
 $this->Session->delete('PAG_NUM');


if($var1==null){

$this->index();
$this->render('index');

}else{

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$var1.'  and  ano_orden_compra='.$ano.' ';
$numero_datos          = $this->cscd04_ordencompra_encabezado->findAll($condicion);
$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion, null, 'numero_orden_compra DESC');

$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
	$ano_orden_compra = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
	$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
}//fin foreach
//$rif = "jj-as";
$opc     = $this->cscd04_ordencompra_autorizacion_cuerpo->findCount($condicion.' and ano_orden_compra='.$ano_orden_compra.'  and  numero_orden_compra='.$numero_orden_compra.'');
$result  = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($condicion."   and ano_orden_compra=".$ano_orden_compra."  and  numero_orden_compra=".$numero_orden_compra." ", null, "numero_pago ASC", null, null);
foreach($result as $ves){$opc = $ves['cscd04_ordencompra_autorizacion_cuerpo']['numero_pago'];}//fin foreach




$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");

$objeto_rif = "";
$denominacion_rif = "";
$porcentaje_iva  = "";
$direccion_comercial_rif = "";
$datos_contrato_obra_anteriores = "";
$datos_orden_pagos_anteriores_partidas = "";
$exento_islr_cooperativa = "";

foreach($rif_datos as $aux_2){
	$denominacion_rif          = $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif   = $aux_2['cpcd02']['direccion_comercial'];
	$objeto_rif                = $aux_2['cpcd02']['objeto'];
	$exento_islr_cooperativa   = $aux_2['cpcd02']['exento_islr_cooperativa'];
}//fin foreach


if($objeto_rif != "" && $denominacion_rif!=""){


$datos_orden_pagos_anteriores          = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($condicion.' and condicion_actividad=1', null, null, null, null);
$datos_orden_pagos_anteriores_partidas = $this->cscd04_ordencompra_a_pago_partidas->findAll($condicion, null, null, null, null);


$opc++;


//////////***********************  PARAMETROS   **********************************///////////////
$porcentaje_anticipo = 0;
$factor_reversion = "";
$anticipo_incluye_iva = "";

$anticipo_incluye_iva = "0";
$porcentaje_anticipo  = "0";
$porcentaje_iva       = "0";


 $condicion        = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
 $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
    foreach($parametros_datos as $aux_22){
      $anticipo_incluye_iva = $aux_22['cscd04_ordencompra_parametros']['anticipo_incluye_iva'];
      $porcentaje_anticipo  = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
      $porcentaje_iva       = $aux_22['cscd04_ordencompra_parametros']['porcentaje_iva'];
    }//fin foreach


//////////***********************  FIN PARAMETROS   ******************************///////////////
//$objeto_rif = 4;

$this->detalles_del_pago($objeto_rif, $ano_orden_compra, $numero_orden_compra, $exento_islr_cooperativa);



}else{
	$this->set('errorMessage', 'El rif del proveedor no existe');
	$this->set('porcentaje_retencion_iva'  , '');
	$this->set('impuesto_sobre_la_renta'   , '');
	$this->set('timbre_fiscal'             , '');
	$this->set('impuesto_municipal'        , '');
	$this->set('amortizacion_del_anticipo' , '');
	$this->set('anticipo_con_iva'          , '');
	$this->set('anticipo_con_iva2'         , '');
	$this->set('sustraendo'                , '');
	$this->set('porcentaje_fiel_cumplimiento' , '');
    $this->set('porcentaje_laboral'           , '');

 echo'<script>';
  echo'document.getElementById("guardar").disabled = true; ';
 echo'</script>';
}//fin else



$this->set('porcentaje_iva', $porcentaje_iva);
$this->set('ano_orden_compra_pago', $ano);
$this->set('numero_orden_compra_pago', $opc);
$this->set('datos_orden_compra', $numero_datos);
$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('direccion_comercial_rif', $direccion_comercial_rif);
$this->set('datos_orden_pagos_anteriores', $datos_orden_pagos_anteriores);
$this->set('datos_orden_pagos_anteriores_partidas', $datos_orden_pagos_anteriores_partidas);




   }//fin else
}//fin function


function pregunta_pago_parcial($opcion=null){

 $this->layout = "ajax";


 echo'<script>';

  echo'document.getElementById("monto_a_pagar_con_iva").value = "0,00"; ';
  echo'document.getElementById("monto_iva").value = "0,00";';
  echo'document.getElementById("monto_sin_iva").value = "0,00";';



  echo'document.getElementById("monto_a_pagar_con_iva").disabled = true; ';
  echo'document.getElementById("monto_iva").disabled = true;';
  echo'document.getElementById("monto_sin_iva").disabled = true;';
  echo'document.getElementById("retencion_incluye_iva").disabled = true;';
  echo'document.getElementById("retencion_incluye_iva_monto_iva").disabled = true;';
  echo'document.getElementById("impuesto_sobre_la_renta").disabled = true;';
  echo'document.getElementById("sustraendo").disabled = true;';
  echo'document.getElementById("impuesto_sobre_la_renta_monto_iva").disabled = true;';
  echo'document.getElementById("timbre_fiscal").disabled = true;';
  echo'document.getElementById("timbre_fiscal_monto_iva").disabled = true;';
  echo'document.getElementById("impuesto_municipal").disabled = true;';
  echo'document.getElementById("impuesto_municipal_monto_iva").disabled = true;';
  echo'document.getElementById("amortizacion_del_anticipo").disabled = true;';
  echo'document.getElementById("amortizacion_del_anticipo_monto_iva").disabled = true;';
  echo'document.getElementById("total_retencion_monto_iva").disabled = true;';
  echo'document.getElementById("monto_orden_de_pago_monto_iva").disabled = true;';
  echo'document.getElementById("monto_a_pagar_monto_iva").disabled = true;';

									  echo'document.getElementById("retencion_multa_monto").disabled = true;';
									  echo'document.getElementById("retencion_responsabilidad_social").disabled = true;';
									  echo'document.getElementById("rcivil").disabled = true;';
									  echo'document.getElementById("rsocial").disabled = true;';

  echo'cscp03_cotizacion_cuerpo_moneda("TOTALINGRESOS", "0.00");';

   echo' for(ii=0; ii<document.getElementById("cuenta_i").value; ii++){ ';
   echo'     document.getElementById("pago_"+ii).disabled = true; ';
   echo'     document.getElementById("pago_"+ii).value = "0,00"; ';
   echo' }//fin for ';

echo'</script>';


$this->set('opcion', $opcion);


}//function







function respuesta_pago_parcial($opcion=null){

 $this->layout = "ajax";

echo"<script>";
echo"pregunta_pago_parcial('".$opcion."');";
echo"</script>";



}//function









function detalles_del_pago($objeto_rif, $ano_orden_compra, $numero_orden_compra, $exento_islr_cooperativa){


$amortizacion_del_anticipo = "";
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
$parametros_datos_detalles_del_pago = $this->cscd04_ordencompra_parametros->findAll($this->SQLCA());
//echo $objeto_rif;


//echo $this->Session->read('SScoddep');

  $porcentaje_fiel_cumplimiento     =   "0";
  $porcentaje_laboral               =   "0";
  $retencion_incluye_iva            =   "0";
  $porcentaje_islr_natural          =   "0";
  $desde_monto_natural              =   "0";
  $sustraendo                       =   "0";
  $porcentaje_islr_juridico         =   "0";
  $desde_monto_juridico             =   "0";
  $porcentaje_timbre_fiscal         =   "0";
  $desde_monto_timbre               =   "0";
  $porcentaje_impuesto_municipal    =   "0";
  $desde_monto_impuesto_municipal   =   "0";
  $porcentaje_retencion_iva         =   "0";
  $aplica_retencion_iva             =   "0";
  $porcentaje_anticipo              =   "0";
  $anticipo_incluye_iva             =   "0";
  $unidad_tributaria                =   "0";
  $porcentaje_iva                   =   "0";
  $factor_reversion                 =   "0";



foreach($parametros_datos_detalles_del_pago as $aux_parametros_datos_detalles_del_pago){

  $porcentaje_fiel_cumplimiento     =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_fiel_cumplimiento'];
  $porcentaje_laboral               =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_laboral'];
  $retencion_incluye_iva            =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['retencion_incluye_iva'];
  $porcentaje_islr_natural          =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_islr_natural'];
  $desde_monto_natural              =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['desde_monto_natural'];
  $sustraendo                       =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['sustraendo'];
  $porcentaje_islr_juridico         =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_islr_juridico'];
  $desde_monto_juridico             =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['desde_monto_juridico'];
  $porcentaje_timbre_fiscal         =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_timbre_fiscal'];
  $desde_monto_timbre               =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['desde_monto_timbre'];
  $porcentaje_impuesto_municipal    =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_impuesto_municipal'];
  $desde_monto_impuesto_municipal   =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['desde_monto_impuesto_municipal'];
  $porcentaje_retencion_iva         =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_retencion_iva'];
  $aplica_retencion_iva             =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['aplica_retencion_iva'];
  $porcentaje_anticipo              =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
  $anticipo_incluye_iva             =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['anticipo_incluye_iva'];
  $unidad_tributaria                =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['unidad_tributaria'];
  $porcentaje_iva                   =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_iva'];
  $factor_reversion                 =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['factor_reversion'];


}//fin foreach


$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra.' ';
$numero_datos = $this->cscd04_ordencompra_encabezado->findAll($condicion);

$numero_datos_aux =  $numero_datos;

foreach($numero_datos_aux as $aux){
	$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
	$ano_orden_compra = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
	$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
	$porcentaje_anticipo = $aux['cscd04_ordencompra_encabezado']['porcentaje_anticipo'];
	$anticipo_con_iva = $aux['cscd04_ordencompra_encabezado']['anticipo_con_iva'];
	$monto_orden = $aux['cscd04_ordencompra_encabezado']['monto_orden'];
    $modificacion_aumento = $aux['cscd04_ordencompra_encabezado']['modificacion_aumento'];
    $modificacion_disminucion = $aux['cscd04_ordencompra_encabezado']['modificacion_disminucion'];
}//fin foreach


/**
 *
 *
 * LA OPCION 1 ES PARA QUE EN JAVASCRIPT LO VEA CON EL MONTO COMPLETO EN EL MONTO DEL CONTRATO AL CALCULAR EL PORCENTAJE DE LA AMORTIZACION
 *
 * NO IMPORTA EL 1 O 2 PARA QUE EL JAVASCRIPT LO VEA CON MONTO COMPLETO DEBE SER EL 1 ESTA OPCION ESTA BIEN NO TE CONFUNDAS
 */

//$anticipo_con_iva = 1;

$this->set('objeto_rif', $objeto_rif);

//echo $objeto_rif;

switch($objeto_rif){

case'1':{

	$monto_actual = $monto_orden + ($modificacion_aumento - $modificacion_disminucion);

    $sustraendo2 = "0.00";
    $sustraendo_neto = 0;
    $sustraendo_tresporciento = "0.00";

	$this->set('porcentaje_retencion_iva'       , $porcentaje_retencion_iva);
	$this->set('retencion_incluye_iva'          , $retencion_incluye_iva);
	$this->set('impuesto_sobre_la_renta'        , $porcentaje_islr_juridico);
	$this->set('desde_monto_islr'               , $desde_monto_juridico);
	$this->set('timbre_fiscal'                  , $porcentaje_timbre_fiscal);
	$this->set('desde_monto_timbre'             , $desde_monto_timbre);
	$this->set('impuesto_municipal'             , $porcentaje_impuesto_municipal);
	$this->set('desde_monto_impuesto_municipal' , $desde_monto_impuesto_municipal);
	$this->set('amortizacion_del_anticipo'      , $porcentaje_anticipo);
	$this->set('anticipo_con_iva'               , $anticipo_con_iva);
	$this->set('anticipo_con_iva2'              , $retencion_incluye_iva);
	$this->set('sustraendo'                     , $this->Formato2($sustraendo2));
	$this->set('sustraendo_neto'                , $sustraendo);
	$this->set('sustraendo_tresporciento'       , $this->Formato2($sustraendo_tresporciento));
	$this->set('exento_islr_cooperativa'        , $exento_islr_cooperativa);

}break;


case'2':{

	$monto_actual = $monto_orden + ($modificacion_aumento - $modificacion_disminucion);

    $sustraendo2 = "0.00";
    $sustraendo_neto = 0;
    $sustraendo_tresporciento = "0.00";

	$this->set('porcentaje_retencion_iva'       , $porcentaje_retencion_iva);
	$this->set('retencion_incluye_iva'          , $retencion_incluye_iva);
	$this->set('impuesto_sobre_la_renta'        , $porcentaje_islr_juridico);
	$this->set('desde_monto_islr'               , $desde_monto_juridico);
	$this->set('timbre_fiscal'                  , $porcentaje_timbre_fiscal);
	$this->set('desde_monto_timbre'             , $desde_monto_timbre);
	$this->set('impuesto_municipal'             , $porcentaje_impuesto_municipal);
	$this->set('desde_monto_impuesto_municipal' , $desde_monto_impuesto_municipal);
	$this->set('amortizacion_del_anticipo'      , $porcentaje_anticipo);
	$this->set('anticipo_con_iva'               , $anticipo_con_iva);
	$this->set('anticipo_con_iva2'              , $retencion_incluye_iva);
	$this->set('sustraendo'                     , $this->Formato2($sustraendo2));
	$this->set('sustraendo_neto'                , $sustraendo);
	$this->set('sustraendo_tresporciento'       , $this->Formato2($sustraendo_tresporciento));
	$this->set('exento_islr_cooperativa'        , $exento_islr_cooperativa);

}break;


case'3':{

	$monto_actual = $monto_orden + ($modificacion_aumento - $modificacion_disminucion);

	$sustraendo2 = "0.00";
    $sustraendo_neto = 0;
    $sustraendo_tresporciento = "0.00";

	$this->set('porcentaje_retencion_iva'       , $porcentaje_retencion_iva);
	$this->set('retencion_incluye_iva'          , $retencion_incluye_iva);
	$this->set('impuesto_sobre_la_renta'        , $porcentaje_islr_juridico);
	$this->set('desde_monto_islr'               , $desde_monto_juridico);
	$this->set('timbre_fiscal'                  , $porcentaje_timbre_fiscal);
	$this->set('desde_monto_timbre'             , $desde_monto_timbre);
	$this->set('impuesto_municipal'             , $porcentaje_impuesto_municipal);
	$this->set('desde_monto_impuesto_municipal' , $desde_monto_impuesto_municipal);
	$this->set('amortizacion_del_anticipo'      , $porcentaje_anticipo);
	$this->set('anticipo_con_iva'               , $anticipo_con_iva);
	$this->set('anticipo_con_iva2'              , $retencion_incluye_iva);
	$this->set('sustraendo'                     , $this->Formato2($sustraendo2));
	$this->set('sustraendo_neto'                , $sustraendo);
	$this->set('sustraendo_tresporciento'       , $this->Formato2($sustraendo_tresporciento));
	$this->set('exento_islr_cooperativa'        , $exento_islr_cooperativa);

}break;


case'4':{
//echo $porcentaje_islr_natural;

	$monto_actual = $monto_orden + ($modificacion_aumento - $modificacion_disminucion);

	$sql_busca_sustraendo = $this->cscd04_ordencompra_parametros->execute("SELECT f_sustraendo($sustraendo) AS sustraendo_tresporciento");
    $sustraendo2 = $sql_busca_sustraendo[0][0]['sustraendo_tresporciento'];
    $sustraendo_tresporciento = $sustraendo2;

	$this->set('porcentaje_retencion_iva'       , $porcentaje_retencion_iva);
	$this->set('retencion_incluye_iva'          , $retencion_incluye_iva);
	$this->set('impuesto_sobre_la_renta'        , $porcentaje_islr_natural);
	$this->set('desde_monto_islr'               , $desde_monto_natural);
	$this->set('timbre_fiscal'                  , $porcentaje_timbre_fiscal);
	$this->set('desde_monto_timbre'             , $desde_monto_timbre);
	$this->set('impuesto_municipal'             , $porcentaje_impuesto_municipal);
	$this->set('desde_monto_impuesto_municipal' , $desde_monto_impuesto_municipal);
	$this->set('amortizacion_del_anticipo'      , $porcentaje_anticipo);
	$this->set('anticipo_con_iva'               , $anticipo_con_iva);
	$this->set('anticipo_con_iva2'              , $retencion_incluye_iva);
	$this->set('sustraendo'                     , $this->Formato2($sustraendo2));
	$this->set('sustraendo_neto'                , $sustraendo);
	$this->set('sustraendo_tresporciento'       , $this->Formato2($sustraendo_tresporciento));
	$this->set('exento_islr_cooperativa'        , $exento_islr_cooperativa);

}break;


case'5':{


	echo '<script>';
   	  echo'document.getElementById("retencion_incluye_iva").readOnly = true;';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	  echo'document.getElementById("impuesto_municipal").readOnly = true;';
   	  echo'document.getElementById("impuesto_sobre_la_renta").readOnly = true;';
   	  echo'document.getElementById("amortizacion_del_anticipo").readOnly = true;';
   	echo '</script>';


	$this->set('porcentaje_retencion_iva'       , 0);
	$this->set('retencion_incluye_iva'          , 0);
	$this->set('impuesto_sobre_la_renta'        , 0);
	$this->set('desde_monto_islr'               , 0);
	$this->set('timbre_fiscal'                  , 0);
	$this->set('desde_monto_timbre'             , 0);
	$this->set('impuesto_municipal'             , 0);
	$this->set('desde_monto_impuesto_municipal' , 0);
	$this->set('amortizacion_del_anticipo'      , 0);
	$this->set('anticipo_con_iva'               , 0);
	$this->set('anticipo_con_iva2'              , 0);
	$this->set('sustraendo'                     , 0);
	$this->set('sustraendo_neto'                , 0);
	$this->set('sustraendo_tresporciento'       , 0);
	$this->set('exento_islr_cooperativa'        , 0);

}break;


case'6':{


	echo '<script>';
   	  echo'document.getElementById("retencion_incluye_iva").readOnly = true;';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	  echo'document.getElementById("impuesto_municipal").readOnly = true;';
   	  echo'document.getElementById("impuesto_sobre_la_renta").readOnly = true;';
   	  echo'document.getElementById("amortizacion_del_anticipo").readOnly = true;';
   	echo '</script>';


	$this->set('porcentaje_retencion_iva'       , 0);
	$this->set('retencion_incluye_iva'          , 0);
	$this->set('impuesto_sobre_la_renta'        , 0);
	$this->set('desde_monto_islr'               , 0);
	$this->set('timbre_fiscal'                  , 0);
	$this->set('desde_monto_timbre'             , 0);
	$this->set('impuesto_municipal'             , 0);
	$this->set('desde_monto_impuesto_municipal' , 0);
	$this->set('amortizacion_del_anticipo'      , 0);
	$this->set('anticipo_con_iva'               , 0);
	$this->set('anticipo_con_iva2'              , 0);
	$this->set('sustraendo'                     , 0);
	$this->set('sustraendo_neto'                , 0);
	$this->set('sustraendo_tresporciento'       , 0);
	$this->set('exento_islr_cooperativa'        , 0);

}break;





}//fin switch







}//fin funcion







function  opcion_pago($ano=null, $numero_ordencompra=null, $opc){

 $this->layout = "ajax";

$ano_orden_compra = $this->ano_ejecucion();
$entrega_completa = $this->cscd04_ordencompra_encabezado->field('cscd04_ordencompra_encabezado.entrega_completa', $conditions = $this->condicion()." and cscd04_ordencompra_encabezado.ano_orden_compra='$ano' and numero_orden_compra='$numero_ordencompra'", $order =null);
$tipo_modificacion = $this->cscd04_ordcom_modificacion_cuerpo->field('tipo_modificacion', $conditions = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_ordencompra'", $order =null);
$tipo_orden = $this->cscd04_ordencompra_encabezado->field('tipo_orden', $conditions, null);

if($tipo_orden ==2){
	if($opc == "2"){
		echo"<script>";
  		echo"pregunta_pago_parcial('".$opc."');";
  		echo"</script>";

	}else{
	echo'<script>';

  echo'document.getElementById("monto_a_pagar_con_iva").value = "0,00"; ';
  echo'document.getElementById("monto_iva").value = "0,00";';
  echo'document.getElementById("monto_sin_iva").value = "0,00";';



  echo'document.getElementById("monto_a_pagar_con_iva").disabled = true; ';
  echo'document.getElementById("monto_iva").disabled = true;';
  echo'document.getElementById("monto_sin_iva").disabled = true;';
  echo'document.getElementById("retencion_incluye_iva").disabled = true;';
  echo'document.getElementById("retencion_incluye_iva_monto_iva").disabled = true;';
  echo'document.getElementById("impuesto_sobre_la_renta").disabled = true;';
  echo'document.getElementById("impuesto_sobre_la_renta_monto_iva").disabled = true;';
  echo'document.getElementById("timbre_fiscal").disabled = true;';
  echo'document.getElementById("sustraendo").disabled = true;';
  echo'document.getElementById("timbre_fiscal_monto_iva").disabled = true;';
  echo'document.getElementById("impuesto_municipal").disabled = true;';
  echo'document.getElementById("impuesto_municipal_monto_iva").disabled = true;';
  echo'document.getElementById("amortizacion_del_anticipo").disabled = true;';
  echo'document.getElementById("amortizacion_del_anticipo_monto_iva").disabled = true;';
  echo'document.getElementById("total_retencion_monto_iva").disabled = true;';
  echo'document.getElementById("monto_orden_de_pago_monto_iva").disabled = true;';
  echo'document.getElementById("monto_a_pagar_monto_iva").disabled = true;';

									  echo'document.getElementById("retencion_multa_monto").disabled = true;';
									  echo'document.getElementById("retencion_responsabilidad_social").disabled = true;';
									  echo'document.getElementById("rcivil").disabled = true;';
									  echo'document.getElementById("rsocial").disabled = true;';

  echo'cscp03_cotizacion_cuerpo_moneda("TOTALINGRESOS", "0.00");';

   echo' for(ii=0; ii<document.getElementById("cuenta_i").value; ii++){ ';
   echo'     document.getElementById("pago_"+ii).disabled = true; ';
   echo'     document.getElementById("pago_"+ii).value = "0,00"; ';
   echo' }//fin for ';

echo'</script>';
	}


}else{


			if($opc == "2"){


				     if((!empty($entrega_completa) && $entrega_completa==0)){

                         $this->set('msgError', 'FAVOR REGISTRAR LA NOTA DE ENTREGA');

				}else if((!empty($entrega_completa) && $entrega_completa==2) || (!empty($tipo_modificacion) && $tipo_modificacion==2)){

				echo"<script>";
			  	echo"pregunta_pago_parcial('".$opc."');";
			  	echo"</script>";

				}else{
					$this->set('msgError', 'NO PUEDE REALIZAR PAGO TOTAL - FALTA POR ENTREGAR PRODUCTOS');
				}
			}else{
				if((!empty($entrega_completa) && ($entrega_completa==1 || $entrega_completa==2)) || (!empty($tipo_modificacion) && $tipo_modificacion==2)){
							 echo'<script>';
									  echo'document.getElementById("monto_a_pagar_con_iva").value = "0,00"; ';
									  echo'document.getElementById("monto_iva").value = "0,00";';
									  echo'document.getElementById("monto_sin_iva").value = "0,00";';
									  echo'document.getElementById("monto_a_pagar_con_iva").disabled = true; ';
									  echo'document.getElementById("monto_iva").disabled = true;';
									  echo'document.getElementById("monto_sin_iva").disabled = true;';
									  echo'document.getElementById("retencion_incluye_iva").disabled = true;';
									  echo'document.getElementById("retencion_incluye_iva_monto_iva").disabled = true;';
									  echo'document.getElementById("impuesto_sobre_la_renta").disabled = true;';
									  echo'document.getElementById("impuesto_sobre_la_renta_monto_iva").disabled = true;';
									  echo'document.getElementById("timbre_fiscal").disabled = true;';
									  echo'document.getElementById("sustraendo").disabled = true;';
									  echo'document.getElementById("timbre_fiscal_monto_iva").disabled = true;';
									  echo'document.getElementById("impuesto_municipal").disabled = true;';
									  echo'document.getElementById("impuesto_municipal_monto_iva").disabled = true;';
									  echo'document.getElementById("amortizacion_del_anticipo").disabled = true;';
									  echo'document.getElementById("amortizacion_del_anticipo_monto_iva").disabled = true;';
									  echo'document.getElementById("total_retencion_monto_iva").disabled = true;';
									  echo'document.getElementById("monto_orden_de_pago_monto_iva").disabled = true;';
									  echo'document.getElementById("monto_a_pagar_monto_iva").disabled = true;';
									  echo'document.getElementById("retencion_multa_monto").disabled = true;';
									  echo'document.getElementById("retencion_responsabilidad_social").disabled = true;';
									  echo'document.getElementById("rcivil").disabled = true;';
									  echo'document.getElementById("rsocial").disabled = true;';
									  echo'cscp03_cotizacion_cuerpo_moneda("TOTALINGRESOS", "0.00");';
									  echo' for(ii=0; ii<document.getElementById("cuenta_i").value; ii++){ ';
									  echo'     document.getElementById("pago_"+ii).disabled = true; ';
									  echo'     document.getElementById("pago_"+ii).value = "0,00"; ';
									  echo' }//fin for ';
					         echo'</script>';
				}else{
					$this->set('msgError', 'NO PUEDE REALIZAR PAGO PARCIAL - FALTA POR ENTREGAR PRODUCTOS');
				}

			}//fin else

}//fin if tipo orden

$this->set('opc' , $opc);



}//function





function guardar(){

  $this->layout = "ajax";


  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');

  $datos_orden_pagos_anteriores = "";


 $i_lenght = $this->data['cscd04_ordencompra_autorizacion']['cuenta_i'];

  $ano_orden_compra                      =       $this->data['cscd04_ordencompra_autorizacion']['ano_orden_compra'];
  $numero_orden_compra                   =       $this->data['cscd04_ordencompra_autorizacion']['numero_orden_compra'];
  $ano_orden_compra_autorizacion         =       $ano_orden_compra;
  $ann = $ano_orden_compra;
  $numero_orden_compra_autorizacion      =       $this->data['cscd04_ordencompra_autorizacion']['numero_orden_compra_autorizacion_pagos'];
  $nda = $numero_orden_compra_autorizacion;
  $fecha_autorizacion_pagos              =       $this->data['cscd04_ordencompra_autorizacion']['fecha_autorizacion_pagos'];
  $fd = $fecha_autorizacion_pagos;



 $anticipo_con_iva                      =       $this->data['cscd04_ordencompra_autorizacion']['anticipo_con_iva'];



  $numero_pago                          =       $numero_orden_compra_autorizacion;

  $porcentaje_amortizacion              =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['amortizacion_del_anticipo']);
  $total_retencion_monto_iva            =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['total_retencion_monto_iva']);
  $monto_orden_de_pago_monto_iva        =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_orden_de_pago']);
  $monto_a_pagar_monto_iva              =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_a_pagar_monto_iva']);
  $concepto_autorizacion                =       $this->data['cscd04_ordencompra_autorizacion']['concepto'];

  $monto_orden2                          =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_orden']);

  $saldo_orden                           =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['saldo_orden']);



  $monto_orden_pago                     =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_orden_de_pago']);
  $modificacion                         =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['modificaciones']);
  $monto_anticipo                       =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_anticipo']);
  $monto_amortizacion                   =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_amortizacion']);
  $monto_cancelado                      =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_cancelado']);

  $monto_iva                            =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_iva']);
  $monto_cancelar                       =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_a_pagar_con_iva']);
  $monto_cancelar_siniva                =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['monto_sin_iva']);
  $amortizacion_anticipo                =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['amortizacion_del_anticipo_monto_iva']);
  $monto_islr                           =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['impuesto_sobre_la_renta_monto_iva']);


  if(isset($this->data['cscd04_ordencompra_autorizacion']['impuesto_sobre_la_renta'])){
    $porcentaje_islr                      =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['impuesto_sobre_la_renta']);
  }else{
  	$porcentaje_islr = 0;
  }


  $monto_sustraendo                     =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['sustraendo']);
  $monto_timbre_fiscal                  =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['timbre_fiscal_monto_iva']);
  $porcentaje_timbre_fiscal             =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['timbre_fiscal']);
  $monto_impuesto_municipal             =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['impuesto_municipal_monto_iva']);
  $porcentaje_impuesto_municipal        =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['impuesto_municipal']);
  $monto_retencion_iva                  =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['retencion_incluye_iva_monto_iva']);
  $porcentaje_retencion_iva             =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['retencion_incluye_iva']);

  $porcentaje_iva_aplicado              =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['porcentaje_iva']);



  $retencion_multa_monto                        =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['retencion_multa_monto']);
  $retencion_responsabilidad_social             =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['retencion_responsabilidad_social']);
  $prc                        =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['rcivil']);
  $prs             =       $this->Formato1($this->data['cscd04_ordencompra_autorizacion']['rsocial']);



if($monto_iva==0){$porcentaje_iva_aplicado=0;}



  if(isset($i_lenght)){
  if(isset($ano_orden_compra)){
  if(isset($numero_orden_compra)){
  if(isset($numero_orden_compra_autorizacion)){
  if(isset($fecha_autorizacion_pagos)){
  if(isset($anticipo_con_iva)){
  if(isset($porcentaje_amortizacion)){
  if(isset($total_retencion_monto_iva)){
  if(isset($monto_orden_de_pago_monto_iva)){
  if(isset($monto_a_pagar_monto_iva)){
  if(isset($concepto_autorizacion)){
  if(isset($monto_orden2)){
  if(isset($saldo_orden)){
  if(isset($monto_orden_pago)){
  if(isset($modificacion)){
  if(isset($monto_anticipo)){
  if(isset($monto_amortizacion)){
  if(isset($monto_cancelado)){
  if(isset($monto_iva)){
  if(isset($monto_cancelar)){
  if(isset($monto_cancelar_siniva)){
  if(isset($amortizacion_anticipo)){
  if(isset($monto_islr)){
  if(isset($porcentaje_islr)){
  if(isset($monto_sustraendo)){
  if(isset($monto_timbre_fiscal)){
  if(isset($porcentaje_timbre_fiscal)){
  if(isset($monto_impuesto_municipal)){
  if(isset($porcentaje_impuesto_municipal)){
  if(isset($monto_retencion_iva)){
  if(isset($porcentaje_retencion_iva)){
  if(isset($porcentaje_iva_aplicado)){





















  $ano_asiento_registro                 =       '0';
  $mes_asiento_registro                 =       '0';
  $dia_asiento_registro                 =       '0';
  $numero_asiento_registro              =       '0';
  $username_registro                    =      	$this->Session->read('nom_usuario');
  $username_anulacion                   =       '0';
  $fecha_proceso_registro               =       date('d/m/Y');
  $condicion_actividad                  =       '1';
  $ano_asiento_anulacion                =       '0';
  $mes_asiento_anulacion                =       '0';
  $dia_asiento_anulacion                =       '0';
  $numero_asiento_anulacion             =       '0';
  $fecha_proceso_anulacion              =       '01/01/1900';
  $ano_orden_pago                       =        0 ;
  $numero_orden_pago                    =        0 ;



  //$monto_amortizacion_aux   =  $amortizacion_anticipo + $monto_amortizacion;
  //$monto_cancelado_aux      =  $monto_orden + $monto_cancelado;

  $monto_amortizacion_aux   =   $monto_amortizacion;
  $monto_cancelado_aux      =   $monto_cancelado;

  $monto_cancelado_aux3     =  $monto_orden_pago;
  $monto_amortizacion_aux3  =  $amortizacion_anticipo;


  $monto_nuevo  = $saldo_orden - $monto_cancelar;


$sw2 = 0;
$sw3 = 0;
$sw4 = 0;
$sw5 = 0;


$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra.' ';
$datos_orden_pagos_anteriores = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($condicion.' and condicion_actividad=1', null, null, null, null);


$sql="  BEGIN;  INSERT INTO cscd04_ordencompra_autorizacion_pago_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra, numero_pago, monto_orden, modificacion, monto_anticipo,  monto_amortizacion, monto_cancelado, monto_iva, monto_cancelar, monto_cancelar_siniva, amortizacion_anticipo, porcentaje_amortizacion, monto_islr, porcentaje_islr, monto_sustraendo, monto_timbre_fiscal, porcentaje_timbre_fiscal, monto_impuesto_municipal, porcentaje_impuesto_municipal, monto_retencion_iva, porcentaje_retencion_iva, porcentaje_iva_aplicado, ano_asiento_registro, mes_asiento_registro, dia_asiento_registro, numero_asiento_registro, username_registro, fecha_proceso_registro, fecha_autorizacion, condicion_actividad, ano_asiento_anulacion, mes_asiento_anulacion, dia_asiento_anulacion, numero_asiento_anulacion, fecha_proceso_anulacion, ano_orden_pago, numero_orden_pago, username_anulacion, concepto, retencion_multa, retencion_responsabilidad, codigo_retencion_islr, cod_actividad,porcentaje_multa,porcentaje_responsabilidad)";
$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$numero_pago."', '".$monto_orden2."', '".$modificacion."', '".$monto_anticipo."',  '".$monto_amortizacion_aux."', '".$monto_cancelado_aux."', '".$monto_iva."', '".$monto_cancelar."', '".$monto_cancelar_siniva."', '".$amortizacion_anticipo."', '".$porcentaje_amortizacion."', '".$monto_islr."', '".$porcentaje_islr."', '".$monto_sustraendo."', '".$monto_timbre_fiscal."', '".$porcentaje_timbre_fiscal."', '".$monto_impuesto_municipal."', '".$porcentaje_impuesto_municipal."', '".$monto_retencion_iva."', '".$porcentaje_retencion_iva."', '".$porcentaje_iva_aplicado."', '".$ano_asiento_registro."', '".$mes_asiento_registro."', '".$dia_asiento_registro."', '".$numero_asiento_registro."', '".$username_registro."', '".$this->Cfecha($fecha_proceso_registro, 'A-M-D')."', '".$this->Cfecha($fecha_autorizacion_pagos, 'A-M-D')."', '".$condicion_actividad."', '".$ano_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$dia_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$this->Cfecha($fecha_proceso_anulacion, 'A-M-D')."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$username_anulacion."', '".$concepto_autorizacion."', '".$retencion_multa_monto."', '".$retencion_responsabilidad_social."', '".$_SESSION["ventana_islr"]."', '".$_SESSION["ventana_impuesto_municipal"]."','".$prc."','".$prs."'); ";
$sw = $this->cscd04_ordencompra_autorizacion_cuerpo->execute($sql);

$amortizacion_aux_compara = 0;


$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra.' ';
$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion, null, 'numero_orden_compra DESC');
$a=0;
$i_aux = 0;
if($sw>1){
	foreach($numero_datos_partidas as $aux_partidas){
	  $cod_presi2[$a]                    =   $aux_partidas['cscd04_ordencompra_partidas']['cod_presi'];
	  $cod_presiA                        =   $cod_presi2[$a];
	  $cod_entidad2[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['cod_entidad'];
	  $cod_entidadA                      =   $cod_entidad2[$a];
	  $cod_tipo_inst2[$a]                =   $aux_partidas['cscd04_ordencompra_partidas']['cod_tipo_inst'];
	  $cod_tipo_instA                    =   $cod_tipo_inst2[$a];
	  $cod_inst2[$a]                     =   $aux_partidas['cscd04_ordencompra_partidas']['cod_inst'];
	  $cod_instA                         =   $cod_inst2[$a];
	  $cod_dep2[$a]                      =   $aux_partidas['cscd04_ordencompra_partidas']['cod_dep'];
	  $cod_depA                          =   $cod_dep2[$a];
	  $ano_orden_compra3[$a]             =   $aux_partidas['cscd04_ordencompra_partidas']['ano_orden_compra'];
	  $ano_orden_compraA                 =   $ano_orden_compra3[$a];
	  $numero_orden_compra3[$a]          =   $aux_partidas['cscd04_ordencompra_partidas']['numero_orden_compra'];
	  $numero_orden_compraA              =   $numero_orden_compra3[$a];
	  $ano_partidas[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['ano'];
	  $ano_partidasA                     =   $ano_partidas[$a];
	  $cod_sector[$a]                    =   $aux_partidas['cscd04_ordencompra_partidas']['cod_sector'];
	  $cod_sectorA                       =   $cod_sector[$a];
	  $cod_programa[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['cod_programa'];
	  $cod_programaA                     =   $cod_programa[$a];
	  $cod_sub_prog[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['cod_sub_prog'];
	  $cod_sub_progA                     =   $cod_sub_prog[$a];
	  $cod_proyecto[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['cod_proyecto'];
	  $cod_proyectoA                     =   $cod_proyecto[$a];
	  $cod_activ_obra[$a]                =   $aux_partidas['cscd04_ordencompra_partidas']['cod_activ_obra'];
	  $cod_activ_obraA                   =   $cod_activ_obra[$a];
	  $cod_partida[$a]                   =   $aux_partidas['cscd04_ordencompra_partidas']['cod_partida'];
	  $cod_partidaA                      =   $cod_partida[$a];
	  $cod_generica[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['cod_generica'];
	  $cod_genericaA                     =   $cod_generica[$a];
	  $cod_especifica[$a]                =   $aux_partidas['cscd04_ordencompra_partidas']['cod_especifica'];
	  $cod_especificaA                   =   $cod_especifica[$a];
	  $cod_sub_espec[$a]                 =   $aux_partidas['cscd04_ordencompra_partidas']['cod_sub_espec'];
	  $cod_sub_especA                    =   $cod_sub_espec[$a];
	  $cod_auxiliar[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['cod_auxiliar'];
	  $cod_auxiliarA                     =   $cod_auxiliar[$a];
	  $monto2[$a]                        =   $aux_partidas['cscd04_ordencompra_partidas']['monto'];
	  $aumento2[$a]                      =   $aux_partidas['cscd04_ordencompra_partidas']['aumento'];
	  $disminucion2[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['disminucion'];
	  $numero_control_compromiso[$a]     =   $aux_partidas['cscd04_ordencompra_partidas']['numero_asiento_compromiso'];
	  $rnco = $numero_control_compromiso[$a];
      $sql_where[$a] = "cod_presi='$cod_presiA' and cod_entidad=$cod_entidadA and cod_tipo_inst=$cod_tipo_instA and cod_inst=$cod_instA and cod_dep=$cod_depA and ano_orden_compra=$ano_orden_compraA and numero_orden_compra=$numero_orden_compraA and ano=$ano_partidasA and cod_sector=$cod_sectorA and cod_programa=$cod_programaA and cod_sub_prog=$cod_sub_progA and cod_proyecto=$cod_proyectoA and cod_activ_obra=$cod_activ_obraA and cod_partida=$cod_partidaA and cod_generica=$cod_genericaA and cod_especifica=$cod_especificaA and cod_sub_espec=$cod_sub_especA and cod_auxiliar=$cod_auxiliarA";
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

			for($i=0; $i<$i_lenght; $i++){
			   if($partidas_aux==$this->data['partidas']['partidas_'.$i]){$partidas_vista['pago_'.$i_aux] = $this->data['cscd04_ordencompra_autorizacion']['pago_'.$i]; $i_aux++;}
			}//fin foreach


}//fin foreach









///////////////////////////////////////////////REDONDEO AMORTIZACION Y CANCELADO///////////////////////////////////////////
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra.' ';
$numero_datos_partidas_anteriores = $this->cscd04_ordencompra_a_pago_partidas->findAll($condicion);
$f = 0;
$monto_ante        = 0;
$amortizacion_ante = 0;
$amortizacion_aux_partidas = 0;


//$amortizacion_anticipo            ::: ES EL TOTAL DE A AMORTIZAR EN ESTA VALUACIÓN
//$amortizacion_aux_partidas        ::: ES LA SUMATORIA DE LA AMORTIZACION DE LAS PARTIDAS

foreach($numero_datos_partidas_anteriores as $aux_partidas_anteriores){
	  $cod_presi_ante[$f]                     =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_presi'];
	  $cod_entidad_ante[$f]                   =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_entidad'];
	  $cod_tipo_inst_ante[$f]                 =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_tipo_inst'];
	  $cod_inst_ante[$f]                      =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_inst'];
	  $cod_dep_ante[$f]                       =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_dep'];
	  $fno_orden_compra_ante[$f]              =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['ano_orden_compra'];
	  $numero_orden_compra_ante[$f]           =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['numero_orden_compra'];
	  $ano_partidas_ante[$f]                  =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['ano'];
	  $cod_sector_ante[$f]                    =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_sector'];
	  $cod_programa_ante[$f]                  =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_programa'];
	  $cod_sub_prog_ante[$f]                  =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_sub_prog'];
	  $cod_proyecto_ante[$f]                  =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_proyecto'];
	  $cod_activ_obra_ante[$f]                =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_activ_obra'];
	  $cod_partida_ante[$f]                   =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_partida'];
	  $cod_generica_ante[$f]                  =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_generica'];
	  $cod_especifica_ante[$f]                =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_especifica'];
	  $cod_sub_espec_ante[$f]                 =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_sub_espec'];
	  $cod_auxiliar_ante[$f]                  =      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['cod_auxiliar'];
	  $monto_ante                            +=      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['monto'];
	  $amortizacion_ante                     +=      $aux_partidas_anteriores['cscd04_ordencompra_a_pago_partidas']['amortizacion'];
$f++;
}//fin for


$am_cont = 0;
for($i=0; $i<$i_lenght; $i++){
	       $var[$i]['pago']=$partidas_vista['pago_'.$i];
	 if($var[$i]['pago']!="0,00"){  $var[$i]['pago'] = $this->Formato1($var[$i]['pago']); $am_cont++;
        $amortizacion_aux[$am_cont] = 0;
	 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
         if($monto_nuevo=="0"){

                    $datos_cscd04_ordencompra_partidas    =     $this->cscd04_ordencompra_partidas->findAll($sql_where[$i]);
					foreach($datos_cscd04_ordencompra_partidas as $aux_datos_cscd04_ordencompra_partidas){
						$anticipo        =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['anticipo'];
						$amortizacion    =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['amortizacion'];
						$cancelado       =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['cancelado'];
						$aumento         =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['aumento'];
						$disminucion     =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['disminucion'];
						$numero_compromiso =  $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['numero_asiento_compromiso'];
					}//fin foreach


			             	 if($concate!="403.18.01.00"){
			                            $amortizacion_aux[$am_cont] = $anticipo - $amortizacion;
			             	 }//fin if



             }else{
				     if($anticipo_con_iva==1 && $monto_iva!=0){
				     	  if($concate!="403.18.01.00"){
				     	     $amortizacion_aux[$am_cont] = (($var[$i]['pago'] + ($var[$i]['pago'] * $porcentaje_iva_aplicado/100)) *  $porcentaje_amortizacion) / 100;
				     	  }//fin
					  }else{
				          if($concate!="403.18.01.00"){
				             $amortizacion_aux[$am_cont] = ($var[$i]['pago'] * $porcentaje_amortizacion) / 100;
				            }//fin if
					   }//fin else
             }//fin else

             $amortizacion_aux[$am_cont] = $this->Formato2($amortizacion_aux[$am_cont]);
             $amortizacion_aux[$am_cont] = $this->Formato1($amortizacion_aux[$am_cont]);

         $amortizacion_aux_partidas    += $amortizacion_aux[$am_cont];
	 }//fin if
}//fin for


$datos_orden_compra_encabezado    =     $this->cscd04_ordencompra_encabezado->findAll($condicion);
foreach($datos_orden_compra_encabezado as $aux_datos_orden_compra_encabezado){
		$monto_amortizacion_aux2 = $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_amortizacion'];
		$monto_cancelado_aux2    = $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_cancelado'];
		$monto_anticipo_aux2     = $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_anticipo'];
}//fin foreach



//////////////////////////////////////////////////////REDONDEO/////////////////////////////////////////////////////////////////




$monto_iva_aux_partidas          =  0;
$monto_cancelacion_aux_partidas  =  0;
$monto_total_aux_partidas        =  0;
$am_cont=0;
 for($i=0; $i<$i_lenght; $i++){
 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
       if($partidas_vista['pago_'.$i]!="0,00"){
	   	         $var[$i]['pago'] = $this->Formato1($partidas_vista['pago_'.$i]);
	   	         $am_cont++;
                 $var[$i]['pago'] = $var[$i]['pago']-($amortizacion_aux[$am_cont]);
	            if($concate=="403.18.01.00"){$monto_iva_aux_partidas += $var[$i]['pago'];}else{$monto_cancelacion_aux_partidas +=$var[$i]['pago'];}//fin if
	         $monto_total_aux_partidas += $var[$i]['pago'];
	   }//fin if
 }//fin for



$monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
$monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);
$monto_cancelacion_aux_partidas =  $this->Formato2($monto_cancelacion_aux_partidas);
$monto_cancelacion_aux_partidas =  $this->Formato1($monto_cancelacion_aux_partidas);


$am_cont=0;
if($amortizacion_anticipo!=$amortizacion_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){
    $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	  $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont]);
              if($concate!="403.18.01.00"){
	               if($amortizacion_anticipo>$amortizacion_aux_partidas && $aux_monto_partida!=$total_partida_vista){
	                  $amortizacion_aux[$am_cont] += 0.01; $amortizacion_aux_partidas += 0.01;
	                }else if($amortizacion_anticipo<$amortizacion_aux_partidas && $aux_monto_partida!=$total_partida_vista){
	                  $amortizacion_aux[$am_cont] -= 0.01; $amortizacion_aux_partidas -= 0.01;
	                }//fin if
               }//fin if
      }//fin if
   }//fin if
}//fin if




$am_cont=0;
if($amortizacion_anticipo!=$amortizacion_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){$aux_a = 0;
           	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
                 if($anticipo_con_iva==1){
                 	  if($concate!="403.18.01.00"){
		                         $aux_a = $amortizacion_aux[$am_cont]+$amortizacion_ante;
		                      if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){ $amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
		                }else if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){ $amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
		              }//fin else
		           }//fin else
                 }else{
				      if($concate!="403.18.01.00"){
				          	$aux_a = $amortizacion_aux[$am_cont]+$amortizacion_ante;
                      if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){ $amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
                }else if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){ $amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
                }//fin else
                	  }//fin if
			}//fin else
	 }//fin if
   }//fin for
}//fin if



if($monto_total_aux_partidas!=$monto_orden_pago){
$am_cont=0;
 for($i=0; $i<$i_lenght; $i++){
 	 $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
          if($concate!="403.18.01.00"){
          	    $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          	    $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont]);
                if($monto_total_aux_partidas<$monto_orden_pago && $aux_monto_partida!=$total_partida_vista){
                  $var[$i]['pago'] += 0.01; $monto_total_aux_partidas += 0.01;
                }else if($monto_total_aux_partidas>$monto_orden_pago && $aux_monto_partida!=$total_partida_vista){
                  $var[$i]['pago'] -= 0.01; $monto_total_aux_partidas -= 0.01;
                }//fin if
          }//fin if
	 }//fin if
  }//fin if
}//fin if
$monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
$monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);
if($monto_total_aux_partidas!=$monto_orden_pago){
$am_cont=0;
 for($i=0; $i<$i_lenght; $i++){
 	 $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
          if($concate!="403.18.01.00"){
          	    $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          	    $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont]);
                if($monto_total_aux_partidas<$monto_orden_pago){
                  $var[$i]['pago'] += 0.01; $monto_total_aux_partidas += 0.01;
                }else if($monto_total_aux_partidas>$monto_orden_pago){
                  $var[$i]['pago'] -= 0.01; $monto_total_aux_partidas -= 0.01;
                }//fin if
          }//fin if
	 }//fin if
  }//fin if
}//fin if


/////////////////////////////////////////////FIN REDONDEO AMORTIZACION Y CANCELADO///////////////////////////////////////////




$j = 0;
$numero_causado = 0;

/*

$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);
if(!empty($numero_causado)){
	$numero_causado ++;
	$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
}else{
	$numero_causado = 1;
	$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado');";
}//fin if
$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);


*/

for($i=0; $i<$i_lenght; $i++){
	       //$var[$i]['pago']=$partidas_vista['pago_'.$i];
	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;

		    // $var[$i]['pago'] = $this->Formato1($var[$i]['pago']);
		     $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
             //$amortizacion_aux = 0;

                   $datos_cscd04_ordencompra_partidas    =     $this->cscd04_ordencompra_partidas->findAll($sql_where[$i]);
					foreach($datos_cscd04_ordencompra_partidas as $aux_datos_cscd04_ordencompra_partidas){
						$anticipo          =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['anticipo'];
						$amortizacion      =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['amortizacion'];
						$cancelado         =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['cancelado'];
						$aumento           =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['aumento'];
						$disminucion       =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['disminucion'];
						$numero_compromiso =    $aux_datos_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['numero_asiento_compromiso'];
					}//fin foreach



            $amortizacion_aux_compara +=  $amortizacion_aux[$am_cont];

			$monto_partida = $this->Formato1($partidas_vista['pago_'.$i]);
            $pago = $var[$i]['pago'];
		    $cp   = $this->crear_partida($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
			$to   = 1;
			$td   = 4;
			$ta   = 3;
			$mt   = $var[$i]['pago'];
			$ndo  = $numero_orden_compra3[$i];
			$rnco = $numero_compromiso;
			$rnca = $numero_causado;

			//$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp='', $ann, $ndo, $nda, null, null, null, null, null, null, $rnco, $rnca, null, null, $i);


			$sw2 = 0;

            $monto_partida = $this->Formato1($partidas_vista['pago_'.$i]);
            $pago  = $var[$i]['pago'];

		    $sql2  ="INSERT INTO cscd04_ordencompra_autorizacion_pago_partidas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra, numero_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec,  cod_auxiliar, monto, numero_control_compromiso, numero_control_causado, amortizacion) ";
	        $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$numero_orden_compra_autorizacion."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$pago."', '$rnco', '$rnca', '$amortizacion_aux[$am_cont]'); ";
	        $sw2 = $this->cscd04_ordencompra_a_pago_partidas->execute($sql2);
	        $sql_where2 = $sql_where[$i];

           if($sw2 > 1){
		 	$sql_partidas_ordencompra = "UPDATE cscd04_ordencompra_partidas SET amortizacion=amortizacion+".$amortizacion_aux[$am_cont].", cancelado=cancelado + ".$pago." WHERE $sql_where2".";";
	        $sw3 = $this->cscd04_ordencompra_partidas->execute($sql_partidas_ordencompra);
	        if($sw3 > 1){}else{/*$this->cscd04_ordencompra_partidas->execute("ROLLBACK;");*/ }//fin else
	       	        }else{
              //$this->cscd04_ordencompra_a_pago_partidas->execute("ROLLBACK;");

	       }//fin else

	}//fin if

}//fin for


if($sw3 > 1){


	$datos_orden_compra_encabezado    =     $this->cscd04_ordencompra_encabezado->findAll($condicion);


	foreach($datos_orden_compra_encabezado as $aux_datos_orden_compra_encabezado){
		$monto_amortizacion_aux2 = $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_amortizacion'];
		$monto_cancelado_aux2    = $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_cancelado'];
	}//fin foreach

	  $monto_amortizacion_aux2 = $monto_amortizacion_aux2 + $monto_amortizacion_aux3;
	//$monto_amortizacion_aux2 = $monto_amortizacion_aux2 + $amortizacion_aux_compara;
	$monto_cancelado_aux2 = $monto_cancelado_aux2 + $monto_cancelado_aux3;
	$sql3  = "UPDATE cscd04_ordencompra_encabezado SET monto_amortizacion = '".$monto_amortizacion_aux2."', monto_cancelado = '".$monto_cancelado_aux2."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_orden_compra=".$numero_orden_compra."  and  ano_orden_compra=".$ano_orden_compra."; ";
	$sw4 = $this->cscd04_ordencompra_encabezado->execute($sql3);


    if($sw4 > 1){
        $this->cscd04_ordencompra_encabezado->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
     }else{
     	$this->cscd04_ordencompra_encabezado->execute("ROLLBACK;"); $this->set('errorMessage', 'NO SE LOGRO GUARDAR LA AUTORIZACION - POR FAVOR INTENTE DE NUEVO');
     	  }//fin else


}else{
$this->cscd04_ordencompra_a_pago_partidas->execute("ROLLBACK;");
$this->set('errorMessage', 'NO SE LOGRO GUARDAR LA AUTORIZACION - POR FAVOR INTENTE DE NUEVO');
}//fin else



}else{
	$this->cscd04_ordencompra_autorizacion_cuerpo->execute("ROLLBACK;");
	$msg_error = 'NO SE LOGRO GUARDAR LA AUTORIZACION DE PAGO - POR FAVOR INTENTE DE NUEVO';
	$this->set('msg_error', 'NO SE LOGRO GUARDAR LA AUTORIZACION DE PAGO - POR FAVOR INTENTE DE NUEVO');
	return;
}



}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
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



}//fin guardar
















function buscar_year($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


  //$lista = $this->cscd04_ordencompra_autorizacion_cuerpo->generateList($condicion." and ano_orden_compra=".$var1, ' numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_autorizacion_cuerpo.numero_orden_compra', '{n}.cscd04_ordencompra_autorizacion_cuerpo.numero_orden_compra');
  //$this->AddCero('compras', $lista);

  $lista = $this->select_autorizacion_pago->generateList($condicion." and ano_orden_compra=".$var1." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_autorizacion_pago.numero_orden_compra', '{n}.select_autorizacion_pago.beneficiario');
  $this->set('compras', $lista);


}//fin function

















function consulta_index($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $pag_num = 0;
  $opcion = 'si';



  if(!empty($this->data['caop04_ordencompra_autorizacion_pagos']['ano_ejecucion'])){
       	 $_SESSION['ano_compra'] = $this->data['caop04_ordencompra_autorizacion_pagos']['ano_ejecucion'];
   }else{$_SESSION['ano_compra'] = $this->ano_ejecucion();}


    $ano = $_SESSION['ano_compra'];

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
  $lista = $this->select_autorizacion_pago->generateList($condicion." and ano_orden_compra=".$ano." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_autorizacion_pago.numero_orden_compra', '{n}.select_autorizacion_pago.beneficiario');
  $this->set('compras', $lista);
  $this->set('ano',$ano);

if($var1!=null){

  if($var1=='si'){

if(!empty($this->data['caop04_ordencompra_autorizacion_pagos']['numero_orden_compra'])){

	   $array = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($condicion. " and numero_orden_compra='".$this->data['caop04_ordencompra_autorizacion_pagos']['numero_orden_compra']."' and ano_orden_compra = ".$ano, null, 'ano_orden_compra, numero_orden_compra, numero_pago ASC', null);
  $i = 0;


foreach($array as $aux){
 	$numero[$i]['ano_orden_compra']    = $aux['cscd04_ordencompra_autorizacion_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_autorizacion_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_pago'] = $aux['cscd04_ordencompra_autorizacion_cuerpo']['numero_pago'];
 	$i++;
} $i--;


  for($a=0; $a<=$i; $a++){
    if($this->data['caop04_ordencompra_autorizacion_pagos']['numero_orden_compra'] == $numero[$a]['numero_orden_compra']){
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

      if($opcion=='si'){$_SESSION['PAG_NUM']=$this->data['caop04_ordencompra_autorizacion_pagos']['numero_orden_compra'];  $this->consulta($pag_num, $numero_documento);$this->render('consulta');
}else if($opcion=='no'){
	$this->set('errorMessage', 'No existen datos');
	}//fin else





}else{


	$array = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($condicion. " and ano_orden_compra = ".$ano, null, 'ano_orden_compra, numero_orden_compra, numero_pago ASC', null);
  $i = 0;



foreach($array as $aux){
 	$numero[$i]['ano_orden_compra']    = $aux['cscd04_ordencompra_autorizacion_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_autorizacion_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_pago'] = $aux['cscd04_ordencompra_autorizacion_cuerpo']['numero_pago'];
 	$i++;
} $i--;


	$this->Session->delete('PAG_NUM');

	if($i<=0){
	  $this->set('errorMessage', 'No existen datos');
	  $this->consulta_index();
	  $this->render('consulta_index');
	}else{$this->consulta(0, $numero[0]['numero_orden_compra']); $this->render('consulta');} }//fin else



  }//fin if
}//fin if

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function












function consulta($pag_num=null, $numero_documento=null, $g=null){
  $this->layout = "ajax";
  if(!empty($this->data['cscp02_solicitud_cotizacion']['ano_ejecucion'])){
	$ano = $this->data['cscp02_solicitud_cotizacion']['ano_ejecucion'];
  }else{
  	$ano = $this->ano_ejecucion();
  }


  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}
   $this->set('ano_compra', $ano);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
  $this->set('ano_ejecucion', $this->ano_ejecucion());

   $array = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($condicion. ' and ano_orden_compra = '.$ano.'  and numero_orden_compra='.$numero_documento , null, 'numero_pago ASC', null);


       $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_orden_compra']    = $aux['cscd04_ordencompra_autorizacion_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_autorizacion_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_pago']         = $aux['cscd04_ordencompra_autorizacion_cuerpo']['numero_pago'];

 	$i++;

} $i--;








if(isset($numero[$pag_num]['numero_orden_compra'])){



$datos_cscd04_ordencompra_autorizacion_pago_cuerpo          =     $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].'  and numero_pago='.$numero[$pag_num]['numero_pago'].'   ');
$datos_cscd04_ordencompra_autorizacion_pago_partidas        =     $this->cscd04_ordencompra_a_pago_partidas->findAll(    $condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and numero_pago='.$numero[$pag_num]['numero_pago'].'  ');
$datos_orden_compra_encabezado                              =     $this->cscd04_ordencompra_encabezado->findAll(         $condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].'  ');
$v_cscd04_ordencompra_pago                                  =     $this->v_cscd04_ordencompra_pago->findAll(             $condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].'  and numero_pago='.$numero[$pag_num]['numero_pago'].' ', null, null, null, null);

//print_r($datos_cscd04_ordencompra_autorizacion_pago_partidas );

$cur = 0;



//print_r($v_cscd04_ordencompra_pago);

 foreach($v_cscd04_ordencompra_pago as $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux){


  $cuerpo[$cur]['cod_presi']                              =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['cod_presi'];
  $cuerpo[$cur]['cod_entidad']                            =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['cod_entidad'];
  $cuerpo[$cur]['cod_tipo_inst']                          =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['cod_tipo_inst'];
  $cuerpo[$cur]['cod_inst']                               =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['cod_inst'];
  $cuerpo[$cur]['cod_dep']                                =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['cod_dep'];
  $cuerpo[$cur]['ano_orden_compra']                       =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['ano_orden_compra'];
  $cuerpo[$cur]['numero_orden_compra']                    =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['numero_orden_compra'];
  $cuerpo[$cur]['numero_pago']                            =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['numero_pago'];
  $cuerpo[$cur]['monto_orden']                            =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_orden'];
  $cuerpo[$cur]['modificacion']                           =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['modificacion'];
  $cuerpo[$cur]['monto_anticipo']                         =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_anticipo'];
  $cuerpo[$cur]['monto_amortizacion']                     =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_amortizacion'];
  $cuerpo[$cur]['monto_cancelado']                        =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_cancelado'];
  $cuerpo[$cur]['monto_iva']                              =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_iva'];
  $cuerpo[$cur]['monto_cancelar']                         =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_cancelar'];
  $cuerpo[$cur]['monto_cancelar_siniva']                  =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_cancelar_siniva'];
  $cuerpo[$cur]['amortizacion_anticipo']                  =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['amortizacion_anticipo'];
  $cuerpo[$cur]['monto_islr']                             =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_islr'];
  $cuerpo[$cur]['porcentaje_islr']                        =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['porcentaje_islr'];
  $cuerpo[$cur]['monto_timbre_fiscal']                    =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_timbre_fiscal'];
  $cuerpo[$cur]['porcentaje_timbre_fiscal']               =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['porcentaje_timbre_fiscal'];
  $cuerpo[$cur]['monto_impuesto_municipal']               =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_impuesto_municipal'];
  $cuerpo[$cur]['porcentaje_impuesto_municipal']          =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['porcentaje_impuesto_municipal'];
  $cuerpo[$cur]['monto_retencion_iva']                    =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['monto_retencion_iva'];
  $cuerpo[$cur]['porcentaje_retencion_iva']               =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['porcentaje_retencion_iva'];
  $cuerpo[$cur]['porcentaje_iva_aplicado']                =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['porcentaje_iva_aplicado'];
  $cuerpo[$cur]['ano_asiento_registro']                   =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['ano_asiento_registro'];
  $cuerpo[$cur]['mes_asiento_registro']                   =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['mes_asiento_registro'];
  $cuerpo[$cur]['dia_asiento_registro']                   =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['dia_asiento_registro'];
  $cuerpo[$cur]['numero_asiento_registro']                =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['numero_asiento_registro'];
  $cuerpo[$cur]['username_registro']                      =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['username_registro'];
  $cuerpo[$cur]['fecha_proceso_registro']                 =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['fecha_proceso_registro'];
  $cuerpo[$cur]['condicion_actividad']                    =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['condicion_actividad'];
  $cuerpo[$cur]['ano_asiento_anulacion']                  =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['ano_asiento_anulacion'];
  $cuerpo[$cur]['mes_asiento_anulacion']                  =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['mes_asiento_anulacion'];
  $cuerpo[$cur]['dia_asiento_anulacion']                  =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['dia_asiento_anulacion'];
  $cuerpo[$cur]['numero_asiento_anulacion']               =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['numero_asiento_anulacion'];
  $cuerpo[$cur]['fecha_proceso_anulacion']                =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['fecha_proceso_anulacion'];
  $cuerpo[$cur]['ano_orden_pago']                         =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['ano_orden_pago'];
  $cuerpo[$cur]['numero_orden_pago']                      =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['numero_orden_pago'];
  $cuerpo[$cur]['username_anulacion']                     =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['username_anulacion'];
  $cuerpo[$cur]['concepto']                               =          $datos_cscd04_ordencompra_autorizacion_pago_cuerpo_aux['v_cscd04_ordencompra_pago']['concepto'];




  $cur++;

 }//fin for

////************************************** DESPUES DE GUARDAR ****************************************/////




 $var1 = $numero[0]['ano_orden_compra'];



 $ano='';
 //$datos_orden_pagos_anteriores = "";
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $ano = $this->ano_ejecucion();



 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$cuerpo[0]['ano_orden_compra'].'';
 $lista = $this->cscd04_ordencompra_encabezado->generateList($condicion.' and condicion_actividad=1 and ((monto_orden+modificacion_aumento)-(modificacion_disminucion+monto_amortizacion+monto_cancelado))!=0', ' numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.numero_orden_compra');
 $this->AddCero('lista_numero', $lista);

 $this->set('numero_orden_compra', $var1);



$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$cuerpo[0]['numero_orden_compra'].'  and  ano_orden_compra='.$cuerpo[0]['ano_orden_compra'].' ';
$numero_datos = $this->cscd04_ordencompra_encabezado->findAll($condicion);
$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion);
$numero_datos_aux =  $numero_datos;
$numero_datos = $datos_cscd04_ordencompra_autorizacion_pago_cuerpo;

foreach($numero_datos_aux as $aux){
	$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
	$ano_orden_compra = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
	$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
	$tipo_orden = $aux['cscd04_ordencompra_encabezado']['tipo_orden'];
	$fecha_orden_compra = $aux['cscd04_ordencompra_encabezado']['fecha_orden_compra'];
}//fin foreach

$opc = $this->cscd04_ordencompra_autorizacion_cuerpo->findCount($condicion.' and ano_orden_compra='.$ano_orden_compra.'  and numero_orden_compra='.$numero_orden_compra.'');
$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
//print_r($rif_datos);

foreach($rif_datos as $aux_2){
	$denominacion_rif = $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
	$objeto_rif = $aux_2['cpcd02']['objeto'];
	$exento_islr_cooperativa   = $aux_2['cpcd02']['exento_islr_cooperativa'];
}//fin foreach





  $this->set('rif', $rif);
  $this->set('fecha_orden_compra', $fecha_orden_compra);
  $this->set('tipo_orden', $tipo_orden);
  $this->set('ano_orden_compra', $ano_orden_compra);
  $this->set('numero_orden_compra', $numero_orden_compra);
  $this->set('numero_orden_compra_autorizacion_pagos',$cuerpo[0]['numero_orden_pago'] );
  $this->set('fecha_autorizacion_pagos',            $numero_datos[0]['cscd04_ordencompra_autorizacion_cuerpo']['fecha_autorizacion'] );

  $this->set('numero_orden_compra_autorizacion',    $cuerpo[0]['numero_orden_pago']);

  $this->set('monto_actual',                        $cuerpo[0]['monto_orden']);
  $this->set('modificaciones',                      $cuerpo[0]['modificacion']);
  $this->set('monto_anticipo',                      $cuerpo[0]['monto_anticipo']);
  $this->set('monto_amortizacion',                  $cuerpo[0]['monto_amortizacion']);
  $this->set('monto_cancelado',                     $cuerpo[0]['monto_cancelado']);

  $this->set('monto_iva',                           $cuerpo[0]['monto_iva'] );
  $this->set('monto_a_pagar_con_iva',               $cuerpo[0]['monto_cancelar']);
  $this->set('monto_sin_iva',                       $cuerpo[0]['monto_cancelar_siniva']);
  $this->set('amortizacion_del_anticipo_monto_iva', $cuerpo[0]['amortizacion_anticipo']);
  $this->set('impuesto_sobre_la_renta_monto_iva',   $cuerpo[0]['monto_islr']);
  $this->set('impuesto_sobre_la_renta',             $cuerpo[0]['porcentaje_islr']);
  $this->set('timbre_fiscal_monto_iva',             $cuerpo[0]['monto_timbre_fiscal']);
  $this->set('timbre_fiscal',                       $cuerpo[0]['porcentaje_timbre_fiscal']);
  $this->set('impuesto_municipal_monto_iva',        $cuerpo[0]['monto_impuesto_municipal']);
  $this->set('impuesto_municipal',                  $cuerpo[0]['porcentaje_impuesto_municipal']);
  $this->set('retencion_incluye_iva_monto_iva',     $cuerpo[0]['monto_retencion_iva']);
  $this->set('porcentaje_retencion_iva',            $cuerpo[0]['porcentaje_retencion_iva']);
  $this->set('concepto',                            $cuerpo[0]['concepto']);

  $this->set('amortizacion_del_anticipo',           $datos_cscd04_ordencompra_autorizacion_pago_cuerpo[0]['cscd04_ordencompra_autorizacion_cuerpo']['porcentaje_amortizacion']);


 $TOTAL_RETENCIONES	 = $cuerpo[0]['monto_retencion_iva'] + $cuerpo[0]['monto_timbre_fiscal'] + $cuerpo[0]['monto_islr'] + $cuerpo[0]['amortizacion_anticipo'] + $cuerpo[0]['monto_impuesto_municipal'] + $datos_cscd04_ordencompra_autorizacion_pago_cuerpo[0]['cscd04_ordencompra_autorizacion_cuerpo']['retencion_multa'] + $datos_cscd04_ordencompra_autorizacion_pago_cuerpo[0]['cscd04_ordencompra_autorizacion_cuerpo']['retencion_responsabilid'];

  $this->set('total_retencion_monto_iva',      $TOTAL_RETENCIONES);
  $this->set('monto_orden_de_pago_monto_iva',  $cuerpo[0]['monto_cancelar'] - $cuerpo[0]['amortizacion_anticipo']);
  $this->set('monto_a_pagar_monto_iva',        $cuerpo[0]['monto_cancelar'] - $TOTAL_RETENCIONES);



$i_lenght = 0;



 foreach($datos_cscd04_ordencompra_autorizacion_pago_partidas as $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux){

if(!isset($datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['amortizacion_anticipo'])){
	 $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['amortizacion_anticipo'] = "";
}//fin



  $partidas[$i_lenght]['cod_presi']                  =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_presi'];
  $partidas[$i_lenght]['cod_entidad']                =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_entidad'];
  $partidas[$i_lenght]['cod_tipo_inst']              =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_tipo_inst'];
  $partidas[$i_lenght]['cod_inst']                   =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_inst'];
  $partidas[$i_lenght]['cod_dep']                    =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_dep'];
  $partidas[$i_lenght]['ano_orden_compra']           =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['ano_orden_compra'];
  $partidas[$i_lenght]['numero_orden_compra']        =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['numero_orden_compra'];
  $partidas[$i_lenght]['numero_pago']                =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['numero_pago'];
  $partidas[$i_lenght]['ano']                        =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['ano'];
  $partidas[$i_lenght]['cod_sector']                 =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_sector'];
  $partidas[$i_lenght]['cod_programa']               =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_programa'];
  $partidas[$i_lenght]['cod_sub_prog']               =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_sub_prog'];
  $partidas[$i_lenght]['cod_proyecto']               =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_proyecto'];
  $partidas[$i_lenght]['cod_activ_obra']             =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_activ_obra'];
  $partidas[$i_lenght]['cod_partida']                =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_partida'];
  $partidas[$i_lenght]['cod_generica']               =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_generica'];
  $partidas[$i_lenght]['cod_especifica']             =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_especifica'];
  $partidas[$i_lenght]['cod_sub_espec']              =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_sub_espec'];
  $partidas[$i_lenght]['cod_auxiliar']               =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['cod_auxiliar'];
  $partidas[$i_lenght]['monto']                      =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['monto'];
  $partidas[$i_lenght]['amortizacion_anticipo']      =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['amortizacion_anticipo'];
  $partidas[$i_lenght]['numero_control_compromiso']  =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['numero_control_compromiso'];
  $partidas[$i_lenght]['numero_control_causado']     =          $datos_cscd04_ordencompra_autorizacion_pago_partidas_aux['cscd04_ordencompra_a_pago_partidas']['numero_control_causado'];


   $i_lenght ++;

 }//fin for

 $i_lenght--;

/*
 for($ii=0; $ii<$i_lenght; $ii++){
       $var[$ii]['pago']=$partidas[$ii]['monto'] ;
            $_SESSION['pago_'.$ii] = $var[$ii]['pago'];
 }//fin for */



$this->set('ano_asiento_registro',      $cuerpo[0]['ano_asiento_registro']);
$this->set('mes_asiento_registro',      $cuerpo[0]['mes_asiento_registro']);
$this->set('dia_asiento_registro',      $cuerpo[0]['dia_asiento_registro']);
$this->set('numero_asiento_registro',   $cuerpo[0]['numero_asiento_registro']);
$this->set('username_registro',         $cuerpo[0]['username_registro']);
$this->set('fecha_proceso_registro',    $cuerpo[0]['fecha_proceso_registro']);
$this->set('condicion_actividad',       $cuerpo[0]['condicion_actividad']);



$this->set('ano_acta_anulacion',     0);
$this->set('numero_acta_anulacion',  0);

$this->set('ano_asiento_anulacion',     $cuerpo[0]['ano_asiento_anulacion']);
$this->set('mes_asiento_anulacion',     $cuerpo[0]['mes_asiento_anulacion']);
$this->set('dia_asiento_anulacion',     $cuerpo[0]['dia_asiento_anulacion']);
$this->set('numero_asiento_anulacion',  $cuerpo[0]['numero_asiento_anulacion']);
$this->set('fecha_proceso_anulacion',   $cuerpo[0]['fecha_proceso_anulacion']);
$this->set('ano_orden_pago',            $cuerpo[0]['ano_orden_pago']);
$this->set('numero_orden_pago',         $cuerpo[0]['numero_orden_pago']);
$this->set('username_anulacion',        $cuerpo[0]['username_anulacion']);


$this->set('ano_orden_compra_pago',       $cuerpo[0]['ano_orden_compra'] );
$this->set('numero_orden_compra_pago',    $cuerpo[0]['numero_pago'] );
$this->set('datos_orden_compra',          $numero_datos);
$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
$this->set('denominacion_rif',            $denominacion_rif);
$this->set('direccion_comercial_rif',     $direccion_comercial_rif);
$this->set('datos_orden_compra_partidas', $datos_cscd04_ordencompra_autorizacion_pago_partidas);


$total_retencion_monto_iva =   $cuerpo[0]['monto_retencion_iva'] + $cuerpo[0]['monto_islr'] + $cuerpo[0]['monto_timbre_fiscal'] + $cuerpo[0]['monto_impuesto_municipal'] + $cuerpo[0]['amortizacion_anticipo'] + $datos_cscd04_ordencompra_autorizacion_pago_cuerpo[0]['cscd04_ordencompra_autorizacion_cuerpo']['retencion_multa'] + $datos_cscd04_ordencompra_autorizacion_pago_cuerpo[0]['cscd04_ordencompra_autorizacion_cuerpo']['retencion_responsabilid'];
$monto_a_pagar_monto_iva   =   $cuerpo[0]['monto_cancelar'] - $total_retencion_monto_iva;


$this->set('total_retencion_monto_iva',  $total_retencion_monto_iva);
$this->set('monto_a_pagar_monto_iva',    $monto_a_pagar_monto_iva);


////************************************** DESPUES DE GUARDAR ****************************************/////








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

	if(isset($this->data["caop04_ordencompra_autorizacion_pagos"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		     $tipo_documento           =  243;

			 //$concepto_anulacion       =  $this->data["caop04_ordencompra_autorizacion_pagos"]["concepto_anulacion"];
			 $concepto_anulacion = "";
			 $concepto = $concepto_anulacion;
			 $fecha_proceso_anulacion  =  date("d/m/Y");


			 $condicion_documento      =  2;//cuando se guarda es Activo=1

			 $ano_orden_compra    = $this->data["caop04_ordencompra_autorizacion_pagos"]["ano_orden_compra"];
			 $numero_orden_compra = $this->data["caop04_ordencompra_autorizacion_pagos"]["numero_orden_compra"];
			 $fecha_orden_compra  = $this->data["caop04_ordencompra_autorizacion_pagos"]["fecha_autorizacion_pagos"];
			 $fd = $fecha_orden_compra;
			 $amortizacion_aux2 = 0;
			 $amortizacion_prueba = 0;
			 $cancelado_prueba = 0;

			 $numero_orden_compra_autorizacion_pagos = $this->data["caop04_ordencompra_autorizacion_pagos"]["numero_orden_compra_autorizacion_pagos"];




if(isset($ano_orden_compra)){
if(isset($numero_orden_compra)){
if(isset($fecha_orden_compra)){
if(isset($numero_orden_compra_autorizacion_pagos)){





			 $monto_cancelado = 0;
			 $datos_partidas = $this->cscd04_ordencompra_a_pago_partidas->findAll($conditions = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and numero_pago='$numero_orden_compra_autorizacion_pagos'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			 $sql_update_cscd04_partidas ='';


/////////////////////////ACTUALIZA LAS PARTIDAS/////////////////////
foreach($datos_partidas as $row){
			 	$ano = $row['cscd04_ordencompra_a_pago_partidas']['ano'];
			 	$cod_sector = $row['cscd04_ordencompra_a_pago_partidas']['cod_sector'];
			 	$cod_programa = $row['cscd04_ordencompra_a_pago_partidas']['cod_programa'];
			 	$cod_sub_prog = $row['cscd04_ordencompra_a_pago_partidas']['cod_sub_prog'];
			 	$cod_proyecto = $row['cscd04_ordencompra_a_pago_partidas']['cod_proyecto'];
			 	$cod_activ_obra = $row['cscd04_ordencompra_a_pago_partidas']['cod_activ_obra'];
			 	$cod_partida = $row['cscd04_ordencompra_a_pago_partidas']['cod_partida'];
			 	$cod_generica = $row['cscd04_ordencompra_a_pago_partidas']['cod_generica'];
			 	$cod_especifica = $row['cscd04_ordencompra_a_pago_partidas']['cod_especifica'];
			 	$cod_sub_espec = $row['cscd04_ordencompra_a_pago_partidas']['cod_sub_espec'];
			 	$cod_auxiliar = $row['cscd04_ordencompra_a_pago_partidas']['cod_auxiliar'];
			 	$monto_partida = $row['cscd04_ordencompra_a_pago_partidas']['monto'];
			 	$amortizacion2 = $row['cscd04_ordencompra_a_pago_partidas']['amortizacion'];
			 	$numero_control_compromiso = $row['cscd04_ordencompra_a_pago_partidas']['numero_control_compromiso'];
			 	$numero_control_causado = $row['cscd04_ordencompra_a_pago_partidas']['numero_control_causado'];
			 	$cond1 = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

                $amortizacion_aux2 += $amortizacion2;

				$monto_cancelado2   = $monto_partida - $amortizacion2;

				//$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
				//$num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 4, 3, date("d/m/Y"), $monto_cancelado2, $concepto, $ano, $numero_orden_compra, $numero_orden_compra_autorizacion_pagos, null, null, null, null, null, null, $numero_control_compromiso, $numero_control_causado, null, null, null);

                $datos_cscd04_ordencompra_partidas    =     $this->cscd04_ordencompra_partidas->findAll($cond1);
                foreach($datos_cscd04_ordencompra_partidas as $aux_cscd04_ordencompra_partidas){
		           $amortizacion = $aux_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['amortizacion'];
		           $cancelado    = $aux_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['cancelado'];
	             }//fin foreac
                $amortizacion     =  $amortizacion - $amortizacion2;
                $cancelado        =  $cancelado - $monto_partida;

			 	$sql_update_cscd04_partidas .= "UPDATE cscd04_ordencompra_partidas SET amortizacion=".$amortizacion.", cancelado=".$cancelado." WHERE ".$cond1.";";

}//fin for
$sw = $this->cscd04_ordencompra_partidas->execute($sql_update_cscd04_partidas);
//echo $sql_update_cscd04_partidas."\n";
//echo "despues del update: ".print_r($sw);
///////////////////////FIN ACTUALIZA LAS PARTIDAS/////////////////////



/////////////////////////ACTUALIZA EL ENCABEZADO//////////////////

$monto_amortizacion_aux_yy = 0;
$monto_cancelado_aux_yy    = 0;
$monto_cancelar_aux_zz     = 0;

           $datos_cuerpo = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($conditions = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and numero_pago='$numero_orden_compra_autorizacion_pagos'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
            foreach($datos_cuerpo as $aux_datos_cuerpo){
		       $monto_amortizacion_aux_yy = $aux_datos_cuerpo['cscd04_ordencompra_autorizacion_cuerpo']['amortizacion_anticipo'];
		       $monto_cancelado_aux_yy    = $aux_datos_cuerpo['cscd04_ordencompra_autorizacion_cuerpo']['monto_cancelado'];
		       $monto_cancelar_aux_zz     = $aux_datos_cuerpo['cscd04_ordencompra_autorizacion_cuerpo']['monto_cancelar'];
	        }//fin foreach
			$cond2 = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra'";
			$cond3 = $this->condicion()." and ano_ordencompra='$ano_orden_compra' and numero_ordencompra='$numero_orden_compra'";
			$datos_orden_compra_encabezado    =     $this->cscd04_ordencompra_encabezado->findAll($cond2);


            foreach($datos_orden_compra_encabezado as $aux_datos_orden_compra_encabezado){
		       $monto_amortizacion_aux2        =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_amortizacion'];
		       $monto_cancelado_aux2           =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_cancelado'];
		       $monto_ordencompra              =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];
		       $monto_aumento_ordencompra      =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['modificacion_aumento'];
		       $monto_disminucion_ordencompra  =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['modificacion_disminucion'];
	        }//fin foreach


	        $monto_total_ordencompra = ($monto_ordencompra + $monto_aumento_ordencompra) - $monto_disminucion_ordencompra;
	        $monto_amortizacion_aux2   = $monto_amortizacion_aux2 - $monto_amortizacion_aux_yy;
	        $monto_cancelado_aux2      = $monto_cancelado_aux2 - ($monto_cancelar_aux_zz - $monto_amortizacion_aux_yy);

	        $rif_notaentrega = $this->cscd05_ordencompra_nota_entrega_encabezado->field('cscd05_ordencompra_nota_entrega_encabezado.rif', $conditions = $cond2, $order = null);
	        $ano_notaentrega = $this->cscd05_ordencompra_nota_entrega_encabezado->field('cscd05_ordencompra_nota_entrega_encabezado.ano_nota_entrega', $conditions = $cond2, $order = null);
	        $num_notaentrega = $this->cscd05_ordencompra_nota_entrega_encabezado->field('cscd05_ordencompra_nota_entrega_encabezado.numero_nota_entrega', $conditions = $cond2, $order = null);
	        $num_cotizacion = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.numero_cotizacion', $conditions = $cond3, $order = null);

			//echo $monto_total_ordencompra." = ".$monto_cancelar_aux_zz;
			$cond_nota = $this->condicion()." and ano_nota_entrega='$ano_notaentrega' and numero_nota_entrega='$num_notaentrega' and rif='$rif_notaentrega'";


	        if($monto_total_ordencompra == $monto_cancelado_aux2){
	        	$sql_delete_encabezado = "DELETE FROM cscd05_ordencompra_nota_entrega_encabezado WHERE ".$cond_nota." ; ";
	        	$sql_delete_cuerpo = "DELETE FROM cscd05_ordencompra_nota_entrega_cuerpo WHERE ".$cond_nota." ; ";
	        	$sql_update_cotizacion = "UPDATE cscd03_cotizacion_cuerpo SET cantidad_entregada=0 WHERE ".$this->condicion()." and ano_cotizacion='$ano_orden_compra' and numero_cotizacion='$num_cotizacion'";

				$this->cscd03_cotizacion_cuerpo->execute($sql_update_cotizacion);
	        	$this->cscd04_ordencompra_autorizacion_cuerpo->execute($sql_delete_encabezado);
	        	$this->cscd04_ordencompra_autorizacion_cuerpo->execute($sql_delete_cuerpo);
                $sql_update_cscd04_encabezado ="UPDATE cscd04_ordencompra_encabezado SET entrega_completa=0, monto_amortizacion=".$monto_amortizacion_aux2.", monto_cancelado=".$monto_cancelado_aux2."  WHERE ".$cond2.";";
	        }else{
	        	$sql_update_cscd04_encabezado ="UPDATE cscd04_ordencompra_encabezado SET  monto_amortizacion=".$monto_amortizacion_aux2.", monto_cancelado=".$monto_cancelado_aux2."  WHERE ".$cond2.";";
			}//fin else

			$sw = $this->cscd04_ordencompra_encabezado->execute($sql_update_cscd04_encabezado);

/////////////////////////FIN ACTUALIZA EL ENCABEZADO////////////////




			 //$numero_anulacion=$this->cugd03_acta_anulacion_numero->execute($condicion);
             /*$v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra." ORDER BY numero_acta_anulacion DESC");

		     if($v!=null && $sw > 1){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",1)");
			    $numero=1;
		     }//fin else*/

			// $R1 = $this->cscd04_ordencompra_autorizacion_cuerpo->execute("UPDATE cscd04_ordencompra_autorizacion_pago_cuerpo SET ano_asiento_anulacion=".date("Y").", numero_asiento_anulacion=".$numero.", condicion_actividad=".$condicion_documento.", mes_asiento_anulacion=".date("m").",  dia_asiento_anulacion=".date("d").",  fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'  WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_pago=".$numero_orden_compra_autorizacion_pagos);

			 $R1 = $this->cscd04_ordencompra_autorizacion_cuerpo->execute("DELETE FROM cscd04_ordencompra_autorizacion_pago_partidas   WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_pago=".$numero_orden_compra_autorizacion_pagos);
			 $R1 = $this->cscd04_ordencompra_autorizacion_cuerpo->execute("       DELETE FROM cscd04_ordencompra_autorizacion_pago_cuerpo     WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_pago=".$numero_orden_compra_autorizacion_pagos);


		    // $v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",".$numero.",".$tipo_documento.",".$ano_orden_compra.",".$numero_orden_compra.",'".$this->Cfecha($fecha_orden_compra, 'A-M-D')."','".$concepto_anulacion."')");

                   $this->set('Message_existe', 'Los registro fue anulado correctamente');

            }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
			}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
			}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
			}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}


	}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}






$this->consulta_index('1');
$this->render('consulta_index');

/*
echo'<script>';
    echo'document.getElementById("guardar").disabled = true; ';
    echo'document.getElementById("anular").disabled = true; ';

    echo'document.getElementById("condicion_actividad_1").checked = false;';
  	echo'document.getElementById("condicion_actividad_2").checked = true;';

    echo'document.getElementById("a").innerHTML = "'.$ano_orden_compra.'"; ';
    echo'document.getElementById("b").innerHTML = "'.$numero.'"; ';
    echo'document.getElementById("c").innerHTML = "'.$fecha_proceso_anulacion.'"; ';
    echo'document.getElementById("d").innerHTML = "'.date("Y").'"; ';
    echo'document.getElementById("e").innerHTML = "'.date("m").'"; ';
    echo'document.getElementById("f").innerHTML = "'.$numero.'"; ';  ///AQUI VA EL NUMERO DE ASIENTO PERO HAY QUE ESPERAR EL DE EL MOTOR
    echo'document.getElementById("g").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';

echo'</script>';

*/

}//fin function


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['caop04_ordencompra_autorizacion_pagos']['login']) && isset($this->data['caop04_ordencompra_autorizacion_pagos']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['caop04_ordencompra_autorizacion_pagos']['login']);
		$paswd=addslashes($this->data['caop04_ordencompra_autorizacion_pagos']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=83 and clave='".$paswd."'";
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