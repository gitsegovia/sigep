<?php

class ScriptReactualizarContabilidadController extends AppController {

   var $name = "script_reactualizar_contabilidad";
   var $uses = array('cstd03_cheque_cuerpo', 'cstd03_cheque_partidas', 'cstd07_retenciones_cuerpo_iva', 'cstd07_retenciones_partidas_iva',
                      'cstd07_retenciones_cuerpo_islr', 'cstd07_retenciones_partidas_islr', 'cstd07_retenciones_cuerpo_timbre',
                      'cstd07_retenciones_partidas_timbre', 'cstd07_retenciones_cuerpo_municipal', 'cstd07_retenciones_partidas_municipal',
                      'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_partidas','cstd03_cheque_ordenes', 'ccfd04_cierre_mes',
                      'cstd03_cheque_poremitir','cstd04_movimientos_generales','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','cfpd01_grupo',
                      'cstd02_cuentas_bancarias','ccfd03_instalacion','cstd03_cheque_numero', 'cugd03_acta_anulacion_numero',
                      'cstd06_comprobante_cuerpo_egreso', 'cstd06_comprobante_cuerpo_islr', 'cstd06_comprobante_cuerpo_iva',
                      'cstd06_comprobante_cuerpo_municipal', 'cstd06_comprobante_cuerpo_timbre', 'cugd03_acta_anulacion_cuerpo',
                      'cstd06_comprobante_numero_egreso', 'cstd06_comprobante_numero_islr', 'cstd06_comprobante_numero_iva', 'cepd02_cs_modificacion_cuerpo',
                      'cstd06_comprobante_numero_municipal', 'cstd06_comprobante_numero_timbre','cstd04_movimientos_generales', 'v_cstd03_cheque_ordenes',
                      'cstd06_comprobante_poremitir_egreso', 'cstd06_comprobante_poremitir_islr', 'cstd06_comprobante_poremitir_iva',
                      'cstd06_comprobante_poremitir_municipal', 'cstd06_comprobante_poremitir_timbre','ccfd03_instalacion', 'cfpd23_numero_asiento_pagado',
                      'cfpd05', 'cugd04', 'cfpd23', 'cobd01_contratoobras_valuacion_partidas', 'cobd01_contratoobras_valuacion_cuerpo',
                      'cepd02_contratoservicio_valuacion_cuerpo', 'cepd02_contratoservicio_valuacion_partidas', 'cscd04_ordencompra_autorizacion_cuerpo',
                      'cscd04_ordencompra_a_pago_partidas', 'cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'cscd04_ordencompra_encabezado',
                      'cstd07_retenciones_cuerpo_multa', 'cstd07_retenciones_cuerpo_responsabilidad', 'cstd07_retenciones_partidas_multa', 'cstd07_retenciones_partidas_responsabilidad',
                      'cstd06_comprobante_poremitir_multa', 'cstd06_comprobante_poremitir_responsabilidad', 'cstd06_comprobante_numero_multa', 'cstd06_comprobante_numero_responsabilidad',
                      'cstd06_comprobante_cuerpo_multa', 'cstd06_comprobante_cuerpo_responsabilidad', 'cobd01_co_modificacion_cuerpo', 'cstd09_notadebito_cuerpo_pago',

                        'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_descripcion_tmp','ccfd10_detalles', 'ccfd10_detalles_tmp','ccfd02', 'ccfd05_numero_asiento',
                        'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo', 'cstd30_debito_cuerpo',
                        'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'cscd04_ordcom_modificacion_cuerpo',
					    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo', 'cstd09_notadebito_ordenes',
					    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
					    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'cscd02_solicitud_encabezado',
					    'v_cstd03_cheque_orden_pago', 'v_cstd09_notadebito_orden_pago', 'v_inventario_muebles_todo', 'v_inventario_inmuebles_todo',
					    'cimd01_clasificacion_seccion', 'cfpd30_reintegro_cuerpo', 'cfpd30_reintegro_partidas','cfpd30_rendiciones_cuerpo', 'cfpd30_rendiciones_partidas', 'cstd03_movimientos_manuales',
					    'cstd03_movimientos_manuales_ingresos','a_control_panel'
                    );
   var $helpers = array('Html','Ajax','Javascript','Sisap');




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

/* function SQLCA_noDEP(){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=1  and    ";
				 $sql_re .= "cod_entidad=11  and  ";
				 $sql_re .= "cod_tipo_inst=30  and ";
				 $sql_re .= "cod_inst=11 ";

				 return $sql_re;
		}//fin funcion SQLCA

*/


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









function ver_fecha_compromiso_mayor_orden_pago($pagina=null, $contador=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0);
    ini_set ("memory_limit", "777M");


    if($pagina==null){
    	$pagina=1;
    	$datos = $this->cepd03_ordenpago_cuerpo->findAll($this->condicionNDEP()." and  ano_orden_pago='".$ano_select."'  ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, numero_orden_pago ASC");
        $contar_datos   = count($datos);
        $_SESSION["contar_datos"] = $contar_datos;
    }





	$datos = $this->cepd03_ordenpago_cuerpo->findAll($this->condicionNDEP()." and  ano_orden_pago='".$ano_select."'  ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, numero_orden_pago ASC", 500, $pagina, null);
    $Tpag           = (int)ceil($_SESSION["contar_datos"]/500);
    $contar_datos   = count($datos);
    $contar_proceso = 0;


inicio_ventana_barra_proceso("Generando archivo Pag: ".$pagina."/".$Tpag." , Espere por favor...");

foreach($datos as $datos_aux){

		$contar_proceso++;
        proceso_ventana_barra_proceso($contar_proceso, 50, $contar_datos);


  $cod_presi                = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_orden_pago                              =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
  $numero_orden_pago                           =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_orden_pago'];
  $tipo_orden                                  =    $datos_aux['cepd03_ordenpago_cuerpo']['tipo_orden'];
  $fecha_orden_pago                            =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_orden_pago'];
  $ano_documento_origen                        =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_documento_origen'];
  $numero_documento_origen                     =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_documento_origen'];
  $numero_documento_adjunto                    =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_documento_adjunto'];
  $cod_tipo_documento                          =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_tipo_documento'];
  $rif                                         =    $datos_aux['cepd03_ordenpago_cuerpo']['rif'];
  $beneficiario                                =    $datos_aux['cepd03_ordenpago_cuerpo']['beneficiario'];
  $autorizado                                  =    $datos_aux['cepd03_ordenpago_cuerpo']['autorizado'];
  $cedula_identidad                            =    $datos_aux['cepd03_ordenpago_cuerpo']['cedula_identidad'];
  $concepto                                    =    $datos_aux['cepd03_ordenpago_cuerpo']['concepto'];
  $monto_total                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_total'];
  $numero_pago                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_pago'];
  $monto_parcial                               =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_parcial'];
  $cod_frecuencia_pago                         =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_frecuencia_pago'];
  $fecha_desde                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_desde'];
  $fecha_hasta                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_hasta'];
  $cod_tipo_pago                               =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_tipo_pago'];
  $monto_coniva                                =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_coniva'];
  $monto_iva                                   =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_iva'];
  $porcentaje_iva                              =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_iva'];
  $monto_siniva                                =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_siniva'];
  $monto_retencion_laboral                     =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_retencion_laboral'];
  $monto_retencion_fielcumplimiento            =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_retencion_fielcumplimiento'];
  $monto_descontar_impuesto                    =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_descontar_impuesto'];
  $amortizacion_anticipo                       =    $datos_aux['cepd03_ordenpago_cuerpo']['amortizacion_anticipo'];
  $monto_orden_pago                            =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_orden_pago'];
  $monto_retencion_iva                         =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_retencion_iva'];
  $porcentaje_retencion_iva                    =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_retencion_iva'];
  $monto_islr                                  =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_islr'];
  $porcentaje_islr                             =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_islr'];
  $monto_sustraendo                            =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_sustraendo'];
  $monto_timbre_fiscal                         =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_timbre_fiscal'];
  $porcentaje_timbre_fiscal                    =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_timbre_fiscal'];
  $monto_impuesto_municipal                    =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_impuesto_municipal'];
  $porcentaje_impuesto_municipal               =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_impuesto_municipal'];
  $monto_neto_cobrar                           =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_neto_cobrar'];
  $dia_asiento_registro                        =    $datos_aux['cepd03_ordenpago_cuerpo']['dia_asiento_registro'];
  $mes_asiento_registro                        =    $datos_aux['cepd03_ordenpago_cuerpo']['mes_asiento_registro'];
  $ano_asiento_registro                        =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_asiento_registro'];
  $numero_asiento_registro                     =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_asiento_registro'];
  $username_registro                           =    $datos_aux['cepd03_ordenpago_cuerpo']['username_registro'];
  $condicion_actividad                         =    $datos_aux['cepd03_ordenpago_cuerpo']['condicion_actividad'];
  $ano_anulacion                               =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_anulacion'];
  $numero_anulacion                            =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_anulacion'];
  $dia_asiento_anulacion                       =    $datos_aux['cepd03_ordenpago_cuerpo']['dia_asiento_anulacion'];
  $mes_asiento_anulacion                       =    $datos_aux['cepd03_ordenpago_cuerpo']['mes_asiento_anulacion'];
  $ano_asiento_anulacion                       =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_asiento_anulacion'];
  $numero_asiento_anulacion                    =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_asiento_anulacion'];
  $username_anulacion                          =    $datos_aux['cepd03_ordenpago_cuerpo']['username_anulacion'];
  $cod_entidad_bancaria                        =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria'];
  $cod_sucursal                                =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_sucursal'];
  $cuenta_bancaria                             =    $datos_aux['cepd03_ordenpago_cuerpo']['cuenta_bancaria'];
  $numero_cheque_op                            =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_cheque'];
  $fecha_cheque                                =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_cheque'];
  $fecha_proceso_registro                      =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_proceso_registro'];
  $fecha_proceso_anulacion                     =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_proceso_anulacion'];
  $numero_comprobante_islr                     =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_islr'];
  $numero_comprobante_timbre                   =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_timbre'];
  $numero_comprobante_municipal                =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_municipal'];
  $numero_comprobante_iva                      =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_iva'];
  $numero_comprobante_librocompras             =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_librocompras'];

  $retencion_multa                             =    $datos_aux['cepd03_ordenpago_cuerpo']['retencion_multa'];
  $retencion_responsabilidad                   =    $datos_aux['cepd03_ordenpago_cuerpo']['retencion_responsabilidad'];

  if($retencion_multa==""){          $retencion_multa           = 0;}
  if($retencion_responsabilidad==""){$retencion_responsabilidad = 0;}


             if($cod_tipo_documento==1){//REGISTRO DE COMPROMISO

              $datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento='".$ano_documento_origen."' and numero_documento='".$numero_documento_origen."'   ");

              if(!isset($datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"])){$datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"]=$fecha_orden_pago;}

              $f_dc_adj_array_pago_aux        = null;
              $f_dc_array_pago_aux            = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"];
              $fecha_proceso_registro_aux     = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_proceso_registro"];

         }else if($cod_tipo_documento==2){//Anticipo Orden de compra

               $datos  = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."' and numero_anticipo='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(     $this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"])){$datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"])){$datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"]=$fecha_orden_pago;}

               $f_dc_adj_array_pago_aux        = $datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"];
               $f_dc_array_pago_aux            = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
               $fecha_proceso_registro_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_proceso_registro"];

         }else if($cod_tipo_documento==3){//Autorización de Orden de compra

               $datos  = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."' and numero_pago='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(         $this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"])){$datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"])){$datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux        = $datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"];
               $f_dc_array_pago_aux            = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
               $fecha_proceso_registro_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_proceso_registro"];


         }else if($cod_tipo_documento==4){//Anticipo CONTRATO DE OBRA

               $datos  = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."' and numero_anticipo='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(         $this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"])){$datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"])){$datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux        = $datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"];
               $f_dc_array_pago_aux            = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
               $fecha_proceso_registro_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_proceso_registro"];


          }else if($cod_tipo_documento==5){//VALUACIÓN DE CONTRATO DE OBRA


               $datos  = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."' and numero_valuacion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"])){$datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"])){$datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux        = $datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"];
               $f_dc_array_pago_aux            = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
               $fecha_proceso_registro_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_proceso_registro"];


          }else if($cod_tipo_documento==6){//RETENCIÓN DE CONTRATO DE OBRA

               $datos  = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."' and numero_retencion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"])){$datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"])){$datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux        = $datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"];
               $f_dc_array_pago_aux            = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
               $fecha_proceso_registro_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_proceso_registro"];


          }else if($cod_tipo_documento==7){//Anticipo CONTRATO DE SERVICIO

               $datos  = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."' and numero_anticipo='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(         $this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"])){$datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"])){$datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux        = $datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"];
               $f_dc_array_pago_aux            = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
               $fecha_proceso_registro_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_proceso_registro"];


          }else if($cod_tipo_documento==8){//VALUACIÓN DE CONTRATO DE SERVICIO

               $datos  = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."' and numero_valuacion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"])){$datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"])){$datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux        = $datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"];
               $f_dc_array_pago_aux            = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
               $fecha_proceso_registro_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_proceso_registro"];


          }else if($cod_tipo_documento==9){//RETENCIÓN DE CONTRATO DE SERVICIO


               $datos  = $this->cepd02_contratoservicio_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."' and numero_retencion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"])){$datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"])){$datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux        = $datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"];
               $f_dc_array_pago_aux            = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
               $fecha_proceso_registro_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_proceso_registro"];


          }

     $f_dc_array_pago_aux = cambiar_formato_fecha($f_dc_array_pago_aux);
     $fecha_orden_pago    = cambiar_formato_fecha($fecha_orden_pago);

     $ano_documento_aux        = substr($f_dc_array_pago_aux,6,4);
     if($ano_select!=$ano_documento_aux){ $f_dc_array_pago_aux=cambiar_formato_fecha($fecha_proceso_registro_aux); }
       if(compara_fechas_basic($f_dc_array_pago_aux, $fecha_orden_pago)>0){
              $mes_1  = $f_dc_array_pago_aux[3].$f_dc_array_pago_aux[4];
              $mes_2  = $fecha_orden_pago[3].$fecha_orden_pago[4];
       	      $cadena = $this->condicion()." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' -- tipo documento ".$cod_tipo_documento." -- fecha documento ".$f_dc_array_pago_aux." -- fecha orden de pago ".$fecha_orden_pago." ";
               if($mes_1!=$mes_2){
		            $file_leer_candena = $this->leer_file("../webroot/descargas/fecha_compromiso_mayor_causado.txt");
					$this->wFile('fecha_compromiso_mayor_causado', $cadena."\n".$file_leer_candena, "w", "../webroot/descargas");
					if(file_exists("../webroot/descargas/fecha_compromiso_mayor_causado.txt")){
						     chmod("../webroot/descargas/fecha_compromiso_mayor_causado.txt", 0777);
					}
               }//fin if
       }//fin if
}//fin foreach

fin_ventana_barra_proceso();



 if($Tpag!=$pagina){

 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'ver_fecha_compromiso_mayor_orden_pago');


 }else{

 	if(file_exists("../webroot/descargas/fecha_compromiso_mayor_causado.txt")){
      echo "<input type='button' onclick=\"javascript:window.location='/descargas/descargar.php?name=fecha_compromiso_mayor_causado.txt& descarga_elimina=si;' \"  value='Descarga archivo'>";
    }

    $this->set('mensaje', "termino el script");
    $this->set('termino', true);

}






 $this->render('vista_index');


}//fin function




function prueba_formularios_barra($opcion=null){


         if($opcion==1){  $this->layout = "administradors"; }else{$this->layout = "pdf";}


$this->set("var", $opcion);



}//fin function






function termino($var1=null, $var2=null){

 $this->layout = "ajax";

	       if($var1==1){


	}else if($var1==2){



	}//fin else



$this->set("mensaje", $var2);
$this->set("var",     $var1);

}//fin function



function inicio_crear_cuentas_enlaces(){



 $this->layout = "ajax";



$sql = "DELETE FROM ccfd04_cuentas_enlace; INSERT INTO ccfd04_cuentas_enlace (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_fiscal, cod_tipo_enlace, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision) VALUES ";

for($i=1; $i<=2050; $i++){

	if($i==1){

	   $sql .= "(1,11,30,11, ".$i.", 2010, 5, 1, 132, 1, 6, 1),
				(1,11,30,11, ".$i.", 2010, 50, 1, 132, 1, 6, 1),
				(1,11,30,11, ".$i.", 2010, 51, 1, 132, 1, 6, 1),
				(1,11,30,11, ".$i.", 2010, 52, 1, 132, 1, 6, 1),
				(1,11,30,11, ".$i.", 2010, 53, 1, 132, 1, 6, 1),
				(1,11,30,11, ".$i.", 2010, 54, 1, 132, 1, 6, 1),
				(1,11,30,11, ".$i.", 2010, 55, 1, 132, 1, 6, 1) ";


	}else{

		$sql .= "
				,   (1,11,30,11, ".$i.", 2010, 5, 1, 132, 1, 6, 1),
					(1,11,30,11, ".$i.", 2010, 50, 1, 132, 1, 6, 1),
					(1,11,30,11, ".$i.", 2010, 51, 1, 132, 1, 6, 1),
					(1,11,30,11, ".$i.", 2010, 52, 1, 132, 1, 6, 1),
					(1,11,30,11, ".$i.", 2010, 53, 1, 132, 1, 6, 1),
					(1,11,30,11, ".$i.", 2010, 54, 1, 132, 1, 6, 1),
					(1,11,30,11, ".$i.", 2010, 55, 1, 132, 1, 6, 1) ";

	}

}



$sw_3 = $this->ccfd02->execute($sql);

echo $sql;


 $this->render('vista_index');

}//fin function






function inicio_reactualizacion(){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

if($data_control_pane[0]['a_control_panel']['sistema_cerrado']==0){
 $this->layout = "ajax";
 $this->set('mensaje', "EL ACCESO AL SISTEMA NO SE ENCUENTRA CERRADO POR FAVOR CIERRELO");
 $this->set('termino', 'vista_index');
 $this->render('vista_index');
}else{


 $this->layout = "ajax";

 porcentaje_barra(0, 100, "vaciar tablas", 1);

                    //$this->Session->read('total_pag_op_session_contabilidad');

                    //$this->Session->read('total_pag_rc_session_contabilidad');

                    //$this->Session->read('total_pag_ch_session_contabilidad');
$total  = 8;
$contar = 0;

     			$this->cugd04->execute("delete from ccfd10_descripcion_tmp;");
     	        $contar++;
     	        porcentaje_barra($contar, $total);

     	     	$this->cugd04->execute("delete from ccfd10_detalles_tmp;");
     	        $contar++;
     	        porcentaje_barra($contar, $total);

				$this->cugd04->execute("INSERT INTO ccfd10_descripcion_tmp (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento, instancia_asiento, concepto, tipo_documento, numero_documento, fecha_documento) SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento, instancia_asiento, concepto, tipo_documento, numero_documento, fecha_documento FROM ccfd10_descripcion WHERE ".$this->condicionNDEP()." and ano_asiento=".YEAR_REACTUALIZACION ." and instancia_asiento IN(1,4);");
				$contar++;
				porcentaje_barra($contar, $total);

  				$var_sel_detalle = "INSERT INTO ccfd10_detalles_tmp (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento, numero_linea, debito_credito, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision, monto) SELECT a.* from ccfd10_detalles a, ccfd10_descripcion_tmp b where

													    a.cod_presi              =   b.cod_presi and
													    a.cod_entidad            =   b.cod_entidad and
													    a.cod_tipo_inst          =   b.cod_tipo_inst and
													    a.cod_inst               =   b.cod_inst and
													    a.cod_dep                =   b.cod_dep and
													    a.ano_asiento       	 =   b.ano_asiento and
													    a.mes_asiento   		 =   b.mes_asiento and
								                        a.dia_asiento        	 =   b.dia_asiento and
														a.numero_asiento         =   b.numero_asiento";

				$this->cugd04->execute($var_sel_detalle);
				$contar++;
				porcentaje_barra($contar, $total);

     	                     $var2 =  $this->cugd04->execute("delete from ccfd02                 WHERE ".$this->condicionNDEP()." and ano_fiscal =".YEAR_REACTUALIZACION);
     	                     $contar++;
     	                     porcentaje_barra($contar, $total);

     	if($var2 > 1){       $var3 = $this->cugd04->execute("delete from ccfd05_numero_asiento  WHERE ".$this->condicionNDEP()." and ano_asiento=".YEAR_REACTUALIZACION);
                             $contar++;
     	                     porcentaje_barra($contar, $total);


     		if($var3 > 1){   $var4 = $this->cugd04->execute("delete from ccfd10_descripcion     WHERE ".$this->condicionNDEP()." and ano_asiento=".YEAR_REACTUALIZACION ." and instancia_asiento IN(2,3);");
                             $contar++;
     	                     porcentaje_barra($contar, $total);

     	     if($var4 > 1){   $var5 = $this->cugd04->execute("delete from ccfd10_detalles        WHERE ".$this->condicionNDEP()." and ano_asiento=".YEAR_REACTUALIZACION);
                             $contar++;
     	                     porcentaje_barra($contar, $total);

     	                  } //fin if

						  }//fin if

						  }//fin if


       $this->set('pagina',    1);
 	   $this->set('siguiente', 'reactualizacion_compromisos_rc');
 	   //$this->set('termino',    true);
 	   //$this->set('mensaje',    "termino");
       $this->render('vista_index');

}


}//fin function















function reactualizacion_compromisos_rc($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");


    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros de compromisos Pag:".$pagina."/".$this->Session->read('total_pag_rc_session_contabilidad'), 1);

	$datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and ano_documento='".$ano_select."'  ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_documento, numero_documento ASC", 500, $pagina, null);

    $total_suma = count($datos);



foreach($datos as $datos_aux){

		$contar_proceso++;
        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cepd01_compromiso_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cepd01_compromiso_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cepd01_compromiso_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cepd01_compromiso_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cepd01_compromiso_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);


  $ano_documento            = $datos_aux["cepd01_compromiso_cuerpo"]["ano_documento"];
  $numero_documento         = $datos_aux["cepd01_compromiso_cuerpo"]["numero_documento"];
  $cod_tipo_compromiso      = $datos_aux["cepd01_compromiso_cuerpo"]["cod_tipo_compromiso"];
  $fecha_documento          = cambiar_formato_fecha($datos_aux["cepd01_compromiso_cuerpo"]["fecha_documento"]);
  $tipo_recurso             = $datos_aux["cepd01_compromiso_cuerpo"]["tipo_recurso"];
  $rif                      = $datos_aux["cepd01_compromiso_cuerpo"]["rif"];
  $cedula_identidad         = $datos_aux["cepd01_compromiso_cuerpo"]["cedula_identidad"];
  $cod_dir_superior         = $datos_aux["cepd01_compromiso_cuerpo"]["cod_dir_superior"];
  $cod_coordinacion         = $datos_aux["cepd01_compromiso_cuerpo"]["cod_coordinacion"];
  $cod_secretaria           = $datos_aux["cepd01_compromiso_cuerpo"]["cod_secretaria"];
  $cod_direccion            = $datos_aux["cepd01_compromiso_cuerpo"]["cod_direccion"];
  $concepto                 = $datos_aux["cepd01_compromiso_cuerpo"]["concepto"];
  $monto                    = $datos_aux["cepd01_compromiso_cuerpo"]["monto"];
  $condicion_actividad      = $datos_aux["cepd01_compromiso_cuerpo"]["condicion_actividad"];
  $dia_asiento_registro     = $datos_aux["cepd01_compromiso_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cepd01_compromiso_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cepd01_compromiso_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cepd01_compromiso_cuerpo"]["numero_asiento_registro"];
  $username_registro        = $datos_aux["cepd01_compromiso_cuerpo"]["username_registro"];
  $ano_anulacion            = $datos_aux["cepd01_compromiso_cuerpo"]["ano_anulacion"];
  $numero_anulacion         = $datos_aux["cepd01_compromiso_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion    = $datos_aux["cepd01_compromiso_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cepd01_compromiso_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cepd01_compromiso_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cepd01_compromiso_cuerpo"]["numero_asiento_anulacion"];
  $username_anulacion       = $datos_aux["cepd01_compromiso_cuerpo"]["username_anulacion"];
  $ano_orden_pago           = $datos_aux["cepd01_compromiso_cuerpo"]["ano_orden_pago"];
  $numero_orden_pago        = $datos_aux["cepd01_compromiso_cuerpo"]["numero_orden_pago"];
  $beneficiario             = $datos_aux["cepd01_compromiso_cuerpo"]["beneficiario"];
  $condicion_actividad       = $datos_aux["cepd01_compromiso_cuerpo"]["condicion_actividad"];
  $fecha_proceso_registro   =  cambiar_formato_fecha($datos_aux["cepd01_compromiso_cuerpo"]["fecha_proceso_registro"]);
  $fecha_proceso_anulacion  = $datos_aux["cepd01_compromiso_cuerpo"]["fecha_proceso_anulacion"];


  $ano_documento_aux    = substr($fecha_documento,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_documento=$fecha_proceso_registro; }


     $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
								                                  $to=1,
								                                  $td=6,
								                                  $rif_doc = $rif,
								                                  $ano_dc  = $ano_documento,
								                                  $n_dc    = basic_mascara_ocho($numero_documento),
								                                  $f_dc    = $fecha_documento,
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
															      $tipo_modificacion  = null);


}//fin foreach









 if($this->Session->read('total_pag_rc_session_contabilidad')==$pagina || $this->Session->read('total_pag_rc_session_contabilidad')==0){

 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_rc_anulados');
 	//$this->set('termino',    true);


 }else{

 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'reactualizacion_compromisos_rc');
 	//$this->set('termino',    true);

 }





 $this->render('vista_index');



}//fin function















function reactualizacion_compromisos_rc_anulados($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros de compromisos anulación", 1);



	$datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and  ano_documento='".$ano_select."' and condicion_actividad=2 ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cepd01_compromiso_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cepd01_compromiso_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cepd01_compromiso_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cepd01_compromiso_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cepd01_compromiso_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);


  $ano_documento            = $datos_aux["cepd01_compromiso_cuerpo"]["ano_documento"];
  $numero_documento         = $datos_aux["cepd01_compromiso_cuerpo"]["numero_documento"];
  $cod_tipo_compromiso      = $datos_aux["cepd01_compromiso_cuerpo"]["cod_tipo_compromiso"];
  $fecha_documento          = cambiar_formato_fecha($datos_aux["cepd01_compromiso_cuerpo"]["fecha_documento"]);
  $tipo_recurso             = $datos_aux["cepd01_compromiso_cuerpo"]["tipo_recurso"];
  $rif                      = $datos_aux["cepd01_compromiso_cuerpo"]["rif"];
  $cedula_identidad         = $datos_aux["cepd01_compromiso_cuerpo"]["cedula_identidad"];
  $cod_dir_superior         = $datos_aux["cepd01_compromiso_cuerpo"]["cod_dir_superior"];
  $cod_coordinacion         = $datos_aux["cepd01_compromiso_cuerpo"]["cod_coordinacion"];
  $cod_secretaria           = $datos_aux["cepd01_compromiso_cuerpo"]["cod_secretaria"];
  $cod_direccion            = $datos_aux["cepd01_compromiso_cuerpo"]["cod_direccion"];
  $concepto                 = $datos_aux["cepd01_compromiso_cuerpo"]["concepto"];
  $monto                    = $datos_aux["cepd01_compromiso_cuerpo"]["monto"];
  $condicion_actividad      = $datos_aux["cepd01_compromiso_cuerpo"]["condicion_actividad"];
  $dia_asiento_registro     = $datos_aux["cepd01_compromiso_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cepd01_compromiso_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cepd01_compromiso_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cepd01_compromiso_cuerpo"]["numero_asiento_registro"];
  $username_registro        = $datos_aux["cepd01_compromiso_cuerpo"]["username_registro"];
  $ano_anulacion            = $datos_aux["cepd01_compromiso_cuerpo"]["ano_anulacion"];
  $numero_anulacion         = $datos_aux["cepd01_compromiso_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion    = $datos_aux["cepd01_compromiso_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cepd01_compromiso_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cepd01_compromiso_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cepd01_compromiso_cuerpo"]["numero_asiento_anulacion"];
  $username_anulacion       = $datos_aux["cepd01_compromiso_cuerpo"]["username_anulacion"];
  $ano_orden_pago           = $datos_aux["cepd01_compromiso_cuerpo"]["ano_orden_pago"];
  $numero_orden_pago        = $datos_aux["cepd01_compromiso_cuerpo"]["numero_orden_pago"];
  $beneficiario             = $datos_aux["cepd01_compromiso_cuerpo"]["beneficiario"];
  $condicion_actividad       = $datos_aux["cepd01_compromiso_cuerpo"]["condicion_actividad"];
  $fecha_proceso_registro   =  cambiar_formato_fecha($datos_aux["cepd01_compromiso_cuerpo"]["fecha_proceso_registro"]);
  $fecha_proceso_anulacion  =  cambiar_formato_fecha($datos_aux["cepd01_compromiso_cuerpo"]["fecha_proceso_anulacion"]);


  $ano_documento_aux    = substr($fecha_documento,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_documento=$fecha_proceso_registro; }


     $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
								                                  $to=2,
								                                  $td=6,
								                                  $rif_doc = $rif,
								                                  $ano_dc  = $ano_documento,
								                                  $n_dc    = basic_mascara_ocho($numero_documento),
								                                  $f_dc    = $fecha_proceso_anulacion,
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
															      $tipo_modificacion  = null);


}//fin foreach







 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_compromisos_compra');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function






















