<?php
class Cobp01registroanticipocontratoobrasUsoGeneralController extends AppController {

   var $name = "cobp01_registro_anticipo_contratoobras_uso_general";
   var $uses = array('cscd04_ordencompra_parametros','cfpd07_obras_cuerpo', 'cfpd22_numero_asiento_causado','ccfd04_cierre_mes',
                     'cobd01_contratoobras_anticipo_cuerpo','cobd01_contratoobras_partidas','cobd01_contratoobras_cuerpo',
                     'cscd04_ordencompra_parametros', 'cscd04_ordencompra_encabezado', 'cugd03_acta_anulacion_numero',
                     'cugd03_acta_anulacion_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo', 'cscd04_ordencompra_anticipo_partidas',
                     'ccfd03_instalacion', 'cscd04_ordencompra_partidas', 'cpcd02','cfpd05','cugd04','cfpd22','cobd01_contratoobras_anticipo_partidas',

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






function filtra_obra($year=null){



$cod_dep                  =       $this->Session->read('SScoddep');
$SScoddeporig             =       $this->Session->read('SScoddeporig');
$Modulo                   =       $this->Session->read('Modulo');



$sql_obra = "";

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$cod_dep.'  ';

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
 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');
 $lista = "";
 $this->data =null;

 $ano='';
 $ano=$this->ano_ejecucion();
 $this->set('ano',$ano);




$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ((monto_original_contrato+aumento)-(disminucion+monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0 and ano_contrato_obra='.$ano.' and condicion_actividad=1';
$a = $this->cobd01_contratoobras_cuerpo->findAll($condicion, null,' numero_contrato_obra ASC');
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.'  and ano_estimacion='.$ano;
$b = $this->cfpd07_obras_cuerpo->findAll($condicion);
foreach($a as $a_aux){
   foreach($b as $b_aux){
      if($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion']==$b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] &&  strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra'])==strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])){
        $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']]=$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
     }//fin if
  }//fin foreach
}//fin foreach



$this->set('lista_numero', $lista);
$this->Session->delete("ano_contrato_obra");


}//fin function





function incluye_iva($codigo=null, $var1=null){
	//echo " codigo ".$codigo." var1 ".$var1;

    $this->layout = "ajax";//echo "incluye".$codigo.$var1;
    $porcentaje_anticipo = 0;
    $factor_reversion = "";
    $porcentaje_iva = "0";

	if($var1 != null){
		$this->Session->write('incluye_iva', $var1);
	}

    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
    $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
    foreach($parametros_datos as $aux_22){
      $porcentaje_anticipo = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
      $porcentaje_iva    = $aux_22['cscd04_ordencompra_parametros']['porcentaje_iva'];
     }//fin foreach

if($porcentaje_iva==0){

    echo'<script>';
     echo'monto_anticipo_uso_general("'.$var1.'", "0", "0");';
    echo'</script>';

}else{

	echo'<script>';
     echo'monto_anticipo_uso_general("'.$var1.'", "'.$this->Formato_redondear($porcentaje_anticipo).'", "'.$this->Formato_redondear($porcentaje_iva).'");';
    echo'</script>';


}//fin else

}//fin function


function datos($cod=null, $var=null) {
	$this->layout = "ajax";
	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
	$numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion." and numero_contrato_obra='$cod'", null, 'numero_contrato_obra DESC');
	//echo "si esta aqui";
	//print_r($numero_datos_partidas);
	$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
	$this->incluye_iva($cod, $var);

}//fin datos




