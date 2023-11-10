<?php

class ScriptCorrecionesPanelController extends AppController {

   var $name = "script_correciones_panel";
   var $uses = array('arrd05','v_restaurar_pagados','v_restaurar_causados_op','v_restaurar_compromisos',
					 'v_reformulacion_verificar','a_control_panel',
                     'cstd01_entidades_bancarias','cstd03_cheque_cuerpo','cstd03_cheque_partidas',
					 'cstd09_notadebito_cuerpo','cstd09_notadebito_cuerpo_propio','cstd01_entidades_bancarias',
					 'cstd01_sucursales_bancarias','cstd02_cuentas_bancarias','cstd03_movimientos_manuales',
                     'cstd07_retenciones_cuerpo_iva', 'cstd07_retenciones_cuerpo_municipal','cstd07_retenciones_cuerpo_timbre',
                     'cstd07_retenciones_cuerpo_islr', 'cstd09_notadebito_cuerpo_pago',
					 'cfpd07_obras_cuerpo','cfpd07_obras_partidas','cfpd05','cfpd20','cfpd21','cfpd22','cfpd23',
					 'cfpd21_numero_asiento_compromiso','cfpd22_numero_asiento_causado','cfpd23_numero_asiento_pagado',
					 'cfpd10_reformulacion_partidas', 'cfpd10_reformulacion_texto','cpcd02',
					 'cobd01_contratoobras_cuerpo','cobd01_co_modificacion_cuerpo',
                     'cobd01_contratoobras_partidas','cobd01_contratoobras_valuacion_cuerpo','cobd01_contratoobras_valuacion_partidas','cobd01_contratoobras_retencion_partidas',
                     'cobd01_co_modificacion_partidas','cobd01_contratoobras_anticipo_cuerpo','cobd01_contratoobras_anticipo_partidas',
                     'cugd03_acta_anulacion_numero','cugd03_acta_anulacion_cuerpo','cugd04',
                     'cepd01_compromiso_cuerpo','cepd01_compromiso_partidas','cepd01_compromiso_beneficiario_rif',
                     'cepd01_compromiso_beneficiario_cedula','cepd03_ordenpago_cuerpo',
                     'cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas','cepd02_contratoservicio_retencion_partidas',
                     'cepd02_contratoservicio_cuerpo','cepd02_cs_modificacion_cuerpo','cepd02_cs_modificacion_partidas',
                     'cepd02_contratoservicio_partidas','cepd02_contratoservicio_valuacion_cuerpo',
                     'cepd02_contratoservicio_valuacion_partidas','cepd03_ordenpago_partidas','cepd03_ordenpago_cuerpo',
                     'cepd01_compromiso_numero','cepd03_ordenpago_facturas',
                     'cscd04_ordencompra_encabezado','cscd04_ordencompra_autorizacion_cuerpo', 'Usuario',
                     'cscd04_ordencompra_partidas','cscd04_ordencompra_anticipo_cuerpo','cscd04_ordencompra_a_pago_partidas',
                     'cnmd06_datos_personales','cnmd06_datos_educativos','cnmd06_datos_formacion_profesional','cnmd06_datos_registro_titulo',
					 'cnmd06_datos_familiares','cnmd06_experiencia_administrativa','cnmd06_datos_otrasexperiencias_laborables',
					 'cnmd06_datos_bienes','cnmd06_soportes','cnmd06_datos_permisos','cnmd06_datos_amonestaciones','cnmd06_fichas',
					 'cnmd06_fichas_h_c_a','cnmd08_historia_trabajador','ccfd04_cierre_mes'
                    );
   var $helpers = array('Html','Ajax','Javascript','Sisap');
   var $layout="script_correciones";



function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re  = "cod_presi=".$this->verifica_SS(1)."  and    ";
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

//Sql Codigos Institucion:


function SQLCA_noDEP(){
	$sql_codig = CODIGOSCONDICION;
	return $sql_codig;
}//fin funcion SQL


function condicionNDEP_script(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

  return $condicion;

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


function checkSession(){


				if (!$this->Session->check('Root_session')){
						$this->redirect('/script_correciones/salir/');
						exit();
				}else{
					if($this->Session->read('Root_session')!="VISION_INTEGRAL"){
						$this->redirect('/script_correciones/salir/');
						 exit();
					}
				}

}//fin checksession



function beforeFilter(){

	$this->checkSession();

}



function index(){

	$ano_ejecucion=YEAR_REACTUALIZACION;
    $this->Session->write('ano_ejecucion',YEAR_REACTUALIZACION);

    $total_op=$this->v_restaurar_causados_op->findCount($this->condicionNDEP_script()." and ano_orden_pago=".$ano_ejecucion);
    $this->set("total_op",$total_op);
    $Tpag = (int)ceil($total_op/10000);
    $this->set("total_pag_op",$Tpag);
    $this->Session->write('total_pag_op_session', $Tpag);


    $total_rc= $this->v_restaurar_compromisos->findCount($this->condicionNDEP_script()." and ano_documento=".$ano_ejecucion);
    $this->set("total_rc",$total_rc);
    $Tpag_rc = (int)ceil($total_rc/10000);
    $this->set("total_pag_rc",$Tpag_rc);
    $this->Session->write('total_pag_rc_session', $Tpag_rc);

    $total_ch=$this->v_restaurar_pagados->findCount($this->condicionNDEP_script()." and ano_movimiento=".$ano_ejecucion);
    $this->set("total_ch",$total_ch);
    $Tpag_ch = (int)ceil($total_ch/10000);
    $this->set("total_pag_ch",$Tpag_ch);
    $this->Session->write('total_pag_ch_session', $Tpag_ch);


//echo " <br><br><br><br><br><br><br><br><br>".$Tpag_ch." *** ".$Tpag;


    $total_op=$this->cepd03_ordenpago_cuerpo->findCount($this->condicionNDEP_script()." and ano_orden_pago=".$ano_ejecucion);
    $this->set("total_op",$total_op);
    $Tpag = (int)ceil($total_op/500);
    $this->set("total_pag_op",$Tpag);
     $this->Session->write('total_pag_op_session_contabilidad', $Tpag);

    //$total_rc=$this->cepd01_compromiso_partidas->findCount("cod_presi=".$someone['Usuario']['cod_presi']." and cod_entidad=".$someone['Usuario']['cod_entidad']." and cod_tipo_inst=".$someone['Usuario']['cod_tipo_inst']." and cod_inst=".$someone['Usuario']['cod_inst']." and ano_documento=".$ano_ejecucion);
    $total_rc= $this->cepd01_compromiso_cuerpo->findCount($this->condicionNDEP_script()." and ano_documento=".$ano_ejecucion);
    $this->set("total_rc",$total_rc);
    $Tpag_rc = (int)ceil($total_rc/500);
    $this->set("total_pag_rc",$Tpag_rc);
    $this->Session->write('total_pag_rc_session_contabilidad', $Tpag_rc);

    $total_ch=$this->cstd03_cheque_cuerpo->findCount($this->condicionNDEP_script()." and ano_movimiento=".$ano_ejecucion);
    $this->set("total_ch",$total_ch);
    $Tpag_ch = (int)ceil($total_ch/500);
    $this->set("total_pag_ch",$Tpag_ch);
    $this->Session->write('total_pag_ch_session_contabilidad', $Tpag_ch);

    $total_ch_anulacion =$this->cstd03_cheque_cuerpo->findCount($this->condicionNDEP_script()." and ano_movimiento=".$ano_ejecucion." and condicion_actividad=2");
    $total_ch_anulacion = (int)ceil($total_ch_anulacion/500);
    $this->Session->write('total_pag_ch_anulacion_session_contabilidad', $total_ch_anulacion);

    $total_debi =$this->cstd09_notadebito_cuerpo_pago->findCount($this->condicionNDEP_script()." and ano_movimiento=".$ano_ejecucion."");
    $total_debi = (int)ceil($total_debi/500);
    $this->Session->write('total_pag_debi_session_contabilidad', $total_debi);

    $total_orden_compra =$this->cscd04_ordencompra_encabezado->findCount($this->condicionNDEP_script()." and ano_orden_compra=".$ano_ejecucion."");
    $total_orden_compra = (int)ceil($total_orden_compra/500);
    $this->Session->write('total_pag_orden_compra_session_contabilidad', $total_orden_compra);


    $data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
    $this->set('a_control_panels',$data_control_pane);
}



function configurar_reactualizacion ($var = null) {
	$this->layout = "ajax";
    if(isset($var) && $var=='form'){
        $ANO    = $this->data['reactualizacion']['ano'];
        $CONTAB = $this->data['reactualizacion']['contabilidad']==''?1:$this->data['reactualizacion']['contabilidad'];
        $CIERRE = $this->data['reactualizacion']['sistema_cerrado']==''?0:$this->data['reactualizacion']['sistema_cerrado'];
    	$SQL = "UPDATE a_control_panel SET  ano_reactualizacion=$ANO , contabilidad=$CONTAB , sistema_cerrado=$CIERRE WHERE ".CODIGOSCONDICION;
        $this->a_control_panel->execute($SQL);
        $this->set('CIERRE',$CIERRE);
    }
    $data_control_pane = $this->a_control_panel->findAll(CODIGOSCONDICION);
    $this->set('a_control_panels',$data_control_pane);

}


function vista_index(){}



function limpiar_tablas($var=null){

 $this->layout = "ajax";

 $data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }

if($data_control_pane[0]['a_control_panel']['sistema_cerrado']==0){
 $this->layout = "ajax";
 $this->set('mensaje', "EL ACCESO AL SISTEMA NO SE ENCUENTRA CERRADO POR FAVOR CIERRELO");
 $this->set('termino', 'vista_index');
 $this->render('vista_index');
}else{

$ano_select = YEAR_REACTUALIZACION;

$total  = 12;
$contar = 0;

porcentaje_barra($contar, $total, "vaciar tablas", 1);

$var1 = $this->cugd04->execute("UPDATE cfpd05 SET
											compromiso_anual = 0 ,
											causado_anual = 0 ,
											pagado_anual = 0 ,
											compromiso_ene = 0 ,
											causado_ene = 0 ,
											pagado_ene = 0 ,
											compromiso_feb = 0 ,
											causado_feb = 0 ,
											pagado_feb = 0 ,
											compromiso_mar = 0 ,
											causado_mar = 0 ,
											pagado_mar = 0 ,
											compromiso_abr = 0 ,
											causado_abr = 0 ,
											pagado_abr = 0 ,
											compromiso_may = 0 ,
											causado_may = 0 ,
											pagado_may = 0 ,
											compromiso_jun = 0 ,
											causado_jun = 0 ,
											pagado_jun = 0 ,
											compromiso_jul = 0 ,
											causado_jul = 0 ,
											pagado_jul = 0 ,
											compromiso_ago = 0 ,
											causado_ago = 0 ,
											pagado_ago = 0 ,
											compromiso_sep = 0 ,
											causado_sep = 0 ,
											pagado_sep = 0 ,
											compromiso_oct = 0 ,
											causado_oct = 0 ,
											pagado_oct = 0 ,
											compromiso_nov = 0 ,
											causado_nov = 0 ,
											pagado_nov = 0 ,
											compromiso_dic = 0 ,
											causado_dic = 0 ,
											pagado_dic = 0, precompromiso_congelado = 0 WHERE ".$this->SQLCA_noDEP()." and ano=".$ano_select);

     if($var1 > 1){

     	                     $var2 = $this->cfpd20->execute("delete from cfpd20 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);
     	                     $contar++;
     	                     porcentaje_barra($contar, $total);

     	if($var2 > 1){       $var3 = $this->cfpd05->execute("update cfpd05 set rebaja_anual=0, rebaja_ene=0, rebaja_feb=0, rebaja_mar=0, rebaja_abr=0, rebaja_may=0, rebaja_jun=0, rebaja_jul=0, rebaja_ago=0, rebaja_sep=0, rebaja_oct=0, rebaja_nov=0, rebaja_dic=0 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);
                             $contar++;
     	                     porcentaje_barra($contar, $total);


     		if($var3 > 1){   $var4 = $this->cfpd05->execute("update cfpd05 set disminucion_traslado_anual=0, disminucion_traslado_ene=0, disminucion_traslado_feb=0, disminucion_traslado_mar=0, disminucion_traslado_abr=0, disminucion_traslado_may=0, disminucion_traslado_jun=0, disminucion_traslado_jul=0, disminucion_traslado_ago=0, disminucion_traslado_sep=0, disminucion_traslado_oct=0, disminucion_traslado_nov=0, disminucion_traslado_dic=0 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);
                             $contar++;
     	                     porcentaje_barra($contar, $total);

     			if($var4 > 1){ $var5 = $this->cfpd05->execute("update cfpd05 set aumento_traslado_anual=0, aumento_traslado_ene=0, aumento_traslado_feb=0, aumento_traslado_mar=0, aumento_traslado_abr=0, aumento_traslado_may=0, aumento_traslado_jun=0, aumento_traslado_jul=0, aumento_traslado_ago=0, aumento_traslado_sep=0, aumento_traslado_oct=0, aumento_traslado_nov=0, aumento_traslado_dic=0 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);
                               $contar++;
     	                       porcentaje_barra($contar, $total);

     				if($var5 > 1){ $var6 = $this->cfpd05->execute("update cfpd05 set credito_adicional_anual=0, credito_adicional_ene=0, credito_adicional_feb=0, credito_adicional_mar=0, credito_adicional_abr=0, credito_adicional_may=0, credito_adicional_jun=0, credito_adicional_jul=0, credito_adicional_ago=0, credito_adicional_sep=0, credito_adicional_oct=0, credito_adicional_nov=0, credito_adicional_dic=0 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);
                                   $contar++;
     	                           porcentaje_barra($contar, $total);


     					if($var6 > 1){ $var7 = $this->cugd04->execute("DELETE FROM cfpd21 WHERE ".$this->SQLCA_noDEP()." and ano=".$ano_select);
     						           $contar++;
     	                               porcentaje_barra($contar, $total);

     						if($var7 > 1){ $var8 = $this->cugd04->execute("DELETE FROM cfpd22 WHERE ".$this->SQLCA_noDEP()." and ano=".$ano_select);
     							           $contar++;
     	                                   porcentaje_barra($contar, $total);

     							if($var8 > 1){ $var9 = $this->cugd04->execute("DELETE FROM cfpd23 WHERE ".$this->SQLCA_noDEP()." and ano=".$ano_select);
     								           $contar++;
     	                                       porcentaje_barra($contar, $total);

     								if($var9 > 1){ $var10 = $this->cugd04->execute("DELETE FROM cfpd21_numero_asiento_compromiso WHERE ".$this->SQLCA_noDEP()." and ano_compromiso=".$ano_select);
                                                   $contar++;
     	                                           porcentaje_barra($contar, $total);


     									if($var10 > 1){ $var11 = $this->cugd04->execute("DELETE FROM cfpd22_numero_asiento_causado WHERE ".$this->SQLCA_noDEP()." and ano_causado=".$ano_select);
                                                        $contar++;
     	                                                porcentaje_barra($contar, $total);

     										if($var11 > 1){ $var12 = $this->cugd04->execute("DELETE FROM cfpd23_numero_asiento_pagado WHERE ".$this->SQLCA_noDEP()." and ano_pagado=".$ano_select);
                                                            $contar++;
     	                                                    porcentaje_barra($contar, $total);

     											if($var12 > 1){

     												         $contar++;
     	                                                     porcentaje_barra($contar, $total);

     												 if($var!=null){$this->reformulacion($var);}else{
												     	 $this->layout = "ajax";
												     	 $this->set('inicio', true);
												     	 ////$this->set('mensaje', 'Las tablas fueron vaciadas con exito');

												         $this->set('siguiente', 'otros_compromisos/var_null');
												     	 $this->set('pagina',    1);
												         $this->render('vista_index');
												     }

     												}//fin if

     												}//fin if

     												}//fin if

     												}//fin if

     												}//fin if

     												}//fin if

     												}//fin if

     												}//fin if

     												}//fin if

     												}//fin if

     												}//fin if

                                                    }//fin if



     //$this->cugd04->execute("ALTER SEQUENCE cfpd21_consecutivo_seq RESTART WITH 1;");
     //$this->cugd04->execute("ALTER SEQUENCE cfpd22_consecutivo_seq RESTART WITH 1;");
     //$this->cugd04->execute("ALTER SEQUENCE cfpd23_consecutivo_seq RESTART WITH 1;");
}

}//fin fuction





/**********************************************************+
 * OTROS COMPROMISOS
 *************************************************************/
function otros_compromisos($var=null, $pagina=null){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }

    $nco=0;
	$nca=0;
	$npa=0;
	$this->Session->write('numero_compromiso', $nco);
	$this->Session->write('numero_causado', $nca);
	$this->Session->write('numero_pagado', $npa);

    $ano_select = YEAR_REACTUALIZACION;
    set_time_limit(0);
    $contar_proceso=0;

    porcentaje_barra($contar_proceso, 100, "Otros Compromisos Pag:".$pagina."/".$this->Session->read('total_pag_rc_session'), 1);

	$partidas= $this->v_restaurar_compromisos->findAll("ano_documento=".$ano_select,null,'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,numero_documento,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',10000,$pagina,null);
	$values="";
	$monto=0;
	$i=0;
	$ann=YEAR_REACTUALIZACION;
	$j=1;
	$x=1;
	$veri_documento=0;
	$suma = count($partidas);
	$ver_bandera = true;

	$mensaje="Pag:[".$pagina."] Registros de Otros Compromisos fueron actualizados con exito";

	  foreach($partidas as $partidaX){

		$contar_proceso++;

        if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $suma);}

		$partida[0]=$partidaX['v_restaurar_compromisos'];
		$cod_presi                           =         $partida[0]['cod_presi'];
		$cod_entidad                         =         $partida[0]['cod_entidad'];
		$cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		$cod_inst                            =         $partida[0]['cod_inst'];
		$cod_dep                             =         $partida[0]['cod_dep'];
		$this->Session->write('SScodpresi', $cod_presi);
		$this->Session->write('SScodentidad', $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst', $cod_inst);
		$this->Session->write('SScoddep', $cod_dep);
		$ano_documento                       =         $partida[0]['ano_documento'];
		$fecha_registro                      =         cambiar_formato_fecha($partida[0]['fecha_proceso_registro']);
		$fd                                  =         cambiar_formato_fecha($partida[0]['fecha_documento']);
		$ano_fd                              =         (int) divide_fecha($partida[0]['fecha_documento'],'ano');
		$ano_fdpartida                       =         (int) $partida[0]['ano'];

		if($ano_fd!=$ano_fdpartida){
		     $fecha_documento=$fecha_registro;
		     $fd=$fecha_registro;
		}else{
		  	$fecha_documento=$fd;
	    }

		$numero_documento          = $partida[0]['numero_documento'];
		$ano                       = $partida[0]['ano'];
		$cod_sector                = $partida[0]['cod_sector'];
		$cod_programa              = $partida[0]['cod_programa'];
		$cod_sub_prog              = $partida[0]['cod_sub_prog'];
		$cod_proyecto              = $partida[0]['cod_proyecto'];
		$cod_activ_obra            = $partida[0]['cod_activ_obra'];
		$cod_partida               = $partida[0]['cod_partida'];
		$cod_generica              = $partida[0]['cod_generica'];
		$cod_especifica            = $partida[0]['cod_especifica'];
		$cod_sub_espec             = $partida[0]['cod_sub_espec'];
		$cod_auxiliar              = $partida[0]['cod_auxiliar'];
		$monto                     = $partida[0]['monto'];
		$numero_control_compromiso = $partida[0]['numero_control_compromiso'];
		$numero_orden_pago         = $partida[0]['numero_orden_pago'];
		$ano_orden_pago            = $partida[0]['ano_orden_pago'];
		$concepto                  = $partida[0]['concepto'];
		$beneficiario_rif          = $partida[0]['beneficiario_rif'];
		$beneficiario_cedula       = $partida[0]['beneficiario_cedula'];

		$resultado = strpos($concepto, "BENEFICIARIO:");
	  if($resultado==false){
			if($beneficiario_rif!=null){
				$beneficiario=$beneficiario_rif;
		 	}else{
				$beneficiario=$beneficiario_cedula;
			}
				$concepto="BENEFICIARIO: ".$beneficiario." POR CONCEPTO DE: ".$concepto;
	  	}


	  if ($numero_documento!=$veri_documento){
				$nco++;
				$numero_compromiso=$nco;
				if($numero_compromiso==1){
					$sql_up="INSERT INTO cfpd21_numero_asiento_compromiso VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_compromiso');";
					$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
		     		}else{
						$sql_up="UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso=$numero_compromiso WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_compromiso=$ano";
						$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
						}
						 	$this->Session->write('numero_compromiso', $nco);
	  		}
							$veri_documento=$numero_documento;


        $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	    $to   = 1;
	    $td   = 3;
	    $ta   = 1;
	    $mt   = $monto;
	    $ccp  = str_replace("'","",$concepto);

	    $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);

        $j++;
        $x++;
        $this->cepd01_compromiso_partidas->execute("UPDATE cepd01_compromiso_partidas SET numero_control_compromiso=".$numero_compromiso." WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=".$cod_dep." and ano_documento=".$ano." and numero_documento=".$numero_documento." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
        $this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso=".$numero_compromiso." WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=".$cod_dep." and ano_orden_pago=".$ano_orden_pago." and numero_orden_pago=".$numero_orden_pago." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");

	}//fin foreach

unset($partidas);
unset($partida);
unset($partidaX);
unset($cp);

 $this->layout = "ajax";



 if($this->Session->read('total_pag_rc_session')==$pagina || $this->Session->read('total_pag_rc_session')==0){
 	$this->set('pagina',    null);
 	$this->set('siguiente', 'anulacion_otros_compromisos');
 }else{
 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'otros_compromisos/var_null');
 }
 $this->render('vista_index');



}//fin funcion otros_compromisos




/**********************************************************+
 * 	ANULACIÓN DE OTROS COMPROMISOS
 *************************************************************/
function anulacion_otros_compromisos() {
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $ano_select = YEAR_REACTUALIZACION;
  $ann=YEAR_REACTUALIZACION;
  set_time_limit(0);

  porcentaje_barra(0, 100, "Registros de compromisos anulación", 1);


  $partidas= $this->v_restaurar_compromisos->findAll("condicion_actividad=2 and ano_documento=".$ano_select,null,'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,numero_documento ASC',null,null,null);
	$j=1;

	  $total_suma     = count($partidas);
	  $contar_proceso = 0;



	  foreach($partidas as $partidaX){

		$contar_proceso++;

        if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}



		$partida[0]=$partidaX['v_restaurar_compromisos'];
		$cod_presi                           =         $partida[0]['cod_presi'];
		$cod_entidad                         =         $partida[0]['cod_entidad'];
		$cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		$cod_inst                            =         $partida[0]['cod_inst'];
		$cod_dep                             =         $partida[0]['cod_dep'];
		$this->Session->write('SScodpresi', $cod_presi);
		$this->Session->write('SScodentidad', $cod_entidad);
		$this->Session->write('SScodtipoinst', $cod_tipo_inst);
		$this->Session->write('SScodinst', $cod_inst);
		$this->Session->write('SScoddep', $cod_dep);
		$ano_documento                       =         $partida[0]['ano_documento'];
		$fecha_registro                      =         cambiar_formato_fecha($partida[0]['fecha_proceso_anulacion']);
		$fd                                  =         cambiar_formato_fecha($partida[0]['fecha_proceso_anulacion']);
		$ano_fd                              =         (int) divide_fecha($partida[0]['fecha_proceso_anulacion'],'ano');
		$ano_fdpartida                       =         (int) $partida[0]['ano'];
		if($ano_fd!=$ano_fdpartida){
		     $fecha_documento=$fecha_registro;
		     $fd=$fecha_registro;
		}else{
		  	$fecha_documento=$fd;
	    }
		$numero_documento          = $partida[0]['numero_documento'];
		$ano                       = $partida[0]['ano'];
		$cod_sector                = $partida[0]['cod_sector'];
		$cod_programa              = $partida[0]['cod_programa'];
		$cod_sub_prog              = $partida[0]['cod_sub_prog'];
		$cod_proyecto              = $partida[0]['cod_proyecto'];
		$cod_activ_obra            = $partida[0]['cod_activ_obra'];
		$cod_partida               = $partida[0]['cod_partida'];
		$cod_generica              = $partida[0]['cod_generica'];
		$cod_especifica            = $partida[0]['cod_especifica'];
		$cod_sub_espec             = $partida[0]['cod_sub_espec'];
		$cod_auxiliar              = $partida[0]['cod_auxiliar'];
		$monto                     = $partida[0]['monto'];
		$numero_anulacion          = $partida[0]['numero_anulacion'];
		$concepto                  = $partida[0]['concepto'];
        $concepto_anulacion        = $partida[0]['concepto_anulacion'];
        $rif                       = $partida[0]['rif'];
		$beneficiario_rif          = $partida[0]['beneficiario_rif'];
		$beneficiario_cedula       = $partida[0]['beneficiario_cedula'];
		$numero_control_compromiso = $partida[0]['numero_control_compromiso'];


        $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	    $to   = 2;
	    $td   = 3;
	    $ta   = 1;
	    $rnco = $numero_control_compromiso;
	    $mt   = $monto;
	    $ccp  = str_replace("'","",$concepto_anulacion);


	    $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ann, $numero_documento, null, null, null, null, null, null, null, $rnco, null, null, null, $j, null, null);

        $j++;
}//fin foreach

$mensaje="Anulación de Otros Compromisos fueron actualizados con exito";
unset($partidas);
unset($partida);
unset($partidaX);
unset($cp);

		 $this->layout = "ajax";
         $this->set('siguiente', 'ordenes_compra');
     	 $this->set('pagina',   null);
		 $this->render('vista_index');

}//fin funcion anulacion_otros_compromisos






function ordenes_compra($var=null){
$nco = $this->Session->read('numero_compromiso');

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);

porcentaje_barra(0, 100, "Ordenes de compra", 1);
$ano_select = YEAR_REACTUALIZACION;


							$partidas=$this->cscd04_ordencompra_encabezado->execute("SELECT
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst,
									a.cod_dep,
									a.ano_orden_compra,
									a.numero_orden_compra,
									a.ano,
									a.cod_sector,
									a.cod_programa,
									a.cod_sub_prog,
									a.cod_proyecto,
									a.cod_activ_obra,
									a.cod_partida,
									a.cod_generica,
									a.cod_especifica,
									a.cod_sub_espec,
									a.cod_auxiliar,
									a.monto,
									a.aumento,
									a.disminucion,
									a.anticipo,
									a.amortizacion,
									a.cancelado,
									a.numero_asiento_compromiso,
									b.fecha_orden_compra,
									b.ano_orden_compra,
									b.numero_orden_compra,
									b.fecha_proceso_registro,
									b.fecha_proceso_anulacion,
									b.condicion_actividad,
									b.ano_cotizacion,
									b.numero_cotizacion,
									b.rif,
									c.denominacion,
									(SELECT x.uso_destino from cscd02_solicitud_encabezado x WHERE x.ano_solicitud=".YEAR_REACTUALIZACION." and x.cod_dep=a.cod_dep AND x.numero_solicitud=(SELECT y.numero_solicitud FROM cscd03_cotizacion_encabezado y WHERE y.cod_dep=a.cod_dep AND y.ano_cotizacion=".YEAR_REACTUALIZACION." AND y.numero_cotizacion=b.numero_cotizacion AND upper(y.rif)=upper(b.rif) AND y.numero_ordencompra=b.numero_orden_compra) limit 1)::text as concepto

									FROM

										 cscd04_ordencompra_partidas a, cscd04_ordencompra_encabezado b, cpcd02 c

									WHERE

									  b.condicion_actividad = 1 and
									  a.cod_presi           = b.cod_presi and
									  a.cod_entidad         = b.cod_entidad and
									  a.cod_tipo_inst       = b.cod_tipo_inst and
									  a.cod_inst            = b.cod_inst and
									  a.cod_dep             = b.cod_dep and
									  b.rif                 = c.rif and
									  b.ano_orden_compra    = ".$ano_select." and
									  a.ano_orden_compra    = ".$ano_select." and
									  a.numero_orden_compra = b.numero_orden_compra");
			            $j=0;
			            $i=0;
			            $ano=$ano_select;
						$suma=count($partidas);
					    $total_suma=count($partidas);
				        $contar_proceso=0;
				        $veri_documento=0;

						foreach($partidas as $partida){
								$contar_proceso++;

						        if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}

								  $cod_presi        = $partida[0]['cod_presi'];
								  $cod_entidad      = $partida[0]['cod_entidad'];
								  $cod_tipo_inst    = $partida[0]['cod_tipo_inst'];
								  $cod_inst         = $partida[0]['cod_inst'];
								  $cod_dep          = $partida[0]['cod_dep'];
								  $this->Session->write('SScodpresi', $cod_presi);
								  $this->Session->write('SScodentidad', $cod_entidad);
								  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
								  $this->Session->write('SScodinst', $cod_inst);
								  $this->Session->write('SScoddep', $cod_dep);
								  $ano_documento    = $partida[0]['ano_orden_compra'];
								  $fr               = $partida[0]['fecha_proceso_registro'];
								  $fecha_registro   = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
								  $fd               = $partida[0]['fecha_orden_compra'];
								  $ano_fd           = $fd[0].$fd[1].$fd[2].$fd[3];
								  $fd               = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
								  $ano_fd           = (int) $ano_fd;
								  $ano_fdpartida    = (int) $partida[0]['ano'];
										  if($ano_fd!=$ano_fdpartida){
										     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
										  }else{
										  	$fecha_documento=$fd;
										  }
								  $numero_documento = $partida[0]['numero_orden_compra'];
								  $ano              = $partida[0]['ano'];
								  $cod_sector       = $partida[0]['cod_sector'];
								  $cod_programa     = $partida[0]['cod_programa'];
								  $cod_sub_prog     = $partida[0]['cod_sub_prog'];
								  $cod_proyecto     = $partida[0]['cod_proyecto'];
								  $cod_activ_obra   = $partida[0]['cod_activ_obra'];
								  $cod_partida      = $partida[0]['cod_partida'];
								  $cod_generica     = $partida[0]['cod_generica'];
								  $cod_especifica   = $partida[0]['cod_especifica'];
								  $cod_sub_espec    = $partida[0]['cod_sub_espec'];
								  $cod_auxiliar     = $partida[0]['cod_auxiliar'];
								  $monto            = $partida[0]['monto'];
								  $concepto         = $partida[0]['concepto'];
								  $proveedor        = $partida[0]['denominacion'];


								  			$resultado=strpos("PROVEEDOR:", $concepto);
										if($resultado==false){
								   			$concepto="PROVEEDOR: ".$proveedor." POR CONCEPTO DE: ".$concepto;
											}


	  								if ($numero_documento!=$veri_documento){
										$nco++;
										$numero_compromiso=$nco;
										if($numero_compromiso==1){
											$sql_up="INSERT INTO cfpd21_numero_asiento_compromiso VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_compromiso');";
											$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
		     								}else{
												$sql_up="UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso=$numero_compromiso WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_compromiso=$ano";
												$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
												}
						 					$this->Session->write('numero_compromiso', $nco);
	  								 }
											$veri_documento=$numero_documento;


									$cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
									$to   = 1;
									$td   = 3;
									$ta   = 2;
									$mt   = $monto;
									$ccp  = str_replace("'","",$concepto);

									$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);

									$j++;
									$this->cscd04_ordencompra_partidas->execute("UPDATE cscd04_ordencompra_partidas SET numero_asiento_compromiso=".$numero_compromiso." WHERE cod_dep=".$cod_dep." and ano_orden_compra=".$ano." and numero_orden_compra=".$numero_documento."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
						   }//fin foreach


unset($partidas);
unset($partida);
unset($cp);

		 $this->layout = "ajax";
         $this->set('siguiente', 'anulacion_orden_compra');
     	 $this->set('pagina',   null);
		 $this->render('vista_index');


}//fin funcion ordenes de compras




function anulacion_orden_compra($var=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Anulación de Ordenes de Compra", 1);


									$partidas=$this->cscd04_ordencompra_encabezado->execute("SELECT
												a.cod_presi,
												a.cod_entidad,
												a.cod_tipo_inst,
												a.cod_inst,
												a.cod_dep,
												a.ano_orden_compra,
												a.numero_orden_compra,
												a.ano,
												a.cod_sector,
												a.cod_programa,
												a.cod_sub_prog,
												a.cod_proyecto,
												a.cod_activ_obra,
												a.cod_partida,
												a.cod_generica,
												a.cod_especifica,
												a.cod_sub_espec,
												a.cod_auxiliar,
												a.monto,
												a.aumento,
												a.disminucion,
												a.anticipo,
												a.amortizacion,
												a.cancelado,
												a.numero_asiento_compromiso,
												b.fecha_orden_compra,
												b.ano_orden_compra,
												b.numero_orden_compra,
												b.fecha_proceso_registro,
												b.fecha_proceso_anulacion,
												b.condicion_actividad,
												b.ano_cotizacion,
												b.numero_cotizacion,
												upper(b.rif),
												(SELECT x.uso_destino from cscd02_solicitud_encabezado_anulado x WHERE  x.ano_solicitud=".$ano_select." and x.cod_dep=a.cod_dep AND x.numero_solicitud=(SELECT y.numero_solicitud FROM cscd03_cotizacion_encabezado_anulado y WHERE y.cod_dep=a.cod_dep AND y.ano_cotizacion=".YEAR_REACTUALIZACION." AND y.numero_cotizacion=b.numero_cotizacion AND upper(y.rif)=upper(b.rif) AND y.numero_ordencompra=b.numero_orden_compra) limit 1)::text as concepto

												FROM
													cscd04_ordencompra_partidas a,cscd04_ordencompra_encabezado b

		                                       where
												  b.condicion_actividad = 2 and
												  a.cod_presi           = b.cod_presi and
												  a.cod_entidad         = b.cod_entidad and
												  a.cod_tipo_inst       = b.cod_tipo_inst and
												  a.cod_inst            = b.cod_inst and
												  a.cod_dep             = b.cod_dep and
												  b.ano_orden_compra    = ".$ano_select." and
												  a.ano_orden_compra    = ".$ano_select." and
												  a.numero_orden_compra = b.numero_orden_compra");

						            $j=0;
						            $i=0;
						            $ano=$ano_select;
									$suma = count($partidas);
									$total_suma     = count($partidas);
									$contar_proceso = 0;


									foreach($partidas as $partida){


										$contar_proceso++;


								        if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}


										  $cod_presi         = $partida[0]['cod_presi'];
										  $cod_entidad       = $partida[0]['cod_entidad'];
										  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
										  $cod_inst          = $partida[0]['cod_inst'];
										  $cod_dep           = $partida[0]['cod_dep'];
										  $this->Session->write('SScodpresi', $cod_presi);
										  $this->Session->write('SScodentidad', $cod_entidad);
										  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
										  $this->Session->write('SScodinst', $cod_inst);
										  $this->Session->write('SScoddep', $cod_dep);
										  $ano_documento     = $partida[0]['ano_orden_compra'];
										  $fr                = $partida[0]['fecha_proceso_registro'];
										  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
										  $fd                = $partida[0]['fecha_orden_compra'];
										  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
										  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
										  $ano_fd            = (int) $ano_fd;
										  $ano_fdpartida     = (int) $partida[0]['ano'];
											  if($ano_fd!=$ano_fdpartida){
											     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
											  }else{
											  	$fecha_documento=$fd;
											  }
										  $numero_documento  = $partida[0]['numero_orden_compra'];
										  $ano               = $partida[0]['ano'];
										  $cod_sector        = $partida[0]['cod_sector'];
										  $cod_programa      = $partida[0]['cod_programa'];
										  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
										  $cod_proyecto      = $partida[0]['cod_proyecto'];
										  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
										  $cod_partida       = $partida[0]['cod_partida'];
										  $cod_generica      = $partida[0]['cod_generica'];
										  $cod_especifica    = $partida[0]['cod_especifica'];
										  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
										  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
										  $monto             = $partida[0]['monto'];
										  $numero_compromiso = $partida[0]['numero_asiento_compromiso'];
										  $concepto          = $partida[0]['concepto'];

										    $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
											$to   = 1;
											$td   = 3;
											$ta   = 2;
											$mt   = $monto;
											$ccp  = str_replace("'","",$concepto);

											$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);

											$j++;
									}//fin foreach

unset($partidas);
unset($partida);
unset($cp);

		 $this->layout = "ajax";
         $this->set('siguiente', 'anulacion_orden_compra_2da_parte');
     	 $this->set('pagina',   null);
		 $this->render('vista_index');


}//fin funcion anulacion_orden_compra