function reactualizacion_compromisos_compra($pagina=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }


    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros de orden de compras Pag:".$pagina."/".$this->Session->read('total_pag_orden_compra_session_contabilidad'), 1);

	$datos = $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and ano_orden_compra='".$ano_select."'  ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra ASC", 500, $pagina, null);

    $total_suma = count($datos);
    $cadena     = "";


foreach($datos as $datos_aux){

	$motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cscd04_ordencompra_encabezado"]["cod_presi"];
  $cod_entidad              = $datos_aux["cscd04_ordencompra_encabezado"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cscd04_ordencompra_encabezado"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cscd04_ordencompra_encabezado"]["cod_inst"];
  $cod_dep                  = $datos_aux["cscd04_ordencompra_encabezado"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_orden_compra           = $datos_aux["cscd04_ordencompra_encabezado"]["ano_orden_compra"];
  $numero_orden_compra        = $datos_aux["cscd04_ordencompra_encabezado"]["numero_orden_compra"];
  $tipo_orden                 = $datos_aux["cscd04_ordencompra_encabezado"]["tipo_orden"];
  $rif                        = $datos_aux["cscd04_ordencompra_encabezado"]["rif"];
  $ano_cotizacion             = $datos_aux["cscd04_ordencompra_encabezado"]["ano_cotizacion"];
  $numero_cotizacion          = $datos_aux["cscd04_ordencompra_encabezado"]["numero_cotizacion"];
  $lugar_entrega              = $datos_aux["cscd04_ordencompra_encabezado"]["lugar_entrega"];
  $plazo_entrega              = $datos_aux["cscd04_ordencompra_encabezado"]["plazo_entrega"];
  $monto_orden                = $datos_aux["cscd04_ordencompra_encabezado"]["monto_orden"];
  $modificacion_aumento       = $datos_aux["cscd04_ordencompra_encabezado"]["modificacion_aumento"];
  $modificacion_disminucion   = $datos_aux["cscd04_ordencompra_encabezado"]["modificacion_disminucion"];
  $monto_anticipo             = $datos_aux["cscd04_ordencompra_encabezado"]["monto_anticipo"];
  $monto_amortizacion         = $datos_aux["cscd04_ordencompra_encabezado"]["monto_amortizacion"];
  $monto_cancelado            = $datos_aux["cscd04_ordencompra_encabezado"]["monto_cancelado"];
  $porcentaje_iva             = $datos_aux["cscd04_ordencompra_encabezado"]["porcentaje_iva"];
  $porcentaje_anticipo        = $datos_aux["cscd04_ordencompra_encabezado"]["porcentaje_anticipo"];
  $anticipo_con_iva           = $datos_aux["cscd04_ordencompra_encabezado"]["anticipo_con_iva"];
  $fecha_proceso_registro     =  cambiar_formato_fecha($datos_aux["cscd04_ordencompra_encabezado"]["fecha_proceso_registro"]);
  $dia_asiento_registro       = $datos_aux["cscd04_ordencompra_encabezado"]["dia_asiento_registro"];
  $mes_asiento_registro       = $datos_aux["cscd04_ordencompra_encabezado"]["mes_asiento_registro"];
  $ano_asiento_registro       = $datos_aux["cscd04_ordencompra_encabezado"]["ano_asiento_registro"];
  $numero_asiento_registro    = $datos_aux["cscd04_ordencompra_encabezado"]["numero_asiento_registro"];
  $username_registro          = $datos_aux["cscd04_ordencompra_encabezado"]["username_registro"];
  $condicion_actividad        = $datos_aux["cscd04_ordencompra_encabezado"]["condicion_actividad"];
  $dia_asiento_anulacion      = $datos_aux["cscd04_ordencompra_encabezado"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion      = $datos_aux["cscd04_ordencompra_encabezado"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion      = $datos_aux["cscd04_ordencompra_encabezado"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion   = $datos_aux["cscd04_ordencompra_encabezado"]["numero_asiento_anulacion"];
  $username_anulacion         = $datos_aux["cscd04_ordencompra_encabezado"]["username_anulacion"];
  $fecha_proceso_anulacion    = $datos_aux["cscd04_ordencompra_encabezado"]["fecha_proceso_anulacion"];
  $fecha_orden_compra         =  cambiar_formato_fecha($datos_aux["cscd04_ordencompra_encabezado"]["fecha_orden_compra"]);
  $entrega_completa           = $datos_aux["cscd04_ordencompra_encabezado"]["entrega_completa"];
  $saldo_ano_anterior         = $datos_aux["cscd04_ordencompra_encabezado"]["saldo_ano_anterior"];

  $concepto                    = $this->cscd02_solicitud_encabezado->field('uso_destino', $condicion." and rif='".$rif."' and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."' ");


  $ano_documento_aux    = substr($fecha_orden_compra,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_orden_compra=$fecha_proceso_registro; }

 $RANDOM             = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_orden_compra='".$ano_orden_compra."' and  numero_orden_compra='".$numero_orden_compra."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){


  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                $to=1,
                                                                $td=5,
                                                                $rif_doc  = $rif,
                                                                $ano_dc   = $ano_orden_compra,
                                                                $n_dc     = $numero_orden_compra,
                                                                $f_dc     = $fecha_orden_compra,
                                                                $cpt_dc   = $concepto,
                                                                $ben_dc   = null,
                                                                $mon_dc   = array("monto"=>$monto_orden),
                                                                $ano_op   = null,
                                                                $n_op     = null,
                                                                $f_op     = null,
                                                                $a_adj_op = null,
                                                                $n_adj_op = null,
                                                                $f_adj_op = null,
                                                                $tp_op    = null,
                                                                $deno_ban_pago   = null,
                                                                $ano_movimiento  = null,
                                                                $cod_ent_pago    = null,
                                                                $cod_suc_pago    = null,
                                                                $cod_cta_pago    = null,
                                                                $num_che_o_debi  = null,
                                                                $fec_che_o_debi  = null,
                                                                $clas_che_o_debi = null
                                                               );

}




}//fin foreach


/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/


 if($this->Session->read('total_pag_orden_compra_session_contabilidad')==$pagina || $this->Session->read('total_pag_orden_compra_session_contabilidad')==0){

 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_compromisos_compra_anulacion');
 	//$this->set('termino',    true);


 }else{

 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'reactualizacion_compromisos_compra');
 	//$this->set('termino',    true);

 }



 $this->render('vista_index');



}//fin function















function reactualizacion_compromisos_compra_anulacion($pagina=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }


    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros orden de compras anulación", 1);



	$datos = $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and  ano_orden_compra='".$ano_select."' and condicion_actividad=2 ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	$motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cscd04_ordencompra_encabezado"]["cod_presi"];
  $cod_entidad              = $datos_aux["cscd04_ordencompra_encabezado"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cscd04_ordencompra_encabezado"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cscd04_ordencompra_encabezado"]["cod_inst"];
  $cod_dep                  = $datos_aux["cscd04_ordencompra_encabezado"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_orden_compra           = $datos_aux["cscd04_ordencompra_encabezado"]["ano_orden_compra"];
  $numero_orden_compra        = $datos_aux["cscd04_ordencompra_encabezado"]["numero_orden_compra"];
  $tipo_orden                 = $datos_aux["cscd04_ordencompra_encabezado"]["tipo_orden"];
  $rif                        = $datos_aux["cscd04_ordencompra_encabezado"]["rif"];
  $ano_cotizacion             = $datos_aux["cscd04_ordencompra_encabezado"]["ano_cotizacion"];
  $numero_cotizacion          = $datos_aux["cscd04_ordencompra_encabezado"]["numero_cotizacion"];
  $lugar_entrega              = $datos_aux["cscd04_ordencompra_encabezado"]["lugar_entrega"];
  $plazo_entrega              = $datos_aux["cscd04_ordencompra_encabezado"]["plazo_entrega"];
  $monto_orden                = $datos_aux["cscd04_ordencompra_encabezado"]["monto_orden"];
  $modificacion_aumento       = $datos_aux["cscd04_ordencompra_encabezado"]["modificacion_aumento"];
  $modificacion_disminucion   = $datos_aux["cscd04_ordencompra_encabezado"]["modificacion_disminucion"];
  $monto_anticipo             = $datos_aux["cscd04_ordencompra_encabezado"]["monto_anticipo"];
  $monto_amortizacion         = $datos_aux["cscd04_ordencompra_encabezado"]["monto_amortizacion"];
  $monto_cancelado            = $datos_aux["cscd04_ordencompra_encabezado"]["monto_cancelado"];
  $porcentaje_iva             = $datos_aux["cscd04_ordencompra_encabezado"]["porcentaje_iva"];
  $porcentaje_anticipo        = $datos_aux["cscd04_ordencompra_encabezado"]["porcentaje_anticipo"];
  $anticipo_con_iva           = $datos_aux["cscd04_ordencompra_encabezado"]["anticipo_con_iva"];
  $fecha_proceso_registro     =  cambiar_formato_fecha($datos_aux["cscd04_ordencompra_encabezado"]["fecha_proceso_registro"]);
  $dia_asiento_registro       = $datos_aux["cscd04_ordencompra_encabezado"]["dia_asiento_registro"];
  $mes_asiento_registro       = $datos_aux["cscd04_ordencompra_encabezado"]["mes_asiento_registro"];
  $ano_asiento_registro       = $datos_aux["cscd04_ordencompra_encabezado"]["ano_asiento_registro"];
  $numero_asiento_registro    = $datos_aux["cscd04_ordencompra_encabezado"]["numero_asiento_registro"];
  $username_registro          = $datos_aux["cscd04_ordencompra_encabezado"]["username_registro"];
  $condicion_actividad        = $datos_aux["cscd04_ordencompra_encabezado"]["condicion_actividad"];
  $dia_asiento_anulacion      = $datos_aux["cscd04_ordencompra_encabezado"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion      = $datos_aux["cscd04_ordencompra_encabezado"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion      = $datos_aux["cscd04_ordencompra_encabezado"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion   = $datos_aux["cscd04_ordencompra_encabezado"]["numero_asiento_anulacion"];
  $username_anulacion         = $datos_aux["cscd04_ordencompra_encabezado"]["username_anulacion"];
  $fecha_proceso_anulacion    =  cambiar_formato_fecha($datos_aux["cscd04_ordencompra_encabezado"]["fecha_proceso_anulacion"]);
  $fecha_orden_compra         =  cambiar_formato_fecha($datos_aux["cscd04_ordencompra_encabezado"]["fecha_orden_compra"]);
  $entrega_completa           = $datos_aux["cscd04_ordencompra_encabezado"]["entrega_completa"];
  $saldo_ano_anterior         = $datos_aux["cscd04_ordencompra_encabezado"]["saldo_ano_anterior"];

  $concepto                   = $this->cscd02_solicitud_encabezado->field('uso_destino', $condicion." and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."' ");


  $ano_documento_aux    = substr($fecha_orden_compra,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_orden_compra=$fecha_proceso_registro; }

$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_orden_compra='".$ano_orden_compra."' and  numero_orden_compra='".$numero_orden_compra."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                $to       = 2,
                                                                $td       = 5,
                                                                $rif_doc  = $rif,
                                                                $ano_dc   = $ano_orden_compra,
                                                                $n_dc     = $numero_orden_compra,
                                                                $f_dc     = $fecha_proceso_anulacion,
                                                                $cpt_dc   = $concepto,
                                                                $ben_dc   = null,
                                                                $mon_dc   = array("monto"=>$monto_orden),
                                                                $ano_op   = null,
                                                                $n_op     = null,
                                                                $f_op     = null,
                                                                $a_adj_op = null,
                                                                $n_adj_op = null,
                                                                $f_adj_op = null,
                                                                $tp_op    = null,
                                                                $deno_ban_pago   = null,
                                                                $ano_movimiento  = null,
                                                                $cod_ent_pago    = null,
                                                                $cod_suc_pago    = null,
                                                                $cod_cta_pago    = null,
                                                                $num_che_o_debi  = null,
                                                                $fec_che_o_debi  = null,
                                                                $clas_che_o_debi = null
                                                               );
}


}//fin foreach





/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/
 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_compra_modificacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function


















function reactualizacion_compromisos_compra_modificacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros orden de compras modificación", 1);



	$datos = $this->cscd04_ordcom_modificacion_cuerpo->findAll($this->condicionNDEP()." and  ano_orden_compra='".$ano_select."'  ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_orden_compra         = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["ano_orden_compra"];
  $numero_orden_compra      = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_orden_compra"];
  $numero_modificacion      = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_modificacion"];
  $tipo_modificacion        = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["tipo_modificacion"];
  $monto_modificacion       = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["monto_modificacion"];
  $fecha_modificacion       = cambiar_formato_fecha($datos_aux["cscd04_ordcom_modificacion_cuerpo"]["fecha_modificacion"]);
  $observaciones            = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["observaciones"];
  $dia_asiento_registro     = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_asiento_registro"];
  $fecha_proceso_registro   =  cambiar_formato_fecha($datos_aux["cscd04_ordcom_modificacion_cuerpo"]["fecha_proceso_registro"]);
  $username_registro        = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["username_registro"];
  $condicion_actividad      = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["condicion_actividad"];
  $dia_asiento_anulacion    = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_asiento_anulacion"];
  $username_anulacion       = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["username_anulacion"];
  $fecha_proceso_anulacion  = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["fecha_proceso_anulacion"];
  $ano_anulacion            = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["ano_anulacion"];
  $numero_anulacion         = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_anulacion"];

  $rif                      = $this->cscd04_ordencompra_encabezado->field('rif',                $condicion." and ano_orden_compra='".$ano_orden_compra."'  and numero_orden_compra='".$numero_orden_compra."' ");
  $fecha_orden_compra       =  cambiar_formato_fecha($this->cscd04_ordencompra_encabezado->field('fecha_orden_compra', $condicion." and ano_orden_compra='".$ano_orden_compra."'  and numero_orden_compra='".$numero_orden_compra."' "));

    $ano_documento_aux    = substr($fecha_modificacion,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_modificacion=$fecha_proceso_registro; }


$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_orden_compra='".$ano_orden_compra."' and  numero_orden_compra='".$numero_orden_compra."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

                            $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
				                                                                          $to=1,
				                                                                          $td=10,
				                                                                          $rif_doc = $rif,

				                                                                          $ano_dc  = $ano_orden_compra,
				                                                                          $n_dc    = $numero_orden_compra,

				                                                                          $f_dc    = $fecha_orden_compra,
				                                                                          $cpt_dc  = $observaciones,
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
}


}//fin foreach



/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/


 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_compra_modificacion_anulacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function













function reactualizacion_compromisos_compra_modificacion_anulacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros orden de compras modificación anulación", 1);



	$datos = $this->cscd04_ordcom_modificacion_cuerpo->findAll($this->condicionNDEP()." and  ano_orden_compra='".$ano_select."' and condicion_actividad=2 ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_orden_compra         = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["ano_orden_compra"];
  $numero_orden_compra      = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_orden_compra"];
  $numero_modificacion      = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_modificacion"];
  $tipo_modificacion        = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["tipo_modificacion"];
  $monto_modificacion       = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["monto_modificacion"];
  $fecha_modificacion       = cambiar_formato_fecha($datos_aux["cscd04_ordcom_modificacion_cuerpo"]["fecha_modificacion"]);
  $observaciones            = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["observaciones"];
  $dia_asiento_registro     = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_asiento_registro"];
  $fecha_proceso_registro   =  cambiar_formato_fecha($datos_aux["cscd04_ordcom_modificacion_cuerpo"]["fecha_proceso_registro"]);
  $username_registro        = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["username_registro"];
  $condicion_actividad      = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["condicion_actividad"];
  $dia_asiento_anulacion    = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_asiento_anulacion"];
  $username_anulacion       = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["username_anulacion"];
  $fecha_proceso_anulacion  =  cambiar_formato_fecha($datos_aux["cscd04_ordcom_modificacion_cuerpo"]["fecha_proceso_anulacion"]);
  $ano_anulacion            = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["ano_anulacion"];
  $numero_anulacion         = $datos_aux["cscd04_ordcom_modificacion_cuerpo"]["numero_anulacion"];

  $rif                      = $this->cscd04_ordencompra_encabezado->field('rif',                $condicion." and ano_orden_compra='".$ano_orden_compra."'  and numero_orden_compra='".$numero_orden_compra."' ");
  $fecha_orden_compra       =  cambiar_formato_fecha($this->cscd04_ordencompra_encabezado->field('fecha_orden_compra', $condicion." and ano_orden_compra='".$ano_orden_compra."'  and numero_orden_compra='".$numero_orden_compra."' "));

    $ano_documento_aux    = substr($fecha_modificacion,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_modificacion=$fecha_proceso_registro; }


$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_orden_compra='".$ano_orden_compra."' and  numero_orden_compra='".$numero_orden_compra."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

                            $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
				                                                                          $to=2,
				                                                                          $td=10,
				                                                                          $rif_doc = $rif,

				                                                                          $ano_dc  = $ano_orden_compra,
				                                                                          $n_dc    = $numero_orden_compra,

				                                                                          $f_dc    = $fecha_orden_compra,
				                                                                          $cpt_dc  = $observaciones,
				                                                                          $ben_dc  = null,

				                                                                          $mon_dc=array("monto"=>$monto_modificacion),

				                                                                          $ano_op               = null,
				                                                                          $n_op                 = null,
				                                                                          $f_op                 = null,
				                                                                          $a_adj_op             = null,
				                                                                          $n_adj_op             = $numero_modificacion,
				                                                                          $f_adj_op             = $fecha_proceso_anulacion,
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
}


}//fin foreach




/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/


 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_obras');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function




















function reactualizacion_compromisos_obras($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros contrato de obra", 1);



	$datos = $this->cobd01_contratoobras_cuerpo->findAll($this->condicionNDEP()." and  ano_contrato_obra='".$ano_select."'  ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_contrato_obra                  = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_contrato_obra"];
  $numero_contrato_obra               = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_contrato_obra"];
  $ano_estimacion                     = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_estimacion"];
  $cod_obra                           = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_obra"];
  $cod_estado                         = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_estado"];
  $cod_municipio                      = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_municipio"];
  $cod_parroquia                      = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_parroquia"];
  $cod_centro                         = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_centro"];
  $especifique_ubicacion              = $datos_aux["cobd01_contratoobras_cuerpo"]["especifique_ubicacion"];
  $otorgamiento                       = $datos_aux["cobd01_contratoobras_cuerpo"]["otorgamiento"];
  $denominacion_obra                  = $datos_aux["cobd01_contratoobras_cuerpo"]["denominacion_obra"];
  $rif                                = $datos_aux["cobd01_contratoobras_cuerpo"]["rif"];
  $fecha_contrato_obra                =  cambiar_formato_fecha($datos_aux["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]);
  $fecha_inicio_contrato              = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_inicio_contrato"];
  $fecha_terminacion_contrato         = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_terminacion_contrato"];
  $monto_original_contrato            = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_original_contrato"];
  $aumento                            = $datos_aux["cobd01_contratoobras_cuerpo"]["aumento"];
  $disminucion                        = $datos_aux["cobd01_contratoobras_cuerpo"]["disminucion"];
  $monto_anticipo                     = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_anticipo"];
  $monto_amortizacion                 = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_amortizacion"];
  $monto_retencion_laboral            = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_retencion_laboral"];
  $monto_retencion_fielcumplimiento   = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_retencion_fielcumplimiento"];
  $monto_cancelado                    = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_cancelado"];
  $porcentaje_iva                     = $datos_aux["cobd01_contratoobras_cuerpo"]["porcentaje_iva"];
  $porcentaje_anticipo                = $datos_aux["cobd01_contratoobras_cuerpo"]["porcentaje_anticipo"];
  $anticipo_con_iva                   = $datos_aux["cobd01_contratoobras_cuerpo"]["anticipo_con_iva"];
  $fecha_proceso_registro             =  cambiar_formato_fecha($datos_aux["cobd01_contratoobras_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro               = $datos_aux["cobd01_contratoobras_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro               = $datos_aux["cobd01_contratoobras_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro               = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro            = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_asiento_registro"];
  $username_registro                  = $datos_aux["cobd01_contratoobras_cuerpo"]["username_registro"];
  $condicion_actividad                = $datos_aux["cobd01_contratoobras_cuerpo"]["condicion_actividad"];
  $ano_anulacion                      = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_anulacion"];
  $numero_anulacion                   = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion              = $datos_aux["cobd01_contratoobras_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion              = $datos_aux["cobd01_contratoobras_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion              = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_asiento_anulacion"];
  $fecha_proceso_anulacion            = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_proceso_anulacion"];
  $username_anulacion                 = $datos_aux["cobd01_contratoobras_cuerpo"]["username_anulacion"];
  $fielcumplimiento_cancelado         = $datos_aux["cobd01_contratoobras_cuerpo"]["fielcumplimiento_cancelado"];
  $laboral_cancelado                  = $datos_aux["cobd01_contratoobras_cuerpo"]["laboral_cancelado"];
  $numero_buenapro                    = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_buenapro"];
  $fecha_buenapro                     = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_buenapro"];
  $numero_fianza_anticipo             = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_fianza_anticipo"];
  $fecha_fianza_anticipo              = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_fianza_anticipo"];
  $numero_fianza_fielcumplimiento     = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_fianza_fielcumplimiento"];
  $fecha_fianza_fielcumplimiento      = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_fianza_fielcumplimiento"];
  $numero_fianza_calidad              = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_fianza_calidad"];
  $fecha_fianza_calidad               = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_fianza_calidad"];
  $numero_asiento_anulacion           = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_asiento_anulacion"];
  $saldo_ano_anterior                 = $datos_aux["cobd01_contratoobras_cuerpo"]["saldo_ano_anterior"];


     $ano_documento_aux    = substr($fecha_contrato_obra,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_contrato_obra=$fecha_proceso_registro; }



$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_contrato_obra='".$ano_contrato_obra."' and  numero_contrato_obra='".$numero_contrato_obra."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                $to=1,
                                                                $td=7,
                                                                $rif_doc  = $rif,
                                                                $ano_dc   = $ano_contrato_obra,
                                                                $n_dc     = $numero_contrato_obra,
                                                                $f_dc     = $fecha_contrato_obra,
                                                                $cpt_dc   = $denominacion_obra,
                                                                $ben_dc   = null,
                                                                $mon_dc   = array("monto"=>$monto_original_contrato),
                                                                $ano_op   = null,
                                                                $n_op     = null,
                                                                $f_op     = null,
                                                                $a_adj_op = null,
                                                                $n_adj_op = null,
                                                                $f_adj_op = null,
                                                                $tp_op    = null,
                                                                $deno_ban_pago   = null,
                                                                $ano_movimiento  = null,
                                                                $cod_ent_pago    = null,
                                                                $cod_suc_pago    = null,
                                                                $cod_cta_pago    = null,
                                                                $num_che_o_debi  = null,
                                                                $fec_che_o_debi  = null,
                                                                $clas_che_o_debi = null
                                                                );


}



}//fin foreach




/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/



 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_obras_anulacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function










function reactualizacion_compromisos_obras_anulacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros contrato de obra anulación", 1);



	$datos = $this->cobd01_contratoobras_cuerpo->findAll($this->condicionNDEP()." and  ano_contrato_obra='".$ano_select."' and condicion_actividad=2 ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_contrato_obra                  = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_contrato_obra"];
  $numero_contrato_obra               = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_contrato_obra"];
  $ano_estimacion                     = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_estimacion"];
  $cod_obra                           = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_obra"];
  $cod_estado                         = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_estado"];
  $cod_municipio                      = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_municipio"];
  $cod_parroquia                      = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_parroquia"];
  $cod_centro                         = $datos_aux["cobd01_contratoobras_cuerpo"]["cod_centro"];
  $especifique_ubicacion              = $datos_aux["cobd01_contratoobras_cuerpo"]["especifique_ubicacion"];
  $otorgamiento                       = $datos_aux["cobd01_contratoobras_cuerpo"]["otorgamiento"];
  $denominacion_obra                  = $datos_aux["cobd01_contratoobras_cuerpo"]["denominacion_obra"];
  $rif                                = $datos_aux["cobd01_contratoobras_cuerpo"]["rif"];
  $fecha_contrato_obra                =  cambiar_formato_fecha($datos_aux["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]);
  $fecha_inicio_contrato              = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_inicio_contrato"];
  $fecha_terminacion_contrato         = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_terminacion_contrato"];
  $monto_original_contrato            = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_original_contrato"];
  $aumento                            = $datos_aux["cobd01_contratoobras_cuerpo"]["aumento"];
  $disminucion                        = $datos_aux["cobd01_contratoobras_cuerpo"]["disminucion"];
  $monto_anticipo                     = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_anticipo"];
  $monto_amortizacion                 = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_amortizacion"];
  $monto_retencion_laboral            = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_retencion_laboral"];
  $monto_retencion_fielcumplimiento   = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_retencion_fielcumplimiento"];
  $monto_cancelado                    = $datos_aux["cobd01_contratoobras_cuerpo"]["monto_cancelado"];
  $porcentaje_iva                     = $datos_aux["cobd01_contratoobras_cuerpo"]["porcentaje_iva"];
  $porcentaje_anticipo                = $datos_aux["cobd01_contratoobras_cuerpo"]["porcentaje_anticipo"];
  $anticipo_con_iva                   = $datos_aux["cobd01_contratoobras_cuerpo"]["anticipo_con_iva"];
  $fecha_proceso_registro             =  cambiar_formato_fecha($datos_aux["cobd01_contratoobras_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro               = $datos_aux["cobd01_contratoobras_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro               = $datos_aux["cobd01_contratoobras_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro               = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro            = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_asiento_registro"];
  $username_registro                  = $datos_aux["cobd01_contratoobras_cuerpo"]["username_registro"];
  $condicion_actividad                = $datos_aux["cobd01_contratoobras_cuerpo"]["condicion_actividad"];
  $ano_anulacion                      = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_anulacion"];
  $numero_anulacion                   = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion              = $datos_aux["cobd01_contratoobras_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion              = $datos_aux["cobd01_contratoobras_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion              = $datos_aux["cobd01_contratoobras_cuerpo"]["ano_asiento_anulacion"];
  $fecha_proceso_anulacion            =  cambiar_formato_fecha($datos_aux["cobd01_contratoobras_cuerpo"]["fecha_proceso_anulacion"]);
  $username_anulacion                 = $datos_aux["cobd01_contratoobras_cuerpo"]["username_anulacion"];
  $fielcumplimiento_cancelado         = $datos_aux["cobd01_contratoobras_cuerpo"]["fielcumplimiento_cancelado"];
  $laboral_cancelado                  = $datos_aux["cobd01_contratoobras_cuerpo"]["laboral_cancelado"];
  $numero_buenapro                    = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_buenapro"];
  $fecha_buenapro                     = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_buenapro"];
  $numero_fianza_anticipo             = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_fianza_anticipo"];
  $fecha_fianza_anticipo              = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_fianza_anticipo"];
  $numero_fianza_fielcumplimiento     = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_fianza_fielcumplimiento"];
  $fecha_fianza_fielcumplimiento      = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_fianza_fielcumplimiento"];
  $numero_fianza_calidad              = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_fianza_calidad"];
  $fecha_fianza_calidad               = $datos_aux["cobd01_contratoobras_cuerpo"]["fecha_fianza_calidad"];
  $numero_asiento_anulacion           = $datos_aux["cobd01_contratoobras_cuerpo"]["numero_asiento_anulacion"];
  $saldo_ano_anterior                 = $datos_aux["cobd01_contratoobras_cuerpo"]["saldo_ano_anterior"];


     $ano_documento_aux    = substr($fecha_contrato_obra,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_contrato_obra=$fecha_proceso_registro; }


$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_contrato_obra='".$ano_contrato_obra."' and  numero_contrato_obra='".$numero_contrato_obra."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                $to       = 2,
                                                                $td       = 7,
                                                                $rif_doc  = $rif,
                                                                $ano_dc   = $ano_contrato_obra,
                                                                $n_dc     = $numero_contrato_obra,
                                                                $f_dc     = $fecha_proceso_anulacion,
                                                                $cpt_dc   = $denominacion_obra,
                                                                $ben_dc   = null,
                                                                $mon_dc   = array("monto"=>$monto_original_contrato),
                                                                $ano_op   = null,
                                                                $n_op     = null,
                                                                $f_op     = null,
                                                                $a_adj_op = null,
                                                                $n_adj_op = null,
                                                                $f_adj_op = null,
                                                                $tp_op    = null,
                                                                $deno_ban_pago   = null,
                                                                $ano_movimiento  = null,
                                                                $cod_ent_pago    = null,
                                                                $cod_suc_pago    = null,
                                                                $cod_cta_pago    = null,
                                                                $num_che_o_debi  = null,
                                                                $fec_che_o_debi  = null,
                                                                $clas_che_o_debi = null
                                                                );
}


}//fin foreach





/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/

 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_obras_modificacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function
















function reactualizacion_compromisos_obras_modificacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros contrato de obra modificación", 1);



	$datos = $this->cobd01_co_modificacion_cuerpo->findAll($this->condicionNDEP()." and  ano_contrato_obra='".$ano_select."'  ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


  $ano_contrato_obra              = $datos_aux["cobd01_co_modificacion_cuerpo"]["ano_contrato_obra"];
  $numero_contrato_obra           = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_contrato_obra"];
  $numero_modificacion            = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_modificacion"];
  $tipo_modificacion              = $datos_aux["cobd01_co_modificacion_cuerpo"]["tipo_modificacion"];
  $monto_modificacion             = $datos_aux["cobd01_co_modificacion_cuerpo"]["monto_modificacion"];
  $fecha_modificacion             =  cambiar_formato_fecha($datos_aux["cobd01_co_modificacion_cuerpo"]["fecha_modificacion"]);
  $observaciones                  = $datos_aux["cobd01_co_modificacion_cuerpo"]["observaciones"];
  $dia_asiento_registro           = $datos_aux["cobd01_co_modificacion_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro           = $datos_aux["cobd01_co_modificacion_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro           = $datos_aux["cobd01_co_modificacion_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro        = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_asiento_registro"];
  $fecha_proceso_registro         =  cambiar_formato_fecha($datos_aux["cobd01_co_modificacion_cuerpo"]["fecha_proceso_registro"]);
  $username_registro              = $datos_aux["cobd01_co_modificacion_cuerpo"]["username_registro"];
  $condicion_actividad            = $datos_aux["cobd01_co_modificacion_cuerpo"]["condicion_actividad"];
  $ano_anulacion                  = $datos_aux["cobd01_co_modificacion_cuerpo"]["ano_anulacion"];
  $numero_anulacion               = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion          = $datos_aux["cobd01_co_modificacion_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion          = $datos_aux["cobd01_co_modificacion_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion          = $datos_aux["cobd01_co_modificacion_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion       = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_asiento_anulacion"];
  $fecha_proceso_anulacion        = $datos_aux["cobd01_co_modificacion_cuerpo"]["fecha_proceso_anulacion"];
  $username_anulacion             = $datos_aux["cobd01_co_modificacion_cuerpo"]["username_anulacion"];
  $aumento_obra_extra             = $datos_aux["cobd01_co_modificacion_cuerpo"]["aumento_obra_extra"];
  $aumento_reconsideracion_precio = $datos_aux["cobd01_co_modificacion_cuerpo"]["aumento_reconsideracion_precio"];
  $aumento_obras                  = $datos_aux["cobd01_co_modificacion_cuerpo"]["aumento_obras"];


  $rif                      =                        $this->cobd01_contratoobras_cuerpo->field('rif',                 $condicion." and ano_contrato_obra='".$ano_contrato_obra."'  and numero_contrato_obra='".$numero_contrato_obra."' ");
  $fecha_contrato_obra      =  cambiar_formato_fecha($this->cobd01_contratoobras_cuerpo->field('fecha_contrato_obra', $condicion." and ano_contrato_obra='".$ano_contrato_obra."'  and numero_contrato_obra='".$numero_contrato_obra."' "));

    $ano_documento_aux    = substr($fecha_modificacion,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_modificacion=$fecha_proceso_registro; }


$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_contrato_obra='".$ano_contrato_obra."' and  numero_contrato_obra='".$numero_contrato_obra."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
	                                                              $to=1,
	                                                              $td=11,
	                                                              $rif_doc = $rif,

	                                                              $ano_dc  = $ano_contrato_obra,
	                                                              $n_dc    = $numero_contrato_obra,

	                                                              $f_dc    = $fecha_contrato_obra,
	                                                              $cpt_dc  = $observaciones,
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

}



}//fin foreach





/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/

 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_obras_modificacion_anulacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');




}//fin function











function reactualizacion_compromisos_obras_modificacion_anulacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros contrato de obra modificación anulación", 1);



	$datos = $this->cobd01_co_modificacion_cuerpo->findAll($this->condicionNDEP()." and  ano_contrato_obra='".$ano_select."' and condicion_actividad=2 ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cobd01_co_modificacion_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


  $ano_contrato_obra              = $datos_aux["cobd01_co_modificacion_cuerpo"]["ano_contrato_obra"];
  $numero_contrato_obra           = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_contrato_obra"];
  $numero_modificacion            = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_modificacion"];
  $tipo_modificacion              = $datos_aux["cobd01_co_modificacion_cuerpo"]["tipo_modificacion"];
  $monto_modificacion             = $datos_aux["cobd01_co_modificacion_cuerpo"]["monto_modificacion"];
  $fecha_modificacion             =  cambiar_formato_fecha($datos_aux["cobd01_co_modificacion_cuerpo"]["fecha_modificacion"]);
  $observaciones                  = $datos_aux["cobd01_co_modificacion_cuerpo"]["observaciones"];
  $dia_asiento_registro           = $datos_aux["cobd01_co_modificacion_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro           = $datos_aux["cobd01_co_modificacion_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro           = $datos_aux["cobd01_co_modificacion_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro        = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_asiento_registro"];
  $fecha_proceso_registro         =  cambiar_formato_fecha($datos_aux["cobd01_co_modificacion_cuerpo"]["fecha_proceso_registro"]);
  $username_registro              = $datos_aux["cobd01_co_modificacion_cuerpo"]["username_registro"];
  $condicion_actividad            = $datos_aux["cobd01_co_modificacion_cuerpo"]["condicion_actividad"];
  $ano_anulacion                  = $datos_aux["cobd01_co_modificacion_cuerpo"]["ano_anulacion"];
  $numero_anulacion               = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion          = $datos_aux["cobd01_co_modificacion_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion          = $datos_aux["cobd01_co_modificacion_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion          = $datos_aux["cobd01_co_modificacion_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion       = $datos_aux["cobd01_co_modificacion_cuerpo"]["numero_asiento_anulacion"];
  $fecha_proceso_anulacion        =  cambiar_formato_fecha($datos_aux["cobd01_co_modificacion_cuerpo"]["fecha_proceso_anulacion"]);
  $username_anulacion             = $datos_aux["cobd01_co_modificacion_cuerpo"]["username_anulacion"];
  $aumento_obra_extra             = $datos_aux["cobd01_co_modificacion_cuerpo"]["aumento_obra_extra"];
  $aumento_reconsideracion_precio = $datos_aux["cobd01_co_modificacion_cuerpo"]["aumento_reconsideracion_precio"];
  $aumento_obras                  = $datos_aux["cobd01_co_modificacion_cuerpo"]["aumento_obras"];


  $rif                      =                        $this->cobd01_contratoobras_cuerpo->field('rif',                 $condicion." and ano_contrato_obra='".$ano_contrato_obra."'  and numero_contrato_obra='".$numero_contrato_obra."' ");
  $fecha_contrato_obra      =  cambiar_formato_fecha($this->cobd01_contratoobras_cuerpo->field('fecha_contrato_obra', $condicion." and ano_contrato_obra='".$ano_contrato_obra."'  and numero_contrato_obra='".$numero_contrato_obra."' "));

    $ano_documento_aux    = substr($fecha_modificacion,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_modificacion=$fecha_proceso_registro; }



$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_contrato_obra='".$ano_contrato_obra."' and  numero_contrato_obra='".$numero_contrato_obra."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
	                                                              $to=2,
	                                                              $td=11,
	                                                              $rif_doc = $rif,

	                                                              $ano_dc  = $ano_contrato_obra,
	                                                              $n_dc    = $numero_contrato_obra,

	                                                              $f_dc    = $fecha_contrato_obra,
	                                                              $cpt_dc  = $observaciones,
	                                                              $ben_dc  = null,

	                                                              $mon_dc=array("monto"=>$monto_modificacion),

	                                                              $ano_op               = null,
	                                                              $n_op                 = null,
	                                                              $f_op                 = null,
	                                                              $a_adj_op             = null,
	                                                              $n_adj_op             = $numero_modificacion,
	                                                              $f_adj_op             = $fecha_proceso_anulacion,
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

}




}//fin foreach



/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/



 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_servicio');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function














function reactualizacion_compromisos_servicio($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros contrato de servicio", 1);



	$datos = $this->cepd02_contratoservicio_cuerpo->findAll($this->condicionNDEP()." and  ano_contrato_servicio='".$ano_select."'  ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


  $ano_contrato_servicio               = $datos_aux["cepd02_contratoservicio_cuerpo"]["ano_contrato_servicio"];
  $numero_contrato_servicio            = $datos_aux["cepd02_contratoservicio_cuerpo"]["numero_contrato_servicio"];
  $codigo_prod_serv                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["codigo_prod_serv"];
  $cod_dir_superior                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_dir_superior"];
  $cod_coordinacion                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_coordinacion"];
  $cod_secretaria                      = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_secretaria"];
  $cod_direccion                       = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_direccion"];
  $rif                                 = $datos_aux["cepd02_contratoservicio_cuerpo"]["rif"];
  $concepto                            = $datos_aux["cepd02_contratoservicio_cuerpo"]["concepto"];
  $fecha_contrato_servicio             = cambiar_formato_fecha($datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]);
  $fecha_inicio_contrato               = $datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_inicio_contrato"];
  $fecha_terminacion_contrato          = $datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_terminacion_contrato"];
  $monto_original_contrato             = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_original_contrato"];
  $aumento                             = $datos_aux["cepd02_contratoservicio_cuerpo"]["aumento"];
  $disminucion                         = $datos_aux["cepd02_contratoservicio_cuerpo"]["disminucion"];
  $monto_anticipo                      = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_anticipo"];
  $monto_amortizacion                  = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_amortizacion"];
  $monto_retencion_laboral             = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_retencion_laboral"];
  $monto_retencion_fielcumplimiento    = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_retencion_fielcumplimient"];
  $monto_cancelado                     = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_cancelado"];
  $porcentaje_iva                      = $datos_aux["cepd02_contratoservicio_cuerpo"]["porcentaje_iva"];
  $porcentaje_anticipo                 = $datos_aux["cepd02_contratoservicio_cuerpo"]["porcentaje_anticipo"];
  $anticipo_con_iva                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["anticipo_con_iva"];
  $fecha_proceso_registro              = cambiar_formato_fecha($datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro                = $datos_aux["cepd02_contratoservicio_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro                = $datos_aux["cepd02_contratoservicio_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro                = $datos_aux["cepd02_contratoservicio_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro             = $datos_aux["cepd02_contratoservicio_cuerpo"]["numero_asiento_registro"];
  $username_registro                   = $datos_aux["cepd02_contratoservicio_cuerpo"]["username_registro"];
  $condicion_actividad                 = $datos_aux["cepd02_contratoservicio_cuerpo"]["condicion_actividad"];
  $ano_anulacion                       = $datos_aux["cepd02_contratoservicio_cuerpo"]["ano_anulacion"];
  $numero_anulacion                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion               = $datos_aux["cepd02_contratoservicio_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion               = $datos_aux["cepd02_contratoservicio_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion               = $datos_aux["cepd02_contratoservicio_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion            = $datos_aux["cepd02_contratoservicio_cuerpo"]["numero_asiento_anulacion"];
  $fecha_proceso_anulacion             = cambiar_formato_fecha($datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_proceso_anulacion"]);
  $username_anulacion                  = $datos_aux["cepd02_contratoservicio_cuerpo"]["username_anulacion"];
  $laboral_cancelado                   = $datos_aux["cepd02_contratoservicio_cuerpo"]["laboral_cancelado"];
  $fielcumplimiento_cancelado          = $datos_aux["cepd02_contratoservicio_cuerpo"]["fielcumplimiento_cancelado"];
  $saldo_ano_anterior                  = $datos_aux["cepd02_contratoservicio_cuerpo"]["saldo_ano_anterior"];

     $ano_documento_aux    = substr($fecha_contrato_servicio,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_contrato_servicio=$fecha_proceso_registro; }

$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_contrato_servicio='".$ano_contrato_servicio."' and  numero_contrato_servicio='".$numero_contrato_servicio."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                  $to       = 1,
                                                                  $td       = 8,
                                                                  $rif_doc  = $rif,
                                                                  $ano_dc   = $ano_contrato_servicio,
                                                                  $n_dc     = $numero_contrato_servicio,
                                                                  $f_dc     = $fecha_contrato_servicio,
                                                                  $cpt_dc   = $concepto,
                                                                  $ben_dc   = null,
                                                                  $mon_dc   = array("monto"=>$monto_original_contrato),
                                                                  $ano_op   = null,
                                                                  $n_op     = null,
                                                                  $f_op     = null,
                                                                  $a_adj_op = null,
                                                                  $n_adj_op = null,
                                                                  $f_adj_op = null,
                                                                  $tp_op    = null,
                                                                  $deno_ban_pago   = null,
                                                                  $ano_movimiento  = null,
                                                                  $cod_ent_pago    = null,
                                                                  $cod_suc_pago    = null,
                                                                  $cod_cta_pago    = null,
                                                                  $num_che_o_debi  = null,
                                                                  $fec_che_o_debi  = null,
                                                                  $clas_che_o_debi = null);


}


}//fin foreach





/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/

 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_servicio_anulacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function










function reactualizacion_compromisos_servicio_anulacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros contrato de servicio anulación", 1);

	$datos = $this->cepd02_contratoservicio_cuerpo->findAll($this->condicionNDEP()." and  ano_contrato_servicio='".$ano_select."' and condicion_actividad=2 ");

    $total_suma = count($datos);
    $cadena     = "";

foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


  $ano_contrato_servicio               = $datos_aux["cepd02_contratoservicio_cuerpo"]["ano_contrato_servicio"];
  $numero_contrato_servicio            = $datos_aux["cepd02_contratoservicio_cuerpo"]["numero_contrato_servicio"];
  $codigo_prod_serv                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["codigo_prod_serv"];
  $cod_dir_superior                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_dir_superior"];
  $cod_coordinacion                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_coordinacion"];
  $cod_secretaria                      = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_secretaria"];
  $cod_direccion                       = $datos_aux["cepd02_contratoservicio_cuerpo"]["cod_direccion"];
  $rif                                 = $datos_aux["cepd02_contratoservicio_cuerpo"]["rif"];
  $concepto                            = $datos_aux["cepd02_contratoservicio_cuerpo"]["concepto"];
  $fecha_contrato_servicio             = cambiar_formato_fecha($datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]);
  $fecha_inicio_contrato               = $datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_inicio_contrato"];
  $fecha_terminacion_contrato          = $datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_terminacion_contrato"];
  $monto_original_contrato             = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_original_contrato"];
  $aumento                             = $datos_aux["cepd02_contratoservicio_cuerpo"]["aumento"];
  $disminucion                         = $datos_aux["cepd02_contratoservicio_cuerpo"]["disminucion"];
  $monto_anticipo                      = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_anticipo"];
  $monto_amortizacion                  = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_amortizacion"];
  $monto_retencion_laboral             = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_retencion_laboral"];
  $monto_retencion_fielcumplimiento    = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_retencion_fielcumplimient"];
  $monto_cancelado                     = $datos_aux["cepd02_contratoservicio_cuerpo"]["monto_cancelado"];
  $porcentaje_iva                      = $datos_aux["cepd02_contratoservicio_cuerpo"]["porcentaje_iva"];
  $porcentaje_anticipo                 = $datos_aux["cepd02_contratoservicio_cuerpo"]["porcentaje_anticipo"];
  $anticipo_con_iva                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["anticipo_con_iva"];
  $fecha_proceso_registro              = cambiar_formato_fecha($datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro                = $datos_aux["cepd02_contratoservicio_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro                = $datos_aux["cepd02_contratoservicio_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro                = $datos_aux["cepd02_contratoservicio_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro             = $datos_aux["cepd02_contratoservicio_cuerpo"]["numero_asiento_registro"];
  $username_registro                   = $datos_aux["cepd02_contratoservicio_cuerpo"]["username_registro"];
  $condicion_actividad                 = $datos_aux["cepd02_contratoservicio_cuerpo"]["condicion_actividad"];
  $ano_anulacion                       = $datos_aux["cepd02_contratoservicio_cuerpo"]["ano_anulacion"];
  $numero_anulacion                    = $datos_aux["cepd02_contratoservicio_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion               = $datos_aux["cepd02_contratoservicio_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion               = $datos_aux["cepd02_contratoservicio_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion               = $datos_aux["cepd02_contratoservicio_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion            = $datos_aux["cepd02_contratoservicio_cuerpo"]["numero_asiento_anulacion"];
  $fecha_proceso_anulacion             = cambiar_formato_fecha($datos_aux["cepd02_contratoservicio_cuerpo"]["fecha_proceso_anulacion"]);
  $username_anulacion                  = $datos_aux["cepd02_contratoservicio_cuerpo"]["username_anulacion"];
  $laboral_cancelado                   = $datos_aux["cepd02_contratoservicio_cuerpo"]["laboral_cancelado"];
  $fielcumplimiento_cancelado          = $datos_aux["cepd02_contratoservicio_cuerpo"]["fielcumplimiento_cancelado"];
  $saldo_ano_anterior                  = $datos_aux["cepd02_contratoservicio_cuerpo"]["saldo_ano_anterior"];

     $ano_documento_aux    = substr($fecha_contrato_servicio,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_contrato_servicio=$fecha_proceso_registro; }



$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_contrato_servicio='".$ano_contrato_servicio."' and  numero_contrato_servicio='".$numero_contrato_servicio."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                  $to       = 2,
                                                                  $td       = 8,
                                                                  $rif_doc  = $rif,
                                                                  $ano_dc   = $ano_contrato_servicio,
                                                                  $n_dc     = $numero_contrato_servicio,
                                                                  $f_dc     = $fecha_proceso_anulacion,
                                                                  $cpt_dc   = $concepto,
                                                                  $ben_dc   = null,
                                                                  $mon_dc   = array("monto"=>$monto_original_contrato),
                                                                  $ano_op   = null,
                                                                  $n_op     = null,
                                                                  $f_op     = null,
                                                                  $a_adj_op = null,
                                                                  $n_adj_op = null,
                                                                  $f_adj_op = null,
                                                                  $tp_op    = null,
                                                                  $deno_ban_pago   = null,
                                                                  $ano_movimiento  = null,
                                                                  $cod_ent_pago    = null,
                                                                  $cod_suc_pago    = null,
                                                                  $cod_cta_pago    = null,
                                                                  $num_che_o_debi  = null,
                                                                  $fec_che_o_debi  = null,
                                                                  $clas_che_o_debi = null);

}




}//fin foreach




/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/


 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_servicio_modificacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function











function reactualizacion_compromisos_servicio_modificacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros contrato de servicio modificación", 1);



	$datos = $this->cepd02_cs_modificacion_cuerpo->findAll($this->condicionNDEP()." and  ano_contrato_servicio='".$ano_select."'  ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_contrato_servicio    = $datos_aux["cepd02_cs_modificacion_cuerpo"]["ano_contrato_servicio"];
  $numero_contrato_servicio = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_contrato_servicio"];
  $numero_modificacion      = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_modificacion"];
  $tipo_modificacion        = $datos_aux["cepd02_cs_modificacion_cuerpo"]["tipo_modificacion"];
  $monto_modificacion       = $datos_aux["cepd02_cs_modificacion_cuerpo"]["monto_modificacion"];
  $fecha_modificacion       = cambiar_formato_fecha($datos_aux["cepd02_cs_modificacion_cuerpo"]["fecha_modificacion"]);
  $observaciones            = $datos_aux["cepd02_cs_modificacion_cuerpo"]["observaciones"];
  $dia_asiento_registro     = $datos_aux["cepd02_cs_modificacion_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cepd02_cs_modificacion_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cepd02_cs_modificacion_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_asiento_registro"];
  $fecha_proceso_registro   = cambiar_formato_fecha($datos_aux["cepd02_cs_modificacion_cuerpo"]["fecha_proceso_registro"]);
  $username_registro        = $datos_aux["cepd02_cs_modificacion_cuerpo"]["username_registro"];
  $condicion_actividad      = $datos_aux["cepd02_cs_modificacion_cuerpo"]["condicion_actividad"];
  $ano_anulacion            = $datos_aux["cepd02_cs_modificacion_cuerpo"]["ano_anulacion"];
  $numero_anulacion         = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion    = $datos_aux["cepd02_cs_modificacion_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cepd02_cs_modificacion_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cepd02_cs_modificacion_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_asiento_anulacion"];
  $fecha_proceso_anulacion  = cambiar_formato_fecha($datos_aux["cepd02_cs_modificacion_cuerpo"]["fecha_proceso_anulacion"]);
  $username_anulacion       = $datos_aux["cepd02_cs_modificacion_cuerpo"]["username_anulacion"];

  $rif                      =                        $this->cepd02_contratoservicio_cuerpo->field('rif',                     $condicion." and ano_contrato_servicio='".$ano_contrato_servicio."'  and numero_contrato_servicio='".$numero_contrato_servicio."' ");
  $fecha_contrato_servicio  =  cambiar_formato_fecha($this->cepd02_contratoservicio_cuerpo->field('fecha_contrato_servicio', $condicion." and ano_contrato_servicio='".$ano_contrato_servicio."'  and numero_contrato_servicio='".$numero_contrato_servicio."' "));



     $ano_documento_aux    = substr($fecha_modificacion,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_modificacion=$fecha_proceso_registro; }



$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_contrato_servicio='".$ano_contrato_servicio."' and  numero_contrato_servicio='".$numero_contrato_servicio."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
	                                                              $to=1,
	                                                              $td=12,
	                                                              $rif_doc = $rif,

	                                                              $ano_dc  = $ano_contrato_servicio,
	                                                              $n_dc    = $numero_contrato_servicio,

	                                                              $f_dc    = $fecha_contrato_servicio,
	                                                              $cpt_dc  = $observaciones,
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
}


}//fin foreach




/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/


 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_compromisos_servicio_modificacion_anulacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function













function reactualizacion_compromisos_servicio_modificacion_anulacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros contrato de servicio modificación anulación", 1);



	$datos = $this->cepd02_cs_modificacion_cuerpo->findAll($this->condicionNDEP()." and  ano_contrato_servicio='".$ano_select."' and condicion_actividad=2 ");

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

	    $motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cepd02_cs_modificacion_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_contrato_servicio    = $datos_aux["cepd02_cs_modificacion_cuerpo"]["ano_contrato_servicio"];
  $numero_contrato_servicio = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_contrato_servicio"];
  $numero_modificacion      = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_modificacion"];
  $tipo_modificacion        = $datos_aux["cepd02_cs_modificacion_cuerpo"]["tipo_modificacion"];
  $monto_modificacion       = $datos_aux["cepd02_cs_modificacion_cuerpo"]["monto_modificacion"];
  $fecha_modificacion       = cambiar_formato_fecha($datos_aux["cepd02_cs_modificacion_cuerpo"]["fecha_modificacion"]);
  $observaciones            = $datos_aux["cepd02_cs_modificacion_cuerpo"]["observaciones"];
  $dia_asiento_registro     = $datos_aux["cepd02_cs_modificacion_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cepd02_cs_modificacion_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cepd02_cs_modificacion_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_asiento_registro"];
  $fecha_proceso_registro   = cambiar_formato_fecha($datos_aux["cepd02_cs_modificacion_cuerpo"]["fecha_proceso_registro"]);
  $username_registro        = $datos_aux["cepd02_cs_modificacion_cuerpo"]["username_registro"];
  $condicion_actividad      = $datos_aux["cepd02_cs_modificacion_cuerpo"]["condicion_actividad"];
  $ano_anulacion            = $datos_aux["cepd02_cs_modificacion_cuerpo"]["ano_anulacion"];
  $numero_anulacion         = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_anulacion"];
  $dia_asiento_anulacion    = $datos_aux["cepd02_cs_modificacion_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cepd02_cs_modificacion_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cepd02_cs_modificacion_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cepd02_cs_modificacion_cuerpo"]["numero_asiento_anulacion"];
  $fecha_proceso_anulacion  = cambiar_formato_fecha($datos_aux["cepd02_cs_modificacion_cuerpo"]["fecha_proceso_anulacion"]);
  $username_anulacion       = $datos_aux["cepd02_cs_modificacion_cuerpo"]["username_anulacion"];

  $rif                      =                        $this->cepd02_contratoservicio_cuerpo->field('rif',                     $condicion." and ano_contrato_servicio='".$ano_contrato_servicio."'  and numero_contrato_servicio='".$numero_contrato_servicio."' ");
  $fecha_contrato_servicio  =  cambiar_formato_fecha($this->cepd02_contratoservicio_cuerpo->field('fecha_contrato_servicio', $condicion." and ano_contrato_servicio='".$ano_contrato_servicio."'  and numero_contrato_servicio='".$numero_contrato_servicio."' "));



     $ano_documento_aux    = substr($fecha_modificacion,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_modificacion=$fecha_proceso_registro; }



$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$condicion." and  ano_contrato_servicio='".$ano_contrato_servicio."' and  numero_contrato_servicio='".$numero_contrato_servicio."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
	                                                              $to=2,
	                                                              $td=12,
	                                                              $rif_doc = $rif,

	                                                              $ano_dc  = $ano_contrato_servicio,
	                                                              $n_dc    = $numero_contrato_servicio,

	                                                              $f_dc    = $fecha_contrato_servicio,
	                                                              $cpt_dc  = $observaciones,
	                                                              $ben_dc  = null,

	                                                              $mon_dc=array("monto"=>$monto_modificacion),

	                                                              $ano_op               = null,
	                                                              $n_op                 = null,
	                                                              $f_op                 = null,
	                                                              $a_adj_op             = null,
	                                                              $n_adj_op             = $numero_modificacion,
	                                                              $f_adj_op             = $fecha_proceso_anulacion,
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

}




}//fin foreach




/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/



 	$this->set('pagina',    null);
 	$this->set('siguiente', 'reactualizacion_causado_op/1');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function




















function reactualizacion_causado_op($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros de Orden Pago Pag:".$pagina."/".$this->Session->read('total_pag_op_session_contabilidad'), 1);



	$datos = $this->cepd03_ordenpago_cuerpo->findAll($this->condicionNDEP()." and  ano_orden_pago='".$ano_select."'  ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_pago, numero_orden_pago ASC", 500, $pagina, null);

    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

		$contar_proceso++;
		$motor_activo=1;

		//extract($datos_aux["cepd03_ordenpago_cuerpo"]);

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_orden_pago                              =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
  $numero_orden_pago                           =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_orden_pago'];
  $tipo_orden                                  =    $datos_aux['cepd03_ordenpago_cuerpo']['tipo_orden'];
  $fecha_orden_pago                            =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_orden_pago'];
  $ano_documento_origen                        =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_documento_origen'];
  $numero_documento_origen                     =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_documento_origen'];
  $numero_documento_adjunto                    =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_documento_adjunto'];
  $cod_tipo_documento                          =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_tipo_documento'];
  $rif                                         =    $datos_aux['cepd03_ordenpago_cuerpo']['rif'];
  $beneficiario                                =    $datos_aux['cepd03_ordenpago_cuerpo']['beneficiario'];
  $autorizado                                  =    $datos_aux['cepd03_ordenpago_cuerpo']['autorizado'];
  $cedula_identidad                            =    $datos_aux['cepd03_ordenpago_cuerpo']['cedula_identidad'];
  $concepto                                    =    $datos_aux['cepd03_ordenpago_cuerpo']['concepto'];
  $monto_total                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_total'];
  $numero_pago                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_pago'];
  $monto_parcial                               =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_parcial'];
  $cod_frecuencia_pago                         =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_frecuencia_pago'];
  $fecha_desde                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_desde'];
  $fecha_hasta                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_hasta'];
  $cod_tipo_pago                               =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_tipo_pago'];
  $monto_coniva                                =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_coniva'];
  $monto_iva                                   =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_iva'];
  $porcentaje_iva                              =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_iva'];
  $monto_siniva                                =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_siniva'];
  $monto_retencion_laboral                     =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_retencion_laboral'];
  $monto_retencion_fielcumplimiento            =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_retencion_fielcumplimiento'];
  $monto_descontar_impuesto                    =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_descontar_impuesto'];
  $amortizacion_anticipo                       =    $datos_aux['cepd03_ordenpago_cuerpo']['amortizacion_anticipo'];
  $monto_orden_pago                            =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_orden_pago'];
  $monto_retencion_iva                         =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_retencion_iva'];
  $porcentaje_retencion_iva                    =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_retencion_iva'];
  $monto_islr                                  =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_islr'];
  $porcentaje_islr                             =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_islr'];
  $monto_sustraendo                            =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_sustraendo'];
  $monto_timbre_fiscal                         =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_timbre_fiscal'];
  $porcentaje_timbre_fiscal                    =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_timbre_fiscal'];
  $monto_impuesto_municipal                    =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_impuesto_municipal'];
  $porcentaje_impuesto_municipal               =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_impuesto_municipal'];
  $monto_neto_cobrar                           =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_neto_cobrar'];
  $dia_asiento_registro                        =    $datos_aux['cepd03_ordenpago_cuerpo']['dia_asiento_registro'];
  $mes_asiento_registro                        =    $datos_aux['cepd03_ordenpago_cuerpo']['mes_asiento_registro'];
  $ano_asiento_registro                        =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_asiento_registro'];
  $numero_asiento_registro                     =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_asiento_registro'];
  $username_registro                           =    $datos_aux['cepd03_ordenpago_cuerpo']['username_registro'];
  $condicion_actividad                         =    $datos_aux['cepd03_ordenpago_cuerpo']['condicion_actividad'];
  $ano_anulacion                               =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_anulacion'];
  $numero_anulacion                            =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_anulacion'];
  $dia_asiento_anulacion                       =    $datos_aux['cepd03_ordenpago_cuerpo']['dia_asiento_anulacion'];
  $mes_asiento_anulacion                       =    $datos_aux['cepd03_ordenpago_cuerpo']['mes_asiento_anulacion'];
  $ano_asiento_anulacion                       =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_asiento_anulacion'];
  $numero_asiento_anulacion                    =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_asiento_anulacion'];
  $username_anulacion                          =    $datos_aux['cepd03_ordenpago_cuerpo']['username_anulacion'];
  $cod_entidad_bancaria                        =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria'];
  $cod_sucursal                                =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_sucursal'];
  $cuenta_bancaria                             =    $datos_aux['cepd03_ordenpago_cuerpo']['cuenta_bancaria'];
  $numero_cheque_op                            =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_cheque'];
  $fecha_cheque                                =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_cheque'];
  $fecha_proceso_registro                      =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_proceso_registro'];
  $fecha_proceso_anulacion                     =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_proceso_anulacion'];
  $numero_comprobante_islr                     =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_islr'];
  $numero_comprobante_timbre                   =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_timbre'];
  $numero_comprobante_municipal                =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_municipal'];
  $numero_comprobante_iva                      =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_iva'];
  $numero_comprobante_librocompras             =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_librocompras'];

  $retencion_multa                             =    $datos_aux['cepd03_ordenpago_cuerpo']['retencion_multa'];
  $retencion_responsabilidad                   =    $datos_aux['cepd03_ordenpago_cuerpo']['retencion_responsabilidad'];

  if($retencion_multa==""){          $retencion_multa           = 0;}
  if($retencion_responsabilidad==""){$retencion_responsabilidad = 0;}




             if($cod_tipo_documento==1){//REGISTRO DE COMPROMISO

              $datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento='".$ano_documento_origen."' and numero_documento='".$numero_documento_origen."'   ");

              if(!isset($datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"])){$datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"]=$fecha_orden_pago;}

              $f_dc_adj_array_pago_aux = null;
              $f_dc_array_pago_aux     = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"];

         }else if($cod_tipo_documento==2){//Anticipo Orden de compra

               $datos  = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."' and numero_anticipo='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(     $this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"])){$datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"])){$datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"]=$fecha_orden_pago;}

               $f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"];
               $f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];

         }else if($cod_tipo_documento==3){//Autorización de Orden de compra

               $datos  = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."' and numero_pago='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(         $this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"])){$datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"])){$datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"];
               $f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];


         }else if($cod_tipo_documento==4){//Anticipo CONTRATO DE OBRA

               $datos  = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."' and numero_anticipo='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(         $this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"])){$datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"])){$datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"];
               $f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];


          }else if($cod_tipo_documento==5){//VALUACIÓN DE CONTRATO DE OBRA


               $datos  = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."' and numero_valuacion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"])){$datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"])){$datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"];
               $f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];


          }else if($cod_tipo_documento==6){//RETENCIÓN DE CONTRATO DE OBRA

               $datos  = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."' and numero_retencion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"])){$datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"])){$datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"];
               $f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];


          }else if($cod_tipo_documento==7){//Anticipo CONTRATO DE SERVICIO

               $datos  = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."' and numero_anticipo='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(         $this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"])){$datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"])){$datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"];
               $f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];


          }else if($cod_tipo_documento==8){//VALUACIÓN DE CONTRATO DE SERVICIO

               $datos  = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."' and numero_valuacion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"])){$datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"])){$datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"];
               $f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];


          }else if($cod_tipo_documento==9){//RETENCIÓN DE CONTRATO DE SERVICIO


               $datos  = $this->cepd02_contratoservicio_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."' and numero_retencion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"])){$datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"])){$datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"];
               $f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];


          }


