<?php

class Reporte4Controller extends AppController{


    var $name    = "reporte4";
    var $uses    = array('arrd05','csrd01_solicitud_recurso_cuerpo', 'csrd01_solicitud_recurso_partidas', 'csrd01_tipo_solicitud',
                         'ccfd04_cierre_mes', 'cstd09_notadebito_cuerpo_pago', 'cstd09_notadebito_partidas_pago', 'cstd09_notadebito_ordenes',
                         'cstd09_notadebito_poremitir', 'cstd06_comprobante_poremitir_iva', 'cstd06_comprobante_poremitir_egreso',
                         'cstd06_comprobante_poremitir_municipal', 'cstd06_comprobante_poremitir_timbre', 'cstd06_comprobante_poremitir_islr',
                         'cstd01_entidades_bancarias', 'cstd01_sucursales_bancarias', 'cstd02_cuentas_bancarias', 'cstd06_comprobante_cuerpo_egreso',
                         'cugd03_acta_anulacion_numero', 'cstd06_comprobante_cuerpo_egreso', 'cstd06_comprobante_cuerpo_islr', 'cstd06_comprobante_cuerpo_iva',
                         'cstd06_comprobante_cuerpo_municipal', 'cstd06_comprobante_cuerpo_timbre', 'cugd03_acta_anulacion_cuerpo',
                         'cstd06_comprobante_numero_egreso', 'cstd06_comprobante_numero_islr', 'cstd06_comprobante_numero_iva',
                         'cstd06_comprobante_numero_municipal', 'cstd06_comprobante_numero_timbre','cstd04_movimientos_generales',
                         'cstd06_comprobante_poremitir_egreso', 'cstd06_comprobante_poremitir_islr', 'cstd06_comprobante_poremitir_iva',
                         'cstd06_comprobante_poremitir_municipal', 'cstd06_comprobante_poremitir_timbre', 'cepd03_ordenpago_cuerpo', 'cfpd05',
                         'cepd03_tipo_documento', 'cepd03_ordenpago_facturas', 'cugd02_dependencia', 'cugd02_institucion','v_solicitud_cfpd05_p2',
						 'v_solicitud_cfpd05_p2','v_balance_ejecucion_partidas_inst','v_balance_ejecucion_partidas_dep','cepd01_compromiso_cuerpo',
						 'cepd01_compromiso_partidas','v_cfpd05_disponibilidad', 'cstd07_retenciones_cuerpo_multa', 'cstd07_retenciones_cuerpo_responsabilidad', 'cstd07_retenciones_partidas_multa', 'cstd07_retenciones_partidas_responsabilidad',
                      'cstd06_comprobante_poremitir_multa', 'cstd06_comprobante_poremitir_responsabilidad', 'cstd06_comprobante_numero_multa', 'cstd06_comprobante_numero_responsabilidad',
                      'cstd06_comprobante_cuerpo_multa', 'cstd06_comprobante_cuerpo_responsabilidad');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');

//,'v_balance_ejecucion_partidas_inst','v_balance_ejecucion_partidas_dep'




function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession





function beforeFilter(){$this->checkSession();}

function verifica_SS($i){
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
    function SQLCA_report_a($pre=null){
         $sql_re = "a.cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "a.cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "a.cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "a.cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "a.cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "a.cod_dep=".$this->verifica_SS(5)." ";
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


function reporte_ingreso_mensual_sr($var=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();

    $this->layout="ajax";
    $meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	$this->concatena($meses, 'mes');
	$this->set('ano', $ano);
	$ano            = "";
    $mes_select     = "";


    if($var=='no'){

	$this->layout = "ajax";





	}else{


               $ano_input      = $this->data['datos']['ano'];
               $mes_select     = $this->data['datos']['mes'];
               $this->set('mes',  $mes_select);
               $this->set('year', $ano_input);
               $this->layout = "pdf";






        $datos = $this->csrd01_solicitud_recurso_cuerpo->execute(" SELECT

                                           	  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano_solicitud,
											  a.numero_solicitud,
											  a.fecha_solicitud,
											  a.monto_solicitado,
											  a.monto_entregado,
											  a.cod_entidad_bancaria,
											  a.cod_sucursal,
											  a.cuenta_bancaria,
											  a.numero_cheque,
											  a.fecha_cheque,
											  a.concepto,
											  a.frecuencia_solicitud,
											  a.tipo_solicitud_recurso,
											  a.forma_solicitud,
											  a.mes_solicitado,
											  a.numero_quincena,
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
											  b.monto as monto_partida,
											  b.monto_entregado as monto_entregado_partida,
                                              (SELECT

                                              		   SUM(x.monto_entregado)

                                              	FROM  csrd01_solicitud_recurso_cuerpo y, csrd01_solicitud_recurso_partidas x

                                              			WHERE  a.frecuencia_solicitud =   1 and
                                              				   a.numero_quincena      =   2 and
                                              				   y.cod_presi            =   a.cod_presi and
                                              				   y.cod_entidad          =   a.cod_entidad and
                                              				   y.cod_tipo_inst        =   a.cod_tipo_inst and
                                              				   y.cod_dep              =   a.cod_dep and
                                              				   y.ano_solicitud        =   a.ano_solicitud  and
                                              				   y.mes_solicitado       =  ".$mes_select." and
                                              				   x.cod_presi            =   a.cod_presi and
                                              				   x.cod_entidad          =   a.cod_entidad and
                                              				   x.cod_tipo_inst        =   a.cod_tipo_inst and
                                              				   x.cod_dep              =   a.cod_dep and
                                              				   x.ano_solicitud        =   y.ano_solicitud and
                                              				   x.numero_solicitud     =   y.numero_solicitud and
                                              				   x.ano                  =   b.ano and
                                              				   x.cod_sector           =   b.cod_sector and
                                              				   x.cod_programa         =   b.cod_programa and
                                              				   x.cod_sub_prog         =   b.cod_sub_prog and
                                              				   x.cod_proyecto         =   b.cod_proyecto and
                                              				   x.cod_activ_obra       =   b.cod_activ_obra and
                                              				   x.cod_partida          =   b.cod_partida and
                                              				   x.cod_generica         =   b.cod_generica and
                                              				   x.cod_especifica       =   b.cod_especifica and
                                              				   x.cod_sub_espec        =   b.cod_sub_espec and
                                              				   x.cod_auxiliar         =   b.cod_auxiliar


                                                     ) as monto_entregado_partida_mes

                                         from  csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas b

                             	where

                             			a.cod_presi            =  ".$cod_presi."             and
									    a.cod_entidad          =  ".$cod_entidad."           and
									    a.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    a.cod_inst             =  ".$cod_inst."              and
									    a.cod_dep              =  ".$cod_dep."               and
									    a.ano_solicitud        =  ".$ano_input."             and
									    a.mes_solicitado       =  ".$mes_select."            and
									    b.cod_presi            =  ".$cod_presi."             and
									    b.cod_entidad          =  ".$cod_entidad."           and
									    b.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    b.cod_inst             =  ".$cod_inst."              and
									    b.cod_dep              =  ".$cod_dep."               and
									    b.ano_solicitud        =  a.ano_solicitud            and
                             			b.numero_solicitud     =  a.numero_solicitud

                                ORDER BY a.numero_solicitud, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, a.numero_quincena ASC; ");

                $this->set('datos', $datos);





                   $datos2 = $this->csrd01_solicitud_recurso_cuerpo->execute("SELECT

                                                           x.cod_presi,
                                          				   x.cod_entidad,
                                          				   x.cod_tipo_inst,
                                          				   x.cod_dep,
		               		                               x.ano,
		                                  				   x.cod_sector,
		                                  				   x.cod_programa,
		                                  				   x.cod_sub_prog,
		                                  				   x.cod_proyecto,
		                                  				   x.cod_activ_obra,
		                                  				   x.cod_partida,
		                                  				   x.cod_generica,
		                                  				   x.cod_especifica,
		                                  				   x.cod_sub_espec,
		                                  				   x.cod_auxiliar,
		                                  				   (SELECT

				                                              		  SUM(((yy.asignacion_anual + aumento_traslado_anual + credito_adicional_anual) - (disminucion_traslado_anual + rebaja_anual)) )

				                                              	FROM  cfpd05 yy

				                                              			WHERE
				                                              				   yy.cod_presi            =   x.cod_presi and
				                                              				   yy.cod_entidad          =   x.cod_entidad and
				                                              				   yy.cod_tipo_inst        =   x.cod_tipo_inst and
				                                              				   yy.cod_dep              =   x.cod_dep and
				                                              				   yy.ano                  =   x.ano and
				                                              				   yy.cod_sector           =   x.cod_sector and
				                                              				   yy.cod_programa         =   x.cod_programa and
				                                              				   yy.cod_sub_prog         =   x.cod_sub_prog and
				                                              				   yy.cod_proyecto         =   x.cod_proyecto and
				                                              				   yy.cod_activ_obra       =   x.cod_activ_obra and
				                                              				   yy.cod_partida          =   x.cod_partida and
				                                              				   yy.cod_generica         =   x.cod_generica and
				                                              				   yy.cod_especifica       =   x.cod_especifica and
				                                              				   yy.cod_sub_espec        =   x.cod_sub_espec and
				                                              				   yy.cod_auxiliar         =   x.cod_auxiliar

			                                                     ) as disponibilidad_partida_a,

		                                  				   (SELECT

				                                              		  SUM(((yy.asignacion_anual + aumento_traslado_anual + credito_adicional_anual) - (disminucion_traslado_anual + rebaja_anual)) )

				                                              	FROM  cfpd05 yy

				                                              			WHERE
				                                              				   yy.cod_presi            =   x.cod_presi and
				                                              				   yy.cod_entidad          =   x.cod_entidad and
				                                              				   yy.cod_tipo_inst        =   x.cod_tipo_inst and
				                                              				   yy.cod_dep              =   x.cod_dep and
				                                              				   yy.ano                  =   x.ano and
				                                              				   yy.cod_sector           =   x.cod_sector and
				                                              				   yy.cod_programa         =   x.cod_programa and
				                                              				   yy.cod_sub_prog         =   x.cod_sub_prog and
				                                              				   yy.cod_proyecto         =   x.cod_proyecto and
				                                              				   yy.cod_activ_obra       =   x.cod_activ_obra and
				                                              				   yy.cod_partida          =   x.cod_partida

			                                                     ) as disponibilidad_partida_b,

		                                  				   (SELECT

				                                              		  SUM(((yy.asignacion_anual + aumento_traslado_anual + credito_adicional_anual) - (disminucion_traslado_anual + rebaja_anual)))

				                                              	FROM  cfpd05 yy

				                                              			WHERE
				                                              				   yy.cod_presi            =   x.cod_presi and
				                                              				   yy.cod_entidad          =   x.cod_entidad and
				                                              				   yy.cod_tipo_inst        =   x.cod_tipo_inst and
				                                              				   yy.cod_dep              =   x.cod_dep and
				                                              				   yy.ano                  =   x.ano and
				                                              				   yy.cod_sector           =   x.cod_sector and
				                                              				   yy.cod_programa         =   x.cod_programa and
				                                              				   yy.cod_sub_prog         =   x.cod_sub_prog and
				                                              				   yy.cod_proyecto         =   x.cod_proyecto and
				                                              				   yy.cod_activ_obra       =   x.cod_activ_obra and
				                                              				   yy.cod_partida          =   x.cod_partida and
				                                              				   yy.cod_generica         =   x.cod_generica

			                                                     ) as disponibilidad_partida_c,

		                                  				   (SELECT

				                                              		  SUM(((yy.asignacion_anual + aumento_traslado_anual + credito_adicional_anual) - (disminucion_traslado_anual + rebaja_anual)))

				                                              	FROM  cfpd05 yy

				                                              			WHERE
				                                              				   yy.cod_presi            =   x.cod_presi and
				                                              				   yy.cod_entidad          =   x.cod_entidad and
				                                              				   yy.cod_tipo_inst        =   x.cod_tipo_inst and
				                                              				   yy.cod_dep              =   x.cod_dep and
				                                              				   yy.ano                  =   x.ano and
				                                              				   yy.cod_sector           =   x.cod_sector and
				                                              				   yy.cod_programa         =   x.cod_programa and
				                                              				   yy.cod_sub_prog         =   x.cod_sub_prog and
				                                              				   yy.cod_proyecto         =   x.cod_proyecto and
				                                              				   yy.cod_activ_obra       =   x.cod_activ_obra and
				                                              				   yy.cod_partida          =   x.cod_partida and
				                                              				   yy.cod_generica         =   x.cod_generica and
				                                              				   yy.cod_especifica       =   x.cod_especifica

			                                                     ) as disponibilidad_partida_d


                                              	FROM  csrd01_solicitud_recurso_cuerpo y, csrd01_solicitud_recurso_partidas x

                                              			WHERE
                                              				   y.cod_presi            =  ".$cod_presi."             and
														       y.cod_entidad          =  ".$cod_entidad."           and
														       y.cod_tipo_inst        =  ".$cod_tipo_inst."         and
														       y.cod_inst             =  ".$cod_inst."              and
														       y.cod_dep              =  ".$cod_dep."               and
                                              				   y.ano_solicitud        =  ".$ano_input."             and
                                              				   y.mes_solicitado       =  ".$mes_select."            and
                                              				   x.cod_presi            =   y.cod_presi               and
                                              				   x.cod_entidad          =   y.cod_entidad             and
                                              				   x.cod_tipo_inst        =   y.cod_tipo_inst           and
                                              				   x.cod_dep              =   y.cod_dep                 and
                                              				   x.ano_solicitud        =   y.ano_solicitud           and
                                              				   x.numero_solicitud     =   y.numero_solicitud

	                                              		group by

	                                              		       x.cod_presi,
	                                          				   x.cod_entidad,
	                                          				   x.cod_tipo_inst,
	                                          				   x.cod_dep,
			               		                               x.ano,
			                                  				   x.cod_sector,
			                                  				   x.cod_programa,
			                                  				   x.cod_sub_prog,
			                                  				   x.cod_proyecto,
			                                  				   x.cod_activ_obra,
			                                  				   x.cod_partida,
			                                  				   x.cod_generica,
			                                  				   x.cod_especifica,
			                                  				   x.cod_sub_espec,
			                                  				   x.cod_auxiliar;



                                                 ");

                $this->set('datos2', $datos2);


 //pr($datos2);


	     }//fin if

	     $this->set('opcion', $var);

	      $this->set('titulo_a',$this->Session->read('dependencia'));

}//fin function












function debito_formato_preimpreso($year=null){
	$this->layout="ajax";

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $this->Session->delete('FIN');
    $contador = 0;
    $borrar = "no";



if(isset($this->data['radio']['opcion'])){  $opcion = $this->data['radio']['opcion'];  }else{ $opcion = 1; $borrar = "si";}
$this->set('opcion', $opcion);

if(!$year){



    $this->set('ir', 'no');
    $ano = $this->ano_ejecucion();
	if(!empty($ano)){$this->set('year', $ano);}else{$this->set('year', '');}




$datos_cstd09_notadebito_poremitir  =  $this->cstd09_notadebito_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cstd09_notadebito_poremitir', $datos_cstd09_notadebito_poremitir);

$this->set('usuario', $this->Session->read('nom_usuario'));



//if($this->cstd06_comprobante_poremitir_egreso->findCount($condicion." and username='".$this->Session->read('nom_usuario')."' ")!=0){$contador++;}
if($this->cstd06_comprobante_poremitir_municipal->findCount($condicion." and username='".$this->Session->read('nom_usuario')."' ")!=0){$contador++;}
if($this->cstd06_comprobante_poremitir_timbre->findCount($condicion." and username='".$this->Session->read('nom_usuario')."' ")!=0){$contador++;}
if($this->cstd06_comprobante_poremitir_islr->findCount($condicion." and username='".$this->Session->read('nom_usuario')."' ")!=0){$contador++;}
if($this->cstd06_comprobante_poremitir_iva->findCount($condicion." and username='".$this->Session->read('nom_usuario')."' ")!=0){$contador++;}
if($this->cstd06_comprobante_poremitir_multa->findCount($condicion." and username='".$this->Session->read('nom_usuario')."' ")!=0){$contador++;}
if($this->cstd06_comprobante_poremitir_responsabilidad->findCount($condicion." and username='".$this->Session->read('nom_usuario')."' ")!=0){$contador++;}

$this->set('contador', $contador);



}else if($opcion==1){


$this->layout = "pdf";


$datos_cstd06_comprobante_poremitir_egreso    =   $this->cstd06_comprobante_poremitir_egreso->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ", null, 'numero_comprobante_egreso ASC');

$ii=0;
$sql_1 = "";
$sql_2 = "";


//pr($datos_cstd06_comprobante_poremitir_egreso);

foreach($datos_cstd06_comprobante_poremitir_egreso as $aux_cstd06_comprobante_poremitir_egreso){ $ii++;

  $ano_comprobante_egreso[$ii]      =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['ano_comprobante_egreso'];
  $numero_comprobante_egreso[$ii]   =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['numero_comprobante_egreso'];
  $ano_orden_pago[$ii]              =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['ano_orden_pago'];
  $clase_orden[$ii]                 =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['clase_orden'];
  $numero_orden_pago[$ii]           =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['numero_orden_pago'];



//if($sql_1==""){$sql_1   .= "  numero_comprobante_egreso='".$numero_comprobante_egreso[$ii]."'   and  ano_orden_pago='".$ano_orden_pago[$ii]."'    and  numero_orden_pago='".$numero_orden_pago[$ii]."' ";
//         }else{$sql_1   .= " or  (numero_comprobante_egreso='".$numero_comprobante_egreso[$ii]."'   and  ano_orden_pago='".$ano_orden_pago[$ii]."'    and  numero_orden_pago='".$numero_orden_pago[$ii]."'  )  ";    }

if($sql_1==""){$sql_1   = " ano_orden_pago='".$ano_orden_pago[$ii]."'    and  numero_orden_pago='".$numero_orden_pago[$ii]."' ";
          }else{$sql_1   .= " or  (ano_orden_pago='".$ano_orden_pago[$ii]."'    and  numero_orden_pago='".$numero_orden_pago[$ii]."'  )  ";    }




if($sql_2==""){$sql_2   = "   ano_comprobante_egreso='".$ano_comprobante_egreso[$ii]."'  and  numero_comprobante_egreso='".$numero_comprobante_egreso[$ii]."'   ";
         }else{$sql_2   .= " or  (ano_comprobante_egreso='".$ano_comprobante_egreso[$ii]."'  and  numero_comprobante_egreso='".$numero_comprobante_egreso[$ii]."'     )  ";}






}//fin for



if($sql_1==""){
		//echo'<script>history.back(1);</script>';
}else{

$datos_cstd06_comprobante_cuerpo_egreso    =   $this->cstd06_comprobante_cuerpo_egreso->findAll($condicion." and (".$sql_2.")", null, 'numero_comprobante_egreso ASC');
$aux2 = 0;
$sql_3 = "";
foreach($datos_cstd06_comprobante_cuerpo_egreso as $aux_cstd06_comprobante_cuerpo_egreso){
   $aux2++;
  $ano_comprobante_egreso[$aux2]      =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['ano_comprobante_egreso'];
  $numero_comprobante_egreso[$aux2]   =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['numero_comprobante_egreso'];
  $ano_movimiento[$aux2]              =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['ano_movimiento'];
  $cod_entidad_bancaria[$aux2]        =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['cod_entidad_bancaria'];
  $cod_sucursal[$aux2]                =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['cod_sucursal'];
  $cuenta_bancaria[$aux2]             =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['cuenta_bancaria'];
  $numero_cheque[$aux2]               =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['numero_cheque'];
if($sql_3==""){$sql_3   .= "   numero_comprobante_egreso='".$numero_comprobante_egreso[$aux2]."'   and  ano_movimiento='".$ano_movimiento[$aux2]."'  and  cod_entidad_bancaria='".$cod_entidad_bancaria[$aux2]."' and  cod_sucursal='".$cod_sucursal[$aux2]."'    and  cuenta_bancaria='".$cuenta_bancaria[$aux2]."'  and  numero_debito='".$numero_cheque[$aux2]."'    ";
         }else{$sql_3   .= " or  (numero_comprobante_egreso='".$numero_comprobante_egreso[$aux2]."'    and  ano_movimiento='".$ano_movimiento[$aux2]."'  and  cod_entidad_bancaria='".$cod_entidad_bancaria[$aux2]."'  and  cod_sucursal='".$cod_sucursal[$aux2]."'  and  cuenta_bancaria='".$cuenta_bancaria[$aux2]."'  and  numero_debito='".$numero_cheque[$aux2]."')  ";}
}//FIN FOR
//echo $condicion.$sql_1." and username_registro='".$this->Session->read('nom_usuario')."'   ";
$datos_cstd09_notadebito_cuerpo_pago=$this->cstd09_notadebito_cuerpo_pago->findAll($condicion." and (".$sql_3.") and username_registro='".$this->Session->read('nom_usuario')."'   ");





$datos_cepd03_ordenpago_cuerpo=$this->cepd03_ordenpago_cuerpo->findAll($condicion.' '." and (".$sql_1.")");
$aux= 0;
$tipodocu=$this->cepd03_tipo_documento->findAll();
$datos_cstd06_comprobante_poremitir_iva   =   $this->cstd06_comprobante_poremitir_iva->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );
$datos_cepd03_ordenpago_facturas=$this->cepd03_ordenpago_facturas->findAll($condicion);
$datos_cugd02_dependencias=$this->cugd02_dependencia->findAll();




$datos_cstd09_notadebito_poremitir=$this->cstd09_notadebito_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."'   ");
foreach($datos_cstd09_notadebito_poremitir as $ve12){
	 $cod_presi22     = $ve12['cstd09_notadebito_poremitir']['cod_presi'];
	 $cod_entidad22   = $ve12['cstd09_notadebito_poremitir']['cod_entidad'];
	 $cod_tipo_inst22 = $ve12['cstd09_notadebito_poremitir']['cod_tipo_inst'];
	 $cod_inst22      = $ve12['cstd09_notadebito_poremitir']['cod_inst'];
	 $cod_dep22       = $ve12['cstd09_notadebito_poremitir']['cod_dep'];
	 $username22                   = $ve12['cstd09_notadebito_poremitir']['username'];
	 $ano_movimiento22             = $ve12['cstd09_notadebito_poremitir']['ano_movimiento'];
	 $cod_entidad_bancaria22       = $ve12['cstd09_notadebito_poremitir']['cod_entidad_bancaria'];
	 $cod_sucursal22               = $ve12['cstd09_notadebito_poremitir']['cod_sucursal'];
	 $cuenta_bancaria22            = $ve12['cstd09_notadebito_poremitir']['cuenta_bancaria'];
	 $numero_cheque22              = $ve12['cstd09_notadebito_poremitir']['numero_debito'];
//$sqldc = " UPDATE cstd09_notadebito_cuerpo_pago SET status_cheque=2 WHERE cod_presi='".$cod_presi22."' and cod_entidad='".$cod_entidad22."' and  cod_tipo_inst='".$cod_tipo_inst22."' and  cod_inst='".$cod_inst22."' and cod_dep='".$cod_dep22."' and username_registro ='".$username22."' and ano_movimiento='".$ano_movimiento22."' and cod_entidad_bancaria = '".$cod_entidad_bancaria22."' and   cod_sucursal='".$cod_sucursal22."' and  cuenta_bancaria='".$cuenta_bancaria22."'  and numero_debito='".$numero_debito22."' ";
//$this->cstd09_notadebito_poremitir->execute($sqldc);
}//fin foeach


//solo el de egreso


/*

$sql = "delete from cstd09_notadebito_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd09_notadebito_poremitir->execute($sql);*/
/*
$sql = "delete from cstd06_comprobante_poremitir_egreso  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_egreso->execute($sql);
*/
/*
$sql = "delete from cstd06_comprobante_poremitir_municipal  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_municipal->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_timbre  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_timbre->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_islr  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_islr->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_iva  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_iva->execute($sql);
*/

}//fin else








$this->set('datos_cstd06_comprobante_poremitir_iva', $datos_cstd06_comprobante_poremitir_iva);
$this->set('datos_cstd06_comprobante_poremitir_egreso', $datos_cstd06_comprobante_poremitir_egreso);
$this->set('datos_cstd06_comprobante_cuerpo_egreso', $datos_cstd06_comprobante_cuerpo_egreso);





$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll());
$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);

$this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
$this->set('datos_cstd09_notadebito_cuerpo_pago', $datos_cstd09_notadebito_cuerpo_pago);
$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);


$this->set('tipodocu', $tipodocu);



if($borrar=="si"){

$sql = "delete from cstd09_notadebito_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd09_notadebito_poremitir->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_egreso  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_egreso->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_municipal  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_municipal->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_timbre  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_timbre->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_islr  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_islr->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_iva  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_iva->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_multa  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_multa->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_responsabilidad  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_responsabilidad->execute($sql);

}//fin if




















}else if($opcion==2){



$this->layout = "pdf";



$datos_cstd06_comprobante_poremitir_egreso    =   $this->cstd06_comprobante_poremitir_egreso->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";
$sql_2 = "";

foreach($datos_cstd06_comprobante_poremitir_egreso as $aux_cstd06_comprobante_poremitir_egreso){ $ii++;

  $ano_comprobante_egreso[$ii]      =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['ano_comprobante_egreso'];
  $numero_comprobante_egreso[$ii]   =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['numero_comprobante_egreso'];
  $ano_orden_pago[$ii]              =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['ano_orden_pago'];
  $clase_orden[$ii]                 =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['clase_orden'];
  $numero_orden_pago[$ii]           =    $aux_cstd06_comprobante_poremitir_egreso['cstd06_comprobante_poremitir_egreso']['numero_orden_pago'];


//if($sql_1==""){$sql_1   .= "  numero_comprobante_egreso='".$numero_comprobante_egreso[$ii]."'   and  ano_orden_pago='".$ano_orden_pago[$ii]."'    and  numero_orden_pago='".$numero_orden_pago[$ii]."' ";
//         }else{$sql_1   .= " or  (numero_comprobante_egreso='".$numero_comprobante_egreso[$ii]."'   and  ano_orden_pago='".$ano_orden_pago[$ii]."'    and  numero_orden_pago='".$numero_orden_pago[$ii]."'  )  ";    }

if($sql_1==""){$sql_1   = " ano_orden_pago='".$ano_orden_pago[$ii]."'    and  numero_orden_pago='".$numero_orden_pago[$ii]."' ";
          }else{$sql_1   .= " or  (ano_orden_pago='".$ano_orden_pago[$ii]."'    and  numero_orden_pago='".$numero_orden_pago[$ii]."'  )  ";    }



if($sql_2==""){$sql_2   = "   ano_comprobante_egreso='".$ano_comprobante_egreso[$ii]."'  and  numero_comprobante_egreso='".$numero_comprobante_egreso[$ii]."'   ";
         }else{$sql_2   .= " or  (ano_comprobante_egreso='".$ano_comprobante_egreso[$ii]."'  and  numero_comprobante_egreso='".$numero_comprobante_egreso[$ii]."'     )  ";}





}//fin for





if($sql_1==""){

	echo'<script>history.back(1);</script>';

}else{






$datos_cstd06_comprobante_cuerpo_egreso    =   $this->cstd06_comprobante_cuerpo_egreso->findAll($condicion." and (".$sql_2.")");


$aux2 = 0;
$sql_3 = "";

foreach($datos_cstd06_comprobante_cuerpo_egreso as $aux_cstd06_comprobante_cuerpo_egreso){
   $aux2++;

  $ano_comprobante_egreso[$aux2]      =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['ano_comprobante_egreso'];
  $numero_comprobante_egreso[$aux2]   =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['numero_comprobante_egreso'];
  $ano_movimiento[$aux2]              =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['ano_movimiento'];
  $cod_entidad_bancaria[$aux2]        =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['cod_entidad_bancaria'];
  $cod_sucursal[$aux2]                =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['cod_sucursal'];
  $cuenta_bancaria[$aux2]             =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['cuenta_bancaria'];
  $numero_cheque[$aux2]               =    $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['numero_cheque'];


if($sql_3==""){$sql_3   .= "   numero_comprobante_egreso='".$numero_comprobante_egreso[$aux2]."'   and  ano_movimiento='".$ano_movimiento[$aux2]."'  and  cod_entidad_bancaria='".$cod_entidad_bancaria[$aux2]."' and  cod_sucursal='".$cod_sucursal[$aux2]."'    and  cuenta_bancaria='".$cuenta_bancaria[$aux2]."'  and  numero_debito='".$numero_cheque[$aux2]."'    ";
         }else{$sql_3   .= " or  (numero_comprobante_egreso='".$numero_comprobante_egreso[$aux2]."'    and  ano_movimiento='".$ano_movimiento[$aux2]."'  and  cod_entidad_bancaria='".$cod_entidad_bancaria[$aux2]."'  and  cod_sucursal='".$cod_sucursal[$aux2]."'  and  cuenta_bancaria='".$cuenta_bancaria[$aux2]."'  and  numero_debito='".$numero_cheque[$aux2]."')  ";}


}//FIN FOR


//echo $condicion." and (".$sql_1.") and username_registro='".$this->Session->read('nom_usuario')."'   ";

$datos_cstd09_notadebito_cuerpo_pago=$this->cstd09_notadebito_cuerpo_pago->findAll($condicion." and (".$sql_3.") and username_registro='".$this->Session->read('nom_usuario')."'   ");
$datos_cepd03_ordenpago_cuerpo=$this->cepd03_ordenpago_cuerpo->findAll($condicion." and (".$sql_1.")   ");

$aux= 0;
$tipodocu=$this->cepd03_tipo_documento->findAll();








$datos_cstd06_comprobante_poremitir_iva   =   $this->cstd06_comprobante_poremitir_iva->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );
$datos_cepd03_ordenpago_facturas          =   $this->cepd03_ordenpago_facturas->findAll($condicion);
$datos_cugd02_dependencias                =   $this->cugd02_dependencia->findAll();







$datos_cstd06_comprobante_poremitir_islr   =   $this->cstd06_comprobante_poremitir_islr->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );
$this->set('datos_cstd06_comprobante_poremitir_islr', $datos_cstd06_comprobante_poremitir_islr);



$datos_cstd06_comprobante_poremitir_municipal   =   $this->cstd06_comprobante_poremitir_municipal->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );
$this->set('datos_cstd06_comprobante_poremitir_municipal', $datos_cstd06_comprobante_poremitir_municipal);




