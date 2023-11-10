<?php
/*
 * Creado el 14/04/2008 a las 04:43:23 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class Cstp07RetencionesAcumuladasPendientesController extends AppController{
	var $name = 'cstp07_retenciones_acumuladas_pendientes';
	var $uses = array('cstd07_retenciones_cuerpo_iva','cstd07_retenciones_cuerpo_islr','cstd07_retenciones_cuerpo_municipal',
                      'cstd07_retenciones_cuerpo_timbre','cepd03_ordenpago_cuerpo','cstd07_retenciones_partidas_timbre',
                      'cstd07_retenciones_partidas_municipal', 'ccfd04_cierre_mes', 'cstd07_retenciones_cuerpo_islr_consutal',
                      'cstd07_retenciones_cuerpo_iva_consutal', 'cstd07_retenciones_cuerpo_municipal_consutal',
                      'cstd07_retenciones_cuerpo_timbre_consutal','cstd07_retenciones_cuerpo_multa', 'cstd07_retenciones_cuerpo_responsabilidad',
                      'cstd07_retenciones_partidas_multa', 'cstd07_retenciones_partidas_responsabilidad',
                      'cstd06_comprobante_poremitir_multa', 'cstd06_comprobante_poremitir_responsabilidad', 'cstd06_comprobante_numero_multa',
                      'cstd06_comprobante_numero_responsabilidad', "cstd07_retenciones_cuerpo_multa_consutal", "cstd07_retenciones_cuerpo_resp_consutal",
                      'cstd06_comprobante_cuerpo_multa', 'cstd06_comprobante_cuerpo_responsabilidad',
                      'cstd07_retenciones_cuerpo_obras_laboral_consulta', 'cstd07_retenciones_cuerpo_obras_fielcumplimiento_consulta');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');


function checkSession(){
		if (!$this->Session->check('Usuario')){
		   $this->redirect('/salir/');
		   exit();
		}else{
		   $this->requestAction('/usuarios/actualizar_user');
		}
}


function beforeFilter(){
		$this->checkSession();
}


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


function index () {
 	$this->layout ="ajax";
 	$_SESSION['ano_consulta_acomuluado_pendiente'] = $this->ano_ejecucion();
 	$this->set('ano_consulta_acomuluado_pendiente',$this->ano_ejecucion());


}


function buscar_year($var1=null){

  $this->layout = "ajax";
  $_SESSION['ano_consulta_acomuluado_pendiente'] = $var1;

                     echo "<script>";
                        echo "document.getElementById('tipo_impuesto_1').checked=false;";
                        echo "document.getElementById('tipo_impuesto_2').checked=false;";
                        echo "document.getElementById('tipo_impuesto_3').checked=false;";
                        echo "document.getElementById('tipo_impuesto_4').checked=false;";
					echo "</script>";

}//fin function



function retenciones_impuestos($var=null, $pagina=null){
 	$this->layout ="ajax";
 	//echo $var;
 	 if(isset($_SESSION['ano_consulta_acomuluado_pendiente'])){$ano = $_SESSION['ano_consulta_acomuluado_pendiente'];}else{$ano = $this->ano_ejecucion();}

        if(isset($pagina)){
				$pagina=$pagina;
		}else{
				 $pagina=1;
		}//fin else

$this->set('ano_ejecucion',$this->ano_ejecucion());

 	$cond=$this->SQLCA()." and ano_orden_pago=".$ano;

					     
 	switch($var){
 	    case '1':         //$datos_cuerpo_iva=$this->cstd07_retenciones_cuerpo_iva->findAll($cond,array('ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'numero_orden_pago ASC');
                           $Tfilas=$this->cstd07_retenciones_cuerpo_iva_consutal->findCount($cond);
					       if($Tfilas!=0){
					        	$Tfilas=(int)ceil($Tfilas/200);
					        	$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cstd07_retenciones_cuerpo_iva_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC",200,$pagina,null);
						        $this->set("datos_cuerpo_impuesto",$datos_filas);
						        $datos_filas_todos=$this->cstd07_retenciones_cuerpo_iva_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC");
						        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					         }else{
					        	$this->set("datos_cuerpo_impuesto",'');
					        	$this->set("datos_cuerpo_impuesto_todos",'');
					         }

			//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
			//$this->set('datos_ordenpago',$datos_ordenpago);
			$this->set('titulo','RETENCIÓN  I.V.A.');
			$this->set('var',$var);
 	    break;
 		case '2':  ///$datos_cuerpo_isrl=$this->cstd07_retenciones_cuerpo_islr->findAll($cond,array('ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro','status'),'numero_orden_pago ASC');

					       $Tfilas=$this->cstd07_retenciones_cuerpo_islr_consutal->findCount($cond);
					       if($Tfilas!=0){
					        	$Tfilas=(int)ceil($Tfilas/200);
					        	$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cstd07_retenciones_cuerpo_islr_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC",200,$pagina,null);
						        $this->set("datos_cuerpo_impuesto",$datos_filas);
						        $datos_filas_todos=$this->cstd07_retenciones_cuerpo_islr_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC");
						        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					         }else{
					        	$this->set("datos_cuerpo_impuesto",'');
					        	$this->set("datos_cuerpo_impuesto_todos",'');
					         }
			//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
			//$this->set('datos_ordenpago',$datos_ordenpago);
			$this->set('titulo','RETENCIÓN I.S.L.R.');
			$this->set('var',$var);
 	    break;
 	    case '3':  //$datos_cuerpo_timbre=$this->cstd07_retenciones_cuerpo_timbre->findAll($cond,array('ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro','status'),'numero_orden_pago ASC');


					$Tfilas=$this->cstd07_retenciones_cuerpo_timbre_consutal->findCount($cond);
					       if($Tfilas!=0){
					        	$Tfilas=(int)ceil($Tfilas/200);
					        	$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cstd07_retenciones_cuerpo_timbre_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC",200,$pagina,null);
						        $this->set("datos_cuerpo_impuesto",$datos_filas);
						        $datos_filas_todos=$this->cstd07_retenciones_cuerpo_timbre_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC");
						        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					         }else{
					        	$this->set("datos_cuerpo_impuesto",'');
					        	$this->set("datos_cuerpo_impuesto_todos",'');
					         }

					//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
					//$this->set('datos_ordenpago',$datos_ordenpago);
					$this->set('titulo','RETENCIÓN TIMBRE FISCAL');
					$this->set('var',$var);
 	    break;
 	    case '4': 	//$datos_cuerpo_impmunicipal=$this->cstd07_retenciones_cuerpo_municipal->findAll($cond,array('ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'numero_orden_pago ASC');


					$Tfilas=$this->cstd07_retenciones_cuerpo_municipal_consutal->findCount($cond);
					       if($Tfilas!=0){
					        	$Tfilas=(int)ceil($Tfilas/200);
					        	$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cstd07_retenciones_cuerpo_municipal_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC",200,$pagina,null);
						        $this->set("datos_cuerpo_impuesto",$datos_filas);
						        $datos_filas_todos=$this->cstd07_retenciones_cuerpo_municipal_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC");
						        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					         }else{
					        	$this->set("datos_cuerpo_impuesto",'');
					        	$this->set("datos_cuerpo_impuesto_todos",'');
					         }

					//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
					//$this->set('datos_ordenpago',$datos_ordenpago);
					$this->set('titulo','RETENCIÓN IMPUESTO MUNICIPAL');
					$this->set('var',$var);
 	    break;
 	    case '5': 	//$datos_cuerpo_multa=$this->cstd07_retenciones_cuerpo_multa->findAll($cond,array('ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'numero_orden_pago ASC');


					$Tfilas=$this->cstd07_retenciones_cuerpo_multa_consutal->findCount($cond);
					       if($Tfilas!=0){
					        	$Tfilas=(int)ceil($Tfilas/200);
					        	$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cstd07_retenciones_cuerpo_multa_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC",200,$pagina,null);
						        $this->set("datos_cuerpo_impuesto",$datos_filas);
						        $datos_filas_todos=$this->cstd07_retenciones_cuerpo_multa_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC");
						        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					         }else{
					        	$this->set("datos_cuerpo_impuesto",'');
					        	$this->set("datos_cuerpo_impuesto_todos",'');
					         }

					//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
					//$this->set('datos_ordenpago',$datos_ordenpago);
					$this->set('titulo','RETENCIÓN RESPONSABILIDAD CIVIL');
					$this->set('var',$var);
 		break;
 		case '6': 	//$datos_cuerpo_responsabilidad=$this->cstd07_retenciones_cuerpo_responsabilidad->findAll($cond,array('ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'numero_orden_pago ASC');


		$Tfilas=$this->cstd07_retenciones_cuerpo_resp_consutal->findCount($cond);
		       if($Tfilas!=0){
		        	$Tfilas=(int)ceil($Tfilas/200);
		        	$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->cstd07_retenciones_cuerpo_resp_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC",200,$pagina,null);
			        $this->set("datos_cuerpo_impuesto",$datos_filas);
			        $datos_filas_todos=$this->cstd07_retenciones_cuerpo_resp_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC");
			        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
			        $this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
		         }else{
		        	$this->set("datos_cuerpo_impuesto",'');
		        	$this->set("datos_cuerpo_impuesto_todos",'');
		         }

		//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
		//$this->set('datos_ordenpago',$datos_ordenpago);
		$this->set('titulo','RETENCIÓN RESPONSABILIDAD SOCIAL');
		$this->set('var',$var);
 		break;

 		case '7': 	//$datos_cuerpo_responsabilidad=$this->cstd07_retenciones_cuerpo_responsabilidad->findAll($cond,array('ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'numero_orden_pago ASC');


		$Tfilas=$this->cstd07_retenciones_cuerpo_resp_consutal->findCount($cond);
		       if($Tfilas!=0){
		        	$Tfilas=(int)ceil($Tfilas/200);
		        	$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->cstd07_retenciones_cuerpo_resp_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC",200,$pagina,null);
			        $this->set("datos_cuerpo_impuesto",$datos_filas);
			        $datos_filas_todos=$this->cstd07_retenciones_cuerpo_resp_consutal->findAll($cond,null," ano_orden_pago, status, cuenta_bancaria_op, fecha_proceso_registro, numero_orden_pago ASC");
			        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
			        $this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);

		         }else{
		        	$this->set("datos_cuerpo_impuesto",'');
		        	$this->set("datos_cuerpo_impuesto_todos",'');
		         }
		//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
		//$this->set('datos_ordenpago',$datos_ordenpago);
		$this->set('titulo','RETENCIÓN DE NOMINA');
		$this->set('var',$var);
 		break;
 		case '8': 	//$datos_cuerpo_responsabilidad=$this->cstd07_retenciones_cuerpo_responsabilidad->findAll($cond,array('ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'numero_orden_pago ASC');


		$Tfilas=$this->cstd07_retenciones_cuerpo_obras_laboral_consulta->findCount($this->SQLCA());
		       if($Tfilas!=0){
		        	$Tfilas=(int)ceil($Tfilas/200);
		        	$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->cstd07_retenciones_cuerpo_obras_laboral_consulta->findAll($this->SQLCA(),null,"ano_orden_pago, sta, cuentabop, fechapr, numop, benef ASC",200,$pagina,null);
			        $this->set("datos_cuerpo_impuesto",$datos_filas);
			        $datos_filas_todos=$this->cstd07_retenciones_cuerpo_obras_laboral_consulta->findAll($this->SQLCA(),null,"ano_orden_pago, sta, cuentabop, fechapr, numop, benef ASC");
			        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
			        $this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
					//print_r($datos_filas_todos[0]);
		         }else{
		        	$this->set("datos_cuerpo_impuesto",'');
		        	$this->set("datos_cuerpo_impuesto_todos",'');
		         }
		//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
		//$this->set('datos_ordenpago',$datos_ordenpago);
		$this->set('titulo','RETENCIÓN LABORAL');
		$this->set('var',$var);
 		break;
 		case '9': 	//$datos_cuerpo_responsabilidad=$this->cstd07_retenciones_cuerpo_responsabilidad->findAll($cond,array('ano_orden_pago','numero_orden_pago','monto','fecha_proceso_registro', 'status'),'numero_orden_pago ASC');


		$Tfilas=$this->cstd07_retenciones_cuerpo_obras_fielcumplimiento_consulta->findCount($this->SQLCA());
		       if($Tfilas!=0){
		        	$Tfilas=(int)ceil($Tfilas/200);
		        	$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->cstd07_retenciones_cuerpo_obras_fielcumplimiento_consulta->findAll($this->SQLCA(),null,"ano_orden_pago, sta, cuentabop, fechapr, numop, benef ASC",200,$pagina,null);
			        $this->set("datos_cuerpo_impuesto",$datos_filas);
			        $datos_filas_todos=$this->cstd07_retenciones_cuerpo_obras_fielcumplimiento_consulta->findAll($this->SQLCA(),null," ano_orden_pago, sta, cuentabop, fechapr, numop, benef ASC");
			        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
			        $this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
					//print_r($datos_filas_todos);
		         }else{
		        	$this->set("datos_cuerpo_impuesto",'');
		        	$this->set("datos_cuerpo_impuesto_todos",'');
		         }
		//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
		//$this->set('datos_ordenpago',$datos_ordenpago);
		$this->set('titulo','RETENCIÓN FIEL CUMPLIMIENTO');
		$this->set('var',$var);
 		break;


 	}
 }//retenciones_impuestos


function eliminar($ano_orden=null,$numero_orden=null,$tipo_impuesto=null){
	$this->layout ="ajax";

	if($tipo_impuesto!=null){
		if($tipo_impuesto==1){
			$sql="DELETE FROM cstd07_retenciones_cuerpo_iva WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			if($this->cstd07_retenciones_cuerpo_iva->execute($sql)>1){
			$this->set('mensaje',"La retencion por I.V.A. fue eliminada correctamente");
			}else{
			$this->set('mensajeError',"Lo siento, la retencion no pudo ser eliminada");
			}

		}elseif($tipo_impuesto==2){
			$sql="DELETE FROM cstd07_retenciones_cuerpo_islr WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			if($this->cstd07_retenciones_cuerpo_islr->execute($sql)>1){
			$this->set('mensaje',"La retencion por I.S.L.R. fue eliminada correctamente");
			}else{
			$this->set('mensajeError',"Lo siento, la retencion no pudo ser eliminada");
			}

		}elseif($tipo_impuesto==3){
			//Se elimina primero de la tabla partidas.
			$sql_elim_part ="DELETE FROM cstd07_retenciones_partidas_timbre WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			$this->cstd07_retenciones_partidas_timbre->execute($sql_elim_part );
			//Luego se elimina de la tabla cuerpo.
			$sql="DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			if($this->cstd07_retenciones_cuerpo_timbre->execute($sql)>1){
			$this->set('mensaje',"La retencion por timbre fiscal fue eliminada correctamente");
			}else{
			$this->set('mensajeError',"Lo siento, la retencion no pudo ser eliminada");
			}

		}elseif($tipo_impuesto==4){
			//Se elimina primero de la tabla partidas.
			$sql_elim_part = "DELETE FROM cstd07_retenciones_partidas_municipal WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			$this->cstd07_retenciones_partidas_municipal->execute($sql_elim_part);
			//Luego se elimina de la tabla cuerpo.
			$sql="DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			if($this->cstd07_retenciones_cuerpo_municipal->execute($sql)>1){
			$this->set('mensaje',"La retencion por impuesto municipal fue eliminada correctamente");
			}else{
			$this->set('mensajeError',"Lo siento, la retencion no pudo ser eliminada");
			}


		}elseif($tipo_impuesto==5){
			//Se elimina primero de la tabla partidas.
			$sql_elim_part = "DELETE FROM cstd07_retenciones_partidas_multa WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			$this->cstd07_retenciones_partidas_multa->execute($sql_elim_part);
			//Luego se elimina de la tabla cuerpo.
			$sql="DELETE FROM cstd07_retenciones_cuerpo_multa WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			if($this->cstd07_retenciones_cuerpo_multa->execute($sql)>1){
			$this->set('mensaje',"La retencion por multa fue eliminada correctamente");
			}else{
			$this->set('mensajeError',"Lo siento, la retencion no pudo ser eliminada");
			}

		}elseif($tipo_impuesto==6){
			//Se elimina primero de la tabla partidas.
			$sql_elim_part = "DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			$this->cstd07_retenciones_partidas_responsabilidad->execute($sql_elim_part);
			//Luego se elimina de la tabla cuerpo.
			$sql="DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$numero_orden;
			if($this->cstd07_retenciones_cuerpo_responsabilidad->execute($sql)>1){
			$this->set('mensaje',"La retencion por responsabilidad fue eliminada correctamente");
			}else{
			$this->set('mensajeError',"Lo siento, la retencion no pudo ser eliminada");
			}






		}
	}
}//fin eliminar

//$ano_orden.'/'.$num_orden_pago.'/'.$monto.'/1/'.$j
function congelar($ano_orden=null,$num_orden=null,$monto=null,$tipo_impuesto=null,$i=null,$fecha_reten=null,$benef=null,$cuenta=null){
	$this->layout ="ajax";
	if($ano_orden!=null && $num_orden!=null && $tipo_impuesto!=null){
			switch($tipo_impuesto){
				case '1'://Retencion del IVA acumulado pendiente
						$benef = str_replace("##", "%", $benef);
						$ano_orden;
						$num_orden;
						$tipo_impuesto;
						$update="UPDATE cstd07_retenciones_cuerpo_iva SET status=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_iva->execute($update)>1){
							$this->set('mensaje',"La retencion por I.V.A. fue congelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser congelada");
						}
				break;
				case '2'://Retencion del ISLR acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_islr SET status=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_islr->execute($update)>1){
							$this->set('mensaje',"La retencion por I.S.L.R. fue congelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser congelada");
						}
				break;
				case '3'://Retencion del timbre acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_timbre SET status=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_timbre->execute($update)>1){
							$this->set('mensaje',"La retencion por timbre fiscal fue congelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser congelada");
						}
				break;
				case '4'://Retencion del imp_municipal acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_municipal SET status=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_municipal->execute($update)>1){
							$this->set('mensaje',"La retencion por impuesto municipal fue congelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser congelada");
						}

				break;



				case '5'://Retencion del multa acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_multa SET status=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_multa->execute($update)>1){
							$this->set('mensaje',"La retencion por multa fue congelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser congelada");
						}

				break;


				case '6'://Retencion del responsabilidad acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_responsabilidad SET status=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_responsabilidad->execute($update)>1){
							$this->set('mensaje',"La retencion por responsabilidad fue congelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser congelada");
						}

				break;

				case '8'://Retencion Laboral de Obras
						$update="UPDATE cobd01_contratoobras_retencion_cuerpo SET status=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_responsabilidad->execute($update)>1){
							$this->set('mensaje',"La retencion laboral fue congelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser congelada");
						}

				break;
				case '9'://Retencion Fiel Cumplimiento Obras
						$update="UPDATE cobd01_contratoobras_retencion_cuerpo SET status=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_responsabilidad->execute($update)>1){
							$this->set('mensaje',"La retencion de fiel cumplimiento fue congelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser congelada");
						}

				break;
			}
	}else{
	$this->set('mensajeError',"Lo siento, algunos de los datos no llegaron completamente");
	}
}//congelar


function descongelar($ano_orden=null,$num_orden=null,$monto=null,$tipo_impuesto=null,$i=null,$fecha_reten=null,$benef=null,$cuenta=null){
	$this->layout ="ajax";
	//echo "<br>ano_orden".$ano_orden;
	//echo "<br>num_orden".$num_orden;
	//echo "<br>tipo_impuesto".$tipo_impuesto;

	if($ano_orden!=null && $num_orden!=null && $tipo_impuesto!=null){
			switch($tipo_impuesto){
				case '1'://Retencion del IVA acumulado pendiente
						$benef = str_replace("##", "%", $benef);
						$update="UPDATE cstd07_retenciones_cuerpo_iva SET status=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_iva->execute($update)>1){
							$this->set('mensaje',"La retencion por I.V.A. fue descongelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser descongelada");
						}
				break;
				case '2'://Retencion del ISLR acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_islr SET status=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_islr->execute($update)>1){
							$this->set('mensaje',"La retencion por I.S.L.R. fue descongelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser descongelada");
						}
				break;
				case '3'://Retencion del timbre acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_timbre SET status=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_timbre->execute($update)>1){
							$this->set('mensaje',"La retencion por I.S.L.R. fue descongelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser descongelada");
						}
				break;
				case '4'://Retencion del imp_municipal acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_municipal SET status=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_municipal->execute($update)>1){
							$this->set('mensaje',"La retencion por impuesto municipal fue descongelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser descongelada");
						}
				break;
				case '5'://Retencion del multa acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_multa SET status=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_multa->execute($update)>1){
							$this->set('mensaje',"La retencion por multa fue descongelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser descongelada");
						}
				break;
				case '6'://Retencion del responsabilidad acumulado pendiente
						$update="UPDATE cstd07_retenciones_cuerpo_responsabilidad SET status=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_responsabilidad->execute($update)>1){
							$this->set('mensaje',"La retencion por responsabilidad fue descongelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser descongelada");
						}
				break;
				case '8'://Retencion Laboral de Obra
						$update="UPDATE cobd01_contratoobras_retencion_cuerpo SET status=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_responsabilidad->execute($update)>1){
							$this->set('mensaje',"La retencion laboral fue descongelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser descongelada");
						}
				break;
				case '9'://Retencion Fiel Cumplimiento Obras
						$update="UPDATE cobd01_contratoobras_retencion_cuerpo SET status=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden;
						if($this->cstd07_retenciones_cuerpo_responsabilidad->execute($update)>1){
							$this->set('mensaje',"La retencion de fiel cumplimiento fue descongelada correctamente");
							$this->set('cuenta',$cuenta);
							$this->set('ano_orden',$ano_orden);
							$this->set('num_orden_pago',$num_orden);
							$this->set('monto',$monto);
							$this->set('tipo_impuesto',$tipo_impuesto);
							$this->set('fecha_reten',$fecha_reten);
							$this->set('beneficiario',$benef);
							$this->set('j',$i);
						}else{
							$this->set('mensajeError',"Lo siento, la retencion no pudo ser congelada");
						}
				break;
			}
	}else{
	$this->set('mensajeError',"algunos de los datos no llegaron completamente, no se pudo procesar la operacion");
	}
}//descongelar


}//fin class
?>