function selecion($var1=null){
  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


$para = $this->cscd04_ordencompra_parametros->findAll();
 foreach($para as $param)
 {
 	$ivap = $param['cscd04_ordencompra_parametros']['porcentaje_iva'];
 	$pora = $param['cscd04_ordencompra_parametros']['porcentaje_anticipo'];

 }
//echo "el iva es ".$ivap;
$this->set('ivap',$ivap);
$this->set('pora',$pora);

 $ano='';
 $this->set('codigo', $var1);

 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');
 $lista = "";

 $ano='';
 $ano=$this->ano_ejecucion();
 $this->set('ano',$ano);


$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ((monto_original_contrato+aumento)-(disminucion+monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0 and ano_contrato_obra='.$ano.' and condicion_actividad=1';
$a = $this->cobd01_contratoobras_cuerpo->findAll($condicion, null,' numero_contrato_obra ASC');
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.'  and ano_estimacion='.$ano;
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


if($var1 != null){
 	$incluye_iva = $this->cscd04_ordencompra_parametros->field('cscd04_ordencompra_parametros.anticipo_incluye_iva', $conditions = $this->SQLCA(), $order =null);
 	$por_anticipo = $this->cscd04_ordencompra_parametros->field('cscd04_ordencompra_parametros.porcentaje_anticipo', $conditions = $this->SQLCA(), $order =null);
 	if(empty($por_anticipo)){
 		$por_anticipo = 0;
 	}
 	$this->set('por_anticipo',  $por_anticipo);
 	//echo "seleccion ".$var1.$incluye_iva;
 	if(!empty($incluye_iva)){
 		$this->incluye_iva($var1, $incluye_iva);
 	}
}//fin if


if($var1==null){

$this->index();
$this->render('index');

}else{



//////***********       LOS DEL PORCENTAJE         *****************//////////

$porcentaje_anticipo = 0;
$factor_reversion = "";
$anticipo_incluye_iva = "";
$porcentaje_iva = 0;


 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
 $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
    foreach($parametros_datos as $aux_22){
      $anticipo_incluye_iva = $aux_22['cscd04_ordencompra_parametros']['anticipo_incluye_iva'];
      $porcentaje_anticipo = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
      $porcentaje_iva    = $aux_22['cscd04_ordencompra_parametros']['porcentaje_iva'];
    }//fin foreach





//////***********       FIN LOS DEL PORCENTAJE         *****************//////////

$condicion = "cod_presi=".$this->Session->read("SScodpresi")."  and  cod_entidad=".$this->Session->read("SScodentidad")." and cod_tipo_inst=".$this->Session->read("SScodtipoinst")." and  cod_inst=".$this->Session->read("SScodinst")." and cod_dep=".$this->Session->read("SScoddep")." and  upper(numero_contrato_obra)='".strtoupper($var1)."' and  ano_contrato_obra=".$ano." ";
$numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
$numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion, null, 'numero_contrato_obra DESC');

$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cobd01_contratoobras_cuerpo']['rif'];
	$ano_contrato_obra = $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
	$numero_contrato_obra = $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
}//fin foreach

$opc     = $this->cobd01_contratoobras_anticipo_cuerpo->findCount($condicion." and ano_contrato_obra=".$ano_contrato_obra."  and upper(numero_contrato_obra)='".strtoupper($numero_contrato_obra)."'");
$result  = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($condicion."   and ano_contrato_obra=".$ano_contrato_obra."  and  upper(numero_contrato_obra)='".strtoupper($numero_contrato_obra)."' ", null, "numero_anticipo ASC", null, null);
foreach($result as $ves){$opc = $ves['cobd01_contratoobras_anticipo_cuerpo']['numero_anticipo'];}//fin foreach


$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");

foreach($rif_datos as $aux_2){
	$denominacion_rif = $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
}//fin foreach
$opc++;


$this->set('ano_contrato_obra_anticipo', $ano);
$this->set('numero_contrato_obra_anticipo', $opc);
$this->set('datos_orden_compra', $numero_datos);
$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
//print_r($numero_datos_partidas);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('direccion_comercial_rif', $direccion_comercial_rif);
$this->set('anticipo_incluye_iva', $anticipo_incluye_iva);


}//fin else

}//fin function







function AddCero2($numero=null,$extra=null){

   	  if($extra==null){
   	  	  if($numero<10){
        	   $numero="0".$numero;
        	}else{
	           $numero;
        	}
   	  }else{
        	if($numero<10){
        	   $numero=$extra.".".$numero;
        	}else{
	           $numero=$extra.".".$numero;
        	}

   	  }
	    return $numero;
   }//fin AddCero2



