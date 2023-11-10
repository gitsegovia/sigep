<?php

 class Shp900CobranzaController extends AppController{
 	var $name = "shp900_cobranza";
	var $uses = array('v_shd001_registro_contribuyentes','shd001_registro_contribuyentes','shd900_cobranza_diaria','shd900_cobranza_numero','shd003_codigo_ingresos','shd900_planillas_deuda_cobro_detalles',
                       'shd100_actividades', 'cnmd06_profesiones','cugd01_republica', 'cugd01_estados','ccfd04_cierre_mes','shd900_cobranza_acumulada','shd002_cobranza_realizada',
                      'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados', 'cugd01_vialidad', 'cugd01_vereda','v_shd900_cobranza_diaria','shd002_cobranza_pendiente','shd000_control_actualizacion',
                      'cstd02_cuentas_bancarias','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','v_consulta_ingreso','v_cobranza_diaria','shd002_cobradores','shd000_arranque','cfpd03','v_cobranza',
                      'shd100_patente','shd200_vehiculos','shd300_propaganda','shd400_propiedad','shd500_aseo_domiciliario','shd600_aprobacion_arrendamiento','shd700_credito_vivienda','shd900_cobranza_diaria_planillas','v_shd900_pdpcdc',
                      'shd100_declaracion_ingresos', 'shd100_declaracion_ingresos_facturado', 'shd100_declaracion_ingresos_convenimientos', 'shd100_dec_ing_fac_conve', 'v_cobranza_recibo', 'v_ingresos_fijos');
 	//,

 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Form');

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
function index($var=null){///////////////<<--INDEX
	 $this->layout = "ajax";
	 //$ano=$this->ano_ejecucion();
	 $ano = $this->shd000_arranque->ano($this->SQLCA());
     $maxi=$this->shd900_cobranza_numero->findCount($this->SQLCA()." and ano_comprobante=".$ano." and situacion=1");
      //$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()."  ORDER BY numero_compromiso ASC LIMIT 1");
      if($maxi==0){
         $this->set("errorMessage","Verifique el n&uacute;mero de control de compromisos");
      	 $this->set("numero_compromiso","");
      	 $this->redirect("/shp900_cobranza_numero/");
      }
}//fin index

function index2($var=null){
	$this->layout = "ajax";
 	$this->data = null;
 	//$ano=$this->ano_ejecucion();
 	$ano = $this->shd000_arranque->ano($this->SQLCA());
	$this->set('lista_entidad_bancaria', $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));
    $numero_comprobante=$this->shd900_cobranza_numero->field('shd900_cobranza_numero.numero_comprobante', $this->SQLCA()." and ano_comprobante='$ano' and situacion=1", $order ="numero_comprobante ASC");
    if(!empty($numero_comprobante)){
  		$this->set('numero_comprobante',          $numero_comprobante);
  		$this->set('ano_comprobante',          $ano);
  		$this->shd900_cobranza_numero->execute('UPDATE shd900_cobranza_numero set situacion=2 WHERE '.$this->SQLCA()." and ano_comprobante='$ano' and numero_comprobante = '$numero_comprobante'");
    }else{
  		$this->set('msg_error1', $msg_error1 = 'NECESITA CREAR LOS NUMEROS DE COMPROBANTE PARA CONTINUAR');
  		$this->redirect('/shp900_cobranza_numero');
	    return;
    }
    $this->concatena($this->shd003_codigo_ingresos->generateList(null, 'cod_ingreso ASC', null, '{n}.shd003_codigo_ingresos.cod_ingreso', '{n}.shd003_codigo_ingresos.denominacion'), 'lista_ingreso');

}//fin index2

function cargar_codigo_ingreso ($cod_ingreso=null) {
   $this->layout="ajax";
   $cond =" cod_ingreso=".$cod_ingreso."";
   $d = $this->shd003_codigo_ingresos->findAll($cond);
   $this->set('denominacion',$d[0]['shd003_codigo_ingresos']['denominacion']);
   $this->set('cod_ingreso',$cod_ingreso);
   $this->set('data',$d);
}//fin funcion cargar_sucursal

function cargar_deuda_pendiente ($rif_cedula,$cod_ingreso) {
   $this->layout="ajax";
   $cod_presi     = $this->Session->read('SScodpresi');
   $cod_entidad   = $this->Session->read('SScodentidad');
   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
   $cod_inst      = $this->Session->read('SScodinst');
   $cod_dep       = $this->Session->read('SScoddep');
   $ano_arranque = $this->shd000_arranque->ano($this->SQLCA());
   $mes_arranque = $this->shd000_arranque->mes($this->SQLCA());
   $cond =" cod_ingreso=".$cod_ingreso."";
   $d = $this->shd003_codigo_ingresos->findAll($cond);
   extract($d[0]['shd003_codigo_ingresos']);
     //   $condicion = $thhis->shd000_control_actualizacion->condicion2($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$ano_arranque,$cod_ingreso,$mes_arranque);


             if($_SESSION["utiliza_planillas_liquidacion_previa"]==1 || $_SESSION["utiliza_planillas_liquidacion_previa"]=='1'){
   	 	$data = $this->v_shd900_pdpcdc->findAll($this->SQLCA()." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec and cod_auxiliar=$cod_auxiliar and rif_cedula='$rif_cedula' and cancelado=2 and condicion=2",null,"ano,mes ASC");
        $this->set('data',$data);
        $this->set("opcion", 1);
        //echo "gola";
       }else if($_SESSION["utiliza_planillas_liquidacion_previa"]==2 && $cod_ingreso==1){
        $data = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='$rif_cedula' and condicion_actividad = 1 and cancelado=2 ",null," ano_declaracion, numero_declaracion ASC");
        $this->set('data',$data);
        $this->set("opcion", 2);
        $data2 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and cancelado=2",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
        $this->set('data2',$data2);
       }else{
       	$data = $this->v_shd900_pdpcdc->findAll($this->SQLCA()." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec and cod_auxiliar=$cod_auxiliar and rif_cedula='$rif_cedula' and cancelado=2 and condicion=2",null,"ano,mes ASC");
        $this->set('data',$data);
        $this->set("opcion", 1);
       }



         //echo $this->SQLCA()." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec and cod_auxiliar=$cod_auxiliar and rif_cedula='$rif_cedula' and cancelado=2 and condicion=2";

   $_SESSION["items_deuda"]=array();
   $_SESSION["contador"]=0;
	if($cod_ingreso==1){
		$modelo="shd100_patente";
	}else if($cod_ingreso==2){
		$modelo="shd200_vehiculos";
	}else if($cod_ingreso==3){
		$modelo="shd300_propaganda";
	}else if($cod_ingreso==4){
		$modelo="shd400_propiedad";
	}else if($cod_ingreso==5){
		$modelo="shd500_aseo_domiciliario";
	}else if($cod_ingreso==6){
		$modelo="shd600_aprobacion_arrendamiento";
	}else if($cod_ingreso==7){
		$modelo="shd700_credito_vivienda";
	}
	$ricico=$this->$modelo->findAll($this->SQLCA()." and rif_cedula='$rif_cedula'",'rif_ci_cobrador');
	$this->set('rif_ci_cobrador',count($ricico)>0?$ricico[0][$modelo]['rif_ci_cobrador']:0);
	$this->set('cod_ingreso',$cod_ingreso);
	//shd100_patente -01
	//shd200_vehiculos -02
	//shd300_propaganda -03
	//shd400_propiedad -04
	//shd500_aseo_domiciliario -05
	//shd600_aprobacion_arrendamiento -06
	//shd700_credito_vivienda -07
}//fin funcion function_name

