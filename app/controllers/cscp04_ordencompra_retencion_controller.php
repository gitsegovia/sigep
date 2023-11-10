<?php

 class Cscp04OrdencompraRetencionController extends AppController{
	var $name = 'cscp04_ordencompra_retencion';
	var $uses = array('cscd04_ordencompra_encabezado', 'cscd04_ordencompra_partidas',
                      'cscd04_ordencompra_autorizacion_cuerpo', 'cscd04_ordencompra_a_pago_partidas',
                      'cscd04_ordencompra_retencion_cuerpo', 'cscd04_ordencompra_retencion_partidas',
                      'cscd04_ordencompra_anticipo_cuerpo', 'cscd04_ordencompra_parametros', 'select_orden_compra', 'select_orden_compra_retenciones',

                      'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas',
                      'cobd01_contratoobras_valuacion_cuerpo', 'cobd01_contratoobras_valuacion_partidas',
                      'cobd01_contratoobras_retencion_cuerpo', 'cobd01_contratoobras_retencion_partidas',


	                  'ccfd04_cierre_mes', 'cpcd02', 'cfpd22_numero_asiento_causado', 'cfpd22', 'cfpd05', 'cugd04',
	                  'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
	                  'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo',
					  'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
					  'cepd02_contratoservicio_retencion_cuerpo','cepd02_contratoservicio_cuerpo');


	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin function



function beforeFilter(){
    $this->checkSession();
}//fin function


function index(){
 $this->layout = "ajax";

 $ano = $this->ano_ejecucion();
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
 $lista = $this->select_orden_compra->generateList($condicion.' and condicion_actividad=1 and monto_verificar=0 and laboral_cancelado=0 or fielcumplimiento_cancelado=0'." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
 $this->set('lista_numero', $lista);

 $this->set('ano',$ano);
 $this->Session->delete('PAG_NUM');


}//fin function





function filtra_obra($year=null){

$cod_dep                  =  $this->Session->read('SScoddep');
$SScoddeporig             =  $this->Session->read('SScoddeporig');
$Modulo                   =  $this->Session->read('Modulo');
$sql_obra = "";
$SScoddeporig             =  $this->Session->read('SScoddeporig');
$SScoddep                 =  $this->Session->read('SScoddep');
$Modulo                   =  $this->Session->read('Modulo');


$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


$a = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$year);


foreach($a as $a_aux){
         if($sql_obra==""){
         	    $sql_obra .= "    numero_orden_compra=".$a_aux['cscd04_ordencompra_encabezado']['numero_orden_compra']." ";
     }//fin if

}//fin foreach

if($sql_obra!=""){ $sql_obra  =  "  and (".$sql_obra.")";}



return $sql_obra;


}//fin function








function detalles_del_pago($objeto_rif, $ano_orden_compra, $numero_orden_compra){


$amortizacion_del_anticipo = "";
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
$parametros_datos_detalles_del_pago = $this->cscd04_ordencompra_parametros->findAll($condicion);

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
  $rcivil                           =   "0";
  $retencion_multa_monto            =   "0";
  $rsocial                          =   "0";
  $retencion_responsabilidad_social =   "0";

//GOB.GUARICO
/*
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
*/
//FIN GOB.GUARICO


//ORIGINAL
/*
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
*/
//FIN ORIGINAL

$this->set('porcentaje_fiel_cumplimiento' , $porcentaje_fiel_cumplimiento);
$this->set('porcentaje_laboral'           , $porcentaje_laboral);
$this->set('rcivil'                       , $rcivil);
$this->set('rsocial'                      , $rcivil);

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_orden_compra='".$numero_orden_compra."'  and  ano_orden_compra=".$ano_orden_compra." ";
$numero_datos = $this->cscd04_ordencompra_encabezado->findAll($condicion);

$numero_datos_aux =  $numero_datos;

foreach($numero_datos_aux as $aux){
	$rif                          =  $aux['cscd04_ordencompra_encabezado']['rif'];
	$ano_orden_compra             =  $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
	$numero_orden_compra          =  $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
	$porcentaje_anticipo          =  $aux['cscd04_ordencompra_encabezado']['porcentaje_anticipo'];
	$anticipo_con_iva             =  $aux['cscd04_ordencompra_encabezado']['anticipo_con_iva'];
	$monto_original_contrato      =  $aux['cscd04_ordencompra_encabezado']['monto_original_contrato'];
    $modificacion_aumento         =  $aux['cscd04_ordencompra_encabezado']['aumento'];
    $modificacion_disminucion     =  $aux['cscd04_ordencompra_encabezado']['disminucion'];
    $monto_retencion_laboral              =  $aux['cscd04_ordencompra_encabezado']['monto_retencion_laboral'];
    $monto_retencion_fielcumplimiento     =  $aux['cscd04_ordencompra_encabezado']['monto_retencion_fielcumplimiento'];
}//fin foreach

$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){
	$objeto_rif               =  $aux_2['cpcd02']['objeto'];
	$exento_islr_cooperativa  = $aux_2['cpcd02']['exento_islr_cooperativa'];
}//fin foreach

$this->set('objeto_rif', $objeto_rif);


switch($objeto_rif){

case'1':{

	$monto_actual = $monto_retencion_laboral + $monto_retencion_fielcumplimiento;

	$sustraendo2 = "0.00";
    $sustraendo_neto = 0;
    $sustraendo_tresporciento = "0.00";

	$this->set('porcentaje_retencion_iva'         , $porcentaje_retencion_iva);
	$this->set('retencion_incluye_iva'            , $retencion_incluye_iva);
	$this->set('impuesto_sobre_la_renta'          , $porcentaje_islr_juridico);
	$this->set('desde_monto_islr'                 , $desde_monto_juridico);
	$this->set('timbre_fiscal'                    , $porcentaje_timbre_fiscal);
	$this->set('desde_monto_timbre'               , $desde_monto_timbre);
	$this->set('impuesto_municipal'               , $porcentaje_impuesto_municipal);
	$this->set('desde_monto_impuesto_municipal'   , $desde_monto_impuesto_municipal);
	$this->set('amortizacion_del_anticipo'        , $porcentaje_anticipo);
	$this->set('anticipo_con_iva'                 , $anticipo_con_iva);
	$this->set('anticipo_con_iva2'                , $retencion_incluye_iva);
	$this->set('sustraendo'                       , $this->Formato2($sustraendo2));
	$this->set('sustraendo_neto'                  , $sustraendo);
	$this->set('sustraendo_tresporciento'         , $this->Formato2($sustraendo_tresporciento));
	$this->set('exento_islr_cooperativa'          , $exento_islr_cooperativa);

	$this->set('rcivil'                           , $rcivil);
	$this->set('retencion_multa_monto'            , $retencion_multa_monto);
	$this->set('rsocial'                          , $rsocial);
	$this->set('retencion_responsabilidad_social' , $retencion_responsabilidad_social);

}break;


case'2':{

	$monto_actual = $monto_retencion_laboral + $monto_retencion_fielcumplimiento;

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

	$this->set('rcivil'                           , $rcivil);
	$this->set('retencion_multa_monto'            , $retencion_multa_monto);
	$this->set('rsocial'                          , $rsocial);
	$this->set('retencion_responsabilidad_social' , $retencion_responsabilidad_social);

}break;


case'3':{

	$monto_actual = $monto_retencion_laboral + $monto_retencion_fielcumplimiento;

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

	$this->set('rcivil'                           , $rcivil);
	$this->set('retencion_multa_monto'            , $retencion_multa_monto);
	$this->set('rsocial'                          , $rsocial);
	$this->set('retencion_responsabilidad_social' , $retencion_responsabilidad_social);

}break;


case'4':{

	$monto_actual = $monto_retencion_laboral + $monto_retencion_fielcumplimiento;

	$sql_busca_sustraendo = $this->cepd02_contratoservicio_valuacion_cuerpo->execute("SELECT f_sustraendo($sustraendo) AS sustraendo_tresporciento");
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

	$this->set('rcivil'                           , $rcivil);
	$this->set('retencion_multa_monto'            , $retencion_multa_monto);
	$this->set('rsocial'                          , $rsocial);
	$this->set('retencion_responsabilidad_social' , $retencion_responsabilidad_social);

}break;


case'5':{


	echo '<script>';
   	  echo'document.getElementById("retencion_incluye_iva").readOnly = true;';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	  echo'document.getElementById("impuesto_municipal").readOnly = true;';
   	  echo'document.getElementById("impuesto_sobre_la_renta").readOnly = true;';
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

	$this->set('rcivil'                           , $rcivil);
	$this->set('retencion_multa_monto'            , $retencion_multa_monto);
	$this->set('rsocial'                          , $rsocial);
	$this->set('retencion_responsabilidad_social' , $retencion_responsabilidad_social);

}break;


case'6':{


	echo '<script>';
   	  echo'document.getElementById("retencion_incluye_iva").readOnly = true;';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	  echo'document.getElementById("impuesto_municipal").readOnly = true;';
   	  echo'document.getElementById("impuesto_sobre_la_renta").readOnly = true;';
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

	$this->set('rcivil'                           , $rcivil);
	$this->set('retencion_multa_monto'            , $retencion_multa_monto);
	$this->set('rsocial'                          , $rsocial);
	$this->set('retencion_responsabilidad_social' , $retencion_responsabilidad_social);

}break;



}//fin switch







}//fin fucntion











