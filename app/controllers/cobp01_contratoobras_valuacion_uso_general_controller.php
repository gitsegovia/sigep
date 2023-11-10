<?php


class Cobp01ContratoobrasValuacionUsoGeneralController extends AppController {

   var $name = "cobp01_contratoobras_valuacion_uso_general";
   var $uses = array('cscd04_ordencompra_parametros', 'cobd01_contratoobras_cuerpo', 'ccfd04_cierre_mes', 'cobd01_contratoobras_valuacion_cuerpo',
                     'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cobd01_contratoobras_partidas',
                      'cobd01_contratoobras_valuacion_partidas', 'cfpd07_obras_cuerpo', 'cepd03_ordenpago_cuerpo',
                     'ccfd03_instalacion', 'cscd04_ordencompra_partidas', 'cpcd02', 'cfpd22_numero_asiento_causado', 'cfpd22', 'cfpd05', 'cugd04',

                            'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo'

                     );
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
$this->data =null;

 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');
 $lista = "";


 $ano='';
 $ano=$this->ano_ejecucion();
 $this->set('ano',$ano);


$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ((monto_original_contrato+(aumento-disminucion))-(monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0 and ano_contrato_obra='.$ano.' and condicion_actividad=1';
$a = $this->cobd01_contratoobras_cuerpo->findAll($condicion, null,' numero_contrato_obra ASC');
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

}//fin function





function selecion($var1=null){
 $this->layout = "ajax";
 $ano='';
 $datos_orden_pagos_anteriores = "";




 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');
 $lista = "";

 $ano='';
 $ano=$this->ano_ejecucion();
 $this->set('ano',$ano);


$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ((monto_original_contrato+(aumento-disminucion))-(monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0 and ano_contrato_obra='.$ano.' and condicion_actividad=1';
$a = $this->cobd01_contratoobras_cuerpo->findAll($condicion, null,' numero_contrato_obra ASC');
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

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$var1."'  and  ano_contrato_obra=".$ano." ";
$numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
$numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion, null, 'numero_contrato_obra DESC');

$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cobd01_contratoobras_cuerpo']['rif'];
	$ano_contrato_obra = $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
	$numero_contrato_obra = $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
}//fin foreach

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
$opc     = $this->cobd01_contratoobras_valuacion_cuerpo->findCount($condicion." and ano_contrato_obra=".$ano_contrato_obra."  and        numero_contrato_obra='".$numero_contrato_obra."'  ");
$result  = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($condicion."   and ano_contrato_obra=".$ano_contrato_obra."  and  upper(numero_contrato_obra)='".strtoupper($numero_contrato_obra)."' ", null, "numero_valuacion ASC", null, null);
foreach($result as $ves){$opc = $ves['cobd01_contratoobras_valuacion_cuerpo']['numero_valuacion'];}//fin foreach


$valuacion_numero = $this->cobd01_contratoobras_valuacion_cuerpo->findCount($condicion." and ano_contrato_obra=".$ano_contrato_obra."  and numero_contrato_obra='".$numero_contrato_obra."' and condicion_actividad=1 ");
$valuacion_numero++;
$this->set('valuacion_numero', $valuacion_numero);


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
}//fin foreach


if($objeto_rif != "" && $denominacion_rif!=""){



$datos_contrato_obra_anteriores         = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($condicion."  and ano_contrato_obra=".$ano_contrato_obra."  and numero_contrato_obra='".$numero_contrato_obra."'  and condicion_actividad=1", null, null, null, null);
$datos_orden_pagos_anteriores_partidas = $this->cobd01_contratoobras_valuacion_partidas->findAll($condicion, null, 'numero_contrato_obra DESC');






$datos_cepd03_ordenpago_cuerpo         = $this->cepd03_ordenpago_cuerpo->findAll($condicion."  and ano_documento_origen=".$ano_contrato_obra."  and numero_documento_origen='".$numero_contrato_obra."'  and condicion_actividad=1  and cod_tipo_documento='5'  ", null, null, null, null);
$this->set('datos_cepd03_ordenpago_cuerpo'  , $datos_cepd03_ordenpago_cuerpo);





$opc++;




//////////***********************  PARAMETROS   **********************************///////////////
$porcentaje_anticipo = 0;
$factor_reversion = "";
$anticipo_incluye_iva = "";

$anticipo_incluye_iva = "0";
$porcentaje_anticipo  = "0";
$porcentaje_iva       = "0";


 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
 $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
    foreach($parametros_datos as $aux_22){
      $anticipo_incluye_iva = $aux_22['cscd04_ordencompra_parametros']['anticipo_incluye_iva'];
      $porcentaje_anticipo  = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
      $porcentaje_iva       = $aux_22['cscd04_ordencompra_parametros']['porcentaje_iva'];
    }//fin foreach


//////////***********************  FIN PARAMETROS   ******************************///////////////


$this->detalles_del_pago($objeto_rif, $ano_contrato_obra, $numero_contrato_obra, $exento_islr_cooperativa);

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

//$this->opcion_pago($ano, $numero_contrato_obra, '2');

$this->set('porcentaje_iva', $porcentaje_iva);
$this->set('ano_contrato_obra_pago', $ano);
$this->set('numero_contrato_obra_pago', $opc);
$this->set('datos_contrato_obra', $numero_datos);
$this->set('datos_contrato_obra_partidas', $numero_datos_partidas);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('direccion_comercial_rif', $direccion_comercial_rif);
$this->set('datos_contrato_obra_anteriores', $datos_contrato_obra_anteriores);
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
  echo'document.getElementById("porce_retencion_fiel_cumplimiento").disabled = true;';
  echo'document.getElementById("porce_retencion_laboral").disabled = true;';
  echo'document.getElementById("monto_fiel_cumplimiento").disabled = true;';
  echo'document.getElementById("monto_laboral").disabled = true;';


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
echo" cobp01_contratoobras_valuacion_uso_general_pregunta_pago_parcial('".$opcion."');";
echo"</script>";



}//function









function detalles_del_pago($objeto_rif, $ano_orden_compra, $numero_orden_compra, $exento_islr_cooperativa){


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



$this->set('porcentaje_fiel_cumplimiento' , $porcentaje_fiel_cumplimiento);
$this->set('porcentaje_laboral'           , $porcentaje_laboral);



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
}//fin foreach


/**
 *
 *
 * LA OPCION 1 ES PARA QUE EN JAVASCRIPT LO VEA CON EL MONTO COMPLETO EN EL MONTO DEL CONTRATO AL CALCULAR EL PORCENTAJE DE LA AMORTIZACION
 *
 * NO IMPORTA EL 1 O 2 PARA QUE EL JAVASCRIPT LO VEA CON MONTO COMPLETO DEBE SER EL 1 ESTA OPCION ESTA BIEN NO TE CONFUNDAS
 */


$anticipo_con_iva = 1;

$this->set('objeto_rif', $objeto_rif);


switch($objeto_rif){


case'1':{

   $impuesto_sobre_la_renta   =    $porcentaje_islr_juridico;
   $timbre_fiscal             =    $porcentaje_timbre_fiscal;
   $impuesto_municipal        =    $porcentaje_impuesto_municipal;
   $amortizacion_del_anticipo =    $porcentaje_anticipo;
   $sustraendo2 = "0.00";

   $monto_actual = $monto_original_contrato + ($modificacion_aumento - $modificacion_disminucion);
   if($monto_actual<$desde_monto_timbre){$timbre_fiscal=0;
   	echo '<script>';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	echo '</script>';
   	}//fin if


	$this->set('porcentaje_retencion_iva'  , $porcentaje_retencion_iva);
	$this->set('impuesto_sobre_la_renta'   , $impuesto_sobre_la_renta);
	$this->set('timbre_fiscal'             , $timbre_fiscal);
	$this->set('impuesto_municipal'        , $impuesto_municipal);
	$this->set('amortizacion_del_anticipo' , $amortizacion_del_anticipo);
	$this->set('anticipo_con_iva'          , $anticipo_con_iva);
	$this->set('anticipo_con_iva2'         , $retencion_incluye_iva);
	$this->set('sustraendo'                , $this->Formato2($sustraendo2));


}break;




case'2':{


   $impuesto_sobre_la_renta   =    $porcentaje_islr_juridico;
   $timbre_fiscal             =    $porcentaje_timbre_fiscal;
   $impuesto_municipal        =    $porcentaje_impuesto_municipal;
   $amortizacion_del_anticipo =    $porcentaje_anticipo;
   $sustraendo2 = "0.00";



    $monto_actual = $monto_original_contrato + ($modificacion_aumento - $modificacion_disminucion);
   if($monto_actual<$desde_monto_timbre){$timbre_fiscal=0;
   	echo '<script>';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	echo '</script>';
   	}//fin if


	$this->set('porcentaje_retencion_iva'  , $porcentaje_retencion_iva);
	$this->set('impuesto_sobre_la_renta'   , $impuesto_sobre_la_renta);
	$this->set('timbre_fiscal'             , $timbre_fiscal);
	$this->set('impuesto_municipal'        , $impuesto_municipal);
	$this->set('amortizacion_del_anticipo' , $amortizacion_del_anticipo);
	$this->set('anticipo_con_iva'          , $anticipo_con_iva);
	$this->set('anticipo_con_iva2'         , $retencion_incluye_iva);
	$this->set('sustraendo'                , $this->Formato2($sustraendo2));



}break;




case'3':{

/*

	echo '<script>';
   	  echo'document.getElementById("retencion_incluye_iva").readOnly = true;';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	  echo'document.getElementById("impuesto_municipal").readOnly = true;';
   	  echo'document.getElementById("impuesto_sobre_la_renta").readOnly = true;';
   	  echo'document.getElementById("amortizacion_del_anticipo").readOnly = true;';
   	  echo'document.getElementById("porce_retencion_laboral").readOnly = true;';
   	  echo'document.getElementById("porce_retencion_fiel_cumplimiento").readOnly = true;';
   	echo '</script>';
          $amortizacion_del_anticipo = $porcentaje_anticipo;
      	  $impuesto_islr = 0;
   	if($exento_islr_cooperativa==2){
          $impuesto_islr = $porcentaje_islr_natural;
   	}//fin if

	$this->set('porcentaje_retencion_iva'  , 0);
	$this->set('retencion_incluye_iva'     , $retencion_incluye_iva);
	$this->set('impuesto_sobre_la_renta'   , $impuesto_islr);
	$this->set('timbre_fiscal'             , 0);
	$this->set('impuesto_municipal'        , 0);
	$this->set('sustraendo', '');
	$this->set('anticipo_con_iva'          , 0);
	$this->set('anticipo_con_iva2'         , 0);
	$this->set('amortizacion_del_anticipo' , $amortizacion_del_anticipo);

*/



   $impuesto_sobre_la_renta   =    $porcentaje_islr_juridico;
   $timbre_fiscal             =    $porcentaje_timbre_fiscal;
   $impuesto_municipal        =    $porcentaje_impuesto_municipal;
   $amortizacion_del_anticipo =    $porcentaje_anticipo;
   $sustraendo2 = "0.00";



    $monto_actual = $monto_original_contrato + ($modificacion_aumento - $modificacion_disminucion);
   if($monto_actual<$desde_monto_timbre){$timbre_fiscal=0;
   	echo '<script>';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	echo '</script>';
   	}//fin if


	$this->set('porcentaje_retencion_iva'  , $porcentaje_retencion_iva);
	$this->set('impuesto_sobre_la_renta'   , $impuesto_sobre_la_renta);
	$this->set('timbre_fiscal'             , $timbre_fiscal);
	$this->set('impuesto_municipal'        , $impuesto_municipal);
	$this->set('amortizacion_del_anticipo' , $amortizacion_del_anticipo);
	$this->set('anticipo_con_iva'          , $anticipo_con_iva);
	$this->set('anticipo_con_iva2'         , $retencion_incluye_iva);
	$this->set('sustraendo'                , $this->Formato2($sustraendo2));


}break;




case'4':{



   $impuesto_sobre_la_renta   =    $porcentaje_islr_natural;
   $timbre_fiscal             =    $porcentaje_timbre_fiscal;
   $impuesto_municipal        =    $porcentaje_impuesto_municipal;
   $amortizacion_del_anticipo =    $porcentaje_anticipo;

   $sustraendo2 = $sustraendo * $porcentaje_islr_natural;

   //$sustraendo2 =$this->Formato1($sustraendo2);
   //$sustraendo2 =$this->Formato2($sustraendo2);



    $monto_actual = $monto_original_contrato + ($modificacion_aumento - $modificacion_disminucion);
    if($monto_actual < $desde_monto_natural){$impuesto_sobre_la_renta = '0.00'; $sustraendo2=0;
    echo '<script>';
   	  echo'document.getElementById("impuesto_sobre_la_renta").readOnly = true;';
   	echo '</script>';
    }


    if($monto_actual<$desde_monto_timbre){$timbre_fiscal=0;
    echo '<script>';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	echo '</script>';}

	$this->set('porcentaje_retencion_iva'  , $porcentaje_retencion_iva);
	$this->set('retencion_incluye_iva'     , $retencion_incluye_iva);
	$this->set('impuesto_sobre_la_renta'   , $impuesto_sobre_la_renta);
	$this->set('timbre_fiscal'             , $timbre_fiscal);
	$this->set('impuesto_municipal'        , $impuesto_municipal);
	$this->set('amortizacion_del_anticipo' , $amortizacion_del_anticipo);
		$this->set('anticipo_con_iva'          , $anticipo_con_iva);
		$this->set('anticipo_con_iva2'         , $retencion_incluye_iva);
		$this->set('sustraendo'                , $this->Formato2($sustraendo2));
		$this->set('sustraendo_neto'           , $sustraendo);


}break;




case'5':{


	echo '<script>';
   	  echo'document.getElementById("retencion_incluye_iva").readOnly = true;';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	  echo'document.getElementById("impuesto_municipal").readOnly = true;';
   	  echo'document.getElementById("impuesto_sobre_la_renta").readOnly = true;';
   	  echo'document.getElementById("amortizacion_del_anticipo").readOnly = true;';
   	  echo'document.getElementById("porce_retencion_laboral").readOnly = true;';
   	  echo'document.getElementById("porce_retencion_fiel_cumplimiento").readOnly = true;';
   	echo '</script>';

   	$amortizacion_del_anticipo =    $porcentaje_anticipo;

	$this->set('porcentaje_retencion_iva'  , 0);
	$this->set('retencion_incluye_iva'     , $retencion_incluye_iva);
	$this->set('impuesto_sobre_la_renta'   , 0);
	$this->set('timbre_fiscal'             , 0);
	$this->set('impuesto_municipal'        , 0);
	$this->set('sustraendo', '');
	$this->set('anticipo_con_iva'          , 0);
	$this->set('anticipo_con_iva2'         , 0);
	$this->set('amortizacion_del_anticipo' , $amortizacion_del_anticipo);


}break;





case'6':{

    $amortizacion_del_anticipo =    $porcentaje_anticipo;

	echo '<script>';
   	  echo'document.getElementById("retencion_incluye_iva").readOnly = true;';
   	  echo'document.getElementById("timbre_fiscal").readOnly = true;';
   	  echo'document.getElementById("impuesto_municipal").readOnly = true;';
   	  echo'document.getElementById("impuesto_sobre_la_renta").readOnly = true;';
   	  echo'document.getElementById("amortizacion_del_anticipo").readOnly = true;';
   	  echo'document.getElementById("porce_retencion_laboral").readOnly = true;';
   	  echo'document.getElementById("porce_retencion_fiel_cumplimiento").readOnly = true;';
   	echo '</script>';

	$this->set('porcentaje_retencion_iva'  , 0);
	$this->set('retencion_incluye_iva'     , $retencion_incluye_iva);
	$this->set('impuesto_sobre_la_renta'   , 0);
	$this->set('timbre_fiscal'             , 0);
	$this->set('impuesto_municipal'        , 0);
	$this->set('sustraendo', '');
	$this->set('anticipo_con_iva'          , 0);
	$this->set('anticipo_con_iva2'         , 0);
	$this->set('amortizacion_del_anticipo' , $amortizacion_del_anticipo);


}break;





}//fin switch







}//fin fucntion







function  opcion_pago($ano=null, $numero_contrato_obra=null, $opc){

 $this->layout = "ajax";

$ano_orden_compra = $this->ano_ejecucion();

if($opc == "2"){
		echo"<script>";
  		echo"cobp01_contratoobras_valuacion_uso_general_pregunta_pago_parcial('".$opc."');";
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
  echo'document.getElementById("porce_retencion_fiel_cumplimiento").disabled = true;';
  echo'document.getElementById("porce_retencion_laboral").disabled = true;';
  echo'document.getElementById("monto_fiel_cumplimiento").disabled = true;';
  echo'document.getElementById("monto_laboral").disabled = true;';


  echo'cscp03_cotizacion_cuerpo_moneda("TOTALINGRESOS", "0.00");';

   echo' for(ii=0; ii<document.getElementById("cuenta_i").value; ii++){ ';
   echo'     document.getElementById("pago_"+ii).disabled = true; ';
   echo'     document.getElementById("pago_"+ii).value = "0,00"; ';
   echo' }//fin for ';

echo'</script>';
}


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


 $i_lenght = $this->data['cobp01_contratoobras_valuacion_uso_general']['cuenta_i'];

  $ano_contrato_obra                      =       $this->data['cobp01_contratoobras_valuacion_uso_general']['ano_contrato_obra'];
  $num_contrato_obra                      =       $this->data['cobp01_contratoobras_valuacion_uso_general']['num_contrato_obra'];
  $numero_contrato_obra                   =       $num_contrato_obra;
  $ano_orden_compra_autorizacion          =       $ano_contrato_obra;

  $ann = $ano_contrato_obra;
  $numero_valuacion                      =       $this->data['cobp01_contratoobras_valuacion_uso_general']['numero_valuacion'];
  $nda = $numero_valuacion;

  $fecha_valuacion              =       $this->data['cobp01_contratoobras_valuacion_uso_general']['fecha_valuacion'];
  $fd = $fecha_valuacion;



  $monto_original_contrato              =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_contrato']);
  $monto_retenido_laboral               =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['retencion_laboral']);
  $monto_retenido_fielcumplimiento      =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['fiel_cumplimiento']);



  $porcentaje_amortizacion              =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['amortizacion_del_anticipo']);
  $total_retencion_monto_iva            =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['total_retencion_monto_iva']);
  $monto_descontar_impuesto             =       $total_retencion_monto_iva;
  $monto_orden_de_pago_monto_iva        =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_orden_de_pago_monto_iva']);
  $monto_a_pagar_monto_iva              =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_a_pagar_monto_iva']);
  $concepto_contrato_obra               =       $this->data['cobp01_contratoobras_valuacion_uso_general']['concepto'];


  $monto_orden                          =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_orden_de_pago_monto_iva']);
  $monto_cancelado2                     =       $monto_orden;
  $monto_neto_cobrar                    =       $monto_a_pagar_monto_iva;

  $aumento                              =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['aumento']);
  $disminucion                          =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['disminucion']);

  $monto_anticipo                       =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_anticipo']);
  $monto_amortizacion                   =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_amortizacion']);
  $monto_cancelado                      =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_cancelado']);

  $monto_iva                            =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_iva']);
  $monto_cancelar                       =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_a_pagar_con_iva']);
  $monto_cancelar_siniva                =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_sin_iva']);
  $amortizacion_anticipo                =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['amortizacion_del_anticipo_monto_iva']);
  $monto_islr                           =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['impuesto_sobre_la_renta_monto_iva']);
  $porcentaje_islr                      =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['impuesto_sobre_la_renta']);
  $anticipo_con_iva                     =       $this->data['cobp01_contratoobras_valuacion_uso_general']['anticipo_con_iva'];
  $anticipo_con_iva2                    =       $this->data['cobp01_contratoobras_valuacion_uso_general']['anticipo_con_iva2'];


  $monto_sustraendo                     =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['sustraendo']);
  $monto_timbre_fiscal                  =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['timbre_fiscal_monto_iva']);

  $porcentaje_timbre_fiscal             =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['timbre_fiscal']);
  $monto_impuesto_municipal             =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['impuesto_municipal_monto_iva']);

  $porcentaje_impuesto_municipal        =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['impuesto_municipal']);
  $monto_retencion_iva                  =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['retencion_incluye_iva_monto_iva']);
  $porcentaje_retencion_iva             =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['retencion_incluye_iva']);


  $porcentaje_fiel_cumplimiento         =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['porcentaje_fiel_cumplimiento']);
  $monto_retencion_laboral              =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_laboral']);
  $porcentaje_fielcumplimiento          =       $porcentaje_fiel_cumplimiento;


  $monto_retencion_fielcumplimiento     =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['monto_fiel_cumplimiento']);
  $porcentaje_laboral                   =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['porce_retencion_laboral']);
  $saldo_orden                          =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['saldo_contrato']);


  $aumento_obra_extra              = 0;
  $aumento_reconsideracion_precio  = 0;
  $aumento_obras                   = 0;


  $porcentaje_iva_aplicado              =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['porcentaje_iva']);
  $oficio_aprobacion                    =       $this->data['cobp01_contratoobras_valuacion_uso_general']['numero_aprobacion'];
  $fecha_aprobacion                     =       $this->data['cobp01_contratoobras_valuacion_uso_general']['fecha_aprobacion'];
  $periodo_desde                        =       $this->data['cobp01_contratoobras_valuacion_uso_general']['desde_periodo'];
  $periodo_hasta                        =       $this->data['cobp01_contratoobras_valuacion_uso_general']['hasta_periodo'];
  $retencion_incluye_iva                =       $anticipo_con_iva2;
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
  $monto_orden_pago                     =        $monto_orden;
  $ano_anulacion                        =        0;
  $numero_anulacion                     =        0;




  $retencion_multa_monto                        =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['retencion_multa_monto']);
  $retencion_responsabilidad_social             =       $this->Formato1($this->data['cobp01_contratoobras_valuacion_uso_general']['retencion_responsabilidad_social']);





