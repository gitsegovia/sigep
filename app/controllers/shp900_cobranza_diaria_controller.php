<?php

 class Shp900CobranzaDiariaController extends AppController{
 	var $name = "shp900_cobranza_diaria";
	var $uses = array('v_shd001_registro_contribuyentes','shd001_registro_contribuyentes','shd900_cobranza_diaria','shd900_cobranza_numero','arrd05',
                       'shd100_actividades', 'cnmd06_profesiones','cugd01_republica', 'cugd01_estados','ccfd04_cierre_mes','shd900_cobranza_acumulada',
                      'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados', 'cugd01_vialidad', 'cugd01_vereda','v_shd900_cobranza_diaria',
                      'cstd02_cuentas_bancarias','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','v_consulta_ingreso','v_cobranza_diaria','shd002_cobradores','shd000_arranque','cfpd03');
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
	$this->set('lista_entidad_bancaria',$this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));
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
    $this->set('lista_cobradores',$this->shd002_cobradores->generateList(null, 'nombre_razon ASC', null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));

}//fin index2

function cargar_cobrador ($rif_ci=null) {
   $this->layout="ajax";
   $cond =" rif_ci='".$rif_ci."'";
   $cobrador = $this->shd002_cobradores->findAll($cond);
   $this->set('nombre_razon',$cobrador[0]['shd002_cobradores']['nombre_razon']);
   $this->set('rif_ci',$rif_ci);
}//fin funcion cargar_sucursal

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

	extract($this->data['shp900_cobranza_diaria']);
	$codigo_ingreso = explode('.',$cod_ingreso);
	$values = array();
	$values[] = $cod_presi;
	$values[] = $cod_entidad;
	$values[] = $cod_tipo_inst;
	$values[] = $cod_inst;
	$values[] = $cod_dep;
	$values[] = $ano_comprobante;
	$values[] = $numero_comprobante;
	$values[] = $cod_partida   = $codigo_ingreso[0];
	$values[] = $cod_generica  = $codigo_ingreso[1];//cod_generica;
	$values[] = $cod_especifica= $codigo_ingreso[2];//cod_especifica;
	$values[] = $cod_sub_espec = $codigo_ingreso[3];//cod_sub_espec;
	$values[] = $cod_auxiliar  = $codigo_ingreso[4];//cod_auxiliar;
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
	$ano_comprobante = divide_fecha($fecha_comprobante,'ANO');
	$mes_comprobante = divide_fecha($fecha_comprobante,'MES');
	$dia_comprobante = divide_fecha($fecha_comprobante,'DIA');
	$values2[] = $ano_comprobante;
	$values2[] = $mes_comprobante;
	$values2[] = $dia_comprobante;
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
	$values3[] = $monto_total;
	$values3[] = $monto_total;


	$condicion_acumulado=$this->SQLCA()." and ano=".$ano_comprobante." and mes=".$mes_comprobante." and dia=".$dia_comprobante." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
	$condicion_cfpd03=$this->SQLCA()." and ano=".$ano_comprobante." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
	$sql_insert        = "INSERT INTO shd900_cobranza_diaria VALUES (".implode(',',$values).");";
	$sql_insert_cfpd03 = "INSERT INTO cfpd03 VALUES (".implode(',',$values3).");";
    $sql_insert_acumulada = "INSERT INTO shd900_cobranza_acumulada VALUES (".implode(',',$values2).");";
    $sql_update_acumulada = "UPDATE shd900_cobranza_acumulada SET
       deuda_vigente=deuda_vigente+$deuda_vigente,
       deuda_anterior=deuda_anterior+$deuda_anterior,
       monto_recargo=monto_recargo+$monto_recargo,
       monto_multa=monto_multa+$monto_multa,
       monto_intereses=monto_intereses+$monto_intereses,
       monto_descuento=monto_descuento+$monto_descuento,
       cantidad_depositos=cantidad_depositos+$cd,
       monto_depositos=monto_depositos+$monto_deposito,
       cantidad_notas_credito=cantidad_notas_credito+$cnc,
       monto_notas_credito=monto_notas_credito+$monto_nota_credito,
       cantidad_cheques=cantidad_cheques+$cch,
       monto_cheques=monto_cheques+$monto_cheque,
       cantidad_pagos_efectivo=cantidad_pagos_efectivo+$cpe,
       cantidad_descuento=cantidad_descuento+$cmd,
       monto_pagos_efectivo=monto_pagos_efectivo+$monto_efectivo  WHERE ".$condicion_acumulado;
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
		    		$sw2 = $this->shd900_cobranza_numero->execute("UPDATE cfpd03 SET monto_facturado=monto_facturado+$monto_total , monto_cobrado=monto_cobrado+$monto_total WHERE ".$condicion_cfpd03);
		    	}
		    	if($sw2>1){
		    		$this->set('exito', 'LOS DATOS FUERON GUARDADOS');
			        $this->shd900_cobranza_numero->execute('UPDATE shd900_cobranza_numero set situacion=3 WHERE '.$this->SQLCA()." and ano_comprobante='$ano_comprobante' and numero_comprobante = '$numero_comprobante'");
		            $this->set('guadado',true);
		    		$this->shd900_cobranza_numero->execute("COMMIT;");
		    	}else{
		    		$this->shd900_cobranza_numero->execute("ROLLBACK;");
		    	}
		    }else{
                $this->shd900_cobranza_numero->execute("ROLLBACK;");
		    }
		}else{
			$this->set('error', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->set('ano_comprobante',$ano_comprobante);
	    $this->set('numero_comprobante',$numero_comprobante);
        $this->set('rif',$rif_constribuyente);



}//fin guardar

function consultar($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	 //$ano=$this->ano_ejecucion();
          	 $ano = $this->shd000_arranque->ano($this->SQLCA());
          	 $condicion=$this->SQLCA()." and ano_comprobante=".$ano;
          	 $Tfilas=$this->v_cobranza_diaria->findCount($condicion);
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARÓN DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cobranza_diaria->findAll($condicion,null,'numero_comprobante ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 //$this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
               $cond =" rif_ci='".$datos[0]['v_cobranza_diaria']['rif_ci_cobrador']."'";
			   $cobrador = $this->shd002_cobradores->findAll($cond);
			   $this->set('nombre_razon',$cobrador[0]['shd002_cobradores']['nombre_razon']);
             }
            // echo"hola";
 }else{
 	$pagina=1;
 			$this->set('pagina',$pagina);
          	 //$ano=$this->ano_ejecucion();
          	 $ano = $this->shd000_arranque->ano($this->SQLCA());
          	 $condicion=$this->SQLCA()." and ano_comprobante=".$ano;
          	 $Tfilas=$this->v_cobranza_diaria->findCount($condicion);
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARÓN DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cobranza_diaria->findAll($condicion,null,'numero_comprobante ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $cond =" rif_ci='".$datos[0]['v_cobranza_diaria']['rif_ci_cobrador']."'";
			   $cobrador = $this->shd002_cobradores->findAll($cond);
			   $this->set('nombre_razon',$cobrador[0]['shd002_cobradores']['nombre_razon']);
			 }
}//fin function consultar2
}//fin class