function selecion($var1=null){

 $this->layout = "ajax";

 $this->Session->delete('monto_pago_acum');
 $this->Session->delete('monto_pago_acum_fc');

 $ano='';
 $datos_orden_pagos_anteriores     = "";
 $anticipo_incluye_iva             = "";
 $porcentaje_anticipo              = "0";
 $porcentaje_iva                   = "0";
 $rcivil                           = "0";
 $retencion_multa_monto            = "0";
 $rsocial                          = "0";
 $retencion_responsabilidad_social = "0";

 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');
 $lista = "";

 $ano=$this->ano_ejecucion();
 $this->set('ano',$ano);

 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.' and  numero_orden_compra='.$var1.'';
 $a = $this->cscd04_ordencompra_encabezado->findAll($condicion,null);

 $numero_datos=$a;
 $datos_cuerpo=$a;

foreach($a as $a_aux){
        $lista[$a_aux['cscd04_ordencompra_encabezado']['numero_orden_compra']]=$a_aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
}//fin foreach

$this->set('lista_numero', $lista);
$this->set('numero_orden_compra', $var1);
$this->Session->delete('PAG_NUM');
$this->set('ano',$ano);

if($var1==null){

$this->index();
$this->render('index');

}else{

 $mont_acu_pago=0;
 $mont_acu_pago_fc=0;

 $condicion_par = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  ano_orden_compra=".$ano." and  numero_orden_compra=".$var1." and (retencion_laboral + retencion_fielcumplimiento)!=0 ";
 $numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion_par, null, 'numero_orden_compra DESC');


foreach($numero_datos_partidas as $dpaux){
	$mont_acu_pago += $dpaux['cscd04_ordencompra_partidas']['retencion_laboral'];
    $mont_acu_pago_fc += $dpaux['cscd04_ordencompra_partidas']['retencion_fielcumplimiento'];
}

	$this->Session->write('monto_pago_acum', $mont_acu_pago);
	$this->Session->write('monto_pago_acum_fc', $mont_acu_pago_fc);



	$numero_datos_aux =  $numero_datos;

foreach($numero_datos_aux as $aux){
	$rif                                  =    $aux['cscd04_ordencompra_encabezado']['rif'];
	$ano_orden_compra                     =    $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
	$numero_orden_compra                  =    $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
	$laboral_cancelado                    =    $aux['cscd04_ordencompra_encabezado']['laboral_cancelado'];
	$fielcumplimiento_cancelado           =    $aux['cscd04_ordencompra_encabezado']['fielcumplimiento_cancelado'];
	$monto_retencion_laboral              =    $aux['cscd04_ordencompra_encabezado']['monto_retencion_laboral'];
	$monto_retencion_fielcumplimiento     =    $aux['cscd04_ordencompra_encabezado']['monto_retencion_fielcumplimiento'];
	$anticipo_incluye_iva                 =    $aux['cscd04_ordencompra_encabezado']['anticipo_con_iva'];
    $porcentaje_anticipo                  =    $aux['cscd04_ordencompra_encabezado']['porcentaje_anticipo'];
    $porcentaje_iva                       =    $aux['cscd04_ordencompra_encabezado']['porcentaje_iva'];
}//fin foreach

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
$opc     = $this->cscd04_ordencompra_retencion_cuerpo->findCount($condicion." and ano_orden_compra=".$ano_orden_compra."  and numero_orden_compra='".$numero_orden_compra."'   ");
$result  = $this->cscd04_ordencompra_retencion_cuerpo->findAll($condicion."   and ano_orden_compra=".$ano_orden_compra."  and numero_orden_compra=".$numero_orden_compra." ", null, "numero_retencion ASC", null, null);
foreach($result as $ves){$opc = $ves['cscd04_ordencompra_retencion_cuerpo']['numero_retencion'];}//fin foreach

$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");

$objeto_rif = "";
$denominacion_rif = "";
$direccion_comercial_rif = "";
$datos_contrato_obra_anteriores = "";
$datos_orden_anteriores_partidas = "";
$exento_islr_cooperativa = "";

foreach($rif_datos as $aux_2){
	$denominacion_rif = $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
	$objeto_rif = $aux_2['cpcd02']['objeto'];
	$exento_islr_cooperativa   = $aux_2['cpcd02']['exento_islr_cooperativa'];
}//fin foreach

if($objeto_rif != "" && $denominacion_rif!=""){

	$datos_contrato_obra_anteriores  = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($condicion."  and ano_orden_compra=".$ano_orden_compra."  and numero_orden_compra=".$numero_orden_compra."  and condicion_actividad=1", null, null, null, null);
	$datos_orden_anteriores_partidas = $this->cscd04_ordencompra_a_pago_partidas->findAll($condicion, null, 'numero_orden_compra DESC');

$opc++;

//////////***********************  PARAMETROS   **********************************///////////////

$factor_reversion = "";
$timbre_fiscal        = "0";
$desde_monto_timbre_fiscal   = "0";

 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
 $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);

    foreach($parametros_datos as $aux_22){




//GOB.GUARICO

$timbre_fiscal = 0;
$desde_monto_timbre_fiscal = 0;

//FIN GOB.GUARICO

//ORIGINAL
/*
      $timbre_fiscal        = $aux_22['cscd04_ordencompra_parametros']['porcentaje_timbre_fiscal'];
      $desde_monto_timbre_fiscal   = $aux_22['cscd04_ordencompra_parametros']['desde_monto_timbre'];
*/
//FIN ORIGINAL




    }//fin foreach


//////////***********************  FIN PARAMETROS   ******************************///////////////


$this->detalles_del_pago($objeto_rif, $ano_orden_compra, $numero_orden_compra);

$tipo_selecion_value = "";

if($laboral_cancelado==0 && $monto_retencion_laboral!=0  &&  $fielcumplimiento_cancelado==0 && $monto_retencion_fielcumplimiento!=0){

}else{

      if($laboral_cancelado==0 && $monto_retencion_laboral!=0 ){$this->tipo_retencion($ano_orden_compra, $numero_orden_compra, 1); $tipo_selecion_value="1";
}else if($fielcumplimiento_cancelado==0 && $monto_retencion_fielcumplimiento!=0){$this->tipo_retencion($ano_orden_compra, $numero_orden_compra, 2); $tipo_selecion_value="2";}

      }//fin else



$this->set('tipo_selecion_value', $tipo_selecion_value);


}else{
	$this->set('errorMessage', 'El rif del proveedor no existe');
	$this->set('desde_monto_timbre_fiscal' , '');
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
	$this->set('rcivil'                       , '');
	$this->set('rsocial'                      , '');

 echo'<script>';
  echo'document.getElementById("guardar").disabled = true; ';
 echo'</script>';
}//fin else