$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0 && $cod_tipo_documento!=1){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$this->condicion()." and  ano_orden_pago='".$ano_orden_pago."' and  numero_orden_pago='".$numero_orden_pago."' \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

          $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                         $to              = 1,
                                                                         $td              = 9,
                                                                         $rif_doc         = $rif,
                                                                         $ano_dc          = $ano_documento_origen,
                                                                         $n_dc            = $numero_documento_origen,
                                                                         $f_dc            = cambia_fecha($f_dc_array_pago_aux),
                                                                         $cpt_dc          = $concepto,
                                                                         $ben_dc          = $autorizado,
                                                                         $mon_dc          = array("monto_total_orden"  => $monto_coniva,
                                                                                                  "monto_orden_pago"   => $monto_orden_pago,
                                                                                                  "monto_amortizacion" => $amortizacion_anticipo
                                                                                                 ),

                                                                         $ano_op          = $ano_orden_pago,
                                                                         $n_op            = $numero_orden_pago,
                                                                         $f_op            = cambia_fecha($fecha_orden_pago),

                                                                         $a_adj_op        = null,
                                                                         $n_adj_op        = $numero_documento_adjunto,
                                                                         $f_adj_op        = cambia_fecha($f_dc_adj_array_pago_aux),
                                                                         $tp_op           = $cod_tipo_documento,

                                                                         $deno_ban_pago   = null,
                                                                         $ano_movimiento  = null,
                                                                         $cod_ent_pago    = null,
                                                                         $cod_suc_pago    = null,
                                                                         $cod_cta_pago    = null,
                                                                         $num_che_o_debi  = null,
                                                                         $fec_che_o_debi  = null,
                                                                         $clas_che_o_debi = null
                                                                     );

}





}//fin foreach




