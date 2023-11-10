<?php

 class ventanaImpuestoMunicipalController extends AppController{
	var $uses = array('catd02_ficha_datos','v_shd001_registro_contribuyentes','v_shd100_actividadees','v_shd100_solicitud_actividades','v_shd100_solicitud',
                      'shd100_solicitud_actividades','cnmd06_profesiones','shd003_codigo_ingresos','shd002_cobradores','shd100_actividades',
                      'shd100_solicitud','cugd01_republica','shd001_registro_contribuyentes','cugd01_estados','cugd01_municipios',
                      'cugd01_parroquias','cugd01_centropoblados','cugd01_vialidad','cugd01_vereda', 'cugd90_municipio_defecto', 'v_shd100_solicitud');
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


function buscar_actividades_pista($var1=null, $rif=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('rif_pista', $rif);
}//fin function


function buscar_porpista_actividad($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
// $v_shd100_solicitud = $this->v_shd100_actividadees->findAll($this->condicionNDEP());
$modelo='v_shd100_actividadees';
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista_activid', $var2);
					 $Tfilas=$this->$modelo->findCount($this->condicionNDEP()." and alicuota!=0 and ((cod_actividad LIKE '%$var2%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$modelo->findAll($this->condicionNDEP()." and alicuota!=0 and ((cod_actividad LIKE '%$var2%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var2%')))",null,"alicuota ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista_activid');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->condicionNDEP()." and alicuota!=0 and ((cod_actividad LIKE '%$var22%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var22%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->$modelo->findAll($this->condicionNDEP()." and alicuota!=0 and ((cod_actividad LIKE '%$var22%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var22%')))",null,"alicuota ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
   		}//fin else
$this->set("opcion",$var1);
} //fin funcion



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
$rif_pista                 = $this->Session->read('rif_pista');
$contar_v_shd100_solicitud = $this->v_shd100_solicitud->findCount($this->condicionNDEP());    //  ." and rif_cedula='".$rif_pista."'  "
$sql_v_shd100_solicitud    = "";
if($contar_v_shd100_solicitud!=0 || $contar_v_shd100_solicitud!="0"){     // $contar_v_shd100_solicitud==0 || $contar_v_shd100_solicitud=="0"
	$modelo             = "shd100_actividades";
	$modelo_2           = 1;
}else{
	$modelo             = "v_shd100_solicitud_actividades";
	$v_shd100_solicitud = $this->v_shd100_solicitud->findAll($this->condicionNDEP());    //  ." and rif_cedula='".$rif_pista."'  "
	foreach($v_shd100_solicitud as $ve){
		if($sql_v_shd100_solicitud==""){
			  $sql_v_shd100_solicitud  = "    numero_solicitud='".$ve["v_shd100_solicitud"]["numero_solicitud"]."'  ";
		}else{
              $sql_v_shd100_solicitud .= " or numero_solicitud='".$ve["v_shd100_solicitud"]["numero_solicitud"]."'  ";
		}
	}
	$sql_v_shd100_solicitud = " and (".$sql_v_shd100_solicitud.")";
	$modelo_2               = 2;
}
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->$modelo->findCount($this->condicionNDEP().$sql_v_shd100_solicitud." and alicuota!=0  and ((cod_actividad LIKE '%$var2%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$modelo->findAll($this->condicionNDEP().$sql_v_shd100_solicitud." and alicuota!=0 and ((cod_actividad LIKE '%$var2%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var2%')))",null,"cod_actividad ASC",50,1,null);
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
						//if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->$modelo->findCount($this->condicionNDEP().$sql_v_shd100_solicitud." and alicuota!=0 and minimo_tributable!=0 and ((cod_actividad LIKE '%$var22%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var22%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->$modelo->findAll($this->condicionNDEP().$sql_v_shd100_solicitud." and alicuota!=0 and minimo_tributable!=0 and ((cod_actividad LIKE '%$var22%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var22%')))",null,"cod_actividad ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
$this->set("modelo_2",$modelo_2);
}//fin function


function seleccion($var1=null, $var2=null){

$this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;


$shd100_actividades_aux = $this->shd100_actividades->findAll($condicion." and cod_actividad = '".$var2."'");
$this->Session->write('ventana_impuesto_municipal', $var2);

foreach($shd100_actividades_aux as $ve){
	$var1_aux = $ve["shd100_actividades"]["denominacion_actividad"];
	$var2_aux = $ve["shd100_actividades"]["alicuota"];
	$var3_aux = $ve["shd100_actividades"]["minimo_tributable"];
}//fin foreach
       echo'<script>';

                     if($var1==1){$id="impuesto_municipal";
               }else if($var1==2){$id="impuesto_municipal";
               }else if($var1==3){$id="impuesto_municipal";
               }else if($var1==4){$id="porce_deduccion_impuesto_municipal";
               }else if($var1==5){$id="impuesto_municipal";
               }else if($var1==6){$id="impuesto_municipal";
               }




               echo" document.getElementById('".$id."').value = '".$this->Formato2($var2_aux)."'; ";

                     if($var1==1){echo" cobp01_contratoobras_valuacion_detalles_del_pago(); ";
               }else if($var1==2){echo" cepp02_contratoservicios_valuacion_detalles_del_pago(); ";
               }else if($var1==3){echo" detalles_del_pago(); ";
               }else if($var1==4){echo" re_calcular(); ";
               }else if($var1==5){echo" cobp01_contratoobras_retencion_detalles_del_pago(); ";
               }else if($var1==6){echo" cepp02_contratoservicio_retencion_detalles_del_pago(); ";
               }

      echo'</script>';


}//fin seleccion




}?>