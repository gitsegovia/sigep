<?php

class Cstp09NotadebitoPorCancelarController extends AppController{


    var $name = "cstp09_notadebito_por_cancelar";
 	var $uses = array('v_cstd01_bancos','v_cstd01_sucursales','cstd09_notadebito_cuerpo_pago', 'cstd09_notadebito_partidas_pago', 'cstd07_retenciones_cuerpo_iva', 'cstd07_retenciones_partidas_iva',
                      'cstd07_retenciones_cuerpo_islr', 'cstd07_retenciones_partidas_islr', 'cstd07_retenciones_cuerpo_timbre',
                      'cstd07_retenciones_partidas_timbre', 'cstd07_retenciones_cuerpo_municipal', 'cstd07_retenciones_partidas_municipal',
                      'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_partidas','cstd09_notadebito_ordenes', 'ccfd04_cierre_mes',
                      'cstd09_notadebito_poremitir','cstd04_movimientos_generales','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','cfpd01_grupo',
                      'cstd02_cuentas_bancarias','ccfd03_instalacion','cstd03_cheque_numero', 'cugd03_acta_anulacion_numero',
                      'cstd06_comprobante_cuerpo_egreso', 'cstd06_comprobante_cuerpo_islr', 'cstd06_comprobante_cuerpo_iva',
                      'cstd06_comprobante_cuerpo_municipal', 'cstd06_comprobante_cuerpo_timbre', 'cugd03_acta_anulacion_cuerpo',
                      'cstd06_comprobante_numero_egreso', 'cstd06_comprobante_numero_islr', 'cstd06_comprobante_numero_iva',
                      'cstd06_comprobante_numero_municipal', 'cstd06_comprobante_numero_timbre','cstd04_movimientos_generales',
                      'cstd06_comprobante_poremitir_egreso', 'cstd06_comprobante_poremitir_islr', 'cstd06_comprobante_poremitir_iva',
                      'cstd06_comprobante_poremitir_municipal', 'cstd06_comprobante_poremitir_timbre','ccfd03_instalacion', 'cfpd23_numero_asiento_pagado',
                      'cfpd05', 'cugd04', 'cfpd23', 'cobd01_contratoobras_valuacion_partidas', 'cobd01_contratoobras_valuacion_cuerpo',
                      'cepd02_contratoservicio_valuacion_cuerpo', 'cepd02_contratoservicio_valuacion_partidas', 'cscd04_ordencompra_autorizacion_cuerpo',
                      'cscd04_ordencompra_a_pago_partidas', 'cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'cscd04_ordencompra_encabezado',
                      'cstd03_movimientos_manuales', 'v_cstd09_notadebito_ordenes',
                      'cstd07_retenciones_cuerpo_multa', 'cstd07_retenciones_cuerpo_responsabilidad', 'cstd07_retenciones_partidas_multa', 'cstd07_retenciones_partidas_responsabilidad',
                      'cstd06_comprobante_poremitir_multa', 'cstd06_comprobante_poremitir_responsabilidad', 'cstd06_comprobante_numero_multa', 'cstd06_comprobante_numero_responsabilidad',
                      'cstd06_comprobante_cuerpo_multa', 'cstd06_comprobante_cuerpo_responsabilidad',


                      'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
                            'cscd04_ordencompra_retencion_cuerpo', 'cscd04_ordencompra_retencion_partidas',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'cugd99_firmas_responsabilidad'
                      );

 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');




function mascara_ocho($var1){

$var = strlen($var1);
switch($var){
case '1';{$var1 = '0000000'.$var1; }break;
case '2';{$var1 = '000000'.$var1; }break;
case '3';{$var1 = '00000'.$var1; }break;
case '4';{$var1 = '0000'.$var1; }break;
case '5';{$var1 = '000'.$var1; }break;
case '6';{$var1 = '00'.$var1; }break;
case '7';{$var1 = '0'.$var1; }break;
case '8';{$var1 = '0'.$var1; }break;
}//fin

return $var1;

}//fin funtion



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
}



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
		function SQLCA_admin($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				 if($ano!=null){
					if($this->verifica_SS(5)!=1){
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
								}
								$sql_re .= "ano=".$ano."  ";
				 }else{
					 if($this->verifica_SS(5)!=1){
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)."  ";
								}
				 }
				 return $sql_re;
		}//fin funcion SQLCA
		function SQLCA_reque($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				 if($ano!=null){
					 $sql_re .= "ano=".$ano."  ";
				 }else{

				 }
				 return $sql_re;
		}//fin funcion SQLCA

		function SQLCA_report($pre=null){
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 if($pre!=null && $pre==1){
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
				 //$sql_re .= "cod_dep=0";
				 }else{
					 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
						$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
				 }

				 return $sql_re;
		}//fin funcion SQLCA
		function SQLCA_report_in($pre=null){
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .= $this->verifica_SS(3).",";
				 if($pre!=null && $pre==1){
				 $sql_re .= $this->verifica_SS(4).",";
				 $sql_re .= 0;
				 }else{
					 $sql_re .= $this->verifica_SS(4).",";
						$sql_re .= $this->verifica_SS(5)." ";
				 }

				 return $sql_re;
		}//fin funcion SQLCA


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






function zeros($x=null){
	if($x != null){
		if($x<10){
			$x="000".$x;
		}else if($x>=10 && $x<=99){
			$x="00".$x;
		}else if($x>=100 && $x<=999){
			$x="0".$x;
		}
	}
	return $x;

}


function index(){
$this->layout = "ajax";
$this->Session->delete('radio');
//$this->set('entidad_federal', $this->Session->read('entidad_federal'));

$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');

$cond= $this->SQLCA();

	$ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $ano = $this->ano_ejecucion();
 $this->set('ano',$ano);
 $this->Session->write('cod4',$ano);

 $this->Session->write('pregunta_ejercicio', 2);

  $this->Session->delete('ORDEN_PAGO');
  $this->Session->delete('CUENTA_ORDENES_PAGO');
  $this->Session->delete('ORDEN_PAGO_TOTAL');


$this->Session->delete('cod_entidad_bancaria_aux');
$this->Session->delete('cod_sucursal_aux');
$this->Session->delete('cuenta_bancaria');
$this->Session->delete('numero_debito');

  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_IVA']        =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_ISRL']       =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_TIMBRE']     =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_MUNICIPIO']  =   "no";

  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_RETENCION_MULTA']            =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_RETENCION_RESPONSABILIDAD']  =   "no";

  $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['autorizado'] = "";

//$cond="cod_presi=1 and cod_entidad=1 and cod_tipo_inst=1 and cod_inst=1 and cod_dep=1 and ano_orden_pago=2008";
 $cond.=" and ano_orden_pago=".$ano;
 $this->concatena_seis_digitos($this->cepd03_ordenpago_cuerpo->generateList($cond.' and tipo_orden=2  and condicion_actividad=1 and numero_cheque=0', 'autorizado ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.autorizado'), 'grupo');


            echo'<script>';
			  echo"document.getElementById('concepto').value = ''; ";
			echo'</script>';


}//fin index





function select($select=null,$var=null, $var2=null) { //select codigos presupuestarios
	$this->layout = "ajax";
if($select!=null && $var!=null){
		//$cond =$this->SQLCA();
	switch($select){
		case 'sucursal':
			$this->set('SELECT','sucursal');
			$this->set('codigo','sucursal');
			$this->set('seleccion','');
			$this->set('n',2);
			//$this->set('no','no');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			$this->Session->write('cod1',$var2);
			$cond =" cod_entidad_bancaria=".$var2;
			$lista = "";
			if($var2!=""){
			  	$busca=$this->SQLCA()." and cod_entidad_bancaria=".$var2;
				$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'vector');
			}//fin if
		break;
		case 'cuenta':
			$this->set('SELECT','cuenta');
			$this->set('codigo','cuenta');
			$this->set('seleccion','');
			$this->set('n',3);
			$this->set('no','no');
			$this->set('otro','otro');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			if($var!=null && $var=='consulta'){$this->set('cuenta','cuenta');}
			$this->Session->write('cod2',$var2);
			$cod_1 =  $this->Session->read('cod1');
			$cond  =  $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$var2;
			$lista = "";
			if($var2!=""){
			   $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
			}//fin if
			if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
		break;
	}//fin wsitch
	}else{
			echo "";
	}
}//fin select codigos bancarios



function envia_form_firmas($num_tipo_doc = null, $cant_firmas = 8){
    // $this->layout="ajax";

	if($num_tipo_doc != null){

		$firmantes = $this->cugd99_firmas_responsabilidad->findAll($this->SQLCA()." and cod_tipo_documento=".$num_tipo_doc, null, null, 1, 1, null);

	if($firmantes != null){
		$this->set('firma_existe','si');
		$this->set('b_readonly','readonly');
		$this->set('tipo_documento',$firmantes[0]['cugd99_firmas_responsabilidad']['cod_tipo_documento']);

		switch((int) $cant_firmas){
		case 1:
			$this->set('responsa_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_primera_firma']);
			$this->set('funcionario_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_primera_firma']);
			$this->set('cedula_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_primera_firma']);
		break;


		case 2:
			$this->set('responsa_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_primera_firma']);
			$this->set('funcionario_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_primera_firma']);
			$this->set('cedula_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_primera_firma']);

			$this->set('responsa_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_segunda_firma']);
			$this->set('funcionario_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_segunda_firma']);
			$this->set('cedula_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_segunda_firma']);
		break;


		case 3:
			$this->set('responsa_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_primera_firma']);
			$this->set('funcionario_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_primera_firma']);
			$this->set('cedula_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_primera_firma']);

			$this->set('responsa_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_segunda_firma']);
			$this->set('funcionario_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_segunda_firma']);
			$this->set('cedula_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_segunda_firma']);

			$this->set('responsa_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_tercera_firma']);
			$this->set('funcionario_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_tercera_firma']);
			$this->set('cedula_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_tercera_firma']);
		break;


		case 4:
			$this->set('responsa_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_primera_firma']);
			$this->set('funcionario_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_primera_firma']);
			$this->set('cedula_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_primera_firma']);

			$this->set('responsa_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_segunda_firma']);
			$this->set('funcionario_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_segunda_firma']);
			$this->set('cedula_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_segunda_firma']);

			$this->set('responsa_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_tercera_firma']);
			$this->set('funcionario_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_tercera_firma']);
			$this->set('cedula_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_tercera_firma']);

			$this->set('responsa_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_cuarta_firma']);
			$this->set('funcionario_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_cuarta_firma']);
			$this->set('cedula_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_cuarta_firma']);
		break;


		case 5:
			$this->set('responsa_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_primera_firma']);
			$this->set('funcionario_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_primera_firma']);
			$this->set('cedula_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_primera_firma']);

			$this->set('responsa_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_segunda_firma']);
			$this->set('funcionario_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_segunda_firma']);
			$this->set('cedula_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_segunda_firma']);

			$this->set('responsa_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_tercera_firma']);
			$this->set('funcionario_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_tercera_firma']);
			$this->set('cedula_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_tercera_firma']);

			$this->set('responsa_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_cuarta_firma']);
			$this->set('funcionario_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_cuarta_firma']);
			$this->set('cedula_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_cuarta_firma']);

			$this->set('responsa_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_quinta_firma']);
			$this->set('funcionario_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_quinta_firma']);
			$this->set('cargo_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_quinta_firma']);
			$this->set('cedula_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_quinta_firma']);
		break;


		case 6:
			$this->set('responsa_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_primera_firma']);
			$this->set('funcionario_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_primera_firma']);
			$this->set('cedula_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_primera_firma']);

			$this->set('responsa_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_segunda_firma']);
			$this->set('funcionario_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_segunda_firma']);
			$this->set('cedula_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_segunda_firma']);

			$this->set('responsa_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_tercera_firma']);
			$this->set('funcionario_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_tercera_firma']);
			$this->set('cedula_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_tercera_firma']);

			$this->set('responsa_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_cuarta_firma']);
			$this->set('funcionario_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_cuarta_firma']);
			$this->set('cedula_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_cuarta_firma']);

			$this->set('responsa_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_quinta_firma']);
			$this->set('funcionario_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_quinta_firma']);
			$this->set('cargo_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_quinta_firma']);
			$this->set('cedula_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_quinta_firma']);

			$this->set('responsa_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_sexta_firma']);
			$this->set('funcionario_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_sexta_firma']);
			$this->set('cargo_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_sexta_firma']);
			$this->set('cedula_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_sexta_firma']);
		break;


		case 7:
			$this->set('responsa_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_primera_firma']);
			$this->set('funcionario_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_primera_firma']);
			$this->set('cedula_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_primera_firma']);

			$this->set('responsa_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_segunda_firma']);
			$this->set('funcionario_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_segunda_firma']);
			$this->set('cedula_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_segunda_firma']);

			$this->set('responsa_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_tercera_firma']);
			$this->set('funcionario_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_tercera_firma']);
			$this->set('cedula_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_tercera_firma']);

			$this->set('responsa_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_cuarta_firma']);
			$this->set('funcionario_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_cuarta_firma']);
			$this->set('cedula_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_cuarta_firma']);

			$this->set('responsa_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_quinta_firma']);
			$this->set('funcionario_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_quinta_firma']);
			$this->set('cargo_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_quinta_firma']);
			$this->set('cedula_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_quinta_firma']);

			$this->set('responsa_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_sexta_firma']);
			$this->set('funcionario_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_sexta_firma']);
			$this->set('cargo_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_sexta_firma']);
			$this->set('cedula_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_sexta_firma']);

			$this->set('responsa_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_septima_firma']);
			$this->set('funcionario_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_septima_firma']);
			$this->set('cargo_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_septima_firma']);
			$this->set('cedula_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_septima_firma']);
		break;


		case 8:
			$this->set('responsa_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_primera_firma']);
			$this->set('funcionario_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_primera_firma']);
			$this->set('cedula_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_primera_firma']);

			$this->set('responsa_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_segunda_firma']);
			$this->set('funcionario_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_segunda_firma']);
			$this->set('cedula_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_segunda_firma']);

			$this->set('responsa_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_tercera_firma']);
			$this->set('funcionario_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_tercera_firma']);
			$this->set('cedula_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_tercera_firma']);

			$this->set('responsa_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_cuarta_firma']);
			$this->set('funcionario_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_cuarta_firma']);
			$this->set('cedula_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_cuarta_firma']);

			$this->set('responsa_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_quinta_firma']);
			$this->set('funcionario_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_quinta_firma']);
			$this->set('cargo_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_quinta_firma']);
			$this->set('cedula_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_quinta_firma']);

			$this->set('responsa_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_sexta_firma']);
			$this->set('funcionario_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_sexta_firma']);
			$this->set('cargo_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_sexta_firma']);
			$this->set('cedula_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_sexta_firma']);

			$this->set('responsa_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_septima_firma']);
			$this->set('funcionario_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_septima_firma']);
			$this->set('cargo_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_septima_firma']);
			$this->set('cedula_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_septima_firma']);

			$this->set('responsa_octava_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_octava_firma']);
			$this->set('funcionario_octava_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_octava_firma']);
			$this->set('cargo_octava_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_octava_firma']);
			$this->set('cedula_octava_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_octava_firma']);
		break;

		default:
			$this->set('responsa_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_primera_firma']);
			$this->set('funcionario_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_primera_firma']);
			$this->set('cedula_primera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_primera_firma']);

			$this->set('responsa_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_segunda_firma']);
			$this->set('funcionario_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_segunda_firma']);
			$this->set('cedula_segunda_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_segunda_firma']);

			$this->set('responsa_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_tercera_firma']);
			$this->set('funcionario_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_tercera_firma']);
			$this->set('cedula_tercera_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_tercera_firma']);

			$this->set('responsa_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_cuarta_firma']);
			$this->set('funcionario_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_cuarta_firma']);
			$this->set('cedula_cuarta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_cuarta_firma']);

			$this->set('responsa_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_quinta_firma']);
			$this->set('funcionario_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_quinta_firma']);
			$this->set('cargo_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_quinta_firma']);
			$this->set('cedula_quinta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_quinta_firma']);

			$this->set('responsa_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_sexta_firma']);
			$this->set('funcionario_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_sexta_firma']);
			$this->set('cargo_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_sexta_firma']);
			$this->set('cedula_sexta_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_sexta_firma']);

			$this->set('responsa_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_septima_firma']);
			$this->set('funcionario_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_septima_firma']);
			$this->set('cargo_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_septima_firma']);
			$this->set('cedula_septima_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_septima_firma']);

			$this->set('responsa_octava_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['responsa_octava_firma']);
			$this->set('funcionario_octava_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['funcionario_octava_firma']);
			$this->set('cargo_octava_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cargo_octava_firma']);
			$this->set('cedula_octava_firma',$firmantes[0]['cugd99_firmas_responsabilidad']['cedula_octava_firma']);
		break;
		}


	}else{
		$this->set('Message_existe2','POR FAVOR, INGRESE LOS NOMBRES Y CARGO DE LOS FIRMANTES');
		$this->set('firma_existe','no');
		$this->set('b_readonly','');
		$this->set('tipo_documento',$num_tipo_doc);

		$this->set('responsa_primera_firma', '');
		$this->set('funcionario_primera_firma', '');
		$this->set('cargo_primera_firma', '');
		$this->set('cedula_primera_firma', '');

		$this->set('responsa_segunda_firma', '');
		$this->set('funcionario_segunda_firma', '');
		$this->set('cargo_segunda_firma', '');
		$this->set('cedula_segunda_firma', '');

		$this->set('responsa_tercera_firma', '');
		$this->set('funcionario_tercera_firma', '');
		$this->set('cargo_tercera_firma', '');
		$this->set('cedula_tercera_firma', '');

		$this->set('responsa_cuarta_firma', '');
		$this->set('funcionario_cuarta_firma', '');
		$this->set('cargo_cuarta_firma', '');
		$this->set('cedula_cuarta_firma', '');

		$this->set('responsa_quinta_firma', '');
		$this->set('funcionario_quinta_firma', '');
		$this->set('cargo_quinta_firma', '');
		$this->set('cedula_quinta_firma', '');

		$this->set('responsa_sexta_firma', '');
		$this->set('funcionario_sexta_firma', '');
		$this->set('cargo_sexta_firma', '');
		$this->set('cedula_sexta_firma', '');

		$this->set('responsa_septima_firma', '');
		$this->set('funcionario_septima_firma', '');
		$this->set('cargo_septima_firma', '');
		$this->set('cedula_septima_firma', '');

		$this->set('responsa_octava_firma', '');
		$this->set('funcionario_octava_firma', '');
		$this->set('cargo_octava_firma', '');
		$this->set('cedula_octava_firma', '');
	}

	}else{
		$this->set('errorMessage2','Disculpe, No llego el C&oacute;digo del Tipo de Documento para realizar el proceso de firmas');
	} // fin else num_tipo_doc dif. null

} // fin funcion envia_form_firmas


function reporte_emision_nota_debito($ano = null, $select_nota_debito = null){
			$this->layout = "pdf";
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$this->envia_form_firmas(3, 2); // 3: Doc. Nota de Debito, con 2 firmas...

			if($ano != null && $select_nota_debito != null){
					if($select_nota_debito!='NO'){
						$sql_ndebito = "SELECT a.cod_dep, a.clase_orden, a.ano_orden_pago, a.numero_orden_pago, a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.numero_debito,
										(SELECT b.fecha_debito FROM cstd09_notadebito_cuerpo b WHERE a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.cod_dep=b.cod_dep AND a.ano_movimiento=b.ano_movimiento AND a.cod_entidad_bancaria=b.cod_entidad_bancaria AND a.cod_sucursal=b.cod_sucursal AND a.cuenta_bancaria=b.cuenta_bancaria AND a.numero_debito=b.numero_debito) as fecha_debito,
										(SELECT b.concepto FROM cstd09_notadebito_cuerpo b WHERE a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.cod_dep=b.cod_dep AND a.ano_movimiento=b.ano_movimiento AND a.cod_entidad_bancaria=b.cod_entidad_bancaria AND a.cod_sucursal=b.cod_sucursal AND a.cuenta_bancaria=b.cuenta_bancaria AND a.numero_debito=b.numero_debito) as concepto,
										(SELECT b.monto FROM cstd09_notadebito_cuerpo b WHERE a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.cod_dep=b.cod_dep AND a.ano_movimiento=b.ano_movimiento AND a.cod_entidad_bancaria=b.cod_entidad_bancaria AND a.cod_sucursal=b.cod_sucursal AND a.cuenta_bancaria=b.cuenta_bancaria AND a.numero_debito=b.numero_debito) as monto
										FROM cstd09_notadebito_ordenes a WHERE
										a.cod_presi='$cod_presi' AND
										a.cod_entidad='$cod_entidad' AND
										a.cod_tipo_inst='$cod_tipo_inst' AND
										a.cod_inst='$cod_inst' AND
										a.cod_dep='$cod_dep' AND
										a.ano_movimiento='$ano' AND
										a.numero_debito='$select_nota_debito' ORDER BY a.numero_debito, a.cuenta_bancaria, fecha_debito;";
						$notad = $this->cstd09_notadebito_ordenes->execute($sql_ndebito);
						$this->set('tipo_reporte',2);
						$this->set('notad',$notad);
					}else{
						echo "<script>history.back(1)</script>";
					}

				$find_ent_ban = $this->cstd01_entidades_bancarias->findAll(null,null,'cod_entidad_bancaria ASC');
				foreach($find_ent_ban as $ent){
				$ent_ban[$ent['cstd01_entidades_bancarias']['cod_entidad_bancaria']] = $ent['cstd01_entidades_bancarias']['denominacion'];
				}
				$this->set('ent_ban',$ent_ban);
				$this->set('var',"no");
			}else{
				echo "<script>history.back(1)</script>";
			}
}//reporte_emision_nota_debito





