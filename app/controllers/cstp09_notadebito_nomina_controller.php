<?php

class Cstp09NotadebitoNominaController extends AppController{



    var $name = "cstp09_notadebito_nomina";
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

		$cond.=" and ano_orden_pago=".$ano;

		$autorizados = $this->cepd03_ordenpago_cuerpo->findAll($cond.' and tipo_orden=2  and condicion_actividad=1 and numero_cheque=0 and cod_tipo_pago = 4 group by autorizado', "distinct(autorizado)", 'autorizado ASC');

		 $aut = array();
			foreach ($autorizados as $key => $value) {
				$value_aux = str_replace("/", "--", $value[0]['autorizado']);
				$value_aux = urlencode($value_aux);
				//$value_aux = str_replace("%", "#", $value_aux);
				$aut[$value_aux] = $value[0]['autorizado'];
			}
			$this->set("grupo",$aut);


		            echo'<script>';
					  echo"document.getElementById('concepto').value = ''; ";
					echo'</script>';


	}//fin index


	function portal_patria(){
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

		$cond.=" and ano_orden_pago=".$ano;

		$autorizados = $this->cepd03_ordenpago_cuerpo->findAll($cond.' and tipo_orden=2  and condicion_actividad=1 and numero_cheque=0 and cod_tipo_pago = 4 group by autorizado', "distinct(autorizado)", 'autorizado ASC');

		 $aut = array();
			foreach ($autorizados as $key => $value) {
				$value_aux = str_replace("/", "--", $value[0]['autorizado']);
				$value_aux = urlencode($value_aux);
				//$value_aux = str_replace("%", "#", $value_aux);
				$aut[$value_aux] = $value[0]['autorizado'];
			}
			$this->set("grupo",$aut);


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
				$cod_2 =  $this->Session->read('cod2');
				$cod_1 =  $this->Session->read('cod1');
				$cond  =  $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2;
				$lista = "";
				/*$lista = array();
				if($var2!=""){
				   $sql =  $this->cstd02_cuentas_bancarias->execute("
						SELECT cuenta_bancaria
						  FROM 
						  	cnmd09_bancos_cancelan_nominas 
						  where 
						  	".$cond."
						  group by
						  	cuenta_bancaria
				   	");

				   foreach ($sql as $value) {
				   		$lista[$value[0]['cuenta_bancaria']] = $value[0]['cuenta_bancaria']." - NÓMINAS";
				   }
				}//fin if
				if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}*/

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

function select_consulta($select=null,$var=null, $var2=null) { //select codigos presupuestarios
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
			case 'year':
			$this->set('SELECT','year');
			$this->set('codigo','year');
			$this->set('seleccion',$this->ano_ejecucion());
			$this->set('n',3);
			$this->set('no','no');
			$this->set('otro','otro');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			if($var!=null && $var=='consulta'){$this->set('year','year');}
			$this->Session->write('cod2',$var2);
			$cod_1 =  $this->Session->read('cod1');
			$cond  =  $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$var2;
			$lista = "";

			if($var2!=""){
						$rs=$this->cstd09_notadebito_cuerpo_pago->execute("SELECT DISTINCT ano_movimiento FROM cstd09_notadebito_cuerpo WHERE ". $cond);
					    foreach($rs as $l){
							$lista[$l[0]["ano_movimiento"]]=$l[0]["ano_movimiento"];
						}
			}//fin if
			$year2 = $this->ano_ejecucion();
            if($lista==""){$lista = array(); $this->set('vector',$lista);}else{$this->set('vector',$lista);}
            $this->set('year_ejecucion',$year2);
            echo "<script type='text/javascript'>ver_documento('/cstp09_notadebito_nomina/select_consulta/cuenta/consulta/".$year2."','st_cuenta');</script>";
		break;
			case 'cuenta':
				$this->set('SELECT','cuenta');
				$this->set('codigo','cuenta');
				$this->set('seleccion','');
				$this->set('n',4);
				$this->set('no','no');
				$this->set('otro','otro');
				if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
				if($var!=null && $var=='consulta'){$this->set('cuenta','cuenta');}
				$cod_2 =  $this->Session->read('cod2');
				$cod_1 =  $this->Session->read('cod1');
				$this->Session->write('cod4',$var2);
				$cond  =  $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2;
				$lista = "";
				/*$lista = array();
				if($var2!=""){
				   $sql =  $this->cstd02_cuentas_bancarias->execute("
						SELECT cuenta_bancaria
						  FROM 
						  	cnmd09_bancos_cancelan_nominas 
						  where 
						  	".$cond."
						  group by
						  	cuenta_bancaria
				   	");

				   foreach ($sql as $value) {
				   		$lista[$value[0]['cuenta_bancaria']] = $value[0]['cuenta_bancaria']." - NÓMINAS";
				   }
				}//fin if
				if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}*/
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
           $lista=  $this->cstd09_notadebito_cuerpo_pago->generateList($cond2." and ano_movimiento=".$ano, 'numero_debito ASC', null, '{n}.cstd09_notadebito_cuerpo_pago.numero_debito', '{n}.cstd09_notadebito_cuerpo_pago.beneficiario');


	}else{$lista="";}//fin else

$this->concatena( $lista, 'lista');

}//fin function