$this->set('desde_monto_timbre_fiscal', $desde_monto_timbre_fiscal);
$this->set('timbre_fiscal', $timbre_fiscal);
$this->set('porcentaje_iva', $porcentaje_iva);
$this->set('ano_orden_compra_pago', $ano);
$this->set('numero_retencion', $opc);
$this->set('datos_orden_compra', $numero_datos);
$this->set('datos_contrato_obra_partidas', $numero_datos_partidas);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('direccion_comercial_rif', $direccion_comercial_rif);
$this->set('datos_contrato_obra_anteriores', $datos_contrato_obra_anteriores);
$this->set('datos_orden_anteriores_partidas', $datos_orden_anteriores_partidas);
$this->set('rcivil'                           , $rcivil);
$this->set('retencion_multa_monto'            , $retencion_multa_monto);
$this->set('rsocial'                          , $rsocial);
$this->set('retencion_responsabilidad_social' , $retencion_responsabilidad_social);

   }//fin else



}//fin function










function eliminar($var1=null, $var2=null, $var3=null, $var4=null){

$this->layout = "ajax";
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

$opcion = "si";

      if($this->cscd04_ordencompra_retencion_cuerpo->execute("DELETE FROM cscd04_ordencompra_retencion_cuerpo WHERE ".$condicion."  and ano_orden_compra ='".$var1."' and numero_orden_compra ='".$var2."'   and numero_retencion ='".$var3."' ")>1){}else{$opcion = 'no';}//fin else
      if($this->cscd04_ordencompra_retencion_partidas->execute("DELETE FROM cscd04_ordencompra_retencion_partidas WHERE ".$condicion."  and ano_orden_compra ='".$var1."' and numero_orden_compra ='".$var2."'   and numero_retencion ='".$var3."' ")>1){}else{$opcion = 'no';}//fin else
      if($var4=="1"){
           if($this->cscd04_ordencompra_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set laboral_cancelado=0 WHERE ".$condicion."  and ano_orden_compra ='".$var1."' and numero_orden_compra ='".$var2."' ")>1){}else{$opcion = 'no';}//fin else
}else if($var4=="2"){
           if($this->cscd04_ordencompra_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set fielcumplimiento_cancelado=0 WHERE ".$condicion."  and ano_orden_compra ='".$var1."' and numero_orden_compra ='".$var2."' ")>1){}else{$opcion = 'no';}//fin else
}//fin else




if($opcion=="si"){$this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS');}

$this->index();
$this->render('index');

}//function








function consulta($pag_num=null, $numero_documento=null, $g=null){
  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $lista = "";
  if(!empty($this->data['cobd01_contratoobras_retencion']['ano_ejecucion'])){
	$ano = $this->data['cobd01_contratoobras_retencion']['ano_ejecucion'];
  }else{
  	$ano = $this->ano_ejecucion();
  }


  $this->set('ano_orden_compra_ejecucion', $this->ano_ejecucion());


  if($g=="si"){$this->set('Message_existe', 'Los datos fueron guardados correctamente');}
  if($g=="sii"){$this->set('Message_existe', 'El registro fue anulado correctamente');}



   if(isset($_SESSION['ano_orden_compra'])){$ano = $_SESSION['ano_orden_compra'];}else{$ano = $this->ano_ejecucion();}
   $this->set('ano_orden_compra', $ano);

    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

   $array = $this->cscd04_ordencompra_retencion_cuerpo->findAll($condicion. "  and numero_orden_compra='".$numero_documento."' and ano_orden_compra = ".$ano , null, 'ano_orden_compra, numero_orden_compra, numero_retencion ASC', null);


    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_orden_compra']    = $aux['cscd04_ordencompra_retencion_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra']  = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_retencion']      = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_retencion'];

 	$i++;

} $i--;


if(isset($numero[$pag_num]['numero_orden_compra'])){


$datos_cscd04_ordencompra_retencion_cuerpo          =     $this->cscd04_ordencompra_retencion_cuerpo->findAll($condicion."   and ano_orden_compra=".$numero[$pag_num]['ano_orden_compra']."  and numero_orden_compra=".$numero[$pag_num]['numero_orden_compra']."  and numero_retencion='".$numero[$pag_num]['numero_retencion']."'   ");
$datos_cscd04_ordencompra_retencion_partidas        =     $this->cscd04_ordencompra_retencion_partidas->findAll($condicion." and ano_orden_compra=".$numero[$pag_num]['ano_orden_compra']."  and numero_orden_compra=".$numero[$pag_num]['numero_orden_compra']."  and numero_retencion='".$numero[$pag_num]['numero_retencion']."'  ");
$datos_cscd04_ordencompra_encabezado                =     $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$numero[$pag_num]['ano_orden_compra']."  and numero_orden_compra=".$numero[$pag_num]['numero_orden_compra']."  ");

$numero_datos_aux =  $datos_cscd04_ordencompra_encabezado;
foreach($numero_datos_aux as $aux){
	$rif                   =   $aux['cscd04_ordencompra_encabezado']['rif'];
	$ano_orden_compra      =   $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
	$numero_orden_compra   =   $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
	$ano_estimacion        =   $aux['cscd04_ordencompra_encabezado']['ano_estimacion'];
	$cod_obra              =   $aux['cscd04_ordencompra_encabezado']['cod_obra'];
	$fecha_contrato_obra   =   $aux['cscd04_ordencompra_encabezado']['fecha_contrato_obra'];
	$denominacion_obra     =   $aux['cscd04_ordencompra_encabezado']['denominacion_obra'];
	$porcentaje_iva        =   $aux['cscd04_ordencompra_encabezado']['porcentaje_iva'];
}//fin foreach

$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){
	$denominacion_rif         =  $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif  =  $aux_2['cpcd02']['direccion_comercial'];
	$objeto_rif               =  $aux_2['cpcd02']['objeto'];
	$exento_islr_cooperativa   = $aux_2['cpcd02']['exento_islr_cooperativa'];

}//fin foreach

$this->set('datos_cscd04_ordencompra_retencion_cuerpo', $datos_cscd04_ordencompra_retencion_cuerpo);
$this->set('datos_cobd01_contratoobras_retencion_partidas', $datos_cscd04_ordencompra_retencion_partidas);
$this->set('datos_orden_compras', $datos_cscd04_ordencompra_encabezado);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('rif', $rif);
$this->set('direccion_comercial_rif', $direccion_comercial_rif);
$this->set('porcentaje_iva', $porcentaje_iva);
$this->set('ano_estimacion', $ano_estimacion);
$this->set('cod_obra', $cod_obra);
$this->set('fecha_contrato_obra', $fecha_contrato_obra);
$this->set('denominacion_obra', $denominacion_obra);

 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);

}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

}//fin else



}//fin function