function generate_select_numero($var=null){



		$this->layout="ajax";
    	$i = 0;

  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  $year2 = $this->ano_ejecucion();




	if(isset($var) && $var!=""){

		    $radio_1 =  $this->Session->read('radio');
			$cod_1   =  $this->Session->read('cod1');
			$cod_2   =  $this->Session->read('cod2');

			$this->Session->write('cod3',$var);



			$ano     =  $this->Session->read('cod4');
			$cond    =  $this->SQLCA();
			$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";
			$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' and clase_beneficiario=1  ";

           $lista=  $this->cstd09_notadebito_cuerpo_pago->generateList($cond2." and ano_movimiento='".$year2."'    ", 'numero_debito ASC', null, '{n}.cstd09_notadebito_cuerpo_pago.numero_debito', '{n}.cstd09_notadebito_cuerpo_pago.beneficiario');


	}else{$lista="";}//fin else

$this->concatena( $lista, 'lista');

}//fin function





function mostrar($opcion,$var,$codigo=null) {
	$this->layout="ajax";
	if(isset($codigo) && $codigo!=''){
	switch($opcion){
		case 'entidades':
			if(isset($var) && $var=="codigo"){
				//$c=$this->cepd03_ordenpago_tipopago->findByCod_entidad_bancaria($codigo);
				//$this->set("codigo",$c["cepd03_ordenpago_tipopago"]["cod_tipo_pago"]);
				$this->set("codigo",$codigo);
			}else if(isset($var) && $var=="deno"){
				$c=$this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($codigo);
				$this->set("deno",$c["cstd01_entidades_bancarias"]["denominacion"]);
			}

			echo'<script>';
			  echo"document.getElementById('codigo_select_3').innerHTML = '<br>'; ";
			  echo"document.getElementById('deno_select_3').innerHTML = '<br>'; ";
			echo'</script>';
			echo'<script>';
			  echo"document.getElementById('dispo').value = ''; ";
			  echo"document.getElementById('numero_debito').value = ''; ";
			 // echo"document.getElementById('deno_select_3').value = ''; ";
			 echo"document.getElementById('genn_numero_debito').disabled = true; ";
			echo'</script>';


	break;
		case 'sucursales':
			if(isset($var) && $var=="codigo"){
		//$c=$this->cepd03_ordenpago_tipopago->findByCod_entidad_bancaria($codigo);
		//$this->set("codigo",$c["cepd03_ordenpago_tipopago"]["cod_tipo_pago"]);
		$this->set("codigo",$codigo);
	}else if(isset($var) && $var=="deno"){
		$c=$this->cstd01_sucursales_bancarias->findByCod_sucursal($codigo);
		$this->set("deno",$c["cstd01_sucursales_bancarias"]["denominacion"]);
	}
	break;
	}
	}else{
		echo'<script>';
			  echo"document.getElementById('codigo_select_3').innerHTML = '<br>'; ";
			  echo"document.getElementById('deno_select_3').innerHTML = '<br>'; ";
			echo'</script>';
			echo'<script>';
			  echo"document.getElementById('dispo').value = ''; ";
			  echo"document.getElementById('numero_debito').value = ''; ";
			 // echo"document.getElementById('deno_select_3').value = ''; ";
			echo'</script>';
		echo "";
	}

}//fin mostrar




function num_auto ($var=null,$ano=2008) {
		$this->layout="ajax";
//$this->Session->delete('radio');
$this->Session->write('radio',$var);
/*	echo '<script>' .
			'habilita_compromiso();' .
			'</script>';
	if(isset($var) && $var==1){
		//buscar para que el codigo sea automatico
		$v=$this->cstd03_cheque_numero->execute("SELECT numero_control_cheque FROM cstd03_cheque_numero WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." ORDER BY numero_compromiso DESC");
		//print_r($v);
		if($v!=null){
			$numero=$v[0][0]["numero_compromiso"];
			$numero = $numero =="" ? 1 : $numero+1;
		}else{
			$numero=1;
		}
	}else{
		$numero="";
	}
		$this->set("numero",$numero);*/
}//fin num_auto