/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/


 if($this->Session->read('total_pag_op_session_contabilidad')==$pagina || $this->Session->read('total_pag_op_session_contabilidad')==0){

 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_causado_op_anulacion');
 	//$this->set('termino',    true);


 }else{

 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'reactualizacion_causado_op');
 	//$this->set('termino',    true);

 }





 $this->render('vista_index');








}//fin function




















function reactualizacion_causado_op_anulacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros de Orden Pago anulados ", 1);



	$datos = $this->cepd03_ordenpago_cuerpo->findAll($this->condicionNDEP()." and ano_orden_pago='".$ano_select."' and condicion_actividad=2 ");

    $total_suma = count($datos);
    $cadena     = "";

//pr($datos);

foreach($datos as $datos_aux){

	//extract($datos_aux["cepd03_ordenpago_cuerpo"]);

		$contar_proceso++;
		$motor_activo=1;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cepd03_ordenpago_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_orden_pago                              =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_orden_pago'];
  $numero_orden_pago                           =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_orden_pago'];
  $tipo_orden                                  =    $datos_aux['cepd03_ordenpago_cuerpo']['tipo_orden'];
  $fecha_orden_pago                            =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_orden_pago'];
  $ano_documento_origen                        =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_documento_origen'];
  $numero_documento_origen                     =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_documento_origen'];
  $numero_documento_adjunto                    =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_documento_adjunto'];
  $cod_tipo_documento                          =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_tipo_documento'];
  $rif                                         =    $datos_aux['cepd03_ordenpago_cuerpo']['rif'];
  $beneficiario                                =    $datos_aux['cepd03_ordenpago_cuerpo']['beneficiario'];
  $autorizado                                  =    $datos_aux['cepd03_ordenpago_cuerpo']['autorizado'];
  $cedula_identidad                            =    $datos_aux['cepd03_ordenpago_cuerpo']['cedula_identidad'];
  $concepto                                    =    $datos_aux['cepd03_ordenpago_cuerpo']['concepto'];
  $monto_total                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_total'];
  $numero_pago                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_pago'];
  $monto_parcial                               =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_parcial'];
  $cod_frecuencia_pago                         =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_frecuencia_pago'];
  $fecha_desde                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_desde'];
  $fecha_hasta                                 =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_hasta'];
  $cod_tipo_pago                               =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_tipo_pago'];
  $monto_coniva                                =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_coniva'];
  $monto_iva                                   =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_iva'];
  $porcentaje_iva                              =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_iva'];
  $monto_siniva                                =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_siniva'];
  $monto_retencion_laboral                     =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_retencion_laboral'];
  $monto_retencion_fielcumplimiento            =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_retencion_fielcumplimiento'];
  $monto_descontar_impuesto                    =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_descontar_impuesto'];
  $amortizacion_anticipo                       =    $datos_aux['cepd03_ordenpago_cuerpo']['amortizacion_anticipo'];
  $monto_orden_pago                            =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_orden_pago'];
  $monto_retencion_iva                         =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_retencion_iva'];
  $porcentaje_retencion_iva                    =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_retencion_iva'];
  $monto_islr                                  =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_islr'];
  $porcentaje_islr                             =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_islr'];
  $monto_sustraendo                            =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_sustraendo'];
  $monto_timbre_fiscal                         =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_timbre_fiscal'];
  $porcentaje_timbre_fiscal                    =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_timbre_fiscal'];
  $monto_impuesto_municipal                    =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_impuesto_municipal'];
  $porcentaje_impuesto_municipal               =    $datos_aux['cepd03_ordenpago_cuerpo']['porcentaje_impuesto_municipal'];
  $monto_neto_cobrar                           =    $datos_aux['cepd03_ordenpago_cuerpo']['monto_neto_cobrar'];
  $dia_asiento_registro                        =    $datos_aux['cepd03_ordenpago_cuerpo']['dia_asiento_registro'];
  $mes_asiento_registro                        =    $datos_aux['cepd03_ordenpago_cuerpo']['mes_asiento_registro'];
  $ano_asiento_registro                        =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_asiento_registro'];
  $numero_asiento_registro                     =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_asiento_registro'];
  $username_registro                           =    $datos_aux['cepd03_ordenpago_cuerpo']['username_registro'];
  $condicion_actividad                         =    $datos_aux['cepd03_ordenpago_cuerpo']['condicion_actividad'];
  $ano_anulacion                               =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_anulacion'];
  $numero_anulacion                            =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_anulacion'];
  $dia_asiento_anulacion                       =    $datos_aux['cepd03_ordenpago_cuerpo']['dia_asiento_anulacion'];
  $mes_asiento_anulacion                       =    $datos_aux['cepd03_ordenpago_cuerpo']['mes_asiento_anulacion'];
  $ano_asiento_anulacion                       =    $datos_aux['cepd03_ordenpago_cuerpo']['ano_asiento_anulacion'];
  $numero_asiento_anulacion                    =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_asiento_anulacion'];
  $username_anulacion                          =    $datos_aux['cepd03_ordenpago_cuerpo']['username_anulacion'];
  $cod_entidad_bancaria                        =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria'];
  $cod_sucursal                                =    $datos_aux['cepd03_ordenpago_cuerpo']['cod_sucursal'];
  $cuenta_bancaria                             =    $datos_aux['cepd03_ordenpago_cuerpo']['cuenta_bancaria'];
  $numero_cheque_op                            =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_cheque'];
  $fecha_cheque                                =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_cheque'];
  $fecha_proceso_registro                      =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_proceso_registro'];
  $fecha_proceso_anulacion                     =    $datos_aux['cepd03_ordenpago_cuerpo']['fecha_proceso_anulacion'];
  $numero_comprobante_islr                     =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_islr'];
  $numero_comprobante_timbre                   =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_timbre'];
  $numero_comprobante_municipal                =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_municipal'];
  $numero_comprobante_iva                      =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_iva'];
  $numero_comprobante_librocompras             =    $datos_aux['cepd03_ordenpago_cuerpo']['numero_comprobante_librocompras'];

  $retencion_multa                             =    $datos_aux['cepd03_ordenpago_cuerpo']['retencion_multa'];
  $retencion_responsabilidad                   =    $datos_aux['cepd03_ordenpago_cuerpo']['retencion_responsabilidad'];

  if($retencion_multa==""){          $retencion_multa           = 0;}
  if($retencion_responsabilidad==""){$retencion_responsabilidad = 0;}




               if($cod_tipo_documento==1){//REGISTRO DE COMPROMISO

              $datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento='".$ano_documento_origen."' and numero_documento='".$numero_documento_origen."'   ");

              if(!isset($datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"])){$datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"]=$fecha_orden_pago;}

              $f_dc_adj_array_pago_aux = null;
              $f_dc_array_pago_aux     = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"];

         }else if($cod_tipo_documento==2){//Anticipo Orden de compra

               $datos  = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."' and numero_anticipo='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(     $this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"])){$datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"])){$datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"]=$fecha_orden_pago;}

               $f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"];
               $f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];

         }else if($cod_tipo_documento==3){//Autorización de Orden de compra

               $datos  = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."' and numero_pago='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cscd04_ordencompra_encabezado->findAll(         $this->condicion()." and ano_orden_compra='".$ano_documento_origen."' and numero_orden_compra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"])){$datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"])){$datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"];
               $f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];


         }else if($cod_tipo_documento==4){//Anticipo CONTRATO DE OBRA

               $datos  = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."' and numero_anticipo='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(         $this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"])){$datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"])){$datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"];
               $f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];


          }else if($cod_tipo_documento==5){//VALUACIÓN DE CONTRATO DE OBRA


               $datos  = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."' and numero_valuacion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"])){$datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"])){$datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"];
               $f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];


          }else if($cod_tipo_documento==6){//RETENCIÓN DE CONTRATO DE OBRA

               $datos  = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."' and numero_retencion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento_origen."' and numero_contrato_obra='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"])){$datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"])){$datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"];
               $f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];


          }else if($cod_tipo_documento==7){//Anticipo CONTRATO DE SERVICIO

               $datos  = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."' and numero_anticipo='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(         $this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"])){$datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"])){$datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"];
               $f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];


          }else if($cod_tipo_documento==8){//VALUACIÓN DE CONTRATO DE SERVICIO

               $datos  = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."' and numero_valuacion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"])){$datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"])){$datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"];
               $f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];


          }else if($cod_tipo_documento==9){//RETENCIÓN DE CONTRATO DE SERVICIO


               $datos  = $this->cepd02_contratoservicio_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."' and numero_retencion='".$numero_documento_adjunto."'  ");
               $datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento_origen."' and numero_contrato_servicio='".$numero_documento_origen."'  ");

                if(!isset($datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"])){$datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"]=$fecha_orden_pago;}
                if(!isset($datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"])){$datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"]=$fecha_orden_pago;}


               $f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"];
               $f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];


           }//fin else if


$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0 && $cod_tipo_documento!=1){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif." NO ESTA EN LA TABLA cpcd02 ".$this->condicion()." and  ano_orden_pago='".$ano_orden_pago."' and  numero_orden_pago='".$numero_orden_pago."' \n";
	$cadena .= "\n \n";
}




if($motor_activo==1){

          $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                         $to              = 2,
                                                                         $td              = 9,
                                                                         $rif_doc         = $rif,
                                                                         $ano_dc          = $ano_documento_origen,
                                                                         $n_dc            = $numero_documento_origen,
                                                                         $f_dc            = cambia_fecha($f_dc_array_pago_aux),
                                                                         $cpt_dc          = $concepto,
                                                                         $ben_dc          = $autorizado,
                                                                         $mon_dc          = array("monto_total_orden"  => $monto_coniva,
                                                                                                  "monto_orden_pago"   => $monto_orden_pago,
                                                                                                  "monto_amortizacion" => $amortizacion_anticipo
                                                                                                 ),

                                                                         $ano_op          = $ano_orden_pago,
                                                                         $n_op            = $numero_orden_pago,
                                                                         $f_op            = cambia_fecha($fecha_proceso_anulacion),

                                                                         $a_adj_op        = null,
                                                                         $n_adj_op        = $numero_documento_adjunto,
                                                                         $f_adj_op        = cambia_fecha($f_dc_adj_array_pago_aux),
                                                                         $tp_op           = $cod_tipo_documento,

                                                                         $deno_ban_pago   = null,
                                                                         $ano_movimiento  = null,
                                                                         $cod_ent_pago    = null,
                                                                         $cod_suc_pago    = null,
                                                                         $cod_cta_pago    = null,
                                                                         $num_che_o_debi  = null,
                                                                         $fec_che_o_debi  = null,
                                                                         $clas_che_o_debi = null
                                                                     );

}


}//fin foreach



/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/


 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd03_cheque_cuerpo');
 	//$this->set('termino',    true);







 $this->render('vista_index');








}//fin function





























function reactualizacion_pagado_cstd03_cheque_cuerpo($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

     $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Pagado de cancelaciones Pag ".$pagina."/".$this->Session->read('total_pag_ch_session_contabilidad'), 1);


	$datos      = $this->cstd03_cheque_cuerpo->findAll($this->condicionNDEP()." and  ano_movimiento='".$ano_select."'  ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque ASC", 500, $pagina, null);
    $total_suma = count($datos);
    $cadena     = "";


foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cstd03_cheque_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cstd03_cheque_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cstd03_cheque_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cstd03_cheque_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cstd03_cheque_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_movimiento              = $datos_aux["cstd03_cheque_cuerpo"]["ano_movimiento"];
  $cod_entidad_bancaria        = $datos_aux["cstd03_cheque_cuerpo"]["cod_entidad_bancaria"];
  $cod_sucursal                = $datos_aux["cstd03_cheque_cuerpo"]["cod_sucursal"];
  $cuenta_bancaria             = $datos_aux["cstd03_cheque_cuerpo"]["cuenta_bancaria"];
  $numero_cheque               = $datos_aux["cstd03_cheque_cuerpo"]["numero_cheque"];
  $fecha_cheque                = cambia_fecha($datos_aux["cstd03_cheque_cuerpo"]["fecha_cheque"]);
  $beneficiario                = $datos_aux["cstd03_cheque_cuerpo"]["beneficiario"];
  $monto                       = $datos_aux["cstd03_cheque_cuerpo"]["monto"];
  $concepto                    = $datos_aux["cstd03_cheque_cuerpo"]["concepto"];
  $rif_cedula                  = $datos_aux["cstd03_cheque_cuerpo"]["rif_cedula"];
  $cod_tipo_pago               = $datos_aux["cstd03_cheque_cuerpo"]["cod_tipo_pago"];
  $status_cheque               = $datos_aux["cstd03_cheque_cuerpo"]["status_cheque"];
  $clase_beneficiario          = $datos_aux["cstd03_cheque_cuerpo"]["clase_beneficiario"];
  $fecha_proceso_registro      = cambia_fecha($datos_aux["cstd03_cheque_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro        = $datos_aux["cstd03_cheque_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro        = $datos_aux["cstd03_cheque_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro        = $datos_aux["cstd03_cheque_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro     = $datos_aux["cstd03_cheque_cuerpo"]["numero_asiento_registro"];
  $username_registro           = $datos_aux["cstd03_cheque_cuerpo"]["username_registro"];
  $condicion_actividad         = $datos_aux["cstd03_cheque_cuerpo"]["condicion_actividad"];
  $ano_anulacion               = $datos_aux["cstd03_cheque_cuerpo"]["ano_anulacion"];
  $numero_anulacion            = $datos_aux["cstd03_cheque_cuerpo"]["numero_anulacion"];
  $fecha_proceso_anulacion     = cambia_fecha($datos_aux["cstd03_cheque_cuerpo"]["fecha_proceso_anulacion"]);
  $dia_asiento_anulacion       = $datos_aux["cstd03_cheque_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion       = $datos_aux["cstd03_cheque_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion       = $datos_aux["cstd03_cheque_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion    = $datos_aux["cstd03_cheque_cuerpo"]["numero_asiento_anulacion"];
  $username_anulacion          = $datos_aux["cstd03_cheque_cuerpo"]["username_anulacion"];
  $numero_comprobante_egreso   = $datos_aux["cstd03_cheque_cuerpo"]["numero_comprobante_egreso"];
  $ano_anterior                = $datos_aux["cstd03_cheque_cuerpo"]["ano_anterior"];


$contador_contabilidad=0;
$monto_retenciones["monto_neto_orden"]        = 0;
$monto_retenciones["monto_total_retenciones"] = 0;


$datos_orden_pago_cuerpo   = $this->v_cstd03_cheque_orden_pago->findAll($this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_cheque=".$numero_cheque);


$ano_dc_array_pago_aux   = array();
$n_dc_array_pago_aux     = array();
$n_dc_adj_array_pago_aux = array();
$f_dc_array_pago_aux     = array();
$ano_op_array_pago_aux   = array();
$n_op_array_pago_aux     = array();
$f_op_array_pago_aux     = array();
$tipo_op_array_pago_aux  = array();
$f_dc_adj_array_pago_aux = array();

$ano_dc_aux  = "";
$n_dc_aux    = "";
$f_dc_aux    = "";

$ano_op_aux   = "";
$n_op_aux     = "";
$f_op_aux     = "";

$a_adj_op_aux = null;
$n_adj_op_aux = "";
$f_adj_op_aux = "";
$tp_op_aux    = "";

$tipo_bandera     = 0;
$motor_activo     = 1;



				foreach($datos_orden_pago_cuerpo as $contabilidad_ve_contabilidad){

							  $contabilidad_contabilidad_cod_presi                      =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_presi'];
							  $contabilidad_cod_entidad                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_entidad'];
							  $contabilidad_cod_tipo_inst                               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_tipo_inst'];
							  $contabilidad_cod_inst                                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_inst'];
							  $contabilidad_cod_dep                                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_dep'];
							  $contabilidad_ano_orden_pago                              =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_orden_pago'];
							  $contabilidad_numero_orden_pago                           =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_orden_pago'];
							  $contabilidad_tipo_orden                                  =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['tipo_orden'];
							  $contabilidad_fecha_orden_pago                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_orden_pago'];
							  $contabilidad_ano_documento_origen                        =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_documento_origen'];
							  $contabilidad_numero_documento_origen                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_documento_origen'];
							  $contabilidad_numero_documento_adjunto                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_documento_adjunto'];
							  $contabilidad_cod_tipo_documento                          =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_tipo_documento'];
							  $contabilidad_rif                                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['rif'];
							  $contabilidad_beneficiario                                =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['beneficiario'];
							  $contabilidad_autorizado                                  =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['autorizado'];
							  $contabilidad_cedula_identidad                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cedula_identidad'];
							  $contabilidad_concepto                                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['concepto'];
							  $contabilidad_monto_total                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_total'];
							  $contabilidad_numero_pago                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_pago'];
							  $contabilidad_monto_parcial                               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_parcial'];
							  $contabilidad_cod_frecuencia_pago                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_frecuencia_pago'];
							  $contabilidad_fecha_desde                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_desde'];
							  $contabilidad_fecha_hasta                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_hasta'];
							  $contabilidad_cod_tipo_pago                               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_tipo_pago'];
							  $contabilidad_monto_coniva                                =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_coniva'];
							  $contabilidad_monto_iva                                   =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_iva'];
							  $contabilidad_porcentaje_iva                              =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_iva'];
							  $contabilidad_monto_siniva                                =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_siniva'];
							  $contabilidad_monto_retencion_laboral                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_retencion_laboral'];
							  $contabilidad_monto_retencion_fielcumplimiento            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_retencion_fielcumplimiento'];
							  $contabilidad_monto_descontar_impuesto                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_descontar_impuesto'];
							  $contabilidad_amortizacion_anticipo                       =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['amortizacion_anticipo'];
							  $contabilidad_monto_orden_pago                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_orden_pago'];
							  $contabilidad_monto_retencion_iva                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_retencion_iva'];
							  $contabilidad_porcentaje_retencion_iva                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_retencion_iva'];
							  $contabilidad_monto_islr                                  =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_islr'];
							  $contabilidad_porcentaje_islr                             =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_islr'];
							  $contabilidad_monto_sustraendo                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_sustraendo'];
							  $contabilidad_monto_timbre_fiscal                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_timbre_fiscal'];
							  $contabilidad_porcentaje_timbre_fiscal                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_timbre_fiscal'];
							  $contabilidad_monto_impuesto_municipal                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_impuesto_municipal'];
							  $contabilidad_porcentaje_impuesto_municipal               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_impuesto_municipal'];
							  $contabilidad_monto_neto_cobrar                           =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_neto_cobrar'];
							  $contabilidad_dia_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['dia_asiento_registro'];
							  $contabilidad_mes_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['mes_asiento_registro'];
							  $contabilidad_ano_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_asiento_registro'];
							  $contabilidad_numero_asiento_registro                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_asiento_registro'];
							  $contabilidad_username_registro                           =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['username_registro'];
							  $contabilidad_condicion_actividad                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['condicion_actividad'];
							  $contabilidad_ano_anulacion                               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_anulacion'];
							  $contabilidad_numero_anulacion                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_anulacion'];
							  $contabilidad_dia_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['dia_asiento_anulacion'];
							  $contabilidad_mes_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['mes_asiento_anulacion'];
							  $contabilidad_ano_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_asiento_anulacion'];
							  $contabilidad_numero_asiento_anulacion                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_asiento_anulacion'];
							  $contabilidad_username_anulacion                          =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['username_anulacion'];
							  $contabilidad_fecha_proceso_registro                      =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_proceso_registro'];
							  $contabilidad_fecha_proceso_anulacion                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_proceso_anulacion'];
							  $contabilidad_numero_comprobante_islr                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_islr'];
							  $contabilidad_numero_comprobante_timbre                   =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_timbre'];
							  $contabilidad_numero_comprobante_municipal                =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_municipal'];
							  $contabilidad_numero_comprobante_iva                      =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_iva'];
							  $contabilidad_numero_comprobante_librocompras             =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_librocompras'];

							  $contabilidad_retencion_multa                             =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['retencion_multa'];
							  $contabilidad_retencion_responsabilidad                   =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['retencion_responsabilidad'];

							  if($contabilidad_retencion_multa==""){          $contabilidad_retencion_multa           = 0;}
							  if($contabilidad_retencion_responsabilidad==""){$contabilidad_retencion_responsabilidad = 0;}




				$contador_contabilidad++;


				                  if($contabilidad_cod_tipo_documento==1){//REGISTRO DE COMPROMISO

						              if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_documento_compromiso"]==""){
						              	 $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_documento_compromiso"]=$contabilidad_fecha_orden_pago;
						               }
						              $contabilidad_f_dc_adj_array_pago_aux = null;
						              $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_documento_compromiso"];

					         }else if($contabilidad_cod_tipo_documento==2){//Anticipo Orden de compra


						                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_compra"]==""){    $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_compra"]=$contabilidad_fecha_orden_pago;}
						                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"]=$contabilidad_fecha_orden_pago;}

						               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_compra"];
						               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"];

					         }else if($contabilidad_cod_tipo_documento==3){//Autorización de Orden de compra

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_autorizacion_compra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_autorizacion_compra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_autorizacion_compra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"];


					         }else if($contabilidad_cod_tipo_documento==4){//Anticipo CONTRATO DE OBRA

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_obra"]==""){     $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==5){//VALUACIÓN DE CONTRATO DE OBRA


					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_obra"]==""){    $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==6){//RETENCIÓN DE CONTRATO DE OBRA

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_obra"]==""){   $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==7){//Anticipo CONTRATO DE SERVICIO

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_servicio"]==""){          $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"];


					          }else if($contabilidad_cod_tipo_documento==8){//VALUACIÓN DE CONTRATO DE SERVICIO

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_servicio"]==""){        $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"];


					          }else if($contabilidad_cod_tipo_documento==9){//RETENCIÓN DE CONTRATO DE SERVICIO


					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_servicio"]==""){        $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"];


					          }//fin if



							if($contabilidad_monto_retencion_iva!=0){
								if(isset($monto_retenciones["monto_iva"])){
									 $monto_retenciones["monto_iva"] += $contabilidad_monto_retencion_iva;
								}else{
									 $monto_retenciones["monto_iva"]  = $contabilidad_monto_retencion_iva;
								}//fin else
				             }//fin if



							if($contabilidad_monto_islr!=0){
								if(isset($monto_retenciones["monto_islr"])){
									 $monto_retenciones["monto_islr"] += $contabilidad_monto_islr;
								}else{
									 $monto_retenciones["monto_islr"]  = $contabilidad_monto_islr;
								}//fin else
				           }//fin if



							if($contabilidad_monto_timbre_fiscal!=0){
									if(isset($monto_retenciones["monto_timbre"])){
										 $monto_retenciones["monto_timbre"] += $contabilidad_monto_timbre_fiscal;
									}else{
										 $monto_retenciones["monto_timbre"]  = $contabilidad_monto_timbre_fiscal;
									}//fin else
							}//fin if



							if($contabilidad_monto_impuesto_municipal!=0){
									if(isset($monto_retenciones["monto_municipal"])){
										 $monto_retenciones["monto_municipal"] += $contabilidad_monto_impuesto_municipal;
									}else{
										 $monto_retenciones["monto_municipal"]  = $contabilidad_monto_impuesto_municipal;
									}//fin else
						     }//fin if



							if($contabilidad_retencion_multa!=0){
									if(isset($monto_retenciones["monto_multa"])){
										 $monto_retenciones["monto_multa"] += $contabilidad_retencion_multa;
									}else{
										 $monto_retenciones["monto_multa"]  = $contabilidad_retencion_multa;
									}//fin else
							 }//fin if



							if($contabilidad_retencion_responsabilidad!=0){
									if(isset($monto_retenciones["monto_responsabilidad"])){
										 $monto_retenciones["monto_responsabilidad"] += $contabilidad_retencion_responsabilidad;
									}else{
										 $monto_retenciones["monto_responsabilidad"]  = $contabilidad_retencion_responsabilidad;
									}//fin else
						     }//fin if



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

						      if($tipo_bandera==0){
						      	  $tipo_bandera = $contabilidad_cod_tipo_documento;
						      }else{
						      	if($tipo_bandera!=$contabilidad_cod_tipo_documento && $clase_beneficiario==1){
						      		$motor_activo = 0;
						      		$cadena .= "ESTE CHEQUE POSEE ORDENES DE DIFERENTE TIPO DE DOCUMENTO \n";
						      		$cadena .= $this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_cheque=".$numero_cheque." \n";
						      	    $cadena .= "\n \n";
						      	}
						      }





						 $monto_retenciones["monto_neto_orden"]         += $contabilidad_monto_neto_cobrar;
				         $monto_retenciones["monto_total_retenciones"]  += $suma_retencion;

				}//fin foreach


    $c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
	$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];


unset($datos_orden_pago_cuerpo);


if($clase_beneficiario!=1){

	$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
															      $to      = 1,
															      $td      = 1,
															      $rif_doc = $rif_cedula,
															      $ano_dc  = null,
															      $n_dc    = null,
															      $f_dc    = null,
															      $cpt_dc  = $concepto,
															      $ben_dc  = $beneficiario,
															      $mon_dc  = array("monto_cheque"=>$monto),

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

															      $num_che_o_debi  = $numero_cheque,
															      $fec_che_o_debi  = $fecha_cheque,
															      $clas_che_o_debi = $clase_beneficiario,
															      $tipo_che_o_debi = 1,

															      $ano_dc_array_pago     = null,
															      $n_dc_array_pago       = null,
															      $n_dc_adj_array_pago   = null,
															      $f_dc_array_pago       = null,

															      $ano_op_array_pago  = null,
															      $n_op_array_pago    = null,
															      $f_op_array_pago    = null

															);


}else{



$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif_cedula."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0 && $clase_beneficiario==1 && $tipo_bandera!=1){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif_cedula." NO ESTA EN LA TABLA cpcd02 ".$this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_cheque=".$numero_cheque." \n";
	$cadena .= "\n \n";
}

