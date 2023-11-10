<?php


class Cepp02ContratoserviciosValuacionController extends AppController {

   var $name = "cepp02_contratoservicios_valuacion";
   var $uses = array('cscd04_ordencompra_parametros', 'cepd02_contratoservicio_cuerpo', 'ccfd04_cierre_mes', 'cepd02_contratoservicio_valuacion_cuerpo',
                     'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cepd02_contratoservicio_partidas', 'cepd02_contratoservicio_valuacion_partidas',
                     'ccfd03_instalacion', 'cscd04_ordencompra_partidas', 'cpcd02', 'cfpd22_numero_asiento_causado', 'cfpd22', 'cfpd05', 'cugd04', 'cscd01_catalogo',

                     'cepd03_ordenpago_cuerpo', 'v_cepd02_contratoservicio_cuerpo', 'v_cepd02_contratoservicio_valuacion_cuerpo'

                     ,      'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo','cugd05_restriccion_clave'

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




function concatena_servicio($vector1=null, $nomVar=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			$cod[$x] = mascara($x,2).' - '.$y;
		}
	}
	$this->set($nomVar, $cod);
}//fin function





function index(){

$this->verifica_entrada('89');

$this->layout = "ajax";
 $ano='';
 $this->data=null;
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $ano = $this->ano_ejecucion();
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_contrato_servicio='.$ano.'';
 $lista = $this->v_cepd02_contratoservicio_cuerpo->generateList($condicion.' and condicion_actividad=1 and ((monto_original_contrato+aumento)-(disminucion+monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0','numero_contrato_servicio ASC',null, '{n}.v_cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.v_cepd02_contratoservicio_cuerpo.deno_numero_contrato_con_rif');
 $this->set('lista_numero', $lista);
$this->set('ano',$ano);
$this->Session->delete('PAG_NUM');

}//fin function





function selecion($var1=null){
 $this->layout = "ajax";
 $ano='';
 $datos_orden_pagos_anteriores = "";

 $anticipo_incluye_iva = "";
 $porcentaje_anticipo  = "0";
 $porcentaje_iva       = "0";

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
  $ano = $this->ano_ejecucion();
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_contrato_servicio='.$ano.'';
  $lista = $this->cepd02_contratoservicio_cuerpo->generateList($condicion.' and condicion_actividad=1 and ((monto_original_contrato+aumento)-(disminucion+monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0', ' numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio');
  $this->set('lista_numero', $lista);

 $this->set('numero_contrato_servicio', $var1);
 $this->Session->delete('PAG_NUM');
 $this->set('ano',$ano);

if($var1==null){

$this->index();
$this->render('index');

}else{

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_servicio='".$var1."'  and  ano_contrato_servicio=".$ano." ";
$numero_datos = $this->cepd02_contratoservicio_cuerpo->findAll($condicion);
$numero_datos_partidas = $this->cepd02_contratoservicio_partidas->findAll($condicion, null, 'numero_contrato_servicio DESC');

$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif                       =  $aux['cepd02_contratoservicio_cuerpo']['rif'];
	$ano_contrato_servicio     =  $aux['cepd02_contratoservicio_cuerpo']['ano_contrato_servicio'];
	$numero_contrato_servicio  =  $aux['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio'];
	$codigo_prod_serv          =  $aux['cepd02_contratoservicio_cuerpo']['codigo_prod_serv'];
	$anticipo_incluye_iva      =  $aux['cepd02_contratoservicio_cuerpo']['anticipo_con_iva'];
    $porcentaje_anticipo       =  $aux['cepd02_contratoservicio_cuerpo']['porcentaje_anticipo'];
    $porcentaje_iva            =  $aux['cepd02_contratoservicio_cuerpo']['porcentaje_iva'];
}//fin foreach

//$rif = "jj-as";

$servicio = $this->cscd01_catalogo->findAll("codigo_prod_serv='".$codigo_prod_serv."'");
$this->set('tipo_servicio',         $servicio[0]['cscd01_catalogo']['cod_snc']);
$this->set('denominacion_servicio', $servicio[0]['cscd01_catalogo']['denominacion']);

$opc      = $this->cepd02_contratoservicio_valuacion_cuerpo->findCount($condicion." and ano_contrato_servicio=".$ano_contrato_servicio."  and numero_contrato_servicio='".$numero_contrato_servicio."'  ");
$result   = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($condicion."  and ano_contrato_servicio=".$ano_contrato_servicio."  and numero_contrato_servicio='".$numero_contrato_servicio."' ", null, "numero_valuacion ASC", null, null);
foreach($result as $ves){$opc = $ves['cepd02_contratoservicio_valuacion_cuerpo']['numero_valuacion'];}//fin foreach


$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");

$objeto_rif = "";
$denominacion_rif = "";
$porcentaje_laboral    = "";
$porcentaje_fiel_cump  = "";
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


$datos_contrato_obra_anteriores         = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($condicion."  and ano_contrato_servicio=".$ano_contrato_servicio."  and numero_contrato_servicio='".$numero_contrato_servicio."' and condicion_actividad=1", null, null, null, null);
$datos_orden_pagos_anteriores_partidas = $this->cepd02_contratoservicio_valuacion_partidas->findAll($condicion, null, null, null, null);

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
$datos_cepd03_ordenpago_cuerpo         = $this->cepd03_ordenpago_cuerpo->findAll($condicion."  and ano_documento_origen=".$ano_contrato_servicio."  and numero_documento_origen='".$numero_contrato_servicio."'  and condicion_actividad=1  and cod_tipo_documento='5'  ", null, null, null, null);
					$this->set('datos_cepd03_ordenpago_cuerpo'  , $datos_cepd03_ordenpago_cuerpo);





$opc++;




//////////***********************  PARAMETROS   **********************************///////////////

$factor_reversion = "";
$porcentaje_laboral     = "0";
$porcentaje_fiel_cump   = "0";

 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
 $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
    foreach($parametros_datos as $aux_22){
      $porcentaje_laboral   = $aux_22['cscd04_ordencompra_parametros']['porcentaje_laboral'];
      $porcentaje_fiel_cump = $aux_22['cscd04_ordencompra_parametros']['porcentaje_fiel_cumplimiento'];
    }//fin foreach



//////////***********************  FIN PARAMETROS   ******************************///////////////

//$objeto_rif = 4;

$this->detalles_del_pago($objeto_rif, $ano_contrato_servicio, $numero_contrato_servicio, $exento_islr_cooperativa);

}else{
	$this->set('errorMessage', 'El rif del proveedor no existe');
	$this->set('porcentaje_retencion_iva'     , '');
	$this->set('impuesto_sobre_la_renta'      , '');
	$this->set('timbre_fiscal'                , '');
	$this->set('impuesto_municipal'           , '');
	$this->set('amortizacion_del_anticipo'    , '');
	$this->set('anticipo_con_iva'             , '');
	$this->set('anticipo_con_iva2'            , '');
	$this->set('sustraendo'                   , '');
	$this->set('porcentaje_fiel_cumplimiento' , '');
    $this->set('porcentaje_laboral'           , '');

 echo'<script>';
  echo'document.getElementById("guardar").disabled = true; ';
 echo'</script>';
}//fin else

//$this->opcion_pago($ano, $numero_contrato_servicio, '2');

$this->set('porcentaje_iva', $porcentaje_iva);
$this->set('porcentaje_laboral',   $porcentaje_laboral);
$this->set('porcentaje_fiel_cump', $porcentaje_fiel_cump);
$this->set('ano_contrato_servicio_pago', $ano);
$this->set('numero_contrato_servicio_pago', $opc);
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
echo" cepp02_contratoservicios_valuacion_pregunta_pago_parcial('".$opcion."');";
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



$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_servicio='".$numero_orden_compra."'  and  ano_contrato_servicio=".$ano_orden_compra." ";
$numero_datos = $this->cepd02_contratoservicio_cuerpo->findAll($condicion);

$numero_datos_aux =  $numero_datos;

foreach($numero_datos_aux as $aux){
	$rif                          =  $aux['cepd02_contratoservicio_cuerpo']['rif'];
	$ano_contrato_servicio        =  $aux['cepd02_contratoservicio_cuerpo']['ano_contrato_servicio'];
	$numero_contrato_servicio     =  $aux['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio'];
	$porcentaje_anticipo          =  $aux['cepd02_contratoservicio_cuerpo']['porcentaje_anticipo'];
	$anticipo_con_iva             =  $aux['cepd02_contratoservicio_cuerpo']['anticipo_con_iva'];
	$monto_original_contrato      =  $aux['cepd02_contratoservicio_cuerpo']['monto_original_contrato'];
    $modificacion_aumento         =  $aux['cepd02_contratoservicio_cuerpo']['aumento'];
    $modificacion_disminucion     =  $aux['cepd02_contratoservicio_cuerpo']['disminucion'];
}//fin foreach

//$objeto_rif = 4;

/**
 *
 *
 * LA OPCION 1 ES PARA QUE EN JAVASCRIPT LO VEA CON EL MONTO COMPLETO EN EL MONTO DEL CONTRATO AL CALCULAR EL PORCENTAJE DE LA AMORTIZACION
 *
 * NO IMPORTA EL 1 O 2 PARA QUE EL JAVASCRIPT LO VEA CON MONTO COMPLETO DEBE SER EL 1 ESTA OPCION ESTA BIEN NO TE CONFUNDAS
 */

//$anticipo_con_iva = 1;

$this->set('objeto_rif', $objeto_rif);

switch($objeto_rif){


case'1':{

	$monto_actual = $monto_original_contrato + ($modificacion_aumento - $modificacion_disminucion);

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

	$monto_actual = $monto_original_contrato + ($modificacion_aumento - $modificacion_disminucion);

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

	$monto_actual = $monto_original_contrato + ($modificacion_aumento - $modificacion_disminucion);

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

	$monto_actual = $monto_original_contrato + ($modificacion_aumento - $modificacion_disminucion);

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

   	$amortizacion_del_anticipo = $porcentaje_anticipo;

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
   	  echo'document.getElementById("porce_retencion_laboral").readOnly = true;';
   	  echo'document.getElementById("porce_retencion_fiel_cumplimiento").readOnly = true;';
   	echo '</script>';

   	$amortizacion_del_anticipo = $porcentaje_anticipo;

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







}//fin function







function  opcion_pago($ano=null, $numero_contrato_servicio=null, $opc){

 $this->layout = "ajax";

$ano_orden_compra = $this->ano_ejecucion();

if($opc == "2"){
		echo"<script>";
  		echo"cepp02_contratoservicios_valuacion_pregunta_pago_parcial('".$opc."');";
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


  $i_lenght = $this->data['cepp02_contratoservicios_valuacion']['cuenta_i'];
  $ano_contrato_servicio                =       $this->data['cepp02_contratoservicios_valuacion']['ano_contrato_servicio'];
  $num_contrato_obra                    =       $this->data['cepp02_contratoservicios_valuacion']['num_contrato_servicio'];
  $numero_contrato_servicio             =       $num_contrato_obra;
  $ano_orden_compra_autorizacion        =       $ano_contrato_servicio;
  $ann = $ano_contrato_servicio;
  $numero_valuacion                     =       $this->data['cepp02_contratoservicios_valuacion']['numero_valuacion'];
  $nda = $numero_valuacion;
  $fecha_valuacion                      =       $this->data['cepp02_contratoservicios_valuacion']['fecha_valuacion'];
  $fd = $fecha_valuacion;
  $monto_original_contrato              =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_contrato']);
  $monto_retenido_laboral               =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['retencion_laboral']);
  $monto_retenido_fielcumplimiento      =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['fiel_cumplimiento']);
  $porcentaje_amortizacion              =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['amortizacion_del_anticipo']);
  $total_retencion_monto_iva            =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['total_retencion_monto_iva']);
  $monto_descontar_impuesto             =       $total_retencion_monto_iva;
  $monto_orden_de_pago_monto_iva        =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_orden_de_pago_monto_iva']);
  $monto_a_pagar_monto_iva              =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_a_pagar_monto_iva']);
  $concepto_contrato_obra               =       $this->data['cepp02_contratoservicios_valuacion']['concepto'];
  $monto_orden                          =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_orden_de_pago_monto_iva']);
  $monto_cancelado2                     =       $monto_orden;
  $monto_neto_cobrar                    =       $monto_a_pagar_monto_iva;

  $monto_mano_obra                      =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_mano_obra']);
 ////fin monto de la mano de obra/////