function anulacion_orden_compra_2da_parte($var=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;


porcentaje_barra(0, 100, "Compromisos de orden de compra anulación", 1);

											$partidas=$this->cscd04_ordencompra_encabezado->execute("SELECT
													a.cod_presi,
													a.cod_entidad,
													a.cod_tipo_inst,
													a.cod_inst,
													a.cod_dep,
													a.ano_orden_compra,
													a.numero_orden_compra,
													a.ano,
													a.cod_sector,
													a.cod_programa,
													a.cod_sub_prog,
													a.cod_proyecto,
													a.cod_activ_obra,
													a.cod_partida,
													a.cod_generica,
													a.cod_especifica,
													a.cod_sub_espec,
													a.cod_auxiliar,
													a.monto,
													a.aumento,
													a.disminucion,
													a.anticipo,
													a.amortizacion,
													a.cancelado,
													a.numero_asiento_compromiso,
													b.fecha_orden_compra,
													b.fecha_proceso_registro,
													b.fecha_proceso_anulacion,
													b.condicion_actividad,
													b.ano_cotizacion,
													b.numero_cotizacion,b.numero_asiento_anulacion,
													upper(b.rif) as rif,
													(SELECT x.uso_destino from cscd02_solicitud_encabezado_anulado x WHERE x.ano_solicitud=".$ano_select." and x.cod_dep=a.cod_dep AND x.numero_solicitud=(SELECT y.numero_solicitud FROM cscd03_cotizacion_encabezado_anulado y WHERE y.cod_dep=a.cod_dep AND y.ano_cotizacion=".YEAR_REACTUALIZACION." AND y.numero_cotizacion=b.numero_cotizacion AND upper(y.rif)=upper(b.rif) AND y.numero_ordencompra=b.numero_orden_compra) limit 1)::text as concepto

													FROM cscd04_ordencompra_partidas a,cscd04_ordencompra_encabezado b
													 where
													  b.condicion_actividad = 2 and
													  a.cod_presi           = b.cod_presi and
													  a.cod_entidad         = b.cod_entidad and
													  a.cod_tipo_inst       = b.cod_tipo_inst and
													  a.cod_inst            = b.cod_inst and
													  a.cod_dep             = b.cod_dep and
													  b.ano_orden_compra    = ".$ano_select." and
												      a.ano_orden_compra    = ".$ano_select." and
													  a.numero_orden_compra = b.numero_orden_compra");

            $j=0;
            $ano=$ano_select;
			$i=0;


			  $total_suma     = count($partidas);
			  $contar_proceso = 0;

			foreach($partidas as $partida){


				$contar_proceso++;
		        if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}


				  $cod_presi         = $partida[0]['cod_presi'];
				  $cod_entidad       = $partida[0]['cod_entidad'];
				  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
				  $cod_inst          = $partida[0]['cod_inst'];
				  $cod_dep           = $partida[0]['cod_dep'];
				  $this->Session->write('SScodpresi', $cod_presi);
				  $this->Session->write('SScodentidad', $cod_entidad);
				  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
				  $this->Session->write('SScodinst', $cod_inst);
				  $this->Session->write('SScoddep', $cod_dep);
				  $ano_documento     = $partida[0]['ano_orden_compra'];
				  $fr                = $partida[0]['fecha_proceso_anulacion'];
				  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                = $partida[0]['fecha_proceso_anulacion'];
				  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
				  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd            = (int) $ano_fd;
				  $ano_fdpartida     = (int) $partida[0]['ano'];
					  if($ano_fd!=$ano_fdpartida){
					     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
					  }else{
					  	$fecha_documento=$fd;
					  }
				  $numero_documento  = $partida[0]['numero_orden_compra'];
				  $ano               = $partida[0]['ano'];
				  $cod_sector        = $partida[0]['cod_sector'];
				  $cod_programa      = $partida[0]['cod_programa'];
				  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
				  $cod_proyecto      = $partida[0]['cod_proyecto'];
				  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
				  $cod_partida       = $partida[0]['cod_partida'];
				  $cod_generica      = $partida[0]['cod_generica'];
				  $cod_especifica    = $partida[0]['cod_especifica'];
				  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
				  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
				  $monto             = $partida[0]['monto'];
				  $numero_compromiso = $partida[0]['numero_asiento_compromiso'];
				  $concepto          = $partida[0]['concepto'];

				  $numero_anulacion         = $partida[0]['numero_asiento_anulacion'];
				  $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."'  and tipo_operacion='232' and numero_documento='".$numero_documento."' and ano_acta_anulacion=".$ano_select);
                  $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

				   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
				   $to   = 2;
				   $td   = 3;
				   $ta   = 2;
				   $mt   = $monto;
				   $ccp  = str_replace("'","",$concepto_anulacion);

				   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);

					$j++;
			}//fin foreach

unset($partidas);
unset($partida);
unset($cp);

		 $this->layout = "ajax";
         $this->set('siguiente', 'modificacion_orden_compra');
     	 $this->set('pagina',   null);
		 $this->render('vista_index');

}//fin function anulacion_orden_compra_2da_parte






function modificacion_orden_compra($var=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Compromisos de orden de compra modificación", 1);

				  $partidas=$this->cobd01_co_modificacion_partidas->execute("select
							  a.cod_presi,
							  a.cod_entidad,
							  a.cod_tipo_inst,
							  a.cod_inst,
							  a.cod_dep,
							  a.ano_orden_compra,
							  a.numero_orden_compra,
							  a.numero_modificacion,
							  a.ano,
							  a.cod_sector,
							  a.cod_programa,
							  a.cod_sub_prog,
							  a.cod_proyecto,
							  a.cod_activ_obra,
							  a.cod_partida,
							  a.cod_generica,
							  a.cod_especifica,
							  a.cod_sub_espec,
							  a.cod_auxiliar,
							  a.monto,
							  b.fecha_modificacion as fecha_modificacion,
							  b.fecha_proceso_anulacion,
							  b.condicion_actividad,b.tipo_modificacion,
							  b.observaciones as concepto,
							  b.tipo_modificacion,
							  c.fecha_proceso_registro,
							  d.numero_control_compromiso
							 from
							  cscd04_ordencompra_modificacion_partidas a,cscd04_ordencompra_modificacion_cuerpo b, cscd04_ordencompra_encabezado c, cscd04_ordencompra_partidas d
							where         a.cod_presi           = d.cod_presi and
										  a.cod_entidad         = d.cod_entidad and
										  a.cod_tipo_inst       = d.cod_tipo_inst and
										  a.cod_inst            = d.cod_inst and
										  a.cod_dep             = d.cod_dep and
										  a.numero_orden_compra = d.numero_orden_compra and
										  a.ano                 = d.ano and
							              a.cod_sector          = d.cod_sector and
							  			  a.cod_programa        = d.cod_programa and
							  			  a.cod_sub_prog        = d.cod_sub_prog and
							  			  a.cod_proyecto        = d.cod_proyecto and
							  			  a.cod_activ_obra      = d.cod_activ_obra and
							  			  a.cod_partida         = d.cod_partida and
							  			  a.cod_generica        = d.cod_generica and
							  			  a.cod_especifica      = d.cod_especifica and
							  			  a.cod_sub_espec       = d.cod_sub_espec and
							  			  a.cod_auxiliar        = d.cod_auxiliar and
							  			  a.cod_presi           = b.cod_presi and
										  a.cod_entidad         = b.cod_entidad and
										  a.cod_tipo_inst       = b.cod_tipo_inst and
										  a.cod_inst            = b.cod_inst and
										  a.cod_dep             = b.cod_dep and
										  a.numero_orden_compra = b.numero_orden_compra and
                                          a.numero_modificacion = b.numero_modificacion and
							              b.cod_presi           = c.cod_presi and
										  b.cod_entidad         = c.cod_entidad and
										  b.cod_tipo_inst       = c.cod_tipo_inst and
										  b.cod_inst            = c.cod_inst and
										  b.cod_dep             = c.cod_dep and
                                          b.numero_orden_compra = c.numero_orden_compra and
                                          a.ano_orden_compra    = ".$ano_select." and
                                          b.ano_orden_compra    = ".$ano_select." and
                                          c.ano_orden_compra    = ".$ano_select." and
                                          d.ano_orden_compra    = ".$ano_select.";");

             $values="";
			 $monto=0;
			 $i=0;
             $x=0;
             $j=1;
             $ann=$ano_select;

                      		  $suma = count($partidas);
                              $total_suma     = count($partidas);
							  $contar_proceso = 0;


							foreach($partidas as $partida){


								$contar_proceso++;

						        if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}

									  $cod_presi           = $partida[0]['cod_presi'];
									  $cod_entidad         = $partida[0]['cod_entidad'];
									  $cod_tipo_inst       = $partida[0]['cod_tipo_inst'];
									  $cod_inst            = $partida[0]['cod_inst'];
									  $cod_dep             = $partida[0]['cod_dep'];
									  $this->Session->write('SScodpresi', $cod_presi);
									  $this->Session->write('SScodentidad', $cod_entidad);
									  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
									  $this->Session->write('SScodinst', $cod_inst);
									  $this->Session->write('SScoddep', $cod_dep);
									  $ano_documento       = $partida[0]['ano_orden_compra'];
									  $fr                  = $partida[0]['fecha_proceso_registro'];//0123-56-89
									  $fecha_registro      = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
									  $fd                  = $partida[0]['fecha_modificacion'];//0123-56-89 esta fecha es la del contrato de la obra pero tiene un alias fecha_modificacion
									  $ano_fd              = $fd[0].$fd[1].$fd[2].$fd[3];
									  $fd                  = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
									  $ano_fd              = (int) $ano_fd;
									  $ano_fdpartida       = (int) $partida[0]['ano'];
											  if($ano_fd!=$ano_fdpartida){
											     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
											  }else{
											  	$fecha_documento=$fd;
											  	$fd=$fd;
											  }

									  $numero_documento    = $partida[0]['numero_orden_compra'];
									  $numero_modificacion = $partida[0]['numero_modificacion'];
									  $ano                 = $partida[0]['ano'];
									  $cod_sector          = $partida[0]['cod_sector'];
									  $cod_programa        = $partida[0]['cod_programa'];
									  $cod_sub_prog        = $partida[0]['cod_sub_prog'];
									  $cod_proyecto        = $partida[0]['cod_proyecto'];
									  $cod_activ_obra      = $partida[0]['cod_activ_obra'];
									  $cod_partida         = $partida[0]['cod_partida'];
									  $cod_generica        = $partida[0]['cod_generica'];
									  $cod_especifica      = $partida[0]['cod_especifica'];
									  $cod_sub_espec       = $partida[0]['cod_sub_espec'];
									  $cod_auxiliar        = $partida[0]['cod_auxiliar'];
									  $monto               = $partida[0]['monto'];
									  $numero_compromiso   = $partida[0]['numero_control_compromiso'];
									  $concepto            = $partida[0]['concepto'];
									  $tipo_modificacion   = $partida[0]['tipo_modificacion'];


									       $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
										   $to   = 1;
										   $td   = 2;
										   $ta   = $tipo_modificacion;
										   $mt   = $monto;
										   $ccp  = str_replace("'","",$concepto);

										   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, $numero_modificacion, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);
									 $j++;
									 $x++;
									 $this->cobd01_co_modificacion_partidas->execute("UPDATE cscd04_ordencompra_modificacion_partidas SET numero_control_compromiso=".$numero_compromiso." WHERE  ano_orden_compra=".$ano." and numero_orden_compra='".$numero_documento."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");

					}//fin foreach

unset($partidas);
unset($partida);
unset($cp);


		 $this->layout = "ajax";
         $this->set('siguiente', 'anulacion_modificacion_orden_compra');
     	 $this->set('pagina',   null);
		 $this->render('vista_index');

}//fin function modificacion_orden_compra



function anulacion_modificacion_orden_compra($var=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;


porcentaje_barra(0, 100, "Compromisos de orden de compra modificación anulación", 1);

								  $partidas=$this->cobd01_contratoobras_cuerpo->execute("select
											  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano_orden_compra,
											  a.numero_orden_compra,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar,
											  a.monto,
											  a.numero_control_compromiso,
											  b.fecha_modificacion as fecha_modificacion,
											  b.numero_modificacion,
											  b.tipo_modificacion,
											  c.fecha_proceso_registro,
											  b.fecha_proceso_anulacion,
											  b.numero_anulacion
											from
											  cscd04_ordencompra_modificacion_partidas a,cscd04_ordencompra_modificacion_cuerpo b, cscd04_ordencompra_encabezado c
											where
											  b.cod_presi            = c.cod_presi and
											  b.cod_entidad          = c.cod_entidad and
											  b.cod_tipo_inst        = c.cod_tipo_inst and
											  b.cod_inst             = c.cod_inst and
											  b.cod_dep              = c.cod_dep and
	                                          a.cod_presi            = b.cod_presi and
											  a.cod_entidad          = b.cod_entidad and
											  a.cod_tipo_inst        = b.cod_tipo_inst and
											  a.cod_inst             = b.cod_inst and
											  a.cod_dep              = b.cod_dep and
	                                          c.ano_orden_compra     = ".$ano_select." and
											  b.ano_orden_compra     = ".$ano_select." and
											  a.ano_orden_compra     = ".$ano_select." and
	                                          b.numero_orden_compra  = c.numero_orden_compra and
	                                          a.numero_orden_compra  = b.numero_orden_compra and
	                                          a.numero_modificacion  = b.numero_modificacion and
											  b.condicion_actividad = 2");
										$j=1;

							  $total_suma     = count($partidas);
							  $contar_proceso = 0;

							foreach($partidas as $partida){


								$contar_proceso++;
						        if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}

										  $cod_presi         = $partida[0]['cod_presi'];
										  $cod_entidad       = $partida[0]['cod_entidad'];
										  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
										  $cod_inst          = $partida[0]['cod_inst'];
										  $cod_dep           = $partida[0]['cod_dep'];
										  $this->Session->write('SScodpresi', $cod_presi);
										  $this->Session->write('SScodentidad', $cod_entidad);
										  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
										  $this->Session->write('SScodinst', $cod_inst);
										  $this->Session->write('SScoddep', $cod_dep);
										  $ano_documento     = $partida[0]['ano_orden_compra'];
										  $fr                = $partida[0]['fecha_proceso_anulacion'];
										  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
										  $fd                = $partida[0]['fecha_proceso_anulacion'];//0123-56-89
										  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
										  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
										  $ano_fd            = (int) $ano_fd;
										  $ano_fdpartida     = (int) $partida[0]['ano'];
											  if($ano_fd!=$ano_fdpartida){
											     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
											  }else{
											  	$fecha_documento=$fd;
											  }
										  $numero_documento  = $partida[0]['numero_orden_compra'];
										  $ano               = $partida[0]['ano'];
										  $cod_sector        = $partida[0]['cod_sector'];
										  $cod_programa      = $partida[0]['cod_programa'];
										  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
										  $cod_proyecto      = $partida[0]['cod_proyecto'];
										  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
										  $cod_partida       = $partida[0]['cod_partida'];
										  $cod_generica      = $partida[0]['cod_generica'];
										  $cod_especifica    = $partida[0]['cod_especifica'];
										  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
										  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
										  $monto             = $partida[0]['monto'];
										  $numero_compromiso = $partida[0]['numero_control_compromiso'];
										  $numero_anulacion  = $partida[0]['numero_anulacion'];
										  $tipo_modificacion = $partida[0]['tipo_modificacion'];
										  $nro_modificacion  = $partida[0]['numero_modificacion'];

										  $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='222' and numero_documento='".$numero_documento."' and  ano_acta_anulacion=".$ano_select);
										  $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

										                                   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
																		   $to   = 2;
																		   $td   = 2;
																		   $ta   = $tipo_modificacion;
																		   $mt   = $monto;
																		   $ccp  = str_replace("'","",$concepto_anulacion);

																		   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, $nro_modificacion, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);
																		   $j++;
										   }//fin foreach


unset($partidas);
unset($partida);
unset($cp);


     	 $this->layout = "ajax";
     	 $this->set('siguiente', 'anticipos_ordenes_compras');
     	 $this->set('pagina',   null);
         $this->render('vista_index');



}//fin function anulacion_modificacion_orden_compra



function anticipos_ordenes_compras() {
$nca = $this->Session->read('numero_causado');
if ($nca==0 || $nca==0){$nca=0;}
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }

    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Anticipos Ordenes de compras", 1);



$partidas=$this->cscd04_ordencompra_anticipo_cuerpo->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_orden_compra,
a.numero_orden_compra,
a.numero_anticipo,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.numero_control_causado,
b.fecha_anticipo,
b.numero_anticipo,
b.ano_orden_compra,
b.numero_orden_compra,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,
b.observaciones as  concepto,
d.numero_control_compromiso
FROM cscd04_ordencompra_anticipo_partidas a, cscd04_ordencompra_anticipo_cuerpo b, cscd04_ordencompra_partidas d
 where
  a.cod_presi                = d.cod_presi and
  a.cod_entidad              = d.cod_entidad and
  a.cod_tipo_inst            = d.cod_tipo_inst and
  a.cod_inst                 = d.cod_inst and
  a.cod_dep                  = d.cod_dep and
  a.numero_orden_compra      = d.numero_orden_compra and
  a.ano                      = d.ano and
  a.cod_sector               = d.cod_sector and
  a.cod_programa             = d.cod_programa and
  a.cod_sub_prog             = d.cod_sub_prog and
  a.cod_proyecto             = d.cod_proyecto and
  a.cod_activ_obra           = d.cod_activ_obra and
  a.cod_partida              = d.cod_partida and
  a.cod_generica             = d.cod_generica and
  a.cod_especifica           = d.cod_especifica and
  a.cod_sub_espec            = d.cod_sub_espec and
  a.cod_auxiliar             = d.cod_auxiliar and
  a.cod_presi                = b.cod_presi and
  a.cod_entidad              = b.cod_entidad and
  a.cod_tipo_inst            = b.cod_tipo_inst and
  a.cod_inst                 = b.cod_inst and
  a.cod_dep                  = b.cod_dep and
  a.numero_orden_compra      = b.numero_orden_compra and
  a.numero_anticipo          = b.numero_anticipo  and
  a.ano_orden_compra         = '".$ano_select."' and
  b.ano_orden_compra         = '".$ano_select."' and
  d.ano_orden_compra         = '".$ano_select."' and
  b.saldo_ano_anterior       = 2");

	$j = 0;
	$i = 0;
	$ano=YEAR_REACTUALIZACION;
	$suma = count($partidas);

	                          $total_suma     = count($partidas);
							  $contar_proceso = 0;
							  $veri_documento = 0;

							foreach($partidas as $partida){



								 $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $suma);}


		  $cod_presi         = $partida[0]['cod_presi'];
		  $cod_entidad       = $partida[0]['cod_entidad'];
		  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
		  $cod_inst          = $partida[0]['cod_inst'];
		  $cod_dep           = $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento    = $partida[0]['ano_orden_compra'];
		  $ano_orden_compra  = $partida[0]['ano_orden_compra'];
		  $fr                = $partida[0]['fecha_proceso_registro'];
		  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
	      $fd                = $partida[0]['fecha_anticipo'];
		  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
		  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_fd            = (int) $ano_fd;
	      $ano_fdpartida     = (int) $partida[0]['ano'];

				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $ndo               = $partida[0]['numero_orden_compra'];
		  $nda               = $partida[0]['numero_anticipo'];
		  $ano               = $partida[0]['ano'];
		  $cod_sector        = $partida[0]['cod_sector'];
		  $cod_programa      = $partida[0]['cod_programa'];
		  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
		  $cod_proyecto      = $partida[0]['cod_proyecto'];
		  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
		  $cod_partida       = $partida[0]['cod_partida'];
		  $cod_generica      = $partida[0]['cod_generica'];
		  $cod_especifica    = $partida[0]['cod_especifica'];
		  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
		  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
		  $monto             = $partida[0]['monto'];
		  $numero_compromiso = $partida[0]['numero_control_compromiso'];
		  $numero_causado    = $partida[0]['numero_control_causado'];
		  $concepto          = $partida[0]['concepto'];

			if ($ndo!=$veri_documento){
				$nca++;
				$numero_causado=$nca;
			    $this->Session->write('numero_causado', $nca);
			}
				$veri_documento=$ndo;


		$cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		$to   = 1;
		$td   = 4;
		$ta   = 2;
		$mt   = $monto;
	    $ccp  = str_replace("'","",$concepto);


	    $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);
		$sql4  = "UPDATE cscd04_ordencompra_anticipo_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado."  where numero_orden_compra=".$ndo." and numero_anticipo=".$nda." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
		$this->cscd04_ordencompra_partidas->execute($sql4);
		$j++;
		$i++;

	}//fin foreach

unset($partidas);
unset($partida);
unset($cp);

		 $this->layout = "ajax";
         $this->set('siguiente', 'contratos_obra');
     	 $this->set('pagina',   null);
		 $this->render('vista_index');

}//fin function anticipos_ordenes_compras





function contratos_obra($var=null){
$nco = $this->Session->read('numero_compromiso');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;

	porcentaje_barra(1, 100, "Contratos de obras", 1);

						  $partidas=$this->cobd01_contratoobras_cuerpo->execute("select
								  a.*,
								  b.fecha_contrato_obra,
								  b.fecha_proceso_registro,
								  b.fecha_proceso_anulacion,
								  b.condicion_actividad,
								  b.denominacion_obra as concepto,
								  b.rif,
								  c.denominacion
							   from
								  cobd01_contratoobras_partidas a,cobd01_contratoobras_cuerpo b, cpcd02 c
								where
								  a.cod_presi            = b.cod_presi and
								  a.cod_entidad          = b.cod_entidad and
								  a.cod_tipo_inst        = b.cod_tipo_inst and
								  a.cod_inst             = b.cod_inst and
								  a.cod_dep              = b.cod_dep and
								  b.rif                  = c.rif and
								  b.ano_contrato_obra    = ".$ano_select." and
								  a.ano_contrato_obra    = ".$ano_select." and
								  a.numero_contrato_obra = b.numero_contrato_obra
								  order by 1, 2, 3, 4, 5, 6, 7");

                          $values="";
						  $monto=0;
						  $i=0;
						  $ann=$ano_select;
						  $j=1;
						  $x=0;
						  $suma = count($partidas);

							  $total_suma     = count($partidas);
							  $contar_proceso = 0;
							  $veri_documento = 0;

							foreach($partidas as $partida){

								$contar_proceso++;
						        if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}

								  $cod_presi                 = $partida[0]['cod_presi'];
								  $cod_entidad               = $partida[0]['cod_entidad'];
								  $cod_tipo_inst             = $partida[0]['cod_tipo_inst'];
								  $cod_inst                  = $partida[0]['cod_inst'];
								  $cod_dep                   = $partida[0]['cod_dep'];
								  $this->Session->write('SScodpresi', $cod_presi);
								  $this->Session->write('SScodentidad', $cod_entidad);
								  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
								  $this->Session->write('SScodinst', $cod_inst);
								  $this->Session->write('SScoddep', $cod_dep);
								  $ano_documento             = $partida[0]['ano_contrato_obra'];
								  $fr                        = $partida[0]['fecha_proceso_registro'];
								  $fecha_registro            = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
								  $fd                        = $partida[0]['fecha_contrato_obra'];//0123-56-89
								  $ano_fd                    = $fd[0].$fd[1].$fd[2].$fd[3];
								  $fd                        = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
								  $ano_fd                    = (int) $ano_fd;
								  $ano_fdpartida             = (int) $partida[0]['ano'];
										  if($ano_fd!=$ano_fdpartida){
										     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
										  }else{
										  	$fecha_documento=$fd;
										  }
								  $numero_documento          = $partida[0]['numero_contrato_obra'];
								  $ano                       = $partida[0]['ano'];
								  $cod_sector                = $partida[0]['cod_sector'];
								  $cod_programa              = $partida[0]['cod_programa'];
								  $cod_sub_prog              = $partida[0]['cod_sub_prog'];
								  $cod_proyecto              = $partida[0]['cod_proyecto'];
								  $cod_activ_obra            = $partida[0]['cod_activ_obra'];
								  $cod_partida               = $partida[0]['cod_partida'];
								  $cod_generica              = $partida[0]['cod_generica'];
								  $cod_especifica            = $partida[0]['cod_especifica'];
								  $cod_sub_espec             = $partida[0]['cod_sub_espec'];
								  $cod_auxiliar              = $partida[0]['cod_auxiliar'];
								  $monto                     = $partida[0]['monto'];
								  $numero_control_compromiso = $partida[0]['numero_control_compromiso'];
						 	      $concepto                  = $partida[0]['concepto'];
						 	      $rif                       = $partida[0]['rif'];
								  $contratista               = $partida[0]['denominacion'];

						   $resultado=strpos("CONTRATISTA:", $concepto);
						if($resultado==false){
						   $concepto="CONTRATISTA: ".$contratista." DENOMINACIÓN DE LA OBRA: ".$concepto;
						}

									  if ($numero_documento!=$veri_documento){
												$nco++;
												$numero_compromiso=$nco;
											if($numero_compromiso==1){
												$sql_up="INSERT INTO cfpd21_numero_asiento_compromiso VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_compromiso');";
												$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
		     									}else{
													$sql_up="UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso=$numero_compromiso WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_compromiso=$ano";
													$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
													}
						 								$this->Session->write('numero_compromiso', $nco);
	  									}
														$veri_documento=$numero_documento;

						 	      $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								  $to   = 1;
								  $td   = 3;
								  $ta   = 3;
								  $mt   = $monto;
								  $ccp  = str_replace("'","",$concepto);
								  $rnco = $numero_compromiso;

						          $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $rnco, null, null, null, $j, null, null);

						          $j++;
						          $x++;
						          $this->cepd01_compromiso_partidas->execute("UPDATE cobd01_contratoobras_partidas SET numero_control_compromiso=".$rnco." WHERE cod_dep=".$cod_dep." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$numero_documento."'");
				}//fin foreach


unset($partidas);
unset($partida);
unset($cp);


     	 $this->layout = "ajax";
     	 $this->set('siguiente', 'anulacion_contratos_obra');
     	 $this->set('pagina',   null);
         $this->render('vista_index');



}//fin function contratos_obra




function anulacion_contratos_obra($var=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Anulación Contratos de obras", 1);

								  $partidas=$this->cobd01_contratoobras_cuerpo->execute("select
											  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano_contrato_obra,
											  a.numero_contrato_obra,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar,
											  a.monto,
											  a.numero_control_compromiso,
											  b.fecha_contrato_obra,
											  b.fecha_proceso_registro,
											  b.fecha_proceso_anulacion,
											  b.rif,
											  b.numero_anulacion
											from
											  cobd01_contratoobras_partidas a,cobd01_contratoobras_cuerpo b
											where
											  a.cod_presi            = b.cod_presi and
											  a.cod_entidad          = b.cod_entidad and
											  a.cod_tipo_inst        = b.cod_tipo_inst and
											  a.cod_inst             = b.cod_inst and
											  a.cod_dep              = b.cod_dep and
											  b.ano_contrato_obra    = ".$ano_select." and
											  a.ano_contrato_obra    = ".$ano_select." and
											  a.numero_contrato_obra = b.numero_contrato_obra and
											  b.condicion_actividad  = 2");
								$j=1;

                              $total_suma     = count($partidas);
							  $contar_proceso = 0;

							foreach($partidas as $partida){

								$contar_proceso++;
						        if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}

								  $cod_presi                 = $partida[0]['cod_presi'];
								  $cod_entidad               = $partida[0]['cod_entidad'];
								  $cod_tipo_inst             = $partida[0]['cod_tipo_inst'];
								  $cod_inst                  = $partida[0]['cod_inst'];
								  $cod_dep                   = $partida[0]['cod_dep'];
								  $this->Session->write('SScodpresi', $cod_presi);
								  $this->Session->write('SScodentidad', $cod_entidad);
								  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
								  $this->Session->write('SScodinst', $cod_inst);
								  $this->Session->write('SScoddep', $cod_dep);
								  $ano_documento             = $partida[0]['ano_contrato_obra'];
								  $fr                        = $partida[0]['fecha_proceso_anulacion'];
								  $fecha_registro            = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
								  $fd                        = $partida[0]['fecha_proceso_anulacion'];//0123-56-89
								  $ano_fd                    = $fd[0].$fd[1].$fd[2].$fd[3];
								  $fd                        = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
								  $ano_fd                    = (int) $ano_fd;
								  $ano_fdpartida             = (int) $partida[0]['ano'];
												  if($ano_fd!=$ano_fdpartida){
												     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
												  }else{
												  	$fecha_documento=$fd;
												  }
								  $numero_documento          = $partida[0]['numero_contrato_obra'];
								  $ano                       = $partida[0]['ano'];
								  $cod_sector                = $partida[0]['cod_sector'];
								  $cod_programa              = $partida[0]['cod_programa'];
								  $cod_sub_prog              = $partida[0]['cod_sub_prog'];
								  $cod_proyecto              = $partida[0]['cod_proyecto'];
								  $cod_activ_obra            = $partida[0]['cod_activ_obra'];
								  $cod_partida               = $partida[0]['cod_partida'];
								  $cod_generica              = $partida[0]['cod_generica'];
								  $cod_especifica            = $partida[0]['cod_especifica'];
								  $cod_sub_espec             = $partida[0]['cod_sub_espec'];
								  $cod_auxiliar              = $partida[0]['cod_auxiliar'];
								  $monto                     = $partida[0]['monto'];
								  $numero_compromiso         = $partida[0]['numero_control_compromiso'];
								  $numero_anulacion          = $partida[0]['numero_anulacion'];
								  $rif                       = $partida[0]['rif'];
								  $busca_concepto_anulacion  = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='233' and numero_documento='".$numero_documento."' and ano_acta_anulacion=".$ano_select);
								  $concepto_anulacion        = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

				                                   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
												   $to   = 2;
												   $td   = 3;
												   $ta   = 3;
												   $rnco = $numero_compromiso;
												   $mt   = $monto;
												   $ccp  = str_replace("'","",$concepto_anulacion);

												   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $rnco, null, null, null, $j, null, null);

				                       $j++;
								}//fin foreach
unset($partidas);
unset($partida);
unset($cp);

     	 $this->layout = "ajax";
     	 $this->set('siguiente', 'modificacion_contrato_obras');
     	 $this->set('pagina',   null);
         $this->render('vista_index');


}//fin function anulacion_contratos_obra





function modificacion_contrato_obras($var=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Modificacion Contrato de Obras", 1);

						  $partidas=$this->cobd01_co_modificacion_partidas->execute("select
										  a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.ano_contrato_obra,
										  a.numero_contrato_obra,
										  a.numero_modificacion,
										  a.ano,
										  a.cod_sector,
										  a.cod_programa,
										  a.cod_sub_prog,
										  a.cod_proyecto,
										  a.cod_activ_obra,
										  a.cod_partida,
										  a.cod_generica,
										  a.cod_especifica,
										  a.cod_sub_espec,
										  a.cod_auxiliar,
										  a.monto,
										  b.fecha_modificacion as fecha_modificacion,
										  c.fecha_proceso_registro,
										  b.fecha_proceso_anulacion,
										  b.condicion_actividad,
										  b.tipo_modificacion,
										  b.observaciones as concepto,
										  d.numero_control_compromiso
										from
										  cobd01_contratoobras_modificacion_partidas a,cobd01_contratoobras_modificacion_cuerpo b,cobd01_contratoobras_cuerpo c, cobd01_contratoobras_partidas d
										where
										  a.cod_presi            = d.cod_presi and
										  a.cod_entidad          = d.cod_entidad and
										  a.cod_tipo_inst        = d.cod_tipo_inst and
										  a.cod_inst             = d.cod_inst and
										  a.cod_dep              = d.cod_dep and
										  a.numero_contrato_obra = d.numero_contrato_obra and
										  a.ano                  = d.ano and
							              a.cod_sector           = d.cod_sector and
							  			  a.cod_programa         = d.cod_programa and
							  			  a.cod_sub_prog         = d.cod_sub_prog and
							  			  a.cod_proyecto         = d.cod_proyecto and
							  			  a.cod_activ_obra       = d.cod_activ_obra and
							  			  a.cod_partida          = d.cod_partida and
							  			  a.cod_generica         = d.cod_generica and
							  			  a.cod_especifica       = d.cod_especifica and
							  			  a.cod_sub_espec        = d.cod_sub_espec and
							  			  a.cod_auxiliar         = d.cod_auxiliar and
							  			  a.cod_presi            = b.cod_presi and
										  a.cod_entidad          = b.cod_entidad and
										  a.cod_tipo_inst        = b.cod_tipo_inst and
										  a.cod_inst             = b.cod_inst and
										  a.cod_dep              = b.cod_dep and
										  a.numero_contrato_obra = b.numero_contrato_obra and
                                          a.numero_modificacion  = b.numero_modificacion and
										  b.cod_presi            = c.cod_presi and
										  b.cod_entidad          = c.cod_entidad and
										  b.cod_tipo_inst        = c.cod_tipo_inst and
										  b.cod_inst             = c.cod_inst and
										  b.cod_dep              = c.cod_dep and
										  b.numero_contrato_obra = c.numero_contrato_obra and
										  a.ano_contrato_obra    = ".$ano_select." and
										  b.ano_contrato_obra    = ".$ano_select." and
										  c.ano_contrato_obra    = ".$ano_select." and
										  d.ano_contrato_obra    = ".$ano_select."; ");


             $values="";
			 $monto=0;
			 $i=0;
             $ann=$ano_select;

					  $j=1;
                      $x=0;
                      $suma = count($partidas);
					          $total_suma     = count($partidas);
							  $contar_proceso = 0;


							foreach($partidas as $partida){


								 $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $suma);}

							  $cod_presi           = $partida[0]['cod_presi'];
							  $cod_entidad         = $partida[0]['cod_entidad'];
							  $cod_tipo_inst       = $partida[0]['cod_tipo_inst'];
							  $cod_inst            = $partida[0]['cod_inst'];
							  $cod_dep             = $partida[0]['cod_dep'];
							  $this->Session->write('SScodpresi', $cod_presi);
							  $this->Session->write('SScodentidad', $cod_entidad);
							  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
							  $this->Session->write('SScodinst', $cod_inst);
							  $this->Session->write('SScoddep', $cod_dep);
							  $ano_documento       = $partida[0]['ano_contrato_obra'];
							  $fr                  = $partida[0]['fecha_proceso_registro'];//0123-56-89
							  $fecha_registro      = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
							  $fd                  = $partida[0]['fecha_modificacion'];//0123-56-89 esta fecha es la del contrato de la obra pero tiene un alias fecha_modificacion
							  $ano_fd              = $fd[0].$fd[1].$fd[2].$fd[3];
							  $fd                  = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
							  $ano_fd              = (int) $ano_fd;
							  $ano_fdpartida       = (int) $partida[0]['ano'];
										  if($ano_fd!=$ano_fdpartida){
										     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
										  }else{
										  	$fecha_documento=$fd; $fd=$fd;
										  }
							  $numero_documento    = $partida[0]['numero_contrato_obra'];
							  $numero_modificacion = $partida[0]['numero_modificacion'];
							  $ano                 = $partida[0]['ano'];
							  $cod_sector          = $partida[0]['cod_sector'];
							  $cod_programa        = $partida[0]['cod_programa'];
							  $cod_sub_prog        = $partida[0]['cod_sub_prog'];
							  $cod_proyecto        = $partida[0]['cod_proyecto'];
							  $cod_activ_obra      = $partida[0]['cod_activ_obra'];
							  $cod_partida         = $partida[0]['cod_partida'];
							  $cod_generica        = $partida[0]['cod_generica'];
							  $cod_especifica      = $partida[0]['cod_especifica'];
							  $cod_sub_espec       = $partida[0]['cod_sub_espec'];
							  $cod_auxiliar        = $partida[0]['cod_auxiliar'];
							  $monto               = $partida[0]['monto'];
							  $numero_compromiso   = $partida[0]['numero_control_compromiso'];
							  $concepto            = $partida[0]['concepto'];
							  $tipo_modificacion   = $partida[0]['tipo_modificacion'];

                                   $cp  = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								   $to  = 1;
								   $td  = 2;
								   if ($tipo_modificacion=='1'){$ta=3;}else{$ta=4;}
								   $mt  = $monto;
								   $ccp = str_replace("'","",$concepto);

								   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, $numero_modificacion, null, null, null, null, null, null, $numero_compromiso, null, null, null, null);
							 $j++;
							 $x++;
							 $this->cobd01_co_modificacion_partidas->execute("UPDATE cobd01_contratoobras_modificacion_partidas SET numero_control_compromiso=".$numero_compromiso." WHERE  ano_contrato_obra=".$ano." and numero_contrato_obra='".$numero_documento."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
							}//fin foreach

unset($partidas);
unset($partida);
unset($cp);


     	 $this->layout = "ajax";
     	 $this->set('siguiente', 'anulacion_modificacion_contratos_obras');
     	 $this->set('pagina',   null);
         $this->render('vista_index');

}//fin function modificacion_contrato_obras






function anulacion_modificacion_contratos_obras($var=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Anulación Modificación Contratos de Obras", 1);

								  $partidas=$this->cobd01_contratoobras_cuerpo->execute("select
								  a.cod_presi,
								  a.cod_entidad,
								  a.cod_tipo_inst,
								  a.cod_inst,
								  a.cod_dep,
								  a.ano_contrato_obra,
								  a.numero_contrato_obra,
								  a.ano,
								  a.cod_sector,
								  a.cod_programa,
								  a.cod_sub_prog,
								  a.cod_proyecto,
								  a.cod_activ_obra,
								  a.cod_partida,
								  a.cod_generica,
								  a.cod_especifica,
								  a.cod_sub_espec,
								  a.cod_auxiliar,
								  a.monto,
								  a.numero_control_compromiso,
								  b.fecha_modificacion as fecha_modificacion,
								  b.numero_modificacion,b.tipo_modificacion,
								  c.fecha_proceso_registro,
								  b.fecha_proceso_anulacion,
								  b.numero_anulacion
								from
								  cobd01_contratoobras_modificacion_partidas a,cobd01_contratoobras_modificacion_cuerpo b,cobd01_contratoobras_cuerpo c
								where
								          b.cod_presi            = c.cod_presi and
										  b.cod_entidad          = c.cod_entidad and
										  b.cod_tipo_inst        = c.cod_tipo_inst and
										  b.cod_inst             = c.cod_inst and
										  b.cod_dep              = c.cod_dep and
                                          a.cod_presi            = b.cod_presi and
										  a.cod_entidad          = b.cod_entidad and
										  a.cod_tipo_inst        = b.cod_tipo_inst and
										  a.cod_inst             = b.cod_inst and
										  a.cod_dep              = b.cod_dep and
                                          c.ano_contrato_obra    = ".$ano_select." and
										  b.ano_contrato_obra    = ".$ano_select." and
										  a.ano_contrato_obra    = ".$ano_select." and
                                          b.numero_contrato_obra = c.numero_contrato_obra and
                                          a.numero_contrato_obra = b.numero_contrato_obra and
                                          a.numero_modificacion  = b.numero_modificacion  and
								          b.condicion_actividad  = 2;");

							  $total_suma     = count($partidas);
							  $contar_proceso = 0;
							  $j=1;

							foreach($partidas as $partida){

								 $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}

										  $cod_presi         = $partida[0]['cod_presi'];
										  $cod_entidad       = $partida[0]['cod_entidad'];
										  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
										  $cod_inst          = $partida[0]['cod_inst'];
										  $cod_dep           = $partida[0]['cod_dep'];
										  $this->Session->write('SScodpresi', $cod_presi);
										  $this->Session->write('SScodentidad', $cod_entidad);
										  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
										  $this->Session->write('SScodinst', $cod_inst);
										  $this->Session->write('SScoddep', $cod_dep);
										  $ano_documento     = $partida[0]['ano_contrato_obra'];
										  $fr                = $partida[0]['fecha_proceso_anulacion'];
										  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
										  $fd                = $partida[0]['fecha_proceso_anulacion'];//0123-56-89
										  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
										  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
										  $ano_fd            = (int) $ano_fd;
										  $ano_fdpartida     = (int) $partida[0]['ano'];
											if($ano_fd!=$ano_fdpartida){
											   $fecha_documento=$fecha_registro; $fd=$fecha_registro;
											}else{
												$fecha_documento=$fd;
												}
										  $numero_documento  = $partida[0]['numero_contrato_obra'];
										  $ano               = $partida[0]['ano'];
										  $cod_sector        = $partida[0]['cod_sector'];
										  $cod_programa      = $partida[0]['cod_programa'];
										  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
										  $cod_proyecto      = $partida[0]['cod_proyecto'];
										  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
										  $cod_partida       = $partida[0]['cod_partida'];
										  $cod_generica      = $partida[0]['cod_generica'];
										  $cod_especifica    = $partida[0]['cod_especifica'];
										  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
										  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
										  $monto             = $partida[0]['monto'];
										  $numero_compromiso = $partida[0]['numero_control_compromiso'];
										  $numero_anulacion  = $partida[0]['numero_anulacion'];
										  $tipo_modificacion = $partida[0]['tipo_modificacion'];
										  $nro_modificacion  = $partida[0]['numero_modificacion'];

										  if($tipo_modificacion==1){$ta='3';}else if($tipo_modificacion==2){$ta='4';}

										  $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='22".$ta
										  ."' and numero_documento='".$numero_documento."' and ano_acta_anulacion=".$ano_select );
										  $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

								                                   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
																   $to   = 2;
																   $td   = 2;
																   $mt   = $monto;
																   $ccp  = str_replace("'","",$concepto_anulacion);
																   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, $nro_modificacion, null, null, null, null, null, null, $numero_compromiso, null, null, null, null);
												                   $j++;
								}//fin foreach