function modificacion($var1=null, $g=null){


  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $lista = "";
  if(!empty($this->data['cobd01_contratoobras_retencion']['ano_ejecucion'])){
	$ano = $this->data['cobd01_contratoobras_retencion']['ano_ejecucion'];
  }else{
  	$ano = $this->ano_ejecucion();
  }


  if($g=="si"){$this->set('Message_existe', 'Los datos fueron guardados correctamente');}
  if($g=="sii"){$this->set('Message_existe', 'El registro fue anulado correctamente');}

   $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

	$pag_num ="";


if(isset($var1)){

   $array = $this->cscd04_ordencompra_retencion_cuerpo->findAll($condicion. " and ano_orden_compra = ".$ano."  and numero_orden_compra=".$var1." ",null, 'numero_retencion ASC', null);
       $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_orden_compra']    = $aux['cscd04_ordencompra_retencion_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_retencion']     = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_retencion'];
 	$i++;

} $i--;


}else{

     $array = "";
     $c = $this->cscd04_ordencompra_retencion_cuerpo->findAll($condicion. ' and ano_orden_compra = '.$ano , 'numero_orden_compra, ano_orden_compra', null, null);
     $sql_c = ""; //print_r($c);
foreach($c as $c_aux){
			if($sql_c == ""){
                          $sql_c  = "     numero_orden_compra='".$c_aux['cscd04_ordencompra_retencion_cuerpo']['numero_orden_compra']."'  and ano_orden_compra='".$c_aux['cscd04_ordencompra_retencion_cuerpo']['ano_orden_compra']."' ";
			}else{        $sql_c .= " or (numero_orden_compra='".$c_aux['cscd04_ordencompra_retencion_cuerpo']['numero_orden_compra']."'  and ano_orden_compra='".$c_aux['cscd04_ordencompra_retencion_cuerpo']['ano_orden_compra']."') ";		}//fin else
}//fin for
	if($sql_c!=""){
	  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' ';
	  $a = $this->cscd04_ordencompra_encabezado->findAll($condicion." and (".$sql_c.")");
	  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' ';

		foreach($a as $a_aux){
         $sql_c .= " or (numero_orden_compra='".$a_aux['cscd04_ordencompra_encabezado']['numero_orden_compra']."'  and ano_orden_compra='".$ano."') ";
		}//fin foreach
	}//fin if


if($sql_c!=""){
		$array = $this->cscd04_ordencompra_retencion_cuerpo->findAll($condicion." and (".$sql_c.")", 'DISTINCT ano_orden_compra, numero_orden_compra, numero_retencion ', 'ano_orden_compra, numero_orden_compra, numero_retencion ASC', null);
		$i = 0;
		 if($pag_num==null){$pag_num=0;}

		 foreach($array as $aux){

		 	$numero[$i]['ano_orden_compra']    = $aux['cscd04_ordencompra_retencion_cuerpo']['ano_orden_compra'];
		 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_orden_compra'];
		 	$numero[$i]['numero_retencion']     = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_retencion'];
		 	$i++;

		} $i--;
}//fin if





}//fin else




if(isset($numero[$pag_num]['numero_orden_compra'])){


$datos_cscd04_ordencompra_retencion_cuerpo          =     $this->cscd04_ordencompra_retencion_cuerpo->findAll($condicion."   and ano_orden_compra=".$numero[$pag_num]['ano_orden_compra']."  and numero_orden_compra=".$numero[$pag_num]['numero_orden_compra']."  and numero_retencion='".$numero[$pag_num]['numero_retencion']."'   ");
$datos_cscd04_ordencompra_retencion_partidas        =     $this->cscd04_ordencompra_retencion_partidas->findAll($condicion." and ano_orden_compra=".$numero[$pag_num]['ano_orden_compra']."  and numero_orden_compra=".$numero[$pag_num]['numero_orden_compra']."  and numero_retencion='".$numero[$pag_num]['numero_retencion']."'  ");
$datos_cscd04_ordencompra_encabezado                  =     $this->cscd04_ordencompra_encabezado->findAll($condicion."             and ano_orden_compra=".$numero[$pag_num]['ano_orden_compra']."  and numero_orden_compra=".$numero[$pag_num]['numero_orden_compra']."  ");


$numero_datos_aux =  $datos_cscd04_ordencompra_encabezado;
foreach($numero_datos_aux as $aux){
	$rif                   =   $aux['cobd01_contratoobras_cuerpo']['rif'];
	$ano_orden_compra      =   $aux['cobd01_contratoobras_cuerpo']['ano_orden_compra'];
	$numero_orden_compra   =   $aux['cobd01_contratoobras_cuerpo']['numero_orden_compra'];
	$ano_estimacion        =   $aux['cobd01_contratoobras_cuerpo']['ano_estimacion'];
	$cod_obra              =   $aux['cobd01_contratoobras_cuerpo']['cod_obra'];
	$fecha_contrato_obra   =   $aux['cobd01_contratoobras_cuerpo']['fecha_contrato_obra'];
	$denominacion_obra     =   $aux['cobd01_contratoobras_cuerpo']['denominacion_obra'];
	$porcentaje_iva        =   $aux['cobd01_contratoobras_cuerpo']['porcentaje_iva'];
}//fin foreach

$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){
	$denominacion_rif         =  $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif  =  $aux_2['cpcd02']['direccion_comercial'];
	$objeto_rif               =  $aux_2['cpcd02']['objeto'];
	$exento_islr_cooperativa   = $aux_2['cpcd02']['exento_islr_cooperativa'];
}//fin foreach





$this->set('datos_cscd04_ordencompra_retencion_cuerpo', $datos_cscd04_ordencompra_retencion_cuerpo);
$this->set('datos_cscd04_ordencompra_retencion_partidas', $datos_cscd04_ordencompra_retencion_partidas);
$this->set('datos_cscd04_ordencompra_encabezado', $datos_cscd04_ordencompra_encabezado);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('rif', $rif);
$this->set('porcentaje_iva', $porcentaje_iva);

$this->set('ano_estimacion', $ano_estimacion);
$this->set('cod_obra', $cod_obra);
$this->set('fecha_contrato_obra', $fecha_contrato_obra);
$this->set('denominacion_obra', $denominacion_obra);


 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);

}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

}//fin else















}//fin function












function guardar_modificacion(){


 $this->layout = "ajax";
 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep;



  $ano_orden_compra                        =       $this->data['cscp04_ordencompra_retencion']['ano_orden_compra'];
  $numero_orden_compra                     =       $this->data['cscp04_ordencompra_retencion']['numero_orden_compra'];
  $numero_retencion                        =       $this->data['cscp04_ordencompra_retencion']['numero_retencion'];
  $concepto                                =       $this->data['cscp04_ordencompra_retencion']['concepto'];
  $fecha_retencion                         =       $this->Cfecha($this->data['cscp04_ordencompra_retencion']['fecha_retencion'], 'A-M-D');
  $oficio_aprobacion                       =       $this->data['cscp04_ordencompra_retencion']['numero_aprobacion'];
  $fecha_aprobacion                        =       $this->Cfecha($this->data['cscp04_ordencompra_retencion']['fecha_aprobacion'], 'A-M-D');


						 $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_orden_compra=".$numero_orden_compra."  and  ano_orden_compra=".$ano_orden_compra." and numero_retencion='".$numero_retencion."' ";
						 $sql3  = " BEGIN; UPDATE cscd04_ordencompra_retencion_cuerpo SET concepto ='".$concepto."', fecha_retencion ='".$fecha_retencion."', fecha_aprobacion ='".$fecha_aprobacion."', oficio_aprobacion ='".$oficio_aprobacion."'  where ".$condicion.';';
						 $sw4 = $this->cscd04_ordencompra_retencion_cuerpo->execute($sql3);


                            if($sw4 > 1){
                                $this->cscd04_ordencompra_retencion_cuerpo->execute("COMMIT;");
                                $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
                           }else{
							    $this->cscd04_ordencompra_retencion_cuerpo->execute("ROLLBACK;");
								$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
							}//fin else



$this->consulta_index();
$this->render('consulta_index');


}//fin funtion















