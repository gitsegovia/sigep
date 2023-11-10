<?php

class Cnmp99ReversoOrdenesPagoController extends AppController {
   var $name = 'cnmp99_reverso_ordenes_pago';

   var $uses=array('v_cepd02_contratoservi_retencion','v_cobd01_contratoobras_retencion','cobd01_contratoobras_retencion_cuerpo',
                   'cobd01_contratoobras_retencion_partidas','cscd04_ordencompra_autorizacion_cuerpo','cepd02_contratoservicio_retencion_cuerpo',
                   'cepd02_contratoservicio_retencion_partidas','v_cepd02_contratoservi_valuacion','cepd02_contratoservicio_valuacion_cuerpo',
                   'cepd02_contratoservicio_valuacion_partidas','v_cobd01_contratoobras_valuacion','cobd01_contratoobras_valuacion_partidas',
                   'cobd01_contratoobras_valuacion_cuerpo','cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas',
                   'v_cepd02_servicio_anticipo','cobd01_contratoobras_anticipo_partidas','cobd01_contratoobras_anticipo_cuerpo',
                   'v_cobd01_contratoobras_anticipo','cscd04_ordencompra_a_pago_partidas','cscd04_ordencompra_autorizacion_cuerpo',
                   'cugd03_acta_anulacion_cuerpo','cugd03_acta_anulacion_numero','cugd04','cfpd21','cfpd22_numero_asiento_causado','cfpd22','cfpd05',
                   'ccfd04_cierre_mes','cscd04_ordencompra_autorizacion_pago_partidas','v_cepd03_ordenpago_autorizacion_compra',
                   'cscd04_ordencompra_autorizacion_cuerpo','cepd03_ordenpago_poremitir','v_cepd03_ordenpago_anticipo_compra',
                   'v_cepd03_ordenpago_compromiso','cscd04_ordencompra_anticipo_cuerpo','cscd04_ordencompra_anticipo_partidas',
                   'cscd04_ordencompra_encabezado','cugd03_acta_anulacion_cuerpo','cpcd02','cscd04_ordencompra_parametros',
                   'cepd01_compromiso_cuerpo','cepd01_compromiso_partidas','cepd01_tipo_compromiso','cepd03_tipo_documento','ccfd03_instalacion',
                   'cepd03_ordenpago_numero','cepd03_ordenpago_cuerpo','cepd03_ordenpago_partidas','cepd03_ordenpago_tipopago',
                   'cepd03_ordenpago_facturas','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','cstd07_retenciones_cuerpo_multa', 'cstd07_retenciones_cuerpo_responsabilidad', 'cstd07_retenciones_partidas_multa',
                   'cstd07_retenciones_partidas_responsabilidad','cstd06_comprobante_poremitir_multa',
                   'cstd06_comprobante_poremitir_responsabilidad', 'cstd06_comprobante_numero_multa',
                   'cstd06_comprobante_numero_responsabilidad', 'cstd06_comprobante_cuerpo_multa', 'cstd06_comprobante_cuerpo_responsabilidad',
                   'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                   'ccfd04_cuentas_enlace', 'cpcd02', 'cscd04_ordencompra_anticipo_cuerpo',
                   'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo','cepd01_compromiso_numero',
				   'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
				   'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
				   'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo','cugd05_restriccion_clave',
				   'Cnmd01', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cnmd06_fichas');


	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
	if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
	}else{
		$this->requestAction('/usuarios/actualizar_user');
	}
}//fin checksession

function beforeFilter(){
	$this->checkSession();
	if($this->ano_ejecucion()!=""){
		return;
	}else{
		echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
		exit();
	}
}

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


function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  return $condicion;
}


function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector != null){
			if($extra==null){
			foreach($vector as $x){
				if($x<10){
				$Var[$x]="0".$x;
				}else{
				$Var[$x]=$x;
				}
			}//fin each
		}else{
			foreach($vector as $x){
				if($x<10){
				$Var[$x]=$extra.".0".$x;
				}else{
				$Var[$x]=$extra.".".$x;
				}
			}//fin each
		}
		$this->set($nomVar,$Var);
   	  }else{
   	  	$this->set($nomVar,'');
   	  }
}//fin AddCero

