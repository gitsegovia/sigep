<?php


 class Cobp01ContratoobrasRetencionController extends AppController{
	var $name = 'cobp01_contratoobras_retencion';
	var $uses = array('cobd01_contratoobras_retencion_cuerpo','cobd01_contratoobras_retencion_partidas',
	                  'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas', 'ccfd04_cierre_mes',
	                  'cpcd02', 'cfpd22_numero_asiento_causado', 'cfpd22', 'cfpd05', 'cugd04', 'cfpd07_obras_cuerpo',
	                  'cobd01_contratoobras_valuacion_cuerpo', 'cobd01_contratoobras_valuacion_partidas', 'cscd04_ordencompra_parametros',

	                        'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'v_cobd01_contratoobras_retencion', 'cepd01_compromiso_partidas', 'cepd03_ordenpago_numero', 'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_partidas', 'cepd03_ordenpago_poremitir'
	                 );
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

function index(){


$this->layout = "ajax";

	$this->Session->delete('monto_pago_acum');
	$this->Session->delete('monto_pago_acum_fc');

 $this->data=null;
 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');
 $lista = "";

 $ano='';
 $ano=$this->ano_ejecucion();
 $this->set('ano',$ano);

/*
Condicion original
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ((monto_original_contrato+(aumento-disminucion))-(monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))=0 and ((laboral_cancelado=0 and monto_retencion_laboral!=0) or (fielcumplimiento_cancelado=0 and monto_retencion_fielcumplimiento!=0)) and ano_contrato_obra='.$ano.' and condicion_actividad=1';
 */

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ((monto_original_contrato+(aumento-disminucion))-(monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))=0 and ((laboral_cancelado=0 and monto_retencion_laboral!=0) or (fielcumplimiento_cancelado=0 and monto_retencion_fielcumplimiento!=0)) and ano_contrato_obra='.$ano.' and condicion_actividad=1';

$a = $this->cobd01_contratoobras_cuerpo->findAll($condicion.' and (monto_retencion_laboral!=0 or monto_retencion_fielcumplimiento!=0) and (fielcumplimiento_cancelado=0 or laboral_cancelado=0)');

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.'   and ano_estimacion='.$ano;
$b = $this->cfpd07_obras_cuerpo->findAll($condicion);
foreach($a as $a_aux){
   foreach($b as $b_aux){
      if($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion']==$b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] &&  strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra'])==strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])){
        $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']]=$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
     }//fin if
  }//fin foreach
}//fin foreach




$this->set('lista_numero', $lista);
$this->set('ano',$ano);
$this->Session->delete('PAG_NUM');





}//fin fin







function filtra_obra($year=null){



$cod_dep                  =       $this->Session->read('SScoddep');
$SScoddeporig             =       $this->Session->read('SScoddeporig');
$Modulo                   =       $this->Session->read('Modulo');



$sql_obra = "";

  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else

$a = $this->cobd01_contratoobras_cuerpo->findAll($condicion." and ano_contrato_obra=".$year);



  if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.' ';
}//fin else

$b = $this->cfpd07_obras_cuerpo->findAll($condicion." and ano_estimacion=".$year);



foreach($a as $a_aux){
   foreach($b as $b_aux){
      if($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion']==$b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] &&  strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra'])==strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])){
         if($sql_obra==""){
         	    $sql_obra .= "    upper(numero_contrato_obra)='".strtoupper($a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'])."' ";
         }else{ $sql_obra .= " or upper(numero_contrato_obra)='".strtoupper($a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'])."' ";  }
     }//fin if
  }//fin foreach
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

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$numero_orden_compra."'  and  ano_contrato_obra=".$ano_orden_compra." ";
$numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);

$numero_datos_aux =  $numero_datos;

foreach($numero_datos_aux as $aux){
	$rif                          =  $aux['cobd01_contratoobras_cuerpo']['rif'];
	$ano_contrato_obra            =  $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
	$numero_contrato_obra         =  $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
	$porcentaje_anticipo          =  $aux['cobd01_contratoobras_cuerpo']['porcentaje_anticipo'];
	$anticipo_con_iva             =  $aux['cobd01_contratoobras_cuerpo']['anticipo_con_iva'];
	$monto_original_contrato      =  $aux['cobd01_contratoobras_cuerpo']['monto_original_contrato'];
    $modificacion_aumento         =  $aux['cobd01_contratoobras_cuerpo']['aumento'];
    $modificacion_disminucion     =  $aux['cobd01_contratoobras_cuerpo']['disminucion'];
    $monto_retencion_laboral              =  $aux['cobd01_contratoobras_cuerpo']['monto_retencion_laboral'];
    $monto_retencion_fielcumplimiento     =  $aux['cobd01_contratoobras_cuerpo']['monto_retencion_fielcumplimiento'];
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

 $ano='';
 $ano=$this->ano_ejecucion();
 $this->set('ano',$ano);


$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ((monto_original_contrato+(aumento-disminucion))-(monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))=0 and ((laboral_cancelado=0 and monto_retencion_laboral!=0) or (fielcumplimiento_cancelado=0 and monto_retencion_fielcumplimiento!=0)) and ano_contrato_obra='.$ano.' and condicion_actividad=1';


$a = $this->cobd01_contratoobras_cuerpo->findAll($condicion.' and (monto_retencion_laboral!=0 or monto_retencion_fielcumplimiento!=0) and (fielcumplimiento_cancelado=0 or laboral_cancelado=0)');

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.'   and ano_estimacion='.$ano;
$b = $this->cfpd07_obras_cuerpo->findAll($condicion);

foreach($a as $a_aux){
   foreach($b as $b_aux){
      if($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion']==$b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] &&  strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra'])==strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])){
        $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']]=$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
     }//fin if
  }//fin foreach
}//fin foreach


$this->set('lista_numero', $lista);
$this->set('numero_contrato_obra', $var1);
$this->Session->delete('PAG_NUM');
$this->set('ano',$ano);

if($var1==null){

$this->index();
$this->render('index');

}else{

 	$mont_acu_pago=0;
 	$mont_acu_pago_fc=0;
$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$var1."'  and  ano_contrato_obra=".$ano." ";

$numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
/* 
campos de retencion laboral y fiel cumplimiento todos los de la tabla contrato_obra_cuerpo*/

$condicion_lf = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$var1."'";

$numero_datos_lf = $this->cobd01_contratoobras_cuerpo
                    ->execute( 'SELECT SUM(monto_retencion_laboral) as monto_retencion_laboral,  SUM(monto_retencion_fielcumplimiento) as monto_retencion_fielcumplimiento, SUM(monto_cancelado) as monto_cancelado, SUM(monto_anticipo) as monto_anticipo, SUM(monto_amortizacion) as monto_amortizacion FROM cobd01_contratoobras_cuerpo WHERE '.$condicion_lf);

$condicion_par = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$var1."' and (retencion_laboral + retencion_fielcumplimiento)!=0 GROUP BY  cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";


$numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion_par, 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, SUM(retencion_laboral) as retencion_laboral, SUM(retencion_fielcumplimiento) as retencion_fielcumplimiento', null);

/*fin cambio para traer todo de fiel cumplimiento y retencion laboral*/

foreach($numero_datos_partidas as $dpaux){
	$mont_acu_pago += $dpaux['cobd01_contratoobras_partidas']['retencion_laboral'];
    $mont_acu_pago_fc += $dpaux['cobd01_contratoobras_partidas']['retencion_fielcumplimiento'];
}

	$this->Session->write('monto_pago_acum', $mont_acu_pago);
	$this->Session->write('monto_pago_acum_fc', $mont_acu_pago_fc);

$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif                                  =    $aux['cobd01_contratoobras_cuerpo']['rif'];
	$ano_contrato_obra                    =    $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
	$numero_contrato_obra                 =    $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
	$laboral_cancelado                    =    $aux['cobd01_contratoobras_cuerpo']['laboral_cancelado'];
	$fielcumplimiento_cancelado           =    $aux['cobd01_contratoobras_cuerpo']['fielcumplimiento_cancelado'];
	$monto_retencion_laboral              =    $aux['cobd01_contratoobras_cuerpo']['monto_retencion_laboral'];
	$monto_retencion_fielcumplimiento     =    $aux['cobd01_contratoobras_cuerpo']['monto_retencion_fielcumplimiento'];
	$anticipo_incluye_iva                 =    $aux['cobd01_contratoobras_cuerpo']['anticipo_con_iva'];
    $porcentaje_anticipo                  =    $aux['cobd01_contratoobras_cuerpo']['porcentaje_anticipo'];
    $porcentaje_iva                       =    $aux['cobd01_contratoobras_cuerpo']['porcentaje_iva'];
}//fin foreach
//$rif = "jj-as";

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

$opc     = $this->cobd01_contratoobras_retencion_cuerpo->findCount($condicion." and ano_contrato_obra=".$ano_contrato_obra."  and numero_contrato_obra='".$numero_contrato_obra."'   ");

$result  = $this->cobd01_contratoobras_retencion_cuerpo->findAll($condicion."   and ano_contrato_obra=".$ano_contrato_obra."  and  upper(numero_contrato_obra)='".strtoupper($numero_contrato_obra)."' ", null, "numero_retencion ASC", null, null);

foreach($result as $ves){$opc = $ves['cobd01_contratoobras_retencion_cuerpo']['numero_retencion'];}//fin foreach

$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");


$objeto_rif = "";
$denominacion_rif = "";
$direccion_comercial_rif = "";
$datos_contrato_obra_anteriores = "";
$datos_orden_pagos_anteriores_partidas = "";
$exento_islr_cooperativa = "";

foreach($rif_datos as $aux_2){
	$denominacion_rif = $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
	$objeto_rif = $aux_2['cpcd02']['objeto'];
	$exento_islr_cooperativa   = $aux_2['cpcd02']['exento_islr_cooperativa'];
}//fin foreach