$datos_cstd06_comprobante_poremitir_timbre   =   $this->cstd06_comprobante_poremitir_timbre->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );
$this->set('datos_cstd06_comprobante_poremitir_timbre', $datos_cstd06_comprobante_poremitir_timbre);




$datos_cstd06_comprobante_poremitir_multa   =   $this->cstd06_comprobante_poremitir_multa->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );
$this->set('datos_cstd06_comprobante_poremitir_multa', $datos_cstd06_comprobante_poremitir_multa);


$datos_cstd06_comprobante_poremitir_responsabilidad   =   $this->cstd06_comprobante_poremitir_responsabilidad->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );
$this->set('datos_cstd06_comprobante_poremitir_responsabilidad', $datos_cstd06_comprobante_poremitir_responsabilidad);

}//fin else





$resul =  $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");



$this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
$this->set('rif_institucion',              $resul[0]['cugd02_institucion']['rif']);
$this->set('denominacion_institucion',     $resul[0]['cugd02_institucion']['denominacion']);


$this->set('datos_cstd06_comprobante_poremitir_iva', $datos_cstd06_comprobante_poremitir_iva);
$this->set('datos_cstd06_comprobante_poremitir_egreso', $datos_cstd06_comprobante_poremitir_egreso);
$this->set('datos_cstd06_comprobante_cuerpo_egreso', $datos_cstd06_comprobante_cuerpo_egreso);