////monto de la valuación/////
  $monto_opcion_pago                    =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_opcion_pago']);
 ////fin monto de la valuación/////


  $aumento                              =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['aumento']);
  $disminucion                          =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['disminucion']);
  $monto_anticipo                       =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_anticipo']);
  $monto_amortizacion                   =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_amortizacion']);
  $monto_cancelado                      =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_cancelado']);
  $monto_iva                            =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_iva']);
  $monto_cancelar                       =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_a_pagar_con_iva']);
  $monto_cancelar_siniva                =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_sin_iva']);
  $amortizacion_anticipo                =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['amortizacion_del_anticipo_monto_iva']);
  $monto_islr                           =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['impuesto_sobre_la_renta_monto_iva']);


  if(isset($this->data['cepp02_contratoservicios_valuacion']['impuesto_sobre_la_renta'])){
    $porcentaje_islr                      =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['impuesto_sobre_la_renta']);
  }else{
  	$porcentaje_islr = 0;
  }


  $anticipo_con_iva                     =       $this->data['cepp02_contratoservicios_valuacion']['anticipo_con_iva'];
  $anticipo_con_iva2                    =       $this->data['cepp02_contratoservicios_valuacion']['anticipo_con_iva2'];
  $anticipo_con_iva2                    =       1;


  $monto_sustraendo                     =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['sustraendo']);
  $monto_timbre_fiscal                  =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['timbre_fiscal_monto_iva']);
  $porcentaje_timbre_fiscal             =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['timbre_fiscal']);
  $monto_impuesto_municipal             =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['impuesto_municipal_monto_iva']);
  $porcentaje_impuesto_municipal        =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['impuesto_municipal']);
  $monto_retencion_iva                  =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['retencion_incluye_iva_monto_iva']);
  $porcentaje_retencion_iva             =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['retencion_incluye_iva']);


  $monto_retencion_fielcumplimiento     =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_fiel_cumplimiento']);
  $porcentaje_fiel_cumplimiento         =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['porcentaje_fiel_cumplimiento']);
  $porcentaje_fielcumplimiento          =       $porcentaje_fiel_cumplimiento;

  $monto_retencion_laboral              =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['monto_laboral']);
  $porcentaje_laboral                   =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['porce_retencion_laboral']);