if($objeto_rif != "" && $denominacion_rif!=""){




$datos_contrato_obra_anteriores         = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($condicion."  and ano_contrato_obra=".$ano_contrato_obra."  and numero_contrato_obra='".$numero_contrato_obra."'  and condicion_actividad=1", null, null, null, null);

$datos_orden_pagos_anteriores_partidas = $this->cobd01_contratoobras_valuacion_partidas->findAll($condicion, null, 'numero_contrato_obra DESC');


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


$datos_cuerpo   = $this->cobd01_contratoobras_cuerpo->findAll($condicion."  and ano_contrato_obra=".$ano_contrato_obra."  and numero_contrato_obra='".$numero_contrato_obra."'  and condicion_actividad=1", null, 'numero_contrato_obra DESC');

//////////***********************  FIN PARAMETROS   ******************************///////////////


$this->detalles_del_pago($objeto_rif, $ano_contrato_obra, $numero_contrato_obra);

$tipo_selecion_value = "";


if($laboral_cancelado==0 && $monto_retencion_laboral!=0  &&  $fielcumplimiento_cancelado==0 && $monto_retencion_fielcumplimiento!=0){}else{

// $this->tipo_retencion($ano_contrato_obra, $numero_contrato_obra, 2);
      if($laboral_cancelado==0 && $monto_retencion_laboral!=0 ){$this->tipo_retencion($ano_contrato_obra, $numero_contrato_obra, 1); $tipo_selecion_value="1";
}else if($fielcumplimiento_cancelado==0 && $monto_retencion_fielcumplimiento!=0){$this->tipo_retencion($ano_contrato_obra, $numero_contrato_obra, 2); $tipo_selecion_value="2";}}//fin else


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
$this->set('ano_contrato_obra_pago', $ano);
$this->set('numero_retencion', $opc);
$this->set('datos_contrato_obra', $numero_datos);
$this->set('datos_contrato_obra_partidas', $numero_datos_partidas);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('direccion_comercial_rif', $direccion_comercial_rif);
$this->set('datos_contrato_obra_anteriores', $datos_contrato_obra_anteriores);
$this->set('datos_orden_pagos_anteriores_partidas', $datos_orden_pagos_anteriores_partidas);
$this->set('rcivil'                           , $rcivil);
$this->set('retencion_multa_monto'            , $retencion_multa_monto);
$this->set('rsocial'                          , $rsocial);
$this->set('retencion_responsabilidad_social' , $retencion_responsabilidad_social);
$this->set('datos_lab_fiel', $numero_datos_lf);

   }//fin else
}//fin function










function eliminar($var1=null, $var2=null, $var3=null, $var4=null){

$this->layout = "ajax";
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

$opcion = "si";

      if($this->cobd01_contratoobras_retencion_cuerpo->execute("DELETE FROM cobd01_contratoobras_retencion_cuerpo WHERE ".$condicion."  and ano_contrato_obra ='".$var1."' and numero_contrato_obra ='".$var2."'   and numero_retencion ='".$var3."' ")>1){}else{$opcion = 'no';}//fin else
      if($this->cobd01_contratoobras_retencion_partidas->execute("DELETE FROM cobd01_contratoobras_retencion_partidas WHERE ".$condicion."  and ano_contrato_obra ='".$var1."' and numero_contrato_obra ='".$var2."'   and numero_retencion ='".$var3."' ")>1){}else{$opcion = 'no';}//fin else
      if($var4=="1"){
           if($this->cobd01_contratoobras_cuerpo->execute("UPDATE cobd01_contratoobras_cuerpo set laboral_cancelado=0 WHERE ".$condicion."  and ano_contrato_obra ='".$var1."' and numero_contrato_obra ='".$var2."' ")>1){}else{$opcion = 'no';}//fin else
}else if($var4=="2"){
           if($this->cobd01_contratoobras_cuerpo->execute("UPDATE cobd01_contratoobras_cuerpo set fielcumplimiento_cancelado=0 WHERE ".$condicion."  and ano_contrato_obra ='".$var1."' and numero_contrato_obra ='".$var2."' ")>1){}else{$opcion = 'no';}//fin else
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


  $this->set('ano_contrato_obra_ejecucion', $this->ano_ejecucion());


  if($g=="si"){$this->set('Message_existe', 'Los datos fueron guardados correctamente');}
  if($g=="sii"){$this->set('Message_existe', 'El registro fue anulado correctamente');}



   if(isset($_SESSION['ano_contrato_obra'])){$ano = $_SESSION['ano_contrato_obra'];}else{$ano = $this->ano_ejecucion();}
   $this->set('ano_contrato_obra', $ano);


if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else


   $this->set('ano_contrato_obra', $ano);

   $array = $this->cobd01_contratoobras_retencion_cuerpo->findAll($condicion. "  and numero_contrato_obra='".$numero_documento."' and ano_contrato_obra = ".$ano , null, 'ano_contrato_obra, numero_contrato_obra, numero_retencion ASC', null);

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_retencion_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_retencion']      = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_retencion'];

 	$i++;

} $i--;


if(isset($numero[$pag_num]['numero_contrato_obra'])){


$datos_cobd01_contratoobras_retencion_cuerpo          =     $this->cobd01_contratoobras_retencion_cuerpo->findAll($condicion."   and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']."  and numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."'  and numero_retencion='".$numero[$pag_num]['numero_retencion']."'   ");
$datos_cobd01_contratoobras_retencion_partidas        =     $this->cobd01_contratoobras_retencion_partidas->findAll($condicion." and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']."  and numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."'  and numero_retencion='".$numero[$pag_num]['numero_retencion']."'  ");
$datos_cobd01_contratoobras_cuerpo                    =     $this->cobd01_contratoobras_cuerpo->findAll($condicion."             and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']."  and numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."'  ");


$numero_datos_aux =  $datos_cobd01_contratoobras_cuerpo;
foreach($numero_datos_aux as $aux){
	$rif                   =   $aux['cobd01_contratoobras_cuerpo']['rif'];
	$ano_contrato_obra     =   $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
	$numero_contrato_obra  =   $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
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





$this->set('datos_cobd01_contratoobras_retencion_cuerpo', $datos_cobd01_contratoobras_retencion_cuerpo);
$this->set('datos_cobd01_contratoobras_retencion_partidas', $datos_cobd01_contratoobras_retencion_partidas);
$this->set('datos_cobd01_contratoobras_cuerpo', $datos_cobd01_contratoobras_cuerpo);
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



   $array = $this->cobd01_contratoobras_retencion_cuerpo->findAll($condicion. " and ano_contrato_obra = ".$ano."  and upper(numero_contrato_obra)='".strtoupper($var1)."'"  ,null, 'numero_retencion ASC', null);
       $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_retencion_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_retencion']     = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_retencion'];
 	$i++;

} $i--;






}else{





     $array = "";
     $c = $this->cobd01_contratoobras_retencion_cuerpo->findAll($condicion. ' and ano_contrato_obra = '.$ano , 'numero_contrato_obra, ano_contrato_obra', null, null);
     $sql_c = ""; //print_r($c);
foreach($c as $c_aux){
			if($sql_c == ""){
                          $sql_c  = "     numero_contrato_obra='".$c_aux['cobd01_contratoobras_retencion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_retencion_cuerpo']['ano_contrato_obra']."' ";
			}else{        $sql_c .= " or (numero_contrato_obra='".$c_aux['cobd01_contratoobras_retencion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_retencion_cuerpo']['ano_contrato_obra']."') ";		}//fin else
}//fin for
if($sql_c!=""){
	  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' ';
	  $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion." and (".$sql_c.")");
	  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.' ';
	  $b = $this->cfpd07_obras_cuerpo->findAll($condicion);
	  $sql_c = "";
	  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' ';
	foreach($a as $a_aux){
	   foreach($b as $b_aux){
	      if($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion']==$b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] &&  strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra'])==strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])){
	        //$array[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']]=$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
	        if($sql_c == ""){
                          $sql_c  = "     numero_contrato_obra='".$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$a_aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra']."' ";
			}else{        $sql_c .= " or (numero_contrato_obra='".$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$a_aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra']."') ";		}//fin else
	     }//fin if
	  }//fin foreach
	}//fin foreach
	}//fin if
if($sql_c!=""){
		$array = $this->cobd01_contratoobras_retencion_cuerpo->findAll($condicion." and (".$sql_c.")", 'DISTINCT ano_contrato_obra, numero_contrato_obra, numero_retencion ', 'ano_contrato_obra, numero_contrato_obra, numero_retencion ASC', null);
		$i = 0;
		 if($pag_num==null){$pag_num=0;}

		 foreach($array as $aux){

		 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_retencion_cuerpo']['ano_contrato_obra'];
		 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_contrato_obra'];
		 	$numero[$i]['numero_retencion']     = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_retencion'];
		 	$i++;

		} $i--;
}//fin if





}//fin else