$this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
$this->set('cod_sucursal22',         $this->cstd01_sucursales_bancarias->findAll());
$this->set('cuenta_bancaria22',      $this->cstd02_cuentas_bancarias->findAll($condicion));
$this->set('datos_cugd02_dependencias',      $datos_cugd02_dependencias);
$this->set('datos_cepd03_ordenpago_facturas', $datos_cepd03_ordenpago_facturas);
$this->set('datos_cstd09_notadebito_cuerpo_pago', $datos_cstd09_notadebito_cuerpo_pago);
$this->set('datos_cepd03_ordenpago_cuerpo', $datos_cepd03_ordenpago_cuerpo);


$this->set('tipodocu', $tipodocu);


////borra todo



$sql = "delete from cstd09_notadebito_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd09_notadebito_poremitir->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_egreso  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_egreso->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_municipal  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_municipal->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_timbre  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_timbre->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_islr  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_islr->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_iva  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_iva->execute($sql);


$sql = "delete from cstd06_comprobante_poremitir_multa  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_multa->execute($sql);

$sql = "delete from cstd06_comprobante_poremitir_responsabilidad  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cstd06_comprobante_poremitir_responsabilidad->execute($sql);




}//fin else





$this->set('cod_entidad_bancaria', $this->cstd01_entidades_bancarias->findAll());
$this->set('cod_sucursal',         $this->cstd01_sucursales_bancarias->findAll());
$this->set('cuenta_bancaria',      $this->cstd02_cuentas_bancarias->findAll());
$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));




}//generar_cheque






