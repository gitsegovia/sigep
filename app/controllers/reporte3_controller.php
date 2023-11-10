<?php

class Reporte3Controller extends AppController{


    var $name    = "reporte3";
    var $uses    = array('arrd05', 'cfpd07_plan_inversion', 'cstd01_entidades_bancarias', 'cugd02_dependencia', 'cstd07_retenciones_cuerpo_iva',
                         'ccfd04_cierre_mes', 'cstd03_cheque_ordenes', 'cstd03_cheque_cuerpo', 'cstd03_cheque_partidas',
                         'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_partidas', 'cobd01_contratoobras_valuacion_cuerpo',
                         'cobd01_contratoobras_cuerpo', 'cfpd07_obras_cuerpo', 'csrd01_solicitud_recurso_cuerpo', 'csrd01_tipo_solicitud',
                         'cstd09_notadebito_ordenes','cfpd05_auxiliar','cfpd01_formulacion','cfpd97','cstd07_retenciones_cuerpo_islr',
                         'cstd07_retenciones_cuerpo_multa', 'cstd07_retenciones_cuerpo_responsabilidad', 'cstd07_retenciones_partidas_multa',
                         'cstd07_retenciones_partidas_responsabilidad','cstd06_comprobante_poremitir_multa',
                         'cstd06_comprobante_poremitir_responsabilidad', 'cstd06_comprobante_numero_multa', 'cugd06_oficios_poremitir_comun', 'cepd01_compromiso_poremitir',
                         'cstd06_comprobante_numero_responsabilidad', 'cstd06_comprobante_cuerpo_multa', 'cstd06_comprobante_cuerpo_responsabilidad','cugd05_restriccion_clave', 'cugd99_firmas_responsabilidad','cstd03_movimientos_manuales');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');






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



function agregarCeros($valor=null,$cant_relleno=null){
	$relleno='';
	$valor_retorno='';
	$char = strlen($valor);
	for($j=0; $j<$cant_relleno - $char; $j++){
		$relleno .= '0';
	}
	$valor_retorno .= $relleno.''.$valor;
	return $valor_retorno;
}



function cuerpo_asignacion($var2=null, $var1=null, $var3=null){


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
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);  $cond = "";
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));

     if(isset($_SESSION['select_dependencia'])){if($_SESSION['select_dependencia']!='si'){$cod_dep=$_SESSION['select_dependencia']; $Modulo="2"; }else{$Modulo="2";}}

     if($var1!=6){

          if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
			 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.tipo_recurso=".$var1."  ";
			    $cod_dep_sql = " and x.tipo_recurso=".$var1." ";
			    $cod_dep_sql2 = "";
			    $this->set('global', 'si');
			}else{
				$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$cod_dep."  and a.tipo_recurso=".$var1."   ";
			    $cod_dep_sql  = "and x.cod_dep_original=".$cod_dep." and x.tipo_recurso=".$var1." ";
			    $cod_dep_sql2 = " , a.cod_dep";
			    $this->set('global', 'no');
			}//fin else


				 $datos = $this->cfpd07_plan_inversion->execute("SELECT
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst ".$cod_dep_sql2.", a.tipo_recurso,
									(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and status=1) as asignacion_inicial,
									(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and status=2) as credito_adicional,
									(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." ) as total
									FROM
									       cfpd07_obras_cuerpo a
									WHERE
									  ".$cond."
									group by
										a.cod_presi,
										a.cod_entidad,
										a.cod_tipo_inst,
										a.cod_inst ".$cod_dep_sql2.", a.tipo_recurso
									order by
										a.cod_presi,
										a.cod_entidad,
										a.cod_tipo_inst,
										a.cod_inst ".$cod_dep_sql2.";	");



     }else{




     	    if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
			 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  ";
			    $cod_dep_sql = " ";
			    $cod_dep_sql2 = "";
			    $this->set('global', 'si');
			}else{
				$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$cod_dep." ";
			    $cod_dep_sql  = "and x.cod_dep_original=".$cod_dep."";
			    $cod_dep_sql2 = " , a.cod_dep";
			    $this->set('global', 'no');
			}//fin else

				 $datos = $this->cfpd07_plan_inversion->execute("SELECT
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst ".$cod_dep_sql2.",
									(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and status=1) as asignacion_inicial,
									(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and status=2) as credito_adicional,
									(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." ) as total
									FROM
									       cfpd07_obras_cuerpo a
									WHERE
									  ".$cond."
									group by
										a.cod_presi,
										a.cod_entidad,
										a.cod_tipo_inst,
										a.cod_inst ".$cod_dep_sql2."
									order by
										a.cod_presi,
										a.cod_entidad,
										a.cod_tipo_inst,
										a.cod_inst ".$cod_dep_sql2.";	");




     }//fin else


              if(!isset($datos[0][0]['asignacion_inicial'])){
                   $datos[0][0]['asignacion_inicial'] = "";
                   $datos[0][0]['credito_adicional'] = "";
                   $datos[0][0]['total'] = "";
              }//fin if

                 $this->set('asignacion_inicial', $datos[0][0]['asignacion_inicial']);
                 $this->set('credito_adicional', $datos[0][0]['credito_adicional']);
                 $this->set('total', $datos[0][0]['total']);

                 $this->set('year', $var2);
                 $this->set('tipo_recurso', $var1);
                 $this->set('clasificacion_recurso', $var3);





}//fin function




function reporte_retencion_islr_acumulado_pend_por_periodo($desde=null, $hasta=null){
	$this->layout = "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano = $this->ano_ejecucion();
	//$cond=$this->SQLCA();
	  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $datos_cuerpo_iva_2       =       "";
if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'si');
}else{
	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and a.cod_dep=".$cod_dep." and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'no');
}//fin else

	  $datos_cuerpo_isrl_2= $this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.clase_orden,
  a.numero_orden_pago,
  a.monto,
  a.fecha_proceso_registro,
  a.status,
  a.ano_movimiento,
  a.cod_entidad_bancaria,
  a.cod_sucursal,
  b.cuenta_bancaria,
  a.numero_cheque,
  a.fecha_proceso_anulacion,
  b.beneficiario,
  b.monto_descontar_impuesto,
  b.porcentaje_retencion_iva,
  b.porcentaje_timbre_fiscal,
  b.monto_retencion_fielcumplimiento,
  b.monto_retencion_laboral,
  b.monto_iva,
  b.monto_coniva,
  b.monto_sustraendo,
  b.porcentaje_islr,
  b.porcentaje_impuesto_municipal,
  c.denominacion

FROM cstd07_retenciones_cuerpo_islr a ,  cepd03_ordenpago_cuerpo b, cugd02_dependencias c  where ".$cond."


and b.cod_presi              =   a.cod_presi and
    b.cod_entidad            =   a.cod_entidad and
    b.cod_tipo_inst          =   a.cod_tipo_inst and
    b.cod_inst               =   a.cod_inst and
    b.cod_dep                =   a.cod_dep and
    c.cod_tipo_institucion   =   a.cod_tipo_inst and
    c.cod_institucion        =   a.cod_inst and
    c.cod_dependencia        =   a.cod_dep and
    c.tipo_dependencia       =   1 and
    b.ano_orden_pago         =   a.ano_orden_pago and
    b.numero_orden_pago      =   a.numero_orden_pago ORDER BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_dep, b.ano_orden_pago, b.cuenta_bancaria,  b.numero_orden_pago;");


if($datos_cuerpo_isrl_2!=""){
		$this->set('datos_cuerpo_isrl',$datos_cuerpo_isrl_2);
		$this->set('vacio','no');
	}else{
		$this->set('vacio','si');
		if($this->data['retencion_islr_acumulado']['status']==1){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS EN LA RETENCIÓN DEL I.S.L.R. PENDIENTE');
		}elseif($this->data['retencion_islr_acumulado']['status']==3){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS POR RESTAURAR EN LA RETENCIÓN DEL I.S.L.R.');
		}
	}
	$this->set('titulo_a',$this->Session->read('dependencia'));
	$this->set('titulo_inst','GOBERNACIÓN DEL ESTADO FALCÓN');
}//reporte_retencion_islr_acumulado_pend













function reporte_retencion_multa_acumulado_pend_por_periodo($desde=null, $hasta=null){
	$this->layout = "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano = $this->ano_ejecucion();
	//$cond=$this->SQLCA();
	  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $datos_cuerpo_iva_2       =       "";
if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'si');
}else{
	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and a.cod_dep=".$cod_dep." and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'no');
}//fin else

	  $datos_cuerpo_multa_2= $this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.clase_orden,
  a.numero_orden_pago,
  a.monto,
  a.fecha_proceso_registro,
  a.status,
  a.ano_movimiento,
  a.cod_entidad_bancaria,
  a.cod_sucursal,
  b.cuenta_bancaria,
  a.numero_cheque,
  a.fecha_proceso_anulacion,
  b.beneficiario,
  b.monto_descontar_impuesto,
  b.porcentaje_retencion_iva,
  b.porcentaje_timbre_fiscal,
  b.monto_retencion_fielcumplimiento,
  b.monto_retencion_laboral,
  b.monto_iva,
  b.monto_coniva,
  b.monto_sustraendo,
  b.porcentaje_islr,
  b.porcentaje_impuesto_municipal,
  c.denominacion

FROM cstd07_retenciones_cuerpo_multa a ,  cepd03_ordenpago_cuerpo b, cugd02_dependencias c  where ".$cond."


and b.cod_presi              =   a.cod_presi and
    b.cod_entidad            =   a.cod_entidad and
    b.cod_tipo_inst          =   a.cod_tipo_inst and
    b.cod_inst               =   a.cod_inst and
    b.cod_dep                =   a.cod_dep and
    c.cod_tipo_institucion   =   a.cod_tipo_inst and
    c.cod_institucion        =   a.cod_inst and
    c.cod_dependencia        =   a.cod_dep and
    c.tipo_dependencia       =   1 and
    b.ano_orden_pago         =   a.ano_orden_pago and
    b.numero_orden_pago      =   a.numero_orden_pago ORDER BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_dep, b.ano_orden_pago, b.cuenta_bancaria,  b.numero_orden_pago;");


if($datos_cuerpo_multa_2!=""){
		$this->set('datos_cuerpo_multa',$datos_cuerpo_multa_2);
		$this->set('vacio','no');
	}else{
		$this->set('vacio','si');
		if($this->data['retencion_multa_acumulado']['status']==1){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS EN LA RETENCIÓN DE MULTA PENDIENTE');
		}elseif($this->data['retencion_multa_acumulado']['status']==3){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS POR RESTAURAR EN LA RETENCIÓN DE MULTA');
		}
	}
	$this->set('titulo_a',$this->Session->read('dependencia'));
	$this->set('titulo_inst','GOBERNACIÓN DEL ESTADO FALCÓN');
}//reporte_retencion_multa_acumulado_pend









function reporte_retencion_responsabilidad_acumulado_pend_por_periodo($desde=null, $hasta=null){
	$this->layout = "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano = $this->ano_ejecucion();
	//$cond=$this->SQLCA();
	  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $datos_cuerpo_iva_2       =       "";
if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'si');
}else{
	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and a.cod_dep=".$cod_dep." and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'no');
}//fin else

	  $datos_cuerpo_responsabilidad_2= $this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.clase_orden,
  a.numero_orden_pago,
  a.monto,
  a.fecha_proceso_registro,
  a.status,
  a.ano_movimiento,
  a.cod_entidad_bancaria,
  a.cod_sucursal,
  b.cuenta_bancaria,
  a.numero_cheque,
  a.fecha_proceso_anulacion,
  b.beneficiario,
  b.monto_descontar_impuesto,
  b.porcentaje_retencion_iva,
  b.porcentaje_timbre_fiscal,
  b.monto_retencion_fielcumplimiento,
  b.monto_retencion_laboral,
  b.monto_iva,
  b.monto_coniva,
  b.monto_sustraendo,
  b.porcentaje_islr,
  b.porcentaje_impuesto_municipal,
  c.denominacion

FROM cstd07_retenciones_cuerpo_responsabilidad a ,  cepd03_ordenpago_cuerpo b, cugd02_dependencias c  where ".$cond."


and b.cod_presi              =   a.cod_presi and
    b.cod_entidad            =   a.cod_entidad and
    b.cod_tipo_inst          =   a.cod_tipo_inst and
    b.cod_inst               =   a.cod_inst and
    b.cod_dep                =   a.cod_dep and
    c.cod_tipo_institucion   =   a.cod_tipo_inst and
    c.cod_institucion        =   a.cod_inst and
    c.cod_dependencia        =   a.cod_dep and
    c.tipo_dependencia       =   1 and
    b.ano_orden_pago         =   a.ano_orden_pago and
    b.numero_orden_pago      =   a.numero_orden_pago ORDER BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_dep, b.ano_orden_pago, b.cuenta_bancaria,  b.numero_orden_pago;");


if($datos_cuerpo_responsabilidad_2!=""){
		$this->set('datos_cuerpo_responsabilidad',$datos_cuerpo_responsabilidad_2);
		$this->set('vacio','no');
	}else{
		$this->set('vacio','si');
		if($this->data['retencion_responsabilidad_acumulado']['status']==1){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS EN LA RETENCIÓN DE RESPONSABILIDAD PENDIENTE');
		}elseif($this->data['retencion_responsabilidad_acumulado']['status']==3){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS POR RESTAURAR EN LA RETENCIÓN DE RESPONSABILIDAD');
		}
	}
	$this->set('titulo_a',$this->Session->read('dependencia'));
	$this->set('titulo_inst','GOBERNACIÓN DEL ESTADO FALCÓN');
}//reporte_retencion_responsabilidad_acumulado_pend




























function reporte_retencion_iva_acumulado_pend_por_periodo($desde=null, $hasta=null){
	$this->layout = "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano = $this->ano_ejecucion();
	//$cond=$this->SQLCA();


  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $datos_cuerpo_iva_2       =       "";
if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'si');
}else{
	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and a.cod_dep=".$cod_dep." and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'no');
}//fin else

	  $datos_cuerpo_iva_2= $this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.clase_orden,
  a.numero_orden_pago,
  a.monto,
  a.fecha_proceso_registro,
  a.status,
  a.ano_movimiento,
  a.cod_entidad_bancaria,
  a.cod_sucursal,
  b.cuenta_bancaria,
  a.numero_cheque,
  a.fecha_proceso_anulacion,
  b.beneficiario,
  b.monto_descontar_impuesto,
  b.porcentaje_retencion_iva,
  b.porcentaje_timbre_fiscal,
  b.monto_retencion_fielcumplimiento,
  b.monto_retencion_laboral,
  b.monto_iva,
  b.monto_coniva,
  b.porcentaje_islr,
  b.porcentaje_impuesto_municipal,
  c.denominacion

FROM cstd07_retenciones_cuerpo_iva a ,  cepd03_ordenpago_cuerpo b, cugd02_dependencias c  where ".$cond."


and b.cod_presi              =   a.cod_presi and
    b.cod_entidad            =   a.cod_entidad and
    b.cod_tipo_inst          =   a.cod_tipo_inst and
    b.cod_inst               =   a.cod_inst and
    b.cod_dep                =   a.cod_dep and
    c.cod_tipo_institucion   =   a.cod_tipo_inst and
    c.cod_institucion        =   a.cod_inst and
    c.cod_dependencia        =   a.cod_dep and
    c.tipo_dependencia       =   1 and
    b.ano_orden_pago         =   a.ano_orden_pago and
    b.numero_orden_pago      =   a.numero_orden_pago

  ORDER BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_dep, b.ano_orden_pago, b.cuenta_bancaria, b.numero_orden_pago;");



	if($datos_cuerpo_iva_2!=""){
		$this->set('datos_cuerpo_iva',$datos_cuerpo_iva_2);
		$this->set('vacio','no');
	}else{
		$this->set('vacio','si');
		if($this->data['retencion_iva_acumulado']['status']==1){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS EN LA RETENCIÓN DEL I.V.A. PENDIENTE');
		}elseif($this->data['retencion_iva_acumulado']['status']==3){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS POR RESTAURAR EN LA RETENCIÓN DEL I.V.A.');
		}
	}
	$this->set('titulo_a',$this->Session->read('dependencia'));
	$this->set('titulo_inst','GOBERNACIÓN DEL ESTADO FALCÓN');
}//reporte_retencion_iva_acumulado_pend









function reporte_retencion_timbrefiscal_acumulado_pend_por_periodo($desde=null, $hasta=null){
	$this->layout = "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano = $this->ano_ejecucion();
	//$cond=$this->SQLCA();

	  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $datos_cuerpo_iva_2       =       "";
if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'si');
}else{
	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and a.cod_dep=".$cod_dep." and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'no');
}//fin else

	  $datos_cuerpo_timbre_2= $this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.clase_orden,
  a.numero_orden_pago,
  a.monto,
  a.fecha_proceso_registro,
  a.status,
  a.ano_movimiento,
  a.cod_entidad_bancaria,
  a.cod_sucursal,
  b.cuenta_bancaria,
  a.numero_cheque,
  a.fecha_proceso_anulacion,
  b.beneficiario,
  b.monto_descontar_impuesto,
  b.porcentaje_retencion_iva,
  b.porcentaje_timbre_fiscal,
  b.monto_retencion_fielcumplimiento,
  b.monto_retencion_laboral,
  b.monto_iva,
  b.monto_coniva,
  b.porcentaje_islr,
  b.porcentaje_impuesto_municipal,
  c.denominacion

FROM cstd07_retenciones_cuerpo_timbre a ,  cepd03_ordenpago_cuerpo b, cugd02_dependencias c  where ".$cond."


and b.cod_presi              =   a.cod_presi and
    b.cod_entidad            =   a.cod_entidad and
    b.cod_tipo_inst          =   a.cod_tipo_inst and
    b.cod_inst               =   a.cod_inst and
    b.cod_dep                =   a.cod_dep and
    c.cod_tipo_institucion   =   a.cod_tipo_inst and
    c.cod_institucion        =   a.cod_inst and
    c.cod_dependencia        =   a.cod_dep and
    c.tipo_dependencia       =   1 and
    b.ano_orden_pago         =   a.ano_orden_pago and
    b.numero_orden_pago      =   a.numero_orden_pago ORDER BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_dep, b.ano_orden_pago, b.cuenta_bancaria, b.numero_orden_pago;");




if($datos_cuerpo_timbre_2!=""){
		$this->set('datos_cuerpo_timbre',$datos_cuerpo_timbre_2);
		$this->set('vacio','no');
	}else{
		$this->set('vacio','si');
		if($this->data['retencion_timbrefiscal_acumulado']['status']==1){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS EN LA RETENCIÓN DEL TIMBRE FISCAL PENDIENTE');
		}elseif($this->data['retencion_timbrefiscal_acumulado']['status']==3){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS POR RESTAURAR EN LA RETENCIÓN DEL TIMBRE FISCAL');
		}
	}
	$this->set('titulo_a',$this->Session->read('dependencia'));
	$this->set('titulo_inst','GOBERNACIÓN DEL ESTADO FALCÓN');
}//reporte_retencion_timbrefiscal_acumulado_pend











function reporte_retencion_impmunicipal_acumulado_pend_por_periodo($desde=null, $hasta=null){
	$this->layout = "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano = $this->ano_ejecucion();
	//$cond=$this->SQLCA();

	  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $datos_cuerpo_iva_2       =       "";
if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano." and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'si');
}else{
	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and (a.status=1 or a.status=3) and a.ano_orden_pago=".$ano."  and a.cod_dep=".$cod_dep." and (a.fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'no');
}//fin else

	  $datos_cuerpo_impmunicipal_2= $this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.clase_orden,
  a.numero_orden_pago,
  a.monto,
  a.fecha_proceso_registro,
  a.status,
  a.ano_movimiento,
  a.cod_entidad_bancaria,
  a.cod_sucursal,
  b.cuenta_bancaria,
  a.numero_cheque,
  a.fecha_proceso_anulacion,
  b.beneficiario,
  b.monto_descontar_impuesto,
  b.porcentaje_retencion_iva,
  b.porcentaje_timbre_fiscal,
  b.monto_retencion_fielcumplimiento,
  b.monto_retencion_laboral,
  b.monto_iva,
  b.monto_coniva,
  b.porcentaje_islr,
  b.porcentaje_impuesto_municipal,
  c.denominacion

FROM cstd07_retenciones_cuerpo_municipal a ,  cepd03_ordenpago_cuerpo b, cugd02_dependencias c  where ".$cond."


and b.cod_presi              =   a.cod_presi and
    b.cod_entidad            =   a.cod_entidad and
    b.cod_tipo_inst          =   a.cod_tipo_inst and
    b.cod_inst               =   a.cod_inst and
    b.cod_dep                =   a.cod_dep and
    c.cod_tipo_institucion   =   a.cod_tipo_inst and
    c.cod_institucion        =   a.cod_inst and
    c.cod_dependencia        =   a.cod_dep and
    c.tipo_dependencia       =   1 and
    b.ano_orden_pago         =   a.ano_orden_pago and
    b.numero_orden_pago      =   a.numero_orden_pago ORDER BY b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_dep, b.ano_orden_pago, b.cuenta_bancaria, b.numero_orden_pago;");



if($datos_cuerpo_impmunicipal_2!=""){
		$this->set('datos_cuerpo_impmunicipal',$datos_cuerpo_impmunicipal_2);
		$this->set('vacio','no');
	}else{
		$this->set('vacio','si');
		if($this->data['retencion_impmunicipal_acumulado']['status']==1){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS EN LA RETENCIÓN DEL IMPUESTO MUNICIPAL PENDIENTE');
		}elseif($this->data['retencion_impmunicipal_acumulado']['status']==3){
		$this->set('mensaje','NO SE ENCONTRARÓN DATOS POR RESTAURAR EN LA RETENCIÓN DEL IMPUESTO MUNICIPAL');
		}
	}
	$this->set('titulo_a',$this->Session->read('dependencia'));
	$this->set('titulo_inst','GOBERNACIÓN DEL ESTADO FALCÓN');
}//reporte_retencion_impmunicipal_acumulado_pend








function reporte_analitico_pago($var=null){
$opcion = "si";
$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));

if($var==null){

	$this->layout = "ajax";



}else{

	$this->layout = "pdf";
	$opcion = "no";

 	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$ano = $this->ano_ejecucion();


	$desde = $this->Cfecha($this->data['reporte3']['desde_periodo'], 'A-M-D');
	$hasta = $this->Cfecha($this->data['reporte3']['hasta_periodo'], 'A-M-D');

	$this->set('desde', $desde);
	$this->set('hasta', $hasta);



  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
  $lista = "";

if(isset($this->data['reporte3']['consolidacion'])){if($this->data['reporte3']['consolidacion']==2){$Modulo="2";}}

if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and d.condicion_actividad=1 and a.condicion_actividad=1  and (a.fecha_cheque BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'si');
}else{
	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and d.condicion_actividad=1 and a.condicion_actividad=1  and a.cod_dep=".$cod_dep." and (a.fecha_cheque BETWEEN '$desde' AND '$hasta')";
    $this->set('global', 'no');
}//fin else

$resultado= $this->cstd03_cheque_cuerpo->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
e.denominacion,
a.cod_entidad_bancaria,
a.cod_sucursal,
a.cuenta_bancaria,
a.numero_cheque,
a.fecha_cheque,
a.beneficiario,
a.clase_beneficiario,
a.monto,
b.ano_orden_pago,
b.numero_orden_pago,
d.fecha_orden_pago,
d.monto_neto_cobrar,
d.monto_coniva,
d.monto_iva,
d.porcentaje_iva,
d.monto_siniva,
d.monto_retencion_laboral,
d.porcentaje_laboral,
d.monto_retencion_fielcumplimiento,
d.porcentaje_fielcumplimiento,
d.monto_descontar_impuesto,
d.amortizacion_anticipo,
d.porcentaje_amortizacion,
d.monto_orden_pago,
d.monto_retencion_iva,
d.porcentaje_retencion_iva,
d.monto_islr,
d.porcentaje_islr,
d.monto_sustraendo,
d.monto_timbre_fiscal,
d.porcentaje_timbre_fiscal,
d.monto_impuesto_municipal,
d.porcentaje_impuesto_municipal,
d.tipo_orden,
c.cod_sector,
c.cod_programa,
c.cod_sub_prog,
c.cod_proyecto,
c.cod_activ_obra,
c.cod_partida,
c.cod_generica,
c.cod_especifica,
c.cod_sub_espec,
c.cod_auxiliar,
c.monto as monto_partida

FROM cstd03_cheque_cuerpo a ,  cstd03_cheque_ordenes b,  cstd03_cheque_partidas c, cepd03_ordenpago_cuerpo d, cugd02_dependencias e  where ".$cond."


and b.cod_presi              =   a.cod_presi and
    b.cod_entidad            =   a.cod_entidad and
    b.cod_tipo_inst          =   a.cod_tipo_inst and
    b.cod_inst               =   a.cod_inst and
    b.cod_dep                =   a.cod_dep and
    e.cod_tipo_institucion   =   a.cod_tipo_inst and
    e.cod_institucion        =   a.cod_inst and
    e.cod_dependencia        =   a.cod_dep and
    b.ano_movimiento         =   a.ano_movimiento and
    b.cod_entidad_bancaria   =   a.cod_entidad_bancaria and
    b.cod_sucursal           =   a.cod_sucursal and
    b.cuenta_bancaria        =   a.cuenta_bancaria and
    b.numero_cheque          =   a.numero_cheque and
    c.cod_presi              =   a.cod_presi and
    c.cod_entidad            =   a.cod_entidad and
    c.cod_tipo_inst          =   a.cod_tipo_inst and
    c.cod_inst               =   a.cod_inst and
    c.cod_dep                =   a.cod_dep and
    c.ano_orden_pago         =   b.ano_orden_pago and
    c.numero_orden_pago      =   b.numero_orden_pago and
    c.ano_movimiento         =   a.ano_movimiento and
    c.cod_entidad_bancaria   =   a.cod_entidad_bancaria and
    c.cod_sucursal           =   a.cod_sucursal and
    c.cuenta_bancaria        =   a.cuenta_bancaria and
    c.numero_cheque          =   a.numero_cheque and
    d.cod_presi              =   a.cod_presi and
    d.cod_entidad            =   a.cod_entidad and
    d.cod_tipo_inst          =   a.cod_tipo_inst and
    d.cod_inst               =   a.cod_inst and
    d.cod_dep                =   a.cod_dep and
    d.ano_orden_pago         =   b.ano_orden_pago and
    d.numero_orden_pago      =   b.numero_orden_pago

ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_dep, a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.numero_cheque, b.numero_orden_pago, c.cod_sector, c.cod_programa, c.cod_sub_prog, c.cod_proyecto, c.cod_activ_obra, c.cod_partida, c.cod_generica, c.cod_especifica, c.cod_sub_espec, c.cod_auxiliar ASC;");



$this->set('resultado', $resultado);
$this->set('cugd02_dependencia', $this->cugd02_dependencia->findAll($cond3));

}//fin else



//echo "<pre>";
 //print_r($resultado);
//echo "</pre>";


$this->set('opcion',    $opcion);

}//fin function
















function reporte_analitico_por_cuenta($var=null){


$opcion = "si";
$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
$this->set('tipo', $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));


if($var==null){

	$this->layout = "ajax";



}else{

	$this->layout = "pdf";
	$opcion = "no";

 	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$ano = $this->ano_ejecucion();


	$desde = $this->Cfecha($this->data['reporte3']['desde_periodo'], 'A-M-D');
	$hasta = $this->Cfecha($this->data['reporte3']['hasta_periodo'], 'A-M-D');
	$cod_entidad_bancaria                   =         $this->data['cepp03_pagos_por_cancelar']['entidad'];
    $cod_sucursal                           =         $this->data['cepp03_pagos_por_cancelar']['sucursal'];
    $cuenta_bancaria                        =         $this->data['cepp03_pagos_por_cancelar']['cuenta'];

	$this->set('desde', $desde);
	$this->set('hasta', $hasta);



  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
  $lista = "";





if(isset($this->data['reporte3']['consolidacion'])){if($this->data['reporte3']['consolidacion']==2){$Modulo="2";}}else{$Modulo="2";}

if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and d.condicion_actividad=1 and a.condicion_actividad=1  and (a.fecha_cheque BETWEEN '$desde' AND '$hasta') and  a.cod_entidad_bancaria= ".$cod_entidad_bancaria." and a.cod_sucursal= ".$cod_sucursal." and a.cuenta_bancaria = '".$cuenta_bancaria."' ";
    $this->set('global', 'si');
}else{
	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and d.condicion_actividad=1 and a.condicion_actividad=1  and a.cod_dep=".$cod_dep." and (a.fecha_cheque BETWEEN '$desde' AND '$hasta') and  a.cod_entidad_bancaria= ".$cod_entidad_bancaria." and a.cod_sucursal= ".$cod_sucursal." and a.cuenta_bancaria = '".$cuenta_bancaria."' ";
    $this->set('global', 'no');
}//fin else

