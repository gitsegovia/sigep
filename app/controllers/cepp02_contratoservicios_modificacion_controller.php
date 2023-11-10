<?php


class cepp02contratoserviciosmodificacionController extends AppController {

   var $name = "cepp02_contratoservicios_modificacion";
   var $uses = array('ccfd03_instalacion', 'cepd02_contratoservicio_cuerpo', 'cfpd07_obras_cuerpo', 'ccfd04_cierre_mes',
                     'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cepd02_cs_modificacion_cuerpo',
                     'cepd02_cs_modificacion_partidas', 'cepd02_contratoservicio_partidas', 'cpcd02', 'v_cfpd05_disponibilidad',
                     'cfpd21_numero_asiento_compromiso', 'cfpd21', 'cfpd05', 'cugd04', 'v_cepd02_contratoservicio_cuerpo', 'v_cepd02_contratoservicio_modificacion_cuerpo',

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

function Formato2($monto){
    	return number_format($monto,2,",",".");
    }






function filtra_obra(){



$cod_dep                  =       $this->Session->read('SScoddep');
$SScoddeporig             =       $this->Session->read('SScoddeporig');
$Modulo                   =       $this->Session->read('Modulo');



$sql_obra = "";

return $sql_obra;


}//fin function




 function index(){
$this->layout = "ajax";
 $SScoddeporig             =       $this->Session->read('SScoddeporig');
 $SScoddep                 =       $this->Session->read('SScoddep');
 $Modulo                   =       $this->Session->read('Modulo');
 $lista = "";

$this->data=null;
 $ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $ano = $this->ano_ejecucion();
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_contrato_servicio='.$ano.'';
 //$lista = $this->cepd02_contratoservicio_cuerpo->generateList($condicion.' and condicion_actividad=1', ' numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio');


$lista = $this->v_cepd02_contratoservicio_cuerpo->generateList($condicion.' and condicion_actividad=1 and ((monto_original_contrato+aumento)-(disminucion+monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0', ' numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.v_cepd02_contratoservicio_cuerpo.deno_numero_contrato_con_rif');




 $this->set('lista_numero', $lista);
 $ano = $this->ano_ejecucion();
 $this->set('year', $ano);

 $this->Session->delete("ano_contrato_servicio");


 }//fin function





function selecion($var1=null){
  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');

 $ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $ano = $this->ano_ejecucion();


 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_contrato_servicio='.$ano.'';

$lista = $this->cepd02_contratoservicio_cuerpo->generateList($condicion.' and condicion_actividad=1 and ((monto_original_contrato+aumento)-(disminucion+monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0', ' numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio');

 $this->set('lista_numero', $lista);

 $this->set('numero_orden_compra', $var1);
 $this->Session->delete('PAG_NUM');


if($var1==null){

$this->index();
$this->render('index');

}else{

$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_servicio='".$var1."'  and  ano_contrato_servicio=".$ano."";
$numero_datos = $this->cepd02_contratoservicio_cuerpo->findAll($condicion);
$numero_datos_partidas = $this->cepd02_contratoservicio_partidas->findAll($condicion, null, 'numero_contrato_servicio DESC');

$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cepd02_contratoservicio_cuerpo']['rif'];
	$ano_orden_compra = $aux['cepd02_contratoservicio_cuerpo']['ano_contrato_servicio'];
	$numero_orden_compra = $aux['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio'];
	$this->Session->write("contrato_servicio", $numero_orden_compra);
}//fin foreach

$opc     = $this->cepd02_cs_modificacion_cuerpo->findCount($condicion." and ano_contrato_servicio=".$ano_orden_compra."  and  numero_contrato_servicio='".$numero_orden_compra."'");
$result  = $this->cepd02_cs_modificacion_cuerpo->findAll($condicion."   and ano_contrato_servicio=".$ano_orden_compra."  and  numero_contrato_servicio='".$numero_orden_compra."' ", null, "numero_modificacion ASC", null, null);
foreach($result as $ves){$opc = $ves['cepd02_cs_modificacion_cuerpo']['numero_modificacion'];}//fin foreach

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
        $numero_contrato = $this->Session->read("contrato_servicio");

        if ($tipo==1){
        	$condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and  numero_contrato_servicio='" . $numero_contrato . "'  and  ano_contrato_servicio=" . $ano . "";
            $numero_datos_partidas_7 = $this->cepd02_contratoservicio_partidas->findAll($condicion, null, 'numero_contrato_servicio DESC');

            foreach ($numero_datos_partidas_7 as $aux_partidas_7) {
                $cod_sector = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_sector'];
                $cod_programa = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_programa'];
                $cod_sub_prog = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_sub_prog'];
                $cod_proyecto = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_proyecto'];
                $cod_activ_obra = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_activ_obra'];
                $cod_partida = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_partida'];
                $cod_generica = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_generica'];
                $cod_especifica = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_especifica'];
                $cod_sub_espec = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_sub_espec'];
                $cod_auxiliar = $aux_partidas_7['cepd02_contratoservicio_partidas']['cod_auxiliar'];
                $dispo = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
                $disponibilidad = ($disponibilidad + $dispo);
            }//fin foreach

			$disponibilidad = $this->Formato2($disponibilidad);
            $disponibilidad = $this->Formato1($disponibilidad);

            if ($monto > $disponibilidad) {
                $this->set('msg_error', 'No hay suficiente disponibilidad para realizar el Aumento -  Monto Disponible: ' . $this->Formato2($disponibilidad));

                echo "<script> document.getElementById('guardar').disabled=true; </script>";
				echo "<script> document.getElementById('aumento').value='0,00'; </script>";
            }else{
            	echo "<script> document.getElementById('guardar').disabled=false; </script>";
            }

        }else{
            $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and  numero_contrato_servicio='" . $numero_contrato . "'  and  ano_contrato_servicio=" . $ano . "";
            $numero_datos_cuerpo_6 = $this->cepd02_contratoservicio_cuerpo->findAll($condicion, null, 'numero_contrato_servicio DESC');

            foreach ($numero_datos_cuerpo_6 as $aux_cuerpo_6) {

                $monto_6 = $aux_cuerpo_6['cepd02_contratoservicio_cuerpo']['monto_original_contrato'];
                $aumento_6 = $aux_cuerpo_6['cepd02_contratoservicio_cuerpo']['aumento'];
                $disminucion_6 = $aux_cuerpo_6['cepd02_contratoservicio_cuerpo']['disminucion'];
                $amortizacion_6 = $aux_cuerpo_6['cepd02_contratoservicio_cuerpo']['monto_amortizacion'];
                $retencion_laboral_6 = $aux_cuerpo_6['cepd02_contratoservicio_cuerpo']['monto_retencion_laboral'];
                $retencion_fielcumplimiento_6 = $aux_cuerpo_6['cepd02_contratoservicio_cuerpo']['monto_retencion_fielcumplimiento'];
                $cancelado_6 = $aux_cuerpo_6['cepd02_contratoservicio_cuerpo']['monto_cancelado'];
                $disponibilidad = (($monto_6 + $aumento_6) - ($disminucion_6 + $amortizacion_6 + $retencion_laboral_6 + $retencion_fielcumplimiento_6 + $cancelado_6));
            }//fin foreach

            $disponibilidad = $this->Formato2($disponibilidad);
            $disponibilidad = $this->Formato1($disponibilidad);

            if ($monto > $disponibilidad) {
                $this->set('msg_error', 'El monto de la disminucion es mayor al saldo del contrato -  Saldo Disponible: ' . $this->Formato2($disponibilidad));

				echo "<script> document.getElementById('guardar').disabled=true; </script>";
				echo "<script> document.getElementById('disminucion').value='0,00'; </script>";

            } else {
                echo"<script> document.getElementById('guardar').disabled=false; </script>";
            }


     }


}// fin funcion aumento_disminucion


function ver_disponibilidad($i, $ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $monto) {
        $this->layout = "ajax";
        $username = $this->Session->read('nom_usuario');
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $tipo = $_SESSION['tipo'];
        $monto = $this->Formato1($monto);
        $disponibilidad = 0;

        if ($tipo == 1){
            $disponibilidad = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

            $disponibilidad = $this->Formato2($disponibilidad);
            $disponibilidad = $this->Formato1($disponibilidad);

            if ($monto > $disponibilidad) {
                $this->set('msg_error', 'la disponibilidad presupustaria no es suficiente - Monto disponible: ' . $this->Formato2($disponibilidad));
                $this->set('i', $i);
			    echo"<script> document.getElementById('guardar').disabled=true; </script>";
			    echo"<script> document.getElementById('modificacion_".$i."').value='0,00';</script>";

            }else{
				echo"<script> document.getElementById('guardar').disabled=false; </script>";
			}

        }else{

            $numero_contrato = $this->Session->read("contrato_servicio");
            $cepp02_datos_partidas = $this->cepd02_contratoservicio_partidas->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and cod_tipo_inst='" . $cod_tipo_inst . "' and cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and ano_contrato_servicio='" . $ano . "'  and  numero_contrato_servicio='" . $numero_contrato . "' and  cod_sector='" . $cod_sector . "' and cod_programa='" . $cod_programa . "' and cod_sub_prog='" . $cod_sub_prog . "' and cod_proyecto='" . $cod_proyecto . "' and cod_activ_obra='" . $cod_activ_obra . "' and cod_partida='" . $cod_partida . "' and cod_generica='" . $cod_generica . "' and cod_especifica='" . $cod_especifica . "' and cod_sub_espec='" . $cod_sub_espec . "' and cod_auxiliar='" . $cod_auxiliar . "' ", null, 'numero_contrato_servicio DESC');

            foreach ($cepp02_datos_partidas as $aux_partidas_5) {
                $monto_5 = $aux_partidas_5['cepd02_contratoservicio_partidas']['monto'];
                $aumento_5 = $aux_partidas_5['cepd02_contratoservicio_partidas']['aumento'];
                $disminucion_5 = $aux_partidas_5['cepd02_contratoservicio_partidas']['disminucion'];
                $amortizacion_5 = $aux_partidas_5['cepd02_contratoservicio_partidas']['amortizacion'];
                $retencion_laboral_5 = $aux_partidas_5['cepd02_contratoservicio_partidas']['retencion_laboral'];
                $retencion_fielcumplimiento_5 = $aux_partidas_5['cepd02_contratoservicio_partidas']['retencion_fielcumplimiento'];
                $cancelado_5 = $aux_partidas_5['cepd02_contratoservicio_partidas']['cancelacion'];
                $disponibilidad = (($monto_5 + $aumento_5) - ($disminucion_5 + $amortizacion_5 + $retencion_laboral_5 + $retencion_fielcumplimiento_5 + $cancelado_5));
            }//fin foreach

            $disponibilidad = $this->Formato2($disponibilidad);
            $disponibilidad = $this->Formato1($disponibilidad);

            if ($monto > $disponibilidad) {
                $this->set('msg_error', 'la disponibilidad para disminuir el contrato de servicio para esta partida es de: ' . $this->Formato2($disponibilidad));
                $this->set('i', $i);

                echo"<script> document.getElementById('guardar').disabled=true;</script>";
                echo"<script> document.getElementById('modificacion_".$i."').value='0,00';</script>";
            }else{
                echo"<script> document.getElementById('guardar').disabled=false;</script>";
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
}// fin function guardar_cugd04

function prueba(){

    echo'<script>';
      echo'alert("hola");';
    echo'</script>';

}//fin function




function tipo_modificacion($var1=null){
   $this->layout = "ajax";
   //echo "el tipo es: ".$var1;
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
    echo'</script>';













}//fin function





function guardar(){

  $this->layout = "ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');

  $ano_orden_compra         =       $this->data['cscp04_ordencompra_modificacion']['ano_orden_compra'];
  $ann = $ano_orden_compra;
  $numero_orden_compra      =       $this->data['cscp04_ordencompra_modificacion']['numero_orden_compra'];
  $numero_modificacion      =       $this->data['cscp04_ordencompra_modificacion']['numero_orden_compra_modificacion'];




  $tipo_modificacion        =       $this->data['cscp04_ordencompra_modificacion']['tipo_modificacion'];
        if($tipo_modificacion=='1'){$monto = $this->Formato1($this->data['cscp04_ordencompra_modificacion']['aumento']);$tm='5';
  }else if($tipo_modificacion=='2'){$monto = $this->Formato1($this->data['cscp04_ordencompra_modificacion']['disminucion']);$tm='6';}//fin else
  $monto_modificacion       =       $monto;

   $fecha_modificacion       =       $this->data['cscp04_ordencompra_modificacion']['fecha_modificacion'];
   $fecha_contrato_servicio  =       $this->data['cscp04_ordencompra_modificacion']['fecha_contrato_servicio'];

   $fd                       =       $this->data['cscp04_ordencompra_modificacion']['fecha_modificacion'];
   $fecha_proceso_registro   =       $this->data['cscp04_ordencompra_modificacion']['fecha_proceso_registro'];



   $rif                      =       $this->data["cscp04_ordencompra_modificacion"]["rif"];


  $observaciones            =       $this->data['cscp04_ordencompra_modificacion']['observaciones'];


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
$sql="BEGIN;  INSERT INTO cepd02_contratoservicio_modificacion_cuerpo (cod_presi, cod_entidad, cod_tipo_inst ,cod_inst, cod_dep , ano_contrato_servicio, numero_contrato_servicio, numero_modificacion, tipo_modificacion, monto_modificacion, fecha_modificacion, observaciones, ano_asiento_registro, mes_asiento_registro, dia_asiento_registro, numero_asiento_registro, fecha_proceso_registro, username_registro, condicion_actividad, ano_asiento_anulacion, mes_asiento_anulacion, dia_asiento_anulacion , numero_asiento_anulacion, username_anulacion, fecha_proceso_anulacion, ano_anulacion, numero_anulacion)";
$sql.="VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$numero_modificacion."', '".$tipo_modificacion."', '".$monto_modificacion."', '".$this->Cfecha($fecha_modificacion, 'A-M-D')."', '".$observaciones."', '".$ano_asiento_registro."', '".$mes_asiento_registro."', '".$dia_asiento_registro."', '".$numero_asiento_registro."', '".$this->Cfecha($fecha_proceso_registro, 'A-M-D')."', '".$username_registro."', '".$condicion_actividad."', '".$ano_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$dia_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$username_anulacion."', '".$this->Cfecha($fecha_proceso_anulacion, 'A-M-D')."', '".$ano_anulacion."', '".$numero_anulacion."'); ";
$sw2 = $this->cepd02_cs_modificacion_cuerpo->execute($sql);

if($sw2>1){

		$i_lenght = $this->data['cscp04_ordencompra_modificacion']['cuenta_i'];
		$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_servicio='".$numero_orden_compra."'  and  ano_contrato_servicio=".$ano_orden_compra."";
		$numero_datos_partidas = $this->cepd02_contratoservicio_partidas->findAll($condicion, null, 'numero_contrato_servicio DESC');
		$a=0;
		$i_aux = 0;
		foreach($numero_datos_partidas as $aux_partidas){
		  $cod_presi2[$a]                 =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_presi'];
		  $cod_entidad2[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_entidad'];
		  $cod_tipo_inst2[$a]             =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_tipo_inst'];
		  $cod_inst2[$a]                  =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_inst'];
		  $cod_dep2[$a]                   =   $aux_partidas['cepd02_contratoservicio_partidas']['cod_dep'];
		  $ano_orden_compra3[$a]          =   $aux_partidas['cepd02_contratoservicio_partidas']['ano_contrato_servicio'];
		  $numero_orden_compra3[$a]       =   $aux_partidas['cepd02_contratoservicio_partidas']['numero_contrato_servicio'];
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
		  $aumento2[$a]                   =   $aux_partidas['cepd02_contratoservicio_partidas']['aumento'];
		  $disminucion2[$a]               =   $aux_partidas['cepd02_contratoservicio_partidas']['disminucion'];
		  $numero_compromiso              =   $aux_partidas['cepd02_contratoservicio_partidas']['numero_control_compromiso'];
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
			   if($partidas_aux==$this->data['partidas']['partidas_'.$i]){$partidas_vista['pago_'.$i_aux] = $this->data['cscp04_ordencompra_modificacion']['modificacion_'.$i]; $i_aux++;}
			}//fin foreach


		}//fin foreach

		$j =0;


		for($i=0; $i<$i_lenght; $i++){
		       $var[$i]['monto']=$partidas_vista['pago_'.$i];
		if($var[$i]['monto']!="0,00"){$var[$i]['monto'] = $this->Formato1($var[$i]['monto']);

			$disponibilidad = $this->disponibilidad($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
            $disponibilidad = $disponibilidad - $var[$i]['monto'];

            if($tipo_modificacion=='2' || $disponibilidad>=0){


								 $cp   = $this->crear_partida($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
								 $to   = 1;
                                 $td   = 2;
                                 $ta   = $tm;
                                 $mt   = $var[$i]['monto'];
                                 $ndo  = $numero_orden_compra3[$i];
                                 $rnco = $numero_compromiso;
								 $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ann, $ndo, $numero_modificacion, null, null, null, null, null, null, $rnco, null, null, null, $i);
								       $sql2  ="INSERT INTO cepd02_contratoservicio_modificacion_partidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_servicio, numero_contrato_servicio, numero_modificacion, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica,  cod_sub_espec, cod_auxiliar,  monto, numero_control_compromiso) ";
								       $sql2 .= "  VALUES ('".$cod_presi2[$i]."', '".$cod_entidad2[$i]."', '".$cod_tipo_inst2[$i]."', '".$cod_inst2[$i]."', '".$cod_dep2[$i]."', '".$ano_orden_compra3[$i]."', '".$numero_orden_compra3[$i]."', '".$numero_modificacion."', '".$ano_partidas[$i]."', '".$cod_sector[$i]."', '".$cod_programa[$i]."', '".$cod_sub_prog[$i]."', '".$cod_proyecto[$i]."', '".$cod_activ_obra[$i]."', '".$cod_partida[$i]."', '".$cod_generica[$i]."', '".$cod_especifica[$i]."',  '".$cod_sub_espec[$i]."', '".$cod_auxiliar[$i]."',  '".$var[$i]['monto']."', '".$rnco."') ";
								       $sw3 = $this->cepd02_cs_modificacion_partidas->execute($sql2);

								        if($sw3>1){
											    $monto_modificacion_tipo2 = 0;
											      if($tipo_modificacion=='1'){
												$monto_modificacion_tipo2 = $aumento2[$i] + $var[$i]['monto']; $campo="aumento";
											}else if($tipo_modificacion=='2'){
												$monto_modificacion_tipo2 = $disminucion2[$i] + $var[$i]['monto']; $campo="disminucion";
											}//fin else
											$sql4  = "UPDATE cepd02_contratoservicio_partidas SET ".$campo."= '".$monto_modificacion_tipo2."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and  numero_contrato_servicio='".$numero_orden_compra2."'  and  ano_contrato_servicio=".$ano_orden_compra2." and ano=".$ano_partidas[$i]." and cod_sector=".$cod_sector[$i]." and cod_programa=".$cod_programa[$i]." and cod_sub_prog=".$cod_sub_prog[$i]." and cod_proyecto=".$cod_proyecto[$i]." and cod_activ_obra=".$cod_activ_obra[$i]." and cod_partida=".$cod_partida[$i]." and cod_generica=".$cod_generica[$i]." and cod_especifica=".$cod_especifica[$i]." and cod_sub_espec=".$cod_sub_espec[$i]." and cod_auxiliar=".$cod_auxiliar[$i]." ";
											$sw4 = $this->cepd02_contratoservicio_partidas->execute($sql4);
											  if($sw4 > 1){}else{break;}//fin else
								        }else{  }//fin else

                }else{$sw4 = 0; break;}

		  }//fin if
		}//fin for

if($sw4 > 1){
		$campo = "";
		$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and   numero_contrato_servicio='".$numero_orden_compra2."'  and  ano_contrato_servicio=".$ano_orden_compra2."";
		$numero_datos = $this->cepd02_contratoservicio_cuerpo->findAll($condicion);
		$numero_datos_aux =  $numero_datos;
		foreach($numero_datos_aux as $aux){
			$modificacion_aumento = $aux['cepd02_contratoservicio_cuerpo']['aumento'];
			$modificacion_disminucion = $aux['cepd02_contratoservicio_cuerpo']['disminucion'];
		}//fin foreach
		      if($tipo_modificacion=='1'){
			$monto_modificacion_tipo = $monto_modificacion + $modificacion_aumento; $campo="aumento";
		}else if($tipo_modificacion=='2'){
			$monto_modificacion_tipo = $monto_modificacion + $modificacion_disminucion;  $campo="disminucion";
		}//fin else

		$sql3 = "UPDATE cepd02_contratoservicio_cuerpo SET ".$campo."= '".$monto_modificacion_tipo."'  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')."  and   numero_contrato_servicio='".$numero_orden_compra2."'  and  ano_contrato_servicio=".$ano_orden_compra2." ";
		$sw5 = $this->cepd02_contratoservicio_cuerpo->execute($sql3);

        if($sw5 > 1){

            $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                          $to=1,
                                                                          $td=12,
                                                                          $rif_doc = $rif,

                                                                          $ano_dc  = $ano_orden_compra,
                                                                          $n_dc    = $numero_orden_compra,

                                                                          $f_dc    = $fecha_contrato_servicio,
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
		////////
		//echo "la actualizacion es ".$sql3;
		//////////

		////************************************** DESPUES DE GUARDAR ****************************************/////
		 $ano='';
		 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
		 $ano = $this->ano_ejecucion();
		 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_contrato_servicio='.$ano.'';
		 $lista = $this->cepd02_contratoservicio_cuerpo->generateList($condicion.' and condicion_actividad=1', ' numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio');
		 $this->AddCero('lista_numero', $lista);
		 $this->set('numero_orden_compra', $numero_orden_compra2);
		 $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and  numero_contrato_servicio='".$numero_orden_compra2."'  and  ano_contrato_servicio=".$ano_orden_compra2."";
		 $numero_datos_encabezado = $this->cepd02_contratoservicio_cuerpo->findAll($condicion." and rif='".$this->data['cscp04_ordencompra_modificacion']['rif']."' ");
		 $numero_datos_orden_compra_partidas = $this->cepd02_contratoservicio_partidas->findAll($condicion. " and ano=".$ano_partidas[0]."  ");
		 $numero_datos_aux  =  $numero_datos_encabezado;
		foreach($numero_datos_aux as $aux){
			$rif = $aux['cepd02_contratoservicio_cuerpo']['rif'];
			$ano_orden_compra = $aux['cepd02_contratoservicio_cuerpo']['ano_contrato_servicio'];
			$numero_orden_compra = $aux['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio'];
		}//fin foreach
		$opc = $this->cepd02_cs_modificacion_cuerpo->findAll($condicion." and ano_contrato_servicio=".$ano_orden_compra."  and numero_contrato_servicio='".$numero_orden_compra."' and numero_modificacion=".$numero_modificacion."");
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
		$aux_monto_modificacion = $this->cepd02_cs_modificacion_partidas->findAll($condicion." and ano_contrato_servicio=".$ano_orden_compra."  and numero_contrato_servicio='".$numero_orden_compra."' and numero_modificacion=".$numero_modificacion."  ".$agrupar, $campos, null,  null, null);
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
		$this->cepd02_cs_modificacion_cuerpo->execute("COMMIT;");
		$this->set('Message_existe', 'Los datos fueron guardados correctamente');
		////************************************** DESPUES DE GUARDAR ****************************************/////

		}else{
			$this->cepd02_cs_modificacion_cuerpo->execute("ROLLBACK;");
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
       }//fin else

     }else{
			$this->cepd02_cs_modificacion_cuerpo->execute("ROLLBACK;");
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
     }//fin else

}else{
	$this->cepd02_cs_modificacion_cuerpo->execute("ROLLBACK;");
	$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
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





function buscar_year($var1=null){
  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');

if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' ';
}else{$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';}


  $lista = $this->cepd02_cs_modificacion_cuerpo->generateList($condicion.' and ano_contrato_servicio='.$var1, ' numero_contrato_servicio ASC', null, '{n}.cepd02_cs_modificacion_cuerpo.numero_contrato_servicio', '{n}.cepd02_cs_modificacion_cuerpo.numero_contrato_servicio');
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
  $ano = $this->ano_ejecucion();


if(!empty($this->data['cepp02_contratoservicios_modificacion']['ano_contrato'])){	$_SESSION['ano_contrato_servicio'] = $this->data['cepp02_contratoservicios_modificacion']['ano_contrato'];}else{$_SESSION['ano_contrato_servicio'] = $this->ano_ejecucion();}


if($var1!=null){

  if($var1=='si'){

 $ano = $_SESSION['ano_contrato_servicio'];

  if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else


if(!empty($this->data['cepp02_contratoservicios_modificacion']['numero_contrato_servicio'])){


   $array = $this->cepd02_cs_modificacion_cuerpo->findAll($condicion. " and  numero_contrato_servicio='".$this->data['cepp02_contratoservicios_modificacion']['numero_contrato_servicio']."'   and ano_contrato_servicio = ".$ano , null, 'ano_contrato_servicio, numero_contrato_servicio, numero_modificacion ASC', null);
  $i = 0;
   foreach($array as $aux){
 	$numero[$i]['ano_orden_compra'] = $aux['cepd02_cs_modificacion_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_orden_compra'] = $aux['cepd02_cs_modificacion_cuerpo']['numero_contrato_servicio'];
 	$numero[$i]['numero_modificacion'] = $aux['cepd02_cs_modificacion_cuerpo']['numero_modificacion'];
 	$i++;
} $i--;


  for($a=0; $a<=$i; $a++){
    if($this->data['cepp02_contratoservicios_modificacion']['numero_contrato_servicio'] == $numero[$a]['numero_orden_compra']){
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

      if($opcion=='si'){$_SESSION['PAG_NUM']=$this->data['cepp02_contratoservicios_modificacion']['numero_contrato_servicio'];
      	                $this->consulta($pag_num, $numero_documento);$this->render('consulta');
}else if($opcion=='no'){
	$this->set('errorMessage', 'No existen datos'); $this->consulta_index();$this->render('consulta_index');
	}//fin else


         }else{

	   $array = $this->cepd02_cs_modificacion_cuerpo->findAll($condicion. "  and ano_contrato_servicio = ".$ano , null, 'ano_contrato_servicio, numero_contrato_servicio, numero_modificacion ASC', null);
  $i = 0;
   foreach($array as $aux){
 	$numero[$i]['ano_orden_compra'] = $aux['cepd02_cs_modificacion_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_orden_compra'] = $aux['cepd02_cs_modificacion_cuerpo']['numero_contrato_servicio'];
 	$numero[$i]['numero_modificacion'] = $aux['cepd02_cs_modificacion_cuerpo']['numero_modificacion'];
 	$i++;
} $i--;



	$this->Session->delete('PAG_NUM');

	if($i<=0){
	  $this->set('errorMessage', 'No existen datos');
	  $this->consulta_index();
	  $this->render('consulta_index');
	}else{$this->consulta(0, $numero[0]['numero_orden_compra']);$this->render('consulta'); }}//fin else



  }//fin if
}//fin if

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' ';
}else{$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';}
 $lista = $this->v_cepd02_contratoservicio_modificacion_cuerpo->generateList($condicion.' and ano_contrato_servicio='.$_SESSION['ano_contrato_servicio'], ' numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_modificacion_cuerpo.numero_contrato_', '{n}.v_cepd02_contratoservicio_modificacion_cuerpo.denominacion_rif');
 if($lista==""){$lista = array(''=>'');}
 $this->concatena( $lista, 'lista_numero');
 $this->set('ano_contrato_servicio', $_SESSION['ano_contrato_servicio']);



}//fin function








function consulta($pag_num=null, $numero_documento=null){
  $this->layout = "ajax";

   $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
   if(isset($_SESSION['ano_contrato_servicio'])){$ano = $_SESSION['ano_contrato_servicio'];}else{$ano = $this->ano_ejecucion();}
   $this->set('ano_contrato_servicio', $ano);
   $this->set('ano_ejecucion', $this->ano_ejecucion());

$acta = 0;
$concepto = "";

$sacar1 = $this->cepd02_cs_modificacion_cuerpo->findAll($condicion." and ano_contrato_servicio= ".$ano);
//print_r($sacar1);
//echo $condicion. " and ano_contrato_servicio=" .$ano;
foreach($sacar1 as $sa){
 	$acta= $sa['cepd02_cs_modificacion_cuerpo']['numero_anulacion'];

}
//echo "el acta es ".$acta;

$sacar = $this->cugd03_acta_anulacion_cuerpo->findAll($condicion. " and numero_acta_anulacion=".$acta." and ano_acta_anulacion=".$ano );
//print_r($sacar);
//echo $condicion. " and numero_acta_anulacion=" .$acta;
foreach($sacar as $sa2){

 	$concepto= $sa2['cugd03_acta_anulacion_cuerpo']['motivo_anulacion'];

}
$this->set('concepto',$concepto);


  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else


   $array = $this->cepd02_cs_modificacion_cuerpo->findAll($condicion. " and  numero_contrato_servicio='".$numero_documento."' and ano_contrato_servicio = ".$ano,  null, 'ano_contrato_servicio, numero_contrato_servicio, numero_modificacion ASC', null);

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['ano_orden_compra'] = $aux['cepd02_cs_modificacion_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_orden_compra'] = $aux['cepd02_cs_modificacion_cuerpo']['numero_contrato_servicio'];
 	$numero[$i]['numero_modificacion'] = $aux['cepd02_cs_modificacion_cuerpo']['numero_modificacion'];

 	$i++;

} $i--;






if(isset($numero[$pag_num]['numero_orden_compra'])){



$datos_orden_compra_encabezado          =   $this->cepd02_contratoservicio_cuerpo->findAll($condicion." and ano_contrato_servicio=".$numero[$pag_num]['ano_orden_compra']."  and  numero_contrato_servicio='".$numero[$pag_num]['numero_orden_compra']."'");
$datos_orden_compra_modificacion_cuerpo =   $this->cepd02_cs_modificacion_cuerpo->findAll($condicion." and ano_contrato_servicio=".$numero[$pag_num]['ano_orden_compra']."  and  numero_contrato_servicio='".$numero[$pag_num]['numero_orden_compra']."' and  numero_modificacion=".$numero[$pag_num]['numero_modificacion']."");
//print_r($datos_orden_compra_modificacion_cuerpo);



$numero_datos_aux  =  $datos_orden_compra_encabezado;
foreach($numero_datos_aux as $aux){$rif = $aux['cepd02_contratoservicio_cuerpo']['rif'];}//fin foreach
$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
foreach($rif_datos as $aux_2){$denominacion_rif = $aux_2['cpcd02']['denominacion']; $direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];}//fin foreach
$campos = ' cod_presi, cod_entidad, cod_tipo_inst, cod_inst, SUM(monto) as "monto" ';
$agrupar = ' GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst';
$sql = $condicion."  and  ano_contrato_servicio=".$numero[$pag_num]['ano_orden_compra']."  and  numero_contrato_servicio='".$numero[$pag_num]['numero_orden_compra']."' and numero_modificacion=".$numero[$pag_num]['numero_modificacion']."".$agrupar;
$sql2 = $condicion."  and  ano_contrato_servicio=".$numero[$pag_num]['ano_orden_compra']."  and  numero_contrato_servicio='".$numero[$pag_num]['numero_orden_compra']."' and numero_modificacion=".$numero[$pag_num]['numero_modificacion']."";
$aux_monto_modificacion = $this->cepd02_cs_modificacion_partidas->findAll($sql, $campos, null,  null, null);
$numero_datos_orden_compra_partidas = $this->cepd02_cs_modificacion_partidas->findAll($sql2);
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





function guardar_anulacion2($tipo_modificacion,$var=null) {

	//echo "si entro a anular la modificacion del contrato de servicios";

$this->layout="ajax";
//print_r($this->data["cscp04_ordencompra_modificacion"]);
	if(isset($this->data["cscp04_ordencompra_modificacion"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		     $tipo_documento           =  22;

			 $concepto_anulacion       =  $this->data["cscp04_ordencompra_modificacion"]["concepto_anulacion"];
			 $concepto = $concepto_anulacion;
			 $fecha_proceso_anulacion  =  date("d/m/Y");

			 $condicion_documento      =  2;//cuando se guarda es Activo=1
			 $ano_orden_compra    = $this->data["cscp04_ordencompra_modificacion"]["ano_orden_compra"];
			 $numero_orden_compra = $this->data["cscp04_ordencompra_modificacion"]["numero_orden_compra"];


			 $fecha_contrato      = $this->data["cscp04_ordencompra_modificacion"]["fecha_contrato2"];


			 $fecha_modificacion  = $this->data["cscp04_ordencompra_modificacion"]["fecha_modificacion"];
			 $fd = $fecha_modificacion;

			 $fecha_contrato_servicio  = $this->data["cscp04_ordencompra_modificacion"]["fecha_contrato_servicio"];

			 $numero_modificacion = $this->data["cscp04_ordencompra_modificacion"]["numero_modificacion"];
			 //$tipo_modificacion = $this->data["cscp04_ordencompra_modificacion"]["tipo_modificacion"];

			$rif                        =       $this->data["cscp04_ordencompra_modificacion"]["rif"];


if(isset($concepto_anulacion)){
if(isset($ano_orden_compra)){
if(isset($numero_orden_compra)){
if(isset($fecha_modificacion)){
if(isset($fecha_contrato)){
if(isset($numero_modificacion)){





			 $tmo=$tipo_modificacion + 3;
			 //echo "ojo el tipo de modificacion es".$tipo_modificacion;
			 //echo "el tmo que va al motor es ".$tmo;
			 if($tipo_modificacion == 1){
			 	//echo "entro a una";
			 	$actualizar='aumento';
			 	$tipo='aumento =aumento - ';
				$signo = '-';
			 }else{
			 	//echo "entro a otra";
			 	$tipo='disminucion = disminucion - ';
			 	$actualizar='disminucion';
				$signo = '-';
			 }

			$tipo_documento           =  '22'.$tmo;

			$datos_partidas = $this->cepd02_cs_modificacion_partidas->findAll($conditions = $this->condicion()." and ano_contrato_servicio='$ano_orden_compra' and numero_contrato_servicio='".$numero_orden_compra."' and numero_modificacion='$numero_modificacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			 			$monto_anticipo = 0;
			$sql_update_cscd04_partidas = '';

			$monto_modificacion=0;

			 foreach($datos_partidas as $row){
			 	$ano = $row['cepd02_cs_modificacion_partidas']['ano'];
			 	$cod_sector = $row['cepd02_cs_modificacion_partidas']['cod_sector'];
			 	$cod_programa = $row['cepd02_cs_modificacion_partidas']['cod_programa'];
			 	$cod_sub_prog = $row['cepd02_cs_modificacion_partidas']['cod_sub_prog'];
			 	$cod_proyecto = $row['cepd02_cs_modificacion_partidas']['cod_proyecto'];
			 	$cod_activ_obra = $row['cepd02_cs_modificacion_partidas']['cod_activ_obra'];
			 	$cod_partida = $row['cepd02_cs_modificacion_partidas']['cod_partida'];
			 	$cod_generica = $row['cepd02_cs_modificacion_partidas']['cod_generica'];
			 	$cod_especifica = $row['cepd02_cs_modificacion_partidas']['cod_especifica'];
			 	$cod_sub_espec = $row['cepd02_cs_modificacion_partidas']['cod_sub_espec'];
			 	$cod_auxiliar = $row['cepd02_cs_modificacion_partidas']['cod_auxiliar'];
			 	$monto_partida = $row['cepd02_cs_modificacion_partidas']['monto'];
			 	$numero_control_compromiso = $row['cepd02_cs_modificacion_partidas']['numero_control_compromiso'];
			 	$cond1 = $this->condicion()." and ano_contrato_servicio='$ano_orden_compra' and numero_contrato_servicio='".$numero_orden_compra."' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

				$monto_modificacion += $monto_partida;
				$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

				$num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 2, $tipo_modificacion+4, date("d/m/Y"), $monto_partida, $concepto, $ano, $numero_orden_compra, $numero_modificacion, null, null, null, null, null, null, $numero_control_compromiso, null, null, null, null);

			 	$sql_update_cscd04_partidas .= "UPDATE cepd02_contratoservicio_partidas SET ".$actualizar."=".$actualizar."".$signo."'$monto_partida' WHERE ".$cond1.";";
			 }

			 	$sql_cscd04_encabezado="UPDATE cepd02_contratoservicio_cuerpo SET ".$tipo." ".$monto_modificacion." WHERE ".$this->condicion()." and ano_contrato_servicio='$ano_orden_compra' and numero_contrato_servicio='".$numero_orden_compra."';";
				//echo $sql_cscd04_encabezado;

				$this->cepd02_cs_modificacion_partidas->execute($sql_cscd04_encabezado);
				$this->cepd02_cs_modificacion_partidas->execute($sql_update_cscd04_partidas);


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
			 $R1 = $this->cepd02_cs_modificacion_cuerpo->execute("UPDATE cepd02_contratoservicio_modificacion_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.", fecha_proceso_anulacion='".$this->Cfecha($fecha_proceso_anulacion, 'A-M-D')."', username_anulacion='".$_SESSION['nom_usuario']."'  WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano_orden_compra." and numero_contrato_servicio='".$numero_orden_compra."' and numero_modificacion=".$numero_modificacion );
		     $v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_orden_compra.",".$numero.",".$tipo_documento.",".$ano_orden_compra.",'".$numero_orden_compra."','".$this->Cfecha($fecha_modificacion, 'A-M-D')."','".$concepto_anulacion."')");
		     if($R1 > 1 && $v>1){

		     }





		    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                          $to=2,
                                                                          $td=12,
                                                                          $rif_doc = $rif,

                                                                          $ano_dc  = $ano_orden_compra,
                                                                          $n_dc    = $numero_orden_compra,

                                                                          $f_dc    = $fecha_contrato_servicio,
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









                      $this->set('Message_existe', 'El registro fue eliminado correctamente');




                }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
				}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}


	}else{	$this->set('errorMessage', 'El registro fu&eacute; anulado correctamente');}


$this->index();
$this->render('index');



}//fin function







}//fin class

?>