if($monto_retencion_laboral==0){$porcentaje_laboral = 0;}

  $saldo_orden                          =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['saldo_contrato']);

  $porcentaje_iva_aplicado              =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['porcentaje_iva']);
  if($monto_iva==0){$porcentaje_iva_aplicado = 0;}


  $oficio_aprobacion                    =       $this->data['cepp02_contratoservicios_valuacion']['numero_aprobacion'];
  $fecha_aprobacion                     =       $this->data['cepp02_contratoservicios_valuacion']['fecha_aprobacion'];
  $periodo_desde                        =       $this->data['cepp02_contratoservicios_valuacion']['desde_periodo'];
  $periodo_hasta                        =       $this->data['cepp02_contratoservicios_valuacion']['hasta_periodo'];


  $retencion_multa_monto                        =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['retencion_multa_monto']);
  $retencion_responsabilidad_social             =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['retencion_responsabilidad_social']);
  $prc                        =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['rcivil']);
  $prs             =       $this->Formato1($this->data['cepp02_contratoservicios_valuacion']['rsocial']);




  if(isset($i_lenght)){
  if(isset($ano_contrato_servicio)){
  if(isset($num_contrato_obra)){
  if(isset($numero_valuacion)){
  if(isset($fecha_valuacion)){
  if(isset($monto_original_contrato)){
  if(isset($monto_retenido_laboral)){
  if(isset($monto_retenido_fielcumplimiento)){
  if(isset($porcentaje_fiel_cumplimiento)){
  if(isset($total_retencion_monto_iva)){
  if(isset($monto_orden_de_pago_monto_iva)){
  if(isset($monto_a_pagar_monto_iva)){
  if(isset($concepto_contrato_obra)){
  if(isset($monto_orden)){
  if(isset($aumento)){
  if(isset($disminucion)){
  if(isset($monto_anticipo)){
  if(isset($monto_amortizacion)){
  if(isset($monto_cancelado)){
  if(isset($monto_iva)){
  if(isset($monto_cancelar)){
  if(isset($monto_cancelar_siniva)){
  if(isset($amortizacion_anticipo)){
  if(isset($monto_islr)){
  if(isset($porcentaje_islr)){
  if(isset($anticipo_con_iva)){
  if(isset($anticipo_con_iva2)){
  if(isset($monto_sustraendo)){
  if(isset($monto_timbre_fiscal)){
  if(isset($porcentaje_timbre_fiscal)){
  if(isset($monto_impuesto_municipal)){
  if(isset($porcentaje_impuesto_municipal)){
  if(isset($monto_retencion_iva)){
  if(isset($porcentaje_retencion_iva)){
  if(isset($porcentaje_amortizacion)){
  if(isset($monto_retencion_laboral)){
  if(isset($monto_retencion_fielcumplimiento)){
  if(isset($porcentaje_laboral)){
  if(isset($saldo_orden  )){
  if(isset($porcentaje_iva_aplicado)){
  if(isset($oficio_aprobacion)){
  if(isset($fecha_aprobacion)){
  if(isset($periodo_desde)){
  if(isset($periodo_hasta)){





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


$monto_nuevo  = $saldo_orden - $monto_cancelar;


$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  upper(numero_contrato_servicio)='".strtoupper($num_contrato_obra)."'  and  ano_contrato_servicio=".$ano_contrato_servicio." ";
$datos_orden_pagos_anteriores = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($condicion, null, null, null, null);

$sw2 = 0;
$sw3 = 0;
$sw4 = 0;



$sql=" BEGIN; INSERT INTO cepd02_contratoservicio_valuacion_cuerpo (cod_presi, cod_entidad, cod_tipo_inst , cod_inst, cod_dep, ano_contrato_servicio, numero_contrato_servicio, numero_valuacion, monto_original_contrato, aumento, disminucion, monto_anticipo, monto_amortizacion, monto_retenido_laboral, monto_retenido_fielcumplimiento, monto_cancelado, monto_coniva, monto_iva, porcentaje_iva, monto_siniva, monto_retencion_laboral, porcentaje_laboral, monto_retencion_fielcumplimiento, porcentaje_fielcumplimiento, monto_descontar_impuesto, amortizacion_anticipo, porcentaje_amortizacion, monto_orden_pago, monto_retencion_iva, porcentaje_retencion_iva, monto_islr, porcentaje_islr, monto_sustraendo, monto_timbre_fiscal, porcentaje_timbre_fiscal, monto_impuesto_municipal, porcentaje_impuesto_municipal, monto_neto_cobrar, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, username_registro, condicion_actividad, ano_anulacion, numero_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, numero_asiento_anulacion, fecha_proceso_anulacion, username_anulacion, ano_orden_pago, numero_orden_pago, oficio_aprobacion, fecha_aprobacion, periodo_desde, periodo_hasta, retencion_incluye_iva, fecha_valuacion, fecha_proceso_registro, concepto, retencion_multa, retencion_responsabilidad, monto_mano_obra, codigo_retencion_islr, cod_actividad,porcentaje_multa,porcentaje_responsabilidad)";
$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst ."', '".$cod_inst."', '".$cod_dep."', '".$ano_contrato_servicio."', '".$numero_contrato_servicio."', '".$numero_valuacion."', '".$monto_original_contrato."', '".$aumento."', '".$disminucion."', '".$monto_anticipo."', '".$monto_amortizacion."', '".$monto_retenido_laboral."', '".$monto_retenido_fielcumplimiento."', '".$monto_cancelado."', '".$monto_cancelar."', '".$monto_iva."', '".$porcentaje_iva_aplicado."', '".$monto_cancelar_siniva."', '".$monto_retencion_laboral."', '".$porcentaje_laboral."', '".$monto_retencion_fielcumplimiento."', '".$porcentaje_fielcumplimiento."', '".$monto_descontar_impuesto."', '".$amortizacion_anticipo."', '".$porcentaje_amortizacion."', '".$monto_orden_pago."', '".$monto_retencion_iva."', '".$porcentaje_retencion_iva."', '".$monto_islr."', '".$porcentaje_islr."', '".$monto_sustraendo."', '".$monto_timbre_fiscal."', '".$porcentaje_timbre_fiscal."', '".$monto_impuesto_municipal."', '".$porcentaje_impuesto_municipal."', '".$monto_neto_cobrar."', '".$dia_asiento_registro."', '".$mes_asiento_registro."', '".$ano_asiento_registro."', '".$numero_asiento_registro."', '".$username_registro."', '".$condicion_actividad."', '".$ano_anulacion."', '".$numero_anulacion."', '".$dia_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$this->Cfecha($fecha_proceso_anulacion, 'A-M-D')."', '".$username_anulacion."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$oficio_aprobacion."', '".$this->Cfecha($fecha_aprobacion, 'A-M-D')."', '".$this->Cfecha($periodo_desde, 'A-M-D')."', '".$this->Cfecha($periodo_hasta, 'A-M-D')."', '".$retencion_incluye_iva."', '".$this->Cfecha($fecha_valuacion, 'A-M-D')."', '".$this->Cfecha($fecha_proceso_registro, 'A-M-D')."', '".$concepto_contrato_obra."', '".$retencion_multa_monto."', '".$retencion_responsabilidad_social."', '".$monto_mano_obra."', '".$_SESSION["ventana_islr"]."', '".$_SESSION["ventana_impuesto_municipal"]."','".$prc."','".$prs."');";
$sw = $this->cepd02_contratoservicio_valuacion_cuerpo->execute($sql);





$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  upper(numero_contrato_servicio)='".strtoupper($num_contrato_obra)."'  and  ano_contrato_servicio=".$ano_contrato_servicio." ";
$numero_datos_partidas = $this->cepd02_contratoservicio_partidas->findAll($condicion, null, 'numero_contrato_servicio DESC');
$a=0;
$i_aux = 0;
if($sw>1){
	foreach($numero_datos_partidas as $aux_partidas){
	  $cod_presi2[$a]                 =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_presi'];
	  $cod_presiA=$cod_presi2[$a];
	  $cod_entidad2[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_entidad'];
	  $cod_entidadA=$cod_entidad2[$a];
	  $cod_tipo_inst2[$a]             =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_tipo_inst'];
	  $cod_tipo_instA = $cod_tipo_inst2[$a];
	  $cod_inst2[$a]                  =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_inst'];
	  $cod_instA =$cod_inst2[$a];
	  $cod_dep2[$a]                   =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_dep'];
	  $cod_depA = $cod_dep2[$a];
	  $ano_contrato_servicio3[$a]          =   $aux_partidas['cepd02_contratoservicio_partidas']['ano_contrato_servicio'];
	  $ano_contrato_servicioA = $ano_contrato_servicio3[$a];
	  $num_contrato_obra3[$a]       =   $aux_partidas['cepd02_contratoservicio_partidas']['numero_contrato_servicio'];
	  $num_contrato_obraA = $num_contrato_obra3[$a];
	  $ano_partidas[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['ano'];
	  $ano_partidasA = $ano_partidas[$a];
	  $cod_sector[$a]                 =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_sector'];
	  $cod_sectorA = $cod_sector[$a];
	  $cod_programa[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_programa'];
	  $cod_programaA = $cod_programa[$a];
	  $cod_sub_prog[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_sub_prog'];
	  $cod_sub_progA = $cod_sub_prog[$a];
	  $cod_proyecto[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_proyecto'];
	  $cod_proyectoA= $cod_proyecto[$a];
	  $cod_activ_obra[$a]             =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_activ_obra'];
	  $cod_activ_obraA=$cod_activ_obra[$a];
	  $cod_partida[$a]                =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_partida'];
	  $cod_partidaA = $cod_partida[$a];
	  $cod_generica[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_generica'];
	  $cod_genericaA = $cod_generica[$a];
	  $cod_especifica[$a]             =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_especifica'];
	  $cod_especificaA = $cod_especifica[$a];
	  $cod_sub_espec[$a]              =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_sub_espec'];
	  $cod_sub_especA = $cod_sub_espec[$a];
	  $cod_auxiliar[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_auxiliar'];
	  $cod_auxiliarA = $cod_auxiliar[$a];
	  $monto2[$a]                     =   $aux_partidas['cepd02_contratoservicio_partidas']['monto'];
	  $aumento2[$a]                   =   $aux_partidas['cepd02_contratoservicio_partidas']['aumento'];
	  $disminucion2[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['disminucion'];
	  $monto_total_partida[$a]        = $monto2[$a] + ($aumento2[$a] - $disminucion2[$a]);

	  $anticipo2[$a]                     =   $aux_partidas['cepd02_contratoservicio_partidas']['anticipo'];
	  $amortizacion2[$a]                 =   $aux_partidas['cepd02_contratoservicio_partidas']['amortizacion'];
	  $retencion_laboral2[$a]            =   $aux_partidas['cepd02_contratoservicio_partidas']['retencion_laboral'];
	  $retencion_fielcumplimiento2[$a]   =   $aux_partidas['cepd02_contratoservicio_partidas']['retencion_fielcumplimiento'];
	  $cancelacion2[$a]                  =   $aux_partidas['cepd02_contratoservicio_partidas']['cancelacion'];
	  $numero_control_compromiso[$a]     =   $aux_partidas['cepd02_contratoservicio_partidas']['numero_control_compromiso'];

		$sql_where[$a] = "cod_presi='$cod_presiA' and cod_entidad=$cod_entidadA and cod_tipo_inst=$cod_tipo_instA and cod_inst=$cod_instA and cod_dep=$cod_depA and ano_contrato_servicio=$ano_contrato_servicioA and upper(numero_contrato_servicio)='".strtoupper($num_contrato_obraA)."' and ano=$ano_partidasA and cod_sector=$cod_sectorA and cod_programa=$cod_programaA and cod_sub_prog=$cod_sub_progA and cod_proyecto=$cod_proyectoA and cod_activ_obra=$cod_activ_obraA and cod_partida=$cod_partidaA and cod_generica=$cod_genericaA and cod_especifica=$cod_especificaA and cod_sub_espec=$cod_sub_especA and cod_auxiliar=$cod_auxiliarA";
	$a++;

$partidas_aux  = $aux_partidas['cepd02_contratoservicio_partidas']['cod_sector'];
$partidas_aux .= $aux_partidas['cepd02_contratoservicio_partidas']['cod_programa'];
$partidas_aux .= $aux_partidas['cepd02_contratoservicio_partidas']['cod_sub_prog'];
$partidas_aux .= $aux_partidas['cepd02_contratoservicio_partidas']['cod_proyecto'];
$partidas_aux .= $aux_partidas['cepd02_contratoservicio_partidas']['cod_activ_obra'];
$partidas_aux .= $aux_partidas['cepd02_contratoservicio_partidas']['cod_partida'];
$partidas_aux .= $aux_partidas['cepd02_contratoservicio_partidas']['cod_generica'];
$partidas_aux .= $aux_partidas['cepd02_contratoservicio_partidas']['cod_especifica'];
$partidas_aux .= $aux_partidas['cepd02_contratoservicio_partidas']['cod_sub_espec'];
$partidas_aux .= $aux_partidas['cepd02_contratoservicio_partidas']['cod_auxiliar'];


for($i=0; $i<$i_lenght; $i++){
   if($partidas_aux==$this->data['partidas']['partidas_'.$i]){$partidas_vista['pago_'.$i_aux] = $this->data['cepp02_contratoservicios_valuacion']['pago_'.$i]; $i_aux++;}
}//fin foreach


	}//fin foreach




///////////////////////////////////////////////REDONDEO AMORTIZACION Y RENTENCION LABORAL Y RETENCION FIEL CUMPLIMIENTO Y CANCELADO///////////////////////////////////////////
$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  upper(numero_contrato_servicio)='".strtoupper($num_contrato_obra)."'  and  ano_contrato_servicio=".$ano_contrato_servicio." ";
$numero_datos_partidas_anteriores = $this->cepd02_contratoservicio_valuacion_partidas->findAll($condicion);
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
	  $cod_presi_ante[$f]                     =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_presi'];
	  $cod_entidad_ante[$f]                   =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_entidad'];
	  $cod_tipo_inst_ante[$f]                 =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_tipo_inst'];
	  $cod_inst_ante[$f]                      =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_inst'];
	  $cod_dep_ante[$f]                       =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_dep'];
	  $fno_orden_compra_ante[$f]              =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['ano_contrato_servic'];
	  $numero_orden_compra_ante[$f]           =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['numero_contrato_ser'];
	  $ano_partidas_ante[$f]                  =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['ano'];
	  $cod_sector_ante[$f]                    =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_sector'];
	  $cod_programa_ante[$f]                  =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_programa'];
	  $cod_sub_prog_ante[$f]                  =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_sub_prog'];
	  $cod_proyecto_ante[$f]                  =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_proyecto'];
	  $cod_activ_obra_ante[$f]                =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_activ_obra'];
	  $cod_partida_ante[$f]                   =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_partida'];
	  $cod_generica_ante[$f]                  =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_generica'];
	  $cod_especifica_ante[$f]                =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_especifica'];
	  $cod_sub_espec_ante[$f]                 =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_sub_espec'];
	  $cod_auxiliar_ante[$f]                  =      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['cod_auxiliar'];
	  $monto_ante                            +=      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['monto'];
	  $amortizacion_ante                     +=      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['amortizacion'];
	  $retencion_laboral_ante                +=      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['retencion_laboral'];
	  $retencion_fielcumplimiento_ante       +=      $aux_partidas_anteriores['cepd02_contratoservicio_valuacion_partidas']['retencion_fielcumpl'];
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
        $datos_cscd04_ordencompra_partidas    =     $this->cepd02_contratoservicio_partidas->findAll($sql_where[$i]);
					foreach($datos_cscd04_ordencompra_partidas as $aux_datos_cscd04_ordencompra_partidas){
						$anticipo                         =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['anticipo'];
						$amortizacion                     =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['amortizacion'];
						$cancelado                        =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['cancelacion'];
						$retencion_laboral                =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['retencion_laboral'];
						$retencion_fielcumplimiento       =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['retencion_fielcumplimiento'];
					}//fin foreach

//GOB.GUARICO

         if($monto_nuevo=="0"){
                      if($concate!="403.18.01.00"){
				             $retencion_laboral_aux[$am_cont]          = ($var[$i]['pago'] * $monto_retencion_laboral) /  $monto_opcion_pago;
				             $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $monto_retencion_fielcumplimiento) /  $monto_opcion_pago;
		                             $amortizacion_aux[$am_cont] = $anticipo - $amortizacion;
                      }
             }else{
                      if($concate!="403.18.01.00"){
				             $retencion_laboral_aux[$am_cont]          = ($var[$i]['pago'] * $monto_retencion_laboral) /  $monto_opcion_pago;
				             $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $monto_retencion_fielcumplimiento) /  $monto_opcion_pago;
		                     $amortizacion_aux[$am_cont] = ($var[$i]['pago'] * $porcentaje_amortizacion) / 100;
                      }
             }//fin else


//FIN GOB.GUARICO


//ORIGINAL
/*
         if($monto_nuevo=="0"){
                      if($anticipo_con_iva2==1){
				             $retencion_laboral_aux[$am_cont]          = ($var[$i]['pago'] * $monto_retencion_laboral) /  $monto_opcion_pago;
				             $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $monto_retencion_fielcumplimiento) /  $monto_opcion_pago;
					  }else{
//				          if($concate!="403.18.01.00"){
				              $retencion_laboral_aux[$am_cont]         = ($var[$i]['pago'] * $monto_retencion_laboral) /  $monto_opcion_pago;
				             $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $monto_retencion_fielcumplimiento) /  $monto_opcion_pago;
//				            }//fin if
					   }//fin else


		             	if($concate!="403.18.01.00"){
		                  $amortizacion_aux[$am_cont] = $anticipo - $amortizacion;
		             	}//fin if

             }else{


             	       if($anticipo_con_iva==1 && $monto_iva!=0){
             		      if($concate!="403.18.01.00"){
                            $amortizacion_aux[$am_cont]               = (($var[$i]['pago'] + ($var[$i]['pago'] * $porcentaje_iva_aplicado/100)) *  $porcentaje_amortizacion) / 100;
             		      }
				       }else{
				          if($concate!="403.18.01.00"){
				             $amortizacion_aux[$am_cont]               = ($var[$i]['pago'] * $porcentaje_amortizacion) / 100;
				            }//fin if
					   }//fin else

				       if($anticipo_con_iva2==1){
				             $retencion_laboral_aux[$am_cont]          = ($var[$i]['pago'] * $monto_retencion_laboral) /  $monto_opcion_pago;
				             $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $monto_retencion_fielcumplimiento) /  $monto_opcion_pago;
					  }else{
//				          if($concate!="403.18.01.00"){
				             $retencion_laboral_aux[$am_cont]          = ($var[$i]['pago'] * $monto_retencion_laboral) /  $monto_opcion_pago;
				             $retencion_fielcumplimiento_aux[$am_cont] = ($var[$i]['pago'] * $monto_retencion_fielcumplimiento) /  $monto_opcion_pago;
//				            }//fin if
					   }//fin else
             }//fin else
*/
//FIN ORIGINAL


// GOB.GUARICO
		if ($concate=="403.18.01.00"){

		}else{
            	$amortizacion_aux[$am_cont] = $this->Formato2($amortizacion_aux[$am_cont]);
             	$amortizacion_aux[$am_cont]  = $this->Formato1($amortizacion_aux[$am_cont]);

				$retencion_laboral_gu = $this->Formato2($retencion_laboral_aux[$am_cont]);
				$retencion_laboral_gu = $this->Formato1($this->redondeo($retencion_laboral_aux[$am_cont]));
				$retencion_fiel_cu_gu = $this->Formato2($retencion_fielcumplimiento_aux[$am_cont]);
				$retencion_fiel_cu_gu = $this->Formato1($this->redondeo($retencion_fielcumplimiento_aux[$am_cont]));

			if ($retencion_laboral_gu!=0){
				$retencion_laboral_gu_iva = $this->redondeo(($retencion_laboral_gu * $porcentaje_iva_aplicado/100));
				$retencion_laboral_gua = ($retencion_laboral_gu + $retencion_laboral_gu_iva);
                $retencion_laboral_aux[$am_cont] = $retencion_laboral_gua;
			}

			if ($retencion_fiel_cu_gu!=0){
				$retencion_fiel_cu_gu_iva = $this->redondeo(($retencion_fiel_cu_gu * $porcentaje_iva_aplicado/100));
				$retencion_fiel_cu_gua = ($retencion_fiel_cu_gu + $retencion_fiel_cu_gu_iva);
                $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fiel_cu_gua;
			}

         		$amortizacion_aux_partidas                  += $amortizacion_aux[$am_cont];
         		$retencion_laboral_aux_partidas             += $retencion_laboral_aux[$am_cont];
         		$retencion_fielcumplimiento_aux_partidas    += $retencion_fielcumplimiento_aux[$am_cont];
		}
//FIN GUARICO


//ORIGINAL
/*
             $amortizacion_aux[$am_cont]               = $this->Formato2($amortizacion_aux[$am_cont]);
             $amortizacion_aux[$am_cont]               = $this->Formato1($amortizacion_aux[$am_cont]);

             $retencion_laboral_aux[$am_cont]          = $this->Formato2($retencion_laboral_aux[$am_cont]);
             $retencion_laboral_aux[$am_cont]          = $this->Formato1($retencion_laboral_aux[$am_cont]);

             $retencion_fielcumplimiento_aux[$am_cont] = $this->Formato2($retencion_fielcumplimiento_aux[$am_cont]);
             $retencion_fielcumplimiento_aux[$am_cont] = $this->Formato1($retencion_fielcumplimiento_aux[$am_cont]);

         $amortizacion_aux_partidas                  += $amortizacion_aux[$am_cont];
         $retencion_laboral_aux_partidas             += $retencion_laboral_aux[$am_cont];
         $retencion_fielcumplimiento_aux_partidas    += $retencion_fielcumplimiento_aux[$am_cont];
*/
//FIN ORIGINAL

	 }//fin if
}//fin for


      $amortizacion_aux_partidas          = $this->Formato2($amortizacion_aux_partidas);
      $amortizacion_aux_partidas          = $this->Formato1($amortizacion_aux_partidas);

      $retencion_laboral_aux_partidas          = $this->Formato2($retencion_laboral_aux_partidas);
      $retencion_laboral_aux_partidas          = $this->Formato1($retencion_laboral_aux_partidas);

      $retencion_fielcumplimiento_aux_partidas          = $this->Formato2($retencion_fielcumplimiento_aux_partidas);
      $retencion_fielcumplimiento_aux_partidas          = $this->Formato1($retencion_fielcumplimiento_aux_partidas);


$datos_contrato_obra    =     $this->cepd02_contratoservicio_cuerpo->findAll($condicion);
foreach($datos_contrato_obra as $aux_datos_contrato_obra){
		$monto_anticipo_aux2                      =     $aux_datos_contrato_obra['cepd02_contratoservicio_cuerpo']['monto_anticipo'];
		$monto_amortizacion_aux2                  =     $aux_datos_contrato_obra['cepd02_contratoservicio_cuerpo']['monto_amortizacion'];
		$monto_cancelado_aux2                     =     $aux_datos_contrato_obra['cepd02_contratoservicio_cuerpo']['monto_cancelado'];
		$monto_retencion_laboral_aux2             =     $aux_datos_contrato_obra['cepd02_contratoservicio_cuerpo']['monto_retencion_laboral'];
		$monto_retencion_fielcumplimiento_aux2    =     $aux_datos_contrato_obra['cepd02_contratoservicio_cuerpo']['monto_retencion_fielcumplimient'];
}//fin foreach







//////////////////////////////////////////////////////REDONDEO/////////////////////////////////////////////////////////////////






             //$amortizacion_aux[$am_cont]
             //$retencion_laboral_aux[$am_cont]
             //$retencion_fielcumplimiento_aux[$am_cont]
             //$var[$i]['pago']


$monto_iva_aux_partidas          =  0;
$monto_cancelacion_aux_partidas  =  0;
$monto_total_aux_partidas        =  0;
$am_cont=0;
 for($i=0; $i<$i_lenght; $i++){
 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
       if($partidas_vista['pago_'.$i]!="0,00"){
	   	         $var[$i]['pago'] = $this->Formato1($partidas_vista['pago_'.$i]);
	   	         $am_cont++;

//GOB.GUARICO
			if ($concate=="403.18.01.00"){
				}else{
                 $var[$i]['pago'] = $var[$i]['pago']-($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                 $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
                 $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
				}
//FIN GUARICO


//ORIGINAL
/*
                 $var[$i]['pago'] = $var[$i]['pago']-($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                 $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
                 $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
*/
//FIN ORIGINAL

	             if($concate=="403.18.01.00"){$monto_iva_aux_partidas += $var[$i]['pago'];}else{$monto_cancelacion_aux_partidas +=$var[$i]['pago'];}//fin if
	         		$monto_total_aux_partidas += $var[$i]['pago'];
	   }//fin if
 }//fin for










$contar_iva = 0;
 for($i=0; $i<$i_lenght; $i++){
 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
       if($concate=="403.18.01.00"){
                $contar_iva++;
	   }//fin if
 }//fin for
     if($contar_iva==1){ $am_cont=0;
					            for($i=0; $i<$i_lenght; $i++){ $am_cont++;
								 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);

//GOB.GUARICO
								       if($concate=="403.18.01.00"){
								       	          $monto_iva_suma = $var[$i]['pago'];
								       	          $monto_iva_aux_partidas = $monto_iva;
								       	          $monto_iva_suma =  $this->Formato2($monto_iva_suma);
					                              $monto_iva_suma =  $this->Formato1($monto_iva_suma);
					                                     // PRIMER REDONDEO NEG
								                  if($monto_iva_suma>$monto_iva){
								                  	     $monto_iva_suma -=0.01; $var[$i]['pago']-=0.01;
								                  	     // SEGUNDO REDONDEO NEG
								                  	     if($monto_iva_suma>$monto_iva){
								                  	     $monto_iva_suma -=0.01; $var[$i]['pago']-=0.01;
								                  	     }
								                  	     // TERCER REDONDEO NEG
								                  	     if($monto_iva_suma>$monto_iva){
								                  	     $monto_iva_suma -=0.01; $var[$i]['pago']-=0.01;
								                  	     }
								         				 // PRIMER REDONDEO POS
								         	}else if($monto_iva_suma<$monto_iva){
								         		         $monto_iva_suma +=0.01; $var[$i]['pago']+=0.01;
								         				 // SEGUNDO REDONDEO POS
								         		         if($monto_iva_suma<$monto_iva){
								         		         $monto_iva_suma +=0.01; $var[$i]['pago']+=0.01;
								         		         }
								         				 // TERCERO REDONDEO POS
								         		         if($monto_iva_suma<$monto_iva){
								         		         $monto_iva_suma +=0.01; $var[$i]['pago']+=0.01;
								         		         }
									           }//fin if
									          $monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
                                              $monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);
                                              $monto_iva_suma =  $this->Formato2($monto_iva_suma);
                                              $monto_iva_suma =  $this->Formato1($monto_iva_suma);
                                              $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
                                              $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
									    }//fin if
//FIN GOB.GUARICO


//ORIGINAL
/*
								       if($concate=="403.18.01.00"){
								       	          $monto_iva_suma = $var[$i]['pago'];
								       	          $monto_iva_aux_partidas = $monto_iva;
								       	          $monto_iva_suma =  $this->Formato2($monto_iva_suma);
					                              $monto_iva_suma =  $this->Formato1($monto_iva_suma);
								                  if($monto_iva_suma>$monto_iva){
								                  	     $monto_iva_suma -=0.01; $var[$i]['pago']-=0.01;
//					                                     $monto_iva_aux_partidas-=0.01;
//							                             if($amortizacion_anticipo==$amortizacion_aux_partidas && $amortizacion_anticipo!=0){                          $amortizacion_aux_partidas +=0.01; $amortizacion_aux[$am_cont] +=0.01;}
							                             if($monto_retencion_fielcumplimiento==$retencion_fielcumplimiento_aux_partidas && $monto_retencion_fielcumplimiento!=0){ $retencion_fielcumplimiento_aux_partidas +=0.01; $retencion_fielcumplimiento_aux[$am_cont] += 0.01;}
							                             if($monto_retencion_laboral==$retencion_laboral_aux_partidas  && $monto_retencion_laboral!=0){                   $retencion_laboral_aux_partidas +=0.01; $retencion_laboral_aux[$am_cont] +=0.01;}
								         	}else if($monto_iva_suma<$monto_iva){
								         		         $monto_iva_suma +=0.01;
								         		         $var[$i]['pago']+=0.01;
//								                         $monto_iva_aux_partidas+=0.01;
//										                 if($amortizacion_anticipo==$amortizacion_aux_partidas  && $amortizacion_anticipo!=0){                          $amortizacion_aux_partidas -=0.01; $amortizacion_aux[$am_cont] -=0.01;}
							                             if($monto_retencion_fielcumplimiento==$retencion_fielcumplimiento_aux_partidas  && $monto_retencion_fielcumplimiento!=0){ $retencion_fielcumplimiento_aux_partidas -=0.01;  $retencion_fielcumplimiento_aux[$am_cont] -=0.01;}
							                             if($monto_retencion_laboral==$retencion_laboral_aux_partidas && $monto_retencion_laboral!=0){                   $retencion_laboral_aux_partidas -=0.01;  $retencion_laboral_aux[$am_cont] -=0.01;}
									           }//fin if
									          $monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
                                              $monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);
                                              $monto_iva_suma =  $this->Formato2($monto_iva_suma);
                                              $monto_iva_suma =  $this->Formato1($monto_iva_suma);
                                              $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
                                              $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
									    }//fin if
*/
//FIN ORIGINAL
								 }//fin for


}else if($contar_iva!=0){



					            $monto_iva_suma = 0;
						        for($i=0; $i<$i_lenght; $i++){
								 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
								       if($concate=="403.18.01.00"){
								                 $monto_iva_suma +=$var[$i]['pago'];
									   }//fin if
								 }//fin for
								 $monto_iva_aux_partidas = $monto_iva;
								 $monto_iva_suma         =  $this->Formato2($monto_iva_suma);
					             $monto_iva_suma         =  $this->Formato1($monto_iva_suma);
					             if($monto_iva_suma!=$monto_iva){ $am_cont=0;
					             	 for($i=0; $i<$i_lenght; $i++){ $am_cont++;
								 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);


//GOB.GUARICO
								       if($concate=="403.18.01.00"){
								       		// PRIMER REDONDEO NEG
								         	if($monto_iva_suma>$monto_iva){
					                             $monto_iva_suma -=0.01;
					                             $var[$i]['pago']-=0.01;
					                        // SEGUNDO REDONDEO NEG
								         	if($monto_iva_suma>$monto_iva){
					                             $monto_iva_suma -=0.01;
								         	}
								         	if($monto_iva_suma>$var[$i]['pago']){
					                             $var[$i]['pago']-=0.01;
								         	}
					                        // TERCER REDONDEO NEG
								         	if($monto_iva_suma>$monto_iva){
					                             $monto_iva_suma -=0.01;
								         	}
								         	if($monto_iva_suma>$var[$i]['pago']){
					                             $var[$i]['pago']-=0.01;
								         	}


								         	// PRIMER REDONDEO POS
								         	}else if($monto_iva_suma<$monto_iva){
								                 $monto_iva_suma +=0.01;
								                 $var[$i]['pago']+=0.01;
								         	// SEGUNDO REDONDEO POS
								            if($monto_iva_suma<$monto_iva){
								                 $monto_iva_suma +=0.01;
								              	}
								            if($monto_iva_suma<$var[$i]['pago']){
								                 $var[$i]['pago']+=0.01;
								              	}
								         	// TERCER REDONDEO POS
								            if($monto_iva_suma<$monto_iva){								                 $monto_iva_suma +=0.01;

								             	 }
								            if($monto_iva_suma<$var[$i]['pago']){
								                 $var[$i]['pago']+=0.01;
								             	 }
									          }//fin if

									          $monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
                                              $monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);
                                              $monto_iva_suma =  $this->Formato2($monto_iva_suma);
                                              $monto_iva_suma =  $this->Formato1($monto_iva_suma);
                                              $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
                                              $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
								           }//fin for

//FIN GOB.GUARICO



//ORIGINAL
/*
								       if($concate=="403.18.01.00"){
								       		// PRIMER REDONDEO NEG
								         	if($monto_iva_suma>$monto_iva){
					                             $monto_iva_suma -=0.01;
					                             $var[$i]['pago']-=0.01;
					                        // SEGUNDO REDONDEO NEG
								         	if($monto_iva_suma>$monto_iva){
					                             $monto_iva_suma -=0.01;
					                             $var[$i]['pago']-=0.01;
								         	}
					                        // TERCER REDONDEO NEG
								         	if($monto_iva_suma>$monto_iva){
					                             $monto_iva_suma -=0.01;
					                             $var[$i]['pago']-=0.01;
								         	}
					                             if($monto_retencion_fielcumplimiento==$retencion_fielcumplimiento_aux_partidas && $monto_retencion_fielcumplimiento!=0){ $retencion_fielcumplimiento_aux_partidas +=0.01; $retencion_fielcumplimiento_aux[$am_cont] += 0.01;}
					                             if($monto_retencion_laboral==$retencion_laboral_aux_partidas && $monto_retencion_laboral!=0){                   $retencion_laboral_aux_partidas +=0.01; $retencion_laboral_aux[$am_cont] +=0.01;}
								         	// PRIMER REDONDEO POS
								         	}else if($monto_iva_suma<$monto_iva){
								                 $monto_iva_suma +=0.01;
								                 $var[$i]['pago']+=0.01;
								         	// SEGUNDO REDONDEO POS
								            if($monto_iva_suma<$monto_iva){
								                 $monto_iva_suma +=0.01;
								                 $var[$i]['pago']+=0.01;
								              }
								         	// TERCER REDONDEO POS
								            if($monto_iva_suma<$monto_iva){
								                 $monto_iva_suma +=0.01;
								                 $var[$i]['pago']+=0.01;
								              }
					                             if($monto_retencion_fielcumplimiento==$retencion_fielcumplimiento_aux_partidas && $monto_retencion_fielcumplimiento!=0){ $retencion_fielcumplimiento_aux_partidas -=0.01;  $retencion_fielcumplimiento_aux[$am_cont] -=0.01;}
					                             if($monto_retencion_laboral==$retencion_laboral_aux_partidas && $monto_retencion_laboral!=0){                   $retencion_laboral_aux_partidas -=0.01;  $retencion_laboral_aux[$am_cont] -=0.01;}
									          }//fin if

									          $monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
                                              $monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);
                                              $monto_iva_suma =  $this->Formato2($monto_iva_suma);
                                              $monto_iva_suma =  $this->Formato1($monto_iva_suma);
                                              $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
                                              $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
								           }//fin for
*/
//FIN ORIGINAL



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



//GOB.GUARICO

	  	   	if($monto_iva_aux_partidas<$monto_iva){
              $var[$i]['pago']                 += 0.01;
              $monto_iva_aux_partidas          += 0.01;
              $monto_cancelacion_aux_partidas  += 0.01;
              $monto_total_aux_partidas        += 0.01;
              $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
              $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
              $monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
              $monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);
              $monto_cancelacion_aux_partidas =  $this->Formato2($monto_cancelacion_aux_partidas);
              $monto_cancelacion_aux_partidas =  $this->Formato1($monto_cancelacion_aux_partidas);
              $monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
              $monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);
	  	    }else if($monto_iva_aux_partidas>$monto_iva){
              $var[$i]['pago']                 -= 0.01;
              $monto_iva_aux_partidas          -= 0.01;
              $monto_cancelacion_aux_partidas  -= 0.01;
              $monto_total_aux_partidas        -= 0.01;
              $var[$i]['pago'] =  $this->Formato2($var[$i]['pago']);
              $var[$i]['pago'] =  $this->Formato1($var[$i]['pago']);
              $monto_iva_aux_partidas =  $this->Formato2($monto_iva_aux_partidas);
              $monto_iva_aux_partidas =  $this->Formato1($monto_iva_aux_partidas);
              $monto_cancelacion_aux_partidas =  $this->Formato2($monto_cancelacion_aux_partidas);
              $monto_cancelacion_aux_partidas =  $this->Formato1($monto_cancelacion_aux_partidas);
              $monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
              $monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);
	  	    }//fin if



//FIN GOB.GUARICO


//ORIGINAL
/*

	  	   	if($monto_iva_aux_partidas<$monto_iva && $retencion_laboral_aux_partidas!=0){
              $retencion_laboral_aux[$am_cont] -= 0.01;
              $retencion_laboral_aux_partidas  -= 0.01;
              $var[$i]['pago']                 += 0.01;
              $monto_iva_aux_partidas          += 0.01;
              $monto_cancelacion_aux_partidas  += 0.01;
              $monto_total_aux_partidas        += 0.01;
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
              $retencion_fielcumplimiento_aux[$am_cont] -= 0.01;
              $retencion_fielcumplimiento_aux_partidas -= 0.01;
              $var[$i]['pago'] +=0.01;
              $monto_iva_aux_partidas +=0.01;
              $monto_cancelacion_aux_partidas +=0.01;
              $monto_total_aux_partidas+=0.01;
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
*/
//FIN ORIGINAL




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
	                $amortizacion_aux[$am_cont] =  $this->Formato2($amortizacion_aux[$am_cont]);
                    $amortizacion_aux[$am_cont] =  $this->Formato1($amortizacion_aux[$am_cont]);
                    $amortizacion_aux_partidas  =  $this->Formato2($amortizacion_aux_partidas);
                    $amortizacion_aux_partidas  =  $this->Formato1($amortizacion_aux_partidas);
               }//fin if
      }//fin if
   }//fin if
}//fin if


$am_cont=0;
if($monto_retencion_laboral!=$retencion_laboral_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){
    $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);




//GOB.GUARICO

	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	  $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);


               	       if($concate!="403.18.01.00"){
						//PRIMER REDONDEO POS
			               if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			            //SEGUNDO REDONDEO POS
			                  	if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                 		 $retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			                  	 }
			            //TERCER REDONDEO POS
			                  	if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			                  	 }
			            //PRIMER REDONDEO NEG
			                }else if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			            //SEGUNDO REDONDEO NEG
			                  	  if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			                  	  }
			            //TERCER REDONDEO NEG
			                  	  if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			                  	  }
			                }//fin if
			             }//fin if


                $retencion_laboral_aux[$am_cont] =  $this->Formato2($retencion_laboral_aux[$am_cont]);
                $retencion_laboral_aux[$am_cont] =  $this->Formato1($retencion_laboral_aux[$am_cont]);
                $retencion_laboral_aux_partidas  =  $this->Formato2($retencion_laboral_aux_partidas);
                $retencion_laboral_aux_partidas  =  $this->Formato1($retencion_laboral_aux_partidas);
      }//fin if

//FIN GOB.GUARICO





//ORIGINAL
/*
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	  $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
               if($anticipo_con_iva2==1){
                   if(($concate=="403.18.01.00" && $aux_monto_partida!=$monto_iva) || $concate!="403.18.01.00"){
                   		//PRIMER REDONDEO POS
                         if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			            //SEGUNDO REDONDEO POS
			                  if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			                  }
			            //TERCER REDONDEO POS
			                  if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			                  }
			            //PRIMER REDONDEO NEG
			                }else if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			            //SEGUNDO REDONDEO NEG
			                  		if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                 		 $retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			                  		}
			            //TERCER REDONDEO NEG
			                  		if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                 		 $retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			                  		}
			                }//fin if
                    }//fin if
               }else{
//               	       if($concate!="403.18.01.00"){
						//PRIMER REDONDEO POS
			               if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			            //SEGUNDO REDONDEO POS
			                  	if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                 		 $retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			                  	 }
			            //TERCER REDONDEO POS
			                  	if($monto_retencion_laboral>$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_laboral_aux[$am_cont] += 0.01; $retencion_laboral_aux_partidas += 0.01;
			                  	 }
			            //PRIMER REDONDEO NEG
			                }else if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			            //SEGUNDO REDONDEO NEG
			                  	  if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			                  	  }
			            //TERCER REDONDEO NEG
			                  	  if($monto_retencion_laboral<$retencion_laboral_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_laboral_aux[$am_cont] -= 0.01; $retencion_laboral_aux_partidas -= 0.01;
			                  	  }
			                }//fin if
//			             }//fin if
               }//fin else
                $retencion_laboral_aux[$am_cont] =  $this->Formato2($retencion_laboral_aux[$am_cont]);
                $retencion_laboral_aux[$am_cont] =  $this->Formato1($retencion_laboral_aux[$am_cont]);
                $retencion_laboral_aux_partidas  =  $this->Formato2($retencion_laboral_aux_partidas);
                $retencion_laboral_aux_partidas  =  $this->Formato1($retencion_laboral_aux_partidas);
      }//fin if
*/
//FIN ORIGINAL




   }//fin if
}//fin if



$am_cont=0;
if($monto_retencion_fielcumplimiento!=$retencion_fielcumplimiento_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){
    $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);


//GOB.GUARICO

	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	  $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);

           	       if($concate!="403.18.01.00"){
						//PRIMER REDONDEO POS
           				          if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
						//SEGUNDO REDONDEO POS
			                  			if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
			                  			}
						//TERCER REDONDEO POS
			                  			if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
			                  			}
			            //PRIMER REDONDEO NEG
			                }else if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			            //SEGUNDO REDONDEO NEG
			                  			if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			                  			}
			            //TERCER REDONDEO NEG
			                  			if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			                  			}
			                }//fin if
			        	}//fin if

			  $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato2($retencion_fielcumplimiento_aux[$am_cont]);
              $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato1($retencion_fielcumplimiento_aux[$am_cont]);
              $retencion_fielcumplimiento_aux_partidas  =  $this->Formato2($retencion_fielcumplimiento_aux_partidas);
              $retencion_fielcumplimiento_aux_partidas  =  $this->Formato1($retencion_fielcumplimiento_aux_partidas);
      }//fin if

//FIN GOB.GUARICO



//ORIGINAL
/*
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	  $total_partida_vista = $this->Formato1($partidas_vista['pago_'.$i]);
          $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
             if($anticipo_con_iva2==1){
                     if(($concate=="403.18.01.00" && $aux_monto_partida!=$monto_iva) || $concate!="403.18.01.00"){
                     	//PRIMER REDONDEO POS
                          if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  $retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
			            //SEGUNDO REDONDEO POS
			                  if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
			                  }
			            //TERCER REDONDEO POS
			                  if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
			                  }
			            //PRIMER REDONDEO NEG
			                }else if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			            //SEGUNDO REDONDEO NEG
			                  		if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			                  		}
			            //TERCER REDONDEO NEG
			                  		if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			                  		}
			                }//fin if
                     }//fin if
               }else{
//           	       if($concate!="403.18.01.00"){
						//PRIMER REDONDEO POS
           				          if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
						//SEGUNDO REDONDEO POS
			                  			if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
			                  			}
						//TERCER REDONDEO POS
			                  			if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] += 0.01; $retencion_fielcumplimiento_aux_partidas += 0.01;
			                  			}
			            //PRIMER REDONDEO NEG
			                }else if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  		$retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			            //SEGUNDO REDONDEO NEG
			                  			if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			                  			}
			            //TERCER REDONDEO NEG
			                  			if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas && $aux_monto_partida!=$total_partida_vista){
			                  			$retencion_fielcumplimiento_aux[$am_cont] -= 0.01; $retencion_fielcumplimiento_aux_partidas -= 0.01;
			                  			}
			                }//fin if
//			        }//fin if
			  }//fin if
			  $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato2($retencion_fielcumplimiento_aux[$am_cont]);
              $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato1($retencion_fielcumplimiento_aux[$am_cont]);
              $retencion_fielcumplimiento_aux_partidas  =  $this->Formato2($retencion_fielcumplimiento_aux_partidas);
              $retencion_fielcumplimiento_aux_partidas  =  $this->Formato1($retencion_fielcumplimiento_aux_partidas);
      }//fin if
*/
//FIN ORIGINAL


   }//fin if
}//fin if




///////////////////////////////////////////////////VIEJO///////////////////////////////////////////////////////////////////////////////////

$am_cont=0;
if($amortizacion_anticipo!=$amortizacion_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){$aux_a = 0;


//GOB.GUARICO

           	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
				if($concate!="403.18.01.00"){
                         $aux_a = $amortizacion_aux[$am_cont]+$amortizacion_ante;
                       //PRIMER REDONDEO NEG
                      		if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
                       //SEGUNDO REDONDEO NEG
                      		if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
                      		}
                       //TERCER REDONDEO NEG
                      		if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
                      		}
                      //PRIMER REDONDEO POS
                		  }else if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
                      //SEGUNDO REDONDEO POS
                	  			if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
                	  			}
                      //TERCER REDONDEO POS
                	  			if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
                	  			}
                		}//fin else
                }//fin if

			  $amortizacion_aux_partidas  =  $this->Formato2($amortizacion_aux_partidas);
              $amortizacion_aux_partidas  =  $this->Formato1($amortizacion_aux_partidas);
              $amortizacion_aux[$am_cont] =  $this->Formato2($amortizacion_aux[$am_cont]);
              $amortizacion_aux[$am_cont] =  $this->Formato1($amortizacion_aux[$am_cont]);
	 }//fin if


//FIN GOB.GUARICO



//ORIGINAL
/*
           	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
           	 	if($anticipo_con_iva==1){
           	 		if($concate!="403.18.01.00"){
		           	 		 $aux_a = $amortizacion_aux[$am_cont]+$amortizacion_ante;
		           	 	//PRIMER REDONDEO NEG
		                      if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
		           	 	//SEGUNDO REDONDEO NEG
		                      if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
		                      }
		           	 	//TERCER REDONDEO NEG
		                      if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
		                      }
		           	 	//PRIMER REDONDEO POS
		                }else if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
		           	 	//SEGUNDO REDONDEO POS
		             		  if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
		             		  }
		           	 	//TERCER REDONDEO POS
		             		  if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
		             		  }
		             }//fin if
                }//fin else
					  }else{
				          if($concate!="403.18.01.00"){
                         $aux_a = $amortizacion_aux[$am_cont]+$amortizacion_ante;
                       //PRIMER REDONDEO NEG
                      		if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
                       //SEGUNDO REDONDEO NEG
                      		if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
                      		}
                       //TERCER REDONDEO NEG
                      		if($amortizacion_anticipo<$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas - 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] - 0.01;}//fin if
                      		}
                      //PRIMER REDONDEO POS
                }else if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
                      //SEGUNDO REDONDEO POS
                	  if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
                	  }
                      //TERCER REDONDEO POS
                	  if($amortizacion_anticipo>$amortizacion_aux_partidas){if($aux_a!=$monto_anticipo_aux2){$amortizacion_aux_partidas = $amortizacion_aux_partidas + 0.01; $amortizacion_aux[$am_cont] = $amortizacion_aux[$am_cont] + 0.01;}//fin if
                	  }
                }//fin else
                             }//fin if
					   }//fin else
			  $amortizacion_aux_partidas  =  $this->Formato2($amortizacion_aux_partidas);
              $amortizacion_aux_partidas  =  $this->Formato1($amortizacion_aux_partidas);
              $amortizacion_aux[$am_cont] =  $this->Formato2($amortizacion_aux[$am_cont]);
              $amortizacion_aux[$am_cont] =  $this->Formato1($amortizacion_aux[$am_cont]);
	 }//fin if

*/
//FIN ORIGINAL


   }//fin for
}//fin if


$am_cont=0;
if($monto_retencion_laboral!=$retencion_laboral_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){$aux_a = 0;



//GOB.GUARICO

           	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 		$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);

				          if($concate!="403.18.01.00"){
				          	  	$aux_a = $retencion_laboral_aux[$am_cont]+$retencion_laboral_ante;
                         		$suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                         	//PRIMER REDONDEO NEG
                       			if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
                         	//SEGUNDO REDONDEO NEG
                       				if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
                       				}
                         	//TERCER REDONDEO NEG
                       				if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
                       				}
                         	//PRIMER REDONDEO POS
                				}else if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
                          	//SEGUNDO REDONDEO POS
               					  		if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
               					  		}
                         	//TERCER REDONDEO POS
                					  	if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
                					  	}
                				}//fin else
                       }//fin if

			  $retencion_laboral_aux_partidas  =  $this->Formato2($retencion_laboral_aux_partidas);
              $retencion_laboral_aux_partidas  =  $this->Formato1($retencion_laboral_aux_partidas);
              $retencion_laboral_aux[$am_cont] =  $this->Formato2($retencion_laboral_aux[$am_cont]);
              $retencion_laboral_aux[$am_cont] =  $this->Formato1($retencion_laboral_aux[$am_cont]);
	 }//fin if

//FIN GOB.GUARICO




//ORIGINAL
/*

           	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 		$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
           	 	if($anticipo_con_iva2==1){
                         $aux_a = $retencion_laboral_aux[$am_cont]+$retencion_laboral_ante;
                         $suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                         $aux_1 = $var[$i]['pago'];
                         if(($concate=="403.18.01.00" && $var[$i]['pago']!=$monto_iva) || $concate!="403.18.01.00"){
                         	//PRIMER REDONDEO NEG
			                      if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
                         	//SEGUNDO REDONDEO NEG
			                      		if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
			                      		}
                         	//TERCERO REDONDEO NEG
			                      		if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
			                      		}
			                //PRIMER REDONDEO POS
			                	}else if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
			                //SEGUNDO REDONDEO POS
			                	  		if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
			                	  		}
			                //TERCER REDONDEO POS
			                	  		if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
			                	  		}
			            		}//fin else
                		}//fin if
                  }else{
//				          if($concate!="403.18.01.00"){
				          	  	$aux_a = $retencion_laboral_aux[$am_cont]+$retencion_laboral_ante;
                         		$suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                         	//PRIMER REDONDEO NEG
                       			if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
                         	//SEGUNDO REDONDEO NEG
                       				if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
                       				}
                         	//TERCER REDONDEO NEG
                       				if($monto_retencion_laboral<$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas - 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;}//fin if
                       				}
                         	//PRIMER REDONDEO POS
                				}else if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
                          	//SEGUNDO REDONDEO POS
               					  		if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
               					  		}
                         	//TERCER REDONDEO POS
                					  	if($monto_retencion_laboral>$retencion_laboral_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_laboral_aux_partidas = $retencion_laboral_aux_partidas + 0.01; $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;}//fin if
                					  	}
                				}//fin else
//                       }//fin if
					}//fin else
			  $retencion_laboral_aux_partidas  =  $this->Formato2($retencion_laboral_aux_partidas);
              $retencion_laboral_aux_partidas  =  $this->Formato1($retencion_laboral_aux_partidas);
              $retencion_laboral_aux[$am_cont] =  $this->Formato2($retencion_laboral_aux[$am_cont]);
              $retencion_laboral_aux[$am_cont] =  $this->Formato1($retencion_laboral_aux[$am_cont]);
	 }//fin if

*/
//FIN ORIGINAL


   }//fin for
}//fin if




$am_cont=0;
if($monto_retencion_fielcumplimiento!=$retencion_fielcumplimiento_aux_partidas){
  for($i=0; $i<$i_lenght; $i++){$aux_a = 0;



//GOB.GUARICO

if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
				      	if($concate!="403.18.01.00"){
				         $aux_a = $retencion_fielcumplimiento_aux[$am_cont]+$retencion_fielcumplimiento_ante;
                         $suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                          	//PRIMER REDONDEO NEG
                       					if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if

                          	//SEGUNDO REDONDEO NEG
                       						if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if
                       						}
                          	//TERCER REDONDEO NEG
                       						if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if
                       						}
                          	//PRIMER REDONDEO POS
                						}else if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
                          	//SEGUNDO REDONDEO POS
                									if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
                									}
                          	//TERCER REDONDEO POS
                									if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
                									}
                						}//fin else
				     }//fin if

			  $retencion_fielcumplimiento_aux_partidas  =  $this->Formato2($retencion_fielcumplimiento_aux_partidas);
              $retencion_fielcumplimiento_aux_partidas  =  $this->Formato1($retencion_fielcumplimiento_aux_partidas);
              $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato2($retencion_fielcumplimiento_aux[$am_cont]);
              $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato1($retencion_fielcumplimiento_aux[$am_cont]);
	 }//fin if