function pasar_deuda ($cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $cod_auxiliar=null, $rif_cedula=null, $ano=null, $mes=null, $numero_planilla=null, $cod_numero_catastral_pla=null, $radio=null) {
   $this->layout="ajax";

if($cod_partida!="no"){

	  if($_SESSION["utiliza_planillas_liquidacion_previa"]==1 || $_SESSION["utiliza_planillas_liquidacion_previa"]=='1'){

   	 	     $data = $this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar and rif_cedula='$rif_cedula' and ano=$ano and mes=$mes and numero_planilla=$numero_planilla",null,"ano,mes ASC");
		     extract($data[0]['shd900_planillas_deuda_cobro_detalles']);
		     $this->set('data',$data);
		     $_SESSION["contador"]=isset($_SESSION["contador"])?$_SESSION["contador"]+1:1;
		     $i= $_SESSION["contador"];
		     $vec[$i]['ano']=$ano;
			 $vec[$i]['mes']=$mes;
			 $vec[$i]['numero_planilla']=$numero_planilla;
			 $vec[$i]['deuda_vigente']=$deuda_vigente;
			 $vec[$i]['monto_recargo']=$monto_recargo;
			 $vec[$i]['monto_multa']=$monto_multa;
			 $vec[$i]['monto_intereses']=$monto_intereses;
			 $vec[$i]['monto_descuento']=$monto_descuento;
			 $vec[$i]['total']=($deuda_vigente+$monto_recargo+$monto_multa+$monto_intereses)-$monto_descuento;
			 $vec[$i]['cod_planilla_catastral_pla']=$cod_numero_catastral_pla;
			 $vec[$i]['id']=$i;
			 $_SESSION["items_deuda"]=isset($_SESSION["items_deuda"])?$_SESSION["items_deuda"]+$vec:$vec;
			 $this->set('vector',$_SESSION["items_deuda"]);
			 $this->set('y',$i);

            $this->set("opcion", 1);

       }else if($_SESSION["utiliza_planillas_liquidacion_previa"]==2 && $numero_planilla==null){


	         if($rif_cedula!=null){  $numero_convenimiento=$rif_cedula;
						             $data2 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$cod_generica."' and ano_declaracion='".$cod_especifica."' and numero_declaracion='".$cod_sub_espec."' and ano_convenimiento='".$cod_auxiliar."' and numero_convenimiento='".$numero_convenimiento."' and cancelado=2",null," ano_declaracion, numero_declaracion ASC");
						             $this->set('data2',$data2);
						             extract($data2[0]['shd100_declaracion_ingresos_convenimientos']);
							         $_SESSION["contador"]=isset($_SESSION["contador"])?$_SESSION["contador"]+1:1;
								     $i= $_SESSION["contador"];
								     $vec[$i]['declaracion_ingresos'] = "si";
								     $vec[$i]['convenimiento']        = "si";
								     $vec[$i]['ano_declaracion']      = $ano_declaracion;
									 $vec[$i]['numero_declaracion']   = $numero_declaracion;
									 $vec[$i]['ano_convenimiento']    = $ano_convenimiento;
									 $vec[$i]['numero_convenimiento'] = $numero_convenimient;
									 $vec[$i]['monto_deuda']          = $monto_deuda;
									 $vec[$i]['fecha_acordada_pago']  = $fecha_acordada_pago;
									 $vec[$i]['monto_convenido']      = $monto_convenido;
									 $vec[$i]['deuda_pendiente']      = $deuda_pendiente;
									 $vec[$i]['fecha_cancelacion']    = $fecha_cancelacion;
									 $vec[$i]['cancelado']            = $cancelado;
						             $vec[$i]['id']=$i;
									 $_SESSION["items_deuda"]=isset($_SESSION["items_deuda"])?$_SESSION["items_deuda"]+$vec:$vec;
									 $this->set('vector',$_SESSION["items_deuda"]);
									 $this->set('y',$i);
							         $this->set("opcion", 2);

						             $data_aux = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and condicion_actividad = 1 and cancelado=2 and rif_cedula='".$cod_generica."' and ano_declaracion='".$cod_especifica."' and numero_declaracion='".$cod_sub_espec."'   ",null," ano_declaracion, numero_declaracion ASC");

						             $this->set("fecha_declaracion", $data_aux[0]["shd100_declaracion_ingresos"]["fecha_declaracion"]);
						             $this->set("periodo_desde",     $data_aux[0]["shd100_declaracion_ingresos"]["periodo_desde"]);
						             $this->set("periodo_hasta",     $data_aux[0]["shd100_declaracion_ingresos"]["periodo_hasta"]);
	         }else{$numero_convenimiento=0;
							         $data = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and condicion_actividad = 1 and cancelado=2 and rif_cedula='".$cod_generica."' and ano_declaracion='".$cod_especifica."' and numero_declaracion='".$cod_sub_espec."'   ",null," ano_declaracion, numero_declaracion ASC");
							         $this->set('data',$data);
							         extract($data[0]['shd100_declaracion_ingresos']);
							         $_SESSION["contador"]=isset($_SESSION["contador"])?$_SESSION["contador"]+1:1;
								     $i= $_SESSION["contador"];
								     $vec[$i]['declaracion_ingresos'] = "si";
								     $vec[$i]['ano_declaracion']      = $ano_declaracion;
									 $vec[$i]['numero_declaracion']   = $numero_declaracion;
									 $vec[$i]['periodo_desde']        = $periodo_desde;
									 $vec[$i]['periodo_hasta']        = $periodo_hasta;
									 $vec[$i]['fecha_declaracion']    = $fecha_declaracion;
									 $vec[$i]['ingresos_declarados']  = $ingresos_declarados;
									 $vec[$i]['monto_impuesto']       = $monto_impuesto;
									 $vec[$i]['monto_exonerado']      = $monto_exonerado;
									 $vec[$i]['id']=$i;
									 $_SESSION["items_deuda"]=isset($_SESSION["items_deuda"])?$_SESSION["items_deuda"]+$vec:$vec;
									 $this->set('vector',$_SESSION["items_deuda"]);
									 $this->set('y',$i);
							         $this->set("opcion", 2);
							         $this->set('data2',array());
	         }//fin else

       }else{

       	     $data = $this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar and rif_cedula='$rif_cedula' and ano=$ano and mes=$mes and numero_planilla=$numero_planilla",null,"ano,mes ASC");
		     extract($data[0]['shd900_planillas_deuda_cobro_detalles']);
		     $this->set('data',$data);
		     $_SESSION["contador"]=isset($_SESSION["contador"])?$_SESSION["contador"]+1:1;
		     $i= $_SESSION["contador"];
		     $vec[$i]['ano']=$ano;
			 $vec[$i]['mes']=$mes;
			 $vec[$i]['numero_planilla']=$numero_planilla;
			 $vec[$i]['deuda_vigente']=$deuda_vigente;
			 $vec[$i]['monto_recargo']=$monto_recargo;
			 $vec[$i]['monto_multa']=$monto_multa;
			 $vec[$i]['monto_intereses']=$monto_intereses;
			 $vec[$i]['monto_descuento']=$monto_descuento;
			 $vec[$i]['total']=($deuda_vigente+$monto_recargo+$monto_multa+$monto_intereses)-$monto_descuento;
			 $vec[$i]['cod_planilla_catastral_pla']=$cod_numero_catastral_pla;
			 $vec[$i]['id']=$i;
			 $_SESSION["items_deuda"]=isset($_SESSION["items_deuda"])?$_SESSION["items_deuda"]+$vec:$vec;
			 $this->set('vector',$_SESSION["items_deuda"]);
			 $this->set('y',$i);

			 $this->set("opcion", 1);


       }//fin else


}else{

             if($_SESSION["utiliza_planillas_liquidacion_previa"]==1){
            $this->set("opcion", 1);
       }else if($_SESSION["utiliza_planillas_liquidacion_previa"]==2 && $cod_especifica==1){
	        $this->set("opcion", 2);
	        $data2 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$cod_generica."' and cancelado=2",null," ano_declaracion, numero_declaracion ASC");
            $this->set('data2',$data2);
       }else{
       	    $this->set("opcion", 1);
       }


}
if($cod_partida!="no" && $numero_planilla!=null){
$d = $this->shd003_codigo_ingresos->findAll("cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_subespec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar");
$this->set("cod_ingreso", $d[0]["shd003_codigo_ingresos"]["cod_ingreso"]);
}else{$this->set("cod_ingreso", "");}


}//fin funcion function_name





function cargar_select_ingreso ($rif_cedula) {
   $this->layout="ajax";
   $this->set('rif_cedula',$rif_cedula);
   $this->concatena($this->shd003_codigo_ingresos->generateList(null, 'cod_ingreso ASC', null, '{n}.shd003_codigo_ingresos.cod_ingreso', '{n}.shd003_codigo_ingresos.denominacion'), 'lista_ingreso');
}//fin funcion cargar_select_ingreso




function cargar_sucursal ($i,$cod_entidad=null) {
   $this->layout="ajax";
   if(isset($cod_entidad) && $cod_entidad!=null){
		$cond =" cod_entidad_bancaria=".$cod_entidad;
		$lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
   }//fin if
   if($lista==""){$lista = array(); $this->set('vector',$lista);}else{$this->set('vector',$lista);}
   $ENTIDAD = $this->cstd01_entidades_bancarias->findAll($cond);
   $this->set('denominacion_entidad',$ENTIDAD[0]['cstd01_entidades_bancarias']['denominacion']);
   $this->set('cod_entidad',mascara($cod_entidad,4));
   $this->set('i',$i);
   //pr($lista);
}//fin funcion cargar_sucursal

function cargar_cuenta ($i,$cod_entidad=null,$cod_sucursal) {
   $this->layout="ajax";
   if(isset($cod_entidad) && $cod_entidad!=null){
		$cond =" cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$cod_sucursal;
		$lista=  $this->cstd02_cuentas_bancarias->generateList($this->SQLCA()." and ".$cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
   }//fin if
   if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->concatena($lista,'vector');}
   //echo $cond;
   $ENTIDAD = $this->cstd01_sucursales_bancarias->findAll($cond);
   $this->set('denominacion',$ENTIDAD[0]['cstd01_sucursales_bancarias']['denominacion']);
   $this->set('cod_sucursal',mascara($cod_sucursal,4));
   $this->set('i',$i);
}//fin funcion cargar_sucursal

function buscar_constribuyente($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_codigos_ingresos($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var2%') or (razon_social_nombres LIKE '%$var2%'))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var2%') or (razon_social_nombres LIKE '%$var2%'))   ",null,"rif_cedula ASC",50,1,null);
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
						$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var22%') or (razon_social_nombres LIKE '%$var22%'))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var22%') or (razon_social_nombres LIKE '%$var22%'))  ",null,"rif_cedula ASC",50,$pagina,null);
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


function seleccion_busqueda_venta($var1=null){
$this->layout="ajax";
$datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$var1."'");
	if($datos != null){
	$cod_profesion=$datos[0]["shd001_registro_contribuyentes"]["profesion"];
	$cod_pais=$datos[0]["shd001_registro_contribuyentes"]["cod_pais"];
	$cod_estado=$datos[0]["shd001_registro_contribuyentes"]["cod_estado"];
	$cod_municipio=$datos[0]["shd001_registro_contribuyentes"]["cod_municipio"];
	$cod_parroquia=$datos[0]["shd001_registro_contribuyentes"]["cod_parroquia"];
	$cod_centro_poblado=$datos[0]["shd001_registro_contribuyentes"]["cod_centro_poblado"];
	$cod_calle_avenida=$datos[0]["shd001_registro_contribuyentes"]["cod_calle_avenida"];
	$cod_vereda_edificio=$datos[0]["shd001_registro_contribuyentes"]["cod_vereda_edificio"];
	$pais=$this->cugd01_republica->findAll('cod_republica='.$cod_pais);
	$estados=$this->cugd01_estados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado);
	$municipios=$this->cugd01_municipios->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio);
	$parroquias=$this->cugd01_parroquias->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia);
	$centros=$this->cugd01_centropoblados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado);
	$vialidad=$this->cugd01_vialidad->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida);
	$vereda=$this->cugd01_vereda->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida.' and cod_vereda='.$cod_vereda_edificio);
	$profesiones=$this->cnmd06_profesiones->findAll('cod_profesion='.$cod_profesion);
	$this->set('profesion',$profesiones);
	$this->set('pais',$pais);
	$this->set('estados',$estados);
	$this->set('municipios',$municipios);
	$this->set('parroquias',$parroquias);
	$this->set('centros',$centros);
	$this->set('vialidad',$vialidad);
	$this->set('vereda',$vereda);
	$this->set('datos',$datos);