function reporte_detalle_solicitud_organismo($var=null,$var2=null){
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$ano=$this->ano_ejecucion();
	if($var=="si"){
		$this->layout="ajax";
		$cod_dep= $this->Session->read('SScoddep');
		if($cod_dep==1){
			$dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
			$this->concatena($dep, 'dependencias');
		}else{
			$dep = $this->arrd05->generateList($conditions = $this->condicion(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
			$this->concatena($dep, 'dependencias');
		}
		$this->set('ir','si');
	}else if($var=="no"){///fin si
		$this->layout = "pdf";
		$this->set('ir','no');
		//pr($this->data);
		$recurso = $this->csrd01_tipo_solicitud->FindAll();
		$this->set('recurso',$recurso);
		$cod_dep= $this->Session->read('SScoddep');
		if($cod_dep==1){
			//pr($this->data);
			$dependencia=$this->data["organismo"]["cod_dep"];
			$dep = $this->arrd05->field('denominacion', $conditions = $this->condicionNDEP()." and cod_dep='$dependencia'", $order ="cod_dep ASC");
		$this->set('nom_dep',$dep);
			/*$cuerpo=$this->csrd01_solicitud_recurso_cuerpo->execute("select * from csrd01_solicitud_recurso_cuerpo where ".$this->condicionNDEP()." and cod_dep=".$dependencia." and tipo_solicitud_recurso=".$recurso."  order by numero_solicitud asc");
			$subpartidas=$this->csrd01_solicitud_recurso_partidas->execute("select ano_solicitud,numero_solicitud,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,monto_entregado from csrd01_solicitud_recurso_partidas where ".$this->condicionNDEP()." and cod_dep=".$dependencia." group by ano_solicitud,numero_solicitud,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,monto_entregado order by ano_solicitud,numero_solicitud,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar asc");
		*/
		$dispo=$this->v_solicitud_cfpd05_p2->execute("SELECT sum(asignacion_anual_actualizada) as asignacion FROM v_solicitud_cfpd05_p2 where ".$this->condicionNDEP()." and cod_dep=".$dependencia." and ano=".$ano);
		$asignacion_anual=$dispo[0][0]['asignacion'];
		$this->set('asignacion',$asignacion_anual);
		$cuerpo = $this->csrd01_solicitud_recurso_cuerpo->execute(" SELECT

                                           	  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano_solicitud,
											  a.numero_solicitud,
											  a.fecha_solicitud,
											  a.monto_solicitado,
											  a.monto_entregado,
											  a.cod_entidad_bancaria,
											  a.cod_sucursal,
											  a.cuenta_bancaria,
											  a.numero_cheque,
											  a.fecha_cheque,
											  a.concepto,
											  a.frecuencia_solicitud,
											  a.tipo_solicitud_recurso,
											  a.forma_solicitud,
											  a.mes_solicitado,
											  a.numero_quincena,
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
											  b.monto as monto_partida,
											  b.monto_entregado as monto_entregado_partida,
                                              (SELECT

                                              		   SUM(x.monto_entregado)

                                              	FROM  csrd01_solicitud_recurso_cuerpo y, csrd01_solicitud_recurso_partidas x

                                              			WHERE  a.frecuencia_solicitud =   1 and
                                              				   a.numero_quincena      =   2 and
                                              				   y.cod_presi            =   a.cod_presi and
                                              				   y.cod_entidad          =   a.cod_entidad and
                                              				   y.cod_tipo_inst        =   a.cod_tipo_inst and
                                              				   y.cod_dep              =   a.cod_dep and
                                              				   y.ano_solicitud        =   a.ano_solicitud  and
                                              				   y.mes_solicitado       =   a.mes_solicitado and
                                              				   y.tipo_solicitud_recurso=  a.tipo_solicitud_recurso and
                                              				   x.cod_presi            =   a.cod_presi and
                                              				   x.cod_entidad          =   a.cod_entidad and
                                              				   x.cod_tipo_inst        =   a.cod_tipo_inst and
                                              				   x.cod_dep              =   a.cod_dep and
                                              				   x.ano_solicitud        =   y.ano_solicitud and
                                              				   x.numero_solicitud     =   y.numero_solicitud and
                                              				   x.ano                  =   b.ano and
                                              				   x.cod_sector           =   b.cod_sector and
                                              				   x.cod_programa         =   b.cod_programa and
                                              				   x.cod_sub_prog         =   b.cod_sub_prog and
                                              				   x.cod_proyecto         =   b.cod_proyecto and
                                              				   x.cod_activ_obra       =   b.cod_activ_obra and
                                              				   x.cod_partida          =   b.cod_partida and
                                              				   x.cod_generica         =   b.cod_generica and
                                              				   x.cod_especifica       =   b.cod_especifica and
                                              				   x.cod_sub_espec        =   b.cod_sub_espec and
                                              				   x.cod_auxiliar         =   b.cod_auxiliar


                                                     ) as monto_entregado_partida_mes

                                         from  csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas b

                             	where

                             			a.cod_presi            =  ".$cod_presi."             and
									    a.cod_entidad          =  ".$cod_entidad."           and
									    a.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    a.cod_inst             =  ".$cod_inst."              and
									    a.cod_dep              =  ".$dependencia."           and
									    a.ano_solicitud        =  ".$ano."                   and
									    b.cod_presi            =  ".$cod_presi."             and
									    b.cod_entidad          =  ".$cod_entidad."           and
									    b.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    b.cod_inst             =  ".$cod_inst."              and
									    b.cod_dep              =  ".$dependencia."           and
									    b.ano_solicitud        =  a.ano_solicitud            and
                             			b.numero_solicitud     =  a.numero_solicitud

                                ORDER BY a.numero_solicitud, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, a.numero_quincena ASC; ");

               // $this->set('cuerpo', $cuerpo);

		}else{
			$dep = $this->arrd05->field('denominacion', $conditions = $this->condicion(), $order ="cod_dep ASC");
		$this->set('nom_dep',$dep);
			$dispo=$this->v_solicitud_cfpd05_p2->execute("SELECT sum(asignacion_anual_actualizada) as asignacion FROM v_solicitud_cfpd05_p2 where ".$this->condicion()." and ano=$ano");
//		pr($dispo);
		$asignacion_anual=$dispo[0][0]['asignacion'];
		$this->set('asignacion',$asignacion_anual);
			//$cuerpo=$this->csrd01_solicitud_recurso_cuerpo->execute("select * from csrd01_solicitud_recurso_cuerpo where ".$this->condicion()." and tipo_solicitud_recurso=".$recurso."  order by numero_solicitud asc");
			//$subpartidas=$this->csrd01_solicitud_recurso_partidas->execute("select ano_solicitud,numero_solicitud,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,monto_entregado from csrd01_solicitud_recurso_partidas where ".$this->condicion()." group by ano_solicitud,numero_solicitud,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,monto_entregado order by ano_solicitud,numero_solicitud,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar asc");
		$cuerpo = $this->csrd01_solicitud_recurso_cuerpo->execute(" SELECT

                                           	  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano_solicitud,
											  a.numero_solicitud,
											  a.fecha_solicitud,
											  a.monto_solicitado,
											  a.monto_entregado,
											  a.cod_entidad_bancaria,
											  a.cod_sucursal,
											  a.cuenta_bancaria,
											  a.numero_cheque,
											  a.fecha_cheque,
											  a.concepto,
											  a.frecuencia_solicitud,
											  a.tipo_solicitud_recurso,
											  a.forma_solicitud,
											  a.mes_solicitado,
											  a.numero_quincena,
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
											  b.monto as monto_partida,
											  b.monto_entregado as monto_entregado_partida,
                                              (SELECT

                                              		   SUM(x.monto_entregado)

                                              	FROM  csrd01_solicitud_recurso_cuerpo y, csrd01_solicitud_recurso_partidas x

                                              			WHERE  a.frecuencia_solicitud =   1 and
                                              				   a.numero_quincena      =   2 and
                                              				   y.cod_presi            =   a.cod_presi and
                                              				   y.cod_entidad          =   a.cod_entidad and
                                              				   y.cod_tipo_inst        =   a.cod_tipo_inst and
                                              				   y.cod_dep              =   a.cod_dep and
                                              				   y.ano_solicitud        =   a.ano_solicitud  and
                                              				   y.mes_solicitado       =   a.mes_solicitado and
                                              				   y.tipo_solicitud_recurso=  a.tipo_solicitud_recurso and
                                              				   x.cod_presi            =   a.cod_presi and
                                              				   x.cod_entidad          =   a.cod_entidad and
                                              				   x.cod_tipo_inst        =   a.cod_tipo_inst and
                                              				   x.cod_dep              =   a.cod_dep and
                                              				   x.ano_solicitud        =   y.ano_solicitud and
                                              				   x.numero_solicitud     =   y.numero_solicitud and
                                              				   x.ano                  =   b.ano and
                                              				   x.cod_sector           =   b.cod_sector and
                                              				   x.cod_programa         =   b.cod_programa and
                                              				   x.cod_sub_prog         =   b.cod_sub_prog and
                                              				   x.cod_proyecto         =   b.cod_proyecto and
                                              				   x.cod_activ_obra       =   b.cod_activ_obra and
                                              				   x.cod_partida          =   b.cod_partida and
                                              				   x.cod_generica         =   b.cod_generica and
                                              				   x.cod_especifica       =   b.cod_especifica and
                                              				   x.cod_sub_espec        =   b.cod_sub_espec and
                                              				   x.cod_auxiliar         =   b.cod_auxiliar


                                                     ) as monto_entregado_partida_mes

                                         from  csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas b

                             	where

                             			a.cod_presi            =  ".$cod_presi."             and
									    a.cod_entidad          =  ".$cod_entidad."           and
									    a.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    a.cod_inst             =  ".$cod_inst."              and
									    a.cod_dep              =  ".$SScoddep."              and
									    a.ano_solicitud        =  ".$ano."                   and
									    b.cod_presi            =  ".$cod_presi."             and
									    b.cod_entidad          =  ".$cod_entidad."           and
									    b.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    b.cod_inst             =  ".$cod_inst."              and
									    b.cod_dep              =  ".$SScoddep."              and
									    b.ano_solicitud        =  a.ano_solicitud            and
                             			b.numero_solicitud     =  a.numero_solicitud

                                ORDER BY a.numero_solicitud, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, a.numero_quincena ASC; ");

                //$this->set('cuerpo', $cuerpo);


		}
		//pr($cuerpo);
		$this->set('cuerpo',$cuerpo);
		//$this->set('subpartidas',$subpartidas);

	}else if($var=='mostrar'){
		$this->layout="ajax";
		if($var2!=""){
			$deno_nomina = $this->arrd05->field('denominacion', $conditions = $this->condicionNDEP()." and cod_dep='$var2'", $order ="cod_dep ASC");
			$this->set('deno', $deno_nomina);
		}else{
			$this->set('deno', '');
		}

		$this->set('mostrar','');
		//echo $var2;
	}else if($var=='boton'){
		$this->layout="ajax";
		$this->set('boton','');
	}// fin if no


}//fin reporte_detalle_solicitud_organismo









function reporte_detalle_solicitud_dependencia($var=null,$var2=null){
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$ano=$this->ano_ejecucion();
	if($var=="si"){
		$this->layout="ajax";
		$cod_dep= $this->Session->read('SScoddep');
		if($cod_dep==1){
			$dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
			$this->concatena($dep, 'dependencias');
		}else{
			$dep = $this->arrd05->generateList($conditions = $this->condicion(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
			$this->concatena($dep, 'dependencias');
		}
		$recurso = $this->csrd01_tipo_solicitud->generateList(null, $order = 'cod_tipo_solicitud', $limit = null, '{n}.csrd01_tipo_solicitud.cod_tipo_solicitud', '{n}.csrd01_tipo_solicitud.denominacion');
		$this->concatena($recurso, 'recurso');
		$this->set('ir','si');
	}else if($var=="no"){///fin si
		$this->layout = "pdf";
		$this->set('ir','no');
		$this->set('recurso',$this->csrd01_tipo_solicitud->FindAll());
		$recurso=$this->data["organismo"]["recurso"];
		$this->set('recurso_tipo',$recurso);
		$cod_dep= $this->Session->read('SScoddep');
		$dependencia=$this->data["organismo"]["cod_dep"];
		$dep = $this->arrd05->field('denominacion', $conditions = $this->condicionNDEP()." and cod_dep='$dependencia'", $order ="cod_dep ASC");
		$this->set('nom_dep',$dep);
			/*$cuerpo=$this->csrd01_solicitud_recurso_cuerpo->execute("select * from csrd01_solicitud_recurso_cuerpo where ".$this->condicionNDEP()." and cod_dep=".$dependencia." and tipo_solicitud_recurso=".$recurso."  order by numero_solicitud asc");
			$subpartidas=$this->csrd01_solicitud_recurso_partidas->execute("select ano_solicitud,numero_solicitud,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,monto_entregado from csrd01_solicitud_recurso_partidas where ".$this->condicionNDEP()." and cod_dep=".$dependencia." group by ano_solicitud,numero_solicitud,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,monto_entregado order by ano_solicitud,numero_solicitud,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar asc");
		*/

$query = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud, a.numero_solicitud,a.fecha_solicitud, a.mes_solicitado,a.forma_solicitud,a.frecuencia_solicitud,a.numero_quincena,a.tipo_solicitud_recurso, b.ano,b.cod_sector,b.cod_programa
,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar,a.monto_solicitado, b.monto, b.monto_entregado,

null_cero((SELECT SUM(e.asignacion_anual_actualizada) FROM v_solicitud_cfpd05_p2 e
WHERE
e.cod_presi = a.cod_presi and
e.cod_entidad = a.cod_entidad and
e.cod_tipo_inst = a.cod_tipo_inst and
e.cod_inst = a.cod_inst and
e.cod_dep = a.cod_dep and
e.cod_sector = b.cod_sector and
e.cod_programa = b.cod_programa and
e.cod_sub_prog = b.cod_sub_prog and
e.cod_proyecto = b.cod_proyecto and
e.cod_activ_obra = b.cod_activ_obra and
e.cod_partida=b.cod_partida and
e.cod_generica=b.cod_generica and
e.cod_especifica=b.cod_especifica and
e.cod_sub_espec=b.cod_sub_espec and
e.cod_auxiliar=b.cod_auxiliar and
e.ano = b.ano
group by e.cod_presi, e.cod_entidad,e.cod_tipo_inst, e.cod_inst, e.cod_dep, e.cod_partida, e.cod_generica, e.cod_especifica, e.cod_sub_espec, e.cod_auxiliar
)) as asignacion_anual1,

null_cero((SELECT SUM(e.asignacion_anual_actualizada) FROM v_solicitud_cfpd05_p2 e
WHERE
e.cod_presi = a.cod_presi and
e.cod_entidad = a.cod_entidad and
e.cod_tipo_inst = a.cod_tipo_inst and
e.cod_inst = a.cod_inst and
e.cod_dep = a.cod_dep and
e.cod_sector = b.cod_sector and
e.cod_programa = b.cod_programa and
e.cod_sub_prog = b.cod_sub_prog and
e.cod_proyecto = b.cod_proyecto and
e.cod_activ_obra = b.cod_activ_obra and
e.cod_partida=b.cod_partida and
e.ano = b.ano
group by e.cod_presi, e.cod_entidad,e.cod_tipo_inst, e.cod_inst, e.cod_dep,e.cod_sector,e.cod_programa,e.cod_sub_prog,e.cod_proyecto,e.cod_activ_obra,e.cod_partida
)) as asignacion_anual2,
null_cero((SELECT SUM(c.monto_entregado) FROM csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas c
WHERE
a.cod_presi = c.cod_presi and
a.cod_entidad = c.cod_entidad and
a.cod_tipo_inst = c.cod_tipo_inst and
a.cod_inst = c.cod_inst and
a.cod_dep = c.cod_dep and
a.ano_solicitud = c.ano_solicitud and
a.numero_solicitud = c.numero_solicitud and
a.tipo_solicitud_recurso!=".$recurso." and c.cod_dep='$dependencia' and
 b.cod_sector = c.cod_sector and b.cod_programa=c.cod_programa and b.cod_sub_prog=c.cod_sub_prog and b.cod_proyecto=c.cod_proyecto and b.cod_activ_obra=c.cod_activ_obra and b.cod_partida=c.cod_partida and b.cod_generica=c.cod_generica and b.cod_especifica=c.cod_especifica and b.cod_sub_espec=c.cod_sub_espec and b.cod_auxiliar=c.cod_auxiliar
)) as monto_otros
FROM csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas b, cfpd05 d
WHERE
a.cod_presi = b.cod_presi and
a.cod_entidad = b.cod_entidad and
a.cod_tipo_inst = b.cod_tipo_inst and
a.cod_inst = b.cod_inst and
a.cod_dep = d.cod_dep and
a.cod_presi = d.cod_presi and
a.cod_entidad = d.cod_entidad and
a.cod_tipo_inst = d.cod_tipo_inst and
a.cod_inst = d.cod_inst and
a.cod_dep = d.cod_dep and
d.cod_presi = b.cod_presi and
d.cod_entidad = b.cod_entidad and
d.cod_tipo_inst = b.cod_tipo_inst and
d.cod_inst = b.cod_inst and
d.cod_dep = b.cod_dep and
a.ano_solicitud = b.ano_solicitud and
a.ano_solicitud = d.ano and
d.ano = b.ano_solicitud and
a.numero_solicitud = b.numero_solicitud and
a.tipo_solicitud_recurso='$recurso' and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$dependencia'
 GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud, a.numero_solicitud, a.fecha_solicitud, a.mes_solicitado,a.forma_solicitud,a.frecuencia_solicitud,a.numero_quincena,a.tipo_solicitud_recurso ,b.ano,b.cod_sector,b.cod_programa," .
 		"b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, a.monto_solicitado, b.monto, b.monto_entregado
 ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, b.ano,a.numero_solicitud,b.cod_sector,b.cod_programa,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar  ASC";





$query2 = "SELECT distinct a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud,b.ano,b.cod_sector,b.cod_programa
,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar,


null_cero((SELECT SUM(c.monto_entregado) FROM csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas c
WHERE
a.cod_presi = c.cod_presi and
a.cod_entidad = c.cod_entidad and
a.cod_tipo_inst = c.cod_tipo_inst and
a.cod_inst = c.cod_inst and
a.cod_dep = c.cod_dep and
a.ano_solicitud = c.ano_solicitud and
a.tipo_solicitud_recurso!=".$recurso." and c.cod_dep='$dependencia' and
 b.cod_sector = c.cod_sector and b.cod_programa=c.cod_programa and b.cod_sub_prog=c.cod_sub_prog and b.cod_proyecto=c.cod_proyecto and b.cod_activ_obra=c.cod_activ_obra and b.cod_partida=c.cod_partida and b.cod_generica=c.cod_generica and b.cod_especifica=c.cod_especifica and b.cod_sub_espec=c.cod_sub_espec and b.cod_auxiliar=c.cod_auxiliar
)) as monto_otros
FROM csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas b, cfpd05 d
WHERE
a.cod_presi = b.cod_presi and
a.cod_entidad = b.cod_entidad and
a.cod_tipo_inst = b.cod_tipo_inst and
a.cod_inst = b.cod_inst and
a.cod_dep = d.cod_dep and
a.cod_presi = d.cod_presi and
a.cod_entidad = d.cod_entidad and
a.cod_tipo_inst = d.cod_tipo_inst and
a.cod_inst = d.cod_inst and
a.cod_dep = d.cod_dep and
d.cod_presi = b.cod_presi and
d.cod_entidad = b.cod_entidad and
d.cod_tipo_inst = b.cod_tipo_inst and
d.cod_inst = b.cod_inst and
d.cod_dep = b.cod_dep and
a.ano_solicitud = b.ano_solicitud and
a.ano_solicitud = d.ano and
d.ano = b.ano_solicitud and
a.numero_solicitud = b.numero_solicitud and
a.tipo_solicitud_recurso!='$recurso' and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$dependencia'
 GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud,b.ano,b.cod_sector,b.cod_programa,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar
 ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, b.ano,b.cod_sector,b.cod_programa,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar";






$dispo=$this->v_solicitud_cfpd05_p2->execute("SELECT sum(asignacion_anual_actualizada) as asignacion FROM v_solicitud_cfpd05_p2 where ".$this->condicionNDEP()." and cod_dep=".$dependencia);
//pr($dispo);
$asignacion_anual=$dispo[0][0]['asignacion'];
 $this->set('asignacion',$asignacion_anual);

 $dispo=$this->v_solicitud_cfpd05_p2->execute("SELECT sum(monto_entregado) as asignacion FROM csrd01_solicitud_recurso_partidas where ".$this->condicionNDEP()." and cod_dep=".$dependencia);
//pr($dispo);
$entregado_part=$dispo[0][0]['asignacion'];
$disponibilidad=$asignacion_anual-$entregado_part;
$this->set('disponible',$disponibilidad);

//echo $asignacion_anual;
/*

$query2 = "SELECT distinct a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud,b.ano,b.cod_sector,b.cod_programa
,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar,

null_cero((SELECT SUM(e.asignacion_anual_actualizada) FROM v_solicitud_cfpd05_p2 e
WHERE
e.cod_presi = a.cod_presi and
e.cod_entidad = a.cod_entidad and
e.cod_tipo_inst = a.cod_tipo_inst and
e.cod_inst = a.cod_inst and
e.cod_dep = a.cod_dep and
e.cod_sector = b.cod_sector and
e.cod_programa = b.cod_programa and
e.cod_sub_prog = b.cod_sub_prog and
e.cod_proyecto = b.cod_proyecto and
e.cod_activ_obra = b.cod_activ_obra and
e.cod_partida=b.cod_partida and
e.cod_generica=b.cod_generica and
e.cod_especifica=b.cod_especifica and
e.cod_sub_espec=b.cod_sub_espec and
e.cod_auxiliar=b.cod_auxiliar and
e.ano = b.ano
group by e.cod_presi, e.cod_entidad,e.cod_tipo_inst, e.cod_inst, e.cod_dep,e.cod_sector,e.cod_programa ,e.cod_sub_prog,e.cod_proyecto,e.cod_activ_obra, e.cod_partida, e.cod_generica, e.cod_especifica, e.cod_sub_espec, e.cod_auxiliar
)) as asignacion_subpartida,
null_cero((SELECT SUM(c.monto_entregado) FROM csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas c
WHERE
a.cod_presi = c.cod_presi and
a.cod_entidad = c.cod_entidad and
a.cod_tipo_inst = c.cod_tipo_inst and
a.cod_inst = c.cod_inst and
a.cod_dep = c.cod_dep and
a.ano_solicitud = c.ano_solicitud and
a.tipo_solicitud_recurso!=".$recurso." and c.cod_dep='$dependencia' and
 b.cod_sector = c.cod_sector and b.cod_programa=c.cod_programa and b.cod_sub_prog=c.cod_sub_prog and b.cod_proyecto=c.cod_proyecto and b.cod_activ_obra=c.cod_activ_obra and b.cod_partida=c.cod_partida and b.cod_generica=c.cod_generica and b.cod_especifica=c.cod_especifica and b.cod_sub_espec=c.cod_sub_espec and b.cod_auxiliar=c.cod_auxiliar
)) as monto_otros
FROM csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas b, cfpd05 d
WHERE
a.cod_presi = b.cod_presi and
a.cod_entidad = b.cod_entidad and
a.cod_tipo_inst = b.cod_tipo_inst and
a.cod_inst = b.cod_inst and
a.cod_dep = d.cod_dep and
a.cod_presi = d.cod_presi and
a.cod_entidad = d.cod_entidad and
a.cod_tipo_inst = d.cod_tipo_inst and
a.cod_inst = d.cod_inst and
a.cod_dep = d.cod_dep and
d.cod_presi = b.cod_presi and
d.cod_entidad = b.cod_entidad and
d.cod_tipo_inst = b.cod_tipo_inst and
d.cod_inst = b.cod_inst and
d.cod_dep = b.cod_dep and
a.ano_solicitud = b.ano_solicitud and
a.ano_solicitud = d.ano and
d.ano = b.ano_solicitud and
a.numero_solicitud = b.numero_solicitud and
a.tipo_solicitud_recurso='$recurso' and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$dependencia'
 GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud,b.ano,b.cod_sector,b.cod_programa,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar
 ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, b.ano,b.cod_sector,b.cod_programa,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar";






$query3 = "SELECT distinct a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud,b.ano,b.cod_sector,b.cod_programa
,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida,

null_cero((SELECT SUM(f.asignacion_anual_actualizada) FROM v_solicitud_cfpd05_pp2 f
WHERE
f.cod_presi = a.cod_presi and
f.cod_entidad = a.cod_entidad and
f.cod_tipo_inst = a.cod_tipo_inst and
f.cod_inst = a.cod_inst and
f.cod_dep = a.cod_dep and
f.cod_sector = b.cod_sector and
f.cod_programa = b.cod_programa and
f.cod_sub_prog = b.cod_sub_prog and
f.cod_proyecto = b.cod_proyecto and
f.cod_activ_obra = b.cod_activ_obra and
f.cod_partida=b.cod_partida and
f.ano = b.ano
group by f.cod_presi, f.cod_entidad,f.cod_tipo_inst, f.cod_inst, f.cod_dep, f.cod_partida
)) as asignacion_partida

FROM csrd01_solicitud_recurso_cuerpo a, csrd01_solicitud_recurso_partidas b, cfpd05 d
WHERE
a.cod_presi = b.cod_presi and
a.cod_entidad = b.cod_entidad and
a.cod_tipo_inst = b.cod_tipo_inst and
a.cod_inst = b.cod_inst and
a.cod_dep = d.cod_dep and
a.cod_presi = d.cod_presi and
a.cod_entidad = d.cod_entidad and
a.cod_tipo_inst = d.cod_tipo_inst and
a.cod_inst = d.cod_inst and
a.cod_dep = d.cod_dep and
d.cod_presi = b.cod_presi and
d.cod_entidad = b.cod_entidad and
d.cod_tipo_inst = b.cod_tipo_inst and
d.cod_inst = b.cod_inst and
d.cod_dep = b.cod_dep and
a.ano_solicitud = b.ano_solicitud and
a.ano_solicitud = d.ano and
d.ano = b.ano_solicitud and
a.numero_solicitud = b.numero_solicitud and
a.tipo_solicitud_recurso='$recurso' and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$dependencia'
 GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud,b.ano,b.cod_sector,b.cod_programa,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida
 ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, b.ano,b.cod_sector,b.cod_programa,b.cod_sub_prog,b.cod_proyecto,b.cod_activ_obra,b.cod_partida";


	$datos = $this->csrd01_tipo_solicitud->execute($query);
	$datos2 = $this->csrd01_tipo_solicitud->execute($query2);
	$datos3 = $this->csrd01_tipo_solicitud->execute($query3);
$subpartida=0;
$partida=0;
	for($i=0; $i<count($datos2); $i++){
		$subpartida+=$datos2[$i][0]['asignacion_subpartida'];
	}


	for($i=0; $i<count($datos3); $i++){
		$partida+=$datos3[$i][0]['asignacion_partida'];
	}
$disponibilidad=$partida-$subpartida;
$this->set('disponible',$disponibilidad);


*/
$datos = $this->csrd01_tipo_solicitud->execute($query);
$datos2 = $this->csrd01_tipo_solicitud->execute($query2);

//$subpartida=0;
//	for($i=0; $i<count($datos2); $i++){
//		$subpartida+=$datos2[$i][0]['monto_otros'];
//	}
//	$this->set('cuerpo', $datos);
//	$disponibilidad=$asignacion_anual-$subpartida;
//	$this->set('disponible',$disponibilidad);
	//pr($datos2);
$this->set('cuerpo', $datos);

//	pr($datos);


	}else if($var=='dep'){
		$this->layout="ajax";
		if($var2!=''){
			$deno_nomina = $this->arrd05->field('denominacion', $conditions = $this->condicionNDEP()." and cod_dep='$var2'", $order ="cod_dep ASC");
			$this->set('deno', $deno_nomina);
		}else{
			$this->set('deno', '');
		}

		$this->set('dep','');

	}else if($var=='recurso'){
		$this->layout="ajax";
		if($var2!=''){
			$deno_nomina = $this->csrd01_tipo_solicitud->field('denominacion', "cod_tipo_solicitud='$var2'", $order ="cod_tipo_solicitud ASC");
			$this->set('recur', $deno_nomina);
		}else{
			$this->set('recur', '');
		}

		$this->set('recurso','');
	}else if($var=='boton'){
		$this->layout="ajax";
		$this->set('boton','');
	}// fin if no

//echo $var."  ".$var2;
}//fin reporte_detalle_solicitud_organismo




function reporte_trimestre_2($var=null){
	 $this->layout="ajax";
	 $cod_presi = $this->Session->read('SScodpresi');
     $cod_entidad = $this->Session->read('SScodentidad');
     if(isset($var) && $var=="FORM"){
     	$this->layout="ajax";
	    $Ano=$this->ano_ejecucion();
	    $this->set('ANO',$Ano);
	    $this->set('ELFORM',true);
			     }else if(isset($var) && $var=="GENERAR"){
			     	$this->layout="pdf";
			     	$this->set('ELFORM',false);
			        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
				    if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
			              $Ano=$this->data["reporte"]["ano"];
				     }else{
				     	$Ano=$this->ano_ejecucion();
				     }
			    	$this->set('ANO',$Ano);
			    	$this->Session->write('ANO',$Ano);
			    	if(isset($this->data['cfpp05']['consolidacion'])){
			    	    $con=$this->SQLCA_consolidado_opcion($this->data['cfpp05']['consolidacion'], "a");
			    	    $tipo=$this->data['cfpp05']['consolidacion'];
			    	}else{
			    		$con=$this->SQLCA_consolidado_opcion(null, "a");
			    		$tipo=2;
			    	}
			    	if(isset($this->data['cfpp05']['tipo_gasto'])){
			    	    $tipo_gasto=$this->data['cfpp05']['tipo_gasto'];
			    	}else{
			    		$tipo_gasto=0;
			    	}
			    	if(isset($this->data['cfpp05']['tipo_recurso'])){
			    	    $tipo_recurso=$this->data['cfpp05']['tipo_recurso'];
			    	}else{
			    		$tipo_recurso=0;
			    	}

			    	$this->set('tipo_gasto',$tipo_gasto);
			    	$this->set('tipo_recurso',$tipo_recurso);
			        $titulo_a = $this->Session->read('dependencia');
			  	    $this->set('titulo_a',$titulo_a);

														// *** TIPO DE GASTO ****

        switch ($tipo_gasto) {
			case 0:
				$cod_tipo_gasto = " and (a.cod_tipo_gasto=1 OR a.cod_tipo_gasto=2 OR a.cod_tipo_gasto=3 OR a.cod_tipo_gasto=4) "; // Funcionamiento, Inversion, Situado y Transferencia
				break;
			case 1:
				$cod_tipo_gasto = " and a.cod_tipo_gasto=2 "; // Inversion
				break;
			case 2:
				$cod_tipo_gasto = " and (a.cod_tipo_gasto=1 OR a.cod_tipo_gasto=3 OR a.cod_tipo_gasto=4) "; // Funcionamiento, Situado y Transferencia
				break;

			default:
				break;
		}

													// *** TIPO DE RECURSOS ****

        if($tipo_recurso==0 || $tipo_recurso=='0'){
        	$cond_tipo_recurso= " and (a.tipo_presupuesto= 1 OR a.tipo_presupuesto= 2 OR a.tipo_presupuesto= 3 OR a.tipo_presupuesto= 4 OR a.tipo_presupuesto= 5 OR a.tipo_presupuesto= 6 OR a.tipo_presupuesto= 7 OR a.tipo_presupuesto= 8) ";
        }else{
        	$cond_tipo_recurso= " and a.tipo_presupuesto=".$tipo_recurso;
        }


/*
		switch ($tipo_recurso) {
			case 0:
				$cond_tipo_recurso= " and (a.tipo_presupuesto= 1 OR a.tipo_presupuesto= 2 OR a.tipo_presupuesto= 3 OR a.tipo_presupuesto= 4 OR a.tipo_presupuesto= 5 OR a.tipo_presupuesto= 6) ";
				break;
			case 1:
				$cond_tipo_recurso= " and a.tipo_presupuesto= 1 "; // Ordinario
				break;
			case 2:
				$cond_tipo_recurso= " and a.tipo_presupuesto= 2 "; // Coordinado
				break;
			case 3:
				$cond_tipo_recurso= " and a.tipo_presupuesto= 3 "; // Fci
				break;
			case 4:
				$cond_tipo_recurso= " and a.tipo_presupuesto= 4 "; // Mpps
				break;
			case 5:
				$cond_tipo_recurso= " and a.tipo_presupuesto= 5 "; // Ingresos Extraordinarios
				break;
			case 6:
				$cond_tipo_recurso= " and a.tipo_presupuesto= 6 "; // Ingresos Propios
				break;

			default:
				break;
		}
*/


	if($tipo==1){ // *** INSTITUCION ***
		$modelov="v_balance_ejecucion_partidas_inst";
		$sql = "(((SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_2_partida a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer
         LIMIT 1)) AS denominacion, sum(a.compromiso_ene + a.compromiso_feb + a.compromiso_mar) AS compromiso_tri_uno, sum(a.causado_ene + a.causado_feb + a.causado_mar) AS causado_tri_uno, sum(a.pagado_ene + a.pagado_feb + a.pagado_mar) AS pagado_tri_uno, sum(a.compromiso_abr + a.compromiso_may + a.compromiso_jun) AS compromiso_tri_dos, sum(a.causado_abr + a.causado_may + a.causado_jun) AS causado_tri_dos, sum(a.pagado_abr + a.pagado_may + a.pagado_jun) AS pagado_tri_dos, sum(a.compromiso_jul + a.compromiso_ago + a.compromiso_sep) AS compromiso_tri_tres, sum(a.causado_jul + a.causado_ago + a.causado_sep) AS causado_tri_tres, sum(a.pagado_jul + a.pagado_ago + a.pagado_sep) AS pagado_tri_tres, sum(a.compromiso_oct + a.compromiso_nov + a.compromiso_dic) AS compromiso_tri_cuarto, sum(a.causado_oct + a.causado_nov + a.causado_dic) AS causado_tri_cuarto, sum(a.pagado_oct + a.pagado_nov + a.pagado_dic) AS pagado_tri_cuarto
   FROM cfpd05 a
  WHERE ".$con." and ano=".$Ano.$cod_tipo_gasto.$cond_tipo_recurso."
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, a.cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_3_generica a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica
         LIMIT 1)) AS denominacion, sum(a.compromiso_ene + a.compromiso_feb + a.compromiso_mar) AS compromiso_tri_uno, sum(a.causado_ene + a.causado_feb + a.causado_mar) AS causado_tri_uno, sum(a.pagado_ene + a.pagado_feb + a.pagado_mar) AS pagado_tri_uno, sum(a.compromiso_abr + a.compromiso_may + a.compromiso_jun) AS compromiso_tri_dos, sum(a.causado_abr + a.causado_may + a.causado_jun) AS causado_tri_dos, sum(a.pagado_abr + a.pagado_may + a.pagado_jun) AS pagado_tri_dos, sum(a.compromiso_jul + a.compromiso_ago + a.compromiso_sep) AS compromiso_tri_tres, sum(a.causado_jul + a.causado_ago + a.causado_sep) AS causado_tri_tres, sum(a.pagado_jul + a.pagado_ago + a.pagado_sep) AS pagado_tri_tres, sum(a.compromiso_oct + a.compromiso_nov + a.compromiso_dic) AS compromiso_tri_cuarto, sum(a.causado_oct + a.causado_nov + a.causado_dic) AS causado_tri_cuarto, sum(a.pagado_oct + a.pagado_nov + a.pagado_dic) AS pagado_tri_cuarto
   FROM cfpd05 a
  WHERE ".$con." and ano=".$Ano.$cod_tipo_gasto.$cond_tipo_recurso."
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, a.cod_generica)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_4_especifica a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica
         LIMIT 1)) AS denominacion, sum(a.compromiso_ene + a.compromiso_feb + a.compromiso_mar) AS compromiso_tri_uno, sum(a.causado_ene + a.causado_feb + a.causado_mar) AS causado_tri_uno, sum(a.pagado_ene + a.pagado_feb + a.pagado_mar) AS pagado_tri_uno, sum(a.compromiso_abr + a.compromiso_may + a.compromiso_jun) AS compromiso_tri_dos, sum(a.causado_abr + a.causado_may + a.causado_jun) AS causado_tri_dos, sum(a.pagado_abr + a.pagado_may + a.pagado_jun) AS pagado_tri_dos, sum(a.compromiso_jul + a.compromiso_ago + a.compromiso_sep) AS compromiso_tri_tres, sum(a.causado_jul + a.causado_ago + a.causado_sep) AS causado_tri_tres, sum(a.pagado_jul + a.pagado_ago + a.pagado_sep) AS pagado_tri_tres, sum(a.compromiso_oct + a.compromiso_nov + a.compromiso_dic) AS compromiso_tri_cuarto, sum(a.causado_oct + a.causado_nov + a.causado_dic) AS causado_tri_cuarto, sum(a.pagado_oct + a.pagado_nov + a.pagado_dic) AS pagado_tri_cuarto
   FROM cfpd05 a
  WHERE ".$con." and ano=".$Ano.$cod_tipo_gasto.$cond_tipo_recurso."
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, 0 AS cod_auxiliar, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_5_sub_espec a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica AND a1.cod_sub_espec = a.cod_sub_espec
         LIMIT 1)) AS denominacion, sum(a.compromiso_ene + a.compromiso_feb + a.compromiso_mar) AS compromiso_tri_uno, sum(a.causado_ene + a.causado_feb + a.causado_mar) AS causado_tri_uno, sum(a.pagado_ene + a.pagado_feb + a.pagado_mar) AS pagado_tri_uno, sum(a.compromiso_abr + a.compromiso_may + a.compromiso_jun) AS compromiso_tri_dos, sum(a.causado_abr + a.causado_may + a.causado_jun) AS causado_tri_dos, sum(a.pagado_abr + a.pagado_may + a.pagado_jun) AS pagado_tri_dos, sum(a.compromiso_jul + a.compromiso_ago + a.compromiso_sep) AS compromiso_tri_tres, sum(a.causado_jul + a.causado_ago + a.causado_sep) AS causado_tri_tres, sum(a.pagado_jul + a.pagado_ago + a.pagado_sep) AS pagado_tri_tres, sum(a.compromiso_oct + a.compromiso_nov + a.compromiso_dic) AS compromiso_tri_cuarto, sum(a.causado_oct + a.causado_nov + a.causado_dic) AS causado_tri_cuarto, sum(a.pagado_oct + a.pagado_nov + a.pagado_dic) AS pagado_tri_cuarto
   FROM cfpd05 a
  WHERE ".$con." and ano=".$Ano.$cod_tipo_gasto.$cond_tipo_recurso."
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec)
ORDER BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano, cod_partida, cod_generica, cod_especifica, cod_sub_espec
";
	}
	else { // *** DEPENDENCIA ***
		$modelov="v_balance_ejecucion_partidas_dep";
		$sql = "(((SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, 0 AS cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_2_partida a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer
         LIMIT 1)) AS denominacion, sum(a.compromiso_ene + a.compromiso_feb + a.compromiso_mar) AS compromiso_tri_uno, sum(a.causado_ene + a.causado_feb + a.causado_mar) AS causado_tri_uno, sum(a.pagado_ene + a.pagado_feb + a.pagado_mar) AS pagado_tri_uno, sum(a.compromiso_abr + a.compromiso_may + a.compromiso_jun) AS compromiso_tri_dos, sum(a.causado_abr + a.causado_may + a.causado_jun) AS causado_tri_dos, sum(a.pagado_abr + a.pagado_may + a.pagado_jun) AS pagado_tri_dos, sum(a.compromiso_jul + a.compromiso_ago + a.compromiso_sep) AS compromiso_tri_tres, sum(a.causado_jul + a.causado_ago + a.causado_sep) AS causado_tri_tres, sum(a.pagado_jul + a.pagado_ago + a.pagado_sep) AS pagado_tri_tres, sum(a.compromiso_oct + a.compromiso_nov + a.compromiso_dic) AS compromiso_tri_cuarto, sum(a.causado_oct + a.causado_nov + a.causado_dic) AS causado_tri_cuarto, sum(a.pagado_oct + a.pagado_nov + a.pagado_dic) AS pagado_tri_cuarto
   FROM cfpd05 a
  WHERE ".$con." and ano=".$Ano.$cod_tipo_gasto.$cond_tipo_recurso."
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_3_generica a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica
         LIMIT 1)) AS denominacion, sum(a.compromiso_ene + a.compromiso_feb + a.compromiso_mar) AS compromiso_tri_uno, sum(a.causado_ene + a.causado_feb + a.causado_mar) AS causado_tri_uno, sum(a.pagado_ene + a.pagado_feb + a.pagado_mar) AS pagado_tri_uno, sum(a.compromiso_abr + a.compromiso_may + a.compromiso_jun) AS compromiso_tri_dos, sum(a.causado_abr + a.causado_may + a.causado_jun) AS causado_tri_dos, sum(a.pagado_abr + a.pagado_may + a.pagado_jun) AS pagado_tri_dos, sum(a.compromiso_jul + a.compromiso_ago + a.compromiso_sep) AS compromiso_tri_tres, sum(a.causado_jul + a.causado_ago + a.causado_sep) AS causado_tri_tres, sum(a.pagado_jul + a.pagado_ago + a.pagado_sep) AS pagado_tri_tres, sum(a.compromiso_oct + a.compromiso_nov + a.compromiso_dic) AS compromiso_tri_cuarto, sum(a.causado_oct + a.causado_nov + a.causado_dic) AS causado_tri_cuarto, sum(a.pagado_oct + a.pagado_nov + a.pagado_dic) AS pagado_tri_cuarto
   FROM cfpd05 a
  WHERE ".$con." and ano=".$Ano.$cod_tipo_gasto.$cond_tipo_recurso."
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_4_especifica a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica
         LIMIT 1)) AS denominacion, sum(a.compromiso_ene + a.compromiso_feb + a.compromiso_mar) AS compromiso_tri_uno, sum(a.causado_ene + a.causado_feb + a.causado_mar) AS causado_tri_uno, sum(a.pagado_ene + a.pagado_feb + a.pagado_mar) AS pagado_tri_uno, sum(a.compromiso_abr + a.compromiso_may + a.compromiso_jun) AS compromiso_tri_dos, sum(a.causado_abr + a.causado_may + a.causado_jun) AS causado_tri_dos, sum(a.pagado_abr + a.pagado_may + a.pagado_jun) AS pagado_tri_dos, sum(a.compromiso_jul + a.compromiso_ago + a.compromiso_sep) AS compromiso_tri_tres, sum(a.causado_jul + a.causado_ago + a.causado_sep) AS causado_tri_tres, sum(a.pagado_jul + a.pagado_ago + a.pagado_sep) AS pagado_tri_tres, sum(a.compromiso_oct + a.compromiso_nov + a.compromiso_dic) AS compromiso_tri_cuarto, sum(a.causado_oct + a.causado_nov + a.causado_dic) AS causado_tri_cuarto, sum(a.pagado_oct + a.pagado_nov + a.pagado_dic) AS pagado_tri_cuarto
   FROM cfpd05 a
  WHERE ".$con." and ano=".$Ano.$cod_tipo_gasto.$cond_tipo_recurso."
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, 0 AS cod_auxiliar, upper(( SELECT a1.denominacion
           FROM cfpd01_ano_5_sub_espec a1
          WHERE a1.ejercicio = a.ano AND a1.cod_grupo = 4 AND a1.cod_partida = substr(a.cod_partida::text, 2, 2)::integer AND a1.cod_generica = a.cod_generica AND a1.cod_especifica = a.cod_especifica AND a1.cod_sub_espec = a.cod_sub_espec
         LIMIT 1)) AS denominacion, sum(a.compromiso_ene + a.compromiso_feb + a.compromiso_mar) AS compromiso_tri_uno, sum(a.causado_ene + a.causado_feb + a.causado_mar) AS causado_tri_uno, sum(a.pagado_ene + a.pagado_feb + a.pagado_mar) AS pagado_tri_uno, sum(a.compromiso_abr + a.compromiso_may + a.compromiso_jun) AS compromiso_tri_dos, sum(a.causado_abr + a.causado_may + a.causado_jun) AS causado_tri_dos, sum(a.pagado_abr + a.pagado_may + a.pagado_jun) AS pagado_tri_dos, sum(a.compromiso_jul + a.compromiso_ago + a.compromiso_sep) AS compromiso_tri_tres, sum(a.causado_jul + a.causado_ago + a.causado_sep) AS causado_tri_tres, sum(a.pagado_jul + a.pagado_ago + a.pagado_sep) AS pagado_tri_tres, sum(a.compromiso_oct + a.compromiso_nov + a.compromiso_dic) AS compromiso_tri_cuarto, sum(a.causado_oct + a.causado_nov + a.causado_dic) AS causado_tri_cuarto, sum(a.pagado_oct + a.pagado_nov + a.pagado_dic) AS pagado_tri_cuarto
   FROM cfpd05 a
  WHERE ".$con." and ano=".$Ano.$cod_tipo_gasto.$cond_tipo_recurso."
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec)
ORDER BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano, cod_partida, cod_generica, cod_especifica, cod_sub_espec";
	}
		$this->set("modelo",0);
		$result = $this->$modelov->execute($sql);
		$this->set("DATA",$result);
     }//fin generar reporte
}//fin reporte trimestre 2