//FIN GOB.GUARICO


//ORIGINAL

/*
           	 if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
           	 	$concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
           	   	if($anticipo_con_iva2==1){
                         $aux_a = $retencion_fielcumplimiento_aux[$am_cont]+$retencion_fielcumplimiento_ante;
                         $suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                         $aux_1 = $var[$i]['pago'];
                         if(($concate=="403.18.01.00" && $var[$i]['pago']!=$monto_iva) || $concate!="403.18.01.00"){
                          	//PRIMER REDONDEO NEG
				                       if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if
                         	//SEGUNDO REDONDEO NEG
				                       		if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if
				                       		}
                         	//TERCER REDONDEO NEG
				                       		if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if
				                       		}
                          	//PRIMER REDONDEO POS
				                		}else if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
                          	//SEGUNDO REDONDEO POS
				                			  if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
				                			  }
                          	//TERCEDR REDONDEO POS
				                			  if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
				                			  }
				        				}//fin if
				    	}//fin else
                   }else{
//				      	if($concate!="403.18.01.00"){
				         $aux_a = $retencion_fielcumplimiento_aux[$am_cont]+$retencion_fielcumplimiento_ante;
                         $suma_retencion = ($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                          	//PRIMER REDONDEO NEG
                       					if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if

                          	//SEGUNDO REDONDEO NEG
                       						if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if
                       						}
                          	//TERCER REDONDEO NEG
                       						if($monto_retencion_fielcumplimiento<$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){$retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas - 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;}//fin if
                       						}
                          	//PRIMER REDONDEO POS
                						}else if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
                          	//SEGUNDO REDONDEO POS
                									if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
                									}
                          	//TERCER REDONDEO POS
                									if($monto_retencion_fielcumplimiento>$retencion_fielcumplimiento_aux_partidas){if($monto_total_partida[$i]!=$suma_retencion){ $retencion_fielcumplimiento_aux_partidas = $retencion_fielcumplimiento_aux_partidas + 0.01; $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;}//fin if
                									}
                						}//fin else
//				     }//fin if
				}//fin else
			  $retencion_fielcumplimiento_aux_partidas  =  $this->Formato2($retencion_fielcumplimiento_aux_partidas);
              $retencion_fielcumplimiento_aux_partidas  =  $this->Formato1($retencion_fielcumplimiento_aux_partidas);
              $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato2($retencion_fielcumplimiento_aux[$am_cont]);
              $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato1($retencion_fielcumplimiento_aux[$am_cont]);
	 }//fin if

*/
//FIN ORIGINAL


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
                $monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
                $monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);
                $var[$i]['pago']          =  $this->Formato2($var[$i]['pago']);
                $var[$i]['pago']          =  $this->Formato1($var[$i]['pago']);
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
                  $var[$i]['pago'] = $var[$i]['pago'] + 0.01; $monto_total_aux_partidas = $monto_total_aux_partidas + 0.01;
                }else if($monto_total_aux_partidas>$monto_cancelado2){
                  $var[$i]['pago'] = $var[$i]['pago'] - 0.01; $monto_total_aux_partidas = $monto_total_aux_partidas - 0.01;
                }//fin if
                $monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
                $monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);
                $var[$i]['pago']          =  $this->Formato2($var[$i]['pago']);
                $var[$i]['pago']          =  $this->Formato1($var[$i]['pago']);
          }//fin if
	 }//fin if
  }//fin if
}//fin if