$this->set('datos',$datos);
$resul = javascript_encode($datos[0]['shd001_registro_contribuyentes']['razon_social_nombres'], 1);
   echo'<script>';
			 echo"document.getElementById('deno_rif').value = \"$resul\"; ";
			  echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
   echo'</script>';
					/*echo "<script>";
					    echo "document.getElementById('deno_rif').value='".$datos[0]['shd001_registro_contribuyentes']['razon_social_nombres']."';   ";
					    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
					echo "</script>";*/
}else{
	$vacio='';
					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$vacio."';   ";
						echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
					echo "</script>";
}

}//fin function


function guardar(){
	$this->layout = "ajax";//pr($this->data);
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
    $ano = $this->shd000_arranque->ano($this->SQLCA());
    $mes = $this->shd000_arranque->mes($this->SQLCA());
	extract($this->data['shp900_cobranza']);
	$codigo_ingreso = explode('.',$cod_ingreso);
	$values = array();
	$values[] = $cod_presi;
	$values[] = $cod_entidad;
	$values[] = $cod_tipo_inst;
	$values[] = $cod_inst;
	$values[] = $cod_dep;
	$values[] = $ano_comprobante;
	$values[] = $numero_comprobante;
	$values[] = $cod_partida;
	$values[] = $cod_generica;//cod_generica;
	$values[] = $cod_especifica;//cod_especifica;
	$values[] = $cod_sub_espec;//cod_sub_espec;
	$values[] = $cod_auxiliar;//cod_auxiliar;
	$values[] = "'".cambiar_formato_fecha($fecha_comprobante)."'";//fecha_comprobante;
	$values[] = "'".$rif_constribuyente."'";//rif_cedula;
	$values[] = "'".$concepto_comprobante."'";
	$values[] = $deuda_anterior  = $this->Formato1($deuda_anterior);
    $values[] = $deuda_vigente   = $this->Formato1($deuda_vigente);
	$values[] = $monto_recargo   = $this->Formato1($monto_recargo);
	$values[] = $monto_multa     = $this->Formato1($monto_multa);
	$values[] = $monto_intereses = $this->Formato1($monto_intereses);
	$values[] = $monto_descuento = $this->Formato1($monto_descuento);
	$values[] = $a[1] = empty($cod_entidad1)?'0':$cod_entidad1;
	$values[] = $a[2] = empty($cod_sucursal1)?'0':$cod_sucursal1;
	$values[] = $a[3] = empty($numero_cuenta1)?'0':$numero_cuenta1;//cuenta_bancaria_deposito;
	$values[] = $a[4] = empty($numero_deposito)?'0':$numero_deposito;
	$values[] = $monto_deposito=$a[1]==0?0:$this->Formato1($monto_deposito);
	$fecha_deposito = !empty($fecha_deposito) && $a[1]!=0?cambiar_formato_fecha($fecha_deposito):'1900-01-01';
	$values[] = "'".$fecha_deposito."'";
	$values[] = $a[5] = empty($cod_entidad2)?'0':$cod_entidad2;
	$values[] = $a[6] = empty($cod_sucursal2)?'0':$cod_sucursal2;
	$values[] = $a[7] = empty($numero_cuenta2)?'0':$numero_cuenta2;
	$values[] = $a[8] = empty($numero_nota_credito)?'0':$numero_nota_credito;
	$values[] = $monto_nota_credito= $a[5]==0?0:$this->Formato1($monto_nota_credito);
	$fecha_nota_credito = !empty($fecha_nota_credito) && $a[5]!=0?cambiar_formato_fecha($fecha_nota_credito):'1900-01-01';
	$values[] = "'".$fecha_nota_credito."'";
	$values[] = $a[9] = empty($cod_entidad3)?'0':$cod_entidad3;
	$values[] = $a[10] = empty($cod_sucursal3)?'0':$cod_sucursal3;
	$values[] = $a[11] = empty($numero_cuenta3)?'0':$numero_cuenta3;
	$values[] = $a[12] = empty($numero_cheque)?'0':$numero_cheque;
	$values[] = $monto_cheque=$a[9]==0?0:$this->Formato1($monto_cheque);
	$fecha_cheque = !empty($fecha_cheque)&&$a[9]!=0?cambiar_formato_fecha($fecha_cheque):'1900-01-01';
	$values[] = "'".$fecha_cheque."'";
	$p1 = $this->Formato1($deuda_vigente)+$this->Formato1($deuda_anterior)+$this->Formato1($monto_recargo)+$this->Formato1($monto_multa)+$this->Formato1($monto_intereses);
	$p2 = $this->Formato1($monto_descuento);
	$p2 = $p2>=$p1?0:$p2;
	$monto_efectivo = $p1-$p2;
	$monto_total = $monto_efectivo;
	//$monto_efectivo = $monto_efectivo-$this->Formato1($monto_deposito)-$this->Formato1($monto_nota_credito)-$this->Formato1($monto_cheque);
	$monto_efectivo = $monto_efectivo-$monto_deposito-$monto_nota_credito-$monto_cheque;
	$values[] = $monto_efectivo;
	$values[] = 1;
	$values[] = "'".date('Y-m-d')."'";//fecha_registro;
	$values[] = "'".$_SESSION['nom_usuario']."'";//username_registro;
	$values[] = 0;
	$values[] = 0;
	$values[] = "'1900-01-01'";
	$values[] = "'0'";
	$values[] = "'".$rif_ci_cobrador."'";
	$cd  = ($a[1]=='0' && $a[2]=='0' && $a[3]=='0' && $a[4]=='0')?0:1;
	$cnc = ($a[5]=='0' && $a[6]=='0' && $a[7]=='0' && $a[8]=='0')?0:1;
	$cch = ($a[9]=='0' && $a[10]=='0' && $a[11]=='0' && $a[12]=='0')?0:1;
	$cpe = $monto_efectivo!=0?1:0;
	$cmd = $monto_descuento!=0?1:0;

	$values2 = array();
	$values2[] = $cod_presi;
	$values2[] = $cod_entidad;
	$values2[] = $cod_tipo_inst;
	$values2[] = $cod_inst;
	$values2[] = $cod_dep;
	$values2[] = divide_fecha($fecha_comprobante,'ANO');
	$values2[] = divide_fecha($fecha_comprobante,'MES');
	$values2[] = divide_fecha($fecha_comprobante,'DIA');
	$values2[] = $cod_partida;
	$values2[] = $cod_generica;//cod_generica;
	$values2[] = $cod_especifica;
	$values2[] = $cod_sub_espec;
	$values2[] = $cod_auxiliar;
	$values2[] = $deuda_vigente;
	$values2[] = $deuda_anterior;
	$values2[] = $monto_recargo;
	$values2[] = $monto_multa;
	$values2[] = $monto_intereses;
	$values2[] = $monto_descuento;
	$values2[] = $cd;
	$values2[] = $monto_deposito;
	$values2[] = $cnc;
	$values2[] = $monto_nota_credito;
	$values2[] = $cch;
	$values2[] = $monto_cheque;
	$values2[] = $cmd;
	$values2[] = $cpe;
	$values2[] = $monto_efectivo;

	$values3 = array();
	$values3[] = $cod_presi;
	$values3[] = $cod_entidad;
	$values3[] = $cod_tipo_inst;
	$values3[] = $cod_inst;
	$values3[] = $cod_dep;
	$values3[] = $ano_comprobante;
	$values3[] = $cod_partida;
	$values3[] = $cod_generica;
	$values3[] = $cod_especifica;
	$values3[] = $cod_sub_espec;
	$values3[] = $cod_auxiliar;
	$values3[] = 0;
	$values3[] = 0;
	$values3[] = 0;
	$values3[] = 0;
	$values3[] = $monto_total;


	$condicion_acumulado=$this->SQLCA()." and ano=".date('Y')." and mes=".date('m')." and dia=".date('d')." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
	$condicion_cfpd03=$this->SQLCA()." and ano=".$ano_comprobante." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
	$sql_insert        = "INSERT INTO shd900_cobranza_diaria VALUES (".implode(',',$values).");";
	$sql_insert_cfpd03 = "INSERT INTO cfpd03 VALUES (".implode(',',$values3).");";
    $sql_insert_acumulada = "INSERT INTO shd900_cobranza_acumulada VALUES (".implode(',',$values2).");";
    $sql_update_acumulada = "UPDATE shd900_cobranza_acumulada SET
															       deuda_vigente   = deuda_vigente+$deuda_vigente,
															       deuda_anterior  = deuda_anterior+$deuda_anterior,
															       monto_recargo   = monto_recargo+$monto_recargo,
															       monto_multa     = monto_multa+$monto_multa,
															       monto_intereses = monto_intereses+$monto_intereses,
															       monto_descuento = monto_descuento+$monto_descuento,
															       cantidad_depositos = cantidad_depositos+$cd,
															       monto_depositos    = monto_depositos+$monto_deposito,
															       cantidad_notas_credito  = cantidad_notas_credito+$cnc,
															       monto_notas_credito     = monto_notas_credito+$monto_nota_credito,
															       cantidad_cheques        = cantidad_cheques+$cch,
															       monto_cheques           = monto_cheques+$monto_cheque,
															       cantidad_pagos_efectivo = cantidad_pagos_efectivo+$cpe,
															       cantidad_descuento      = cantidad_descuento+$cmd,
															       monto_pagos_efectivo    = monto_pagos_efectivo+$monto_efectivo  WHERE ".$condicion_acumulado;
        $this->shd900_cobranza_numero->execute("BEGIN;");
		$sw = $this->shd900_cobranza_numero->execute($sql_insert);
		if($sw>1){
		    $c_a = $this->shd900_cobranza_acumulada->findCount($condicion_acumulado);
		    if($c_a==0){
		    	$X1=$this->shd900_cobranza_acumulada->execute($sql_insert_acumulada);
		    }else{
		    	$X1=$this->shd900_cobranza_acumulada->execute($sql_update_acumulada);
		    }
		    if($X1>1){
		    	$contar_cfpd03 = $this->cfpd03->findCount($condicion_cfpd03);
		    	if($contar_cfpd03==0){
		    		$sw2 = $this->shd900_cobranza_numero->execute($sql_insert_cfpd03);
		    	}else{
		    		$sw2 = $this->shd900_cobranza_numero->execute("UPDATE cfpd03 SET monto_cobrado=monto_cobrado+$monto_total WHERE ".$condicion_cfpd03);
		    	}
		    	if($sw2>1){
		    		//actualizar condicion en la planila detalles
		    		$meses=array(1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre');
		    		$update_planilla_detalles="";
		    		$insert_cobranza_planilla="INSERT INTO shd900_cobranza_diaria_planillas VALUES ";
                    $vect=$_SESSION["items_deuda"];
                    $insert_cobranza_plan=array();
                    $sql_shd100_declaracion_ingresos_facturado   = " INSERT INTO shd100_declaracion_ingresos_facturado VALUES ";
                    $sql_shd100_declaracion_ingresos_facturado_2 = array();

                    $sql_shd100_dec_ing_fac_conve   = " INSERT INTO shd100_declaracion_ingresos_facturado_convenimientos VALUES ";
                    $sql_shd100_dec_ing_fac_conve_2 = array();

                    $contar_facturado          = 0;
                    $contar_facturado_convenio = 0;
                    $update_documento_detalles                   = "";
                     for($z=1;$z<=count($vect);$z++){
	                    	if(isset($vect[$z]['declaracion_ingresos'])){
	                    		 if(isset($vect[$z]['convenimiento'])){
	                    		 	 $deuda_pendiente                               = $vect[$z]['deuda_pendiente'];
									 $fecha_declaracion                             = $vect[$z]['fecha_acordada_pago'];
	                                 $mes_rebajar                                   = $fecha_declaracion[5].$fecha_declaracion[6];
			                    	 $ano_rebajar                                   = $vect[$z]['ano_declaracion'];
			                    	 $numero_documento                              = $vect[$z]['numero_declaracion'];
			                    	 $ano_convenimiento                             = $vect[$z]['ano_convenimiento'];
			                    	 $numero_convenimiento                          = $vect[$z]['numero_convenimiento'];
			                    	 $monto_convenido                               = $vect[$z]['monto_convenido'];
			                    	 $fecha_cancelacion                             = date("Y-m-d");
			                    	 if($deuda_pendiente==0){
			                    	 	$update_documento_detalles         .= "UPDATE shd100_declaracion_ingresos                SET cancelado=1 WHERE ".$this->SQLCA()." and rif_cedula='$rif_constribuyente'  and ano_declaracion=$ano_rebajar and numero_declaracion='".$numero_documento."'; ";
			                    	 }
			                    	    $update_documento_detalles         .= "UPDATE shd100_declaracion_ingresos_convenimientos SET cancelado=1, fecha_cancelacion='".$fecha_cancelacion."'   WHERE ".$this->SQLCA()." and rif_cedula='$rif_constribuyente'  and ano_declaracion=$ano_rebajar and numero_declaracion='".$numero_documento."' and ano_convenimiento='".$ano_convenimiento."' and numero_convenimiento='".$numero_convenimiento."'; ";
			                    	    $update_documento_detalles         .= "UPDATE shd100_declaracion_ingresos                SET acumulado_pagos_parciales=acumulado_pagos_parciales+".$monto_convenido."            WHERE ".$this->SQLCA()." and rif_cedula='$rif_constribuyente'  and ano_declaracion=$ano_rebajar and numero_declaracion='".$numero_documento."'; ";
			                    	    $sql_shd100_dec_ing_fac_conve_2[] = " ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '$rif_constribuyente', $ano_comprobante, '$numero_comprobante', $ano_rebajar, '$numero_documento', '$ano_convenimiento', '$numero_convenimiento')";
	                    		        $contar_facturado_convenio++;
	                    		 }else{
	                    		 	 $fecha_declaracion                             = $vect[$z]['fecha_declaracion'];
	                                 $mes_rebajar                                   = $fecha_declaracion[5].$fecha_declaracion[6];
			                    	 $ano_rebajar                                   = $vect[$z]['ano_declaracion'];
			                    	 $numero_documento                              = $vect[$z]['numero_declaracion'];
			                    	 $update_documento_detalles                    .= "UPDATE shd100_declaracion_ingresos SET cancelado=1 WHERE ".$this->SQLCA()." and rif_cedula='$rif_constribuyente'  and ano_declaracion=$ano_rebajar and numero_declaracion='".$numero_documento."'; ";
	                                 $sql_shd100_declaracion_ingresos_facturado_2[] = " ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '$rif_constribuyente', $ano_comprobante, '$numero_comprobante', $ano_rebajar, '$numero_documento')";
	                                 $contar_facturado++;
	                    		 }
		                    }else{
		                    	$mes_rebajar                = $vect[$z]['mes'];
		                    	$ano_rebajar                = $vect[$z]['ano'];
		                    	$numero_documento           = $vect[$z]['numero_planilla'];
		                    	$update_documento_detalles .= "UPDATE shd900_planillas_deuda_cobro_detalles SET cancelado=1 WHERE ".$this->SQLCA()." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar and rif_cedula='$rif_constribuyente'  and ano=$ano_rebajar and mes=$mes_rebajar  and cod_numero_catastral_placas='".$vect[$z]['cod_planilla_catastral_pla']."'; ";
		                        $insert_cobranza_plan[]="($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano_comprobante,$numero_comprobante, $ano_rebajar, $mes_rebajar, $numero_documento)";
		                    }//fin else

                      }//fin for
                          if($contar_facturado!=0){
                              $cob_pla=$this->shd900_cobranza_numero->execute($sql_shd100_declaracion_ingresos_facturado."  ".implode(',',$sql_shd100_declaracion_ingresos_facturado_2).";");
                    }else if($contar_facturado_convenio!=0){
                    	      $cob_pla=$this->shd900_cobranza_numero->execute($sql_shd100_dec_ing_fac_conve."  ".implode(',',$sql_shd100_dec_ing_fac_conve_2).";");
                    }else{
                              $cob_pla=$this->shd900_cobranza_numero->execute($insert_cobranza_planilla." ".implode(',',$insert_cobranza_plan).";");
                    }
                    if($cob_pla>1){
                    	$pla=$this->shd900_cobranza_numero->execute($update_documento_detalles);
	                    if($pla>1){
	                       $this->set('exito', 'LOS DATOS FUERÓN GUARDADOS');
				           $this->shd900_cobranza_numero->execute('UPDATE shd900_cobranza_numero set situacion=3 WHERE '.$this->SQLCA()." and ano_comprobante='$ano_comprobante' and numero_comprobante = '$numero_comprobante'");
			               $this->set('guadado',true);
			    		   $this->shd900_cobranza_numero->execute("COMMIT;");
	                    }else{
	                    	$this->set('error', 'LOS DATOS NO FUERON GUARDADOS - actulización de detalles no completado');
	                        $this->shd900_cobranza_numero->execute("ROLLBACK;");
	                    }
                    }else{
	                    	$this->set('error', 'LOS DATOS NO FUERON GUARDADOS - problemas en plantillas');
	                        $this->shd900_cobranza_numero->execute("ROLLBACK;");
	                }
		    	}else{
		    		$this->shd900_cobranza_numero->execute("ROLLBACK;");
		    	}
		    }else{
                $this->shd900_cobranza_numero->execute("ROLLBACK;");
		    }
		}else{
			$this->set('error', 'LOS DATOS NO FUERON GUARDADOS');
		}
		$this->set('ano_comprobante',$ano_comprobante);
	    $this->set('numero_comprobante',$numero_comprobante);
        $this->set('rif',$rif_constribuyente);



}//fin guardar

function consultar($pagina=null, $var1_2=null){//echo 'si llego';
 		$this->layout = "ajax";
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	 //$ano=$this->ano_ejecucion();
          	 $ano = $this->shd000_arranque->ano($this->SQLCA());
          	 $condicion=$this->SQLCA()." and ano_comprobante=".$ano;
          	 $Tfilas=$this->v_ingresos_fijos->findCount($condicion);
          	 if($Tfilas==0){
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_ingresos_fijos->findAll($condicion,null,'numero_comprobante ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 //$this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
               $cond =" rif_ci='".$datos[0]['v_ingresos_fijos']['rif_ci_cobrador']."'";
			   $cobrador = $this->shd002_cobradores->findAll($cond);
			   $this->set('nombre_razon',$cobrador[0]['shd002_cobradores']['nombre_razon']);

			   $rs_cdp    = $this->shd900_cobranza_diaria_planillas->findAll($this->SQLCA()." and ano_comprobante=".$datos[0]['v_ingresos_fijos']['ano_comprobante']." and numero_comprobante=".$datos[0]['v_ingresos_fijos']['numero_comprobante']);
	           $rs_cdp_2  = $this->shd100_declaracion_ingresos_facturado->findAll($this->SQLCA()." and ano_comprobante=".$datos[0]['v_ingresos_fijos']['ano_comprobante']." and numero_comprobante=".$datos[0]['v_ingresos_fijos']['numero_comprobante']);
	           $rs_cdp_3  = $this->shd100_dec_ing_fac_conve->findAll($this->SQLCA()." and ano_comprobante=".$datos[0]['v_ingresos_fijos']['ano_comprobante']." and numero_comprobante=".$datos[0]['v_ingresos_fijos']['numero_comprobante']);


                    if(!empty($rs_cdp_3)){
                       $i=1;
                       $this->set("opcion_consulta", 3);
			           foreach($rs_cdp_3 as $rscdp){
			           	     $ano_declaracion      = $rscdp['shd100_dec_ing_fac_conve']['ano_declaracion'];
			           	     $numero_declaracion   = $rscdp['shd100_dec_ing_fac_conve']['numero_declaracion'];
			           	     $ano_convenimiento    = $rscdp['shd100_dec_ing_fac_conve']['ano_convenimiento'];
			           	     $numero_convenimient  = $rscdp['shd100_dec_ing_fac_conve']['numero_convenimiento'];
				             $data2 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$datos[0]['v_ingresos_fijos']['rif_cedula']."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."' and ano_convenimiento='".$ano_convenimiento."' and numero_convenimiento='".$numero_convenimient."' ",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
				             $this->set('data2',$data2);
				             extract($data2[0]['shd100_declaracion_ingresos_convenimientos']);
						     $vec[$i]['declaracion_ingresos'] = "si";
						     $vec[$i]['convenimiento']        = "si";
						     $vec[$i]['ano_declaracion']      = $ano_declaracion;
							 $vec[$i]['numero_declaracion']   = $numero_declaracion;
							 $vec[$i]['ano_convenimiento']    = $ano_convenimiento;
							 $vec[$i]['numero_convenimiento'] = $numero_convenimient;
							 $vec[$i]['monto_deuda']          = $monto_deuda;
							 $vec[$i]['fecha_acordada_pago']  = $fecha_acordada_pago;
							 $vec[$i]['monto_convenido']      = $monto_convenido;
							 $vec[$i]['deuda_pendiente']      = $deuda_pendiente;
							 $vec[$i]['fecha_cancelacion']    = $fecha_cancelacion;
							 $vec[$i]['cancelado']            = $cancelado;
				             $vec[$i]['id']=$i;
							 $i++;
			           }
               }else if(!empty($rs_cdp_2)){
                       $i=1;
                       $this->set("opcion_consulta", 1);
			           foreach($rs_cdp_2 as $rscdp){
			           	     $ano_declaracion    = $rscdp['shd100_declaracion_ingresos_facturado']['ano_declaracion'];
			           	     $numero_declaracion = $rscdp['shd100_declaracion_ingresos_facturado']['numero_declaracion'];
							 $data = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$datos[0]['v_ingresos_fijos']['rif_cedula']."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."'   ",null," ano_declaracion, numero_declaracion ASC");
					         extract($data[0]['shd100_declaracion_ingresos']);
					         $vec[$i]['declaracion_ingresos'] = "si";
						     $vec[$i]['ano_declaracion']      = $ano_declaracion;
							 $vec[$i]['numero_declaracion']   = $numero_declaracion;
							 $vec[$i]['periodo_desde']        = $periodo_desde;
							 $vec[$i]['periodo_hasta']        = $periodo_hasta;
							 $vec[$i]['fecha_declaracion']    = $fecha_declaracion;
							 $vec[$i]['ingresos_declarados']  = $ingresos_declarados;
							 $vec[$i]['monto_impuesto']       = $monto_impuesto;
							 $vec[$i]['monto_exonerado']       = $monto_exonerado;
							 $vec[$i]['id']=$i;
							 $i++;

			           }
               }else{


				           $i=1;
				           $this->set("opcion_consulta", 2);
				           foreach($rs_cdp as $rscdp){
				           	    $ano_planilla=$rscdp['shd900_cobranza_diaria_planillas']['ano'];
				           	    $mes_planilla=$rscdp['shd900_cobranza_diaria_planillas']['mes'];
				           	    $numero_planilla=$rscdp['shd900_cobranza_diaria_planillas']['numero_planilla'];
				           	    $data_detalles =$this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and cod_partida=".$datos[0]['v_ingresos_fijos']['cod_partida']." and cod_generica=".$datos[0]['v_ingresos_fijos']['cod_generica']." and cod_especifica=".$datos[0]['v_ingresos_fijos']['cod_especifica']." and cod_sub_espec=".$datos[0]['v_ingresos_fijos']['cod_sub_espec']." and cod_auxiliar=".$datos[0]['v_ingresos_fijos']['cod_auxiliar']." and rif_cedula='".$datos[0]['v_ingresos_fijos']['rif_cedula']."' and ano=$ano_planilla and mes=$mes_planilla and numero_planilla=$numero_planilla","deuda_vigente,monto_recargo,monto_multa,monto_intereses,monto_descuento,cod_numero_catastral_placas");
				                $deuda_vigente = 0;
				                $monto_recargo = 0;
				                $monto_multa   = 0;
				                $monto_intereses = 0;
				                $monto_descuento = 0;
				                if(!empty($data_detalles)){
				                	extract($data_detalles[0]['shd900_planillas_deuda_cobro_detalles']);
				                }
				                 //$total=($deuda_vigente+$monto_recargo+$monto_multa+$monto_intereses)-$monto_descuento;
			                     $vec[$i]['ano']=isset($ano_planilla)?$ano_planilla:0;
								 $vec[$i]['mes']=isset($mes_planilla)?$mes_planilla:0;
								 $vec[$i]['numero_planilla']=isset($numero_planilla)?$numero_planilla:0;
								 $vec[$i]['deuda_vigente']=isset($deuda_vigente)?$deuda_vigente:0;
								 $vec[$i]['monto_recargo']=isset($monto_recargo)?$monto_recargo:0;
								 $vec[$i]['monto_multa']=isset($monto_multa)?$monto_multa:0;
								 $vec[$i]['monto_intereses']=isset($monto_intereses)?$monto_intereses:0;
								 $vec[$i]['monto_descuento']=isset($monto_descuento)?$monto_descuento:0;
								 $vec[$i]['total']=($deuda_vigente+$monto_recargo+$monto_multa+$monto_intereses)-$monto_descuento;
								 $vec[$i]['cod_planilla_catastral_pla']=isset($cod_numero_catastral_pla)?$cod_numero_catastral_pla:0;
								 $vec[$i]['id']=$i;
								 $i++;
				           }
               }





	           $v_cod=$this->shd003_codigo_ingresos->findAll("cod_partida=".$datos[0]['v_ingresos_fijos']['cod_partida']." and cod_generica=".$datos[0]['v_ingresos_fijos']['cod_generica']." and cod_especifica=".$datos[0]['v_ingresos_fijos']['cod_especifica']." and cod_subespec=".$datos[0]['v_ingresos_fijos']['cod_sub_espec']." and cod_auxiliar=".$datos[0]['v_ingresos_fijos']['cod_auxiliar']);
			   $this->set('cod_ingreso',$v_cod[0]['shd003_codigo_ingresos']['cod_ingreso']);
	           $this->set('data_grilla',$vec);
	           $this->set('i',$i);


             }
            // echo"hola";
 }else{
 	        $pagina=1;
 			$this->set('pagina',$pagina);
          	 //$ano=$this->ano_ejecucion();
          	 $ano = $this->shd000_arranque->ano($this->SQLCA());
          	 $condicion=$this->SQLCA()." and ano_comprobante=".$ano;
          	 $Tfilas=$this->v_ingresos_fijos->findCount($condicion);
          	 if($Tfilas==0){
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_ingresos_fijos->findAll($condicion,null,'numero_comprobante ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $cond =" rif_ci='".$datos[0]['v_ingresos_fijos']['rif_ci_cobrador']."'";
			   $cobrador = $this->shd002_cobradores->findAll($cond);
			   $this->set('nombre_razon',$cobrador[0]['shd002_cobradores']['nombre_razon']);
			   $rs_cdp    = $this->shd900_cobranza_diaria_planillas->findAll($this->SQLCA()." and ano_comprobante=".$datos[0]['v_ingresos_fijos']['ano_comprobante']." and numero_comprobante=".$datos[0]['v_ingresos_fijos']['numero_comprobante']);
	           $rs_cdp_2  = $this->shd100_declaracion_ingresos_facturado->findAll($this->SQLCA()." and ano_comprobante=".$datos[0]['v_ingresos_fijos']['ano_comprobante']." and numero_comprobante=".$datos[0]['v_ingresos_fijos']['numero_comprobante']);
               $rs_cdp_3  = $this->shd100_dec_ing_fac_conve->findAll($this->SQLCA()." and ano_comprobante=".$datos[0]['v_ingresos_fijos']['ano_comprobante']." and numero_comprobante=".$datos[0]['v_ingresos_fijos']['numero_comprobante']);
               if(!empty($rs_cdp_3)){
                       $i=1;
                       $this->set("opcion_consulta", 3);
			           foreach($rs_cdp_3 as $rscdp){
			           	     $ano_declaracion      = $rscdp['shd100_dec_ing_fac_conve']['ano_declaracion'];
			           	     $numero_declaracion   = $rscdp['shd100_dec_ing_fac_conve']['numero_declaracion'];
			           	     $ano_convenimiento    = $rscdp['shd100_dec_ing_fac_conve']['ano_convenimiento'];
			           	     $numero_convenimient  = $rscdp['shd100_dec_ing_fac_conve']['numero_convenimiento'];
				             $data2 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$datos[0]['v_ingresos_fijos']['rif_cedula']."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."' and ano_convenimiento='".$ano_convenimiento."' and numero_convenimiento='".$numero_convenimient."' ",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
				             $this->set('data2',$data2);
				             extract($data2[0]['shd100_declaracion_ingresos_convenimientos']);
						     $vec[$i]['declaracion_ingresos'] = "si";
						     $vec[$i]['convenimiento']        = "si";
						     $vec[$i]['ano_declaracion']      = $ano_declaracion;
							 $vec[$i]['numero_declaracion']   = $numero_declaracion;
							 $vec[$i]['ano_convenimiento']    = $ano_convenimiento;
							 $vec[$i]['numero_convenimiento'] = $numero_convenimient;
							 $vec[$i]['monto_deuda']          = $monto_deuda;
							 $vec[$i]['fecha_acordada_pago']  = $fecha_acordada_pago;
							 $vec[$i]['monto_convenido']      = $monto_convenido;
							 $vec[$i]['deuda_pendiente']      = $deuda_pendiente;
							 $vec[$i]['fecha_cancelacion']    = $fecha_cancelacion;
							 $vec[$i]['cancelado']            = $cancelado;
				             $vec[$i]['id']=$i;
							 $i++;
			           }
               }else if(!empty($rs_cdp_2)){
                       $i=1;
                       $this->set("opcion_consulta", 1);
			           foreach($rs_cdp_2 as $rscdp){
			           	     $ano_declaracion    = $rscdp['shd100_declaracion_ingresos_facturado']['ano_declaracion'];
			           	     $numero_declaracion = $rscdp['shd100_declaracion_ingresos_facturado']['numero_declaracion'];
							 $data = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$datos[0]['v_ingresos_fijos']['rif_cedula']."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."'   ",null," ano_declaracion, numero_declaracion ASC");
					         extract($data[0]['shd100_declaracion_ingresos']);
					         $vec[$i]['declaracion_ingresos'] = "si";
						     $vec[$i]['ano_declaracion']      = $ano_declaracion;
							 $vec[$i]['numero_declaracion']   = $numero_declaracion;
							 $vec[$i]['periodo_desde']        = $periodo_desde;
							 $vec[$i]['periodo_hasta']        = $periodo_hasta;
							 $vec[$i]['fecha_declaracion']    = $fecha_declaracion;
							 $vec[$i]['ingresos_declarados']  = $ingresos_declarados;
							 $vec[$i]['monto_impuesto']       = $monto_impuesto;
							 $vec[$i]['monto_exonerado']       = $monto_exonerado;
							 $vec[$i]['id']=$i;
							 $i++;

			           }
               }else{
			           $i=1;
			           $this->set("opcion_consulta", 2);
			           foreach($rs_cdp as $rscdp){
				           	    $ano_planilla=$rscdp['shd900_cobranza_diaria_planillas']['ano'];
				           	    $mes_planilla=$rscdp['shd900_cobranza_diaria_planillas']['mes'];
				           	    $numero_planilla=$rscdp['shd900_cobranza_diaria_planillas']['numero_planilla'];
				           	    $data_detalles =$this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and cod_partida=".$datos[0]['v_ingresos_fijos']['cod_partida']." and cod_generica=".$datos[0]['v_ingresos_fijos']['cod_generica']." and cod_especifica=".$datos[0]['v_ingresos_fijos']['cod_especifica']." and cod_sub_espec=".$datos[0]['v_ingresos_fijos']['cod_sub_espec']." and cod_auxiliar=".$datos[0]['v_ingresos_fijos']['cod_auxiliar']." and rif_cedula='".$datos[0]['v_ingresos_fijos']['rif_cedula']."' and ano=$ano_planilla and mes=$mes_planilla and numero_planilla=$numero_planilla","deuda_vigente,monto_recargo,monto_multa,monto_intereses,monto_descuento,cod_numero_catastral_placas");
				                $deuda_vigente = 0;
				                $monto_recargo = 0;
				                $monto_multa   = 0;
				                $monto_intereses = 0;
				                $monto_descuento = 0;
				                if(!empty($data_detalles)){
				                	extract($data_detalles[0]['shd900_planillas_deuda_cobro_detalles']);
				                }
				                 //$total=($deuda_vigente+$monto_recargo+$monto_multa+$monto_intereses)-$monto_descuento;
			                     $vec[$i]['ano']=isset($ano_planilla)?$ano_planilla:0;
								 $vec[$i]['mes']=isset($mes_planilla)?$mes_planilla:0;
								 $vec[$i]['numero_planilla']=isset($numero_planilla)?$numero_planilla:0;
								 $vec[$i]['deuda_vigente']=isset($deuda_vigente)?$deuda_vigente:0;
								 $vec[$i]['monto_recargo']=isset($monto_recargo)?$monto_recargo:0;
								 $vec[$i]['monto_multa']=isset($monto_multa)?$monto_multa:0;
								 $vec[$i]['monto_intereses']=isset($monto_intereses)?$monto_intereses:0;
								 $vec[$i]['monto_descuento']=isset($monto_descuento)?$monto_descuento:0;
								 $vec[$i]['total']=($deuda_vigente+$monto_recargo+$monto_multa+$monto_intereses)-$monto_descuento;
								 $vec[$i]['cod_planilla_catastral_pla']=isset($cod_numero_catastral_pla)?$cod_numero_catastral_pla:0;
								 $vec[$i]['id']=$i;
								 $i++;
				           }
               }//fin else


	           $v_cod=$this->shd003_codigo_ingresos->findAll("cod_partida=".$datos[0]['v_ingresos_fijos']['cod_partida']." and cod_generica=".$datos[0]['v_ingresos_fijos']['cod_generica']." and cod_especifica=".$datos[0]['v_ingresos_fijos']['cod_especifica']." and cod_subespec=".$datos[0]['v_ingresos_fijos']['cod_sub_espec']." and cod_auxiliar=".$datos[0]['v_ingresos_fijos']['cod_auxiliar']);
			   $this->set('cod_ingreso',$v_cod[0]['shd003_codigo_ingresos']['cod_ingreso']);
	           $this->set('data_grilla',$vec);
	           $this->set('i',$i);
			 }
}//
}//

function consultar2($ano_comprobante=null,$numero_comprobante=null){//echo 'si llego';
 		$this->layout = "ajax";

 	        $pagina=1;
 			$this->set('pagina',$pagina);
          	 $condicion=$this->SQLCA()." and ano_comprobante=".$ano_comprobante." and numero_comprobante=".$numero_comprobante;
          	 $Tfilas=$this->v_ingresos_fijos->findCount($condicion);
          	 if($Tfilas==0){
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_ingresos_fijos->findAll($condicion,null,'numero_comprobante ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
               $cond =" rif_ci='".$datos[0]['v_ingresos_fijos']['rif_ci_cobrador']."'";
			   $cobrador = $this->shd002_cobradores->findAll($cond);
			   $this->set('nombre_razon',$cobrador[0]['shd002_cobradores']['nombre_razon']);
			   $rs_cdp    = $this->shd900_cobranza_diaria_planillas->findAll($this->SQLCA()." and ano_comprobante=".$datos[0]['v_ingresos_fijos']['ano_comprobante']." and numero_comprobante=".$datos[0]['v_ingresos_fijos']['numero_comprobante']);
			   $rs_cdp_2  = $this->shd100_declaracion_ingresos_facturado->findAll($this->SQLCA()." and ano_comprobante=".$datos[0]['v_ingresos_fijos']['ano_comprobante']." and numero_comprobante=".$datos[0]['v_ingresos_fijos']['numero_comprobante']);
               $rs_cdp_3  = $this->shd100_dec_ing_fac_conve->findAll($this->SQLCA()." and ano_comprobante=".$datos[0]['v_ingresos_fijos']['ano_comprobante']." and numero_comprobante=".$datos[0]['v_ingresos_fijos']['numero_comprobante']);

               if(!empty($rs_cdp_3)){
                       $i=1;
                       $this->set("opcion_consulta", 3);
			           foreach($rs_cdp_3 as $rscdp){
			           	     $ano_declaracion      = $rscdp['shd100_dec_ing_fac_conve']['ano_declaracion'];
			           	     $numero_declaracion   = $rscdp['shd100_dec_ing_fac_conve']['numero_declaracion'];
			           	     $ano_convenimiento    = $rscdp['shd100_dec_ing_fac_conve']['ano_convenimiento'];
			           	     $numero_convenimient  = $rscdp['shd100_dec_ing_fac_conve']['numero_convenimiento'];
				             $data2 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$datos[0]['v_ingresos_fijos']['rif_cedula']."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."' and ano_convenimiento='".$ano_convenimiento."' and numero_convenimiento='".$numero_convenimient."' ",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
				             $this->set('data2',$data2);
				             extract($data2[0]['shd100_declaracion_ingresos_convenimientos']);
						     $vec[$i]['declaracion_ingresos'] = "si";
						     $vec[$i]['convenimiento']        = "si";
						     $vec[$i]['ano_declaracion']      = $ano_declaracion;
							 $vec[$i]['numero_declaracion']   = $numero_declaracion;
							 $vec[$i]['ano_convenimiento']    = $ano_convenimiento;
							 $vec[$i]['numero_convenimiento'] = $numero_convenimient;
							 $vec[$i]['monto_deuda']          = $monto_deuda;
							 $vec[$i]['fecha_acordada_pago']  = $fecha_acordada_pago;
							 $vec[$i]['monto_convenido']      = $monto_convenido;
							 $vec[$i]['deuda_pendiente']      = $deuda_pendiente;
							 $vec[$i]['fecha_cancelacion']    = $fecha_cancelacion;
							 $vec[$i]['cancelado']            = $cancelado;
				             $vec[$i]['id']=$i;
							 $i++;
			           }
               }else if(!empty($rs_cdp_2)){
                       $i=1;
                       $this->set("opcion_consulta", 1);
			           foreach($rs_cdp_2 as $rscdp){
			           	     $ano_declaracion    = $rscdp['shd100_declaracion_ingresos_facturado']['ano_declaracion'];
			           	     $numero_declaracion = $rscdp['shd100_declaracion_ingresos_facturado']['numero_declaracion'];
							 $data = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$datos[0]['v_ingresos_fijos']['rif_cedula']."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."'   ",null," ano_declaracion, numero_declaracion ASC");
					         extract($data[0]['shd100_declaracion_ingresos']);
					         $vec[$i]['declaracion_ingresos'] = "si";
						     $vec[$i]['ano_declaracion']      = $ano_declaracion;
							 $vec[$i]['numero_declaracion']   = $numero_declaracion;
							 $vec[$i]['periodo_desde']        = $periodo_desde;
							 $vec[$i]['periodo_hasta']        = $periodo_hasta;
							 $vec[$i]['fecha_declaracion']    = $fecha_declaracion;
							 $vec[$i]['ingresos_declarados']  = $ingresos_declarados;
							 $vec[$i]['monto_impuesto']       = $monto_impuesto;
							 $vec[$i]['monto_exonerado']      = $monto_exonerado;
							 $vec[$i]['id']=$i;
							 $i++;

			           }
               }else{

			           $i=1;
			           $this->set("opcion_consulta", 2);
			           foreach($rs_cdp as $rscdp){
				           	    $ano_planilla=$rscdp['shd900_cobranza_diaria_planillas']['ano'];
				           	    $mes_planilla=$rscdp['shd900_cobranza_diaria_planillas']['mes'];
				           	    $numero_planilla=$rscdp['shd900_cobranza_diaria_planillas']['numero_planilla'];
				           	    $data_detalles =$this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and cod_partida=".$datos[0]['v_ingresos_fijos']['cod_partida']." and cod_generica=".$datos[0]['v_ingresos_fijos']['cod_generica']." and cod_especifica=".$datos[0]['v_ingresos_fijos']['cod_especifica']." and cod_sub_espec=".$datos[0]['v_ingresos_fijos']['cod_sub_espec']." and cod_auxiliar=".$datos[0]['v_ingresos_fijos']['cod_auxiliar']." and rif_cedula='".$datos[0]['v_ingresos_fijos']['rif_cedula']."' and ano=$ano_planilla and mes=$mes_planilla and numero_planilla=$numero_planilla","deuda_vigente,monto_recargo,monto_multa,monto_intereses,monto_descuento,cod_numero_catastral_placas");
				                $deuda_vigente = 0;
				                $monto_recargo = 0;
				                $monto_multa   = 0;
				                $monto_intereses = 0;
				                $monto_descuento = 0;
				                if(!empty($data_detalles)){
				                	extract($data_detalles[0]['shd900_planillas_deuda_cobro_detalles']);
				                }
				                 //$total=($deuda_vigente+$monto_recargo+$monto_multa+$monto_intereses)-$monto_descuento;
			                     $vec[$i]['ano']=isset($ano_planilla)?$ano_planilla:0;
								 $vec[$i]['mes']=isset($mes_planilla)?$mes_planilla:0;
								 $vec[$i]['numero_planilla']=isset($numero_planilla)?$numero_planilla:0;
								 $vec[$i]['deuda_vigente']=isset($deuda_vigente)?$deuda_vigente:0;
								 $vec[$i]['monto_recargo']=isset($monto_recargo)?$monto_recargo:0;
								 $vec[$i]['monto_multa']=isset($monto_multa)?$monto_multa:0;
								 $vec[$i]['monto_intereses']=isset($monto_intereses)?$monto_intereses:0;
								 $vec[$i]['monto_descuento']=isset($monto_descuento)?$monto_descuento:0;
								 $vec[$i]['total']=($deuda_vigente+$monto_recargo+$monto_multa+$monto_intereses)-$monto_descuento;
								 $vec[$i]['cod_planilla_catastral_pla']=isset($cod_numero_catastral_pla)?$cod_numero_catastral_pla:0;
								 $vec[$i]['id']=$i;
								 $i++;
				           }

               }//fin eslse








	           $v_cod=$this->shd003_codigo_ingresos->findAll("cod_partida=".$datos[0]['v_ingresos_fijos']['cod_partida']." and cod_generica=".$datos[0]['v_ingresos_fijos']['cod_generica']." and cod_especifica=".$datos[0]['v_ingresos_fijos']['cod_especifica']." and cod_subespec=".$datos[0]['v_ingresos_fijos']['cod_sub_espec']." and cod_auxiliar=".$datos[0]['v_ingresos_fijos']['cod_auxiliar']);
			   $this->set('cod_ingreso',$v_cod[0]['shd003_codigo_ingresos']['cod_ingreso']);
	           $this->set('data_grilla',$vec);
	           $this->set('i',$i);
			 }
}//fin consultar2

function eliminar($ano_comprobante=null,$numero_comprobante=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();
 	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
 	$cond="ano_comprobante='".$ano_comprobante."' and numero_comprobante=".$numero_comprobante." and $ca";
 	$data = $this->v_shd900_cobranza_diaria->findAll($cond);
    extract($data[0]['v_shd900_cobranza_diaria']);
    $fecha_comprobante = explode('-',$fecha_comprobante);
	$ano=$fecha_comprobante[0];
	$mes=$fecha_comprobante[1];
	$mes2=(int) $fecha_comprobante[1];
	$dia=$fecha_comprobante[2];
	$a[1]  = empty($cod_entidad_deposito)?'0':$cod_entidad_deposito;
	$a[2]  = empty($cod_sucursal_deposito)?'0':$cod_sucursal_deposito;
	$a[3]  = empty($cuenta_bancaria_deposito)?'0':$cuenta_bancaria_deposito;//cuenta_bancaria_deposito;
	$a[4]  = empty($numero_deposito)?'0':$numero_deposito;
	$a[5]  = empty($cod_entidad_credito)?'0':$cod_entidad_credito;
	$a[6]  = empty($cod_sucursal_credito)?'0':$cod_sucursal_credito;
	$a[7]  = empty($cuenta_bancaria_credito)?'0':$cuenta_bancaria_credito;
	$a[8]  = empty($numero_nota_credito)?'0':$numero_nota_credito;
	$a[9]  = empty($cod_entidad_cheque)?'0':$cod_entidad_cheque;
	$a[10] = empty($cod_sucursal_cheque)?'0':$cod_sucursal_cheque;
	$a[11] = empty($cuenta_bancaria_cheque)?'0':$cuenta_bancaria_cheque;
	$a[12] = empty($numero_cheque)?'0':$numero_cheque;
	$cd  = ($a[1]=='0' && $a[2]=='0' && $a[3]=='0' && $a[4]=='0')?0:1;
	$cnc = ($a[5]=='0' && $a[6]=='0' && $a[7]=='0' && $a[8]=='0')?0:1;
	$cch = ($a[9]=='0' && $a[10]=='0' && $a[11]=='0' && $a[12]=='0')?0:1;
	$cpe = $monto_efectivo!=0?1:0;
	$cmd = $monto_descuento!=0?1:0;

	$p1 = $deuda_vigente+$deuda_anterior+$monto_recargo+$monto_multa+$monto_intereses;
	$p2 = $monto_descuento;
	$p2 = $p2>=$p1?0:$p2;
	$monto_total = $p1-$p2;

    $condicion_acumulado=$this->SQLCA()." and ano=".$ano." and mes=".$mes." and dia=".$dia." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
    $sql_update_acumulada = "UPDATE shd900_cobranza_acumulada SET
       deuda_vigente=deuda_vigente-$deuda_vigente,
       deuda_anterior=deuda_anterior-$deuda_anterior,
       monto_recargo=monto_recargo-$monto_recargo,
       monto_multa=monto_multa-$monto_multa,
       monto_intereses=monto_intereses-$monto_intereses,
       monto_descuento=monto_descuento-$monto_descuento,
       cantidad_depositos=cantidad_depositos-$cd,
       monto_depositos=monto_depositos-$monto_deposito,
       cantidad_notas_credito=cantidad_notas_credito-$cnc,
       monto_notas_credito=monto_notas_credito-$monto_nota_credito,
       cantidad_cheques=cantidad_cheques-$cch,
       monto_cheques=monto_cheques-$monto_cheque,
       cantidad_pagos_efectivo=cantidad_pagos_efectivo-$cpe,
       cantidad_descuento=cantidad_descuento-$cmd,
       monto_pagos_efectivo=monto_pagos_efectivo-$monto_efectivo  WHERE ".$condicion_acumulado;
        $username_anulacion = $_SESSION['nom_usuario'];
        $this->shd900_cobranza_numero->execute("BEGIN;");
        $res=$this->v_shd900_cobranza_diaria->execute("UPDATE shd900_cobranza_diaria SET condicion_documento=2, fecha_anulacion='".date('Y-m-d')."',ano_anulacion=".date('Y').", username_anulacion='$username_anulacion'  WHERE ".$cond);
	    //echo $sql_update_acumulada;
	    if($res>1){
           $res=$this->v_shd900_cobranza_diaria->execute($sql_update_acumulada);
           $condicion_cfpd03=$this->SQLCA()." and ano=".$ano." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
           $sw2 = $this->shd900_cobranza_numero->execute("UPDATE cfpd03 SET monto_cobrado=monto_cobrado-$monto_total WHERE ".$condicion_cfpd03);
           $rs_cdp=$this->shd900_cobranza_diaria_planillas->findAll($this->SQLCA()." and ano_comprobante=$ano_comprobante and numero_comprobante=$numero_comprobante");
           $update_planilla_detalles ="";

           $rs_cdp_2  = $this->shd100_declaracion_ingresos_facturado->findAll($this->SQLCA()." and ano_comprobante=".$ano_comprobante." and numero_comprobante=".$numero_comprobante);
           $rs_cdp_3  = $this->shd100_dec_ing_fac_conve->findAll($this->SQLCA()." and ano_comprobante=".$ano_comprobante." and numero_comprobante=".$numero_comprobante);

               if(!empty($rs_cdp_3)){
                       $i=1;
                       $this->set("opcion_consulta", 3);
			           foreach($rs_cdp_3 as $rscdp){
			           	     $ano_declaracion      = $rscdp['shd100_dec_ing_fac_conve']['ano_declaracion'];
			           	     $numero_declaracion   = $rscdp['shd100_dec_ing_fac_conve']['numero_declaracion'];
			           	     $ano_convenimiento    = $rscdp['shd100_dec_ing_fac_conve']['ano_convenimiento'];
			           	     $numero_convenimient  = $rscdp['shd100_dec_ing_fac_conve']['numero_convenimiento'];
				             $data2 = $this->shd100_declaracion_ingresos_convenimientos->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."' and ano_convenimiento='".$ano_convenimiento."' and numero_convenimiento='".$numero_convenimient."' ",null," ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento ASC");
				             extract($data2[0]['shd100_declaracion_ingresos_convenimientos']);
				             $update_planilla_detalles .="UPDATE shd100_declaracion_ingresos SET                 cancelado=2, acumulado_pagos_parciales=acumulado_pagos_parciales-".$monto_convenido."    WHERE ".$this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion and numero_declaracion='$numero_declaracion' ; ";
				             $update_planilla_detalles .="UPDATE shd100_declaracion_ingresos_convenimientos SET  cancelado=2                                                                              WHERE ".$this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion and numero_declaracion='$numero_declaracion' and ano_convenimiento='".$ano_convenimiento."' and numero_convenimiento='".$numero_convenimient."'; ";

			           }
               }else if(!empty($rs_cdp_2)){
                       foreach($rs_cdp_2 as $rscdp){
			           	     $ano_declaracion    = $rscdp['shd100_declaracion_ingresos_facturado']['ano_declaracion'];
			           	     $numero_declaracion = $rscdp['shd100_declaracion_ingresos_facturado']['numero_declaracion'];
							 $data = $this->shd100_declaracion_ingresos->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and ano_declaracion='".$ano_declaracion."' and numero_declaracion='".$numero_declaracion."'   ",null," ano_declaracion, numero_declaracion ASC");
					         extract($data[0]['shd100_declaracion_ingresos']);
					         $update_planilla_detalles .="UPDATE shd100_declaracion_ingresos SET  cancelado=2 WHERE ".$this->SQLCA()." and rif_cedula='$rif_cedula' and ano_declaracion=$ano_declaracion and numero_declaracion='$numero_declaracion' ; ";
			           }
               }else{

			           foreach($rs_cdp as $rscdp){
			           	    $ano_planilla=$rscdp['shd900_cobranza_diaria_planillas']['ano'];
			           	    $mes_planilla=$rscdp['shd900_cobranza_diaria_planillas']['mes'];
			           	    $numero_planilla=$rscdp['shd900_cobranza_diaria_planillas']['numero_planilla'];
			           	    $data_detalles =$this->shd900_planillas_deuda_cobro_detalles->findAll($this->SQLCA()." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar and rif_cedula='$rif_cedula' and ano=$ano_planilla and mes=$mes_planilla and numero_planilla=$numero_planilla","deuda_vigente,monto_recargo,monto_multa,monto_intereses,monto_descuento");
			                extract($data_detalles[0]['shd900_planillas_deuda_cobro_detalles']);
			                $update_planilla_detalles .="UPDATE shd900_planillas_deuda_cobro_detalles SET cancelado=2 WHERE ".$this->SQLCA()." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar and rif_cedula='$rif_cedula' and ano=$ano_planilla and mes=$mes_planilla and numero_planilla=$numero_planilla; ";
			           }
               }


           $xx=$this->shd900_cobranza_numero->execute($update_planilla_detalles);
           if($xx>1){
               $this->shd900_cobranza_numero->execute("COMMIT;");
		       $this->set('exito', 'Registro Eliminado con exito');
		       $this->consultar($pagina);//si es el primero solamente
	           $this->render("consultar");
           }else{
                $this->shd900_cobranza_numero->execute("ROLLBACK;");
           		$this->set('error', 'Registro No Eliminado');
           		$this->consultar($pagina);//si es el primero solamente
           		$this->render("consultar");
           }

		}else{
           $this->shd900_cobranza_numero->execute("ROLLBACK;");
           $this->set('error', 'Registro No Eliminado');
           $this->consultar($pagina);//si es el primero solamente
           $this->render("consultar");
		}
}

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_recibo($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista_recibo');
}//fin function


function buscar_pista_recibo($var2=null, $var3=null){
	$this->layout="ajax";

    if($var3==null){
    	$var2 = strtoupper($var2);
		$this->Session->write('pista_recibo', $var2);
		$Tfilas=$this->v_ingresos_fijos->findCount("denominacion_busqueda LIKE '%$var2%'");
		        if($Tfilas!=0){
		        	$pagina=1;
		        	$Tfilas=(int)ceil($Tfilas/50);
		        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->v_ingresos_fijos->findAll("denominacion_busqueda LIKE '%$var2%'",null,"ano_comprobante, numero_comprobante ASC",50,1,null);
			        $this->set("datosFILAS",$datos_filas);
			        $this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
		          }else{
		        	$this->set("datosFILAS",'');
		          }
   }else{
		$var22 = $this->Session->read('pista_recibo');
		$Tfilas=$this->v_ingresos_fijos->findCount("denominacion_busqueda LIKE '%$var22%'");
		        if($Tfilas!=0){
		        	$pagina=$var3;
		        	$Tfilas=(int)ceil($Tfilas/50);
		        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->v_ingresos_fijos->findAll("denominacion_busqueda LIKE '%$var22%'",null,"ano_comprobante, numero_comprobante ASC",50,$pagina,null);
			        $this->set("datosFILAS",$datos_filas);
			        $this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
		          }else{
		        	$this->set("datosFILAS",'');
		          }
     }//fin else
}//fin function buscar_pista_recibo

function buscar_pista_ingreso ($pagina=null,$pista){
	$this->layout="ajax";
	if($pagina!=null){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}
	//print_r($this->data);
	if(isset($this->data["consulta2"]["ano"]) && isset($this->data["consulta2"]["pista"]) && !empty($this->data["consulta2"]["ano"]) && !empty($this->data["consulta2"]["pista"])){
         $this->data["consulta"]["ano"]=$this->data["consulta2"]["ano"];
         $this->data["consulta"]["pista"]=$this->data["consulta2"]["pista"];
         $otra="si";
	}else{
		 //$ano = $this->ano_ejecucion();
		 $ano = $this->shd000_arranque->ano($this->SQLCA());
	     $this->data["consulta"]["ano"]=$ano;
         $this->data["consulta"]["pista"]=$pista;
         $otra="si";
	}
	if((isset($this->data["consulta"]["ano"]) && isset($this->data["consulta"]["pista"]) && !empty($this->data["consulta"]["ano"]) && !empty($this->data["consulta"]["pista"])) || $otra=="si"){
         $ano=$this->data["consulta"]["ano"];
         $pista=strtoupper($this->data["consulta"]["pista"]);
        // echo $pista;
         $cantidad_resultado=$this->v_consulta_ingreso->findCount(" ".$this->busca_separado(array("denominacion_busqueda"), $pista)." ");
         $resultado=$this->v_consulta_ingreso->findAll("            ".$this->busca_separado(array("denominacion_busqueda"), $pista)." ",null,null,1,$pagina);
         if($cantidad_resultado!=0){
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",$resultado);
           $this->set("ano",$ano);
           $this->set("pista",$pista);

           $this->set('siguiente',$pagina+1);
           $this->set('anterior',$pagina-1);
		   $this->set('actual',$pagina);
		   $this->bt_nav($cantidad_resultado,$pagina);
         }else{
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",array(0=>array("v_consulta_ingreso"=>array("cod_grupo"=>0,"cod_partida"=>0,"cod_generica"=>0,"cod_especifica"=>0,"cod_sub_espec"=>0,"cod_auxiliar"=>0,"concepto"=>"","denominacion"=>"No se encontraron datos para la pista indicada, ".$pista))));
           $this->set("ano",$ano);
           $this->set("pista",$pista);
           $this->set('siguiente',$pagina+1);
           $this->set('anterior',$pagina-1);
		   $this->set('actual',$pagina);
           $this->bt_nav(1,1);
         }
         $this->set("MUESTRAME","");


	}else{
		if(isset($this->data["consulta"]["ano"]) && !empty($this->data["consulta"]["ano"])){
			echo "<h4>Faltan Datos para las busqueda, por favor indique pista.</h4>";
		}else if(isset($this->data["consulta"]["pista"]) && !empty($this->data["consulta"]["pista"])){
			echo "<h4>Faltan Datos para las busqueda, por favor indique año.</h4>";
		}else{
			echo "<h4>Faltan Datos para las busqueda, por favor indique año y pista.</h4>";
		}
		//echo "<h4>Faltan Datos para las busqueda, por favor indique pista.</h4>";
	}

}//fin buscar_pista_ingreso

function planilla () {
   $this->layout="pdf";
   extract($this->data['planilla']);
   $cond = $this->SQLCA()." and ano_comprobante=$ano_comprobante and numero_comprobante=$numero_comprobante";
   $this->set('datos1',$this->v_ingresos_fijos->findAll($cond));
   //print_r($this->v_ingresos_fijos->findAll($cond));

}//fin funcion planilla



function salir($numero_comprobante=null){
	$this->layout="ajax";
	$_SESSION["items_deuda"]=array();
	$_SESSION["contador"]=0;
	$ano = $this->shd000_arranque->ano($this->SQLCA());
    $this->shd900_cobranza_numero->execute('UPDATE shd900_cobranza_numero set situacion=1 WHERE '.$this->SQLCA()." and ano_comprobante='$ano' and numero_comprobante = '$numero_comprobante'");
    $this->index();
    $this->render('index');
}

function vacio ($msj,$tipo) {
   $this->layout="ajax";
   $this->set($tipo,$msj);

}//fin funcion vacio




 }
?>