unset($partidas);
unset($partida);
unset($cp);


     	 $this->layout = "ajax";
     	 $this->set('siguiente', 'anticipo_contrato_obras');
     	 $this->set('pagina',   null);
         $this->render('vista_index');

}//fin function anulacion_modificacion_contratos_obras



//****************************************ANTICIPOS CONTRATOS DE OBRAS*********************************//

function anticipo_contrato_obras() {
$nca = $this->Session->read('numero_causado');
if ($nca==0 || $nca==0){$nca=0;}
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }

	porcentaje_barra(0, 100, "Causado de obras por anticipo de saldo anterior", 1);

  $partidas=$this->cobd01_contratoobras_anticipo_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.numero_anticipo,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_causado,
  b.fecha_anticipo,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.fecha_proceso_registro,
  b.observaciones as concepto,
  d.numero_control_compromiso
   from
  cobd01_contratoobras_anticipo_partidas a, cobd01_contratoobras_anticipo_cuerpo b, cobd01_contratoobras_partidas d
where
   a.cod_presi            = d.cod_presi and
   a.cod_entidad          = d.cod_entidad and
   a.cod_tipo_inst        = d.cod_tipo_inst and
   a.cod_inst             = d.cod_inst and
   a.cod_dep              = d.cod_dep and
   a.numero_contrato_obra = d.numero_contrato_obra and
   a.ano                  = d.ano and
   a.cod_sector           = d.cod_sector and
   a.cod_programa         = d.cod_programa and
   a.cod_sub_prog         = d.cod_sub_prog and
   a.cod_proyecto         = d.cod_proyecto and
   a.cod_activ_obra       = d.cod_activ_obra and
   a.cod_partida          = d.cod_partida and
   a.cod_generica         = d.cod_generica and
   a.cod_especifica       = d.cod_especifica and
   a.cod_sub_espec        = d.cod_sub_espec and
   a.cod_auxiliar         = d.cod_auxiliar and
   a.cod_presi            = b.cod_presi and
   a.cod_entidad          = b.cod_entidad and
   a.cod_tipo_inst        = b.cod_tipo_inst and
   a.cod_inst             = b.cod_inst and
   a.cod_dep              = b.cod_dep and
   a.numero_contrato_obra = b.numero_contrato_obra and
   a.numero_anticipo      = b.numero_anticipo and
   a.ano_contrato_obra    = '".YEAR_REACTUALIZACION."' and
   b.ano_contrato_obra    = '".YEAR_REACTUALIZACION."' and
   d.ano_contrato_obra    = '".YEAR_REACTUALIZACION."' and
   b.saldo_ano_anterior   = 2 ");

    $j    = 0;
	$i    = 0;
	$ano  = YEAR_REACTUALIZACION;
	$suma = count($partidas);

	                          $total_suma     = count($partidas);
							  $contar_proceso = 0;
							  $veri_documento = 0;

			foreach($partidas as $partida){

								 $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $suma);}

		  $cod_presi         = $partida[0]['cod_presi'];
		  $cod_entidad       = $partida[0]['cod_entidad'];
		  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
		  $cod_inst          = $partida[0]['cod_inst'];
		  $cod_dep           = $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento    = $partida[0]['ano_contrato_obra'];
		  $fd                = $partida[0]['fecha_anticipo'];
		  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $fr                = $partida[0]['fecha_proceso_registro'];
		  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
		  $fd                = $partida[0]['fecha_anticipo'];
		  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
		  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_fd            = (int) $ano_fd;
		  $ano_fdpartida     = (int) $partida[0]['ano'];

				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $ano_orden_compra  = $partida[0]['ano_contrato_obra'];
		  $ndo               = $partida[0]['numero_contrato_obra'];
		  $nda               = $partida[0]['numero_anticipo'];
		  $ano               = $partida[0]['ano'];
		  $cod_sector        = $partida[0]['cod_sector'];
		  $cod_programa      = $partida[0]['cod_programa'];
		  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
		  $cod_proyecto      = $partida[0]['cod_proyecto'];
		  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
		  $cod_partida       = $partida[0]['cod_partida'];
		  $cod_generica      = $partida[0]['cod_generica'];
		  $cod_especifica    = $partida[0]['cod_especifica'];
		  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
		  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
		  $monto             = $partida[0]['monto'];
		  $numero_compromiso = $partida[0]['numero_control_compromiso'];
		  $numero_causado    = $partida[0]['numero_control_causado'];
		  $concepto          = $partida[0]['concepto'];
		  $ano_orden_pago    = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];

			if ($ndo!=$veri_documento){
				$nca++;
				$numero_causado=$nca;
			    $this->Session->write('numero_causado', $nca);
			}
				$veri_documento=$ndo;

		$cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		$to   = 1;
		$td   = 4;
		$ta   = 4;
		$mt   = $monto;
	    $ccp  = str_replace("'","",$concepto);



		$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);
		$this->cobd01_contratoobras_anticipo_partidas->execute("UPDATE cobd01_contratoobras_anticipo_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = '".$numero_causado."'  where numero_contrato_obra='".$ndo."' and numero_anticipo=".$nda." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    $j++;
	    $i++;

	}//fin foreach


unset($partidas);
unset($partida);
unset($cp);

         $this->layout = "ajax";
         $this->set('siguiente', 'contratos_servicio');
     	 $this->set('pagina',   null);
		 $this->render('vista_index');

}//fin fuction anticipo_contrato_obras




function contratos_servicio($var=null){
$nco = $this->Session->read('numero_compromiso');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "contratos de servicio", 1);

					$partidas=$this->cepd02_contratoservicio_cuerpo->execute("select
								  a.cod_presi,
								  a.cod_entidad,
								  a.cod_tipo_inst,
								  a.cod_inst,
								  a.cod_dep,
								  a.ano_contrato_servicio,
								  a.numero_contrato_servicio,
								  a.ano,
								  a.cod_sector,
								  a.cod_programa,
								  a.cod_sub_prog,
								  a.cod_proyecto,
								  a.cod_activ_obra,
								  a.cod_partida,
								  a.cod_generica,
								  a.cod_especifica,
								  a.cod_sub_espec,
								  a.cod_auxiliar,
								  a.monto,
								  a.numero_control_compromiso,
								  b.fecha_contrato_servicio,
								  b.fecha_proceso_registro,
								  b.fecha_proceso_anulacion,
								  b.rif,
								  b.concepto,
								  c.denominacion
								from
								  cepd02_contratoservicio_partidas a,cepd02_contratoservicio_cuerpo b, cpcd02 c
								where
								  a.cod_presi                = b.cod_presi and
								  a.cod_entidad              = b.cod_entidad and
								  a.cod_tipo_inst            = b.cod_tipo_inst and
								  a.cod_inst                 = b.cod_inst and
								  a.cod_dep                  = b.cod_dep and
								  b.rif                      = c.rif and
								  b.ano_contrato_servicio    = ".$ano_select." and
								  a.ano_contrato_servicio    = ".$ano_select." and
								  a.numero_contrato_servicio = b.numero_contrato_servicio
								  ORDER BY 1, 2, 3, 4, 5, 6, 7");

             $values="";
			 $monto=0;
			 $i=0;
             $x=0;
             $j=1;
             $ann=$ano_select;

                      $suma = count($partidas);

					          $total_suma     = count($partidas);
							  $contar_proceso = 0;
							  $veri_documento = 0;

							foreach($partidas as $partida){

								 $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $suma);}

								  $cod_presi                 = $partida[0]['cod_presi'];
								  $cod_entidad               = $partida[0]['cod_entidad'];
								  $cod_tipo_inst             = $partida[0]['cod_tipo_inst'];
								  $cod_inst                  = $partida[0]['cod_inst'];
								  $cod_dep                   = $partida[0]['cod_dep'];
								  $this->Session->write('SScodpresi', $cod_presi);
								  $this->Session->write('SScodentidad', $cod_entidad);
								  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
								  $this->Session->write('SScodinst', $cod_inst);
								  $this->Session->write('SScoddep', $cod_dep);
								  $ano_documento             = $partida[0]['ano_contrato_servicio'];
								  $fr                        = $partida[0]['fecha_proceso_registro'];
								  $fecha_registro            = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
								  $fd                        = $partida[0]['fecha_contrato_servicio'];//0123-56-89
								  $ano_fd                    = $fd[0].$fd[1].$fd[2].$fd[3];
								  $fd                        = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
								  $ano_fd                    = (int) $ano_fd;
								  $ano_fdpartida             = (int) $partida[0]['ano'];
										  if($ano_fd!=$ano_fdpartida){
										     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
										  }else{
										  	$fecha_documento=$fd;
										  }
								  $numero_documento          = $partida[0]['numero_contrato_servicio'];
								  $ano                       = $partida[0]['ano'];
								  $cod_sector                = $partida[0]['cod_sector'];
								  $cod_programa              = $partida[0]['cod_programa'];
								  $cod_sub_prog              = $partida[0]['cod_sub_prog'];
								  $cod_proyecto              = $partida[0]['cod_proyecto'];
								  $cod_activ_obra            = $partida[0]['cod_activ_obra'];
								  $cod_partida               = $partida[0]['cod_partida'];
								  $cod_generica              = $partida[0]['cod_generica'];
								  $cod_especifica            = $partida[0]['cod_especifica'];
								  $cod_sub_espec             = $partida[0]['cod_sub_espec'];
								  $cod_auxiliar              = $partida[0]['cod_auxiliar'];
								  $monto                     = $partida[0]['monto'];
								  $numero_control_compromiso = $partida[0]['numero_control_compromiso'];
								  $concepto                  = $partida[0]['concepto'];
								  $rif                       = $partida[0]['rif'];
								  $contratista               = $partida[0]['denominacion'];

							   $resultado=strpos("CONTRATISTA:", $concepto);
							if($resultado==false){
						   	   $concepto="CONTRATISTA: ".$contratista." DENOMINACIÓN DEL SERVICIO: ".$concepto;
								}


								if ($numero_documento!=$veri_documento){
												$nco++;
												$numero_compromiso=$nco;
											if($numero_compromiso==1){
												$sql_up="INSERT INTO cfpd21_numero_asiento_compromiso VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_compromiso');";
												$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
		     									}else{
													$sql_up="UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso=$numero_compromiso WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_compromiso=$ano";
													$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
													}
						 								$this->Session->write('numero_compromiso', $nco);
	  									}
														$veri_documento=$numero_documento;

								  	       				   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
														   $to   = 1;
														   $td   = 3;
														   $ta   = 4;
														   $mt   = $monto;
														   $ccp  = str_replace("'","",$concepto);
														   $rnco = $numero_compromiso;

														   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $rnco, null, null, null, $j, null, null);
											         $j++;
											         $x++;
											         $this->cepd02_contratoservicio_partidas->execute("UPDATE cepd02_contratoservicio_partidas SET numero_control_compromiso=".$rnco." WHERE cod_dep=".$cod_dep." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_documento."'");

								}//fin foreach



unset($partidas);
unset($partida);
unset($cp);


     	 $this->layout = "ajax";
     	 $this->set('siguiente', 'contratos_servicio_anulados');
     	 $this->set('pagina',   null);
         $this->render('vista_index');


}//fin function contratos_servicio




function contratos_servicio_anulados($var=null){
$nco = $this->Session->read('numero_compromiso');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;


porcentaje_barra(0, 100, "Anulación de Contratos de Servicio", 1);


							  $partidas=$this->cepd02_contratoservicio_cuerpo->execute("select
										  a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.ano_contrato_servicio,
										  a.numero_contrato_servicio,
										  a.ano,
										  a.cod_sector,
										  a.cod_programa,
										  a.cod_sub_prog,
										  a.cod_proyecto,
										  a.cod_activ_obra,
										  a.cod_partida,
										  a.cod_generica,
										  a.cod_especifica,
										  a.cod_sub_espec,
										  a.cod_auxiliar,
										  a.monto,
										  a.numero_control_compromiso,
										  b.fecha_contrato_servicio,
										  b.fecha_proceso_registro,
										  b.fecha_proceso_anulacion,
										  b.numero_anulacion
										from
										  cepd02_contratoservicio_partidas a,cepd02_contratoservicio_cuerpo b
										where
										  a.cod_presi                = b.cod_presi and
										  a.cod_entidad              = b.cod_entidad and
										  a.cod_tipo_inst            = b.cod_tipo_inst and
										  a.cod_inst                 = b.cod_inst and
										  a.cod_dep                  = b.cod_dep and
										  b.ano_contrato_servicio    = ".$ano_select." and
										  a.ano_contrato_servicio    = ".$ano_select." and
										  a.numero_contrato_servicio = b.numero_contrato_servicio and
										  b.condicion_actividad      = 2");



							  $total_suma     = count($partidas);
							  $contar_proceso = 0;
							  $j=1;

							foreach($partidas as $partida){


								 $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}



											  $cod_presi                = $partida[0]['cod_presi'];
											  $cod_entidad              = $partida[0]['cod_entidad'];
											  $cod_tipo_inst            = $partida[0]['cod_tipo_inst'];
											  $cod_inst                 = $partida[0]['cod_inst'];
											  $cod_dep                  = $partida[0]['cod_dep'];
											  $this->Session->write('SScodpresi', $cod_presi);
											  $this->Session->write('SScodentidad', $cod_entidad);
											  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
											  $this->Session->write('SScodinst', $cod_inst);
											  $this->Session->write('SScoddep', $cod_dep);
											  $ano_documento            = $partida[0]['ano_contrato_servicio'];
											  $fr                       = $partida[0]['fecha_proceso_anulacion'];
											  $fecha_registro           = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
											  $fd                       = $partida[0]['fecha_proceso_anulacion'];//0123-56-89
											  $ano_fd                   = $fd[0].$fd[1].$fd[2].$fd[3];
											  $fd                       = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
											  $ano_fd                   = (int) $ano_fd;
											  $ano_fdpartida            = (int) $partida[0]['ano'];
											  $numero_documento         = $partida[0]['numero_contrato_servicio'];
											  $ano                      = $partida[0]['ano'];
											  $cod_sector               = $partida[0]['cod_sector'];
											  $cod_programa             = $partida[0]['cod_programa'];
											  $cod_sub_prog             = $partida[0]['cod_sub_prog'];
											  $cod_proyecto             = $partida[0]['cod_proyecto'];
											  $cod_activ_obra           = $partida[0]['cod_activ_obra'];
											  $cod_partida              = $partida[0]['cod_partida'];
											  $cod_generica             = $partida[0]['cod_generica'];
											  $cod_especifica           = $partida[0]['cod_especifica'];
											  $cod_sub_espec            = $partida[0]['cod_sub_espec'];
											  $cod_auxiliar             = $partida[0]['cod_auxiliar'];
											  $monto                    = $partida[0]['monto'];
											  $numero_compromiso        = $partida[0]['numero_control_compromiso'];
											  $numero_anulacion         = $partida[0]['numero_anulacion'];
										  	  $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='234' and numero_documento='".$numero_documento."' and ano_acta_anulacion=".$ano_select);
											  $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

								                               $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
															   $to   = 2;
															   $td   = 3;
															   $ta   = 4;
															   $nda  = $numero_compromiso;
															   $mt   = $monto;
															   $ccp  = str_replace("'","",$concepto_anulacion);

															   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano_documento, $numero_documento, null, null, null, null, null, null, null, $nda, null, null, null, null);

											$j++;
											}

unset($partidas);
unset($partida);
unset($cp);

		 $this->layout = "ajax";
     	 $this->set('siguiente', 'modificacion_contratos_servicio');
     	 $this->set('pagina',   null);
         $this->render('vista_index');


}//fin function contratos_servicio_anulados





function modificacion_contratos_servicio($var=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Modificación de Contratos de Servicio", 1);

						  $partidas=$this->cepd02_contratoservicio_cuerpo->execute("select
								  a.cod_presi,
								  a.cod_entidad,
								  a.cod_tipo_inst,
								  a.cod_inst,
								  a.cod_dep,
								  a.ano_contrato_servicio,
								  a.numero_contrato_servicio,
								  a.ano,
								  a.cod_sector,
								  a.cod_programa,
								  a.cod_sub_prog,
								  a.cod_proyecto,
								  a.cod_activ_obra,
								  a.cod_partida,
								  a.cod_generica,
								  a.cod_especifica,
								  a.cod_sub_espec,
								  a.cod_auxiliar,
								  a.monto,
								  b.fecha_modificacion as fecha_modificacion,
								  b.numero_modificacion,b.tipo_modificacion,
								  c.fecha_proceso_registro,
								  b.fecha_proceso_anulacion,
								  b.numero_anulacion,
								  b.tipo_modificacion,
								  b.observaciones as concepto,
								  d.numero_control_compromiso
								   from
								  cepd02_contratoservicio_modificacion_partidas a,cepd02_contratoservicio_modificacion_cuerpo b, cepd02_contratoservicio_cuerpo c, cepd02_contratoservicio_partidas d
								where
										  a.cod_presi                = d.cod_presi and
										  a.cod_entidad              = d.cod_entidad and
										  a.cod_tipo_inst            = d.cod_tipo_inst and
										  a.cod_inst                 = d.cod_inst and
										  a.cod_dep                  = d.cod_dep and
										  a.numero_contrato_servicio = d.numero_contrato_servicio and
										  a.ano                      = d.ano and
							              a.cod_sector               = d.cod_sector and
							  			  a.cod_programa             = d.cod_programa and
							  			  a.cod_sub_prog             = d.cod_sub_prog and
							  			  a.cod_proyecto             = d.cod_proyecto and
							  			  a.cod_activ_obra           = d.cod_activ_obra and
							  			  a.cod_partida              = d.cod_partida and
							  			  a.cod_generica             = d.cod_generica and
							  			  a.cod_especifica           = d.cod_especifica and
							  			  a.cod_sub_espec            = d.cod_sub_espec and
							  			  a.cod_auxiliar             = d.cod_auxiliar and
							  			  a.cod_presi                = b.cod_presi and
										  a.cod_entidad              = b.cod_entidad and
										  a.cod_tipo_inst            = b.cod_tipo_inst and
										  a.cod_inst                 = b.cod_inst and
										  a.cod_dep                  = b.cod_dep and
										  a.numero_contrato_servicio = b.numero_contrato_servicio and
                                          a.numero_modificacion      = b.numero_modificacion and
								          b.cod_presi                = c.cod_presi and
										  b.cod_entidad              = c.cod_entidad and
										  b.cod_tipo_inst            = c.cod_tipo_inst and
										  b.cod_inst                 = c.cod_inst and
										  b.cod_dep                  = c.cod_dep and
										  b.numero_contrato_servicio = c.numero_contrato_servicio and
										  a.ano_contrato_servicio    = ".$ano_select." and
										  b.ano_contrato_servicio    = ".$ano_select." and
                                          c.ano_contrato_servicio    = ".$ano_select." and
										  d.ano_contrato_servicio    = ".$ano_select.";");

  $values="";
  $monto=0;
  $i=0;
  $ann=$ano_select;
  $j=1;
  $x=0;
  $suma = count($partidas);

						$total_suma     = count($partidas);
						$contar_proceso = 0;


							foreach($partidas as $partida){


								 $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $suma);}

								  $cod_presi           = $partida[0]['cod_presi'];
								  $cod_entidad         = $partida[0]['cod_entidad'];
								  $cod_tipo_inst       = $partida[0]['cod_tipo_inst'];
								  $cod_inst            = $partida[0]['cod_inst'];
								  $cod_dep             = $partida[0]['cod_dep'];
								  $this->Session->write('SScodpresi', $cod_presi);
								  $this->Session->write('SScodentidad', $cod_entidad);
								  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
								  $this->Session->write('SScodinst', $cod_inst);
								  $this->Session->write('SScoddep', $cod_dep);
								  $ano_documento       = $partida[0]['ano_contrato_servicio'];
								  $fecha_documento     = $this->Cfecha($partida[0]['fecha_modificacion'],"D/M/A");
								  $fd                  = $this->Cfecha($partida[0]['fecha_modificacion'],"D/M/A");
								  $fd                  = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
								  $fecha_documento     = $fd;
								  $ano_documento       = $partida[0]['ano_contrato_servicio'];
								  $fr                  = $partida[0]['fecha_proceso_registro'];
								  $fecha_registro      = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
								  $fd                  = $partida[0]['fecha_modificacion'];//0123-56-89
								  $ano_fd              = $fd[0].$fd[1].$fd[2].$fd[3];
								  $fd                  = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
								  $ano_fd              = (int) $ano_fd;
								  $ano_fdpartida       = (int) $partida[0]['ano'];
										  if($ano_fd!=$ano_fdpartida){
										     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
										  }else{
										  	$fecha_documento=$fd;
										  }
								  $numero_documento    = $partida[0]['numero_contrato_servicio'];
								  $numero_modificacion = $partida[0]['numero_modificacion'];
								  $ano                 = $partida[0]['ano'];
								  $cod_sector          = $partida[0]['cod_sector'];
								  $cod_programa        = $partida[0]['cod_programa'];
								  $cod_sub_prog        = $partida[0]['cod_sub_prog'];
								  $cod_proyecto        = $partida[0]['cod_proyecto'];
								  $cod_activ_obra      = $partida[0]['cod_activ_obra'];
								  $cod_partida         = $partida[0]['cod_partida'];
								  $cod_generica        = $partida[0]['cod_generica'];
								  $cod_especifica      = $partida[0]['cod_especifica'];
								  $cod_sub_espec       = $partida[0]['cod_sub_espec'];
								  $cod_auxiliar        = $partida[0]['cod_auxiliar'];
								  $monto               = $partida[0]['monto'];
								  $numero_compromiso   = $partida[0]['numero_control_compromiso'];
								  $concepto            = $partida[0]['concepto'];
								  $tipo_modificacion   = $partida[0]['tipo_modificacion'];

								  if($tipo_modificacion==1){
								  	$tm='5';
								  }else if($tipo_modificacion==2){
								  	$tm='6';
								  }

							     	       $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
										   $to   = 1;
										   $td   = 2;
										   $ta   = $tm;
										   $mt   = $monto;
										   $ccp  = str_replace("'","",$concepto);
										   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, $numero_modificacion, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);
													         $j++;
													         $x++;
													         $this->cepd02_contratoservicio_partidas->execute("UPDATE cepd02_contratoservicio_partidas SET numero_control_compromiso=".$numero_compromiso." WHERE cod_dep=".$cod_dep." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_documento."'");
						}//fin foreach

//echo "<script>alert('modificacion_contratos_servicio ".$cp."')</script>";

unset($partidas);
unset($partida);
unset($cp);

		 $this->layout = "ajax";
     	 $this->set('siguiente', 'anulacion_modificacion_contratos_servicio');
     	 $this->set('pagina',   null);
         $this->render('vista_index');

}//fin function modificacion_contratos_servicio




function anulacion_modificacion_contratos_servicio($var=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    set_time_limit(0);
	$ano_select = YEAR_REACTUALIZACION;


porcentaje_barra(0, 100, "Anulación de Modificación Contratos de Servicio", 1);



						  $partidas=$this->cepd02_contratoservicio_cuerpo->execute("select
								  a.cod_presi,
								  a.cod_entidad,
								  a.cod_tipo_inst,
								  a.cod_inst,
								  a.cod_dep,
								  a.ano_contrato_servicio,
								  a.numero_contrato_servicio,
								  a.ano,
								  a.cod_sector,
								  a.cod_programa,
								  a.cod_sub_prog,
								  a.cod_proyecto,
								  a.cod_activ_obra,
								  a.cod_partida,
								  a.cod_generica,
								  a.cod_especifica,
								  a.cod_sub_espec,
								  a.cod_auxiliar,
								  a.monto,
								  a.numero_control_compromiso,
								  b.fecha_modificacion as fecha_modificacion,
								  b.numero_modificacion,b.tipo_modificacion,
								  c.fecha_proceso_registro,
								  b.fecha_proceso_anulacion,
								  b.numero_anulacion
								 from
								  cepd02_contratoservicio_modificacion_partidas a,cepd02_contratoservicio_modificacion_cuerpo b,cepd02_contratoservicio_cuerpo c
								where
								          b.cod_presi                = c.cod_presi and
										  b.cod_entidad              = c.cod_entidad and
										  b.cod_tipo_inst            = c.cod_tipo_inst and
										  b.cod_inst                 = c.cod_inst and
										  b.cod_dep                  = c.cod_dep and
                                          a.cod_presi                = b.cod_presi and
										  a.cod_entidad              = b.cod_entidad and
										  a.cod_tipo_inst            = b.cod_tipo_inst and
										  a.cod_inst                 = b.cod_inst and
										  a.cod_dep                  = b.cod_dep and
                                          c.ano_contrato_servicio    = ".$ano_select." and
										  b.ano_contrato_servicio    = ".$ano_select." and
										  a.ano_contrato_servicio    = ".$ano_select." and
                                          b.numero_contrato_servicio = c.numero_contrato_servicio and
                                          a.numero_contrato_servicio = b.numero_contrato_servicio and
                                          a.numero_modificacion      = b.numero_modificacion and
								          b.condicion_actividad      = 2");
							  $j=1;


							  $total_suma     = count($partidas);
							  $contar_proceso = 0;

							foreach($partidas as $partida){



								 $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}


							  $cod_presi                = $partida[0]['cod_presi'];
							  $cod_entidad              = $partida[0]['cod_entidad'];
							  $cod_tipo_inst            = $partida[0]['cod_tipo_inst'];
							  $cod_inst                 = $partida[0]['cod_inst'];
							  $cod_dep                  = $partida[0]['cod_dep'];
							  $this->Session->write('SScodpresi', $cod_presi);
							  $this->Session->write('SScodentidad', $cod_entidad);
							  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
							  $this->Session->write('SScodinst', $cod_inst);
							  $this->Session->write('SScoddep', $cod_dep);
							  $ano_documento            = $partida[0]['ano_contrato_servicio'];
							  $fecha_documento          = $this->Cfecha($partida[0]['fecha_proceso_anulacion'],"D/M/A");
							  $fd                       = $this->Cfecha($partida[0]['fecha_proceso_anulacion'],"D/M/A");
							  $fd                       = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
							  $fecha_documento          = $fd;
							  $ano_documento            = $partida[0]['ano_contrato_servicio'];
							  $fr                       = $partida[0]['fecha_proceso_anulacion'];
							  $fecha_registro           = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
							  $fd                       = $partida[0]['fecha_proceso_anulacion'];//0123-56-89
							  $ano_fd                   = $fd[0].$fd[1].$fd[2].$fd[3];
							  $fd                       = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
							  $ano_fd                   = (int) $ano_fd;
							  $ano_fdpartida            = (int) $partida[0]['ano'];
									  if($ano_fd!=$ano_fdpartida){
									     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
									  }else{
									  	$fecha_documento=$fd;
									  }
							  $numero_documento         = $partida[0]['numero_contrato_servicio'];
							  $ano                      = $partida[0]['ano'];
							  $cod_sector               = $partida[0]['cod_sector'];
							  $cod_programa             = $partida[0]['cod_programa'];
							  $cod_sub_prog             = $partida[0]['cod_sub_prog'];
							  $cod_proyecto             = $partida[0]['cod_proyecto'];
							  $cod_activ_obra           = $partida[0]['cod_activ_obra'];
							  $cod_partida              = $partida[0]['cod_partida'];
							  $cod_generica             = $partida[0]['cod_generica'];
							  $cod_especifica           = $partida[0]['cod_especifica'];
							  $cod_sub_espec            = $partida[0]['cod_sub_espec'];
							  $cod_auxiliar             = $partida[0]['cod_auxiliar'];
							  $monto                    = $partida[0]['monto'];
							  $numero_compromiso        = $partida[0]['numero_control_compromiso'];
							  $numero_anulacion         = $partida[0]['numero_anulacion'];
							  $tipo_modificacion        = $partida[0]['tipo_modificacion'];
							  $nro_modificacion         = $partida[0]['numero_modificacion'];
							  if($tipo_modificacion==1){
							  	 $tm='5';
							  }else if($tipo_modificacion==2){
							  	 $tm='6';
							  }
							  $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='234' and numero_documento='".$numero_documento."' and ano_acta_anulacion=".$ano_select);
							  $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

								  $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								  $to   = 2;
								  $td   = 2;
								  $ta   = $tm;
								  $mt   = $monto;
								  $ccp  = str_replace("'","",$concepto_anulacion);
								  $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, $nro_modificacion, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);
								  $j++;

						}//fin foreach


unset($partidas);
unset($partida);
unset($cp);

     	 $this->layout = "ajax";
     	 $this->set('siguiente', 'anticipo_contratos_servicios');
     	 $this->set('pagina',   null);
         $this->render('vista_index');


}//fin function anulacion_modificacion_contratos_servicio


function anticipo_contratos_servicios() {
$nca = $this->Session->read('numero_causado');
if ($nca==0 || $nca==0){$nca=0;}
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }


porcentaje_barra(0, 100, "Anticipo Contratos de Servicio", 1);

  $partidas=$this->cepd02_contratoservicio_anticipo_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.numero_anticipo,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_causado,
  b.fecha_anticipo,b.fecha_proceso_registro,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.observaciones as concepto,
  d.numero_control_compromiso
   from
  cepd02_contratoservicio_anticipo_partidas a, cepd02_contratoservicio_anticipo_cuerpo b, cepd02_contratoservicio_anticipo_partidas d
where
  a.cod_presi                = d.cod_presi and
  a.cod_entidad              = d.cod_entidad and
  a.cod_tipo_inst            = d.cod_tipo_inst and
  a.cod_inst                 = d.cod_inst and
  a.cod_dep                  = d.cod_dep and
  a.numero_contrato_servicio = d.numero_contrato_servicio and
  a.ano                      = d.ano and
  a.cod_sector               = d.cod_sector and
  a.cod_programa             = d.cod_programa and
  a.cod_sub_prog             = d.cod_sub_prog and
  a.cod_proyecto             = d.cod_proyecto and
  a.cod_activ_obra           = d.cod_activ_obra and
  a.cod_partida              = d.cod_partida and
  a.cod_generica             = d.cod_generica and
  a.cod_especifica           = d.cod_especifica and
  a.cod_sub_espec            = d.cod_sub_espec and
  a.cod_auxiliar             = d.cod_auxiliar and
  a.cod_presi                = b.cod_presi and
  a.cod_entidad              = b.cod_entidad and
  a.cod_tipo_inst            = b.cod_tipo_inst and
  a.cod_inst                 = b.cod_inst and
  a.cod_dep                  = b.cod_dep and
  a.numero_contrato_servicio = b.numero_contrato_servicio  and
  a.numero_anticipo          = b.numero_anticipo           and
  a.ano_contrato_servicio    = '".YEAR_REACTUALIZACION."'  and
  b.ano_contrato_servicio    = '".YEAR_REACTUALIZACION."'  and
  d.ano_contrato_servicio    = '".YEAR_REACTUALIZACION."'  and
  b.saldo_ano_anterior       =   2   ");


    $j = 0;
	$i = 0;
	$ano=YEAR_REACTUALIZACION;
	$suma = count($partidas);
	                          $total_suma     = count($partidas);
							  $contar_proceso = 0;
							  $veri_documento = 0;

				foreach($partidas as $partida){


								 $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $suma);}

		  $cod_presi         = $partida[0]['cod_presi'];
		  $cod_entidad       = $partida[0]['cod_entidad'];
		  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
		  $cod_inst          = $partida[0]['cod_inst'];
		  $cod_dep           = $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento    = $partida[0]['ano_contrato_servicio'];
		  $fr                = $partida[0]['fecha_proceso_registro'];
		  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
		  $fd                = $partida[0]['fecha_anticipo'];
		  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
		  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_fd            = (int) $ano_fd;
		  $ano_fdpartida     = (int) $partida[0]['ano'];

				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $ano_orden_compra  = $partida[0]['ano_contrato_servicio'];
		  $ndo               = $partida[0]['numero_contrato_servicio'];
		  $nda               = $partida[0]['numero_anticipo'];
		  $ano               = $partida[0]['ano'];
		  $cod_sector        = $partida[0]['cod_sector'];
		  $cod_programa      = $partida[0]['cod_programa'];
		  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
		  $cod_proyecto      = $partida[0]['cod_proyecto'];
		  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
		  $cod_partida       = $partida[0]['cod_partida'];
		  $cod_generica      = $partida[0]['cod_generica'];
		  $cod_especifica    = $partida[0]['cod_especifica'];
		  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
		  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
		  $monto             = $partida[0]['monto'];
		  $numero_compromiso = $partida[0]['numero_control_compromiso'];
		  $numero_causado    = $partida[0]['numero_control_causado'];
		  $concepto          = $partida[0]['concepto'];
		  $ano_orden_pago    = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];

		  	if ($ndo!=$veri_documento){
				$nca++;
				$numero_causado=$nca;
			    $this->Session->write('numero_causado', $nca);
			}
				$veri_documento=$ndo;

		$cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		$to   = 1;
		$td   = 4;
		$ta   = 6;
		$mt   = $monto;
	    $ccp  = str_replace("'","",$concepto);

		$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);

		$this->cepd02_contratoservicio_anticipo_partidas->execute("UPDATE cepd02_contratoservicio_anticipo_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado." where numero_contrato_servicio='".$ndo."' and numero_anticipo=".$nda." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
        $j++;
	    $i++;

	}//fin foreach

unset($partidas);
unset($partida);
unset($cp);


		 $this->layout = "ajax";
		 $this->set('siguiente', 'ordenes_pago/var_null');
     	 $this->set('pagina',   1);
     	 $this->render('vista_index');


}//fin function anticipo_contratos_servicios






/********************************************
 * ORDENES DE PAGOS
 ********************************************/