function mostrar($opcion,$var,$codigo=null) {
	$this->layout="ajax";
	if(isset($codigo) && $codigo!=''){
	switch($opcion){
		case 'entidades':
			if(isset($var) && $var=="codigo"){
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
			 echo"document.getElementById('genn_numero_debito').disabled = true; ";
			echo'</script>';


	break;
		case 'sucursales':
			if(isset($var) && $var=="codigo"){
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

	/*if ($cod_presi==1 && $cod_entidad==12 && $cod_tipo_inst==30 && $cod_inst==12 && $cod_dep==1){//GOBERNACIÓN DE GUÁRICO
	    $max_numero_deb = $this->cstd09_notadebito_cuerpo_pago->execute("SELECT MAX(numero_debito) AS numero_debito FROM cstd09_notadebito_cuerpo WHERE cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$year2."' and cod_entidad_bancaria='".$cod_1."' and cod_sucursal='".$cod_2."' and cuenta_bancaria='".$var."';");
		if($max_numero_deb[0][0]['numero_debito'] != ""){
			$numero = $max_numero_deb[0][0]['numero_debito'] + 1;
		}else{
			
			$numero = "";
		}
	}else{
		$numero = "";
	}*/

	$max_numero_deb = $this->cstd09_notadebito_cuerpo_pago->execute("SELECT MAX(numero_debito) AS numero_debito FROM cstd09_notadebito_cuerpo WHERE cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$year2."' and cod_entidad_bancaria='".$cod_1."' and cod_sucursal='".$cod_2."' and cuenta_bancaria='".$var."';");
	
	if($max_numero_deb[0][0]['numero_debito'] != ""){
		$numero = $max_numero_deb[0][0]['numero_debito'] + 1;
		/*if($cod_dep==1){
			$numero="";
		}*/
	}else{
		
		$numero = "100000000";
		if($cod_dep==1){
			$numero="";
		}
	}

	//PARA INSTITUCIONES DONDE SE NECESITE COLOCAR EL NUMERO DE DEBITO MANUAL 
	if($cod_dep==1 || $cod_dep==1020 || $cod_dep==1019 || $cod_dep==1005 || $cod_dep==1004 || $cod_dep==1001 || $cod_dep==1011 || $cod_dep==1015 || $cod_dep==1023 || $cod_dep==1007 || $cod_dep==1022 || $cod_dep==1027 || $cod_dep==1035 || $cod_dep==1038 || $cod_dep==1039 || $cod_dep==1040 || $cod_dep==1041 ){
		//esta dentro de una condicion de cod_dep para que se desbloquee solo a esa dependencia y las demas puedan seguir 
		// trabajando sin problemas
		$numero='';
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

  }else{
  	$numero="";
    echo'<script>';
      echo"document.getElementById('numero_debito').value = '';  ";
      echo"document.getElementById('numero_debito').readOnly = false;  ";
    echo'</script>';
  }//fin else

  $this->set("numero",$numero);

}//fin function






function disponibilidad($var=null){
	$this->layout="ajax";
	$disponible = "";
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

	if($pista != null && $pista != ""){

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
		$resultado=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and numero_orden_pago='".$var."' ");

		$resul=$resultado[0]["cepd03_ordenpago_cuerpo"]["autorizado"];
		//echo $resul;
		echo'<script>';
			  echo"document.getElementById('bene').value = \"$resul\" ; ";
	     echo'</script>';
		$this->set('beneficiario',$resul);

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
			echo'</script>';
	}

}//fin mostrar_datos






function guardar()
{

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;

  $year2 = $this->ano_ejecucion();

  $opcion = 'si';
  $cuenta = $_SESSION['BENEFICIARIO_ORDENES'];

  $numero_comprobante_municipal       =     0;
  $numero_comprobante_timbre          =     0;
  $numero_comprobante_islr            =     0;
  $numero_comprobante_iva             =     0;
  $numero_comprobante_egreso          =     0;
  $numero_comprobante_multa           =     0;
  $numero_comprobante_responsabilidad =     0;



  //    cstd09_notadebito_cuerpo_pago


  $ano_movimiento                         =         $this->data['cstp09_notadebito_nomina']['ano_movimiento'];
  $ann = $ano_movimiento;
  $cod_entidad_bancaria                   =         $this->data['cstp09_notadebito_nomina']['entidad'];
  $cod_sucursal                           =         $this->data['cstp09_notadebito_nomina']['sucursal'];
  $cuenta_bancaria                        =         $this->data['cstp09_notadebito_nomina']['cuenta'];
  $numero_debito                          =         $this->data['cstp09_notadebito_nomina']['numero_debito'];
  $fecha_debito                           =         $this->Cfecha($this->data['cstp09_notadebito_nomina']['fecha'], 'A-M-D');
  $fd                                     =         $this->data['cstp09_notadebito_nomina']['fecha'];
  $concepto                               =         $this->data['cstp09_notadebito_nomina']['concepto'];


  $status_debito                          =         3;
  $clase_beneficiario                     =         1;

  $rif_cedula                             =         $this->data['cstp09_notadebito_nomina']['rif_input'];
  $cod_tipo_pago                          =         $this->data['cstp09_notadebito_nomina']['tipo_pago'];
  $monto                                  =         $this->formato1($this->data['cstp09_notadebito_nomina']['monto']);
  $beneficiario                           =         urldecode($this->data['cstp09_notadebito_nomina']['beneficiario']);

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

  //MENSAJE
  $error1 = "";
  $error2 = 0;
  $error3 = "";
  $error4 = 0;


  if (!empty($ano_movimiento) && !empty($cod_entidad_bancaria) && !empty($cod_sucursal) && !empty($cuenta_bancaria) && !empty($numero_debito) && !empty($fecha_debito) && !empty($concepto) && !empty($rif_cedula) && !empty($cod_tipo_pago) && !empty($monto) && !empty($beneficiario)) {

    if ($this->cstd09_notadebito_cuerpo_pago->findCount("cod_presi='" . $cod_presi . "'  and cod_entidad='" . $cod_entidad . "' and cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and  cod_dep='" . $cod_dep . "' and ano_movimiento='" . $ano_movimiento . "'  and  cod_entidad_bancaria='" . $cod_entidad_bancaria . "' and cod_sucursal='" . $cod_sucursal . "' and cuenta_bancaria='" . $cuenta_bancaria . "' and numero_debito='" . $numero_debito . "' ") == 0) {

      $datos_cstd06_comprobante_numero_egreso = $this->cstd06_comprobante_numero_egreso->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "'  and cod_dep='" . $cod_dep . "'  and ano_comprobante_egreso='" . $ano_movimiento . "'");

      foreach ($datos_cstd06_comprobante_numero_egreso as $aux) {
        $numero_comprobante_egreso = $aux['cstd06_comprobante_numero_egreso']['numero_comprobante_egreso'];
      }
      $numero_comprobante_egreso++;

      $resul_dc = $this->cstd02_cuentas_bancarias->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and cod_entidad_bancaria = '" . $cod_entidad_bancaria . "' and   cod_sucursal='" . $cod_sucursal . "' and  cuenta_bancaria='" . $cuenta_bancaria . "' ", null, null, null);
      $disponibilidad_cuenta=0;
          foreach ($resul_dc as $resul_aux) {
            $disponibilidad_cuenta    =    $resul_aux['cstd02_cuentas_bancarias']['disponibilidad_libro'];
          } //fin foreach

      if($disponibilidad_cuenta-$monto>=0){

	      if ($beneficiario != "") {
	        if ($this->cstd06_comprobante_cuerpo_egreso->findCount("cod_presi='" . $cod_presi . "'  and cod_entidad='" . $cod_entidad . "' and cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and  cod_dep='" . $cod_dep . "' and ano_comprobante_egreso='" . $ano_movimiento . "'  and  numero_comprobante_egreso='" . $numero_comprobante_egreso . "'  ") == 0) {

	          //	$this->cstd06_comprobante_numero_egreso->execute(" BEGIN; ");


	          // cstd06_comprobante_numero_egreso


	          $ano_comprobante_egreso           =     $ano_movimiento;
	          $numero_comprobante_egreso        =     0;

	          if ($this->cstd06_comprobante_numero_egreso->findCount("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "'  and ano_comprobante_egreso='" . $ano_comprobante_egreso . "'") > 0) {

	            $datos_cstd06_comprobante_numero_egreso = $this->cstd06_comprobante_numero_egreso->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "'  and cod_dep='" . $cod_dep . "'  and ano_comprobante_egreso='" . $ano_comprobante_egreso . "'");

	            foreach ($datos_cstd06_comprobante_numero_egreso as $aux) {
	              $numero_comprobante_egreso = $aux['cstd06_comprobante_numero_egreso']['numero_comprobante_egreso'];
	            }

	            $numero_comprobante_egreso++;

	            if ($this->cstd06_comprobante_numero_egreso->execute("UPDATE cstd06_comprobante_numero_egreso SET numero_comprobante_egreso=" . $numero_comprobante_egreso . " WHERE cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "'  and cod_dep='" . $cod_dep . "'   and ano_comprobante_egreso='" . $ano_comprobante_egreso . "'; ") >= 1) {
	            } else {
	              $opcion = 'no';
	            } //fin else
	          } else {
	            $numero_comprobante_egreso++;
	            $sql_cstd06_comprobante_poremitir_egreso2 = "INSERT INTO cstd06_comprobante_numero_egreso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_egreso, numero_comprobante_egreso)";
	            $sql_cstd06_comprobante_poremitir_egreso2 .= "VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "',  '" . $cod_dep . "',  '" . $ano_comprobante_egreso . "', '" . $numero_comprobante_egreso . "'); ";
	            if ($this->cstd06_comprobante_numero_egreso->execute($sql_cstd06_comprobante_poremitir_egreso2) >= 1) {
	            } else {
	              $opcion = 'no';
	            } //fin else

	          } //fin esel


	          // cstd06_comprobante_cuerpo_egreso

	          $sql_cstd06_comprobante_cuerpo_egreso = "INSERT INTO cstd06_comprobante_cuerpo_egreso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_egreso, numero_comprobante_egreso, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque)";
	          $sql_cstd06_comprobante_cuerpo_egreso .= "VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $ano_comprobante_egreso . "', '" . $numero_comprobante_egreso . "', '" . $ano_movimiento . "', '" . $cod_entidad_bancaria . "', '" . $cod_sucursal . "', '" . $cuenta_bancaria . "', '" . $numero_debito . "'); ";

	          if ($this->cstd06_comprobante_cuerpo_egreso->execute($sql_cstd06_comprobante_cuerpo_egreso) >= 1) {
	          } else {
	            $opcion = 'no';
	          } //fin else


	          //  cstd09_notadebito_cuerpo_pago

	          $monto = $monto_para_actualizar_en_cuenta;


	          $sql_cstd09_notadebito_cuerpo_pago = "INSERT INTO cstd09_notadebito_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito, fecha_debito, beneficiario, monto, concepto, rif_cedula, cod_tipo_pago, status_debito, clase_beneficiario, fecha_proceso_registro, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, username_registro, condicion_actividad, ano_anulacion, numero_anulacion, fecha_proceso_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, ano_asiento_anulacion, numero_asiento_anulacion, username_anulacion, numero_comprobante_egreso, ano_anterior) ";
	          $sql_cstd09_notadebito_cuerpo_pago .= "VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $ano_movimiento . "', '" . $cod_entidad_bancaria . "', '" . $cod_sucursal . "', '" . $cuenta_bancaria . "', '" . $numero_debito . "', '" . $fecha_debito . "', '" . $beneficiario . "', '" . $monto . "', '" . $concepto . "', '" . $rif_cedula . "', '" . $cod_tipo_pago . "', '" . $status_debito . "', '" . $clase_beneficiario . "', '" . $fecha_proceso_registro . "', '" . $dia_asiento_registro . "', '" . $mes_asiento_registro . "', '" . $ano_asiento_registro . "', '" . $numero_asiento_registro . "', '" . $username_registro . "', '" . $condicion_actividad . "', '" . $ano_anulacion . "', '" . $numero_anulacion . "', '" . $fecha_proceso_anulacion . "', '" . $dia_asiento_anulacion . "', '" . $mes_asiento_anulacion . "', '" . $ano_asiento_anulacion . "', '" . $numero_asiento_anulacion . "', '" . $username_anulacion . "', '" . $numero_comprobante_egreso . "', '" . $ejercicio_anterior . "');";


	          if ($this->cstd09_notadebito_cuerpo_pago->execute($sql_cstd09_notadebito_cuerpo_pago) >= 1) {
	          } else {
	            $opcion = 'no';
	          }


	          // cstd09_notadebito_poremitir

	          $sql_cstd09_notadebito_poremitir = "INSERT INTO cstd09_notadebito_poremitir ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito) ";
	          $sql_cstd09_notadebito_poremitir .= "VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $username . "', '" . $ano_movimiento . "', '" . $cod_entidad_bancaria . "', '" . $cod_sucursal . "', '" . $cuenta_bancaria . "', '" . $numero_debito . "');";

	          if ($this->cstd09_notadebito_poremitir->execute($sql_cstd09_notadebito_poremitir) >= 1) {
	          } else {
	            $opcion = 'no';
	          }

	          $contador_contabilidad = 0;
	          $monto_retenciones["monto_neto_orden"]        = 0;
	          $monto_retenciones["monto_total_retenciones"] = 0;




	          foreach ($cuenta as $value) {

	            $ano              =   $value[0]['ano_orden_pago'];
	            $var              =   $value[0]['numero_orden_pago'];
	            $var1              =   $value[0]['numero_orden_pago_secuencia'];
	            $fecha_orden_pago =   $value[0]['fecha_orden_pago'];
	            $clase_orden      =   $value[0]['tipo_orden'];


	            $datos_orden_pago_partidas = $this->cepd03_ordenpago_partidas->findAll($condicion . " and  ano_orden_pago=" . $ano . "  and  numero_orden_pago=" . $var . " and numero_orden_pago_secuencia = '" . $var1 . "'", null, 'ano_orden_pago, numero_orden_pago, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar DESC');
	            $datos_orden_pago_cuerpo   = $this->cepd03_ordenpago_cuerpo->findAll($condicion . " and  ano_orden_pago=" . $ano . "  and  numero_orden_pago=" . $var . " and numero_orden_pago_secuencia = '" . $var1 . "'");


	            foreach ($datos_orden_pago_cuerpo as $contabilidad_ve_contabilidad) {

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

	              if ($contabilidad_retencion_multa == "") {
	                $contabilidad_retencion_multa = 0;
	              }
	              if ($contabilidad_retencion_responsabilidad == "") {
	                $contabilidad_retencion_responsabilidad = 0;
	              }

	              $datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicion() . " and ano_documento='" . $contabilidad_ano_documento_origen . "' and numero_documento='" . $contabilidad_numero_documento_origen . "'   ");

	              $contabilidad_f_dc_adj_array_pago_aux = null;
	              $contabilidad_f_dc_array_pago_aux     = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"];


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

	              $n_ops_array_pago_aux[$contador_contabilidad]    = $contabilidad_numero_orden_pago_secuencia;

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
	            } //fin foreach


	            $status                              =         $status_debito;
	            $status_retencion                    =         1;

	            //   cstd09_notadebito_ordenes

	            $sql_cstd09_notadebito_ordenes = "INSERT INTO cstd09_notadebito_ordenes(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, clase_orden, ano_orden_pago, numero_orden_pago, numero_orden_pago_secuencia, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito) ";
	            $sql_cstd09_notadebito_ordenes .= "VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $clase_orden . "', '" . $ano . "', '" . $var . "', '" . $var1 . "', '" . $ano_movimiento . "', '" . $cod_entidad_bancaria . "', '" . $cod_sucursal . "', '" . $cuenta_bancaria . "', '" . $numero_debito . "'); ";

	            if ($this->cstd09_notadebito_ordenes->execute($sql_cstd09_notadebito_ordenes) >= 1) {
	            } else {
	              $opcion = 'no';
	            }
	            // cstd06_comprobante_poremitir_egreso


	            $sql_cstd06_comprobante_poremitir_egreso = "INSERT INTO cstd06_comprobante_poremitir_egreso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_egreso, numero_comprobante_egreso, ano_orden_pago, clase_orden, numero_orden_pago, numero_orden_pago_secuencia, tipo_pago)";
	            $sql_cstd06_comprobante_poremitir_egreso .= "VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $username . "', '" . $ano_comprobante_egreso . "', '" . $numero_comprobante_egreso . "', '" . $ano . "', '" . $clase_orden . "', '" . $var . "', '" . $var1 . "','1'); ";

	            if ($this->cstd06_comprobante_poremitir_egreso->execute($sql_cstd06_comprobante_poremitir_egreso) >= 1) {
	            } else {
	              $opcion = 'no';
	            } //fin else

	            $j = 0;

	            $numero_pagado = $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', $conditions = $this->condicionNDEP() . " and ano_pagado='$ann'", $order = null);
	            if (!empty($numero_pagado)) {
	              $numero_pagado++;
	              $sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='$ann' and " . $this->condicionNDEP() . ";";
	            } else {
	              $numero_pagado = 1;
	              /*$sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_pagado');";*/
	            }
	            $sw_numero_pagado = $this->cfpd23_numero_asiento_pagado->query($sql_numero_pagado);


	            //////////////////////////////////////////////////---------------PARTIDAS-----------------///////////////////////////

	            $x = 0;

	            if (isset($datos_orden_pago_partidas)) {
	              if ($datos_orden_pago_partidas != null) {
	                foreach ($datos_orden_pago_partidas as $ve) {

	                  //cstd09_notadebito_partidas

	                  $concate  = $this->AddCeroR2(substr($ve['cepd03_ordenpago_partidas']['cod_partida'], -2), substr($ve['cepd03_ordenpago_partidas']['cod_partida'], 0, 1)) . '.' . $this->AddCeroR2($ve['cepd03_ordenpago_partidas']['cod_generica']) . '.' . $this->AddCeroR2($ve['cepd03_ordenpago_partidas']['cod_especifica']) . '.' . $this->AddCeroR2($ve['cepd03_ordenpago_partidas']['cod_sub_espec']);
	                  $concate2 = $this->AddCeroR2(substr($ve['cepd03_ordenpago_partidas']['cod_partida'], -2), substr($ve['cepd03_ordenpago_partidas']['cod_partida'], 0, 1));

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
	                  $numero_control_compromiso           =         $ve['cepd03_ordenpago_partidas']['numero_control_compromiso'];
	                  $numero_control_causado              =         $ve['cepd03_ordenpago_partidas']['numero_control_causado'];
	                  $monto                               =         $ve['cepd03_ordenpago_partidas']['monto'];
	                  $numero_control_pagado               =         $numero_pagado;

	                  $sql_verificar = "  and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog . " and cod_proyecto=" . $cod_proyecto . " and cod_activ_obra=" . $cod_activ_obra;
	                  $sql_verificar .= " and cod_partida=" . $cod_partida . " and cod_generica=" . $cod_generica . " and cod_especifica=" . $cod_especifica . " and cod_sub_espec=" . $cod_sub_espec . " and cod_auxiliar=" . $cod_auxiliar . "";


	                  $c                        = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
	                  $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

	                  $cp       = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	                  $to       = 1;
	                  $td       = 5;
	                  $ta       = 5;
	                  $mt       = $monto;
	                  $ccp      = $concepto;
	                  $opago    = $var;
	                  $opfecha  = $fecha_orden_pago;
	                  $cbanco   = $cod_entidad_bancaria_aux;
	                  $ccuenta  = $cuenta_bancaria;
	                  $ccheque  = $numero_debito;
	                  $fechache = $fd;
	                  $rnco     = $numero_control_compromiso;
	                  $rnca     = $numero_control_causado;
	                  $rnpa     = $numero_control_pagado;

	                  if ($ejercicio_anterior == 1) {
	                    $dnco = 0;
	                  } else {
	                    $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ann, null, null, $opago, $opfecha, $cbanco, $ccuenta, $ccheque, $fechache, $rnco, $rnca, $rnpa, null, $x);
	                  } //fin else

	                  //  cstd09_notadebito_partidas


	                  $sql_cstd09_notadebito_partidas = "INSERT INTO cstd09_notadebito_partidas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito, clase_orden, ano_orden_pago, numero_orden_pago, numero_orden_pago_secuencia, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, numero_control_compromiso, numero_control_causado, numero_control_pagado) ";
	                  $sql_cstd09_notadebito_partidas .= "VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $ano_movimiento . "', '" . $cod_entidad_bancaria . "', '" . $cod_sucursal . "', '" . $cuenta_bancaria . "', '" . $numero_debito . "', '" . $clase_orden . "', '" . $ano . "', '" . $var . "', '" . $var1 . "', '" . $ano . "', '" . $cod_sector . "', '" . $cod_programa . "', '" . $cod_sub_prog . "', '" . $cod_proyecto . "', '" . $cod_activ_obra . "', '" . $cod_partida . "', '" . $cod_generica . "', '" . $cod_especifica . "', '" . $cod_sub_espec . "', '" . $cod_auxiliar . "', '" . $monto . "', '" . $rnco . "', '" . $rnca . "', '" . $rnpa . "'); ";
	                  $x++;

	                  if ($monto != 0) {
	                    if ($this->cstd09_notadebito_partidas_pago->execute($sql_cstd09_notadebito_partidas) >= 1) {
	                    } else {
	                      $opcion = 'no';
	                    } //fin else
	                  }
	                  $j++;
	                } //fin foreach
	              } //fin if
	            } //fin if



	            /////////////////////////////////////////////////////////-------FIN----------////////////////////////////////////////

	            if ($this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_movimiento=" . $ano_movimiento . ", numero_comprobante_islr=" . $numero_comprobante_islr . ", numero_comprobante_timbre=" . $numero_comprobante_timbre . ", numero_comprobante_egreso=" . $numero_comprobante_egreso . ", numero_comprobante_municipal=" . $numero_comprobante_municipal . ", numero_comprobante_iva=" . $numero_comprobante_iva . ",  numero_comprobante_multa=" . $numero_comprobante_multa . ",  numero_comprobante_responsabilidad=" . $numero_comprobante_responsabilidad . ", cod_entidad_bancaria = '" . $cod_entidad_bancaria . "',  cod_sucursal='" . $cod_sucursal . "', cuenta_bancaria='" . $cuenta_bancaria . "', numero_cheque=" . $numero_debito . ", fecha_cheque='" . $fecha_debito . "', documento_pago='1'
								 WHERE cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "'  and ano_orden_pago='" . $ano . "' and numero_orden_pago='" . $var . "' and numero_orden_pago_secuencia='" . $var1 . "'; ") >= 1) {
	            } else {
	              $opcion = 'no';
	            } //fin else

	            // cstd04_movimientos_generales

	            $mes                   =  $fecha_debito[5] . $fecha_debito[6];
	            $dia                   =  $fecha_debito[8] . $fecha_debito[9];
	            $tipo_documento        =  "3";
	          } //fin for I



	          $sql_cstd04_movimientos_generales = "INSERT INTO cstd04_movimientos_generales( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, mes, dia, tipo_documento, numero_documento, monto) ";
	          $sql_cstd04_movimientos_generales .= "VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $ano_movimiento . "', '" . $cod_entidad_bancaria . "', '" . $cod_sucursal . "', '" . $cuenta_bancaria . "', '" . $mes . "', '" . $dia . "', '" . $tipo_documento . "', '" . $numero_debito . "', '" . $monto_para_actualizar_en_cuenta . "');";


	          if ($this->cstd04_movimientos_generales->findCount("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and ano_movimiento='" . $ano_movimiento . "' and cod_entidad_bancaria = '" . $cod_entidad_bancaria . "' and   cod_sucursal='" . $cod_sucursal . "' and  cuenta_bancaria='" . $cuenta_bancaria . "' and mes = '" . $mes . "' and  dia = '" . $dia . "' and  tipo_documento = '" . $tipo_documento . "' and  numero_documento = '" . $numero_debito . "' ") != 0) {
	          } else {
	            if ($this->cstd04_movimientos_generales->execute($sql_cstd04_movimientos_generales) >= 1) {
	            } else {
	              $opcion = 'no';
	            }
	          } //fin

	          $mensaje = 0;

	          $sql_manuales = "INSERT INTO cstd03_movimientos_manuales VALUES(" . $cod_presi . ", " . $cod_entidad . ", " . $cod_tipo_inst . ", " . $cod_inst . ", " . $cod_dep . "," . $ano_movimiento . "," . $cod_entidad_bancaria . "," . $cod_sucursal . ",'" . $cuenta_bancaria . "'," . $tipo_documento . "," . $numero_debito . ",'" . $fecha_debito . "','" . $beneficiario . "'," . $monto_para_actualizar_en_cuenta . ",'" . $concepto . "','" . $fecha_proceso_registro . "',0,0,0,0,'" . $username_registro . "',1,0,0,'1900/01/01',0,0,0,0,'0',0,0)";

	          //MENSAJE 
	          $error3 = $opcion;
	          if ($this->cstd03_movimientos_manuales->findCount("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and ano_movimiento='" . $ano_movimiento . "' and cod_entidad_bancaria = '" . $cod_entidad_bancaria . "' and   cod_sucursal='" . $cod_sucursal . "' and  cuenta_bancaria='" . $cuenta_bancaria . "' and  tipo_documento = '" . $tipo_documento . "' and  numero_documento = '" . $numero_debito . "' ") != 0) {

	            $opcion = 'no';
	            $mensaje = 1;
	            //MENSAJE 
	            $error1 .= "8-";
	          } else {
	            $this->cstd03_movimientos_manuales->execute($sql_manuales);
	          } //fin else
	          //MENSAJE
	          $error3 .= $sql_manuales;

	          $resul = $this->cstd02_cuentas_bancarias->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and cod_entidad_bancaria = '" . $cod_entidad_bancaria . "' and   cod_sucursal='" . $cod_sucursal . "' and  cuenta_bancaria='" . $cuenta_bancaria . "' ", null, null, null);
	          foreach ($resul as $resul_aux) {
	            $nota_debito_dia              =    $resul_aux['cstd02_cuentas_bancarias']['nota_debito_dia'];
	            $nota_debito_mes              =    $resul_aux['cstd02_cuentas_bancarias']['nota_debito_mes'];
	            $nota_debito_ano              =    $resul_aux['cstd02_cuentas_bancarias']['nota_debito_ano'];
	            $disponibilidad_libro    =    $resul_aux['cstd02_cuentas_bancarias']['disponibilidad_libro'];
	          } //fin foreach

	          $nota_debito_dia              +=    $monto_para_actualizar_en_cuenta;
	          $nota_debito_mes              +=    $monto_para_actualizar_en_cuenta;
	          $nota_debito_ano              +=    $monto_para_actualizar_en_cuenta;
	          $disponibilidad_libro    -=    $monto_para_actualizar_en_cuenta;

	          if ($this->cstd02_cuentas_bancarias->execute("UPDATE cstd02_cuentas_bancarias SET nota_debito_dia=" . $nota_debito_dia . ", nota_debito_mes=" . $nota_debito_mes . ", nota_debito_ano=" . $nota_debito_ano . ", disponibilidad_libro=" . $disponibilidad_libro . "   WHERE cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and cod_entidad_bancaria = '" . $cod_entidad_bancaria . "' and   cod_sucursal='" . $cod_sucursal . "' and  cuenta_bancaria='" . $cuenta_bancaria . "'; ") >= 1) {
	          } else {
	            $opcion = 'no';
	          } //fin else


	          if ($opcion == "si") {



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
	              $f_dc_adj_array_pago = $f_dc_adj_array_pago_aux

	            );

	            if ($valor_motor_contabilidad == true) {

	              //$this->cstd09_notadebito_cuerpo_pago->execute("COMMIT;");
	              $this->set('Message_existe', 'Los datos fueron grabados correctamente');

	              $this->set('enano_movimiento', $ano_movimiento);
	              $this->set('ennumero_debito', $numero_debito);
	            } else {

	              ///  $this->cstd09_notadebito_cuerpo_pago->execute("ROLLBACK;");
	              if ($mensaje == 1) {
	                $this->set('errorMessage', 'La nota de debito ya existe en movimientos manuales');
	              } else {
	                $this->set('errorMessage', 'La datos no fueron almacenados');
	              }
	            } //fin else
	          } else {
	            //MENSAJE
	            //$this->cstd09_notadebito_cuerpo_pago->execute("ROLLBACK;");

	            if ($mensaje == 1) {
	              $this->set('errorMessage', 'La nota de debito ya existe en movimientos manuales');
	            } else {
	              $this->set('errorMessage', 'La datos no fueron almacenados');
	            }
	          } //fin else


	        } else {
	          $this->set('errorMessage', 'Los datos no pueden ser almacenado verifique el n&uacute;mero de comprobante egreso');
	          $this->index();
	          $this->render('index');
	        } //fin else
	      } else {
	        $this->set('errorMessage', 'Los datos no pueden ser almacenados');
	      } //fin else
    	} else {
        $this->set('errorMessage', 'La cuenta no tiene disponibilidad');
      } //fin else disponibilidad cuenta
    } else {
      $this->set('errorMessage', 'Los datos ya existen');
    } //fin else*/

  } else {
    $this->set('errorMessage', 'Los datos no pueden ser almacenados');
  }


  $this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'), 'tipo');

  $autorizados = $this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA() . " and ano_orden_pago = " . $this->ano_ejecucion() . " and tipo_orden=2  and condicion_actividad=1 and numero_cheque=0 and cod_tipo_pago = 4 group by autorizado", "distinct(autorizado)", 'autorizado ASC');

  $aut = array();
  foreach ($autorizados as $key => $value) {
    $value_aux = str_replace("/", "--", $value[0]['autorizado']);
    $value_aux = urlencode($value_aux);
    //$value_aux = str_replace("%", "#", $value_aux);
    $aut[$value_aux] = $value[0]['autorizado'];
  }

  $this->set("grupo", $aut);
  $this->set("ano", $this->ano_ejecucion());

  echo '<script>';
  echo "document.getElementById('concepto').value = ''; ";
  echo '</script>';

  $this->render('index');
}//fin guardar




