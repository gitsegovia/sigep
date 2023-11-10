<?php

class Scripts2Controller extends AppController{

    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    var $uses = array("cscd04_ordencompra_a_pago_partidas", 'cugd02_institucion','Usuario', 'arrd05','cugd04',
                      'cfpd10_reformulacion_partidas', 'cugd04_entrada_modulo', 'arrd05', 'cugd02_dependencia',
                      'cstd07_retenciones_cuerpo_iva', 'cstd07_retenciones_partidas_iva', 'cepd02_contratoservicio_partidas',
                      'cepd02_contratoservicio_cuerpo', 'cscd02_solicitud_encabezado', 'cscd03_cotizacion_cuerpo',
                      'cscd03_cotizacion_encabezado', 'cscd02_solicitud_cuerpo', 'cscd02_solicitud_encabezado_anulado',
                      'cfpd07_obras_partidas', 'cfpd07_obras_cuerpo', 'cfpd07', 'cfpd05', 'cstd07_retenciones_cuerpo_islr',
                      'cscd02_solicitud_numero', "cepd02_contratoservicio_valuacion_partidas", "cscd04_ordencompra_partidas",
                      'cstd07_retenciones_partidas_islr', 'cfpd22','cfpd22_numero_asiento_causado', 'cfpd21_numero_asiento_compromiso',
                      'cscd05_ordencompra_nota_entrega_cuerpo',
                      'cfpd21','cfpd05_auxiliar', 'cobd01_contratoobras_anticipo_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo',
                      'cstd03_cheque_cuerpo','cfpd05_auxiliar','cfpd23_numero_asiento_pagado','cfpd23','cstd03_cheque_partidas',
                      'cstd03_movimientos_manuales',
                      'cugd03_acta_anulacion_cuerpo','cepd01_compromiso_cuerpo','cepd01_compromiso_partidas','cepd03_ordenpago_cuerpo',
                      'cstd09_notadebito_cuerpo', "cscd05_ordencompra_nota_entrega_encabezado",
                      'cstd03_cheque_partidas','cepd03_ordenpago_partidas', 'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_facturas',
                      'cstd30_debito_cuerpo', "cobd01_contratoobras_valuacion_partidas",
                      'cepd02_contratoservicio_cuerpo', 'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas',
                      'cscd04_ordencompra_autorizacion_cuerpo',
                       'cstd03_cheque_cuerpo' , 'cstd03_cheque_ordenes', 'cscd02_solicitud_numero', 'ccfd04_cierre_mes',
                        'cstd30_debito_cuerpo', "cepd02_contratoservicio_valuacion_cuerpo",
                       'cscd04_ordencompra_encabezado', 'cstd09_notadebito_cuerpo_pago', 'cobd01_contratoobras_valuacion_cuerpo',
                       'cscd04_ordencompra_anticipo_cuerpo', 'cstd07_retenciones_cuerpo_islr', 'cstd07_retenciones_partidas_islr',
                       'cstd07_retenciones_cuerpo_iva', 'cstd07_retenciones_partidas_iva', 'cstd07_retenciones_cuerpo_municipal',
                       'cstd07_retenciones_partidas_municipal', 'cstd07_retenciones_cuerpo_timbre', 'cstd07_retenciones_partidas_timbre'
                      );






function index(){$this->layout="ajax";}







function eliminar_valuacion_contrato_obras(){


$this->layout="ajax";

set_time_limit(0);

$datos_cuerpo1 = $this->cobd01_contratoobras_valuacion_cuerpo->findAll("ano_contrato_obra=2008 and numero_orden_pago=0 and condicion_actividad=1");



foreach($datos_cuerpo1 as $aux_ve){



		      $cod_presi                =       $aux_ve["cobd01_contratoobras_valuacion_cuerpo"]["cod_presi"];
			  $cod_entidad              =       $aux_ve["cobd01_contratoobras_valuacion_cuerpo"]["cod_entidad"];
			  $cod_tipo_inst            =       $aux_ve["cobd01_contratoobras_valuacion_cuerpo"]["cod_tipo_inst"];
			  $cod_inst                 =       $aux_ve["cobd01_contratoobras_valuacion_cuerpo"]["cod_inst"];
			  $cod_dep                  =       $aux_ve["cobd01_contratoobras_valuacion_cuerpo"]["cod_dep"];

$condicion = 'cod_presi='.$cod_presi.'  and  cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and  cod_inst='.$cod_inst.' and cod_dep='.$cod_dep.' ';


		    $tipo_documento           =  245;
       	    $concepto_anulacion = "";
			$concepto = $concepto_anulacion;
			$fecha_proceso_anulacion  =  date("d/m/Y");
       	    $condicion_documento      =  2;
  		    $ano_contrato_obra    = $aux_ve["cobd01_contratoobras_valuacion_cuerpo"]["ano_contrato_obra"];
			$num_contrato_obra    = $aux_ve["cobd01_contratoobras_valuacion_cuerpo"]["numero_contrato_obra"];
			$fecha_valuacion      = $aux_ve["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"];
			$fd = $fecha_valuacion;
			$numero_valuacion     = $aux_ve["cobd01_contratoobras_valuacion_cuerpo"]["numero_valuacion"];
			$monto_cancelado      = 0;
			$monto_cancelado_para_cuerpo = 0;
			$datos_partidas = $this->cobd01_contratoobras_valuacion_partidas->findAll($conditions = $condicion." and ano_contrato_obra='$ano_contrato_obra' and numero_contrato_obra='$num_contrato_obra' and numero_valuacion='$numero_valuacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			$sql_update_cscd04_partidas ='';


		foreach($datos_partidas as $row){
			 	$ano                          =   $row['cobd01_contratoobras_valuacion_partidas']['ano'];
			 	$cod_sector                   =   $row['cobd01_contratoobras_valuacion_partidas']['cod_sector'];
			 	$cod_programa                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_programa'];
			 	$cod_sub_prog                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_sub_prog'];
			 	$cod_proyecto                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_proyecto'];
			 	$cod_activ_obra               =   $row['cobd01_contratoobras_valuacion_partidas']['cod_activ_obra'];
			 	$cod_partida                  =   $row['cobd01_contratoobras_valuacion_partidas']['cod_partida'];
			 	$cod_generica                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_generica'];
			 	$cod_especifica               =   $row['cobd01_contratoobras_valuacion_partidas']['cod_especifica'];
			 	$cod_sub_espec                =   $row['cobd01_contratoobras_valuacion_partidas']['cod_sub_espec'];
			 	$cod_auxiliar                 =   $row['cobd01_contratoobras_valuacion_partidas']['cod_auxiliar'];
			 	$monto_partida                =   $row['cobd01_contratoobras_valuacion_partidas']['monto'];
			 	$numero_control_compromiso    =   $row['cobd01_contratoobras_valuacion_partidas']['numero_control_comprom'];
			 	$numero_control_causado       =   $row['cobd01_contratoobras_valuacion_partidas']['numero_control_causado'];
			 	$amortizacion2                =   $row['cobd01_contratoobras_valuacion_partidas']['amortizacion'];
			 	$retencion_laboral2           =   $row['cobd01_contratoobras_valuacion_partidas']['retencion_laboral'];
			 	$retencion_fielcumplimiento2  =   $row['cobd01_contratoobras_valuacion_partidas']['retencion_fielcumplimi'];

			 	$cond1 = $condicion." and ano_contrato_obra='$ano_contrato_obra' and numero_contrato_obra='$num_contrato_obra' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

                $monto_cancelado_para_cuerpo += $monto_partida;


                $datos_cobd01_contratoobras_partidas    =     $this->cobd01_contratoobras_partidas->findAll($cond1);
                foreach($datos_cobd01_contratoobras_partidas as $aux_cobd01_contratoobras_partidas){
		           $amortizacion                =    $aux_cobd01_contratoobras_partidas['cobd01_contratoobras_partidas']['amortizacion'];
		           $cancelado                   =    $aux_cobd01_contratoobras_partidas['cobd01_contratoobras_partidas']['cancelacion'];
		           $retencion_laboral           =    $aux_cobd01_contratoobras_partidas['cobd01_contratoobras_partidas']['retencion_laboral'];
		           $retencion_fielcumplimiento  =    $aux_cobd01_contratoobras_partidas['cobd01_contratoobras_partidas']['retencion_fielcumplimiento'];
	             }//fin foreac

                $amortizacion                 =   $amortizacion - $amortizacion2;
                $retencion_laboral            =   $retencion_laboral - $retencion_laboral2;
                $retencion_fielcumplimiento   =   $retencion_fielcumplimiento -  $retencion_fielcumplimiento2;
                $cancelado                    =   $cancelado - $monto_partida;



			 	$sql_update_cscd04_partidas = "UPDATE cobd01_contratoobras_partidas SET retencion_fielcumplimiento=".$retencion_fielcumplimiento.", retencion_laboral=".$retencion_laboral.", amortizacion=".$amortizacion.", cancelacion=".$cancelado." WHERE ".$cond1.";";
                $sw = $this->cobd01_contratoobras_partidas->execute($sql_update_cscd04_partidas);

          }//fin for


$monto_amortizacion_aux_yy                    =    0;
$monto_retencion_fielcumplimiento_aux_zz      =    0;
$monto_retencion_laboral_aux_zz               =    0;
$monto_cancelar_aux_zz                        =    0;


           $datos_cuerpo = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($conditions = $condicion." and ano_contrato_obra='$ano_contrato_obra' and numero_contrato_obra='$num_contrato_obra' and numero_valuacion='$numero_valuacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);


            foreach($datos_cuerpo as $aux_datos_cuerpo){
		       $monto_amortizacion_aux_yy                   = $aux_datos_cuerpo['cobd01_contratoobras_valuacion_cuerpo']['amortizacion_anticipo'];
		       $monto_cancelar_aux_zz                       = $aux_datos_cuerpo['cobd01_contratoobras_valuacion_cuerpo']['monto_orden_pago'];
		       $monto_retencion_laboral_aux_zz              = $aux_datos_cuerpo['cobd01_contratoobras_valuacion_cuerpo']['monto_retencion_laboral'];
		       $monto_retencion_fielcumplimiento_aux_zz     = $aux_datos_cuerpo['cobd01_contratoobras_valuacion_cuerpo']['monto_retencion_fielcump'];
	        }//fin foreach
			$cond2 = $condicion." and ano_contrato_obra='$ano_contrato_obra' and numero_contrato_obra='$num_contrato_obra' ";
			$datos_orden_compra_encabezado    =     $this->cobd01_contratoobras_cuerpo->findAll($cond2);



            foreach($datos_orden_compra_encabezado as $aux_datos_orden_compra_encabezado){
		       $monto_amortizacion_aux2                  = $aux_datos_orden_compra_encabezado['cobd01_contratoobras_cuerpo']['monto_amortizacion'];
		       $monto_cancelado_aux2                     = $aux_datos_orden_compra_encabezado['cobd01_contratoobras_cuerpo']['monto_cancelado'];
		       $monto_retencion_laboral_aux2             = $aux_datos_orden_compra_encabezado['cobd01_contratoobras_cuerpo']['monto_retencion_laboral'];
		       $monto_retencion_fielcumplimiento_aux2    = $aux_datos_orden_compra_encabezado['cobd01_contratoobras_cuerpo']['monto_retencion_fielcumplimiento'];
	        }//fin foreach

	        $monto_amortizacion_aux2               =  $monto_amortizacion_aux2 - $monto_amortizacion_aux_yy;
	        $monto_retencion_laboral_aux2          =  $monto_retencion_laboral_aux2 - $monto_retencion_laboral_aux_zz;
	        $monto_retencion_fielcumplimiento_aux2 =  $monto_retencion_fielcumplimiento_aux2 - $monto_retencion_fielcumplimiento_aux_zz;
	        $monto_cancelado_aux2                  =  $monto_cancelado_aux2 - $monto_cancelar_aux_zz;


			$sql_update_cscd04_encabezado ="UPDATE cobd01_contratoobras_cuerpo SET monto_retencion_fielcumplimiento=".$monto_retencion_fielcumplimiento_aux2.", monto_retencion_laboral=".$monto_retencion_laboral_aux2.", monto_amortizacion=".$monto_amortizacion_aux2.",  monto_cancelado=".$monto_cancelado_aux2." WHERE ".$cond2.";";
			$sw = $this->cobd01_contratoobras_cuerpo->execute($sql_update_cscd04_encabezado);

			$R1 = $this->cobd01_contratoobras_valuacion_cuerpo->execute("DELETE FROM cobd01_contratoobras_valuacion_partidas  WHERE ".$condicion." and ano_contrato_obra=".$ano_contrato_obra." and numero_contrato_obra='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");
			$R1 = $this->cobd01_contratoobras_valuacion_cuerpo->execute("  DELETE FROM cobd01_contratoobras_valuacion_cuerpo    WHERE ".$condicion." and ano_contrato_obra=".$ano_contrato_obra." and numero_contrato_obra='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");





}//fin foreach



 $this->render("index");


}//fin function
























function eliminar_valuacion_contrato_convenios(){


$this->layout="ajax";


set_time_limit(0);

$datos_cuerpo1 = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll("ano_contrato_servicio=2008 and numero_orden_pago=0 and condicion_actividad=1");


foreach($datos_cuerpo1 as $aux_ve){



		      $cod_presi                =       $aux_ve["cepd02_contratoservicio_valuacion_cuerpo"]["cod_presi"];
			  $cod_entidad              =       $aux_ve["cepd02_contratoservicio_valuacion_cuerpo"]["cod_entidad"];
			  $cod_tipo_inst            =       $aux_ve["cepd02_contratoservicio_valuacion_cuerpo"]["cod_tipo_inst"];
			  $cod_inst                 =       $aux_ve["cepd02_contratoservicio_valuacion_cuerpo"]["cod_inst"];
			  $cod_dep                  =       $aux_ve["cepd02_contratoservicio_valuacion_cuerpo"]["cod_dep"];
              $condicion = 'cod_presi='.$cod_presi.'  and  cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and  cod_inst='.$cod_inst.' and cod_dep='.$cod_dep.' ';




		    $tipo_documento           =  245;
       	    $concepto_anulacion = "";
			$concepto = $concepto_anulacion;
			$fecha_proceso_anulacion  =  date("d/m/Y");
       	    $condicion_documento      =  2;
  		    $ano_contrato_servicio    = $aux_ve["cepd02_contratoservicio_valuacion_cuerpo"]["ano_contrato_servicio"];
			$num_contrato_obra        = $aux_ve["cepd02_contratoservicio_valuacion_cuerpo"]["numero_contrato_servi"];
			$fecha_valuacion          = $aux_ve["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"];
			$fd = $fecha_valuacion;
			$numero_valuacion         = $aux_ve["cepd02_contratoservicio_valuacion_cuerpo"]["numero_valuacion"];
			$monto_cancelado = 0;
			$monto_cancelado_para_cuerpo = 0;
			$datos_partidas = $this->cepd02_contratoservicio_valuacion_partidas->findAll($conditions = $condicion." and ano_contrato_servicio='$ano_contrato_servicio' and numero_contrato_servicio='$num_contrato_obra' and numero_valuacion='$numero_valuacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			$sql_update_cscd04_partidas ='';





		foreach($datos_partidas as $row){
			 	$ano                          =   $row['cepd02_contratoservicio_valuacion_partidas']['ano'];
			 	$cod_sector                   =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_sector'];
			 	$cod_programa                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_programa'];
			 	$cod_sub_prog                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_sub_prog'];
			 	$cod_proyecto                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_proyecto'];
			 	$cod_activ_obra               =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_activ_obra'];
			 	$cod_partida                  =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_partida'];
			 	$cod_generica                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_generica'];
			 	$cod_especifica               =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_especifica'];
			 	$cod_sub_espec                =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_sub_espec'];
			 	$cod_auxiliar                 =   $row['cepd02_contratoservicio_valuacion_partidas']['cod_auxiliar'];
			 	$monto_partida                =   $row['cepd02_contratoservicio_valuacion_partidas']['monto'];
			 	$numero_control_compromiso    =   $row['cepd02_contratoservicio_valuacion_partidas']['numero_control_comp'];
			 	$numero_control_causado       =   $row['cepd02_contratoservicio_valuacion_partidas']['numero_control_caus'];
			 	$amortizacion2                =   $row['cepd02_contratoservicio_valuacion_partidas']['amortizacion'];
			 	$retencion_laboral2           =   $row['cepd02_contratoservicio_valuacion_partidas']['retencion_laboral'];
			 	$retencion_fielcumplimiento2  =   $row['cepd02_contratoservicio_valuacion_partidas']['retencion_fielcumpl'];

			 	$cond1 = $condicion." and ano_contrato_servicio='$ano_contrato_servicio' and numero_contrato_servicio='$num_contrato_obra' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

                $monto_cancelado_para_cuerpo += $monto_partida;

                $datos_cepd02_contratoservicio_partidas    =     $this->cepd02_contratoservicio_partidas->findAll($cond1);
                foreach($datos_cepd02_contratoservicio_partidas as $aux_cepd02_contratoservicio_partidas){
		           $amortizacion                =    $aux_cepd02_contratoservicio_partidas['cepd02_contratoservicio_partidas']['amortizacion'];
		           $cancelado                   =    $aux_cepd02_contratoservicio_partidas['cepd02_contratoservicio_partidas']['cancelacion'];
		           $retencion_laboral           =    $aux_cepd02_contratoservicio_partidas['cepd02_contratoservicio_partidas']['retencion_laboral'];
		           $retencion_fielcumplimiento  =    $aux_cepd02_contratoservicio_partidas['cepd02_contratoservicio_partidas']['retencion_fielcumplimiento'];
	             }//fin foreac

                $amortizacion                 =   $amortizacion - $amortizacion2;
                $retencion_laboral            =   $retencion_laboral - $retencion_laboral2;
                $retencion_fielcumplimiento   =   $retencion_fielcumplimiento -  $retencion_fielcumplimiento2;
                $cancelado                    =   $cancelado - $monto_partida;

			 	$sql_update_cscd04_partidas = "UPDATE cepd02_contratoservicio_partidas SET retencion_fielcumplimiento=".$retencion_fielcumplimiento.", retencion_laboral=".$retencion_laboral.", amortizacion=".$amortizacion.", cancelacion=".$cancelado." WHERE ".$cond1.";";
                $sw = $this->cepd02_contratoservicio_partidas->execute($sql_update_cscd04_partidas);

}//fin for


$monto_amortizacion_aux_yy                    =    0;
$monto_retencion_fielcumplimiento_aux_zz      =    0;
$monto_retencion_laboral_aux_zz               =    0;
$monto_cancelar_aux_zz                        =    0;


           $datos_cuerpo = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($conditions = $condicion." and ano_contrato_servicio='$ano_contrato_servicio' and numero_contrato_servicio='$num_contrato_obra' and numero_valuacion='$numero_valuacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);



            foreach($datos_cuerpo as $aux_datos_cuerpo){
		       $monto_amortizacion_aux_yy                   = $aux_datos_cuerpo['cepd02_contratoservicio_valuacion_cuerpo']['amortizacion_anticipo'];
		       $monto_cancelar_aux_zz                       = $aux_datos_cuerpo['cepd02_contratoservicio_valuacion_cuerpo']['monto_orden_pago'];
		       $monto_retencion_laboral_aux_zz              = $aux_datos_cuerpo['cepd02_contratoservicio_valuacion_cuerpo']['monto_retencion_labor'];
		       $monto_retencion_fielcumplimiento_aux_zz     = $aux_datos_cuerpo['cepd02_contratoservicio_valuacion_cuerpo']['monto_retencion_fielc'];
	        }//fin foreach
			$cond2 = $condicion." and ano_contrato_servicio='$ano_contrato_servicio' and numero_contrato_servicio='$num_contrato_obra' ";
			$datos_orden_compra_encabezado    =     $this->cepd02_contratoservicio_cuerpo->findAll($cond2);


            foreach($datos_orden_compra_encabezado as $aux_datos_orden_compra_encabezado){
		       $monto_amortizacion_aux2                  = $aux_datos_orden_compra_encabezado['cepd02_contratoservicio_cuerpo']['monto_amortizacion'];
		       $monto_cancelado_aux2                     = $aux_datos_orden_compra_encabezado['cepd02_contratoservicio_cuerpo']['monto_cancelado'];
		       $monto_retencion_laboral_aux2             = $aux_datos_orden_compra_encabezado['cepd02_contratoservicio_cuerpo']['monto_retencion_laboral'];
		       $monto_retencion_fielcumplimiento_aux2    = $aux_datos_orden_compra_encabezado['cepd02_contratoservicio_cuerpo']['monto_retencion_fielcumplimient'];
	        }//fin foreach
	        $monto_amortizacion_aux2               =  $monto_amortizacion_aux2 - $monto_amortizacion_aux_yy;
	        $monto_retencion_laboral_aux2          =  $monto_retencion_laboral_aux2 - $monto_retencion_laboral_aux_zz;
	        $monto_retencion_fielcumplimiento_aux2 =  $monto_retencion_fielcumplimiento_aux2 - $monto_retencion_fielcumplimiento_aux_zz;
	        $monto_cancelado_aux2                  =  $monto_cancelado_aux2 - $monto_cancelar_aux_zz;


			$sql_update_cscd04_encabezado ="UPDATE cepd02_contratoservicio_cuerpo SET monto_retencion_fielcumplimiento=".$monto_retencion_fielcumplimiento_aux2.", monto_retencion_laboral=".$monto_retencion_laboral_aux2.", monto_amortizacion=".$monto_amortizacion_aux2.",  monto_cancelado=".$monto_cancelado_aux2." WHERE ".$cond2.";";
			$sw = $this->cepd02_contratoservicio_cuerpo->execute($sql_update_cscd04_encabezado);



             $R1 = $this->cepd02_contratoservicio_valuacion_cuerpo->execute(" DELETE FROM cepd02_contratoservicio_valuacion_partidas WHERE ".$condicion." and ano_contrato_servicio=".$ano_contrato_servicio." and numero_contrato_servicio='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");
		     $R1 = $this->cepd02_contratoservicio_valuacion_cuerpo->execute(" DELETE FROM cepd02_contratoservicio_valuacion_cuerpo WHERE ".$condicion." and ano_contrato_servicio=".$ano_contrato_servicio." and numero_contrato_servicio='".$num_contrato_obra."' and numero_valuacion='".$numero_valuacion."' ");



}//fin foreach



 $this->render("index");


}//fin function



































function eliminar_autorizacion_compra(){


$this->layout="ajax";


set_time_limit(0);

$datos_cuerpo1 = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll("ano_orden_compra=2008 and numero_orden_pago=0 and condicion_actividad=1");


foreach($datos_cuerpo1 as $aux_ve){


		      $cod_presi                =       $aux_ve["cscd04_ordencompra_autorizacion_cuerpo"]["cod_presi"];
			  $cod_entidad              =       $aux_ve["cscd04_ordencompra_autorizacion_cuerpo"]["cod_entidad"];
			  $cod_tipo_inst            =       $aux_ve["cscd04_ordencompra_autorizacion_cuerpo"]["cod_tipo_inst"];
			  $cod_inst                 =       $aux_ve["cscd04_ordencompra_autorizacion_cuerpo"]["cod_inst"];
			  $cod_dep                  =       $aux_ve["cscd04_ordencompra_autorizacion_cuerpo"]["cod_dep"];
              $condicion = 'cod_presi='.$cod_presi.'  and  cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and  cod_inst='.$cod_inst.' and cod_dep='.$cod_dep.' ';


		     $tipo_documento           =  243;
		     $concepto_anulacion = "";
			 $concepto = $concepto_anulacion;
			 $fecha_proceso_anulacion  =  date("d/m/Y");
			 $condicion_documento      =  2;
			 $ano_orden_compra    = $aux_ve["cscd04_ordencompra_autorizacion_cuerpo"]["ano_orden_compra"];
			 $numero_orden_compra = $aux_ve["cscd04_ordencompra_autorizacion_cuerpo"]["numero_orden_compra"];
			 $fecha_orden_compra  = $aux_ve["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"];
			 $fd = $fecha_orden_compra;
			 $amortizacion_aux2 = 0;
			 $amortizacion_prueba = 0;
			 $cancelado_prueba = 0;

			 $numero_orden_compra_autorizacion_pagos = $aux_ve["cscd04_ordencompra_autorizacion_cuerpo"]["numero_pago"];

			 $monto_cancelado = 0;
			 $datos_partidas = $this->cscd04_ordencompra_a_pago_partidas->findAll($conditions = $condicion." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and numero_pago='$numero_orden_compra_autorizacion_pagos'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
			 $sql_update_cscd04_partidas ='';



foreach($datos_partidas as $row){
			 	$ano = $row['cscd04_ordencompra_a_pago_partidas']['ano'];
			 	$cod_sector = $row['cscd04_ordencompra_a_pago_partidas']['cod_sector'];
			 	$cod_programa = $row['cscd04_ordencompra_a_pago_partidas']['cod_programa'];
			 	$cod_sub_prog = $row['cscd04_ordencompra_a_pago_partidas']['cod_sub_prog'];
			 	$cod_proyecto = $row['cscd04_ordencompra_a_pago_partidas']['cod_proyecto'];
			 	$cod_activ_obra = $row['cscd04_ordencompra_a_pago_partidas']['cod_activ_obra'];
			 	$cod_partida = $row['cscd04_ordencompra_a_pago_partidas']['cod_partida'];
			 	$cod_generica = $row['cscd04_ordencompra_a_pago_partidas']['cod_generica'];
			 	$cod_especifica = $row['cscd04_ordencompra_a_pago_partidas']['cod_especifica'];
			 	$cod_sub_espec = $row['cscd04_ordencompra_a_pago_partidas']['cod_sub_espec'];
			 	$cod_auxiliar = $row['cscd04_ordencompra_a_pago_partidas']['cod_auxiliar'];
			 	$monto_partida = $row['cscd04_ordencompra_a_pago_partidas']['monto'];
			 	$amortizacion2 = $row['cscd04_ordencompra_a_pago_partidas']['amortizacion'];
			 	$numero_control_compromiso = $row['cscd04_ordencompra_a_pago_partidas']['numero_control_compromiso'];
			 	$numero_control_causado = $row['cscd04_ordencompra_a_pago_partidas']['numero_control_causado'];
			 	$cond1 = $condicion." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

                $amortizacion_aux2 += $amortizacion2;

				$monto_cancelado2   = $monto_partida - $amortizacion2;
                $datos_cscd04_ordencompra_partidas    =     $this->cscd04_ordencompra_partidas->findAll($cond1);
                foreach($datos_cscd04_ordencompra_partidas as $aux_cscd04_ordencompra_partidas){
		           $amortizacion = $aux_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['amortizacion'];
		           $cancelado    = $aux_cscd04_ordencompra_partidas['cscd04_ordencompra_partidas']['cancelado'];
	             }//fin foreac
                $amortizacion     =  $amortizacion - $amortizacion2;
                $cancelado        =  $cancelado - $monto_partida;

			 	$sql_update_cscd04_partidas .= "UPDATE cscd04_ordencompra_partidas SET amortizacion=".$amortizacion.", cancelado=".$cancelado." WHERE ".$cond1.";";

}//fin for
$sw = $this->cscd04_ordencompra_partidas->execute($sql_update_cscd04_partidas);


$monto_amortizacion_aux_yy = 0;
$monto_cancelado_aux_yy    = 0;
$monto_cancelar_aux_zz     = 0;

           $datos_cuerpo = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($conditions = $condicion." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and numero_pago='$numero_orden_compra_autorizacion_pagos'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
            foreach($datos_cuerpo as $aux_datos_cuerpo){
		       $monto_amortizacion_aux_yy = $aux_datos_cuerpo['cscd04_ordencompra_autorizacion_cuerpo']['amortizacion_anticipo'];
		       $monto_cancelado_aux_yy    = $aux_datos_cuerpo['cscd04_ordencompra_autorizacion_cuerpo']['monto_cancelado'];
		       $monto_cancelar_aux_zz     = $aux_datos_cuerpo['cscd04_ordencompra_autorizacion_cuerpo']['monto_cancelar'];
	        }//fin foreach
			$cond2 = $condicion." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra'";
			$cond3 = $condicion." and ano_ordencompra='$ano_orden_compra' and numero_ordencompra='$numero_orden_compra'";
			$datos_orden_compra_encabezado    =     $this->cscd04_ordencompra_encabezado->findAll($cond2);


            foreach($datos_orden_compra_encabezado as $aux_datos_orden_compra_encabezado){
		       $monto_amortizacion_aux2        =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_amortizacion'];
		       $monto_cancelado_aux2           =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_cancelado'];
		       $monto_ordencompra              =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];
		       $monto_aumento_ordencompra      =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['modificacion_aumento'];
		       $monto_disminucion_ordencompra  =  $aux_datos_orden_compra_encabezado['cscd04_ordencompra_encabezado']['modificacion_disminucion'];
	        }//fin foreach


	        $monto_total_ordencompra = ($monto_ordencompra + $monto_aumento_ordencompra) - $monto_disminucion_ordencompra;
	        $monto_amortizacion_aux2   = $monto_amortizacion_aux2 - $monto_amortizacion_aux_yy;
	        $monto_cancelado_aux2      = $monto_cancelado_aux2 - ($monto_cancelar_aux_zz - $monto_amortizacion_aux_yy);

	        $rif_notaentrega = $this->cscd05_ordencompra_nota_entrega_encabezado->field('cscd05_ordencompra_nota_entrega_encabezado.rif', $conditions = $cond2, $order = null);
	        $ano_notaentrega = $this->cscd05_ordencompra_nota_entrega_encabezado->field('cscd05_ordencompra_nota_entrega_encabezado.ano_nota_entrega', $conditions = $cond2, $order = null);
	        $num_notaentrega = $this->cscd05_ordencompra_nota_entrega_encabezado->field('cscd05_ordencompra_nota_entrega_encabezado.numero_nota_entrega', $conditions = $cond2, $order = null);
	        $num_cotizacion = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.numero_cotizacion', $conditions = $cond3, $order = null);

			$cond_nota = $condicion." and ano_nota_entrega='$ano_notaentrega' and numero_nota_entrega='$num_notaentrega' and rif='$rif_notaentrega'";


	        if($monto_total_ordencompra == $monto_cancelado_aux2){
	        	$sql_delete_encabezado = "DELETE FROM cscd05_ordencompra_nota_entrega_encabezado WHERE ".$cond_nota." ; ";
	        	$sql_delete_cuerpo = "DELETE FROM cscd05_ordencompra_nota_entrega_cuerpo WHERE ".$cond_nota." ; ";
	        	$sql_update_cotizacion = "UPDATE cscd03_cotizacion_cuerpo SET cantidad_entregada=0 WHERE ".$condicion." and ano_cotizacion='$ano_orden_compra' and numero_cotizacion='$num_cotizacion'";

				$this->cscd03_cotizacion_cuerpo->execute($sql_update_cotizacion);
	        	$this->cscd04_ordencompra_autorizacion_cuerpo->execute($sql_delete_encabezado);
	        	$this->cscd04_ordencompra_autorizacion_cuerpo->execute($sql_delete_cuerpo);
                $sql_update_cscd04_encabezado ="UPDATE cscd04_ordencompra_encabezado SET entrega_completa=0, monto_amortizacion=".$monto_amortizacion_aux2.", monto_cancelado=".$monto_cancelado_aux2."  WHERE ".$cond2.";";
	        }else{
	        	$sql_update_cscd04_encabezado ="UPDATE cscd04_ordencompra_encabezado SET  monto_amortizacion=".$monto_amortizacion_aux2.", monto_cancelado=".$monto_cancelado_aux2."  WHERE ".$cond2.";";
			}//fin else

			$sw = $this->cscd04_ordencompra_encabezado->execute($sql_update_cscd04_encabezado);

			 $R1 = $this->cscd04_ordencompra_autorizacion_cuerpo->execute("DELETE FROM cscd04_ordencompra_autorizacion_pago_partidas   WHERE ".$condicion." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_pago=".$numero_orden_compra_autorizacion_pagos);
			 $R1 = $this->cscd04_ordencompra_autorizacion_cuerpo->execute("       DELETE FROM cscd04_ordencompra_autorizacion_pago_cuerpo     WHERE ".$condicion." and ano_orden_compra=".$ano_orden_compra." and numero_orden_compra=".$numero_orden_compra." and numero_pago=".$numero_orden_compra_autorizacion_pagos);




}//fin foreach



 $this->render("index");


}//fin function













































}//fin class


?>