function tipo_retencion($var1=null, $var2=null, $var3=null){

 $this->layout = "ajax";
 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep;


 $monto_rentencion_iva     = 0;
 $monto_rentencion_sin_iva = 0;
 $monto_total_retencion    = 0;
 $monto_iva                = 0;
 $monto_rlaba     = 0;
 $monto_fcump     = 0;


 $datos_contrato_obra_anteriores     = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($condicion."  and ano_orden_compra=".$var1."  and numero_orden_compra=".$var2."  and condicion_actividad=1", null, null, null, null);


foreach($datos_contrato_obra_anteriores as $ve){

$monto_rlaba = $ve['cscd04_ordencompra_autorizacion_cuerpo']['monto_retencion_laboral'];
$monto_fcump = $ve['cscd04_ordencompra_autorizacion_cuerpo']['monto_retencion_fielcum'];

	    $numero_datos_partidas = $this->cscd04_ordencompra_a_pago_partidas->findAll($condicion."  and ano_orden_compra=".$var1."  and numero_orden_compra=".$var2."  and numero_pago = ".$ve['cscd04_ordencompra_autorizacion_cuerpo']['numero_pago']."  ", null, 'numero_orden_compra DESC');



	       if($var3=="1"){
/*
	                        if($ve['cscd04_ordencompra_autorizacion_cuerpo']['retencion_incluye_iva']=="1"){
	                        $monto_rentencion_iva     += $ve['cscd04_ordencompra_autorizacion_cuerpo']['monto_retencion_laboral'];
	                  }else if($ve['cscd04_ordencompra_autorizacion_cuerpo']['retencion_incluye_iva']=="2"){
	                  	    $monto_rentencion_sin_iva += $ve['cscd04_ordencompra_autorizacion_cuerpo']['monto_retencion_laboral'];
	                  }//fin else
*/
	                  $monto_rentencion_iva     += $monto_rlaba;

	 }else if($var3=="2"){
/*
	                        if($ve['cscd04_ordencompra_autorizacion_cuerpo']['retencion_incluye_iva']=="1"){
	                        $monto_rentencion_iva     += $ve['cscd04_ordencompra_autorizacion_cuerpo']['monto_retencion_fielcump'];
	                  }else if($ve['cscd04_ordencompra_autorizacion_cuerpo']['retencion_incluye_iva']=="2"){
	                  	    $monto_rentencion_sin_iva += $ve['cscd04_ordencompra_autorizacion_cuerpo']['monto_retencion_fielcump'];
	                  }//fin else
*/
	                  $monto_rentencion_iva     += $monto_fcump;

	           	}//fin else




	             foreach($numero_datos_partidas as $ve2){
							  $concate  = $this->AddCeroR2(substr($ve2['cscd04_ordencompra_a_pago_partidas']['cod_partida'], -2) , substr($ve2['cscd04_ordencompra_a_pago_partidas']['cod_partida'], 0, 1 )).'.'.$this->AddCeroR2($ve2['cscd04_ordencompra_a_pago_partidas']['cod_generica']).'.'.$this->AddCeroR2($ve2['cscd04_ordencompra_a_pago_partidas']['cod_especifica']).'.'.$this->AddCeroR2($ve2['cscd04_ordencompra_a_pago_partidas']['cod_sub_espec']);
							  $concate2 = $this->AddCeroR2(substr($ve2['cscd04_ordencompra_a_pago_partidas']['cod_partida'], -2) , substr($ve2['cscd04_ordencompra_a_pago_partidas']['cod_partida'], 0, 1 ));
     						  $ano                      =         $ve2['cscd04_ordencompra_a_pago_partidas']['ano'];
							  $cod_sector               =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_sector'];
							  $cod_programa             =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_programa'];
							  $cod_sub_prog             =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_sub_prog'];
							  $cod_proyecto             =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_proyecto'];
							  $cod_activ_obra           =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_activ_obra'];
							  $cod_partida              =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_partida'];
							  $cod_generica             =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_generica'];
							  $cod_especifica           =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_especifica'];
							  $cod_sub_espec            =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_sub_espec'];
							  $cod_auxiliar             =         $ve2['cscd04_ordencompra_a_pago_partidas']['cod_auxiliar'];

//ORIGINAL

/*
                                     if($var3=="1"){
							            if($concate=="4.03.18.01.00"){ $monto_iva += $ve2['cscd04_ordencompra_a_pago_partidas']['retencion_laboral']; }
							   }else if($var3=="2"){
							   	         if($concate=="4.03.18.01.00"){$monto_iva += $ve2['cscd04_ordencompra_a_pago_partidas']['monto_retencion_fielcum'];  }
                               }//fin esle
*/

//FIN ORIGINAL



//GOB.GUARICO

			                       $monto_iva = 0;

//FIN GOB.GUARICO



	             }//fin foreach



}//fin foreach


       if($var3=="1"){
			echo"<script>";
			echo"for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){";
			echo " if(ii==0 && document.getElementById('monto_act_rete') && document.getElementById('monto_act_rete').value!=''){
							document.getElementById('pago_'+ii).value = document.getElementById('monto_act_rete').value;
							moneda('pago_'+ii);
					}else{ ";
			echo" document.getElementById('pago_'+ii).value = document.getElementById('monto_partida_laboral_'+ii).value;";
			echo"if(document.getElementById('partida_iva_'+ii)){document.getElementById('partida_iva_'+ii).value = document.getElementById('monto_partida_laboral_'+ii).value;}";
			echo"moneda('pago_'+ii);";
			echo" document.getElementById('pago_'+ii).disabled = false;";
			echo" document.getElementById('pago_'+ii).readOnly = true;";
			echo"}}";
			echo "</script>";

 }else if($var3=="2"){
 	        echo"<script>";
			echo"for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){";
			echo " if(ii==0 && document.getElementById('monto_act_reted') && document.getElementById('monto_act_reted').value!=''){
							document.getElementById('pago_'+ii).value = document.getElementById('monto_act_reted').value;
							moneda('pago_'+ii);
					}else{ ";
			echo" document.getElementById('pago_'+ii).value = document.getElementById('monto_partida_fiel_cumplimiento_'+ii).value;";
			echo"if(document.getElementById('partida_iva_'+ii)){document.getElementById('partida_iva_'+ii).value = document.getElementById('monto_partida_fiel_cumplimiento_'+ii).value;}";
     		echo"moneda('pago_'+ii);";
			echo" document.getElementById('pago_'+ii).disabled = false;";
			echo" document.getElementById('pago_'+ii).readOnly = true;";
     		echo"}}";
			echo "</script>";
 }//fin else


$monto_total_retencion = $monto_rentencion_sin_iva + $monto_rentencion_iva;

echo"<script>";
 echo'document.getElementById("monto_retencion_iva").value      = "'.$this->Formato2($monto_rentencion_iva).'" ; ';
 echo'document.getElementById("monto_retencion_sin_iva").value  = "'.$this->Formato2($monto_rentencion_sin_iva).'";  ';
 echo'document.getElementById("monto_total_retencion").value    = "'.$this->Formato2($monto_total_retencion).'";  ';
 echo'document.getElementById("monto_a_pagar_con_iva").value    = "'.$this->Formato2($monto_total_retencion).'";  ';
 echo'document.getElementById("TOTALINGRESOS").innerHTML        = "'.$this->Formato2($monto_total_retencion).'";  ';
 echo'document.getElementById("monto_iva").value                = "'.$this->Formato2($monto_iva).'";  ';
echo "</script>";


	$monto_total_retencion22 = $this->Formato2($monto_total_retencion);
	$monto_total_retencion22 = $this->Formato1($monto_total_retencion22);
	$monto_pago_a = 0;

	if($var3=="1"){
		$monto_pago_a = $this->Session->read('monto_pago_acum');
		$monto_pago_a = $this->Formato2($monto_pago_a);
		$monto_pago_a = $this->Formato1($monto_pago_a);
		$campo_upd = "retencion_laboral";
	}else if($var3=="2"){
		$monto_pago_a = $this->Session->read('monto_pago_acum_fc');
		$monto_pago_a = $this->Formato2($monto_pago_a);
		$monto_pago_a = $this->Formato1($monto_pago_a);
		$campo_upd = "retencion_fielcumplimiento";
	}


if($monto_pago_a!=0 && $monto_pago_a != $monto_total_retencion22){

	$monto_pago_orig = $monto_pago_a;
	$ccentims = 0;

while($monto_pago_a != $monto_total_retencion22){

	if($monto_pago_a == $monto_total_retencion22){
		break;
	}else if($monto_pago_a > $monto_total_retencion22){
		$monto_pago_a -= 0.01;
		$ccentims -= 0.01;
	}else if($monto_pago_a < $monto_total_retencion22){
		$monto_pago_a += 0.01;
		$ccentims += 0.01;
	}

	$monto_pago_a = $this->Formato2($monto_pago_a);
	$monto_pago_a = $this->Formato1($monto_pago_a);
} // fin while


	  $aux_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion." and ano_orden_compra=".$var1." and numero_orden_compra=".$var2." and (retencion_laboral + retencion_fielcumplimiento)!=0", null, 'numero_orden_compra DESC');

	if(!empty($aux_partidas)){
	  $ano_contrato_obra_r = $aux_partidas[0]['cscd04_ordencompra_partidas']['ano_orden_compra'];
	  $num_contrato_obra_r = $aux_partidas[0]['cscd04_ordencompra_partidas']['numero_orden_compra'];
	  $ano_partidas_r      = $aux_partidas[0]['cscd04_ordencompra_partidas']['ano'];
	  $cod_sector_r        = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_sector'];
	  $cod_programa_r      = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_programa'];
	  $cod_sub_prog_r      = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_sub_prog'];
	  $cod_proyecto_r      = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_proyecto'];
	  $cod_activ_obra_r    = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_activ_obra'];
	  $cod_partida_r       = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_partida'];
	  $cod_generica_r      = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_generica'];
	  $cod_especifica_r    = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_especifica'];
	  $cod_sub_espec_r     = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_sub_espec'];
	  $cod_auxiliar_r      = $aux_partidas[0]['cscd04_ordencompra_partidas']['cod_auxiliar'];

	$cond_partds = " and ano_orden_compra='$ano_contrato_obra_r' and numero_orden_compra='$num_contrato_obra_r' and ano='$ano_partidas_r' and cod_sector='$cod_sector_r' and cod_programa='$cod_programa_r' and cod_sub_prog='$cod_sub_prog_r' and cod_proyecto='$cod_proyecto_r' and cod_activ_obra='$cod_activ_obra_r' and cod_partida='$cod_partida_r' and cod_generica='$cod_generica_r' and cod_especifica='$cod_especifica_r' and cod_sub_espec='$cod_sub_espec_r' and cod_auxiliar='$cod_auxiliar_r'";
	$sql_upd = "UPDATE cscd04_ordencompra_partidas SET $campo_upd = $campo_upd + ($ccentims) WHERE ".$condicion.$cond_partds.";";
	$swupd = $this->cscd04_ordencompra_partidas->execute($sql_upd);

	if($swupd > 1){

		$montot_actualizado = ($monto_pago_orig+($ccentims));

		if($var3=="1"){
			$this->Session->delete('monto_pago_acum');
			$this->Session->write('monto_pago_acum', $montot_actualizado);

			echo "<script> var suma1=0; var suma2=0; var suma=0;
      					if(document.getElementById('monto_partida_laboral_0')){ suma1 = document.getElementById('monto_partida_laboral_0').value;}
      					suma2 = $ccentims;
      					suma = eval(suma1)+eval(suma2);
      					suma = redondear(suma, 2);
						if(document.getElementById('m_total_pact_0')){
							document.getElementById('m_total_pact_0').innerHTML = '".$this->Formato2($montot_actualizado+$this->Session->read('monto_pago_acum_fc'))."';
						}
						if(document.getElementById('pago_0')){
							document.getElementById('pago_0').value = suma;
							moneda('pago_0');
						}
						if(document.getElementById('monto_act_rete')){
							document.getElementById('monto_act_rete').value = '$montot_actualizado';
						}
				</script>";

		}else if($var3=="2"){
			$this->Session->delete('monto_pago_acum_fc');
			$this->Session->write('monto_pago_acum_fc', $montot_actualizado);

			echo "<script> var suma1=0; var suma2=0; var suma=0; var sumamact=0;
      					if(document.getElementById('monto_partida_fiel_cumplimiento_0')){ suma1 = document.getElementById('monto_partida_fiel_cumplimiento_0').value;}
      					suma2 = $ccentims;
      					suma = eval(suma1)+eval(suma2);
      					suma = redondear(suma, 2);
						if(document.getElementById('m_total_pact_0')){
							document.getElementById('m_total_pact_0').innerHTML = '".$this->Formato2($montot_actualizado+$this->Session->read('monto_pago_acum'))."';
						}
						if(document.getElementById('pago_0')){
							document.getElementById('pago_0').value = suma;
							moneda('pago_0');
						}
						if(document.getElementById('monto_act_reted')){
							document.getElementById('monto_act_reted').value = '$montot_actualizado';
						}
				</script>";

		}
	} // fin if Actualizacion, Update...
	} // fin no vacio partidas

} // fin if monto pago != 0