function guardar(){
  $this->layout="ajax";

  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $mensaje                  =       2;
  $ano_contrato_obra         =       $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['ano_contrato_obra'];
  $ann = $ano_contrato_obra;
  $numero_contrato_obra      =       $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['numero_contrato_obra'];
 // echo "el numero contrato es".$numero_contrato_obra;
  $numero_anticipo      =       $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['numero_anticipo'];
  $nda = $numero_anticipo;
  //$tipo_anticipo        =       $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['incluye_iva'];
  $tipo_anticipo        =  "2";
  $iva                  =       $this->Formato1($this->data['cobp01_registro_anticipo_contratoobras_uso_general']['porcentaje_iva']);
  $iva_anticipo         =       $this->Formato1($this->data['cobp01_registro_anticipo_contratoobras_uso_general']['porcentaje_anticipo']);
  $monto_anticipo       =       $this->Formato1($this->data['cobp01_registro_anticipo_contratoobras_uso_general']['monto_anticipo']);
  $fecha_anticipo       =       $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['fecha_anticipo'];




if(isset($this->data['cobp01_registro_anticipo_contratoobras_uso_general']['pregunta_ejercicio'])){

									  $pregunta_ejercicio       =       $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['pregunta_ejercicio'];

									  $fd = $fecha_anticipo;
									  $observaciones            =       $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['observaciones'];
									  $ccp = $observaciones;

									   $fecha_proceso_registro                 =   date("d/m/Y");
									   $fecha_contrato_obra2 = $fd;
									   $aux = $fecha_contrato_obra2[6].$fecha_contrato_obra2[7].$fecha_contrato_obra2[8].$fecha_contrato_obra2[9];
									if($aux!=$ano_contrato_obra){$fd = $fecha_proceso_registro;}


									  $dia_asiento_registro                   =   '0';
									  $mes_asiento_registro                   =   '0';
									  $ano_asiento_registro                   =   '0';
									  $numero_asiento_registro='0';
									  $username_registro=$_SESSION['nom_usuario'];
									  $condicion_actividad='1';
									  $ano_asiento_anulacion='0';
									  $mes_asiento_anulacion='0';
									  $dia_asiento_anulacion='0';
									  $numero_asiento_anulacion='0';
									  $username_anulacion='0';
									  $fecha_proceso_anulacion='01/01/1900';
									  $ano_orden_pago= "0";
									  $numero_orden_pago="0";
									$numero_orden_pago2 = $numero_contrato_obra;
									$ano_orden_pago2 = $ano_contrato_obra;
									$ano_anulacion='0';
									$ano_asiento_contable='0';
									$numero_anulacion='0';

									$sw1 = 0;
									$sw2 = 0;
									$sw3 = 0;
									$sw4 = 0;
									$sw5 = 0;

									$sql="   BEGIN;   INSERT INTO cobd01_contratoobras_anticipo_cuerpo (cod_presi,
									  cod_entidad,
									  cod_tipo_inst,
									  cod_inst,
									  cod_dep,
									  ano_contrato_obra,
									  numero_contrato_obra,
									  numero_anticipo,
									  monto_anticipo,
									  fecha_anticipo,
									  observaciones,
									  dia_asiento_registro,
									  mes_asiento_registro,
									  ano_asiento_registro,
									  numero_asiento_registro,
									  username_registro,
									  fecha_proceso_registro,
									  condicion_actividad,
									  ano_anulacion,
									  numero_anulacion,
									  dia_asiento_anulacion,
									  mes_asiento_anulacion,
									  ano_asiento_contable,
									  numero_asiento_anulacion,
									  fecha_proceso_anulacion,
									  username_anulacion,
									  ano_orden_pago,
									  numero_orden_pago, porcentaje_anticipo, saldo_ano_anterior)";
									$sql.="VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."', '".$ano_contrato_obra."', '".$numero_contrato_obra."', '".$numero_anticipo."',".$this->Formato1($monto_anticipo).", '".$this->Cfecha($fecha_anticipo, 'A-M-D')."', '".$observaciones."', '".$dia_asiento_registro."','".$mes_asiento_registro."', '".$ano_asiento_registro."', '".$numero_asiento_registro."','".$username_registro."', '".$this->Cfecha($fecha_proceso_registro, 'A-M-D')."', '".$condicion_actividad."', '".$ano_anulacion."','".$numero_anulacion."', '".$dia_asiento_anulacion."', '".$mes_asiento_anulacion."', ".$ano_asiento_contable.", '".$numero_asiento_anulacion."', '".$this->Cfecha($fecha_proceso_anulacion, 'A-M-D')."', '".$username_anulacion."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$iva_anticipo."', '".$pregunta_ejercicio."'); ";
									$sw1 =  $this->cscd04_ordencompra_anticipo_cuerpo->execute($sql);

									if($sw1>1){


									$i_lenght = $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['cuenta_i'];
									$condicion = "cod_presi=".$this->Session->read('SScodpresi')." and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$numero_contrato_obra."'  and  ano_contrato_obra=".$ano_contrato_obra."";
									if($tipo_anticipo==1){
										$condicion1 = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$numero_contrato_obra."'  and  ano_contrato_obra=".$ano_contrato_obra."";
									}else{
										$condicion1 = "cod_presi=".$this->Session->read('SScodpresi')." and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$numero_contrato_obra."'  and  ano_contrato_obra=".$ano_contrato_obra." and not(cod_partida=403 and cod_generica=18 and cod_especifica=1 and cod_sub_espec=0)";
									}
									//echo "la condicion es".$condicion;


									$numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion1, null, 'numero_contrato_obra DESC');
									$a=0;
									$i_aux = 0;
									foreach($numero_datos_partidas as $aux_partidas){

									  $cod_presi2[$a]                 =   $aux_partidas['cobd01_contratoobras_partidas']['cod_presi'];
									  $cod_entidad2[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_entidad'];
									  $cod_tipo_inst2[$a]             =   $aux_partidas['cobd01_contratoobras_partidas']['cod_tipo_inst'];
									  $cod_inst2[$a]                  =   $aux_partidas['cobd01_contratoobras_partidas']['cod_inst'];
									  $cod_dep2[$a]                   =   $aux_partidas['cobd01_contratoobras_partidas']['cod_dep'];
									  $ano_contrato_obra3[$a]          =   $aux_partidas['cobd01_contratoobras_partidas']['ano_contrato_obra'];
									  $numero_contrato_obra3[$a]       =   $aux_partidas['cobd01_contratoobras_partidas']['numero_contrato_obra'];
									  $ano_partidas[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['ano'];
									  $cod_sector[$a]                 =   $aux_partidas['cobd01_contratoobras_partidas']['cod_sector'];
									  $cod_programa[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_programa'];
									  $cod_sub_prog[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_prog'];
									  $cod_proyecto[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_proyecto'];
									  $cod_activ_obra[$a]             =   $aux_partidas['cobd01_contratoobras_partidas']['cod_activ_obra'];
									  $cod_partida[$a]                =   $aux_partidas['cobd01_contratoobras_partidas']['cod_partida'];
									  $cod_generica[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_generica'];
									  $cod_especifica[$a]             =   $aux_partidas['cobd01_contratoobras_partidas']['cod_especifica'];
									  $cod_sub_espec[$a]              =   $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_espec'];
									  $cod_auxiliar[$a]               =   $aux_partidas['cobd01_contratoobras_partidas']['cod_auxiliar'];
									  $monto2[$a]                     =   $aux_partidas['cobd01_contratoobras_partidas']['monto'];
									  $anticipo2[$a]                  =   $aux_partidas['cobd01_contratoobras_partidas']['anticipo'];
									  if($aux_partidas['cobd01_contratoobras_partidas']['numero_control_compromiso'] != null){
									  	$numero_control_compromiso[$a]  =   $aux_partidas['cobd01_contratoobras_partidas']['numero_control_compromiso'];
									  }else{
									  	$numero_control_compromiso[$a]  =  0;
									  }

									 $concate[$a] = $this->AddCero2(substr($cod_partida[$a], -2), substr($cod_partida[$a], 0, 1 )).'.'.$this->AddCero2($cod_generica[$a]).'.'.$this->AddCero2($cod_especifica[$a]).'.'.$this->AddCero2($cod_sub_espec[$a]);
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


									for($i=0; $i<=$i_lenght; $i++){
									   if($partidas_aux==$this->data['partidas']['partidas_'.$i]){$partidas_vista['pago_'.$i_aux] = $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['anticipo_'.$i]; $i_aux++;}
									}//fin foreach



									}//fin foreach


									$j =0;
									$numero_causado = 0;

/*

									$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);
								if(!empty($numero_causado)){
									$numero_causado ++;
									$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
								}else{
									$numero_causado = 1;
									$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado'); ";
								}
									$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);

*/

									for($i=0; $i<count($numero_datos_partidas); $i++){//echo "si entro". $cod_sector[$i];
									       $var[$i]['monto']=$partidas_vista['pago_'.$i] ;

									       //echo "el monto es".$var[$i]['monto'];

									if($var[$i]['monto']!="0,00"){
										$var[$i]['monto'] = $this->Formato1($var[$i]['monto']);
									             if(($var[$i]['monto']+$anticipo2[$i]) <= $monto2[$i] ){

															if($pregunta_ejercicio==2){
																	$cp = $this->crear_partida($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
																	$to=1;
																	$td = 4;
																	$ta = 4;
																	$mt = $var[$i]['monto'];
																	$ndo = $numero_contrato_obra3[$i];
																	//echo "si llego al motor";
																	$rnco = $numero_control_compromiso[$i];
																	$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $i);
															 }else{
															       $dnca = true;
													         }//fin else

															if($dnca != false){
															          $dnca=0;

															       $sql2  ="INSERT INTO cobd01_contratoobras_anticipo_partidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_obra, numero_contrato_obra, numero_anticipo, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica,  cod_sub_espec, cod_auxiliar,  monto, numero_control_compromiso, numero_control_causado) ";
															       $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_contrato_obra3[$i]."', '".$numero_contrato_obra3[$i]."', '".$numero_anticipo."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['monto']."', '".$numero_control_compromiso[$i]."', '$dnca'); ";
															       $sw2 = $this->cobd01_contratoobras_anticipo_partidas->execute($sql2);
															    //   echo $sql2;
																   $monto_anticipo_tipo2 = 0;
																	$monto_anticipo_tipo2 = $anticipo2[$i] + $var[$i]['monto'];
																	if($sw2 >1){
																		$sql4  = "UPDATE cobd01_contratoobras_partidas SET anticipo= '".$monto_anticipo_tipo2."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_contrato_obra='".$numero_contrato_obra."'  and  ano_contrato_obra=".$ano_orden_pago2." and ano=".$ano_partidas[$i]." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i]."; ";
																		$sw3 = $this->cobd01_contratoobras_partidas->execute($sql4);
																		if($sw3 > 1){}else{break;}//fin else
																	}else{break;}


															}else{
																break;
															}//fin else

									        }else{ $mensaje=1; $sw3=0; break;  }

									  }//fin if
									}//fin for


									if($sw3 > 1){


									//echo $sql4;
									$numero_datos_partidas22 = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
									foreach($numero_datos_partidas22 as $aux_partidas22){
									  $monto_anticipo_aux             =   $aux_partidas22['cobd01_contratoobras_cuerpo']['monto_anticipo'];
									}//fin for


									$monto_anticipo = $monto_anticipo + $monto_anticipo_aux;
									$tipo_anticipo = "1";
									$sql3  = "UPDATE cobd01_contratoobras_cuerpo SET monto_anticipo= '".$monto_anticipo."', porcentaje_iva='".$iva."', porcentaje_anticipo= porcentaje_anticipo + '".$iva_anticipo."', anticipo_con_iva='".$tipo_anticipo."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_contrato_obra='".$numero_contrato_obra."'  and  ano_contrato_obra=".$ano_orden_pago2."; ";
									$sw4 = $this->cobd01_contratoobras_cuerpo->execute($sql3);
									//echo $sql3;

									 if($sw4 > 1){
									        $this->cobd01_contratoobras_cuerpo->execute("COMMIT;");    $this->set('msg', 'LOS DATOS FUERON GUARDADOS');

									////************************************** DESPUES DE GUARDAR ****************************************/////
									 $ano='';
									 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
									//echo $condicion;
									 $ano = $this->ano_ejecucion();
									 $condicion = "cod_presi=".$this->Session->read("SScodpresi")." and  cod_entidad=".$this->Session->read("SScodentidad")." and cod_tipo_inst=".$this->Session->read("SScodtipoinst")." and  cod_inst=".$this->Session->read("SScodinst")." and cod_dep=".$this->Session->read("SScoddep")." and  ano_contrato_obra=".$ano."";
									 $lista = $this->cobd01_contratoobras_cuerpo->generateList($condicion, " numero_contrato_obra ASC", null, "{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra", "{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra");
									 $this->set('lista_numero', $lista);
									 $this->set('numero_contrato_obra', $numero_orden_pago2);
									 $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_obra='".$numero_contrato_obra."'  and  ano_contrato_obra=".$ano_orden_pago2."";
									 $numero_datos_encabezado = $this->cobd01_contratoobras_cuerpo->findAll($condicion." and rif='".$this->data['cobp01_registro_anticipo_contratoobras_uso_general']['rif']."' ");
									 $numero_datos_orden_compra_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion. " and ano=".$ano_partidas[0]."  ", null, 'numero_contrato_obra DESC');
									 $numero_datos_aux  =  $numero_datos_encabezado;
									foreach($numero_datos_aux as $aux){
										$rif = $aux['cobd01_contratoobras_cuerpo']['rif'];
										$ano_contrato_obra    =  $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
										$numero_contrato_obra =  $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
										$anticipo_con_iva    =  $aux['cobd01_contratoobras_cuerpo']['anticipo_con_iva'];
										$porcentaje_iva      =  $aux['cobd01_contratoobras_cuerpo']['porcentaje_iva'];
										$porcentaje_anticipo =  $aux['cobd01_contratoobras_cuerpo']['porcentaje_anticipo'];
									}//fin foreach
									$opc = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($condicion." and ano_contrato_obra=".$ano_contrato_obra." and numero_contrato_obra='".$numero_contrato_obra."' and numero_anticipo=".$numero_anticipo."  ");

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
									$aux_monto_anticipo = $this->cobd01_contratoobras_anticipo_partidas->findAll($condicion." and ano_contrato_obra=".$ano_contrato_obra."  and numero_contrato_obra='".$numero_contrato_obra."' and numero_anticipo=".$numero_anticipo."  ".$agrupar, $campos, null,  null, null);
									foreach($aux_monto_anticipo as $aux2_monto_anticipo){
										$monto_anticipo = $aux2_monto_anticipo[0]['monto'];
									}//fin foreach
									$this->set('year', $ano);
									//$this->set('datos_orden_compra_anticipo_cuerpo', $opc);
									//$this->set('datos_orden_compra_encabezado', $numero_datos_encabezado);
									//$this->set('datos_orden_compra_partidas', $numero_datos_orden_compra_partidas);
									//$this->set('denominacion_rif', $denominacion_rif);
									//$this->set('direccion_comercial_rif', $direccion_comercial_rif);
									//$this->set('monto_anticipo', $monto_anticipo);
									//$this->set('anticipo_con_iva', $anticipo_con_iva);
									//$this->set('porcentaje_anticipo', $porcentaje_anticipo);
									//$this->set('porcentaje_iva', $porcentaje_iva);
									////************************************** DESPUES DE GUARDAR ****************************************/////

									 }else{
									     	$this->cobd01_contratoobras_cuerpo->execute("ROLLBACK;");
									     	$this->set('msg_error', 'EL ANTICIPO NO PUEDO SER ALMACENADO - POR FAVOR INTENTE DE NUEVO');
									     }//fin else


									}else{
									        $this->cobd01_contratoobras_anticipo_partidas->execute("ROLLBACK;");
											if($mensaje==1){
											   $this->set('msg_error', 'ANTICIPO NO PUEDE SER PROCESADO, FAVOR REVISE LAS PARTIDAS');
											}else if($mensaje==2){
											   $this->set('msg_error', 'EL ANTICIPO NO PUEDO SER ALMACENADO - POR FAVOR INTENTE DE NUEVO');
											}//fin if

									}//fin else



									}else{
										$this->cscd04_ordencompra_anticipo_cuerpo->execute("ROLLBACK;");
										$this->set('msg_error', 'EL ANTICIPO NO PUEDO SER ALMACENADO - POR FAVOR INTENTE DE NUEVO');
									}//fin else


}else{

		$this->set('msg_error', 'EL ANTICIPO NO PUEDO SER ALMACENADO - POR FAVOR INTENTE DE NUEVO');

}//fin else



  $this->index();
  $this->render("index");

}//fin funtion guardar







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


if(!empty($this->data['cobp01_registro_anticipo_contratoobras_uso_general']['ano_contrato'])){	$_SESSION['ano_contrato_obra'] = $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['ano_contrato'];}else{$_SESSION['ano_contrato_obra'] = $this->ano_ejecucion();}
$ano = $_SESSION['ano_contrato_obra'];

if($var1!=null){
  if($var1=='si'){





if(!empty($this->data['cobp01_registro_anticipo_contratoobras_uso_general']['numero_contrato_obra'])){

	   $array = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($condicion. "  and numero_contrato_obra='".$this->data['cobp01_registro_anticipo_contratoobras_uso_general']['numero_contrato_obra']."' and ano_contrato_obra = ".$ano , null, 'ano_contrato_obra, numero_contrato_obra, numero_anticipo ASC', null);
  $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_anticipo_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_anticipo_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_anticipo']      = $aux['cobd01_contratoobras_anticipo_cuerpo']['numero_anticipo'];
 	$i++;
} $i--;

  for($a=0; $a<=$i; $a++){

    if($this->data['cobp01_registro_anticipo_contratoobras_uso_general']['numero_contrato_obra'] == $numero[$a]['numero_contrato_obra']){
    	   $pag_num = 0;
    	  $opcion='si';
    	  $numero_documento = $numero[$a]['numero_contrato_obra']; break;
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

	   $array = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($condicion. " and ano_contrato_obra = ".$ano , null, 'ano_contrato_obra, numero_contrato_obra, numero_anticipo ASC', null);
  $i = 0;

   foreach($array as $aux){
 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_anticipo_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_anticipo_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_anticipo']      = $aux['cobd01_contratoobras_anticipo_cuerpo']['numero_anticipo'];
 	$i++;
} $i--;


 if($i<=0){
	  $this->set('errorMessage', 'No existen datos');
	  $this->consulta_index();
	  $this->render('consulta_index');

	}else{
	 $this->consulta(0, $numero[0]['numero_contrato_obra']); $this->render('consulta');}  }//fin else



  }//fin if
}//fin i





		 $lista = $this->cobd01_contratoobras_anticipo_cuerpo->generateList($condicion.' and ano_contrato_obra='.$ano.$this->filtra_obra($ano), ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra');
		 $this->set('lista_numero', $lista);
		 $this->set('ano_contrato_obra', $_SESSION['ano_contrato_obra']);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));



}//fin function






function buscar_year($var1=null){
  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


                if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){

					$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
					$lista = $this->cobd01_contratoobras_anticipo_cuerpo->generateList($condicion.' and ano_contrato_obra='.$var1, ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra');
					 if($lista==""){$lista = array(''=>'');}
					$this->set('obras', $lista);
					$this->set('ano',$var1);

				}else{

				   $lista = $this->cobd01_contratoobras_anticipo_cuerpo->generateList($this->condicion().' and ano_contrato_obra='.$var1.$this->filtra_obra($var1), ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra');
				   if($lista==""){$lista = array(''=>'');}
				   $this->set('obras', $lista);

               }//fin else


}//fin function






function consulta($pag_num=null, $numero_documento=null, $g=null){
  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else


  $this->set('ano_contrato_obra_ejecucion', $this->ano_ejecucion());


   if(isset($_SESSION['ano_contrato_obra'])){$ano = $_SESSION['ano_contrato_obra'];}else{$ano = $this->ano_ejecucion();}
   $this->set('ano_contrato_obra', $ano);

   $array = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($condicion. "  and numero_contrato_obra='".$numero_documento."' and ano_contrato_obra = ".$ano , null, 'ano_contrato_obra, numero_contrato_obra, numero_anticipo ASC', null);

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_contrato_obra']    = $aux['cobd01_contratoobras_anticipo_cuerpo']['ano_contrato_obra'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_anticipo_cuerpo']['numero_contrato_obra'];
 	$numero[$i]['numero_anticipo']      = $aux['cobd01_contratoobras_anticipo_cuerpo']['numero_anticipo'];

 	$i++;

} $i--;

if(isset($numero[$pag_num]['numero_contrato_obra'])){



$datos_orden_compra_encabezado          =   $this->cobd01_contratoobras_cuerpo->findAll($condicion." and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']."  and upper(numero_contrato_obra)='".strtoupper($numero[$pag_num]['numero_contrato_obra'])."'  ");
$datos_orden_compra_anticipo_cuerpo =   $this->cobd01_contratoobras_anticipo_cuerpo->findAll($condicion." and ano_contrato_obra=".$numero[$pag_num]['ano_contrato_obra']." and upper(numero_contrato_obra)='".strtoupper($numero[$pag_num]['numero_contrato_obra'])."' and numero_anticipo=".$numero[$pag_num]['numero_anticipo']."  ");
//print_r($datos_orden_compra_anticipo_cuerpo);
$numero_datos_aux  =  $datos_orden_compra_encabezado;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cobd01_contratoobras_cuerpo']['rif'];
	$anticipo_con_iva = $aux['cobd01_contratoobras_cuerpo']['anticipo_con_iva'];
	$porcentaje_iva = $aux['cobd01_contratoobras_cuerpo']['porcentaje_iva'];
	$porcentaje_anticipo = $aux['cobd01_contratoobras_cuerpo']['porcentaje_anticipo'];
}//fin foreach
$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){$denominacion_rif = $aux_2['cpcd02']['denominacion']; $direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];}//fin foreach
$campos = ' cod_presi, cod_entidad, cod_tipo_inst, cod_inst, SUM(monto) as "monto" ';
$agrupar = ' GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst';
$sql = $condicion."  and  ano_contrato_obra='".$numero[$pag_num]['ano_contrato_obra']."'  and  numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."' and numero_anticipo=".$numero[$pag_num]['numero_anticipo']."  ".$agrupar;
$sql2 = $condicion."  and  ano_contrato_obra='".$numero[$pag_num]['ano_contrato_obra']."'  and  numero_contrato_obra='".$numero[$pag_num]['numero_contrato_obra']."' and numero_anticipo=".$numero[$pag_num]['numero_anticipo']."  ";
$aux_monto_anticipo = $this->cobd01_contratoobras_anticipo_partidas->findAll($sql, $campos, null,  null, null);
$numero_datos_orden_compra_partidas = $this->cobd01_contratoobras_anticipo_partidas->findAll($sql2);
foreach($aux_monto_anticipo as $aux2_monto_anticipo){ $monto_anticipo = $aux2_monto_anticipo[0]['monto']; }//fin foreach
$this->set('monto_anticipo', $monto_anticipo);
$this->set('anticipo_con_iva', $anticipo_con_iva);
$this->set('incluye_iva', $anticipo_con_iva);
$this->set('porcentaje_anticipo', $porcentaje_anticipo);
$this->set('porcentaje_iva', $porcentaje_iva);
 $this->set('denominacion_rif', $denominacion_rif);
 $this->set('datos_orden_compra_anticipo_partidas', $numero_datos_orden_compra_partidas);
 $this->set('direccion_comercial_rif', $direccion_comercial_rif);
 $this->set('datos_orden_compra', $datos_orden_compra_encabezado);
 $this->set('datos_orden_compra_anticipo_cuerpo', $datos_orden_compra_anticipo_cuerpo);
 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);

}else{

   if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else

  $lista = $this->cobd01_contratoobras_anticipo_cuerpo->generateList($condicion.' and ano_contrato_obra='.$ano.$this->filtra_obra($ano), ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra');
   if($lista==""){$lista = array(''=>'');}
   $this->set('obras', $lista);

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
	if(isset($this->data["cobp01_registro_anticipo_contratoobras_uso_general"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		     $tipo_documento           =  244;

			 //$concepto_anulacion       =  $this->data["cobp01_registro_anticipo_contratoobras_uso_general"]["concepto_anulacion"];
			 $concepto_anulacion = "";
			 $concepto = $concepto_anulacion;
			 $fecha_proceso_anulacion  =  date("d/m/Y");

			 $condicion_documento      =  2;//cuando se guarda es Activo=1

			 $ano_orden_compra    = $this->data["cobp01_registro_anticipo_contratoobras_uso_general"]["ano_contrato_obra"];
			 $numero_orden_compra = $this->data["cobp01_registro_anticipo_contratoobras_uso_general"]["numero_contrato_obra"];
			 $fecha_orden_compra  = $this->data["cobp01_registro_anticipo_contratoobras_uso_general"]["fecha_anticipo"];

			 $fecha_contrato      = $this->data["cobp01_registro_anticipo_contratoobras_uso_general"]["fecha_anticipo2"];
			 $fd = $fecha_contrato;


			 $numero_orden_compra_anticipo =                 $this->data["cobp01_registro_anticipo_contratoobras_uso_general"]["numero_anticipo"];
			 $porcentaje_anticipo          = $this->Formato1($this->data["cobp01_registro_anticipo_contratoobras_uso_general"]["porcentaje_anticipo"]);
			 $pregunta_ejercicio           = $this->data['cobp01_registro_anticipo_contratoobras_uso_general']['pregunta_ejercicio'];



			 $datos_partidas = $this->cobd01_contratoobras_anticipo_partidas->findAll($conditions = $this->condicion()." and ano_contrato_obra='$ano_orden_compra' and upper(numero_contrato_obra)='".strtoupper($numero_orden_compra)."' and numero_anticipo='$numero_orden_compra_anticipo'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			 $num_base = count($datos_partidas);
			$monto_anticipo = 0;
			$sql_update_cscd04_partidas = '';
			 foreach($datos_partidas as $row){
			 	$ano = $row['cobd01_contratoobras_anticipo_partidas']['ano'];
			 	$cod_sector = $row['cobd01_contratoobras_anticipo_partidas']['cod_sector'];
			 	$cod_programa = $row['cobd01_contratoobras_anticipo_partidas']['cod_programa'];
			 	$cod_sub_prog = $row['cobd01_contratoobras_anticipo_partidas']['cod_sub_prog'];
			 	$cod_proyecto = $row['cobd01_contratoobras_anticipo_partidas']['cod_proyecto'];
			 	$cod_activ_obra = $row['cobd01_contratoobras_anticipo_partidas']['cod_activ_obra'];
			 	$cod_partida = $row['cobd01_contratoobras_anticipo_partidas']['cod_partida'];
			 	$cod_generica = $row['cobd01_contratoobras_anticipo_partidas']['cod_generica'];
			 	$cod_especifica = $row['cobd01_contratoobras_anticipo_partidas']['cod_especifica'];
			 	$cod_sub_espec = $row['cobd01_contratoobras_anticipo_partidas']['cod_sub_espec'];
			 	$cod_auxiliar = $row['cobd01_contratoobras_anticipo_partidas']['cod_auxiliar'];
			 	$monto_partida = $row['cobd01_contratoobras_anticipo_partidas']['monto'];
			 	$numero_control_compromiso = $row['cobd01_contratoobras_anticipo_partidas']['numero_control_compromi'];
			 	$numero_control_causado = $row['cobd01_contratoobras_anticipo_partidas']['numero_control_causado'];
			 	$cond1 = $this->condicion()." and ano_contrato_obra=".$ano_orden_compra." and numero_contrato_obra='".$numero_orden_compra."' and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";
				$monto_anticipo += $monto_partida;

						$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
						$num_asiento_compromiso = $this->motor_presupuestario($cp,2, 4, 4, date("d/m/Y"), $monto_partida, $concepto, $ano, $numero_orden_compra, $numero_orden_compra_anticipo, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);

			 			$sql_update_cscd04_partidas .= "UPDATE cobd01_contratoobras_partidas SET anticipo=anticipo-$monto_partida WHERE ".$cond1.";";
			 }

             $cond2 = $this->condicion()." and ano_contrato_obra=".$ano_orden_compra." and upper(numero_contrato_obra)='".strtoupper($numero_orden_compra)."'";
			 $sql_update_cscd04_encabezado ="UPDATE cobd01_contratoobras_cuerpo SET porcentaje_anticipo= porcentaje_anticipo - ".$porcentaje_anticipo.", porcentaje_iva=0, anticipo_con_iva='0', monto_anticipo=monto_anticipo-'$monto_anticipo' WHERE ".$cond2.";";

				$sw = $this->cobd01_contratoobras_cuerpo->execute($sql_update_cscd04_partidas.$sql_update_cscd04_encabezado);

             /*$v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra." ORDER BY numero_acta_anulacion DESC");

		     if($v!=null && $sw >1){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",1)");
			    $numero=1;
		     }//fin else*/
			// $R1 = $this->cobd01_contratoobras_anticipo_cuerpo->execute("UPDATE cobd01_contratoobras_anticipo_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero." , condicion_actividad=2 ,fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'  WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano_orden_compra." and upper(numero_contrato_obra)='".strtoupper($numero_orden_compra)."' and numero_anticipo=".$numero_orden_compra_anticipo);


			 $R1 = $this->cobd01_contratoobras_anticipo_cuerpo->execute("DELETE FROM cobd01_contratoobras_anticipo_partidas  WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano_orden_compra." and upper(numero_contrato_obra)='".strtoupper($numero_orden_compra)."' and numero_anticipo=".$numero_orden_compra_anticipo);
			 $R1 = $this->cobd01_contratoobras_anticipo_cuerpo->execute("  DELETE FROM cobd01_contratoobras_anticipo_cuerpo    WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano_orden_compra." and upper(numero_contrato_obra)='".strtoupper($numero_orden_compra)."' and numero_anticipo=".$numero_orden_compra_anticipo);


		     //$v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",".$numero.",".$tipo_documento.",".$ano_orden_compra.",'".$numero_orden_compra."','".$this->Cfecha($fecha_orden_compra, 'A-M-D')."','".$concepto_anulacion."')");

	}//fin if



$this->set('Message_existe', 'El Anticipo fue eliminado correctamente');

$this->consulta_index('1');
$this->render('consulta_index');

/*

echo'<script>';
    echo'document.getElementById("guardar").disabled = true; ';
    echo'document.getElementById("anular").disabled = true; ';

    echo'document.getElementById("condicion_modificacion_1").checked = false;';
  	echo'document.getElementById("condicion_modificacion_1").checked = true;';

    echo'document.getElementById("a").innerHTML = "'.$ano_orden_compra.'"; ';
    echo'document.getElementById("b").innerHTML = "'.$numero.'"; ';
    echo'document.getElementById("c").innerHTML = "'.$fecha_proceso_anulacion.'"; ';
    echo'document.getElementById("d").innerHTML = "0"; ';
    echo'document.getElementById("e").innerHTML = "0"; ';
    echo'document.getElementById("f").innerHTML = "0"; ';  ///AQUI VA EL NUMERO DE ASIENTO PERO HAY QUE ESPERAR EL DE EL MOTOR
    echo'document.getElementById("g").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';

echo'</script>';*/



}//fin function





}//fin class
?>