$resultado= $this->cstd03_cheque_cuerpo->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
e.denominacion,
a.cod_entidad_bancaria,
a.cod_sucursal,
a.cuenta_bancaria,
a.numero_cheque,
a.fecha_cheque,
a.beneficiario,
a.clase_beneficiario,
a.monto,
b.ano_orden_pago,
b.numero_orden_pago,
d.fecha_orden_pago,
d.monto_neto_cobrar,
d.monto_coniva,
d.monto_iva,
d.porcentaje_iva,
d.monto_siniva,
d.monto_retencion_laboral,
d.porcentaje_laboral,
d.monto_retencion_fielcumplimiento,
d.porcentaje_fielcumplimiento,
d.monto_descontar_impuesto,
d.amortizacion_anticipo,
d.porcentaje_amortizacion,
d.monto_orden_pago,
d.monto_retencion_iva,
d.porcentaje_retencion_iva,
d.monto_islr,
d.porcentaje_islr,
d.monto_sustraendo,
d.monto_timbre_fiscal,
d.porcentaje_timbre_fiscal,
d.monto_impuesto_municipal,
d.porcentaje_impuesto_municipal,
d.tipo_orden,
c.cod_sector,
c.cod_programa,
c.cod_sub_prog,
c.cod_proyecto,
c.cod_activ_obra,
c.cod_partida,
c.cod_generica,
c.cod_especifica,
c.cod_sub_espec,
c.cod_auxiliar,
c.monto as monto_partida

FROM cstd03_cheque_cuerpo a ,  cstd03_cheque_ordenes b,  cstd03_cheque_partidas c, cepd03_ordenpago_cuerpo d, cugd02_dependencias e  where ".$cond."


and b.cod_presi              =   a.cod_presi and
    b.cod_entidad            =   a.cod_entidad and
    b.cod_tipo_inst          =   a.cod_tipo_inst and
    b.cod_inst               =   a.cod_inst and
    b.cod_dep                =   a.cod_dep and
    e.cod_tipo_institucion   =   a.cod_tipo_inst and
    e.cod_institucion        =   a.cod_inst and
    e.cod_dependencia        =   a.cod_dep and
    b.ano_movimiento         =   a.ano_movimiento and
    b.cod_entidad_bancaria   =   a.cod_entidad_bancaria and
    b.cod_sucursal           =   a.cod_sucursal and
    b.cuenta_bancaria        =   a.cuenta_bancaria and
    b.numero_cheque          =   a.numero_cheque and
    c.cod_presi              =   a.cod_presi and
    c.cod_entidad            =   a.cod_entidad and
    c.cod_tipo_inst          =   a.cod_tipo_inst and
    c.cod_inst               =   a.cod_inst and
    c.cod_dep                =   a.cod_dep and
    c.ano_orden_pago         =   b.ano_orden_pago and
    c.numero_orden_pago      =   b.numero_orden_pago and
    c.ano_movimiento         =   a.ano_movimiento and
    c.cod_entidad_bancaria   =   a.cod_entidad_bancaria and
    c.cod_sucursal           =   a.cod_sucursal and
    c.cuenta_bancaria        =   a.cuenta_bancaria and
    c.numero_cheque          =   a.numero_cheque and
    d.cod_presi              =   a.cod_presi and
    d.cod_entidad            =   a.cod_entidad and
    d.cod_tipo_inst          =   a.cod_tipo_inst and
    d.cod_inst               =   a.cod_inst and
    d.cod_dep                =   a.cod_dep and
    d.ano_orden_pago         =   b.ano_orden_pago and
    d.numero_orden_pago      =   b.numero_orden_pago

ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_dep, a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.numero_cheque, b.numero_orden_pago, c.cod_sector, c.cod_programa, c.cod_sub_prog, c.cod_proyecto, c.cod_activ_obra, c.cod_partida, c.cod_generica, c.cod_especifica, c.cod_sub_espec, c.cod_auxiliar ASC;");



$this->set('resultado', $resultado);
$this->set('cugd02_dependencia', $this->cugd02_dependencia->findAll($cond3));

}//fin else




//echo "<pre>";
 //print_r($resultado);
//echo "</pre>";


$this->set('opcion',    $opcion);

}//fin function
















function reporte_analitico_orden_pago($var=null){


$opcion = "si";
$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
$this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
$opcion1_aux = "";
$opcion2_aux = "";

if($var==null){

	$this->layout = "ajax";

}else{

	$this->layout = "pdf";
	$opcion = "no";

 	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$ano = $this->ano_ejecucion();


	$desde = $this->Cfecha($this->data['reporte3']['desde_periodo'], 'A-M-D');
	$hasta = $this->Cfecha($this->data['reporte3']['hasta_periodo'], 'A-M-D');

	$opcion1 = $this->data['reporte3']['opcion1'];
	$opcion2 = $this->data['reporte3']['opcion2'];

	$this->set('desde', $desde);
	$this->set('hasta', $hasta);





  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
  $lista = "";





if(isset($this->data['reporte3']['consolidacion'])){if($this->data['reporte3']['consolidacion']==2){$Modulo="2";}}else{$Modulo="2";}

if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = $this->SQLCA_consolidado_opcion($this->data['reporte3']['consolidacion'], "a")." and a.condicion_actividad=1  and (a.fecha_orden_pago BETWEEN '$desde' AND '$hasta') ";
    $this->set('global', 'si');
}else{
	$cond = $this->SQLCA_consolidado_opcion($this->data['reporte3']['consolidacion'], "a")."  and a.condicion_actividad=1 and (a.fecha_orden_pago BETWEEN '$desde' AND '$hasta') ";
    $this->set('global', 'no');
}//fin else

      if($opcion1==2){  $cond .= " and a.cod_tipo_pago  ='2' ";  $opcion1_aux = "CAPITAL";
}else if($opcion1==3){  $cond .= " and a.cod_tipo_pago !='2' ";  $opcion1_aux = "CORRIENTE"; }//fin else


$cond .=" and (
d.cod_presi        =    b.cod_presi and
d.cod_entidad      =    b.cod_entidad and
d.cod_tipo_inst    =    b.cod_tipo_inst and
d.cod_inst         =    b.cod_inst and
d.cod_dep          =    b.cod_dep and
d.ano              =    b.ano and
d.cod_sector       =    b.cod_sector and
d.cod_programa     =    b.cod_programa and
d.cod_sub_prog     =    b.cod_sub_prog and
d.cod_proyecto     =    b.cod_proyecto and
d.cod_activ_obra   =    b.cod_activ_obra and
d.cod_partida      =    b.cod_partida and
d.cod_generica     =    b.cod_generica and
d.cod_especifica   =    b.cod_especifica and
d.cod_sub_espec    =    b.cod_sub_espec and
d.cod_auxiliar     =    b.cod_auxiliar ";

if($opcion1!=1 && $opcion2!=1){$opcion2_aux = "/";}

      if($opcion2==1){  $cond .=" ) ";
}else if($opcion2==2){  $cond .=" and d.tipo_presupuesto = 1 )"; $opcion2_aux .= "Ordinario";
}else if($opcion2==3){  $cond .=" and d.tipo_presupuesto = 2 )"; $opcion2_aux .= "Coordinado";
}else if($opcion2==4){  $cond .=" and d.tipo_presupuesto = 4 )"; $opcion2_aux .= "Fides";
}else if($opcion2==5){  $cond .=" and d.tipo_presupuesto = 3 )"; $opcion2_aux .= "Laee";
}else if($opcion2==6){  $cond .=" and d.tipo_presupuesto = 5 )"; $opcion2_aux .= "Ingreso Extraordinario"; }//fin else







$resultado= $this->cstd03_cheque_cuerpo->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
c.denominacion,
a.ano_orden_pago,
a.numero_orden_pago,
a.fecha_orden_pago,
a.monto_neto_cobrar,
a.monto_coniva,
a.monto_iva,
a.beneficiario,
a.porcentaje_iva,
a.monto_siniva,
a.monto_retencion_laboral,
a.porcentaje_laboral,
a.monto_retencion_fielcumplimiento,
a.porcentaje_fielcumplimiento,
a.monto_descontar_impuesto,
a.amortizacion_anticipo,
a.porcentaje_amortizacion,
a.monto_orden_pago,
a.monto_total,
a.monto_retencion_iva,
a.porcentaje_retencion_iva,
a.monto_islr,
a.porcentaje_islr,
a.monto_sustraendo,
a.monto_timbre_fiscal,
a.porcentaje_timbre_fiscal,
a.monto_impuesto_municipal,
a.porcentaje_impuesto_municipal,
a.tipo_orden,
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
b.monto as monto_partida

FROM cepd03_ordenpago_cuerpo a, cepd03_ordenpago_partidas b, cugd02_dependencias c, cfpd05 d  where ".$cond."


and b.cod_presi              =   a.cod_presi and
    b.cod_entidad            =   a.cod_entidad and
    b.cod_tipo_inst          =   a.cod_tipo_inst and
    b.cod_inst               =   a.cod_inst and
    b.cod_dep                =   a.cod_dep and
    c.cod_tipo_institucion   =   a.cod_tipo_inst and
    c.cod_institucion        =   a.cod_inst and
    c.cod_dependencia        =   a.cod_dep and
    b.ano_orden_pago         =   a.ano_orden_pago and
    b.numero_orden_pago      =   a.numero_orden_pago

ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_dep, a.numero_orden_pago, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_activ_obra, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar ASC;");


$this->set('resultado', $resultado);
$this->set('cugd02_dependencia', $this->cugd02_dependencia->findAll($cond3));

}//fin else




//echo "<pre>";
 //print_r($resultado);
//echo "</pre>";


$this->set('opcion',    $opcion);
$this->set('opcion1_aux', $opcion1_aux);
$this->set('opcion2_aux', $opcion2_aux);

}//fin function









function relacion_obra($var=null){

$this->verifica_entrada('93');

$opcion = "si";
$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
$this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
$opcion1_aux = "";
$opcion2_aux = "";

if($var==null){

	$this->layout = "ajax";
	$ano = $this->ano_ejecucion();
	$this->set('year', $ano);

}else{

	$this->layout = "pdf";
	$opcion = "no";

 	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$ano = $this->ano_ejecucion();



	$opcion2 = $this->data['reporte3']['opcion2'];
	$year    = $this->data['reporte3']['year'];




  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
  $lista = "";





if(isset($this->data['reporte3']['consolidacion'])){if($this->data['reporte3']['consolidacion']==2){$Modulo="2";}}


 if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = $this->SQLCA_consolidado_opcion($this->data['reporte3']['consolidacion'], "a");
    $this->set('global', 'si');
}else{
	 $cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
    $this->set('global', 'no');
}//fin else


      if($opcion2==0){  $cond .="";
}else if($opcion2==1){  $cond .=" and a.tipo_recurso = 1"; $opcion2_aux .= "Ordinario";
}else if($opcion2==2){  $cond .=" and a.tipo_recurso = 2"; $opcion2_aux .= "Coordinado";
}else if($opcion2==3){  $cond .=" and a.tipo_recurso = 3"; $opcion2_aux .= "Fci";
}else if($opcion2==4){  $cond .=" and a.tipo_recurso = 4"; $opcion2_aux .= "Mpps";
}else if($opcion2==5){  $cond .=" and a.tipo_recurso = 5"; $opcion2_aux .= "Ingreso Extraordinario";
}else if($opcion2==6){  $cond .=" and a.tipo_recurso = 6"; $opcion2_aux .= "Ingresos Propios";
}else if($opcion2==7){  $cond .=" and a.tipo_recurso = 7"; $opcion2_aux .= "Laee";
}else if($opcion2==8){  $cond .=" and a.tipo_recurso = 8"; $opcion2_aux .= "Fides"; } //fin else



$resultado= $this->cstd03_cheque_cuerpo->execute("SELECT
	  a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano_estimacion,
	  a.cod_obra,
	  a.denominacion,
	  a.tipo_recurso as cfpd07_tipo_recurso,
	  a.clasificacion_recurso as cfpd07_clasificacion_recurso,
	  a.costo_total,
	  a.estimado_presu,
	  a.monto_contratado,
      a.aumento_obras     as  aumento_obras,
      a.disminucion_obras as  disminucion_obras,
	  a.ano_plan,
	  a.cod_dep_original,
	  b.denominacion as denominacion_dep,
	  (SELECT SUM(monto_anticipo)     FROM cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and x.condicion_actividad=1) as monto_anticipo,
	  (SELECT SUM(monto_cancelado)    FROM cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and x.condicion_actividad=1) as monto_cancelado,
	  (SELECT SUM(monto_amortizacion) FROM cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and x.condicion_actividad=1) as monto_amortizacion,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1 and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio   LIMIT 1 OFFSET 0 * 1) as denominacion_municipio_1,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1  and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio  LIMIT 1 OFFSET 1 * 1) as denominacion_municipio_2,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1  and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio  LIMIT 1 OFFSET 2 * 1) as denominacion_municipio_3,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1  and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio  LIMIT 1 OFFSET 3 * 1) as denominacion_municipio_4,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1  and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio  LIMIT 1 OFFSET 4 * 1) as denominacion_municipio_5,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1  and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio  LIMIT 1 OFFSET 5 * 1) as denominacion_municipio_6,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1  and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio  LIMIT 1 OFFSET 6 * 1) as denominacion_municipio_7,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1  and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio  LIMIT 1 OFFSET 7 * 1) as denominacion_municipio_8,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1  and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio  LIMIT 1 OFFSET 8 * 1) as denominacion_municipio_9,
      (SELECT y.denominacion          FROM cobd01_contratoobras_cuerpo x,  cugd01_municipios y WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep  and x.condicion_actividad=1  and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra and y.cod_republica=x.cod_presi and y.cod_estado=x.cod_estado and y.cod_municipio=x.cod_municipio  LIMIT 1 OFFSET 9 * 1) as denominacion_municipio_10


FROM cfpd07_obras_cuerpo a, cugd02_dependencias b where ".$cond." and

      a.ano_estimacion         =   ".$year." and
	  b.cod_tipo_institucion   =   a.cod_tipo_inst and
      b.cod_institucion        =   a.cod_inst and
      b.cod_dependencia        =   a.cod_dep_original


ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_dep_original, a.tipo_recurso, a.cod_obra ASC;");


$this->set('opcion2', $opcion2);
$this->set('resultado', $resultado);
$this->set('cugd02_dependencia', $this->cugd02_dependencia->findAll($cond3));

}//fin else




//echo "<pre>";
 //print_r($resultado);
//echo "</pre>";


$this->set('opcion',    $opcion);
$this->set('opcion1_aux', $opcion1_aux);
$this->set('opcion2_aux', $opcion2_aux);

}//fin function









function relacion_proyecto($var=null){

$this->verifica_entrada('92');

$opcion = "si";
$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
$this->concatena($this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
$opcion1_aux = "";
$opcion2_aux = "";

if($var==null){

	$this->layout = "ajax";
	$ano = $this->ano_ejecucion();
	$this->set('year', $ano);

}else{

	$this->layout = "pdf";
	$opcion = "no";

 	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$ano = $this->ano_ejecucion();



	$opcion2 = $this->data['reporte3']['opcion2'];
	$year    = $this->data['reporte3']['year'];




  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
  $lista = "";





if(isset($this->data['reporte3']['consolidacion'])){if($this->data['reporte3']['consolidacion']==2){$Modulo="2";}}


if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
 	$cond = $this->SQLCA_consolidado_opcion($this->data['reporte3']['consolidacion'], "a");
    $this->set('global', 'si');
}else{
	 $cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
    $this->set('global', 'no');
}//fin else


      if($opcion2==0){  $cond .="";
}else if($opcion2==1){  $cond .=" and a.tipo_recurso = 1"; $opcion2_aux .= "Ordinario";
}else if($opcion2==2){  $cond .=" and a.tipo_recurso = 2"; $opcion2_aux .= "Coordinado";
}else if($opcion2==3){  $cond .=" and a.tipo_recurso = 3"; $opcion2_aux .= "Fci";
}else if($opcion2==4){  $cond .=" and a.tipo_recurso = 4"; $opcion2_aux .= "Mpps";
}else if($opcion2==5){  $cond .=" and a.tipo_recurso = 5"; $opcion2_aux .= "Ingreso Extraordinario";
}else if($opcion2==6){  $cond .=" and a.tipo_recurso = 6"; $opcion2_aux .= "Ingresos Propios";
}else if($opcion2==7){  $cond .=" and a.tipo_recurso = 7"; $opcion2_aux .= "Laee";
}else if($opcion2==8){  $cond .=" and a.tipo_recurso = 8"; $opcion2_aux .= "Fides"; } //fin else



$resultado= $this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_estimacion,
  a.cod_obra,
  a.denominacion,
  a.tipo_recurso as cfpd07_tipo_recurso,
  a.clasificacion_recurso as cfpd07_clasificacion_recurso,
  a.estimado_presu,
  a.monto_contratado,
  a.ano_plan,
  a.cod_dep_original,
  a.aumento_obras     as  aumento_obras,
  a.disminucion_obras as  disminucion_obras,
  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep_original ) as denominacion_dep


FROM cfpd07_obras_cuerpo a  where ".$cond." and

    a.ano_estimacion         =   ".$year."



ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_dep_original, a.tipo_recurso, a.cod_obra ASC;");


$this->set('opcion2', $opcion2);
$this->set('resultado', $resultado);
$this->set('cugd02_dependencia', $this->cugd02_dependencia->findAll($cond3));

}//fin else




//echo "<pre>";
 //print_r($resultado);
//echo "</pre>";


$this->set('opcion',    $opcion);
$this->set('opcion1_aux', $opcion1_aux);
$this->set('opcion2_aux', $opcion2_aux);

}//fin function









function ano_recurso_asignacion($var1=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $this->layout="ajax";
    $this->set('year', $var1);
    $this->Session->write('ano_recurso_asignacion', $var1);
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));



}//fin function










function relacion_obra_segun_asignacion($opcion=null){


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

        $this->set('opcion', $opcion);
        $this->set('year', $ano);

	     if($opcion=='si'){
		   $this->layout="ajax";
		   $this->Session->write('ano_recurso_asignacion',  $this->ano_ejecucion());


	}else{

		    $this->layout = "pdf";
		    $year = $this->Session->read('ano_recurso_asignacion');
		    $year                      =   $this->data["graficos1"]["ano"];
			$tipo_recurso              =   $this->data["graficos1"]["tipo_recurso"];
            $this->set('year', $year);

            if(isset($this->data['reporte3']['consolidacion'])){if($this->data['reporte3']['consolidacion']==2){$Modulo="2";}}

             			if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
						 	$cod_dep_sql = "";
						    $cod_dep_sql2 = "";
						    $this->set('global', 'si');
						}else{
							$cod_dep_sql  = " a.cod_dep_original=".$this->cod_dep_consolidado()." and ";
						    $cod_dep_sql2 = " , a.cod_dep";
						    $this->set('global', 'no');
						}//fin else

						if($tipo_recurso!=6){$cod_dep_sql  .= "a.tipo_recurso=".$tipo_recurso." and";}


                   	  $datos = $this->cfpd07_plan_inversion->execute("SELECT
								  a.cod_presi,
								  a.cod_entidad,
								  a.cod_tipo_inst,
								  a.cod_inst,
								  a.cod_dep,
								  a.ano_estimacion,
								  a.cod_obra,
								  a.status,
								  a.denominacion,
								  a.cod_dep_original,
								  d.denominacion as denominacion_dep,
								  a.tipo_recurso as cfpd07_tipo_recurso,
								  (SELECT SUM(estimado_presu)     FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.cod_obra = a.cod_obra and a.status=1) as asignacion_inicial,
								  (SELECT SUM(estimado_presu)     FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.cod_obra = a.cod_obra and a.status=2) as credito_adicional,
                                  (SELECT SUM(aumento_obras)      FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.cod_obra = a.cod_obra) as aumento_obras,
                                  (SELECT SUM(disminucion_obras)  FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.cod_obra = a.cod_obra) as disminucion_obras


								FROM cfpd07_obras_cuerpo  a ,    cugd02_dependencias d  where


								      a.cod_presi                =   ".$cod_presi." and
								      a.cod_entidad              =   ".$cod_entidad." and
								      a.cod_tipo_inst            =   ".$cod_tipo_inst." and
								      a.cod_inst                 =   ".$cod_inst." and ".$cod_dep_sql."
									  d.cod_tipo_institucion     =    a.cod_tipo_inst and
								      d.cod_institucion          =    a.cod_inst and
								      a.ano_estimacion           =    ".$year." and
								      d.cod_dependencia          =    a.cod_dep_original


								  group by
										  a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.status,
										  a.ano_estimacion,
										  a.cod_obra,
										  a.denominacion,
										  a.cod_dep_original,
										  d.denominacion,
										  a.tipo_recurso

								ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep_original, a.tipo_recurso, a.cod_obra ASC;");

                 	           $datos2[0][0]['denominacion']="";




         if($tipo_recurso==1){  $opcion1_aux = "Ordinario";
   }else if($tipo_recurso==2){  $opcion1_aux = "Coordinado";
   }else if($tipo_recurso==3){  $opcion1_aux = "Laee";
   }else if($tipo_recurso==4){  $opcion1_aux = "Fides";
   }else if($tipo_recurso==5){  $opcion1_aux = "Ingreso Extraordinario";
   }else if($tipo_recurso==6){  $opcion1_aux = ""; }//fin else

            $this->set('opcion2', $tipo_recurso);
            $this->set('opcion1_aux', $opcion1_aux);
            $this->set('opcion2_aux', $datos2[0][0]['denominacion']);
            $this->set('resultado', $datos);
            $this->set('tipo_recurso', $tipo_recurso);


         }//fin function

$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));




}//fin function












function selet_dep($var=null){

    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$ano = $this->ano_ejecucion();
	$condicion = "arrd05.cod_presi = ".$cod_presi." and arrd05.cod_entidad = ".$cod_entidad." and arrd05.cod_tipo_inst = ".$cod_tipo_inst." and arrd05.cod_inst = ".$cod_inst;
    $nom = $this->arrd05->generateList($condicion, 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
    $this->concatena($nom, 'clasificacion');

    if($var==1){$this->Session->delete('select_dependencia');}else{$this->Session->write('select_dependencia','si');}

   echo "<script>
			     if(document.getElementById('tipo_recurso')){
		                  document.getElementById('tipo_recurso_1').checked = false;
		                  document.getElementById('tipo_recurso_2').checked = false;
		                  document.getElementById('tipo_recurso_3').checked = false;
		                  document.getElementById('tipo_recurso_4').checked = false;
		                  document.getElementById('tipo_recurso_5').checked = false;
		                  document.getElementById('tipo_recurso_6').checked = false;
				 }//fin fi
         </script>";

}//fin function




















function reporte_valuacion_1($var1=null, $var2=null, $var3=null){


    $this->set('opcion',$var1);
    $cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');


if($var1=='si'){
					  $this->layout = "ajax";
					  $pag_num = 0;
					  $opcion = 'si';
					  $ano = $this->ano_ejecucion();
					  $SScoddeporig             =       $this->Session->read('SScoddeporig');
					  $SScoddep                 =       $this->Session->read('SScoddep');
					  $Modulo                   =       $this->Session->read('Modulo');
					  $lista = "";



					if($SScoddep==1 && $Modulo=="0"){

					$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
					$lista = $this->cobd01_contratoobras_valuacion_cuerpo->generateList($condicion.' and ano_contrato_obra='.$ano, ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra');
					$this->set('obras', $lista);
					$this->set('ano',$ano);

					}else{


					$c = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion().' and ano_contrato_obra='.$ano, 'numero_contrato_obra, ano_contrato_obra', null, null);
					$sql_c = ""; //print_r($c);
					foreach($c as $c_aux){
								if($sql_c == ""){
					                          $sql_c  = "     numero_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra']."' ";
								}else{        $sql_c .= " or (numero_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra']."') ";		}//fin else
					}//fin for
					if($sql_c!=""){
						  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' ';
						  $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion." and (".$sql_c.")");
						  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.' ';
						  $b = $this->cfpd07_obras_cuerpo->findAll($condicion);
						foreach($a as $a_aux){
						   foreach($b as $b_aux){
						      if($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion']==$b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] &&  strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra'])==strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])){
						        $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']]=$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
						     }//fin if
						  }//fin foreach
						}//fin foreach
					}//fin if


					}//fin else


					           if($var3!=null){

                                            if($SScoddep==1 && $Modulo=="0"){
                                                $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and condicion_actividad=1  ';
					                        }else{
										        $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and condicion_actividad=1  ';
                                            }//fin else
											$lista2 = $this->cobd01_contratoobras_valuacion_cuerpo->generateList($condicion." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$var2."'   ", ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_valuacion', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_valuacion');
											$this->set('obras', $lista);
											$this->AddCero('obras2', $lista2);
											$this->set('seleccion', $var2);
											$this->set('seleccion2', $var3);
											$this->set('ano',$ano);


									}else{

				                            $this->set('obras', $lista);
											$this->set('obras2', '');
											$this->set('seleccion', '');
											$this->set('seleccion2', '');
											$this->set('ano',$ano);


									}//fin else




}else{

		    $this->layout = "pdf";
		    $SScoddeporig             =       $this->Session->read('SScoddeporig');
			$SScoddep                 =       $this->Session->read('SScoddep');
			$Modulo                   =       $this->Session->read('Modulo');


               if($SScoddep==1 && $Modulo=="0"){
	                $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	            }else{
			        $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
	            }//fin else



            $sql = "";

             $ano_obra                       = $this->data['cobp01_contratoobras_valuacion']['ano_ejecucion'];
             $numero_contrato_obra           = $this->data['cobp01_contratoobras_valuacion']['numero_contrato_obra'];
             $numero_contrato_obra_valuacion = $this->data['cobp01_contratoobras_valuacion']['numero_contrato_obra_valuacion'];

             if($numero_contrato_obra_valuacion=="TODOS"){
                   $sql = " ";

             }else{
                   $sql = " and a.numero_valuacion='".$numero_contrato_obra_valuacion."' ";
             }//fin if



             if(isset($_SESSION['select_dependencia'])){if($_SESSION['select_dependencia']!='si'){$cod_dep=$_SESSION['select_dependencia']; $Modulo="2"; }else{$Modulo="2";}}

			          if($SScoddep==1  && $Modulo=="0"){
						 	$cod_dep_sql = "";
						    $cod_dep_sql2 = "";
						    $this->set('global', 'si');
						}else{
							$cod_dep_sql  = "   a.cod_dep              =  ".$cod_dep."               and";
						    $cod_dep_sql2 = " , a.cod_dep";
						    $this->set('global', 'no');
						}//fin else


                   	  $datos = $this->cfpd07_plan_inversion->execute(" SELECT
                          a.cod_presi as                            cod_presi_valuacion,
						  a.cod_entidad as                          cod_entidad_valuacion,
						  a.cod_tipo_inst as                        cod_tipo_inst_valuacion,
						  a.cod_inst as                             cod_inst_valuacion,
						  a.cod_dep as                              cod_dep_valuacion,
						  a.ano_contrato_obra as                    ano_contrato_obra_valuacion,
						  a.numero_contrato_obra as                 numero_contrato_obra_valuacion,
						  a.numero_valuacion as                     numero_valuacion_valuacion,
						  a.monto_original_contrato as              monto_original_contrato_valuacion,
						  a.aumento as                              aumento_valuacion,
						  a.disminucion as                          disminucion_valuacion,
						  a.monto_anticipo as                       monto_anticipo_valuacion,
						  a.monto_amortizacion as                   monto_amortizacion_valuacion,
						  a.monto_retenido_laboral as               monto_retenido_laboral_valuacion,
						  a.monto_retenido_fielcumplimiento as      monto_retenido_fielcumplimiento_valuacion,
						  a.monto_cancelado as                      monto_cancelado_valuacion,
						  a.monto_coniva as                         monto_coniva_valuacion,
						  a.monto_iva as                            monto_iva_valuacion,
						  a.porcentaje_iva as                       porcentaje_iva_valuacion,
						  a.monto_siniva as                         monto_siniva_valuacion,
						  a.retencion_incluye_iva  as               retencion_incluye_iva_valuacion,
						  a.monto_retencion_laboral  as             monto_retencion_laboral_valuacion,
						  a.porcentaje_laboral  as                  porcentaje_laboral_valuacion,
						  a.monto_retencion_fielcumplimiento as     monto_retencion_fielcumplimiento_valuacion,
						  a.porcentaje_fielcumplimiento  as         porcentaje_fielcumplimiento_valuacion,
						  a.monto_descontar_impuesto  as            monto_descontar_impuesto_valuacion,
						  a.amortizacion_anticipo  as               amortizacion_anticipo_valuacion,
						  a.porcentaje_amortizacion  as             porcentaje_amortizacion_valuacion,
						  a.monto_orden_pago  as                    monto_orden_pago_valuacion,
						  a.monto_retencion_iva  as                 monto_retencion_iva_valuacion,
						  a.porcentaje_retencion_iva  as            porcentaje_retencion_iva_valuacion,
						  a.monto_islr  as                          monto_islr_valuacion,
						  a.porcentaje_islr  as                     porcentaje_islr_valuacion,
						  a.monto_sustraendo as                     monto_sustraendo_valuacion,
						  a.monto_timbre_fiscal as                  monto_timbre_fiscal_valuacion,
						  a.porcentaje_timbre_fiscal as             porcentaje_timbre_fiscal_valuacion,
						  a.monto_impuesto_municipal as             monto_impuesto_municipal_valuacion,
						  a.porcentaje_impuesto_municipal as        porcentaje_impuesto_municipal_valuacion,
						  a.monto_neto_cobrar as                    monto_neto_cobrar_valuacion,
						  a.concepto as                             concepto_valuacion,
						  a.fecha_valuacion as                      fecha_valuacion_valuacion,
						  a.oficio_aprobacion as                    oficio_aprobacion_valuacion,
						  a.fecha_aprobacion as                     fecha_aprobacion_valuacion,
						  a.periodo_desde as                        periodo_desde_valuacion,
						  a.periodo_hasta as                        periodo_hasta_valuacion,
						  a.aumento_obra_extra  as                  aumento_obra_extra_valuacion,
						  a.aumento_reconsideracion_precio  as      aumento_reconsideracion_precio_valuacion,
						  a.aumento_obras  as                       aumento_obras_valuacion,
						  a.monto_mano_obra  as                     monto_mano_obra,
						  a.retencion_responsabilidad  as           retencion_responsabilidad,
						  a.retencion_multa  as                     retencion_multa,

						  b.*,
                   	  		      (SELECT SUM(aumento_obra_extra)              FROM  cobd01_contratoobras_modificacion_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_contrato_obra  = '".$ano_obra."'  and  x.numero_contrato_obra = '".$numero_contrato_obra."') as aumento_obra_extra,
                                  (SELECT SUM(aumento_reconsideracion_precio)  FROM  cobd01_contratoobras_modificacion_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_contrato_obra  = '".$ano_obra."'  and  x.numero_contrato_obra = '".$numero_contrato_obra."') as aumento_reconsideracion_precio,
                                  (SELECT SUM(aumento_obras)                   FROM  cobd01_contratoobras_modificacion_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_contrato_obra  = '".$ano_obra."'  and  x.numero_contrato_obra = '".$numero_contrato_obra."') as aumento_obras,
                                  (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) as cod_dep_original,
                                  (SELECT tipo_dependencia FROM cugd02_dependencias bb WHERE
                                                     bb.cod_tipo_institucion = a.cod_tipo_inst and
                                                     bb.cod_institucion      = a.cod_inst      and
                                                     bb.cod_dependencia      = (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) ) as tipo_dependencia,
                                  (SELECT denominacion FROM cugd02_dependencias bb WHERE
                                                     bb.cod_tipo_institucion = a.cod_tipo_inst and
                                                     bb.cod_institucion      = a.cod_inst      and
                                                     bb.cod_dependencia      = (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) ) as denominacion_dep,

                                   (SELECT cargo FROM cugd02_dependencias bb WHERE
                                                     bb.cod_tipo_institucion = a.cod_tipo_inst and
                                                     bb.cod_institucion      = a.cod_inst      and
                                                     bb.cod_dependencia      = (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) ) as cargo_dep,

                                  (SELECT funcionario_responsable FROM cugd02_dependencias bb WHERE
                                                     bb.cod_tipo_institucion = a.cod_tipo_inst and
                                                     bb.cod_institucion      = a.cod_inst      and
                                                     bb.cod_dependencia      = (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) ) as funcionario_responsable_dep

                                         from  cobd01_contratoobras_valuacion_cuerpo a, cobd01_contratoobras_cuerpo b

                             	where
                             			a.ano_contrato_obra    = '".$ano_obra."'             and
                             			a.numero_contrato_obra = '".$numero_contrato_obra."' and
                             			a.condicion_actividad  =  1                          and
                             			a.cod_presi            =  ".$cod_presi."             and
									    a.cod_entidad          =  ".$cod_entidad."           and
									    a.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    a.cod_inst             =  ".$cod_inst."              and
									    ".$cod_dep_sql."
									    b.cod_presi            =  ".$cod_presi."             and
									    b.cod_entidad          =  ".$cod_entidad."           and
									    b.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    b.cod_inst             =  ".$cod_inst."              and
									    b.cod_dep              =    a.cod_dep                and
									    b.ano_contrato_obra    = '".$ano_obra."'             and
                             			b.numero_contrato_obra = '".$numero_contrato_obra."'".$sql."


                                ORDER BY numero_valuacion ASC;
                   	  		");




             $this->set('datos',$datos);



     }//fin else



$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));
}//fin funtion