function num_cheque($var=null){
		$this->layout="ajax";

			$i = 0;

  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  $year2 = $this->ano_ejecucion();

  $numero = "";
 
	if(isset($var) && $var!=""){

		    $radio_1 =  $this->Session->read('radio');
			$cod_1   =  $this->Session->read('cod1');
			$cod_2   =  $this->Session->read('cod2');
			$ano     =  $this->Session->read('cod4');
			$cond    =  $this->SQLCA();
			$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' and situacion=1 ";
			$cond2   = $cond." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";



$this->Session->write('cod_entidad_bancaria_aux',  $cod_1);
$this->Session->write('cod_sucursal_aux',          $cod_2);
$this->Session->write('cuenta_bancaria',           $var);

if ($cod_presi==1 && $cod_entidad==12 && $cod_tipo_inst==30 && $cod_inst==12 && $cod_dep==1){//GOBERNACIÓN DE GUÁRICO
  //$max_numero_deb = $this->cstd09_notadebito_cuerpo_pago->execute("SELECT MAX(numero_debito) AS numero_debito FROM cstd09_notadebito_cuerpo WHERE condicion_actividad=1 and cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$year2."' and cod_entidad_bancaria='".$cod_1."' and cod_sucursal='".$cod_2."' and cuenta_bancaria='".$var."';");
    $max_numero_deb = $this->cstd09_notadebito_cuerpo_pago->execute("SELECT MAX(numero_debito) AS numero_debito FROM cstd09_notadebito_cuerpo WHERE cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$year2."' and cod_entidad_bancaria='".$cod_1."' and cod_sucursal='".$cod_2."' and cuenta_bancaria='".$var."';");
	if($max_numero_deb[0][0]['numero_debito'] != ""){
		//$numero = "";
		$numero = $max_numero_deb[0][0]['numero_debito'] + 1;
		// if($cod_dep==1){
		// 	$numero="";
		// }
	}else{
		$numero = "";
	}
}else{
	$numero = "";
}


$this->Session->write('numero_debito',$numero);

	if($numero != ""){
       echo'<script>';
         echo"document.getElementById('numero_debito').value = '';  ";
         echo"document.getElementById('numero_debito').disabled = false;  ";
         echo"document.getElementById('numero_debito').readOnly = false;  ";

         echo"document.getElementById('numero_debito').value = '".mascara($numero, 9)."';  ";
         echo"document.getElementById('numero_debito').disabled = false;  ";
         echo"document.getElementById('numero_debito').readOnly = true;  ";

      echo'</script>';
	}else{
       echo'<script>';
         echo"document.getElementById('numero_debito').value = '';  ";
         echo"document.getElementById('numero_debito').disabled = false;  ";
         echo"document.getElementById('numero_debito').readOnly = false;  ";
      echo'</script>';
	}



	   }else{$numero="";
	   	   echo'<script>';
	   	     echo"document.getElementById('numero_debito').value = '';  ";
	   	     echo"document.getElementById('numero_debito').readOnly = false;  ";
	   	   echo'</script>'; }//fin else




$this->set("numero",$numero);





}//fin function






function disponibilidad($var=null){
	$this->layout="ajax";
	$disponible = "";
	//$resultado=$this->cstd02_cuentas_bancarias->findByCuenta_bancaria($this->SQLCA().$var);
	if($var!=""){
	  $resultado=$this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cuenta_bancaria='".$var."' ");
	  $disponible=$resultado[0]["cstd02_cuentas_bancarias"]["disponibilidad_libro"];
	}//fin if

	if($disponible!=""){
				 $this->set('disponible',$disponible);
	}else{
		$this->set('disponible'," ");
		echo'<script>';
			  echo"document.getElementById('dispo').value = ''; ";
			 // echo"document.getElementById('deno_select_3').value = ''; ";
			echo'</script>';
	}

}//fin disponibilidad


function select_ordenado2($pista = null) {
	$this->layout = "ajax";

	echo "<script>
			  document.getElementById('bene').value = '';
			  document.getElementById('monto').value = '';
		</script>";

	$ano = $this->ano_ejecucion();
	$cond = $this->SQLCA();
	$cond .= " and ano_orden_pago=".$ano;
	$ordernado = 'numero_orden_pago';

	if($pista != null || $pista != ""){

		if(is_numeric($pista)){ $sql = " (numero_orden_pago::text LIKE '%$pista%') OR ";}else{ $sql = ""; }
		$sql_like = " and ($sql quitar_acentos(beneficiario) LIKE quitar_acentos('%$pista%') OR quitar_acentos(rif) LIKE quitar_acentos('%$pista%'))";
		$cond .= $sql_like;
		$ordernado = 'beneficiario, numero_orden_pago';
	}

	$this->concatena_seis_digitos($this->cepd03_ordenpago_cuerpo->generateList($cond.' and tipo_orden=2  and condicion_actividad=1 and numero_cheque=0', $ordernado.' ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.autorizado'), 'grupo');
}


function datos($opcion,$var=null){
		$this->layout="ajax";
	if(isset($var) && $var!=''){
	switch($opcion){
		case 'bene':
		//$resultado=$this->cepd03_ordenpago_cuerpo->findByNumero_orden_pago($var);
		$resultado=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and numero_orden_pago='".$var."' ");

		$resul=$resultado[0]["cepd03_ordenpago_cuerpo"]["autorizado"];
		//echo $resul;
		echo'<script>';
			  echo"document.getElementById('bene').value = \"$resul\" ; ";
	     echo'</script>';
		$this->set('beneficiario',$resul);
			//$c=$this->cstd01_sucursales_bancarias->findByCod_sucursal($codigo);

	break;
		case 'monto':
		$resultado=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and numero_orden_pago='".$var."' ");
		//$resul=$resultado[0]["cepd03_ordenpago_cuerpo"]["monto_total"];
		$resul=$resultado[0]["cepd03_ordenpago_cuerpo"]["monto_siniva"];
		//echo $resul;
		$this->set('monto',$resul);

	break;
	}
	}else{
		$this->set('beneficiario'," ");
		$this->set('monto'," ");
			echo'<script>';
			  echo"document.getElementById('bene').value = ''; ";
			  echo"document.getElementById('monto').value = ''; ";
			 // echo"document.getElementById('deno_select_3').value = ''; ";
			echo'</script>';
		//$this->set('beneficiario','');
		//$this->set('monto','');
		//echo "";
	}

}//fin mostrar_datos






function guardar(){

  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  $year2 = $this->ano_ejecucion();

  $opcion = 'si';
  $beneficiario = "";
  $cuenta = $_SESSION['CUENTA_ORDENES_PAGO'];

   $numero_comprobante_municipal       =     0;
   $numero_comprobante_timbre          =     0;
   $numero_comprobante_islr            =     0;
   $numero_comprobante_iva             =     0;
   $numero_comprobante_egreso          =     0;
   $numero_comprobante_multa           =     0;
   $numero_comprobante_responsabilidad =     0;


   $this->Session->delete('cod_entidad_bancaria_aux');
   $this->Session->delete('cod_sucursal_aux');
   $this->Session->delete('cuenta_bancaria');
   $this->Session->delete('numero_debito');


//    cstd09_notadebito_cuerpo_pago


  $ano_movimiento                         =         $this->data['cstp09_notadebito_por_cancelar']['ano_movimiento'];
  $ann = $ano_movimiento;
  $cod_entidad_bancaria                   =         $this->data['cstp09_notadebito_por_cancelar']['entidad'];
  $cod_sucursal                           =         $this->data['cstp09_notadebito_por_cancelar']['sucursal'];
  $cuenta_bancaria                        =         $this->data['cstp09_notadebito_por_cancelar']['cuenta'];
  $numero_debito                          =         $this->data['cstp09_notadebito_por_cancelar']['numero_debito'];
  $fecha_debito                           =         $this->Cfecha($this->data['cstp09_notadebito_por_cancelar']['fecha'], 'A-M-D');
  $fd                                     =         $this->data['cstp09_notadebito_por_cancelar']['fecha'];
  $concepto                               =         $this->data['cstp09_notadebito_por_cancelar']['concepto'];


  $status_debito                          =         3;
  $clase_beneficiario                     =         1;

  $rif_cedula                             =         $_SESSION['ORDEN_PAGO_TOTAL']['rif'];
  $cod_tipo_pago                          =         $_SESSION['ORDEN_PAGO_TOTAL']['cod_tipo_pago'];
  $monto                                  =         $_SESSION['ORDEN_PAGO_TOTAL']['monto'];
  $beneficiario                           =         $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['autorizado'];

  if($beneficiario==""){$beneficiario = $this->data['cstp09_notadebito_por_cancelar']['beneficiario2'];}

  $fecha_proceso_registro                 =     date("Y-m-d");
  $dia_asiento_registro                   =     "0";
  $mes_asiento_registro                   =     "0";
  $ano_asiento_registro                   =     "0";
  $numero_asiento_registro                =     "0";
  $username_registro                      =     $_SESSION['nom_usuario'];
  $condicion_actividad                    =     "1";
  $ano_anulacion                          =     "0";
  $numero_anulacion                       =     "0";
  $dia_asiento_anulacion                  =     "0";
  $mes_asiento_anulacion                  =     "0";
  $ano_asiento_anulacion                  =     "0";
  $numero_asiento_anulacion               =     "0";
  $fecha_proceso_anulacion                =     "01/01/1997";
  $username_anulacion                     =     "0";
  $numero_comprobante_egreso              =     "";
  $username                               =     $_SESSION['nom_usuario'];
  $monto_para_actualizar_en_cuenta        =     $monto;
  $ejercicio_anterior                     =     2;


// //print_r($this->data['cstp09_notadebito_por_cancelar']);
// $mes_cierre = $this->cstd09_notadebito_cuerpo_pago->execute("SELECT mes_cierre_mensual FROM ccfd04_cierre_mes WHERE cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_cierre_mensual='".$year2."'");

// $guardar_nota=true;

// for($i=1; $i<=($cuenta-1); $i++){

// 	$fecha_orden_pago_aux =   explode('/',$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['fecha_orden_pago']);
	
// 	if($fecha_orden_pago_aux[1]<=$mes_cierre[0][0]["mes_cierre_mensual"]){
// 		$guardar_nota=false;
// 	}

// }

// if($guardar_nota){

if(!empty($ano_movimiento)){
  if(!empty($cod_entidad_bancaria)){
   if(!empty($cod_sucursal)){
     if(!empty($cuenta_bancaria)){
          if(!empty($numero_debito)){
               if(!empty($fecha_debito)){
                 if(!empty($concepto)){
                   if(!empty($rif_cedula)){
                        if(!empty($cod_tipo_pago)){
                             if(!empty($monto)){
                               if(!empty($beneficiario)){


if($this->cstd09_notadebito_cuerpo_pago->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_movimiento."'  and  cod_entidad_bancaria='".$cod_entidad_bancaria."' and cod_sucursal='".$cod_sucursal."' and cuenta_bancaria='".$cuenta_bancaria."' and numero_debito='".$numero_debito."' ") == 0){


///CONTADORES


 for($i=1; $i<=($cuenta-1); $i++){
		$ejercicio_anterior              =   $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['ejercicio_anterior'];
 }//fin foreach


  $datos_cstd06_comprobante_numero_egreso = $this->cstd06_comprobante_numero_egreso->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_egreso='".$ano_movimiento."'");
  foreach($datos_cstd06_comprobante_numero_egreso as $aux){$numero_comprobante_egreso = $aux['cstd06_comprobante_numero_egreso']['numero_comprobante_egreso'];}
  $numero_comprobante_egreso++;


  $datos_cstd06_comprobante_numero_iva = $this->cstd06_comprobante_numero_iva->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."'  and cod_dep='".$cod_dep."'  and  cod_inst='".$cod_inst."' and ano_comprobante_iva='".$ano_movimiento."'");
  foreach($datos_cstd06_comprobante_numero_iva as $aux_2){$numero_comprobante_iva = $aux_2['cstd06_comprobante_numero_iva']['numero_comprobante_iva'];}
  $numero_comprobante_iva++;


  $datos_cstd06_comprobante_numero_islr = $this->cstd06_comprobante_numero_islr->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_islr='".$ano_movimiento."'");
  foreach($datos_cstd06_comprobante_numero_islr as $aux_3){$numero_comprobante_islr = $aux_3['cstd06_comprobante_numero_islr']['numero_comprobante_islr'];}
  $numero_comprobante_islr++;


  $datos_cstd06_comprobante_numero_timbre = $this->cstd06_comprobante_numero_timbre->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_timbre='".$ano_movimiento."'");
  foreach($datos_cstd06_comprobante_numero_timbre as $aux_4){$numero_comprobante_timbre = $aux_4['cstd06_comprobante_numero_timbre']['numero_comprobante_timbre'];}
  $numero_comprobante_timbre++;


  $datos_cstd06_comprobante_numero_municipal = $this->cstd06_comprobante_numero_municipal->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_municipal='".$ano_movimiento."'");
  foreach($datos_cstd06_comprobante_numero_municipal as $aux_5){$numero_comprobante_municipal = $aux_5['cstd06_comprobante_numero_municipal']['numero_comprobante_municip'];}
  $numero_comprobante_municipal++;


  $datos_cstd06_comprobante_numero_multa = $this->cstd06_comprobante_numero_multa->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_multa='".$ano_movimiento."'");
  foreach($datos_cstd06_comprobante_numero_multa as $aux_6){$numero_comprobante_multa = $aux_6['cstd06_comprobante_numero_multa']['numero_comprobante_multa'];}
  $numero_comprobante_multa++;


  $datos_cstd06_comprobante_numero_responsabilidad = $this->cstd06_comprobante_numero_responsabilidad->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_responsabilidad='".$ano_movimiento."'");
  foreach($datos_cstd06_comprobante_numero_responsabilidad as $aux_7){$numero_comprobante_responsabilidad = $aux_7['cstd06_comprobante_numero_responsabilidad']['numero_comprobante_r'];}
  $numero_comprobante_responsabilidad++;



$resul_dc = $this->cstd02_cuentas_bancarias->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and cod_entidad_bancaria = '" . $cod_entidad_bancaria . "' and   cod_sucursal='" . $cod_sucursal . "' and  cuenta_bancaria='" . $cuenta_bancaria . "' ", null, null, null);
      $disponibilidad_cuenta=0;
          foreach ($resul_dc as $resul_aux) {
            $disponibilidad_cuenta    =    $resul_aux['cstd02_cuentas_bancarias']['disponibilidad_libro'];
          } //fin foreach

      if($disponibilidad_cuenta-$monto>=0){

if($beneficiario!=""){
if($this->cstd06_comprobante_cuerpo_egreso->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_comprobante_egreso='".$ano_movimiento."'  and  numero_comprobante_egreso='".$numero_comprobante_egreso."'  ") == 0){
	if($this->cstd06_comprobante_cuerpo_iva->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_comprobante_iva='".$ano_movimiento."'  and  numero_comprobante_iva='".$numero_comprobante_iva."'  ") == 0 || $_SESSION['ORDEN_PAGO_TOTAL']['HAY_IVA']  ==   "no"){
		if($this->cstd06_comprobante_cuerpo_islr->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_comprobante_islr='".$ano_movimiento."'  and  numero_comprobante_islr='".$numero_comprobante_islr."'  ") == 0  || $_SESSION['ORDEN_PAGO_TOTAL']['HAY_ISRL']  ==   "no"){
			if($this->cstd06_comprobante_cuerpo_timbre->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_comprobante_timbre='".$ano_movimiento."'  and  numero_comprobante_timbre='".$numero_comprobante_timbre."'  ") == 0  || $_SESSION['ORDEN_PAGO_TOTAL']['HAY_TIMBRE']  ==   "no"){
				if($this->cstd06_comprobante_cuerpo_municipal->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_comprobante_municipal='".$ano_movimiento."'  and  numero_comprobante_municipal='".$numero_comprobante_municipal."'  ") == 0  || $_SESSION['ORDEN_PAGO_TOTAL']['HAY_MUNICIPIO']  ==   "no"){
					if($this->cstd06_comprobante_numero_multa->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_comprobante_multa='".$ano_movimiento."'  and  numero_comprobante_multa='".$numero_comprobante_multa."'  ") == 0  || $_SESSION['ORDEN_PAGO_TOTAL']['HAY_RETENCION_MULTA']  ==   "no"){
						if($this->cstd06_comprobante_numero_responsabilidad->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_comprobante_responsabilidad='".$ano_movimiento."'  and  numero_comprobante_responsabilidad='".$numero_comprobante_responsabilidad."'  ") == 0  || $_SESSION['ORDEN_PAGO_TOTAL']['HAY_RETENCION_RESPONSABILIDAD']  ==   "no"){



$this->cstd06_comprobante_numero_egreso->execute(" BEGIN; ");


// cstd06_comprobante_numero_egreso


$ano_comprobante_egreso           =     $ano_movimiento;
$numero_comprobante_egreso        =     0;

if($this->cstd06_comprobante_numero_egreso->findCount("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."'  and ano_comprobante_egreso='".$ano_comprobante_egreso."'")>0){
  $datos_cstd06_comprobante_numero_egreso = $this->cstd06_comprobante_numero_egreso->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_egreso='".$ano_comprobante_egreso."'");
  foreach($datos_cstd06_comprobante_numero_egreso as $aux){$numero_comprobante_egreso = $aux['cstd06_comprobante_numero_egreso']['numero_comprobante_egreso'];}
  $numero_comprobante_egreso++;
  if($this->cstd06_comprobante_numero_egreso->execute("UPDATE cstd06_comprobante_numero_egreso SET numero_comprobante_egreso=".$numero_comprobante_egreso." WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'   and ano_comprobante_egreso='".$ano_comprobante_egreso."'; ")>=1){}else{$opcion = 'no';}//fin else
}else{
$numero_comprobante_egreso++;
$sql_cstd06_comprobante_poremitir_egreso2 = "INSERT INTO cstd06_comprobante_numero_egreso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_egreso, numero_comprobante_egreso)";
$sql_cstd06_comprobante_poremitir_egreso2.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."',  '".$cod_dep."',  '".$ano_comprobante_egreso."', '".$numero_comprobante_egreso."'); ";
if($this->cstd06_comprobante_numero_egreso->execute($sql_cstd06_comprobante_poremitir_egreso2)>=1){}else{$opcion = 'no';}//fin else


}//fin esel


// cstd06_comprobante_cuerpo_egreso

$sql_cstd06_comprobante_cuerpo_egreso = "INSERT INTO cstd06_comprobante_cuerpo_egreso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_egreso, numero_comprobante_egreso, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)";
$sql_cstd06_comprobante_cuerpo_egreso.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_comprobante_egreso."', '".$numero_comprobante_egreso."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."'); ";

if($this->cstd06_comprobante_cuerpo_egreso->execute($sql_cstd06_comprobante_cuerpo_egreso)>=1){}else{$opcion = 'no';}//fin else



//  cstd09_notadebito_cuerpo_pago

$monto = $monto_para_actualizar_en_cuenta;


$sql_cstd09_notadebito_cuerpo_pago = "INSERT INTO cstd09_notadebito_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito, fecha_debito, beneficiario, monto, concepto, rif_cedula, cod_tipo_pago, status_debito, clase_beneficiario, fecha_proceso_registro, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, username_registro, condicion_actividad, ano_anulacion, numero_anulacion, fecha_proceso_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, ano_asiento_anulacion, numero_asiento_anulacion, username_anulacion, numero_comprobante_egreso, ano_anterior) ";
$sql_cstd09_notadebito_cuerpo_pago.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."', '".$fecha_debito."', '".$beneficiario."', '".$monto."', '".$concepto."', '".$rif_cedula."', '".$cod_tipo_pago."', '".$status_debito."', '".$clase_beneficiario."', '".$fecha_proceso_registro."', '".$dia_asiento_registro."', '".$mes_asiento_registro."', '".$ano_asiento_registro."', '".$numero_asiento_registro."', '".$username_registro."', '".$condicion_actividad."', '".$ano_anulacion."', '".$numero_anulacion."', '".$fecha_proceso_anulacion."', '".$dia_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$ano_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$username_anulacion."', '".$numero_comprobante_egreso."', '".$ejercicio_anterior."');";


if($this->cstd09_notadebito_cuerpo_pago->execute($sql_cstd09_notadebito_cuerpo_pago)>=1){}else{$opcion = 'no';}



// cstd09_notadebito_poremitir

$sql_cstd09_notadebito_poremitir = "INSERT INTO cstd09_notadebito_poremitir ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito) ";
$sql_cstd09_notadebito_poremitir.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."');";

if($this->cstd09_notadebito_poremitir->execute($sql_cstd09_notadebito_poremitir)>=1){}else{$opcion = 'no';}







$numero_comprobante_iva           =     0;


if($_SESSION['ORDEN_PAGO_TOTAL']['HAY_IVA']  ==   "si"){
// cstd06_comprobante_numero_iva


  $ano_comprobante_iva              =     $ano_movimiento;


if($this->cstd06_comprobante_numero_iva->findCount("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_iva='".$ano_comprobante_iva."'")>0){
  $datos_cstd06_comprobante_numero_iva = $this->cstd06_comprobante_numero_iva->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."'  and cod_dep='".$cod_dep."'  and  cod_inst='".$cod_inst."' and ano_comprobante_iva='".$ano_comprobante_iva."'");
  foreach($datos_cstd06_comprobante_numero_iva as $aux_2){$numero_comprobante_iva = $aux_2['cstd06_comprobante_numero_iva']['numero_comprobante_iva'];}
  $numero_comprobante_iva++;
  $this->cstd06_comprobante_numero_iva->execute("UPDATE cstd06_comprobante_numero_iva SET numero_comprobante_iva=".$numero_comprobante_iva." WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_iva='".$ano_comprobante_iva."'; ");
}else{
$numero_comprobante_iva++;
$sql_cstd06_comprobante_poremitir_iva2 = "INSERT INTO cstd06_comprobante_numero_iva (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_iva, numero_comprobante_iva)";
$sql_cstd06_comprobante_poremitir_iva2.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."',  '".$cod_dep."',  '".$ano_comprobante_iva."', '".$numero_comprobante_iva."'); ";
if($this->cstd06_comprobante_numero_iva->execute($sql_cstd06_comprobante_poremitir_iva2)>=1){}else{$opcion = 'no';}//fin else

}//fin else


// cstd06_comprobante_cuerpo_iva

$sql_cstd06_comprobante_cuerpo_iva = "INSERT INTO cstd06_comprobante_cuerpo_iva (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_iva, numero_comprobante_iva, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)";
$sql_cstd06_comprobante_cuerpo_iva.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_comprobante_iva."', '".$numero_comprobante_iva."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."'); ";

if($this->cstd06_comprobante_cuerpo_iva->execute($sql_cstd06_comprobante_cuerpo_iva)>=1){}else{$opcion = 'no';}

}//fin if





$numero_comprobante_islr           =     0;

if($_SESSION['ORDEN_PAGO_TOTAL']['HAY_ISRL']  ==   "si"){

// cstd06_comprobante_numero_islr


  $ano_comprobante_islr              =     $ano_movimiento;

if($this->cstd06_comprobante_numero_islr->findCount("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_islr='".$ano_comprobante_islr."'")>0){
  $datos_cstd06_comprobante_numero_islr = $this->cstd06_comprobante_numero_islr->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_islr='".$ano_comprobante_islr."'");
  foreach($datos_cstd06_comprobante_numero_islr as $aux_3){$numero_comprobante_islr = $aux_3['cstd06_comprobante_numero_islr']['numero_comprobante_islr'];}
  $numero_comprobante_islr++;
  $this->cstd06_comprobante_numero_islr->execute("UPDATE cstd06_comprobante_numero_islr SET numero_comprobante_islr=".$numero_comprobante_islr." WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_islr='".$ano_comprobante_islr."'; ");
}else{
$numero_comprobante_islr++;
$sql_cstd06_comprobante_poremitir_islr2 = "INSERT INTO cstd06_comprobante_numero_islr (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_islr, numero_comprobante_islr)";
$sql_cstd06_comprobante_poremitir_islr2.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."',  '".$cod_dep."',  '".$ano_comprobante_islr."', '".$numero_comprobante_islr."'); ";
if($this->cstd06_comprobante_numero_islr->execute($sql_cstd06_comprobante_poremitir_islr2)>=1){}else{$opcion = 'no';}//fin else


}//fin else


// cstd06_comprobante_cuerpo_islr

$sql_cstd06_comprobante_cuerpo_islr = "INSERT INTO cstd06_comprobante_cuerpo_islr (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_islr, numero_comprobante_islr, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)";
$sql_cstd06_comprobante_cuerpo_islr.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_comprobante_islr."', '".$numero_comprobante_islr."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."'); ";

if($this->cstd06_comprobante_cuerpo_islr->execute($sql_cstd06_comprobante_cuerpo_islr)>=1){}else{$opcion = 'no';}

}//fin if




 $numero_comprobante_timbre           =     0;


if($_SESSION['ORDEN_PAGO_TOTAL']['HAY_TIMBRE']  ==   "si"){


// cstd06_comprobante_numero_timbre

  $ano_comprobante_timbre              =     $ano_movimiento;


if($this->cstd06_comprobante_numero_timbre->findCount("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_timbre='".$ano_comprobante_timbre."'")>0){
  $datos_cstd06_comprobante_numero_timbre = $this->cstd06_comprobante_numero_timbre->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_timbre='".$ano_comprobante_timbre."'");
  foreach($datos_cstd06_comprobante_numero_timbre as $aux_4){$numero_comprobante_timbre = $aux_4['cstd06_comprobante_numero_timbre']['numero_comprobante_timbre'];}
  $numero_comprobante_timbre++;
  $this->cstd06_comprobante_numero_timbre->execute("UPDATE cstd06_comprobante_numero_timbre SET numero_comprobante_timbre=".$numero_comprobante_timbre." WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_timbre='".$ano_comprobante_timbre."'; ");
}else{
$numero_comprobante_timbre++;
$sql_cstd06_comprobante_poremitir_timbre2 = "INSERT INTO cstd06_comprobante_numero_timbre (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_timbre, numero_comprobante_timbre)";
$sql_cstd06_comprobante_poremitir_timbre2.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."',  '".$cod_dep."', '".$ano_comprobante_timbre."', '".$numero_comprobante_timbre."'); ";
if($this->cstd06_comprobante_numero_timbre->execute($sql_cstd06_comprobante_poremitir_timbre2)>=1){}else{$opcion = 'no';}//fin else

}//fin else


// cstd06_comprobante_cuerpo_timbre

$sql_cstd06_comprobante_cuerpo_timbre = "INSERT INTO cstd06_comprobante_cuerpo_timbre (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_timbre, numero_comprobante_timbre, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)";
$sql_cstd06_comprobante_cuerpo_timbre.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_comprobante_timbre."', '".$numero_comprobante_timbre."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."'); ";

if($this->cstd06_comprobante_cuerpo_timbre->execute($sql_cstd06_comprobante_cuerpo_timbre)>=1){}else{$opcion = 'no';}

}//fin if




$numero_comprobante_municipal           =     0;

if($_SESSION['ORDEN_PAGO_TOTAL']['HAY_MUNICIPIO']  ==   "si"){

// cstd06_comprobante_numero_municipal

  $ano_comprobante_municipal              =     $ano_movimiento;

if($this->cstd06_comprobante_numero_municipal->findCount("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_municipal='".$ano_comprobante_municipal."'")>0){
  $datos_cstd06_comprobante_numero_municipal = $this->cstd06_comprobante_numero_municipal->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_municipal='".$ano_comprobante_municipal."'");
  foreach($datos_cstd06_comprobante_numero_municipal as $aux_5){$numero_comprobante_municipal = $aux_5['cstd06_comprobante_numero_municipal']['numero_comprobante_municip'];}
  $numero_comprobante_municipal++;
 // print_R($datos_cstd06_comprobante_numero_municipal);

  $this->cstd06_comprobante_numero_municipal->execute("UPDATE cstd06_comprobante_numero_municipal SET numero_comprobante_municipal=".$numero_comprobante_municipal." WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_municipal='".$ano_comprobante_municipal."'; ");
}else{

$numero_comprobante_municipal++;
$sql_cstd06_comprobante_poremitir_municipal2 = "INSERT INTO cstd06_comprobante_numero_municipal (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_municipal, numero_comprobante_municipal)";
$sql_cstd06_comprobante_poremitir_municipal2.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."',  '".$cod_dep."',  '".$ano_comprobante_municipal."', '".$numero_comprobante_municipal."'); ";
if($this->cstd06_comprobante_numero_municipal->execute($sql_cstd06_comprobante_poremitir_municipal2)>=1){}else{$opcion = 'no';}//fin else



}//fin else



// cstd06_comprobante_cuerpo_municipal

$sql_cstd06_comprobante_cuerpo_municipal = "INSERT INTO cstd06_comprobante_cuerpo_municipal (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_municipal, numero_comprobante_municipal, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)";
$sql_cstd06_comprobante_cuerpo_municipal.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_comprobante_municipal."', '".$numero_comprobante_municipal."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."'); ";

if($this->cstd06_comprobante_cuerpo_municipal->execute($sql_cstd06_comprobante_cuerpo_municipal)>=1){}else{$opcion = 'no';}

}//fin if










$numero_comprobante_multa           =     0;

if($_SESSION['ORDEN_PAGO_TOTAL']['HAY_RETENCION_MULTA']  ==   "si"){

// cstd06_comprobante_numero_multa

  $ano_comprobante_multa              =     $ano_movimiento;

		if($this->cstd06_comprobante_numero_multa->findCount("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_multa='".$ano_comprobante_multa."'")>0){
				  $datos_cstd06_comprobante_numero_multa = $this->cstd06_comprobante_numero_multa->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_multa='".$ano_comprobante_multa."'");
				  foreach($datos_cstd06_comprobante_numero_multa as $aux_6){$numero_comprobante_multa = $aux_6['cstd06_comprobante_numero_multa']['numero_comprobante_multa'];}
				  $numero_comprobante_multa++;


				  $this->cstd06_comprobante_numero_multa->execute("UPDATE cstd06_comprobante_numero_multa SET numero_comprobante_multa=".$numero_comprobante_multa." WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_multa='".$ano_comprobante_multa."'; ");
		}else{

				  $numero_comprobante_multa++;
				  $sql_cstd06_comprobante_poremitir_multa2 = "INSERT INTO cstd06_comprobante_numero_multa (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_multa, numero_comprobante_multa)";
				  $sql_cstd06_comprobante_poremitir_multa2.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."',  '".$cod_dep."',  '".$ano_comprobante_multa."', '".$numero_comprobante_multa."'); ";
				  if($this->cstd06_comprobante_numero_multa->execute($sql_cstd06_comprobante_poremitir_multa2)>=1){}else{$opcion = 'no';}//fin else



		}//fin else

// cstd06_comprobante_cuerpo_multa

$sql_cstd06_comprobante_cuerpo_multa = "INSERT INTO cstd06_comprobante_cuerpo_multa (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_multa, numero_comprobante_multa, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)";
$sql_cstd06_comprobante_cuerpo_multa.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_comprobante_multa."', '".$numero_comprobante_multa."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."'); ";

if($this->cstd06_comprobante_cuerpo_multa->execute($sql_cstd06_comprobante_cuerpo_multa)>=1){}else{$opcion = 'no';}

}//fin if






















$numero_comprobante_responsabilidad           =     0;

if($_SESSION['ORDEN_PAGO_TOTAL']['HAY_RETENCION_RESPONSABILIDAD']  ==   "si"){

// cstd06_comprobante_numero_responsabilidad

  $ano_comprobante_responsabilidad              =     $ano_movimiento;

		if($this->cstd06_comprobante_numero_responsabilidad->findCount("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_responsabilidad='".$ano_comprobante_responsabilidad."'")>0){
				  $datos_cstd06_comprobante_numero_responsabilidad = $this->cstd06_comprobante_numero_responsabilidad->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_responsabilidad='".$ano_comprobante_responsabilidad."'");
				  foreach($datos_cstd06_comprobante_numero_responsabilidad as $aux_7){$numero_comprobante_responsabilidad = $aux_7['cstd06_comprobante_numero_responsabilidad']['numero_comprobante_r'];}
				  $numero_comprobante_responsabilidad++;


				  $this->cstd06_comprobante_numero_responsabilidad->execute("UPDATE cstd06_comprobante_numero_responsabilidad SET numero_comprobante_responsabilidad=".$numero_comprobante_responsabilidad." WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_responsabilidad='".$ano_comprobante_responsabilidad."'; ");
		}else{

				  $numero_comprobante_responsabilidad++;
				  $sql_cstd06_comprobante_poremitir_responsabilidad2 = "INSERT INTO cstd06_comprobante_numero_responsabilidad (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_responsabilidad, numero_comprobante_responsabilidad)";
				  $sql_cstd06_comprobante_poremitir_responsabilidad2.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."',  '".$cod_dep."',  '".$ano_comprobante_responsabilidad."', '".$numero_comprobante_responsabilidad."'); ";
				  if($this->cstd06_comprobante_numero_responsabilidad->execute($sql_cstd06_comprobante_poremitir_responsabilidad2)>=1){}else{$opcion = 'no';}//fin else



		}//fin else

// cstd06_comprobante_cuerpo_responsabilidad

$sql_cstd06_comprobante_cuerpo_responsabilidad = "INSERT INTO cstd06_comprobante_cuerpo_responsabilidad (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_responsabilidad, numero_comprobante_responsabilidad, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)";
$sql_cstd06_comprobante_cuerpo_responsabilidad.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_comprobante_responsabilidad."', '".$numero_comprobante_responsabilidad."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."'); ";

if($this->cstd06_comprobante_cuerpo_responsabilidad->execute($sql_cstd06_comprobante_cuerpo_responsabilidad)>=1){}else{$opcion = 'no';}

}//fin if










$contador_contabilidad=0;
$monto_retenciones["monto_neto_orden"]        = 0;
$monto_retenciones["monto_total_retenciones"] = 0;




 for($i=1; $i<=($cuenta-1); $i++){

$ano              =   $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['ano_orden_pago'];
$var              =   $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['numero_orden_pago'];
$fecha_orden_pago =   $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['fecha_orden_pago'];


if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar'] != "no"){



$datos_orden_pago_partidas = $this->cepd03_ordenpago_partidas->findAll($condicion. ' and  ano_orden_pago='.$ano.'  and  numero_orden_pago='.$var, null, 'ano_orden_pago, numero_orden_pago, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar DESC');
$datos_orden_pago_cuerpo   = $this->cepd03_ordenpago_cuerpo->findAll(  $condicion. ' and  ano_orden_pago='.$ano.'  and  numero_orden_pago='.$var);








foreach($datos_orden_pago_cuerpo as $contabilidad_ve_contabilidad){


			  $contabilidad_contabilidad_cod_presi                      =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_presi'];
			  $contabilidad_cod_entidad                                 =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_entidad'];
			  $contabilidad_cod_tipo_inst                               =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_tipo_inst'];
			  $contabilidad_cod_inst                                    =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_inst'];
			  $contabilidad_cod_dep                                     =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_dep'];
			  $contabilidad_ano_orden_pago                              =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
			  $contabilidad_numero_orden_pago                           =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_orden_pago'];
			  $contabilidad_tipo_orden                                  =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['tipo_orden'];
			  $contabilidad_fecha_orden_pago                            =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['fecha_orden_pago'];
			  $contabilidad_ano_documento_origen                        =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['ano_documento_origen'];
			  $contabilidad_numero_documento_origen                     =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_documento_origen'];
			  $contabilidad_numero_documento_adjunto                    =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_documento_adjunto'];
			  $contabilidad_cod_tipo_documento                          =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_tipo_documento'];
			  $contabilidad_rif                                         =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['rif'];
			  $contabilidad_beneficiario                                =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['beneficiario'];
			  $contabilidad_autorizado                                  =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['autorizado'];
			  $contabilidad_cedula_identidad                            =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cedula_identidad'];
			  $contabilidad_concepto                                    =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['concepto'];
			  $contabilidad_monto_total                                 =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_total'];
			  $contabilidad_numero_pago                                 =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_pago'];
			  $contabilidad_monto_parcial                               =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_parcial'];
			  $contabilidad_cod_frecuencia_pago                         =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_frecuencia_pago'];
			  $contabilidad_fecha_desde                                 =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['fecha_desde'];
			  $contabilidad_fecha_hasta                                 =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['fecha_hasta'];
			  $contabilidad_cod_tipo_pago                               =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_tipo_pago'];
			  $contabilidad_monto_coniva                                =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_coniva'];
			  $contabilidad_monto_iva                                   =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_iva'];
			  $contabilidad_porcentaje_iva                              =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['porcentaje_iva'];
			  $contabilidad_monto_siniva                                =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_siniva'];
			  $contabilidad_monto_retencion_laboral                     =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_retencion_laboral'];
			  $contabilidad_monto_retencion_fielcumplimiento            =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_retencion_fielcumplimiento'];
			  $contabilidad_monto_descontar_impuesto                    =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_descontar_impuesto'];
			  $contabilidad_amortizacion_anticipo                       =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['amortizacion_anticipo'];
			  $contabilidad_monto_orden_pago                            =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_orden_pago'];
			  $contabilidad_monto_retencion_iva                         =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_retencion_iva'];
			  $contabilidad_porcentaje_retencion_iva                    =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['porcentaje_retencion_iva'];
			  $contabilidad_monto_islr                                  =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_islr'];
			  $contabilidad_porcentaje_islr                             =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['porcentaje_islr'];
			  $contabilidad_monto_sustraendo                            =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_sustraendo'];
			  $contabilidad_monto_timbre_fiscal                         =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_timbre_fiscal'];
			  $contabilidad_porcentaje_timbre_fiscal                    =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['porcentaje_timbre_fiscal'];
			  $contabilidad_monto_impuesto_municipal                    =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_impuesto_municipal'];
			  $contabilidad_porcentaje_impuesto_municipal               =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['porcentaje_impuesto_municipal'];
			  $contabilidad_monto_neto_cobrar                           =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['monto_neto_cobrar'];
			  $contabilidad_dia_asiento_registro                        =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['dia_asiento_registro'];
			  $contabilidad_mes_asiento_registro                        =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['mes_asiento_registro'];
			  $contabilidad_ano_asiento_registro                        =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['ano_asiento_registro'];
			  $contabilidad_numero_asiento_registro                     =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_asiento_registro'];
			  $contabilidad_username_registro                           =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['username_registro'];
			  $contabilidad_condicion_actividad                         =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['condicion_actividad'];
			  $contabilidad_ano_anulacion                               =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['ano_anulacion'];
			  $contabilidad_numero_anulacion                            =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_anulacion'];
			  $contabilidad_dia_asiento_anulacion                       =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['dia_asiento_anulacion'];
			  $contabilidad_mes_asiento_anulacion                       =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['mes_asiento_anulacion'];
			  $contabilidad_ano_asiento_anulacion                       =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['ano_asiento_anulacion'];
			  $contabilidad_numero_asiento_anulacion                    =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_asiento_anulacion'];
			  $contabilidad_username_anulacion                          =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['username_anulacion'];
			  $contabilidad_cod_entidad_bancaria                        =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria'];
			  $contabilidad_cod_sucursal                                =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cod_sucursal'];
			  $contabilidad_cuenta_bancaria                             =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['cuenta_bancaria'];
			  $contabilidad_numero_cheque_op                            =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_cheque'];
			  $contabilidad_fecha_cheque                                =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['fecha_cheque'];
			  $contabilidad_fecha_proceso_registro                      =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['fecha_proceso_registro'];
			  $contabilidad_fecha_proceso_anulacion                     =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['fecha_proceso_anulacion'];
			  $contabilidad_numero_comprobante_islr                     =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_comprobante_islr'];
			  $contabilidad_numero_comprobante_timbre                   =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_comprobante_timbre'];
			  $contabilidad_numero_comprobante_municipal                =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_comprobante_municipal'];
			  $contabilidad_numero_comprobante_iva                      =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_comprobante_iva'];
			  $contabilidad_numero_comprobante_librocompras             =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['numero_comprobante_librocompras'];

			  $contabilidad_retencion_multa                             =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['retencion_multa'];
			  $contabilidad_retencion_responsabilidad                   =    $contabilidad_ve_contabilidad['cepd03_ordenpago_cuerpo']['retencion_responsabilidad'];

			  if($contabilidad_retencion_multa==""){          $contabilidad_retencion_multa           = 0;}
			  if($contabilidad_retencion_responsabilidad==""){$contabilidad_retencion_responsabilidad = 0;}





switch($contabilidad_cod_tipo_documento){ //switch Tipo de operación

         case 1:{//REGISTRO DE COMPROMISO
              $datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento='".$contabilidad_ano_documento_origen."' and numero_documento='".$contabilidad_numero_documento_origen."'   ");

              $contabilidad_f_dc_adj_array_pago_aux = null;
              $contabilidad_f_dc_array_pago_aux     = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"];
         }break;


         case 2:{//Anticipo Orden de compra
               $datos  = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."' and numero_anticipo='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(     $this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
         }break;


         case 3:{//Autorización de Orden de compra
               $datos  = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."' and numero_pago='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(         $this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
         }break;


          case 4:{//Anticipo CONTRATO DE OBRA
               $datos  = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."' and numero_anticipo='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(         $this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
          }break;


          case 5:{//VALUACIÓN DE CONTRATO DE OBRA
               $datos  = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."' and numero_valuacion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
          }break;


          case 6:{//RETENCIÓN DE CONTRATO DE OBRA
               $datos  = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."' and numero_retencion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
          }break;


          case 7:{//Anticipo CONTRATO DE SERVICIO
               $datos  = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."' and numero_anticipo='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(         $this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
          }break;


          case 8:{//VALUACIÓN DE CONTRATO DE SERVICIO
               $datos  = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."' and numero_valuacion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
          }break;


          case 9:{//RETENCIÓN DE CONTRATO DE SERVICIO
               $datos  = $this->cepd02_contratoservicio_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."' and numero_retencion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
          }break;

          case 10:{//RETENCIÓN DE ORDENES DE COMPRAS
               $datos  = $this->cscd04_ordencompra_retencion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."' and numero_retencion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(          $this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_retencion_cuerpo"]["fecha_retencion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
          }break;

}//fin switch

$contador_contabilidad++;

$suma_retencion  = $contabilidad_monto_retencion_iva;
$suma_retencion += $contabilidad_monto_islr;
$suma_retencion += $contabilidad_monto_timbre_fiscal;
$suma_retencion += $contabilidad_monto_impuesto_municipal;
$suma_retencion += $contabilidad_retencion_multa;
$suma_retencion += $contabilidad_retencion_responsabilidad;

			  $ano_dc_array_pago_aux[$contador_contabilidad]      = $contabilidad_ano_documento_origen;
		      $n_dc_array_pago_aux[$contador_contabilidad]        = $contabilidad_numero_documento_origen;
		      $f_dc_array_pago_aux[$contador_contabilidad]        = cambia_fecha($contabilidad_f_dc_array_pago_aux);


		      $ano_op_array_pago_aux[$contador_contabilidad]  = $contabilidad_ano_orden_pago;
		      $n_op_array_pago_aux[$contador_contabilidad]    = $contabilidad_numero_orden_pago;
		      $f_op_array_pago_aux[$contador_contabilidad]    = cambia_fecha($contabilidad_fecha_orden_pago);
		      $tipo_op_array_pago_aux[$contador_contabilidad] = $contabilidad_cod_tipo_documento;


		      $n_dc_adj_array_pago_aux[$contador_contabilidad]    = $contabilidad_numero_documento_adjunto;
		      $f_dc_adj_array_pago_aux[$contador_contabilidad]    = cambia_fecha($contabilidad_f_dc_adj_array_pago_aux);



		      $ano_dc_aux  = $contabilidad_ano_documento_origen;
		      $n_dc_aux    = $contabilidad_numero_documento_origen;
		      $f_dc_aux    = cambia_fecha($contabilidad_f_dc_array_pago_aux);

		      $ano_op_aux   = $contabilidad_ano_orden_pago;
		      $n_op_aux     = $contabilidad_numero_orden_pago;
		      $f_op_aux     = cambia_fecha($contabilidad_fecha_orden_pago);

		      $a_adj_op_aux = null;
		      $n_adj_op_aux = $contabilidad_numero_documento_adjunto;
		      $f_adj_op_aux = cambia_fecha($contabilidad_f_dc_adj_array_pago_aux);
		      $tp_op_aux    = $contabilidad_cod_tipo_documento;






         $monto_retenciones["monto_neto_orden"]        += $contabilidad_monto_neto_cobrar;
         $monto_retenciones["monto_total_retenciones"] += $suma_retencion;



}//fin foreach









/*

    $obra_valuacion_cuerpo      = array();
    $servicio_valuacion_cuerpo  = array();
    $compra_autorizacion_cuerpo = array();
    $obra_valuacion_partidas = "";
    $fecha_orden_pago="";
    $servicio_valuacion_partidas = "";
    $compra_autorizacion_partidas = "";
    $incluye_iva="";
    $porcentaje_iva = "";
    $ano_aux              =  $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
    $numero_aux           =  $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['numero_documento_origen'];
    $numero_valuacion_aux =  $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['numero_documento_adjunto'];
    $tipo_documento       =  $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['cod_tipo_documento'];
    $amortizacion         =  $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['amortizacion_anticipo'];
    $porcentaje_iva       =  $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['porcentaje_iva'];
    if($tipo_documento=="5" && $amortizacion!=0){$obra_valuacion_partidas         =   $this->cobd01_contratoobras_valuacion_partidas->findAll($condicion." and ano_contrato_obra='".$ano_aux."' and upper(numero_contrato_obra)='".strtoupper($numero_aux)."'  and numero_valuacion='".$numero_valuacion_aux."' ");             $datos_orden_pago_cuerpo2   =  $this->cepd03_ordenpago_cuerpo->findAll($condicion." and  upper(numero_documento_origen)='".strtoupper($numero_aux)."' and condicion_actividad=1 and (cod_tipo_documento=2 or cod_tipo_documento=4 or cod_tipo_documento=7)"); $fecha_orden_pago = $datos_orden_pago_cuerpo2[0]['cepd03_ordenpago_cuerpo']['fecha_debito'];}
    if($tipo_documento=="8" && $amortizacion!=0 ){$servicio_valuacion_partidas    =   $this->cepd02_contratoservicio_valuacion_partidas->findAll($condicion." and ano_contrato_servicio='".$ano_aux."' and upper(numero_contrato_servicio)='".strtoupper($numero_aux)."'  and numero_valuacion='".$numero_valuacion_aux."' ");  $datos_orden_pago_cuerpo2   =  $this->cepd03_ordenpago_cuerpo->findAll($condicion." and  upper(numero_documento_origen)='".strtoupper($numero_aux)."' and condicion_actividad=1 and (cod_tipo_documento=2 or cod_tipo_documento=4 or cod_tipo_documento=7)"); $fecha_orden_pago = $datos_orden_pago_cuerpo2[0]['cepd03_ordenpago_cuerpo']['fecha_debito'];}
    if($tipo_documento=="3" && $amortizacion!=0 ){$compra_autorizacion_partidas   =   $this->cscd04_ordencompra_a_pago_partidas->findAll($condicion." and ano_orden_compra='".$ano_aux."' and numero_orden_compra='".$numero_aux."' and numero_pago='".$numero_valuacion_aux."' ");                                             $datos_orden_pago_cuerpo2   =  $this->cepd03_ordenpago_cuerpo->findAll($condicion." and  numero_documento_origen='".$numero_aux."' and condicion_actividad=1 and (cod_tipo_documento=2 or cod_tipo_documento=4 or cod_tipo_documento=7)"); $fecha_orden_pago = $datos_orden_pago_cuerpo2[0]['cepd03_ordenpago_cuerpo']['fecha_debito'];}
    if($tipo_documento=="5" && $amortizacion!=0){ $obra_valuacion_cuerpo          =   $this->cobd01_contratoobras_cuerpo->findAll($condicion." and ano_contrato_obra='".$ano_aux."' and upper(numero_contrato_obra)='".strtoupper($numero_aux)."'   ");}
    if($tipo_documento=="8" && $amortizacion!=0 ){$servicio_valuacion_cuerpo      =   $this->cepd02_contratoservicio_cuerpo->findAll($condicion." and ano_contrato_servicio='".$ano_aux."' and upper(numero_contrato_servicio)='".strtoupper($numero_aux)."' ");}
    if($tipo_documento=="3" && $amortizacion!=0){ $compra_autorizacion_cuerpo     =   $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra='".$ano_aux."' and numero_orden_compra='".$numero_aux."'");}
    if(isset($obra_valuacion_cuerpo[0]['cobd01_contratoobras_cuerpo']['anticipo_con_iva'])){        $incluye_iva = $obra_valuacion_cuerpo[0]['cobd01_contratoobras_cuerpo']['anticipo_con_iva'];}
    if(isset($servicio_valuacion_cuerpo[0]['cepd02_contratoservicio_cuerpo']['anticipo_con_iva'])){ $incluye_iva = $servicio_valuacion_cuerpo[0]['cepd02_contratoservicio_cuerpo']['anticipo_con_iva'];}
    if(isset($compra_autorizacion_cuerpo[0]['cscd04_ordencompra_encabezado']['anticipo_con_iva'])){ $incluye_iva = $compra_autorizacion_cuerpo[0]['cscd04_ordencompra_encabezado']['anticipo_con_iva'];}


*/


  $clase_orden                         =         $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['tipo_orden'];
  $ano_orden_pago                      =         $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['ano_orden_pago'];
  $numero_orden_pago                   =         $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['numero_orden_pago'];
  $status                              =         $status_debito;
  $status_retencion                    =         1;





//   cstd09_notadebito_ordenes

$sql_cstd09_notadebito_ordenes = "INSERT INTO cstd09_notadebito_ordenes(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, clase_orden, ano_orden_pago, numero_orden_pago, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito) ";
$sql_cstd09_notadebito_ordenes.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$clase_orden."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."'); ";

if($this->cstd09_notadebito_ordenes->execute($sql_cstd09_notadebito_ordenes)>=1){}else{$opcion = 'no';}



// cstd06_comprobante_poremitir_egreso


$sql_cstd06_comprobante_poremitir_egreso = "INSERT INTO cstd06_comprobante_poremitir_egreso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_egreso, numero_comprobante_egreso, ano_orden_pago, clase_orden, numero_orden_pago, tipo_pago)";
$sql_cstd06_comprobante_poremitir_egreso.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_comprobante_egreso."', '".$numero_comprobante_egreso."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."','1'); ";

if($this->cstd06_comprobante_poremitir_egreso->execute($sql_cstd06_comprobante_poremitir_egreso)>=1){}else{$opcion = 'no';}//fin else






//    cstd07_retenciones_cuerpo_iva

$monto                           =         $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['IVA_TOTAL'];

$sql_cstd07_retenciones_cuerpo_iva = "INSERT INTO cstd07_retenciones_cuerpo_iva ( cod_presi,  cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, monto, fecha_proceso_registro, status, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque, fecha_proceso_anulacion, ano_anterior) ";
$sql_cstd07_retenciones_cuerpo_iva.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$monto."', '".$fecha_debito."', '".$status_retencion."', '0', '0', '0', '0', '0', '".$fecha_proceso_anulacion."', '".$ejercicio_anterior."'); ";



if($this->cstd07_retenciones_cuerpo_iva->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ") == 0){
		if($monto!='no' && $monto!='iva' && $monto!=0){

			if($contabilidad_monto_retencion_iva!=0){
				if(isset($monto_retenciones["monto_iva"])){
					 $monto_retenciones["monto_iva"] += $contabilidad_monto_retencion_iva;
				}else{
					 $monto_retenciones["monto_iva"]  = $contabilidad_monto_retencion_iva;
				}//fin else
             }//fin if


		 if($this->cstd07_retenciones_cuerpo_iva->execute($sql_cstd07_retenciones_cuerpo_iva)>=1){}else{$opcion = 'no';}
		// cstd06_comprobante_poremitir_iva
		$sql_cstd06_comprobante_poremitir_iva = "INSERT INTO cstd06_comprobante_poremitir_iva (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_iva, numero_comprobante_iva, ano_orden_pago, clase_orden, numero_orden_pago)";
		$sql_cstd06_comprobante_poremitir_iva.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_comprobante_iva."', '".$numero_comprobante_iva."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."'); ";
		if($this->cstd06_comprobante_poremitir_iva->execute($sql_cstd06_comprobante_poremitir_iva)>=1){}else{$opcion = 'no';}
		 }//fin else
}//fin else


//    cstd07_retenciones_cuerpo_islr

  $monto                           =      $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['ISRL_MONTO_TOTAL'];



$sql_cstd07_retenciones_cuerpo_islr = "INSERT INTO cstd07_retenciones_cuerpo_islr ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, monto, fecha_proceso_registro, status, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque, fecha_proceso_anulacion, ano_anterior) ";
$sql_cstd07_retenciones_cuerpo_islr.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$monto."', '".$fecha_debito."', '".$status_retencion."', '0', '0', '0', '0', '0', '".$fecha_proceso_anulacion."', '".$ejercicio_anterior."'); ";

if($this->cstd07_retenciones_cuerpo_islr->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ") == 0){
		if($monto!='no' && $monto!='iva' && $monto!=0){

			if($contabilidad_monto_islr!=0){
				if(isset($monto_retenciones["monto_islr"])){
					 $monto_retenciones["monto_islr"] += $contabilidad_monto_islr;
				}else{
					 $monto_retenciones["monto_islr"]  = $contabilidad_monto_islr;
				}//fin else
           }//fin if



		  if($this->cstd07_retenciones_cuerpo_islr->execute($sql_cstd07_retenciones_cuerpo_islr)>=1){}else{$opcion = 'no';}//fin else
		// cstd06_comprobante_poremitir_islr
		$sql_cstd06_comprobante_poremitir_islr = "INSERT INTO cstd06_comprobante_poremitir_islr (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_islr, numero_comprobante_islr, ano_orden_pago, clase_orden, numero_orden_pago)";
		$sql_cstd06_comprobante_poremitir_islr.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_comprobante_islr."', '".$numero_comprobante_islr."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."');";
		if($this->cstd06_comprobante_poremitir_islr->execute($sql_cstd06_comprobante_poremitir_islr)>=1){}else{$opcion = 'no';}
		 }//fin else
}//fin else




//    cstd07_retenciones_cuerpo_timbre

  $monto                           =       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['TIMBRE_FISCAL_MONTO_TOTAL'];

$sql_cstd07_retenciones_cuerpo_timbre = "INSERT INTO cstd07_retenciones_cuerpo_timbre (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, monto, fecha_proceso_registro, status, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque, fecha_proceso_anulacion, ano_anterior) ";
$sql_cstd07_retenciones_cuerpo_timbre.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$monto."', '".$fecha_debito."', '".$status_retencion."', '0', '0', '0', '0', '0', '".$fecha_proceso_anulacion."', '".$ejercicio_anterior."');";



if($this->cstd07_retenciones_cuerpo_timbre->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ") == 0){
if($monto!='no'  && $monto!='iva' && $monto!=0){

	if($contabilidad_monto_timbre_fiscal!=0){
			if(isset($monto_retenciones["monto_timbre"])){
				 $monto_retenciones["monto_timbre"] += $contabilidad_monto_timbre_fiscal;
			}else{
				 $monto_retenciones["monto_timbre"]  = $contabilidad_monto_timbre_fiscal;
			}//fin else
	}//fin if


  if($this->cstd07_retenciones_cuerpo_timbre->execute($sql_cstd07_retenciones_cuerpo_timbre)>=1){}else{$opcion = 'no';}//fin else
// cstd06_comprobante_poremitir_timbre
$sql_cstd06_comprobante_poremitir_timbre = "INSERT INTO cstd06_comprobante_poremitir_timbre (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_timbre, numero_comprobante_timbre, ano_orden_pago, clase_orden, numero_orden_pago)";
$sql_cstd06_comprobante_poremitir_timbre.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_comprobante_timbre."', '".$numero_comprobante_timbre."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."');";
if($this->cstd06_comprobante_poremitir_timbre->execute($sql_cstd06_comprobante_poremitir_timbre)>=1){}else{$opcion = 'no';}
 }//fin else
}//fin else



//  cstd07_retenciones_cuerpo_municipal

  $monto                           =       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['IMPUESTO_MUNICIPAL_MONTO_TOTAL'];

$sql_cstd07_retenciones_cuerpo_municipal = "INSERT INTO cstd07_retenciones_cuerpo_municipal ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, monto, fecha_proceso_registro, status, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque, fecha_proceso_anulacion, ano_anterior) ";
$sql_cstd07_retenciones_cuerpo_municipal.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$monto."', '".$fecha_debito."', '".$status_retencion."', '0', '0', '0', '0', '0','".$fecha_proceso_anulacion."', '".$ejercicio_anterior."');";

if($this->cstd07_retenciones_cuerpo_municipal->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ") == 0){
if($monto!='no'  && $monto!='iva' && $monto!=0){


	if($contabilidad_monto_impuesto_municipal!=0){
			if(isset($monto_retenciones["monto_municipal"])){
				 $monto_retenciones["monto_municipal"] += $contabilidad_monto_impuesto_municipal;
			}else{
				 $monto_retenciones["monto_municipal"]  = $contabilidad_monto_impuesto_municipal;
			}//fin else
     }//fin if


   if($this->cstd07_retenciones_cuerpo_municipal->execute($sql_cstd07_retenciones_cuerpo_municipal)>=1){}else{$opcion = 'no';}//fin else
// cstd06_comprobante_poremitir_municipal
$sql_cstd06_comprobante_poremitir_municipal = "INSERT INTO cstd06_comprobante_poremitir_municipal (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_municipal, numero_comprobante_municipal, ano_orden_pago, clase_orden, numero_orden_pago)";
$sql_cstd06_comprobante_poremitir_municipal.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_comprobante_municipal."', '".$numero_comprobante_municipal."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."');";
if($this->cstd06_comprobante_poremitir_municipal->execute($sql_cstd06_comprobante_poremitir_municipal)>=1){}else{$opcion = 'no';}
 }//fin else
}//fin if






//  cstd07_retenciones_cuerpo_multa

  $monto                           =       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['RETENCION_MULTA_TOTAL'];

$sql_cstd07_retenciones_cuerpo_multa = "INSERT INTO cstd07_retenciones_cuerpo_multa ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, monto, fecha_proceso_registro, status, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque, fecha_proceso_anulacion, ano_anterior) ";
$sql_cstd07_retenciones_cuerpo_multa.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$monto."', '".$fecha_debito."', '".$status_retencion."', '0', '0', '0', '0', '0','".$fecha_proceso_anulacion."', '".$ejercicio_anterior."');";

if($this->cstd07_retenciones_cuerpo_multa->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ") == 0){
if($monto!='no'  && $monto!='iva' && $monto!=0){


	if($contabilidad_retencion_multa!=0){
			if(isset($monto_retenciones["monto_multa"])){
				 $monto_retenciones["monto_multa"] += $contabilidad_retencion_multa;
			}else{
				 $monto_retenciones["monto_multa"]  = $contabilidad_retencion_multa;
			}//fin else
	 }//fin if


   if($this->cstd07_retenciones_cuerpo_multa->execute($sql_cstd07_retenciones_cuerpo_multa)>=1){}else{$opcion = 'no';}//fin else
// cstd06_comprobante_poremitir_multa
$sql_cstd06_comprobante_poremitir_multa = "INSERT INTO cstd06_comprobante_poremitir_multa (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_multa, numero_comprobante_multa, ano_orden_pago, clase_orden, numero_orden_pago)";
$sql_cstd06_comprobante_poremitir_multa.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_comprobante_multa."', '".$numero_comprobante_multa."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."');";
if($this->cstd06_comprobante_poremitir_multa->execute($sql_cstd06_comprobante_poremitir_multa)>=1){}else{$opcion = 'no';}
 }//fin else
}//fin if














//  cstd07_retenciones_cuerpo_responsabilidad

  $monto                           =       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['RETENCION_RESPONSABILIDAD_TOTAL'];

$sql_cstd07_retenciones_cuerpo_responsabilidad = "INSERT INTO cstd07_retenciones_cuerpo_responsabilidad ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, monto, fecha_proceso_registro, status, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque, fecha_proceso_anulacion, ano_anterior) ";
$sql_cstd07_retenciones_cuerpo_responsabilidad.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$monto."', '".$fecha_debito."', '".$status_retencion."', '0', '0', '0', '0', '0','".$fecha_proceso_anulacion."', '".$ejercicio_anterior."');";

if($this->cstd07_retenciones_cuerpo_responsabilidad->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ") == 0){
if($monto!='no'  && $monto!='iva' && $monto!=0){


	if($contabilidad_retencion_responsabilidad!=0){
			if(isset($monto_retenciones["monto_responsabilidad"])){
				 $monto_retenciones["monto_responsabilidad"] += $contabilidad_retencion_responsabilidad;
			}else{
				 $monto_retenciones["monto_responsabilidad"]  = $contabilidad_retencion_responsabilidad;
			}//fin else
     }//fin if


   if($this->cstd07_retenciones_cuerpo_responsabilidad->execute($sql_cstd07_retenciones_cuerpo_responsabilidad)>=1){}else{$opcion = 'no';}//fin else
// cstd06_comprobante_poremitir_responsabilidad
$sql_cstd06_comprobante_poremitir_responsabilidad = "INSERT INTO cstd06_comprobante_poremitir_responsabilidad (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_responsabilidad, numero_comprobante_responsabilidad, ano_orden_pago, clase_orden, numero_orden_pago)";
$sql_cstd06_comprobante_poremitir_responsabilidad.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_comprobante_responsabilidad."', '".$numero_comprobante_responsabilidad."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."');";
if($this->cstd06_comprobante_poremitir_responsabilidad->execute($sql_cstd06_comprobante_poremitir_responsabilidad)>=1){}else{$opcion = 'no';}
 }//fin else
}//fin if










	$j = 0;

	$numero_pagado= $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', $conditions = $this->condicionNDEP()." and ano_pagado='$ann'", $order =null);
	if(!empty($numero_pagado)){
		$numero_pagado ++;
		$sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='$ann' and ".$this->condicionNDEP().";";
	}else{
		$numero_pagado = 1;
		$sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_pagado');";
	}
	$sw_numero_pagado = $this->cfpd23_numero_asiento_pagado->query($sql_numero_pagado);


//////////////////////////////////////////////////---------------PARTIDAS-----------------//////////////////////////////////////////////////////////

$x=0;

 if(isset($datos_orden_pago_partidas)){
  if($datos_orden_pago_partidas!=null){
    foreach($datos_orden_pago_partidas as $ve){


//    cstd09_notadebito_partidas


$concate  = $this->AddCeroR2(substr($ve['cepd03_ordenpago_partidas']['cod_partida'], -2) , substr($ve['cepd03_ordenpago_partidas']['cod_partida'], 0, 1 )).'.'.$this->AddCeroR2($ve['cepd03_ordenpago_partidas']['cod_generica']).'.'.$this->AddCeroR2($ve['cepd03_ordenpago_partidas']['cod_especifica']).'.'.$this->AddCeroR2($ve['cepd03_ordenpago_partidas']['cod_sub_espec']);
$concate2 = $this->AddCeroR2(substr($ve['cepd03_ordenpago_partidas']['cod_partida'], -2) , substr($ve['cepd03_ordenpago_partidas']['cod_partida'], 0, 1 ));

  $ano                                 =         $ano_movimiento;
  $cod_sector                          =         $ve['cepd03_ordenpago_partidas']['cod_sector'];
  $cod_programa                        =         $ve['cepd03_ordenpago_partidas']['cod_programa'];
  $cod_sub_prog                        =         $ve['cepd03_ordenpago_partidas']['cod_sub_prog'];
  $cod_proyecto                        =         $ve['cepd03_ordenpago_partidas']['cod_proyecto'];
  $cod_activ_obra                      =         $ve['cepd03_ordenpago_partidas']['cod_activ_obra'];
  $cod_partida                         =         $ve['cepd03_ordenpago_partidas']['cod_partida'];
  $cod_generica                        =         $ve['cepd03_ordenpago_partidas']['cod_generica'];
  $cod_especifica                      =         $ve['cepd03_ordenpago_partidas']['cod_especifica'];
  $cod_sub_espec                       =         $ve['cepd03_ordenpago_partidas']['cod_sub_espec'];
  $cod_auxiliar                        =         $ve['cepd03_ordenpago_partidas']['cod_auxiliar'];
  $monto                               =         $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i][$j]['MONTO_PARTIDA'];
  $numero_control_compromiso           =         $ve['cepd03_ordenpago_partidas']['numero_control_compromiso'];
  $numero_control_causado              =         $ve['cepd03_ordenpago_partidas']['numero_control_causado'];
  $numero_control_pagado               =         $numero_pagado;

 $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
 $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";


    $c                        = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
	$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

	$cp       = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	$to       = 1;
	$td       = 5;
	$ta       = 5;
	$mt       = $monto;
	$ccp      = $concepto;
	$opago    = $numero_orden_pago;
	$opfecha  = $fecha_orden_pago;
	$cbanco   = $cod_entidad_bancaria_aux;
	$ccuenta  = $cuenta_bancaria;
	$ccheque  = $numero_debito;
	$fechache = $fd;
	$rnco     = $numero_control_compromiso;
	$rnca     = $numero_control_causado;
	$rnpa     = $numero_control_pagado;

    if($ejercicio_anterior==1){
			$dnco = 0;
	}else{
		    $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ann, null, null, $opago, $opfecha, $cbanco, $ccuenta, $ccheque, $fechache, $rnco, $rnca, $rnpa, null, $x);
    }


//  cstd09_notadebito_partidas


   $monto                               =         $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i][$j]['MONTO_PARTIDA'];


 $sql_cstd09_notadebito_partidas = "INSERT INTO cstd09_notadebito_partidas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito, clase_orden, ano_orden_pago, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, numero_control_compromiso, numero_control_causado, numero_control_pagado) ";
 $sql_cstd09_notadebito_partidas.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."', '".$clase_orden."', '".$ano_orden_pago."', '".$numero_orden_pago."', '".$ano."', '".$cod_sector."', '".$cod_programa."', '".$cod_sub_prog."', '".$cod_proyecto."', '".$cod_activ_obra."', '".$cod_partida."', '".$cod_generica."', '".$cod_especifica."', '".$cod_sub_espec."', '".$cod_auxiliar."', '".$monto."', '".$rnco."', '".$rnca."', '".$rnpa."'); ";
$x++;

if($monto!=0){
  if($this->cstd09_notadebito_partidas_pago->execute($sql_cstd09_notadebito_partidas)>=1){}else{$opcion = 'no';}//fin else
}



//    cstd07_retenciones_partidas_iva



  $monto                                       =            $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i][$j]['IVA_MONTO'];


$sql_cstd07_retenciones_partidas_iva = "INSERT INTO cstd07_retenciones_partidas_iva ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, numero_control_compromiso, numero_control_causado, numero_control_pagado) ";
$sql_cstd07_retenciones_partidas_iva.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$ano."', '".$cod_sector."', '".$cod_programa."', '".$cod_sub_prog."', '".$cod_proyecto."', '".$cod_activ_obra."', '".$cod_partida."', '".$cod_generica."', '".$cod_especifica."', '".$cod_sub_espec."', '".$cod_auxiliar."', '".$monto."', '".$rnco."', '".$rnca."', '".$rnpa."'); ";

//echo $concate;


if($this->cstd07_retenciones_partidas_iva->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ".$sql_verificar) == 0){
  if(($concate=="4.03.18.01.00"  && $monto!='iva'  && $monto!=0) || ($concate2 == "4.11" && $monto!='iva'  && $monto!=0)){
	  if($this->cstd07_retenciones_partidas_iva->execute($sql_cstd07_retenciones_partidas_iva)>=1){}else{$opcion = 'no';}//fin else
  }//fin else
}//fin if



//    cstd07_retenciones_partidas_islr



  $monto                            =       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i][$j]['ISRL_MONTO'];

$sql_cstd07_retenciones_partidas_islr = "INSERT INTO cstd07_retenciones_partidas_islr ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, numero_control_compromiso, numero_control_causado, numero_control_pagado) ";
$sql_cstd07_retenciones_partidas_islr.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$ano."', '".$cod_sector."', '".$cod_programa."', '".$cod_sub_prog."', '".$cod_proyecto."', '".$cod_activ_obra."', '".$cod_partida."', '".$cod_generica."', '".$cod_especifica."', '".$cod_sub_espec."', '".$cod_auxiliar."', '".$monto."', '".$rnco."', '".$rnca."', '".$rnpa."' );";


if($this->cstd07_retenciones_partidas_islr->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ".$sql_verificar) == 0){
if($monto!='no'  && $monto!='iva' && $monto!=0){
 if($concate!="4.03.18.01.00"){
   if($this->cstd07_retenciones_partidas_islr->execute($sql_cstd07_retenciones_partidas_islr)>=1){}else{$opcion = 'no';}//fin else
 }///fin else
}///fin else
}//fin if


//    cstd07_retenciones_partidas_timbre



  $monto                              =       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i][$j]['TIMBRE_FISCAL_MONTO'];



$sql_cstd07_retenciones_partidas_timbre = "INSERT INTO cstd07_retenciones_partidas_timbre ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, numero_control_compromiso, numero_control_causado, numero_control_pagado) ";
$sql_cstd07_retenciones_partidas_timbre.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$ano."', '".$cod_sector."', '".$cod_programa."', '".$cod_sub_prog."', '".$cod_proyecto."', '".$cod_activ_obra."', '".$cod_partida."', '".$cod_generica."', '".$cod_especifica."', '".$cod_sub_espec."', '".$cod_auxiliar."', '".$monto."', '".$rnco."', '".$rnca."', '".$rnpa."');";

if($this->cstd07_retenciones_partidas_timbre->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ".$sql_verificar) == 0){
if($monto!='no'  && $monto!='iva' && $monto!=0){
 if($concate!="4.03.18.01.00"){
  if($this->cstd07_retenciones_partidas_timbre->execute($sql_cstd07_retenciones_partidas_timbre)>=1){}else{$opcion = 'no';}//fin else
 }//fin else
}//fin else
}//fin



//    cstd07_retenciones_partidas_municipal



  $monto                                =             $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i][$j]['IMPUESTO_MUNICIPAL_MONTO'];


$sql_cstd07_retenciones_partidas_municipal = "INSERT INTO cstd07_retenciones_partidas_municipal (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, numero_control_compromiso,  numero_control_causado, numero_control_pagado) ";
$sql_cstd07_retenciones_partidas_municipal.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$ano."', '".$cod_sector."', '".$cod_programa."', '".$cod_sub_prog."', '".$cod_proyecto."', '".$cod_activ_obra."', '".$cod_partida."', '".$cod_generica."', '".$cod_especifica."', '".$cod_sub_espec."', '".$cod_auxiliar."', '".$monto."', '".$rnco."', '".$rnca."', '".$rnpa."');";

if($this->cstd07_retenciones_partidas_municipal->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ".$sql_verificar) == 0){
if($monto!='no'  && $monto!='iva' && $monto!=0){
 if($concate!="4.03.18.01.00"){
   if($this->cstd07_retenciones_partidas_municipal->execute($sql_cstd07_retenciones_partidas_municipal)>=1){}else{$opcion = 'no';}//fin else
 }//fin else
}//fin else
}//fin



//    cstd07_retenciones_partidas_multa


  $monto                                =             $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i][$j]['RETENCION_MULTA_MONTO'];

$sql_cstd07_retenciones_partidas_multa = "INSERT INTO cstd07_retenciones_partidas_multa (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, numero_control_compromiso,  numero_control_causado, numero_control_pagado) ";
$sql_cstd07_retenciones_partidas_multa.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$ano."', '".$cod_sector."', '".$cod_programa."', '".$cod_sub_prog."', '".$cod_proyecto."', '".$cod_activ_obra."', '".$cod_partida."', '".$cod_generica."', '".$cod_especifica."', '".$cod_sub_espec."', '".$cod_auxiliar."', '".$monto."', '".$rnco."', '".$rnca."', '".$rnpa."');";

if($this->cstd07_retenciones_partidas_multa->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ".$sql_verificar) == 0){
if($monto!='no'  && $monto!='iva'&& $monto!=0){
 if($concate!="4.03.18.01.00"){
   if($this->cstd07_retenciones_partidas_multa->execute($sql_cstd07_retenciones_partidas_multa)>=1){}else{$opcion = 'no';}//fin else
 }//fin else
}//fin else
}//fin


//    cstd07_retenciones_partidas_responsabilidad


  $monto                                =             $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i][$j]['RETENCION_RESPONSABILIDAD_MONTO'];

$sql_cstd07_retenciones_partidas_responsabilidad = "INSERT INTO cstd07_retenciones_partidas_responsabilidad (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, clase_orden, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, numero_control_compromiso,  numero_control_causado, numero_control_pagado) ";
$sql_cstd07_retenciones_partidas_responsabilidad.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_orden_pago."', '".$clase_orden."', '".$numero_orden_pago."', '".$ano."', '".$cod_sector."', '".$cod_programa."', '".$cod_sub_prog."', '".$cod_proyecto."', '".$cod_activ_obra."', '".$cod_partida."', '".$cod_generica."', '".$cod_especifica."', '".$cod_sub_espec."', '".$cod_auxiliar."', '".$monto."', '".$rnco."', '".$rnca."', '".$rnpa."');";

if($this->cstd07_retenciones_partidas_responsabilidad->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$ano_orden_pago."'  and  clase_orden='".$clase_orden."'   and  numero_orden_pago='".$numero_orden_pago."' ".$sql_verificar) == 0){
if($monto!='no'  && $monto!='iva'&& $monto!=0){
 if($concate!="4.03.18.01.00"){
   if($this->cstd07_retenciones_partidas_responsabilidad->execute($sql_cstd07_retenciones_partidas_responsabilidad)>=1){}else{$opcion = 'no';}//fin else
 }//fin else
}//fin else
}//fin





$j++;


    }//fin for
  }//fin if
}//fin if



/////////////////////////////////////////////////////////-------FIN----------//////////////////////////////////////////////////////////////////////////




if($this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_movimiento=".$ano_movimiento.", numero_comprobante_islr=".$numero_comprobante_islr.", numero_comprobante_timbre=".$numero_comprobante_timbre.", numero_comprobante_egreso=".$numero_comprobante_egreso.", numero_comprobante_municipal=".$numero_comprobante_municipal.", numero_comprobante_iva=".$numero_comprobante_iva.",  numero_comprobante_multa=".$numero_comprobante_multa.",  numero_comprobante_responsabilidad=".$numero_comprobante_responsabilidad.", cod_entidad_bancaria = '".$cod_entidad_bancaria."',  cod_sucursal='".$cod_sucursal."', cuenta_bancaria='".$cuenta_bancaria."', numero_cheque=".$numero_debito.", fecha_cheque='".$fecha_debito."', documento_pago='1'      WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."'  and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."'; ")>=1){}else{$opcion = 'no';}//fin else
//if($this->cstd03_cheque_numero->execute("UPDATE cstd03_cheque_numero SET situacion=3   WHERE ".$condicion." and  cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and  numero_debito=".$numero_debito.";" )>=1){}else{$opcion = 'no';}//fin else









// cstd04_movimientos_generales

  $mes                   =  $fecha_debito[5].$fecha_debito[6];
  $dia                   =  $fecha_debito[8].$fecha_debito[9];
  $tipo_documento        =  "3";



/*
  $numero_documento = 0;
  $datos_cstd04_movimientos_generales = $this->cstd04_movimientos_generales->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and ano_movimiento='".$ano_movimiento."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and cod_sucursal = '".$cod_sucursal."' and cuenta_bancaria = '".$cuenta_bancaria."' and mes = '".$mes."' and dia = '".$dia."' and tipo_documento = '".$tipo_documento."' ");
  foreach($datos_cstd04_movimientos_generales as $aux_88){$numero_documento = $aux_88['cstd04_movimientos_generales']['numero_documento'];}
  $numero_documento++;
  */

  }//fin if
 }//fin for I




$monto                             =   $_SESSION['ORDEN_PAGO_TOTAL']['monto'];
$monto_para_actualizar_en_cuenta   =   $_SESSION['ORDEN_PAGO_TOTAL']['monto'];


$sql_cstd04_movimientos_generales = "INSERT INTO cstd04_movimientos_generales( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, mes, dia, tipo_documento, numero_documento, monto) ";
$sql_cstd04_movimientos_generales.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$mes."', '".$dia."', '".$tipo_documento."', '".$numero_debito."', '".$monto."');";
//if($this->cstd04_movimientos_generales->execute($sql_cstd04_movimientos_generales)>=1){}else{$opcion = 'no';}//fin else



if($this->cstd04_movimientos_generales->findCount("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and ano_movimiento='".$ano_movimiento."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and mes = '".$mes."' and  dia = '".$dia."' and  tipo_documento = '".$tipo_documento."' and  numero_documento = '".$numero_debito."' ") !=0){

}else{
	   if($this->cstd04_movimientos_generales->execute($sql_cstd04_movimientos_generales)>=1){}else{$opcion = 'no';}
	}//fin


$mensaje = 0;

   $sql_manuales="insert into cstd03_movimientos_manuales values('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."','".$ano_movimiento."','".$cod_entidad_bancaria."','".$cod_sucursal."','".$cuenta_bancaria."','".$tipo_documento."','".$numero_debito."','".$fecha_debito."','".$beneficiario."','".$monto."','".$concepto."','".$fecha_proceso_registro."','0','0','0','0','".$username_registro."','1','0', '0', '1900/01/01', '0', '0', '0', '0', '0', '0', '0')";
if($this->cstd03_movimientos_manuales->execute($sql_manuales)>=1){}else{$opcion = 'no'; $mensaje=1; }//fin else






$resul = $this->cstd02_cuentas_bancarias->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' ", null, null, null);
foreach($resul as $resul_aux){
	$cheque_dia              =    $resul_aux['cstd02_cuentas_bancarias']['cheque_dia'];
	$cheque_mes              =    $resul_aux['cstd02_cuentas_bancarias']['cheque_mes'];
	$cheque_ano              =    $resul_aux['cstd02_cuentas_bancarias']['cheque_ano'];
	$monto_cheque_por_emitir =    $resul_aux['cstd02_cuentas_bancarias']['monto_cheque_por_emitir'];
	$disponibilidad_libro    =    $resul_aux['cstd02_cuentas_bancarias']['disponibilidad_libro'];
}//fin foreach

    $cheque_dia              +=    $monto_para_actualizar_en_cuenta;
	$cheque_mes              +=    $monto_para_actualizar_en_cuenta;
	$cheque_ano              +=    $monto_para_actualizar_en_cuenta;
	$monto_cheque_por_emitir +=    $monto_para_actualizar_en_cuenta;
	$disponibilidad_libro    -=    $monto_para_actualizar_en_cuenta;

if($this->cstd02_cuentas_bancarias->execute("UPDATE cstd02_cuentas_bancarias SET cheque_dia=".$cheque_dia.", cheque_mes=".$cheque_mes.", cheque_ano=".$cheque_ano.", monto_cheque_por_emitir=".$monto_cheque_por_emitir.", disponibilidad_libro=".$disponibilidad_libro."   WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'; ")>=1){}else{$opcion = 'no';}//fin else




if($opcion=="si"){



	$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(

															      $to      = 1,
															      $td      = 4,
															      $rif_doc = $rif_cedula,
															      $ano_dc  = $ano_dc_aux,
															      $n_dc    = $n_dc_aux,
															      $f_dc    = $f_dc_aux,
															      $cpt_dc  = $concepto,
															      $ben_dc  = $beneficiario,
															      $mon_dc  = $monto_retenciones,

															      $ano_op   = $ano_op_aux,
															      $n_op     = $n_op_aux,
															      $f_op     = $f_op_aux,

															      $a_adj_op = $a_adj_op_aux,
															      $n_adj_op = $n_adj_op_aux,
															      $f_adj_op = $f_adj_op_aux,
															      $tp_op    = $tp_op_aux,

															      $deno_ban_pago  = $cod_entidad_bancaria_aux,
															      $ano_movimiento = $ano_movimiento,
															      $cod_ent_pago   = $cod_entidad_bancaria,
															      $cod_suc_pago   = $cod_sucursal,
															      $cod_cta_pago   = $cuenta_bancaria,

															      $num_che_o_debi  = $numero_debito,
															      $fec_che_o_debi  = $fd,
															      $clas_che_o_debi = $clase_beneficiario,
															      $tipo_che_o_debi = 2,

															      $ano_dc_array_pago     = $ano_dc_array_pago_aux,
															      $n_dc_array_pago       = $n_dc_array_pago_aux,
															      $n_dc_adj_array_pago   = $n_dc_adj_array_pago_aux,
															      $f_dc_array_pago       = $f_dc_array_pago_aux,

															      $ano_op_array_pago  = $ano_op_array_pago_aux,
															      $n_op_array_pago    = $n_op_array_pago_aux,
															      $f_op_array_pago    = $f_op_array_pago_aux,
															      $tipo_op_array_pago = $tipo_op_array_pago_aux,
															      null,
															      $f_dc_adj_array_pago= $f_dc_adj_array_pago_aux

															);





					if($valor_motor_contabilidad==true){

							$this->cstd09_notadebito_cuerpo_pago->execute("COMMIT;");
							$this->set('Message_existe', 'Los datos fueron grabados correctamente');

							$this->set('enano_movimiento', $ano_movimiento);
							$this->set('ennumero_debito', $numero_debito);

					}else{

						    $this->cstd09_notadebito_cuerpo_pago->execute("ROLLBACK;");
							//if($numero_debito!=""){$this->cstd03_cheque_numero->execute("UPDATE cstd03_cheque_numero SET  situacion='1'  WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and numero_debito='".$numero_debito."' ");}
							if($mensaje==1){$this->set('errorMessage', 'La nota de debito ya existe en movimientos manuales');}else{$this->set('errorMessage', 'La datos no fueron almacenados');}


					}//fin else
}else{

$this->cstd09_notadebito_cuerpo_pago->execute("ROLLBACK;");
//if($numero_debito!=""){$this->cstd03_cheque_numero->execute("UPDATE cstd03_cheque_numero SET  situacion='1'  WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and numero_debito='".$numero_debito."' ");}
if($mensaje==1){$this->set('errorMessage', 'La nota de debito ya existe en movimientos manuales');}else{$this->set('errorMessage', 'La datos no fueron almacenados');}

}//fin else




      }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenado verifique el n&uacute;mero de comprobante responsabilidad'); $this->index(); $this->render('index'); }//fin else
     }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenado verifique el n&uacute;mero de comprobante multa'); $this->index(); $this->render('index'); }//fin else
    }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenado verifique el n&uacute;mero de comprobante municipal'); $this->index(); $this->render('index'); }//fin else
   }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenado verifique el n&uacute;mero de comprobante timbre');  $this->index(); $this->render('index'); }//fin else
  }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenado verifique el n&uacute;mero de comprobante i.s.l.r');  $this->index(); $this->render('index'); }//fin else
 }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenado verifique el n&uacute;mero de comprobante i.v.a');  $this->index(); $this->render('index'); }//fin else
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenado verifique el n&uacute;mero de comprobante egreso');  $this->index(); $this->render('index'); }//fin else
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}//fin else
} else { $this->set('errorMessage', 'La cuenta no tiene disponibilidad'); } //fin else disponibilidad cuenta
}else{	$this->set('errorMessage', 'Los datos ya existen');}//fin else



             }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (1)');}
           }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (2)');}
          }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (3)');}
         }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (4)');}
       }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (5)');}
      }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (6)');}
     }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (7)');}
    }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (8)');}
   }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (9)');}
 }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (10)');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados (11)');}