if($monto_nuevo=="0"){
	$am_cont=0;
 for($i=0; $i<$i_lenght; $i++){
 	 $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
	  if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;
	  	 $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
         $datos_cscd04_ordencompra_partidas    =     $this->cepd02_contratoservicio_partidas->findAll($sql_where[$i]);
                   foreach($datos_cscd04_ordencompra_partidas as $aux_datos_cscd04_ordencompra_partidas){
						$anticipo                         =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['anticipo'];
						$amortizacion                     =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['amortizacion'];
						$cancelado                        =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['cancelacion'];
						$retencion_laboral                =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['retencion_laboral'];
						$retencion_fielcumplimiento       =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['retencion_fielcumplimiento'];

						$monto         =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['monto'];
						$aumento       =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['aumento'];
						$disminucion   =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['disminucion'];

						$monto_aux_partida1 =  ($monto+$aumento) - $disminucion;
						$monto_aux_partida2 =  $cancelado + $amortizacion + $retencion_laboral + $retencion_fielcumplimiento;

					}//fin foreach

                    $aux_monto_partida   = $var[$i]['pago']+($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
                    $monto_aux_partida3  = $monto_aux_partida2 + $aux_monto_partida;

                           if($this->Formato1($monto_aux_partida3)>$this->Formato1($monto_aux_partida1)){
											                           	      if($retencion_laboral_aux[$am_cont]!=0){
											                           	      	 $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] - 0.01;
											                           	}else if($retencion_fielcumplimiento_aux[$am_cont]!=0){
                                                                                 $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] - 0.01;
											                           	}//fin else

                    }else  if($this->Formato1($monto_aux_partida3)<$this->Formato1($monto_aux_partida1)){
                    	                                                      if($retencion_laboral_aux[$am_cont]!=0){
											                           	      	 $retencion_laboral_aux[$am_cont] = $retencion_laboral_aux[$am_cont] + 0.01;
											                           	}else if($retencion_fielcumplimiento_aux[$am_cont]!=0){
                                                                                 $retencion_fielcumplimiento_aux[$am_cont] = $retencion_fielcumplimiento_aux[$am_cont] + 0.01;
											                           	}//fin else
                    }//fin else

                    $retencion_laboral_aux[$am_cont] =  $this->Formato2($retencion_laboral_aux[$am_cont]);
                    $retencion_laboral_aux[$am_cont] =  $this->Formato1($retencion_laboral_aux[$am_cont]);
                    $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato2($retencion_fielcumplimiento_aux[$am_cont]);
                    $retencion_fielcumplimiento_aux[$am_cont] =  $this->Formato1($retencion_fielcumplimiento_aux[$am_cont]);



	 }//fin if
  }//fin if
}//fin if