function numero_valuacion($var1=null, $var2=null){

	  $this->layout = "ajax";
      $cod_presi = $this->Session->read('SScodpresi');
      $cod_entidad = $this->Session->read('SScodentidad');
      $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $this->set('ano',      $var1);
	  $this->set('contrato', $var2);
	  $lista = "";
	  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and condicion_actividad=1  ';
//		if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
        $lista = $this->cobd01_contratoobras_valuacion_cuerpo->generateList($condicion." and ano_contrato_obra=".$var1." and numero_contrato_obra='".$var2."'   ", ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_valuacion', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_valuacion');
     	if($lista!=null){
     		$this->AddCero('obras', $lista);
     	}else{
     		$this->set('obras','');
     	}
//		}

}//fin function












function buscar_year($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $this->set('ano', $var1);
  $lista = "";

		if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){

			$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
			$lista = $this->cobd01_contratoobras_valuacion_cuerpo->generateList($condicion.' and ano_contrato_obra='.$var1, ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra');
			$this->set('obras', $lista);

		}else{

			$c = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion().' and ano_contrato_obra='.$var1, 'numero_contrato_obra, ano_contrato_obra', null, null);
			$sql_c = ""; //print_r($c);
			foreach($c as $c_aux){
						if($sql_c == ""){
			                          $sql_c  = "     numero_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra']."' ";
						}else{        $sql_c .= " or (numero_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra']."') ";		}//fin else
			}//fin for
			if($sql_c!=""){
				  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' ';
				  $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion." and (".$sql_c.")");
				  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.' ';
				  $b = $this->cfpd07_obras_cuerpo->findAll($condicion);
				foreach($a as $a_aux){
				   foreach($b as $b_aux){
				      if($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion']==$b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] &&  strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra'])==strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])){
				        $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']]=$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
				     }//fin if
				  }//fin foreach
				}//fin foreach
			}//fin if
			$this->set('obras', $lista);
			$this->set('ano',$var1);

		}//fin else


}//fin functi




function reporte_valuacion_2($var1=null){


    $this->set('opcion',$var1);
    $cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($var1=='si'){
				  $this->layout = "ajax";
				  $pag_num = 0;
				  $opcion = 'si';
				  $ano = $this->ano_ejecucion();
				  $SScoddeporig             =       $this->Session->read('SScoddeporig');
				  $SScoddep                 =       $this->Session->read('SScoddep');
				  $Modulo                   =       $this->Session->read('Modulo');
				  $lista = "";



				if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){

				$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'  ';
				$lista = $this->cobd01_contratoobras_valuacion_cuerpo->generateList($condicion.' and ano_contrato_obra='.$ano, ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra');
				$this->set('obras', $lista);
				$this->set('ano',$ano);

				}else{


				$c = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion().' and ano_contrato_obra='.$ano, 'numero_contrato_obra, ano_contrato_obra', null, null);
				$sql_c = ""; //print_r($c);
				foreach($c as $c_aux){
							if($sql_c == ""){
				                          $sql_c  = "     numero_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra']."' ";
							}else{        $sql_c .= " or (numero_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['numero_contrato_obra']."'  and ano_contrato_obra='".$c_aux['cobd01_contratoobras_valuacion_cuerpo']['ano_contrato_obra']."') ";		}//fin else
				}//fin for
				if($sql_c!=""){
					  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' ';
					  $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion." and (".$sql_c.")");
					  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.' ';
					  $b = $this->cfpd07_obras_cuerpo->findAll($condicion);
					foreach($a as $a_aux){
					   foreach($b as $b_aux){
					      if($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion']==$b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] &&  strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra'])==strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])){
					        $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']]=$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
					     }//fin if
					  }//fin foreach
					}//fin foreach
				}//fin if
				$this->set('obras', $lista);
				$this->set('ano',$ano);

				}//fin else



}else{

		    $this->layout = "pdf";
		    $SScoddeporig             =       $this->Session->read('SScoddeporig');
			$SScoddep                 =       $this->Session->read('SScoddep');
			$Modulo                   =       $this->Session->read('Modulo');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;



             $ano_obra             = $this->data['cobp01_contratoobras_valuacion']['ano_ejecucion'];
             $numero_contrato_obra = $this->data['cobp01_contratoobras_valuacion']['numero_contrato_obra'];


             if(isset($_SESSION['select_dependencia'])){if($_SESSION['select_dependencia']!='si'){$cod_dep=$_SESSION['select_dependencia']; $Modulo="2"; }else{$Modulo="2";}}

			          if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
						 	$cod_dep_sql = "";
						    $cod_dep_sql2 = "";
						    $this->set('global', 'si');
						}else{
							$cod_dep_sql  = "";
						    $cod_dep_sql2 = " , a.cod_dep";
						    $this->set('global', 'no');
						}//fin else


                   	  $datos = $this->cfpd07_plan_inversion->execute(" SELECT

												  a.cod_presi as                            cod_presi_valuacion,
												  a.cod_entidad as                          cod_entidad_valuacion,
												  a.cod_tipo_inst as                        cod_tipo_inst_valuacion,
												  a.cod_inst as                             cod_inst_valuacion,
												  a.cod_dep as                              cod_dep_valuacion,
												  a.ano_contrato_obra as                    ano_contrato_obra_valuacion,
												  a.numero_contrato_obra as                 numero_contrato_obra_valuacion,
												  a.numero_valuacion as                     numero_valuacion_valuacion,
												  a.monto_original_contrato as              monto_original_contrato_valuacion,
												  a.aumento as                              aumento_valuacion,
												  a.disminucion as                          disminucion_valuacion,
												  a.monto_anticipo as                       monto_anticipo_valuacion,
												  a.monto_amortizacion as                   monto_amortizacion_valuacion,
												  a.monto_retenido_laboral as               monto_retenido_laboral_valuacion,
												  a.monto_retenido_fielcumplimiento as      monto_retenido_fielcumplimiento_valuacion,
												  a.monto_cancelado as                      monto_cancelado_valuacion,
												  a.monto_coniva as                         monto_coniva_valuacion,
												  a.monto_iva as                            monto_iva_valuacion,
												  a.porcentaje_iva as                       porcentaje_iva_valuacion,
												  a.monto_siniva as                         monto_siniva_valuacion,
												  a.retencion_incluye_iva  as               retencion_incluye_iva_valuacion,
												  a.monto_retencion_laboral  as             monto_retencion_laboral_valuacion,
												  a.porcentaje_laboral  as                  porcentaje_laboral_valuacion,
												  a.monto_retencion_fielcumplimiento as     monto_retencion_fielcumplimiento_valuacion,
												  a.porcentaje_fielcumplimiento  as         porcentaje_fielcumplimiento_valuacion,
												  a.monto_descontar_impuesto  as            monto_descontar_impuesto_valuacion,
												  a.amortizacion_anticipo  as               amortizacion_anticipo_valuacion,
												  a.porcentaje_amortizacion  as             porcentaje_amortizacion_valuacion,
												  a.monto_orden_pago  as                    monto_orden_pago_valuacion,
												  a.monto_retencion_iva  as                 monto_retencion_iva_valuacion,
												  a.porcentaje_retencion_iva  as            porcentaje_retencion_iva_valuacion,
												  a.monto_islr  as                          monto_islr_valuacion,
												  a.porcentaje_islr  as                     porcentaje_islr_valuacion,
												  a.monto_sustraendo as                     monto_sustraendo_valuacion,
												  a.monto_timbre_fiscal as                  monto_timbre_fiscal_valuacion,
												  a.porcentaje_timbre_fiscal as             porcentaje_timbre_fiscal_valuacion,
												  a.monto_impuesto_municipal as             monto_impuesto_municipal_valuacion,
												  a.porcentaje_impuesto_municipal as        porcentaje_impuesto_municipal_valuacion,
												  a.monto_neto_cobrar as                    monto_neto_cobrar_valuacion



                   	  		, b.*,

                   	  			 (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) as cod_dep_original,
                                 (SELECT tipo_dependencia FROM cugd02_dependencias bb WHERE
                                                     bb.cod_tipo_institucion = a.cod_tipo_inst and
                                                     bb.cod_institucion      = a.cod_inst      and
                                                     bb.cod_dependencia      = (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) ) as tipo_dependencia,
                                  (SELECT denominacion FROM cugd02_dependencias bb WHERE
                                                     bb.cod_tipo_institucion = a.cod_tipo_inst and
                                                     bb.cod_institucion      = a.cod_inst      and
                                                     bb.cod_dependencia      = (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) ) as denominacion_dep,


                                  (SELECT cargo FROM cugd02_dependencias bb WHERE
                                                     bb.cod_tipo_institucion = a.cod_tipo_inst and
                                                     bb.cod_institucion      = a.cod_inst      and
                                                     bb.cod_dependencia      = (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) ) as cargo_dep,

                                  (SELECT funcionario_responsable FROM cugd02_dependencias bb WHERE
                                                     bb.cod_tipo_institucion = a.cod_tipo_inst and
                                                     bb.cod_institucion      = a.cod_inst      and
                                                     bb.cod_dependencia      = (SELECT x.cod_dep_original   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.ano_estimacion  = '".$ano_obra."'  and  x.cod_obra = b.cod_obra) ) as funcionario_responsable_dep


                                         from  cobd01_contratoobras_valuacion_cuerpo a, cobd01_contratoobras_cuerpo b

                             	where
                             			a.ano_contrato_obra    = '".$ano_obra."'             and
                             			a.numero_contrato_obra = '".$numero_contrato_obra."' and
                             			a.condicion_actividad  =  1                          and
                             			a.cod_presi            =  ".$cod_presi."             and
									    a.cod_entidad          =  ".$cod_entidad."           and
									    a.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    a.cod_inst             =  ".$cod_inst."              and
									    a.cod_dep              =  ".$cod_dep."               and
									    b.cod_presi            =  ".$cod_presi."             and
									    b.cod_entidad          =  ".$cod_entidad."           and
									    b.cod_tipo_inst        =  ".$cod_tipo_inst."         and
									    b.cod_inst             =  ".$cod_inst."              and
									    b.cod_dep              =  ".$cod_dep."               and
									    b.ano_contrato_obra    = '".$ano_obra."'             and
                             			b.numero_contrato_obra = '".$numero_contrato_obra."'

                                ORDER BY numero_valuacion ASC;
                   	  		");



             $this->set('datos',$datos);


     }//fin else


$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));
}//fin funtion


function reporte_saldo_partidas_form(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$this->set('cod_dep', $cod_dep);

	if($cod_dep == 1){
		$dependencias = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep ASC', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
		$this->concatena($dependencias, 'dependencias');
		$disabled = "";
	}else{
		$dependencias = $this->arrd05->generateList($conditions = $this->condicion(), $order = 'cod_dep ASC', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
		$this->concatena($dependencias, 'dependencias');
		$disabled = "disabled";
	}

	$this->set('disabled', $disabled);

	/*$dependencias = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep ASC', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
	$this->concatena($dependencias, 'dependencias');*/
	$denominacion_dep = $this->arrd05->field('arrd05.denominacion', $conditions = $this->condicion(), $order ="cod_dep ASC");
	$this->set('denominacion_dep', $denominacion_dep);

	$tipo_recurso = $this->csrd01_tipo_solicitud->generateList($conditions = null, $order = 'cod_tipo_solicitud ASC', $limit = null, '{n}.csrd01_tipo_solicitud.cod_tipo_solicitud', '{n}.csrd01_tipo_solicitud.denominacion');

	$this->concatena($tipo_recurso, 'tipo_recurso');

}

function deno_recurso($tipo_recurso=null) {
	$this->layout = "ajax";

	if($tipo_recurso != null){

		$denominacion = $this->csrd01_tipo_solicitud->field('csrd01_tipo_solicitud.denominacion', $conditions = "cod_tipo_solicitud='$tipo_recurso'", $order =null);
		$this->set('denominacion', $denominacion);
	}
}

function reporte_saldo_partidas_pdf(){
	$this->layout= "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->data['reporte3']['cod_dep'];

	$dep = $this->arrd05->field('denominacion', $conditions = $this->condicionNDEP()." and cod_dep='$cod_dep'", $order ="cod_dep ASC");
	$this->set('nom_dep',$dep);

	$cod_recurso = $this->data['reporte3']['cod_recurso'];

	$denominacion = $this->csrd01_tipo_solicitud->field('csrd01_tipo_solicitud.denominacion', $conditions = "cod_tipo_solicitud='$cod_recurso'", $order =null);
	$this->set('denominacion', $denominacion);


	$query = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud, a.numero_solicitud,
a.fecha_solicitud, a.mes_solicitado, b.ano,
b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar,
a.monto_solicitado, b.monto, b.monto_entregado,

null_cero((SELECT SUM(e.asignacion_anual_actualizada) FROM v_solicitud_cfpd05_p2 e
WHERE
e.cod_presi = a.cod_presi and
e.cod_entidad = a.cod_entidad and
e.cod_tipo_inst = a.cod_tipo_inst and
e.cod_inst = a.cod_inst and
e.cod_dep = a.cod_dep and
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
e.cod_partida=b.cod_partida and
e.ano = b.ano
group by e.cod_presi, e.cod_entidad,e.cod_tipo_inst, e.cod_inst, e.cod_dep, e.cod_partida
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
a.tipo_solicitud_recurso!=".$cod_recurso." and c.cod_dep='$cod_dep' and
 b.cod_partida=c.cod_partida and b.cod_generica=c.cod_generica and b.cod_especifica=c.cod_especifica and b.cod_sub_espec=c.cod_sub_espec and b.cod_auxiliar=c.cod_auxiliar
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
a.tipo_solicitud_recurso='$cod_recurso' and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep'
 GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.ano_solicitud, a.numero_solicitud, a.fecha_solicitud, a.mes_solicitado, b.ano, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, a.monto_solicitado, b.monto, b.monto_entregado
 ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, b.ano, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar,a.ano_solicitud, a.numero_solicitud";

	$datos = $this->csrd01_tipo_solicitud->execute($query);

	$this->set('datos', $datos);
	//pr($datos);

}

function deno_dep($cod_dep=null){
	$this->layout= "ajax";

	if($cod_dep != null){
		$deno_dep = $this->arrd05->field('denominacion', $this->condicionNDEP()." and cod_dep='$cod_dep'", null);
		$this->set('deno_dep', $deno_dep);
	}

}



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



function guardar_editar_firmas($varf=null, $cantf=null){
	$this->layout="ajax";

	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	if($varf=='no'){
		$this->set('varf',''.$varf);
		$this->set('cantf',''.$cantf);
		echo "<script>document.getElementById('link_limpiaf').style.display= 'block';</script>";
		$this->set('Message_existe','Puede modificar los datos de los firmantes...');
	}
else if($varf=='si'){

	$modelo = 'reporte';

	$tipo_doc = $this->data[$modelo]['cod_tipo_documento'];
	$r1 = isset($this->data[$modelo]['responsa_primera_firma']) ? $this->data[$modelo]['responsa_primera_firma'] : '';
	$f1 = isset($this->data[$modelo]['funcionario_primera_firma']) ? $this->data[$modelo]['funcionario_primera_firma'] : '';
	$ca1 = isset($this->data[$modelo]['cargo_primera_firma']) ? $this->data[$modelo]['cargo_primera_firma'] : '';
	$ce1 = isset($this->data[$modelo]['cedula_primera_firma']) ? $this->data[$modelo]['cedula_primera_firma'] : '';

	$r2 = isset($this->data[$modelo]['responsa_segunda_firma']) ? $this->data[$modelo]['responsa_segunda_firma'] : '';
	$f2 = isset($this->data[$modelo]['funcionario_segunda_firma']) ? $this->data[$modelo]['funcionario_segunda_firma'] : '';
	$ca2 = isset($this->data[$modelo]['cargo_segunda_firma']) ? $this->data[$modelo]['cargo_segunda_firma'] : '';
	$ce2 = isset($this->data[$modelo]['cedula_segunda_firma']) ? $this->data[$modelo]['cedula_segunda_firma'] : '';

	$r3 = isset($this->data[$modelo]['responsa_tercera_firma']) ? $this->data[$modelo]['responsa_tercera_firma'] : '';
	$f3 = isset($this->data[$modelo]['funcionario_tercera_firma']) ? $this->data[$modelo]['funcionario_tercera_firma'] : '';
	$ca3 = isset($this->data[$modelo]['cargo_tercera_firma']) ? $this->data[$modelo]['cargo_tercera_firma'] : '';
	$ce3 = isset($this->data[$modelo]['cedula_tercera_firma']) ? $this->data[$modelo]['cedula_tercera_firma'] : '';

	$r4 = isset($this->data[$modelo]['responsa_cuarta_firma']) ? $this->data[$modelo]['responsa_cuarta_firma'] : '';
	$f4 = isset($this->data[$modelo]['funcionario_cuarta_firma']) ? $this->data[$modelo]['funcionario_cuarta_firma'] : '';
	$ca4 = isset($this->data[$modelo]['cargo_cuarta_firma']) ? $this->data[$modelo]['cargo_cuarta_firma'] : '';
	$ce4 = isset($this->data[$modelo]['cedula_cuarta_firma']) ? $this->data[$modelo]['cedula_cuarta_firma'] : '';

	$r5 = isset($this->data[$modelo]['responsa_quinta_firma']) ? $this->data[$modelo]['responsa_quinta_firma'] : '';
	$f5 = isset($this->data[$modelo]['funcionario_quinta_firma']) ? $this->data[$modelo]['funcionario_quinta_firma'] : '';
	$ca5 = isset($this->data[$modelo]['cargo_quinta_firma']) ? $this->data[$modelo]['cargo_quinta_firma'] : '';
	$ce5 = isset($this->data[$modelo]['cedula_quinta_firma']) ? $this->data[$modelo]['cedula_quinta_firma'] : '';

	$r6 = isset($this->data[$modelo]['responsa_sexta_firma']) ? $this->data[$modelo]['responsa_sexta_firma'] : '';
	$f6 = isset($this->data[$modelo]['funcionario_sexta_firma']) ? $this->data[$modelo]['funcionario_sexta_firma'] : '';
	$ca6 = isset($this->data[$modelo]['cargo_sexta_firma']) ? $this->data[$modelo]['cargo_sexta_firma'] : '';
	$ce6 = isset($this->data[$modelo]['cedula_sexta_firma']) ? $this->data[$modelo]['cedula_sexta_firma'] : '';

	$r7 = isset($this->data[$modelo]['responsa_septima_firma']) ? $this->data[$modelo]['responsa_septima_firma'] : '';
	$f7 = isset($this->data[$modelo]['funcionario_septima_firma']) ? $this->data[$modelo]['funcionario_septima_firma'] : '';
	$ca7 = isset($this->data[$modelo]['cargo_septima_firma']) ? $this->data[$modelo]['cargo_septima_firma'] : '';
	$ce7 = isset($this->data[$modelo]['cedula_septima_firma']) ? $this->data[$modelo]['cedula_septima_firma'] : '';

	$r8 = isset($this->data[$modelo]['responsa_octava_firma']) ? $this->data[$modelo]['responsa_octava_firma'] : '';
	$f8 = isset($this->data[$modelo]['funcionario_octava_firma']) ? $this->data[$modelo]['funcionario_octava_firma'] : '';
	$ca8 = isset($this->data[$modelo]['cargo_octava_firma']) ? $this->data[$modelo]['cargo_octava_firma'] : '';
	$ce8 = isset($this->data[$modelo]['cedula_octava_firma']) ? $this->data[$modelo]['cedula_octava_firma'] : '';

	$enc_td_firma = $this->cugd99_firmas_responsabilidad->findCount($this->SQLCA()." and cod_tipo_documento=$tipo_doc");

	if($enc_td_firma==0){
		$muestr_accion = 'Registradas';
		$sql_ejecutar = "INSERT INTO cugd99_firmas_responsabilidad VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc, '$r1', '$f1', '$ca1', '$ce1','$r2', '$f2', '$ca2', '$ce2', '$r3', '$f3', '$ca3', '$ce3', '$r4', '$f4', '$ca4', '$ce4', '$r5', '$f5', '$ca5', '$ce5', '$r6', '$f6', '$ca6', '$ce6', '$r7', '$f7', '$ca7', '$ce7', '$r8', '$f8', '$ca8', '$ce8');";
	}else{
		$muestr_accion = 'Modificadas';
		$sql_ejecutar = "UPDATE cugd99_firmas_responsabilidad SET responsa_primera_firma='$r1', funcionario_primera_firma='$f1', cargo_primera_firma='$ca1', cedula_primera_firma='$ce1', responsa_segunda_firma='$r2', funcionario_segunda_firma='$f2', cargo_segunda_firma='$ca2', cedula_segunda_firma='$ce2', responsa_tercera_firma='$r3', funcionario_tercera_firma='$f3', cargo_tercera_firma='$ca3', cedula_tercera_firma='$ce3', responsa_cuarta_firma='$r4', funcionario_cuarta_firma='$f4', cargo_cuarta_firma='$ca4', cedula_cuarta_firma='$ce4', responsa_quinta_firma='$r5', funcionario_quinta_firma='$f5', cargo_quinta_firma='$ca5', cedula_quinta_firma='$ce5', responsa_sexta_firma='$r6', funcionario_sexta_firma='$f6', cargo_sexta_firma='$ca6', cedula_sexta_firma='$ce6', responsa_septima_firma='$r7', funcionario_septima_firma='$f7', cargo_septima_firma='$ca7', cedula_septima_firma='$ce7', responsa_octava_firma='$r8', funcionario_octava_firma='$f8', cargo_octava_firma='$ca8', cedula_octava_firma='$ce8' WHERE ".$this->SQLCA()." and cod_tipo_documento=".$tipo_doc;
	}

	$swi = $this->cugd99_firmas_responsabilidad->execute($sql_ejecutar);

	$this->set('varf',''.$varf);
	$this->set('cantf',''.$cantf);

	if($swi>1){
		if($muestr_accion == 'Modificadas'){
			echo "<script>document.getElementById('link_limpiaf').style.display= 'none';</script>";
		}
		$this->set('Message_existe','Las firmas fuer&oacute;n '.$muestr_accion.' correctamente');
	}else{
		$this->set('errorMessage','Las firmas no fuer&oacute;n '.$muestr_accion.'');
	}
}else{
	$this->set('errorMessage','Lo siento la informaci&oacute;n no puede ser procesada...');
}
} // fin funcion guardar_editar_firmas


function borrar_firmas($cantf=null){
	$this->layout="ajax";
	$this->set('cantf',''.$cantf);
}



/* *
 * Reporte: reporte_emision_nota_debito
 * Funciones necesarias: reporte_emision_nota_debito, session_ano_nd y select_notas_debito.
 * Descripcion: genera el reporte de todas las notas de debitos registradas en cstd09_notadebito_ordenes. Filtrada por las condiciones que ingrese el usuario.
 */
function reporte_emision_nota_debito($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano = $this->ano_ejecucion();
			$this->Session->write('ano_reporte_emision_nd',$ano);//Se escribe el ano en session para el reporte de emision nota de debito.
			$this->set('var',$var);
			$this->set('ano_ejecucion',$ano);
			$this->envia_form_firmas(3, 2); // 3: Doc. Nota de Debito, con 2 firmas...

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$this->envia_form_firmas(3, 2); // 3: Doc. Nota de Debito, con 2 firmas...

			$ano = $_SESSION['ano_reporte_emision_nd'];
			$tipo_reporte = $this->data['emision_nota_debito']['tipo_reporte'];
			if(isset($ano)){
				if($tipo_reporte==1){
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
										a.ano_movimiento='$ano' ORDER BY a.numero_debito, a.cuenta_bancaria, fecha_debito;";

					$notad = $this->cstd09_notadebito_ordenes->execute($sql_ndebito);
					$this->set('tipo_reporte',$tipo_reporte);
					$this->set('notad',$notad);
				}elseif($tipo_reporte==2){
					$select_nota_debito = $this->data['emision_nota_debito']['select_nota_debito'];
					if($select_nota_debito!='' && $select_nota_debito!='NO'){
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
						$this->set('tipo_reporte',$tipo_reporte);
						$this->set('notad',$notad);
					}else{
						echo "<script>history.back(1)</script>";
					}
				}
				$find_ent_ban = $this->cstd01_entidades_bancarias->findAll(null,null,'cod_entidad_bancaria ASC');
				foreach($find_ent_ban as $ent){
				$ent_ban[$ent['cstd01_entidades_bancarias']['cod_entidad_bancaria']] = $ent['cstd01_entidades_bancarias']['denominacion'];
				}
				$this->set('ent_ban',$ent_ban);
				$this->set('var',$var);

			}else{
				echo "<script>history.back(1)</script>";
			}
		}

	}else{
		echo "<script>history.back(1)</script>";
	}
}//reporte_emision_nota_debito




/* *
 * Reporte: reporte_emision_nota_debito_transferencia
 * Funciones necesarias: reporte_emision_nota_debito_transferencia, session_ano_nd y select_notas_debito.
 * Descripcion: genera el reporte de todas las notas de debitos registradas en cstd03_movimiento_manuales. Filtrada por las condiciones que ingrese el usuario.
 */



function reporte_emision_nota_debito_transferencia($var=null){

  if($var!=null){
    if($var=='si'){// Se muestra la vista del formulario.
      $this->layout="ajax";
      $ano = $this->ano_ejecucion();
      $this->Session->write('ano_reporte_emision_nd',$ano);//Se escribe el ano en session para el reporte de emision nota de debito.
      $this->set('var',$var);
      $this->set('ano_ejecucion',$ano);
      $this->envia_form_firmas(3, 2); // 3: Doc. Nota de Debito, con 2 firmas...

    }elseif($var=='no'){// Se muestra la vista del reporte.
      $this->layout = "pdf";
      
      $this->envia_form_firmas(3, 2); // 3: Doc. Nota de Debito, con 2 firmas...

      $ano = $_SESSION['ano_reporte_emision_nd'];
      $tipo_reporte = $this->data['emision_nota_debito']['tipo_reporte'];
      $cod_dep = $this->Session->read('SScoddep');
      $condcion_nota_debito="a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$this->verifica_SS(5);

      if(isset($ano)){

        if($tipo_reporte==1){

//-----------------Reporte de todas las Notas de Debito------------------------

          $sql_ndebito = "SELECT a.numero_documento, a.fecha_documento, 
(SELECT denominacion FROM cstd01_entidades_bancarias as b WHERE b.cod_entidad_bancaria=a.cod_entidad_bancaria) AS entidad_bancaria, a.cuenta_bancaria, a.beneficiario, a.monto, a.concepto FROM cstd03_movimientos_manuales as a WHERE a.ano_movimiento=$ano AND a.tipo_documento=3 AND ".$condcion_nota_debito;
          $notad = $this->cstd03_movimientos_manuales->execute($sql_ndebito);
          $this->set('tipo_reporte',$tipo_reporte);
          $this->set('notad',$notad);

        }elseif($tipo_reporte==2){

//----------------Reporte de Notas de Debito Especificas-----------------------------

          $select_nota_debito = $this->data['emision_nota_debito']['select_nota_debito_transferencia'];
          if($select_nota_debito!='' && $select_nota_debito!='NO'){

            $sql_ndebito = "SELECT a.numero_documento, a.fecha_documento, (SELECT denominacion FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=a.cod_entidad_bancaria) AS entidad_bancaria, a.cuenta_bancaria, a.beneficiario, b.numero_solicitud, a.monto, a.concepto
                              FROM cstd03_movimientos_manuales a, csrd01_solicitud_recurso_cuerpo as b
                              WHERE b.cod_entidad_bancaria=a.cod_entidad_bancaria AND b.cod_sucursal=a.cod_sucursal AND b.cuenta_bancaria=a.cuenta_bancaria AND b.numero_cheque::text=a.numero_documento::text and a.ano_movimiento=$ano AND a.numero_documento='$select_nota_debito';";


            $notad = $this->cstd03_movimientos_manuales->execute($sql_ndebito);
            $this->set('tipo_reporte',$tipo_reporte);
            $this->set('notad',$notad);

          }else{
            echo "<script>history.back(1)</script>";
          }

        }elseif($tipo_reporte==3){
            $sql_ndebito = "SELECT a.numero_documento, a.fecha_documento, (SELECT denominacion FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=a.cod_entidad_bancaria) AS entidad_bancaria, a.cuenta_bancaria, a.beneficiario, b.numero_solicitud, a.monto, a.concepto
                                        FROM cstd03_movimientos_manuales a, csrd01_solicitud_recurso_cuerpo as b
                                        WHERE b.cod_entidad_bancaria=a.cod_entidad_bancaria AND b.cod_sucursal=a.cod_sucursal AND b.cuenta_bancaria=a.cuenta_bancaria AND b.numero_cheque::text=a.numero_documento::text and a.codi_dep= $cod_dep and a.ano_movimiento=$ano;";
                      $notad = $this->cstd03_movimientos_manuales->execute($sql_ndebito);
                      $this->set('tipo_reporte',$tipo_reporte);
                      $this->set('notad',$notad);
        }
        
        $this->set('var',$var);

      }else{
        echo "<script>history.back(1)</script>";
      }
    }

  }else{
    echo "<script>history.back(1)</script>";
  }
}//reporte_emision_nota_debito_transferencia






function firmantes_nota_debito(){

      $this->layout="ajax";
      $ano = $this->ano_ejecucion();
      $this->set('ano_ejecucion',$ano);
      $this->envia_form_firmas(3, 2); // 3: Doc. Nota de Debito, con 2 firmas...

}//firmantes de Nota de Debito





/*function consolidacion_dependencia($var=null){
	$this->layout="ajax";
	//echo $var;
	if($var==1){
	   $this->Session->write('tipo_consolidado','1');//Institucion.
	}elseif($var==2){
	   $this->Session->write('tipo_consolidado','2');//Dependencia.
	}
	echo "<script>".
	 	  "document.getElementById('tipo_reporte_2').checked=false; ".
	 	  "document.getElementById('tipo_reporte_1').checked=true; ".
		  "</script>";
}*/


function session_ano_nd($ano=null){
	$this->layout="ajax";
	$ano == null ? $this->Session->write('ano_reporte_emision_nd',$this->ano_ejecucion()) : $this->Session->write('ano_reporte_emision_nd',$ano);
	echo "<script>".
	 	  "document.getElementById('tipo_reporte_2').checked=false; ".
	 	  "document.getElementById('tipo_reporte_1').checked=true; ".
		  "</script>";
}//session_ano_nd

function select_notas_debito($var=null){
	$this->layout="ajax";
	if($var!=null){
		$cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$ano = $_SESSION['ano_reporte_emision_nd'];
		if($var==1){
			$this->set('var',3);
		}elseif($var==2){

			$sqlselectnd = "SELECT a.numero_debito, (SELECT b.beneficiario FROM cstd09_notadebito_cuerpo b WHERE a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.cod_dep=b.cod_dep AND a.ano_movimiento=b.ano_movimiento AND a.cod_entidad_bancaria=b.cod_entidad_bancaria AND a.cod_sucursal=b.cod_sucursal AND a.cuenta_bancaria=b.cuenta_bancaria AND a.numero_debito=b.numero_debito) AS beneficiario
							FROM cstd09_notadebito_ordenes a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_movimiento='$ano' ORDER BY a.numero_debito;";

			$notad = $this->cstd09_notadebito_ordenes->execute($sqlselectnd);
			if(count($notad)!=0){
				for($i=0; $i<count($notad); $i++){
				$nd[]  = $notad[$i][0]['numero_debito'];
				$dep[] = $notad[$i][0]['numero_debito'].' - '.$notad[$i][0]['beneficiario'];
				}
				$list = array_combine($nd, $dep);
				$this->set('list',$list);
				$this->set('var',$var);
			}else{
				$this->set('list',array('no'=>'No hay registros'));
				$this->set('var',$var);
			}
		}elseif($var==3){
			// @var:3 Indica que se activo por el input del ano.
			// Por esa razon se imprime vacio, para tumbar lo que existiera antes de cambiar el input, como asi cambiaria la condicion.
			$this->set('var',$var);
		}
	}
}//select_notas_debito




function select_notas_debito_transferencia($var=null){
  $this->layout="ajax";
  if($var!=null){
   
    $ano = $_SESSION['ano_reporte_emision_nd'];
    if($var==1){
      $this->set('var',3);
    }elseif($var==2){
      
      $sqlselectnd = "SELECT a.numero_documento, a.beneficiario
                        FROM cstd03_movimientos_manuales as a, csrd01_solicitud_recurso_cuerpo as b
                        WHERE b.cod_entidad_bancaria=a.cod_entidad_bancaria AND b.cod_sucursal=a.cod_sucursal AND b.cuenta_bancaria=a.cuenta_bancaria AND b.numero_cheque <>0 AND b.numero_cheque::text=a.numero_documento::text AND b.ano_solicitud=$ano 
                        ORDER BY a.numero_documento;";
      $notad = $this->cstd03_movimientos_manuales->execute($sqlselectnd);
      if(count($notad)!=0){
        for($i=0; $i<count($notad); $i++){
        $nd[]  = $notad[$i][0]['numero_documento'];
        $dep[] = ($i+1).'- '.$notad[$i][0]['numero_documento'].' - '.$notad[$i][0]['beneficiario'];
        }
        $list = array_combine($nd, $dep);
        $this->set('list',$list);
        $this->set('var',$var);
      }else{
        $this->set('list',array('no'=>'No hay registros'));
        $this->set('var',$var);
      }
    }elseif($var==3){
      // @var:3 Indica que se activo por el input del ano.
      // Por esa razon se imprime vacio, para tumbar lo que existiera antes de cambiar el input, como asi cambiaria la condicion.
      $this->set('var',$var);
    }
  }
}//select_notas_debito_transferencia





/* *
 * Reporte: Reporte_Cuadro_Enterar_Imp_Srlr
 * Funciones necesarias:
 * Descripcion:
 */
function reporte_cuadro_enterar_imp_srlr($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano = $this->ano_ejecucion();
			$this->set('ano_ejecucion',$ano);
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');


			$ano = $this->data['cuadro_enterar_imp_srlr']['ano'];
			$ano == '' ? $ano = $this->ano_ejecucion() : '';
			$mes = $this->data['cuadro_enterar_imp_srlr']['mes'];
			$mes < 10 ? $mes='0'.$mes : '';

			// int mktime  ([ int $hora  [, int $minuto  [, int $segundo  [, int $mes  [, int $dia  [, int $anyo  [, int $es_dst  ]]]]]] )
			$fecha_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
			$fecha_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));

			if($cod_dep!=1){
				$sql_distinct  = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, porcentaje_islr FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND monto_islr!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, porcentaje_islr;";
				$distinct_islr = $this->cstd07_retenciones_cuerpo_islr->execute($sql_distinct);
				$datos = array();
				for($i=0; $i<count($distinct_islr); $i++){
					$sql = "SELECT
							a.cod_tipo_pago,
							a.porcentaje_islr,
							a.ano_orden_pago,
							a.monto_descontar_impuesto,
							a.monto_islr,
							a.monto_sustraendo,
							a.numero_orden_pago,
							a.autorizado,
							b.ano_orden_pago,
							b.numero_orden_pago,
							b.numero_cheque,
							(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
							(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
							(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
							g.denominacion as dependencia,
							g.agente_retencion,
							g.fiscal_rentas,
							g.direccion,
							g.cod_area,
							g.telefonos,
							g.email,
							g.rif,
							g.nit
							FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cugd02_dependencias g WHERE
							a.cod_presi=b.cod_presi AND
							a.cod_entidad=b.cod_entidad AND
							a.cod_tipo_inst=b.cod_tipo_inst AND
							a.cod_inst=b.cod_inst AND
							a.cod_dep=b.cod_dep AND
							a.ano_orden_pago=b.ano_orden_pago AND
							a.numero_orden_pago=b.numero_orden_pago AND
							b.cod_tipo_inst=g.cod_tipo_institucion AND
							b.cod_inst=g.cod_institucion AND
							b.cod_dep=g.cod_dependencia AND
							a.cod_presi='$cod_presi' AND
							a.cod_entidad='$cod_entidad' AND
							a.cod_tipo_inst='$cod_tipo_inst' AND
							a.cod_inst='$cod_inst' AND
							a.cod_dep='$cod_dep' AND
							a.ano_orden_pago = ".$distinct_islr[$i][0]['ano_orden_pago']." AND
							a.cod_tipo_pago  = ".$distinct_islr[$i][0]['cod_tipo_pago']." AND
							a.porcentaje_islr = ".$distinct_islr[$i][0]['porcentaje_islr']." AND
							a.monto_islr!='0' AND
							a.cuenta_bancaria!='0' AND
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
							ORDER BY a.cod_tipo_pago, a.porcentaje_islr, b.numero_cheque, a.numero_orden_pago;";
					$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
				}
				$this->set('datos',$datos);
				$this->set('ano',$ano);
				$this->set('mes',$mes);


			}elseif($cod_dep==1){
				$consolidacion = $this->data['cuadro_enterar_imp_srlr']['consolidacion'];
				if($consolidacion == 1){
					$sql_distinct  = "SELECT DISTINCT cod_dep, ano_orden_pago, cod_tipo_pago, porcentaje_islr FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND ano_orden_pago='$ano' AND monto_islr!='0' AND cuenta_bancaria!='0' ORDER BY cod_dep, ano_orden_pago, cod_tipo_pago, porcentaje_islr;";
					$distinct_islr = $this->cstd07_retenciones_cuerpo_islr->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_islr); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.porcentaje_islr,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.monto_islr,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep=".$distinct_islr[$i][0]['cod_dep']." AND
								a.ano_orden_pago = ".$distinct_islr[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_islr[$i][0]['cod_tipo_pago']." AND
								a.porcentaje_islr = ".$distinct_islr[$i][0]['porcentaje_islr']." AND
								a.monto_islr!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_dep, a.cod_tipo_pago, a.porcentaje_islr, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);

				}elseif($consolidacion == 2){
					$sql_distinct  = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, porcentaje_islr FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND monto_islr!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, porcentaje_islr;";
					$distinct_islr = $this->cstd07_retenciones_cuerpo_islr->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_islr); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.porcentaje_islr,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.monto_islr,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep='$cod_dep' AND
								a.ano_orden_pago = ".$distinct_islr[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_islr[$i][0]['cod_tipo_pago']." AND
								a.porcentaje_islr = ".$distinct_islr[$i][0]['porcentaje_islr']." AND
								a.monto_islr!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_tipo_pago, a.porcentaje_islr, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);
				}
			}
		$this->set('var',$var);//no
		}
	}
}//reporte_cuadro_enterar_imp_srlr