if($tp_op_aux!="" && $motor_activo==1){

    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
															      $to      = 1,
															      $td      = 1,
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

															      $num_che_o_debi  = $numero_cheque,
															      $fec_che_o_debi  = $fecha_cheque,
															      $clas_che_o_debi = $clase_beneficiario,
															      $tipo_che_o_debi = 1,

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
}

unset($ano_dc_array_pago_aux);
unset($n_dc_array_pago_aux);
unset($n_dc_adj_array_pago_aux);
unset($f_dc_array_pago_aux);
unset($ano_op_array_pago_aux);
unset($n_op_array_pago_aux);
unset($f_op_array_pago_aux);
unset($tipo_op_array_pago_aux);
unset($f_dc_adj_array_pago_aux);


}//fin else




unset($monto_retenciones);



}//fin foreach



/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/






 if($this->Session->read('total_pag_ch_session_contabilidad')==$pagina){

 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd03_cheque_cuerpo_anulacion');
 	// $this->set('termino',    true);

 }else if($this->Session->read('total_pag_ch_session_contabilidad')==0){

 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd03_cheque_cuerpo_anulacion');
	// $this->set('termino',    true);
 }else{

 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd03_cheque_cuerpo');
 	//$this->set('termino',    true);

 }







 $this->render('vista_index');








}//fin function































function reactualizacion_pagado_cstd03_cheque_cuerpo_anulacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

     $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Pagado de cancelaciones anulación Pag ".$pagina."/".$this->Session->read('total_pag_ch_anulacion_session_contabilidad'), 1);


	$datos      = $this->cstd03_cheque_cuerpo->findAll($this->condicionNDEP()." and  ano_movimiento='".$ano_select."' and condicion_actividad=2 ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque ASC", 500, $pagina, null);
    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cstd03_cheque_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cstd03_cheque_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cstd03_cheque_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cstd03_cheque_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cstd03_cheque_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_movimiento              = $datos_aux["cstd03_cheque_cuerpo"]["ano_movimiento"];
  $cod_entidad_bancaria        = $datos_aux["cstd03_cheque_cuerpo"]["cod_entidad_bancaria"];
  $cod_sucursal                = $datos_aux["cstd03_cheque_cuerpo"]["cod_sucursal"];
  $cuenta_bancaria             = $datos_aux["cstd03_cheque_cuerpo"]["cuenta_bancaria"];
  $numero_cheque               = $datos_aux["cstd03_cheque_cuerpo"]["numero_cheque"];
  $fecha_cheque                = cambia_fecha($datos_aux["cstd03_cheque_cuerpo"]["fecha_cheque"]);
  $beneficiario                = $datos_aux["cstd03_cheque_cuerpo"]["beneficiario"];
  $monto                       = $datos_aux["cstd03_cheque_cuerpo"]["monto"];
  $concepto                    = $datos_aux["cstd03_cheque_cuerpo"]["concepto"];
  $rif_cedula                  = $datos_aux["cstd03_cheque_cuerpo"]["rif_cedula"];
  $cod_tipo_pago               = $datos_aux["cstd03_cheque_cuerpo"]["cod_tipo_pago"];
  $status_cheque               = $datos_aux["cstd03_cheque_cuerpo"]["status_cheque"];
  $clase_beneficiario          = $datos_aux["cstd03_cheque_cuerpo"]["clase_beneficiario"];
  $fecha_proceso_registro      = cambia_fecha($datos_aux["cstd03_cheque_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro        = $datos_aux["cstd03_cheque_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro        = $datos_aux["cstd03_cheque_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro        = $datos_aux["cstd03_cheque_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro     = $datos_aux["cstd03_cheque_cuerpo"]["numero_asiento_registro"];
  $username_registro           = $datos_aux["cstd03_cheque_cuerpo"]["username_registro"];
  $condicion_actividad         = $datos_aux["cstd03_cheque_cuerpo"]["condicion_actividad"];
  $ano_anulacion               = $datos_aux["cstd03_cheque_cuerpo"]["ano_anulacion"];
  $numero_anulacion            = $datos_aux["cstd03_cheque_cuerpo"]["numero_anulacion"];
  $fecha_proceso_anulacion     = cambia_fecha($datos_aux["cstd03_cheque_cuerpo"]["fecha_proceso_anulacion"]);
  $dia_asiento_anulacion       = $datos_aux["cstd03_cheque_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion       = $datos_aux["cstd03_cheque_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion       = $datos_aux["cstd03_cheque_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion    = $datos_aux["cstd03_cheque_cuerpo"]["numero_asiento_anulacion"];
  $username_anulacion          = $datos_aux["cstd03_cheque_cuerpo"]["username_anulacion"];
  $numero_comprobante_egreso   = $datos_aux["cstd03_cheque_cuerpo"]["numero_comprobante_egreso"];
  $ano_anterior                = $datos_aux["cstd03_cheque_cuerpo"]["ano_anterior"];


$contador_contabilidad=0;
$monto_retenciones["monto_neto_orden"]        = 0;
$monto_retenciones["monto_total_retenciones"] = 0;

$ano_dc_array_pago_aux   = array();
$n_dc_array_pago_aux     = array();
$n_dc_adj_array_pago_aux = array();
$f_dc_array_pago_aux     = array();
$ano_op_array_pago_aux   = array();
$n_op_array_pago_aux     = array();
$f_op_array_pago_aux     = array();
$tipo_op_array_pago_aux  = array();
$f_dc_adj_array_pago_aux = array();

$ano_dc_aux  = "";
$n_dc_aux    = "";
$f_dc_aux    = "";

$ano_op_aux   = "";
$n_op_aux     = "";
$f_op_aux     = "";

$a_adj_op_aux = null;
$n_adj_op_aux = "";
$f_adj_op_aux = "";
$tp_op_aux    = "";

$tipo_bandera     = 0;
$motor_activo     = 1;

$datos_orden_pago_cuerpo   = $this->v_cstd03_cheque_orden_pago->findAll($this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_cheque=".$numero_cheque);
$sql_aux_cheque            = $this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_cheque=".$numero_cheque;
				foreach($datos_orden_pago_cuerpo as $contabilidad_ve_contabilidad){

							  $contabilidad_contabilidad_cod_presi                      =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_presi'];
							  $contabilidad_cod_entidad                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_entidad'];
							  $contabilidad_cod_tipo_inst                               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_tipo_inst'];
							  $contabilidad_cod_inst                                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_inst'];
							  $contabilidad_cod_dep                                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_dep'];
							  $contabilidad_ano_orden_pago                              =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_orden_pago'];
							  $contabilidad_numero_orden_pago                           =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_orden_pago'];
							  $contabilidad_tipo_orden                                  =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['tipo_orden'];
							  $contabilidad_fecha_orden_pago                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_orden_pago'];
							  $contabilidad_ano_documento_origen                        =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_documento_origen'];
							  $contabilidad_numero_documento_origen                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_documento_origen'];
							  $contabilidad_numero_documento_adjunto                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_documento_adjunto'];
							  $contabilidad_cod_tipo_documento                          =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_tipo_documento'];
							  $contabilidad_rif                                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['rif'];
							  $contabilidad_beneficiario                                =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['beneficiario'];
							  $contabilidad_autorizado                                  =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['autorizado'];
							  $contabilidad_cedula_identidad                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cedula_identidad'];
							  $contabilidad_concepto                                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['concepto'];
							  $contabilidad_monto_total                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_total'];
							  $contabilidad_numero_pago                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_pago'];
							  $contabilidad_monto_parcial                               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_parcial'];
							  $contabilidad_cod_frecuencia_pago                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_frecuencia_pago'];
							  $contabilidad_fecha_desde                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_desde'];
							  $contabilidad_fecha_hasta                                 =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_hasta'];
							  $contabilidad_cod_tipo_pago                               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['cod_tipo_pago'];
							  $contabilidad_monto_coniva                                =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_coniva'];
							  $contabilidad_monto_iva                                   =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_iva'];
							  $contabilidad_porcentaje_iva                              =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_iva'];
							  $contabilidad_monto_siniva                                =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_siniva'];
							  $contabilidad_monto_retencion_laboral                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_retencion_laboral'];
							  $contabilidad_monto_retencion_fielcumplimiento            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_retencion_fielcumplimiento'];
							  $contabilidad_monto_descontar_impuesto                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_descontar_impuesto'];
							  $contabilidad_amortizacion_anticipo                       =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['amortizacion_anticipo'];
							  $contabilidad_monto_orden_pago                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_orden_pago'];
							  $contabilidad_monto_retencion_iva                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_retencion_iva'];
							  $contabilidad_porcentaje_retencion_iva                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_retencion_iva'];
							  $contabilidad_monto_islr                                  =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_islr'];
							  $contabilidad_porcentaje_islr                             =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_islr'];
							  $contabilidad_monto_sustraendo                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_sustraendo'];
							  $contabilidad_monto_timbre_fiscal                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_timbre_fiscal'];
							  $contabilidad_porcentaje_timbre_fiscal                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_timbre_fiscal'];
							  $contabilidad_monto_impuesto_municipal                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_impuesto_municipal'];
							  $contabilidad_porcentaje_impuesto_municipal               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['porcentaje_impuesto_municipal'];
							  $contabilidad_monto_neto_cobrar                           =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['monto_neto_cobrar'];
							  $contabilidad_dia_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['dia_asiento_registro'];
							  $contabilidad_mes_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['mes_asiento_registro'];
							  $contabilidad_ano_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_asiento_registro'];
							  $contabilidad_numero_asiento_registro                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_asiento_registro'];
							  $contabilidad_username_registro                           =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['username_registro'];
							  $contabilidad_condicion_actividad                         =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['condicion_actividad'];
							  $contabilidad_ano_anulacion                               =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_anulacion'];
							  $contabilidad_numero_anulacion                            =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_anulacion'];
							  $contabilidad_dia_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['dia_asiento_anulacion'];
							  $contabilidad_mes_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['mes_asiento_anulacion'];
							  $contabilidad_ano_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['ano_asiento_anulacion'];
							  $contabilidad_numero_asiento_anulacion                    =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_asiento_anulacion'];
							  $contabilidad_username_anulacion                          =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['username_anulacion'];
							  $contabilidad_fecha_proceso_registro                      =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_proceso_registro'];
							  $contabilidad_fecha_proceso_anulacion                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['fecha_proceso_anulacion'];
							  $contabilidad_numero_comprobante_islr                     =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_islr'];
							  $contabilidad_numero_comprobante_timbre                   =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_timbre'];
							  $contabilidad_numero_comprobante_municipal                =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_municipal'];
							  $contabilidad_numero_comprobante_iva                      =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_iva'];
							  $contabilidad_numero_comprobante_librocompras             =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['numero_comprobante_librocompras'];

							  $contabilidad_retencion_multa                             =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['retencion_multa'];
							  $contabilidad_retencion_responsabilidad                   =    $contabilidad_ve_contabilidad['v_cstd03_cheque_orden_pago']['retencion_responsabilidad'];

							  if($contabilidad_retencion_multa==""){          $contabilidad_retencion_multa           = 0;}
							  if($contabilidad_retencion_responsabilidad==""){$contabilidad_retencion_responsabilidad = 0;}


				               $contador_contabilidad++;



				                  if($contabilidad_cod_tipo_documento==1){//REGISTRO DE COMPROMISO

						              if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_documento_compromiso"]==""){
						              	 $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_documento_compromiso"]=$contabilidad_fecha_orden_pago;
						               }
						              $contabilidad_f_dc_adj_array_pago_aux = null;
						              $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_documento_compromiso"];

					         }else if($contabilidad_cod_tipo_documento==2){//Anticipo Orden de compra


						                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_compra"]==""){    $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_compra"]=$contabilidad_fecha_orden_pago;}
						                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"]=$contabilidad_fecha_orden_pago;}

						               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_compra"];
						               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"];

					         }else if($contabilidad_cod_tipo_documento==3){//Autorización de Orden de compra

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_autorizacion_compra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_autorizacion_compra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_autorizacion_compra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_orden_compra_compra"];


					         }else if($contabilidad_cod_tipo_documento==4){//Anticipo CONTRATO DE OBRA

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_obra"]==""){     $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==5){//VALUACIÓN DE CONTRATO DE OBRA


					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_obra"]==""){    $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==6){//RETENCIÓN DE CONTRATO DE OBRA

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_obra"]==""){   $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==7){//Anticipo CONTRATO DE SERVICIO

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_servicio"]==""){          $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_anticipo_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"];


					          }else if($contabilidad_cod_tipo_documento==8){//VALUACIÓN DE CONTRATO DE SERVICIO

					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_servicio"]==""){        $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_valuacion_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"];


					          }else if($contabilidad_cod_tipo_documento==9){//RETENCIÓN DE CONTRATO DE SERVICIO


					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_servicio"]==""){        $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]==""){$contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_retencion_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd03_cheque_orden_pago"]["fecha_contrato_servicio_servicio"];


					          }//fin if



					       if($contabilidad_monto_retencion_iva!=0){
								if(isset($monto_retenciones["monto_iva"])){
									 $monto_retenciones["monto_iva"] += $contabilidad_monto_retencion_iva;
								}else{
									 $monto_retenciones["monto_iva"]  = $contabilidad_monto_retencion_iva;
								}//fin else
				             }//fin if



							if($contabilidad_monto_islr!=0){
								if(isset($monto_retenciones["monto_islr"])){
									 $monto_retenciones["monto_islr"] += $contabilidad_monto_islr;
								}else{
									 $monto_retenciones["monto_islr"]  = $contabilidad_monto_islr;
								}//fin else
				           }//fin if



							if($contabilidad_monto_timbre_fiscal!=0){
									if(isset($monto_retenciones["monto_timbre"])){
										 $monto_retenciones["monto_timbre"] += $contabilidad_monto_timbre_fiscal;
									}else{
										 $monto_retenciones["monto_timbre"]  = $contabilidad_monto_timbre_fiscal;
									}//fin else
							}//fin if



							if($contabilidad_monto_impuesto_municipal!=0){
									if(isset($monto_retenciones["monto_municipal"])){
										 $monto_retenciones["monto_municipal"] += $contabilidad_monto_impuesto_municipal;
									}else{
										 $monto_retenciones["monto_municipal"]  = $contabilidad_monto_impuesto_municipal;
									}//fin else
						     }//fin if



							if($contabilidad_retencion_multa!=0){
									if(isset($monto_retenciones["monto_multa"])){
										 $monto_retenciones["monto_multa"] += $contabilidad_retencion_multa;
									}else{
										 $monto_retenciones["monto_multa"]  = $contabilidad_retencion_multa;
									}//fin else
							 }//fin if



							if($contabilidad_retencion_responsabilidad!=0){
									if(isset($monto_retenciones["monto_responsabilidad"])){
										 $monto_retenciones["monto_responsabilidad"] += $contabilidad_retencion_responsabilidad;
									}else{
										 $monto_retenciones["monto_responsabilidad"]  = $contabilidad_retencion_responsabilidad;
									}//fin else
						     }//fin if



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

						      if($tipo_bandera==0){
						      	  $tipo_bandera = $contabilidad_cod_tipo_documento;
						      }else{
						      	if($tipo_bandera!=$contabilidad_cod_tipo_documento && $clase_beneficiario==1){
						      		$motor_activo = 0;
						      		$cadena .= "ESTE CHEQUE POSEE ORDENES DE DIFERENTE TIPO DE DOCUMENTO \n";
						      		$cadena .= $this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_cheque=".$numero_cheque." \n";
						      	    $cadena .= "\n \n";
						      	}
						      }


						 $monto_retenciones["monto_neto_orden"]        += $contabilidad_monto_neto_cobrar;
				         $monto_retenciones["monto_total_retenciones"] += $suma_retencion;

				}//fin foreach


    $c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
	$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];



unset($datos_orden_pago_cuerpo);


if($clase_beneficiario!=1){

	$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
															      $to      = 2,
															      $td      = 1,
															      $rif_doc = $rif_cedula,
															      $ano_dc  = null,
															      $n_dc    = null,
															      $f_dc    = null,
															      $cpt_dc  = $concepto,
															      $ben_dc  = $beneficiario,
															      $mon_dc  = array("monto_cheque"=>$monto),

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

															      $num_che_o_debi  = $numero_cheque,
															      $fec_che_o_debi  = $fecha_proceso_anulacion,
															      $clas_che_o_debi = $clase_beneficiario,
															      $tipo_che_o_debi = 1,

															      $ano_dc_array_pago     = null,
															      $n_dc_array_pago       = null,
															      $n_dc_adj_array_pago   = null,
															      $f_dc_array_pago       = null,

															      $ano_op_array_pago  = null,
															      $n_op_array_pago    = null,
															      $f_op_array_pago    = null

															);


}else{



$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif_cedula."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0 && $clase_beneficiario==1 && $tipo_bandera!=1){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif_cedula." NO ESTA EN LA TABLA cpcd02 ".$this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_cheque=".$numero_cheque." \n";
	$cadena .= "\n \n";
}




if($tp_op_aux!="" && $motor_activo==1){

    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
															      $to      = 2,
															      $td      = 1,
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

															      $num_che_o_debi  = $numero_cheque,
															      $fec_che_o_debi  = $fecha_proceso_anulacion,
															      $clas_che_o_debi = $clase_beneficiario,
															      $tipo_che_o_debi = 1,

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

}

unset($ano_dc_array_pago_aux);
unset($n_dc_array_pago_aux);
unset($n_dc_adj_array_pago_aux);
unset($f_dc_array_pago_aux);
unset($ano_op_array_pago_aux);
unset($n_op_array_pago_aux);
unset($f_op_array_pago_aux);
unset($tipo_op_array_pago_aux);
unset($f_dc_adj_array_pago_aux);



}//fin else


unset($monto_retenciones);



}//fin foreach






/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/



 if($this->Session->read('total_pag_ch_anulacion_session_contabilidad')==$pagina){

 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd09_notadebito_cuerpo_pago');
 	//$this->set('termino',    true);

 }else if($this->Session->read('total_pag_ch_anulacion_session_contabilidad')==0){

 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd09_notadebito_cuerpo_pago');

 }else{

 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd03_cheque_cuerpo_anulacion');
 	//$this->set('termino',    true);

 }


    $this->render('vista_index');



}//fin function

























function reactualizacion_pagado_cstd09_notadebito_cuerpo_pago($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

     $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Pagado de notas de debito Pag: ".$pagina."/".$this->Session->read('total_pag_debi_session_contabilidad'), 1);


	$datos      = $this->cstd09_notadebito_cuerpo_pago->findAll($this->condicionNDEP()." and  ano_movimiento='".$ano_select."'  ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito ASC", 500, $pagina, null);
    $total_suma = count($datos);
    $cadena     = "";



foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_presi"];
  $cod_entidad              = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_inst"];
  $cod_dep                  = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_movimiento              = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_movimiento"];
  $cod_entidad_bancaria        = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_entidad_bancaria"];
  $cod_sucursal                = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_sucursal"];
  $cuenta_bancaria             = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cuenta_bancaria"];
  $numero_debito               = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_debito"];
  $fecha_debito                = cambia_fecha($datos_aux["cstd09_notadebito_cuerpo_pago"]["fecha_debito"]);
  $beneficiario                = $datos_aux["cstd09_notadebito_cuerpo_pago"]["beneficiario"];
  $monto                       = $datos_aux["cstd09_notadebito_cuerpo_pago"]["monto"];
  $concepto                    = $datos_aux["cstd09_notadebito_cuerpo_pago"]["concepto"];
  $rif_cedula                  = $datos_aux["cstd09_notadebito_cuerpo_pago"]["rif_cedula"];
  $cod_tipo_pago               = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_tipo_pago"];
  $status_cheque               = $datos_aux["cstd09_notadebito_cuerpo_pago"]["status_debito"];
  $clase_beneficiario          = $datos_aux["cstd09_notadebito_cuerpo_pago"]["clase_beneficiario"];
  $fecha_proceso_registro      = cambia_fecha($datos_aux["cstd09_notadebito_cuerpo_pago"]["fecha_proceso_registro"]);
  $dia_asiento_registro        = $datos_aux["cstd09_notadebito_cuerpo_pago"]["dia_asiento_registro"];
  $mes_asiento_registro        = $datos_aux["cstd09_notadebito_cuerpo_pago"]["mes_asiento_registro"];
  $ano_asiento_registro        = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_asiento_registro"];
  $numero_asiento_registro     = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_asiento_registro"];
  $username_registro           = $datos_aux["cstd09_notadebito_cuerpo_pago"]["username_registro"];
  $condicion_actividad         = $datos_aux["cstd09_notadebito_cuerpo_pago"]["condicion_actividad"];
  $ano_anulacion               = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_anulacion"];
  $numero_anulacion            = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_anulacion"];
  $fecha_proceso_anulacion     = cambia_fecha($datos_aux["cstd09_notadebito_cuerpo_pago"]["fecha_proceso_anulacion"]);
  $dia_asiento_anulacion       = $datos_aux["cstd09_notadebito_cuerpo_pago"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion       = $datos_aux["cstd09_notadebito_cuerpo_pago"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion       = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion    = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_asiento_anulacion"];
  $username_anulacion          = $datos_aux["cstd09_notadebito_cuerpo_pago"]["username_anulacion"];
  $numero_comprobante_egreso   = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_comprobante_egreso"];
  $ano_anterior                = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_anterior"];


$contador_contabilidad=0;
$monto_retenciones["monto_neto_orden"]        = 0;
$monto_retenciones["monto_total_retenciones"] = 0;

$ano_dc_array_pago_aux   = array();
$n_dc_array_pago_aux     = array();
$n_dc_adj_array_pago_aux = array();
$f_dc_array_pago_aux     = array();
$ano_op_array_pago_aux   = array();
$n_op_array_pago_aux     = array();
$f_op_array_pago_aux     = array();
$tipo_op_array_pago_aux  = array();
$f_dc_adj_array_pago_aux = array();

$ano_dc_aux  = "";
$n_dc_aux    = "";
$f_dc_aux    = "";

$ano_op_aux   = "";
$n_op_aux     = "";
$f_op_aux     = "";

$a_adj_op_aux = null;
$n_adj_op_aux = "";
$f_adj_op_aux = "";

$tipo_bandera     = 0;
$motor_activo     = 1;

$datos_orden_pago_cuerpo   = $this->v_cstd09_notadebito_orden_pago->findAll($this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_debito=".$numero_debito);


				foreach($datos_orden_pago_cuerpo as $contabilidad_ve_contabilidad){

							  $contabilidad_contabilidad_cod_presi                      =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_presi'];
							  $contabilidad_cod_entidad                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_entidad'];
							  $contabilidad_cod_tipo_inst                               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_tipo_inst'];
							  $contabilidad_cod_inst                                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_inst'];
							  $contabilidad_cod_dep                                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_dep'];
							  $contabilidad_ano_orden_pago                              =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_orden_pago'];
							  $contabilidad_numero_orden_pago                           =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_orden_pago'];
							  $contabilidad_tipo_orden                                  =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['tipo_orden'];
							  $contabilidad_fecha_orden_pago                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_orden_pago'];
							  $contabilidad_ano_documento_origen                        =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_documento_origen'];
							  $contabilidad_numero_documento_origen                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_documento_origen'];
							  $contabilidad_numero_documento_adjunto                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_documento_adjunto'];
							  $contabilidad_cod_tipo_documento                          =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_tipo_documento'];
							  $contabilidad_rif                                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['rif'];
							  $contabilidad_beneficiario                                =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['beneficiario'];
							  $contabilidad_autorizado                                  =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['autorizado'];
							  $contabilidad_cedula_identidad                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cedula_identidad'];
							  $contabilidad_concepto                                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['concepto'];
							  $contabilidad_monto_total                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_total'];
							  $contabilidad_numero_pago                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_pago'];
							  $contabilidad_monto_parcial                               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_parcial'];
							  $contabilidad_cod_frecuencia_pago                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_frecuencia_pago'];
							  $contabilidad_fecha_desde                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_desde'];
							  $contabilidad_fecha_hasta                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_hasta'];
							  $contabilidad_cod_tipo_pago                               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_tipo_pago'];
							  $contabilidad_monto_coniva                                =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_coniva'];
							  $contabilidad_monto_iva                                   =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_iva'];
							  $contabilidad_porcentaje_iva                              =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_iva'];
							  $contabilidad_monto_siniva                                =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_siniva'];
							  $contabilidad_monto_retencion_laboral                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_retencion_laboral'];
							  $contabilidad_monto_retencion_fielcumplimiento            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_retencion_fielcumplimient'];
							  $contabilidad_monto_descontar_impuesto                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_descontar_impuesto'];
							  $contabilidad_amortizacion_anticipo                       =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['amortizacion_anticipo'];
							  $contabilidad_monto_orden_pago                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_orden_pago'];
							  $contabilidad_monto_retencion_iva                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_retencion_iva'];
							  $contabilidad_porcentaje_retencion_iva                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_retencion_iva'];
							  $contabilidad_monto_islr                                  =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_islr'];
							  $contabilidad_porcentaje_islr                             =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_islr'];
							  $contabilidad_monto_sustraendo                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_sustraendo'];
							  $contabilidad_monto_timbre_fiscal                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_timbre_fiscal'];
							  $contabilidad_porcentaje_timbre_fiscal                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_timbre_fiscal'];
							  $contabilidad_monto_impuesto_municipal                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_impuesto_municipal'];
							  $contabilidad_porcentaje_impuesto_municipal               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_impuesto_municipal'];
							  $contabilidad_monto_neto_cobrar                           =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_neto_cobrar'];
							  $contabilidad_dia_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['dia_asiento_registro'];
							  $contabilidad_mes_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['mes_asiento_registro'];
							  $contabilidad_ano_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_asiento_registro'];
							  $contabilidad_numero_asiento_registro                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_asiento_registro'];
							  $contabilidad_username_registro                           =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['username_registro'];
							  $contabilidad_condicion_actividad                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['condicion_actividad'];
							  $contabilidad_ano_anulacion                               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_anulacion'];
							  $contabilidad_numero_anulacion                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_anulacion'];
							  $contabilidad_dia_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['dia_asiento_anulacion'];
							  $contabilidad_mes_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['mes_asiento_anulacion'];
							  $contabilidad_ano_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_asiento_anulacion'];
							  $contabilidad_numero_asiento_anulacion                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_asiento_anulacion'];
							  $contabilidad_username_anulacion                          =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['username_anulacion'];
							  $contabilidad_fecha_proceso_registro                      =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_proceso_registro'];
							  $contabilidad_fecha_proceso_anulacion                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_proceso_anulacion'];
							  $contabilidad_numero_comprobante_islr                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_islr'];
							  $contabilidad_numero_comprobante_timbre                   =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_timbre'];
							  $contabilidad_numero_comprobante_municipal                =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_municipal'];
							  $contabilidad_numero_comprobante_iva                      =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_iva'];
							  $contabilidad_numero_comprobante_librocompras             =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_librocompras'];

							  $contabilidad_retencion_multa                             =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['retencion_multa'];
							  $contabilidad_retencion_responsabilidad                   =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['retencion_responsabilidad'];

							  if($contabilidad_retencion_multa==""){          $contabilidad_retencion_multa           = 0;}
							  if($contabilidad_retencion_responsabilidad==""){$contabilidad_retencion_responsabilidad = 0;}


				$contador_contabilidad++;



				                  if($contabilidad_cod_tipo_documento==1){//REGISTRO DE COMPROMISO

						              if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_documento_compromiso"]==""){
						              	 $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_documento_compromiso"]=$contabilidad_fecha_orden_pago;
						               }
						              $contabilidad_f_dc_adj_array_pago_aux = null;
						              $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_documento_compromiso"];

					         }else if($contabilidad_cod_tipo_documento==2){//Anticipo Orden de compra


						                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_compra"]==""){    $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_compra"]=$contabilidad_fecha_orden_pago;}
						                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"]=$contabilidad_fecha_orden_pago;}

						               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_compra"];
						               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"];

					         }else if($contabilidad_cod_tipo_documento==3){//Autorización de Orden de compra

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_autorizacion_compra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_autorizacion_compra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_autorizacion_compra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"];


					         }else if($contabilidad_cod_tipo_documento==4){//Anticipo CONTRATO DE OBRA

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_obra"]==""){     $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==5){//VALUACIÓN DE CONTRATO DE OBRA


					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_obra"]==""){    $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==6){//RETENCIÓN DE CONTRATO DE OBRA

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_obra"]==""){   $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==7){//Anticipo CONTRATO DE SERVICIO

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_servicio"]==""){          $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"];


					          }else if($contabilidad_cod_tipo_documento==8){//VALUACIÓN DE CONTRATO DE SERVICIO

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_servicio"]==""){        $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"];


					          }else if($contabilidad_cod_tipo_documento==9){//RETENCIÓN DE CONTRATO DE SERVICIO


					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_servicio"]==""){        $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"];


					          }//fin if


                             if($contabilidad_monto_retencion_iva!=0){
								if(isset($monto_retenciones["monto_iva"])){
									 $monto_retenciones["monto_iva"] += $contabilidad_monto_retencion_iva;
								}else{
									 $monto_retenciones["monto_iva"]  = $contabilidad_monto_retencion_iva;
								}//fin else
				             }//fin if



							if($contabilidad_monto_islr!=0){
								if(isset($monto_retenciones["monto_islr"])){
									 $monto_retenciones["monto_islr"] += $contabilidad_monto_islr;
								}else{
									 $monto_retenciones["monto_islr"]  = $contabilidad_monto_islr;
								}//fin else
				           }//fin if



							if($contabilidad_monto_timbre_fiscal!=0){
									if(isset($monto_retenciones["monto_timbre"])){
										 $monto_retenciones["monto_timbre"] += $contabilidad_monto_timbre_fiscal;
									}else{
										 $monto_retenciones["monto_timbre"]  = $contabilidad_monto_timbre_fiscal;
									}//fin else
							}//fin if



							if($contabilidad_monto_impuesto_municipal!=0){
									if(isset($monto_retenciones["monto_municipal"])){
										 $monto_retenciones["monto_municipal"] += $contabilidad_monto_impuesto_municipal;
									}else{
										 $monto_retenciones["monto_municipal"]  = $contabilidad_monto_impuesto_municipal;
									}//fin else
						     }//fin if



							if($contabilidad_retencion_multa!=0){
									if(isset($monto_retenciones["monto_multa"])){
										 $monto_retenciones["monto_multa"] += $contabilidad_retencion_multa;
									}else{
										 $monto_retenciones["monto_multa"]  = $contabilidad_retencion_multa;
									}//fin else
							 }//fin if



							if($contabilidad_retencion_responsabilidad!=0){
									if(isset($monto_retenciones["monto_responsabilidad"])){
										 $monto_retenciones["monto_responsabilidad"] += $contabilidad_retencion_responsabilidad;
									}else{
										 $monto_retenciones["monto_responsabilidad"]  = $contabilidad_retencion_responsabilidad;
									}//fin else
						     }//fin if


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

						      if($tipo_bandera==0){
						      	  $tipo_bandera = $contabilidad_cod_tipo_documento;
						      }else{
						      	if($tipo_bandera!=$contabilidad_cod_tipo_documento && $clase_beneficiario==1){
						      		$motor_activo = 0;
						      		$cadena .= "ESTE CHEQUE POSEE ORDENES DE DIFERENTE TIPO DE DOCUMENTO \n";
						      		$cadena .= $this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_debito=".$numero_debito." \n";
						      	    $cadena .= "\n \n";
						      	}
						      }


						 $monto_retenciones["monto_neto_orden"]        += $contabilidad_monto_neto_cobrar;
				         $monto_retenciones["monto_total_retenciones"] += $suma_retencion;

				}//fin foreach


    $c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
	$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];