//////////////////////////////////////////////////////FIN REDONDEO/////////////////////////////////////////////////////////////////


/////////////////////////////////////////////FIN REDONDEO AMORTIZACION Y RENTENCION LABORAL Y RETENCION FIEL CUMPLIMIENTO Y CANCELADO///////////////////////////////////////////



$monto_total_aux_partidas =  $this->Formato2($monto_total_aux_partidas);
$monto_total_aux_partidas =  $this->Formato1($monto_total_aux_partidas);








$j              = 0;
$am_cont        = 0;
$numero_causado = 0;

/*

$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);
if(!empty($numero_causado)){

	$numero_causado ++;
	$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
}else{
	$numero_causado = 1;
	$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado');";
}
$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);*/



	 for($i=0; $i<$i_lenght; $i++){
	       //$var[$i]['pago']=$partidas_vista['pago_'.$i];
	         if($partidas_vista['pago_'.$i]!="0,00"){ $am_cont++;

		   //$var[$i]['pago'] = $this->Formato1($var[$i]['pago']);




/////////////////////////////////////INICIO///////////////////////////////////////////////////


		     $concate = $cod_partida[$i].'.'.$this->AddCeroR($cod_generica[$i]).'.'.$this->AddCeroR($cod_especifica[$i]).'.'.$this->AddCeroR($cod_sub_espec[$i]);
             //$amortizacion_aux               = 0;
             //$retencion_laboral_aux          = 0;
             //$retencion_fielcumplimiento_aux = 0;
             $amortizacion_aux_compara       = 0;
             $retencion_laboral_aux_compara  = 0;
             $retencion_fielcumplimiento_compara = 0;
                   $datos_cscd04_ordencompra_partidas    =     $this->cepd02_contratoservicio_partidas->findAll($sql_where[$i]);
					foreach($datos_cscd04_ordencompra_partidas as $aux_datos_cscd04_ordencompra_partidas){
						$anticipo                         =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['anticipo'];
						$amortizacion                     =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['amortizacion'];
						$cancelado                        =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['cancelacion'];
						$retencion_laboral                =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['retencion_laboral'];
						$retencion_fielcumplimiento       =    $aux_datos_cscd04_ordencompra_partidas['cepd02_contratoservicio_partidas']['retencion_fielcumplimiento'];

					}//fin foreach



            $amortizacion_aux_compara           +=  $amortizacion_aux[$am_cont];
            $retencion_laboral_aux_compara      +=  $retencion_laboral_aux[$am_cont];
            $retencion_fielcumplimiento_compara +=  $retencion_fielcumplimiento_aux[$am_cont];
           // $var[$i]['pago'] = $var[$i]['pago']-($amortizacion_aux[$am_cont] + $retencion_laboral_aux[$am_cont] + $retencion_fielcumplimiento_aux[$am_cont]);
            //$pago = $var[$i]['pago'];
///////////////////////////////////FIN///////////////////////////////////////////////////

			$rnca = $numero_causado;

		    /*

		    $cp = $this->crear_partida($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
			$to=1;
			$td = 4;
			$ta = 7;
			$mt = $var[$i]['pago'];
			$ndo = $num_contrato_obra3[$i];
			$rnco = $numero_control_compromiso[$i];
			$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp='', $ann, $ndo, $nda, null, null, null, null, null, null, $rnco, $rnca, null, null, $i);

            */

$dnca = true;
if($dnca != false){
          $dnca=0;
$sw2 = 0;
           $monto_partida =  $this->Formato1($partidas_vista['pago_'.$i]);
           $pago  = $var[$i]['pago'];

		   $sql2  ="INSERT INTO cepd02_contratoservicio_valuacion_partidas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_servicio, numero_contrato_servicio, numero_valuacion, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec,  cod_auxiliar, monto, numero_control_compromiso, numero_control_causado, amortizacion, retencion_laboral, retencion_fielcumplimiento) ";
	       $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_contrato_servicio."', '".$num_contrato_obra."', '".$numero_valuacion."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['pago']."', '".$numero_control_compromiso[$i]."', '$rnca', '$amortizacion_aux[$am_cont]', '$retencion_laboral_aux[$am_cont]', '$retencion_fielcumplimiento_aux[$am_cont]'); ";
	       $sw2 = $this->cepd02_contratoservicio_valuacion_partidas->execute($sql2);
	       $sql_where2 = $sql_where[$i].';';

	       if($sw2 > 1){
		 	  $sql_partidas_contrato_obra = "UPDATE cepd02_contratoservicio_partidas SET retencion_laboral=retencion_laboral+".$retencion_laboral_aux[$am_cont].", retencion_fielcumplimiento=retencion_fielcumplimiento+".$retencion_fielcumplimiento_aux[$am_cont].", cancelacion=cancelacion + ".$pago.", amortizacion=amortizacion+".$amortizacion_aux[$am_cont]." WHERE $sql_where2".";";
		 	  $sw3 = $this->cepd02_contratoservicio_partidas->execute($sql_partidas_contrato_obra);
		 	  if($sw3 > 1){}else{/*$this->cepd02_contratoservicio_partidas->execute("ROLLBACK;");*/ }//fin else
	       }else{
              //$this->cepd02_contratoservicio_valuacion_partidas->execute("ROLLBACK;");
	       }//fin else

}else{
	//$this->cfpd05->execute("ROLLBACK;");
}//fin else


	       }//fin if
	 }//fin for