/* *
 * Reporte: Reporte_Cuadro_Enterar_TimbreFiscal
 * Funciones necesarias:
 * Descripcion:
 */
function reporte_cuadro_enterar_timbrefiscal($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano = $this->ano_ejecucion();
			$this->set('ano_ejecucion',$ano);
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$ano = $this->data['cuadro_enterar_timbrefiscal']['ano'];
			$ano == '' ? $ano = $this->ano_ejecucion() : '';
			$mes = $this->data['cuadro_enterar_timbrefiscal']['mes'];
			$mes < 10 ? $mes='0'.$mes : '';
			$fecha_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
			$fecha_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));

			if($cod_dep!=1){
				$sql_distinct  = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, porcentaje_timbre_fiscal FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND monto_timbre_fiscal!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, porcentaje_timbre_fiscal;";
				$distinct_timbre = $this->cstd07_retenciones_cuerpo_islr->execute($sql_distinct);
				$datos = array();
				for($i=0; $i<count($distinct_timbre); $i++){
					$sql = "SELECT
							a.cod_tipo_pago,
							a.porcentaje_timbre_fiscal,
							a.ano_orden_pago,
							a.monto_descontar_impuesto,
							a.monto_timbre_fiscal,
							a.monto_sustraendo,
							a.numero_orden_pago,
							a.autorizado,
							b.ano_orden_pago,
							b.numero_orden_pago,
							b.numero_cheque,
							(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
							(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
							(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
							g.denominacion as dependencia,
							g.agente_retencion,
							g.fiscal_rentas,
							g.direccion,
							g.cod_area,
							g.telefonos,
							g.email,
							g.rif,
							g.nit
							FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_timbre b, cugd02_dependencias g WHERE
							a.cod_presi=b.cod_presi AND
							a.cod_entidad=b.cod_entidad AND
							a.cod_tipo_inst=b.cod_tipo_inst AND
							a.cod_inst=b.cod_inst AND
							a.cod_dep=b.cod_dep AND
							a.ano_orden_pago=b.ano_orden_pago AND
							a.numero_orden_pago=b.numero_orden_pago AND
							b.cod_tipo_inst=g.cod_tipo_institucion AND
							b.cod_inst=g.cod_institucion AND
							b.cod_dep=g.cod_dependencia AND
							a.cod_presi='$cod_presi' AND
							a.cod_entidad='$cod_entidad' AND
							a.cod_tipo_inst='$cod_tipo_inst' AND
							a.cod_inst='$cod_inst' AND
							a.cod_dep='$cod_dep' AND
							a.ano_orden_pago = ".$distinct_timbre[$i][0]['ano_orden_pago']." AND
							a.cod_tipo_pago  = ".$distinct_timbre[$i][0]['cod_tipo_pago']." AND
							a.porcentaje_timbre_fiscal = ".$distinct_timbre[$i][0]['porcentaje_timbre_fiscal']." AND
							a.monto_timbre_fiscal!='0' AND
							a.cuenta_bancaria!='0' AND
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
							ORDER BY a.cod_tipo_pago, a.porcentaje_timbre_fiscal, b.numero_cheque, a.numero_orden_pago;";
					$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
				}
				$this->set('datos',$datos);
				$this->set('ano',$ano);
				$this->set('mes',$mes);


			}elseif($cod_dep==1){
				$consolidacion = $this->data['cuadro_enterar_timbrefiscal']['consolidacion'];
				if($consolidacion == 1){
					$sql_distinct  = "SELECT DISTINCT cod_dep, ano_orden_pago, cod_tipo_pago, porcentaje_timbre_fiscal FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND ano_orden_pago='$ano' AND monto_timbre_fiscal!='0' AND cuenta_bancaria!='0' ORDER BY cod_dep, ano_orden_pago, cod_tipo_pago, porcentaje_timbre_fiscal;";
					$distinct_timbre = $this->cstd07_retenciones_cuerpo_islr->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_timbre); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.porcentaje_timbre_fiscal,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.monto_timbre_fiscal,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_timbre b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep=".$distinct_timbre[$i][0]['cod_dep']." AND
								a.ano_orden_pago = ".$distinct_timbre[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_timbre[$i][0]['cod_tipo_pago']." AND
								a.porcentaje_timbre_fiscal = ".$distinct_timbre[$i][0]['porcentaje_timbre_fiscal']." AND
								a.monto_timbre_fiscal!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_dep, a.cod_tipo_pago, a.porcentaje_timbre_fiscal, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);

				}elseif($consolidacion == 2){
					$sql_distinct  = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, porcentaje_timbre_fiscal FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND monto_timbre_fiscal!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, porcentaje_timbre_fiscal;";
					$distinct_timbre = $this->cstd07_retenciones_cuerpo_islr->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_timbre); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.porcentaje_timbre_fiscal,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.monto_timbre_fiscal,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_timbre b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep='$cod_dep' AND
								a.ano_orden_pago = ".$distinct_timbre[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_timbre[$i][0]['cod_tipo_pago']." AND
								a.porcentaje_timbre_fiscal = ".$distinct_timbre[$i][0]['porcentaje_timbre_fiscal']." AND
								a.monto_timbre_fiscal!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_tipo_pago, a.porcentaje_timbre_fiscal, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);
				}
			}
		$this->set('var',$var);//no
		}
	}
}//reporte_cuadro_enterar_timbrefiscal


/* *
 * Reporte: Reporte_Cuadro_Enterar_multa
 * Funciones necesarias:
 * Descripcion:
 */
function reporte_cuadro_enterar_imp_municipal($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano = $this->ano_ejecucion();
			$this->set('ano_ejecucion',$ano);
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$ano = $this->data['cuadro_enterar_imp_municipal']['ano'];
			$ano == '' ? $ano = $this->ano_ejecucion() : '';
			$mes = $this->data['cuadro_enterar_imp_municipal']['mes'];
			$mes < 10 ? $mes='0'.$mes : '';
			$fecha_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
			$fecha_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));

			if($cod_dep!=1){
				$sql_distinct  = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, porcentaje_impuesto_municipal FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND monto_impuesto_municipal!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, porcentaje_impuesto_municipal;";
				$distinct_munic = $this->cstd07_retenciones_cuerpo_islr->execute($sql_distinct);
				$datos = array();
				for($i=0; $i<count($distinct_munic); $i++){
					$sql = "SELECT
							a.cod_tipo_pago,
							a.porcentaje_impuesto_municipal,
							a.ano_orden_pago,
							a.monto_descontar_impuesto,
							a.monto_impuesto_municipal,
							a.monto_sustraendo,
							a.numero_orden_pago,
							a.autorizado,
							b.ano_orden_pago,
							b.numero_orden_pago,
							b.numero_cheque,
							(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
							(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
							(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
							g.denominacion as dependencia,
							g.agente_retencion,
							g.fiscal_rentas,
							g.direccion,
							g.cod_area,
							g.telefonos,
							g.email,
							g.rif,
							g.nit
							FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_municipal b, cugd02_dependencias g WHERE
							a.cod_presi=b.cod_presi AND
							a.cod_entidad=b.cod_entidad AND
							a.cod_tipo_inst=b.cod_tipo_inst AND
							a.cod_inst=b.cod_inst AND
							a.cod_dep=b.cod_dep AND
							a.ano_orden_pago=b.ano_orden_pago AND
							a.numero_orden_pago=b.numero_orden_pago AND
							b.cod_tipo_inst=g.cod_tipo_institucion AND
							b.cod_inst=g.cod_institucion AND
							b.cod_dep=g.cod_dependencia AND
							a.cod_presi='$cod_presi' AND
							a.cod_entidad='$cod_entidad' AND
							a.cod_tipo_inst='$cod_tipo_inst' AND
							a.cod_inst='$cod_inst' AND
							a.cod_dep='$cod_dep' AND
							a.ano_orden_pago = ".$distinct_munic[$i][0]['ano_orden_pago']." AND
							a.cod_tipo_pago  = ".$distinct_munic[$i][0]['cod_tipo_pago']." AND
							a.porcentaje_impuesto_municipal = ".$distinct_munic[$i][0]['porcentaje_impuesto_municipal']." AND
							a.monto_impuesto_municipal!='0' AND
							a.cuenta_bancaria!='0' AND
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
							ORDER BY a.cod_tipo_pago, a.porcentaje_impuesto_municipal, b.numero_cheque, a.numero_orden_pago;";
					$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
				}
				$this->set('datos',$datos);
				$this->set('ano',$ano);
				$this->set('mes',$mes);


			}elseif($cod_dep==1){
				$consolidacion = $this->data['cuadro_enterar_imp_municipal']['consolidacion'];
				if($consolidacion == 1){
					$sql_distinct  = "SELECT DISTINCT cod_dep, ano_orden_pago, cod_tipo_pago, porcentaje_impuesto_municipal FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND ano_orden_pago='$ano' AND monto_impuesto_municipal!='0' AND cuenta_bancaria!='0' ORDER BY cod_dep, ano_orden_pago, cod_tipo_pago, porcentaje_impuesto_municipal;";
					$distinct_munic = $this->cstd07_retenciones_cuerpo_islr->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_munic); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.porcentaje_impuesto_municipal,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.monto_impuesto_municipal,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_municipal b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep=".$distinct_munic[$i][0]['cod_dep']." AND
								a.ano_orden_pago = ".$distinct_munic[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_munic[$i][0]['cod_tipo_pago']." AND
								a.porcentaje_impuesto_municipal = ".$distinct_munic[$i][0]['porcentaje_impuesto_municipal']." AND
								a.monto_impuesto_municipal!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_dep, a.cod_tipo_pago, a.porcentaje_impuesto_municipal, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);

				}elseif($consolidacion == 2){
					$sql_distinct  = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, porcentaje_impuesto_municipal FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND monto_impuesto_municipal!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, porcentaje_impuesto_municipal;";
					$distinct_munic = $this->cstd07_retenciones_cuerpo_islr->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_munic); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.porcentaje_impuesto_municipal,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.monto_impuesto_municipal,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_municipal b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep='$cod_dep' AND
								a.ano_orden_pago = ".$distinct_munic[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_munic[$i][0]['cod_tipo_pago']." AND
								a.porcentaje_impuesto_municipal = ".$distinct_munic[$i][0]['porcentaje_impuesto_municipal']." AND
								a.monto_impuesto_municipal!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_tipo_pago, a.porcentaje_islr, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);
				}
			}
		$this->set('var',$var);//no
		}
	}
}//reporte_cuadro_enterar_imp_municipal






















function reporte_cuadro_enterar_multa($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano = $this->ano_ejecucion();
			$this->set('ano_ejecucion',$ano);
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.

			$this->layout = "pdf";

			$cod_presi     = $this->Session->read('SScodpresi');
		    $cod_entidad   = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst      = $this->Session->read('SScodinst');
			$cod_dep       = $this->Session->read('SScoddep');

			$ano = $this->data['cuadro_enterar_multa']['ano'];
			$ano == '' ? $ano = $this->ano_ejecucion() : '';
			$mes = $this->data['cuadro_enterar_multa']['mes'];
			$mes < 10 ? $mes='0'.$mes : '';
			$fecha_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
			$fecha_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));

			if($cod_dep!=1){
				$sql_distinct  = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, retencion_multa FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND retencion_multa!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, retencion_multa;";
				$distinct_multa = $this->cstd07_retenciones_cuerpo_multa->execute($sql_distinct);
				$datos = array();
				for($i=0; $i<count($distinct_multa); $i++){
					$sql = "SELECT
							a.cod_tipo_pago,
							a.retencion_multa,
							a.ano_orden_pago,
							a.monto_descontar_impuesto,
							a.retencion_multa,
							a.monto_sustraendo,
							a.numero_orden_pago,
							a.autorizado,
							b.ano_orden_pago,
							b.numero_orden_pago,
							b.numero_cheque,
							(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
							(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e  WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c       WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
							(SELECT d.situacion    FROM cstd03_cheque_numero d       WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
							g.denominacion as dependencia,
							g.agente_retencion,
							g.fiscal_rentas,
							g.direccion,
							g.cod_area,
							g.telefonos,
							g.email,
							g.rif,
							g.nit
							FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_multa b, cugd02_dependencias g WHERE
							a.cod_presi=b.cod_presi AND
							a.cod_entidad=b.cod_entidad AND
							a.cod_tipo_inst=b.cod_tipo_inst AND
							a.cod_inst=b.cod_inst AND
							a.cod_dep=b.cod_dep AND
							a.ano_orden_pago=b.ano_orden_pago AND
							a.numero_orden_pago=b.numero_orden_pago AND
							b.cod_tipo_inst=g.cod_tipo_institucion AND
							b.cod_inst=g.cod_institucion AND
							b.cod_dep=g.cod_dependencia AND
							a.cod_presi='$cod_presi' AND
							a.cod_entidad='$cod_entidad' AND
							a.cod_tipo_inst='$cod_tipo_inst' AND
							a.cod_inst='$cod_inst' AND
							a.cod_dep='$cod_dep' AND
							a.ano_orden_pago = ".$distinct_multa[$i][0]['ano_orden_pago']." AND
							a.cod_tipo_pago  = ".$distinct_multa[$i][0]['cod_tipo_pago']." AND
							a.retencion_multa = ".$distinct_multa[$i][0]['retencion_multa']." AND
							a.retencion_multa!='0' AND
							a.cuenta_bancaria!='0' AND
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
							ORDER BY a.cod_tipo_pago, a.retencion_multa, b.numero_cheque, a.numero_orden_pago;";
					$datos[] = $this->cstd07_retenciones_cuerpo_multa->execute($sql);
				}
				$this->set('datos',$datos);
				$this->set('ano',$ano);
				$this->set('mes',$mes);


			}elseif($cod_dep==1){
				$consolidacion = $this->data['cuadro_enterar_multa']['consolidacion'];
				if($consolidacion == 1){
					$sql_distinct  = "SELECT DISTINCT cod_dep, ano_orden_pago, cod_tipo_pago, retencion_multa FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND ano_orden_pago='$ano' AND retencion_multa!='0' AND cuenta_bancaria!='0' ORDER BY cod_dep, ano_orden_pago, cod_tipo_pago, retencion_multa;";
					$distinct_multa = $this->cstd07_retenciones_cuerpo_multa->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_multa); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.retencion_multa,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.retencion_multa,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_multa b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep=".$distinct_multa[$i][0]['cod_dep']." AND
								a.ano_orden_pago = ".$distinct_multa[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_multa[$i][0]['cod_tipo_pago']." AND
								a.retencion_multa = ".$distinct_multa[$i][0]['retencion_multa']." AND
								a.retencion_multa!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_dep, a.cod_tipo_pago, a.retencion_multa, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_multa->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);

				}elseif($consolidacion == 2){
					$sql_distinct   = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, retencion_multa FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND retencion_multa!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, retencion_multa;";
					$distinct_multa = $this->cstd07_retenciones_cuerpo_multa->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_multa); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.retencion_multa,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.retencion_multa,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_multa b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep='$cod_dep' AND
								a.ano_orden_pago = ".$distinct_multa[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_multa[$i][0]['cod_tipo_pago']." AND
								a.retencion_multa = ".$distinct_multa[$i][0]['retencion_multa']." AND
								a.retencion_multa!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_tipo_pago, a.porcentaje_islr, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_multa->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);
				}
			}
		$this->set('var',$var);//no
		}
	}
}//reporte_cuadro_enterar_multa







