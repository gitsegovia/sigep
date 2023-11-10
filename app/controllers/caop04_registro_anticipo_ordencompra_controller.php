<?php
class Caop04registroanticipoordencompraController extends AppController {

   var $name = "caop04_registro_anticipo_ordencompra";
   var $uses = array('ccfd04_cierre_mes','cscd04_ordencompra_parametros', 'cscd04_ordencompra_encabezado', 'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo', 'cscd04_ordencompra_anticipo_partidas','ccfd03_instalacion', 'cscd04_ordencompra_partidas', 'cpcd02', 'cfpd22_numero_asiento_causado', 'cfpd22', 'cfpd05', 'cugd04','select_orden_compra','select_anticipo_compra','cugd05_restriccion_clave');
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
 	$lista = $this->select_orden_compra->generateList($condicion.' and condicion_actividad=1  and monto_cancelado=0'." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
 	$this->set('lista_numero', $lista);
 	$this->set('ano',$ano);
    $this->data = null;

 }//fin function





function incluye_iva($codigo=null, $var1=null){
    $this->layout = "ajax";
    $porcentaje_anticipo = 0;
    $factor_reversion = "";
    $porcentaje_iva = "0";

	if($var1 != null){
		$this->Session->write('incluye_iva', $var1);
	}
	//echo 'el incluye iva es : '.$this->Session->read('incluye_iva');
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
    $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
    foreach($parametros_datos as $aux_22){
      $porcentaje_anticipo = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
      $porcentaje_iva    = $aux_22['cscd04_ordencompra_parametros']['porcentaje_iva'];
     }//fin foreach

    $ano = $this->ano_ejecucion();
 	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';

     $porcentaje_iva = $this->cscd04_ordencompra_encabezado->field('cscd04_ordencompra_encabezado.porcentaje_iva', $condicion. " and cscd04_ordencompra_encabezado.numero_orden_compra='$codigo'", $order =null);
     //echo 'el porcentaje iva es: '.$porcentaje_iva;

// echo $porcentaje_iva;
if($porcentaje_iva==0){

    echo'<script>';
     echo'monto_anticipo("'.$var1.'", "0", "0");';
    echo'</script>';

}else{

	echo'<script>';
     echo'monto_anticipo("'.$var1.'", "'.$this->Formato_redondear($porcentaje_anticipo).'", "'.$this->Formato_redondear($porcentaje_iva).'");';
    echo'</script>';


}//fin else

//$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion);
//$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
//$this->render('datos');


}//fin function


function datos($cod=null, $var=null) {
	$this->layout = "ajax";
	$ano = $this->ano_ejecucion();
 	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
    $numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion." and numero_orden_compra='$cod'");
	$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
	$this->incluye_iva($cod, $var);

}//fin datos