$datos[0][0]['aumento_obra_extra'] = "";
$datos[0][0]['aumento_reconsideracion_precio'] = "";
$datos[0][0]['aumento_obras'] = "";

  $datos = $this->cobd01_contratoobras_valuacion_cuerpo->execute(" SELECT

                   	  		      (SELECT SUM(aumento_obra_extra)              FROM  cobd01_contratoobras_modificacion_cuerpo   x WHERE x.cod_presi=b.cod_presi and x.cod_entidad=b.cod_entidad and x.cod_tipo_inst=b.cod_tipo_inst and x.cod_dep = b.cod_dep and x.ano_contrato_obra  = '".$ano_contrato_obra."'  and  x.numero_contrato_obra = '".$numero_contrato_obra."') as aumento_obra_extra,
                                  (SELECT SUM(aumento_reconsideracion_precio)  FROM  cobd01_contratoobras_modificacion_cuerpo   x WHERE x.cod_presi=b.cod_presi and x.cod_entidad=b.cod_entidad and x.cod_tipo_inst=b.cod_tipo_inst and x.cod_dep = b.cod_dep and x.ano_contrato_obra  = '".$ano_contrato_obra."'  and  x.numero_contrato_obra = '".$numero_contrato_obra."') as aumento_reconsideracion_precio,
                                  (SELECT SUM(aumento_obras)                   FROM  cobd01_contratoobras_modificacion_cuerpo   x WHERE x.cod_presi=b.cod_presi and x.cod_entidad=b.cod_entidad and x.cod_tipo_inst=b.cod_tipo_inst and x.cod_dep = b.cod_dep and x.ano_contrato_obra  = '".$ano_contrato_obra."'  and  x.numero_contrato_obra = '".$numero_contrato_obra."') as aumento_obras


                                         from  cobd01_contratoobras_cuerpo b

                             	where

                             			b.cod_presi            =  ".$cod_presi."             and
									    b.cod_entidad          =  ".$cod_entidad."           and
									    b.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    b.cod_inst             =  ".$cod_inst."              and
									    b.cod_dep              =  ".$cod_dep."               and
									    b.ano_contrato_obra    = '".$ano_contrato_obra."'    and
                             			b.numero_contrato_obra = '".$numero_contrato_obra."' and
                             			b.condicion_actividad  =  1;
                   	  		");


if(isset($datos[0][0]['aumento_obra_extra'])){$aumento_obra_extra  = $datos[0][0]['aumento_obra_extra'];}else{$aumento_obra_extra  = "";}
if(isset($datos[0][0]['aumento_reconsideracion_precio'])){$aumento_reconsideracion_precio  = $datos[0][0]['aumento_reconsideracion_precio'];}else{$aumento_reconsideracion_precio  = "";}
if(isset($datos[0][0]['aumento_obras'])){$aumento_obras  = $datos[0][0]['aumento_obras'];}else{$aumento_obras  = "";}


if($aumento_obra_extra==""){$aumento_obra_extra=0;}
if($aumento_reconsideracion_precio==""){$aumento_reconsideracion_precio=0;}
if($aumento_obras==""){$aumento_obras=0;}


$monto_nuevo  = $saldo_orden - $monto_cancelar;
$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  upper(numero_contrato_obra)='".strtoupper($num_contrato_obra)."'  and  ano_contrato_obra=".$ano_contrato_obra." ";
$datos_orden_pagos_anteriores = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($condicion, null, null, null, null);


$sw2 = 0;
$sw3 = 0;
$sw4 = 0;


$sql=" BEGIN; INSERT INTO cobd01_contratoobras_valuacion_cuerpo (cod_presi, cod_entidad, cod_tipo_inst , cod_inst, cod_dep, ano_contrato_obra, numero_contrato_obra, numero_valuacion, monto_original_contrato, aumento, disminucion, monto_anticipo, monto_amortizacion, monto_retenido_laboral, monto_retenido_fielcumplimiento, monto_cancelado, monto_coniva, monto_iva, porcentaje_iva, monto_siniva, monto_retencion_laboral, porcentaje_laboral, monto_retencion_fielcumplimiento, porcentaje_fielcumplimiento, monto_descontar_impuesto, amortizacion_anticipo, porcentaje_amortizacion, monto_orden_pago, monto_retencion_iva, porcentaje_retencion_iva, monto_islr, porcentaje_islr, monto_sustraendo, monto_timbre_fiscal, porcentaje_timbre_fiscal, monto_impuesto_municipal, porcentaje_impuesto_municipal, monto_neto_cobrar, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, username_registro, condicion_actividad, ano_anulacion, numero_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, numero_asiento_anulacion, fecha_proceso_anulacion, username_anulacion, ano_orden_pago, numero_orden_pago, oficio_aprobacion, fecha_aprobacion, periodo_desde, periodo_hasta, retencion_incluye_iva, fecha_valuacion, fecha_proceso_registro, concepto, aumento_obra_extra, aumento_reconsideracion_precio,  aumento_obras, retencion_multa, retencion_responsabilidad)";
$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst ."', '".$cod_inst."', '".$cod_dep."', '".$ano_contrato_obra."', '".$numero_contrato_obra."', '".$numero_valuacion."', '".$monto_original_contrato."', '".$aumento."', '".$disminucion."', '".$monto_anticipo."', '".$monto_amortizacion."', '".$monto_retenido_laboral."', '".$monto_retenido_fielcumplimiento."', '".$monto_cancelado."', '".$monto_cancelar."', '".$monto_iva."', '".$porcentaje_iva_aplicado."', '".$monto_cancelar_siniva."', '".$monto_retencion_laboral."', '".$porcentaje_laboral."', '".$monto_retencion_fielcumplimiento."', '".$porcentaje_fielcumplimiento."', '".$monto_descontar_impuesto."', '".$amortizacion_anticipo."', '".$porcentaje_amortizacion."', '".$monto_orden_pago."', '".$monto_retencion_iva."', '".$porcentaje_retencion_iva."', '".$monto_islr."', '".$porcentaje_islr."', '".$monto_sustraendo."', '".$monto_timbre_fiscal."', '".$porcentaje_timbre_fiscal."', '".$monto_impuesto_municipal."', '".$porcentaje_impuesto_municipal."', '".$monto_neto_cobrar."', '".$dia_asiento_registro."', '".$mes_asiento_registro."', '".$ano_asiento_registro."', '".$numero_asiento_registro."', '".$username_registro."', '".$condicion_actividad."', '".$ano_anulacion."', '".$numero_anulacion."', '".$dia_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$fecha_proceso_anulacion."', '".$username_anulacion."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$oficio_aprobacion."', '".$this->Cfecha($fecha_aprobacion, 'A-M-D')."', '".$this->Cfecha($periodo_desde, 'A-M-D')."', '".$this->Cfecha($periodo_hasta, 'A-M-D')."', '".$retencion_incluye_iva."', '".$this->Cfecha($fecha_valuacion, 'A-M-D')."', '".$this->Cfecha($fecha_proceso_registro, 'A-M-D')."', '".$concepto_contrato_obra."', '".$aumento_obra_extra."', '".$aumento_reconsideracion_precio."',  '".$aumento_obras."', '".$retencion_multa_monto."', '".$retencion_responsabilidad_social."'); ";
$sw = $this->cobd01_contratoobras_valuacion_cuerpo->execute($sql);


$i_aux = 0;


$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  upper(numero_contrato_obra)='".strtoupper($num_contrato_obra)."'  and  ano_contrato_obra=".$ano_contrato_obra." ";
$numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion, null, 'numero_contrato_obra DESC');
$a=0;
if($sw>1){
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
   if($partidas_aux==$this->data['partidas']['partidas_'.$i]){$partidas_vista['pago_'.$i_aux] = $this->data['cobp01_contratoobras_valuacion_uso_general']['pago_'.$i]; $i_aux++;}
}//fin foreach



}//fin foreach