function reporte_cuadro_enterar_iva($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano = $this->ano_ejecucion();
			$this->set('ano_ejecucion',$ano);
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.

			$this->layout = "pdf";

			$cod_presi     = $this->Session->read('SScodpresi');
		    $cod_entidad   = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst      = $this->Session->read('SScodinst');
			$cod_dep       = $this->Session->read('SScoddep');

			$ano = $this->data['cuadro_enterar_iva']['ano'];
			$ano == '' ? $ano = $this->ano_ejecucion() : '';
			$mes = $this->data['cuadro_enterar_iva']['mes'];
			$mes < 10 ? $mes='0'.$mes : '';
			$fecha_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
			$fecha_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));

			if($cod_dep!=1){
				$sql_distinct  = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, monto_iva FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND monto_iva!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, monto_iva;";
				$distinct_iva = $this->cstd07_retenciones_cuerpo_iva->execute($sql_distinct);
				$datos = array();
				for($i=0; $i<count($distinct_iva); $i++){
					$sql = "SELECT
							a.cod_tipo_pago,
							a.porcentaje_iva,
							a.ano_orden_pago,
							a.monto_descontar_impuesto,
							a.monto_iva, a.monto_retencion_iva, a.porcentaje_retencion_iva,
							a.monto_sustraendo,
							a.numero_orden_pago,
							a.autorizado,
							b.ano_orden_pago,
							b.numero_orden_pago,
							b.numero_cheque,
							(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
							(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e  WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c       WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
							(SELECT d.situacion    FROM cstd03_cheque_numero d       WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
							g.denominacion as dependencia,
							g.agente_retencion,
							g.fiscal_rentas,
							g.direccion,
							g.cod_area,
							g.telefonos,
							g.email,
							g.rif,
							g.nit
							FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_iva b, cugd02_dependencias g WHERE
							a.cod_presi=b.cod_presi AND
							a.cod_entidad=b.cod_entidad AND
							a.cod_tipo_inst=b.cod_tipo_inst AND
							a.cod_inst=b.cod_inst AND
							a.cod_dep=b.cod_dep AND
							a.ano_orden_pago=b.ano_orden_pago AND
							a.numero_orden_pago=b.numero_orden_pago AND
							b.cod_tipo_inst=g.cod_tipo_institucion AND
							b.cod_inst=g.cod_institucion AND
							b.cod_dep=g.cod_dependencia AND
							a.cod_presi='$cod_presi' AND
							a.cod_entidad='$cod_entidad' AND
							a.cod_tipo_inst='$cod_tipo_inst' AND
							a.cod_inst='$cod_inst' AND
							a.cod_dep='$cod_dep' AND
							a.ano_orden_pago = ".$distinct_iva[$i][0]['ano_orden_pago']." AND
							a.cod_tipo_pago  = ".$distinct_iva[$i][0]['cod_tipo_pago']." AND
							a.monto_iva = ".$distinct_iva[$i][0]['monto_iva']." AND
							a.monto_iva!='0' AND
							a.cuenta_bancaria!='0' AND
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
							ORDER BY a.cod_tipo_pago, a.monto_iva, b.numero_cheque, a.numero_orden_pago;";
					$datos[] = $this->cstd07_retenciones_cuerpo_iva->execute($sql);
				}
				$this->set('datos',$datos);
				$this->set('ano',$ano);
				$this->set('mes',$mes);


			}elseif($cod_dep==1){
				$consolidacion = $this->data['cuadro_enterar_iva']['consolidacion'];
				if($consolidacion == 1){
					$sql_distinct  = "SELECT DISTINCT cod_dep, ano_orden_pago, cod_tipo_pago, monto_iva FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND ano_orden_pago='$ano' AND monto_iva!='0' AND cuenta_bancaria!='0' ORDER BY cod_dep, ano_orden_pago, cod_tipo_pago, monto_iva;";
					$distinct_iva = $this->cstd07_retenciones_cuerpo_iva->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_iva); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.porcentaje_iva,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.monto_iva, a.monto_retencion_iva, a.porcentaje_retencion_iva,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_iva b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep=".$distinct_iva[$i][0]['cod_dep']." AND
								a.ano_orden_pago = ".$distinct_iva[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_iva[$i][0]['cod_tipo_pago']." AND
								a.monto_iva = ".$distinct_iva[$i][0]['monto_iva']." AND
								a.monto_iva!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_dep, a.cod_tipo_pago, a.monto_iva, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_iva->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);

				}elseif($consolidacion == 2){
					$sql_distinct   = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, monto_iva FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND monto_iva!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, monto_iva;";
					$distinct_iva = $this->cstd07_retenciones_cuerpo_iva->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_iva); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.porcentaje_iva,
								a.ano_orden_pago,
								a.monto_descontar_impuesto, a.porcentaje_retencion_iva,
								a.monto_iva,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								a.monto_retencion_iva,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_iva b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep='$cod_dep' AND
								a.ano_orden_pago = ".$distinct_iva[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_iva[$i][0]['cod_tipo_pago']." AND
								a.monto_iva = ".$distinct_iva[$i][0]['monto_iva']." AND
								a.monto_iva!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_tipo_pago, a.porcentaje_islr, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_iva->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);
				}
			}
		$this->set('var',$var);//no
		}
	}
}//reporte_cuadro_enterar_iva




















function reporte_cuadro_enterar_responsabilidad($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano = $this->ano_ejecucion();
			$this->set('ano_ejecucion',$ano);
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.

			$this->layout = "pdf";

			$cod_presi     = $this->Session->read('SScodpresi');
		    $cod_entidad   = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst      = $this->Session->read('SScodinst');
			$cod_dep       = $this->Session->read('SScoddep');

			$ano = $this->data['cuadro_enterar_responsabilidad']['ano'];
			$ano == '' ? $ano = $this->ano_ejecucion() : '';
			$mes = $this->data['cuadro_enterar_responsabilidad']['mes'];
			$mes < 10 ? $mes='0'.$mes : '';
			$fecha_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
			$fecha_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));

			if($cod_dep!=1){
				$sql_distinct  = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, retencion_responsabilidad FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND retencion_responsabilidad!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, retencion_responsabilidad;";
				$distinct_responsabilidad = $this->cstd07_retenciones_cuerpo_responsabilidad->execute($sql_distinct);
				$datos = array();
				for($i=0; $i<count($distinct_responsabilidad); $i++){
					$sql = "SELECT
							a.cod_tipo_pago,
							a.retencion_responsabilidad,
							a.ano_orden_pago,
							a.monto_descontar_impuesto,
							a.retencion_responsabilidad,
							a.monto_sustraendo,
							a.numero_orden_pago,
							a.autorizado,
							b.ano_orden_pago,
							b.numero_orden_pago,
							b.numero_cheque,
							(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
							(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e  WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c       WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
							(SELECT d.situacion    FROM cstd03_cheque_numero d       WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
							g.denominacion as dependencia,
							g.agente_retencion,
							g.fiscal_rentas,
							g.direccion,
							g.cod_area,
							g.telefonos,
							g.email,
							g.rif,
							g.nit
							FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_responsabilidad b, cugd02_dependencias g WHERE
							a.cod_presi=b.cod_presi AND
							a.cod_entidad=b.cod_entidad AND
							a.cod_tipo_inst=b.cod_tipo_inst AND
							a.cod_inst=b.cod_inst AND
							a.cod_dep=b.cod_dep AND
							a.ano_orden_pago=b.ano_orden_pago AND
							a.numero_orden_pago=b.numero_orden_pago AND
							b.cod_tipo_inst=g.cod_tipo_institucion AND
							b.cod_inst=g.cod_institucion AND
							b.cod_dep=g.cod_dependencia AND
							a.cod_presi='$cod_presi' AND
							a.cod_entidad='$cod_entidad' AND
							a.cod_tipo_inst='$cod_tipo_inst' AND
							a.cod_inst='$cod_inst' AND
							a.cod_dep='$cod_dep' AND
							a.ano_orden_pago = ".$distinct_responsabilidad[$i][0]['ano_orden_pago']." AND
							a.cod_tipo_pago  = ".$distinct_responsabilidad[$i][0]['cod_tipo_pago']." AND
							a.retencion_responsabilidad = ".$distinct_responsabilidad[$i][0]['retencion_responsabilidad']." AND
							a.retencion_responsabilidad!='0' AND
							a.cuenta_bancaria!='0' AND
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
							ORDER BY a.cod_tipo_pago, a.retencion_responsabilidad, b.numero_cheque, a.numero_orden_pago;";
					$datos[] = $this->cstd07_retenciones_cuerpo_responsabilidad->execute($sql);
				}
				$this->set('datos',$datos);
				$this->set('ano',$ano);
				$this->set('mes',$mes);


			}elseif($cod_dep==1){
				$consolidacion = $this->data['cuadro_enterar_responsabilidad']['consolidacion'];
				if($consolidacion == 1){
					$sql_distinct  = "SELECT DISTINCT cod_dep, ano_orden_pago, cod_tipo_pago, retencion_responsabilidad FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND ano_orden_pago='$ano' AND retencion_responsabilidad!='0' AND cuenta_bancaria!='0' ORDER BY cod_dep, ano_orden_pago, cod_tipo_pago, retencion_responsabilidad;";
					$distinct_responsabilidad = $this->cstd07_retenciones_cuerpo_responsabilidad->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_responsabilidad); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.retencion_responsabilidad,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.retencion_responsabilidad,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_responsabilidad b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep=".$distinct_responsabilidad[$i][0]['cod_dep']." AND
								a.ano_orden_pago = ".$distinct_responsabilidad[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_responsabilidad[$i][0]['cod_tipo_pago']." AND
								a.retencion_responsabilidad = ".$distinct_responsabilidad[$i][0]['retencion_responsabilidad']." AND
								a.retencion_responsabilidad!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_dep, a.cod_tipo_pago, a.retencion_responsabilidad, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_responsabilidad->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);

				}elseif($consolidacion == 2){
					$sql_distinct   = "SELECT DISTINCT ano_orden_pago, cod_tipo_pago, retencion_responsabilidad FROM cepd03_ordenpago_cuerpo WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano_orden_pago='$ano' AND retencion_responsabilidad!='0' AND cuenta_bancaria!='0' ORDER BY ano_orden_pago, cod_tipo_pago, retencion_responsabilidad;";
					$distinct_responsabilidad = $this->cstd07_retenciones_cuerpo_responsabilidad->execute($sql_distinct);
					$datos = array();
					for($i=0; $i<count($distinct_responsabilidad); $i++){
						$sql = "SELECT
								a.cod_tipo_pago,
								a.retencion_responsabilidad,
								a.ano_orden_pago,
								a.monto_descontar_impuesto,
								a.retencion_responsabilidad,
								a.monto_sustraendo,
								a.numero_orden_pago,
								a.autorizado,
								b.ano_orden_pago,
								b.numero_orden_pago,
								b.numero_cheque,
								(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
								(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
								(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
								g.denominacion as dependencia,
								g.agente_retencion,
								g.fiscal_rentas,
								g.direccion,
								g.cod_area,
								g.telefonos,
								g.email,
								g.rif,
								g.nit
								FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_responsabilidad b, cugd02_dependencias g WHERE
								a.cod_presi=b.cod_presi AND
								a.cod_entidad=b.cod_entidad AND
								a.cod_tipo_inst=b.cod_tipo_inst AND
								a.cod_inst=b.cod_inst AND
								a.cod_dep=b.cod_dep AND
								a.ano_orden_pago=b.ano_orden_pago AND
								a.numero_orden_pago=b.numero_orden_pago AND
								b.cod_tipo_inst=g.cod_tipo_institucion AND
								b.cod_inst=g.cod_institucion AND
								b.cod_dep=g.cod_dependencia AND
								a.cod_presi='$cod_presi' AND
								a.cod_entidad='$cod_entidad' AND
								a.cod_tipo_inst='$cod_tipo_inst' AND
								a.cod_inst='$cod_inst' AND
								a.cod_dep='$cod_dep' AND
								a.ano_orden_pago = ".$distinct_responsabilidad[$i][0]['ano_orden_pago']." AND
								a.cod_tipo_pago  = ".$distinct_responsabilidad[$i][0]['cod_tipo_pago']." AND
								a.retencion_responsabilidad = ".$distinct_responsabilidad[$i][0]['retencion_responsabilidad']." AND
								a.retencion_responsabilidad!='0' AND
								a.cuenta_bancaria!='0' AND
								(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '$fecha_inicial' AND '$fecha_final'
								ORDER BY a.cod_tipo_pago, a.porcentaje_islr, b.numero_cheque, a.numero_orden_pago;";
						$datos[] = $this->cstd07_retenciones_cuerpo_responsabilidad->execute($sql);
					}
					$this->set('datos',$datos);
					$this->set('ano',$ano);
					$this->set('mes',$mes);
				}
			}
		$this->set('var',$var);//no
		}
	}
}//reporte_cuadro_enterar_responsabilidad

















function reporte_cuadro_demostrativo_anual_islr_enterado_seniat($var=null){
	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$ano = $this->ano_ejecucion();
			$cod_dep==1 ? $this->Session->write('consolidacion_dep_reporte_3',1) : $this->Session->write('consolidacion_dep_reporte_3',2);
			$this->Session->write('session_ano_generico',$ano);
			$this->set('ano_ejecucion',$ano);
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			/*echo "<br>ano: ".$ano = $this->Session->read('session_ano_generico');
			$ano == '' ? $ano = $this->ano_ejecucion() : '';
			echo "<br>consolidacion: ".$consolidacion  = $this->Session->read('consolidacion_dep_reporte_3');
			echo "<br>radio_empresas: ".$radio_empresas = $this->data['cuadro_demostrativo_anual_islr_seniat']['radio_empresa_especifica'];
			echo "<br>rif_empresa: ".$rif_empresa    = $this->data['cuadro_demostrativo_anual_islr_seniat']['empresas'];*/

			$ano = $this->Session->read('session_ano_generico');
			$ano == '' ? $ano = $this->ano_ejecucion() : '';
			$consolidacion  = $this->Session->read('consolidacion_dep_reporte_3');
			$radio_empresas = $this->data['cuadro_demostrativo_anual_islr_seniat']['radio_empresa_especifica'];
			$rif_empresa    = $this->data['cuadro_demostrativo_anual_islr_seniat']['empresas'];

			if($consolidacion == 1){
				if($radio_empresas == 1){
					/*$sql_distinct = "SELECT DISTINCT a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr, a.rif FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_orden_pago='$ano' AND a.monto_islr!='0' AND a.cuenta_bancaria!='0'
									 	and
										a.cod_presi=b.cod_presi AND
										a.cod_entidad=b.cod_entidad AND
										a.cod_tipo_inst=b.cod_tipo_inst AND
										a.cod_inst=b.cod_inst AND
										a.cod_dep=b.cod_dep AND
										a.ano_orden_pago=b.ano_orden_pago AND
										a.numero_orden_pago=b.numero_orden_pago AND
										b.numero_cheque!='0'
										ORDER BY a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr;";
					*/

					$sql_distinct = "SELECT DISTINCT a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_orden_pago='$ano' AND a.monto_islr!='0' AND a.cuenta_bancaria!='0'
									 	and
										a.cod_presi=b.cod_presi AND
										a.cod_entidad=b.cod_entidad AND
										a.cod_tipo_inst=b.cod_tipo_inst AND
										a.cod_inst=b.cod_inst AND
										a.cod_dep=b.cod_dep AND
										a.ano_orden_pago=b.ano_orden_pago AND
										a.numero_orden_pago=b.numero_orden_pago AND
										b.numero_cheque!='0'
										ORDER BY a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr;";
				}else{
					//No se coloca la condicion de igualdad porque el registro que se desea buscar ya deberia venir filtrado por los que tienen pago de ISLR.
					$sql_distinct = "SELECT DISTINCT a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr FROM cepd03_ordenpago_cuerpo a WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_orden_pago='$ano' AND a.monto_islr!='0' AND a.cuenta_bancaria!='0' AND UPPER(a.rif)='$rif_empresa' ORDER BY a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr;";
				}


			}elseif($consolidacion == 2){
				if($radio_empresas == 1){
					/*$sql_distinct = "SELECT DISTINCT a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr, a.rif FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.cod_dep='$cod_dep' AND a.ano_orden_pago='$ano' AND a.monto_islr!='0' AND a.cuenta_bancaria!='0'
										and
										a.cod_presi=b.cod_presi AND
										a.cod_entidad=b.cod_entidad AND
										a.cod_tipo_inst=b.cod_tipo_inst AND
										a.cod_inst=b.cod_inst AND
										a.cod_dep=b.cod_dep AND
										a.ano_orden_pago=b.ano_orden_pago AND
										a.numero_orden_pago=b.numero_orden_pago AND
										b.numero_cheque!='0'
										ORDER BY a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr, a.rif;";
					*/

					$sql_distinct = "SELECT DISTINCT a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.cod_dep='$cod_dep' AND a.ano_orden_pago='$ano' AND a.monto_islr!='0' AND a.cuenta_bancaria!='0'
										and
										a.cod_presi=b.cod_presi AND
										a.cod_entidad=b.cod_entidad AND
										a.cod_tipo_inst=b.cod_tipo_inst AND
										a.cod_inst=b.cod_inst AND
										a.cod_dep=b.cod_dep AND
										a.ano_orden_pago=b.ano_orden_pago AND
										a.numero_orden_pago=b.numero_orden_pago AND
										b.numero_cheque!='0'
										ORDER BY a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr;";
				}else{
					//No se coloca la condicion de igualdad porque el registro que se desea buscar ya deberia venir filtrado por los que tienen pago de ISLR.
					$sql_distinct = "SELECT DISTINCT a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr, a.rif FROM cepd03_ordenpago_cuerpo a WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.cod_dep='$cod_dep' AND a.ano_orden_pago='$ano' AND a.monto_islr!='0' AND a.cuenta_bancaria!='0' AND UPPER(a.rif)='$rif_empresa' ORDER BY a.cod_dep, a.ano_orden_pago, a.cod_tipo_pago, a.porcentaje_islr;";
				}

			}
			$distinct_islr = $this->cepd03_ordenpago_cuerpo->execute($sql_distinct);
			$datos = array();

			if($radio_empresas == 1){// Condicion para saber como buscar, si por rif o todo.
				for($i=0; $i<count($distinct_islr); $i++){
					$sql = "SELECT
							a.cod_presi,
						    a.cod_entidad,
						    a.cod_tipo_inst,
						    a.cod_inst,
						    a.cod_dep,
							a.cod_tipo_pago,
							a.porcentaje_islr,
							a.ano_orden_pago,
							a.numero_orden_pago,
							a.monto_descontar_impuesto,
							a.monto_islr,
							a.monto_sustraendo,
							a.numero_orden_pago,
							a.autorizado,
							b.numero_cheque,
							(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
							(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
							(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
							g.denominacion as dependencia,
							g.agente_retencion,
							g.fiscal_rentas,
							g.direccion,
							g.cod_area,
							g.telefonos,
							g.email,
							g.rif,
							g.nit
							FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cugd02_dependencias g WHERE
							a.cod_presi=b.cod_presi AND
							a.cod_entidad=b.cod_entidad AND
							a.cod_tipo_inst=b.cod_tipo_inst AND
							a.cod_inst=b.cod_inst AND
							a.cod_dep=b.cod_dep AND
							a.ano_orden_pago=b.ano_orden_pago AND
							a.numero_orden_pago=b.numero_orden_pago AND
							b.cod_tipo_inst=g.cod_tipo_institucion AND
							b.cod_inst=g.cod_institucion AND
							b.cod_dep=g.cod_dependencia AND
							a.cod_presi='$cod_presi' AND
							a.cod_entidad='$cod_entidad' AND
							a.cod_tipo_inst='$cod_tipo_inst' AND
							a.cod_inst='$cod_inst' AND
							a.cod_dep=".$distinct_islr[$i][0]['cod_dep']." AND
							a.ano_orden_pago = ".$distinct_islr[$i][0]['ano_orden_pago']." AND
							a.cod_tipo_pago  = ".$distinct_islr[$i][0]['cod_tipo_pago']." AND
							a.porcentaje_islr = ".$distinct_islr[$i][0]['porcentaje_islr']." AND
							a.monto_islr!='0' AND
							a.cuenta_bancaria!='0' AND
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '01-01-$ano' AND '31-12-$ano'
							ORDER BY a.cod_dep, fecha_cheque, a.cod_tipo_pago, a.porcentaje_islr, b.numero_cheque, a.numero_orden_pago;";
					$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
				}

			}else{//viene un rif especifico

				for($i=0; $i<count($distinct_islr); $i++){
					$rif =$distinct_islr[$i][0]['rif'];
					$sql = "SELECT
							a.cod_presi,
						    a.cod_entidad,
						    a.cod_tipo_inst,
						    a.cod_inst,
						    a.cod_dep,
							a.cod_tipo_pago,
							a.porcentaje_islr,
							a.ano_orden_pago,
							a.numero_orden_pago,
							a.monto_descontar_impuesto,
							a.monto_islr,
							a.monto_sustraendo,
							a.numero_orden_pago,
							a.autorizado,
							b.numero_cheque,
							(SELECT f.denominacion FROM cstd01_entidades_bancarias f WHERE b.cod_entidad_bancaria=f.cod_entidad_bancaria) AS entidad_bancaria,
							(SELECT e.denominacion FROM cepd03_ordenpago_tipopago e WHERE a.cod_tipo_pago=e.cod_tipo_pago) AS tipo_pago,
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) AS fecha_cheque,
							(SELECT d.situacion FROM cstd03_cheque_numero d WHERE b.cod_presi=d.cod_presi AND b.cod_entidad=d.cod_entidad AND b.cod_tipo_inst=d.cod_tipo_inst AND b.cod_inst=d.cod_inst AND b.cod_dep=d.cod_dep AND b.cod_entidad_bancaria=d.cod_entidad_bancaria AND b.cod_sucursal=d.cod_sucursal AND b.cuenta_bancaria=d.cuenta_bancaria AND b.numero_cheque=d.numero_cheque) AS situacion_cheque,
							g.denominacion as dependencia,
							g.agente_retencion,
							g.fiscal_rentas,
							g.direccion,
							g.cod_area,
							g.telefonos,
							g.email,
							g.rif,
							g.nit
							FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cugd02_dependencias g WHERE
							a.cod_presi=b.cod_presi AND
							a.cod_entidad=b.cod_entidad AND
							a.cod_tipo_inst=b.cod_tipo_inst AND
							a.cod_inst=b.cod_inst AND
							a.cod_dep=b.cod_dep AND
							a.ano_orden_pago=b.ano_orden_pago AND
							a.numero_orden_pago=b.numero_orden_pago AND
							b.cod_tipo_inst=g.cod_tipo_institucion AND
							b.cod_inst=g.cod_institucion AND
							b.cod_dep=g.cod_dependencia AND
							a.cod_presi='$cod_presi' AND
							a.cod_entidad='$cod_entidad' AND
							a.cod_tipo_inst='$cod_tipo_inst' AND
							a.cod_inst='$cod_inst' AND
							a.cod_dep=".$distinct_islr[$i][0]['cod_dep']." AND
							a.ano_orden_pago = ".$distinct_islr[$i][0]['ano_orden_pago']." AND
							a.cod_tipo_pago  = ".$distinct_islr[$i][0]['cod_tipo_pago']." AND
							a.porcentaje_islr = ".$distinct_islr[$i][0]['porcentaje_islr']." AND
							a.monto_islr!='0' AND
							a.cuenta_bancaria!='0' AND
							UPPER(a.rif)='$rif' AND
							(SELECT c.fecha_cheque FROM cstd03_cheque_cuerpo c WHERE b.cod_presi=c.cod_presi AND b.cod_entidad=c.cod_entidad AND b.cod_tipo_inst=c.cod_tipo_inst AND b.cod_inst=c.cod_inst AND b.cod_dep=c.cod_dep AND b.ano_movimiento=c.ano_movimiento AND b.cod_entidad_bancaria=c.cod_entidad_bancaria AND b.cod_sucursal=c.cod_sucursal AND b.cuenta_bancaria=c.cuenta_bancaria AND b.numero_cheque=c.numero_cheque) BETWEEN '01-01-$ano' AND '31-12-$ano'
							ORDER BY a.cod_dep, fecha_cheque, a.cod_tipo_pago, a.porcentaje_islr, b.numero_cheque, a.numero_orden_pago;";
					$datos[] = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
				}
			}



			for($i=0; $i<count($datos); $i++){
				$total=count($datos[$i]);
				$k=0;
				for($j=0; $j<$total; $j++){
					for($k=0; $k<count($datos[$i][$j]); $k++){
						$sql_fac="SELECT * FROM cepd03_ordenpago_facturas WHERE cod_presi=".$datos[$i][$j][0]['cod_presi']." AND cod_entidad=".$datos[$i][$j][0]['cod_entidad']." AND cod_tipo_inst=".$datos[$i][$j][0]['cod_tipo_inst']." AND cod_inst=".$datos[$i][$j][0]['cod_inst']." AND cod_dep=".$datos[$i][$j][0]['cod_dep']." AND ano_orden_pago=".$datos[$i][$j][0]['ano_orden_pago']." AND numero_orden_pago=".$datos[$i][$j][0]['numero_orden_pago']." ORDER BY numero_orden_pago";
						$datos_fac[$i][$j][$k] = $this->cstd07_retenciones_cuerpo_islr->execute($sql_fac);
					}
				}
			}
			$this->set('datos',$datos);
			$this->set('datos_fac',$datos_fac);
			$this->set('ano',$ano);
		$this->set('var',$var);//no
		}
	}//fin @var==null
}//reporte_cuadro_demostrativo_anual_islr_enterado_seniat


function consolidacion_dependencia($var=null){
	$this->layout="ajax";
	$this->Session->write('consolidacion_dep_reporte_3',$var);
}


function select_empresas_ordenpago($var=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano = $this->Session->read('session_ano_generico');
	$consolidacion = $this->Session->read('consolidacion_dep_reporte_3');

	if($var==2){//@var: indica el tipo de selec, si es 2, se listan porque indica una especifica, si es 1 no no se busca porque se quieren todas las empresas.
		if($consolidacion==1){
			$cond = "a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.ano_orden_pago='$ano'
					and
					a.cod_presi=b.cod_presi AND
					a.cod_entidad=b.cod_entidad AND
					a.cod_tipo_inst=b.cod_tipo_inst AND
					a.cod_inst=b.cod_inst AND
					a.cod_dep=b.cod_dep AND
					a.ano_orden_pago=b.ano_orden_pago AND
					a.numero_orden_pago=b.numero_orden_pago AND
					b.numero_cheque!='0'";

		}else{
			$cond = "a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.ano_orden_pago='$ano'
					and
					a.cod_presi=b.cod_presi AND
					a.cod_entidad=b.cod_entidad AND
					a.cod_tipo_inst=b.cod_tipo_inst AND
					a.cod_inst=b.cod_inst AND
					a.cod_dep=b.cod_dep AND
					a.ano_orden_pago=b.ano_orden_pago AND
					a.numero_orden_pago=b.numero_orden_pago AND
					b.numero_cheque!='0'";
		}

		$distinct_empresas = $this->cepd03_ordenpago_cuerpo->execute("SELECT DISTINCT a.rif, a.beneficiario FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b WHERE $cond ORDER BY rif");
		if(count($distinct_empresas)==0){
			$this->set('empresas', array('0'=>'NO SE ENCONTRARON REGISTROS'));
		}else{
			for($i=0; $i<count($distinct_empresas); $i++){
			$rif[] = $distinct_empresas[$i][0]['rif'];
			$deno[] = $distinct_empresas[$i][0]['rif']." - ".$distinct_empresas[$i][0]['beneficiario'];
			}
			$empresas = array_combine($rif, $deno);
			$this->set('empresas',$empresas);
		}
		$this->set('vacio',false);
	}else{
		$this->set('vacio',true);
	}
}//select_empresas_ordenpago


function buscar_empresa_ordenpago($var=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cod_dep = $this->Session->read('SScoddep');
	$ano = $this->Session->read('session_ano_generico');

	if($cod_dep==1){
		//$cond = "cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and ano_orden_pago='$ano' and beneficiario like '%".strtoupper($var)."%'";
		$cond = "a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.ano_orden_pago='$ano' and beneficiario like '%".strtoupper($var)."%'
					and
					a.cod_presi=b.cod_presi AND
					a.cod_entidad=b.cod_entidad AND
					a.cod_tipo_inst=b.cod_tipo_inst AND
					a.cod_inst=b.cod_inst AND
					a.cod_dep=b.cod_dep AND
					a.ano_orden_pago=b.ano_orden_pago AND
					a.numero_orden_pago=b.numero_orden_pago AND
					b.numero_cheque!='0'";
	}else{
		//$cond = "cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and ano_orden_pago='$ano' and beneficiario like '%".strtoupper($var)."%'";
		$cond = "a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.ano_orden_pago='$ano' and beneficiario like '%".strtoupper($var)."%'
					and
					a.cod_presi=b.cod_presi AND
					a.cod_entidad=b.cod_entidad AND
					a.cod_tipo_inst=b.cod_tipo_inst AND
					a.cod_inst=b.cod_inst AND
					a.cod_dep=b.cod_dep AND
					a.ano_orden_pago=b.ano_orden_pago AND
					a.numero_orden_pago=b.numero_orden_pago AND
					b.numero_cheque!='0'";
	}
	$distinct_empresas = $this->cepd03_ordenpago_cuerpo->execute("SELECT DISTINCT rif, beneficiario FROM cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b WHERE $cond ORDER BY rif");
	if(count($distinct_empresas)==0){
		$this->set('empresas', array('0'=>'NO SE ENCONTRARON REGISTROS'));
		$this->set('vacio',true);
	}else{
		for($i=0; $i<count($distinct_empresas); $i++){
		$rif[] = $distinct_empresas[$i][0]['rif'];
		$deno[] = $distinct_empresas[$i][0]['rif']." - ".$distinct_empresas[$i][0]['beneficiario'];
		}
		$empresas = array_combine($rif, $deno);
		$this->set('empresas',$empresas);
	}
	$this->set('vacio',false);
}


function session_ano_generico($var=null){
	$this->layout="ajax";
	echo "Ano session: ".$var;
	$this->Session->write('session_ano_generico',$var);
}

function reporte_cuadro_rendicion_anual_seniat_islr($var=null){
	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano = $this->ano_ejecucion();
			$this->set('ano_ejecucion',$ano);
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$cod_dep == 1 ? $consolidacion = $this->data['cuadro_rendicion_anual_seniat_islr']['consolidacion'] : $consolidacion = 2;
			$ano = $this->data['cuadro_rendicion_anual_seniat_islr']['ano'];
			$ano == '' ? $ano = $this->ano_ejecucion() : '';

			if($consolidacion==1){
			$cond = "a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND ";
			}else{
			$cond = "a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.cod_dep='$cod_dep' AND ";
			}

			// Para buscar solo las personas NO JURIDICAS.
			$sql_1 = "SELECT
					a.cod_dep,
					a.cod_tipo_pago,
					a.porcentaje_islr,
					a.ano_orden_pago,
					(SELECT tp.denominacion FROM cepd03_ordenpago_tipopago tp WHERE tp.cod_tipo_pago=a.cod_tipo_pago) as tipo_pago,
					devolver_denominacion_beneficiario_op(a.rif, a.cedula_identidad::text) as autorizado,
					d.denominacion as dependencia,
					d.agente_retencion,
					d.fiscal_rentas,
					d.direccion,
					d.cod_area,
					d.telefonos,
					d.email,
					d.rif as rif_dependencia,
					d.nit as nit_dependencia,
					a.cedula_identidad,
					a.rif as rif_orden_pago,
					COUNT(*) as cantidad_retenciones,
					SUM(c.monto_sub_total) as monto_base,
					SUM(a.monto_islr) as impuestos
					FROM
					cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cepd03_ordenpago_facturas c, cugd02_dependencias d
					WHERE
					a.cod_presi=b.cod_presi AND
					a.cod_entidad=b.cod_entidad AND
					a.cod_tipo_inst=b.cod_tipo_inst AND
					a.cod_inst=b.cod_inst AND
					a.cod_dep=b.cod_dep AND
					a.ano_orden_pago=b.ano_orden_pago AND
					a.numero_orden_pago=b.numero_orden_pago AND
					a.cod_presi=c.cod_presi AND
					a.cod_entidad=c.cod_entidad AND
					a.cod_tipo_inst=c.cod_tipo_inst AND
					a.cod_inst=c.cod_inst AND
					a.cod_dep=c.cod_dep AND
					a.ano_orden_pago=c.ano_orden_pago AND
					a.numero_orden_pago=c.numero_orden_pago AND
					a.cod_tipo_inst=d.cod_tipo_institucion AND
					a.cod_inst=d.cod_institucion AND
					a.cod_dep=d.cod_dependencia AND ".
					$cond
					."a.ano_orden_pago='$ano' AND
					a.cuenta_bancaria!='0' AND
					a.monto_islr!='0' AND
					a.cedula_identidad!='0' AND
					b.cuenta_bancaria!='0'
					GROUP BY
					a.cod_dep,
					a.cod_tipo_pago,
					a.porcentaje_islr,
					a.ano_orden_pago,
					a.cedula_identidad,
					a.rif,
					d.denominacion,
					d.agente_retencion,
					d.fiscal_rentas,
					d.direccion,
					d.cod_area,
					d.telefonos,
					d.email,
					d.rif,
					d.nit
					ORDER BY
					a.cod_dep,
					a.cod_tipo_pago,
					a.porcentaje_islr,
					a.ano_orden_pago,
					a.cedula_identidad,
					a.rif;";

			// Para buscar solo las personas JURIDICAS.
			$sql_2 = "SELECT
					a.cod_dep,
					a.cod_tipo_pago,
					a.porcentaje_islr,
					a.ano_orden_pago,
					(SELECT tp.denominacion FROM cepd03_ordenpago_tipopago tp WHERE tp.cod_tipo_pago=a.cod_tipo_pago) as tipo_pago,
					devolver_denominacion_beneficiario_op(a.rif, a.cedula_identidad::text) as autorizado,
					d.denominacion as dependencia,
					d.agente_retencion,
					d.fiscal_rentas,
					d.direccion,
					d.cod_area,
					d.telefonos,
					d.email,
					d.rif as rif_dependencia,
					d.nit as nit_dependencia,
					a.cedula_identidad,
					a.rif as rif_orden_pago,
					COUNT(*) as cantidad_retenciones,
					SUM(c.monto_sub_total) as monto_base,
					SUM(a.monto_islr) as impuestos
					FROM
					cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cepd03_ordenpago_facturas c, cugd02_dependencias d
					WHERE
					a.cod_presi=b.cod_presi AND
					a.cod_entidad=b.cod_entidad AND
					a.cod_tipo_inst=b.cod_tipo_inst AND
					a.cod_inst=b.cod_inst AND
					a.cod_dep=b.cod_dep AND
					a.ano_orden_pago=b.ano_orden_pago AND
					a.numero_orden_pago=b.numero_orden_pago AND
					a.cod_presi=c.cod_presi AND
					a.cod_entidad=c.cod_entidad AND
					a.cod_tipo_inst=c.cod_tipo_inst AND
					a.cod_inst=c.cod_inst AND
					a.cod_dep=c.cod_dep AND
					a.ano_orden_pago=c.ano_orden_pago AND
					a.numero_orden_pago=c.numero_orden_pago AND
					a.cod_tipo_inst=d.cod_tipo_institucion AND
					a.cod_inst=d.cod_institucion AND
					a.cod_dep=d.cod_dependencia AND ".
					$cond
					."a.ano_orden_pago='$ano' AND
					a.cuenta_bancaria!='0' AND
					a.monto_islr!='0' AND
					a.cedula_identidad='0' AND
					b.cuenta_bancaria!='0'
					GROUP BY
					a.cod_dep,
					a.cod_tipo_pago,
					a.porcentaje_islr,
					a.ano_orden_pago,
					a.cedula_identidad,
					a.rif,
					d.denominacion,
					d.agente_retencion,
					d.fiscal_rentas,
					d.direccion,
					d.cod_area,
					d.telefonos,
					d.email,
					d.rif,
					d.nit
					ORDER BY
					a.cod_dep,
					a.cod_tipo_pago,
					a.porcentaje_islr,
					a.ano_orden_pago,
					a.cedula_identidad,
					a.rif;";

			/*
			$sql = "SELECT
			a.cod_dep,
			a.cod_tipo_pago,
			a.porcentaje_islr,
			a.ano_orden_pago,
			(SELECT tp.denominacion FROM cepd03_ordenpago_tipopago tp WHERE tp.cod_tipo_pago=a.cod_tipo_pago) as tipo_pago,
			SUBSTRING(ch.fecha_cheque::text, 6, 2) as mes_cheque,
			devolver_denominacion_beneficiario_op(a.rif, a.cedula_identidad::text) as autorizado,
			d.denominacion as dependencia,
			d.agente_retencion,
			d.fiscal_rentas,
			d.direccion,
			d.cod_area,
			d.telefonos,
			d.email,
			d.rif as rif_dependencia,
			d.nit as nit_dependencia,
			a.cedula_identidad,
			a.rif as rif_orden_pago,
			COUNT(*) as cantidad_retenciones,
			SUM(a.monto_descontar_impuesto) as monto_base,
			SUM(a.monto_islr) as impuestos
			FROM
			cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cepd03_ordenpago_facturas c, cugd02_institucion d, cstd03_cheque_cuerpo ch
			WHERE
			a.cod_presi=b.cod_presi AND
			a.cod_entidad=b.cod_entidad AND
			a.cod_tipo_inst=b.cod_tipo_inst AND
			a.cod_inst=b.cod_inst AND
			a.cod_dep=b.cod_dep AND
			a.ano_orden_pago=b.ano_orden_pago AND
			a.numero_orden_pago=b.numero_orden_pago AND
			a.cod_presi=c.cod_presi AND
			a.cod_entidad=c.cod_entidad AND
			a.cod_tipo_inst=c.cod_tipo_inst AND
			a.cod_inst=c.cod_inst AND
			a.cod_dep=c.cod_dep AND
			a.cod_presi=ch.cod_presi AND
			a.cod_entidad=ch.cod_entidad AND
			a.cod_tipo_inst=ch.cod_tipo_inst AND
			a.cod_inst=ch.cod_inst AND
			a.cod_dep=ch.cod_dep AND
			a.ano_orden_pago=ch.ano_movimiento AND
			a.numero_cheque=ch.numero_cheque AND
			a.ano_orden_pago=c.ano_orden_pago AND
			a.numero_orden_pago=c.numero_orden_pago AND
			a.cod_tipo_inst=d.cod_tipo_institucion AND
			a.cod_inst=d.cod_institucion AND ".
			$cond
			."a.ano_orden_pago='$ano' AND
			a.cuenta_bancaria!='0' AND
			a.monto_islr!='0' AND
			b.cuenta_bancaria!='0'
			GROUP BY
			a.cod_dep,
			a.cod_tipo_pago,
			a.porcentaje_islr,
			a.ano_orden_pago,
			a.cedula_identidad,
			a.rif,
			mes_cheque,
			d.denominacion,
			d.agente_retencion,
			d.fiscal_rentas,
			d.direccion,
			d.cod_area,
			d.telefonos,
			d.email,
			d.rif,
			d.nit
			ORDER BY
			a.cod_dep,
			a.cod_tipo_pago,
			a.porcentaje_islr,
			a.ano_orden_pago,
			a.cedula_identidad,
			a.rif,
			mes_cheque;";
			*/
					$datos = $this->cstd07_retenciones_cuerpo_islr->execute($sql_1);
					$datos_2 = $this->cstd07_retenciones_cuerpo_islr->execute($sql_2);
					$this->set('datos',$datos);
					$this->set('datos_2',$datos_2);
					$this->set('ano',$ano);

			$this->set('var',$var);
		}
	}
}//reporte_cuadro_rendicion_anual_seniat_islr


