<?php
/*
 * Creado el 21/04/2008 a las 09:27:18 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class ConsultaRelacionesDocumentosController extends AppController{
	var $name='consulta_relaciones_documentos';
	var $uses = array('ccfd04_cierre_mes','cepd01_compromiso_cuerpo','cscd04_ordencompra_encabezado','cscd04_ordencompra_autorizacion_cuerpo',
                      'cepd03_ordenpago_cuerpo', 'v_relacion_orden_compra_consulta', 'v_reporte_relacion_otros_compromisos', 'cpcd02',
					  'cugd02_direccionsuperior','cugd02_coordinacion','cugd02_secretaria','cugd02_direccion','cepd01_tipo_compromiso','cepd01_compromiso_partidas','cugd03_acta_anulacion_cuerpo','cstd01_entidades_bancarias','cstd01_sucursales_bancarias',
					  'v_cscd04_ordencompra','cscd03_cotizacion_encabezado','v_cscd03_cotizacion','cscd04_ordencompra_partidas','cscd03_cotizacion_encabezado_anulado','v_cscd03_cotizacion_anulada','cepd03_tipo_documento','cepd03_ordenpago_partidas','cepd03_ordenpago_facturas','cepd03_ordenpago_tipopago');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



function checkSession(){
   if (!$this->Session->check('Usuario')){
      $this->redirect('/salir/');
      exit();
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




function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  return $condicion;
}


function vacio(){
	$this->layout="ajax";
}
//-----------------------------------//----------------------------------------//


function busqueda_beneficiarios_compromisos($var=null){
	$this->layout="ajax";
	$this->set('opcion',$var);
}











function buscar_razonsocial_ordenescompras($var=null){
	$this->layout="ajax";
	$array =  array();

		if(isset($_SESSION['ano_compra_consulta'])){$ano = $_SESSION['ano_compra_consulta'];}else{$ano = $this->ano_ejecucion();}
		$cpcd02=$this->cpcd02->findAll("upper(denominacion) like upper('%$var%')",array('rif','denominacion'));
		$buscar=array();
		foreach($cpcd02 as $ddrif){
			$buscar[]="'".$ddrif['cpcd02']['rif']."'";
		}
		$bus=implode(',',$buscar);

		//$xx = "select c.rif from cpcd02 c where upper(razon_social) like upper('%$var%')";
		$rif=$this->cscd04_ordencompra_encabezado->execute("select distinct rif from cscd04_ordencompra_encabezado where ".$this->SQLCA()." and  ano_orden_compra='".$ano."' and  rif in ($bus)");
		$i=-1;
		$array = array();
		foreach($rif as $x){$i=$i+1;}
		for($j=0; $j<=$i; $j++){
			foreach($cpcd02 as $razonsocial){
	     	  if($rif[$j][0]['rif'] == $razonsocial['cpcd02']['rif']){
	 		  $array[$rif[$j][0]['rif']] = $razonsocial['cpcd02']['denominacion'];
			  }
			}
		}
		$this->set('razonsocial',$array);
		$this->set('var',$var);



}//razonsocial_ordenescompras









function buscar_beneficiario_orden_pago($var){
	$this->layout="ajax";
	$pista = strtoupper($var);
	$array = array();
	 $beneficiarios=$this->cepd03_ordenpago_cuerpo->execute("select distinct rif as rifci,beneficiario from cepd03_ordenpago_cuerpo where ".$this->SQLCA()."  and UPPER(beneficiario) LIKE  upper('%$pista%') and ano_orden_pago='".$_SESSION['ano_orden_de_pago']."' and cedula_identidad=0 UNION select distinct cedula_identidad::varchar(20) as rifci,beneficiario from cepd03_ordenpago_cuerpo where ".$this->SQLCA()." and ano_orden_pago='".$_SESSION['ano_orden_de_pago']."' and rif='0' ORDER BY beneficiario ASC;");
		for($j=0; $j<count($beneficiarios); $j++){
		$array[$beneficiarios[$j][0]['rifci']] = $beneficiarios[$j][0]['beneficiario']." - ".$beneficiarios[$j][0]['rifci'];
	}
	$this->set('beneficiarios',$array);
	$this->set('var',$var);
}










function buscar_beneficiario($var){
	$this->layout="ajax";
	$pista = strtoupper($var);
	$array = array();
	$beneficiarios=$this->cepd01_compromiso_cuerpo->execute("select distinct beneficiario from cepd01_compromiso_cuerpo where ".$this->SQLCA()." and UPPER(beneficiario) LIKE  upper('%$pista%') and ano_documento='".$_SESSION['ano_otroscompromisos']."' ");
	for($j=0; $j<count($beneficiarios); $j++){
	$array[$beneficiarios[$j][0]['beneficiario']] = $beneficiarios[$j][0]['beneficiario'];
	}


	$this->set('beneficiarios',$array);
	$this->set('var',$var);
}










function buscar_year_otroscompromisos($var1=null){



  $this->layout = "ajax";
  $_SESSION['ano_otroscompromisos'] = $var1;

echo "<script>";
  echo "document.getElementById('estilo_reporte_1').checked=true;";
  echo "document.getElementById('estilo_reporte_2').checked=false;";
   echo "document.getElementById('estilo_reporte_3').checked=false;";
echo "</script>";



echo "<script>";
  echo "if(document.getElementById('datos-consulta')){document.getElementById('datos-consulta').innerHTML='';}";
echo "</script>";



}//fin function







function reporte_relacion_otros_compromisos($ir=null, $pagina=null){
	$this->layout="ajax";

	if($ir!=null){
		if($ir=='si'){//si va al formulario
		   $this->set('ano',$this->ano_ejecucion());
		   $_SESSION['ano_otroscompromisos'] = $this->ano_ejecucion();
		   $this->set('ir','si');
		}elseif($ir=='no'){//no va al formulario, pero si va al reporte
		$this->layout="ajax";
		$this->set('ir','no');

		$cp  = $this->Session->read('SScodpresi');
		$ce  = $this->Session->read('SScodentidad');
		$cti = $this->Session->read('SScodtipoinst');
		$ci  = $this->Session->read('SScodinst');
		$cd  = $this->Session->read('SScoddep');

		//$ano = $this->data['relacion_otroscompromisos']['ano'];

                 if(isset($_SESSION['ano_otroscompromisos'])){
	           	    $year=$_SESSION['ano_otroscompromisos'];
	           }else{
	           	    $year=$this->ano_ejecucion();
	                }//fin else


	           if(isset($_SESSION['opcion_estilo_reporte'])){
			           	    if(isset($this->data['relacion_otroscompromisos']['estilo_reporte'])){
					           	    $estilo_reporte                    =  $this->data['relacion_otroscompromisos']['estilo_reporte'];
					           	    $_SESSION['opcion_estilo_reporte'] = $estilo_reporte;
				           	    }else{
				           	    	 $estilo_reporte=$_SESSION['opcion_estilo_reporte'];
				           	    }
	           }else{
			           	    $estilo_reporte                    =  $this->data['relacion_otroscompromisos']['estilo_reporte'];
			           	    $_SESSION['opcion_estilo_reporte'] = $estilo_reporte;
	           	    }//fin else






	           	 if(isset($_SESSION['opcion_beneficiarios'])){
			           	 	if(isset($this->data['relacion_otroscompromisos']['beneficiarios'])){
					           	    $opcion_beneficiarios             =  $this->data['relacion_otroscompromisos']['beneficiarios'];
					           	    $_SESSION['opcion_beneficiarios'] = $opcion_beneficiarios;
				           	    }else{
				           	    	$opcion_beneficiarios=$_SESSION['opcion_beneficiarios'];
				           	    }//fin else
	           }else{
				             	if(isset($this->data['relacion_otroscompromisos']['beneficiarios'])){
					           	    $opcion_beneficiarios             =  $this->data['relacion_otroscompromisos']['beneficiarios'];
					           	    $_SESSION['opcion_beneficiarios'] = $opcion_beneficiarios;
				           	    }//fin if
	           	    }//fin else


	           	if(isset($_SESSION['opcion_tipopago'])){
			           		    if(isset($this->data['relacion_otroscompromisos']['tipocompromiso'])){
					           	    $opcion_tipopago             =  $this->data['relacion_otroscompromisos']['tipocompromiso'];
					           	    $_SESSION['opcion_tipopago'] = $opcion_tipopago;
				           	    }else{
				           	    	$opcion_tipopago=$_SESSION['opcion_tipopago'];
				           	    }
	           }else{
				             	if(isset($this->data['relacion_otroscompromisos']['tipocompromiso'])){
					           	    $opcion_tipopago             =  $this->data['relacion_otroscompromisos']['tipocompromiso'];
					           	    $_SESSION['opcion_tipopago'] = $opcion_tipopago;
				           	    }//fin if
	           	    }//fin else


			    if(isset($pagina)){
					$pagina=$pagina;
				}else{
						 $pagina=1;
				}//fin else





                                $this->set('ir','no');


		if($estilo_reporte==1){


			$condicion = " cod_presi      = '$cp'  and
							cod_entidad    = '$ce'  and
							cod_tipo_inst  = '$cti' and
							cod_inst       = '$ci'  and
							cod_dep        = '$cd'  and
							ano_documento  = '$year'";




                               $Tfilas=$this->v_reporte_relacion_otros_compromisos->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/1000);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_reporte_relacion_otros_compromisos->findAll($condicion,null,"numero_documento ASC",1000,$pagina,null);
							        $this->set("datos_compromiso_cuerpo",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos_compromiso_cuerpo",'');
						        }


		}elseif($estilo_reporte==2){

			$beneficiario = strtoupper($opcion_beneficiarios);

			$condicion = " cod_presi      = '$cp'  and
							cod_entidad    = '$ce'  and
							cod_tipo_inst  = '$cti' and
							cod_inst       = '$ci'  and
							cod_dep        = '$cd'  and
							ano_documento  = '$year'  and
			                upper(beneficiario) =  upper('$beneficiario') ";



			                    $Tfilas=$this->v_reporte_relacion_otros_compromisos->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/1000);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_reporte_relacion_otros_compromisos->findAll($condicion,null,"numero_documento ASC",1000,$pagina,null);
							        $this->set("datos_compromiso_cuerpo",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos_compromiso_cuerpo",'');
						        }

		}elseif($estilo_reporte==3){

			$tipocompromiso = strtoupper($opcion_tipopago);

			$condicion = " cod_presi      = '$cp'  and
							cod_entidad    = '$ce'  and
							cod_tipo_inst  = '$cti' and
							cod_inst       = '$ci'  and
							cod_dep        = '$cd'  and
							ano_documento  = '$year'  and
			                cod_tipo_compromiso = '$tipocompromiso'";

			                    $Tfilas=$this->v_reporte_relacion_otros_compromisos->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/1000);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_reporte_relacion_otros_compromisos->findAll($condicion,null,"numero_documento ASC",1000,$pagina,null);
							        $this->set("datos_compromiso_cuerpo",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos_compromiso_cuerpo",'');
						        }

		 }//fin else

		}//fin else
	}//fin null

}//fin function










function buscar_year($var1=null){

  $this->layout = "ajax";
  $_SESSION['ano_compra_consulta'] = $var1;

echo "<script>";
  echo "document.getElementById('estilo_reporte_1').checked=true;";
  echo "document.getElementById('estilo_reporte_2').checked=false;";
echo "</script>";


echo "<script>";
  echo "if(document.getElementById('datos-consulta')){document.getElementById('datos-consulta').innerHTML='';}";
echo "</script>";



}//fin function











function reporte_relacion_ordenes_compra($ir=null, $pagina=null){
	$this->layout="ajax";
	if($ir!=null){
		if($ir=='si'){//si va al formulario
		  $this->set('ano',$ano=$this->ano_ejecucion());
		  $_SESSION['ano_compra_consulta'] = $this->ano_ejecucion();
		  $this->set('ir','si');
		}elseif($ir=='no'){//no va al formulario, pero si va al reporte
		$this->layout="pdf";
		$this->set('ir','no');

		$cp  = $this->Session->read('SScodpresi');
		$ce  = $this->Session->read('SScodentidad');
		$cti = $this->Session->read('SScodtipoinst');
		$ci  = $this->Session->read('SScodinst');
		$cd  = $this->Session->read('SScoddep');

		     if(isset($_SESSION['ano_compra_consulta'])){
			       $ano = $_SESSION['ano_compra_consulta'];
			  }else{
			  	    $ano = $this->ano_ejecucion();

			  	    }//fin else


		      if(isset($_SESSION['opcion_estilo_reporte'])){
				           	     if(isset($this->data['relacion_ordencompra']['estilo_reporte'])){
						           	     $estilo_reporte                    =  $this->data['relacion_ordencompra']['estilo_reporte'];
				           	             $_SESSION['opcion_estilo_reporte'] = $estilo_reporte;
					           	    }else{
					           	    	$opcion_razonsocialios=$_SESSION['opcion_razonsocial'];
					           	    }
	           }else{
				           	    $estilo_reporte                    =  $this->data['relacion_ordencompra']['estilo_reporte'];
				           	    $_SESSION['opcion_estilo_reporte'] = $estilo_reporte;
	           	    }//fin else







	           	 if(isset($_SESSION['opcion_razonsocial'])){

				           	    if(isset($this->data['relacion_ordencompra']['razonsocial'])){
						           	    $opcion_razonsocialios             =  $this->data['relacion_ordencompra']['razonsocial'];
						           	    $_SESSION['opcion_razonsocial'] = $opcion_razonsocialios;
					           	    }else{
					           	    	$opcion_razonsocialios=$_SESSION['opcion_razonsocial'];
					           	    }
	           }else{
				             	if(isset($this->data['relacion_ordencompra']['razonsocial'])){
					           	    $opcion_razonsocialios             =  $this->data['relacion_ordencompra']['razonsocial'];
					           	    $_SESSION['opcion_razonsocial'] = $opcion_razonsocialios;
				           	    }//fin if
	           	    }//fin else


if($pagina==null){$pagina=1;}



		     if($estilo_reporte==1){ $this->set('ir','no');

                                $condicion=$this->SQLCA()." and ano_orden_compra=".$ano." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)";

								$Tfilas=$this->v_relacion_orden_compra_consulta->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/1000);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_relacion_orden_compra_consulta->findAll($condicion,null,"numero_orden_compra ASC",1000,$pagina,null);
							        $this->set("datos_orden_encabezado",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);



						        }else{
						        	$this->set("datos_orden_encabezado",'');
						        }


		 }else if($estilo_reporte==2){ $this->set('ir','no');

			     $razon_social = strtoupper($opcion_razonsocialios);

							    $condicion=$this->SQLCA()." and ano_orden_compra=".$ano." and upper(rif) =  upper('".$razon_social."') and (cod_obra='' or cod_obra='0' or cod_obra IS NULL) ";

								$Tfilas=$this->v_relacion_orden_compra_consulta->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/1000);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_relacion_orden_compra_consulta->findAll($condicion,null,"numero_orden_compra ASC",1000,$pagina,null);
							        $this->set("datos_orden_encabezado",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos_orden_encabezado",'');
						        }
		     }//fin else

		}
	}
}//reporte_relacion_ordenes_compra














function buscar_year_orden_pago($var1=null,$var2=null){
$this->layout="ajax";

	if($var2!=null){
	        $_SESSION['ano_orden_de_pago'] = $var2;
	        echo "<script>";
			  echo "document.getElementById('estilo_reporte_1').checked=false;";
			  echo "document.getElementById('estilo_reporte_2').checked=false;";
			  echo "document.getElementById('estilo_reporte_3').checked=false;";
			  echo "document.getElementById('estilo_reporte_4').checked=false;";
			  echo "document.getElementById('estilo_reporte_5').checked=false;";
			echo "</script>";
	}else{
		    $_SESSION['ano_orden_de_pago'] = $var1;
			echo "<script>";
			  echo "document.getElementById('estilo_reporte_1').checked=true;";
			  echo "document.getElementById('estilo_reporte_2').checked=false;";
			  echo "document.getElementById('estilo_reporte_3').checked=false;";
			echo "</script>";
	}//fin else


echo "<script>";
  echo "if(document.getElementById('datos-consulta')){document.getElementById('datos-consulta').innerHTML='';}";
echo "</script>";




}//fin function










function reporte_relacion_ordenes_pago($ir=null, $pagina=null){
	$this->layout="ajax";
	if($ir!=null){
		if($ir=='si'){//si va al formulario



		  $this->set('ano',$ano=$this->ano_ejecucion());
		  $_SESSION['ano_orden_de_pago'] = $this->ano_ejecucion();
		  $this->set('ir','si');



		}elseif($ir=='no'){//no va al formulario, pero si va al reporte


	           if(isset($_SESSION['ano_orden_de_pago'])){
	           	    $year=$_SESSION['ano_orden_de_pago'];
	           }else{
	           	    $year=$this->ano_ejecucion();
	                }//fin else


	           if(isset($_SESSION['opcion_estilo_reporte'])){
			           	    if(isset($this->data['relacion_ordenpago']['estilo_reporte'])){
					           	    $estilo_reporte                    =  $this->data['relacion_ordenpago']['estilo_reporte'];
					           	    $_SESSION['opcion_estilo_reporte'] = $estilo_reporte;
				           	    }else{
				           	    	 $estilo_reporte=$_SESSION['opcion_estilo_reporte'];
				           	    }
	           }else{
			           	    $estilo_reporte                    =  $this->data['relacion_ordenpago']['estilo_reporte'];
			           	    $_SESSION['opcion_estilo_reporte'] = $estilo_reporte;
	           	    }//fin else






	           	 if(isset($_SESSION['opcion_beneficiarios'])){
			           	 	if(isset($this->data['relacion_ordenpago']['beneficiarios'])){
					           	    $opcion_beneficiarios             =  $this->data['relacion_ordenpago']['beneficiarios'];
					           	    $_SESSION['opcion_beneficiarios'] = $opcion_beneficiarios;
				           	    }else{
				           	    	$opcion_beneficiarios=$_SESSION['opcion_beneficiarios'];
				           	    }//fin else
	           }else{
				             	if(isset($this->data['relacion_ordenpago']['beneficiarios'])){
					           	    $opcion_beneficiarios             =  $this->data['relacion_ordenpago']['beneficiarios'];
					           	    $_SESSION['opcion_beneficiarios'] = $opcion_beneficiarios;
				           	    }//fin if
	           	    }//fin else


	           	if(isset($_SESSION['opcion_tipopago'])){
			           		    if(isset($this->data['relacion_ordenpago']['tipopago'])){
					           	    $opcion_tipopago             =  $this->data['relacion_ordenpago']['tipopago'];
					           	    $_SESSION['opcion_tipopago'] = $opcion_tipopago;
				           	    }else{
				           	    	$opcion_tipopago=$_SESSION['opcion_tipopago'];
				           	    }
	           }else{
				             	if(isset($this->data['relacion_ordenpago']['tipopago'])){
					           	    $opcion_tipopago             =  $this->data['relacion_ordenpago']['tipopago'];
					           	    $_SESSION['opcion_tipopago'] = $opcion_tipopago;
				           	    }//fin if
	           	    }//fin else

			   $condicion=$this->SQLCA()." and ano_orden_pago=".$year;

			    if(isset($pagina)){
					$pagina=$pagina;
				}else{
						 $pagina=1;
				}//fin else



					       if($estilo_reporte==1){


						     $Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/1000);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($condicion,null,"numero_orden_pago ASC",1000,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

						     $this->set('ir','no');



					  }else if($estilo_reporte==2){


						     $beneficiario = strtoupper($opcion_beneficiarios);
								     if(is_int($beneficiario)){
					                     $cedula=$beneficiario;
					                     $cond=$condicion." and cedula_identidad=$cedula";
								     }else{
								     	 $cedula=0;
								     	 $cond=$condicion." and upper(rif)=upper('$beneficiario')  ";
								     	}//fin else


						  	 $Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($cond);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/1000);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($cond,null,"numero_orden_pago ASC",1000,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }


						     $this->set('ir','no');





					  }else if($estilo_reporte==3){

						     $tipopago = $opcion_tipopago;
						  	 $cond=$condicion." and cod_tipo_pago='$tipopago'";
						  	 $denotipopago=$this->cepd03_ordenpago_tipopago->findAll('cod_tipo_pago='.$tipopago);

						    $Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($cond);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/1000);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($cond,null,"numero_orden_pago ASC",1000,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }


						     $this->set('ir','no');




					  }//fin else

		}
	}
}//reporte_relacion_ordenes_pago





























function lista_busqueda_compromiso($var=null) {
	$this->layout="ajax";
	//$this->cepd01_compromiso_cuerpo->getColumnType();
	if(isset($var)){
		$cod=$var;
	}else{
		$cod=$this->data["cepd01_compromiso"]["codido"];
	}

 if(isset($_SESSION['ano_otroscompromisos'])){$Ano=$_SESSION['ano_otroscompromisos'];}else{$Ano=$this->ano_ejecucion();}


						 $this->set('ano',$Ano);
						 $this->set('ejercicio', $Ano);
						 $Tfilas=$this->cepd01_compromiso_cuerpo->findCount($this->SQLCA()." and ano_documento=".$Ano." and numero_documento=".$cod);
						 //echo $Tfilas;
						 if($Tfilas!=0){
						 //$this->set('pag_cant',$pagina.'/'.$Tfilas);
						 //$this->set('ultimo',$Tfilas);
						 $dataCompromiso=$this->cepd01_compromiso_cuerpo->findAll($this->SQLCA()." and ano_documento=".$Ano." and numero_documento=".$cod,null,'ano_documento,numero_documento ASC',1,null,null);
						 foreach ($dataCompromiso as $YYY);
								 //$YYY['cfpd05']['cod_sector'];
							    //////////////////////////////////////////////////////////////////////////
								$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
								$cond1=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior'];
						        $a1=  $this->cugd02_direccionsuperior->findAll($cond.$cond1);
								$x1= $a1[0]['cugd02_direccionsuperior']['denominacion'];
								$this->set('dir_sup',$x1);
								$cond2=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd01_compromiso_cuerpo']['cod_coordinacion'];
						        $a2=  $this->cugd02_coordinacion->findAll($cond.$cond2);
								$x2= $a2[0]['cugd02_coordinacion']['denominacion'];
								$this->set('coordinacion',$x2);
 				                $cond3=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$YYY['cepd01_compromiso_cuerpo']['cod_secretaria'];
						        $a3=  $this->cugd02_secretaria->findAll($cond.$cond3);
								$x3= $a3[0]['cugd02_secretaria']['denominacion'];
								$this->set('secretaria',$x3);
								$cond4=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd01_compromiso_cuerpo']['cod_coordinacion']." and  cod_secretaria=".$YYY['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$YYY['cepd01_compromiso_cuerpo']['cod_direccion'];
						        $a4=  $this->cugd02_direccion->findAll($cond.$cond4);
								$x4= $a4[0]['cugd02_direccion']['denominacion'];
								$this->set('direccion',$x4);
								$tipo_doc=$this->cepd01_tipo_compromiso->findAll("cod_tipo_compromiso=".$YYY['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);

								$this->set("tipo_doc",$tipo_doc[0]["cepd01_tipo_compromiso"]["denominacion"]);
								//$this->traer_beneficiario($YYY['cepd01_compromiso_cuerpo']['rif']);
								$compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($this->SQLCA()." and ano_documento=".$Ano." and numero_documento=".$YYY['cepd01_compromiso_cuerpo']['numero_documento']);

								//$C_A=$this->cugd03_acta_anulacion_cuerpo->findByNumero_acta_anulacion($YYY['cepd01_compromiso_cuerpo']['numero_anulacion']);
								$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($this->SQLCA()." and numero_acta_anulacion=".$YYY['cepd01_compromiso_cuerpo']['numero_anulacion']." and ano_acta_anulacion=".$Ano);
                                //print_r($C_A);
                                if($C_A!=null){
                                	$this->set("concepto_anulacion",$C_A[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"]);
                                }else{
                                	$this->set("concepto_anulacion","");
                                }
                                if($YYY['cepd01_compromiso_cuerpo']['ano_orden_pago']!=0 && $YYY['cepd01_compromiso_cuerpo']['numero_orden_pago']!=0){
                                    $dataOdenpago=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and ano_orden_pago=".$YYY['cepd01_compromiso_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$YYY['cepd01_compromiso_cuerpo']['numero_orden_pago'],array("fecha_orden_pago","cuenta_bancaria","numero_cheque","fecha_cheque","cod_entidad_bancaria"),'ano_orden_pago,numero_orden_pago ASC',1,null,null);
						            $this->set("numero_orden_pago",$YYY['cepd01_compromiso_cuerpo']['numero_orden_pago']);
						            $this->set("fecha_orden_pago",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['fecha_orden_pago']);
                                    $this->set("tiene_ordenpago",true);
                                    //print_r($dataOdenpago);
	                                if($dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria']!=0  && $dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cuenta_bancaria']!=0 && $dataOdenpago[0]['cepd03_ordenpago_cuerpo']['numero_cheque']!=0){
	                                  //'cstd01_entidades_bancarias','cstd01_sucursales_bancarias'
	                                  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria']);
	                                  $this->set("denominacion_bancaria",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]);
	                                  $this->set("nro_cta",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cuenta_bancaria']);
	                                  $this->set("nro_cheque",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['numero_cheque']);
	                                  $this->set("fecha_cheque",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
	                                  $this->set("tiene_cheque",true);
									}else{
										$this->set("denominacion_bancaria","--");
	                                    $this->set("nro_cta","--");
	                                    $this->set("nro_cheque","--");
	                                    $this->set("fecha_cheque","1900-01-01");
	                                    $this->set("tiene_cheque",false);
									}
	                                }else{
	                                	$this->set("numero_orden_pago","--");
						                $this->set("fecha_orden_pago","1900-01-01");
	                                    $this->set("tiene_ordenpago",false);
	                                }



								$this->set('COMPROMISO_PARTIDA',$compromiso_partidas);
								$this->set('COMPROMISO',$dataCompromiso);

						}else{
						$this->set('COMPROMISO','');
						$this->set('errorMessage', 'No se encontrar&oacute;n datos para el n&uacute;mero de compromiso '.$var.' en el a&ntilde;o '.$Ano);
						$this->consultar(1,'No se encontrar&oacute;n datos para el n&uacute;mero de compromiso '.$var);
						$this->render("consultar");

					 }///fin else  del if-else que compara las Tfilas
        if(isset($msj)){
        	if($msj==true)$this->set('Message_existe', 'Compromiso Anulado con exito');
        	else$this->set('errorMessage', 'Anulaci&oacute;n del Compromiso sin exito');
        }
}


function consultar(){
	$this->layout="ajax";
}



function mostrar_orden_compra($numero_ordencomp=null,$ano_ordencomp=null){
	$this->layout="ajax";
	if($numero_ordencomp!=null && $ano_ordencomp!=null){
		$ano_ordencompra = $ano_ordencomp;
		$numero_ordencompra = $numero_ordencomp;
		$pagina =null;
		$data =$this->v_cscd04_ordencompra->findAll($this->condicion()." and ano_orden_compra='$ano_ordencompra' and numero_orden_compra='$numero_ordencompra'",null,'numero_orden_compra ASC',1,null,null);
		$this->set('data', $data);
		foreach($data as $row){
			$numero_orden_compra= $row['v_cscd04_ordencompra']['numero_orden_compra'];
			$condicion_actividad = $row['v_cscd04_ordencompra']['condicion_actividad'];
			$ano_documento = $row['v_cscd04_ordencompra']['ano_orden_compra'];
			$fecha_documento = $row['v_cscd04_ordencompra']['fecha_orden_compra'];
			$num_cotizacion = $row['v_cscd04_ordencompra']['numero_cotizacion'];
			$rif = $row['v_cscd04_ordencompra']['rif'];
		}
		$condicion= $this->condicion();



		if($condicion_actividad==2){

			$fecha_cotizacion2 = $this->cscd03_cotizacion_encabezado_anulado->field('fecha_cotizacion', $conditions = $this->condicion()." and numero_cotizacion='$num_cotizacion' and upper(rif)=upper('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", $order =null);
			$this->set('fecha_cotizacion2', $fecha_cotizacion2);
			$numsolicitud = $this->v_cscd03_cotizacion_anulada->field('v_cscd03_cotizacion_anulada.numero_solicitud', $conditions = $this->condicion()." and v_cscd03_cotizacion_anulada.numero_cotizacion='$num_cotizacion' and upper(rif)=upper('$rif')    and   ano_cotizacion='".$ano_documento."'        ", $order ="numero_cotizacion ASC");
    		$lista = $this->v_cscd03_cotizacion_anulada->findAll($condicion." and numero_cotizacion='$num_cotizacion' and upper(rif)=upper('$rif')  and   ano_cotizacion='".$ano_documento."'",'DISTINCT codigo_prod_serv, numero_cotizacion, expresion, cantidad, descripcion,precio_unitario, total', 'numero_cotizacion ASC', null);
	     	$this->set('lista_cscd02_solicitud_cuerpo', $lista);
	     	$this->set('index_cotizacion', 'v_cscd03_cotizacion_anulada');

			$ano_anulacion         = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.ano_acta_anulacion',    $conditions = $this->condicion()." and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order ="ano_acta_anulacion, numero_acta_anulacion ASC");
			$numero_acta_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.numero_acta_anulacion', $conditions = $this->condicion()." and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order ="ano_acta_anulacion, numero_acta_anulacion ASC");
			$motivo_anulacion      = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.motivo_anulacion',      $conditions = $this->condicion()." and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order ="ano_acta_anulacion, numero_acta_anulacion ASC");


		}else{

			$fecha_cotizacion2 = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.fecha_cotizacion', $conditions = $this->condicion()." and numero_cotizacion='$num_cotizacion' and upper(rif)=upper('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", $order =null);
			$this->set('fecha_cotizacion2', $fecha_cotizacion2);
			$numsolicitud = $this->v_cscd03_cotizacion->field('v_cscd03_cotizacion.numero_solicitud', $conditions = $this->condicion()." and v_cscd03_cotizacion.numero_cotizacion='$num_cotizacion' and upper(rif)=upper('$rif')  and   ano_cotizacion='".$ano_documento."'", $order ="numero_cotizacion ASC");
    	    $lista        = $this->v_cscd03_cotizacion->findAll($condicion." and numero_cotizacion='$num_cotizacion' and upper(rif)=upper('$rif')  and   ano_cotizacion='".$ano_documento."'",'DISTINCT codigo_prod_serv, expresion, cantidad, precio_unitario, descripcion, numero_cotizacion', 'numero_cotizacion ASC', null);
     	    $this->set('lista_cscd02_solicitud_cuerpo', $lista);
     	    $this->set('index_cotizacion', 'v_cscd03_cotizacion');
			$ano_anulacion= 0;
			$numero_acta_anulacion=0;
			$motivo_anulacion = " ";

		}

		$this->set('ano_anulacion', $ano_anulacion);
		$this->set('numero_acta_anulacion', $numero_acta_anulacion);
		$this->set('motivo_anulacion',$motivo_anulacion);

		$ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and numero_orden_compra='$numero_orden_compra' and ano_orden_compra='$ano_ordencompra' ");
		$ordencompra_partidas   = $this->cscd04_ordencompra_partidas->findAll(  $this->condicion()." and numero_orden_compra='$numero_orden_compra' and ano_orden_compra='$ano_ordencompra' ");
		$this->set('ordencompra_partidas', $ordencompra_partidas);
		$this->set('ordencompra_encabezado', $ordencompra_encabezado);

		$this->set('siguiente',$pagina+1);
		$this->set('anterior',$pagina-1);
		$this->set('mostrarA', '');
		$this->set('mostrarS', '');
		$this->set('ultimo', '');
		$this->set('primero', '');
		//$this->render('consulta');

	}else{
		$this->set('mensajeError','Lo siento, algunos datos no llegaron correctamente');
		$this->vacio();
		$this->render('vacio');
	}

}




 function lista_busqueda_ordenpago($var=null) {
	$this->layout="ajax";
		if(isset($var)){
				$numero_busqueda=$var;
		}else{
				 $numero_busqueda=1;
		}//fin else

		 if(isset($_SESSION['ano_orden_de_pago'])){$Ano=$_SESSION['ano_orden_de_pago'];}else{$Ano=$this->ano_ejecucion();}


						 $this->set('ano',$Ano);
						 $this->set('ejercicio', $Ano);
						 $Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($this->SQLCA()." and ano_orden_pago=".$Ano." and numero_orden_pago=".$var);
						 if($Tfilas!=0){
						 $dataOdenpago=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and ano_orden_pago=".$Ano." and numero_orden_pago=".$numero_busqueda,null,'ano_orden_pago,numero_orden_pago ASC',1,null,null);
						 foreach ($dataOdenpago as $vec_op);
						        if($vec_op['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria']!=0 && $vec_op['cepd03_ordenpago_cuerpo']['cod_sucursal']!=0 && $vec_op['cepd03_ordenpago_cuerpo']['cuenta_bancaria']!=0 && $vec_op['cepd03_ordenpago_cuerpo']['numero_cheque']!=0){
                                  //'cstd01_entidades_bancarias','cstd01_sucursales_bancarias'
                                  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$vec_op['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria']);
                                  $this->set("denominacion_bancaria",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]);
                                  $csb=$this->cstd01_sucursales_bancarias->findAll("cod_sucursal=".$vec_op['cepd03_ordenpago_cuerpo']['cod_sucursal']);
                                  $this->set("denominacion_sucursal",$csb[0]["cstd01_sucursales_bancarias"]["denominacion"]);
                                  $this->set("nro_cta",$vec_op['cepd03_ordenpago_cuerpo']['cuenta_bancaria']);
                                  $this->set("nro_cheque",$vec_op['cepd03_ordenpago_cuerpo']['numero_cheque']);
                                  $this->set("fecha_cheque",$vec_op['cepd03_ordenpago_cuerpo']['fecha_cheque']);
                                  $this->set("documento_pago",$vec_op['cepd03_ordenpago_cuerpo']['documento_pago']);
                                  $this->set("tiene_cheque",true);
								}else{
									$this->set("tiene_cheque",false);
								}
								$tipo_doc=$this->cepd03_tipo_documento->findAll("cod_tipo_documento=".$vec_op['cepd03_ordenpago_cuerpo']['cod_tipo_documento']);
								$this->set("tipo",$tipo_doc[0]["cepd03_tipo_documento"]["denominacion"]);
								$ordenpago_partidas=$this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$Ano." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
								$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($this->SQLCA()." and numero_acta_anulacion=".$vec_op['cepd03_ordenpago_cuerpo']['numero_anulacion']);
																if($C_A!=null){
																	$this->set("concepto_anulacion",$C_A[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"]);
																}else{
																	$this->set("concepto_anulacion","");
																}
																$resultado_facturas=$this->cepd03_ordenpago_facturas->findAll($this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
								$resultado_tipo_pago=$this->cepd03_ordenpago_tipopago->findAll("cod_tipo_pago=".$vec_op['cepd03_ordenpago_cuerpo']['cod_tipo_pago']);
								$frecuencia_de_pago=array(1=>"Una sola vez",2=>"Quincenal anticipada",3=>"Quincenal vencida",4=>"Mensual Anticipada",5=>"Mensual Vencida");
										$tipo_de_orden=array(1=>"Permanente",2=>"Especial");
										$this->set('frecuencia_de_pago',$frecuencia_de_pago);
										$this->set('tipo_de_orden',$tipo_de_orden);
								$this->set('facturas',$resultado_facturas);
								$this->set('tipo_pago',$resultado_tipo_pago);
								$this->set('ORDENPAGO_PARTIDA',$ordenpago_partidas);
								$this->set('ORDENPAGO_CUERPO',$dataOdenpago);
						}else{
						$this->set('COMPROMISO','');
						$this->set('errorMessage', 'No se encontrar&oacute;n datos');

					 }///fin else  del if-else que compara las Tfilas
}//fin lista_busqueda

}//fin class
?>