$this->opcion_pago();


}//fin function











function  opcion_pago(){

 $this->layout = "ajax";

 echo"<script>cobp01_contratoobras_retencion_detalles_del_pago();</script>";


}//function




function buscar_year($var1=null){

 $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $lista = "";
  $ano = $this->ano_ejecucion();


			$c = $this->cscd04_ordencompra_retencion_cuerpo->findAll($this->condicion().' and ano_orden_compra='.$var1, 'numero_orden_compra, ano_orden_compra', null, null);
			$sql_c = ""; //print_r($c);
			foreach($c as $c_aux){
						if($sql_c == ""){
			                          $sql_c  = "     numero_orden_compra='".$c_aux['cscd04_ordencompra_retencion_cuerpo']['numero_orden_compra']."'  and ano_orden_compra='".$c_aux['cscd04_ordencompra_retencion_cuerpo']['ano_orden_compra']."' ";
						}else{        $sql_c .= " or (numero_orden_compra='".$c_aux['cscd04_ordencompra_retencion_cuerpo']['numero_orden_compra']."'  and ano_orden_compra='".$c_aux['cscd04_ordencompra_retencion_cuerpo']['ano_orden_compra']."') ";		}//fin else
			}//fin for

			if($sql_c!=""){
				  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' ';
				  $a = $this->cscd04_ordencompra_encabezado->findAll($condicion." and (".$sql_c.")");

				foreach($a as $a_aux){
				      if($a_aux['cscd04_ordencompra_encabezado']['ano_estimacion']==$ano && $a_aux['cscd04_ordencompra_encabezado']['cod_obra']==$var1){
				        $lista[$a_aux['cscd04_ordencompra_encabezado']['numero_orden_compra']]=$a_aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
				     }//fin if
				}//fin foreach

			}//fin if
			$this->set('obras', $lista);
			$this->set('ano',$var1);


}//fin function