$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif_cedula."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0 && $clase_beneficiario==1 && $tipo_bandera!=1){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif_cedula." NO ESTA EN LA TABLA cpcd02 ".$this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_debito=".$numero_debito." \n";
	$cadena .= "\n \n";
}

//CHEQUE O NOTA DE DÉBITO

if($motor_activo==1){

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
															      $fec_che_o_debi  = $fecha_debito,
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
}

unset($monto_retenciones);


}//fin foreach






/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/






 if($this->Session->read('total_pag_debi_session_contabilidad')==$pagina){

 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd09_notadebito_cuerpo_pago_anulacion');
 	//$this->set('termino',    true);


 }else if($this->Session->read('total_pag_debi_session_contabilidad')==0){

 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd09_notadebito_cuerpo_pago_anulacion');

 }else{

 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd09_notadebito_cuerpo_pago');
 	//$this->set('termino',    true);

 }




$this->render('vista_index');




}//fin function





























function reactualizacion_pagado_cstd09_notadebito_cuerpo_pago_anulacion($pagina=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Pagado de notas de debito anulación", 1);


	$datos      = $this->cstd09_notadebito_cuerpo_pago->findAll($this->condicionNDEP()." and  ano_movimiento='".$ano_select."' and condicion_actividad=2 ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_debito ASC");
    $total_suma = count($datos);
    $cadena     = "";


foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_presi"];
  $cod_entidad              = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_inst"];
  $cod_dep                  = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_movimiento              = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_movimiento"];
  $cod_entidad_bancaria        = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_entidad_bancaria"];
  $cod_sucursal                = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_sucursal"];
  $cuenta_bancaria             = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cuenta_bancaria"];
  $numero_debito               = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_debito"];
  $fecha_debito                = cambia_fecha($datos_aux["cstd09_notadebito_cuerpo_pago"]["fecha_debito"]);
  $beneficiario                = $datos_aux["cstd09_notadebito_cuerpo_pago"]["beneficiario"];
  $monto                       = $datos_aux["cstd09_notadebito_cuerpo_pago"]["monto"];
  $concepto                    = $datos_aux["cstd09_notadebito_cuerpo_pago"]["concepto"];
  $rif_cedula                  = $datos_aux["cstd09_notadebito_cuerpo_pago"]["rif_cedula"];
  $cod_tipo_pago               = $datos_aux["cstd09_notadebito_cuerpo_pago"]["cod_tipo_pago"];
  $status_cheque               = $datos_aux["cstd09_notadebito_cuerpo_pago"]["status_debito"];
  $clase_beneficiario          = $datos_aux["cstd09_notadebito_cuerpo_pago"]["clase_beneficiario"];
  $fecha_proceso_registro      = cambia_fecha($datos_aux["cstd09_notadebito_cuerpo_pago"]["fecha_proceso_registro"]);
  $dia_asiento_registro        = $datos_aux["cstd09_notadebito_cuerpo_pago"]["dia_asiento_registro"];
  $mes_asiento_registro        = $datos_aux["cstd09_notadebito_cuerpo_pago"]["mes_asiento_registro"];
  $ano_asiento_registro        = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_asiento_registro"];
  $numero_asiento_registro     = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_asiento_registro"];
  $username_registro           = $datos_aux["cstd09_notadebito_cuerpo_pago"]["username_registro"];
  $condicion_actividad         = $datos_aux["cstd09_notadebito_cuerpo_pago"]["condicion_actividad"];
  $ano_anulacion               = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_anulacion"];
  $numero_anulacion            = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_anulacion"];
  $fecha_proceso_anulacion     = cambia_fecha($datos_aux["cstd09_notadebito_cuerpo_pago"]["fecha_proceso_anulacion"]);
  $dia_asiento_anulacion       = $datos_aux["cstd09_notadebito_cuerpo_pago"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion       = $datos_aux["cstd09_notadebito_cuerpo_pago"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion       = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion    = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_asiento_anulacion"];
  $username_anulacion          = $datos_aux["cstd09_notadebito_cuerpo_pago"]["username_anulacion"];
  $numero_comprobante_egreso   = $datos_aux["cstd09_notadebito_cuerpo_pago"]["numero_comprobante_egreso"];
  $ano_anterior                = $datos_aux["cstd09_notadebito_cuerpo_pago"]["ano_anterior"];


$contador_contabilidad=0;
$monto_retenciones["monto_neto_orden"]        = 0;
$monto_retenciones["monto_total_retenciones"] = 0;



$ano_dc_array_pago_aux   = array();
$n_dc_array_pago_aux     = array();
$n_dc_adj_array_pago_aux = array();
$f_dc_array_pago_aux     = array();
$ano_op_array_pago_aux   = array();
$n_op_array_pago_aux     = array();
$f_op_array_pago_aux     = array();
$tipo_op_array_pago_aux  = array();
$f_dc_adj_array_pago_aux = array();

$ano_dc_aux  = "";
$n_dc_aux    = "";
$f_dc_aux    = "";

$ano_op_aux   = "";
$n_op_aux     = "";
$f_op_aux     = "";

$a_adj_op_aux = null;
$n_adj_op_aux = "";
$f_adj_op_aux = "";

$tipo_bandera     = 0;
$motor_activo     = 1;

$datos_orden_pago_cuerpo   = $this->v_cstd09_notadebito_orden_pago->findAll($this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_debito=".$numero_debito);

				foreach($datos_orden_pago_cuerpo as $contabilidad_ve_contabilidad){

							  $contabilidad_contabilidad_cod_presi                      =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_presi'];
							  $contabilidad_cod_entidad                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_entidad'];
							  $contabilidad_cod_tipo_inst                               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_tipo_inst'];
							  $contabilidad_cod_inst                                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_inst'];
							  $contabilidad_cod_dep                                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_dep'];
							  $contabilidad_ano_orden_pago                              =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_orden_pago'];
							  $contabilidad_numero_orden_pago                           =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_orden_pago'];
							  $contabilidad_tipo_orden                                  =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['tipo_orden'];
							  $contabilidad_fecha_orden_pago                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_orden_pago'];
							  $contabilidad_ano_documento_origen                        =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_documento_origen'];
							  $contabilidad_numero_documento_origen                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_documento_origen'];
							  $contabilidad_numero_documento_adjunto                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_documento_adjunto'];
							  $contabilidad_cod_tipo_documento                          =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_tipo_documento'];
							  $contabilidad_rif                                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['rif'];
							  $contabilidad_beneficiario                                =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['beneficiario'];
							  $contabilidad_autorizado                                  =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['autorizado'];
							  $contabilidad_cedula_identidad                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cedula_identidad'];
							  $contabilidad_concepto                                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['concepto'];
							  $contabilidad_monto_total                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_total'];
							  $contabilidad_numero_pago                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_pago'];
							  $contabilidad_monto_parcial                               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_parcial'];
							  $contabilidad_cod_frecuencia_pago                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_frecuencia_pago'];
							  $contabilidad_fecha_desde                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_desde'];
							  $contabilidad_fecha_hasta                                 =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_hasta'];
							  $contabilidad_cod_tipo_pago                               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['cod_tipo_pago'];
							  $contabilidad_monto_coniva                                =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_coniva'];
							  $contabilidad_monto_iva                                   =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_iva'];
							  $contabilidad_porcentaje_iva                              =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_iva'];
							  $contabilidad_monto_siniva                                =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_siniva'];
							  $contabilidad_monto_retencion_laboral                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_retencion_laboral'];
							  $contabilidad_monto_retencion_fielcumplimiento            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_retencion_fielcumplimient'];
							  $contabilidad_monto_descontar_impuesto                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_descontar_impuesto'];
							  $contabilidad_amortizacion_anticipo                       =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['amortizacion_anticipo'];
							  $contabilidad_monto_orden_pago                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_orden_pago'];
							  $contabilidad_monto_retencion_iva                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_retencion_iva'];
							  $contabilidad_porcentaje_retencion_iva                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_retencion_iva'];
							  $contabilidad_monto_islr                                  =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_islr'];
							  $contabilidad_porcentaje_islr                             =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_islr'];
							  $contabilidad_monto_sustraendo                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_sustraendo'];
							  $contabilidad_monto_timbre_fiscal                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_timbre_fiscal'];
							  $contabilidad_porcentaje_timbre_fiscal                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_timbre_fiscal'];
							  $contabilidad_monto_impuesto_municipal                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_impuesto_municipal'];
							  $contabilidad_porcentaje_impuesto_municipal               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['porcentaje_impuesto_municipal'];
							  $contabilidad_monto_neto_cobrar                           =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['monto_neto_cobrar'];
							  $contabilidad_dia_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['dia_asiento_registro'];
							  $contabilidad_mes_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['mes_asiento_registro'];
							  $contabilidad_ano_asiento_registro                        =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_asiento_registro'];
							  $contabilidad_numero_asiento_registro                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_asiento_registro'];
							  $contabilidad_username_registro                           =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['username_registro'];
							  $contabilidad_condicion_actividad                         =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['condicion_actividad'];
							  $contabilidad_ano_anulacion                               =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_anulacion'];
							  $contabilidad_numero_anulacion                            =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_anulacion'];
							  $contabilidad_dia_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['dia_asiento_anulacion'];
							  $contabilidad_mes_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['mes_asiento_anulacion'];
							  $contabilidad_ano_asiento_anulacion                       =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['ano_asiento_anulacion'];
							  $contabilidad_numero_asiento_anulacion                    =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_asiento_anulacion'];
							  $contabilidad_username_anulacion                          =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['username_anulacion'];
							  $contabilidad_fecha_proceso_registro                      =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_proceso_registro'];
							  $contabilidad_fecha_proceso_anulacion                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['fecha_proceso_anulacion'];
							  $contabilidad_numero_comprobante_islr                     =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_islr'];
							  $contabilidad_numero_comprobante_timbre                   =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_timbre'];
							  $contabilidad_numero_comprobante_municipal                =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_municipal'];
							  $contabilidad_numero_comprobante_iva                      =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_iva'];
							  $contabilidad_numero_comprobante_librocompras             =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['numero_comprobante_librocompras'];

							  $contabilidad_retencion_multa                             =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['retencion_multa'];
							  $contabilidad_retencion_responsabilidad                   =    $contabilidad_ve_contabilidad['v_cstd09_notadebito_orden_pago']['retencion_responsabilidad'];

							  if($contabilidad_retencion_multa==""){          $contabilidad_retencion_multa           = 0;}
							  if($contabilidad_retencion_responsabilidad==""){$contabilidad_retencion_responsabilidad = 0;}


				$contador_contabilidad++;



				                  if($contabilidad_cod_tipo_documento==1){//REGISTRO DE COMPROMISO

						              if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_documento_compromiso"]==""){
						              	 $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_documento_compromiso"]=$contabilidad_fecha_orden_pago;
						               }
						              $contabilidad_f_dc_adj_array_pago_aux = null;
						              $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_documento_compromiso"];

					         }else if($contabilidad_cod_tipo_documento==2){//Anticipo Orden de compra


						                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_compra"]==""){    $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_compra"]=$contabilidad_fecha_orden_pago;}
						                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"]=$contabilidad_fecha_orden_pago;}

						               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_compra"];
						               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"];

					         }else if($contabilidad_cod_tipo_documento==3){//Autorización de Orden de compra

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_autorizacion_compra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_autorizacion_compra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_autorizacion_compra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_orden_compra_compra"];


					         }else if($contabilidad_cod_tipo_documento==4){//Anticipo CONTRATO DE OBRA

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_obra"]==""){     $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==5){//VALUACIÓN DE CONTRATO DE OBRA


					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_obra"]==""){    $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==6){//RETENCIÓN DE CONTRATO DE OBRA

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_obra"]==""){   $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_obra"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_obra"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_obra_obra"];


					          }else if($contabilidad_cod_tipo_documento==7){//Anticipo CONTRATO DE SERVICIO

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_servicio"]==""){          $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_anticipo_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"];


					          }else if($contabilidad_cod_tipo_documento==8){//VALUACIÓN DE CONTRATO DE SERVICIO

					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_servicio"]==""){        $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_valuacion_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"];


					          }else if($contabilidad_cod_tipo_documento==9){//RETENCIÓN DE CONTRATO DE SERVICIO


					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_servicio"]==""){        $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_servicio"]=$contabilidad_fecha_orden_pago;}
					                if($contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]==""){$contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"]=$contabilidad_fecha_orden_pago;}


					               $contabilidad_f_dc_adj_array_pago_aux = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_retencion_servicio"];
					               $contabilidad_f_dc_array_pago_aux     = $contabilidad_ve_contabilidad["v_cstd09_notadebito_orden_pago"]["fecha_contrato_servicio_servici"];


					          }//fin if



					          if($contabilidad_monto_retencion_iva!=0){
								if(isset($monto_retenciones["monto_iva"])){
									 $monto_retenciones["monto_iva"] += $contabilidad_monto_retencion_iva;
								}else{
									 $monto_retenciones["monto_iva"]  = $contabilidad_monto_retencion_iva;
								}//fin else
				             }//fin if



							if($contabilidad_monto_islr!=0){
								if(isset($monto_retenciones["monto_islr"])){
									 $monto_retenciones["monto_islr"] += $contabilidad_monto_islr;
								}else{
									 $monto_retenciones["monto_islr"]  = $contabilidad_monto_islr;
								}//fin else
				           }//fin if



							if($contabilidad_monto_timbre_fiscal!=0){
									if(isset($monto_retenciones["monto_timbre"])){
										 $monto_retenciones["monto_timbre"] += $contabilidad_monto_timbre_fiscal;
									}else{
										 $monto_retenciones["monto_timbre"]  = $contabilidad_monto_timbre_fiscal;
									}//fin else
							}//fin if



							if($contabilidad_monto_impuesto_municipal!=0){
									if(isset($monto_retenciones["monto_municipal"])){
										 $monto_retenciones["monto_municipal"] += $contabilidad_monto_impuesto_municipal;
									}else{
										 $monto_retenciones["monto_municipal"]  = $contabilidad_monto_impuesto_municipal;
									}//fin else
						     }//fin if



							if($contabilidad_retencion_multa!=0){
									if(isset($monto_retenciones["monto_multa"])){
										 $monto_retenciones["monto_multa"] += $contabilidad_retencion_multa;
									}else{
										 $monto_retenciones["monto_multa"]  = $contabilidad_retencion_multa;
									}//fin else
							 }//fin if



							if($contabilidad_retencion_responsabilidad!=0){
									if(isset($monto_retenciones["monto_responsabilidad"])){
										 $monto_retenciones["monto_responsabilidad"] += $contabilidad_retencion_responsabilidad;
									}else{
										 $monto_retenciones["monto_responsabilidad"]  = $contabilidad_retencion_responsabilidad;
									}//fin else
						     }//fin if



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

						      if($tipo_bandera==0){
						      	  $tipo_bandera = $contabilidad_cod_tipo_documento;
						      }else{
						      	if($tipo_bandera!=$contabilidad_cod_tipo_documento && $clase_beneficiario==1){
						      		$motor_activo = 0;
						      		$cadena .= "ESTE CHEQUE POSEE ORDENES DE DIFERENTE TIPO DE DOCUMENTO \n";
						      		$cadena .= $this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_debito=".$numero_debito." \n";
						      	    $cadena .= "\n \n";
						      	}
						      }


						 $monto_retenciones["monto_neto_orden"]        += $contabilidad_monto_neto_cobrar;
				         $monto_retenciones["monto_total_retenciones"] += $suma_retencion;

				}//fin foreach


    $c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
	$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

$RANDOM              = rand();
$contador_cpcd02     = $this->cpcd02->findCount("rif='".$rif_cedula."' and ".$RANDOM."=".$RANDOM." ");
if($contador_cpcd02==0 && $clase_beneficiario==1 && $tipo_bandera!=1){
    $motor_activo = 0;
	$cadena .= "EL RIF ".$rif_cedula." NO ESTA EN LA TABLA cpcd02 ".$this->condicion()."  and  cod_entidad_bancaria='".$cod_entidad_bancaria."'  and  cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and  ano_movimiento=".$ano_movimiento."  and  numero_debito=".$numero_debito." \n";
	$cadena .= "\n \n";
}

if($motor_activo==1){

    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
															      $to      = 2,
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
}

unset($monto_retenciones);


}//fin foreach





/*

if($cadena!=""){
    $file_leer_candena = $this->leer_file("../webroot/descargas/contabilidad_notificacion_reactializacion.txt");
	$this->wFile('contabilidad_notificacion_reactializacion', $cadena." \n ".$file_leer_candena, "w", "../webroot/descargas");
	if(file_exists("../webroot/descargas/contabilidad_notificacion_reactializacion.txt")){
		     chmod("../webroot/descargas/contabilidad_notificacion_reactializacion.txt", 0777);
	}
}

*/


 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd30_debito_cuerpo');
 	//$this->set('termino',    true);

    $this->render('vista_index');








}//fin function