function reporte_registro_compromiso_pre_impreso($ir=null,$opcion=null, $cont=null){
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

//	cepd01_compromiso_cuerpo
//cepd01_compromiso_partidas
	if($ir=='no'){
		$this->layout="ajax";

		$this->set('ir','no');
		$this->set('usuario', $this->Session->read('nom_usuario'));
	}else if($ir=='vista'){
		$this->layout="ajax";

		$ano = $this->ano_ejecucion();
		$this->set('year', $ano);
		$this->set('vista','');
		$this->set('opcion',$opcion);




			$url                  =  "/select_ventana_generar_reporte/v_reporte_emision_rc_pre_imp_1";
			$width_aux            =  "750px";
			$height_aux           =  "400px";
			$title_aux            =  "Buscar";
			$resizable_aux        =  false;
			$maximizable_aux      =  false;
			$minimizable_aux      =  false;
			$closable_aux         =  false;

				  if($opcion==2){
				         echo"<script>";
				           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
				         echo"</script>";
			    	}else{
			              echo"<script>";
			               echo  " Windows.close(document.getElementById('capa_ventana').value)";
			              echo"</script>";

				}//fin else







	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
		$opcion=$this->data['cepp01_compromiso']['radio'];
			if($opcion==1){
				$this->set('modo',1);
				$ano=$this->data['cepp01_compromiso']['ano'.$cont.''];
				$num_a=$this->data['cepp01_compromiso']['numero_a'.$cont.''];
				$num_b=$this->data['cepp01_compromiso']['numero_b'];
				$k=0;
				for($i=$num_a;$i<=$num_b;$i++){
					$verifica=$this->cepd01_compromiso_cuerpo->FindAll($this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$i);
					if($verifica!=null){
			$query = " SELECT
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_documento,
						a.numero_documento,
						a.cod_tipo_compromiso,
						a.fecha_documento,
						a.tipo_recurso,
						a.rif,
						a.cedula_identidad,
						a.concepto,
						a.beneficiario,
						a.condicion_juridica,
						a.monto,
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
						b.monto as monto_partidas
						FROM cepd01_compromiso_partidas b,cepd01_compromiso_cuerpo a
						WHERE
						a.cod_presi=a.cod_presi and
						a.cod_entidad=b.cod_entidad and
						a.cod_tipo_inst=b.cod_tipo_inst and
						a.cod_inst=b.cod_inst and
						a.cod_dep=b.cod_dep and
						a.ano_documento=b.ano_documento and
						a.numero_documento=b.numero_documento and
						a.cod_presi=".$cod_presi." and
						a.cod_entidad=".$cod_entidad." and
						a.cod_tipo_inst=".$cod_tipo_inst." and
						a.cod_inst=".$cod_inst." and
						a.cod_dep=".$cod_dep." and
						a.ano_documento=".$ano." and
						a.numero_documento=".$i."
						ORDER BY
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_documento,
						a.numero_documento,
						b.cod_sector,
						b.cod_programa,
						b.cod_sub_prog,
						b.cod_proyecto,
						b.cod_activ_obra,
						b.cod_partida,
						b.cod_generica,
						b.cod_especifica,
						b.cod_sub_espec,
						b.cod_auxiliar";
				$vector_data[$k]=$this->cepd01_compromiso_cuerpo->execute($query);
				$k++;
					}//fin verifica
				}//fio for
                        $cuerpo = $vector_data;
                 for($i=0;$i<count($cuerpo);$i++){
                 	$tipo_gasto_1[$i] = 0;
					$tipo_gasto_2[$i] = 0;
					for($j=0;$j<count($cuerpo[$i]);$j++){
										$cod_sector=$cuerpo[$i][$j][0]['cod_sector'];
									    $cod_programa=$cuerpo[$i][$j][0]['cod_programa'];
									    $cod_sub_prog=$cuerpo[$i][$j][0]['cod_sub_prog'];
										$cod_proyecto=$cuerpo[$i][$j][0]['cod_proyecto'];
										$cod_activ_obra=$cuerpo[$i][$j][0]['cod_activ_obra'];
										$cod_partida=$cuerpo[$i][$j][0]['cod_partida'];
										$cod_generica=$cuerpo[$i][$j][0]['cod_generica'];
										$cod_especifica=$cuerpo[$i][$j][0]['cod_especifica'];
										$cod_sub_espec=$cuerpo[$i][$j][0]['cod_sub_espec'];
										$cod_auxiliar=$cuerpo[$i][$j][0]['cod_auxiliar'];
										$cadena=$cod_partida.$cod_generica.$cod_especifica.$cod_sub_espec;

								$sql=$condicion." and ano=".$ano."  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
								$busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
					         if($busqueda!=null){
									     if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
										    $tipo_gasto_1[$i] = 1;
									}else  {$tipo_gasto_2[$i] = 1; }
								}//fin if

					   }//fin for
					}//fin for



					$this->set('tipo_1',$tipo_gasto_1);
					$this->set('tipo_2',$tipo_gasto_2);


//			pr($vector_data);
//echo count($vector_data[0]);
				$this->set('cuerpo',$vector_data);
			}else{
				$this->set('modo',2);
				$ano   = $this->data['cepp01_compromiso']['ano'.$cont.''];
				$num_a = $this->data['cepp01_compromiso']['numero_a'.$cont.''];
//				$datos = $this->cepd01_compromiso_cuerpo->FindAll($this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$num_a);
			$query  =  "SELECT
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_documento,
						a.numero_documento,
						a.cod_tipo_compromiso,
						a.fecha_documento,
						a.tipo_recurso,
						a.rif,
						a.cedula_identidad,
						a.concepto,
						a.beneficiario,
						a.condicion_juridica,
						a.monto,
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
						b.monto as monto_partidas
						FROM cepd01_compromiso_partidas b,cepd01_compromiso_cuerpo a
						WHERE
						a.cod_presi=a.cod_presi and
						a.cod_entidad=b.cod_entidad and
						a.cod_tipo_inst=b.cod_tipo_inst and
						a.cod_inst=b.cod_inst and
						a.cod_dep=b.cod_dep and
						a.ano_documento=b.ano_documento and
						a.numero_documento=b.numero_documento and
						a.cod_presi=".$cod_presi." and
						a.cod_entidad=".$cod_entidad." and
						a.cod_tipo_inst=".$cod_tipo_inst." and
						a.cod_inst=".$cod_inst." and
						a.cod_dep=".$cod_dep." and
						a.ano_documento=".$ano." and
						a.numero_documento=".$num_a."
						ORDER BY
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_documento,
						a.numero_documento,
						b.cod_sector,
						b.cod_programa,
						b.cod_sub_prog,
						b.cod_proyecto,
						b.cod_activ_obra,
						b.cod_partida,
						b.cod_generica,
						b.cod_especifica,
						b.cod_sub_espec,
						b.cod_auxiliar";
				$datos = $this->cepd01_compromiso_cuerpo->execute($query);
				$this->set('cuerpo',$datos);
					$partidacorriente = 407120;
					$partidacapital   = 407220;
					$tipo_gasto_1     = 0;
					$tipo_gasto_2     = 0;

					for($i=0;$i<count($datos);$i++){
						        //$year_sql       = $datos[$i][0]['ano'];
								$cod_sector     = $datos[$i][0]['cod_sector'];
							    $cod_programa   = $datos[$i][0]['cod_programa'];
							    $cod_sub_prog   = $datos[$i][0]['cod_sub_prog'];
								$cod_proyecto   = $datos[$i][0]['cod_proyecto'];
								$cod_activ_obra = $datos[$i][0]['cod_activ_obra'];
								$cod_partida    = $datos[$i][0]['cod_partida'];
								$cod_generica   = $datos[$i][0]['cod_generica'];
								$cod_especifica = $datos[$i][0]['cod_especifica'];
								$cod_sub_espec  = $datos[$i][0]['cod_sub_espec'];
								$cod_auxiliar   = $datos[$i][0]['cod_auxiliar'];
								$cadena         = $cod_partida.$cod_generica.$cod_especifica.$cod_sub_espec;
								if($partidacorriente == $cadena){ $tipo_gasto_1 = 1;}
								if($partidacapital   == $cadena){ $tipo_gasto_2 = 1;}
					}//fin for

			$sql=$condicion." and ano=".$ano."  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
			$busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
				     if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
					    $tipo_gasto_1 = 1;
				}else  {$tipo_gasto_2 = 1; }
			}//fin if


					$this->set('tipo_1',$tipo_gasto_1);
					$this->set('tipo_2',$tipo_gasto_2);
				//pr($datos);
			}// fin opcion 2
	  }// fin ir si
}//fin reporte_registro_compromiso_new


