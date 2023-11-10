<?php
class Cobp01ContratoobrasAnticipoController extends AppController {

   var $name = "cobp01_contratoobras_anticipo";
   var $uses = array('cobd01_contratoobras_partidas','cobd01_contratoobras_anticipo_cuerpo','cscd04_ordencompra_parametros', 'cscd04_ordencompra_encabezado', 'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo', 'cscd04_ordencompra_anticipo_partidas','ccfd03_instalacion', 'cscd04_ordencompra_partidas', 'cpcd02','cobd01_contratoobras_cuerpo');
   var $helpers = array('Html','Ajax','Javascript','Sisap');

     function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }


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
 $ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = null;
 foreach($year as $year){
 	$ano = $year['ccfd03_instalacion']['ano_arranque'];
 }
 	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_estimacion='.$ano.'';
 	$lista = $this->cobd01_contratoobras_cuerpo->generateList($condicion.' and condicion_actividad=1', ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
 	$this->AddCero('lista_numero', $lista);

$this->set('ano',$ano);
 }//fin function





function incluye_iva($codigo=null, $var1=null){ //echo "si entro a la funcion";
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
     $porcentaje_iva = $this->cscd04_ordencompra_encabezado->field('cscd04_ordencompra_encabezado.porcentaje_iva', $conditions = $this->SQLCA(). "and cscd04_ordencompra_encabezado.numero_orden_compra='$codigo'", $order =null);
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

$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion);
//$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
//$this->render('datos');


}//fin function


function datos($cod=null, $var=null) {
	$this->layout = "ajax";
	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
	$numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion." and numero_contrato_obra='$cod'");
	$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
	$this->incluye_iva($cod, $var);

}


function selecion($var1=null){//echo "llego ".$var1;
  $this->layout = "ajax";

 $ano='';
 $this->set('codigo', $var1);
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = null;
 foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_contrato_obra='.$ano.'';
 $lista = $this->cobd01_contratoobras_cuerpo->generateList($condicion.' and condicion_actividad=1', ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
 $this->AddCero('lista_numero', $lista);//para volver a armar la lista
 $condiciont = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and numero_contrato_obra='.$var1;

    $numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion." and numero_contrato_obra='$var1'");
	$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
//	$this->incluye_iva($var1, $var);



 $cuerpo=$this->cobd01_contratoobras_cuerpo->findAll($condiciont.' and condicion_actividad=1');
 //print_r($cuerpo);
 $this->set('cuerpo',$cuerpo);
 $this->set('numero_contrato', $var1);//numero contrato
 if($var1 != null){//parametros iva
 	$incluye_iva = $this->cscd04_ordencompra_parametros->field('cscd04_ordencompra_parametros.anticipo_incluye_iva', $conditions = $this->SQLCA(), $order =null);
 	echo "el incluye iva es: ".$incluye_iva;
 	$porcentaje_iva = $this->cscd04_ordencompra_parametros->field('cscd04_ordencompra_parametros.porcentaje_iva', $conditions = $this->SQLCA(), $order =null);
 	echo "el porcentaje iva es: ".$porcentaje_iva;
 	$this->set('porcentaje_iva',$porcentaje_iva);
 	$por_anticipo = $this->cscd04_ordencompra_parametros->field('cscd04_ordencompra_parametros.porcentaje_anticipo', $conditions = $this->SQLCA(), $order =null);
 	if(empty($por_anticipo)){
 		$por_anticipo = 0;
 	}
 	$this->set('por_anticipo',  $por_anticipo);

 	echo "y la var1 es:".$var1;

 	/*if(!empty($incluye_iva)){
 		$this->incluye_iva($var1, $incluye_iva);
 		//echo "entro incluye";
 	}*/
 }


if($var1==null){

$this->index();
$this->render('index');

}else{



//////***********       LOS DEL PORCENTAJE         *****************//////////

$porcentaje_anticipo = 0;
$factor_reversion = "";
$anticipo_incluye_iva = "";
$porcentaje_iva = 0;


 $condicion2 = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
 $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion2);
    foreach($parametros_datos as $aux_22){
      $anticipo_incluye_iva = $aux_22['cscd04_ordencompra_parametros']['anticipo_incluye_iva'];
      $porcentaje_anticipo = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
      $porcentaje_iva    = $aux_22['cscd04_ordencompra_parametros']['porcentaje_iva'];
    }//fin foreach

$this->set('anticipo_incluye_iva', $anticipo_incluye_iva);



//////***********       FIN LOS DEL PORCENTAJE         *****************//////////

//$condicion2 = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_contrato_obra='.$var1.'  and  ano_contrato_obra='.$ano.' ';
$numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
$numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion);
//print_r($numero_datos_partidas);
//print_r($numero_datos);
foreach($numero_datos as $aux){
	$rif = $aux['cobd01_contratoobras_cuerpo']['rif'];
	$ano_contrato_obra = $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];//echo "el ano es el".$ano_contrato_obra;
	//$numero_contrato_obra = $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
}//fin foreach