// }else{	$this->set('errorMessage', 'LO SIENTO NO PUEDE REGISTRAR NING&Uacute;N DOCUMENTO EN MESES CERRADOS');}




  $this->Session->delete('ORDEN_PAGO');
  $this->Session->delete('CUENTA_ORDENES_PAGO');
  $this->Session->delete('ORDEN_PAGO_TOTAL');


  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_IVA']        =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_ISRL']       =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_TIMBRE']     =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_MUNICIPIO']  =   "no";





/*
$datos_cheque_cuerpo   = $this->cstd09_notadebito_cuerpo_pago->findAll($condicion. '  and  ano_movimiento='.$ano_movimiento.'  and  numero_debito='.$numero_debito);
$datos_cheque_ordenes  = $this->cstd09_notadebito_ordenes->findAll($condicion. ' and  ano_orden_pago='.$ano_movimiento.'  and  numero_debito='.$numero_debito);
$datos_cheque_partidas = $this->cstd09_notadebito_partidas_pago->findAll($condicion. ' and  ano_orden_pago='.$ano_movimiento.'  and  numero_debito='.$numero_debito);
*/


$datos_cheque_cuerpo   = $this->cstd09_notadebito_cuerpo_pago->findAll($condicion.   " and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and   ano_movimiento=".$ano_movimiento."  and  numero_debito=".$numero_debito);
$datos_cheque_ordenes  = $this->v_cstd09_notadebito_ordenes->findAll($condicion.  " and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_orden_pago=".$ano_movimiento."  and  numero_debito=".$numero_debito);
$datos_cheque_partidas = $this->cstd09_notadebito_partidas_pago->findAll($condicion. " and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_orden_pago=".$ano_movimiento."  and  numero_debito=".$numero_debito);