if(isset($numero[$pag_num]['numero_contrato_obra'])){


$datos_cobd01_contratoobras_retencion_cuerpo          =     $this->cobd01_contratoobras_retencion_cuerpo->findAll($condicion."   and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']."  and numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."'  and numero_retencion='".$numero[$pag_num]['numero_retencion']."'   ");
$datos_cobd01_contratoobras_retencion_partidas        =     $this->cobd01_contratoobras_retencion_partidas->findAll($condicion." and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']."  and numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."'  and numero_retencion='".$numero[$pag_num]['numero_retencion']."'  ");
$datos_cobd01_contratoobras_cuerpo                    =     $this->cobd01_contratoobras_cuerpo->findAll($condicion."             and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']."  and numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."'  ");


$numero_datos_aux =  $datos_cobd01_contratoobras_cuerpo;
foreach($numero_datos_aux as $aux){
	$rif                   =   $aux['cobd01_contratoobras_cuerpo']['rif'];
	$ano_contrato_obra     =   $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
	$numero_contrato_obra  =   $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
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





$this->set('datos_cobd01_contratoobras_retencion_cuerpo', $datos_cobd01_contratoobras_retencion_cuerpo);
$this->set('datos_cobd01_contratoobras_retencion_partidas', $datos_cobd01_contratoobras_retencion_partidas);
$this->set('datos_cobd01_contratoobras_cuerpo', $datos_cobd01_contratoobras_cuerpo);
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



  $ano_contrato_obra                       =       $this->data['cobp01_contratoobras_retencion']['ano_contrato_obra'];
  $numero_contrato_obra                    =       $this->data['cobp01_contratoobras_retencion']['num_contrato_obra'];
  $numero_retencion                        =       $this->data['cobp01_contratoobras_retencion']['numero_retencion'];
  $concepto                                =       $this->data['cobp01_contratoobras_retencion']['concepto'];
  $fecha_retencion                         =       $this->Cfecha($this->data['cobp01_contratoobras_retencion']['fecha_retencion'], 'A-M-D');
  $oficio_aprobacion                       =       $this->data['cobp01_contratoobras_retencion']['numero_aprobacion'];
  $fecha_aprobacion                        =       $this->Cfecha($this->data['cobp01_contratoobras_retencion']['fecha_aprobacion'], 'A-M-D');


						 $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$numero_contrato_obra."'  and  ano_contrato_obra=".$ano_contrato_obra." and numero_retencion='".$numero_retencion."' ";
						 $sql3  = " BEGIN; UPDATE cobd01_contratoobras_retencion_cuerpo SET concepto ='".$concepto."', fecha_retencion ='".$fecha_retencion."', fecha_aprobacion ='".$fecha_aprobacion."', oficio_aprobacion ='".$oficio_aprobacion."'  where ".$condicion.';';
						 $sw4 = $this->cobd01_contratoobras_retencion_cuerpo->execute($sql3);


                            if($sw4 > 1){
                                $this->cobd01_contratoobras_retencion_cuerpo->execute("COMMIT;");
                                $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
                           }else{
							    $this->cobd01_contratoobras_retencion_cuerpo->execute("ROLLBACK;");
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


 $datos_contrato_obra_anteriores = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($condicion."  and numero_contrato_obra='".$var2."'  and condicion_actividad=1 GROUP BY numero_valuacion, retencion_incluye_iva", 'numero_valuacion, retencion_incluye_iva, 
       SUM(monto_retencion_laboral) as monto_retencion_laboral, SUM(monto_retencion_fielcumplimiento) as monto_retencion_fielcump', null, null, null);
 
//echo'<pre>';
// print_r($numero_datos_partidas);
//echo'</pre>';


foreach($datos_contrato_obra_anteriores as $ve){
//	    $ve['cobd01_contratoobras_valuacion_cuerpo']['retencion_incluye_iva']=1;
	     /*$numero_datos_partidas = $this->cobd01_contratoobras_valuacion_partidas->findAll($condicion."  and ano_contrato_obra=".$var1."  and numero_contrato_obra='".$var2."'  and numero_valuacion = '".$ve['cobd01_contratoobras_valuacion_cuerpo']['numero_valuacion']."'  ", null, 'numero_contrato_obra DESC');*/
       

	       if($var3=="1"){
	                        if($ve['cobd01_contratoobras_valuacion_cuerpo']['retencion_incluye_iva']=="1"){
	                        $monto_rentencion_iva     += $ve[0]['monto_retencion_laboral'];
	                  }else if($ve['cobd01_contratoobras_valuacion_cuerpo']['retencion_incluye_iva']=="2"){
	                  	    $monto_rentencion_sin_iva += $ve[0]['monto_retencion_laboral'];
	                  }//fin else
	 }else if($var3=="2"){
	                        if($ve['cobd01_contratoobras_valuacion_cuerpo']['retencion_incluye_iva']=="1"){
	                        $monto_rentencion_iva     += $ve[0]['monto_retencion_fielcump'];
	                  }else if($ve['cobd01_contratoobras_valuacion_cuerpo']['retencion_incluye_iva']=="2"){
	                  	    $monto_rentencion_sin_iva += $ve[0]['monto_retencion_fielcump'];
	                  }//fin else
	           	}//fin else
              $numero_datos_partidas = $this->cobd01_contratoobras_valuacion_partidas->findAll($condicion."  and numero_contrato_obra='".$var2."'  and numero_valuacion = '".$ve['cobd01_contratoobras_valuacion_cuerpo']['numero_valuacion']."' and (retencion_laboral + retencion_fielcumplimiento)!=0  GROUP BY  cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar", 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, SUM(retencion_laboral) as retencion_laboral, SUM(retencion_fielcumplimiento) as retencion_fielcumplimi', null);
	             foreach($numero_datos_partidas as $ve2){
							  $concate  = $this->AddCeroR2(substr($ve2['cobd01_contratoobras_valuacion_partidas']['cod_partida'], -2) , substr($ve2['cobd01_contratoobras_valuacion_partidas']['cod_partida'], 0, 1 )).'.'.$this->AddCeroR2($ve2['cobd01_contratoobras_valuacion_partidas']['cod_generica']).'.'.$this->AddCeroR2($ve2['cobd01_contratoobras_valuacion_partidas']['cod_especifica']).'.'.$this->AddCeroR2($ve2['cobd01_contratoobras_valuacion_partidas']['cod_sub_espec']);
							  $concate2 = $this->AddCeroR2(substr($ve2['cobd01_contratoobras_valuacion_partidas']['cod_partida'], -2) , substr($ve2['cobd01_contratoobras_valuacion_partidas']['cod_partida'], 0, 1 ));
     						 // $ano                      =         $ve2['cobd01_contratoobras_valuacion_partidas']['ano'];
							  $cod_sector               =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_sector'];
							  $cod_programa             =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_programa'];
							  $cod_sub_prog             =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_sub_prog'];
							  $cod_proyecto             =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_proyecto'];
							  $cod_activ_obra           =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_activ_obra'];
							  $cod_partida              =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_partida'];
							  $cod_generica             =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_generica'];
							  $cod_especifica           =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_especifica'];
							  $cod_sub_espec            =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_sub_espec'];
							  $cod_auxiliar             =         $ve2['cobd01_contratoobras_valuacion_partidas']['cod_auxiliar'];

                if($var3=="1" && $ve['cobd01_contratoobras_valuacion_cuerpo']['retencion_incluye_iva']=="1"){
							      if($concate=="4.03.18.01.00"){ 
                      $monto_iva += $ve2[0]['retencion_laboral']; 
                    }
							    }else if($var3=="2" && $ve['cobd01_contratoobras_valuacion_cuerpo']['retencion_incluye_iva']=="1")
                  {
							   	  if($concate=="4.03.18.01.00"){
                      $monto_iva += $ve2[0]['retencion_fielcumplimi'];  
                    }
                  }//fin esle
                  
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

      echo" document.getElementById('TOTALINGRESOS').innerHTML = document.getElementById('monto_partida_laboral_'+ii).value;";

      echo" document.getElementById('monto_a_pagar_con_iva').value = document.getElementById('monto_partida_laboral_'+ii).value;";

      echo" document.getElementById('monto_retencion_iva').value = document.getElementById('monto_partida_laboral_'+ii).value;";

      echo" document.getElementById('monto_retencion_sin_iva').value = document.getElementById('monto_partida_laboral_'+ii).value;";

      echo" document.getElementById('monto_total_retencion').value = document.getElementById('monto_partida_laboral_'+ii).value;";

     
      echo"moneda('monto_a_pagar_con_iva');";
      echo"moneda('monto_retencion_iva');";
      echo"moneda('monto_retencion_sin_iva');";
      echo"moneda('monto_total_retencion');";


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

      echo" document.getElementById('TOTALINGRESOS').innerHTML = document.getElementById('monto_partida_fiel_cumplimiento_'+ii).value;";

      echo" document.getElementById('monto_a_pagar_con_iva').value = document.getElementById('monto_partida_fiel_cumplimiento_'+ii).value;";

      echo" document.getElementById('monto_retencion_iva').value = document.getElementById('monto_partida_fiel_cumplimiento_'+ii).value;";

      echo" document.getElementById('monto_retencion_sin_iva').value = document.getElementById('monto_partida_fiel_cumplimiento_'+ii).value;";

      echo" document.getElementById('monto_total_retencion').value = document.getElementById('monto_partida_fiel_cumplimiento_'+ii).value;";


      echo"moneda('monto_a_pagar_con_iva');";
      echo"moneda('monto_retencion_iva');";
      echo"moneda('monto_retencion_sin_iva');";
      echo"moneda('monto_total_retencion');";

      echo"if(document.getElementById('partida_iva_'+ii)){document.getElementById('partida_iva_'+ii).value = document.getElementById('monto_partida_fiel_cumplimiento_'+ii).value;}";
        echo"moneda('pago_'+ii);";
      echo" document.getElementById('pago_'+ii).disabled = false;";
      echo" document.getElementById('pago_'+ii).readOnly = true;";
        echo"}}";
      echo "</script>";
 }//fin else


$monto_total_retencion = $monto_rentencion_sin_iva + $monto_rentencion_iva;


echo"<script>";
 //echo'document.getElementById("monto_retencion_iva").value      = "'.$this->Formato2($monto_rentencion_iva).'" ; ';
 //echo'document.getElementById("monto_retencion_sin_iva").value  = "'.$this->Formato2($monto_rentencion_sin_iva).'";  ';
 //echo'document.getElementById("monto_total_retencion").value    = "'.$this->Formato2($monto_total_retencion).'";  ';
 //echo'document.getElementById("monto_a_pagar_con_iva").value    = "'.$this->Formato2($monto_total_retencion).'";  ';
 //echo'document.getElementById("TOTALINGRESOS").innerHTML        = "'.$this->Formato2($monto_total_retencion).'";  ';
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


	  $aux_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion." and ano_contrato_obra=".$var1." and upper(numero_contrato_obra)='".strtoupper($var2)."' and (retencion_laboral + retencion_fielcumplimiento)!=0", null, 'numero_contrato_obra DESC');

	if(!empty($aux_partidas)){
	  $ano_contrato_obra_r = $aux_partidas[0]['cobd01_contratoobras_partidas']['ano_contrato_obra'];
	  $num_contrato_obra_r = $aux_partidas[0]['cobd01_contratoobras_partidas']['numero_contrato_obra'];
	  $ano_partidas_r      = $aux_partidas[0]['cobd01_contratoobras_partidas']['ano'];
	  $cod_sector_r        = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_sector'];
	  $cod_programa_r      = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_programa'];
	  $cod_sub_prog_r      = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_sub_prog'];
	  $cod_proyecto_r      = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_proyecto'];
	  $cod_activ_obra_r    = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_activ_obra'];
	  $cod_partida_r       = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_partida'];
	  $cod_generica_r      = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_generica'];
	  $cod_especifica_r    = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_especifica'];
	  $cod_sub_espec_r     = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_sub_espec'];
	  $cod_auxiliar_r      = $aux_partidas[0]['cobd01_contratoobras_partidas']['cod_auxiliar'];

  	$cond_partds = " and ano_contrato_obra='$ano_contrato_obra_r' and numero_contrato_obra='$num_contrato_obra_r' and ano='$ano_partidas_r' and cod_sector='$cod_sector_r' and cod_programa='$cod_programa_r' and cod_sub_prog='$cod_sub_prog_r' and cod_proyecto='$cod_proyecto_r' and cod_activ_obra='$cod_activ_obra_r' and cod_partida='$cod_partida_r' and cod_generica='$cod_generica_r' and cod_especifica='$cod_especifica_r' and cod_sub_espec='$cod_sub_espec_r' and cod_auxiliar='$cod_auxiliar_r'";
  	$sql_upd = "UPDATE cobd01_contratoobras_partidas SET $campo_upd = $campo_upd + ($ccentims) WHERE ".$condicion.$cond_partds.";";
  	$swupd = $this->cobd01_contratoobras_partidas->execute($sql_upd);

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


		if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
			$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
			$lista = $this->cobd01_contratoobras_retencion_cuerpo->generateList($condicion.' and ano_contrato_obra='.$var1, ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_retencion_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_retencion_cuerpo.numero_contrato_obra');
			$this->set('obras', $lista);
		}else{
			$c = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->condicion().' and ano_contrato_obra='.$var1, 'numero_contrato_obra, ano_contrato_obra', null, null);
			$sql_c = ""; //print_r($c);
			foreach($c as $c_aux){
						if($sql_c == ""){
			                          $sql_c  = "     numero_contrato_obra='".$c_aux['cobd01_contratoobras_retencion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_retencion_cuerpo']['ano_contrato_obra']."' ";
						}else{        $sql_c .= " or (numero_contrato_obra='".$c_aux['cobd01_contratoobras_retencion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_retencion_cuerpo']['ano_contrato_obra']."') ";		}//fin else
			}//fin for
			if($sql_c!=""){
				  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' ';
				  $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion." and (".$sql_c.")");
				  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.' ';
				  $b = $this->cfpd07_obras_cuerpo->findAll($condicion);
				foreach($a as $a_aux){
				   foreach($b as $b_aux){
				      if($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion']==$b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] &&  strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra'])==strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])){
				        $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']]=$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
				     }//fin if
				  }//fin foreach
				}//fin foreach
			}//fin if
			$this->set('obras', $lista);
			$this->set('ano',$var1);

		}//fin else

}//fin function




