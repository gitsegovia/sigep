<?php
/*
 * Creado el 14/04/2008 a las 04:43:23 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class Cstp07PagosNominaController extends AppController{
	var $name = 'cstp07_pagos_nomina';
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
 	if(isset($_SESSION['ano_consulta_acomuluado_pendiente'])){
 		$ano = $_SESSION['ano_consulta_acomuluado_pendiente'];
 	}else{
 		$ano = $this->ano_ejecucion();
    }

    if(isset($pagina)){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}//fin else

	$this->set('ano_ejecucion',$this->ano_ejecucion());

 	$cond=$this->SQLCA()." and ano_orden_pago=".$ano;

 	switch($var){
 	    case '1':        
            /* POR MOMENTO ESTARA LA BUSQUEDA REALIZADA SIN CONDICIONAR EL numero_orden_pago_secuencia A !='0'
             *   CON LA FINALIDAD DE CONSULTAR ORDENES DE NOMINAS VIEJAS Y ORDENES NUEVAS REALIZADAS POR 
             *   ORDENAMIENTO DE PAGO CON EL MISMO BENEFICIARIO
             */
            $Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($cond. " and condicion_actividad=1 and (numero_cheque=0 or numero_cheque=-1)");
            
            //$Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($cond. " and (numero_cheque=0 or numero_cheque=-1) and numero_orden_pago_secuencia!='0' and condicion_actividad=1");
		       if($Tfilas!=0){
		        	$Tfilas=(int)ceil($Tfilas/200);
		        	$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('ultimo',$Tfilas);
					//$datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($cond. " and numero_cheque=0  and numero_orden_pago_secuencia!='0' and condicion_actividad=1",null,"ano_orden_pago, cuenta_bancaria, fecha_proceso_registro, numero_orden_pago , monto_total , autorizado , numero_cheque,numero_orden_pago_secuencia",200,$pagina,null);
					$datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($cond. " and condicion_actividad=1 and (numero_cheque=0 or numero_cheque=-1)",null,"numero_cheque DESC, numero_orden_pago, numero_orden_pago_secuencia",200,$pagina,null);
			        $this->set("datos_cuerpo_impuesto",$datos_filas);
			        //$datos_filas_todos=$this->cepd03_ordenpago_cuerpo->findAll($cond. " and numero_cheque=0  and numero_orden_pago_secuencia!='0' and condicion_actividad=1",null," ano_orden_pago, cuenta_bancaria, fecha_proceso_registro, numero_orden_pago , monto_total , autorizado , numero_cheque,numero_orden_pago_secuencia");
			        $datos_filas_todos=$this->cepd03_ordenpago_cuerpo->findAll($cond. " and condicion_actividad=1 and (numero_cheque=0 or numero_cheque=-1)",null,"numero_cheque DESC, numero_orden_pago, numero_orden_pago_secuencia");
			        $this->set("datos_cuerpo_impuesto_todos",$datos_filas_todos);
			        $this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
		         }else{
		        	$this->set("datos_cuerpo_impuesto",'');
		        	$this->set("datos_cuerpo_impuesto_todos",'');
		        	$this->set("datos_cuerpo_impuesto_nomina",'');
		         }
		//$datos_ordenpago=$this->cepd03_ordenpago_cuerpo->findAll($cond,array('numero_orden_pago','beneficiario'));
		//$this->set('datos_ordenpago',$datos_ordenpago);
		$this->set('titulo','ORDENES DE PAGO');
		$this->set('var',$var);
 	    break;
 	}
 }//retenciones_impuestos




//$ano_orden.'/'.$num_orden_pago.'/'.$monto.'/1/'.$j
function congelar($ano_orden=null,$num_orden=null,$tipo_impuesto=null,$i=null,$monto=null,$fecha_reten=null,$benef=null,$num_orden_sec=null){
	$this->layout ="ajax";
	if($ano_orden!=null && $num_orden!=null && $tipo_impuesto!=null){
			switch($tipo_impuesto){
				case '1':
					$update="UPDATE cepd03_ordenpago_cuerpo SET numero_cheque=-1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden." and numero_orden_pago_secuencia='".$num_orden_sec."'";
						
						$benef = str_replace("--", "/", $benef);
						$benef = str_replace("porciento", "%", $benef);

					if($this->cepd03_ordenpago_cuerpo->execute($update)>1){
						$this->set('mensaje',"La retencion por nómina fue congelada correctamente");
						$this->set('ano_orden',$ano_orden);
						$this->set('num_orden_pago',$num_orden);
						$this->set('monto',$monto);
						$this->set('tipo_impuesto',$tipo_impuesto);
						$this->set('fecha_reten',$fecha_reten);
						$this->set('beneficiario',$benef);
						$this->set('numero_orden_pago_secuencia',$num_orden_sec);
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

function descongelar($ano_orden=null,$num_orden=null,$tipo_impuesto=null,$i=null,$monto=null,$fecha_reten=null,$benef=null,$num_orden_sec=null){
	$this->layout ="ajax";

	if($ano_orden!=null && $num_orden!=null && $tipo_impuesto!=null){
			switch($tipo_impuesto){
				case '1'://
					$update="UPDATE cepd03_ordenpago_cuerpo SET numero_cheque=0 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_orden." and numero_orden_pago=".$num_orden." and numero_orden_pago_secuencia='".$num_orden_sec."'";

						$benef = str_replace("--", "/", $benef);
						$benef = str_replace("porciento", "%", $benef);

					if($this->cepd03_ordenpago_cuerpo->execute($update)>1){
						$this->set('mensaje',"La retencion por nómina fue congelada correctamente");
						$this->set('ano_orden',$ano_orden);
						$this->set('num_orden_pago',$num_orden);
						$this->set('monto',$monto);
						$this->set('tipo_impuesto',$tipo_impuesto);
						$this->set('fecha_reten',$fecha_reten);
						$this->set('beneficiario',$benef);
						$this->set('numero_orden_pago_secuencia',$num_orden_sec);
						$this->set('j',$i);
					}else{
						$this->set('mensajeError',"Lo siento, la retencion no pudo ser descongelada");
					}
				break;
			}
	}else{
		$this->set('mensajeError',"algunos de los datos no llegaron completamente, no se pudo procesar la operacion");
	}
}//descongelar


}//fin class
?>