$a = $this->cpcd02->findAll("rif='".$rif."'",array('denominacion'));//echo "saul".$rif;
//print_r($a);
$this->set("denominacion_rif",$a[0]['cpcd02']['denominacion']);

$opc = $this->cobd01_contratoobras_anticipo_cuerpo->findCount($condicion.' and ano_contrato_obra='.$ano_contrato_obra.'  and numero_contrato_obra='.$var1.'');
$opc++;
$this->set('numero_contratoobra_anticipo', $opc);
$this->set('ano_contratoobras_anticipo', $ano);

}//fin else*/

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
print_r($this->data['cobp01_contratoobras_anticipo']);
/*
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano_orden_compra         =       $this->data['cscp04_registro_anticipo_ordencompra']['ano_orden_compra'];
  $numero_orden_compra      =       $this->data['cscp04_registro_anticipo_ordencompra']['numero_orden_compra'];
  $numero_anticipo      =       $this->data['cscp04_registro_anticipo_ordencompra']['numero_orden_compra_anticipo'];
  $tipo_anticipo        =       $this->data['cscp04_registro_anticipo_ordencompra']['incluye_iva'];
  $iva                  =       $this->Formato1($this->data['cscp04_registro_anticipo_ordencompra']['iva']);
  $iva_anticipo         =       $this->Formato1($this->data['cscp04_registro_anticipo_ordencompra']['iva_anticipo']);
  $monto_anticipo       =       $this->Formato1($this->data['cscp04_registro_anticipo_ordencompra']['monto_anticipo']);
  $fecha_anticipo       =       $this->data['cscp04_registro_anticipo_ordencompra']['fecha_anticipo'];
  $observaciones            =       $this->data['cscp04_registro_anticipo_ordencompra']['observaciones'];
  $ano_asiento_registro= '0';
  $mes_asiento_registro='0';
  $dia_asiento_registro='0';
  $numero_asiento_registro='0';
  $fecha_proceso_registro='10/10/2008';
  $username_registro='0';
  $condicion_actividad='1';
  $ano_asiento_anulacion='0';
  $mes_asiento_anulacion='0';
  $dia_asiento_anulacion='0';
  $numero_asiento_anulacion='0';
  $username_anulacion='0';
  $fecha_proceso_anulacion='10/10/2008';
  $ano_orden_pago= "0";
  $numero_orden_pago="0";
$numero_orden_compra2 = $numero_orden_compra;
$ano_orden_compra2 = $ano_orden_compra;
$sql="INSERT INTO cscd04_ordencompra_anticipo_cuerpo (cod_presi, cod_entidad, cod_tipo_inst ,cod_inst, cod_dep , ano_orden_compra, numero_orden_compra, numero_anticipo, fecha_anticipo, observaciones, ano_asiento_registro, mes_asiento_registro, dia_asiento_registro, numero_asiento_registro, fecha_proceso_registro, username_registro, condicion_actividad, ano_asiento_anulacion, mes_asiento_anulacion, dia_asiento_anulacion , numero_asiento_anulacion, username_anulacion, fecha_proceso_anulacion, ano_orden_pago, numero_orden_pago)";
$sql.="VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$numero_anticipo."', '".$fecha_anticipo."', '".$observaciones."', '".$ano_asiento_registro."', '".$mes_asiento_registro."', '".$dia_asiento_registro."', '".$numero_asiento_registro."', '".$fecha_proceso_registro."', '".$username_registro."', '".$condicion_actividad."', '".$ano_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$dia_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$username_anulacion."', '".$fecha_proceso_anulacion."', '".$ano_orden_pago."', '".$numero_orden_pago."')";
$this->cscd04_ordencompra_anticipo_cuerpo->execute($sql);
$i_lenght = $this->data['cscp04_registro_anticipo_ordencompra']['cuenta_i'];
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra.' ';
if($tipo_anticipo==1){
	$condicion1 = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra.' ';
}else{
	$condicion1 = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra.'  and  ano_orden_compra='.$ano_orden_compra." and cod_partida!=403 and cod_especifica!=18 and cod_sub_espec!=1";
}


$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion1);
$a=0;
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
  if($aux_partidas['cscd04_ordencompra_partidas']['numero_control_compromiso'] != null){
  	$numero_control_compromiso[$a]  =   $aux_partidas['cscd04_ordencompra_partidas']['numero_control_compromiso'];
  }else{
  	$numero_control_compromiso[$a]  =  0;
  }
  //$numero_control_compromiso[$a]  =   $aux_partidas['cscd04_ordencompra_partidas']['numero_control_compromiso'];

 //$concate[$a] = $this->AddCero2(substr($cod_partida[$a], -2), substr($cod_partida[$a], 0, 1 )).'.'.$this->AddCero2($cod_generica[$a]).'.'.$this->AddCero2($cod_especifica[$a]).'.'.$this->AddCero2($cod_sub_espec[$a]);

$a++;
}//fin foreach
for($i=0; $i<count($numero_datos_partidas); $i++){
       $var[$i]['monto']=$this->data['cscp04_registro_anticipo_ordencompra']['anticipo_'.$i];
if($var[$i]['monto']!="0,00"){
	$var[$i]['monto'] = $this->Formato1($var[$i]['monto']);
	//echo 'concate='.$concate[$i].' tipo_anticipo='.$tipo_anticipo.'<br>';
	//if(($concate[$i] != "4.03.18.01.00" &&  $tipo_anticipo== 2) || ($concate[$i] != "4.03.18.01.00" &&  $tipo_anticipo== 1) || ($concate[$i] == "4.03.18.01.00" &&  $tipo_anticipo== 1)){
		//echo 'entre aqui cuando concate='.$concate[$i].' tipo_anticipo='.$tipo_anticipo.'<br>';
       $sql2  ="INSERT INTO cscd04_ordencompra_anticipo_partidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra, numero_anticipo, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica,  cod_sub_espec, cod_auxiliar,  monto, numero_control_compromiso) ";
       $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_orden_compra3[$i]."', '".$numero_orden_compra3[$i]."', '".$numero_anticipo."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['monto']."', '".$numero_control_compromiso[$i]."') ";
       $sw = $this->cscd04_ordencompra_anticipo_partidas->execute($sql2);
	   $monto_anticipo_tipo2 = 0;
		$monto_anticipo_tipo2 = $anticipo2[$i] + $var[$i]['monto'];
		if($sw >1){
			$sql4  = "UPDATE cscd04_ordencompra_partidas SET anticipo= '".$monto_anticipo_tipo2."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_orden_compra=".$numero_orden_compra2."  and  ano_orden_compra=".$ano_orden_compra2." and ano=".$ano_partidas[$i]." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i]." ";
			$this->cscd04_ordencompra_partidas->execute($sql4);
		}else{
			$this->set('errorMessage', 'NO SE PUDO REGISTRAR EL ANTICIPO - POR FAVOR INTENTE DE NUEVO');
		}
	//}else{
		//echo 'NO entre aqui cuando concate='.$concate[$i].' tipo_anticipo='.$tipo_anticipo.'<br>';
	//}
  }//fin if
}//fin for


$numero_datos_partidas22 = $this->cscd04_ordencompra_encabezado->findAll($condicion);
foreach($numero_datos_partidas22 as $aux_partidas22){
  $monto_anticipo_aux             =   $aux_partidas22['cscd04_ordencompra_encabezado']['monto_anticipo'];
}//fin for


$monto_anticipo = $monto_anticipo + $monto_anticipo_aux;

$sql3  = "UPDATE cscd04_ordencompra_encabezado SET monto_anticipo='".$monto_anticipo."', porcentaje_iva='".$iva."', porcentaje_anticipo='".$iva_anticipo."', anticipo_con_iva='".$tipo_anticipo."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_orden_compra=".$numero_orden_compra2."  and  ano_orden_compra=".$ano_orden_compra2." ";
$this->cscd04_ordencompra_encabezado->execute($sql3);




////************************************** DESPUES DE GUARDAR ****************************************/////
/* $ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = null;
 foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
 $lista = $this->cscd04_ordencompra_encabezado->generateList($condicion, ' numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.numero_orden_compra');
 $this->AddCero('lista_numero', $lista);
 $this->set('numero_orden_compra', $numero_orden_compra2);
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero_orden_compra2.'  and  ano_orden_compra='.$ano_orden_compra2.' ';
 $numero_datos_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and rif='".$this->data['cscp04_registro_anticipo_ordencompra']['rif']."' ");
 $numero_datos_orden_compra_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion. " and ano=".$ano_partidas[0]."  ");
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
$this->set('Message_existe', 'Los datos fueron guardados correctamente');
////************************************** DESPUES DE GUARDAR ****************************************/////


}//fin funtion guardar