function reactualizacion_pagado_cstd30_debito_cuerpo($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Pagado de retenciones de nota de debido ", 1);


	$datos      = $this->cstd30_debito_cuerpo->findAll($this->condicionNDEP()." and  ano_movimiento='".$ano_select."'  ");
    $total_suma = count($datos);
    $cadena     = "";


foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cstd30_debito_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cstd30_debito_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cstd30_debito_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cstd30_debito_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cstd30_debito_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_movimiento              = $datos_aux["cstd30_debito_cuerpo"]["ano_movimiento"];
  $cod_entidad_bancaria        = $datos_aux["cstd30_debito_cuerpo"]["cod_entidad_bancaria"];
  $cod_sucursal                = $datos_aux["cstd30_debito_cuerpo"]["cod_sucursal"];
  $cuenta_bancaria             = $datos_aux["cstd30_debito_cuerpo"]["cuenta_bancaria"];
  $numero_debito               = $datos_aux["cstd30_debito_cuerpo"]["numero_debito"];
  $fecha_debito                = cambia_fecha($datos_aux["cstd30_debito_cuerpo"]["fecha_debito"]);
  $beneficiario                = $datos_aux["cstd30_debito_cuerpo"]["beneficiario"];
  $monto                       = $datos_aux["cstd30_debito_cuerpo"]["monto"];
  $concepto                    = $datos_aux["cstd30_debito_cuerpo"]["concepto"];
  $rif_cedula                  = $datos_aux["cstd30_debito_cuerpo"]["rif_cedula"];
  $cod_tipo_pago               = $datos_aux["cstd30_debito_cuerpo"]["cod_tipo_pago"];
  $status_cheque               = $datos_aux["cstd30_debito_cuerpo"]["status_debito"];
  $clase_beneficiario          = $datos_aux["cstd30_debito_cuerpo"]["clase_beneficiario"];
  $fecha_proceso_registro      = cambia_fecha($datos_aux["cstd30_debito_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro        = $datos_aux["cstd30_debito_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro        = $datos_aux["cstd30_debito_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro        = $datos_aux["cstd30_debito_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro     = $datos_aux["cstd30_debito_cuerpo"]["numero_asiento_registro"];
  $username_registro           = $datos_aux["cstd30_debito_cuerpo"]["username_registro"];
  $condicion_actividad         = $datos_aux["cstd30_debito_cuerpo"]["condicion_actividad"];
  $ano_anulacion               = $datos_aux["cstd30_debito_cuerpo"]["ano_anulacion"];
  $numero_anulacion            = $datos_aux["cstd30_debito_cuerpo"]["numero_anulacion"];
  $fecha_proceso_anulacion     = cambia_fecha($datos_aux["cstd30_debito_cuerpo"]["fecha_proceso_anulacion"]);
  $dia_asiento_anulacion       = $datos_aux["cstd30_debito_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion       = $datos_aux["cstd30_debito_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion       = $datos_aux["cstd30_debito_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion    = $datos_aux["cstd30_debito_cuerpo"]["numero_asiento_anulacion"];
  $username_anulacion          = $datos_aux["cstd30_debito_cuerpo"]["username_anulacion"];
  $numero_comprobante_egreso   = $datos_aux["cstd30_debito_cuerpo"]["numero_comprobante_egreso"];

    $c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
	$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
															      $to      = 1,
															      $td      = 4,
															      $rif_doc = $rif_cedula,
															      $ano_dc  = null,
															      $n_dc    = null,
															      $f_dc    = null,
															      $cpt_dc  = $concepto,
															      $ben_dc  = $beneficiario,
															      $mon_dc  = array("monto_cheque"=>$monto),

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
															      $fec_che_o_debi  = $fecha_debito,
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





}//fin foreach








 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_pagado_cstd30_debito_cuerpo_anulacion');
 	//$this->set('termino',    true);

    $this->render('vista_index');








}//fin function















function reactualizacion_pagado_cstd30_debito_cuerpo_anulacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Pagado de retenciones de nota de debido anulación ", 1);


	$datos      = $this->cstd30_debito_cuerpo->findAll($this->condicionNDEP()."  and  ano_movimiento='".$ano_select."' and condicion_actividad=2 ");
    $total_suma = count($datos);
    $cadena     = "";


foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cstd30_debito_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cstd30_debito_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cstd30_debito_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cstd30_debito_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cstd30_debito_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_movimiento              = $datos_aux["cstd30_debito_cuerpo"]["ano_movimiento"];
  $cod_entidad_bancaria        = $datos_aux["cstd30_debito_cuerpo"]["cod_entidad_bancaria"];
  $cod_sucursal                = $datos_aux["cstd30_debito_cuerpo"]["cod_sucursal"];
  $cuenta_bancaria             = $datos_aux["cstd30_debito_cuerpo"]["cuenta_bancaria"];
  $numero_debito               = $datos_aux["cstd30_debito_cuerpo"]["numero_debito"];
  $fecha_debito                = cambia_fecha($datos_aux["cstd30_debito_cuerpo"]["fecha_debito"]);
  $beneficiario                = $datos_aux["cstd30_debito_cuerpo"]["beneficiario"];
  $monto                       = $datos_aux["cstd30_debito_cuerpo"]["monto"];
  $concepto                    = $datos_aux["cstd30_debito_cuerpo"]["concepto"];
  $rif_cedula                  = $datos_aux["cstd30_debito_cuerpo"]["rif_cedula"];
  $cod_tipo_pago               = $datos_aux["cstd30_debito_cuerpo"]["cod_tipo_pago"];
  $status_cheque               = $datos_aux["cstd30_debito_cuerpo"]["status_debito"];
  $clase_beneficiario          = $datos_aux["cstd30_debito_cuerpo"]["clase_beneficiario"];
  $fecha_proceso_registro      = cambia_fecha($datos_aux["cstd30_debito_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro        = $datos_aux["cstd30_debito_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro        = $datos_aux["cstd30_debito_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro        = $datos_aux["cstd30_debito_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro     = $datos_aux["cstd30_debito_cuerpo"]["numero_asiento_registro"];
  $username_registro           = $datos_aux["cstd30_debito_cuerpo"]["username_registro"];
  $condicion_actividad         = $datos_aux["cstd30_debito_cuerpo"]["condicion_actividad"];
  $ano_anulacion               = $datos_aux["cstd30_debito_cuerpo"]["ano_anulacion"];
  $numero_anulacion            = $datos_aux["cstd30_debito_cuerpo"]["numero_anulacion"];
  $fecha_proceso_anulacion     = cambia_fecha($datos_aux["cstd30_debito_cuerpo"]["fecha_proceso_anulacion"]);
  $dia_asiento_anulacion       = $datos_aux["cstd30_debito_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion       = $datos_aux["cstd30_debito_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion       = $datos_aux["cstd30_debito_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion    = $datos_aux["cstd30_debito_cuerpo"]["numero_asiento_anulacion"];
  $username_anulacion          = $datos_aux["cstd30_debito_cuerpo"]["username_anulacion"];
  $numero_comprobante_egreso   = $datos_aux["cstd30_debito_cuerpo"]["numero_comprobante_egreso"];

    $c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
	$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
															      $to      = 2,
															      $td      = 4,
															      $rif_doc = $rif_cedula,
															      $ano_dc  = null,
															      $n_dc    = null,
															      $f_dc    = null,
															      $cpt_dc  = $concepto,
															      $ben_dc  = $beneficiario,
															      $mon_dc  = array("monto_cheque"=>$monto),

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





}//fin foreach








       $this->layout = "ajax";

       $this->set('pagina',    1);
 	   $this->set('siguiente', 'reactualizacion_muebles');

     	 //$this->set('mensaje', 'La reactualización fue realizada');
         //$this->set('termino', true);
         $this->render('vista_index');








}//fin function




























function reactualizacion_muebles($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }


$this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Bienes muebles ", 1);


	$datos      = $this->v_inventario_muebles_todo->findAll($this->condicionNDEP());
    $total_suma = count($datos);
    $cadena     = "";


foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["v_inventario_muebles_todo"]["cod_presi"];
  $cod_entidad              = $datos_aux["v_inventario_muebles_todo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["v_inventario_muebles_todo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["v_inventario_muebles_todo"]["cod_inst"];
  $cod_dep                  = $datos_aux["v_inventario_muebles_todo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $cod_tipo                   =  $datos_aux['v_inventario_muebles_todo']['cod_tipo'];
  $deno_tipo                  =  $datos_aux['v_inventario_muebles_todo']['deno_tipo'];
  $cod_grupo                  =  $datos_aux['v_inventario_muebles_todo']['cod_grupo'];
  $deno_grupo                 =  $datos_aux['v_inventario_muebles_todo']['deno_grupo'];
  $cod_subgrupo               =  $datos_aux['v_inventario_muebles_todo']['cod_subgrupo'];
  $deno_subgrupo              =  $datos_aux['v_inventario_muebles_todo']['deno_subgrupo'];
  $cod_seccion                =  $datos_aux['v_inventario_muebles_todo']['cod_seccion'];
  $deno_seccion               =  $datos_aux['v_inventario_muebles_todo']['deno_seccion'];
  $numero_identificacion      =  $datos_aux['v_inventario_muebles_todo']['numero_identificacion'];
  $denominacion               =  $datos_aux['v_inventario_muebles_todo']['denominacion'];
  $especificaciones           =  $datos_aux['v_inventario_muebles_todo']['especificaciones'];
  $cantidad                   =  $datos_aux['v_inventario_muebles_todo']['cantidad'];
  $valor_unitario             =  $datos_aux['v_inventario_muebles_todo']['valor_unitario'];
  $cod_tipo_incorporacion     =  $datos_aux['v_inventario_muebles_todo']['cod_tipo_incorporacion'];
  $deno_incorporacion         =  $datos_aux['v_inventario_muebles_todo']['deno_incorporacion'];
  $fecha_incorporacion        =  $datos_aux['v_inventario_muebles_todo']['fecha_incorporacion'];
  $cod_tipo_desincorporacion  =  $datos_aux['v_inventario_muebles_todo']['cod_tipo_desincorporacion'];
  $deno_desincorporacion      =  $datos_aux['v_inventario_muebles_todo']['deno_desincorporacion'];
  $fecha_desincorporacion     =  $datos_aux['v_inventario_muebles_todo']['fecha_desincorporacion'];

$aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");

//pr($aux_datos);

 if(isset($aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"])){

        $parametro_bienes_aux["denominacion"]            = $denominacion;
        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
        $parametro_bienes_aux["fecha_identificacion"]    = cambia_fecha($fecha_incorporacion);
        $parametro_bienes_aux["concepto"]                = $deno_incorporacion;
		$parametro_bienes_aux["monto"]                   = $valor_unitario;
        $parametro_bienes_aux["cod_tipo_cuenta"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_tipo"];
        $parametro_bienes_aux["cod_cuenta"]              = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_cuenta"];
        $parametro_bienes_aux["cod_subcuenta"]           = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_grupo_subcuenta"];
        $parametro_bienes_aux["cod_division"]            = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_subgrupo_division"];
        $parametro_bienes_aux["cod_subdivision"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"];

        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;



		            $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																			      $to      = 1,
																			      $td      = 16,
																			      $rif_doc = null,
																			      $ano_dc  = $ano_select,
																			      $n_dc    = $numero_identificacion,
																			      $f_dc    = cambia_fecha($fecha_incorporacion),
																			      $cpt_dc  = null,
																			      $ben_dc  = null,
																			      $mon_dc  = array(),

																			      $ano_op   = null,
																			      $n_op     = null,
																			      $f_op     = null,

																			      $a_adj_op = null,
																			      $n_adj_op = null,
																			      $f_adj_op = null,
																			      $tp_op    = null,

																			      $deno_ban_pago  = null,
																			      $ano_movimiento = null,
																			      $cod_ent_pago   = null,
																			      $cod_suc_pago   = null,
																			      $cod_cta_pago   = null,

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
																			      $parametro_bienes   = $parametro_bienes_aux
																			  );
        }



}//fin foreach







 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_muebles_desincorporado');
  //$this->set('termino',    true);

    $this->render('vista_index');




}//fin fucntion









function reactualizacion_muebles_desincorporado($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }


$this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Bienes muebles desincorporados", 1);


	$datos      = $this->v_inventario_muebles_todo->findAll($this->condicionNDEP()." and cod_tipo_desincorporacion!=0");
    $total_suma = count($datos);
    $cadena     = "";


foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["v_inventario_muebles_todo"]["cod_presi"];
  $cod_entidad              = $datos_aux["v_inventario_muebles_todo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["v_inventario_muebles_todo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["v_inventario_muebles_todo"]["cod_inst"];
  $cod_dep                  = $datos_aux["v_inventario_muebles_todo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $cod_tipo                   =  $datos_aux['v_inventario_muebles_todo']['cod_tipo'];
  $deno_tipo                  =  $datos_aux['v_inventario_muebles_todo']['deno_tipo'];
  $cod_grupo                  =  $datos_aux['v_inventario_muebles_todo']['cod_grupo'];
  $deno_grupo                 =  $datos_aux['v_inventario_muebles_todo']['deno_grupo'];
  $cod_subgrupo               =  $datos_aux['v_inventario_muebles_todo']['cod_subgrupo'];
  $deno_subgrupo              =  $datos_aux['v_inventario_muebles_todo']['deno_subgrupo'];
  $cod_seccion                =  $datos_aux['v_inventario_muebles_todo']['cod_seccion'];
  $deno_seccion               =  $datos_aux['v_inventario_muebles_todo']['deno_seccion'];
  $numero_identificacion      =  $datos_aux['v_inventario_muebles_todo']['numero_identificacion'];
  $denominacion               =  $datos_aux['v_inventario_muebles_todo']['denominacion'];
  $especificaciones           =  $datos_aux['v_inventario_muebles_todo']['especificaciones'];
  $cantidad                   =  $datos_aux['v_inventario_muebles_todo']['cantidad'];
  $valor_unitario             =  $datos_aux['v_inventario_muebles_todo']['valor_unitario'];
  $cod_tipo_incorporacion     =  $datos_aux['v_inventario_muebles_todo']['cod_tipo_incorporacion'];
  $deno_incorporacion         =  $datos_aux['v_inventario_muebles_todo']['deno_incorporacion'];
  $fecha_incorporacion        =  $datos_aux['v_inventario_muebles_todo']['fecha_incorporacion'];
  $cod_tipo_desincorporacion  =  $datos_aux['v_inventario_muebles_todo']['cod_tipo_desincorporacion'];
  $deno_desincorporacion      =  $datos_aux['v_inventario_muebles_todo']['deno_desincorporacion'];
  $fecha_desincorporacion     =  $datos_aux['v_inventario_muebles_todo']['fecha_desincorporacion'];

$aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");

 if(isset($aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"])){

        $parametro_bienes_aux["denominacion"]            = $denominacion;
        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
        $parametro_bienes_aux["fecha_identificacion"]    = cambia_fecha($fecha_incorporacion);
        $parametro_bienes_aux["concepto"]                = $deno_desincorporacion;
		$parametro_bienes_aux["monto"]                   = $valor_unitario;
        $parametro_bienes_aux["cod_tipo_cuenta"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_tipo"];
        $parametro_bienes_aux["cod_cuenta"]              = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_cuenta"];
        $parametro_bienes_aux["cod_subcuenta"]           = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_grupo_subcuenta"];
        $parametro_bienes_aux["cod_division"]            = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_subgrupo_division"];
        $parametro_bienes_aux["cod_subdivision"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"];

        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;

		            $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																			      $to      = 2,
																			      $td      = 16,
																			      $rif_doc = null,
																			      $ano_dc  = $ano_select,
																			      $n_dc    = $numero_identificacion,
																			      $f_dc    = cambia_fecha($fecha_desincorporacion),
																			      $cpt_dc  = null,
																			      $ben_dc  = null,
																			      $mon_dc  = array(),

																			      $ano_op   = null,
																			      $n_op     = null,
																			      $f_op     = null,

																			      $a_adj_op = null,
																			      $n_adj_op = null,
																			      $f_adj_op = null,
																			      $tp_op    = null,

																			      $deno_ban_pago  = null,
																			      $ano_movimiento = null,
																			      $cod_ent_pago   = null,
																			      $cod_suc_pago   = null,
																			      $cod_cta_pago   = null,

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
																			      $parametro_bienes   = $parametro_bienes_aux
																			  );

 }


}//fin foreach







 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_inmuebles');
  //$this->set('termino',    true);

    $this->render('vista_index');




}//fin fucntion










function reactualizacion_inmuebles($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }


$this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Bienes inmuebles", 1);


	$datos      = $this->v_inventario_inmuebles_todo->findAll($this->condicionNDEP());
    $total_suma = count($datos);
    $cadena     = "";


foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["v_inventario_inmuebles_todo"]["cod_presi"];
  $cod_entidad              = $datos_aux["v_inventario_inmuebles_todo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["v_inventario_inmuebles_todo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["v_inventario_inmuebles_todo"]["cod_inst"];
  $cod_dep                  = $datos_aux["v_inventario_inmuebles_todo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $cod_tipo                   =  $datos_aux['v_inventario_inmuebles_todo']['cod_tipo'];
  $deno_tipo                  =  $datos_aux['v_inventario_inmuebles_todo']['deno_tipo'];
  $cod_grupo                  =  $datos_aux['v_inventario_inmuebles_todo']['cod_grupo'];
  $deno_grupo                 =  $datos_aux['v_inventario_inmuebles_todo']['deno_grupo'];
  $cod_subgrupo               =  $datos_aux['v_inventario_inmuebles_todo']['cod_subgrupo'];
  $deno_subgrupo              =  $datos_aux['v_inventario_inmuebles_todo']['deno_subgrupo'];
  $cod_seccion                =  $datos_aux['v_inventario_inmuebles_todo']['cod_seccion'];
  $deno_seccion               =  $datos_aux['v_inventario_inmuebles_todo']['deno_seccion'];
  $numero_identificacion      =  $datos_aux['v_inventario_inmuebles_todo']['numero_identificacion'];
  $denominacion               =  $datos_aux['v_inventario_inmuebles_todo']['denominacion_inmueble'];
  $avaluo_actual              =  $datos_aux['v_inventario_inmuebles_todo']['avaluo_actual'];
  $cod_tipo_incorporacion     =  $datos_aux['v_inventario_inmuebles_todo']['cod_tipo_incorporacion'];
  $deno_incorporacion         =  $datos_aux['v_inventario_inmuebles_todo']['deno_incorporacion'];
  $fecha_incorporacion        =  $datos_aux['v_inventario_inmuebles_todo']['fecha_incorporacion'];
  $cod_tipo_desincorporacion  =  $datos_aux['v_inventario_inmuebles_todo']['cod_tipo_desincorporacion'];
  $deno_desincorporacion      =  $datos_aux['v_inventario_inmuebles_todo']['deno_desincorporacion'];
  $fecha_desincorporacion     =  $datos_aux['v_inventario_inmuebles_todo']['fecha_desincorporacion'];

$aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");


 if(isset($aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"])){

        $parametro_bienes_aux["denominacion"]            = $denominacion;
        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
        $parametro_bienes_aux["fecha_identificacion"]    = cambia_fecha($fecha_incorporacion);
        $parametro_bienes_aux["concepto"]                = $deno_incorporacion;
		$parametro_bienes_aux["monto"]                   = $avaluo_actual;
        $parametro_bienes_aux["cod_tipo_cuenta"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_tipo"];
        $parametro_bienes_aux["cod_cuenta"]              = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_cuenta"];
        $parametro_bienes_aux["cod_subcuenta"]           = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_grupo_subcuenta"];
        $parametro_bienes_aux["cod_division"]            = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_subgrupo_division"];
        $parametro_bienes_aux["cod_subdivision"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"];

        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;

		            $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																			      $to      = 1,
																			      $td      = 17,
																			      $rif_doc = null,
																			      $ano_dc  = $ano_select,
																			      $n_dc    = $numero_identificacion,
																			      $f_dc    = cambia_fecha($fecha_incorporacion),
																			      $cpt_dc  = null,
																			      $ben_dc  = null,
																			      $mon_dc  = array(),

																			      $ano_op   = null,
																			      $n_op     = null,
																			      $f_op     = null,

																			      $a_adj_op = null,
																			      $n_adj_op = null,
																			      $f_adj_op = null,
																			      $tp_op    = null,

																			      $deno_ban_pago  = null,
																			      $ano_movimiento = null,
																			      $cod_ent_pago   = null,
																			      $cod_suc_pago   = null,
																			      $cod_cta_pago   = null,

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
																			      $parametro_bienes   = $parametro_bienes_aux
																			  );

 }



}//fin foreach







 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_inmuebles_desincorporados');
  //$this->set('termino',    true);

    $this->render('vista_index');




}//fin fucntion










function reactualizacion_inmuebles_desincorporados($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }


$this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0);
    ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
     porcentaje_barra($contar_proceso, 100, "Bienes inmuebles", 1);


	$datos      = $this->v_inventario_inmuebles_todo->findAll($this->condicionNDEP()." and cod_tipo_desincorporacion!=0");
    $total_suma = count($datos);
    $cadena     = "";


foreach($datos as $datos_aux){

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["v_inventario_inmuebles_todo"]["cod_presi"];
  $cod_entidad              = $datos_aux["v_inventario_inmuebles_todo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["v_inventario_inmuebles_todo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["v_inventario_inmuebles_todo"]["cod_inst"];
  $cod_dep                  = $datos_aux["v_inventario_inmuebles_todo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $cod_tipo                   =  $datos_aux['v_inventario_inmuebles_todo']['cod_tipo'];
  $deno_tipo                  =  $datos_aux['v_inventario_inmuebles_todo']['deno_tipo'];
  $cod_grupo                  =  $datos_aux['v_inventario_inmuebles_todo']['cod_grupo'];
  $deno_grupo                 =  $datos_aux['v_inventario_inmuebles_todo']['deno_grupo'];
  $cod_subgrupo               =  $datos_aux['v_inventario_inmuebles_todo']['cod_subgrupo'];
  $deno_subgrupo              =  $datos_aux['v_inventario_inmuebles_todo']['deno_subgrupo'];
  $cod_seccion                =  $datos_aux['v_inventario_inmuebles_todo']['cod_seccion'];
  $deno_seccion               =  $datos_aux['v_inventario_inmuebles_todo']['deno_seccion'];
  $numero_identificacion      =  $datos_aux['v_inventario_inmuebles_todo']['numero_identificacion'];
  $denominacion               =  $datos_aux['v_inventario_inmuebles_todo']['denominacion_inmueble'];
  $avaluo_actual              =  $datos_aux['v_inventario_inmuebles_todo']['avaluo_actual'];
  $cod_tipo_incorporacion     =  $datos_aux['v_inventario_inmuebles_todo']['cod_tipo_incorporacion'];
  $deno_incorporacion         =  $datos_aux['v_inventario_inmuebles_todo']['deno_incorporacion'];
  $fecha_incorporacion        =  $datos_aux['v_inventario_inmuebles_todo']['fecha_incorporacion'];
  $cod_tipo_desincorporacion  =  $datos_aux['v_inventario_inmuebles_todo']['cod_tipo_desincorporacion'];
  $deno_desincorporacion      =  $datos_aux['v_inventario_inmuebles_todo']['deno_desincorporacion'];
  $fecha_desincorporacion     =  $datos_aux['v_inventario_inmuebles_todo']['fecha_desincorporacion'];

$aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");


 if(isset($aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"])){
        $parametro_bienes_aux["denominacion"]            = $denominacion;
        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
        $parametro_bienes_aux["fecha_identificacion"]    = cambia_fecha($fecha_incorporacion);
        $parametro_bienes_aux["concepto"]                = $deno_desincorporacion;
		$parametro_bienes_aux["monto"]                   = $avaluo_actual;
        $parametro_bienes_aux["cod_tipo_cuenta"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_tipo"];
        $parametro_bienes_aux["cod_cuenta"]              = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_cuenta"];
        $parametro_bienes_aux["cod_subcuenta"]           = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_grupo_subcuenta"];
        $parametro_bienes_aux["cod_division"]            = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_subgrupo_division"];
        $parametro_bienes_aux["cod_subdivision"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"];

        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;

		            $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																			      $to      = 2,
																			      $td      = 17,
																			      $rif_doc = null,
																			      $ano_dc  = $ano_select,
																			      $n_dc    = $numero_identificacion,
																			      $f_dc    = cambia_fecha($fecha_desincorporacion),
																			      $cpt_dc  = null,
																			      $ben_dc  = null,
																			      $mon_dc  = array(),

																			      $ano_op   = null,
																			      $n_op     = null,
																			      $f_op     = null,

																			      $a_adj_op = null,
																			      $n_adj_op = null,
																			      $f_adj_op = null,
																			      $tp_op    = null,

																			      $deno_ban_pago  = null,
																			      $ano_movimiento = null,
																			      $cod_ent_pago   = null,
																			      $cod_suc_pago   = null,
																			      $cod_cta_pago   = null,

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
																			      $parametro_bienes   = $parametro_bienes_aux
																			  );

 }

}//fin foreach









 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_rendiciones_generales');
  //$this->set('termino',    true);


$this->render('vista_index');


}//fin fucntion

















function reactualizacion_rendiciones_generales($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros de rendiciones generales ", 1);

	$datos = $this->cfpd30_rendiciones_cuerpo->findAll($this->condicionNDEP()." and ano_rendicion='".$ano_select."'  ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_rendicion, numero_rendicion ASC", 500, $pagina, null);

    $total_suma = count($datos);
    $cadena     = "";

foreach($datos as $datos_aux){

	$motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_rendicion            = $datos_aux["cfpd30_rendiciones_cuerpo"]["ano_rendicion"];
  $numero_rendicion         = $datos_aux["cfpd30_rendiciones_cuerpo"]["numero_rendicion"];
  $fecha_rendicion          = cambiar_formato_fecha($datos_aux["cfpd30_rendiciones_cuerpo"]["fecha_rendicion"]);
  $funcionario_responsable  = $datos_aux["cfpd30_rendiciones_cuerpo"]["funcionario_responsable"];
  $concepto                 = $datos_aux["cfpd30_rendiciones_cuerpo"]["concepto"];
  $fecha_proceso_registro   = cambiar_formato_fecha($datos_aux["cfpd30_rendiciones_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro     = $datos_aux["cfpd30_rendiciones_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cfpd30_rendiciones_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cfpd30_rendiciones_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cfpd30_rendiciones_cuerpo"]["numero_asiento_registro"];
  $username_registro        = $datos_aux["cfpd30_rendiciones_cuerpo"]["username_registro"];
  $condicion_actividad      = $datos_aux["cfpd30_rendiciones_cuerpo"]["condicion_actividad"];
  $dia_asiento_anulacion    = $datos_aux["cfpd30_rendiciones_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cfpd30_rendiciones_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cfpd30_rendiciones_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cfpd30_rendiciones_cuerpo"]["numero_asiento_anulacion"];
  $ano_acta_anulacion       = $datos_aux["cfpd30_rendiciones_cuerpo"]["ano_acta_anulacion"];
  $numero_acta_anulacion    = $datos_aux["cfpd30_rendiciones_cuerpo"]["numero_acta_anulacion"];
  $username_anulacion       = $datos_aux["cfpd30_rendiciones_cuerpo"]["username_anulacion"];
  $fecha_proceso_anulacion  = cambiar_formato_fecha($datos_aux["cfpd30_rendiciones_cuerpo"]["fecha_proceso_anulacion"]);
  $cod_entidad_bancaria     = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_entidad_bancaria"];
  $cod_sucursal             = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_sucursal"];
  $cuenta_bancaria          = $datos_aux["cfpd30_rendiciones_cuerpo"]["cuenta_bancaria"];

  $ano_documento_aux    = substr($fecha_rendicion,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_rendicion=$fecha_proceso_registro; }

  $partidas               = $this->cfpd30_rendiciones_partidas->findAll($conditions = $this->condicion()." and ano_rendicion='$ano_rendicion' and numero_rendicion='$numero_rendicion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
  $suma_para_contabilidad = 0;
			foreach($partidas as $row){
		        $monto = $row['cfpd30_rendiciones_partidas']['monto'];
		        $suma_para_contabilidad += $monto;
		      }

if($cod_entidad_bancaria!=""){

  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                 $to              = 1,
                                                                 $td              = 19,
                                                                 $rif_doc         = null,
                                                                 $ano_dc          = $ano_rendicion,
                                                                 $n_dc            = $numero_rendicion,
                                                                 $f_dc            = $fecha_rendicion,
                                                                 $cpt_dc          = $concepto,
                                                                 $ben_dc          = $funcionario_responsable,
                                                                 $mon_dc          = array("monto"  => $suma_para_contabilidad),

                                                                 $ano_op          = null,
                                                                 $n_op            = null,
                                                                 $f_op            = null,

                                                                 $a_adj_op        = null,
                                                                 $n_adj_op        = null,
                                                                 $f_adj_op        = null,
                                                                 $tp_op           = null,

                                                                 $deno_ban_pago   = null,
                                                                 $ano_movimiento  = null,
                                                                 $cod_ent_pago    = $cod_entidad_bancaria,
                                                                 $cod_suc_pago    = $cod_sucursal,
                                                                 $cod_cta_pago    = $cuenta_bancaria,

                                                                 $num_che_o_debi  = null,
                                                                 $fec_che_o_debi  = null,
                                                                 $clas_che_o_debi = null
                                                             );
}//fin if


}//fin foreach




 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_rendiciones_generales_anulacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function










function reactualizacion_rendiciones_generales_anulacion($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros de rendiciones generales anulación ", 1);

	$datos = $this->cfpd30_rendiciones_cuerpo->findAll($this->condicionNDEP()." and ano_rendicion='".$ano_select."' and condicion_actividad=2 ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_rendicion, numero_rendicion ASC", 500, $pagina, null);

    $total_suma = count($datos);
    $cadena     = "";

foreach($datos as $datos_aux){

	$motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_rendicion            = $datos_aux["cfpd30_rendiciones_cuerpo"]["ano_rendicion"];
  $numero_rendicion         = $datos_aux["cfpd30_rendiciones_cuerpo"]["numero_rendicion"];
  $fecha_rendicion          = cambiar_formato_fecha($datos_aux["cfpd30_rendiciones_cuerpo"]["fecha_rendicion"]);
  $funcionario_responsable  = $datos_aux["cfpd30_rendiciones_cuerpo"]["funcionario_responsable"];
  $concepto                 = $datos_aux["cfpd30_rendiciones_cuerpo"]["concepto"];
  $fecha_proceso_registro   = cambiar_formato_fecha($datos_aux["cfpd30_rendiciones_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro     = $datos_aux["cfpd30_rendiciones_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cfpd30_rendiciones_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cfpd30_rendiciones_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cfpd30_rendiciones_cuerpo"]["numero_asiento_registro"];
  $username_registro        = $datos_aux["cfpd30_rendiciones_cuerpo"]["username_registro"];
  $condicion_actividad      = $datos_aux["cfpd30_rendiciones_cuerpo"]["condicion_actividad"];
  $dia_asiento_anulacion    = $datos_aux["cfpd30_rendiciones_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cfpd30_rendiciones_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cfpd30_rendiciones_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cfpd30_rendiciones_cuerpo"]["numero_asiento_anulacion"];
  $ano_acta_anulacion       = $datos_aux["cfpd30_rendiciones_cuerpo"]["ano_acta_anulacion"];
  $numero_acta_anulacion    = $datos_aux["cfpd30_rendiciones_cuerpo"]["numero_acta_anulacion"];
  $username_anulacion       = $datos_aux["cfpd30_rendiciones_cuerpo"]["username_anulacion"];
  $fecha_proceso_anulacion  = cambiar_formato_fecha($datos_aux["cfpd30_rendiciones_cuerpo"]["fecha_proceso_anulacion"]);
  $cod_entidad_bancaria     = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_entidad_bancaria"];
  $cod_sucursal             = $datos_aux["cfpd30_rendiciones_cuerpo"]["cod_sucursal"];
  $cuenta_bancaria          = $datos_aux["cfpd30_rendiciones_cuerpo"]["cuenta_bancaria"];

  $ano_documento_aux    = substr($fecha_rendicion,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_rendicion=$fecha_proceso_registro; }

  $partidas               = $this->cfpd30_rendiciones_partidas->findAll($conditions = $this->condicion()." and ano_rendicion='$ano_rendicion' and numero_rendicion='$numero_rendicion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
  $suma_para_contabilidad = 0;
			foreach($partidas as $row){
		        $monto = $row['cfpd30_rendiciones_partidas']['monto'];
		        $suma_para_contabilidad += $monto;
		      }

if($cod_entidad_bancaria!=""){

  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                 $to              = 2,
                                                                 $td              = 19,
                                                                 $rif_doc         = null,
                                                                 $ano_dc          = $ano_rendicion,
                                                                 $n_dc            = $numero_rendicion,
                                                                 $f_dc            = $fecha_proceso_anulacion,
                                                                 $cpt_dc          = $concepto,
                                                                 $ben_dc          = $funcionario_responsable,
                                                                 $mon_dc          = array("monto"  => $suma_para_contabilidad),

                                                                 $ano_op          = null,
                                                                 $n_op            = null,
                                                                 $f_op            = null,

                                                                 $a_adj_op        = null,
                                                                 $n_adj_op        = null,
                                                                 $f_adj_op        = null,
                                                                 $tp_op           = null,

                                                                 $deno_ban_pago   = null,
                                                                 $ano_movimiento  = null,
                                                                 $cod_ent_pago    = $cod_entidad_bancaria,
                                                                 $cod_suc_pago    = $cod_sucursal,
                                                                 $cod_cta_pago    = $cuenta_bancaria,

                                                                 $num_che_o_debi  = null,
                                                                 $fec_che_o_debi  = null,
                                                                 $clas_che_o_debi = null
                                                             );
}//fin if


}//fin foreach




 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_reintegros');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function






function reactualizacion_reintegros($pagina=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros de reintegros ", 1);

	$datos = $this->cfpd30_reintegro_cuerpo->findAll($this->condicionNDEP()." and ano_reintegro='".$ano_select."' ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_reintegro, numero_reintegro ASC", 500, $pagina, null);

    $total_suma = count($datos);
    $cadena     = "";

foreach($datos as $datos_aux){

	$motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_reintegro            = $datos_aux["cfpd30_reintegro_cuerpo"]["ano_reintegro"];
  $numero_reintegro         = $datos_aux["cfpd30_reintegro_cuerpo"]["numero_reintegro"];
  $fecha_reintegro          = cambiar_formato_fecha($datos_aux["cfpd30_reintegro_cuerpo"]["fecha_reintegro"]);
  $funcionario_responsable  = $datos_aux["cfpd30_reintegro_cuerpo"]["funcionario_responsable"];
  $concepto                 = $datos_aux["cfpd30_reintegro_cuerpo"]["concepto"];
  $fecha_proceso_registro   = cambiar_formato_fecha($datos_aux["cfpd30_reintegro_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro     = $datos_aux["cfpd30_reintegro_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cfpd30_reintegro_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cfpd30_reintegro_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cfpd30_reintegro_cuerpo"]["numero_asiento_registro"];
  $username_registro        = $datos_aux["cfpd30_reintegro_cuerpo"]["username_registro"];
  $condicion_actividad      = $datos_aux["cfpd30_reintegro_cuerpo"]["condicion_actividad"];
  $dia_asiento_anulacion    = $datos_aux["cfpd30_reintegro_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cfpd30_reintegro_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cfpd30_reintegro_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cfpd30_reintegro_cuerpo"]["numero_asiento_anulacion"];
  $ano_acta_anulacion       = $datos_aux["cfpd30_reintegro_cuerpo"]["ano_acta_anulacion"];
  $numero_acta_anulacion    = $datos_aux["cfpd30_reintegro_cuerpo"]["numero_acta_anulacion"];
  $username_anulacion       = $datos_aux["cfpd30_reintegro_cuerpo"]["username_anulacion"];
  $fecha_proceso_anulacion  = cambiar_formato_fecha($datos_aux["cfpd30_reintegro_cuerpo"]["fecha_proceso_anulacion"]);
  $cod_entidad_bancaria     = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_entidad_bancaria"];
  $cod_sucursal             = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_sucursal"];
  $cuenta_bancaria          = $datos_aux["cfpd30_reintegro_cuerpo"]["cuenta_bancaria"];

  $ano_documento_aux    = substr($fecha_reintegro,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_reintegro=$fecha_proceso_registro; }

  $partidas               = $this->cfpd30_reintegro_partidas->findAll($conditions = $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);

  $montos["monto_pre_compromiso"] = 0;
  $montos["monto_compromiso"]     = 0;
  $montos["monto_causado"]        = 0;
  $montos["monto_pagado"]        = 0;

			foreach($partidas as $row){
				  $monto_pre_compromiso = $row['cfpd30_reintegro_partidas']['monto_pre_compromiso'];
				  $monto_compromiso     = $row['cfpd30_reintegro_partidas']['monto_compromiso'];
				  $monto_causado        = $row['cfpd30_reintegro_partidas']['monto_causado'];
				  $monto_pagado         = $row['cfpd30_reintegro_partidas']['monto_pagado'];

				  $montos["monto_pre_compromiso"] += $monto_pre_compromiso;
				  $montos["monto_compromiso"]     += $monto_compromiso;
				  $montos["monto_causado"]        += $monto_causado;
				  $montos["monto_pagado"]         += $monto_pagado;
		      }//fin foreach

		      if($montos["monto_pre_compromiso"]==0){unset($montos["monto_pre_compromiso"]);}
		      if($montos["monto_compromiso"]==0){    unset($montos["monto_compromiso"]);}
		      if($montos["monto_causado"]==0){       unset($montos["monto_causado"]);}
		      if($montos["monto_pagado"]==0){        unset($montos["monto_pagado"]);}

				if((isset($montos["monto_compromiso"]) || isset($montos["monto_causado"]) || isset($montos["monto_pagado"])) && ($cod_entidad_bancaria!="" && $cod_entidad_bancaria!="0")  ){

				  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
				                                                                 $to              = 1,
				                                                                 $td              = 18,
				                                                                 $rif_doc         = null,
				                                                                 $ano_dc          = $ano_reintegro,
				                                                                 $n_dc            = $numero_reintegro,
				                                                                 $f_dc            = $fecha_reintegro,
				                                                                 $cpt_dc          = $concepto,
				                                                                 $ben_dc          = $funcionario_responsable,
				                                                                 $mon_dc          = $montos,

				                                                                 $ano_op          = null,
				                                                                 $n_op            = null,
				                                                                 $f_op            = null,

				                                                                 $a_adj_op        = null,
				                                                                 $n_adj_op        = null,
				                                                                 $f_adj_op        = null,
				                                                                 $tp_op           = null,

				                                                                 $deno_ban_pago   = null,
				                                                                 $ano_movimiento  = null,
				                                                                 $cod_ent_pago    = $cod_entidad_bancaria,
				                                                                 $cod_suc_pago    = $cod_sucursal,
				                                                                 $cod_cta_pago    = $cuenta_bancaria,

				                                                                 $num_che_o_debi  = null,
				                                                                 $fec_che_o_debi  = null,
				                                                                 $clas_che_o_debi = null
				                                                             );
				}//fin if

}//fin foreach




 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_reintegros_anulacion');
 	//$this->set('termino',    true);




 $this->render('vista_index');



}//fin function

















function reactualizacion_reintegros_anulacion($pagina=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }

    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Registros de reintegro anulación ", 1);

	$datos = $this->cfpd30_reintegro_cuerpo->findAll($this->condicionNDEP()." and ano_reintegro='".$ano_select."' and condicion_actividad=2 ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_reintegro, numero_reintegro ASC", 500, $pagina, null);

    $total_suma = count($datos);
    $cadena     = "";

foreach($datos as $datos_aux){

	$motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_presi"];
  $cod_entidad              = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_inst"];
  $cod_dep                  = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

  $ano_reintegro            = $datos_aux["cfpd30_reintegro_cuerpo"]["ano_reintegro"];
  $numero_reintegro         = $datos_aux["cfpd30_reintegro_cuerpo"]["numero_reintegro"];
  $fecha_reintegro          = cambiar_formato_fecha($datos_aux["cfpd30_reintegro_cuerpo"]["fecha_reintegro"]);
  $funcionario_responsable  = $datos_aux["cfpd30_reintegro_cuerpo"]["funcionario_responsable"];
  $concepto                 = $datos_aux["cfpd30_reintegro_cuerpo"]["concepto"];
  $fecha_proceso_registro   = cambiar_formato_fecha($datos_aux["cfpd30_reintegro_cuerpo"]["fecha_proceso_registro"]);
  $dia_asiento_registro     = $datos_aux["cfpd30_reintegro_cuerpo"]["dia_asiento_registro"];
  $mes_asiento_registro     = $datos_aux["cfpd30_reintegro_cuerpo"]["mes_asiento_registro"];
  $ano_asiento_registro     = $datos_aux["cfpd30_reintegro_cuerpo"]["ano_asiento_registro"];
  $numero_asiento_registro  = $datos_aux["cfpd30_reintegro_cuerpo"]["numero_asiento_registro"];
  $username_registro        = $datos_aux["cfpd30_reintegro_cuerpo"]["username_registro"];
  $condicion_actividad      = $datos_aux["cfpd30_reintegro_cuerpo"]["condicion_actividad"];
  $dia_asiento_anulacion    = $datos_aux["cfpd30_reintegro_cuerpo"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion    = $datos_aux["cfpd30_reintegro_cuerpo"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion    = $datos_aux["cfpd30_reintegro_cuerpo"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion = $datos_aux["cfpd30_reintegro_cuerpo"]["numero_asiento_anulacion"];
  $ano_acta_anulacion       = $datos_aux["cfpd30_reintegro_cuerpo"]["ano_acta_anulacion"];
  $numero_acta_anulacion    = $datos_aux["cfpd30_reintegro_cuerpo"]["numero_acta_anulacion"];
  $username_anulacion       = $datos_aux["cfpd30_reintegro_cuerpo"]["username_anulacion"];
  $fecha_proceso_anulacion  = cambiar_formato_fecha($datos_aux["cfpd30_reintegro_cuerpo"]["fecha_proceso_anulacion"]);
  $cod_entidad_bancaria     = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_entidad_bancaria"];
  $cod_sucursal             = $datos_aux["cfpd30_reintegro_cuerpo"]["cod_sucursal"];
  $cuenta_bancaria          = $datos_aux["cfpd30_reintegro_cuerpo"]["cuenta_bancaria"];

  $ano_documento_aux    = substr($fecha_reintegro,6,4);
  if($ano_select!=$ano_documento_aux){ $fecha_reintegro=$fecha_proceso_registro; }

  $partidas               = $this->cfpd30_reintegro_partidas->findAll($conditions = $this->condicion()." and ano_reintegro='$ano_reintegro' and numero_reintegro='$numero_reintegro'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);

  $montos["monto_pre_compromiso"] = 0;
  $montos["monto_compromiso"]     = 0;
  $montos["monto_causado"]        = 0;
  $montos["monto_pagado"]        = 0;

			foreach($partidas as $row){
				  $monto_pre_compromiso = $row['cfpd30_reintegro_partidas']['monto_pre_compromiso'];
				  $monto_compromiso     = $row['cfpd30_reintegro_partidas']['monto_compromiso'];
				  $monto_causado        = $row['cfpd30_reintegro_partidas']['monto_causado'];
				  $monto_pagado         = $row['cfpd30_reintegro_partidas']['monto_pagado'];

				  $montos["monto_pre_compromiso"] += $monto_pre_compromiso;
				  $montos["monto_compromiso"]     += $monto_compromiso;
				  $montos["monto_causado"]        += $monto_causado;
				  $montos["monto_pagado"]         += $monto_pagado;
		      }//fin foreach

		      if($montos["monto_pre_compromiso"]==0){unset($montos["monto_pre_compromiso"]);}
		      if($montos["monto_compromiso"]==0){    unset($montos["monto_compromiso"]);}
		      if($montos["monto_causado"]==0){       unset($montos["monto_causado"]);}
		      if($montos["monto_pagado"]==0){        unset($montos["monto_pagado"]);}

				if((isset($montos["monto_compromiso"]) || isset($montos["monto_causado"]) || isset($montos["monto_pagado"])) && ($cod_entidad_bancaria!="" && $cod_entidad_bancaria!="0")){

				  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
				                                                                 $to              = 2,
				                                                                 $td              = 18,
				                                                                 $rif_doc         = null,
				                                                                 $ano_dc          = $ano_reintegro,
				                                                                 $n_dc            = $numero_reintegro,
				                                                                 $f_dc            = $fecha_proceso_anulacion,
				                                                                 $cpt_dc          = $concepto,
				                                                                 $ben_dc          = $funcionario_responsable,
				                                                                 $mon_dc          = $montos,

				                                                                 $ano_op          = null,
				                                                                 $n_op            = null,
				                                                                 $f_op            = null,

				                                                                 $a_adj_op        = null,
				                                                                 $n_adj_op        = null,
				                                                                 $f_adj_op        = null,
				                                                                 $tp_op           = null,

				                                                                 $deno_ban_pago   = null,
				                                                                 $ano_movimiento  = null,
				                                                                 $cod_ent_pago    = $cod_entidad_bancaria,
				                                                                 $cod_suc_pago    = $cod_sucursal,
				                                                                 $cod_cta_pago    = $cuenta_bancaria,

				                                                                 $num_che_o_debi  = null,
				                                                                 $fec_che_o_debi  = null,
				                                                                 $clas_che_o_debi = null
				                                                             );
				}//fin if

}//fin foreach




// 	$this->set('mensaje',    "LA REACTUALIZACIÓN DE CONTABILIDAD TERMINO");
// 	$this->set('termino',    true);

 	$this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_cstd03_movimientos_manuales');


    $this->render('vista_index');



}//fin function



















function reactualizacion_cstd03_movimientos_manuales($pagina=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
    $this->layout = "ajax";

    $pagina = null;

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Movimientos manuales ", 1);

	$datos = $this->cstd03_movimientos_manuales->findAll($this->condicionNDEP()." and ano_movimiento='".$ano_select."' ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, tipo_documento, numero_documento ASC");

    $total_suma = count($datos);
    $cadena     = "";

    foreach($datos as $datos_aux){

	$motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cstd03_movimientos_manuales"]["cod_presi"];
  $cod_entidad              = $datos_aux["cstd03_movimientos_manuales"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cstd03_movimientos_manuales"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cstd03_movimientos_manuales"]["cod_inst"];
  $cod_dep                  = $datos_aux["cstd03_movimientos_manuales"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);


  $ano_movimiento             = $datos_aux["cstd03_movimientos_manuales"]["ano_movimiento"];
  $cod_entidad_bancaria       = $datos_aux["cstd03_movimientos_manuales"]["cod_entidad_bancaria"];
  $cod_sucursal               = $datos_aux["cstd03_movimientos_manuales"]["cod_sucursal"];
  $cuenta_bancaria            = $datos_aux["cstd03_movimientos_manuales"]["cuenta_bancaria"];
  $tipo_documento             = $datos_aux["cstd03_movimientos_manuales"]["tipo_documento"];
  $numero_documento           = $datos_aux["cstd03_movimientos_manuales"]["numero_documento"];
  $fecha_documento            = cambiar_formato_fecha($datos_aux["cstd03_movimientos_manuales"]["fecha_documento"]);
  $beneficiario               = $datos_aux["cstd03_movimientos_manuales"]["beneficiario"];
  $monto                      = $datos_aux["cstd03_movimientos_manuales"]["monto"];
  $concepto                   = $datos_aux["cstd03_movimientos_manuales"]["concepto"];
  $fecha_proceso_registro     = $datos_aux["cstd03_movimientos_manuales"]["fecha_proceso_registro"];
  $dia_asiento_registro       = $datos_aux["cstd03_movimientos_manuales"]["dia_asiento_registro"];
  $mes_asiento_registro       = $datos_aux["cstd03_movimientos_manuales"]["mes_asiento_registro"];
  $ano_asiento_registro       = $datos_aux["cstd03_movimientos_manuales"]["ano_asiento_registro"];
  $numero_asiento_registro    = $datos_aux["cstd03_movimientos_manuales"]["numero_asiento_registro"];
  $username_registro          = $datos_aux["cstd03_movimientos_manuales"]["username_registro"];
  $condicion_actividad        = $datos_aux["cstd03_movimientos_manuales"]["condicion_actividad"];
  $ano_anulacion              = $datos_aux["cstd03_movimientos_manuales"]["ano_anulacion"];
  $numero_anulacion           = $datos_aux["cstd03_movimientos_manuales"]["numero_anulacion"];
  $fecha_proceso_anulacion    = cambiar_formato_fecha($datos_aux["cstd03_movimientos_manuales"]["fecha_proceso_anulacion"]);
  $dia_asiento_anulacion      = $datos_aux["cstd03_movimientos_manuales"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion      = $datos_aux["cstd03_movimientos_manuales"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion      = $datos_aux["cstd03_movimientos_manuales"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion   = $datos_aux["cstd03_movimientos_manuales"]["numero_asiento_anulacion"];
  $username_anulacion         = $datos_aux["cstd03_movimientos_manuales"]["username_anulacion"];
  $tipo_recurso               = $datos_aux["cstd03_movimientos_manuales"]["tipo_recurso"];
  $clasificacion_recurso      = $datos_aux["cstd03_movimientos_manuales"]["clasificacion_recurso"];
  $colocacion                 = $datos_aux["cstd03_movimientos_manuales"]["colocacion"];
  $status                     = $datos_aux["cstd03_movimientos_manuales"]["status"];
  $cod_fondo_tercero          = $datos_aux["cstd03_movimientos_manuales"]["cod_fondo_tercero"];




  switch($tipo_documento){
				case 1:{

                            $cstd02_cuentas_bancarias=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
	   					    $disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
	   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
                            if($tipo_cuenta==1){
							                    $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                $cuenta_afectar  = null;
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
												$monto_total["monto_total"] = $monto;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 1,
																										      $td      = 2,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_documento,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = $cuenta_afectar
																										     );
                            }//fin if
				}break;
				case 2:{

                            $cstd02_cuentas_bancarias=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
	   					    $disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
	   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
                            if($tipo_cuenta==1){
							                    $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                $cuenta_afectar  = null;
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
												$monto_total["monto_total"] = $monto;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 1,
																										      $td      = 3,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_documento,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = $cuenta_afectar
																										     );
                            }//fin if
				}break;
				case 3:{

						$var_nota_debito_orden = $this->cstd09_notadebito_cuerpo_pago->findCount("cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_debito='$numero_documento'");

						if($var_nota_debito_orden==0){

                            $cstd02_cuentas_bancarias=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
	   					    $disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
	   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
                            if($tipo_cuenta==1){
							                    $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                $cuenta_afectar  = null;
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
												$monto_total["monto_total"] = $monto;
												$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
				                                $cuenta_afectar_2["tipo_movimiento"] = 2;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 1,
																										      $td      = 20,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_documento,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = $cuenta_afectar,
																										      $cuenta_afectar_2
																										     );
                            }//fin if

						}
				}break;
				case 4:{



                            $cstd02_cuentas_bancarias=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
	   					    $disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
	   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
                            if($tipo_cuenta==1){
							                    $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                $cuenta_afectar  = null;
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
												$monto_total["monto_total"] = $monto;
												$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
				                                $cuenta_afectar_2["tipo_movimiento"] = 1;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 1,
																										      $td      = 20,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_proceso_anulacion,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = $cuenta_afectar,
																										      $cuenta_afectar_2
																										     );

                            }else if($tipo_cuenta==2){

								                $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
								                if($tipo_cuenta==2 && $tipo_documento==4){
								                	if($cod_fondo_tercero=="" || $cod_fondo_tercero=="0" || $cod_fondo_tercero==null  ){
                                                     $cod_fondo_tercero = 5;
								                	}
													 $cod_tipo_enlace=$cod_fondo_tercero;
												}else{
													$cod_tipo_enlace=0;
												}
												$monto_total["monto_total"]          = $monto;
												$cuenta_afectar_2["tipo_retencion"]  = $cod_tipo_enlace;
												$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
		                                        $cuenta_afectar_2["tipo_movimiento"] = 1;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 1,
																										      $td      = 20,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_documento,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = null,
																										      $cuenta_afectar_2
																										     );
                            }//fin if
				}break;


  }//switch

}//fin foreach


    $this->set('pagina',    1);
 	$this->set('siguiente', 'reactualizacion_cstd03_movimientos_manuales_anulacion');
    $this->render('vista_index');
}//fin function






















function reactualizacion_cstd03_movimientos_manuales_anulacion($pagina=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
    $this->layout = "ajax";

    $pagina = null;

	$ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
    porcentaje_barra($contar_proceso, 100, "Movimientos manuales anulación", 1);

	$datos = $this->cstd03_movimientos_manuales->findAll($this->condicionNDEP()." and ano_movimiento='".$ano_select."' and condicion_actividad=2 ", null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, tipo_documento, numero_documento ASC");

    $total_suma = count($datos);
    $cadena     = "";

    foreach($datos as $datos_aux){

	$motor_activo=1;

		$contar_proceso++;

        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

  $cod_presi                = $datos_aux["cstd03_movimientos_manuales"]["cod_presi"];
  $cod_entidad              = $datos_aux["cstd03_movimientos_manuales"]["cod_entidad"];
  $cod_tipo_inst            = $datos_aux["cstd03_movimientos_manuales"]["cod_tipo_inst"];
  $cod_inst                 = $datos_aux["cstd03_movimientos_manuales"]["cod_inst"];
  $cod_dep                  = $datos_aux["cstd03_movimientos_manuales"]["cod_dep"];

        $this->Session->write('SScodpresi',    $cod_presi);
		$this->Session->write('SScodentidad',  $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst',     $cod_inst);
		$this->Session->write('SScoddep',      $cod_dep);

  $ano_movimiento             = $datos_aux["cstd03_movimientos_manuales"]["ano_movimiento"];
  $cod_entidad_bancaria       = $datos_aux["cstd03_movimientos_manuales"]["cod_entidad_bancaria"];
  $cod_sucursal               = $datos_aux["cstd03_movimientos_manuales"]["cod_sucursal"];
  $cuenta_bancaria            = $datos_aux["cstd03_movimientos_manuales"]["cuenta_bancaria"];
  $tipo_documento             = $datos_aux["cstd03_movimientos_manuales"]["tipo_documento"];
  $numero_documento           = $datos_aux["cstd03_movimientos_manuales"]["numero_documento"];
  $fecha_documento            = cambiar_formato_fecha($datos_aux["cstd03_movimientos_manuales"]["fecha_documento"]);
  $beneficiario               = $datos_aux["cstd03_movimientos_manuales"]["beneficiario"];
  $monto                      = $datos_aux["cstd03_movimientos_manuales"]["monto"];
  $concepto                   = $datos_aux["cstd03_movimientos_manuales"]["concepto"];
  $fecha_proceso_registro     = $datos_aux["cstd03_movimientos_manuales"]["fecha_proceso_registro"];
  $dia_asiento_registro       = $datos_aux["cstd03_movimientos_manuales"]["dia_asiento_registro"];
  $mes_asiento_registro       = $datos_aux["cstd03_movimientos_manuales"]["mes_asiento_registro"];
  $ano_asiento_registro       = $datos_aux["cstd03_movimientos_manuales"]["ano_asiento_registro"];
  $numero_asiento_registro    = $datos_aux["cstd03_movimientos_manuales"]["numero_asiento_registro"];
  $username_registro          = $datos_aux["cstd03_movimientos_manuales"]["username_registro"];
  $condicion_actividad        = $datos_aux["cstd03_movimientos_manuales"]["condicion_actividad"];
  $ano_anulacion              = $datos_aux["cstd03_movimientos_manuales"]["ano_anulacion"];
  $numero_anulacion           = $datos_aux["cstd03_movimientos_manuales"]["numero_anulacion"];
  $fecha_proceso_anulacion    = cambiar_formato_fecha($datos_aux["cstd03_movimientos_manuales"]["fecha_proceso_anulacion"]);
  $dia_asiento_anulacion      = $datos_aux["cstd03_movimientos_manuales"]["dia_asiento_anulacion"];
  $mes_asiento_anulacion      = $datos_aux["cstd03_movimientos_manuales"]["mes_asiento_anulacion"];
  $ano_asiento_anulacion      = $datos_aux["cstd03_movimientos_manuales"]["ano_asiento_anulacion"];
  $numero_asiento_anulacion   = $datos_aux["cstd03_movimientos_manuales"]["numero_asiento_anulacion"];
  $username_anulacion         = $datos_aux["cstd03_movimientos_manuales"]["username_anulacion"];
  $tipo_recurso               = $datos_aux["cstd03_movimientos_manuales"]["tipo_recurso"];
  $clasificacion_recurso      = $datos_aux["cstd03_movimientos_manuales"]["clasificacion_recurso"];
  $colocacion                 = $datos_aux["cstd03_movimientos_manuales"]["colocacion"];
  $status                     = $datos_aux["cstd03_movimientos_manuales"]["status"];
  $cod_fondo_tercero          = $datos_aux["cstd03_movimientos_manuales"]["cod_fondo_tercero"];


  switch($tipo_documento){
				case 1:{

                            $cstd02_cuentas_bancarias=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
	   					    $disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
	   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
                            if($tipo_cuenta==1){
							                    $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                $cuenta_afectar  = null;
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
												$monto_total["monto_total"] = $monto;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 2,
																										      $td      = 2,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_proceso_anulacion,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = $cuenta_afectar
																										     );
                            }//fin if
				}break;
				case 2:{

                            $cstd02_cuentas_bancarias=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
	   					    $disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
	   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
                            if($tipo_cuenta==1){
							                    $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                $cuenta_afectar  = null;
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
												$monto_total["monto_total"] = $monto;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 2,
																										      $td      = 3,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_proceso_anulacion,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = $cuenta_afectar
																										     );
                            }//fin if
				}break;
				case 3:{

                            $cstd02_cuentas_bancarias=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
	   					    $disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
	   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
                            if($tipo_cuenta==1){
							                    $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                $cuenta_afectar  = null;
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
												$monto_total["monto_total"] = $monto;
												$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
				                                $cuenta_afectar_2["tipo_movimiento"] = 2;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 2,
																										      $td      = 20,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_proceso_anulacion,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = $cuenta_afectar,
																										      $cuenta_afectar_2
																										     );
                            }//fin if
				}break;
				case 4:{



                            $cstd02_cuentas_bancarias=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
	   					    $disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
	   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
                            if($tipo_cuenta==1){
							                    $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                $cuenta_afectar  = null;
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
												$monto_total["monto_total"] = $monto;
												$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
				                                $cuenta_afectar_2["tipo_movimiento"] = 1;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 2,
																										      $td      = 20,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_proceso_anulacion,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = $cuenta_afectar,
																										      $cuenta_afectar_2
																										     );

                            }else if($tipo_cuenta==2){

								                $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								                $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
								                if(!empty($datos_cuentas)){
								                  foreach($datos_cuentas as $ve_aux_cuenta_1){
								                    $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
													$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                  }
								                }else{
								                	 $var_aux_cuenta   = '301-1-3-12';
													 $cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$monto);
								                }
								                if($tipo_cuenta==2 && $tipo_documento==4){
								                	if($cod_fondo_tercero=="" || $cod_fondo_tercero=="0" || $cod_fondo_tercero==null  ){
                                                     $cod_fondo_tercero = 5;
								                	}
													 $cod_tipo_enlace=$cod_fondo_tercero;
												}else{
													$cod_tipo_enlace=0;
												}
												$monto_total["monto_total"]          = $monto;
												$cuenta_afectar_2["tipo_retencion"]  = $cod_tipo_enlace;
												$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
		                                        $cuenta_afectar_2["tipo_movimiento"] = 1;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																										      $to      = 2,
																										      $td      = 20,
																										      $rif_doc = null,
																										      $ano_dc  = $ano_movimiento,
																										      $n_dc    = $numero_documento,
																										      $f_dc    = $fecha_documento,
																										      $cpt_dc  = null,
																										      $ben_dc  = $beneficiario,
																										      $mon_dc  = $monto_total,
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
																										      $num_che_o_debi  = $numero_documento,
																										      $fec_che_o_debi  = $fecha_documento,
																										      $clas_che_o_debi = null,
																										      $tipo_che_o_debi = null,
																										      $ano_dc_array_pago     = null,
																										      $n_dc_array_pago       = null,
																										      $n_dc_adj_array_pago   = null,
																										      $f_dc_array_pago       = null,
																										      $ano_op_array_pago  = null,
																										      $n_op_array_pago    = null,
																										      $f_op_array_pago    = null,
																										      $tipo_op_array_pago = null,
																										      null,
																										      $f_dc_adj_array_pago= null,
																										      $parametro_bienes   = null,
																										      $cuenta_afectar_2
																										     );
                            }//fin if
				}break;


  }//switch

}//fin foreach


    $this->set('pagina',    1);
 	$this->set('siguiente', 'asientos_manuales');
    $this->render('vista_index');

	/* $this->set('mensaje',    "LA REACTUALIZACIÓN DE CONTABILIDAD TERMINO");
 	$this->set('termino',    true);
    $this->render('vista_index'); */
}//fin function





function asientos_manuales($pagina=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
    $this->layout = "ajax";

	$ano_select = YEAR_REACTUALIZACION;
	set_time_limit(0); ini_set ("memory_limit", "777M");

    $contar_proceso = 0;
	$varsamcontab = $this->Session->read('total_pag_rdtmp_session_contabilidad');
    porcentaje_barra($contar_proceso, 100, "Asientos Manuales Pag:".$pagina."/".$varsamcontab, 1);

	$datos_desc = $this->ccfd10_descripcion_tmp->findAll($this->condicionNDEP()." and ano_asiento=".$ano_select, null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, numero_asiento ASC", 500, $pagina, null);

	$total_suma = count($datos_desc);

foreach($datos_desc as $datos_descrip){

		$contar_proceso++;
        if ($contar_proceso%50 == 0){porcentaje_barra($contar_proceso, $total_suma);}

	$cod1 = $datos_descrip['ccfd10_descripcion_tmp']['cod_presi'];
	$cod2 = $datos_descrip['ccfd10_descripcion_tmp']['cod_entidad'];
	$cod3 = $datos_descrip['ccfd10_descripcion_tmp']['cod_tipo_inst'];
	$cod4 = $datos_descrip['ccfd10_descripcion_tmp']['cod_inst'];
	$cod5 = $datos_descrip['ccfd10_descripcion_tmp']['cod_dep'];
	$ano = $datos_descrip['ccfd10_descripcion_tmp']['ano_asiento'];
	$mes = $datos_descrip['ccfd10_descripcion_tmp']['mes_asiento'];
	$dia = $datos_descrip['ccfd10_descripcion_tmp']['dia_asiento'];
	$numero_asiento_desc = $datos_descrip['ccfd10_descripcion_tmp']['numero_asiento'];
 	$instancia      =   $datos_descrip['ccfd10_descripcion_tmp']['instancia_asiento'];
 	$concepto   	=   $datos_descrip['ccfd10_descripcion_tmp']['concepto'];
 	$tipo_documento =	$datos_descrip['ccfd10_descripcion_tmp']['tipo_documento'];
 	$numero      	=   $datos_descrip['ccfd10_descripcion_tmp']['numero_documento'];
 	$fecha1      	=   $datos_descrip['ccfd10_descripcion_tmp']['fecha_documento'];
 	$fecha       	=   $this->Cfecha($fecha1,'A-M-D');
	$sql_insert_det = "";
	$valores_insertar = array();
	$codigos_depend = "cod_presi=".$cod1." and cod_entidad=".$cod2." and cod_tipo_inst=".$cod3." and cod_inst=".$cod4." and cod_dep=".$cod5;
	$RANDOM  = rand();

$numero_asiento= $this->ccfd05_numero_asiento->field('ccfd05_numero_asiento.numero_asiento', $conditions = $codigos_depend." and ano_asiento='".$ano."'  and mes_asiento='".$mes."' and ".$RANDOM."=".$RANDOM."    ");
if(!empty($numero_asiento)){
	$numero_asiento++;
	$sql_numero = "UPDATE ccfd05_numero_asiento SET numero_asiento='".$numero_asiento."' WHERE ano_asiento='".$ano."' and mes_asiento='".$mes."'  and ".$codigos_depend." and ".$RANDOM."=".$RANDOM."; ";
}else{
    if($mes==1){
		$numero_asiento=2;
	}else{
		$numero_asiento=1;
	}
	$sql_numero = "INSERT INTO ccfd05_numero_asiento VALUES('".$cod1."', '".$cod2."', '".$cod3."', '".$cod4."', '".$cod5."', '".$ano."', '".$mes."', '".$numero_asiento."'); ";
}
	$this->ccfd05_numero_asiento->execute($sql_numero);

   	$num_asiento = $numero_asiento;

	$sql2 = " INSERT INTO ccfd10_descripcion VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano', '$mes', '$dia','$num_asiento','$instancia','".$concepto."','$tipo_documento','$numero','$fecha')";
	$this->ccfd10_descripcion->execute($sql2);


										// **** DETALLES ****

	$datos_detalles = $this->ccfd10_detalles_tmp->findAll($codigos_depend." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia." and numero_asiento=".$numero_asiento_desc, null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento ASC", null, null, null);

	$sql_insert_det = "INSERT INTO ccfd10_detalles VALUES ";

	foreach($datos_detalles as $datos_detalles_tmp){

	$cod1_det = $datos_detalles_tmp['ccfd10_detalles_tmp']['cod_presi'];
	$cod2_det = $datos_detalles_tmp['ccfd10_detalles_tmp']['cod_entidad'];
	$cod3_det = $datos_detalles_tmp['ccfd10_detalles_tmp']['cod_tipo_inst'];
	$cod4_det = $datos_detalles_tmp['ccfd10_detalles_tmp']['cod_inst'];
	$cod5_det = $datos_detalles_tmp['ccfd10_detalles_tmp']['cod_dep'];
	$ano_det = $datos_detalles_tmp['ccfd10_detalles_tmp']['ano_asiento'];
	$mes_det = $datos_detalles_tmp['ccfd10_detalles_tmp']['mes_asiento'];
	$dia_det = $datos_detalles_tmp['ccfd10_detalles_tmp']['dia_asiento'];
	$numero_asiento_det = $datos_detalles_tmp['ccfd10_detalles_tmp']['numero_asiento'];

 	$numero_linea     =   $datos_detalles_tmp['ccfd10_detalles_tmp']['numero_linea'];
 	$debito_credito   =   $datos_detalles_tmp['ccfd10_detalles_tmp']['debito_credito'];
 	$cod_tipo_cuenta  =	$datos_detalles_tmp['ccfd10_detalles_tmp']['cod_tipo_cuenta'];
 	$cod_cuenta       =   $datos_detalles_tmp['ccfd10_detalles_tmp']['cod_cuenta'];
 	$cod_subcuenta    =   $datos_detalles_tmp['ccfd10_detalles_tmp']['cod_subcuenta'];

 	$cod_division     =   $datos_detalles_tmp['ccfd10_detalles_tmp']['cod_division'];
 	$cod_subdivision  =   $datos_detalles_tmp['ccfd10_detalles_tmp']['cod_subdivision'];
 	$monto      	  =   $datos_detalles_tmp['ccfd10_detalles_tmp']['monto'];
	$sql_ccfd02_campos = "";
	$cod_depend_det = "cod_presi=".$cod1_det." and cod_entidad=".$cod2_det." and cod_tipo_inst=".$cod3_det." and cod_inst=".$cod4_det." and cod_dep=".$cod5_det;

	$mes_str  = $this->fecha_str($mes_det);
	if($debito_credito==1){
	    $campo_a         = "debito_acumulado";
		$campo_b         = "debito_".$mes_str;
	}else{
		$campo_a         = "credito_acumulado";
		$campo_b         = "credito_".$mes_str;
	}//fin else

	$cuenta_existe    = $this->ccfd02->findCount($cod_depend_det." and ano_fiscal='".$ano_det."' and cod_tipo_cuenta='".$cod_tipo_cuenta."' and cod_cuenta='".$cod_cuenta."' and cod_subcuenta='".$cod_subcuenta."' and cod_division='".$cod_division."' and cod_subdivision='".$cod_subdivision."' and ".$RANDOM."=".$RANDOM."  ");

	if($cuenta_existe==0){
		$sql_ccfd02_campos   .= "  INSERT INTO ccfd02 (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_fiscal, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision, debito_acumulado, credito_acumulado, debito_ene, credito_ene, debito_feb, credito_feb, debito_mar, credito_mar, debito_abr, credito_abr, debito_may, credito_may, debito_jun, credito_jun, debito_jul, credito_jul, debito_ago, credito_ago, debito_sep, credito_sep, debito_oct, credito_oct, debito_nov, credito_nov, debito_dic, credito_dic) VALUES ('".$cod1_det."', '".$cod2_det."', '".$cod3_det."', '".$cod4_det."', '".$cod5_det."', '".$ano_det."', '".$cod_tipo_cuenta."', '".$cod_cuenta."', '".$cod_subcuenta."', '".$cod_division."', '".$cod_subdivision."', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'); ";
	}

      	$sql_ccfd02_campos   .= "  UPDATE ccfd02 SET ".$campo_b." = ".$campo_b." + ".$monto."   WHERE ".$cod_depend_det." and ano_fiscal='".$ano."' and cod_tipo_cuenta='".$cod_tipo_cuenta."' and cod_cuenta='".$cod_cuenta."' and cod_subcuenta='".$cod_subcuenta."' and cod_division='".$cod_division."'  and cod_subdivision='".$cod_subdivision."' ";

		$this->ccfd02->execute($sql_ccfd02_campos);

		$valores_insertar[] = " ('$cod1_det', '$cod2_det', '$cod3_det', '$cod4_det', '$cod5_det', '$ano_det', '$mes_det','$dia_det','$num_asiento', '$numero_linea', '$debito_credito','$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$monto')";

	}

	$sql_insert_det .= " ".implode(',', $valores_insertar).";";
	$this->ccfd10_detalles->execute($sql_insert_det);

}


 if($this->Session->read('total_pag_rdtmp_session_contabilidad')==$pagina || $this->Session->read('total_pag_rdtmp_session_contabilidad')==0){

	$this->set('mensaje',    "LA REACTUALIZACIÓN DE CONTABILIDAD TERMINO CON EXITO");
	$this->set('pagina', null);
 	$this->set('termino', true);

 }else{

 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'asientos_manuales');
 	//$this->set('termino',    true);

  }

	$this->render('vista_index');

} // fin funcion asientos manuales






}//fin class



?>