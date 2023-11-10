<?php

class Cstp30DebitoIslrController extends AppController{



    var $name = "cstp30_debito_islr";
 	var $uses = array('cstd30_debito_cuerpo', 'cstd30_debito_partidas', 'cstd07_retenciones_cuerpo_iva', 'cstd07_retenciones_partidas_iva',
                      'cstd07_retenciones_cuerpo_islr', 'cstd07_retenciones_partidas_islr', 'cstd07_retenciones_cuerpo_timbre',
                      'cstd07_retenciones_partidas_timbre', 'cstd07_retenciones_cuerpo_islr', 'cstd07_retenciones_partidas_islr',
                      'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_partidas','cstd30_debito_ordenes', 'ccfd04_cierre_mes',
                      'cstd03_cheque_poremitir','cstd04_movimientos_generales','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','cfpd01_grupo',
                      'cstd02_cuentas_bancarias','ccfd03_instalacion','cstd03_cheque_numero', 'cugd03_acta_anulacion_numero',
                      'cstd06_comprobante_cuerpo_egreso', 'cstd06_comprobante_cuerpo_islr', 'cstd06_comprobante_cuerpo_iva',
                      'cstd06_comprobante_cuerpo_islr', 'cstd06_comprobante_cuerpo_timbre', 'cugd03_acta_anulacion_cuerpo',
                      'cstd06_comprobante_numero_egreso', 'cstd06_comprobante_numero_islr', 'cstd06_comprobante_numero_iva', 'cstd03_beneficiario_retencion_islr',
                      'cstd06_comprobante_numero_islr', 'cstd06_comprobante_numero_timbre','cstd04_movimientos_generales',
                      'cstd06_comprobante_poremitir_egreso', 'cstd06_comprobante_poremitir_islr', 'cstd06_comprobante_poremitir_iva',
                      'cstd06_comprobante_poremitir_islr', 'cstd06_comprobante_poremitir_timbre','ccfd03_instalacion', 'cfpd23_numero_asiento_pagado',
                      'cfpd05', 'cugd04', 'cfpd23', 'cugd02_dependencia', 'cstd03_movimientos_manuales',

                      'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo'

                      );

 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');









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



function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zeros($x).' - '.$y;

		}
		$this->set($nomVar, $cod);
	}
}



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
	$this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
$cond= $this->SQLCA();

$this->data=null;

	$ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $ano = $this->ano_ejecucion();
 $this->set('ano',$ano);
 $this->Session->write('cod4',$ano);

  $this->Session->delete('ORDEN_PAGO');
  $this->Session->delete('CUENTA_ORDENES_PAGO');
  $this->Session->delete('ORDEN_PAGO_TOTAL');
  $this->Session->delete('opcion_emitir');

  $this->Session->delete('cod_entidad_bancaria_aux');
$this->Session->delete('cod_sucursal_aux');
$this->Session->delete('cuenta_bancaria');
$this->Session->delete('numero_debito');


  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_IVA']        =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_ISRL']       =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_TIMBRE']     =   "no";
  $_SESSION['ORDEN_PAGO_TOTAL']['HAY_MUNICIPIO']  =   "no";
  $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['autorizado'] = "";

//$cond="cod_presi=1 and cod_entidad=1 and cod_tipo_inst=1 and cod_inst=1 and cod_dep=1 and ano_orden_pago=2008";
 $cond.=" and ano_orden_pago=".$ano;
 $this->AddCero('grupo', $this->cepd03_ordenpago_cuerpo->generateList($cond.' and tipo_orden=2  and condicion_actividad=1', 'numero_orden_pago ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago'));
}//fin index











function index2($var=null){
$this->layout = "ajax";
$this->Session->delete('radio');
//$this->set('entidad_federal', $this->Session->read('entidad_federal'));
$this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
$cond= $this->SQLCA();

if($var!=null){$_SESSION['opcion_emitir']=$var;}

	$ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $ano = $this->ano_ejecucion();
 $this->set('ano',$ano);
 $this->Session->write('cod4',$ano);

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
  $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_1']['DATOS_ORDEN_PAGO']['autorizado'] = "";

//$cond="cod_presi=1 and cod_entidad=1 and cod_tipo_inst=1 and cod_inst=1 and cod_dep=1 and ano_orden_pago=2008";
 $cond.=" and ano_orden_pago=".$ano;
 $this->AddCero('grupo', $this->cepd03_ordenpago_cuerpo->generateList($cond.' and tipo_orden=2  and condicion_actividad=1', 'numero_orden_pago ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago'));
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
			  $lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
			}//fin if
			if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->concatena($lista, 'vector');}
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
            if($lista==""){$lista = array();  $this->set('vector',array());}else{ $this->concatena($lista,'vector');}
		break;
	}//fin wsitch
	}else{
			echo "";
	}
}//fin select codigos bancarios









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
			$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' and clase_beneficiario=2";

           $lista=  $this->cstd30_debito_cuerpo->generateList($cond2." and ano_movimiento='".$year2."'    ", 'numero_debito ASC', null, '{n}.cstd30_debito_cuerpo.numero_debito', '{n}.cstd30_debito_cuerpo.numero_debito');


	}else{$lista="";}//fin else

$this->set('lista', $lista);

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
//$this->Session->write('numero_debito',             $numero);

       echo'<script>';
         echo"document.getElementById('numero_debito').value = '';  ";
         echo"document.getElementById('numero_debito').disabled = false;  ";
         echo"document.getElementById('numero_debito').readOnly = false;  ";
      echo'</script>';





	   }else{$numero="";
	   	   echo'<script>';
	   	     echo"document.getElementById('numero_debito').value = '';  ";
	   	     echo"document.getElementById('numero_debito').readOnly = true;  ";
	   	   echo'</script>'; }//fin else




$this->set("numero",$numero);




}//fin function