function consulta_index($var1=null){

  $this->layout = "ajax";
  $pag_num = 0;
  $opcion = 'si';


if($var1!=null){

  if($var1=='si'){

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
  $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
  $ano = null;
  foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}

   $array = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($condicion. 'and ano_orden_compra = '.$ano , 'DISTINCT ano_orden_compra, numero_orden_compra, numero_anticipo ', 'ano_orden_compra, numero_orden_compra, numero_anticipo ASC', null);
  $i = 0;
   foreach($array as $aux){
 	$numero[$i]['ano_orden_compra'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_anticipo'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_anticipo'];
 	$i++;
} $i--;


if(!empty($this->data['cscp04_registro_anticipo_ordencompra']['numero_orden_compra'] )){
  for($a=0; $a<=$i; $a++){
    if($this->data['cscp04_registro_anticipo_ordencompra']['numero_orden_compra'] == $numero[$i]['numero_orden_compra']){$pag_num = $a; $opcion='si'; break;}else{$opcion='no';}
   }//fin for

      if($opcion=='si'){$this->consulta($pag_num);$this->render('consulta');
}else if($opcion=='no'){
	$this->set('errorMessage', 'No existen datos');
	}//fin else

}else{$this->consulta($pag_num);$this->render('consulta');}//fin else



  }//fin if
}//fin if

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function








