<?php

class ReporteCierreEjercicioController extends AppController{


    var $name    = "reporte_cierre_ejercicio";
    var $uses    = array('ccfd04_cierre_mes', "v_documentos_comprometidos_no_causados", "v_nivel_ejecucion_presupuestaria",
                         "v_documentos_causados_no_pagados", "v_obras_comp_cau_pag_no_pag", "v_obras_comp_cau_pag_no_pag_order",
                         "v_reintegro_cuenta_bancaria",'cugd02_dependencia');
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









function forma_cierre_1($var1=null, $var2=null){set_time_limit(0);



      if($var1==1){

					      	    $this->layout = "ajax";

								$cod_presi     = $this->Session->read('SScodpresi');
								$cod_entidad   = $this->Session->read('SScodentidad');
								$cod_tipo_inst = $this->Session->read('SScodtipoinst');
								$cod_inst      = $this->Session->read('SScodinst');
								$cod_dep       = $this->Session->read('SScoddep');


							        for($minCount = 2007; $minCount < 2020; $minCount++) {
								     $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
								     $this->set('anos',$anos);
							        }
							        $this->set('ano_ejecucion', $this->ano_ejecucion());



}else if($var1==2){

                     $this->layout = "pdf";

                     if(!empty($this->data['datos']['consolidacion'])){
  	         	         $consolidado     = $this->data['datos']['consolidacion'];
  	         	         $consolidado_aux = $this->data['datos']['consolidacion'];
  	                 }else{
  	                 	 $consolidado     = 2;
  	                 	 $consolidado_aux = 2;

  	                 }
				          if($consolidado==2){
				  	     	$consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }else if($consolidado==1){
				  		    $consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }
                     if(!empty($this->data['datos']['ano'])){
  	         	           $sql = $consolidado." and ano=".$this->data['datos']['ano'];
  	                 }else{
  	                 	   $sql = $consolidado;
  	                 }
  	                 if(!empty($this->data['reporte']['fecha_desde']) && !empty($this->data['reporte']['fecha_hasta'])){
						$fecha_desde = $this->data['reporte']['fecha_desde'];
					    $fecha_hasta = $this->data['reporte']['fecha_hasta'];
					    $fecha_sql   = "and (fecha_documento BETWEEN '".$fecha_desde."' AND '$fecha_hasta')";
					}else{
	                    $fecha_sql   = "";
	                    $fecha_desde = "";
	                    $fecha_hasta = "";
				    }
				    $sql .= $fecha_sql;
                    $this->set("fecha_desde", $fecha_desde);
				    $this->set("fecha_hasta", $fecha_hasta);

  	                 $campos  = " cod_presi,
								  cod_entidad,
								  cod_tipo_inst,
								  cod_inst,
								  cod_dep,
								  ano,
								  tipo_documento,
								  beneficiario,
								  ano_documento,
								  numero_documento,
								  fecha_documento,
								  cod_sector,
								  cod_programa,
								  cod_sub_prog,
								  cod_proyecto,
								  cod_activ_obra,
								  cod_partida,
								  cod_generica,
								  cod_especifica,
								  cod_sub_espec,
								  SUM((monto + aumento) - disminucion) as monto";

					 $group_by = " GROUP BY   cod_presi,
											  cod_entidad,
											  cod_tipo_inst,
											  cod_inst,
											  cod_dep,
											  ano,
											  tipo_documento,
											  beneficiario,
											  ano_documento,
											  numero_documento,
											  fecha_documento,
											  cod_sector,
											  cod_programa,
											  cod_sub_prog,
											  cod_proyecto,
											  cod_activ_obra,
											  cod_partida,
											  cod_generica,
											  cod_especifica,
											  cod_sub_espec";
  	                 $this->set('datos',        $this->v_documentos_comprometidos_no_causados->findAll($sql." and condicion_actividad=1 ".$group_by,$campos,"cod_dep, tipo_documento, ano_documento, numero_documento ASC",null, null, null));
                     $this->set('dependencia',  $this->cugd02_dependencia->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst'),array('cod_dependencia','denominacion')));
                     $this->set("consolidacion", $consolidado_aux);


}//fin else

$this->set('opcion', $var1);

}//fin function








function forma_cierre_2($var1=null, $var2=null){set_time_limit(0);



      if($var1==1){

					      	    $this->layout = "ajax";

								$cod_presi     = $this->Session->read('SScodpresi');
								$cod_entidad   = $this->Session->read('SScodentidad');
								$cod_tipo_inst = $this->Session->read('SScodtipoinst');
								$cod_inst      = $this->Session->read('SScodinst');
								$cod_dep       = $this->Session->read('SScoddep');


							        for($minCount = 2007; $minCount < 2020; $minCount++) {
								     $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
								     $this->set('anos',$anos);
							        }
							        $this->set('ano_ejecucion', $this->ano_ejecucion());



}else if($var1==2){

                     $this->layout = "pdf";

                     if(!empty($this->data['datos']['consolidacion'])){
  	         	         $consolidado     = $this->data['datos']['consolidacion'];
  	         	         $consolidado_aux = $this->data['datos']['consolidacion'];
  	                 }else{
  	                 	 $consolidado     = 2;
  	                 	 $consolidado_aux = 2;

  	                 }
				          if($consolidado==2){
				  	     	$consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }else if($consolidado==1){
				  		    $consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }
                     if(!empty($this->data['datos']['ano'])){
  	         	           $sql = $consolidado." and ano=".$this->data['datos']['ano'];
  	                 }else{
  	                 	   $sql = $consolidado;
  	                 }

  	                 $campos  = " cod_presi,
								  cod_entidad,
								  cod_tipo_inst,
								  cod_inst,
								  cod_dep,
								  ano,
								  cod_sector,
								  cod_programa,
								  cod_sub_prog,
								  cod_proyecto,
								  cod_activ_obra,
								  cod_partida,
								  cod_generica,
								  cod_especifica,
								  cod_sub_espec,
								  SUM(asignacion_anual)           as asignacion_anual,
								  SUM(aumento_traslado_anual)     as aumento_traslado_anual,
								  SUM(disminucion_traslado_anual) as disminucion_traslado_anual,
								  SUM(credito_adicional_anual)    as credito_adicional_anual,
								  SUM(rebaja_anual)          as rebaja_anual,
								  SUM(compromiso_anual)      as compromiso_anual,
								  SUM(causado_anual)         as causado_anual,
								  SUM(pagado_anual)          as pagado_anual,
								  SUM(asignacion_modificada) as asignacion_modificada,
								  SUM(disponibilidad)        as disponibilidad ";

					 $group_by = " GROUP BY   cod_presi,
											  cod_entidad,
											  cod_tipo_inst,
											  cod_inst,
											  cod_dep,
											  ano,
											  cod_sector,
											  cod_programa,
											  cod_sub_prog,
											  cod_proyecto,
											  cod_activ_obra,
											  cod_partida,
											  cod_generica,
											  cod_especifica,
											  cod_sub_espec";

  	                 $this->set('datos',$this->v_nivel_ejecucion_presupuestaria->findAll($sql." ".$group_by,$campos," cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC",null, null, null));
                     $this->set('dependencia',  $this->cugd02_dependencia->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst'),array('cod_dependencia','denominacion')));
                     $this->set("consolidacion", $consolidado_aux);




}//fin else

$this->set('opcion', $var1);

}//fin function















function forma_cierre_3($var1=null, $var2=null){set_time_limit(0);



      if($var1==1){

					      	    $this->layout = "ajax";

								$cod_presi     = $this->Session->read('SScodpresi');
								$cod_entidad   = $this->Session->read('SScodentidad');
								$cod_tipo_inst = $this->Session->read('SScodtipoinst');
								$cod_inst      = $this->Session->read('SScodinst');
								$cod_dep       = $this->Session->read('SScoddep');


							        for($minCount = 2007; $minCount < 2020; $minCount++) {
								     $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
								     $this->set('anos',$anos);
							        }
							        $this->set('ano_ejecucion', $this->ano_ejecucion());



}else if($var1==2){

                     $this->layout = "pdf";
                     if(!empty($this->data['datos']['consolidacion'])){
  	         	         $consolidado     = $this->data['datos']['consolidacion'];
  	         	         $consolidado_aux = $this->data['datos']['consolidacion'];
  	                 }else{
  	                 	 $consolidado     = 2;
  	                 	 $consolidado_aux = 2;

  	                 }
				          if($consolidado==2){
				  	     	$consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }else if($consolidado==1){
				  		    $consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }
                     if(!empty($this->data['datos']['ano'])){
  	         	           $sql = $consolidado." and ano=".$this->data['datos']['ano'];
  	                 }else{
  	                 	   $sql = $consolidado;
  	                 }
  	                 if(!empty($this->data['reporte']['fecha_desde']) && !empty($this->data['reporte']['fecha_hasta'])){
						$fecha_desde = $this->data['reporte']['fecha_desde'];
					    $fecha_hasta = $this->data['reporte']['fecha_hasta'];
					    $fecha_sql   = "and (fecha_documento BETWEEN '".$fecha_desde."' AND '$fecha_hasta')";
					}else{
	                    $fecha_sql   = "";
	                    $fecha_desde = "";
	                    $fecha_hasta = "";
				    }
				     $sql .= $fecha_sql;
				     $this->set("fecha_desde", $fecha_desde);
				     $this->set("fecha_hasta", $fecha_hasta);
  	                 $campos  = " cod_presi,
								  cod_entidad,
								  cod_tipo_inst,
								  cod_inst,
								  cod_dep,
								  ano,
								  tipo_documento,
								  ano_documento,
								  numero_documento,
								  fecha_documento,
								  beneficiario,
								  cod_sector,
								  cod_programa,
								  cod_sub_prog,
								  cod_proyecto,
								  cod_activ_obra,
								  cod_partida,
								  cod_generica,
								  cod_especifica,
								  cod_sub_espec,
								  SUM(monto)           as monto";

					 $group_by = " GROUP BY   cod_presi,
											  cod_entidad,
											  cod_tipo_inst,
											  cod_inst,
											  cod_dep,
											  ano,
											  tipo_documento,
											  ano_documento,
											  numero_documento,
											  beneficiario,
											  fecha_documento,
											  cod_sector,
											  cod_programa,
											  cod_sub_prog,
											  cod_proyecto,
											  cod_activ_obra,
											  cod_partida,
											  cod_generica,
											  cod_especifica,
											  cod_sub_espec";
  	                 $this->set('datos',$this->v_documentos_causados_no_pagados->findAll($sql." and condicion_actividad=1 ".$group_by,$campos,"cod_dep, tipo_documento, ano_documento, numero_documento ASC",null, null, null));
                     $this->set('dependencia',  $this->cugd02_dependencia->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst'),array('cod_dependencia','denominacion')));
                     $this->set("consolidacion", $consolidado_aux);


}//fin else

$this->set('opcion', $var1);

}//fin function









function forma_cierre_4($var1=null, $var2=null){set_time_limit(0);



      if($var1==1){

					      	    $this->layout = "ajax";

								$cod_presi     = $this->Session->read('SScodpresi');
								$cod_entidad   = $this->Session->read('SScodentidad');
								$cod_tipo_inst = $this->Session->read('SScodtipoinst');
								$cod_inst      = $this->Session->read('SScodinst');
								$cod_dep       = $this->Session->read('SScoddep');


							        for($minCount = 2007; $minCount < 2020; $minCount++) {
								     $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
								     $this->set('anos',$anos);
							        }
							        $this->set('ano_ejecucion', $this->ano_ejecucion());



}else if($var1==2){

                     $this->layout = "pdf";


}//fin else

$this->set('opcion', $var1);

}//fin function








function forma_cierre_5($var1=null, $var2=null){set_time_limit(0);



      if($var1==1){

					      	    $this->layout = "ajax";

								$cod_presi     = $this->Session->read('SScodpresi');
								$cod_entidad   = $this->Session->read('SScodentidad');
								$cod_tipo_inst = $this->Session->read('SScodtipoinst');
								$cod_inst      = $this->Session->read('SScodinst');
								$cod_dep       = $this->Session->read('SScoddep');


							        for($minCount = 2007; $minCount < 2020; $minCount++) {
								     $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
								     $this->set('anos',$anos);
							        }
							        $this->set('ano_ejecucion', $this->ano_ejecucion());



}else if($var1==2){

                     $this->layout = "pdf";


}//fin else

$this->set('opcion', $var1);

}//fin function




function forma_cierre_6($var1=null, $var2=null){set_time_limit(0);



      if($var1==1){

					      	    $this->layout = "ajax";

								$cod_presi     = $this->Session->read('SScodpresi');
								$cod_entidad   = $this->Session->read('SScodentidad');
								$cod_tipo_inst = $this->Session->read('SScodtipoinst');
								$cod_inst      = $this->Session->read('SScodinst');
								$cod_dep       = $this->Session->read('SScoddep');


							        for($minCount = 2007; $minCount < 2020; $minCount++) {
								     $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
								     $this->set('anos',$anos);
							        }
							        $this->set('ano_ejecucion', $this->ano_ejecucion());



}else if($var1==2){

                     $this->layout = "pdf";


}//fin else

$this->set('opcion', $var1);

}//fin function




function forma_cierre_7($var1=null, $var2=null){set_time_limit(0);



      if($var1==1){

					      	    $this->layout = "ajax";

								$cod_presi     = $this->Session->read('SScodpresi');
								$cod_entidad   = $this->Session->read('SScodentidad');
								$cod_tipo_inst = $this->Session->read('SScodtipoinst');
								$cod_inst      = $this->Session->read('SScodinst');
								$cod_dep       = $this->Session->read('SScoddep');


							        for($minCount = 2007; $minCount < 2020; $minCount++) {
								     $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
								     $this->set('anos',$anos);
							        }
							        $this->set('ano_ejecucion', $this->ano_ejecucion());



}else if($var1==2){

                     $this->layout = "pdf";
                     if(!empty($this->data['datos']['consolidacion'])){
  	         	         $consolidado     = $this->data['datos']['consolidacion'];
  	         	         $consolidado_aux = $this->data['datos']['consolidacion'];
  	                 }else{
  	                 	 $consolidado     = 2;
  	                 	 $consolidado_aux = 2;

  	                 }
				          if($consolidado==2){
				  	     	$consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }else if($consolidado==1){
				  		    $consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }
                     if(!empty($this->data['datos']['ano'])){
  	         	           $sql = $consolidado." and ano=".$this->data['datos']['ano'];
  	                 }else{
  	                 	   $sql = $consolidado;
  	                 }
  	                 if(!empty($this->data['reporte']['fecha_desde']) && !empty($this->data['reporte']['fecha_hasta'])){
						$fecha_desde = $this->data['reporte']['fecha_desde'];
					    $fecha_hasta = $this->data['reporte']['fecha_hasta'];
					    $fecha_sql   = "and (fecha_documento BETWEEN '".$fecha_desde."' AND '$fecha_hasta')";
					}else{
	                    $fecha_sql   = "";
	                    $fecha_desde = "";
	                    $fecha_hasta = "";
				    }
				    $this->set("fecha_desde", $fecha_desde);
				    $this->set("fecha_hasta", $fecha_hasta);
				    $sql .= $fecha_sql;
  	                 $campos  = " cod_presi,
								  cod_entidad,
								  cod_tipo_inst,
								  cod_inst,
								  cod_dep,
								  ano,
								  tipo_documento,
								  ano_documento,
								  numero_documento,
								  fecha_documento,
								  rif,
								  beneficiario,
								  autorizado,
								  cod_sector,
								  cod_programa,
								  cod_sub_prog,
								  cod_proyecto,
								  cod_activ_obra,
								  cod_partida,
								  cod_generica,
								  cod_especifica,
								  cod_sub_espec,
								  SUM(monto)           as monto";

					 $group_by = " GROUP BY   cod_presi,
											  cod_entidad,
											  cod_tipo_inst,
											  cod_inst,
											  cod_dep,
											  ano,
											  tipo_documento,
											  ano_documento,
											  numero_documento,
											  fecha_documento,
											  rif,
									          beneficiario,
									          autorizado,
											  cod_sector,
											  cod_programa,
											  cod_sub_prog,
											  cod_proyecto,
											  cod_activ_obra,
											  cod_partida,
											  cod_generica,
											  cod_especifica,
											  cod_sub_espec";
  	                 $this->set('datos',$this->v_documentos_causados_no_pagados->findAll($sql." and condicion_actividad=1 and tipo_documento=3 ".$group_by,$campos,"cod_dep, tipo_documento, ano_documento, numero_documento ASC",null, null, null));
                     $this->set('dependencia',  $this->cugd02_dependencia->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst'),array('cod_dependencia','denominacion')));
                     $this->set("consolidacion", $consolidado_aux);


}//fin else

$this->set('opcion', $var1);

}//fin function







function forma_cierre_8($var1=null, $var2=null){set_time_limit(0);



      if($var1==1){

					      	    $this->layout = "ajax";

								$cod_presi     = $this->Session->read('SScodpresi');
								$cod_entidad   = $this->Session->read('SScodentidad');
								$cod_tipo_inst = $this->Session->read('SScodtipoinst');
								$cod_inst      = $this->Session->read('SScodinst');
								$cod_dep       = $this->Session->read('SScoddep');


							        for($minCount = 2007; $minCount < 2020; $minCount++) {
								     $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
								     $this->set('anos',$anos);
							        }
							        $this->set('ano_ejecucion', $this->ano_ejecucion());


}else if($var1==2){

                     $this->layout = "pdf";
  	                 if(!empty($this->data['datos']['consolidacion'])){
  	         	         $consolidado     = $this->data['datos']['consolidacion'];
  	         	         $consolidado_aux = $this->data['datos']['consolidacion'];
  	                 }else{
  	                 	 $consolidado     = 2;
  	                 	 $consolidado_aux = 2;

  	                 }
				          if($consolidado==2){
				  	     	$consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }else if($consolidado==1){
				  		    $consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }
                     if(!empty($this->data['datos']['ano'])){
  	         	           $sql = $consolidado." and ano_reintegro=".$this->data['datos']['ano'];
  	                 }else{
  	                 	   $sql = $consolidado;
  	                 }

  	                 $this->set('datos', $this->v_reintegro_cuenta_bancaria->findAll($sql." and condicion_actividad=1 and cuenta_bancaria!='0'", null, "cod_dep, ano_reintegro ASC",null, null, null));
                     $this->set('dependencia',  $this->cugd02_dependencia->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst'), array('cod_dependencia','denominacion') ));
                     $this->set("consolidacion", $consolidado_aux);



}//fin else

$this->set('opcion', $var1);




}//fin function









function formato_9_seniat($var1=null, $var2=null){set_time_limit(0);



      if($var1==1){

					      	    $this->layout = "ajax";

								$cod_presi     = $this->Session->read('SScodpresi');
								$cod_entidad   = $this->Session->read('SScodentidad');
								$cod_tipo_inst = $this->Session->read('SScodtipoinst');
								$cod_inst      = $this->Session->read('SScodinst');
								$cod_dep       = $this->Session->read('SScoddep');


							        for($minCount = 2007; $minCount < 2020; $minCount++) {
								     $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
								     $this->set('anos',$anos);
							        }
							        $this->set('ano_ejecucion', $this->ano_ejecucion());

}else if($var1==2){
                     $this->layout = "ajax";
                     $campos  = " cod_presi,
								  cod_entidad,
								  cod_tipo_inst,
								  cod_inst,
								  cod_dep,
								  ano_contrato_obra";
					 $group_by = " GROUP BY   cod_presi,
											  cod_entidad,
											  cod_tipo_inst,
											  cod_inst,
											  cod_dep,
											  ano_contrato_obra";
  	                 $datos = $this->v_obras_comp_cau_pag_no_pag_order->findAll($this->condicion()." ".$group_by, $campos, "cod_dep, ano_contrato_obra ASC",null, null, null);
                     $anos  = array();
                     foreach($datos as $ve){
                        $anos[$ve["v_obras_comp_cau_pag_no_pag_order"]["ano_contrato_obra"]] = $ve["v_obras_comp_cau_pag_no_pag_order"]["ano_contrato_obra"];
                     }
                     $this->set('anos',$anos);
                     $this->set('ano_ejecucion', $this->ano_ejecucion());
                     $this->set('opcion2', $var2);
}else if($var1==3){

                     $this->layout = "pdf";
  	                 if(!empty($this->data['datos']['consolidacion'])){
  	         	         $consolidado     = $this->data['datos']['consolidacion'];
  	         	         $consolidado_aux = $this->data['datos']['consolidacion'];
  	                 }else{
  	                 	 $consolidado     = 2;
  	                 	 $consolidado_aux = 2;

  	                 }
				          if($consolidado==2){
				  	     	$consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }else if($consolidado==1){
				  		    $consolidado = $this->SQLCA_consolidado($consolidado);
				  	 }
                     if(!empty($this->data['datos']['ano'])){
  	         	           $sql = $consolidado." and ano_contrato_obra=".$this->data['datos']['ano'];
  	                 }else{
  	                 	   $sql = $consolidado;
  	                 }
  	                 $campos  = " cod_presi,
								  cod_entidad,
								  cod_tipo_inst,
								  cod_inst,
								  ano_contrato_obra";
					 $group_by = " GROUP BY   cod_presi,
											  cod_entidad,
											  cod_tipo_inst,
											  cod_inst,
											  ano_contrato_obra";
  	                 $datos   = $this->v_obras_comp_cau_pag_no_pag_order->findAll($sql." ".$group_by, $campos, "ano_contrato_obra ASC",null, null, null);
                     $anos_t  = "";
                     foreach($datos as $ve){
                     	if($anos_t==""){
                          $anos_t  = $ve["v_obras_comp_cau_pag_no_pag_order"]["ano_contrato_obra"];
                     	}else{
                     	  $anos_t .= " - ".$ve["v_obras_comp_cau_pag_no_pag_order"]["ano_contrato_obra"];
                     	}
                     }
                     $campos  = " SELECT              cod_presi,
													  cod_entidad,
													  cod_tipo_inst,
													  cod_inst,
													  cod_dep,
													  ano_contrato_obra,
													  numero_contrato_obra,
													  rif,
													  beneficiario,
													  monto_contratado,
													  monto_retenio_iva,
													  porcentaje_retencion_iva,
											          fecha_retencion_iva,
											          monto_retenio_islr,
													  porcentaje_retencion_islr,
											          fecha_retencion_islr,
											          tipo,
											          numero_valuacion  FROM v_obras_comp_cau_pag_no_pag_order WHERE ".$sql." ORDER BY cod_dep, beneficiario, ano_contrato_obra, numero_contrato_obra, numero_valuacion  ASC";



  	                 $this->set('datos', $this->v_obras_comp_cau_pag_no_pag_order->execute($campos));
  	                 $this->set("anos_t", $anos_t);
  	                 $this->set('dependencia',  $this->cugd02_dependencia->findAll("cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst'),array('cod_dependencia','denominacion')));
  	                 $this->set("consolidacion", $consolidado_aux);




}//fin else

$this->set('opcion', $var1);




}

















}//fin class

?>