function ordenes_pago($var=null,$pagina=null){
$nca = $this->Session->read('numero_causado');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  set_time_limit(0);
  $ano_select = YEAR_REACTUALIZACION;
  $ann=YEAR_REACTUALIZACION;

    $ano_ejecucion=YEAR_REACTUALIZACION;
    $this->Session->write('ano_ejecucion',YEAR_REACTUALIZACION);
    $total_op=$this->v_restaurar_causados_op->findCount($this->condicionNDEP_script()." and ano_orden_pago=".$ano_ejecucion);
    $this->set("total_op",$total_op);
    $Tpag = (int)ceil($total_op/10000);
    $this->set("total_pag_op",$Tpag);
    $this->Session->write('total_pag_op_session', $Tpag);

  porcentaje_barra(0, 100, "Ordenes de pago Pag ".$pagina."/".$this->Session->read('total_pag_op_session'), 1);


//$partidas= $this->v_restaurar_causados_op->findAll("ano_orden_pago=".$ano_select,null,'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,numero_orden_pago,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC','limite' =>10000,$pagina,null);
  $partidas= $this->v_restaurar_causados_op->findAll("ano_orden_pago=".$ano_select,null,'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,numero_orden_pago,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',10000,$pagina,null);
  $i=1;
  $j =0;
  $suma = count($partidas);

  	                          $total_suma     = count($partidas);
							  $contar_proceso = 0;
							  $veri_documento = 0;

							foreach($partidas as $partidaX){

                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}

	$partida[0]=$partidaX['v_restaurar_causados_op'];

  	$cod_presi                 = $partida[0]['cod_presi'];
  	$cod_entidad               = $partida[0]['cod_entidad'];
  	$cod_tipo_inst             = $partida[0]['cod_tipo_inst'];
  	$cod_inst                  = $partida[0]['cod_inst'];
  	$cod_dep                   = $partida[0]['cod_dep'];
  	$this->Session->write('SScodpresi', $cod_presi);
  	$this->Session->write('SScodentidad', $cod_entidad);
  	$this->Session->write('SScodtipoinst', $cod_tipo_inst);
  	$this->Session->write('SScodinst', $cod_inst);
  	$this->Session->write('SScoddep', $cod_dep);
  	$ano_orden_pago            = $partida[0]['ano_orden_pago'];
  	$fd                        = cambiar_formato_fecha($partida[0]['fecha_orden_pago']);
  	$fdoo                      = cambiar_formato_fecha($partida[0]['fecha_documento']);
  	$fecha_orden_pago          = $fdoo;
  	$numero_orden_pago         = $partida[0]['numero_orden_pago'];
  	$numero_documento_origen   = $partida[0]['numero_documento_origen'];
  	$numero_documento_adjunto  = $partida[0]['numero_documento_adjunto'];
  	$ano                       = $partida[0]['ano'];
  	$cod_sector                = $partida[0]['cod_sector'];
  	$cod_programa              = $partida[0]['cod_programa'];
  	$cod_sub_prog              = $partida[0]['cod_sub_prog'];
  	$cod_proyecto              = $partida[0]['cod_proyecto'];
  	$cod_activ_obra            = $partida[0]['cod_activ_obra'];
  	$cod_partida               = $partida[0]['cod_partida'];
  	$cod_generica              = $partida[0]['cod_generica'];
  	$cod_especifica            = $partida[0]['cod_especifica'];
  	$cod_sub_espec             = $partida[0]['cod_sub_espec'];
  	$cod_auxiliar              = $partida[0]['cod_auxiliar'];
  	$monto                     = $partida[0]['monto'];
  	$cod_entidad_bancaria      = $partida[0]['cod_entidad_bancaria'];
  	$cod_sucursal              = $partida[0]['cod_sucursal'];
  	$cuenta_bancaria           = $partida[0]['cuenta_bancaria'];
  	$concepto                  = $partida[0]['concepto'];
    $numero_cheque             = $partida[0]['numero_cheque'];
    $tipo_docum                = $partida[0]['cod_tipo_documento'];
    $ndo = $numero_documento_origen;


			if ($numero_orden_pago!=$veri_documento){
				$nca++;
				$numero_causado=$nca;
				if($numero_causado==1){
					$sql_up="INSERT INTO cfpd22_numero_asiento_causado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_causado');";
					$this->cfpd22_numero_asiento_causado->execute($sql_up);
		     		}else{
					$sql_up="UPDATE cfpd22_numero_asiento_causado SET numero_causado=$numero_causado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_causado=$ano";
					$this->cfpd22_numero_asiento_causado->execute($sql_up);
						}
							$this->Session->write('numero_causado', $nca);
			}
							$veri_documento=$numero_orden_pago;
							$numero_control_compromiso=0;

    	  if ($tipo_docum==1){
    	$ta=1;
    	$doc_tipo='OTROS COMPROMISOS';
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cepd01_compromiso_partidas WHERE ano_documento=".$ano_orden_pago." and numero_documento=".$ndo." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and  ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
    }else if ($tipo_docum==2){
    	$ta=2;
    	$doc_tipo='ANTICIPO ORDEN COMPRA';
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_asiento_compromiso FROM cscd04_ordencompra_partidas WHERE ano_orden_compra=".$ano_orden_pago." and numero_orden_compra=".$ndo."                      and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_asiento_compromiso"];
    }else if ($tipo_docum==3){
    	$ta=3;
    	$doc_tipo='AUTORIZACION ORDEN COMPRA';
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_asiento_compromiso FROM cscd04_ordencompra_partidas WHERE ano_orden_compra=".$ano_orden_pago." and numero_orden_compra=".$ndo."                      and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_asiento_compromiso"];
     }else if ($tipo_docum==4){
    	$ta=4;
    	$doc_tipo='ANTICIPO CONTRATO DE OBRA';
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cobd01_contratoobras_partidas WHERE ano_contrato_obra=".$ano_orden_pago." and upper(numero_contrato_obra)=upper('".$ndo."')     and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
      }else if ($tipo_docum==5){
    	$ta=5;
    	$doc_tipo='VALUACION CONTRATO DE OBRA';
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cobd01_contratoobras_partidas WHERE ano_contrato_obra=".$ano_orden_pago." and upper(numero_contrato_obra)=upper('".$ndo."')     and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
      }else if ($tipo_docum==6){
    	$ta=12;
    	$doc_tipo='RETENCIÓN CONTRATO DE OBRA';
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cobd01_contratoobras_partidas WHERE ano_contrato_obra=".$ano_orden_pago." and upper(numero_contrato_obra)=upper('".$ndo."')     and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
      }else if ($tipo_docum==7){
    	$ta=6;
    	$doc_tipo='ANTICIPO CONTRATO DE SERVICIO';
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cepd02_contratoservicio_partidas WHERE ano_contrato_servicio=".$ano_orden_pago." and upper(numero_contrato_servicio)=upper('".$ndo."') and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
      }else if ($tipo_docum==8){
    	$ta=7;
    	$doc_tipo='VALUACIÓN CONTRATO DE SERVICIO';
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cepd02_contratoservicio_partidas WHERE ano_contrato_servicio=".$ano_orden_pago." and upper(numero_contrato_servicio)=upper('".$ndo."') and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
      }else if ($tipo_docum==9){
    	$ta=11;
    	$doc_tipo='RETENCIÓN CONTRATO DE SERVICIO';
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cepd02_contratoservicio_partidas WHERE ano_contrato_servicio=".$ano_orden_pago." and upper(numero_contrato_servicio)=upper('".$ndo."') and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
      }else {
      	echo "<script>alert('Otro tipo de documento? ".$tipo_docum."')</script>";
      }

	if ($numero_control_compromiso==null || $numero_control_compromiso==0){
		echo "<script>alert('No encontro el documento ".$cod_dep."  ".$doc_tipo."  ".$numero_documento_origen."  ".$numero_documento_adjunto."')</script>";
	}else{

	   $numero_compromiso = $numero_control_compromiso;


    $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	$to   = 1;
	$td   = 4;
	$mt   = $monto;
    $ccp  = str_replace("'","",$concepto);
    $taa  = $ta;


    $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $numero_documento_origen, $numero_documento_adjunto, $numero_orden_pago, $fdoo, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);



    if ($taa==2){$this->cepd03_ordenpago_partidas->execute("UPDATE cscd04_ordencompra_anticipo_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado."              where ano_orden_compra=".$ano_orden_pago."      and numero_orden_compra=".$ndo."        and numero_anticipo=".$numero_documento_adjunto."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");}
	if ($taa==3){$this->cepd03_ordenpago_partidas->execute("UPDATE cscd04_ordencompra_autorizacion_pago_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado."       where ano_orden_compra=".$ano_orden_pago."      and numero_orden_compra=".$ndo ."       and numero_pago=".$numero_documento_adjunto."      and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");}
	if ($taa==4){$this->cepd03_ordenpago_partidas->execute("UPDATE cobd01_contratoobras_anticipo_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado."          where ano_contrato_obra=".$ano_orden_pago."     and numero_contrato_obra='".$ndo."'     and numero_anticipo=".$numero_documento_adjunto."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");}
	if ($taa==5){$this->cepd03_ordenpago_partidas->execute("UPDATE cobd01_contratoobras_valuacion_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado."        where ano_contrato_obra=".$ano_orden_pago."     and numero_contrato_obra='".$ndo."'     and numero_valuacion=".$numero_documento_adjunto." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");}
	if ($taa==12){$this->cepd03_ordenpago_partidas->execute("UPDATE cobd01_contratoobras_retencion_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado."       where ano_contrato_obra=".$ano_orden_pago."     and numero_contrato_obra='".$ndo."'     and numero_retencion=".$numero_documento_adjunto." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");}
	if ($taa==6){$this->cepd03_ordenpago_partidas->execute("UPDATE cepd02_contratoservicio_anticipo_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado."    where ano_contrato_servicio=".$ano_orden_pago." and numero_contrato_servicio='".$ndo."' and numero_anticipo=".$numero_documento_adjunto."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");}
	if ($taa==7){$this->cepd03_ordenpago_partidas->execute("UPDATE cepd02_contratoservicio_valuacion_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado."  where ano_contrato_servicio=".$ano_orden_pago." and numero_contrato_servicio='".$ndo."' and numero_valuacion=".$numero_documento_adjunto." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");}
	if ($taa==11){$this->cepd03_ordenpago_partidas->execute("UPDATE cepd02_contratoservicio_retencion_partidas SET numero_control_compromiso = ".$numero_compromiso.", numero_control_causado = ".$numero_causado." where ano_contrato_servicio=".$ano_orden_pago." and numero_contrato_servicio='".$ndo."' and numero_retencion=".$numero_documento_adjunto." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");}



    		$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso=".$numero_compromiso.", numero_control_causado=".$numero_causado." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	if ($numero_cheque!=null || $numero_cheque!=0){
    		$this->cepd03_ordenpago_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=".$numero_compromiso." ,numero_control_causado=".$numero_causado."   WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
   		 }

    	$i++;
   		$j++;
	}

 }//fin foreach

unset($partidas);
unset($partida);
unset($partidaX);
unset($cp);

$this->layout = "ajax";
//$this->set('mensaje', "Pag [".$pagina."] Causado fue realizado con exito");

if($this->Session->read('total_pag_op_session')==$pagina || $this->Session->read('total_pag_op_session')==0){
 	$this->set('pagina',    null);
 	$this->set('siguiente', 'anulacion_ordenes_pago');
 }else{
 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'ordenes_pago/var_null');
 }
	$this->render('vista_index');


}//fin function ordenes_pago




function anulacion_ordenes_pago(){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  set_time_limit(0);
  $ano_select = YEAR_REACTUALIZACION;
  $ann=YEAR_REACTUALIZACION;



  porcentaje_barra(0, 100, "Anulación de Ordenes de pago", 1);




  $partidas= $this->v_restaurar_causados_op->findAll("condicion_actividad=2 and ano_orden_pago=".$ano_select,null,'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,numero_orden_pago,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
  $j=1;


                              $total_suma     = count($partidas);
							  $contar_proceso = 0;

							foreach($partidas as $partidaX){



                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}


	$partida[0]=$partidaX['v_restaurar_causados_op'];

  	$cod_presi          = $partida[0]['cod_presi'];
  	$cod_entidad        = $partida[0]['cod_entidad'];
  	$cod_tipo_inst      = $partida[0]['cod_tipo_inst'];
  	$cod_inst           = $partida[0]['cod_inst'];
  	$cod_dep            = $partida[0]['cod_dep'];
  	$this->Session->write('SScodpresi', $cod_presi);
  	$this->Session->write('SScodentidad', $cod_entidad);
  	$this->Session->write('SScodtipoinst', $cod_tipo_inst);
  	$this->Session->write('SScodinst', $cod_inst);
  	$this->Session->write('SScoddep', $cod_dep);
  	$ano_orden_pago     = $partida[0]['ano_orden_pago'];
  	$fd                 = cambiar_formato_fecha($partida[0]['fecha_proceso_anulacion']);
  	$fdoo               = cambiar_formato_fecha($partida[0]['fecha_proceso_anulacion']);
  	$fecha_orden_pago   = $fdoo;
  	$opago              = $partida[0]['numero_orden_pago'];
  	$ndo                = $partida[0]['numero_documento_origen'];
  	$nda                = $partida[0]['numero_documento_adjunto'];
  	$ano                = $partida[0]['ano'];
  	$cod_sector         = $partida[0]['cod_sector'];
  	$cod_programa       = $partida[0]['cod_programa'];
  	$cod_sub_prog       = $partida[0]['cod_sub_prog'];
  	$cod_proyecto       = $partida[0]['cod_proyecto'];
  	$cod_activ_obra     = $partida[0]['cod_activ_obra'];
  	$cod_partida        = $partida[0]['cod_partida'];
  	$cod_generica       = $partida[0]['cod_generica'];
  	$cod_especifica     = $partida[0]['cod_especifica'];
  	$cod_sub_espec      = $partida[0]['cod_sub_espec'];
  	$cod_auxiliar       = $partida[0]['cod_auxiliar'];
  	$monto              = $partida[0]['monto'];
  	$numero_compromiso  = $partida[0]['numero_control_compromiso'];
  	$numero_causado     = $partida[0]['numero_control_causado'];
  	$numero_anulacion   = $partida[0]['numero_anulacion'];
  	$concepto           = $partida[0]['concepto'];
    $concepto_anulacion = $partida[0]['concepto_anulacion'];

  	switch($partida[0]['cod_tipo_documento']){
    	case 1: $ta=1;  break;//RC
    	case 2: $ta=2;  break;//OC anticipos
    	case 3: $ta=3;  break;//OC autorizacion
    	case 4: $ta=4;  break;//OB anticipos
    	case 5: $ta=5;  break;//OB valuaciones
    	case 6: $ta=12; break;//OB retenciones
    	case 7: $ta=6;  break;//SR anticipos
    	case 8: $ta=7;  break;//SR valuacion
    	case 9: $ta=11; break;//SR retencion
    }//fin switch

   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
   $to   = 2;
   $td   = 4;
   $mt   = $monto;
   $ccp  = str_replace("'","",$concepto_anulacion);

   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, $opago, $fdoo, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);

   $j++;

 }//fin foreach
unset($partidas);
unset($partida);
unset($partidaX);
unset($cp);

	 $this->layout = "ajax";
     $this->set('pagina',    1);
  	 $this->set('siguiente', 'pagado/var_null');
 	 $this->render('vista_index');

}//fin function anulacion_ordenes_pago








/********************************************************
 * PAGADO
 ********************************************************/
function pagado($var=null,$pagina=null){
$npa = $this->Session->read('numero_pagado');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;

    $total_ch=$this->v_restaurar_pagados->findCount($this->condicionNDEP_script()." and ano_movimiento=".$ano_select);
    $this->set("total_ch",$total_ch);
    $Tpag_ch = (int)ceil($total_ch/10000);
    $this->set("total_pag_ch",$Tpag_ch);
    $this->Session->write('total_pag_ch_session', $Tpag_ch);


porcentaje_barra(0, 100, "Pagado ".$pagina."/".$this->Session->read('total_pag_ch_session'), 1);

    $partidas= $this->v_restaurar_pagados->findAll("ano_movimiento=".$ano_select,null,'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,fecha_cheque,cod_entidad_bancaria,cod_sucursal,cuenta_bancaria,numero_cheque,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',10000,$pagina,null);
  	$suma = count($partidas);
	$j = 1;
                              $total_suma     = count($partidas);
							  $contar_proceso = 0;
							  $veri_documento = 0;

							foreach($partidas as $partidaX){



                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}


  $partida[0]=$partidaX['v_restaurar_pagados'];


  $cod_presi            = $partida[0]['cod_presi'];
  $cod_entidad          = $partida[0]['cod_entidad'];
  $cod_tipo_inst        = $partida[0]['cod_tipo_inst'];
  $cod_inst             = $partida[0]['cod_inst'];
  $cod_dep              = $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_movimiento       = $partida[0]['ano_movimiento'];
  $ano_anterior         = $partida[0]['ano_anterior'];
  $clase_beneficiario   = $partida[0]['clase_beneficiario'];
  $fd                   = cambiar_formato_fecha($partida[0]['fecha_cheque']);
  $fdoo                 = cambiar_formato_fecha($partida[0]['fecha_cheque']);
  $fecha_orden_pago     = $fdoo;
  $cod_entidad_bancaria = $partida[0]['cod_entidad_bancaria'];
  $cod_sucursal         = $partida[0]['cod_sucursal'];
  $cuenta_bancaria      = $partida[0]['cuenta_bancaria'];
  $numero_cheque        = $partida[0]['numero_cheque'];
  $clase_orden          = $partida[0]['clase_orden'];
  $ano_orden_pago       = $partida[0]['ano_orden_pago'];
  $ano                  = $partida[0]['ano'];
  $cod_sector           = $partida[0]['cod_sector'];
  $cod_programa         = $partida[0]['cod_programa'];
  $cod_sub_prog         = $partida[0]['cod_sub_prog'];
  $cod_proyecto         = $partida[0]['cod_proyecto'];
  $cod_activ_obra       = $partida[0]['cod_activ_obra'];
  $cod_partida          = $partida[0]['cod_partida'];
  $cod_generica         = $partida[0]['cod_generica'];
  $cod_especifica       = $partida[0]['cod_especifica'];
  $cod_sub_espec        = $partida[0]['cod_sub_espec'];
  $cod_auxiliar         = $partida[0]['cod_auxiliar'];
  $monto                = $partida[0]['monto'];
  $numero_compromiso    = $partida[0]['numero_control_compromiso'];
  $numero_causado       = $partida[0]['numero_control_causado'];
  $numero_orden_pago    = $partida[0]['numero_orden_pago'];
  $concepto             = $partida[0]['concepto'];


  		if ($numero_cheque!=$veri_documento){

					$npa++;
					$numero_pagado=$npa;
				if($numero_pagado==1){
					$sql_up="INSERT INTO cfpd23_numero_asiento_pagado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_pagado');";
					$this->cfpd23_numero_asiento_pagado->execute($sql_up);
		     		}else{
						$sql_up="UPDATE cfpd23_numero_asiento_pagado SET numero_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_pagado=$ano";
						$this->cfpd23_numero_asiento_pagado->execute($sql_up);
						}
							$this->Session->write('numero_pagado', $npa);
		}
							$veri_documento=$numero_cheque;


  $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
  $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";

  $cp  = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
  $to  = 1;
  $td  = 5;
  $ta  = 1;
  $mt  = $monto;
  $ccp = str_replace("'","",$concepto);

  $c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
  $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

    $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $sql_actual = $condicion." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' ";

       if($clase_beneficiario==1){

 }else if($clase_beneficiario==2){

 	$resul        = $this->cstd07_retenciones_cuerpo_islr->findAll($sql_actual);
      if(isset( $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'])){$ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'];}

 }else if($clase_beneficiario==3){

 	$resul        = $this->cstd07_retenciones_cuerpo_timbre->findAll($sql_actual);
      if(isset( $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'])){$ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'];}

 }else if($clase_beneficiario==4){

 	$resul        = $this->cstd07_retenciones_cuerpo_municipal->findAll($sql_actual);
      if(isset( $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'])){$ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'];}

 }else if($clase_beneficiario==5){

 	$resul        = $this->cstd07_retenciones_cuerpo_iva->findAll($sql_actual);
      if(isset( $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'])){$ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'];}

 }//fin else

		if($ano_anterior==1){
		    $dnco = 0;
		}else{
			$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, null, null, $opago=$numero_orden_pago, $opfecha=$fecha_orden_pago, $cbanco=$cod_entidad_bancaria_aux, $ccuenta=$cuenta_bancaria, $ccheque=$numero_cheque, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);

            	$sql_cstd03_cheque_partidas = "UPDATE cstd03_cheque_partidas SET  numero_control_pagado=".$numero_pagado." ";
  				$sql_cstd03_cheque_partidas.= "WHERE  cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_cheque='$numero_cheque' and ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
  				$this->cstd03_cheque_cuerpo->execute($sql_cstd03_cheque_partidas);
  			 $j++;
		}




}//fin foreach

unset($partidas);
unset($partida);
unset($partidaX);
unset($cp);

 $this->layout = "ajax";
 //$this->set('mensaje', "Pag [".$pagina."] Pagado fue realizado con exito");


 if($this->Session->read('total_pag_ch_session')==$pagina || $this->Session->read('total_pag_ch_session')==0){
 	$this->set('pagina',    null);
 	$this->set('siguiente', 'anulacion_pagado');
 }else{
 	$this->set('pagina',    $pagina+1);
 	$this->set('siguiente', 'pagado/var_null');

 }
 	$this->render('vista_index');


}//fin function pagado





function anulacion_pagado(){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  set_time_limit(0);
  $ano_select = YEAR_REACTUALIZACION;



  porcentaje_barra(0, 100, "Anulación Pagado", 1);


  $partidas= $this->v_restaurar_pagados->findAll("condicion_actividad=2 and ano_movimiento=".$ano_select,null,'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,fecha_cheque,cod_entidad_bancaria,cod_sucursal,cuenta_bancaria,numero_cheque,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);

  $j = 1;
                              $total_suma     = count($partidas);
							  $contar_proceso = 0;



		foreach($partidas as $partidaX){



                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}



  $partida[0]=$partidaX['v_restaurar_pagados'];



  $cod_presi            = $partida[0]['cod_presi'];
  $cod_entidad          = $partida[0]['cod_entidad'];
  $cod_tipo_inst        = $partida[0]['cod_tipo_inst'];
  $cod_inst             = $partida[0]['cod_inst'];
  $cod_dep              = $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_movimiento       = $partida[0]['ano_movimiento'];
  $ano_anterior         = $partida[0]['ano_anterior'];
  $clase_beneficiario   = $partida[0]['clase_beneficiario'];
  $fd                   = cambiar_formato_fecha($partida[0]['fecha_proceso_anulacion']);
  $fdoo                 = cambiar_formato_fecha($partida[0]['fecha_proceso_anulacion']);
  $fecha_orden_pago     = $fdoo;
  $cod_entidad_bancaria = $partida[0]['cod_entidad_bancaria'];
  $cod_sucursal         = $partida[0]['cod_sucursal'];
  $cuenta_bancaria      = $partida[0]['cuenta_bancaria'];
  $numero_cheque        = $partida[0]['numero_cheque'];
  $clase_orden          = $partida[0]['clase_orden'];
  $ano_orden_pago       = $partida[0]['ano_orden_pago'];
  $ano                  = $partida[0]['ano'];
  $cod_sector           = $partida[0]['cod_sector'];
  $cod_programa         = $partida[0]['cod_programa'];
  $cod_sub_prog         = $partida[0]['cod_sub_prog'];
  $cod_proyecto         = $partida[0]['cod_proyecto'];
  $cod_activ_obra       = $partida[0]['cod_activ_obra'];
  $cod_partida          = $partida[0]['cod_partida'];
  $cod_generica         = $partida[0]['cod_generica'];
  $cod_especifica       = $partida[0]['cod_especifica'];
  $cod_sub_espec        = $partida[0]['cod_sub_espec'];
  $cod_auxiliar         = $partida[0]['cod_auxiliar'];
  $monto                = $partida[0]['monto'];
  $numero_compromiso    = $partida[0]['numero_control_compromiso'];
  $numero_causado       = $partida[0]['numero_control_causado'];
  $numero_pagado        = $partida[0]['numero_control_pagado'];
  $numero_orden_pago    = $partida[0]['numero_orden_pago'];
  $numero_anulacion     = $partida[0]['numero_anulacion'];
  $concepto_anulacion   = $partida[0]['concepto_anulacion'];



  $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
  $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";


    $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $sql_actual = $condicion." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' ";


       if($clase_beneficiario==1){

 }else if($clase_beneficiario==2){

 	$resul        = $this->cstd07_retenciones_cuerpo_islr->findAll($sql_actual);
      if(isset( $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'])){$ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'];}

 }else if($clase_beneficiario==3){

 	$resul        = $this->cstd07_retenciones_cuerpo_timbre->findAll($sql_actual);
      if(isset( $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'])){$ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'];}

 }else if($clase_beneficiario==4){

 	$resul        = $this->cstd07_retenciones_cuerpo_municipal->findAll($sql_actual);
      if(isset( $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'])){$ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'];}

 }else if($clase_beneficiario==5){

 	$resul        = $this->cstd07_retenciones_cuerpo_iva->findAll($sql_actual);
      if(isset( $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'])){$ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'];}

 }//fin else


			$cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

		if($ano_anterior==1){
		    $dnco = 0;
		}else{
			$dnco = $this->motor_presupuestario($cp, 2, 5, 1, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, null, null, $numero_orden_pago, $opfecha=$fecha_orden_pago, $cod_entidad_bancaria, $cuenta_bancaria, $numero_cheque, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);
            $j++;
		}



}//fin foreach

unset($partidas);
unset($partida);
unset($partidaX);
unset($cp);

	$this->layout = "ajax";
    $this->set('pagina',    null);
 	$this->set('siguiente', 'rendiciones_cuentas');
	$this->render('vista_index');


}//fin function anulacion_pagado







function rendiciones_cuentas($var=null){
$nco = $this->Session->read('numero_compromiso');
$nca = $this->Session->read('numero_causado');
$npa = $this->Session->read('numero_pagado');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }

set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;

$numero_causado = 0;
$numero_control_pagado = 0;

porcentaje_barra(0, 100, "Rendiciones de cuentas", 1);



$partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT * FROM cfpd30_rendiciones_partidas a,cfpd30_rendiciones_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_rendicion=b.ano_rendicion and
a.numero_rendicion=b.numero_rendicion and a.ano_rendicion=".$ano_select);



$j = 1;
$ano = YEAR_REACTUALIZACION;
$suma = count($partidas);
	    $total_suma = count($partidas);
	    $contar_proceso = 0;


							foreach($partidas as $partida){

                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}



		  $cod_presi              = $partida[0]['cod_presi'];
		  $cod_entidad            = $partida[0]['cod_entidad'];
		  $cod_tipo_inst          = $partida[0]['cod_tipo_inst'];
		  $cod_inst               = $partida[0]['cod_inst'];
		  $cod_dep                = $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento          = $partida[0]['ano_rendicion'];
				  $fr             = $partida[0]['fecha_proceso_registro'];
				  $fecha_registro = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd             = $partida[0]['fecha_rendicion'];
				  $ano_fd         = $fd[0].$fd[1].$fd[2].$fd[3];
				  $fd             = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd         = (int) $ano_fd;
				  $ano_fdpartida  = (int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $numero_documento       = $partida[0]['numero_rendicion'];
		  $ano                    = $partida[0]['ano'];
		  $cod_sector             = $partida[0]['cod_sector'];
		  $cod_programa           = $partida[0]['cod_programa'];
		  $cod_sub_prog           = $partida[0]['cod_sub_prog'];
		  $cod_proyecto           = $partida[0]['cod_proyecto'];
		  $cod_activ_obra         = $partida[0]['cod_activ_obra'];
		  $cod_partida            = $partida[0]['cod_partida'];
		  $cod_generica           = $partida[0]['cod_generica'];
		  $cod_especifica         = $partida[0]['cod_especifica'];
		  $cod_sub_espec          = $partida[0]['cod_sub_espec'];
		  $cod_auxiliar           = $partida[0]['cod_auxiliar'];
		  $monto                  = $partida[0]['monto'];
		  $concepto               = $partida[0]['concepto'];


											$nco++;
											$numero_compromiso=$nco;
										if($numero_compromiso==1){
											$sql_up="INSERT INTO cfpd21_numero_asiento_compromiso VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_compromiso');";
											$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
		     								}else{
												$sql_up="UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso=$numero_compromiso WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_compromiso=$ano";
												$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
												}
						 					$this->Session->write('numero_compromiso', $nco);


	      $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	      $to   = 1;
	      $td   = 3;
	      $ta   = 6;
	      $mt   = $monto;
	      $ccp  = str_replace("'","",$concepto);


		  $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);


           			$nca++;
					$numero_causado=$nca;
				if($numero_causado==1){
					$sql_up="INSERT INTO cfpd22_numero_asiento_causado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_causado');";
					$this->cfpd22_numero_asiento_causado->execute($sql_up);
		     		}else{
					$sql_up="UPDATE cfpd22_numero_asiento_causado SET numero_causado=$numero_causado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_causado=$ano";
					$this->cfpd22_numero_asiento_causado->execute($sql_up);
						}
							$this->Session->write('numero_causado', $nca);

              $to   = 1;
              $td   = 4;
              $ta   = 9;

              $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);


							$npa++;
							$numero_pagado=$npa;
						if($numero_pagado==1){
						   $sql_up="INSERT INTO cfpd23_numero_asiento_pagado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_pagado');";
						   $this->cfpd23_numero_asiento_pagado->execute($sql_up);
		     			   }else{
								$sql_up="UPDATE cfpd23_numero_asiento_pagado SET numero_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_pagado=$ano";
								$this->cfpd23_numero_asiento_pagado->execute($sql_up);
								}
									$this->Session->write('numero_pagado', $npa);

				                $to = 1;
				                $td = 5;
				                $ta = 3;

				                $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, null, null, $opfecha=$fecha_documento, $cod_entidad_bancaria=0, $cuenta_bancaria=0, $numero_documento, $fechache=$fecha_documento, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);


				                $sql_up_notadebito_partidas = "UPDATE cfpd30_rendiciones_partidas SET numero_control_compromiso=$numero_compromiso, numero_control_causado=$numero_causado ,numero_control_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_rendicion=$ano_documento and numero_rendicion=$numero_documento and ano=$ano and cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
				                $sw4 = $this->cepd01_compromiso_cuerpo->execute($sql_up_notadebito_partidas);

          		$j++;

        }//fin del foreach


unset($partidas);
unset($partida);
unset($cp);

	$this->layout = "ajax";
    $this->set('pagina',    null);
 	$this->set('siguiente', 'anulacion_rendiciones_cuentas');
	$this->render('vista_index');


}//fin function rendiciones_cuentas





function anulacion_rendiciones_cuentas($var=null){
$nco = $this->Session->read('numero_compromiso');
$nca = $this->Session->read('numero_causado');
$npa = $this->Session->read('numero_pagado');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }

set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Anulación Rendiciones de Cuentas", 1);


$partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT * FROM cfpd30_rendiciones_partidas a,cfpd30_rendiciones_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_rendicion=b.ano_rendicion and
a.numero_rendicion=b.numero_rendicion and b.condicion_actividad=2 and a.ano_rendicion=".$ano_select);


			 $j   = 1;
			 $ano = YEAR_REACTUALIZACION;

      						  $suma           = count($partidas);
	                          $total_suma     = count($partidas);
							  $contar_proceso = 0;


							foreach($partidas as $partida){


                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}



		  $cod_presi         = $partida[0]['cod_presi'];
		  $cod_entidad       = $partida[0]['cod_entidad'];
		  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
		  $cod_inst          = $partida[0]['cod_inst'];
		  $cod_dep           = $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento     = $partida[0]['ano_rendicion'];
	      $fr                = $partida[0]['fecha_proceso_anulacion'];
		  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
		  $fd                = $partida[0]['fecha_proceso_anulacion'];
		  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
		  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
	      $ano_fd            = (int) $ano_fd;
		  $ano_fdpartida     = (int) $partida[0]['ano'];

				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $numero_documento  = $partida[0]['numero_rendicion'];
		  $ano               = $partida[0]['ano'];
		  $cod_sector        = $partida[0]['cod_sector'];
		  $cod_programa      = $partida[0]['cod_programa'];
		  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
		  $cod_proyecto      = $partida[0]['cod_proyecto'];
		  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
		  $cod_partida       = $partida[0]['cod_partida'];
		  $cod_generica      = $partida[0]['cod_generica'];
		  $cod_especifica    = $partida[0]['cod_especifica'];
		  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
		  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
		  $monto             = $partida[0]['monto'];
		  $numero_compromiso = $partida[0]['numero_control_compromiso'];
		  $numero_causado    = $partida[0]['numero_control_causado'];
		  $numero_pagado     = $partida[0]['numero_control_pagado'];
          $numero_anulacion  = $partida[0]['numero_acta_anulacion'];
          $concepto          = $partida[0]['concepto'];

           $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and ano_documento=".$ano_select);
           $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];


           	  $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

			  $dnco = $this->motor_presupuestario($cp, 2, 3, 6, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_anulacion, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);


			  $dnco = $this->motor_presupuestario($cp, 2, 4, 9, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_anulacion, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);


			  $dnco = $this->motor_presupuestario($cp, 2, 5, 3, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_anulacion, null, $opfecha=$fd, $cod_entidad_bancaria=0, $cuenta_bancaria=0, $numero_documento, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);

	      $j++;
        }//fin del foreach


unset($partidas);
unset($partida);
unset($cp);

     	 $this->layout = "ajax";
     	 $this->set('pagina',    null);
 	     $this->set('siguiente', 'reintegro_presupuestario');
         $this->render('vista_index');

}//fin function anulacion_rendiciones_cuentas





function reintegro_presupuestario($var=null){
$nco = $this->Session->read('numero_compromiso');
$nca = $this->Session->read('numero_causado');
$npa = $this->Session->read('numero_pagado');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;


porcentaje_barra(0, 100, "Reintegro Presupuestario", 1);

  $numero_causado = 0;
  $numero_pagado  = 0;
  $partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT * FROM cfpd30_reintegro_partidas a,cfpd30_reintegro_cuerpo b
				WHERE
				a.cod_presi=b.cod_presi and
				a.cod_entidad=b.cod_entidad and
				a.cod_tipo_inst=b.cod_tipo_inst and
				a.cod_inst=b.cod_inst and
				a.cod_dep=b.cod_dep and
				a.ano_reintegro=b.ano_reintegro and
				a.numero_reintegro=b.numero_reintegro and a.ano_reintegro=".$ano_select);

			 $j    = 1;
			 $ano  = YEAR_REACTUALIZACION;
             $suma = count($partidas);

			                  $total_suma     = count($partidas);
							  $contar_proceso = 0;

							foreach($partidas as $partida){


                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}

				  $cod_presi        = $partida[0]['cod_presi'];
				  $cod_entidad      = $partida[0]['cod_entidad'];
				  $cod_tipo_inst    = $partida[0]['cod_tipo_inst'];
				  $cod_inst         = $partida[0]['cod_inst'];
				  $cod_dep          = $partida[0]['cod_dep'];
				  $this->Session->write('SScodpresi', $cod_presi);
				  $this->Session->write('SScodentidad', $cod_entidad);
				  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
				  $this->Session->write('SScodinst', $cod_inst);
				  $this->Session->write('SScoddep', $cod_dep);
				  $ano_documento    = $partida[0]['ano_reintegro'];
				  $fr               = $partida[0]['fecha_proceso_registro'];
				  $fecha_registro   = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd               = $partida[0]['fecha_reintegro'];
				  $ano_fd           = $fd[0].$fd[1].$fd[2].$fd[3];
				  $fd               = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd           = (int) $ano_fd;
				  $ano_fdpartida    = (int) $partida[0]['ano'];

				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

				  $numero_documento = $partida[0]['numero_reintegro'];
				  $ano              = $partida[0]['ano'];
				  $cod_sector       = $partida[0]['cod_sector'];
				  $cod_programa     = $partida[0]['cod_programa'];
				  $cod_sub_prog     = $partida[0]['cod_sub_prog'];
				  $cod_proyecto     = $partida[0]['cod_proyecto'];
				  $cod_activ_obra   = $partida[0]['cod_activ_obra'];
				  $cod_partida      = $partida[0]['cod_partida'];
				  $cod_generica     = $partida[0]['cod_generica'];
				  $cod_especifica   = $partida[0]['cod_especifica'];
				  $cod_sub_espec    = $partida[0]['cod_sub_espec'];
				  $cod_auxiliar     = $partida[0]['cod_auxiliar'];
				  $monto_pre        = $partida[0]['monto_pre_compromiso'];
				  $monto_com        = $partida[0]['monto_compromiso'];
				  $monto_cau        = $partida[0]['monto_causado'];
				  $monto_pag        = $partida[0]['monto_pagado'];
				  $concepto         = $partida[0]['concepto'];


                  $update_pre_compromiso = "UPDATE cfpd05 SET precompromiso_congelado=precompromiso_congelado - $monto_pre WHERE ".$this->condicion()." and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' ;";
		          $sw_pre = $this->cfpd05->execute($update_pre_compromiso);

											$nco++;
											$numero_compromiso=$nco;
										if($numero_compromiso==1){
											$sql_up="INSERT INTO cfpd21_numero_asiento_compromiso VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_compromiso');";
											$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
		     								}else{
												$sql_up="UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso=$numero_compromiso WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_compromiso=$ano";
												$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
												}
						 							$this->Session->write('numero_compromiso', $nco);



			      $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
			      $to   = 1;
			      $td   = 3;
			      $ta   = 7;
			      $ccp  = str_replace("'","",$concepto);

				  $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $monto_com, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);


		          		   $nca++;
						   $numero_causado=$nca;
						if($numero_causado==1){
						   $sql_up="INSERT INTO cfpd22_numero_asiento_causado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_causado');";
						   $this->cfpd22_numero_asiento_causado->execute($sql_up);
		     			   }else{
								$sql_up="UPDATE cfpd22_numero_asiento_causado SET numero_causado=$numero_causado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_causado=$ano";
								$this->cfpd22_numero_asiento_causado->execute($sql_up);
							}
									$this->Session->write('numero_causado', $nca);

		            	$to   = 1;
		            	$td   = 4;
		            	$ta   = 10;

						$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $monto_cau, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);

		                		$npa++;
								$numero_pagado=$npa;
							 if($numero_pagado==1){
						   		$sql_up="INSERT INTO cfpd23_numero_asiento_pagado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_pagado');";
						   		$this->cfpd23_numero_asiento_pagado->execute($sql_up);
		     			   		}else{
									$sql_up="UPDATE cfpd23_numero_asiento_pagado SET numero_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_pagado=$ano";
									$this->cfpd23_numero_asiento_pagado->execute($sql_up);
									}
									$this->Session->write('numero_pagado', $npa);

			            		$to = 1;
			            		$td = 5;
			            		$ta = 4;

			            		$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $monto_pag, $ccp, $ano, $numero_documento, null, null, $opfecha=$fecha_documento, $cbanco=null, $ccuenta=null, $ccheque=$numero_documento, $fechache=$fecha_documento, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);
		          $j++;

//echo "<script>alert('Partidas ".$cod_partida."   ".$cod_generica."  ".$cod_especifica."')</script>";


	}//fin del foreach

unset($partidas);
unset($partida);
unset($cp);

     	 $this->layout = "ajax";
     	 $this->set('pagina',    null);
 	     $this->set('siguiente', 'anulacion_reintegro_presupuestario');
         $this->render('vista_index');

}//fin function reintegro_presupuestario





function anulacion_reintegro_presupuestario($var=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;




porcentaje_barra(0, 100, "Reintegro anulación", 1);


  $partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT * FROM cfpd30_reintegro_partidas a,cfpd30_reintegro_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_reintegro=b.ano_reintegro and
a.numero_reintegro=b.numero_reintegro and b.condicion_actividad=2 and a.ano_reintegro=".$ano_select);

			 $j    = 1;
			 $ano  = YEAR_REACTUALIZACION;
             $suma = count($partidas);

			                  $total_suma     = count($partidas);
							  $contar_proceso = 0;

							foreach($partidas as $partida){



                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}



				  $cod_presi         = $partida[0]['cod_presi'];
				  $cod_entidad       = $partida[0]['cod_entidad'];
				  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
				  $cod_inst          = $partida[0]['cod_inst'];
				  $cod_dep           = $partida[0]['cod_dep'];
				  $this->Session->write('SScodpresi', $cod_presi);
				  $this->Session->write('SScodentidad', $cod_entidad);
				  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
				  $this->Session->write('SScodinst', $cod_inst);
				  $this->Session->write('SScoddep', $cod_dep);
				  $ano_documento     = $partida[0]['ano_reintegro'];
				  $fr                = $partida[0]['fecha_proceso_anulacion'];
				  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                = $partida[0]['fecha_proceso_anulacion'];
				  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
				  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd            = (int) $ano_fd;
				  $ano_fdpartida     = (int) $partida[0]['ano'];

				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

				  $numero_documento  = $partida[0]['numero_reintegro'];
				  $ano               = $partida[0]['ano'];
				  $cod_sector        = $partida[0]['cod_sector'];
				  $cod_programa      = $partida[0]['cod_programa'];
				  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
				  $cod_proyecto      = $partida[0]['cod_proyecto'];
				  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
				  $cod_partida       = $partida[0]['cod_partida'];
				  $cod_generica      = $partida[0]['cod_generica'];
				  $cod_especifica    = $partida[0]['cod_especifica'];
				  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
				  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
				  $monto_pre         = $partida[0]['monto_pre_compromiso'];
				  $monto_com         = $partida[0]['monto_compromiso'];
				  $monto_cau         = $partida[0]['monto_causado'];
				  $monto_pag         = $partida[0]['monto_pagado'];
				  $numero_compromiso = $partida[0]['numero_control_compromiso'];
		  		  $numero_causado    = $partida[0]['numero_control_causado'];
		  		  $numero_pagado     = $partida[0]['numero_control_pagado'];
		          $numero_anulacion  = $partida[0]['numero_acta_anulacion'];
		          $concepto          = $partida[0]['concepto'];

		          $update_pre_compromiso = "UPDATE cfpd05 SET precompromiso_congelado=precompromiso_congelado + $monto_pre WHERE ".$this->condicion()." and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' ;";
		          $sw_pre = $this->cfpd05->execute($update_pre_compromiso);

		          $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."'");
		          $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];


		          $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

				  $dnco = $this->motor_presupuestario($cp, 2, 3, 7, $fd, $monto_com, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_anulacion, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);

				  $dnco = $this->motor_presupuestario($cp, 2, 4, 10, $fd, $monto_cau, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_anulacion, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);

				  $dnco = $this->motor_presupuestario($cp, 2, 5, 4, $fd, $monto_pag, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_anulacion, null, $opfecha=$fd, $cod_entidad_bancaria=0, $cuenta_bancaria=0, $numero_documento, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);

			      $j++;
		        }//fin del foreach



unset($partidas);
unset($partida);
unset($cp);

     	 $this->layout = "ajax";
     	 $this->set('pagina',    null);
 	     $this->set('siguiente', 'nota_debito_especial');
         $this->render('vista_index');

}//fin function anulacion_reintegro_presupuestario