function consulta_index($var1=null){

  $this->layout = "ajax";
  $pag_num = 0;
  $opcion = 'no';
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');

  if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
	}else{
	    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
	}//fin else

if(!empty($this->data['cobp01_contratoobras_retencion']['ano_ejecucion'])){	$_SESSION['ano_contrato_obra'] = $this->data['cobp01_contratoobras_retencion']['ano_ejecucion'];}else{$_SESSION['ano_contrato_obra'] = $this->ano_ejecucion();}
$ano = $_SESSION['ano_contrato_obra'];

if($var1!=null){
  if($var1=='si'){

if(!empty($this->data['cobp01_contratoobras_retencion']['numero_contrato_obra'])){

    $array = $this->cobd01_contratoobras_retencion_cuerpo->findAll($condicion. "  and numero_contrato_obra='".$this->data['cobp01_contratoobras_retencion']['numero_contrato_obra']."' and ano_contrato_obra = ".$ano , null, 'ano_contrato_obra, numero_contrato_obra, numero_retencion ASC', null);
    $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_retencion_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_retencion']      = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_retencion'];
 	$i++;
} $i--;

  for($a=0; $a<=$i; $a++){

    if($this->data['cobp01_contratoobras_retencion']['numero_contrato_obra'] == $numero[$a]['numero_contrato_obra']){
    	  $pag_num = 0;
    	  $opcion='si';
    	  $numero_documento = $numero[$a]['numero_contrato_obra'];
    	  break;
    }else{$pag_num = 0;
    	  $opcion='si';
    	  $numero_documento = $numero[0]['numero_contrato_obra']; }
   }//fin for

      if($opcion=='si'){$this->consulta($pag_num, $numero_documento);$this->render('consulta');
}else if($opcion=='no'){
	$this->set('errorMessage', 'No existen datos');
	$this->consulta_index();$this->render('consulta_index');
	}//fin else

}else{

	$array = $this->cobd01_contratoobras_retencion_cuerpo->findAll($condicion. "  and ano_contrato_obra = ".$ano , null, 'ano_contrato_obra, numero_contrato_obra, numero_retencion ASC', null);
  $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_retencion_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_retencion']      = $aux['cobd01_contratoobras_retencion_cuerpo']['numero_retencion'];
 	$i++;
} $i--;

 if($i<=0){
	  $this->set('errorMessage', 'No existen datos');
	  $this->consulta_index();
	  $this->render('consulta_index');

	}else{
	  $this->consulta(0, $numero[0]['numero_contrato_obra']); $this->render('consulta'); }   }//fin else

  }//fin if
}//fin i

		 $lista = $this->cobd01_contratoobras_retencion_cuerpo->generateList($condicion.' and ano_contrato_obra='.$ano.$this->filtra_obra($ano), ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_retencion_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_retencion_cuerpo.numero_contrato_obra');
		 $this->set('obras', $lista);
		 $this->set('ano', $_SESSION['ano_contrato_obra']);
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

  //$porcentaje_iva                          =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['porcentaje_iva']);
  $porcentaje_iva = 0;
  $ano_contrato_obra                       =       $this->data['cobp01_contratoobras_retencion']['ano_contrato_obra'];
  $numero_contrato_obra                    =       $this->data['cobp01_contratoobras_retencion']['num_contrato_obra'];
  $numero_retencion                        =       $this->data['cobp01_contratoobras_retencion']['numero_retencion'];
  $tipo_retencion                          =       $this->data['cobp01_contratoobras_retencion']['tipo_retencion'];
  $monto_original_contrato                 =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['monto_contrato']);
  $aumento                                 =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['aumento']);
  $disminucion                             =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['disminucion']);
  $monto_anticipo                          =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['monto_anticipo']);
  $monto_amortizacion                      =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['monto_amortizacion']);
  $monto_iva                               =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['monto_iva']);
  $monto_retenido_laboral                  =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['retencion_laboral']);
  $monto_retenido_fielcumplimiento         =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['fiel_cumplimiento']);
  $monto_cancelado                         =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['monto_cancelado']);
  $monto_retenido_coniva                   =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['monto_retencion_iva']);
  $monto_retenido_siniva                   =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['monto_retencion_sin_iva']);
  $monto_descontar_impuesto                =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['total_retencion_monto_iva']);
  $monto_orden_pago                        =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['monto_orden_de_pago_monto_iva']);
  //$monto_retencion_iva                     =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['retencion_incluye_iva_monto_iva']);
  $monto_retencion_iva = 0;
  //$porcentaje_retencion_iva                =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['retencion_incluye_iva']);
  $porcentaje_retencion_iva = 0;
  $monto_islr                              =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['impuesto_sobre_la_renta_monto_iva']);
  //$porcentaje_islr                         =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['impuesto_sobre_la_renta']);
  $porcentaje_islr = 0;
  $monto_sustraendo                        =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['sustraendo']);
  $monto_timbre_fiscal                     =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['timbre_fiscal_monto_iva']);
  //$porcentaje_timbre_fiscal                =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['timbre_fiscal']);
  $porcentaje_timbre_fiscal  = 0;
  //$rcivil                                  =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['rcivil']);
  $rcivil  = 0;
  $retencion_multa_monto                   =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['retencion_multa_monto']);
  //$rsocial                                 =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['rsocial']);
  $rsocial = 0;
  $retencion_responsabilidad_social        =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['retencion_responsabilidad_social']);

  $monto_impuesto_municipal                =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['impuesto_municipal_monto_iva']);
  //$porcentaje_impuesto_municipal           =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['impuesto_municipal']);
  $porcentaje_impuesto_municipal = 0;
  $monto_neto_cobrar                       =       $this->Formato1($this->data['cobp01_contratoobras_retencion']['monto_a_pagar_monto_iva']);
  $concepto                                =       $this->data['cobp01_contratoobras_retencion']['concepto'];
  $fecha_retencion                         =       $this->Cfecha($this->data['cobp01_contratoobras_retencion']['fecha_retencion'], 'A-M-D');
  $oficio_aprobacion                       =       $this->data['cobp01_contratoobras_retencion']['numero_aprobacion'];
  $fecha_aprobacion                        =       $this->Cfecha($this->data['cobp01_contratoobras_retencion']['fecha_aprobacion'], 'A-M-D');
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

//$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  upper(numero_contrato_obra)='".strtoupper($numero_contrato_obra)."'  and  ano_contrato_obra=".$ano_contrato_obra." ";
//$datos_cuerpo = $this->cobd01_contratoobras_partidas->findAll($condicion, null, 'numero_contrato_obra DESC');
//$porcenta_iva = $datos_cuerpo[0]['cobd01_contratoobras_cuerpo']['porcentaje_iva'];