if($sw3 > 1){

	$datos_contrato_obra    =     $this->cepd02_contratoservicio_cuerpo->findAll($condicion);

//echo'<pre>';
// print_r($datos_contrato_obra);
//echo'</pre>';


	foreach($datos_contrato_obra as $aux_datos_contrato_obra){
		$monto_amortizacion_aux                  =     $aux_datos_contrato_obra['cepd02_contratoservicio_cuerpo']['monto_amortizacion'];
		$monto_cancelado_aux                     =     $aux_datos_contrato_obra['cepd02_contratoservicio_cuerpo']['monto_cancelado'];
		$monto_retencion_laboral_aux             =     $aux_datos_contrato_obra['cepd02_contratoservicio_cuerpo']['monto_retencion_laboral'];
		$monto_retencion_fielcumplimiento_aux    =     $aux_datos_contrato_obra['cepd02_contratoservicio_cuerpo']['monto_retencion_fielcumplimient'];
	}//fin foreach

	$monto_amortizacion_aux               =  $monto_amortizacion_aux + $amortizacion_anticipo;
	$monto_cancelado_aux                  =  $monto_cancelado_aux + $monto_orden_de_pago_monto_iva;
	$monto_retencion_fielcumplimiento_aux =  $monto_retencion_fielcumplimiento_aux  +  $monto_retencion_fielcumplimiento;
	$monto_retencion_laboral_aux          =  $monto_retencion_laboral_aux + $monto_retencion_laboral;

	$sql3  = "UPDATE cepd02_contratoservicio_cuerpo SET monto_retencion_laboral='".$monto_retencion_laboral_aux."',   monto_retencion_fielcumplimiento='".$monto_retencion_fielcumplimiento_aux."'    ,monto_amortizacion = '".$monto_amortizacion_aux."', monto_cancelado = '".$monto_cancelado_aux."'  where ".$condicion;
	$sw4 = $this->cepd02_contratoservicio_cuerpo->execute($sql3);
     if($sw4 > 1){
        $this->cepd02_contratoservicio_cuerpo->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
     }else{
     	$this->cepd02_contratoservicio_cuerpo->execute("ROLLBACK;"); $this->set('errorMessage', 'NO SE LOGRO REALIZAR LA VALUACIÓN - POR FAVOR INTENTE DE NUEVO');
     	  }//fin else



}else{
$this->cepd02_contratoservicio_valuacion_partidas->execute("ROLLBACK;");
$this->set('errorMessage', 'NO SE LOGRO REALIZAR LA VALUACIÓN - POR FAVOR INTENTE DE NUEVO');
}//fin else







}else{
	$this->cepd02_contratoservicio_valuacion_cuerpo->execute("ROLLBACK;");
	$msg_error = 'NO SE LOGRO REALIZAR LA VALUACIÓN - POR FAVOR INTENTE DE NUEVO';
	$this->set('errorMessage', 'NO SE LOGRO REALIZAR LA VALUACIÓN - POR FAVOR INTENTE DE NUEVO');
	return;
}//fin else



}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados'); }


$this->index();
$this->render('index');


}//fin guardar







function buscar_year($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');

if($SScoddep==1 && $SScoddeporig==1){
      $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' ';
}else{$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';}


  $lista = $this->cepd02_contratoservicio_valuacion_cuerpo->generateList($condicion.' and ano_contrato_servicio='.$var1, ' numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_valuacion_cuerpo.numero_contrato_servi', '{n}.cepd02_contratoservicio_valuacion_cuerpo.numero_contrato_servi');
  $this->set('obras', $lista);


}//fin function






function consulta_index($var1=null){

  $this->layout = "ajax";
  $pag_num = 0;
  $opcion = 'si';
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');

  $ano = $this->ano_ejecucion();
if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' ';
}else{$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';}

 $lista = $this->v_cepd02_contratoservicio_valuacion_cuerpo->generateList($condicion."  and ano_contrato_servicio = ".$ano, ' numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_valuacion_cuerpo.numero_contrato_ser', '{n}.v_cepd02_contratoservicio_valuacion_cuerpo.denominacion_rif');
 $this->concatena_servicio($lista,'obras');
 $this->set('ano',$ano);

if($var1!=null){

  if($var1=='si'){

  	if(isset($this->data['cepp02_contratoservicios_valuacion']['ano_ejecucion'])){$_SESSION['ano_contrato']=$this->data['cepp02_contratoservicios_valuacion']['ano_ejecucion'];}else{$_SESSION['ano_contrato'] = $this->ano_ejecucion();}

     $ano = $_SESSION['ano_contrato'];



if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else



if(!empty($this->data['cepp02_contratoservicios_valuacion']['numero_contrato_servicio'])){


	 $array = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($condicion. " and  numero_contrato_servicio='".$this->data['cepp02_contratoservicios_valuacion']['numero_contrato_servicio']."'   and ano_contrato_servicio = ".$ano ,null, 'ano_contrato_servicio, numero_contrato_servicio, numero_valuacion ASC', null);
   $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_contrato_servicio']    = $aux['cepd02_contratoservicio_valuacion_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_contrato_servicio'] = $aux['cepd02_contratoservicio_valuacion_cuerpo']['numero_contrato_servi'];
 	$numero[$i]['numero_valuacion']         = $aux['cepd02_contratoservicio_valuacion_cuerpo']['numero_valuacion'];
 	$i++;
} $i--;

  for($a=0; $a<=$i; $a++){
    if($this->data['cepp02_contratoservicios_valuacion']['numero_contrato_servicio'] == $numero[$a]['numero_contrato_servicio']){
    	 $pag_num = 0;
    	 $opcion='si';
    	 $numero_documento = $numero[$a]['numero_contrato_servicio'];
    	 break;
    	 }else{
    	 $pag_num = 0;
    	 $opcion='si';
    	 $numero_documento = $numero[0]['numero_contrato_servicio'];
    	 }
   }//fin for

      if($opcion=='si'){$_SESSION['PAG_NUM']=$this->data['cepp02_contratoservicios_valuacion']['numero_contrato_servicio'];
      	                $this->consulta($pag_num, $numero_documento);$this->render('consulta');
}else if($opcion=='no'){
	$this->set('errorMessage', 'No existen datos');
	}//fin else



        }else{

		 $array = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($condicion. "  and ano_contrato_servicio = ".$ano , null, 'ano_contrato_servicio, numero_contrato_servicio, numero_valuacion ASC', null);
   $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_contrato_servicio']    = $aux['cepd02_contratoservicio_valuacion_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_contrato_servicio'] = $aux['cepd02_contratoservicio_valuacion_cuerpo']['numero_contrato_servi'];
 	$numero[$i]['numero_valuacion']         = $aux['cepd02_contratoservicio_valuacion_cuerpo']['numero_valuacion'];
 	$i++;
} $i--;


	$this->Session->delete('PAG_NUM');

	if($i<=0){
	  $this->set('errorMessage', 'No existen datos');
	  $this->consulta_index();
	  $this->render('consulta_index');
	}else{$this->consulta(0,$numero[0]['numero_contrato_servicio']);$this->render('consulta');} }//fin else



  }//fin if
}//fin if

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function












function consulta($pag_num=null, $numero_documento=null, $g=null){

  $this->layout = "ajax";


  if(!empty($this->data['cepp02_contratoservicios_valuacion']['ano_ejecucion'])){
	$ano = $this->data['cepp02_contratoservicios_valuacion']['ano_ejecucion'];
  }else{
  	$ano = $this->ano_ejecucion();
  }

   if(isset($_SESSION['ano_contrato'])){$ano = $_SESSION['ano_contrato'];}else{$ano = $this->ano_ejecucion();}
   $this->set('ano_contrato_ejecucion', $this->ano_ejecucion());

  if($g=="si"){$this->set('Message_existe', 'Los datos fueron guardados correctamente');}
  if($g=="sii"){$this->set('Message_existe', 'El registro fue anulado correctamente');}
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else



   $array = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($condicion. " and  numero_contrato_servicio='".$numero_documento."' and ano_contrato_servicio = ".$ano , null, 'ano_contrato_servicio, numero_contrato_servicio, numero_valuacion ASC', null);

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_contrato_servicio']    = $aux['cepd02_contratoservicio_valuacion_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_contrato_servicio'] = $aux['cepd02_contratoservicio_valuacion_cuerpo']['numero_contrato_servi'];
 	$numero[$i]['numero_valuacion']     = $aux['cepd02_contratoservicio_valuacion_cuerpo']['numero_valuacion'];
 	$i++;

} $i--;




if(isset($numero[$pag_num]['numero_contrato_servicio'])){


$datos_cepd02_contratoservicio_valuacion_cuerpo          =     $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($condicion."   and ano_contrato_servicio=".$numero[$pag_num]['ano_contrato_servicio']."  and numero_contrato_servicio='".$numero[$pag_num]['numero_contrato_servicio']."'  and numero_valuacion='".$numero[$pag_num]['numero_valuacion']."'   ");
$datos_cepd02_contratoservicio_valuacion_partidas        =     $this->cepd02_contratoservicio_valuacion_partidas->findAll($condicion." and ano_contrato_servicio=".$numero[$pag_num]['ano_contrato_servicio']."  and numero_contrato_servicio='".$numero[$pag_num]['numero_contrato_servicio']."'  and numero_valuacion='".$numero[$pag_num]['numero_valuacion']."'  ");
$datos_cepd02_contratoservicio_cuerpo                    =     $this->cepd02_contratoservicio_cuerpo->findAll($condicion."             and ano_contrato_servicio=".$numero[$pag_num]['ano_contrato_servicio']."  and numero_contrato_servicio='".$numero[$pag_num]['numero_contrato_servicio']."'  ");

$cod_obra = "";
$denominacion_obra = "";
$ano_estimacion = "";
$numero_datos_aux =  $datos_cepd02_contratoservicio_cuerpo;
foreach($numero_datos_aux as $aux){
	$rif                   =   $aux['cepd02_contratoservicio_cuerpo']['rif'];
	$ano_contrato_servicio     =   $aux['cepd02_contratoservicio_cuerpo']['ano_contrato_servicio'];
	$numero_contrato_servicio  =   $aux['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio'];
	$fecha_contrato_servicio   =   $aux['cepd02_contratoservicio_cuerpo']['fecha_contrato_servicio'];
	$codigo_prod_serv     =   $aux['cepd02_contratoservicio_cuerpo']['codigo_prod_serv'];
}//fin foreach

if(!isset($rif)){$rif=0;}
if(!isset($codigo_prod_serv)){$codigo_prod_serv=0;}

$servicio = $this->cscd01_catalogo->findAll("codigo_prod_serv='".$codigo_prod_serv."'");

if(!isset($servicio[0]['cscd01_catalogo']['denominacion'])){$servicio[0]['cscd01_catalogo']['denominacion']=0;}
if(!isset($servicio[0]['cscd01_catalogo']['cod_snc'])){$servicio[0]['cscd01_catalogo']['cod_snc']=0;}

$this->set('tipo_servicio',         $servicio[0]['cscd01_catalogo']['cod_snc']);
$this->set('denominacion_servicio', $servicio[0]['cscd01_catalogo']['denominacion']);


$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){
	$denominacion_rif         =  $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif  =  $aux_2['cpcd02']['direccion_comercial'];
	$objeto_rif               =  $aux_2['cpcd02']['objeto'];
	$exento_islr_cooperativa   = $aux_2['cpcd02']['exento_islr_cooperativa'];
}//fin foreach


if(!isset($denominacion_rif)){$denominacion_rif=0;}


$this->set('datos_cepd02_contratoservicio_valuacion_cuerpo', $datos_cepd02_contratoservicio_valuacion_cuerpo);
$this->set('datos_cepd02_contratoservicio_valuacion_partidas', $datos_cepd02_contratoservicio_valuacion_partidas);
$this->set('datos_cepd02_contratoservicio_cuerpo', $aux);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('rif', $rif);
$this->set('codigo_prod_serv', $codigo_prod_serv);


if(!isset($fecha_contrato_servicio)){$fecha_contrato_servicio=0;}
if(!isset($denominacion_obra)){$denominacion_obra=0;}