function nota_debito_especial($var=null){
$nco = $this->Session->read('numero_compromiso');
$nca = $this->Session->read('numero_causado');
$npa = $this->Session->read('numero_pagado');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;


porcentaje_barra(0, 100, "Nota de debito especial", 1);



$partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT a.*,b.numero_orden_pago,b.fecha_nota_debito,b.fecha_proceso_registro,b.concepto FROM cstd09_notadebito_especial_partidas a,cstd09_notadebito_especial_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_movimiento=b.ano_movimiento and
a.cod_entidad_bancaria=b.cod_entidad_bancaria and
a.cod_sucursal=b.cod_sucursal and
a.cuenta_bancaria=b.cuenta_bancaria and
a.tipo_documento=b.tipo_documento and
a.numero_documento=b.numero_documento and a.ano_movimiento=".$ano_select);


			 $j  = 0;
			 $ano =YEAR_REACTUALIZACION;

                           $suma = count($partidas);
	                       $total_suma     = count($partidas);
						   $contar_proceso = 0;
						   $veri_documento = 0;
						   $numero_causado = 0;
						   $numero_pagado  = 0;



			foreach($partidas as $partida){

                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}



		  $cod_presi            = $partida[0]['cod_presi'];
		  $cod_entidad          = $partida[0]['cod_entidad'];
		  $cod_tipo_inst        = $partida[0]['cod_tipo_inst'];
		  $cod_inst             = $partida[0]['cod_inst'];
		  $cod_dep              = $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento        = $partida[0]['ano_movimiento'];
	      $fr                   = $partida[0]['fecha_proceso_registro'];
		  $fecha_registro       = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
		  $fd                   = $partida[0]['fecha_nota_debito'];
	      $ano_fd               = $fd[0].$fd[1].$fd[2].$fd[3];
	      $fd                   = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_fd               = (int) $ano_fd;
		  $ano_fdpartida        = (int) $partida[0]['ano'];

				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $cod_entidad_bancaria = $partida[0]['cod_entidad_bancaria'];
		  $cod_sucursal         = $partida[0]['cod_sucursal'];
		  $cuenta_bancaria      = $partida[0]['cuenta_bancaria'];
		  $tipo_documento       = $partida[0]['tipo_documento'];
		  $numero_documento     = $partida[0]['numero_documento'];
		  $ano                  = $partida[0]['ano'];
		  $cod_sector           = $partida[0]['cod_sector'];
		  $cod_programa         = $partida[0]['cod_programa'];
		  $cod_sub_prog         = $partida[0]['cod_sub_prog'];
		  $cod_proyecto         = $partida[0]['cod_proyecto'];
		  $cod_activ_obra       = $partida[0]['cod_activ_obra'];
		  $cod_partida          = $partida[0]['cod_partida'];
		  $cod_generica         = $partida[0]['cod_generica'];
		  $cod_especifica       = $partida[0]['cod_especifica'];
		  $cod_sub_espec        = $partida[0]['cod_sub_espec'];
		  $cod_auxiliar         = $partida[0]['cod_auxiliar'];
		  $numero_compromiso    = $partida[0]['numero_control_compromiso'];
		  $numero_causado       = $partida[0]['numero_control_causado'];
		  $numero_pagado        = $partida[0]['numero_control_pagado'];
		  $monto                = $partida[0]['monto'];
		  $numero_orden_pago    = $partida[0]['numero_orden_pago'];
		  $concepto             = $partida[0]['concepto'];


			if ($numero_documento!=$veri_documento){

	      			if($numero_orden_pago!=0){

					    		$npa++;
								$numero_pagado=$npa;
							 if($numero_pagado==1){
						   		$sql_up="INSERT INTO cfpd23_numero_asiento_pagado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_pagado');";
						   		$this->cfpd23_numero_asiento_pagado->execute($sql_up);
		     			   		}else{
									$sql_up="UPDATE cfpd23_numero_asiento_pagado SET numero_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_pagado=$ano";
									$this->cfpd23_numero_asiento_pagado->execute($sql_up);
									}
									$this->Session->write('numero_pagado', $npa);


						       			$cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
							            $to   = 1;
							            $td   = 5;
							            $ta   = 2;
							            $mt   = $monto;
							            $ccp  = $concepto;

							            $c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
									    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

										$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $numero_documento, null, $opago=$numero_orden_pago, $opfecha=$fd, $cbanco=$cod_entidad_bancaria_aux, $ccuenta=$cuenta_bancaria, $ccheque=$numero_documento, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);

									        $sql_up_notadebito_partidas = "UPDATE cstd09_notadebito_especial_partidas SET numero_control_compromiso=$numero_compromiso , numero_control_causado=$numero_causado ,numero_control_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_movimiento=$ano_documento and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and tipo_documento=$tipo_documento and numero_documento=$numero_documento and ano=$ano and cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
									        $sw4 = $this->cepd01_compromiso_cuerpo->execute($sql_up_notadebito_partidas);

			      }else{

										  $nco++;
											$numero_compromiso=$nco;
										if($numero_compromiso==1){
											$sql_up="INSERT INTO cfpd21_numero_asiento_compromiso VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_compromiso');";
											$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
		     								}else{
												$sql_up="UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso=$numero_compromiso WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_compromiso=$ano";
												$this->cfpd21_numero_asiento_compromiso->execute($sql_up);
												}
						 							$this->Session->write('numero_compromiso', $nco);

								  		  $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
									      $to   = 1;
									      $td   = 3;
									      $ta   = 5;
									      $mt   = $monto;
									      $ccp  = str_replace("'","",$concepto);

										  $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j, null, null);

								            		$nca++;
						   							$numero_causado=$nca;
												 if($numero_causado==1){
						   							$sql_up="INSERT INTO cfpd22_numero_asiento_causado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_causado');";
						   							$this->cfpd22_numero_asiento_causado->execute($sql_up);
		     			   							}else{
														$sql_up="UPDATE cfpd22_numero_asiento_causado SET numero_causado=$numero_causado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_causado=$ano";
														$this->cfpd22_numero_asiento_causado->execute($sql_up);
														}
															$this->Session->write('numero_causado', $nca);

								              $to = 1;
								              $td = 4;
								              $ta = 8;

								              $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);

								               $npa++;
											   $numero_pagado=$npa;
							 			if($numero_pagado==1){
						   						$sql_up="INSERT INTO cfpd23_numero_asiento_pagado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_pagado');";
						   						$this->cfpd23_numero_asiento_pagado->execute($sql_up);
		     			   						}else{
													$sql_up="UPDATE cfpd23_numero_asiento_pagado SET numero_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_pagado=$ano";
													$this->cfpd23_numero_asiento_pagado->execute($sql_up);
													}
														$this->Session->write('numero_pagado', $npa);

									                $to = 1;
									                $td = 5;
									                $ta = 2;

													$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $numero_documento, null, $opago=$numero_orden_pago, $opfecha=$fd, $cbanco=$cod_entidad_bancaria, $ccuenta=$cuenta_bancaria, $ccheque=$numero_documento, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);

									                  $sql_up_notadebito_partidas = "UPDATE cstd09_notadebito_especial_partidas SET numero_control_compromiso=$numero_compromiso , numero_control_causado=$numero_causado ,numero_control_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_movimiento=$ano_documento and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and tipo_documento=$tipo_documento and numero_documento=$numero_documento and ano=$ano and cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
									                  $sw4 = $this->cepd01_compromiso_cuerpo->execute($sql_up_notadebito_partidas);
								          $j++;

								        }//fin orden de pago


									}// fin de documento

										$veri_documento=$numero_documento;


								}// fin del foreach

unset($partidas);
unset($partida);
unset($cp);

     	 $this->layout = "ajax";
     	 $this->set('pagina',    null);
 	     $this->set('siguiente', 'nota_debito');
         $this->render('vista_index');


}//fin function nota_debito_especial





function nota_debito($var=null){
$npa = $this->Session->read('numero_pagado');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Nota de debito ", 1);


							  $partidas=$this->cstd03_cheque_cuerpo->execute("select
											  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano_movimiento,
											  a.cod_entidad_bancaria,
											  a.cod_sucursal,
											  a.cuenta_bancaria,
											  a.numero_debito,
											  a.clase_orden,
											  a.ano_orden_pago,
											  a.numero_orden_pago,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar,
											  a.monto,
											  a.numero_control_compromiso,
											  a.numero_control_causado,
											  a.numero_control_pagado,
											  b.fecha_proceso_registro,
											  b.fecha_proceso_anulacion,
											  b.condicion_actividad,
											  b.concepto,
											  b.fecha_debito,
											  b.ano_anterior,
											  b.clase_beneficiario
											from
											  cstd09_notadebito_partidas a, cstd09_notadebito_cuerpo b
											where
											  a.cod_presi            = b.cod_presi and
											  a.cod_entidad          = b.cod_entidad and
											  a.cod_tipo_inst        = b.cod_tipo_inst and
											  a.cod_inst             = b.cod_inst and
											  a.cod_dep              = b.cod_dep and
											  a.ano_movimiento       = b.ano_movimiento and
											  a.cod_entidad_bancaria = b.cod_entidad_bancaria and
											  a.cod_sucursal         = b.cod_sucursal and
											  a.cuenta_bancaria      = b.cuenta_bancaria and
											  a.numero_debito        = b.numero_debito and a.ano_movimiento=".$ano_select);

							  	$suma = count($partidas);
							 		$total_suma     = count($partidas);
							 		$contar_proceso = 0;
							 		$veri_documento = 0;
							 		$j = 1;

				foreach($partidas as $partida){


                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}


							              $cod_presi            = $partida[0]['cod_presi'];
										  $cod_entidad          = $partida[0]['cod_entidad'];
										  $cod_tipo_inst        = $partida[0]['cod_tipo_inst'];
										  $cod_inst             = $partida[0]['cod_inst'];
										  $cod_dep              = $partida[0]['cod_dep'];
										  $this->Session->write('SScodpresi', $cod_presi);
										  $this->Session->write('SScodentidad', $cod_entidad);
										  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
										  $this->Session->write('SScodinst', $cod_inst);
										  $this->Session->write('SScoddep', $cod_dep);
										  $ano_anterior         = $partida[0]['ano_anterior'];
  										  $clase_beneficiario   = $partida[0]['clase_beneficiario'];
										  $ano_movimiento       = $partida[0]['ano_movimiento'];
										  $foo                  = $partida[0]['fecha_debito'];
										  $fd                   = $partida[0]['fecha_debito'];
										  $fd                   = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
										  $fdoo                 = $foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
										  $fecha_orden_pago     = $fdoo;
										  $cod_entidad_bancaria = $partida[0]['cod_entidad_bancaria'];
										  $cod_sucursal         = $partida[0]['cod_sucursal'];
										  $cuenta_bancaria      = $partida[0]['cuenta_bancaria'];
										  $numero_debito        = $partida[0]['numero_debito'];
										  $clase_orden          = $partida[0]['clase_orden'];
										  $ano_orden_pago       = $partida[0]['ano_orden_pago'];
										  $ano                  = $partida[0]['ano'];
										  $cod_sector           = $partida[0]['cod_sector'];
										  $cod_programa         = $partida[0]['cod_programa'];
										  $cod_sub_prog         = $partida[0]['cod_sub_prog'];
										  $cod_proyecto         = $partida[0]['cod_proyecto'];
										  $cod_activ_obra       = $partida[0]['cod_activ_obra'];
										  $cod_partida          = $partida[0]['cod_partida'];
										  $cod_generica         = $partida[0]['cod_generica'];
										  $cod_especifica       = $partida[0]['cod_especifica'];
										  $cod_sub_espec        = $partida[0]['cod_sub_espec'];
										  $cod_auxiliar         = $partida[0]['cod_auxiliar'];
										  $monto                = $partida[0]['monto'];
										  $numero_compromiso    = $partida[0]['numero_control_compromiso'];
										  $numero_causado       = $partida[0]['numero_control_causado'];
										  $numero_orden_pago    = $partida[0]['numero_orden_pago'];
										  $concepto             = $partida[0]['concepto'];

										  $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
										  $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";

												if ($numero_debito!=$veri_documento){
														$npa++;
											   			$numero_pagado=$npa;
							 						if($numero_pagado==1){
						   								$sql_up="INSERT INTO cfpd23_numero_asiento_pagado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_pagado');";
						   								$this->cfpd23_numero_asiento_pagado->execute($sql_up);
		     			   								}else{
															$sql_up="UPDATE cfpd23_numero_asiento_pagado SET numero_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_pagado=$ano";
															$this->cfpd23_numero_asiento_pagado->execute($sql_up);
															}
																$this->Session->write('numero_pagado', $npa);
													}
														$veri_documento=$numero_debito;

											$cp  = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
											$to  = 1;
											$td  = 5;
											$ta  = 1;
											$mt  = $monto;
											$ccp = str_replace("'","",$concepto);
											$c   = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);

											$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

                                            $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
										    $sql_actual = $condicion." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' ";

										       if($clase_beneficiario==1){


										 }else if($clase_beneficiario==2){

										 	$resul        = $this->cstd07_retenciones_cuerpo_islr->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'];

										 }else if($clase_beneficiario==3){

										 	$resul        = $this->cstd07_retenciones_cuerpo_timbre->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'];

										 }else if($clase_beneficiario==4){

										 	$resul        = $this->cstd07_retenciones_cuerpo_municipal->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'];

										 }else if($clase_beneficiario==5){

										 	$resul        = $this->cstd07_retenciones_cuerpo_iva->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'];

										 }//fin else

												if($ano_anterior==1){
												    $dnco = 0;
												}else{
													$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $numero_debito, null, $opago=$numero_orden_pago, $opfecha=$fecha_orden_pago, $cbanco=$cod_entidad_bancaria_aux, $ccuenta=$cuenta_bancaria, $ccheque=$numero_debito, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);
															$sql_cstd03_cheque_partidas = "UPDATE cstd09_notadebito_partidas SET numero_control_pagado='".$numero_pagado."' ";
										 					$sql_cstd03_cheque_partidas.= "WHERE  ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND numero_control_compromiso='".$numero_compromiso."' AND numero_control_causado='".$numero_causado."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
										 				$j++;
										 			}
										  					if($this->cstd03_cheque_partidas->execute($sql_cstd03_cheque_partidas)>=1){}else{$opcion = 'no';}//fin else

							  }//fin foreach


unset($partidas);
unset($partida);
unset($cp);

     	 $this->layout = "ajax";
     	 $this->set('pagina',    null);
 	     $this->set('siguiente', 'anulacion_nota_debito');
         $this->render('vista_index');


}//fin function nota_debito





function anulacion_nota_debito($var=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;


porcentaje_barra(0, 100, "Pagado cstd09 notadebito anulación ", 1);

								  $partidas=$this->cstd03_cheque_cuerpo->execute("select
												  a.cod_presi,
												  a.cod_entidad,
												  a.cod_tipo_inst,
												  a.cod_inst,
												  a.cod_dep,
												  a.ano_movimiento,
												  a.cod_entidad_bancaria,
												  a.cod_sucursal,
												  a.cuenta_bancaria,
												  a.numero_debito,
												  a.clase_orden,
												  a.ano_orden_pago,
												  a.numero_orden_pago,
												  a.ano,
												  a.cod_sector,
												  a.cod_programa,
												  a.cod_sub_prog,
												  a.cod_proyecto,
												  a.cod_activ_obra,
												  a.cod_partida,
												  a.cod_generica,
												  a.cod_especifica,
												  a.cod_sub_espec,
												  a.cod_auxiliar,
												  a.monto,
												  a.numero_control_compromiso,
												  a.numero_control_causado,
												  a.numero_control_pagado,
												  b.fecha_proceso_registro,
												  b.fecha_proceso_anulacion,
												  b.condicion_actividad,
												  b.numero_anulacion,
												  b.concepto,
												  b.fecha_debito,
												  a.numero_control_pagado,
												  b.ano_anterior,
											      b.clase_beneficiario
												from
												  cstd09_notadebito_partidas a, cstd09_notadebito_cuerpo b
												where
												  a.cod_presi            = b.cod_presi and
												  a.cod_entidad          = b.cod_entidad and
												  a.cod_tipo_inst        = b.cod_tipo_inst and
												  a.cod_inst             = b.cod_inst and
												  a.cod_dep              = b.cod_dep and
												  a.ano_movimiento       = b.ano_movimiento and
												  a.cod_entidad_bancaria = b.cod_entidad_bancaria and
												  a.cod_sucursal         = b.cod_sucursal and
												  a.cuenta_bancaria      = b.cuenta_bancaria and
												  a.numero_debito        = b.numero_debito and
												  b.condicion_actividad  = 2 and a.ano_movimiento=".$ano_select);


								$x=0;
								$j=0;


							  $total_suma     = count($partidas);
							  $contar_proceso = 0;



				foreach($partidas as $partida){



                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}



												  $cod_presi            = $partida[0]['cod_presi'];
												  $cod_entidad          = $partida[0]['cod_entidad'];
												  $cod_tipo_inst        = $partida[0]['cod_tipo_inst'];
												  $cod_inst             = $partida[0]['cod_inst'];
												  $cod_dep              = $partida[0]['cod_dep'];

												  $this->Session->write('SScodpresi', $cod_presi);
												  $this->Session->write('SScodentidad', $cod_entidad);
												  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
												  $this->Session->write('SScodinst', $cod_inst);
												  $this->Session->write('SScoddep', $cod_dep);

												  $ano_anterior         = $partida[0]['ano_anterior'];
				  								  $clase_beneficiario   = $partida[0]['clase_beneficiario'];
												  $ano_movimiento       = $partida[0]['ano_movimiento'];
												  $foo                  = $partida[0]['fecha_proceso_anulacion'];
												  $fd                   = $partida[0]['fecha_proceso_anulacion'];
												  $fd					= $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
												  $fdoo					= $foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
												  $fecha_orden_pago	    = $fdoo;
												  $cod_entidad_bancaria = $partida[0]['cod_entidad_bancaria'];
												  $cod_sucursal         = $partida[0]['cod_sucursal'];
												  $cuenta_bancaria      = $partida[0]['cuenta_bancaria'];
												  $numero_debito        = $partida[0]['numero_debito'];
												  $clase_orden          = $partida[0]['clase_orden'];
												  $ano_orden_pago       = $partida[0]['ano_orden_pago'];
												  $numero_orden_pago    = $partida[0]['numero_orden_pago'];
												  $ano                  = $partida[0]['ano'];
												  $cod_sector           = $partida[0]['cod_sector'];
												  $cod_programa         = $partida[0]['cod_programa'];
												  $cod_sub_prog         = $partida[0]['cod_sub_prog'];
												  $cod_proyecto         = $partida[0]['cod_proyecto'];
												  $cod_activ_obra       = $partida[0]['cod_activ_obra'];
												  $cod_partida          = $partida[0]['cod_partida'];
												  $cod_generica         = $partida[0]['cod_generica'];
												  $cod_especifica       = $partida[0]['cod_especifica'];
												  $cod_sub_espec        = $partida[0]['cod_sub_espec'];
												  $cod_auxiliar         = $partida[0]['cod_auxiliar'];
												  $monto                = $partida[0]['monto'];
												  $numero_compromiso    = $partida[0]['numero_control_compromiso'];
												  $numero_causado       = $partida[0]['numero_control_causado'];
												  $numero_pagado        = $partida[0]['numero_control_pagado'];
												  $numero_anulacion     = $partida[0]['numero_anulacion'];

												  $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='251' and numero_documento='".$numero_debito."' and ano_acta_anulacion=".$ano_select);
												  $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

								                  	$sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
												  	$sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";
				                                  	$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

								            		$condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
										    		$sql_actual = $condicion." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' ";

										       if($clase_beneficiario==1){


										 }else if($clase_beneficiario==2){

										 	$resul        = $this->cstd07_retenciones_cuerpo_islr->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'];

										 }else if($clase_beneficiario==3){

										 	$resul        = $this->cstd07_retenciones_cuerpo_timbre->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'];

										 }else if($clase_beneficiario==4){

										 	$resul        = $this->cstd07_retenciones_cuerpo_municipal->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'];

										 }else if($clase_beneficiario==5){

										 	$resul        = $this->cstd07_retenciones_cuerpo_iva->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'];

										 }//fin else

												if($ano_anterior==1){
												    $dnco = 0;
												}else{
													$dnco = $this->motor_presupuestario($cp, 2, 5, 1, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_debito, $numero_anulacion, $numero_orden_pago, $opfecha=$fecha_orden_pago, $cod_entidad_bancaria, $cuenta_bancaria, $numero_debito, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, 'NOTADE');
													$j++;
													}
				}//fin foreach



unset($partidas);
unset($partida);
unset($cp);



     	 $this->layout = "ajax";
     	 $this->set('pagina',    null);
 	     $this->set('siguiente', 'nota_debito_no_orden');
         $this->render('vista_index');



}//fin function anulacion_nota_debito






function nota_debito_no_orden($var=null){
$npa = $this->Session->read('numero_pagado');
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;

porcentaje_barra(0, 100, "Nota de debito sin orden de pago ", 1);

							  $partidas=$this->cstd03_cheque_cuerpo->execute("select
											  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano_movimiento,
											  a.cod_entidad_bancaria,
											  a.cod_sucursal,
											  a.cuenta_bancaria,
											  a.numero_debito,
											  a.clase_orden,
											  a.ano_orden_pago,
											  a.numero_orden_pago,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar,
											  a.monto,
											  a.numero_control_compromiso,
											  a.numero_control_causado,
											  a.numero_control_pagado,
											  b.fecha_proceso_registro,
											  b.fecha_proceso_anulacion,
											  b.condicion_actividad,
											  b.concepto,
											  b.fecha_debito,
											  b.clase_beneficiario
											from
											  cstd30_debito_partidas a, cstd30_debito_cuerpo b
											where
											  a.cod_presi            = b.cod_presi and
											  a.cod_entidad          = b.cod_entidad and
											  a.cod_tipo_inst        = b.cod_tipo_inst and
											  a.cod_inst             = b.cod_inst and
											  a.cod_dep              = b.cod_dep and
											  a.ano_movimiento       = b.ano_movimiento and
											  a.cod_entidad_bancaria = b.cod_entidad_bancaria and
											  a.cod_sucursal         = b.cod_sucursal and
											  a.cuenta_bancaria      = b.cuenta_bancaria and
											  a.numero_debito        = b.numero_debito and a.ano_movimiento=".$ano_select);

							  	$suma = count($partidas);
							  	$j = 1;
							  				$total_suma     = count($partidas);
							  				$contar_proceso = 0;
							  				$veri_documento = 0;


							foreach($partidas as $partida){



                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}


							              $cod_presi            = $partida[0]['cod_presi'];
										  $cod_entidad          = $partida[0]['cod_entidad'];
										  $cod_tipo_inst        = $partida[0]['cod_tipo_inst'];
										  $cod_inst             = $partida[0]['cod_inst'];
										  $cod_dep              = $partida[0]['cod_dep'];
										  $this->Session->write('SScodpresi', $cod_presi);
										  $this->Session->write('SScodentidad', $cod_entidad);
										  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
										  $this->Session->write('SScodinst', $cod_inst);
										  $this->Session->write('SScoddep', $cod_dep);
										  $ano_movimiento       = $partida[0]['ano_movimiento'];
  										  $clase_beneficiario   = $partida[0]['clase_beneficiario'];
										  $foo                  = $partida[0]['fecha_debito'];
										  $fd                   = $partida[0]['fecha_debito'];
										  $fd                   = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
										  $fdoo                 = $foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
										  $fecha_orden_pago     = $fdoo;
										  $cod_entidad_bancaria = $partida[0]['cod_entidad_bancaria'];
										  $cod_sucursal         = $partida[0]['cod_sucursal'];
										  $cuenta_bancaria      = $partida[0]['cuenta_bancaria'];
										  $numero_debito        = $partida[0]['numero_debito'];
										  $clase_orden          = $partida[0]['clase_orden'];
										  $ano_orden_pago       = $partida[0]['ano_orden_pago'];
										  $ano                  = $partida[0]['ano'];
										  $cod_sector           = $partida[0]['cod_sector'];
										  $cod_programa         = $partida[0]['cod_programa'];
										  $cod_sub_prog         = $partida[0]['cod_sub_prog'];
										  $cod_proyecto         = $partida[0]['cod_proyecto'];
										  $cod_activ_obra       = $partida[0]['cod_activ_obra'];
										  $cod_partida          = $partida[0]['cod_partida'];
										  $cod_generica         = $partida[0]['cod_generica'];
										  $cod_especifica       = $partida[0]['cod_especifica'];
										  $cod_sub_espec        = $partida[0]['cod_sub_espec'];
										  $cod_auxiliar         = $partida[0]['cod_auxiliar'];
										  $monto                = $partida[0]['monto'];
										  $numero_compromiso    = $partida[0]['numero_control_compromiso'];
										  $numero_causado       = $partida[0]['numero_control_causado'];
										  $numero_orden_pago    = $partida[0]['numero_orden_pago'];
										  $concepto             = $partida[0]['concepto'];



										 $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
										 $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";



											if ($numero_debito!=$veri_documento){
														$npa++;
											   			$numero_pagado=$npa;
							 						if($numero_pagado==1){
						   								$sql_up="INSERT INTO cfpd23_numero_asiento_pagado VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$ano','$numero_pagado');";
						   								$this->cfpd23_numero_asiento_pagado->execute($sql_up);
		     			   								}else{
															$sql_up="UPDATE cfpd23_numero_asiento_pagado SET numero_pagado=$numero_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ano_pagado=$ano";
															$this->cfpd23_numero_asiento_pagado->execute($sql_up);
															}
																$this->Session->write('numero_pagado', $npa);
													}
														$veri_documento=$numero_debito;


											$cp  = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
											$to  = 1;
											$td  = 5;
											$ta  = 2;
											$mt  = $monto;
											$ccp = str_replace("'","",$concepto);
											$c   = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
											$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];


                                            $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
										    $sql_actual = $condicion." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' ";

										       if($clase_beneficiario==1){

                                             $ano_anterior = 2;

										 }else if($clase_beneficiario==2){

										 	$resul        = $this->cstd07_retenciones_cuerpo_islr->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'];

										 }else if($clase_beneficiario==3){

										 	$resul        = $this->cstd07_retenciones_cuerpo_timbre->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'];

										 }else if($clase_beneficiario==4){

										 	$resul        = $this->cstd07_retenciones_cuerpo_municipal->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'];

										 }else if($clase_beneficiario==5){

										 	$resul        = $this->cstd07_retenciones_cuerpo_iva->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'];

										 }//fin else

												if($ano_anterior==1){
												    $dnco = 0;
												}else{
													$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano, $numero_debito, null, $opago=$numero_orden_pago, $opfecha=$fecha_orden_pago, $cbanco=$cod_entidad_bancaria_aux, $ccuenta=$cuenta_bancaria, $ccheque=$numero_debito, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);
											         	$sql_cstd03_cheque_partidas = "UPDATE cstd30_debito_partidas SET numero_control_pagado='".$numero_pagado."' ";
										 				$sql_cstd03_cheque_partidas.= "WHERE  ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND numero_control_compromiso='".$numero_compromiso."' AND numero_control_causado='".$numero_causado."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
													$j++;
												}

										  if($this->cstd03_cheque_partidas->execute($sql_cstd03_cheque_partidas)>=1){}else{$opcion = 'no';}//fin else
							  }//fin foreachsss


unset($partidas);
unset($partida);
unset($cp);



     	 $this->layout = "ajax";
     	 $this->set('pagina',    null);
 	     $this->set('siguiente', 'anulacion_nota_debito_no_orden');
         $this->render('vista_index');



}//fin function nota_debito_no_orden





function anulacion_nota_debito_no_orden($var=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;


porcentaje_barra(0, 100, "Anulación Nota de Debito sin Orden de Pago", 1);



								  $partidas=$this->cstd03_cheque_cuerpo->execute("select
												  a.cod_presi,
												  a.cod_entidad,
												  a.cod_tipo_inst,
												  a.cod_inst,
												  a.cod_dep,
												  a.ano_movimiento,
												  a.cod_entidad_bancaria,
												  a.cod_sucursal,
												  a.cuenta_bancaria,
												  a.numero_debito,
												  a.clase_orden,
												  a.ano_orden_pago,
												  a.numero_orden_pago,
												  a.ano,
												  a.cod_sector,
												  a.cod_programa,
												  a.cod_sub_prog,
												  a.cod_proyecto,
												  a.cod_activ_obra,
												  a.cod_partida,
												  a.cod_generica,
												  a.cod_especifica,
												  a.cod_sub_espec,
												  a.cod_auxiliar,
												  a.monto,
												  a.numero_control_compromiso,
												  a.numero_control_causado,
												  a.numero_control_pagado,
												  b.fecha_proceso_registro,
												  b.fecha_proceso_anulacion,
												  b.condicion_actividad,
												  b.numero_anulacion,
												  b.concepto,
												  b.fecha_debito,
												  a.numero_control_pagado,
											      b.clase_beneficiario
												from
												  cstd30_debito_partidas a, cstd30_debito_cuerpo b
												where
												  a.cod_presi            = b.cod_presi and
												  a.cod_entidad          = b.cod_entidad and
												  a.cod_tipo_inst        = b.cod_tipo_inst and
												  a.cod_inst             = b.cod_inst and
												  a.cod_dep              = b.cod_dep and
												  a.ano_movimiento       = b.ano_movimiento and
												  a.cod_entidad_bancaria = b.cod_entidad_bancaria and
												  a.cod_sucursal         = b.cod_sucursal and
												  a.cuenta_bancaria      = b.cuenta_bancaria and
												  a.numero_debito        = b.numero_debito and
												  b.condicion_actividad  = 2 and a.ano_movimiento=".$ano_select);


							  $j = 0;
							  $total_suma     = count($partidas);
							  $contar_proceso = 0;

				foreach($partidas as $partida){



                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}




								  $cod_presi            = $partida[0]['cod_presi'];
								  $cod_entidad          = $partida[0]['cod_entidad'];
								  $cod_tipo_inst        = $partida[0]['cod_tipo_inst'];
								  $cod_inst             = $partida[0]['cod_inst'];
								  $cod_dep              = $partida[0]['cod_dep'];
								  $this->Session->write('SScodpresi', $cod_presi);
								  $this->Session->write('SScodentidad', $cod_entidad);
								  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
								  $this->Session->write('SScodinst', $cod_inst);
								  $this->Session->write('SScoddep', $cod_dep);
								  $ano_movimiento       = $partida[0]['ano_movimiento'];
  							      $clase_beneficiario   = $partida[0]['clase_beneficiario'];
								  $foo                  = $partida[0]['fecha_proceso_anulacion'];
								  $fd                   = $partida[0]['fecha_proceso_anulacion'];
								  $fd					= $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
								  $fdoo					= $foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
								  $fecha_orden_pago	    = $fdoo;
								  $cod_entidad_bancaria = $partida[0]['cod_entidad_bancaria'];
								  $cod_sucursal         = $partida[0]['cod_sucursal'];
								  $cuenta_bancaria      = $partida[0]['cuenta_bancaria'];
								  $numero_debito        = $partida[0]['numero_debito'];
								  $clase_orden          = $partida[0]['clase_orden'];
								  $ano_orden_pago       = $partida[0]['ano_orden_pago'];
								  $ano                  = $partida[0]['ano'];
								  $cod_sector           = $partida[0]['cod_sector'];
								  $cod_programa         = $partida[0]['cod_programa'];
								  $cod_sub_prog         = $partida[0]['cod_sub_prog'];
								  $cod_proyecto         = $partida[0]['cod_proyecto'];
								  $cod_activ_obra       = $partida[0]['cod_activ_obra'];
								  $cod_partida          = $partida[0]['cod_partida'];
								  $cod_generica         = $partida[0]['cod_generica'];
								  $cod_especifica       = $partida[0]['cod_especifica'];
								  $cod_sub_espec        = $partida[0]['cod_sub_espec'];
								  $cod_auxiliar         = $partida[0]['cod_auxiliar'];
								  $monto                = $partida[0]['monto'];
								  $numero_compromiso    = $partida[0]['numero_control_compromiso'];
								  $numero_causado       = $partida[0]['numero_control_causado'];
								  $numero_pagado        = $partida[0]['numero_control_pagado'];
								  $numero_orden_pago    = $partida[0]['numero_orden_pago'];
								  $numero_anulacion     = $partida[0]['numero_anulacion'];


								  $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='251' and numero_documento='".$numero_debito."' and ano_acta_anulacion=".$ano_select);
								  $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
				                  $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
								  $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";
                                  $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);


								  $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
								  $sql_actual = $condicion." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' ";

										       if($clase_beneficiario==1){

                                            $ano_anterior = 2;

										 }else if($clase_beneficiario==2){

										 	$resul        = $this->cstd07_retenciones_cuerpo_islr->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'];

										 }else if($clase_beneficiario==3){

										 	$resul        = $this->cstd07_retenciones_cuerpo_timbre->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'];

										 }else if($clase_beneficiario==4){

										 	$resul        = $this->cstd07_retenciones_cuerpo_municipal->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'];

										 }else if($clase_beneficiario==5){

										 	$resul        = $this->cstd07_retenciones_cuerpo_iva->findAll($sql_actual);
										    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'];

										 }//fin else

												if($ano_anterior==1){
												    $dnco = 0;
												}else{
													$dnco = $this->motor_presupuestario($cp, 2, 5, 2, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_debito, $numero_anulacion, $numero_orden_pago, $opfecha=$fecha_orden_pago, $cod_entidad_bancaria, $cuenta_bancaria, $numero_debito, $fechache=$fd, $numero_compromiso, $numero_causado, $numero_pagado, null, $j, null, null);
												$j++;
													}

				}//fin foreach


unset($partidas);
unset($partida);
unset($cp);



if($var!=null){  $this->set('mensaje', "Fin de la reactualizacion");
		 $this->layout = "ajax";
     	 $this->set('pagina',    null);
 	     $this->set('siguiente', 'reformulacion');
         $this->render('vista_index');
}else{
     	 $this->layout = "ajax";
     	 $this->set('pagina',    null);
 	     $this->set('siguiente', 'reformulacion');
         $this->render('vista_index');
}//fin if



}//fin function anulacion_nota_debito_no_orden




function reformulacion($var_opcion=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
$ano_select = YEAR_REACTUALIZACION;
set_time_limit(0);

$contar_proceso = 0;

porcentaje_barra($contar_proceso, 100, "Reformulación", 1);

     $this->cfpd20->execute("delete from cfpd20 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);
     $this->cfpd05->execute("update cfpd05 set rebaja_anual=0, rebaja_ene=0, rebaja_feb=0, rebaja_mar=0, rebaja_abr=0, rebaja_may=0, rebaja_jun=0, rebaja_jul=0, rebaja_ago=0, rebaja_sep=0, rebaja_oct=0, rebaja_nov=0, rebaja_dic=0 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);
     $this->cfpd05->execute("update cfpd05 set disminucion_traslado_anual=0, disminucion_traslado_ene=0, disminucion_traslado_feb=0, disminucion_traslado_mar=0, disminucion_traslado_abr=0, disminucion_traslado_may=0, disminucion_traslado_jun=0, disminucion_traslado_jul=0, disminucion_traslado_ago=0, disminucion_traslado_sep=0, disminucion_traslado_oct=0, disminucion_traslado_nov=0, disminucion_traslado_dic=0 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);
     $this->cfpd05->execute("update cfpd05 set aumento_traslado_anual=0, aumento_traslado_ene=0, aumento_traslado_feb=0, aumento_traslado_mar=0, aumento_traslado_abr=0, aumento_traslado_may=0, aumento_traslado_jun=0, aumento_traslado_jul=0, aumento_traslado_ago=0, aumento_traslado_sep=0, aumento_traslado_oct=0, aumento_traslado_nov=0, aumento_traslado_dic=0 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);
     $this->cfpd05->execute("update cfpd05 set credito_adicional_anual=0, credito_adicional_ene=0, credito_adicional_feb=0, credito_adicional_mar=0, credito_adicional_abr=0, credito_adicional_may=0, credito_adicional_jun=0, credito_adicional_jul=0, credito_adicional_ago=0, credito_adicional_sep=0, credito_adicional_oct=0, credito_adicional_nov=0, credito_adicional_dic=0 where ".$this->SQLCA_noDEP()." and ano=".$ano_select);


$RESUTADO_REFOR="SELECT
  a.cod_presi,a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.codi_dep,
  a.ano_reformulacion,a.numero_oficio,a.codi_dep,a.ano,a.cod_sector, a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,
  a.monto_disminucion,a.monto_aumento,b.fecha_decreto,b.numero_decreto,b.cod_tipo,b.fecha_oficio
FROM
  cfpd10_reformulacion_partidas a,cfpd10_reformulacion_texto b
WHERE
  upper(a.numero_oficio)=upper(b.numero_oficio)  and
  a.cod_presi=b.cod_presi and
  a.cod_entidad=b.cod_entidad and
  a.cod_tipo_inst=b.cod_tipo_inst and
  a.cod_inst=b.cod_inst and
  a.cod_dep=b.cod_dep and a.ano_reformulacion=b.ano_reformulacion and a.ano_reformulacion=".$ano_select;



   $DATA=$this->cfpd10_reformulacion_partidas->execute($RESUTADO_REFOR);

                              $total_suma     = count($DATA);

							foreach($DATA as $partida){



                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}



									  $cod_presi         = $partida[0]['cod_presi'];
									  $cod_entidad       = $partida[0]['cod_entidad'];
									  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
									  $cod_inst          = $partida[0]['cod_inst'];
									  $cod_dep           = $partida[0]['codi_dep'];
									  $this->Session->write('SScodpresi', $cod_presi);
									  $this->Session->write('SScodentidad', $cod_entidad);
									  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
									  $this->Session->write('SScodinst', $cod_inst);
									  $this->Session->write('SScoddep', $cod_dep);
									  $ano_documento     = $partida[0]['ano_reformulacion'];
									  $fr                = $partida[0]['fecha_decreto'];
									  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
									  $fd                = $partida[0]['fecha_decreto'];//0123-56-89
									  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
									  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
									  $ano_fd            = (int) $ano_fd;
									  $ano_fdpartida     = (int) $partida[0]['ano'];
											  if($ano_fd!=$ano_fdpartida){
											     $fecha_documento=$fecha_registro; $fd=$fecha_registro;
											  }else{
											  	$fecha_documento=$fd;
											  }


											 // porcentaje_barra($contar_proceso, 100, "Reformulación con Mes: ".$fd."", 1);



									  $numero_documento = $partida[0]['numero_oficio'];
									  $numero_decreto   = $partida[0]['numero_decreto'];
									  $ano              = $partida[0]['ano'];
									  $cod_sector       = $partida[0]['cod_sector'];
									  $cod_programa     = $partida[0]['cod_programa'];
									  $cod_sub_prog     = $partida[0]['cod_sub_prog'];
									  $cod_proyecto     = $partida[0]['cod_proyecto'];
									  $cod_activ_obra   = $partida[0]['cod_activ_obra'];
									  $cod_partida      = $partida[0]['cod_partida'];
									  $cod_generica     = $partida[0]['cod_generica'];
									  $cod_especifica   = $partida[0]['cod_especifica'];
									  $cod_sub_espec    = $partida[0]['cod_sub_espec'];
									  $cod_auxiliar     = $partida[0]['cod_auxiliar'];
									  $monto_disminuir  = $partida[0]['monto_disminucion'];
									  $monto_aumento    = $partida[0]['monto_aumento'];
									  $codigo= $partida[0]['cod_tipo'];


									 if($codigo==1 && $monto_disminuir!=0){
                                           $cp  = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
										   $to  = 1;
										   $td  = 1;
										   $ta  = 2;
										   $mt  = $monto_disminuir;
										   $ccp = '';
										   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano_documento, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);
											if($dnco != false){
												$numero_control_compromiso=$dnco;
											}else{
												$numero_control_compromiso=null;
												break;
											}
									}
									if($codigo==1 && $monto_aumento!=0){
                                           $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
										   $to   = 1;
										   $td   = 1;
										   $ta   = 1;
										   $mt   = $monto_aumento;
										   $ccp  = '';
										   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano_documento, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);
											if($dnco != false){
												$numero_control_compromiso=$dnco;
											}else{
												$numero_control_compromiso=null;
												break;
											}
									}///cod_tipo=1

									if($codigo==2 && $monto_aumento!=0){
                                           $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
										   $to   = 1;
										   $td   = 1;
										   $ta   = 3;
										   $mt   = $monto_aumento;
										   $ccp  = '';
										   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano_documento, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);
											if($dnco != false){
												$numero_control_compromiso=$dnco;
											}else{
												$numero_control_compromiso=null;
												break;
											}
									}///cod_tipo=1

									if($codigo==3 && $monto_disminuir!=0){
                                       $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
									   $to   = 1;
									   $td   = 1;
									   $ta   = 4;
									   $mt   = $monto_disminuir;
									   $ccp  = '';
									   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano_documento, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);
										if($dnco != false){
											$numero_control_compromiso=$dnco;
										}else{
											$numero_control_compromiso=null;
											break;
										}
									}
									if($codigo==4 && $monto_aumento!=0){
										$cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
										$to   = 1;
										$td   = 1;
										$ta   = 5;
										$mt   = $this->Formato1($monto_aumento);
										$ccp  = '';
										$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano_documento, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);
										if($dnco != false){
											$numero_control_compromiso=$dnco;
										}else{
											$numero_control_compromiso=null;
											break;
										}
									}

										if($codigo==5 && $monto_aumento!=0){
										$cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
										$to   = 1;
										$td   = 1;
										$ta   = 6;
										$mt   = $this->Formato1($monto_aumento);
										$ccp  = '';
										$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ano_documento, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);
										if($dnco != false){
											$numero_control_compromiso=$dnco;
										}else{
											$numero_control_compromiso=null;
											break;
										}
									}





   }//fin foreach