///////////////////////////////////////////////REDONDEO AMORTIZACION Y RENTENCION LABORAL Y RETENCION FIEL CUMPLIMIENTO Y CANCELADO///////////////////////////////////////////
$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  upper(numero_contrato_obra)='".strtoupper($num_contrato_obra)."'  and  ano_contrato_obra=".$ano_contrato_obra." ";
$numero_datos_partidas_anteriores = $this->cobd01_contratoobras_valuacion_partidas->findAll($condicion);
$f = 0;
$monto_ante             = 0;
$amortizacion_ante      = 0;
$retencion_laboral_ante = 0;
$retencion_laboral_ante = 0;

$amortizacion_aux_partidas                = 0;
$retencion_laboral_aux_partidas           = 0;
$retencion_fielcumplimiento_aux_partidas  = 0;
$retencion_fielcumplimiento_ante          = 0;
$retencion_laboral_ante                   = 0;

//$amortizacion_anticipo            ::: ES EL TOTAL DE A AMORTIZAR EN ESTA VALUACIÓN
//$amortizacion_aux_partidas        ::: ES LA SUMATORIA DE LA AMORTIZACION DE LAS PARTIDAS


//$monto_retencion_laboral                ::: ES EL TOTAL DE A RETENCION LABORAL EN ESTA VALUACIÓN
//$retencion_laboral_aux_partidas         ::: ES LA SUMATORIA DE LA RETENCION LABORAL

