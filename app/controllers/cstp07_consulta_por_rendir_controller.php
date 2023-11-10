<?php

class Cstp07ConsultaPorRendirController extends AppController{


    var $name    = "cstp07_consulta_por_rendir";
    var $uses    = array('cugd02_dependencia','ccfd04_cierre_mes', 'cepd03_ordenpago_facturas', 'cstd07_retenciones_cuerpo_iva',
                         'cstd07_retenciones_cuerpo_islr','cstd07_retenciones_cuerpo_municipal','cstd07_retenciones_cuerpo_timbre',
                         'cepd03_ordenpago_cuerpo', 'cstd07_retenciones_cuerpo_multa', 'cstd07_retenciones_cuerpo_responsabilidad', 'cstd07_retenciones_partidas_multa', 'cstd07_retenciones_partidas_responsabilidad',
                         'cstd06_comprobante_poremitir_multa', 'cstd06_comprobante_poremitir_responsabilidad', 'cstd06_comprobante_numero_multa', 'cstd06_comprobante_numero_responsabilidad',
                         'cstd06_comprobante_cuerpo_multa', 'cstd06_comprobante_cuerpo_responsabilidad',"cstd07_retenciones_cuerpo_multa_consutal", "cstd07_retenciones_cuerpo_resp_consutal");
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








function index(){
$this->layout = "ajax";

$rs=$this->cugd02_dependencia->findCount("cod_dependencia<>1");
if($rs==0){

	   echo'<script>';
	        echo "fun_msj('No se han registrado Dependencias');";
		echo'</script>';

		echo " <script type='text/javascript'>
	                window.parent.ver_documento('/administradors/vacio','principal');
               </script>";

}


}//fin function







function generar_diskett_iva($desde=NULL, $hasta=NULL ){


  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();
  $cadena="";
  $cond3= "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
  $cugd02_dependencia =  $this->cugd02_dependencia->findAll($cond3);
///////////////////////////ARCHIVO PLANO//////////////////////////////
$cond  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano_orden_pago=".$ano." and (fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
$cond2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
$datos_cuerpo_iva=$this->cstd07_retenciones_cuerpo_iva->findAll($cond,array('cod_dep','ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'cod_dep, numero_orden_pago ASC');
$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano_orden_pago=".$ano."";
foreach($datos_cuerpo_iva as $ve2){
	foreach($cugd02_dependencia as $aux_dependencia){ $tipo_dependencia = 0;
                                      if($ve2['cstd07_retenciones_cuerpo_iva']['cod_dep']==$aux_dependencia['cugd02_dependencia']['cod_dependencia']){
                                              $tipo_dependencia = $aux_dependencia['cugd02_dependencia']['tipo_dependencia']; break;
                                      }//fin if
				 	}//fin for
		if($tipo_dependencia==1){
			 $datos_cepd03_ordenpago_facturas = $this->cepd03_ordenpago_facturas->findAll($cond. ' and cod_dep='.$ve2['cstd07_retenciones_cuerpo_iva']['cod_dep'].'  and  ano_orden_pago='.$ve2['cstd07_retenciones_cuerpo_iva']['ano_orden_pago'].'  and  numero_orden_pago='.$ve2['cstd07_retenciones_cuerpo_iva']['numero_orden_pago']);
             $datos_ordenes = $this->cepd03_ordenpago_cuerpo->findAll($cond. ' and cod_dep='.$ve2['cstd07_retenciones_cuerpo_iva']['cod_dep'].' and  ano_orden_pago='.$ve2['cstd07_retenciones_cuerpo_iva']['ano_orden_pago'].'  and  numero_orden_pago='.$ve2['cstd07_retenciones_cuerpo_iva']['numero_orden_pago']);
			 $fecha_orden_pago  = $datos_ordenes[0]['cepd03_ordenpago_cuerpo']['fecha_orden_pago'];
			 $fecha_cheque_comp = $datos_ordenes[0]['cepd03_ordenpago_cuerpo']['fecha_cheque'];
			 $mes_comprobante  =  $fecha_cheque_comp[5].$fecha_cheque_comp[6];
			 $dia_comprobante  =  $fecha_cheque_comp[8].$fecha_cheque_comp[9];
			foreach($datos_cepd03_ordenpago_facturas as $aux_datos_cepd03_ordenpago_facturas){
					  $cod_presi2             =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['cod_presi'];
					  $cod_entidad2           =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['cod_entidad'];
					  $cod_tipo_inst2         =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['cod_tipo_inst'];
					  $cod_inst2              =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['cod_inst'];
					  $cod_dep2               =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['cod_dep'];
					  $ano_orden_pago2        =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['ano_orden_pago'];
					  $numero_orden_pago2     =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['numero_orden_pago'];
					  $numero_factura         =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['numero_factura'];
					  $numero_control         =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['numero_control'];
					  $fecha_factura          =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['fecha_factura'];
					  $monto_total_factura    =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['monto_total_factura'];
					  $monto_sub_total        =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['monto_sub_total'];
					  $porcentaje_iva         =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['porcentaje_iva'];
					  $monto_exento           =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['monto_exento'];
					  $monto_iva              =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['monto_iva'];
					  $monto_retencion_iva    =         $aux_datos_cepd03_ordenpago_facturas['cepd03_ordenpago_facturas']['monto_retencion_iva'];
					$datos_institucion =  $this->cugd02_dependencia->findAll("cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep." ");
					if($datos_institucion[0]['cugd02_dependencia']['tipo_dependencia']==1){
					 $datos_institucion =  $this->cugd02_dependencia->findAll("cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=1");
					 $rif_institucion   =  $datos_institucion[0]['cugd02_dependencia']['rif'];
					   }else{
					 $rif_institucion   =  $datos_institucion[0]['cugd02_dependencia']['rif'];
					}//fin else
					//$periodo          =  $fecha_factura[0].$fecha_factura[1].$fecha_factura[2].$fecha_factura[3].$fecha_factura[5].$fecha_factura[6];
					$periodo            =  date('Y').$mes_comprobante;
					$comprobante_iva    =  date('Y').$mes_comprobante.$this->mascara_cuatro($cod_dep2).$this->mascara_cuatro($datos_ordenes[0]['cepd03_ordenpago_cuerpo']['numero_comprobante_iva']);
					$rif_empresa        =  str_replace("-","",$datos_ordenes[0]['cepd03_ordenpago_cuerpo']['rif']);
					$cadena .= "".str_replace("-","",$rif_institucion)."\t".$periodo."\t".$fecha_factura."\tC\t01\t".$rif_empresa."\t".$numero_factura."\t".$numero_control."\t".$monto_total_factura."\t".$monto_sub_total."\t".$monto_retencion_iva."\t0\t".$comprobante_iva."\t".$monto_exento."\t".$porcentaje_iva."\t0\n";
			}//fin
		}//fin
 }//fin foreach
$this->wFile('riva_'.$cod_dep.'_'.date('d_m_Y'), $cadena);
if(file_exists('../webroot/descargas/riva_'.$cod_dep.'_'.date('d_m_Y').'.txt')){chmod('../webroot/descargas/riva_'.$cod_dep.'_'.date('d_m_Y').'.txt', 0777);}
$this->set('name', 'riva_'.$cod_dep.'_'.date('d_m_Y'));
///////////////////////////FIN ARCHIVO PLANO//////////////////////////////////
}//fin function




function index_retenciones_impuestos($var=null){

$this->layout = "ajax";
$this->set('opcion',$var);

     echo'<script>';
	        echo"document.getElementById('ir').disabled  = false; ";
		echo'</script>';

}//fin function







function retenciones_impuestos(){
 	$this->layout ="ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$ano = $this->ano_ejecucion();

	$var   = $this->data['cstp07_consulta_por_rendir']['opcion'];
	$desde = $this->Cfecha($this->data['cstp07_consulta_por_rendir']['desde_periodo'], 'A-M-D');
	$hasta = $this->Cfecha($this->data['cstp07_consulta_por_rendir']['hasta_periodo'], 'A-M-D');

	$opcion1 = $this->data['cstp07_consulta_por_rendir']['opcion1'];

	$this->set('desde', $desde);
	$this->set('hasta', $hasta);
	$this->set('opcion1', $opcion1);

 	$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and (status=1 or status=3) and ano_orden_pago=".$ano." and (fecha_proceso_registro BETWEEN '$desde' AND '$hasta')";
 	$cond2= "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
 	$cond3= "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
 	switch($var){
 	     case '1':  $datos_cuerpo_iva=$this->cstd07_retenciones_cuerpo_iva->findAll($cond,array('cod_dep','ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'cod_dep, numero_orden_pago ASC');
					$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond2,array('cod_dep','numero_orden_pago','beneficiario'));
					$this->set('datos_ordenpago',$datos_ordenpago);
					$this->set('datos_cuerpo_impuesto',$datos_cuerpo_iva);
					$this->set('titulo','RETENCIÓN  I.V.A.');
					$this->set('var',$var);
 	      	   break;

 		 case '2':  $datos_cuerpo_isrl=$this->cstd07_retenciones_cuerpo_islr->findAll($cond,array('cod_dep','ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro','status'),'cod_dep, numero_orden_pago ASC');
					$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond2,array('cod_dep','numero_orden_pago','beneficiario'));
					$this->set('datos_ordenpago',$datos_ordenpago);
					$this->set('datos_cuerpo_impuesto',$datos_cuerpo_isrl);
					$this->set('titulo','RETENCIÓN I.S.L.R.');
					$this->set('var',$var);
 	      	   break;

 	     case '3':  $datos_cuerpo_timbre=$this->cstd07_retenciones_cuerpo_timbre->findAll($cond,array('cod_dep','ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro','status'),'cod_dep, numero_orden_pago ASC');
					$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond2,array('cod_dep','numero_orden_pago','beneficiario'));
					$this->set('datos_ordenpago',$datos_ordenpago);
					$this->set('datos_cuerpo_impuesto',$datos_cuerpo_timbre);
					$this->set('titulo','RETENCIÓN TIMBRE FISCAL');
					$this->set('var',$var);
 	     		break;

 	     case '4': 	$datos_cuerpo_impmunicipal=$this->cstd07_retenciones_cuerpo_municipal->findAll($cond,array('cod_dep','ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'cod_dep, numero_orden_pago ASC');
					$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond2,array('cod_dep','numero_orden_pago','beneficiario'));
					$this->set('datos_ordenpago',$datos_ordenpago);
					$this->set('datos_cuerpo_impuesto',$datos_cuerpo_impmunicipal);
					$this->set('titulo','RETENCIÓN IMPUESTO MUNICIPAL');
					$this->set('var',$var);
 	     		break;


 	     case '5': 	$datos_cuerpo_multa=$this->cstd07_retenciones_cuerpo_multa->findAll($cond,array('cod_dep','ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'cod_dep, numero_orden_pago ASC');
					$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond2,array('cod_dep','numero_orden_pago','beneficiario'));
					$this->set('datos_ordenpago',$datos_ordenpago);
					$this->set('datos_cuerpo_impuesto',$datos_cuerpo_multa);
					$this->set('titulo','RETENCIÓN RESPONSABILIDAD CIVIL');
					$this->set('var',$var);
 	     		break;

 	     case '6': 	$datos_cuerpo_responsabilidad=$this->cstd07_retenciones_cuerpo_responsabilidad->findAll($cond,array('cod_dep','ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'cod_dep, numero_orden_pago ASC');
					$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond2,array('cod_dep','numero_orden_pago','beneficiario'));
					$this->set('datos_ordenpago',$datos_ordenpago);
					$this->set('datos_cuerpo_impuesto',$datos_cuerpo_responsabilidad);
					$this->set('titulo','RETENCIÓN RESPONSABILIDAD SOCIAL');
					$this->set('var',$var);
 	     		break;




 	}


 	$this->set('cugd02_dependencia', $this->cugd02_dependencia->findAll($cond3));


 }//retenciones_impuestos





}//fin class
?>