function consultar2($ano_comprobante=null,$numero_comprobante=null){//echo 'si llego';
 		$this->layout = "ajax";

 	        $pagina=1;
 			$this->set('pagina',$pagina);
          	 $condicion=$this->SQLCA()." and ano_comprobante=".$ano_comprobante." and numero_comprobante=".$numero_comprobante;
          	 $Tfilas=$this->v_cobranza_diaria->findCount($condicion);
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARÓN DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cobranza_diaria->findAll($condicion,null,'numero_comprobante ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
               $cond =" rif_ci='".$datos[0]['v_cobranza_diaria']['rif_ci_cobrador']."'";
			   $cobrador = $this->shd002_cobradores->findAll($cond);
			   $this->set('nombre_razon',$cobrador[0]['shd002_cobradores']['nombre_razon']);
			 }
}//fin consultar2

function eliminar($ano_comprobante=null,$numero_comprobante=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();
 	$cond="ano_comprobante='".$ano_comprobante."' and numero_comprobante=".$numero_comprobante." and $ca";
 	$data = $this->v_shd900_cobranza_diaria->findAll($cond);
    extract($data[0]['v_shd900_cobranza_diaria']);
    $fecha_comprobante = explode('-',$fecha_comprobante);
	$ano=$fecha_comprobante[0];
	$mes=$fecha_comprobante[1];
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
           $sw2 = $this->shd900_cobranza_numero->execute("UPDATE cfpd03 SET monto_facturado=monto_facturado-$monto_total , monto_cobrado=monto_cobrado-$monto_total WHERE ".$condicion_cfpd03);
           //echo "UPDATE cfpd03 SET monto_facturado=monto_facturado-$monto_total , monto_cobrado=monto_cobrado-$monto_total WHERE ".$condicion_cfpd03;
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
		$Tfilas=$this->v_cobranza_diaria->findCount("denominacion_busqueda LIKE '%$var2%'");
		        if($Tfilas!=0){
		        	$pagina=1;
		        	$Tfilas=(int)ceil($Tfilas/50);
		        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->v_cobranza_diaria->findAll("denominacion_busqueda LIKE '%$var2%'",null,"ano_comprobante, numero_comprobante ASC",50,1,null);
			        $this->set("datosFILAS",$datos_filas);
			        $this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
		          }else{
		        	$this->set("datosFILAS",'');
		          }
   }else{
		$var22 = $this->Session->read('pista_recibo');
		$Tfilas=$this->v_cobranza_diaria->findCount("denominacion_busqueda LIKE '%$var22%'");
		        if($Tfilas!=0){
		        	$pagina=$var3;
		        	$Tfilas=(int)ceil($Tfilas/50);
		        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->v_cobranza_diaria->findAll("denominacion_busqueda LIKE '%$var22%'",null,"ano_comprobante, numero_comprobante ASC",50,$pagina,null);
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
           $this->set("resultado",array(0=>array("v_consulta_ingreso"=>array("cod_grupo"=>0,"cod_partida"=>0,"cod_generica"=>0,"cod_especifica"=>0,"cod_sub_espec"=>0,"cod_auxiliar"=>0,"concepto"=>"","denominacion"=>"No se encontrarón datos para la pista indicada, ".$pista))));
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
   $this->set('datos1',$this->v_cobranza_diaria->findAll($cond));

}//fin funcion planilla



function salir($numero_comprobante=null){
	$this->layout="ajax";
	//$ano=$this->ano_ejecucion();
	$ano = $this->shd000_arranque->ano($this->SQLCA());
    $this->shd900_cobranza_numero->execute('UPDATE shd900_cobranza_numero set situacion=1 WHERE '.$this->SQLCA()." and ano_comprobante='$ano' and numero_comprobante = '$numero_comprobante'");
    $this->index();
    $this->render('index');
}


function vacio ($msj='',$tipo='error') {
   $this->layout="ajax";
   $this->set($tipo,$msj);

}//fin funcion vacio





function reactualizar_fechas () {
   $this->layout="ajax";
/**/
    $cod_presi 					= 1;
	$cod_entidad 				= 3;
	$cod_tipo_inst 				= 50;
	$cod_inst 					= 13;
	$cod_dep 					= 1;
/**/
    $DATA = $this->shd900_cobranza_diaria->findAll("condicion_documento=1");
    $sql_mostrar ="";
    $this->shd900_cobranza_diaria->execute("DELETE FROM shd900_cobranza_acumulada WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep");
    foreach($DATA as $data_diaria){
       extract($data_diaria['shd900_cobranza_diaria']);



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
	$values[] = "'".$fecha_comprobante."'";//fecha_comprobante;
	$values[] = "'".$rif_cedula."'";//rif_cedula;
	$values[] = "'".$concepto_comprobante."'";
	$values[] = $deuda_anterior;
    $values[] = $deuda_vigente;
	$values[] = $monto_recargo;
	$values[] = $monto_multa;
	$values[] = $monto_intereses;
	$values[] = $monto_descuento;
	$values[] = $a[1] = $cod_entidad_deposito;
	$values[] = $a[2] = $cod_sucursal_deposito;
	$values[] = $a[3] = $cuenta_bancaria_deposito;
	$values[] = $a[4] = $numero_deposito;
	$values[] = $monto_deposito=$a[1]==0?0:$monto_deposito;
	$fecha_deposito = $fecha_deposito;
	$values[] = "'".$fecha_deposito."'";
	$values[] = $a[5] = $cod_entidad_credito;
	$values[] = $a[6] = $cod_sucursal_credito;
	$values[] = $a[7] = $cuenta_bancaria_credito;
	$values[] = $a[8] = $numero_nota_credito;
	$values[] = $monto_nota_credito= $a[5]==0?0:$monto_nota_credito;
	$fecha_nota_credito = $fecha_nota_credito;
	$values[] = "'".$fecha_nota_credito."'";
	$values[] = $a[9] = $cod_entidad_cheque;
	$values[] = $a[10] = $cod_sucursal_cheque;
	$values[] = $a[11] = $cuenta_bancaria_cheque;
	$values[] = $a[12] = $numero_cheque;
	$values[] = $monto_cheque=$a[9]==0?0:$monto_cheque;
	$fecha_cheque = $fecha_cheque;
	$values[] = "'".$fecha_cheque."'";
	$p1 = $deuda_vigente+$deuda_anterior+$monto_recargo+$monto_multa+$monto_intereses;
	$p2 = $monto_descuento;
	$p2 = $p2>=$p1?0:$p2;
	$monto_efectivo = $p1-$p2;
	$monto_total = $monto_efectivo;
	$monto_efectivo = $monto_efectivo-$monto_deposito-$monto_nota_credito-$monto_cheque;
	$values[] = $monto_efectivo;
	$values[] = 1;
	$values[] = "'".$fecha_registro."'";//fecha_registro;
	$values[] = "'".$username_registro."'";//username_registro;
	$values[] = $ano_anulacion;
	$values[] = $numero_anulacion;
	$values[] = "'".$fecha_anulacion."'";
	$values[] = "'".$username_anulacion."'";
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
	$ano_comprobante = divide_fecha($fecha_comprobante,'ANO');
	$mes_comprobante = divide_fecha($fecha_comprobante,'MES');
	$dia_comprobante = divide_fecha($fecha_comprobante,'DIA');
	$values2[] = $ano_comprobante;
	$values2[] = $mes_comprobante;
	$values2[] = $dia_comprobante;
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
    $aleatorio = rand();
	$condicion_acumulado="cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=".$ano_comprobante." and mes=".$mes_comprobante." and dia=".$dia_comprobante." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar and $aleatorio=$aleatorio";
	$sql_insert        = "INSERT INTO shd900_cobranza_diaria VALUES (".implode(',',$values).");";
    $sql_insert_acumulada = "INSERT INTO shd900_cobranza_acumulada VALUES (".implode(',',$values2).");";
    $sql_update_acumulada = "UPDATE shd900_cobranza_acumulada SET
       deuda_vigente=deuda_vigente+$deuda_vigente,
       deuda_anterior=deuda_anterior+$deuda_anterior,
       monto_recargo=monto_recargo+$monto_recargo,
       monto_multa=monto_multa+$monto_multa,
       monto_intereses=monto_intereses+$monto_intereses,
       monto_descuento=monto_descuento+$monto_descuento,
       cantidad_depositos=cantidad_depositos+$cd,
       monto_depositos=monto_depositos+$monto_deposito,
       cantidad_notas_credito=cantidad_notas_credito+$cnc,
       monto_notas_credito=monto_notas_credito+$monto_nota_credito,
       cantidad_cheques=cantidad_cheques+$cch,
       monto_cheques=monto_cheques+$monto_cheque,
       cantidad_pagos_efectivo=cantidad_pagos_efectivo+$cpe,
       cantidad_descuento=cantidad_descuento+$cmd,
       monto_pagos_efectivo=monto_pagos_efectivo+$monto_efectivo  WHERE ".$condicion_acumulado;
        //$this->shd900_cobranza_numero->execute("BEGIN;");
		$sw = 2;//$this->shd900_cobranza_numero->execute($sql_insert);
		if($sw>1){
		    $c_a = $this->shd900_cobranza_acumulada->findCount($condicion_acumulado);
		    if($c_a==0){
		    	$X1=$this->shd900_cobranza_acumulada->execute($sql_insert_acumulada);
		    	$sql_mostrar .="<br>".$sql_insert_acumulada;
		    }else{
		    	$X1=$this->shd900_cobranza_acumulada->execute($sql_update_acumulada);
		    	$sql_mostrar .="<br>".$sql_update_acumulada;
		    }

		}else{
			$this->set('error', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}

	}//fin foreach data
	echo $sql_mostrar;

}//fin funcion reactualizar_fechas















function eliminar_manualmente($ano_comprobante=null,$numero_comprobante=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();



 	$ano_comprobante    = 2010;
 	$numero_comprobante = 226;


 	$cond="ano_comprobante='".$ano_comprobante."' and numero_comprobante=".$numero_comprobante." and $ca";
 	$data = $this->v_shd900_cobranza_diaria->findAll($cond);
    extract($data[0]['v_shd900_cobranza_diaria']);
    $fecha_comprobante = explode('-',$fecha_comprobante);
	$ano=$fecha_comprobante[0];
	$mes=$fecha_comprobante[1];
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
           $sw2 = $this->shd900_cobranza_numero->execute("UPDATE cfpd03 SET monto_facturado=monto_facturado-$monto_total , monto_cobrado=monto_cobrado-$monto_total WHERE ".$condicion_cfpd03);
           //echo "UPDATE cfpd03 SET monto_facturado=monto_facturado-$monto_total , monto_cobrado=monto_cobrado-$monto_total WHERE ".$condicion_cfpd03;
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

}


    function ingresos_vs_metas($ir = null, $var = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        if ($ir == 'si') {
            $this->layout = "ajax";
            if ($cod_dep == 1) {
                $dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                $this->concatena($dep, 'dependencias');
            } else {
                $dep = $this->arrd05->generateList($conditions = $this->condicion(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                $this->concatena($dep, 'dependencias');
            }
            $ano = $this->ano_ejecucion();
            $this->set('year', $ano);
            $this->set('ir', 'si');
        } else if ($ir == 'no') {
            $this->layout = "pdf";

            if (isset($this->data['organismo']['tipo_peticion'])) {
                $tipo_peticion = $this->data['organismo']['tipo_peticion'];
                if ($tipo_peticion == 1) {
                    $filtro = $this->condicionNDEP();
                } else {
                    if (!empty($this->data['organismo']['cod_dep'])) {
                        $filtro = $this->condicionNDEP() . " and cod_dep=" . $this->data['organismo']['cod_dep'];
                    } else {
                        $filtro = $this->SQLCA();
                    }
                }
            } else {
                $filtro = $this->SQLCA();
            }

           $ano = $this->data['organismo']['ano'];
           $sql = "SELECT * from v_shd999_ingresos_vs_metas where ".$filtro." and ano=$ano";

            $datos = $this->arrd05->execute($sql);
            if ($datos != null) {
                $this->set('datos', $datos);
            } else {
                $this->set('datos', null);
            }
            $this->set('ir', 'no');
        } else if ($ir == 'peticion') {
            $this->layout = "ajax";
            $this->set('peticion', $var);
            $dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            $this->concatena($dep, 'dependencias');
        }
    }



 }
?>