//$monto_retencion_fielcumplimiento                ::: ES EL TOTAL DE A RETENCION FIEL CUMPLIMIENTO EN ESTA VALUACIÓN
//$retencion_fielcumplimiento_aux_partidas         ::: ES LA SUMATORIA DE LA RETENCION FIEL CUMPLIMIENTO





foreach($numero_datos_partidas_anteriores as $aux_partidas_anteriores){
	  $cod_presi_ante[$f]                     =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_presi'];
	  $cod_entidad_ante[$f]                   =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_entidad'];
	  $cod_tipo_inst_ante[$f]                 =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_tipo_inst'];
	  $cod_inst_ante[$f]                      =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_inst'];
	  $cod_dep_ante[$f]                       =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_dep'];
	  $fno_orden_compra_ante[$f]              =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['ano_contrato_obra'];
	  $numero_orden_compra_ante[$f]           =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['numero_contrato_obra'];
	  $ano_partidas_ante[$f]                  =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['ano'];
	  $cod_sector_ante[$f]                    =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_sector'];
	  $cod_programa_ante[$f]                  =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_programa'];
	  $cod_sub_prog_ante[$f]                  =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_sub_prog'];
	  $cod_proyecto_ante[$f]                  =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_proyecto'];
	  $cod_activ_obra_ante[$f]                =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_activ_obra'];
	  $cod_partida_ante[$f]                   =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_partida'];
	  $cod_generica_ante[$f]                  =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_generica'];
	  $cod_especifica_ante[$f]                =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_especifica'];
	  $cod_sub_espec_ante[$f]                 =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_sub_espec'];
	  $cod_auxiliar_ante[$f]                  =      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['cod_auxiliar'];
	  $monto_ante                            +=      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['monto'];
	  $amortizacion_ante                     +=      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['amortizacion'];
	  $retencion_laboral_ante                +=      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['retencion_laboral'];
	  $retencion_fielcumplimiento_ante       +=      $aux_partidas_anteriores['cobd01_contratoobras_valuacion_partidas']['retencion_fielcumplimi'];
$f++;
}//fin for














$am_cont = 0;
for($i=0; $i<$i_lenght; $i++){
	       $var[$i]['pago']=$partidas_vista['pago_'.$i];
	 if($var[$i]['pago']!="0,00"){  $var[$i]['pago'] = $this->Formato1($var[$i]['pago']); $am_cont++;
        $amortizacion_aux[$am_cont]                  = 0;
        $retencion_laboral_aux[$am_cont]             = 0;
		$retencion_fielcumplimiento_aux[$am_cont]    = 0;
	 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
        $datos_cscd04_ordencompra_partidas    =     $this->cobd01_contratoobras_partidas->findAll($sql_where[$i]);
        foreach($datos_cscd04_ordencompra_partidas as $aux_datos_cscd04_ordencompra_partidas){
						$anticipo                         =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['anticipo'];
						$amortizacion                     =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['amortizacion'];
						$cancelado                        =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['cancelacion'];
						$retencion_laboral                =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['retencion_laboral'];
						$retencion_fielcumplimiento       =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['retencion_fielcumplimiento'];
					}//fin foreach


         if($monto_nuevo=="0"){
                   if($anticipo_con_iva2==1){

				             $retencion_laboral_aux[$am_cont]          = ($var[$i]['pago'] * $porcentaje_laboral) / 100;
				     	     $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $porcentaje_fielcumplimiento) / 100;
					  }else{
				          if($concate!="403.18.01.00"){
				             $retencion_laboral_aux[$am_cont]          = ($var[$i]['pago'] * $porcentaje_laboral) / 100;
				     	     $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $porcentaje_fielcumplimiento) / 100;
				            }//fin if
					   }//fin else


		             	if($concate!="403.18.01.00"){
		                  $amortizacion_aux[$am_cont] = $anticipo - $amortizacion;
		             	}//fin if

             }else{


             	if($anticipo_con_iva==1 && $monto_iva!=0){
             		      if($concate!="403.18.01.00"){
				     	     $amortizacion_aux[$am_cont]               = (($var[$i]['pago'] + ($var[$i]['pago'] * $porcentaje_iva_aplicado/100)) *  $porcentaje_amortizacion) / 100;
             		        // $amortizacion_aux[$am_cont]               = $amortizacion_anticipo;
             		      }
				  }else{
				          if($concate!="403.18.01.00"){
				             $amortizacion_aux[$am_cont]               = ($var[$i]['pago'] * $porcentaje_amortizacion) / 100;
				             //$amortizacion_aux[$am_cont]               = $amortizacion_anticipo;
				            }//fin if
					   }//fin else

				if($anticipo_con_iva2==1){
				             $retencion_laboral_aux[$am_cont]          = ($var[$i]['pago'] * $porcentaje_laboral) / 100;
				     	     $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $porcentaje_fielcumplimiento) / 100;
					  }else{
				          if($concate!="403.18.01.00"){
				             $retencion_laboral_aux[$am_cont]          = ($var[$i]['pago'] * $porcentaje_laboral) / 100;
				     	     $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $porcentaje_fielcumplimiento) / 100;
				            }//fin if
					   }//fin else
             }//fin else

             $amortizacion_aux[$am_cont]               = $this->Formato2($amortizacion_aux[$am_cont]);
             $amortizacion_aux[$am_cont]               = $this->Formato1($amortizacion_aux[$am_cont]);

             $retencion_laboral_aux[$am_cont]          = $this->Formato2($retencion_laboral_aux[$am_cont]);
             $retencion_laboral_aux[$am_cont]          = $this->Formato1($retencion_laboral_aux[$am_cont]);

             $retencion_fielcumplimiento_aux[$am_cont] = $this->Formato2($retencion_fielcumplimiento_aux[$am_cont]);
             $retencion_fielcumplimiento_aux[$am_cont] = $this->Formato1($retencion_fielcumplimiento_aux[$am_cont]);

         $amortizacion_aux_partidas                  += $amortizacion_aux[$am_cont];
         $retencion_laboral_aux_partidas             += $retencion_laboral_aux[$am_cont];
         $retencion_fielcumplimiento_aux_partidas    += $retencion_fielcumplimiento_aux[$am_cont];

	 }//fin if
}//fin for


      $amortizacion_aux_partidas          = $this->Formato2($amortizacion_aux_partidas);
      $amortizacion_aux_partidas          = $this->Formato1($amortizacion_aux_partidas);

      $retencion_laboral_aux_partidas          = $this->Formato2($retencion_laboral_aux_partidas);
      $retencion_laboral_aux_partidas          = $this->Formato1($retencion_laboral_aux_partidas);

      $retencion_fielcumplimiento_aux_partidas          = $this->Formato2($retencion_fielcumplimiento_aux_partidas);
      $retencion_fielcumplimiento_aux_partidas          = $this->Formato1($retencion_fielcumplimiento_aux_partidas);


$datos_contrato_obra    =     $this->cobd01_contratoobras_cuerpo->findAll($condicion);
foreach($datos_contrato_obra as $aux_datos_contrato_obra){
		$monto_anticipo_aux2                      =     $aux_datos_contrato_obra['cobd01_contratoobras_cuerpo']['monto_anticipo'];
		$monto_amortizacion_aux2                  =     $aux_datos_contrato_obra['cobd01_contratoobras_cuerpo']['monto_amortizacion'];
		$monto_cancelado_aux2                     =     $aux_datos_contrato_obra['cobd01_contratoobras_cuerpo']['monto_cancelado'];
		$monto_retencion_laboral_aux2             =     $aux_datos_contrato_obra['cobd01_contratoobras_cuerpo']['monto_retencion_laboral'];
		$monto_retencion_fielcumplimiento_aux2    =     $aux_datos_contrato_obra['cobd01_contratoobras_cuerpo']['monto_retencion_fielcumplimiento'];
}//fin foreach









//////////////////////////////////////////////////////REDONDEO/////////////////////////////////////////////////////////////////





             //$amortizacion_aux[$am_cont]
             //$retencion_laboral_aux[$am_cont]
             //$retencion_fielcumplimiento_aux[$am_cont]
             //$var[$i]['pago']





$contar_otra = 0;
 for($i=0; $i<$i_lenght; $i++){
 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
       if($concate=="403.18.01.00"){

	   }else{
	   	         $contar_otra++;
	   }
 }//fin for
