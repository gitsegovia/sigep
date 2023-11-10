<?php

 class ventanaIslrController extends AppController{
	var $uses = array('catd02_ficha_datos','v_shd001_registro_contribuyentes','v_shd100_solicitud_actividades','v_shd100_solicitud',
                      'shd100_solicitud_actividades','cnmd06_profesiones','shd003_codigo_ingresos','shd002_cobradores','shd100_actividades',
                      'shd100_solicitud','cugd01_republica','shd001_registro_contribuyentes','cugd01_estados','cugd01_municipios',
                      'cugd01_parroquias','cugd01_centropoblados','cugd01_vialidad','cugd01_vereda', 'cugd90_municipio_defecto', 'v_shd100_solicitud',
                      'cepd01_codigos_retencion_islr');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');


//ventana_impuesto_municipal

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession




function beforeFilter(){
    $this->checkSession();
}//fin before filter




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

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX


    function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
         return $sql_re;
    }//fin funcion SQLCA

       function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}//fin zero



function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}

		$this->set($nomVar, $cod);

	}
}//fin concatena


/*
 <?= $sisap->input_buscar('shp100_solicitud/rif_constribuyente',array('size'=>'25', 'id'=>'rif_constribuyente','style'=>'text-align:center'),1,"Buscar contribuyente", "/shp100_solicitud/buscar_constribuyente/1", "750px", "333px" ); ?>
 */

function buscar_actividadx($var1=null, $rif=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('rif_pista', $rif);
}//fin function




function buscar_por_pistax($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$condicion = $this->busca_separado(array('denominacion_actividad', 'codigo_retencion', 'denominacion_escala'), $var2);
					$Tfilas=$this->cepd01_codigos_retencion_islr->findCount($condicion);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cepd01_codigos_retencion_islr->findAll($condicion,null,"codigo_retencion, cod_escala ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$condicion = $this->busca_separado(array('denominacion_actividad', 'codigo_retencion', 'denominacion_escala'), $var22);
						$Tfilas=$this->cepd01_codigos_retencion_islr->findCount($condicion);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cepd01_codigos_retencion_islr->findAll($condicion,null,"codigo_retencion, cod_escala ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function seleccion($var1=null, $var2=null, $var3=null){
$this->layout = "ajax";
$cepd01_codigos_retencion_islr_aux = $this->cepd01_codigos_retencion_islr->findAll("  codigo_retencion = '".$var2."'  and  cod_escala='".$var3."'       ");
$this->Session->write('ventana_islr', $var2);
foreach($cepd01_codigos_retencion_islr_aux as $ve){
	$var1_aux = $ve["cepd01_codigos_retencion_islr"]["denominacion_actividad"];
	$var2_aux = $ve["cepd01_codigos_retencion_islr"]["porcentaje"];
	$var3_aux = $ve["cepd01_codigos_retencion_islr"]["denominacion_escala"];
}//fin foreach
       echo'<script>';
                     if($var1==1){$id="impuesto_sobre_la_renta";
               }else if($var1==2){$id="impuesto_sobre_la_renta";
               }else if($var1==3){$id="impuesto_sobre_la_renta";
               }else if($var1==4){$id="porce_deduccion_isrl";
               }else if($var1==5){$id="impuesto_sobre_la_renta";
               }else if($var1==6){$id="impuesto_sobre_la_renta";
               }
               echo" document.getElementById('".$id."').value = '".$this->Formato2($var2_aux)."'; ";
                     if($var1==1){echo" cobp01_contratoobras_valuacion_detalles_del_pago(); ";
               }else if($var1==2){echo" cepp02_contratoservicios_valuacion_detalles_del_pago(); ";
               }else if($var1==3){echo" detalles_del_pago(); ";
               }else if($var1==4){echo" re_calcular(); ";
               	                  echo "$('num_factura').readOnly=false;";
               	                  echo "$('num_control').readOnly=false;";
               	                  echo "$('monto_total').readOnly=false;";
               	                  echo "$('plus').disabled=false;";
               	                  echo "$('num_factura').setAttribute('onBlur','s_factura_sin_iva_con_retencion();');";
               	                  echo "$('monto_total').setAttribute('onChange','s_factura_sin_iva_con_retencion_2();');";

               }else if($var1==5){echo" cobp01_contratoobras_retencion_detalles_del_pago(); ";
               }else if($var1==6){echo" cepp02_contratoservicio_retencion_detalles_del_pago(); ";
               }
      echo'</script>';
}//fin seleccion




}?>