//$resultado      = $this->cstd02_cuentas_bancarias->findByCuenta_bancaria($cuenta_bancaria);
$resultado=$this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cuenta_bancaria='".$cuenta_bancaria."' ");
$disponibilidad = $resultado[0]["cstd02_cuentas_bancarias"]["disponibilidad_libro"];


$c=$this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
$denominacion_a = $c["cstd01_entidades_bancarias"]["denominacion"];

$b=$this->cstd01_sucursales_bancarias->findByCod_sucursal($cod_sucursal);
$denominacion_b = $b["cstd01_sucursales_bancarias"]["denominacion"];


$this->set('disponibilidad' , $disponibilidad);
$this->set('denominacion_a' , $denominacion_a);
$this->set('denominacion_b' , $denominacion_b);



$this->set('datos_cheque_cuerpo' , $datos_cheque_cuerpo);
$this->set('datos_cheque_ordenes', $datos_cheque_ordenes);
$this->set('datos_cheque_partidas', $datos_cheque_partidas);


$this->index();
$this->render('index');



}//fin guardar









function camio_de_orden_ejercicio($var1=null, $var2=null){
  $this->layout="ajax";

  $this->Session->write('pregunta_ejercicio', $var1);

$ano = $this->ano_ejecucion();

if($var1==1){

  $ano--;

}//fin

         echo'<script>';
			  echo"document.getElementById('ano').value = '".$ano."'; ";
	     echo'</script>';


 $cond= $this->SQLCA()." and ano_orden_pago=".$ano;
 $this->concatena($this->cepd03_ordenpago_cuerpo->generateList($cond.' and tipo_orden=2  and condicion_actividad=1 and numero_cheque=0', 'numero_orden_pago ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.autorizado'), 'grupo');


}//fin function











