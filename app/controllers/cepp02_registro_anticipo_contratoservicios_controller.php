<?php
class Cepp02registroanticipocontratoserviciosController extends AppController {

   var $name = "cepp02_registro_anticipo_contratoservicios";
   var $uses = array('cscd04_ordencompra_parametros','cfpd22_numero_asiento_causado','ccfd04_cierre_mes',
                     'cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_partidas','cepd02_contratoservicio_cuerpo',
                     'cscd04_ordencompra_parametros', 'cscd04_ordencompra_encabezado', 'cugd03_acta_anulacion_numero',
                     'cugd03_acta_anulacion_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo', 'cscd04_ordencompra_anticipo_partidas',
                     'ccfd03_instalacion', 'cscd04_ordencompra_partidas', 'cpcd02','cfpd05','cugd04','cfpd22',
                     'cepd02_contratoservicio_anticipo_partidas', 'v_cepd02_contratoservicio_cuerpo', 'v_cepd02_contratoservicio_anticipo_cuerpo',

                     'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
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


 function index(){

 $this->verifica_entrada('88');

 $this->layout = "ajax";
 $ano='';
 $ano=$this->ano_ejecucion();

  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');



    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  and  ano_contrato_servicio='.$ano.'';
 	$lista = $this->v_cepd02_contratoservicio_cuerpo->generateList($condicion.' and condicion_actividad=1   and monto_cancelado=0', ' numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.v_cepd02_contratoservicio_cuerpo.deno_numero_contrato_con_rif');
 	$this->set('lista_numero', $lista);
 	$this->set('ano',$ano);


 }//fin function


function incluye_iva($codigo=null, $var1=null){
    $this->layout = "ajax";//echo "incluye".$codigo.$var1;
    $porcentaje_anticipo = 0;
    $factor_reversion    = "";
    $porcentaje_iva      = "0";

	if($var1 != null){
		$this->Session->write('incluye_iva', $var1);
	}
	//echo 'el incluye iva es : '.$this->Session->read('incluye_iva');
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
    $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
    foreach($parametros_datos as $aux_22){
      $porcentaje_anticipo = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
     }//fin foreach

     $porcentaje_iva = $this->cepd02_contratoservicio_cuerpo->field('cepd02_contratoservicio_cuerpo.porcentaje_iva', $conditions = $this->SQLCA(). "and cepd02_contratoservicio_cuerpo.numero_contrato_servicio='".$codigo."'", $order =null);


 //echo "el porcentaje iva es ".$porcentaje_iva;

	echo'<script>';
     echo'monto_anticipo("'.$var1.'", "'.$this->Formato_redondear($porcentaje_anticipo).'", "'.$this->Formato_redondear($porcentaje_iva).'");';
    echo'</script>';


}//fin function


function datos($cod=null, $var=null) {
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' '.'  and  ano_contrato_servicio='.$ano;
	$numero_datos_partidas = $this->cepd02_contratoservicio_partidas->findAll($condicion." and numero_contrato_servicio='$cod'");
	$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
	$this->incluye_iva($cod, $var);

}//fin datos




function selecion($var1=null){
  $this->layout = "ajax";

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

 $year = $this->ano_ejecucion();
 $ano = $this->ano_ejecucion();

  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');



 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  and  ano_contrato_servicio='.$ano.'';
 $lista = $this->cepd02_contratoservicio_cuerpo->generateList($condicion.' and condicion_actividad=1   and monto_cancelado=0', ' numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio');
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
 	$incluye_iva = 2; //para no usar la tabla de parametros por defecto se esta colocando 2
 	if(!empty($incluye_iva)){ $this->incluye_iva($var1, $incluye_iva);}
}//fin if


if($var1==null){

$this->index();
$this->render('index');

}else{



//////***********       LOS DEL PORCENTAJE         *****************//////////

$porcentaje_anticipo = 0;
$factor_reversion = "";
$porcentaje_iva = 0;


 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
 $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
    foreach($parametros_datos as $aux_22){
      $anticipo_incluye_iva = $aux_22['cscd04_ordencompra_parametros']['anticipo_incluye_iva'];
      $porcentaje_anticipo = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
      $porcentaje_iva    = $aux_22['cscd04_ordencompra_parametros']['porcentaje_iva'];
    }//fin foreach



$anticipo_incluye_iva = 2; //para no usar la tabla de parametros por defecto se esta colocando 2

//////***********       FIN LOS DEL PORCENTAJE         *****************//////////

$condicion = "cod_presi=".$this->Session->read("SScodpresi")."  and  cod_entidad=".$this->Session->read("SScodentidad")." and cod_tipo_inst=".$this->Session->read("SScodtipoinst")." and  cod_inst=".$this->Session->read("SScodinst")." and cod_dep=".$this->Session->read("SScoddep")." and  numero_contrato_servicio='".$var1."' and  ano_contrato_servicio=".$ano." ";
$numero_datos = $this->cepd02_contratoservicio_cuerpo->findAll($condicion);
$numero_datos_partidas = $this->cepd02_contratoservicio_partidas->findAll($condicion, null, 'numero_contrato_servicio DESC');


$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cepd02_contratoservicio_cuerpo']['rif'];
	$ano_contrato_obra = $aux['cepd02_contratoservicio_cuerpo']['ano_contrato_servicio'];
	$numero_contrato_obra = $aux['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio'];
}//fin foreach

$opc    = $this->cepd02_contratoservicio_anticipo_cuerpo->findCount($condicion." and ano_contrato_servicio=".$ano_contrato_obra."  and numero_contrato_servicio='".$numero_contrato_obra."'");
$result = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($condicion."  and ano_contrato_servicio=".$ano_contrato_obra."  and numero_contrato_servicio='".$numero_contrato_obra."' ", null, "numero_anticipo ASC", null, null);
foreach($result as $ves){$opc = $ves['cepd02_contratoservicio_anticipo_cuerpo']['numero_anticipo'];}//fin foreach


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

  $cod_presi                 = $this->Session->read('SScodpresi');
  $cod_entidad               = $this->Session->read('SScodentidad');
  $cod_tipo_inst             = $this->Session->read('SScodtipoinst');
  $cod_inst                  = $this->Session->read('SScodinst');
  $cod_dep                   = $this->Session->read('SScoddep');
  $mensaje                   = 2;
  $ano_contrato_obra         = $this->data['cobp01_registro_anticipo_contratoobras']['ano_contrato_obra'];
  $ann                       = $ano_contrato_obra;
  $numero_contrato_obra      =       $this->data['cobp01_registro_anticipo_contratoobras']['numero_contrato_obra'];
 // echo "el numero contrato es".$numero_contrato_obra;
  $numero_anticipo           = $this->data['cobp01_registro_anticipo_contratoobras']['numero_anticipo'];
  $nda = $numero_anticipo;
//  $tipo_anticipo             = $this->data['cobp01_registro_anticipo_contratoobras']['incluye_iva'];
  $tipo_anticipo=2;

  $iva                       = $this->Formato1($this->data['cobp01_registro_anticipo_contratoobras']['porcentaje_iva']);
  $iva_anticipo              = $this->Formato1($this->data['cobp01_registro_anticipo_contratoobras']['porcentaje_anticipo']);
  $monto_anticipo            = $this->Formato1($this->data['cobp01_registro_anticipo_contratoobras']['monto_anticipo']);
  $fecha_anticipo            = $this->data['cobp01_registro_anticipo_contratoobras']['fecha_anticipo'];
  $fd = $fecha_anticipo;
  $observaciones             =  $this->data['cobp01_registro_anticipo_contratoobras']['observaciones'];
  $pregunta_ejercicio        =  $this->data['cobp01_registro_anticipo_contratoobras']['pregunta_ejercicio'];





if(isset($ano_contrato_obra)){
if(isset($numero_contrato_obra)){
if(isset($numero_anticipo)){
if(isset($iva)){
if(isset($iva_anticipo)){
if(isset($monto_anticipo)){
if(isset($fecha_anticipo)){
if(isset($observaciones)){








   $ccp                                    = $observaciones;
   $fecha_proceso_registro                 = date("d/m/Y");
   $fecha_contrato_obra2                   = $fd;
   $aux                                    = $fecha_contrato_obra2[6].$fecha_contrato_obra2[7].$fecha_contrato_obra2[8].$fecha_contrato_obra2[9];
if($aux!=$ano_contrato_obra){$fd = $fecha_proceso_registro;}


  $dia_asiento_registro                   = '0';
  $mes_asiento_registro                   = '0';
  $ano_asiento_registro                   = '0';
  $numero_asiento_registro                = '0';
  $username_registro                      = $_SESSION['nom_usuario'];
  $condicion_actividad                    = '1';
  $ano_asiento_anulacion                  = '0';
  $mes_asiento_anulacion                  = '0';
  $dia_asiento_anulacion                  = '0';
  $numero_asiento_anulacion               = '0';
  $username_anulacion                     = '0';
  $fecha_proceso_anulacion                = '01/01/1900';
  $ano_orden_pago                         = "0";
  $numero_orden_pago                      = '0';
  $numero_orden_pago2                     = $numero_contrato_obra;
  $ano_orden_pago2                        = $ano_contrato_obra;
  $ano_anulacion                          = '0';
  $ano_asiento_contable                   = '0';
  $numero_anulacion                       = '0';


$sw1 = 0;
$sw2 = 0;
$sw3 = 0;
$sw4 = 0;
$sw5 = 0;

$sql="     BEGIN;    INSERT INTO cepd02_contratoservicio_anticipo_cuerpo (cod_presi,
  cod_entidad,
  cod_tipo_inst,
  cod_inst,
  cod_dep,
  ano_contrato_servicio,
  numero_contrato_servicio,
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
  ano_asiento_anulacion,
  numero_asiento_anulacion,
  fecha_proceso_anulacion,
  username_anulacion,
  ano_orden_pago,
  numero_orden_pago, porcentaje_anticipo, saldo_ano_anterior)";
$sql.="VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."', '".$ano_contrato_obra."', '".$numero_contrato_obra."', '".$numero_anticipo."',".$this->Formato1($monto_anticipo).", '".$this->Cfecha($fecha_anticipo, 'A-M-D')."', '".$observaciones."', '".$dia_asiento_registro."','".$mes_asiento_registro."', '".$ano_asiento_registro."', '".$numero_asiento_registro."','".$username_registro."', '".$this->Cfecha($fecha_proceso_registro, 'A-M-D')."', '".$condicion_actividad."', '".$ano_anulacion."','".$numero_anulacion."', '".$dia_asiento_anulacion."', '".$mes_asiento_anulacion."', ".$ano_asiento_contable.", '".$numero_asiento_anulacion."', '".$this->Cfecha($fecha_proceso_anulacion, 'A-M-D')."', '".$username_anulacion."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$iva_anticipo."', '".$pregunta_ejercicio."'); ";
$sw1 = $this->cscd04_ordencompra_anticipo_cuerpo->execute($sql);

if($sw1>1){


$i_lenght = $this->data['cobp01_registro_anticipo_contratoobras']['cuenta_i'];
$condicion = "cod_presi=".$this->Session->read('SScodpresi')." and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_servicio='".$numero_contrato_obra."'  and  ano_contrato_servicio=".$ano_contrato_obra."";

$condicion1 = "cod_presi=".$this->Session->read('SScodpresi')." and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_servicio='".$numero_contrato_obra."'  and  ano_contrato_servicio=".$ano_contrato_obra." and not(cod_partida=403 and cod_generica=18 and cod_especifica=1 and cod_sub_espec=0)";
$numero_datos_partidas = $this->cepd02_contratoservicio_partidas->findAll($condicion1, null, 'numero_contrato_servicio DESC');


$a=0;
$i_aux = 0;

////////////////
//echo "si llego al foreach";
////////////

foreach($numero_datos_partidas as $aux_partidas){//inicio foreach


  $cod_presi2[$a]                 =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_presi'];
  $cod_entidad2[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_entidad'];
  $cod_tipo_inst2[$a]             =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_tipo_inst'];
  $cod_inst2[$a]                  =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_inst'];
  $cod_dep2[$a]                   =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_dep'];
  $ano_contrato_obra3[$a]         =   $aux_partidas['cepd02_contratoservicio_partidas']['ano_contrato_servicio'];
  $numero_contrato_obra3[$a]      =   $aux_partidas['cepd02_contratoservicio_partidas']['numero_contrato_servicio'];
  $ano_partidas[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['ano'];
  $cod_sector[$a]                 =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_sector'];
  $cod_programa[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_programa'];
  $cod_sub_prog[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_sub_prog'];
  $cod_proyecto[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_proyecto'];
  $cod_activ_obra[$a]             =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_activ_obra'];
  $cod_partida[$a]                =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_partida'];
  $cod_generica[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_generica'];
  $cod_especifica[$a]             =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_especifica'];
  $cod_sub_espec[$a]              =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_sub_espec'];
  $cod_auxiliar[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_auxiliar'];
  $monto2[$a]                     =   $aux_partidas['cepd02_contratoservicio_partidas']['monto'];
  $anticipo2[$a]                  =   $aux_partidas['cepd02_contratoservicio_partidas']['anticipo'];
  $numero_control_compromiso      =   $aux_partidas['cepd02_contratoservicio_partidas']['numero_control_compromiso'];


 $concate[$a] = $this->AddCero2(substr($cod_partida[$a], -2), substr($cod_partida[$a], 0, 1 )).'.'.$this->AddCero2($cod_generica[$a]).'.'.$this->AddCero2($cod_especifica[$a]).'.'.$this->AddCero2($cod_sub_espec[$a]);
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


for($i=0; $i<=$i_lenght; $i++){
	 if($partidas_aux==$this->data['partidas']['partidas_'.$i]){$partidas_vista['pago_'.$i_aux] = $this->data['cobp01_registro_anticipo_contratoobras']['anticipo_'.$i]; $i_aux++;}
}//fin foreach




}//fin foreach


			$j = 0;

			$numero_causado = 0;

		if($pregunta_ejercicio==2){

			$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);

			if(!empty($numero_causado)){
				$numero_causado ++;
				$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
			}else{
				$numero_causado = 1;
				$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado');";
			}
				$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);


		}//fin function

for($i=0; $i<count($numero_datos_partidas); $i++){//echo "si entro". $cod_sector[$i];
       $var[$i]['monto']=$partidas_vista['pago_'.$i];

if($var[$i]['monto']!="0,00"){
   $var[$i]['monto'] = $this->Formato1($var[$i]['monto']);

             if(($var[$i]['monto']+$anticipo2[$i]) <= $monto2[$i] ){


             			$ndo  = $numero_contrato_obra3[$a];
             			$rnco = $numero_control_compromiso;
						$rnca = $numero_causado;


             	if($pregunta_ejercicio==2){

						$cp = $this->crear_partida($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
						$to   = 1;
						$td   = 4;
						$ta   = 6;
						$mt   = $var[$i]['monto'];
						$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, $nda, null, null, null, null, null, null, $rnco, $rnca, null, null, $i);

				}//fin function

						$sql2  ="INSERT INTO cepd02_contratoservicio_anticipo_partidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_servicio, numero_contrato_servicio, numero_anticipo, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica,  cod_sub_espec, cod_auxiliar,  monto, numero_control_compromiso, numero_control_causado) ";
						$sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_contrato_obra3[$i]."', '".$numero_contrato_obra3[$i]."', '".$numero_anticipo."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['monto']."', '$rnco', '$rnca'); ";
						$sw2 = $this->cepd02_contratoservicio_anticipo_partidas->execute($sql2);

							if($sw2 >1){
								$monto_anticipo_tipo2 = $anticipo2[$i] + $var[$i]['monto'];
							}else{
								$mensaje = 1; $sw3 = 0;
								break;
							}

						$sql4  = "UPDATE cepd02_contratoservicio_partidas SET anticipo= '".$monto_anticipo_tipo2."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_contrato_servicio='".$numero_contrato_obra."'  and  ano_contrato_servicio=".$ano_orden_pago2." and ano=".$ano_partidas[$i]." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i]."; ";
						$sw3 = $this->cepd02_contratoservicio_partidas->execute($sql4);

							if($sw3 >1){
							}else{
								$mensaje = 1; $sw3 = 0;
								break;
							}

         			}else{ $mensaje=1; $sw3=0; break;  }

  }//fin if
}//fin for




if($sw3 > 1){


$numero_datos_partidas22 = $this->cepd02_contratoservicio_cuerpo->findAll($condicion);
foreach($numero_datos_partidas22 as $aux_partidas22){
  $monto_anticipo_aux             =   $aux_partidas22['cepd02_contratoservicio_cuerpo']['monto_anticipo'];
}//fin for


$monto_anticipo = $monto_anticipo + $monto_anticipo_aux;
//$tipo_anticipo = "2";
$sql3  = "UPDATE cepd02_contratoservicio_cuerpo SET monto_anticipo=  '".$monto_anticipo."', porcentaje_iva='".$iva."', porcentaje_anticipo=porcentaje_anticipo + '".$iva_anticipo."', anticipo_con_iva='".$tipo_anticipo."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_contrato_servicio='".$numero_contrato_obra."'  and  ano_contrato_servicio=".$ano_orden_pago2."; ";
$sw4 = $this->cepd02_contratoservicio_cuerpo->execute($sql3);
//echo $sql3;


 if($sw4 > 1){
        $this->cepd02_contratoservicio_cuerpo->execute("COMMIT;");    $this->set('msg', 'LOS DATOS FUERON GUARDADOS');

////************************************** DESPUES DE GUARDAR ****************************************/////
 $ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
//echo $condicion;
 $ano = $this->ano_ejecucion();
 $condicion = "cod_presi=".$this->Session->read("SScodpresi")." and  cod_entidad=".$this->Session->read("SScodentidad")." and cod_tipo_inst=".$this->Session->read("SScodtipoinst")." and  cod_inst=".$this->Session->read("SScodinst")." and cod_dep=".$this->Session->read("SScoddep")." and  ano_contrato_servicio=".$ano."";
 $lista = $this->cepd02_contratoservicio_cuerpo->generateList($condicion, " numero_contrato_servicio ASC", null, "{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio", "{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio");
 $this->set('lista_numero', $lista);
 $this->set('numero_contrato_obra', $numero_orden_pago2);
 $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_servicio='".$numero_contrato_obra."'  and  ano_contrato_servicio=".$ano_orden_pago2."";
 $numero_datos_encabezado = $this->cepd02_contratoservicio_cuerpo->findAll($condicion." and rif='".$this->data['cobp01_registro_anticipo_contratoobras']['rif']."' ");
 $numero_datos_orden_compra_partidas = $this->cepd02_contratoservicio_partidas->findAll($condicion. " and ano=".$ano_partidas[0]."  ");
 $numero_datos_aux  =  $numero_datos_encabezado;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cepd02_contratoservicio_cuerpo']['rif'];
	$ano_contrato_obra    =  $aux['cepd02_contratoservicio_cuerpo']['ano_contrato_servicio'];
	$numero_contrato_obra =  $aux['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio'];
	$anticipo_con_iva     =  $aux['cepd02_contratoservicio_cuerpo']['anticipo_con_iva'];
	$porcentaje_iva       =  $aux['cepd02_contratoservicio_cuerpo']['porcentaje_iva'];
	$porcentaje_anticipo  =  $aux['cepd02_contratoservicio_cuerpo']['porcentaje_anticipo'];
}//fin foreach
$opc = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($condicion." and ano_contrato_servicio=".$ano_contrato_obra." and numero_contrato_servicio='".$numero_contrato_obra."' and numero_anticipo=".$numero_anticipo."  ");

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
$aux_monto_anticipo = $this->cepd02_contratoservicio_anticipo_partidas->findAll($condicion." and ano_contrato_servicio=".$ano_contrato_obra."  and numero_contrato_servicio='".$numero_contrato_obra."' and numero_anticipo=".$numero_anticipo."  ".$agrupar, $campos, null,  null, null);
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
     	$this->cepd02_contratoservicio_cuerpo->execute("ROLLBACK;");
     	$this->set('msg_error', 'EL ANTICIPO NO PUEDO SER ALMACENADO - POR FAVOR INTENTE DE NUEVO');
     }//fin else




}else{
$this->cepd02_contratoservicio_anticipo_partidas->execute("ROLLBACK;");
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




}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}


  $this->index();
  $this->render("index");

}//fin function guardar





function buscar_year($var1=null){
  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');

  if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' ';
}else{$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';}



  $lista = $this->cepd02_contratoservicio_anticipo_cuerpo->generateList($condicion.' and ano_contrato_servicio='.$var1, ' numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_anticipo_cuerpo.numero_contrato_servic', '{n}.cepd02_contratoservicio_anticipo_cuerpo.numero_contrato_servic');
   if($lista==""){$lista = array(''=>'');}
   $this->set('obras', $lista);
}//fin function








function consulta_index($var1=null){

  $this->layout = "ajax";
  $pag_num = 0;
  $opcion = 'no';
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


if(!empty($this->data['cobp01_registro_anticipo_contratoobras']['ano_contrato'])){	$_SESSION['ano_contrato_servicio'] = $this->data['cobp01_registro_anticipo_contratoobras']['ano_contrato'];}else{$_SESSION['ano_contrato_servicio'] = $this->ano_ejecucion();}


if($var1!=null){

  if($var1=='si'){

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
  $year = $this->ano_ejecucion();
  $ano = $_SESSION['ano_contrato_servicio'];


  if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else



if(!empty($this->data['cobp01_registro_anticipo_contratoobras']['numero_contrato_obra'] )){

   $array = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($condicion. " and  numero_contrato_servicio='".$this->data['cobp01_registro_anticipo_contratoobras']['numero_contrato_obra']."'   and ano_contrato_servicio = ".$ano , null, 'ano_contrato_servicio, numero_contrato_servicio, numero_anticipo ASC', null);
  $i = 0;
  //print_r($array);
   foreach($array as $aux){
 	$numero[$i]['ano_contrato_obra']    = $aux['cepd02_contratoservicio_anticipo_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cepd02_contratoservicio_anticipo_cuerpo']['numero_contrato_servic'];
 	$numero[$i]['numero_anticipo']      = $aux['cepd02_contratoservicio_anticipo_cuerpo']['numero_anticipo'];
 	$i++;
} $i--;



  for($a=0; $a<=$i; $a++){



    if($this->data['cobp01_registro_anticipo_contratoobras']['numero_contrato_obra'] == $numero[$a]['numero_contrato_obra']){
    	$pag_num = 0;
    	$opcion='si';
    	$numero_documento = $numero[$a]['numero_contrato_obra'];
    	break;}
    	else{
    		$pag_num = 0;
    		$numero_documento = $numero[0]['numero_contrato_obra'];
    		$opcion='si';
    	}
   }//fin for

      if($opcion=='si'){$this->consulta($pag_num, $numero_documento);$this->render('consulta');//echo "entro en si";
}else if($opcion=='no'){//echo "entro en no";
	$this->set('errorMessage', 'No existen datos');
	}//fin else

}else{

	 $array = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($condicion. "  and ano_contrato_servicio = ".$ano , null, 'ano_contrato_servicio, numero_contrato_servicio, numero_anticipo ASC', null);
  $i = 0;
  //print_r($array);
   foreach($array as $aux){
 	$numero[$i]['ano_contrato_obra']    = $aux['cepd02_contratoservicio_anticipo_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cepd02_contratoservicio_anticipo_cuerpo']['numero_contrato_servic'];
 	$numero[$i]['numero_anticipo']      = $aux['cepd02_contratoservicio_anticipo_cuerpo']['numero_anticipo'];
 	$i++;
} $i--;

 if($i<=0){
	  $this->set('errorMessage', 'No existen datos');
	  $this->consulta_index();
	  $this->render('consulta_index');

	}else{

	$this->consulta(0, $numero[0]['numero_contrato_obra']);$this->render('consulta');}  }//fin else



  }//fin if
}//fin if


if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' ';
}else{$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';}

 $lista = $this->v_cepd02_contratoservicio_anticipo_cuerpo->generateList($condicion.' and ano_contrato_servicio='.$_SESSION['ano_contrato_servicio'], ' numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_anticipo_cuerpo.numero_contrato_serv', '{n}.v_cepd02_contratoservicio_anticipo_cuerpo.denominacion_rif');
 if($lista==""){$lista = array(''=>'');}
 $this->concatena( $lista, 'lista_numero');
 $this->set('ano_contrato_servicio', $_SESSION['ano_contrato_servicio']);

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function








function consulta($pag_num=null, $numero_documento=null){
  $this->layout = "ajax";
  $denominacion_rif= "";
  $direccion_comercial_rif = "";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');

      if(isset($_SESSION['ano_contrato_servicio'])){$ano = $_SESSION['ano_contrato_servicio'];}else{$ano = $this->ano_ejecucion();}


  if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else




$this->set('ano_ejecucion', $this->ano_ejecucion());



   $array = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($condicion. " and  numero_contrato_servicio='".$numero_documento."' and ano_contrato_servicio = ".$ano, null, 'ano_contrato_servicio, numero_contrato_servicio, numero_anticipo ASC', null);

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_contrato_obra'] = $aux['cepd02_contratoservicio_anticipo_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_contrato_obra'] = $aux['cepd02_contratoservicio_anticipo_cuerpo']['numero_contrato_servic'];
 	$numero[$i]['numero_anticipo'] = $aux['cepd02_contratoservicio_anticipo_cuerpo']['numero_anticipo'];

 	$i++;

} $i--;



if(isset($numero[$pag_num]['numero_contrato_obra'])){


$datos_orden_compra_encabezado          =   $this->cepd02_contratoservicio_cuerpo->findAll($condicion." and ano_contrato_servicio=".$numero[$pag_num]['ano_contrato_obra']."  and numero_contrato_servicio='".$numero[$pag_num]['numero_contrato_obra']."' and monto_anticipo!=0  ");
$datos_orden_compra_anticipo_cuerpo =   $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($condicion." and ano_contrato_servicio=".$numero[$pag_num]['ano_contrato_obra']." and numero_contrato_servicio='".$numero[$pag_num]['numero_contrato_obra']."' and numero_anticipo=".$numero[$pag_num]['numero_anticipo']."  ");
//print_r($datos_orden_compra_anticipo_cuerpo);
$numero_datos_aux  =  $datos_orden_compra_encabezado;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cepd02_contratoservicio_cuerpo']['rif'];
	$anticipo_con_iva    = $aux['cepd02_contratoservicio_cuerpo']['anticipo_con_iva'];
	$porcentaje_iva      = $aux['cepd02_contratoservicio_cuerpo']['porcentaje_iva'];
	$porcentaje_anticipo = $aux['cepd02_contratoservicio_cuerpo']['porcentaje_anticipo'];
}//fin foreach
$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){$denominacion_rif = $aux_2['cpcd02']['denominacion']; $direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];}//fin foreach
$campos = ' cod_presi, cod_entidad, cod_tipo_inst, cod_inst, SUM(monto) as "monto" ';
$agrupar = ' GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst';
$sql = $condicion."  and  ano_contrato_servicio=".$numero[$pag_num]['ano_contrato_obra']."  and  numero_contrato_servicio='".$numero[$pag_num]['numero_contrato_obra']."' and numero_anticipo=".$numero[$pag_num]['numero_anticipo']."  ".$agrupar;
$sql2 = $condicion."  and  ano_contrato_servicio=".$numero[$pag_num]['ano_contrato_obra']."  and  numero_contrato_servicio='".$numero[$pag_num]['numero_contrato_obra']."' and numero_anticipo=".$numero[$pag_num]['numero_anticipo']."  ";
$aux_monto_anticipo = $this->cepd02_contratoservicio_anticipo_partidas->findAll($sql, $campos, null,  null, null);
$numero_datos_orden_compra_partidas = $this->cepd02_contratoservicio_anticipo_partidas->findAll($sql2);
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
 //[print_r($numero_datos_orden_compra_partidas);

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
//print_r($this->data);
	if(isset($this->data["cobp01_registro_anticipo_contratoobras"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		     $tipo_documento           =  246;

			 //$concepto_anulacion       =  $this->data["cobp01_registro_anticipo_contratoobras"]["concepto_anulacion"];
			 $concepto_anulacion = "";
			 $concepto = $concepto_anulacion;
			 $fecha_proceso_anulacion  =  date("d/m/Y");

			 $condicion_documento      =  2;//cuando se guarda es Activo=1

			 $ano_orden_compra    = $this->data["cobp01_registro_anticipo_contratoobras"]["ano_contrato_obra"];
			 $numero_orden_compra = $this->data["cobp01_registro_anticipo_contratoobras"]["numero_contrato_obra"];
			 $fecha_orden_compra  = $this->data["cobp01_registro_anticipo_contratoobras"]["fecha_anticipo"];

			 $fecha_contrato      = $this->data["cobp01_registro_anticipo_contratoobras"]["fecha_anticipo2"];
			 $fd = $fecha_contrato;

			 $numero_orden_compra_anticipo = (int) $this->data["cobp01_registro_anticipo_contratoobras"]["numero_anticipo"];

			 $porcentaje_anticipo          = $this->Formato1($this->data["cobp01_registro_anticipo_contratoobras"]["porcentaje_anticipo"]);
			 $pregunta_ejercicio           = $this->data['cobp01_registro_anticipo_contratoobras']['pregunta_ejercicio'];

			 $datos_partidas = $this->cepd02_contratoservicio_anticipo_partidas->findAll($conditions = $this->condicion()." and ano_contrato_servicio='$ano_orden_compra' and numero_contrato_servicio='".$numero_orden_compra."' and numero_anticipo='$numero_orden_compra_anticipo'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			 $num_base = count($datos_partidas);
			 //print_r($datos_partidas);
			$monto_anticipo = 0;
			$sql_update_cscd04_partidas = '';




if(isset($ano_orden_compra)){
if(isset($numero_orden_compra)){
if(isset($fecha_orden_compra)){
if(isset($numero_orden_compra_anticipo)){




//print_r($datos_partidas);
			 foreach($datos_partidas as $row){
			 	$ano = $row['cepd02_contratoservicio_anticipo_partidas']['ano'];
			 	$cod_sector = $row['cepd02_contratoservicio_anticipo_partidas']['cod_sector'];
			 	$cod_programa = $row['cepd02_contratoservicio_anticipo_partidas']['cod_programa'];
			 	$cod_sub_prog = $row['cepd02_contratoservicio_anticipo_partidas']['cod_sub_prog'];
			 	$cod_proyecto = $row['cepd02_contratoservicio_anticipo_partidas']['cod_proyecto'];
			 	$cod_activ_obra = $row['cepd02_contratoservicio_anticipo_partidas']['cod_activ_obra'];
			 	$cod_partida = $row['cepd02_contratoservicio_anticipo_partidas']['cod_partida'];
			 	$cod_generica = $row['cepd02_contratoservicio_anticipo_partidas']['cod_generica'];
			 	$cod_especifica = $row['cepd02_contratoservicio_anticipo_partidas']['cod_especifica'];
			 	$cod_sub_espec = $row['cepd02_contratoservicio_anticipo_partidas']['cod_sub_espec'];
			 	$cod_auxiliar = $row['cepd02_contratoservicio_anticipo_partidas']['cod_auxiliar'];
			 	$monto_partida = $row['cepd02_contratoservicio_anticipo_partidas']['monto'];
			 	$numero_control_compromiso = $row['cepd02_contratoservicio_anticipo_partidas']['numero_control_compr'];
			 	$numero_control_causado = $row['cepd02_contratoservicio_anticipo_partidas']['numero_control_causa'];
			 	$cond1 = $this->condicion()." and ano_contrato_servicio=".$ano_orden_compra." and numero_contrato_servicio='".$numero_orden_compra."' and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";

				$monto_anticipo += $monto_partida;

				$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

		if($pregunta_ejercicio==2){

				$num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 4, 6, date("d/m/Y"), $monto_partida, $concepto, $ano, $numero_orden_compra, $numero_orden_compra_anticipo, null, null, null, null, null, null, $numero_control_compromiso, $numero_control_causado, null, null, null);

		}//fin function

			 	$sql_update_cscd04_partidas .= "UPDATE cepd02_contratoservicio_partidas SET anticipo=anticipo-$monto_partida WHERE ".$cond1.";";


			 }//fin foreach

			 $cond2 = $this->condicion()." and ano_contrato_servicio=".$ano_orden_compra." and numero_contrato_servicio='".$numero_orden_compra."'";
			 $sql_update_cscd04_encabezado ="UPDATE cepd02_contratoservicio_cuerpo SET porcentaje_anticipo= porcentaje_anticipo - ".$porcentaje_anticipo.", monto_anticipo=monto_anticipo-'$monto_anticipo' WHERE ".$cond2.";";

				$sw = $this->cepd02_contratoservicio_cuerpo->execute($sql_update_cscd04_partidas.$sql_update_cscd04_encabezado);

			 //$numero_anulacion=$this->cugd03_acta_anulacion_numero->execute($condicion);
             /*$v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra." ORDER BY numero_acta_anulacion DESC");

		     if($v!=null && $sw >1){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",1)");
			    $numero=1;
		     }//fin else*/

			 //$R1 = $this->cepd02_contratoservicio_anticipo_cuerpo->execute("UPDATE cepd02_contratoservicio_anticipo_cuerpo SET  ano_anulacion=".date("Y").", numero_anulacion=".$numero." , condicion_actividad=2 ,fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'  WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano_orden_compra." and numero_contrato_servicio='".$numero_orden_compra."' and numero_anticipo=".$numero_orden_compra_anticipo);

			 $R1 = $this->cepd02_contratoservicio_anticipo_cuerpo->execute(" DELETE FROM cepd02_contratoservicio_anticipo_partidas   WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano_orden_compra." and numero_contrato_servicio='".$numero_orden_compra."' and numero_anticipo=".$numero_orden_compra_anticipo);
			 $R1 = $this->cepd02_contratoservicio_anticipo_cuerpo->execute(" DELETE FROM cepd02_contratoservicio_anticipo_cuerpo   WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano_orden_compra." and numero_contrato_servicio='".$numero_orden_compra."' and numero_anticipo=".$numero_orden_compra_anticipo);

			 $orden_pago = $this->cepd02_contratoservicio_anticipo_cuerpo->execute(" SELECT * FROM cepd03_ordenpago_cuerpo WHERE cod_tipo_documento=7 and condicion_actividad=2 and ".$this->SQLCA()." and numero_documento_origen='".$numero_orden_compra."' and numero_documento_adjunto='".$numero_orden_compra_anticipo."' and ano_documento_origen=".$ano_orden_compra);
 				foreach($orden_pago as $orden){
 				$ano_orden    = $orden[0]['ano_orden_pago'];
 				$numero_orden = $orden[0]['numero_orden_pago'];
 				$this->cepd02_contratoservicio_anticipo_cuerpo->execute(" DELETE FROM cepd03_ordenpago_cuerpo WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden);

						$orden_pago_cheque=$this->cepd02_contratoservicio_anticipo_cuerpo->execute(" SELECT * FROM cstd03_cheque_ordenes WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden);
				 		foreach($orden_pago_cheque as $orden_cheque){
 						$ano_movimiento       = $orden_cheque[0]['ano_movimiento'];
 						$cod_entidad_bancaria = $orden_cheque[0]['cod_entidad_bancaria'];
 						$cod_sucursal         = $orden_cheque[0]['cod_sucursal'];
 						$cuenta_bancaria      = $orden_cheque[0]['cuenta_bancaria'];
 						$numero_cheque        = $orden_cheque[0]['numero_cheque'];
 						$this->cepd02_contratoservicio_anticipo_cuerpo->execute(" DELETE FROM cstd03_cheque_cuerpo WHERE ".$this->SQLCA()." and ano_movimiento=".$ano_movimiento." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque=".$numero_cheque);
 						$this->cepd02_contratoservicio_anticipo_cuerpo->execute(" DELETE FROM cstd04_movimientos_generales WHERE ".$this->SQLCA()." and ano_movimiento=".$ano_movimiento." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_documento=4 and numero_documento=".$numero_cheque);
				 		}
				 }




              //$v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",".$numero.",".$tipo_documento.",".$ano_orden_compra.",'".$numero_orden_compra."','".$this->Cfecha($fecha_orden_compra, 'A-M-D')."','".$concepto_anulacion."')");


	                   $this->set('Message_existe', 'El registro fue eliminado correctamente');

					}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
					}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
					}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
					}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}


	}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}






/*
echo'<script>';
    echo'document.getElementById("guardar").disabled = true; ';
    echo'document.getElementById("anular").disabled = true; ';

    echo'document.getElementById("condicion_modificacion_1").checked = false;';
  	echo'document.getElementById("condicion_modificacion_2").checked = true;';

    echo'document.getElementById("a").innerHTML = "'.$ano_orden_compra.'"; ';
    echo'document.getElementById("b").innerHTML = "'.$numero.'"; ';
    echo'document.getElementById("c").innerHTML = "'.$fecha_proceso_anulacion.'"; ';
    echo'document.getElementById("d").innerHTML = "0"; ';
    echo'document.getElementById("e").innerHTML = "0"; ';
    echo'document.getElementById("f").innerHTML = "0"; ';  ///AQUI VA EL NUMERO DE ASIENTO PERO HAY QUE ESPERAR EL DE EL MOTOR
    echo'document.getElementById("g").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';

echo'</script>';
*/

$this->consulta_index('1');
$this->render('consulta_index');



}//fin function


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cepp02_registro_anticipo_contratoservicios']['login']) && isset($this->data['cepp02_registro_anticipo_contratoservicios']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cepp02_registro_anticipo_contratoservicios']['login']);
		$paswd=addslashes($this->data['cepp02_registro_anticipo_contratoservicios']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=88 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('msg_error',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}






}//fin class
?>