function disponibilidad($var=null){
	$this->layout="ajax";
	$disponible = "";


	$resultado=$this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cuenta_bancaria='".$var."' ");

    if(isset($resultado[0]["cstd02_cuentas_bancarias"]["disponibilidad_libro"])){$disponible=$resultado[0]["cstd02_cuentas_bancarias"]["disponibilidad_libro"];}

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





function datos($opcion,$var=null){
		$this->layout="ajax";
	if(isset($var) && $var!=''){
	switch($opcion){
		case 'bene':
		$resultado=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and numero_orden_pago='".$var."' ");
		$resul=$resultado[0]["cepd03_ordenpago_cuerpo"]["autorizado"];
		//echo $resul;
		$this->set('beneficiario',$resul);
			//$c=$this->cstd01_sucursales_bancarias->findByCod_sucursal($codigo);

	break;
		case 'monto':
		$resultado=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and numero_orden_pago='".$var."' ");
		$resul=$resultado[0]["cepd03_ordenpago_cuerpo"]["monto_total"];
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

echo"<script>observar_table();</script>";

if($numero_debito!=""){$this->cstd03_cheque_numero->execute("UPDATE cstd03_cheque_numero SET  situacion='1'  WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and numero_debito='".$numero_debito."' ");}

echo"<script>menu_activo();</script>";

}//fin salir











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


$this->Session->delete('cod_entidad_bancaria_aux');
$this->Session->delete('cod_sucursal_aux');
$this->Session->delete('cuenta_bancaria');
$this->Session->delete('numero_debito');

   $opcion = 'si';
   $numero_comprobante_islr     =     0;
   $numero_comprobante_timbre        =     0;
   $numero_comprobante_islr          =     0;
   $numero_comprobante_iva           =     0;
   $numero_comprobante_egreso        =     0;


//    cstd30_debito_cuerpo


  $ano_movimiento                         =         $this->data['cstp30_debito_islr']['ano'];
  $ann = $ano_movimiento;
  $cod_entidad_bancaria                   =         $this->data['cstp30_debito_islr']['entidad'];
  $cod_sucursal                           =         $this->data['cstp30_debito_islr']['sucursal'];
  $cuenta_bancaria                        =         $this->data['cstp30_debito_islr']['cuenta'];
  $numero_debito                          =         $this->data['cstp30_debito_islr']['numero_debito'];
  $fecha_debito                           =         $this->Cfecha($this->data['cstp30_debito_islr']['fecha'], 'A-M-D');
  $fd                                     =         $this->data['cstp30_debito_islr']['fecha'];
  $concepto                               =         $this->data['cstp30_debito_islr']['concepto'];
  //$status_debito                          =         $this->data['cstp30_debito_islr']['status_debito'];
  //$clase_beneficiario                     =         $this->data['cstp30_debito_islr']['clase_beneficiario'];

  $status_debito                          =         1;
  $clase_beneficiario                     =         2;

  $rif_cedula                             =         $_SESSION['ORDEN_PAGO_TOTAL']['rif'];
  $cod_tipo_pago                          =         $_SESSION['ORDEN_PAGO_TOTAL']['cod_tipo_pago'];
  $monto                                  =         $_SESSION['ORDEN_PAGO_TOTAL']['monto'];
  $beneficiario                           =         $_SESSION['ORDEN_PAGO_TOTAL']['autorizado'];

  $fecha_proceso_registro                 =     date("d/m/Y");
  $dia_asiento_registro                   =     date("d");
  $mes_asiento_registro                   =     date("m");
  $ano_asiento_registro                   =     date("Y");
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




// cstd30_debito_cuerpo PREGUNTA SI EXISTE YA EL CHEQUE


if($this->cstd30_debito_cuerpo->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_movimiento."'  and  cod_entidad_bancaria='".$cod_entidad_bancaria."' and cod_sucursal='".$cod_sucursal."' and cuenta_bancaria='".$cuenta_bancaria."' and numero_debito='".$numero_debito."' ") == 0){



  $datos_cstd06_comprobante_numero_egreso = $this->cstd06_comprobante_numero_egreso->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."'  and cod_dep='".$cod_dep."'  and ano_comprobante_egreso='".$ano_movimiento."'");
  foreach($datos_cstd06_comprobante_numero_egreso as $aux){$numero_comprobante_egreso = $aux['cstd06_comprobante_numero_egreso']['numero_comprobante_egreso'];}
  $numero_comprobante_egreso++;



if($this->cstd06_comprobante_cuerpo_egreso->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_comprobante_egreso='".$ano_movimiento."'  and  numero_comprobante_egreso='".$numero_comprobante_egreso."'  ") == 0){


$this->cstd06_comprobante_cuerpo_egreso->execute(" BEGIN; ");


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






// cstd30_debito_cuerpo

$sql_cstd30_debito_cuerpo = "INSERT INTO cstd30_debito_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito, fecha_debito, beneficiario, monto, concepto, rif_cedula, cod_tipo_pago, status_debito, clase_beneficiario, fecha_proceso_registro, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, username_registro, condicion_actividad, ano_anulacion, numero_anulacion, fecha_proceso_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, ano_asiento_anulacion, numero_asiento_anulacion, username_anulacion, numero_comprobante_egreso) ";
$sql_cstd30_debito_cuerpo.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."', '".$fecha_debito."', '".$beneficiario."', '".$monto."', '".$concepto."', '".$rif_cedula."', '".$cod_tipo_pago."', '".$status_debito."', '".$clase_beneficiario."', '".$fecha_proceso_registro."', '".$dia_asiento_registro."', '".$mes_asiento_registro."', '".$ano_asiento_registro."', '".$numero_asiento_registro."', '".$username_registro."', '".$condicion_actividad."', '".$ano_anulacion."', '".$numero_anulacion."', '".$fecha_proceso_anulacion."', '".$dia_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$ano_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$username_anulacion."', '".$numero_comprobante_egreso."');";


$monto_debito = $monto;


if($this->cstd30_debito_cuerpo->execute($sql_cstd30_debito_cuerpo)>=1){}else{$opcion = 'no';}




// cstd03_cheque_poremitir
/*
$sql_cstd03_cheque_poremitir = "INSERT INTO cstd03_cheque_poremitir ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito) ";
$sql_cstd03_cheque_poremitir.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."');";
if($this->cstd03_cheque_poremitir->execute($sql_cstd03_cheque_poremitir)>=1){}else{$opcion = 'no';}
*/

// cstd06_comprobante_poremitir_egreso




// cstd04_movimientos_generales

  $mes                   =  $fecha_debito[5].$fecha_debito[6];
  $dia                   =  $fecha_debito[8].$fecha_debito[9];
  $tipo_documento        =  "3";


$sql_cstd04_movimientos_generales = "INSERT INTO cstd04_movimientos_generales( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, mes, dia, tipo_documento, numero_documento, monto) ";
$sql_cstd04_movimientos_generales.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$mes."', '".$dia."', '".$tipo_documento."', '".$numero_debito."', '".$monto."');";

if($this->cstd04_movimientos_generales->execute($sql_cstd04_movimientos_generales)>=1){}else{$opcion = 'no';}//fin else


$mensaje = 0;

   $sql_manuales="insert into cstd03_movimientos_manuales values('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."','".$ano_movimiento."','".$cod_entidad_bancaria."','".$cod_sucursal."','".$cuenta_bancaria."','".$tipo_documento."','".$numero_debito."','".$fecha_debito."','".$beneficiario."','".$monto."','".$concepto."','".$fecha_proceso_registro."','0','0','0','0','".$username_registro."','1','0', '0', '1900/01/01', '0', '0', '0', '0', '0', '0', '0')";
if($this->cstd03_movimientos_manuales->execute($sql_manuales)>=1){}else{$opcion = 'no'; $mensaje = 1;}//fin else



/////////////////////INICIO ORDENES DE PAGO////////////////////////////////

if(!isset($_SESSION['opcion_emitir'])){$_SESSION['opcion_emitir']=1;}


$j  = 0;
$x  = 0;
$jj = 0;
$var_aux = 0;

$dato = $this->cepd03_ordenpago_cuerpo->execute("


select


	  a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano_orden_pago,
	  a.clase_orden,
	  a.numero_orden_pago,
	  a.ano_anterior,
	  a.monto                 as   monto_cuerpo_retencion,
      c.beneficiario,
      c.autorizado,
      c.cod_tipo_pago




FROM


         cstd07_retenciones_cuerpo_islr a, cstd07_retenciones_partidas_islr b,  cepd03_ordenpago_cuerpo c


WHERE

        a.cod_presi            =     ".$cod_presi."         and
        a.cod_entidad          =     ".$cod_entidad."       and
        a.cod_tipo_inst        =     ".$cod_tipo_inst."     and
        a.cod_inst             =     ".$cod_inst."          and
        a.cod_dep              =     ".$cod_dep."           and
        c.ano_movimiento       =     ".$ano_movimiento."           and
	    c.cod_entidad_bancaria =      ".$cod_entidad_bancaria."    and
	    c.cod_sucursal         =      ".$cod_sucursal."            and
	    c.cuenta_bancaria      =      '".$cuenta_bancaria."'       and
        b.cod_presi            =      a.cod_presi           and
        b.cod_entidad          =      a.cod_entidad         and
        b.cod_tipo_inst        =      a.cod_tipo_inst       and
        b.cod_inst             =      a.cod_inst            and
        b.cod_dep              =      a.cod_dep             and
        b.ano_orden_pago       =      a.ano_orden_pago      and
        b.numero_orden_pago    =      a.numero_orden_pago   and
        c.cod_presi            =      a.cod_presi           and
        c.cod_entidad          =      a.cod_entidad         and
        c.cod_tipo_inst        =      a.cod_tipo_inst       and
        c.cod_inst             =      a.cod_inst            and
        c.cod_dep              =      a.cod_dep             and
        c.ano_orden_pago       =      a.ano_orden_pago      and
        c.numero_orden_pago    =      a.numero_orden_pago   and
        a.ano_movimiento       =      0                     and
	    a.cod_entidad_bancaria =      0                     and
	    a.cod_sucursal         =      0                     and
	    a.cuenta_bancaria      =     '0'                    and
	    a.status               =      ".$_SESSION['opcion_emitir']."



 GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano_orden_pago,
	  a.clase_orden,
	  a.numero_orden_pago,
	  a.ano_anterior,
	  monto_cuerpo_retencion,
      c.beneficiario,
      c.autorizado,
      c.cod_tipo_pago

ORDER BY

           a.ano_orden_pago,
           a.numero_orden_pago ASC

           	 ");





 if(isset($dato)){
  if($dato!=null){
    foreach($dato as $ve2){

    	$ano_anterior =  $ve2[0]['ano_anterior'];


if(isset($this->data['cstp30_debito_islr']['ano_orden_pago_'.$var_aux])){

									if($ve2[0]['ano_orden_pago']    == $this->data['cstp30_debito_islr']['ano_orden_pago_'.$var_aux] &&
									   $ve2[0]['numero_orden_pago'] == $this->data['cstp30_debito_islr']['numero_orden_pago_'.$var_aux]
									){

									 $var_aux++;





																				//   cstd30_debito_ordenes

																				$sql_cstd30_debito_ordenes = "INSERT INTO cstd30_debito_ordenes(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, clase_orden, ano_orden_pago, numero_orden_pago, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito) ";
																				$sql_cstd30_debito_ordenes.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ve2[0]['clase_orden']."', '".$ve2[0]['ano_orden_pago']."', '".$ve2[0]['numero_orden_pago']."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."');";

																				if($this->cstd30_debito_ordenes->execute($sql_cstd30_debito_ordenes)>=1){}else{$opcion = 'no';}




																				/*
																				$sql_cstd06_comprobante_poremitir_egreso = "INSERT INTO cstd06_comprobante_poremitir_egreso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username, ano_comprobante_egreso, numero_comprobante_egreso, ano_orden_pago, clase_orden, numero_orden_pago)";
																				$sql_cstd06_comprobante_poremitir_egreso.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$username."', '".$ano_comprobante_egreso."', '".$numero_comprobante_egreso."', '".$ve2[0]['ano_orden_pago']."', '".$ve2[0]['clase_orden']."', '".$ve2[0]['numero_orden_pago']."'); ";
																				if($this->cstd06_comprobante_poremitir_egreso->execute($sql_cstd06_comprobante_poremitir_egreso)>=1){}else{$opcion = 'no';}//fin else
																				*/



									$dato2 = $this->cepd03_ordenpago_cuerpo->execute("


									select


										  a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.ano_orden_pago,
										  a.clase_orden,
										  a.numero_orden_pago,
										  a.monto                  as   monto_cuerpo_retencion,
										  a.fecha_proceso_registro,
										  a.status,
										  a.ano_movimiento,
										  a.cod_entidad_bancaria,
										  a.cod_sucursal,
										  a.cuenta_bancaria ,
										  a.numero_cheque,
										  a.fecha_proceso_anulacion,
									      b.ano,
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
										  b.monto,
										  b.numero_control_compromiso,
										  b.numero_control_causado,
										  b.numero_control_pagado,
									      c.ano_movimiento             as    ano_movimiento_cheque_beneficiario,
										  c.cod_entidad_bancaria       as    cod_entidad_bancaria_cheque_beneficiario,
										  c.cod_sucursal               as    cod_sucursal_cheque_beneficiario,
										  c.cuenta_bancaria            as    cuenta_bancaria_cheque_beneficiario,
										  c.numero_cheque              as    numero_cheque_cheque_beneficiario,
										  c.fecha_cheque               as    fecha_cheque_cheque_beneficiario,
										  c.cod_tipo_documento,
									      c.rif,
									      c.beneficiario,
									      c.autorizado,
									      c.cedula_identidad,
									      c.concepto,
									      c.cod_tipo_pago




									FROM


									         cstd07_retenciones_cuerpo_islr a, cstd07_retenciones_partidas_islr b,  cepd03_ordenpago_cuerpo c


									WHERE

									        a.cod_presi            =     ".$cod_presi."         and
									        a.cod_entidad          =     ".$cod_entidad."       and
									        a.cod_tipo_inst        =     ".$cod_tipo_inst."     and
									        a.cod_inst             =     ".$cod_inst."          and
									        a.cod_dep              =     ".$cod_dep."           and
									        c.ano_movimiento       =     ".$ano_movimiento."           and
										    c.cod_entidad_bancaria =      ".$cod_entidad_bancaria."    and
										    c.cod_sucursal         =      ".$cod_sucursal."            and
										    c.cuenta_bancaria      =      '".$cuenta_bancaria."'       and
									        b.cod_presi            =      a.cod_presi           and
									        b.cod_entidad          =      a.cod_entidad         and
									        b.cod_tipo_inst        =      a.cod_tipo_inst       and
									        b.cod_inst             =      a.cod_inst            and
									        b.cod_dep              =      a.cod_dep             and
									        b.ano_orden_pago       =      a.ano_orden_pago      and
									        b.numero_orden_pago    =      a.numero_orden_pago   and
									        c.cod_presi            =      a.cod_presi           and
									        c.cod_entidad          =      a.cod_entidad         and
									        c.cod_tipo_inst        =      a.cod_tipo_inst       and
									        c.cod_inst             =      a.cod_inst            and
									        c.cod_dep              =      a.cod_dep             and
									        c.ano_orden_pago       =      a.ano_orden_pago      and
									        c.numero_orden_pago    =      a.numero_orden_pago   and
									        a.ano_movimiento       =      0                     and
										    a.cod_entidad_bancaria =      0                     and
										    a.cod_sucursal         =      0                     and
										    a.cuenta_bancaria      =     '0'                    and
										    a.status               =      ".$_SESSION['opcion_emitir']."


									ORDER BY

									           ano_orden_pago,
									           numero_orden_pago,
									           cod_sector,
									           cod_programa,
									           cod_sub_prog,
									           cod_proyecto,
									           cod_activ_obra,
									           cod_partida,
									           cod_generica,
									           cod_especifica,
									           cod_sub_espec,
									           cod_auxiliar ASC; ");


																					$numero_pagado= $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', $conditions = $this->condicionNDEP()." and ano_pagado='$ann'", $order =null);
																					if(!empty($numero_pagado)){
																						$numero_pagado ++;
																						$sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='$ann' and ".$this->condicionNDEP().";";
																					}else{
																						$numero_pagado = 1;
																						$sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_pagado');";
																					}
																					$sw_numero_pagado = $this->cfpd23_numero_asiento_pagado->query($sql_numero_pagado);






																				///////////////////////////// PARTIDAS /////////////////////////////////////////////

																				$j = 0;
																				$x=0;

																				$datos_orden_pago_partidas = $this->cstd07_retenciones_partidas_islr->findAll($condicion. ' and  ano_orden_pago='.$ve2[0]['ano_orden_pago'].'  and  numero_orden_pago='.$ve2[0]['numero_orden_pago'], null, 'ano_orden_pago, numero_orden_pago, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

																				 if(isset($datos_orden_pago_partidas)){
																				  if($datos_orden_pago_partidas!=null){
																				    foreach($datos_orden_pago_partidas as $ve){




																				$fecha_orden_pago = "";

																				$datos_ordenes = $this->cepd03_ordenpago_cuerpo->findAll($condicion. ' and  ano_orden_pago='.$ve2[0]['ano_orden_pago'].'  and  numero_orden_pago='.$ve2[0]['numero_orden_pago']);
																				$fecha_orden_pago = $datos_ordenes[0]['cepd03_ordenpago_cuerpo']['fecha_orden_pago'];



																				$concate = $this->AddCeroR2(substr($ve['cstd07_retenciones_partidas_islr']['cod_partida'], -2) , substr($ve['cstd07_retenciones_partidas_islr']['cod_partida'], 0, 1 ) ).'.'.$this->AddCeroR2($ve['cstd07_retenciones_partidas_islr']['cod_generica']).'.'.$this->AddCeroR2($ve['cstd07_retenciones_partidas_islr']['cod_especifica']).'.'.$this->AddCeroR2($ve['cstd07_retenciones_partidas_islr']['cod_sub_espec']);


																				  $ano                                 =         $ve['cstd07_retenciones_partidas_islr']['ano'];
																				  $cod_sector                          =         $ve['cstd07_retenciones_partidas_islr']['cod_sector'];
																				  $cod_programa                        =         $ve['cstd07_retenciones_partidas_islr']['cod_programa'];
																				  $cod_sub_prog                        =         $ve['cstd07_retenciones_partidas_islr']['cod_sub_prog'];
																				  $cod_proyecto                        =         $ve['cstd07_retenciones_partidas_islr']['cod_proyecto'];
																				  $cod_activ_obra                      =         $ve['cstd07_retenciones_partidas_islr']['cod_activ_obra'];
																				  $cod_partida                         =         $ve['cstd07_retenciones_partidas_islr']['cod_partida'];
																				  $cod_generica                        =         $ve['cstd07_retenciones_partidas_islr']['cod_generica'];
																				  $cod_especifica                      =         $ve['cstd07_retenciones_partidas_islr']['cod_especifica'];
																				  $cod_sub_espec                       =         $ve['cstd07_retenciones_partidas_islr']['cod_sub_espec'];
																				  $cod_auxiliar                        =         $ve['cstd07_retenciones_partidas_islr']['cod_auxiliar'];
																				  $monto                               =         $this->Formato1($this->data['cstp30_debito_islr']['monto_'.$jj]);
																				  $ano_orden_pago                      =         $ve['cstd07_retenciones_partidas_islr']['ano_orden_pago'];
																				  $numero_orden_pago                   =         $ve['cstd07_retenciones_partidas_islr']['numero_orden_pago'];
																				  $numero_control_compromiso           =         $ve['cstd07_retenciones_partidas_islr']['numero_control_compromiso'];
																				  $numero_control_causado              =         $ve['cstd07_retenciones_partidas_islr']['numero_control_causado'];
																				  $numero_control_pagado               =         $numero_pagado;
																				  $jj++;

																				 $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
																				 $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";


																					$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
																					$to = 1;
																					$td = 5;
																					$ta = 2;
																					$mt = $monto;
																					$ccp = $concepto;
																					$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
																					$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];


																					if($ano_anterior==1){
																						$dnco = 0;
																					}else{
																						$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $numero_debito, null, $numero_orden_pago, $fecha_orden_pago, $cod_entidad_bancaria_aux, $cuenta_bancaria, $numero_debito, $fd, $numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null, $x);
																					}


																				//  cstd30_debito_partidas

																				 $sql_cstd30_debito_partidas = "INSERT INTO cstd30_debito_partidas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito, clase_orden, ano_orden_pago, numero_orden_pago, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, numero_control_compromiso, numero_control_causado, numero_control_pagado) ";
																				 $sql_cstd30_debito_partidas.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$numero_debito."', '".$ve2[0]['clase_orden']."', '".$ve2[0]['ano_orden_pago']."', '".$ve2[0]['numero_orden_pago']."', '".$ano."', '".$cod_sector."', '".$cod_programa."', '".$cod_sub_prog."', '".$cod_proyecto."', '".$cod_activ_obra."', '".$cod_partida."', '".$cod_generica."', '".$cod_especifica."', '".$cod_sub_espec."', '".$cod_auxiliar."', '".$monto."', '".$numero_control_compromiso."', '".$numero_control_causado."', '".$numero_control_pagado."'); ";
																				$x++;
																				if($this->cstd30_debito_partidas->execute($sql_cstd30_debito_partidas)>=1){}else{$opcion = 'no';}//fin else


																				    }//fin
																				  }//fin
																				 }//fin


																				//////////////////////////FIN PARTIDAS /////////////////////////////////////////


																				if($this->cstd07_retenciones_cuerpo_islr->execute("UPDATE cstd07_retenciones_cuerpo_islr SET status=2, ano_movimiento=".$ano_movimiento.", cod_entidad_bancaria = '".$cod_entidad_bancaria."',  cod_sucursal='".$cod_sucursal."', cuenta_bancaria='".$cuenta_bancaria."', numero_cheque=".$numero_debito." WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."'  and ano_orden_pago='".$ve2[0]['ano_orden_pago']."' and numero_orden_pago='".$ve2[0]['numero_orden_pago']."'; ")>=1){}else{$opcion = 'no';}//fin else


									}//fin if
							}//fin if

    }//fin
  }//fin
 }//fin



/////////////////////FIN ORDENES DE PAGO////////////////////////////////










//if($this->cstd03_cheque_numero->execute("UPDATE cstd03_cheque_numero SET situacion=3   WHERE ".$condicion." and  cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and  numero_debito=".$numero_debito."; " )>=1){}else{$opcion = 'no';}//fin else




// cstd02_cuentas_bancarias

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
															      $ano_dc  = null,
															      $n_dc    = null,
															      $f_dc    = null,
															      $cpt_dc  = $concepto,
															      $ben_dc  = $beneficiario,
															      $mon_dc  = array("monto_cheque"=>$monto_debito),

															      $ano_op   = null,
															      $n_op     = null,
															      $f_op     = null,

															      $a_adj_op = null,
															      $n_adj_op = null,
															      $f_adj_op = null,
															      $tp_op    = null,

															      $deno_ban_pago  = $cod_entidad_bancaria_aux,
															      $ano_movimiento = $ano_movimiento,
															      $cod_ent_pago   = $cod_entidad_bancaria,
															      $cod_suc_pago   = $cod_sucursal,
															      $cod_cta_pago   = $cuenta_bancaria,

															      $num_che_o_debi  = $numero_debito,
															      $fec_che_o_debi  = $fd,
															      $clas_che_o_debi = $clase_beneficiario,
															      $tipo_che_o_debi = 2,

															      $ano_dc_array_pago     = null,
															      $n_dc_array_pago       = null,
															      $n_dc_adj_array_pago   = null,
															      $f_dc_array_pago       = null,

															      $ano_op_array_pago  = null,
															      $n_op_array_pago    = null,
															      $f_op_array_pago    = null

															);





					if($valor_motor_contabilidad==true){


				      	  $this->cstd30_debito_cuerpo->execute("COMMIT;");
				      	  $this->set('Message_existe', 'Los datos fueron grabados correctamente');

					}else{

						  $this->cstd30_debito_cuerpo->execute("ROLLBACK;");
			    		  $this->set('errorMessage', 'La datos no fueron almacenados correctamente');

					}//fin else







	}else{$this->cstd30_debito_cuerpo->execute("ROLLBACK;");

		if($mensaje==1){$this->set('errorMessage', 'La nota de debito ya existe en movimientos manuales');}else{$this->set('errorMessage', 'La datos no fueron almacenados');}


		 }//fin else



}else{	$this->cstd30_debito_cuerpo->execute("ROLLBACK;"); $this->set('errorMessage', 'Los datos no pueden ser almacenado verifique el n&uacute;mero de comprobante egreso');  }//fin else
}else{	$this->cstd30_debito_cuerpo->execute("ROLLBACK;"); $this->set('errorMessage', 'Los datos ya existen'); }//fin else


   $this->index();
   $this->render('index');


}//fin guardar










function consulta_index($var1=null){

$this->layout = "ajax";
$this->Session->delete('radio');
//$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
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
			$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_debito=".$pag_num."";



		   $array = $this->cstd30_debito_cuerpo->findAll($cond2." and ano_movimiento='".$ano."'  ");
		  $i = 0;
		   foreach($array as $aux){
		 	$numero[$i]['ano_movimiento']       = $aux['cstd30_debito_cuerpo']['ano_movimiento'];
		 	$numero[$i]['numero_debito']        = $aux['cstd30_debito_cuerpo']['numero_debito'];
		 	$numero[$i]['cod_entidad_bancaria'] = $aux['cstd30_debito_cuerpo']['cod_entidad_bancaria'];
		 	$numero[$i]['cod_sucursal']         = $aux['cstd30_debito_cuerpo']['cod_sucursal'];
		 	$numero[$i]['cuenta_bancaria']      = $aux['cstd30_debito_cuerpo']['cuenta_bancaria'];
		 	$numero[$i]['numero_anulacion']     = $aux['cstd30_debito_cuerpo']['numero_anulacion'];
		 	$i++;
		} $i--;

}//fin


if(isset($numero[0]['numero_debito'])){



$datos_cheque_cuerpo   = $this->cstd30_debito_cuerpo->findAll($condicion. " and  cod_entidad_bancaria='".$numero[0]['cod_entidad_bancaria']."'  and  cod_sucursal='".$numero[0]['cod_sucursal']."' and  cuenta_bancaria='".$numero[0]['cuenta_bancaria']."' and   ano_movimiento=".$numero[0]['ano_movimiento']."  and  numero_debito=".$numero[0]['numero_debito']);
$datos_cheque_ordenes  = $this->cstd30_debito_ordenes->findAll($condicion. " and  cod_entidad_bancaria='".$numero[0]['cod_entidad_bancaria']."'  and  cod_sucursal='".$numero[0]['cod_sucursal']."' and  cuenta_bancaria='".$numero[0]['cuenta_bancaria']."'  and  ano_movimiento=".$numero[0]['ano_movimiento']."  and  numero_debito=".$numero[0]['numero_debito']);
$datos_cheque_partidas = $this->cstd30_debito_partidas->findAll($condicion. " and  cod_entidad_bancaria='".$numero[0]['cod_entidad_bancaria']."'  and  cod_sucursal='".$numero[0]['cod_sucursal']."' and  cuenta_bancaria='".$numero[0]['cuenta_bancaria']."'  and  ano_movimiento=".$numero[0]['ano_movimiento']."  and  numero_debito=".$numero[0]['numero_debito']);



$resultado=$this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cuenta_bancaria='".$numero[0]['cuenta_bancaria']."' ");
$disponibilidad = $resultado[0]["cstd02_cuentas_bancarias"]["disponibilidad_libro"];

$c=$this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($numero[0]['cod_entidad_bancaria'] );
$denominacion_a = $c["cstd01_entidades_bancarias"]["denominacion"];

$b=$this->cstd01_sucursales_bancarias->findByCod_sucursal($numero[0]['cod_sucursal'] );
$denominacion_b = $b["cstd01_sucursales_bancarias"]["denominacion"];

$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($condicion." and numero_acta_anulacion=".$numero[0]['numero_anulacion']." and ano_acta_anulacion=".$numero[0]['ano_movimiento']);
   if($C_A!=null){
          $this->set("concepto_anulacion",$C_A[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"]);
    }else{
          $this->set("concepto_anulacion","");
    }//fin else


    	   $cond2   =  $this->SQLCA()."  and ano_movimiento='".$numero[0]['ano_movimiento']."' and cod_entidad_bancaria=".$numero[0]['cod_entidad_bancaria']." and cod_sucursal=".$numero[0]['cod_sucursal']." and cuenta_bancaria='".$numero[0]['cuenta_bancaria']."' and clase_beneficiario=2 ";
           $lista   =  $this->cstd30_debito_cuerpo->generateList($cond2, 'numero_debito ASC', null, '{n}.cstd30_debito_cuerpo.numero_debito', '{n}.cstd30_debito_cuerpo.numero_debito');
$this->set('lista', $lista);


$this->set('disponibilidad' , $disponibilidad);
$this->set('denominacion_a' , $denominacion_a);
$this->set('denominacion_b' , $denominacion_b);



$this->set('datos_cheque_cuerpo' , $datos_cheque_cuerpo);
$this->set('datos_cheque_ordenes', $datos_cheque_ordenes);
$this->set('datos_cheque_partidas', $datos_cheque_partidas);


$datos_orden_pago_cuerpo = $this->cepd03_ordenpago_cuerpo->findAll($condicion." and ano_orden_pago='".$numero[0]['ano_movimiento']."' ");
$this->set('datos_orden_pago_cuerpo', $datos_orden_pago_cuerpo);


 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);

}else{
 $this->consulta_index();
 $this->render("consulta_index");


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
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


	if(isset($this->data["cstp30_debito_islr"])){


		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		      $tipo_documento           =  251;
			  $concepto_anulacion       =  $this->data["cstp30_debito_islr"]["concepto_anulacion"];
			  $fecha_proceso_anulacion  =  date("d/m/Y");
			  $condicion_documento      =  2;//cuando se guarda es Activo=1
			  $fecha_debito            =   $this->data["cstp30_debito_islr"]["fecha_debito"];
			  $fd = $fecha_debito;
			  $cod_sucursal            =   $this->data["cstp30_debito_islr"]["cod_sucursal"];
			  $ano_movimiento          =   $this->data["cstp30_debito_islr"]["ano_movimiento"];
			  $cuenta_bancaria         =   $this->data["cstp30_debito_islr"]["cuenta_bancaria"];
			  $numero_debito           =   $this->data["cstp30_debito_islr"]["numero_debito"];
			  $cod_entidad_bancaria    =   $this->data["cstp30_debito_islr"]["cod_entidad_bancaria"];



   $array = $this->cstd30_debito_cuerpo->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."' and numero_debito=".$numero_debito);
   foreach($array as $aux){$monto_para_actualizar_en_cuenta   = $aux['cstd30_debito_cuerpo']['monto'];}

 $year = $this->ano_ejecucion();
  $ano = null;





$datos_partidas = $this->cstd30_debito_partidas->findAll($conditions = $this->condicion()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_debito='$numero_debito'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
$sql_3="";
foreach($datos_partidas as $aux_cstd30_debito_partidas){
  $ano_orden_pago      =    $aux_cstd30_debito_partidas['cstd30_debito_partidas']['ano_orden_pago'];
  $numero_orden_pago   =    $aux_cstd30_debito_partidas['cstd30_debito_partidas']['numero_orden_pago'];
if($sql_3==""){$sql_3   .= " and  ano_orden_pago='".$ano_orden_pago."'   and  numero_orden_pago='".$numero_orden_pago."'   ";
         }else{$sql_3   .= " or  (ano_orden_pago='".$ano_orden_pago."'    and  numero_orden_pago='".$numero_orden_pago."')  ";}

if($this->cstd07_retenciones_cuerpo_islr->execute("UPDATE cstd07_retenciones_cuerpo_islr SET status=3, ano_movimiento=0, cod_entidad_bancaria = '0',  cod_sucursal='0', cuenta_bancaria='0', numero_cheque=0  WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."'  and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' ")>=1){}else{$opcion = 'no';}//fin else

}//FIN FOR

$datos_ordenes = $this->cepd03_ordenpago_cuerpo->findAll($conditions = $this->condicion().$sql_3, $fields = null, $order = null, $limit = null, $page = null, $recursive = null);


/*
foreach($datos_ordenes as $row22){if($this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET  cod_entidad_bancaria = '0',  cod_sucursal='0', cuenta_bancaria='0', numero_debito='0', fecha_debito='0'      WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."'  and ano_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['ano_orden_pago']."' and numero_orden_pago='".$row22['cepd03_ordenpago_cuerpo']['numero_orden_pago']."' ")>=1){}else{$opcion = 'no';}//fin else}//fin foreach
*/




$sql_update_cscd04_partidas ='';
foreach($datos_partidas as $row){
										 	$ano = $row['cstd30_debito_partidas']['ano'];
										 	$cod_sector = $row['cstd30_debito_partidas']['cod_sector'];
										 	$cod_programa = $row['cstd30_debito_partidas']['cod_programa'];
										 	$cod_sub_prog = $row['cstd30_debito_partidas']['cod_sub_prog'];
										 	$cod_proyecto = $row['cstd30_debito_partidas']['cod_proyecto'];
										 	$cod_activ_obra = $row['cstd30_debito_partidas']['cod_activ_obra'];
										 	$cod_partida = $row['cstd30_debito_partidas']['cod_partida'];
										 	$cod_generica = $row['cstd30_debito_partidas']['cod_generica'];
										 	$cod_especifica = $row['cstd30_debito_partidas']['cod_especifica'];
										 	$cod_sub_espec = $row['cstd30_debito_partidas']['cod_sub_espec'];
										 	$cod_auxiliar = $row['cstd30_debito_partidas']['cod_auxiliar'];
										 	$monto_partida = $row['cstd30_debito_partidas']['monto'];
										 	$numero_control_compromiso = $row['cstd30_debito_partidas']['numero_control_compromiso'];
										 	$numero_control_causado = $row['cstd30_debito_partidas']['numero_control_causado'];
										 	$numero_control_pagado = $row['cstd30_debito_partidas']['numero_control_pagado'];
										 	$opago = $row['cstd30_debito_partidas']['numero_orden_pago'];
										 	$cbanco = $row['cstd30_debito_partidas']['cod_entidad_bancaria'];
										 	$ccuenta = $row['cstd30_debito_partidas']['cuenta_bancaria'];
										 	$ccheque = $numero_debito;
										  //$cond1 = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";


							$fecha_orden_pago = "";

							foreach($datos_ordenes as $row2){
							 if($row2['cepd03_ordenpago_cuerpo']['ano_orden_pago'] == $row['cstd30_debito_partidas']['ano_orden_pago']  &&  $row2['cepd03_ordenpago_cuerpo']['numero_orden_pago'] == $row['cstd30_debito_partidas']['numero_orden_pago']){
							     $fecha_orden_pago = $row2['cepd03_ordenpago_cuerpo']['fecha_orden_pago'];
							  }//fin
							}//fin foreach


							$ano_orden_pago_1     =  $row['cstd30_debito_partidas']['ano_orden_pago'];
                            $numero_orden_pago_1  =  $row['cstd30_debito_partidas']['numero_orden_pago'];
                            $sql_actual = $this->condicion()." and ano_orden_pago='".$ano_orden_pago_1."' and numero_orden_pago='".$numero_orden_pago_1."' ";
                            $resul = $this->cstd07_retenciones_cuerpo_islr->findAll($sql_actual);
                            $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'];




											//$monto_cancelado += $monto_partida;
											$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

											if($ano_anterior==1){
												$num_asiento_compromiso = 0;
											}else{
												$c      = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cbanco);
										        $cbanco = $c["cstd01_entidades_bancarias"]["denominacion"];

												$num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 5, 2, date("d/m/Y"), $monto_partida, $concepto_anulacion, $ano, $numero_debito, null, $opago, $fecha_orden_pago, $cbanco, $ccuenta, $ccheque, $fd, $numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null, null);
											}

										 	//$sql_update_cscd04_partidas .= "UPDATE cscd04_ordencompra_partidas SET cancelado=cancelado-'$monto_partida' WHERE ".$cond1.";";
}//fin foreach






$clase_beneficiario       =    2;
$c                        = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

$monto_debito    =   $this->data["cstp30_debito_islr"]["monto_debito"];
$beneficiario    =   $this->data["cstp30_debito_islr"]["beneficiario"];
$rif_cedula      =   $this->data["cstp30_debito_islr"]["rif_cedula"];


$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(

															      $to      = 2,
															      $td      = 4,
															      $rif_doc = $rif_cedula,
															      $ano_dc  = null,
															      $n_dc    = null,
															      $f_dc    = null,
															      $cpt_dc  = $concepto_anulacion,
															      $ben_dc  = $beneficiario,
															      $mon_dc  = array("monto_cheque"=>$monto_debito),

															      $ano_op   = null,
															      $n_op     = null,
															      $f_op     = null,

															      $a_adj_op = null,
															      $n_adj_op = null,
															      $f_adj_op = null,
															      $tp_op    = null,

															      $deno_ban_pago  = $cod_entidad_bancaria_aux,
															      $ano_movimiento = $ano_movimiento,
															      $cod_ent_pago   = $cod_entidad_bancaria,
															      $cod_suc_pago   = $cod_sucursal,
															      $cod_cta_pago   = $cuenta_bancaria,

															      $num_che_o_debi  = $numero_debito,
															      $fec_che_o_debi  = $fecha_proceso_anulacion,
															      $clas_che_o_debi = $clase_beneficiario,
															      $tipo_che_o_debi = 2,

															      $ano_dc_array_pago     = null,
															      $n_dc_array_pago       = null,
															      $n_dc_adj_array_pago   = null,
															      $f_dc_array_pago       = null,

															      $ano_op_array_pago  = null,
															      $n_op_array_pago    = null,
															      $f_op_array_pago    = null

															);















			//$cond2 = $this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra'";
			//$sql_update_cscd04_encabezado ="UPDATE cscd04_ordencompra_encabezado SET monto_cancelado=monto_cancelado-'$monto_cancelado' WHERE ".$cond2.";";

			//$sw = $this->cscd04_ordencompra_encabezado->execute($sql_update_cscd04_partidas.$sql_update_cscd04_encabezado);


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
			 $R1 = $this->cstd30_debito_cuerpo->execute("UPDATE cstd30_debito_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.",  condicion_actividad=".$condicion_documento.",  fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'       WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_debito='".$numero_debito."' ");
             $v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_movimiento.",".$numero.",".$tipo_documento.",".$ano_movimiento.",".$numero_debito.",'".$this->Cfecha($fecha_debito, 'A-M-D')."','".$concepto_anulacion."')");


      $sql="update cstd03_movimientos_manuales set ano_anulacion='".date("Y")."', numero_anulacion='".$numero."', fecha_proceso_anulacion='".$fecha_proceso_anulacion."', dia_asiento_anulacion=0, mes_asiento_anulacion=0, ano_asiento_anulacion=0, numero_asiento_anulacion='0', username_anulacion='".$_SESSION['nom_usuario']."', condicion_actividad='2'  where ".$this->SQLCA()." and ano_movimiento='".$ano_movimiento."' and cod_entidad_bancaria='".$cod_entidad_bancaria."' and cod_sucursal='".$cod_sucursal."' and cuenta_bancaria='".$cuenta_bancaria."' and tipo_documento='3' and numero_documento='".$numero_debito."'";
   if($this->cstd03_movimientos_manuales->execute($sql)>0){}

}//fin if





/////////////////////////////////////////////////RETROCEDER CAMBIOS////////////////////////////////////////////////////








///////////////////////////////////////////////FIN--RETROCEDER----CAMBIOS////////////////////////////////////////////////////

$this->set('Message_existe', 'El registro fue anulado');


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







function editar_monto($var=null){

  $this->layout="ajax";


echo'<script>';
  echo"document.getElementById('monto_".$var."').readOnly = false; ";
  echo"document.getElementById('monto_".$var."').focus(); ";
  echo"document.getElementById('monto_".$var."').style.background='#ffffca'; ";
echo'</script>';


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
  $ano = $this->ano_ejecucion();


            $cod_1   =  $this->Session->read('cod1');
			$cod_2   =  $this->Session->read('cod2');
			$ano     =  $this->Session->read('cod4');
			$cond    =  $this->SQLCA();
			$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";




if(!isset($_SESSION['opcion_emitir'])){$_SESSION['opcion_emitir']=1;}

$dato = $this->cepd03_ordenpago_cuerpo->execute("


select


	  a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano_orden_pago,
	  a.clase_orden,
	  a.numero_orden_pago,
	  a.monto                  as   monto_cuerpo_retencion,
	  a.fecha_proceso_registro,
	  a.status,
	  a.ano_movimiento,
	  a.cod_entidad_bancaria,
	  a.cod_sucursal,
	  a.cuenta_bancaria ,
	  a.numero_cheque,
	  a.fecha_proceso_anulacion,
      b.ano,
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
	  b.monto,
	  b.numero_control_compromiso,
	  b.numero_control_causado,
	  b.numero_control_pagado,
      c.ano_movimiento             as    ano_movimiento_cheque_beneficiario,
	  c.cod_entidad_bancaria       as    cod_entidad_bancaria_cheque_beneficiario,
	  c.cod_sucursal               as    cod_sucursal_cheque_beneficiario,
	  c.cuenta_bancaria            as    cuenta_bancaria_cheque_beneficiario,
	  c.numero_cheque              as    numero_cheque_cheque_beneficiario,
	  c.fecha_cheque               as    fecha_cheque_cheque_beneficiario,
	  c.cod_tipo_documento,
      c.rif,
      c.beneficiario,
      c.autorizado,
      c.cedula_identidad,
      c.concepto,
      c.cod_tipo_pago




FROM


         cstd07_retenciones_cuerpo_islr a, cstd07_retenciones_partidas_islr b,  cepd03_ordenpago_cuerpo c


WHERE

        a.cod_presi            =     ".$cod_presi."         and
        a.cod_entidad          =     ".$cod_entidad."       and
        a.cod_tipo_inst        =     ".$cod_tipo_inst."     and
        a.cod_inst             =     ".$cod_inst."          and
        a.cod_dep              =     ".$cod_dep."           and
        c.ano_movimiento       =     ".$ano."               and
	    c.cod_entidad_bancaria =      ".$cod_1."            and
	    c.cod_sucursal         =      ".$cod_2."            and
	    c.cuenta_bancaria      =      '".$var."'            and
        b.cod_presi            =      a.cod_presi           and
        b.cod_entidad          =      a.cod_entidad         and
        b.cod_tipo_inst        =      a.cod_tipo_inst       and
        b.cod_inst             =      a.cod_inst            and
        b.cod_dep              =      a.cod_dep             and
        b.ano_orden_pago       =      a.ano_orden_pago      and
        b.numero_orden_pago    =      a.numero_orden_pago   and
        c.cod_presi            =      a.cod_presi           and
        c.cod_entidad          =      a.cod_entidad         and
        c.cod_tipo_inst        =      a.cod_tipo_inst       and
        c.cod_inst             =      a.cod_inst            and
        c.cod_dep              =      a.cod_dep             and
        c.ano_orden_pago       =      a.ano_orden_pago      and
        c.numero_orden_pago    =      a.numero_orden_pago   and
        a.ano_movimiento       =      0                     and
	    a.cod_entidad_bancaria =      0                     and
	    a.cod_sucursal         =      0                     and
	    a.cuenta_bancaria      =     '0'                    and
	    a.status               =      ".$_SESSION['opcion_emitir']."


ORDER BY

           ano_orden_pago,
           numero_orden_pago,
           cod_sector,
           cod_programa,
           cod_sub_prog,
           cod_proyecto,
           cod_activ_obra,
           cod_partida,
           cod_generica,
           cod_especifica,
           cod_sub_espec,
           cod_auxiliar ASC; ");



 if(isset($dato[0][0]['ano_orden_pago'])){
    $this->set('datos',   $dato);
 }//fin if







}//fin funtion








function agregar_orden_pago_session($var=null){

  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();


            $cod_1   =  $this->Session->read('cod1');
			$cod_2   =  $this->Session->read('cod2');
			$ano     =  $this->Session->read('cod4');
			$cond    =  $this->SQLCA();
			$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";

  if(!isset($_SESSION['opcion_emitir'])){$_SESSION['opcion_emitir']=1;}




   $beneficiario = "";
   $beneficiario = $this->cstd03_beneficiario_retencion_islr->findAll($this->SQLCA());



if(!isset($beneficiario[0]['cstd03_beneficiario_retencion_islr']['beneficiario'])){
  echo'<script>';echo"document.getElementById('guardar').disabled = true; ";echo'</script>';
  $this->set('errorMessage', 'No existe beneficiario de la retención');
  $_SESSION['ORDEN_PAGO_TOTAL']['autorizado'] = "";
  $this->set('beneficiario', "<br>" );
}else{



$dato = $this->cepd03_ordenpago_cuerpo->execute("


select


	  a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano_orden_pago,
	  a.clase_orden,
	  a.numero_orden_pago,
	  a.monto                 as   monto_cuerpo_retencion,
      c.beneficiario,
      c.autorizado,
      c.cod_tipo_pago




FROM


         cstd07_retenciones_cuerpo_islr a, cstd07_retenciones_partidas_islr b,  cepd03_ordenpago_cuerpo c


WHERE

        a.cod_presi            =     ".$cod_presi."         and
        a.cod_entidad          =     ".$cod_entidad."       and
        a.cod_tipo_inst        =     ".$cod_tipo_inst."     and
        a.cod_inst             =     ".$cod_inst."          and
        a.cod_dep              =     ".$cod_dep."           and
        c.ano_movimiento       =     ".$ano."               and
	    c.cod_entidad_bancaria =      ".$cod_1."            and
	    c.cod_sucursal         =      ".$cod_2."            and
	    c.cuenta_bancaria      =      '".$var."'            and
        b.cod_presi            =      a.cod_presi           and
        b.cod_entidad          =      a.cod_entidad         and
        b.cod_tipo_inst        =      a.cod_tipo_inst       and
        b.cod_inst             =      a.cod_inst            and
        b.cod_dep              =      a.cod_dep             and
        b.ano_orden_pago       =      a.ano_orden_pago      and
        b.numero_orden_pago    =      a.numero_orden_pago   and
        c.cod_presi            =      a.cod_presi           and
        c.cod_entidad          =      a.cod_entidad         and
        c.cod_tipo_inst        =      a.cod_tipo_inst       and
        c.cod_inst             =      a.cod_inst            and
        c.cod_dep              =      a.cod_dep             and
        c.ano_orden_pago       =      a.ano_orden_pago      and
        c.numero_orden_pago    =      a.numero_orden_pago   and
        a.ano_movimiento       =      0                     and
	    a.cod_entidad_bancaria =      0                     and
	    a.cod_sucursal         =      0                     and
	    a.cuenta_bancaria      =     '0'                    and
	    a.status               =      ".$_SESSION['opcion_emitir']."



 GROUP BY

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano_orden_pago,
	  a.clase_orden,
	  a.numero_orden_pago,
	  monto_cuerpo_retencion,
      c.beneficiario,
      c.autorizado,
      c.cod_tipo_pago

ORDER BY

           a.ano_orden_pago,
           a.numero_orden_pago ASC

           	 ");



 if(isset($dato[0][0]['ano_orden_pago'])){
    $this->set('datos',   $dato);
 }//fin if


	if(!isset($dato[0][0]['numero_orden_pago'])){
		   echo'<script>';echo"document.getElementById('guardar').disabled = true; ";echo'</script>';
           $this->set('errorMessage', 'No existen Ordenes para esta Cuenta');
	}else{
		   echo'<script>';echo"document.getElementById('guardar').disabled = false; ";echo'</script>';
		   $this->set('beneficiario',   $beneficiario[0]['cstd03_beneficiario_retencion_islr']['beneficiario']);
		   $_SESSION['ORDEN_PAGO_TOTAL']['autorizado'] = $beneficiario[0]['cstd03_beneficiario_retencion_islr']['beneficiario'];
	}//fin else
}//fin else


$campo_rif = "";
$resul =  $this->cugd02_dependencia->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep." ");
$_SESSION['ORDEN_PAGO_TOTAL']['rif'] = $resul[0]['cugd02_dependencia']['rif'];
$_SESSION['ORDEN_PAGO_TOTAL']['cod_tipo_pago'] = $dato[0][0]['cod_tipo_pago'];

	if($_SESSION['ORDEN_PAGO_TOTAL']['rif']=='0'){
		$this->set('errorMessage', 'FAVOR REGISTRAR EL RIF DE LA INSTITUCIÓN');
	}


}//fin function

















function eliminar_session($var1=null){

	$this->layout="ajax";

     $_SESSION['ORDEN_PAGO']['ORDEN_PAGO_'.$var1]['DATOS_ORDEN_PAGO']['usar'] = "no";
     $this->set('errorMessage', 'La Orden de pago fue eliminada de la lista');


}//fin function





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