function consulta_index($var1=null){

$this->layout = "ajax";
$this->Session->delete('radio');
//$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');

$cond= $this->SQLCA();

	$ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
$ano = $this->ano_ejecucion();

 $this->set('ano',$ano);
 $this->Session->write('cod4',$ano);
  $this->Session->delete('ORDEN_PAGO');
  $this->Session->delete('CUENTA_ORDENES_PAGO');
  $this->Session->delete('ORDEN_PAGO_TOTAL');


  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_IVA']        =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_ISRL']       =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_TIMBRE']     =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_MUNICIPIO']  =   "no";



//$cond="cod_presi=1 and cod_entidad=1 and cod_tipo_inst=1 and cod_inst=1 and cod_dep=1 and ano_orden_pago=2008";
 $cond.=" and ano_orden_pago=".$ano;
 $this->AddCero('grupo', $this->cepd03_ordenpago_cuerpo->generateList($cond.' and tipo_orden=2 ', 'numero_orden_pago ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago'));


}//fin index








function consulta($pag_num=null){
  $this->layout = "ajax";

   $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
   $ano = $this->ano_ejecucion();


if(isset($pag_num)){
		    $radio_1 =  $this->Session->read('radio');
			$cod_1   =  $this->Session->read('cod1');
			$cod_2   =  $this->Session->read('cod2');
			$cod_3   =  $this->Session->read('cod3');
			$ano     =  $this->Session->read('cod4');
			$cond    =  $this->SQLCA();
			$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria=".$cod_3." and situacion=1 ";
			$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_debito=".$pag_num;


if(!isset($_SESSION['cod3'])){$cond2   = $this->SQLCA()." and numero_debito=".$pag_num;}


		   $array = $this->cstd09_notadebito_cuerpo_pago->findAll($cond2." and ano_movimiento='".$ano."'  ");

		  $i = 0;
		   foreach($array as $aux){
		 	$numero[$i]['ano_movimiento']              =   $aux['cstd09_notadebito_cuerpo_pago']['ano_movimiento'];
		 	$numero[$i]['numero_debito']               =   $aux['cstd09_notadebito_cuerpo_pago']['numero_debito'];
		 	$numero[$i]['cod_entidad_bancaria']        =   $aux['cstd09_notadebito_cuerpo_pago']['cod_entidad_bancaria'];
		 	$numero[$i]['cod_sucursal']                =   $aux['cstd09_notadebito_cuerpo_pago']['cod_sucursal'];
		 	$numero[$i]['cuenta_bancaria']             =   $aux['cstd09_notadebito_cuerpo_pago']['cuenta_bancaria'];
		 	$numero[$i]['numero_anulacion']            =   $aux['cstd09_notadebito_cuerpo_pago']['numero_anulacion'];
		 	$i++;
		} $i--;


}//fin



if(isset($numero[0]['numero_debito'])){


$datos_cheque_cuerpo   = $this->cstd09_notadebito_cuerpo_pago->findAll($condicion. "   and  cod_entidad_bancaria='".$numero[0]['cod_entidad_bancaria']."'  and  cod_sucursal='".$numero[0]['cod_sucursal']."' and  cuenta_bancaria='".$numero[0]['cuenta_bancaria']."' and   ano_movimiento=".$numero[0]['ano_movimiento']."  and  numero_debito=".$numero[0]['numero_debito']);
$datos_cheque_ordenes  = $this->v_cstd09_notadebito_ordenes->findAll($condicion. "       and  cod_entidad_bancaria='".$numero[0]['cod_entidad_bancaria']."'  and  cod_sucursal='".$numero[0]['cod_sucursal']."' and  cuenta_bancaria='".$numero[0]['cuenta_bancaria']."'  and  ano_movimiento=".$numero[0]['ano_movimiento']."  and  numero_debito=".$numero[0]['numero_debito']);
$datos_cheque_partidas = $this->cstd09_notadebito_partidas_pago->findAll($condicion. " and  cod_entidad_bancaria='".$numero[0]['cod_entidad_bancaria']."'  and  cod_sucursal='".$numero[0]['cod_sucursal']."' and  cuenta_bancaria='".$numero[0]['cuenta_bancaria']."'  and  ano_movimiento=".$numero[0]['ano_movimiento']."  and  numero_debito=".$numero[0]['numero_debito']);



		   $cond2   =  $this->SQLCA()." and ano_movimiento='".$numero[0]['ano_movimiento']."' and cod_entidad_bancaria=".$numero[0]['cod_entidad_bancaria']." and cod_sucursal=".$numero[0]['cod_sucursal']." and cuenta_bancaria='".$numero[0]['cuenta_bancaria']."' and clase_beneficiario=1 ";
           $lista   =  $this->cstd09_notadebito_cuerpo_pago->generateList($cond2, 'numero_debito ASC', null, '{n}.cstd09_notadebito_cuerpo_pago.numero_debito', '{n}.cstd09_notadebito_cuerpo_pago.beneficiario');
$this->concatena($lista, 'lista');


//$resultado      = $this->cstd02_cuentas_bancarias->findByCuenta_bancaria($numero[0]['cuenta_bancaria'] );

$resultado=$this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cuenta_bancaria='".$numero[0]['cuenta_bancaria']."'");
$disponibilidad = $resultado[0]["cstd02_cuentas_bancarias"]["disponibilidad_libro"];

//echo $numero[0]['numero_anulacion'];

	$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($condicion." and numero_acta_anulacion=".$numero[0]['numero_anulacion']." and ano_acta_anulacion=".$numero[0]['ano_movimiento']);

   if($C_A!=null){
          $this->set("concepto_anulacion",$C_A[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"]);
    }else{
          $this->set("concepto_anulacion","");
    }//fin else



$c=$this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($numero[0]['cod_entidad_bancaria'] );
$denominacion_a = $c["cstd01_entidades_bancarias"]["denominacion"];

$b=$this->cstd01_sucursales_bancarias->findByCod_sucursal($numero[0]['cod_sucursal'] );
$denominacion_b = $b["cstd01_sucursales_bancarias"]["denominacion"];


$this->set('disponibilidad' , $disponibilidad);
$this->set('denominacion_a' , $denominacion_a);
$this->set('denominacion_b' , $denominacion_b);



$this->set('datos_cheque_cuerpo' , $datos_cheque_cuerpo);
$this->set('datos_cheque_ordenes', $datos_cheque_ordenes);
$this->set('datos_cheque_partidas', $datos_cheque_partidas);

 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);

}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

 $this->consulta_index();
 $this->render('consulta_index');

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
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


	if(!empty($this->data["cstp09_notadebito_por_cancelar"]["fecha_debito"]) && !empty($this->data["cstp09_notadebito_por_cancelar"]["concepto_anulacion"]) && !empty($this->data["cstp09_notadebito_por_cancelar"]["cod_sucursal"]) && !empty($this->data["cstp09_notadebito_por_cancelar"]["cuenta_bancaria"]) && !empty($this->data["cstp09_notadebito_por_cancelar"]["cod_entidad_bancaria"]) && !empty($this->data["cstp09_notadebito_por_cancelar"]["numero_debito"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		      $tipo_documento           =  251;
			  $concepto_anulacion       =  $this->data["cstp09_notadebito_por_cancelar"]["concepto_anulacion"];
			  $fecha_proceso_anulacion  =  date("d/m/Y");
			  $condicion_documento      =  2;//cuando se guarda es Activo=1
			  $fecha_debito            =   $this->data["cstp09_notadebito_por_cancelar"]["fecha_debito"];
			  $fd = $fecha_debito;
			  $cod_sucursal            =   $this->data["cstp09_notadebito_por_cancelar"]["cod_sucursal"];
			  $ano_movimiento          =   $this->data["cstp09_notadebito_por_cancelar"]["ano_movimiento"];
			  $cuenta_bancaria         =   $this->data["cstp09_notadebito_por_cancelar"]["cuenta_bancaria"];
			  $numero_debito           =   $this->data["cstp09_notadebito_por_cancelar"]["numero_debito"];
			  $cod_entidad_bancaria    =   $this->data["cstp09_notadebito_por_cancelar"]["cod_entidad_bancaria"];

			  $pregunta_ejercicio      =   $this->data["cstp09_notadebito_por_cancelar"]["pregunta_ejercicio"];





   $array = $this->cstd09_notadebito_cuerpo_pago->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and numero_debito=".$numero_debito);
   foreach($array as $aux){$monto_para_actualizar_en_cuenta   = $aux['cstd09_notadebito_cuerpo_pago']['monto'];}


 $year = $this->ano_ejecucion();
  $ano = null;


   $datos_partidas = $this->cstd09_notadebito_partidas_pago->findAll($conditions = $this->condicion()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_debito='$numero_debito'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);




$sql_3="";
foreach($datos_partidas as $aux_cstd09_notadebito_partidas){
  $ano_orden_pago      =    $aux_cstd09_notadebito_partidas['cstd09_notadebito_partidas_pago']['ano_orden_pago'];
  $numero_orden_pago   =    $aux_cstd09_notadebito_partidas['cstd09_notadebito_partidas_pago']['numero_orden_pago'];
if($sql_3==""){$sql_3   .= "  ano_orden_pago='".$ano_orden_pago."'   and  numero_orden_pago='".$numero_orden_pago."'   ";
         }else{$sql_3   .= " or  (ano_orden_pago='".$ano_orden_pago."'    and  numero_orden_pago='".$numero_orden_pago."')  ";}

}//FIN FOR

$datos_ordenes = $this->cepd03_ordenpago_cuerpo->findAll($conditions = $this->condicion()." and (".$sql_3.")", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);





$contador_contabilidad=0;
$monto_retenciones["monto_neto_orden"]        = 0;
$monto_retenciones["monto_total_retenciones"] = 0;

foreach($datos_ordenes as $row22){



			  $contabilidad_contabilidad_cod_presi                      =    $row22['cepd03_ordenpago_cuerpo']['cod_presi'];
			  $contabilidad_cod_entidad                                 =    $row22['cepd03_ordenpago_cuerpo']['cod_entidad'];
			  $contabilidad_cod_tipo_inst                               =    $row22['cepd03_ordenpago_cuerpo']['cod_tipo_inst'];
			  $contabilidad_cod_inst                                    =    $row22['cepd03_ordenpago_cuerpo']['cod_inst'];
			  $contabilidad_cod_dep                                     =    $row22['cepd03_ordenpago_cuerpo']['cod_dep'];
			  $contabilidad_ano_orden_pago                              =    $row22['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
			  $contabilidad_numero_orden_pago                           =    $row22['cepd03_ordenpago_cuerpo']['numero_orden_pago'];
			  $contabilidad_tipo_orden                                  =    $row22['cepd03_ordenpago_cuerpo']['tipo_orden'];
			  $contabilidad_fecha_orden_pago                            =    $row22['cepd03_ordenpago_cuerpo']['fecha_orden_pago'];
			  $contabilidad_ano_documento_origen                        =    $row22['cepd03_ordenpago_cuerpo']['ano_documento_origen'];
			  $contabilidad_numero_documento_origen                     =    $row22['cepd03_ordenpago_cuerpo']['numero_documento_origen'];
			  $contabilidad_numero_documento_adjunto                    =    $row22['cepd03_ordenpago_cuerpo']['numero_documento_adjunto'];
			  $contabilidad_cod_tipo_documento                          =    $row22['cepd03_ordenpago_cuerpo']['cod_tipo_documento'];
			  $contabilidad_rif                                         =    $row22['cepd03_ordenpago_cuerpo']['rif'];
			  $contabilidad_beneficiario                                =    $row22['cepd03_ordenpago_cuerpo']['beneficiario'];
			  $contabilidad_autorizado                                  =    $row22['cepd03_ordenpago_cuerpo']['autorizado'];
			  $contabilidad_cedula_identidad                            =    $row22['cepd03_ordenpago_cuerpo']['cedula_identidad'];
			  $contabilidad_concepto                                    =    $row22['cepd03_ordenpago_cuerpo']['concepto'];
			  $contabilidad_monto_total                                 =    $row22['cepd03_ordenpago_cuerpo']['monto_total'];
			  $contabilidad_numero_pago                                 =    $row22['cepd03_ordenpago_cuerpo']['numero_pago'];
			  $contabilidad_monto_parcial                               =    $row22['cepd03_ordenpago_cuerpo']['monto_parcial'];
			  $contabilidad_cod_frecuencia_pago                         =    $row22['cepd03_ordenpago_cuerpo']['cod_frecuencia_pago'];
			  $contabilidad_fecha_desde                                 =    $row22['cepd03_ordenpago_cuerpo']['fecha_desde'];
			  $contabilidad_fecha_hasta                                 =    $row22['cepd03_ordenpago_cuerpo']['fecha_hasta'];
			  $contabilidad_cod_tipo_pago                               =    $row22['cepd03_ordenpago_cuerpo']['cod_tipo_pago'];
			  $contabilidad_monto_coniva                                =    $row22['cepd03_ordenpago_cuerpo']['monto_coniva'];
			  $contabilidad_monto_iva                                   =    $row22['cepd03_ordenpago_cuerpo']['monto_iva'];
			  $contabilidad_porcentaje_iva                              =    $row22['cepd03_ordenpago_cuerpo']['porcentaje_iva'];
			  $contabilidad_monto_siniva                                =    $row22['cepd03_ordenpago_cuerpo']['monto_siniva'];
			  $contabilidad_monto_retencion_laboral                     =    $row22['cepd03_ordenpago_cuerpo']['monto_retencion_laboral'];
			  $contabilidad_monto_retencion_fielcumplimiento            =    $row22['cepd03_ordenpago_cuerpo']['monto_retencion_fielcumplimiento'];
			  $contabilidad_monto_descontar_impuesto                    =    $row22['cepd03_ordenpago_cuerpo']['monto_descontar_impuesto'];
			  $contabilidad_amortizacion_anticipo                       =    $row22['cepd03_ordenpago_cuerpo']['amortizacion_anticipo'];
			  $contabilidad_monto_orden_pago                            =    $row22['cepd03_ordenpago_cuerpo']['monto_orden_pago'];
			  $contabilidad_monto_retencion_iva                         =    $row22['cepd03_ordenpago_cuerpo']['monto_retencion_iva'];
			  $contabilidad_porcentaje_retencion_iva                    =    $row22['cepd03_ordenpago_cuerpo']['porcentaje_retencion_iva'];
			  $contabilidad_monto_islr                                  =    $row22['cepd03_ordenpago_cuerpo']['monto_islr'];
			  $contabilidad_porcentaje_islr                             =    $row22['cepd03_ordenpago_cuerpo']['porcentaje_islr'];
			  $contabilidad_monto_sustraendo                            =    $row22['cepd03_ordenpago_cuerpo']['monto_sustraendo'];
			  $contabilidad_monto_timbre_fiscal                         =    $row22['cepd03_ordenpago_cuerpo']['monto_timbre_fiscal'];
			  $contabilidad_porcentaje_timbre_fiscal                    =    $row22['cepd03_ordenpago_cuerpo']['porcentaje_timbre_fiscal'];
			  $contabilidad_monto_impuesto_municipal                    =    $row22['cepd03_ordenpago_cuerpo']['monto_impuesto_municipal'];
			  $contabilidad_porcentaje_impuesto_municipal               =    $row22['cepd03_ordenpago_cuerpo']['porcentaje_impuesto_municipal'];
			  $contabilidad_monto_neto_cobrar                           =    $row22['cepd03_ordenpago_cuerpo']['monto_neto_cobrar'];
			  $contabilidad_dia_asiento_registro                        =    $row22['cepd03_ordenpago_cuerpo']['dia_asiento_registro'];
			  $contabilidad_mes_asiento_registro                        =    $row22['cepd03_ordenpago_cuerpo']['mes_asiento_registro'];
			  $contabilidad_ano_asiento_registro                        =    $row22['cepd03_ordenpago_cuerpo']['ano_asiento_registro'];
			  $contabilidad_numero_asiento_registro                     =    $row22['cepd03_ordenpago_cuerpo']['numero_asiento_registro'];
			  $contabilidad_username_registro                           =    $row22['cepd03_ordenpago_cuerpo']['username_registro'];
			  $contabilidad_condicion_actividad                         =    $row22['cepd03_ordenpago_cuerpo']['condicion_actividad'];
			  $contabilidad_ano_anulacion                               =    $row22['cepd03_ordenpago_cuerpo']['ano_anulacion'];
			  $contabilidad_numero_anulacion                            =    $row22['cepd03_ordenpago_cuerpo']['numero_anulacion'];
			  $contabilidad_dia_asiento_anulacion                       =    $row22['cepd03_ordenpago_cuerpo']['dia_asiento_anulacion'];
			  $contabilidad_mes_asiento_anulacion                       =    $row22['cepd03_ordenpago_cuerpo']['mes_asiento_anulacion'];
			  $contabilidad_ano_asiento_anulacion                       =    $row22['cepd03_ordenpago_cuerpo']['ano_asiento_anulacion'];
			  $contabilidad_numero_asiento_anulacion                    =    $row22['cepd03_ordenpago_cuerpo']['numero_asiento_anulacion'];
			  $contabilidad_username_anulacion                          =    $row22['cepd03_ordenpago_cuerpo']['username_anulacion'];
			  $contabilidad_cod_entidad_bancaria                        =    $row22['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria'];
			  $contabilidad_cod_sucursal                                =    $row22['cepd03_ordenpago_cuerpo']['cod_sucursal'];
			  $contabilidad_cuenta_bancaria                             =    $row22['cepd03_ordenpago_cuerpo']['cuenta_bancaria'];
			  $contabilidad_numero_cheque_op                            =    $row22['cepd03_ordenpago_cuerpo']['numero_cheque'];
			  $contabilidad_fecha_cheque                                =    $row22['cepd03_ordenpago_cuerpo']['fecha_cheque'];
			  $contabilidad_fecha_proceso_registro                      =    $row22['cepd03_ordenpago_cuerpo']['fecha_proceso_registro'];
			  $contabilidad_fecha_proceso_anulacion                     =    $row22['cepd03_ordenpago_cuerpo']['fecha_proceso_anulacion'];
			  $contabilidad_numero_comprobante_islr                     =    $row22['cepd03_ordenpago_cuerpo']['numero_comprobante_islr'];
			  $contabilidad_numero_comprobante_timbre                   =    $row22['cepd03_ordenpago_cuerpo']['numero_comprobante_timbre'];
			  $contabilidad_numero_comprobante_municipal                =    $row22['cepd03_ordenpago_cuerpo']['numero_comprobante_municipal'];
			  $contabilidad_numero_comprobante_iva                      =    $row22['cepd03_ordenpago_cuerpo']['numero_comprobante_iva'];
			  $contabilidad_numero_comprobante_librocompras             =    $row22['cepd03_ordenpago_cuerpo']['numero_comprobante_librocompras'];

			  $contabilidad_retencion_multa                             =    $row22['cepd03_ordenpago_cuerpo']['retencion_multa'];
			  $contabilidad_retencion_responsabilidad                   =    $row22['cepd03_ordenpago_cuerpo']['retencion_responsabilidad'];

			  if($contabilidad_retencion_multa==""){          $contabilidad_retencion_multa           = 0;}
			  if($contabilidad_retencion_responsabilidad==""){$contabilidad_retencion_responsabilidad = 0;}


switch($contabilidad_cod_tipo_documento){ //switch Tipo de operación

         case 1:{//REGISTRO DE COMPROMISO
              $datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento='".$contabilidad_ano_documento_origen."' and numero_documento='".$contabilidad_numero_documento_origen."'   ");

              $contabilidad_f_dc_adj_array_pago_aux = null;
              $contabilidad_f_dc_array_pago_aux     = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"];
         }break;


         case 2:{//Anticipo Orden de compra
               $datos  = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."' and numero_anticipo='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(     $this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
         }break;


         case 3:{//Autorización de Orden de compra
               $datos  = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."' and numero_pago='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(         $this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
         }break;


          case 4:{//Anticipo CONTRATO DE OBRA
               $datos  = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."' and numero_anticipo='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(         $this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
          }break;


          case 5:{//VALUACIÓN DE CONTRATO DE OBRA
               $datos  = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."' and numero_valuacion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
          }break;


          case 6:{//RETENCIÓN DE CONTRATO DE OBRA
               $datos  = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."' and numero_retencion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$contabilidad_ano_documento_origen."' and numero_contrato_obra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
          }break;


          case 7:{//Anticipo CONTRATO DE SERVICIO
               $datos  = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."' and numero_anticipo='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(         $this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
          }break;


          case 8:{//VALUACIÓN DE CONTRATO DE SERVICIO
               $datos  = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."' and numero_valuacion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
          }break;


          case 9:{//RETENCIÓN DE CONTRATO DE SERVICIO
               $datos  = $this->cepd02_contratoservicio_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."' and numero_retencion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$contabilidad_ano_documento_origen."' and numero_contrato_servicio='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
          }break;

          case 10:{//RETENCIÓN DE ORDENES DE COMPRAS
               $datos  = $this->cscd04_ordencompra_retencion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."' and numero_retencion='".$contabilidad_numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(          $this->condicion()." and ano_orden_compra='".$contabilidad_ano_documento_origen."' and numero_orden_compra='".$contabilidad_numero_documento_origen."'  ");

               $contabilidad_f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_retencion_cuerpo"]["fecha_retencion"];
               $contabilidad_f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
          }break;

}//fin switch

$contador_contabilidad++;

$suma_retencion  = $contabilidad_monto_retencion_iva;
$suma_retencion += $contabilidad_monto_islr;
$suma_retencion += $contabilidad_monto_timbre_fiscal;
$suma_retencion += $contabilidad_monto_impuesto_municipal;
$suma_retencion += $contabilidad_retencion_multa;
$suma_retencion += $contabilidad_retencion_responsabilidad;

			  $ano_dc_array_pago_aux[$contador_contabilidad]      = $contabilidad_ano_documento_origen;
		      $n_dc_array_pago_aux[$contador_contabilidad]        = $contabilidad_numero_documento_origen;
		      $f_dc_array_pago_aux[$contador_contabilidad]        = cambia_fecha($contabilidad_f_dc_array_pago_aux);


		      $ano_op_array_pago_aux[$contador_contabilidad]  = $contabilidad_ano_orden_pago;
		      $n_op_array_pago_aux[$contador_contabilidad]    = $contabilidad_numero_orden_pago;
		      $f_op_array_pago_aux[$contador_contabilidad]    = cambia_fecha($contabilidad_fecha_orden_pago);
		      $tipo_op_array_pago_aux[$contador_contabilidad] = $contabilidad_cod_tipo_documento;


		      $n_dc_adj_array_pago_aux[$contador_contabilidad]    = $contabilidad_numero_documento_adjunto;
		      $f_dc_adj_array_pago_aux[$contador_contabilidad]    = cambia_fecha($contabilidad_f_dc_adj_array_pago_aux);



		      $ano_dc_aux  = $contabilidad_ano_documento_origen;
		      $n_dc_aux    = $contabilidad_numero_documento_origen;
		      $f_dc_aux    = cambia_fecha($contabilidad_f_dc_array_pago_aux);

		      $ano_op_aux   = $contabilidad_ano_orden_pago;
		      $n_op_aux     = $contabilidad_numero_orden_pago;
		      $f_op_aux     = cambia_fecha($contabilidad_fecha_orden_pago);

		      $a_adj_op_aux = null;
		      $n_adj_op_aux = $contabilidad_numero_documento_adjunto;
		      $f_adj_op_aux = cambia_fecha($contabilidad_f_dc_adj_array_pago_aux);
		      $tp_op_aux    = $contabilidad_cod_tipo_documento;


		      $beneficiario = $contabilidad_autorizado;
		      $rif_cedula   = $contabilidad_rif;


         $monto_retenciones["monto_neto_orden"]        += $contabilidad_monto_neto_cobrar;
         $monto_retenciones["monto_total_retenciones"] += $suma_retencion;


if($this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET  numero_comprobante_islr=0, numero_comprobante_timbre=0, numero_comprobante_egreso=0, numero_comprobante_municipal=0, numero_comprobante_iva=0,  numero_comprobante_multa=0,  numero_comprobante_responsabilidad=0, cod_entidad_bancaria = '0',  cod_sucursal='0', cuenta_bancaria='0', numero_cheque='0'    WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."'  and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' and numero_cheque=".$numero_debito)>=1){}else{$opcion = 'no';}//fin else

if($this->cstd07_retenciones_cuerpo_iva->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."'  and  clase_orden=2   and  numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' and numero_cheque=0  ") != 0){

		 if($contabilidad_monto_retencion_iva!=0){
			if(isset($monto_retenciones["monto_iva"])){
				 $monto_retenciones["monto_iva"] += $contabilidad_monto_retencion_iva;
			}else{
				 $monto_retenciones["monto_iva"]  = $contabilidad_monto_retencion_iva;
			}//fin else
		  }//fin if

		 if($this->cstd07_retenciones_partidas_iva->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' ")>1){}else{$opcion = 'no';}
		 if($this->cstd07_retenciones_cuerpo_iva->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' and numero_cheque=0 ")>1){}else{$opcion = 'no';}//fin else
	}//fin if






	if($this->cstd07_retenciones_cuerpo_islr->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."'  and  clase_orden=2   and  numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' and numero_cheque=0  ") != 0){

		 if($contabilidad_monto_islr!=0){
			if(isset($monto_retenciones["monto_islr"])){
				 $monto_retenciones["monto_islr"] += $contabilidad_monto_islr;
			}else{
				 $monto_retenciones["monto_islr"]  = $contabilidad_monto_islr;
			}//fin else
		 }//fin if

		if($this->cstd07_retenciones_partidas_islr->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' ")>1){}else{$opcion = 'no';}
	    if($this->cstd07_retenciones_cuerpo_islr->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."'  and numero_cheque=0 ")>1){}else{$opcion = 'no';}
	}//fin if







	if($this->cstd07_retenciones_cuerpo_municipal->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."'  and  clase_orden=2   and  numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' and numero_cheque=0  ") != 0){


		if($contabilidad_monto_impuesto_municipal!=0){
			if(isset($monto_retenciones["monto_municipal"])){
				 $monto_retenciones["monto_municipal"] += $contabilidad_monto_impuesto_municipal;
			}else{
				 $monto_retenciones["monto_municipal"]  = $contabilidad_monto_impuesto_municipal;
			}//fin else
		}//fin if

		if($this->cstd07_retenciones_partidas_municipal->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' ")>1){}else{$opcion = 'no';}
		if($this->cstd07_retenciones_cuerpo_municipal->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."'  and numero_cheque=0 ")>1){}else{$opcion = 'no';}
	}//fin if










	if($this->cstd07_retenciones_cuerpo_timbre->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."'  and  clase_orden=2   and  numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' and numero_cheque=0  ") != 0){

		if($contabilidad_monto_timbre_fiscal!=0){
					if(isset($monto_retenciones["monto_timbre"])){
						 $monto_retenciones["monto_timbre"] += $contabilidad_monto_timbre_fiscal;
					}else{
						 $monto_retenciones["monto_timbre"]  = $contabilidad_monto_timbre_fiscal;
					}//fin else
		}//fin if

		if($this->cstd07_retenciones_partidas_timbre->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' ")>1){}else{$opcion = 'no';}
		if($this->cstd07_retenciones_cuerpo_timbre->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."'  and numero_cheque=0 ")>1){}else{$opcion = 'no';}
	}//fin if










	if($this->cstd07_retenciones_cuerpo_multa->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."'  and  clase_orden=2   and  numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' and numero_cheque=0  ") != 0){

		if($contabilidad_retencion_multa!=0){
			if(isset($monto_retenciones["monto_multa"])){
				 $monto_retenciones["monto_multa"] += $contabilidad_retencion_multa;
			}else{
				 $monto_retenciones["monto_multa"]  = $contabilidad_retencion_multa;
			}//fin else
		}//fin if

		if($this->cstd07_retenciones_partidas_multa->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' ")>1){}else{$opcion = 'no';}
		if($this->cstd07_retenciones_cuerpo_multa->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."'  and numero_cheque=0 ")>1){}else{$opcion = 'no';}
	}//fin if








	if($this->cstd07_retenciones_cuerpo_responsabilidad->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."'  and  clase_orden=2   and  numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' and numero_cheque=0  ") != 0){

		if($contabilidad_retencion_responsabilidad!=0){
			if(isset($monto_retenciones["monto_responsabilidad"])){
				 $monto_retenciones["monto_responsabilidad"] += $contabilidad_retencion_responsabilidad;
			}else{
				 $monto_retenciones["monto_responsabilidad"]  = $contabilidad_retencion_responsabilidad;
			}//fin else
		}//fin if

		if($this->cstd07_retenciones_partidas_responsabilidad->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' ")>1){}else{$opcion = 'no';}
		if($this->cstd07_retenciones_cuerpo_responsabilidad->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and clase_orden=2 and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."'  and numero_cheque=0 ")>1){}else{$opcion = 'no';}
	}//fin if




}//fin foreach








$c                        = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];