// MODIFICACIONES NUEVAS

 // reporte relacion de ordenes de pago sin retencion de timbre fiscal
    function reporte_ordenes_sin_timbre_fiscal($ir=null){

      // funcion de verificacion de entrada
      $this->verifica_entrada('123');

      if($ir!=null){
        if($ir=='si'){
          # vista formulario
         
          $this->layout='ajax';
          $this->set('anho',$anho=$this->ano_ejecucion());
          $_SESSION['anho_orden_pago'] = $this->ano_ejecucion();
          $cod_dep= $this->Session->read('SScoddep');
          if($cod_dep==1){
            $dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            $this->concatena($dep, 'dependencias');
          }else{
            $dep = $this->arrd05->generateList($conditions = $this->condicion(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            $this->concatena($dep, 'dependencias');
          }
          $this->set('ir','si');

        }elseif ($ir=='no') {
          # reporte
         
            $this->layout = "ajax";
            $cod_presi = $this->Session->read('SScodpresi');
            $cod_entidad = $this->Session->read('SScodentidad');
            $cod_tipo_inst = $this->Session->read('SScodtipoinst');
            $cod_inst = $this->Session->read('SScodinst');
            $cod_dep = $this->Session->read('SScoddep');
            
            if($this->data["relacion_ordenpago"]["ano"]!=NULL){
              $ano_ejecucion=$this->data["relacion_ordenpago"]["ano"];
              if($ano_ejecucion<2014){
                $condicion_ano_ut="(a.fecha_orden_pago < '2013-12-31' and
                     a.monto_siniva  > 5350)";
              }elseif($ano_ejecucion==2014){
                $condicion_ano_ut="(a.fecha_orden_pago BETWEEN '2014-01-01' AND '2014-02-18' and
                     a.monto_siniva  > 5350) OR
                    (a.fecha_orden_pago BETWEEN '2014-02-19' AND '2014-12-31' and
                     a.monto_siniva  > 6350)";
              }elseif ($ano_ejecucion==2015) {
                $condicion_ano_ut="(a.fecha_orden_pago BETWEEN '2015-01-01' AND '2015-02-24' and
                     a.monto_siniva  > 6350) OR
                    (a.fecha_orden_pago BETWEEN '2015-02-25' AND '2015-12-31' and
                     a.monto_siniva  > 7500)";
              }elseif ($ano_ejecucion==2016){
                $condicion_ano_ut="(a.fecha_orden_pago BETWEEN '2016-01-01' AND '2016-02-11' and
                     a.monto_siniva  > 7500) OR
                    (a.fecha_orden_pago BETWEEN '2016-02-12' AND '2016-12-30' and
                     a.monto_siniva  > b.desde_monto_timbre)";
              }else{
              	$condicion_ano_ut="(a.fecha_orden_pago BETWEEN '$ano_ejecucion-01-01' AND '$ano_ejecucion-12-30' and
                     a.monto_siniva  > b.desde_monto_timbre)";
              }
            }else{
              $ano_ejecucion = $this->ano_ejecucion();
            }

            if($this->data["relacion_ordenpago"]["tipo_reporte"]==1){
              $tipo_reporte="a.porcentaje_timbre_fiscal=b.porcentaje_timbre_fiscal";
            }else{
              $tipo_reporte="a.porcentaje_timbre_fiscal!=b.porcentaje_timbre_fiscal";
            }

            if($this->data["relacion_ordenpago"]["cod_dep"]!=NULL){
              $cond_cod_dep = "a.cod_dep = ".$this->data["relacion_ordenpago"]["cod_dep"];
            }else{
              $cond_cod_dep ="a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep=".$cod_dep;
            }
        

            $datos_cuerpo_timbre= $this->cepd03_ordenpago_cuerpo->execute("SELECT
              c.denominacion as dependencia,
              a.ano_orden_pago, a.numero_orden_pago, a.fecha_orden_pago, 
              a.rif, a.beneficiario, a.concepto, 
              a.monto_total, a.monto_descontar_impuesto, a.monto_orden_pago, 
              a.monto_timbre_fiscal

              FROM cepd03_ordenpago_cuerpo as a, cscd04_ordencompra_parametros as b, arrd05 as c
      
              WHERE
                a.ano_orden_pago=$ano_ejecucion and
                $tipo_reporte and
                numero_cheque!=0 and
                username_registro!='AUTO_NOMINA' and 
                (".
                  $condicion_ano_ut  
                .") and ".
                    $cond_cod_dep
                  ." and
                    b.cod_presi              =   a.cod_presi and
                    b.cod_entidad            =   a.cod_entidad and
                    b.cod_tipo_inst          =   a.cod_tipo_inst and
                    b.cod_inst               =   a.cod_inst and
                    b.cod_dep                =   a.cod_dep and
                    c.cod_presi              =   a.cod_presi and
                    c.cod_entidad            =   a.cod_entidad and
                    c.cod_tipo_inst          =   a.cod_tipo_inst and
                    c.cod_inst               =   a.cod_inst and
                    c.cod_dep                =   a.cod_dep
                    
                ORDER BY  a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_dep, a.ano_orden_pago, a.numero_orden_pago;");


                if(count($datos_cuerpo_timbre,1)>0){
                    $this->set('datos',$datos_cuerpo_timbre);
                    $this->set('vacio','no');
                }else{
                    $this->set('vacio','si');
                    $this->set('mensaje','NO SE ENCONTRARÓN ORDENES SIN RETENCIÓN DEL TIMBRE FISCAL PARA EL AÑO '.$ano_ejecucion);
                }
                $this->set('tipo',$this->data["relacion_ordenpago"]["tipo_reporte"]);
                
                $this->set('ir', 'no');
                // FIN ACCION REPORTE
        }
      }
    }
//              $this->set('titulo_a', $this->Session->read('dependencia'));
//              $this->set('titulo_inst', $this->Session->read('entidad_federal'));
//fin funcion reporte ordenes sin timbre fiscal

    //listar dependencia para parametro de ordenes de pago









}//fin class
?>