//////////////////////////////////////////////////////////////////////////////////////


porcentaje_barra(0, 100, "Reformulación pre-compromiso", 1);

$RESUTADO_REFOR_temp="SELECT *
FROM
  cfpd10_reformulacion_partidas_tmp a
WHERE
  a.ano_reformulacion=".$ano_select;

   $DATA_TEMP=$this->cfpd10_reformulacion_partidas->execute($RESUTADO_REFOR_temp);

                              $total_suma     = count($DATA_TEMP);
							  $contar_proceso = 0;

							foreach($DATA_TEMP as $partida){



                                $contar_proceso++;

                                if ($contar_proceso%1000 == 0){porcentaje_barra($contar_proceso, $total_suma);}


									  $cod_presi       = $partida[0]['cod_presi'];
									  $cod_entidad     = $partida[0]['cod_entidad'];
									  $cod_tipo_inst   = $partida[0]['cod_tipo_inst'];
									  $cod_inst        = $partida[0]['cod_inst'];
									  $cod_dep         = $partida[0]['codi_dep'];
									  $ano             = $partida[0]['ano'];
									  $cod_sector      = $partida[0]['cod_sector'];
									  $cod_programa    = $partida[0]['cod_programa'];
									  $cod_sub_prog    = $partida[0]['cod_sub_prog'];
									  $cod_proyecto    = $partida[0]['cod_proyecto'];
									  $cod_activ_obra  = $partida[0]['cod_activ_obra'];
									  $cod_partida     = $partida[0]['cod_partida'];
									  $cod_generica    = $partida[0]['cod_generica'];
									  $cod_especifica  = $partida[0]['cod_especifica'];
									  $cod_sub_espec   = $partida[0]['cod_sub_espec'];
									  $cod_auxiliar    = $partida[0]['cod_auxiliar'];
									  $monto_disminuir = $partida[0]['monto_disminucion'];
									 if($monto_disminuir!=0){
                                           $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
										   $this->cfpd05->execute("UPDATE cfpd05 SET precompromiso_congelado=precompromiso_congelado+".$monto_disminuir." WHERE cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar);
									 }


   }//fin foreach


if($var_opcion!=null){}else{
     	 $this->layout = "ajax";
     	 $this->set('mensaje', 'La reactualización fue realizada');
         $this->set('termino', true);
         $this->render('vista_index');
}//fin if


}//fin function reformulacion




































function reactualizacion_relacion_obras($var_opcion=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
set_time_limit(0);


porcentaje_barra(0, 100, "Relacion de Obras", 1);

$ano_select = YEAR_REACTUALIZACION;
$monto_total = 0;

	$this->cugd04->execute("UPDATE cfpd05 SET precompromiso_obras=0 WHERE ".$this->condicionNDEP_script()." and ano=".$ano_select);

	$sql_1 = "update cfpd07_obras_cuerpo set estimado_presu=0, monto_contratado=0, aumento_obras=0, disminucion_obras=0 where ".$this->condicionNDEP_script()." and ano_estimacion=".$ano_select." and punto_operacion=1";
	$this->cfpd07_obras_cuerpo->execute($sql_1);


		// ******** RELACION DE OBRAS ************


	$datos_obras = $this->cfpd07_obras_cuerpo->findAll($this->condicionNDEP_script()." and ano_estimacion=".$ano_select." and punto_operacion=1");

	foreach($datos_obras as $row_datos_obras){

		$estimado_presup = 0;
		$aumento_obrasp = 0;
		$disminucion_obrasp = 0;

		$cod_presi      = $row_datos_obras['cfpd07_obras_cuerpo']['cod_presi'];
		$cod_entidad    = $row_datos_obras['cfpd07_obras_cuerpo']['cod_entidad'];
		$cod_tipo_inst  = $row_datos_obras['cfpd07_obras_cuerpo']['cod_tipo_inst'];
		$cod_inst       = $row_datos_obras['cfpd07_obras_cuerpo']['cod_inst'];
		$cod_dep        = $row_datos_obras['cfpd07_obras_cuerpo']['cod_dep'];
		$ano_estimacion = $row_datos_obras['cfpd07_obras_cuerpo']['ano_estimacion'];
		$cod_obra       = $row_datos_obras['cfpd07_obras_cuerpo']['cod_obra'];

		$datos_obras_partidas_re = $this->cfpd07_obras_partidas->findAll("cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano_estimacion." and cod_obra=".$cod_obra);
		foreach($datos_obras_partidas_re as $datos_obras_partidas){
		$cod_sector        = $datos_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
		$cod_programa      = $datos_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
		$cod_sub_prog      = $datos_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
		$cod_proyecto      = $datos_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
		$cod_activ_obra    = $datos_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
		$cod_partida       = $datos_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
		$cod_generica      = $datos_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
		$cod_especifica    = $datos_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
		$cod_sub_espec     = $datos_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
		$cod_auxiliar      = $datos_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
		$monto             = $datos_obras_partidas['cfpd07_obras_partidas']['monto'];
		$aumento_obras     = $datos_obras_partidas['cfpd07_obras_partidas']['aumento_obras'];
		$disminucion_obras = $datos_obras_partidas['cfpd07_obras_partidas']['disminucion_obras'];

		$estimado_presup += $monto;
		$aumento_obrasp += $aumento_obras;
		$disminucion_obrasp += $disminucion_obras;
		$monto_total = (($estimado_presup+$aumento_obras)-$disminucion_obras);

		$sql_2 = "update cfpd07_obras_partidas set monto_contratado=0 where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano_estimacion." and cod_obra=".$cod_obra." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
		$sql_3 = "update cfpd05 set precompromiso_obras = precompromiso_obras+$monto_total where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano_estimacion." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;

		$this->cfpd07_obras_partidas->execute($sql_2." ".$sql_3);

		}// fin foreach interno relacion de obras

		$sql_4 = "update cfpd07_obras_cuerpo set estimado_presu=$estimado_presup, aumento_obras=$aumento_obrasp, disminucion_obras=$disminucion_obrasp where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano_estimacion." and cod_obra=".$cod_obra;
		$this->cfpd07_obras_cuerpo->execute($sql_4);

	} // fin foreach externo relacion de obras


				// ******** ORDENES DE COMPRAS ************

	$monto_total = 0;
	$datos_compras = $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP_script()." and ano_orden_compra=".$ano_select." and condicion_actividad=1 and cod_obra IS NOT NULL");

	foreach($datos_compras as $row_datos_compras){

		$monto_orden    = 0;
		$aumento_oc     = 0;
		$disminucion_oc = 0;

		$cod_presi           = $row_datos_compras['cscd04_ordencompra_encabezado']['cod_presi'];
		$cod_entidad         = $row_datos_compras['cscd04_ordencompra_encabezado']['cod_entidad'];
		$cod_tipo_inst       = $row_datos_compras['cscd04_ordencompra_encabezado']['cod_tipo_inst'];
		$cod_inst            = $row_datos_compras['cscd04_ordencompra_encabezado']['cod_inst'];
		$cod_dep             = $row_datos_compras['cscd04_ordencompra_encabezado']['cod_dep'];
		$ano_estimacion      = $row_datos_compras['cscd04_ordencompra_encabezado']['ano_orden_compra'];
		$numero_orden_compra = $row_datos_compras['cscd04_ordencompra_encabezado']['numero_orden_compra'];
		$cod_obra            = $row_datos_compras['cscd04_ordencompra_encabezado']['cod_obra'];

		$datos_compras_partidas_re = $this->cscd04_ordencompra_partidas->findAll("cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_orden_compra=".$ano_estimacion." and numero_orden_compra=".$numero_orden_compra);
		foreach($datos_compras_partidas_re as $datos_compras_partidas){
		$cod_sector     = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_sector'];
		$cod_programa   = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_programa'];
		$cod_sub_prog   = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_sub_prog'];
		$cod_proyecto   = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_proyecto'];
		$cod_activ_obra = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_activ_obra'];
		$cod_partida    = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_partida'];
		$cod_generica   = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_generica'];
		$cod_especifica = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_especifica'];
		$cod_sub_espec  = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_sub_espec'];
		$cod_auxiliar   = $datos_compras_partidas['cscd04_ordencompra_partidas']['cod_auxiliar'];
		$monto          = $datos_compras_partidas['cscd04_ordencompra_partidas']['monto'];
		$aumento        = $datos_compras_partidas['cscd04_ordencompra_partidas']['aumento'];
		$disminucion    = $datos_compras_partidas['cscd04_ordencompra_partidas']['disminucion'];

		$monto_orden += $monto;
		$aumento_oc += $aumento;
		$disminucion_oc += $disminucion;
		$monto_total = (($monto_orden+$aumento_oc)-$disminucion_oc);

		$sql_2 = "update cfpd07_obras_partidas set monto_contratado = monto_contratado+$monto_total where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano_estimacion." and cod_obra=".$cod_obra." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
		$sql_3 = "update cfpd05 set precompromiso_obras = precompromiso_obras-$monto_total where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano_estimacion." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;

		$this->cfpd07_obras_partidas->execute($sql_2." ".$sql_3);

		} // fin foreach interno orden de compras

		$sql_4 = "update cfpd07_obras_cuerpo set monto_contratado = monto_contratado+$monto_total where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano_estimacion." and cod_obra=".$cod_obra;
		$this->cfpd07_obras_cuerpo->execute($sql_4);

	} // fin foreach externo orden de compras


			// ******** OTROS COMPROMISOS ************

	$monto_compromiso = 0;
	$datos_compromisos = $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP_script()." and ano_documento=".$ano_select." and condicion_actividad=1 and cod_obra IS NOT NULL");

	foreach($datos_compromisos as $row_datos_compromisos){

		$cod_presi        = $row_datos_compromisos['cepd01_compromiso_cuerpo']['cod_presi'];
		$cod_entidad      = $row_datos_compromisos['cepd01_compromiso_cuerpo']['cod_entidad'];
		$cod_tipo_inst    = $row_datos_compromisos['cepd01_compromiso_cuerpo']['cod_tipo_inst'];
		$cod_inst         = $row_datos_compromisos['cepd01_compromiso_cuerpo']['cod_inst'];
		$cod_dep          = $row_datos_compromisos['cepd01_compromiso_cuerpo']['cod_dep'];
		$ano_estimacion   = $row_datos_compromisos['cepd01_compromiso_cuerpo']['ano_documento'];
		$numero_documento = $row_datos_compromisos['cepd01_compromiso_cuerpo']['numero_documento'];
		$cod_obra         = $row_datos_compromisos['cepd01_compromiso_cuerpo']['cod_obra'];

		$datos_compromisos_partidas_re = $this->cepd01_compromiso_partidas->findAll("cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_documento=".$ano_estimacion." and numero_documento=".$numero_documento." and ano=".$ano_estimacion);
		foreach($datos_compromisos_partidas_re as $datos_compromisos_partidas){
		$cod_sector       = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_sector'];
		$cod_programa     = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_programa'];
		$cod_sub_prog     = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_sub_prog'];
		$cod_proyecto     = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_proyecto'];
		$cod_activ_obra   = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_activ_obra'];
		$cod_partida      = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_partida'];
		$cod_generica     = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_generica'];
		$cod_especifica   = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_especifica'];
		$cod_sub_espec    = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_sub_espec'];
		$cod_auxiliar     = $datos_compromisos_partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
		$monto            = $datos_compromisos_partidas['cepd01_compromiso_partidas']['monto'];

		$monto_compromiso += $monto;

		$sql_2 = "update cfpd07_obras_partidas set monto_contratado = monto_contratado+$monto_compromiso where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano_estimacion." and cod_obra=".$cod_obra." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
		$sql_3 = "update cfpd05 set precompromiso_obras = precompromiso_obras-$monto_compromiso where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano_estimacion." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;

		$this->cfpd07_obras_partidas->execute($sql_2." ".$sql_3);

		} // fin foreach interno otros compromisos

		$sql_4 = "update cfpd07_obras_cuerpo set monto_contratado = monto_contratado+$monto_compromiso where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano_estimacion." and cod_obra=".$cod_obra;
		$this->cfpd07_obras_cuerpo->execute($sql_4);

	} // fin foreach externo otros compromisos


unset($datos_obras);
unset($datos_compras);
unset($datos_compromisos);


if($var_opcion!=null){}else{
     	 $this->layout = "ajax";
     	 $this->set('mensaje', 'La reactualización fue realizada');
         $this->set('termino', true);
         $this->render('vista_index');
}//fin if


} // fin function reactualizacion_relacion_obras






function revision_numeros ($opcion=null) {
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
	$this->layout="ajax";
	if(isset($opcion) && $opcion!=null){
         switch(strtoupper($opcion)){
         	case 'OC':
                $sql_actualizar="";
                $name_numero_n='numero_orden_compra';
                $name_numero_c='numero_orden_compra';
                $name_ano_n='ano_orden_compra';
                $name_ano_c='ano_orden_compra';
                $name_tabla_c='cscd04_ordencompra_encabezado';
                $name_tabla_n='cscd04_ordencompra_numero';
                $c_ano ='ano_orden_compra='.$this->ano_ejecucion();
                $c_ano2='ano_orden_compra='.$this->ano_ejecucion();
                $opc=2;
         	break;
         	case 'RC':
                $name_numero_n='numero_compromiso';
                $name_numero_c='numero_documento';
                $name_ano_n='ano_compromiso';
                $name_ano_c='ano_documento';
                $name_tabla_c='cepd01_compromiso_cuerpo';
                $name_tabla_n='cepd01_compromiso_numero';
                $c_ano ='ano_compromiso='.$this->ano_ejecucion();
                $c_ano2='ano_documento='.$this->ano_ejecucion();
                $opc=2;
         	break;
         	case 'OP':
         	    $name_numero_n='numero_orden_pago';
                $name_numero_c='numero_orden_pago';
                $name_ano_n='ano_orden_pago';
                $name_ano_c='ano_orden_pago';
                $name_tabla_c='cepd03_ordenpago_cuerpo';
                $name_tabla_n='cepd03_ordenpago_numero';
                $c_ano ='ano_orden_pago='.$this->ano_ejecucion();
                $c_ano2='ano_orden_pago='.$this->ano_ejecucion();
                $opc=2;
         	break;
         	case 'SC':
                $opc=1;
         	break;
         }//fin switch


         if($opc==2){


						         $result_anulados=$this->cepd01_compromiso_numero->execute("SELECT a.cod_dep,a.situacion, b.".$name_numero_c.", b.".$name_ano_c."
									  FROM ".$name_tabla_c." b , ".$name_tabla_n." a where
									  b.cod_presi=a.cod_presi and
									  b.cod_entidad=a.cod_entidad and
									  b.cod_tipo_inst=a.cod_tipo_inst and
									  b.cod_inst=a.cod_inst and
									  b.cod_dep=a.cod_dep and
									  b.".$name_numero_c."=a.".$name_numero_n." and b.".$name_ano_c."=a.".$name_ano_n." and
									  b.condicion_actividad=2 and a.situacion!=4  and b.".$c_ano2);
							    echo'<table width="100%"><tr><td width="50%"  align="center" valign="top">';
						        echo'<table style="border:1px #000000 solid;" align="center">';
						        echo'<tr><th  colspan="3">ANULADOS</th></tr>';
						        echo'<tr><td>COD_DEP</td><td>SITUACION</td><td>N&Uacute;MERO</td></tr>';
								foreach($result_anulados as $rsa){
						              $this->cepd01_compromiso_numero->execute("UPDATE ".$name_tabla_n." SET situacion=4 WHERE cod_dep=".$rsa[0]['cod_dep']." and ".$name_ano_n."=".$rsa[0][$name_ano_n]." and ".$name_numero_n."=".$rsa[0][$name_numero_c].";");
									  echo'<tr><td>'.$rsa[0]['cod_dep'].'</td><td>'.$rsa[0]["situacion"].'</td><td>'.$rsa[0][$name_numero_c].'</td></tr>';
									  echo"<tr><td colspan=\"3\">UPDATE ".$name_tabla_n." SET situacion=4 WHERE cod_dep=".$rsa[0]['cod_dep']." and ".$name_ano_n."=".$rsa[0][$name_ano_n]." and ".$name_numero_n."=".$rsa[0][$name_numero_c].";</td>";
								}
								echo'</table>';
								echo'</td><td align="center" valign="top">';
								$result_emitidos=$this->cepd01_compromiso_numero->execute("SELECT a.cod_dep,a.situacion, b.".$name_numero_c.", b.".$name_ano_c."
									  FROM ".$name_tabla_c." b , ".$name_tabla_n." a where
									  b.cod_presi=a.cod_presi and
									  b.cod_entidad=a.cod_entidad and
									  b.cod_tipo_inst=a.cod_tipo_inst and
									  b.cod_inst=a.cod_inst and
									  b.cod_dep=a.cod_dep and
									  b.".$name_numero_c."=a.".$name_numero_n." and b.".$name_ano_c."=a.".$name_ano_n." and
									  b.condicion_actividad=1 and a.situacion!=3 and b.".$c_ano2);// IN (1,2,4);




						        echo'<table style="border:1px #000000 solid;" align="center">';
						        echo'<tr><th colspan="3">EMITIDOS</th></tr>';
						        echo'<tr><td>COD_DEP</td><td>SITUACION</td><td>N&Uacute;MERO</td></tr>';
								foreach($result_emitidos as $rse){
						              $this->cepd01_compromiso_numero->execute("UPDATE ".$name_tabla_n." SET situacion=3 WHERE cod_dep=".$rse[0]['cod_dep']." and ".$name_ano_n."=".$rse[0][$name_ano_c]." and ".$name_numero_n."=".$rse[0][$name_numero_c].";");
									  echo'<tr><td>'.$rse[0]['cod_dep'].'</td><td>'.$rse[0]["situacion"].'</td><td>'.$rse[0][$name_numero_c].'</td></tr>';
									  echo"<tr><td colspan=\"3\">UPDATE ".$name_tabla_n." SET situacion=3 WHERE cod_dep=".$rse[0]['cod_dep']." and ".$name_ano_n."=".$rse[0][$name_ano_c]." and ".$name_numero_n."=".$rse[0][$name_numero_c].";</td>";
								}
								echo'</table>';
								echo'</td></tr></table>';
								/*echo"SELECT a.cod_dep,a.situacion, b.".$name_numero_c.", b.".$name_ano_c."
									  FROM ".$name_tabla_c." b , ".$name_tabla_n." a where
									  b.cod_presi=a.cod_presi and
									  b.cod_entidad=a.cod_entidad and
									  b.cod_tipo_inst=a.cod_tipo_inst and
									  b.cod_inst=a.cod_inst and
									  b.cod_dep=a.cod_dep and
									  b.".$name_numero_c."=a.".$name_numero_n." and b.".$name_ano_c."=a.".$name_ano_n." and
									  b.condicion_actividad=1 and a.situacion!=3 and a.situacion!=5";*/


         }else if($opc==1){

                $name_numero_n='numero_solicitud';
                $name_numero_c='numero_solicitud';

                $name_ano_n='ano_solicitud';
                $name_ano_c='ano_solicitud';

                $name_tabla_c1='cscd02_solicitud_encabezado';
                $name_tabla_c2='cscd02_solicitud_encabezado_anulado';

                $name_tabla_n='cscd02_solicitud_numero';
                $c_ano='ano_solicitud='.$this->ano_ejecucion();


                               $result_anulados=$this->cepd01_compromiso_numero->execute("SELECT a.cod_dep,a.situacion, b.".$name_numero_c.", b.".$name_ano_c."
									  FROM ".$name_tabla_c2." b , ".$name_tabla_n." a where
									  b.cod_presi=a.cod_presi and
									  b.cod_entidad=a.cod_entidad and
									  b.cod_tipo_inst=a.cod_tipo_inst and
									  b.cod_inst=a.cod_inst and
									  b.cod_dep=a.cod_dep and
									  b.".$name_numero_c."=a.".$name_numero_n." and b.".$name_ano_c."=a.".$name_ano_n."
									  and a.situacion!=4  and a.".$c_ano);



							    echo'<table width="100%"><tr><td width="50%"  align="center" valign="top">';


								        echo'<table style="border:1px #000000 solid;" align="center">';
								        echo'<tr><th  colspan="3">ANULADOS</th></tr>';
								        echo'<tr><td>COD_DEP</td><td>SITUACION</td><td>N&Uacute;MERO</td></tr>';
										foreach($result_anulados as $rsa){
								              $this->cepd01_compromiso_numero->execute("BEGIN; UPDATE ".$name_tabla_n." SET situacion=4 WHERE cod_dep=".$rsa[0]['cod_dep']." and ".$name_ano_n."=".$rsa[0][$name_ano_n]." and ".$name_numero_n."=".$rsa[0][$name_numero_c]."; COMMIT;");
											  echo'<tr><td>'.$rsa[0]['cod_dep'].'</td><td>'.$rsa[0]["situacion"].'</td><td>'.$rsa[0][$name_numero_c].'</td></tr>';
											  echo"<tr><td colspan=\"3\">UPDATE ".$name_tabla_n." SET situacion=4 WHERE cod_dep=".$rsa[0]['cod_dep']." and ".$name_ano_n."=".$rsa[0][$name_ano_n]." and ".$name_numero_n."=".$rsa[0][$name_numero_c].";</td>";
										}
										echo'</table>';


								echo'</td><td align="center" valign="top">';

										$result_emitidos=$this->cepd01_compromiso_numero->execute("SELECT a.cod_dep,a.situacion, b.".$name_numero_c.", b.".$name_ano_c."
											  FROM ".$name_tabla_c1." b , ".$name_tabla_n." a where
											  b.cod_presi=a.cod_presi and
											  b.cod_entidad=a.cod_entidad and
											  b.cod_tipo_inst=a.cod_tipo_inst and
											  b.cod_inst=a.cod_inst and
											  b.cod_dep=a.cod_dep and
											  b.".$name_numero_c."=a.".$name_numero_n." and b.".$name_ano_c."=a.".$name_ano_n." and
											  a.situacion!=3 and a.situacion!=5 and a.".$c_ano);

								        echo'<table style="border:1px #000000 solid;" align="center">';
								        echo'<tr><th colspan="3">EMITIDOS</th></tr>';
								        echo'<tr><td>COD_DEP</td><td>SITUACION</td><td>N&Uacute;MERO</td></tr>';
										foreach($result_emitidos as $rse){
								              $this->cepd01_compromiso_numero->execute("BEGIN; UPDATE ".$name_tabla_n." SET situacion=3 WHERE cod_dep=".$rse[0]['cod_dep']." and ".$name_ano_n."=".$rse[0][$name_ano_c]." and ".$name_numero_n."=".$rse[0][$name_numero_c]."; COMMIT;");
											  echo'<tr><td>'.$rse[0]['cod_dep'].'</td><td>'.$rse[0]["situacion"].'</td><td>'.$rse[0][$name_numero_c].'</td></tr>';
											  echo"<tr><td colspan=\"3\">UPDATE ".$name_tabla_n." SET situacion=3 WHERE cod_dep=".$rse[0]['cod_dep']." and ".$name_ano_n."=".$rse[0][$name_ano_c]." and ".$name_numero_n."=".$rse[0][$name_numero_c].";</td>";
										}
										echo'</table>';


								echo'</td></tr></table>';








         }//fin else






        $this->set('mensaje', "N&uacute;meros Corregidos Correctamente");
	}else{
	    $this->set('mensaje', "Disculpe error de parametro");
	}
}







function eliminar_cheques($var=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
 $this->layout="ajax";

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;


 $this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

		       if($var==1){


		        $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
		     	$this->concatena($nom, 'arr05');

		  }else if($var==2){

                $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
     	        $this->concatena($nom, 'arr05');


     	  }else if($var==3){

                $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
     	        $this->concatena($nom, 'arr05');


     	   }else if($var==4){


                  $cod_dep             =     $this->data["datos"]["cod_dep"];
                  $entidad_bancaria    =     $this->data["datos"]["entidad_bancaria"];
                  $sucursal_bancaria   =     $this->data["datos"]["sucursal_bancaria"];
                  $cuenta_bancaria     =     $this->data["datos"]["cuenta_bancaria"];
                  $sw                  =     0;
                   $var11 = 2;
                  if(!empty($this->data["datos"]["numero_cheque"])){
                   $numero_cheque       =     $this->data["datos"]["numero_cheque"];
                   if($this->cstd03_cheque_cuerpo->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."'  and  cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque='".$numero_cheque."' ") != 0){
                      $cstd03_cheque_cuerpo = $this->cstd03_cheque_cuerpo->findAll("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."'  and  cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque='".$numero_cheque."' ");
                      $numero_compropante = $cstd03_cheque_cuerpo[0]["cstd03_cheque_cuerpo"]["numero_comprobante_egreso"];
                      $sw = $this->arrd05->execute("select borrar_cheques('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_ejecucion."', '".$entidad_bancaria."', '".$sucursal_bancaria."', '".$cuenta_bancaria."', '".$numero_cheque."', '".$numero_compropante."'  );");

					     $get_users     = "script_correciones_panel/eliminar_cheques de la dependencia: ".$cod_dep." entidad bancaria ".$entidad_bancaria." sucursal ".$sucursal_bancaria." cuenta bancaria ".$cuenta_bancaria." numero cheque ".$numero_cheque;
   						 $post_users    = $get_users;
   						 $usuario_users = "ADMIN";
   						 $session_users = "script_correciones_panel/eliminar_cheques";
   						 $fecha_dia     = date("Y-m-d H:i:s");
   					     $url_users     = $this->params['url']['url'];
   						 $ip_users      = $this->RequestHandler->getClientIP();
   						 $modulo_users  = "script_correciones";
   						 $sql = "INSERT INTO monitor_actividad (get_, post_, usuario, session_, fecha, url_, ip, modulo) ";
		                 $sql .= "VALUES('$get_users','$post_users','$usuario_users','$session_users','$fecha_dia','$url_users','$ip_users','$modulo_users')";
		                 $resultado = $this->cfpd21_numero_asiento_compromiso->execute($sql);

                   }//fin if
                  }else if($var1==2){
                  	$numero_cheque =  ""; $var11 = 2;
                  	if($this->cstd03_cheque_cuerpo->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."'  and  cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."' ") != 0){
                      $sw = $this->arrd05->execute("select borrar_cheques_cuenta('".$cod_dep."', '".$ano_ejecucion."', '".$entidad_bancaria."', '".$sucursal_bancaria."', '".$cuenta_bancaria."');");

                         $get_users     = "script_correciones_panel/eliminar_cheques de la dependencia: ".$cod_dep." entidad bancaria ".$entidad_bancaria." sucursal ".$sucursal_bancaria." cuenta bancaria (elimino todos los cheques registrados en esta cuenta) ".$cuenta_bancaria;
   						 $post_users    = $get_users;
   						 $usuario_users = "ADMIN";
   						 $session_users = "script_correciones_panel/eliminar_cheques";
   						 $fecha_dia     = date("Y-m-d H:i:s");
   					     $url_users     = $this->params['url']['url'];
   						 $ip_users      = $this->RequestHandler->getClientIP();
   						 $modulo_users  = "script_correciones";
   						 $sql = "INSERT INTO monitor_actividad (get_, post_, usuario, session_, fecha, url_, ip, modulo) ";
		                 $sql .= "VALUES('$get_users','$post_users','$usuario_users','$session_users','$fecha_dia','$url_users','$ip_users','$modulo_users')";
		                 $resultado = $this->cfpd21_numero_asiento_compromiso->execute($sql);

                     }//fin if

                  }//fin else


                  if($var6!=null && !empty($this->data["datos"]["cuenta_bancaria"]) && $var1==3){
                          $var11 = 1;
                          $cod_dep             =     $this->data["datos"]["cod_dep"];
		                  $entidad_bancaria    =     $this->data["datos"]["entidad_bancaria"];
		                  $sucursal_bancaria   =     $this->data["datos"]["sucursal_bancaria"];
		                  $cuenta_bancaria     =     $this->data["datos"]["cuenta_bancaria"];
                        $this->set('var1', $var1);
						$this->set('var2', $var2);
						$this->set('var3', $var3);
						$this->set('var4', $var4);
						$this->set('var5', $var5);
						$this->set('var6', $var6);
						 $ano_ejecucion = $this->Session->read('ano_ejecucion');
						 $cond2   = $condicion." and  cod_dep='".$cod_dep."' and cod_entidad_bancaria=".$entidad_bancaria." and cod_sucursal=".$sucursal_bancaria." and cuenta_bancaria='".$cuenta_bancaria."' and condicion_actividad=2 and ano_movimiento='".$ano_ejecucion."'  ";
                        $lista=  $this->cstd03_cheque_cuerpo->generateList($cond2, 'numero_cheque ASC', null, '{n}.cstd03_cheque_cuerpo.numero_cheque', '{n}.cstd03_cheque_cuerpo.numero_cheque');
                     if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
                  }//fin if





		              if($sw>1){
                       $this->set('Message_existe', 'Cheque eliminado correctamente');
		              }else{
                        $this->set('errorMessage', 'Cheque no pudo ser eliminado ');
		              }//fin else




                    $this->set('var11', $var11);


		  }//fin



$this->set('var', $var);



}//fin funtion








function eliminar_notas_debito($var=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
 $this->layout="ajax";

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;


 $this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

		       if($var==1){


		        $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
		     	$this->concatena($nom, 'arr05');

		  }else if($var==2){

                $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
     	        $this->concatena($nom, 'arr05');


     	  }else if($var==3){

                $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
     	        $this->concatena($nom, 'arr05');


     	   }else if($var==4){

                  $cod_dep             =     $this->data["datos"]["cod_dep"];
                  $entidad_bancaria    =     $this->data["datos"]["entidad_bancaria"];
                  $sucursal_bancaria   =     $this->data["datos"]["sucursal_bancaria"];
                  $cuenta_bancaria     =     $this->data["datos"]["cuenta_bancaria"];
                  $sw                  =     0;
                   $var11 = 2;
                  if(!empty($this->data["datos"]["numero_cheque"])){
                   $numero_cheque       =     $this->data["datos"]["numero_cheque"];

                   if($this->cstd09_notadebito_cuerpo_propio->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."'  and  cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."' and numero_debito='".$numero_cheque."' ") != 0){
                      $cstd03_cheque_cuerpo = $this->cstd09_notadebito_cuerpo_propio->findAll("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."'  and  cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."' and numero_debito='".$numero_cheque."' ");
                      $numero_compropante = $cstd03_cheque_cuerpo[0]["cstd09_notadebito_cuerpo_propio"]["numero_comprobante_egreso"];
                      $sw = $this->arrd05->execute("select borrar_nota_debito('".$cod_dep."', '".$ano_ejecucion."', '".$entidad_bancaria."', '".$sucursal_bancaria."', '".$cuenta_bancaria."', '".$numero_cheque."', '".$numero_compropante."'  );");

                      	 $get_users     = "script_correciones_panel/eliminar_nota_debito de la dependencia: ".$cod_dep." entidad bancaria ".$entidad_bancaria." sucursal ".$sucursal_bancaria." cuenta bancaria ".$cuenta_bancaria." nota de debito ".$numero_cheque;
   						 $post_users    = $get_users;
   						 $usuario_users = "ADMIN";
   						 $session_users = "script_correciones_panel/eliminar_nota_debito";
   						 $fecha_dia     = date("Y-m-d H:i:s");
   					     $url_users     = $this->params['url']['url'];
   						 $ip_users      = $this->RequestHandler->getClientIP();
   						 $modulo_users  = "script_correciones";
   						 $sql = "INSERT INTO monitor_actividad (get_, post_, usuario, session_, fecha, url_, ip, modulo) ";
		                 $sql .= "VALUES('$get_users','$post_users','$usuario_users','$session_users','$fecha_dia','$url_users','$ip_users','$modulo_users')";
		                 $resultado = $this->cfpd21_numero_asiento_compromiso->execute($sql);

                   }//fin if
                  }else if($var1==2){

                  	/* $numero_cheque =  ""; $var11 = 2;
                  	if($this->cstd09_notadebito_cuerpo->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."'  and  cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."' ") != 0){
                      $sw = $this->arrd05->execute("select borrar_cheques_cuenta('".$cod_dep."', '".$ano_ejecucion."', '".$entidad_bancaria."', '".$sucursal_bancaria."', '".$cuenta_bancaria."');");
                     }//fin if
                     */

                  }//fin else


                  if($var6!=null && !empty($this->data["datos"]["cuenta_bancaria"]) && $var1==3){
                          $var11 = 1;
                          $cod_dep             =     $this->data["datos"]["cod_dep"];
		                  $entidad_bancaria    =     $this->data["datos"]["entidad_bancaria"];
		                  $sucursal_bancaria   =     $this->data["datos"]["sucursal_bancaria"];
		                  $cuenta_bancaria     =     $this->data["datos"]["cuenta_bancaria"];
                        $this->set('var1', $var1);
						$this->set('var2', $var2);
						$this->set('var3', $var3);
						$this->set('var4', $var4);
						$this->set('var5', $var5);
						$this->set('var6', $var6);
						 $ano_ejecucion = $this->Session->read('ano_ejecucion');
						 $cond2   = $condicion." and  cod_dep='".$cod_dep."' and cod_entidad_bancaria=".$entidad_bancaria." and cod_sucursal=".$sucursal_bancaria." and cuenta_bancaria='".$cuenta_bancaria."' and condicion_actividad=2 and ano_movimiento='".$ano_ejecucion."'  ";
                        $lista=  $this->cstd09_notadebito_cuerpo_propio->generateList($cond2, 'numero_debito ASC', null, '{n}.cstd09_notadebito_cuerpo_propio.numero_debito', '{n}.cstd09_notadebito_cuerpo_propio.numero_debito');
                     if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
                  }//fin if





		              if($sw>1){
                       $this->set('Message_existe', 'Nota de D&eacute;bito eliminada correctamente');
		              }else{
                        $this->set('errorMessage', 'La Nota de D&eacute;bito no pudo ser eliminada ');
		              }//fin else




                    $this->set('var11', $var11);


		  }//fin



$this->set('var', $var);



}//fin funtion






function select_cheque($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
 $this->layout="ajax";

  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$var3;

  $ano_ejecucion = $this->Session->read('ano_ejecucion');


//$var1 = si son todos o uno en especifico
//$var2 = opcion
//$var3 = dependencia
//$var4 = entidad  bancaria
//$var5 = sucursal bancaria
//$var6 = cuenta   bancaria



		switch($var2){

			        case 'entidad':

			           if($var3!=null){
                       $this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
			           }else{
			           	$lista = array();
			           	$this->set('tipo', $lista);
			           }
                       $var2 = "sucursal";
                    break;


			        case 'sucursal':
			            $cond =" cod_entidad_bancaria=".$var4;
						$lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
			           if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->concatena($lista, 'vector');}
					   $var2 = "cuenta";
					break;


					case 'cuenta':
					     $cond  =  $condicion." and cod_entidad_bancaria=".$var4." and cod_sucursal=".$var5;
						 $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
                      if($lista==""){$lista = array(); $this->concatena($lista,'vector');}else{$this->concatena($lista,'vector');}
					     $var2 = "cheque";
					break;


					case 'cheque':
                          $cond2   = $condicion." and cod_entidad_bancaria=".$var4." and cod_sucursal=".$var5." and cuenta_bancaria='".$var6."' and condicion_actividad=2 and ano_movimiento='".$ano_ejecucion."' ";
                          $lista=  $this->cstd03_cheque_cuerpo->generateList($cond2, 'numero_cheque ASC', null, '{n}.cstd03_cheque_cuerpo.numero_cheque', '{n}.cstd03_cheque_cuerpo.numero_cheque');
                          if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
                          if($var1==3){$var2 = "lista_cheque";}else if($var1==2){$var2 = "final";}

					break;


					case 'lista_cheque':
                          $var2 = "final";
					break;


		}//fin switch


		$this->set('var1', $var1);
		$this->set('var2', $var2);
		$this->set('var3', $var3);
		$this->set('var4', $var4);
		$this->set('var5', $var5);
		$this->set('var6', $var6);


}//fin funcction




function select_cheque_notad($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
 $this->layout="ajax";

  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$var3;

  $ano_ejecucion = $this->Session->read('ano_ejecucion');


//$var1 = si son todos o uno en especifico
//$var2 = opcion
//$var3 = dependencia
//$var4 = entidad  bancaria
//$var5 = sucursal bancaria
//$var6 = cuenta   bancaria



		switch($var2){

			        case 'entidad':

			           if($var3!=null){
                       $this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
			           }else{
			           	$lista = array();
			           	$this->set('tipo', $lista);
			           }
                       $var2 = "sucursal";
                    break;


			        case 'sucursal':
			            $cond =" cod_entidad_bancaria=".$var4;
						$lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
			           if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->concatena($lista, 'vector');}
					   $var2 = "cuenta";
					break;


					case 'cuenta':
					     $cond  =  $condicion." and cod_entidad_bancaria=".$var4." and cod_sucursal=".$var5;
						 $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
                      if($lista==""){$lista = array(); $this->concatena($lista,'vector');}else{$this->concatena($lista,'vector');}
					     $var2 = "cheque";
					break;


					case 'cheque':
                          $cond2   = $condicion." and cod_entidad_bancaria=".$var4." and cod_sucursal=".$var5." and cuenta_bancaria='".$var6."' and condicion_actividad=2 and ano_movimiento='".$ano_ejecucion."' ";
                          $lista=  $this->cstd09_notadebito_cuerpo_propio->generateList($cond2, 'numero_debito ASC', null, '{n}.cstd09_notadebito_cuerpo_propio.numero_debito', '{n}.cstd09_notadebito_cuerpo_propio.numero_debito');
                          if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
                          if($var1==3){$var2 = "lista_cheque";}else if($var1==2){$var2 = "final";}

					break;


					case 'lista_cheque':
                          $var2 = "final";
					break;


		}//fin switch


		$this->set('var1', $var1);
		$this->set('var2', $var2);
		$this->set('var3', $var3);
		$this->set('var4', $var4);
		$this->set('var5', $var5);
		$this->set('var6', $var6);


}//fin funcction



function eliminar_orden($var=null, $tipo_orden=null,$var3=null,$var4=null){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $this->layout="ajax";
  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
  $this->set("tipo_orden",$tipo_orden);
  $this->set("var2",$tipo_orden);
  $this->set("var3",$var3);

 //$this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
if($var==2 && isset($tipo_orden)){
	switch(strtoupper($tipo_orden)){
		case 'OC':$this->set("TIPOORDEN"," de la Orden de Compra");break;
		case 'RC':$this->set("TIPOORDEN"," del Registro de Compromiso");break;
		case 'OP':$this->set("TIPOORDEN"," de la Orden de Pago");break;
	}//fin switch
    $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
    $this->concatena($nom, 'arr05');
    $this->set("var3",$var3);
}

//$ano_ejecucion=YEAR_REACTUALIZACION;
if(strtoupper($var)=='ESPECIFICO'){
	  $cod_dep   =     $this->data["datos"]["cod_dep"];
      $numero    =     $this->data["datos"]["numero_orden"];
      switch(strtoupper($tipo_orden)){
		case 'OC':
           $rs=$this->arrd05->execute("select borrar_oc(".$cod_dep.", ".$ano_ejecucion.", ".$numero.");");

                         $get_users     = "script_correciones_panel/eliminar_orden_compra de la dependencia: ".$cod_dep." orden de compra ".$numero;
   						 $post_users    = $get_users;
   						 $usuario_users = "ADMIN";
   						 $session_users = "script_correciones_panel/eliminar_orden_compra";
   						 $fecha_dia     = date("Y-m-d H:i:s");
   					     $url_users     = $this->params['url']['url'];
   						 $ip_users      = $this->RequestHandler->getClientIP();
   						 $modulo_users  = "script_correciones";
   						 $sql = "INSERT INTO monitor_actividad (get_, post_, usuario, session_, fecha, url_, ip, modulo) ";
		                 $sql .= "VALUES('$get_users','$post_users','$usuario_users','$session_users','$fecha_dia','$url_users','$ip_users','$modulo_users')";
		                 $resultado = $this->cfpd21_numero_asiento_compromiso->execute($sql);

           $this->set('Message_existe', 'Orden de Compra eliminada correctamente');
           $lista=  $this->cscd04_ordencompra_encabezado->generateList($condicion." and cod_dep=".$cod_dep." and condicion_actividad=2 and ano_orden_compra=".$ano_ejecucion, 'numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.numero_orden_compra');
            if($lista==""){
            	$lista = array();
            	$this->set('vector', $lista);
            }else{
            	$this->set('vector', $lista);
            }
        break;
		case 'RC':
           $rs=$this->arrd05->execute("select borrar_rc(".$cod_dep.", ".$ano_ejecucion.", '".$this->mascara_ocho($numero)."');");

                         $get_users     = "script_correciones_panel/eliminar_otros_comprimisos de la dependencia: ".$cod_dep." otros compromisos ".$numero;
   						 $post_users    = $get_users;
   						 $usuario_users = "ADMIN";
   						 $session_users = "script_correciones_panel/eliminar_otros_comprimisos";
   						 $fecha_dia     = date("Y-m-d H:i:s");
   					     $url_users     = $this->params['url']['url'];
   						 $ip_users      = $this->RequestHandler->getClientIP();
   						 $modulo_users  = "script_correciones";
   						 $sql = "INSERT INTO monitor_actividad (get_, post_, usuario, session_, fecha, url_, ip, modulo) ";
		                 $sql .= "VALUES('$get_users','$post_users','$usuario_users','$session_users','$fecha_dia','$url_users','$ip_users','$modulo_users')";
		                 $resultado = $this->cfpd21_numero_asiento_compromiso->execute($sql);

           $this->set('Message_existe', 'Registro de compromiso eliminado correctamente');
           $lista=  $this->cepd01_compromiso_cuerpo->generateList($condicion." and cod_dep=".$cod_dep."  and condicion_actividad=2 and ano_documento=".$ano_ejecucion, 'numero_documento ASC', null, '{n}.cepd01_compromiso_cuerpo.numero_documento', '{n}.cepd01_compromiso_cuerpo.numero_documento');
            if($lista==""){
            	$lista = array();
            	$this->set('vector', $lista);
            }else{
            	$this->set('vector', $lista);
            }
        break;
		case 'OP':
           $rs=$this->arrd05->execute("select borrar_orden_pago(".$cod_dep.", ".$ano_ejecucion.", '".$this->mascara_ocho($numero)."');");

                         $get_users     = "script_correciones_panel/eliminar_orden_pago de la dependencia: ".$cod_dep." orden de pago ".$numero;
   						 $post_users    = $get_users;
   						 $usuario_users = "ADMIN";
   						 $session_users = "script_correciones_panel/eliminar_orden_pago";
   						 $fecha_dia     = date("Y-m-d H:i:s");
   					     $url_users     = $this->params['url']['url'];
   						 $ip_users      = $this->RequestHandler->getClientIP();
   						 $modulo_users  = "script_correciones";
   						 $sql = "INSERT INTO monitor_actividad (get_, post_, usuario, session_, fecha, url_, ip, modulo) ";
		                 $sql .= "VALUES('$get_users','$post_users','$usuario_users','$session_users','$fecha_dia','$url_users','$ip_users','$modulo_users')";
		                 $resultado = $this->cfpd21_numero_asiento_compromiso->execute($sql);

            $this->set('Message_existe', 'Orden de Pago eliminada correctamente');
            $lista=  $this->cepd03_ordenpago_cuerpo->generateList($condicion." and cod_dep=".$cod_dep." and condicion_actividad=2 and ano_orden_pago=".$ano_ejecucion, 'numero_orden_pago ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago');
            if($lista==""){
            	$lista = array();
            	$this->set('vector', $lista);
            }else{
            	$this->set('vector', $lista);
            }
		break;
	}//fin switch
    $this->set("Mustra_select",true);

}

if(strtoupper($var)=='TODO'){
	  $cod_dep   =     $this->data["datos"]["cod_dep"];
      //$numero    =     $this->data["datos"]["numero_orden"];
      switch(strtoupper($tipo_orden)){
		case 'OC':
           $rs=$this->arrd05->execute("select borrar_oc_dep(".$cod_dep.", ".$ano_ejecucion.");");
           $this->set('Message_existe', 'Todas Las Ordenes de Compra eliminadas correctamente');
        break;
		case 'RC':
           $rs=$this->arrd05->execute("select borrar_rc_dep(".$cod_dep.", ".$ano_ejecucion.");");
           $this->set('Message_existe', 'Todos Los Registros de compromiso eliminados correctamente');
        break;
		case 'OP':
           $rs=$this->arrd05->execute("select borrar_orden_pago_dep(".$cod_dep.", ".$ano_ejecucion.");");
           $this->set('Message_existe', 'Todas Las  Orden de Pagos eliminadas correctamente');
		break;
	}//fin switch
}
$this->set('var', $var);
}//fin funtion eliminar ordenes


function select_orden($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$var3;
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
		switch(strtoupper($var1)){
			        case 'TODO':
			           if($var3!=null){
                       $this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
			           }else{
			           	$lista = array();
			           	$this->set('tipo', $lista);
			           }
                       $varX = "final_todo";
                       $this->set("dep",$var3);
                    break;
			        case 'ESPECIFICO':
			            switch($var2){
			            	case 'OC';
						         $lista=  $this->cscd04_ordencompra_encabezado->generateList($condicion." and condicion_actividad=2 and ano_orden_compra=".$ano_ejecucion, 'numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.numero_orden_compra');
			                    if($lista==""){
			                    	$lista = array();
			                    	$this->set('vector', $lista);
			                    }else{
			                    	$this->set('vector', $lista);
			                    }

			            	break;
			            	case 'RC';
						         $lista=  $this->cepd01_compromiso_cuerpo->generateList($condicion." and condicion_actividad=2 and ano_documento=".$ano_ejecucion, 'numero_documento ASC', null, '{n}.cepd01_compromiso_cuerpo.numero_documento', '{n}.cepd01_compromiso_cuerpo.numero_documento');
			                    if($lista==""){
			                    	$lista = array();
			                    	$this->set('vector', $lista);
			                    }else{
			                    	$this->set('vector', $lista);
			                    }
			            	break;
			            	case 'OP';
						         $lista=  $this->cepd03_ordenpago_cuerpo->generateList($condicion." and condicion_actividad=2 and ano_orden_pago=".$ano_ejecucion, 'numero_orden_pago ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago');
			                    if($lista==""){
			                    	$lista = array();
			                    	$this->set('vector', $lista);
			                    }else{
			                    	$this->set('vector', $lista);
			                    }
			            	break;
			            }//fin switch
					    $this->set("dep",$var3);
					break;
		}//fin switch
		$this->set('var1', $var1);
		$this->set('var2', $var2);
		$this->set('var3', $var3);
		$this->set('var4', $var4);
		$this->set('var5', $var5);
		//echo $var1." / ".$var2." / ".$var3." / ".$var4;


}//fin funcion select orden

function boton_eliminar_orden ($var1=null,$var2=null,$var3=null,$var4=null) {
     $this->layout="ajax";
        $this->set('var1', $var1);
		$this->set('var2', $var2);
		$this->set('var3', $var3);
		$this->set('var4', $var4);
		//echo $var1." / ".$var2." / ".$var3." / ".$var4;
}




function cambiar_rif($var1=null,$var2=null,$var3=null,$var4=null) {
     $this->layout="ajax";
     if($var1==1){

     }else{
                if(!empty($this->data['campo']['rif_a']) && !empty($this->data['campo']['rif_b'])  && !empty($this->data['campo']['rif_c'])){
                        $campo_a  =  strtoupper($this->data['campo']['rif_b']);
                        $campo_b  =  strtoupper($this->data['campo']['rif_a']);
                        $campo_c  =  strtoupper($this->data['campo']['rif_c']);
                             if($campo_c==1){
                        $a=$this->cepd03_ordenpago_cuerpo->execute(" select reemplazar_rif ('".$campo_a."', '".$campo_b."', 1);  ");
                        if($a>1){$this->set('mensaje', "El rif fue cambiado con exito");}else{$this->set('mensaje', "No pudo ser cambiado");}
                       }else if($campo_c==2){
                       	$a=$this->cepd03_ordenpago_cuerpo->execute(" select reemplazar_rif ('".$campo_a."', '".$campo_b."', 2);  ");
                         if($a>1){$this->set('mensaje', "El cedula fue cambiado con exito");}else{$this->set('mensaje', "No pudo ser cambiado");}
                       }//fin if
                }else{
                         $this->set('mensaje', "Faltan datos");
                }



     }//fin function



	$this->set('var1', $var1);

}///fin function




function select_eliminar_movimientos_manuales_dep($var=null){
		$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    $this->layout="ajax";
    $nom = $this->cfpd10_reformulacion_texto->generateList($this->condicionNDEP_script()." and cod_dep='".$var."'and ano_reformulacion='".YEAR_REACTUALIZACION."' ", 'numero_oficio ASC', null, '{n}.cfpd10_reformulacion_texto.numero_oficio', '{n}.cfpd10_reformulacion_texto.numero_oficio');
    $this->set('arr05', $nom);


}



function eliminar_decreto ($var=null) {

	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    $this->layout="ajax";
    $this->set("var",$var);
    if($var==1){
        $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
        $this->concatena($nom, 'arr05');
    }else if(strtoupper($var)=='ELIMINAR'){
    	 if(!empty($this->data["datos"]["cod_dep"]) && !empty($this->data["datos"]["decreto"])){
    	 	  $cod_presi     = $this->Session->read('SScodpresi');
			  $cod_entidad   = $this->Session->read('SScodentidad');
			  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			  $cod_inst      = $this->Session->read('SScodinst');
			  $ano_ejecucion = $this->Session->read('ano_ejecucion');
			  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
              $cod_dep=$this->data["datos"]["cod_dep"];
              $numero_oficio=strtoupper($this->data["datos"]["decreto"]);
              $c=$this->v_reformulacion_verificar->findCount($condicion." and cod_dep=".$cod_dep." and ano_reformulacion=".$ano_ejecucion." and upper(numero_oficio)='".$numero_oficio."'");
              if($c!=0){
              	  $rs=$this->v_reformulacion_verificar->execute("SELECT sum(verificar) as verificar from v_reformulacion_verificar where ".$condicion." and cod_dep=".$cod_dep." and ano_reformulacion=".$ano_ejecucion." and upper(numero_oficio)='".$numero_oficio."'");
	    	      if($rs[0][0]["verificar"]==0){
                      $data=$this->v_reformulacion_verificar->findAll($condicion." and cod_dep=".$cod_dep." and ano_reformulacion=".$ano_ejecucion." and upper(numero_oficio)='".$numero_oficio."'");
                      //print_r($data);
	    	      	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						foreach($data as $row){
						$rs_rf=$row['v_reformulacion_verificar'];
						$cod_presi 			= $rs_rf['cod_presi'];
						$cod_entidad 		= $rs_rf['cod_entidad'];
						$cod_tipo_inst 		= $rs_rf['cod_tipo_inst'];
						$cod_inst 			= $rs_rf['cod_inst'];
						$cod_dep_2 			= $rs_rf['cod_dep'];
						$cod_dep 			= $rs_rf['codi_dep'];
						$ano 				= $rs_rf['ano_reformulacion'];
						$cod_sector 		= $rs_rf['cod_sector'];
						$cod_programa 		= $rs_rf['cod_programa'];
						$cod_sub_prog 		= $rs_rf['cod_sub_prog'];
						$cod_proyecto 		= $rs_rf['cod_proyecto'];
						$cod_activ_obra 	= $rs_rf['cod_activ_obra'];
						$cod_partida		= $rs_rf['cod_partida'];
						$cod_generica		= $rs_rf['cod_generica'];
						$cod_especifica		= $rs_rf['cod_especifica'];
						$cod_sub_espec 		= $rs_rf['cod_sub_espec'];
						$cod_auxiliar		= $rs_rf['cod_auxiliar'];
						$monto_disminucion	= $rs_rf['monto_disminucion'];
						$monto_aumento		= $rs_rf['monto_aumento'];
						$numero_decreto		= $rs_rf['numero_decreto'];
						$fdc		        = $rs_rf['fecha_decreto'];
						$fdo		        = $rs_rf['fecha_oficio'];

                        $fdc=explode('-',$fdc);
                        $fecha_decreto=$fdc[2]."/".$fdc[1]."/".$fdc[0];

                        $fdo=explode('-',$fdo);
                        $fecha_oficio=$fdo[2]."/".$fdo[1]."/".$fdo[0];

						$tipo=$rs_rf["cod_tipo"];

						if($tipo==1 and $monto_aumento!=0){

		                   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 1;
						   $mt   = $this->Formato1($monto_aumento);
						   $ccp  = '';
						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, "$fecha_decreto", $mt, $ccp, $ano,  $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);


						}else if($tipo==1 and $monto_disminucion!=0){

						   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 2;
						   $mt   = $this->Formato1($monto_disminucion);
						   $ccp  = '';
						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, "$fecha_decreto", $mt, $ccp, $ano,  $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);


						}else if($tipo==2){

						   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 3;
						   $mt   = $this->Formato1($monto_aumento);
						   $ccp  = '';
						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, "$fecha_decreto", $mt, $ccp, $ano,  $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);

					     }else if($tipo==3){

						   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 4;
						   $mt   = $this->Formato1($monto_disminucion);
						   $ccp  = '';
						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, "$fecha_decreto", $mt, $ccp, $ano,  $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);

							}else if($tipo==4){

							$cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 5;
						   $mt   = $this->Formato1($monto_aumento);
						   $ccp  = '';
						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, "$fecha_decreto", $mt, $ccp, $ano,  $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);

						    }else if($tipo==5){

						   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 6;
						   $mt   = $this->Formato1($monto_aumento);
						   $ccp  = '';
						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, "$fecha_decreto", $mt, $ccp, $ano,  $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);

						    }

							$delete_ref_part="delete from cfpd10_reformulacion_partidas where ".$condicion." and codi_dep=".$cod_dep." and  cod_dep=".$cod_dep_2." and upper(numero_oficio)='".$numero_oficio."' and ano_reformulacion=".$ano;
							$delete_ref_text="delete from cfpd10_reformulacion_texto where  ".$condicion." and cod_dep=".$cod_dep_2." and  upper(numero_oficio)='".$numero_oficio."' and ano_reformulacion=".$ano;
							$eliminar1=$this->cfpd10_reformulacion_partidas->execute($delete_ref_part);
							$eliminar2=$this->cfpd10_reformulacion_texto->execute($delete_ref_text);
							$msn=2;

                         $partida_users = $cod_sector."-".$cod_programa."-".$cod_sub_prog."-".$cod_proyecto."-".$cod_activ_obra."-".$cod_partida."-".$cod_generica."-".$cod_especifica."-".$cod_sub_prog."-".$cod_auxiliar;
					     $get_users     = "script_correciones_panel/eliminar_decreto de la dependencia: ".$cod_dep." oficio ".$numero_oficio." de fecha ".$fecha_oficio." Decreto ".$numero_decreto." de fecha ".$fecha_decreto." partida ".$partida_users." monto ".$mt;
   						 $post_users    = $get_users;
   						 $usuario_users = "ADMIN";
   						 $session_users = "script_correciones_panel/eliminar_decreto";
   						 $fecha_dia     = date("Y-m-d H:i:s");
   					     $url_users     = $this->params['url']['url'];
   						 $ip_users      = $this->RequestHandler->getClientIP();
   						 $modulo_users  = "script_correciones";
   						 $sql = "INSERT INTO monitor_actividad (get_, post_, usuario, session_, fecha, url_, ip, modulo) ";
		                 $sql .= "VALUES('$get_users','$post_users','$usuario_users','$session_users','$fecha_dia','$url_users','$ip_users','$modulo_users')";
		                 $resultado = $this->cfpd21_numero_asiento_compromiso->execute($sql);


						}// fin foreach

	    	      	  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	    	      	  $this->set('Message_existe',  "Eliminado con exito");
	    	      	  $this->log('PANEL_SCRIPT: ELIMINACION DE OFICIO SIN MOVIMIENTOS ['.$numero_oficio.']', LOG_ELIMINAR);
	    	      }else{
	    	      	  $this->set('errorMessage',  "Disculpe no se puede proceder a eliminar el oficio");
	    	      	  $this->set('BOTON_ACEPTAR',true);
	    	      	  $this->set('NUMERO_OFICIO',$numero_oficio);
	    	      	  $this->set('COD_DEP',$cod_dep);
	    	      }
              }else{
              	 $this->set('errorMessage',  "Disculpe Datos no encontrados, verifique numero del oficio");
              }
    	 }else{
    	 	$this->set('errorMessage',  "Por favor seleccione la dependencia e ingrese el numero del oficio");
    		 }

    }

}//fin eliminar_decreto