if($contar_otra==1){$am_cont=0;
                    for($i=0; $i<$i_lenght; $i++){ $am_cont++;
								 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
								       if($concate!="403.18.01.00"){
                                             $amortizacion_aux[$am_cont] = $amortizacion_anticipo;
                                             $amortizacion_aux_partidas  = $amortizacion_anticipo;
									    }//fin if
								 }//fin for


}//fin if











$monto_iva_aux_partidas          =  0;
$monto_cancelacion_aux_partidas  =  0;
$monto_total_aux_partidas        =  0;
$am_cont=0;
 for($i=0; $i<$i_lenght; $i++){
 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
       if($partidas_vista['pago_'.$i]!="0,00"){
	   	         $var[$i]['pago'] = $this->Formato1($partidas_vista['pago_'.$i]);
	   	         $am_cont++;
                 $var[$i]['pago'] = $var[$i]['pago']-($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
	            if($concate=="403.18.01.00"){$monto_iva_aux_partidas += $var[$i]['pago'];}else{$monto_cancelacion_aux_partidas +=$var[$i]['pago'];}//fin if
	         $monto_total_aux_partidas += $var[$i]['pago'];
	   }//fin if
 }//fin for











$contar_iva  = 0;
 if($contar_iva==1){ $am_cont=0;
					            for($i=0; $i<$i_lenght; $i++){ $am_cont++;
								 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
								       if($concate=="403.18.01.00"){
								       	          $monto_iva_suma = $var[$i]['pago'];
								       	          $monto_iva_aux_partidas = $monto_iva;
								                  $var[$i]['pago'] = $monto_iva;
								                  if($monto_iva_suma>$monto_iva){
								                  	     $monto_iva_suma -=0.01; $var[$i]['pago']-=0.01;
					                                     $monto_iva_aux_partidas-=0.01;
							                             if($amortizacion_anticipo==$amortizacion_aux_partidas && $amortizacion_anticipo!=0){                          $amortizacion_aux_partidas +=0.01; $amortizacion_aux[$am_cont] +=0.01;}
							                             if($monto_retencion_fielcumplimiento==$retencion_fielcumplimiento_aux_partidas && $monto_retencion_fielcumplimiento!=0){ $retencion_fielcumplimiento_aux_partidas +=0.01; $retencion_fielcumplimiento_aux[$am_cont] += 0.01;}
							                             if($monto_retencion_laboral==$retencion_laboral_aux_partidas  && $monto_retencion_laboral!=0){                   $retencion_laboral_aux_partidas +=0.01; $retencion_laboral_aux[$am_cont] +=0.01;}
								         	}else if($monto_iva_suma<$monto_iva){
								         		         $monto_iva_suma +=0.01; $var[$i]['pago']+=0.01;
								                         $monto_iva_aux_partidas+=0.01;
										                 if($amortizacion_anticipo==$amortizacion_aux_partidas  && $amortizacion_anticipo!=0){                          $amortizacion_aux_partidas -=0.01; $amortizacion_aux[$am_cont] -=0.01;}
							                             if($monto_retencion_fielcumplimiento==$retencion_fielcumplimiento_aux_partidas  && $monto_retencion_fielcumplimiento!=0){ $retencion_fielcumplimiento_aux_partidas -=0.01;  $retencion_fielcumplimiento_aux[$am_cont] -=0.01;}
							                             if($monto_retencion_laboral==$retencion_laboral_aux_partidas && $monto_retencion_laboral!=0){                   $retencion_laboral_aux_partidas -=0.01;  $retencion_laboral_aux[$am_cont] -=0.01;}
									           }//fin if
									    }//fin if
								 }//fin for


}else if($contar_iva!=0){



					            $monto_iva_suma = 0;
						        for($i=0; $i<$i_lenght; $i++){
								 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
								       if($concate=="403.18.01.00"){
								                 $monto_iva_suma +=$var[$i]['pago'];
									   }//fin if
								 }//fin for
								 $monto_iva_suma =  $this->Formato2($monto_iva_suma);
					             $monto_iva_suma =  $this->Formato1($monto_iva_suma);
					             if($monto_iva_suma!=$monto_iva){ $am_cont=0;
					             	 for($i=0; $i<$i_lenght; $i++){ $am_cont++;
								 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
								       if($concate=="403.18.01.00"){
								         	if($monto_iva_suma>$monto_iva){
					                             $monto_iva_suma -=0.01; $var[$i]['pago']-=0.01;
					                             $monto_iva_aux_partidas-=0.01;
					                             if($amortizacion_anticipo==$amortizacion_aux_partidas && $amortizacion_anticipo!=0){                          $amortizacion_aux_partidas +=0.01; $amortizacion_aux[$am_cont] +=0.01;}
					                             if($monto_retencion_fielcumplimiento==$retencion_fielcumplimiento_aux_partidas && $monto_retencion_fielcumplimiento!=0){ $retencion_fielcumplimiento_aux_partidas +=0.01; $retencion_fielcumplimiento_aux[$am_cont] += 0.01;}
					                             if($monto_retencion_laboral==$retencion_laboral_aux_partidas && $monto_retencion_laboral!=0){                   $retencion_laboral_aux_partidas +=0.01; $retencion_laboral_aux[$am_cont] +=0.01;}

								         	}else if($monto_iva_suma<$monto_iva){
								                 $monto_iva_suma +=0.01; $var[$i]['pago']+=0.01;
								                 $monto_iva_aux_partidas+=0.01;
								                 if($amortizacion_anticipo==$amortizacion_aux_partidas && $amortizacion_anticipo!=0){                          $amortizacion_aux_partidas -=0.01; $amortizacion_aux[$am_cont] -=0.01;}
					                             if($monto_retencion_fielcumplimiento==$retencion_fielcumplimiento_aux_partidas && $monto_retencion_fielcumplimiento!=0){ $retencion_fielcumplimiento_aux_partidas -=0.01;  $retencion_fielcumplimiento_aux[$am_cont] -=0.01;}
					                             if($monto_retencion_laboral==$retencion_laboral_aux_partidas && $monto_retencion_laboral!=0){                   $retencion_laboral_aux_partidas -=0.01;  $retencion_laboral_aux[$am_cont] -=0.01;}

									          }//fin if

									          $monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
                                              $monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);

								           }//fin for
								         }//fin for
					             }//fin if
}//fin else


$am_cont=0;
$monto_total_aux_partidas = 0;
 for($i=0; $i<$i_lenght; $i++){
 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
       if($partidas_vista['pago_'.$i]!="0,00"){
	   	     $monto_total_aux_partidas += $var[$i]['pago'];
	   }//fin if
 }//fin for















$monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
$monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);
$monto_cancelacion_aux_partidas =  $this->Formato2($monto_cancelacion_aux_partidas);
$monto_cancelacion_aux_partidas =  $this->Formato1($monto_cancelacion_aux_partidas);
$monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
$monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);



$am_cont=0;
if($monto_iva_aux_partidas!=$monto_iva){
  for($i=0; $i<$i_lenght; $i++){
    $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	  $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
	  	   if($concate=="403.18.01.00"){
	  	   	if($monto_iva_aux_partidas<$monto_iva && $retencion_laboral_aux_partidas!=0){
              $retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01; $var[$i]['pago'] +=0.01; $monto_iva_aux_partidas +=0.01; $monto_cancelacion_aux_partidas +=0.01; $monto_total_aux_partidas+=0.01;
              $retencion_laboral_aux[$am_cont] =  $this->Formato2($retencion_laboral_aux[$am_cont]);
              $retencion_laboral_aux[$am_cont] =  $this->Formato1($retencion_laboral_aux[$am_cont]);
              $retencion_laboral_aux_partidas =  $this->Formato2($retencion_laboral_aux_partidas);
              $retencion_laboral_aux_partidas =  $this->Formato1($retencion_laboral_aux_partidas);
              $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
              $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
              $monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
              $monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);
              $monto_cancelacion_aux_partidas =  $this->Formato2($monto_cancelacion_aux_partidas);
              $monto_cancelacion_aux_partidas =  $this->Formato1($monto_cancelacion_aux_partidas);
              $monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
              $monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);
	  	    }//fin if
	  	    if($monto_iva_aux_partidas<$monto_iva && $retencion_fielcumplimiento_aux_partidas!=0){
              $retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01; $var[$i]['pago'] +=0.01; $monto_iva_aux_partidas +=0.01; $monto_cancelacion_aux_partidas +=0.01;  $monto_total_aux_partidas+=0.01;
              $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato2($retencion_fielcumplimiento_aux[$am_cont]);
              $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato1($retencion_fielcumplimiento_aux[$am_cont]);
              $retencion_fielcumplimiento_aux_partidas =  $this->Formato2($retencion_fielcumplimiento_aux_partidas);
              $retencion_fielcumplimiento_aux_partidas =  $this->Formato1($retencion_fielcumplimiento_aux_partidas);
              $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
              $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
              $monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
              $monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);
              $monto_cancelacion_aux_partidas =  $this->Formato2($monto_cancelacion_aux_partidas);
              $monto_cancelacion_aux_partidas =  $this->Formato1($monto_cancelacion_aux_partidas);
              $monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
              $monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);
	  	    }//fin if
	  	 }//fin if
	  }//fin if
  }//fin for
}//fin if







$am_cont=0;
if($amortizacion_anticipo!=$amortizacion_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){
    $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	  $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
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
if($monto_retencion_laboral!=$retencion_laboral_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){
    $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	  $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
               if($anticipo_con_iva2==1){
                   if(($concate=="403.18.01.00" && $aux_monto_partida!=$monto_iva) || $concate!="403.18.01.00"){
                         if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			                }else if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			                }//fin if
                    }//fin if
               }else{
               	       if($concate!="403.18.01.00"){
			               if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			                }else if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			                }//fin if
			             }//fin if
               }//fin else
      }//fin if
   }//fin if
}//fin if