function reporte_relacion_rendicion_anual_seniat_islr($var=null){
	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano = $this->ano_ejecucion();
			$this->set('ano_ejecucion',$ano);
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$cod_dep == 1 ? $consolidacion = $this->data['cuadro_rendicion_anual_seniat_islr']['consolidacion'] : $consolidacion = 2;
			$ano = $this->data['cuadro_rendicion_anual_seniat_islr']['ano'];
			$ano == '' ? $ano = $this->ano_ejecucion() : '';

			if($consolidacion==1){
			$cond = "a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND ";
			}else{
			$cond = "a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.cod_dep='$cod_dep' AND ";
			}

			$sql = "SELECT
					a.cod_dep,
					a.cod_tipo_pago,
					a.porcentaje_islr,
					a.ano_orden_pago,
					(SELECT tp.denominacion FROM cepd03_ordenpago_tipopago tp WHERE tp.cod_tipo_pago=a.cod_tipo_pago) as tipo_pago,
					devolver_denominacion_beneficiario_op(a.rif, a.cedula_identidad::text) as autorizado,
					d.denominacion as dependencia,
					d.agente_retencion,
					d.fiscal_rentas,
					d.direccion,
					d.cod_area,
					d.telefonos,
					d.email,
					d.rif as rif_dependencia,
					d.nit as nit_dependencia,
					a.cedula_identidad,
					a.rif as rif_orden_pago,
					COUNT(*) as cantidad_retenciones,
					SUM(a.monto_descontar_impuesto) as monto_base,
					SUM(a.monto_islr) as impuestos
					FROM
					cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cepd03_ordenpago_facturas c, cugd02_dependencias d
					WHERE
					a.cod_presi=b.cod_presi AND
					a.cod_entidad=b.cod_entidad AND
					a.cod_tipo_inst=b.cod_tipo_inst AND
					a.cod_inst=b.cod_inst AND
					a.cod_dep=b.cod_dep AND
					a.ano_orden_pago=b.ano_orden_pago AND
					a.numero_orden_pago=b.numero_orden_pago AND
					a.cod_presi=c.cod_presi AND
					a.cod_entidad=c.cod_entidad AND
					a.cod_tipo_inst=c.cod_tipo_inst AND
					a.cod_inst=c.cod_inst AND
					a.cod_dep=c.cod_dep AND
					a.ano_orden_pago=c.ano_orden_pago AND
					a.numero_orden_pago=c.numero_orden_pago AND
					a.cod_tipo_inst=d.cod_tipo_institucion AND
					a.cod_inst=d.cod_institucion AND
					a.cod_dep=d.cod_dependencia AND ".
					$cond
					."a.ano_orden_pago='$ano' AND
					a.cuenta_bancaria!='0' AND
					a.monto_islr!='0' AND
					b.cuenta_bancaria!='0'
					GROUP BY
					a.cod_dep,
					a.cod_tipo_pago,
					a.porcentaje_islr,
					a.ano_orden_pago,
					a.cedula_identidad,
					a.rif,
					d.denominacion,
					d.agente_retencion,
					d.fiscal_rentas,
					d.direccion,
					d.cod_area,
					d.telefonos,
					d.email,
					d.rif,
					d.nit
					ORDER BY
					a.cod_dep,
					a.cod_tipo_pago,
					a.porcentaje_islr,
					a.ano_orden_pago,
					a.cedula_identidad,
					a.rif;";
					$datos = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
					$this->set('datos',$datos);
					$this->set('ano',$ano);

			$this->set('var',$var);
		}
	}
}//reporte_relacion_rendicion_anual_seniat_islr


function reporte_relacion_auxiliares($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano_ejecucion = $this->ano_ejecucion();
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
			$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$ano = $year[0]['cfpd01_formulacion']['ano_formular'];
			if($ano != 0){
				$this->set('ano_formulacion',$ano);
			}else{
				$this->set('ano_formulacion',$ano_ejecucion);
			}
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";

			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$ano = $this->data['relacion_auxiliares']['ano'];
			$ordenamiento = $this->data['relacion_auxiliares']['ordenamiento'];

			$_SESSION['ano_relacion_aux']= $ano;


			if(isset($this->data['cfpp05']['consolidacion'])){
	    	    $consolidacion = $this->data['cfpp05']['consolidacion'];

	    	    if($this->data['cfpp05']['consolidacion']==2){
	  	     		$titulo_a = $_SESSION["dependencia_reporte_consolidado"];
	  	     		$cod_dep  = $_SESSION['cod_dep_reporte_consolidado'];
		  	    }else if($this->data['cfpp05']['consolidacion']==1){
		  		    $titulo_a = $this->Session->read('dependencia');
		  	    }

	    	}else{
	    		$consolidacion = "";
	    	}

$this->set('titulo_a',$titulo_a);


			switch($ordenamiento){
				case '1':
						if($cod_dep==1){
							if($consolidacion==1){
							$sql   = "SELECT * FROM v_cfpd05_relacion_auxiliares WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND ano='$ano' ORDER BY cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";
							$datos = $this->cfpd05_auxiliar->execute($sql);
							}else{
							$sql   = "SELECT * FROM v_cfpd05_relacion_auxiliares WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano='$ano' ORDER BY cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";
							$datos = $this->cfpd05_auxiliar->execute($sql);
							}
							$this->set('datos',$datos);
						}else{
							$sql   = "SELECT * FROM v_cfpd05_relacion_auxiliares WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano='$ano' ORDER BY cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";
							$datos = $this->cfpd05_auxiliar->execute($sql);
							$this->set('datos',$datos);
						}
						break;

				case '2':
						if($cod_dep==1){
							if($consolidacion==1){
							$sql   = "SELECT * FROM v_cfpd05_relacion_auxiliares WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND ano='$ano' AND (utilizado_cfpd05='1' OR utilizado_cfpd05_requerimiento='1') ORDER BY cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";
							$datos = $this->cfpd05_auxiliar->execute($sql);
							}else{
							$sql   = "SELECT * FROM v_cfpd05_relacion_auxiliares WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano='$ano' AND (utilizado_cfpd05='1' OR utilizado_cfpd05_requerimiento='1') ORDER BY cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";
							$datos = $this->cfpd05_auxiliar->execute($sql);
							}
							$this->set('datos',$datos);
						}else{
							$sql   = "SELECT * FROM v_cfpd05_relacion_auxiliares WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano='$ano' AND utilizado='1' ORDER BY cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";
							$datos = $this->cfpd05_auxiliar->execute($sql);
							$this->set('datos',$datos);
						}
						break;

				case '3':
						if($cod_dep==1){
							if($consolidacion==1){
							$sql   = "SELECT * FROM v_cfpd05_relacion_auxiliares WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND ano='$ano' AND utilizado_cfpd05='0' AND utilizado_cfpd05_requerimiento='0' ORDER BY cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";
							$datos = $this->cfpd05_auxiliar->execute($sql);
							}else{
							$sql   = "SELECT * FROM v_cfpd05_relacion_auxiliares WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano='$ano' AND utilizado_cfpd05='0' AND utilizado_cfpd05_requerimiento='0' ORDER BY cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";
							$datos = $this->cfpd05_auxiliar->execute($sql);
							}
							$this->set('datos',$datos);
						}else{
							$sql   = "SELECT * FROM v_cfpd05_relacion_auxiliares WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND ano='$ano' AND utilizado_cfpd05='0' AND utilizado_cfpd05_requerimiento='0' ORDER BY cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";
							$datos = $this->cfpd05_auxiliar->execute($sql);
							$this->set('datos',$datos);
						}
						break;
			}
			$this->set('var',$var);
		}
	}
}



function reporte_cargos_fuera_distribucion($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$ano_ejecucion = $this->ano_ejecucion();
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
			$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$ano = $year[0]['cfpd01_formulacion']['ano_formular'];
			if($ano != 0){
				$this->set('ano_formulacion',$ano);
			}else{
				$this->set('ano_formulacion',$ano_ejecucion);
			}
			$this->set('var',$var);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";

			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$ano = $this->data['cargos_fuera_distribucion']['ano'];
			$_SESSION['ano_cargos_fuera']= $ano;


			     if(isset($this->data['cfpp05']['consolidacion'])){

					$consolidacion = $this->data['cfpp05']['consolidacion'];
			    	if($this->data['cfpp05']['consolidacion']==2){
			  	     	$titulo_a = $_SESSION["dependencia_reporte_consolidado"];
			  	     	$cod_dep  = $_SESSION['cod_dep_reporte_consolidado'];
			  	    }else if($this->data['cfpp05']['consolidacion']==1){
			  		    $titulo_a = $this->Session->read('dependencia');
			  	    }

		    	}else{
		    		    $consolidacion = 2;
		    		    $titulo_a = $this->Session->read('dependencia');
		    	}

		    	$this->set('titulo_a',$titulo_a);



			if($cod_dep==1){
				if($consolidacion==1){
					 $sql = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_tipo_nomina, a.cod_cargo,
						(SELECT c.denominacion FROM cnmd01 c WHERE a.cod_presi=c.cod_presi AND a.cod_entidad=c.cod_entidad AND a.cod_tipo_inst=c.cod_tipo_inst AND a.cod_inst=c.cod_inst AND a.cod_dep=c.cod_dep AND a.cod_tipo_nomina=c.cod_tipo_nomina) AS denominacion_nomina,
						(SELECT count(*) FROM cfpd05 b WHERE
						a.cod_presi         = b.cod_presi AND
						a.cod_entidad 	    = b.cod_entidad AND
						a.cod_tipo_inst     = b.cod_tipo_inst AND
						a.cod_inst          = b.cod_inst AND
						a.cod_dep           = b.cod_dep AND
						a.ano		        = b.ano AND
						a.cod_sector        = b.cod_sector AND
						a.cod_programa      = b.cod_programa AND
						a.cod_sub_prog      = b.cod_sub_prog AND
						a.cod_proyecto      = b.cod_proyecto AND
						a.cod_activ_obra    = b.cod_activ_obra AND
						a.cod_partida       = b.cod_partida AND
						a.cod_generica      = b.cod_generica AND
						a.cod_especifica    = b.cod_especifica AND
						a.cod_sub_espec     = b.cod_sub_espec AND
						a.cod_auxiliar      = b.cod_auxiliar) AS existe_cfpd05
						FROM cfpd97 a WHERE
						a.cod_presi     = '$cod_presi' AND
						a.cod_entidad 	= '$cod_entidad' AND
						a.cod_tipo_inst = '$cod_tipo_inst' AND
						a.cod_inst      = '$cod_inst' AND
						a.ano		    = '$ano'
						ORDER BY a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_tipo_nomina, a.cod_cargo;";
						$datos = $this->cfpd97->execute($sql);
						$this->set('datos',$datos);

				}else{
					$sql = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_tipo_nomina, a.cod_cargo,
						(SELECT c.denominacion FROM cnmd01 c WHERE a.cod_presi=c.cod_presi AND a.cod_entidad=c.cod_entidad AND a.cod_tipo_inst=c.cod_tipo_inst AND a.cod_inst=c.cod_inst AND a.cod_dep=c.cod_dep AND a.cod_tipo_nomina=c.cod_tipo_nomina) AS denominacion_nomina,
						(SELECT count(*) FROM cfpd05 b WHERE
						a.cod_presi         = b.cod_presi AND
						a.cod_entidad 	    = b.cod_entidad AND
						a.cod_tipo_inst     = b.cod_tipo_inst AND
						a.cod_inst          = b.cod_inst AND
						a.cod_dep           = b.cod_dep AND
						a.ano		        = b.ano AND
						a.cod_sector        = b.cod_sector AND
						a.cod_programa      = b.cod_programa AND
						a.cod_sub_prog      = b.cod_sub_prog AND
						a.cod_proyecto      = b.cod_proyecto AND
						a.cod_activ_obra    = b.cod_activ_obra AND
						a.cod_partida       = b.cod_partida AND
						a.cod_generica      = b.cod_generica AND
						a.cod_especifica    = b.cod_especifica AND
						a.cod_sub_espec     = b.cod_sub_espec AND
						a.cod_auxiliar      = b.cod_auxiliar) AS existe_cfpd05
						FROM cfpd97 a WHERE
						a.cod_presi     = '$cod_presi' AND
						a.cod_entidad 	= '$cod_entidad' AND
						a.cod_tipo_inst = '$cod_tipo_inst' AND
						a.cod_inst      = '$cod_inst' AND
						a.cod_dep       = '$cod_dep' AND
						a.ano		    = '$ano'
						ORDER BY a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_tipo_nomina, a.cod_cargo;";
						$datos = $this->cfpd97->execute($sql);
						$this->set('datos',$datos);
				}

			}else{
				$sql = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_tipo_nomina, a.cod_cargo,
						(SELECT c.denominacion FROM cnmd01 c WHERE a.cod_presi=c.cod_presi AND a.cod_entidad=c.cod_entidad AND a.cod_tipo_inst=c.cod_tipo_inst AND a.cod_inst=c.cod_inst AND a.cod_dep=c.cod_dep AND a.cod_tipo_nomina=c.cod_tipo_nomina) AS denominacion_nomina,
						(SELECT count(*) FROM cfpd05 b WHERE
						a.cod_presi         = b.cod_presi AND
						a.cod_entidad 	    = b.cod_entidad AND
						a.cod_tipo_inst     = b.cod_tipo_inst AND
						a.cod_inst          = b.cod_inst AND
						a.cod_dep           = b.cod_dep AND
						a.ano		        = b.ano AND
						a.cod_sector        = b.cod_sector AND
						a.cod_programa      = b.cod_programa AND
						a.cod_sub_prog      = b.cod_sub_prog AND
						a.cod_proyecto      = b.cod_proyecto AND
						a.cod_activ_obra    = b.cod_activ_obra AND
						a.cod_partida       = b.cod_partida AND
						a.cod_generica      = b.cod_generica AND
						a.cod_especifica    = b.cod_especifica AND
						a.cod_sub_espec     = b.cod_sub_espec AND
						a.cod_auxiliar      = b.cod_auxiliar) AS existe_cfpd05
						FROM cfpd97 a WHERE
						a.cod_presi     = '$cod_presi' AND
						a.cod_entidad 	= '$cod_entidad' AND
						a.cod_tipo_inst = '$cod_tipo_inst' AND
						a.cod_inst      = '$cod_inst' AND
						a.cod_dep       = '$cod_dep' AND
						a.ano		    = '$ano'
						ORDER BY a.cod_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_tipo_nomina, a.cod_cargo;";
						$datos = $this->cfpd97->execute($sql);
						$this->set('datos',$datos);
			}
		$this->set('var',$var);
		}
	}
}





function generar_diskette_cuadrorendicionanual_islr(){
	$this->layout="ajax";

	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$cod_dep == 1 ? $consolidacion = $this->data['cuadro_rendicion_anual_seniat_islr']['consolidacion'] : $consolidacion = 2;
	$ano = $this->data['cuadro_rendicion_anual_seniat_islr']['ano'];
	$ano == '' ? $ano = $this->ano_ejecucion() : '';

	if($consolidacion==1){
	$cond = "a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND ";
	}else{
	$cond = "a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.cod_dep='$cod_dep' AND ";
	}

	$cadena='';

	/*$sql = "SELECT
			a.cod_dep,
			a.cod_tipo_pago,
			a.porcentaje_islr,
			a.ano_orden_pago,
			(SELECT tp.denominacion FROM cepd03_ordenpago_tipopago tp WHERE tp.cod_tipo_pago=a.cod_tipo_pago) as tipo_pago,
			SUBSTRING(ch.fecha_cheque::text, 6, 2) as mes_cheque,
			devolver_denominacion_beneficiario_op(a.rif, a.cedula_identidad::text) as autorizado,
			d.denominacion as dependencia,
			d.agente_retencion,
			d.fiscal_rentas,
			d.direccion,
			d.cod_area,
			d.telefonos,
			d.email,
			d.rif as rif_dependencia,
			d.nit as nit_dependencia,
			a.cedula_identidad,
			a.rif as rif_orden_pago,
			COUNT(*) as cantidad_retenciones,
			SUM(c.monto_sub_total) as monto_base,
			SUM(a.monto_islr) as impuestos
			FROM
			cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cepd03_ordenpago_facturas c, cugd02_institucion d, cstd03_cheque_cuerpo ch
			WHERE
			a.cod_presi=b.cod_presi AND
			a.cod_entidad=b.cod_entidad AND
			a.cod_tipo_inst=b.cod_tipo_inst AND
			a.cod_inst=b.cod_inst AND
			a.cod_dep=b.cod_dep AND
			a.ano_orden_pago=b.ano_orden_pago AND
			a.numero_orden_pago=b.numero_orden_pago AND
			a.cod_presi=c.cod_presi AND
			a.cod_entidad=c.cod_entidad AND
			a.cod_tipo_inst=c.cod_tipo_inst AND
			a.cod_inst=c.cod_inst AND
			a.cod_dep=c.cod_dep AND
			a.cod_presi=ch.cod_presi AND
			a.cod_entidad=ch.cod_entidad AND
			a.cod_tipo_inst=ch.cod_tipo_inst AND
			a.cod_inst=ch.cod_inst AND
			a.cod_dep=ch.cod_dep AND
			a.ano_orden_pago=ch.ano_movimiento AND
			a.numero_cheque=ch.numero_cheque AND
			a.ano_orden_pago=c.ano_orden_pago AND
			a.numero_orden_pago=c.numero_orden_pago AND
			a.cod_tipo_inst=d.cod_tipo_institucion AND
			a.cod_inst=d.cod_institucion AND ".
			$cond
			."a.ano_orden_pago='$ano' AND
			a.cuenta_bancaria!='0' AND
			a.monto_islr!='0' AND
			b.cuenta_bancaria!='0'
			GROUP BY
			a.cod_dep,
			a.cod_tipo_pago,
			a.porcentaje_islr,
			a.ano_orden_pago,
			a.cedula_identidad,
			a.rif,
			ch.fecha_cheque,
			d.denominacion,
			d.agente_retencion,
			d.fiscal_rentas,
			d.direccion,
			d.cod_area,
			d.telefonos,
			d.email,
			d.rif,
			d.nit
			ORDER BY
			a.cod_dep,
			a.cod_tipo_pago,
			a.porcentaje_islr,
			a.ano_orden_pago,
			a.cedula_identidad,
			a.rif,
			mes_cheque;";
			*/

			$sql = "SELECT
			a.cod_dep,
			a.cod_tipo_pago,
			a.porcentaje_islr,
			a.ano_orden_pago,
			(SELECT tp.denominacion FROM cepd03_ordenpago_tipopago tp WHERE tp.cod_tipo_pago=a.cod_tipo_pago) as tipo_pago,
			SUBSTRING(ch.fecha_cheque::text, 6, 2) as mes_cheque,
			devolver_denominacion_beneficiario_op(a.rif, a.cedula_identidad::text) as autorizado,
			d.denominacion as dependencia,
			d.agente_retencion,
			d.fiscal_rentas,
			d.direccion,
			d.cod_area,
			d.telefonos,
			d.email,
			d.rif as rif_dependencia,
			d.nit as nit_dependencia,
			a.cedula_identidad,
			a.rif as rif_orden_pago,
			COUNT(*) as cantidad_retenciones,
			SUM(a.monto_descontar_impuesto) as monto_base,
			SUM(a.monto_islr) as impuestos
			FROM
			cepd03_ordenpago_cuerpo a, cstd07_retenciones_cuerpo_islr b, cepd03_ordenpago_facturas c, cugd02_institucion d, cstd03_cheque_cuerpo ch
			WHERE
			a.cod_presi=b.cod_presi AND
			a.cod_entidad=b.cod_entidad AND
			a.cod_tipo_inst=b.cod_tipo_inst AND
			a.cod_inst=b.cod_inst AND
			a.cod_dep=b.cod_dep AND
			a.ano_orden_pago=b.ano_orden_pago AND
			a.numero_orden_pago=b.numero_orden_pago AND
			a.cod_presi=c.cod_presi AND
			a.cod_entidad=c.cod_entidad AND
			a.cod_tipo_inst=c.cod_tipo_inst AND
			a.cod_inst=c.cod_inst AND
			a.cod_dep=c.cod_dep AND
			a.cod_presi=ch.cod_presi AND
			a.cod_entidad=ch.cod_entidad AND
			a.cod_tipo_inst=ch.cod_tipo_inst AND
			a.cod_inst=ch.cod_inst AND
			a.cod_dep=ch.cod_dep AND
			a.ano_orden_pago=ch.ano_movimiento AND
			a.numero_cheque=ch.numero_cheque AND
			a.ano_orden_pago=c.ano_orden_pago AND
			a.numero_orden_pago=c.numero_orden_pago AND
			a.cod_tipo_inst=d.cod_tipo_institucion AND
			a.cod_inst=d.cod_institucion AND ".
			$cond
			."a.ano_orden_pago='$ano' AND
			a.cuenta_bancaria!='0' AND
			a.monto_islr!='0' AND
			b.cuenta_bancaria!='0'
			GROUP BY
			a.cod_dep,
			a.cod_tipo_pago,
			a.porcentaje_islr,
			a.ano_orden_pago,
			a.cedula_identidad,
			a.rif,
			mes_cheque,
			d.denominacion,
			d.agente_retencion,
			d.fiscal_rentas,
			d.direccion,
			d.cod_area,
			d.telefonos,
			d.email,
			d.rif,
			d.nit
			ORDER BY
			a.cod_dep,
			a.ano_orden_pago,
			a.cedula_identidad,
			a.rif,
			mes_cheque;";
			$datos = $this->cstd07_retenciones_cuerpo_islr->execute($sql);
			$rif_2 = "A-111-A";//RIF GENERICO DE CONTROL
			for($i=0; $i<count($datos); $i++){
				$rif_1 = $datos[$i][0]['rif_orden_pago'];

				/*
				 * Todos estos datos se sacan de la tabla de institucion, y no van agrupados, deben ser individuales pendiente.
				 * */
				$institucion = strtoupper($datos[$i][0]['dependencia']);
				$rif_inst = strtoupper($datos[$i][0]['rif_dependencia']);
				$periodo = '12'.$ano;//'(12AAAA) 12345678 99 EN EL CASO DEL 2008 SERIA ASÍ 122008';
				$agent_reten = strtoupper($datos[$i][0]['agente_retencion']);
				$telefono = $datos[$i][0]['cod_area'].''.$datos[$i][0]['telefonos'];
				$dir_inst = strtoupper($datos[$i][0]['direccion']);
				$dato_fijo1 = '01';
				$contribuyente = strtoupper($datos[$i][0]['autorizado']);
				$rif_contrib = strtoupper($datos[$i][0]['rif_orden_pago']);
				if($rif_contrib!='0'){
					$cedula_contrib = ' ';
				}else{
					$rif_contrib = ' ';
					$cedula_contrib = $datos[$i][0]['cedula_identidad'];
				}
				$ano_retencion = $datos[$i][0]['ano_orden_pago'];
				$mes_retencion = $datos[$i][0]['mes_cheque'];
				$base_mensual_aux = str_replace('.','',$datos[$i][0]['monto_base']);
				$retencion_mensual_aux = str_replace('.','',$datos[$i][0]['impuestos']);

				$base_mensual = $this->agregarCeros($base_mensual_aux, '14');
				$retencion_mensual = $this->agregarCeros($retencion_mensual_aux, '14');

				if($rif_1 != $rif_2){
					$cadena .= "".$institucion.";".$rif_inst.";".$periodo.";".$agent_reten.";".$telefono.";".$dir_inst.";".$dato_fijo1."; ; ;".$contribuyente.";".$rif_contrib.";".$cedula_contrib.";".$ano_retencion.";".$mes_retencion.";".$base_mensual.";".$retencion_mensual."\n";
				}else{
					$cadena .= " ; ; ; ; ; ; ; ; ;".$contribuyente.";".$rif_contrib.";".$cedula_contrib.";".$ano_retencion.";".$mes_retencion.";".$base_mensual.";".$retencion_mensual."\n";
				}

				$rif_2 = $datos[$i][0]['rif_orden_pago'];
			}
			$cadena == '' ? $cadena=' ' : '';//Si no paso por el ciclo for, significa que no se encontraron datos, por lo que se le asigna un espacio vacio para que tumbe el contenido viejo del archivo.
			$nombre_archivo = 'cra_islr_'.$cod_dep.'_'.date('d_m_Y');
			$this->wFile($nombre_archivo, $cadena);
			if(file_exists('../webroot/descargas/cra_islr_'.$cod_dep.'_'.date('d_m_Y').'.txt')){chmod('../webroot/descargas/cra_islr_'.$cod_dep.'_'.date('d_m_Y').'.txt', 0777);}
			$this->set('name', $nombre_archivo);

}