function consulta_index($var1=null){
  $this->layout = "ajax";
  $pag_num = 0;
  $opcion = 'no';
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';



if(!empty($this->data['cscp04_ordencompra_retencion']['ano_ejecucion'])){	$_SESSION['ano_orden_compra'] = $this->data['cscp04_ordencompra_retencion']['ano_ejecucion'];}else{$_SESSION['ano_orden_compra'] = $this->ano_ejecucion();}
$ano = $_SESSION['ano_orden_compra'];

if($var1!=null){
  if($var1=='si'){

if(!empty($this->data['cscp04_ordencompra_retencion']['numero_orden_compra'])){

    $array = $this->cscd04_ordencompra_retencion_cuerpo->findAll($condicion. " and ano_orden_compra = ".$ano, null, 'ano_orden_compra, numero_orden_compra, numero_retencion ASC', null);
    $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_orden_compra']      = $aux['cscd04_ordencompra_retencion_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra']   = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_retencion']      = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_retencion'];
 	$i++;
} $i--;

  for($a=0; $a<=$i; $a++){

    if($this->data['cscp04_ordencompra_retencion']['numero_orden_compra'] == $numero[$a]['numero_orden_compra']){
    	  $pag_num = 0;
    	  $opcion='si';
    	  $numero_documento = $numero[$a]['numero_orden_compra'];
    	  break;
    }else{$pag_num = 0;
    	  $opcion='si';
    	  $numero_documento = $numero[0]['numero_orden_compra']; }
   }//fin for

      if($opcion=='si'){$this->consulta($pag_num, $numero_documento);$this->render('consulta');
}else if($opcion=='no'){
	$this->set('errorMessage', 'No existen datos');
	$this->consulta_index();$this->render('consulta_index');
	}//fin else

}else{

	$array = $this->cscd04_ordencompra_retencion_cuerpo->findAll($condicion. " and ano_orden_compra = ".$ano , null, 'ano_orden_compra, numero_orden_compra, numero_retencion ASC', null);
  $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_orden_compra']    = $aux['cscd04_ordencompra_retencion_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_retencion']      = $aux['cscd04_ordencompra_retencion_cuerpo']['numero_retencion'];
 	$i++;
} $i--;

 if($i<=0){
	  $this->set('errorMessage', 'No existen datos');
	  $this->consulta_index();
	  $this->render('consulta_index');

	}else{
	  $this->consulta(0, $numero[0]['numero_orden_compra']); $this->render('consulta'); }   }//fin else

  }//fin if
}//fin i




 		$lista = $this->select_orden_compra_retenciones->generateList($condicion.' and ano_orden_compra='.$ano,' numero_orden_compra ASC', null, '{n}.select_orden_compra_retenciones.numero_orden_compra', '{n}.select_orden_compra_retenciones.beneficiario');

		 $this->set('obras', $lista);
		 $this->set('ano', $_SESSION['ano_orden_compra']);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function





function guardar(){
  $this->layout = "ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion                =       'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$cod_dep;
  $i_lenght                 =       $this->data['cobp01_contratoobras_retencion']['cuenta_i'];

  $porcentaje_iva                          =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['porcentaje_iva']);
  $ano_orden_compra                        =       $this->data['cscp04_ordencompra_retencion']['ano_orden_compra'];
  $numero_orden_compra                     =       $this->data['cscp04_ordencompra_retencion']['num_orden_compra'];
  $numero_retencion                        =       $this->data['cscp04_ordencompra_retencion']['numero_retencion'];
  $tipo_retencion                          =       $this->data['cscp04_ordencompra_retencion']['tipo_retencion'];
  $monto_original_contrato                 =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['monto_contrato']);
  $aumento                                 =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['aumento']);
  $disminucion                             =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['disminucion']);
  $monto_anticipo                          =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['monto_anticipo']);
  $monto_amortizacion                      =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['monto_amortizacion']);
  $monto_iva                               =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['monto_iva']);
  $monto_retenido_laboral                  =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['retencion_laboral']);
  $monto_retenido_fielcumplimiento         =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['fiel_cumplimiento']);
  $monto_cancelado                         =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['monto_cancelado']);
  $monto_retenido_coniva                   =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['monto_retencion_iva']);
  $monto_retenido_siniva                   =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['monto_retencion_sin_iva']);
  $monto_descontar_impuesto                =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['total_retencion_monto_iva']);
  $monto_orden_pago                        =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['monto_orden_de_pago_monto_iva']);
  $monto_retencion_iva                     =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['retencion_incluye_iva_monto_iva']);
  $porcentaje_retencion_iva                =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['retencion_incluye_iva']);
  $monto_islr                              =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['impuesto_sobre_la_renta_monto_iva']);
  $porcentaje_islr                         =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['impuesto_sobre_la_renta']);
  $monto_sustraendo                        =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['sustraendo']);
  $monto_timbre_fiscal                     =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['timbre_fiscal_monto_iva']);
  $porcentaje_timbre_fiscal                =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['timbre_fiscal']);

  $rcivil                                  =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['rcivil']);
  $retencion_multa_monto                   =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['retencion_multa_monto']);
  $rsocial                                 =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['rsocial']);
  $retencion_responsabilidad_social        =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['retencion_responsabilidad_social']);

  $monto_impuesto_municipal                =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['impuesto_municipal_monto_iva']);
  $porcentaje_impuesto_municipal           =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['impuesto_municipal']);
  $monto_neto_cobrar                       =       $this->Formato1($this->data['cscp04_ordencompra_retencion']['monto_a_pagar_monto_iva']);
  $concepto                                =       $this->data['cscp04_ordencompra_retencion']['concepto'];
  $fecha_retencion                         =       $this->Cfecha($this->data['cscp04_ordencompra_retencion']['fecha_retencion'], 'A-M-D');
  $oficio_aprobacion                       =       $this->data['cscp04_ordencompra_retencion']['numero_aprobacion'];
  $fecha_aprobacion                        =       $this->Cfecha($this->data['cscp04_ordencompra_retencion']['fecha_aprobacion'], 'A-M-D');
  $dia_asiento_registro                    =       '0';
  $mes_asiento_registro                    =       '0';
  $ano_asiento_registro                    =       '0';
  $numero_asiento_registro                 =       '0';
  $username_registro                       =       $this->Session->read('nom_usuario');
  $fecha_proceso_registro                  =       date('Y-m-d');
  $condicion_actividad                     =       '1';
  $ano_anulacion                           =       '0';
  $numero_anulacion                        =       '0';
  $dia_asiento_anulacion                   =       '0';
  $mes_asiento_anulacion                   =       '0';
  $ano_asiento_anulacion                   =       '0';
  $numero_asiento_anulacion                =       '0';
  $fecha_proceso_anulacion                 =       '01/01/1900';
  $username_anulacion                      =       '0';
  $ano_orden_pago                          =       '0';
  $numero_orden_pago                       =       '0';

$porcenta_iva = $porcentaje_iva;

$sql =" BEGIN; INSERT INTO cscd04_ordencompra_retencion_cuerpo (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra, numero_retencion, tipo_retencion,  monto_original_contrato, aumento, disminucion, monto_anticipo, monto_amortizacion, monto_iva, monto_retenido_laboral, monto_retenido_fielcumplimiento, monto_cancelado,  monto_retenido_coniva , monto_retenido_siniva, monto_descontar_impuesto, monto_orden_pago, monto_retencion_iva, porcentaje_retencion_iva, monto_islr, porcentaje_islr,  monto_sustraendo, monto_timbre_fiscal, porcentaje_timbre_fiscal, monto_impuesto_municipal, porcentaje_impuesto_municipal, monto_neto_cobrar, concepto, fecha_retencion, oficio_aprobacion, fecha_aprobacion, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, username_registro, fecha_proceso_registro, condicion_actividad, ano_anulacion, numero_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, ano_asiento_anulacion, numero_asiento_anulacion, fecha_proceso_anulacion, username_anulacion, ano_orden_pago, numero_orden_pago, porcenta_iva, codigo_retencion_islr, cod_actividad, porcentaje_multa, retencion_multa, porcentaje_responsabilidad, retencion_responsabilidad) VALUES ";
$sql.=" ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$numero_retencion."', '".$tipo_retencion."', '".$monto_original_contrato."', '".$aumento."', '".$disminucion."', '".$monto_anticipo."', '".$monto_amortizacion."', '".$monto_iva."', '".$monto_retenido_laboral."', '".$monto_retenido_fielcumplimiento."', '".$monto_cancelado."', '".$monto_retenido_coniva."', '".$monto_retenido_siniva."', '".$monto_descontar_impuesto."', '".$monto_orden_pago."', '".$monto_retencion_iva."', '".$porcentaje_retencion_iva."', '".$monto_islr."', '".$porcentaje_islr."', '".$monto_sustraendo."', '".$monto_timbre_fiscal."', '".$porcentaje_timbre_fiscal."', '".$monto_impuesto_municipal."', '".$porcentaje_impuesto_municipal."', '".$monto_neto_cobrar."', '".$concepto."', '".$fecha_retencion."', '".$oficio_aprobacion."', '".$fecha_aprobacion."', '".$dia_asiento_registro."', '".$mes_asiento_registro."', '".$ano_asiento_registro."', '".$numero_asiento_registro."', '".$username_registro."', '".$fecha_proceso_registro."', '".$condicion_actividad."', '".$ano_anulacion."', '".$numero_anulacion."', '".$dia_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$ano_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$fecha_proceso_anulacion."', '".$username_anulacion."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$porcenta_iva."', '".$_SESSION["ventana_islr"]."', '".$_SESSION["ventana_impuesto_municipal"]."', '".$rcivil."', '".$retencion_multa_monto."', '".$rsocial."', '".$retencion_responsabilidad_social."')  ";
$sw  = $this->cscd04_ordencompra_retencion_cuerpo->execute($sql);

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_orden_compra=".$numero_orden_compra."  and  ano_orden_compra=".$ano_orden_compra." ";
$condicion_par = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_orden_compra=".$numero_orden_compra."  and  ano_orden_compra=".$ano_orden_compra." and (retencion_laboral + retencion_fielcumplimiento)!=0 ";