$clase_beneficiario = 1;


	$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(

															      $to      = 2,
															      $td      = 4,
															      $rif_doc = $rif_cedula,
															      $ano_dc  = $ano_dc_aux,
															      $n_dc    = $n_dc_aux,
															      $f_dc    = $f_dc_aux,
															      $cpt_dc  = $concepto_anulacion,
															      $ben_dc  = $beneficiario,
															      $mon_dc  = $monto_retenciones,

															      $ano_op   = $ano_op_aux,
															      $n_op     = $n_op_aux,
															      $f_op     = $f_op_aux,

															      $a_adj_op = $a_adj_op_aux,
															      $n_adj_op = $n_adj_op_aux,
															      $f_adj_op = $f_adj_op_aux,
															      $tp_op    = $tp_op_aux,

															      $deno_ban_pago  = $cod_entidad_bancaria_aux,
															      $ano_movimiento = $ano_movimiento,
															      $cod_ent_pago   = $cod_entidad_bancaria,
															      $cod_suc_pago   = $cod_sucursal,
															      $cod_cta_pago   = $cuenta_bancaria,

															      $num_che_o_debi  = $numero_debito,
															      $fec_che_o_debi  = $fecha_proceso_anulacion,
															      $clas_che_o_debi = $clase_beneficiario,
															      $tipo_che_o_debi = 2,

															      $ano_dc_array_pago     = $ano_dc_array_pago_aux,
															      $n_dc_array_pago       = $n_dc_array_pago_aux,
															      $n_dc_adj_array_pago   = $n_dc_adj_array_pago_aux,
															      $f_dc_array_pago       = $f_dc_array_pago_aux,

															      $ano_op_array_pago  = $ano_op_array_pago_aux,
															      $n_op_array_pago    = $n_op_array_pago_aux,
															      $f_op_array_pago    = $f_op_array_pago_aux,
															      $tipo_op_array_pago = $tipo_op_array_pago_aux,
															      null,
															      $f_dc_adj_array_pago= $f_dc_adj_array_pago_aux

															);





$incluye_iva="";
$porcentaje_iva = "";

$sql_update_cscd04_partidas ='';
foreach($datos_partidas as $row){
			 	$cbanco                    = $row['cstd09_notadebito_partidas_pago']['cod_entidad_bancaria'];
				$ccuenta                   = $row['cstd09_notadebito_partidas_pago']['cuenta_bancaria'];
				$ccheque                   = $row['cstd09_notadebito_partidas_pago']['numero_debito'];
				$opago                     = $row['cstd09_notadebito_partidas_pago']['numero_orden_pago'];
			 	$ano                       = $row['cstd09_notadebito_partidas_pago']['ano'];
			 	$cod_sector                = $row['cstd09_notadebito_partidas_pago']['cod_sector'];
			 	$cod_programa              = $row['cstd09_notadebito_partidas_pago']['cod_programa'];
			 	$cod_sub_prog              = $row['cstd09_notadebito_partidas_pago']['cod_sub_prog'];
			 	$cod_proyecto              = $row['cstd09_notadebito_partidas_pago']['cod_proyecto'];
			 	$cod_activ_obra            = $row['cstd09_notadebito_partidas_pago']['cod_activ_obra'];
			 	$cod_partida               = $row['cstd09_notadebito_partidas_pago']['cod_partida'];
			 	$cod_generica              = $row['cstd09_notadebito_partidas_pago']['cod_generica'];
			 	$cod_especifica            = $row['cstd09_notadebito_partidas_pago']['cod_especifica'];
			 	$cod_sub_espec             = $row['cstd09_notadebito_partidas_pago']['cod_sub_espec'];
			 	$cod_auxiliar              = $row['cstd09_notadebito_partidas_pago']['cod_auxiliar'];
			 	$monto_partida             = $row['cstd09_notadebito_partidas_pago']['monto'];
			 	$rnco                      = $row['cstd09_notadebito_partidas_pago']['numero_control_compromiso'];
			 	$rnca                      = $row['cstd09_notadebito_partidas_pago']['numero_control_causado'];
			 	$rnpa                      = $row['cstd09_notadebito_partidas_pago']['numero_control_pagado'];


$concate = $this->AddCeroR2(substr( $row['cstd09_notadebito_partidas_pago']['cod_partida'], -2) , substr( $row['cstd09_notadebito_partidas_pago']['cod_partida'], 0, 1 ) ).'.'.$this->AddCeroR2( $row['cstd09_notadebito_partidas_pago']['cod_generica']).'.'.$this->AddCeroR2( $row['cstd09_notadebito_partidas_pago']['cod_especifica']).'.'.$this->AddCeroR2( $row['cstd09_notadebito_partidas_pago']['cod_sub_espec']);

$sql_where = "and ano=$ano and cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";


foreach($datos_ordenes as $row2){
 if($row2['cepd03_ordenpago_cuerpo']['ano_orden_pago'] == $row['cstd09_notadebito_partidas_pago']['ano_orden_pago']  &&  $row2['cepd03_ordenpago_cuerpo']['numero_orden_pago'] == $row['cstd09_notadebito_partidas_pago']['numero_orden_pago']){
   $fecha_orden_pago = $row2['cepd03_ordenpago_cuerpo']['fecha_orden_pago'];

  }//fin
}//fin foreach
				//$monto_cancelado += $monto_partida;

				$cp       = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
				$opfecha  = $fecha_orden_pago;
				$mt       = $monto_partida;
				$ccp      = $concepto_anulacion;
				$fechache = $fd;


				if($pregunta_ejercicio==1){
                   $num_asiento_compromiso = 0;
				}else{
				   $num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 5, 5, date("d/m/Y"), $mt, $ccp, $ano, null, null, $opago, $opfecha, $cbanco, $ccuenta, $ccheque, $fechache, $rnco, $rnca, $rnpa, null, null);
				}


}//fin for