function consulta($pag_num=null){
  $this->layout = "ajax";

   $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
   $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
  $ano = null;
  foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}


   $array = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($condicion. 'and ano_orden_compra = '.$ano , 'DISTINCT ano_orden_compra, numero_orden_compra, numero_anticipo ', 'ano_orden_compra, numero_orden_compra, numero_anticipo ASC', null);

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_orden_compra'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['ano_orden_compra'];
 	$numero[$i]['numero_orden_compra'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_orden_compra'];
 	$numero[$i]['numero_anticipo'] = $aux['cscd04_ordencompra_anticipo_cuerpo']['numero_anticipo'];

 	$i++;

} $i--;

if(isset($numero[$pag_num]['numero_orden_compra'])){



$datos_orden_compra_encabezado          =   $this->cscd04_ordencompra_encabezado->findAll($condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].'  ');
$datos_orden_compra_anticipo_cuerpo =   $this->cscd04_ordencompra_anticipo_cuerpo->findAll($condicion.' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].'  and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and numero_anticipo='.$numero[$pag_num]['numero_anticipo'].'  ');

$numero_datos_aux  =  $datos_orden_compra_encabezado;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
	$anticipo_con_iva = $aux['cscd04_ordencompra_encabezado']['anticipo_con_iva'];
	$porcentaje_iva = $aux['cscd04_ordencompra_encabezado']['porcentaje_iva'];
	$porcentaje_anticipo = $aux['cscd04_ordencompra_encabezado']['porcentaje_anticipo'];
}//fin foreach
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

	if(isset($this->data["cscp04_registro_anticipo_ordencompra"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		     $tipo_documento           =  2;

			 $concepto_anulacion       =  $this->data["cscp04_registro_anticipo_ordencompra"]["concepto_anulacion"];
			 $fecha_proceso_anulacion  =  date("d/m/Y");

			 $condicion_documento      =  2;//cuando se guarda es Activo=1

			 $ano_orden_compra    = $this->data["cscp04_registro_anticipo_ordencompra"]["ano_orden_compra"];
			 $numero_orden_compra = $this->data["cscp04_registro_anticipo_ordencompra"]["numero_orden_compra"];
			 $fecha_orden_compra  = $this->data["cscp04_registro_anticipo_ordencompra"]["fecha_anticipo"];


			 $numero_orden_compra_anticipo = $this->data["cscp04_ordencompra_modificacion"]["numero_orden_compra_anticipo"];

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
			 $R1 = $this->cscd04_ordencompra_anticipo_cuerpo->execute("UPDATE cscd04_ordencompra_anticipo_cuerpo SET ano_asiento_anulacion=".date("Y").", numero_asiento_anulacion=".$numero.", condicion_actividad=".$condicion_documento.", mes_asiento_anulacion=".date("m").",  dia_asiento_anulacion=".date("d").",  fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'  WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_anticipo=".$numero_orden_compra_anticipo);
		     $v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_documento,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",".$numero.",".$tipo_documento.",".$ano_orden_compra.",".$numero_orden_compra.",'".$fecha_orden_compra."','".$concepto_anulacion."')");

	}//fin if



$this->set('Message_existe', 'Los registro fue anulado correctamente');


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



}//fin function














}//fin class
?>