$am_cont=0;
if($monto_retencion_fielcumplimiento!=$retencion_fielcumplimiento_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){
    $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	  $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
             if($anticipo_con_iva2==1){
                     if(($concate=="403.18.01.00" && $aux_monto_partida!=$monto_iva) || $concate!="403.18.01.00"){
                          if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
			                }else if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			                }//fin if
                     }//fin if
               }else{
           	       if($concate!="403.18.01.00"){
           				          if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
			                }else if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			                }//fin if
			        }//fin if
			  }//fin if
      }//fin if
   }//fin if
}//fin if




///////////////////////////////////////////////////VIEJO///////////////////////////////////////////////////////////////////////////////////

$am_cont=0;
if($amortizacion_anticipo!=$amortizacion_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){$aux_a = 0;
           	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
           	 	if($anticipo_con_iva==1){
           	 		if($concate!="403.18.01.00"){
		           	 		 $aux_a = $amortizacion_aux[$am_cont]+$amortizacion_ante;
		                      if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
		                }else if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
		             }//fin if
                }//fin else
					  }else{
				          if($concate!="403.18.01.00"){
                         $aux_a = $amortizacion_aux[$am_cont]+$amortizacion_ante;
                      if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
                }else if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
                }//fin else
                             }//fin if
					   }//fin else
	 }//fin if
   }//fin for
}//fin if


$am_cont=0;
if($monto_retencion_laboral!=$retencion_laboral_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){$aux_a = 0;
           	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 		$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
           	 	if($anticipo_con_iva2==1){
                         $aux_a = $retencion_laboral_aux[$am_cont]+$retencion_laboral_ante;
                         $suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                         $aux_1 = $var[$i]['pago'];
                         if(($concate=="403.18.01.00" && $var[$i]['pago']!=$monto_iva) || $concate!="403.18.01.00"){
			                      if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
			                }else if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
			            }//fin
                }//fin else
                  }else{
				          if($concate!="403.18.01.00"){
				          	  $aux_a = $retencion_laboral_aux[$am_cont]+$retencion_laboral_ante;
                         $suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                       if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
                }else if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
                }//fin else
                       }//fin if
					}//fin else
	 }//fin if
   }//fin for
}//fin if




$am_cont=0;
if($monto_retencion_fielcumplimiento!=$retencion_fielcumplimiento_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){$aux_a = 0;
           	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
           	   	if($anticipo_con_iva2==1){
                         $aux_a = $retencion_fielcumplimiento_aux[$am_cont]+$retencion_fielcumplimiento_ante;
                         $suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                         $aux_1 = $var[$i]['pago'];
                         if(($concate=="403.18.01.00" && $var[$i]['pago']!=$monto_iva) || $concate!="403.18.01.00"){
				                       if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if
				                }else if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
				        }//fin if
				    }//fin else
                   }else{
				      if($concate!="403.18.01.00"){
				         $aux_a = $retencion_fielcumplimiento_aux[$am_cont]+$retencion_fielcumplimiento_ante;
                         $suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                       if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if
                }else if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
                }//fin else
				     }//fin if
				}//fin else
	 }//fin if
   }//fin for
}//fin if




if($monto_total_aux_partidas!=$monto_cancelado2){
$am_cont=0;
 for($i=0; $i<$i_lenght; $i++){
 	 $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
          if($concate!="403.18.01.00"){
          	    $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          	    $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                if($monto_total_aux_partidas<$monto_cancelado2 && $aux_monto_partida!=$total_partida_vista){
                  $var[$i]['pago'] += 0.01; $monto_total_aux_partidas += 0.01;
                }else if($monto_total_aux_partidas>$monto_cancelado2 && $aux_monto_partida!=$total_partida_vista){
                  $var[$i]['pago'] -= 0.01; $monto_total_aux_partidas -= 0.01;
                }//fin if
          }//fin if
	 }//fin if
  }//fin if
}//fin if

$monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
$monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);


if($monto_total_aux_partidas!=$monto_cancelado2){
$am_cont=0;
 for($i=0; $i<$i_lenght; $i++){
 	 $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
          if($concate!="403.18.01.00"){
          	    $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          	    $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                if($monto_total_aux_partidas<$monto_cancelado2){
                  $var[$i]['pago'] += 0.01; $monto_total_aux_partidas += 0.01;
                }else if($monto_total_aux_partidas>$monto_cancelado2){
                  $var[$i]['pago'] -= 0.01; $monto_total_aux_partidas -= 0.01;
                }//fin if
          }//fin if
	 }//fin if
  }//fin if
}//fin if

//////////////////////////////////////////////////////FIN REDONDEO/////////////////////////////////////////////////////////////////


/////////////////////////////////////////////FIN REDONDEO AMORTIZACION Y RENTENCION LABORAL Y RETENCION FIEL CUMPLIMIENTO Y CANCELADO///////////////////////////////////////////












$j      =0;
$am_cont=0;/*
$suma = count($numero_datos_partidas);
$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);
if(!empty($numero_causado)){
	$num_base = $numero_causado;
	$numero_causado += $suma;
	$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
}else{
	$num_base = 1;
	$numero_causado = $num_base+$suma;
	$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado');";
}
$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);*/

	 for($i=0; $i<$i_lenght; $i++){
	      // $var[$i]['pago']=$partidas_vista['pago_'.$i];
	         if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;

		        // $var[$i]['pago'] = $this->Formato1($var[$i]['pago']);




/////////////////////////////////////INICIO///////////////////////////////////////////////////


		     $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
             //$amortizacion_aux               = 0;
             //$retencion_laboral_aux          = 0;
             //$retencion_fielcumplimiento_aux = 0;
             $amortizacion_aux_compara       = 0;
             $retencion_laboral_aux_compara  = 0;
             $retencion_fielcumplimiento_compara = 0;
                   $datos_cscd04_ordencompra_partidas    =     $this->cobd01_contratoobras_partidas->findAll($sql_where[$i]);
					foreach($datos_cscd04_ordencompra_partidas as $aux_datos_cscd04_ordencompra_partidas){
						$anticipo                         =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['anticipo'];
						$amortizacion                     =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['amortizacion'];
						$cancelado                        =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['cancelacion'];
						$retencion_laboral                =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['retencion_laboral'];
						$retencion_fielcumplimiento       =    $aux_datos_cscd04_ordencompra_partidas['cobd01_contratoobras_partidas']['retencion_fielcumplimiento'];

					}//fin foreach


            $amortizacion_aux_compara           +=  $amortizacion_aux[$am_cont];
            $retencion_laboral_aux_compara      +=  $retencion_laboral_aux[$am_cont];
            $retencion_fielcumplimiento_compara +=  $retencion_fielcumplimiento_aux[$am_cont];
          //  $var[$i]['pago'] = $var[$i]['pago']-($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
            //$pago = $var[$i]['pago'];
///////////////////////////////////FIN///////////////////////////////////////////////////

		    /*$cp = $this->crear_partida($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
			$to=1;
			$td = 4;
			$ta = 5;
			$mt = $var[$i]['pago'];
			$ndo = $num_contrato_obra3[$i];
			$rnco = $numero_control_compromiso[$i];
			$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp='', $ann, $ndo, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $i);
*/

$dnca = true;
if($dnca != false){
          $dnca=0;
$sw2 = 0;

           $monto_partida = $this->Formato1($partidas_vista['pago_'.$i]);
           $pago  = $var[$i]['pago'];

		   $sql2  ="INSERT INTO cobd01_contratoobras_valuacion_partidas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_obra, numero_contrato_obra, numero_valuacion, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec,  cod_auxiliar, monto, numero_control_compromiso, numero_control_causado, amortizacion, retencion_laboral, retencion_fielcumplimiento) ";
	       $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_contrato_obra."', '".$num_contrato_obra."', '".$numero_valuacion."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['pago']."', '".$numero_control_compromiso[$i]."', '$dnca', '$amortizacion_aux[$am_cont]', '$retencion_laboral_aux[$am_cont]', '$retencion_fielcumplimiento_aux[$am_cont]'); ";
	       $sw2 =  $this->cobd01_contratoobras_valuacion_partidas->execute($sql2);
	       $sql_where2 = $sql_where[$i];

       if($sw2 > 1){
		 	$sql_partidas_contrato_obra = "UPDATE cobd01_contratoobras_partidas SET retencion_laboral=retencion_laboral+".$retencion_laboral_aux[$am_cont].", retencion_fielcumplimiento=retencion_fielcumplimiento+".$retencion_fielcumplimiento_aux[$am_cont].", cancelacion=cancelacion + ".$pago.", amortizacion=amortizacion+".$amortizacion_aux[$am_cont]." WHERE $sql_where2".";";
			$sw3 = $this->cobd01_contratoobras_partidas->execute($sql_partidas_contrato_obra);
             if($sw3 > 1){}else{/*$this->cobd01_contratoobras_partidas->execute("ROLLBACK;");*/ }//fin else
            }else{
              //$this->cobd01_contratoobras_valuacion_partidas->execute("ROLLBACK;");
	       }//fin else

}else{
	//$this->cfpd05->execute("ROLLBACK;");
}//fin else


	       }//fin if
	 }//fin for