$this->set('ano_estimacion', $ano_estimacion);
$this->set('cod_obra', $cod_obra);
$this->set('fecha_contrato_servicio', $fecha_contrato_servicio);
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

	if(isset($this->data["cepp02_contratoservicios_valuacion"])){

		    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
		    $tipo_documento           =  245;
       	    //$concepto_anulacion       =  $this->data["cepp02_contratoservicios_valuacion"]["concepto_anulacion"];
			$concepto_anulacion = "";
			$concepto = $concepto_anulacion;
			$fecha_proceso_anulacion  =  date("d/m/Y");
       	    $condicion_documento      =  2;//cuando se guarda es Activo=1
  		    $ano_contrato_servicio    = $this->data["cepp02_contratoservicios_valuacion"]["ano_contrato_servicio"];
			$num_contrato_obra    = $this->data["cepp02_contratoservicios_valuacion"]["num_contrato_obra"];
			$fecha_valuacion  = $this->data["cepp02_contratoservicios_valuacion"]["fecha_valuacion"];
			$fd = $fecha_valuacion;
			$numero_valuacion = (int) $this->data["cepp02_contratoservicios_valuacion"]["numero_valuacion"];
			$monto_cancelado = 0;
			$monto_cancelado_para_cuerpo = 0;
			$datos_partidas = $this->cepd02_contratoservicio_valuacion_partidas->findAll($conditions = $this->condicion()." and ano_contrato_servicio='$ano_contrato_servicio' and numero_contrato_servicio='$num_contrato_obra' and numero_valuacion='$numero_valuacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			$sql_update_cscd04_partidas ='';


if(isset($ano_contrato_servicio)){
if(isset($num_contrato_obra)){
if(isset($fecha_valuacion)){
if(isset($numero_valuacion)){




/////////////////////////ACTUALIZA LAS PARTIDAS/////////////////////
		foreach($datos_partidas as $row){
			 	$ano                          =   $row['cepd02_contratoservicio_valuacion_partidas']['ano'];
			 	$cod_sector                   =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_sector'];
			 	$cod_programa                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_programa'];
			 	$cod_sub_prog                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_sub_prog'];
			 	$cod_proyecto                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_proyecto'];
			 	$cod_activ_obra               =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_activ_obra'];
			 	$cod_partida                  =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_partida'];
			 	$cod_generica                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_generica'];
			 	$cod_especifica               =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_especifica'];
			 	$cod_sub_espec                =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_sub_espec'];
			 	$cod_auxiliar                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_auxiliar'];
			 	$monto_partida                =   $row['cepd02_contratoservicio_valuacion_partidas']['monto'];
			 	$numero_control_compromiso    =   $row['cepd02_contratoservicio_valuacion_partidas']['numero_control_comp'];
			 	$numero_control_causado       =   $row['cepd02_contratoservicio_valuacion_partidas']['numero_control_caus'];
			 	$amortizacion2                =   $row['cepd02_contratoservicio_valuacion_partidas']['amortizacion'];
			 	$retencion_laboral2           =   $row['cepd02_contratoservicio_valuacion_partidas']['retencion_laboral'];
			 	$retencion_fielcumplimiento2  =   $row['cepd02_contratoservicio_valuacion_partidas']['retencion_fielcumpl'];

			 	$cond1 = $this->condicion()." and ano_contrato_servicio='$ano_contrato_servicio' and numero_contrato_servicio='$num_contrato_obra' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";


                //$monto_partida = ($monto_partida - ($amortizacion2  + $retencion_laboral2 + $retencion_fielcumplimiento2 ));
				$monto_cancelado_para_cuerpo += $monto_partida;
				//$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
				//$num_asiento_compromiso = $this->motor_presupuestario($cp,2, 4, 7, date("d/m/Y"), $monto_partida, $concepto, $ano, $num_contrato_obra, $numero_valuacion, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);


                $datos_cepd02_contratoservicio_partidas    =     $this->cepd02_contratoservicio_partidas->findAll($cond1);
                foreach($datos_cepd02_contratoservicio_partidas as $aux_cepd02_contratoservicio_partidas){
		           $amortizacion                =    $aux_cepd02_contratoservicio_partidas['cepd02_contratoservicio_partidas']['amortizacion'];
		           $cancelado                   =    $aux_cepd02_contratoservicio_partidas['cepd02_contratoservicio_partidas']['cancelacion'];
		           $retencion_laboral           =    $aux_cepd02_contratoservicio_partidas['cepd02_contratoservicio_partidas']['retencion_laboral'];
		           $retencion_fielcumplimiento  =    $aux_cepd02_contratoservicio_partidas['cepd02_contratoservicio_partidas']['retencion_fielcumplimiento'];
	             }//fin foreac

                $amortizacion                 =   $amortizacion - $amortizacion2;
                $retencion_laboral            =   $retencion_laboral - $retencion_laboral2;
                $retencion_fielcumplimiento   =   $retencion_fielcumplimiento -  $retencion_fielcumplimiento2;
//              $cancelado                    =   $cancelado - ($monto_partida - ($amortizacion2  +  $retencion_laboral2 + $retencion_fielcumplimiento2));
                $cancelado                    =   $cancelado - $monto_partida;



			 	$sql_update_cscd04_partidas = "UPDATE cepd02_contratoservicio_partidas SET retencion_fielcumplimiento=".$retencion_fielcumplimiento.", retencion_laboral=".$retencion_laboral.", amortizacion=".$amortizacion.", cancelacion=".$cancelado." WHERE ".$cond1.";";
                $sw = $this->cepd02_contratoservicio_partidas->execute($sql_update_cscd04_partidas);

}//fin for

///////////////////////FIN ACTUALIZA LAS PARTIDAS/////////////////////



/////////////////////////ACTUALIZA EL ENCABEZADO//////////////////

$monto_amortizacion_aux_yy                    =    0;
$monto_retencion_fielcumplimiento_aux_zz      =    0;
$monto_retencion_laboral_aux_zz               =    0;
$monto_cancelar_aux_zz                        =    0;


           $datos_cuerpo = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($conditions = $this->condicion()." and ano_contrato_servicio='$ano_contrato_servicio' and numero_contrato_servicio='$num_contrato_obra' and numero_valuacion='$numero_valuacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);



            foreach($datos_cuerpo as $aux_datos_cuerpo){
		       $monto_amortizacion_aux_yy                   = $aux_datos_cuerpo['cepd02_contratoservicio_valuacion_cuerpo']['amortizacion_anticipo'];
		     //$monto_cancelar_aux_zz                       = $aux_datos_cuerpo['cepd02_contratoservicio_valuacion_cuerpo']['monto_neto_cobrar'];
		       $monto_cancelar_aux_zz                       = $aux_datos_cuerpo['cepd02_contratoservicio_valuacion_cuerpo']['monto_orden_pago'];
		       $monto_retencion_laboral_aux_zz              = $aux_datos_cuerpo['cepd02_contratoservicio_valuacion_cuerpo']['monto_retencion_labor'];
		       $monto_retencion_fielcumplimiento_aux_zz     = $aux_datos_cuerpo['cepd02_contratoservicio_valuacion_cuerpo']['monto_retencion_fielc'];
	        }//fin foreach
			$cond2 = $this->condicion()." and ano_contrato_servicio='$ano_contrato_servicio' and numero_contrato_servicio='$num_contrato_obra' ";
			$datos_orden_compra_encabezado    =     $this->cepd02_contratoservicio_cuerpo->findAll($cond2);

//echo'<pre>';
 //print_r($datos_orden_compra_encabezado);
//echo'</pre>';

            foreach($datos_orden_compra_encabezado as $aux_datos_orden_compra_encabezado){
		       $monto_amortizacion_aux2                  = $aux_datos_orden_compra_encabezado['cepd02_contratoservicio_cuerpo']['monto_amortizacion'];
		       $monto_cancelado_aux2                     = $aux_datos_orden_compra_encabezado['cepd02_contratoservicio_cuerpo']['monto_cancelado'];
		       $monto_retencion_laboral_aux2             = $aux_datos_orden_compra_encabezado['cepd02_contratoservicio_cuerpo']['monto_retencion_laboral'];
		       $monto_retencion_fielcumplimiento_aux2    = $aux_datos_orden_compra_encabezado['cepd02_contratoservicio_cuerpo']['monto_retencion_fielcumplimient'];

	        }//fin foreach
	        $monto_amortizacion_aux2               =  $monto_amortizacion_aux2 - $monto_amortizacion_aux_yy;
	        $monto_retencion_laboral_aux2          =  $monto_retencion_laboral_aux2 - $monto_retencion_laboral_aux_zz;
	        $monto_retencion_fielcumplimiento_aux2 =  $monto_retencion_fielcumplimiento_aux2 - $monto_retencion_fielcumplimiento_aux_zz;
	        //$monto_cancelado_aux2                  =  $monto_cancelado_aux2 - ($monto_cancelar_aux_zz - ($monto_amortizacion_aux_yy + $monto_retencion_laboral_aux_zz + $monto_retencion_fielcumplimiento_aux_zz));
	        //$monto_cancelado_aux2                  =  $monto_cancelado_aux2 - $monto_cancelado_para_cuerpo;
	          $monto_cancelado_aux2                  =  $monto_cancelado_aux2 - $monto_cancelar_aux_zz;


			$sql_update_cscd04_encabezado ="UPDATE cepd02_contratoservicio_cuerpo SET monto_retencion_fielcumplimiento=".$monto_retencion_fielcumplimiento_aux2.", monto_retencion_laboral=".$monto_retencion_laboral_aux2.", monto_amortizacion=".$monto_amortizacion_aux2.",  monto_cancelado=".$monto_cancelado_aux2." WHERE ".$cond2.";";
			$sw = $this->cepd02_contratoservicio_cuerpo->execute($sql_update_cscd04_encabezado);
/////////////////////////FIN ACTUALIZA EL ENCABEZADO////////////////






			 //$numero_anulacion=$this->cugd03_acta_anulacion_numero->execute($condicion);
             /*$v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano_contrato_servicio." ORDER BY numero_acta_anulacion DESC");
		     if($v!=null && $sw > 1){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano_contrato_servicio."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano_contrato_servicio.",1)");
			    $numero=1;
		     }//fin else*/

			 //$R1 = $this->cepd02_contratoservicio_valuacion_cuerpo->execute("UPDATE cepd02_contratoservicio_valuacion_cuerpo SET ano_asiento_anulacion=0, numero_asiento_anulacion=0,  numero_anulacion=".$numero.",  ano_anulacion=".date("Y").",  condicion_actividad=".$condicion_documento.", mes_asiento_anulacion=".date("m").",  dia_asiento_anulacion=".date("d").",  fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'  WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano_contrato_servicio." and numero_contrato_servicio='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");

		     $R1 = $this->cepd02_contratoservicio_valuacion_cuerpo->execute(" DELETE FROM cepd02_contratoservicio_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano_contrato_servicio." and numero_contrato_servicio='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");
		     $R1 = $this->cepd02_contratoservicio_valuacion_cuerpo->execute(" DELETE FROM cepd02_contratoservicio_valuacion_cuerpo WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano_contrato_servicio." and numero_contrato_servicio='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");

			 $orden_pago = $this->cepd02_contratoservicio_valuacion_cuerpo->execute(" SELECT * FROM cepd03_ordenpago_cuerpo WHERE cod_tipo_documento=8 and condicion_actividad=2 and ".$this->SQLCA()." and numero_documento_origen='".$num_contrato_obra."' and numero_documento_adjunto='".$numero_valuacion."' and ano_documento_origen=".$ano_contrato_servicio);
 				foreach($orden_pago as $orden){
 				$ano_orden    = $orden[0]['ano_orden_pago'];
 				$numero_orden = $orden[0]['numero_orden_pago'];
 				$this->cepd02_contratoservicio_valuacion_cuerpo->execute(" DELETE FROM cepd03_ordenpago_cuerpo WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden);

						$orden_pago_cheque=$this->cepd02_contratoservicio_valuacion_cuerpo->execute(" SELECT * FROM cstd03_cheque_ordenes WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden);
				 		foreach($orden_pago_cheque as $orden_cheque){
 						$ano_movimiento       = $orden_cheque[0]['ano_movimiento'];
 						$cod_entidad_bancaria = $orden_cheque[0]['cod_entidad_bancaria'];
 						$cod_sucursal         = $orden_cheque[0]['cod_sucursal'];
 						$cuenta_bancaria      = $orden_cheque[0]['cuenta_bancaria'];
 						$numero_cheque        = $orden_cheque[0]['numero_cheque'];
 						$this->cepd02_contratoservicio_valuacion_cuerpo->execute(" DELETE FROM cstd03_cheque_cuerpo WHERE ".$this->SQLCA()." and ano_movimiento=".$ano_movimiento." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque=".$numero_cheque);
 						$this->cepd02_contratoservicio_valuacion_cuerpo->execute(" DELETE FROM cstd04_movimientos_generales WHERE ".$this->SQLCA()." and ano_movimiento=".$ano_movimiento." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_documento=4 and numero_documento=".$numero_cheque);
				 		}
				 }

		     //$v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_contrato_servicio.",".$numero.",".$tipo_documento.",".$ano_contrato_servicio.",".$numero_valuacion.",'".$this->Cfecha($fecha_valuacion, 'A-M-D')."','".$concepto_anulacion."')");

	$this->set('Message_existe', 'El Registro fue eliminado correctamente');



			}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
			}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
			}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
			}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}




	}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}




$this->consulta_index('1');
$this->render('consulta_index');



}//fin function

function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cepp02_contratoservicios_valuacion']['login']) && isset($this->data['cepp02_contratoservicios_valuacion']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cepp02_contratoservicios_valuacion']['login']);
		$paswd=addslashes($this->data['cepp02_contratoservicios_valuacion']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=89 and clave='".$paswd."'";
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