///////////
function eliminar_decreto_si ($var=null,$cod_dep,$numero_oficio) {
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
    $this->layout="ajax";
    $this->set("var",$var);
    if(strtoupper($var)=='PROCEDER'){
    	 if(!empty($cod_dep) && !empty($numero_oficio)){
    	 	  $cod_presi     = $this->Session->read('SScodpresi');
			  $cod_entidad   = $this->Session->read('SScodentidad');
			  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			  $cod_inst      = $this->Session->read('SScodinst');
			  $ano_ejecucion = $this->Session->read('ano_ejecucion');
			  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
              //$cod_dep=$this->data["datos"]["cod_dep"];
              $numero_oficio=strtoupper($numero_oficio);
              $c=$this->v_reformulacion_verificar->findCount($condicion." and cod_dep=".$cod_dep." and ano_reformulacion=".$ano_ejecucion." and upper(numero_oficio)='".$numero_oficio."'");
              //echo $condicion." and cod_dep=".$cod_dep." and ano_reformulacion=".$ano_ejecucion." and upper(numero_oficio)='".$numero_oficio."'";
              if($c!=0){
              	  //$rs=$this->v_reformulacion_verificar->execute("SELECT sum(verificar) as verificar from v_reformulacion_verificar where ".$condicion." and cod_dep=".$cod_dep." and ano_reformulacion=".$ano_ejecucion." and upper(numero_oficio)='".$numero_oficio."'");
	    	     // if($rs[0][0]["verificar"]==0){
                      $data=$this->v_reformulacion_verificar->findAll($condicion." and cod_dep=".$cod_dep." and ano_reformulacion=".$ano_ejecucion." and upper(numero_oficio)='".$numero_oficio."'");
                      //print_r($data);
	    	      	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						$LOG_DATA_REF ="";



				foreach($data as $row){


						$rs_rf=$row['v_reformulacion_verificar'];
						$cod_presi 			= $rs_rf['cod_presi'];
						$cod_entidad 		= $rs_rf['cod_entidad'];
						$cod_tipo_inst 		= $rs_rf['cod_tipo_inst'];
						$cod_inst 			= $rs_rf['cod_inst'];
						$cod_dep_2 			= $rs_rf['cod_dep'];
						$cod_dep 			= $rs_rf['codi_dep'];
						$ano 				= $rs_rf['ano_reformulacion'];
						$cod_sector 		= $rs_rf['cod_sector'];
						$cod_programa 		= $rs_rf['cod_programa'];
						$cod_sub_prog 		= $rs_rf['cod_sub_prog'];
						$cod_proyecto 		= $rs_rf['cod_proyecto'];
						$cod_activ_obra 	= $rs_rf['cod_activ_obra'];
						$cod_partida		= $rs_rf['cod_partida'];
						$cod_generica		= $rs_rf['cod_generica'];
						$cod_especifica		= $rs_rf['cod_especifica'];
						$cod_sub_espec 		= $rs_rf['cod_sub_espec'];
						$cod_auxiliar		= $rs_rf['cod_auxiliar'];
						$monto_disminucion	= $rs_rf['monto_disminucion'];
						$monto_aumento		= $rs_rf['monto_aumento'];
						$numero_decreto		= $rs_rf['numero_decreto'];
						$fdc		        = $rs_rf['fecha_decreto'];
						$fdo		        = $rs_rf['fecha_oficio'];
                        $fdc=explode('-',$fdc);
                        $fecha_decreto=$fdc[2]."/".$fdc[1]."/".$fdc[0];
                        $fdo=explode('-',$fdo);
                        $fecha_oficio=$fdo[2]."/".$fdo[1]."/".$fdo[0];
						$tipo=$rs_rf["cod_tipo"];
						$LOG_DATA_REF .="\n[$cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep_2,$cod_dep,$ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar,$monto_disminucion,$monto_aumento,$numero_decreto,$fecha_decreto]";



						if($tipo==1 and $monto_aumento!=0){

		                   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 1;
						   $mt   = $this->Formato1($monto_aumento);
						   $ccp  = '';

						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_decreto, $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);

						}else if($tipo==1 and $monto_disminucion!=0){

						   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 2;
						   $mt   = $this->Formato1($monto_disminucion);
						   $ccp  = '';

						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_decreto, $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);

						}else if($tipo==2){

						   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 3;
						   $mt   = $this->Formato1($monto_aumento);
						   $ccp  = '';

						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_decreto, $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);

					     }else if($tipo==3){

						    $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
							$to   = 2;
							$td   = 1;
							$ta   = 4;
							$mt   = $this->Formato1($monto_disminucion);
							$ccp  = '';

							$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_decreto, $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);

						 }else if($tipo==4){

						   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 5;
						   $mt   = $this->Formato1($monto_aumento);
						   $ccp  = '';

						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_decreto, $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);


						 }else if($tipo==5){

						   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 2;
						   $td   = 1;
						   $ta   = 6;
						   $mt   = $this->Formato1($monto_aumento);
						   $ccp  = '';

						   $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_decreto, $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep, null);

						 }

							$delete_ref_part="delete from cfpd10_reformulacion_partidas where ".$condicion." and codi_dep=".$cod_dep." and  cod_dep=".$cod_dep_2." and upper(numero_oficio)='".$numero_oficio."' and ano_reformulacion=".$ano;
							$delete_ref_text="delete from cfpd10_reformulacion_texto where  ".$condicion." and cod_dep=".$cod_dep_2." and  upper(numero_oficio)='".$numero_oficio."' and ano_reformulacion=".$ano;
							$delete_cfpd20="delete from cfpd20 where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." and fecha='".$fecha_decreto."' and ano=".$ano;
							$eliminar1=$this->cfpd10_reformulacion_partidas->execute($delete_ref_part);
							$eliminar2=$this->cfpd10_reformulacion_texto->execute($delete_ref_text);
							$eliminar3=$this->cfpd20->execute($delete_cfpd20);
							$msn=2;

                         $partida_users = $cod_sector."-".$cod_programa."-".$cod_sub_prog."-".$cod_proyecto."-".$cod_activ_obra."-".$cod_partida."-".$cod_generica."-".$cod_especifica."-".$cod_sub_prog."-".$cod_auxiliar;
					     $get_users     = "script_correciones_panel/eliminar_decreto de la dependencia: ".$cod_dep." oficio ".$numero_oficio." de fecha ".$fecha_oficio." Decreto ".$numero_decreto." de fecha ".$fecha_decreto." partida ".$partida_users." monto ".$mt;
   						 $post_users    = $get_users;
   						 $usuario_users = "ADMIN";
   						 $session_users = "script_correciones_panel/eliminar_decreto";
   						 $fecha_dia     = date("Y-m-d H:i:s");
   					     $url_users     = $this->params['url']['url'];
   						 $ip_users      = $this->RequestHandler->getClientIP();
   						 $modulo_users  = "script_correciones";
   						 $sql = "INSERT INTO monitor_actividad (get_, post_, usuario, session_, fecha, url_, ip, modulo) ";
		                 $sql .= "VALUES('$get_users','$post_users','$usuario_users','$session_users','$fecha_dia','$url_users','$ip_users','$modulo_users')";
		                 $resultado = $this->cfpd21_numero_asiento_compromiso->execute($sql);

				}// fin foreach

	    	      	  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    	      	  $this->set('Message_existe',  "Eliminado con exito");
	    	      	  $this->log("PANEL_SCRIPT: ELIMINACION DE OFICIO CON MOVIMIENTOS [$numero_oficio]\nINICIO".$LOG_DATA_REF."\nFIN", LOG_ELIMINAR);
	    	     // }else{
	    	     // 	  $this->set('errorMessage',  "Disculpe no se puede proceder a eliminar el oficio");
	    	     // }
              }else{
              	 $this->set('errorMessage',  "Disculpe Datos no encontrados, verifique numero del oficio");
              }
    	 }else{
    	 	$this->set('errorMessage',  "Por favor seleccione la dependencia e ingrese el numero del oficio");
    	 }

    }else{

    }

}//fin eliminar_decreto


////////777