if($sw3 > 1){


	$datos_contrato_obra    =     $this->cobd01_contratoobras_cuerpo->findAll($condicion);


	foreach($datos_contrato_obra as $aux_datos_contrato_obra){
		$monto_amortizacion_aux                  =     $aux_datos_contrato_obra['cobd01_contratoobras_cuerpo']['monto_amortizacion'];
		$monto_cancelado_aux                     =     $aux_datos_contrato_obra['cobd01_contratoobras_cuerpo']['monto_cancelado'];
		$monto_retencion_laboral_aux             =     $aux_datos_contrato_obra['cobd01_contratoobras_cuerpo']['monto_retencion_laboral'];
		$monto_retencion_fielcumplimiento_aux    =     $aux_datos_contrato_obra['cobd01_contratoobras_cuerpo']['monto_retencion_fielcumplimiento'];
	}//fin foreach

	$monto_amortizacion_aux               =  $monto_amortizacion_aux + $amortizacion_anticipo;
	$monto_cancelado_aux                  =  $monto_cancelado_aux + $monto_orden_de_pago_monto_iva;
	$monto_retencion_fielcumplimiento_aux =  $monto_retencion_fielcumplimiento_aux  +  $monto_retencion_fielcumplimiento;
	$monto_retencion_laboral_aux          =  $monto_retencion_laboral_aux + $monto_retencion_laboral;

	$sql3  = "UPDATE cobd01_contratoobras_cuerpo SET monto_retencion_laboral='".$monto_retencion_laboral_aux."',   monto_retencion_fielcumplimiento='".$monto_retencion_fielcumplimiento_aux."'    ,monto_amortizacion = '".$monto_amortizacion_aux."', monto_cancelado = '".$monto_cancelado_aux."'  where ".$condicion.';';
	$sw4 = $this->cobd01_contratoobras_cuerpo->execute($sql3);

    if($sw4 > 1){
        $this->cobd01_contratoobras_cuerpo->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
     }else{
     	$this->cobd01_contratoobras_cuerpo->execute("ROLLBACK;"); $this->set('errorMessage', 'NO SE LOGRO REALIZAR LA VALUACIÓN - POR FAVOR INTENTE DE NUEVO');
     	  }//fin else




}else{
$this->cobd01_contratoobras_valuacion_partidas->execute("ROLLBACK;");
$this->set('errorMessage', 'NO SE LOGRO REALIZAR LA VALUACIÓN - POR FAVOR INTENTE DE NUEVO');
}//fin else




}else{
	$this->cobd01_contratoobras_valuacion_cuerpo->execute("ROLLBACK;");
	$msg_error = 'NO SE LOGRO REALIZAR LA VALIACIÓN - POR FAVOR INTENTE DE NUEVO';
	$this->set('errorMessage', 'NO SE LOGRO REALIZAR LA VALIACIÓN - POR FAVOR INTENTE DE NUEVO');
	return;
}//fin else




$this->index();
$this->render('index');





 //$this->redirect("/reporte3/reporte_valuacion_1/si/".$num_contrato_obra."/".$numero_valuacion);

}//fin guardar