if($sw>1){
   $a = 0;
   $i_aux = 0;
   $numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion_par, null, 'numero_orden_compra DESC');

	foreach($numero_datos_partidas as $aux_partidas){
		  $cod_presi2[$a]                 =   $aux_partidas['cscd04_ordencompra_partidas']['cod_presi'];
		  $cod_presiA=$cod_presi2[$a];
		  $cod_entidad2[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_entidad'];
		  $cod_entidadA=$cod_entidad2[$a];
		  $cod_tipo_inst2[$a]             =   $aux_partidas['cscd04_ordencompra_partidas']['cod_tipo_inst'];
		  $cod_tipo_instA                 = $cod_tipo_inst2[$a];
		  $cod_inst2[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['cod_inst'];
		  $cod_instA =$cod_inst2[$a];
		  $cod_dep2[$a]                   =   $aux_partidas['cscd04_ordencompra_partidas']['cod_dep'];
		  $cod_depA = $cod_dep2[$a];
		  $ano_orden_compra3[$a]          =   $aux_partidas['cscd04_ordencompra_partidas']['ano_orden_compra'];
		  $ano_orden_compraA              = $ano_orden_compra3[$a];
		  $num_orden_compra3[$a]          =   $aux_partidas['cscd04_ordencompra_partidas']['numero_orden_compra'];
		  $num_orden_compraA              = $num_orden_compra3[$a];
		  $ano_partidas[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['ano'];
		  $ano_partidasA   = $ano_partidas[$a];
		  $cod_sector[$a]                 =   $aux_partidas['cscd04_ordencompra_partidas']['cod_sector'];
		  $cod_sectorA     = $cod_sector[$a];
		  $cod_programa[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_programa'];
		  $cod_programaA   = $cod_programa[$a];
		  $cod_sub_prog[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_sub_prog'];
		  $cod_sub_progA   = $cod_sub_prog[$a];
		  $cod_proyecto[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_proyecto'];
		  $cod_proyectoA   = $cod_proyecto[$a];
		  $cod_activ_obra[$a]             =   $aux_partidas['cscd04_ordencompra_partidas']['cod_activ_obra'];
		  $cod_activ_obraA = $cod_activ_obra[$a];
		  $cod_partida[$a]                =   $aux_partidas['cscd04_ordencompra_partidas']['cod_partida'];
		  $cod_partidaA = $cod_partida[$a];
		  $cod_generica[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_generica'];
		  $cod_genericaA = $cod_generica[$a];
		  $cod_especifica[$a]             =   $aux_partidas['cscd04_ordencompra_partidas']['cod_especifica'];
		  $cod_especificaA = $cod_especifica[$a];
		  $cod_sub_espec[$a]              =   $aux_partidas['cscd04_ordencompra_partidas']['cod_sub_espec'];
		  $cod_sub_especA = $cod_sub_espec[$a];
		  $cod_auxiliar[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['cod_auxiliar'];
		  $cod_auxiliarA = $cod_auxiliar[$a];
		  $monto2[$a]                     =   $aux_partidas['cscd04_ordencompra_partidas']['monto'];
		  $aumento2[$a]                   =   $aux_partidas['cscd04_ordencompra_partidas']['aumento'];
		  $disminucion2[$a]               =   $aux_partidas['cscd04_ordencompra_partidas']['disminucion'];
		  $monto_total_partida[$a]        = $monto2[$a] + ($aumento2[$a] - $disminucion2[$a]);

		  $anticipo2[$a]                     =   $aux_partidas['cscd04_ordencompra_partidas']['anticipo'];
		  $amortizacion2[$a]                 =   $aux_partidas['cscd04_ordencompra_partidas']['amortizacion'];
		  $cancelacion2[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['cancelacion'];
		  $retencion_laboral2[$a]            =   $aux_partidas['cscd04_ordencompra_partidas']['retencion_laboral'];
		  $retencion_fielcumplimiento2[$a]   =   $aux_partidas['cscd04_ordencompra_partidas']['retencion_fielcumplimiento'];
		  $numero_control_compromiso[$a]     =   $aux_partidas['cscd04_ordencompra_partidas']['numero_asiento_compromiso'];

			$sql_where[$a] = "cod_presi=$cod_presiA and cod_entidad=$cod_entidadA and cod_tipo_inst=$cod_tipo_instA and cod_inst=$cod_instA and cod_dep=$cod_depA and ano_orden_compra=$ano_orden_compraA and numero_orden_compra=$num_orden_compraA and ano=$ano_partidasA and cod_sector=$cod_sectorA and cod_programa=$cod_programaA and cod_sub_prog=$cod_sub_progA and cod_proyecto=$cod_proyectoA and cod_activ_obra=$cod_activ_obraA and cod_partida=$cod_partidaA and cod_generica=$cod_genericaA and cod_especifica=$cod_especificaA and cod_sub_espec=$cod_sub_especA and cod_auxiliar=$cod_auxiliarA";
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
			   	if($partidas_aux==$this->data['partidas']['partidas_'.$i]){
			   		$partidas_vista['pago_'.$i_aux] = $this->data['cobp01_contratoobras_retencion']['pago_'.$i]; $i_aux++;
			   	}
			}//fin foreach

		}//fin foreach


		for($i=0; $i<$i_lenght; $i++){      $var[$i]['pago'] = $partidas_vista['pago_'.$i];
	         if($var[$i]['pago']!="0,00"){  $var[$i]['pago'] = $this->Formato1($var[$i]['pago']);


              $sql2  ="INSERT INTO cscd04_ordencompra_retencion_partidas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra, numero_retencion, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec,  cod_auxiliar, monto, numero_control_compromiso, numero_control_causado) ";
	          $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_orden_compra."', '".$num_orden_compra3[$i]."', '".$numero_retencion."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['pago']."', '".$numero_control_compromiso[$i]."', '0'); ";
	          $sw2 =  $this->cscd04_ordencompra_retencion_partidas->execute($sql2);
	            if($sw2 > 1){}else{break;}


	         }//fin if
		}//fin for


		if($sw2 > 1){


                        if($tipo_retencion=="1"){
                            $sql3  = "UPDATE cscd04_ordencompra_encabezado SET laboral_cancelado  ='1'  where ".$condicion.';';
                  }else if($tipo_retencion=="2"){
                            $sql3  = "UPDATE cscd04_ordencompra_encabezado SET fielcumplimiento_cancelado ='1'  where ".$condicion.';';
                     }//fin if
                           $sw4 = $this->cscd04_ordencompra_encabezado->execute($sql3);
                            if($sw4 > 1){
                               $this->cscd04_ordencompra_encabezado->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
                           }else{
							    $this->cscd04_ordencompra_retencion_cuerpo->execute("ROLLBACK;");
								$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
							}//fin else
			}else{
			    $this->cscd04_ordencompra_retencion_cuerpo->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
			 }//fin else
}else{
    $this->cscd04_ordencompra_retencion_cuerpo->execute("ROLLBACK;");
	$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
}//fin else


$this->index();
$this->render('index');


}//fin function guardar



 }//fin class

 ?>