$ano = $this->ano_ejecucion();

$resul = $this->cstd02_cuentas_bancarias->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' ", null, null, null);
foreach($resul as $resul_aux){
	$cheque_dia              =    $resul_aux['cstd02_cuentas_bancarias']['cheque_dia'];
	$cheque_mes              =    $resul_aux['cstd02_cuentas_bancarias']['cheque_mes'];
	$cheque_ano              =    $resul_aux['cstd02_cuentas_bancarias']['cheque_ano'];
	$monto_cheque_por_emitir =    $resul_aux['cstd02_cuentas_bancarias']['monto_cheque_por_emitir'];
	$disponibilidad_libro    =    $resul_aux['cstd02_cuentas_bancarias']['disponibilidad_libro'];
}//fin foreach

    $cheque_dia              -=    $monto_para_actualizar_en_cuenta;
	$cheque_mes              -=    $monto_para_actualizar_en_cuenta;
	$cheque_ano              -=    $monto_para_actualizar_en_cuenta;
	$monto_cheque_por_emitir -=    $monto_para_actualizar_en_cuenta;
	$disponibilidad_libro    +=    $monto_para_actualizar_en_cuenta;



if($this->cstd02_cuentas_bancarias->execute("UPDATE cstd02_cuentas_bancarias SET cheque_dia=".$cheque_dia.", cheque_mes=".$cheque_mes.", cheque_ano=".$cheque_ano.", monto_cheque_por_emitir=".$monto_cheque_por_emitir.", disponibilidad_libro=".$disponibilidad_libro."   WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' ")>=1){}else{$opcion = 'no';}//fin else

             $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano_movimiento." ORDER BY numero_acta_anulacion DESC");

		     if($v!=null){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano_movimiento."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano_movimiento.",1)");
			    $numero=1;
		     }//fin else
             $numero_asiento_anulacion = 0;
			 $R1 = $this->cstd09_notadebito_cuerpo_pago->execute("UPDATE cstd09_notadebito_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.",  condicion_actividad=".$condicion_documento.",   fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'       WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_debito='".$numero_debito."' ");
		     $v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_movimiento.",".$numero.",".$tipo_documento.",".$ano_movimiento.",".$numero_debito.",'".$this->Cfecha($fecha_debito, 'A-M-D')."','".$concepto_anulacion."')");

      $sql="update cstd03_movimientos_manuales set ano_anulacion='".date("Y")."', numero_anulacion='".$numero."', fecha_proceso_anulacion='".$fecha_proceso_anulacion."', dia_asiento_anulacion=0, mes_asiento_anulacion=0, ano_asiento_anulacion=0, numero_asiento_anulacion='0', username_anulacion='".$_SESSION['nom_usuario']."', condicion_actividad='2' where ".$this->SQLCA()." and ano_movimiento='".$ano_movimiento."' and cod_entidad_bancaria='".$cod_entidad_bancaria."' and cod_sucursal='".$cod_sucursal."' and cuenta_bancaria='".$cuenta_bancaria."' and tipo_documento='3' and numero_documento='".$numero_debito."'";
if($this->cstd03_movimientos_manuales->execute($sql)>0){}



      $this->set('Message_existe', 'El registro fue anulado');
}else{$this->set('errorMessage', 'El registro no pudo ser anulado');}




/////////////////////////////////////////////////RETROCEDER CAMBIOS////////////////////////////////////////////////////








///////////////////////////////////////////////FIN--RETROCEDER----CAMBIOS////////////////////////////////////////////////////





$this->consulta_index('1');
$this->render('consulta_index');

/*
echo'<script>';
    echo'document.getElementById("guardar").disabled = true; ';
    echo'document.getElementById("anular").disabled = true; ';
    echo'document.getElementById("condicion_actividad_1").checked = false;';
  	echo'document.getElementById("condicion_actividad_2").checked = true;';
    echo'document.getElementById("a").innerHTML = "'.$ano.'"; ';
    echo'document.getElementById("b").innerHTML = "'.$numero.'"; ';
    echo'document.getElementById("c").innerHTML = "'.$fecha_proceso_anulacion.'"; ';
    echo'document.getElementById("d").innerHTML = "'.date("d").'"; ';
    echo'document.getElementById("d").innerHTML = "'.date("m").'"; ';
    echo'document.getElementById("e").innerHTML = "'.date("Y").'"; ';
    echo'document.getElementById("f").innerHTML = "'.$numero.'"; ';  ///AQUI VA EL NUMERO DE ASIENTO PERO HAY QUE ESPERAR EL DE EL MOTOR
    echo'document.getElementById("g").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';

echo'</script>';

*/

}//fin function



















function datos_imputacion($var=null){

	$this->layout="ajax";


  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  $ano = $this->ano_ejecucion();   $this->set('ano_ejecucion', $ano);

  if($this->Session->read('pregunta_ejercicio')==1){
     $ano--;
  }//fin if

 $cuenta = $_SESSION['CUENTA_ORDENES_PAGO'];
 $verifica = "si";

if($var!=null){

	    //$resultado=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and numero_orden_pago='".$var."' ");
		//$resul=$resultado[0]["cepd03_ordenpago_cuerpo"]["autorizado"];


   $datos_orden_pago_partidas = $this->cepd03_ordenpago_partidas->findAll($condicion. ' and  ano_orden_pago='.$ano.'  and  numero_orden_pago='.$var, null, 'ano_orden_pago, numero_orden_pago, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar DESC');
   $datos_orden_pago_cuerpo   = $this->cepd03_ordenpago_cuerpo->findAll($condicion. ' and  ano_orden_pago='.$ano.'  and  numero_orden_pago='.$var);
   $resul     = $datos_orden_pago_cuerpo[0]["cepd03_ordenpago_cuerpo"]["autorizado"];
   $resul = javascript_encode($resul, 1);

   $rif_input1=0;
   $rif_input2=0;
   $rif_input =0;
   $rif_input1       = $datos_orden_pago_cuerpo[0]["cepd03_ordenpago_cuerpo"]["rif"];
   $rif_input2       = $datos_orden_pago_cuerpo[0]["cepd03_ordenpago_cuerpo"]["cedula_identidad"];
   $fecha_orden_pago = $datos_orden_pago_cuerpo[0]["cepd03_ordenpago_cuerpo"]["fecha_orden_pago"];

if($rif_input2==0){$rif_input=$rif_input1;}else{$rif_input=$rif_input2;}

     echo'<script>';

			  echo"document.getElementById('bene').value = \"$resul\" ; ";
			  echo"document.getElementById('bene2').value = \"$resul\"; ";
			  echo"document.getElementById('rif_input').value = '".$rif_input."'; ";
			  echo"document.getElementById('fecha_op').value  = '".$fecha_orden_pago."'; ";

			  echo"document.getElementById('tipo_documento').value  = '".$datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['cod_tipo_documento']."'; ";
			  echo"document.getElementById('tipo_pago').value       = '".$datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['cod_tipo_pago']."'; ";
	echo'</script>';

   $obra_valuacion_cuerpo      = array();
   $servicio_valuacion_cuerpo  = array();
   $compra_autorizacion_cuerpo = array();
   $obra_valuacion_partidas = "";
   $servicio_valuacion_partidas = "";
   $compra_autorizacion_partidas = "";
   $incluye_iva="";
   $ano_aux              = $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
   $numero_aux           = $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['numero_documento_origen'];
   $numero_valuacion_aux = $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['numero_documento_adjunto'];
   $tipo_documento       = $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['cod_tipo_documento'];
   $amortizacion         = $datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['amortizacion_anticipo'];


   if($tipo_documento=="5"){$obra_valuacion_partidas        =   $this->cobd01_contratoobras_valuacion_partidas->findAll($condicion." and ano_contrato_obra='".$ano_aux."' and upper(numero_contrato_obra)='".strtoupper($numero_aux)."'  and numero_valuacion='".$numero_valuacion_aux."' ");}
   if($tipo_documento=="8"){$servicio_valuacion_partidas    =   $this->cepd02_contratoservicio_valuacion_partidas->findAll($condicion." and ano_contrato_servicio='".$ano_aux."' and upper(numero_contrato_servicio)='".strtoupper($numero_aux)."'  and numero_valuacion='".$numero_valuacion_aux."' ");}
   if($tipo_documento=="3" ){$compra_autorizacion_partidas   =   $this->cscd04_ordencompra_a_pago_partidas->findAll($condicion." and ano_orden_compra='".$ano_aux."' and numero_orden_compra='".$numero_aux."' and numero_pago='".$numero_valuacion_aux."' ");}

   if($tipo_documento=="5" ){ $obra_valuacion_cuerpo        =   $this->cobd01_contratoobras_cuerpo->findAll($condicion." and ano_contrato_obra='".$ano_aux."' and upper(numero_contrato_obra)='".strtoupper($numero_aux)."'   ");}
   if($tipo_documento=="8" ){$servicio_valuacion_cuerpo    =   $this->cepd02_contratoservicio_cuerpo->findAll($condicion." and ano_contrato_servicio='".$ano_aux."' and upper(numero_contrato_servicio)='".strtoupper($numero_aux)."' ");}
   if($tipo_documento=="3" ){ $compra_autorizacion_cuerpo   =   $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra='".$ano_aux."' and numero_orden_compra='".$numero_aux."'");}
   if(isset($obra_valuacion_cuerpo[0]['cobd01_contratoobras_cuerpo']['anticipo_con_iva'])){ $incluye_iva = $obra_valuacion_cuerpo[0]['cobd01_contratoobras_cuerpo']['anticipo_con_iva'];}
   if(isset($servicio_valuacion_cuerpo[0]['cepd02_contratoservicio_cuerpo']['anticipo_con_iva'])){ $incluye_iva = $servicio_valuacion_cuerpo[0]['cepd02_contratoservicio_cuerpo']['anticipo_con_iva'];}
   if(isset($compra_autorizacion_cuerpo[0]['cscd04_ordencompra_encabezado']['anticipo_con_iva'])){$incluye_iva = $compra_autorizacion_cuerpo[0]['cscd04_ordencompra_encabezado']['anticipo_con_iva'];}
   $this->set('anticipo_con_iva',             $incluye_iva);
   $this->set('obra_valuacion_partidas',      $obra_valuacion_partidas);
   $this->set('servicio_valuacion_partidas',  $servicio_valuacion_partidas);
   $this->set('compra_autorizacion_partidas', $compra_autorizacion_partidas);




if(isset($this->data['cstp09_notadebito_por_cancelar']['beneficiario'])){
for($i=1; $i<=($cuenta-1); $i++){
 if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no" && (strtoupper($this->data['cstp09_notadebito_por_cancelar']['beneficiario'])!=strtoupper($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['autorizado']))){
   $verifica = "no";
 }//fin if
}//fin for


if($verifica=="si"){

     echo'<script>'; echo"document.getElementById('enviar_orden').disabled = true;"; echo'</script>';
            }else{
                  echo'<script>'; echo"document.getElementById('enviar_orden').disabled = false;"; echo'</script>';
            }//else
}else{
    echo'<script>';  echo"document.getElementById('enviar_orden').disabled = false;"; echo'</script>';
}//else






}else{

	$datos_orden_pago_partidas = "";
    $datos_orden_pago_cuerpo   = "";

     $cuenta = $_SESSION['CUENTA_ORDENES_PAGO'];

if(isset($this->data['cstp09_notadebito_por_cancelar']['beneficiario'])){

for($i=1; $i<=($cuenta-1); $i++){
 if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no" && ($this->data['cstp09_notadebito_por_cancelar']['beneficiario']!=$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['autorizado'])){
   $verifica = "no";
 }//fin if
}//fin for

$tipo_documento = $this->data['cstp09_notadebito_por_cancelar']['tipo_documento'];
$tipo_pago      = $this->data['cstp09_notadebito_por_cancelar']['tipo_pago'];

for($i=1; $i<=($cuenta-1); $i++){
  if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no" && $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['tipo_documento']!=$tipo_documento){
     $verifica = "tipo_documento";
  }//fin if
}//fin for


for($i=1; $i<=($cuenta-1); $i++){
  if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no" &&  $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['tipo_pago']!=$tipo_pago){
     $verifica = "tipo_pago";
  }//fin if
}//fin for



	if($verifica=="si"){
		   echo'<script>'; echo"document.getElementById('enviar_orden').disabled = true;"; echo'</script>';

	}else{
		   echo'<script>'; echo"document.getElementById('enviar_orden').disabled = false;"; echo'</script>';

		   $datos_orden_pago_partidas = $this->cepd03_ordenpago_partidas->findAll($condicion. ' and  ano_orden_pago='.$ano.'  and  numero_orden_pago='.$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['numero_orden_pago'], null, 'ano_orden_pago, numero_orden_pago, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar DESC');
		   $datos_orden_pago_cuerpo   = $this->cepd03_ordenpago_cuerpo->findAll($condicion. ' and  ano_orden_pago='.$ano.'  and  numero_orden_pago='.$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['numero_orden_pago']);

	     }//else

}else{
    echo'<script>';  echo"document.getElementById('enviar_orden').disabled = true;"; echo'</script>';
}//else



}//fin else








$this->set('datos_orden_pago_cuerpo', $datos_orden_pago_cuerpo);
$this->set('datos_orden_pago_partidas', $datos_orden_pago_partidas);




}//fin funtion









function agregar_orden_pago_session($var1=null){


  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  $ano = $this->ano_ejecucion();
  $opcion = "si";
  $verifica = "si";
  $cuenta_aux = 0;
  $cuenta = $_SESSION['CUENTA_ORDENES_PAGO'];

  $pregunta_ejercicio2 = $this->data['datos']['pregunta_ejercicio2'];

$mes_cierre = $this->cstd09_notadebito_cuerpo_pago->execute("SELECT mes_cierre_mensual FROM ccfd04_cierre_mes WHERE cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_cierre_mensual='".$ano."'");

// $guardar_nota=true;

// for($i=1; $i<=($cuenta-1); $i++){

$fecha_orden_pago_aux =   explode('-',$this->data['cstp09_notadebito_por_cancelar']['fecha_op']);
	
if($fecha_orden_pago_aux[1]<=$mes_cierre[0][0]["mes_cierre_mensual"]){
	$verifica = "mes_cerrado";
}

// }

if($verifica=="si"){
	for($i=1; $i<=($cuenta-1); $i++){
	 if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no"){
	    $cuenta_aux++;
	 }//fin if
	}//fin for





 	if($cuenta_aux==0){
 	   $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['autorizado']=$this->data['cstp09_notadebito_por_cancelar']['beneficiario'];

		$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago'] = str_replace("\n",'',$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago']);
		$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago'] = str_replace('"','',$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago']);
		$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago'] = str_replace("'",'',$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago']);
		$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago'] = str_replace("<",'',$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago']);
		$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago'] = str_replace(">",'',$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago']);
		$opcion_auxx = $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['concepto_orden_pago'];

		$opcion_auxx = javascript_encode($opcion_auxx, 1);

		echo'<script>';
		echo"document.getElementById('concepto').value = \"$opcion_auxx\" ; ";
		echo'</script>';

	}else{
		echo'<script>';echo"document.getElementById('concepto').value = ''  ;";echo'</script>';
	}//fin else





	$tipo_documento = $this->data['cstp09_notadebito_por_cancelar']['tipo_documento'];
	$tipo_pago      = $this->data['cstp09_notadebito_por_cancelar']['tipo_pago'];
	$tipo_pago      = $this->data['cstp09_notadebito_por_cancelar']['tipo_pago'];
	//print_r($this->data['cstp09_notadebito_por_cancelar']);


	for($i=1; $i<=($cuenta-1); $i++){
	  if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no" && $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['tipo_documento']!=$tipo_documento){
	     $verifica = "tipo_documento";
	  }//fin if
	}//fin for


	for($i=1; $i<=($cuenta-1); $i++){
	  if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no" && $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['tipo_pago']!=$tipo_pago){
	     $verifica = "tipo_pago";
	  }//fin if
	}//fin for


	for($i=1; $i<=($cuenta-1); $i++){
		$a_a = str_replace(" ",'',$this->data['cstp09_notadebito_por_cancelar']['beneficiario']);
	    $b_b = str_replace(" ",'',$_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['autorizado']);
	 if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no" && (strtoupper($a_a)!=strtoupper($b_b))){
	   $verifica = "no";
	 }//fin if
	}//fin for


	for($i=1; $i<=($cuenta-1); $i++){
	  if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['ejercicio_anterior']!=$pregunta_ejercicio2){
	     $verifica = "ejercicio";
	  }//fin if
	}//fin for



	if($verifica=="si"){


		for($i=1; $i<=($cuenta-1); $i++){
		  $ano_orden_pago                      =         $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['ano_orden_pago'];
		  $numero_orden_pago                   =         $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['numero_orden_pago'];
			if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no" && ($this->data['cstp09_notadebito_por_cancelar']['ano']==$ano_orden_pago && $this->data['cstp09_notadebito_por_cancelar']['num_orden']==$numero_orden_pago)){
		  	$opcion = "no";
		  }//fin if
		}//fin for

		if($opcion=="si"){

	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['autorizado']         = $this->data['cstp09_notadebito_por_cancelar']['beneficiario'];
	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['fecha_orden_pago']   = $this->data['cstp09_notadebito_por_cancelar']['fecha_op'];
	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['ejercicio_anterior'] = $pregunta_ejercicio2;
	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['tipo_documento']     = $tipo_documento;
	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['tipo_pago']          = $tipo_pago;

	       if(!isset($_SESSION['CUENTA_ORDENES_PAGO'])){$_SESSION['CUENTA_ORDENES_PAGO']=1;}else{$_SESSION['CUENTA_ORDENES_PAGO'] = $_SESSION['CUENTA_ORDENES_PAGO'] + 1;}
	       $_SESSION['ORDEN_PAGO_TOTAL']['rif'] = $this->data['cstp09_notadebito_por_cancelar']['rif_input'];
	       //$this->set('Message_existe', 'Orden de pago agregada a la lista');
		}else{$this->set('errorMessage', 'La orden de pago ya esta agregada');}//fin else

	}else if($verifica=="tipo_documento"){

	  $this->set('errorMessage', 'Las ordenes deben de ser del mismo tipo de documento');

	}else if($verifica=="tipo_pago"){

	  $this->set('errorMessage', 'Las ordenes deben de ser del mismo tipo de pago');

	}else if($verifica=="ejercicio"){

	  $this->set('errorMessage', 'exite orden de un ejercicio deferente');

	}else{$this->set('errorMessage', 'El autorizado a cobrar no es igual al primero');}//fin else

}else{$this->set('errorMessage', 'La orden de pago es de un mes cerrado presupuestariamente');}//fin else



}//fin function















function eliminar_session($var1=null){

	$this->layout="ajax";

     $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$var1]['DATOS_ORDEN_PAGO']['usar'] = "no";
     $this->set('errorMessage', 'La Orden de pago fue eliminada de la lista');


$cuenta = $_SESSION['CUENTA_ORDENES_PAGO'];
$cuenta_aux = 0;



for($i=1; $i<=($cuenta-1); $i++){
 if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no"){
    $cuenta_aux++;
 }//fin if
}//fin for





 if($cuenta_aux==0){
     echo'<script>';echo"document.getElementById('concepto').value = ''  ;";echo'</script>';
 }//fin if

}//fin function










function salir(){


  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();

$numero_debito = "";

$cod_entidad_bancaria   =   $this->Session->read('cod_entidad_bancaria_aux');
$cod_sucursal           =   $this->Session->read('cod_sucursal_aux');
$cuenta_bancaria        =   $this->Session->read('cuenta_bancaria');
$numero_debito          =   $this->Session->read('numero_debito');

$this->Session->delete('cod_entidad_bancaria_aux');
$this->Session->delete('cod_sucursal_aux');
$this->Session->delete('cuenta_bancaria');
$this->Session->delete('numero_debito');

//if($numero_debito!=""){$this->cstd03_cheque_numero->execute("UPDATE cstd03_cheque_numero SET  situacion='1'  WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and numero_debito='".$numero_debito."' ");}

echo"<script>menu_activo();</script>";

}//fin salir












function ver_campo_session($var1=null){

	$this->layout="ajax";

  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  $ano = $this->ano_ejecucion();



   $ano = $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$var1]['DATOS_ORDEN_PAGO']['ano_orden_pago'];
   $var = $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$var1]['DATOS_ORDEN_PAGO']['numero_orden_pago'];


  $datos_orden_pago_partidas = $this->cepd03_ordenpago_partidas->findAll($condicion. ' and  ano_orden_pago='.$ano.'  and  numero_orden_pago='.$var, null, "ano_orden_pago, numero_orden_pago, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar DESC");
  $datos_orden_pago_cuerpo   = $this->cepd03_ordenpago_cuerpo->findAll($condicion. '   and  ano_orden_pago='.$ano.'  and  numero_orden_pago='.$var);


  $this->set('datos_orden_pago_cuerpo', $datos_orden_pago_cuerpo);
  $this->set('datos_orden_pago_partidas', $datos_orden_pago_partidas);
  $this->set('id', $var1);




}//fin function








}//fin clas



?>