function entrar_relacion_proyecto(){
	$this->layout="ajax";
	if(isset($this->data['relacion_proyecto']['login']) && isset($this->data['relacion_proyecto']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['relacion_proyecto']['login']);
		$paswd=addslashes($this->data['relacion_proyecto']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=92 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->set('opcion','si');
			$this->relacion_proyecto();
			$this->render("relacion_proyecto");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->set('opcion','si');
			$this->relacion_proyecto();
			$this->render("relacion_proyecto");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('opcion','si');
			$this->relacion_proyecto();
			$this->render("relacion_proyecto");
		}
	}
}

function entrar_relacion_obra(){
	$this->layout="ajax";
	if(isset($this->data['relacion_obra']['login']) && isset($this->data['relacion_obra']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['relacion_obra']['login']);
		$paswd=addslashes($this->data['relacion_obra']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=93 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->set('opcion','si');
			$this->relacion_obra();
			$this->render("relacion_obra");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->set('opcion','si');
			$this->relacion_obra();
			$this->render("relacion_obra");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->set('opcion','si');
			$this->relacion_obra();
			$this->render("relacion_obra");
		}
	}
}




function reporte_registro_valuacion($year=null, $opcion=null, $cont=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
$this->set('ir', 'no');
$this->Session->delete('FIN');

    $partidacorriente=407120;
  $partidacapital=407220;

if($year == "vista"){

  $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);


$url                  =  "/select_ventana_generar_reporte/v_reporte_emision_rc_generico_valuacion_1_f";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

    if($opcion==3){
           echo"<script>";
             echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
           echo"</script>";
      }else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

  }//fin else



}else{



if(!$year){


    #$this->limpia_menu();
    $this->set('ir', 'no');

    $ano = $this->ano_ejecucion();

  if(!empty($dato)){
    $this->set('year', $dato);
  }else{
    $this->set('year', '');
  }


$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);

$datos_cepd01_compromiso_poremitir = $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cepd01_compromiso_poremitir', $datos_cepd01_compromiso_poremitir);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


    $this->layout = "pdf";
    $this->set('pdf', 'si');
    $radio = 1;
    $numero_compromiso_a  = "";
    $numero_compromiso_b  = "";


         if(isset($this->data['cobd01_contratoobras_cuerpo']['radio'])){$radio = $this->data['cobd01_contratoobras_cuerpo']['radio'];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''])){     $ano_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''])){$numero_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_b'])){$numero_compromiso_b = $this->data['cobd01_contratoobras_cuerpo']['numero_b'];}




if($radio == "3" && $numero_compromiso_a!=""){////////////////////////////////////////////////OPCION 1
  $sql = "SELECT c.ano_contrato_obra,
                 c.numero_contrato_obra,
                 c.ano_estimacion,
                 c.cod_obra,
                 c.cod_estado,
                 c.cod_municipio,
                 c.cod_parroquia,
                 c.cod_centro,
                 especifique_ubicacion,
                 otorgamiento,
                 denominacion_obra,
                 c.rif,
                 fecha_contrato_obra,
                 fecha_inicio_contrato,
                 fecha_terminacion_contrato,
                 e.denominacion AS estado,
                 m.denominacion AS municipio,
                 p.denominacion AS parroquia,
                 a.denominacion AS centro,
                 x.monto_original_contrato,
                 x.aumento,
                 x.disminucion,
                 (x.monto_original_contrato + x.aumento - x.disminucion) AS monto_actual,
                 x.monto_retencion_laboral,
                 x.monto_retencion_fielcumplimiento,
                 (x.monto_retencion_laboral + x.monto_retencion_fielcumplimiento) AS monto_retenciones,
                 x.monto_cancelado,
                 x.monto_anticipo,
                 x.monto_amortizacion,
                 (x.monto_anticipo - x.monto_amortizacion) AS saldo_anticipo,
                 ((x.monto_original_contrato + x.aumento) - (x.disminucion + x.monto_cancelado + x.monto_amortizacion + x.monto_retencion_laboral +
                  x.monto_retencion_fielcumplimiento)) AS saldo_del_contrato,
                r.denominacion AS empresa,
                x.concepto AS observaciones
          FROM cobd01_contratoobras_cuerpo c
          JOIN cugd01_estados e ON (c.cod_estado = e.cod_estado)
          JOIN cugd01_municipios m ON (c.cod_municipio = m.cod_municipio AND m.cod_estado = c.cod_estado)
          JOIN cugd01_parroquias p ON (c.cod_parroquia = p.cod_parroquia AND p.cod_estado = c.cod_estado AND p.cod_municipio = c.cod_municipio)
          JOIN cugd01_centros_poblados a ON (c.cod_parroquia = a.cod_parroquia AND a.cod_estado = c.cod_estado AND a.cod_municipio = c.cod_municipio AND a.cod_parroquia = p.cod_parroquia AND a.cod_centro = c.cod_centro)
          JOIN cpcd02 r ON (r.rif = c.rif)
    JOIN cobd01_contratoobras_valuacion_cuerpo x ON (x.ano_contrato_obra = c.ano_contrato_obra AND c.numero_contrato_obra = x.numero_contrato_obra)

          WHERE c.cod_obra = '$numero_compromiso_a' AND c.condicion_actividad = 1 AND c.ano_contrato_obra = $ano_compromiso_a LIMIT 1";
  $datos_cepd01_compromiso_cuerpo = $this->cobd01_contratoobras_cuerpo->query($sql);
  #echo $sql;
  $sql = "SELECT p.ano,
                 cod_sector,
                 cod_programa,
                 cod_sub_prog,
                 cod_proyecto,
                 cod_activ_obra,
                 cod_partida,
                 cod_generica,
                 cod_especifica,
                 cod_sub_espec,
                 cod_auxiliar,
                 monto
      FROM cobd01_contratoobras_cuerpo c
      JOIN cobd01_contratoobras_valuacion_partidas p ON (p.ano_contrato_obra = c.ano_contrato_obra AND p.numero_contrato_obra = c.numero_contrato_obra)
       WHERE c.cod_obra = '$numero_compromiso_a' AND c.condicion_actividad = 1 AND c.ano_contrato_obra = $ano_compromiso_a";
    $datos_cepd01_compromiso_partidas = $this->cobd01_contratoobras_cuerpo->query($sql);


}else if($radio=="2" && $numero_compromiso_a!="" && $numero_compromiso_b!=""){///////////////////////////////////////OPCION 2

  $datos_cepd01_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ", null, ' numero_documento ASC');
  $datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and (numero>='$numero_compromiso_a'  and numero<='$numero_compromiso_a')  ";

$sql_rif_a = "";

$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]=0;
   $capital[$aux]=0;
   $ordinario[$aux] = 0;
   $coordinado[$aux] = 0;
   $laee[$aux] = 0;
   $fides[$aux] = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;
foreach($datos_cepd01_compromiso_partidas as $partidas){
  if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
        if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
            $capital[$aux]   = 1;
        }else{$corriente[$aux] = 1;}
      }//fin if
      $evalpartcorriente=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
        }//fin if
    }//fin for

$resultado=$this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];



    $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));

    $direccionsup[$aux] = $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux] = $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]   = $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]    = $cugd02_direccion[0]['cugd02_direccion']['denominacion'];

if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}


  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if

$sql_rif_a = "asdasd";

}//fin for each

if($sql_rif_a==""){
  echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);

}//fin for





}else if($radio=="1"){//////////////////////////////////////////////////////////////////////////////////OPCIOPN 3





$datos_cepd01_compromiso_poremitir      =   $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";
$sql_para_por_emitir = "";
foreach($datos_cepd01_compromiso_poremitir as $aux_cepd01_compromiso_poremitir){ $ii++;

  $ano_documento_1[$ii]     =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['ano_documento'];
  $numero_documento_1[$ii]  =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['numero_documento'];


if($sql_1==""){$sql_1   .= "  ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."'  ";       $sql_para_por_emitir .= "    and   ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."')  ";  $sql_para_por_emitir .= " or  (ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."')  ";}

}//fin for


if($sql_1==""){

  echo'<script>history.back(1);</script>';

}else{


  $datos_cepd01_compromiso_cuerpo    =  $this->cepd01_compromiso_cuerpo->findAll(  $condicion." and condicion_actividad=1 and (".$sql_1.") and username_registro='".$this->Session->read('nom_usuario')."'   ", null, 'numero_documento ASC');
  $datos_cepd01_compromiso_partidas  =  $this->cepd01_compromiso_partidas->findAll($condicion." and (".$sql_1.")",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');


$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
    if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]     = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]    = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]           = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]         = 1;}

             if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
              $capital[$aux] = 1;
        }else{$corriente[$aux] = 1; }
      }//fin if
      $evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];

      }//fin if
    }//fin for
                   $resultado =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
                        $rif  =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];
        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
    $direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}
  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];
if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if


}//fin for each


$datos_cpcd02    =   $this->cpcd02->findAll($sql_rif);


$sql = "delete from cepd01_compromiso_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cepd01_compromiso_poremitir->execute($sql);


}//fin else


                  }//fin else

    $this->set('direccionsup',$direccionsup);
    $this->set('coordinacion',$coordinacion);
    $this->set('secretaria',$secretaria);
    $this->set('direccion',$direccion);

     $this->set('corriente',$corriente);
   $this->set('capital',$capital);
   $this->set('ordinario',$ordinario);
   $this->set('coordinado',$coordinado);
   $this->set('laee',$laee);
   $this->set('fides',$fides);
   $this->set('ingresosextra',$ingresosextra);
   $this->set('ingresospropios',$ingresospropios);
   $this->set('fci',$fci);
   $this->set('mpps',$mpps);
   $this->set('tipodocu',$tipodocu);
   $this->set('datos_cepd01_compromiso_cuerpo',$datos_cepd01_compromiso_cuerpo);
   $this->set('datos_cepd01_compromiso_partidas',$datos_cepd01_compromiso_partidas);
     $sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ".$sql_para_por_emitir;
     $this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));

}//fin else


$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin else


}//fin funtion




function reporte_registro_compromiso_servicio($year=null, $opcion=null, $cont=null){

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
$this->set('ir', 'no');
$this->Session->delete('FIN');

    $partidacorriente=407120;
  $partidacapital=407220;

if($year == "vista"){

  $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);


$url                  =  "/select_ventana_generar_reporte/v_reporte_emision_rc_generico_1_servicio_f";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

    if($opcion==3){
           echo"<script>";
             echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
           echo"</script>";
      }else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

  }//fin else



}else{



if(!$year){


    #$this->limpia_menu();
    $this->set('ir', 'no');

    $ano = $this->ano_ejecucion();

  if(!empty($dato)){
    $this->set('year', $dato);
  }else{
    $this->set('year', '');
  }


$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);

$datos_cepd01_compromiso_poremitir = $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cepd01_compromiso_poremitir', $datos_cepd01_compromiso_poremitir);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


    $this->layout = "pdf";
    $this->set('pdf', 'si');
    $radio = 1;
    $numero_compromiso_a  = "";
    $numero_compromiso_b  = "";


         if(isset($this->data['cobd01_contratoobras_cuerpo']['radio'])){$radio = $this->data['cobd01_contratoobras_cuerpo']['radio'];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''])){     $ano_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''])){$numero_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_b'])){$numero_compromiso_b = $this->data['cobd01_contratoobras_cuerpo']['numero_b'];}




if($radio == "3" && $numero_compromiso_a!=""){////////////////////////////////////////////////OPCION 1
  $sql = "SELECT ano_contrato_servicio,
                  numero_contrato_servicio,
                  c.codigo_prod_serv,
                  c.cod_dir_superior,
                  c.cod_coordinacion,
                  c.cod_secretaria,
                  c.cod_direccion,
                  rif,
                  concepto,
                  fecha_contrato_servicio,
                  fecha_inicio_contrato,
                  fecha_terminacion_contrato,
                  d.denominacion AS direccion_superior,
                  p.denominacion AS cordinacion,
                  s.denominacion AS secretaria,
                  x.denominacion AS direccion,
                  t.denominacion AS denominacion_servicio
                  FROM cepd02_contratoservicio_cuerpo c
        JOIN cscd01_catalogo t ON (t.codigo_prod_serv=c.codigo_prod_serv)
        JOIN cugd02_direccionsuperior d ON (c.cod_dir_superior = d.cod_dir_superior AND c.cod_tipo_inst = d.cod_tipo_institucion AND c.cod_inst = d.cod_institucion AND c.cod_dep = d.cod_dependencia)
        JOIN cugd02_coordinacion p ON (p.cod_coordinacion = c.cod_coordinacion AND c.cod_dir_superior = p.cod_dir_superior AND c.cod_tipo_inst = p.cod_tipo_institucion AND c.cod_inst = p.cod_institucion AND c.cod_dep = p.cod_dependencia)
        JOIN cugd02_secretaria s ON (s.cod_secretaria = c.cod_secretaria AND s.cod_coordinacion = c.cod_coordinacion AND c.cod_dir_superior = s.cod_dir_superior AND c.cod_tipo_inst = s.cod_tipo_institucion AND c.cod_inst = s.cod_institucion AND c.cod_dep = s.cod_dependencia)
        JOIN cugd02_direccion x ON (x.cod_direccion = c.cod_direccion AND x.cod_secretaria = c.cod_secretaria AND x.cod_coordinacion = c.cod_coordinacion AND c.cod_dir_superior = x.cod_dir_superior AND c.cod_tipo_inst = x.cod_tipo_institucion AND c.cod_inst = x.cod_institucion AND c.cod_dep = x.cod_dependencia)
        WHERE numero_contrato_servicio = '$numero_compromiso_a' AND ano_contrato_servicio = $ano_compromiso_a AND condicion_actividad = 1";
 // print_r($sql);
   $datos_cepd01_compromiso_cuerpo = $this->cobd01_contratoobras_cuerpo->query($sql);

  $sql = "SELECT ano,
       cod_sector,
       cod_programa,
       cod_sub_prog,
       cod_proyecto,
       cod_activ_obra,
       cod_partida,
       cod_generica,
       cod_especifica,
       cod_sub_espec,
       cod_auxiliar,
       monto FROM cepd02_contratoservicio_partidas
WHERE numero_contrato_servicio = '$numero_compromiso_a' AND ano_contrato_servicio = $ano_compromiso_a";
    $datos_cepd01_compromiso_partidas = $this->cobd01_contratoobras_cuerpo->query($sql);


}else if($radio=="2" && $numero_compromiso_a!="" && $numero_compromiso_b!=""){///////////////////////////////////////OPCION 2

  $datos_cepd01_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ", null, ' numero_documento ASC');
  $datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and (numero>='$numero_compromiso_a'  and numero<='$numero_compromiso_a')  ";

$sql_rif_a = "";

$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]=0;
   $capital[$aux]=0;
   $ordinario[$aux] = 0;
   $coordinado[$aux] = 0;
   $laee[$aux] = 0;
   $fides[$aux] = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;
foreach($datos_cepd01_compromiso_partidas as $partidas){
  if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
        if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
            $capital[$aux]   = 1;
        }else{$corriente[$aux] = 1;}
      }//fin if
      $evalpartcorriente=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
        }//fin if
    }//fin for

$resultado=$this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];



    $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));

    $direccionsup[$aux] = $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux] = $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]   = $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]    = $cugd02_direccion[0]['cugd02_direccion']['denominacion'];

if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}


  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if

$sql_rif_a = "asdasd";

}//fin for each

if($sql_rif_a==""){
  echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);

}//fin for





}else if($radio=="1"){//////////////////////////////////////////////////////////////////////////////////OPCIOPN 3





$datos_cepd01_compromiso_poremitir      =   $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";
$sql_para_por_emitir = "";
foreach($datos_cepd01_compromiso_poremitir as $aux_cepd01_compromiso_poremitir){ $ii++;

  $ano_documento_1[$ii]     =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['ano_documento'];
  $numero_documento_1[$ii]  =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['numero_documento'];


if($sql_1==""){$sql_1   .= "  ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."'  ";       $sql_para_por_emitir .= "    and   ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."')  ";  $sql_para_por_emitir .= " or  (ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."')  ";}

}//fin for


if($sql_1==""){

  echo'<script>history.back(1);</script>';

}else{


  $datos_cepd01_compromiso_cuerpo    =  $this->cepd01_compromiso_cuerpo->findAll(  $condicion." and condicion_actividad=1 and (".$sql_1.") and username_registro='".$this->Session->read('nom_usuario')."'   ", null, 'numero_documento ASC');
    $datos_cepd01_compromiso_partidas  =  $this->cepd01_compromiso_partidas->findAll($condicion." and (".$sql_1.")",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');


$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
    if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]     = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]    = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]           = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]         = 1;}

             if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
              $capital[$aux] = 1;
        }else{$corriente[$aux] = 1; }
      }//fin if
      $evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
      }//fin if
    }//fin for
                   $resultado =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
                        $rif  =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];
        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
    $direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}
  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];
if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if


}//fin for each


$datos_cpcd02    =   $this->cpcd02->findAll($sql_rif);


$sql = "delete from cepd01_compromiso_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cepd01_compromiso_poremitir->execute($sql);


}//fin else


                  }//fin else

    $this->set('direccionsup',$direccionsup);
    $this->set('coordinacion',$coordinacion);
    $this->set('secretaria',$secretaria);
    $this->set('direccion',$direccion);

     $this->set('corriente',$corriente);
   $this->set('capital',$capital);
   $this->set('ordinario',$ordinario);
   $this->set('coordinado',$coordinado);
   $this->set('laee',$laee);
   $this->set('fides',$fides);
   $this->set('ingresosextra',$ingresosextra);
   $this->set('ingresospropios',$ingresospropios);
   $this->set('fci',$fci);
   $this->set('mpps',$mpps);
   $this->set('tipodocu',$tipodocu);
   $this->set('datos_cepd01_compromiso_cuerpo',$datos_cepd01_compromiso_cuerpo);
   $this->set('datos_cepd01_compromiso_partidas',$datos_cepd01_compromiso_partidas);
     $sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ".$sql_para_por_emitir;
     $this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));

}//fin else





$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));






}//fin else


}//fin funtion



function reporte_registro_compromiso($year=null, $opcion=null, $cont=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
$this->set('ir', 'no');
$this->Session->delete('FIN');

  $partidacorriente=407120;
  $partidacapital=407220;

if($year == "vista"){

  $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);


$url                  =  "/select_ventana_generar_reporte/v_reporte_emision_rc_generico_1_f";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

    if($opcion==3){
           echo"<script>";
             echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
           echo"</script>";
      }else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

  }//fin else



}else{



if(!$year){


    #$this->limpia_menu();
    $this->set('ir', 'no');

    $ano = $this->ano_ejecucion();

  if(!empty($dato)){
    $this->set('year', $dato);
  }else{
    $this->set('year', '');
  }


$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);

$datos_cepd01_compromiso_poremitir = $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cepd01_compromiso_poremitir', $datos_cepd01_compromiso_poremitir);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


    $this->layout = "pdf";
    $this->set('pdf', 'si');
    $radio = 1;
    $numero_compromiso_a  = "";
    $numero_compromiso_b  = "";


         if(isset($this->data['cobd01_contratoobras_cuerpo']['radio'])){$radio = $this->data['cobd01_contratoobras_cuerpo']['radio'];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''])){     $ano_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''])){$numero_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_b'])){$numero_compromiso_b = $this->data['cobd01_contratoobras_cuerpo']['numero_b'];}




if($radio == "3" && $numero_compromiso_a!=""){////////////////////////////////////////////////OPCION 1
  $sql = "SELECT ano_contrato_obra,
                 numero_contrato_obra,
                 ano_estimacion,
                 cod_obra,
                 c.cod_estado,
                 c.cod_municipio,
                 c.cod_parroquia,
                 c.cod_centro,
                 especifique_ubicacion,
                 otorgamiento,
                 denominacion_obra,
                 rif,
                 fecha_contrato_obra,
                 fecha_inicio_contrato,
                 fecha_terminacion_contrato,
                 e.denominacion AS estado,
                 m.denominacion AS municipio,
                 p.denominacion AS parroquia,
                 a.denominacion AS centro
          FROM cobd01_contratoobras_cuerpo c
          JOIN cugd01_estados e ON (c.cod_estado = e.cod_estado)
          JOIN cugd01_municipios m ON (c.cod_municipio = m.cod_municipio AND m.cod_estado = c.cod_estado)
          JOIN cugd01_parroquias p ON (c.cod_parroquia = p.cod_parroquia AND p.cod_estado = c.cod_estado AND p.cod_municipio = c.cod_municipio)
          JOIN cugd01_centros_poblados a ON (c.cod_parroquia = a.cod_parroquia AND a.cod_estado = c.cod_estado AND a.cod_municipio = c.cod_municipio AND a.cod_parroquia = p.cod_parroquia AND a.cod_centro = c.cod_centro)
          WHERE cod_obra = '$numero_compromiso_a' AND condicion_actividad = 1 AND ano_contrato_obra = $ano_compromiso_a";
  $datos_cepd01_compromiso_cuerpo = $this->cobd01_contratoobras_cuerpo->query($sql);

  $sql = "SELECT p.ano,
                 cod_sector,
                 cod_programa,
                 cod_sub_prog,
                 cod_proyecto,
                 cod_activ_obra,
                 cod_partida,
                 cod_generica,
                 cod_especifica,
                 cod_sub_espec,
                 cod_auxiliar,
                 monto
      FROM cobd01_contratoobras_cuerpo c
      JOIN cobd01_contratoobras_partidas p ON (p.ano_contrato_obra = c.ano_contrato_obra AND p.numero_contrato_obra = c.numero_contrato_obra)
       WHERE c.cod_obra = '$numero_compromiso_a' AND c.condicion_actividad = 1 AND c.ano_contrato_obra = $ano_compromiso_a";
    $datos_cepd01_compromiso_partidas = $this->cobd01_contratoobras_cuerpo->query($sql);


}else if($radio=="2" && $numero_compromiso_a!="" && $numero_compromiso_b!=""){///////////////////////////////////////OPCION 2

  $datos_cepd01_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ", null, ' numero_documento ASC');
  $datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and (numero>='$numero_compromiso_a'  and numero<='$numero_compromiso_a')  ";

$sql_rif_a = "";

$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]=0;
   $capital[$aux]=0;
   $ordinario[$aux] = 0;
   $coordinado[$aux] = 0;
   $laee[$aux] = 0;
   $fides[$aux] = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;
foreach($datos_cepd01_compromiso_partidas as $partidas){
  if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
        if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
            $capital[$aux]   = 1;
        }else{$corriente[$aux] = 1;}
      }//fin if
      $evalpartcorriente=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
        }//fin if
    }//fin for

$resultado=$this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];



        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));

    $direccionsup[$aux] = $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux] = $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]   = $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]    = $cugd02_direccion[0]['cugd02_direccion']['denominacion'];

if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}


  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if

$sql_rif_a = "asdasd";

}//fin for each

if($sql_rif_a==""){
  echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);

}//fin for





}else if($radio=="1"){//////////////////////////////////////////////////////////////////////////////////OPCIOPN 3





$datos_cepd01_compromiso_poremitir      =   $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";
$sql_para_por_emitir = "";
foreach($datos_cepd01_compromiso_poremitir as $aux_cepd01_compromiso_poremitir){ $ii++;

  $ano_documento_1[$ii]     =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['ano_documento'];
  $numero_documento_1[$ii]  =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['numero_documento'];


if($sql_1==""){$sql_1   .= "  ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."'  ";       $sql_para_por_emitir .= "    and   ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."')  ";  $sql_para_por_emitir .= " or  (ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."')  ";}

}//fin for


if($sql_1==""){

  echo'<script>history.back(1);</script>';

}else{


  $datos_cepd01_compromiso_cuerpo    =  $this->cepd01_compromiso_cuerpo->findAll(  $condicion." and condicion_actividad=1 and (".$sql_1.") and username_registro='".$this->Session->read('nom_usuario')."'   ", null, 'numero_documento ASC');
    $datos_cepd01_compromiso_partidas  =  $this->cepd01_compromiso_partidas->findAll($condicion." and (".$sql_1.")",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');


$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
    if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]     = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]    = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]           = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]         = 1;}

             if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
              $capital[$aux] = 1;
        }else{$corriente[$aux] = 1; }
      }//fin if
      $evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
      }//fin if
    }//fin for
                   $resultado =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
                        $rif  =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];
        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
    $direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}
  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];
if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if


}//fin for each


$datos_cpcd02    =   $this->cpcd02->findAll($sql_rif);


$sql = "delete from cepd01_compromiso_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cepd01_compromiso_poremitir->execute($sql);


}//fin else


                  }//fin else

    $this->set('direccionsup',$direccionsup);
    $this->set('coordinacion',$coordinacion);
    $this->set('secretaria',$secretaria);
    $this->set('direccion',$direccion);

     $this->set('corriente',$corriente);
   $this->set('capital',$capital);
   $this->set('ordinario',$ordinario);
   $this->set('coordinado',$coordinado);
   $this->set('laee',$laee);
   $this->set('fides',$fides);
   $this->set('ingresosextra',$ingresosextra);
   $this->set('ingresospropios',$ingresospropios);
   $this->set('fci',$fci);
   $this->set('mpps',$mpps);
   $this->set('tipodocu',$tipodocu);
   $this->set('datos_cepd01_compromiso_cuerpo',$datos_cepd01_compromiso_cuerpo);
   $this->set('datos_cepd01_compromiso_partidas',$datos_cepd01_compromiso_partidas);
     $sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ".$sql_para_por_emitir;
     $this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));

}//fin else





$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));






}//fin else


}//fin funtion