function guardar_patria(){

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
  $cuenta = $_SESSION['BENEFICIARIO_ORDENES'];

   
  $ano_movimiento                         =         $this->data['cstp09_notadebito_nomina']['ano_movimiento'];
  $ann = $ano_movimiento;
  $numero_debito                          =         $this->data['cstp09_notadebito_nomina']['numero_debito'];
  $fecha_debito                           =         $this->Cfecha($this->data['cstp09_notadebito_nomina']['fecha'], 'A-M-D');
  $fd                                     =         $this->data['cstp09_notadebito_nomina']['fecha'];
  $concepto                               =         $this->data['cstp09_notadebito_nomina']['concepto'];
	$monto                                  =         $this->formato1($this->data['cstp09_notadebito_nomina']['monto']);
  $beneficiario                           =         urldecode($this->data['cstp09_notadebito_nomina']['beneficiario']);
  $fecha_proceso_registro                 =     date("Y-m-d");
 
	if(!empty($ano_movimiento) && !empty($numero_debito) && !empty($fecha_debito) && !empty($concepto) && !empty($monto) && !empty($beneficiario)){
			
		if($beneficiario!=""){
				
			foreach ($cuenta as $value) 
			{

				$ano              =   $value[0]['ano_orden_pago'];
				$var              =   $value[0]['numero_orden_pago'];
				$var1              =   $value[0]['numero_orden_pago_secuencia'];
				$fecha_orden_pago =   $value[0]['fecha_orden_pago'];
				$clase_orden      =   $value[0]['tipo_orden'];
		
					if($this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_movimiento=".$ano_movimiento.", numero_cheque=".$numero_debito.", fecha_cheque='".$fecha_debito."', documento_pago='1'
					 WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."'  and ano_orden_pago='".$ano."' and numero_orden_pago='".$var."' and numero_orden_pago_secuencia='".$var1."'; ")>=1){}else{$opcion = 'no';
					}//fin else

			}//fin for I


			if($opcion=="si"){
				$this->set('Message_existe', 'Los datos fueron grabados correctamente');
				$this->set('enano_movimiento', $ano_movimiento);
				$this->set('ennumero_debito', $numero_debito);
			}else{
				$this->set('errorMessage', 'La datos no fueron almacenados');
			}//fin else
		}else{
			$this->set('errorMessage', 'La datos no fueron almacenados.');
		}//fin else
	}else{	
		$this->set('errorMessage', 'Los datos no pueden ser almacenados');
	}

	echo'<script>';
	echo"document.getElementById('concepto').value = ''; ";
	echo'</script>';

	$this->portal_patria();
	$this->render('portal_patria');

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


 $cond.=" and ano_orden_pago=".$ano;
 $this->AddCero('grupo', $this->cepd03_ordenpago_cuerpo->generateList($cond.' and tipo_orden=2 ', 'numero_orden_pago ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago'));


}//fin index