function selecion($var1=null){
  $this->layout = "ajax";

 $ano='';
 $this->set('codigo', $var1);
 $ano = $this->ano_ejecucion();
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
 $lista = $this->select_orden_compra->generateList($condicion.' and condicion_actividad=1  and monto_cancelado=0'." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
 $this->set('lista_numero', $lista);

 $this->set('numero_orden_compra', $var1);
 $this->Session->delete('PAG_NUM');


if($var1 != null){
 	$incluye_iva = $this->cscd04_ordencompra_parametros->field('cscd04_ordencompra_parametros.anticipo_incluye_iva', $conditions = $this->SQLCA(), $order =null);
 	$por_anticipo = $this->cscd04_ordencompra_parametros->field('cscd04_ordencompra_parametros.porcentaje_anticipo', $conditions = $this->SQLCA(), $order =null);
 	if(empty($por_anticipo)){
 		$por_anticipo = 0;
 	}
 	$this->set('por_anticipo',  $por_anticipo);

 	$incluye_iva = 2; //para no usar la tabla de parametros por defecto se esta colocando 2
 	if(!empty($incluye_iva)){$this->incluye_iva($var1, $incluye_iva);}

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

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$var1.'  and  ano_orden_compra='.$ano.' ';
$numero_datos = $this->cscd04_ordencompra_encabezado->findAll($condicion);
$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion, null, 'numero_orden_compra DESC');

$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
	$ano_orden_compra = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
	$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
}//fin foreach

$opc     = $this->cscd04_ordencompra_anticipo_cuerpo->findCount($condicion.' and ano_orden_compra='.$ano_orden_compra.'  and numero_orden_compra='.$numero_orden_compra.'');
$result  = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($condicion."   and ano_orden_compra=".$ano_orden_compra."  and  numero_orden_compra=".$numero_orden_compra." ", null, "numero_anticipo ASC", null, null);
foreach($result as $ves){$opc = $ves['cscd04_ordencompra_anticipo_cuerpo']['numero_anticipo'];}//fin foreach



$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");

foreach($rif_datos as $aux_2){
	$denominacion_rif = $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
}//fin foreach
$opc++;


$this->set('ano_orden_compra_anticipo', $ano);
$this->set('numero_orden_compra_anticipo', $opc);
$this->set('datos_orden_compra', $numero_datos);
$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
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
  $ano_orden_compra         =       $this->data['caop04_registro_anticipo_ordencompra']['ano_orden_compra'];
  $ann = $ano_orden_compra;
  $numero_orden_compra      =       $this->data['caop04_registro_anticipo_ordencompra']['numero_orden_compra'];
  $numero_anticipo      =       $this->data['caop04_registro_anticipo_ordencompra']['numero_orden_compra_anticipo'];
  $nda = $numero_anticipo;
  $tipo_anticipo        =       $this->data['caop04_registro_anticipo_ordencompra']['incluye_iva'];
//  $tipo_anticipo        =  "2";

  $iva                  =       $this->Formato1($this->data['caop04_registro_anticipo_ordencompra']['iva']);
  $iva_anticipo         =       $this->Formato1($this->data['caop04_registro_anticipo_ordencompra']['iva_anticipo']);
  $monto_anticipo       =       $this->Formato1($this->data['caop04_registro_anticipo_ordencompra']['monto_anticipo']);
  $fecha_anticipo       =       $this->data['caop04_registro_anticipo_ordencompra']['fecha_anticipo'];
  $fd = $fecha_anticipo;
  $observaciones            =       $this->data['caop04_registro_anticipo_ordencompra']['observaciones'];


  $pregunta_ejercicio       =       $this->data['caop04_registro_anticipo_ordencompra']['pregunta_ejercicio'];



if(isset($ano_orden_compra)){
if(isset($numero_orden_compra)){
if(isset($numero_anticipo)){
if(isset($iva)){
if(isset($iva_anticipo)){
if(isset($monto_anticipo)){
if(isset($fecha_anticipo)){
if(isset($observaciones)){







  $ccp = $observaciones;
  $ano_asiento_registro= '0';
  $mes_asiento_registro='0';
  $dia_asiento_registro='0';
  $numero_asiento_registro='0';


   $fecha_proceso_registro=date('d/m/Y');
   $fecha_contrato_obra2 = $fd;
   $aux = $fecha_contrato_obra2[6].$fecha_contrato_obra2[7].$fecha_contrato_obra2[8].$fecha_contrato_obra2[9];
if($aux!=$ano_orden_compra){$fd = $fecha_proceso_registro;}



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
$numero_orden_compra2 = $numero_orden_compra;
$ano_orden_compra2 = $ano_orden_compra;
$sql="  BEGIN;  INSERT INTO cscd04_ordencompra_anticipo_cuerpo (cod_presi, cod_entidad, cod_tipo_inst ,cod_inst, cod_dep , ano_orden_compra, numero_orden_compra, numero_anticipo, fecha_anticipo, observaciones, ano_asiento_registro, mes_asiento_registro, dia_asiento_registro, numero_asiento_registro, fecha_proceso_registro, username_registro, condicion_actividad, ano_asiento_anulacion, mes_asiento_anulacion, dia_asiento_anulacion , numero_asiento_anulacion, username_anulacion, fecha_proceso_anulacion, ano_orden_pago, numero_orden_pago, porcentaje_anticipo, monto_anticipo, saldo_ano_anterior)";
$sql.="VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$numero_anticipo."', '".$this->Cfecha($fecha_anticipo, 'A-M-D')."', '".$observaciones."', '".$ano_asiento_registro."', '".$mes_asiento_registro."', '".$dia_asiento_registro."', '".$numero_asiento_registro."', '".$this->Cfecha($fecha_proceso_registro, 'A-M-D')."', '".$username_registro."', '".$condicion_actividad."', '".$ano_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$dia_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$username_anulacion."', '".$this->Cfecha($fecha_proceso_anulacion, 'A-M-D')."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$iva_anticipo."', '".$monto_anticipo."', '".$pregunta_ejercicio."');";
$swa= $this->cscd04_ordencompra_anticipo_cuerpo->execute($sql);
$i_lenght = $this->data['caop04_registro_anticipo_ordencompra']['cuenta_i'];
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra.' ';
/*if($tipo_anticipo==1){
	$condicion1 = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra.' ';
}else{
	$condicion1 = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra." and not(cod_partida=403 and cod_generica=18 and cod_especifica=1 and cod_sub_espec=0)";
}*/

$condicion1 = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra." and not(cod_partida=403 and cod_generica=18 and cod_especifica=1 and cod_sub_espec=0)";

if($swa>1){

			$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion1, null, 'numero_orden_compra DESC');
			//echo $condicion1;
			//print_r($numero_datos_partidas);
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
			  $anticipo2[$a]                  =   $aux_partidas['cscd04_ordencompra_partidas']['anticipo'];
			  $numero_compromiso              =   $aux_partidas['cscd04_ordencompra_partidas']['numero_asiento_compromiso'];

			 $concate[$a] = $this->AddCero2(substr($cod_partida[$a], -2), substr($cod_partida[$a], 0, 1 )).'.'.$this->AddCero2($cod_generica[$a]).'.'.$this->AddCero2($cod_especifica[$a]).'.'.$this->AddCero2($cod_sub_espec[$a]);
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

						for($i=0; $i<=$i_lenght; $i++){
						   if($partidas_aux==$this->data['partidas']['partidas_'.$i]){$partidas_vista['pago_'.$i_aux] = $this->data['caop04_registro_anticipo_ordencompra']['anticipo_'.$i]; $i_aux++;}
						}//fin foreach


			}//fin foreach

									$j =0;
									$numero_causado = 0;

			if($pregunta_ejercicio==2){
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

			}//fin function

			for($i=0; $i<count($numero_datos_partidas); $i++){
					       $var[$i]['monto']=$partidas_vista['pago_'.$i];

					if($var[$i]['monto']!="0,00"){
						$var[$i]['monto'] = $this->Formato1($var[$i]['monto']);


                              if(($var[$i]['monto']+$anticipo2[$i]) <= $monto2[$i] ){

													$rnco = $numero_compromiso;
													$rnca = $numero_causado;

                              	if($pregunta_ejercicio==2){

							              			$cp   = $this->crear_partida($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
													$to   = 1;
													$td   = 4;
													$ta   = 2;
													$mt   = $var[$i]['monto'];
													$ndo  = $numero_orden_compra3[$i];

												  // FAVOR NO BORRAR LA INSTRUCCION ABAJO COMENTADA
												  // $dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, $nda, null, null, null, null, null, null, $rnco, $rnca, null, null, $i);

								 }//fin if

										       $sql2  ="INSERT INTO cscd04_ordencompra_anticipo_partidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra, numero_anticipo, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica,  cod_sub_espec, cod_auxiliar,  monto, numero_control_compromiso, numero_control_causado) ";
										       $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_orden_compra3[$i]."', '".$numero_orden_compra3[$i]."', '".$numero_anticipo."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['monto']."', '".$rnco."', '$rnca'); ";
										       $sw = $this->cscd04_ordencompra_anticipo_partidas->execute($sql2);
											   $monto_anticipo_tipo2 = 0;
												$monto_anticipo_tipo2 = $anticipo2[$i] + $var[$i]['monto'];
												if($sw >1){
													$sql4  = "UPDATE cscd04_ordencompra_partidas SET anticipo= '".$monto_anticipo_tipo2."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_orden_compra=".$numero_orden_compra2."  and  ano_orden_compra=".$ano_orden_compra2." and ano=".$ano_partidas[$i]." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i]." ";
													$sw3 = $this->cscd04_ordencompra_partidas->execute($sql4);
													if($sw3 > 1){}else{/*$this->cscd04_ordencompra_partidas->execute("ROLLBACK;");*/ }//fin else
												}else{
													//$this->set('errorMessage', 'NO SE PUDO REGISTRAR EL ANTICIPO - POR FAVOR INTENTE DE NUEVO');
													break;
												}
											//}else{
												//echo 'NO entre aqui cuando concate='.$concate[$i].' tipo_anticipo='.$tipo_anticipo.'<br>';
											//}


                              }else{ $mensaje=1; $sw3=0;  break;  }

					  }//fin if
			}//fin for


         if($sw3 > 1){

						$numero_datos_partidas22 = $this->cscd04_ordencompra_encabezado->findAll($condicion);
						foreach($numero_datos_partidas22 as $aux_partidas22){
						  $monto_anticipo_aux             =   $aux_partidas22['cscd04_ordencompra_encabezado']['monto_anticipo'];
						}//fin for


						$monto_anticipo = $monto_anticipo + $monto_anticipo_aux;
//						$tipo_anticipo = "2";

						$sql3  = "UPDATE cscd04_ordencompra_encabezado SET monto_anticipo = '".$monto_anticipo."', porcentaje_iva='".$iva."', porcentaje_anticipo= porcentaje_anticipo +'".$iva_anticipo."', anticipo_con_iva='".$tipo_anticipo."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_orden_compra=".$numero_orden_compra2."  and  ano_orden_compra=".$ano_orden_compra2."; ";
						$swb = $this->cscd04_ordencompra_encabezado->execute($sql3);

 							if($swb > 1){


											////************************************** DESPUES DE GUARDAR ****************************************/////
											 $ano='';
											 $ano = $this->ano_ejecucion();
											 $ano_orden_compra2 = $ano;
											 $ano_partidas = $ano;
											 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
											 $lista = $this->cscd04_ordencompra_encabezado->generateList($condicion, ' numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.numero_orden_compra');
											 $this->AddCero('lista_numero', $lista);
											 $this->set('numero_orden_compra', $numero_orden_compra2);
											 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra2.'  and  ano_orden_compra='.$ano_orden_compra2.' ';
											 $numero_datos_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and rif='".$this->data['caop04_registro_anticipo_ordencompra']['rif']."' ");
											 $numero_datos_orden_compra_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion. " and ano=".$ano_partidas."  ");
											 $numero_datos_aux  =  $numero_datos_encabezado;
											foreach($numero_datos_aux as $aux){
												$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
												$ano_orden_compra    =  $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
												$numero_orden_compra =  $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
												$anticipo_con_iva    =  $aux['cscd04_ordencompra_encabezado']['anticipo_con_iva'];
												$porcentaje_iva      =  $aux['cscd04_ordencompra_encabezado']['porcentaje_iva'];
												$porcentaje_anticipo =  $aux['cscd04_ordencompra_encabezado']['porcentaje_anticipo'];
											}//fin foreach
											$opc = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($condicion.' and ano_orden_compra='.$ano_orden_compra.'  and numero_orden_compra='.$numero_orden_compra.' and numero_anticipo='.$numero_anticipo.'  ');
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
											$aux_monto_anticipo = $this->cscd04_ordencompra_anticipo_partidas->findAll($condicion.' and ano_orden_compra='.$ano_orden_compra.'  and numero_orden_compra='.$numero_orden_compra.' and numero_anticipo='.$numero_anticipo.'  '.$agrupar, $campos, null,  null, null);
											foreach($aux_monto_anticipo as $aux2_monto_anticipo){
												$monto_anticipo = $aux2_monto_anticipo[0]['monto'];
											}//fin foreach
											$this->set('year', $ano);
											$this->set('datos_orden_compra_anticipo_cuerpo', $opc);
											$this->set('datos_orden_compra_encabezado', $numero_datos_encabezado);
											$this->set('datos_orden_compra_partidas', $numero_datos_orden_compra_partidas);
											$this->set('denominacion_rif', $denominacion_rif);
											$this->set('direccion_comercial_rif', $direccion_comercial_rif);
											$this->set('monto_anticipo', $monto_anticipo);
											$this->set('anticipo_con_iva', $anticipo_con_iva);
											$this->set('porcentaje_anticipo', $porcentaje_anticipo);
											$this->set('porcentaje_iva', $porcentaje_iva);
											 $this->cscd04_ordencompra_anticipo_partidas->execute("COMMIT;");
											$this->set('Message_existe', 'Los datos fueron guardados correctamente');
											////************************************** DESPUES DE GUARDAR ****************************************/////

									}else{
						     	      $this->cscd04_ordencompra_anticipo_partidas->execute("ROLLBACK;");
						     	      $this->set('errorMessage', 'NO SE LOGRO REALIZAR EL ANTICIPO - POR FAVOR INTENTE DE NUEVO');
						     	  }//fin else

			}else{
     	      $this->cscd04_ordencompra_anticipo_partidas->execute("ROLLBACK;");
     	      if($mensaje==1){
				   $this->set('errorMessage', 'ANTICIPO NO PUEDE SER PROCESADO, FAVOR REVISE LAS PARTIDAS');
				}else if($mensaje==2){
				   $this->set('errorMessage', 'EL ANTICIPO NO PUEDO SER ALMACENADO - POR FAVOR INTENTE DE NUEVO');
				}//fin if
     	  }//fin else

}else{
$this->cscd04_ordencompra_anticipo_partidas->execute("ROLLBACK;");
$this->set('errorMessage', 'NO SE LOGRO REALIZAR EL ANTICIPO - POR FAVOR INTENTE DE NUEVO');
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
   $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


  $pag_num = 0;
  $opcion = 'si';

   if(!empty($this->data['caop04_registro_anticipo_ordencompra']['ano_ejecucion'])){
       	 $_SESSION['ano_compra'] = $this->data['caop04_registro_anticipo_ordencompra']['ano_ejecucion'];
   }else{$_SESSION['ano_compra'] = $this->ano_ejecucion();}


    $ano = $_SESSION['ano_compra'];

 $lista = $this->select_anticipo_compra->generateList($condicion."  and ano_orden_compra = ".$ano." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_anticipo_compra.numero_orden_compra', '{n}.select_anticipo_compra.beneficiario');
 $this->set('lista_numero', $lista);
 $this->set('ano', $this->ano_ejecucion());


if($var1!=null){

  if($var1=='si'){



if(!empty($this->data['caop04_registro_anticipo_ordencompra']['numero_orden_compra'] )){

  $array = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($condicion. " and numero_orden_compra='".$this->data['caop04_registro_anticipo_ordencompra']['numero_orden_compra']."' and ano_orden_compra = ".$ano , null, 'ano_orden_compra, numero_orden_compra, numero_anticipo ASC', null);
  $i = 0;
   foreach($array as $aux){
 	$numero[$i]['ano_orden_compra'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_anticipo'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_anticipo'];
 	$i++;
} $i--;

  for($a=0; $a<=$i; $a++){
    if($this->data['caop04_registro_anticipo_ordencompra']['numero_orden_compra'] == $numero[$a]['numero_orden_compra']){
    	$pag_num = 0;
    	$opcion='si';
    	$numero_documento = $numero[$a]['numero_orden_compra'];
    	 break;
    	 }else{$pag_num = 0;
    	      $opcion='si';
    	      $numero_documento = $numero[0]['numero_orden_compra'];}
   }//fin for

      if($opcion=='si'){$_SESSION['PAG_NUM']=$this->data['caop04_registro_anticipo_ordencompra']['numero_orden_compra']; $this->consulta($pag_num, $numero_documento);$this->render('consulta');
}else if($opcion=='no'){
	$this->set('errorMessage', 'No existen datos');
	}//fin else




                 }else{




$array = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($condicion. "  and ano_orden_compra = ".$ano , null, 'ano_orden_compra, numero_orden_compra, numero_anticipo ASC', null);
  $i = 0;
   foreach($array as $aux){
 	$numero[$i]['ano_orden_compra'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_anticipo'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_anticipo'];
 	$i++;
} $i--;

	$this->Session->delete('PAG_NUM');


	if($i<=0){
	  $this->set('errorMessage', 'No existen datos');
	  $this->consulta_index();
	  $this->render('consulta_index');
	}else{
	$this->consulta(0, $numero[0]['numero_orden_compra']); $this->render('consulta');}}//fin else



  }//fin if
}//fin if

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function












function buscar_year($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


  //$lista = $this->cscd04_ordencompra_anticipo_cuerpo->generateList($condicion." and ano_orden_compra=".$var1, ' numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_anticipo_cuerpo.numero_orden_compra', '{n}.cscd04_ordencompra_anticipo_cuerpo.numero_orden_compra');
  //$this->AddCero('compras', $lista);
  $lista = $this->select_anticipo_compra->generateList($condicion."  and ano_orden_compra = ".$var1." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_anticipo_compra.numero_orden_compra', '{n}.select_anticipo_compra.beneficiario');
  $this->set('compras', $lista);


}//fin function










function consulta($pag_num=null, $numero_documento=null, $g=null){

  $this->layout = "ajax";

   if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}
   $this->set('ano_compra', $ano);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
  $this->set('ano_ejecucion', $this->ano_ejecucion());



 $array = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($condicion. ' and ano_orden_compra = '.$ano.'  and numero_orden_compra='.$numero_documento , null, 'numero_anticipo ASC', null);

       $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$ano_documento = $numero[$i]['ano_orden_compra']          = $aux['cscd04_ordencompra_anticipo_cuerpo']['ano_orden_compra'];
 	$numero_orden_compra = $numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_anticipo']                            = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_anticipo'];
 	$fecha_documento                                          = $aux['cscd04_ordencompra_anticipo_cuerpo']['fecha_anticipo'];
 	$condicion_actividad                                      = $aux['cscd04_ordencompra_anticipo_cuerpo']['condicion_actividad'];
 	$tipo = "242";

 	$i++;

} $i--;


if($condicion_actividad==2){
			$ano_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.ano_acta_anulacion', $conditions = $this->condicion()." and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion='$tipo'", $order ="ano_acta_anulacion, numero_acta_anulacion ASC");
			$numero_acta_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.numero_acta_anulacion', $conditions = $this->condicion()." and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion='$tipo'", $order ="ano_acta_anulacion, numero_acta_anulacion ASC");
			$motivo_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.motivo_anulacion', $conditions = $this->condicion()." and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion='$tipo'", $order ="ano_acta_anulacion, numero_acta_anulacion ASC");
			//echo $conditions;
			//echo "el ANO_ANULACION ES: ".$ano_anulacion." el numero es: ".$numero_acta_anulacion." el motivo es: ".$motivo_anulacion;
}else{
			$ano_anulacion= "0";
			$numero_acta_anulacion="<br>";
			$motivo_anulacion = "";
}//fin else

		$this->set('ano_anulacion', $ano_anulacion);
		$this->set('numero_acta_anulacion', $numero_acta_anulacion);
		$this->set('motivo_anulacion',$motivo_anulacion);






if(isset($numero[$pag_num]['numero_orden_compra'])){



$datos_orden_compra_encabezado          =   $this->cscd04_ordencompra_encabezado->findAll($condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].'  ');
$datos_orden_compra_anticipo_cuerpo     =   $this->cscd04_ordencompra_anticipo_cuerpo->findAll($condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and numero_anticipo='.$numero[$pag_num]['numero_anticipo'].'  ');

$numero_datos_aux  =  $datos_orden_compra_encabezado;

foreach($numero_datos_aux as $aux){
	$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
	$anticipo_con_iva    = $aux['cscd04_ordencompra_encabezado']['anticipo_con_iva'];
	$porcentaje_iva      = $aux['cscd04_ordencompra_encabezado']['porcentaje_iva'];
	$porcentaje_anticipo = $aux['cscd04_ordencompra_encabezado']['porcentaje_anticipo'];
}//fin foreach+

$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){$denominacion_rif = $aux_2['cpcd02']['denominacion']; $direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];}//fin foreach
$campos = ' cod_presi, cod_entidad, cod_tipo_inst, cod_inst, SUM(monto) as "monto" ';
$agrupar = ' GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst';
$sql = $condicion.'  and  ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and  numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and numero_anticipo='.$numero[$pag_num]['numero_anticipo'].'  '.$agrupar;
$sql2 = $condicion.'  and  ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and  numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and numero_anticipo='.$numero[$pag_num]['numero_anticipo'].'  ';
$aux_monto_anticipo = $this->cscd04_ordencompra_anticipo_partidas->findAll($sql, $campos, null,  null, null);
$numero_datos_orden_compra_partidas = $this->cscd04_ordencompra_anticipo_partidas->findAll($sql2);
foreach($aux_monto_anticipo as $aux2_monto_anticipo){ $monto_anticipo = $aux2_monto_anticipo[0]['monto']; }//fin foreach
 $this->set('monto_anticipo', $monto_anticipo);
 $this->set('anticipo_con_iva', $anticipo_con_iva);
 $this->set('porcentaje_anticipo', $porcentaje_anticipo);
 $this->set('porcentaje_iva', $porcentaje_iva);
 $this->set('denominacion_rif', $denominacion_rif);
 $this->set('datos_orden_compra_anticipo_partidas', $numero_datos_orden_compra_partidas);
 $this->set('direccion_comercial_rif', $direccion_comercial_rif);
 $this->set('datos_orden_compra_encabezado', $datos_orden_compra_encabezado);
 $this->set('datos_orden_compra_anticipo_cuerpo', $datos_orden_compra_anticipo_cuerpo);
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

	if(isset($this->data["caop04_registro_anticipo_ordencompra"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		     $tipo_documento           =  242;

			 //$concepto_anulacion       =  $this->data["caop04_registro_anticipo_ordencompra"]["concepto_anulacion"];
			 $concepto_anulacion = "";
			 $concepto = $concepto_anulacion;
			 $fecha_proceso_anulacion  =  date("d/m/Y");

			 $condicion_documento      =  2;//cuando se guarda es Activo=1

			 $ano_orden_compra    = $this->data["caop04_registro_anticipo_ordencompra"]["ano_orden_compra"];
			 $numero_orden_compra = $this->data["caop04_registro_anticipo_ordencompra"]["numero_orden_compra"];
			 $fecha_orden_compra  = $this->data["caop04_registro_anticipo_ordencompra"]["fecha_anticipo"];

			 $fecha_contrato      = $this->data["caop04_registro_anticipo_ordencompra"]["fecha_anticipo2"];
			 $fd = $fecha_contrato;

			 $numero_orden_compra_anticipo = $this->data["caop04_registro_anticipo_ordencompra"]["numero_orden_compra_anticipo"];
			 $porcentaje_anticipo          = $this->Formato1($this->data["caop04_registro_anticipo_ordencompra"]["porcentaje_anticipo"]);
			 $pregunta_ejercicio           = $this->data['caop04_registro_anticipo_ordencompra']['pregunta_ejercicio'];




if(isset($ano_orden_compra)){
if(isset($numero_orden_compra)){
if(isset($fecha_orden_compra)){
if(isset($numero_orden_compra_anticipo)){









			 $datos_partidas = $this->cscd04_ordencompra_anticipo_partidas->findAll($conditions = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and numero_anticipo='$numero_orden_compra_anticipo'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			 $num_base = count($datos_partidas);
			 //print_r($datos_partidas);
			$monto_anticipo = 0;
			$sql_update_cscd04_partidas = '';

			 foreach($datos_partidas as $row){

			 	$ano = $row['cscd04_ordencompra_anticipo_partidas']['ano'];
			 	$cod_sector = $row['cscd04_ordencompra_anticipo_partidas']['cod_sector'];
			 	$cod_programa = $row['cscd04_ordencompra_anticipo_partidas']['cod_programa'];
			 	$cod_sub_prog = $row['cscd04_ordencompra_anticipo_partidas']['cod_sub_prog'];
			 	$cod_proyecto = $row['cscd04_ordencompra_anticipo_partidas']['cod_proyecto'];
			 	$cod_activ_obra = $row['cscd04_ordencompra_anticipo_partidas']['cod_activ_obra'];
			 	$cod_partida = $row['cscd04_ordencompra_anticipo_partidas']['cod_partida'];
			 	$cod_generica = $row['cscd04_ordencompra_anticipo_partidas']['cod_generica'];
			 	$cod_especifica = $row['cscd04_ordencompra_anticipo_partidas']['cod_especifica'];
			 	$cod_sub_espec = $row['cscd04_ordencompra_anticipo_partidas']['cod_sub_espec'];
			 	$cod_auxiliar = $row['cscd04_ordencompra_anticipo_partidas']['cod_auxiliar'];
			 	$monto_partida = $row['cscd04_ordencompra_anticipo_partidas']['monto'];
			 	$numero_control_compromiso = $row['cscd04_ordencompra_anticipo_partidas']['numero_control_compromiso'];
			 	$numero_control_causado = $row['cscd04_ordencompra_anticipo_partidas']['numero_control_causado'];
			 	$cond1 = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

				$monto_anticipo += $monto_partida;

					if($pregunta_ejercicio==2){

							   // FAVOR NO BORRAR LA INSTRUCCION COMENTADA ABAJO
					           // $num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 4, 2, date("d/m/Y"), $monto_partida, $concepto, $ano, $numero_orden_compra, $numero_orden_compra_anticipo, null, null, null, null, null, null, $numero_control_compromiso, $numero_control_causado, null, null, null);

					}//fin if

			 	$sql_update_cscd04_partidas .= "UPDATE cscd04_ordencompra_partidas SET anticipo=anticipo-'$monto_partida' WHERE ".$cond1.";";

			 }//fin foreach



			 $cond2 = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra'";
			 $sql_update_cscd04_encabezado ="UPDATE cscd04_ordencompra_encabezado SET porcentaje_anticipo= porcentaje_anticipo - ".$porcentaje_anticipo.",  monto_anticipo=monto_anticipo-'$monto_anticipo' WHERE ".$cond2.";";

				$sw = $this->cscd04_ordencompra_encabezado->execute($sql_update_cscd04_partidas.$sql_update_cscd04_encabezado);

			 //$numero_anulacion=$this->cugd03_acta_anulacion_numero->execute($condicion);
            /* $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra." ORDER BY numero_acta_anulacion DESC");

		     if($v!=null && $sw >1){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano_orden_compra."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",1)");
			    $numero=1;
		     }//fin else*/
			 //$R1 = $this->cscd04_ordencompra_anticipo_cuerpo->execute("UPDATE cscd04_ordencompra_anticipo_cuerpo SET condicion_actividad=".$condicion_documento.", fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'  WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_anticipo=".$numero_orden_compra_anticipo);

			 $R1 = $this->cscd04_ordencompra_anticipo_cuerpo->execute("DELETE FROM cscd04_ordencompra_anticipo_partidas  WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_anticipo=".$numero_orden_compra_anticipo);
			 $R1 = $this->cscd04_ordencompra_anticipo_cuerpo->execute("  DELETE FROM cscd04_ordencompra_anticipo_cuerpo    WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_anticipo=".$numero_orden_compra_anticipo);


		     //$v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",".$numero.",".$tipo_documento.",".$ano_orden_compra.",".$numero_orden_compra.",'".$this->Cfecha($fecha_orden_compra, 'A-M-D')."','".$concepto_anulacion."')");

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
    echo'document.getElementById("d").innerHTML = "0"; ';
    echo'document.getElementById("e").innerHTML = "0"; ';
    echo'document.getElementById("f").innerHTML = "0"; ';  ///AQUI VA EL NUMERO DE ASIENTO PERO HAY QUE ESPERAR EL DE EL MOTOR
    echo'document.getElementById("g").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';

echo'</script>';*/



}//fin function



function entrar(){
	$this->layout="ajax";
	if(isset($this->data['caop04_registro_anticipo_ordencompra']['login']) && isset($this->data['caop04_registro_anticipo_ordencompra']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['caop04_registro_anticipo_ordencompra']['login']);
		$paswd=addslashes($this->data['caop04_registro_anticipo_ordencompra']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=82 and clave='".$paswd."'";
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