function reporte_registro_anticipo($year=null, $opcion=null, $cont=null){

   $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
$this->set('ir', 'no');
$this->Session->delete('FIN');

    $partidacorriente=407120;
  $partidacapital=407220;

if($year == "vista"){

  $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);


$url                  =  "/select_ventana_generar_reporte/v_reporte_emision_rc_generico_anticipo_1_f";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

    if($opcion==3){
           echo"<script>";
             echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
           echo"</script>";
      }else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

  }//fin else



}else{



if(!$year){


    #$this->limpia_menu();
    $this->set('ir', 'no');

    $ano = $this->ano_ejecucion();

  if(!empty($dato)){
    $this->set('year', $dato);
  }else{
    $this->set('year', '');
  }


$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);

$datos_cepd01_compromiso_poremitir = $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cepd01_compromiso_poremitir', $datos_cepd01_compromiso_poremitir);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


    $this->layout = "pdf";
    $this->set('pdf', 'si');
    $radio = 1;
    $numero_compromiso_a  = "";
    $numero_compromiso_b  = "";


         if(isset($this->data['cobd01_contratoobras_cuerpo']['radio'])){$radio = $this->data['cobd01_contratoobras_cuerpo']['radio'];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''])){     $ano_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''])){$numero_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_b'])){$numero_compromiso_b = $this->data['cobd01_contratoobras_cuerpo']['numero_b'];}




if($radio == "3" && $numero_compromiso_a!=""){////////////////////////////////////////////////OPCION 1
  $sql = "SELECT c.ano_contrato_obra,
                 c.numero_contrato_obra,
                 c.ano_estimacion,
                 c.cod_obra,
                 c.cod_estado,
                 c.cod_municipio,
                 c.cod_parroquia,
                 c.cod_centro,
                 especifique_ubicacion,
                 otorgamiento,
                 denominacion_obra,
                 c.rif,
                 fecha_contrato_obra,
                 fecha_inicio_contrato,
                 fecha_terminacion_contrato,
                 e.denominacion AS estado,
                 m.denominacion AS municipio,
                 p.denominacion AS parroquia,
                 a.denominacion AS centro,
                 c.monto_original_contrato,
                 c.aumento,
                 c.disminucion,
                 (c.monto_original_contrato + c.aumento - c.disminucion) AS monto_actual,
                 c.monto_retencion_laboral,
                 c.monto_retencion_fielcumplimiento,
                 (c.monto_retencion_laboral + c.monto_retencion_fielcumplimiento) AS monto_retenciones,
                 c.monto_cancelado,
                 c.monto_anticipo,
                 c.monto_amortizacion,
                 (c.monto_anticipo - c.monto_amortizacion) AS saldo_anticipo,
                 ((c.monto_original_contrato + c.aumento) - (c.disminucion + c.monto_cancelado + c.monto_amortizacion + c.monto_retencion_laboral +
                  c.monto_retencion_fielcumplimiento)) AS saldo_del_contrato,
                b.observaciones,
                r.denominacion AS empresa
          FROM cobd01_contratoobras_cuerpo c
          JOIN cugd01_estados e ON (c.cod_estado = e.cod_estado)
          JOIN cugd01_municipios m ON (c.cod_municipio = m.cod_municipio AND m.cod_estado = c.cod_estado)
          JOIN cugd01_parroquias p ON (c.cod_parroquia = p.cod_parroquia AND p.cod_estado = c.cod_estado AND p.cod_municipio = c.cod_municipio)
          JOIN cugd01_centros_poblados a ON (c.cod_parroquia = a.cod_parroquia AND a.cod_estado = c.cod_estado AND a.cod_municipio = c.cod_municipio AND a.cod_parroquia = p.cod_parroquia AND a.cod_centro = c.cod_centro)
          JOIN cobd01_contratoobras_anticipo_cuerpo b ON (b.ano_contrato_obra = c.ano_contrato_obra AND b.numero_contrato_obra = c.numero_contrato_obra)
          JOIN cpcd02 r ON (r.rif = c.rif)
          WHERE c.cod_obra = '$numero_compromiso_a' AND c.condicion_actividad = 1 AND c.ano_contrato_obra = $ano_compromiso_a LIMIT 1";
  $datos_cepd01_compromiso_cuerpo = $this->cobd01_contratoobras_cuerpo->query($sql);

  $sql = "SELECT p.ano,
                 cod_sector,
                 cod_programa,
                 cod_sub_prog,
                 cod_proyecto,
                 cod_activ_obra,
                 cod_partida,
                 cod_generica,
                 cod_especifica,
                 cod_sub_espec,
                 cod_auxiliar,
                 monto
      FROM cobd01_contratoobras_cuerpo c
      JOIN cobd01_contratoobras_anticipo_partidas p ON (p.ano_contrato_obra = c.ano_contrato_obra AND p.numero_contrato_obra = c.numero_contrato_obra)
       WHERE c.cod_obra = '$numero_compromiso_a' AND c.condicion_actividad = 1 AND c.ano_contrato_obra = $ano_compromiso_a";
    $datos_cepd01_compromiso_partidas = $this->cobd01_contratoobras_cuerpo->query($sql);


}else if($radio=="2" && $numero_compromiso_a!="" && $numero_compromiso_b!=""){///////////////////////////////////////OPCION 2

  $datos_cepd01_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ", null, ' numero_documento ASC');
  $datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and (numero>='$numero_compromiso_a'  and numero<='$numero_compromiso_a')  ";

$sql_rif_a = "";

$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]=0;
   $capital[$aux]=0;
   $ordinario[$aux] = 0;
   $coordinado[$aux] = 0;
   $laee[$aux] = 0;
   $fides[$aux] = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;
foreach($datos_cepd01_compromiso_partidas as $partidas){
  if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
        if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
            $capital[$aux]   = 1;
        }else{$corriente[$aux] = 1;}
      }//fin if
      $evalpartcorriente=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
        }//fin if
    }//fin for

$resultado=$this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];



        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));

    $direccionsup[$aux] = $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux] = $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]   = $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]    = $cugd02_direccion[0]['cugd02_direccion']['denominacion'];

if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}


  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if

$sql_rif_a = "asdasd";

}//fin for each

if($sql_rif_a==""){
  echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);

}//fin for





}else if($radio=="1"){//////////////////////////////////////////////////////////////////////////////////OPCIOPN 3





$datos_cepd01_compromiso_poremitir      =   $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";
$sql_para_por_emitir = "";
foreach($datos_cepd01_compromiso_poremitir as $aux_cepd01_compromiso_poremitir){ $ii++;

  $ano_documento_1[$ii]     =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['ano_documento'];
  $numero_documento_1[$ii]  =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['numero_documento'];


if($sql_1==""){$sql_1   .= "  ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."'  ";       $sql_para_por_emitir .= "    and   ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."')  ";  $sql_para_por_emitir .= " or  (ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."')  ";}

}//fin for


if($sql_1==""){

  echo'<script>history.back(1);</script>';

}else{


  $datos_cepd01_compromiso_cuerpo    =  $this->cepd01_compromiso_cuerpo->findAll(  $condicion." and condicion_actividad=1 and (".$sql_1.") and username_registro='".$this->Session->read('nom_usuario')."'   ", null, 'numero_documento ASC');
    $datos_cepd01_compromiso_partidas  =  $this->cepd01_compromiso_partidas->findAll($condicion." and (".$sql_1.")",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');


$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
    if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]     = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]    = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]           = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]         = 1;}

             if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
              $capital[$aux] = 1;
        }else{$corriente[$aux] = 1; }
      }//fin if
      $evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      }//fin if
    }//fin for
                   $resultado =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
                        $rif  =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];
        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
    $direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}
  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];
if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if


}//fin for each


$datos_cpcd02    =   $this->cpcd02->findAll($sql_rif);


$sql = "delete from cepd01_compromiso_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cepd01_compromiso_poremitir->execute($sql);


}//fin else


                  }//fin else

    $this->set('direccionsup',$direccionsup);
    $this->set('coordinacion',$coordinacion);
    $this->set('secretaria',$secretaria);
    $this->set('direccion',$direccion);

     $this->set('corriente',$corriente);
   $this->set('capital',$capital);
   $this->set('ordinario',$ordinario);
   $this->set('coordinado',$coordinado);
   $this->set('laee',$laee);
   $this->set('fides',$fides);
   $this->set('ingresosextra',$ingresosextra);
   $this->set('ingresospropios',$ingresospropios);
   $this->set('fci',$fci);
   $this->set('mpps',$mpps);
   $this->set('tipodocu',$tipodocu);
   $this->set('datos_cepd01_compromiso_cuerpo',$datos_cepd01_compromiso_cuerpo);
   $this->set('datos_cepd01_compromiso_partidas',$datos_cepd01_compromiso_partidas);
     $sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ".$sql_para_por_emitir;
     $this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));

}//fin else





$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin else


}//fin funtion



function reporte_registro_anticipo_servicio($year=null, $opcion=null, $cont=null){

   $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
$this->set('ir', 'no');
$this->Session->delete('FIN');

    $partidacorriente=407120;
  $partidacapital=407220;

if($year == "vista"){

  $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);


$url                  =  "/select_ventana_generar_reporte/v_reporte_emision_rc_generico_anticipo_1_servicio_f";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

    if($opcion==3){
           echo"<script>";
             echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
           echo"</script>";
      }else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

  }//fin else



}else{



if(!$year){


    #$this->limpia_menu();
    $this->set('ir', 'no');

    $ano = $this->ano_ejecucion();

  if(!empty($dato)){
    $this->set('year', $dato);
  }else{
    $this->set('year', '');
  }


$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);

$datos_cepd01_compromiso_poremitir = $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cepd01_compromiso_poremitir', $datos_cepd01_compromiso_poremitir);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


    $this->layout = "pdf";
    $this->set('pdf', 'si');
    $radio = 1;
    $numero_compromiso_a  = "";
    $numero_compromiso_b  = "";


         if(isset($this->data['cobd01_contratoobras_cuerpo']['radio'])){$radio = $this->data['cobd01_contratoobras_cuerpo']['radio'];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''])){     $ano_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''])){$numero_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_b'])){$numero_compromiso_b = $this->data['cobd01_contratoobras_cuerpo']['numero_b'];}




if($radio == "3" && $numero_compromiso_a!=""){////////////////////////////////////////////////OPCION 1
  $sql = "SELECT c.ano_contrato_servicio,
                  c.numero_contrato_servicio,
                  codigo_prod_serv,
                  c.cod_dir_superior,
                  c.cod_coordinacion,
                  c.cod_secretaria,
                  c.cod_direccion,
                  c.rif,
                  concepto,
                  fecha_contrato_servicio,
                  fecha_inicio_contrato,
                  fecha_terminacion_contrato,
                  d.denominacion AS direccion_superior,
                  p.denominacion AS cordinacion,
                  s.denominacion AS secretaria,
                  x.denominacion AS direccion,
                  c.monto_original_contrato,
                 c.aumento,
                 c.disminucion,
                 (c.monto_original_contrato + c.aumento - c.disminucion) AS monto_actual,
                 c.monto_retencion_laboral,
                 c.monto_retencion_fielcumplimiento,
                 (c.monto_retencion_laboral + c.monto_retencion_fielcumplimiento) AS monto_retenciones,
                 c.monto_cancelado,
                 c.monto_anticipo,
                 c.monto_amortizacion,
                 (c.monto_anticipo - c.monto_amortizacion) AS saldo_anticipo,
                 ((c.monto_original_contrato + c.aumento) - (c.disminucion + c.monto_cancelado + c.monto_amortizacion + c.monto_retencion_laboral +
                  c.monto_retencion_fielcumplimiento)) AS saldo_del_contrato,
                  a.observaciones,
                  t.denominacion AS empresa
                  FROM cepd02_contratoservicio_cuerpo c
        JOIN cugd02_direccionsuperior d ON (c.cod_dir_superior = d.cod_dir_superior AND c.cod_tipo_inst = d.cod_tipo_institucion AND c.cod_inst = d.cod_institucion AND c.cod_dep = d.cod_dependencia)
        JOIN cugd02_coordinacion p ON (p.cod_coordinacion = c.cod_coordinacion AND c.cod_dir_superior = p.cod_dir_superior AND c.cod_tipo_inst = p.cod_tipo_institucion AND c.cod_inst = p.cod_institucion AND c.cod_dep = p.cod_dependencia)
        JOIN cugd02_secretaria s ON (s.cod_secretaria = c.cod_secretaria AND s.cod_coordinacion = c.cod_coordinacion AND c.cod_dir_superior = s.cod_dir_superior AND c.cod_tipo_inst = s.cod_tipo_institucion AND c.cod_inst = s.cod_institucion AND c.cod_dep = s.cod_dependencia)
        JOIN cugd02_direccion x ON (x.cod_direccion = c.cod_direccion AND x.cod_secretaria = c.cod_secretaria AND x.cod_coordinacion = c.cod_coordinacion AND c.cod_dir_superior = x.cod_dir_superior AND c.cod_tipo_inst = x.cod_tipo_institucion AND c.cod_inst = x.cod_institucion AND c.cod_dep = x.cod_dependencia)
  LEFT JOIN cepd02_contratoservicio_anticipo_cuerpo a ON (a.numero_contrato_servicio = c.numero_contrato_servicio AND c.ano_contrato_servicio = a.ano_contrato_servicio)
  JOIN cpcd02 t ON (t.rif = c.rif)
        WHERE c.numero_contrato_servicio = '$numero_compromiso_a' AND c.ano_contrato_servicio = $ano_compromiso_a AND c.condicion_actividad = 1";
  $datos_cepd01_compromiso_cuerpo = $this->cobd01_contratoobras_cuerpo->query($sql);

  $sql = "SELECT p.ano,
                 cod_sector,
                 cod_programa,
                 cod_sub_prog,
                 cod_proyecto,
                 cod_activ_obra,
                 cod_partida,
                 cod_generica,
                 cod_especifica,
                 cod_sub_espec,
                 cod_auxiliar,
                 monto
      FROM cepd02_contratoservicio_cuerpo c
      JOIN cepd02_contratoservicio_anticipo_partidas p ON (p.numero_contrato_servicio = c.numero_contrato_servicio AND p.ano_contrato_servicio = c.ano_contrato_servicio)
       WHERE c.numero_contrato_servicio = '$numero_compromiso_a' AND c.condicion_actividad = 1 AND c.ano_contrato_servicio = $ano_compromiso_a";

    $datos_cepd01_compromiso_partidas = $this->cobd01_contratoobras_cuerpo->query($sql);
    #echo $condicion." and condicion_actividad=1 and ano_contrato_obra=".$ano_compromiso_a."  and numero_contrato_obra='$numero_compromiso_a' ";
  #$datos_cepd01_compromiso_cuerpo=$this->cobd01_contratoobras_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_contrato_obra=".$ano_compromiso_a."  and cod_obra='$numero_compromiso_a' ", null, ' cod_obra ASC');
  #$datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and numero_documento='$numero_compromiso_a' ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');
  #print_r($datos_cepd01_compromiso_cuerpo);
/*
$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and numero='$numero_compromiso_a' ";


$sql_rif_a ="";

$aux= 0;
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
        if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
            $capital[$aux]   = 1;
        }else{$corriente[$aux] = 1;}
      }//fin if
      $evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
}//fin for

$resultado  =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif        =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];

        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
    $direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
      $ano_documento      =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
      $numero_documento   =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
      $monto              =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if
$sql_rif_a = "asdsad";

}//fin foreach de cuerpo


if($sql_rif_a==""){
  echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02      =   $this->cpcd02->findAll(" rif='".$rif."' ");

}//fin fopr


*/



}else if($radio=="2" && $numero_compromiso_a!="" && $numero_compromiso_b!=""){///////////////////////////////////////OPCION 2

  $datos_cepd01_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ", null, ' numero_documento ASC');
  $datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and (numero>='$numero_compromiso_a'  and numero<='$numero_compromiso_a')  ";

$sql_rif_a = "";

$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]=0;
   $capital[$aux]=0;
   $ordinario[$aux] = 0;
   $coordinado[$aux] = 0;
   $laee[$aux] = 0;
   $fides[$aux] = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;
foreach($datos_cepd01_compromiso_partidas as $partidas){
  if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
        if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
            $capital[$aux]   = 1;
        }else{$corriente[$aux] = 1;}
      }//fin if
      $evalpartcorriente=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
        }//fin if
    }//fin for

$resultado=$this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];



        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));

    $direccionsup[$aux] = $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux] = $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]   = $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]    = $cugd02_direccion[0]['cugd02_direccion']['denominacion'];

if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}


  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if

$sql_rif_a = "asdasd";

}//fin for each

if($sql_rif_a==""){
  echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);

}//fin for





}else if($radio=="1"){//////////////////////////////////////////////////////////////////////////////////OPCIOPN 3





$datos_cepd01_compromiso_poremitir      =   $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";
$sql_para_por_emitir = "";
foreach($datos_cepd01_compromiso_poremitir as $aux_cepd01_compromiso_poremitir){ $ii++;

  $ano_documento_1[$ii]     =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['ano_documento'];
  $numero_documento_1[$ii]  =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['numero_documento'];


if($sql_1==""){$sql_1   .= "  ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."'  ";       $sql_para_por_emitir .= "    and   ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."')  ";  $sql_para_por_emitir .= " or  (ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."')  ";}

}//fin for


if($sql_1==""){

  echo'<script>history.back(1);</script>';

}else{


  $datos_cepd01_compromiso_cuerpo    =  $this->cepd01_compromiso_cuerpo->findAll(  $condicion." and condicion_actividad=1 and (".$sql_1.") and username_registro='".$this->Session->read('nom_usuario')."'   ", null, 'numero_documento ASC');
    $datos_cepd01_compromiso_partidas  =  $this->cepd01_compromiso_partidas->findAll($condicion." and (".$sql_1.")",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');


$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
    if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]     = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]    = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]           = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]         = 1;}

             if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
              $capital[$aux] = 1;
        }else{$corriente[$aux] = 1; }
      }//fin if
      $evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
      }//fin if
    }//fin for
                   $resultado =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
                        $rif  =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];
        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
    $direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}
  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];
if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if


}//fin for each


$datos_cpcd02    =   $this->cpcd02->findAll($sql_rif);


$sql = "delete from cepd01_compromiso_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cepd01_compromiso_poremitir->execute($sql);


}//fin else


                  }//fin else

    $this->set('direccionsup',$direccionsup);
    $this->set('coordinacion',$coordinacion);
    $this->set('secretaria',$secretaria);
    $this->set('direccion',$direccion);

     $this->set('corriente',$corriente);
   $this->set('capital',$capital);
   $this->set('ordinario',$ordinario);
   $this->set('coordinado',$coordinado);
   $this->set('laee',$laee);
   $this->set('fides',$fides);
   $this->set('ingresosextra',$ingresosextra);
   $this->set('ingresospropios',$ingresospropios);
   $this->set('fci',$fci);
   $this->set('mpps',$mpps);
   $this->set('tipodocu',$tipodocu);
   $this->set('datos_cepd01_compromiso_cuerpo',$datos_cepd01_compromiso_cuerpo);
   $this->set('datos_cepd01_compromiso_partidas',$datos_cepd01_compromiso_partidas);
     $sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ".$sql_para_por_emitir;
     $this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));

}//fin else





$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));






}//fin else


}//fin funtion



function reporte_registro_valuacion_servicio($year=null, $opcion=null, $cont=null){

   $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
$this->set('ir', 'no');
$this->Session->delete('FIN');

    $partidacorriente=407120;
  $partidacapital=407220;

if($year == "vista"){

  $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);


$url                  =  "/select_ventana_generar_reporte/v_reporte_emision_rc_generico_valuacion_1_servicio_f";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

    if($opcion==3){
           echo"<script>";
             echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
           echo"</script>";
      }else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

  }//fin else



}else{



if(!$year){


    #$this->limpia_menu();
    $this->set('ir', 'no');

    $ano = $this->ano_ejecucion();

  if(!empty($dato)){
    $this->set('year', $dato);
  }else{
    $this->set('year', '');
  }


$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);

$datos_cepd01_compromiso_poremitir = $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cepd01_compromiso_poremitir', $datos_cepd01_compromiso_poremitir);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


    $this->layout = "pdf";
    $this->set('pdf', 'si');
    $radio = 1;
    $numero_compromiso_a  = "";
    $numero_compromiso_b  = "";


         if(isset($this->data['cobd01_contratoobras_cuerpo']['radio'])){$radio = $this->data['cobd01_contratoobras_cuerpo']['radio'];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''])){     $ano_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['ano'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''])){$numero_compromiso_a = $this->data['cobd01_contratoobras_cuerpo']['numero_a'.$cont.''];}
       if(isset($this->data['cobd01_contratoobras_cuerpo']['numero_b'])){$numero_compromiso_b = $this->data['cobd01_contratoobras_cuerpo']['numero_b'];}




if($radio == "3" && $numero_compromiso_a!=""){////////////////////////////////////////////////OPCION 1
  $sql = "SELECT c.ano_contrato_servicio,
                  c.numero_contrato_servicio,
                  codigo_prod_serv,
                  c.cod_dir_superior,
                  c.cod_coordinacion,
                  c.cod_secretaria,
                  c.cod_direccion,
                  c.rif,
                  c.concepto,
                  fecha_contrato_servicio,
                  fecha_inicio_contrato,
                  fecha_terminacion_contrato,
                  d.denominacion AS direccion_superior,
                  p.denominacion AS cordinacion,
                  s.denominacion AS secretaria,
                  x.denominacion AS direccion,
                  c.monto_original_contrato,
                 c.aumento,
                 c.disminucion,
                 (c.monto_original_contrato + c.aumento - c.disminucion) AS monto_actual,
                 c.monto_retencion_laboral,
                 c.monto_retencion_fielcumplimiento,
                 (c.monto_retencion_laboral + c.monto_retencion_fielcumplimiento) AS monto_retenciones,
                 c.monto_cancelado,
                 c.monto_anticipo,
                 c.monto_amortizacion,
                 (c.monto_anticipo - c.monto_amortizacion) AS saldo_anticipo,
                 ((c.monto_original_contrato + c.aumento) - (c.disminucion + c.monto_cancelado + c.monto_amortizacion + c.monto_retencion_laboral +
                  c.monto_retencion_fielcumplimiento)) AS saldo_del_contrato,
                  a.concepto AS observaciones,
                  t.denominacion AS empresa
                  FROM cepd02_contratoservicio_cuerpo c
        JOIN cugd02_direccionsuperior d ON (c.cod_dir_superior = d.cod_dir_superior AND c.cod_tipo_inst = d.cod_tipo_institucion AND c.cod_inst = d.cod_institucion AND c.cod_dep = d.cod_dependencia)
        JOIN cugd02_coordinacion p ON (p.cod_coordinacion = c.cod_coordinacion AND c.cod_dir_superior = p.cod_dir_superior AND c.cod_tipo_inst = p.cod_tipo_institucion AND c.cod_inst = p.cod_institucion AND c.cod_dep = p.cod_dependencia)
        JOIN cugd02_secretaria s ON (s.cod_secretaria = c.cod_secretaria AND s.cod_coordinacion = c.cod_coordinacion AND c.cod_dir_superior = s.cod_dir_superior AND c.cod_tipo_inst = s.cod_tipo_institucion AND c.cod_inst = s.cod_institucion AND c.cod_dep = s.cod_dependencia)
        JOIN cugd02_direccion x ON (x.cod_direccion = c.cod_direccion AND x.cod_secretaria = c.cod_secretaria AND x.cod_coordinacion = c.cod_coordinacion AND c.cod_dir_superior = x.cod_dir_superior AND c.cod_tipo_inst = x.cod_tipo_institucion AND c.cod_inst = x.cod_institucion AND c.cod_dep = x.cod_dependencia)
  LEFT JOIN cepd02_contratoservicio_valuacion_cuerpo a ON (a.numero_contrato_servicio = c.numero_contrato_servicio AND c.ano_contrato_servicio = a.ano_contrato_servicio)
  JOIN cpcd02 t ON (t.rif = c.rif)
        WHERE c.numero_contrato_servicio = '$numero_compromiso_a' AND c.ano_contrato_servicio = $ano_compromiso_a AND c.condicion_actividad = 1 LIMIT 1";
  //echo $sql;
  $datos_cepd01_compromiso_cuerpo = $this->cobd01_contratoobras_cuerpo->query($sql);

  $sql = "SELECT p.ano,
                 cod_sector,
                 cod_programa,
                 cod_sub_prog,
                 cod_proyecto,
                 cod_activ_obra,
                 cod_partida,
                 cod_generica,
                 cod_especifica,
                 cod_sub_espec,
                 cod_auxiliar,
                 monto
      FROM cepd02_contratoservicio_cuerpo c
      JOIN cepd02_contratoservicio_valuacion_partidas p ON (p.ano_contrato_servicio = c.ano_contrato_servicio AND p.numero_contrato_servicio = c.numero_contrato_servicio)
       WHERE c.numero_contrato_servicio = '$numero_compromiso_a' AND c.condicion_actividad = 1 AND c.ano_contrato_servicio = $ano_compromiso_a";
    $datos_cepd01_compromiso_partidas = $this->cobd01_contratoobras_cuerpo->query($sql);
    #echo $condicion." and condicion_actividad=1 and ano_contrato_obra=".$ano_compromiso_a."  and numero_contrato_obra='$numero_compromiso_a' ";
  #$datos_cepd01_compromiso_cuerpo=$this->cobd01_contratoobras_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_contrato_obra=".$ano_compromiso_a."  and cod_obra='$numero_compromiso_a' ", null, ' cod_obra ASC');
  #$datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and numero_documento='$numero_compromiso_a' ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');
  #print_r($datos_cepd01_compromiso_cuerpo);
/*
$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and numero='$numero_compromiso_a' ";


$sql_rif_a ="";

$aux= 0;
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
        if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
            $capital[$aux]   = 1;
        }else{$corriente[$aux] = 1;}
      }//fin if
      $evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
}//fin for

$resultado  =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif        =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];

        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
    $direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
      $ano_documento      =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
      $numero_documento   =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
      $monto              =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if
$sql_rif_a = "asdsad";

}//fin foreach de cuerpo


if($sql_rif_a==""){
  echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02      =   $this->cpcd02->findAll(" rif='".$rif."' ");

}//fin fopr


*/



}else if($radio=="2" && $numero_compromiso_a!="" && $numero_compromiso_b!=""){///////////////////////////////////////OPCION 2

  $datos_cepd01_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ", null, ' numero_documento ASC');
  $datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and (numero>='$numero_compromiso_a'  and numero<='$numero_compromiso_a')  ";

$sql_rif_a = "";

$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]=0;
   $capital[$aux]=0;
   $ordinario[$aux] = 0;
   $coordinado[$aux] = 0;
   $laee[$aux] = 0;
   $fides[$aux] = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;
foreach($datos_cepd01_compromiso_partidas as $partidas){
  if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
        if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
            $capital[$aux]   = 1;
        }else{$corriente[$aux] = 1;}
      }//fin if
      $evalpartcorriente=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
        }//fin if
    }//fin for

$resultado=$this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];



        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));

    $direccionsup[$aux] = $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux] = $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]   = $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]    = $cugd02_direccion[0]['cugd02_direccion']['denominacion'];

if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}


  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if

$sql_rif_a = "asdasd";

}//fin for each

if($sql_rif_a==""){
  echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);

}//fin for





}else if($radio=="1"){//////////////////////////////////////////////////////////////////////////////////OPCIOPN 3





$datos_cepd01_compromiso_poremitir      =   $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";
$sql_para_por_emitir = "";
foreach($datos_cepd01_compromiso_poremitir as $aux_cepd01_compromiso_poremitir){ $ii++;

  $ano_documento_1[$ii]     =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['ano_documento'];
  $numero_documento_1[$ii]  =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['numero_documento'];


if($sql_1==""){$sql_1   .= "  ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."'  ";       $sql_para_por_emitir .= "    and   ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."')  ";  $sql_para_por_emitir .= " or  (ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."')  ";}

}//fin for


if($sql_1==""){

  echo'<script>history.back(1);</script>';

}else{


  $datos_cepd01_compromiso_cuerpo    =  $this->cepd01_compromiso_cuerpo->findAll(  $condicion." and condicion_actividad=1 and (".$sql_1.") and username_registro='".$this->Session->read('nom_usuario')."'   ", null, 'numero_documento ASC');
    $datos_cepd01_compromiso_partidas  =  $this->cepd01_compromiso_partidas->findAll($condicion." and (".$sql_1.")",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');


$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
    if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
      $sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
             if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]     = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]    = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]           = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux] = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]          = 1;
        }elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]         = 1;}

             if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
              $capital[$aux] = 1;
        }else{$corriente[$aux] = 1; }
      }//fin if
      $evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      $evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
      //if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
      //if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
      }//fin if
    }//fin for
                   $resultado =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
                        $rif  =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];
        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
    $cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
    $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
    $cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
    $direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
    $coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
    $secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
    $direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}
  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];
if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if


}//fin for each


$datos_cpcd02    =   $this->cpcd02->findAll($sql_rif);


$sql = "delete from cepd01_compromiso_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cepd01_compromiso_poremitir->execute($sql);


}//fin else


                  }//fin else

    $this->set('direccionsup',$direccionsup);
    $this->set('coordinacion',$coordinacion);
    $this->set('secretaria',$secretaria);
    $this->set('direccion',$direccion);

     $this->set('corriente',$corriente);
   $this->set('capital',$capital);
   $this->set('ordinario',$ordinario);
   $this->set('coordinado',$coordinado);
   $this->set('laee',$laee);
   $this->set('fides',$fides);
   $this->set('ingresosextra',$ingresosextra);
   $this->set('ingresospropios',$ingresospropios);
   $this->set('fci',$fci);
   $this->set('mpps',$mpps);
   $this->set('tipodocu',$tipodocu);
   $this->set('datos_cepd01_compromiso_cuerpo',$datos_cepd01_compromiso_cuerpo);
   $this->set('datos_cepd01_compromiso_partidas',$datos_cepd01_compromiso_partidas);
     $sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ".$sql_para_por_emitir;
     $this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));

}//fin else





$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));






}//fin else


}//fin funtion





}//fin class