function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}


function index () {

$this->verifica_entrada('111');

    $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=4", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=4")!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin index_reverso_corrida


function deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and status_nomina=4 and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago');
		if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=4 and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->set( 'nomina',$lista);

		}else{
			$this->set('nomina', array());
		}
	}
}


function salir_prenomina ($numero=null) {
       $this->layout="ajax";
       $this->Session->delete("autor_valido");
}//fin salir_prenomina

function seleccion_nomina () {
    $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=4", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=4")!=0){
		$this->concatena($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin seleccion_nomina



function entrar_reverso(){
	$this->layout="ajax";
	if(isset($this->data['cnmp99_reverso_ordenes_pago']['login']) && isset($this->data['cnmp99_reverso_ordenes_pago']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp99_reverso_ordenes_pago']['login']);
		$paswd=addslashes($this->data['cnmp99_reverso_ordenes_pago']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=111 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


function deno_nomina_reverso_corrida ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and status_nomina=4 and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago');
		if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=4 and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->set( 'nomina',$lista);

		}else{
			$this->set('nomina', array());
		}
	}
	$cuenta_pendiente=$this->cepd01_compromiso_cuerpo->execute("SELECT count(*) as cuenta FROM cnmd99_orden_pago_prenomina WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina."");
    $cuenta=$cuenta_pendiente[0][0]["cuenta"];
  if($cuenta==0){
  	$this->set('errorMessage', 'N&Oacute;MINA NO TIENE ORDEN DE PAGO');
		echo "<script>
				document.getElementById('procesar').disabled=true;
			</script>";
}

}// fin funciondeno_nomina_reverso_corrida


function seleccion_nomina_reverso_corrida () {
    $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=4", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=4")!=0){
		$this->concatena($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin seleccion_nomina



function procesar ($cod_tipo_nomina=null, $numero_nomina=null) {
$this->layout = "ajax";
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$ano=$this->ano_ejecucion();
 $this->set('errorMessage', 'N&Oacute;MINA NO TIENE ORDEN DE PAGO');
if($cod_tipo_nomina != null && $numero_nomina != null){

}else{
	$cod_tipo_nomina = $this->data['cnmp99_prenomina']['cod_tipo_nomina'];
	$numero_nomina = $this->data['cnmp99_prenomina']['numero_nomina'];
}

$cuenta_pendiente=$this->cepd01_compromiso_cuerpo->execute("SELECT count(*) as cuenta FROM cnmd99_orden_pago_prenomina WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina."");
$cuenta=$cuenta_pendiente[0][0]["cuenta"];
if($cuenta!=0){
		$cuenta=$cuenta_pendiente[0][0]["cuenta"]*2;

		$nro=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano_acta_anulacion=".$ano."");
		if($nro!=null){
		$numero=$nro[0][0]["numero_acta_anulacion"];
		}else{
		$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$ano.",1)");
		$numero=0;
		}

		$numero_cuenta=($numero+$cuenta)+2;
		$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero_cuenta." WHERE cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano_acta_anulacion=".$ano."");


// REVERSO - CAUSADOS
			 $orden_pagado_tmp=$this->cepd01_compromiso_cuerpo->execute("SELECT ano_orden_pago, numero_orden_pago FROM cnmd99_orden_pago_prenomina WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." ORDER BY ano_documento, numero_documento ASC");
		     if($orden_pagado_tmp!=null){
		     	foreach($orden_pagado_tmp as $orden_tmp){
				$ano_docu=$orden_tmp[0]['ano_orden_pago'];
				$nume_docu=$orden_tmp[0]['numero_orden_pago'];
					$orden_pagado=$this->cepd01_compromiso_cuerpo->execute("SELECT * FROM cepd03_ordenpago_cuerpo WHERE ".$this->condicion()." and ano_orden_pago=".$ano_docu." and numero_orden_pago=".$nume_docu." ORDER BY ano_orden_pago, numero_orden_pago ASC");
					if($orden_pagado!=null){
						foreach($orden_pagado as $orden_paga){
							$cod_presi = $orden_paga[0]['cod_presi'];
							$cod_entidad = $orden_paga[0]['cod_entidad'];
							$cod_tipo_inst = $orden_paga[0]['cod_tipo_inst'];
							$cod_inst = $orden_paga[0]['cod_inst'];
							$cod_dep = $orden_paga[0]['cod_dep'];
							$ano_orden_pago = $orden_paga[0]['ano_orden_pago'];
							$numero_orden_pago = $orden_paga[0]['numero_orden_pago'];
							$fecha_orden = $orden_paga[0]['fecha_orden_pago'];
							$rif = strtoupper($orden_paga[0]['rif']);
							$ano_documento = $orden_paga[0]['ano_documento_origen'];
							$numero_documento = $orden_paga[0]['numero_documento_origen'];
							$fecha_documento = $orden_paga[0]['fecha_documento'];
							$cedula = $orden_paga[0]['cedula_identidad'];
							$concepto = $orden_paga[0]['concepto'];
							$beneficiario = $orden_paga[0]['beneficiario'];
							$monto_total_orden_contabilidad = $orden_paga[0]['monto_total'];
							$monto_orden_pago_contabilidad = $orden_paga[0]['monto_orden_pago'];
							$monto_amortizacion_contabilidad = $orden_paga[0]['amortizacion_anticipo'];
							$numero_doc_adjunto = $orden_paga[0]['numero_documento_adjunto'];
							$fecha_doc_adjunto = $orden_paga[0]['numero_documento_adjunto'];
							$tipo_orden_pago = $orden_paga[0]['tipo_orden'];
							$num_asiento=0;
							$m_fdo     = date("d/m/Y");
							$ano=$ano_orden_pago;
							$ndo=$numero_documento;
							$nda=0; //documento adjunto
							$nrc=$numero_documento;
							$opago=$numero_orden_pago;
							$m_opfecha=$fecha_documento;
							$numero++;
							$concepto_anulacion="BENEFICIARIO: ".$beneficiario." ANULADO POR: REVERSO DE NOMINA";
							if ($rif==null || $rif=='0'){$rif=$cedula;}


                         $tipo_documento=241;
						 $condicion_documento=2;

					        $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
						    if($v>1){
						    	$R1=$this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->condicion()." and ano_documento=".$ano." and numero_documento=".$nrc);
                                $Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='AUTO-NOMINA' WHERE ".$this->condicion()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						        if($R1>1){
			                        $R1_a=$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->condicion()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						        }
						    }

			                   $msj=isset($R1) && $R1>1?true:false;
			                   if($msj==true){
			                   	    $partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($conditions = $this->condicion()." and ano=".$ano." and numero_orden_pago=".$opago, $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			                        foreach($partidas_compromiso as $vec){
								     	       $cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
											   $to   = 2;
											   $td   = 4;
											   $ta   = 1;
											   $rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
											   $rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];
											   $mt   = $vec["cepd03_ordenpago_partidas"]["monto"];
											   $ccp  = $concepto_anulacion;

											   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);

								     }//fin foreach
			                   }


                          $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
	                                                                                     $to              = 2,
	                                                                                     $td              = 9,
	                                                                                     $rif_doc         = $rif,
	                                                                                     $ano_dc          = $ano_documento,
	                                                                                     $n_dc            = $numero_documento,
	                                                                                     $f_dc            = cambiar_formato_fecha($fecha_documento),
	                                                                                     $cpt_dc          = $concepto,
	                                                                                     $ben_dc          = $beneficiario,
	                                                                                     $mon_dc          = array("monto_total_orden"  => $monto_total_orden_contabilidad,
                                                                                                                  "monto_orden_pago"   => $monto_orden_pago_contabilidad,
                                                                                                                  "monto_amortizacion" => $monto_amortizacion_contabilidad
                                                                                                                 ),

	                                                                                     $ano_op          = $ano_orden_pago,
	                                                                                     $n_op            = $numero_orden_pago,
	                                                                                     $f_op            = cambiar_formato_fecha($fecha_orden),

	                                                                                     $a_adj_op        = null,
	                                                                                     $n_adj_op        = null,
	                                                                                     $f_adj_op        = null,
	                                                                                     $tp_op           = 1,

	                                                                                     $deno_ban_pago   = null,
	                                                                                     $ano_movimiento  = null,
	                                                                                     $cod_ent_pago    = null,
	                                                                                     $cod_suc_pago    = null,
	                                                                                     $cod_cta_pago    = null,

	                                                                                     $num_che_o_debi  = null,
	                                                                                     $fec_che_o_debi  = null,
	                                                                                     $clas_che_o_debi = null,
	                                                                                     $tipo_che_o_debi = null,
	                                                                                     $ano_dc_array_pago    = array(),
                                                                                         $n_dc_array_pago      = array(),
                                                                                         $n_dc_adj_array_pago  = array(),
                                                                                         $f_dc_array_pago      = array(),

                                                                                         $ano_op_array_pago  = array(),
                                                                                         $n_op_array_pago    = array(),
                                                                                         $f_op_array_pago    = array(),
                                                                                         $tipo_op_array_pago = array(),
                                                                                         $tipo_modificacion  = null,
                                                                                         $f_dc_adj_array_pago= array(),
                                                                                         $parametro_extras_1   = array(),
                                                                                         $parametro_extras_2   = array()
	                                                                                     );


						}// foreach cepd03_ordenpago_cuerpo

				}// lectura cepd03_ordenpago_cuerpo

		}// foreach cnmd99_orden_pago_prenomina

}// FIN REVERSO - CAUSADOS




// REVERSO - COMPROMISOS

			 $otros_compro_tmp=$this->cugd03_acta_anulacion_numero->execute("SELECT ano_documento, numero_documento FROM cnmd99_orden_pago_prenomina WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." ORDER BY ano_documento, numero_documento ASC");
		     if($otros_compro_tmp!=null){
		     	foreach($otros_compro_tmp as $compro_tmp){
				$ano_docu=$compro_tmp[0]['ano_documento']; $this->set('errorMessage', 'N&Oacute;MINA NO TIENE ORDEN DE PAGO');
				$nume_docu=$compro_tmp[0]['numero_documento'];
					$otros_compromisos=$this->cugd03_acta_anulacion_numero->execute("SELECT * FROM cepd01_compromiso_cuerpo WHERE ".$this->condicion()." and ano_documento=".$ano_docu." and numero_documento=".$nume_docu." ORDER BY ano_documento, numero_documento ASC");
					if($otros_compromisos!=null){
						foreach($otros_compromisos as $otros_compro){
							$ano = $otros_compro[0]['ano_documento'];
							$numero_documento = $otros_compro[0]['numero_documento'];
							$fecha_documento = $otros_compro[0]['fecha_documento'];
							$rif = strtoupper($otros_compro[0]['rif']);
							$cedula = $otros_compro[0]['cedula_identidad'];
							$concepto = $otros_compro[0]['concepto'];
							$monto = $otros_compro[0]['monto'];
							$beneficiario = $otros_compro[0]['beneficiario'];
							$num_asiento=0;
							$numero++;
							$concepto_anulacion="BENEFICIARIO: ".$beneficiario." ANULADO POR: REVERSO DE NOMINA";
							if ($rif==null || $rif=='0'){$rif=$cedula;}


			 $condicion_documento=2;//cuando se guarda es Activo=1
			 $tipo_documento=231;
		     $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$ano.",".$numero.",".$tipo_documento.",".$ano.",".$numero_documento.",'".date("Y-m-d")."','".$concepto_anulacion."')");
			    if($v>1){
			    	$R1=$this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo SET  ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.", fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='AUTO-NOMINA' WHERE ".$this->condicion()." and ano_documento=".$ano." and numero_documento=".$numero_documento);
			        if($R1>1){
                        $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=4 WHERE ".$this->condicion()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento);
			        }
			    }
               $msj=isset($R1) && $R1>1?true:false; $this->set('errorMessage', 'N&Oacute;MINA NO TIENE ORDEN DE PAGO');

                   if($msj==true){
                   	    $partidas_compromiso = $this->cepd01_compromiso_partidas->findAll($conditions = $this->condicion()." and ano=".$ano." and numero_documento=".$numero_documento, $fields = null, $order = null, $limit = null, $page = null, $recursive = null);

                        foreach($partidas_compromiso as $vec){
					     	       $cp   = $this->crear_partida($vec["cepd01_compromiso_partidas"]["ano"], $vec["cepd01_compromiso_partidas"]["cod_sector"], $vec["cepd01_compromiso_partidas"]["cod_programa"], $vec["cepd01_compromiso_partidas"]["cod_sub_prog"], $vec["cepd01_compromiso_partidas"]["cod_proyecto"], $vec["cepd01_compromiso_partidas"]["cod_activ_obra"], $vec["cepd01_compromiso_partidas"]["cod_partida"],$vec["cepd01_compromiso_partidas"]["cod_generica"], $vec["cepd01_compromiso_partidas"]["cod_especifica"], $vec["cepd01_compromiso_partidas"]["cod_sub_espec"],$vec["cepd01_compromiso_partidas"]["cod_auxiliar"]);
								   $to   = 2;
								   $td   = 3;
								   $ta   = 1;
								   $rnco = $vec["cepd01_compromiso_partidas"]["numero_control_compromiso"];
								   $mt   = $vec["cepd01_compromiso_partidas"]["monto"];
								   $ccp  = $concepto_anulacion;

								   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, date("d/m/Y"), $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $rnco, null, null, null, null);

									if($dnco == true){
										$msj2="Compromiso Anulado con exito";
										$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
										$para_msj = true;
									}else{
										$msj2="Compromiso no Anulado ";
										$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
										$para_msj = false;
										break;
									}

					     }//fin foreach

					}

							$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
										                                                  $to=2,
										                                                  $td=6,
										                                                  $rif_doc = $rif,
										                                                  $ano_dc  = $ano,
										                                                  $n_dc    = $numero_documento,
										                                                  $f_dc    = cambiar_formato_fecha($fecha_documento),
										                                                  $cpt_dc  = $concepto,
										                                                  $ben_dc  = $beneficiario,
										                                                  $mon_dc=array("monto"=>$monto),

										                                                  $ano_op               = null,
										                                                  $n_op                 = null,
										                                                  $f_op                 = null,

										                                                  $a_adj_op             = null,
										                                                  $n_adj_op             = null,
										                                                  $f_adj_op             = null,
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
																						  $tipo_modificacion  = null,
																						  $f_dc_adj_array_pago= array(),
      																					  $parametro_extras_1   = array(),
                                                                                          $parametro_extras_2   = array()
                                                                                          );
					}// FOREACH cepd01_compromiso_cuerpo

				} // lectura cepd01_compromiso_cuerpo

		   }// FOREACH cnmd99_orden_pago_prenomina

		}// FIN REVERSO - COMPROMISOS

             // ELIMINA LAS ORDENES DE PAGO GUARDADAS EN LA NOMINA ANTERIOR
             $elimina_ante_tempo=$this->cepd01_compromiso_cuerpo->execute("DELETE FROM cnmd99_orden_pago_prenomina WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina);
             $elimina_ante_perma=$this->cepd01_compromiso_cuerpo->execute("DELETE FROM cnmd99_orden_pago_prenomina_perma WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina."and numero_nomina=".$numero_nomina);

		if($valor_motor_contabilidad == true){

			$this->Cnmd01->execute("UPDATE cnmd01 SET status_nomina=2 WHERE ".$this->condicion()." and status_nomina=4 and cod_tipo_nomina=".$cod_tipo_nomina);

			$this->set('Message_existe','EL REVERSO DE ORDENES DE PAGO EFECTUADAS EN N&Oacute;MINAS FUE REALIZADO EXITOSAMENTE.!!!');

		}else{

			$this->set('errorMessage', 'NO SE PUDO EJECUTAR EL PROCESO PARA EL REVERSO DE ORDENES DE PAGO EFECTUADAS EN N&Oacute;MINAS. . .');
		}

	}else{

		 $this->set('errorMessage', 'N&Oacute;MINA NO TIENE ORDEN DE PAGO');

         }

	} // fin function proceso

}// END CLASS


?>