function consulta($pag_num=null){
  $this->layout = "ajax";

   $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
   $ano = $this->ano_ejecucion();
   $this->set('ano_ejecucion', $ano);


	if(isset($pag_num)){
	    $radio_1 =  $this->Session->read('radio');
		$cod_1   =  $this->Session->read('cod1');
		$cod_2   =  $this->Session->read('cod2');
		$cod_3   =  $this->Session->read('cod3');
		$ano     =  $this->Session->read('cod4');
		$cond    =  $this->SQLCA();
		$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria=".$cod_3." and situacion=1 ";
		$cond2   = $this->SQLCA()." and ano_movimiento=".$ano." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_debito=".$pag_num;
		
		if(!isset($_SESSION['cod3'])){
			$cond2   = $this->SQLCA()." and ano_movimiento=".$ano." and numero_debito=".$pag_num;
		}

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
		}
		$i--;
	}//fin

	if(isset($numero[0]['numero_debito'])){
		$datos_cheque_cuerpo   = $this->cstd09_notadebito_cuerpo_pago->findAll($condicion. "   and  cod_entidad_bancaria='".$numero[0]['cod_entidad_bancaria']."'  and  cod_sucursal='".$numero[0]['cod_sucursal']."' and  cuenta_bancaria='".$numero[0]['cuenta_bancaria']."' and   ano_movimiento=".$numero[0]['ano_movimiento']."  and  numero_debito=".$numero[0]['numero_debito']);
		$datos_cheque_ordenes  = $this->v_cstd09_notadebito_ordenes->findAll($condicion. "       and  cod_entidad_bancaria='".$numero[0]['cod_entidad_bancaria']."'  and  cod_sucursal='".$numero[0]['cod_sucursal']."' and  cuenta_bancaria='".$numero[0]['cuenta_bancaria']."'  and  ano_movimiento=".$numero[0]['ano_movimiento']."  and  numero_debito=".$numero[0]['numero_debito']);
		$datos_cheque_partidas = $this->cstd09_notadebito_partidas_pago->findAll($condicion. " and  cod_entidad_bancaria='".$numero[0]['cod_entidad_bancaria']."'  and  cod_sucursal='".$numero[0]['cod_sucursal']."' and  cuenta_bancaria='".$numero[0]['cuenta_bancaria']."'  and  ano_movimiento=".$numero[0]['ano_movimiento']."  and  numero_debito=".$numero[0]['numero_debito']);
		$cond2   =  $this->SQLCA()." and ano_movimiento='".$numero[0]['ano_movimiento']."' and cod_entidad_bancaria=".$numero[0]['cod_entidad_bancaria']." and cod_sucursal=".$numero[0]['cod_sucursal']." and cuenta_bancaria='".$numero[0]['cuenta_bancaria']."' and clase_beneficiario=1 ";
		$lista   =  $this->cstd09_notadebito_cuerpo_pago->generateList($cond2, 'numero_debito ASC', null, '{n}.cstd09_notadebito_cuerpo_pago.numero_debito', '{n}.cstd09_notadebito_cuerpo_pago.beneficiario');

		$this->concatena($lista, 'lista');
		$resultado=$this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cuenta_bancaria='".$numero[0]['cuenta_bancaria']."'");
		$disponibilidad = $resultado[0]["cstd02_cuentas_bancarias"]["disponibilidad_libro"];
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

	/**
	 * Modificacion de la vista en el select de fecha de orden pago
	 * CREATE OR REPLACE VIEW v_cstd09_notadebito_ordenes AS 
		 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.clase_orden, a.ano_orden_pago, a.numero_orden_pago, a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.numero_debito, ( SELECT x.fecha_orden_pago
		           FROM cepd03_ordenpago_cuerpo x
		          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.ano_orden_pago = a.ano_orden_pago AND x.numero_orden_pago = a.numero_orden_pago limit 1) AS fecha_orden_pago
		   FROM cstd09_notadebito_ordenes a;

		ALTER TABLE v_cstd09_notadebito_ordenes
		  OWNER TO sisap;
	 */

  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


	if(!empty($this->data["cstp09_notadebito_nomina"]["fecha_debito"]) && !empty($this->data["cstp09_notadebito_nomina"]["concepto_anulacion"]) && !empty($this->data["cstp09_notadebito_nomina"]["cod_sucursal"]) && !empty($this->data["cstp09_notadebito_nomina"]["cuenta_bancaria"]) && !empty($this->data["cstp09_notadebito_nomina"]["cod_entidad_bancaria"]) && !empty($this->data["cstp09_notadebito_nomina"]["numero_debito"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		      $tipo_documento           =  251;
			  $concepto_anulacion       =  $this->data["cstp09_notadebito_nomina"]["concepto_anulacion"];
			  $fecha_proceso_anulacion  =  date("d/m/Y");
			  $condicion_documento      =  2;//cuando se guarda es Activo=1
			  $fecha_debito            =   $this->data["cstp09_notadebito_nomina"]["fecha_debito"];
			  $fd = $fecha_debito;
			  $cod_sucursal            =   $this->data["cstp09_notadebito_nomina"]["cod_sucursal"];
			  $ano_movimiento          =   $this->data["cstp09_notadebito_nomina"]["ano_movimiento"];
			  $cuenta_bancaria         =   $this->data["cstp09_notadebito_nomina"]["cuenta_bancaria"];
			  $numero_debito           =   $this->data["cstp09_notadebito_nomina"]["numero_debito"];
			  $cod_entidad_bancaria    =   $this->data["cstp09_notadebito_nomina"]["cod_entidad_bancaria"];

			  $pregunta_ejercicio      =   $this->data["cstp09_notadebito_nomina"]["pregunta_ejercicio"];

   $array = $this->cstd09_notadebito_cuerpo_pago->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and numero_debito=".$numero_debito);
   foreach($array as $aux){$monto_para_actualizar_en_cuenta   = $aux['cstd09_notadebito_cuerpo_pago']['monto'];}


 $year = $this->ano_ejecucion();
  $ano = null;


   $datos_partidas = $this->cstd09_notadebito_partidas_pago->findAll($conditions = $this->condicion()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_debito='$numero_debito'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);

$sql_3="";
foreach($datos_partidas as $aux_cstd09_notadebito_partidas){
	$ano_orden_pago      =    $aux_cstd09_notadebito_partidas['cstd09_notadebito_partidas_pago']['ano_orden_pago'];
	$numero_orden_pago   =    $aux_cstd09_notadebito_partidas['cstd09_notadebito_partidas_pago']['numero_orden_pago'];
	$numero_orden_pago_secuencia = $aux_cstd09_notadebito_partidas['cstd09_notadebito_partidas_pago']['numero_orden_pago_secuencia'];

	if($sql_3==""){
		$sql_3   .= "  ano_orden_pago='".$ano_orden_pago."'   and  numero_orden_pago='".$numero_orden_pago."' and numero_orden_pago_secuencia='".$numero_orden_pago_secuencia."'";
	}else{
		$sql_3   .= " or  (ano_orden_pago='".$ano_orden_pago."'    and  numero_orden_pago='".$numero_orden_pago."' and numero_orden_pago_secuencia='".$numero_orden_pago_secuencia."')";
	}

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

			  $contabilidad_numero_orden_pago_secuencia                 =    $row22['cepd03_ordenpago_cuerpo']['numero_orden_pago_secuencia'];

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

			  $datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento='".$contabilidad_ano_documento_origen."' and numero_documento='".$contabilidad_numero_documento_origen."'   ");

              $contabilidad_f_dc_adj_array_pago_aux = null;
              $contabilidad_f_dc_array_pago_aux     = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"];


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

		      $n_ops_array_pago_aux[$contador_contabilidad]   = $contabilidad_numero_orden_pago_secuencia;

		      $f_op_array_pago_aux[$contador_contabilidad]    = cambia_fecha($contabilidad_fecha_orden_pago);
		      $tipo_op_array_pago_aux[$contador_contabilidad] = $contabilidad_cod_tipo_documento;


		      $n_dc_adj_array_pago_aux[$contador_contabilidad]    = $contabilidad_numero_documento_adjunto;
		      $f_dc_adj_array_pago_aux[$contador_contabilidad]    = cambia_fecha($contabilidad_f_dc_adj_array_pago_aux);



		      $ano_dc_aux  = $contabilidad_ano_documento_origen;
		      $n_dc_aux    = $contabilidad_numero_documento_origen;
		      $f_dc_aux    = cambia_fecha($contabilidad_f_dc_array_pago_aux);

		      $ano_op_aux   = $contabilidad_ano_orden_pago;
		      $n_op_aux     = $contabilidad_numero_orden_pago;

		      $n_ops_aux     = $contabilidad_numero_orden_pago_secuencia;

		      $f_op_aux     = cambia_fecha($contabilidad_fecha_orden_pago);

		      $a_adj_op_aux = null;
		      $n_adj_op_aux = $contabilidad_numero_documento_adjunto;
		      $f_adj_op_aux = cambia_fecha($contabilidad_f_dc_adj_array_pago_aux);
		      $tp_op_aux    = $contabilidad_cod_tipo_documento;


		      $beneficiario = $contabilidad_autorizado;
		      $rif_cedula   = $contabilidad_rif;


         $monto_retenciones["monto_neto_orden"]        += $contabilidad_monto_neto_cobrar;
         $monto_retenciones["monto_total_retenciones"] += $suma_retencion;


if($this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET  numero_comprobante_islr=0, numero_comprobante_timbre=0, numero_comprobante_egreso=0, numero_comprobante_municipal=0, numero_comprobante_iva=0,  numero_comprobante_multa=0,  numero_comprobante_responsabilidad=0, cod_entidad_bancaria = '0',  cod_sucursal='0', cuenta_bancaria='0', numero_cheque='0'    WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."'  and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' and numero_orden_pago_secuencia = '".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago_secuencia']."'")>=1){}else{$opcion = 'no';}//fin else

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
 if($row2['cepd03_ordenpago_cuerpo']['ano_orden_pago'] == $row['cstd09_notadebito_partidas_pago']['ano_orden_pago']  &&  $row2['cepd03_ordenpago_cuerpo']['numero_orden_pago'] == $row['cstd09_notadebito_partidas_pago']['numero_orden_pago'] && $row2['cepd03_ordenpago_cuerpo']['numero_orden_pago_secuencia'] == $row['cstd09_notadebito_partidas_pago']['numero_orden_pago_secuencia']){
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
		$nota_debito_dia              =    $resul_aux['cstd02_cuentas_bancarias']['nota_debito_dia'];
		$nota_debito_mes              =    $resul_aux['cstd02_cuentas_bancarias']['nota_debito_mes'];
		$nota_debito_ano              =    $resul_aux['cstd02_cuentas_bancarias']['nota_debito_ano'];
		$disponibilidad_libro    =    $resul_aux['cstd02_cuentas_bancarias']['disponibilidad_libro'];
	}

    $nota_debito_dia              -=    $monto_para_actualizar_en_cuenta;
	$nota_debito_mes              -=    $monto_para_actualizar_en_cuenta;
	$nota_debito_ano              -=    $monto_para_actualizar_en_cuenta;
	$disponibilidad_libro    +=    $monto_para_actualizar_en_cuenta;



if($this->cstd02_cuentas_bancarias->execute("UPDATE cstd02_cuentas_bancarias SET nota_debito_dia=".$nota_debito_dia.", nota_debito_mes=".$nota_debito_mes.", nota_debito_ano=".$nota_debito_ano.", disponibilidad_libro=".$disponibilidad_libro."   WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' ")>=1){}else{$opcion = 'no';}//fin else

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


$this->consulta_index('1');
$this->render('consulta_index');


}//fin function


function datos_imputacion($opcion = null, $var=null){

	$this->layout="ajax";


  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  $ano = $this->ano_ejecucion();   $this->set('ano_ejecucion', $ano);

	$var = str_replace("--","/", $var);
	$var = urldecode($var);

	$mes_cierre = $this->cstd09_notadebito_cuerpo_pago->execute("SELECT mes_cierre_mensual FROM ccfd04_cierre_mes WHERE cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_cierre_mensual='".$ano."'");


  if($this->Session->read('pregunta_ejercicio')==1){
     $ano--;
  }//fin if

	if($opcion == 1){

  	if($var!=null){
			/**
			 * ALBERTO VELIZ
			 * CONSULTAR TODAS LAS ORDENES DE PAGO RELACIONADA CON EL BENEFICIARIO SOLICITADO
			 */
			$datos_ordenes_pago_beneficiario = $this->cepd03_ordenpago_cuerpo->execute("
					select	
						a.tipo_orden,
						a.ano_orden_pago, 
						a.fecha_orden_pago, 
						a.numero_orden_pago,
						a.numero_orden_pago_secuencia, 
						a.autorizado, 
						a.monto_total
					from 
						cepd03_ordenpago_cuerpo AS a
					where
						a.cod_presi = ".$cod_presi.
						" and a.cod_entidad = ".$cod_entidad.
						" and a.cod_tipo_inst = ".$cod_tipo_inst.
						" and a.cod_inst = ".$cod_inst.
						" and a.cod_dep = ".$cod_dep.
						" and a.ano_orden_pago = ".$ano." and 
						a.autorizado = '".$var."' and 
						a.numero_cheque = 0 and
						a.condicion_actividad=1
					order by 
						a.ano_orden_pago, 
						a.fecha_orden_pago, 
						a.numero_orden_pago, 
						a.autorizado DESC
			");

			$fecha_orden_pago_aux =   explode('-',$datos_ordenes_pago_beneficiario[0][0]['fecha_orden_pago']);

			if($fecha_orden_pago_aux[1]<=$mes_cierre[0][0]["mes_cierre_mensual"]){

					echo'<script>';
				  echo "document.getElementById('concepto').value = ''; ";
					echo'</script>';
					$this->set('errorMessage', 'La orden de pago es de un mes cerrado presupuestariamente');
			}else{
			
				if(count($datos_ordenes_pago_beneficiario) == 1){
					$concepto_orden_pago_individual = $this->cepd03_ordenpago_cuerpo->execute("
						select	
							a.concepto
						from 
							cepd03_ordenpago_cuerpo AS a
						where
							a.cod_presi = ".$cod_presi.
							" and a.cod_entidad = ".$cod_entidad.
							" and a.cod_tipo_inst = ".$cod_tipo_inst.
							" and a.cod_inst = ".$cod_inst.
							" and a.cod_dep = ".$cod_dep.
							" and a.ano_orden_pago = ".$ano." and 
							a.numero_orden_pago = ".$datos_ordenes_pago_beneficiario[0][0]['numero_orden_pago']." and 
							a.numero_orden_pago_secuencia = '".$datos_ordenes_pago_beneficiario[0][0]['numero_orden_pago_secuencia']."' and
							a.condicion_actividad=1
					");
					echo'<script>';
					  echo "document.getElementById('concepto').value = '".$concepto_orden_pago_individual[0][0]['concepto']."'; ";
					echo'</script>';
				}else{
					echo'<script>';
					  echo "document.getElementById('concepto').value = ''; ";
					echo'</script>';
				}

				$_SESSION['BENEFICIARIO_ORDENES'] = $datos_ordenes_pago_beneficiario;


				$datos_orden_pago_cuerpo   = $this->cepd03_ordenpago_cuerpo->findAll($condicion. " and  ano_orden_pago=".$ano."  and  autorizado='".$var."' limit 1");

				if($datos_orden_pago_cuerpo[0]["cepd03_ordenpago_cuerpo"]["cedula_identidad"]==0){
					$rif_input = $datos_orden_pago_cuerpo[0]["cepd03_ordenpago_cuerpo"]["rif"];
				}else{
					$rif_input = $datos_orden_pago_cuerpo[0]["cepd03_ordenpago_cuerpo"]["cedula_identidad"];
				}

				echo'<script>';
				  echo"document.getElementById('rif_input').value = '".$rif_input."'; ";
				  echo"document.getElementById('tipo_documento').value  = '".$datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['cod_tipo_documento']."'; ";
				  echo"document.getElementById('tipo_pago').value       = '".$datos_orden_pago_cuerpo[0]['cepd03_ordenpago_cuerpo']['cod_tipo_pago']."'; ";
				echo'</script>';

				$this->set('datos_ordenes_pago_beneficiario', $datos_ordenes_pago_beneficiario);
			}
				$this->render('agregar_orden_pago_session');
		}else{

			echo'<script>';
				echo"document.getElementById('bene').value = '' ; ";
				echo"document.getElementById('rif_input').value = ''; ";
				echo"document.getElementById('tipo_documento').value  = ''; ";
				echo"document.getElementById('tipo_pago').value       = ''; ";
				echo"document.getElementById('monto').value       = ''; ";
				echo "document.getElementById('concepto').value = ''; ";
			echo'</script>';

			$this->set('datos_orden_pago_cuerpo', '');
			$this->set('datos_orden_pago_partidas', '');

			//}//else
		}//fin else
  }else if($opcion == 2){
  		
  	if($var!=null){
			
			/**
			 * ALBERTO VELIZ
			 * CONSULTAR TODAS LAS PARTIDAS RELACIONADAS A LAS ORDENES DE PAGOS DEL BENEFICIARIO SOLICITADO
			 */
			$datos_orden_pago_partidas = $this->cepd03_ordenpago_cuerpo->execute("
					select 
						b.ano_orden_pago, 
						b.cod_sector, 
						b.cod_programa, 
						b.cod_sub_prog, 
						b.cod_proyecto, 
						b.cod_activ_obra, 
						b.cod_partida, 
						b.cod_generica, 
						b.cod_especifica, 
						b.cod_sub_espec, 
						b.cod_auxiliar,
						sum(b.monto) as monto
					from 
						cepd03_ordenpago_cuerpo AS a
						join
						cepd03_ordenpago_partidas AS b
						on
						a.cod_presi = b.cod_presi and
						a.cod_entidad = b.cod_entidad and
						a.cod_tipo_inst = b.cod_tipo_inst and
						a.cod_inst = b.cod_inst and
						a.cod_dep = b.cod_dep and
						a.ano_orden_pago = b.ano_orden_pago and
						a.numero_orden_pago = b.numero_orden_pago and
						a.numero_orden_pago_secuencia = b.numero_orden_pago_secuencia
					where
						a.cod_presi = ".$cod_presi.
						" and a.cod_entidad = ".$cod_entidad.
						" and a.cod_tipo_inst = ".$cod_tipo_inst.
						" and a.cod_inst = ".$cod_inst.
						" and a.cod_dep = ".$cod_dep.
						" and a.ano_orden_pago = ".$ano." and 
						a.autorizado = '".$var."' and 
						a.numero_cheque = 0 and
						a.condicion_actividad=1
					group by		
						b.ano_orden_pago, 
						b.cod_sector, 
						b.cod_programa, 
						b.cod_sub_prog, 
						b.cod_proyecto, 
						b.cod_activ_obra, 
						b.cod_partida, 
						b.cod_generica, 
						b.cod_especifica, 
						b.cod_sub_espec, 
						b.cod_auxiliar
					order by 
						b.ano_orden_pago, 
						b.cod_sector, 
						b.cod_programa, 
						b.cod_sub_prog, 
						b.cod_proyecto, 
						b.cod_activ_obra, 
						b.cod_partida, 
						b.cod_generica, 
						b.cod_especifica, 
						b.cod_sub_espec, 
						b.cod_auxiliar DESC
			");
			$datos_ordenes_pago_beneficiario = $this->cepd03_ordenpago_cuerpo->execute("
					select	
						a.tipo_orden,
						a.ano_orden_pago, 
						a.fecha_orden_pago, 
						a.numero_orden_pago,
						a.numero_orden_pago_secuencia, 
						a.autorizado, 
						a.monto_total
					from 
						cepd03_ordenpago_cuerpo AS a
					where
						a.cod_presi = ".$cod_presi.
						" and a.cod_entidad = ".$cod_entidad.
						" and a.cod_tipo_inst = ".$cod_tipo_inst.
						" and a.cod_inst = ".$cod_inst.
						" and a.cod_dep = ".$cod_dep.
						" and a.ano_orden_pago = ".$ano." and 
						a.autorizado = '".$var."' and 
						a.numero_cheque = 0 and
						a.condicion_actividad=1
					order by 
						a.ano_orden_pago, 
						a.fecha_orden_pago, 
						a.numero_orden_pago, 
						a.autorizado DESC
			");
			$fecha_orden_pago_aux =   explode('-',$datos_ordenes_pago_beneficiario[0][0]['fecha_orden_pago']);

			if($fecha_orden_pago_aux[1]<=$mes_cierre[0][0]["mes_cierre_mensual"]){
					$this->set('datos_orden_pago_cuerpo', '');
					$this->set('datos_orden_pago_partidas', '');
					$this->set('errorMessage', 'La orden de pago es de un mes cerrado presupuestariamente');
			}else{

				$_SESSION['BENEFICIARIO_PARTIDAS'] = $datos_orden_pago_partidas;


				$this->set('datos_orden_pago_partidas', $datos_orden_pago_partidas);
			}

		}else{

			$this->set('datos_orden_pago_cuerpo', '');
			$this->set('datos_orden_pago_partidas', '');
			
		}//fin else
  }
		





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
	//$verifica = "mes_cerrado";
}

// }

if($verifica=="si"){
	for($i=1; $i<=($cuenta-1); $i++){
	 if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no"){
	    $cuenta_aux++;
	 }//fin if
	}//fin for





 	if($cuenta_aux==0){
 	   $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['autorizado']=$this->data['cstp09_notadebito_nomina']['beneficiario'];

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



	$tipo_documento = $this->data['cstp09_notadebito_nomina']['tipo_documento'];
	$tipo_pago      = $this->data['cstp09_notadebito_nomina']['tipo_pago'];

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
		$a_a = str_replace(" ",'',$this->data['cstp09_notadebito_nomina']['beneficiario']);
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
			if($_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$i]['DATOS_ORDEN_PAGO']['usar']!="no" && ($this->data['cstp09_notadebito_nomina']['ano']==$ano_orden_pago && $this->data['cstp09_notadebito_nomina']['num_orden']==$numero_orden_pago)){
		  $opcion = "no";
		  }//fin if
		}//fin for

		if($opcion=="si"){

	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['autorizado']         = $this->data['cstp09_notadebito_nomina']['beneficiario'];
	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['fecha_orden_pago']   = $this->data['cstp09_notadebito_nomina']['fecha_op'];
	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['ejercicio_anterior'] = $pregunta_ejercicio2;
	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['tipo_documento']     = $tipo_documento;
	       $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$cuenta]['DATOS_ORDEN_PAGO']['tipo_pago']          = $tipo_pago;

	       if(!isset($_SESSION['CUENTA_ORDENES_PAGO'])){$_SESSION['CUENTA_ORDENES_PAGO']=1;}else{$_SESSION['CUENTA_ORDENES_PAGO'] = $_SESSION['CUENTA_ORDENES_PAGO'] + 1;}
	       $_SESSION['ORDEN_PAGO_TOTAL']['rif'] = $this->data['cstp09_notadebito_nomina']['rif_input'];
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