function buscar_year($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $lista = "";

		if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){

			$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
			$lista = $this->cobd01_contratoobras_valuacion_cuerpo->generateList($condicion.' and ano_contrato_obra='.$var1, ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra');
			$this->set('obras', $lista);

		}else{

			$c = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion().' and ano_contrato_obra='.$var1, 'numero_contrato_obra, ano_contrato_obra', null, null);
			$sql_c = ""; //print_r($c);
			foreach($c as $c_aux){
						if($sql_c == ""){
			                          $sql_c  = "     numero_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra']."' ";
						}else{        $sql_c .= " or (numero_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra']."') ";		}//fin else
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


if(!empty($this->data['cobp01_contratoobras_valuacion_uso_general']['ano_ejecucion'])){	$_SESSION['ano_contrato_obra'] = $this->data['cobp01_contratoobras_valuacion_uso_general']['ano_ejecucion'];}else{$_SESSION['ano_contrato_obra'] = $this->ano_ejecucion();}
$ano = $_SESSION['ano_contrato_obra'];

if($var1!=null){
  if($var1=='si'){





if(!empty($this->data['cobp01_contratoobras_valuacion_uso_general']['numero_contrato_obra'])){

	 $array = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($condicion. "  and numero_contrato_obra='".$this->data['cobp01_contratoobras_valuacion_uso_general']['numero_contrato_obra']."' and ano_contrato_obra = ".$ano, null, 'ano_contrato_obra, numero_contrato_obra, numero_valuacion ASC', null);
  $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_valuacion']      = $aux['cobd01_contratoobras_valuacion_cuerpo']['numero_valuacion'];
 	$i++;
} $i--;


  for($a=0; $a<=$i; $a++){

    if($this->data['cobp01_contratoobras_valuacion_uso_general']['numero_contrato_obra'] == $numero[$a]['numero_contrato_obra']){
    	  $pag_num = 0;
    	  $opcion='si';
    	  $numero_documento = $numero[$a]['numero_contrato_obra'];
    	   break;
    }else{$pag_num = 0;
    	  $opcion='si';
    	  $numero_documento = $numero[0]['numero_contrato_obra'];}
   }//fin for


      if($opcion=='si'){   $this->consulta($pag_num, $numero_documento); $this->render('consulta');
}else if($opcion=='no'){
	$this->set('errorMessage', 'No existen datos');
	$this->consulta_index(); $this->render('consulta_index');
	}//fin else

}else{


	 $array = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($condicion. "  and ano_contrato_obra = ".$ano, null, 'ano_contrato_obra, numero_contrato_obra, numero_valuacion ASC', null);
  $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_valuacion']      = $aux['cobd01_contratoobras_valuacion_cuerpo']['numero_valuacion'];
 	$i++;
} $i--;

		 if($i<=0){
			  $this->set('errorMessage', 'No existen datos');
			  $this->consulta_index();
			  $this->render('consulta_index');

			}else{
			 $this->consulta(0, $numero[0]['numero_contrato_obra']); $this->render('consulta');
		    }

	 }//fin else



  }//fin if
}//fin i





		 $lista = $this->cobd01_contratoobras_valuacion_cuerpo->generateList($condicion.' and ano_contrato_obra='.$ano.$this->filtra_obra($ano), ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra');
		 $this->set('obras', $lista);
		 $this->set('ano', $_SESSION['ano_contrato_obra']);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin function








function filtra_obra($year=null){



$cod_dep                  =       $this->Session->read('SScoddep');
$SScoddeporig             =       $this->Session->read('SScoddeporig');
$Modulo                   =       $this->Session->read('Modulo');
$SScoddeporig             =       $this->Session->read('SScoddeporig');
$SScoddep                 =       $this->Session->read('SScoddep');
$Modulo                   =       $this->Session->read('Modulo');



$sql_obra = "";



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


}//fin functionsss








function consulta($pag_num=null, $numero_documento=null, $g=null){
  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $lista = "";
  if(!empty($this->data['cobp01_contratoobras_valuacion_uso_general']['ano_ejecucion'])){
	$ano = $this->data['cobp01_contratoobras_valuacion_uso_general']['ano_ejecucion'];
  }else{
  	$ano = $this->ano_ejecucion();
  }


  if($g=="si"){$this->set('Message_existe', 'Los datos fueron guardados correctamente');}
  if($g=="sii"){$this->set('Message_existe', 'El registro fue anulado correctamente');}





$this->set('ano_contrato_obra_ejecucion', $this->ano_ejecucion());

  if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else



   if(isset($_SESSION['ano_contrato_obra'])){$ano = $_SESSION['ano_contrato_obra'];}else{$ano = $this->ano_ejecucion();}
   $this->set('ano_contrato_obra', $ano);

   $array = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($condicion. "  and numero_contrato_obra='".$numero_documento."' and ano_contrato_obra = ".$ano , null, 'ano_contrato_obra, numero_contrato_obra, numero_valuacion ASC', null);

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_valuacion']      = $aux['cobd01_contratoobras_valuacion_cuerpo']['numero_valuacion'];

 	$i++;

} $i--;

















if(isset($numero[$pag_num]['numero_contrato_obra'])){


$datos_cobd01_contratoobras_valuacion_cuerpo          =     $this->cobd01_contratoobras_valuacion_cuerpo->findAll($condicion."   and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']."  and numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."'  and numero_valuacion='".$numero[$pag_num]['numero_valuacion']."'   ");
$datos_cobd01_contratoobras_valuacion_partidas        =     $this->cobd01_contratoobras_valuacion_partidas->findAll($condicion." and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']."  and numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."'  and numero_valuacion='".$numero[$pag_num]['numero_valuacion']."'  ");
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
}//fin foreach

$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){
	$denominacion_rif         =  $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif  =  $aux_2['cpcd02']['direccion_comercial'];
	$objeto_rif               =  $aux_2['cpcd02']['objeto'];
}//fin foreach



$this->set('datos_cobd01_contratoobras_valuacion_cuerpo', $datos_cobd01_contratoobras_valuacion_cuerpo);
$this->set('datos_cobd01_contratoobras_valuacion_partidas', $datos_cobd01_contratoobras_valuacion_partidas);
$this->set('datos_cobd01_contratoobras_cuerpo', $datos_cobd01_contratoobras_cuerpo);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('rif', $rif);

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









function guardar_anulacion1($var=null) {
	$this->layout="ajax";


echo'<script>';
    echo'document.getElementById("guardar").disabled = false; ';
    echo'document.getElementById("anular").disabled = true; ';
echo'</script>';


}//fin function





function guardar_anulacion2($var=null) {

$this->layout="ajax";

	if(isset($this->data["cobp01_contratoobras_valuacion_uso_general"])){

		    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
		    $tipo_documento           =  245;
       	    //$concepto_anulacion       =  $this->data["cobp01_contratoobras_valuacion_uso_general"]["concepto_anulacion"];
			$concepto_anulacion = "";
			$concepto = $concepto_anulacion;
			$fecha_proceso_anulacion  =  date("d/m/Y");
       	    $condicion_documento      =  2;//cuando se guarda es Activo=1
  		    $ano_contrato_obra    = $this->data["cobp01_contratoobras_valuacion_uso_general"]["ano_contrato_obra"];
			$num_contrato_obra    = $this->data["cobp01_contratoobras_valuacion_uso_general"]["num_contrato_obra"];
			$fecha_valuacion  = $this->data["cobp01_contratoobras_valuacion_uso_general"]["fecha_valuacion"];
			$fd = $fecha_valuacion;
			$numero_valuacion = $this->data["cobp01_contratoobras_valuacion_uso_general"]["numero_valuacion"];
			$monto_cancelado = 0;
			$monto_cancelado_para_cuerpo = 0;
			$datos_partidas = $this->cobd01_contratoobras_valuacion_partidas->findAll($conditions = $this->condicion()." and ano_contrato_obra='$ano_contrato_obra' and numero_contrato_obra='$num_contrato_obra' and numero_valuacion='$numero_valuacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			$sql_update_cscd04_partidas ='';


/////////////////////////ACTUALIZA LAS PARTIDAS/////////////////////
		foreach($datos_partidas as $row){
			 	$ano                          =   $row['cobd01_contratoobras_valuacion_partidas']['ano'];
			 	$cod_sector                   =   $row['cobd01_contratoobras_valuacion_partidas']['cod_sector'];
			 	$cod_programa                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_programa'];
			 	$cod_sub_prog                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_sub_prog'];
			 	$cod_proyecto                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_proyecto'];
			 	$cod_activ_obra               =   $row['cobd01_contratoobras_valuacion_partidas']['cod_activ_obra'];
			 	$cod_partida                  =   $row['cobd01_contratoobras_valuacion_partidas']['cod_partida'];
			 	$cod_generica                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_generica'];
			 	$cod_especifica               =   $row['cobd01_contratoobras_valuacion_partidas']['cod_especifica'];
			 	$cod_sub_espec                =   $row['cobd01_contratoobras_valuacion_partidas']['cod_sub_espec'];
			 	$cod_auxiliar                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_auxiliar'];
			 	$monto_partida                =   $row['cobd01_contratoobras_valuacion_partidas']['monto'];
			 	$numero_control_compromiso    =   $row['cobd01_contratoobras_valuacion_partidas']['numero_control_comprom'];
			 	$numero_control_causado       =   $row['cobd01_contratoobras_valuacion_partidas']['numero_control_causado'];
			 	$amortizacion2                =   $row['cobd01_contratoobras_valuacion_partidas']['amortizacion'];
			 	$retencion_laboral2           =   $row['cobd01_contratoobras_valuacion_partidas']['retencion_laboral'];
			 	$retencion_fielcumplimiento2  =   $row['cobd01_contratoobras_valuacion_partidas']['retencion_fielcumplimi'];

			 	$cond1 = $this->condicion()." and ano_contrato_obra='$ano_contrato_obra' and numero_contrato_obra='$num_contrato_obra' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";


                //$monto_partida = ($monto_partida - ($amortizacion2  + $retencion_laboral2 + $retencion_fielcumplimiento2 ));
				$monto_cancelado_para_cuerpo += $monto_partida;
				//$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
				//$num_asiento_compromiso = $this->motor_presupuestario($cp,2, 4, 5, date("d/m/Y"), $monto_partida, $concepto, $ano, $num_contrato_obra, $numero_valuacion, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);


                $datos_cobd01_contratoobras_partidas    =     $this->cobd01_contratoobras_partidas->findAll($cond1);
                foreach($datos_cobd01_contratoobras_partidas as $aux_cobd01_contratoobras_partidas){
		           $amortizacion                =    $aux_cobd01_contratoobras_partidas['cobd01_contratoobras_partidas']['amortizacion'];
		           $cancelado                   =    $aux_cobd01_contratoobras_partidas['cobd01_contratoobras_partidas']['cancelacion'];
		           $retencion_laboral           =    $aux_cobd01_contratoobras_partidas['cobd01_contratoobras_partidas']['retencion_laboral'];
		           $retencion_fielcumplimiento  =    $aux_cobd01_contratoobras_partidas['cobd01_contratoobras_partidas']['retencion_fielcumplimiento'];
	             }//fin foreac

                $amortizacion                 =   $amortizacion - $amortizacion2;
                $retencion_laboral            =   $retencion_laboral - $retencion_laboral2;
                $retencion_fielcumplimiento   =   $retencion_fielcumplimiento -  $retencion_fielcumplimiento2;
//              $cancelado                    =   $cancelado - ($monto_partida - ($amortizacion2  +  $retencion_laboral2 + $retencion_fielcumplimiento2));
                $cancelado                    =   $cancelado - $monto_partida;



			 	$sql_update_cscd04_partidas = "UPDATE cobd01_contratoobras_partidas SET retencion_fielcumplimiento=".$retencion_fielcumplimiento.", retencion_laboral=".$retencion_laboral.", amortizacion=".$amortizacion.", cancelacion=".$cancelado." WHERE ".$cond1.";";
                $sw = $this->cobd01_contratoobras_partidas->execute($sql_update_cscd04_partidas);

}//fin for

///////////////////////FIN ACTUALIZA LAS PARTIDAS/////////////////////



/////////////////////////ACTUALIZA EL ENCABEZADO//////////////////

$monto_amortizacion_aux_yy                    =    0;
$monto_retencion_fielcumplimiento_aux_zz      =    0;
$monto_retencion_laboral_aux_zz               =    0;
$monto_cancelar_aux_zz                        =    0;


           $datos_cuerpo = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($conditions = $this->condicion()." and ano_contrato_obra='$ano_contrato_obra' and numero_contrato_obra='$num_contrato_obra' and numero_valuacion='$numero_valuacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);


            foreach($datos_cuerpo as $aux_datos_cuerpo){
		       $monto_amortizacion_aux_yy                   = $aux_datos_cuerpo['cobd01_contratoobras_valuacion_cuerpo']['amortizacion_anticipo'];
		     //$monto_cancelar_aux_zz                       = $aux_datos_cuerpo['cobd01_contratoobras_valuacion_cuerpo']['monto_neto_cobrar'];
		       $monto_cancelar_aux_zz                       = $aux_datos_cuerpo['cobd01_contratoobras_valuacion_cuerpo']['monto_orden_pago'];
		       $monto_retencion_laboral_aux_zz              = $aux_datos_cuerpo['cobd01_contratoobras_valuacion_cuerpo']['monto_retencion_laboral'];
		       $monto_retencion_fielcumplimiento_aux_zz     = $aux_datos_cuerpo['cobd01_contratoobras_valuacion_cuerpo']['monto_retencion_fielcump'];
	        }//fin foreach
			$cond2 = $this->condicion()." and ano_contrato_obra='$ano_contrato_obra' and numero_contrato_obra='$num_contrato_obra' ";
			$datos_orden_compra_encabezado    =     $this->cobd01_contratoobras_cuerpo->findAll($cond2);



            foreach($datos_orden_compra_encabezado as $aux_datos_orden_compra_encabezado){
		       $monto_amortizacion_aux2                  = $aux_datos_orden_compra_encabezado['cobd01_contratoobras_cuerpo']['monto_amortizacion'];
		       $monto_cancelado_aux2                     = $aux_datos_orden_compra_encabezado['cobd01_contratoobras_cuerpo']['monto_cancelado'];
		       $monto_retencion_laboral_aux2             = $aux_datos_orden_compra_encabezado['cobd01_contratoobras_cuerpo']['monto_retencion_laboral'];
		       $monto_retencion_fielcumplimiento_aux2    = $aux_datos_orden_compra_encabezado['cobd01_contratoobras_cuerpo']['monto_retencion_fielcumplimiento'];

	        }//fin foreach
	        $monto_amortizacion_aux2               =  $monto_amortizacion_aux2 - $monto_amortizacion_aux_yy;
	        $monto_retencion_laboral_aux2          =  $monto_retencion_laboral_aux2 - $monto_retencion_laboral_aux_zz;
	        $monto_retencion_fielcumplimiento_aux2 =  $monto_retencion_fielcumplimiento_aux2 - $monto_retencion_fielcumplimiento_aux_zz;
	        //$monto_cancelado_aux2                  =  $monto_cancelado_aux2 - ($monto_cancelar_aux_zz - ($monto_amortizacion_aux_yy + $monto_retencion_laboral_aux_zz + $monto_retencion_fielcumplimiento_aux_zz));
	        //$monto_cancelado_aux2                  =  $monto_cancelado_aux2 - $monto_cancelado_para_cuerpo;
	          $monto_cancelado_aux2                  =  $monto_cancelado_aux2 - $monto_cancelar_aux_zz;


			$sql_update_cscd04_encabezado ="UPDATE cobd01_contratoobras_cuerpo SET monto_retencion_fielcumplimiento=".$monto_retencion_fielcumplimiento_aux2.", monto_retencion_laboral=".$monto_retencion_laboral_aux2.", monto_amortizacion=".$monto_amortizacion_aux2.",  monto_cancelado=".$monto_cancelado_aux2." WHERE ".$cond2.";";
			$sw = $this->cobd01_contratoobras_cuerpo->execute($sql_update_cscd04_encabezado);
/////////////////////////FIN ACTUALIZA EL ENCABEZADO////////////////






			 //$numero_anulacion=$this->cugd03_acta_anulacion_numero->execute($condicion);
            /* $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano_contrato_obra." ORDER BY numero_acta_anulacion DESC");

		     if($v!=null && $sw > 1){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano_contrato_obra."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano_contrato_obra.",1)");
			    $numero=1;
		     }//fin else*/

			 //$R1 = $this->cobd01_contratoobras_valuacion_cuerpo->execute("UPDATE cobd01_contratoobras_valuacion_cuerpo SET ano_asiento_anulacion=0, ano_anulacion=".date("Y").",  numero_anulacion=".$numero.", numero_asiento_anulacion=0, condicion_actividad=".$condicion_documento.", mes_asiento_anulacion=".date("m").",  dia_asiento_anulacion=".date("d").",  fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'  WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano_contrato_obra." and numero_contrato_obra='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");

			 $R1 = $this->cobd01_contratoobras_valuacion_cuerpo->execute("DELETE FROM cobd01_contratoobras_valuacion_partidas  WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano_contrato_obra." and numero_contrato_obra='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");
			 $R1 = $this->cobd01_contratoobras_valuacion_cuerpo->execute("  DELETE FROM cobd01_contratoobras_valuacion_cuerpo    WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano_contrato_obra." and numero_contrato_obra='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");



		     //$v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_contrato_obra.",".$numero.",".$tipo_documento.",".$ano_contrato_obra.",".$numero_valuacion.",'".$this->Cfecha($fecha_valuacion, 'A-M-D')."','".$concepto_anulacion."')");

	}//fin if

$this->set('Message_existe', 'El registro fue eliminado correctamente');


$this->consulta_index('1');
$this->render('consulta_index');



}//fin function










}//fin class

?>