$porcenta_iva = $porcentaje_iva;

$sql =" BEGIN; INSERT INTO cobd01_contratoobras_retencion_cuerpo (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, ano_contrato_obra, numero_contrato_obra, numero_retencion, tipo_retencion,  monto_original_contrato, aumento, disminucion, monto_anticipo, monto_amortizacion, monto_iva, monto_retenido_laboral, monto_retenido_fielcumplimiento, monto_cancelado,  monto_retenido_coniva , monto_retenido_siniva, monto_descontar_impuesto, monto_orden_pago, monto_retencion_iva, porcentaje_retencion_iva, monto_islr, porcentaje_islr,  monto_sustraendo, monto_timbre_fiscal, porcentaje_timbre_fiscal, monto_impuesto_municipal, porcentaje_impuesto_municipal, monto_neto_cobrar, concepto, fecha_retencion, oficio_aprobacion, fecha_aprobacion, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, username_registro, fecha_proceso_registro, condicion_actividad, ano_anulacion, numero_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, ano_asiento_anulacion, numero_asiento_anulacion, fecha_proceso_anulacion, username_anulacion, ano_orden_pago, numero_orden_pago, porcenta_iva, codigo_retencion_islr, cod_actividad, porcentaje_multa, retencion_multa, porcentaje_responsabilidad, retencion_responsabilidad) VALUES ";
$sql.=" ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_contrato_obra."', '".$numero_contrato_obra."', '".$numero_retencion."', '".$tipo_retencion."', '".$monto_original_contrato."', '".$aumento."', '".$disminucion."', '".$monto_anticipo."', '".$monto_amortizacion."', '".$monto_iva."', '".$monto_retenido_laboral."', '".$monto_retenido_fielcumplimiento."', '".$monto_cancelado."', '".$monto_retenido_coniva."', '".$monto_retenido_siniva."', '".$monto_descontar_impuesto."', '".$monto_orden_pago."', '".$monto_retencion_iva."', '".$porcentaje_retencion_iva."', '".$monto_islr."', '".$porcentaje_islr."', '".$monto_sustraendo."', '".$monto_timbre_fiscal."', '".$porcentaje_timbre_fiscal."', '".$monto_impuesto_municipal."', '".$porcentaje_impuesto_municipal."', '".$monto_neto_cobrar."', '".$concepto."', '".$fecha_retencion."', '".$oficio_aprobacion."', '".$fecha_aprobacion."', '".$dia_asiento_registro."', '".$mes_asiento_registro."', '".$ano_asiento_registro."', '".$numero_asiento_registro."', '".$username_registro."', '".$fecha_proceso_registro."', '".$condicion_actividad."', '".$ano_anulacion."', '".$numero_anulacion."', '".$dia_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$ano_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$fecha_proceso_anulacion."', '".$username_anulacion."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$porcenta_iva."', '".$_SESSION["ventana_islr"]."', '".$_SESSION["ventana_impuesto_municipal"]."', '".$rcivil."', '".$retencion_multa_monto."', '".$rsocial."', '".$retencion_responsabilidad_social."')  ";
$sw  = $this->cobd01_contratoobras_retencion_cuerpo->execute($sql);

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  upper(numero_contrato_obra)='".strtoupper($numero_contrato_obra)."'  and  ano_contrato_obra=".$ano_contrato_obra." ";
$condicion_par = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  upper(numero_contrato_obra)='".strtoupper($numero_contrato_obra)."'  and  ano_contrato_obra=".$ano_contrato_obra." and (retencion_laboral + retencion_fielcumplimiento)!=0 ";