function cambiar_fecha_cheque($var=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
 $this->layout="ajax";

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;


 $this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

		       if($var==1){


		        $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
		     	$this->concatena($nom, 'arr05');

		  }else if($var==2){

                $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
     	        $this->concatena($nom, 'arr05');


     	  }else if($var==3){

                $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
     	        $this->concatena($nom, 'arr05');


     	   }else if($var==4){


                  $cod_dep             =     $this->data["datos"]["cod_dep"];
                  $entidad_bancaria    =     $this->data["datos"]["entidad_bancaria"];
                  $sucursal_bancaria   =     $this->data["datos"]["sucursal_bancaria"];
                  $cuenta_bancaria     =     $this->data["datos"]["cuenta_bancaria"];
                  $sw                  =     0;
                   $var11 = 2;
                  if(!empty($this->data["datos"]["numero_cheque"])){
                   $numero_cheque       =     $this->data["datos"]["numero_cheque"];
                   if($this->cstd03_cheque_cuerpo->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."'  and  cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque='".$numero_cheque."' ") != 0){
                      $cstd03_cheque_cuerpo = $this->cstd03_cheque_cuerpo->findAll("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."'  and  cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque='".$numero_cheque."' ");
                      $numero_compropante = $cstd03_cheque_cuerpo[0]["cstd03_cheque_cuerpo"]["numero_comprobante_egreso"];
                      $sw = $this->arrd05->execute("select borrar_cheques('".$cod_dep."', '".$ano_ejecucion."', '".$entidad_bancaria."', '".$sucursal_bancaria."', '".$cuenta_bancaria."', '".$numero_cheque."', '".$numero_compropante."'  );");

                   }//fin if
                  }else if($var1==2){
                  	$numero_cheque =  ""; $var11 = 2;
                  	if($this->cstd03_cheque_cuerpo->findCount("cod_presi='".$cod_presi."'  and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."'  and  cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."' ") != 0){
                      $sw = $this->arrd05->execute("select borrar_cheques_cuenta('".$cod_dep."', '".$ano_ejecucion."', '".$entidad_bancaria."', '".$sucursal_bancaria."', '".$cuenta_bancaria."');");
                     }//fin if

                  }//fin else


                  if($var6!=null && !empty($this->data["datos"]["cuenta_bancaria"]) && $var1==3){
                          $var11 = 1;
                          $cod_dep             =     $this->data["datos"]["cod_dep"];
		                  $entidad_bancaria    =     $this->data["datos"]["entidad_bancaria"];
		                  $sucursal_bancaria   =     $this->data["datos"]["sucursal_bancaria"];
		                  $cuenta_bancaria     =     $this->data["datos"]["cuenta_bancaria"];
                        $this->set('var1', $var1);
						$this->set('var2', $var2);
						$this->set('var3', $var3);
						$this->set('var4', $var4);
						$this->set('var5', $var5);
						$this->set('var6', $var6);
						$ano_ejecucion = $this->Session->read('ano_ejecucion');
						 $cond2   = $condicion." and  cod_dep='".$cod_dep."' and cod_entidad_bancaria=".$entidad_bancaria." and cod_sucursal=".$sucursal_bancaria." and cuenta_bancaria='".$cuenta_bancaria."' and condicion_actividad=2 and ano_movimiento='".$ano_ejecucion."'  ";
                        $lista=  $this->cstd03_cheque_cuerpo->generateList($cond2, 'numero_cheque ASC', null, '{n}.cstd03_cheque_cuerpo.numero_cheque', '{n}.cstd03_cheque_cuerpo.numero_cheque');
                     if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
                  }//fin if





		              if($sw>1){
                       $this->set('Message_existe', 'Cheque eliminado correctamente');
		              }else{
                        $this->set('errorMessage', 'Cheque no pudo ser eliminado ');
		              }//fin else




                    $this->set('var11', $var11);



          }else if($var==5){


					          	 if(!empty($this->data["datos"]["cod_dep"]) ||
						          	 !empty($this->data["datos"]["entidad_bancaria"]) ||
						          	 !empty($this->data["datos"]["sucursal_bancaria"]) ||
						          	 !empty($this->data["datos"]["cuenta_bancaria"]) ||
						          	 !empty($this->data["datos"]["tipo"]) ||
						          	 !empty($this->data["datos"]["fecha"]) ||
						          	 !empty($this->data["datos"]["numero_cheque"])
					          	 ){
					          	      $cod_dep             =     $this->data["datos"]["cod_dep"];
					                  $entidad_bancaria    =     $this->data["datos"]["entidad_bancaria"];
					                  $sucursal_bancaria   =     $this->data["datos"]["sucursal_bancaria"];
					                  $cuenta_bancaria     =     $this->data["datos"]["cuenta_bancaria"];
					                  $numero_cheque       =     $this->data["datos"]["numero_cheque"];
					                  $tipo                =     $this->data["datos"]["tipo"];
					                  $fecha               =     $this->data["datos"]["fecha"];
					                  if($tipo==2){
                                           $R1 = $this->cstd03_cheque_cuerpo->execute("UPDATE cstd03_cheque_cuerpo SET  fecha_proceso_anulacion='".$fecha."'    WHERE  cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$entidad_bancaria."' and   cod_sucursal='".$sucursal_bancaria."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_cheque='".$numero_cheque."' and ano_movimiento='".$ano_ejecucion."'  ");
                                      }else{
                                           $R1 = $this->cstd03_cheque_cuerpo->execute("UPDATE cstd03_cheque_cuerpo SET     fecha_cheque='".$fecha."'    WHERE  cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$entidad_bancaria."' and   cod_sucursal='".$sucursal_bancaria."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_cheque='".$numero_cheque."' and ano_movimiento='".$ano_ejecucion."'  ");
                                           $R1 = $this->cstd03_cheque_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET  fecha_cheque='".$fecha."'    WHERE  cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$entidad_bancaria."' and   cod_sucursal='".$sucursal_bancaria."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_cheque='".$numero_cheque."' and ano_orden_pago='".$ano_ejecucion."'  ");
                                      }//fin if
							              if($R1>1){
					                       $this->set('Message_existe', 'la fecha fue cambiada correctamente');
					                       $this->set("mensaje", 'la fecha fue cambiada correctamente');
							              }else{
					                        $this->set('errorMessage', 'No pudo ser cambiada la fecha');
					                        $this->set("mensaje", 'No pudo ser cambiada la fecha');
							              }//fin else
					          	 }else{
					                      $this->set('errorMessage', 'Faltan Datos');
					                      $this->set("mensaje", 'Faltan Datos');
					          	 }//fin else

		  }//fin



$this->set('var', $var);



}//fin funtion

function select_cheque_fecha($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$var3;

//$var1 = si son todos o uno en especifico
//$var2 = opcion
//$var3 = dependencia
//$var4 = entidad  bancaria
//$var5 = sucursal bancaria
//$var6 = cuenta   bancaria

		switch($var2){

			        case 'entidad':

			           if($var3!=null){
                       $this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
			           }else{
			           	$lista = array();
			           	$this->set('tipo', $lista);
			           }
                       $var2 = "sucursal";
                    break;


			        case 'sucursal':
			            $cond =" cod_entidad_bancaria=".$var4;
						$lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
			           if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->concatena($lista, 'vector');}
					   $var2 = "cuenta";
					break;


					case 'cuenta':
					     $cond  =  $condicion." and cod_entidad_bancaria=".$var4." and cod_sucursal=".$var5;
						 $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
                      if($lista==""){$lista = array(); $this->concatena($lista,'vector');}else{$this->concatena($lista,'vector');}
					     $var2 = "cheque";
					break;


					case 'cheque':
                          $cond2   = $condicion." and cod_entidad_bancaria=".$var4." and cod_sucursal=".$var5." and cuenta_bancaria='".$var6."' and condicion_actividad=2 and ano_movimiento='".$ano_ejecucion."' ";
                          $lista=  $this->cstd03_cheque_cuerpo->generateList($cond2, 'numero_cheque ASC', null, '{n}.cstd03_cheque_cuerpo.numero_cheque', '{n}.cstd03_cheque_cuerpo.numero_cheque');
                          if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
                          if($var1==3){$var2 = "lista_cheque";}else if($var1==2){$var2 = "final";}

					break;


					case 'lista_cheque':
                          $var2 = "final";
					break;


		}//fin switch


		$this->set('var1', $var1);
		$this->set('var2', $var2);
		$this->set('var3', $var3);
		$this->set('var4', $var4);
		$this->set('var5', $var5);
		$this->set('var6', $var6);


}//fin funcction

//////////////////////////////////////////////////////77



function cambiar_fecha_movimientos_manuales($var=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $this->layout="ajax";

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
  $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
		     	$this->concatena($nom, 'arr05');

  // pr($this->data);
          if($var==5){
					          	 if(!empty($this->data["datos"]["cod_dep"]) && !empty($this->data["datos"]["cod_entidad"]) && !empty($this->data["datos"]["cod_sucursal"]) && !empty($this->data["datos"]["cod_cuenta_bancaria"]) && !empty($this->data["datos"]["cod_tipo_documento"]) && !empty($this->data["datos"]["fecha"]) && !empty($this->data["datos"]["cod_numero_documento"])){
					          	      $cod_dep             =     $this->data["datos"]["cod_dep"];
					                  $entidad_bancaria    =     $this->data["datos"]["cod_entidad"];
					                  $sucursal_bancaria   =     $this->data["datos"]["cod_sucursal"];
					                  $cuenta_bancaria     =     $this->data["datos"]["cod_cuenta_bancaria"];
					                  $numero_cheque       =     $this->data["datos"]["cod_numero_documento"];
					                  $tipo                =     $this->data["datos"]["cod_tipo_documento"];
					                  $fecha               =     $this->data["datos"]["fecha"];
					                  $fdc=explode('/',$fecha);
                                      $fecha_anulacion_nueva=$fdc[2]."-".$fdc[1]."-".$fdc[0];
					                  $SQL="SELECT update_fecha_anulacion_mm(".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.", ".$ano_ejecucion.", ".$entidad_bancaria.", ".$sucursal_bancaria.",'".$cuenta_bancaria."', ".$numero_cheque.", ".$tipo.",'".$fecha_anulacion_nueva."')";
                                      //echo $SQL;
                                      $R1 = $this->cstd03_cheque_cuerpo->execute($SQL);

							              if($R1>1){
					                       $this->set('Message_existe', 'la fecha fue cambiada correctamente');
					                       //$this->set("mensaje", 'la fecha fue cambiada correctamente');
							              }else{
					                        $this->set('errorMessage', 'No pudo ser cambiada la fecha');
					                        //$this->set("mensaje", 'No pudo ser cambiada la fecha');
							              }//fin else
					          	 }else{
					                      $this->set('errorMessage', 'Faltan Datos');
					                      //$this->set("mensaje", 'Faltan Datos');
					          	 }//fin else
					          	 $this->set("guardando",true);
		  }//fin
$this->set('var', $var);
}//fin funcion cambiar_fecha_movimientos_manuales

function select_fecha_movimientos_manuales($select=null, $var=null){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
  //echo $select;
    switch($select){

		case 'entidad':
			$this->set('SELECT','sucursal');
			$this->set('codigo','entidad');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('DEP_CHE',$var);
			$this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'vector');
		break;
		case 'sucursal':
			$this->set('SELECT','cuenta_bancaria');
			$this->set('codigo','sucursal');
			$this->set('seleccion','');
			$this->set('n',3);
			$this->Session->write('ENT_BANCARIA',$var);
			$cond =" cod_entidad_bancaria=".$var;
		    $lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
			if(!is_array($lista)){$lista = array(); $this->set('vector', $lista);}else{$this->concatena($lista, 'vector');}
		break;
		case 'cuenta_bancaria':
			$this->set('SELECT','tipo_documento');
			$this->set('codigo','cuenta_bancaria');
			$this->set('seleccion','');
			$this->set('n',4);
			$cod_dep =  $this->Session->read('DEP_CHE');
			$cod_entidad =  $this->Session->read('ENT_BANCARIA');
			$this->Session->write('COD_SUCURSAL',$var);
			$cond  =  $condicion." and cod_dep=".$cod_dep." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$var;
		    $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
            if(!is_array($lista)){$lista = array(); $this->concatena($lista,'vector');}else{$this->concatena($lista,'vector');}
		break;
		case 'tipo_documento':
			$this->set('SELECT','numero_documento');
			$this->set('codigo','tipo_documento');
			$this->set('seleccion','');
			$this->set('n',5);
			$this->Session->write('CUENTA_BANCARIA',$var);
			$tipo= array('1'=>'Deposito','2'=>'Nota de Crédito','3'=>'Nota de debito','4'=>'Cheque');
            $this->concatena($tipo,'vector');
		break;
		case 'numero_documento':
			$this->set('SELECT','fecha');
			$this->set('codigo','numero_documento');
			$this->set('seleccion','');
			$this->set('n',6);
			$cod_dep      =  $this->Session->read('DEP_CHE');
			$cod_entidad  =  $this->Session->read('ENT_BANCARIA');
			$cod_sucursal =  $this->Session->read('COD_SUCURSAL');
			$cuenta       =  $this->Session->read('CUENTA_BANCARIA');
			$this->Session->write('TIPO_DOC',$var);
			$cond2   = $condicion." and cod_dep=".$cod_dep." and ano_movimiento=".$ano_ejecucion." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$cod_sucursal." and tipo_documento=".$var." and cuenta_bancaria='".$cuenta."' and condicion_actividad=2 ";
            $lista=  $this->cstd03_movimientos_manuales->generateList($cond2, 'numero_documento ASC', null, '{n}.cstd03_movimientos_manuales.numero_documento', '{n}.cstd03_movimientos_manuales.numero_documento');
            if(!is_array($lista)){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
		break;
		case 'fecha':
			$this->set('SELECT','TERMINO');
			$this->set('codigo','fecha');
			$this->set('seleccion','');
			$this->set('n',7);
			$this->Session->write('NRO_DOCUMENTO',$var);
		break;



    }//fin switch

}//fin funcion select_fecha_movimientos_manuales


//////////7
//ELIMINAR CHEQUES MOVIMIENTOS


function eliminar_movimientos_manuales($var=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $this->layout="ajax";

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
  $nom = $this->arrd05->generateList($this->condicionNDEP_script(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
		     	$this->concatena($nom, 'arr05');

  // pr($this->data);
          if($var==5){
					          	 if(!empty($this->data["datos"]["cod_dep"]) && !empty($this->data["datos"]["cod_entidad"]) && !empty($this->data["datos"]["cod_sucursal"]) && !empty($this->data["datos"]["cod_cuenta_bancaria"]) && !empty($this->data["datos"]["cod_tipo_documento"]) &&  !empty($this->data["datos"]["cod_numero_documento"])){
					          	      $cod_dep             =     $this->data["datos"]["cod_dep"];
					                  $entidad_bancaria    =     $this->data["datos"]["cod_entidad"];
					                  $sucursal_bancaria   =     $this->data["datos"]["cod_sucursal"];
					                  $cuenta_bancaria     =     $this->data["datos"]["cod_cuenta_bancaria"];
					                  $numero_cheque       =     $this->data["datos"]["cod_numero_documento"];
					                  $tipo                =     $this->data["datos"]["cod_tipo_documento"];
					                  //eliminacion_cheque_mm(integer, integer, integer, integer, integer, integer, integer, integer, character varying, integer, integer)
					                  $SQL="SELECT eliminacion_cheque_mm(".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.", ".$ano_ejecucion.", ".$entidad_bancaria.", ".$sucursal_bancaria.",'".$cuenta_bancaria."', ".$numero_cheque.", ".$tipo.")";

					                  if ($tipo==1){
					     $get_users     = "script_correciones_panel/eliminar_movimientos_manuales de la dependencia: ".$cod_dep." entidad bancaria ".$entidad_bancaria." sucursal ".$sucursal_bancaria." cuenta bancaria ".$cuenta_bancaria." deposito ".$numero_cheque;
					                  }
					                  if ($tipo==2){
					     $get_users     = "script_correciones_panel/eliminar_movimientos_manuales de la dependencia: ".$cod_dep." entidad bancaria ".$entidad_bancaria." sucursal ".$sucursal_bancaria." cuenta bancaria ".$cuenta_bancaria." nota de credito ".$numero_cheque;
					                  }
					                  if ($tipo==3){
					     $get_users     = "script_correciones_panel/eliminar_movimientos_manuales de la dependencia: ".$cod_dep." entidad bancaria ".$entidad_bancaria." sucursal ".$sucursal_bancaria." cuenta bancaria ".$cuenta_bancaria." nota de debito ".$numero_cheque;
					                  }
					                  if ($tipo==4){
					     $get_users     = "script_correciones_panel/eliminar_movimientos_manuales de la dependencia: ".$cod_dep." entidad bancaria ".$entidad_bancaria." sucursal ".$sucursal_bancaria." cuenta bancaria ".$cuenta_bancaria." cheque ".$numero_cheque;
					                  }


   						 $post_users    = $get_users;
   						 $usuario_users = "ADMIN";
   						 $session_users = "script_correciones_panel/eliminar_movimientos_manuales";
   						 $fecha_dia     = date("Y-m-d H:i:s");
   					     $url_users     = $this->params['url']['url'];
   						 $ip_users      = $this->RequestHandler->getClientIP();
   						 $modulo_users  = "script_correciones";
   						 $sql = "INSERT INTO monitor_actividad (get_, post_, usuario, session_, fecha, url_, ip, modulo) ";
		                 $sql .= "VALUES('$get_users','$post_users','$usuario_users','$session_users','$fecha_dia','$url_users','$ip_users','$modulo_users')";
		                 $resultado = $this->cfpd21_numero_asiento_compromiso->execute($sql);

                                     // echo $SQL;

                                      $R2 = 2;


                                      if($tipo==3){

                                      	   $sql_aux_compara         = "cod_dep='".$cod_dep."' AND ano_movimiento='".$ano_ejecucion."' and cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."'  and numero_debito='".$numero_cheque."'";
                                      	   $cont_cstd09_notadebito  = $this->cstd09_notadebito_cuerpo_pago->findCount($sql_aux_compara);

                                           if($cont_cstd09_notadebito!=0){

		                                      	   $sql_aux_compara = "cod_dep='".$cod_dep."' AND ano_movimiento='".$ano_ejecucion."' and cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."'  and numero_debito='".$numero_cheque."' and condicion_actividad=2";
		                                      	   $cont            = $this->cstd09_notadebito_cuerpo_pago->findCount($sql_aux_compara);

		                                      	   if($cont!=0){

					                                   $sql_delete  = "DELETE FROM cstd09_notadebito_cuerpo          WHERE cod_dep='".$cod_dep."' AND ano_movimiento='".$ano_ejecucion."' and cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."'  and numero_debito='".$numero_cheque."'; ";
												       $sql_delete .= "DELETE FROM cstd09_notadebito_partidas        WHERE cod_dep='".$cod_dep."' AND ano_movimiento='".$ano_ejecucion."' and cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."'  and numero_debito='".$numero_cheque."'; ";
												       $sql_delete .= "DELETE FROM cstd09_notadebito_poremitir       WHERE cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."' and cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."'  and numero_debito='".$numero_cheque."'; ";
												       $sql_delete .= "DELETE FROM cstd09_notadebito_ordenes         WHERE cod_dep='".$cod_dep."' and ano_movimiento='".$ano_ejecucion."' and cod_entidad_bancaria='".$entidad_bancaria."' and cod_sucursal='".$sucursal_bancaria."' and cuenta_bancaria='".$cuenta_bancaria."'  and numero_debito='".$numero_cheque."'; ";

			                                           $R1 = $this->cstd03_cheque_cuerpo->execute($SQL);
			                                           $R2 = $this->cstd03_cheque_cuerpo->execute($sql_delete);


		                                      	   }else{

		                                      	   	 $R1 = 0;

		                                      	   }//fin else

                                             }else{

                                           	     $R1 = $this->cstd03_cheque_cuerpo->execute($SQL);

                                             }//fin else


                                       }else{

                                      	 $R1 = $this->cstd03_cheque_cuerpo->execute($SQL);

                                       }//fin else



							              if($R1>1 && $R2>1){
					                       $this->set('Message_existe', 'El registro fue eliminado correctamente');
					                       $this->log("PANEL_SCRIPT: ELIMINACION DE MOVIMIENTOS MANUALES [".$cod_dep.", ".$ano_ejecucion.", ".$entidad_bancaria.", ".$sucursal_bancaria.",'".$cuenta_bancaria."', ".$numero_cheque.", ".$tipo."]", LOG_ELIMINAR);

					                       //$this->set("mensaje", 'la fecha fue cambiada correctamente');
							              }else{
					                        $this->set('errorMessage', 'No pudo eliminar el documento');
					                        //$this->set("mensaje", 'No pudo ser cambiada la fecha');
							              }//fin else
					          	$cod_dep      =  $this->data["datos"]["cod_dep"];
								$cod_entidad  =  $this->data["datos"]["cod_entidad"];
								$cod_sucursal =  $this->data["datos"]["cod_sucursal"];
								$cuenta       =  $this->data["datos"]["cod_cuenta_bancaria"];
								$cond2   = $condicion." and cod_dep=".$cod_dep." and ano_movimiento=".$ano_ejecucion." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$cod_sucursal." and tipo_documento=".$tipo." and cuenta_bancaria='".$cuenta."' and condicion_actividad=2 ";
					            $lista=  $this->cstd03_movimientos_manuales->generateList($cond2, 'numero_documento ASC', null, '{n}.cstd03_movimientos_manuales.numero_documento', '{n}.cstd03_movimientos_manuales.numero_documento');
					            //echo $cond2;
					            if(!is_array($lista)){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
					          	 $this->set("guardando",true);
					          	 }else{
					                      $this->set('errorMessage', 'Faltan Datos');
					                      //$this->set("mensaje", 'Faltan Datos');
					          	 }//fin else


		  }//fin
$this->set('var', $var);
}//fin funcion eliminar_movimientos_manuales

function select_eliminar_movimientos_manuales($select=null, $var=null){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
  //echo $select;
    switch($select){

		case 'entidad':
			$this->set('SELECT','sucursal');
			$this->set('codigo','entidad');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('DEP_CHE',$var);
			$this->set('vector', $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));
		break;
		case 'sucursal':
			$this->set('SELECT','cuenta_bancaria');
			$this->set('codigo','sucursal');
			$this->set('seleccion','');
			$this->set('n',3);
			$this->Session->write('ENT_BANCARIA',$var);
			$cond =" cod_entidad_bancaria=".$var;
		    $lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
			if(!is_array($lista)){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
		break;
		case 'cuenta_bancaria':
			$this->set('SELECT','tipo_documento');
			$this->set('codigo','cuenta_bancaria');
			$this->set('seleccion','');
			$this->set('n',4);
			$cod_dep =  $this->Session->read('DEP_CHE');
			$cod_entidad =  $this->Session->read('ENT_BANCARIA');
			$this->Session->write('COD_SUCURSAL',$var);
			$cond  =  $condicion." and cod_dep=".$cod_dep." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$var;
		    $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
            if(!is_array($lista)){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
		break;
		case 'tipo_documento':
			$this->set('SELECT','numero_documento');
			$this->set('codigo','tipo_documento');
			$this->set('seleccion','');
			$this->set('n',5);
			$this->Session->write('CUENTA_BANCARIA',$var);
			$tipo= array('1'=>'Deposito','2'=>'Nota de Crédito','3'=>'Nota de debito','4'=>'Cheque');
            $this->concatena($tipo,'vector');
		break;
		case 'numero_documento':
			$this->set('SELECT','boton');
			$this->set('codigo','numero_documento');
			$this->set('seleccion','');
			$this->set('n',6);
			$cod_dep      =  $this->Session->read('DEP_CHE');
			$cod_entidad  =  $this->Session->read('ENT_BANCARIA');
			$cod_sucursal =  $this->Session->read('COD_SUCURSAL');
			$cuenta       =  $this->Session->read('CUENTA_BANCARIA');
			$this->Session->write('TIPO_DOC',$var);
			$cond2   = $condicion." and cod_dep=".$cod_dep." and ano_movimiento=".$ano_ejecucion." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$cod_sucursal." and tipo_documento=".$var." and cuenta_bancaria='".$cuenta."' and condicion_actividad=2 ";
            $lista=  $this->cstd03_movimientos_manuales->generateList($cond2, 'numero_documento ASC', null, '{n}.cstd03_movimientos_manuales.numero_documento', '{n}.cstd03_movimientos_manuales.numero_documento');
            if(!is_array($lista)){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
		break;
		case 'boton':
			$this->set('SELECT','TERMINO');
			$this->set('codigo','boton');
			$this->set('seleccion','');
			$this->set('n',7);
			$this->Session->write('NRO_DOCUMENTO',$var);
		break;



    }//fin switch

}//fin funcion select_eliminar_movimientos_manuales






function reactualizacion_obras_anticipo_anulado(){
	$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $partidas=$this->cobd01_contratoobras_anticipo_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.numero_anticipo,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_anticipo,b.fecha_proceso_registro,b.fecha_proceso_anulacion,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.observaciones as concepto
   from
  cobd01_contratoobras_anticipo_partidas a, cobd01_contratoobras_anticipo_cuerpo b
where
  b.condicion_actividad      =   2                            and
  a.cod_presi                =   b.cod_presi                  and
  a.cod_entidad              =   b.cod_entidad                and
  a.cod_tipo_inst            =   b.cod_tipo_inst              and
  a.cod_inst                 =   b.cod_inst                   and
  a.cod_dep                  =   b.cod_dep                    and
  a.ano_contrato_obra        =   b.ano_contrato_obra          and
  a.numero_contrato_obra     =   b.numero_contrato_obra       and
  a.numero_anticipo          =   b.numero_anticipo            and
  a.ano_contrato_obra        =   '".YEAR_REACTUALIZACION."'   and
  b.saldo_ano_anterior       =   2   ");

    $j =0;
	$i =0;

	foreach($partidas as $partida){

		  $cod_presi         = $partida[0]['cod_presi'];
		  $cod_entidad       = $partida[0]['cod_entidad'];
		  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
		  $cod_inst          = $partida[0]['cod_inst'];
		  $cod_dep           = $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento    = $partida[0]['ano_contrato_obra'];
		  $fr                = $partida[0]['fecha_proceso_anulacion'];
		  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
	      $fd                =         $partida[0]['fecha_proceso_anulacion'];
		  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
		  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_fd            = (int) $ano_fd;
		  $ano_fdpartida     = (int) $partida[0]['ano'];

				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $ano_orden_compra  = $partida[0]['ano_contrato_obra'];
		  $ndo               = $partida[0]['numero_contrato_obra'];
		  $nda               = $partida[0]['numero_anticipo'];
		  $ano               = $partida[0]['ano'];
		  $cod_sector        = $partida[0]['cod_sector'];
		  $cod_programa      = $partida[0]['cod_programa'];
		  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
		  $cod_proyecto      = $partida[0]['cod_proyecto'];
		  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
		  $cod_partida       = $partida[0]['cod_partida'];
		  $cod_generica      = $partida[0]['cod_generica'];
		  $cod_especifica    = $partida[0]['cod_especifica'];
		  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
		  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
		  $monto             = $partida[0]['monto'];
		  $numero_compromiso = $partida[0]['numero_control_compromiso'];
		  $numero_causado    = $partida[0]['numero_control_causado'];
		  $concepto          = $partida[0]['concepto'];
		  $ano_orden_pago    = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];

          $busca_concepto_anulacion = $this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."'  and tipo_operacion='244' and numero_documento='".$ndo."'");
          $concepto_anulacion       = $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

		  $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

/* LA ORDEN DE PAGO SE ENCARGA DE ACTUALIZAR EN EL MOTOR

		  $dnco = $this->motor_presupuestario($cp, 2, 4, 4, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $ndo, $nda, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);
		  $j++;
	      $i++;
*/

	}//fin for

$this->layout = "ajax";
$this->set('mensaje', "Causado anticipo contrato obras realizado con exito");
         $this->set('siguiente', 'reactualizacion_obras_anticipo');
     	 $this->set('pagina',   null);
$this->render('vista_index');


}//reactualizacion_obras_anticipo_anulado


//****************************************CONTRATO ANTICIPOS*********************************//







function reactualizacion_servicios_anticipo_anulado(){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
  $partidas=$this->cepd02_contratoservicio_anticipo_cuerpo->execute("select
		  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.ano_contrato_servicio,
		  a.numero_contrato_servicio,
		  a.numero_anticipo,
		  a.ano,
		  a.cod_sector,
		  a.cod_programa,
		  a.cod_sub_prog,
		  a.cod_proyecto,
		  a.cod_activ_obra,
		  a.cod_partida,
		  a.cod_generica,
		  a.cod_especifica,
		  a.cod_sub_espec,
		  a.cod_auxiliar,
		  a.monto,
		  a.numero_control_compromiso,
		  a.numero_control_causado,
		  b.fecha_anticipo,b.fecha_proceso_registro,
		  b.condicion_actividad,
		  b.ano_orden_pago,
		  b.numero_orden_pago,
		  b.observaciones as concepto
		   from
		  cepd02_contratoservicio_anticipo_partidas a,cepd02_contratoservicio_anticipo_cuerpo b
		where
		  b.condicion_actividad      =   2                            and
		  a.cod_presi                =   b.cod_presi                  and
		  a.cod_entidad              =   b.cod_entidad                and
		  a.cod_tipo_inst            =   b.cod_tipo_inst              and
		  a.cod_inst                 =   b.cod_inst                   and
		  a.cod_dep                  =   b.cod_dep                    and
		  a.ano_contrato_servicio    =   b.ano_contrato_servicio      and
		  a.numero_contrato_servicio =   b.numero_contrato_servicio   and
		  a.numero_anticipo          =   b.numero_anticipo            and
		  a.ano_contrato_servicio    =   '".YEAR_REACTUALIZACION."'   and
		  b.saldo_ano_anterior       =   2 ");

    $j =0;
	$i =0;

	foreach($partidas as $partida){
		  $cod_presi         = $partida[0]['cod_presi'];
		  $cod_entidad       = $partida[0]['cod_entidad'];
		  $cod_tipo_inst     = $partida[0]['cod_tipo_inst'];
		  $cod_inst          = $partida[0]['cod_inst'];
		  $cod_dep           = $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento    = $partida[0]['ano_contrato_servicio'];
		  $fr                = $partida[0]['fecha_proceso_anulacion'];
		  $fecha_registro    = $fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
		  $fd                = $partida[0]['fecha_proceso_anulacion'];
		  $ano_fd            = $fd[0].$fd[1].$fd[2].$fd[3];
		  $fd                = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_fd            = (int) $ano_fd;
		  $ano_fdpartida     = (int) $partida[0]['ano'];

				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $ano_orden_compra  = $partida[0]['ano_contrato_servicio'];
		  $ndo               = $partida[0]['numero_contrato_servicio'];
		  $nda               = $partida[0]['numero_anticipo'];
		  $ano               = $partida[0]['ano'];
		  $cod_sector        = $partida[0]['cod_sector'];
		  $cod_programa      = $partida[0]['cod_programa'];
		  $cod_sub_prog      = $partida[0]['cod_sub_prog'];
		  $cod_proyecto      = $partida[0]['cod_proyecto'];
		  $cod_activ_obra    = $partida[0]['cod_activ_obra'];
		  $cod_partida       = $partida[0]['cod_partida'];
		  $cod_generica      = $partida[0]['cod_generica'];
		  $cod_especifica    = $partida[0]['cod_especifica'];
		  $cod_sub_espec     = $partida[0]['cod_sub_espec'];
		  $cod_auxiliar      = $partida[0]['cod_auxiliar'];
		  $monto             = $partida[0]['monto'];
		  $numero_compromiso = $partida[0]['numero_control_compromiso'];
		  $numero_causado    = $partida[0]['numero_control_causado'];
		  $concepto          = $partida[0]['concepto'];
		  $ano_orden_pago    = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];

          $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."'  and tipo_operacion='246' and numero_documento='".$ndo."'");
          $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

          $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

/* LA ORDEN DE PAGO SE ENCARGA DE ACTUALIZAR EL MOTOR
		  $dnco = $this->motor_presupuestario($cp, 2, 4, 6, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $ndo, $nda, null, null, null, null, null, null, $numero_compromiso, $numero_causado, null, null, $j, null, null);
          $j++;
	      $i++;
*/

	}//fin foreach

$this->layout = "ajax";
$this->set('mensaje', "Causado anticipo C.C realizado con exito");
$this->render('vista_index');

}//reactualizacion_servicios_anticipo_anulado



//****************************************SERVICIOS ANTICIPOS*********************************//







function ver_anticipos_por_una_partida(){
$data_control_pane = $this->a_control_panel->findAll($this->condicionNDEP_script());
 if(!defined('CONTABILIDAD_FISCAL')){
				define('CONTABILIDAD_FISCAL',$data_control_pane[0]['a_control_panel']['contabilidad']);
 }
 if(!defined('YEAR_REACTUALIZACION')){
				define('YEAR_REACTUALIZACION',$data_control_pane[0]['a_control_panel']['ano_reactualizacion']);
 }
 if(!defined('CODIGOSINSTREACTUALIZAR')){
 	$codigosint = $data_control_pane[0]['a_control_panel']['cod_presi'].", ".$data_control_pane[0]['a_control_panel']['cod_entidad'].", ".$data_control_pane[0]['a_control_panel']['cod_tipo_inst'].", ".$data_control_pane[0]['a_control_panel']['cod_inst']." ";
				define('CODIGOSINSTREACTUALIZAR',$codigosint);
 }
	$this->layout = "ajax";



$partidas = $this->cobd01_contratoobras_anticipo_cuerpo->execute("select
				  a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.ano_contrato_obra,
				  a.numero_contrato_obra,
				  a.numero_anticipo,
				  a.ano,
				  a.cod_sector,
				  a.cod_programa,
				  a.cod_sub_prog,
				  a.cod_proyecto,
				  a.cod_activ_obra,
				  a.cod_partida,
				  a.cod_generica,
				  a.cod_especifica,
				  a.cod_sub_espec,
				  a.cod_auxiliar,
				  a.monto,
				  b.monto_anticipo,
				  a.numero_control_compromiso,
				  a.numero_control_causado,
				  b.fecha_anticipo,
				  b.condicion_actividad,
				  b.ano_orden_pago,
				  b.numero_orden_pago,b.fecha_proceso_registro,
				  b.observaciones as concepto,
				  ( select count(x.cod_dep)

					  from
					    	  cobd01_contratoobras_partidas x
					  where
							  x.cod_presi            = b.cod_presi                 and
							  x.cod_entidad          = b.cod_entidad               and
							  x.cod_tipo_inst        = b.cod_tipo_inst             and
							  x.cod_inst             = b.cod_inst                  and
							  x.cod_dep              = b.cod_dep                   and
							  x.ano_contrato_obra    = b.ano_contrato_obra         and
							  x.numero_contrato_obra = b.numero_contrato_obra
				  ) as cuenta_partidas



				from
				  cobd01_contratoobras_anticipo_partidas a,cobd01_contratoobras_anticipo_cuerpo b
				where
				  a.cod_presi            = b.cod_presi                 and
				  a.cod_entidad          = b.cod_entidad               and
				  a.cod_tipo_inst        = b.cod_tipo_inst             and
				  a.cod_inst             = b.cod_inst                  and
				  a.cod_dep              = b.cod_dep                   and
				  a.ano_contrato_obra    = b.ano_contrato_obra         and
				  a.numero_contrato_obra = b.numero_contrato_obra      and
				  a.numero_anticipo      = b.numero_anticipo           and
				  a.ano_contrato_obra    = '".YEAR_REACTUALIZACION."'  and
				  b.saldo_ano_anterior   = 2 ");




		foreach($partidas as $ve){

				if($ve[0]["monto"]==$ve[0]["monto_anticipo"] && $ve[0]["cuenta_partidas"]>2){

					echo $ve[0]["cod_dep"]." - ".$ve[0]["ano_contrato_obra"]." - ".$ve[0]["numero_contrato_obra"]." - ".$ve[0]["cuenta_partidas"]." <br>";

				}

		}//fin foreach





}//fin funtion




function cambiar_cedula($var1=null) {
     $this->layout="ajax";
     if($var1==1){

     }else{
                if(!empty($this->data['campo']['cedula_a']) && !empty($this->data['campo']['cedula_b'])){
                        $campo_a  =  $this->data['campo']['cedula_a'];
                        $campo_b  =  $this->data['campo']['cedula_b'];
                        $delete = $this->cnmd06_datos_personales->execute("BEGIN;
				ALTER TABLE cnmd06_fichas_historial_condicion DROP CONSTRAINT cnmd06_fichas_historial_condicion_1;
				ALTER TABLE cnmd07_transacciones_actuales_faov_tmp DROP CONSTRAINT cnmd07_ficha;
				ALTER TABLE cnmd08_historia_transacciones DROP CONSTRAINT cnmd08_historia_transacciones_1;
				ALTER TABLE cnmd10_individual_porcentaje_horas_cantidad DROP CONSTRAINT cnmd10_individual_porcentaje_horas_cantidad_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_porcentaje_cantidad_ded DROP CONSTRAINT cnmd10_individual_porcentaje_cantidad_ded_cod_presi_fkey1;
				ALTER TABLE cnmd10_individual_porcentaje_cantidad_ded DROP CONSTRAINT cnmd10_individual_porcentaje_cantidad_ded_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_porcentaje_cantidad DROP CONSTRAINT cnmd10_individual_porcentaje_cantidad_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_dias_cantidad DROP CONSTRAINT cnmd10_individual_dias_cantidad_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_cantidad_horas_cantidad DROP CONSTRAINT cnmd10_individual_cantidad_horas_cantidad_cod_presi_fkey1;
				ALTER TABLE cnmd10_individual_bolivares_cantidad_ded DROP CONSTRAINT cnmd10_individual_bolivares_cantidad_ded_cod_presi_fkey1;
				ALTER TABLE cnmd10_individual_bolivares_cantidad_ded DROP CONSTRAINT cnmd10_individual_bolivares_cantidad_ded_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_bolivares_cantidad DROP CONSTRAINT cnmd10_individual_bolivares_cantidad_cod_presi_fkey1;
				ALTER TABLE cnmd10_individual_bolivares_cantidad DROP CONSTRAINT cnmd10_individual_bolivares_cantidad_cod_presi_fkey;
				ALTER TABLE cnmd07_transacciones_actuales DROP CONSTRAINT cnmd07_ficha;
				ALTER TABLE cnmd07_calculo_bonovaca DROP CONSTRAINT cnmd07_calculo_bonovaca;
				ALTER TABLE cnmd07_calculo_aguinaldos DROP CONSTRAINT cnmd07_calculo_aguinaldo_1;
				ALTER TABLE cnmd06_datos_experiencia_administrativa DROP CONSTRAINT cnmd06_datos_experiencia_administrativa_1;
				ALTER TABLE cnmd06_datos_amonestaciones DROP CONSTRAINT cnmd06_datos_amonestaciones_1;
				ALTER TABLE cnmd06_datos_educativos DROP CONSTRAINT cnmd06_datos_educativos_1;
				ALTER TABLE cnmd06_datos_formacion_profesional DROP CONSTRAINT cnmd06_datos_formacion_profesional_1;
				ALTER TABLE cnmd06_datos_registro_titulo DROP CONSTRAINT cnmd06_datos_registro_titulo_1;
				ALTER TABLE cnmd06_datos_otrasexperiencias_laborables DROP CONSTRAINT cnmd06_datos_otrasexperiencias_laborales_1;
				ALTER TABLE cnmd06_datos_bienes DROP CONSTRAINT cnmd06_datos_bienes_1;
				ALTER TABLE cnmd06_datos_permisos DROP CONSTRAINT cnmd06_datos_permisos_1;
				ALTER TABLE cnmd08_historia_trabajador DROP CONSTRAINT cnmd08_historia_trabajador_1;
				ALTER TABLE cnmd06_datos_personales DROP CONSTRAINT cnmd06_datos_personales_pkey;
				ALTER TABLE cnmd06_datos_educativos DROP CONSTRAINT cnmd06_datos_educativos_pkey;
				ALTER TABLE cnmd06_datos_formacion_profesional DROP CONSTRAINT cnmd06_datos_formacion_profesional_pkey;
				ALTER TABLE cnmd06_datos_registro_titulo DROP CONSTRAINT cnmd06_datos_registro_titulo_pkey;
				ALTER TABLE cnmd06_datos_familiares DROP CONSTRAINT cnmd06_datos_familiares_pkey;
				ALTER TABLE cnmd06_datos_otrasexperiencias_laborables DROP CONSTRAINT cnmd06_datos_otrasexperiencias_laborables_pkey;
				ALTER TABLE cnmd06_datos_bienes DROP CONSTRAINT cnmd06_datos_bienes_pkey;
				ALTER TABLE cnmd06_soportes DROP CONSTRAINT cnmd06_soportes_pkey;
				ALTER TABLE cnmd06_datos_permisos DROP CONSTRAINT cnmd06_datos_permisos_pkey;
				ALTER TABLE cnmd06_datos_amonestaciones DROP CONSTRAINT cnmd06_datos_amonestaciones_pkey;
				ALTER TABLE cnmd06_fichas DROP CONSTRAINT cnmd06_fichas_pkey;
				ALTER TABLE cnmd08_historia_trabajador DROP CONSTRAINT cnmd08_historia_trabajador_pkey;
				ALTER TABLE cnmd06_datos_experiencia_administrativa DROP CONSTRAINT cnmd06_datos_experiencia_administrativa_pkey;
						");
if($delete > 1){
$update  = 'update cnmd06_datos_personales set cedula_identidad='.$campo_b.' where cedula_identidad='.$campo_a.';';
$update .= 'update cnmd06_datos_educativos set cedula='.$campo_b.' where cedula='.$campo_a.';';
$update .= 'update cnmd06_datos_formacion_profesional set cedula='.$campo_b.' where cedula='.$campo_a.';';
$update .= 'update cnmd06_datos_registro_titulo set cedula='.$campo_b.' where cedula='.$campo_a.';';
$update .= 'update cnmd06_datos_familiares set cedula='.$campo_b.' where cedula='.$campo_a.';';
$update .= 'update cnmd06_datos_experiencia_administrativa set cedula='.$campo_b.' where cedula='.$campo_a.';';
$update .= 'update cnmd06_datos_otrasexperiencias_laborables set cedula='.$campo_b.' where cedula='.$campo_a.';';
$update .= 'update cnmd06_datos_bienes set cedula_identidad='.$campo_b.' where cedula_identidad='.$campo_a.';';
$update .= 'update cnmd06_soportes set cedula='.$campo_b.' where cedula='.$campo_a.';';
$update .= 'update cnmd06_datos_permisos set cedula='.$campo_b.' where cedula='.$campo_a.';';
$update .= 'update cnmd06_datos_amonestaciones set cedula='.$campo_b.' where '.$this->SQLCA_noDEP().' and cedula='.$campo_a.';';
$update .= 'update cnmd06_fichas set cedula_identidad='.$campo_b.' where '.$this->SQLCA_noDEP().' and cedula_identidad='.$campo_a.';';
$update .= 'update cnmd06_fichas_historial_cambios_ascensos set cedula_identidad='.$campo_b.' where '.$this->SQLCA_noDEP().' and cedula_identidad='.$campo_a.';';
$update .= 'update cnmd08_historia_trabajador set cedula_identidad='.$campo_b.' where '.$this->SQLCA_noDEP().' and cedula_identidad='.$campo_a.';';
$upddate1 = $this->cnmd06_datos_personales->execute($update);



					if($upddate1 > 1){
					                     	$crear = $this->cnmd06_datos_personales->execute("
					ALTER TABLE cnmd06_datos_experiencia_administrativa
					ADD CONSTRAINT cnmd06_datos_experiencia_administrativa_pkey PRIMARY KEY(cedula, consecutivo);
					ALTER TABLE cnmd08_historia_trabajador
					ADD CONSTRAINT cnmd08_historia_trabajador_pkey PRIMARY KEY(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha);
					ALTER TABLE cnmd06_fichas
					ADD CONSTRAINT cnmd06_fichas_pkey PRIMARY KEY(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha);
					ALTER TABLE cnmd06_datos_amonestaciones
					ADD CONSTRAINT cnmd06_datos_amonestaciones_pkey PRIMARY KEY(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula, cod_amonestacion, consecutivo);
					ALTER TABLE cnmd06_datos_permisos
					ADD CONSTRAINT cnmd06_datos_permisos_pkey PRIMARY KEY(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha);
					ALTER TABLE cnmd06_soportes
					ADD CONSTRAINT cnmd06_soportes_pkey PRIMARY KEY(cedula, cod_soporte);
					ALTER TABLE cnmd06_datos_bienes
					ADD CONSTRAINT cnmd06_datos_bienes_pkey PRIMARY KEY(cedula_identidad, cod_bien, consecutivo);
					ALTER TABLE cnmd06_datos_otrasexperiencias_laborables
					ADD CONSTRAINT cnmd06_datos_otrasexperiencias_laborables_pkey PRIMARY KEY(cedula, consecutivo);
					ALTER TABLE cnmd06_datos_familiares
					ADD CONSTRAINT cnmd06_datos_familiares_pkey PRIMARY KEY(cedula, cod_parentesco, consecutivo);
					ALTER TABLE cnmd06_datos_registro_titulo
					ADD CONSTRAINT cnmd06_datos_registro_titulo_pkey PRIMARY KEY(cedula, cod_profesion, consecutivo);
					ALTER TABLE cnmd06_datos_formacion_profesional
					ADD CONSTRAINT cnmd06_datos_formacion_profesional_pkey PRIMARY KEY(cedula, cod_curso, consecutivo);
					ALTER TABLE cnmd06_datos_educativos
					ADD CONSTRAINT cnmd06_datos_educativos_pkey PRIMARY KEY(cedula, cod_nivel_educacion, consecutivo);
					ALTER TABLE cnmd06_datos_personales
					ADD CONSTRAINT cnmd06_datos_personales_pkey PRIMARY KEY(cedula_identidad);
					ALTER TABLE cnmd08_historia_trabajador
					ADD CONSTRAINT cnmd08_historia_trabajador_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina)
					REFERENCES cnmd08_historia_nomina (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_permisos
					ADD CONSTRAINT cnmd06_datos_permisos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_bienes
					ADD CONSTRAINT cnmd06_datos_bienes_1 FOREIGN KEY (cedula_identidad)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_otrasexperiencias_laborables
					ADD CONSTRAINT cnmd06_datos_otrasexperiencias_laborales_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_registro_titulo
					ADD CONSTRAINT cnmd06_datos_registro_titulo_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_formacion_profesional
					ADD CONSTRAINT cnmd06_datos_formacion_profesional_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_experiencia_administrativa
					ADD CONSTRAINT cnmd06_datos_experiencia_administrativa_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_amonestaciones
					ADD CONSTRAINT cnmd06_datos_amonestaciones_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_educativos
					ADD CONSTRAINT cnmd06_datos_educativos_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd07_calculo_aguinaldos
					ADD CONSTRAINT cnmd07_calculo_aguinaldo_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd07_calculo_bonovaca
					ADD CONSTRAINT cnmd07_calculo_bonovaca FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd07_transacciones_actuales
					ADD CONSTRAINT cnmd07_ficha FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_bolivares_cantidad
					ADD CONSTRAINT cnmd10_individual_bolivares_cantidad_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_bolivares_cantidad
					ADD CONSTRAINT cnmd10_individual_bolivares_cantidad_cod_presi_fkey1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_bolivares_cantidad_ded
					ADD CONSTRAINT cnmd10_individual_bolivares_cantidad_ded_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_bolivares_cantidad_ded
					ADD CONSTRAINT cnmd10_individual_bolivares_cantidad_ded_cod_presi_fkey1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_cantidad_horas_cantidad
					ADD CONSTRAINT cnmd10_individual_cantidad_horas_cantidad_cod_presi_fkey1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_dias_cantidad
					ADD CONSTRAINT cnmd10_individual_dias_cantidad_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_porcentaje_cantidad
					ADD CONSTRAINT cnmd10_individual_porcentaje_cantidad_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_porcentaje_cantidad_ded
					ADD CONSTRAINT cnmd10_individual_porcentaje_cantidad_ded_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_porcentaje_cantidad_ded
					ADD CONSTRAINT cnmd10_individual_porcentaje_cantidad_ded_cod_presi_fkey1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_porcentaje_horas_cantidad
					ADD CONSTRAINT cnmd10_individual_porcentaje_horas_cantidad_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd08_historia_transacciones
					ADD CONSTRAINT cnmd08_historia_transacciones_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd08_historia_trabajador (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_fichas_historial_condicion
  					ADD CONSTRAINT cnmd06_fichas_historial_condicion_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
      				REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
      				ON UPDATE NO ACTION ON DELETE CASCADE;
      				ALTER TABLE cnmd07_transacciones_actuales_faov_tmp
  					ADD CONSTRAINT cnmd07_ficha FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
      				REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
     			 	ON UPDATE NO ACTION ON DELETE CASCADE;
     			 			");



					if($crear > 1){
						$this->set('mensaje', 'Datos actualizados con exito.');
						$this->cnmd06_datos_personales->execute('COMMIT;');
					}else{
						$this->set('mensaje', 'No se puedo actualizar los datos.');
						$this->cnmd06_datos_personales->execute('ROLLBACK;');

					}

					}else{
						$this->set('mensaje', 'No se puedo actualizar los datos.');
						$this->cnmd06_datos_personales->execute('ROLLBACK;');
					}



					}else{
						$this->set('mensaje', 'No se puedo actualizar los datos.');
						$this->cnmd06_datos_personales->execute('ROLLBACK;');
					}








}else{
	$this->set('mensaje', 'Faltan datos.');
}


                }
	$this->set('var1', $var1);

}///fin function














}//fin class



?>