if($sw>1){
   $a = 0;
   $i_aux = 0;
   $numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion_par, null, 'numero_contrato_obra DESC');

	foreach($numero_datos_partidas as $aux_partidas){
		  $cod_presi2[$a]                 =   $aux_partidas['cobd01_contratoobras_partidas']['cod_presi'];
		  $cod_presiA=$cod_presi2[$a];
		  $cod_entidad2[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_entidad'];
		  $cod_entidadA=$cod_entidad2[$a];
		  $cod_tipo_inst2[$a]             =   $aux_partidas['cobd01_contratoobras_partidas']['cod_tipo_inst'];
		  $cod_tipo_instA = $cod_tipo_inst2[$a];
		  $cod_inst2[$a]                  =   $aux_partidas['cobd01_contratoobras_partidas']['cod_inst'];
		  $cod_instA =$cod_inst2[$a];
		  $cod_dep2[$a]                   =   $aux_partidas['cobd01_contratoobras_partidas']['cod_dep'];
		  $cod_depA = $cod_dep2[$a];
		  $ano_contrato_obra3[$a]          =   $aux_partidas['cobd01_contratoobras_partidas']['ano_contrato_obra'];
		  $ano_contrato_obraA = $ano_contrato_obra3[$a];
		  $num_contrato_obra3[$a]       =   $aux_partidas['cobd01_contratoobras_partidas']['numero_contrato_obra'];
		  $num_contrato_obraA = $num_contrato_obra3[$a];
		  $ano_partidas[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['ano'];
		  $ano_partidasA = $ano_partidas[$a];
		  $cod_sector[$a]                 =   $aux_partidas['cobd01_contratoobras_partidas']['cod_sector'];
		  $cod_sectorA = $cod_sector[$a];
		  $cod_programa[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_programa'];
		  $cod_programaA = $cod_programa[$a];
		  $cod_sub_prog[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_prog'];
		  $cod_sub_progA = $cod_sub_prog[$a];
		  $cod_proyecto[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_proyecto'];
		  $cod_proyectoA= $cod_proyecto[$a];
		  $cod_activ_obra[$a]             =   $aux_partidas['cobd01_contratoobras_partidas']['cod_activ_obra'];
		  $cod_activ_obraA=$cod_activ_obra[$a];
		  $cod_partida[$a]                =   $aux_partidas['cobd01_contratoobras_partidas']['cod_partida'];
		  $cod_partidaA = $cod_partida[$a];
		  $cod_generica[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_generica'];
		  $cod_genericaA = $cod_generica[$a];
		  $cod_especifica[$a]             =   $aux_partidas['cobd01_contratoobras_partidas']['cod_especifica'];
		  $cod_especificaA = $cod_especifica[$a];
		  $cod_sub_espec[$a]              =   $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_espec'];
		  $cod_sub_especA = $cod_sub_espec[$a];
		  $cod_auxiliar[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_auxiliar'];
		  $cod_auxiliarA = $cod_auxiliar[$a];
		  $monto2[$a]                     =   $aux_partidas['cobd01_contratoobras_partidas']['monto'];
		  $aumento2[$a]                   =   $aux_partidas['cobd01_contratoobras_partidas']['aumento'];
		  $disminucion2[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['disminucion'];
		  $monto_total_partida[$a]        = $monto2[$a] + ($aumento2[$a] - $disminucion2[$a]);

		  $anticipo2[$a]                     =   $aux_partidas['cobd01_contratoobras_partidas']['anticipo'];
		  $amortizacion2[$a]                 =   $aux_partidas['cobd01_contratoobras_partidas']['amortizacion'];
		  $retencion_laboral2[$a]            =   $aux_partidas['cobd01_contratoobras_partidas']['retencion_laboral'];
		  $retencion_fielcumplimiento2[$a]   =   $aux_partidas['cobd01_contratoobras_partidas']['retencion_fielcumplimiento'];
		  $cancelacion2[$a]                  =   $aux_partidas['cobd01_contratoobras_partidas']['cancelacion'];
		  $numero_control_compromiso[$a]     =   $aux_partidas['cobd01_contratoobras_partidas']['numero_control_compromiso'];

			$sql_where[$a] = "cod_presi='$cod_presiA' and cod_entidad=$cod_entidadA and cod_tipo_inst=$cod_tipo_instA and cod_inst=$cod_instA and cod_dep=$cod_depA and ano_contrato_obra=$ano_contrato_obraA and upper(numero_contrato_obra)='".strtoupper($num_contrato_obraA)."' and ano=$ano_partidasA and cod_sector=$cod_sectorA and cod_programa=$cod_programaA and cod_sub_prog=$cod_sub_progA and cod_proyecto=$cod_proyectoA and cod_activ_obra=$cod_activ_obraA and cod_partida=$cod_partidaA and cod_generica=$cod_genericaA and cod_especifica=$cod_especificaA and cod_sub_espec=$cod_sub_especA and cod_auxiliar=$cod_auxiliarA";
		$a++;

		    $partidas_aux  = $aux_partidas['cobd01_contratoobras_partidas']['cod_sector'];
			$partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_programa'];
			$partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_prog'];
			$partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_proyecto'];
			$partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_activ_obra'];
			$partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_partida'];
			$partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_generica'];
			$partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_especifica'];
			$partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_espec'];
			$partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_auxiliar'];


			for($i=0; $i<$i_lenght; $i++){
			   if($partidas_aux==$this->data['partidas']['partidas_'.$i]){$partidas_vista['pago_'.$i_aux] = $this->data['cobp01_contratoobras_retencion']['pago_'.$i]; $i_aux++;}
			}//fin foreach

		}//fin foreach

		for($i=0; $i<$i_lenght; $i++){      $var[$i]['pago'] = $partidas_vista['pago_'.$i];
	         if($var[$i]['pago']!="0,00"){  $var[$i]['pago'] = $this->Formato1($var[$i]['pago']);


              $sql2  ="INSERT INTO cobd01_contratoobras_retencion_partidas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_obra, numero_contrato_obra, numero_retencion, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec,  cod_auxiliar, monto, numero_control_compromiso, numero_control_causado) ";
	          $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_contrato_obra."', '".$num_contrato_obra3[$i]."', '".$numero_retencion."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['pago']."', '".$numero_control_compromiso[$i]."', '0'); ";
	          $sw2 =  $this->cobd01_contratoobras_retencion_partidas->execute($sql2);
	            if($sw2 > 1){}else{break;}


	         }//fin if
		}//fin for


		if($sw2 > 1){


                        if($tipo_retencion=="1"){
                            $sql3  = "UPDATE cobd01_contratoobras_cuerpo SET laboral_cancelado  ='1'  where ".$condicion.';';
                  }else if($tipo_retencion=="2"){
                            $sql3  = "UPDATE cobd01_contratoobras_cuerpo SET fielcumplimiento_cancelado ='1'  where ".$condicion.';';
                     }//fin if
                           $sw4 = $this->cobd01_contratoobras_cuerpo->execute($sql3);
                            if($sw4 > 1){
                              /**
                               * Colocar Información para guardar orden de pago de la retencion
                               * automatico para no realizar orden de pago por el Modulo OP
                               */
                              
                              $num_op = $this->numeroOP();
  
                              if($num_op == 0){
                                $this->cobd01_contratoobras_cuerpo->execute("ROLLBACK;");
                                $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - VERIFIQUE N&Uacute;MERO DISPONIBLE EN CONTROL DE ORDEN DE PAGO.');
                              }

                              $op_guardada = $this->guardarOP($numero_contrato_obra, $num_op, $numero_retencion, $fecha_retencion, $tipo_retencion);
                              
                              if($op_guardada){
                                $this->cobd01_contratoobras_cuerpo->execute("COMMIT;");    
                                $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
                              }else{
                                $this->cobd01_contratoobras_retencion_cuerpo->execute("ROLLBACK;");
                                $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
                              }

                              /**
                               * Fin de registro orden de pago de la retencion
                               */
                              
                           }else{
							               $this->cobd01_contratoobras_retencion_cuerpo->execute("ROLLBACK;");
								              $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
							             }//fin else
			}else{
			    $this->cobd01_contratoobras_retencion_cuerpo->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else
}else{
    $this->cobd01_contratoobras_retencion_cuerpo->execute("ROLLBACK;");
	$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
}//fin else


$this->index();
$this->render('index');


}//fin function guardar

public function numeroOP(){
  $max = $this->cepd03_ordenpago_numero->execute("SELECT numero_orden_pago FROM cepd03_ordenpago_numero WHERE ".$this->SQLCA()." and ano_orden_pago=".$this->ano_ejecucion()." and situacion=1 ORDER BY numero_orden_pago ASC LIMIT 1");

  if($max!=null){
      $resultado=$this->cepd03_ordenpago_numero->execute("UPDATE  cepd03_ordenpago_numero SET situacion=2 WHERE ".$this->SQLCA()." and numero_orden_pago=".$max[0][0]["numero_orden_pago"]." and ano_orden_pago=".$this->ano_ejecucion());
      if($resultado>1){
           return $max[0][0]["numero_orden_pago"];
      }else{
          return 0;
      }
  }else{
    return 0;
  }
}

public function guardarOP($num_doc, $num_op, $num_doc_adj, $fecha_doc_origen, $tipo_ret){
  
  /**
   * Informacion carga 1 OP
   */
    //if($codigo!=""){
    //'v_cepd02_contratoservi_retencion','v_cobd01_contratoobras_retencion'
      
      $resultado = $this->v_cobd01_contratoobras_retencion->findAll($this->SQLCA()." and ano_contrato_obra=".$this->ano_ejecucion()." and upper(numero_contrato_obra)='".strtoupper($num_doc)."' and numero_retencion=".$num_doc_adj);     

      $partidas=$this->cobd01_contratoobras_retencion_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$this->ano_ejecucion()." and upper(numero_contrato_obra)='".strtoupper($num_doc)."' and numero_retencion=".$num_doc_adj);

      //$this->set("contrato_obra_datos",$resultado);
    //  print_r($resultado);
      //$this->set("partidas",$resultado_partidas);
    //  print_r($resultado_partidas);

    /*}else{
      $this->set('contrato_obra_datos',array());
      $this->set('partidas',array());
    }
      $this->set('tipo',6);*/
  /*Fin carga 1*/


  /*Carga 2*/
    /*if($codigo!=""){
      $ano=$this->Session->read('ano_contrato_obra');
      $contrato_obra=$this->Session->read("numero_contrato_obra");
      $resultado=$this->v_cobd01_contratoobras_retencion->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_retencion=".$codigo);
      $resultado_partidas=$this->cobd01_contratoobras_retencion_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_retencion=".$codigo);
      $this->set("concepto",$resultado[0]["v_cobd01_contratoobras_retencion"]["concepto"]);
      $mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_retencion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
      $mislr   = $this->cepd01_compromiso_partidas->execute("SELECT monto_islr AS monto_islr  FROM cobd01_contratoobras_retencion_cuerpo WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_retencion=".$codigo.";");
      if($mi[0][0]["monto_iva"]>0 || $mislr[0][0]["monto_islr"]>0){
                         $this->set("mostrar_crear_factura",true);
      }else{
          $this->set("mostrar_crear_factura",false);
      }
      }else{
      $this->set("concepto","");
    }
    $this->set('tipo',5);*/
  /*Fin Carga 2*/

  /*Carga 3*/
    /*$ano=$this->Session->read('ano_contrato_obra');
    $contrato_obra=$this->Session->read("numero_contrato_obra");
    $valuacion_obras = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo);
    $this->set("valuacion_obras",$valuacion_obras);
    $mt      = $this->cobd01_contratoobras_retencion_partidas->execute("SELECT SUM(monto) AS monto_total FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo);
    $mi      = $this->cobd01_contratoobras_retencion_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
    $monto401= $this->cobd01_contratoobras_retencion_partidas->execute("SELECT SUM(monto) AS monto_401   FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo." and cod_partida=401");
    $this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
    $this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]);
    $this->set("monto_iva_partidas",$mi[0][0]["monto_iva"]);
    $this->Session->write("monto_iva_partidasSS",$mi[0][0]["monto_iva"]);
    
    $this->set("monto401",$monto401[0][0]["monto_401"]);
    $this->set('tipo',6);*/
  /*Fin carga 3*/
  
  $valuacion_obras = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->SQLCA()." and ano_contrato_obra=".$this->ano_ejecucion()." and upper(numero_contrato_obra)='".strtoupper($num_doc)."' and numero_retencion=".$num_doc_adj);
  //print_r($valuacion_obras);

  $mt = $this->cobd01_contratoobras_retencion_partidas->execute("SELECT SUM(monto) AS monto_total FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$this->ano_ejecucion()." and upper(numero_contrato_obra)='".strtoupper($num_doc)."' and numero_retencion=".$num_doc_adj);

  /*$mi = $this->cobd01_contratoobras_retencion_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$this->ano_ejecucion()." and upper(numero_contrato_obra)='".strtoupper($num_doc)."' and numero_retencion=".$num_doc_adj." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
  */
 
  /**
   * Verificacion de datos a enviar para OP
   * 
   */
  //$rif=$contrato_obra_datos[0]["v_cobd01_contratoobras_retencion"]["rif"];
  // $beneficiario=$contrato_obra_datos[0]["v_cobd01_contratoobras_retencion"]["denominacion"];
  //  $fecha=$contrato_obra_datos[0]["v_cobd01_contratoobras_retencion"]["fecha_retencion"];
  //$fecha=$sisap->Cfecha($fecha,"D/M/A");
  //$beneficiario=str_replace("'","\'",$beneficiario);


  //$var[0] = $this->ano_ejecucion();
  //$ann = $this->ano_ejecucion();
 // $var[1] = 2;
 // $fd = date('d/m/Y'); // recibe => 01/01/2000
  //$var[2] = $fd[6].$fd[7].$fd[8].$fd[9]."-".$fd[3].$fd[4]."-".$fd[0].$fd[1]; //transf => 2000-01-01
  //$opfecha= $fd;
  //$var[3] = $this->ano_ejecucion();
  //$var[4] = 6;
  //$var[5] = $num_doc;
                        
  if($tipo_ret == "1"){
    $num_op_sec = 'ret-lab';
  }else{
    $num_op_sec = 'ret-fc';
  }
  $tipo_op = 2;
  $ano_op    = $this->ano_ejecucion();
  $fecha_op = date('Y-m-d');
  $tipo_doc = 6;
  $monto_mano_obra   = 0; // 0 no hay mano de obra

  /*if(isset($num_doc_adj) && $num_doc_adj!="")
     $numero_doc_adjunto = $num_doc_adj;
  else
     $numero_doc_adjunto = 0; */

  //$fdoo = $fecha_doc_origen;
  //$fdo = $this->Cfecha($fdoo,"A-M-D");

  //$m_fdo = date("d/m/Y");
  //$m_opfecha = $fecha_doc_origen;

  //$ano_documento    = $this->ano_ejecucion();
  //$numero_documento = $num_doc;
  //$fecha_documento  = $fecha_doc_origen;

  $rif_ci = $resultado[0]["v_cobd01_contratoobras_retencion"]["rif"]; // rif
  $beneficiario = str_replace("'","\'",$resultado[0]["v_cobd01_contratoobras_retencion"]["denominacion"]); //beneficiario

  //$var[9] = $autorizado_cobrar;
  //$var[9] = str_replace('"','\"',$var[9]);
  $autorizado_cobrar = str_replace("'","\'",$resultado[0]["v_cobd01_contratoobras_retencion"]["denominacion"]);

  //$var[10] = $autorizado_cedula;
  //$var[10] = str_replace('"','\"',$var[10]);
  //$autorizado_cedula = str_replace('"','\"',$autorizado_cedula);
  
  /*if($var[10]=="" || $var[10]==null){
    $var[10]=0;
  }
  if($autorizado_cedula == "" || $autorizado_cedula == null){
    $autorizado_cedula=0;
  }*/

  $autorizado_cedula = 0;
  $tipo_pago = 1;
  $porcentaje_retencion = 0;
  $monto_laboral = 0;
  $porcentaje_fc = 0;
  $monto_fc = 0;
  $porcentaje_amort_anti = 0;
  $c_numero_pago = 1;
  $frecuencia_de_pago = 1;
  $monto_amort_anti = 0;
  $monto_isrl = 0;
  $porcentaje_isrl = 0;

  $monto_tt_partidas  = 0;
  foreach ($partidas as $partida) {
    $monto_tt_partidas += $partida["cobd01_contratoobras_retencion_partidas"]["monto"]; 
  }
  $monto_tt_partidas = $this->Formato1($monto_tt_partidas);
  
  $monto_tt_cancelar = $this->Formato1($mt[0][0]["monto_total"]);
  $fecha_desde = date('Y-m-d');
  $fecha_hasta = date('Y-m-d');
  $concepto = str_replace('"','\"',$resultado[0]["v_cobd01_contratoobras_retencion"]["concepto"]);
  $cta_banc_beneficiario = "";

  $monto_iva_partidas = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_iva"]);

  $monto_desc_imp = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_descontar_impuesto"]);

  $monto_op = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_orden_pago"]);

  $porcentaje_retencion_iva = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["porcentaje_retencion_iva"]);

  $monto_retencion_iva = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_retencion_iva"]);

  $porcentaje_islr = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["porcentaje_islr"]);

  $monto_islr = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_islr"]);

  $porcentaje_tf = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["porcentaje_timbre_fiscal"]);

  $monto_tf = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_timbre_fiscal"]);

  $porcentaje_im = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["porcentaje_impuesto_muni"]);

  $monto_im = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_impuesto_municipal"]);

  $neto_cobrar = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_neto_cobrar"]);

  $porcentaje_iva = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["porcenta_iva"]);

  $monto_sustraendo = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_sustraendo"]);

  $c_monto_total = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_orden_pago"]);

  $c_monto_parcial = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["monto_orden_pago"]);

  $monto_retencion_multa = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["retencion_multa"]);

  $monto_retencion_rs = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["retencion_responsabilida"]); 

  $rcivil = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["porcentaje_multa"]);

  $rsocial = $this->Formato1($valuacion_obras[0]["cobd01_contratoobras_retencion_cuerpo"]["porcentaje_responsabilid"]);

  
  //$var[11] = $monto_tt_partidas;
  //$var[12] = $tipo_pago;
  //$var[13] = $monto_tt_cancelar;
  //$var[14] = $porcentaje_retencion;
  //$var[15] = $monto_laboral;
  //$var[16] = $porcentaje_fc;
  //$var[17] = $monto_fc;
  //$var[18] = $monto_iva_partidas;
  //$var[19] = $monto_desc_imp;
  //$var[20] = $porcentaje_amort_anti;
  //$var[21] = $monto_amort_anti;
  //$var[22] = $monto_op;
  //$var[23] = $porcentaje_retencion_iva;
  //$var[24] = $monto_retencion_iva;
  //$var[25] = $porcentaje_isrl;
  //$var[26] = $monto_isrl;
  //$var[27] = $porcentaje_tf; // tf = Timbre fiscal
  //$var[28] = $monto_tf;
  //$var[29] = $porcentaje_im; //im = Impuesto municipal
  //$var[30] = $monto_im;
  //$var[31] = $neto_cobrar;

  //$porcentaje_iva = $porcentaje_iva;
  //$var[32] = $c_monto_total;
  //$var[33] = $c_numero_pago;
  //$var[34] = $c_monto_parcial;
  
  /*$fd1     = $c_fecha_desde;
  $fd2     = $c_fecha_hasta;
  $var[35] =  $this->Cfecha($fd1,"A-M-D");
  $var[36] =  $this->Cfecha($fd2,"A-M-D");*/
  //$var[35] =  $c_fecha_desde;
  //$var[36] =  $c_fecha_hasta;

  //$var[37] = $frecuencia_de_pago;

  /*$var[38] = $num_factura;
  $var[39] = $num_control;
  $var[40] = $fecha_factura;
  $var[41] = $monto_total;
  $var[42] = $monto_base;
  $var[43] = $f_iva;
  $var[44] = $monto_iva;*/
  

  //$var[45] = $concepto;
  /*$var[45] = str_replace('"','\"',$var[45]);
  $var[46] = $comprobante_retencion_isrl_ano;
  $var[47] = $comprobante_retencion_isrl_numero;
  $var[48] = $comprobante_retencion_timbre_fiscal_ano;
  $var[49] = $comprobante_retencion_timbre_fiscal_numero;
  $var[50] = $comprobante_retencion_municipal_ano;
  $var[51] = $comprobante_retencion_municipal_numero;
  $var[52] = $comprobante_retencion_iva_ano;
  $var[53] = $comprobante_retencion_iva_mes;
  $var[54] = $comprobante_retencion_iva_numero;
  $var[55] = $comprobante_retencion_libro_compra_dia;
  $var[56] = $comprobante_retencion_libro_compra_mes;
  $var[57] = $comprobante_retencion_libro_compra_ano;
  $var[58] = $comprobante_retencion_libro_compra_numero;*/

  //$var[59] = $monto_retencion_multa;
  //$var[60] = $monto_retencion_rs; //Responsabilidad social
  //$var[61] = $rcivil;
  //$var[62] = $rsocial;
  //$var[63] = $cta_banc_beneficiario;


  /*$monto_total_orden_contabilidad  = $monto_tt_cancelar;
  $monto_orden_pago_contabilidad   = $monto_op;
  $monto_amortizacion_contabilidad = $monto_amort_anti;
  */
  $CAMPOS_CUERPO = "cod_presi,
    cod_entidad,
    cod_tipo_inst,
    cod_inst,
    cod_dep,
    ano_orden_pago,
    numero_orden_pago,
    tipo_orden,
    fecha_orden_pago,
    ano_documento_origen,
    numero_documento_origen,
    numero_documento_adjunto,
    fecha_documento,
    cod_tipo_documento,
    rif,
    beneficiario,
    autorizado,
    cedula_identidad,
    concepto,
    monto_total,
    numero_pago,
    monto_parcial,
    cod_frecuencia_pago,
    fecha_desde,
    fecha_hasta,
    cod_tipo_pago,
    monto_coniva,
    monto_iva,
    porcentaje_iva,
    monto_siniva,
    monto_retencion_laboral,
    porcentaje_laboral,
    monto_retencion_fielcumplimiento,
    porcentaje_fielcumplimiento,
    monto_descontar_impuesto,
    amortizacion_anticipo,
    porcentaje_amortizacion,
    monto_orden_pago,
    monto_retencion_iva,
    porcentaje_retencion_iva,
    monto_islr,
    porcentaje_islr,
    monto_sustraendo,
    monto_timbre_fiscal,
    porcentaje_timbre_fiscal,
    monto_impuesto_municipal,
    porcentaje_impuesto_municipal,
    monto_neto_cobrar ,
    dia_asiento_registro,
    mes_asiento_registro,
    ano_asiento_registro,
    numero_asiento_registro,
    username_registro,
    condicion_actividad,
    ano_anulacion,
    numero_anulacion,
    dia_asiento_anulacion,
    mes_asiento_anulacion,
    ano_asiento_anulacion,
    numero_asiento_anulacion,
    username_anulacion,
    ano_movimiento,
    cod_entidad_bancaria,
    cod_sucursal,
    cuenta_bancaria,
    numero_cheque,
    fecha_cheque,
    fecha_proceso_registro,
    fecha_proceso_anulacion,
    numero_comprobante_islr,
    numero_comprobante_timbre,
    numero_comprobante_municipal,
    numero_comprobante_iva,
    numero_comprobante_librocompras,
    numero_comprobante_egreso,
    retencion_multa,
    retencion_responsabilidad,
    monto_mano_obra,
    codigo_retencion_islr,
    cod_actividad,
    porcentaje_multa,
    porcentaje_responsabilidad,
    enlace_contable,
    cuenta_bancaria_beneficiario,
    numero_orden_pago_secuencia,
    numero_documento_secuencia";

  $CAMPOS_PARTIDAS="cod_presi,
    cod_entidad,
    cod_tipo_inst,
    cod_inst,
    cod_dep,
    ano_orden_pago,
    numero_orden_pago,
    ano,
    cod_sector,
    cod_programa,
    cod_sub_prog,
    cod_proyecto,
    cod_activ_obra,
    cod_partida,
    cod_generica,
    cod_especifica,
    cod_sub_espec,
    cod_auxiliar,
    monto,
    numero_control_compromiso,
    numero_control_causado,
    numero_orden_pago_secuencia,
    numero_documento_secuencia";

        
  //case 6:
  //contrato obras retenciones
  $compromiso_partidas = $this->cobd01_contratoobras_retencion_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$this->ano_ejecucion()." and numero_contrato_obra='".$num_doc."' and numero_retencion=".$num_doc_adj);

  $total_partidas = $this->cobd01_contratoobras_retencion_partidas->findCount($this->SQLCA()." and ano_contrato_obra=".$this->ano_ejecucion()." and numero_contrato_obra='".$num_doc."' and numero_retencion=".$num_doc_adj);

  $total_partidas_iva = $this->cobd01_contratoobras_retencion_partidas->findCount($this->SQLCA()." and ano_contrato_obra=".$this->ano_ejecucion()." and numero_contrato_obra='".$num_doc."' and numero_retencion=".$num_doc_adj."  and cod_partida=403 and cod_generica=18 and cod_especifica=1");

  $partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
  //$partidas=$compromiso_partidas;
  $modelo="cobd01_contratoobras_retencion_partidas";
  $modelo_tabla="cobd01_contratoobras_retencion_partidas";
  $modelo_cuerpo1="cobd01_contratoobras_retencion_cuerpo";
  $modelo_cuerpo2="cobd01_contratoobras_retencion_cuerpo";
  $ano_up="ano_contrato_obra";
  $numero_up="numero_contrato_obra";
  $numero_up_adj=" and numero_retencion='".$num_doc_adj."'";
  $campo_ncc="numero_control_comprom";
  $n_c_c="numero_control_causado";
  //$to = 1;
  //$td = 4;
  //$ta = 12;
  //$ndo = $num_doc;
  //$nda = $num_doc_adj;
       
    
      $VALUES_CUERPO=$this->SQLCAIN().",".$ano_op.",".$num_op.",".$tipo_op.",'".$fecha_op."',".$ano_op.",'".$num_doc."',".$num_doc_adj.",'".$fecha_doc_origen."',".$tipo_doc.",'".$rif_ci."','".$beneficiario."','".$autorizado_cobrar."',".$autorizado_cedula.",'".$concepto."',".$monto_tt_partidas.",".$c_numero_pago.",".$c_monto_parcial.",".$frecuencia_de_pago.",'".$fecha_desde."','".$fecha_hasta."',".$tipo_pago.",".$monto_tt_cancelar.",".$monto_iva_partidas.",".$porcentaje_iva.",".($monto_tt_cancelar-$monto_iva_partidas).",".$monto_laboral.",".$porcentaje_retencion.",".$monto_fc.",".$porcentaje_fc.",".$monto_desc_imp.",".$monto_amort_anti.",".$porcentaje_amort_anti.",".$monto_op.",".$monto_retencion_iva.",".$porcentaje_retencion_iva.",".$monto_isrl.",".$porcentaje_isrl.",".$monto_sustraendo.",".$monto_tf.",".$porcentaje_tf.",".$monto_im.",".$porcentaje_im.",".$neto_cobrar.",0,0,0,0,'".$this->Session->read('nom_usuario')."',1,0,0,0,0,0,0,'0',0,0,0,0,0,'1900-01-01','".date("Y-m-d")."','1900-01-01',0,0,0,0,0,0,'".$monto_retencion_multa."', '".$monto_retencion_rs."', '".$monto_mano_obra."', '".$_SESSION["ventana_islr"]."', '".$_SESSION["ventana_impuesto_municipal"]."', '".$rcivil."', '".$rsocial."', '0', '".$cta_banc_beneficiario."', '".$num_op_sec."', '".$num_doc_adj."' ";

        //echo $VALUES_CUERPO;
        $resultado1=$this->cepd03_ordenpago_cuerpo->execute("INSERT INTO cepd03_ordenpago_cuerpo  ($CAMPOS_CUERPO) VALUES ($VALUES_CUERPO)");
        if($resultado1>1){

            $i=1;

            $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ano_op'", $order =null);

            if(!empty($numero_causado)){
              $numero_causado ++;
              $sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano_op' and ".$this->condicionNDEP().";";
            }else{
              $numero_causado = 1;
              $sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ano_op', '$numero_causado')";
            }
            $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);


            foreach($partidas as $partida){
                $cp   = $this->crear_partida($partida[$modelo]["ano"],$partida[$modelo]["cod_sector"],$partida[$modelo]["cod_programa"],$partida[$modelo]["cod_sub_prog"],$partida[$modelo]["cod_proyecto"],$partida[$modelo]["cod_activ_obra"],$partida[$modelo]["cod_partida"],$partida[$modelo]["cod_generica"],$partida[$modelo]["cod_especifica"],$partida[$modelo]["cod_sub_espec"],$partida[$modelo]["cod_auxiliar"]);
                $mt   = $partida[$modelo]["monto"];
                //$ccp  = $concepto;
                $rnco = $partida[$modelo][$campo_ncc];
                $rnca = $numero_causado;
                //$this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ann, $ndo, $nda, $numero_orden_pago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, $i);
                
                $dnca = $this->motor_presupuestario($cp, 1, 4, 12, date('d/m/Y'), $mt, $concepto, $ano_op, $num_doc, $num_doc_adj, $num_op, $fecha_doc_origen, null, null, null, null, $partida[$modelo][$campo_ncc], $numero_causado, null, null, $i);

                //$ncc=$dnca;

                $VPARTIDAS[]="(".$this->SQLCAIN().",".$this->ano_ejecucion().",".$num_op.",".$partida[$modelo]["ano"].",".$partida[$modelo]["cod_sector"].",".$partida[$modelo]["cod_programa"].",".$partida[$modelo]["cod_sub_prog"].",".$partida[$modelo]["cod_proyecto"].",".$partida[$modelo]["cod_activ_obra"].",".$partida[$modelo]["cod_partida"].",".$partida[$modelo]["cod_generica"].",".$partida[$modelo]["cod_especifica"].",".$partida[$modelo]["cod_sub_espec"].",".$partida[$modelo]["cod_auxiliar"].",".$partida[$modelo]["monto"].",".$rnco.",".$rnca.", '".$num_op_sec."', '".$num_doc_adj."')";

                if($tipo_doc!=1){
                  $RS_update_causado_partidas=$this->$modelo->execute("UPDATE ".$modelo_tabla." SET numero_control_causado=".$numero_causado." WHERE ".$this->SQLCA()." and ".$ano_up."=".$this->ano_ejecucion()." and ".$numero_up."='".$num_doc."' ".$numero_up_adj." and cod_sector=".$partida[$modelo]["cod_sector"]." and cod_programa=".$partida[$modelo]["cod_programa"]." and cod_sub_prog=".$partida[$modelo]["cod_sub_prog"]." and cod_proyecto=".$partida[$modelo]["cod_proyecto"]." and cod_activ_obra=".$partida[$modelo]["cod_activ_obra"]." and cod_partida=".$partida[$modelo]["cod_partida"]." and cod_generica=".$partida[$modelo]["cod_generica"]." and cod_especifica=".$partida[$modelo]["cod_especifica"]." and cod_sub_espec=".$partida[$modelo]["cod_sub_espec"]." and cod_auxiliar=".$partida[$modelo]["cod_auxiliar"]);
                }
                $i++;
            }//fin foreach partidas



            $VALUES_PARTIDAS=implode(',', $VPARTIDAS);
            $resultado2 = $this->cepd03_ordenpago_partidas->execute("INSERT INTO cepd03_ordenpago_partidas  ($CAMPOS_PARTIDAS) VALUES $VALUES_PARTIDAS");


            if($resultado2>1){

              $datos  = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$this->ano_ejecucion()."' and numero_contrato_obra='".$num_doc."' and numero_retencion='".$num_doc_adj."'");

              $datos2 = $this->cobd01_contratoobras_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$this->ano_ejecucion()."' and numero_contrato_obra='".$num_doc."'");

              $f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"];
              $f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
   
              $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                        $to = 1,
                                        $td = 9,
                                        $rif_doc = $rif_ci,
                                        $ano_dc = $this->ano_ejecucion(),
                                        $n_dc = $num_doc,
                                        $f_dc = cambiar_formato_fecha($f_dc_array_pago_aux),
                                        $cpt_dc = $concepto,
                                        $ben_dc = $autorizado_cobrar,
                                        $mon_dc = array("monto_total_orden"  => $monto_tt_cancelar,
                                                          "monto_orden_pago"   => $monto_op,
                                                          "monto_amortizacion" => $monto_amort_anti
                                                      ),
                                        $ano_op = $this->ano_ejecucion(),
                                        $n_op = $num_op,
                                        $f_op = date('d/m/Y'),
                                          $a_adj_op = null,
                                        $n_adj_op = $num_doc_adj,
                                        $f_adj_op = cambiar_formato_fecha($f_dc_adj_array_pago_aux),
                                        $tp_op = $tipo_doc,
                                          $deno_ban_pago = null,
                                        $ano_movimiento = null,
                                        $cod_ent_pago = null,
                                        $cod_suc_pago = null,
                                        $cod_cta_pago = null,
                                        $num_che_o_debi = null,
                                        $fec_che_o_debi = null,
                                        $clas_che_o_debi = null
                                        );
            }else{
              $valor_motor_contabilidad = false; 
            }

            if($valor_motor_contabilidad==true){   
              $this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=3 WHERE ".$this->SQLCA()." and ano_orden_pago=".$this->ano_ejecucion()." and numero_orden_pago=".$num_op);
              $contador = $this->$modelo_cuerpo1->findCount($this->SQLCA()." and ".$ano_up."=".$this->ano_ejecucion()." and ".$numero_up."='".$num_doc."' ".$numero_up_adj." and ano_orden_pago=0 and numero_orden_pago=0");
              if($contador!=0){
                  $upop=$this->$modelo_cuerpo1->execute("UPDATE ".$modelo_cuerpo2." SET ano_orden_pago=".$this->ano_ejecucion().", numero_orden_pago=".$num_op." WHERE ".$this->SQLCA()." and ".$ano_up."=".$this->ano_ejecucion()." and ".$numero_up."='".$num_doc."' ".$numero_up_adj);
                  $re=$this->cepd03_ordenpago_poremitir->execute("INSERT INTO cepd03_ordenpago_poremitir VALUES (".$this->SQLCAIN().",'".$this->Session->read('nom_usuario')."',".$this->ano_ejecucion().",".$num_op.")");
                  if($re>1 && $upop>1){
                    return true;
                  }else{
                    return false;
                  }
              }else{
                  return false;
              }
            }else{
              return false;
            }
        }else{
          $this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$this->ano_ejecucion()." and numero_orden_pago=".$num_op);
          return false;
        }    
}

// REVISAR QUE CARAJO ES ESTO
/*
public function cuerpoRetencion($num_op, $monto_op, $tipo_retencion)
{
  $CAMPOS_CUERPO = "cod_presi,
    cod_entidad,
    cod_tipo_inst,
    cod_inst,
    cod_dep,
    ano_orden_pago,
    clase_orden,
    numero_orden_pago,
    monto,
    fecha_proceso_registro,
    status,
    tipo_retencion,
    ano_movimiento,
    cod_entidad_bancaria,
    cod_sucursal,
    cuenta_bancaria,
    numero_cheque,
    fecha_proceso_anulacion";

  $VALUES_CUERPO = $this->SQLCA().",".$this->ano_ejecucion().",2,".$num_op.",".$monto_op.",".date('Y-m-d').",1,".$tipo_retencion.",0,0,0,0,0,'1900-01-01'"; 
}
*/

}//fin class

?>